
    var matching = JSON.parse('{"text": ["paragraph","date","email","number","dropdown", "radio"], "paragraph": ["text","date","email","number","dropdown", "radio"], "checkboxes": [], "radio": [], "date": [], "dropdown": [], "number": ["text"], "email" : ["text"], "sign": [], "file": []}');

    function matchType(select){
        select = $(select);
        if (select.val() == '')
            return;

        option = select.find('option:selected');
        type1=(select.attr('data-type'));
    
        type2=(option.attr('data-type'));
        if (type1 == type2)
            return;

        for (i=0;i< (matching[type1]).length;i++)
            if (matching[type1][i] == type2)
                return true;
        select.val('');
        select.change();
        display_msg('Warning','Can not use type of <b>' + type2 + "</b> for a type of <b>" + type1 + "</b>");
    }


    function dislay_key_management(){
        $('#m-1')[0].reset();
        $('#m-2')[0].reset();
        $('#key-management').css('display','block');
        $('#msg-key').css('display','block');
        var msg = 'Your key has been saved in our system. If you would like to use it, click <a href="#" onclick="hide_key_management()">here</a> !!!';
        $('#msg-key').html(msg);
        $('#btn-sign').attr('disabled',true);
        $('#current-key-info').css('display','none');

    }

    function hide_key_management(){
        $('#key-management').css('display','none');
        $('#msg-key').css('display','block');
        var msg = 'Your key has been saved in our system. If you would like to modify, click <a href="#" onclick="dislay_key_management()">here</a> !!!';
        $('#msg-key').html(msg);
        $('#btn-sign').attr('disabled',false);
        $('#current-key-info').css('display','block');
    }


    // This part is used for the cryptography feature
    function load_keys(url){
        $.ajax({
            type: "get",
            url: url,
            success: function(data){
                if (data == ''){
                    dislay_key_management();
                    $('#msg-key').css('display','none');
                }
                else{
                    hide_key_management();
                }
            }
        });
    } 

    function load_keys_info(url){
        $.ajax({
            type: "get",
            url: url,
            dataType: "json",
            success: function(data){
                if (data != ''){
                    $('#o-name').text(data.name);
                    $('#o-email').text(data.email);
                    $('#o-reg').text(data.reg);
                    $('#o-exp').text(data.exp);
                    $('#o-valid').text(data.valid);
                    $('#o-expired').text(data.expired);
                    hide_key_management();
                }
                else{         
                    dislay_key_management();          
                }
            }
        });
    }

    function upload_key(input, id){
        var file = input.files[0];
        (function(file) {
            var name = file.name;
            var reader = new FileReader();  
            reader.onload = function(e) {  
                $(document.getElementById(id)).val(e.target.result);
            }
            reader.readAsText(file);
        })(file);
}