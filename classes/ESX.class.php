<?php
/*
	VIO Intelligence DCIM

	This is the main class library for the VIO Intelligence DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/

class ESX {
	/*	ESX:	Class that contains methods, only.

				Originally handled methods and properties related to VMs but 
				when ProxMox support was added this was broken out and the
				VM.class.php section has all of the properties.
	*/

	static function EnumerateVMs($d,$debug=false){
		if(!$dev=Device::BasicTests($d->DeviceID)){
			// This device failed the basic tests
			return false;
		}

		$namesList=Device::OSS_SNMP_Walk($dev,null,".1.3.6.1.4.1.6876.2.1.1.2");
		$statesList=Device::OSS_SNMP_Walk($dev,null,".1.3.6.1.4.1.6876.2.1.1.6");

		$vmList=array();

		if(is_array($namesList) && count($namesList)>0 && count($namesList)==count($statesList)){
			$tempList=array_combine($namesList,$statesList);
		}else{
			$tempList=array();
		}

		if(count($tempList)){
			if($debug){
				printf("\t%d VMs found\n", count($tempList));
			}

			foreach($tempList as $name => $state){
				$vm=new VM();
				$vm->DeviceID=$dev->DeviceID;
				$vm->LastUpdated=date( 'Y-m-d H:i:s' );
				$vm->vmID=count($vmList);
				$vm->vmName=trim(str_replace('"','',@end(explode(":",$name))));
				$vm->vmState=trim(str_replace('"','',@end(explode(":",$state))));
				$vmList[]=$vm;
			}
		}

		return $vmList;
	}
  
	function UpdateInventory($debug=false){
		$dev=new Device();

		$devList=$dev->GetESXDevices();

		foreach($devList as $esxDev){
			if($debug){
				print "Querying host $esxDev->Label @ $esxDev->PrimaryIP...\n";
			}

			if ( $esxDev->SNMPFailureCount < 3 ) {
				$vmList = ESX::RefreshInventory( $esxDev, $debug );
			}

			if($debug){
				print_r($vmList);
			}
		}
	}
  
	static function RefreshInventory( $ESXDevice, $debug = false ) {
		global $dbh;

		$dev = new Device();
		if ( is_object( $ESXDevice ) ) {
			$dev->DeviceID = $ESXDevice->DeviceID;
		} else {
			$dev->DeviceID = $ESXDevice;
		}
		$dev->GetDevice();
		
		$search = $dbh->prepare( "select * from fac_VMInventory where vmName=:vmName" );
		$update = $dbh->prepare( "update fac_VMInventory set DeviceID=:DeviceID, LastUpdated=:LastUpdated, vmID=:vmID, vmState=:vmState where vmName=:vmName" );
		$insert = $dbh->prepare( "insert into fac_VMInventory set DeviceID=:DeviceID, LastUpdated=:LastUpdated, vmID=:vmID, vmState=:vmState, vmName=:vmName" );
		
		$vmList = ESX::EnumerateVMs( $dev, $debug );
		if ( count( $vmList ) > 0 ) {
			foreach( $vmList as $vm ) {
				$search->execute( array( ":vmName"=>$vm->vmName ) );
				
				$parameters = array( ":DeviceID"=>$vm->DeviceID, ":LastUpdated"=>$vm->LastUpdated, ":vmID"=>$vm->vmID, ":vmState"=>$vm->vmState, ":vmName"=>$vm->vmName );

				if ( $search->rowCount() > 0 ) {
					$update->execute( $parameters );
					if ( $debug )
						error_log( "Updating existing VM '" . $vm->vmName . "'in inventory." );
				} else {
					$insert->execute( $parameters );
					if ( $debug ) 
						error_log( "Adding new VM '" . $vm->vmName . "'to inventory." );
				}
			}
		}
		
		return $vmList;
	}

}
?>
