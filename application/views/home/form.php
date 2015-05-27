<div id="page-wrapper" style="min-height: 275px;">
    <!-- /.row -->
    <div class="row">

        <div class="col-lg-12">
 
        </div>            


    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel-default">

                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Form Title</th>
                                            <?php if ($this->session->userdata('select') == 'sent') echo "<th>Receiver</th>";?>
                                            <?php if ($this->session->userdata('select') == 'inbox') echo "<th>Sender</th>";?>
                                            
                                            <th>Date</th>
                                            <th>Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php 
$i=0;
if (isset($forms)){

		
	foreach ($forms as $form){
		if ($i%2==0)
			echo "<tr class='odd gradeX'>";
		else
			echo "<tr class='even gradeX'>";
		echo "<td><a href='".base_url()."index.php/form/detail/".$form->getId()."'>".$form->getTitle()."</td>";
		echo "<td>".$form->getCreatedDate()->format("d/m/Y H:i:s")."</td>";
		echo "<td>".$form->getType()->getTitle()."</td>";	
		echo "</tr>";
		$i = $i+1;
	}
}
else{
	
	foreach ($emails as $form){
		if ($i%2==0)
			echo "<tr class='odd gradeX'>";
		else
			echo "<tr class='even gradeX'>";
		if ($form['count_inbox'] == 0)
			echo "<td><a href='".base_url()."index.php/form/detail/".$form['form_id']."'>".$form['title']."</a></td>";
		else 
			if ($this->session->userdata('select') == 'sent')
				echo "<td><a href='".base_url()."index.php/form/detail/".$form['form_id']."'>".$form['title']." (".$form['count_inbox'].")</a></td>";
				
				else 
			echo "<td><a href='".base_url()."index.php/form/detail/".$form['form_id']."'><b>".$form['title']." (".$form['count_inbox'].")</b></a></td>";
		
			echo "<td><b>".$form['first_name']." ".$form['last_name']."</b> (".$form['email'].")</td>";
		
		
		echo "<td>".$form['send_date']."</td>";
		echo "<td>".$form['t_title']."</td>";
		$i = $i+1;
		echo "</tr>";
		
	}
}
?>
                                        </tr>
      
                                    </tbody>
                                </table>
                            </div>

                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
</div>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true,
                "aaSorting": []
        });
    });
    </script>