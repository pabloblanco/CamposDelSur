<?php
require('../fpdf182/fpdf.php');
require "../model/Conexion.php";
class PDF extends FPDF
{
   //Cabecera de página
   function Header()
   {
        $this->Image('../Files/Sucursal/LogoJPG.jpg',170,4,22);
        $this->SetFont('Arial','B',16);
        $this->Cell(0,10,'Datos',0,1,'C');
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
        $this->SetY(-10);
        $this->SetFont('Arial','I',8);
        $this->Image('../Files/Sucursal/Telefono.png',20,285,8);
        $this->Image('../Files/Sucursal/email.png',75,285,8);
        $this->Image('../Files/Sucursal/ubicacion.png',130,285,8);
        $this->Cell(60,3,"(+591-2)2120958",0,0,'C');
        $this->Cell(60,3,"info@silcom.com.bo",0,0,'C');
        $this->Cell(60,3,"C/Juan Capriles Nro. 1568",0,1,'C');
        $this->Cell(60,3,"(+591-2)2238777",0,0,'C');
        $this->Cell(60,3,"www.silcom.com.bo",0,0,'C');
        $this->Cell(60,3,"San Antonio Bajo. La Paz-Bolivia",0,1,'C');
    }
}

//Creación del objeto de la clase heredada
$pdf=new PDF();
$pdf->AddPage();
$idCotiza = $_GET['Cotizacion'];
$sql = "SELECT a.idcotizacion,b.nombre as proyecto,c.nombre as cliente,a.tiempo_entrega,a.fecha_validez,a.garantia,a.forma_pago,a.fecha_creacion,c.direccion_provincia
FROM cotizacion a inner join proyecto b 
		on a.idproyecto = b.idproyecto
     INNER JOIN persona c 
     	on a.idcliente = c.idpersona
where idcotizacion = $idCotiza";
$rsc = mysqli_query($conexion, $sql);
if($row = mysqli_fetch_assoc($rsc)){
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,7,$row["proyecto"],0,1,'C');
    $pdf->Cell(0,4,"",0,1,'C');
    $pdf->SetFont('Arial','I',9);
    $pdf->Cell(30,4,"Cliente:",'L,T',0,'L');
    $pdf->Cell(100,4,$row["cliente"],'L,T,R',1,'L');

    $pdf->Cell(30,4,"Lugar:",'L',0,'L');
    $pdf->Cell(100,4,$row["direccion_provincia"],'L,R',1,'L');

    $pdf->Cell(30,4,"Proyecto:",'L',0,'L');
    $pdf->Cell(100,4,$row["proyecto"],'L,R',1,'L');

    $pdf->Cell(30,4,"Fecha:",'L',0,'L');
    $pdf->Cell(100,4,$row["fecha_creacion"],'L,R',1,'L');

    $pdf->Cell(30,4,"Nro. Cotizacion:",'L,B',0,'L');
    $pdf->Cell(100,4,$row["idcotizacion"],'L,B,R',1,'L');
    $pdf->Ln();
}
$pdf->SetFillColor(232,232,232);

