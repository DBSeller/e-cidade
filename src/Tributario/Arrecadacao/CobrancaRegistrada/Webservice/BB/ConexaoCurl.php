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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\BB;

use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\BB\Arquivo\RequisicaoInterface;
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\BB\Arquivo\RetornoBoleto;
use ECidade\Lib\Request\Curl;

abstract class ConexaoCurl
{
    /**
     * Objeto que cria o arquivo de requisição do Webservice
     *
     * @var RequisicaoInterface
     */
    protected $oRequisicao;

    /**
     * Objeto com os dados de retorno da requisição
     *
     * @var \stdClass
     */
    protected $oRetornoCurl;

    /**
     * Processamos a requisição conforme informações disponibilizadas
     *
     * @return \stdClass
     */
    public function processarRequisicao()
    {
        $retornoBoleto = RetornoBoleto::getInstance();

        if (!$retornoBoleto->getErro()) {
            $oRegistro    = $this->oRequisicao->getRegistro();
            $oDomDocument = $this->oRequisicao->getRequestXml();
            $sXml         = $oDomDocument->saveXML();

            $aHeader = array(
                "Content-Type: text/xml; charset=utf-8",
                "Authorization: Bearer {$oRegistro->sAccessToken}",
                "SOAPAction: registrarBoleto"
            );

            $sXml = str_replace("\\n", "", $sXml);
            $sXml = str_replace("<?xml version=\"1.0\"?>", "", $sXml);
            $sXml = str_replace("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>", "", $sXml);
            $sXml = str_replace("<?xml version=\"1.0\" encoding=\"utf-8\"?>", "", $sXml);

            $sXml = trim($sXml);

            $sFileName = ECIDADE_PATH."tmp/envio-cobranca-registrada.xml";
            $fileOpen = fopen($sFileName, "a+");
            fwrite($fileOpen, json_encode($sXml));
            fclose($fileOpen);

            $aOpcoes = array(
                CURLOPT_URL            => $oRegistro->sLocation,
                CURLOPT_HEADER         => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => &$sXml,
                CURLOPT_HTTPHEADER     => $aHeader,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_BINARYTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT        => 20,
                CURLOPT_MAXREDIRS      => 3
            );

            $curl = new Curl();
            $curl->setOptions($aOpcoes);
            $curl->execute();
            $curl->close();
        
            $this->oRetornoCurl = $curl->getResponse();
        }
    }
    /**
     * Função que gera o Access Token para acesso ao webservice
     * @param string
     * @param string
     * @return string
     */
    protected function getAccessToken($sAtenticacao, $sAuth)
    {
        $aHeader = array(
            "Content-Type: application/x-www-form-urlencoded",
            "Authorization: Basic {$sAtenticacao}"
        );

        $aPayload = array(
            "grant_type" => "client_credentials",
            "scope"      => "cobranca.registro-boletos"
        );

        $aOpcoes = array(
            CURLOPT_URL            => $sAuth,
            CURLOPT_HEADER         => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($aPayload),
            CURLOPT_HTTPHEADER     => $aHeader,
            CURLOPT_RETURNTRANSFER => true
        );

        $curl = new Curl();
        $curl->setOptions($aOpcoes);
        $curl->execute();

        preg_match('/{(.*)}/', $curl->getResponse(), $aResponse);

        $sResponse = $aResponse[0];

        $oResponse = json_decode($sResponse);

        if (isset($oResponse->error)) {
            $retornoBoleto = RetornoBoleto::getInstance();

            $retornoBoleto->setErro(true);

            $retornoBoleto->setMensagemRetorno($oResponse->error_description);
        }

        return json_decode($sResponse)->access_token;
    }

    /**
     * Função que retorna a resposta da requisição
     * @return RetornoBoleto
     */
    public function getResposta()
    {
        $retornoBoleto = RetornoBoleto::getInstance();

        $retornoBoleto->getTreatReturn($this->oRetornoCurl);

        return $retornoBoleto;
    }
}
