            <!-- /.navbar-top-links -->

            <div role="navigation" class="navbar-default sidebar">
                <div class="sidebar-nav navbar-collapse">
                    <ul id="side-menu" class="nav in">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" placeholder="<?php echo lang('search_form');?>" class="form-control">
                                <span class="input-group-btn">
                                <button type="button" class="btn btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href="<?php echo base_url();?>/index.php/home/create" class="<?php if ($this->session->userdata('select') == 'create') echo 'active';?>"><i class="fa fa-pencil fa-fw"></i> <?php echo lang('form_create');?></a>
                        </li>          
                        
                        <li>
                            <a href="<?php echo base_url();?>/index.php/home/index" class="<?php if ($this->session->userdata('select') == 'inbox') echo 'active';?>"><i class="fa fa-envelope-o fa-fw"></i> <?php echo lang('form_inbox');?></a>
                        </li>

                        <li>
                            <a href="<?php echo base_url();?>/index.php/home/sent" class="<?php if ($this->session->userdata('select') == 'sent') echo 'active';?>"><i class="fa fa-send fa-fw"></i> <?php echo lang('form_sent');?></a>
                        </li>
                        
                        <li>
                            <a href="<?php echo base_url();?>/index.php/home/draft" class="<?php if ($this->session->userdata('select') == 'draft') echo 'active';?>"><i class="fa fa-table fa-fw"></i> <?php echo lang('form_draft');?></a>
                        </li>
                        
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->