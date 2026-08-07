<?php

use Classes\PostgresMigration;

class M9790GetfatoridadeedificacaoOsorio extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<FUNCAO
create or replace function fc_iptu_getfatoridadeedificacao_osorio_2018 (iMatricula    integer,
                                                                        iIdContrucao  integer,
                                                                        iAnousu       integer,
                                                                        lRaise        boolean,

                                                                   OUT nFatorIdadeEdificacao numeric,
                                                                   OUT rlErro                boolean,
                                                                   OUT riCodErro             integer,
                                                                   OUT rtErro                text) returns record as
$$
declare
  iMatricula            alias for $1;
  iIdContrucao          alias for $2;
  iAnousu               alias for $3;
  lRaise                alias for $4;

  iAnoConstrucao   integer default 0;
  iIdadeConstrucao integer default 0;

begin

  nFatorIdadeEdificacao := 1;
  rlErro                := false;
  riCodErro             := 0;
  rtErro                := '';

  select 
      case when char_length(j39_ano::varchar) = 4 
           then j39_ano 
           else null 
      end as j39_ano
    into iAnoConstrucao
    from iptuconstr
   where j39_idcons = iIdContrucao
     and j39_matric = iMatricula;

  if iAnoConstrucao is null then

    rlErro    := true;
    riCodErro := 116;
    rtErro    := '';
    return;
  end if;

  iIdadeConstrucao := iAnousu - iAnoConstrucao;

  if iIdadeConstrucao < 0 then

    rlErro    := true;
    riCodErro := 116;
    rtErro    := '';
    return;
  end if;

  if iIdadeConstrucao >= 6 and iIdadeConstrucao <= 10 then
    nFatorIdadeEdificacao := 0.95;
  end if;

  if iIdadeConstrucao >= 11 and iIdadeConstrucao <= 15 then
    nFatorIdadeEdificacao := 0.90;
  end if;

  if iIdadeConstrucao >= 16 and iIdadeConstrucao <= 20 then
    nFatorIdadeEdificacao := 0.85;
  end if;

  if iIdadeConstrucao >= 21 and iIdadeConstrucao <= 25 then
    nFatorIdadeEdificacao := 0.80;
  end if;

  if iIdadeConstrucao >= 26 and iIdadeConstrucao <= 30 then
    nFatorIdadeEdificacao := 0.75;
  end if;

  if iIdadeConstrucao >= 31 and iIdadeConstrucao <= 35 then
    nFatorIdadeEdificacao := 0.70;
  end if;

  if iIdadeConstrucao >= 36 and iIdadeConstrucao <= 40 then
    nFatorIdadeEdificacao := 0.65;
  end if;

  if iIdadeConstrucao >= 41 and iIdadeConstrucao <= 45 then
    nFatorIdadeEdificacao := 0.60;
  end if;

  if iIdadeConstrucao >= 46 and iIdadeConstrucao <= 50 then
    nFatorIdadeEdificacao := 0.55;
  end if;

  if iIdadeConstrucao > 50 then
    nFatorIdadeEdificacao := 0.50;
  end if;

  perform fc_debug(' <fc_iptu_getfatoridadeedificacao_osorio_2018> Buscando fator da idade da construcao:',      lRaise);
  perform fc_debug(' <fc_iptu_getfatoridadeedificacao_osorio_2018> iMatricula      : ' || iMatricula,            lRaise);
  perform fc_debug(' <fc_iptu_getfatoridadeedificacao_osorio_2018> iIdContrucao    : ' || iIdContrucao,          lRaise);
  perform fc_debug(' <fc_iptu_getfatoridadeedificacao_osorio_2018> Anousu          : ' || iAnousu,               lRaise);
  perform fc_debug(' <fc_iptu_getfatoridadeedificacao_osorio_2018> iAnoConstrucao  : ' || iAnoConstrucao,        lRaise);
  perform fc_debug(' <fc_iptu_getfatoridadeedificacao_osorio_2018> iIdadeConstrucao: ' || iIdadeConstrucao,      lRaise);
  perform fc_debug(' <fc_iptu_getfatoridadeedificacao_osorio_2018> Valor Retornado : ' || nFatorIdadeEdificacao, lRaise);
  perform fc_debug('', lRaise);

end;
$$  language 'plpgsql';
FUNCAO;

        $this->execute($sSql);
    }

    public function down()
    {
        $this->execute("drop function fc_iptu_getfatoridadeedificacao_osorio_2018(integer, integer, integer, boolean);");
    }
}
