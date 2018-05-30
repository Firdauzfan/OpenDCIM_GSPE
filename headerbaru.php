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

        <div class="navbar navbar-default navbar-fixed-top" role="navigation" style="padding-bottom: 15px;">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">VIO</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Initialization <b class="caret"></b></a>

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

                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Operation <b class="caret"></b></a>

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

                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Manage <b class="caret"></b></a>

                            <ul class="dropdown-menu">

                            	<?php
                                    if ($person->ContactAdmin) {
                                    	echo '
                                    <li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Administration <b
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
                                
                                <li><a href="reports.php">Reports</a></li>
                            </ul>
                        </li>
                    </ul>

                </div><!--/.nav-collapse -->
            </div>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <!-- Include all compiled plugins (below), or include individual files as needed -->

		<script src="scripts/navbar.js"></script>