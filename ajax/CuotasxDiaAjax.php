<?php


	session_start();


	require_once "../model/Contrato.php";



	$objContrato = new Contrato();

    $fecha = $_GET["fecha"];
//    $fecha = date("2020-06-10");
//    $fecha = date("Y-m-d");


			$query_Tipo = $objContrato->ListarCuotasMora($fecha);

            $data = Array();

            $i = 1;
            $verReporte = true;

     		while ($reg = $query_Tipo->fetch_object()) {
     			if ($reg->monto==$reg->saldo) {
     				$color = "red";
     			} else {
     				$color = "blue";
     			}

     			$data[] = array(

     				"0"=>$i,

                    "1"=> $reg->documento,

                    "2"=>$reg->adquiriente,

                    "3"=>$reg->celular,

                    "4"=>$reg->telefono,

                    "5"=>'<p style="color:' . $color. '">' . $reg->nrocuota . '</p>',

                    "6"=>'<p style="color:' . $color. '">' . $reg->saldo . '</p>',

                    "7"=>$reg->ejecutivoventa,

                    "8"=>$reg->nrocontrato,

                    "9"=>$reg->precio);
/*
                    "9"=>'<button class="btn btn-primary" data-toggle="tooltip" title="Ver Reporte de Kardex" onclick="verKardex('. $reg->idcontrato . ',' . $reg->nrocontrato  .')"><i class="fa fa-print"></i> </button>');
*/
                $i++;

            }

            $results = array(

            "sEcho" => 1,

        	"iTotalRecords" => count($data),

        	"iTotalDisplayRecords" => count($data),

            "aaData"=>$data);

			echo json_encode($results);



