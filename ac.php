<?php
	require_once( 'db.inc.php' );
	require_once( 'facilities.inc.php' );

	$ac=new AC();
	$dept=new Department();

	$status="";

	// AJAX Requests
	// END - AJAX Requests

	$write=($person->WriteAccess)?true:false;

	if(isset($_REQUEST['acid'])){
		$ac->ACID=(isset($_POST['acid'])?$_POST['acid']:$_GET['acid']);
		$ac->GetAC();
		$write=($person->canWrite($ac->AssignedTo))?true:$write;
	}

	// If you're deleting the cabinet, no need to pull in the rest of the information, so get it out of the way
	// Only a site administrator can create or delete a cabinet
	if(isset($_POST["delete"]) && $_POST["delete"]=="yes" && $person->SiteAdmin ) {
		$ac->DeleteAC();
		$status['code']=200;
		$status['msg']=redirect("ac.php");
		header('Content-Type: application/json');
		echo json_encode($status);
		exit;
	}

	// this will allow a user to modify a rack but not create a new one
	// creation is still limited to global write priviledges
	if(!$write){
		// No soup for you.
		header('Location: '.redirect());
		exit;
	}

	if(isset($_POST['action'])){
		$ac->DataCenterID=$_POST['datacenterid'];
		$ac->Location=trim($_POST['location']);
		$ac->AssignedTo=$_POST['assignedto'];
		$ac->ZoneID=$_POST['zoneid'];
		$ac->CabRowID=$_POST['cabrowid'];
		$ac->Model=$_POST['model'];;
		$ac->ColCap=$_POST['colcap'];
		$ac->InstallationDate=$_POST['installationdate'];
		$ac->Notes=trim($_POST['notes']);
		$ac->Notes=($cab->Notes=="<br>")?"":$cab->Notes;

		// if ( $cab->U1Position == "Default" ) {
		// 	$dc = new DataCenter();
		// 	$dc->DataCenterID = $cab->DataCenterID;
		// 	$dc->GetDataCenter();
		// 	if ( $dc->U1Position == "Top" ) {
		// 		$cab->U1Position = "Top";
		// 	} elseif ( $dc->U1Position == "Default" ) {
		// 		$cab->U1Position = $config->ParameterArray["U1Position"];
		// 	} else {
		// 		$cab->U1Position = "Bottom";
		// 	}
		// }
		
		if($ac->Location!=""){
			if(($ac->ACID >0)&&($_POST['action']=='Update')){
				$status=__("Updated");
				$ac->UpdateAC();
			}elseif($_POST['action']=='Create'){
				$ac->CreateAC();
			}

		}
	}elseif($ac->ACID >0){
		$ac->GetAC();
	}else{
		$ac->ACID=null;
		//Set DataCenterID to first DC in dcList for getting zoneList
		$dc=new DataCenter();
		$dcList=$dc->GetDCList();
		$keys=array_keys($dcList);
		$ac->DataCenterID=(isset($_GET['dcid']))?intval($_GET['dcid']):$keys[0];
		$ac->Location=null;
		$ac->ZoneID=(isset($_GET['zoneid']))?intval($_GET['zoneid']):null;
		$ac->CabRowID=(isset($_GET['cabrowid']))?intval($_GET['cabrowid']):null;
		$ac->Model=null;
		$ac->ColCap=null;
		$ac->InstallationDate=date('Y-m-d');
	}

	$deptList=$dept->GetDepartmentList();
	$acList=$ac->ListACs();
	// $sensorList = SensorTemplate::getTemplates();

