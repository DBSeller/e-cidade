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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
$oParam            = JSON::create()->parse( str_replace("\\","",$_POST["json"]) );
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$oRetorno->erro    = false;
$anousu            = db_getsession('DB_anousu');

try {
  switch ($oParam->exec) {

    case 'getDotacoesConfiguradas':

      $oDaoSiconfdotacaofinalidade = new cl_siconfidotacaofinalidade();
      $campos  = " c119_sequencinullal as sequencial,";
      $campos .= " c119_coddot as dotacao,";
      $campos .= " o41_descr as descricao,";
      $campos .= " c119_tipo as tipo ";
      $where   = "orcdotacao.o58_anousu = {$anousu}";

      $sSql = $oDaoSiconfdotacaofinalidade->sql_query_dados_dotacao($campos, "c119_sequencial", $where);
      $rsSiconfdotacaofinalidade   = db_query($sSql);
      if ( ! $rsSiconfdotacaofinalidade ) {
        throw new Exception('Ocorreu algo inesperado ao buscar as dotações configuradas para MDE/ASPS.');
      }

      $oRetorno->aDotacoesConfiguradas = db_utils::getCollectionByRecord($rsSiconfdotacaofinalidade);
      break;

    case 'salvar':

      db_inicio_transacao();
      $where = "c119_coddot is null ";
      if ( ! empty($oParam->orgao) ) {
          $contendo = ($oParam->contendo_orgao == 'notin'? 'not in': 'in');
          $where .= " and o58_orgao $contendo ({$oParam->orgao}) ";
      }
      if ( ! empty($oParam->unidade) ) {
          $contendo = ($oParam->contendo_unidade == 'notin'? 'not in': 'in');
          $where .= " and o58_unidade $contendo ({$oParam->unidade}) ";
      }
      if ( ! empty($oParam->funcao) ) {
          $contendo = ($oParam->contendo_funcao == 'notin'? 'not in': 'in');
          $where .= " and o58_funcao $contendo ({$oParam->funcao}) ";
      }
      if ( ! empty($oParam->subfuncao) ) {
          $contendo = ($oParam->contendo_subfuncao == 'notin'? 'not in': 'in');
          $where .= " and o58_subfuncao $contendo ({$oParam->subfuncao}) ";
      }

      if ( ! empty($oParam->projeto_atividade) ) {
          $contendo = ($oParam->contendo_projeto_atividade == 'notin'? 'not in': 'in');
          $where .= " and o58_projativ $contendo ({$oParam->projeto_atividade}) ";
      }

      if ( ! empty($oParam->recurso) ) {
          $contendo = ($oParam->contendo_recurso == 'notin'? 'not in': 'in');
          $where   .= " and o58_codigo $contendo ({$oParam->recurso}) ";
      }

      if ( ! empty($oParam->programa) ) {
          $contendo = ($oParam->contendo_programa == 'notin'? 'not in': 'in');
          $where .= " and o58_programa $contendo ({$oParam->programa}) ";
      }

      $oDaoDotacao  = new cl_orcdotacao();
      $campos = " o58_coddot as coddot, o58_anousu as exercicio ";
      $sSqlDotacoes = $oDaoDotacao->sql_query_dotacao_finalidade($campos,null, $where);
      $rsDotacoes   = db_query($sSqlDotacoes);
      $dotacoes     = db_utils::getCollectionByRecord($rsDotacoes);

      if (count($dotacoes) === 0) {
          throw new Exception("Não foi encontrado nenhuma dotação para o filtro selecionado.");
      }
      foreach ( $dotacoes as $dotacao ) {

          $oDaoSiconfdotacaofinalidade = new cl_siconfidotacaofinalidade();
          $oDaoSiconfdotacaofinalidade->c119_coddot = $dotacao->coddot;
          $oDaoSiconfdotacaofinalidade->c119_anousu = $dotacao->exercicio;
          $oDaoSiconfdotacaofinalidade->c119_tipo   = $oParam->tipo;
          $oDaoSiconfdotacaofinalidade->incluir();
          if ( $oDaoSiconfdotacaofinalidade->erro_status == "0" ) {
              throw new Exception("Ocorreu algo inesperado ao incluir configuração de dotação por tipo de despesa. ".$oDaoSiconfdotacaofinalidade->erro_msg);
          }
      }
      db_fim_transacao(false);

    break;

    case 'excluir':

        $oDaoSiconfdotacaofinalidade = new cl_siconfidotacaofinalidade();
        $sSequenciais = $oParam->sequenciais;
        $where = " c119_sequencial in ({$sSequenciais}) ";
        $oDaoSiconfdotacaofinalidade->excluir(null,$where);
        if ( $oDaoSiconfdotacaofinalidade->erro_status == "0" ) {
            throw new Exception("Ocorreu algo inesperado ao excluir os registros selecionados. ".$oDaoSiconfdotacaofinalidade->erro_msg);
        }

        $oRetorno->mensagem = "Exclusão efetuada com sucesso.";
        $oRetorno->error    = true;

    break;
  }

} catch (Exception $oException) {

  db_fim_transacao(true);
  $oRetorno->mensagem = $oException->getMessage();
  $oRetorno->erro = true;

}
echo JSON::create()->stringify($oRetorno);
