<?php
// my class
// by tibong.......
class Model {

    
    public function hello_world()  
    {  
        // here goes some hardcoded values to simulate the database  
        return "helloworld";  
    }
    private function save_forms($list_id, $form_name, $type, $campaign, $POST){
        global $wpdb;
        $insert_result = $wpdb->insert("wp_generated",
            array( 'list_of_accounts_id' => $list_id,
                'form_name' => $form_name,
                'category_id' => $type,
                'shortcode' => '',
                'widget' => '',
                'campaign' => $campaign
            ));
        $lastid = $wpdb->insert_id;
        $update_result = $wpdb->update( "wp_generated",
            array('shortcode' => "generated_id= $lastid",
                'widget' => $lastid
            ),
            array('generated_id' => $lastid)
        );
        foreach($POST as $key=>$val){
            if($key == 'activate' || $key == 'type' || $key == 'listid' || $key == 'formName' || $key == 'submit' || $key =='formid' || $key == 'desc'){
                //echo  "Skipped = > ". $key;
            }else{
                //get the position and value
                $new_val = explode("-",$val);
                //echo $key."=>".$new_val[0]."<br/>";
                $wpdb->insert("wp_activated",
                    array( 'fields_id' => $new_val[0],
                        'status' => '1',
                        'generated_id' => $lastid,
                        'field_position' => $new_val[1]
                    ));
            }
        }
        return $lastid;
    }
    private function update_forms($POST){
        global $wpdb;
        //update the form ame
        $wpdb->update("wp_generated",
            array('form_name' => $POST['formName'],
                  'category_id' => $POST['type'],
                  'campaign'=> $_POST['campaign']
            ),
            array('generated_id' => $POST['formid'])
        );
        $activation = $POST['activate'];
        foreach($POST as $key=>$val){
            $new_val = explode("-",$val);
            //filter uneccessary fields
            if($key == 'activate' || $key == 'type' || $key == 'listid' || $key == 'formName' || $key == 'update' || $key == 'desc'){
                //echo "Skipped = > ". $key;
            }else{
                //echo $key."=>".$new_val[0]." updated with activation".$_POST['formid']."<br/>";
                //update if found a fields_id in database
                $res= $wpdb->get_results("SELECT generated_id,fields_id FROM wp_activated WHERE generated_id=".$POST['formid']." AND fields_id='".$new_val[0]."'");
                if(empty($res)){
                    // insert if checked is not found in database
                    $unless_insert= $wpdb->insert("wp_activated",
                        array( 'fields_id' => $new_val[0],
                            'status' =>  $activation,
                            'generated_id' => $POST['formid'],
                            'field_position' => $new_val[1]
                        ));
                    //echo "inserted==>".$unless_insert;
                }else{
                    //dont let the email address to be disabled
                    $email_checker= $wpdb->get_results("SELECT * FROM wp_maximizer WHERE id='".$new_val[0]."'");
                    if ($email_checker[0]->name == 'Email Address'){
                        continue;
                    }
                    //if found update status
                    $up_results = $wpdb->update( "wp_activated",
                        array('status' => $activation,
                            'field_position' => $new_val[1]
                        ),
                        array('generated_id' => $POST['formid'],
                            'fields_id' => $new_val[0]
                        )
                    );
                    //echo "updated==>".$unless_insert;
                }

            }
        }
    }
    public function transactions($form_name)  
    {    
         if($form_name=="maximizer-forms"){
             if(isset($_POST['submit'])) {
                   //echo"------------------------LOGS-----------------------------<br/>";
                    $id = $_POST['listid'];
                    $type = $_POST['type'];
                    $campaign = $_POST['campaign'];
                    $form_name = $_POST['formName'];
                    $form_desc = '';
                    $last_id = $this->save_forms($id,$form_name,$type,$campaign,$_POST);
                 ?>
                 <script type="text/javascript">
                     window.location.href = "<?php echo admin_url();?>admin.php?page=analytify-dashboard&mypage=maximizer-forms&listid=<?php echo $id;?>&formid=<?php echo $last_id;?>&formname=<?php echo $form_name;?>&desc=<?php echo $form_desc;?>&campaign=<?php echo $campaign;?>";
                 </script>
                 <?php
             }elseif (isset($_POST['update'])) {
                 $this->update_forms($_POST);
             }elseif(isset($_GET['edit'])) {
                 global $wpdb;
                 $id = $_GET['edit'];
                 $data = $_GET['data'];
                 $campaign = $_GET['campaign'];
                 $list_id = $_GET['listid'];
                 $label = $_GET['label'];
                 $form_name = 'Desired Form name Here';
                 $type = 1;
                 if(isset($_GET['formid'])){
                     $up_results = $wpdb->update("wp_maximizer",
                         array('html' => htmlspecialchars($data)),
                         array('id' => $id)
                     );
                     $up_set_results = $wpdb->update("wp_maximizer_settings_fields",
                         array('display_name' => $label),
                         array('account_id' => $list_id,
                               'fields_id' => $id)
                     );
                     if($_GET['formid']=='') {
                         ?>
                         <script type="text/javascript">
                             window.stop();
                             window.location.href = "<?php echo admin_url();?>admin.php?page=analytify-dashboard&mypage=maximizer-forms&listid=<?php echo $list_id;?>&formname=<?php echo $form_name;?>&campaign=<?php echo $campaign;?>";
                         </script>
                         <?php
                     }
                 }
             }elseif(isset($_GET['mandatoryid']) && isset($_GET['manda_type'])) {
                 global $wpdb;
                 $id = $_GET['mandatoryid'];
                 $data = $_GET['data'];
                 $campaign = $_GET['campaign'];
                 $list_id = $_GET['listid'];
                 $form_name = 'Desired Form name Here';
                     if($_GET['manda_type'] == 1){$manda_stat = 1;}else{$manda_stat = 0;}
                     $up_results = $wpdb->update("wp_maximizer_settings_fields",
                         array('mandatory' => $manda_stat),
                         array('fields_id' => $id)
                     );
             }

        }elseif($form_name=="maximizer-settings") {
                if(isset($_POST['submit'])){
                global $wpdb;
                $acountid = $_POST['acountid'];
                $upresults = $wpdb->get_results("SELECT account_id FROM wp_maximizer_settings WHERE account_id=".$acountid); 
                if(!empty($upresults)) { 
                           $wpdb->update( "wp_maximizer_settings",
                           array('action_url_company' => $_POST['url'],
                                 'action_url_individuals' =>  $_POST['url2']
                                ),
                           array('account_id' => $acountid)
                           ); 
                }else{

                    $wpdb->insert("wp_maximizer_settings",
                           array( 'account_id' => $acountid,
                                  'action_url_company' => $_POST['url'],
                                  'action_url_individuals' =>  $_POST['url2']
                            ));
                }

                $mykey=0;
                foreach($_POST as $key=>$val){
                           // echo $key."=>".$val."<br/>";
                        if($key == 'acountid' || $key == 'url' || $key == 'submit' || substr($key, 0, 4) === 'chk_' ||  $key == 'url2'){
                            if(substr($key, 0, 4) === 'chk_'){
                                $mykey=1;
                            }
                            
                        }else{

                           $up_results = $wpdb->get_results("SELECT account_id,fields_id FROM wp_maximizer_settings_fields WHERE fields_id =".$key."  AND account_id=".$acountid);
                           if(!empty($up_results)) { 
                                $wpdb->update( "wp_maximizer_settings_fields",
                                array('display_name' => $val,
                                      'Mandatory' => $mykey
                                     ),
                                array('account_id' => $acountid,
                                      'fields_id' => $key
                                     )
                                ); 

                           }else{
                               $insertes = $wpdb->insert("wp_maximizer_settings_fields",
                               array( 'account_id' => $acountid,
                                      'fields_id' => $key,
                                      'display_name' => $val,
                                      'Mandatory'=> $mykey  
                                    ));
                              
                           }
                            $mykey=0;
                         }
                       
                } 
            }
        }elseif($form_name=="maximizer-account") {
                $current_url = admin_url("admin.php?page=analytify-dashboard");
               if(isset($_POST['save'])){
                    global $wpdb;
                    $account = $_POST['accountname'];
                    $description = $_POST['Description'];
                    $mailchimp = $_POST['Synchmailchimp'];
                    $mailchimp_code = $_POST['mailchimp_name_code'];
                    $result = $wpdb->insert("wp_list_of_accounts",
                               array( 'account_name' => $account,
                                      'description' => $description,
                                      'sync_mailchimp'=> $mailchimp,
                                      'mailchimp_code_name' => $mailchimp_code
                                    ));
                if($result){
                    ?>
                        <script type="text/javascript">
                          window.location = "<?php echo $current_url; ?>";
                        </script>
               <?php }}
               elseif (isset($_POST['update'])) {
                      global $wpdb;
                      $result = $wpdb->update( "wp_list_of_accounts",
                                       array('account_name' => $_POST['accountname'],
                                             'description' => $_POST['Description'],
                                             'sync_mailchimp'=>$_POST['Synchmailchimp'],
                                             'mailchimp_code_name' => $_POST['mailchimp_name_code']
                                            ),
                                       array('list_of_account_id' => $_POST['myid'])
                                     );
                      if($result){
                    ?>
                          <script type="text/javascript">
                              window.location = "<?php echo $current_url; ?>";
                          </script>
                    <?php }
               }
        }elseif($form_name=="maximizer-add-names") {
             if(isset($_POST['submit'])){
                global $wpdb;   
                $unless_insert= $wpdb->insert("wp_maximizer",
                      array( 'account_id' =>$_POST['account_id'],
                              'name' =>  $_POST['desc'],
                              'status' =>  1,
                              'description' =>$_POST['desc'],
                              'givenname' => $_POST['givenname'],
                              'html' => htmlspecialchars($_POST['htmlchar'])
                              ));
                }
               if(isset($_POST['save'])){
                 global $wpdb;
                 $unless_update = $wpdb->update( "wp_maximizer",
                             array('givenname' => $_POST['givenname']),
                             array('id' => $_POST['id'])
                             ); 
               }
             if(isset($_GET['import'])){
                 global $wpdb;
                 $import_id = $_GET['import'];
                 $dec = $_GET['desc'];
                 $current_id = $_GET['listid'];
                 $res = $wpdb->get_results("SELECT * FROM wp_maximizer WHERE account_id =".$import_id);
                 if(!empty($res)) {
                     foreach($res as $r) {
                         $exist_data = $wpdb->get_results("SELECT * FROM wp_maximizer WHERE account_id =".$current_id." AND givenname ='".$r->givenname."'");
                         if(empty($exist_data)) {
                             print_r($exist_data);
                             $unless_insert= $wpdb->insert("wp_maximizer",
                                 array( 'account_id' =>$current_id,
                                     'name' =>  $r->name,
                                     'status' =>  1,
                                     'description' =>$r->description,
                                     'givenname' => $r->givenname,
                                     'html' => $r->html,
                                 ));
                         }
                     }
                     $url_copy = $wpdb->get_results("SELECT * FROM wp_maximizer_settings WHERE account_id =".$import_id);
                     $exist_url = $wpdb->get_results("SELECT * FROM wp_maximizer_settings WHERE account_id =" . $current_id);
                     if (!empty($exist_url)) {
                         $wpdb->update("wp_maximizer_settings",
                                 array('action_url_company' => $url_copy[0]->action_url_company,
                                     'action_url_individuals' => $url_copy[0]->action_url_individuals
                                 ),
                                 array('account_id' => $current_id)
                             );
                     } else {
                         $wpdb->insert("wp_maximizer_settings",
                                 array('account_id' => $current_id,
                                     'action_url_company' => $url_copy[0]->action_url_company,
                                     'action_url_individuals' => $url_copy[0]->action_url_individuals
                                 ));
                     }
                     $exist_url = $wpdb->get_results("SELECT * FROM wp_list_of_accounts WHERE list_of_account_id =".$import_id);
                     if(!empty($exist_url)) {
                         $result = $wpdb->update("wp_list_of_accounts",
                             array('sync_mailchimp' => $exist_url[0]->sync_mailchimp,
                                 'mailchimp_code_name' => $exist_url[0]->mailchimp_code_name
                             ),
                             array('list_of_account_id' => $current_id)
                         );
                     }
                 }
             }
             if(isset($_GET['delete'])){
                 global $wpdb;
                 $wpdb->delete( 'wp_maximizer', array( 'id' => $_GET['delete'] ) );
             }
         }elseif($form_name=="maximizer-generate-list") {
             if(isset($_GET['delete'])){
                 global $wpdb;
                 $wpdb->update("wp_generated",
                     array('status' => 1),
                     array('generated_id' => $_GET['delete'])
                 );
             }
         }elseif($form_name=="maximizer-wpcf7"){
            if(isset($_POST['link'])){
                global $wpdb;
                if($_POST['wpcf7_id']){
                    $wpdb->update("wp_maximizer_wpcf7",
                        array('wpcf7_names' => $_POST['wpcf7_names'],
                            'maximizer_name' => $_POST['maximizer_name']
                        ),
                        array('wpcf7_id' => $_POST['wpcf7_id'])
                    );
                }else{
                    $wpdb->insert("wp_maximizer_wpcf7",
                        array('account_id' => $_POST['account_id'],
                            'wpcf7_names' => $_POST['wpcf7_names'],
                            'maximizer_name' => $_POST['maximizer_name']
                        ));
                }
            }
         }
     //function end
    }  
 //class End 
}  