<?php

use Classes\PostgresMigration;

class M15204TabelasEcidade extends PostgresMigration
{

    public function up()
    {
        $this->areaConhecimento();
        $this->disciplinas();
    }

    public function down()
    {
        $this->execute('delete from escola.areaconhecimento where ed293_sequencial between 1000 and 1999;');

        $this->execute("
            create temp table w_deletar_disciplina as
            select ed232_i_codigo
              from escola.caddisciplina
              join escola.bnccdisciplinas on trim(ed232_c_descr) = trim(ed149_nome)
                                         and trim(ed232_c_abrev) = trim(ed149_sigla);

            delete from escola.disciplina
            using w_deletar_disciplina
            where ed12_i_caddisciplina = ed232_i_codigo ;

            delete from escola.censocaddisciplina
            using w_deletar_disciplina
            where ed294_caddisciplina = ed232_i_codigo ;

            delete from caddisciplina
            using w_deletar_disciplina
            where caddisciplina.ed232_i_codigo = w_deletar_disciplina.ed232_i_codigo ;
        ");
    }

    private function areaConhecimento()
    {
        $this->execute("
            insert into escola.areaconhecimento
            values (1000,'Linguagens', 1),
                   (1001,'Matemática', 1),
                   (1002,'Ciências da Natureza', 1),
                   (1003,'Ciências Humanas', 1),
                   (1004,'Ensino Religioso', 1);

            select setval('areaconhecimento_ed293_sequencial_seq', 2000);
        ");
    }

    private function disciplinas()
    {
        $this->execute("

            create temp table w_nova_disciplina as
            select nextval('caddisciplina_ed232_i_codigo_seq') as id,
                           ed149_nome as a,
                           ed149_sigla,
                           null::integer,
                           ed149_nome as b
                      from escola.bnccdisciplinas
                     where ed149_ensino = 'EI';

            insert into escola.caddisciplina select * from w_nova_disciplina;

            insert into escola.disciplina
            select nextval('disciplina_ed12_i_codigo_seq'), ensino.ed10_i_codigo, id
              from escola.ensino, w_nova_disciplina
              where ed10_tipo = 1;

              insert into censocaddisciplina
              select nextval('censocaddisciplina_ed294_sequencial_seq'), 99, id
                from w_nova_disciplina;
        ");
    }
}
