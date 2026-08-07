<?php
namespace ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Importar;

use Aluno;
use ECidade\Educacao\Escola\Censo\Censo;
use ECidade\Educacao\Escola\Censo\Helpers\Pessoa;
use ECidade\Educacao\Escola\Censo\Helpers\Turma;
use ECidade\Educacao\Escola\Censo\Log\LogImportarCodigoInep;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Builder\Registro20Builder;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Builder\Registro30Builder;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Builder\Registro60Builder;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\Registro20;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\Registro30;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\Registro60;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\ImportarInterface;
use ECidade\Educacao\Escola\Censo\Registry\LogCensoRegistry;
use ECidade\Educacao\Escola\Registry\AlunoRegistry;
use ECidade\File\Csv\Dumper\Dumper;
use Escola;
use Exception;
use stdClass;

/**
 * Class Importar
 * @package ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Importar
 */
class Importar implements ImportarInterface
{
    const POSICAO_TIPO_REGISTRO = 0;
    const POSICAO_CODIGO_INEP_ESCOLA = 1;

    const REGISTRO_DADOS_TURMA = 20;
    const REGISTRO_DADOS_ALUNOS = 30;
    const REGISTRO_DADOS_INEP = 60;

    /**
     * @var Censo
     */
    private $censo;

    /**
     * Separa o arquivo por escola.
     * @var array
     */
    private $arquivoPorEscola = [];
    /**
     * @var Escola[]
     */
    private $escolas;

    /**
     * @inheritDoc
     */
    public function setCenso(Censo $censo)
    {
        $this->censo = $censo;
    }


    /**
     * @param stdClass $file
     * @return bool
     * @throws Exception
     */
    public function importarINEP(stdClass $file)
    {
        LogCensoRegistry::set(LogCensoRegistry::MATRICULA_INICIAL, new LogImportarCodigoInep());
        $this->validar($file);
        $this->lerArquivo($file);

        if (empty($this->arquivoPorEscola)) {
            throw new Exception("Não existe nenhum registro para atualizar.");
        }

        foreach ($this->arquivoPorEscola as $inepEscola => $registros) {
            foreach ($registros[self::REGISTRO_DADOS_INEP] as $registro) {
                if ($registro->getTipoRegistro() == 60) {
                    $this->atualizarCodigoINEP($registro);
                }
            }
        }

        return true;
    }

    /**
     * @param stdClass $file
     * @throws Exception
     */
    private function validar(stdClass $file)
    {
        if ($file->extension !== 'txt') {
            throw new Exception('Arquivo inválido, extensão do arquivo não é "txt".');
        }
    }

    /**
     * Lê o arquivo do censo e separa por escola.
     * @param stdClass $file
     * @throws Exception
     */
    private function lerArquivo(stdClass $file)
    {
        $dumperCsv = new Dumper();
        $dumperCsv->setCsvControl('|');
        $linhasArquivo = $dumperCsv->ler($file->path);

        $inepEscolasSelecionadas = [];
        if (!empty($this->escolas)) {
            $inepEscolasSelecionadas = $this->getInepEscolasSelecionadas();
        }

        /**
         * @todo No momento só preciso do registro 60, portanto não vou criar todo o arquivo
         * @todo Ao implementar a atualização dos dados através do arquivo de Matricula Incial, implementar os demais
         */
        foreach ($linhasArquivo as $linha) {
            if (!empty($this->escolas) &&
                !in_array($linha[self::POSICAO_CODIGO_INEP_ESCOLA], $inepEscolasSelecionadas)) {
                continue;
            }

            switch ($linha[self::POSICAO_TIPO_REGISTRO]) {
                case 20:
                    $builder = new Registro20Builder();
                    break;
                case 30:
                    $builder = new Registro30Builder();
                    break;
                case 60:
                    $builder = new Registro60Builder();
                    break;
                default:
                    continue 2; // pula o foreach
            }

            $registro = $builder->buildFromFileLine($linha);
            $tipoRegistro = $registro->getTipoRegistro();
            $this->arquivoPorEscola[$linha[self::POSICAO_CODIGO_INEP_ESCOLA]][$tipoRegistro][] = $registro;
        }
    }

