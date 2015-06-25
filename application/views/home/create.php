
<!-- jQuery-Tagging-Tokenizer-Input-with-Autocomplete -->
<script src="<?php echo base_url(); ?>js/tokens.js"></script>
<script src="<?php echo base_url(); ?>dist/jquery.bonsai.js"></script>
<script src="<?php echo base_url(); ?>dist/jquery.qubit.js"></script>
<link href="<?php echo base_url(); ?>dist/jquery.bonsai.css"rel="stylesheet">
<script src="<?php echo base_url(); ?>js/formless.js"></script>

<script type="text/javascript">
var current_email = '<?php echo $this->session->userdata('identity');?>';
var email_share_before = '';
var email_share_current = '';
var email_send = '';
var form_data_before = '';
var create_new=false;
var data_fillable_form;
var title_before;
var title_current;
var share_info;
var view = '<?php if ($this->session->userdata('select') == 'detail') echo "detail"; else echo "create"; ?>';


function initialize(){
    
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
    initialize();
    $("#email-contact").change();
    title_before = $('#form-title').val();

    // tooltip
    $('.tooltip-demo').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    });
});


function load_shared_info(){
            $.ajax({
            url: "<?php echo base_url() ?>index.php/form/get_shared_info/",
            type: "post",
            dataType: 'json',
            data: {form_id: $('#form-id').val()},
            success: function (data) {
                var json = data;
                //update the list of share info
                share_info = data;
                $('#share-info').empty();

                $.each(data, function(){ 
                    $('#share-info').append($('<button>',
                    {
                        class:'btn btn-primary', 
                        onclick: "$('#email-contact-share').val($(this).text());$('#email-contact-share').change();"
                    }
                    ).html(this.email));
                });
            }
        });
}

function load_list_users(){
    $('#email-contact-share').empty();
        $.ajax({
            url: "<?php echo base_url() ?>index.php/user/get_all_emails",
            type: "post",
            dataType: 'json',
            success: function (data) {
                $('#email-contact-share').append($('<option>',{value:''}).html('Please select user to share'));
            
                $.each(data, function(){
                    
                    $('#email-contact-share').append($('<option>',{value:this}).html(this));
                });

                $('#email-contact-share').change();

                $("input.tokens-input-text").attr('placeholder', 'Enter emails...');
                email_share_before = $('#txt-email-share').val();
            }
        });
}



function view_image(img){        
    window.open(img.src,'_blank');
}

	
function display_email(email,date,message){
	html = '<li class="left clearfix">'+
				'<div class="chat-body clearfix">'+
					'<div class="header">'+
						'<strong class="primary-font">'+email+'</strong>'+
						'<br/>'+
						'<small class="text-muted">'+
						'<i class="fa fa-clock-o fa-fw"></i>'+date+
						'</small>'+
					'</div>'+
					'<p>'+message+'</p>'+
				'</div>'+
			'</li>';			

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
                    display_email(item.from,item.date,item.message);
                }
                else {
                    display_email(item.from, item.date, item.message);
                }
              });
          }

        });
    }


