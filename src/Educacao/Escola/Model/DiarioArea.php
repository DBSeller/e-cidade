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

namespace ECidade\Educacao\Escola\Model;

use DiarioAvaliacaoDisciplina;
use ECidade\Educacao\Escola\Registry\AreaConhecimentoRegistry;
use ECidade\Educacao\Escola\Registry\DiarioAlunoRegistry;
use ECidade\Educacao\Escola\Registry\DiarioAreaRegistry;
use Exception;

/**
 * Class DiarioArea
 * @package ECidade\Educacao\Escola\Model
 */
class DiarioArea
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var AreaConhecimento
     */
    private $areaConhecimento;

    /**
     * @var DiarioAluno
     */
    private $diarioAluno;

    /**
     * @var DiarioAreaAvaliacao[]
     */
    private $avaliacoes = [];
    /**
     * @var DiarioAreaResultado
     */
    private $resultado;

    /**
     * @var DiarioAvaliacaoDisciplina[]
     */
    private $diarioAvaliacaoDisciplinas = [];

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return DiarioArea
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return AreaConhecimento
     */
    public function getAreaConhecimento()
    {
        return $this->areaConhecimento;
    }

    /**
     * @param AreaConhecimento $areaConhecimento
     * @return DiarioArea
     */
    public function setAreaConhecimento(AreaConhecimento $areaConhecimento)
    {
        $this->areaConhecimento = $areaConhecimento;
        return $this;
    }

    /**
     * @return DiarioAluno
     */
    public function getDiarioAluno()
    {
        return $this->diarioAluno;
    }

    /**
     * @param DiarioAluno $diarioAluno
     * @return DiarioArea
     */
    public function setDiarioAluno($diarioAluno)
    {
        $this->diarioAluno = $diarioAluno;
        return $this;
    }

    /**
     * @return DiarioAreaAvaliacao[]
     */
    public function getAvaliacoes()
    {
        return $this->avaliacoes;
    }

    /**
     * @param DiarioAreaAvaliacao[] $avaliacoes
     * @return DiarioArea
     */
    public function setAvaliacoes($avaliacoes)
    {
        $this->avaliacoes = $avaliacoes;
        return $this;
    }

    /**
     * @param DiarioAreaResultado $diarioAreaResultado
     * @return DiarioArea
     */
    public function setResultado(DiarioAreaResultado $diarioAreaResultado)
    {
        $this->resultado = $diarioAreaResultado;
        return $this;
    }

    /**
     * @return DiarioAreaResultado
     */
    public function getResultado()
    {
        return $this->resultado;
    }

    /**
     * @param array $state
     * @return DiarioArea
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed162_codigo', $state)) {
            $self->setCodigo($state['ed162_codigo']);
        }
        if (array_key_exists('ed162_areaconhecimento', $state)) {
            $self->setAreaConhecimento(AreaConhecimentoRegistry::get($state['ed162_areaconhecimento']));
        }
        if (array_key_exists('ed162_diarioaluno', $state)) {
            $self->setDiarioAluno(DiarioAlunoRegistry::get($state['ed162_diarioaluno']));
        }

        DiarioAreaRegistry::set($self);

        return $self;
    }

    /**
     * @param DiarioAreaAvaliacao $diarioAreaAvaliacao
     */
    public function addAvaliacao(DiarioAreaAvaliacao $diarioAreaAvaliacao)
    {
        $this->avaliacoes[] = $diarioAreaAvaliacao;
    }

    /**
     * @param DiarioAvaliacaoDisciplina $diarioAvaliacaoDisciplina
     */
    public function addDiarioAvaliacaoDisciplina(DiarioAvaliacaoDisciplina $diarioAvaliacaoDisciplina)
    {
        $this->diarioAvaliacaoDisciplinas[] = $diarioAvaliacaoDisciplina;
    }

    /**
     * @return DiarioAvaliacaoDisciplina[]
     */
    public function getDiarioAvaliacaoDisciplinas()
    {
        return $this->diarioAvaliacaoDisciplinas;
    }
}
