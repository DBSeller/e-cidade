<?php

use Classes\PostgresMigration;

class M17615AtualizaTrigger extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL

CREATE OR REPLACE FUNCTION pc_delete_valores()
  RETURNS TRIGGER
  LANGUAGE PLPGSQL
  AS
$$
BEGIN

    if TG_TABLE_NAME = 'objetivosprogramaestrategico' THEN
        delete from planejamento.valores
         where pl10_origem = 'OBJETIVOS'::origem_valores
           and pl10_chave = OLD.pl11_codigo;
    end if;

    if TG_TABLE_NAME = 'programaestrategico' THEN
        delete from planejamento.valores
         where pl10_origem = 'PROGRAMA ESTRATEGICO'::origem_valores
           and pl10_chave = OLD.pl9_codigo;
    end if;

    if TG_TABLE_NAME = 'estimativareceita' THEN
        delete from planejamento.valores
         where pl10_origem = 'RECEITA'::origem_valores
           and pl10_chave = OLD.id;
    end if;

    RETURN OLD;
END;
$$;

CREATE TRIGGER tg_remove_valores_estimativareceita
  AFTER DELETE
  ON planejamento.estimativareceita
  FOR EACH ROW
  EXECUTE PROCEDURE pc_delete_valores();

SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
        DROP TRIGGER IF EXISTS tg_remove_valores_estimativareceita ON planejamento.estimativareceita;
SQL
        );
    }
}
