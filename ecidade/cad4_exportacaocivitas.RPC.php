<?php
/*
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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('libs/db_app.utils.php');
require_once modification('libs/db_utils.php');
require_once modification('libs/JSON.php');
require_once modification('dbforms/db_funcoes.php');

use ECidade\Tributario\Integracao\Civitas\Repository\Exportacao as ExportacaoRepository;
use ECidade\Tributario\Integracao\Civitas\Arquivo\Exportacao as ArquivoExportacao;

$retorno = new stdClass();
$retorno->erro = false;
$retorno->mensagem = '';

$parametros = JSON::requestParameters();
//kill($parametros );
db_inicio_transacao();

try {

    switch ($parametros->acao) {
        case 'exportacao':

            if (!isset($parametros->dataInicio)) {
                throw new Exception("A data de início é obrigatória.");
            }
            $dataFinal = null;
            if (isset($parametros->dataFinal)) {

                $dataFinal = new DateTime($parametros->dataFinal);
                $dataFinal = new DBDate($dataFinal->format("Y-m-d"));
            }
            $dataInicio = new DateTime($parametros->dataInicio);
            $dataInicio = new DBDate($dataInicio->format("Y-m-d"));

            $dados = ExportacaoRepository::getDados($dataInicio, $dataFinal);
            $arquivo = new ArquivoExportacao($dados);
            $retorno->arquivo = $arquivo->getFilePath();
        break;
    }

} catch (Exception $exception) {
    $retorno->erro = true;
    $retorno->mensagem = $exception->getMessage();
}

db_fim_transacao($retorno->erro);

echo JSON::create()->stringify($retorno);
