<?php
/**
 * Name: Formmaker
 *
 * @author Hiep Le
 *
 * Description: This class aims to process JSON template/form tasks such as extract data, fileds, generate HTML from JSON
 */
class Formmaker {
	/**
	 * 
	 * HTML represenation of a template/ form
	 * 
	 * @var string
	 */
	private $html_template;
	
	/**
	 * Construction
	 */
	function Formmaker() {
		$this->html_template = '<form id="data-form" class = "form-auto-generate"><table>';
	}
	
	/**
	 * Get fields from requested data
	 * @param string $json
	 * @return array of fields
	 */
	function get_attributes_from_requested_json($json) {
		$attrs = array ();
		foreach ( $json as $key ) {
			$attrs [] = $key ['name'];
		}
		return json_encode ( $attrs );
	}
	
	/**
	 * get data from json
	 * This functions is used to extract data from JSON form stored in DB
	 * This return value is used in the function auto-filling (fill()) in the controller FORM
	 * 
	 * @param string $data_json JSON Form contains data
	 * @return array of data, each element has form (id=>value)
	 */
	function get_data_form_json($data_json) {
		$data = json_decode ( $data_json, true );
		$values = array ();
		foreach ( $data ['fields'] as $item ) {
			$id = $item ['cid'];
			$val = $item ['value'];
			$type = $item ['field_type'];
			
			/**
			 * Case of file where we have lots of image source 64BASE
			 * the return value will be an array <img>
			 */
			if ($type == 'file') {
				$img = '';
				if (! is_array ( $val ))
					$val = array (
							$val 
					);
				foreach ( $val as $v )
					if ($v != '')
						$img .= "<img src=$v class='image-select'/><br/>";
				$values [$id] = $img;
			} 
			
			/**
			 * Case of signature
			 * We have 2 text for first name and last name which are displayed as 2 <span>
			 * the last one is the image <img> for the signature photo
			 */
			else if ($type == 'sign') {
				if ($val [2] != '') {
					$values [$id] = "<span>$val[0]</span> <span>$val[1]</span> <br/><img src=$val[2] class='image-select'/>";
				} else
					$values [$id] = "<span>$val[0]</span> <span>$val[1]</span>";
			} 
			
			/**
			 * Case of checkbox
			 */
			else if ($type == 'checkboxes') {
				$p = '';
				foreach ( $val as $v )
					$p .= "<span>$v</span>, ";
				$values [$id] = $p;
			} 
			
			/**
			 * Others cases
			 */
			else
				$values [$id] = $val;
		}
		
		return $values;
	}
	
	/**
	 * Get data from request data with form {{name:'', value:''}} and return as an array with form {id=>data}
	 * 
	 * @param array $data
	 * @return array (id=>data)
	 */
	function data_request_to_array($data) {
		// get array data from request, each field is a pair (name, value)
		$_data = array ();
		foreach ( $data as $d ) {
			$name = $d ['name'];
			
			//case of multiple values
			if (isset ( $_data [$name] )) {
				if (! is_array ( $_data [$name] )) {
					$temp = $_data [$name];
					$_data [$name] = array ();
					$_data [$name] [] = $temp;
				}
				$_data [$name] [] = $d ['value'];
			}
			
			//case of scalar value
			else {
				$_data [$name] = $d ["value"];
			}
		}
		
		return $_data;
	}
	
