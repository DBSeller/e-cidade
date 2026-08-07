<?php

namespace ECidade\Educacao\Escola\Censo\Validacao;

use DadosCensoAluno2016;
use DBString;
use ECidade\Educacao\Escola\Censo\Exportacao\ExportacaoCenso2018;
use Exception;
use ExportacaoCensoBase;
use IExportacaoCenso;

/**
 * Class DadosAluno2018
 * @package ECidade\Educacao\Escola\Censo\Validacao
 */
class DadosAluno2018 extends DadosCensoAluno2016
{
    /**
     * @param IExportacaoCenso $exportacao
     * @return bool
     */
    public static function validarDados(IExportacaoCenso $exportacao)
    {
        $validou = true;

        foreach ($exportacao->getDadosProcessadosAluno() as $dadosAluno) {
            if (!static::validaRegistro60($exportacao, $dadosAluno)) {
                $validou = false;
            }

            if (!static::validaRegistro70($exportacao, $dadosAluno)) {
                $validou = false;
            }

            if (!static::validaRegistro80($exportacao, $dadosAluno)) {
                $validou = false;
            }

            if (!static::validarRegistro70Coluna18Regra10($exportacao, $dadosAluno)) {
                $validou = false;
            }

            if (!static::validarRegistro70Coluna26Regra3($exportacao, $dadosAluno)) {
                $validou = false;
            }
        }

        return $validou;
    }

    /**
     * @param IExportacaoCenso $exportacao
     * @param $dadosAluno
     * @return bool
     */
    protected static function validarRegistro70Coluna18Regra10(IExportacaoCenso $exportacao, $dadosAluno)
    {
        if ($dadosAluno->registro70->tipo_certidao_civil == 2) {
            $mensagem = "Aluno(a) {$dadosAluno->registro60->codigo_aluno_entidade_escola} - {$dadosAluno->registro60->nome_completo}: \n";
            $mensagem .= "A certidão informada não pode ser de casamento.";

            $exportacao->logErro($mensagem, ExportacaoCensoBase::LOG_ALUNO);

            return false;
        }

        return true;
    }

    /**
     * @param $exportacao
     * @param $dadosAluno
     * @return bool
     */
    protected static function validarRegistro70Coluna26Regra3($exportacao, $dadosAluno)
    {
        if (!empty($dadosAluno->registro70->complemento) && preg_match("/^(?:(?!\s|[a-zA-Z]|[0-9]|\ª|\º|\-|\/|\.|\,).)*$/", $dadosAluno->registro70->complemento)) {
            $mensagem = "Aluno(a) {$dadosAluno->registro60->codigo_aluno_entidade_escola} - {$dadosAluno->registro60->nome_completo}: \n";
            $mensagem .= "O campo \"Complemento\" foi preenchido com valor inválido.";

            $exportacao->logErro($mensagem, ExportacaoCensoBase::LOG_ALUNO);

            return false;
        }

        return true;
    }

    /**
     * @param $aluno
     * @param $registro60
     * @param $exportacao
     * @return bool
     * @throws Exception
     */
    protected static function validarRegistro60Coluna10Regra4($aluno, $registro60, $exportacao)
    {
        if (!empty($registro60->filiacao_1) && !DBString::isNomeValido($registro60->filiacao_1, DBString::NOME_REGRA_3)) {
            $mensagem = "Aluno(a) {$aluno}:\n";
            $mensagem .= " \"Filiação 1\" ({$registro60->filiacao_1}) Deve ter mais de uma palavra quando o campo 19 do registro 70 não for preenchido com um CPF regular ou pendente de regularização.";

            $exportacao->logErro($mensagem, ExportacaoCenso2018::LOG_ALUNO);

            return false;
        }

        return true;
    }

    /**
     * @param $aluno
     * @param $registro60
     * @param $exportacao
     * @return bool
     * @throws Exception
     */
    protected static function validarRegistro60Coluna11Regra4($aluno, $registro60, $exportacao)
    {
        if (!empty($registro60->filiacao_2) && !DBString::isNomeValido($registro60->filiacao_2, DBString::NOME_REGRA_3)) {
            $mensagem = "Aluno(a) {$aluno}:\n";
            $mensagem .= " \"Filiação 2\" ({$registro60->filiacao_2}) Deve ter mais de uma palavra quando o campo 19 do registro 70 não for preenchido com um CPF regular ou pendente de regularização.";

            $exportacao->logErro($mensagem, ExportacaoCenso2018::LOG_ALUNO);

            return false;
        }

        return true;
    }

    public static function validaRegistro80($exportacaoCenso, $dadosAluno)
    {
        $validou = parent::validaRegistro80($exportacaoCenso, $dadosAluno);

        foreach ($dadosAluno->registro80 as $matricula) {
            
            $turma = static::getTurmaAluno($exportacaoCenso, $matricula->codigo_turma_entidade_escola);

            if ($turma->tipo_atendimento == 4 || $turma->tipo_atendimento == 5) {
                $matricula->recebe_escolarizacao_outro_espaco = null;
            }
        }

        return $validou;
    }
}
