<?php
    require_once( 'db.inc.php' );
    require_once( 'facilities.inc.php' );

    define('FPDF_FONTPATH','font/');
    require('fpdf.php');

class PDF extends FPDF {
  var $outlines=array();
  var $OutlineRoot;
  var $pdfconfig;
  var $pdfDB;
  
    function PDF(){
        parent::FPDF();
    }
  
    function Header() {
        $this->pdfconfig = new Config();
        if ( file_exists( 'images/' . $this->pdfconfig->ParameterArray['PDFLogoFile'] )) {
            $this->Image( 'images/' . $this->pdfconfig->ParameterArray['PDFLogoFile'],10,8,100);
        }
        $this->SetFont($this->pdfconfig->ParameterArray['PDFfont'],'B',12);
        $this->Cell(120);
        $this->Cell(30,20,__("Information Technology Services"),0,0,'C');
        $this->Ln(25);
        $this->SetFont( $this->pdfconfig->ParameterArray['PDFfont'],'',10 );
        $this->Cell( 50, 6, __("Data Center PAC Report"), 0, 1, 'L' );
        $this->Cell( 50, 6, __("Date").': ' . date('d F Y'), 0, 1, 'L' );
        $this->Ln(10);
    }

    function Footer() {
            $this->SetY(-15);
            $this->SetFont($this->pdfconfig->ParameterArray['PDFfont'],'I',8);
            $this->Cell(0,10,__("Page").' '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

    $Owner = @$_REQUEST['owner'];
    $DataCenterID = @$_REQUEST['datacenterid'];
  
    $pdf=new PDF();
    include_once("loadfonts.php");
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont($config->ParameterArray['PDFfont'],'',6);

    $pdf->SetFillColor( 0, 0, 0 );
    $pdf->SetTextColor( 255 );
    $pdf->SetDrawColor( 128, 0, 0 );
    $pdf->SetLineWidth( .3 );

    $headerTags = array( __("DC Room"), __("Location"), __("Assign To"), __("Zone"), __("Cabinet Row"), __("Model"), __("Cooling Capacity"), __("Installation Date") );
    $cellWidths = array( 25, 25, 20, 20, 20, 30, 30, 20 );
    $maxval = count( $headerTags );

    for ( $col = 0; $col < $maxval; $col++ )
        $pdf->Cell( $cellWidths[$col], 7, $headerTags[$col], 1, 0, 'C', 1 );

    $pdf->Ln();

    $pdf->SetfillColor( 224, 235, 255 );
    $pdf->SetTextColor( 0 );

    $fill = 0;
        
    $searchSQL = 'select (select Name from fac_DataCenter fdc where fdc.DataCenterID = fac.DataCenterID) as DataCenter, fac.Location, fac.AssignedTo, (select Description from fac_Zone fz where fz.ZoneID = fac.ZoneID ) as Zone, CabRowID, Model, ColCap, InstallationDate from fac_AC fac';

    $lastDC = '';
    $lastCab = '';

    foreach($dbh->query($searchSQL) as $reportRow){
        $DataCenter = $reportRow['DataCenter'];
        $Location = $reportRow['Location'];
        $AssignedTo = $reportRow['AssignedTo'];
        $Zone = $reportRow['Zone'];
        $CabRowID = $reportRow['CabRowID'];
        $Model = $reportRow['Model'];
        $ColCap = $reportRow['ColCap'];
        $InstallationDate = $reportRow['InstallationDate'];

        if ( $reportRow["Height"] > 1 )
            $Position = '[' . $reportRow['Position'] . '-' . intval($reportRow['Position']+$reportRow['Height']-1) . ']';
        else
            $Position = $reportRow['Position'];
            
        $Label = $reportRow['Label'];
        $SerialNo = $reportRow['SerialNo'];
        $AssetTag = $reportRow['AssetTag'];

        
        $pdf->Cell( $cellWidths[0], 6, $DataCenter, 'LR', 0, 'L', $fill );
        $pdf->Cell( $cellWidths[1], 6, $Location, 'LR', 0, 'L', $fill );
        $pdf->Cell( $cellWidths[2], 6, $AssignedTo, 'LR', 0, 'L', $fill );
        $pdf->Cell( $cellWidths[3], 6, $Zone, 'LR', 0, 'L', $fill );
        $pdf->Cell( $cellWidths[4], 6, $CabRowID, 'LR', 0, 'L', $fill );
        $pdf->Cell( $cellWidths[5], 6, $Model, 'LR', 0, 'L', $fill );
        $pdf->Cell( $cellWidths[6], 6, $ColCap, 'LR', 0, 'L', $fill );
        $pdf->Cell( $cellWidths[7], 6, $InstallationDate, 'LR', 0, 'L', $fill );
        $pdf->Ln();

        $fill =! $fill;

        $lastDC = $DataCenter;
        $lastCab = $Location;
    }

    $pdf->Cell( array_sum( $cellWidths ), 0, '', 'T' );

    $pdf->Output();
?>
