<?php
require_once('../../../../../wp-config.php');
require_once('helper.php');
global $wpdb;
$handler = $_GET['type'];
if($handler == 'get_url') {
    $save = new Helper();
    $arr_from_database = array();
    $arr_for_WPCampaign = array();
    $arr_from_form = array();
    $final_combine = array();
    $result_account = $wpdb->get_results("SELECT * FROM wp_list_of_accounts WHERE account_name='ExcelinBusinessCRM'");
    $result_generated = $wpdb->get_results("SELECT * FROM wp_maximizer_wpcf7 WHERE account_id=" . $result_account[0]->list_of_account_id);
    $result_settings = $wpdb->get_results("SELECT * FROM wp_maximizer_settings WHERE account_id=" . $result_account[0]->list_of_account_id);
    $campaign = $_GET['act'];
    $campaign = str_replace("/", "", $campaign);
    if ($_POST) {
        if ($result_generated) {
            foreach ($result_generated as $r) {
                foreach ($_POST as $key => $val) {
                    $options = explode(",", $r->wpcf7_names);
                    foreach ($options as $value) {
                        if (strcmp(strtolower($key), strtolower($value)) == 0) {
                            $final_combine[$r->maximizer_name] = $val;
                        } elseif ($value == 'campaign') {
                            $final_combine[$r->maximizer_name] = $campaign;
                        }
                    }
                }
            }
        }
    }
    $arr_for_WPCampaign = array($result_account[0]->mailchimp_code_name => 'Yes');
    $final_merge = array_merge($final_combine, $arr_for_WPCampaign);
    echo(executeSend($final_merge));
    // print_r($final_merge);
    // $final_result = $save->save_data($result_settings[0]->action_url_company, $final_merge);
    // $cleaned = trim(strip_tags($final_result));
    // if (stripos($cleaned, "Form submitted.") !== false || stripos($cleaned, "Object moved to") !== false) {
    //     echo "True";
    // } else {
    //     echo "False - Failed to submit the maximizer form to server!";
    //     echo $final_result;
    // }
}elseif ($handler == 'save'){
    // $save = new Helper();
    // $full_url = $_GET['act'].'&token='.$_GET['token'];
    // $final_result = $save->save_data($full_url, $_POST);
    // $cleaned = trim(strip_tags($final_result));
    // $recipient = "customerservices@excelinbusiness.com";
    // $subject = "Customer Registered";
    // if(isset($_POST['C18IPhone1'])){
    //     $phone = "Phone: ".$_POST['C18IPhone1']."<br/>";
    // }
    // if(isset($_POST['C30IPosition'])){
    //     $position = "Position: ".$_POST['C30IPosition']."<br/>";
    // }
    // if(isset($_POST['C31ISalutation'])){
    //     $message = "Message: ".$_POST['C31ISalutation']."<br/>";
    // }
    // if(isset($_POST['U41I412'])){
    //     $interested = "Interested: ".$_POST['U41I412']."<br/>";
    // }
    // $body = "name: ".$_POST['C15ILastName'].", ".$_POST['C13IFirstName']."<br/>
    //          Company: ".$_POST['C3ICompanyName']."<br/>
    //          Email: ".$_POST['U7I58850']."<br/>".$phone.$position.$interested.$message;
    // $headers .= "Content-Type: text/html\n";
    // $headers .= "X-WPCF7-Content-Type: text/html\n";
    // $attachments='';
    // if (stripos($cleaned, "Form submitted.") !== false || stripos($cleaned, "Object moved to") !== false) {
    //           wp_mail( $recipient, $subject, $body, $headers, $attachments );
    //     echo "True";
    // } else {
    //     echo "False - Failed to submit the maximizer form to server!";
    //     echo $final_result;
    // }
    $exec_func = executeSend($_POST);
    if($exec_func == 'True'){
        composeMail($POST);
    }
    echo($exec_func);
}
else {
        echo "False - no retrieved data in result_generated ";
}

//API

