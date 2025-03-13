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

namespace ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Service;

use ECidade\Educacao\Escola\Censo\Censo;
use ECidade\Educacao\Escola\Censo\Helpers\Pessoa;
use ECidade\Educacao\Escola\Censo\Helpers\Turma;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Builder\Registro50Builder;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\Registro30;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\Registro50;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\TurmaCensoVo;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Repository\Registro50Repository;
use ECidade\Educacao\Escola\Model\CensoDisciplina;
use ECidade\Educacao\Escola\Model\ProfissionalEscola;
use ECidade\Educacao\Escola\Registry\CensoDisciplinaRegistry;
use ECidade\Educacao\Escola\Registry\ProfissionalEscolaRegistry;
use Escola;
use Exception;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Validators\Registro50Validator;

/**
 * Class Registro50Service
 * @package ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Service
 */
class Registro50Service
{
    /**
     * @var Registro50Repository
     */
    protected $registro50Repository;

    /**
     * @var Registro50[]
     */
    protected $registros = array();

    /**
     * @var Censo
     */
    protected $censo;

    /**
     * @var Escola
     */
    protected $escola;

    /**
     * @var Registro00Service
     */
    private $registro00Service;

    /**
     * @var Registro20Service
     */
    private $registro20Service;

    /**
     * @var Registro30Service
     */
    private $registro30Service;

    public function __construct()
    {
        $this->registro50Repository = new Registro50Repository();
    }

    public function setRegistro00Service(Registro00Service $registro00Service)
    {
        $this->registro00Service = $registro00Service;
    }

    public function setRegistro20Service(Registro20Service $registro20Service)
    {
        $this->registro20Service = $registro20Service;
    }

    public function setRegistro30Service(Registro30Service $registro30Service)
    {
        $this->registro30Service = $registro30Service;
    }

    /**
     * @param TurmaCensoVo $turma
     * @throws Exception
     */
    protected function buscarProfessoresTurmasRegulares(TurmaCensoVo $turma)
    {
        $dataCenso = $this->censo->getDataCenso();
        $profissionaisFuncao = $this->registro50Repository->resetScopes()
            ->scopeTurma($turma->getCodigoTurma())
            ->scopeProfissionaisAtivosDataCenso($dataCenso)
            ->getOutrosProfissionaisTurma();

        $this->buildProfissionais($turma, $profissionaisFuncao);

        $professores = $this->registro50Repository->regentesAtivos($dataCenso)
            ->scopeDocentePresente($dataCenso)
            ->getProfessoresTurma();

        $this->buildProfissionais($turma, $professores, true);
    }

    /**
     * @param TurmaCensoVo $turma
     * @param array $profissionaisFuncao
     * @throws Exception
     */
    protected function buildProfissionais(TurmaCensoVo $turma, array $profissionaisFuncao, $professor = false)
    {
        foreach ($profissionaisFuncao as $profissionalFuncao) {
            $profissional = ProfissionalEscolaRegistry::get($profissionalFuncao->vinculo_escola);

            if (is_null($profissional)) {
                continue;
            }

            $builder = new Registro50Builder();
            $builder->addProfissional($profissional)
                ->addFuncao($profissionalFuncao->funcao)
                ->addTurma($turma);

            if ($professor) {
                $disciplinas = $this->getDisciplinasProfessor($turma, $profissional);
                $temDuploVinculo = $this->validaDuploVinculoProfissional($profissional, $turma, $disciplinas);

                if ($temDuploVinculo) {
                    continue;
                }

                if (!in_array($turma->getEtapaCenso(), array(1, 2, 3))) {
                    $builder->addDisciplinas($disciplinas);
                }
            }

            $this->addRegistro($builder->build());
        }
    }

    /**
     * @param TurmaCensoVo $turma
     * @throws Exception
     */
    protected function buscarProfissionaisTurmasEspeciais(TurmaCensoVo $turma)
    {
        $profissionaisFuncao = $this->registro50Repository->resetScopes()->scopeTurmaEspecial($turma->getCodigoTurma())
            ->scopeProfissionaisAtivosDataCenso($this->censo->getDataCenso())
            ->getProfissionaisTurmasEspecial();

        $this->buildProfissionais($turma, $profissionaisFuncao);
    }

    /**
     * @throws Exception
     */
    public function buscarDados()
    {
        $turmasProcessadas = Registro20Service::getTurmasProcessadas();
        foreach ($turmasProcessadas as $turmaProcessada) {
            if ($turmaProcessada->isEscolarizacao()) {
                $this->buscarProfessoresTurmasRegulares($turmaProcessada);
            } else {
                $this->buscarProfissionaisTurmasEspeciais($turmaProcessada);
            }
        }
    }

    public function setCenso(Censo $censo)
    {
        $this->censo = $censo;
    }

    public function setEscola(Escola $escola)
    {
        $this->escola = $escola;
    }

    /**
     * @param Registro50 $registro
     */
    private function addRegistro($registro)
    {
        $this->registros[] = $registro;
    }

    /**
     * @param TurmaCensoVo $turma
     * @param ProfissionalEscola $profissional
     * @return CensoDisciplina[]
     * @throws Exception
     */
    private function getDisciplinasProfessor(TurmaCensoVo $turma, ProfissionalEscola $profissional)
    {
        return $this->registro50Repository->resetScopes()
            ->scopeCodigoRecHumano($profissional->getCodigoRecursoHumano())
            ->scopeTurma($turma->getCodigoTurma())
            ->regentesAtivos($this->censo->getDataCenso())
            ->getDisciplinasProfessor();
    }

