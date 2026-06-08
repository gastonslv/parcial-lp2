<?php
// ============================================================
// ARCHIVO DE CONEXIÓN Y FUNCIONES PRINCIPALES
// Proyecto: Concesionaria de Autos
// ------------------------------------------------------------
// Acá viven TODAS las funciones que hablan con la base de datos.
// Cada página del sistema incluye este archivo y usa estas
// funciones en vez de escribir las consultas SQL a mano.
// ============================================================

// Establece la conexión con la base de datos MySQL.
// Devuelve el "link" de conexión que usan todas las demás funciones.
function conexion() {
    $host = '127.0.0.1:3306'; // localhost (servidor de la BD)
    $usuario = 'root';        // usuario de MySQL
    $contra = '';             // contraseña de MySQL (vacía en XAMPP)
    $bd = 'concesionario_db'; // nombre de la base de datos

    $linkConexion = mysqli_connect($host, $usuario, $contra, $bd);
    return $linkConexion;
}

// ============================================================
// FUNCIONES DE USUARIOS
// ============================================================

// Busca un usuario por email y contraseña para el login.
// Recibe: la conexión, el email y la contraseña en texto plano.
// Devuelve: un array con los datos del usuario si las credenciales
//           son correctas, o un array vacío si no coinciden.
function login($conexion, $email, $contra) {
    // Comparamos la contraseña escrita (encriptada con MD5) contra
    // la que está guardada en la BD, que también está en MD5.
    // Ejemplo: MD5('1234') → 'c4ca4238a0b923820dcc509a6f75849b'
    $consulta = "SELECT u.id, u.nombre, u.apellido, u.email, u.rol
                 FROM usuarios u
                 WHERE u.email = '$email'
                 AND u.password = MD5('$contra')"; // u es el apodo para usuarios

    $rs = mysqli_query($conexion, $consulta);
    $data = mysqli_fetch_array($rs);

    $usuarioLog = array();

    // Solo llenamos el array si la consulta encontró un usuario.
    if (!empty($data)) {
        $usuarioLog['id']       = $data['id'];
        $usuarioLog['nombre']   = $data['nombre'];
        $usuarioLog['apellido'] = $data['apellido'];
        $usuarioLog['email']    = $data['email'];
        $usuarioLog['rol']      = $data['rol'];
    }

    return $usuarioLog;
}

// Trae todos los usuarios del sistema (solo lo usa el admin).
// Devuelve: un array con todos los usuarios.
function listarUsuarios($conexion) {
    $consulta = "SELECT u.id, u.nombre, u.apellido, u.email, u.rol
                 FROM usuarios u";

    $rs = mysqli_query($conexion, $consulta);
    $usuarios = array();
    $i = 0;

    // Vamos guardando cada fila de la BD en el array $usuarios.
    while ($data = mysqli_fetch_array($rs)) {
        $usuarios[$i]['id']       = $data['id'];
        $usuarios[$i]['nombre']   = $data['nombre'];
        $usuarios[$i]['apellido'] = $data['apellido'];
        $usuarios[$i]['email']    = $data['email'];
        $usuarios[$i]['rol']      = $data['rol'];
        $i++;
    }

    return $usuarios;
}

// Busca un usuario por su ID (para precargar el formulario de editar).
// Devuelve: un array con los datos del usuario, o vacío si no existe.
function buscarUsuario($conexion, $id) {
    $consulta = "SELECT id, nombre, apellido, email, rol
                 FROM usuarios WHERE id = $id";
    $rs = mysqli_query($conexion, $consulta);
    $data = mysqli_fetch_array($rs);

    $usuario = array();

    if (!empty($data)) {
        $usuario['id']       = $data['id'];
        $usuario['nombre']   = $data['nombre'];
        $usuario['apellido'] = $data['apellido'];
        $usuario['email']    = $data['email'];
        $usuario['rol']      = $data['rol'];
    }

    return $usuario;
}

// Inserta un usuario nuevo con los datos que vienen del formulario.
// La contraseña se guarda encriptada con MD5, nunca en texto plano.
// Devuelve: true si se insertó bien, false si hubo error.
function crearUsuario($conexion) {
    $consulta = "INSERT INTO usuarios (nombre, apellido, email, password, rol)
                 VALUES (
                     '{$_POST['nombre']}',
                     '{$_POST['apellido']}',
                     '{$_POST['email']}',
                     MD5('{$_POST['password']}'),
                     '{$_POST['rol']}'
                 )";

    if (mysqli_query($conexion, $consulta)) {
        return true;
    } else {
        return false;
    }
}

