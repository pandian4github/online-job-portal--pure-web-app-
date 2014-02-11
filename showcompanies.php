<?php
	$uid=$_REQUEST['uid'];
	
	//Stem array
	$stem=array("is","was","this","there","the","an","a","he","she","it","might","could","should","ought","to","behind","in","on","why","how","what","who","when","where");
	$slen=count($stem);

	//Open file
	$file=fopen('resumes/'.$uid.'.txt',"r");
	$req=fread($file, 1000);
	$temp=$req;
	
//	echo $req;
//	echo "<br>";

	//Extract words
	$matches=array();
	$req=str_replace(array('.',',',';'), ' ', $req);
//	echo $req."<br/>";
	$req=preg_replace('!\s+!', ' ', $req);
//	echo $req."<br/>";
	$req=explode(" ",$req);
	foreach ($req as $word) 
	{
		$word=strtolower($word);
		$flag=0;
		for($i=0;$i<$slen;$i++)
			if(strcmp($word, $stem[$i])==0)
			{
				$flag=1;
				break;
			}
		if($flag==0)
			array_push($matches, $word);
	}
//	echo "<br/>";
//	echo $matches[0][0];
//	echo $matches[1][0];
//	echo "<br/>";
//	print_r($matches);

	include('connect.php');

	$query="SELECT uid from `resume`.`requirements`;";
	$res=mysqli_query($dbc,$query);

	$temp=array();
	$c=array();
	$n=array();
	while(($r=mysqli_fetch_array($res))!=NULL)
	{
		$f=$r['uid'];
		$file=fopen("requirements/".$f.".txt","r");
		$var=fread($file, 1000);
	//	echo $var;
	//	echo "dsfads";

		$count=0;
		$matches2=array();
		$var=str_replace(array('.',',',';'), ' ', $var);

 		$var=preg_replace('!\s+!', ' ', $var);

		$max=0;

		$var=explode(" ",$var);
		foreach ($var as $word) 
		{
			$word=strtolower($word);
			$flag=0;
			for($i=0;$i<$slen;$i++)
				if(strcmp($word, $stem[$i])==0)
				{
					$flag=1;
					break;
				}
			if($flag==0)
			{
	//			echo $word."<br>";
				for($j=0;$j<count($matches);$j++)
					if(strcmp($matches[$j],$word)==0)
						$count++;
			}
		}
		if($count>$max)
			$max=$count;
		array_push($c, $count);
		array_push($n, $f);
		//echo $count;
	//	echo "<br>";
	//	echo $f;

	}
	for($i=0;$i<count($c);$i++)
		for($j=$i+1;$j<count($c);$j++)
			if($c[$i]<$c[$j])
			{
				$t=$c[$i];
				$c[$i]=$c[$j];
				$c[$j]=$t;
				$t=$n[$i];
				$n[$i]=$n[$j];
				$n[$j]=$t;
			}
//	print_r($n);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Resume classification</title>
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-fileupload.css">
<link rel="stylesheet" type="text/css" href="employee.css">
<link rel="stylesheet" type="text/css" href="common.css">

