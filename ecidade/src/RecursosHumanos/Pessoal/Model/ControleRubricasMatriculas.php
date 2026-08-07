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

namespace ECidade\RecursosHumanos\Pessoal\Model;

use BusinessException;
use Instituicao;
use InstituicaoRepository;
use Servidor;
use ServidorRepository;

class ControleRubricasMatriculas
{
    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var Instituicao
     */
    private $instituicao;

    /**
     * @var Servidor
     */
    private $servidor;

    /**
     * @var int $ano
     */
    private $ano;

    /**
     * @var int $mes
     */
    private $mes;

    /**
     * @var string
     */
    private $horasLiberadas;

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
     * @return Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param Instituicao $instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @return Servidor
     */
    public function getServidor()
    {
        return $this->servidor;
    }

    /**
     * @param Servidor $servidor
     */
    public function setServidor($servidor)
    {
        $this->servidor = $servidor;
    }

    /**
     * @return int
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * @param int $ano
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    /**
     * @return int
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * @param int $mes
     */
    public function setMes($mes)
    {
        $this->mes = $mes;
    }

    /**
     * @return string
     */
    public function getHorasLiberadas()
    {
        return $this->horasLiberadas;
    }

    /**
     * @param string $horasLiberadas
     */
    public function setHorasLiberadas($horasLiberadas)
    {
        $this->horasLiberadas = $horasLiberadas;
    }

    /**
     * Valida se a hora está no intervalo de 00:00 e 99:59
     * @return boolean
     */
    public function validaHorasLiberadas()
    {
        if (empty($this->horasLiberadas)) {
            return false;
        }

        return preg_match("/^[0-9][0-9]:[0-5][0-9]/", $this->horasLiberadas);
    }

    /**
     * @param array $state
     * @return ControleRubricasMatriculas
     * @throws
     * BusinessException
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (!empty($state['rh234_sequencial'])) {
            $self->setSequencial($state['rh234_sequencial']);
        }
        if (!empty($state['rh234_instituicao'])) {
            $self->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo($state['rh234_instituicao']));
        }
        if (!empty($state['rh234_matricula'])) {
            $self->setServidor(ServidorRepository::getInstanciaByCodigo($state['rh234_matricula']));
        }
        if (!empty($state['rh234_ano'])) {
            $self->setAno((int) $state['rh234_ano']);
        }
        if (!empty($state['rh234_mes'])) {
            $self->setMes((int) $state['rh234_mes']);
        }
        if (!empty($state['rh234_horas_liberadas'])) {
            $self->setHorasLiberadas($state['rh234_horas_liberadas']);
        }

        return $self;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'sequencial' => $this->getSequencial(),
            'instituicao' => !is_null($this->getInstituicao()) ? $this->getInstituicao()->toArray() : null,
            'servidor' => !is_null($this->getServidor()) ? $this->getServidor()->toArray() : null,
            'ano' => $this->getAno(),
            'mes' => $this->getMes(),
            'horasLiberadas' => $this->getHorasLiberadas()
        );
    }
}
