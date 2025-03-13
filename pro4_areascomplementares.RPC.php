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

use ECidade\Tributario\Projetos\Obras\Converter\AreaComplementar as AreaComplementarConverter;
use ECidade\Tributario\Projetos\Obras\Model\AreaComplementar as AreaComplementarModel;
use ECidade\Tributario\Projetos\Obras\Repository\Construcao as ConstrucaoRepository;
use ECidade\Tributario\Projetos\Obras\Model\Obra;
use ECidade\Tributario\Projetos\Obras\Repository\AreaComplementar as AreaComplementarRepository;

$post = db_utils::postMemory($_REQUEST);
$post->json = str_replace("\\", "", $post->json);
$parametro = JSON::create()->parse($post->json);
$retorno = (object)array('erro' => false, 'mensagem' => '');

try {
    db_inicio_transacao();

    switch ($parametro->executa) {
        case 'buscar':
            $obra = new Obra();
            $obra->setSequencial($parametro->obra !== '' ? $parametro->obra : null);

            $areaComplementarRepository = AreaComplementarRepository::getInstance();
            $areaComplementarCollection = $areaComplementarRepository->getAreasByObra($obra);
            $retorno->areasComplementares = AreaComplementarConverter::collectionToArrayStdClass($areaComplementarCollection);

            break;

        case 'salvar':
            $evento = $parametro->evento;

            $obra = new Obra();
            $obra->setSequencial($evento->obra);

            $construcaoRepository = ConstrucaoRepository::getInstance();
            $construcao = $construcaoRepository->getConstrucaoByObra($obra);

            $areaComplementarModel = new AreaComplementarModel();
            $areaComplementarModel->setSequencial($evento->sequencial !== '' ? $evento->sequencial : null);
            $areaComplementarModel->setTipoAreaComplementar($evento->tipoAreaComplementar);
            $areaComplementarModel->setTipoLancamento($evento->tipoLancamento);
            $areaComplementarModel->setTipoConstrucao($evento->tipoConstrucao);
            $areaComplementarModel->setOcupacao($evento->ocupacao);
            $areaComplementarModel->setMedidaAreaDescoberta($evento->medidaAreaDescoberta);
            $areaComplementarModel->setMedidaAreaCoberta($evento->medidaAreaCoberta);
            $areaComplementarModel->setDescricao($evento->descricao);
            $areaComplementarModel->setConstrucao($construcao);

            $areaComplementarRepository = AreaComplementarRepository::getInstance();
            $areaComplementarRepository->save($areaComplementarModel);

            $retorno->mensagem = 'Área Complementar salva com sucesso.';

            break;

        case 'excluir':
            $areaComplementarModel = new AreaComplementarModel();
            $areaComplementarModel->setSequencial($parametro->sequencial);

            $areaComplementarRepository = AreaComplementarRepository::getInstance();
            $areaComplementarRepository->excluir($areaComplementarModel);

            $retorno->mensagem = 'Área Complementar excluída com sucesso.';

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
