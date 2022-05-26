<?php
/************************************************************** 
* Objetivo: Arquivo responsável pela manipulação de dados de contato
*   Obs(Este arquivo fará a ponte entre a View e a Model)
* Autor: João Gabriel 
* Data: 04/03/2022
* Versão: 1.0
***************************************************************/

// Import do arquivo de configurção do projeto
require_once(SRC.'modulo/config.php');

// Função para receber dados da View e encaminhar para a Model (Inserir)
function inserirContato ($dadosContatos){

    $nomeFoto = (string) null;

    // Validação para verificar se o objeto esta vazio 
    if(!empty($dadosContatos)){

        // Validação de caixa vazia dos elementos nome,
        // celular e email, pois são campos obrigatórios no BD 
        if(!empty($dadosContatos[0]['nome']) && !empty($dadosContatos[0]['celular']) && !empty($dadosContatos[0]['email']) && !empty($dadosContatos[0]['estado']))
            {
                // Recebe o objeto imagem que foi encaminhado dentro do array
                $file = $dadosContatos['file'];
                // Validação para identificar se chegou um arquivo para upload
                if($file['foto']['name'] != null)
                {
                    // Import da função de upload
                    require_once(SRC.'modulo/upload.php');
                    // Chama a função de upload
                    $nomeFoto = uploadFile($file['foto']);

                    // Chama a função de upload
                    if(is_array($nomeFoto))
                    {
                        // Caso aconteça algum erro no processo de upload, a função irá retornar
                        // um array com a possivel mensagem de erro. Esse array será retornado para
                        // a router e ela irá exibir a mensagem na para o usuário
                        return $nomeFoto;
                    }
    
                }
                // Criação do array de dados que será encaminhado a model
                // para inserir no banco de dados, é importante
                // criar este array conforme as necessidades de manipulação do BD
                // OBS: Criar as chaves do array conforme os nomes dos atributos do BD
                $arrayDados = array(
                    "nome"          => $dadosContatos[0]['nome'],
                    "telefone"      => $dadosContatos[0]['telefone'],
                    "celular"       => $dadosContatos[0]['celular'],
                    "email"         => $dadosContatos[0]['email'],
                    "obs"           => $dadosContatos[0]['obs'],
                    "foto"          => $nomeFoto,
                    "idestado"      => $dadosContatos[0]['estado']
                );
                // Require do arquivo da model que faz a conexão direta com o BD
                require_once(SRC.'model/bd/contato.php');

                // Chama a função que fará o insert do BD (esta função está na model)
                if (insertContato($arrayDados))
                    return true;
                else
                    return array('idErro' => 1, 
                                 'message' => 'Não foi possível inserir os bancos de Dados');
            }
        else
            return array('idErro' =>2,
                         'message' => 'Existem campos obrigatórios que não foram preenchidos.');
    }
}

// Função para receber dados da View e encaminhar para a Model (Atualizar)
function atualizarContato ($dadosContatos, $arrayDados){

    $statusUpload = (boolean) false;

    // Recebe o id anviado peloas ArraDados
    $id = $arrayDados['id'];

    // Recebe a foto enviada pelo arratDados (nome da foto que ja exsitia no BD)
    $foto = $arrayDados['foto'];

    // Recebe o objeto de array referente a nova foto que poderá ser enviada ao servidor
    $file = $arrayDados['file'];

    // Validação para verificar se o objeto esta vazio 
    if(!empty($dadosContatos)){

        // Validação de caixa vazia dos elementos nome,
        // celular e email, pois são campos obrigatórios no BD 
        if(!empty($dadosContatos['txtNome']) && !empty($dadosContatos['txtCelular']) && !empty($dadosContatos['txtEmail']))
            {
                // Validação para garantir que o id seja válido
                if(!empty($id) && $id != 0 && is_numeric($id))
                {
                    // Validação para identificar se será enviado ao servidor uma nova foto
                    if($file['fleFoto']['name'] != null)
                    {
                        // Import da função de upload para emviar a nova foto ao servidor
                        require_once('modulo/upload.php');

                        // Chama a função de upload
                        $novaFoto = uploadFile($file['fleFoto']);
                        // Faz a alteração quando inserida uma nova foto
                        $statusUpload = true;
                    } else {
                        // Permanece a mesma foto no BD
                        $novaFoto = $foto;
                    }
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
                        "foto"          => $novaFoto,
                        "idestado"      => $dadosContatos['sltEstado']
                    );

                    // Require do arquivo da model que faz a conexão direta com o BD
                    require_once('./model/bd/contato.php');
                    // Chama a função que fará o update do BD (esta função está na model)
                    if (uptadeContato($arrayDados)){
                        // Validação para verificar se será necessário apagar a foto antiga
                        // Essa variavel foi ativada na linha 102, quando realizamos o upload 
                        // de uma nova foto para o servidor.
                        if($statusUpload){
                            // Apaga a foto antiga da pasta do servidor
                            unlink(DIRETORIO_FILE_UPLOAD.$foto);
                        }
                            return true;
                    }
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
function excluirContato ($arrayDados){

    // Recebe o id do registro que será excluído
    $id = $arrayDados['id'];
    // Recebe o nome da foto que será excluída da pasta do servidor
    $foto = $arrayDados['foto'];

    // Validação para verificar se o id tem um número válido
    if($id != 0 && !empty($id) && is_numeric($id))
    {
        // Import do arquivo de contato
        require_once(SRC.'model/bd/contato.php');
        // require_once('modulo/config.php');

        // Chama a função da model e valida se o retorno foi verdadeiro ou falso
        if(deleteContato($id))
        {
            // Validação para caso a foto não exista com o registro
            if($foto != null)
            {
                // O uso do @ foi utilizado para corrigir um teste quando inserimos dento
                // do banco uma imagem que não existe fisicamente
                if(@unlink(SRC.DIRETORIO_FILE_UPLOAD.$foto)){
                    return true;
                }
                else
                    return array('idErro'   => 5,
                             'message'  => 'O registro do Banco de Dados foi excluído com sucesso,
                                            porém a imagem não foi excluída do diretório do servidor!');
            }
            else
                return true;
        }
            // Unlink() = Função para apagar um arquivo de um diretório
            // Permite apagar a foto fisicamente do diretório no servidor
        else
            return array('idErro'   => 3,
                         'message'  => 'O banco de Dados não pode excluir o registro.');
    } else {
        return array('idErro'   => 4,
                    'message'   => 'Não é possível excluir um registro sem informar um id válido');
    }
}

// Função para solicitar os dados da model e 
// encaminhar a lista de contatos para View
function listarContato (){

    // Import do arquivo que vai buscar os dados no BD
    require_once(SRC.'model/bd/contato.php');

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
        require_once(SRC.'model/bd/contato.php');

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
