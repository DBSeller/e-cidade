<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009 DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

use ECidade\RecursosHumanos\Pessoal\Repository\SindicatoRepository;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('classes/db_rhsindicato_classe.php');
require_once modification('dbforms/db_funcoes.php');

$parametros = JSON::requestParameters();

foreach (get_object_vars($parametros) as $key => $value) {
    $GLOBALS[$key] = $value;
    ${$key} = $value;
}

$clrhsindicato = new cl_rhsindicato;
$db_opcao = 22;
$db_botao = false;
$erro = false;
$mensagem = '';

db_inicio_transacao();

try {
    if (isset($parametros->alterar)) {
        $sindicato = SindicatoRepository::find($parametros->rh116_sequencial);
        $sindicato->setSequencial($parametros->rh116_sequencial);
        $sindicato->setMesDataBase($parametros->mes_data_base);
        $sindicato->setCodigo($parametros->rh116_codigo);
        $sindicato->setRazaoSocial($parametros->rh116_descricao);
        $sindicato->setCnpj(str_replace(array('.', '/', '-'), '', $parametros->rh116_cnpj));

        SindicatoRepository::save($sindicato);

        $mensagem = 'Sindicato salvo com sucesso!';

        $db_opcao = 2;
        $db_botao = true;
    } elseif (isset($parametros->chavepesquisa)) {
        $sindicato = SindicatoRepository::find($chavepesquisa);

        $rh116_sequencial = $sindicato->getSequencial();
        $rh116_codigo = $sindicato->getCodigo();
        $rh116_descricao = $sindicato->getRazaoSocial();
        $rh116_cnpj = $sindicato->getCnpj();

        $db_opcao = 2;
        $db_botao = true;
    }
} catch (Exception $exception) {
    $erro = true;
    $mensagem = $exception->getMessage();
}

if ($mensagem) {
    db_msgbox($mensagem);
}

db_fim_transacao($erro);

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DBSeller Informática Ltda</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInputCNPJ.js"></script>
</head>
<body>
<div class="container">
    <?php require_once modification('forms/db_frmrhsindicato.php'); ?>
</div>
<?php

db_menu();

if ($db_opcao === 22) { ?>
    <script>document.form1.pesquisar.click();</script>
<?php } ?>
</body>
</html>
