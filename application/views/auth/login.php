<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title><?php echo $title;?></title>
        <?php echo $css_javascript;?>
      
    </head>

    <body>

        <div class="container">
            <div class="row">
                
                <div class="col-md-4 col-md-offset-4">
                    
                    <div class="login-panel panel panel-default">
                        <?php if ($message != "") { ?>
                            <div id="infoMessage" class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <?php echo $message; ?>
                            </div>
                        <?php } ?>

                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo lang('login_heading'); ?></h3>
                        </div>
                        <div class="panel-body">
                            <?php echo form_open("auth/login"); ?>
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="<?php echo lang('login_identity_label'); ?>" id="identity" name="identity" type="email" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="<?php echo lang('login_password_label'); ?>" id= 'password' name="password" type="password" value="">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" id= 'remember' type="checkbox" value="1">    <?php echo lang('login_remember_label'); ?>

                                    </label>
                                </div>
                                <p><a href="forgot_password"><?php echo lang('login_forgot_password'); ?></a></p>
                                <input class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="<?php echo lang('login_submit_btn') ?>"  />
                            </fieldset>

                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery -->
        <script src="../bower_components/jquery/dist/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="../dist/js/sb-admin-2.js"></script>

    </body>

</html>
