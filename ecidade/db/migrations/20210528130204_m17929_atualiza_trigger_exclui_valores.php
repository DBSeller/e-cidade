<?php

use Classes\PostgresMigration;

class M17929AtualizaTriggerExcluiValores extends PostgresMigration
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

    if TG_TABLE_NAME = 'metasobjetivoprogramaestrategico' THEN
        delete from planejamento.valores
         where pl10_origem = 'META OBJETIVO'::origem_valores
           and pl10_chave = OLD.pl21_codigo;
    end if;

    if TG_TABLE_NAME = 'planejamento' THEN
        delete from planejamento.valores
         where pl10_origem = 'PIB'::origem_valores
           and pl10_chave = OLD.pl2_codigo;
    end if;

    RETURN OLD;
END;
$$;

CREATE TRIGGER tg_remove_valores_planejamento
  AFTER DELETE
  ON planejamento.planejamento
  FOR EACH ROW
  EXECUTE PROCEDURE pc_delete_valores();
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
drop trigger if exists tg_remove_valores_planejamento
    ON planejamento.planejamento;
SQL
        );
    }
}
