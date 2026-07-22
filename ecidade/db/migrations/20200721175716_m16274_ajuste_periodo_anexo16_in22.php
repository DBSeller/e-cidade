<?php

use Classes\PostgresMigration;

class M16274AjustePeriodoAnexo16In22 extends PostgresMigration
{
    public function up() {
        $sql = "delete from orcparamrelperiodos where o113_orcparamrel = 231 and o113_periodo not in(28)";
        $this->execute($sql);
    }

    public function down() {
        $sql = "
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 17, 231);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 18, 231);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 19, 231);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 20, 231);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 21, 231);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 22, 231);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 23, 231);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 24, 231);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 25, 231);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 26, 231);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 27, 231);
        ";
        $this->execute($sql);
    }
}
