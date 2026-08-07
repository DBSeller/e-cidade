<?php

use Classes\PostgresMigration;

class M16261In22CriacaoRelatorios extends PostgresMigration
{

    private $dadosRelatorios = array(
        227 => 'TCE/RO - ANEXO 12',
        228 => 'TCE/RO - ANEXO 13',
        229 => 'TCE/RO - ANEXO 14',
        230 => 'TCE/RO - ANEXO 15',
        231 => 'TCE/RO - ANEXO 16',
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
delete from orcparamrelperiodos where o113_orcparamrel between 227 and 231;
delete from orcparamrel where o42_codparrel between 227 and 231;
SQL_DOWN
        );
    }
}
