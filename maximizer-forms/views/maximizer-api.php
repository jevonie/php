<div class='container'>
    <h1>Maximizer web to lead forms </h1>
    <div class='panel panel-default'>
      <div class='panel-heading'><span class='glyphicon glyphicon-cog' aria-hidden='true'></span> Maximizer API</div>
       <div class="panel-body">
        <div class="btn-group pull-right">
             <a href="admin.php?page=analytify-dashboard&mypage=maximizer-settings&listid=<?php echo $_GET['listid'];?>&desc=<?php echo $_GET['desc']?>" class="btn btn-default btn-sm">BACK</a>
      	</div>
       	<form action="" method="POST">
       		<div class="col-md-12">
       		<?php
       		 global $wpdb;
       		 $common = 9999; //static info can be edited
       		 $url_results = $wpdb->get_results("SELECT * FROM wp_maximizer WHERE account_id = ".$common);
       		 ?>
       		<div class="form-group row">
			  <label for="example-text-input" class="col-2 col-form-label">HOST</label>
			  <div class="col-10">
			    <input class="form-control" type="text" name="host"  value="<?php echo(isset($_POST['host']) ? $_POST['host'] : $url_results[0]->name);  ?>" required/>
			  </div>
			</div>
			<div class="form-group row">
			  <label for="example-text-input" class="col-2 col-form-label">Database</label>
			  <div class="col-10">
			    <input class="form-control" type="text" name="database"  value="<?php echo(isset($_POST['database']) ? $_POST['database'] : $url_results[0]->description);  ?>" required/>
			  </div>
			</div>
			<div class="form-group row">
			  <label for="example-text-input" class="col-2 col-form-label">Username</label>
			  <div class="col-10">
			    <input class="form-control" type="text" name="username"  value="<?php echo(isset($_POST['username']) ? $_POST['username'] : $url_results[0]->givenname);  ?>" required/>
			  </div>
			</div>
			<div class="form-group row">
			  <label for="example-text-input" class="col-2 col-form-label">Password</label>
			  <div class="col-10">
			    <input class="form-control" type="text" name="pwd"  value="<?php echo(isset($_POST['pwd']) ? $_POST['pwd'] : $url_results[0]->html);  ?>" required />
			  </div>
			</div>
       	</div>
       	<input type="hidden" name="acountid" value="<?php echo $_GET['listid'];?>"/>
       	<div class="col-md-12">
			 <div class="form-group row">
				  <input type="submit" value="Connect" name="api_connect" class="btn btn-success"/>
<!--				  <button type="reset" class="btn btn-danger">Discard Settings</button>-->
			 </div>
		 </div>
	   </form>
	</div>
	<div class="panel-footer">
    <?php
	   	 if(isset($_POST['api_connect'])){
	   	 	$host = $_POST['host'];
	   	 	$database = $_POST['database'];
	   	 	$username = $_POST['username'];
	   	 	$pwd = $_POST['pwd'];
	   	 	$arr  = authAPI($host,$database,$username,$pwd);
	   	 	$code = $arr[0]->Code;
	   	 	$token = $arr[0]->Data->Token;
	   	 	if($token){
	   	 		?>
	   	 		<div class="alert alert-success" role="alert">
				  <strong>Well done!</strong> You successfully connected to maximizer api.
				</div>
	   	 		<?php
	   	 		global $wpdb; 
	   	 		      $res= $wpdb->get_results("SELECT account_id FROM wp_maximizer WHERE account_id=".$common);
                if(empty($res)){
                    // insert if checked is not found in database
                    $unless_insert= $wpdb->insert("wp_maximizer",
                        array( 'account_id' => $common,
                            'name' =>  $host,
                            'status' => 0,
                            'description' => $database,
                            'givenname' => $username,
                            'html' => $pwd
                        ));
                    //echo "inserted==>".$unless_insert;
                }else{
                    //if found update host
                    $up_results = $wpdb->update( "wp_maximizer",
                        array(
                        	'name' =>  $host,
                            'status' => 0,
                            'description' => $database,
                            'givenname' => $username,
                            'html' => $pwd
                        ),
                        array(
                            'account_id' => $common
                             )
                    );
                    //echo "updated==>".$unless_insert;
                }
	   	 	}else{
	   	 		?>
	   	 		<div class="alert alert-danger" role="alert">
				  <strong>Oh snap!</strong> <? echo (($arr[0]->Msg[0]) ? $arr[0]->Msg[0] : 'Cant connect to host'); ?>
				</div>
	   	 		<?php
	   	 	}
	   	 	$fieldsList = fielList($host, $token);
			$maxiFileds = $fieldsList[0]->AbEntry->Data->properties;
			foreach ($maxiFileds as $key => $value) {
				echo '<pre>';
				echo 'Name: <strong>'.$key.'</strong><br>';
				print_r($value);
				echo '</pre>';
			}
	   	 }else{
	   	 if($url_results[0]->name){
	   	 	?>
	   	 		<div class="alert alert-success" role="alert">
				  <strong>Connected!</strong> You currently connected to Maximizer API.
				</div>
			<?php
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
		function authAPI($host,$db,$user,$pass){
		    $url = $host."/MaximizerWebData/Data.svc/json/Authenticate";
		    $fields = array(
		        'Database'=> $db,
		        "UID"=> $user,
		        "Password" => $pass
		    );
		    $arr  = array(getUrlContent($url,$fields));
		    return $arr;
		}
		function fielList($host, $token){
			$infoUrl = $host."/MaximizerWebData/Data.svc/json/AbEntryGetFieldInfo";
		    $arrayinfo = array(
		          "Token"=>$token,
		          "AbEntry"=> array(
		            "Options"=> array(
		              "Complex" => true
		            )
		          )
		        );
		    $inf  = array(getUrlContent($infoUrl,$arrayinfo));
		    return  $inf;
		}
	   ?>
    </div>
<div class='panel-footer'></div>
</div>