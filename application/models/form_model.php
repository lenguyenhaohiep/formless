<?php

class Form_model extends CI_Model {

    private $em;

    function __construct() {
        parent::__construct();
        $this->em = $this->doctrine->em;
    }
    
    function get_form($form_id){
    	$form = $this->em->find('Entities\Form', $form_id);
    	return $form; 
    }

    /**
     * Create new form 
     * @param unknown $title
     * @param unknown $type_id
     * @param unknown $status
     * @param unknown $path_form
     */
    function create_or_update_form($form_id = NULL, $title, $type_id, $status, $data) {
        //get type of form
        $type = $this->em->find('Entities\Type', $type_id);

        //create new form
        if ($form_id == null) {
            $form = new Entities\Form;
            $form->setCreatedDate(new DateTime());
            $status = 0;
        } else
            $form = $this->em->find('Entities\Form', $form_id);
        if (isset($form)) {
            $form->setType($type);
            $form->setTitle($title);
            if ($data != "-1")
                $form->setData($data);
            $form->setStatus($status);

            if ($form->getUser() == NULL)
                $form->setUser($this->em->find('Entities\User', $this->ion_auth->get_user_id()));

            $this->em->persist($form);
            $this->em->flush();
            return $form;
        }
    }

    function modify_form($form_id, $user_id) {
        //get the current form
        $form = $this->em->find('Entities\Form', $form_id);
        //get the user who modifies the form
        $user = $this->em->find('Entities\User', $user_id);

        //Create new line in the modify history
        $modify = new Entities\Modify_history;
        $modify->setForm($form);
        $modify->setUser($user);
        $modify->setModifiedDate(new DateTime());

        $this->em->persist($modify);
        $this->em->flush();
    }

    function delete_from($form_id) {
        $form = $this->em->find('Entities\Form', $form_id);
        if (isset($form)) {
            $this->em->remove($form);
            $result = $this->em->flush();
        }
    }

    function send_form($form_id = NULL, $title, $type_id, $status, $data, $from_user_id, $to_user_id, $message) {
        $form = $this->create_or_update_form($form_id, $title, $type_id, $status, $data);

        //get from user
        $from_user = $this->em->find('Entities\User', $from_user_id);
        //get to users
        $to_user = $this->em->find('Entities\User', $to_user_id);

        $sending = new Entities\Send_history;
        $sending->setForm($form);
        $sending->setFromUser($from_user);
        $sending->setToUser($to_user);
        $sending->setMessage($message);
        $sending->setSendDate(new DateTime());
        $sending->setStatus($status);

        $this->em->persist($sending);
        $result = $this->em->flush();

        return $sending;
    }

    function share_form($form_id = NULL, $title, $type_id, $status, $data, $to_user_id, $attr) {
       if ($form_id == NULL)
            $form = $this->create_or_update_form($form_id, $title, $type_id, $status, $data);
        else
            $form = $this->em->find('Entities\Form', $form_id);

        //get to users
        $to_user = $this->em->find('Entities\User', $to_user_id);

        //delete old shared information
        $share = $this->em->getRepository('Entities\Share')->findOneBy(array('form' => $form, 'user' => $to_user));

// 		//update shared form information
        if ($share == null){
            $share = new Entities\Share();
            $share->setForm($form);
            $share->setUser($to_user);
        }
        $share->setAttrs($attr);

        $this->em->persist($share);
        $result = $this->em->flush();

        return $share;
    }

    function get_inbox($user_id) {
// 		$user = $this->em->find('Entities\User',$user_id);		
// 		$inbox = $this->em->getRepository('Entities\Send_history')->findBy(array('status'=>1,'to_user'=>$user),array('send_date'=>'desc'));
        $sql = 'select sum(r.status) count_inbox, r.form_id, f.title, send_date, from_user_id, first_name, last_name, email, t.title t_title
					from (select * from send_history order by send_date desc) r, form f, users u, type t 
					where to_user_id=' . $user_id . ' and from_user_id=u.id and f.id = r.form_id and f.type_id=t.id
					group by r.form_id,from_user_id 
					order by send_date desc';
        $inbox = $this->em->getConnection()->query($sql)->fetchAll();
        return $inbox;
    }

    function get_sent($user_id) {
// 		$user = $this->em->find('Entities\User',$user_id);
// 		$sent = $this->em->getRepository('Entities\Send_history')->findBy(array('status'=>1,'from_user'=>$user),array('send_date'=>'desc'));

        $sql = 'select count(*) count_inbox, r.form_id, f.title, send_date, to_user_id, first_name, last_name, email, t.title t_title
					from (select * from send_history order by send_date desc) r, form f, users u, type t
					where from_user_id=' . $user_id . ' and to_user_id=u.id and f.id = r.form_id and f.type_id=t.id
					group by r.form_id,to_user_id
					order by send_date desc';
        $sent = $this->em->getConnection()->query($sql)->fetchAll();
        return $sent;
    }

    function get_draft($user_id) {
        $user = $this->em->find('Entities\User', $user_id);
        $draft = $this->em->getRepository('Entities\Form')->findBy(array('status' => 0, 'user' => $user), array('created_date' => 'DESC'));
        return $draft;
    }

    function get_all_forms($user_id) {
        $user = $this->em->find('Entities\User', $user_id);
        $forms = $this->em->getRepository('Entities\Form')->findBy(array('user' => $user), array('title' => 'asc'));
        return $forms;
    }

    function get_history_modification($form_id) {
        $form = $this->em->find('Entities\Form', $form_id);

        $history = $this->em->getRepository('Entities\Modify_history')->findBy(array('form' => $form), array('modified_date' => 'desc'));
        return $history;
    }

