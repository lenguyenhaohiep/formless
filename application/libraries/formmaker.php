<?php
class Formmaker {
	private $html_template;
	private $count;
	function Formmaker() {
		$this->html_template = '<form id="data-form" class = "form-auto-generate"><table>';
		$this->count = 0;
	}
	function get_value_from_xml($xml) {
	}
	
	function get_data_form_json($data_json){
		$data= json_decode($data_json, true);
		$values = array();
		foreach ($data['fields'] as $item){
			$id = $item['cid'];
			$val = $item['value'];
			$type = $item['field_type'];
			
			if ($type == 'sign' || $type == 'file'){
				$img='';
				if (!is_array($val))
					$val = array($val);
				foreach ($val as $v)
					$img .= "<img src=$v class='image-select'/>";
				$values[$id] = $img;
			}else 
				if ($type == 'checkboxes'){
				$p='';
				foreach ($val as $v)
					$p .= "<span>$v</span>, ";
				$values[$id] = $p;
			}else
				$values[$id]=$val;
		}

		return $values;		
	}
	
	//fill data to template
	function fill_data($json, $data) {
		$_data = array ();
		foreach ( $data as $d ) {
			if (isset ( $_data [$d ['name']] )) {
				if (! is_array ( $_data [$d ['name']] )) {
					$temp = $_data [$d ['name']];
					$_data [$d ['name']] = array ();
					$_data [$d ['name']] [] = $temp;
				}
				$_data [$d ['name']] [] = $d ['value'];
			} else {
				$_data [$d ['name']] = $d ["value"];
			}
		}
				
		$_json = json_decode ( $json, true );
		$j = 0;
		foreach ( $_json ['fields'] as $component ) {
			$type = $component ['field_type'];
			$id = $component ['cid'];
			switch ($type) {
				case 'checkboxes' :
				case 'file' :
					if (isset ( $_data [$id] )) {
						if (is_array ( $_data [$id] ))
							$_json ['fields'] [$j] ['value'] = $_data [$id];
						else
							$_json ['fields'] [$j] ['value'] = array (
									$_data [$id] 
							);
							
					} else {
						$_json ['fields'] [$j] ['value'] = array ();
					}
					break;
				default :
					if (isset ( $_data [$id] ))
						$_json ['fields'] [$j] ['value'] = $_data [$id];
					else
						$_json ['fields'] [$j] ['value'] = null;
					break;
			}
			$j ++;
		}
		return json_encode ( $_json );
	}
	function get_relation($relation) {
		$result = array ();
		foreach ( $relation as $r ) {
			$id1 = strval ( $r->getType1 ()->getId () );
			$id2 = strval ( $r->getType2 ()->getId () );
			$attr1 = $r->getAttr1 ();
			$attr2 = $r->getAttr2 ();
			$result [$id1] [$id2] [$attr1] = $attr2;
			$result [$id2] [$id1] [$attr2] = $attr1;
		}
		return $result;
	}
	function get_relation_identical($type_id, $json) {
		$result = array ();
		
		$_json = json_decode ( $json, true );
		$template = $_json['fields'];
		if (count ( $template ) > 0) {
			foreach ( $template as $component ) {
				$attr1 = $component ['cid'];
				$type = $component['field_type'];
				if ($type != 'header' && $type != 'section')
				$result [$type_id] [$type_id] [$attr1] = $attr1;
			}
		}
		return $result;
	}
	function get_attribute_from_json($json, $data = NULL) {
		$result = array ();
		$template = json_decode ( $json, true );
		$template = $template['fields'];
		$count = 0;
		foreach ( $template as $component ) {
			$type = $component ['field_type'];
			if ($type != 'header' && $type != 'section')
				$result [] = array (
						$component ['cid'] => $component ['label'] 
				);
		}
		return $result;
	}
	function generate_from_json($json) {
		$template = json_decode ( $json, true );
		$template = $template['fields'];
		if (count ( $template ) > 0) {
			foreach ( $template as $component ) {
				$type = $component ['field_type'];
				switch ($type) {
					case 'header' :
						$this->html_template .= $this->generate_header ( $component );
						break;
					case 'text' :
						$this->html_template .= $this->generate_text ( $component );
						break;
					case 'paragraph' :
						$this->html_template .= $this->generate_paragraph ( $component );
						break;
					case 'checkboxes' :
						$this->html_template .= $this->generate_checkboxes ( $component );
						break;
					case 'radio' :
						$this->html_template .= $this->generate_radio ( $component );
						break;
					case 'date' :
						$this->html_template .= $this->generate_date ( $component );
						break;
					case 'dropdown' :
						$this->html_template .= $this->generate_dropdown ( $component );
						break;
					case 'number' :
						$this->html_template .= $this->generate_number ( $component );
						break;
					case 'email' :
						$this->html_template .= $this->generate_email ( $component );
						break;
					case 'file' :
						$this->html_template .= $this->generate_file ( $component );
						break;
					case 'sign' :
						$this->html_template .= $this->generate_sign ( $component );
						break;
					case 'section' :
						$this->html_template .= $this->generate_section ( $component );
						break;
					default :
						break;
				}
			}
		} else {
			$this->html_template = 'No template available';
		}
		
		return $this->html_template . "</table></form>";
	}
	function check_require($true) {
		if ($true == true)
			return 'required';
		return '';
	}
	function text_require($true) {
		if ($true == true)
			return '<span class="field-required">*</span>';
		return '';
	}
	function generate_text($component) {
		// {"label":"Name","field_type":"text","required":true,"field_options":{"size":"small"},"cid":"c22"}
		return $this->generate_html5_type ( $component, $type = 'text', '' );
	}
	function generate_html5_type($component, $type, $option) {
		$id = $component ['cid'];
		$require = $this->check_require ( $component ['required'] );
		$label = $component ['label'];
		$require_text = $this->text_require ( $component ['required'] );
		
		// Check the value of the component
		if (isset ( $component ['value'] ))
			$value = $component ['value'];
		else
			$value = '';
			
			// if input[type=file]
		if ($type == 'file') {
			if ($value == '')
				$value = array ();
			if (! is_array ( $value ))
				$value = array (
						$value 
				);
			$data = "<div id='$id-data'>";
			$j = 0;
			foreach ( $value as $img ) {
				$id_img_data = "d" . $id . "-" . $j;
				$data .= "<div class='img-data' id='" . $id_img_data . "-div' ><img onclick='view_image(this)' src='" . $img . "' class='image-select' />";
				$data .= "<button class='btn btn-danger select-file' type='button' onclick='delete_img(\"$id_img_data-div\");'>Delete</button>";
				$data .= "<input type='checkbox' checked name='$id' value='$img' style='display: none'></div>";
				
				$j ++;
			}
			
			$data .= "</div>";
			$name = "";
			$html = "<tr><td><label for='$id'>$label $require_text</label></td><td><label for='$id' class='btn btn-success'>Select image files</label><input class='hidden' id='$id' $name type='$type' $require $option />$data</td></tr>";
		} else {
			// input[type= text, date, number, email]
			$data = '';
			$name = "name = '$id'";
			$html = "<tr><td><label for='$id'>$label $require_text</label></td><td><input id='$id' $name type='$type' value='$value' $require $option/>$data</td></tr>";
		}
		
		return $html;
	}
	function generate_paragraph($component) {
		$id = $component ['cid'];
		$require = $this->check_require ( $component ['required'] );
		$label = $component ['label'];
		$require_text = $this->text_require ( $component ['required'] );
		
		if (isset ( $component ['value'] ))
			$value = $component ['value'];
		else
			$value = '';
		
		$html = "<tr><td><label for='$id'>$label $require_text</label></td><td><textarea id='$id' name='$id' $require>$value</textarea></td></tr>";
		return $html;
	}
	function generate_header($component) {
		$html = "<tr><td colspan='2'><center> <h3 id='" . $component ['cid'] . "'>" . $component ['label'] . "</h3> </center></td></tr>";
		return $html;
	}
	function generate_checkboxes($component) {
		$id = $component ['cid'];
		$require = $this->check_require ( $component ['required'] );
		$label = $component ['label'];
		$require_text = $this->text_require ( $component ['required'] );
		
		$html = "<tr><td><label for='$id'>$label $require_text</label></td><td>";
		
		$options = $component ['field_options'] ['options'];
		
		if (isset ( $component ['value'] ))
			$value = $component ['value'];
		else
			$value = array ();
		
		foreach ( $options as $opt ) {
			$val = $opt ['label'];
			
			if (in_array ( $val, $value ))
				$check = 'checked';
			else
				$check = '';
			
			$html .= "<input id='$id' type='checkbox' name='$id' value='$val' $check>$val<br/>";
		}
		
		$html .= "</td></tr>";
		return $html;
	}
	function generate_radio($component) {
		$id = $component ['cid'];
		$require = $this->check_require ( $component ['required'] );
		$label = $component ['label'];
		$require_text = $this->text_require ( $component ['required'] );
		
		$html = "<tr><td><label for='$id'>$label $require_text</label></td><td>";
		
		$options = $component ['field_options'] ['options'];
		if (isset ( $component ['value'] ))
			$value = $component ['value'];
		else
			$value = '';
		
		foreach ( $options as $opt ) {
			$val = $opt ['label'];
			if ($value == $val)
				$check = 'checked';
			else
				$check = '';
			
			$html .= "<input id='$id' type='radio' name='$id' value='$val' $check>$val<br/>";
		}
		
		$html .= "</td></tr>";
		
		return $html;
	}
	function generate_date($component) {
		return $this->generate_html5_type ( $component, $type = 'date', '' );
	}
	function generate_dropdown($component) {
		$id = $component ['cid'];
		$require = $this->check_require ( $component ['required'] );
		$label = $component ['label'];
		$require_text = $this->text_require ( $component ['required'] );
		
		$html = "<tr><td><label for='$id'>$label $require_text</label></td><td><select id='$id' name='$id' $require>";
		
		$options = $component ['field_options'] ['options'];
		
		foreach ( $options as $opt ) {
			$val = $opt ['label'];
			$html .= "<option type='checkbox' value='$val'>$val</option>";
		}
		
		$html .= "</select></td></tr>";
		
		return $html;
	}
	function generate_number($component) {
		return $this->generate_html5_type ( $component, $type = 'number', '' );
	}
	function generate_email($component) {
		return $this->generate_html5_type ( $component, $type = 'email', '' );
	}
	function generate_file($component) {
		return $this->generate_html5_type ( $component, $type = 'file', $option = 'multiple class="file-upload" onchange="process_upload(this,2)"' );
	}
	function generate_sign($component) {
		return $this->generate_html5_type ( $component, $type = 'file', $option = 'class="file-upload" onchange="process_upload(this,1)"' );
	}
	function generate_section($component) {
		$html = "<tr><td colspan='2'><h4 id='" . $component ['cid'] . "'>" . $component ['label'] . "</h4></td></tr>";
		return $html;
	}
}