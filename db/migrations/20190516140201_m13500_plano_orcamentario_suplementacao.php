<?php

use Classes\PostgresMigration;

class M13500PlanoOrcamentarioSuplementacao extends PostgresMigration
{

    public function up()
    {

        $this->execute(<<<SQL_UP

insert into db_syscampo values(1010476,'o47_planoorcamentariolinhapacto','int4','Linha de Pacto','0', 'Linha de Pacto',10,'t','f','f',1,'text','Linha de Pacto');
insert into db_sysarqcamp values(787,1010476,6,0);

delete from db_sysforkey where codarq = 787 and referen = 0;
insert into db_sysforkey values(787,1010476,1,1010346,0);

insert into db_sysindices values(1008458,'orcsuplemval_planoorcamentariolinhapacto_in',787,'0');
insert into db_syscadind values(1008458,1010476,1);

alter table orcsuplemval add column o47_planoorcamentariolinhapacto integer default null;
alter table orcsuplemval add constraint orcsuplemval_planoorcamentariolinhapacto_fk foreign key (o47_planoorcamentariolinhapacto) references planoorcamentariolinhapacto(o156_sequencial);
create index orcsuplemval_planoorcamentariolinhapacto_in on orcsuplemval(o47_planoorcamentariolinhapacto);

create or replace function fc_orcsuplemval_po_inc_alt_del() returns trigger as
$$
declare

    tipo        record;
    dataSessao  date;
    exclusao    boolean default false;
    valor       numeric default 0;
    tipoMovimentacao integer default 2;

begin

    dataSessao := cast( fc_getsession('DB_datausu') as date);
    if dataSessao is null then
    raise exception 'Data da sessão não encontrada.';
    end if;

    if TG_OP = 'DELETE' then
        tipo := old;
        exclusao := true;
    else
        tipo := new;
    end if;

    if tipo.o47_valor > 0 then
        return tipo;
    end if;

    /**
   * quando o valor é negativo, estamos reduzindo aquela dotacao.
     * Logo o tipo da movimentacao deve ser 1
     */
    if tipo.o47_valor < 0 then
        tipoMovimentacao = 1;

    end if;
    
    if TG_OP = 'UPDATE' THEN
        if old.o47_planoorcamentariolinhapacto is not null then
            if tipoMovimentacao = 1 then
                tipoMovimentacao = 5;
            end if;
            perform fc_atualiza_saldo_po(old.o47_planoorcamentariolinhapacto, tipoMovimentacao, dataSessao, cast((old.o47_valor) as numeric), true);
        end if;
    end if;

    if tipo.o47_planoorcamentariolinhapacto is not null then
        if tipo.o47_valor < 0 then
            tipoMovimentacao := 1;
        end if;
        perform fc_atualiza_saldo_po(tipo.o47_planoorcamentariolinhapacto, tipoMovimentacao, dataSessao, cast(abs(tipo.o47_valor) as numeric), exclusao);
    end if;

    return tipo;

end;
$$
    language 'plpgsql';

drop trigger if exists fc_orcsuplemval_po_inc_alt_del on orcsuplemval;
create trigger fc_orcsuplemval_po_inc_alt_del after INSERT OR UPDATE OR DELETE on orcsuplemval for each row execute procedure fc_orcsuplemval_po_inc_alt_del();

SQL_UP
);
    }

    public function down()
    {

        $this->execute(<<<SQL_DOWN

delete from db_sysarqcamp where codarq = 787 and codcam = 1010476;
delete from db_sysforkey where codarq = 787 and codcam = 1010476;
delete from db_syscadind where codind = 1008458;
delete from db_sysindices where codind = 1008458;
delete from db_syscampo where codcam = 1010476;

alter table orcsuplemval drop column o47_planoorcamentariolinhapacto;

drop trigger if exists fc_orcsuplemval_po_inc_alt_del on orcsuplemval;

SQL_DOWN
);
    }
}

