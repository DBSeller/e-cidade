<?php

use Classes\PostgresMigration;

class M9980TaxaLimpezaSapiranga2017 extends PostgresMigration
{
    public function up()
    {
        $sCalCuloLimpeza = <<<EOL
drop function if exists fc_iptu_taxalimpeza_sap_2017 (integer, numeric, integer, numeric, numeric, boolean);
create or replace function fc_iptu_taxalimpeza_sap_2017 (integer, numeric, integer, numeric, numeric, boolean) returns boolean as
$$
declare

    iReceita       alias for $1;
    iAliquota      alias for $2;
    iHistCalc      alias for $3;
    iPercIsen      alias for $4;
    nValpar        alias for $5;
    lRaise         alias for $6;

    nValTaxa       numeric(15,2) default 0;
    nValorBase     numeric(15,2) default 0;
    nTotAreaConstr numeric       default 0;
    nTotAreaConstrNaoResidencial numeric       default 0;

    iZona          integer       default 0;
    iIdbql         integer       default 0;
    iAnousu        integer       default 0;
    iMatric        integer       default 0;

    lValorBaseDuplicado  boolean       default false;

    tSql           text          default '';

begin

    lRaise := true;

    perform fc_debug(' <iptu_taxalimpeza> Calculando taxa de limpeza', lRaise);
    perform fc_debug(' <iptu_taxalimpeza> receita: '   || iReceita, lRaise);
    perform fc_debug(' <iptu_taxalimpeza> aliq: '      || iAliquota, lRaise);
    perform fc_debug(' <iptu_taxalimpeza> historico: ' || iHistCalc, lRaise);

    select matric
      into iMatric
      from tmpdadostaxa limit 1;


  select idbql, anousu
    into iIdbql, iAnousu
  from tmpdadostaxa limit 1;
  perform fc_debug(' <iptu_taxalimpeza> iMatric: ' || iMatric, lRaise);

  select j34_zona
  into iZona
  from lote
  where j34_idbql = iIdbql;

  select j57_valor
  into nValorBase
  from zonastaxa
  where j57_zona   = iZona
  and j57_receit = iReceita
  and j57_anousu = iAnousu;

select coalesce(SUM(j39_area),0)
  into nTotAreaConstrNaoResidencial
from carconstr
  inner join iptuconstr on j39_matric = j48_matric
                           and j39_idcons = j48_idcons
  inner join caracter   on j48_caract = j31_codigo
  inner join cargrup    on j31_grupo  = j32_grupo
where j48_matric = iMatric
      and j39_dtdemo is null
      and j48_caract in (21, 20, 212);

perform fc_debug(' <iptu_taxalimpeza> nTotAreaConstrNaoResidencial: ' || nTotAreaConstrNaoResidencial, lRaise);

  -- se area comerciais, industriais e ou servicos construida > 100 dobra o valor da taxa de limpeza
  if nTotAreaConstrNaoResidencial > 100::numeric then
    nValorBase := (nValorBase*2)::numeric;
    lValorBaseDuplicado := true;
  end if;

  perform fc_debug(' <iptu_taxalimpeza> lValorBaseDuplicado: ' || lValorBaseDuplicado, lRaise);
  perform fc_debug(' <iptu_taxalimpeza> nValorBase: ' || nValorBase, lRaise);

  select sum(j39_area)
  into nTotAreaConstr
  from iptuconstr
  where j39_matric = iMatric
        and j39_dtdemo is null;

  perform fc_debug(' <iptu_taxalimpeza> nTotAreaConstr: ' || nTotAreaConstr, lRaise);

		nValTaxa := nValorBase;

    if nValTaxa is null then
		   return false;
		end if;

  	insert into tmptaxapercisen values (iReceita,iPercIsen,0,nValTaxa);

		if iPercIsen > 0 then
      nValTaxa := nValTaxa * (100 - iPercIsen) / 100;
    end if;

    perform fc_debug(' <iptu_taxalimpeza> Percentual Isencao: ' || iPercIsen, lRaise);
    perform fc_debug(' <iptu_taxalimpeza> Valor final da taxa: ' || nValTaxa, lRaise);

    tSql := 'insert into tmprecval values ('||iReceita||','||nValTaxa||','||iHistCalc||',true)';
    execute tSql;

    return true;
end;

$$ language 'plpgsql';

EOL;
        $this->execute($sCalCuloLimpeza);
    }

