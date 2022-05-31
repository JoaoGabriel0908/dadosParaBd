<?php

/******
 * $Request      - Recebe dados do corpo da requisição (JSON, FORM/DATA, XML, etc..) 
 * $Response     - Envia dados de retorno da API 
 * $Args         - Permite receber dados de atributos na API

 * Os métodos de requisição para uma API são
 * GET           - para buscar dados
 * POST          - para inseri um novo dado
 * DELETE        - para apagar dados
 * PUT/PATCH     - para editar um dado já existente
 *******/

// Import do arquivo autoload, que fara as instancias do Slin
require_once('vendor/autoload.php');

// Criando um objeto do slim chamado app, para configuar os Endpoints
$app = new \Slim\App();

// EndPoint: Requisição para listar todos os contatos
$app->get('/contatos', function ($request, $response, $args) {

    require_once('../modulo/config.php');
    // Import da controller de contatos, que fará a busca de dados
    require_once('../controller/controllerContatos.php');

    // Solicita od dados par controle
    if ($dados = listarContato()) {
        // Caso exista dados a serem retornados,informamos o Status 200 e
        // enviamos um JSON com todos os dados encontrados
        if ($dadosJSON = createJSON($dados)) {
            return $response->withStatus(200)
                ->withHeader('Content-Type', 'application/json')
                ->write($dadosJSON);
        }
    } else {
        // Retorna um statusCode qu significa que a requisiçao foi aceita, porém sem conteúdo
        return $response->withStatus(204);
    }
});

// EndPoint: Requisição para listar todos os contatos pelo id
$app->get('/contatos/{id}', function ($request, $response, $args) {
    // Recebe o id do registro que deverá ser retornado pela API
    // Esse id está chegando pela variavel criada no endpoint
    $id = $args['id'];

    require_once('../modulo/config.php');
    // Import da controller de contatos, que fará a busca de dados
    require_once('../controller/controllerContatos.php');

    // Solicita od dados par controle
    if ($dados = buscarContato($id)) {
        // Verifica se houve algum tipo de erro no retorno dos dados da controller
        if (!isset($dados['idErro'])) {
            // Caso exista dados a serem retornados,informamos o Status 200 e
            // enviamos um JSON com todos os dados encontrados
            if ($dadosJSON = createJSON($dados)) {
                return $response->withStatus(200)
                    ->withHeader('Content-Type', 'application/json')
                    ->write($dadosJSON);
            }
        } else {
            // Converte para JSON o erro, pois a controller retorna um array
            $dadosJSON = createJSON($dados);
            // Retorna um erro que significa que o cliente passou dados errados
            return $response->withStatus(404)
                ->withHeader('Content-Type', 'application/json')
                ->write('{"message": Dados inválidos, 
                                                "Erro": ' . $dadosJSON . '
                                                }');
        }
    } else {
        // Retorna um statusCode qu significa que a requisiçao foi aceita, porém sem conteúdo
        return $response->withStatus(204);
    }
});

