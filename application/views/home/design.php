<link rel="stylesheet"
      href="<?php echo base_url(); ?>vendor/css/vendor.css" />
<link rel="stylesheet"
      href="<?php echo base_url(); ?>dist/formbuilder.css" />
<script src="<?php echo base_url(); ?>js/formless.js"></script>

<script type="text/javascript">
    var template_before = '';
    var json_template = null;
    var change = false;
    var attrs_current;
    var attrs_auto_fill;
    function save_form(mode) {
        $('#submit-form').click();
        
        if (!change || (template_before === json_template)){
            if (mode === 1)
                dislay_template_preview();
            return;
        }

        form = $('#form-info')[0];
        if (form.checkValidity()) {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>index.php/template/save/",
                dataType: "json",
                data: {json_template: json_template, type_id: $('#cmb-type-id').val()},
                success: function (data) {
                    if (data != '') {
                        //display_msg('info', "Saved successfully");
                    }
                    if (mode==1){
                        dislay_template_preview();
                    }
                }
            });
        }
        template_before = json_template;

    }
    
    function dislay_template_preview(){
                            $.ajax({
                            url: "<?php echo base_url() ?>index.php/form/get_template/" + $('#cmb-type-id').val(),
                            success: function (data) {
                                $('#form-message-content').html(data);
                                jq('#form-preview').modal();
                            }
                        });
    }
    
    function load_template_attr(){
            $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>index.php/template/get_attr/" + $('#template-relate').val(),
            dataType: 'json',
            success: function (data) {
                var txt;
                if ($('#template-relate').val()=='')
                    txt = 'Please select template to define';
                else
                    txt = 'Please select field';
                var option='<option value="">'+txt+'</option>';
                $.each(data, function (i, item){
                    $.each(item, function (key, value){
                        if (value.type !== 'header' && value.type !== 'section'){
                            option += '<option data-type="'+value.type+'" value="'+key+'">'+value.label+'</option>';
                        }
                    });
                });
                $.each(attrs_current, function (i, item){                   
                    $.each(item, function (key, value){
                        var select =  $(document.getElementById(key));
                        select.empty();
                        select.append(option);
                    });

                });
                
                //load current relation
                $.ajax({
                    type:"GET",
                    url : "<?php echo base_url()?>index.php/template/get_relation/"+$('#cmb-type-id').val()+"/"+$('#template-relate').val(),
                    dataType: 'json',
                    success: function(data){
                        $.each(data, function(key,item){
                            var select =  $(document.getElementById(key));
                            if (select !== undefined)
                                select.val(item);
                        });
                    }
                });
            }
        });
    }

    function load_template() {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>index.php/template/get_template/" + $('#cmb-type-id').val(),
            dataType: 'json',
            success: function (data) {
                reset_template(data.fields);
                template_before = JSON.stringify(data);
                json_template = template_before;
            }
        });
    }

    function new_message(id) {
        $(id).css('display', 'block');
    }

    function close_new_message(id) {
        $(id).css('display', 'none');
    }

    function display_msg(title, msg) {
        $('#myModalLabel').html(title);
        $('#message-content').html(msg);
        jq('#myModal').modal();
    }

    function discard_form() {
        jq('#confirm-modal').modal('hide');

        form_id = $('#cmb-type-id').val();
        if (form_id != '') {
            $.ajax({
                url: "<?php echo base_url() ?>index.php/template/discard/" + $('#cmb-type-id').val(),
                success: function (data) {
                    var json = JSON.parse(data);
                    display_msg('info', json.msg);
                    if (json.err == 0) {
                        $('#cmb-type-id').val('');
                        $('#cmb-type-id').change();
                    }
                }
            });
        }
        else {
            display_msg('Warning', 'No template to be discarded')
        }
    }

    function preview_form() {
        if ($('#cmb-type-id').val() === '') {
            display_msg('Warning', 'Please Select Template');
            return;
        }
            save_form(1);
    }
    
    function define_relation(){
        if ($('#cmb-type-id').val() == ''){
            display_msg('Warning', 'Please Select Template');
            return;
        }
        $('#template-relate').val('');
        $('#template-relate option').css('display','block');
        $('#template-relate option[value='+$('#cmb-type-id').val()+']').css('display','none');
        $.ajax({
            type: "GET",
            url: '<?php echo base_url();?>index.php/template/get_attr/'+$('#cmb-type-id').val(),
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
                            txt = 'Please select template to define';
                            td2.append($('<select>',{id: key, name: key, 'data-type':value.type, 'onchange': 'matchType(this)'}).append($('<option>',{value:''}).html(txt)));
                            tr.append(td2);
                            $('#body-template-attr').append(tr);
                        }
                    });

                });
            }
        });
        jq('#form-relation').modal();
    }

    function reset_template(_bootstrapData) {
        $('.fb-main').empty();
        if ($('#cmb-type-id').val() !== '')
        $(function () {
            fb = new Formbuilder({
                selector: '.fb-main',
                bootstrapData: _bootstrapData
            });

            fb.on('save', function (payload) {
                console.log(payload);
                json_template = payload;
                change = true;
                if (json_template !== template_before){
                    save_form(0);
                }
            });
        });
    }

    
    function define_form(){
        $('#submit-relation').click();
        form = $('#form-relation-data')[0];
        if (form.checkValidity()){
            $.ajax({
                type: "POST",
                url : "<?php echo base_url() ?>index.php/template/relation",
                data: {type_id1: $('#cmb-type-id').val(), type_id2: $('#template-relate').val(), data: $('#form-relation-data').serializeArray()},
                success: function(data){
                	jq('#form-relation').modal('hide');
                    display_msg('info', data);
                }
            });
        }
    }

    function display_confirm(title, msg, func){
        $('#myModalLabel2').html(title);
        $('#message-content2').html(msg);
        jq('#confirm-modal').modal();
        $('#btn-yes').attr('onclick',func);
    }


    $( document ).ready(function() {
        jq('.tooltip-demo').tooltip({
            selector: "[data-toggle=tooltip]",
            container: "body"
        });

        $('#cmb-type-id').change(function(){
            if ($(this).val() == ""){
                //disable all buttons
                $('#btn-relation').attr('disabled',true);
                $('#btn-discard').attr('disabled',true);
                $('#btn-save').attr('disabled',true);
                $('#btn-preview').attr('disabled',true);

            }
            else{
                //enable all buttons
                $('#btn-relation').attr('disabled',false);
                $('#btn-discard').attr('disabled',false);
                $('#btn-save').attr('disabled',false);
                $('#btn-preview').attr('disabled',false);
            }
        }
        );

        $('#cmb-type-id').change();
    });



