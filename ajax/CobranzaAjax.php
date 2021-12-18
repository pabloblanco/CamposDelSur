<?php


  
	session_start();


	require_once "../model/Cobranza.php";


	$objCobranza = new Cobranza();



	switch ($_GET["op"]) {


		case 'SaveOrUpdate':

			$idcontrato = $_POST["txtIdContrato"];

			$fechacobranza = $_POST["txtFechaCobranza"];

			if(empty($_POST["txtIdCobranza2"])){

				if($objCobranza->Registrar($idcontrato,$fechacobranza)){

					echo "Cobranza registrada correctamente";

				}else{

					echo "La Cobranza no ha podido ser registrada.";

				}

			}else{

				$idcobranza = $_POST["txtIdCobranza2"];

				if($objCobranza->Modificar($idcobranza,$idcontrato,$fechacobranza)){

					echo "La informacion de la Cobranza ha sido actualizada";

				}else{

					echo "La informacion de la Cobranza no ha podido ser actualizada.";

				}

			}

			break;


		case "delete":

			$id = $_POST["id"];

			$result = $objCobranza->Eliminar($id);

			if ($result) {

				echo "Eliminado Exitosamente";

			} else {

				echo "No fue Eliminado";

			}

			break;

		case "verTasa":

			$tasacambio = $objCobranza->ObtenerTasaCambio();

			echo $tasacambio;

			break;

		case "list":

			$tasacambio = $objCobranza->ObtenerTasaCambio();

			$query_Tipo = $objCobranza->ListarCobranzas();

            $data = Array();

            $i = 1;

     		while ($reg = $query_Tipo->fetch_object()) {

     			$data[] = array(

     				"0"=>$i,

                    "1"=>$reg->nrorecibo,

                    "2"=>$reg->nrocontrato,

                    "3"=>$reg->adquiriente,

                    "4"=>$reg->fechacobranza,

                    "5"=>$reg->monto,

					"6"=>($reg->estado=='activo' ? 'Activo':'Anulado'),
					
					"7"=>utf8_encode($reg->concepto),

					"8"=>utf8_encode($reg->observaciones),					

                    "9"=>'<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataCobranza('.$reg->idcobranza.','.$reg->idcontrato.',\''.$reg->fechacobranza.'\',\''.$reg->nrocontrato.'\',\''.$reg->fechacontrato.'\',\''.$reg->adquiriente.'\',\''.$reg->cementerio.'\',\''.$reg->sector.'\',\''.$reg->lote.'\',\''.$reg->fila.'\',\''.$reg->columna.'\','.$reg->monto.',\''.$reg->tiporecibo.'\','.$tasacambio.')"><i class="fa fa-pencil"></i> </button>&nbsp;'.
     				'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarCobranza('.$reg->idcobranza.')"' . ($reg->estado<>"anulado" ? '': ' disabled=""') . '><i class="fa fa-trash"></i> </button>&nbsp;'.
     				'<button class="btn btn-primary" data-toggle="tooltip" title="Recibo de Pago" onclick="reciboPago('.$reg->idcobranza . ',\'' . trim($reg->nrocontrato) .'\')"><i class="fa fa-print"></i> </button>');

                $i++;

            }

            $results = array(

            "sEcho" => 1,

        	"iTotalRecords" => count($data),

        	"iTotalDisplayRecords" => count($data),

            "aaData"=>$data);

			echo json_encode($results);


			break;

		case "listContrato":

	        require_once "../model/Contrato.php";
            
            $objContrato = new Contrato();

	        $query = $objContrato->ListarContratos();
			
			$data = Array();
	        
	        $i = 1;
	     	
	     	while ($regContrato = $query->fetch_object()) {

	     			$data[] = array(
	     				"0"=>'
							<input type="radio" name="optContratoBusqueda" data-nombre="' . $regContrato->nrocontrato . 
		                	'" data-cementerio="' . trim($regContrato->razonsocial) . 
		                	'" data-sector="' . trim($regContrato->sector) . 
		                	'" data-lote="' . trim($regContrato->nrolote) . 
		                	'" data-fila="' . trim($regContrato->fila) . 
		                	'" data-columna="' . trim($regContrato->columna) . 
		                	'" data-adquiriente="' . trim($regContrato->adquiriente) . 
		                	'" data-fechacontrato="' . $regContrato->fechacontrato . '" id="cont'.$regContrato->idcontrato.'" value="'.$regContrato->idcontrato.'" />',
	     				"1"=>$i,
						"2"=>$regContrato->nrocontrato,
						"3"=>$regContrato->adquiriente,
						"4"=>$regContrato->documento,
						"5"=>$regContrato->ejecutivoventa);

					$i++;
	            }

	            $results = array(
	            "sEcho" => 1,
	        	"iTotalRecords" => count($data),
	        	"iTotalDisplayRecords" => count($data),
	            "aaData"=>$data);
				echo json_encode($results);

	        break;
		case "listCuota":

			$idcontrato = $_POST["idcontrato"];

	        $queryCuota = $objCobranza->ListarCuotasPendientes($idcontrato);

	        $i = 1;

            $data = Array();

	        while ($regCuota = $queryCuota->fetch_object()) {

	            echo '<tr>
	            		<td>
                    <script type="text/javascript">
      							function changeColor(x)

      							{

      									if(x.style.background=="rgb(247, 211, 88)")

      									{

      											x.style.background="#5cb85c";

      									}else{

      											x.style.background="#5cb85c";

      									}

      									return false;

      							}
      							</script>
			                 <button type="button" class="btn btn-warning" name="optCuotaBusqueda[]" data-idcuota="'.$regCuota->idcuota.'" data-fechalimite="'.$regCuota->fechalimite.'" data-monto="'.$regCuota->monto.'" data-estado="'.$regCuota->estado.'" id="'.$regCuota->idcuota.'" value="'.$regCuota->idcuota.'"
                  				data-toggle="tooltip" title="Agregar al pago" onclick="AgregarPedCarritoCobranza('.$regCuota->idcuota.',\''.$regCuota->nrocuota.'\',\''.$regCuota->tipocuota.'\',\''.$regCuota->fechalimite.'\','.$regCuota->monto.','.$regCuota->acuenta.','.$regCuota->saldo.',\''.$regCuota->estado.'\');changeColor(this);" >
                  				<i class="fa fa-check" ></i> 
                  			</button>
                  		</td>

		                <td>'. $i . '</td>

		                <td>'. ($regCuota->tipocuota=='C' ? 'CONTRATO' : ($regCuota->tipocuota=='M' ? 'MANTENIMIENTO ANUAL' : ( $regCuota->tipocuota=='R' ? 'RECARGO' : 'NO DETERMINADO' )) ) .'</td>

		                <td>'. ($regCuota->nrocuota==0 ? 'Inicial':$regCuota->nrocuota) .'</td>

		                <td>'.$regCuota->fechalimite.'</td>

		                <td>'.$regCuota->monto.'</td>

		                <td>'.$regCuota->acuenta.'</td>

		                <td>'.$regCuota->saldo.'</td>

		                <td>'. ($regCuota->estado=='P' ? 'PENDIENTE' : ($regCuota->estado=='V' ? 'VENCIDA' : ($regCuota->estado=='C' ? 'PAGADA' : 'NO DETERMINADO') ) ) .'</td>

	                   </tr>';
                $i++;

            }

			break;
		case "verCuota":

			$idcontrato = $_POST["idcontrato"];
			$idcobranza = $_POST["idcobranza"];

	        $queryCuota = $objCobranza->ListarCuotasPagadas($idcobranza,$idcontrato);

	        $i = 1;

            $data = Array();

	        while ($regCuota = $queryCuota->fetch_object()) {

	            echo '<tr>
		                <td>'.$regCuota->idcuota.'</td>

		                <td>'. ($regCuota->nrocuota==0 ? 'Inicial':$regCuota->nrocuota) . '</td>

		                <td>'. ($regCuota->tipocuota=='C' ? 'CONTRATO' : ($regCuota->tipocuota=='M' ? 'MANTENIMIENTO ANUAL' : ( $regCuota->tipocuota=='R' ? 'RECARGO' : 'NO DETERMINADO' )) ) .'</td>

		                <td>'.$regCuota->fechalimite.'</td>

		                <td>'.$regCuota->monto.'</td>

		                <td><input type="number" value="'.$regCuota->acuenta.'" id="'.$regCuota->id.'" class="form-control" /></td>

		                <td>'.$regCuota->saldo.'</td>

		                <td>'. ($regCuota->estado=='P' ? 'PENDIENTE' : ($regCuota->estado=='V' ? 'VENCIDA' : ($regCuota->estado=='C' ? 'PAGADA' : 'NO DETERMINADO') ) ) .'</td>

		                <td><label class="btn btn-primary" title="Guardar" onclick="actualizarAbono(\''.$regCuota->id.'\',\''.$regCuota->idcuota.'\',\''.$regCuota->saldo.'\',\''.$regCuota->acuenta.'\')"><i class="fa fa-save"></i> </label></td>'.
	                   '</tr>';
                $i++;

            }

			break;

		case "crearReciboPago":
			$id = $_POST["id"];

			$objCobranza->CrearReciboPago($id);

			break;


		case "listPago":

	        $queryCuota = $objCobranza->ListarCuotasPendientes($idcontrato);

	        $i = 1;

            $data = Array();

	        while ($regCuota = $queryCuota->fetch_object()) {

	            echo '<tr>
	            		<td>
                    <script type="text/javascript">
      							function changeColor(x)

      							{

      									if(x.style.background=="rgb(247, 211, 88)")

      									{

      											x.style.background="#5cb85c";

      									}else{

      											x.style.background="#5cb85c";

      									}

      									return false;

      							}
      							</script>
			                 <button type="button" class="btn btn-warning" name="optCuotaBusqueda[]" data-idcuota="'.$regCuota->idcuota.'" data-fechalimite="'.$regCuota->fechalimite.'" data-monto="'.$regCuota->monto.'" data-estado="'.$regCuota->estado.'" id="'.$regCuota->idcuota.'" value="'.$regCuota->idcuota.'"
                  				data-toggle="tooltip" title="Agregar al pago" onclick="AgregarPedCarritoCobranza('.$regCuota->idcuota.',\''.$regCuota->nrocuota.'\',\''.$regCuota->tipocuota.'\',\''.$regCuota->fechalimite.'\','.$regCuota->monto.','.$regCuota->acuenta.','.$regCuota->saldo.',\''.$regCuota->estado.'\');changeColor(this);" >
                  				<i class="fa fa-check" ></i> 
                  			</button>
                  		</td>

		                <td>'. $i .'</td>

		                <td>'. ($regCuota->tipocuota=='C' ? 'CONTRATO' : ($regCuota->tipocuota=='M' ? 'MANTENIMIENTO ANUAL' : ( $regCuota->tipocuota=='R' ? 'RECARGO' : 'NO DETERMINADO' )) ) .'</td>

		                <td>'. ($regCuota->nrocuota==0 ? 'Inicial':$regCuota->nrocuota) .'</td>

		                <td>'.$regCuota->fechalimite.'</td>

		                <td>'.$regCuota->monto.'</td>

		                <td>'.$regCuota->acuenta.'</td>

		                <td>'.$regCuota->saldo.'</td>

		                <td>'. ($regCuota->estado=='P' ? 'PENDIENTE' : ($regCuota->estado=='V' ? 'VENCIDA' : ($regCuota->estado=='C' ? 'PAGADA' : 'NO DETERMINADO') ) ) .'</td>

	                   </tr>';
                $i++;

            }

			break;
    	case 'SaveFactura':
//		require_once "../CodigoControl/ControlCode.php";
//    	require_once "../model/Pedido.php";

    	$obj= new Cobranza();


        $idcontrato = $_POST["idcontrato"];
        $nrorecibo = $_POST["nrorecibo"];
	    $tipopago = $_POST["tipopago"];
	    $incremento = $_POST["incremento"];
	    $objetoincremento = $_POST["objetoincremento"];
	    $concepto = $_POST["concepto"];
	    $observaciones = $_POST["observaciones"];
	    $nombre = $_POST["nombre"];
        $fechacobranza = $_POST["fechacobranza"];
	    $descuento = $_POST["descuento"];
        $monto = $_POST["monto"];
        $tiporecibo = $_POST["tiporecibo"];
        $emiterecibo = $_POST["emiterecibo"];
        $montobs = $_POST["montobs"];
        $tasacambio = $_POST["tasacambio"];
        $usuario = $_POST["usuario"];
/*
        echo $idcontrato . '<br>';
        echo $nrorecibo . '<br>';
	    echo $tipopago . '<br>';
	    echo $incremento . '<br>';
	    echo $objetoincremento . '<br>';
	    echo $concepto . '<br>';
	    echo $observaciones . '<br>';
	    echo $nombre . '<br>';
        echo $fechacobranza . '<br>';
        return;
*/

//        detalle : detalle //son los productos

		$hosp = $obj->RegistrarCobranza($idcontrato, $nrorecibo, $tipopago, $incremento, $objetoincremento, $concepto, $observaciones, $nombre, $fechacobranza, $_POST["detalle"],$descuento,$monto,$tiporecibo,$emiterecibo,$montobs,$tasacambio, $usuario);
      	if (true) {
           	echo 'Cobranza Registrada Correctamente...';
       	} else {
          	echo "No se ha podido registrar la Cobranza";
       	}


		break;
	case "actAbono":
		$obj= new Cobranza();
		$id = $_POST["id"];
		$Abono = $_POST["Abono"];
		$idCuota = $_POST["idCuota"];
		$Saldo = $_POST["Saldo"];
		$AbonoAnt = $_POST["AbonoAnt"];

		$DifAbono = $AbonoAnt -$Abono;
		$Saldo = $Saldo + $DifAbono;
		if($Saldo < 0){
			echo "El abono actual supera el saldo restante";
		}else{
			$hosp = $obj->actualizaAbono($id,$Abono,$Saldo,$idCuota);
			echo $hosp;
		}
		break;
	case "actAbono":
		$obj= new Cobranza();
		$id = $_POST["id"];
		$hosp = $obj->actualizaAbono($id,$Abono,$Saldo,$idCuota);
		echo $hosp;
		break;
	case "getCabecera":
		$obj= new Cobranza();
		$id = $_POST["id"];
		$hosp = $obj->getCabecera($id);
		echo $hosp;
		break;
	case "ActCabecera":
		$obj= new Cobranza();
		$id = $_POST["id"];
		$NroRecibo = $_POST["NroRecibo"];
		$TipoPago = $_POST["TPago"];
		$FechaCobranza = $_POST["FecCob"];
		$Incremento = $_POST["Incremento"];
		$ObjetoIncremento = $_POST["ObjInc"];
		$Concepto = $_POST["Concepto"];
		$Nombre = $_POST["Nombre"];
		$Observaciones = $_POST["Obs"];
		$Descuento = $_POST["Desc"];
		$moneda = $_POST["Moneda"];
		$tasaCambio = $_POST["tasaCamb"];
		$totalPagarBs = $_POST["TPagaBs"];
		$totalPagar = $_POST["TPagar"];

		$hosp = $obj->updateCabecera($id,
									$NroRecibo,
									$TipoPago,
									$FechaCobranza,
									$Incremento,
									$ObjetoIncremento,
									$Concepto,
									$Nombre,
									$Observaciones,
									$Descuento,
									$moneda,
									$tasaCambio,
									$totalPagarBs,
									$totalPagar);
		echo $hosp;
		break;
		case "ifReceiptNumberExist":
			$id = $_POST["receiptNumber"];
			$query_Tipo = $objCobranza->Buscar($id);
			$i = 1;

     		while ($reg = $query_Tipo->fetch_object()) {

     			$recibo = $reg->nrorecibo;

                $i++;

            }
			if ($id == $recibo) {
				echo 1;
			} else {
				echo 0;
			}
		break;
	}