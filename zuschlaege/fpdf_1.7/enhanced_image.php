<?php
/*
Enhanced Image Function

Image(string file, float x, float y [, float w [, float h [, string type [, mixed link [, float dpi [, char align]]]]]])
Version
License: Freeware (same as FPDF)
Date: 16.01.2007
Version: 1.1 + enhancement (based on FPDF 1.53)
Description
Puts an image in the page. One point must be given. If there no align selection is made,
the upper-left corner is standard. The dimensions can be specified in different ways:
- explicit width and height (expressed in user unit)
- explicit dpi for width and height (only one resolution for both directions to keep the original proportions)
- one explicit dimension, the other being calculated automatically in order to keep the original proportions (dpi in that case will be ignored)
- no explicit dimension or dpi, in which case the image is put at 72 dpi

Supported formats are JPEG and PNG.

For JPEG, all flavors are allowed:
gray scales
true colors (24 bits)
CMYK (32 bits)
For jpg, are allowed:
gray scales on at most 8 bits (256 levels)
indexed colors
true colors (24 bits)
but are not supported:
Interlacing
Alpha channel

If a transparent color is defined, it will be taken into account (but will be only interpreted by Acrobat 4 and above).
The format can be specified explicitly or inferred from the file extension.
It is possible to put a link on the image.

Remark: if an image is used several times, only one copy will be embedded in the file.
Parameters
file
Name of the file containing the image.
x
Abscissa of the reference point as selectet with align. If there is no selection, the upper-left corner is used
y
Ordinate of the reference point as selectet with align. If there is no selection, the upper-left corner is used
w
Width of the image in the page. If not specified or equal to zero, it is automatically calculated.
h
Height of the image in the page. If not specified or equal to zero, it is automatically calculated.
type
Image format. Possible values are (case insensitive): JPG, JPEG, PNG. If not specified, the type is inferred from the file extension.
link
URL or identifier returned by AddLink().
dpi
Image resolution in dpi. Only used if w and h is emty or zero
align
Reference point of image. Possible values are:
- 1 = upper left corner
- 2 = middle of upper side
- 3 = upper right corner
- 4 = middle of left side
- 5 = center of picture
- 6 = middle of right side
- 7 = lower left corner
- 8 = middle of lower side
- 9 = lower right corner
In case of no choice or a wrong one, upper left corner is used (1)

*/
// End of description, following is the function and an example script

require('fpdf.php');

class PDF extends FPDF
{
//Enhancement of the image() function
function Image($file,$x,$y,$w=0,$h=0,$type='',$link='', $dpi='', $align='')
{
   //Put an image on the page
   if(!isset($this->images[$file]))
   {
      //First use of image, get info
      if($type=='')
      {
         $pos=strrpos($file,'.');
         if(!$pos)
            $this->Error('Image file has no extension and no type was specified: '.$file);
         $type=substr($file,$pos+1);
      }
      $type=strtolower($type);
      $mqr=get_magic_quotes_runtime();
      set_magic_quotes_runtime(0);
      if($type=='jpg' || $type=='jpeg')
         $info=$this->_parsejpg($file);
      elseif($type=='png')
         $info=$this->_parsepng($file);
      else
      {
         //Allow for additional formats
         $mtd='_parse'.$type;
         if(!method_exists($this,$mtd))
            $this->Error('Unsupported image type: '.$type);
         $info=$this->$mtd($file);
      }
      set_magic_quotes_runtime($mqr);
      $info['i']=count($this->images)+1;
      $this->images[$file]=$info;
   }
   else
      $info=$this->images[$file];

   //Automatic width and height calculation if needed
   if($dpi==0)
      $dpi=72;
   if($w==0 && $h==0)
   {
      //Put image at specified dpi
      $w=($info['w']/$this->k) * (72 / $dpi);
      $h=($info['h']/$this->k) * (72 / $dpi);
   }
   if($w==0)
      $w=$h*$info['w']/$info['h'];
   if($h==0)
      $h=$w*$info['h']/$info['w'];

   switch ($align) {
   case 1:
      break;
   case 2:
      $x=$x-$w/2;
      break;
   case 3:
      $x=$x-$w;
      break;
   case 4:
      $y=$y-$h/2;
      break;
   case 5:
      $x=$x-$w/2;
      $y=$y-$h/2;
      break;
   case 6:
      $x=$x-$w;
      $y=$y-$h/2;
      break;
   case 7:
      $y=$y-$h;
      break;
   case 8:
      $x=$x-$w/2;
      $y=$y-$h;
      break;
   case 9:
      $x=$x-$w;
      $y=$y-$h;
      break;
   default:
      break;
}
   $this->_out(sprintf('q %.2f 0 0 %.2f %.2f %.2f cm /I%d Do Q',$w*$this->k,$h*$this->k,$x*$this->k,($this->h-($y+$h))*$this->k,$info['i']));
   if($link)
      $this->Link($x,$y,$w,$h,$link);
  }
}

// End of function, all following is an example script

$pdf=new PDF();
$pdf->SetDisplayMode(fullpage);
$pdf->SetFont('Times','',12);

// Page 1
$pdf->AddPage();
$pdf->SetXY(10,10);
$pdf->Cell(0,0,'Page '.$pdf->PageNo().' - Standard 72dpi / upper left corner is reference');
$pdf->Line(105, 5, 105, 292);
$pdf->Line(5, 148.5, 205, 148.5);
$pdf->Image('testbild.png', 105, 148.5,'');

