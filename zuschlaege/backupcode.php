<?php
	
/* Erklärungen

# Cell (Breite, Höhe, Text, Rahmen, Zeilenumbruch danach?)
# Multicell erzeugt Text, der am Ende automatisch umbricht oder bei \n
# $pdf->MultiCell(110, 05, $schluss, 0, 1);
# A4: 210 mm breit, 297mm hoch


# $pdf->Cell(190,10,'Dreieicher Rollenspiel-Treffen $dc_jahr', 0,1);

# Image('Link', Pos von Links, Pos von oben, Breite)
#$pdf->Image('img/misc/ticket_freierEintritt.jpg', 10, 10, 190);
#$pdf->SetFont('Arial','',12);
#$pdf->SetXY(10,10);

$pdf->Cell(170, 10, $begruessung,0,1);
$pdf->MultiCell(110, 05, $text,0,1);
$pdf->Ln();
$pdf->MultiCell(110, 05, $schluss, 0, 1);


$pdf->Code128(140,97, $barcode, 50, 15);
$pdf->SetXY(138.5, 112); # 15px tiefer
$pdf->SetFont('Courier', '', '10');
$pdf->Write(5, "ConServer-Nr: $barcode");

$pdf->SetXY(170, 15);
$pdf->SetFont('Arial', '', '40');
$pdf->Cell(25,5, "$art",0);



$pdf->Circle(185, 98.5, 2.2);


$pdf->SetFillColor(180,180,180);
$pdf->Rect(5.25,30.25,199.5,9.5,"F");
$pdf->SetFillColor(255,255,255);



*/






// //require('include/fpdf/fpdf_rotate.php');

// require('fpdf/fpdf_barcodes.php');










// class PDF_Rotate extends PDF_Code128
// {
// var $angle=0;

// 	function Rotate($angle,$x=-1,$y=-1)
// 	{
// 		if($x==-1)
// 			$x=$this->x;
// 		if($y==-1)
// 			$y=$this->y;
// 		if($this->angle!=0)
// 			$this->_out('Q');
// 		$this->angle=$angle;
// 		if($angle!=0)
// 		{
// 			$angle*=M_PI/180;
// 			$c=cos($angle);
// 			$s=sin($angle);
// 			$cx=$x*$this->k;
// 			$cy=($this->h-$y)*$this->k;
// 			$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
// 		}
// 	}

// 	function _endpage()
// 	{
// 		if($this->angle!=0)
// 		{
// 			$this->angle=0;
// 			$this->_out('Q');
// 		}
// 		parent::_endpage();
// 	}
// }


// class PDF extends PDF_Rotate
// {
// 	function RotatedText($x,$y,$txt,$angle)
// 	{
// 		//Text rotated around its origin
// 		$this->Rotate($angle,$x,$y);
// 		$this->Text($x,$y,$txt);
// 		$this->Rotate(0);
// 	}

// 	function RotatedImage($file,$x,$y,$w,$h,$angle)
// 	{
// 		//Image rotated around its upper-left corner
// 		$this->Rotate($angle,$x,$y);
// 		$this->Image($file,$x,$y,$w,$h);
// 		$this->Rotate(0);
// 	}


// 	protected $FontSpacingPt;      // current font spacing in points
// 	protected $FontSpacing;        // current font spacing in user units

// 	function SetFontSpacing($size)
// 	{
// 		if($this->FontSpacingPt==$size)
// 			return;
// 		$this->FontSpacingPt = $size;
// 		$this->FontSpacing = $size/$this->k;
// 		if ($this->page>0)
// 			$this->_out(sprintf('BT %.3f Tc ET', $size));
// 	}

	
//     //Cell with horizontal scaling if text is too wide
//     function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
//     {
//         //Get string width
//         $str_width=$this->GetStringWidth($txt);

//         //Calculate ratio to fit cell
//         if($w==0)
//             $w = $this->w-$this->rMargin-$this->x;
//         $ratio = ($w-$this->cMargin*2)/$str_width;

//         $fit = ($ratio < 1 || ($ratio > 1 && $force));
//         if ($fit)
//         {
//             if ($scale)
//             {
//                 //Calculate horizontal scaling
//                 $horiz_scale=$ratio*100.0;
//                 //Set horizontal scaling
//                 $this->_out(sprintf('BT %.2F Tz ET', $horiz_scale));
//             }
//             else
//             {
//                 //Calculate character spacing in points
//                 $char_space=($w-$this->cMargin*2-$str_width)/max(strlen($txt)-1, 1)*$this->k;
//                 //Set character spacing
//                 $this->_out(sprintf('BT %.2F Tc ET', $char_space));
//             }
//             //Override user alignment (since text will fill up cell)
//             $align='';
//         }

//         //Pass on to Cell method
//         $this->Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);

//         //Reset character spacing/horizontal scaling
//         if ($fit)
//             $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
//     }

//     //Cell with horizontal scaling only if necessary
//     function CellFitScale($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
//     {
//         $this->CellFit($w, $h, $txt, $border, $ln, $align, $fill, $link, true, false);
//     }

//     //Cell with horizontal scaling always
//     function CellFitScaleForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
//     {
//         $this->CellFit($w, $h, $txt, $border, $ln, $align, $fill, $link, true, true);
//     }

//     //Cell with character spacing only if necessary
//     function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
//     {
//         $this->CellFit($w, $h, $txt, $border, $ln, $align, $fill, $link, false, false);
//     }

//     //Cell with character spacing always
//     function CellFitSpaceForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
//     {
//         //Same as calling CellFit directly
//         $this->CellFit($w, $h, $txt, $border, $ln, $align, $fill, $link, false, true);
//     }
// }














$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(227,0,0);

$pdf->SetLineWidth(0.25);


$pdf->AddPage();

$font_size = 18;
$pdf->SetFont('Arial','',$font_size);
$pdf->SetFontSpacing(2);


$pdf->SetFontSpacing(0);
$font_size = 9;
$pdf->SetFontSize($font_size);




?>