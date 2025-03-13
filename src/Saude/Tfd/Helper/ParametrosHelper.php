<?php

namespace ECidade\Saude\Tfd\Helper;

class ParametrosHelper
{
    public static function get()
    {
        $parametros = (object)[
            'utilizaGradeHorario' => false,
            'campoFoco' => '',
            'especMedico' => '',
            'obrigaHoraSaida' => true
        ];

        $dao = new \cl_tfd_parametros();
        $sql = $dao->sql_query_file();
        $rs = $dao->sql_record($sql);
        if ($dao->numrows == 0) {
            return $parametros;
        }

        $config = \db_utils::fieldsmemory($rs, 0);
        $parametros->utilizaGradeHorario = $config->tf11_i_utilizagradehorario == 1;
        $parametros->campoFoco = $config->tf11_i_campofoco;
        $parametros->especialidadeMedico = $config->tf11_especmedico;
        $parametros->obrigaHoraSaida = $config->tf11_obriga_hora_saida == 't';

        return $parametros;
    }
}
