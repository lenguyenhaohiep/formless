           <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                    	<?php echo "<b>".$this->session->userdata('username')."</b> (";?>
                        <?php echo $this->session->userdata('identity').")";?>
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                        
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="<?php echo base_url()?>index.php/auth/edit_user/<?php echo $this->ion_auth->get_user_id();?>"><i class="fa fa-user fa-fw"></i><?php echo lang('user_profile');?></a>
                        </li>
                        <li><a href="<?php echo base_url()?>index.php/user/certificate"><i class="fa fa-gear fa-fw"></i><?php echo lang('settings');?></a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="<?php echo base_url()?>index.php/auth/logout"><i class="fa fa-sign-out fa-fw"></i> <?php echo lang('logout');?></a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>