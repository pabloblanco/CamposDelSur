<?php
session_start();
ob_start();
//require('../fpdf182/fpdf.php');
require('../fpdf17/fpdf.php');
require "../model/Conexion.php";
class PDF extends FPDF
{
    public function valorEnLetras($x,$moneda) 
{ 
	if ($x<0) { 
		$signo = "menos ";
	} else { 
		$signo = "";
	} 
	$x = abs ($x); 
	$C1 = $x; 


	$G6 = floor($x/(1000000));  // 7 y mas 

	$E7 = floor($x/(100000)); 
	$G7 = $E7-$G6*10;   // 6 

	$E8 = floor($x/1000); 
	$G8 = $E8-$E7*100;   // 5 y 4 

	$E9 = floor($x/100); 
	$G9 = $E9-$E8*10;  //  3 

	$E10 = floor($x); 
	$G10 = $E10-$E9*100;  // 2 y 1 

	$G11 = round(($x-$E10)*100,0);  // Decimales 
	////////////////////// 

	$H6 = $this->unidades($G6); 


	if ($G7==1 AND $G8==0) { 
		$H7 = "Cien "; 
	} else { 
		$H7 = $this->decenas($G7); 
	} 

	$H8 = $this->unidades($G8); 

	if ($G9==1 AND $G10==0) { 
		$H9 = "Cien "; 
	} else {
		$H9 = $this->decenas($G9); 
	} 

	$H10 = $this->unidades($G10); 


	if ($G11 < 10) { 
		$H11 = "0".$G11; 
	} else { 
		$H11 = $G11; 
	} 


	///////////////////////////// 
	if($G6==0) { 
		$I6=" "; 
	} elseif ($G6==1) { 
			$I6="Millón "; 
		} else { 
			$I6="Millones "; 
		} 
	          
	if ($G8==0 AND $G7==0) { 
		$I8=" "; 
	} else { 
		$I8="Mil "; 
	} 
	          
	$I10 = $moneda; 
	$I11 = "/100 ".$moneda; 
//	$I11 = "/100 M.N. "; 

	$C3 = $signo.$H6.$I6.$H7.$H8.$I8.$H9.$H10.$H11.$I11; 

	return $C3; //Retornar el resultado 
}
public function unidades($u) 
{ 
    if ($u==0)  {$ru = " ";} 
elseif ($u==1)  {$ru = "Un ";} 
elseif ($u==2)  {$ru = "Dos ";} 
elseif ($u==3)  {$ru = "Tres ";} 
elseif ($u==4)  {$ru = "Cuatro ";} 
elseif ($u==5)  {$ru = "Cinco ";} 
elseif ($u==6)  {$ru = "Seis ";} 
elseif ($u==7)  {$ru = "Siete ";} 
elseif ($u==8)  {$ru = "Ocho ";} 
elseif ($u==9)  {$ru = "Nueve ";} 
elseif ($u==10) {$ru = "Diez ";} 

elseif ($u==11) {$ru = "Once ";} 
elseif ($u==12) {$ru = "Doce ";} 
elseif ($u==13) {$ru = "Trece ";} 
elseif ($u==14) {$ru = "Catorce ";} 
elseif ($u==15) {$ru = "Quince ";} 
elseif ($u==16) {$ru = "Dieciseis ";} 
elseif ($u==17) {$ru = "Decisiete ";} 
elseif ($u==18) {$ru = "Dieciocho ";} 
elseif ($u==19) {$ru = "Diecinueve ";} 
elseif ($u==20) {$ru = "Veinte ";} 

elseif ($u==21) {$ru = "Veintiun ";} 
elseif ($u==22) {$ru = "Veintidos ";} 
elseif ($u==23) {$ru = "Veintitres ";} 
elseif ($u==24) {$ru = "Veinticuatro ";} 
elseif ($u==25) {$ru = "Veinticinco ";} 
elseif ($u==26) {$ru = "Veintiseis ";} 
elseif ($u==27) {$ru = "Veintisiente ";} 
elseif ($u==28) {$ru = "Veintiocho ";} 
elseif ($u==29) {$ru = "Veintinueve ";} 
elseif ($u==30) {$ru = "Treinta ";} 

elseif ($u==31) {$ru = "Treinta y un ";} 
elseif ($u==32) {$ru = "Treinta y dos ";} 
elseif ($u==33) {$ru = "Treinta y tres ";} 
elseif ($u==34) {$ru = "Treinta y cuatro ";} 
elseif ($u==35) {$ru = "Treinta y cinco ";} 
elseif ($u==36) {$ru = "Treinta y seis ";} 
elseif ($u==37) {$ru = "Treinta y siete ";} 
elseif ($u==38) {$ru = "Treinta y ocho ";} 
elseif ($u==39) {$ru = "Treinta y nueve ";} 
elseif ($u==40) {$ru = "Cuarenta ";} 

elseif ($u==41) {$ru = "Cuarenta y un ";} 
elseif ($u==42) {$ru = "Cuarenta y dos ";} 
elseif ($u==43) {$ru = "Cuarenta y tres ";} 
elseif ($u==44) {$ru = "Cuarenta y cuatro ";} 
elseif ($u==45) {$ru = "Cuarenta y cinco ";} 
elseif ($u==46) {$ru = "Cuarenta y seis ";} 
elseif ($u==47) {$ru = "Cuarenta y siete ";} 
elseif ($u==48) {$ru = "Cuarenta y ocho ";} 
elseif ($u==49) {$ru = "Cuarenta y nueve ";} 
elseif ($u==50) {$ru = "Cincuenta ";} 

elseif ($u==51) {$ru = "Cincuenta y un ";} 
elseif ($u==52) {$ru = "Cincuenta y dos ";} 
elseif ($u==53) {$ru = "Cincuenta y tres ";} 
elseif ($u==54) {$ru = "Cincuenta y cuatro ";} 
elseif ($u==55) {$ru = "Cincuenta y cinco ";} 
elseif ($u==56) {$ru = "Cincuenta y seis ";} 
elseif ($u==57) {$ru = "Cincuenta y siete ";} 
elseif ($u==58) {$ru = "Cincuenta y ocho ";} 
elseif ($u==59) {$ru = "Cincuenta y nueve ";} 
elseif ($u==60) {$ru = "Sesenta ";} 

elseif ($u==61) {$ru = "Sesenta y un ";} 
elseif ($u==62) {$ru = "Sesenta y dos ";} 
elseif ($u==63) {$ru = "Sesenta y tres ";} 
elseif ($u==64) {$ru = "Sesenta y cuatro ";} 
elseif ($u==65) {$ru = "Sesenta y cinco ";} 
elseif ($u==66) {$ru = "Sesenta y seis ";} 
elseif ($u==67) {$ru = "Sesenta y siete ";} 
elseif ($u==68) {$ru = "Sesenta y ocho ";} 
elseif ($u==69) {$ru = "Sesenta y nueve ";} 
elseif ($u==70) {$ru = "Setenta ";} 

elseif ($u==71) {$ru = "Setenta y un ";} 
elseif ($u==72) {$ru = "Setenta y dos ";} 
elseif ($u==73) {$ru = "Setenta y tres ";} 
elseif ($u==74) {$ru = "Setenta y cuatro ";} 
elseif ($u==75) {$ru = "Setenta y cinco ";} 
elseif ($u==76) {$ru = "Setenta y seis ";} 
elseif ($u==77) {$ru = "Setenta y siete ";} 
elseif ($u==78) {$ru = "Setenta y ocho ";} 
elseif ($u==79) {$ru = "Setenta y nueve ";} 
elseif ($u==80) {$ru = "Ochenta ";} 

elseif ($u==81) {$ru = "Ochenta y un ";} 
elseif ($u==82) {$ru = "Ochenta y dos ";} 
elseif ($u==83) {$ru = "Ochenta y tres ";} 
elseif ($u==84) {$ru = "Ochenta y cuatro ";} 
elseif ($u==85) {$ru = "Ochenta y cinco ";} 
elseif ($u==86) {$ru = "Ochenta y seis ";} 
elseif ($u==87) {$ru = "Ochenta y siete ";} 
elseif ($u==88) {$ru = "Ochenta y ocho ";} 
elseif ($u==89) {$ru = "Ochenta y nueve ";} 
elseif ($u==90) {$ru = "Noventa ";} 

elseif ($u==91) {$ru = "Noventa y un ";} 
elseif ($u==92) {$ru = "Noventa y dos ";} 
elseif ($u==93) {$ru = "Noventa y tres ";} 
elseif ($u==94) {$ru = "Noventa y cuatro ";} 
elseif ($u==95) {$ru = "Noventa y cinco ";} 
elseif ($u==96) {$ru = "Noventa y seis ";} 
elseif ($u==97) {$ru = "Noventa y siete ";} 
elseif ($u==98) {$ru = "Noventa y ocho ";} 
else            {$ru = "Noventa y nueve ";} 
return $ru; //Retornar el resultado 
} 
public function decenas($d) 
{ 
    if ($d==0)  {$rd = "";} 
	elseif ($d==1)  {$rd = "Ciento ";} 
	elseif ($d==2)  {$rd = "Doscientos ";} 
	elseif ($d==3)  {$rd = "Trescientos ";} 
	elseif ($d==4)  {$rd = "Cuatrocientos ";} 
	elseif ($d==5)  {$rd = "Quinientos ";} 
	elseif ($d==6)  {$rd = "Seiscientos ";} 
	elseif ($d==7)  {$rd = "Setecientos ";} 
	elseif ($d==8)  {$rd = "Ochocientos ";} 
	else            {$rd = "Novecientos ";} 
	return $rd; //Retornar el resultado 
}
   //Cabecera de página
   function Header()
    {
        global $conexion;
        global $fechacobranza;
        global $nrcobranza;
        global $TpoCambio;
        global $BS;
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
            $this->Cell(30,6,utf8_decode(strtoupper('LLEVA EL SELLO SECO')),0,1,'C');
            
            $this->Cell(10);
            $this->SetFont('Arial','B',15);
            $this->SetTextColor(6, 66, 147 );
            $this->Cell(0,6,utf8_decode(strtoupper($regglobal->empresa)),0,1,'C');
            
            $this->SetTextColor(0, 0, 0 );
            $this->Cell(1);
            $this->SetFont('Arial','B',8);
            $this->Cell(10,1,utf8_decode($regglobal->linea1),0,1,'L');            
            $this->Cell(162);
            $this->Cell(10,1,utf8_decode("T.C: ".$TpoCambio),0,0,'L');
            $this->ln();
            $this->SetFont('Arial','B',8);
            //$this->Cell(0,1,utf8_decode($regglobal->linea1),0,1,'L');
            $this->Cell(1);
            $this->Cell(0,7,utf8_decode($regglobal->linea2),0,1,'L');
            //$this->Cell(30,7,utf8_decode($regglobal->linea2),0,1,'L');
            //$this->Cell(1);
            //$this->Cell(0,1,utf8_decode(""),0,1,'L');
            $this->Cell(85);
            $this->SetFont('Arial','B',10);
            $this->Cell(30,3,'Nro: '. $nrcobranza ,0,1,'C');

            

            $this->ln();
            $this->Cell(30);
            $this->SetFont('Arial','B',15);
            $this->SetTextColor(76, 205, 28  );
            $this->SetDrawColor(6, 66, 147 );
            $this->Cell(140,9,'RECIBO OFICIAL' ,"T,B",1,'C');
            
            $this->Cell(10);
            $this->SetFont('Arial','B',7);
            $this->Cell(0,4,utf8_decode(""),0,1,'L');

            $this->Cell(162);
            $this->SetTextColor(0, 0, 0  );
            $this->SetFont('Arial','B',15);
            $this->Cell(30,2,utf8_decode(strtoupper('Bs:'.$BS)),0,1,'L');
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
if(isset($_GET['id'])){
    try{
        $idcobranza = $_GET['id'];
        $sqlcontrato = "select idcontrato from vcobranza where idcobranza = $idcobranza";
        
        $querycontrato = $conexion->query($sqlcontrato);
        
        while ($regcontrato = $querycontrato->fetch_object()) {
            $idcontrato = $regcontrato->idcontrato;
        }
        
        $sqlsaldo = "select * from cuota where idcontrato = $idcontrato and estado<>'C'";
        
        $querysaldo = $conexion->query($sqlsaldo);
        $saldo = 0;
        
        while ($regsaldo = $querysaldo->fetch_object()) {
            $saldo = $saldo + $regsaldo->monto;
        }
        
        
        $sql = "select * from vcobranza where idcobranza = $idcobranza";
        
        $query = $conexion->query($sql);
        
        while ($reg = $query->fetch_object()) {
                $sql3 = "select concat(p.nombre,' ',p.apellidos) as usuario 
                    from usuario u left join personal p on u.idpersonal = p.idpersonal 
                    where u.idusuario = " . $_SESSION["idusuario"];
        
                $query3 = $conexion->query($sql3);
                while ($reg3 = $query3->fetch_object()) {
                    $fechacobranza = $reg->fechacobranza;
                    $nrcobranza = $reg->nrcobranza;
                    $TpoCambio = $reg->tasacambio;
                    $BS = $reg->montobs;
                    

                    $pdf = new PDF("L","mm",array(216,140));
                    $pdf->AddPage();
        
            
                    $pdf->SetFont('Arial','B',11);
                    $pdf->Cell(40,5,"Hemos Recibido:",0,0,'R');
                    $pdf->SetFont('Arial','',11);
                    $pdf->Cell(0,5,utf8_decode($reg->nomadquiriente . ' ' . $reg->apeadquiriente),0,1,'L');
        
                    $pdf->SetFont('Arial','B',11);
                    $pdf->Cell(40,5,"La suma de:",0,0,'R');
                    $pdf->SetFont('Arial','',11);
                    if ($reg->emiterecibo=='D') {
                        $ImpL = $pdf->valorEnLetras($reg->montocobranza,'Dolares');
                    }else{
                        $ImpL = $pdf->valorEnLetras($reg->montobs,'Bolivianos');
                    }
                    $pdf->Cell(100,5,$ImpL,0,1,'L');
        
                    $pdf->SetFont('Arial','B',11);
                    $pdf->Cell(40,5,"Tipo de Pago:",0,0,'R');
                    $pdf->SetFont('Arial','',11);
                    $pdf->Cell(100,5,utf8_decode($reg->tpcobranza=='E' ? 'EFECTIVO' : ($reg->tpcobranza=='C' ? 'CHEQUE' : 'BANCO') ),0,1,'L');
        
                    $pdf->SetFont('Arial','B',11);
                    $pdf->Cell(40,5,"Cuota(s):",0,0,'R');
                    $pdf->SetFont('Arial','',11);
                    $pdf->Cell(70,5,utf8_decode($reg->concobranza ),0,0,'L');
                    $pdf->SetFont('Arial','B',11);
                    $pdf->Cell(20,5,"Contrato:",0,0,'R');
                    $pdf->SetFont('Arial','',11);
                    $pdf->Cell(40,5,utf8_decode($reg->nrocontrato ),0,1,'L');
        
                    $pdf->SetFont('Arial','B',11);
                    $pdf->Cell(40,5,"Observaciones:",0,0,'R');
                    $pdf->SetFont('Arial','',11);
                    $pdf->Cell(100,5,utf8_decode($reg->obscobranza ),0,1,'L');
        
               /*     $pdf->SetFont('Arial','B',11);
                    $pdf->Cell(40,5,"Saldo:",0,0,'R');
                    $pdf->SetFont('Arial','',11);
                    $pdf->Cell(40,5,'USD ' . number_format($saldo, 2),0,1,'L');
                    $pdf->Ln();
        */
                    $pdf->SetFont('Arial','B',11);
                    $pdf->SetTextColor(6, 66, 147 );
                    $pdf->Cell(100,5,"__________________",0,0,'C');
                    $pdf->Cell(100,5,"__________________",0,1,'C');
                    $pdf->SetFont('Arial','',11);
                    $pdf->SetTextColor(0, 0,0 );
                    $pdf->Cell(100,5,"Usuario",0,0,'C');
                    $pdf->Cell(100,5,"Cliente",0,1,'C');
                    $pdf->Cell(100,5,utf8_decode($reg3->usuario),0,0,'C');
                    $pdf->Cell(100,5,utf8_decode($reg->nomadquiriente . ' ' . $reg->apeadquiriente),0,1,'C');
                    $pdf->Output();
        
        
                    
            }
        };
    }catch(Exception $e){
echo $e->getMessage();
    }
    
}else{
    echo "No se pudo determinar el ID de la cobranza, vuelve a intentarlo";
}
ob_end_flush();

?>