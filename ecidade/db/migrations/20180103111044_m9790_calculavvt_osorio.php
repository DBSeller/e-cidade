<?php

use Classes\PostgresMigration;

class M9790CalculavvtOsorio extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<FUNCAO
create or replace function fc_iptu_calculavvt_osorio_2018( integer, integer, integer, numeric, numeric, boolean, boolean,
                                                            OUT rnVvt        numeric(15,2),
                                                            OUT rbErro       boolean,
                                                            OUT riCoderro    integer,
                                                            OUT rtErro       text ) returns record as
$$
declare

    iMatricula       alias for $1;
    iIdbql           alias for $2;
    iAnousu          alias for $3;
    nFracao          alias for $4;
    nAreal           alias for $5;
    lDemonstrativo   alias for $6;
    lRaise           alias for $7;

    rnArealote              numeric default 0;
    nVm2terreno             numeric default 0;
    nAreaCorrigida          numeric default 0;
    nFatorPedologia         numeric default 0;
    nFatorTopografia        numeric default 0;
    nFatorSitQuadra         numeric default 0;
    iCodigoFatorMuroPasseio integer default 0;
    nFatorMuroPasseio       numeric default 0;
    nFatorGleba             numeric default 0;
    nFatorProfundidade      numeric default 0;

    lErro               boolean default false;
    iCodErro            integer;
    tRetorno            text default '';
    rValorMetroQuadrado record;
