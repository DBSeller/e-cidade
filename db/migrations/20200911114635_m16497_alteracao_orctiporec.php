<?php

use Classes\PostgresMigration;

class M16497AlteracaoOrctiporec extends PostgresMigration
{
    public function up()
    {
        $this->dicionario();
        $this->estrutura();

    }

    public function down()
    {
        $this->execute("
        delete from db_syssequencia where codsequencia = 1000968;
        delete from db_syscadind where codind in (1008611, 1008610);
        delete from db_sysindices where codind in (1008611, 1008610);
        delete from db_sysarqcamp where codcam = 1011803;
        delete from db_syscampo where codcam = 1011803;
        update db_syscampo set descricao = 'Código do tipo de Recurso', rotulo = 'Recurso', rotulorel = 'Recurso' where codcam = 3347;
        ");

        $this->execute("DROP SEQUENCE IF EXISTS orctiporec_o15_codigo_seq;");
        $this->execute("DROP INDEX orctiporec_recurso_complemento_in;");
        $this->execute("alter table orcamento.orctiporec drop CONSTRAINT orctiporec_complemento_fk;");
    }

    private function dicionario()
    {
        $this->execute("
        update db_syscampo set descricao = 'Código Sequencial', rotulo = 'Código', rotulorel = 'Código' where codcam = 3347;
        insert into db_syscampo values(1011803,'o15_recurso','varchar(10)','Recurso','', 'Recurso',10,'t','t','f',0,'text','Recurso');
        insert into db_sysarqcamp values(749,1011803,14,0);
        insert into db_sysindices values(1008610,'orctiporec_o15_recurso_in',749,'0');
        insert into db_syscadind values(1008610,1011803,1);
        insert into db_syssequencia values(1000968, 'orctiporec_o15_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000968 where codarq = 749 and codcam = 3347;
        ");

        $this->execute("
        insert into db_sysindices values(1008611,'orctiporec_recurso_complemento_in',749,'1');
        insert into db_syscadind values(1008611,1011803,1);
        insert into db_syscadind values(1008611,1011286,2);
        ");

    }

    private function estrutura()
    {
        $this->execute("
            CREATE SEQUENCE orctiporec_o15_codigo_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
            alter table orctiporec add o15_recurso varchar(10);
            CREATE INDEX orctiporec_o15_recurso_in ON orctiporec(o15_recurso);
        ");

         $stmt = $this->query("select munic from db_config limit 1");
         $dado = $stmt->fetch(PDO::FETCH_ASSOC);
         if ($dado['munic'] == 'NITEROI') {
             $this->execute("
                 update orctiporec set o15_recurso = lpad(o15_codigo, 5) where o15_codigo in (0,1);
                 update orctiporec set o15_loatipo = 0 where o15_loatipo is null;
                 update orctiporec set o15_loagrupo = 0 where o15_loagrupo is null;
                 update orctiporec set o15_loaespecificacao = '00'  where o15_loaespecificacao = '';
                 update orctiporec set o15_recurso = o15_loaidentificadoruso::varchar || o15_loatipo::varchar || o15_loagrupo::varchar || o15_loaespecificacao;
             ");
         } else {
             $this->execute("update orctiporec set o15_recurso = lpad(o15_loaespecificacao, 4, 0);");
         }

        $this->execute("alter table orctiporec alter column o15_recurso set not null;");
        $this->execute(
            "select setval('orctiporec_o15_codigo_seq', (select max(o15_codigo) from orcamento.orctiporec));"
        );

        $this->execute('
            CREATE UNIQUE INDEX orctiporec_recurso_complemento_in
                      ON orctiporec(o15_recurso, o15_complemento);'
        );

        $this->execute("
            ALTER TABLE orcamento.orctiporec ADD CONSTRAINT orctiporec_complemento_fk FOREIGN KEY (o15_complemento) REFERENCES orcamento.complementofonterecurso;
        ");
    }
}
