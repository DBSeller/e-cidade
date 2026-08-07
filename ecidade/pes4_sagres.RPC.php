<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
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

use ECidade\RecursosHumanos\Pessoal\Sagres\SagresFiscal;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');
require_once(modification("libs/JSON.php"));

$oParam     = JSON::requestParameters();
$oJson      = new services_json();
$oRetorno   = new stdClass();

$oRetorno->erro  = false;
$oRetorno->message = '';

switch ($oParam->exec) {
    case 'gerarSagres':
        try {
            // Setados para manter o padrão.
            $oParam->admiss = true;
            $oParam->periodo = 'mensal';

            $ano = date('Y');
            $instituicoes = InstituicaoRepository::getInstituicoes();
            $instituicao = InstituicaoRepository::getInstituicaoSessao();
    
            if ($instituicao->getTipo() == 2) {
                $codigoInstituicoes = [$instituicao->getCodigo()];
            } else {
                $codigoInstituicoes = array_filter($instituicoes, function (Instituicao $instituicao) {
                    if ($instituicao->getTipo() != 2) {
                        return $instituicao->getCodigo();
                    }
                });
                $codigoInstituicoes = array_keys($codigoInstituicoes);
            }
    
            $departamento = DBDepartamentoRepository::getDBDepartamentoByCodigo(db_getsession('DB_coddepto'));
    
            if (empty($relatorios)) {
                throw new Exception("Nenhum relatório selecionado.");
            }
            
            $oParam->formatos['txt'] = isset($oParam->txt);

            $oParam->folder = '';
            $oParam->dataSQL = new stdClass;

            $oParam->dataSQL->mes = $oParam->mes;
            $oParam->dataSQL->ano = $oParam->ano;
            $oParam->folder = $oParam->ano.'/'.$oParam->mes;
            
            if (empty($oParam->txt)) {
                throw new Exception("Formato não selecionado.");
            }
            
            $sagresFiscal = new SagresFiscal(
                $oParam,
                $departamento,
                $codigoInstituicoes,
                $ano,
                $oParam->codigoTCE
            );

            $sagresFiscal->processarArquivos($relatorios, $oParam);
            $arquivoZip = $sagresFiscal->comprimir($oParam);
            
            $oRetorno->zip = $arquivoZip;
            $oRetorno->arquivos = $sagresFiscal->getArquivosEmitidos();
            $oRetorno->mensagem = "Arquivo gerado com sucesso!";
        } catch (Exception $e) {
            $oRetorno->erro   = true;
            $oRetorno->message = utf8_encode($e->getMessage());
        }
        
        break;
}

function convertDate($data, $format)
{
    switch ($format) {
        case 'd/m/Y':
            $date1 = DateTime::createFromFormat('Y-m-d', $data);
            return $date1->format('d/m/Y');
        break;
      
        case 'Y-m-d':
            $ano= substr($data, 6);
            $mes= substr($data, 3, -5);
            $dia= substr($data, 0, -8);
            return $ano."-".$mes."-".$dia;
        break;
      
        case 'dmY':
            $timestamp = strtotime($data);
            return date("dmY", $timestamp);
        break;

        case 'Y':
            $timestamp = strtotime($data);
            return date("Y", $timestamp);
        break;
    }
}

echo $oJson->encode($oRetorno);
