<?php
	require_once('db.inc.php');
	require_once('facilities.inc.php');

	$subheader=__("Container Detail");

	if(!$person->SiteAdmin){
		// No soup for you.
		header('Location: '.redirect());
		exit;
	}
	
	$c=new Container();
	$status="";

	if(isset($_POST['action'])&&(($_POST['action']=='Create')||($_POST['action']=='Update'))){
		$c->ContainerID=$_POST['containerid'];
		$c->Name=trim($_POST['name']);
		$c->DrawingFileName=$_POST['drawingfilename'];
		$c->ParentID=$_POST['parentid'];
		$c->MapX=$_POST['x'];
		$c->MapY=$_POST['y'];
		
		if($c->Name!=""){
			if($_POST['action']=='Create'){
				$c->CreateContainer();
			}else{
				$status=__("Updated");
				$c->UpdateContainer();
			}
		}
	}
	
	if(isset($_POST['action']) && $_POST['action']=='Delete'){
		$c->ContainerID=$_POST['containerid'];
		$c->DeleteContainer();
		header('Location: container.php');
		exit;
	}
	
	if(isset($_POST['cambio_cont'])&& $_POST['cambio_cont']=='SI'){
		$c->ContainerID=$_POST['containerid'];
		$c->Name=trim($_POST['name']);
		$c->DrawingFileName=$_POST['drawingfilename'];
		$c->ParentID=$_POST['parentid'];
		if ($c->ParentID==0){
			$c->MapX=0;
			$c->MapY=0;
		}else{
			$c->MapX=$_POST['x'];
			$c->MapY=$_POST['y'];
		}
	}
	elseif(isset($_REQUEST['containerid'])&& $_REQUEST['containerid'] >0){
		$c->ContainerID=(isset($_POST['containerid']) ? $_POST['containerid'] : $_GET['containerid']);
		$c->GetContainer();
	}
	$cList=$c->GetContainerList();

	$imageselect='<div id="preview"></div><div id="filelist">';

	$path='./drawings';
	$dir=scandir($path);
	foreach($dir as $i => $f){
		if(is_file($path.DIRECTORY_SEPARATOR.$f)){
			$mimeType=mime_content_type($path.DIRECTORY_SEPARATOR.$f);
			if(preg_match('/^image/i', $mimeType)){
				$imageselect.="<span>$f</span>\n";
			}
		}
	}
	$imageselect.="</div>";

