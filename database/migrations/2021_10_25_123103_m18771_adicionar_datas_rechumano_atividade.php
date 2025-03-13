<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M18771AdicionarDatasRechumanoAtividade extends Migration
{
    public function up()
    {
        $this->upDicionario();

        DB::statement("alter table escola.rechumanoativ add column ed22_datainicio date;");
        DB::statement("alter table escola.rechumanoativ add column ed22_datafim date;");
        DB::statement("alter table escola.rechumanoativ add column ed22_turno integer;");
        DB::statement("update rechumanoativ
                set ed22_datainicio = ed75_d_ingresso,
                    ed22_datafim = ed75_i_saidaescola
                    from rechumanoescola
                where ed22_i_rechumanoescola = ed75_i_codigo;");

        DB::statement("alter table escola.rechumanoativ alter column ed22_datainicio set not null;");

        $this->divideAtividadesPorTurno();
    }

    public function down()
    {
        $this->downDicionario();

            DB::statement("alter table escola.rechumanoativ drop column ed22_datainicio;");
            DB::statement("alter table escola.rechumanoativ drop column ed22_datafim;");
            DB::statement("alter table escola.rechumanoativ drop column ed22_turno;");
    }

    public function upDicionario()
    {
        DB::statement("insert into db_syscampo values(1013382,'ed22_datainicio','date','Guarda a Data de Início da Função/Atividade exercida pelo Profissional Escola','null', 'Data de Início',10,'f','f','f',0,'text','Data de Início');");
        DB::statement("insert into db_syscampo values(1013383,'ed22_datafim','date','Guarda a Data de Fim de vigência da Função/Atividade exercida pelo Profissional Escola','null', 'Até',10,'t','f','f',0,'text','Até');");
        DB::statement("insert into db_syscampo values(1013384,'ed22_turno','int4','Turno da Função exercída pelo rechumano na atividade e no periodo','0', 'Turno',10,'f','f','f',1,'text','Turno');");

        DB::statement("insert into db_sysarqcamp values(1010096,1013382,9,0);");
        DB::statement("insert into db_sysarqcamp values(1010096,1013383,10,0);");
        DB::statement("insert into db_sysarqcamp values(1010096,1013384,11,0);");
    }

    public function downDicionario()
    {
        DB::statement("delete from db_sysarqcamp where codcam in (1013382, 1013383, 1013384);");
        DB::statement("delete from db_syscampo where codcam in (1013382, 1013383, 1013384);");
    }

    private function divideAtividadesPorTurno()
    {
        DB::statement("with turnos_atividades as (
                select distinct on (ed22_i_codigo) ed22_i_codigo, ed129_turno
                from rechumanoescola
                         join rechumanoativ ON rechumanoativ.ed22_i_rechumanoescola = rechumanoescola.ed75_i_codigo
                         join agendaatividade ON agendaatividade.ed129_rechumanoativ = rechumanoativ.ed22_i_codigo
                order by ed22_i_codigo, ed129_turno asc
            ) update rechumanoativ set ed22_turno = turnos_atividades.ed129_turno
                from turnos_atividades where rechumanoativ.ed22_i_codigo = turnos_atividades.ed22_i_codigo;");

        DB::statement("create temp table w_dados_rechumanoativ as
            with rechumanoativ_duplicar as (
                select distinct ed22_i_codigo as codigo, ed129_turno as turno
                from rechumanoescola
                         join rechumanoativ ON rechumanoativ.ed22_i_rechumanoescola = rechumanoescola.ed75_i_codigo
                         join agendaatividade ON agendaatividade.ed129_rechumanoativ = rechumanoativ.ed22_i_codigo
                    and rechumanoativ.ed22_turno <> agendaatividade.ed129_turno
            ), dados_inserir as (
                select nextval('rechumanoativ_ed22_i_codigo_seq') as sequencial,
                       ed22_i_codigo as codigo,
                       ed22_i_rechumanoescola,
                       ed22_i_atividade,
                       ed22_i_horasmanha,
                       ed22_i_horastarde,
                       ed22_i_horasnoite,
                       ed22_i_atolegal,
                       ed22_ativo,
                       ed22_datainicio,
                       ed22_datafim,
                       turno
                from rechumanoativ_duplicar
                    join rechumanoativ on codigo = ed22_i_codigo
            ) select * from dados_inserir;");

        DB::statement("insert into rechumanoativ
                select  sequencial,
                        ed22_i_rechumanoescola,
                        ed22_i_atividade,
                        ed22_i_horasmanha,
                        ed22_i_horastarde,
                        ed22_i_horasnoite,
                        ed22_i_atolegal,
                        ed22_ativo,
                        ed22_datainicio,
                        ed22_datafim,
                        turno
                    from w_dados_rechumanoativ;");

        DB::statement("with atualizar_agenda as (
                select ed129_codigo, sequencial from w_dados_rechumanoativ join agendaatividade on codigo = ed129_rechumanoativ and ed129_turno = turno
            )
            update agendaatividade set ed129_rechumanoativ = atualizar_agenda.sequencial
                from atualizar_agenda where agendaatividade.ed129_codigo = atualizar_agenda.ed129_codigo;");
    }
}
