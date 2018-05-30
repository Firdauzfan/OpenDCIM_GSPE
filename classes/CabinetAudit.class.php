<?php
/*
	VIO Intelligence DCIM

	This is the main class library for the VIO Intelligence DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/

class CabinetAudit {
	/*	CabinetAudit:	A perpetual audit trail for how often a cabinet has been audited, and by what user.
	*/
	
	var $CabinetID;
	var $UserID;
	var $AuditStamp;
	var $Comments;

	function CertifyAudit() {
		if($this->Comments){
			$tmpAudit=new CabinetAudit();
			$tmpAudit->CabinetID=$this->CabinetID;
			(class_exists('LogActions'))?LogActions::LogThis($this,$tmpAudit):'';
		}else{
			(class_exists('LogActions'))?LogActions::LogThis($this):'';
		}

		return;
	}

	function GetLastAudit( $db = null ) {
		global $dbh;
		
		$sql = "select * from fac_GenericLog where ObjectID=\"" . intval( $this->CabinetID ) . "\" and Class=\"CabinetAudit\" order by Time DESC Limit 1";

		if($row=$dbh->query($sql)->fetch()){
			$this->CabinetID=$row["ObjectID"];
			$this->UserID=$row["UserID"];
			$this->AuditStamp=date("M d, Y H:i", strtotime($row["Time"]));

			return true;
		} else {
			// No sense in logging an error for something that's never been done
			return false;
		}
	}
	
	function GetLastAuditByUser() {
		global $dbh;
				
		$sql = "select * from fac_GenericLog where UserID=\"" . addslashes( $this->UserID ) . "\" and Class=\"CabinetAudit\" order by Time DESC Limit 1";

		if ( $row = $dbh->query( $sql )->fetch() ) {
			$this->CabinetID = $row["ObjectID"];
			$this->UserID = $row["UserID"];
			$this->AuditStamp = date( "M d, Y H:i", strtotime( $row["Time"] ) );
		} else {
			$info = $dbh->errorInfo();

			error_log( "PDO Error: " . $info[2] . " SQL=" . $sql );
			return false;
		}
		
		return;
	}
}
?>