// Modifica los datos de un usuario existente.
// Devuelve: true si se actualizó bien, false si hubo error.
function modificarUsuario($conexion, $id) {
    $consulta = "UPDATE usuarios
                 SET nombre   = '{$_POST['nombre']}',
                     apellido = '{$_POST['apellido']}',
                     email    = '{$_POST['email']}',
                     rol      = '{$_POST['rol']}'
                 WHERE id = $id";

    if (mysqli_query($conexion, $consulta)) {
        return true;
    } else {
        return false;
    }
}

// Elimina un usuario por su ID.
// Devuelve: true si se eliminó bien, false si hubo error.
function eliminarUsuario($conexion, $id) {
    $consulta = "DELETE FROM usuarios WHERE id = $id";

    if (mysqli_query($conexion, $consulta)) {
        return true;
    } else {
        return false;
    }
}

// ============================================================
// FUNCIONES DE AUTOS
// ============================================================

// Trae todos los autos (para el administrador, que ve todas las gamas).
// Devuelve: un array con todos los autos.
function listarAutos($conexion) {
    $consulta = "SELECT a.id, a.marca, a.modelo, a.anio, a.precio,
                        a.color, a.kilometraje, a.gama, a.estado
                 FROM autos a";

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
        $autos[$i]['gama']        = $data['gama'];
        $autos[$i]['estado']      = $data['estado'];
        $i++;
    }

    return $autos;
}

// Trae solo los autos de una gama específica (para los vendedores).
// Recibe: la conexión y la gama ('baja', 'media' o 'alta').
// Ejemplo: si el vendedor tiene rol "vendedor_baja", solo verá
// los autos con gama = 'baja' (Onix, Sandero, etc.). Un vendedor
// de gama alta NO debería ver esos autos, igual que un traumatólogo
// no ve las consultas de dermatología.
// Devuelve: un array con los autos de esa gama.
function listarAutosPorGama($conexion, $gama) {
    // Excluimos los autos vendidos: los vendedores no necesitan ver
    // autos que ya se vendieron, solo los que todavía pueden ofrecer.
    // Ejemplo: si el Onix (gama baja) ya está vendido, el vendedor_baja
    // no lo verá más en su listado.
    $consulta = "SELECT a.id, a.marca, a.modelo, a.anio, a.precio,
                        a.color, a.kilometraje, a.gama, a.estado
                 FROM autos a
                 WHERE a.gama = '$gama'
                 AND a.estado != 'vendido'";

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
        $autos[$i]['gama']        = $data['gama'];
        $autos[$i]['estado']      = $data['estado'];
        $i++;
    }

    return $autos;
}

// Busca un auto por su ID (para precargar el formulario de editar).
// Devuelve: un array con los datos del auto, o vacío si no existe.
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
        $auto['gama']        = $data['gama'];
        $auto['estado']      = $data['estado'];
    }

    return $auto;
}

// Inserta un auto nuevo con los datos que vienen del formulario.
// Devuelve: true si se insertó bien, false si hubo error.
function crearAuto($conexion) {
    $consulta = "INSERT INTO autos (marca, modelo, anio, precio, color, kilometraje, gama, estado)
                 VALUES (
                     '{$_POST['marca']}',
                     '{$_POST['modelo']}',
                     {$_POST['anio']},
                     {$_POST['precio']},
                     '{$_POST['color']}',
                     {$_POST['kilometraje']},
                     '{$_POST['gama']}',
                     '{$_POST['estado']}'
                 )";

    if (mysqli_query($conexion, $consulta)) {
        return true;
    } else {
        return false;
    }
}

// Modifica un auto existente.
// Devuelve: true si se actualizó bien, false si hubo error.
function modificarAuto($conexion, $id) {
    $consulta = "UPDATE autos
                 SET marca        = '{$_POST['marca']}',
                     modelo       = '{$_POST['modelo']}',
                     anio         = {$_POST['anio']},
                     precio       = {$_POST['precio']},
                     color        = '{$_POST['color']}',
                     kilometraje  = {$_POST['kilometraje']},
                     gama         = '{$_POST['gama']}',
                     estado       = '{$_POST['estado']}'
                 WHERE id = $id";

    if (mysqli_query($conexion, $consulta)) {
        return true;
    } else {
        return false;
    }
}

