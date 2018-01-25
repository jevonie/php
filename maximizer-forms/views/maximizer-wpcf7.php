<div class='container'>
    <h1>Maximizer web to lead forms </h1>
    <div class='panel panel-default'>
        <div class='panel-heading'><span class="glyphicon glyphicon-list" aria-hidden="true"></span>Maximizer wpcf7 settings </div>
        <div class="panel-body">
        <div class="btn-group pull-right">
            <a href="admin.php?page=analytify-dashboard&mypage=maximizer-settings&listid=<?php echo $_GET['listid'];?>&desc=<?php echo $_GET['desc']?>" class="btn btn-default btn-sm">BACK</a>
        </div>
            <?php  if(isset($_GET['del'])){ global $wpdb;  $wpdb->delete( 'wp_maximizer_wpcf7', array( 'wpcf7_id' => $_GET['del'] ) ); } ?>
            <div id="form-acounts">
                <table class="table table-striped table-bordered">
                    <thead>
                    <td>linked fields id</td>
                    <td>wpcf7 names</td>
                    <td>maximizer name</td>
                    <td>Action</td>
                    </thead>
                    <tbody>
                    <?php
                    global $wpdb;
                    $results = $wpdb->get_results("SELECT * FROM wp_maximizer_wpcf7 WHERE account_id =".$_GET['listid']);
                    if(!empty($results)) {
                        //echo $results[0]->action;
                        foreach($results as $r) {
                            echo"<tr>";
                            echo"<td>$r->wpcf7_id</a></td>";
                            echo"<td class='fl-wpcf7-name'>$r->wpcf7_names</td>";
                            echo"<td class='fl-max-name'>$r->maximizer_name</td>";
                            echo"<td><a href='#' data-id='$r->wpcf7_id' class='label label-info wpcf7-edit' data-toggle='modal' data-target='#wpcf7_modal'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span> Edit</a> <label data-href='admin.php?page=analytify-dashboard&mypage=maximizer-wpcf7&listid=".$_GET['listid']."&desc=".$_GET['desc']."&del=".$r->wpcf7_id."' class='label label-danger wpcf7-delete'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> delete</label></td>";
                            echo"</tr>";
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>


            <div class='panel-footer'>
            <button class="btn btn-success" data-toggle="modal" data-target="#wpcf7_modal"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create link </button>
            </div>
        </div>
    </div>
    <div id="wpcf7-del" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Confirmation</h4>
                </div>
                <div class="modal-body">
                    <h3>Are you sure to delete this item?</h3>
                </div>
                <div class="modal-footer">
                    <a href="" class="btn btn-danger" id="delete-wpcf7"><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> delete</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>
<div id="wpcf7_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Link Fields</h4>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                        <div class="container">
                            <input class="form-control" type="hidden" name="account_id" value="<?php echo $_GET['listid'];  ?>"/>
                            <input class="form-control wpcf7_id" type="hidden" name="wpcf7_id" />
                            <div class="form-group row">
                                <label for="example-text-input" class="col-2 col-form-label">maximizer name</label>
                                <div class="col-10">
<!--                                    <input >-->
                                    <select class="form-control dp-maxi" type="text" name="maximizer_name"  required id="example-text-input">
                                        <?php
                                        $max_results = $wpdb->get_results("SELECT * FROM wp_maximizer WHERE account_id =".$_GET['listid']);
                                        if(!empty($max_results)) {
                                            foreach($max_results as $r) {
                                                echo "<option value='".$r->givenname."'>" .$r->name."</option>";
                                            }
                                        }else{
                                            echo "<option>No Data</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-2 col-form-label">wpcf7 names</label>
                                <div class="col-10">
                                    <textarea class="form-control txt-wpcf7" name="wpcf7_names"></textarea>
                                </div>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-success" name="link" value="link" id="ok_delete"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
