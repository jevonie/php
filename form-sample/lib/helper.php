<?php
/**
 * Created by PhpStorm.
 * User: NexusNinja4
 * Date: 10/9/2017
 * Time: 11:30 PM
 */

//
// A very simple PHP example that sends a HTTP POST to a remote site
//
class Helper{
    public function save_data($url_full,$data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_full);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);
        return $server_output;
    }

    public function getUrlContent($url, $fields){
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

}