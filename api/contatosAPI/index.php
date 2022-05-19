<?php
    // Import do arquivo autoload, que fara as instancias do Slin
    require_once('vendor/autoload.php');

    // Criando um objeto do slim chamado app, para configuar os Endpoints
    $app = new \Slim\App();

    // EndPoint: Requisição para listar todos os contatos
    $app->get('/contatos', function($request, $response, $args){
        $response->write('Testando a API pelo GET');
    });
    // EndPoint: Requisição para listar todos os contatos pelo id
    $app->get('/contatos/{id}', function($request, $response, $args){
        
    });
    // EndPoint: Requisição para listar todos os contatos
    $app->post('/contatos', function($request, $response, $args){
        
    })
?>