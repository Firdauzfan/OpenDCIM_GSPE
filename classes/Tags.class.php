<?php
/*
	VIO Intelligence DCIM

	This is the main class library for the VIO Intelligence DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/

class Tags {
	var $TagID;
	var $TagName;

	//Add Create Tag Function
	static function CreateTag($TagName){
		global $dbh;

		if(!is_null($TagName)){
			$TagName=sanitize($TagName);
			$sql="INSERT INTO fac_Tags VALUES (NULL, '$TagName');";
			if(!$dbh->exec($sql)){
				return null;
			}else{
				return $dbh->lastInsertId();
			}
		}
		return null;
	}

	//Add Delete Tag Function

	static function FindID($TagName=null){
		global $dbh;

		if(!is_null($TagName)){
			$TagName=sanitize($TagName);
			// When searching, do a case insensitive search, but when adding you pay attention to case
			$sql="SELECT TagID FROM fac_Tags WHERE ucase(Name) = ucase('$TagName');";
			if($TagID=$dbh->query($sql)->fetchColumn()){
				return $TagID;
			}
		}else{
			//No tagname was supplied so kick back an array of all available TagIDs and Names
			return $this->FindAll();
		}
		//everything failed give them nothing
		return 0;
	}

	static function FindName($TagID=null){
		global $dbh;

		if(!is_null($TagID)){
			$TagID=intval($TagID);
			$sql="SELECT Name FROM fac_Tags WHERE TagID = $TagID;";
			if($TagName=$dbh->query($sql)->fetchColumn()){
				return $TagName;
			}
		}else{
			//No tagname was supplied so kick back an array of all available TagIDs and Names
			return $this->FindAll();
		}
		//everything failed give them nothing
		return 0;
	}

	static function FindAll(){
		global $dbh;

		$sql="SELECT * FROM fac_Tags order by Name ASC";

		$tagarray=array();
		foreach($dbh->query($sql) as $row){
			$tagarray[$row['TagID']]=$row['Name'];
		}
		return $tagarray;
	}

}
?>
