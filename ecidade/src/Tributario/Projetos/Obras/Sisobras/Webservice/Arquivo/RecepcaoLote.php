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
use ECidade\Tributario\Projetos\Obras\Sisobras\Webservice\Arquivo\RecepcaoLoteAlvara;
use ECidade\Tributario\Projetos\Obras\Sisobras\Webservice\Arquivo\RecepcaoLoteHabitese;

/**
 * Classe responsável pela criação do xml para a operação recepcaoLote de Habite-se
 * @author Matheus Sousa <matheus.sousa@dbseller.com.br>
 */
class RecepcaoLote implements RequisicaoInterface
{
    private $oXml;
    private $arrayRegistroAlvara;
    private $arrayRegistroHabitese;
    private $sOperacao;
    private $localA1;
    private $senhaA1;

  /**
   * Criamos o objeto da classe com as informações necessárias para a sua exitência
   *
   * @param stdClass $oRegistroHabitese
   */
    public function __construct($arrayRegistroAlvara, $arrayRegistroHabitese, $localA1, $senhaA1)
    {
        $this->oXml                      = new DOMDocument("1.0", "utf-8");
        $this->oXml->preserveWhiteSpace  = false;
        $this->oXml->formatOutput        = true;
        $this->arrayRegistroAlvara       = $arrayRegistroAlvara;
        $this->arrayRegistroHabitese     = $arrayRegistroHabitese;
        $this->sOperacao                 = "recepcaoLote";
        $this->localA1                  = $localA1;
        $this->senhaA1                  = $senhaA1;
    }

