<?php

namespace ECidade\Educacao\Escola\Censo\SituacaoAluno\Dados;

use DBString;
use stdClass;

/**
 * Processa os dados do Aluno que entrou após a data do censo.
 * Registro 91 do Layout de Exportação da Situação do Aluno 2016
 *
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.4 $
 */
class DadosAlunosApos2016 extends DadosAluno2016 implements DadosInterface
{
    private $iMediacaoDidaticoPedagogico;
    private $iModalidade;
    private $iEtapaArquivo; // etapa que vai no arquivo do censo.

    private $iEtapaMatricula;
    private $iEtapaTurma;

    /**
     * @param stdClass $oDados
     */
    public function popular(stdClass $oDados)
    {
        parent::popular($oDados);
        $this->iMediacaoDidaticoPedagogico = $oDados->mediacao_didatico_pedagogico;
        $this->iModalidade = $oDados->modalidade;
        $this->iEtapaMatricula = $oDados->etapa;
        $this->iEtapaTurma = $oDados->etapa_turma;
        $this->iEtapaArquivo = $oDados->etapa_turma;

        if (empty($this->iTurmaInep)) {
            $this->iEtapaArquivo = $this->iEtapaMatricula;
        }

        if (!empty($this->iTurmaInep)) {
            //campo 8 - regra 2
            $this->iMediacaoDidaticoPedagogico = '';
            //campo 9 - regra 2
            $this->iModalidade = '';
        }

        // campo 10 - regra 2
        $aMultiEtapaCenso = array(3, 12, 13, 22, 23, 24, 64, 72);
        if (in_array($this->iEtapaTurma, $aMultiEtapaCenso)) {
            $this->iEtapaArquivo = $this->iEtapaMatricula;
        }
        // campo 10 - regra 3
        if (!empty($this->iTurmaInep) && !in_array($this->iEtapaTurma, $aMultiEtapaCenso)) {
            $this->iEtapaArquivo = '';
        }
    }

    /**
     * Transforma os dados da classe em uma stdClass para informar no layout
     * @return stdClass
     */
    public function transformarStdClass()
    {
        $oDados = new stdClass();

        $oDados->tipo_registro = 91;
        $oDados->codigo_escola_inep = $this->iEscolaInep;
        $oDados->codigo_turma_escola = $this->iTurmaEscola;
        $oDados->codigo_turma_inep = $this->iTurmaInep;
        $oDados->codigo_aluno_inep = $this->iAlunoInep;
        $oDados->codigo_aluno_escola = $this->iAlunoEscola;
        $oDados->codigo_matricula_inep = $this->iMatriculaInep;
        $oDados->mediacao_didatico_pedagogico = $this->iMediacaoDidaticoPedagogico;
        $oDados->modalidade = $this->iModalidade;
        $oDados->etapa = $this->iEtapaArquivo;
        $oDados->situacao_aluno = $this->iSituacaoAluno;

        return $oDados;
    }

    /**
     * @param null $iInepEscola
     * @return bool
     */
    public function validar($iInepEscola = null)
    {
        $this->validarINEPEscola($iInepEscola);
        $this->validarINEPTurma();
        $this->validarINEPAluno();
        $this->validarINEPMatricula();
        $this->validarSituacao();

        $this->validarMediacaoDidaticoPedagogica();
        $this->validarModalidade();
        $this->validarEtapa();

        return count($this->aErros) == 0;
    }

    /**
     * Realiza as validações do campo 2 Código da Escola - INEP
     */
    protected function validarINEPTurma()
    {
        // campo 4 - regra 1
        if (!empty($this->iTurmaInep) && !DBString::validarTamanhoMaximo($this->iTurmaInep, 10)) {
            $this->addErro('O campo "Código da turma - INEP" está maior que o especificado.');
        }
        // campo 4 - regra 2
        if (!empty($this->iTurmaInep) && !DBString::isSomenteNumero($this->iTurmaInep)) {
            $this->addErro('O campo "Código da turma - INEP" foi preenchido com valor inválido.');
        }
    }

    /**
     * Realiza as validações do campo Matrícula (INEP)
     */
    protected function validarINEPMatricula()
    {
        //Regra 1
        if (!empty($this->iMatriculaInep)) {
            $this->addErro('O campo "Código da matrícula" não pode ser preenchido.');
        }
    }

    /**
     * Realiza as validações do campo Tipo de mediação didático pedagógico
     */
    protected function validarMediacaoDidaticoPedagogica()
    {
        $campo = 'O campo "Tipo de mediação didático pedagógico"';
        // campo 8 - regra 1
        if (empty($this->iTurmaInep) && empty($this->iMediacaoDidaticoPedagogico)) {
            $this->addErro(sprintf(
                '%s deve ser preenchido quando o campo "Código da turma - INEP" não for preenchido.',
                $campo
            ));
        }

        // campo 8 - regra 3
        if (!empty($this->iMediacaoDidaticoPedagogico) &&
            !in_array($this->iMediacaoDidaticoPedagogico, [1, 2, 3])) {
            $this->addErro(sprintf('%s foi preenchido com valor inválido.', $campo));
        }
    }

