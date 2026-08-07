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

class RegistroAreaComplementar
{

    /**
     * @var string
     */
    private $categoria;
    
    /**
     * @var string
     */
    private $destinacao;
    
    /**
     * @var string
     */
    private $tipoObra;

    /**
     * @var string
     */
    private $tipoAreaComplementar;
    
    /**
     * @var integer
     */
    private $qtd_total_unidades_bloco;
    
    /**
     * @var float
     */
    private $areaCoberta;

    /**
    * @var float
    */
    private $areaDescoberta;

    /**
     * @return string
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * @param string $categoria
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }

    /**
     * @return string
     */
    public function getDestinacao()
    {
        return $this->destinacao;
    }
    
    /**
     * @param string $destinacao
     */
    public function setDestinacao($destinacao)
    {
        $this->destinacao = $destinacao;
    }
    
    /**
     * @return string
     */
    public function getTipoObra()
    {
        return $this->tipoObra;
    }
    
    /**
     * @param string $tipoObra
     */
    public function setTipoObra($tipoObra)
    {
        $this->tipoObra = $tipoObra;
    }
    
    /**
     * @return string
     */
    public function getTipoAreaComplementar()
    {
        return $this->tipoAreaComplementar;
    }
    
    /**
     * @param string $tipoAreaComplementar
     */
    public function setTipoAreaComplementar($tipoAreaComplementar)
    {
        $this->tipoAreaComplementar = $tipoAreaComplementar;
    }

    /**
     * @return integer
     */
    public function getQtdTotalUnidadesBloco()
    {
        return $this->qtd_total_unidades_bloco;
    }
    
    /**
     * @param integer $qtd_total_unidades_bloco
     */
    public function setQtdTotalUnidadesBloco($qtd_total_unidades_bloco)
    {
        $this->qtd_total_unidades_bloco = $qtd_total_unidades_bloco;
    }
    
    /**
     * @return float
     */
    public function getAreaCoberta()
    {
        return $this->area;
    }
    
    /**
     * @param float $areaCoberta
     */
    public function setAreaCoberta($area)
    {
        $this->area = $area;
    }

    /**
     * @return float
     */
    public function getAreaDescoberta()
    {
        return $this->area;
    }
    
    /**
     * @param float $areaDescoberta
     */
    public function setAreaDescoberta($area)
    {
        $this->area = $area;
    }
}
