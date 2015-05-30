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
	
}