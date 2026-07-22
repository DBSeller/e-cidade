<?php

namespace ECidade\Educacao\Escola\Censo\Validacao;

use DadosCensoDocente2016;
use DBString;
use ExportacaoCensoBase;
use IExportacaoCenso;

class DadosDocente2018 extends DadosCensoDocente2016
{
    protected static function validarRegistro40Coluna5Regra1($dadosDocente, $registro30, $registro40, IExportacaoCenso $exportacao)
    {
        if ($registro30->nacionalidade_docente != 3 && empty($registro40->numero_cpf)) {
            $mensagem = "Docente CGM {$dadosDocente}: \n";
            $mensagem .= "O campo \"Número do CPF\" deve ser preenchido quando o campo \"Nacionalidade do Profissional escolar em sala de Aula\"";
            $mensagem .= " for igual a 1 (Brasileira) ou 2 (Brasileira nascido no Exterior ou Naturalizado).";

            $exportacao->logErro($mensagem, ExportacaoCensoBase::LOG_DOCENTE);

            return false;
        }

        return true;
    }

    protected static function validarRegistro30Coluna12($dadosDocente, $exportacao, $registro30, $registro40)
    {
        $valido = true;

        if (!static::validarRegistro30Coluna12Regra1($dadosDocente, $exportacao, $registro30)) {
            $valido = false;
        }

        if (!static::validarRegistro30Coluna12Regra2($dadosDocente, $exportacao, $registro30)) {
            $valido = false;
        }

        if (!static::validarRegistro30Coluna12Regra3($dadosDocente, $exportacao, $registro30)) {
            $valido = false;
        }

        if (!static::validarRegistro30Coluna12Regra4($dadosDocente, $exportacao, $registro30, $registro40)) {
            $valido = false;
        }

        if (!static::validarRegistro30Coluna12Regra5($dadosDocente, $exportacao, $registro30, $registro40)) {
            $valido = false;
        }

        return $valido;
    }

    protected static function validarRegistro30Coluna13($dadosDocente, $exportacao, $registro30, $registro40)
    {
        $valido = true;

        if (!static::validarRegistro30Coluna13Regra1($dadosDocente, $exportacao, $registro30)) {
            $valido = false;
        }

        if (!static::validarRegistro30Coluna13Regra2($dadosDocente, $exportacao, $registro30)) {
            $valido = false;
        }

        if (!static::validarRegistro30Coluna13Regra3($dadosDocente, $exportacao, $registro30)) {
            $valido = false;
        }

        if (!static::validarRegistro30Coluna13Regra4($dadosDocente, $exportacao, $registro30, $registro40)) {
            $valido = false;
        }

        if (!static::validarRegistro30Coluna13Regra5($dadosDocente, $exportacao, $registro30, $registro40)) {
            $valido = false;
        }

        if (!static::validarRegistro30Coluna13Regra6($dadosDocente, $exportacao, $registro30)) {
            $valido = false;
        }

        return $valido;
    }

    protected static function validarRegistro30Coluna12Regra1($dadosDocente, IExportacaoCenso $exportacao, $registro30)
    {
        if ($registro30->filiacao == 0 && !empty($registro30->filiacao_1)) {
            $sMsgErro = "Docente CGM {$dadosDocente}:\n";
            $sMsgErro .= "O campo \"Filiação 1\" não pode ser preenchido quando o campo 11 (Filiação) for igual a 0 (Não declarado/Ignorado).";
            $exportacao->logErro($sMsgErro, ExportacaoCensoBase::LOG_DOCENTE);

            return false;
        }

        return true;
    }

    protected static function validarRegistro30Coluna12Regra2($dadosDocente, IExportacaoCenso $exportacao, $registro30)
    {
        if (strlen(trim($registro30->filiacao_1)) > 100) {
            $mensagem = "Docente CGM {$dadosDocente}: \n";
            $mensagem .= "O campo \"Filiação 1\" está maior que o especificado (100 caracteres).";

            $exportacao->logErro($mensagem, ExportacaoCensoBase::LOG_DOCENTE);

            return false;
        }

        return true;
    }

