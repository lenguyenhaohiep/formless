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
			$type->setPathTemplate($template);
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
	
}