?>
<!doctype html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>VIO DCIM Data Center Inventory</title>
  
  <link rel="stylesheet" href="css/inventory.php" type="text/css">
  <link rel="stylesheet" href="css/jquery-ui.css" type="text/css">
  <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css">
  <!--[if lt IE 9]>
  <link rel="stylesheet"  href="css/ie.css" type="text/css">
  <![endif]-->
  <script type="text/javascript" src="scripts/jquery.min.js"></script>
  <script type="text/javascript" src="scripts/jquery-ui.min.js"></script>
  <script type="text/javascript" src="scripts/jquery.validationEngine-en.js"></script>
  <script type="text/javascript" src="scripts/jquery.validationEngine.js"></script>
  <script type="text/javascript" src="scripts/common.js?v<?php echo filemtime('scripts/common.js');?>"></script>

  <script type="text/javascript">
	$(document).ready(function() {
		$('#containerid').change(function(e){
			location.href='container.php?containerid='+this.value;
		});

		$("#containerid").combobox();
		$("#parentid").combobox();

		$('span.custom-combobox').width($('span.custom-combobox').width()+2);

		$('#datacenterform').validationEngine({});
		$('#drawingfilename').click(function(){
			$("#imageselection").dialog({
				resizable: false,
				height:500,
				width: 600,
				modal: true,
				buttons: {
<?php echo '					',__("Select"),': function() {'; ?>
						if($('#imageselection #preview').attr('image')!=""){
							$('#drawingfilename').val($('#imageselection #preview').attr('image'));
						}
						$(this).dialog("close");
					}
				}
			});
			$("#imageselection span").each(function(){
				var preview=$('#imageselection #preview');
				$(this).click(function(){
					preview.css({'border-width': '5px', 'width': '380px', 'height': '380px'});
					preview.html('<img src="drawings/'+$(this).text()+'" alt="preview">').attr('image',$(this).text());
					preview.children('img').load(function(){
						var topmargin=0;
						var leftmargin=0;
						if($(this).height()<$(this).width()){
							$(this).width(preview.innerHeight());
							$(this).css({'max-width': preview.innerWidth()+'px'});
							topmargin=Math.floor((preview.innerHeight()-$(this).height())/2);
						}else{
							$(this).height(preview.innerHeight());
							$(this).css({'max-height': preview.innerWidth()+'px'});
							leftmargin=Math.floor((preview.innerWidth()-$(this).width())/2);
						}
						$(this).css({'margin-top': topmargin+'px', 'margin-left': leftmargin+'px'});
					});
					$("#imageselection span").each(function(){
						$(this).removeAttr('style');
					});
					$(this).css('border','1px dotted black')
				});
				if($('#drawingfilename').val()==$(this).text()){
					$(this).click();
				}
			});
		});
		
		// Delete container confirmation dialog
		$('button[value="Delete"]').click(function(e){
			var form=$(this).parents('form');
			var btn=$(this);
			console.log(form);
<?php
print '		var dialog=$("<div>").prop("title","'.__("Verify Delete Container").'").html("<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left; margin:0 7px 20px 0;\"></span><span></span></p>");';
print '		dialog.find("span + span").html("'.__("This container will be deleted and there is no undo. Their direct descendants will be moved to \'home\'.").'<br>'.__("Are you sure?").'");'; 
?>
			dialog.dialog({
				resizable: false,
				modal: true,
				dialogClass: "no-close",
				buttons: {
<?php echo '				',__("Yes"),': function(){'; ?>
						$(this).dialog("destroy");
						form.append('<input type="hidden" name="'+btn.attr("name")+'" value="'+btn.val()+'">');
							form.submit();
					},
<?php echo '				',__("No"),': function(){'; ?>
						$(this).dialog("destroy");
					}
				}
			});
		});
	});
	function coords(evento){
		mievento = evento || window.event;

		yo=document.getElementById("yo");
		x=mievento.layerX;
		y=mievento.layerY;
		yo.style.left=(x-12)+"px";
		yo.style.top=(y-12)+"px";
		yo.hidden=false;
		CoorX=document.getElementById("x");
		CoorX.value=x*2;
		CoorY=document.getElementById("y");
		CoorY.value=y*2;
	}
	function mueve(){
		tam=50;
		red=.5;
		tam=tam*red;
		yo=document.getElementById("yo");
		cont=document.getElementById("containerimg");
		CoorX=document.getElementById("x");
		CoorY=document.getElementById("y");
		if (CoorX.value<0) CoorX.value=0;
		if (CoorX.value*red>cont.offsetWidth) CoorX.value=cont.offsetWidth/red;
		if (CoorY.value<0) CoorY.value=0;
		if (CoorY.value*red>cont.offsetHeight) CoorY.value=cont.offsetHeight/red;
		yo.style.left=(CoorX.value*red-tam/2)+"px";
		yo.style.top=(CoorY.value*red-tam/2)+"px";
		if (CoorX.value<0 || CoorX.value*red>cont.offsetWidth
			|| CoorY.value<0 || CoorY.value*red>cont.offsetHeight)
			yo.hidden=true;
		else
			yo.hidden=false;
	}
	function cambio_container(){
		document.getElementById("cambio_cont").value="SI";
		document.getElementById("containerform").submit();
	}
  </script>
<style type="text/css">
   .container2{height:300px}
    #status{position:fixed;left:0px;top:0px;width:100%;height:140px;overflow:hidden}
    #status div{background-color:rgba(13, 13, 13, 0.5);width:100%;height:100%;padding:10px 10px 10px 10px;font:13px bold sans-serif;color:#fff}
  </style>


  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/maptalks@0.40.3/dist/maptalks.css">
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/maptalks@0.40.3/dist/maptalks.min.js"></script>
</head>
<body>
<?php include( 'header.inc.php' ); ?>
<div class="backgroundpage">
<div class="page1">
<div class="makecenter">
<?php
	// include( 'sidebar.inc.php' );

echo '<div class="main">
<h3>',$status,'</h3>
<div class="center"><div>
<form id="containerform" method="POST">
<div class="table">
<div>
   <div><label for="containerid">',__("Container"),'</label></div>
   <div><select name="containerid" id="containerid">
      <option value="0">',__("New Container"),'</option>';

	foreach($cList as $cRow){
		if($cRow->ContainerID == $c->ContainerID){$selected=" selected";}else{$selected="";}
		print "<option value=\"$cRow->ContainerID\"$selected>$cRow->Name</option>\n";
	}

echo '	</select></div>
</div>
<div>
   <div><label for="cname">',__("Name"),'</label></div>
   <div><input class="validate[required,minSize[3],maxSize[80]]" type="text" name="name" id="cname" size="50" maxlength="80" value="',$c->Name,'"></div>
