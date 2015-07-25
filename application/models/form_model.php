<?php
/**
 * Name: Form model
 * @author Hiep Le
 * @abstract This class is to access form information and its related information
 * 
 * Description: Form management and its related information such as : sharing information, sending and receiving, signing
 */
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\OptimisticLockException;
class Form_model extends CI_Model {
	
	/**
	 *
	 * @var Entity Manager of Doctrine
	 */
	private $em;
	
	/**
	 * Construction
	 */
	function __construct() {
		parent::__construct ();
		$this->em = $this->doctrine->em;
	}
	
	/**
	 * Get a form by its id
	 * 
	 * @param {int} $form_id        	
	 * @return \Entities\Form
	 */
	function get_form($form_id) {
		$form = $this->em->find ( 'Entities\Form', $form_id );
		return $form;
	}
	
	/**
	 * Create or update a form with the info given
	 * @param string $form_id
	 * @param string $title
	 * @param int $type_id
	 * @param int $status status of a form (=0 draft, =1 sent)
	 * @param string $data Json data of the form with the data filled included
	 * @param int $expectedVersion to control the concurrency with the versioned entity
	 * @return \Entities\Form
	 */
	function create_or_update_form($form_id = NULL, $title, $type_id, $status, $data, $expectedVersion = null) {
		try {
			
			// get type of form
			$type = $this->em->find ( 'Entities\Type', $type_id );
			// create new form
			if ($form_id == null) {
				$form = new Entities\Form ();
				$form->setCreatedDate ( new DateTime () );
				$status = 0;
			} else
				/**
				 * User lock mode to verify that at a moment of time, only one modification is accepted.
				 */
				$form = $this->em->find ( 'Entities\Form', $form_id, LockMode::OPTIMISTIC, ( int ) $expectedVersion );
			if (isset ( $form )) {
				$form->setType ( $type );
				$form->setTitle ( $title );
				if ($data != "-1")
					$form->setData ( $data );
				$form->setStatus ( $status );
				
				if ($form_id == null)
					$form->setVersion ( 1 );
				else
					$form->setVersion ( $expectedVersion + 1 );
				$form->setVersion ( 1 );
				
				if ($form->getUser () == NULL)
					$form->setUser ( $this->em->find ( 'Entities\User', $this->ion_auth->get_user_id () ) );
				$this->em->persist ( $form );
				$this->em->flush ();
				
				return $form;
			}
		} catch ( OptimisticLockException $e ) {
			echo $e->getMessage ();
			return "Sorry, but someone else has already changed this entity. Please apply the changes again!";
		}
	}
	
	/**
	 * Update the modification history of a form
	 * 
	 * @param {int} $form_id        	
	 * @param {int} $user_id The id of the user who changed the form
	 */
	function modify_form($form_id, $user_id) {
		// get the current form
		$form = $this->em->find ( 'Entities\Form', $form_id );
		// get the user who modifies the form
		$user = $this->em->find ( 'Entities\User', $user_id );
		
		// Create new line in the modify history
		$modify = new Entities\Modify_history ();
		$modify->setForm ( $form );
		$modify->setUser ( $user );
		$modify->setModifiedDate ( new DateTime () );
		
		$this->em->persist ( $modify );
		$this->em->flush ();
	}
	
	/**
	 * Delete a form by its id
	 * 
	 * @param {int} $form_id        	
	 */
	function delete_from($form_id) {
		$form = $this->em->find ( 'Entities\Form', $form_id );
		if (isset ( $form )) {
			$this->em->remove ( $form );
			$result = $this->em->flush ();
		}
	}
	
	/**
	 * Send messages and form to others users
	 * 
	 * @param string $form_id
	 * @param string $title
	 * @param int $type_id
	 * @param int $status
	 * @param string $data
	 * @param int $version
	 * @param int $from_user_id
	 * @param int $to_user_id
	 * @param string $message
	 * @return NULL|\Entities\Send_history
	 */
	function send_form($form_id = NULL, $title, $type_id, $status, $data, $version, $from_user_id, $to_user_id, $message) {
		// $form = $this->create_or_update_form($form_id, $title, $type_id, $status, $data, $version);
		if ($form_id == null)
			return null;
		
		$form = $this->em->find ( 'Entities\User', $form_id );
		if ($form != null) {
			// get from user
			$from_user = $this->em->find ( 'Entities\User', $from_user_id );
			// get to users
			$to_user = $this->em->find ( 'Entities\User', $to_user_id );
			
			$sending = new Entities\Send_history ();
			$sending->setForm ( $form );
			$sending->setFromUser ( $from_user );
			$sending->setToUser ( $to_user );
			$sending->setMessage ( $message );
			$sending->setSendDate ( new DateTime () );
			$sending->setStatus ( $status );
			
			$this->em->persist ( $sending );
			$result = $this->em->flush ();
			
			return $sending;
		}
		return null;
	}
	
