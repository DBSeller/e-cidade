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

require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_liborcamento.php"));

$oJson             = JSON::create();
$oParam            = $oJson->parse(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$lErro             = false;
$sMensagem         = "";

$oContaPadrao          = new ContaPlanoPCASP(null, db_getsession("DB_anousu"), 261873, null);
$sDescricaoContaPadrao = $oContaPadrao->getDescricao();
$iReduzidoContaPadrao  = $oContaPadrao->getReduzido();

switch($oParam->exec) {

    case "gerarSlip":

        db_inicio_transacao();
        try {

            $sqlConta = <<<SQL
                               select k12_conta as conta_credito
                                 from corcla 
                                      inner join corrente c on c.k12_data = corcla.k12_data 
                                                           and c.k12_id   = corcla.k12_id 
                                                           and c.k12_autent = corcla.k12_autent 
                                where k12_codcla = {$oParam->codcla} 
                                limit 1
SQL;
            $rsConta = db_query($sqlConta);
            if (! $rsConta || pg_num_rows($rsConta) === 0 ) {
               throw new BusinessException("Classificação do arquivo não encontrada.");
            }

            $conta_credito = db_utils::fieldsMemory($rsConta,0)->conta_credito;

            foreach ($oParam->aSlipsGerar as $slip) {

                $conta_debito  = $slip->conta_debito;

                $tipoOperacaoSlip = 13;
                if ( $conta_debito === $oContaPadrao->getReduzido() ) {
                    $tipoOperacaoSlip = 5;
                    $instituicao = InstituicaoRepository::getInstituicaoSessao();
                    $slip->cgm = $instituicao->getNumeroCgm();
                }

                $oContaDebito  = new ContaPlanoPCASP(null, db_getsession("DB_anousu"), $conta_debito, null);
                $oContaCredito = new ContaPlanoPCASP(null, db_getsession("DB_anousu"), $conta_credito, null);

                if ($oContaDebito->getReduzido() == '') {
                    throw new BusinessException("A Conta Débito informada é inválida.");
                }

                if ($oContaCredito->getReduzido() == '') {
                    throw new BusinessException("A Conta Crédito informada é inválida.");
                }

                $oTransferencia = TransferenciaFactory::getInstance($tipoOperacaoSlip);

                $oTransferencia->setContaDebito(  $oContaDebito->getReduzido());
                $oTransferencia->setContaCredito( $oContaCredito->getReduzido());

                $oTransferencia->setValor(str_replace(",", ".", $slip->valor));
                $oTransferencia->setHistorico(1);
                $oTransferencia->setObservacao("Transferências de custas geradas automaticas a partir da classificação código {$oParam->codcla}");
                $oTransferencia->setTipoPagamento(0);
                $oTransferencia->setSituacao(1);
                $oTransferencia->setCodigoCgm($slip->cgm);
                $oTransferencia->setCaracteristicaPeculiarDebito("000");
                $oTransferencia->setCaracteristicaPeculiarCredito("000");
                $oTransferencia->setData(date("Y-m-d",db_getsession("DB_datausu")));
                $oTransferencia->salvar();

                $daoSlipcustasclassificacao              = new cl_slipcustasclassificacao();
                $daoSlipcustasclassificacao->k190_codcla = $oParam->codcla;
                $daoSlipcustasclassificacao->k190_slip   = $oTransferencia->getCodigoSlip();
                $daoSlipcustasclassificacao->incluir();
                if ($daoSlipcustasclassificacao->erro_status == "0") {
                    throw new BusinessException("Ocorreu algo inesperado ao incluir a transferência bancária.");
                }
                $oRetorno->aSlips[] = $oTransferencia->getCodigoSlip();
            }

            $aTransferencias   = implode(", ", $oRetorno->aSlips);
            $oRetorno->message = urlencode("Transferência(s) [{$aTransferencias}] salva(s) com sucesso.");

            db_fim_transacao(false);

        } catch (Exception $eErro) {

            $oRetorno->message = str_replace("\n", "\\n", urlencode($eErro->getMessage()));
            $oRetorno->status  = 2;
            db_fim_transacao(true);
        }

        break;

    case 'pesquisarRegistros' :

        $instituicao = db_getsession("DB_instit");
        $ano         = db_getsession("DB_anousu");

        $sqlSlips = <<<SQL
select z01_numcgm as cgm,
       nome,
       receita_codigo,
       receita_descricao,
       case when c61_reduz is null then {$iReduzidoContaPadrao} else c61_reduz end as reduzido,
       case when c60_descr is null then '{$sDescricaoContaPadrao}' else c60_descr end as conta,
       sum(valor) as valor
  from ( select z01_numcgm,
               case
                when z01_nome is not null then z01_nome
                else 'MUNICIPIO DE NITEROI'
              end as nome,
              case
                when cgm.z01_numcgm is null then 0
                else receita_codigo
              end as receita_codigo,
              case
                when cgm.z01_numcgm is null then 'SLIP - PREFEITURA'
                else receita_descricao
              end as receita_descricao,
              sum(receita_valor) as valor

         from (select disrec.k00_receit as receita_codigo,
                      tabrec.k02_descr  as receita_descricao,
                      sum(disrec.vlrrec) as receita_valor
                 from discla
                      inner join disrec         on disrec.codcla      = discla.codcla
                      inner join tabrec         on tabrec.k02_codigo  = disrec.k00_receit
                where discla.codcla = {$oParam->codcla} 
                  and discla.instit = {$instituicao}
                group by receita_codigo, receita_descricao
                order by receita_codigo 
              ) as receita
              left join taxa on taxa.ar36_receita in (select k02_codigo
                                                        from tabrec
                                                       where k02_codigo = receita.receita_codigo
                                                          or k02_reccredito = receita.receita_codigo)
              left join favorecidotaxa on favorecidotaxa.v87_taxa = taxa.ar36_sequencial
              left join favorecido on favorecido.v86_sequencial = favorecidotaxa.v87_favorecido
              left join cgm on cgm.z01_numcgm = favorecido.v86_numcgm
        group by z01_numcgm,
                 nome,
                 receita_codigo,
                 receita_descricao
        order by 1 ) as x
        left join tabplan        on tabplan.k02_codigo  = receita_codigo
                                and tabplan.k02_anousu = {$ano}
        left join conplanoreduz  on conplanoreduz.c61_reduz  = tabplan.k02_reduz
                                 and conplanoreduz.c61_anousu = tabplan.k02_anousu
        left join conplano       on conplanoreduz.c61_codcon = conplano.c60_codcon
                                and conplanoreduz.c61_anousu = conplano.c60_anousu 
   
group by cgm,
         nome,
         receita_codigo,
         receita_descricao,
         conta,
         reduzido
order by cgm
SQL;

        // 21664
//        die($sqlSlips);
        $rsSlip = db_query($sqlSlips);
        $oRetorno->status = '1';
        $oRetorno->dados  = db_utils::getCollectionByRecord($rsSlip);

        break;

    case 'buscarClassificacoes' :

        $sqlClassificacoes = <<<SQL
        select codcla as classificacao, 
               codret as codretorno,
               dtcla  as data_classificacao,
               dtaute as data_autenticacao 
          from discla
               left join slipcustasclassificacao sc on sc.k190_codcla = codcla
         where codret = {$oParam->codret}
           and dtaute is not null
           and k190_codcla is null
SQL;

        $rsClassificacoes = db_query($sqlClassificacoes);
        $oRetorno->status = '1';
        $oRetorno->dados  = db_utils::getCollectionByRecord($rsClassificacoes);

        break;

}

echo $oJson->stringify($oRetorno);