    /**
     * @return Registro50[]
     */
    public function getRegistros()
    {
        return $this->registros;
    }

    public function validar()
    {
        foreach ($this->registros as $registro) {
            $validator = new Registro50Validator();

            $registro00 = $this->registro00Service->getRegistro();
            $registro20 = $this->getRegistro20($registro);
            $registro30 = $this->getRegistro30($registro);

            $validator->setRegistro($registro);
            $validator->setRegistro00($registro00);
            $validator->setRegistro20($registro20);
            $validator->setRegistro30($registro30);

            $validator->validar();
        }
    }

    private function getRegistro20(Registro50 $vinculoProfTurma)
    {
        foreach ($this->registro20Service->getRegistros() as $turma) {
            if ($vinculoProfTurma->getCodigoTurma() == $turma->getCodigoTurma()) {
                return $turma;
            }
        }

        return null;
    }

    /**
     * @param Registro50 $vinculoProfTurma
     * @return Registro30|null
     */
    private function getRegistro30(Registro50 $vinculoProfTurma)
    {
        foreach ($this->registro30Service->getRegistros() as $prof) {
            if ($vinculoProfTurma->getCodigoPessoa() == $prof->getCodigoPessoa()) {
                return $prof;
            }
        }

        return null;
    }

    /**
     * Essa função foi criada para resolver um caso muito especifico que aconteceu em natal.
     * Onde um profissional tinha 2 matrículas, uma para trabalhar pela manha e outra a tarde e o profissional
     * estava vinculado em uma mesma turma de turno integral onde pela manhã era uma matrícula e a tarde outra
     *
     * @param ProfissionalEscola $profissional
     * @param TurmaCensoVo $turma
     * @param CensoDisciplina[] $disciplinas
     * @return boolean
     */
    private function validaDuploVinculoProfissional(
        ProfissionalEscola $profissional,
        TurmaCensoVo $turma,
        $disciplinas = array()
    ) {
        $registro = null;
        foreach ($this->registros as $registro50) {
            if (Pessoa::decodeCodigoProfissional($registro50->getCodigoPessoa()) === $profissional->getCgm()->getCpf()
                && Turma::decodeCodigoTurma($registro50->getCodigoTurma()) == $turma->getCodigoTurma()) {
                $registro = $registro50;
                break;
            }
        }
        if (is_null($registro)) {
            return false;
        }

        if (!in_array($turma->getEtapaCenso(), [1, 2, 3])) {
            $this->mergeDisciplinasVinculoProfissional($registro, $disciplinas);
        }

        return true;
    }

    /**
     * @param Registro50 $registro
     * @param CensoDisciplina[] $disciplinas
     */
    private function mergeDisciplinasVinculoProfissional(Registro50 $registro, $disciplinas = array())
    {
        $disciplinasRegistro = array(
            $registro->getCodigo1(),
            $registro->getCodigo2(),
            $registro->getCodigo3(),
            $registro->getCodigo4(),
            $registro->getCodigo5(),
            $registro->getCodigo6(),
            $registro->getCodigo7(),
            $registro->getCodigo8(),
            $registro->getCodigo9(),
            $registro->getCodigo10(),
            $registro->getCodigo11(),
            $registro->getCodigo12(),
            $registro->getCodigo13(),
            $registro->getCodigo14(),
            $registro->getCodigo15()
        );

        $disciplinasRegistro = array_filter($disciplinasRegistro);
        foreach ($disciplinasRegistro as $disciplinaRegistro) {
            if (!is_null($disciplinaRegistro)) {
                $disciplinaX = CensoDisciplinaRegistry::get($disciplinaRegistro);
                $existe = array_filter($disciplinas, function (CensoDisciplina $disciplina) use ($disciplinaX) {
                    return $disciplina->getCodigo() === $disciplinaX->getCodigo();
                });
                if (empty($existe)) {
                    $disciplinas[] = $disciplinaX;
                }
            }
        }

        foreach ($disciplinas as $index => $disciplina) {
            switch ($index) {
                case 0:
                    $registro->setCodigo1($disciplina->getCodigo());
                    break;
                case 1:
                    $registro->setCodigo2($disciplina->getCodigo());
                    break;
                case 2:
                    $registro->setCodigo3($disciplina->getCodigo());
                    break;
                case 3:
                    $registro->setCodigo4($disciplina->getCodigo());
                    break;
                case 4:
                    $registro->setCodigo5($disciplina->getCodigo());
                    break;
                case 5:
                    $registro->setCodigo6($disciplina->getCodigo());
                    break;
                case 6:
                    $registro->setCodigo7($disciplina->getCodigo());
                    break;
                case 7:
                    $registro->setCodigo8($disciplina->getCodigo());
                    break;
                case 8:
                    $registro->setCodigo9($disciplina->getCodigo());
                    break;
                case 9:
                    $registro->setCodigo10($disciplina->getCodigo());
                    break;
                case 10:
                    $registro->setCodigo11($disciplina->getCodigo());
                    break;
                case 11:
                    $registro->setCodigo12($disciplina->getCodigo());
                    break;
                case 12:
                    $registro->setCodigo13($disciplina->getCodigo());
                    break;
                case 13:
                    $registro->setCodigo14($disciplina->getCodigo());
                    break;
                case 14:
                    $registro->setCodigo15($disciplina->getCodigo());
                    break;
            }
        }
    }
}
