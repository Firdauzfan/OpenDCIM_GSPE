<?php
/*
	VIO Intelligence DCIM

	This is the main class library for the VIO Intelligence DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/

class DataMechanism {
	var $MechanismID;
	var $Name;
	var $MechanismType;
	var $MetaData;		// This will be a separate table in the db, not a field in the main table
}

?>