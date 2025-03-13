<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2018  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Projetos\Obras\Model;

use ECidade\Tributario\Projetos\Obras\Model\Construcao as ConstrucaoModel;

/**
 * Class AreaComplementar
 * @package ECidade\Tributario\Projetos\Obras\Model
 */
class AreaComplementar
{
    const TIPO_QUADRA = 1;
    const TIPO_ESTACIONAMENTO_TERREO = 2;
    const TIPO_PISCINA = 3;
    const TIPO_AREA_POSTO_GASOLINA = 4;

    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var ConstrucaoModel
     */
    private $construcao;

    /**
     * @var string
     */
    private $descricao;

    /**
     * @var float
     */
    private $medidaAreaCoberta;

    /**
     * @var float
     */
    private $medidaAreaDescoberta;

    /**
     * @var int
     */
    private $ocupacao;

    /**
     * @var int
     */
    private $tipoConstrucao;

    /**
     * @var int
     */
    private $tipoLancamento;

    /**
     * @var int
     */
    private $tipoAreaComplementar;

    /**
     * @var array
     */
    private $tipoAreaComplementarDescricoes = array(
        self::TIPO_QUADRA => 'Quadra',
        self::TIPO_ESTACIONAMENTO_TERREO => 'Estacionamento Térreo',
        self::TIPO_PISCINA => 'Piscina',
        self::TIPO_AREA_POSTO_GASOLINA => 'Área Posto de Gasolina'
    );

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @return ConstrucaoModel
     */
    public function getConstrucao()
    {
        return $this->construcao;
    }

    /**
     * @param ConstrucaoModel $construcao
     */
    public function setConstrucao($construcao)
    {
        $this->construcao = $construcao;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @return float
     */
    public function getMedidaAreaCoberta()
    {
        return $this->medidaAreaCoberta;
    }

    /**
     * @param float $medidaAreaCoberta
     */
    public function setMedidaAreaCoberta($medidaAreaCoberta)
    {
        $this->medidaAreaCoberta = $medidaAreaCoberta;
    }

    /**
     * @return float
     */
    public function getMedidaAreaDescoberta()
    {
        return $this->medidaAreaDescoberta;
    }

    /**
     * @param float $medidaAreaDescoberta
     */
    public function setMedidaAreaDescoberta($medidaAreaDescoberta)
    {
        $this->medidaAreaDescoberta = $medidaAreaDescoberta;
    }

    /**
     * @return int
     */
    public function getOcupacao()
    {
        return $this->ocupacao;
    }

    /**
     * @param int $ocupacao
     */
    public function setOcupacao($ocupacao)
    {
        $this->ocupacao = $ocupacao;
    }

    /**
     * @return int
     */
    public function getTipoConstrucao()
    {
        return $this->tipoConstrucao;
    }

    /**
     * @param int $tipoConstrucao
     */
    public function setTipoConstrucao($tipoConstrucao)
    {
        $this->tipoConstrucao = $tipoConstrucao;
    }

    /**
     * @return int
     */
    public function getTipoLancamento()
    {
        return $this->tipoLancamento;
    }

    /**
     * @param int $tipoLancamento
     */
    public function setTipoLancamento($tipoLancamento)
    {
        $this->tipoLancamento = $tipoLancamento;
    }

    /**
     * @return int
     */
    public function getTipoAreaComplementar()
    {
        return $this->tipoAreaComplementar;
    }

    /**
     * @param int $tipoAreaComplementar
     */
    public function setTipoAreaComplementar($tipoAreaComplementar)
    {
        $this->tipoAreaComplementar = $tipoAreaComplementar;
    }

    /**
     * @return string
     */
    public function getTipoAreaComplementarDescricao($codigoTipoAreaComplementar)
    {
        return $this->tipoAreaComplementarDescricoes[$codigoTipoAreaComplementar];
    }
}
