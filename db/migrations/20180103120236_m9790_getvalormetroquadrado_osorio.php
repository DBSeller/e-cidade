<?php

use Classes\PostgresMigration;

class M9790GetvalormetroquadradoOsorio extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<FUNCAO
create or replace function fc_iptu_getvalormetroquadrado_osorio_2018(integer, integer, boolean,
                                                              OUT rnValorMetroQuadrado numeric,
                                                              OUT rlErro               boolean,
                                                              OUT riCodigoErro         integer,
                                                              OUT rtTextoErro          text ) returns record as
$$
declare

    iIdbql         alias for $1;
    iAnousu        alias for $2;
    lRaise         alias for $3;

    nValorAntigo         numeric default null;
    nValorNovo           numeric default null;
    nPercentualExercicio numeric default null;

begin
    perform fc_debug('', lRaise);
    perform fc_debug('* <fc_iptu_getvalormetroquadrado_osorio_2018> INICIANDO CALCULO DO VALOR DO METRO QUADRADO ', lRaise);

    rnValorMetroQuadrado := 0;
    rlErro               := false;
    riCodigoErro         := 0;
    rtTextoErro          := '';

    select j81_valorterreno
    into nValorAntigo
    from lote
        inner join testpri   on j49_idbql  = j34_idbql
        inner join face      on j37_face   = j49_face
        inner join facevalor on j37_face   = j81_face
                            and j81_anousu = iAnousu - 1
    where j34_idbql = iIdbql;

    if nValorAntigo is null then
        rlErro := true;
        rtTextoErro  := 'VALOR DO EXERCÍCIO ANTERIOR NÃO ENCONTRADO.';
        riCodigoErro := 25;
        return;
    end if;

    perform fc_debug('<fc_iptu_getvalormetroquadrado_osorio_2018> Valor m2 do exercicio anterior: ' || nValorAntigo, lRaise);

    select j81_valorterreno
        into nValorNovo
        from lote
           inner join testpri   on j49_idbql  = j34_idbql
           inner join face      on j37_face   = j49_face
           inner join facevalor on j37_face   = j81_face
                               and j81_anousu = iAnousu
    where j34_idbql = iIdbql;

    if nValorNovo is null then
        rlErro := true;
        riCodigoErro := 25;
        rtTextoErro := 'VALOR DO EXERCÍCIO ATUAL NÃO ENCONTRADO.';
        return;
    end if;

    perform fc_debug('<fc_iptu_getvalormetroquadrado_osorio_2018> Valor m2 do exercicio atual: ' || nValorNovo, lRaise);

    select round(j145_valor / 100, 2) as percentualexercicio
        from iptupercentualexercicio
        into nPercentualExercicio
    where j145_anousu = iAnousu;

    if nPercentualExercicio is null or nPercentualExercicio = 0 then
        nPercentualExercicio := 1;
    end if;

    rnValorMetroQuadrado := round(nValorAntigo + ((nValorNovo - nValorAntigo) * nPercentualExercicio), 2);

    perform fc_debug('<fc_iptu_getvalormetroquadrado_osorio_2018> Valor m2: ' || rnValorMetroQuadrado, lRaise);
    return;

end;
$$  language 'plpgsql';
FUNCAO;

        $this->execute($sSql);
    }

    public function down()
    {
        $this->execute("drop function fc_iptu_getvalormetroquadrado_osorio_2018(integer, integer, boolean);");
    }
}
