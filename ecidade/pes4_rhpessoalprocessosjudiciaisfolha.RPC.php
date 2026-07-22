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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

use ECidade\RecursosHumanos\Pessoal\Service\ServidorProcessosJudiciaisFolhaService;
use ECidade\RecursosHumanos\Pessoal\Model\ServidorProcessosJudiciaisFolha;

$parametros = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$retorno = new stdClass();
$retorno->erro = 1;
$retorno->mensagem = '';

try {
    db_inicio_transacao();

    switch ($parametros->executa) {
        case 'salvar':

            $service = new ServidorProcessosJudiciaisFolhaService();
            $service->salvar($parametros);
            $retorno->mensagem = 'Processo salvo com sucesso.';
            break;
        case 'excluir':

            $service = new ServidorProcessosJudiciaisFolhaService();
            $service->excluir($parametros);
            $retorno->mensagem = 'Processo excluído com sucesso.';
            break;
        case 'buscar':

            $service = new ServidorProcessosJudiciaisFolhaService();
            $processosJudiciais = $service->buscarProcessosJudiciaisPorMatriculaCompetencia($parametros);

            $retorno->processosJudiciais = array_map(function (ServidorProcessosJudiciaisFolha $processosJudiciais) {
                return $processosJudiciais->toArray();
            }, $processosJudiciais);
            break;
    }

    db_fim_transacao(false);
} catch (Exception $eErro) {
    db_fim_transacao(true);
    $retorno->erro = 2;
    $retorno->mensagem = $eErro->getMessage();
}

$retorno->erro = $retorno->erro == 2;
echo JSON::create()->stringify($retorno);
