<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//Conecta la base de datos con usuario, contraseña y nombre de la DB
$server = "localhost";
$user = "root";
$password ="";
$db = "empleados";

$conexionDB =new mysqli($server,$user,$password,$db);

//Consulta datos y recepciona una clave para consultar dichos datos con dicha clave
if(isset($_GET["consultar"])){
    $sqlEmpleados =mysqli_query($conexionDB,"SELECT * FROM empleados WHERE id=".$_GET["consultar"]);

    if(mysqli_num_rows($sqlEmpleados)>0){
        $empleados = mysqli_fetch_all($sqlEmpleados,MYSQLI_ASSOC);
        echo json_encode($empleados);
        exit();
    }
    else{
        echo json_encode(["success"=>0]);
    }
}

//borrar pero se le debe enviar una clave (para borrador)
if(isset($_GET["borrar"])){
    $sqlEmpleados = mysqli_query($conexionDB, "DELETE FROM empleados WHERE id=".$_GET["borrar"]);

    if($sqlEmpleados){
        echo json_encode(["success"=>1]);
        exit();
    }
    else{
        echo json_encode(["success"=>0]);
    }
}

//insertar un nuevo registro y recepciona en metodo POST los datos de nombre y correo
if(isset($_GET["insertar"])){
    $data = json_decode(file_get_contents("php://input"));
    $nombre=$data->nombre;
    $correo=$data->correo;

    if(($correo!="")&&($nombre!="")){
        $sqlEmpleados = mysqli_query($conexionDB,"INSERT INTO empleados (nombre,correo) VALUES ('$nombre','$correo')");
        echo json_encode(["success"=>1]);
    }
    exit();
}

//actualzar datos pero recepciona datos de nombre,correo y una clave para realizar la actualizacion
if(isset($_GET["actualizar"])){
    $data = json_decode(file_get_contents("php://input"));

    $id=(isset($data->id))?$data->id:$_GET["actualizar"];
    $nombre=$data->nombre;
    $correo=$data->correo;

    $sqlEmpleados = mysqli_query($conexionDB,"UPDATE empleados SET nombre='$nombre', correo='$correo' WHERE id='$id'");
    echo json_encode(["success"=>1]);
    exit();
}

//Consulta todos los registros de la tabla empleados
$sqlEmpleados = mysqli_query($conexionDB, "SELECT * FROM empleados");

if(mysqli_num_rows($sqlEmpleados)>0){
    $empleados = mysqli_fetch_all($sqlEmpleados,MYSQLI_ASSOC);
    echo json_encode($empleados);
}
else{
    echo json_encode([["success"=>0]]);
}
?>