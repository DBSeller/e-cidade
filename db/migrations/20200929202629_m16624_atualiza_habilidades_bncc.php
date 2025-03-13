<?php

use Classes\PostgresMigration;

class M16624AtualizaHabilidadesBncc extends PostgresMigration
{
    public function change()
    {
        $stmt = $this->query("select * from sec_parametros where ed290_bncc = 1;");
        $configuracao_bncc = $stmt->fetch()['ed290_bncc'];
        if ($configuracao_bncc === 1) {
            $this->execute(<<<SQL
insert into bnccensinofundamental (select nextval('bnccensinofundamental_ed148_sequencial_seq'),
                  ed166_disciplina,
                  ed166_etapa,
                  ed166_codigo,
                  ed166_unidade_tematica,
                  ed166_objeto_conhecimento,
                  ed166_habilidade,
                  '2020' as ed148_ano
           from bnccensinofundamentaloriginal
           where not exists(select 1
                from bnccensinofundamental
                where bnccensinofundamental.ed148_codigo = bnccensinofundamentaloriginal.ed166_codigo));
SQL
            );
        }
    }
}
