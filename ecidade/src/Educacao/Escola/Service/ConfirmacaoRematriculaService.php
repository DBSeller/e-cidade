<?php

namespace ECidade\Educacao\Escola\Service;

use Aluno;
use AlunoRepository;
use CalendarioRepository;
use ECidade\Educacao\Escola\Model\ConfirmacaoRematricula;
use ECidade\Educacao\Escola\Relatorios\ConfirmacaoRematriculaRelatorio;
use ECidade\Educacao\Escola\Repository\ConfirmacaoRematriculaRepository;
use EscolaRepository;
use Etapa;
use EtapaRepository;
use Exception;
use ParameterException;
use stdClass;
use TurmaRepository;

/**
 * Class ConfirmacaoRematriculaService
 * @package ECidade\Educacao\Escola\Service
 */
class ConfirmacaoRematriculaService
{
    /**
     * @var stdClass
     */
    private $parametros;
    /**
     * @var ConfirmacaoRematriculaRepository
     */
    private $repositorio;

    /**
     * ConfirmacaoRematriculaService constructor.
     * @param stdClass $parametros
     */
    public function __construct(stdClass $parametros)
    {
        $this->parametros = $parametros;
        $this->repositorio = new ConfirmacaoRematriculaRepository();
    }

    /**
     * @return array
     * @throws Exception
     */
    public function buscarAlunosComRematriculaNaoConfirmada()
    {
        $this->verificarSeParametroEscolaExiste();
        $this->verificarSeParametroCalendarioExiste();
        $this->verificarSeParametroTurmaExiste();

        $turma = TurmaRepository::getTurmaByCodigo($this->parametros->turma);
        $alunos = AlunoRepository::getAlunosByTurmaOrdemAlfabetica($turma);

        $this->verificarSeRetornouRegistros($alunos);

        $escola = EscolaRepository::getEscolaByCodigo($this->parametros->escola);
        $calendario = CalendarioRepository::getCalendarioByCodigo($this->parametros->calendario);

        $rematriculasConfirmadas = $this->repositorio->scopeEscola($escola)
            ->scopeCalendario($calendario)
            ->scopeTurma($turma)
            ->get();

        $codigosAlunosConfirmados = $this->obterCodigosAlunosComRematriculaConfirmada($rematriculasConfirmadas);
        $alunosNaoConfirmados = $this->filtrarAlunosComRematriculaNaoConfirmada($alunos, $codigosAlunosConfirmados);
        $alunosNaoConfirmados = $this->mapearAlunosComRematriculaNaoConfirmada($alunosNaoConfirmados);

        return array_values($alunosNaoConfirmados);
    }

    /**
     * @param array $rematriculasConfirmadas
     * @return array
     */
    private function obterCodigosAlunosComRematriculaConfirmada(array $rematriculasConfirmadas)
    {
        return array_map(function (ConfirmacaoRematricula $confirmacaoRematricula) {
            return $confirmacaoRematricula->getAluno()->getCodigoAluno();
        }, $rematriculasConfirmadas);
    }

    /**
     * @param array $alunos
     * @param array $codigosConfirmacoesRematricula
     * @return array
     */
    private function filtrarAlunosComRematriculaNaoConfirmada(array $alunos, array $codigosConfirmacoesRematricula)
    {
        return array_filter($alunos, function (Aluno $aluno) use ($codigosConfirmacoesRematricula) {
            return !in_array($aluno->getCodigoAluno(), $codigosConfirmacoesRematricula);
        });
    }

    /**
     * @param array $alunos
     * @return array
     */
    private function mapearAlunosComRematriculaNaoConfirmada(array $alunos)
    {
        return array_map(function (Aluno $aluno) {
            return array(
                'codigo' => filter_var($aluno->getCodigoAluno(), FILTER_VALIDATE_INT),
                'nome' => $aluno->getNome()
            );
        }, $alunos);
    }

    /**
     * @throws Exception
     */
    public function confirmarRematricula()
    {
        $this->verificarSeParametroEscolaExiste();
        $this->verificarSeParametroCalendarioExiste();
        $this->verificarSeParametroTurmaExiste();
        $this->verificarSeParametroAlunosExiste();

        $escola = EscolaRepository::getEscolaByCodigo($this->parametros->escola);
        $calendario = CalendarioRepository::getCalendarioByCodigo($this->parametros->calendario);
        $turma = TurmaRepository::getTurmaByCodigo($this->parametros->turma);

        foreach ($this->parametros->alunos as $aluno) {
            $aluno = AlunoRepository::getAlunoByCodigo($aluno);

            $confirmacaoRematricula = new ConfirmacaoRematricula();
            $confirmacaoRematricula->setEscola($escola);
            $confirmacaoRematricula->setCalendario($calendario);
            $confirmacaoRematricula->setTurma($turma);
            $confirmacaoRematricula->setAluno($aluno);

            $this->repositorio->save($confirmacaoRematricula);
        }
    }

