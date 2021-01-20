<?php 
/*
 * Copyright 2014, Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * author Peter Engels
 *
 * On this page the admin can print all teacher appointments as PDF.
 */

require_once "estap.php";
require_once "FPDF/fpdf.php";

use ESTAP\Session;
use PhoolKit\I18N;

class PDF extends FPDF
{
public $headLine;

// Page header
function Header()
{
    // Arial bold 18
    $this->SetFont('Arial','B',18);
    // Title
    $this->Cell(0,0,utf8_decode($this->headLine),0,0,'L');
    // Line break
    $this->Ln(8);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,sprintf(I18N::getMessage("printPDF.generated"),date('d.m.Y, H:i'))." - ".utf8_decode(sprintf(I18N::getMessage("printPDF.copyright"),date('Y'))),0,0,'R');
}

// Colored table
function AppointmentTable($header, $data)
{
  //Document informations
  $this->SetAuthor(I18N::getMessage("printPDF.author"));
  $this->SetCreator(I18N::getMessage("printPDF.creator"));
  $this->SetTitle(I18N::getMessage("printPDF.title"));

	// Colors, line width and bold font
	$this->SetFillColor(220,220,220);
	$this->SetTextColor(0);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.3);
	$this->SetFont('','B',11);
	// Header
	$w = array(40, 30, 100, 20);
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],8,$header[$i],1,0,'C',true);
	$this->Ln();
	// Color and font restoration
	$this->SetFillColor(235,235,235);
	$this->SetTextColor(0);
	$this->SetFont('','',9);
	// Data
	$fill = false;
	foreach($data as $row)
	{
		$this->Cell($w[0],6,$row[0],1,0,'C',$fill);
		$this->Cell($w[1],6,$row[1],1,0,'C',$fill);
		$this->Cell($w[2],6,$row[2],1,0,'C',$fill);
		$this->Cell($w[3],6,$row[3],1,0,'C',$fill);
		$this->Ln();
		$fill = !$fill;
	}
}

function AppointmentTableStudent($header, $data)
    {
        //Document informations
        $this->SetAuthor(I18N::getMessage("printPDF.author"));
        $this->SetCreator(I18N::getMessage("printPDF.creator"));
        $this->SetTitle(I18N::getMessage("printPDF.title"));

        // Colors, line width and bold font
        $this->SetFillColor(220,220,220);
        $this->SetTextColor(0);
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('','B',11);
        // Header
        $w = array(40, 30, 100);
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],8,$header[$i],1,0,'C',true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(235,235,235);
        $this->SetTextColor(0);
        $this->SetFont('','',9);
        // Data
        $fill = false;
        foreach($data as $row)
        {
            $this->Cell($w[0],6,$row[0],1,0,'C',$fill);
            $this->Cell($w[1],6,$row[1],1,0,'C',$fill);
            $this->Cell($w[2],6,$row[2],1,0,'C',$fill);
            $this->Ln();
            $fill = !$fill;
        }
    }

// Colored table
function ParentAppointmentTable($header, $data)
{
  //Document informations
  $this->SetAuthor(I18N::getMessage("printPDF.author"));
  $this->SetCreator(I18N::getMessage("printPDF.creator"));
  $this->SetTitle(I18N::getMessage("printPDF.title"));

	// Colors, line width and bold font
	$this->SetFillColor(220,220,220);
	$this->SetTextColor(0);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.3);
	$this->SetFont('','B',11);
	// Header
	$w = array(40, 50, 50, 30);
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],8,$header[$i],1,0,'C',true);
	$this->Ln();
	// Color and font restoration
	$this->SetFillColor(235,235,235);
	$this->SetTextColor(0);
	$this->SetFont('','',9);
	// Data
	$fill = false;
	foreach($data as $row)
	{
		$this->Cell($w[0],6,$row[0],1,0,'C',$fill);
		$this->Cell($w[1],6,$row[1],1,0,'C',$fill);
		$this->Cell($w[2],6,$row[2],1,0,'C',$fill);
		$this->Cell($w[3],6,$row[3],1,0,'C',$fill);
		$this->Cell($w[4],6,$row[4],1,0,'C',$fill);
		$this->Ln();
		$fill = !$fill;
	}
}
}
?>