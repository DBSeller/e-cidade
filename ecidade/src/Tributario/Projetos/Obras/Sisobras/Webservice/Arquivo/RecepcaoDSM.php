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
 * Classe responsável pela criação do xml para a operação recepcaoDSM
 * @author Matheus Sousa <matheus.sousa@dbseller.com.br>
 */
class RecepcaoDSM implements RequisicaoInterface
{
    private $oXml;
    private $iMes;
    private $iAno;
    private $sOperacao;

  /**
   * Criamos o objeto da classe com as informações necessárias para a sua exitência
   *
   * @param stdClass $oRegistroHabitese
   */
    public function __construct($iMes, $iAno)
    {
        $this->oXml                      = new DOMDocument("1.0", "utf-8");
        $this->oXml->preserveWhiteSpace  = false;
        $this->oXml->formatOutput        = true;
        $this->iMes                      = $iMes;
        $this->iAno                      = $iAno;
        $this->sOperacao                 = "recepcaoDSM";
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
        $sisobraPref = $this->oXml->createElement("sisobraPref");
        $dsm         = $this->oXml->createElement("dsm");
        $infDsm      = $this->oXml->createElement("infDsm");
        $Id          = $this->oXml->createElement("Id", "id".$this->iAno.$this->iMes);
        $ano         = $this->oXml->createElement("ano", $this->iAno);
        $mes         = $this->oXml->createElement("mes", $this->iMes);
        $versao      = $this->oXml->createElement("versao", "versão do DSM");

        // Monta tags referentes a Área
        $sisobraPref->appendChild($dsm);
        $dsm->appendChild($infDsm);
        $infDsm->appendChild($ano);
        $infDsm->appendChild($mes);
        $infDsm->appendChild($Id);
        $sisobraPref->appendChild($versao);

        $this->oXml->appendChild($sisobraPref);

        return $this->oXml;
    }
}
