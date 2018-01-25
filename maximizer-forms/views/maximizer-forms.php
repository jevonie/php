<style>
@media screen and (max-width: 1350px){
  .active, .inactive{
      width: 100%;
  } 
}

@media screen and (max-width: 1200px){
  form .active, form .inactive, form .col-md-12, #form-activate .col-md-6{
    padding: 0;
    overflow: hidden;
  }
}
@media screen and (max-width: 991px){
  .panel-success{
    overflow: hidden;
  }
}
@media screen and (max-width: 420px){
  .maximizer-box{
        padding: 20px 15px 30px 15px;
  }
}
 
</style>

<div class='container'>
    <h1>Maximizer web to lead forms </h1>
    <div class='panel panel-default'>
      <div class='panel-heading'><h3>Form View</h3></div>
      <div class="pull-right">
            <a href="admin.php?page=analytify-dashboard&mypage=maximizer-generate-list&listid=<?php echo $_GET['listid'];?>&formid=<?php echo $_GET['formid'];?>&desc=<?php echo $_GET['desc'];?>"  class="btn btn-default btn-sm">BACK</a>
      </div>
      <script type="text/javascript">
         jQuery(document).ready(function($){
             $( "#sortable1" ).sortable({
                  connectWith: ".connectedSortable"
              }).disableSelection();
          });
      </script>
      <!-- main -->
      	<div id="form-activate">
          <div class='panel-body'>
              <form method='POST' action='admin.php?page=analytify-dashboard&mypage=maximizer-forms&listid=<?php echo $_GET['listid'];?>&formid=<?php echo $_GET['formid'];?>&formname=<?php echo $_GET['formname'];?>&campaign=<?php echo $_GET['campaign'];?>&desc=<?php echo $_GET['desc'];?>'>
                <div class='col-md-12'>
                  	<div class='form-group col-md-12'>  
                    	   <span class='webformlabel'>Form Name</span>
                        <input type="text" class="form-control" id = "formname-maximizer" required value="<?php if(isset($_POST['update'])){echo $_GET['formname'];}else{echo $_GET['formname'];}?>" name="formName" />
                    </div>
                    <div class='form-group col-md-12'>
                        <span class='webformlabel'>Campaign Name</span>
                        <input type="text" class="form-control" id = "campaign-maximizer" required value="<?php if(isset($_POST['update'])){echo $_GET['campaign'];}else{echo $_GET['campaign'];}?>" name="campaign" />
                    </div>
                </div>
                <?php   
                  global $wpdb;    
                  $rad_results = $wpdb->get_results("SELECT category_id,generated_id FROM wp_generated WHERE generated_id =".$_GET['formid']); 
                 ?>
                <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                  <div class='form-group col-md-12' style="display: none">
                       <div class = "panel panel-success">
                        <div class = "panel-heading">
                            <h3 class = "panel-title">Form action type <span class="label label-danger"> required</span></h3>
                        </div>
                        <div class = "panel-body">
                          <input type="radio" name="type" value="1" checked <?php if($rad_results[0]->category_id=="1"){ echo "checked";} ?> required /> Company and contacts
                          <input type="radio" name="type" value="2" <?php if($rad_results[0]->category_id=="2"){ echo "checked";} ?> /> Individuals
                        </div>
                     </div>
                  </div>
                  <div class='form-group' style="display: none;">  
                     <input type="text" id="listid-maximizer" name="listid"  value="<?php echo $_GET['listid'];?>"/>
                  </div>
                  <div class='form-group' style="display: none;">  
                     <input type="text" id="formid-maximizer" name="formid"  value="<?php echo $_GET['formid'];?>"/>
                  </div>
                  <div class='form-group' style="display: none;">  
                     <input type="text" id="desc-maximizer" name="desc"  value="<?php echo $_GET['desc'];?>"/>
                  </div> 
                <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12 active'>
                <!-- start -->
                      <div class = "panel panel-success" id="first_list">
                        <div class = "panel-heading">
                          <h3 class = "panel-title"> Active</h3>
                        </div>
                        <div class = "panel-body">
                            <div class="form-controler-check">
                                <input type='checkbox' id="chk_for_active"/> select all
                            </div>
                          <ul id="sortable1" class="connectedSortable">
                          <?php 
                            global $wpdb;  
                            $res="";
                            $active="";
                            $desc="";
                            $type="submit";
                            $dp="";
                            if(isset($_GET['formid'])){
                                $query = "SELECT * FROM wp_activated  LEFT JOIN  wp_maximizer  ON wp_maximizer.id = wp_activated.fields_id WHERE wp_activated.generated_id=".$_GET['formid']." AND wp_maximizer.account_id=".$_GET['listid']." AND wp_activated.status=1 ORDER BY wp_activated.field_position ASC";
                                $res= $wpdb->get_results($query);
                                $type="update";
                            }else{
      //                           $res = $wpdb->get_results("SELECT givenname,description,name,id FROM wp_maximizer WHERE account_id=".$_GET['listid']);
                            }
                             if(!empty($res)) {
                                 $email_check=0;
                                 $aws='';
                                 foreach ($res as $r) {
                                     $starstar = '';
                                      $result_generated = $wpdb->get_results("SELECT account_id,fields_id,display_name,mandatory FROM wp_maximizer_settings_fields WHERE account_id = " . $_GET['listid'] . " AND fields_id='" . $r->id . "'");
                                      if (!empty($result_generated)) {
                                         if ($result_generated[0]->display_name != "") {
                                             $desc = $result_generated[0]->display_name;
                                         } else {
                                             $desc = $r->description;
                                         }
                                     } else {
                                         $desc = $r->description;
                                     }
                                     if ($result_generated[0]->mandatory == '1') {
                                         $required = "required";
                                         $manda_link = "&manda_type=0";
                                         $manda_style = 'style="color: lightcoral"';
                                         $starstar = '*';
                                     }else{
                                         $manda_link = "&manda_type=1";
                                     }
                                     if($r->name == 'Email Address'){
                                         $email_check = 1;
                                         $manda_style = 'style="color: lightcoral"';
                                         $manda_link = "&manda_type=1";
                                     }
                                     $manda_tory = "<a data-content='" . $r->html ."' title='toggle required fields' href='admin.php?page=analytify-dashboard&mypage=maximizer-forms&listid=" . $_GET['listid'] . "&formid=" . $_GET['formid'] . "&formname=" . $_GET['formname'] . "&desc=".$_GET['desc']."&mandatoryid=" . $r->id . "&campaign=".$_GET['campaign']."$manda_link' data-toggle='tooltip' class='badge input-madatory' style='float: right'><span $manda_style class='glyphicon glyphicon-asterisk'></span></a>";
                                     if(!empty($r->html)) {
                                         $dp = "<span class='caret'></span>";
                                     }
                                     $show_link = "<a  href='#' data-title='".$desc."' data-content='" . $r->html . "' title='edit' data-href='admin.php?page=analytify-dashboard&mypage=maximizer-forms&listid=" . $_GET['listid'] . "&formid=" . $_GET['formid'] . "&formname=" . $_GET['formname'] . "&desc=".$_GET['desc']."&formname=" . $_GET['formname'] . "&edit=" . $r->id . "&campaign=".$_GET['campaign']."' data-toggle='tooltip' class='badge badge-info dropdowns-edit' style='float: right'><span class='glyphicon glyphicon-pencil'></span></a>";
                                     if($r->name == 'WPCampaign'){
                                         $auto_chk='checked';
                                         $hide_list = 'style="text-align:center; display: none;"';
                                         echo "<li class='ui-state-default maxi maximizer-box' $hide_list><input type='checkbox' $auto_chk class='chks' name='$r->name'  value='$r->id'><span class='webformlabel ui-icon ui-icon-arrowthick-2-n-s max-desc-forms'> $desc <span class='mandatorymarker'>" . $starstar . "</span></span><span> $manda_tory </span> <span> $show_link </span></li>";
                                         continue;
                                     }
      //                                       remove $required
                                     echo "<li class='ui-state-default maxi maximizer-box'><input type='checkbox'  class='chk' name='$r->name'  value='$r->id'><span class='webformlabel ui-icon ui-icon-arrowthick-2-n-s max-desc-forms'>".$desc." $dp <span class='mandatorymarker'>" . $starstar . "</span></span><span> $manda_tory </span><span> $show_link </span></li>";
                                     $active = "";
                                     $show_link = "";
                                     $hide_list = "";
                                     $auto_chk = "";
                                     $manda_tory="";
                                     $manda_style="";
                                     $manda_link="";
                                     $dp="";
                                     }
                                     if($email_check == 0){
                                         $get_email = "SELECT * FROM wp_maximizer WHERE wp_maximizer.name='Email Address' AND wp_maximizer.account_id=".$_GET['listid'];
                                         $email_list = $wpdb->get_results($get_email);
                                         if(!empty($email_list)) {
                                             echo"<li class='ui-state-default maxi maximizer-box' id='droppable'><input type='checkbox' class='chk' name='".$email_list[0]->name."'  value='".$email_list[0]->id."'><span class='webformlabel ui-icon ui-icon-arrowthick-2-n-s max-desc-forms'> ".$email_list[0]->description."<span class='mandatorymarker'>*</span></span></li>";
                                         }
                                     }

                            }else{
                                 $get_email = "SELECT * FROM wp_maximizer WHERE wp_maximizer.name='Email Address' AND wp_maximizer.account_id=".$_GET['listid'];
                                 $email_list = $wpdb->get_results($get_email);
                                 if(!empty($email_list)) {
                                     echo"<li class='ui-state-default maxi maximizer-box' id='droppable'><input type='checkbox' class='chk' name='".$email_list[0]->name."'  value='".$email_list[0]->id."'><span class='webformlabel ui-icon ui-icon-arrowthick-2-n-s max-desc-forms'> ".$email_list[0]->description."<span class='mandatorymarker'>*</span></span></li>";
                                 }
                             }
                            ?>
                        </ul>
                      </div>
                       <div class='panel-footer'>
                        <div class='form-group' style="display: none">
                            <input type="radio" id="activate-form" name="activate" value="1" checked> Activate
                            <input type="radio" id="deactivate-form" name="activate" value="0"> Deactivate
                        </div>
                         <div class='form-group'>
      <!--                    <input class='submitbutton btn btn-primary' data-toggle="modal" data-target="#myModal_loading" name="--><?php //echo $type;?><!--"   type='submit' id='SubmitButton' value='submit'/>-->
                             <button type='button' id="form-save-active" class='submitbutton btn btn-info'> Save</button>
                             <button type='button' id="form-remove-active" class='submitbutton btn btn-warning'> Remove</button>
                        </div> 
                      </div>
                    </div>
              </div>

               <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12 inactive'>
               <!-- passive -->
               <?php  ?>
               <div class = "panel panel-success">
                  <div class = "panel-heading">
                    <h3 class = "panel-title">Inactive</h3>
                  </div>
                  <div class = "panel-body">
                      <div class="form-controler-check">
                          <input type='checkbox' id="chk_for_inactive"/> select all
                      </div>
                    <ul id="sortable2" class="connectedSortable">
                    <?php
                     $results2="";
                     $desc2="";
                     $active2="";
                     $starstar2="";
                    $dp2="";
                    if(isset($_GET['formid'])){
                           $query2 = "SELECT givenname,id,name,description,html FROM  wp_maximizer l WHERE  NOT EXISTS (SELECT generated_id,fields_id,status FROM  wp_activated i WHERE  l.id = i.fields_id AND  i.status != 0 AND i.generated_id = ".$_GET['formid'].") AND l.account_id=".$_GET['listid'];
                           $results2 = $wpdb->get_results($query2);
                    }else{
                           $results2 = $wpdb->get_results("SELECT givenname,description,name,id,html FROM wp_maximizer WHERE account_id=".$_GET['listid']);
                    } 
                    foreach($results2 as $val){                
                          $result2_generated = $wpdb->get_results("SELECT account_id,fields_id,display_name,mandatory FROM wp_maximizer_settings_fields WHERE account_id = ".$_GET['listid']." AND fields_id=".$val->id);
                           if(!empty($result2_generated)) {
                               if($result2_generated[0]->display_name!=""){
                                    $desc2 = $result2_generated[0]->display_name;
                                }else{
                                    $desc2 = $val->description;
                                }
                           }else{
                              $desc2 = $val->description;
                           }
                            if($result2_generated[0]->mandatory == '1'){
                                $required="required";
                                $starstar2 = '*';
                            }
                            if(!empty($val->html)) {
                                $dp2 = "<span class='caret'></span>";
                            }
                            $show_link = "<a  href='#' title='edit' data-title='".$desc2."' data-content='".$val->html."' data-href='admin.php?page=analytify-dashboard&mypage=maximizer-forms&listid=".$_GET['listid']."&formid=".$_GET['formid']."&desc=".$_GET['desc']."&formname=".$_GET['formname']."&edit=".$val->id."&campaign=".$_GET['campaign']."' class='badge dropdowns-edit' data-toggle='tooltip' style='float: right'><span class='glyphicon glyphicon-pencil'></span></a>";
                            if($val->name == 'WPCampaign'){
                                $auto_chk='checked';
                                $hide_list = 'style="text-align:center; display: none;"';
                                echo "<li class='ui-state-default maxi maximizer-box' $hide_list><input type='checkbox' $auto_chk class='chk' name='$val->name'  value='$val->id'><span class='webformlabel ui-icon ui-icon-arrowthick-2-n-s max-desc-forms'> $desc2 <span class='mandatorymarker'>" . $starstar . "</span></span><span>$show_link</span></li>";
                                continue;
                            }
                            if($val->name != "Email Address"){
                                echo("<li class='ui-state-highlight dexi maximizer-box' id='droppable2'><input type='checkbox' class='chk' $active2 name='$val->name' value='$val->id'/><span class='webformlabel ui-icon ui-icon-arrowthick-2-n-s'> ".$desc2." $dp2<span class='mandatorymarker'>".$starstar2."</span></span>$show_link</li>");
                            }
                            $dp2="";
                            $desc2="";
                            $active2="";
                            $show_link="";
                            $starstar2="";
                    }
                    ?>
                    </ul>
                  </div>
                   <div class='panel-footer'>
                       <div class='form-group'>
                           <input class='submitbutton btn btn-primary' data-toggle="modal" data-target="#myModal_loading" name="<?php echo $type;?>"   type='submit' id='SubmitButton' value='submit'/>
                           <!--                           <button type='button' id="form-remove-active" class='submitbutton btn btn-warning'> Remove</button>-->
                       </div>
                   </div>
                </div>
                <?php  ?>
              </div>
              </form>
            </div>
            <div class='col-lg-6 col-md-6 col-sm-12 col-xs-12'>
            
            <div class = "panel panel-success">
               <div class = "panel-heading">
                  <h3 class = "panel-title">Form Preview</h3>
               </div>
               <div class = "panel-body">
               <h1 style="text-align:center"><?php if(isset($_POST['update'])){echo $_POST['formName'];}else{echo $_GET['formname'];}?></h1>  
                <?php 
                    $desc3="";
                    $query = "SELECT * FROM wp_activated  LEFT JOIN  wp_maximizer  ON wp_maximizer.id = wp_activated.fields_id WHERE wp_activated.generated_id=".$_GET['formid']." AND wp_activated.status=1 ORDER BY wp_activated.field_position ASC";
                    $res= $wpdb->get_results($query);
                    foreach($res as $r) {
                      $result3_generated = $wpdb->get_results("SELECT account_id,fields_id,display_name,mandatory FROM wp_maximizer_settings_fields WHERE account_id = ".$_GET['listid']." AND fields_id='".$r->id."'"); 
                            if($r->name == 'WPCampaign'){continue;}
                            if(!empty($result3_generated)) {
                                if($result3_generated[0]->display_name!=""){
                                    $desc3 = $result3_generated[0]->display_name;
                                }else{
                                    $desc3 = $r->description;
                                }
                             }else{
                                $desc3 = $r->description;
                             }
                ?>
                    <div class='form-group'>
                      <span class='webformlabel'><?php echo $desc3; ?></span>
                        <?php if(!empty($r->html)){
                            $options = explode(",", $r->html);
                            foreach ($options as $value) {
                                $output2 .= "<option>".$value.'</option>';
                            }
                            echo "<select class='form-control'  $required name='$r->givenname' type='$type'>".htmlspecialchars_decode($output2)."</select><br>";
                            $output2="";
                        }else{
                          echo "<input class='form-control'  name='' type='text'><br>";
                        }
                        ?>
                    </div>
                <?php
                       $desc3="";
                     }
                ?>
                 <div class='form-group'>
                <?php if(isset($_GET['formid'])){ ?>
                            <div style="text-align:center"><input class="btn btn-info" type="submit" id="SubmitButton" value="Submit"/></div>
                 <?php }else{ ?>
                            <p align="center">The form generated will be displayed here..</p>
                 <?php } ?>
                 </div>
               </div>
            </div>
            </div>
            <div class="col-md-12">
        </div>
      </div>
    </div>
  </div>
<div id="edit_form_dropdowns" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class='glyphicon glyphicon-pencil'></span> Edit field</h4>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class="form-group">
                        <label for="example-text-input" class="col-form-label">Input Label Name</label>
                        <input class="form-control" id="form-label-name" type="text" name="givenname"  value=""/>
                    </div>
                    <div class="form-group">
                        <label for="example-text-input" class="col-form-label">Dropdowns</label>
                        <p>To add an item separate each with a comma</p>
                        <textarea class="form-control" id="data-dropdowns" style="height: 300px"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="" data-content="" class="btn btn-info" id="save-dropdowns"><span class='glyphicon glyphicon-check' aria-hidden='true'></span> save</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>