    protected static function validarRegistro30Coluna12Regra3($dadosDocente, IExportacaoCenso $exportacao, $registro30)
    {
        if (!DBString::isNomeValido(trim($registro30->filiacao_1), DBString::NOME_REGRA_5)) {
            $mensagem = "Docente CGM {$dadosDocente}: \n";
            $mensagem .= "O campo \"Filiação 1\" foi preenchido com caracteres inválidos.";

            $exportacao->logErro($mensagem, ExportacaoCensoBase::LOG_DOCENTE);

            return false;
        }

        return true;
    }

    protected static function validarRegistro30Coluna12Regra4($dadosDocente, IExportacaoCenso $exportacao, $registro30, $registro40)
    {
        $filiacao2 = trim($registro30->filiacao_2);

        if (empty($filiacao2) && !DBString::isNomeValido(trim($registro30->filiacao_1), DBString::NOME_REGRA_3)) {
            $mensagem = "Docente CGM {$dadosDocente}: \n";
            $mensagem .= "O campo \"Filiação 1\" é obrigatório caso o campo \"Filiação 2\" não estiver preenchido e deve conter duas palavras ou mais.";

            $exportacao->logErro($mensagem, ExportacaoCensoBase::LOG_DOCENTE);

            return false;
        }

        return true;
    }

    protected static function validarRegistro30Coluna12Regra5($dadosDocente, IExportacaoCenso $exportacao, $registro30, $registro40)
    {
        if (!DBString::isNomeValido(trim($registro30->filiacao_1), DBString::NOME_REGRA_4)) {
            $mensagem = "Docente CGM {$dadosDocente}: \n";
            $mensagem .= "O campo \"Filiação 1\" foi preenchido com valor contendo 4 letras iguais em sequência.";

            $exportacao->logErro($mensagem, ExportacaoCensoBase::LOG_DOCENTE);

            return false;
        }

        return true;
    }

    protected static function validarRegistro30Coluna13Regra2($dadosDocente, IExportacaoCenso $exportacao, $registro30)
    {
        if (strlen(trim($registro30->filiacao_2)) > 100) {
            $mensagem = "Docente CGM {$dadosDocente}: \n";
            $mensagem .= "O campo \"Filiação 2\" está maior que o especificado (100 caracteres).";

            $exportacao->logErro($mensagem, ExportacaoCensoBase::LOG_DOCENTE);

            return false;
        }

        return true;
    }

    protected static function validarRegistro30Coluna13Regra4($dadosDocente, IExportacaoCenso $exportacao, $registro30, $registro40)
    {
        $filiacao1 = trim($registro30->filiacao_1);

        if (empty($filiacao1) && !DBString::isNomeValido(trim($registro30->filiacao_2), DBString::NOME_REGRA_3)) {
            $mensagem = "Docente CGM {$dadosDocente}: \n";
            $mensagem .= "O campo \"Filiação 2\" é obrigatório caso o campo \"Filiação 1\" não estiver preenchido e deve conter duas palavras ou mais.";

            $exportacao->logErro($mensagem, ExportacaoCensoBase::LOG_DOCENTE);

            return false;
        }

        return true;
    }

    protected static function validarRegistro30Coluna13Regra5($dadosDocente, IExportacaoCenso $exportacao, $registro30, $registro40)
    {
        if (!DBString::isNomeValido(trim($registro30->filiacao_2), DBString::NOME_REGRA_4)) {
            $mensagem = "Docente CGM {$dadosDocente}: \n";
            $mensagem .= "O campo \"Filiação 2\" foi preenchido com valor contendo 4 letras iguais em sequência.";

            $exportacao->logErro($mensagem, ExportacaoCensoBase::LOG_DOCENTE);

            return false;
        }

        return true;
    }

    protected static function validarRegistro30Coluna13Regra6($dadosDocente, IExportacaoCenso $exportacao, $registro30)
    {
        $filiacao1 = trim($registro30->filiacao_1);
        $filiacao2 = trim($registro30->filiacao_2);

        if (!empty($filiacao1) && !empty($filiacao2) && $filiacao1 == $filiacao2) {
            $mensagem = "Docente CGM {$dadosDocente}: \n";
            $mensagem .= "O campo \"Filiação 2\" não pode ser igual ao campo \"Filiação 1\".";

            $exportacao->logErro($mensagem, ExportacaoCensoBase::LOG_DOCENTE);

            return false;
        }

        return true;
    }
}