    public function gerar()
    {
        $newdoc = new DOMDocument('1.0', 'utf-8');
        $newdoc->formatOutput = true;

        $sisobraPref = $newdoc->createElement("sisobraPref");
        $Versao = $newdoc->createAttribute("versao");
        $Versao->value = '1.01';
        $sisobraPref->appendChild($Versao);

        $oRecepcaoLoteAlvara = new RecepcaoLoteAlvara(
            $this->arrayRegistroAlvara,
            $this->oXml,
            $this->localA1,
            $this->senhaA1
        );
        $this->oXml = $oRecepcaoLoteAlvara->getRequestXml();
        $nodeListAlvara = $this->oXml->getElementsByTagName('Alvara');

        $oRecepcaoLoteHabitese = new RecepcaoLoteHabitese(
            $this->arrayRegistroHabitese,
            $this->oXml,
            $this->localA1,
            $this->senhaA1
        );
        $this->oXml = $oRecepcaoLoteHabitese->getRequestXml();
        $nodeListHabitese = $this->oXml->getElementsByTagName('Habitese');

        foreach ($nodeListAlvara as $key => $value) {
            $nodeAlvara = $newdoc->importNode($value, true);
            $sisobraPref->appendChild($nodeAlvara);
        }

        foreach ($nodeListHabitese as $key => $value) {
            $nodeHabitese = $newdoc->importNode($value, true);
            $sisobraPref->appendChild($nodeHabitese);
        }

        $newdoc->appendChild($sisobraPref);

        return $newdoc;
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
   * Geramos o xml com os dados do habitese
   *
   * @return DOMDocument
   */
    public function getRequestXml()
    {
        $this->oXml->appendChild($this->getDadosXml());
        return $this->oXml;
    }

    public function getDadosXml()
    {
        $infHabitese = $this->oXml->createElement("infHabitese");

        $Id = $this->oXml->createAttribute("Id");
        $Id->value = $this->oRegistroHabitese->getId();

        $numeroHabitese = $this->oXml->createElement(
            "numeroHabitese",
            $this->oRegistroHabitese->getNumeroHabitese()
        );
        $dataHabitese = $this->oXml->createElement(
            "dataHabitese",
            $this->oRegistroHabitese->getDataHabitese()
        );
        $dataFinalObra = $this->oXml->createElement(
            "dataFinalObra",
            $this->oRegistroHabitese->getDataFinalObra()
        );
        $tipoHabitese = $this->oXml->createElement(
            "tipoHabitese",
            $this->oRegistroHabitese->getTipoHabitese()
        );
        $observacao = $this->oXml->createElement(
            "observacao",
            $this->oRegistroHabitese->getObservacao()
        );
        $unidadeMedida = $this->oXml->createElement(
            "unidadeMedida",
            $this->oRegistroHabitese->getUnidadeMedida()
        );
        $valorUnidadeMedida = $this->oXml->createElement(
            "valorUnidadeMedida",
            $this->oRegistroHabitese->getValorUnidadeMedida()
        );
        $numeroAlvara = $this->oXml->createElement(
            "numeroAlvara",
            $this->oRegistroHabitese->getNumeroAlvara()
        );
        $dataAlvara = $this->oXml->createElement(
            "dataAlvara",
            $this->oRegistroHabitese->getDataAlvara()
        );

        $infHabitese->appendChild($Id);
        $infHabitese->appendChild($numeroHabitese);
        $infHabitese->appendChild($dataHabitese);
        $infHabitese->appendChild($dataFinalObra);
        $infHabitese->appendChild($tipoHabitese);
        $infHabitese->appendChild($observacao);
        $infHabitese->appendChild($unidadeMedida);
        $infHabitese->appendChild($valorUnidadeMedida);
        
        // Monta tags referentes a Área
        $area = $this->oXml->createElement("area");
        $infHabitese->appendChild($area);
        $area->appendChild($this->getDadosAreaPrincipalXml());
        $area->appendChild($this->getDadosAreaComplementarXml());

        $infHabitese->appendChild($numeroAlvara);
        $infHabitese->appendChild($dataAlvara);

        $this->oXml->appendChild($infHabitese);

        return $infHabitese;
    }

    public function getDadosAreaPrincipalXml()
    {
        $areaPrincipal = $this->oXml->createElement("areaPrincipal");

        $categoria = $this->oXml->createElement(
            "categoria",
            $this->oRegistroAreaPrincipal->getCategoria()
        );
        $destinacao = $this->oXml->createElement(
            "destinacao",
            $this->oRegistroAreaPrincipal->getDestinacao()
        );
        $tipoObra = $this->oXml->createElement(
            "tipoObra",
            $this->oRegistroAreaPrincipal->getTipoObra()
        );
        $qtd_total_unidades_bloco = $this->oXml->createElement(
            "qtd_total_unidades_bloco",
            $this->oRegistroAreaPrincipal->getQtdTotalUnidadesBloco()
        );
        $area = $this->oXml->createElement(
            "area",
            $this->oRegistroAreaPrincipal->getArea()
        );

        $areaPrincipal->appendChild($categoria);
        $areaPrincipal->appendChild($destinacao);
        $areaPrincipal->appendChild($tipoObra);
        $areaPrincipal->appendChild($qtd_total_unidades_bloco);
        $areaPrincipal->appendChild($area);

        $this->oXml->appendChild($areaPrincipal);

        return $areaPrincipal;
    }

    public function getDadosAreaComplementarXml()
    {
        $areaComplementar = $this->oXml->createElement("areaComplementar");

        $categoria = $this->oXml->createElement(
            "categoria",
            $this->oRegistroAreaComplementar->getCategoria()
        );
        $destinacao = $this->oXml->createElement(
            "destinacao",
            $this->oRegistroAreaComplementar->getDestinacao()
        );
        $tipoObra = $this->oXml->createElement(
            "tipoObra",
            $this->oRegistroAreaComplementar->getTipoObra()
        );
        $tipoAreaComplementar = $this->oXml->createElement(
            "tipoAreaComplementar",
            $this->oRegistroAreaComplementar->getTipoAreaComplementar()
        );
        $qtd_total_unidades_bloco = $this->oXml->createElement(
            "qtd_total_unidades_bloco",
            $this->oRegistroAreaComplementar->getQtdTotalUnidadesBloco()
        );
        $areaCoberta = $this->oXml->createElement(
            "areaCoberta",
            $this->oRegistroAreaComplementar->getAreaCoberta()
        );
        $areaDescoberta = $this->oXml->createElement(
            "areaDescoberta",
            $this->oRegistroAreaComplementar->getAreaDescoberta()
        );

        $areaComplementar->appendChild($categoria);
        $areaComplementar->appendChild($destinacao);
        $areaComplementar->appendChild($tipoObra);
        $areaComplementar->appendChild($tipoAreaComplementar);
        $areaComplementar->appendChild($qtd_total_unidades_bloco);
        $areaComplementar->appendChild($areaCoberta);
        $areaComplementar->appendChild($areaDescoberta);

        $this->oXml->appendChild($areaComplementar);

        return $areaComplementar;
    }
}
