<?php

use Classes\PostgresMigration;

class M15862AtributoCfMsc extends PostgresMigration
{

    public function up()
    {
      $this->execute(<<<SQL
update contabilidade.conplanoinfocomplementar
 set c121_sql = 'select o200_sequencial from conlancamcomplementorecurso inner join complementofonterecurso on o200_sequencial = o201_complemento where o201_codlan = codigo_lancamento and o200_msc is true limit 1'
 where c121_sequencial = 53;
SQL
);
    }

    public function down()
    {
        $this->execute(<<<SQL
update contabilidade.conplanoinfocomplementar
 set c121_sql = 'select null'
 where c121_sequencial = 53;
SQL
        );
    }
}
