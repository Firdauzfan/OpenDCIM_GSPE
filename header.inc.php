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
                    <a href="index.php"><img src="images/logo.png" style="margin-left: -9px;margin-right: 12px"></a>
                </div>
                <div class="collapse navbar-collapse" style="margin-bottom: -1">
                    <ul class="nav navbar-nav">
                        <li style="padding:13px;width: 200px">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="text-align: center; font-size: 20px">Initialization <b class="caret"></b></a>

                            <ul class="dropdown-menu">


                                <li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Infrastructure Management <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu">
                                    	<?php
                                    	if ( $person->SiteAdmin ) {
                                    		echo '
                                    	<li><a href="container.php">Container</a></li>
                                    	<li><a href="datacenter.php">Data Centers</a></li>
                                    	<li><a href="zone.php">Zones</a></li>
                                    	<li><a href="cabrow.php">Rows of Cabinet</a></li>';
                                    	}
                                    	?>
                                    	
                                    	<?php
                                    	if ( $person->WriteAccess ) {
                                    		echo '
                                    	<li><a href="cabinets.php">Cabinets</a></li>';
                                    	}
                                    	?>  

                                    	<?php
                                    	if ( $person->SiteAdmin ) {
                                    		echo '
                                    	<li><a href="image_management.php#drawings">Facilities Image Management</a></li>';
                                    	}
                                    	?>                                          
                                        
                                    </ul>
                                </li>

                                <li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Template Management <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu">
                                    	<?php
                                    	if ( $person->WriteAccess ) {
                                    		echo '
                                    	<li><a href="device_templates.php">Device Template</a></li>
                                        <li><a href="image_management.php">Device Image Management</a></li>';
                                    	}

                                    	if( $person->SiteAdmin ) {
                                    	echo '
                                    	<li><a href="device_manufacturers.php">Manufacture</a></li>
                                        <li><a href="repository_sync_ui.php">Repository Sync</a></li>';
                                    	}
                                    	?>
                                       
                                    </ul>
                                </li>

                                <?php
                                if ( $person->SiteAdmin ) {
                                	echo '
                                <li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Power Management <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu">
                                        <li><a href="power_panel.php">Power Panels</a></li>
                                    </ul>
                                </li>

                                <li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Path Connections <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu">
                                        <li><a href="paths.php">View Path Connection</a></li>
                                        <li><a href="pathmaker.php">Make Path Connection</a></li>
                                    </ul>
                                </li>';
                                }
                                ?>
                                

                                <li><a href="project_mgr.php">Project Catalog</a></li>
                            </ul>
                        </li>

                        <li style="padding:13px;width: 200px">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="text-align: center;font-size: 20px">Operation <b class="caret"></b></a>

                            <ul class="dropdown-menu">
                            	<?php
                                    if ($person->ContactAdmin ) {
                                    	echo '
                                    <li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Issue Escalation <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu">
                                        <li><a href="timeperiods.php">Time Period</a></li>
                                        <li><a href="escalations.php">Escalation Rules</a></li>
                                    </ul>
                                	</li>';
                                    }
                                ?>

                               	<?php
                               	if( $person->SiteAdmin ) {
                               		echo '
                               	<li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Material Management <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu">
                                    	<li><a href="supplybin.php">Manage Supply Bins</a></li>
                                    	<li><a href="supplies.php">Manage Supplies</a></li>
                                    	<li><a href="disposition.php">Manage Disposal Methods</a></li>
                                    </ul>
                                </li>';
                               	}
                               	?>
                                

                                <?php
                                if ($person->BulkOperations) {
                                echo '
                                <li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Import Management <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu">
                                    	<li><a href="bulk_container.php">Import Container/Data Center/Zone/Row</a></li>
                                    	<li><a href="bulk_users.php">Import User Accounts</a></li>
                                    	<li><a href="bulk_departments.php">Import Departments/Customers</a></li>
                                    	<li><a href="bulk_templates.php">Import Device Templates</a></li>
                                    	<li><a href="bulk_cabinet.php">Import Cabinets</a></li>
                                    	<li><a href="bulk_importer.php">Import Devices</a></li>
                                    	<li><a href="bulk_network.php">Import Network Connections</a></li>
                                    	<li><a href="bulk_power.php">Import Power Connections</a></li>
                                    	<li><a href="bulk_moves.php">Process Bulk Moves</a></li>
                                    </ul>
                                </li>';
                                }
                                ?>
                                
                                <?php
                                if ( $config->ParameterArray["RackRequests"] == "enabled" && $person->RackRequest ) {
									echo '<li><a href="rackrequest.php">Rack Request Form</a></li>';
								}
								?>
                                
                            </ul>
                        </li>

                        <li style="padding:13px;width: 200px">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="text-align: center;font-size: 20px">Manage <b class="caret"></b></a>

                            <ul class="dropdown-menu" style="margin-left: 20px">

                            	<?php
                                    if ($person->ContactAdmin) {
                                    	echo '
                                    <li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Administration <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu">
                                        <li><a href="usermgr.php">User</a></li>
                                        <li><a href="departments.php">Department</a></li>
                                    </ul>
                                	</li>';
                                    }
                                ?>

                                <?php
                                if ( $person->SiteAdmin ) {
                                	echo '<li><a href="configuration.php">Configuration</a></li>';
                                }
                                ?>
                                
                                
                            </ul>
                        </li>

                        <li style="padding:13px;width: 200px">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="text-align: center;font-size: 20px">Reports <b class="caret"></b></a>

                            <ul class="dropdown-menu" style="margin-left: 5px">

                                <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Asset Reports <b
                                        class="caret"></b></a>

                                <ul class="dropdown-menu">
                                    <li><a href="search_export.php">Search/Export by Data Center</a></li>
                                    <li><a href="search_export_storage_room.php">Storage Room Search/Export by Data Center</a></li>
                                    <li><a href="report_xml_CFD.php">Export Data Center for CFD (XML)</a></li>
                                    <li><a href="report_contact.php">Asset Report by Owner</a></li>
                                    <li><a href="report_asset.php">Data Center Asset Report</a></li>
                                    <li><a href="report_asset_Excel.php">Data Center Asset Report [Excel]</a></li>
                                    <li><a href="report_cost.php">Data Center Asset Costing Report</a></li>
                                    <li><a href="report_projects.php">Project Asset Report</a></li>
                                    <li><a href="report_aging.php">Asset Aging Report</a></li>
                                    <li><a href="report_warranty.php">Warranty Expiration Report</a></li>
                                    <li><a href="report_vm_by_department.php">Virtual Machines by Department</a></li>
                                    <li><a href="report_network_map.php">Network Map</a></li>
                                    <li><a href="report_vendor_model.php">Vendor/Model Report</a></li>
                                </ul>
                                </li>

                                <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Operational Reports <b
                                        class="caret"></b></a>

                                <ul class="dropdown-menu">
                                    <li><a href="report_exception.php">Data Exceptions Report</a></li>
                                    <li><a href="report_diverse_power_exceptions.php">Diverse Power Exceptions Report</a></li>
                                    <li><a href="report_outage_simulator.php">Simulated Power Outage Report</a></li>
                                    <li><a href="report_project_outage_simulator.php">Project Power Outage Report</a></li>
                                    <li><a href="report_power_distribution.php">Power Distribution by Data Center</a></li>
                                    <li><a href="report_power_utilization.php">Server Tier Classification Report</a></li>
                                    <li><a href="report_panel_schedule.php">Power Panel Schedule Report</a></li>
                                    <li><a href="report_cabinets.php">Cabinet List</a></li>
                                </ul>
                                </li>

                                <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Auditing Reports <b
                                        class="caret"></b></a>

                                <ul class="dropdown-menu">
                                    <li><a href="report_audit.php">Cabinet Audit Logs</a></li>
                                    <li><a href="report_audit_frequency.php">Cabinet Audit Frequency</a></li>
                                    <li><a href="report_surplus.php">Surplus/Salvage Audit Report</a></li>
                                    <li><a href="report_supply_status.php">Supplies Status Report</a></li>
                                    <li><a href="report_logging.php">Actions Log</a></li>
                                </ul>
                                </li>

                                <li><a href="report_department.php">Contact Reports</a></li>
                                
                            </ul>
                        </li>
                    </ul>

                </div><!--/.nav-collapse -->
            </div>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <!-- Include all compiled plugins (below), or include individual files as needed -->

		<script src="scripts/navbar.js"></script>