</script>
<div id="page-wrapper">
    
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


        <div class="modal" id="form-relation" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog form-review">
            <div class="modal-content">
                <div class="modal-header control">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabelForm">Define Form Relation</h4>
                </div>
                <div class="modal-body" id='form-relation-content'>
                    <form id='form-relation-data' onsubmit="return false">
                    <input id='submit-relation' type="submit" style="display: none" value='submit'>
                    <table id='lst-attr' class='form-auto-generate'>
                        <thead>
                        <tr>
                            <th style="width: auto;min-width:270px">Your Current Template's fields</th>
                                    <th>Template related 's fields
                    <select id='template-relate' required
                            onchange="load_template_attr()">
                        <option value=''>Please Select Document</option>
                        <?php
                        foreach ($group_types as $group) {
                            echo "<optgroup label='$group[0]'>";
                            foreach ($group[1] as $type)
                                echo "<option value='" . $type->getId() . "'>" . $type->getTitle() . "</option>";
                            echo "</optgroup>";
                        }
                        ?>
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
                    <button class="btn btn-primary" id='btn-send' onclick="define_form()">Define</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    
    <div class="modal" id="form-preview" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog form-review">
            <div class="modal-content">
                <div class="modal-header control">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabelForm">Preview</h4>
                </div>
                <div class="modal-body" id='form-message-content'>

                </div>
                <div class="modal-footer">
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
    <!-- /.modal -->


    <div class='row'>
        <div class="page-header tooltip-demo">
            <button id="btn-relation" class="btn btn-primary" type="button" onclick="define_relation()" data-toggle="tooltip" data-placement="bottom" data-original-title="Define the relations amongs different templates">Relations</button>
            <button id="btn-save" class="btn btn-primary" type="button" onclick="save_form(0)" data-toggle="tooltip" data-placement="bottom" data-original-title="Save the template">Save</button>
            <button id="btn-discard" class="btn btn-primary" type="button" onclick='display_confirm("Warning","Do you want to delete", "discard_form()")' data-toggle="tooltip" data-placement="bottom" data-original-title="Delete permanently the template">Discard</button>
            <button id="btn-preview" class="btn btn-primary" type="button" onclick="preview_form()" data-toggle="tooltip" data-placement="bottom" data-original-title="Review the template">Preview</button>
        </div>


        <div class="panel-heading">
            <form id='form-info' onsubmit="return false">
                <div class="form-group">
                    <select id='cmb-type-id' class="form-control" required
                            onchange="load_template()">
                        <option value=''>Please Select Document</option>
                        <?php
                        foreach ($group_types as $group) {
                            echo "<optgroup label='$group[0]'>";
                            foreach ($group[1] as $type)
                                echo "<option value='" . $type->getId() . "'>" . $type->getTitle() . "</option>";
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
<script type="text/javascript">
    var jq = jQuery.noConflict();
</script>
<script src="<?php echo base_url(); ?>vendor/js/vendor.js"></script>
<script src="<?php echo base_url(); ?>dist/formbuilder.js"></script>

