<?php
/*
	VIO Intelligence DCIM

	This is the main class library for the VIO Intelligence DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/

class ColorCoding {
	var $ColorID;
	var $Name;
	var $DefaultNote;
	
	function CreateCode() {
		global $dbh;
		
		$sql="INSERT INTO fac_ColorCoding SET Name=\"".sanitize($this->Name)."\", 
			DefaultNote=\"".sanitize($this->DefaultNote)."\"";
		
		if($dbh->exec($sql)){
			$this->ColorID=$dbh->lastInsertId();
		}else{
			$info=$dbh->errorInfo();

			error_log("PDO Error::CreateCode {$info[2]}");
			return false;
		}
		
		return $this->ColorID;
	}
	
	function UpdateCode() {
		global $dbh;
		
		$sql="UPDATE fac_ColorCoding SET Name=\"".sanitize($this->Name)."\", 
			DefaultNote=\"".sanitize($this->DefaultNote)."\" WHERE ColorID=".intval($this->ColorID).";";
		
		if(!$dbh->query($sql)){
			$info=$dbh->errorInfo();
			error_log("PDO Error: {$info[2]}");
			return false;
		}else{		
			return true;
		}
	}
	
	function DeleteCode() {
		/* If you call this, the upstream application should be checking to see if it is used already - you don't want to
			create orphan connetions that reference this color code! */
		global $dbh;
		
		$sql="DELETE FROM fac_ColorCoding WHERE ColorID=".intval($this->ColorID);
		
		if(!$dbh->exec($sql)){
			$info=$dbh->errorInfo();

			error_log("PDO Error: {$info[2]}");
			return false;
		}
		
		return true;
	}
	
	function GetCode() {
		global $dbh;
		
		$sql="SELECT * FROM fac_ColorCoding WHERE ColorID=".intval($this->ColorID);

		if($row=$dbh->query($sql)->fetch()){
			$this->Name=$row["Name"];
			$this->DefaultNote=$row["DefaultNote"];
		}else{
			return false;
		}
			
		return true;
	}
	
	function GetCodeByName() {
		global $dbh;
		
		$sql="SELECT * FROM fac_ColorCoding WHERE Name='".transform($this->Name)."';";

		if($row=$dbh->query($sql)->fetch()){
			$this->ColorID=$row["ColorID"];
			$this->DefaultNote=$row["DefaultNote"];
		}else{
			return false;
		}
			
		return true;
	}
	
	
	static function GetCodeList($indexedby="ColorID") {
		global $dbh;
		
		$sql="SELECT * FROM fac_ColorCoding ORDER BY Name ASC";
		
		$codeList=array();
		foreach($dbh->query($sql) as $row){
			$n=$row[$indexedby]; // index array by id
			$codeList[$n]=new ColorCoding();
			$codeList[$n]->ColorID=$row["ColorID"];
			$codeList[$n]->Name=$row["Name"];
			$codeList[$n]->DefaultNote=$row["DefaultNote"];
		}
		
		return $codeList;
	}

	static function ResetCode($colorid,$tocolorid=0){
	/*
	 * This probably shouldn't be a function here since it will only be used in one
	 * place. This function will remove a color code from any device ports or will
	 * set it to another via an optional second color id
	 *
	 */
		global $dbh;
		$colorid=intval($colorid);
		$tocolorid=intval($tocolorid); // it will always be 0 unless otherwise set

		$sqlp="UPDATE fac_Ports SET ColorID=$tocolorid WHERE ColorID=$colorid;";
		$sqlt="UPDATE fac_TemplatePorts SET ColorID=$tocolorid WHERE ColorID=$colorid;";
		$sqlm="UPDATE fac_MediaTypes SET ColorID=$tocolorid WHERE ColorID=$colorid;";

		$error=false;
		$error=($dbh->query($sqlp))?false:true;
		$error=($dbh->query($sqlt))?false:true;
		$error=($dbh->query($sqlm))?false:true;

		if($error){
			$info=$dbh->errorInfo();
			error_log("PDO Error: {$info[2]}");
			return false;
		}else{		
			return true;
		}
	}

	static function TimesUsed($colorid){
		global $dbh;
		$colorid=intval($colorid);

		// get a count of the number of times this color is in use both on ports or assigned
		// to a template.  
		$sql="SELECT COUNT(*) + (SELECT COUNT(*) FROM fac_MediaTypes WHERE ColorID=$colorid) +
			(SELECT COUNT(*) FROM fac_TemplatePorts WHERE ColorID=$colorid)
			AS Result FROM fac_Ports WHERE ColorID=$colorid";
		$count=$dbh->prepare($sql);
		$count->execute();
		

		return $count->fetchColumn();
	}
}
?>
