<?php
/**
 * 
 * @author hle
 *
 */
class Type_model extends CI_Model{	
	
	/**
	 * 
	 * @return list of group, each group contains a list of type
	 */
	function getAllTypes(){
		$em = $this->doctrine->em;
		
		$result = array();
		//get all group types
		$groups_type = $em->getRepository('Entities\Group_type')->findAll();
		
		foreach ($groups_type as $group_type){
			$type = $em->getRepository('Entities\Type')->findBy(array('group_type'=>$group_type));
			$result[] = array($group_type->getTitle(),$type);
		}
		return $result;
	}
	
	function get_type($type_id){
		$em = $this->doctrine->em;
		return $em->find('Entities\Type',$type_id);
	}
	
	function create_file($path, $content){
		
	}
	
	function update_template($type_id, $template){
		$em = $this->doctrine->em;
		$type = $em->find('Entities\Type', $type_id);
		
		if ($type!=null){
			$type->setData($template);
			$em->persist($type);
			$em->flush();
		}
		
		return $type;
	}
        
        function discard_template($type_id){
            $em = $this->doctrine->em;
            $type = $em->find('Entities\Type', $type_id);
            $form = $em->getRepository('Entities\Form')->findByType($type);
            if (count ($form) > 0)
                return FALSE;
            return TRUE;
        }
        
        function create_or_update_relation($type_id1, $type_id2, $attr1, $attr2) {
        $em = $this->doctrine->em;
        $type1 = $em->find('Entities\Type', $type_id1);
        $type2 = $em->find('Entities\Type', $type_id2);
        $r = $em->getRepository('Entities\Form_relation')->findOneBy(array('attr1' => $attr1, 'type1' => $type1, 'type2' => $type2));
        if ($r == NULL){
            $r = $em->getRepository('Entities\Form_relation')->findOneBy(array('attr2' => $attr1, 'type2' => $type1, 'type1' => $type2));

            if ($r == NULL){
                $r = new Entities\Form_relation;
                $r->setAttr1($attr1);
                $r->setAttr2($attr2);
                $r->setType1($type1);
                $r->setType2($type2);
            }else{
                $r->setAttr1($attr2);
            }
        }
        else{
            $r->setAttr2($attr2);
        }
        $em->persist($r);
        $em->flush();
    }
    
    function get_relation($type_id1, $type_id2){
        $em = $this->doctrine->em;
        $type1 = $em->find('Entities\Type', $type_id1);
        $type2 = $em->find('Entities\Type', $type_id2);
        $r = $em->getRepository('Entities\Form_relation')->findBy(array('type1' => $type1, 'type2' => $type2));
        return $r;
        
    }

}