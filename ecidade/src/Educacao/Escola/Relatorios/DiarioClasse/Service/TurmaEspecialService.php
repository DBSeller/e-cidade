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

namespace ECidade\Educacao\Escola\Relatorios\DiarioClasse\Service;

use App\Domain\Educacao\Escola\Models\TurmaEspecial;
use App\Domain\Educacao\Escola\Requests\EmissaoDiarioClasseEspecialRequest;
use Carbon\Carbon;
use ECidade\Educacao\Escola\Model\AtividadeComplementar;
use ECidade\Educacao\Escola\Registry\AtividadeComplementarRegistry;
use ECidade\Educacao\Escola\Relatorios\DiarioClasse\Model\AlunoDiarioClasse;
use ECidade\Educacao\Escola\Relatorios\DiarioClasse\Model\DadosDiarioClasse;
use ECidade\Educacao\Escola\Relatorios\DiarioClasse\Model\TurmaDiarioClasse;
use ECidade\Enum\Educacao\Escola\SituacaoMatriculaEnum;
use Exception;

/**
 * Class TurmaEspecialService
 * @package ECidade\Educacao\Escola\Relatorio\DiarioClasse\Service
 */
class TurmaEspecialService
{
    /**
     * @var TurmaEspecial
     */
    private $turmaEspecial;
    /**
     * @var AtividadeComplementar|null
     */
    private $atividadeComplementar;
    /**
     * @var DadosDiarioClasse
     */
    private $dadosDiarioClasse;
    /**
     * @var string
     */
    private $profissionalEscola;

    /**
     * TurmaEspecialService constructor.
     * @param EmissaoDiarioClasseEspecialRequest $request
     * @throws Exception
     */
    public function __construct(EmissaoDiarioClasseEspecialRequest $request)
    {
        $this->turmaEspecial = TurmaEspecial::find($request->get('turma'));
        $atividadeComplementar = $request->get('atividade_complementar');
        if (!empty($atividadeComplementar)) {
            $this->atividadeComplementar = AtividadeComplementarRegistry::get($atividadeComplementar);
        }

        $this->profissionalEscola = $request->get('regente');
    }

    /**
     * @return DadosDiarioClasse
     * @throws Exception
     */
    public function processarDados()
    {
        $this->buildDadosDiario();
        $this->buildMatriculas();
        return $this->dadosDiarioClasse;
    }

    private function buildDadosDiario()
    {
        $turma = new TurmaDiarioClasse();
        $turma->setCodigo($this->turmaEspecial->getAttribute('ed268_i_codigo'))
            ->setNome($this->turmaEspecial->getAttribute('ed268_c_descr'));

        $this->dadosDiarioClasse = new DadosDiarioClasse();
        $this->dadosDiarioClasse->setTurma($turma)
            ->setNomeRegente($this->profissionalEscola)
            ->setEscola($this->turmaEspecial->escola)
            ->setCalendario($this->turmaEspecial->calendario)
            ->setTurno($this->turmaEspecial->turno);
        if ($this->atividadeComplementar) {
            $this->dadosDiarioClasse->setAtividadeComplementar($this->atividadeComplementar);
        }
    }

    /**
     * @throws Exception
     */
    private function buildMatriculas()
    {
        $matriculas = $this->turmaEspecial->matriculas;
        $aMatriculas = $matriculas->toArray();

        usort($aMatriculas, function ($matriculaAtual, $matriculaProxima) {
            return strcmp($matriculaAtual['aluno']['ed47_v_nome'], $matriculaProxima['aluno']['ed47_v_nome']);
        });
        foreach ($aMatriculas as $matricula) {
            $alunoDiario = new AlunoDiarioClasse();
            $alunoVinculado = $matricula["aluno"];
            $dataNascimento = new Carbon($alunoVinculado["ed47_d_nasc"]);

            $alunoDiario->setCodigo($alunoVinculado["ed47_i_codigo"])
                ->setNome($alunoVinculado["ed47_v_nome"])
                ->setDataNascimento($dataNascimento)
                ->setSituacao(new SituacaoMatriculaEnum('MATRICULADO'));
            $this->dadosDiarioClasse->addAluno($alunoDiario);
        }
    }
}
