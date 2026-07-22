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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\BB\Arquivo;

use BaseClassRepository;

class RetornoBoleto extends BaseClassRepository
{
    private $oResposta;

    private $sMensagemRetorno;

    private $lErro = false;

    public function getMensagemRetorno($lUsuarioExterno = false)
    {
        if ($lUsuarioExterno) {
            $sErro = "Ocorreu um erro ao registrar o boleto no banco: ";
            $sErro .= "$this->sMensagemRetorno. Por favor entre em contato com a Instituição.";

            return $sErro;
        }

        return $this->sMensagemRetorno;
    }

    public function setMensagemRetorno($sMensagemRetorno)
    {
        $this->sMensagemRetorno = $sMensagemRetorno;
    }

    public function getResposta()
    {
        return $this->oResposta;
    }

    public function getErro()
    {
        return $this->lErro;
    }

    public function setErro($lErro)
    {
        $this->lErro = $lErro;
    }

    public function getTreatReturn($sResponse)
    {
        $oDom = new \DOMDocument('1.0', 'UTF-8');

        $sResponse = str_replace("\r\n", "", $sResponse);

        if (!empty($sResponse)) {
            preg_match('/<SOAP-ENV:Body>(.*)<\/SOAP-ENV:Body>/', $sResponse, $aResponse);

            if ($oDom->loadXML($aResponse[0])) {
                $aResultado = array();

                $this->createArrayStartingXml($oDom->documentElement->firstChild, $aResultado);
            }

            if (isset($aResultado["textoMensagemErro"])
                and
                $aResultado["textoMensagemErro"] != ""
            ) {
                $this->sMensagemRetorno = $aResultado["textoMensagemErro"];
                $this->lErro = true;
            }

            if (isset($aResultado["detail"]["erro"]["Mensagem"])
                and
                $aResultado["detail"]["erro"]["Mensagem"] != ""
            ) {
                $this->sMensagemRetorno = $aResultado["detail"]["erro"]["Mensagem"];
                $this->lErro = true;
            }

            if (!$this->lErro) {
                $this->oResposta = (object) $aResultado;
            }

            $sFileName = ECIDADE_PATH."tmp/retorno-cobranca-registrada.json";
            $fileOpen = fopen($sFileName, "a+");
            fwrite($fileOpen, json_encode($aResultado));
            fclose($fileOpen);
        }
    }

    private function createArrayStartingXml($noXml, &$aResultado)
    {
        if ($noXml->firstChild && $noXml->firstChild->nodeType == XML_ELEMENT_NODE) {
            foreach ($noXml->childNodes as $itemXml) {
                $this->createArrayStartingXml($itemXml, $aResultado[$itemXml->localName]);
            }
        } else {
            $aResultado = html_entity_decode(trim($noXml->nodeValue));
        }
    }
}
