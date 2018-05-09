<?php
/*
	GSPE DCIM

	This is the main class library for the GSPE DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT Graha Sumber Prima Elektronik
*/

class DataMechanism {
	var $MechanismID;
	var $Name;
	var $MechanismType;
	var $MetaData;		// This will be a separate table in the db, not a field in the main table
}

?>