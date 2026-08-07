<?php

use Classes\PostgresMigration;

class M15739AdicionaDataDesligamentoFormularioEsocial extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            INSERT INTO configuracoes.db_formulas VALUES ((SELECT max(db148_sequencial) + 1 FROM db_formulas), 'ESOCIAL_DATA_DESLIGAMENTO', 'Retorna a data de desligamento do servidor.', 'select case when rh05_seqpes is not null then rh05_recis::text else \'\' end as rh05_recis from rhpessoalmov inner join rhpesrescisao on rh02_seqpes = rh05_seqpes where rh02_anousu = fc_anofolha([ESOCIAL_INSTITUICAO]) and rh02_mesusu = fc_mesfolha([ESOCIAL_INSTITUICAO]) and rh02_regist =  [ESOCIAL_MATRICULA_SERVIDOR];', false);

            INSERT INTO esocial.avaliacaoperguntadb_formulas VALUES (nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), (SELECT max(db148_sequencial) FROM db_formulas), 3000859);
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
        delete from esocial.avaliacaoperguntadb_formulas where eso01_db_formulas in (select db148_sequencial from configuracoes.db_formulas where db148_nome = 'ESOCIAL_DATA_DESLIGAMENTO');
        delete from configuracoes.db_formulas where db148_nome = 'ESOCIAL_DATA_DESLIGAMENTO';
SQL;
        $this->execute($sql);
    }

}
