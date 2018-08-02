<?php
$header=(!isset($header))?$config->ParameterArray["OrgName"]:$header;
$subheader=(!isset($subheader))?"":$subheader;
$version=$config->ParameterArray["Version"];

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
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
<link href="css/demo.css" rel="stylesheet" type="text/css" />
<link href="css/search.css" rel="stylesheet" type="text/css" />
<link href="https://fonts.googleapis.com/css?family=PT+Sans+Narrow" rel="stylesheet"> 

<style type="text/css">
    #topNav ul { max-height:600px; overflow-y:auto; }
</style>

        <div id="header3" class="navbar navbar-default navbar-fixed-top" role="navigation" style="padding-bottom: 0px;">
            <div class="container" style="width: 100%;">
            <div class="inputan">
                <a href="index.php"><img id="logovio" src="images/vioDCIM.png"></a>
                </div>
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" style="margin-bottom: -1; background-color: #00A2E9; font-family: 'PT Sans Narrow', sans-serif;">
                    <ul class="nav navbar-nav">
                        <li style="padding:13px;min-width: 200px; border-radius:0px; -webkit-border-radius:0px;background-color: #00A2E9;">
                            <a href="index.php" style="text-align: center; font-size: 25px; color: white;"><i class="fas fa-home"></i> Home </b></a>
                        </li>

                        <li id="topNav" style="padding:13px;min-width: 200px; border-radius:0px; -webkit-border-radius:0px;background-color: #00A2E9;">
                            <?php 
                            if($_SERVER['PHP_SELF']=="/container_stats.php"){
                                $NamaTab="Data Center";
                            }elseif($_SERVER['PHP_SELF']=="/dc_stats.php"){
                                $NamaTab="Zone";
                            }elseif($_SERVER['PHP_SELF']=="/zone_stats.php"){
                                $NamaTab="Cabinet Row";
                            }elseif($_SERVER['PHP_SELF']=="/rowview.php"){
                                $NamaTab="Cabinet";
                            }else{
                                $NamaTab="Container";
                            }
                            ?>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="text-align: center; font-size: 25px; color: white;"><?php echo $NamaTab; ?> <b class="caret"></b></a>

                            <ul class="dropdown-menu" style="padding:13px;min-width: 200px;background-color: #212F39;">

                                     <?php
                                        //connection
                                     $conn = new mysqli('localhost', 'root', 'root', 'dcim');

                                    ?>

                                    <?php if($_SERVER['PHP_SELF']=="/container_stats.php"){

                                        $NamaTab="Data Center";
                                        $sql = "SELECT * FROM fac_DataCenter WHERE ContainerID=$c->ContainerID";
                                        $query = $conn->query($sql);
                 
                                        while($row = $query->fetch_assoc()){
                                            echo "
                                                <li class='dropdown-toggle' data-toggle='dropdown' style='font-size: 18px'><a href='dc_stats.php?dc=".$row['DataCenterID']."' style='color:white;'>".$row['Name']."</a></li>
                                            ";
                                        }
                                    }elseif($_SERVER['PHP_SELF']=="/dc_stats.php"){
                                        $sql = "SELECT * FROM fac_Zone WHERE DataCenterID=$dc->DataCenterID";
                                        $query = $conn->query($sql);

                                        while($row = $query->fetch_assoc()){
                                            echo "
                                                <li class='dropdown-toggle' data-toggle='dropdown' style='font-size: 18px'><a href='zone_stats.php?zone=".$row['ZoneID']."' style='color:white;'>".$row['Description']."</a></li>
                                            ";
                                        }

                                        echo "<li style='font-size: 18px'><a href='storageroom.php?dc=".$dc->DataCenterID."' style='color:white;''>Data Center Storage Room</a></li>";

                                    }elseif($_SERVER['PHP_SELF']=="/zone_stats.php"){
                                        $sql = "SELECT * FROM fac_CabRow WHERE ZoneID=$zone->ZoneID";
                                        $query = $conn->query($sql);

                                        while($row = $query->fetch_assoc()){
                                            echo "
                                                <li class='dropdown-toggle' data-toggle='dropdown' style='font-size: 18px'><a href='rowview.php?row=".$row['CabRowID']."' style='color:white;'>".$row['Name']."</a></li>
                                            ";
                                        }
                                    }elseif($_SERVER['PHP_SELF']=="/rowview.php"){
                                        $sql = "SELECT * FROM fac_Cabinet WHERE CabRowID=$cabrow->CabRowID";
                                        $query = $conn->query($sql);

                                        while($row = $query->fetch_assoc()){
                                            echo "
                                                <li class='dropdown-toggle' data-toggle='dropdown' style='font-size: 18px'><a href='cabnavigator.php?cabinetid=".$row['CabinetID']."' style='color:white;'>".$row['Location']."</a></li>
                                            ";
                                        }
                                    }else{
                                        $sql = "SELECT * FROM fac_Container";
                                        $query = $conn->query($sql);
                 
                                        while($row = $query->fetch_assoc()){
                                            echo "
                                                <li class='dropdown-toggle' data-toggle='dropdown' style='font-size: 18px'><a href='container_stats.php?container=".$row['ContainerID']."' style='color:white;'>".$row['Name']."</a></li>
                                            ";
                                        }
                                        echo "<li style='font-size: 18px'><a href='storageroom.php' style='color:white;''>General Storage Room</a></li>";
                                    }


                                    ?>

                                    
                            </ul>
                        </li>

                        <li style="padding:13px;min-width: 200px; border-radius:0px; -webkit-border-radius:0px;background-color: #00A2E9;">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="text-align: center; font-size: 25px; color: white;">Initialization <b class="caret"></b></a>

                            <ul class="dropdown-menu" style="background-color: #212F39;">


                                <li style="font-size: 18px">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">Infrastructure Management <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu" style="background-color: #212F39;">
                                    	<?php
                                    	if ( $person->SiteAdmin ) {
                                    		echo '
                                    	<li style="font-size: 18px"><a href="container.php" style="color:white;">Container</a></li>
                                    	<li style="font-size: 18px"><a href="datacenter.php" style="color:white;">Data Centers</a></li>
                                    	<li style="font-size: 18px"><a href="zone.php" style="color:white;">Zones</a></li>
                                    	<li style="font-size: 18px"><a href="cabrow.php" style="color:white;">Rows of Cabinet</a></li>';
                                    	}
                                    	?>
                                    	
                                    	<?php
                                    	if ( $person->WriteAccess ) {
                                    		echo '
                                    	<li style="font-size: 18px"><a href="cabinets.php" style="color:white;">Cabinets</a></li>
                                        <li style="font-size: 18px"><a href="ac.php" style="color:white;">PAC Data Center</a></li>
                                        <li style="font-size: 18px"><a href="facpowatt.php" style="color:white;">Facility Power Attributes</a></li>';
                                    	}
                                    	?>  

                                    	<?php
                                    	if ( $person->SiteAdmin ) {
                                    		echo '
                                    	<li style="font-size: 18px"><a href="image_management.php#drawings" style="color:white;">Facilities Image Management</a></li>';
                                    	}
                                    	?>                                          
                                        
                                    </ul>
                                </li>

                                <li style="font-size: 18px">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">Template Management <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu" style="background-color: #212F39;">
                                    	<?php
                                    	if ( $person->WriteAccess ) {
                                    		echo '
                                    	<li style="font-size: 18px"><a href="device_templates.php" style="color:white;">Device Template</a></li>
                                        <li style="font-size: 18px"><a href="image_management.php" style="color:white;">Device Image Management</a></li>';
                                    	}

                                    	if( $person->SiteAdmin ) {
                                    	echo '
                                    	<li style="font-size: 18px"><a href="device_manufacturers.php" style="color:white;">Manufacture</a></li>
                                        <li style="font-size: 18px"><a href="repository_sync_ui.php" style="color:white;">Repository Sync</a></li>';
                                    	}
                                    	?>
                                       
                                    </ul>
                                </li>

                                <?php
                                if ( $person->SiteAdmin ) {
                                	echo '
                                <li style="font-size: 18px">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">Power Management <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu" style="background-color: #212F39;">
                                        <li style="font-size: 18px"><a href="power_panel.php" style="color:white;">Power Panels</a></li>
                                    </ul>
                                </li>

                                <li style="font-size: 18px">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">Path Connections <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu" style="background-color: #212F39;">
                                        <li style="font-size: 18px"><a href="paths.php" style="color:white;">View Path Connection</a></li>
                                        <li style="font-size: 18px"><a href="pathmaker.php" style="color:white;">Make Path Connection</a></li>
                                    </ul>
                                </li>';
                                }
                                ?>
                                

                                <li style="font-size: 18px"><a href="project_mgr.php" style="color:white;">Project Catalog</a></li>
                            </ul>
                        </li>

                        <li style="padding:13px;min-width: 200px; border-radius:0px; -webkit-border-radius:0px;background-color: #00A2E9;">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="text-align: center;font-size: 25px; color: white;">Operation <b class="caret"></b></a>

                            <ul class="dropdown-menu" style="background-color: #212F39;">
                            	<?php
                                    if ($person->ContactAdmin ) {
                                    	echo '
                                    <li style="font-size: 18px">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"  style="color:white;">Issue Escalation <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu" style="background-color: #212F39;">
                                        <li style="font-size: 18px"><a href="timeperiods.php" style="color:white;">Time Period</a></li>
                                        <li style="font-size: 18px"><a href="escalations.php" style="color:white;">Escalation Rules</a></li>
                                    </ul>
                                	</li>';
                                    }
                                ?>

                               	<?php
                               	if( $person->SiteAdmin ) {
                               		echo '
                               	<li style="font-size: 18px">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">Material Management <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu" style="background-color: #212F39;">
                                    	<li style="font-size: 18px"><a href="supplybin.php" style="color:white;">Manage Supply Bins</a></li>
                                    	<li style="font-size: 18px"><a href="supplies.php" style="color:white;">Manage Supplies</a></li>
                                    	<li style="font-size: 18px"><a href="disposition.php" style="color:white;">Manage Disposal Methods</a></li>
                                    </ul>
                                </li>';
                               	}
                               	?>
                                

                                <?php
                                if ($person->BulkOperations) {
                                echo '
                                <li style="font-size: 18px">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;">Import Management <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu" style="background-color: #212F39;">
                                    	<li style="font-size: 18px"><a href="bulk_container.php" style="color:white;">Import Container/Data Center/Zone/Row</a></li>
                                    	<li style="font-size: 18px"><a href="bulk_users.php" style="color:white;">Import User Accounts</a></li>
                                    	<li style="font-size: 18px"><a href="bulk_departments.php" style="color:white;">Import Departments/Customers</a></li>
                                    	<li style="font-size: 18px"><a href="bulk_templates.php" style="color:white;">Import Device Templates</a></li>
                                    	<li style="font-size: 18px"><a href="bulk_cabinet.php" style="color:white;">Import Cabinets</a></li>
                                    	<li style="font-size: 18px"><a href="bulk_importer.php" style="color:white;">Import Devices</a></li>
                                    	<li style="font-size: 18px"><a href="bulk_network.php" style="color:white;">Import Network Connections</a></li>
                                    	<li style="font-size: 18px"><a href="bulk_power.php" style="color:white;">Import Power Connections</a></li>
                                    	<li style="font-size: 18px"><a href="bulk_moves.php" style="color:white;">Process Bulk Moves</a></li>
                                    </ul>
                                </li>';
                                }
                                ?>
                                
                                <?php
                                if ( $config->ParameterArray["RackRequests"] == "enabled" && $person->RackRequest ) {
									echo '<li style="font-size: 18px"><a href="rackrequest.php" style="color:white;">Rack Request Form</a></li>';
								}
								?>
                                
                            </ul>
                        </li>

                        <li style="padding:13px;min-width: 200px; border-radius:0px; -webkit-border-radius:0px;background-color: #00A2E9;">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="text-align: center;font-size: 25px; color: white;">Manage <b class="caret"></b></a>

                            <ul class="dropdown-menu" style="margin-left: 25px; background-color: #212F39;">

                            	<?php
                                    if ($person->ContactAdmin) {
                                    	echo '
                                    <li style="font-size: 18px">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;"> Administration <b
                                            class="caret"></b></a>

                                    <ul class="dropdown-menu" style="background-color: #212F39;">
                                        <li style="font-size: 18px"><a href="usermgr.php" style="color:white;">User</a></li>
                                        <li style="font-size: 18px"><a href="departments.php" style="color:white;">Department</a></li>
                                    </ul>
                                	</li>';
                                    }
                                ?>

                                <?php
                                if ( $person->SiteAdmin ) {
                                	echo '<li style="font-size: 18px"><a href="configuration.php" style="color:white;">Configuration</a></li>';
                                }
                                ?>
                                
                                
                            </ul>
                        </li>

                        <li style="padding:13px;min-width: 200px; border-radius:0px; -webkit-border-radius:0px;background-color: #00A2E9;" >
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="text-align: center;font-size: 25px; color: white;">Reports <b class="caret"></b></a>

                            <ul class="dropdown-menu" style="margin-left: 5px; background-color: #212F39;">

                                <li style="font-size: 18px">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;"> Asset Reports <b
                                        class="caret"></b></a>

                                <ul class="dropdown-menu" style="background-color: #212F39;">
                                    <li style="font-size: 18px"><a href="search_export.php" style="color:white;">Search/Export by Data Center</a></li>
                                    <li style="font-size: 18px"><a href="search_export_storage_room.php" style="color:white;">Storage Room Search/Export by Data Center</a></li>
                                    <li style="font-size: 18px"><a href="report_xml_CFD.php" style="color:white;">Export Data Center for CFD (XML)</a></li>
                                    <li style="font-size: 18px"><a href="report_contact.php" style="color:white;">Asset Report by Owner</a></li>
                                    <li style="font-size: 18px"><a href="report_asset.php" style="color:white;">Data Center Asset Report</a></li>
                                    <li style="font-size: 18px"><a href="report_asset_Excel.php" style="color:white;">Data Center Asset Report [Excel]</a></li>
                                    <li style="font-size: 18px"><a href="report_cost.php" style="color:white;">Data Center Asset Costing Report</a></li>
                                    <li style="font-size: 18px"><a href="report_projects.php" style="color:white;">Project Asset Report</a></li>
                                    <li style="font-size: 18px"><a href="report_aging.php" style="color:white;">Asset Aging Report</a></li>
                                    <li style="font-size: 18px"><a href="report_warranty.php" style="color:white;">Warranty Expiration Report</a></li>
                                    <li style="font-size: 18px"><a href="report_vm_by_department.php" style="color:white;">Virtual Machines by Department</a></li>
                                    <li style="font-size: 18px"><a href="report_network_map.php" style="color:white;">Network Map</a></li>
                                    <li style="font-size: 18px"><a href="report_vendor_model.php" style="color:white;">Vendor/Model Report</a></li>
                                </ul>
                                </li>

                                <li style="font-size: 18px">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;"> Operational Reports <b
                                        class="caret"></b></a>

                                <ul class="dropdown-menu" style="background-color: #212F39;">
                                    <li style="font-size: 18px"><a href="report_exception.php" style="color:white;">Data Exceptions Report</a></li>
                                    <li style="font-size: 18px"><a href="report_diverse_power_exceptions.php" style="color:white;">Diverse Power Exceptions Report</a></li>
                                    <li style="font-size: 18px"><a href="report_outage_simulator.php" style="color:white;">Simulated Power Outage Report</a></li>
                                    <li style="font-size: 18px"><a href="report_project_outage_simulator.php" style="color:white;">Project Power Outage Report</a></li>
                                    <li style="font-size: 18px"><a href="report_power_distribution.php" style="color:white;">Power Distribution by Data Center</a></li>
                                    <li style="font-size: 18px"><a href="report_power_utilization.php" style="color:white;">Server Tier Classification Report</a></li>
                                    <li style="font-size: 18px"><a href="report_panel_schedule.php" style="color:white;">Power Panel Schedule Report</a></li>
                                    <li style="font-size: 18px"><a href="report_cabinets.php" style="color:white;">Cabinet List</a></li>
                                    <li style="font-size: 18px"><a href="report_pac.php" style="color:white;">PAC List</a></li>
                                </ul>
                                </li>

                                <li style="font-size: 18px">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;"> Auditing Reports <b
                                        class="caret"></b></a>

                                <ul class="dropdown-menu" style="background-color: #212F39;">
                                    <li style="font-size: 18px"><a href="report_audit.php" style="color:white;">Cabinet Audit Logs</a></li>
                                    <li style="font-size: 18px"><a href="report_audit_frequency.php" style="color:white;">Cabinet Audit Frequency</a></li>
                                    <li style="font-size: 18px"><a href="report_surplus.php" style="color:white;">Surplus/Salvage Audit Report</a></li>
                                    <li style="font-size: 18px"><a href="report_supply_status.php" style="color:white;">Supplies Status Report</a></li>
                                    <li style="font-size: 18px"><a href="report_logging.php" style="color:white;">Actions Log</a></li>
                                </ul>
                                </li>

                                <li style="font-size: 18px"><a href="report_department.php" style="color:white;">Contact Reports</a></li>
                                
                            </ul>
                        </li>

                    </ul>

                    <ul style="background-color: #00A2E9;float:right;margin-right: 1%; margin-top: 1%">
                        <form id="formsearch" action="search.php" method="post">
                        <input type="hidden" name="key" value="label">
                        <?php
                            $attrList=DeviceCustomAttribute::GetDeviceCustomAttributeList(true);
                            echo'
                                        
                            <input class="searchname" id="searchnam" name="search" type="text" />
                            <div class="after"></div>
                            </form> ';

                            ?>

                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>


        <script src="scripts/navbar.js"></script>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <!-- Include all compiled plugins (below), or include individual files as needed -->
