<?php
/*
	VIO Intelligence DCIM

	This is the main class library for the VIO Intelligence DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/

class PowerConnection {
	/* PowerConnection:		A mapping of power strip (PDU) ports to the devices connected to them.
							Devices are limited to those within the same cabinet as the power strip,
							as connecting power across cabinets is not just a BAD PRACTICE, it's
							outright idiotic, except in temporary situations.
	*/
	
	var $PDUID;
	var $PDUPosition;
	var $DeviceID;
	var $DeviceConnNumber;

	private function MakeSafe(){
		$this->PDUID=intval($this->PDUID);
		$this->PDUPosition=sanitize($this->PDUPosition);
		$this->DeviceID=intval($this->DeviceID);
		$this->DeviceConnNumber=intval($this->DeviceConnNumber);
	}

	private function MakeDisplay(){
		$this->PDUPosition=stripslashes($this->PDUPosition);
	}

	static function RowToObject($row){
		$conn=new PowerConnection;
		$conn->PDUID=$row["PDUID"];
		$conn->PDUPosition=$row["PDUPosition"];
		$conn->DeviceID=$row["DeviceID"];
		$conn->DeviceConnNumber=$row["DeviceConnNumber"];
		$conn->MakeDisplay();

		return $conn;
	}

	function CanWrite(){
		global $person;
		// check rights
		$write=false;

			// check for an existing device
		$tmpconn=new PowerConnection();
		foreach($this as $prop => $value){
			$tmpconn->$prop=$value;
		}
		$tmpconn->GetPDUConnectionByPosition();
		$dev=new Device();
		$dev->DeviceID=$tmpconn->DeviceID;
		$dev->GetDevice();
		$write=($dev->Rights=="Write")?true:$write;

			// check for new device
		$dev->DeviceID=$this->DeviceID;
		$dev->GetDevice();
		$write=($dev->Rights=="Write")?true:$write;

			// check for rack ownership
		$pdu=new PowerDistribution();
		$pdu->PDUID=$this->PDUID;
		$pdu->GetPDU();
		$cab=new Cabinet();
		$cab->CabinetID=$pdu->CabinetID;
		$cab->GetCabinet();
		$write=($person->canWrite($cab->AssignedTo))?true:$write;

		return $write;
	}

	function CreateConnection(){
		global $dbh;

		$this->MakeSafe();

		$sql="INSERT INTO fac_PowerConnection SET DeviceID=$this->DeviceID, 
			DeviceConnNumber=$this->DeviceConnNumber, PDUID=$this->PDUID, 
			PDUPosition=\"$this->PDUPosition\" ON DUPLICATE KEY UPDATE DeviceID=$this->DeviceID,
			DeviceConnNumber=$this->DeviceConnNumber;";

		if($this->CanWrite()){
			if($dbh->query($sql)){
				(class_exists('LogActions'))?LogActions::LogThis($this):'';
				return true;
			}
		}
		return false;
	}
	
	function DeleteConnections(){
		/*
		 * This function is called when deleting a device, and will remove 
		 * ALL connections for the specified device.
		 */
		global $dbh;

		$this->MakeSafe();
		$sql="DELETE FROM fac_PowerConnection WHERE DeviceID=$this->DeviceID;";

		if($this->CanWrite()){
			if($dbh->exec($sql)){
				(class_exists('LogActions'))?LogActions::LogThis($this):'';
				return true;
			}
		}
		return false;
	}
	
	function RemoveConnection(){
		/*
		 * This function is called when removing a single connection, 
		 * specified by the unique combination of PDU ID and PDU Position.
		 */
		global $dbh;

		$this->MakeSafe();
		$sql="DELETE FROM fac_PowerConnection WHERE PDUID=$this->PDUID AND 
			PDUPosition=\"$this->PDUPosition\";";

		if($this->CanWrite()){
			if($dbh->exec($sql)){
				(class_exists('LogActions'))?LogActions::LogThis($this):'';
				return true;
			}
		}
		return false;
	}

	function GetPDUConnectionByPosition(){
		global $dbh;

		$this->MakeSafe();
		$sql="SELECT * FROM fac_PowerConnection WHERE PDUID=$this->PDUID AND 
			PDUPosition=\"$this->PDUPosition\";";
    
		if($row=$dbh->query($sql)->fetch()){
			foreach(PowerConnection::RowToObject($row) as $prop => $value){
				$this->$prop=$value;
			}
			return true;
		}
		return false;
	}
  
	function GetConnectionsByPDU(){
		global $dbh;

		$this->MakeSafe();
		$sql="SELECT * FROM fac_PowerConnection WHERE PDUID=$this->PDUID ORDER BY 
			PDUPosition;";

		$connList=array();
		foreach($dbh->query($sql) as $row){
			$connList[$row["PDUPosition"]]=PowerConnection::RowToObject($row);
		}
		return $connList;
	}
  
	function GetConnectionsByDevice(){
		global $dbh;

		$this->MakeSafe();
    	$sql="SELECT * FROM fac_PowerConnection WHERE DeviceID=$this->DeviceID ORDER BY DeviceConnnumber ASC, PDUID, PDUPosition";

		$connList=array();
		foreach($dbh->query($sql) as $row){
			$connList[]=PowerConnection::RowToObject($row);
		}
		return $connList;
	}    
}
?>