	/**
	 * Share a form to others users
	 * 
	 * @param int $form_id
	 * @param string $title
	 * @param int $type_id
	 * @param int $status
	 * @param string $data
	 * @param int $version
	 * @param int $to_user_id
	 * @param int $attr
	 * @return NULL|\Entities\Share
	 */
	function share_form($form_id = NULL, $title, $type_id, $status, $data, $version, $to_user_id, $attr) {
		if ($form_id == NULL)
			return null;
			// $form = $this->create_or_update_form($form_id, $title, $type_id, $status, $data, $version);
		else
			$form = $this->em->find ( 'Entities\Form', $form_id );
		
		if ($form == null) {
			return null;
		}
		
		// get to users
		$to_user = $this->em->find ( 'Entities\User', $to_user_id );
		
		// delete old shared information
		$share = $this->em->getRepository ( 'Entities\Share' )->findOneBy ( array (
				'form' => $form,
				'user' => $to_user 
		) );
		
		// update shared form information
		if ($share == null) {
			$share = new Entities\Share ();
			$share->setForm ( $form );
			$share->setUser ( $to_user );
		}
		$share->setAttrs ( $attr );
		
		$this->em->persist ( $share );
		$result = $this->em->flush ();
		
		return $share;
	}
	
	/**
	 * Get the form labelled as inbox by user id
	 * 
	 * @param int $user_id
	 * @return array of ids form
	 */
	function get_inbox($user_id) {
		$sql = 'select sum(r.status) count_inbox, r.form_id, f.title, send_date, from_user_id, first_name, last_name, email, t.title t_title
					from (select * from send_history order by send_date desc) r, form f, users u, type t 
					where to_user_id=' . $user_id . ' and from_user_id=u.id and f.id = r.form_id and f.type_id=t.id
					group by r.form_id,from_user_id 
					order by send_date desc';
		$inbox = $this->em->getConnection ()->query ( $sql )->fetchAll ();
		return $inbox;
	}
	
	/**
	 * Get the form labelled as sent by user id
	 * 
	 * @param int $user_id
	 * @return array of ids form
	 */
	function get_sent($user_id) {
		$sql = 'select count(*) count_inbox, r.form_id, f.title, send_date, to_user_id, first_name, last_name, email, t.title t_title
					from (select * from send_history order by send_date desc) r, form f, users u, type t
					where from_user_id=' . $user_id . ' and to_user_id=u.id and f.id = r.form_id and f.type_id=t.id
					group by r.form_id,to_user_id
					order by send_date desc';
		$sent = $this->em->getConnection ()->query ( $sql )->fetchAll ();
		return $sent;
	}
	
	/**
	 * Get the form labelled as draft, it means that all the forms are not sent
	 * 
	 * @param int $user_id
	 * @return Array of Entities\Form
	 */
	function get_draft($user_id) {
		$user = $this->em->find ( 'Entities\User', $user_id );
		$draft = $this->em->getRepository ( 'Entities\Form' )->findBy ( array (
				'status' => 0,
				'user' => $user 
		), array (
				'created_date' => 'DESC' 
		) );
		return $draft;
	}
	
	/**
	 * Get all forms belong to the given user
	 * 
	 * @param int $user_id
	 * @return Array of \Entities\Form
	 */
	function get_all_forms($user_id) {
		$user = $this->em->find ( 'Entities\User', $user_id );
		$forms = $this->em->getRepository ( 'Entities\Form' )->findBy ( array (
				'user' => $user 
		), array (
				'title' => 'asc' 
		) );
		return $forms;
	}
	
	/**
	 * Get the modification history of a form
	 * 
	 * @param int $form_id
	 * @return Array of Entities\Modify_history
	 */
	function get_history_modification($form_id) {
		$form = $this->em->find ( 'Entities\Form', $form_id );
		
		$history = $this->em->getRepository ( 'Entities\Modify_history' )->findBy ( array (
				'form' => $form 
		), array (
				'modified_date' => 'desc' 
		) );
		return $history;
	}
	
	
	/**
	 * Not completely implemented
	 * @param unknown $form_id
	 */
	function get_all_receivers($form_id) {
		$form = $this->em->find ( 'Entities\Form', $form_id );
	}
	
	/**
	 * Get messages exchanged between two users by the given form
	 * 
	 * @param int $form_id
	 * @param int $sender_id the id of the sender
	 * @param int $receiver_id the id of the receiver 
	 */
	function get_message_by_receiver($form_id, $sender_id, $receiver_id) {
		$to_user = $this->em->find ( 'Entities\User', $receiver_id );
		$from_user = $this->em->find ( 'Entities\User', $sender_id );
	}
	
