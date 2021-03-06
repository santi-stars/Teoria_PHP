<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Listado de Registros</title>
</head>
<body>
<?php
// conectamos a la base de datos
include('connect-db.php');
// obtenemos los resultados de la base de datos mediante la consulta
try {
//configuramos el prepared statement
    $stmt = $dbh->prepare('SELECT * FROM players');
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    /* Visualizamos los datos en una tabla
    Primero mostramos los links necesarios para ver sin paginar o
    paginados. El parámetro ?page, nos indicará al tener valor 1 que es
    primera página de resultados posibles.
    */
    echo "<p><b>Ver todos</b> | <a href='view-paginated.php?page=1'>Ver
paginados</a></p>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr> <th>ID</th> <th>Nombre</th> <th>Apellido</th>
<th></th><th></th></tr>";
    foreach ($resultado as $player) {
// salida de contenidos de cada columna en una fila de la tabla
        echo "<tr>";
        echo '<td>' . $player['id'] . '</td>';
        echo '<td>' . $player['nombre'] . '</td>';
        echo '<td>' . $player['apellido'] . '</td>';
        echo '<td><a href="edit.php?id=' . $player['id'] .
            '">Editar</a></td>';
        echo '<td><a href="delete.php?id=' . $player['id'] .
            '">Eliminar</a></td>';
        echo "</tr>";
    }
// terminamos la tabla
    echo "</table>";
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage();
}
/* En la parte final de la página, mostramos un link para añadir un nuevo
registro*/
?>
<p><a href="new.php">Añadir un nuevo registro</a></p>
</body>
</html>