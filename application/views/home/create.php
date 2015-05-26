<script type="text/javascript">
$( document ).ready(function() {

	$.ajax({
	     url:"<?php echo base_url()?>index.php/user/get_all_emails",
	     type:"post",
	     dataType: 'json',
	     success:function(data){
	    	 $('#txt-email').tokens({
	    		    source : data,
	    		    allowAddingNoSuggestion: false,
	    		    allowMultiplePaste: true,
	    		    validate: function(val) {
	    		      var values = val.split(',');

	    		      for(var value in values) {
	    		        value = values[value];

	    		        if(value.indexOf('@') !== -1) {
	    		          continue;
	    		        } else {
	    		          return false;
	    		        }
	    		      }

	    		      return true;
	    		    }
	    		  });


	    	 $("input.tokens-input-text").attr('placeholder', 'Enter emails...');
	    	 
	    		}   
	});

	<?php 
	if (isset($form_instance)){
		echo "$('#cmb-type-id').val('".$form_instance->getType()->getId()."');";
		echo "$('#cmb-type-id').change();";
		echo "$('#cmb-type-id').prop('disabled',true);";
		echo "$('#form-title').val('".$form_instance->getTitle()."');";
		echo "$('#form-id').val('".$form_instance->getId()."');";
	}
	?>
	
	
	
});
	


  
function load_form(){
	$.ajax({
		  url: "<?php echo base_url()?>index.php/form/get_template/"+$('#cmb-type-id').val(),
		  success:function(data) {
				$('#main-form').html(data);
		  		      }
	});
}

function clear_form(){
	load_form();	
}

function discard_form(){
	form_id = $('#form-id').val();
	if (form_id != ''){
		$.ajax({
			  url: "<?php echo base_url()?>index.php/form/discard/"+$('#form-id')   .val(),
			  success:function(data) {
					$('#main-form').html(data);
						display_msg('info', 'Discarded succesfully');
						clear_form();
			  		}
		});
	}
	else{
		display_msg('Warning', 'No form to be discarded')	
		}
}

function display_msg(title,msg){
	$('#myModalLabel').html(title);
	$('#message-content').html(msg);
	$('#myModal').modal();
}

function send_form(){
	check=true;
	msg='';
	
	if ($('#txt-email').val()== ''){ 
		check=false;
		msg += 'Please enter emails <br/>';
	}

	if ($('#txt-message').val()== ''){ 
		check=false;
		msg += 'Please enter message';
	}
	
	form1 = $('#form-info')[0];
	form2 = $('#email-info')[0];
	
	if (check==true){
	    if(form1.checkValidity()){
	    	$.ajax({
	  		  type: "POST",
	  		  url: "<?php echo base_url()?>index.php/form/send/",
	  		  dataType: "json",
	  		  data: {form_id: $('#form-id').val(), type_id:$('#cmb-type-id').val(), title:$('#form-title').val(), to_user: $('#txt-email').val(), message: $('#txt-message').val()},
	  		  success:function(data) {
	  			$('#form-id').val(data);
				display_msg('info', 'Sent succesfully, Check inbox for more details');
				clear_email();
				clear_form();
	  		  }
	  	});
    }
	    else
			display_msg ('Warning', 'No form to be sent');
	}else{
		display_msg ('Warning', msg);
	}
    
}

function clear_form(){
	$('#form-id').val("");
	$('#form-title').val("");
	$('#cmb-type-id').prop("selectedIndex", 0);
	$('#cmb-type-id').change();
}

function clear_email(){
	$('#txt-email').val('');
	$('#txt-message').val('');
}

function save_form(){
	$('#submit-form').click();

	form = $('#form-info')[0];
	    if(form.checkValidity()){
	    	$.ajax({
	  		  type: "POST",
	  		  url: "<?php echo base_url()?>index.php/form/save/",
	  		  dataType: "json",
	  		  data: {form_id: $('#form-id').val(), type_id:$('#cmb-type-id').val(), title:$('#form-title').val()},
	  		  success:function(data) {
		  		  if (data != ''){
	  			  	$('#form-id').val(data);
	  			  	display_msg('info', 'Saved succesfully');
		  		  }
	  		  }
	  	});
    }
}

function new_message(){
	$('#new-message').css('display','block');
}

function close_new_message(){
	$('#new-message').css('display','none');
}

</script>
<div id="page-wrapper" style="min-height: 275px;">
	<!-- Modal -->
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
	<div class="row">
		<div class="col-lg-12">
			<div class="page-header">
				<button id='btn-new-message' class="btn btn-primary" type="button"
					onclick="new_message()">New Message</button>
				<button class="btn btn-success" type="button" onclick="save_form()">Save
					Form</button>
				<button class="btn btn-warning" type="button" onclick="clear_form()">Clear
					Form</button>
				<button class="btn btn-danger" type="button"
					onclick='discard_form()'>Discard Form</button>

			</div>
		</div>


	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-8">


			<div id='new-message' class="alert alert-info alert-dismissable"
				style="display: none; background-color: #f5f5f5">
				<button type="button" class="close" onclick="close_new_message()">&times;</button>
					<div class="panel-heading">
						<input type="email" id="txt-email" class="form-control input-sm"
							placeholder="Type emails here..." >

					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<textarea rows="5" id='txt-message'
							placeholder="Type your message here" class="form-control"
							required></textarea>
					</div>
					<!-- /.panel-body -->
					<div class="panel-heading">

						<button class="btn btn-info"
							id='btn-send' onclick="send_form()">Send</button>
					</div>

			</div>

			<div class="panel panel-default">

				<div class="panel-heading">
					<form id='form-info' onsubmit="return false">
						<div class="form-group">
							<select id='cmb-type-id' class="form-control" required
								onchange="load_form()">
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
						<input type="hidden" id='form-id' value=''> <input type="text"
							placeholder="Type your document title here..."
							class="form-control input-sm" id="form-title" required> <input
							type="submit" id='submit-form' style="display: none;">
					</form>
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">
							<div id="main-form"></div>
						</div>
						<!-- /.col-lg-6 (nested) -->
					</div>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->

			<!-- /.panel -->

			<!-- /.panel -->
		</div>
		<!-- /.col-lg-8 -->
		<div class="col-lg-4">
		<?php if (isset($modification_history)){?>
<div class="table-responsive table-bordered">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        
                                    foreach ($modification_history as $history){
                                    	echo "<tr><td>".$history->getModifiedDate()->format("d/m/Y H:i:s")."</td>";
                                    	echo "<td>".$history->getUser()->getFirstName()."</td>";
                                    	echo "<td>".$history->getUser()->getLastName()."</td>";
                                    	echo "<td>Modified</td></tr>";
                                    }
                                    echo "<tr><td>".$form_instance->getCreatedDate()->format("d/m/Y H:i:s")."</td>";
                                    echo "<td>".$form_instance->getUser()->getFirstName()."</td>";
                                    echo "<td>".$form_instance->getUser()->getLastName()."</td>";
                                    echo "<td>Created</td></tr>";
                                        }?>

                                     
<?php if (isset($modification_history)){?>
                                    </tbody>
                                </table>
                            </div>
                            <?php }?>
			<!-- /.panel -->

			<!-- /.panel -->

			<!-- /.panel .chat-panel -->
		</div>
	</div>
	<!-- /.row -->
</div>