    public function down()
    {
        $sCalculoLimpezaAnterior = <<<EOL
drop function if exists fc_iptu_taxalimpeza_sap_2017 (integer, numeric, integer, numeric, numeric, boolean);
create or replace function fc_iptu_taxalimpeza_sap_2017 (integer, numeric, integer, numeric, numeric, boolean) returns boolean as
$$
declare

    iReceita       alias for $1;
    iAliquota      alias for $2;
    iHistCalc      alias for $3;
    iPercIsen      alias for $4;
    nValpar        alias for $5;
    lRaise         alias for $6;

    nValTaxa       numeric(15,2) default 0;
    nValorBase     numeric(15,2) default 0;
    nTotAreaConstr numeric       default 0;

    iZona          integer       default 0;
    iIdbql         integer       default 0;
    iAnousu        integer       default 0;
    iMatric        integer       default 0;

    lValorBaseDuplicado  boolean       default false;

    tSql           text          default '';

begin

    lRaise := true;

    perform fc_debug(' <iptu_taxalimpeza> Calculando taxa de limpeza', lRaise);
    perform fc_debug(' <iptu_taxalimpeza> receita: '   || iReceita, lRaise);
    perform fc_debug(' <iptu_taxalimpeza> aliq: '      || iAliquota, lRaise);
    perform fc_debug(' <iptu_taxalimpeza> historico: ' || iHistCalc, lRaise);

    select matric
      into iMatric
      from tmpdadosiptu limit 1;

    perform *
      from carconstr
           inner join iptuconstr on j39_matric = j48_matric
                                and j39_idcons = j48_idcons
           inner join caracter   on j48_caract = j31_codigo
           inner join cargrup    on j31_grupo  = j32_grupo
     where j48_matric = iMatric
       and j39_dtdemo is null
       and j48_caract in (21, 20, 212);
    if found then
      lValorBaseDuplicado := true;
    end if;

    perform fc_debug(' <iptu_taxalimpeza> lValorBaseDuplicado: ' || lValorBaseDuplicado, lRaise);

    select idbql, anousu
		  into iIdbql, iAnousu
			from tmpdadostaxa limit 1;

		select j34_zona
		  into iZona
		  from lote
	   where j34_idbql = iIdbql;

    select j57_valor
		  into nValorBase
      from zonastaxa
		 where j57_zona   = iZona
		   and j57_receit = iReceita
			 and j57_anousu = iAnousu;

    perform fc_debug(' <iptu_taxalimpeza> nValorBase: ' || nValorBase, lRaise);

    select sum(j39_area)
      into nTotAreaConstr
      from iptuconstr
     where j39_matric = iMatric
       and j39_dtdemo is null;

    perform fc_debug(' <iptu_taxalimpeza> nTotAreaConstr: ' || nTotAreaConstr, lRaise);

    -- se comercio e area construida > 100 dobra o valor da taxa de limpeza
		if nTotAreaConstr > 100::numeric and lValorBaseDuplicado  is true then
		  nValorBase := (nValorBase*2)::numeric;
		end if;

		nValTaxa := nValorBase;

    if nValTaxa is null then
		   return false;
		end if;

  	insert into tmptaxapercisen values (iReceita,iPercIsen,0,nValTaxa);

		if iPercIsen > 0 then
      nValTaxa := nValTaxa * (100 - iPercIsen) / 100;
    end if;

    perform fc_debug(' <iptu_taxalimpeza> Percentual Isencao: ' || iPercIsen, lRaise);
    perform fc_debug(' <iptu_taxalimpeza> Valor final da taxa: ' || nValTaxa, lRaise);

    tSql := 'insert into tmprecval values ('||iReceita||','||nValTaxa||','||iHistCalc||',true)';
    execute tSql;

    return true;
end;

$$ language 'plpgsql';

EOL;
        $this->execute($sCalculoLimpezaAnterior);
    }
}