	// fill data to template
	/**
	 * Fill data to JSON template
	 * 
	 * @param string $json JSON representation
	 * @param array $data data from request
	 * @param boolean $owner ownership of the form
	 * @param array $attrs list of fields can be modified
	 */
	function fill_data($json, $data, $owner, $attrs) {
		$_data = $this->data_request_to_array ( $data );
		
		// fill data to the template, template will be converted from json to array
		$_json = json_decode ( $json, true );
		$j = 0;
		foreach ( $_json ['fields'] as $component ) {
			
			/*
			 * type and id of each element
			 */
			$type = $component ['field_type'];
			$id = $component ['cid'];
			
			if (in_array ( $id, $attrs )) {
				// in the case that the current is the owner of the form, the owner information is added.
				$this->CI = & get_instance ();
				
				//check and update the ownership information
				if ($owner == true) {
					$_json ['fields'] [$j] ['owner'] = $this->CI->session->userdata ( 'identity' );
				}
				
				//to indicate who modifies this field
				$_json ['fields'] [$j] ['filled_by'] = $this->CI->session->userdata ( 'identity' );
				
				switch ($type) {
					case 'checkboxes' :
					case 'file' :
						//the case of input[type=file]
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
						
					case 'sign' :
						//the case of signature
						//id-0: FistName, id-1 LastName
						$_json ['fields'] [$j] ['value'] [0] = $_data [$id . '-0'];
						$_json ['fields'] [$j] ['value'] [1] = $_data [$id . '-1'];
						if (isset ( $_data [$id] )) {
							$_json ['fields'] [$j] ['value'] [2] = $_data [$id];
						} else {
							$_json ['fields'] [$j] ['value'] [2] = null;
						}
						break;
					default :
						if (isset ( $_data [$id] ))
							$_json ['fields'] [$j] ['value'] = $_data [$id];
						else
							$_json ['fields'] [$j] ['value'] = null;
						break;
				}
			}
			$j ++;
		}
		return json_encode ( $_json );
	}
	
	/**
	 * 
	 * Parse an entity of Relation to the array
	 * 
	 * @param entity $relation
	 * @return array which depicts the relation
	 */
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
	
	/**
	 * 
	 * Parse the relation from JSON template in case of two identical forms
	 * 
	 * @param int $type_id
	 * @param string $json JSON template
	 * @return array which depicts the relation
	 */
	function get_relation_identical($type_id, $json) {
		$result = array ();
		
		$_json = json_decode ( $json, true );
		$template = $_json ['fields'];
		if (count ( $template ) > 0) {
			foreach ( $template as $component ) {
				$attr1 = $component ['cid'];
				$type = $component ['field_type'];
				if ($type != 'header' && $type != 'section')
					$result [$type_id] [$type_id] [$attr1] = $attr1;
			}
		}
		return $result;
	}
	
	/**
	 * get fields of Json template
	 * Each field contains type and label
	 * 
	 * @param string $json JSON template
	 * @param string $data not implemented yet, can be NULL
	 * @return array of fields
	 */
	function get_attribute_from_json($json, $data = NULL) {
		$result = array ();
		$template = json_decode ( $json, true );
		$template = $template ['fields'];
		$count = 0;
		foreach ( $template as $component ) {
			$type = $component ['field_type'];
			// if ($type != 'header' && $type != 'section')
			$result [] = array (
					$component ['cid'] => array (
							'type' => $type,
							'label' => $component ['label'] 
					) 
			);
		}
		return $result;
	}
	
	/**
	 * Generate HTML text from JSON template
	 * In case that there are some fields the user cannot modify, HTML element will be displayed as disabled item
	 * 
	 * 
	 * @param string $json JSON template
	 * @param array $attrs List of fields that is modifiable for the current user
	 * @return string
	 */
	function generate_from_json($json, $attrs = NULL) {
		$template = json_decode ( $json, true );
		$template = $template ['fields'];
		if (count ( $template ) > 0) {
			foreach ( $template as $component ) {
				$type = $component ['field_type'];
				
				// check if field is authorized to modify
				if (! isset ( $attrs ))
					$disable = '';
				else {
					if (! in_array ( $component ['cid'], $attrs )) {
						$disable = ' disabled';
					} else
						$disable = '';
				}
				
				// assign the html text corresponding to the type
				switch ($type) {
					case 'header' :
						$this->html_template .= $this->generate_header ( $component, $disable );
						break;
					case 'text' :
						$this->html_template .= $this->generate_text ( $component, $disable );
						break;
					case 'paragraph' :
						$this->html_template .= $this->generate_paragraph ( $component, $disable );
						break;
					case 'checkboxes' :
						$this->html_template .= $this->generate_checkboxes ( $component, $disable );
						break;
					case 'radio' :
						$this->html_template .= $this->generate_radio ( $component, $disable );
						break;
					case 'date' :
						$this->html_template .= $this->generate_date ( $component, $disable );
						break;
					case 'dropdown' :
						$this->html_template .= $this->generate_dropdown ( $component, $disable );
						break;
					case 'number' :
						$this->html_template .= $this->generate_number ( $component, $disable );
						break;
					case 'email' :
						$this->html_template .= $this->generate_email ( $component, $disable );
						break;
					case 'file' :
						$this->html_template .= $this->generate_file ( $component, $disable );
						break;
					case 'sign' :
						$this->html_template .= $this->generate_sign ( $component, $disable );
						break;
					case 'section' :
						$this->html_template .= $this->generate_section ( $component, $disable );
						break;
					default :
						break;
				}
			}
		} else {
			$this->html_template = 'No template available';
		}
		return $this->html_template . "</table><input type='submit' id='validate' style='display:none'/></form>";
	}
	
