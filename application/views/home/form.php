<div id="page-wrapper" style="min-height: 275px;">
    <!-- /.row -->
    <div class="row">

        <div class="col-lg-12">
            <div class="page-header">
                <button class="btn btn-default" type="button">Discard</button>
                <button class="btn btn-primary" type="button">Send</button>
                <button class="btn btn-success" type="button">Achieved</button>
                <button class="btn btn-info" type="button">Sign</button>
                <button class="btn btn-warning" type="button">Clear</button>
                <button class="btn btn-danger" type="button">Delete</button>

            </div>
        </div>            


    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">

                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Rendering engine</th>
                                            <th>Browser</th>
                                            <th>Platform(s)</th>
                                            <th>Engine version</th>
                                            <th>CSS grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="odd gradeX">
                                            <td>Trident</td>
                                            <td>Internet Explorer 4.0</td>
                                            <td>Win 95+</td>
                                            <td class="center">4</td>
                                            <td class="center">X</td>
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
                responsive: true
        });
    });
    </script>