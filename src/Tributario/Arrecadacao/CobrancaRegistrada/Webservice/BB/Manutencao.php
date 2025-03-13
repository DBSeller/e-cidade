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
use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\BB\ConexaoCurl;

class Manutencao extends ConexaoCurl
{
    const URL_AUTH_HOMOLOGACAO = "https://oauth.hm.bb.com.br/oauth/token";
    const LOCATION_REQUEST_HOMOLOGACAO = "https://cobranca.homologa.bb.com.br:7101/registrarBoleto";

    const URL_AUTH_PRODUCAO = "https://oauth.bb.com.br/oauth/token";
    const LOCATION_REQUEST_PRODUCAO = "https://cobranca.bb.com.br:7101/registrarBoleto";

    const CLIENT_ID_HOMOLOGACAO = array(
        "eyJpZCI6IjgwNDNiNTMtZjQ5Mi00",
        "YyIsImNvZGlnb1B1YmxpY2Fkb3IiOj",
        "EwOSwiY29kaWdvU29mdHdhcmUiOjE",
        "sInNlcXVlbmNpYWxJbnN0YWxhY2FvIjoxfQ"
    );

    const CLIENT_SECRET_HOMOLOGACAO = array(
        "eyJpZCI6IjBjZDFlMGQtN2UyNC00MGQ",
        "yLWI0YSIsImNvZGlnb1B1YmxpY2Fkb3IiO",
        "jEwOSwiY29kaWdvU29mdHdhcmUiOjEs",
        "InNlcXVlbmNpYWxJbnN0YWxhY2FvIjox",
        "LCJzZXF1ZW5jaWFsQ3JlZGVuY2lhbCI6MX0"
    );

    /**
     * Código do banco que utiliza este webservice
     */
    const CODIGO_BANCO = "001";

    /**
     * Construtor da classe
     *
     * @param RequisicaoInterface $oRequisicao
     */
    public function __construct(RequisicaoInterface $oRequisicao)
    {
        $oRegistro = $oRequisicao->getRegistro();

        $sAuth = self::URL_AUTH_PRODUCAO;
        $sLocation = self::LOCATION_REQUEST_PRODUCAO;

        if (trim($oRegistro->clientId) == implode("", self::CLIENT_ID_HOMOLOGACAO)
            and
            trim($oRegistro->clientSecret) == implode("", self::CLIENT_SECRET_HOMOLOGACAO)
        ) {
            $sAuth = self::URL_AUTH_HOMOLOGACAO;
            $sLocation = self::LOCATION_REQUEST_HOMOLOGACAO;
        }

        $oRequisicao->getRegistro()->sAccessToken = parent::getAccessToken($oRegistro->autenticacao, $sAuth);
        $oRequisicao->getRegistro()->sLocation = $sLocation;

        $this->oRequisicao = $oRequisicao;
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
