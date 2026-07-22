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

use ECidade\RecursosHumanos\ESocial\Mapeadores\ContribuicaoSindical\TipoContribuicao;
use ECidade\RecursosHumanos\ESocial\Repository\ContribuicaoSindical\ContribuicaoRepository;
use ECidade\RecursosHumanos\Pessoal\Model\Sindicato;
use ECidade\RecursosHumanos\Pessoal\Repository\SindicatoRepository;
use Exception;

class Contribuicao
{
    /**
     * @var integer
     */
    private $sequencial;

    /**
     * @var Sindicato
     */
    private $sindicato;

    /**
     * @var integer
     */
    private $tipo;

    /**
     * @var float
     */
    private $valor;

    /**
     * @var Periodo
     */
    private $periodo;

    /**
     * ContribuicaoSindical constructor.
     * @param int $sequencial
     * @throws Exception
     */
    public function __construct($sequencial = null)
    {
        if ($sequencial) {
            $contribuicaoSindical = ContribuicaoRepository::find($sequencial);

            $this->sequencial = $contribuicaoSindical->getSequencial();
            $this->sindicato = $contribuicaoSindical->getSindicato();
            $this->tipo = $contribuicaoSindical->getTipoContribuicao();
            $this->valor = $contribuicaoSindical->getValor();
            $this->periodo = $contribuicaoSindical->getPeriodo();
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
     * @return Contribuicao
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
        return $this;
    }

    /**
     * @return Sindicato
     */
    public function getSindicato()
    {
        return $this->sindicato;
    }

    /**
     * @param Sindicato $sindicato
     * @return Contribuicao
     */
    public function setSindicato($sindicato)
    {
        $this->sindicato = $sindicato;
        return $this;
    }

    /**
     * @return int
     */
    public function getTipoContribuicao()
    {
        return $this->tipo;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     * @return Contribuicao
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * @return Periodo
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * @param Periodo $periodo
     * @return Contribuicao
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;
        return $this;
    }

    /**
     * @param array $state
     * @return Contribuicao
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $contribuicao = new self();

        if (array_key_exists('eso31_sequencial', $state)) {
            $contribuicao->setSequencial((int)$state['eso31_sequencial']);
        }

        if (array_key_exists('eso31_rhsindicato', $state)) {
            $contribuicao->setSindicato(SindicatoRepository::find($state['eso31_rhsindicato']));
        }

        if (array_key_exists('eso31_tipo', $state)) {
            $contribuicao->setTipoContribuicao((int)$state['eso31_tipo']);
        }

        if (array_key_exists('eso31_valor', $state)) {
            $contribuicao->setValor((float)$state['eso31_valor']);
        }

        if (array_key_exists('eso31_contribuicaosindicalperiodo', $state)) {
            $contribuicao->setPeriodo((int)$state['eso31_contribuicaosindicalperiodo']);
        }

        return $contribuicao;
    }

    /**
     * @param int $tipo
     * @return Contribuicao
     */
    public function setTipoContribuicao($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $retorno = array(
            'sequencial' => $this->getSequencial(),
            'sindicato' => $this->getSindicato()->toArray(),
            'codigoTipoContribuicao' => $this->getTipoContribuicao(),
            'descricaoTipoContribuicao' => TipoContribuicao::get($this->getTipoContribuicao()),
            'valor' => $this->getValor()
        );

        return $retorno;
    }
}
