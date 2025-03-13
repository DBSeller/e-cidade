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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("libs/JSON.php"));

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$oRetorno->erro = false;

try {
    switch ($oParam->exec) {

        case 'vincularContas' :

            $sLockFile = "/tmp/sigfisvinculo.lock";
            if (file_exists($sLockFile)) {
                while(file_exists($sLockFile)) {
                    if (!file_exists($sLockFile)) {
                        break;
                    }
                }
            }
            $rsFileLock = fopen($sLockFile, 'w');
            fputs($rsFileLock, db_getsession("DB_id_usuario"));

            if (!file_exists('config/sigfis/vinculoplanoconta.xml')) {

                $oXmlWriter = new XMLWriter();
                $oXmlWriter->openMemory();
                $oXmlWriter->setIndent(true);
                $oXmlWriter->startDocument('1.0','ISO-8859-1');
                $oXmlWriter->endDtd();
                $oXmlWriter->startElement("contas");
                $oXmlWriter->startElement("conta");
                $oXmlWriter->writeAttribute("contatce", $oParam->contatce);
                $oXmlWriter->writeAttribute("contaplano", $oParam->contaplano);
                $oXmlWriter->writeAttribute("naturezasaldo", $oParam->origemsaldo);
                $oXmlWriter->endElement();
                $oXmlWriter->endElement();
                $strBuffer  = $oXmlWriter->outputMemory();
                $rsXMl      = fopen('config/sigfis/vinculoplanoconta.xml', 'w');
                fputs($rsXMl, $strBuffer);
                fclose($rsXMl);
            } else {

                $oDomXml  = new DOMDocument();
                $oDomXml->preserveWhiteSpace = false;
                $oDomXml->formatOutput       = true;
                $oDomXml->load('config/sigfis/vinculoplanoconta.xml');
                $oPlano      = $oDomXml->getElementsByTagName("contas");
                $aContas     = $oDomXml->getElementsByTagName("conta");
                $lAchouConta = false;
                foreach ($aContas as $oConta) {

                    $iCodigoTCE   = $oConta->getAttribute("contatce");
                    $iCodigoConta = $oConta->getAttribute("contaplano");
                    if ($iCodigoConta == $oParam->contaplano && $iCodigoTCE == $oParam->contatce) {

                        $lAchouConta = true;
                        break;
                    }
                }
                if ($lAchouConta) {

                    $oRetorno->status  = 2;
                    $oRetorno->message = urlencode('Conta já vinculadas.');
                } else {

                    $oConta = $oDomXml->createElement("conta");
                    $oConta->setAttribute('contatce', $oParam->contatce);
                    $oConta->setAttribute('contaplano', $oParam->contaplano);
                    $oConta->setAttribute('naturezasaldo', $oParam->origemsaldo);
                    $oPlano->item(0)->appendChild($oConta);
                    $oDomXml->save('config/sigfis/vinculoplanoconta.xml');
                }
            }
            unlink($sLockFile);
            break;

        case 'getVinculos':

            $sLockFile = "/tmp/sigfisvinculo.lock";
            if (file_exists($sLockFile)) {
                while(file_exists($sLockFile)) {
                    if (!file_exists($sLockFile)) {
                        break;
                    }
                }
            }
            $rsFileLock = fopen($sLockFile, 'w');
            $oDomXml= new DOMDocument();
            $oDomXml->preserveWhiteSpace = false;
            $oDomXml->formatOutput       = true;
            $oDomXml->load('config/sigfis/vinculoplanoconta.xml');
            $oNoConta            = $oDomXml->getElementsByTagName("contas");
            $aContas             = $oDomXml->getElementsByTagName("conta");
            $aRecursosVinculados = array();
            $oDaoConplano        = db_utils::getDao("conplano");
            foreach ($aContas as $oConta) {

                $iCodigoTCE           = $oConta->getAttribute("contatce");
                $iCodigoConta         = $oConta->getAttribute("contaplano");
                $sSqlDescricaoConta   = $oDaoConplano->sql_query_file($iCodigoConta, db_getsession("DB_anousu"));
                $rsDescricaoConta     = $oDaoConplano->sql_record($sSqlDescricaoConta);
                if ($oDaoConplano->numrows == 1) {

                    $oDadosConta       = db_utils::fieldsMemory($rsDescricaoConta, 0);
                    $sDescricaoConta   = urlencode($oDadosConta->c60_descr);
                    $sEstruturalConta  = urlencode($oDadosConta->c60_estrut);

                    $oContaVinculado                = new stdClass();
                    $oContaVinculado->descricaoconta= $sDescricaoConta;
                    $oContaVinculado->estrutural    = $sEstruturalConta;
                    $oContaVinculado->codigotce     = $iCodigoTCE;
                    $oContaVinculado->codigoecidade = $iCodigoConta;
                    $aContasVinculados[]            = $oContaVinculado;
                }
            }

            $oRetorno->contasvinculadas = $aContasVinculados;
            unlink($sLockFile);
            break;

        case 'removerVinculos':

            $sLockFile = "/tmp/sigfisvinculo.lock";
            if (file_exists($sLockFile)) {
                while(file_exists($sLockFile)) {
                    if (!file_exists($sLockFile)) {
                        break;
                    }
                }
            }
            $rsFileLock = fopen($sLockFile, 'w');
            fputs($rsFileLock, db_getsession("DB_id_usuario"));
            $oDomXml= new DOMDocument();
            $oDomXml->preserveWhiteSpace = false;
            $oDomXml->formatOutput       = true;
            $oDomXml->load('config/sigfis/vinculoplanoconta.xml');
            $oNoContas           = $oDomXml->getElementsByTagName("contas");
            $aContasRemover      = $oDomXml->getElementsByTagName("conta");
            $aNodesRemover       = array();
            $aContasVinculados   = array();
            foreach ($aContasRemover as $oConta) {

                $iCodigoConta       = $oConta->getAttribute("contaplano");
                if (in_array($iCodigoConta, $oParam->aContas)) {
                    $aNodesRemover[] = $oConta;
                }
            }
            foreach ($aNodesRemover as $oNode) {
                $oNoContas->item(0)->removeChild($oNode);
            }
            $oDomXml->save('config/sigfis/vinculoplanoconta.xml');
            unlink($sLockFile);
            break;

        case 'importarArquivoVinculos':
            $oFiles = db_utils::postMemory($_FILES);
            if (strtolower(substr($oFiles->arquivo['name'], -4)) != '.csv') {
                throw new BusinessException("Arquivo com formato inválido, o arquivo deve estar no formato CSV.");
            }

            if (trim(file_get_contents($oFiles->arquivo['tmp_name'])) == "") {
                throw new BusinessException("Não é possível importar arquivo vazio.");
            }

            $oDaoConplano = new cl_conplano();

            $oFile = new File($oFiles->arquivo['tmp_name']);

            $dadosArquivo = file($oFile->getFilePath());

            $oXmlDocument    = new DOMDocument('1.0', 'UTF-8');
            $oContas = $oXmlDocument->createElement('contas');

            $anousu = db_getsession('DB_anousu');

            $erros = array();

            foreach ($dadosArquivo as $linha => $dado) {

                $linha++;

                list($codigoTce, $estrutural) = array_map('trim', explode(',', $dado));

                if ($linha == 1 && (!is_numeric($codigoTce) || !is_numeric($estrutural))) {
                    continue;
                }

                if (empty($codigoTce) || empty($estrutural)) {
                    $erros[] = "Linha: {$linha} | Valores preenchidos incorretamente.";
                    continue;
                }

                $sSql = $oDaoConplano->sql_query_file(null, null, '*', null, "c60_anousu = {$anousu} AND c60_estrut = '{$estrutural}'");

                $result = $oDaoConplano->sql_record($sSql);

                if ($oDaoConplano->numrows < 1) {
                    $erros[] = "Linha: {$linha} | Estrutural {$estrutural} não encontrado na base de dados para o ano {$anousu}.";
                    continue;
                }

                $conplano = db_utils::fieldsMemory($result, 0);

                $naturezaSaldo = null;
                switch ($conplano->c60_naturezasaldo) {
                    case 1:
                        $naturezaSaldo = 'D';
                        break;
                    case 2:
                        $naturezaSaldo = 'C';
                        break;
                    case 3:
                        $naturezaSaldo = 'M';
                        break;
                    default:
                        $erros[] = "Linha: {$linha} | Natureza do saldo não encontrada.";
                        continue 2;
                }

                $oConta = $oXmlDocument->createElement('conta');
                $oConta->setAttribute('contatce', $codigoTce);
                $oConta->setAttribute('contaplano', $conplano->c60_codcon);
                $oConta->setAttribute('naturezasaldo', $naturezaSaldo);

                $oContas->appendChild($oConta);
            }

            $oXmlDocument->appendChild($oContas);
            $oXmlDocument->preserveWhiteSpace = false;
            $oXmlDocument->formatOutput       = true;
            $oXmlDocument->save('config/sigfis/vinculoplanoconta.xml');

            if (!empty($erros)) {
                file_put_contents("tmp/sigfiserros.json", json_encode(utf8_encode_all($erros)));
            }

            $oRetorno->erros = $erros;

            break;
    }
} catch (Exception $e) {
    $oRetorno->message = urlencode($e->getMessage());
    $oRetorno->erro = true;
    $oRetorno->status = 2;
}

echo $oJson->encode($oRetorno);
?>