	/**
	 * Generate a HTML representation for a form to be export in PDF, 
	 * this html is different from the one displayed in web browser
	 * 
	 * @param string $json JSON template
	 * @return string HTML
	 */
	function generate_html_pdf($json) {
		$template = json_decode ( $json, true );
		$template = $template ['fields'];
		$html = '<html>
				<head>
				</head>
				<body>

				    <div id="print-area">
				        <div id="header">
				        </div>
				        <div id="content">';
		if (count ( $template ) > 0) {
			foreach ( $template as $component ) {
				$type = $component ['field_type'];
				switch ($type) {
					case 'header' :
						$html .= "<h1><center>" . $component ['label'] . "</center></h1>";
						break;
					case 'text' :
					case 'paragraph' :
					case 'date' :
					case 'number' :
					case 'email' :
					case 'dropdown' :
						$html .= "<p><span>" . $component ['label'] . "</span><span>" . $component ['value'] . "</span></p>";
						break;
					
					case 'checkboxes' :
					case 'radio' :
						$type = str_replace ( "es", "", $type );
						$options = $component ['field_options'] ['options'];
						$html .= "<p><span>" . $component ['label'] . "</span><span>";
						
						//multiple value
						if (isset ( $component ['value'] )) {
							$value = $component ['value'];
							if (! is_array ( $value ))
								$value = array (
										$value 
								);
						} else
							$value = array ();
						
						//checked values
						foreach ( $options as $opt ) {
							$val = $opt ['label'];
							if (in_array ( $val, $value ))
								$check = 'checked';
							else
								$check = '';
							
							$html .= "<input type='$type' $check>$val<br/>";
						}
						
						$html .= "</span></p>";
						break;
					
					case 'file' :
						$html .= "<p><span>" . $component ['label'] . "</span><span>" . count ( $component ['value'] ) . " attachment(s)</span></p>";
						break;
					case 'sign' :
						$html .= "<p><span>" . $component ['label'] . "</span><span>" . $component ['value'] [0] . " " . $component ['value'] [1] . "</span></p>";
						if ($component ['value'] [2] != '')
							$html .= "<p><span></span><img src='" . $component ['value'] [2] . "'/></p>";
						break;
					case 'section' :
						$html .= "<h4>" . $component ['label'] . "</h4>";
						break;
					default :
						break;
				}
			}
		}
		
		return $html . '</div>
				        <div id="footer">
				        </div>
				    </div>

				</body>
				</html>';
	}
	
	/**
	 * 
	 * Return a HTML text for a "required" item
	 * 
	 * @param unknown $true
	 * @return string
	 */
	function check_require($true) {
		if ($true == true)
			return 'required';
		return '';
	}
	
	/**
	 * Return a HTML label for an item
	 * 
	 * @param unknown $true
	 * @return string
	 */
	function text_require($true) {
		if ($true == true)
			return '<span class="field-required">*</span>';
		return '';
	}
	
	/**
	 * Generate a HTML text that represents a INPUT[type=text]
	 *
	 * @param array $component a component with type of text input  from JSON template which contains cid, label and others options
	 * @param bool $disable to indicate the HTML element is disabled
	 * @return string that represent a HTML element
	 */
	function generate_text($component, $disable) {
		// {"label":"Name","field_type":"text","required":true,"field_options":{"size":"small"},"cid":"c22"}
		return $this->generate_html5_type ( $component, $type = 'text', $disable );
	}
	
