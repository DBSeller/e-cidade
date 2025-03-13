<?php

use Classes\PostgresMigration;

class M11471FeedbackLoaLinhasPacto extends PostgresMigration
{
    public function up()
    {
        $this->adicionarForeignKeyPlano();
    }

    public function down()
    {
        $this->removerForeignKeyPlano();
    }

    private function adicionarForeignKeyPlano()
    {
        $sql = "
            insert into db_syscampo values(1009885,'c41_previsaodespesaplano','int4','Plano orçamentário','0', 'Plano orçamentário',15,'f','f','f',1,'text','Plano orçamentário');
            insert into db_sysarqcamp values(1010302,1009885,4,0);
            insert into db_sysforkey values(1010302,1009885,1,1010303,0);
            
            ALTER TABLE previsaodespesalinhaspacto
            ADD COLUMN c41_previsaodespesaplano integer not null default 0;
            
            ALTER TABLE previsaodespesalinhaspacto
            ADD CONSTRAINT previsaodespesalinhaspacto_previsaodespesaplano_fk FOREIGN KEY (c41_previsaodespesaplano)
            REFERENCES previsaodespesaplano;
        ";
        $this->execute($sql);
    }

    private function removerForeignKeyPlano()
    {
        $this->table('previsaodespesalinhaspacto')
            ->removeColumn('c41_previsaodespesaplano')
            ->save();

        $sql = "
            delete from db_sysforkey where codcam = 1009885;
            delete from db_sysarqcamp where codcam = 1009885;
            delete from db_syscampo where codcam = 1009885;
        ";
        $this->execute($sql);
    }
}
