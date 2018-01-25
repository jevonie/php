<div class='container'>
    <h1>Maximizer web to lead forms </h1>
    <div class='panel panel-default'>
      <div class='panel-heading'><h3><span class='glyphicon glyphicon-folder-open' aria-hidden='true'></span> <?php echo $_GET['desc']; $desc =  $_GET['desc'];?></h3></div>
      <div class="btn-group pull-right">
            <a href="admin.php?page=analytify-dashboard&mypage=maximizer-forms&listid=<?php echo $_GET['listid']?>&desc=<?php echo $desc; ?>" class="btn btn-default btn-sm"><span class='glyphicon glyphicon-list-alt' aria-hidden='true'></span> CREATE FORM</a>
            <a href="admin.php?page=analytify-dashboard&mypage=maximizer-settings&listid=<?php echo $_GET['listid']?>&desc=<?php echo $desc; ?>" class="btn btn-default btn-sm"><span class='glyphicon glyphicon-cog' aria-hidden='true'></span> SETTINGS</a>
            <a href="admin.php?page=analytify-dashboard" class="btn btn-default btn-sm"><span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span> BACK</a>
          
      </div>
      <!-- main -->
      <div id="form-generate">
       <?php  if(isset($_GET['listid'])) { $GLOBALS['listid'] = $_GET['listid']; ?>
       <table class="table table-striped table-bordered">
       <thead>
	       <tr>
	              <th>Form Name</th>
	              <th>Category</th>
                  <th>Campaign Name</th>
	              <th>Short Code</th>
	              <th>Widget</th>
	              <th>PHP code</th>
	        </tr>
        </thead>
        <tbody>
		        <?php  
		       global $wpdb;    
		              $results = $wpdb->get_results("SELECT list_of_accounts_id,category_id,form_name,widget,generated_id,campaign FROM wp_generated WHERE status = 0 AND list_of_accounts_id =".$_GET['listid']);
		              if(!empty($results)) { 
		                  //echo $results[0]->action;

		                   foreach($results as $r) {
		                   	$category ="";
		                   	if($r->category_id == "1"){$category="Company and Contacts";}elseif($r->category_id == "2"){ $category="Individuals";}else{$category="No Data";}
		                   	echo "<tr>";
		                    echo"<td><a href='admin.php?page=analytify-dashboard&mypage=maximizer-forms&listid=".$_GET['listid']."&formid=$r->generated_id&formname=$r->form_name&desc=$desc&campaign=$r->campaign'><span class='glyphicon glyphicon-list-alt' aria-hidden='true'></span> $r->form_name </a></td>";
		                    echo"<td>".$category."</td>";
		                    echo"<td>".$r->campaign."</td>";
		                    echo"<td>".'[show-form generated_id="'.$r->widget.'"]'."	</td>";
		                    echo"<td>$r->widget</td>"; 
		                    echo"<td>".'echo'.' do_shortcode'.'([show-form generated_id="'.$r->widget.'"]);'."</td>";
		                    echo"<td><label data-file='".$r->form_name."' data-href='admin.php?page=analytify-dashboard&mypage=maximizer-generate-list&listid=".$_GET['listid']."&desc=".$desc."&delete=".$r->generated_id."' class='label label-danger das-delete'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> delete</label></td>";
		                    echo "</tr>";

		                    }
		              } else {
		                   echo "<tr><td colspan='6'> <a href='admin.php?page=analytify-dashboard&mypage=maximizer-forms&listid=". $_GET['listid'] ."&desc=$desc'><span class='glyphicon glyphicon-plus' aria-hidden='true'></span> Create form now</a></td></tr>";
		              } 
		           ?>  
          </tbody>
          </table>
        <?php } ?>
       </div>
    </div>
 </div>
<div id="myModal_file" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body">
                <h3>Are you sure to delete this item?</h3>
                <h4><span class='glyphicon glyphicon-list-alt' aria-hidden='true'></span>    <label id="file_to_delete">Sample folder</label></h4>
            </div>
            <div class="modal-footer">
                <a href="" class="btn btn-danger" id="ok_file_delete"><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> delete</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>