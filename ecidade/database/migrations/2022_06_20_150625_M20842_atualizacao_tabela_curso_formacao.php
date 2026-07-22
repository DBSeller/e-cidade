<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M20842AtualizacaoTabelaCursoFormacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into escola.cursoformacao (ed94_i_codigo, ed94_c_descr, ed94_i_codclasse, ed94_c_codigocenso, ed94_c_descrclasse, ed94_i_grauacademico, ed94_ativo)
values  ( nextval('escola.cursoformacao_ed94_i_codigo_seq'), 'Inteligência artificial - Bacharelado', 6, '0614I012', 'Ciência da computação', 2, true),
        ( nextval('escola.cursoformacao_ed94_i_codigo_seq'), 'Inteligência artificial - Tecnológico', 6, '0614I013', 'Ciência da computação', 1, true),
        ( nextval('escola.cursoformacao_ed94_i_codigo_seq'), 'Internet das coisas - Tecnológico', 6, '0616I013', 'Desenvolvimento de sistemas que integram software e hardware', 1, true),
        ( nextval('escola.cursoformacao_ed94_i_codigo_seq'), 'Agrocomputação - Tecnológico', 6, '0617A013', 'Soluções computacionais para domínios específicos', 1, true),
        ( nextval('escola.cursoformacao_ed94_i_codigo_seq'), 'Ciência de dados - Bacharelado', 6, '0617C012', 'Soluções computacionais para domínios específicos', 2, true),
        ( nextval('escola.cursoformacao_ed94_i_codigo_seq'), 'Ciência de dados - Tecnológico', 6, '0617C013', 'Soluções computacionais para domínios específicos', 1, true),
        ( nextval('escola.cursoformacao_ed94_i_codigo_seq'), 'Computação e Tecnologias da Informação e Comunicação (TIC) em biociências e saúde - Bacharelado', 6, '0617C022', 'Soluções computacionais para domínios específicos', 2, true),
        ( nextval('escola.cursoformacao_ed94_i_codigo_seq'), 'Computação e Tecnologias da Informação e Comunicação (TIC) em biociências e saúde - Tecnológico', 6, '0617C023', 'Soluções computacionais para domínios específicos', 1, true),
        ( nextval('escola.cursoformacao_ed94_i_codigo_seq'), 'Criação digital - Bacharelado', 6, '0617C032', 'Soluções computacionais para domínios específicos', 2, true),
        ( nextval('escola.cursoformacao_ed94_i_codigo_seq'), 'Criação digital - Tecnológico', 6, '0617C033', 'Soluções computacionais para domínios específicos', 1, true),
        ( nextval('escola.cursoformacao_ed94_i_codigo_seq'), 'Programas interdisciplinares abrangendo serviços', 10, '1088P013', 'Programas interdisciplinares abrangendo serviços', 1, true);
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
        DB::connection()->getPdo()->exec(<<<SQL
delete from escola.cursoformacao where ed94_c_codigocenso in ('0614I012', '0614I013', '0616I013', '0617A013', '0617C012', '0617C013', '0617C022', 
                                                                '0617C023', '0617C032', '0617C033', '1088P013');
SQL
        );
    }
}
