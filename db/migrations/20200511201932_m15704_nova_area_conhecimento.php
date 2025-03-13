<?php

use Classes\PostgresMigration;

class M15704NovaAreaConhecimento extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            insert into areaconhecimento values (1006, 'Outras Áreas', 1);
        ");
        $this->execute("
            update caddisciplina set ed232_areaconhecimento = 1006
              from censocaddisciplina
             where censocaddisciplina.ed294_caddisciplina = caddisciplina.ed232_i_codigo
               and censocaddisciplina.ed294_censodisciplina = 99
               and ed232_areaconhecimento is null;
        ");
    }

    public function down()
    {
        $this->execute("
            update caddisciplina set ed232_areaconhecimento = null
              from censocaddisciplina
             where censocaddisciplina.ed294_caddisciplina = caddisciplina.ed232_i_codigo
               and censocaddisciplina.ed294_censodisciplina = 99
               and ed232_areaconhecimento = 1006;
        ");

        $this->execute("delete from areaconhecimento where  ed293_sequencial = 1006;");
    }
}