	/**
	 * Get a form by id given
	 * 
	 * @param int $form_id
	 * @return \Entities\Form
	 */
	function get_form_by_id($form_id) {
		$form = $this->em->find ( 'Entities\Form', $form_id );
		return $form;
	}
	
	/**
	 * Get messages exchanged by current user and other email given by the given form
	 * 
	 * @param int $form_id
	 * @param string $email_contact
	 * @return array of message
	 */
	function get_message_by_email($form_id, $email_contact) {
		$em = $this->doctrine->em;
		$form = $em->find ( 'Entities\Form', $form_id );
		
		$user = $em->getRepository ( 'Entities\User' )->findOneByEmail ( $this->session->userdata ( 'identity' ) );
		$user1 = $em->getRepository ( 'Entities\User' )->findOneByEmail ( $email_contact );
		
		$query = $em->createQuery ( 'SELECT p
				    FROM Entities\Send_history p
				    WHERE p.form = :form and ((p.from_user = :user1 and p.to_user= :user) or (p.from_user = :user and p.to_user= :user1))' )->setParameter ( 'form', $form )->setParameter ( 'user1', $user1 )->setParameter ( 'user', $user );
		
		$send = $query->getResult ();
		return $send;
	}
	
	/**
	 * Get message written by the current users by the given form
	 * @param int $form_id
	 * @return array of message
	 */
	function get_message($form_id) {
		$em = $this->doctrine->em;
		$form = $em->find ( 'Entities\Form', $form_id );
		
		$user = $em->getRepository ( 'Entities\User' )->findOneByEmail ( $this->session->userdata ( 'identity' ) );
		
		$query = $em->createQuery ( 'SELECT p
				    FROM Entities\Send_history p
				    WHERE p.form = :form and (p.from_user = :user or p.to_user= :user)' )->setParameter ( 'user', $user )->setParameter ( 'form', $form );
		
		$send = $query->getResult ();
		return $send;
	}
	
	/**
	 * Count the number of forms lablled as inbox
	 */
	function count_unread() {
		$em = $this->doctrine->em;
		$user = $em->getRepository ( 'Entities\User' )->findOneByEmail ( $this->session->userdata ( 'identity' ) );
		$inbox = $this->em->getRepository ( 'Entities\Send_history' )->findBy ( array (
				'status' => 1,
				'to_user' => $user 
		), array (
				'send_date' => 'desc' 
		) );
		return count ( $inbox );
	}
	
	/**
	 * Mark a for as read by changing status = 0 when the user accesses the comming form in his mailbox
	 * @param int $form_id
	 * @param int $user_id
	 */
	function mark_as_read($form_id, $user_id) {
		$sql = "update send_history set status=0 where form_id = $form_id and to_user_id = $user_id";
		$this->em->getConnection ()->query ( $sql );
	}
	
	/**
	 * Check whether the current user has the right to access (read only) the given form
	 * @param int $form_id
	 * @return boolean
	 */
	function check_access($form_id) {
		$own = $this->check_own_form ( $form_id );
		if ($own)
			return TRUE;
		
		$em = $this->doctrine->em;
		$user = $em->getRepository ( 'Entities\User' )->findOneByEmail ( $this->session->userdata ( 'identity' ) );
		$form = $em->getRepository ( 'Entities\Form' )->findOneBy ( array (
				'id' => $form_id 
		) );
		$send = $em->getRepository ( 'Entities\Send_history' )->findBy ( array (
				'form' => $form,
				'to_user' => $user 
		) );
		if ($send == NULL) {
			$share = $em->getRepository ( 'Entities\Share' )->findBy ( array (
					'user' => $user,
					'form' => $form 
			) );
			if ($share != NULL)
				return TRUE;
			return FALSE;
		}
		if (count ( $send ) > 0)
			return TRUE;
		return FALSE;
	}
	
	/**
	 * Check if the current user has the right to modify a form
	 * @param int $form_id
	 * @return boolean
	 */
	function check_modify($form_id) {
		$em = $this->doctrine->em;
		
		$user = $em->getRepository ( 'Entities\User' )->findOneByEmail ( $this->session->userdata ( 'identity' ) );
		$form = $em->getRepository ( 'Entities\Form' )->findOneBy ( array (
				'id' => $form_id 
		) );
		
		$share = $em->getRepository ( 'Entities\Share' )->findBy ( array (
				'form' => $form,
				'user' => $user 
		) );
		
		if ($share != null) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Check if the current user has the right to delete a form
	 * @param int $form_id
	 * @return boolean
	 */
	function check_delete($form_id) {
		$em = $this->doctrine->em;
		
		$user = $em->getRepository ( 'Entities\User' )->findOneByEmail ( $this->session->userdata ( 'identity' ) );
		$form = $em->getRepository ( 'Entities\Form' )->findOneBy ( array (
				'id' => $form_id,
				'user' => $user 
		) );
		$share = $em->getRepository ( 'Entities\Share' )->findBy ( array (
				'form' => $form 
		) );
		$send = $em->getRepository ( 'Entities\Send_history' )->findBy ( array (
				'form' => $form 
		) );
		
		return (count ( $share ) <= 0) && (count ( $send ) <= 0);
	}
	
	/**
	 * Check if the current user owns a form
	 * @param int $form_id
	 * @return boolean
	 */
	function check_own_form($form_id) {
		$em = $this->doctrine->em;
		
		$user = $em->getRepository ( 'Entities\User' )->findOneByEmail ( $this->session->userdata ( 'identity' ) );
		$form = $em->getRepository ( 'Entities\Form' )->findOneBy ( array (
				'id' => $form_id,
				'user' => $user 
		) );
		
		if ($form != null) {
			return true;
		}
		return false;
	}
	
	/**
	 * Get all the form that the current user is shared
	 * 
	 * @return Array of \Entities\Share
	 */
	function get_shared_forms() {
		$em = $this->doctrine->em;
		
		$user = $em->getRepository ( 'Entities\User' )->findOneByEmail ( $this->session->userdata ( 'identity' ) );
		
		$share = $em->getRepository ( 'Entities\Share' )->findByUser ( $user );
		
		return $share;
	}
	
	/**
	 * Get the form that is shared to the current users
	 * 
	 * @param int $form_id
	 * @return \Entities\Share
	 */
	function get_shared_attrs_by_id($form_id) {
		$em = $this->doctrine->em;
		
		$user = $em->getRepository ( 'Entities\User' )->findOneByEmail ( $this->session->userdata ( 'identity' ) );
		$form = $em->getRepository ( 'Entities\Form' )->findOneBy ( array (
				'id' => $form_id 
		) );
		
		$share = $em->getRepository ( 'Entities\Share' )->findOneBy ( array (
				'user' => $user,
				'form' => $form 
		) );
		return $share;
	}
	
	/**
	 * Get the form that is shared to the current users
	 *
	 * @param int $form_id
	 * @return \Entities\Share
	 */
	function get_share_by_form($form_id) {
		$em = $this->doctrine->em;
		
		$user = $em->getRepository ( 'Entities\User' )->findOneByEmail ( $this->session->userdata ( 'identity' ) );
		$form = $em->getRepository ( 'Entities\Form' )->findOneBy ( array (
				'id' => $form_id,
				'user' => $user 
		) );
		
		if ($form != null) {
			$share = $em->getRepository ( 'Entities\Share' )->findBy ( array (
					'form' => $form 
			) );
			return $share;
		}
		return null;
	}
	
	/**
	 * Sign a form
	 * 
	 * @param int $form_id
	 * @param string $signed_data
	 * @return \Entities\Sign
	 */
	function sign($form_id, $signed_data) {
		$em = $this->doctrine->em;
		
		$user = $em->getRepository ( 'Entities\User' )->findOneByEmail ( $this->session->userdata ( 'identity' ) );
		$form = $em->getRepository ( 'Entities\Form' )->findOneBy ( array (
				'id' => $form_id 
		) );
		$sign = $em->getRepository ( 'Entities\Sign' )->findOneBy ( array (
				'user' => $user,
				'form' => $form 
		) );
		if ($sign == null)
			$sign = new Entities\Sign ();
		$sign->setForm ( $form );
		$sign->setUser ( $user );
		$sign->setData ( $signed_data );
		
		$em->persist ( $sign );
		$em->flush ();
		
		return $sign;
	}
	
	/**
	 * 
	 * @param unknown $form_id
	 * @param string $signer
	 * @return boolean
	 */
	function check_signed($form_id, $signer = null) {
		$em = $this->doctrine->em;
		$form = $em->getRepository ( 'Entities\Form' )->findOneBy ( array (
				'id' => $form_id 
		) );
		
		if (isset ( $signer )) {
			$sign = $em->getRepository ( 'Entities\Sign' )->findBy ( array (
					'form' => $form 
			) );
			if ($sign == null)
				return false;
			return count ( $sign ) > 0;
		}
		
		$user = $em->getRepository ( 'Entities\User' )->findOneByEmail ( $this->session->userdata ( 'identity' ) );
		$sign = $em->getRepository ( 'Entities\Sign' )->findOneBy ( array (
				'user' => $user,
				'form' => $form 
		) );
		return ($sign != null);
	}
	
	/**
	 * Get all signatures of a given form
	 * @param int $form_id
	 * @return Array of \Entities\Sign
	 */
	function get_all_signature($form_id) {
		$em = $this->doctrine->em;
		$form = $em->getRepository ( 'Entities\Form' )->findOneBy ( array (
				'id' => $form_id 
		) );
		$sign = $em->getRepository ( 'Entities\Sign' )->findBy ( array (
				'form'=>$form));
        return $sign;
    }

}
