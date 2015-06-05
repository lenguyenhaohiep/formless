
<!-- jQuery-Tagging-Tokenizer-Input-with-Autocomplete -->
<script src="<?php echo base_url(); ?>js/tokens.js"></script>


<script type="text/javascript">
var current_email = '<?php echo $this->session->userdata('identity');?>';
var email_share_before = '';
var email_share_current = '';
var email_send = '';
var form_data_before;
var create_new=false;
var data_fillable_form;

function initilize(){
    
    //Load your form
    $.ajax({
        type: 'POST',
        url: "<?php echo base_url()?>index.php/form/get_your_form",
        dataType: 'json',
        success: function (data) {
            $.each(data,function(k,v){
                $.each(v,function (k1,v1){
                    opt = $('<optgroup>',{label:k1});
                    $.each(v1,function(k2,v2){
                        //option with value type_id/form_id
                        option = $('<option>',{value:k2}).html(v2);
                        opt.append(option);
                    });
                    $('#template-relate').append(opt);
                });
            });
        }
    });
    
	$.ajax({
	     url:"<?php echo base_url() ?>index.php/user/get_all_emails",
	     type:"post",
	     dataType: 'json',
	     success:function(data) {
                $('#txt-email').tokens({
                    source: data,
                    allowAddingNoSuggestion: false,
                    allowMultiplePaste: true,
                    suggestionsZindex: 19999,
                    updateValue: function (val) {
                        email_send = val;
                    },
                    validate: function (val) {
                        var values = val.split(',');
                        for (var value in values) {
                            value = values[value];

                            if (value.indexOf('@') !== -1) {
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
        $.ajax({
            url: "<?php echo base_url() ?>index.php/form/get_shared_info/",
            type: "post",
            dataType: 'json',
            data: {form_id: $('#form-id').val()},
            success: function (data) {
                var json = data;
                $.ajax({
                    url: "<?php echo base_url() ?>index.php/user/get_all_emails",
                    type: "post",
                    dataType: 'json',
                    success: function (data) {
                        $('#txt-email-share').tokens({
                            source: data,
                            allowAddingNoSuggestion: false,
                            allowMultiplePaste: true,
                            suggestionsZindex: 19999,
                            initValue: json,
                            updateValue: function(val){
                                email_share_current = val;
                            },
                            validate: function (val) {
                                var values = val.split(',');

                                for (var value in values) {
                                    value = values[value];

                                    if (value.indexOf('@') !== -1) {
                                        continue;
                                    } else {
                                        return false;
                                    }
                                }
                                email1 = values;
                                return true;
                            }
                        });

                        $("input.tokens-input-text").attr('placeholder', 'Enter emails...');
                        email_share_before = $('#txt-email-share').val();
                    }
                });

            }
        });
    }
$( document ).ready(function() {
    <?php
    if (isset ( $form_instance )) {
            echo "$('#cmb-type-id').val('" . $form_instance->getType ()->getId () . "');";
            echo "$('#cmb-type-id').change();";
            echo "$('#cmb-type-id').prop('disabled',true);";
            echo "$('#form-title').val('" . $form_instance->getTitle () . "');";
            echo "$('#form-id').val('" . $form_instance->getId () . "');";
    }
    ?>
    initilize();
    $("#email-contact").change();
});

function view_image(img){        
    window.open(img.src,'_blank');
}

	
function display(email,date,message){
	html = '<li class="left clearfix"><div class="chat-body clearfix"><div class="header">'
			+' <strong class="primary-font">'+email+'</strong><br/> <small'
			+' class="text-muted"> <i class="fa fa-clock-o fa-fw"></i>'+date+'</small></div><p>'+message+'</p></div></li>';			
			$('#chat-msg').append(html);
			
}

function load_message(){
    $.ajax({
        url: "<?php echo base_url() ?>index.php/form/get_message/",
        type: "POST",
        dataType: "json",
        data: {form_id: $('#form-id').val(), email_contact: $('#email-contact').val() },
        success:function(data) {
            $('#chat-msg').html('');
                $.each(data, function(i, item){
                if (item.from == current_email){
                    item.from='you';
                    display(item.from,item.date,item.message);
                }
                else {
                    display(item.from, item.date, item.message);
                }
              });
          }

        });
    }


function load_form(){
    var action='<?php if ($this->session->userdata('select') == 'detail') echo "get_data"; else echo "get_template"; ?>';
    if (action == 'get_template')
        create_new = true;
    var id = <?php if ($this->session->userdata('select') == 'detail') echo $form_id; else echo "$('#cmb-type-id').val()"; ?>;
	$.ajax({
            url: "<?php echo base_url() ?>index.php/form/"+action+"/"+ id,
            success:function(data) {
                $('#main-form').html(data);
                form_data_before = $('#data-form').serialize();
                    <?php if ($permission == FALSE) echo "$('#data-form :input').attr('disabled', true);"?>
            }
	});
}

function clear_form(){
	load_form();	
}

function discard_form(){
	form_id = $('#form-id').val();
	if (form_id !== ''){
		$.ajax({
			  url: "<?php echo base_url() ?>index.php/form/discard/"+$('#form-id')   .val(),
			  success:function(data) {
				  var json = JSON.parse(data);
						display_msg('info', json.msg);
						if (json.err === 0){
							clear_form();
						}
			  		}
		});
	}
	else{
        display_msg('Warning', 'No form to be discarded');	
	}
}

function display_msg(title,msg){
	$('#myModalLabel').html(title);
	$('#message-content').html(msg);
	$('#myModal').modal();
}

function send_form(){	
    	var form_data = $('#data-form').serializeArray();

	check=true;
	msg='';

	if (email_send === ''){ 
		check=false;
		msg += 'Please enter emails <br/>';
	}

	if ($('#txt-message').val() === ''){ 
		check=false;
		msg += 'Please enter message';
	}
	
	form1 = $('#form-info')[0];
	form2 = $('#email-info')[0];
	
	if (check===true){
	    if(form1.checkValidity()){
                if (!check_form_edit()){
                    form_data = "-1";
                }
                form_data_before = $('#data-form').serialize();
	    	$.ajax({
	  		  type: "POST",
	  		  url: "<?php echo base_url() ?>index.php/form/send/",
	  		  dataType: "json",
	  		  data: {form_id: $('#form-id').val(), type_id:$('#cmb-type-id').val(), title:$('#form-title').val(), to_user: email_send, message: $('#txt-message').val(),data_form: form_data},
	  		  success:function(data) {
	  			$('#form-id').val(data);
	  			$('#new-message').modal('hide');
				display_msg('info', 'Sent succesfully, Check inbox for more details');
				clear_email();
				clear_form();
				setTimeout(function(){ window.location.href='<?php echo base_url();?>index.php/home/sent'; }, 2000);
                    }
                });
            }
            else
                display_msg('Warning', 'No form to be sent');
        } else {
            display_msg('Warning', msg);
        }

    }

function display_field(select){
	var select_id = select.id;
	var select_val = $(select).val();
	var div_id = 'd'+select_id.substring(1);
	$(document.getElementById(div_id)).empty();
	if (select_val !== ''){
		var data = data_fillable_form[select_val];
		$(document.getElementById(div_id)).html(data);
	}
	
}
    
function share_form(){
	var form_data = $('#data-form').serializeArray();	

	check=true;

	form1 = $('#form-info')[0];
	form2 = $('#email-info')[0];
	
	if (check===true){
	    if(form1.checkValidity()){
                if (email_share_current === email_share_before){
                display_msg('info', 'Shared succesfully');
                if (check_form_edit())
                    save_form();
                return;
            }else{
                email_share_before = email_share_current;
            }
                if (!check_form_edit()){
                    form_data = "-1";
                }
                form_data_before = $('#data-form').serialize();
	    	$.ajax({
	  		  type: "POST",
	  		  url: "<?php echo base_url() ?>index.php/form/share/",
	  		  dataType: "json",
	  		  data: {form_id: $('#form-id').val(), type_id:$('#cmb-type-id').val(), title:$('#form-title').val(), to_user: email_share_current,data_form: form_data},
	  		  success:function(data) {
	  			$('#share-form').modal('hide');
				display_msg('info', 'Shared succesfully');				
	  		  }
	  	});
    }
            else
                display_msg('Warning', 'No form to be shared');
        } else {
            display_msg('Warning', msg);
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
	$('.tokens-list-token-holder').remove();
	
}

function check_form_edit(){
    if (create_new){
        create_new=false;
        console.log('create-new');
        return true;
    }
    //if not edit
    if ($('#data-form').serialize() === form_data_before){
        console.log('content-no-changed');
        return false;
    } else{
        //if edit
        console.log('content-changed');
        return true;
    }
}

function save_form(){
	var form_data = $('#data-form').serializeArray();	        
	$('#submit-form').click();

	form = $('#form-info')[0];
	    if(form.checkValidity()){
                if (!check_form_edit()){
                    display_msg('info', 'Saved succesfully');
                    return;
                }
                form_data_before = $('#data-form').serialize();
	    	$.ajax({
	  		  type: "POST",
	  		  url: "<?php echo base_url()?>index.php/form/save/",
	  		  dataType: "json",
	  		  data: {	form_id: $('#form-id').val(), 
		  		  		type_id:$('#cmb-type-id').val(), 
		  		  		title:$('#form-title').val(),
		  		  		data_form: form_data},
	  		  success:function(data) {
		  		  if (data != ''){
	  			  	$('#form-id').val(data);
	  			  	display_msg('info', 'Saved succesfully');
		  		  }
	  		  }
	  	});
    }
}

    function define_relation(){

        var action='<?php if ($this->session->userdata('select') == 'detail') echo "form"; else echo "template"; ?>';
        var id=<?php if ($this->session->userdata('select') == 'detail') echo "$('#form-id').val();"; else echo "$('#cmb-type-id').val();"; ?>;
                
        if ($('#cmb-type-id').val() == ''){
            display_msg('Warning', 'Please Select Template');
            return;
        }
        $('#template-relate').val('');
        $('#template-relate option').css('display','block');
        if ($('#form-id').val() !== '')
        $('#template-relate option[value="'+$('#form-id').val()+'"]').css('display','none');

        $.ajax({
            type: "GET",
            url: '<?php echo base_url();?>index.php/'+action+'/get_attr/'+id,
            dataType: "json",
            success: function(data){
                $('#body-template-attr').empty();
                attrs_current = data;
                $.each(data, function (i, item){
                    $.each(item, function (key, value){
                        tr = $('<tr>');
                        tr.append($('<td>').html(value));
                        td2 = $('<td>');
                        txt = 'Please select form to fill';
                        prefix = 'f';
                        td2.append($('<select>',{id: prefix+key, name: prefix+key, onchange: 'display_field(this)'}).append($('<option>',{value:''}).html(txt)));

                        prefix = 'd';
                        td3 = $('<td>').append($('<div>',{id: prefix+key}));
                        tr.append(td2);
                        tr.append(td3);
                        $('#body-template-attr').append(tr);

                        

                    });

                });
            }
        });
        $('#fill-form').modal();
    }
    
    function load_template_attr(){
            $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>index.php/form/get_attr/" + $('#template-relate').val(),
            dataType: 'json',
            success: function (data) {
                var txt;
                if ($('#template-relate').val()=='')
                    txt = 'Please select form to fill';
                else
                    txt = 'Please select field';
                var option='<option value="">'+txt+'</option>';
                var prefix= 'f';
                $.each(data, function (i, item){
                    $.each(item, function (key, value){
                        option += '<option value="'+key+'">'+value+'</option>';
                    });
                });
                $.each(attrs_current, function (i, item){                   
                    $.each(item, function (key, value){
                        var select =  $(document.getElementById(prefix+key));
                        select.empty();
                        select.append(option);
                        select.change();
                    });

                });


                //load data of the fillable form
                $.ajax({
                    type:"GET",
                    url : "<?php echo base_url()?>index.php/form/get_data_array/"+$('#template-relate').val(),
                    dataType: 'json',
                    success: function(data){
                    	data_fillable_form = data;
                        //load current relation
                        $.ajax({
                            type:"GET",
                            url : "<?php echo base_url()?>index.php/form/fill/"+$('#cmb-type-id').val()+"/"+$('#template-relate').val(),
                            dataType: 'json',
                            success: function(data){
                                $.each(data, function(key,item){
                                    prefix='f';
                                    var select =  $(document.getElementById(prefix+key));
                                    if (select !== undefined){
                                        if (select.find('option[value='+item+']').val() != undefined){
                                        select.val(item);
                                        select.change();
                                        }
                                    }
                                });
                            }
                        });
                    }
                }); 
                

            }
        });
    }

function new_message(id){
        //$('.form-modal').css('display','none');
	//$(id).css('display','block');
        var msg = '';
        if ($('#cmb-type-id').val()==='')
            msg += 'Please choose your document </br>';
        if ($('#form-title').val()==='')
            msg += 'Please enter the title document';
        if (msg === '')    
            $(id).modal();
        else
            display_msg('Warning', msg);
}

function close_new_message(id){
	$(id).css('display','none');
}

function fill_form(){
	$('#data-form').find('input, textarea, select').each(function(){
		var id = this.id;
		var tagName = this.tagName;
	    var type = this.type;
		
		var div = $(document.getElementById('d'+id));
		if (div != undefined){
				if (type === 'text' || tagName ==='SELECT' || tagName === 'TEXTAREA'){
					$(this).val(div.text());
				} 

				if (type === 'radio'){
					$(this).find(value=div.text()).prop('checked',true);
				}

				if (type === 'checkbox'){
					div.find('span').each(function(e){
						$(this).find(value=$(e).text()).prop('checked',true);
					});
				}

				if (type === 'file'){
				}
			}		
		});
	$('#fill-form').modal('hide');
}


var i=0;

function delete_img(id){
	$('#'+id).remove();
}

function create_line_image(button,source, name){
	  id_button = $(button).attr('id');
	  div_data = $('#'+ $(button).attr('id')+'-data'); 
	  id_img_data = $(button).attr('id')+"-"+i;
	  
	  $(div_data).append("<div class='img-data' id='"+id_img_data+"-div' ><img class='image-select' /></div>");		  
	  img = '#'+id_img_data + '-div img';
	  $(img).attr("src",source);
	  $(img).after('<button class="btn btn-danger select-file" type="button" onclick="delete_img(\''+id_img_data+'-div\');">Delete</button>');
	  $(img).after('<span>'+name+'</span><br/>');
	  $(button).after('<input type="checkbox" checked name="'+id_button+'" value="'+ source +'" style="display: none">');
	  
	  i++;
}

function process_upload(button, type) {
	  if (type==1){
		  $('#'+ $(button).attr('id')+'-data').html('');
	  }
	  var files = button.files; 
	  if(files==null) return;
	  for (var i = 0; i < files.length; i++) { //for multiple files          
	    (function(file) {
	        var name = file.name;
	        var reader = new FileReader();  
	        reader.onload = function(e) {  
	        	create_line_image(button,e.target.result, name);
	        }
	        reader.readAsDataURL(file);
	    })(files[i]);
	  }
	}

</script>
<div id="page-wrapper" style="min-height: 275px;">
	<!-- Modal -->
        <div class="modal" id="new-message" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog form-review">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabelForm">Send Messages</h4>
                    </div>
                    <div class="modal-body" id='form-message-content'>
                        <div class="panel-heading">

                            <input type="email" id="txt-email" class="form-control input-sm"
                                   placeholder="Type emails here...">
                        </div>

                        <div class="panel-body">
                            <textarea rows="15" id='txt-message'
                                      placeholder="Type your message here" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-info" id='btn-send' onclick="send_form()">Send</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        
                <div class="modal" id="share-form" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog form-review">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabelForm">Share form</h4>
                    </div>
                    <div class="modal-body" id='form-message-content'>
				<div class="panel-heading">
					<input type="email" id="txt-email-share"
						class="form-control input-sm" placeholder="Type emails here..." value="">

				</div>
                    </div>
                    <div class="modal-footer">
					<button class="btn btn-info" id='btn-send' onclick="share_form()">Share</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <div class="modal" id="fill-form" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog form-review">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabelForm">Auto-Fill form</h4>
                    </div>
                    <div class="modal-body" id='form-message-content'>
                    <form id='form-relation-data' onsubmit="return false">
                    <input id='submit-relation' type="submit" style="display: none" value='submit'>
                    <table id='lst-attr' class='form-auto-generate'>
                        <thead>
                        <tr>
                            <th style="width: auto;min-width:270px">Your Current Form's fields</th>
                                    <th colspan="2" >Form related 's fields
                    <select id='template-relate' required
                            onchange="load_template_attr()">
                        <option value=''>Please Select Previous Form to Auto-Fill</option>
                    </select>
                                    </th>
                        </thead>
                        <tbody id = 'body-template-attr'>
                            
                        </tbody>
                                    </td>
                        </tr>
                    </table>
                </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-info" id='btn-send' onclick="fill_form()">Auto-Fill</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        
        
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
					onclick="new_message('#new-message')">Message</button>
					<?php
					
if (isset ( $permission ))
						if ($permission == true) {
							?>
				<button class="btn btn-success" type="button" onclick="save_form()">Save</button>
				<button class="btn btn-warning" type="button" onclick="clear_form()">Clear</button>
				<button class="btn btn-danger" type="button"
					onclick='discard_form()'>Discard</button>
					
<?php }?>
					<?php
					
if (isset ( $own ))
						if ($own == true) {
							?>
					
				<button type="button" class="btn btn-primary"
					onclick="new_message('#share-form')">Share</button>
				<?php }?>
                            				<button class="btn btn-success" type="button" onclick="download_form()">Download</button>
                                                        <button class="btn btn-warning" type="button" onclick="define_relation()">Auto-Fill</button>
			</div>
		</div>


	</div>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-8">

			<div class="panel panel-default">

				<div class="panel-heading">
					<form id='form-info' onsubmit="return false">
						<div class="form-group">
							<select id='cmb-type-id' class="form-control" required
								onchange="load_form()">
								<option value=''>Please Select Document</option>
                                        <?php
																																								foreach ( $group_types as $group ) {
																																									echo "<optgroup label='$group[0]'>";
																																									foreach ( $group [1] as $type )
																																										echo "<option value='" . $type->getId () . "'>" . $type->getTitle () . "</option>";
																																									echo "</optgroup>";
																																								}
																																								?>
                                    </select>

						</div>
						<input type="hidden" id='form-id' value=''> <input type="text"
							placeholder="Type your document title here..."
							class="form-control" id="form-title" required> <input
							type="submit" id='submit-form' style="display: none;">
					</form>
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="row">
						<div id="main-form"></div>
						<!-- /.col-lg-6 (nested) -->
					</div>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->

			<!-- /.panel -->

			<!-- /.panel -->
		</div>
		
		<?php if (isset($list_email)){?>		
		<div class="col-lg-4">
			<div class="chat-panel panel panel-default">
				<div class="panel-heading">
                        	<?php if (count($list_email) > 0){?>
										<div class="form-group">
						<i class="fa fa-comments fa-fw"></i> Sender/Receiver
					</div>

					<select id='email-contact' onchange='load_message()' class="form-control">
                            	<?php
				
                                foreach ( $list_email as $email => $value ) {
					if ($value == 1)
						echo "<option value='" . $email . "' selected>" . $email . "</option>";
					else
						echo "<option value='" . $email . "' >" . $email . "</option>";
				}
				?>
                            </select>
                            <?php } else {
				echo "No Communication";
                                }?>
                        </div>
				<!-- /.panel-heading -->
				<div class="panel-body box">
					<ul class='chat' id='chat-msg'>

					</ul>
				</div>
				<!-- /.panel-footer -->
			</div>
			<!-- /.panel .chat-panel -->
		</div>
		<?php }?>
		<!-- /.col-lg-8 -->
		<div class="col-lg-4">
		<?php if (isset($modification_history)){?>
<div class="table-responsive table-bordered box">
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
			
			foreach ( $modification_history as $history ) {
				echo "<tr><td>" . $history->getModifiedDate ()->format ( "d/m/Y H:i:s" ) . "</td>";
				echo "<td>" . $history->getUser ()->getFirstName () . "</td>";
				echo "<td>" . $history->getUser ()->getLastName () . "</td>";
				echo "<td>Modified</td></tr>";
			}
			echo "<tr><td>" . $form_instance->getCreatedDate ()->format ( "d/m/Y H:i:s" ) . "</td>";
			echo "<td>" . $form_instance->getUser ()->getFirstName () . "</td>";
			echo "<td>" . $form_instance->getUser ()->getLastName () . "</td>";
			echo "<td>Created</td></tr>";
		}
		?>

                                     
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



<?php if ($own == FALSE){?>
<script type="text/javascript">
$(document).ready(function(){
    $('#form-title').attr('disabled', true);  
    
});
</script>
<?php } ?>
