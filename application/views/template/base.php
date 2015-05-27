<!DOCTYPE html>
<html>

    <head>
 <meta charset="UTF-8"> 
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title><?php echo $title; ?></title>
        <?php echo $css_javascript; ?>

    </head>
    <body>
        <div id="wrapper">
            <nav style="margin-bottom: 0" role="navigation" class="navbar navbar-default navbar-static-top">
                <?php echo $logo; ?>
                <?php echo $top_menu; ?>
                <?php echo $left_menu; ?>            
            </nav>
            <?php echo $main_content; ?>
            <?php echo $footer; ?>
        </div>

    </body>
    <script type="text/javascript">
    	$(document).ready(function (){
    		h = window.innerHeight - 1;
    		$("#wrapper").css('max-height', h + 'px');
    		$("#wrapper").css('min-height', h + 'px');
    		$("#page-wrapper").css('overflow-x', 'scroll');
    		$("#page-wrapper").css('max-height', h - $('.navbar').height() + 'px');
    		$("#page-wrapper").css('min-height', h - $('.navbar').height() + 'px');
    	});	
    </script>
</html>