<div id="page-wrapper" style="min-height: 275px;">
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
	$.ajax({
		  url: "<?php echo base_url()?>index.php/form/save/",
		  success:function(data) {
			  alert('save_form');
		  }
	});
}

</script>
    <!-- /.row -->

    
                    					<form id='form-info' method="post">
    
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <button class="btn btn-success" type="button" onclick="save_form()">Save</button>
                <button class="btn btn-warning" type="button" onclick="clear_form()">Clear</button>
                <button class="btn btn-danger" type="button" onclick='discard_form()'>Discard</button>

            </div>
        </div>            


    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                
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
                    <input type="email" placeholder="Type your document title here..." class="form-control input-sm" id="txt-title" required>
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
            <div class="chat-panel panel panel-default">
            <form id='email-info' action="<?php echo  base_url()?>index.php/form/send">
                <div class="panel-heading">
                    <input type="email" id="btn-input" class="form-control input-sm" placeholder="Type emails here..." required>
                
                                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
<textarea rows="15" placeholder="Type your message here" class="form-control" required></textarea>                    
                </div>
                <!-- /.panel-body -->                <div class="panel-heading">
                
                <input class="btn btn-primary" type="submit" value='Send'>
                </div>
                </form>
                <!-- /.panel-footer -->
            </div>
            <!-- /.panel .chat-panel -->
        </div>
    </div>
    <!-- /.row -->
</div>