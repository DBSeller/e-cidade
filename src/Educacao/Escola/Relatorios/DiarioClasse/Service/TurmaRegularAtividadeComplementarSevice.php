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

use App\Domain\Educacao\Escola\Models\Calendario;
use App\Domain\Educacao\Escola\Models\Escola;
use App\Domain\Educacao\Escola\Models\Turno;
use App\Domain\Educacao\Escola\Requests\EmissaoDiarioClasseEspecialRequest;
use Carbon\Carbon;
use ECidade\Educacao\Escola\Model\AtividadeComplementar;
use ECidade\Educacao\Escola\Registry\AtividadeComplementarRegistry;
use ECidade\Educacao\Escola\Relatorios\DiarioClasse\Model\AlunoDiarioClasse;
use ECidade\Educacao\Escola\Relatorios\DiarioClasse\Model\DadosDiarioClasse;
use ECidade\Educacao\Escola\Relatorios\DiarioClasse\Model\TurmaDiarioClasse;
use ECidade\Educacao\Escola\Repository\ProfissionalEscolaRepository;
use Exception;
use Turma;

/**
 * Class TurmaRegularAtividadeComplementarSevice
 * @package ECidade\Educacao\Escola\Relatorio\DiarioClasse\Service
 */
class TurmaRegularAtividadeComplementarSevice
{
    /**
     * @var Turma
     */
    private $turma;
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
     * TurmaRegularAtividadeComplementarSevice constructor.
     * @param EmissaoDiarioClasseEspecialRequest $request
     * @throws Exception
     */
    public function __construct(EmissaoDiarioClasseEspecialRequest $request)
    {
        $this->turma = \TurmaRepository::getTurmaByCodigo($request->get('turma'));
        $this->atividadeComplementar = AtividadeComplementarRegistry::get($request->get('atividade_complementar'));
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

    /**
     * @throws Exception
     */
    private function buildMatriculas()
    {
        $matriculas = $this->turma->getAlunosMatriculados();

        foreach ($matriculas as $matricula) {
            $alunoDiario = new AlunoDiarioClasse();
            $aluno = $matricula->getAluno();
            $alunoDiario->setCodigo($aluno->getCodigoAluno())
                ->setNome($aluno->getNome())
                ->setDataNascimento(new Carbon($aluno->getDataNascimento()))
                ->setNumero($matricula->getNumeroOrdemAluno());

            $this->dadosDiarioClasse->addAluno($alunoDiario);
        }
    }

    private function buildDadosDiario()
    {
        $turma = new TurmaDiarioClasse();
        $turma->setCodigo($this->turma->getCodigo())
            ->setNome($this->turma->getDescricao());

        $this->dadosDiarioClasse = new DadosDiarioClasse();
        $this->dadosDiarioClasse->setTurma($turma)
            ->setAtividadeComplementar($this->atividadeComplementar)
            ->setNomeRegente($this->profissionalEscola)
            ->setEscola(Escola::find($this->turma->getEscola()->getCodigo()))
            ->setCalendario(Calendario::find($this->turma->getCalendario()->getCodigo()))
            ->setTurno(Turno::find($this->turma->getTurno()->getCodigoTurno()));
    }
}
