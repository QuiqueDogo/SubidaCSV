<?php
include 'php/conexiones.php';
$campanias = "SELECT id,nombre from audios_listado.campanias where statuscamp ='1'";
$querycampania = mysql_query($campanias,$conexion40);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/master.css">
    <title>Subida Esquema</title>
</head>

<body>
    <div class="Container">
        <div class="cajita">
            <table>
                <tr>
                    <td>Folio Cliente</td>
                    <td>Contacto</td>
                    <td>Telefono</td>
                    <td>Demas Datos</td>
                </tr>
            </table>
        </div>
        <form action="php/importacion.php" method='POST' enctype="multipart/form-data">
            <input type="file" name='import' id='import' accept=".csv">
            <select name="campanias" id="campanias">
                <option value="0" selected disabled>Selecciona la campañia</option>
                <?php 
    while ($datos = mysql_fetch_assoc($querycampania)) {
        ?>
                <option value="<?php echo $datos['id'];?>"><?php echo $datos['nombre'];?></option>
                <?php } ?>
            </select>
            <button type="submit">Importar</button>
        </form>
    </div>

</body>

</html>