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

namespace ECidade\Tributario\Projetos\Obras\Sisobras\Webservice;

use SoapClient;

class Manutencao extends ConexaoSoap
{
  /**
   * Constante com o wsdl do webservice desejado
   */
    const WSDL = "https://sisobrapref.receita.economia.gov.br/sisobraprefWS/recepcao?wsdl";

  /**
   * Construtor da classe
   */
    public function __construct($filenameXmlSigned, $oRequisicao, $localA1, $senhaA1)
    {
        $oContext = stream_context_create(
            array(
                'ssl' => array(
                    'verify_peer'      => false,
                    'verify_peer_name' => false,
                    'allow_self_signed'=> true
                )
            )
        );
        $localA1Pem = str_replace('.pfx', '.pem', $localA1);
        $aOpcoes = array("soap_version"   => SOAP_1_2,
                         "stream_context" => $oContext,
                         "cache_wsdl"     => WSDL_CACHE_NONE,
                         "trace"          => true,
                         "local_cert"     => $localA1Pem,
                         "passphrase"     => $senhaA1
        );

        $sWsdl = self::WSDL;

        $this->oSoapClient = new SoapClient($sWsdl, $aOpcoes);
        $this->oRequisicao = $oRequisicao;
        $this->filenameXmlAssinado = $filenameXmlSigned;
    }

  /**
   * Processamos a requisição conforme informações disponibilizadas
   *
   * @return \stdClass
   */
    public function processarRequisicao()
    {
        parent::processarRequisicao();
    }
}
