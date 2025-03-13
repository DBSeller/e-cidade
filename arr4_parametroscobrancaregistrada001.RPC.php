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

use ECidade\Tributario\Arrecadacao\Model\ParametrosCobrancaRegistrada;
use ECidade\Tributario\Arrecadacao\Repository\ParametrosCobrancaRegistradaRepository;

$post = db_utils::postMemory($_REQUEST);
$post->json = str_replace("\\", "", $post->json);
$parametro = JSON::create()->parse($post->json);
$retorno = (object)array('erro' => false, 'mensagem' => '');

try {
    db_inicio_transacao();

    switch ($parametro->executa) {
        case "salvar":
            $parametrosCobrancaRegistrada = new ParametrosCobrancaRegistrada();
            $parametrosCobrancaRegistrada->setSequencial((!empty($parametro->ar28_sequencial) ? $parametro->ar28_sequencial : null));
            $parametrosCobrancaRegistrada->setUsuario((!empty($parametro->ar28_usuario) ? $parametro->ar28_usuario : null));
            $parametrosCobrancaRegistrada->setClientid((!empty($parametro->ar28_clientid) ? $parametro->ar28_clientid : null));
            $parametrosCobrancaRegistrada->setClientsecret((!empty($parametro->ar28_clientsecret) ? $parametro->ar28_clientsecret : null));
            $parametrosCobrancaRegistrada->setCodban((!empty($parametro->ar28_codban) ? $parametro->ar28_codban : null));
            $parametrosCobrancaRegistrada->setChavej((!empty($parametro->ar28_chavej) ? $parametro->ar28_chavej : null));

            $parametrosCobrancaRegistradaRepository = ParametrosCobrancaRegistradaRepository::getInstance();
            $parametrosCobrancaRegistradaRepository->persist($parametrosCobrancaRegistrada);

            $retorno->mensagem = 'Dados salvos com sucesso.';

            break;

        case "buscar":
            $parametrosCobrancaRegistrada = new ParametrosCobrancaRegistrada();
            $parametrosCobrancaRegistrada->setCodban($parametro->ar28_codban);

            $parametrosCobrancaRegistradaRepository = ParametrosCobrancaRegistradaRepository::getInstance();
            $oDados = $parametrosCobrancaRegistradaRepository->getByWebservice($parametrosCobrancaRegistrada);

            $retorno->ar28_sequencial = $oDados->getSequencial();
            $retorno->ar28_usuario = $oDados->getUsuario();
            $retorno->ar28_clientid = $oDados->getClientid();
            $retorno->ar28_clientsecret = $oDados->getClientsecret();
            $retorno->ar28_codban = $oDados->getCodban();
            $retorno->ar28_chavej = $oDados->getChavej();

            break;

        default:
            throw new Exception('Nenhuma ação encontrada.');
            break;
    }

    db_fim_transacao(false);
} catch (Exception $erro) {
    db_fim_transacao(true);

    $retorno->erro = true;
    $retorno->mensagem = $erro->getMessage();
}

echo JSON::create()->stringify($retorno);