// Elimina un auto por su ID.
// Devuelve: true si se eliminó bien, false si hubo error.
function eliminarAuto($conexion, $id) {
    $consulta = "DELETE FROM autos WHERE id = $id";

    if (mysqli_query($conexion, $consulta)) {
        return true;
    } else {
        return false;
    }
}

// ============================================================
// FUNCIONES DE CLIENTES
// ============================================================

// Trae todos los clientes (cualquier usuario del sistema puede verlos).
// Devuelve: un array con todos los clientes.
function listarClientes($conexion) {
    $consulta = "SELECT c.id, c.nombre, c.apellido, c.telefono, c.email
                 FROM clientes c";

    $rs = mysqli_query($conexion, $consulta);
    $clientes = array();
    $i = 0;

    while ($data = mysqli_fetch_array($rs)) {
        $clientes[$i]['id']       = $data['id'];
        $clientes[$i]['nombre']   = $data['nombre'];
        $clientes[$i]['apellido'] = $data['apellido'];
        $clientes[$i]['telefono'] = $data['telefono'];
        $clientes[$i]['email']    = $data['email'];
        $i++;
    }

    return $clientes;
}

// Busca un cliente por su ID (para precargar el formulario de editar).
// Devuelve: un array con los datos del cliente, o vacío si no existe.
function buscarCliente($conexion, $id) {
    $consulta = "SELECT id, nombre, apellido, telefono, email
                 FROM clientes WHERE id = $id";
    $rs = mysqli_query($conexion, $consulta);
    $data = mysqli_fetch_array($rs);

    $cliente = array();

    if (!empty($data)) {
        $cliente['id']       = $data['id'];
        $cliente['nombre']   = $data['nombre'];
        $cliente['apellido'] = $data['apellido'];
        $cliente['telefono'] = $data['telefono'];
        $cliente['email']    = $data['email'];
    }

    return $cliente;
}

// Inserta un cliente nuevo con los datos del formulario.
// Devuelve: true si se insertó bien, false si hubo error.
function crearCliente($conexion) {
    $consulta = "INSERT INTO clientes (nombre, apellido, telefono, email)
                 VALUES (
                     '{$_POST['nombre']}',
                     '{$_POST['apellido']}',
                     '{$_POST['telefono']}',
                     '{$_POST['email']}'
                 )";

    if (mysqli_query($conexion, $consulta)) {
        return true;
    } else {
        return false;
    }
}

// Modifica un cliente existente.
// Devuelve: true si se actualizó bien, false si hubo error.
function modificarCliente($conexion, $id) {
    $consulta = "UPDATE clientes
                 SET nombre   = '{$_POST['nombre']}',
                     apellido = '{$_POST['apellido']}',
                     telefono = '{$_POST['telefono']}',
                     email    = '{$_POST['email']}'
                 WHERE id = $id";

    if (mysqli_query($conexion, $consulta)) {
        return true;
    } else {
        return false;
    }
}

// Elimina un cliente por su ID.
// Devuelve: true si se eliminó bien, false si hubo error.
function eliminarCliente($conexion, $id) {
    $consulta = "DELETE FROM clientes WHERE id = $id";

    if (mysqli_query($conexion, $consulta)) {
        return true;
    } else {
        return false;
    }
}

// ============================================================
// FUNCIONES DE VENTAS
// ============================================================

// Trae todas las ventas con los nombres del cliente, auto y usuario
// que la registró (lo usa el admin, que ve todas las ventas).
// Devuelve: un array con todas las ventas.
function listarVentas($conexion) {
    $consulta = "SELECT v.id, v.fecha, v.estado, v.observaciones,
                        c.nombre AS cliente_nombre, c.apellido AS cliente_apellido,
                        a.marca, a.modelo,
                        u.nombre AS usuario_nombre, u.apellido AS usuario_apellido
                 FROM ventas v
                 JOIN clientes c ON v.id_cliente = c.id
                 JOIN autos a ON v.id_auto = a.id
                 JOIN usuarios u ON v.id_usuario = u.id";

    $rs = mysqli_query($conexion, $consulta);
    $ventas = array();
    $i = 0;

    while ($data = mysqli_fetch_array($rs)) {
        $ventas[$i]['id']               = $data['id'];
        $ventas[$i]['fecha']            = $data['fecha'];
        $ventas[$i]['estado']           = $data['estado'];
        $ventas[$i]['observaciones']    = $data['observaciones'];
        $ventas[$i]['cliente_nombre']   = $data['cliente_nombre'];
        $ventas[$i]['cliente_apellido'] = $data['cliente_apellido'];
        $ventas[$i]['marca']            = $data['marca'];
        $ventas[$i]['modelo']           = $data['modelo'];
        $ventas[$i]['usuario_nombre']   = $data['usuario_nombre'];
        $ventas[$i]['usuario_apellido'] = $data['usuario_apellido'];
        $i++;
    }

    return $ventas;
}

