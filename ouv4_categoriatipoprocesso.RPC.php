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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('libs/db_app.utils.php');
require_once modification('libs/db_utils.php');
require_once modification('libs/JSON.php');
require_once modification('dbforms/db_funcoes.php');

use ECidade\Patrimonial\Protocolo\TipoProcesso\Model\CategoriaTipoProcesso as CategoriaTipoProcessoModel;
use ECidade\Patrimonial\Protocolo\TipoProcesso\Repository\CategoriaTipoProcesso as CategoriaTipoProcessoRepository;
use ECidade\Patrimonial\Protocolo\TipoProcesso\Collection\TipoProcesso as TipoProcessoCollection;
use ECidade\Patrimonial\Protocolo\TipoProcesso\Model\TipoProcesso as TipoProcessoModel;

$retorno = new stdClass();
$retorno->erro = false;
$retorno->mensagem = '';

db_inicio_transacao();

try {
    $parametrosRequest = JSON::requestParameters();
    $parametros = JSON::create()->parse($parametrosRequest->json);

    switch ($parametros->executa) {
        case 'salvar':
            $categoriaTipoProcessoModel = new CategoriaTipoProcessoModel();
            $categoriaTipoProcessoModel->setSequencial($parametros->sequencial);
            $categoriaTipoProcessoModel->setNome($parametros->nome);
            $categoriaTipoProcessoModel->setDescricao($parametros->descricao);

            $tipoProcessoCollection = new TipoProcessoCollection();

            foreach($parametros->tiposProcesso as $codigoTipoProcesso) {
                $tipoProcessoModel = new TipoProcessoModel();
                $tipoProcessoModel->setCodigo($codigoTipoProcesso);

                $tipoProcessoCollection->add($tipoProcessoModel);
            }

            $categoriaTipoProcessoModel->setTiposProcesso($tipoProcessoCollection);

            $categoriaTipoProcessoRepository = CategoriaTipoProcessoRepository::getInstancia();
            $categoriaTipoProcessoRepository->salvar($categoriaTipoProcessoModel);

            $retorno->mensagem = 'Categoria salva com sucesso.';

            break;

        case 'excluir':
            $categoriaTipoProcessoModel = new CategoriaTipoProcessoModel();
            $categoriaTipoProcessoModel->setSequencial($parametros->sequencial);

            $categoriaTipoProcessoRepository = CategoriaTipoProcessoRepository::getInstancia();
            $categoriaTipoProcessoRepository->remover($categoriaTipoProcessoModel);

            $retorno->mensagem = 'Categoria excluída com sucesso.';
            break;

        case 'buscarTiposProcessos':
            $categoriaTipoProcessoModel = new CategoriaTipoProcessoModel();
            $categoriaTipoProcessoModel->setSequencial($parametros->sequencial);

            $categoriaTipoProcessoRepository = CategoriaTipoProcessoRepository::getInstancia();
            $tipoProcessoCollection = $categoriaTipoProcessoRepository->buscarTiposProcessoVinculados($categoriaTipoProcessoModel);

            $retorno->tiposProcesso = array();

            foreach ($tipoProcessoCollection->getAll() as $tipoProcesso) {
                $dadosTipoProcesso = new stdClass();
                $dadosTipoProcesso->sequencial = $tipoProcesso->getCodigo();
                $dadosTipoProcesso->descricao = $tipoProcesso->getDescricao();

                $retorno->tiposProcesso[] = $dadosTipoProcesso;
            }

            break;
    }
} catch (Exception $exception) {
    $retorno->erro = true;
    $retorno->mensagem = $exception->getMessage();
}

db_fim_transacao($retorno->erro);

echo JSON::create()->stringify($retorno);
