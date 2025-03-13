<?php


namespace ECidade\Educacao\Escola\Repository;

use Exception;

/**
 * Class TurmasRegentesEscolaRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class TurmasRegentesEscolaRepository extends Repository
{

    public function get($codigoCgm, $codigoEscola, $dataLogin, $campos = [], $orderBy = [])
    {
        $campos = implode(', ', $campos);
        $orderBy = implode(', ', $orderBy);
        $sql = "
        with profissional as (
            select distinct ed20_i_codigo
                 from rechumano
                 join rechumanoescola on rechumanoescola.ed75_i_rechumano = rechumano.ed20_i_codigo
                                     and rechumanoescola.ed75_i_saidaescola is null
                left join rechumanocgm on rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo
                left join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = ed20_i_codigo
                left join rhpessoal on rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal
                where (rhpessoal.rh01_numcgm = {$codigoCgm} or rechumanocgm.ed285_i_cgm = {$codigoCgm})
                  and rechumanoescola.ed75_i_escola = {$codigoEscola}
        ), regencias_normal as (
            select distinct regenciahorario.ed58_i_regencia as codigo
              from profissional
              join regenciahorario on regenciahorario.ed58_i_rechumano = profissional.ed20_i_codigo
              join regencia on regencia.ed59_i_codigo = regenciahorario.ed58_i_regencia
             where ed58_ativo is true
        ), regencias_substituta as  (
            select distinct docentesubstituto.ed322_regencia  as codigo
              from profissional
             join docentesubstituto on docentesubstituto.ed322_rechumano = profissional.ed20_i_codigo
              join regenciahorario on regenciahorario.ed58_i_regencia = docentesubstituto.ed322_regencia
              join regencia on regencia.ed59_i_codigo = regenciahorario.ed58_i_regencia
              join turma on turma.ed57_i_codigo = regencia.ed59_i_turma
              join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario
             where ed58_ativo is true
               and ed59_c_freqglob <> 'A'
               and '{$dataLogin}' >= ed322_periodoinicial
               and (    (ed322_periodofinal is null and '{$dataLogin}' <= ed52_d_fim)
                     or '{$dataLogin}' <= ed322_periodofinal
                   )
        ), regencias as (
            select * from regencias_normal
            union all
            select * from regencias_substituta
        ) select {$campos}
         from regencias
         join regencia on regencia.ed59_i_codigo = regencias.codigo
         join serie on  serie.ed11_i_codigo = regencia.ed59_i_serie
         join turma on turma.ed57_i_codigo = regencia.ed59_i_turma
         join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario
         join disciplina on disciplina.ed12_i_codigo = regencia.ed59_i_disciplina
         join caddisciplina on caddisciplina.ed232_i_codigo = disciplina.ed12_i_caddisciplina
        where ed57_i_escola = {$codigoEscola}
          and ed52_i_ano = extract(year from '{$dataLogin}'::date)
          and extract (month FROM CURRENT_DATE)
            between extract(month from ed52_d_inicio) AND extract(month from ed52_d_fim)
        ";
        if (!empty($orderBy)) {
            $sql .= "order by {$orderBy}";
        }

        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar turmas do regente");
        }

        $dados = [];
        while ($state = pg_fetch_array($rs)) {
            $dados[] = $state;
        }

        return $dados;
    }
}
