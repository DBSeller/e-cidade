<?php

namespace ECidade\Saude\Laboratorio\Helper;

class ControleFisicoFinanceiroHelper
{
    const CONTROLE_NAO_INFORMADO = 0;
    const CONTROLE_DEPARTAMENTO = 1;
    const CONTROLE_LABORATORIO = 2;
    const CONTROLE_GRUPO_EXAME = 3;
    const CONTROLE_EXAME = 4;

    /**
     * Foi alterado o SQL para permitir a alteração do tipo de controle quando não existir algum controle em andamento.
     * O SQL abaixo irá retornar o último controle informado, onde a data de fim não tiversido informada ou,
     * ela for maior ou igual a data atual.
     * Caso não encontre nenhum registro, o sistema irá permitir o cadastro de um novo tipo de controle,
     * diferente do que era feito anteriormente.
     * Alteração realizada no redmine M21467 - Laboratório: alteração para Cotas por Unidade (Departamento).
     * @return integer
     */
    public static function getTipoControleAtual()
    {
        $dao = new \cl_lab_controlefisicofinanceiro();

        $campos = 'la56_i_tipocontrole, la56_d_fim';
        $sql = $dao->sql_query_file(null, $campos, 'la56_d_fim desc limit 1');
        $sql = "SELECT la56_i_tipocontrole FROM ({$sql}) AS x WHERE (la56_d_fim is null or la56_d_fim >= now())";
        $rs = $dao->sql_record($sql);
        if (!$rs || $dao->numrows == 0) {
            return self::CONTROLE_NAO_INFORMADO;
        }

        $tipo = \db_utils::fieldsmemory($rs, 0)->la56_i_tipocontrole;

        if ($tipo > 0 && $tipo < 4 || $tipo == 9) {
            return self::CONTROLE_DEPARTAMENTO;
        } elseif ($tipo > 3 && $tipo < 7) {
            return self::CONTROLE_LABORATORIO;
        } elseif ($tipo == 7) {
            return self::CONTROLE_GRUPO_EXAME;
        } elseif ($tipo == 8) {
            return self::CONTROLE_EXAME;
        }

        return self::CONTROLE_NAO_INFORMADO;
    }
}
