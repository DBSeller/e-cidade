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

use ECidade\V3\Extension\Registry;

$parametros = JSON::requestParameters();

$retorno = new stdClass();
$retorno->status = 1;
$retorno->mensagem = '';

$containerTributario   = Registry::get('app.container')->get('tributario.container');

try{

    db_inicio_transacao();

    switch ($parametros->exec){

        case "desfazerParcelamento":

            $parcelamentoForoParaDividaService = $containerTributario->get('ParcelamentoForoParaDividaService');

            if(empty($parametros->parcelamento)){
                throw new BusinessException("Campo Parcelamento é de preenchimento obrigatório");
            }

            if($parametros->filtrar == 1){
                if(
                    empty($parametros->receitas) &&
                    empty($parametros->dividas) &&
                    empty($parametros->exercicioInicio) &&
                    empty($parametros->exercicioFim)
                ) {
                    throw new BusinessException("Nenhum filtro selecionado!");
                }

                if(empty($parametros->tipoDebito)) {
                    throw new BusinessException("Obrigatório prencher tipo de débito!");
                }

                if(
                    (!empty($parametros->exercicioInicio) &&
                    empty($parametros->exercicioFim)) ||
                    (empty($parametros->exercicioInicio) &&
                    !empty($parametros->exercicioFim))
                ) {
                    throw new BusinessException("Necessário preencher exercício inicio e fim!");
                }

                $parcelamentoForoParaDividaService->setFiltrarDividas(true)
                                                  ->setReceitas($parametros->receitas)
                                                  ->setTipoDebito($parametros->tipoDebito)
                                                  ->setDividas($parametros->dividas)
                                                  ->setExercicioInicio($parametros->exercicioInicio)
                                                  ->setExercicioFim($parametros->exercicioFim);

            }


            $parcelamentoForoParaDividaService->setParcelamento($parametros->parcelamento);
            $parcelamentoForoParaDividaService->execute();

            $retorno->mensagem = "Parcelamento do foro passado para parcelamento de dívida com sucesso!";
            break;

        default:
            throw new Exception("Opção inválida!");
            break;

    }

    db_fim_transacao(false);

} catch (Exception $erro){

    db_fim_transacao(true);
    $retorno->status = 2;
    $retorno->mensagem = $erro->getMessage();
}

$retorno->erro = $retorno->status == 2;
echo JSON::create()->stringify($retorno);