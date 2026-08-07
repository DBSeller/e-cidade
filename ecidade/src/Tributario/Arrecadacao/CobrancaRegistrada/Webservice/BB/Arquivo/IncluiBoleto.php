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

use DOMDocument;

/**
 * Classe responsável pela criação do xml para a operação registrarBoleto
 * @author Natanael Giacomini <natanael.giacomini@dbseller.com.br>
 */
class IncluiBoleto implements RequisicaoInterface
{
    private $oXml;
    private $oRegistro;

    /**
     * Criamos o objeto da classe com as informações necessárias para a sua existência
     *
     * @param stdClass $oRegistro
     */
    public function __construct(\stdClass $oRegistro)
    {
        $this->oXml = new DOMDocument("1.0", "utf-8");
        $this->oXml->preserveWhiteSpace = false;
        $this->oXml->formatOutput = true;

        $this->oRegistro = $oRegistro;
    }

    /**
     * Retornamos a coleção com os registros
     *
     * @return stdClass
     */
    public function getRegistro()
    {
        return $this->oRegistro;
    }

    /**
     * Geramos o xml com os dados do boleto
     *
     * @return DOMDocument
     */
    public function getRequestXml()
    {
        $oServicoEntrada = $this->oXml->createElement('soapenv:Envelope');
        $oServicoEntrada->setAttribute("xmlns:soapenv", "http://schemas.xmlsoap.org/soap/envelope/");
        $oServicoEntrada->setAttribute(
            "xmlns:sch",
            "http://www.tibco.com/schemas/bws_registro_cbr/Recursos/XSD/Schema.xsd"
        );

        $oHeader = $this->getHeaderXml();
        $oDados  = $this->getDadosXml();

        $oServicoEntrada->appendChild($oHeader);
        $oServicoEntrada->appendChild($oDados);

        $this->oXml->appendChild($oServicoEntrada);

        return $this->oXml;
    }

    /**
     * Criamos o Header do xml
     *
     * @return DOMElement
     */
    protected function getHeaderXml()
    {
        $oHeader = $this->oXml->createElement('soapenv:Header');

        return $oHeader;
    }

