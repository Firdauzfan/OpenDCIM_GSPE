<?php
/*
	VIO Intelligence DCIM

	This is the main class library for the VIO Intelligence DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/

class DispositionMembership {
	var $DispositionID;
	var $DeviceID;
	var $DispositionDate;
	var $DisposedBy;

	function prepare( $sql ) {
		global $dbh;

		return $dbh->prepare( $sql );
	}

	function addDevice() {
		$st = $this->prepare( "insert into fac_DispositionMembership set DispositionID=:DispositionID, DeviceID=:DeviceID, DispositionDate=NOW(), DisposedBy=:DisposedBy" );
		return $st->execute( array( ":DispositionID"=>$this->DispositionID, ":DeviceID"=>$this->DeviceID, ":DisposedBy"=>$this->DisposedBy ));
	}

	static function getDevices( $DispositionID ) {
		global $dbh;

		$st = $dbh->prepare( "select * from fac_DispositionMembership where DispositionID=:DispositionID" );
		$st->setFetchMode( PDO::FETCH_CLASS, "DispositionMembership" );

		$st->execute( array( ":DispositionID"=>$DispositionID ));

		$devList = array();

		while ( $row = $st->fetch() ) {
			$devList[] = $row;
		}

		return $devList;
	}

	static function removeDevice( $DeviceID ) {
		global $dbh;

		$st = $dbh->prepare( "delete from fac_DispositionMembership where DeviceID=:DeviceID" );
		return $st->execute( array( ":DeviceID"=>$DeviceID ));
	}
}
?>