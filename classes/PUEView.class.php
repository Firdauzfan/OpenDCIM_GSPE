<?php
/*
	VIO Intelligence DCIM

	This is the main class library for the VIO Intelligence DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/

class PUEView {
	var $ViewID;
	var $Description;
	var $Category;
	var $TimePeriod;

	function prepare( $sql ) {
		global $dbh;

		return $dbh->prepare( $sql );
	}

	function lastInsertID( $sql ) {
		global $dbh;

		return $dbh->lastInsertId();
	}

	function makeSafe() {
		// Basic sanity checks and removal of Stupid User Tricks / Naughty User Tricks

		// This doesn't change the calculation method, but PUE can either be Category 1, 2, or 3
		$validCategories = array( "1", "2", "3" );

		// Instead of letting them pick ANY number of days, limit selection to 30, 90, 180, or 365
		$validTimes = array( "30", "90", "180", "365" );
		
		$this->ViewID = int($this->ViewID);
		$this->Description = sanitize($this->Description);
		$this->Category = (in_array( $this->Category, $validCategories ))?$this->Category:"1";
		$this->TimePeriod = (in_array( $this->TimePeriod, $validTimes ))?$this->TimePeriod:"180";
	}

	public function getView( $ViewID = false ) {
		global $dbh;

		if ( $viewID == false ) {
			$sql = "select * from fac_PUEView order by Description ASC";
			$args = array();
		} else {
			$sql = "select * from fac_PUEView where ViewID=:ViewID";
			$args = array( ":ViewID"=>$ViewID );
		}
		$st = $dbh->prepare( $sql );
		$st->setFetchMode( PDO::FETCH_CLASS, "PUEView" );
		$st->exececute( $args );

		$vList = array();
		while ( $row = $st->fetch() ) {
			$vList[] = $row;
		}

		return $vList;
	}

	function saveView() {
		$this->makeSafe();

		$sql = "insert into fac_PUEView set ViewID=:ViewID, Description=:Desc, Category=:Category, TimePeriod=:TimePeriod on duplicate key update Description=:Desc, Category=:Category, TimePeriod=:TimePeriod";

		$args = array( 	":ViewID" => $this->ViewID,
						":Desc" => $this->Description,
						":Category" => $this->Category,
						":TimePeriod" => $this->TimePeriod );

		$st = $this->prepare( $sql );
		$st->execute( $args );

		if ( $this->ViewID == 0 ) {
			$this->ViewID = $this->lastInsertID();
		}

		return;
	}

	function deleteView() {
		$this->makeSafe();

		if ( $this->ViewID < 1 ) {
			return false;
		}

		$sql = "delete from fac_PUEView where ViewID=:ViewID";
		$st = $this->prepare( $sql );
		$st->execute( array( ":ViewID" => $this->ViewID ));

		return;
	}
}
?>