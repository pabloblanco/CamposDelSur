<?php

	require "Conexion.php";

	class Planimetria {

		public function __construct() {

		}

		public function RevisarLotesReservados() {
			global $conexion;

			//$fechamesanterior = '2020-01-16';
	        $date1 = new DateTime;
	        $interval = 'P30D';
	//        $interval = 'P60D';

	        $date1->sub(new DateInterval($interval));
	        $fechamesanterior = $date1->format('Y-m-d');

			$sql = "UPDATE lote set idestadolote= 6, fechareserva= NULL, observaciones= ' ', nombrereserva = ' ', montoreserva = 0    
						WHERE fechareserva < '$fechamesanterior'";

			$query = $conexion->query($sql);

			return 1;

		}

		public function ModificarReserva($idlote, $nombrereserva, $montoreserva, $idestadolote, $tipomovimiento,$fechareserva,$observaciones) {

			global $conexion;

			$loginusuario = $_SESSION["login"];

			if ($tipomovimiento=='M') {
				$sql = "UPDATE lote set fechareserva= '$fechareserva', observaciones= '$observaciones', nombrereserva = '$nombrereserva', montoreserva = $montoreserva, loginactualizo = '$loginusuario'    
						WHERE idlote = $idlote";
			} else {
				if ($idestadolote==6) {
					$idestadolote = 7;
					$sql = "UPDATE lote set idestadolote=$idestadolote, fechareserva= '$fechareserva', observaciones= '$observaciones', nombrereserva = '$nombrereserva', montoreserva = $montoreserva, login = '$loginusuario' 
							WHERE idlote = $idlote";
				} else {
					$idestadolote = 6;
					$nombrereserva = ' ';
					$loginactualizo = ' ';
					$montoreserva = 0;
					$sql = "UPDATE lote set idestadolote=$idestadolote, fechareserva= '$fechareserva', observaciones= '$observaciones', nombrereserva = '$nombrereserva', montoreserva = $montoreserva, login = ' ', loginactualizo = ' ' 
							WHERE idlote = $idlote";
				};

			}

			$query = $conexion->query($sql);

			return $query;

		}

		public function Listar() {

			global $conexion;

			$sql = "select `lote`.`idlote` AS `idlote`,`lote`.`idsector` AS `idsector`,`lote`.`idcementerio` AS `idcementerio`,`lote`.`idtipolote` AS `idtipolote`,`lote`.`idestadolote` AS `idestadolote`,`lote`.`numero` AS `numlote`,`lote`.`fila` AS `fila`,`lote`.`columna` AS `columna`,`lote`.`observaciones` AS `observacioneslote`,lote.fechareserva,lote.nombrereserva,lote.montoreserva,lote.login,lote.loginactualizo, `cementerio`.`razonsocial` AS `cementerio`,`cementerio`.`tipodocumento` AS `tdcementerio`,`cementerio`.`numdocumento` AS `ndcementerio`,`cementerio`.`direccion` AS `dircementerio`,`cementerio`.`telefono` AS `telcementerio`,`cementerio`.`email` AS `emacementerio`,`cementerio`.`representante` AS `repcementerio`,`cementerio`.`logo` AS `logcementerio`,`cementerio`.`estado` AS `estcementerio`,`sector`.`nombre` AS `sector`,`sector`.`observaciones` AS `obssector`,`sector`.`precioni` AS `precioni`,`sector`.`precionf` AS `precionf`,`tipolote`.`nombre` AS `tipolote`,`tipolote`.`estado` AS `esttipolote`,`estadolote`.`nombre` AS `estadolote`,`estadolote`.`estado` AS `estestadolote` from ((((`lote` join `tipolote` on((`lote`.`idtipolote` = `tipolote`.`idtipolote`))) join `estadolote` on((`lote`.`idestadolote` = `estadolote`.`idestadolote`))) join `sector` on((`lote`.`idsector` = `sector`.`idsector`))) join `cementerio` on((`lote`.`idcementerio` = `cementerio`.`idcementerio`))) order by idlote desc";

//			$sql = "SELECT * FROM vlote where idestadolote=6 or idestadolote=7 order by idlote desc";

			$query = $conexion->query($sql);

			return $query;

		}

	}

