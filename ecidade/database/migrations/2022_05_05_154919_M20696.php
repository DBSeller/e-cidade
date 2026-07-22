<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20696 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            UPDATE avaliacaopergunta SET db103_obrigatoria = true
            WHERE db103_sequencial IN (
                SELECT db103_sequencial FROM avaliacaopergunta
                WHERE db103_descricao = 'Indicativo de suspensão da exigibilidade'
            );

            UPDATE avaliacaoperguntaopcao SET db104_descricao = '01 - Liminar em mandado de segurança'
            WHERE db104_sequencial IN (
                SELECT db104_sequencial FROM avaliacaoperguntaopcao
                WHERE db104_descricao = 'Liminar em mandado de segurança'
            );

            UPDATE avaliacaoperguntaopcao SET db104_descricao = '02 - Depósito Judicial do montante integral'
            WHERE db104_sequencial IN (
                SELECT db104_sequencial FROM avaliacaoperguntaopcao
                WHERE db104_descricao = 'Depósito Judicial do montante integral'
            );

            UPDATE avaliacaoperguntaopcao SET db104_descricao = '03 - Depósito administrativo do montante integral'
            WHERE db104_sequencial IN (
                SELECT db104_sequencial FROM avaliacaoperguntaopcao
                WHERE db104_descricao = 'Depósito administrativo do montante integral'
            );

            UPDATE avaliacaoperguntaopcao SET db104_descricao = '04 - Antecipação de tutela'
            WHERE db104_sequencial IN (
                SELECT db104_sequencial FROM avaliacaoperguntaopcao
                WHERE db104_descricao = 'Antecipação de tutela'
            );

            UPDATE avaliacaoperguntaopcao SET db104_descricao = '05 - Liminar em medida cautelar'
            WHERE db104_sequencial IN (
                SELECT db104_sequencial FROM avaliacaoperguntaopcao
                WHERE db104_descricao = 'Liminar em medida cautelar'
            );

            UPDATE avaliacaoperguntaopcao SET db104_descricao = '08 - Sentença em mandado de segurança favorável ao contribuinte'
            WHERE db104_sequencial IN (
                SELECT db104_sequencial FROM avaliacaoperguntaopcao
                WHERE db104_descricao = 'Sentença em mandado de segurança favorável ao contribuinte'
            );

            UPDATE avaliacaoperguntaopcao SET db104_descricao = '09 - Sentença em ação ordinária favorável ao contribuinte e confirmada pelo TRF'
            WHERE db104_sequencial IN (
                SELECT db104_sequencial FROM avaliacaoperguntaopcao
                WHERE db104_descricao = 'Sentença em ação ordinária favorável ao contribuinte e confirmada pelo TRF'
            );

            UPDATE avaliacaoperguntaopcao SET db104_descricao = '10 - Acórdão do TRF favorável ao contribuinte'
            WHERE db104_sequencial IN (
                SELECT db104_sequencial FROM avaliacaoperguntaopcao
                WHERE db104_descricao = 'Acórdão do TRF favorável ao contribuinte'
            );

            UPDATE avaliacaoperguntaopcao SET db104_descricao = '11 - Acórdão do STJ em recurso especial favorável ao contribuinte'
            WHERE db104_sequencial IN (
                SELECT db104_sequencial FROM avaliacaoperguntaopcao
                WHERE db104_descricao = 'Acórdão do STJ em recurso especial favorável ao contribuinte'
            );

            UPDATE avaliacaoperguntaopcao SET db104_descricao = '12 - Acórdão do STF em recurso extraordinário favorável ao contribuinte'
            WHERE db104_sequencial IN (
                SELECT db104_sequencial FROM avaliacaoperguntaopcao
                WHERE db104_descricao = 'Acórdão do STF em recurso extraordinário favorável ao contribuinte'
            );

            UPDATE avaliacaoperguntaopcao SET db104_descricao = '13 - Sentença 1ª instância não transitada em julgado com efeito suspensivo'
            WHERE db104_sequencial IN (
                SELECT db104_sequencial FROM avaliacaoperguntaopcao
                WHERE db104_descricao = 'Sentença 1ª instância não transitada em julgado com efeito suspensivo'
            );

            UPDATE avaliacaoperguntaopcao SET db104_descricao = '90 - Decisão definitiva a favor do contribuinte'
            WHERE db104_sequencial IN (
                SELECT db104_sequencial FROM avaliacaoperguntaopcao
                WHERE db104_descricao = 'Decisão definitiva a favor do contribuinte'
            );

            UPDATE avaliacaoperguntaopcao SET db104_descricao = '92 - Sem suspensão da exigibilidade'
            WHERE db104_sequencial IN (
                SELECT db104_sequencial FROM avaliacaoperguntaopcao
                WHERE db104_descricao = 'Sem suspensão da exigibilidade'
            );
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
        //
    }
}
