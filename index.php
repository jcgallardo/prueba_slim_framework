<?php

require_once "vendor/autoload.php";

$app = new \Slim\Slim();

$db = new mysqli("localhost", "user", "5tgb%TGB_db","cursoangular");

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}

$app->get("/pruebas", function() use($app, $db){
    echo "Hola mundo desde Slim";
});

// LISTAR TODOS LOS PRODUCTOS
$app->get("/productos", function() use($app, $db){
    $query  =  'SELECT * FROM productos ORDER BY id DESC;';
    $result = mysqli_fetch_all($db->query($query), MYSQLI_ASSOC);

    $data = [
        'status'    => 'success',
        'code'      => 200,
        'productos' => $result
    ];
    
    echo json_encode($data);
});

// LISTAR UN PRODUCTO
$app->get("/productos/:id", function($id) use($app, $db){
    $query  =  'SELECT * FROM productos WHERE id = '.$id.';';
    $product = $db->query($query)->fetch_assoc();

    $data = [
        'status'    => 'success',
        'code'      => 200,
        'producto'  => $product
    ];

    if(!$product){
        $data['status'] = 'error';
        $data['msg'] = 'Producto no disponible';
        $data['code'] = 404;
    }

    echo json_encode($data);

});

//Guardar producto
$app->post("/productos", function() use($app, $db){
    $json = $app->request->post("json");
    $data = json_decode($json, true);
    
    if(!isset($data['imagen']))
        $data['imagen'] = null;
    
    if(!isset($data['descripcion']))
        $data['descripcion'] = null;
    
    if(!isset($data['nombre']))
        $data['nombre'] = null;

    if(!isset($data['precio']))
        $data['precio'] = null;
    
    $query  =   "INSERT INTO productos VALUES(NULL, ".
                "'{$data['nombre']}',".
                "'{$data['descripcion']}',".
                "'{$data['precio']}',".
                "'{$data['imagen']}'".
                ");";
    
    $insert = $db->query($query);

    $result = array(
        'status'    => 'error',
        'code'      => 404,
        'msg'       => 'Producto NO creado'
    );

    if($insert){
        $result = array(
            'status'    => 'success',
            'code'      => 200,
            'msg'       => 'Producto creado correctamente'   
        );
    }

    echo json_encode($result);
              
});

// ELIMINAR UN PRODUCTO
$app->get("/productos/:id/delete", function($id) use($app, $db){
    $query  =  'DELETE FROM productos WHERE id = '.$id.';';
    $result = $db->query($query);

    $data = [
        'status'    => 'success',
        'code'      => 200,
        'msg'       => 'Producto eliminado correctamente'
    ];

    if(!$result){
        $data['status'] = 'error';
        $data['msg'] = 'Producto no disponible';
        $data['code'] = 404;
    }

    echo json_encode($data);
});

// ACTUALIZAR UN PRODUCTO
$app->post("/productos/:id/update", function($id) use($app, $db){
    $data = json_decode($app->request->post("json"), true);

    $sql =  "UPDATE productos set ".
            " nombre = '{$data['nombre']}',".
            " descripcion = '{$data['descripcion']}',".
            " precio = '{$data['precio']}'";

    if(isset($data['imagen']))
        $sql .= ", imagen = '{$data['imagen']}'";

    $sql .=  " WHERE id = {$id};";

    $query = $db->query($sql);

    $result = array(
        'status'    => 'error',
        'code'      => 404,
        'msg'       => 'Producto NO actualizado'
    );

    if($query){
        $result = array(
            'status'    => 'success',
            'code'      => 200,
            'msg'       => 'Producto actualizado correctamente'   
        );
    }

    echo json_encode($result);
});

// SUBIR UNA IMAGEN A UN PRODUCTO
$app->post("/productos/:id/upload-file", function($id) use($app, $db){
    $result = array(
        'status'    => 'error',
        'code'      => 404,
        'msg'       => 'El archivo NO ha podido subirse'
    );

    if(isset($_FILES['uploads'])){
        $result['status'] = 'success';
        $result['code'] = 200;
        $result['msg'] = '';

        $piramide_uploader = new PiramideUploader();
        $upload = $piramide_uploader->upload('image', 'uploads', 'uploads', array('image/jpeg', 'image/png', 'image/gif'));
        $file = $piramide_uploader->getInfoFile();
        $file_name = $file['complete_name'];

        if(isset($upload) && $upload['uploaded'] == true){
            $result['result'] = $file_name;
        }
    }

    echo json_encode($result);
});

$app->run();

?>