<!--         <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->

        <!-- Latest compiled and minified CSS -->
<!--         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"></link> -->

        <!-- Optional theme -->

        <!-- Latest compiled and minified JavaScript -->
        <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script> -->




        <script type="text/javascript">
    function addlookup(inputobj,lookuptype){
        // clear any existing autocompletes
        if(inputobj.hasClass('ui-autocomplete-input')){inputobj.autocomplete('destroy');}
        // clear out previous search arrows
        inputobj.next('.text-arrow').remove();
        // Position the arrow
        var inputpos=inputobj.position();
        var arrow=$('<div />').addClass('text-arrow');
        arrow.click(function(){
            inputobj.autocomplete("search", "");
        });
        // add the autocomplete
        inputobj.autocomplete({
            minLength: 0,
            delay: 600,
            autoFocus: true,
            source: function(req, add){
                $.getJSON('scripts/ajax_search.php?'+lookuptype, {q: req.term}, function(data){
                    var suggestions=[];
                    $.each(data, function(i,val){
                        suggestions.push(val);
                    });ey
                    add(suggestions);
                });
            },
            open: function(){
                $(this).autocomplete("widget").css({'width': inputobj.width()+6+'px'});
            }
        }).next().after(arrow);
        arrow.css({'top': inputpos.top+'px', 'left': inputpos.left+inputobj.width()-(arrow.width()/2)});
    }
    $('#advsrch, #searchadv ~ .ui-icon.ui-icon-close').click(function(){
        var here=$(this).position();
        $('#searchadv, #searchname').val('');
        $('#searchadv').parents('form').height(here.top).toggle('slide',200).removeClass('hide');
        if($('#searchadv').hasClass('ui-autocomplete-input')){$('#searchadv').autocomplete('destroy');}
        if($(this).text()=='<?php echo __("Advanced");?>'){$(this).text('<?php echo __("Basic");?>');$('#searchadv ~ select[name="key"]').trigger('change');}else{$(this).text('<?php echo __("Advanced");?>');}
    });
  </script>
  <script type="text/javascript" src="scripts/mktree.js"></script> 
  <script type="text/javascript" src="scripts/konami.js"></script> 
    <hr>
