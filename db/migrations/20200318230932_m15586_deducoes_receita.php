<?php

use Classes\PostgresMigration;

class M15586DeducoesReceita extends PostgresMigration
{
    public function down()
    {
    }

    public function up()
    {
        $this->execute(<<<SQL_UP


create or replace function fc_buscaReceitaDeducao(integer,integer,integer)
    returns integer
as $$
declare

    iCodigoReceita alias for $1;
    iAnousu        alias for $2;
    iInstit        alias for $3;

    iCodigoReceitaDeducao integer default 0;
    sEstruturalReceita    varchar;
    sEstruturalDeducao    varchar;

    codigo_deducao integer;

begin

    select k02_estorc
    into sEstruturalReceita
    from taborc
    where k02_codigo = iCodigoReceita
      and k02_anousu = iAnousu;

    sEstruturalDeducao := '9' || substr(sEstruturalReceita, 2, length(sEstruturalReceita));

    select k164_taborcdeducao
      into iCodigoReceitaDeducao
      from taborcvinculodeducao
     where k164_taborcprincipal = iCodigoReceita
       and k164_anousu          = iAnousu ;

    if iCodigoReceitaDeducao is null then

        select k02_codigo
          into codigo_deducao
          from taborc
         where k02_estorc = sEstruturalDeducao
           and k02_anousu = iAnousu limit 1;
        if (codigo_deducao is null) then
            raise exception 'Receita de deducao nao encontrada na tesouraria para o ano de %. Estrutural receita : %, estrutural receita deducao : % (CODREC %)', iAnousu,sEstruturalReceita,sEstruturalDeducao, iCodigoReceita;
        end if;

        insert into taborcvinculodeducao values (
             nextval('taborcvinculodeducao_k164_sequencial_seq'),
             iCodigoReceita,
             codigo_deducao,
             iAnousu
         );
        iCodigoReceitaDeducao := codigo_deducao;

    end if;
    return iCodigoReceitaDeducao;

end;
$$ language 'plpgsql';





SQL_UP
);
    }
}
