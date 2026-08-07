<?php

use Classes\PostgresMigration;

class M17295TriggerParaRemocaoValores extends PostgresMigration
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

    RETURN OLD;
END;
$$;

CREATE TRIGGER tg_remove_valores_objetivo
  AFTER DELETE
  ON planejamento.objetivosprogramaestrategico
  FOR EACH ROW
  EXECUTE PROCEDURE pc_delete_valores();

CREATE TRIGGER tg_remove_valores_programa
  AFTER DELETE
  ON planejamento.programaestrategico
  FOR EACH ROW
  EXECUTE PROCEDURE pc_delete_valores();

SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
        DROP TRIGGER IF EXISTS tg_remove_valores_objetivo ON planejamento.objetivosprogramaestrategico;
        DROP TRIGGER IF EXISTS tg_remove_valores_programa ON planejamento.programaestrategico;
        DROP PROCEDURE IF EXISTS pc_delete_valores;

SQL
        );
    }
}
