<?php

use Classes\PostgresMigration;

class M15912acertoTransacoes extends PostgresMigration
{
    public function up()
    {
$sql = <<<SQL
insert into conhistdocregra (c92_sequencial,c92_conhistdoc,c92_descricao,c92_regra,c92_anousu)

select nextval('conhistdocregra_c92_sequencial_seq'),
       c92_conhistdoc,
       c92_descricao,
       c92_regra,
       2020
  from conhistdocregra
where not exists(select 1
                   from conhistdocregra r
                  where r.c92_conhistdoc = conhistdocregra.c92_conhistdoc
                    and r.c92_anousu = 2020 )
 group by c92_conhistdoc,
          c92_descricao,
          c92_regra;

update conhistdocregra set c92_regra = c92_regra|| ' and c21_instit = fc_getsession(\'DB_instit\')::int and c21_anousu =fc_getsession(\'DB_anousu\')::int'
where c92_regra ilike 'select 1 from conplanoorcamentogrupo where c21_codcon = [desdobramento] and c21_congrupo%'
  and c92_regra not ilike '%c21_instit = % and c21_anousu =%';
SQL;
	$this->execute($sql);


    }
}
