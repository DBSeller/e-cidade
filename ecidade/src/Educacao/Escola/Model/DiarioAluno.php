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

use AlunoRepository;
use ECidade\Educacao\Escola\Registry\DiarioAlunoRegistry;
use Etapa;
use EtapaRepository;
use Turma;
use TurmaRepository;

/**
 * Class DiarioAluno
 * @package ECidade\Educacao\Escola\Model
 */
class DiarioAluno
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var \Aluno
     */
    private $aluno;
    /**
     * @var Turma
     */
    private $turma;
    /**
     * @var Etapa
     */
    private $etapa;
    /**
     * @var boolean
     */
    private $encerrado = false;

    /**
     * @var DiarioAlunoResultadoFinal
     */
    private $resultadoFinal;

    /**
     * @var DiarioArea[]
     */
    private $diarioAreasConhecimento = [];

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return DiarioAluno
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return \Aluno
     */
    public function getAluno()
    {
        return $this->aluno;
    }

    /**
     * @param \Aluno $aluno
     * @return DiarioAluno
     */
    public function setAluno(\Aluno $aluno)
    {
        $this->aluno = $aluno;
        return $this;
    }

    /**
     * @return Turma
     */
    public function getTurma()
    {
        return $this->turma;
    }

    /**
     * @param Turma $turma
     * @return DiarioAluno
     */
    public function setTurma(Turma $turma)
    {
        $this->turma = $turma;
        return $this;
    }

    /**
     * @return Etapa
     */
    public function getEtapa()
    {
        return $this->etapa;
    }

    /**
     * @param Etapa $etapa
     * @return DiarioAluno
     */
    public function setEtapa(Etapa $etapa)
    {
        $this->etapa = $etapa;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEncerrado()
    {
        return $this->encerrado;
    }

    /**
     * @param bool $encerrado
     * @return DiarioAluno
     */
    public function setEncerrado($encerrado)
    {
        $this->encerrado = $encerrado;
        return $this;
    }

    /**
     * @return DiarioAlunoResultadoFinal
     */
    public function getResultadoFinal()
    {
        return $this->resultadoFinal;
    }

    /**
     * @param DiarioAlunoResultadoFinal $resultadoFinal
     * @return DiarioAluno
     */
    public function setResultadoFinal(DiarioAlunoResultadoFinal $resultadoFinal)
    {
        $this->resultadoFinal = $resultadoFinal;
        return $this;
    }

    /**
     * @return DiarioArea[]
     */
    public function getDiarioAreasConhecimento()
    {
        return $this->diarioAreasConhecimento;
    }

    /**
     * @param DiarioArea[] $diarioAreasConhecimento
     * @return DiarioAluno
     */
    public function setDiarioAreasConhecimento(array $diarioAreasConhecimento)
    {
        $this->diarioAreasConhecimento = $diarioAreasConhecimento;
        return $this;
    }

    /**
     * @param array $state
     * @return DiarioAluno
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed161_codigo', $state)) {
            $self->setCodigo($state['ed161_codigo']);
        }
        if (array_key_exists('ed161_aluno', $state)) {
            $self->setAluno(AlunoRepository::getAlunoByCodigo($state['ed161_aluno']));
        }
        if (array_key_exists('ed161_turma', $state)) {
            $self->setTurma(TurmaRepository::getTurmaByCodigo($state['ed161_turma']));
        }
        if (array_key_exists('ed161_serie', $state)) {
            $self->setEtapa(EtapaRepository::getEtapaByCodigo($state['ed161_serie']));
        }
        if (array_key_exists('ed161_encerrado', $state)) {
            $self->setEncerrado($state['ed161_encerrado'] === 't');
        }

        DiarioAlunoRegistry::set($self);
        return $self;
    }

    /**
     * @param DiarioArea $diarioArea
     */
    public function addAreaConhecimento(DiarioArea $diarioArea)
    {
        $this->diarioAreasConhecimento[] = $diarioArea;
    }
}
