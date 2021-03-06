<?php
include "controlador/loginusuario.controlador.php";
include "controlador/usuarios.controlador.php";
include "controlador/sobrante.controlador.php";
include "controlador/copi.controlador.php";
include "controlador/transferencia.controlador.php";

require "modelos/conexion.php";
require "modelos/loginusuario.modelo.php";
require "modelos/usuarios.modelo.php";
require "modelos/sobrante.modelo.php";
require "modelos/copi.modelo.php";
require "modelos/transferencia.modelo.php";

// function cors() {

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        // may also be using PUT, PATCH, HEAD etc
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');      
        header("Allow: GET, POST, OPTIONS, PUT, DELETE");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}
session_start();
// }