    function get_all_receivers($form_id) {
        $form = $this->em->find('Entities\Form', $form_id);
    }

    function get_message_by_receiver($form_id, $sender_id, $receiver_id) {
        $to_user = $this->em->find('Entities\User', $receiver_id);
        $from_user = $this->em->find('Entities\User', $sender_id);
    }

    function get_form_by_id($form_id) {
        $form = $this->em->find('Entities\Form', $form_id);
        return $form;
    }

    function get_message_by_email($form_id, $email_contact) {
        $em = $this->doctrine->em;
        $form = $em->find('Entities\Form', $form_id);

        $user = $em->getRepository('Entities\User')->findOneByEmail($this->session->userdata('identity'));
        $user1 = $em->getRepository('Entities\User')->findOneByEmail($email_contact);

        $query = $em->createQuery(
                        'SELECT p
				    FROM Entities\Send_history p
				    WHERE p.form = :form and ((p.from_user = :user1 and p.to_user= :user) or (p.from_user = :user and p.to_user= :user1))'
                )
                ->setParameter('form', $form)
                ->setParameter('user1', $user1)
                ->setParameter('user', $user);

        $send = $query->getResult();
        return $send;
    }

    function get_message($form_id) {
        $em = $this->doctrine->em;
        $form = $em->find('Entities\Form', $form_id);

        $user = $em->getRepository('Entities\User')->findOneByEmail($this->session->userdata('identity'));


        $query = $em->createQuery(
                        'SELECT p
				    FROM Entities\Send_history p
				    WHERE p.form = :form and (p.from_user = :user or p.to_user= :user)'
                )->setParameter('user', $user)->setParameter('form', $form);

        $send = $query->getResult();
        return $send;
    }

    function count_unread() {
        $em = $this->doctrine->em;
        $user = $em->getRepository('Entities\User')->findOneByEmail($this->session->userdata('identity'));
        $inbox = $this->em->getRepository('Entities\Send_history')->findBy(array('status' => 1, 'to_user' => $user), array('send_date' => 'desc'));
        return count($inbox);
    }

    function mark_as_read($form_id, $user_id) {
        $sql = "update send_history set status=0 where form_id = $form_id and to_user_id = $user_id";
        $this->em->getConnection()->query($sql);
    }

    function check_access($form_id) {
        $own = $this->check_own_form($form_id);
        if ($own)
            return TRUE;
        
        $em = $this->doctrine->em;
        $user = $em->getRepository('Entities\User')->findOneByEmail($this->session->userdata('identity'));
        $form = $em->getRepository('Entities\Form')->findOneBy(array('id' => $form_id));
        $send = $em->getRepository('Entities\Send_history')->findBy(array('form'=>$form, 'to_user'=>$user));
        if ($send == NULL){
        	$share = $em->getRepository('Entities\Share')->findBy(array('user'=>$user, 'form' => $form));
        	if ($share != NULL)
        		return TRUE;
            return FALSE;
        }
        if (count($send) > 0)
            return TRUE;
        return FALSE;
    }

    function check_modify($form_id) {
        $em = $this->doctrine->em;

        $user = $em->getRepository('Entities\User')->findOneByEmail($this->session->userdata('identity'));
        $form = $em->getRepository('Entities\Form')->findOneBy(array('id' => $form_id));

        $share = $em->getRepository('Entities\Share')->findBy(array('form' => $form, 'user' => $user));

        if ($share != null) {
            return true;
        }

        return false;
    }

    function check_delete($form_id) {
        $em = $this->doctrine->em;

        $user = $em->getRepository('Entities\User')->findOneByEmail($this->session->userdata('identity'));
        $form = $em->getRepository('Entities\Form')->findOneBy(array('id' => $form_id, 'user' => $user));
        $share = $em->getRepository('Entities\Share')->findBy(array('form' => $form));
        $send = $em->getRepository('Entities\Send_history')->findBy(array('form' => $form));

        return (count($share) <= 0) && (count($send) <= 0);
    }

    function check_own_form($form_id) {
        $em = $this->doctrine->em;

        $user = $em->getRepository('Entities\User')->findOneByEmail($this->session->userdata('identity'));
        $form = $em->getRepository('Entities\Form')->findOneBy(array('id' => $form_id, 'user' => $user));

        if ($form != null) {
            return true;
        }
        return false;
    }

    function get_shared_forms() {
        $em = $this->doctrine->em;

        $user = $em->getRepository('Entities\User')->findOneByEmail($this->session->userdata('identity'));

        $share = $em->getRepository('Entities\Share')->findByUser($user);

        return $share;
    }

    function get_shared_attrs_by_id($form_id) {
        $em = $this->doctrine->em;

        $user = $em->getRepository('Entities\User')->findOneByEmail($this->session->userdata('identity'));
        $form = $em->getRepository('Entities\Form')->findOneBy(array('id' => $form_id));

        $share = $em->getRepository('Entities\Share')->findOneBy(array('user'=>$user, 'form'=>$form));
        return $share;
    }

    function get_share_by_form($form_id) {
        $em = $this->doctrine->em;

        $user = $em->getRepository('Entities\User')->findOneByEmail($this->session->userdata('identity'));
        $form = $em->getRepository('Entities\Form')->findOneBy(array('id' => $form_id, 'user' => $user));

        if ($form != null) {
            $share = $em->getRepository('Entities\Share')->findBy(array('form' => $form));
            return $share;
        }
        return null;
    }

}
