<?php
// Import do arquivo de configuração do projeto
require_once('modulo/config.php');
// Essa variavel foi criada para diferenciar no action do formulário qual
// a ação deveria ser levada para a router (inserir ou editar).
// Nas condições abaixo mudamos o action da variacel para a ação de editar.
$form = (string) "router.php?componente=contatos&action=inserir";

// Variavel para carregar o nome da foto do banco de dados
$foto = (string) null;
// Variavel para ser utilizada no carregar dos estados
$idestado = (string) null;

// Valida se a utilização da variavel de sessão esta ativa no servidor
    if(session_status()){
        // Valida se a variavel de sessão dadosContato não esta vazia
        if(!empty($_SESSION['dadosContato'])){
            $id             = $_SESSION['dadosContato']['id'];
            $nome           = $_SESSION['dadosContato']['nome'];
            $telefone       = $_SESSION['dadosContato']['telefone'];
            $celular        = $_SESSION['dadosContato']['celular'];
            $email          = $_SESSION['dadosContato']['email'];
            $obs            = $_SESSION['dadosContato']['obs'];
            $foto           = $_SESSION['dadosContato']['foto'];
            $idestado       = $_SESSION['dadosContato']['idestado'];

            // Mudamos a ação do form para editar o registro no click do botão
            // da ação salvar.
            $form = "router.php?componente=contatos&action=editar&id=".$id."&foto=".$foto;

            // Destroi uma variavel da memoria do servidor
            unset($_SESSION['dadosContato']);
        }
    }
?>

<!DOCTYPE>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title> Cadastro </title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        
        <div id="cadastro"> 
            <div id="cadastroTitulo"> 
                <h1> Cadastro de Contatos. </h1>           
            </div>
            <div id="cadastroInformacoes">
                
            <!-- Enviando variaveis para o router -->

                <!-- enctype="multipart/form-data" 
                    Essa opção é obrgatória para enviar 
                    arquivos do formulário em HTML para o servidor 
                -->
                <form action="<?=$form?>" name="frmCadastro" method="post" enctype="multipart/form-data" >
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <label> Nome: </label>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <input type="text" name="nome" value="<?= isset($nome)?$nome:null?>" placeholder="Digite seu Nome" maxlength="100">
                        </div>
                    </div>
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <label> Estado: </label>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <select name="estado" id="">
                                <option value="">Selecione um item</option>
                                <?php
                                // Import da controller de estado
                                    require_once('controller/controllerEstados.php');
                                    $listEstados = listarEstado();
                                    // Chama a função para carregar todos os estados no BD
                                    foreach ($listEstados as $item)
                                    {
                                        ?>
                                            <option <?=$idestado==$item['idestado']?'selected':null?> value="<?=$item['idestado']?>"><?=$item['sigla']?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>           
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <label> Telefone: </label>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <input type="tel" name="telefone" value="<?= isset($telefone)?$telefone:null?>">
                        </div>
                    </div>
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <label> Celular: </label>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <input type="tel" name="celular" value="<?= isset($celular)?$celular:null?>">
                        </div>
                    </div>      
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <label> Email: </label>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <input type="email" name="email" value="<?= isset($email)?$email:null?>">
                        </div>
                    </div>
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <label> Escolha um arquivo : </label>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <input type="file" name="foto" accept=".jpg, .png, .jpeg, .gif">
                        </div>
                    </div>
                    <div class="campos">
                        <div class="cadastroInformacoesPessoais">
                            <label> Observações: </label>
                        </div>
                        <div class="cadastroEntradaDeDados">
                            <textarea name="obs" cols="50" rows="7"><?=isset($obs)?$obs:null?></textarea>
                        </div>
                    </div>
                    <div class="campos">
                        <img src="<?=DIRETORIO_FILE_UPLOAD.$foto?>" alt="">
                    </div>

                    </div> 
                    <div class="enviar">
                        <div class="enviar">
                            <input type="submit" name="btnEnviar" value="Salvar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="consultaDeDados">
            <table id="tblConsulta" >
                <tr>
                    <td id="tblTitulo" colspan="6">
                        <h1> Consulta de Dados.</h1>
                    </td>
                </tr>
                <tr id="tblLinhas">
                    <td class="tblColunas destaque"> Nome </td>
                    <td class="tblColunas destaque"> Celular </td>
                    <td class="tblColunas destaque"> Email </td>
                    <td class="tblColunas destaque"> Foto </td>
                    <td class="tblColunas destaque"> Opções </td>
                </tr>
                
                <?php
                // Import do arquivo da controller para solicitar a listagem dos dados
                    require_once('controller/controllerContatos.php');
                    // Chama a função que vai retornar os dados de contatos
                    $listContato = listarContato();

                    if($listContato = listarContato()){

                        // Estrutura de repetição para retornar ps dados do array e printar na tela
                        foreach ($listContato as $item)
                        {
                            // Variavel para carregar a foto que veio do BD
                            $foto = $item['foto'];
                ?>

                <tr id="tblLinhas">
                    <td class="tblColunas registros"><?=$item['nome']?></td>
                    <td class="tblColunas registros"><?=$item['celular']?></td>
                    <td class="tblColunas registros"><?=$item['email']?></td>
                    <td class="tblColunas registros"><img src="<?=DIRETORIO_FILE_UPLOAD.$foto?>" class="foto"></td>
                   
                    <td class="tblColunas registros">
                        <a href="router.php?componente=contatos&action=buscar&id=<?=$item['id']?>">
                            <img src="img/edit.png" alt="Editar" title="Editar" class="editar">
                        </a>
                        <a onclick="return confirm('Deseja realmente excluir este <?=$item['nome']?> contato?')" href="router.php?componente=contatos&action=deletar&id=<?=$item['id']?>&foto=<?=$foto?>">
                            <img src="img/trash.png" alt="Excluir" title="Excluir" class="excluir">
                        </a>
                            <img src="img/search.png" alt="Visualizar" title="Visualizar" class="pesquisar">
                    </td>
                </tr>
                <?php
                }
            }
                ?>
            </table>
        </div>
    </body>
</html>