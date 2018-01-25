<?php 
 /**
 * shortcodes for pages
 *
 *
 */
function form_function($atts){
   extract(shortcode_atts(array(
      'generated_id' => 'No Generated ID'
   ), $atts));
   $generated_id =  $atts['generated_id'];
   $custom_class =  $atts['class'];
   $output = '';
   $action_url = "";
   $date_active = 0;
   global $wpdb; 
   $result_generated = $wpdb->get_results("SELECT generated_id,list_of_accounts_id,category_id,campaign FROM wp_generated WHERE generated_id=".$generated_id);
   $result_action = $wpdb->get_results("SELECT * FROM wp_maximizer_settings WHERE wp_maximizer_settings.account_id=".$result_generated[0]->list_of_accounts_id);
   $mailchimp = $wpdb->get_results("SELECT * FROM wp_list_of_accounts WHERE sync_mailchimp = 'Yes' AND list_of_account_id=".$result_generated[0]->list_of_accounts_id);
   $results = $wpdb->get_results("SELECT * FROM wp_activated  LEFT JOIN  wp_maximizer  ON wp_maximizer.id = wp_activated.fields_id WHERE wp_activated.generated_id = ". $result_generated[0]->generated_id." AND wp_activated.status = 1  ORDER BY wp_activated.field_position ASC");
   $required="";
   if($result_generated[0]->category_id=="1"){
      $action_url = $result_action[0]->action_url_company;
   }elseif ($result_generated[0]->category_id=="2") {
      $action_url = $result_action[0]->action_url_individuals;
   }
   $output .="<div class='wrap form-area-maximizer $custom_class'><div class='message message-success'> Form Submitted Succesfully </div> <div class='message message-fail'> Form Submit Fail </div> ";
   $output .="<div id='primary' class='content-area'>";
   $output .="<main id='main' class='site-main' role='main'>";
   if(!empty($results)) { 
             $output .= "<form method='post' id='auto_forms' action=".$action_url.">";
             $has_company = 0;
		     foreach($results as $r) {
                 $result_desc = $wpdb->get_results("SELECT account_id,fields_id,display_name,mandatory FROM wp_maximizer_settings_fields WHERE account_id = ".$r->account_id." AND fields_id='".$r->id."'");
                 $type="text";
                 if($r->givenname == 'Email' || $r->givenname == 'CompanyName' || $r->givenname == 'LastName'){
                        $required="required";
                  }
                 if($r->givenname == 'Udf/$TYPEID(415)'){
                      $fieldChar = 'maxlength="150"';
                 }
                 if($result_desc[0]->mandatory == 1){
                     $required="required";
                 }
                 if($r->givenname == 'Udf/$TYPEID(418)'){
                        $type="date";
                        $date_active=1;
                 }
                 if($r->givenname == 'Email'){ 
                        $type="email";
                 }
                 if($r->name == 'WPCampaign'){
                     $css='style="text-align:center; display: none;" value="'.$result_generated[0]->campaign.'"';
                     $hide_div = 'style="text-align:center; display: none;"';
                 }
                 if($r->name == 'Company'){
                     $has_company = 1;
                 }
                 if($result_desc[0]->display_name!=""){
                     $desc = $result_desc[0]->display_name;
                 }else{
                     $desc = $r->description;
                 }
                 $output .= "<div class='form-group' $hide_div><span class='webformlabel'>".$desc."</span>";
                 if(empty($r->html)) {
                     $output .= "<input class='webforminput' $fieldChar $css $required name='$r->givenname' type='$type'><br>";
                 }else{
                     $output2="";
                     $options = explode(",", $r->html);
                     if($date_active==1){
                        foreach ($options as $value) {
                           $int = intval(preg_replace('/[^0-9]+/', '', $value), 10);
                           $output2 .= "<option value='May/".$int."/".date('Y')."'>".$value.'</option>';
                         }
                     }else{
                       foreach ($options as $value) {
                           $output2 .= "<option>".$value.'</option>';
                       }
                     }
                     $output .= "<select class='webforminput'  $required name='$r->givenname' type='$type'>".htmlspecialchars_decode($output2)."</select><br>";
                 }
                 $output .= "</div>";
                 $required="";
                 $css ="";
                 $hide_div="";
                 $fieldChar="";
                 $date_active=0;
		            
		     }
            if($has_company==0){
		         $company_get  = $wpdb->get_results("SELECT * FROM wp_maximizer WHERE wp_maximizer.name = 'Company'  AND wp_maximizer.account_id=".$result_generated[0]->list_of_accounts_id);
//		         $output .= "<div style='text-align:center; display: none;'><input class='webforminput' value='N/A' name=".$company_get[0]->givenname." type='text'></div>";
		     }
            if(!empty($mailchimp)){
              $mail_choice = $mailchimp[0]->mailchimp_code_name;
            }
            else{
              $mail_choice = 'U35I13';
            }
            $output .= "<div style='text-align:center; display: none;'><input class='webforminput' value='Yes' name='$mail_choice' type='text'></div>";
            $output .= '<div style="text-align:center" id="maximizer-saver-block"><input class="submitbutton" type="submit" id="SubmitButton" value="Submit"/></div></form>';
   } else {
       $output .= "<p></p>";
   }

   $output .="</main>";
   $output .="</div>";
   $output .="</div>";

   return $output;
}
function forms_generated(){
        add_shortcode('show-form', 'form_function');
        wp_enqueue_style('wp-widget-style', get_stylesheet_directory_uri().'/form-sample/src/css/widget.css', __FILE__);
//        wp_enqueue_script( 'strechy', get_stylesheet_directory_uri().'/form-sample/src/js/stretchy.min.js', array( 'jquery' ), '1.0.0', true );
        wp_enqueue_script( 'maximizer-themes', get_stylesheet_directory_uri().'/form-sample/src/js/main-function.js', array( 'jquery' ), '1.0.0', true );
        wp_enqueue_script( 'maximizer-validator', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js', array( 'jquery' ), '1.0.0', true );
        $translation_array = array( 'templateUrl' => get_stylesheet_directory_uri());
        //after wp_enqueue_script
        wp_localize_script( 'maximizer-themes', 'object_name', $translation_array );
}
add_action( 'init', 'forms_generated');