<?php
/************************************************************** 
* Objetivo: Arquivo responsável pela manipulação de dados de contato
*   Obs(Este arquivo fará a ponte entre a View e a Model)
* Autor: João Gabriel 
* Data: 04/03/2022
* Versão: 1.0
***************************************************************/

// Função para receber dados da View e encaminhar para a Model (Inserir)
function inserirContato ($dadosContatos, $file){

    // Validação para verificar se o objeto esta vazio 
    if(!empty($dadosContatos)){

        // Validação de caixa vazia dos elementos nome,
        // celular e email, pois são campos obrigatórios no BD 
        if(!empty($dadosContatos['txtNome']) && !empty($dadosContatos['txtCelular']) && !empty($dadosContatos['txtEmail']))
            {
                if($file != null)
                {
                    require_once('modulo/upload.php');
                    $resultado = uploadFile($file['fleFoto']);
                    echo($resultado);
                    die;
                }
                // Criação do array de dados que será encaminhado a model
                // para inserir no banco de dados, é importante
                // criar este array conforme as necessidades de manipulação do BD
                // OBS: Criar as chaves do array conforme os nomes dos atributos do BD
                $arrayDados = array(
                    "nome"          => $dadosContatos['txtNome'],
                    "telefone"      => $dadosContatos['txtTelefone'],
                    "celular"       => $dadosContatos['txtCelular'],
                    "email"         => $dadosContatos['txtEmail'],
                    "obs"           => $dadosContatos['txtObs'],
                );
                // Require do arquivo da model que faz a conexão direta com o BD
                require_once('./model/bd/contato.php');
                // Chama a função que fará o insert do BD (esta função está na model)
                if (insertContato($arrayDados))
                    return true;
                else
                    return array('idErro' => 1, 
                                 'message' => 'Não foi poosível inserir os bancos de Dados');
            }
        else
            return array('idErro' =>2,
                         'message' => 'Existem campos obrigatórios que não foram preenchidos.');
    }
}

// Função para receber dados da View e encaminhar para a Model (Atualizar)
function atualizarContato ($dadosContatos, $id){

    // Validação para verificar se o objeto esta vazio 
    if(!empty($dadosContatos)){

        // Validação de caixa vazia dos elementos nome,
        // celular e email, pois são campos obrigatórios no BD 
        if(!empty($dadosContatos['txtNome']) && !empty($dadosContatos['txtCelular']) && !empty($dadosContatos['txtEmail']))
            {
                // Validação para garantir que o id seja válido
                if(!empty($id) && $id != 0 && is_numeric($id))
                {
                    // Criação do array de dados que será encaminhado a model
                    // para inserir no banco de dados, é importante
                    // criar este array conforme as necessidades de manipulação do BD
                    // OBS: Criar as chaves do array conforme os nomes dos atributos do BD
                    $arrayDados = array(
                        "id"            => $id,
                        "nome"          => $dadosContatos['txtNome'],
                        "telefone"      => $dadosContatos['txtTelefone'],
                        "celular"       => $dadosContatos['txtCelular'],
                        "email"         => $dadosContatos['txtEmail'],
                        "obs"           => $dadosContatos['txtObs'],
                    );

                    // Require do arquivo da model que faz a conexão direta com o BD
                    require_once('./model/bd/contato.php');
                    // Chama a função que fará o update do BD (esta função está na model)
                    if (uptadeContato($arrayDados))
                        return true;
                    else
                        return array('idErro' => 1,
                                    'message' => 'Não foi possível atualizar os dados no Banco de Dados');
                } else
                    return array('idErro'   => 4,
                             'message'   => 'Não é possível editar um registro sem informar um id válido');
            } else
                return array('idErro' =>2,
                         'message' => 'Existem campos obrigatórios que não foram preenchidos.');
    }
}

// Função para realizar a exclusão de um contato
function excluirContato ($id){

    // Validação para verificar se o id tem um número válido
    if($id != 0 && !empty($id) && is_numeric($id))
    {
        // Import do arquivo de contato
        require_once('model/bd/contato.php');

        // Chama a função da model e valida se o retorno foi verdadeiro ou falso
        if(deleteContato($id))
            return true;
        else
            return array('idErro'   => 3,
                         'message'  => 'O banco de Dados não pode excluir o registro.');
    }else {
        return array('idErro'   => 4,
                    'message'   => 'Não é possível excluir um registro sem informar um id válido');
    }
}

// Função para solicitar os dados da model e 
// encaminhar a lista de contatos para View
function listarContato (){

    // Import do arquivo que vai buscar os dados no BD
    require_once('model/bd/contato.php');

    // Chama a função que vai buscar os dados no BD
    $dados = selectAllContato();

    // Se não ter conteúdo ele irá retornar false
    if(!empty($dados))
        return $dados;
    else
        return false;
}

// Função para buscar um contato através do id do registro
function buscarContato($id) {

    if($id != 0 && !empty($id) && is_numeric($id))
    {
        // Import do arquivo de contato
        require_once('model/bd/contato.php');

        // Chama a função na model que vai buscar no BD
        $dados = selectByIdContato($id);

        // Valida se existem dados para serem desenvolvidos
        if(!empty($dados))
            return $dados;
        else
            return false;
    }else
        return array('idErro'   => 4,
                     'message'   => 'Não é possível buscar um registro sem informar um id válido');
}

?>