// EndPoint: Requisição para deletar um contato pelo id
$app->delete('/contatos/{id}', function ($request, $response, $args) {
    // Recebe o id do registro que deverá ser retornado pela API
    // Esse id está chegando pela variavel criada no endpoint
    if (is_numeric($args['id'])) {
        // Recebe o id enviado no EndPoint através da variavel ID
        $id = $args['id'];

        require_once('../modulo/config.php');
        // Import da controller de contatos, que fará a busca de dados
        require_once('../controller/controllerContatos.php');

        // Busca o nome da foto para ser excluída na controller
        if ($dados = buscarContato($id)) {
            // Recebe o nome da foto que a controller retornou
            $foto = $dados['foto'];

            // Cria um array com o ID e o nome da foto a ser enviada para controller excluir registro
            $arrayDados = array(
                "id"        => $id,
                "foto"      => $foto
            );

            // Chama a função de excluir o contato, encaminhando o array com o ID e a foto
            $resposta = excluirContato($arrayDados);
            if (is_bool($resposta) && $resposta == true) {
                // Retorna um erro que significa que o cliente informou um ID inválido
                return $response->withStatus(200)
                    ->withHeader('Content-Type', 'application/json')
                    ->write('{"message": "Registro excluído com sucesso!"}');
            } elseif (is_array($resposta) && isset($resposta['idErro'])) {
                // Validação referente ao erro 5, que significa que o registro
                // foi excluída do Banco de dados e a imagem não existia no servidor
                if ($resposta['idErro'] == 5) {
                    // Retorna um erro que significa que o cliente informou um ID inválido
                    return $response->withStatus(200)
                        ->withHeader('Content-Type', 'application/json')
                        ->write('{"message": "Registro excluído com sucesso, porém houve um problema na exclusão da imagem na pasta do servidor"}');
                } else {
                    // Converte para JSON o erro, pois a controller retorna um array
                    $dadosJSON = createJSON($resposta);

                    // Retorna um erro que significa que o cliente passou dados errados
                    return $response->withStatus(404)
                                    ->withHeader('Content-Type', 'application/json')
                                    ->write('{"message": "Houve um problema no processo de excluír", 
                                                        "Erro": ' . $dadosJSON . '
                                            }');
                }
            }
        } else {
            // Retorna um erro que significa que o cliente informou um ID inválido
            return $response->withStatus(404)
                ->withHeader('Content-Type', 'application/json')
                ->write('{"message": "O ID informado não existe na base de dados"}');
        }

        // Retorna um erro que significa que o cliente passou dados errados
    } else {
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'application/json')
            ->write('{"message": "É obrigatório informar um id em um formato valido(número)"}');
    }
});

// EndPoint: Requisição para listar todos os contatos 
$app->post('/contatos', function ($request, $response, $args) {
    // Recebe do header da requisição qual será o content-Type
    $contentTypeHeader = $request->getHeaderLine('Content-Type');

    // Cria um array, pois dependendo do content-Type temos mais informações separadas pelo ";"
    $contentType = explode(";", $contentTypeHeader);

    switch ($contentType[0]) {
        case 'multipart/form-data':
            //Recebe os dados comuns enviada pelo corpoda requisição
            $dadosBody = $request->getParsedBody();

            //Recebe as fotos enviada pelo corpoda requisição
            $UploadedFile = $request->getUploadedFiles();

            // Cria um array com todos os dados que chegaram da requisição,
            // devido aos dados serem protegidos, criamos um array e recuperamos os dados pelos métodos do objeto
            $arrayFoto = array(
                "name"      => $UploadedFile['foto']->getClientFileName(),
                "type"      => $UploadedFile['foto']->getClientMediaType(),
                "size"      => $UploadedFile['foto']->getSize(),
                "tmp_name"  => $UploadedFile['foto']->file
            );

            // Cria uma chave chamada "Foto" para colocar todos os dados do objeto, conforme é gerado no HTML
            $file = array("foto" => $arrayFoto);

            // Cria um array com todos os dados comnus e do arquivo que será enviado para o servidor
            $arrayDados = array(
                $dadosBody,
                "file" => $file
            );

            require_once('../modulo/config.php');
            // Import da controller de contatos, que fará a busca de dados
            require_once('../controller/controllerContatos.php');

            // Chama a função da controller para inserir os dados
            $resposta = inserirContato($arrayDados);

            if (is_bool($resposta) && $resposta == true) {
                return $response->withStatus(201)
                    ->withHeader('Content-Type', 'application/json')
                    ->write('{"message": "Registro inserido com sucesso"}');
            } elseif (is_array($resposta) && $resposta['idErro']) {

                // Cria o JSON do erro
                $dadosJSON = createJSON($resposta);
                return $response->withStatus(400)
                    ->withHeader('Content-Type', 'application/json')
                    ->write('{"message": "Houve um problema no processo de excluír", 
                                                    "Erro": ' . $dadosJSON . '
                                            }');
            }
            break;
        case 'application/json':
            $dadosBody = $request->getParsedBody();
            return $response->withStatus(200)
                ->withHeader('Content-Type', 'application/json')
                ->write('{"message": "Formato selecionado foi Json!"}');
            break;

        default:
            return $response->withStatus(400)
                ->withHeader('Content-Type', 'application/json')
                ->write('{"message": "Formato do Content-Type não é válido para essa requisição"}');
    }
});