</div>
<div style="display : none;">
   <div><label for="drawingfilename">',__("Drawing URL"),'</label></div>
   <div><input type="text" name="drawingfilename" id="drawingfilename" size=60 value="',$c->DrawingFileName,'"></div>
</div>
<div><input type="hidden" name="cambio_cont" id="cambio_cont" value=""></div>
<div style="display : none;">
	<div><label for="parentid">',__("Parent Container"),'</label></div>
  	<div><select name="parentid" id="parentid" onChange="cambio_container()">
      <option value="0">',__("None"),'</option>';

	foreach($cList as $cRow){
		if ($cRow->ContainerID<>$c->ContainerID){
			if($cRow->ContainerID == $c->ParentID){$selected=" selected";}else{$selected="";}
			print "<option value=\"$cRow->ContainerID\"$selected>$cRow->Name</option>\n";
		}
	}

echo '	</select></div>
</div>
<div> 
	<div><b>Longitude</b></div> 
 	<div><input type="number" step="any" name="x" id="x" onblur="mueve()" value="',$c->MapX,'" ></div> 
</div> 
<div> 
    <div><b>Latitude</b></div> 
    <div><input type="number" step="any" name="y" id="y" onblur="mueve()" value="',$c->MapY,'"></div> 
</div>'; 

if ($c->ContainerID>0){
	print "<div id=map class=container2>\n"; 
	print "</div>"; 


	$db = new mysqli('localhost', 'root', 'root', 'dcim');

         $sql    =   "SELECT MapX as 'lat', MapY as 'lng' FROM `fac_Container` WHERE ContainerID='$c->ContainerID'";

        $res    =   $db->query( $sql );
        $places=array();
         
         while($row = $res->fetch_assoc()) {
          $places[] = $row;
         
         }

        
        $mapping="[";

        foreach ($places as $key) {
          $mapping.=$key['lat'].",";
          $mapping.=$key['lng']."]".",";
         }

  
         $mapping= substr_replace($mapping ,"",-1);
// 	$container=new Container();
// 	$container->ContainerID=$c->ParentID;
// 	$container->GetContainer();
// 	print '<div>
// 	<div><b>'.__("Click on the image to select container coordinates").'</b></div>
// 	<div>'.$container->MakeContainerMiniImage("container",$c->ContainerID).'</div>
// </div>'; 
}
else{
	print "<div id=map class=container2>\n"; 
	print "</div>"; 

	$mapping="[106.82713,-6.17562]";
}

echo '<div class="caption">';

	if($c->ContainerID >0){
		print "\t<button type=\"submit\" name=\"action\" value=\"Update\">".__("Update")."</button>\n";
		print "\t<button type=\"button\" name=\"action\" value=\"Delete\">".__("Delete")."</button>\n";
	}else{
		print "\t<button type=\"submit\" name=\"action\" value=\"Create\">".__("Create")."</button>\n";
	}
?>
</div>
</div> <!-- END div.table -->
</form>
<?php echo '
			<div id="imageselection" title="Image file selector">
				',$imageselect,'
			</div>
</div></div>
'; ?>
</div><!-- END div.main -->
</div><!-- END div.page -->
</div>
</div>

<script>
	  var mapping = <?php echo json_encode($mapping) ?>;
      var mapping= JSON.parse(mapping);

      var map = new maptalks.Map('map', {
        center: mapping,
        zoom: 17,
        centerCross: true,
        zoomControl : true,
        baseLayer: new maptalks.TileLayer('base', {
          urlTemplate: 'http://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png',
          subdomains: ['a','b','c','d'],
          attribution: '&copy; <a href="http://vioint.co.id">VIO DCIM</a> '
        }),
        layers: [
          new maptalks.VectorLayer('v')
        ]
      });

      map.on('zoomend moving moveend', getStatus);

      getStatus();

      function getStatus() {
        var extent = map.getExtent(),
          ex = [
            '{',
            'xmin:' + extent.xmin.toFixed(5),
            ', ymin:' + extent.ymin.toFixed(5),
            ', xmax:' + extent.xmax.toFixed(5),
            ', ymax:' + extent.xmax.toFixed(5),
            '}'
          ].join('');
        var center = map.getCenter();
        var mapStatus = [
          'Center : [' + [center.x.toFixed(5), center.y.toFixed(5)].join() + ']',
        ];

        CoorX=document.getElementById("x");
		CoorX.value=center.x.toFixed(5);
		CoorY=document.getElementById("y");
		CoorY.value=center.y.toFixed(5);

        // document.getElementById('status').innerHTML = '<div>' + mapStatus.join('<br>') + '</div>';
      }


    </script>
</body>
</html>
