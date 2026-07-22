<?php

use Classes\PostgresMigration;

class M15300CriacaoRelatoriosLegais extends PostgresMigration
{

    private $dadosRelatorios = array(
        206 => 'TCE/RO - ANEXO 1',
        207 => 'TCE/RO - ANEXO 2 - ESTE RELATÓRIO NÃO POSSUI CONFIGURAÇÃO',
        208 => 'TCE/RO - ANEXO 3 - ESTE RELATÓRIO NÃO POSSUI CONFIGURAÇÃO',
        209 => 'TCE/RO - ANEXO 4 - ESTE RELATÓRIO NÃO POSSUI CONFIGURAÇÃO',
        210 => 'TCE/RO - ANEXO 5 - ESTE RELATÓRIO NÃO POSSUI CONFIGURAÇÃO',
        211 => 'TCE/RO - ANEXO 6 - ESTE RELATÓRIO NÃO POSSUI CONFIGURAÇÃO',
        212 => 'TCE/RO - ANEXO 7 - ESTE RELATÓRIO NÃO POSSUI CONFIGURAÇÃO',
        213 => 'TCE/RO - ANEXO 8 - ESTE RELATÓRIO NÃO POSSUI CONFIGURAÇÃO',
        214 => 'TCE/RO - ANEXO 9 - ESTE RELATÓRIO NÃO POSSUI CONFIGURAÇÃO',
        215 => 'TCE/RO - ANEXO 10 - ESTE RELATÓRIO NÃO POSSUI CONFIGURAÇÃO',
    );

    public function up()
    {

        foreach ($this->dadosRelatorios as $codigo => $descricao) {

            $this->execute(<<<SQL_UP

insert into orcparamrel values ($codigo, '$descricao', 4, null);

insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 17, $codigo);
insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 18, $codigo);
insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 19, $codigo);
insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 20, $codigo);
insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 21, $codigo);
insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 22, $codigo);
insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 23, $codigo);
insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 24, $codigo);
insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 25, $codigo);
insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 26, $codigo);
insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 27, $codigo);
insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 28, $codigo);

SQL_UP
            );
        }
    }

    public function down()
    {

        $this->execute(<<<SQL_DOWN

delete from orcparamrelperiodos where o113_orcparamrel between 206 and 215;
delete from orcparamrel where o42_codparrel between 206 and 215;

SQL_DOWN
);
    }

}