// Trae solo las ventas registradas por un usuario específico.
// Recibe: la conexión y el id del usuario logueado.
// Ejemplo: un vendedor solo ve las ventas que él mismo cargó,
// no las de sus compañeros.
// Devuelve: un array con las ventas de ese usuario.
function listarVentasPorUsuario($conexion, $idUsuario) {
    $consulta = "SELECT v.id, v.fecha, v.estado, v.observaciones,
                        c.nombre AS cliente_nombre, c.apellido AS cliente_apellido,
                        a.marca, a.modelo,
                        u.nombre AS usuario_nombre, u.apellido AS usuario_apellido
                 FROM ventas v
                 JOIN clientes c ON v.id_cliente = c.id
                 JOIN autos a ON v.id_auto = a.id
                 JOIN usuarios u ON v.id_usuario = u.id
                 WHERE v.id_usuario = $idUsuario";

    $rs = mysqli_query($conexion, $consulta);
    $ventas = array();
    $i = 0;

    while ($data = mysqli_fetch_array($rs)) {
        $ventas[$i]['id']               = $data['id'];
        $ventas[$i]['fecha']            = $data['fecha'];
        $ventas[$i]['estado']           = $data['estado'];
        $ventas[$i]['observaciones']    = $data['observaciones'];
        $ventas[$i]['cliente_nombre']   = $data['cliente_nombre'];
        $ventas[$i]['cliente_apellido'] = $data['cliente_apellido'];
        $ventas[$i]['marca']            = $data['marca'];
        $ventas[$i]['modelo']           = $data['modelo'];
        $ventas[$i]['usuario_nombre']   = $data['usuario_nombre'];
        $ventas[$i]['usuario_apellido'] = $data['usuario_apellido'];
        $i++;
    }

    return $ventas;
}

// Cambia el estado de un auto en la base de datos.
// Se llama automáticamente desde las funciones de ventas para mantener
// sincronizado el estado del auto con el de su venta.
// Ejemplo: actualizarEstadoAuto($con, 5, 'vendido') → el auto con id=5 pasa a vendido.
// Devuelve: true si se actualizó bien, false si hubo error.
function actualizarEstadoAuto($conexion, $idAuto, $nuevoEstado) {
    $consulta = "UPDATE autos SET estado = '$nuevoEstado' WHERE id = $idAuto";
    if (mysqli_query($conexion, $consulta)) {
        return true;
    } else {
        return false;
    }
}

// Inserta una venta nueva y reserva el auto vinculado. El id_usuario lo
// ponemos desde la sesión (el usuario logueado), no desde el formulario,
// para que quede registrado quién la cargó.
// Recibe: la conexión y el id del usuario logueado.
// Devuelve: true si se insertó bien, false si hubo error.
function crearVenta($conexion, $idUsuario) {
    // Insertamos la venta.
    $consulta = "INSERT INTO ventas (id_cliente, id_auto, id_usuario, estado, observaciones)
                 VALUES (
                     {$_POST['id_cliente']},
                     {$_POST['id_auto']},
                     $idUsuario,
                     '{$_POST['estado']}',
                     '{$_POST['observaciones']}'
                 )";

    if (mysqli_query($conexion, $consulta)) {
        // Si la venta se insertó bien, reservamos el auto para que nadie
        // más pueda venderlo mientras esta venta esté activa.
        actualizarEstadoAuto($conexion, $_POST['id_auto'], 'reservado');
        return true;
    } else {
        return false;
    }
}

