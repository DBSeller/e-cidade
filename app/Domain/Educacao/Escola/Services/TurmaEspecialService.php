<?php

namespace App\Domain\Educacao\Escola\Services;

use stdClass;
use cl_turmaachorarioprofissional;

/**
 * Class TurmaEspecialService
 * @package App\Domain\Educacao\Escola\Services
 */
class TurmaEspecialService
{

    public function getHorarios($turmaAc)
    {
        $dados = [];
        $sCampos = " distinct";
        $sCampos .= " ed346_sequencial,";
        $sCampos .= " ed20_i_codigo,";
        $sCampos .= " case";
        $sCampos .= "    when ed20_i_tiposervidor = 1";
        $sCampos .= "      then trim(cgmrh.z01_nome)";
        $sCampos .= "    else trim(cgmcgm.z01_nome)";
        $sCampos .= " end as profissional,";
        $sCampos .= " ed119_sequencial,";
        $sCampos .= " ed119_descricao as atividade,";
        $sCampos .= " ed32_i_codigo,";
        $sCampos .= " ed32_c_descr as dia,";
        $sCampos .= " ed346_horainicial as inicio,";
        $sCampos .= " ed346_horafinal as fim";

        $sWhere = " ed346_turmaac = {$turmaAc} ";
        $sWhereUnion = " ed176_turmaac = {$turmaAc} ";
        $dao = new cl_turmaachorarioprofissional();
        $sql = $dao->sql_query_vinculo_profissional_union_sem_profissional(null, $sCampos, '', $sWhere, $sWhereUnion);
        $horarios = collect(\DB::select($sql))->map(function ($horario) {
            $mapper = new stdClass();
            $mapper->dia = $horario->dia;
            $mapper->horario = $horario->inicio . ' as '. $horario->fim;
            $mapper->atividade = $horario->atividade == 'Nenhum' ? 'Sem atividade' : $horario->atividade;
            $mapper->profissional = $horario->profissional == 'Nenhum' ? 'Sem profisisonal' : $horario->profissional;

            return $mapper;
        });
        $dados['turma'] = $turmaAc;
        $dados['dados'] = $horarios->all();
        return $dados;
    }
}
