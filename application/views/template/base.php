<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
</html>