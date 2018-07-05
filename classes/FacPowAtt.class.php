<?php
/*
	VIO Intelligence DCIM

	This is the main class library for the VIO Intelligence DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/

class FacPowAtt {

	var $PowAttID;
	var $DataCenterID;
	var $Location;
	var $TotAmp;
	var $InputVolt;
	var $Switchboard;
	var $PowerPath;
	var $GenRedund;
	var $UPSCapacity;
	var $UPSRedundancy;
	var $UPSRuntime;
	var $UPSOutput;

	function MakeSafe() {
		$this->PowAttID=intval($this->PowAttID);
		$this->DataCenterID=intval($this->DataCenterID);
		$this->Location=sanitize($this->Location);
		$this->LocationSortable=str_replace(' ','',$this->Location);
		$this->AssignedTo=intval($this->AssignedTo);
		$this->TotAmp=sanitize($this->TotAmp);
		$this->InputVolt=sanitize($this->InputVolt);
		$this->Switchboard=sanitize($this->Switchboard);
		$this->PowerPath=sanitize($this->PowerPath);
		$this->GenRedund=sanitize($this->GenRedund);
		$this->UPSCapacity=sanitize($this->UPSCapacity);
		$this->UPSRedundancy=sanitize($this->UPSRedundancy);
		$this->UPSRuntime=sanitize($this->UPSRuntime);
		$this->UPSOutput=sanitize($this->UPSOutput);
	}

	public function __construct($facpowid=false){
		if($facpowid){
			$this->PowAttID=$facpowid;
		}
		return $this;
	}

	static function RowToObject($dbRow){
		/*
		 * Generic function that will take any row returned from the fac_Cabinet
		 * table and convert it to an object for use in array or other
		 */
		$powattid=new FacPowAtt();
		$powattid->PowAttID=$dbRow["PowAttID"];
		$powattid->DataCenterID=$dbRow["DataCenterID"];
		$powattid->Location=$dbRow["Location"];
		$powattid->LocationSortable=$dbRow["LocationSortable"];
		$powattid->AssignedTo=$dbRow["AssignedTo"];
		$powattid->TotAmp=$dbRow["TotAmp"];
		$powattid->InputVolt=$dbRow["InputVolt"];
		$powattid->Switchboard=$dbRow["Switchboard"];
		$powattid->PowerPath=$dbRow["PowerPath"];
		$powattid->GenRedund=$dbRow["GenRedund"];
		$powattid->UPSCapacity=$dbRow["UPSCapacity"];
		$powattid->UPSRedundancy=$dbRow["UPSRedundancy"];
		$powattid->UPSRuntime=$dbRow["UPSRuntime"];
		$powattid->UPSOutput=$dbRow["UPSOutput"];

		return $powattid;
	}

	function sql_cek(){
		$this->MakeSafe();

		$sql_cek = mysqli_query("SELECT COUNT(DataCenterID) AS jml FROM fac_PowAtt WHERE DataCenterID='$this->DataCenterID'") or die(mysqli_error());
		$row = mysqli_fetch_assoc($sql_cek);
		$jml = $row["jml"];

		return $jml;
	}

	function CreateFacPowAtt(){
		global $dbh;
		
		$this->MakeSafe();

		$sql_cek = "SELECT COUNT(DataCenterID) AS jml FROM fac_PowAtt WHERE DataCenterID='$this->DataCenterID'";
		$row = $dbh->query($sql_cek)->fetch();
		$jml = $row["jml"];

		if ($jml<1) {
		$sql="INSERT INTO fac_PowAtt VALUES ('','$this->DataCenterID','$this->Location','$this->LocationSortable','$this->AssignedTo','$this->TotAmp','$this->InputVolt','$this->Switchboard','$this->PowerPath','$this->GenRedund','$this->UPSCapacity','$this->UPSRedundancy','$this->UPSRuntime','$this->UPSOutput')";

		if(!$dbh->exec($sql)){
			$info=$dbh->errorInfo();

			error_log("CreateFacPowAtt::PDO Error: {$info[2]} SQL=$sql");
			return false;
		}else{
			$this->PowAttID=$dbh->lastInsertID();
		}
		
		header('Location: '.redirect("facpowatt.php"));
		(class_exists('LogActions'))?LogActions::LogThis($this):'';
		return $this->PowAttID;
		}else{
			echo '<script type="text/javascript">alert("Data Center Sudah Terdaftar"); </script>';
		}
		
	}

	function UpdateFacPowAtt(){
		global $dbh;
		
		$this->MakeSafe();

		$old=new FacPowAtt();
		$old->PowAttID=$this->PowAttID;
		$old->GetFacPowAtt();

		$sql="UPDATE fac_PowAtt SET DataCenterID='$this->DataCenterID',	Location='$this->Location', LocationSortable='$this->LocationSortable',
			AssignedTo='$this->AssignedTo', TotAmp='$this->TotAmp', InputVolt='$this->InputVolt', Switchboard='$this->Switchboard', PowerPath='$this->PowerPath', GenRedund='$this->GenRedund', UPSCapacity='$this->UPSCapacity', UPSRedundancy='$this->UPSRedundancy', UPSRuntime='$this->UPSRuntime', UPSOutput='$this->UPSOutput' WHERE PowAttID='$this->PowAttID'";

		if(!$dbh->query($sql)){
			$info=$dbh->errorInfo();

			error_log("UpdateFacPowAtt::PDO Error: {$info[2]} SQL=$sql" );
			return false;
		}
				
		(class_exists('LogActions'))?LogActions::LogThis($this,$old):'';
		return true;
	}

	function GetFacPowAtt(){
		global $dbh;

		$this->MakeSafe();
		
		$sql="SELECT * FROM fac_PowAtt WHERE PowAttID='$this->PowAttID'";
		
		if($powattidRow=$dbh->query($sql)->fetch()){
			foreach(FacPowAtt::RowToObject($powattidRow) as $prop => $value){
				$this->$prop=$value;
			}
			return true;
		}else{
			return false;
		}
	}

	static function ListFacPowAtts($orderbydc=false, $indexed=false){
		global $dbh;
		global $config;

		$powattidList=array();

		$sql="SELECT * FROM fac_PowAtt ORDER BY LENGTH(LocationSortable), LocationSortable ASC;";

		foreach($dbh->query($sql) as $powattidRow){
			$filter = $config->ParameterArray["FilterFacPowAttList"] == 'Enabled' ? true:false;
			if ( $indexed ) {
				$powattidList[$powattidRow["PowAttID"]]=FacPowAtt::RowToObject($powattidRow, $filter);
			} else {
				$powattidList[]=FacPowAtt::RowToObject($powattidRow, $filter);
			}
		}

		return $powattidList;
	}

	function ListFacPowAttsByDC($limit=false,$limitzone=false){
		global $dbh;
		global $config;
		
		$this->MakeSafe();

		$sql = "SELECT * from fac_PowAtt where DataCenterID='$this->DataCenterID' ORDER BY Location ASC;";

		$powattidList = array();
		
		foreach( $dbh->query($sql) as $powattidRow){
			$filter = $config->ParameterArray["FilterACList"] == 'Enabled' ? true:false;
			$powattidList[]=FacPowAtt::RowToObject($powattidRow, $filter);		
		}
		
		// foreach($acList as $i => $ac){
		// 	if($limit && ($ac->MapX1==$ac->MapX2 || $ac->MapY1==$ac->MapY2)){
		// 		unset($acList[$i]);
		// 	}
		// }

		return $powattidList;
	}

	function GetFacPowAttsByDept(){
		global $dbh;

		$this->MakeSafe();

		$powattidList = array();

		$sql = "SELECT * from fac_PowAtt where AssignedTo='$this->AssignedTo'";
		foreach( $dbh->query($sql) as $powattidRow){
			$filter = $config->ParameterArray["FilterACList"] == 'Enabled' ? true:false;
			$powattidList[]=FacPowAtt::RowToObject($powattidRow, $filter);		
		}

		return $powattidList;
	}

	function DeleteFacPowAtt(){
		global $dbh;
		
		$sql="DELETE FROM fac_PowAtt WHERE PowAttID=$this->PowAttID;";


		if(!$dbh->exec($sql)){
			$info=$dbh->errorInfo();

			error_log("PDO Error::DeleteFacPowAtt: {$info[2]} SQL=$sql");
			return false;
		}
	
		(class_exists('LogActions'))?LogActions::LogThis($this):'';
		return true;
	}

	function Search($indexedbyid=false,$loose=false){
		global $dbh;
		// Store the value of frontedge before we muck with it

		// Make everything safe for us to search with
		$this->MakeSafe();

		// This will store all our extended sql
		$sqlextend="";
		foreach($this as $prop => $val){
			if($val && $val!=date("Y-m-d", strtotime(0))){
				extendsql($prop,$val,$sqlextend,$loose);
			}
		}

		$sql="SELECT * FROM fac_PowAtt $sqlextend ORDER BY LocationSortable ASC";

		$powattidList=array();
		foreach($dbh->query($sql) as $cabRow){
			if($indexedbyid){
				$powattidList[$cabRow["PowAttID"]]=FacPowAtt::RowToObject($cabRow);
			}else{
				$powattidList[]=FacPowAtt::RowToObject($cabRow);
			}
		}

		return $powattidList;
	}

	// Make a simple reference to a loose search
	function LooseSearch($indexedbyid=false){
		return $this->Search($indexedbyid,true);
	}

}
?>
