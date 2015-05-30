<script type="text/javascript">
	var json_template = null; 
	function save_form(){
		$('#submit-form').click();


		form = $('#form-info')[0];
		    if(form.checkValidity()){
		    	$.ajax({
		  		  type: "POST",
		  		  url: "<?php echo base_url()?>index.php/template/save/",
		  		  dataType: "json",
		  		  data: {json_template: json_template, type_id:$('#cmb-type-id').val()},
		  		  success:function(data) {
			  		  if (data != ''){
		  			  	display_msg('info', "Saved successfully");
			  		  }
		  		  }
		  	});
	    }
	}

	function load_template(){
		$.ajax({
			type: "POST",
			url: "<?php echo base_url()?>index.php/template/get_template/"+$('#cmb-type-id').val(),
			dataType: 'json',
			success: function (data){
				alert(data);
			}
			});
	}

	function new_message(id){
		$(id).css('display','block');
	}

	function close_new_message(id){
		$(id).css('display','none');
	}

	function display_msg(title,msg){
		$('#myModalLabel').html(title);
		$('#message-content').html(msg);
		$('#myModal').modal();
	}

	function discard_form(){
		form_id = $('#form-id').val();
		if (form_id != ''){
			$.ajax({
				  url: "<?php echo base_url()?>index.php/form/discard/"+$('#form-id')   .val(),
				  success:function(data) {
					  var json = JSON.parse(data);
							display_msg('info', json.msg);
							if (json.err == 0){
								clear_form();
							}
				  		}
			});
		}
		else{
			display_msg('Warning', 'No template to be discarded')	
			}
	}
	
	
    $(function(){
      fb = new Formbuilder({
        selector: '.fb-main',
        bootstrapData: [
          {
            "label": "Do you have a website?",
            "field_type": "website",
            "required": false,
            "field_options": {},
            "cid": "c1"
          },
          {
            "label": "Please enter your clearance number",
            "field_type": "text",
            "required": true,
            "field_options": {},
            "cid": "c6"
          },
          {
            "label": "Security personnel #82?",
            "field_type": "radio",
            "required": true,
            "field_options": {
                "options": [{
                    "label": "Yes",
                    "checked": false
                }, {
                    "label": "No",
                    "checked": false
                }],
                "include_other_option": true
            },
            "cid": "c10"
          },
          {
            "label": "Medical history",
            "field_type": "file",
            "required": true,
            "field_options": {},
            "cid": "c14"
          }
        ]
      });

      fb.on('save', function(payload){
        console.log(payload);
        json_template = payload;
      })
    });
  </script>
<div id="page-wrapper">
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
		aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width: 300px">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel"></h4>
				</div>
				<div class="modal-body" id='message-content'></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	
	
	<div class='row'>
		<div class="page-header">
			<button id="btn-new-message" class="btn btn-primary" type="button"
				onclick="new_message('#new-relation')">Define Form Relation</button>

			<button class="btn btn-success" type="button" onclick="save_form()">
				Save Template</button>
			<button class="btn btn-warning" type="button" onclick="discard_form()">
				Discard Template</button>

		</div>
		
					<div id='new-relation' class="alert alert-info alert-dismissable"
				style="display: none; background-color: #f5f5f5; border-color: #ddd">
				<div class="panel-heading">
                            <h4>Relations between forms
                            				<button type="button" class="close" onclick="close_new_message('#new-relation')">&times;</button>
                            </h4>
                        </div>
				<div class="panel-heading">
					<input type="email" id="txt-email" class="form-control input-sm"
						placeholder="Type emails here...">

				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<textarea rows="5" id='txt-message'
						placeholder="Type your message here" class="form-control" required></textarea>
				</div>
				<!-- /.panel-body -->
				<div class="panel-heading">

					<button class="btn btn-info" id='btn-send' onclick="send_form()">Send</button>
				</div>

			</div>
		
				<div class="panel-heading">
					<form id='form-info' onsubmit="return false">
						<div class="form-group">
							<select id='cmb-type-id' class="form-control" required
								onchange="load_template()">
								<option value=''>Please Select Document</option>
                                        <?php
                                        foreach ($group_types as $group){
                                        	echo "<optgroup label='$group[0]'>";
                                        	foreach ($group[1] as $type)
                                        		echo "<option value='".$type->getId()."'>".$type->getTitle()."</option>";
                                        	echo "</optgroup>";
                                        } 
                                        ?>
                                    </select>

						</div>
						<input type="hidden" id='form-id' value=''>
						 <input
							type="submit" id='submit-form' style="display: none;">
					</form>
				</div>


	</div>
	<div class='row'>
		<div class='fb-main' id='form-design-area'></div>
	</div>

</div>