<?php
class Graphmaker {
	
	/*
	 * A simple iterative Breadth-First Search implementation.
	 * http://en.wikipedia.org/wiki/Breadth-first_search
	 * Code usage under the license MIT, open soure
	 * https://github.com/lextoumbourou/bfs-php
	 *
	 * 1. Start with a node, enqueue it and mark it visited.
	 * 2. Do this while there are nodes on the queue:
	 * a. dequeue next node.
	 * b. if it's what we want, return true!
	 * c. search neighbours, if they haven't been visited,
	 * add them to the queue and mark them visited.
	 * 3. If we haven't found our node, return false.
	 *
	 * @returns bool
	 */
	function bfs($graph, $start, $end) {
		$queue = new SplQueue ();
		$queue->enqueue ( $start );
		$visited = array($start);
		while ( $queue->count () > 0 ) {
			$node = $queue->dequeue ();
			// We've found what we want
			if ($node === $end) {
				return true;
			}
			foreach ( $graph [$node] as $neighbour ) {
				if (! in_array ( $neighbour, $visited )) {
					// Mark neighbour visited
					$visited [] = $neighbour;
					// Enqueue node
					$queue->enqueue ( $neighbour );
				}
			}
			;
		}
		return false;
	}
	/*
	 * Same as bfs() except instead of returning a bool, it returns a path.	 
	 * Code usage under the license MIT, open soure
	 * Implemented by enqueuing a path, instead of a node, for each neighbour.
	 *
	 * @returns array or false
	 */
	function bfs_path($graph, $start, $end) {
		$queue = new SplQueue ();
		// Enqueue the path
		$queue->enqueue ( array($start) );
		$visited = array($start);
		while ( $queue->count () > 0 ) {
			$path = $queue->dequeue ();
			// Get the last node on the path
			// so we can check if we're at the end
			$node = $path [sizeof ( $path ) - 1];
			
			if ($node === $end) {
				return $path;
			}
			foreach ( $graph [$node] as $neighbour ) {
				if (! in_array ( $neighbour, $visited )) {
					$visited [] = $neighbour;
					// Build new path appending the neighbour then and enqueue it
					$new_path = $path;
					$new_path [] = $neighbour;
					$queue->enqueue ( $new_path );
				}
			}
			;
		}
		return false;
	}
	
	/*
	 * Code adaptation to this application
	 * Build a undirected graph between form relation 
	 * 
	 */
	
	function build_graph_from_relation($relations){
		$graph = array();
		foreach ($relations as $relation){
			$attr1= $relation->getAttr1();
			$attr2= $relation->getAttr2();
			
			if ($attr1 != '' && $attr2 != ''){
				
				$type1= $relation->getType1()->getId();
				$type2= $relation->getType2()->getId();
				
				//construct node from type_id and its related attribute
				$node1 = $type1."-".$attr1;
				$node2 = $type2."-".$attr2;
				
				//construct undirected relation
				$graph[$node1][] = $node2;
				$graph[$node2][] = $node1;
				
			}
		}
		
		return $graph;
	}
	
	/*
	 * get all nodes related to an template 
	 */
	function get_nodes($relations, $type_id){
		$nodes = array();
		foreach ($relations as $relation){
			$attr1= $relation->getAttr1();
			$attr2= $relation->getAttr2();
				
			if ($attr1 != '' && $attr2 != ''){
				$type1= $relation->getType1()->getId();
				$type2= $relation->getType2()->getId();
		
				//construct node from type_id and its related attribute
				if ($type_id == $type1)
					$node = $type1."-".$attr1;
				else 
					if ($type_id == $type2)
						$node = $type2."-".$attr2;
					
				if (isset($node)){
					if (!in_array($node, $nodes))
						$nodes[] = $node;
				}
			}
		}
		return $nodes;
	}
	
	/*
	 * find relations between two list of nodes, each list corresponds to a type/template
	 * return the relations
	 */
	
	function find_relation($graph, $nodes1, $nodes2){
		$result = array();
		$this->CI =& get_instance();
		$this->CI->load->model('type_model','', TRUE);
		
		foreach ($nodes1 as $node1){
			foreach ($nodes2 as $node2){
				$path = $this->bfs_path($graph,$node1,$node2);
				if ($path != false)
				{
					
					//update found paths
					
					$attr1 = explode('-', $node1);
					$attr2 = explode('-', $node2);
					$result[$attr1[1]] = $attr2[1];
					
					foreach ($path as $n){
						if ($n != $node1){
							$_attr = explode('-', $n);
							$this->CI->type_model->create_or_update_relation($attr1[0], $_attr[0], $attr1[1], $_attr[1]);
						}
					}
					break;
				}
			}
		}
		
		return $result;
	}
}