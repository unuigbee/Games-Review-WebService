<?php
require(APPPATH.'libraries/REST_Controller.php');

class Login extends REST_Controller {

    function allgames_get()    
}

//session_start();
//if(!empty($_POST)){			//checks if fields is not empty
//$Email=$_POST['Email'];
//$pw=$_POST['pw'];
//$username;
//$conn=new PDO("mysql:host=localhost;dbname=unuigbee",'unuigbee','Mw2akimbo.');		//connection to database
//$sql="SELECT * FROM customer WHERE email='$Email' and password='$pw'";			//select statement to retrieve information from database	
//$query = $conn->query($sql) or die("failed!");
//while($row= $query->fetch(PDO::FETCH_ASSOC)){
//	if($Email==$row['email'] && $pw==$row['password'] && $username=$row['firstname']){ //reads html form data
//		$_SESSION['Signin'] = $username;
//		header('Location: index.php');
//	}
//else{
//	unset($_SESSION['Signin']);
//	header('Location: Login.php');
//}
//}	
//$conn=null;			//ends connection
//}

