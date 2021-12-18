<?php


	session_start();


	require_once "../model/Contrato.php";



	$objContrato = new Contrato();



	switch ($_GET["op"]) {



		case "list":

			$query_Tipo = $objContrato->ListarContratosMora();

            $data = Array();

            $i = 1;
            $verReporte = true;

     		while ($reg = $query_Tipo->fetch_object()) {

     			$data[] = array(

     				"0"=>$i,

                    "1"=> $reg->documento,

                    "2"=>$reg->adquiriente,

                    "3"=>$reg->celular,

                    "4"=>$reg->telefono,

                    "5"=>'<p style="color:red">' . $reg->cancuotas . '</p>',

                    "6"=>'<p style="color:red">' . $reg->saldo . '</p>',

                    "7"=>$reg->ejecutivoventa,

                    "8"=>$reg->nrocontrato,

                    "9"=>$reg->precio,

                    "10"=>$reg->fechapago);

                $i++;

            }

            $results = array(

            "sEcho" => 1,

        	"iTotalRecords" => count($data),

        	"iTotalDisplayRecords" => count($data),

            "aaData"=>$data);

			echo json_encode($results);



			break;

		case "creaReporteKardex":

			$idcontrato = $_POST["idcontrato"];
//			$idcontrato = 10;

			$query_Tipo = $objContrato->CrearKardex($idcontrato);

			break;
        case "actualizarVencidas":

            $query_Tipo = $objContrato->ActualizarVencidas();

            break;

	}