// EndPoint: Requisição para alterar um contato, simulando o PUT
$app->post('/contatos/{id}', function ($request, $response, $args) {

    if (is_numeric($args['id'])) 
    {
        // Recebe o id enviado no EndPoint através da variavel ID
        $id = $args['id'];

        // Recebe do header da requisição qual será o content-Type
        $contentTypeHeader = $request->getHeaderLine('Content-Type');

        // Cria um array, pois dependendo do content-Type temos mais informações separadas pelo ";"
        $contentType = explode(";", $contentTypeHeader);
        
        switch ($contentType[0]) {
            case 'multipart/form-data':

                require_once('../modulo/config.php');
                // Import da controller de contatos, que fará a busca de dados
                require_once('../controller/controllerContatos.php');

                // Chama a função para buscar a foto que já está salva no banco de dados
                if($dadosContato = buscarContato($id)){
                    $fotoAtual = $dadosContato['foto'];

                    //Recebe os dados comuns enviada pelo corpoda requisição
                    $dadosBody = $request->getParsedBody();

                    //Recebe as fotos enviada pelo corpoda requisição
                    $UploadedFile = $request->getUploadedFiles();

                    // Cria um array com todos os dados que chegaram da requisição,
                    // devido aos dados serem protegidos, criamos um array e recuperamos os dados pelos métodos do objeto
                    $arrayFoto = array(
                        "name"      => $UploadedFile['foto']->getClientFileName(),
                        "type"      => $UploadedFile['foto']->getClientMediaType(),
                        "size"      => $UploadedFile['foto']->getSize(),
                        "tmp_name"  => $UploadedFile['foto']->file
                    );

                    // Cria uma chave chamada "Foto" para colocar todos os dados do objeto, conforme é gerado no HTML
                    $file = array("foto" => $arrayFoto);

                    // Cria um array com todos os dados comnus e do arquivo que será enviado para o servidor
                    $arrayDados = array(
                        $dadosBody,
                        "file" => $file,
                        "id"   => $id,
                        "foto" => $fotoAtual
                    );

                    // Chama a função da controller para inserir os dados
                    $resposta = atualizarContato($arrayDados);

                    if (is_bool($resposta) && $resposta == true) {
                        return $response    ->withStatus(201)
                                            ->withHeader('Content-Type', 'application/json')
                                            ->write('{"message": "Registro inserido com sucesso"}');
                    } elseif (is_array($resposta) && $resposta['idErro']) {

                        // Cria o JSON do erro
                        $dadosJSON = createJSON($resposta);
                        return $response    ->withStatus(400)
                                            ->withHeader('Content-Type', 'application/json')
                                            ->write('{"message": "Houve um problema no processo de inserir", 
                                                        "Erro": ' . $dadosJSON . '
                                                }');
                    }
                } else {
                    // Retorna um erro que significa que o cliente passou dados errados
                    return $response    ->withStatus(404)
                                        ->withHeader('Content-Type', 'application/json')
                                        ->write('{"message": "Houve um problema no processo de editar", 
                                                        "Erro": ' . $dadosJSON . '
                                        }');
                }
                break;
            case 'application/json':

                $dadosBody = $request->getParsedBody();
                return $response    ->withStatus(200)
                                    ->withHeader('Content-Type', 'application/json')
                                    ->write('{"message": "Formato selecionado foi Json!"}');
                break;

            default:
                return $response    ->withStatus(400)
                                    ->withHeader('Content-Type', 'application/json')
                                    ->write('{"message": "Formato do Content-Type não é válido para essa requisição"}');
                                break;
        }
    } else {
        return $response    ->withStatus(404)
                            ->withHeader('Content-Type', 'application/json')
                            ->write('{"message": "É obrigatório informar um id em um formato valido(número)"}');
    }
});
// Executa todos os Endpoints
$app->run();
