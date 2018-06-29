<?php
/*
	VIO Intelligence DCIM

	This is the main class library for the VIO Intelligence DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/

class RCI {
	static function GetStatistics( $limit = "global", $id = "" ) {
		//
		//	This function will return all statistics associated with the Rack Cooling Index
		//
		
		global $dbh;
		global $config;
		
		switch ( $limit ) {
			case "dc":
				$limitSQL = "and c.DataCenterID=$id";
				break;
			case "zone":
				$limitSQL = "and c.ZoneID=$id";
				break;
			default:
				$limitSQL = "";
		}

		$countSQL = "select count(distinct(b.Cabinet)) as TotalCabinets from fac_SensorReadings a, fac_Device b, fac_Cabinet c where a.DeviceID=b.DeviceID and b.Cabinet=c.CabinetID and b.BackSide=0 " . $limitSQL;
		$st = $dbh->prepare( $countSQL );
		$st->execute();
		$row = $st->fetch();
		$result["TotalCabinets"] = $row["TotalCabinets"];
		
		$lowSQL = "select c.Location, a.Temperature from fac_SensorReadings a, fac_Device b, fac_Cabinet c where a.DeviceID=b.DeviceID and b.BackSide=0 and b.Cabinet=c.CabinetID and a.Temperature<>0 and a.Temperature<'" . $config->ParameterArray["RCILow"] . "' $limitSQL order by Location ASC";
		$RCILow = array();
		$st = $dbh->prepare( $lowSQL );
		$st->execute();
		while ( $row = $st->fetch() ) {
			array_push( $RCILow, array( $row["Location"], $row["Temperature"] ));
		}
		
		$result["RCILowCount"] = sizeof( $RCILow );
		$result["RCILowList"] = $RCILow;
		
		$highSQL = "select c.Location, a.Temperature from fac_SensorReadings a, fac_Device b, fac_Cabinet c where a.DeviceID=b.DeviceID and b.BackSide=0 and b.Cabinet=c.CabinetID and a.Temperature<>0 and a.Temperature>'" . $config->ParameterArray["RCIHigh"] . "' $limitSQL order by Location ASC";
		$RCIHigh = array();
		$st = $dbh->prepare( $highSQL );
		$st->execute();
		while ( $row = $st->fetch() ) {
			array_push( $RCIHigh, array( $row["Location"], $row["Temperature"] ));
		}
		
		$result["RCIHighCount"] = sizeof( $RCIHigh );
		$result["RCIHighList"] = $RCIHigh;
		
		return $result;
	}
}	

?>
