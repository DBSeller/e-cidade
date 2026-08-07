<?php
/*
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

namespace ECidade\RecursosHumanos\ESocial\Model\ContribuicaoSindical;


use CgmJuridico;
use ECidade\RecursosHumanos\ESocial\Repository\ContribuicaoSindical\PeriodoRepository;
use Exception;

class Periodo
{
    const PERIODO_MENSAL = 1;
    const PERIODO_ANUAL = 2;

    /**
     * @var integer
     */
    private $sequencial;

    /**
     * @var CgmJuridico
     */
    private $empregador;

    /**
     * @var integer
     */
    private $indicativoPeriodo;

    /**
     * @var string
     */
    private $periodo;


    /**
     * @var Contribuicao[]
     */
    private $contribuicoesSindicais = array();

    /**
     * Periodo constructor.
     * @param int $codigo
     * @throws Exception
     */
    public function __construct($codigo = null)
    {
        if ($codigo) {
            $contribuicaoSindical = PeriodoRepository::find($codigo);

            $this->sequencial = $contribuicaoSindical->getSequencial();
            $this->empregador = $contribuicaoSindical->getEmpregador();
            $this->indicativoPeriodo = $contribuicaoSindical->getIndicativoPeriodo();
            $this->periodo = $contribuicaoSindical->getPeriodo();
            $this->contribuicoesSindicais = $contribuicaoSindical->getContribuicoesSindicais();
        }
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return Periodo
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
        return $this;
    }

    /**
     * @return CgmJuridico
     */
    public function getEmpregador()
    {
        return $this->empregador;
    }

    /**
     * @param CgmJuridico $empregador
     * @return Periodo
     */
    public function setEmpregador($empregador)
    {
        $this->empregador = $empregador;
        return $this;
    }

    /**
     * @return int
     */
    public function getIndicativoPeriodo()
    {
        return $this->indicativoPeriodo;
    }

    /**
     * @param int $indicativoPeriodo
     * @return Periodo
     */
    public function setIndicativoPeriodo($indicativoPeriodo)
    {
        $this->indicativoPeriodo = $indicativoPeriodo;
        return $this;
    }

    /**
     * @return string
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * @param string $periodo
     * @return Periodo
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;
        return $this;
    }

    /**
     * @return Contribuicao
     */
    public function getContribuicoesSindicais()
    {
        return $this->contribuicoesSindicais;
    }

    /**
     * @param array $contribuicoesSindicais
     * @return Periodo
     */
    public function setContribuicoesSindicais($contribuicoesSindicais)
    {
        $this->contribuicoesSindicais = $contribuicoesSindicais;
        return $this;
    }

    /**
     * @param array $state
     * @return Periodo
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $periodo = new self();

        if (array_key_exists('eso30_sequencial', $state)) {
            $periodo->setSequencial((int)$state['eso30_sequencial']);
        }

        if (array_key_exists('eso30_empregador', $state)) {
            $periodo->setEmpregador(new CgmJuridico($state['eso30_empregador']));
        }

        if (array_key_exists('eso30_indicativo_periodo', $state)) {
            $periodo->setIndicativoPeriodo((int)$state['eso30_indicativo_periodo']);
        }

        if (array_key_exists('eso30_periodo', $state)) {
            $periodo->setPeriodo($state['eso30_periodo']);
        }

        return $periodo;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $retorno = array(
            'sequencial' => $this->getSequencial(),
            'empregador' => $this->getEmpregador()->toArray(),
            'indicativoPeriodo' => $this->getIndicativoPeriodo(),
            'periodo' => $this->getPeriodo(),
            'contribuicoes' => array()
        );
        $contribuicoes = $this->getContribuicoesSindicais();
        if (count($contribuicoes) > 0) {
            foreach ($contribuicoes as $contribuicao) {
                $retorno['contribuicoes'][] = $contribuicao->toArray();
            }
        }
        return $retorno;
    }
}
