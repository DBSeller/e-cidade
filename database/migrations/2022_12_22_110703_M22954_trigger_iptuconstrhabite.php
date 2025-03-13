<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22954TriggerIptuconstrhabite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL

CREATE OR REPLACE FUNCTION cadastro.fc_iptuconstrhabite_inc_alt_exc()
 RETURNS trigger
 LANGUAGE plpgsql
AS \$function\$

    declare

        sLog              text default '';
        sLogAux           text default '';

        sAnos             varchar;

        iMatric           integer;
        iCodigoOcorrencia integer;

        bCampoProcesso    boolean;

    begin

         select fc_getsession('DB_anoretroativo') into sAnos;

         if sAnos is null then
            select fc_getsession('DB_anousu') into sAnos;
         end if;

        if TG_OP = 'INSERT' then
           sLog    = 'NO(S) EXERCICIO(S) DE '||sAnos||' HABITE-SE INCLUÍDO: ';
           iMatric = new.j131_matric;
        elseif TG_OP = 'UPDATE' then
           sLog    = 'NO(S) EXERCICIO(S) DE '||sAnos||' HABITE-SE ALTERADO: ';
           iMatric = new.j131_matric;
        elseif TG_OP = 'DELETE' then
           sLog    = 'NO(S) EXERCICIO(S) DE '||sAnos||' HABITE-SE EXCLUÍDO: ';
           iMatric = old.j131_matric;
        end if;

        sLogAux = sLog;

        if TG_OP = 'INSERT' then
            sLog = sLog || 'sequencia: ' || new.j131_sequencial || ' - ';
            if new.j131_idcons is not null then
               sLog = sLog || 'id: ' || new.j131_idcons || ' - ';
            end if;
            if new.j131_matric is not null then
               sLog = sLog || 'matricula: ' || new.j131_matric || (select j39_matric from iptuconstr where j39_matric = new.j131_matric and j39_idcons = new.j131_idcons) || ' - ';
            end if;
            if new.j131_usuario is not null then
               sLog = sLog || 'usuario: ' || new.j131_usuario || ' - ';
            end if;
            if new.j131_codprot is not null then
               sLog = sLog || 'codprot: ' || new.j131_codprot || ' - ';
            end if;
            if new.j131_cadhab is not null then
               sLog = sLog || 'habitacao: ' || new.j131_cadhab  || ' - ';
            end if;
            if new.j131_data is not null then
               sLog = sLog || 'data: ' || new.j131_data || ' - ';
            end if;
            if new.j131_hora is not null then
               sLog = sLog || 'hora: ' || new.j131_hora || ' - ';
            end if;
            if new.j131_obs  is not null then
               sLog = sLog || 'observacao: ' || new.j131_obs || ' - ';
            end if;
            if new.j131_dtprot is not null then
               sLog = sLog || 'data prot: ' || new.j131_dtprot || ' - ';
            end if;
            if new.j131_dthabite is not null then
               sLog = sLog || 'data habite: ' || new.j131_dthabite || ' - ';
            end if;
        elseif TG_OP = 'UPDATE' then
            if new.j131_sequencial  <> old.j131_sequencial  then
               sLog = sLog || 'sequencia alterada de: ' || old.j131_sequencial  || ' para: ' || new.j131_sequencial  || ' - ';
            end if;
            if new.j131_idcons <> old.j131_idcons then
               sLog = sLog || 'id alterada de: ' || old.j131_idcons || (select j39_idcons from iptuconstr where j39_idcons = new.j131_idcons);
               sLog = sLog || ' para: ' || new.j131_idcons || (select j39_idcons from iptuconstr where j39_idcons = new.j131_idcons) ||  ' - ';
            end if;
            if new.j131_matric <> old.j131_matric then
               sLog = sLog || 'matricula alterada de: ' || old.j131_matric || (select j39_matric from iptuconstr where j39_matric = new.j131_matric);
               sLog = sLog || ' para: ' || new.j131_matric || (select j39_matric from iptuconstr where j39_matric = new.j131_matric) || ' - ';
            end if;
            if new.j131_usuario <> old.j131_usuario then
               sLog = sLog || 'usuario alterado de: ' || old.j131_usuario || ' para: ' || new.j131_usuario || ' - ';
            end if;
            if new.j131_codprot <> old.j131_codprot then
               sLog = sLog || 'codprot: ' || new.j131_codprot || ' - ';
               sLog = sLog || 'codprot alterado de: ' || old.j39_codigo || ' para: ' || new.j131_codprot || ' - ';
            end if;
            if new.j131_cadhab <> old.j131_cadhab then
               sLog = sLog || 'cadhab alterado de: ' || old.j131_cadhab || ' para: ' || new.j131_cadhab || ' - ';
            end if;
            if new.j131_data <> old.j131_data then
               sLog = sLog || 'data alterado de: ' || old.j131_data || ' para: ' || new.j131_data || ' - ';
            end if;
            if new.j131_hora <> old.j131_hora then
               sLog = sLog || 'hora alterada de: ' || old.j131_hora || ' para: ' || new.j131_hora || ' - ';
            end if;
            if new.j131_obs <> old.j131_obs then
               sLog = sLog || 'observacao alterada de: ' || old.j131_obs || ' para: ' || new.j131_obs || ' - ';
            end if;
            if (new.j131_dtprot <> old.j131_dtprot) or
               (old.j131_dtprot is null and new.j131_dtprot is not null) then
               if old.j131_dtprot is null then
                  sLog = sLog || 'dataprot alterado de: EM BRANCO para: ' || new.j131_dtprot || ' - ';
               else
                  sLog = sLog || 'dataprot alterado de: ' || old.j131_dtprot || ' para: ' || new.j131_dtprot || ' - ';
               end if;
            end if;
            if (new.j131_dthabite <> old.j131_dthabite) or
               (old.j131_dthabite is null and new.j131_dthabite is not null) then
               if old.j131_dthabite is null then
                  sLog = sLog || 'data do habite-se alterada de: EM BRANCO para: ' || new.j131_dthabite || ' - ';
               else
                  sLog = sLog || 'data do habite-se alterada de: ' || old.j131_dthabite || ' para: ' || new.j131_dthabite || ' - ';
               end if;
            end if;
        elseif TG_OP = 'DELETE' then
            sLog = sLog || 'sequencia: ' || old.j131_sequencial || ' - ';
            if old.j131_idcons is not null then
               sLog = sLog || 'id: ' || old.j131_idcons || ' - ';
            end if;
            if old.j131_matric is not null then
               sLog = sLog || 'matricula: ' || old.j131_matric || ' - ';
            end if;
            if old.j131_usuario is not null then
               sLog = sLog || 'usuario: ' || old.j131_usuario || ' - ';
            end if;
            if old.j131_codprot is not null then
               sLog = sLog || 'codprot: ' || old.j131_codprot || ' - ';
            end if;
            if old.j131_cadhab is not null then
               sLog = sLog || 'habitacao: ' || old.j131_cadhab || ' - ';
            end if;
            if old.j131_data is not null then
               sLog = sLog || 'data: ' || old.j131_data || ' - ';
            end if;
            if old.j131_hora is not null then
               sLog = sLog || 'hora: ' || old.j131_hora || ' - ';
            end if;
            if old.j131_obs  is not null then
               sLog = sLog || 'observacao: ' || old.j131_obs || ' - ';
            end if;
            if old.j131_dtprot is not null then
               sLog = sLog || 'data prot: ' || old.j131_dtprot || ' - ';
            end if;
            if old.j131_dthabite is not null then
               sLog = sLog || 'data habite: ' || old.j131_dthabite || ' - ';
            end if;
        end if;

        if sLog <> sLogAux then
            SELECT nextval('histocorrencia_ar23_sequencial_seq') INTO iCodigoOcorrencia;
            insert into histocorrencia ( ar23_sequencial, ar23_id_usuario, ar23_instit, ar23_modulo, ar23_id_itensmenu,
                                         ar23_data, ar23_hora, ar23_tipo, ar23_descricao, ar23_ocorrencia )
                select  iCodigoOcorrencia, fc_getsession('DB_id_usuario')::integer, fc_getsession('DB_instit')::integer,
                        578, fc_getsession('DB_itemmenu_acessado')::integer, current_date, substr(current_time::varchar,1,5),
                        1, 'log de alteracoes', sLog;
            insert into histocorrenciamatric ( ar25_sequencial, ar25_matric, ar25_histocorrencia )
                select nextval('histocorrenciamatric_ar25_sequencial_seq'), iMatric, iCodigoOcorrencia;
            IF fc_getsession('PROCESSO_LOG') IS NOT NULL THEN
               insert into cadastro.histocorrenciaprocesso ( ar201_sequencial, ar201_processo, ar201_histocorrencia )
                    select nextval('cadastro.histocorrenciaprocesso_ar201_sequencial_seq'),fc_getsession('PROCESSO_LOG') , iCodigoOcorrencia;
            END IF;
        end if;

        if TG_OP = 'INSERT' then
           return new;
        elseif TG_OP = 'UPDATE' then
           return new;
        elseif TG_OP = 'DELETE' then
           return old;
        end if;
    end;
\$function\$;


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
        return true;
    }
}
