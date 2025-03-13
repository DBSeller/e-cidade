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
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Builder\Registro60Builder;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\Registro30;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\Registro60;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Repository\Registro60Repository;
use Exception;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Validators\Registro60Validator;

class Registro60Service
{
    /**
     * @var Registro60[]
     */
    private $registros = array();
    /**
     * @var Censo
     */
    private $censo;

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
     * @param Censo $censo
     */
    public function setCenso(Censo $censo)
    {
        $this->censo = $censo;
    }


    /**
     * @throws Exception
     */
    public function buscarDados()
    {
        $registro60Repository = new Registro60Repository();
        $turmasProcessadas = Registro20Service::getTurmasProcessadas();
        $matriculas = array();
        foreach ($turmasProcessadas as $turma) {
            if ($turma->isEscolarizacao()) {
                $matriculas = array_merge(
                    $matriculas,
                    $registro60Repository->getMatriculaTurmaRegular($turma, $this->censo)
                );
            } else {
                // turmas especiais incluem turmas AEE e Atividade Complementar
                $matriculas = array_merge(
                    $matriculas,
                    $registro60Repository->getMatriculaTurmaEspecial($turma, $this->censo)
                );
            }

            // Carregar alunos de turmas que são multietapa de ensino diferente mas não são as principais
            foreach ($turma->getCodigosTurmasVinculadas() as $turmaVinculada) {
                $turmaVinculada = Registro20Service::getTurmaVinculada($turmaVinculada);
                $matriculasVinculadas = $registro60Repository->getMatriculaTurmaRegular($turmaVinculada, $this->censo);
                foreach ($matriculasVinculadas as $matriculaVinculada) {
                    $matriculaVinculada->setTurma($turma);
                }
                $matriculas = array_merge($matriculas, $matriculasVinculadas);
            }
        }

        foreach ($matriculas as $matricula) {
            $registro60Builder = new Registro60Builder();
            $registro60Builder->addMatricula($matricula);
            if ($matricula->getAluno()->isUtilizaTransportePublico()) {
                $registro60Builder->addVeiculoUtilizado(
                    $registro60Repository->getTransporteEscolar($matricula->getAluno())
                );
            }

            $this->addRegistro($registro60Builder->build());
        }
    }

    private function addRegistro(Registro60 $registro)
    {
        $this->registros[] = $registro;
    }

    /**
     * @return Registro60[]
     */
    public function getRegistros()
    {
        return $this->registros;
    }

    public function validar()
    {
        foreach ($this->registros as $registro) {
            $validator = new Registro60Validator();

            $registro00 = $this->registro00Service->getRegistro();
            $registro20 = $this->getRegistro20($registro);
            $registro30 = $this->getRegistro30($registro);

            $validator->setRegistro($registro);
            $validator->setRegistro00($registro00);
            $validator->setRegistro20($registro20);

            if ($registro30 instanceof Registro30) {
                $validator->setRegistro30($registro30);
            }

            $validator->validar();
        }
    }

    private function getRegistro20(Registro60 $aluno)
    {
        foreach ($this->registro20Service->getRegistros() as $turma) {
            if ($turma->getCodigoTurma() == $aluno->getCodigoTurma()) {
                return $turma;
            }
        }

        return null;
    }

    private function getRegistro30(Registro60 $aluno)
    {
        foreach ($this->registro30Service->getRegistros() as $pessoa) {
            if ($pessoa->getCodigoPessoa() == $aluno->getCodigoPessoa()) {
                return $pessoa;
            }
        }

        return null;
    }
}
