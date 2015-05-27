            <!-- /.navbar-top-links -->

            <div role="navigation" class="navbar-default sidebar">
                <div class="sidebar-nav navbar-collapse">
                    <ul id="side-menu" class="nav in">
        
                        
                        <li>
                            <a href="<?php echo base_url();?>index.php/home/index" class="<?php if ($this->session->userdata('select') == 'inbox') echo 'active';?>"><i class="fa fa-envelope-o fa-fw"></i> 
                            
                            <?php 
                            $inbox=$this->session->userdata('inbox');
                            if ($inbox != "0")
                            	echo "<b>".lang('form_inbox')." (".$inbox.")</b>";
                            	else 
                            echo lang('form_inbox');?>
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo base_url();?>index.php/home/sent" class="<?php if ($this->session->userdata('select') == 'sent') echo 'active';?>"><i class="fa fa-send fa-fw"></i> <?php echo lang('form_sent');?></a>
                        </li>
                                                <li>
                            <a href="<?php echo base_url();?>index.php/home/create" class="<?php if ($this->session->userdata('select') == 'create') echo 'active';?>"><i class="fa fa-pencil fa-fw"></i> <?php echo lang('form_create');?></a>
                        </li>  
                        <li>
                            <a href="<?php echo base_url();?>index.php/home/draft" class="<?php if ($this->session->userdata('select') == 'draft') echo 'active';?>"><i class="fa fa-table fa-fw"></i> <?php echo lang('form_draft');?></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url();?>index.php/home/mydocuments" class="<?php if ($this->session->userdata('select') == 'mydocuments') echo 'active';?>"><i class="fa  fa-folder-open-o  fa-fw"></i> <?php echo lang('form_mydocument');?></a>
                        </li>                        
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->