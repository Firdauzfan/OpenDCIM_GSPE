<?php
/*
	VIO Intelligence DCIM

	This is the main class library for the VIO Intelligence DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/

class MeasurePoint {
	var $MeasurePointID;
	var $Name;
	var $MeasurementType;
	var $UnitOfMeasure;
	var $AcquisitionMechanism;
	var $Resource;

	function prepare( $sql ) {
		global $dbh;

		return $dbh->prepare( $sql );
	}

	function lastInsertId() {
		global $dbh;

		return $dbh->lastInsertId();
	}

	function makeSafe() {
		// Valid measurement types (for starters)
		$validMeasureTypes = array( "Power", "Temperature", "Humidity", "Position", "Capacity", "Flow" );
		// Valid units of measure (for now)
		$validUoM = array( "Watts", "Degrees", "Percentage", "CFM" );

		$this->MeasurePointID = intval( $this->MeasurePointID );
		$this->Name = sanitize( $this->Name );
		$this->MeasurementType = ( in_array( $this->MeasurementType, $validMeasureTypes ))?$this->MeasurementType:"Power";
		$this->UnitOfMeasure = ( in_array( $this->UnitOfMeasure, $validUoM ))?$this->UnitOfMeasure:"Watts";
		$this->AcquisitionMechanism = intval( $this->AcquisitionMechanism );
		// This might have to be removed depending on what we encounter with API specs, but I think it should be ok
		$this->Resource = sanitize( $this->Resource );
	}

	public function getPoint( $MeasurePointID = false ) {
		$this->makeSafe();

		if ( $MeasurePointID == false ) {
			$sql = "select * from fac_MeasurePoint order by Name ASC";
			$args = array();
		} else {
			$sql = "select * from fac_MeasurePoint where MeasurePointID=:MeasurePointID";
			$args = array( ":MeasurePointID" => $MeasurePointID );
		}

		$st = $this->prepare( $sql );
		$st->setFetchMode( PDO::FETCH_CLASS, "MeasurePoint" );
		$st->execute( $args );

		$mpList = array();
		while ( $row = $st->fetch() ) {
			$mpList[] = $row;
		}

		return $mpList;
	}

	function savePoint() {
		$this->makeSafe();

		$sql = "insert into fac_MeasurePoint set MeasurePointID=:MeasurePointID, Name=:Name, MeasurementType=:MeasurementType, UnitOfMeasure=:UnitOfMeasure, AcquisitionMechanism=:AcquisitionMechanism, Resource=:Resource on duplicate key update Name=:Name, MeasurementType=:MeasurementType, UnitOfMeasure=:UnitOfMeasure, AcquisitionMechanism=:AcquisitionMechanism, Resource=:Resource";
		$args = array( 	":MeasurePointID" => $this->MeasurePointID,
						":Name" => $this->Name,
						":MeasurementType" => $this->MeasurementType,
						":UnitOfMeasure" => $this->UnitOfMeasure,
						":AcquisitionMechanism" => $this->AcquisitionMechanism,
						":Resource" => $this->Resource );

		$st = $this->prepare( $sql );
		$st->execute( $args );

		if ( $this->MeasurePointID == 0 ) {
			$this->MeasurePointID = $this->lastInsertID();
		}

		return;
	}
}

?>