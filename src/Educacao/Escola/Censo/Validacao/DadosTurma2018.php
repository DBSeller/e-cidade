<?php

namespace ECidade\Educacao\Escola\Censo\Validacao;

use DadosCensoTurma2015;
use ExportacaoCensoBase;
use IExportacaoCenso;

class DadosTurma2018 extends DadosCensoTurma2015
{
    protected static function validarRegistro20Coluna20a25Regra1($mensagem, $dadosEscola, $dadosTurma, IExportacaoCenso $exportacao)
    {
        if ($dadosEscola->registro10->atividade_complementar == 0 && $dadosTurma->tipo_atendimento == 4) {
            $mensagemErro = "{$mensagem} O campo 'Tipo de Atendimento' foi preenchido com 4 (Atividade complementar), porém a turma não informou tal atividade.";

            $exportacao->logErro($mensagemErro, ExportacaoCensoBase::LOG_TURMA);

            return false;
        }

        return true;
    }
}
