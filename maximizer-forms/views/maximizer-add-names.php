<div class='container'>
    <h1>Maximizer web to lead forms </h1>
    <div class='panel panel-default'>
    <div class='panel-heading'><span class='glyphicon glyphicon-cog' aria-hidden='true'></span> Add names</div>
       <div class="panel-body">
       <div class="btn-group pull-right">
           <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModals">Database import</a>
           <a href="admin.php?page=analytify-dashboard&mypage=maximizer-settings&listid=<?php echo $_GET['listid'];?>&desc=<?php echo $_GET['desc']?>" class="btn btn-default btn-sm">BACK</a>
       </div>
       	 <form action="" method="POST">
       	  <div class="col-md-12">
       	  	 <input class="form-control myurl" type="hidden" name="account_id" value="<?php echo $_GET['listid'];  ?>" required id="example-text-input">
			  <div class="form-group row">
				  <label for="example-text-input" class="col-2 col-form-label">Input Label Name </label>
				  <div class="col-10">
				    <input class="form-control myurl" type="text" name="desc" placeholder="First Name" value="<?php echo $url_results[0]->action_url_company;  ?>" required id="example-text-input">
				  </div>
			  </div>
              <div class="form-group row">
                  <label for="example-text-input" class="col-2 col-form-label">Input Text Name</label>
                  <div class="col-10">
                      <input class="form-control myurl" type="text" name="givenname" placeholder="C2IFirstName" value="<?php echo $url_results[0]->action_url_company;  ?>" required id="example-text-input">
                  </div>
              </div>
              <div class="form-group row">
                  <label for="example-text-input" class="col-2 col-form-label">Html - for dropdowns</label>
                  <div class="col-10">
                      <textarea class="form-control" name="htmlchar" id="example-text-input"></textarea>
                  </div>
              </div>
			  <div class="form-group row">
				   <input type="submit" value="save" name="submit" class="btn btn-success"/>
			  </div>
		  </div>
		 </form>
		  <div class="col-md-12">
		  	<table class="table table-bordered table-condensed">
		      <th>field</th>
		      <th>New name</th>
		      <th>Action</th>
		        <?php 
			       	global $wpdb;    
					$results = $wpdb->get_results("SELECT account_id,description,id,givenname FROM wp_maximizer WHERE account_id=".$_GET['listid']); 
					    if(!empty($results)) { 
		                   foreach($results as $r) {
		                   	 ?>
		                   	 <tr>
		                   	 	<form action="" method="POST">
		                   	 	<input class="form-control" name="id" type="hidden" value="<?php echo $r->id ?>" id="example-text-input">
		                   	 	<td><?php echo $r->description?></td>
						 		<td><input class="form-control" name="givenname" type="text" value="<?php echo $r->givenname ?>" id="example-text-input"></td>
                                    <td><input type="submit" value="save" name="save" class="btn btn-success"/> <button data-href="admin.php?page=analytify-dashboard&mypage=maximizer-add-names&listid=<?php echo $_GET['listid'] ?>&desc=B2B+Marketing&delete=<?php echo $r->id ?>" class="btn btn-danger delete-names-button">delete</button></td>
						 		</form>
						 	</tr>
		                   	 <?php
		                   }
		               }
					 ?>
			  </table>
		  </div>
		</div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModals" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Browse Database..</h4>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                        <th>Available Record list</th>
                        <th>Number of data input</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                        <?php  global $wpdb;
                        $res = $wpdb->get_results("SELECT * FROM wp_list_of_accounts WHERE list_of_account_id !=".$_GET['listid']);
                        if(!empty($res)) {
                            foreach($res as $r) {
                                $available_data = $wpdb->get_results("SELECT Count(*) AS fields_number FROM wp_maximizer WHERE account_id =".$r->list_of_account_id);
                                echo "<tr>";
                                echo "<td>".$r->description."</td>";
                                echo "<td>".$available_data[0]->fields_number."</td>";
                                echo "<td><a href='admin.php?page=analytify-dashboard&mypage=maximizer-add-names&listid=".$_GET['listid']."&desc=".$_GET['desc']."&import=".$r->list_of_account_id."' class='btn btn-info btn-sm'>import data</a></td>";
                                echo "</tr>";
                            }
                        }else{
                            echo"<tr><td rowspan='3'><a href='#' data-dismiss='modal'>No data Available try creating one</a></td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div id="add-names-del" class="modal fade" role="dialog">
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
                    <a href="" class="btn btn-danger" id="delete-names"><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> delete</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>