<?php

use Classes\PostgresMigration;

class M13299AlteracaoCenso2019 extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            -- Tipos de atendimento novos
            update turmaac set ed268_c_aee = ed268_c_aee || '000' where length(ed268_c_aee) = 12 AND ed268_c_aee is not null;

            -- Recursos especiais modificados
            update recursosavaliacaoinep set ed326_descricao = 'PROVA AMPLIADA (FONTE TAMANHO 18)' where ed326_sequencial = 107;
            update recursosavaliacaoinep set ed326_descricao = 'PROVA SUPERAMPLIADA (FONTE TAMANHO 24)' where ed326_sequencial = 108;

            update alunorecursosavaliacaoinep set ed327_recursosavaliacaoinep = 107 where ed327_recursosavaliacaoinep = 106;
            delete from recursosavaliacaoinep where ed326_sequencial = 106;

            -- Migrar disciplinas
            insert into censocaddisciplina (ed294_sequencial, ed294_caddisciplina, ed294_censodisciplina)
                select distinct on (a.ed294_caddisciplina) nextval('censocaddisciplina_ed294_sequencial_seq'), a.ed294_caddisciplina, 99
                from censocaddisciplina a
                where
                    a.ed294_censodisciplina in (15, 20, 21) AND
                    not exists (
                        select b.ed294_sequencial
                        from censocaddisciplina b
                        where
                            b.ed294_caddisciplina = a.ed294_caddisciplina AND
                            b.ed294_censodisciplina = 99
                    );
            delete from censocaddisciplina where ed294_censodisciplina in (15, 20, 21);

            insert into censoregradisc (ed272_i_codigo, ed272_i_censoetapa, ed272_i_censodisciplina, ed272_ano)
                select distinct on (a.ed272_i_censoetapa, a.ed272_ano) nextval('censoregradisc_ed272_i_codigo_seq'), a.ed272_i_censoetapa, 99, a.ed272_ano
                from censoregradisc a
                where
                    a.ed272_i_censodisciplina in (15, 20, 21)
                    AND
                    -- Não pode haver um vínculo com a censodisciplina 99
                    not exists (
                        select b.ed272_i_codigo
                        from censoregradisc b
                        where
                            a.ed272_ano = b.ed272_ano AND
                            a.ed272_i_censoetapa = b.ed272_i_censoetapa AND
                            b.ed272_i_censodisciplina = 99
                    );
            delete from censoregradisc where ed272_i_censodisciplina in (15, 20, 21);

            insert into formacaocensodisciplina (ed145_sequencial, ed145_formacao, ed145_censodisciplina)
                select distinct on (a.ed145_formacao) nextval('formacaocensodisciplina_ed145_sequencial_seq'), a.ed145_formacao, 99
                from formacaocensodisciplina a
                where
                    a.ed145_censodisciplina in (15, 20, 21) AND
                    not exists (
                        select b.ed145_sequencial
                        from formacaocensodisciplina b
                        where
                            b.ed145_censodisciplina = 99 AND
                            b.ed145_formacao = a.ed145_formacao
                    );
            delete from formacaocensodisciplina where ed145_censodisciplina in (15, 20, 21);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            update turmaac set ed268_c_aee = SUBSTRING(ed268_c_aee, 1, 12) where length(ed268_c_aee) = 15 AND ed268_c_aee is not null;

            update recursosavaliacaoinep set ed326_descricao = 'PROVA AMPLIADA (FONTE TAMANHO 20)' where ed326_sequencial = 107;
            update recursosavaliacaoinep set ed326_descricao = 'PROVA AMPLIADA (FONTE TAMANHO 24)' where ed326_sequencial = 108;
SQL
        );
    }
}