	/**
	 * Generate a HTML text that represents a HTML5 element
	 *
	 * @param array $component a component with type of HTML5 from JSON template which contains cid, label and others options
	 * @param bool $disable to indicate the HTML element is disabled
	 * @param string $type type of the element
	 * @param array $option
	 * 
	 * @return string that represent a HTML element
	 */
	function generate_html5_type($component, $type, $disable, $option = NULL) {
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
		if ($type == 'file' || $type == 'sign') {
			if ($value == '')
				$value = array ();
			if (! is_array ( $value ))
				$value = array (
						$value 
				);
			$data = "<div id='$id-data'>";
			$j = 0;
			if ($type == 'sign') {
				// only the third value is the image
				$f_name = isset ( $value [0] ) ? $value [0] : '';
				$l_name = isset ( $value [1] ) ? $value [1] : '';
				$value = isset ( $value [2] ) ? array (
						$value [2] 
				) : array ();
			}
			
			foreach ( $value as $img ) {
				$id_img_data = "d" . $id . "-" . $j;
				$data .= "<div class='img-data' id='" . $id_img_data . "-div' >
								<img onclick='view_image(this)' src='" . $img . "' class='image-select' />";
				$data .= "<button class='btn btn-primary select-file' type='button' onclick='delete_img(\"$id_img_data-div\");' $disable>Delete</button>";
				$data .= "<input type='checkbox' checked name='$id' value='$img' style='display: none' $disable>
							</div>";
				
				$j ++;
			}
			
			$data .= "</div>";
			$name = "";
			$html = "<tr>
						<td>
							<label for='$id'>$label $require_text</label>
						</td>
						<td>";
			if ($type == 'sign')
				$html .= "FirstName <input type='text' id='$id-0' name='$id-0' placeholder='Enter FistName' value='$f_name' $require $disable/>
							LastName <input type='text' id='$id-1' name='$id-1' placeholder='Enter LastName' value='$l_name' $require $disable/>";
				
				// Add buttons Sign, Verify
			
			$html .= "<br/> <br/> <label for='$id' class='btn btn-primary' $disable>Select image file(s)</label>
							<input class='hidden' id='$id' $name type='file' $require $option $disable/>";
			if ($type == 'sign')
				$html .= "<button type='button' class='btn btn-primary' onclick = sign('$id','$id-0','$id-1') $disable>Sign</button>";
				// <button type='button' class='btn btn-warning' onclick = verify('$id','$id-0','$id-1') $disable>Verify</button>";
			$html .= "$data
						</td>
					</tr>";
		} else {
			// input[type= text, date, number, email]
			$data = '';
			$name = "name = '$id'";
			$html = "<tr><td><label for='$id'>$label $require_text</label></td><td><input id='$id' $name type='$type' value='$value' $require $disable/>$data</td></tr>";
		}
		
		return $html;
	}
	
	/**
	 * Generate a HTML text that represents a TEXTAREA
	 *
	 * @param array $component a component with type of paragraph from JSON template which contains cid, label and others options
	 * @param bool $disable to indicate the HTML element is disabled
	 * @return string that represent a HTML element
	 */
	function generate_paragraph($component, $disable) {
		$id = $component ['cid'];
		$require = $this->check_require ( $component ['required'] );
		$label = $component ['label'];
		$require_text = $this->text_require ( $component ['required'] );
		
		if (isset ( $component ['value'] ))
			$value = $component ['value'];
		else
			$value = '';
		
		$html = "<tr><td><label for='$id'>$label $require_text</label></td><td><textarea id='$id' name='$id' $require $disable>$value</textarea></td></tr>";
		return $html;
	}
	
	/**
	 * Generate a HTML text that represents a Header <H3>
	 *
	 * @param array $component a component with type of Header from JSON template which contains cid, label and others options
	 * @param bool $disable to indicate the HTML element is disabled
	 * @return string that represent a HTML element
	 */
	function generate_header($component, $disable) {
		$html = "<tr><td colspan='2'><center> <h3 id='" . $component ['cid'] . "'>" . $component ['label'] . "</h3> </center></td></tr>";
		return $html;
	}
	
	/**
	 * Generate a HTML text that represents a input[type=checkbox]
	 *
	 * @param array $component a component with type of Checkbox from JSON template which contains cid, label and others options
	 * @param bool $disable to indicate the HTML element is disabled
	 * @return string that represent a HTML element
	 */
	function generate_checkboxes($component, $disable) {
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
			
			$html .= "<input id='$id' type='checkbox' name='$id' value='$val' $check $disable>$val<br/>";
		}
		
