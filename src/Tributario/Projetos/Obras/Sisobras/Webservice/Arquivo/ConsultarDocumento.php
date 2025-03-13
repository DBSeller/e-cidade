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

namespace ECidade\Tributario\Projetos\Obras\Sisobras\Webservice\Arquivo;

use DOMDocument;

/**
 * Classe responsável pela criação do xml para a operação consultarDocumeto
 * @author Matheus Sousa <matheus.sousa@dbseller.com.br>
 */
class ConsultarDocumento implements RequisicaoInterface
{
    private $oXml;
    private $documento;
    private $numeroDocumento;
    private $sOperacao;
    private $ano;

  /**
   * Criamos o objeto da classe com as informações necessárias para a sua exitência
   *
   * @param stdClass $oConsultarDocumento
   */
    public function __construct($documento, $numeroDocumento, $ano)
    {
        $this->oXml                     = new DOMDocument("1.0", "utf-8");
        $this->oXml->preserveWhiteSpace = false;
        $this->oXml->formatOutput       = true;
        $this->documento                = $documento;
        $this->numeroDocumento          = $numeroDocumento;
        $this->sOperacao                = "consultarDocumento";
        $this->ano                      = $ano;
    }

    public function gerar()
    {
        $this->getRequestXml();
        return $this->oXml;
    }

  /**
   * Geramos o xml com os dados do sisobra
   *
   * @return DOMDocument
   */
    public function getRequestXml()
    {
        $ConsultaDocumento = $this->oXml->createElement("ConsultaDocumento");
        $versao = $this->oXml->createAttribute("versao");
        $versao->value = '1.01';
        $ConsultaDocumento->appendChild($versao);

        $documento = $this->oXml->createElement("documento", $this->documento);
        $ConsultaDocumento->appendChild($documento);
        
        $numeroDocumento = $this->oXml->createElement("numeroDocumento", $this->numeroDocumento);
        $ConsultaDocumento->appendChild($numeroDocumento);

        $ano = $this->oXml->createElement("ano", $this->ano);
        $ConsultaDocumento->appendChild($ano);
        $this->oXml->appendChild($ConsultaDocumento);

        return $this->oXml;
    }

  /**
   * Buscamos a operação que será executada no webservice
   *
   * @return string
   */
    public function getOperacao()
    {
        return $this->sOperacao;
    }
}