function load_share(){
	var action='<?php if ($this->session->userdata('select') == 'detail') echo "form"; else echo "template"; ?>';
    var id=<?php if ($this->session->userdata('select') == 'detail') echo "$('#form-id').val();"; else echo "$('#cmb-type-id').val();"; ?>;
    $('#share-attrs').empty();

	if ($('#email-contact-share').val() !== '')
    
    $.ajax({
        type: "GET",
        url: '<?php echo base_url();?>index.php/'+action+'/get_attr/'+id,
        dataType: "json",
        success: function(data){

            var list_shared_attrs = [];
            //find attributes shared for current user
            $.each(share_info, function(key,value){
                if (value.email == $('#email-contact-share').val()){
                    list_shared_attrs = JSON.parse(value.attrs);
                    return false;
                }
            });

            attrs_current = data;
            root = $('<ol>',{'id':'lstAttr','data-name':'root'});
            header = root;
            current = root;
            $.each(data, function (i, item){
                $.each(item, function (key, value){

                    //check if this attribure has already shared, then display a check in the tree
                    checked = ($.inArray(key,list_shared_attrs));
                    if (checked != -1)
                        checked = 1;
                    else
                        checked = 0; 

                    //the level of tree depends on the type of attribute: header, section and the others
                    if (value.type === 'header'){
                        li = $('<li>',{'data-name':key, 'data-value':value.label, 'data-checked': checked, class:'header-attr'}).html(value.label); 
                        li.append($('<ol>')); 
                        root.append(li); 
                        current = li.find('ol');  
                        header = current;
                    }
                    else if (value.type === 'section'){
                        li = $('<li>',{'data-name':key, 'data-value':value.label,'data-checked': checked, class:'section-attr'}).html(value.label); 
                        li.append($('<ol>')); 
                        header.append(li); 
                        current = li.find('ol');
                    }
                    else{
                        li = $('<li>',{'data-name':key, 'data-value':value.label,'data-checked': checked, class:'attr'}).html(value.label); 
                        li.append($('<ol>')); 
                        current.append(li); 
                    }           
                });

            });
            $('#share-attrs').append(root);


            $('#lstAttr').bonsai({
            	  expandAll: true,
            	  checkboxes: true, // depends on jquery.qubit plugin
            	  createInputs: 'checkbox' // takes values from data-name and data-value, and data-name is inherited
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
    $('#confirm-modal').modal('hide');
	form_id = $('#form-id').val();
	if (form_id !== ''){
		$.ajax({
			  url: "<?php echo base_url() ?>index.php/form/discard/"+$('#form-id')   .val(),
			  success:function(data) {
				  var json = JSON.parse(data);
                        display_msg('info', json.msg);
						if (json.err === 0){
                            setTimeout(function(){ window.location.href='<?php echo base_url();?>index.php/home/create'; }, 2000);
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
    var listAttributes =  $('#share-attrs').serializeArray();
	var form_data = $('#data-form').serializeArray();	

	check=true;

	form1 = $('#form-info')[0];
	form2 = $('#email-info')[0];
	
	if (check===true){
	    if(form1.checkValidity()){
            if (!check_form_edit())
                form_data = "-1";
            
            form_data_before = $('#data-form').serialize();
	    	
            $.ajax({
	  		   type: "POST",
	  		   url: "<?php echo base_url() ?>index.php/form/share/",
	  		   dataType: "json",
	  		   data: {
                    form_id: $('#form-id').val(), 
                    type_id:$('#cmb-type-id').val(), 
                    title:$('#form-title').val(), 
                    to_user:$('#email-contact-share').val(),
                    data_form: form_data,
                    list_attrs: listAttributes
                },
	  		   success:function(data) {
                    $('#form-id').val(data);
                    load_shared_info();
				    display_msg('info', 'Shared succesfully');
                }
            });
        }else
            display_msg('Warning', 'No form to be shared');
    } else{
        display_msg('Warning', msg);
    }
}

function download_form(){
    if ($('#cmb-type-id').val() == '')
        display_msg('Warning', 'Cannot download, form is not identifiable');
    else{
        var form_data = $('#data-form').serializeArray();


        $.ajax({
            type:"POST",
            url: "<?php echo base_url()?>index.php/form/download/",
            data: {
                form_id: $('#form-id').val(), 
                type_id:$('#cmb-type-id').val(), 
                title:$('#form-title').val(), 
                data_form: form_data},
            success: function(data){
                window.location.href="<?php echo base_url()?>index.php/form/getfile/";
            }

        });
    }

	//window.location.href= "<?php echo base_url()?>index.php/form/download/"+$('#form-id').val();
}

function clear_form(){
    $('#confirm-modal').modal('hide');
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
	check = false;
    if (create_new){
        create_new=false;
        return true;
    }
    //if not edit
    if ($('#data-form').serialize() === form_data_before){
        check= false;
    } else{
        //if edit
        check= true;
    }


    if (check == false)
        //if not change title
        if (title_before != $('#form-title').val())
            check= true;
        else
        	check= false;

	return check;
}


function save_form(mode){

    $('#btn-discard').attr('disabled',false);

	var form_data = $('#data-form').serializeArray();	        
	$('#submit-form').click();

	form = $('#form-info')[0];
	    if(form.checkValidity()){
                if (!check_form_edit()){
                    if (mode == 1)
                        display_msg('info', 'Saved succesfully');
                    return;
                }
                form_data_before = $('#data-form').serialize();
                title_before = $('#form-title').val();
                $('#btn-download').removeAttr('disabled');
	    	$.ajax({
	  		  type: "POST",
	  		  url: "<?php echo base_url()?>index.php/form/save/",
	  		  dataType: "json",
	  		  data: {	form_id: $('#form-id').val(), 
		  		  		type_id:$('#cmb-type-id').val(), 
		  		  		title:$('#form-title').val(),
		  		  		data_form: form_data},
	  		  success:function(data) {
	  			$('#cmb-type-id').prop('disabled', true);
		  		  if (data != ''){
	  			  	$('#form-id').val(data);
                    if (mode==1)
	  			  	   display_msg('info', 'Saved succesfully');
		  		  }
	  		  }
	  	});
    }
}

    function autofill(){

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
                        if (value.type !== 'header' && value.type !== 'section'){
                            tr = $('<tr>');
                            tr.append($('<td>').html(value.label));
                            td2 = $('<td>');
                            txt = 'Please select form to fill';
                            prefix = 'f';
                            td2.append($('<select>',{id: prefix+key, name: prefix+key,'data-type':value.type, onchange: 'matchType(this); display_field(this)'}).append($('<option>',{value:''}).html(txt)));

                            prefix = 'd';
                            td3 = $('<td>').append($('<div>',{id: prefix+key}));
                            tr.append(td2);
                            tr.append(td3);
                            $('#body-template-attr').append(tr);

                    }
                    });

                });
            }
        });
        $('#fill-form').modal();
    }



    //auto-fill-form
    function add_fields(data){
        var txt;
        if ($('#template-relate').val()=='')
            txt = 'Please select form to fill';
        else
            txt = 'Please select field';
        var option='<option value="">'+txt+'</option>';
        var prefix= 'f';
        $.each(data, function (i, item){
            $.each(item, function (key, value){
                if (value.type !== 'header' && value.type !== 'section')
                    option += '<option data-type="'+value.type+'" value="'+key+'">'+value.label+'</option>';
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

    }

    function detect_relation(type1, type2){
        //load current relation
        $.ajax({
            type:"GET",
            url : "<?php echo base_url()?>index.php/form/fill/"+type1+"/"+type2,
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

    
    function match_attributes(){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>index.php/form/get_attr/" + $('#template-relate').val(),
            dataType: 'json',
            success: function (data) {
                add_fields(data);
                //load data of the fillable form
                $.ajax({
                    type:"GET",
                    url : "<?php echo base_url()?>index.php/form/get_data_array/"+$('#template-relate').val(),
                    dataType: 'json',
                    success: function(data){
                    	data_fillable_form = data;
                        type1 = $('#cmb-type-id').val();
                        type2 = $('#template-relate').val();
                        detect_relation(type1, type2);
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

function sign(id,id1,id2){

    var msg = '';
    if ($(document.getElementById(id1)).val()==='')
        msg += 'Please Enter Your Signature\'s First Name </br>';
    if ($(document.getElementById(id2)).val()==='')
        msg += 'Please Enter Your Signature\'s Last Name';
    if (msg === ''){   
         //Sign Process
        load_keys("<?php echo base_url()?>index.php/user/loadkey"); 
        $('#sign-form').modal();
    	}
    else
        display_msg('Warning', msg);
}


function close_new_message(id){
	$(id).css('display','none');
}

function validate_form(){
	$('#validate').click();
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
					$('input:radio[name="'+this.name+'"]').filter('[value="'+div.text()+'"]').attr('checked', true);
				}

				if (type === 'checkbox'){
					name = this.name;
					div.find('span').each(function(){
						$('input:checkbox[name="'+name+'"]').filter('[value="'+$(this).text()+'"]').attr('checked', true);
					});
				}

				if (type === 'file'){
					button=this;
					div.find('img').each(function(){
						name='';
			        	create_line_image(button,this.src, name);
						
					});
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
	  
	  $(div_data).append("<div class='img-data' id='"+id_img_data+"-div' ><img  onclick='view_image(this)' class='image-select' /></div>");		  
	  img = '#'+id_img_data + '-div img';
	  $(img).attr("src",source);
	  $(img).after('<button class="btn btn-danger select-file" type="button" onclick="delete_img(\''+id_img_data+'-div\');">Delete</button>');
// 	  $(img).after('<span>'+name+'</span><br/>');
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

function load_file(){
        var file = document.getElementById('upload-file').files[0];
        (function(file) {
            var name = file.name;
            var reader = new FileReader();  
            reader.onload = function(e) {  
                $.ajax({
                    type:'POST',
                    url: '<?php echo base_url()?>index.php/form/fill_upload',
                    dataType: 'json',
                    data: {file: e.target.result},
                    success: function(data){
                        //add fields to each combobox
                        add_fields(data.attributes);
                        //get data to fill    
                        data_fillable_form = data.data;
                        //detect relation
                        type1 = $('#cmb-type-id').val();
                        type2 = data.type_id;
                        detect_relation(type1, type2);

                    }
                });
            }
            reader.readAsText(file);
        })(file);
}



function display_file(){
        var file = document.getElementById('browse-file').files[0];
        (function(file) {
            var name = file.name;
            var reader = new FileReader();  
            reader.onload = function(e) {  
                $.ajax({
                    type:'POST',
                    url: '<?php echo base_url()?>index.php/form/view_upload',
                    dataType: 'json',
                    data: {file: e.target.result},
                    success: function(data){
                        if (data != ''){
                            $('#cmb-type-id').val(data.type_id);
                            $('#form-title').val(data.title);
                            $('#form-id').val(data.form_id);
                            $('#main-form').html(data.form);
                            //$('#cmb-type-id').change();
                        }
                    }
                });
            }
            reader.readAsText(file);
        })(file);
}

function display_confirm(title, msg, func){
    $('#myModalLabel2').html(title);
    $('#message-content2').html(msg);
    $('#confirm-modal').modal();
    $('#btn-yes').attr('onclick',func);
}

$(document).ready(function(){
    if (view == 'detail'){
        $('#btn-clear').css('display','none');
        $('#btn-browse').css('display','none');
    }

    $('#cmb-type-id').change(function(){
        if ($(this).val() == ""){
            //disable all buttons
            $('#btn-message').attr('disabled',true);
            $('#btn-validate').attr('disabled',true);
            $('#btn-save').attr('disabled',true);
            $('#btn-clear').attr('disabled',true);
            $('#btn-discard').attr('disabled',true);
            $('#btn-share').attr('disabled',true);
            $('#btn-download').attr('disabled',true);
            $('#btn-fill').attr('disabled',true);
            $('#btn-export').attr('disabled',true);
        }
        else{
            //enable all buttons
            $('#btn-message').attr('disabled',false);
            $('#btn-validate').attr('disabled',false);
            $('#btn-save').attr('disabled',false);
            $('#btn-clear').attr('disabled',false);
            $('#btn-share').attr('disabled',false);
            $('#btn-download').attr('disabled',false);
            $('#btn-fill').attr('disabled',false);
            $('#btn-export').attr('disabled',false);
        }
    }
    );

    $('#cmb-type-id').change();
});


function save_key(){
    var msg = '';
        if ($('#priv').val()==='')
            msg += 'Please enter/choose private key </br>';
        if ($('#pub').val()==='')
            msg += 'Please enter/choose public key';
        if (msg != '')    
            display_msg('Warning', msg);
        else{
            $.ajax({
                type: 'post',
                url: '<?php echo base_url()?>index.php/user/savekey',
                dataType: 'json',
                data: {priv: $('#priv').val(), pub: $('#pub').val()},
                success:function(data){
                    if (data != ''){
                        $('#btn-sign').attr('disabled',false);
                    }
                }
            });
        }
}

function sign_form(){
    save_form(2);
    $.ajax({
        type: "post",
        url: '<?php echo base_url()?>index.php/form/sign',
        data: {form_id: $('#form-id').val()},
        success: function(data){
            alert(data);
        }
    });
}

function gen_key(){
    if ($('#pass').val() == ''){
        display_msg('Warning','Please enter passphrase');
    }else{
        $.ajax({
            type: 'post',
            url: '<?php echo base_url()?>index.php/user/genkey',
            dataType: 'json',
            data: {pass: $('#pass').val()},
            success:function(data){
                $('#btn-sign').attr('disabled',false);
            }
        });
    }
}

</script>

<div id="page-wrapper" style="min-height: 275px;">
	<!-- Modal -->

        <div class="modal" id="sign-form" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog form-review">
                <div class="modal-content">
                    <div class="modal-header control">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabelForm">Sign</h4>
                    </div>
                    <div class="modal-body" id='form-message-content'>
                        <div id='msg-key' style= "display:none">
                        </div>
                        <div id='key-management'>
                            <div id='sign-method'>
                                <fieldset>
                                    <legend>Key Management</legend>
                                    <p><input type='radio' name='signature' id='signature' value='1' onclick='$("#gen-cert").css("display","none"); $("#upload-cert").css("display","block");' checked/> Use your valid certificate</p>
                                    <p><input type='radio' name='signature' id='signature' value='2' onclick='$("#upload-cert").css("display","none"); $("#gen-cert").css("display","block");'/> Use the certificate of this site</p>
                                </fieldset>
                            </div>
                            <div id='upload-cert' style="display:true">
                                <form id='m-1'>
                                    <fieldset>
                                        <legend>Key's info</legend>
                                        <label>Public key</label>
                                        <textarea id='pub' placeholder='Paste your public key here or upload file'></textarea>
                                        <input type='file' onchange="upload_key(this,'pub');"/>

                                        <label>Private key</label>
                                        <textarea id='priv' placeholder='Paste your secret key here or upload file'></textarea>
                                        <input type='file' onchange="upload_key(this,'priv');"/>
                                        <span><i>Your key's infomation will be stored in the server</i></span>

                                        <p><button class='btn btn-primary' onclick='save_key()'>Save</button></p>

                                    </fieldset>
                                </form>
                            </div>
                            <div id='gen-cert' style= "display:none">
                                <form id='m-2'>
                                    <fieldset>
                                        <legend>Key's info</legend>
                                        <label>Passphrase</label>
                                        <textarea id='pass' placeholder='Enter a passphrase to generate your keys'></textarea>
                                        <span><i>Your key's infomation will be generated using your information saved in this site</i></span>
                                        <p><button class='btn btn-primary' onclick='gen_key()'>Generate</button></p>
                                        <label>Public key</label>
                                        <textarea placeholder='Your public key here' disabled></textarea>
                                        <label>Private key</label>
                                        <textarea placeholder='Your your secret key here' disabled></textarea>
                                    </fieldset>
                                </form>
                            </div>
                            <div id='save-cert' style="display:none">
                                <form id='key-data'>
                                    <input type='hidden' id='public-key' value=''/>
                                    <input type='hidden' id='private-key' value=''/>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" id='btn-sign' onclick="sign_form()">Sign</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>



        <div class="modal" id="new-message" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog form-review">
                <div class="modal-content">
                    <div class="modal-header control">
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
                        <button class="btn btn-primary" id='btn-send' onclick="send_form()">Send</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
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
                    <div class="modal-header control">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabelForm">Share form</h4>
                    </div>
                    <div class="modal-body" id='form-message-content'>
				<div class="panel-heading">
				
                    <div class='block-left-share'>
                        <h4>Form already shared with</h4>
                        <div id='share-info' class='share-info'></div>  
                    </div>

					<div class='block-right-share'>
                        <h4>Sharing's details</h4>			
                        <select id='email-contact-share' onchange='load_share()' class="form-control"></select> 
                        <form id = 'share-attrs' class='share-attr-info'></form>
				        <button class="btn btn-primary" id='btn-send' onclick="share_form()">Share</button>

                    </div>
       				
				</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
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
                        <div class="modal-header control">
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
                                    <th style="width: auto;min-width:270px">
                                        Your Current Form's fields
                                    </th>
                                    <th>
                                        <label for='template-relate'>Form related 's fields</label>
                                    </th>
                                    <th>
                                        <label for='upload-file'>or Upload</label>
                                    </th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th>
                                        <select id='template-relate' required onchange="match_attributes()">
                                            <option value=''>Select a Form</option>
                                        </select>
                                    <th>
                                        <input id='upload-file' type='file' value='' onchange='load_file()'/>
                                    </th>
                                </tr>
                                
                            </thead>
                            <tbody id = 'body-template-attr'>
                            </tbody>
                        </table>
                    </form>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" id='btn-send' onclick="fill_form()">Auto-Fill</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        
        
	<div class="modal" id="myModal" tabindex="-1" role="dialog"
		aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width: 300px">
			<div class="modal-content">
				<div class="modal-header message">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel"></h4>
				</div>
				<div class="modal-body" id='message-content'></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>


    <div class="modal" id="confirm-modal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 300px">
            <div class="modal-content">
                <div class="modal-header message">
                    <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel2"></h4>
                </div>
                <div class="modal-body" id='message-content2'></div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id='btn-yes' onclick="">Yes</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

	<!-- /.modal -->
	<div class="row">
		<div class="col-lg-12">
			<div class="page-header tooltip-demo">
				<button id='btn-message' class="btn btn-primary" type="button" onclick="new_message('#new-message')" data-toggle="tooltip" data-placement="bottom" data-original-title="Send messages to others users">Message</button>
    		<?php
    		    if (isset ( $permission ))
    				if ($permission == true) {?>
				<button id="btn-validate" class="btn btn-primary" type="button" onclick="validate_form()" data-toggle="tooltip" data-placement="bottom" data-original-title="Check the required information in the form">Validate</button>
				<button id="btn-save" class="btn btn-primary" type="button" onclick="save_form(1)" data-toggle="tooltip" data-placement="bottom" data-original-title="Save and store the form in the server">Save</button>
				<button id="btn-clear" class="btn btn-primary" type="button" onclick='display_confirm("Warning","Do you want to clear form", "clear_form()")' data-toggle="tooltip" data-placement="bottom" data-original-title="Clear the current form">Clear</button>
				<button id="btn-discard" class="btn btn-primary" type="button" onclick='display_confirm("Warning","Do you want to delete", "discard_form()")' data-toggle="tooltip" data-placement="bottom" data-original-title="Delete permanently the form">Discard</button>			
            <?php }?>
			<?php
				if (isset ( $own ))
					if ($own == true) {?>
				<button id="btn-share" type="button" class="btn btn-primary" onclick="load_shared_info(); load_list_users(); new_message('#share-form');" data-toggle="tooltip" data-placement="bottom" data-original-title="Share the form to others users">Share</button>
			<?php }?>
                <button id='btn-download' class="btn btn-primary" type="button" onclick="download_form()" data-toggle="tooltip" data-placement="bottom" data-original-title="Download the form as a text (*.txt) file">Download</button>
                <button id="btn-fill" class="btn btn-primary" type="button" onclick="autofill()" data-toggle="tooltip" data-placement="bottom" data-original-title="Fill out automatically a form using previous forms">Auto-Fill</button>
                <button id="btn-browse" class="btn btn-primary" type="button" onclick="$('#browse-file').click();" data-toggle="tooltip" data-placement="bottom" data-original-title="Browse and view a form from your computer">Browse</button>
                <input id='browse-file' accept="text/plain" type='file' onchange='display_file()' style="display:none"/>
                <button id='btn-export' class="btn btn-primary" type="button" onclick="export_pdf()" data-toggle="tooltip" data-placement="bottom" data-original-title="Export form to PDF (*.pdf) format">Export PDF</button>

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
