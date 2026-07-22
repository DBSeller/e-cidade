<?php

use Classes\PostgresMigration;

class M17166RemoverResultadoFinalDiarioAluno extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<sql
        create temporary table w_diarios_errados as (
            select ed161_codigo,
                   ed165_codigo,
                   --ed161_aluno,
                   --ed60_i_turma,
                   --ed60_i_codigo,
                   --ed60_matricula,
                   --ed60_c_concluida,
                   --ed60_c_ativa,
                   --ed161_turma,
                   ed161_encerrado,
                   ed165_resultado_final
            from diarioaluno
                     join matricula on ed60_i_aluno = ed161_aluno
                     join diarioalunoresultadofinal on ed165_diarioaluno = ed161_codigo
                     join turma on ed161_turma = ed57_i_codigo
                     join calendario on ed52_i_codigo = ed57_i_calendario
                     join matriculaserie on ed221_i_matricula = ed60_i_codigo
            where ed60_c_concluida = 'N'
              and ed60_c_ativa = 'S'
              and ed161_encerrado = 't'
              and ed60_c_situacao = 'MATRICULADO'
              and ed161_turma = ed60_i_turma
              and ed161_serie = ed221_i_serie
              and ed221_c_origem = 'S'
              and calendario.ed52_i_ano = 2020
        );
        update diarioaluno set ed161_encerrado = 'f'
            from w_diarios_errados
                where diarioaluno.ed161_codigo = w_diarios_errados.ed161_codigo;
        update diarioalunoresultadofinal set ed165_resultado_final = null
            from w_diarios_errados
                where diarioalunoresultadofinal.ed165_codigo = w_diarios_errados.ed165_codigo;
sql
        );
    }

    public function down()
    {
        return;
    }
}
