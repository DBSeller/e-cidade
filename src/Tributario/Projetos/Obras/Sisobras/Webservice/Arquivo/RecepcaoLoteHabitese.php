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
use DOMElement;

use NFePHP\Common\Signer;
use NFePHP\Common\Certificate;

/**
 * Classe responsável pela criação do xml para a operação recepcaoLote de Habite-se
 * @author Matheus Sousa <matheus.sousa@dbseller.com.br>
 */
class RecepcaoLoteHabitese implements RequisicaoInterface
{
    private $oXml;
    private $arrayRegistroHabitese;
    private $sOperacao;

  /**
   * Criamos o objeto da classe com as informações necessárias para a sua exitência
   *
   * @param stdClass $arrayRegistroHabitese
   */
    public function __construct($arrayRegistroHabitese, DOMDocument $oXml, $localA1, $senhaA1)
    {
        $this->oXml                     = $oXml;
        $this->oXml->preserveWhiteSpace = false;
        $this->oXml->formatOutput       = true;
        $this->arrayRegistroHabitese    = $arrayRegistroHabitese;
        $this->sOperacao                = "recepcaoLote";
        $this->localA1                  = $localA1;
        $this->senhaA1                  = $senhaA1;
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

  /**
   * Retornamos a coleção com os registros
   *
   * @return stdClass
   */
  // public function getRegistroHabitese()
  // {
  //   return $this->oRegistroHabitese;
  // }

  /**
   * Geramos o xml com os dados do habitese
   *
   * @return DOMDocument
   */
    public function getRequestXml()
    {
        foreach ($this->arrayRegistroHabitese as $key => $oRegistro) {
            $Habitese = $this->getDadosXml($oRegistro);
            $this->oXml->appendChild($Habitese);
        }
        return $this->oXml;
    }

    public function getDadosXml($oRegistro)
    {
        $Habitese = $this->oXml->createElement("Habitese");
        $infHabitese = $this->oXml->createElement("infHabitese");

        $Id = $this->oXml->createAttribute("Id");
        $Id->value = $oRegistro->oRegistroHabitese->getId();

        $numeroHabitese = $this->oXml->createElement(
            "numeroHabitese",
            $oRegistro->oRegistroHabitese->getNumeroHabitese()
        );
        $dataHabitese = $this->oXml->createElement(
            "dataHabitese",
            $oRegistro->oRegistroHabitese->getDataHabitese()
        );
        if (!empty($oRegistro->oRegistroHabitese->getDataFinalObra())) {
            $dataFinalObra = $this->oXml->createElement(
                "dataFinalObra",
                $oRegistro->oRegistroHabitese->getDataFinalObra()
            );
        }
        $tipoHabitese = $this->oXml->createElement(
            "tipoHabitese",
            $oRegistro->oRegistroHabitese->getTipoHabitese()
        );
        if (!empty($oRegistro->oRegistroHabitese->getObservacao())) {
            $observacao = $this->oXml->createElement(
                "observacao",
                \DBString::removerAcentuacao($oRegistro->oRegistroHabitese->getObservacao())
            );
        }
        $unidadeMedida = $this->oXml->createElement(
            "unidadeMedida",
            $oRegistro->oRegistroHabitese->getUnidadeMedida()
        );
        if (!empty($oRegistro->oRegistroHabitese->getValorUnidadeMedida())) {
            $valorUnidadeMedida = $this->oXml->createElement(
                "valorUnidadeMedida",
                $oRegistro->oRegistroHabitese->getValorUnidadeMedida()
            );
        }
        $numeroAlvara = $this->oXml->createElement(
            "numeroAlvara",
            $oRegistro->oRegistroHabitese->getNumeroAlvara()
        );
        $dataAlvara = $this->oXml->createElement(
            "dataAlvara",
            $oRegistro->oRegistroHabitese->getDataAlvara()
        );

        $infHabitese->appendChild($Id);
        $infHabitese->appendChild($numeroHabitese);
        $infHabitese->appendChild($dataHabitese);
        if (!empty($dataFinalObra)) {
            $infHabitese->appendChild($dataFinalObra);
        }
        $infHabitese->appendChild($tipoHabitese);
        if (!empty($observacao)) {
            $infHabitese->appendChild($observacao);
        }
        $infHabitese->appendChild($unidadeMedida);
        if (!empty($valorUnidadeMedida)) {
            $infHabitese->appendChild($valorUnidadeMedida);
        }

        // Monta tags referentes a Área
        $area = $this->oXml->createElement("area");
        $infHabitese->appendChild($area);
        $area->appendChild($this->getDadosAreaPrincipalXml($oRegistro->oRegistroAreaPrincipal));

        /*
         * Percorre Array de objetos da Area Complementar,
         * caso tenha algum dado, adiciona Area Complementa,
         * caso contrário não insere
         */
        $cont=0;
        foreach ($oRegistro->oRegistroAreaComplementar as $value) {
            if (!empty($value)) {
                $cont++;
            }
        }
        if (!empty($cont)) {
            $area->appendChild($this->getDadosAreaComplementarXml($oRegistro->oRegistroAreaComplementar));
        }

        $infHabitese->appendChild($numeroAlvara);
        $infHabitese->appendChild($dataAlvara);
        $Habitese->appendChild($infHabitese);

        /*INICIO NFEPHP*/
        $pfx = file_get_contents($this->localA1);
        $cert = Certificate::readPfx($pfx, $this->senhaA1);

        $tagname = 'infHabitese'; //tag a ser assinada,
                     //se este campo for deixado vazio a tag raiz será assinada

        $mark = 'Id'; //indica se a assinatura fará referencia a uma tag
              //com atributo de identificação definido,
              //se for assinar a raiz do documento este campo deverá
              //ser deixado em branco

        $algorithm = OPENSSL_ALGO_SHA1; //algoritmo de encriptação a ser usado

        $canonical = [true,false,null,null]; //veja função C14n do PHP

        $rootname = 'Habitese'; //este campo indica em qual node a assinatura deverá ser inclusa
        $this->oXml->formatOutput = false;
        $sXml = $this->oXml->saveXML();
        $sXml = utf8_encode($sXml);
        $sXml = $Habitese;

        $Body = $sXml;
        $Document = new DOMDocument();
        $Document->appendChild($Document->importNode($Body, true));

        $sXml = $Document->saveXML();
        try {
            $signed = Signer::sign(
                $cert,
                $sXml,
                $tagname,
                $mark,
                $algorithm,
                $canonical,
                $rootname
            );
        } catch (\Exception $e) {
            //aqui você trata a exceção
            dd($e->getMessage());
        }
        $signed = utf8_decode($signed);
        $signed = str_replace('&lt;', '<', $signed);
        $signed = str_replace('&gt;', '>', $signed);
        $signed = str_replace('<Habitese>', '', $signed);
        $signed = str_replace('</Habitese>', '', $signed);

        $HabiteseAssinado = new DOMElement('Habitese', $signed);
        $this->oXml->appendChild($HabiteseAssinado);
        return $HabiteseAssinado;

        /*FIM NFEPHP*/
    }

    public function getDadosAreaPrincipalXml($oRegistroAreaPrincipal)
    {
        $areaPrincipal = $this->oXml->createElement("areaPrincipal");

        $categoria = $this->oXml->createElement(
            "categoria",
            $oRegistroAreaPrincipal->getCategoria()
        );
        $destinacao = $this->oXml->createElement(
            "destinacao",
            $oRegistroAreaPrincipal->getDestinacao()
        );
        $tipoObra = $this->oXml->createElement(
            "tipoObra",
            $oRegistroAreaPrincipal->getTipoObra()
        );
        if (!empty($oRegistroAreaPrincipal->getQtdTotalUnidadesBloco())) {
            $qtd_total_unidades_bloco = $this->oXml->createElement(
                "qtd_total_unidades_bloco",
                $oRegistroAreaPrincipal->getQtdTotalUnidadesBloco()
            );
        }
        $area = $this->oXml->createElement(
            "area",
            number_format($oRegistroAreaPrincipal->getArea(), 2, '.', '')
        );

        $areaPrincipal->appendChild($categoria);
        $areaPrincipal->appendChild($destinacao);
        $areaPrincipal->appendChild($tipoObra);
        if (!empty($qtd_total_unidades_bloco)) {
            $areaPrincipal->appendChild($qtd_total_unidades_bloco);
        }
        $areaPrincipal->appendChild($area);

        $this->oXml->appendChild($areaPrincipal);

        return $areaPrincipal;
    }

    public function getDadosAreaComplementarXml($oRegistroAreaComplementar)
    {
        $areaComplementar = $this->oXml->createElement("areaComplementar");

        $categoria = $this->oXml->createElement(
            "categoria",
            $oRegistroAreaComplementar->getCategoria()
        );
        $destinacao = $this->oXml->createElement(
            "destinacao",
            $oRegistroAreaComplementar->getDestinacao()
        );
        $tipoObra = $this->oXml->createElement(
            "tipoObra",
            $oRegistroAreaComplementar->getTipoObra()
        );
        $tipoAreaComplementar = $this->oXml->createElement(
            "tipoAreaComplementar",
            $oRegistroAreaComplementar->getTipoAreaComplementar()
        );
        if (!empty($oRegistroAreaComplementar->getQtdTotalUnidadesBloco())) {
            $qtd_total_unidades_bloco = $this->oXml->createElement(
                "qtd_total_unidades_bloco",
                $oRegistroAreaComplementar->getQtdTotalUnidadesBloco()
            );
        }
        $areaCoberta = $this->oXml->createElement(
            "areaCoberta",
            $oRegistroAreaComplementar->getAreaCoberta()
        );
        $areaDescoberta = $this->oXml->createElement(
            "areaDescoberta",
            $oRegistroAreaComplementar->getAreaDescoberta()
        );

        $areaComplementar->appendChild($categoria);
        $areaComplementar->appendChild($destinacao);
        $areaComplementar->appendChild($tipoObra);
        $areaComplementar->appendChild($tipoAreaComplementar);
        if (!empty($qtd_total_unidades_bloco)) {
            $areaComplementar->appendChild($qtd_total_unidades_bloco);
        }
        $areaComplementar->appendChild($areaCoberta);
        $areaComplementar->appendChild($areaDescoberta);

        $this->oXml->appendChild($areaComplementar);

        return $areaComplementar;
    }
}
