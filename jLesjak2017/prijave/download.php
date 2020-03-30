<?php
	require_once "Classes/PHPExcel.php";
	session_start();
	
	if(!isset($_SESSION['user']))
	{
		header('location: index.php');
	}
	
	if(empty($_SESSION['export_IDs']))
	{
		// nič ni za izvozit
		header("location: " . $_SERVER['HTTP_REFERER']);
	}
	
	
	require_once 'connect.inc.php';
	
	
	
	 // format: 
	//    mentorPriimek, mentorIme, EMSO, ime, priimek, program, razred, naslov, naslov(eng) opis, datum
	$query = "SELECT 
			  mentor.Priimek_mentorja, mentor.Ime_mentorja, vloga.EMSO,
			  vloga.Ime, vloga.Priimek, vloga.Izobrazevalni_program, vloga.Razred, 
			  vloga.Naslov_naloge, vloga.Naslov_naloge_eng, vloga.Opis_naloge, vloga.Datum, 
			  vloga.Odobritev_mentor, vloga.Odobritev_komisija
			  FROM
			  vloga JOIN mentor ON(vloga.MentorID = mentor.MentorID)
			  WHERE vloga.EMSO IN(
			  
			";
			
	foreach($_SESSION['export_IDs'] as $key)
	{
		if($key == end($_SESSION['export_IDs']))
		{
			$query .= "'". $key ."') ORDER BY mentor.MentorID";
		}
		else
		{
			$query .= "'". $key ."', ";
		}
	}
	
	
			
	if($query_run = mysqli_query($link, $query))
	{
		$objPHPExcel = new PHPExcel(); 
		$objPHPExcel->getActiveSheet()->setTitle('Prijave');
		// 'header' preglednice
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Priimek mentorja');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Ime mentorja');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'EMŠO kandidata');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Ime kandidata');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Priimek kandidata');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Izobraževalni program');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Oddelek');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Naslov naloge');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Naslov naloge (ang)');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Opis naloge');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Datum oddaje vloge');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Odobritev mentorja');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Odobritev komisije');
		
		$row = 2; // števec za vrstice se začne pri 2 (row 1 je header)
		// pretvori rezultat poizvedbe v asociativen array
		while($query_row = mysqli_fetch_assoc($query_run))
		{
			// prepis query array -> excel
			$col = 'A'; // 'števec' za stolpce (stolpci so označeni od A do K)
			foreach($query_row as $val)
			{
				$objPHPExcel->getActiveSheet()->setCellValue(($col . $row), $val);
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setWidth(20);
				$col = chr(ord($col) + 1);
			}
			$row++;
		}
			
		$filename = 'Kandidati ' . date('d-m-y') . '.xlsx';
			
		// prenos datoteke
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename=' . $filename);
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		$objWriter->save('php://output');

		unset($objPHPExcel);
		
	} else {
		//echo mysqli_error($link);
	}
		
		

	mysqli_close($link);

?>