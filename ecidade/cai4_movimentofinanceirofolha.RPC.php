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

$oParam = JSON::create()->parse(str_replace('\\', "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->status = 1;
$oRetorno->message = '';
$oRetorno->erro = false;

try {

    switch ($oParam->exec) {
        case 'consultar':
            //
            $oRetorno->transferencias = [];
            $transferenciasRalizar = sql_query_folha_recurso($oParam->ano, $oParam->mes, $oParam->tipofolha);
            if ($transferenciasRalizar) {
                $oRetorno->transferencias = $transferenciasRalizar;
            }
            break;

        case 'processar':
            db_inicio_transacao();

            $instituicao = InstituicaoRepository::getInstituicaoSessao();
            $cgm = $instituicao->getCgm()->getCodigo();

            $executarLancamento = true;
            if ($oParam->acao == "financeiro") {
                $executarLancamento = false;
            }

            $codigoOperacao = 16;
            $oParam->acao = 1;

            $transferenciasRalizar = $oParam->transferencias;

            if (count($transferenciasRalizar) == 0) {
                $msg  = "Aconteceu algo inesperado e não foi possível ";
                $msg .= "encontrar os dados para geração das transferências";
                throw new Exception($msg);
            }

            foreach ($transferenciasRalizar as $transferencia) {
                $recurso  = $transferencia[1];
                $reduzido = $transferencia[3];
                if ($recurso == "1") {
                    $reduzidoRecurso = $reduzido;
                    break;
                }
            }

            $slipsGerados = array();
            foreach ($transferenciasRalizar as $transferencia) {
                $recurso  = $transferencia[1];
                $reduzido = $transferencia[3];
                if ($recurso == "1") {
                    continue;
                }
                $contadebito  = $reduzido;
                $contacredito = $reduzidoRecurso;
                $historico    = 996;
                $valor        = (float) str_replace(",",".", str_replace(".", "", $transferencia[6]) );
                if ($oParam->evento == "proprio") {
                    $contadebito  = $reduzidoRecurso;
                    $contacredito = $reduzido;
                    $historico      = 995;
                }
                $oContaDebito  = new ContaPlanoPCASP(null, db_getsession("DB_anousu"), $contadebito, null);
                $oContaCredito = new ContaPlanoPCASP(null, db_getsession("DB_anousu"), $contacredito, null);

                if ($oContaDebito->getReduzido() == '') {
                    throw new BusinessException("A Conta Débito informada é inválida.");
                }

                if ($oContaCredito->getReduzido() == '') {
                    throw new BusinessException("A Conta Crédito informada é inválida.");
                }

                $oTransferencia = TransferenciaFactory::getInstance($codigoOperacao);
                $oTransferencia->setContaDebito($oContaDebito->getReduzido());
                $oTransferencia->setContaCredito($oContaCredito->getReduzido());
                $oTransferencia->setValor($valor);
                $oTransferencia->setHistorico($historico);
                $oTransferencia->setObservacao("Slip de movimento financeiro da folha");
                $oTransferencia->setTipoPagamento(0);
                $oTransferencia->setSituacao(1);
                $oTransferencia->setCodigoCgm($cgm);
                $oTransferencia->setCaracteristicaPeculiarCredito("000");
                $oTransferencia->setCaracteristicaPeculiarDebito("000");
                $oTransferencia->setData(date("Y-m-d", db_getsession("DB_datausu")));
                $oTransferencia->salvar();

                if ($executarLancamento) {
                    $oTransferencia->executaAutenticacao();
                    $oTransferencia->executarLancamentoContabil();
                }
                $slipsGerados[] = $oTransferencia->getCodigoSlip();
            }
            $sCodigos = implode(",", $slipsGerados);
            $oRetorno->message = "Slips criados com sucesso. Codigos: [{$sCodigos}]";
            break;

        default:
            throw new \Exception('Metodo ' . $oParam->exec . ' não existe;');
            break;

    }

    db_fim_transacao(false);

} catch (Exception $oErro) {
    db_fim_transacao(true);
    $oRetorno->status = 2;
    $oRetorno->erro = true;
    $oRetorno->message = $oErro->getMessage();
}
echo JSON::create()->stringify($oRetorno);


function sql_query_folha_recurso($ano, $mes, $tipofolha)
{
    $instit = db_getsession("DB_instit");
    switch ($tipofolha) {
        case "salario":
            $tabela = "gerfsal";
            $sigla  = "r14";
            break;

        case "complementar":
            $tabela = "gerfcom";
            $sigla  = "r48";
            break;

        case "ferias":
            $tabela = "gerffer";
            $sigla  = "r31";
            break;

        case "decimo":
            $tabela = "gerfs13";
            $sigla  = "r35";
            break;
    }

    $sqlFolhaRecurso  = " select o15_codigo as recurso, ";
    $sqlFolhaRecurso .= "        o15_descr  as descricao_recurso, ";
    $sqlFolhaRecurso .= "        case when o15_tipo = 1 then 'true' else 'false' end as livre, ";
    $sqlFolhaRecurso .= "        k13_descr  as descricao_conta,   ";
    $sqlFolhaRecurso .= "        k13_conta  as conta,             ";
    $sqlFolhaRecurso .= "        sum( case when {$sigla}_pd = 1 then x.valor when {$sigla}_pd = 1 then x.valor*1 end) valor ";
    $sqlFolhaRecurso .= "   from (select rh25_recurso as lota, ";
    $sqlFolhaRecurso .= "                {$sigla}_rubric, ";
    $sqlFolhaRecurso .= "                round(sum({$sigla}_valor),2) as valor, ";
    $sqlFolhaRecurso .= "                {$sigla}_pd, ";
    $sqlFolhaRecurso .= "                count({$sigla}_rubric) as soma, ";
    $sqlFolhaRecurso .= "                round(sum({$sigla}_quant),2) as quant ";
    $sqlFolhaRecurso .= "           from {$tabela} ";
    $sqlFolhaRecurso .= "                inner join rhpessoal    on rh01_regist = {$sigla}_regist ";
    $sqlFolhaRecurso .= "                inner join rhpessoalmov on rh02_regist = rh01_regist ";
    $sqlFolhaRecurso .= "                                       and rh02_anousu = {$sigla}_anousu ";
    $sqlFolhaRecurso .= "                                       and rh02_mesusu = {$sigla}_mesusu ";
    $sqlFolhaRecurso .= "                                       and rh02_instit = {$sigla}_instit ";
    $sqlFolhaRecurso .= "                left  join rhpesbanco   on rh44_seqpes = rh02_seqpes ";
    $sqlFolhaRecurso .= "                inner join rhregime     on rh02_codreg = rh30_codreg ";
    $sqlFolhaRecurso .= "                                       and rh30_instit = rh02_instit ";
    $sqlFolhaRecurso .= "                inner join rhlota       on rh02_lota   = r70_codigo ";
    $sqlFolhaRecurso .= "                                       and r70_instit  = rh02_instit ";
    $sqlFolhaRecurso .= "                left  join rhlotavinc   on rh25_codigo = r70_codigo ";
    $sqlFolhaRecurso .= "                                       and rh25_anousu = {$ano} ";
    $sqlFolhaRecurso .= "   where {$sigla}_anousu = {$ano} ";
    $sqlFolhaRecurso .= "     and {$sigla}_mesusu = {$mes} ";
    $sqlFolhaRecurso .= "     and {$sigla}_instit = {$instit} ";
    $sqlFolhaRecurso .= "     and {$sigla}_pd in (1,2) ";
    $sqlFolhaRecurso .= "     and r70_estrut between '0' and '999999' ";
    $sqlFolhaRecurso .= "   group by {$sigla}_rubric, ";
    $sqlFolhaRecurso .= "            lota,  ";
    $sqlFolhaRecurso .= "            {$sigla}_pd  ) as x ";
    $sqlFolhaRecurso .= "       inner join rhrubricas      on x.{$sigla}_rubric = rh27_rubric ";
    $sqlFolhaRecurso .= "                                 and rh27_instit = {$instit} ";
    $sqlFolhaRecurso .= "       left join orctiporec       on lota = o15_codigo ";
    $sqlFolhaRecurso .= "       left join rhcontasrec      on rh41_codigo = o15_codigo ";
    $sqlFolhaRecurso .= "                                 and rh41_anousu = {$ano} ";
    $sqlFolhaRecurso .= "                                 and rh41_instit = {$instit} ";
    $sqlFolhaRecurso .= "       left join saltes           on k13_conta   = rh41_conta ";
    $sqlFolhaRecurso .= "       left join rhrubelemento    on rh23_rubric = rh27_rubric ";
    $sqlFolhaRecurso .= "                                 and rh23_instit = rh27_instit ";
    $sqlFolhaRecurso .= "       left join rhrubretencao    on rh75_rubric = rh27_rubric ";
    $sqlFolhaRecurso .= "                                 and rh75_instit = rh27_instit ";
    $sqlFolhaRecurso .= "       left join retencaotiporec  on e21_sequencial = rh75_retencaotiporec ";
    $sqlFolhaRecurso .= "       left join retencaotipocalc on e32_sequencial = e21_retencaotipocalc ";
    $sqlFolhaRecurso .= "       left join retencaotiporecgrupo on e01_sequencial = e21_retencaotiporecgrupo ";
    $sqlFolhaRecurso .= " where 1 = 1 ";
    $sqlFolhaRecurso .= " group by o15_codigo, o15_descr, k13_descr, k13_conta ";
    $sqlFolhaRecurso .= " order by o15_codigo ";

    $rsFolhaRecurso = db_query($sqlFolhaRecurso);

    if (!$rsFolhaRecurso) {
        return false;
    }
    return db_utils::getCollectionByRecord($rsFolhaRecurso);

}
