<?php
$header=(!isset($header))?$config->ParameterArray["OrgName"]:$header;
$subheader=(!isset($subheader))?"":$subheader;
$version=$config->ParameterArray["Version"];

echo '
<div id="header">
	<span id="header1">',$header,'</span>
	<span id="header2">',$subheader,'</span>
	<span id="version">',$person->UserID,'/',$version,'</span>
</div>
';
?>

<!-- Ganti Header dan menambahkan condition-condition untuk rights -->
<!-- Diubah Oleh Firdauz Fanani 23 April 2018 -->

<style>
.navbar-template {
    padding: 40px 15px;

@media (min-width: 767px) {
.navbar-nav .dropdown-menu .caret {
transform: rotate(-90deg);
	}
}

</style>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/navbar.css" rel="stylesheet">
        <div id="header3" class="navbar navbar-default navbar-fixed-top" role="navigation" style="padding-bottom: 0px;">
            <div class="container" style="width: 2086px;">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="index.php"><img src="images/logovio.png" style="margin-left: -16px;margin-right: 12px"></a>
                </div>
                <div class="collapse navbar-collapse" style="margin-bottom: -1; background-color: #333333;">
                    <ul class="nav navbar-nav">
                        <li style="padding:13px;width: 200px; border-radius:0px; -webkit-border-radius:0px;">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="text-align: center; font-size: 20px; color: white;">Initialization <b class="caret"></b></a>

                            <ul class="dropdown-menu" style="background-color: #333333;">


                                <li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">Infrastructure Management <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu" style="background-color: #333333;">
                                    	<?php
                                    	if ( $person->SiteAdmin ) {
                                    		echo '
                                    	<li><a href="container.php" style="color:white;">Container</a></li>
                                    	<li><a href="datacenter.php" style="color:white;">Data Centers</a></li>
                                    	<li><a href="zone.php" style="color:white;">Zones</a></li>
                                    	<li><a href="cabrow.php" style="color:white;">Rows of Cabinet</a></li>';
                                    	}
                                    	?>
                                    	
                                    	<?php
                                    	if ( $person->WriteAccess ) {
                                    		echo '
                                    	<li><a href="cabinets.php" style="color:white;">Cabinets</a></li>
                                        <li><a href="ac.php" style="color:white;">PAC Data Center</a></li>
                                        <li><a href="facpowatt.php" style="color:white;">Facility Power Attributes</a></li>';
                                    	}
                                    	?>  

                                    	<?php
                                    	if ( $person->SiteAdmin ) {
                                    		echo '
                                    	<li><a href="image_management.php#drawings" style="color:white;">Facilities Image Management</a></li>';
                                    	}
                                    	?>                                          
                                        
                                    </ul>
                                </li>

                                <li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">Template Management <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu" style="background-color: #333333;">
                                    	<?php
                                    	if ( $person->WriteAccess ) {
                                    		echo '
                                    	<li><a href="device_templates.php" style="color:white;">Device Template</a></li>
                                        <li><a href="image_management.php" style="color:white;">Device Image Management</a></li>';
                                    	}

                                    	if( $person->SiteAdmin ) {
                                    	echo '
                                    	<li><a href="device_manufacturers.php" style="color:white;">Manufacture</a></li>
                                        <li><a href="repository_sync_ui.php" style="color:white;">Repository Sync</a></li>';
                                    	}
                                    	?>
                                       
                                    </ul>
                                </li>

                                <?php
                                if ( $person->SiteAdmin ) {
                                	echo '
                                <li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">Power Management <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu" style="background-color: #333333;">
                                        <li><a href="power_panel.php" style="color:white;">Power Panels</a></li>
                                    </ul>
                                </li>

                                <li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">Path Connections <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu" style="background-color: #333333;">
                                        <li><a href="paths.php" style="color:white;">View Path Connection</a></li>
                                        <li><a href="pathmaker.php" style="color:white;">Make Path Connection</a></li>
                                    </ul>
                                </li>';
                                }
                                ?>
                                

                                <li><a href="project_mgr.php" style="color:white;">Project Catalog</a></li>
                            </ul>
                        </li>

                        <li style="padding:13px;width: 200px; border-radius:0px; -webkit-border-radius:0px;">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="text-align: center;font-size: 20px; color: white;">Operation <b class="caret"></b></a>

                            <ul class="dropdown-menu" style="background-color: #333333;">
                            	<?php
                                    if ($person->ContactAdmin ) {
                                    	echo '
                                    <li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"  style="color:white;">Issue Escalation <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu" style="background-color: #333333;">
                                        <li><a href="timeperiods.php" style="color:white;">Time Period</a></li>
                                        <li><a href="escalations.php" style="color:white;">Escalation Rules</a></li>
                                    </ul>
                                	</li>';
                                    }
                                ?>

                               	<?php
                               	if( $person->SiteAdmin ) {
                               		echo '
                               	<li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">Material Management <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu" style="background-color: #333333;">
                                    	<li><a href="supplybin.php" style="color:white;">Manage Supply Bins</a></li>
                                    	<li><a href="supplies.php" style="color:white;">Manage Supplies</a></li>
                                    	<li><a href="disposition.php" style="color:white;">Manage Disposal Methods</a></li>
                                    </ul>
                                </li>';
                               	}
                               	?>
                                

                                <?php
                                if ($person->BulkOperations) {
                                echo '
                                <li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">Import Management <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu" style="background-color: #333333;">
                                    	<li><a href="bulk_container.php" style="color:white;">Import Container/Data Center/Zone/Row</a></li>
                                    	<li><a href="bulk_users.php" style="color:white;">Import User Accounts</a></li>
                                    	<li><a href="bulk_departments.php" style="color:white;">Import Departments/Customers</a></li>
                                    	<li><a href="bulk_templates.php" style="color:white;">Import Device Templates</a></li>
                                    	<li><a href="bulk_cabinet.php" style="color:white;">Import Cabinets</a></li>
                                    	<li><a href="bulk_importer.php" style="color:white;">Import Devices</a></li>
                                    	<li><a href="bulk_network.php" style="color:white;">Import Network Connections</a></li>
                                    	<li><a href="bulk_power.php" style="color:white;">Import Power Connections</a></li>
                                    	<li><a href="bulk_moves.php" style="color:white;">Process Bulk Moves</a></li>
                                    </ul>
                                </li>';
                                }
                                ?>
                                
                                <?php
                                if ( $config->ParameterArray["RackRequests"] == "enabled" && $person->RackRequest ) {
									echo '<li><a href="rackrequest.php" style="color:white;">Rack Request Form</a></li>';
								}
								?>
                                
                            </ul>
                        </li>

                        <li style="padding:13px;width: 200px; border-radius:0px; -webkit-border-radius:0px;">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="text-align: center;font-size: 20px; color: white;">Manage <b class="caret"></b></a>

                            <ul class="dropdown-menu" style="margin-left: 20px; background-color: #333333;">

                            	<?php
                                    if ($person->ContactAdmin) {
                                    	echo '
                                    <li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;"> Administration <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu" style="background-color: #333333;">
                                        <li><a href="usermgr.php" style="color:white;">User</a></li>
                                        <li><a href="departments.php" style="color:white;">Department</a></li>
                                    </ul>
                                	</li>';
                                    }
                                ?>

                                <?php
                                if ( $person->SiteAdmin ) {
                                	echo '<li><a href="configuration.php" style="color:white;">Configuration</a></li>';
                                }
                                ?>
                                
                                
                            </ul>
                        </li>

                        <li style="padding:13px;width: 200px; border-radius:0px; -webkit-border-radius:0px;" >
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="text-align: center;font-size: 20px; color: white;">Reports <b class="caret"></b></a>

                            <ul class="dropdown-menu" style="margin-left: 5px; background-color: #333333;">

                                <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;"> Asset Reports <b
                                        class="caret"></b></a>

                                <ul class="dropdown-menu" style="background-color: #333333;">
                                    <li><a href="search_export.php" style="color:white;">Search/Export by Data Center</a></li>
                                    <li><a href="search_export_storage_room.php" style="color:white;">Storage Room Search/Export by Data Center</a></li>
                                    <li><a href="report_xml_CFD.php" style="color:white;">Export Data Center for CFD (XML)</a></li>
                                    <li><a href="report_contact.php" style="color:white;">Asset Report by Owner</a></li>
                                    <li><a href="report_asset.php" style="color:white;">Data Center Asset Report</a></li>
                                    <li><a href="report_asset_Excel.php" style="color:white;">Data Center Asset Report [Excel]</a></li>
                                    <li><a href="report_cost.php" style="color:white;">Data Center Asset Costing Report</a></li>
                                    <li><a href="report_projects.php" style="color:white;">Project Asset Report</a></li>
                                    <li><a href="report_aging.php" style="color:white;">Asset Aging Report</a></li>
                                    <li><a href="report_warranty.php" style="color:white;">Warranty Expiration Report</a></li>
                                    <li><a href="report_vm_by_department.php" style="color:white;">Virtual Machines by Department</a></li>
                                    <li><a href="report_network_map.php" style="color:white;">Network Map</a></li>
                                    <li><a href="report_vendor_model.php" style="color:white;">Vendor/Model Report</a></li>
                                </ul>
                                </li>

                                <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;"> Operational Reports <b
                                        class="caret"></b></a>

                                <ul class="dropdown-menu" style="background-color: #333333;">
                                    <li><a href="report_exception.php" style="color:white;">Data Exceptions Report</a></li>
                                    <li><a href="report_diverse_power_exceptions.php" style="color:white;">Diverse Power Exceptions Report</a></li>
                                    <li><a href="report_outage_simulator.php" style="color:white;">Simulated Power Outage Report</a></li>
                                    <li><a href="report_project_outage_simulator.php" style="color:white;">Project Power Outage Report</a></li>
                                    <li><a href="report_power_distribution.php" style="color:white;">Power Distribution by Data Center</a></li>
                                    <li><a href="report_power_utilization.php" style="color:white;">Server Tier Classification Report</a></li>
                                    <li><a href="report_panel_schedule.php" style="color:white;">Power Panel Schedule Report</a></li>
                                    <li><a href="report_cabinets.php" style="color:white;">Cabinet List</a></li>
                                    <li><a href="report_pac.php" style="color:white;">PAC List</a></li>
                                </ul>
                                </li>

                                <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;"> Auditing Reports <b
                                        class="caret"></b></a>

                                <ul class="dropdown-menu" style="background-color: #333333;">
                                    <li><a href="report_audit.php" style="color:white;">Cabinet Audit Logs</a></li>
                                    <li><a href="report_audit_frequency.php" style="color:white;">Cabinet Audit Frequency</a></li>
                                    <li><a href="report_surplus.php" style="color:white;">Surplus/Salvage Audit Report</a></li>
                                    <li><a href="report_supply_status.php" style="color:white;">Supplies Status Report</a></li>
                                    <li><a href="report_logging.php" style="color:white;">Actions Log</a></li>
                                </ul>
                                </li>

                                <li><a href="report_department.php" style="color:white;">Contact Reports</a></li>
                                
                            </ul>
                        </li>
                    </ul>

                </div><!--/.nav-collapse -->
            </div>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <!-- Include all compiled plugins (below), or include individual files as needed -->

		<script src="scripts/navbar.js"></script>