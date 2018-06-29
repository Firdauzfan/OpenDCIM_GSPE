<?php
/*
	VIO Intelligence DCIM

	This is the main class library for the VIO Intelligence DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/

class CabinetMetrics {
	var $CabinetID;
	var $IntakeTemperature;
	var $IntakeHumidity;
	var $ExhaustTemperature;
	var $ExhaustHumidity;
	var $CalculatedPower;
	var $CalculatedWeight;
	var $MeasuredPower;
	var $LastRead;
	var $SpaceUsed;

	static function getMetrics( $CabinetID ) {
		global $dbh;
		
		$m = new CabinetMetrics();
		$m->CabinetID = $CabinetID;
		
		$params = array( ":CabinetID"=>$CabinetID );
		// Get the intake side
		$sql = "select max(Temperature) as Temp, max(Humidity) as Humid, LastRead from fac_SensorReadings where DeviceID in (select DeviceID from fac_Device where DeviceType='Sensor' and BackSide=0 and Cabinet=:CabinetID)";
		$st = $dbh->prepare( $sql );
		$st->execute( $params );
		if ( $row = $st->fetch() ) {
			$m->IntakeTemperature = $row["Temp"];
			$m->IntakeHumidity = $row["Humid"];
			$m->LastRead = $row["LastRead"];
		} else {
			error_log( "SQL Error CabinetMetrics::getMetrics" );
		}
		
		// Now the exhaust side
		$sql = "select max(Temperature) as Temp, max(Humidity) as Humid, LastRead from fac_SensorReadings where DeviceID in (select DeviceID from fac_Device where DeviceType='Sensor' and BackSide=1 and Cabinet=:CabinetID)";
		$st = $dbh->prepare( $sql );
		$st->execute( $params );
		if ( $row = $st->fetch() ) {
			$m->ExhaustTemperature = $row["Temp"];
			$m->ExhaustHumidity = $row["Humid"];
		}

		// Now the devices in the cabinet
		// Watts needs to count ALL devices
		$sql = "select sum(a.NominalWatts) as Power, sum(a.Height) as SpaceUsed, sum(b.Weight) as Weight from fac_Device a, fac_DeviceTemplate b where a.TemplateID=b.TemplateID and Cabinet=:CabinetID";
		$st = $dbh->prepare( $sql );
		$st->execute( $params );
		if ( $row = $st->fetch() ) {
			$m->CalculatedPower = $row["Power"];
			$m->CalculatedWeight = $row["Weight"];
		}

		// Space needs to only count devices that are not children of other devices (slots in a chassis)
		$sql = "select sum(if(HalfDepth,Height/2,Height)) as SpaceUsed from fac_Device where Cabinet=:CabinetID and ParentDevice=0";
		$st = $dbh->prepare( $sql );
		$st->execute( $params );
		if ( $row = $st->fetch() ) {
					$m->SpaceUsed = $row["SpaceUsed"];
		}

		
		// And finally the power readings
		$sql = "select sum(Wattage) as Power from fac_PDUStats where PDUID in (select DeviceID from fac_Device where DeviceType='CDU' and Cabinet=:CabinetID)";
		$st = $dbh->prepare( $sql );
		$st->execute( $params );
		if ( $row = $st->fetch() ) {
			$m->MeasuredPower = $row["Power"];
		}
		
		return $m;
	}
}
?>