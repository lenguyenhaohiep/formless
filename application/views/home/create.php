<script type="text/javascript">
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
	form_id = 1;
	if (form_id != null){
		$.ajax({
			  url: "<?php echo base_url()?>index.php/form/discard/"+$('#cmb-type-id').val(),
			  success:function(data) {
					$('#main-form').html(data);
					alert('discard');
			  		      }
		});
	}
	else{
	}
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
	  			  alert(data);
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
    

    
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
            	<button id='btn-new-message' class="btn btn-primary" type="button" onclick="new_message()">New Message</button>
                <button class="btn btn-success" type="button" onclick="save_form()">Save</button>
                <button class="btn btn-warning" type="button" onclick="clear_form()">Clear</button>
                <button class="btn btn-danger" type="button" onclick='discard_form()'>Discard</button>

            </div>
        </div>            


    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-8">
        
        
        <div id='new-message' class="alert alert-info alert-dismissable" style="display: none; background-color: #f5f5f5">
                                <button type="button" class="close" onclick="close_new_message()">×</button>
                    <form id='email-info' action="index.php/form/send">
                <div class="panel-heading">
                    <input type="email" id="txt-email" class="form-control input-sm" placeholder="Type emails here..." required>
                
                                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
<textarea rows="5" placeholder="Type your message here" class="form-control" required></textarea>                    
                </div>
                <!-- /.panel-body -->                <div class="panel-heading">
                
                <input class="btn btn-info" type="submit" value='Send'>
                </div>
                </form>
        
        </div>
        
            <div class="panel panel-default">

                <div class="panel-heading">
   <form id='form-info' onsubmit="return false">
                	<div class="form-group">
                                    <select id='cmb-type-id' class="form-control" required onchange="load_form()">
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
                    <input type="text" placeholder="Type your document title here..." class="form-control input-sm" id="form-title" required>
                    <input type="submit" id='submit-form'>
   </form>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
 							<div id="main-form">
 							</div>
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

            <!-- /.panel -->

            <!-- /.panel -->

            <!-- /.panel .chat-panel -->
        </div>
    </div>
    <!-- /.row -->
</div>