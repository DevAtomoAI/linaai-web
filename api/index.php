<?php

// dependencies
require_once(dirname(__FILE__) . '/inc/config.php');
require_once(dirname(__FILE__) . '/inc/api_response.php');
require_once(dirname(__FILE__) . '/inc/api_logic.php');
require_once(dirname(__FILE__) . '/inc/database.php');

// --------------------------------------------------------
// instanciate the api_classe
$api_response = new api_response();

// --------------------------------------------------------
// check if method is valid
if(!$api_response->check_method($_SERVER['REQUEST_METHOD'])) {
    // send error response
    $api_response->api_request_error('Invalid request method.');
}

// --------------------------------------------------------
// set request method
$api_response->set_method($_SERVER['REQUEST_METHOD']);
$params = null;

if ($api_response->get_method() == 'GET') {
    // Para GET, extraímos diretamente da query string
    $api_response->set_endpoint($_GET['endpoint'] ?? null);
    $params = $_GET;
} elseif ($api_response->get_method() == 'POST') {
    // Para POST, extraímos o endpoint da query string
    $api_response->set_endpoint($_GET['endpoint'] ?? null); // endpoint deve ser passado na URL
    // Para o corpo da solicitação
    $params = $_POST; // ou json_decode(file_get_contents('php://input'), true) se usar JSON
}

// --------------------------------------------------------
// prepare the api logic
$api_logic = new api_logic($api_response->get_endpoint(), $params);

// --------------------------------------------------------
// check if endpoint exists
if (!$api_logic->endpoint_exists()) {
    $api_response->api_request_error('Inexistent endpoint: ' . $api_response->get_endpoint());
}

// request to the api_logic
$result = $api_logic->{$api_response->get_endpoint()}(); 

// Se o resultado for um array ou um objeto que já possui um método de resposta
if (is_array($result) || is_object($result)) {
    header('Content-Type: application/json'); // Mover para aqui
    echo json_encode($result);
    exit(); // Use exit após enviar a resposta
}

// Caso contrário, faça uma resposta padrão
$api_response->send_response();
