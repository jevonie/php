<div class='container'>
    <h1>Maximizer web to lead forms </h1>
    <div class='panel panel-default'>
      <div class='panel-heading'><h3><span class="glyphicon glyphicon-list" aria-hidden="true"></span> Accounts Lists</h3></div>
         <div id="form-acounts">
         <?php  if(isset($_GET['del'])){ global $wpdb;  $wpdb->delete( 'wp_list_of_accounts', array( 'list_of_account_id' => $_GET['del'] ) ); } ?>
         <table class="table table-striped table-bordered">
         <thead>
              <td>Account Name</td>
              <td>Description</td>
              <td>Sync With MailChimp</td>
              <td>Action</td>
         </thead>
         <tbody>
              <?php
              global $wpdb;    
              $results = $wpdb->get_results("SELECT account_name,list_of_account_id,description,sync_mailchimp,mailchimp_code_name FROM wp_list_of_accounts");
              if(!empty($results)) { 
                  //echo $results[0]->action;
                   foreach($results as $r) {
                      echo"<tr>";
                      echo"<td><a href='admin.php?page=analytify-dashboard&mypage=maximizer-generate-list&listid=$r->list_of_account_id&desc=$r->account_name' ><span class='glyphicon glyphicon-folder-open' aria-hidden='true'></span> ".$r->account_name."</a></td>";
                      echo"<td>$r->description</td>";
                      echo"<td>$r->sync_mailchimp</td>";
                      echo"<td><a href='admin.php?page=analytify-dashboard&mypage=maximizer-account&edit=$r->list_of_account_id&name=$r->account_name&dsc=$r->description&mc=$r->sync_mailchimp&mc_code=$r->mailchimp_code_name' class='label label-info'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span> Edit</a> <label data-href='admin.php?page=analytify-dashboard&del=$r->list_of_account_id' folder='$r->account_name' class='label label-danger delete'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> delete</label></td>";
                      echo"</tr>";
                    }
              } 
                    ?>
         </tbody>
         </table>
         </div>
         
  
    <div class='panel-footer'>
        <a href="admin.php?page=analytify-dashboard&mypage=maximizer-account" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Account</a>
    </div>
  </div>
</div>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body">
        <h3>Are you sure to delete this item?</h3>
         <h4><span class='glyphicon glyphicon-folder-open' aria-hidden='true'></span>    <label id="to_delete">Sample folder</label></h4>
      </div>
      <div class="modal-footer">
        <a href="" class="btn btn-danger" id="ok_delete"><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> delete</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
  