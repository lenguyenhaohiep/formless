<link rel="stylesheet"
      href="<?php echo base_url(); ?>vendor/css/vendor.css" />
<link rel="stylesheet"
      href="<?php echo base_url(); ?>dist/formbuilder.css" />

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
        $.ajax({
            type: "GET",
            url: '<?php echo base_url();?>index.php/template/get_attr/'+$('#cmb-type-id').val(),
            dataType: "json",
            success: function(data){
                attrs_current = data;
                $.each(data, function (i, item){
                    $.each(item, function (key, value){
                        console.log(key,value);
                        tr = $('<tr>');
                        tr.append($('<td>').html(value));
                        $('#lst-attr').append(tr);                        
                    });

                });
            }
        });
        new_message('#new-relation');
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


</script>
<div id="page-wrapper">
    <div class="modal fade" id="form-preview" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog form-review">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabelForm">Preview</h4>
                </div>
                <div class="modal-body" id='form-message-content'></div>
                <div class="modal-footer">
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


    <div class='row'>
        <div class="page-header">
            <button id="btn-new-message" class="btn btn-primary" type="button"
                    onclick="define_relation()">Define Relations</button>

            <button class="btn btn-success" type="button" onclick="save_form(0)">Save</button>
            <button class="btn btn-warning" type="button" onclick="discard_form()">Discard</button>
            <button id="btn-new-message" class="btn btn-primary" type="button"
                    onclick="preview_form()">Preview</button>
        </div>

        <div id='new-relation' class="alert alert-info alert-dismissable"
             style="display: none; background-color: #f5f5f5; border-color: #ddd">
            <div class="panel-heading">
                <h4>Relations between forms
                    <button type="button" class="close" onclick="close_new_message('#new-relation')">&times;</button>
                </h4>
            </div>

            <!-- /.panel-heading -->
            <div class="panel-body">
                <form id='form-relation'>
                    <table id='lst-attr'>
                        <tr>
                            <td>Your Current Template</td>
                                    <td>
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
                                    </td>
                        </tr>
                    </table>
                </form>
            </div>
            <!-- /.panel-body -->
            <div class="panel-heading">

                <button class="btn btn-info" id='btn-send' onclick="send_form()">Define</button>
            </div>

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

