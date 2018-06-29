<?php
/*
	VIO Intelligence DCIM

	This is the main class library for the VIO Intelligence DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/

class SwitchInfo {
	/* All of these functions will REQUIRE the built-in SNMP functions - the external calls are simply too slow */
	static private function BasicTests($DeviceID){
		global $config;

		// First check if the SNMP library is present
		if(!class_exists('OSS_SNMP\SNMP')){
			return false;
		}

		$dev=New Device();
		$dev->DeviceID=$DeviceID;

		// Make sure this is a real device and has an IP set
		if(!$dev->GetDevice()){return false;}
		if($dev->PrimaryIP==""){return false;}

		// If the device doesn't have an SNMP community set, check and see if we have a global one
		$dev->SNMPCommunity=($dev->SNMPCommunity=="")?$config->ParameterArray["SNMPCommunity"]:$dev->SNMPCommunity;

		// Make this false faster
		$dev->SNMPCommunity=trim($dev->SNMPCommunity);
		if($dev->SNMPCommunity==""){return false;}

		// We've passed all the repeatable tests, return the device object for digging
		return $dev;
	}

	// Making an attempt at reducing the lines that I was constantly repeating at a cost of making this a little more convoluted.
	static private function OSS_SNMP_Lookup($dev,$snmplookup,$portid=null,$baseoid=null){
		// This is find out the name of the function that called this to make the error logging more descriptive
		$caller=debug_backtrace();
		$caller=$caller[1]['function'];

		// Since we don't really let the user specify the version right now here's a stop gap
		// Try the default version of 2c first
		$snmpHost=new OSS_SNMP\SNMP($dev->PrimaryIP,$dev->SNMPCommunity,$dev->SNMPVersion,$dev->v3SecurityLevel,$dev->v3AuthProtocol,$dev->v3AuthPassphrase,$dev->v3PrivProtocol,$dev->v3PrivPassphrase);
		try {
			$snmpHost->useSystem()->name();
		}catch (Exception $e){
			// That shit the bed so drop down to 1
			$snmpHost=new OSS_SNMP\SNMP($dev->PrimaryIP,$dev->SNMPCommunity,1);
		}

		$snmpresult=false;
		try {
			$snmpresult=(is_null($portid))?$snmpHost->useIface()->$snmplookup(true):$snmpHost->get($baseOID.".$portid");
		}catch (Exception $e){
			error_log("SwitchInfo::$caller($dev->DeviceID) ".$e->getMessage());
		}

		return $snmpresult;
	}

	static function getNumPorts($DeviceID) {
		if(!$dev=SwitchInfo::BasicTests($DeviceID)){
			return false;
		}

		return self::OSS_SNMP_Lookup($dev,"numberOfInterfaces");
	}

	static function findFirstPort( $DeviceID ) {
		if(!$dev=SwitchInfo::BasicTests($DeviceID)){
			return false;
		}
		
		$x=array();
		$portlist=self::OSS_SNMP_Lookup($dev,"names");
		foreach($portlist as $index => $portdesc ) {
			if ( preg_match( "/([0-9]\:|bond|\"[A-Z]|swp|eth|ix|em|e|Ethernet|g|Port-Channel|X|\/)[0]{0,}?[01]$|[01]$/", $portdesc )) {
				$x[$index] = $portdesc;
			} // Find lines that end with /1
		}
		// regex has failed us, return whatever mess we have
		if(count($x)==0){
			if(count($portlist)==0){
				$err_msg=__("I don't know what type of device this is but it did not return any ports whatsoever.  Do not try to report this as an error.");
			}else{
				$err_msg=__("First port detection failed, please report to VIO Intelligence DCIM developers");
			}
			$x=$portlist;
			$x=array("err"=>$err_msg)+$x;
		}
		return $x;
	}

	static function getPortNames($DeviceID,$portid=null){
		if(!$dev=SwitchInfo::BasicTests($DeviceID)){
			return false;
		}

		// We never did finish the discussion of if we should use the mib vs the oid
		$baseOID = ".1.3.6.1.2.1.31.1.1.1.1";
		$baseOID = "IF-MIB::ifName"; 

		$nameList=self::OSS_SNMP_Lookup($dev,"descriptions",$portid,$baseOID);

		if(is_array($nameList)){
			$saving=false;
			$newList=array();
			foreach($nameList as $i => $desc){
				if($i==$dev->FirstPortNum){$saving=true;}
				if($saving){$newList[sizeof($newList)+1]=$desc;}
				if(sizeof($newList)==$dev->Ports){break;}
			}
			$nameList=$newList;
		}

		return $nameList;
	}
	
	static function getPortStatus($DeviceID,$portid=null){
		if(!$dev=SwitchInfo::BasicTests($DeviceID)){
			return false;
		}

		// $baseOID = ".1.3.6.1.2.1.2.2.1.8.";
		$baseOID="IF-MIB::ifOperStatus"; // arguments for not using MIB?

		$statusList=self::OSS_SNMP_Lookup($dev,"operationStates",$portid,$baseOID);

		if(is_array($statusList)){
			$saving=false;
			$newList=array();
			foreach($statusList as $i => $status){
				if($i==$dev->FirstPortNum){$saving=true;}
				if($saving){$newList[sizeof($newList)+1]=$status;}
				if(sizeof($newList)==$dev->Ports){break;}
			}
			$statusList=$newList;
		}

		return $statusList;
	}
	
	static function getPortAlias($DeviceID,$portid=null){
		if(!$dev=SwitchInfo::BasicTests($DeviceID)){
			return false;
		}

		$baseOID=".1.3.6.1.2.1.31.1.1.1.18.";
		$baseOID="IF-MIB::ifAlias";

		$aliasList=self::OSS_SNMP_Lookup($dev,"aliases",$portid,$baseOID);
		
		if(is_array($aliasList)){
			$saving=false;
			$newList=array();
			foreach($aliasList as $i => $alias){
				if($i==$dev->FirstPortNum){$saving=true;}
				if($saving){$newList[sizeof($newList)+1]=$alias;}
				if(sizeof($newList)==$dev->Ports){break;}
			}
			$aliasList=$newList;
		}
		
		return $aliasList;	
	}
}
?>
