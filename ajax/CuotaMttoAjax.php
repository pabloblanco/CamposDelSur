<?php



	session_start();



	require_once "../model/CuotaMtto.php";
	require_once "../model/Inhumacion.php";


	$objCuotaMtto = new CuotaMtto();
	$objInhumacion = new Inhumacion();



	switch ($_GET["op"]) {

		case "delete":



			$id = $_POST["id"];

			$result = $objCuotaMtto->Eliminar($id);

			if ($result) {

				echo "Eliminado Exitosamente";

			} else {

				echo "No fue Eliminado";

			}

			break;



		case "list":

			$query_Tipo = $objCuotaMtto->Listar();

            $data = Array();

            $i = 1;

     		while ($reg = $query_Tipo->fetch_object()) {

     			$data[] = array(

     				"0"=>$i,

                    "1"=>$reg->nrocontrato,

                    "2"=>$reg->adquiriente,

                    "3"=>$reg->fechacontrato,

                    "4"=>substr($reg->fechalimite,0,4),

                    "5"=>$reg->nrocuota,

                    "6"=>$reg->fechalimite,

                    "7"=>$reg->monto,

                    "8"=>($reg->estado=='C' ? 'Cancelada':($reg->estado=='V' ? 'Vencida':'Pendiente')),

                    "9"=>'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarCuotaMtto('.$reg->idcuota.')"' . ($reg->estado<>"C" ? '': ' disabled=""') . '><i class="fa fa-trash"></i> </button>&nbsp;');

                $i++;

            }

            $results = array(

            "sEcho" => 1,

        	"iTotalRecords" => count($data),

        	"iTotalDisplayRecords" => count($data),

            "aaData"=>$data);

			echo json_encode($results);



			break;

		case "create":

			$query_Tipo = $objCuotaMtto->ListarContratos();

            $i = 1; 

     		while ($reg = $query_Tipo->fetch_object()) {

				if ($reg->nuevo=='si') { 

					$query_inhumacion = $objInhumacion->ListarInhumacion($reg->idcontrato);

		     		while ($reg2 = $query_inhumacion->fetch_object()) {
//						$valor = $objCuotaMtto->RegistrarCuotaMtto($reg2->idcontrato,$reg2->fechainhumacion);
						$valor = $objCuotaMtto->RegistrarCuotaMtto($reg->idcontrato,$reg->fechacontrato,'P1Y');
						$i = $i + 1;
		     		}
				} else {
					$valor = $objCuotaMtto->RegistrarCuotaMtto($reg->idcontrato,$reg->fechacontrato,'P2Y');
					$i = $i + 1;
				}
            };
            if ($i>1) {
//				echo ("Cuotas de Mantenimiento creadas correctamente. " . $i . " Cuotas procesadas");
				echo ("Cuotas de Mantenimiento creadas correctamente...");
            } else {
				echo "No se han Encontrado Contrato con las condiciones necesarias para Crear Cuotas de Mantenimiento.";
            }



			break;


	}

