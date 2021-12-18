<?php

	require "Conexion.php";
	require "FPdfPlandePagos.php";
	require "FPdfKardex.php";
	require "FPdfKardex2.php";
	require "FPdfReciboRecargo.php";

	class Contrato {

		public function __construct(){

		}

		public function Registrar($nrocontrato,$idlote,$idsector,$idcementerio,$observaciones,$idadquiriente,$idejecutivoventa,$fechacontrato,$nuevo) {

			global $conexion;

			$sql = "INSERT INTO contrato (nrocontrato,idlote,idsector,idcementerio,observaciones,idadquiriente,idejecutivoventa,fechacontrato,plandepago,nuevo)

						VALUES($nrocontrato,$idlote,$idsector,$idcementerio,'$observaciones',$idadquiriente,$idejecutivoventa,'$fechacontrato','N','$nuevo')";

			$query = $conexion->query($sql);

			return $query;

		}

		public function RegistrarCompleto($nrocontrato,$idlote,$idsector,$idcementerio,$observaciones,$idadquiriente,$idejecutivoventa,$fechacontrato,$nuevo,$tipoprecio,$precio,$cuotainicial,$plazomeses,$cuotamensual,$incrementoplazo,$fechapago, $idcontrato) {

			global $conexion;

			// Contrato Reprogramado
			$sql2 = "UPDATE contrato set estado='reprogramado'  
						WHERE idcontrato = $idcontrato";
			$query2 = $conexion->query($sql2);

			// Nuevo Contrato
			$sql = "INSERT INTO contrato (nrocontrato,idlote,idsector,idcementerio,observaciones,idadquiriente,idejecutivoventa,fechacontrato,plandepago,nuevo,tipoprecio,precio,cuotainicial,plazomeses,cuotamensual,incrementoplazo,fechapago)
						VALUES($nrocontrato,$idlote,$idsector,$idcementerio,'$observaciones',$idadquiriente,$idejecutivoventa,'$fechacontrato','N','$nuevo','$tipoprecio',$precio,$cuotainicial,$plazomeses,$cuotamensual,$incrementoplazo,'$fechapago')";

			$query = $conexion->query($sql);

			return $query;

		}

		public function RegistrarPlandePagos($precio,$incrementoplazo,$cuotainicial,$idcontrato,$plazomeses,$cuotamensual,$fechapago){

			global $conexion;

			$sql = "DELETE FROM cuota WHERE idcontrato = $idcontrato AND tipocuota='C'";

			$query = $conexion->query($sql);

			$nrocuota = 1;
			$fechacuota = new DateTime("$fechapago");

			$total = $plazomeses * $cuotamensual;
			$diferencia = ($precio + $incrementoplazo - $cuotainicial) - $total;
			$Primeracuotamensual = $cuotamensual + $diferencia;

			while ($nrocuota <= $plazomeses) {

	            $fechalimite = date_format($fechacuota,"Y/m/d");
	            if ($nrocuota==1) {
	            	$montocuota = $Primeracuotamensual;
	            } else {
	            	$montocuota = $cuotamensual;
	            }
				$sql = "INSERT INTO cuota (idcontrato,nrocuota,fechalimite,monto,estado,tipocuota,acuenta,saldo)

							VALUES ($idcontrato,$nrocuota,'$fechalimite',$montocuota,'P','C',0,$cuotamensual)";

				$query = $conexion->query($sql);
				$fechacuota->add(new DateInterval('P1M'));
				$nrocuota = $nrocuota + 1;
			}

			$sql2 = "UPDATE contrato SET plandepago='N' 
						WHERE idcontrato = $idcontrato";

			$query2 = $conexion->query($sql2);

			return 1;

		}

		public function ProximoNroCuotaRecargo($idcontrato) {

			global $conexion;

			$proximonrocuota = 1;

			$sql2 = "SELECT nrocuota FROM cuota WHERE idcontrato=" . $idcontrato . " and tipocuota='R' order by nrocuota desc limit 1";

			$query2 = $conexion->query($sql2);

     		while ($reg2 = $query2->fetch_object()) {

     			$proximonrocuota = $reg2->nrocuota + 1;

            }

            return $proximonrocuota;

		}


		public function RegistrarRecargo($idcontrato,$tipocuota,$nrocuota,$concepto,$fechalimite,$monto){

			global $conexion;

//            $fechalimite2 = date_format($fechalimite,"Y-m-d");

			$nrocuota = $this->ProximoNroCuotaRecargo($idcontrato);

			$sql = "INSERT INTO cuota (idcontrato,tipocuota,nrocuota,concepto,fechalimite,monto,estado,acuenta,saldo)

						VALUES($idcontrato,'$tipocuota',$nrocuota,'$concepto','$fechalimite',$monto,'P',0,$monto)";

			$query = $conexion->query($sql);

			return $query;

		}

		public function ModificarRecargo($idcuota,$concepto,$fechalimite,$monto){

			global $conexion;

			$sql = "UPDATE cuota SET concepto='$concepto', fechalimite='$fechalimite', monto=$monto  
						WHERE idcuota = $idcuota";

			$query = $conexion->query($sql);

			return $query;

		}


		public function Modificar($idcontrato,$observaciones,$idadquiriente,$idejecutivoventa,$fechacontrato,$nuevo){

			global $conexion;

			$sql = "UPDATE contrato SET observaciones='$observaciones', idadquiriente=$idadquiriente, idejecutivoventa=$idejecutivoventa, fechacontrato='$fechacontrato', nuevo='$nuevo'  
						WHERE idcontrato = $idcontrato";

			$query = $conexion->query($sql);

			return $query;

		}

		public function ActualizarLote($idlote,$idestadolote){

			global $conexion;

			$sql = "UPDATE lote set idestadolote=$idestadolote  
						WHERE idlote = $idlote";

			$query = $conexion->query($sql);

			return $query;

		}

		public function ValorIdContrato() {
			global $conexion;

			$sql = "SELECT MAX(idcontrato) ultimo FROM contrato";

			$query = $conexion->query($sql);

			return $query;
		}

		public function ModificarPrecios($idcontrato,$tipoprecio,$precio,$cuotainicial,$plazomeses,$cuotamensual,$incrementoplazo,$fechapago) {

			global $conexion;

			$sql = "UPDATE contrato set tipoprecio='$tipoprecio', precio=$precio, cuotainicial=$cuotainicial, plazomeses=$plazomeses, cuotamensual=$cuotamensual, incrementoplazo=$incrementoplazo, fechapago='$fechapago'  
						WHERE idcontrato = $idcontrato";

			$query = $conexion->query($sql);

			return $query;

		}

		public function Eliminar($idcontrato){

			global $conexion;

			$sql = "UPDATE contrato set estado='rescindido'  
						WHERE idcontrato = $idcontrato";
//			$sql = "DELETE FROM contrato WHERE idcontrato = $idcontrato";

			$query = $conexion->query($sql);

			return $query;

		}

		public function EliminarRecargo($idcuota){

			global $conexion;

			$sql = "DELETE FROM cuota WHERE idcuota = $idcuota";

			$query = $conexion->query($sql);

			return $query;

		}

		public function ExisteContrato($nrocontrato) {

			global $conexion;

			$sql = "SELECT * FROM contrato where nrocontrato=$nrocontrato";

			$query = $conexion->query($sql);

			$existe = false;

     		while ($reg = $query->fetch_object()) {

     			$existe = true;

            }

            return $existe;

		}

		public function Listar() {

			global $conexion;

			$sql = "SELECT * FROM contrato order by idcontrato desc";

			$query = $conexion->query($sql);

			return $query;

		}

		public function ListarTodosContratos() {

			global $conexion;

			$sql = "select c.*, CONCAT(a.nombre,' ',a.apellidos) as adquiriente, CONCAT(a.tipodocumento,' ',a.numdocumento) as documento, CONCAT(e.nombre,' ',e.apellidos) as ejecutivoventa, m.razonsocial, s.nombre as sector, l.numero as nrolote, l.fila as fila, l.columna as columna,vr.totalmonto, vr.totalsaldo, vr.totalacuenta from contrato c left join adquiriente a on c.idadquiriente = a.idadquiriente left join ejecutivoventa e on c.idejecutivoventa = e.idejecutivoventa left join cementerio m on c.idcementerio = m.idcementerio left join sector s on c.idsector = s.idsector left join lote l on c.idlote = l.idlote left join vresumencuotas vr on c.idcontrato=vr.idcontrato order by c.idcontrato desc ";

			$query = $conexion->query($sql);

			return $query;

		}

		public function ListarContratos() {

			global $conexion;

			$sql = "select c.*, CONCAT(a.nombre,' ',a.apellidos) as adquiriente, CONCAT(a.tipodocumento,' ',a.numdocumento) as documento, CONCAT(e.nombre,' ',e.apellidos) as ejecutivoventa, m.razonsocial, s.nombre as sector, l.numero as nrolote, l.fila as fila, l.columna as columna from contrato c left join adquiriente a on c.idadquiriente = a.idadquiriente left join ejecutivoventa e on c.idejecutivoventa = e.idejecutivoventa left join cementerio m on c.idcementerio = m.idcementerio left join sector s on c.idsector = s.idsector left join lote l on c.idlote = l.idlote where c.estado='activo' order by c.idcontrato desc ";

			$query = $conexion->query($sql);

			return $query;

		}

		public function ListarRecargos() {

			global $conexion;

			$sql = "select c.*, CONCAT(a.nombre,' ',a.apellidos) as adquiriente, CONCAT(a.tipodocumento,' ',a.numdocumento) as documento, CONCAT(e.nombre,' ',e.apellidos) as ejecutivoventa,ct.* ,m.razonsocial cementerio, s.nombre as sector, l.numero as lote, l.fila as fila, l.columna as columna from contrato c left join adquiriente a on c.idadquiriente = a.idadquiriente left join ejecutivoventa e on c.idejecutivoventa = e.idejecutivoventa left join cementerio m on c.idcementerio = m.idcementerio left join sector s on c.idsector = s.idsector left join lote l on c.idlote = l.idlote left join cuota ct on c.idcontrato = ct.idcontrato where ct.tipocuota='R' order by c.idcontrato desc ";


			$query = $conexion->query($sql);

			return $query;

		}

		public function ListarContratosMora() {

			global $conexion;

			$sql = "select c.*, CONCAT(a.nombre,' ',a.apellidos) as adquiriente, CONCAT(a.tipodocumento,' ',a.numdocumento) as documento, a.celular as celular, a.telefono as telefono, CONCAT(e.nombre,' ',e.apellidos) as ejecutivoventa, m.razonsocial, s.nombre as sector, l.numero as nrolote, l.fila as fila, l.columna as columna, vc.* from contrato c left join adquiriente a on c.idadquiriente = a.idadquiriente left join ejecutivoventa e on c.idejecutivoventa = e.idejecutivoventa left join cementerio m on c.idcementerio = m.idcementerio left join sector s on c.idsector = s.idsector left join lote l on c.idlote = l.idlote left join vcuotas vc on c.idcontrato=vc.idcontrato where vc.estado='V' order by c.idcontrato desc ";

			$query = $conexion->query($sql);

			return $query;

		}

		public function ListarCuotasMora($fecha) {

			global $conexion;

			$sql = "select c.*, CONCAT(a.nombre,' ',a.apellidos) as adquiriente, CONCAT(a.tipodocumento,' ',a.numdocumento) as documento, a.celular as celular, a.telefono as telefono, CONCAT(e.nombre,' ',e.apellidos) as ejecutivoventa, m.razonsocial, s.nombre as sector, l.numero as nrolote, l.fila as fila, l.columna as columna, vc.* from contrato c left join adquiriente a on c.idadquiriente = a.idadquiriente left join ejecutivoventa e on c.idejecutivoventa = e.idejecutivoventa left join cementerio m on c.idcementerio = m.idcementerio left join sector s on c.idsector = s.idsector left join lote l on c.idlote = l.idlote right join cuota vc on c.idcontrato=vc.idcontrato where vc.estado='V' and fechalimite=" . $fecha . " order by c.idcontrato desc ";

			$query = $conexion->query($sql);

			return $query;

		}

		public function SaldoPendiente($idcontrato) {

			global $conexion;

			$sql = "select sum(saldo) as saldo from cuota where idcontrato = $idcontrato";

			$query = $conexion->query($sql);

			return $query;

		}

		public function ListarPlandePagos($idcontrato) {

			global $conexion;

			$sql = "select * from cuota where idcontrato = $idcontrato and tipocuota='C' order by nrocuota";

			$query = $conexion->query($sql);

			return $query;

		}

		public function CrearPlandePagos($idcontrato) {

			global $conexion;

			$sqldatocontrato = "select * 
								from vcontrato c 
								where idcontrato = $idcontrato";

			$querydatocontrato = $conexion->query($sqldatocontrato);

     		while ($reg = $querydatocontrato->fetch_object()) {
		     			$titulo = utf8_decode('PLAN DE PAGOS');
		     			$titulo2 = utf8_decode('(Expresado en dólares)');
						$pdf = new PDFPP('P','mm','LETTER');
						$pdf->AliasNbPages();
						$pdf->AddPage();

						$pdf->SetFont('Arial','',12);
				        $pdf->SetFillColor(229, 229, 229); //Gris tenue de cada fila
				        $pdf->SetTextColor(3, 3, 3); //Color del texto: Negro

						$pdf->Cell(35,7,utf8_decode('CEMENTERIO: '),'T',0);
						$pdf->Cell(70,7,utf8_decode(strtoupper($reg->cementerio)),'T',0,'L',true);

						$pdf->Cell(35,7,utf8_decode('SECTOR: '),'T',0);
						$pdf->Cell(0,7,utf8_decode(strtoupper($reg->sector)),'T',1,'L',true);

						$pdf->Cell(35,7,utf8_decode('REF. LOTE: '),'B',0);
						$pdf->Cell(40,7,utf8_decode(strtoupper($reg->numlote)),'B',0,'L',true);

						$pdf->Cell(30,7,utf8_decode('FILA: '),'B',0);
						$pdf->Cell(35,7,utf8_decode(strtoupper($reg->fila)),'B',0,'L',true);

						$pdf->Cell(35,7,utf8_decode('COLUMNA: '),'B',0);
						$pdf->Cell(0,7,utf8_decode(strtoupper($reg->columna)),'B',1,'L',true);


						$pdf->Cell(80,7,utf8_decode('ADQUIRIENTE TITULAR: '),0,0);
						$pdf->Cell(0,7,utf8_decode($reg->nomadquiriente . ' ' . $reg->apeadquiriente),'T',1,'L',true);


						$pdf->Cell(80,7,utf8_decode('CONTRATO Nº: '),0,0);
						$pdf->Cell(0,7,utf8_decode($reg->nrocontrato),0,1,'L',true);

						$pdf->Cell(80,7,utf8_decode('PRECIO US$: '),0,0);
						$pdf->Cell(0,7,utf8_decode($reg->precio),0,1,'L',true);

						$pdf->Cell(80,7,utf8_decode('INCREMENTO PLAZO: '),0,0);
						$pdf->Cell(0,7,utf8_decode($reg->incrementoplazo),0,1,'L',true);

						$pdf->Cell(80,7,utf8_decode('PLAZO EN MESES: '),0,0);
						$pdf->Cell(0,7,utf8_decode($reg->plazomeses),0,1,'L',true);

						$pdf->Cell(80,7,utf8_decode('CUOTA INICIAL: '),0,0);
						$pdf->Cell(0,7,utf8_decode($reg->cuotainicial),0,1,'L',true);

						$pdf->Cell(80,7,utf8_decode('FECHA CONTRATO: '),0,0);
						$pdf->Cell(0,7,utf8_decode($reg->fechacontrato),0,1,'L',true);

						$pdf->Cell(80,7,utf8_decode('TIPO CAMBIO Bs. /US$: '),0,0);
						$pdf->Cell(0,7,utf8_decode('VALOR DEL DOLAR CONTRA EL BOLIVIANO'),0,1,'L',true);

						$pdf->Cell(80,7,utf8_decode('FECHA DE PAGO: '),0,0);
						$pdf->Cell(0,7,utf8_decode($reg->fechapago),0,1,'L',true);

					$sql3 = "select *  
						from cuota 
					   where idcontrato = $reg->idcontrato";

					$query3 = $conexion->query($sql3);

					//Títulos que llevará la cabecera
					$miCabecera = array(
									array('encabezado1'=>'CUOTA Nª', 'encabezado2'=>'FECHA LÍMITE DE PAGO', 'encabezado3'=>'MONTO A PAGAR EN USD', 'encabezado4'=>'SALDO DEUDOR EN USD', 'len1' => 25, 'len2' => 50, 'len3' => 60, 'len4' => 60)
								);
					 
					//Métodos llamados con el objeto $pdf
					$x = 115;
					$pdf->cabeceraHorizontal($miCabecera,$x);
					$x = 122;
					$bandera = false;
					$saldo = ($reg->precio + $reg->incrementoplazo - $reg->cuotainicial);
	     			$misDatos = array(array('nrocuota' => 'Inicial','fechalimite' => '', 'monto' => '', 'saldo' => $saldo, 'len1' => 25, 'len2' => 50, 'len3' => 60, 'len4' => 60) );
					$pdf->datosHorizontal($misDatos,$x,$bandera);
					$x = 122 + 7;

		     		while ($reg3 = $query3->fetch_object()) {
		     			$saldo = $saldo - $reg3->monto;
		     			$saldo = round($saldo,2);
		     			$misDatos = array(array('nrocuota' => $reg3->nrocuota,'fechalimite' => $reg3->fechalimite, 'monto' => $reg3->monto, 'saldo' => $saldo, 'len1' => 25, 'len2' => 50, 'len3' => 60, 'len4' => 60) );
						$pdf->datosHorizontal($misDatos,$x,$bandera);
						$x = $x + 7;
						$bandera = !$bandera;
						if ($x>250) {
							$pdf->AddPage();
							$x = 40;
							$pdf->cabeceraHorizontal($miCabecera,$x);
							$x = 47;
						}

		     		}

					//enviamos cabezales http para no tener problemas
					header("Content-Transfer-Encoding", "binary");
					header('Cache-Control: maxage=3600'); 
					header('Pragma: public');
					//			$pdf->Output('recibos.pdf','D');
					///$pdf->Output();
					$nombrearchivo = '../Files/Pdf/Plan de Pagos. Contrato Nro. ' . $reg->nrocontrato . '.pdf';
					$pdf->Output($nombrearchivo,'F');

					$sql2 = "UPDATE contrato SET plandepago='S' 
								WHERE idcontrato = $idcontrato";

					$query2 = $conexion->query($sql2);
			};

			return;
		}

		public function ListarContratosInhumados() {

			global $conexion;

			$sql = "select c.*, CONCAT(a.nombre,' ',a.apellidos) as adquiriente, CONCAT(e.nombre,' ',e.apellidos) as ejecutivoventa, m.razonsocial, s.nombre as sector, l.numero as numlote, l.fila as fila, l.columna as columna,d.nombre as difunto,i.fechafallecimiento, i.fechainhumacion 
				from contrato c left join adquiriente a on c.idadquiriente = a.idadquiriente 
					 left join ejecutivoventa e on c.idejecutivoventa = e.idejecutivoventa 
					 left join cementerio m on c.idcementerio = m.idcementerio 
					 left join sector s on c.idsector = s.idsector 
					 left join lote l on c.idlote = l.idlote  
					 left join inhumacion i on c.idcontrato = i.idcontrato 
					 left join difunto d on i.iddifunto=d.iddifunto 
				where d.nombre <> '' 
				order by c.idcontrato desc";

			$query = $conexion->query($sql);

			return $query;

		}


		public function CrearKardex($idcontrato) {

			global $conexion;

			$sqldatocontrato = "select * 
								from vcontrato c 
								where idcontrato = $idcontrato";

			$querydatocontrato = $conexion->query($sqldatocontrato);

     		while ($reg = $querydatocontrato->fetch_object()) {
		     			$titulo = utf8_decode('KARDEX');
		     			$titulo2 = utf8_decode('(Expresado en dólares)');
						$pdf = new PDFKardex('P','mm','LETTER');
						$pdf->AliasNbPages();
						$pdf->AddPage();

						$pdf->SetFont('Arial','',10);
				        $pdf->SetFillColor(229, 229, 229); //Gris tenue de cada fila
				        $pdf->SetTextColor(3, 3, 3); //Color del texto: Negro

						$pdf->Cell(38,7,utf8_decode('ADQUIRIENTE: '),'T',0);
						$pdf->Cell(91,7,utf8_decode(utf8_decode($reg->nomadquiriente . ' ' . $reg->apeadquiriente)),'T',0,'L',true);

						$pdf->Cell(35,7,utf8_decode('CONTRATO Nº: '),'T',0);
						$pdf->Cell(0,7,utf8_decode($reg->nrocontrato),'T',1,'L',true);

						$pdf->Cell(38,7,utf8_decode('PRECIO US$: '),0,0);
						$pdf->Cell(26,7,utf8_decode($reg->precio),0,0,'L',true);

						$pdf->Cell(43,7,utf8_decode('PLAZO EN MESES: '),0,0);
						$pdf->Cell(22,7,utf8_decode($reg->plazomeses),0,0,'L',true);

						$pdf->Cell(35,7,utf8_decode('CUOTA INICIAL: '),0,0);
						$pdf->Cell(0,7,utf8_decode($reg->cuotainicial),0,1,'L',true);

						$pdf->Cell(38,7,utf8_decode('FECHA CONTRATO: '),'B',0);
						$pdf->Cell(26,7,utf8_decode($reg->fechacontrato),'B',0,'L',true);

						$pdf->Cell(43,7,utf8_decode('INCREMENTO PLAZO: '),'B',0);
						$pdf->Cell(22,7,utf8_decode($reg->incrementoplazo),'B',0,'L',true);

						$pdf->Cell(35,7,utf8_decode('FECHA DE PAGO: '),'B',0);
						$pdf->Cell(0,7,utf8_decode($reg->fechapago),'B',1,'L',true);

					$sql3 = "SELECT cobranza.idcobranza, cobranza.idcontrato, cobranza.fechacobranza, cobranza.nrorecibo, cobranzadetalle.nrocuota, cobranzadetalle.monto, cobranzadetalle.acuenta, cobranza.concepto, cobranza.tipopago, cobranza.observaciones, cobranza.nombre
					           FROM cobranza
					           INNER JOIN cobranzadetalle ON cobranza.idcobranza = cobranzadetalle.idcobranza 
					           WHERE cobranza.idcontrato = $idcontrato;";

					$query3 = $conexion->query($sql3);

					//Títulos que llevará la cabecera
					$miCabecera = array(
									array('encabezado1'=>'', 'encabezado2'=>'NRO', 'encabezado3'=>'NRO', 'encabezado4'=>'TOTAL', 'encabezado5'=>'OBJETO DEL', 'encabezado6'=>'SALDO','encabezado7'=>'TIPO DE', 'encabezado8'=>'', 'encabezado21'=>'FECHA', 'encabezado22'=>'RECIBO', 'encabezado23'=>'CUOTA', 'encabezado24'=>'PAGO', 'encabezado25'=>'PAGO', 'encabezado26'=>'USD','encabezado27'=>'PAGO', 'encabezado28'=>'OBSERVACIONES', 'len1' => 20, 'len2' => 20, 'len3' => 15, 'len4' => 20, 'len5' => 30,'len6' => 20, 'len7' => 23, 'len8' => 43)
								);
					 
					//Métodos llamados con el objeto $pdf
					$x = 59;
					$pdf->cabeceraHorizontal($miCabecera,$x);

					$x = 73;
					$bandera = false;
					$totalpagado = 0;

					$saldo = ($reg->precio + $reg->incrementoplazo - $reg->cuotainicial);

		     		while ($reg3 = $query3->fetch_object()) {
		     			$saldo = $saldo - $reg3->acuenta;
		     			$totalpagado = $totalpagado + $reg3->acuenta;
		     			$misDatos = array(array('fechacobranza' => $reg3->fechacobranza,'nrorecibo' => $reg3->nrorecibo, 'nrocuota' => $reg3->nrocuota, 'monto' => $reg3->acuenta, 'concepto' => $reg3->concepto, 'saldo' => number_format($saldo,2), 'tipopago' => $reg3->tipopago, 'observacion' => $reg3->observaciones, 'len1' => 20, 'len2' => 20, 'len3' => 15, 'len4' => 20, 'len5' => 30, 'len6' => 20,'len7' => 23, 'len8' => 43) );
						$pdf->datosHorizontal($misDatos,$x,$bandera);
						$x = $x + 7;
						$bandera = !$bandera;
						if ($x>250) {
							$pdf->AddPage();
							$x = 40;
							$pdf->cabeceraHorizontal($miCabecera,$x);
							$x = 53;
						}

		     		}

	     			$misDatos = array(array('totalpagado' => $totalpagado,'saldo' => $saldo) );
					$pdf->datosTotales($misDatos,$x);

					//enviamos cabezales http para no tener problemas
					header("Content-Transfer-Encoding", "binary");
					header('Cache-Control: maxage=3600'); 
					header('Pragma: public');
					//			$pdf->Output('recibos.pdf','D');
					///$pdf->Output();
					$nombrearchivo = '../Files/Pdf/Kardex. Contrato Nro. ' . $reg->nrocontrato . '.pdf';
					$pdf->Output($nombrearchivo,'F');
			};

			return;
		}


		public function CrearReciboRecargo($idcuota) {

			global $conexion;
			global $fechacobranza;
			global $nrcobranza;

			$sqlcuota = "select * from cuota where idcuota = $idcuota";

			$querycuota = $conexion->query($sqlcuota);
			$concepto = '';
			$acuenta = 0;

     		while ($regcuota = $querycuota->fetch_object()) {
     			$concepto = $regcuota->concepto;
     			$acuenta = $regcuota->acuenta;
     		}

			$sqlcobranzadetalle = "select * from cobranzadetalle where idcuota = $idcuota";

			$querycobranzadetalle = $conexion->query($sqlcobranzadetalle);
			$idcobranza = 0;

     		while ($regcobranzadetalle = $querycobranzadetalle->fetch_object()) {
     			$idcobranza = $regcobranzadetalle->idcobranza;
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
		     			$titulo = utf8_decode('Recibo Oficial Recargo Adicional');
//						$pdf = new PDFRP('P','mm',array(240,180));
						$pdf = new PDFRR('P','mm','LETTER');
						$pdf->AliasNbPages();
						$pdf->AddPage();
						$pdf->SetFont('Arial','B',24);
						$pdf->Cell(0,15,$titulo,'TB',0,'C');
						$pdf->Ln(20);
						$nfila = 25;

						$pdf->SetFont('Arial','',14);
						$pdf->Cell(0,10,utf8_decode($reg->concobranza ),0,1,'C');
						$pdf->Ln(2);
						$pdf->Cell(20,10,utf8_decode('Hemos recibido de: '),0,0);
			            $pdf->Cell($nfila);
						$pdf->Cell(10,10,utf8_decode($reg->nomadquiriente . ' ' . $reg->apeadquiriente),0,1);

						$pdf->Cell(20,10,utf8_decode('La suma de:'),0,0);
			            $pdf->Cell($nfila);

							$pdf->Cell(10,10,trim($this->valorEnLetras($acuenta,'Dolares')),0,1);
				            $pdf->Cell($nfila+20);
							$pdf->Cell(10,10,'(USD ' . $acuenta . ')' ,0,1,'L');

						$pdf->Cell(20,10,utf8_decode('Contrato Nro: ' ),0,0);
			            $pdf->Cell($nfila);
						$pdf->Cell(20,10,utf8_decode($reg->nrocontrato ),0,1);
						$pdf->Cell(20,10,utf8_decode('Concepto:'),0,0);
			            $pdf->Cell($nfila);
						$pdf->Cell(10,10, $concepto,0,1);
						$pdf->Cell(20,10,utf8_decode('Observaciones:'),0,0);
			            $pdf->Cell($nfila);
						$pdf->Cell(10,10,utf8_decode($reg->obscobranza ),0,1);





						$sqldetallecobranza = "select cd.idcontrato, cd.monto, cd.acuenta, cd.saldo, cb.fechacobranza, cb.nrorecibo, cb.tipopago from cobranzadetalle cd left join cobranza cb on cd.idcobranza = cb.idcobranza where cd.idcuota=$idcuota";

						$querydetallecobranza = $conexion->query($sqldetallecobranza);

						$pdf->Cell(0,10,utf8_decode('PAGO(S) REALIZADO(S)'),'B',1,'C');
						$pdf->Cell(10,10,utf8_decode('FECHA DE'),0,0);
			            $pdf->Cell($nfila);
						$pdf->Cell(10,10,utf8_decode('NUMERO DE'),0,0);
			            $pdf->Cell($nfila);
						$pdf->Cell(10,10,utf8_decode('TIPO DE'),0,0);
			            $pdf->Cell($nfila);
						$pdf->Cell(10,10,utf8_decode('MONTO A'),0,0);
			            $pdf->Cell($nfila);
						$pdf->Cell(10,10,utf8_decode('TOTAL'),0,0);
			            $pdf->Cell($nfila);
						$pdf->Cell(10,10,utf8_decode(''),0,1);

						$pdf->Cell(10,1,utf8_decode('PAGO'),0,0);
			            $pdf->Cell($nfila);
						$pdf->Cell(10,1,utf8_decode('RECIBO'),0,0);
			            $pdf->Cell($nfila);
						$pdf->Cell(10,1,utf8_decode('PAGO'),0,0);
			            $pdf->Cell($nfila);
						$pdf->Cell(10,1,utf8_decode('PAGAR'),0,0);
			            $pdf->Cell($nfila);
						$pdf->Cell(10,1,utf8_decode('PAGADO'),0,0);
			            $pdf->Cell($nfila);
						$pdf->Cell(10,1,utf8_decode('SALDO'),0,1);
						$pdf->Cell(0,3,utf8_decode(''),'B',1);

			            $nAltoFila = 7;
			     		while ($regdetallecobranza = $querydetallecobranza->fetch_object()) {
							$pdf->Cell(10,$nAltoFila,utf8_decode($regdetallecobranza->fechacobranza),0,0);
				            $pdf->Cell($nfila);
							$pdf->Cell(10,$nAltoFila,utf8_decode($regdetallecobranza->nrorecibo),0,0);
				            $pdf->Cell($nfila);
							$pdf->Cell(10,$nAltoFila,utf8_decode($regdetallecobranza->tipopago=='E' ? 'EFECTIVO' : ($regdetallecobranza->tipopago=='C' ? 'CHEQUE' : 'BANCO') ),0,0);
				            $pdf->Cell($nfila+15);
							$pdf->Cell(10,$nAltoFila,$regdetallecobranza->monto,0,0,'R');
				            $pdf->Cell($nfila-3);
							$pdf->Cell(10,$nAltoFila,$regdetallecobranza->acuenta,0,0,'R');
				            $pdf->Cell($nfila-3);
							$pdf->Cell(10,$nAltoFila,$regdetallecobranza->saldo,0,1,'R');
							IF ($nAltoFila==10) {
								$nAltoFila = 5;
							}
			     		}





						$pdf->Cell(10,10,utf8_decode(''),0,1);
						$pdf->Ln(4);
						$pdf->Cell(110,10,'USUARIO',0,0,'C');
						$pdf->Cell(80,10,'CLIENTE ',0,1,'C');
						$pdf->Cell(110,2,utf8_decode($reg3->usuario),0,1,'C');

						//enviamos cabezales http para no tener problemas
						header("Content-Transfer-Encoding", "binary");
						header('Cache-Control: maxage=3600'); 
						header('Pragma: public');
						//			$pdf->Output('recibos.pdf','D');
						///$pdf->Output();
						$pdf->Output('../Files/Pdf/Recibo de Recargo. ' . trim($idcuota) . '.pdf','F');
/*						
						$enlace = '../Files/Pdf/Certificado de Obito.pdf'; 
						$tam = filesize($enlace);
						header("Content-type: application/pdf");
						header("Content-Length: $tam"); 
						header("Content-Disposition: inline; filename=proyecto.pdf");
						$file='./ficheros/proyecto.pdf';
						readfile($enlace);
*/
	     		}
			};

			return;
		}


























































		public function CrearKardex2($idcontrato) {

			global $conexion;

			$sqldatocontrato = "select * 
								from vcontrato c 
								where idcontrato = $idcontrato";

			$querydatocontrato = $conexion->query($sqldatocontrato);

     		while ($reg = $querydatocontrato->fetch_object()) {
		     			$titulo = utf8_decode('KARDEX');
		     			$titulo2 = utf8_decode('(Expresado en dólares)');
						$pdf = new PDFKardex2('P','mm','LETTER');
						$pdf->AliasNbPages();
						$pdf->AddPage();

						$pdf->SetFont('Arial','',10);
				        $pdf->SetFillColor(229, 229, 229); //Gris tenue de cada fila
				        $pdf->SetTextColor(3, 3, 3); //Color del texto: Negro

						$pdf->Cell(38,7,utf8_decode('ADQUIRIENTE: '),'T',0);
						$pdf->Cell(91,7,utf8_decode(utf8_decode($reg->nomadquiriente . ' ' . $reg->apeadquiriente)),'T',0,'L',true);

						$pdf->Cell(35,7,utf8_decode('CONTRATO Nº: '),'T',0);
						$pdf->Cell(0,7,utf8_decode($reg->nrocontrato),'T',1,'L',true);

						$pdf->Cell(38,7,utf8_decode('PRECIO US$: '),0,0);
						$pdf->Cell(26,7,utf8_decode($reg->precio),0,0,'L',true);

						$pdf->Cell(43,7,utf8_decode('PLAZO EN MESES: '),0,0);
						$pdf->Cell(22,7,utf8_decode($reg->plazomeses),0,0,'L',true);

						$pdf->Cell(35,7,utf8_decode('CUOTA INICIAL: '),0,0);
						$pdf->Cell(0,7,utf8_decode($reg->cuotainicial),0,1,'L',true);

						$pdf->Cell(38,7,utf8_decode('FECHA CONTRATO: '),'B',0);
						$pdf->Cell(26,7,utf8_decode($reg->fechacontrato),'B',0,'L',true);

						$pdf->Cell(43,7,utf8_decode('INCREMENTO PLAZO: '),'B',0);
						$pdf->Cell(22,7,utf8_decode($reg->incrementoplazo),'B',0,'L',true);

						$pdf->Cell(35,7,utf8_decode('FECHA DE PAGO: '),'B',0);
						$pdf->Cell(0,7,utf8_decode($reg->fechapago),'B',1,'L',true);

					$sql3 = "SELECT cobranza.idcobranza, cobranza.idcontrato, cobranza.fechacobranza, cobranza.nrorecibo, cobranzadetalle.nrocuota, cobranzadetalle.monto, cobranzadetalle.acuenta, cobranzadetalle.saldo, cobranza.concepto, cobranza.tipopago, cobranza.observaciones, cobranza.nombre
					           FROM cobranza
					           INNER JOIN cobranzadetalle ON cobranza.idcobranza = cobranzadetalle.idcobranza 
					           WHERE cobranza.idcontrato = $idcontrato;";

					$query3 = $conexion->query($sql3);

					//Títulos que llevará la cabecera
					$miCabecera = array(
									array('encabezado1'=>'', 'encabezado2'=>'NRO', 'encabezado3'=>'NRO', 'encabezado4'=>'TOTAL', 'encabezado5'=>'TOTAL', 'encabezado6'=>'SALDO', 'encabezado7'=>'OBJETO DEL', 'encabezado8'=>'SALDO','encabezado9'=>'TIPO DE', 'encabezado21'=>'FECHA', 'encabezado22'=>'RECIBO', 'encabezado23'=>'CUOTA', 'encabezado24'=>'CUOTA', 'encabezado25'=>'PAGO', 'encabezado26'=>'CUOTA', 'encabezado27'=>'PAGO', 'encabezado28'=>'USD','encabezado29'=>'PAGO', 'len1' => 20, 'len2' => 20, 'len3' => 15, 'len4' => 20, 'len5' => 20, 'len6' => 20, 'len7' => 34,'len8' => 20, 'len9' => 27)
								);
					 
					//Métodos llamados con el objeto $pdf
					$x = 59;
					$pdf->cabeceraHorizontal($miCabecera,$x);

					$x = 73;
					$bandera = false;
					$totalpagado = 0;

					$saldo = ($reg->precio + $reg->incrementoplazo - $reg->cuotainicial);

		     		while ($reg3 = $query3->fetch_object()) {
		     			$saldo = $saldo - $reg3->acuenta;
		     			$totalpagado = $totalpagado + $reg3->acuenta;
		     			$misDatos = array(array('fechacobranza' => $reg3->fechacobranza,'nrorecibo' => $reg3->nrorecibo, 'nrocuota' => $reg3->nrocuota, 'monto' => $reg3->monto, 'acuenta' => $reg3->acuenta, 'saldocuota' => $reg3->saldo, 'concepto' => $reg3->concepto, 'saldo' => number_format($saldo,2), 'tipopago' => $reg3->tipopago, 'len1' => 20, 'len2' => 20, 'len3' => 15, 'len4' => 20, 'len5' => 20, 'len6' => 20, 'len7' => 34, 'len8' => 20,'len9' => 27) );
						$pdf->datosHorizontal($misDatos,$x,$bandera);
						$x = $x + 7;
						$bandera = !$bandera;
						if ($x>250) {
							$pdf->AddPage();
							$x = 40;
							$pdf->cabeceraHorizontal($miCabecera,$x);
							$x = 53;
						}

		     		}

	     			$misDatos = array(array('totalpagado' => $totalpagado,'saldo' => $saldo) );
					$pdf->datosTotales($misDatos,$x);

					//enviamos cabezales http para no tener problemas
					header("Content-Transfer-Encoding", "binary");
					header('Cache-Control: maxage=3600'); 
					header('Pragma: public');
					//			$pdf->Output('recibos.pdf','D');
					///$pdf->Output();
					$nombrearchivo = '../Files/Pdf/Kardex. Contrato Nro. ' . $reg->nrocontrato . '.pdf';
					$pdf->Output($nombrearchivo,'F');
			};

			return;
		}

		public function ListarContratosCompletos() {

			global $conexion;

			$sql = "select c.*, CONCAT(a.nombre,' ',a.apellidos) as adquiriente, CONCAT(e.nombre,' ',e.apellidos) as ejecutivoventa, m.razonsocial, s.nombre as sector, l.numero as numlote, l.fila as fila, l.columna as columna,d.nombre as difunto,i.fechafallecimiento, i.fechainhumacion 
				from contrato c left join adquiriente a on c.idadquiriente = a.idadquiriente 
					 left join ejecutivoventa e on c.idejecutivoventa = e.idejecutivoventa 
					 left join cementerio m on c.idcementerio = m.idcementerio 
					 left join sector s on c.idsector = s.idsector 
					 left join lote l on c.idlote = l.idlote  
					 left join inhumacion i on c.idcontrato = i.idcontrato 
					 left join difunto d on i.iddifunto=d.iddifunto 
				where c.precio > 0  
				order by c.idcontrato desc";

			$query = $conexion->query($sql);

			return $query;

		}

		public function ActualizarVencidas() {
			global $conexion;

			$hoy = date("Y-m-d");
			$sql = "UPDATE cuota set estado='V' where saldo>0 and fechalimite<'$hoy'";
			$query = $conexion->query($sql);

			return $query;

		}

















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
	$I11 = "/100 Centimos "; 
//	$I11 = "/100 M.N. "; 

	$C3 = $signo.$H6.$I6.$H7.$H8.$I8.$H9.$H10.$I10.' con '.$H11.$I11; 

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


	}

