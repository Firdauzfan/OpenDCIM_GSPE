<?php
$header=(!isset($header))?$config->ParameterArray["OrgName"]:$header;
$subheader=(!isset($subheader))?"":$subheader;
$version=$config->ParameterArray["Version"];


?>

<!-- Ganti Header dan menambahkan condition-condition untuk rights -->
<!-- Diubah Oleh Firdauz Fanani 23 April 2018 -->

    <link href="css/sm-core-css.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">

    <!-- "sm-blue" menu theme (optional, you can use your own CSS, too) -->
    <link href="css/sm-blue.css" rel="stylesheet" type="text/css" />

    <link href="css/demo.css" rel="stylesheet" type="text/css" />
    <link href="css/search.css" rel="stylesheet" type="text/css" />

                
                <div class="inputan">
                <a href="index.php"><img id="logovio" src="images/logo.png"></a>
                </div>
                 <nav id="main-nav" style="margin-bottom: -1; background-color: #00A2E9;">
                      <!-- Sample menu definition -->
                      <ul id="main-menu" class="sm sm-blue">
                        <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="index.php">Data Center</a>
                            <ul style="background-color: #212F39;">
                                <li><a href="container.php" style="color:white;">PT Graha Sumber Prima Elektronik</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" style="color: white;">Initialization <b class="caret"></b></a>

                            <ul style="background-color: #212F39;">


                                <li>
                                    <a href="#" style="color:white;">Infrastructure Management <b
                                            class="caret"></b></a>

                                    <ul style="background-color: #212F39;">
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
                                    <a href="#" style="color:white;">Template Management <b
                                            class="caret"></b></a>

                                    <ul style="background-color: #212F39;">
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
                                    <a href="#" style="color:white;">Power Management <b
                                            class="caret"></b></a>

                                    <ul style="background-color: #212F39;">
                                        <li><a href="power_panel.php" style="color:white;">Power Panels</a></li>
                                    </ul>
                                </li>

                                <li>
                                    <a href="#" style="color:white;">Path Connections <b
                                            class="caret"></b></a>

                                    <ul style="background-color: #212F39;">
                                        <li><a href="paths.php" style="color:white;">View Path Connection</a></li>
                                        <li><a href="pathmaker.php" style="color:white;">Make Path Connection</a></li>
                                    </ul>
                                </li>';
                                }
                                ?>
                                

                                <li><a href="project_mgr.php" style="color:white;">Project Catalog</a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="#" style="color: white;">Operation <b class="caret"></b></a>

                            <ul style="background-color: #212F39;">
                            	<?php
                                    if ($person->ContactAdmin ) {
                                    	echo '
                                    <li>
                                    <a href="#" style="color:white;">Issue Escalation <b
                                            class="caret"></b></a>

                                    <ul style="background-color: #212F39;">
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
                                    <a href="#" style="color:white;">Material Management <b
                                            class="caret"></b></a>

                                    <ul style="background-color: #212F39;">
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
                                    <a href="#" style="color:white;">Import Management <b
                                            class="caret"></b></a>

                                    <ul style="background-color: #212F39;">
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

                        <li>
                            <a href="#">Manage <b class="caret"></b></a>

                            <ul style="margin-left: 20px; background-color: #212F39;">

                            	<?php
                                    if ($person->ContactAdmin) {
                                    	echo '
                                    <li>
                                    <a href="#" style="color:white;"> Administration <b
                                            class="caret"></b></a>

                                    <ul style="background-color: #212F39;">
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

                        <li>
                            <a href="#">Reports <b class="caret"></b></a>

                            <ul style="background-color: #212F39;">

                                <li>
                                <a href="#" style="color:white;"> Asset Reports <b
                                        class="caret"></b></a>

                                <ul style="background-color: #212F39;">
                                    <li><a href="search_export.php" style="color:white;">Search/Export by Data Center</a></li>
                                    <li><a href="search_export_storage_room.php" style="color:white;">Storage Room Search/Export by Data Center</a></li>
                                    <li><a href="report_xml_CFD.php" style="color:white;">Export Data Center for CFD (XML)</a></li>
                                    <li><a href="report_contact.php" style="color:white;">Asset Report by Owner</a></li>
                                    <li><a href="report_asset.php" style="color:white;">Data Center Asset Report</a></li>
                                  <!--   <li><a href="report_asset_Excel.php" style="color:white;">Data Center Asset Report [Excel]</a></li> -->
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
                                <a href="#" style="color:white;"> Operational Reports <b
                                        class="caret"></b></a>

                                <ul style="background-color: #212F39;">
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
                                <a href="#" style="color:white;"> Auditing Reports <b
                                        class="caret"></b></a>

                                <ul style="background-color: #212F39;">
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

                        <li style="float: right; margin-right: 1%; margin-top: 0.3%">
                            <form id="formsearch" action="search.php" method="post">
                            <input type="hidden" name="key" value="label">
                            <?php
                                $attrList=DeviceCustomAttribute::GetDeviceCustomAttributeList(true);
                                echo'
                                            
                                <input class="searchname" id="searchnam" name="search" type="text" />
                                <div class="after"></div>
                                </form> ';

                                ?>

                        </li>
                    </ul>
                </nav>
              

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <!-- Include all compiled plugins (below), or include individual files as needed -->

		   
            <!-- <script type="text/javascript" src="scripts/jquery.js"></script> -->
            <!-- SmartMenus jQuery plugin -->
            <script type="text/javascript" src="scripts/jquery.smartmenus.js"></script>

            <!-- SmartMenus jQuery init -->
            <script type="text/javascript">
                $(function() {
                    $('#main-menu').smartmenus({
                        subMenusSubOffsetX: 1,
                        subMenusSubOffsetY: -8
                    });
                });
            </script>

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
  

<script type="text/javascript">

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
        if(document.readyState==="complete" && $('#datacenters .bullet').length==0){
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

