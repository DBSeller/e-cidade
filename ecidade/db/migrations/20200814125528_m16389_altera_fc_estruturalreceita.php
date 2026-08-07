<?php

use Classes\PostgresMigration;

class M16389AlteraFcEstruturalreceita extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
create or replace function fc_estruturalreceita(integer,integer)
returns varchar
as '
DECLARE

  ANOUSU ALIAS FOR $1;
  CODREC ALIAS FOR $2;
  ESTRUTURAL VARCHAR(200);

BEGIN

  SELECT SUBSTR(O57_FONTE,1,1)||''.''||
         SUBSTR(O57_FONTE,2,1)||''.''||
         SUBSTR(O57_FONTE,3,1)||''.''||
         SUBSTR(O57_FONTE,4,1)||''.''||
         SUBSTR(O57_FONTE,5,1)||''.''||
         SUBSTR(O57_FONTE,6,2)||''.''||
         SUBSTR(O57_FONTE,8,2)||''.''||
         SUBSTR(O57_FONTE,10,2)||''.''||
         SUBSTR(O57_FONTE,12,2)||''.''||
         SUBSTR(O57_FONTE,14,2)||''.''||
         LPAD(o15_loaespecificacao::int,4,0)
  INTO ESTRUTURAL
  FROM ORCRECEITA R
       INNER JOIN ORCFONTES O ON O.O57_CODFON = R.O70_CODFON AND O.O57_ANOUSU = R.O70_ANOUSU
       INNER JOIN orctiporec TR ON TR.o15_codigo = R.o70_codigo
  WHERE R.O70_ANOUSU = ANOUSU AND R.O70_CODREC = CODREC;

RETURN ESTRUTURAL;

END;
' language 'plpgsql';
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
create or replace function fc_estruturalreceita(integer,integer)
returns varchar
as '
DECLARE

  ANOUSU ALIAS FOR $1;
  CODREC ALIAS FOR $2;
  ESTRUTURAL VARCHAR(200);

BEGIN

  SELECT SUBSTR(O57_FONTE,1,1)||''.''||
         SUBSTR(O57_FONTE,2,1)||''.''||
         SUBSTR(O57_FONTE,3,1)||''.''||
         SUBSTR(O57_FONTE,4,1)||''.''||
         SUBSTR(O57_FONTE,5,1)||''.''||
         SUBSTR(O57_FONTE,6,2)||''.''||
         SUBSTR(O57_FONTE,8,2)||''.''||
         SUBSTR(O57_FONTE,10,2)||''.''||
         SUBSTR(O57_FONTE,12,2)||''.''||
         SUBSTR(O57_FONTE,14,2)||''.''||
         LPAD(O70_CODIGO,4,0)
  INTO ESTRUTURAL
  FROM ORCRECEITA R
       INNER JOIN ORCFONTES O ON O.O57_CODFON = R.O70_CODFON AND O.O57_ANOUSU = R.O70_ANOUSU
  WHERE R.O70_ANOUSU = ANOUSU AND R.O70_CODREC = CODREC;

  RETURN ESTRUTURAL;

END;
' language 'plpgsql';
SQL
        );
    }
}
