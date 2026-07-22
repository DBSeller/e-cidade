<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21282AtualizaFcVerificaexclusaosaidatfd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(<<<SQL
            CREATE OR REPLACE FUNCTION fc_verificaexclusaosaidatfd()
            RETURNS TRIGGER AS $$
            DECLARE
              iNumLinhasAfetadas INT;
              pVerificaVeiculoDestino CURSOR FOR
                                         SELECT tf18_i_codigo
                                           FROM tfd_veiculodestino
                                             INNER JOIN tfd_passageiroveiculo on tfd_passageiroveiculo.tf19_i_veiculodestino =
                                               tfd_veiculodestino.tf18_i_codigo AND tfd_passageiroveiculo.tf19_i_valido = 1
                                                 WHERE tf19_i_pedidotfd = OLD.tf17_i_pedidotfd;
              rsDados RECORD;

            BEGIN
              -- Não posso permitir deletar um registro da tfd_agendasaida se já foi indicado um veiculo para o pedido de TFD da da saída (tf17_i_pedidotfd)
              IF TG_OP = 'DELETE' OR TG_OP = 'UPDATE' THEN
                OPEN pVerificaVeiculoDestino;
                FETCH pVerificaVeiculoDestino INTO rsDados;
                IF NOT FOUND THEN -- Significa que não foi indicado veículo para ninguém do pedido de TFD, então, pode excluir
                  IF TG_OP = 'DELETE' THEN
                    CLOSE pVerificaVeiculoDestino;
                    RETURN OLD;
                  ELSE
                    CLOSE pVerificaVeiculoDestino;
                    RETURN NEW;
                  END IF;
                ELSE
                  RAISE EXCEPTION 'O pedido de TFD já possui paciente ou acompanhantes lançados na rotina Indique Veículos.';
                  IF TG_OP = 'DELETE' THEN
                    RETURN NEW;
                  ELSE
                    RETURN OLD;
                  END IF;
                END IF;
              END IF;
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
            CREATE OR REPLACE FUNCTION fc_verificaexclusaosaidatfd()
            RETURNS TRIGGER AS $$
            DECLARE
              iNumLinhasAfetadas INT;
              pVerificaVeiculoDestino CURSOR FOR
                                         SELECT tf18_i_codigo
                                           FROM tfd_veiculodestino
                                             INNER JOIN tfd_passageiroveiculo on tfd_passageiroveiculo.tf19_i_veiculodestino =
                                               tfd_veiculodestino.tf18_i_codigo AND tfd_passageiroveiculo.tf19_i_valido = 1
                                                 WHERE tf19_i_pedidotfd = OLD.tf17_i_pedidotfd;
              rsDados RECORD;

            BEGIN
              -- Não posso permitir deletar um registro da tfd_agendasaida se já foi indicado um veiculo para o pedido de TFD da da saída (tf17_i_pedidotfd)
              IF TG_OP = 'DELETE' OR TG_OP = 'UPDATE' THEN
                OPEN pVerificaVeiculoDestino;
                FETCH pVerificaVeiculoDestino INTO rsDados;
                IF NOT FOUND THEN -- Significa que não foi indicado veículo para ninguém do pedido de TFD, então, pode excluir
                  IF TG_OP = 'DELETE' THEN
                    RETURN OLD;
                  ELSE
                    RETURN NEW;
                  END IF;
                ELSE
                  RAISE EXCEPTION 'O pedido de TFD já possui paciente ou acompanhantes lançados na rotina Indique Veículos.';
                  IF TG_OP = 'DELETE' THEN
                    RETURN NEW;
                  ELSE
                    RETURN OLD;
                  END IF;
                END IF;
              END IF;
            END;
            $$ LANGUAGE 'plpgsql';
SQL
        );
    }
}
