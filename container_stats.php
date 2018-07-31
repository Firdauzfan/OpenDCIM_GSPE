<?php
	require_once("db.inc.php");
	require_once("facilities.inc.php");

	$subheader=__("Data Center Statistics");

	if(!isset($_GET["container"])){
		// No soup for you.
		header('Location: '.redirect());
		exit;
	}

	$c=New Container();
	
	$c->ContainerID=$_GET["container"];
	$c->GetContainer();
	$cStats=$c->GetContainerStatistics();

?>
<!doctype html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  
  <title>VIO DCIM Data Center Information Management</title>
  <style type="text/css">
    .container2{width:100%;height:60%}
    .pane2{padding:0px 15px;background:#34495e;line-height:28px;color:#fff;z-index:10;position:absolute;top:20px;right:20px}
  </style>

  <link rel="stylesheet" href="css/inventory.php" type="text/css">
  <link rel="stylesheet" href="css/jquery-ui.css" type="text/css">
  <!--[if lte IE 8]>
    <link rel="stylesheet"  href="css/ie.css" type="text/css">
    <script src="scripts/excanvas.js"></script>
  <![endif]-->
  <script type="text/javascript" src="scripts/jquery.min.js"></script>
  <script type="text/javascript" src="scripts/jquery-ui.min.js"></script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/maptalks@0.40.3/dist/maptalks.css">
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/maptalks@0.40.3/dist/maptalks.min.js"></script>

  <script type="text/javascript" src="https://unpkg.com/three@0.84.0/build/three.min.js"></script>

  <script type="text/javascript" src="https://unpkg.com/maptalks.three/dist/maptalks.three.js"></script>
</head>
<body onload="loaddata();">
<?php include( 'header.inc.php' ); ?>
<div class="backgroundpage">
<div class="page1 dcstats" id="mapadjust">
<div class="makecenter">

<?php
// include( "sidebar.inc.php" );

if ( $config->ParameterArray["mUnits"] == "english" ) {
    $vol = __("Square Feet");
    $density = __("Watts per Square Foot");
} else {
    $vol = __("Square Meters");
    $density = __("Watts per Square Meter" );
}

echo '<div class="main" style="height:1300px;width:2000px">
<div class="center" style="margin-top:20px;"><div>
<div class="centermargin" id="dcstats">
<div class="table border">
  <div class="title">',$c->Name,'</div>
  <div>
	<div></div>
	<div>',__("Infrastructure"),'</div>
	<div>',__("Occupied"),'</div>
	<div>',__("Allocated"),'</div>
	<div>',__("Available"),'</div>
  </div>
  <div>
	<div>',sprintf(__("Total U")." %5d",$cStats["TotalU"]),'</div>
	<div>',sprintf("%3d",$cStats["Infrastructure"]),'</div>
	<div>',sprintf("%3d",$cStats["Occupied"]),'</div>
	<div>',sprintf("%3d",$cStats["Allocated"]),'</div>
	<div>',sprintf("%3d",$cStats["Available"]),'</div>
  </div>
  <div>
	<div>',__("Percentage"),'</div>
	<div>',(($cStats["TotalU"])?sprintf("%3.1f%%",$cStats["Infrastructure"]/$cStats["TotalU"]*100):"0"),'</div>
	<div>',(($cStats["TotalU"])?sprintf("%3.1f%%",$cStats["Occupied"]/$cStats["TotalU"]*100):"0"),'</div>
	<div>',(($cStats["TotalU"])?sprintf("%3.1f%%",$cStats["Allocated"]/$cStats["TotalU"]*100):"0"),'</div>
	<div>',(($cStats["TotalU"])?sprintf("%3.1f%%",$cStats["Available"]/$cStats["TotalU"]*100):"0"),'</div>
  </div>
  </div> <!-- END div.table -->
  <div class="table border">
  <div>
        <div>',__("Data Centers"),'</div>
        <div>',sprintf("%s ",number_format($cStats["DCs"],0, ",", ".")),'</div>
  </div>
  <div>
        <div>',__("Computed Wattage"),'</div>
        <div>',sprintf("%7d %s", $cStats["ComputedWatts"], __("Watts")),'</div>
  </div>
  <div>
		<div>',__("Measured Wattage"), '</div>
		<div>',sprintf("%7d %s", $cStats["MeasuredWatts"], __("Watts")),'</div>
  </div>
    <div>
		<div>',__("Design Maximum (kW)"),'</div>
		<div>',sprintf("%s kW",number_format($cStats["MaxkW"],0, ",", ".") ),'</div>
  </div>
    <div>
        <div>',__("Total Cooling Capacity"),'</div>
        <div>',sprintf("%s ".__("BTUH"),number_format($cStats["TotCap"],0, ",", ".") ),'</div>
  </div>
  <div>
        <div>',__("BTU Computation from Watts"),'</div>
        <div>',sprintf("%s ".__("BTU"),number_format($cStats["ComputedWatts"]*3.412,0, ",", ".") ),'</div>
  </div>
    <div>
        <div>',__("Cooling Usage Percentage %"),'</div>
        <div>',sprintf("%s %s",number_format(($cStats["ComputedWatts"]*3.412/$cStats["TotCap"])*100,0, ",", ".") ,__("%")),'</div>
  </div>
  <div>
        <div>',__("Data Center Size"),'</div>
        <div>',sprintf("%s ".$vol,number_format($cStats["SquareFootage"],0, ",", ".")),'</div>
  </div>
  <div>
        <div>',$density,'</div>
        <div>',(($cStats["SquareFootage"]>0)?sprintf("%s ".__("Watts"),number_format($cStats["ComputedWatts"]/$cStats["SquareFootage"],0, ",", ".")):"0 ".__("Watts")),'</div>
  </div>
  <div>
        <div>',__("Minimum Cooling Tonnage Required"),'</div>
        <div>',sprintf("%s ".__("Tons"),number_format($cStats["ComputedWatts"]*3.412*1.15/12000,0, ",", ".")),'</div>
  </div>
  <div>
    <div>',__("Total Cabinets"),'</div>
    <div>',sprintf( "%s", number_format($cStats["TotalCabinets"],0,",",".")),'</div>
  </div>
</div> <!-- END div.table -->
</div>

<br>
<div class="JMGA" style="center width: 1200px; overflow: hidden">';

  // print $c->MakeContainerImage();
?>

 <?php

        //connection
        $db = new mysqli('localhost', 'root', 'root', 'dcim');

         $sql    =   "SELECT DataCenterID AS 'dcid',Name AS 'name', MapX as 'lat', MapY as 'lng' FROM `fac_DataCenter` WHERE ContainerID='$c->ContainerID'";

        $res    =   $db->query( $sql );
        $places=array();
        // if( $res ) while( $rs=$res->fetch_object() ) $tempat=array_push($places,'name'=>$rs->name, 'latitude'=>$rs->lat, 'longitude'=>$rs->lng);
         
         while($row = $res->fetch_assoc()) {
          $places[] = $row;
         
         }

        
        $mapping="[";

        foreach ($places as $key) {
          $mapping.="[".'"'.$key['name'].'"'.",";
          $mapping.=$key['lat'].",";
          $mapping.=$key['lng'].",";
          $mapping.='"dc_stats.php?dc='.$key['dcid'].'"'."]".",";
          // echo "["."'".$key['name']."'".",";
          // echo $key['lat'].",";
          // echo $key['lng']."]".",";
          // echo "<br>";
         }

  
         $mapping= substr_replace($mapping ,"",-1);
         $mapping.="]";


         $sql2    =   "SELECT MapX as 'lat', MapY as 'lng' FROM `fac_Container` WHERE ContainerID='$c->ContainerID'";

        $res2    =   $db->query( $sql2 );

        $places2=array();
        // if( $res ) while( $rs=$res->fetch_object() ) $tempat=array_push($places,'name'=>$rs->name, 'latitude'=>$rs->lat, 'longitude'=>$rs->lng);
         
         while($row2 = $res2->fetch_assoc()) {
          $places2[] = $row2;
         
         }

         $mapping2="[";

        foreach ($places2 as $key2) {
          $mapping2.=$key2['lat'].",";
          $mapping2.=$key2['lng']."]".",";
          // echo "["."'".$key['name']."'".",";
          // echo $key['lat'].",";
          // echo $key['lng']."]".",";
          // echo "<br>";
         }

  
         $mapping2= substr_replace($mapping2 ,"",-1);

    ?>

</div></div>
</div><!-- END div.JMGA -->

<div id="map" class="container2"></div>

</div><!-- END div.main -->
</div><!-- END div.page -->
</div><!-- END div.page -->
</div><!-- END div.page -->

<script>
      var mapping = <?php echo json_encode($mapping) ?>;
      var mapping= JSON.parse(mapping);

      var mapping2 = <?php echo json_encode($mapping2) ?>;
      var mapping2= JSON.parse(mapping2);

      var map = new maptalks.Map('map', {
        center: mapping2,
        zoom: 17,
        zoomControl : true,
        //allow map to drag pitching, true by default
        dragPitch : true,
        //allow map to drag rotating, true by default
        dragRotate : true,
        //enable map to drag pitching and rotating at the same time, false by default
        dragRotatePitch : true,
        baseLayer: new maptalks.TileLayer('base', {
          urlTemplate: 'http://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png',
          subdomains: ['a','b','c','d'],
          attribution: '&copy; <a href="http://vioint.co.id">VIO DCIM</a>'
        }),
        layers: [
          new maptalks.VectorLayer('v')
        ]
      });

    var dcims = mapping;
    console.log(dcims);


      var layer = new maptalks.VectorLayer('vector').addTo(map);

       for (var i = 0; i < dcims.length; i++) {
        var dcim = dcims[i];
        var marker = new maptalks.Marker(
          [dcim[1],dcim[2]],
          {
            'properties' : {
              'name' : dcim[0],
            },
            symbol : [
              {
                'markerFile'   : 'images/pin.png',
                'markerWidth'  : 35,
                'markerHeight' : 40
              },
              {
                'textFaceName' : 'sans-serif',
                'textName' : '{name}',
                'textSize' : 14,
                'textDy'   : 24
              }
            ]
          }
        ).addTo(layer);
        
        marker.url= dcim[3];

        marker.on('click', function () {
        window.location.href = this.url;
      })
    };



    </script>

<script type="text/javascript">
	$(document).ready(function() {
		// Hard set widths to stop IE from being retarded
		$('#mapCanvas').css('width', $('.canvas > img[alt="clearmap over canvas"]').width()+'px');
		$('#mapCanvas').parent('.canvas').css('width', $('.canvas > img[alt="clearmap over canvas"]').width()+'px');

		// Don't attempt to open the datacenter tree until it is loaded
		function opentree(){
			if($('#datacenters .bullet').length==0){
				setTimeout(function(){
					opentree();
				},500);
			}else{
				var firstcabinet=$('#c<?php echo $c->ContainerID;?> > ul > li:first-child').attr('id');
				expandToItem('datacenters',firstcabinet);
			}
		}

		// Bind tooltips, highlight functions to the map
		opentree();
	});
</script>
</body>
</html>
