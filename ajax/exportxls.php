<?php 
    include "../config.php";
    require_once '../vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['filename']) && !empty($_GET['filename'])){
        $path = '../assets/xlsx/'.$_GET['filename'];
        $title = $_POST['title'];
        $header = $_POST['header'];
        $data = $_POST['data'];
        $total = $_POST['total'];
        $average = $_POST['avg'];
        $cols = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $styleArray1 = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $styleArray2 = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $styleArray3 = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => array('argb' => '000000'),
                ],
            ],
        ];
        
        $range = $cols[count($header)+1].'1';
        $spreadsheet->getActiveSheet()->mergeCells('A1:'.$range);
        $sheet->setCellValue('A1',$title); 
        $sheet ->getStyle('A1:'.$range)->applyFromArray($styleArray3)->getFont()->setSize(16);
        $i = 0; $j = 0;
        foreach($header as $item){
            $sheet->setCellValue($cols[$i].'2',$item);
            $i++;
        }
        $sheet->setCellValue($cols[$i].'2','Total');
        $sheet->setCellValue($cols[$i+1].'2','Average');
        $sheet ->getStyle('A2:'.$cols[$i+1].'2')->applyFromArray($styleArray1);
        foreach($data as $val){
            $sheet->setCellValue($cols[$j].'3',$val);
            $j++;
        }
        $sheet->setCellValue($cols[$j].'3',$total);
        $sheet->setCellValue($cols[$j+1].'3',$average);
        $sheet ->getStyle('A3:'.$cols[$j+1].'3')->applyFromArray($styleArray2);
        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        if(file_exists($path)){
            echo basename($path);
        }
    }else{
        echo 'Bad request';
    }
?>