// Busca una venta por su ID, trayendo también el nombre del cliente
// y los datos del auto (para precargar el formulario de editar).
// Ejemplo: editar.php?id=3 → buscamos la venta con id = 3.
// Devuelve: un array con los datos de la venta, o vacío si no existe.
function buscarVenta($conexion, $id) {
    $consulta = "SELECT v.id, v.id_cliente, v.id_auto, v.id_usuario,
                        v.estado, v.observaciones, v.fecha,
                        c.nombre as cliente_nombre, c.apellido as cliente_apellido,
                        a.marca, a.modelo
                 FROM ventas v
                 JOIN clientes c ON v.id_cliente = c.id
                 JOIN autos a ON v.id_auto = a.id
                 WHERE v.id = $id";
    $rs = mysqli_query($conexion, $consulta);
    $data = mysqli_fetch_array($rs);
    $venta = array();
    if (!empty($data)) {
        $venta['id']               = $data['id'];
        $venta['id_cliente']       = $data['id_cliente'];
        $venta['id_auto']          = $data['id_auto'];
        $venta['id_usuario']       = $data['id_usuario'];
        $venta['estado']           = $data['estado'];
        $venta['observaciones']    = $data['observaciones'];
        $venta['fecha']            = $data['fecha'];
        $venta['cliente_nombre']   = $data['cliente_nombre'];
        $venta['cliente_apellido'] = $data['cliente_apellido'];
        $venta['marca']            = $data['marca'];
        $venta['modelo']           = $data['modelo'];
    }
    return $venta;
}

// Modifica una venta completa: cliente, auto, estado y observaciones.
// La usa SOLO el admin, que puede cambiar todos los campos.
// Además sincroniza el estado del auto según el nuevo estado de la venta.
// Recibe también $idAuto para saber qué auto actualizar.
// Devuelve: true si se actualizó bien, false si hubo error.
function modificarVentaCompleta($conexion, $id, $idAuto) {
    $consulta = "UPDATE ventas
                 SET id_cliente    = {$_POST['id_cliente']},
                     id_auto       = {$_POST['id_auto']},
                     estado        = '{$_POST['estado']}',
                     observaciones = '{$_POST['observaciones']}'
                 WHERE id = $id";
    if (mysqli_query($conexion, $consulta)) {
        // El admin puede haber cambiado el auto de la venta. $idAuto es el
        // auto ANTERIOR (el que tenía la venta) y $_POST['id_auto'] es el NUEVO.
        // Si son distintos, liberamos el anterior (vuelve a 'disponible')
        // porque esta venta ya no lo ocupa.
        // Ejemplo: la venta apuntaba al Onix y ahora apunta al Corolla:
        // el Onix queda libre y el Corolla pasa a reservado/vendido.
        if ($idAuto != $_POST['id_auto']) {
            actualizarEstadoAuto($conexion, $idAuto, 'disponible');
        }

        // Sincronizamos el estado del auto que quedó vinculado a la venta (el nuevo).
        // Si el estado de la venta es 'vendido', el auto también pasa a 'vendido';
        // con cualquier otro estado (interesado/reservado), queda como 'reservado'.
        if ($_POST['estado'] == 'vendido') {
            actualizarEstadoAuto($conexion, $_POST['id_auto'], 'vendido');
        } else {
            actualizarEstadoAuto($conexion, $_POST['id_auto'], 'reservado');
        }
        return true;
    } else {
        return false;
    }
}

// Modifica solo el estado y las observaciones de una venta.
// La usa el vendedor: no puede cambiar el cliente ni el auto asignado.
// También sincroniza el estado del auto según el nuevo estado de la venta.
// Devuelve: true si se actualizó bien, false si hubo error.
function modificarVentaParcial($conexion, $id, $idAuto) {
    $consulta = "UPDATE ventas
                 SET estado        = '{$_POST['estado']}',
                     observaciones = '{$_POST['observaciones']}'
                 WHERE id = $id";
    if (mysqli_query($conexion, $consulta)) {
        // Mismo espejo que en la versión completa: 'vendido' marca el auto
        // como vendido; cualquier otro estado lo deja como reservado.
        if ($_POST['estado'] == 'vendido') {
            actualizarEstadoAuto($conexion, $idAuto, 'vendido');
        } else {
            actualizarEstadoAuto($conexion, $idAuto, 'reservado');
        }
        return true;
    } else {
        return false;
    }
}

// Elimina una venta y libera el auto vinculado (lo pone como disponible).
// La usa SOLO el admin. Recibe $idAuto para saber qué auto liberar.
// Ejemplo: se cancela la compra del Onix → el Onix vuelve a 'disponible'.
// Devuelve: true si se eliminó bien, false si hubo error.
function eliminarVenta($conexion, $id, $idAuto) {
    $consulta = "DELETE FROM ventas WHERE id = $id";
    if (mysqli_query($conexion, $consulta)) {
        // Al eliminar la venta, el auto queda libre nuevamente.
        actualizarEstadoAuto($conexion, $idAuto, 'disponible');
        return true;
    } else {
        return false;
    }
}
?>
