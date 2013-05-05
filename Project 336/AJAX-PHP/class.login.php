<?php
require($_SERVER['DOCUMENT_ROOT'].'/AJAX-PHP/phpass/PasswordHash.php');
require($_SERVER['DOCUMENT_ROOT'].'/AJAX-PHP/credentials.php');
//For security reasons, don't display any errors or warnings. Comment out in DEV.
error_reporting(0);
//start session
session_start();
class logmein {
	
	//db setup
	var $hostname_logon = DBHOST;      //Database server LOCATION
    var $database_logon = DBNAME;       //Database NAME
    var $username_logon = DBUSERNAME;       //Database USERNAME
    var $password_logon = DBPASSWORD;       //Database PASSWORD
        
    //table fields
    var $user_table = 'User';          //Users table name
    var $user_column = 'NetID';     //USERNAME column (value MUST be valid email)
    var $pass_column = 'Password';      //PASSWORD column
 
    //connect to database
    function dbconnect(){
        $connections = mysql_connect($this->DBHOST, $this->username_logon, $this->password_logon) or die ('Unabale to connect to the database');
        mysql_select_db($this->database_logon) or die ('Unable to select database!');
        return;
    }
 
    //login function
    function login($table, $username, $password){
        //conect to DB
        $this->dbconnect();
        //make sure table name is set
        if($this->user_table == ""){
            $this->user_table = $table;
        }        
        
        //execute login via qry function that prevents MySQL injections
        $result = $this->qry("SELECT * FROM ".$this->user_table." WHERE ".$this->user_column."='?';" , $username);
        $row=mysql_fetch_assoc($result);
        if($row != "Error"){
            if($row[$this->pass_column] != ""){
				
				//create password hasher object with the same parameters that were used to hash the initial pwd.
				$pwdHasher = new PasswordHash(8, FALSE);
				
				//validate password against hashed one
				$validPassword = $pwdHasher->CheckPassword( $password , $row[$this->pass_column] );
				
				if($validPassword){
					//register sessions
					//you can add additional sessions here if needed
					$_SESSION['loggedin'] = $password;
					$_SESSION['netid'] = $username;
					$_SESSION['name'] = $row['Name'];
					$_SESSION['lastname'] = $row['Last_Name'];
					$_SESSION['type'] = $row['User_Type'];
					
					return true;
				}
				else{
					session_destroy();
                	return false;
				}
            }else{
                session_destroy();
                return false;
            }
        }else{
            return false;
        }
 
    }
 
    //prevent injection
    function qry($query) {
      $this->dbconnect();
      $args  = func_get_args();
      $query = array_shift($args);
      $query = str_replace("?", "%s", $query);
      $args  = array_map('mysql_real_escape_string', $args);
      array_unshift($args,$query);
      $query = call_user_func_array('sprintf',$args);
      $result = mysql_query($query) or die(mysql_error());
          if($result){
            return $result;
          }else{
             $error = "Error";
             return $result;
          }
    }
 
    //logout function
    function logout(){
        session_destroy();
        return;
    }
 
    //check if loggedin
    function logincheck($logincode, $user_table, $user_column){
        //conect to DB
        $this->dbconnect();
                
        if($this->user_column == ""){
            $this->user_column = $user_column;
        }
        if($this->user_table == ""){
            $this->user_table = $user_table;
        }
        //exectue query
        $result = $this->qry("SELECT Password FROM ".$this->user_table." WHERE ".$this->user_column." = '?';" , $user_column);
		$row=mysql_fetch_assoc($result);
		
		if($row!="Error"){
			//create password hasher object with the same parameters that were used to hash the initial pwd.
			$pwdHasher = new PasswordHash(8, FALSE);
					
			//validate password against hadhed one
			$validPassword = $pwdHasher->CheckPassword( $logincode , $row['Password'] );
			//return true if logged in and false if not		
			if($validPassword){        
					return true;
				}else{
					return false;
				}
		}
        
    }
 
    //reset password
    function passwordreset($username, $user_table, $pass_column, $user_column){
        //conect to DB
        $this->dbconnect();
        //generate new password
        $newpassword = $this->createPassword();
 
        //make sure password column and table are set
        if($this->pass_column == ""){
            $this->pass_column = $pass_column;
        }
        if($this->user_column == ""){
            $this->user_column = $user_column;
        }
        if($this->user_table == ""){
            $this->user_table = $user_table;
        }
        //check if encryption is used
        if($this->encrypt == true){
            $newpassword_db = md5($newpassword);
        }else{
            $newpassword_db = $newpassword;
        }
 
        //update database with new password
        $qry = "UPDATE ".$this->user_table." SET ".$this->pass_column."='".$newpassword_db."' WHERE ".$this->user_column."='".stripslashes($username)."'";
        $result = mysql_query($qry) or die(mysql_error());
 
        $to = stripslashes($username);
        //some injection protection
        $illegals=array("%0A","%0D","%0a","%0d","bcc:","Content-Type","BCC:","Bcc:","Cc:","CC:","TO:","To:","cc:","to:");
        $to = str_replace($illegals, "", $to);
        $getemail = explode("@",$to);
 
        //send only if there is one email
        if(sizeof($getemail) > 2){
            return false;
        }else{
            //send email
            $from = $_SERVER['SERVER_NAME'];
            $subject = "Password Reset: ".$_SERVER['SERVER_NAME'];
            $msg = "
 
Your new password is: ".$newpassword."
 
";
 
            //now we need to set mail headers
            $headers = "MIME-Version: 1.0 rn" ;
            $headers .= "Content-Type: text/html; \r\n" ;
            $headers .= "From: $from  \r\n" ;
 
            //now we are ready to send mail
            $sent = mail($to, $subject, $msg, $headers);
            if($sent){
                return true;
            }else{
                return false;
            }
        }
    }
 
    //create random password with 8 alphanumerical characters
    function createPassword() {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double)microtime()*1000000);
        $i = 0;
        $pass = '' ;
        while ($i <= 7) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;
    }
 
    //login form
    function loginform($formname, $formclass, $formaction){
        //conect to DB
        $this->dbconnect();
        echo'
<form name="'.$formname.'" method="post" id="'.$formname.'" class="'.$formclass.'" enctype="application/x-www-form-urlencoded" action="'.$formaction.'">
<div><label for="username">Username</label>
<input name="username" id="username" type="text"></div>
<div><label for="password">Password</label>
<input name="password" id="password" type="password"></div>
<input name="action" id="action" value="login" type="hidden">
<div>
<input name="submit" id="submit" value="Login" type="submit"></div>
</form>
 
';
    }
    //reset password form
    function resetform($formname, $formclass, $formaction){
        //conect to DB
        $this->dbconnect();
        echo'
<form name="'.$formname.'" method="post" id="'.$formname.'" class="'.$formclass.'" enctype="application/x-www-form-urlencoded" action="'.$formaction.'">
<div><label for="username">Username</label>
<input name="username" id="username" type="text"></div>
<input name="action" id="action" value="resetlogin" type="hidden">
<div>
<input name="submit" id="submit" value="Reset Password" type="submit"></div>
</form>
 
';
    }
    //function to install logon table
    function cratetable($tablename){
        //conect to DB
        $this->dbconnect();
        $qry = "CREATE TABLE IF NOT EXISTS ".$tablename." (
              userid int(11) NOT NULL auto_increment,
              useremail varchar(50) NOT NULL default '',
              password varchar(50) NOT NULL default '',
              userlevel int(11) NOT NULL default '0',
              PRIMARY KEY  (userid)
            )";
        $result = mysql_query($qry) or die(mysql_error());
        return;
    }
}
?>




