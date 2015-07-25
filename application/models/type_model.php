<?php
/**
 * Name: Type model 
 * @author Hiep Le
 * @abstract This class is to access the type (template)
 *
 */
class Type_model extends CI_Model {
	
	/**
	 * Get all type of the system
	 * Each type belongs to a group type
	 * 
	 * @return list of group, each group contains a list of type
	 */
	function getAllTypes() {
		$em = $this->doctrine->em;
		
		$result = array ();
		// get all group types
		$groups_type = $em->getRepository ( 'Entities\Group_type' )->findAll ();
		
		foreach ( $groups_type as $group_type ) {
			$type = $em->getRepository ( 'Entities\Type' )->findBy ( array (
					'group_type' => $group_type 
			) );
			$result [] = array (
					$group_type->getTitle (),
					$type 
			);
		}
		return $result;
	}
	
	
	/**
	 * Get a type by its id
	 * 
	 * @param {int} $type_id        	
	 * @return \Entities\Type
	 */
	function get_type($type_id) {
		$em = $this->doctrine->em;
		return $em->find ( 'Entities\Type', $type_id );
	}
	
	
	/**
	 * Update a template for a type given
	 * 
	 * @param {int} $type_id        	
	 * @param {string} $template
	 *        	template of the type under the form of JSON
	 * @return \Entities\Type
	 */
	function update_template($type_id, $template) {
		$em = $this->doctrine->em;
		$type = $em->find ( 'Entities\Type', $type_id );
		
		if ($type != null) {
			$type->setData ( $template );
			$em->persist ( $type );
			$em->flush ();
		}
		
		return $type;
	}
	
	
	/**
	 * Discard a template by type id given
	 * @param {int} $type_id
	 * @return boolean
	 */
	function discard_template($type_id) {
		$em = $this->doctrine->em;
		$type = $em->find ( 'Entities\Type', $type_id );
		$form = $em->getRepository ( 'Entities\Form' )->findByType ( $type );
		if (count ( $form ) > 0)
			return FALSE;
		return TRUE;
	}
	
	
	/**
	 * Create or update relation between two templates by their attribute
	 * @param {int} $type_id1
	 * @param {int} $type_id2
	 * @param {string} $attr1 name of the attribute in the type with id $type_id1
	 * @param {string} $attr2 name of the attribute in the type with id $type_id1
	 */
	function create_or_update_relation($type_id1, $type_id2, $attr1, $attr2) {
		$em = $this->doctrine->em;
		$type1 = $em->find ( 'Entities\Type', $type_id1 );
		$type2 = $em->find ( 'Entities\Type', $type_id2 );
		
		
		/**
		 * We should check two side of the relation because only one side is store in database
		 * Ex: There is a relation between type1[attr1] = type2[attr2]
		 * it means that type2[attr2] = type1[attr1]
		 * But the tube in database is (type1, type2, attr1, attr2)
		 * Therefore, we should check two sides
		 */
		$r = $em->getRepository ( 'Entities\Form_relation' )->findOneBy ( array (
				'attr1' => $attr1,
				'type1' => $type1,
				'type2' => $type2 
		) );
		if ($r == NULL) {
			$r = $em->getRepository ( 'Entities\Form_relation' )->findOneBy ( array (
					'attr2' => $attr1,
					'type2' => $type1,
					'type1' => $type2 
			) );
			
			if ($r == NULL) {
				$r = new Entities\Form_relation ();
				$r->setAttr1 ( $attr1 );
				$r->setAttr2 ( $attr2 );
				$r->setType1 ( $type1 );
				$r->setType2 ( $type2 );
			} else {
				$r->setAttr1 ( $attr2 );
			}
		} else {
			$r->setAttr2 ( $attr2 );
		}
		$em->persist ( $r );
		$em->flush ();
	}
	
	
	/**
	 * Get relations between two types
	 * @param {int} $type_id1
	 * @param {int} $type_id2
	 * @return \Entities\Form_relation
	 */
	function get_relation($type_id1, $type_id2) {
		$em = $this->doctrine->em;
		$type1 = $em->find ( 'Entities\Type', $type_id1 );
		$type2 = $em->find ( 'Entities\Type', $type_id2 );
		$r = $em->getRepository ( 'Entities\Form_relation' )->findBy ( array (
				'type1' => $type1,
				'type2' => $type2 
		) );
		return $r;
	}
	
	
	/**
	 * Get all relations stored in the database
	 * @return List of \Entities\Form_relation
	 */
	function get_all_relation() {
		$em = $this->doctrine->em;
		$relation = $em->getRepository ( 'Entities\Form_relation' )->findAll ();
		return $relation;
	}
}