<?php

use Classes\PostgresMigration;

class M17221TriggerValidaEstornoDispensaDeBem extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            /**
            * Trigger que valida a última movimentação no estoque (data/hora)
            * @author Matheus Felini <matheus.felini@dbseller.com.br>
            */
            create or replace function fc_matestoqueinimeimovimentacao()
            returns trigger
            as $$
            declare
            
            dtAtual              date;
            dtUltimaMovimentacao date;
            
            /*
            * Data do processamento do fechamento do material
            */
            dtFechamentoMaterial date;
            lPermiteAlterar      boolean default true;
            iCodigoMaterial      integer;
            lServico             boolean;
            sDescricaoMaterial   varchar;
            sDataAnterior        varchar;
            sHoraAnterior        varchar;
            sDataAtual           varchar;
            sHoraAtual           varchar;

            begin
                
                lPermiteAlterar := (case when fc_getsession('DB_habilita_trigger_movimentacao_estoque') is null then true else false end);
                if not lPermiteAlterar then
                    return NEW;
                end if;
                
                select matmater.m60_codmater
                    into iCodigoMaterial
                    from matestoqueinimei
                        inner join matestoqueitem on matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem
                        inner join matestoque     on matestoque.m70_codigo      = matestoqueitem.m71_codmatestoque
                        inner join matmater       on matmater.m60_codmater      = matestoque.m70_codmatmater
                where matestoqueinimei.m82_matestoqueini = new.m82_matestoqueini 
                limit 1;


                    
                select m80_data, 
                    m60_codmater, 
                    m60_descr,
                    case when m71_servico = 't'
                        then true
                        else false
                    end as m71_servico
                into dtUltimaMovimentacao,
                    iCodigoMaterial,
                    sDescricaoMaterial,
                    lServico
                from matestoqueinimei
                    inner join matestoqueini  on matestoqueini.m80_codigo   = matestoqueinimei.m82_matestoqueini
                    inner join matestoqueitem on matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem
                    inner join matestoque     on matestoque.m70_codigo      = matestoqueitem.m71_codmatestoque
                    inner join matmater       on matmater.m60_codmater      = matestoque.m70_codmatmater
                    inner join bensdispensatombamento on bensdispensatombamento.e139_matestoqueitem = matestoqueitem.m71_codlanc
                        AND bensdispensatombamento.e139_matestoqueitem = matestoqueinimei.m82_matestoqueitem
                where matmater.m60_codmater   = iCodigoMaterial
                and matestoque.m70_coddepto = cast(fc_getsession('db_coddepto') as integer)
                order by m80_data desc limit 1;
                
                /**
                * Selecionamos a data atual do servidor para compararmos com a data do material
                */
                select fc_getsession('DB_datausu') 
                    into dtAtual;

                /**
                * Caso a última data seja superior a data atual, abortamos o processo.
                */
                if dtAtual < dtUltimaMovimentacao then
                    raise exception 'Data da ultima movimentação (%) superior a data da movimentação atual (%)',dtUltimaMovimentacao, dtAtual;
                end if;


                /**
                * Pegamos a data do último processamento do fechamento do material para a instituição que o usuário está logado.
                */
                select max(m05_data)
                into dtFechamentoMaterial
                from posicaoestoqueprocessamento
                where posicaoestoqueprocessamento.m05_instit = fc_getsession('DB_instit')::integer;

                if (lServico = 'f') and (dtFechamentoMaterial is not null and dtAtual <= dtFechamentoMaterial) then
                raise exception 'O material foi processado em %, não será possível realizar movimentação inferior a esta data.', dtFechamentoMaterial;
                end if;

                return new;
                
            end;
            $$ language 'plpgsql';

            drop   trigger if exists tg_matestoqueinimeimovimentacao_inc_alt on matestoqueinimei;
            create trigger tg_matestoqueinimeimovimentacao_inc_alt after INSERT or UPDATE on matestoqueinimei for each row execute procedure fc_matestoqueinimeimovimentacao();
SQL;

        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
            /**
            * Trigger que valida a última movimentação no estoque (data/hora)
            * @author Matheus Felini <matheus.felini@dbseller.com.br>
            */
            create or replace function fc_matestoqueinimeimovimentacao()
            returns trigger
            as $$
            declare
            
            dtAtual              date;
            dtUltimaMovimentacao date;
            
            /*
            * Data do processamento do fechamento do material
            */
            dtFechamentoMaterial date;
            lPermiteAlterar      boolean default true;
            iCodigoMaterial      integer;
            lServico             boolean;
            sDescricaoMaterial   varchar;
            sDataAnterior        varchar;
            sHoraAnterior        varchar;
            sDataAtual           varchar;
            sHoraAtual           varchar;

            begin
                
                lPermiteAlterar := (case when fc_getsession('DB_habilita_trigger_movimentacao_estoque') is null then true else false end);
                if not lPermiteAlterar then
                    return NEW;
                end if;
                
                select matmater.m60_codmater
                    into iCodigoMaterial
                    from matestoqueinimei
                        inner join matestoqueitem on matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem
                        inner join matestoque     on matestoque.m70_codigo      = matestoqueitem.m71_codmatestoque
                        inner join matmater       on matmater.m60_codmater      = matestoque.m70_codmatmater
                where matestoqueinimei.m82_matestoqueini = new.m82_matestoqueini 
                limit 1;


                    
                select m80_data, 
                    m60_codmater, 
                    m60_descr,
                    case when m71_servico = 't'
                        then true
                        else false
                    end as m71_servico
                into dtUltimaMovimentacao,
                    iCodigoMaterial,
                    sDescricaoMaterial,
                    lServico
                from matestoqueinimei
                    inner join matestoqueini  on matestoqueini.m80_codigo   = matestoqueinimei.m82_matestoqueini
                    inner join matestoqueitem on matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem
                    inner join matestoque     on matestoque.m70_codigo      = matestoqueitem.m71_codmatestoque
                    inner join matmater       on matmater.m60_codmater      = matestoque.m70_codmatmater
                where matmater.m60_codmater   = iCodigoMaterial
                and matestoque.m70_coddepto = cast(fc_getsession('db_coddepto') as integer)
                order by m80_data desc limit 1;
                
                /**
                * Selecionamos a data atual do servidor para compararmos com a data do material
                */
                select fc_getsession('DB_datausu') 
                    into dtAtual;

                /**
                * Caso a última data seja superior a data atual, abortamos o processo.
                */
                if dtAtual < dtUltimaMovimentacao then
                    raise exception 'Data da ultima movimentação (%) superior a data da movimentação atual (%)',dtUltimaMovimentacao, dtAtual;
                end if;


                /**
                * Pegamos a data do último processamento do fechamento do material para a instituição que o usuário está logado.
                */
                select max(m05_data)
                into dtFechamentoMaterial
                from posicaoestoqueprocessamento
                where posicaoestoqueprocessamento.m05_instit = fc_getsession('DB_instit')::integer;

                if (lServico = 'f') and (dtFechamentoMaterial is not null and dtAtual <= dtFechamentoMaterial) then
                raise exception 'O material foi processado em %, não será possível realizar movimentação inferior a esta data.', dtFechamentoMaterial;
                end if;

                return new;
                
            end;
            $$ language 'plpgsql';

            drop   trigger if exists tg_matestoqueinimeimovimentacao_inc_alt on matestoqueinimei;
            create trigger tg_matestoqueinimeimovimentacao_inc_alt after INSERT or UPDATE on matestoqueinimei for each row execute procedure fc_matestoqueinimeimovimentacao();
SQL;

        $this->execute($sql);
    }
}
