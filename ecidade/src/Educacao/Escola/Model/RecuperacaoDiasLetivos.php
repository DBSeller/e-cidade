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

namespace ECidade\Educacao\Escola\Model;

/**
 * Class RecuperacaoDiasLetivos
 * @package ECidade\Educacao\Escola\Model
 */
class RecuperacaoDiasLetivos
{
    /**
     * @var int
     */
    private $regenciaHorario;

    /**
     * @var \Turno
     */
    private $turno;

    /**
     * @var \Regencia
     */
    private $regencia;

    /**
     * @var int
     */
    private $rechumano;

    /**
     * @var string
     */
    private $rechumanoDescricao;

    /**
     * @var \DBDate
     */
    private $data;

    /**
     * @var \PeriodoEscola[]
     */
    private $periodos = array();

    /**
     * @return int
     */
    public function getRegenciaHorario()
    {
        return $this->regenciaHorario;
    }

    /**
     * @param int $regenciaHorario
     */
    public function setRegenciaHorario($regenciaHorario)
    {
        $this->regenciaHorario = $regenciaHorario;
    }

    /**
     * @return \Turno
     */
    public function getTurno()
    {
        return $this->turno;
    }

    /**
     * @param \Turno $turno
     */
    public function setTurno(\Turno $turno)
    {
        $this->turno = $turno;
    }

    /**
     * @return \Regencia
     */
    public function getRegencia()
    {
        return $this->regencia;
    }

    /**
     * @param \Regencia $regencia
     */
    public function setRegencia(\Regencia $regencia)
    {
        $this->regencia = $regencia;
    }

    /**
     * @return int
     */
    public function getRechumano()
    {
        return $this->rechumano;
    }

    /**
     * @param int $rechumano
     */
    public function setRechumano($rechumano)
    {
        $this->rechumano = $rechumano;
    }

    /**
     * @return \DBDate
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param \DBDate $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getPeriodos()
    {
        return $this->periodos;
    }

    /**
     * @param \PeriodoEscola[] $periodos
     */
    public function setPeriodos($periodos)
    {
        $this->periodos = $periodos;
    }

    /**
     * @param \PeriodoEscola $periodoEscola
     */
    public function adicionarPeriodo(\PeriodoEscola $periodoEscola)
    {
        $this->periodos[] = $periodoEscola;
    }

    /**
     * @return string
     */
    public function getRechumanoDescricao()
    {
        return $this->rechumanoDescricao;
    }

    /**
     * @param string $rechumanoDescricao
     */
    public function setRechumanoDescricao($rechumanoDescricao)
    {
        $this->rechumanoDescricao = $rechumanoDescricao;
    }
}
