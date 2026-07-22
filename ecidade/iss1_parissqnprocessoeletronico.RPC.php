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
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');

use cl_parametroprocessoeletronico;
use ECidade\Tributario\Issqn\Model\ParametroProcessoEletronico;
use ECidade\Tributario\Issqn\Repository\ParametroProcessoEletronicoRepository;

$parametros = JSON::requestParameters();

$retorno = new stdClass();
$retorno->status = 1;
$retorno->mensagem = '';

$clparametroprocessoeletronico = new cl_parametroprocessoeletronico();
$repository = ParametroProcessoEletronicoRepository::getInstance($clparametroprocessoeletronico);
$entidade = new ParametroProcessoEletronico();

try{

    db_inicio_transacao();

    switch ($parametros->exec){

        case "salvar":

            $entidade->fromState((array) $parametros);

            $repository->save($entidade);

            $retorno->mensagem = 'Parâmetros salvos com sucesso!';

            break;

        case "carregarDados":

            $entidade->fromState($repository->buscaConfiguracao());

            $retorno->dados = $entidade->toArray();

            break;

        default:
            throw new Exception("Opção inválida!");

    }

    db_fim_transacao(false);

} catch (Exception $erro){

    db_fim_transacao(true);
    $retorno->status = 2;
    $retorno->mensagem = $erro->getMessage();
}

$retorno->erro = $retorno->status == 2;
echo JSON::create()->stringify($retorno);