<script type="text/javascript" src="bootstrap/js/bootstrap-fileupload.js"></script>
<script type="text/javascript">
function changecontent(i)
{
	var d=document.getElementById('content');
	if(i==1)
	{
		d.innerHTML="<br/><p><b>Are you looking for a job ?</b></p><p>If you are looking for a job, you can upload your resume and you can mention all your achievements, field of interest etc. You will be given with an unique id. There are two ways in which you will be benefitted. You can use this unique id to search for companies which suit your talents and your field of interest. The companies will be displayed in the order of highest to lowest matching percentage. The other one is, if the company is looking for hiring employees, and if it finds your resume a matching one, the company will contact you for job offers. </p><p><ul><li>If you need to upload your resume and search for companies, click on <b>Upload resume</b> tab.</li><li>If you have already uploaded your resume and if you want to look at all matching companies, click on <b>Look for companies</b> tab. </li></ul></p><br/>";
	}
	else
		if(i==2)
		{
			d.innerHTML="<form class='form-horizontal' method='get' id='entryform' action='upload.php'><div class='control-group'><label class='control-label' for='name'>Name : </label><div class='controls'><input type='text' name='name'/></div></div><div class='control-group'><label class='control-label' for='address'>Address : </label><div class='controls'><textarea name='address'></textarea></div></div><div class='control-group'><label class='control-label' for='phone'>Contact number : </label><div class='controls'><input type='text' name='phone'/></div></div><div class='control-group'><label class='control-label' for='mailid'>Email id : </label><div class='controls'><input type='text' name='mailid'/></div></div><div class='control-group'><label class='control-label' for='lang'>Languages known : </label><div class='controls'><textarea name='lang'></textarea></div></div><div class='control-group'><label class='control-label' for='skills'>Technical skills : </label><div class='controls'><textarea name='skills'></textarea></div></div><div class='control-group'><label class='control-label' for='achieve'>Achievements : </label><div class='controls'><textarea name='achieve'></textarea></div></div><div class='control-group'><label class='control-label' for='interest'>Field of interest : </label><div class='controls'><textarea name='interest'></textarea></div></div><div class='control-group'><label class='control-label' for='other'>Other talents : </label><div class='controls'><textarea name='other'></textarea></div></div><div class='control-group'><label class='control-label' for='hobbies'>Hobbies : </label><div class='controls'><textarea name='hobbies'></textarea></div></div><div class='control-group'><div class='controls'><button type='submit' class='btn btn-success' name='submit'>Submit</button></div></div></form><br/><center>(OR)<br/><br/><div class=\"fileupload fileupload-new\" data-provides=\"fileupload\"><div class=\"input-append\"><div class=\"uneditable-input span3\"><i class=\"icon-file fileupload-exists\"></i> <span class=\"fileupload-preview\"></span></div><span class=\"btn btn-file\"><span class=\"fileupload-new\">Select file</span><span class=\"fileupload-exists\">Change</span><input type=\"file\" /></span><a href=\"#\" class=\"btn fileupload-exists\" data-dismiss=\"fileupload\">Remove</a></div></div><button type='submit' class='btn btn-danger' name='submit'>Upload</button></center>";
		}
		else
		{
			d.innerHTML="<form class='form-horizontal' method='get' id='entryform' action='showcompanies.php'><div class='control-group'><label class='control-label' for='uid'>Enter your unique id : </label><div class='controls'><input type='text' name='uid'/></div></div><div class='control-group'><div class='controls'><button type='submit' class='btn btn-success' name='submit'>Show eligible candidates</button></div></div></form>";
		}
}
function showerror(i)
{
	var error=["nameerror","rollnoerror","phoneerror","mailiderror","batcherror"];
	var x=document.getElementById(error[i]);
	x.className="visible";
}
</script>
</head>
<body>

	<?php include("sitelayout.php"); ?>
	<div class="span8" id="main-content">
		<br/>
		<div id='navbar'>
			<ul class="nav nav-pills">
				<li><a href="index.php">Home</a></li>
				<li><a href="desc.php">Description</a></li>
				<li><a href="employer.php">Employer</a></li>
				<li class="active"><a href="#">Employee</a></li>
				<li><a href="contact.php">Contact</a></li>
			</ul>
		</div>
		<center><span id="entryhead">Employee</span></center>
		<br/>
		<center>
			<div class="btn-group">
				<button class="btn btn-primary" onclick="changecontent(1)">Home</button>
				<button class="btn btn-primary"onclick="changecontent(2)">Upload resume</button>
				<button class="btn btn-primary"onclick="changecontent(3)">Look for companies</button>
			</div>
		</center>

		<div id='content'>
			<br/>
			<br/>
			<?php
				for($i=0;$i<count($n);$i++)
				{
					echo "<center><a class='btn btn-danger' href='requirements/".$n[$i].".txt' target='_blank'>Company : ".$i."</a></center>";
					echo "<br/>";
				}
			?>
			<br/>
		</div>

	</div>
	<?php include('sitelayout2.php'); include('footer.php'); ?>

</body>
</html>
