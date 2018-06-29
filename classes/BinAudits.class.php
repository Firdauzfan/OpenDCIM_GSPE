<?php
/*
	VIO Intelligence DCIM

	This is the main class library for the VIO Intelligence DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/
class BinAudits {
	var $BinID;
	var $UserID;
	var $AuditStamp;

	function MakeSafe(){
		$this->BinID=intval($this->BinID);
		$this->UserID=sanitize($this->UserID);
		$this->AuditStamp=sanitize($this->AuditStamp);
	}

	function MakeDisplay(){
		$this->UserID=stripslashes($this->UserID);
		$this->AuditStamp=stripslashes($this->AuditStamp);
	}

	function exec($sql){
		global $dbh;
		return $dbh->exec($sql);
	}
	
	function AddAudit(){
		$this->AuditStamp=date("Y-m-d",strtotime($this->AuditStamp));
		$this->MakeSafe();

		$sql="INSERT INTO fac_BinAudits SET BinID=$this->BinID, UserID=\"$this->UserID\", AuditStamp=\"$this->AuditStamp\";";
		$this->exec($sql);
	}
}
?>
