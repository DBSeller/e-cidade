<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class M21501CorrigeCancelamentoDebitosApropriacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "

        select fc_startsession();
        select fc_putsession('DB_instit'     ,(select cast(codigo as text) from db_config where prefeitura is true limit 1));
        select fc_putsession('DB_id_usuario' ,'1');
        select fc_putsession('DB_login'      ,'dbseller');
        select fc_putsession('DB_debugon'    ,'true');
        select fc_putsession('DB_datausu'    ,cast(current_date as text));
        select fc_putsession('DB_anousu'     ,cast(extract(year from current_date) as text));
        select fc_putsession('DB_use_pcasp', 'true');

        create table w_debitos_apropriacao as
                select 'Estorno pagto do empenho ' || e60_codemp || '/' || e60_anousu || ' retenção.' as k20_descr,
                       2 as tipo,
                       1 as acao,
                       '12:00' as hora,
                       cast(current_date as date) as data_cancelamento,
                       e60_instit,
                       nextval('cancdebitos_k20_codigo_seq') as k20_codigo,
                       nextval('cancdebitosreg_k21_sequencia_seq') as k21_sequencia,
                       nextval('cancdebitosproc_k23_codigo_seq') as cancdebitosproc,
                       nextval('cancdebitosprocreg_k24_sequencia_seq') as cancdebitosprocreg,
                       (select coddepto from db_depusu where id_usuario = e50_id_usuario limit 1) as depto,
                       arrecad.*,
                       cornump.*
           From cornump
           join arrecad on cornump.k12_numpre = arrecad.k00_numpre
                       and cornump.k12_numpar = arrecad.k00_numpar
           join corrente on cornump.k12_id = corrente.k12_id
                        and cornump.k12_data = corrente.k12_data
                        and cornump.k12_autent = corrente.k12_autent
           left join cancdebitosreg on cornump.k12_numpre = k21_numpre
                                   and cornump.k12_numpar = k21_numpar
           join corgrupocorrente on corgrupocorrente.k105_data = corrente.k12_data
                                and corgrupocorrente.k105_autent = corrente.k12_autent
                                and corgrupocorrente.k105_id = corrente.k12_id
           join retencaocorgrupocorrente on retencaocorgrupocorrente.e47_corgrupocorrente = corgrupocorrente.k105_sequencial
           join retencaoreceitas on retencaoreceitas.e23_sequencial = retencaocorgrupocorrente.e47_retencaoreceita
                                and retencaoreceitas.e23_ativo is false
                                and retencaoreceitas.e23_recolhido is true
           join retencaotiporec on retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec
                               and retencaotiporec.e21_retencaotiporecgrupo = 1
                               and retencaotiporec.e21_instit = corrente.k12_instit
           join retencaopagordem on e23_retencaopagordem = e20_sequencial
           join pagordem on e20_pagordem = e50_codord
           join empempenho on e60_numemp = e50_numemp
          where k12_estorn is true
            and k21_sequencia is null
            and arrecad.k00_numtot = 1
        order by cornump.k12_numpre;

        insert into arrecant select k00_numpre,
                                    k00_numpar,
                                    k00_numcgm,
                                    data_cancelamento,
                                    k00_receit,
                                    k00_hist  ,
                                    k00_valor ,
                                    k00_dtvenc,
                                    k00_numtot,
                                    k00_numdig,
                                    k00_tipo  ,
                                    k00_tipojm
                       from w_debitos_apropriacao;

        delete from arrecad
         where k00_numpre in (select k00_numpre from w_debitos_apropriacao);

        insert into cancdebitos select k20_codigo,
                                       k20_descr,
                                       hora,
                                       data_cancelamento,
                                       1,
                                       e60_instit,
                                       tipo,
                                       depto
            from w_debitos_apropriacao;

         insert into cancdebitosreg
           select k21_sequencia,
                  k20_codigo,
                  k00_numpre,
                  k00_numpar,
                  k00_receit,
                  data_cancelamento,
                  hora,
                  k20_descr
             from w_debitos_apropriacao;

        insert into cancdebitosproc
             select cancdebitosproc,
                    data_cancelamento,
                    hora,
                    1,
                    k20_descr,
                    2
              from w_debitos_apropriacao;

        insert into cancdebitosprocreg
         select cancdebitosprocreg,
                cancdebitosproc,
                k21_sequencia ,
                k00_valor,
                k00_valor,
                0,
                0,
                0
         from w_debitos_apropriacao;

        ";

        DB::connection()->getPdo()->exec($sql);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        $sql = "

        select fc_startsession();
        select fc_putsession('DB_instit'     ,(select cast(codigo as text) from db_config where prefeitura is true limit 1));
        select fc_putsession('DB_id_usuario' ,'1');
        select fc_putsession('DB_login'      ,'dbseller');
        select fc_putsession('DB_debugon'    ,'true');
        select fc_putsession('DB_datausu'    ,cast(current_date as text));
        select fc_putsession('DB_anousu'     ,cast(extract(year from current_date) as text));
        select fc_putsession('DB_use_pcasp', 'true');

        insert into arrecad select k00_numpre,
                                   k00_numpar,
                                   k00_numcgm,
                                   k00_dtoper,
                                   k00_receit,
                                   k00_hist  ,
                                   k00_valor ,
                                   k00_dtvenc,
                                   k00_numtot,
                                   k00_numdig,
                                   k00_tipo  ,
                                   k00_tipojm
            from w_debitos_apropriacao;

     delete from arrecant where k00_numpre in (select k00_numpre from w_debitos_apropriacao);
     delete from cancdebitosprocreg where k24_sequencia in (select cancdebitosprocreg from w_debitos_apropriacao);
     delete from cancdebitosreg where k21_sequencia in (select k21_sequencia from w_debitos_apropriacao);
     delete from cancdebitosproc where k23_codigo in (select cancdebitosproc from w_debitos_apropriacao);
     delete from cancdebitos where k20_codigo in (select k20_codigo from w_debitos_apropriacao);

     drop table w_debitos_apropriacao;

        ";

        DB::connection()->getPdo()->exec($sql);
    }
}
