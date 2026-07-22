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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oPost       = db_utils::postMemory($_REQUEST);
$oPost->json = str_replace("\\","",$oPost->json);
$oParametro  = JSON::create()->parse($oPost->json);
$oRetorno    = (object)array( 'erro' => false, 'mensagem'=> '');

try {

  db_inicio_transacao();

  switch ($oParametro->exec) {

    case 'salvarJornadaServidor':

      if(empty($oParametro->jornada)) {
        throw new ParameterException("Informe a jornada que será vinculado ao servidor.");
      }

      if(empty($oParametro->dataInicio)) {
        throw new ParameterException("Informe uma data de inicio válida para a jornada.");
      }

      if(empty($oParametro->matricula) && empty($oParametro->matriculas) && empty($oParametro->selecao)) {
        throw new ParameterException("Informe ao menos um servidor.");
      }

      if(!empty($oParametro->matricula)) {

        $matriculas = array($oParametro->matricula);

      } else {

        if(!empty($oParametro->matriculas)) {
          $matriculas = $oParametro->matriculas;
        } else {
          $iMesFolha = DBPessoal::getMesFolha();
          $iAnoFolha = DBPessoal::getAnoFolha();
          $matriculas = array_keys(ServidorRepository::getServidoresBySelecao($iAnoFolha,$iMesFolha,$oParametro->selecao));
        }
      }

      $datas = array();

      if(!empty($oParametro->dataFim)) {

        $datas = DBDate::getDatasNoIntervalo(new DBDate($oParametro->dataInicio), new DBDate($oParametro->dataFim));

      } else {

        $datas = array(
          new DBDate($oParametro->dataInicio)
        );
      }

      foreach ($matriculas as $matricula) {

        foreach ($datas as $data) {

          $oDaoJornadaservidor = new cl_jornadaservidor();

          $whereExcluir = array(
            " rh212_data      = '{$data->getDate()}'",
            " rh212_matricula = {$matricula}"
          );

          if(!$oDaoJornadaservidor->excluir(null, implode(' AND ', $whereExcluir))) {
            throw new DBException($oDaoJornadaservidor->erro_msg);;
          }

          $oDaoJornadaservidor->rh212_sequencial = null;
          $oDaoJornadaservidor->rh212_data       = $data->getDate();
          $oDaoJornadaservidor->rh212_matricula  = $matricula;
          $oDaoJornadaservidor->rh212_jornada    = $oParametro->jornada;

          if(!$oDaoJornadaservidor->incluir()) {
            throw new DBException($oDaoJornadaservidor->erro_msg);;
          }
        }
      }

      $oRetorno->mensagem  = "Jornada salva com sucesso.";
      break;

    case 'getJornadaServidor':

      if(!empty($oParametro->codigo)) {

        $whereJornadas[] = " rh212_sequencial = '{$oParametro->codigo}'";

      } else {

        if(!empty($oParametro->data)) {
          $whereJornadas[] = " rh212_data = '{$oParametro->data}'";
        }

        if(!empty($oParametro->matricula)) {
          $whereJornadas[] = " rh212_matricula = {$oParametro->matricula}";
        }
      }

      $camposJornada  = "*";
      $camposJornada .= ",(select z01_nome from cgm where z01_numcgm = rh01_numcgm) as nome";

      $oDaoJornadaservidor = new cl_jornadaservidor();
      $sqlJornadas         = $oDaoJornadaservidor->sql_query(null, $camposJornada, null, implode(' AND ', $whereJornadas));
      $rsJornadaservidor   = db_query($sqlJornadas);

      $oRetorno->jornadas = db_utils::makeCollectionFromRecord($rsJornadaservidor, function($dados) {

        $data = new DBDate($dados->rh212_data);

        return (object)array(
          'sequencial'  => $dados->rh212_sequencial,
          'jornada'     => (object)array(
                              'codigo'    => $dados->rh212_jornada,
                              'descricao' => $dados->rh188_descricao,
                              'tipo'      => $dados->rh188_tipo,
                            ),
          'data'        => $data->getDate(DBDate::DATA_PTBR),
          'servidor'    => (object)array(
                              'matricula' => $dados->rh212_matricula,
                              'nome'      => $dados->nome
                            )
        );
      });
      break;

    case 'excluirJornadaServidor':

      if(empty($oParametro->iCodigoJornadaServidor)) {
        throw new ParameterException("Informe o código do vínculo entre jornada e servidor para excluir.");
      }

      $oDaoJornadaservidor = new cl_jornadaservidor();

      $sqlJornadaservidor = $oDaoJornadaservidor->sql_query_file($oParametro->iCodigoJornadaServidor);
      $rsJornadaservidor  = db_query($sqlJornadaservidor);

      if(pg_num_rows($rsJornadaservidor) == 0) {
        throw new BusinessException("Não há vínculos de jornadas e servidor cadastrados com o código informado ({$oParametro->iCodigoJornadaServidor}).");
      }

      if(!$oDaoJornadaservidor->excluir($oParametro->iCodigoJornadaServidor)) {
        throw new DBException($oDaoJornadaservidor->erro_msg);;
      }

      $oRetorno->mensagem = "Jornada excluído com sucesso.";
      break;
  }

  db_fim_transacao(false);

} catch (Exception $eErro) {

  db_fim_transacao(true);

  $oRetorno->erro     = true;
  $oRetorno->mensagem = $eErro->getMessage();
}

echo JSON::create()->stringify($oRetorno);
