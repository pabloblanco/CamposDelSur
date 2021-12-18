<?php
//require('../fpdf182/fpdf.php');
require('../fpdf17/fpdf.php');
require "../model/Conexion.php";
class PDF extends FPDF
{
   //Cabecera de página
   function Header()
    {
        global $conexion;
        global $fechacobranza;
        global $nrcobranza;

        $sqlglobal = "select * from global";

        $queryglobal = $conexion->query($sqlglobal);
        while ($regglobal = $queryglobal->fetch_object()) {
            // Logo
//            $this->Image('../Files/Global/descarga.jpg',10,8,33);
            $this->Image('../' . $regglobal->logo,8,8,50);
            // Arial bold 15
            $this->SetFont('Arial','B',7);
            // Movernos a la derecha
            $this->Cell(85);
            // Título
        
            $this->Cell(30,7,utf8_decode(strtoupper('EL PRESENTE RECIBO NO ES VALIDO SI NO')),0,0,'C');
            $this->SetFont('Arial','B',8);
            $this->Cell(120,7,'Fecha: ' . substr($fechacobranza,8,2) . '/' . substr($fechacobranza,5,2) . '/' . substr($fechacobranza,0,4) ,0,1,'C');
            $this->SetFont('Arial','B',7);
            $this->Cell(85);
            $this->Cell(30,1,utf8_decode(strtoupper('LLEVA EL SELLO SECO')),0,1,'C');
            $this->Cell(162);
            $this->SetFont('Arial','B',8);
            $this->Cell(30,5,utf8_decode(strtoupper('Bs:7882912')),0,1,'C');
            
            $this->Cell(10);
            $this->SetFont('Arial','B',15);
            $this->SetTextColor(6, 66, 147 );
            $this->Cell(0,6,utf8_decode(strtoupper($regglobal->empresa)),0,1,'C');
            
            $this->SetTextColor(0, 0, 0 );
            $this->Cell(1);
            $this->SetFont('Arial','B',8);
            $this->Cell(10,1,utf8_decode("Calle San Salvador # 1421,Miraflores"),0,1,'L');            
            $this->Cell(168);
            $this->Cell(10,1,utf8_decode("T.C: 6.2"),0,0,'L');
            $this->ln();
            $this->SetFont('Arial','B',8);
            //$this->Cell(0,1,utf8_decode($regglobal->linea1),0,1,'L');
            $this->Cell(1);
            $this->Cell(0,7,utf8_decode("Telf:2222763-77298528"),0,1,'L');
            //$this->Cell(30,7,utf8_decode($regglobal->linea2),0,1,'L');
            $this->Cell(1);
            $this->Cell(0,1,utf8_decode("La Paz - Bolivia"),0,1,'L');

            
            $this->Cell(85);
            $this->SetFont('Arial','B',7);
            $this->Cell(30,1,'Nro: SIS-1929' ,0,0,'C');

            $this->ln();
            $this->Cell(30);
            $this->SetFont('Arial','B',15);
            $this->SetTextColor(76, 205, 28  );
            $this->SetDrawColor(6, 66, 147 );
            $this->Cell(140,10,'RECIBO OFICIAL' ,"T,B",1,'C');
            $this->ln();
           
           
   
            // Salto de línea

        }
    }
   function TablaBasica($header)
   {
    $this->SetFillColor(255,0,0);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');

    //Cabecera
    foreach($header as $col)
   
      $this->Cell(20,5,"hola",1);
      $this->Cell(150,5,"hola2",1);
      $this->Cell(20,5,"hola3",1);
      $this->Ln();
   }
   //Pie de página
   function Footer()
   {
       //$this->SetY(-10);
       //$this->SetFont('Arial','I',8);
       /*$this->Image('../Files/Sucursal/Telefono.png',20,285,8);
       $this->Image('../Files/Sucursal/email.png',75,285,8);
       $this->Image('../Files/Sucursal/ubicacion.png',130,285,8);*/
       /*$this->Cell(60,3,"(+591-2)2120958",0,0,'C');
       $this->Cell(60,3,"info@silcom.com.bo",0,0,'C');
       $this->Cell(60,3,"C/Juan Capriles Nro. 1568",0,1,'C');
       $this->Cell(60,3,"(+591-2)2238777",0,0,'C');
       $this->Cell(60,3,"www.silcom.com.bo",0,0,'C');
       $this->Cell(60,3,"San Antonio Bajo. La Paz-Bolivia",0,1,'C');*/
   }
}
//Creación del objeto de la clase heredada
//$pdf=new PDF();
$pdf = new PDF("L","mm",array(216,140));
$pdf->AddPage();

    
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(40,6,"Hemos Recibido:",0,0,'R');
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(0,6,"Nombre del cliente",0,1,'L');

    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(40,6,"La suma de:",0,0,'R');
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(100,6,"Importe en letra",0,1,'L');

    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(40,6,"Tipo de Pago:",0,0,'R');
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(100,6,"Efectivo",0,1,'L');

    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(40,6,"Cuota(s):",0,0,'R');
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(70,6,"10,22,112",0,0,'L');
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(20,6,"Contrato:",0,0,'R');
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(40,6,"2311",0,1,'L');

    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(40,6,"Observaciones:",0,0,'R');
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(100,6,"Prueba observaciones",0,1,'L');

    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(40,6,"Saldo:",0,0,'R');
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(40,6,"2100",0,1,'L');
    $pdf->Ln();

    $pdf->SetFont('Arial','B',11);
    $pdf->SetTextColor(6, 66, 147 );
    $pdf->Cell(100,5,"__________________",0,0,'C');
    $pdf->Cell(100,5,"__________________",0,1,'C');
    $pdf->SetFont('Arial','',11);
    $pdf->SetTextColor(0, 0,0 );
    $pdf->Cell(100,5,"Usuario",0,0,'C');
    $pdf->Cell(100,5,"Cliente",0,1,'C');
    $pdf->Cell(100,5,"Jose Manuel Nuñez Molina",0,0,'C');
    $pdf->Cell(100,5,"Mary Juarez",0,1,'C');
$pdf->Output();
?>