// Page 2
$pdf->AddPage();
$pdf->SetXY(10,10);
$pdf->Cell(0,0,'Page '.$pdf->PageNo().' - 50dpi / upper left corner is reference');
$pdf->Line(105, 5, 105, 292);
$pdf->Line(5, 148.5, 205, 148.5);
$pdf->Image('testbild.png', 105, 148.5,'','','','',50);

// Page 3
$pdf->AddPage();
$pdf->SetXY(10,10);
$pdf->Cell(0,0,'Page '.$pdf->PageNo().' - 300dpi / upper left corner is reference');
$pdf->Line(105, 5, 105, 292);
$pdf->Line(5, 148.5, 205, 148.5);
$pdf->Image('testbild.png', 105, 148.5,'','','','',300);

// Page 4
$pdf->AddPage();
$pdf->SetXY(10,10);
$pdf->Cell(0,0,'Page '.$pdf->PageNo().' - Standard 72dpi / upper left corner is reference');
$pdf->Line(105, 5, 105, 292);
$pdf->Line(5, 148.5, 205, 148.5);
$pdf->Image('testbild.png', 105, 148.5,'','','','','','',1);

// Page 5
$pdf->AddPage();
$pdf->SetXY(10,10);
$pdf->Cell(0,0,'Page '.$pdf->PageNo().' - 100dpi / middle of upper side is reference');
$pdf->Line(105, 5, 105, 292);
$pdf->Line(5, 148.5, 205, 148.5);
$pdf->Image('testbild.png', 105, 148.5,'','','','',100,2);

// Page 6
$pdf->AddPage();
$pdf->SetXY(10,10);
$pdf->Cell(0,0,'Page '.$pdf->PageNo().' - 300dpi / upper right corner is reference');
$pdf->Line(105, 5, 105, 292);
$pdf->Line(5, 148.5, 205, 148.5);
$pdf->Image('testbild.png', 105, 148.5,'','','','',300,3);

// Page 7
$pdf->AddPage();
$pdf->SetXY(10,10);
$pdf->Cell(0,0,'Page '.$pdf->PageNo().' - Standard 72dpi / middle of left side is reference');
$pdf->Line(105, 5, 105, 292);
$pdf->Line(5, 148.5, 205, 148.5);
$pdf->Image('testbild.png', 105, 148.5,'','','','','',4);

// Page 8
$pdf->AddPage();
$pdf->SetXY(10,10);
$pdf->Cell(0,0,'Page '.$pdf->PageNo().' - 100dpi / center of image is reference');
$pdf->Line(105, 5, 105, 292);
$pdf->Line(5, 148.5, 205, 148.5);
$pdf->Image('testbild.png', 105, 148.5,'','','','',100,5);

// Page 9
$pdf->AddPage();
$pdf->SetXY(10,10);
$pdf->Cell(0,0,'Page '.$pdf->PageNo().' - 300dpi / middle of right side is reference');
$pdf->Line(105, 5, 105, 292);
$pdf->Line(5, 148.5, 205, 148.5);
$pdf->Image('testbild.png', 105, 148.5,'','','','',300,6);

// Page 10
$pdf->AddPage();
$pdf->SetXY(10,10);
$pdf->Cell(0,0,'Page '.$pdf->PageNo().' - Standard 72dpi / lower left corner is reference');
$pdf->Line(105, 5, 105, 292);
$pdf->Line(5, 148.5, 205, 148.5);
$pdf->Image('testbild.png', 105, 148.5,'','','','','',7);

// Page 11
$pdf->AddPage();
$pdf->SetXY(10,10);
$pdf->Cell(0,0,'Page '.$pdf->PageNo().' - 100dpi / middle of bottom side is reference');
$pdf->Line(105, 5, 105, 292);
$pdf->Line(5, 148.5, 205, 148.5);
$pdf->Image('testbild.png', 105, 148.5,'','','','',100,8);

// Page 12
$pdf->AddPage();
$pdf->SetXY(10,10);
$pdf->Cell(0,0,'Page '.$pdf->PageNo().' - 300dpi / lower right corner is reference');
$pdf->Line(105, 5, 105, 292);
$pdf->Line(5, 148.5, 205, 148.5);
$pdf->Image('testbild.png', 105, 148.5,'','','','',300,9);

// Page 13
$pdf->AddPage();
$pdf->SetXY(10,10);
$pdf->Cell(0,0,'Page '.$pdf->PageNo().' - minimal values');
$pdf->Line(105, 5, 105, 292);
$pdf->Line(5, 148.5, 205, 148.5);
$pdf->Image('testbild.png', 105, 148.5);

// Page 14
$pdf->AddPage();
$pdf->SetXY(10,10);
$pdf->Cell(0,0,'Page '.$pdf->PageNo().' - maximal values (wrong / dpi ignored)');
$pdf->Line(105, 5, 105, 292);
$pdf->Line(5, 148.5, 205, 148.5);
$pdf->Image('testbild.png', 105, 148.5,80,160,'jpg','',100,5);

// Page 15
$pdf->AddPage();
$pdf->SetXY(10,10);
$pdf->Cell(0,0,'Page '.$pdf->PageNo().' - explicit dimensions');
$pdf->Line(105, 5, 105, 292);
$pdf->Line(5, 148.5, 205, 148.5);
$pdf->Image('testbild.png', 105, 148.5,80,20,'','','',5);

$pdf->Output();
?>
