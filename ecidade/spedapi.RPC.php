<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                    www.dbseller.com.br
 *                 e-cidade@dbseller.com.br
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

use ECidade\RecursosHumanos\ESocial\Integracao\ESocial;
use ECidade\V3\Extension\Registry;

$retorno = new stdClass();
$retorno->erro = false;
$retorno->mensagem = '';

db_inicio_transacao();
ini_set("memory_limit", "1024M");
try {
    $parametros = JSON::requestParameters();
    $instituicaoSessao = InstituicaoRepository::getInstituicaoSessao();

    switch ($parametros->acao) {
        case 'consulta':
            if (empty($parametros->rota)) {
                throw new ParameterException('É necessário informar uma rota para consultar a API.');
            }

            $metodo = 'GET';

            if (isset($parametros->metodo)) {
                $metodo = $parametros->metodo;
            }

            if (isset($parametros->cgmEmpregador)) {
                $cgm = CgmFactory::getInstanceByCgm($parametros->cgmEmpregador);
                $parametros->inscricaoEmpregador = $cgm->getCnpj();
            }

            if (isset($parametros->cgmContribuinte)) {
                $cgm = CgmFactory::getInstanceByCgm($parametros->cgmContribuinte);
                $parametros->inscricaoContribuinte = $cgm->getCnpj();
            }

            if (isset($parametros->dataInicio)) {
                $dataInicio = new DateTime($parametros->dataInicio);
                $parametros->dataInicio = $dataInicio->format("Y-m-d 00:00");
            }

            if (isset($parametros->dataFinal)) {
                $dataFinal = new DateTime($parametros->dataFinal);
                $parametros->dataFinal = $dataFinal->format("Y-m-d 23:59");
            }
            $exportar = new ESocial(Registry::get('app.config'), $parametros->rota);
            $exportar->setDados($parametros);
            $retorno->data  = $exportar->request($metodo);
            break;
    }
} catch (Exception $exception) {
    $retorno->erro = true;
    $retorno->mensagem = $exception->getMessage();
}

db_fim_transacao($retorno->erro);
echo JSON::create()->stringify($retorno);
