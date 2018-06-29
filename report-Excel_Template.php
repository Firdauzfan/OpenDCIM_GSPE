<?php
/*	Template file for creating Excel based reports
	
	Basically just the setup of the front page for consistency
*/

	require_once "db.inc.php";
	require_once "facilities.inc.php";
	require_once "vendor/autoload.php";

	$person = People::Current();

	$workBook = new PHPExcel();
	
	$workBook->getProperties()->setCreator("VIODCIM");
	$workBook->getProperties()->setLastModifiedBy("VIODCIM");
	$workBook->getProperties()->setTitle("Data Center Inventory Export");
	$workBook->getProperties()->setSubject("Data Center Inventory Export");
	$workBook->getProperties()->setDescription("Export of the VIODCIM database based upon user filtered criteria.");
	
	// Start off with the TPS Cover Page

	$workBook->setActiveSheetIndex(0);
	$sheet = $workBook->getActiveSheet();

    $sheet->SetTitle('Front Page');
    // add logo
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setWorksheet($sheet);
    $objDrawing->setName("Logo");
    $objDrawing->setDescription("Logo");
    $apath = __DIR__ . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
    $objDrawing->setPath($apath . $config->ParameterArray['PDFLogoFile']);
    $objDrawing->setCoordinates('A1');
    $objDrawing->setOffsetX(5);
    $objDrawing->setOffsetY(5);

    $logoHeight = getimagesize( $apath . $config->ParameterArray['PDFLogoFile']);
    $sheet->getRowDimension('1')->setRowHeight($logoHeight[1]);

    // set the header of the print out
    $header_range = "A1:B2";
    $fillcolor = $config->ParameterArray['HeaderColor'];
    $fillcolor = (strpos($fillcolor, '#') == 0) ? substr($fillcolor, 1) : $fillcolor;
    $sheet->getStyle($header_range)
        ->getFill()
        ->getStartColor()
        ->setRGB($fillcolor);

    $org_font_size = 20;
    $sheet->setCellValue('A2', $config->ParameterArray['OrgName']);
    $sheet->getStyle('A2')
        ->getFont()
        ->setSize($org_font_size);
    $sheet->getStyle('A2')
        ->getFont()
        ->setBold(true);
    $sheet->getRowDimension('2')->setRowHeight($org_font_size + 2);
    $sheet->setCellValue('A4', 'Report generated by \''
        . $person->UserID
        . '\' on ' . date('Y-m-d H:i:s'));

    // Add text about the report itself
    $sheet->setCellValue('A7', 'Notes');
    $sheet->getStyle('A7')
        ->getFont()
        ->setSize(14);
    $sheet->getStyle('A7')
        ->getFont()
        ->setBold(true);

    $remarks = array( "You can add in additional notes about the report, here.",
    		"Simply place each line of data as a separate element of the array.",
    		"Each element will be placed on a new line of the front sheet within the workbook." );
    $max_remarks = count($remarks);
    $offset = 8;
    for ($idx = 0; $idx < $max_remarks; $idx ++) {
        $row = $offset + $idx;
        $sheet->setCellValueExplicit('B' . ($row),
            $remarks[$idx],
            PHPExcel_Cell_DataType::TYPE_STRING);
    }
    $sheet->getStyle('B' . $offset . ':B' . ($offset + $max_remarks - 1))
        ->getAlignment()
        ->setWrapText(true);
    $sheet->getColumnDimension('B')->setWidth(120);
    $sheet->getTabColor()->setRGB($fillcolor);

    // Now the real data for the report

	$sheet = $workBook->createSheet();
	$sheet->setTitle( "My Worksheet" );

	// Put in the relevant data and add more worksheets as needed

	
	// Now finalize it and send to the client

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header( sprintf( "Content-Disposition: attachment;filename=\"VIOdcim-%s.xlsx\"", date( "YmdHis" ) ) );
	
	$writer = new PHPExcel_Writer_Excel2007($workBook);
	$writer->save('php://output');
?>