    /**
     * Realiza as validações do campo Código da Modalidade
     */
    protected function validarModalidade()
    {
        $campo = 'O campo "Código da modalidade"';
        //campo 9 - regra 1
        if (empty($this->iTurmaInep) && empty($this->iModalidade)) {
            $this->addErro(sprintf(
                '%s deve ser preenchido quando o campo "Código da turma - INEP" não for preenchido.',
                $campo
            ));
        }
        //campo 9 - regra 3
        if (!empty($this->iModalidade) && !in_array($this->iModalidade, [1, 2, 3, 4])) {
            $this->addErro(sprintf('%s foi preenchido com valor inválido.', $campo));
        }
        //campo 9 - regra 4
        if ($this->iMediacaoDidaticoPedagogico == 3 && !in_array($this->iModalidade, [1, 3, 4])) {
            $this->addErro(sprintf(
                '%s deve ser preenchido com 1, 3 ou 4 quando o campo "Mediação didático-pedagógica" %s',
                $campo,
                'for igual a 3 (Educação a Distância).'
            ));
        }

        $campo = 'Aluno(a) sem deficiência, transtorno global do desenvolvimento ou altas habilidades/superdotação';
        //campo 9 - regra 5
        if (!empty($this->iModalidade) && $this->iModalidade == 2) {
            $aDeficiencias = $this->oMatricula->getAluno()->getNecessidadesEspeciais();
            if (count($aDeficiencias) == 0) {
                $this->addErro(sprintf('%s não pode ser admitido após em uma turma de educação especial.', $campo));
            } else {
                $possuiDeficiencia = false;
                foreach ($aDeficiencias as $oDeficiencia) {
                    if (!in_array($oDeficiencia->iCodigo, [110, 111, 112])) {
                        $possuiDeficiencia = true;
                    }
                }

                if ($possuiDeficiencia) {
                    $this->addErro(sprintf('%s não pode ser admitido após em uma turma de educação especial.', $campo));
                }
            }
        }
    }

    /**
     * Realiza as validações do campo Código da Etapa
     */
    protected function validarEtapa()
    {
        $campo = 'O campo "Código da etapa"';
        // campo 10 - regra 1
        if (empty($this->iTurmaInep) && empty($this->iEtapaArquivo)) {
            $this->addErro(
                sprintf('%s deve ser preenchido quando o campo "Código da turma - INEP" não for preenchido.', $campo)
            );
        }
        // campo 10 - regra 5
        if (in_array($this->iEtapaArquivo, [3, 22, 23, 56, 64, 68, 72])) {
            $this->addErro(sprintf('%s foi preenchido com valor não permitido.', $campo));
        }

        // campo 10 - regra 6
        $campo = 'O campo "Etapa de Ensino" deve ser preenchido';
        if ($this->iMediacaoDidaticoPedagogico == 2 && !in_array($this->iEtapaArquivo, [69, 70, 71])) {
            $this->addErro(sprintf(
                '%s com 69, 70 ou 71 quando o campo "Mediação didático-pedagógica" for igual a 2 (Semipresencial).',
                $campo
            ));
        }
        // campo 10 - regra 7
        if ($this->iMediacaoDidaticoPedagogico == 3 &&
            !in_array($this->iEtapaArquivo, [30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 70, 71, 73, 74, 67])) {
            $this->addErro(sprintf(
                '%s com 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 70, 71, 73, 74 ou 67 quando o %s',
                $campo,
                'campo "Mediação didático-pedagógica" for igual a 3 (Educação a Distância).'
            ));
        }

        $sMsg = sprintf(
            '%s foi preenchido com valor incompatível com a turma informada no campo "Código da turma - INEP".',
            $campo
        );
        // campo 10 - regra 9
        if ($this->iEtapaTurma == 3 && !in_array($this->iEtapaArquivo, [1, 2])) {
            $this->addErro($sMsg);
        }
        // campo 10 - regra 10
        if (in_array($this->iEtapaTurma, [12, 13]) &&
            !in_array($this->iEtapaArquivo, [4, 5, 6, 7, 8, 9, 10, 11])) {
            $this->addErro($sMsg);
        }
        // campo 10 - regra 11
        if (in_array($this->iEtapaTurma, [22, 23]) &&
            !in_array($this->iEtapaArquivo, [14, 15, 16, 17, 18, 19, 20, 21,41])) {
            $this->addErro($sMsg);
        }

        // campo 10 - regra 13
        if ($this->iEtapaTurma == 72 && !in_array($this->iEtapaArquivo, array(69, 70))) {
            $this->addErro($sMsg);
        }
        // campo 10 - regra 14
        if ($this->iEtapaTurma == 56 &&
            !in_array($this->iEtapaArquivo, [1, 2, 14, 15, 16, 17, 18, 19, 20, 21, 41])) {
            $this->addErro($sMsg);
        }
        // campo 10 - regra 15
        if ($this->iEtapaTurma == 64 && !in_array($this->iEtapaArquivo, [39, 40])) {
            $this->addErro($sMsg);
        }
    }
}
