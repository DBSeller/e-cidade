<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21282AtualizaFcVerificapassageiroretorno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(<<<SQL
            CREATE OR REPLACE FUNCTION fc_verificapassageiroretorno()
            RETURNS TRIGGER AS $$
            DECLARE
              iNumLinhasAfetadas INT;
              pVerificaPassageiroRetorno CURSOR FOR
                                           SELECT tf31_i_codigo
                                             FROM tfd_passageiroretorno
                                                WHERE tf31_i_passageiroveiculo = OLD.tf19_i_codigo
                                                  AND tf31_i_valido = 1;
              rsDados RECORD;

            BEGIN
              -- Se na alteração, for alterado um registro da tfd_passageiroveiculo que é referenciado na tfd_passageiroretorno
              -- e este registro for tornada inválido (tf19_i_valido = 2) não posso permitir enquanto não for invalidado na
              -- tfd_passageiroretorno
              IF TG_OP = 'UPDATE' AND NEW.tf19_i_valido = 2 THEN
                OPEN pVerificaPassageiroRetorno;
                FETCH pVerificaPassageiroRetorno INTO rsDados;
                IF NOT FOUND THEN -- Significa que não é referenciado na tfd_passageiroretorno. Posso alterar.
                  CLOSE pVerificaPassageiroRetorno;
                  RETURN NEW;
                ELSE
                  RAISE EXCEPTION 'O paciente possui retorno marcado.';
                  CLOSE pVerificaPassageiroRetorno;
                  RETURN OLD;
                END IF;
                CLOSE pVerificaPassageiroRetorno;
              END IF;
              RETURN NEW;
            END;
            $$ LANGUAGE 'plpgsql';
SQL
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement(<<<SQL
            CREATE OR REPLACE FUNCTION fc_verificapassageiroretorno()
            RETURNS TRIGGER AS $$
            DECLARE
              iNumLinhasAfetadas INT;
              pVerificaPassageiroRetorno CURSOR FOR
                                           SELECT tf31_i_codigo
                                             FROM tfd_passageiroretorno
                                                WHERE tf31_i_passageiroveiculo = OLD.tf19_i_codigo
                                                  AND tf31_i_valido = 1;
              rsDados RECORD;

            BEGIN
              -- Se na alteração, for alterado um registro da tfd_passageiroveiculo que é referenciado na tfd_passageiroretorno
              -- e este registro for tornada inválido (tf19_i_valido = 2) não posso permitir enquanto não for invalidado na
              -- tfd_passageiroretorno
              IF TG_OP = 'UPDATE' AND NEW.tf19_i_valido = 2 THEN
                OPEN pVerificaPassageiroRetorno;
                FETCH pVerificaPassageiroRetorno INTO rsDados;
                IF NOT FOUND THEN -- Significa que não é referenciado na tfd_passageiroretorno. Posso alterar.
                  RETURN NEW;
                ELSE
                  RAISE EXCEPTION 'O paciente possui retorno marcado.';
                  RETURN OLD;
                END IF;
              END IF;
              RETURN NEW;
            END;
            $$ LANGUAGE 'plpgsql';
SQL
        );
    }
}