		$html .= "</td></tr>";
		return $html;
	}
	
	/**
	 * Generate a HTML text that represents a input[type=radio]
	 *
	 * @param array $component a component with type of Radio from JSON template which contains cid, label and others options
	 * @param bool $disable to indicate the HTML element is disabled
	 * @return string that represent a HTML element
	 */
	function generate_radio($component, $disable) {
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
			
			$html .= "<input id='$id' type='radio' name='$id' value='$val' $check $require $disable>$val<br/>";
		}
		
		$html .= "</td></tr>";
		
		return $html;
	}
	
	/**
	 * Generate a HTML text that represents a Date Input[type=date]
	 *
	 * @param array $component a component with type of date input from JSON template which contains cid, label and others options
	 * @param bool $disable to indicate the HTML element is disabled
	 * @return string that represent a HTML element
	 */
	function generate_date($component, $disable) {
		return $this->generate_html5_type ( $component, $type = 'date', $disable );
	}
	
	/**
	 * Generate a HTML text that represents a SELECT
	 *
	 * @param array $component a component with type of dropdown from JSON template which contains cid, label and others options
	 * @param bool $disable to indicate the HTML element is disabled
	 * @return string that represent a HTML element
	 */
	function generate_dropdown($component, $disable) {
		$id = $component ['cid'];
		$require = $this->check_require ( $component ['required'] );
		$label = $component ['label'];
		$require_text = $this->text_require ( $component ['required'] );
		
		$html = "<tr><td><label for='$id'>$label $require_text</label></td><td><select id='$id' name='$id' $require $disable>";
		
		$options = $component ['field_options'] ['options'];
		
		foreach ( $options as $opt ) {
			$val = $opt ['label'];
			$html .= "<option type='checkbox' value='$val'>$val</option>";
		}
		
		$html .= "</select></td></tr>";
		
		return $html;
	}
	
	/**
	 * Generate a HTML text that represents a Number input[type=number]
	 *
	 * @param array $component a component with type of number input from JSON template which contains cid, label and others options
	 * @param bool $disable to indicate the HTML element is disabled
	 * @return string that represent a HTML element
	 */
	function generate_number($component, $disable) {
		return $this->generate_html5_type ( $component, $type = 'number', $disable );
	}
	
	/**
	 * Generate a HTML text that represents a Email input[type='email']
	 *
	 * @param array $component a component with type of SECTION from JSON template which contains cid, label and others options
	 * @param bool $disable to indicate the HTML element is disabled
	 * @return string that represent a HTML element
	 */
	function generate_email($component, $disable) {
		return $this->generate_html5_type ( $component, $type = 'email', $disable );
	}
	
	
	/**
	 * Generate a HTML text that represents a File Upload input[type=file]
	 *
	 * @param array $component a component with type of File Upload from JSON template which contains cid, label and others options
	 * @param bool $disable to indicate the HTML element is disabled
	 * @return string that represent a HTML element
	 */
	function generate_file($component, $disable) {
		return $this->generate_html5_type ( $component, $type = 'file', $disable, $option = 'multiple class="file-upload" onchange="process_upload(this,2)"' );
	}

	/**
	 * Generate a HTML text that represents a signature which contains 2 inputs for First Last Name and one type=file for image of signature
	 *
	 * @param array $component a component with type of SECTION from JSON template which contains cid, label and others options
	 * @param bool $disable to indicate the HTML element is disabled
	 * @return string that represent a HTML element
	 */
	function generate_sign($component, $disable) {
		return $this->generate_html5_type ( $component, $type = 'sign', $disable, $option = 'class="file-upload" onchange="process_upload(this,1)"' );
	}
	
	/**
	 * Generate a HTML text that represents a Section <h4>
	 * 
	 * @param array $component a component with type of SECTION from JSON template which contains cid, label and others options
	 * @param bool $disable to indicate the HTML element is disabled
	 * @return string that represent a HTML element
	 */
	function generate_section($component, $disable) {
		$html = "<tr><td colspan='2'><h4 id='" . $component ['cid'] . "'>" . $component ['label'] . "</h4></td></tr>";
		return $html;
	}
}