begin

    rnVvt        := 0;
    rbErro       := 'f';
    riCoderro    := 0;
    rtErro       := '';

    perform fc_debug('' || lpad('',60,'-'), lRaise);
    perform fc_debug('<fc_iptu_calculavvt_osorio_2018> * INICIANDO CALCULO DO VALOR VENAL TERRITORIAL',lRaise);

    -- nVm2terreno: Valor genérico do metro quadrado do terreno
    rValorMetroQuadrado := fc_iptu_getvalormetroquadrado_osorio_2018(iIdbql, iAnousu, lRaise);
    if rValorMetroQuadrado.rlErro then

        rbErro    := 't';
        riCodErro := rValorMetroQuadrado.riCodigoErro;
        rtErro    := rValorMetroQuadrado.rlErro;
        return;
    end if;

    nVm2terreno := rValorMetroQuadrado.rnValorMetroQuadrado;
    perform fc_debug('<fc_iptu_calculavvt_osorio_2018> Valor m2: ' || nVm2terreno,lRaise);

    -- nAreaCorrigida: Busca a área do terreno
    rnArealote     := nAreal;
    nAreaCorrigida := (rnArealote * ( nFracao / 100 ));
    nAreaCorrigida := round(nAreaCorrigida, 2);

    perform fc_debug('<fc_iptu_calculavvt_osorio_2018> Area real do lote: ' || rnArealote,lRaise);
    perform fc_debug('<fc_iptu_calculavvt_osorio_2018> Area corrigida: '    || nAreaCorrigida,lRaise);
    perform fc_debug('<fc_iptu_calculavvt_osorio_2018> iIdbql: '    || iIdbql,lRaise);

    -- nFatorPedologia: Busca o fator corretivo de pedologia
    select COALESCE(j74_fator, 0)
      into nFatorPedologia
      from carlote
           inner join caracter on j35_caract = j31_codigo
           inner join carfator on j74_caract = j31_codigo
     where j35_idbql = iIdbql
       and j31_grupo = 106;

    perform fc_debug('<fc_iptu_calculavvt_osorio_2018> Fator de Pedologia: ' || nFatorPedologia,lRaise);

    if nFatorPedologia = 0 or nFatorPedologia is null THEN
      rbErro    := true;
      riCodErro := 101;
      rtErro    := 'PEDOLOGIA (106)';
      return;
    end if;

    perform fc_debug('<fc_iptu_calculavvt_osorio_2018> Fator de Pedologia: ' || nFatorPedologia,lRaise);

    -- nFatorTopografia: Busca o fator corretivo de topografia
    select COALESCE(j74_fator, 0)
      into nFatorTopografia
      from carlote
           inner join caracter on j35_caract = j31_codigo
           inner join carfator on j74_caract = j31_codigo
     where j35_idbql = iIdbql
       and j31_grupo = 105;

    if nFatorTopografia = 0 or nFatorTopografia is null THEN
      rbErro    := true;
      riCodErro := 101;
      rtErro    := 'TOPOGRAFIA (105)';
      return;
    end if;

    perform fc_debug('<fc_iptu_calculavvt_osorio_2018> Fator de Topografia: ' || nFatorTopografia, lRaise);   

    -- nFatorSitQuadra: Busca o fator corretivo de situação do terreno
    select COALESCE(j74_fator, 0)
      into nFatorSitQuadra
      from carlote
           inner join caracter on j35_caract = j31_codigo
           inner join carfator on j74_caract = j31_codigo
     where j35_idbql = iIdbql
       and j31_grupo = 104;

    if nFatorSitQuadra = 0 or nFatorSitQuadra is null THEN
      rbErro    := true;
      riCodErro := 101;
      rtErro    := 'SITUACAO NO LOTE (104)';
      return;
    end if;

    perform fc_debug('<fc_iptu_calculavvt_osorio_2018> Fator de situacao do terreno: ' || nFatorSitQuadra, lRaise);

    -- nFatorMuroPasseio: Busca o fator do Muro e/ou Passeio
    select j140_sequencial, j140_valor 
      into iCodigoFatorMuroPasseio, nFatorMuroPasseio
      from agrupamentocaracteristica
     inner join agrupamentocaracteristicavalor on j139_agrupamentocaracteristicavalor = j140_sequencial
     where j139_caracter = (select j35_caract from carlote inner join caracter on j35_caract = j31_codigo where j35_idbql = iIdbql and j31_grupo = 103)
           or j139_caracter = (select j35_caract from carlote inner join caracter on j35_caract = j31_codigo where j35_idbql = iIdbql and j31_grupo = 102)
     group by j140_sequencial
    having count(j140_sequencial) > 1;

    if nFatorMuroPasseio is null or nFatorMuroPasseio = 0 then
      rbErro    := true;
      riCodErro := 101;
      rtErro    := 'DELIMITAÇÃO (102) E PASSEIO (103)';
      return;
    end if;
    
    perform fc_debug('<fc_iptu_calculavvt_osorio_2018> Fator de Muro e/ou Passeio: ' || nFatorMuroPasseio, lRaise);

    -- nFatorGleba: Busca o fator da Gleba
    select rnFatorGleba, rlErro, riCodigoErro, rtTextoErro
      into nFatorGleba, lErro, iCodErro, tRetorno
      from fc_iptu_getfatorgleba_osorio_2018( iIdbql, nAreaCorrigida, lRaise );

    if lErro is true then

      rbErro    := lErro;
      riCodErro := iCodErro;
      rtErro    := tRetorno;
      return;
    end if;

    perform fc_debug('<fc_iptu_calculavvt_osorio_2018> Fator Gleba: ' || nFatorGleba,lRaise);

    -- nFatorProfundidade: Busca o fator corretio de profundidade
    select rnFatorProfundidade, rlErro, riCodigoErro, rtTextoErro
      into nFatorProfundidade, lErro, iCodErro, tRetorno
      from fc_iptu_getfatorprofundidade_osorio_2018( iIdbql, nAreaCorrigida, lRaise );

    if lErro is true then

      rbErro    := lErro;
      riCodErro := iCodErro;
      rtErro    := tRetorno;
      return;
    end if;

    perform fc_debug('<fc_iptu_calculavvt_osorio_2018> Fator Profundidade: ' || nFatorProfundidade, lRaise);


    rnVvt := nVm2terreno * nAreaCorrigida * nFatorPedologia * nFatorTopografia * nFatorSitQuadra * nFatorMuroPasseio * nFatorGleba * nFatorProfundidade;


    update tmpdadosiptu
       set vvt   = rnVvt,
           vm2t  = nVm2terreno,
           areat = nAreaCorrigida;

    return;
end;
$$  language 'plpgsql';
FUNCAO;

        $this->execute($sSql);
    }

    public function down()
    {
        $this->execute("drop function fc_iptu_calculavvt_osorio_2018(integer, integer, integer, numeric, numeric, boolean, boolean);");
    }
}