?>
<!doctype html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  
  <title>Facilities Cabinet Maintenance</title>
  <link rel="stylesheet" href="css/inventory.php" type="text/css">
  <link rel="stylesheet" href="css/jquery-ui.css" type="text/css">
  <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css">
  <link rel="stylesheet" href="css/jquery-te-1.4.0.css" type="text/css">
  <!--[if lt IE 9]>
  <link rel="stylesheet"  href="css/ie.css" type="text/css">
  <![endif]-->
  <script type="text/javascript" src="scripts/jquery.min.js"></script>
  <script type="text/javascript" src="scripts/jquery-ui.min.js"></script>
  <script type="text/javascript" src="scripts/jquery.validationEngine-en.js"></script>
  <script type="text/javascript" src="scripts/jquery.validationEngine.js"></script>
  <script type="text/javascript" src="scripts/jquery-te-1.4.0.min.js"></script>
  <script type="text/javascript" src="scripts/jquery.textext.js"></script>
  <script type="text/javascript" src="scripts/common.js?v<?php echo filemtime('scripts/common.js');?>"></script>

  <script type="text/javascript">
	$(document).ready(function() {
		$('select[name=acid]').change(function(e){
			location.href='ac.php?acid='+this.value;
		});

		$('#datacenterid').change(function(){
			//store the value of the zone id prior to changing the list, we might need it
			var ov=$('#zoneid').val();
			$('#zoneid').html('');
			// Add the option for no zone
			$.get('api/v1/zone?DataCenterID='+$('#datacenterid').val()).done(function(data){
				$('#zoneid').append($('<option>').val(0).text('None'));
				if(!data.error){
					for(var x in data.zone){
						var opt=$('<option>').val(data.zone[x].ZoneID).text(data.zone[x].Description);
						$('#zoneid').append(opt);
					}
				}
			}).then(function(e){
				// Attempt to set the original value of zoneid back after we've updated the options
				$('#zoneid').val(ov);
				// if the original value is no longer valid this will reset it to none
				if($.isEmptyObject($('#zoneid').val())){
					$('#zoneid').val(0);
				}
				$('#zoneid').change();
			});
		});
		$('#zoneid').change(function(){
			//store the value of the zone id prior to changing the list, we might need it
			var ov=$('#cabrowid').val();
			$('#cabrowid').html('');
			// Add the option for no row
			$('#cabrowid').append($('<option>').val(0).text('None'));
			var zonelimit=($('#zoneid').val()!=0)?'&ZoneID='+$('#zoneid').val():'';
			$.get('api/v1/cabrow?DataCenterID='+$('#datacenterid').val()+zonelimit).done(function(data){
				if(!data.error){
					$('#cabrowid').data('cabrow',data.cabrow);
					for(var x in data.cabrow){
						var opt=$('<option>').val(data.cabrow[x].CabRowID).text(data.cabrow[x].Name);
						$('#cabrowid').append(opt);
					}
				}
			}).then(function(e){
				// Attempt to set the original value of zoneid back after we've updated the options
				$('#cabrowid').val(ov);
				// if the original value is no longer valid this will reset it to none
				if($.isEmptyObject($('#cabrowid').val())){
					$('#cabrowid').val(0);
				}
			});
		});
		$('#cabrowid').change(function(e){
			if($('#cabrowid').val()!=0){
				$('#zoneid').val($('#cabrowid').data('cabrow')[$('#cabrowid').val()].ZoneID);
				$('#zoneid').trigger('change');
			}
		});

		// Init form
		$('#datacenterid').trigger('change');

		$("#cabinetid").combobox();
		$("#datacenterid").combobox();
		$("#assignedto").combobox();
		$("#zoneid").combobox();
		$("#cabrowid").combobox();

		$('span.custom-combobox').width($('span.custom-combobox').width()+2);

		$('#rackform').validationEngine({});
		$('input[name="installationdate"]').datepicker({dateFormat: "yy-mm-dd"});
	
	});
  </script>
</head>
<body>
<?php include( 'header.inc.php' ); ?>
<div class="backgroundpage">
<div class="page1">
<div class="makecenter">
<?php
	// include( 'sidebar.inc.php' );

echo '<div class="main">
<h2>',$config->ParameterArray["OrgName"],'</h2>
<h3>',__("Data Center PAC Inventory"),'</h3>
<h3>',$status,'</h3>
<div class="center"><div>
<form id="rackform" method="POST">
<div class="table">
<div>
   <div>',__("PAC"),'</div>
   <div><select name="acid" id="acid">
   <option value=0>',__("New PAC"),'</option>';

	foreach($acList as $cabRow){
		$selected=($cabRow->ACID==$ac->ACID)?' selected':'';
		print "<option value=\"$cabRow->ACID\"$selected>$cabRow->Location</option>\n";
	}

echo '   </select></div>
</div>
<div>
   <div>',__("Data Center"),'</div>
   <div>
		<select name="datacenterid" id="datacenterid">