    /**
     * Criamos o elemento com os dados do recibo para o xml
     *
     * @return DOMElement
     */
    protected function getDadosXml()
    {
        $oBody = $this->oXml->createElement("soapenv:Body");
        $oRequisicao = $this->oXml->createElement("sch:requisicao");

        $oNumeroConvenio = $this->oXml->createElement(
            "sch:numeroConvenio",
            trim($this->oRegistro->numeroConvenio)
        );
        $oRequisicao->appendChild($oNumeroConvenio);

        $oNumeroCarteira = $this->oXml->createElement(
            "sch:numeroCarteira",
            trim($this->oRegistro->numeroCarteira)
        );
        $oRequisicao->appendChild($oNumeroCarteira);

        $oNumeroVariacaoCarteira = $this->oXml->createElement(
            "sch:numeroVariacaoCarteira",
            trim($this->oRegistro->numeroVariacaoCarteira)
        );
        $oRequisicao->appendChild($oNumeroVariacaoCarteira);

        $oCodigoModalidadeTitulo = $this->oXml->createElement(
            "sch:codigoModalidadeTitulo",
            trim($this->oRegistro->codigoModalidadeTitulo)
        );
        $oRequisicao->appendChild($oCodigoModalidadeTitulo);

        $oDataEmissaoTitulo = $this->oXml->createElement(
            "sch:dataEmissaoTitulo",
            trim($this->oRegistro->dataEmissaoTitulo)
        );
        $oRequisicao->appendChild($oDataEmissaoTitulo);

        $oDataVencimentoTitulo = $this->oXml->createElement(
            "sch:dataVencimentoTitulo",
            trim($this->oRegistro->dataVencimentoTitulo)
        );
        $oRequisicao->appendChild($oDataVencimentoTitulo);

        $oValorOriginalTitulo = $this->oXml->createElement(
            "sch:valorOriginalTitulo",
            trim($this->oRegistro->valorOriginalTitulo)
        );
        $oRequisicao->appendChild($oValorOriginalTitulo);

        $oCodigoTipoDesconto = $this->oXml->createElement(
            "sch:codigoTipoDesconto",
            trim($this->oRegistro->codigoTipoDesconto)
        );
        $oRequisicao->appendChild($oCodigoTipoDesconto);

        $oCodigoTipoJuroMora = $this->oXml->createElement(
            "sch:codigoTipoJuroMora",
            trim($this->oRegistro->codigoTipoJuroMora)
        );
        $oRequisicao->appendChild($oCodigoTipoJuroMora);

        $oCodigoTipoMulta = $this->oXml->createElement(
            "sch:codigoTipoMulta",
            trim($this->oRegistro->codigoTipoMulta)
        );
        $oRequisicao->appendChild($oCodigoTipoMulta);

        $oCodigoAceiteTitulo = $this->oXml->createElement(
            "sch:codigoAceiteTitulo",
            trim($this->oRegistro->codigoAceiteTitulo)
        );
        $oRequisicao->appendChild($oCodigoAceiteTitulo);

        $oCodigoTipoTitulo = $this->oXml->createElement(
            "sch:codigoTipoTitulo",
            trim($this->oRegistro->codigoTipoTitulo)
        );
        $oRequisicao->appendChild($oCodigoTipoTitulo);

        $oIndicadorPermissaoRecebimentoParcial = $this->oXml->createElement(
            "sch:indicadorPermissaoRecebimentoParcial",
            trim($this->oRegistro->indicadorPermissaoRecebimentoParcial)
        );
        $oRequisicao->appendChild($oIndicadorPermissaoRecebimentoParcial);

        $oTextoNumeroTituloCliente = $this->oXml->createElement(
            "sch:textoNumeroTituloCliente",
            trim($this->oRegistro->textoNumeroTituloCliente)
        );
        $oRequisicao->appendChild($oTextoNumeroTituloCliente);

        $oCodigoTipoInscricaoPagador = $this->oXml->createElement(
            "sch:codigoTipoInscricaoPagador",
            trim($this->oRegistro->codigoTipoInscricaoPagador)
        );
        $oRequisicao->appendChild($oCodigoTipoInscricaoPagador);

        $oNumeroInscricaoPagador = $this->oXml->createElement(
            "sch:numeroInscricaoPagador",
            trim($this->oRegistro->numeroInscricaoPagador)
        );
        $oRequisicao->appendChild($oNumeroInscricaoPagador);

        $oNomePagador = $this->oXml->createElement(
            "sch:nomePagador",
            trim($this->oRegistro->nomePagador)
        );
        $oRequisicao->appendChild($oNomePagador);

        $oTextoEnderecoPagador = $this->oXml->createElement(
            "sch:textoEnderecoPagador",
            trim($this->oRegistro->textoEnderecoPagador)
        );
        $oRequisicao->appendChild($oTextoEnderecoPagador);

        $oNumeroCepPagador = $this->oXml->createElement(
            "sch:numeroCepPagador",
            trim($this->oRegistro->numeroCepPagador)
        );
        $oRequisicao->appendChild($oNumeroCepPagador);

        $oNomeMunicipioPagador = $this->oXml->createElement(
            "sch:nomeMunicipioPagador",
            trim($this->oRegistro->nomeMunicipioPagador)
        );
        $oRequisicao->appendChild($oNomeMunicipioPagador);

        $oNomeBairroPagador = $this->oXml->createElement(
            "sch:nomeBairroPagador",
            trim($this->oRegistro->nomeBairroPagador)
        );
        $oRequisicao->appendChild($oNomeBairroPagador);

        $oSiglaUfPagador = $this->oXml->createElement(
            "sch:siglaUfPagador",
            trim($this->oRegistro->siglaUfPagador)
        );
        $oRequisicao->appendChild($oSiglaUfPagador);

        $oCodigoChaveUsuario = $this->oXml->createElement(
            "sch:codigoChaveUsuario",
            trim($this->oRegistro->codigoChaveUsuario)
        );
        $oRequisicao->appendChild($oCodigoChaveUsuario);

        $oCodigoTipoCanalSolicitacao = $this->oXml->createElement(
            "sch:codigoTipoCanalSolicitacao",
            trim($this->oRegistro->codigoTipoCanalSolicitacao)
        );
        $oRequisicao->appendChild($oCodigoTipoCanalSolicitacao);

        $oBody->appendChild($oRequisicao);

        return $oBody;
    }
}
