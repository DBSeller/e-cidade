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
$oRetorno    = (object)array( 'erro' => false, 'mensagem' => '');

try {

    db_inicio_transacao();

    switch ($oParametro->exec) {

        case 'incluirRegistros':
            
            if(empty($oParametro->data)) {
                throw new DBException('Informe a data para a marcação.');
            }

            if(empty($oParametro->horarios)) {
                throw new DBException('Informe pelo menos um horário para a marcação.');
            }
            
            if(empty($oParametro->matricula)) {
                throw new DBException('Informe a matrícula para a marcação.');
            }

            $oData = new DBDate($oParametro->data);

            $whereArquivoImportacao = array(
                'rh228_instituicao  = '. db_getsession('DB_instit'), 
                'rh228_data_inicio  = \''. $oData->getDate() .'\'',
                'rh228_data_fim     = \''. $oData->getDate() .'\'',
                'rh228_serial       ilike \'REGISTRO%MANUAL\''
            );
            $oDaoArquivoImportacao = new cl_pontoeletronicoarquivoimportacao();
            $sSqlArquivoImportacao = $oDaoArquivoImportacao->sql_query_file(null, "*", null, implode(' AND ', $whereArquivoImportacao));
            $rsArquivoImportacao   = db_query($sSqlArquivoImportacao);

            if(!$rsArquivoImportacao) {
                throw new DBException('Não foi possível consultar os arquivos de importação.'. pg_last_error());
            }
            
            $codigoArquivoImportacao = null;
            if(pg_num_rows($rsArquivoImportacao) > 0) {
                $codigoArquivoImportacao = db_utils::fieldsMemory($rsArquivoImportacao, 0)->rh228_sequencial;
            } else {

                $oDaoArquivoImportacao = new cl_pontoeletronicoarquivoimportacao();
                $oDaoArquivoImportacao->rh228_instituicao = db_getsession('DB_instit');
                $oDaoArquivoImportacao->rh228_arquivo     = null;
                $oDaoArquivoImportacao->rh228_serial      = 'REGISTRO MANUAL';
                $oDaoArquivoImportacao->rh228_data_inicio = $oData->getDate();
                $oDaoArquivoImportacao->rh228_data_fim    = $oData->getDate();
                
                if(!$oDaoArquivoImportacao->incluir()) {
                    throw new DBException($oDaoArquivoImportacao->erro_msg);
                }
                
                $codigoArquivoImportacao = $oDaoArquivoImportacao->rh228_sequencial;
            }
            
            $oServidor = ServidorRepository::getInstanciaByCodigo($oParametro->matricula);

            $oDaoArquivoImportacaoRegistro = new cl_pontoeletronicoarquivoimportacaoregistro();
            $oDaoArquivoImportacaoRegistro->rh229_pontoeletronicoarquivoimportacao = $codigoArquivoImportacao;
            $oDaoArquivoImportacaoRegistro->rh229_pis                              = $oServidor->getPISPASEP();
            $oDaoArquivoImportacaoRegistro->rh229_matricula                        = $oParametro->matricula;
            $oDaoArquivoImportacaoRegistro->rh229_data                             = $oData->getDate();
            $oDaoArquivoImportacaoRegistro->rh229_serial                           = 'REGISTRO MANUAL';

            $aHorarios = array_filter($oParametro->horarios);

            foreach ($aHorarios as $sHorario) {
                $oDaoArquivoImportacaoRegistro->rh229_sequencial = null;
                $oDaoArquivoImportacaoRegistro->rh229_hora = $sHorario;

                if(!$oDaoArquivoImportacaoRegistro->incluir()) {
                    throw new DBException($oDaoArquivoImportacaoRegistro->erro_msg);
                }

                $oRetorno->registro[] = array(
                    'sequencial'                       => $oDaoArquivoImportacaoRegistro->rh229_sequencial,
                    'pontoeletronicoarquivoimportacao' => $oDaoArquivoImportacaoRegistro->rh229_pontoeletronicoarquivoimportacao,
                    'pis'                              => $oDaoArquivoImportacaoRegistro->rh229_pis,
                    'matricula'                        => $oDaoArquivoImportacaoRegistro->rh229_matricula,
                    'nome'                             => $oServidor->getCgm()->getNome(),
                    'data'                             => $oData->getDate(DBDate::DATA_PTBR),
                    'hora'                             => $sHorario,
                    'serial'                           => $oDaoArquivoImportacaoRegistro->rh229_serial
                );
            }

            $oRetorno->mensagem = "Marcação incluída com sucesso.";
                
            break;

        case 'buscarRegistros':

            // if(empty($oParametro->data) && empty($oParametro->matricula)) {
                // throw new DBException('Informe a matrícula para a marcação.');
            // }

            $whereArquivoImportacaoRegistro = array(
                'instituicao                                     = '. db_getsession('DB_instit'),
                'recursoshumanos.pontoeletronicoarquivoimportacao.serial = \'REGISTRO MANUAL\''
            );

            if(!empty($oParametro->data)) {
                
                $oData                            = new DBDate($oParametro->data);
                $whereArquivoImportacaoRegistro[] = 'data = \''. $oData->getDate() .'\'';
            }
            
            if(!empty($oParametro->matricula)) {
                $whereArquivoImportacaoRegistro[] = 'matricula = '. $oParametro->matricula;
            }

            $camposArquivoImportacaoRegistro = array(
                'recursoshumanos.pontoeletronicoarquivoimportacaoregistro.rh229_sequencial',
                'recursoshumanos.pontoeletronicoarquivoimportacaoregistro.rh229_pontoeletronicoarquivoimportacao',
                'recursoshumanos.pontoeletronicoarquivoimportacaoregistro.rh229_pis',
                'recursoshumanos.pontoeletronicoarquivoimportacaoregistro.rh229_matricula',
                'recursoshumanos.pontoeletronicoarquivoimportacaoregistro.rh229_data',
                'recursoshumanos.pontoeletronicoarquivoimportacaoregistro.rh229_hora',
                'recursoshumanos.pontoeletronicoarquivoimportacaoregistro.rh229_serial',
            );
            $oDaoArquivoImportacaoRegistro   = new cl_pontoeletronicoarquivoimportacaoregistro();
            $sSqlArquivoImportacaoRegistro   = $oDaoArquivoImportacaoRegistro->sql_query(
                null,
                implode(', ', $camposArquivoImportacaoRegistro),
                "recursoshumanos.pontoeletronicoarquivoimportacaoregistro.rh229_sequencial ASC",
                implode(' AND ', $whereArquivoImportacaoRegistro)
            );
            $rsArquivoImportacaoRegistro     = db_query($sSqlArquivoImportacaoRegistro);

            if(!$rsArquivoImportacaoRegistro) {
                throw new DBException('Não foi possível consultar os arquivos de importação.'. pg_last_error());
            }
            
            $oRetorno->aRegistros = array();
            if(pg_num_rows($rsArquivoImportacaoRegistro) > 0) {
            
                $oRetorno->aRegistros = db_utils::makeCollectionFromRecord($rsArquivoImportacaoRegistro, function ($oRetorno) {

                    $oData     = new DBDate($oRetorno->rh229_data);
                    $oServidor = ServidorRepository::getInstanciaByCodigo($oRetorno->rh229_matricula);

                    return (object)array(
                        'sequencial'                       => $oRetorno->rh229_sequencial,
                        'pontoeletronicoarquivoimportacao' => $oRetorno->rh229_pontoeletronicoarquivoimportacao,
                        'pis'                              => $oRetorno->rh229_pis,
                        'matricula'                        => $oRetorno->rh229_matricula,
                        'nome'                             => $oServidor->getCgm()->getNome(),
                        'data'                             => $oData->getDate(DBDate::DATA_PTBR),
                        'hora'                             => $oRetorno->rh229_hora,
                        'serial'                           => $oRetorno->rh229_serial
                    );
                });
            }

            break;

        case 'salvarRegistros':

            if(empty($oParametro->data)) {
                throw new DBException('Informe a data para a marcação.');
            }
            
            if(empty($oParametro->hora)) {
                throw new DBException('Informe a hora para a marcação.');
            }
            
            if(empty($oParametro->sequencial)) {
                throw new DBException('Não foi possível identificar a marcação à alterar.');
            }

            $oDaoArquivoImportacaoRegistro = new cl_pontoeletronicoarquivoimportacaoregistro();
            $sSqlArquivoImportacaoRegistro = $oDaoArquivoImportacaoRegistro->sql_query_file($oParametro->sequencial);
            $rsArquivoImportacaoRegistro   = db_query($sSqlArquivoImportacaoRegistro);

            if(!$rsArquivoImportacaoRegistro) {
                throw new DBException('Não foi possível consultar os arquivos de importação.'. pg_last_error());
            }
            
            if(pg_num_rows($rsArquivoImportacaoRegistro) == 0) {
                throw new DBException('A marcação foi excluída, recarregue a tela para atualizar as marcações.');
            }
            
            $oData = new DBDate($oParametro->data);

            $oDaoArquivoImportacaoRegistro = new cl_pontoeletronicoarquivoimportacaoregistro();
            $oDaoArquivoImportacaoRegistro->rh229_data         = $oData->getDate();
            $oDaoArquivoImportacaoRegistro->rh229_hora         = $oParametro->hora;
            $oDaoArquivoImportacaoRegistro->rh229_serial       = 'REGISTRO MANUAL';
            $oDaoArquivoImportacaoRegistro->rh229_pontoeletronicoarquivoimportacao = $oParametro->pontoeletronicoarquivoimportacao;
            $oDaoArquivoImportacaoRegistro->rh229_matricula                        = $oParametro->matricula;
            $oDaoArquivoImportacaoRegistro->rh229_pis                              = $oParametro->pis;
            $oDaoArquivoImportacaoRegistro->rh229_sequencial   = $oParametro->sequencial;

            if(!$oDaoArquivoImportacaoRegistro->alterar($oParametro->sequencial)) {
                throw new DBException($oDaoArquivoImportacaoRegistro->erro_msg);
            }

            $oRetorno->mensagem = "Marcação alterada com sucesso.";
            break;

        case 'excluirRegistros':
            
            if(empty($oParametro->sequencial)) {
                throw new DBException('Não foi possível identificar a marcação à alterar.');
            }

            $oDaoArquivoImportacaoRegistro = new cl_pontoeletronicoarquivoimportacaoregistro();

            if(!$oDaoArquivoImportacaoRegistro->excluir($oParametro->sequencial)) {
                throw new DBException($oDaoArquivoImportacaoRegistro->erro_msg);
            }

            $oRetorno->mensagem = "Marcação excluída com sucesso.";
            break;
    }

    db_fim_transacao(false);

} catch (Exception $erro) {
  
    db_fim_transacao(true);
    $oRetorno->mensagem = $erro->getMessage();
    $oRetorno->erro     = true;
}

echo JSON::create()->stringify($oRetorno);