function executeSend($POST){
    global $wpdb;
    $common = 9999; //static info cannot be edited but be carefull LOT REFERENCES
    //calling api credentials
    $url_results = $wpdb->get_results("SELECT * FROM wp_maximizer WHERE account_id = ".$common);
    $host = $url_results[0]->name;
    $database = $url_results[0]->description;
    $username = $url_results[0]->givenname;
    $pwd = $url_results[0]->html;
    //data formater
    $add_alt = array('Description','AddressLine1','AddressLine2','City','StateProvince','ZipCode','Country');
    $phone_alt = array('Number','Extension');
    $addarr = array();
    $phonearr = array();
    //remove Multiple personal details to sanitize single DATA array
    // unset($POST['submit']);
    // unset($POST['Phones']);
    // unset($POST['Emails']);
    unset($POST['U39I410']);
    
    foreach ($POST as $key => $value) {
            //extract Address details from $POST
            foreach ($add_alt as $addkey) {
                        if($key == $addkey){
                            $addarr[$key] = $value;
                            unset($POST[$key]);
                        }
                    }
            //extract phone details from $POST
            foreach ($phone_alt as $phonekey) {
                         if($key == $phonekey){
                            $phonearr[$key] = $value;
                            unset($POST[$key]);
                        }
                    }        
    }
    $set_all = array();
    if(count($addarr) > 0){
        $set_all['Address'] = $addarr;
    }else{
        unset($POST['Address']);
    }
    if(count($phonearr) > 0){
        $set_all['Phone'] = $phonearr;
    }else{
        unset($POST['Phone']);
    }
    //merge final array data to insert
    $data = $set_all + $POST;
    // print_r($data);
    // $Address = array('Description' => (isset($POST['Description']) ? $POST['Description'] : ''),
    //                 'AddressLine1' => (isset($POST['C0IAddressLine1']) ? $POST['C0IAddressLine1'] : ''),
    //                 'City' => (isset($POST['C2ICity']) ? $POST['C2ICity'] : ''), 
    //                 'StateProvince' => (isset($POST['C32IStateProvince']) ? $POST['C32IStateProvince'] : ''),
    //                 'ZipCode' => (isset($POST['C34IZipCode']) ? $POST['C34IZipCode'] : ''),
    //                 'Country' => (isset($POST['C4ICountry']) ? $POST['C4ICountry'] : '')
    //                 );
    // $phone = array('Number' => (isset($POST['C18IPhone1']) ? $POST['C18IPhone1'] : ''),
    //                'Extension' => (isset($POST['C20IPhone1Extension']) ? $POST['C20IPhone1Extension'] : '')
    //                 );
    // $data = array(
    //                 'MrMs'=> (isset($POST['C17IMrMs']) ? $POST['C17IMrMs'] : ''),
    //                 'FirstName'=> (isset($POST['C13IFirstName']) ? $POST['C13IFirstName'] : ''),
    //                 'MiddleName'=> (isset($POST['C16IMiddleName']) ? $POST['C16IMiddleName'] : ''),
    //                 'LastName'=> (isset($POST['C15ILastName']) ? $POST['C15ILastName'] : ''),
    //                 'Position'=> (isset($POST['C30IPosition']) ? $POST['C30IPosition'] : ''),
    //                 'Address'=> $Address,
    //                 'Department'=> (isset($POST['C5IDepartment']) ? $POST['C5IDepartment'] : ''),
    //                 'Phone'=> $phone,
    //                 'Salutation'=> (isset($POST['C31ISalutation']) ? $POST['C31ISalutation'] : ''),
    //                 'WebSite'=> (isset($POST['U33I58851']) ? $POST['U33I58851'] : ''),
    //                 'Email'=> (isset($POST['U7I58850']) ? $POST['U7I58850'] : ''),
    //                 'CompanyName'=> (isset($POST['C3ICompanyName']) ? $POST['C3ICompanyName'] : ''),
    //                 'Udf/$TYPEID(411)' => (isset($POST['U40I411']) ? $POST['U40I411'] : ''), //WPCampaign
    //                 'Udf/$TYPEID(364)' => (isset($POST['U36I364']) ? $POST['U36I364'] : ''), //Type Of Insurance Organisation
    //                 'Udf/$TYPEID(153)' => (isset($POST['U35I153']) ? $POST['U35I153'] : ''), //Nature of Business
    //                 'Udf/$TYPEID(367)' => (isset($POST['U38I367']) ? $POST['U38I367'] : ''), //Shipping Business Sector
    //                 'Udf/$TYPEID(365)' => (isset($POST['U37I365']) ? $POST['U37I365'] : ''), //Insurance Business Sector
    //                 'Udf/$TYPEID(412)' => (isset($POST['U41I412']) ? $POST['U41I412'] : ''), //EiB Product Interest
    //                 'Udf/$TYPEID(414)' => (isset($POST['U43I414']) ? $POST['U43I414'] : ''), //Delegates
    //                 'Udf/$TYPEID(415)' => (isset($POST['U42I415']) ? $POST['U42I415'] : ''), //Further Information
    //             );

    // authentication and get the token
        $arr = authAPI($host,$database,$username,$pwd);
        $code = $arr[0]->Code;
        $token = $arr[0]->Data->Token;
    
        if($code == 0){
            // execute insert via API
            $inserted = insertData($host, $token, $data);
            $KeyContact = $inserted[0]->AbEntry->Data->Key;
            if($KeyContact){
                return "True";
            }else{
                return (isset($inserted['err_msg']) ? $inserted['err_msg'] : ($inserted[0]->Msg[0]));
            }
        }else{
            return ($arr[0]->Msg[0]);
        }
}
function getUrlContent($url, $fields){
    $ch = curl_init();
//set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//execute post
    $result = json_decode(curl_exec($ch));
//close connection
    curl_close($ch);
    return $result;
}

