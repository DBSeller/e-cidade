<?php

use Classes\PostgresMigration;

class M9790GetfatorglebaOsorio extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<FUNCAO
create or replace function fc_iptu_getfatorgleba_osorio_2018(integer, numeric, boolean,
                                                              OUT rnFatorGleba numeric,
                                                              OUT rlErro       boolean,
                                                              OUT riCodigoErro integer,
                                                              OUT rtTextoErro  text) returns record as
$$
declare

    iIdbql          alias for $1;
    nAreaTerreno    alias for $2;
    lRaise          alias for $3;

    nTestada        numeric default 0;

begin
    perform fc_debug('', lRaise);
    perform fc_debug('* <fc_iptu_getfatorgleba_osorio_2018> INICIANDO CALCULO DO FATOR GLEBA', lRaise);

    rnFatorGleba := 0;
    rlErro       := false;
    riCodigoErro := 0;
    rtTextoErro  := '';


    perform fc_debug('<fc_iptu_getfatorgleba_osorio_2018> Area fracionada: ' || nAreaTerreno, lRaise);

    if nAreaTerreno is null or nAreaTerreno <= 0 then
        riCodigoErro := 3;
        rlErro       := true;
        return;
    end if;

    if nAreaTerreno <= 3000 then
        rnFatorGleba := 1;

        perform fc_debug('<fc_iptu_getfatorgleba_osorio_2018> Fator gleba: ' || rnFatorGleba, lRaise);
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

    perform fc_debug('<fc_iptu_getfatorgleba_osorio_2018> Valor testada: ' || nTestada, lRaise);

    if nTestada is null or nTestada = 0 then
        riCodigoErro := 6;
        rlErro       := true;
        return;
    end if;

    riCodigoErro := 1;
    rnFatorGleba := 10 * power(nAreaTerreno, -0.42) * power(nTestada, 0.16);

    perform fc_debug('<fc_iptu_getfatorgleba_osorio_2018> Fator gleba: ' || rnFatorGleba, lRaise);
    return;

end;
$$  language 'plpgsql';
FUNCAO;

        $this->execute($sSql);
    }

    public function down()
    {
        $this->execute("drop function fc_iptu_getfatorgleba_osorio_2018(integer, numeric, boolean);");
    }
}
