<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Projetos\Obras\Sisobras;

class RegistroHabitese
{

  /**
   * @var string
   */
    private $Id;

  /**
   * @var integer
   */
    private $numeroHabitese;

  /**
   * @var string
   */
    private $dataHabitese;

  /**
   * @var string
   */
    private $dataFinalObra;

  /**
   * @var string
   */
    private $tipoHabitese;

  /**
   * @var string
   */
    private $observacao;

  /**
   * @var string
   */
    private $unidadeMedida;

  /**
   * @var string
   */
    private $valorUnidadeMedida;

  /**
   * @var float
   */
    private $numeroAlvara;

  /**
   * @var \DBDate
   */
    private $dataAlvara;

  /**
   * @return string
   */
    public function getId()
    {
        return $this->Id;
    }

  /**
   * @param string $Id
   */
    public function setId($Id)
    {
        $this->Id = $Id;
    }

  /**
   * @return integer
   */
    public function getNumeroHabitese()
    {
        return $this->numeroHabitese;
    }

  /**
   * @param integer $numeroHabitese
   */
    public function setNumeroHabitese($numeroHabitese)
    {
        $this->numeroHabitese = $numeroHabitese;
    }

  /**
   * @return string
   */
    public function getDataHabitese()
    {
        return $this->dataHabitese;
    }

  /**
   * @param string $dataHabitese
   */
    public function setDataHabitese($dataHabitese)
    {
        $this->dataHabitese = $dataHabitese;
    }

  /**
   * @return string
   */
    public function getDataFinalObra()
    {
        return $this->dataFinalObra;
    }

  /**
   * @param string $dataFinalObra
   */
    public function setDataFinalObra($dataFinalObra)
    {
        $this->dataFinalObra = $dataFinalObra;
    }
  
  /**
   * @return string
   */
    public function getTipoHabitese()
    {
        return $this->tipoHabitese;
    }

  /**
   * @param string $tipoHabitese
   */
    public function setTipoHabitese($tipoHabitese)
    {
        $this->tipoHabitese = $tipoHabitese;
    }

  /**
   * @return string
   */
    public function getObservacao()
    {
        return $this->observacao;
    }

  /**
   * @param string $observacao
   */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }

  /**
   * @return string
   */
    public function getUnidadeMedida()
    {
        return $this->unidadeMedida;
    }

  /**
   * @param string $unidadeMedida
   */
    public function setUnidadeMedida($unidadeMedida)
    {
        $this->unidadeMedida = $unidadeMedida;
    }

  /**
   * @return string
   */
    public function getValorUnidadeMedida()
    {
        return $this->valorUnidadeMedida;
    }

  /**
   * @param string $valorUnidadeMedida
   */
    public function setValorUnidadeMedida($valorUnidadeMedida)
    {
        $this->valorUnidadeMedida = $valorUnidadeMedida;
    }

  /**
   * @return integer
   */
    public function getNumeroAlvara()
    {
        return $this->numeroAlvara;
    }

  /**
   * @param integer $numeroAlvara
   */
    public function setNumeroAlvara($numeroAlvara)
    {
        $this->numeroAlvara = $numeroAlvara;
    }

  /**
   * @return string
   */
    public function getDataAlvara()
    {
        return $this->dataAlvara;
    }

  /**
   * @param string $dataAlvara
   */
    public function setDataAlvara($dataAlvara)
    {
        $this->dataAlvara = $dataAlvara;
    }
}
