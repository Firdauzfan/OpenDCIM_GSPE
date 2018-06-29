<?php
/*
	VIO Intelligence DCIM

	This is the main class library for the VIO Intelligence DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/

class AC {
	/* Cabinet:		The workhorse logical container for DCIM. 
	*/

	var $ACID;
	var $DataCenterID;
	var $Location;
	var $LocationSortable;
	var $AssignedTo;
	var $ZoneID;
	var $CabRowID;      //JMGA: Row of this cabinet
	var $Model;
	var $ColCap;
	var $InstallationDate;
	var $MapX1;
	var $MapY1;
	var $MapX2;
	var $MapY2;
	var $Notes;

	function MakeSafe() {
		$this->ACID=intval($this->ACID);
		$this->DataCenterID=intval($this->DataCenterID);
		$this->Location=sanitize($this->Location);
		$this->LocationSortable=str_replace(' ','',$this->Location);
		$this->AssignedTo=intval($this->AssignedTo);
		$this->ZoneID=intval($this->ZoneID);
		$this->CabRowID=intval($this->CabRowID);
		$this->Model=sanitize($this->Model);
		$this->ColCap=float_sqlsafe(floatval($this->ColCap));
		$this->InstallationDate=date("Y-m-d", strtotime($this->InstallationDate));
		$this->MapX1=abs($this->MapX1);
		$this->MapY1=abs($this->MapY1);
		$this->MapX2=abs($this->MapX2);
		$this->MapY2=abs($this->MapY2);
		$this->Notes=sanitize($this->Notes,false);
	}

	public function __construct($acid=false){
		if($acid){
			$this->ACID=$acid;
		}
		return $this;
	}

	static function RowToObject($dbRow,$filterrights=true){
		/*
		 * Generic function that will take any row returned from the fac_Cabinet
		 * table and convert it to an object for use in array or other
		 */
		$ac=new AC();
		$ac->ACID=$dbRow["ACID"];
		$ac->DataCenterID=$dbRow["DataCenterID"];
		$ac->Location=$dbRow["Location"];
		$ac->LocationSortable=$dbRow["LocationSortable"];
		$ac->AssignedTo=$dbRow["AssignedTo"];
		$ac->ZoneID=$dbRow["ZoneID"];
		$ac->CabRowID=$dbRow["CabRowID"];
		$ac->Model=$dbRow["Model"];
		$ac->ColCap=$dbRow["ColCap"];
		$ac->InstallationDate=$dbRow["InstallationDate"];
		$ac->MapX1=$dbRow["MapX1"];
		$ac->MapY1=$dbRow["MapY1"];
		$ac->MapX2=$dbRow["MapX2"];
		$ac->MapY2=$dbRow["MapY2"];
		$ac->Notes=$dbRow["Notes"];

		if($filterrights){
			$ac->FilterRights();
		} else {
			// Assume that you can read everything if there's no FilterRights call.
			$ac->Rights = "Read";
		}

		// if($cab->U1Position=="Default"){
		// 	$dc=$_SESSION['datacenters'][$cab->DataCenterID];
		// 	if($dc->U1Position=="Default"){
		// 		global $config;
		// 		$cab->U1Position=$config->ParameterArray["U1Position"];
		// 	}else{
		// 		$cab->U1Position=$dc->U1Position;
		// 	}
		// }

		return $ac;
	}

	private function FilterRights(){
		global $person;
		$this->Rights='None';
		if($person->canRead($this->AssignedTo)){$this->Rights="Read";}
		if($person->canWrite($this->AssignedTo)){$this->Rights="Write";}

		// Remove information that they shouldn't have access to
		if($this->Rights=='None'){
			// ZoneID and CabRowID are probably both not important but meh
			$publicfields=array('ACID','DataCenterID','Location','LocationSortable','ZoneID','CabRowID','Rights','AssignedTo');
			foreach($this as $prop => $value){
				if(!in_array($prop,$publicfields)){
					$this->$prop=null;
				}
			}
		}
	}

	function CreateAC($deferTreeRebuild=false){
		global $dbh;
		
		$this->MakeSafe();

		$sql="INSERT INTO fac_AC SET DataCenterID=$this->DataCenterID, 
			Location=\"$this->Location\", LocationSortable=\"$this->LocationSortable\",
			AssignedTo=$this->AssignedTo, ZoneID=$this->ZoneID, CabRowID=$this->CabRowID, Model=\"$this->Model\", 
			ColCap=$this->ColCap, InstallationDate=\"".date("Y-m-d", strtotime($this->InstallationDate))."\", 
			MapX1=$this->MapX1, MapY1=$this->MapY1, 
			MapX2=$this->MapX2, MapY2=$this->MapY2,
			Notes=\"$this->Notes\";";

		if(!$dbh->exec($sql)){
			$info=$dbh->errorInfo();

			error_log("CreateAC::PDO Error: {$info[2]} SQL=$sql");
			return false;
		}else{
			$this->ACID=$dbh->lastInsertID();
		}

		if ( ! $deferTreeRebuild ) {
			updateNavTreeHTML();
		}
		
		header('Location: '.redirect("ac.php"));
		(class_exists('LogActions'))?LogActions::LogThis($this):'';
		return $this->ACID;
	}

	function UpdateAC(){
		global $dbh;
		
		$this->MakeSafe();

		$old=new AC();
		$old->ACID=$this->ACID;
		$old->GetAC();

		$sql="UPDATE fac_AC SET DataCenterID=$this->DataCenterID, 
			Location=\"$this->Location\", LocationSortable=\"$this->LocationSortable\",
			AssignedTo=$this->AssignedTo, ZoneID=$this->ZoneID, CabRowID=$this->CabRowID, 
			Model=\"$this->Model\", ColCap=$this->ColCap, 
			InstallationDate=\"".date("Y-m-d", strtotime($this->InstallationDate))."\", 
			MapX1=$this->MapX1, MapY1=$this->MapY1, 
			MapX2=$this->MapX2, MapY2=$this->MapY2 WHERE ACID=$this->ACID;";

		if(!$dbh->query($sql)){
			$info=$dbh->errorInfo();

			error_log("UpdateAC::PDO Error: {$info[2]} SQL=$sql" );
			return false;
		}

		updateNavTreeHTML();
				
		(class_exists('LogActions'))?LogActions::LogThis($this,$old):'';
		return true;
	}

	function GetAC(){
		global $dbh;

		$this->MakeSafe();
		
		$sql="SELECT * FROM fac_AC WHERE ACID=$this->ACID;";
		
		if($acRow=$dbh->query($sql)->fetch()){
			foreach(AC::RowToObject($acRow) as $prop => $value){
				$this->$prop=$value;
			}
			return true;
		}else{
			return false;
		}
	}

	static function ListACs($orderbydc=false, $indexed=false){
		global $dbh;
		global $config;

		$acList=array();

		// if AppendCabDC is set then we will be appending the DC to lists so sort them accordingly
		$orderbydc=(!$orderbydc || $config->ParameterArray['AppendAcDC']=='enabled')?'DataCenterID, ':'';
		$sql="SELECT * FROM fac_AC ORDER BY $orderbydc LENGTH(LocationSortable), LocationSortable ASC;";

		foreach($dbh->query($sql) as $acRow){
			$filter = $config->ParameterArray["FilterACList"] == 'Enabled' ? true:false;
			if ( $indexed ) {
				$acList[$acRow["ACID"]]=AC::RowToObject($acRow, $filter);
			} else {
				$acList[]=AC::RowToObject($acRow, $filter);
			}
		}

		return $acList;
	}

	static function getACsForMap( $DataCenterID ) {
		global $dbh;

		$acList = array();

		$st = $dbh->prepare( "select * from fac_AC where DataCenterID=:DataCenterID" );
		$st->setFetchMode( PDO::FETCH_CLASS, "AC" );

		$st->execute( array( ":DataCenterID"=>$DataCenterID ));
		while ( $row = $st->fetch() ) {
			$acList[$row->ACID] = $row;
		}

		return $acList;
	}

	function ListACsByDC($limit=false,$limitzone=false){
		global $dbh;
		global $config;
		
		$this->MakeSafe();

		$sql = "select * from fac_AC where DataCenterID='" . $this->DataCenterID . "'";
		if ( $limitzone && $this->ZoneID>0 ) {
			$sql .= " and ZoneID='" . $this->ZoneID . "'";
		}
		$sql .= " ORDER BY Location ASC";

		$acList = array();
		
		foreach( $dbh->query($sql) as $acRow){
			$filter = $config->ParameterArray["FilterACList"] == 'Enabled' ? true:false;
			$acList[]=AC::RowToObject($acRow, $filter);		
		}
		
		foreach($acList as $i => $ac){
			if($limit && ($ac->MapX1==$ac->MapX2 || $ac->MapY1==$ac->MapY2)){
				unset($acList[$i]);
			}
		}

		return $acList;
	}

	function GetACsByDept(){
		global $dbh;

		$this->MakeSafe();

		$acList = array();

		$sql = "select * from fac_AC where AssignedTo='" . $this->AssignedTo . "'";
		foreach( $dbh->query($sql) as $acRow){
			$filter = $config->ParameterArray["FilterACList"] == 'Enabled' ? true:false;
			$acList[]=AC::RowToObject($acRow, $filter);		
		}

		return $acList;
	}

	function GetACsByZone(){
		global $dbh;
		global $config;

		$this->MakeSafe();

		$acList = array();
		if ( $this->ZoneID>0) {	
			$sql = "select * from fac_AC where ZoneID='" . $this->ZoneID . "'";
			foreach( $dbh->query($sql) as $acRow){
				$filter = $config->ParameterArray["FilterACList"] == 'Enabled' ? true:false;
				$acList[]=AC::RowToObject($acRow, $filter);		
			}
		}
		return $acList;
	}
	
	// function CabinetOccupancy($CabinetID){
	// 	global $dbh;

	// 	$CabinetID=intval($CabinetID);
		
	// 	//$sql="SELECT SUM(Height) AS Occupancy FROM fac_Device WHERE Cabinet=$CabinetID;";
	// 	//JMGA halfdepth height calculation
	// 	$sql = "select sum(if(HalfDepth,Height/2,Height)) as Occupancy from fac_Device where ParentDevice=0 AND Cabinet=$CabinetID";

	// 	if(!$row=$dbh->query($sql)->fetch()){
	// 		$info=$dbh->errorInfo();

	// 		error_log("CabinetOccupancy::PDO Error: {$info[2]} SQL=$sql");
	// 		return false;
	// 	}

	// 	return $row["Occupancy"];
	// }

	// static function GetOccupants($CabinetID){
	// 	global $dbh;

	// 	$sql="SELECT Owner FROM fac_Device WHERE Cabinet=".intval($CabinetID)." Group By Owner;";

	// 	$occupants=array();
	// 	foreach($dbh->query($sql) as $row){
	// 		$occupants[]=$row[0];
	// 	}

	// 	return $occupants;
	// }

	function GetZoneSelectList(){
		global $dbh;
		
		$this->MakeSafe();
		
		$sql="SELECT * FROM fac_Zone WHERE DataCenterID=$this->DataCenterID ORDER BY Description;";

		$selectList='<select name="zoneid" id="zoneid">';
		$selectList.='<option value=0>'.__("None").'</option>';

		foreach($dbh->query($sql) as $selectRow){
			$selected=($selectRow["ZoneID"]==$this->ZoneID)?' selected':'';
			$selectList.="<option value=\"{$selectRow["ZoneID"]}\"$selected>{$selectRow["Description"]}</option>";
		}

		$selectList.='</select>';

		return $selectList;
	}

	function GetACsByRow($rear=false){
		global $dbh;

		$this->MakeSafe();

		$cabrow=new CabRow();
		$cabrow->CabRowID=$this->CabRowID;

		$sql="SELECT MIN(MapX1) AS MapX1, MAX(MapX2) AS MapX2, MIN(MapY1) AS MapY1, 
			MAX(MapY2) AS MapY2, AVG(MapX1) AS AvgX1, AVG(MapX2) AS AvgX2, COUNT(*) AS 
			ACCount FROM fac_AC WHERE CabRowID=$cabrow->CabRowID AND MapX1>0 
			AND MapX2>0 AND MapY1>0 and MapY2>0;";
		$shape=$dbh->query($sql)->fetch();

		// size of average cabinet
		$sX=$shape["AvgX2"]-$shape["AvgX1"];
		// change in x and y to give overall shape of row
		$cX=$shape["MapX2"]-$shape["MapX1"];
		$cY=$shape["MapY2"]-$shape["MapY1"];

		/*
		 * In rows with more than one cabinet we can determine the layout based on
		 * their size.  The side of a row will be close to the change in x or y while
		 * the front/rear of a row will be equal to the average of the sides 
		 * multiplied by the number of objects in the set
		 *
		 * change = size * number of cabinets
		 */
		$layout=($cX==$sX*$shape["ACCount"] || $cX>$cY)?"Horizontal":"Vertical";
		$order=($layout=="Horizontal")?"MapX1,":"MapY1,";
		// $frontedge=$cabrow->GetCabRowFrontEdge($layout);

		// Order first by row layout then by natural sort
		$sql="SELECT * FROM fac_AC WHERE CabRowID=$cabrow->CabRowID ORDER BY $order 
			LocationSortable ASC;";

		$acList=array();
		foreach($dbh->query($sql) as $acRow){
			$acList[]=AC::RowToObject($acRow);
		}

		// if($frontedge=="Right" || $frontedge=="Top"){
		// 	$cabinetList=array_reverse($cabinetList);
		// }

		return $acList;
	}

	function GetCabRowSelectList(){
		global $dbh;

		$this->MakeSafe();
		
		$sql="SELECT * FROM fac_CabRow WHERE ZoneID=$this->ZoneID ORDER BY Name;";

		$selectList='<select name="cabrowid" id="cabrowid">';
		$selectList.='<option value=0>'.__("None").'</option>';
		
		foreach($dbh->query($sql) as $selectRow){
			$selected=($selectRow["CabRowID"]==$this->CabRowID)?' selected':'';
			$selectList.="<option value=\"{$selectRow["CabRowID"]}\"$selected>{$selectRow["Name"]}</option>";
		}

		$selectList.='</select>';

		return $selectList;
	}
	
	function GetACSelectList(){
		global $dbh;
		global $person;
		
		$sql="SELECT Name, CabinetID, Location, AssignedTo FROM fac_DataCenter, fac_AC WHERE 
			fac_DataCenter.DataCenterID=fac_AC.DataCenterID ORDER BY Name ASC, 
			Location ASC, LENGTH(Location);";

		$selectList="<select name=\"CabinetID\" id=\"CabinetID\"><option value=\"-1\">Storage Room</option>";

		foreach($dbh->query($sql) as $selectRow){
			if($selectRow["ACID"]==$this->ACID || $person->canWrite($selectRow["AssignedTo"])){
				$selected=($selectRow["ACID"]==$this->ACID)?' selected':'';
				$selectList.="<option value=\"{$selectRow["ACID"]}\"$selected>{$selectRow["Name"]} / {$selectRow["Location"]}</option>";
			}
		}

		$selectList .= "</select>";

		return $selectList;
	}

	function DeleteAC(){
		global $dbh;
		
		// /* Need to delete all devices and CDUs first */
		// $tmpDev=new Device();
		// $tmpCDU=new PowerDistribution();
		
		// $tmpDev->Cabinet=$this->CabinetID;
		// $devList=$tmpDev->ViewDevicesByCabinet();
		
		// foreach($devList as &$delDev){
		// 	$delDev->DeleteDevice();
		// }
		
		// $tmpCDU->CabinetID=$this->CabinetID;
		// $cduList=$tmpCDU->GetPDUbyCabinet();
		
		// foreach($cduList as &$delCDU){
		// 	$delCDU->DeletePDU();
		// }

		// // Remove from any projects
		// ProjectMembership::removeMember( $this->CabinetID, 'Cabinet' );
		
		$sql="DELETE FROM fac_AC WHERE ACID=$this->ACID;";


		if(!$dbh->exec($sql)){
			$info=$dbh->errorInfo();

			error_log("PDO Error::DeleteAC: {$info[2]} SQL=$sql");
			return false;
		}
	
		updateNavTreeHTML();
				
		(class_exists('LogActions'))?LogActions::LogThis($this):'';
		return true;
	}

	function Search($indexedbyid=false,$loose=false){
		global $dbh;
		// Store the value of frontedge before we muck with it
		$ot=$this->FrontEdge;
		$op = $this->U1Position;

		// Make everything safe for us to search with
		$this->MakeSafe();

		// This will store all our extended sql
		$sqlextend="";
		foreach($this as $prop => $val){
			// We force the following values to knowns in makesafe 
			// if($prop=="FrontEdge" && $val=="Top" && $ot!="Top"){
			// 	continue;
			// }
			// if($prop=="U1Position" && $val=="Default" && $op!="Default") {
			// 	continue;
			// }
			if($val && $val!=date("Y-m-d", strtotime(0))){
				extendsql($prop,$val,$sqlextend,$loose);
			}
		}

		$sql="SELECT * FROM fac_AC $sqlextend ORDER BY LocationSortable ASC";

		$acList=array();
		foreach($dbh->query($sql) as $cabRow){
			if($indexedbyid){
				$acList[$cabRow["ACID"]]=AC::RowToObject($cabRow);
			}else{
				$acList[]=AC::RowToObject($cabRow);
			}
		}

		return $acList;
	}

	// Make a simple reference to a loose search
	function LooseSearch($indexedbyid=false){
		return $this->Search($indexedbyid,true);
	}

	// function SearchByCustomTag( $tag=null ) {
	// 	global $dbh;
		
	// 	$sql="SELECT a.* from fac_Cabinet a, fac_CabinetTags b, fac_Tags c WHERE 
	// 		a.CabinetID=b.CabinetID AND b.TagID=c.TagID AND UCASE(c.Name) LIKE 
	// 		UCASE('%".sanitize($tag)."%') ORDER BY LocationSortable;";

	// 	$cabinetList=array();

	// 	foreach ( $dbh->query( $sql ) as $cabinetRow ) {
	// 		$cabID=$cabinetRow["CabinetID"];
	// 		$cabinetList[$cabID]=Cabinet::RowToObject($cabinetRow);
	// 	}
	// 	return $cabinetList;
	// }
	
	// function GetTags() {
	// 	global $dbh;
		
	// 	$sql = "SELECT TagID FROM fac_CabinetTags WHERE CabinetID=".intval($this->CabinetID).";";

	// 	$tags = array();

	// 	foreach ( $dbh->query( $sql ) as $row ) {
	// 		$tags[]=Tags::FindName($row[0]);
	// 	}

	// 	return $tags;
	// }

	// function SetTags($tags=array()){
	// 	global $dbh;
		
	// 	if(count($tags)>0){
	// 		//Clear existing tags
	// 		$this->SetTags();
	// 		foreach($tags as $tag){
	// 			$t=Tags::FindID($tag);
	// 			if($t==0){
	// 				$t=Tags::CreateTag($tag);
	// 			}
	// 			$sql="INSERT INTO fac_CabinetTags (CabinetID, TagID) VALUES (".intval($this->CabinetID).",$t);";
	// 			if ( ! $dbh->exec($sql) ) {
	// 				$info = $dbh->errorInfo();

	// 				error_log( "PDO Error: " . $info[2] . " SQL=" . $sql );
	// 				return false;
	// 			}			
	// 		}
	// 	}else{
	// 		//If no array is passed then clear all the tags
	// 		$delsql="DELETE FROM fac_CabinetTags WHERE CabinetID=".intval($this->CabinetID).";";
	// 		$dbh->exec($delsql);
	// 	}
	// 	return 0;
	// }

	// static function getStats($CabinetID){
	// 	global $dbh;
	// 	$cab=new Cabinet($CabinetID);
	// 	if(!$cab->GetCabinet()){return false;}

	// 	$cabstats=new stdClass();
	// 	//Weight
	// 	$sql="SELECT SUM(NominalWatts) AS watts, SUM(Weight) AS weight FROM 
	// 		fac_Device WHERE Cabinet=$cab->CabinetID AND DeviceType != 'CDU';";

	// 	foreach($dbh->query($sql) as $row){
	// 		$cabstats->Weight=(!is_null($row['weight']))?$row['weight']:0;
	// 		$cabstats->Wattage=(!is_null($row['watts']))?$row['watts']:0;
	// 	}

	// 	return $cabstats;
	// }

	// function getPictures(){
	// 	global $dbh;
	// 	$sql="SELECT * FROM fac_DeviceCache WHERE DeviceID IN (SELECT DeviceID FROM 
	// 		fac_Device WHERE Cabinet=$this->CabinetID AND ParentDevice=0);";
	// 	$devarray=array();
	// 	foreach($dbh->query($sql) as $row){
	// 		$devarray[$row['DeviceID']]['Front']=html_entity_decode($row['Front']);
	// 		$devarray[$row['DeviceID']]['Rear']=html_entity_decode($row['Rear']);
	// 	}
	// 	return $devarray;
	// }
}
?>