$sql ="select a.iditem,a.item,sum(b.total) as total from itemcotizacion a inner join detlle_item b on a.iditem = b.iditem_cotiza 
where a.idcotizacion = $idCotiza GROUP by a.iditem,a.item";
$rs = mysqli_query($conexion, $sql);
$pdf->SetFont('Arial','I',10);
$Total = 0.00;
while($row = mysqli_fetch_assoc($rs)){
    $pdf->Cell(0,5,$row["item"],1,1,'C',1);
    $pdf->Ln(2);
    $i = 1;
    $SubTotal = 0.00;
    $sql = "SELECT  a.iddetalle
                    ,b.codigo_interno
                    ,b.nombre as prod
                    ,c.nombre
                    ,b.vrestringida
                    ,a.cantidad
                    ,a.precio
                    ,a.total 
            FROM `detlle_item` a inner join articulo b 
                on a.idprod_serv = b.idarticulo 
            inner join unidad_medida c 
                on b.idunidad_medida = c.idunidad_medida 
            where a.tipo = 'Producto' 
            and a.iditem_cotiza = ". $row["iditem"];
    $rsdi = mysqli_query($conexion, $sql);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(10,6,'Item',1,0,'C',0);
    $pdf->Cell(15,6,'Codigo',1,0,'C',0);
    $pdf->Cell(65,6,'Descripcion',1,0,'C',0);
    $pdf->Cell(15,6,'Marca',1,0,'C',0);
    $pdf->Cell(15,6,'Unidad',1,0,'C',0);
    $pdf->Cell(20,6,'Cantidad',1,0,'C',0);
    $pdf->Cell(20,6,'Precio',1,0,'C',0);
    $pdf->Cell(30,6,'Total',1,1,'C',0);
    $pdf->Ln(2);
    $pdf->Cell(0,5,"Materiales",1,2,'L',0);
    while($rowdi = mysqli_fetch_assoc($rsdi)){
        $pdf->SetFont('Arial','I',8);
        $pdf->Cell(10,4,$i,1,0,'C',0);
        $pdf->Cell(15,4,$rowdi["codigo_interno"],1,0,'C',0);
        $pdf->Cell(65,4,$rowdi["prod"],1,0,'C',0);
        $pdf->Cell(15,4,$rowdi["nombre"],1,0,'C',0);
        $pdf->Cell(15,4,$rowdi["vrestringida"],1,0,'C',0);
        $pdf->Cell(20,4,$rowdi["cantidad"],1,0,'C',0);
        $pdf->Cell(20,4,$rowdi["precio"],1,0,'C',0);
        $pdf->Cell(30,4,$rowdi["total"],1,1,'C',0);
        $SubTotal = $SubTotal + $rowdi["total"];
        $i = $i + 1;
    }

    $sql = "SELECT  a.iddetalle
                ,b.codigo
                ,b.nombre as prod
                ,c.nombre
                ,b.medida
                ,a.cantidad
                ,a.precio
                ,a.total 
            FROM `detlle_item` a inner join servicio b 
            on a.idprod_serv = b.idservicio 
            inner join unidad_medida c 
            on b.idunidad_medida = c.idunidad_medida 
            where a.tipo = 'Servicio' 
            and a.iditem_cotiza = ". $row["iditem"];
    $rsdi = mysqli_query($conexion, $sql);
    if(mysqli_num_rows($rsdi)){
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0,5,"Varios e instalacion",1,2,'L',0);
    }
    while($rowdi = mysqli_fetch_assoc($rsdi)){
        $pdf->SetFont('Arial','I',8);
        $pdf->Cell(10,4,$i,1,0,'C',0);
        $pdf->Cell(15,4,$rowdi["codigo"],1,0,'C',0);
        $pdf->Cell(65,4,$rowdi["prod"],1,0,'C',0);
        $pdf->Cell(15,4,$rowdi["nombre"],1,0,'C',0);
        $pdf->Cell(15,4,$rowdi["medida"],1,0,'C',0);
        $pdf->Cell(20,4,$rowdi["cantidad"],1,0,'C',0);
        $pdf->Cell(20,4,$rowdi["precio"],1,0,'C',0);
        $pdf->Cell(30,4,$rowdi["total"],1,1,'C',0);
        $i = $i + 1;
        $SubTotal = $SubTotal + $rowdi["total"];
    }
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(160,5,"Sub Total",1,0,'R',0);
    $pdf->Cell(30,5,$SubTotal,1,1,'C',0);
    $Total = $Total + $SubTotal;
}
$pdf->Ln(2);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(160,6,'Total',1,0,'R',0);
$pdf->Cell(30,6,$Total,1,0,'C',0);
$pdf->Output();
?>