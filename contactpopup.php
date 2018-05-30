<?php
	require_once( 'db.inc.php' );
	require_once( 'facilities.inc.php' );
	
	$header=__("Department Contact Listing");

	$dept=new Department();
	
	if(!isset($_REQUEST['deptid'])){
		// No soup for you.
		header('Location: '.redirect());
		exit;
	}

	$deptID=intval($_REQUEST['deptid']);
	$contactList=$person->GetPeopleByDepartment($deptID);
	$dept->DeptID=$deptID;
	$dept->GetDeptByID();

	if(isset($config->ParameterArray['UserLookupURL']) && isValidURL($config->ParameterArray['UserLookupURL'])){
		$el=1; //enable displaying lookup options
	}else{
		$el=0; //default to not showing lookup options
	}

	$subheader=$dept->Name;
?>
<!doctype html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  
  <title>VIO DCIM Device Maintenance</title>
  <link rel="stylesheet" href="css/inventory.php" type="text/css">
  <!--[if lt IE 9]>
  <link rel="stylesheet"  href="css/ie.css" type="text/css" />
  <![endif]-->
  <style type="text/css">
	div.main { padding: 30px 0; border: 0px; width: 100%; }
  </style>
</head>
<body>
<?php include( 'header.inc.php' ); ?>
<div class="main" style="box-shadow: 10px 10px #333333;">
<div class="table border centermargin">
	<div>
		<div>Last Name</div>
		<div>First Name</div>
		<div>UserID</div>
<?php if($el){ echo '		<div>Lookup</div>';} ?>
	</div>
<?php
	foreach($contactList as $contactRow){
		print "<div>
		<div>$contactRow->LastName</div>
		<div>$contactRow->FirstName</div>
		<div>$contactRow->UserID</div>";
		if($el){
			print "		<div><input type=\"button\" value=\"Contact Lookup\" onclick=\"window.open( '{$config->ParameterArray["UserLookupURL"]}$contactRow->UserID', 'Lookup' );\"></div>";
		}
		print "	</div>\n";
	}
?>	
</div>
</body>
</html>
