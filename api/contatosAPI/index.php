<?php

    /******
    * $Request      - Recebe dados do corpo da requisição (JSON, FORM/DATA, XML, etc..) 
    * $Response     - Envia dados de retorno da API 
    * $Args         - Permite receber dados de atributos na API
    *******/
    // Import do arquivo autoload, que fara as instancias do Slin
    require_once('vendor/autoload.php');

    // Criando um objeto do slim chamado app, para configuar os Endpoints
    $app = new \Slim\App();

    // EndPoint: Requisição para listar todos os contatos
    $app->get('/contatos', function($request, $response, $args){

        require_once('../modulo/config.php');
        // Import da controller de contatos, que fará a busca de dados
        require_once('../controller/controllerContatos.php');

        // Solicita od dados par controle
        if($dados = listarContato())
        {
            // Caso exista dados a serem retornados,informamos o Status 200 e
            // enviamos um JSON com todos os dados encontrados
            if($dadosJSON = createJSON($dados)){
                return $response        ->withStatus(200)
                                        ->withHeader('Content-Type','application/json')
                                        ->write($dadosJSON);
            }    
        } else {
                // Retorna um statusCode qu significa que a requisiçao foi aceita, porém sem conteúdo
                return $response        ->withStatus(204);
        }
    });

    // EndPoint: Requisição para listar todos os contatos pelo id
    $app->get('/contatos/{id}', function($request, $response, $args){

        $id = $args['id'];
        require_once('../modulo/config.php');
        // Import da controller de contatos, que fará a busca de dados
        require_once('../controller/controllerContatos.php');

        // Solicita od dados par controle
        if($dados = buscarContato($id))
        {
            // Caso exista dados a serem retornados,informamos o Status 200 e
            // enviamos um JSON com todos os dados encontrados
            if($dadosJSON = createJSON($dados)){
                return $response        ->withStatus(200)
                                        ->withHeader('Content-Type','application/json')
                                        ->write($dadosJSON);
            }    
        } else {
                // Retorna um statusCode qu significa que a requisiçao foi aceita, porém sem conteúdo
                return $response        ->withStatus(204);
        }
    });

    // EndPoint: Requisição para listar todos os contatos 
    $app->post('/contatos/{id}', function($request, $response, $args){
        
    });

    // Executa todos os Endpoints
    $app->run();
?>