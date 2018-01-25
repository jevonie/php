<div class='container'>
    <h1>Maximizer web to lead forms </h1>
    <div class='panel panel-default'>
      <div class='panel-heading'><span class='glyphicon glyphicon-cog' aria-hidden='true'></span> Settings</div>
       <div class="panel-body">
        <div class="btn-group pull-right">
        	<!-- <button type="button" value="yes" class="btn btn-warning btn-sm change_field">change urls</button> -->
        	<a href="admin.php?page=analytify-dashboard&mypage=maximizer-add-names&listid=<?php echo $_GET['listid']?>&desc=<?php echo $_GET['desc']?>" class="btn btn-info btn-sm add_name">Add / Edit / Import</a>
            <a href="admin.php?page=analytify-dashboard&mypage=maximizer-wpcf7&listid=<?php echo $_GET['listid']?>&desc=<?php echo $_GET['desc']?>" class="btn btn-info btn-sm add_name">wpcf7</a>
            <a href="admin.php?page=analytify-dashboard&mypage=maximizer-api&listid=<?php echo $_GET['listid']?>&desc=<?php echo $_GET['desc']?>" class="btn btn-info btn-sm add_name">API</a>
            <a href="admin.php?page=analytify-dashboard&mypage=maximizer-generate-list&listid=<?php echo $_GET['listid']?>&desc=<?php echo $_GET['desc']?>"  class="btn btn-default btn-sm">BACK</a>

      	</div>
       	<form action="" method="POST">
       		<div class="col-md-12" style="display: none;">
       		<?php
       		 global $wpdb;
       		 $url_results = $wpdb->get_results("SELECT account_id,action_url_company,action_url_individuals FROM wp_maximizer_settings WHERE account_id = ".$_GET['listid']);
       		 ?>
	       		<div class="form-group row">
				  <label for="example-text-input" class="col-2 col-form-label">Company and Contacts Action URL</label>
				  <div class="col-10">
				    <input class="form-control myurl" type="text" name="url" readonly value="<?php echo $url_results[0]->action_url_company;  ?>" required id="example-text-input">
				  </div>
				</div>
				<div class="form-group row">
				  <label for="example-text-input" class="col-2 col-form-label">Individuals Action URL</label>
				  <div class="col-10">
				    <input class="form-control myurl" type="text" name="url2" readonly value="<?php echo $url_results[0]->action_url_individuals;  ?>" required id="example-text-input">
				  </div>
				</div>
       	    </div>
       	<input type="hidden" name="acountid" value="<?php echo $_GET['listid'];?>"/>
       	<div class="col-md-12">
			 <div class="form-group row">
				  <input type="submit" value="Save Settings" name="submit" class="btn btn-success"/>
<!--				  <button type="reset" class="btn btn-danger">Discard Settings</button>-->
			 </div>
		 </div>
		 <table class="table table-bordered table-condensed">
			 <thead>
			 	<th>Mandatory</th>
			 	<th>Display Name on form</th>
			 	<th>Default Field Name</th>
			 	<th>Api Maximizer Name</th>
			 	<th>Type</th>
			 </thead>
			 <tbody>
			 	<?php 
		       		global $wpdb;    
				    $results = $wpdb->get_results("SELECT account_id,givenname,name,id,group_type FROM wp_maximizer WHERE account_id=".$_GET['listid']); 

				    if(!empty($results)) { 
                       foreach($results as $r) {
                       $name_results = $wpdb->get_results("SELECT account_id,display_name,fields_id,mandatory FROM wp_maximizer_settings_fields WHERE fields_id = ".$r->id." AND account_id=".$_GET['listid']);
                       $mandatory="";
                       if ($name_results[0]->mandatory == "1" ) {
                       	$mandatory = "checked";
                       }
                       	 ?>
                       	 <tr>
					 		<td>
					 		   <div class="checkbox">
		  					      <label><input type="checkbox" <?php echo 	$mandatory; ?> name="chk_<?php echo $r->id ?>" value="1"> </label>
					  		   </div> 
					  		</td>
					 		<td><input class="form-control" name="<?php echo $r->id ?>" type="text" value="<?php echo $name_results[0]->display_name;?>" id="example-text-input"></td>
					 		<td><?php echo $r->name ?></td>
					 		<td><?php echo $r->givenname ?></td>
					 		<td><?php echo $r->group_type ?></td>
					 	</tr>
                       	 <?php
                       }
                   }else{
                   		echo "<tr><td colspan='4'><a href='admin.php?page=analytify-dashboard&mypage=maximizer-add-names&listid=".$_GET['listid']."&desc=".$_GET['desc']."'><span class='glyphicon glyphicon-plus' aria-hidden='true'></span> add fields now</a></td></tr>";
                   }
				 ?>
			 	
			 </tbody>
			 <tfoot>
			 </tfoot>
		 </table>
	   </form>
	</div>
</div>
<div class='panel-footer'></div>
</div>