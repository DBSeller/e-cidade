<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2018  DBSeller Servicos de Informatica
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

use ECidade\Saude\Laboratorio\Repository\Parametros as ParametrosRepository;
use ECidade\Saude\Laboratorio\Repository\NumeroControleInternoRequisicao as NumeroControleRepository;
use ECidade\Saude\Laboratorio\Service\NumeroControleInternoRequisicao as NumeroControleService;

$post = db_utils::postMemory($_REQUEST);
$post->json = str_replace("\\", "", $post->json);
$parametros = JSON::create()->parse($post->json);
$retorno = (object)array('erro' => false, 'mensagem' => '');

try {
    db_inicio_transacao();

    switch ($parametros->executa) {
        case 'buscarInformacoesNumeroControleInterno':
            $numeroControleService = new NumeroControleService(NumeroControleRepository::getInstance());
            $numeroControleModel = $numeroControleService->getNumeroControleInternoByParametros($parametros);
            
            $retorno->numeroControleInterno = !empty($numeroControleModel) ? $numeroControleModel->getNumero() : '';
            $retorno->ano = !empty($numeroControleModel) ? $numeroControleModel->getAno() : db_getsession('DB_anousu');
            $retorno->requisicao = !empty($numeroControleModel) ? $numeroControleModel->getCodigoRequisicao() : '';

            break;

        case 'verificarParametro':
            $parametrosLaboratorio = ParametrosRepository::getInstancia();
            $parametrosLaboratorioModel = $parametrosLaboratorio->buscar();

            $retorno->parametroAtivo = $parametrosLaboratorioModel->isNumeroControleInterno();
            $retorno->ano = db_getsession('DB_anousu');

            break;
    }

    db_fim_transacao(false);
} catch (Exception $erro) {
    db_fim_transacao(true);

    $retorno->erro = true;
    $retorno->mensagem = $erro->getMessage();
}

echo JSON::create()->stringify($retorno);
