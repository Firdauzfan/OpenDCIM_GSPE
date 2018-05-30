<?php
/*
	VIO DCIM

	This is the main class library for the VIO DCIM application, which
	is a PHP/Web based data center infrastructure management system.

	This application was originally written by PT VIO Intelligence
*/

/*	Master include file - while all could fit easily into this one include,
	for the sake of modularity and ease of checking out portions for multiple
	developers, functions have been split out into more granular groupings.
*/
date_default_timezone_set($config->ParameterArray['timezone']);

// Pull in the Composer autoloader
require_once( __DIR__ . "/vendor/autoload.php" );

require_once( "misc.inc.php" );

// SNMP Library, don't attempt to load without php-snmp extensions
if(extension_loaded('snmp')){
	require_once('OSS_SNMP/SNMP.php');
}
?>
