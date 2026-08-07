<?php

use Classes\PostgresMigration;

class M12934EstruturalDotacao extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {

        $this->execute(<<<SQL
        create or replace function fc_estruturaldotacao(integer, integer)
  returns character varying
language plpgsql
as $$
DECLARE

  ANOUSU ALIAS FOR $1;
  CODDOT ALIAS FOR $2;
  ESTRUTURAL VARCHAR(200);
  usaFonteUniao boolean default false;
BEGIN

  usaFonteUniao := coalesce(fc_getsession('DB_use_fonte_recurso_uniao')::boolean, false)::boolean;
  SELECT LPAD(O58_ORGAO,2,0)||'.'||
         LPAD(O58_UNIDADE,2,0)||'.'||
         LPAD(O58_FUNCAO,2,0)||'.'||
         LPAD(O58_SUBFUNCAO,3,0)||'.'||
         LPAD(O58_PROGRAMA,4,0)||'.'||
	 LPAD(O58_PROJATIV,4,0)||'.'||
         LPAD(O56_ELEMENTO,13,0)||'.'||
         (case when true is false then LPAD(O58_CODIGO,4,0)
             else
               case when o15_loaidentificadoruso is not null and o15_loatipo is not null and o15_loagrupo is not null and o15_loaespecificacao is not null
                 then o15_loaidentificadoruso::varchar||o15_loatipo||o15_loagrupo||o15_loaespecificacao else LPAD(O58_CODIGO,4,0) end end )::varchar
  INTO ESTRUTURAL
  FROM ORCDOTACAO D
       INNER JOIN ORCELEMENTO O ON O.O56_CODELE = D.O58_CODELE
                               AND O.O56_ANOUSU = D.O58_ANOUSU
       inner JOIN ORCTIPOREC ON o15_codigo = o58_codigo
   WHERE D.O58_ANOUSU = ANOUSU AND D.O58_CODDOT = CODDOT;

  RETURN ESTRUTURAL;

END;
$$;
SQL
);
    }

    public function down()
    {
        $this->execute(<<<SQL
        create or replace function fc_estruturaldotacao(integer, integer)
  returns character varying
language plpgsql
as $$
DECLARE

  ANOUSU ALIAS FOR $1;
  CODDOT ALIAS FOR $2;
  ESTRUTURAL VARCHAR(200);

BEGIN

  SELECT LPAD(O58_ORGAO,2,0)||'.'||
         LPAD(O58_UNIDADE,2,0)||'.'||
         LPAD(O58_FUNCAO,2,0)||'.'||
         LPAD(O58_SUBFUNCAO,3,0)||'.'||
         LPAD(O58_PROGRAMA,4,0)||'.'||
	 LPAD(O58_PROJATIV,4,0)||'.'||
         LPAD(O56_ELEMENTO,13,0)||'.'||
         LPAD(O58_CODIGO,4,0)
  INTO ESTRUTURAL
  FROM ORCDOTACAO D
       INNER JOIN ORCELEMENTO O ON O.O56_CODELE = D.O58_CODELE
                               AND O.O56_ANOUSU = D.O58_ANOUSU
   WHERE D.O58_ANOUSU = ANOUSU AND D.O58_CODDOT = CODDOT;

  RETURN ESTRUTURAL;

END;
$$;
SQL
        );
    }
}