<?php


    function buildmenu($menu){
        $level='';
        foreach($menu as $key => $item){
            $level.="<li>";
            if(!is_array($item)){
                $level.="$item";
            }else{
                $level.="<a>$key</a><ul>";
                $level.=buildmenu($item);
                $level.="</ul>";
            }
            $level.="</li>";
        }
        return $level;
    }
    
    $menu=buildmenu(array_merge_recursive($rmenu,$rrmenu,$camenu,$wamenu,$samenu,$lmenu));
    
    print "
    <div style='margin-left:10px;'>
    <a href=\"index.php\">".__("Home")."</a>\n";

    $lang=GetValidTranslations();
    //strip any encoding info and keep just the country lang pair
    $locale=explode(".",$locale);
    $locale=$locale[0];
    echo '  <div class="langselect hide">
        <label for="language">Language</label>
        <select name="language" id="language" current="'.$locale.'">';
        foreach($lang as $cc => $translatedname){
            // This is for later. For now just display list
            //$selected=""; //
            if($locale==$cc){$selected=" selected";}else{$selected="";}
            print "\t\t\t<option value=\"$cc\"$selected>$translatedname</option>";
        }
    echo '      </select>
    </div>

    <div id="nav_placeholder"></div>';
    // Moved the navigation menu to an ajax load item   