';

	foreach(DataCenter::GetDCList() as $dc){
		$selected=($dc->DataCenterID==$ac->DataCenterID)?' selected':'';
		print "\t\t\t<option value=\"$dc->DataCenterID\"$selected>$dc->Name</option>\n";
	}

echo '		</select>
	</div>
</div>
<div>
   <div>',__("Location"),'</div>
   <div><input type="text" class="validate[required,minSize[1],maxSize[20]]" name="location" size=10 maxlength=20 value="',$ac->Location,'"></div>
</div>
<div>
  <div>',__("Assigned To"),':</div>
  <div><select name="assignedto" id="assignedto">
    <option value=0>',__("General Use"),'</option>';

	foreach($deptList as $deptRow){
		if($deptRow->DeptID==$ac->AssignedTo){$selected=' selected';}else{$selected="";}
		print "<option value=\"$deptRow->DeptID\"$selected>$deptRow->Name</option>\n";
	}

echo '  </select>
  </div>
</div>
<div>
   <div>',__("Zone"),'</div>
   <div>',$ac->GetZoneSelectList(),'</div>
</div>
<div>
   <div>',__("Cabinet Row"),'</div>
   <div>',$ac->GetCabRowSelectList(),'</div>
</div>
<div>
   <div>',__("Model"),'</div>
   <div><input type="text" name="model" size=30 maxlength=80 value="',$ac->Model,'"></div>
</div>
<div>
   <div>',__("Cooling Capacity"),'</div>
   <div><input type="text" name="colcap" size=30 maxlength=11 value="',$ac->ColCap,'"></div>
</div>
<div>
   <div>',__("Date of Installation"),'</div>
   <div><input type="text" name="installationdate" size=15 value="',date('Y-m-d', strtotime($ac->InstallationDate)),'"></div>
</div>
</div> <!-- END div.table -->
<div class="table">
	<div>
	  <div><label for="notes">',__("Notes"),'</label></div>
	  <div><textarea name="notes" id="notes" cols="40" rows="8">',$ac->Notes,'</textarea></div>
	</div>
<div class="caption">';

	if($ac->ACID >0){
		echo '   <button type="submit" name="action" value="Update">',__("Update"),'</button>
	<button type="button" name="action" value="Delete">',__("Delete"),'</button>
	<button type="button" value="AuditReport">',__("Audit Report"),'</button>
	<button type="button" value="MapCoordinates">',__("Map Coordinates"),'</button>';
	}else{
		echo '   <button type="submit" name="action" value="Create">',__("Create"),'</button>';
	}
?>
</div>		
</div> <!-- END div.table -->

</form>
</div></div>
<?php if($ac->ACID >0){
		echo '<a href="ac.php">[ ',__("Return to Navigator"),' ]</a>'; 
	}else{ 
		echo '';
	}

echo '
<!-- hiding modal dialogs here so they can be translated easily -->
<div class="hide">
	<div title="',__("PAC delete confirmation"),'" id="deletemodal">
		<div id="modaltext"><span style="float:left; margin:0 7px 20px 0;" class="ui-icon ui-icon-alert"></span>',__("Are you sure that you want to delete this PAC ?<br><br><b>THERE IS NO UNDO</b>"),'
		</div>
	</div>
</div>'; ?>
</div><!-- END div.main -->
</div><!-- END div.page -->
</div>
</div>
<script type="text/javascript">
$('button[value=AuditReport]').click(function(){
	window.location.assign('cabaudit.php?cabinetid='+$('select[name=cabinetid]').val());
});
$('button[value=MapCoordinates]').click(function(){
	window.location.assign('mapmaker.php?acid='+$('select[name=acid]').val());
});
$('button[value=Delete]').click(function(){
	var defaultbutton={
		"<?php echo __("Yes"); ?>": function(){
			$.post('', {acid: $('select[name=acid]').val(),delete: 'yes' }, function(data){
				if(data.code==200){
					window.location.assign(data.msg);
				}else{
					alert("Danger! DANGER!  Something didn't go as planned.");
				}
			});
		}
	}
	var cancelbutton={
		"<?php echo __("No"); ?>": function(){
			$(this).dialog("destroy");
		}
	}
	var modal=$('#deletemodal').dialog({
		dialogClass: 'no-close',
		modal: true,
		width: 'auto',
		buttons: $.extend({}, defaultbutton, cancelbutton)
	});
});

</script>
</body>
</html>
