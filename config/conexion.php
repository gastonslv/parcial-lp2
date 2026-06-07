<?php
// ============================================================
// ARCHIVO DE CONEXIÓN Y FUNCIONES PRINCIPALES
// Proyecto: Concesionario de Autos
// ============================================================

// Función que establece la conexión con la base de datos
function conexion() {
    $host = '127.0.0.1:3306'; // localhost
    $usuario = 'root';
    $contra = '';
    $bd = 'concesionario_db';

    $linkConexion = mysqli_connect($host, $usuario, $contra, $bd);
    return $linkConexion;
}

// ============================================================
// FUNCIONES DE USUARIOS
// ============================================================

// Busca un usuario por email y contraseña para el login
// La contraseña se compara con MD5 (ver documentación en el .sql)
function login($conexion, $email, $contra) {
    $consulta = "SELECT u.id, u.nombre, u.apellido, u.email, u.rol
                 FROM usuarios u
                 WHERE u.email = '$email'
                 AND u.password = MD5('$contra')"; // u es el apodo para usuarios

    $rs = mysqli_query($conexion, $consulta);
    $data = mysqli_fetch_array($rs);

    $usuarioLog = array();

    if (!empty($data)) {
        $usuarioLog['id']       = $data['id'];
        $usuarioLog['nombre']   = $data['nombre'];
        $usuarioLog['apellido'] = $data['apellido'];
        $usuarioLog['email']    = $data['email'];
        $usuarioLog['rol']      = $data['rol'];
    }

    return $usuarioLog;
}

// ============================================================
// FUNCIONES DE AUTOS
// ============================================================

// Trae todos los autos (para el administrador)
function listarAutos($conexion) {
    $consulta = "SELECT a.id, a.marca, a.modelo, a.anio, a.precio, 
                        a.color, a.kilometraje, a.estado,
                        u.nombre as vendedor_nombre, u.apellido as vendedor_apellido
                 FROM autos a
                 LEFT JOIN usuarios u ON a.id_vendedor = u.id";

    $rs = mysqli_query($conexion, $consulta);
    $autos = array();
    $i = 0;

    while ($data = mysqli_fetch_array($rs)) {
        $autos[$i]['id']                 = $data['id'];
        $autos[$i]['marca']              = $data['marca'];
        $autos[$i]['modelo']             = $data['modelo'];
        $autos[$i]['anio']               = $data['anio'];
        $autos[$i]['precio']             = $data['precio'];
        $autos[$i]['color']              = $data['color'];
        $autos[$i]['kilometraje']        = $data['kilometraje'];
        $autos[$i]['estado']             = $data['estado'];
        $autos[$i]['vendedor_nombre']    = $data['vendedor_nombre'];
        $autos[$i]['vendedor_apellido']  = $data['vendedor_apellido'];
        $i++;
    }

    return $autos;
}

// Trae solo los autos asignados a un vendedor específico
function listarAutosPorVendedor($conexion, $idVendedor) {
    $consulta = "SELECT a.id, a.marca, a.modelo, a.anio, a.precio,
                        a.color, a.kilometraje, a.estado
                 FROM autos a
                 WHERE a.id_vendedor = $idVendedor";

    $rs = mysqli_query($conexion, $consulta);
    $autos = array();
    $i = 0;

    while ($data = mysqli_fetch_array($rs)) {
        $autos[$i]['id']          = $data['id'];
        $autos[$i]['marca']       = $data['marca'];
        $autos[$i]['modelo']      = $data['modelo'];
        $autos[$i]['anio']        = $data['anio'];
        $autos[$i]['precio']      = $data['precio'];
        $autos[$i]['color']       = $data['color'];
        $autos[$i]['kilometraje'] = $data['kilometraje'];
        $autos[$i]['estado']      = $data['estado'];
        $i++;
    }

    return $autos;
}

// Busca un auto por su ID (para editar)
function buscarAuto($conexion, $id) {
    $consulta = "SELECT * FROM autos WHERE id = $id";
    $rs = mysqli_query($conexion, $consulta);
    $data = mysqli_fetch_array($rs);

    $auto = array();

    if (!empty($data)) {
        $auto['id']          = $data['id'];
        $auto['marca']       = $data['marca'];
        $auto['modelo']      = $data['modelo'];
        $auto['anio']        = $data['anio'];
        $auto['precio']      = $data['precio'];
        $auto['color']       = $data['color'];
        $auto['kilometraje'] = $data['kilometraje'];
        $auto['estado']      = $data['estado'];
        $auto['id_vendedor'] = $data['id_vendedor'];
    }

    return $auto;
}

// Inserta un auto nuevo
function crearAuto($conexion) {
    $consulta = "INSERT INTO autos (marca, modelo, anio, precio, color, kilometraje, estado, id_vendedor)
                 VALUES (
                     '{$_POST['marca']}',
                     '{$_POST['modelo']}',
                     {$_POST['anio']},
                     {$_POST['precio']},
                     '{$_POST['color']}',
                     {$_POST['kilometraje']},
                     '{$_POST['estado']}',
                     {$_POST['id_vendedor']}
                 )";

    if (mysqli_query($conexion, $consulta)) {
        return true;
    } else {
        return false;
    }
}

