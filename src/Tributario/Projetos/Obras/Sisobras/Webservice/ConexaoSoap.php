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

use DOMDocument;
use ECidade\Tributario\Projetos\Obras\Sisobras\Webservice\Arquivo\RequisicaoInterface;
use SoapClient;

abstract class ConexaoSoap
{
  /**
   * Objeto que conterá a conexão com o Webservice
   * @var SoapClient
   */
    protected $oSoapClient;

  /**
   * Objeto que cria o arquivo de requisição do Webservice
   *
   * @var RequisicaoInterface
   */
    protected $oRequisicao;
    protected $oRetornoSoap;

  /**
   * Processamos a requisição conforme informações disponibilizadas.
   *
   * @return \stdClass
   */
    public function processarRequisicao()
    {
        if ($this->oRequisicao == 'consultarDocumento') {
            $sXml = $this->filenameXmlAssinado;
        } else {
            $xmlAssinado = new DOMDocument();
            $xmlAssinado->load($this->filenameXmlAssinado);
            $sXml = $xmlAssinado->saveXML();
            $sXml = str_replace("\\n", "", $sXml);
            $sXml = str_replace("<?xml version=\"1.0\"?>", "", $sXml);
            $sXml = str_replace("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>", "", $sXml);
            $sXml = str_replace("<?xml version=\"1.0\" encoding=\"utf-8\"?>", "", $sXml);
        }

        $params = array(
          "xmlEntrada"=>$sXml
        );

        if ($this->oRequisicao == 'consultarDocumento') {
            $this->oRetornoSoap = $this->oSoapClient->consultarDocumento($params);
        }
        if ($this->oRequisicao == 'recepcaoLote') {
            $this->oRetornoSoap = $this->oSoapClient->recepcaoLote($params);
        }
    }

    /**
     * Pegamos o retorno da conexão soap e tratamos em uma string
     *
     * @return array
     */
    public function getRespostaRecepcaoLote()
    {
        $new = simplexml_load_string($this->oRetornoSoap->xmlResultado);
        $con = json_encode($new);
        $newArr = json_decode($con, true);

        return [
            "arrayRetornoRecepaoLote" => $newArr,
            "retorno" => $this->oRetornoSoap->xmlResultado
        ];
    }

    public function getAlvaraHabiteseExistente()
    {
        $removeCabecalho = str_replace('<?xml version="1.0" encoding="UTF-8"?>', "", $this->oRetornoSoap->xmlResultado);
        $xmlRetorno = new \SimpleXMLElement($removeCabecalho);
        if (!empty($xmlRetorno->Alvara->infAlvara->numeroAlvara)) {
            return (string)$xmlRetorno->Alvara->infAlvara->numeroAlvara;
        } elseif (!empty($xmlRetorno->Habitese->infHabitese->numeroHabitese)) {
            return (string)$xmlRetorno->Habitese->infHabitese->numeroHabitese;
        }
    }

    public function getXmlAlvaraHabitese()
    {
        $removeCabecalho = str_replace('<?xml version="1.0" encoding="UTF-8"?>', "", $this->oRetornoSoap->xmlResultado);
        $xmlRetorno = new \SimpleXMLElement($removeCabecalho);
        if (!empty($xmlRetorno)) {
            return (string)$xmlRetorno;
        }
    }

    /**
     * Pegamos o retorno da conexão soap e tratamos em uma string
     *
     * @return array
     */
    public function getRespostaConsultarDocumento()
    {
        $new = simplexml_load_string($this->oRetornoSoap->xmlResultado);
        $con = json_encode($new);
        $newArr = json_decode($con, true);

        if (!$newArr) {
            $removeCabecalho = str_replace(
                '<?xml version="1.0" encoding="UTF-8"?>',
                "",
                $this->oRetornoSoap->xmlResultado
            );
            $xmlRetorno = new \SimpleXMLElement($removeCabecalho);

            // Acessa filho interno, contendo informações seja de Alvara ou Habitese retornado da consulta
            $xmlRetorno->children()->children()->children();

            return [
                'dadosRetornoConsulta' => $xmlRetorno->children()->children()->children(),
                'xmlRetornoConsulta' => $this->oRetornoSoap->xmlResultado
            ];
        } else {
            return $newArr;
        }
    }
}