    /**
     * @param Registro60 $registro
     * @throws Exception
     */
    private function atualizarCodigoINEP(Registro60 $registro)
    {
        if (!$this->validarCodigoINEP($registro)) {
            return;
        }

        $codigoTurma = $registro->getCodigoTurma();
        $codigoAluno = $registro->getCodigoPessoa();

        $this->atualizaInepAluno($registro, $codigoAluno);

        $this->atualizaInepTurma($registro, $codigoTurma);
    }

    /**
     * @param Registro60 $registro
     * @return bool
     * @throws Exception
     */
    private function validarCodigoINEP(Registro60 $registro)
    {
        $log = LogCensoRegistry::get(LogCensoRegistry::MATRICULA_INICIAL);
        $codigoAluno = $registro->getCodigoPessoa();
        $codigoTurma = $registro->getCodigoTurma();

        if (Turma::isTurmaAC($codigoTurma)) {
            return false;
        }
        $msgEscola = sprintf('INEP da Escola: %s', $registro->getCodigoInepEscola());
        if (empty($codigoAluno)) {
            $mensagem =sprintf(
                '%s - "Código do Aluno" não encontrado',
                $msgEscola
            );
            $log->add(LogImportarCodigoInep::ERRO, $mensagem);
            return false;
        }

        $codigoAluno = Pessoa::decodeCodigoAluno($codigoAluno);
        $aluno = AlunoRegistry::get($codigoAluno);

        if (is_null($aluno)) {
            $registro30 = $this->getDadosAluno($registro);
            $mensagem =sprintf(
                '%s - Aluno não foi encontrado no sistema. Aluno %s - %s',
                $msgEscola,
                $codigoAluno,
                $registro30->getNome()
            );
            $log->add(LogImportarCodigoInep::ERRO, $mensagem);
            return false;
        }
        $msgAluno = sprintf('Aluno: %s - %s', $codigoAluno, $aluno->getNome());
        $msgInepAluno = sprintf('INEP do Aluno: %s', $registro->getCodigoInep());
        $msgMatriculaAluno = sprintf('Matrícula INEP: %s', $registro->getCodigoInep());
        $registro20 = $this->getDadosTurma($registro);
        if (empty($codigoTurma)) {
            $mensagem = sprintf(
                '%s - Turma não encontrada no sistema. INEP da Turma: %s - %s, %, %s, %s',
                $msgEscola,
                $registro->getCodigoTurmaInep(),
                $registro20->getNomeTurma(),
                $msgAluno,
                $msgInepAluno,
                $msgMatriculaAluno
            );

            $log->add(LogImportarCodigoInep::ERRO, $mensagem);
            return false;
        }

        $turmas = $this->buscarCodigoTurmaSistema($codigoTurma);
        foreach ($turmas as $codigo) {
            $turma = \TurmaRepository::getTurmaByCodigo($codigo);

            if (is_null($turma->getCodigo())) {
                $mensagem = sprintf(
                    '%s - Turma não encontrada. Turma: %s - %s INEP da Turma: %s - %s, %s, %s',
                    $msgEscola,
                    $codigo,
                    $registro20->getNomeTurma(),
                    $registro->getCodigoTurmaInep(),
                    $msgAluno,
                    $msgInepAluno,
                    $msgMatriculaAluno
                );

                $log->add(LogImportarCodigoInep::ERRO, $mensagem);

                return false;
            }
        }

        $registroValido = true;

        $msgErro = sprintf(
            '%s - %s',
            $msgEscola,
            $msgAluno
        );
        $complemento = "não possui valor ou o valor informado está inválido.";
        $codigoTurmaInep = $registro->getCodigoTurmaInep();
        if (empty($codigoTurmaInep) || strlen($codigoTurmaInep) > 10) {
            $mensagem = sprintf('%s campo "Código da turma - INEP %s', $msgErro, $complemento);
            $log->add(LogImportarCodigoInep::ERRO, $mensagem);
            $registroValido = false;
        }
        $codigoInep = $registro->getCodigoInep();
        if (empty($codigoInep) || strlen($codigoInep) != 12) {
            $mensagem = sprintf(
                '%s campo "Código de identificação única do aluno - INEP %s',
                $msgErro,
                $complemento
            );
            $log->add(LogImportarCodigoInep::ERRO, $mensagem);
            $registroValido = false;
        }

        $codigoMatricula = $registro->getCodigoMatricula();
        if (empty($codigoMatricula) || strlen($codigoMatricula) > 12) {
            $mensagem = sprintf('%s campo "Código da Matrícula - INEP %s', $msgErro, $complemento);
            $log->add(LogImportarCodigoInep::ERRO, $mensagem);
            $registroValido = false;
        }

        return $registroValido;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getLogImportacao()
    {
        $log = LogCensoRegistry::get(LogCensoRegistry::MATRICULA_INICIAL);
        return $log->write();
    }

    public function addEscola(Escola $escola)
    {
        $this->escolas[] = $escola;
    }

    /**
     * retorna o código inep das escolas selecionadas
     * @return array
     */
    private function getInepEscolasSelecionadas()
    {
        $ineps = [];
        foreach ($this->escolas as $escola) {
            $ineps[] = (int) $escola->getCodigoInep();
        }

        return $ineps;
    }

    /**
     * @param Registro60 $registro
     * @param $codigoAluno
     * @throws Exception
     */
    private function atualizaInepAluno(Registro60 $registro, $codigoAluno)
    {
        $codigoAluno = Pessoa::decodeCodigoAluno($codigoAluno);
        $aluno = new Aluno($codigoAluno);
        $aluno->setCodigoInep($registro->getCodigoInep());
        $aluno->salvar();

        $oAlunoMatriculaCenso = new \AlunoMatriculaCenso($aluno, $this->censo->getAno());
        $oAlunoMatriculaCenso->setTurmaCenso($registro->getCodigoTurmaInep());
        $oAlunoMatriculaCenso->setMatriculaCenso($registro->getCodigoMatricula());
        $oAlunoMatriculaCenso->salvar();

        $log = LogCensoRegistry::get(LogCensoRegistry::MATRICULA_INICIAL);
        $mensagem = "Aluno {$aluno->getCodigoAluno()} - {$aluno->getNome()} importado com sucesso.";
        $log->add(LogImportarCodigoInep::IMPORTADO, $mensagem);
    }

    /**
     * @param Registro60 $registro
     * @param $codigoTurma
     * @return bool
     * @throws Exception
     */
    private function atualizaInepTurma(Registro60 $registro, $codigoTurma)
    {
        if (Turma::isTurmaAC($codigoTurma)) {
            return true;
        }

        $turmas = $this->buscarCodigoTurmaSistema($codigoTurma);

        foreach ($turmas as $codigo) {
            $oTurma = \TurmaRepository::getTurmaByCodigo($codigo);
            $oTurma->setCodigoInep($registro->getCodigoTurmaInep());
            $oTurma->salvar();
        }

        return true;
    }

    private function buscarCodigoTurmaSistema($codigoTurma)
    {
        $turmas = [];
        if (Turma::isTurmaUnificada($codigoTurma)) {
            $codigo = Turma::decodeCodigoTurma($codigoTurma);

            $turmaCenso = new \TurmaCenso($codigo);
            $turmasCensoTurma = $turmaCenso->getTurmaCensoTurma();
            foreach ($turmasCensoTurma as $turmaCensoTurma) {
                $turmas[] = $turmaCensoTurma->getTurma()->getCodigo();
            }
        } else {
            $turmas[] = Turma::decodeCodigoTurma($codigoTurma);
        }

        return $turmas;
    }

    /**
     * @param Registro60 $registro
     * @return bool|Registro30
     */
    private function getDadosAluno(Registro60 $registro)
    {
        $registros30 = $this->arquivoPorEscola[$registro->getCodigoInepEscola()][self::REGISTRO_DADOS_ALUNOS];
        foreach ($registros30 as $registro30) {
            /**
             * @var Registro30 $registro30
             */
            if ($registro30->getCodigoPessoa() == $registro->getCodigoPessoa()) {
                return $registro30;
            }
        }

        return false;
    }

    /**
     * @param Registro60 $registro
     * @return bool|Registro20
     */
    private function getDadosTurma(Registro60 $registro)
    {
        $registros20 = $this->arquivoPorEscola[$registro->getCodigoInepEscola()][self::REGISTRO_DADOS_TURMA];
        foreach ($registros20 as $registro20) {
            /**
             * @var Registro20 $registro20
             */
            if ($registro20->getCodigoInep() == $registro->getCodigoTurmaInep()) {
                return $registro20;
            }
        }

        return false;
    }
}
