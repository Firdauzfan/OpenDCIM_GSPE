<?php
/*
	VIO Intelligence DCIM

	This is the main class library for the VIO Intelligence DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/

use ProxmoxVE\Credentials;
use ProxmoxVE\Proxmox;

class PMox {
	/*	ProxMox:	Class that contains methods, only, specific to ProxMox.

					All properties and methods that are generic to VMs are in VM.class.php
	*/

	static function EnumerateVMs($d,$debug=false){
		$vmList=array();

		// Establish credentials for this particular device

		$credentials = new Credentials( $d->PrimaryIP,
			$d->APIUsername,
			$d->APIPassword,
			$d->ProxMoxRealm,
			$d->APIPort );

		try {
			$proxmox = new Proxmox($credentials);
			$pveList = $proxmox->get('/nodes/' . $d->Label . '/qemu' );
		} 
		catch( Exception $e ) {
			error_log( "Unable to poll ProxMox for inventory.  DeviceID=" . $d->DeviceID );
			exit;
		}

		if ( sizeof( $pveList ) > 0 ) {
			foreach( $pveList["data"] as $pve ) { 
				$tmpVM = new VM;

				$tmpVM->DeviceID = $d->DeviceID;
				$tmpVM->LastUpdated = date( "Y-m-d H:i:s" );
				$tmpVM->vmID = $pve["vmid"];
				$tmpVM->vmName = $pve["name"];
				$tmpVM->vmState = $pve["status"];

				if ( $debug ) {
					error_log( "VM: " . $tmpVM->vmName . " added to device " . $d->DeviceID );
				}

				$vmList[] = $tmpVM;
			}
		}
		
		return $vmList;
	}
  
	function UpdateInventory($debug=false){
		$dev=new Device();

		$d = new Device;

		$d->Hypervisor = "ProxMox";
		$devList = $d->Search();

		foreach($devList as $pveDev){
			if($debug){
				print "Querying host $pveDev->Label @ $pveDev->PrimaryIP...\n";
			}

			if ( $pveDev->SNMPFailureCount < 3 ) {
				$vmList = PMox::RefreshInventory( $pveDev, $debug );
			}

			if($debug){
				print_r($vmList);
			}
		}
	}
  
	static function RefreshInventory( $pveDevice, $debug = false ) {
		global $dbh;
		global $config;

		$dev = new Device();
		if ( is_object( $pveDevice ) ) {
			$dev->DeviceID = $pveDevice->DeviceID;
		} else {
			$dev->DeviceID = $pveDevice;
		}
		$dev->GetDevice();
		
		$search = $dbh->prepare( "select * from fac_VMInventory where vmName=:vmName" );
		$update = $dbh->prepare( "update fac_VMInventory set DeviceID=:DeviceID, LastUpdated=:LastUpdated, vmID=:vmID, vmState=:vmState where vmName=:vmName" );
		$insert = $dbh->prepare( "insert into fac_VMInventory set DeviceID=:DeviceID, LastUpdated=:LastUpdated, vmID=:vmID, vmState=:vmState, vmName=:vmName" );
		
		$vmList = PMox::EnumerateVMs( $dev, $debug );
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

		$expire = "delete from fac_VMInventory where to_days(now())-to_days(LastUpdated)>" . intval( $config->ParameterArray['VMExpirationTime']);
		$dbh->query( $expire );
		
		return $vmList;
	}

}
?>
