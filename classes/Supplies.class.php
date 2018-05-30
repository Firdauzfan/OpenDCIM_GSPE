<?php
/*
	VIO Intelligence DCIM

	This is the main class library for the VIO Intelligence DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/
class Supplies {
	var $SupplyID;
	var $PartNum;
	var $PartName;
	var $MinQty;
	var $MaxQty;

	function MakeSafe(){
		$this->SupplyID=intval($this->SupplyID);
		$this->PartNum=sanitize($this->PartNum);
		$this->PartName=sanitize($this->PartName);
		$this->MinQty=intval($this->MinQty);
		$this->MaxQty=intval($this->MaxQty);
	}

	function MakeDisplay(){
		$this->PartNum=stripslashes($this->PartNum);
		$this->PartName=stripslashes($this->PartName);
	}

	static function RowToObject($row){
		$supply=new Supplies();
		$supply->SupplyID=$row['SupplyID'];
		$supply->PartNum=$row['PartNum'];
		$supply->PartName=$row['PartName'];
		$supply->MinQty=$row['MinQty'];
		$supply->MaxQty=$row['MaxQty'];
		$supply->MakeDisplay();

		return $supply;
	}
	
	function query($sql){
		global $dbh;
		return $dbh->query($sql);
	}
	
	function exec($sql){
		global $dbh;
		return $dbh->exec($sql);
	}
	
	function CreateSupplies(){
		global $dbh;

		$sql="INSERT INTO fac_Supplies SET PartNum=\"$this->PartNum\", 
			PartName=\"$this->PartName\", MinQty=$this->MinQty, MaxQty=$this->MaxQty;";

		if(!$this->exec($sql)){
			return false;
		}else{
			$this->SupplyID=$dbh->lastInsertID();
			$this->MakeDisplay();
			return true;
		}
	}
	
	function GetSupplies(){
		$this->MakeSafe();

		$sql="SELECT * FROM fac_Supplies WHERE SupplyID=$this->SupplyID;";
		if($row=$this->query($sql)->fetch()){
			foreach(Supplies::RowToObject($row) as $prop => $value){
				$this->$prop=$value;
			}
			return true;
		}else{
			return false;
		}
	}
	
	static function GetSupplyCount( $SupplyID ) {
		global $dbh;
		
		$sql = "select sum(Count) as TotalQty from fac_BinContents where SupplyID=" . intval( $SupplyID );
		
		if ( $row=$dbh->query($sql)->fetch()) {
			return $row["TotalQty"];
		} else {
			return 0;
		}
	}
	
	function GetSuppliesList($indexbyid=false){
		$sql="SELECT * FROM fac_Supplies ORDER BY PartNum ASC;";
		
		$supplyList=array();
		foreach($this->query($sql) as $row){
			$index=($indexbyid)?$row['SupplyID']:$row['PartNum'];
			$supplyList[$index]=Supplies::RowToObject($row);
		}
		
		return $supplyList;
	}
	
	function UpdateSupplies(){
		$this->MakeSafe();

		$sql="UPDATE fac_Supplies SET PartNum=\"$this->PartNum\", 
			PartName=\"$this->PartName\", MinQty=$this->MinQty, MaxQty=$this->MaxQty WHERE 
			SupplyID=$this->SupplyID;";

		return $this->query($sql);
	}
	
	function DeleteSupplies(){
		$this->MakeSafe();

		$sql="DELETE FROM fac_Supplies WHERE SupplyID=$this->SupplyID;";

		return $this->exec($sql);
	}
}
?>
