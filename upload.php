<?php
	session_start();
	function random_generate() 
	{
	   	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	   	$randomString = '';
	   	for ($i = 0; $i < 10; $i++) 
	   	{
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	   	return $randomString;
	}
	//	echo 'Entered enterreq.php';

	$x=random_generate();
	$name=$_REQUEST['name'];
	$address=$_REQUEST['address'];
	$phone=$_REQUEST['phone'];
	$mailid=$_REQUEST['mailid'];
	$lang=$_REQUEST['lang'];
	$skills=$_REQUEST['skills'];
	$achieve=$_REQUEST['achieve'];
	$interest=$_REQUEST['interest'];
	$other=$_REQUEST['other'];
	$hobbies=$_REQUEST['hobbies'];

	include('connect.php');

	//Inserting into database
	$query="INSERT INTO `resume`.`resume`(`uid`,`name`,`address`,`phone`,`mailid`,`lang`,`skills`,`achieve`,`interest`,`other`,`hobbies`) values('$x','$name','$address','$phone','$mailid','$lang','$skills','$achieve','$interest','$other','$hobbies');";
	echo $query;
	$res=mysqli_query($dbc,$query) or die('Error in query');

	//Creating file
	$file=fopen('resumes/'.$x.'.txt',"w+");
	fwrite($file,'Name : '.$name."\r\n");
	fwrite($file,'Address : '.$address."\r\n");
	fwrite($file,'Contact : '.$phone."\r\n");
	fwrite($file,'Email id : '.$mailid."\r\n");
	fwrite($file,'Languages known : '.$lang."\r\n");
	fwrite($file,'Technical skills : '.$skills."\r\n");
	fwrite($file,'Achievements : '.$achieve."\r\n");
	fwrite($file,'Field of interst : '.$interest."\r\n");
	fwrite($file,'Other talents : '.$other."\r\n");
	fwrite($file,'Hobbies : '.$hobbies."\r\n");

	fclose($file);

	$_SESSION['upload']=1;
	$_SESSION['uid']=$x;
	header('location:employee.php');
?>