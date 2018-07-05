<?php
	require_once( 'db.inc.php' );
	require_once( 'facilities.inc.php' );

	$powattid=new FacPowAtt();
	$dept=new Department();

	$status="";

	// AJAX Requests
	// END - AJAX Requests

	$write=($person->WriteAccess)?true:false;

	if(isset($_REQUEST['facpowid'])){
		$powattid->PowAttID=(isset($_POST['facpowid'])?$_POST['facpowid']:$_GET['facpowid']);
		$powattid->GetFacPowAtt();
		// $write=($person->canWrite($powattid->AssignedTo))?true:$write;
	}

	// If you're deleting the cabinet, no need to pull in the rest of the information, so get it out of the way
	// Only a site administrator can create or delete a cabinet
	if(isset($_POST["delete"]) && $_POST["delete"]=="yes" && $person->SiteAdmin ) {
		$powattid->DeleteFacPowAtt();
		$status['code']=200;
		$status['msg']=redirect("facpowatt.php");
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
		$powattid->DataCenterID=$_POST['datacenterid'];
		$powattid->Location=trim($_POST['location']);
		$powattid->AssignedTo=$_POST['assignedto'];
		$powattid->TotAmp=$_POST['totamp'];
		$powattid->InputVolt=$_POST['inputvolt'];
		$powattid->Switchboard=$_POST['switchboard'];;
		$powattid->PowerPath=$_POST['powerpath'];
		$powattid->GenRedund=$_POST['generatorredundancy'];
		$powattid->UPSCapacity=$_POST['upscapacity'];
		$powattid->UPSRedundancy=$_POST['upsredundancy'];
		$powattid->UPSRuntime=$_POST['upsruntime'];
		$powattid->UPSOutput=$_POST['upsoutput'];
		
		if($powattid->Location!=""){
			if(($powattid->PowAttID >0)&&($_POST['action']=='Update')){
				$status=__("Updated");
				$powattid->UpdateFacPowAtt();
			}elseif($_POST['action']=='Create'){
				$powattid->CreateFacPowAtt();
			}

		}
	}elseif($powattid->PowAttID >0){
		$powattid->GetFacPowAtt();
	}else{
		$powattid->PowAttID=null;
		//Set DataCenterID to first DC in dcList for getting zoneList
		$dc=new DataCenter();
		$dcList=$dc->GetDCList();
		$keys=array_keys($dcList);
		$powattid->DataCenterID=(isset($_GET['dcid']))?intval($_GET['dcid']):$keys[0];
		$powattid->Location=null;
		$powattid->TotAmp=null;
		$powattid->InputVolt=null;
		$powattid->Switchboard=null;
		$powattid->PowerPath=null;
		$powattid->GenRedund=null;
		$powattid->UPSCapacity=null;
		$powattid->UPSRedundancy=null;
		$powattid->UPSRuntime=null;
		$powattid->UPSOutput=null;
	}

	$deptList=$dept->GetDepartmentList();
	$powattidList=$powattid->ListFacPowAtts();

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
		$('select[name=facpowid]').change(function(e){
			location.href='facpowatt.php?facpowid='+this.value;
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
<div class="page">
<?php
	include( 'sidebar.inc.php' );

echo '<div class="main" style="box-shadow: 10px 10px #333333;">
<h2>',$config->ParameterArray["OrgName"],'</h2>
<h3>',__("Data Center Facility Power Attributes"),'</h3>
<h3>',$status,'</h3>
<div class="center"><div>
<form id="rackform" method="POST">
<div class="table">
<div>
   <div>',__("Power Attribute"),'</div>
   <div><select name="facpowid" id="facpowid">
   <option value=0>',__("New Power Attribute"),'</option>';

	foreach($powattidList as $cabRow){
		$selected=($cabRow->PowAttID==$powattid->PowAttID)?' selected':'';
		print "<option value=\"$cabRow->PowAttID\"$selected>$cabRow->Location</option>\n";
	}

echo '   </select></div>
</div>
<div>
   <div>',__("Data Center"),'</div>
   <div>
		<select name="datacenterid" id="datacenterid">
';

	foreach(DataCenter::GetDCList() as $dc){
		$selected=($dc->DataCenterID==$powattid->DataCenterID)?' selected':'';
		print "\t\t\t<option value=\"$dc->DataCenterID\"$selected>$dc->Name</option>\n";
	}

echo '		</select>
	</div>
</div>
<div>
   <div>',__("Location"),'</div>
   <div><input type="text" class="validate[required,minSize[1],maxSize[20]]" name="location" size=10 maxlength=20 value="',$powattid->Location,'"></div>
</div>
<div>
  <div>',__("Assigned To"),':</div>
  <div><select name="assignedto" id="assignedto">
    <option value=0>',__("General Use"),'</option>';

	foreach($deptList as $deptRow){
		if($deptRow->DeptID==$powattid->AssignedTo){$selected=' selected';}else{$selected="";}
		print "<option value=\"$deptRow->DeptID\"$selected>$deptRow->Name</option>\n";
	}

echo '  </select>
  </div>
</div>
<div>
   <div>',__("Total Amps"),'</div>
   <div><input type="text" name="totamp" size=30 maxlength=80 value="',$powattid->TotAmp,'"></div>
</div>
<div>
   <div>',__("Input Voltage"),'</div>
   <div><input type="text" name="inputvolt" size=30 maxlength=80 value="',$powattid->InputVolt,'"></div>
</div>
<div>
   <div>',__("Switchboard kAIC"),'</div>
   <div><input type="text" name="switchboard" size=30 maxlength=80 value="',$powattid->Switchboard,'"></div>
</div>
<div>
   <div>',__("Power Path"),'</div>
   <div><input type="text" name="powerpath" size=30 maxlength=80 value="',$powattid->PowerPath,'"></div>
</div>
<div>
   <div>',__("Generator Redundancy"),'</div>
   <div><input type="text" name="generatorredundancy" size=30 maxlength=80 value="',$powattid->GenRedund,'"></div>
</div>
<div>
   <div>',__("UPS Capacity"),'</div>
   <div><input type="text" name="upscapacity" size=30 maxlength=80 value="',$powattid->UPSCapacity,'"></div>
</div>
<div>
   <div>',__("UPS Redundancy"),'</div>
   <div><input type="text" name="upsredundancy" size=30 maxlength=80 value="',$powattid->UPSRedundancy,'"></div>
</div>
<div>
   <div>',__("UPS Runtime"),'</div>
   <div><input type="text" name="upsruntime" size=30 maxlength=80 value="',$powattid->UPSRuntime,'"></div>
</div>
<div>
   <div>',__("UPS Output Voltage"),'</div>
   <div><input type="text" name="upsoutput" size=30 maxlength=80 value="',$powattid->UPSOutput,'"></div>
</div>
</div> <!-- END div.table -->';

	if($powattid->PowAttID >0){
		echo '   <button type="submit" name="action" value="Update">',__("Update"),'</button>
	<button type="button" name="action" value="Delete">',__("Delete"),'</button>';
	}else{
		echo '   <button type="submit" name="action" value="Create">',__("Create"),'</button>';
	}
?>
</div>		
</div> <!-- END div.table -->
</form>
</div></div>
<?php if($powattid->PowAttID >0){
		echo '<a href="facpowatt.php">[ ',__("Return to Navigator"),' ]</a>'; 
	}else{ 
		echo '<a href="index.php">[ ',__("Return to Main Menu"),' ]</a>';
	}

echo '
<!-- hiding modal dialogs here so they can be translated easily -->
<div class="hide">
	<div title="',__("Facility Power Attributes delete confirmation"),'" id="deletemodal">
		<div id="modaltext"><span style="float:left; margin:0 7px 20px 0;" class="ui-icon ui-icon-alert"></span>',__("Are you sure that you want to delete this Facility Power Attributes ?<br><br><b>THERE IS NO UNDO</b>"),'
		</div>
	</div>
</div>'; ?>
</div><!-- END div.main -->
</div><!-- END div.page -->

<script type="text/javascript">
$('button[value=Delete]').click(function(){
	var defaultbutton={
		"<?php echo __("Yes"); ?>": function(){
			$.post('', {facpowid: $('select[name=facpowid]').val(),delete: 'yes' }, function(data){
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
