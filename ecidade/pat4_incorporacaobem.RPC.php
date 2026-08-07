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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));
require_once (modification("classes/materialestoque.model.php"));

use ECidade\Patrimonial\Patrimonio\Incorporacao\Configuracao;
use ECidade\Patrimonial\Patrimonio\Incorporacao\Incorporacao;
use ECidade\Patrimonial\Patrimonio\Incorporacao\Model\MaterialIncorporadoModel;
use ECidade\Patrimonial\Patrimonio\Incorporacao\Repository\MaterialIncorporadoRepository;
use ECidade\Patrimonial\Patrimonio\Incorporacao\Repository\MaterialPendenteIncorporacaoRepository;

$parametros = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$retorno = new stdClass();
$retorno->iStatus = 1;
$retorno->message = '';

try {
    db_inicio_transacao();

    switch ($parametros->exec) {
        case "buscarConfiguracao":
            $configuracao = new Configuracao();
            $retorno->utiliza = $configuracao->utilizaIncorporacao() ? 'S' : 'N';
            break;
        case 'implantarIncorporacao':
            $configuracao = new Configuracao();
            $configuracao->implantar();
            $retorno->message = "Incorporação de Bens implantada.";
            break;
        case 'buscarItensPorEmpenho':
            $repository = new MaterialPendenteIncorporacaoRepository();
            $bens = $repository->getBensPorEmpenho($parametros->codigoEmpenho);
            $retorno->itens = $repository->toJson($bens);
            break;
        case 'incorporarBens':

            if (empty($parametros->codigo_bem)) {
                throw new Exception("Informe o bem.");
            }

            if (empty($parametros->bens_incorporar)) {
                throw new Exception("Informe ao menos um material/Serviço para incorporar ao bem.");
            }

            $bem = new Bem($parametros->codigo_bem);
            $materialIncorporarRepository  = new MaterialIncorporadoRepository();
            $materialPendentesRepository = new MaterialPendenteIncorporacaoRepository();
            foreach ($parametros->bens_incorporar as $item) {

                $bemIncorporar = new MaterialIncorporadoModel();
                $bemIncorporar->setQuantidade($item->quantidade);
                $bemIncorporar->setData(new DBDate(date('Y-m-d', db_getsession('DB_datausu'))));
                $bemIncorporar->setBem($bem);
                $bemIncorporar->setReavaliar($parametros->reavaliar);
                $bemIncorporar->setMaterialPendenteIncorporacao($materialPendentesRepository->getById($item->codigo));

                $materialIncorporarRepository->addMaterialIncorporavel($bemIncorporar);
            }

            $incorporacao = new Incorporacao();
            $incorporacao->setBem($bem);
            $incorporacao->setMateriais($materialIncorporarRepository);
            $incorporacao->setReavaliar($parametros->reavaliar);
            $incorporacao->setData(new DBDate(date('Y-m-d', db_getsession('DB_datausu'))));
            $incorporacao->incorporar();

            $retorno->message = "Incorporação de bens efetuada com sucesso.";

            break;

        case 'estornarIncorporacao':
             if (empty($parametros->codigo_bem)) {
                throw new Exception("Informe o bem.");
            }

            if (empty($parametros->itens_extornar)) {
                throw new Exception("Informe ao menos um material/Serviço para extornar incorporação.");
            }

            $bem = new Bem($parametros->codigo_bem);
            $materialIncorporarRepository  = new MaterialIncorporadoRepository();
            $materialPendentesRepository = new MaterialPendenteIncorporacaoRepository();


            foreach ($parametros->itens_extornar as $item) {

                $bemIncorporar = new MaterialIncorporadoModel();
                $bemIncorporar->setCodigo($item->codigo);
                $bemIncorporar->setQuantidade($item->quantidade);
                $bemIncorporar->setData(new DBDate($item->data_incorporacao));
                $bemIncorporar->setBem($bem);
                $bemIncorporar->setReavaliar($item->reavaliado);
                $bemIncorporar->setMaterialPendenteIncorporacao($materialPendentesRepository->getById($item->id_material_pendente));

                $materialIncorporarRepository->addMaterialIncorporavel($bemIncorporar);
            }

            $incorporacao = new Incorporacao();
            $incorporacao->setBem($bem);
            $incorporacao->setMateriais($materialIncorporarRepository);
            $incorporacao->setData(new DBDate(date('Y-m-d', db_getsession('DB_datausu'))));
            $incorporacao->usuario(new UsuarioSistema(db_getsession('DB_id_usuario')));

            $incorporacao->cancelar();

            $retorno->message = "Desprocessamento da incorporação de bens efetuado com sucesso.";
            break;
        case 'consultaIncorporacao' :

            if (empty($parametros->codigoBem)) {
                throw new Exception("Informe o código do bem.");
            }

            $dao = new cl_bemincorporado();
            $rs = db_query($dao->consultaMateriaisIncorporado($parametros->codigoBem));

            $retorno->itens = db_utils::makeCollectionFromRecord($rs, function ($dado) {
                $item = new stdClass();
                $item->codigo = $dado->t13_sequencial;
                $item->numpemp = $dado->e60_numemp;
                $item->empenho = "{$dado->e60_codemp}/{$dado->e60_anousu}";
                $item->vlr_empenhado = $dado->e60_vlremp;
                $item->reavaliado = $dado->t13_reavaliacao == 't';
                $item->quantidade = $dado->t13_quantidade;
                $item->vlr_unitario = $dado->t12_valorunitario;
                $item->vlr_incorporado = $dado->valor_incorporado;
                $item->data_incorporacao = $dado->t13_data;
                $item->descricao_item = $dado->m60_descr;
                $item->id_material_pendente = $dado->t12_sequencial;
                $item->servico = $dado->t12_servico == 't';
                return $item;
            });

            break;
    }

    db_fim_transacao(false);
} catch (Exception $eErro){
    db_fim_transacao(true);
    $retorno->iStatus  = 2;
    $retorno->message = $eErro->getMessage();
}
$retorno->erro = $retorno->iStatus == 2;
echo JSON::create()->stringify($retorno);
