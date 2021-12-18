<?php



	session_start();


	require_once "../model/Contrato.php";


	$objContrato = new Contrato();



	switch ($_GET["op"]) {


		case 'SaveOrUpdate':

			$idcontrato = $_POST["txtIdContrato"];

			$tipocuota = 'R';

			$nrocuota = 500;

			$concepto = $_POST["txtConcepto"];

			$fechalimite = $_POST["txtFechaLimite"];

			$monto = $_POST["txtMonto"];

			echo $_POST["txtIdCuota"];
			echo $_POST["txtIdCuota2"];

			if(empty($_POST["txtIdCuota2"])) {

				if($objContrato->RegistrarRecargo($idcontrato,$tipocuota,$nrocuota,$concepto,$fechalimite,$monto)) {

					echo "Recargo registrado correctamente";

				} else {

					echo "El Recargo no ha podido ser registrado.";

				}

			} else {

				$idcuota = $_POST["txtIdCuota2"];

				if($objContrato->ModificarRecargo($idcuota,$concepto,$fechalimite,$monto)) {

					echo "La informacion del Recargo ha sido actualizada";

				} else {

					echo "La informacion del Recargo no ha podido ser actualizada.";

				}

			}

			break;


		case "crearReciboRecargo":
			$id = $_POST["id"];

			$valor = $objContrato->CrearReciboRecargo($id);

			return $valor;

			break;

		case "delete":

			$id = $_POST["id"];

			$result = $objContrato->EliminarRecargo($id);

			if ($result) {

				echo "Eliminado Exitosamente";

			} else {

				echo "No fue Eliminado";

			}

			break;

		case "list":

			$query_Tipo = $objContrato->ListarRecargos();

            $data = Array();

            $i = 1;

     		while ($reg = $query_Tipo->fetch_object()) {

     			$data[] = array(

     				"0"=>$i,

                    "1"=>$reg->nrocontrato,

                    "2"=>$reg->adquiriente,

                    "3"=>$reg->fechalimite,

                    "4"=>$reg->concepto,

                    "5"=>$reg->monto,

                    "6"=>$reg->saldo,

                    "7"=> ($reg->estado=='P' ? 'PENDIENTE':$reg->estado=='V' ? 'VENCIDA':'CANCELADA'),

                    "8"=>'<button class="btn btn-warning" data-toggle="tooltip" title="Editar" onclick="cargarDataRecargo('.$reg->idcuota.','.$reg->idcontrato.',\''.$reg->nrocontrato.'\',\''.$reg->fechacontrato.'\',\''.$reg->adquiriente.'\',\''.$reg->cementerio.'\',\''.$reg->sector.'\',\''.$reg->lote.'\',\''.$reg->fila.'\',\''.$reg->columna.'\','.$reg->monto.',\''.$reg->fechalimite.'\',\''.$reg->concepto.'\')"><i class="fa fa-pencil"></i> </button>&nbsp;'.
     				'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarRecargo('.$reg->idcuota.')"><i class="fa fa-trash"></i> </button>&nbsp;' . 
     				($reg->estado=='C' ? '<button class="btn btn-primary" data-toggle="tooltip" title="Recibo de Recargo" onclick="reciboRecargo('.$reg->idcuota . ',\'' . trim($reg->nrocontrato) .'\')"><i class="fa fa-print"></i> </button>' : ''));
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

		                <td>'. $i .'</td>

		                <td>'. ($regCuota->tipocuota=='C' ? 'CONTRATO' : ($regCuota->tipocuota=='M' ? 'MANTENIMIENTO ANUAL' : 'NO DETERMINADO' ) ) .'</td>

		                <td>'.$regCuota->nrocuota.'</td>

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

		                <td>'.$regCuota->nrocuota.'</td>

		                <td>'. ($regCuota->tipocuota=='C' ? 'CONTRATO' : ($regCuota->tipocuota=='M' ? 'MANTENIMIENTO ANUAL' : 'NO DETERMINADO' ) ) .'</td>

		                <td>'.$regCuota->fechalimite.'</td>

		                <td>'.$regCuota->monto.'</td>

		                <td>'.$regCuota->acuenta.'</td>

		                <td>'.$regCuota->saldo.'</td>

		                <td>'. ($regCuota->estado=='P' ? 'PENDIENTE' : ($regCuota->estado=='V' ? 'VENCIDA' : ($regCuota->estado=='C' ? 'PAGADA' : 'NO DETERMINADO') ) ) .'</td>

		                <td> </td>

	                   </tr>';
                $i++;

            }

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

		                <td>'. ($regCuota->tipocuota=='C' ? 'CONTRATO' : ($regCuota->tipocuota=='M' ? 'MANTENIMIENTO ANUAL' : 'NO DETERMINADO' ) ) .'</td>

		                <td>'.$regCuota->nrocuota.'</td>

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

		$hosp = $obj->RegistrarCobranza($idcontrato, $nrorecibo, $tipopago, $incremento, $objetoincremento, $concepto, $observaciones, $nombre, $fechacobranza, $_POST["detalle"],$descuento,$monto,$tiporecibo,$emiterecibo,$montobs,$tasacambio);
      	if (true) {
           	echo 'Cobranza Registrada Correctamente...';
       	} else {
          	echo "No se ha podido registrar la Cobranza";
       	}


        break;

	}


