// Modifica un auto existente
function modificarAuto($conexion, $id) {
    $consulta = "UPDATE autos
                 SET marca        = '{$_POST['marca']}',
                     modelo       = '{$_POST['modelo']}',
                     anio         = {$_POST['anio']},
                     precio       = {$_POST['precio']},
                     color        = '{$_POST['color']}',
                     kilometraje  = {$_POST['kilometraje']},
                     estado       = '{$_POST['estado']}'
                 WHERE id = $id";

    if (mysqli_query($conexion, $consulta)) {
        return true;
    } else {
        return false;
    }
}

// Elimina un auto por su ID
function eliminarAuto($conexion, $id) {
    $consulta = "DELETE FROM autos WHERE id = $id";

    if (mysqli_query($conexion, $consulta)) {
        return true;
    } else {
        return false;
    }
}

// ============================================================
// FUNCIONES DE CONSULTAS
// ============================================================

// Trae todas las consultas (para el administrador)
function listarConsultas($conexion) {
    $consulta = "SELECT c.id, c.mensaje, c.fecha, c.estado,
                        u.nombre as cliente_nombre, u.apellido as cliente_apellido,
                        a.marca, a.modelo,
                        v.nombre as vendedor_nombre, v.apellido as vendedor_apellido
                 FROM consultas c
                 JOIN usuarios u ON c.id_cliente = u.id
                 JOIN autos a ON c.id_auto = a.id
                 LEFT JOIN usuarios v ON c.id_vendedor = v.id";

    $rs = mysqli_query($conexion, $consulta);
    $consultas = array();
    $i = 0;

    while ($data = mysqli_fetch_array($rs)) {
        $consultas[$i]['id']                 = $data['id'];
        $consultas[$i]['mensaje']            = $data['mensaje'];
        $consultas[$i]['fecha']              = $data['fecha'];
        $consultas[$i]['estado']             = $data['estado'];
        $consultas[$i]['cliente_nombre']     = $data['cliente_nombre'];
        $consultas[$i]['cliente_apellido']   = $data['cliente_apellido'];
        $consultas[$i]['marca']              = $data['marca'];
        $consultas[$i]['modelo']             = $data['modelo'];
        $consultas[$i]['vendedor_nombre']    = $data['vendedor_nombre'];
        $consultas[$i]['vendedor_apellido']  = $data['vendedor_apellido'];
        $i++;
    }

    return $consultas;
}

// Trae solo las consultas de un cliente específico
function listarConsultasPorCliente($conexion, $idCliente) {
    $consulta = "SELECT c.id, c.mensaje, c.fecha, c.estado,
                        a.marca, a.modelo
                 FROM consultas c
                 JOIN autos a ON c.id_auto = a.id
                 WHERE c.id_cliente = $idCliente";

    $rs = mysqli_query($conexion, $consulta);
    $consultas = array();
    $i = 0;

    while ($data = mysqli_fetch_array($rs)) {
        $consultas[$i]['id']      = $data['id'];
        $consultas[$i]['mensaje'] = $data['mensaje'];
        $consultas[$i]['fecha']   = $data['fecha'];
        $consultas[$i]['estado']  = $data['estado'];
        $consultas[$i]['marca']   = $data['marca'];
        $consultas[$i]['modelo']  = $data['modelo'];
        $i++;
    }

    return $consultas;
}

// Trae las consultas asignadas a un vendedor específico
function listarConsultasPorVendedor($conexion, $idVendedor) {
    $consulta = "SELECT c.id, c.mensaje, c.fecha, c.estado,
                        u.nombre as cliente_nombre, u.apellido as cliente_apellido,
                        a.marca, a.modelo
                 FROM consultas c
                 JOIN usuarios u ON c.id_cliente = u.id
                 JOIN autos a ON c.id_auto = a.id
                 WHERE c.id_vendedor = $idVendedor";

    $rs = mysqli_query($conexion, $consulta);
    $consultas = array();
    $i = 0;

    while ($data = mysqli_fetch_array($rs)) {
        $consultas[$i]['id']               = $data['id'];
        $consultas[$i]['mensaje']          = $data['mensaje'];
        $consultas[$i]['fecha']            = $data['fecha'];
        $consultas[$i]['estado']           = $data['estado'];
        $consultas[$i]['cliente_nombre']   = $data['cliente_nombre'];
        $consultas[$i]['cliente_apellido'] = $data['cliente_apellido'];
        $consultas[$i]['marca']            = $data['marca'];
        $consultas[$i]['modelo']           = $data['modelo'];
        $i++;
    }

    return $consultas;
}

// Inserta una nueva consulta desde el formulario del cliente
function crearConsulta($conexion, $idCliente) {
    $consulta = "INSERT INTO consultas (id_cliente, id_auto, id_vendedor, mensaje)
                 VALUES (
                     $idCliente,
                     {$_POST['id_auto']},
                     {$_POST['id_vendedor']},
                     '{$_POST['mensaje']}'
                 )";

    if (mysqli_query($conexion, $consulta)) {
        return true;
    } else {
        return false;
    }
}
?>