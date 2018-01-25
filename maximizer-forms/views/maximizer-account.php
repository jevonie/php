<div class='container'>
    <h1>Maximizer web to lead forms </h1>
    <div class='panel panel-default'>
      <div class='panel-heading'><h3><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Account</h3></div>
       <div class="panel-body">
       <?php if(isset($_GET['edit'])){ $id = $_GET['edit']; $name = $_GET['name']; $desc = $_GET['dsc']; $type="update"; }else{ $type="save"; } ?>
       	<form action="" method="POST">
	      	<div class="form-group">
			  <label for="example-text-input" class="col-2 col-form-label">Account Name</label>
			  <div class="col-10">
			    <input class="form-control" type="text" required value="<?php echo $name ?>" name="accountname" id="example-text-input">
			  </div>
			</div>
			<div class="form-group">
			  <label for="example-text-input" class="col-2 col-form-label">Description</label>
			  <div class="col-10">
			    <input class="form-control" type="text"  value="<?php echo $desc ?>" name="Description"  id="example-text-input">
			  </div>
			</div>
            <div class="form-group">
                <label for="example-text-input" class="col-2 col-form-label">Sync To MailChimp</label>
                <div class="col-10">
                    <?php if(isset($_GET['mc'])){if($_GET['mc']=='Yes'){$yes="selected";}elseif ($_GET['mc']=='No'){$no="selected";}} ?>
                  <select class='webformselect form-control mailchimp' id="mailchimp" name='Synchmailchimp'>
                    <option />
                    <option <?php echo $yes ?>>Yes</option>
                    <option <?php echo $no ?>>No</option>
                  </select>
                </div>
            </div>
            <div class="form-group" style="display: none" id="mailchimp-text">
                <label for="example-text-input" class="col-2 col-form-label">sync connector code</label>
                <div class="col-10">
                    <input class="form-control" type="text" value="<?php echo $_GET['mc_code'] ?>" id="mailchimp_name_code" name="mailchimp_name_code"/>
                    <span class='label label-info' aria-hidden='true'>You can leave this field blank ang comeback later or import will populate this field automatically</span>
                </div>
            </div>
			 <input class="form-control" type="hidden"  value="<?php echo $id ?>" name="myid">
	   </div>
       <div class='panel-footer'>
         <input type="submit" name="<?php echo $type ?>" class="btn btn-success" value="Save"/>
         <a href="admin.php?page=analytify-dashboard" class="btn btn-warning">Cancel</a>
   	   </div>
       </form>
  </div>
</div>
  