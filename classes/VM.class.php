<?php
/*
	VIO Intelligence DCIM

	This is the main class library for the VIO Intelligence DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/

class VM {
	/*	VM:	Originally called ESX since VMWare ESX was the only supported hypervisor for
			remote SNMP queries.  However, ProxMox support has since been added, and it
			is anticipated that more may be added, such as remote data center (cloud) services.

			Methods that are generic to virtualization are in this class, while those specific
			to VWare ESX remain in the ESX class.
	*/
	var $VMIndex;
	var $DeviceID;
	var $LastUpdated;
	var $vmID;
	var $vmName;
	var $vmState;
	var $Owner;
	var $PrimaryContact;
  
	static function RowToObject($dbRow){
		/*
		 * Generic function that will take any row returned from the fac_VMInventory
		 * table and convert it to an object for use in array or other
		 */

		$vm=new VM();
		$vm->VMIndex=$dbRow["VMIndex"];
		$vm->DeviceID=$dbRow["DeviceID"];
		$vm->LastUpdated=$dbRow["LastUpdated"];
		$vm->vmID=$dbRow["vmID"];
		$vm->vmName=$dbRow["vmName"];
		$vm->vmState=$dbRow["vmState"];
		$vm->Owner=$dbRow["Owner"];
		$vm->PrimaryContact=$dbRow["PrimaryContact"];

		return $vm;
	}

	function search($sql){
		global $dbh;

		$vmList=array();
		$vmCount=0;

		foreach($dbh->query($sql) as $row){
			$vmList[$vmCount]=VM::RowToObject($row);
			$vmCount++;
		}

		return $vmList;
	}
 
	function GetVMbyIndex() {
		global $dbh;

		$sql="SELECT * FROM fac_VMInventory WHERE VMIndex=$this->VMIndex;";

		if(!$vmRow=$dbh->query($sql)->fetch()){
			return false;
		}else{
			foreach(VM::RowToObject($vmRow) as $param => $value){
				$this->$param=$value;
			}
			return true;
		}
	}
  
	function UpdateVMOwner() {
		global $dbh;

		$sql="UPDATE fac_VMInventory SET Owner=$this->Owner, PrimaryContact=$this->PrimaryContact WHERE VMIndex=$this->VMIndex;";
		$dbh->query($sql);
	} 
  
	function GetInventory() {
		$sql="SELECT * FROM fac_VMInventory ORDER BY DeviceID, vmName;";
		return $this->search($sql);
	}
  
	function GetDeviceInventory() {
		$sql="SELECT * FROM fac_VMInventory WHERE DeviceID=$this->DeviceID ORDER BY vmName;";
		return $this->search($sql);
	}
  
	function GetVMListbyOwner() {
		$sql="SELECT * FROM fac_VMInventory WHERE Owner=$this->Owner ORDER BY DeviceID, vmName;";
		return $this->search($sql);
	}
  
	function SearchByVMName() {
		$sql="SELECT * FROM fac_VMInventory WHERE vmName like \"%$this->vmName%\";";
		return $this->search($sql);
	}
  
	function GetOrphanVMList(){
		$sql="SELECT * FROM fac_VMInventory WHERE Owner=0;"; 
		return $this->search($sql);
	}

	function GetExpiredVMList($numDays){
		// I don't think this is standard SQL and will need to be looked at closer
		$sql="SELECT * FROM fac_VMInventory WHERE to_days(now())-to_days(LastUpdated)>$numDays;"; 
		return $this->search($sql);
	}
  
	function ExpireVMs($numDays){
		global $dbh;

		// Don't allow calls to expire EVERYTHING
		if($numDays >0){
			$sql="DELETE FROM fac_VMInventory WHERE to_days(now())-to_days(LastUpdated)>$numDays;";
			$dbh->query($sql);
		}
	}

}
?>
