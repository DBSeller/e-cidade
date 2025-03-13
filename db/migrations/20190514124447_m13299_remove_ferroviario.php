<?php

use Classes\PostgresMigration;

class M13299RemoveFerroviario extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            -- Alunos que usam só o trem não usam mais transporte público
            update aluno set ed47_i_transpublico = 0, ed47_c_transporte = '' where ed47_i_codigo in (
                select
                    ed311_aluno
                from
                    alunocensotipotransporte
                where
                    -- Alunos que usam só um transporte escolar
                    ed311_aluno in (
                        select
                            ed311_aluno
                        from
                            alunocensotipotransporte
                        group by
                            ed311_aluno
                        having
                            count(ed311_censotipotransporte) = 1
                    )
                    AND ed311_censotipotransporte = 11
            );

            -- Remover todos os vínculos de aluno com o trem
            delete from alunocensotipotransporte where ed311_censotipotransporte = 11;

            -- Remover o trem
            delete from censotipotransporte where ed312_sequencial = 11;
SQL
        );
    }
}