function authAPI($host,$database,$username,$pwd){
    //API authentitication block
    $url = $host."/MaximizerWebData/Data.svc/json/Authenticate";
    $fields = array(
        'Database'=> $database,
        "UID"=> $username,
        "Password" => $pwd
    );
    $arr  = array(getUrlContent($url,$fields));
    return $arr;
}

function searchSingleParam($token,$searchUrl,$data,$toSearch){
    //API search block
    $search_q2 = array(
        'Token' => $token,
        'AbEntry' => array(
            'Criteria' => array(
                'SearchQuery' => array(
                    '$EQ' => array(
                        $toSearch => $data[$toSearch],
                    ),
                ),
            ),
            'Scope' => array(
                'Fields' => array(
                    'Key' => 1,
                    'CompanyName' => 1,
                    'Email' => 1,
                ),
            ),
        ),
     );
    $search  = array(getUrlContent($searchUrl,$search_q2));
    return $search;
}
function insertData($host, $token,$data){
    $searchUrl = $host."/MaximizerWebData/Data.svc/json/AbEntryRead";
    $insertUrl = $host."/MaximizerWebData/Data.svc/json/AbEntryCreate";
    $emailSearch = searchSingleParam($token,$searchUrl,$data,'Email');
    // search for a existing Email Address
    if(!$emailSearch[0]->AbEntry->Data){
        $companySearch = searchSingleParam($token,$searchUrl,$data,'CompanyName');
        //search for an existing Company Name if found Update Company contact list else create the company
        if(!$companySearch[0]->AbEntry->Data){
            $insert = array(
                'Token' => $token,
                'AbEntry' => array(
                    'Data' => array(
                            'Key'=> null,
                            'Type'=> 'Company',
                            'CompanyName'=> $data['CompanyName'],
                        ),
                ),
            );
            $ret1  = array(getUrlContent($insertUrl,$insert));
            $KeyContact = $ret1[0]->AbEntry->Data->Key;
        }else{
            $KeyContact = $companySearch[0]->AbEntry->Data[0]->Key;
        }
        // $KeyContact is the parentKey of the Inserted company or existing company to be reference by the new contact
        if($KeyContact){
            $ParentKey = array('Key'=> null,
                               'ParentKey' => $KeyContact,
                               'Type'=> 'Contact',
                               'Udf/$TYPEID(410)' => 2,
                               ) + $data;
            unset($ParentKey['CompanyName']);
            #$finalData = $ParentKey + $data;
            $insert2 = array(
                'Token' => $token,
                'AbEntry' => array(
                    'Data' =>$ParentKey,
                ),
            );
            $ret  = array(getUrlContent($insertUrl,$insert2));
        }else{
            $ret = array('err_msg' => 'Failed To Insert', );
        }
    }else{
        $ret = array('err_msg' => 'Email Already Exist', );
    }
    return $ret;
}
function composeMail($POST){
    //customerservices@excelinbusiness.com
    $recipient = "customerservices@excelinbusiness.com";
    $subject = "Customer Registered";
    if(isset($POST['Number'])){
        $phone = "Phone: ".$POST['Number']."<br/>";
    }
    if(isset($POST['Position'])){
        $position = "Position: ".$POST['Position']."<br/>";
    }
    if(isset($POST['Salutation'])){
        $message = "Message: ".$POST['Salutation']."<br/>";
    }
    if(isset($POST['Udf/$TYPEID(412)'])){
        $interested = "Interested: ".$POST['Udf/$TYPEID(412)']."<br/>";
    }
    $body = "From: EiB (wordpress@excelinbusiness.com)<br/>
             Subject: Customer Registered<br/>
             <br/>
             name: ".$POST['LastName'].", ".$POST['FirstName']."<br/>
             Company: ".$POST['CompanyName']."<br/>
             Email: ".$POST['Email']."<br/>".$phone.$position.$interested.$message."<br/>
             <br/><br/>
             -- <br/>
             This e-mail was sent from a contact form on Excel In Business (https://excelinbusiness.com)"
             ;
    $headers .= "Content-Type: text/html\n";
    $headers .= "X-WPCF7-Content-Type: text/html\n";
    $attachments='';

    wp_mail( $recipient, $subject, $body, $headers, $attachments );
}

