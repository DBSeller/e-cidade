<?php

use Classes\PostgresMigration;

class M16858EmissaoHonorariosTaxas extends PostgresMigration
{
    public function up()
    {


$sSql = "

insert into db_sysarquivo values
  (1010629, 'processoforodebitos', 'Valores do débitos no momento do ajuizamento', 'v91', '2020-11-10', 'Débitos do ajuizamento', 0, 'f', 'f', 'f', 'f' )
     ON CONFLICT ON CONSTRAINT db_sysarquivo_coda_pk DO NOTHING;
insert into db_sysarqmod values (21,1010629) ON CONFLICT ON CONSTRAINT db_sysarqmod_codm_coda_pk DO NOTHING;
insert into db_syscampo values(1011882,'v91_processoforo','int4','Processo Foro','0', 'Processo Foro',10,'f','f','f',1,'text','Processo Foro')
  ON CONFLICT ON CONSTRAINT db_syscampo_codc_pk DO NOTHING;
insert into db_syscampo values(1011883,'v91_inicial','int4','Inicial','0', 'Inicial',10,'f','f','f',1,'text','Inicial') ON CONFLICT ON CONSTRAINT db_syscampo_codc_pk DO NOTHING;
insert into db_syscampo values(1011884,'v91_numpre','int4','Numpre','0', 'Numpre',10,'f','f','f',1,'text','Numpre') ON CONFLICT ON CONSTRAINT db_syscampo_codc_pk DO NOTHING;
insert into db_syscampo values(1011885,'v91_numpar','int4','Número Parcela','0', 'Número Parcela',10,'f','f','f',1,'text','Número Parcela') ON CONFLICT ON CONSTRAINT db_syscampo_codc_pk DO NOTHING;
insert into db_syscampo values(1011886,'v91_receit','int4','Receita','0', 'Receita',4,'f','f','f',1,'text','Receita') ON CONFLICT ON CONSTRAINT db_syscampo_codc_pk DO NOTHING;
insert into db_syscampo values(1011887,'v91_dtoper','date','Data Operação','null', 'Data Operação',10,'f','f','f',1,'text','Data Operação') ON CONFLICT ON CONSTRAINT db_syscampo_codc_pk DO NOTHING;
insert into db_syscampo values(1011888,'v91_dtvenc','date','Data Vencimento','null', 'Data Vencimento',10,'f','f','f',1,'text','Data Vencimento') ON CONFLICT ON CONSTRAINT db_syscampo_codc_pk DO NOTHING;
insert into db_syscampo values(1011889,'v91_vlrhist','float4','Valor Histórico','0', 'Valor Histórico',10,'f','f','f',4,'text','Valor Histórico') ON CONFLICT ON CONSTRAINT db_syscampo_codc_pk DO NOTHING;
insert into db_sysarqcamp values(1010629,1011882,1,0) ON CONFLICT ON CONSTRAINT db_sysarqcamp_codc_coda_seqa_pk DO NOTHING;
insert into db_sysarqcamp values(1010629,1011883,2,0) ON CONFLICT ON CONSTRAINT db_sysarqcamp_codc_coda_seqa_pk DO NOTHING;
insert into db_sysarqcamp values(1010629,1011884,3,0) ON CONFLICT ON CONSTRAINT db_sysarqcamp_codc_coda_seqa_pk DO NOTHING;
insert into db_sysarqcamp values(1010629,1011885,4,0) ON CONFLICT ON CONSTRAINT db_sysarqcamp_codc_coda_seqa_pk DO NOTHING;
insert into db_sysarqcamp values(1010629,1011886,5,0) ON CONFLICT ON CONSTRAINT db_sysarqcamp_codc_coda_seqa_pk DO NOTHING;
insert into db_sysarqcamp values(1010629,1011887,6,0) ON CONFLICT ON CONSTRAINT db_sysarqcamp_codc_coda_seqa_pk DO NOTHING;
insert into db_sysarqcamp values(1010629,1011888,7,0) ON CONFLICT ON CONSTRAINT db_sysarqcamp_codc_coda_seqa_pk DO NOTHING;
insert into db_sysarqcamp values(1010629,1011889,8,0) ON CONFLICT ON CONSTRAINT db_sysarqcamp_codc_coda_seqa_pk DO NOTHING;
insert into db_sysforkey values(1010629,1011882,1,3069,0) ON CONFLICT ON CONSTRAINT db_sysforkey_coda_codc_sequ_refe_pk DO NOTHING;
insert into db_sysforkey values(1010629,1011883,1,108,0) ON CONFLICT ON CONSTRAINT db_sysforkey_coda_codc_sequ_refe_pk DO NOTHING;

create table if not exists juridico.processoforodebitos(
    v91_processoforo integer,
    v91_inicial integer,
    v91_numpre  integer,
    v91_numpar  integer,
    v91_receit  integer,
    v91_dtoper  date,
    v91_dtvenc  date,
    v91_vlrhist double precision
);

alter table if exists juridico.processoforodebitos add constraint processoforodebitos_processoforo_fk foreign key (v91_processoforo) references processoforo;
alter table if exists juridico.processoforodebitos add constraint processoforodebitos_inicial_fk foreign key (v91_inicial) references inicial;


CREATE OR REPLACE FUNCTION public.fc_processoforodebito_inc()
 RETURNS trigger
 LANGUAGE plpgsql
AS \$function\$
        declare
            record_iniciais record;
    begin
                    
        for record_iniciais in
            select
                arrecad.k00_numpre as v91_numpre,
                arrecad.k00_numpar as v91_numpar,
                arrecad.k00_receit as v91_receit,
                arrecad.k00_dtoper as v91_dtoper,
                arrecad.k00_dtvenc as v91_dtvenc,
                arrecad.k00_valor  as v91_vlrhist
            from
                inicialnumpre
                inner join arrecad on k00_numpre = v59_numpre
            where
                v59_inicial = new.v71_inicial
        loop    
            
            insert into 
                juridico.processoforodebitos 
                ( 
                     v91_processoforo 
                    ,v91_inicial 
                    ,v91_numpre 
                    ,v91_numpar 
                    ,v91_receit 
                    ,v91_dtoper 
                    ,v91_dtvenc 
                    ,v91_vlrhist
                 )
                 values
                 (
                    new.v71_processoforo,
                    new.v71_inicial,
                    record_iniciais.v91_numpre,
                    record_iniciais.v91_numpar,
                    record_iniciais.v91_receit,
                    record_iniciais.v91_dtoper,
                    record_iniciais.v91_dtvenc,
                    record_iniciais.v91_vlrhist

                 );

        end loop;
    return new;
END;
\$function\$;

DROP TRIGGER IF EXISTS tg_processoforodebito_inc ON juridico.processoforoinicial;
CREATE TRIGGER tg_processoforodebito_inc AFTER INSERT ON juridico.processoforoinicial FOR EACH ROW EXECUTE PROCEDURE public.fc_processoforodebito_inc();

CREATE OR REPLACE FUNCTION public.fc_processoforodebito_exc()
 RETURNS trigger
 LANGUAGE plpgsql
AS \$function\$
        declare
    begin
    delete from juridico.processoforodebitos where v91_inicial = old.v71_inicial;
    
    return old;
END;
\$function\$;

DROP TRIGGER IF EXISTS tg_processoforodebito_exc ON juridico.processoforoinicial;
CREATE TRIGGER tg_processoforodebito_exc BEFORE DELETE ON juridico.processoforoinicial FOR EACH ROW EXECUTE PROCEDURE public.fc_processoforodebito_exc();


";



        $this->execute($sSql );

    }

    public function down()
    {


        $sSql = "
        
        delete from db_sysforkey where codarq = 1010629;
        delete from db_sysarqcamp where codarq = 1010629;
        delete from db_sysarqcamp where codarq = 1010629;
        delete from db_syscampo where codcam in (1011882,1011883,1011884,1011885,1011886,1011887,1011888,1011889);
        delete from db_sysarqmod where codarq = 1010629;
        delete from db_sysarquivo where codarq = 1010629;
        drop function public.fc_processoforodebito_inc() CASCADE;
        drop function public.fc_processoforodebito_exc() CASCADE;
        drop table juridico.processoforodebitos;        
        ";
        $this->execute($sSql);        
    }
}
