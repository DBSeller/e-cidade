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

namespace ECidade\RecursosHumanos\RH\Efetividade\Model;

use Assentamento;
use Exception;
use Instituicao;

class AssentamentoEncerramentoEfetividade
{
    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var Assentamento
     */
    private $assentamento;

    /**
     * @var int
     */
    private $ano;

    /**
     * @var string
     */
    private $mes;

    /**
     * @var Instituicao
     */
    private $instituicao;

    /**
     * @param array $state
     * @return AssentamentoEncerramentoEfetividade
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('rh230_sequencial', $state)) {
            $self->setSequencial($state['rh230_sequencial']);
        }

        if (array_key_exists('rh230_assentamento', $state) && !empty($state['rh230_assentamento'])) {
            $self->setAssentamento(new Assentamento($state['rh230_assentamento']));
        }

        if (array_key_exists('rh230_ano', $state)) {
            $self->setAno($state['rh230_ano']);
        }

        if (array_key_exists('rh230_mes', $state)) {
            $self->setMes($state['rh230_mes']);
        }

        if (array_key_exists('rh230_instituicao', $state) && !empty($state['rh230_instituicao'])) {
            $self->setInstituicao(new Instituicao($state['rh230_instituicao']));
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
            'assentamento' => $this->getAssentamento()->toArray(),
            'ano' => $this->getAno(),
            'mes' => $this->getMes(),
            'instituicao' => $this->getInstituicao()->toArray()
        );
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
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @return Assentamento
     */
    public function getAssentamento()
    {
        return $this->assentamento;
    }

    /**
     * @param Assentamento $assentamento
     */
    public function setAssentamento(Assentamento $assentamento)
    {
        $this->assentamento = $assentamento;
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
     * @return string
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * @param string $mes
     */
    public function setMes($mes)
    {
        $this->mes = $mes;
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
    public function setInstituicao(Instituicao $instituicao)
    {
        $this->instituicao = $instituicao;
    }
}
