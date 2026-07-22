<?php

use Classes\PostgresMigration;

class M9790GetfatorprofundidadeOsorio extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<FUNCAO
create or replace function fc_iptu_getfatorprofundidade_osorio_2018(integer, numeric, boolean,
                                                              OUT rnFatorProfundidade numeric,
                                                              OUT rlErro              boolean,
                                                              OUT riCodigoErro        integer,
                                                              OUT rtTextoErro         text ) returns record as
$$
declare

  iIdbql            alias for $1;
  nAreaTerreno      alias for $2;
  lRaise            alias for $3;

  nTestada                numeric;
  nProfundidadeMedia      numeric;

begin
    perform fc_debug('', lRaise);
    perform fc_debug('* <fc_iptu_getfatorprofundidade_osorio_2018> INICIANDO CALCULO DO FATOR CORRETIVO DE PROFUNDIDADE', lRaise);

    rnFatorProfundidade := 0;
    rlErro              := false;
    riCodigoErro        := 1;
    rtTextoErro         := '';

    perform fc_debug('<fc_iptu_getfatorprofundidade_osorio_2018> Area do terreno: ' || nAreaTerreno, lRaise);

    if nAreaTerreno is null or nAreaTerreno = 0 then
        riCodigoErro := 3;
        rlErro       := true;
        return;
    end if;

    select case
           when j36_testle > 0 then
             j36_testle
           else
             j36_testad
         end as testada
    into nTestada
    from testada
    where j36_idbql = iIdbql;

    perform fc_debug('<fc_iptu_getfatorprofundidade_osorio_2018> Testada: ' || nTestada, lRaise);

    if nTestada is null or nTestada = 0 then
        riCodigoErro := 6;
        rlErro       := true;
        return;
    end if;

    nProfundidadeMedia := nAreaTerreno / nTestada;

    perform fc_debug('<fc_iptu_getfatorprofundidade_osorio_2018> Profundidade média: ' || nProfundidadeMedia, lRaise);

    if nProfundidadeMedia < 30 then
        rnFatorProfundidade := sqrt(nProfundidadeMedia) / 30;
    elseif nProfundidadeMedia >= 30 and nProfundidadeMedia <= 40 then
        rnFatorProfundidade := 1;
    elseif nProfundidadeMedia > 40 and nProfundidadeMedia <= 120 then
        rnFatorProfundidade := 40 / nProfundidadeMedia;
    elseif nProfundidadeMedia > 120 then
        rnFatorProfundidade := 0.58;
    end if;

    perform fc_debug('<fc_iptu_getfatorprofundidade_osorio_2018> Fator profundida: ' || rnFatorProfundidade, lRaise);
    return;

end;
$$  language 'plpgsql';
FUNCAO;

        $this->execute($sSql);
    }

    public function down()
    {
        $this->execute("drop function fc_iptu_getfatorprofundidade_osorio_2018(integer, numeric, boolean);");
    }
}
