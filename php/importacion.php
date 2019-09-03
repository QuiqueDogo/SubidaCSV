	<?php 
	include 'conexiones.php';
	//cosas que ocupo 
	$base = $_FILES["import"];
	$campania = $_REQUEST['campanias'];
	$row = 1;
	$lines = file($base["tmp_name"] , FILE_IGNORE_NEW_LINES);
	
	//creo que necesitamos el ultimo id para insertarlos en info_adicional
	$maximoID = "SELECT MAX(IdRegistro)+1 as total from EsquemaGeneral.baseclientegeneral ";
	$queryID = mysql_query($maximoID,$conexion40);
	$idBasecliente = mysql_fetch_assoc($queryID);
	
	//consultas para insercion
	$sqlEsquemaGeneral = "INSERT INTO EsquemaGeneral.baseclientegeneral(IdRegistro,FolioUnico,Telefono,Contacto,lote,campania) VALUES ";
	$sqlInfoAdicional = "INSERT INTO EsquemaGeneral.info_adicional(`nombre_campo`,`valor_campo`,fk_cliente) VALUES ";
	
	//Leemos el archivo csv
	foreach ($lines as $key => $value) {
		$csv[$key] = str_getcsv(utf8_encode($value)) ; 	
	}
	
	//obtenemos la cabeceras de sobra para meter a info adicional 
	foreach ($csv[0] as $key => $value) {
		if($key >= 3){
			$cabeceras[] = $value;
		}
	}
	$conteocabeceras = sizeof($cabeceras);
	
	$n=0;
	foreach ($csv as $key => $value) {
		if ($key != 0) {
			$sqlEsquemaGeneral .= "('{$idBasecliente['total']}',";
			foreach ($value as $key2 => $value2) {
				//para esquemageneral
				if ($key2 <= 2) {
					$sqlEsquemaGeneral .= "'{$value2}',";
				}
				//para info adicional
				if ($key2 >= 3) {
					if($n == $conteocabeceras ){
						$n=0;
					}
					$sqlInfoAdicional .= "('".$cabeceras[$n]."','{$value2}','{$idBasecliente['total']}'),";
					$n++;
				}
			}
			$sqlEsquemaGeneral = substr($sqlEsquemaGeneral,0,-1);
			$sqlEsquemaGeneral .= ",'lote_{$campania}_".date("Y_m_d")."','{$campania}'),";
		}
	$idBasecliente['total']++;	
	}
	
	$sqlEsquemaGeneral = substr($sqlEsquemaGeneral,0,-1);
	$sqlInfoAdicional = substr($sqlInfoAdicional,0,-1);
	// // contador de filas
	// echo $sqlEsquemaGeneral.'<br>'.'<br>';
	// echo $sqlInfoAdicional.'<br>';
	// echo "<pre>";
	// print_r($cabeceras);
	// // print_r($csv);
	// echo "</pre>";	

	mysql_query($sqlEsquemaGeneral, $conexion40);
	mysql_query($sqlInfoAdicional, $conexion40);
	if (mysql_error()) {
		echo "Hubo un error";
	}else{
		echo "Registro insertado correctamente";
	}
	?>