?>
    </div>
<script type="text/javascript">

$("#sidebar .nav a").each(function(){
    var loc=window.location;
    if($(this).attr("href")=="<?php echo basename($_SERVER['SCRIPT_NAME']);?>" || $(this).attr("href")==loc.href.substr(loc.href.indexOf(loc.host)+loc.host.length+1)){
        $(this).addClass("active");
        $(this).parentsUntil("#ui-id-1","li").children('a:first-child').addClass("active");
    }
});
$("#sidebar .nav").menu();

$('#searchname').width($('#sidebar').innerWidth() - $('#searchname ~ button').outerWidth());
addlookup($('#searchname'),'name');
$('#searchadv ~ select[name="key"]').change(function(){
    addlookup($('#searchadv'),$(this).val())
}).outerHeight($('#searchadv').outerHeight()).outerWidth(157);

// Really long cabinet / zone / dc combinations are making the screen jump around.
// If they make this thing so big it's unusable, fuck em.
$('#sidebar > hr ~ div').css({'width':$('#sidebar > hr ~ ul').width()+'px','overflow':'hidden'});

function resize(){
    // Reset widths to make shrinking screens work better
    $('#header,div.main,div.page').css('width','auto');
    // This function will run each 500ms for 2.5s to account for slow loading content
    var count=0;
    subresize();
    var longload=setInterval(function(){
        subresize();
        if(count>4){
            clearInterval(longload);
            window.resized=true;
        }
        ++count;
    },500);

    function subresize(){
        // page width is calcuated different between ie, chrome, and ff
        $('#header').width(Math.floor($(window).outerWidth()-(16*3))); //16px = 1em per side padding
        var widesttab=0;
        // make all the tabs on the config page the same width
        $('#configtabs > ul ~ div').each(function(){
            widesttab=($(this).width()>widesttab)?$(this).width():widesttab;
        });
        $('#configtabs > ul ~ div').each(function(){
            $(this).width(widesttab);
        });

        if(typeof getCookie=='function' && getCookie("layout")=="Landscape"){
            // edge case where a ridiculously long device type can expand the field selector out too far
            var rdivwidth=$('div.right').outerWidth();
            $('div.right fieldset').each(function(){
                rdivwidth=($(this).outerWidth()>rdivwidth)?$(this).outerWidth():rdivwidth;
            });
            // offset for being centered
            rdivwidth=(rdivwidth>495)?(rdivwidth-495)+rdivwidth:rdivwidth;
        }else{
            rdivwidth=0;
        }

        var pnw=$('#pandn').outerWidth(),hw=$('#header').outerWidth(),maindiv=$('div.main').outerWidth(),
            sbw=$('#sidebar').outerWidth(),width,mw=$('div.left').outerWidth()+rdivwidth+20,
            main,cw=$('.main > .center').outerWidth();
        widesttab+=58;

        // find widths
        width=(cw>mw)?cw:mw;
        main=(pnw>width)?pnw:width; // Find the largest width of possible content in maindiv
        main+=12; // add in padding and borders
        width=((main+sbw)>hw)?main+sbw:hw; // find the widest point of the page

        // The math just isn't adding up across browsers and FUCK IE
        if((main+sbw)<width){ // page is larger than content expand main to fit
            $('#header').outerWidth(width);
            $('div.main').outerWidth(width-sbw-4); 
            $('div.page').outerWidth(width);
        }else{ // page is smaller than content expand the page to fit
            $('div.main').width(width-sbw-12); 
            $('#header').width(width+4);
            $('div.page').width(width+6);
        }

        // If the function MoveButtons is defined run it
        if(typeof movebuttons=='function'){
            movebuttons();
        }
    }
}
$(document).ready(function(){
    resize();
    // redraw the screen if the window size changes for some reason
    $(window).resize(function(){
        if(this.resizeTO){ clearTimeout(this.resizeTO);}
        this.resizeTO=setTimeout(function(){
            resize();
        }, 500);
    });
    $('#header').append($('.langselect'));
    $(".langselect").css({"right": "3px", "z-index": "99", "position": "absolute"}).removeClass('hide').appendTo("#header");
    $(".langselect").css({"bottom": $(".langselect").height()+"px"});
    $("#language").change(function(){
        $.ajax({
            type: 'POST',
            url: 'scripts/ajax_language.php',
            data: 'sl='+$("#language").val(),
            success: function(){
                // new cookie was set. reload the page for the translation.
                location.reload();
            }
        });
    });
<?php
    // No navigation menu if you're not logged in, yet
    if ( ! strpos( $_SERVER['SCRIPT_NAME'], "login" ) ) {
?>
    $.get('scripts/ajax_navmenu.php').done(function(data){
        $('#nav_placeholder').replaceWith(data);
        if(("#").readyState==="complete" && $('#datacenters .bullet').length==0){
            window.convertTrees();
        }
    });
<?php
    }
?>
});

</script>

<script type="text/javascript">
    var s = $('.searchname'),
    f  = $('#formsearch'),
    a = $('.after'),
        m = $('h4');

s.focus(function(){
  if( f.hasClass('open') ) return;
  f.addClass('in');
  setTimeout(function(){
    f.addClass('open');
    f.removeClass('in');
  }, 1300);
});

a.on('click', function(e){
  e.preventDefault();
  if( !f.hasClass('open') ) return;
   s.val('');
  f.addClass('close');
  f.removeClass('open');
  setTimeout(function(){
    f.removeClass('close');
  }, 1300);
})

</script>