    /**
     * @return string
     * @throws Exception
     */
    public function emitirRelatorio()
    {
        $this->verificarSeParametroEscolaExiste();
        $this->verificarSeParametroCalendarioExiste();

        $escola = EscolaRepository::getEscolaByCodigo($this->parametros->escola);
        $calendario = CalendarioRepository::getCalendarioByCodigo($this->parametros->calendario);
        $etapas = EtapaRepository::getByEscolaAndCalendario($escola, $calendario);

        if (count($etapas) === 0) {
            throw new Exception("Não há etapas para o calendário {$calendario->getDescricao()}.");
        }

        $primeiraEtapa = $etapas[0];

        $dados = array(
            $this->montarLinhaRelatorio(
                '-',
                '-',
                $primeiraEtapa->getNome(),
                EtapaRepository::getVagasByEtapa($primeiraEtapa, $escola, $calendario)
            )
        );

        foreach ($etapas as $chave => $etapa) {
            $confirmacoes = ConfirmacaoRematriculaRepository::countConfirmados($etapa, $escola, $calendario);
            $proximaChave = $chave + 1;
            $proximaEtapa = array_key_exists($proximaChave, $etapas) ? $etapas[$proximaChave] : null;

            $dados[] = $this->montarLinhaRelatorio(
                $etapa->getNome(),
                $proximaEtapa instanceof Etapa ? (string)$confirmacoes : '-',
                $proximaEtapa instanceof Etapa ? $proximaEtapa->getNome() : '-',
                $proximaEtapa instanceof Etapa ? EtapaRepository::getVagasByEtapa($etapa, $escola, $calendario) : '-'
            );
        }

        $relatorio = new ConfirmacaoRematriculaRelatorio();
        $relatorio->setDados($dados);
        $relatorio->adicionarDescricao("RELATÓRIO DE CONFIRMAÇÕES DE REMATRÍCULA");
        $relatorio->adicionarDescricao("ESCOLA: {$escola->getNome()}");
        $relatorio->adicionarDescricao("CALENDÁRIO: {$calendario->getDescricao()}");

        return $relatorio->imprimir();
    }

    /**
     * @param $etapaVigente
     * @param $rematriculasConfirmadas
     * @param $etapaProxima
     * @param $totalVagas
     * @return stdClass
     */
    private function montarLinhaRelatorio($etapaVigente, $rematriculasConfirmadas, $etapaProxima, $totalVagas)
    {
        $linha = new stdClass();
        $linha->etapaVigente = $etapaVigente;
        $linha->rematriculasConfirmadas = $rematriculasConfirmadas;
        $linha->etapaProxima = $etapaProxima;
        $linha->totalVagas = $totalVagas;

        return $linha;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function buscarAlunosComRematriculaConfirmada()
    {
        $this->verificarSeParametroEscolaExiste();
        $this->verificarSeParametroCalendarioExiste();
        $this->verificarSeParametroTurmaExiste();

        $turma = TurmaRepository::getTurmaByCodigo($this->parametros->turma);
        $escola = EscolaRepository::getEscolaByCodigo($this->parametros->escola);
        $calendario = CalendarioRepository::getCalendarioByCodigo($this->parametros->calendario);

        $alunosConfirmados = $this->repositorio->scopeEscola($escola)
            ->scopeCalendario($calendario)
            ->scopeTurma($turma)
            ->get();

        $this->verificarSeRetornouRegistros($alunosConfirmados);

        return $this->mapearAlunosComRematriculaConfirmada($alunosConfirmados);
    }

    /**
     * @param array $alunosConfirmados
     * @return array
     */
    private function mapearAlunosComRematriculaConfirmada(array $alunosConfirmados)
    {
        return array_map(function (ConfirmacaoRematricula $confirmacaoRematricula) {
            return array(
                'codigo' => filter_var($confirmacaoRematricula->getAluno()->getCodigoAluno(), FILTER_VALIDATE_INT),
                'nome' => $confirmacaoRematricula->getAluno()->getNome(),
                'sequencial' => $confirmacaoRematricula->getSequencial()
            );
        }, $alunosConfirmados);
    }

    /**
     * @throws Exception
     */
    public function desconfirmarRematricula()
    {
        $this->verificarSeParametroEscolaExiste();
        $this->verificarSeParametroCalendarioExiste();
        $this->verificarSeParametroTurmaExiste();
        $this->verificarSeParametroAlunosExiste();

        ConfirmacaoRematriculaRepository::destroy($this->parametros->alunos);
    }

    /**
     * @throws ParameterException
     */
    private function verificarSeParametroEscolaExiste()
    {
        if ($this->parametros->escola === null || $this->parametros->escola === false) {
            throw new ParameterException('O campo "Escola" é obrigatório.');
        }
    }

    /**
     * @throws ParameterException
     */
    private function verificarSeParametroCalendarioExiste()
    {
        if ($this->parametros->calendario === null || $this->parametros->calendario === false) {
            throw new ParameterException('O campo "Calendário" é obrigatório.');
        }
    }

    /**
     * @throws ParameterException
     */
    private function verificarSeParametroTurmaExiste()
    {
        if ($this->parametros->turma === null || $this->parametros->turma === false) {
            throw new ParameterException('O campo "Turma" é obrigatório.');
        }
    }

    /**
     * @throws ParameterException
     */
    private function verificarSeParametroAlunosExiste()
    {
        if ($this->parametros->alunos === null ||
            $this->parametros->alunos === false ||
            count($this->parametros->alunos) === 0) {
            throw new ParameterException('É necessário selecionar ao menos um aluno.');
        }
    }

    /**
     * @param array $alunos
     * @throws Exception
     */
    private function verificarSeRetornouRegistros(array $alunos)
    {
        if (count($alunos) === 0) {
            throw new Exception('Não há registros para os filtros informados.');
        }
    }
}
