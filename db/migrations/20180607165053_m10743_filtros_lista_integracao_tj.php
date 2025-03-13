<?php

use Classes\PostgresMigration;

class M10743FiltrosListaIntegracaoTj extends PostgresMigration
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

create or replace function fn_cnpj_cpf(text) returns boolean as
$$
 DECLARE
  v_string text := $1;
  v_caldv1 int4;
  v_caldv2 int4;
  v_dv1 int4;
  v_dv2 int4;
  v_array1 text[] ;
  v_array2 text[] ;
  v_tst_string int4;
 BEGIN
  v_string := translate(v_string, './-', '');
  IF (char_length(v_string)) = 14 THEN

   SELECT INTO v_array1 '{5,4,3,2,9,8,7,6,5,4,3,2}';
   SELECT INTO v_array2 '{6,5,4,3,2,9,8,7,6,5,4,3,2}';
   v_dv1 := (substring(v_string, 13, 1))::int4;
   v_dv2 := (substring(v_string, 14, 1))::int4;
   /* COLETA DIG VER 1 CNPJ */
   v_caldv1 := 0;
   FOR va IN 1..12 LOOP
    v_caldv1 := v_caldv1 + ((SELECT substring(v_string, va, 1))::int4 * (v_array1[va]::int4));
   END LOOP;
   v_caldv1 := v_caldv1 % 11;
    IF (v_caldv1 = 0) OR (v_caldv1 = 1) THEN
     v_caldv1 := 0;
    ELSE
     v_caldv1 := 11 - v_caldv1;
    END IF;
   /* COLETA DIG VER 2 CNPJ */
   v_caldv2 := 0;
   FOR va IN 1..13 LOOP
    v_caldv2 := v_caldv2 + ((SELECT substring(v_string || v_caldv1::text, va, 1))::int4 * (v_array2[va]::int4));
   END LOOP;
   v_caldv2 := v_caldv2 % 11;
    IF (v_caldv2 = 0) OR (v_caldv2 = 1) THEN
     v_caldv2 := 0;
    ELSE
     v_caldv2 := 11 - v_caldv2;
    END IF;
   /* TESTA */
   IF (v_caldv1 = v_dv1) AND (v_caldv2 = v_dv2) THEN
    RETURN TRUE;
   ELSE
    RETURN FALSE;
   END IF;

  ELSIF (char_length(v_string)::int4) = 11 THEN

   v_dv1 := (substring(v_string, 10, 1))::int4;
   v_dv2 := (substring(v_string, 11, 1))::int4;
   v_string := substring(v_string, 1, 9);
   /* COLETA DIG VER 1 CPF */
   v_caldv1 := 0;
   FOR va IN 1..9 LOOP
    v_caldv1 := v_caldv1 + ((SELECT substring(v_string, va, 1))::int4 * (11 - va));
   END LOOP;
   v_caldv1 := v_caldv1 % 11;
   IF (v_caldv1 = 0) OR (v_caldv1 = 1) THEN
    v_caldv1 := 0;
   ELSE
    v_caldv1 := 11 - v_caldv1;
   END IF;
   /* COLETA DIG VER 2 CPF */
   v_caldv2 := 0;
   FOR va IN 1..10 LOOP
    v_caldv2 := v_caldv2 + ((SELECT substring((v_string || v_caldv1::text), va, 1))::int4 * (12 - va));
   END LOOP;
   v_caldv2 := v_caldv2 % 11;
   IF (v_caldv2 = 0) OR (v_caldv2 = 1) THEN
    v_caldv2 := 0;
   ELSE
    v_caldv2 := 11 - v_caldv2;
   END IF;
   /* TESTA */
   IF (v_caldv1 = v_dv1) AND (v_caldv2 = v_dv2) THEN
    RETURN TRUE;
   ELSE
    RETURN FALSE;
   END IF;

  END IF;

 RETURN FALSE;
 END;

$$
language 'plpgsql';
SQL

        );
    }
}
