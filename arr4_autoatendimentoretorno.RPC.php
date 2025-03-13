<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
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
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

use ECidade\V3\Extension\Registry;

$parametros = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$retorno = new stdClass();
$retorno->erro = false;
$retorno->mensagem = '';

db_inicio_transacao();

try {
    switch ($parametros->executa) {
        case 'importar':

            if(empty($_FILES['arquivoImportacao'])) {
                throw new ParameterException('Nenhum arquivo informado.');
            }

            if($_FILES['arquivoImportacao']['error'] !== UPLOAD_ERR_OK) {
                throw new FileException('Erro ao importar o arquivo de retorno.');
            }

            if($_FILES['arquivoImportacao']['size'] === 0) {
                throw new FileException('Nenhum conteúdo encontrado no arquivo importado.');
            }

            $containerTributario = Registry::get('app.container')->get('tributario.container');
            $retornoService = $containerTributario->get('Arquivo\Autoatendimento\RCB80R\Service\RetornoService');
            $retornoService->execute($path);

            $retorno->mensagem = 'Registros do arquivo de retorno atualizados com sucesso.';

            break;
    }
} catch (Exception $exception) {
    $retorno->erro = true;
    $retorno->mensagem = $exception->getMessage();
}

db_fim_transacao($retorno->erro);

echo JSON::create()->stringify($retorno);
