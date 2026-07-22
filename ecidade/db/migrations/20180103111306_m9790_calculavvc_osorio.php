<?php

use Classes\PostgresMigration;

class M9790CalculavvcOsorio extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<FUNCAO
create or replace function fc_iptu_calculavvc_osorio_2018(  iMatricula      integer,
                                                            iAnousu         integer,
                                                            bRaise          boolean,

                                                            OUT rnVvc       numeric(15,2),
                                                            OUT rnTotarea   numeric,
                                                            OUT riNumconstr integer,
                                                            OUT rbErro      boolean,
                                                            OUT riCodErro   integer,
                                                            OUT rtErro      text
                                                      ) returns record as
$$
declare

    iMatricula           alias for $1;
    iAnousu              alias for $2;
    lRaise               alias for $3;

    nAreaconstr           numeric(15,2) default 0;
    nValorVenalTotal      numeric(15,2) default 0;
    nVm2c    			  numeric(15,2) default 0;
    nPadraoConstrucao     numeric;
    nEstrutura            numeric;
    nFatorEstConservacao  numeric;
    nFatorIdadeConstrucao numeric;
    nValorVenal           numeric;
    lMatriculaPredial     boolean;
    lAtualiza             boolean default true;
    iNumeroedificacoes    integer default 0;
    tSqlConstr            text    default '';

    rConstr          record;
    rValorM2         record;
    rIdadeEdificacao record;

begin

    perform fc_debug('', lRaise);
    perform fc_debug('' || lpad('',60,'-'), lRaise);
    perform fc_debug('<fc_iptu_calculavvc_osorio_2018> INICIANDO CALCULO DO VALOR VENAL DA CONSTRUCAO', lRaise);

    rnVvc       := 0;
    rnTotarea   := 0;
    riNumconstr := 0;
    rbErro      := 'f';
    riCodErro   := 0;
    rtErro      := '';

    tSqlConstr :=               ' select * ';
    tSqlConstr := tSqlConstr || '  from iptuconstr ';
    tSqlConstr := tSqlConstr || ' where j39_matric = ' || iMatricula;
    tSqlConstr := tSqlConstr || '   and j39_dtdemo is null';

    perform fc_debug('<fc_iptu_calculavvc_osorio_2018> Buscando as construcoes: ' || tSqlConstr, lRaise);

    for rConstr in execute tSqlConstr loop

        -- nVm2c: Buscamos o valor do metro quardado por tipo de edificação
        select j71_valor
          into nVm2c
          from carconstr
               inner join caracter   on j31_codigo = j48_caract
               inner join cargrup    on j32_grupo  = j31_grupo
               inner join iptuconstr on j39_matric = j48_matric
                                    and j39_idcons = j48_idcons
               inner join carvalor   on j71_caract = j31_codigo
         where j48_matric = iMatricula
           and j48_idcons = rConstr.j39_idcons
           and j31_grupo  = 112;

        if not found then

            rbErro    := true;
            riCodErro := 102;
            rtErro    := 'TIPO EDIFICACAO (112)';
            return;
        end if;
        perform fc_debug('<fc_iptu_calculavvc_osorio_2018> Vm2c: ' || nVm2c,lRaise);

        -- nAreaconstr: Buscamos a área construída
        nAreaconstr := nAreaconstr + rConstr.j39_area;
        perform fc_debug('<fc_iptu_calculavvc_osorio_2018> Área de Construção: ' || rConstr.j39_area,lRaise);

        -- nPadraoConstrucao: Buscamos o padrão da construção
        select j74_fator
          into nPadraoConstrucao
          from carconstr
               inner join caracter   on j31_codigo = j48_caract
               inner join cargrup    on j32_grupo  = j31_grupo
               inner join iptuconstr on j39_matric = j48_matric
                                    and j39_idcons = j48_idcons
               inner join carfator   on j74_caract = j31_codigo
         where j48_matric = iMatricula
           and j48_idcons = rConstr.j39_idcons
           and j31_grupo  = 115;

        if not found then

            rbErro    := true;
            riCodErro := 102;
            rtErro    := 'PADRAO DA CONSTRUCAO(115)';
            return;
        end if;
        perform fc_debug('<fc_iptu_calculavvc_osorio_2018> Padrão da Construção: ' || nPadraoConstrucao,lRaise);

        -- nEstrutura: Buscamos a estrutura
        select j74_fator
          into nEstrutura
          from carconstr
               inner join caracter   on j31_codigo = j48_caract
               inner join cargrup    on j32_grupo  = j31_grupo
               inner join iptuconstr on j39_matric = j48_matric
                                    and j39_idcons = j48_idcons
               inner join carfator   on j74_caract = j31_codigo
         where j48_matric = iMatricula
           and j48_idcons = rConstr.j39_idcons
           and j31_grupo  = 107;

        if not found then

            rbErro    := true;
            riCodErro := 102;
            rtErro    := 'ESTRUTURA (107)';
            return;
        end if;
        perform fc_debug('<fc_iptu_calculavvc_osorio_2018> Estrutura: ' || nEstrutura,lRaise);

        -- nFatorEstConservacao: Buscamos o estado de conservação
        select j74_fator
          into nFatorEstConservacao
          from carconstr
               inner join caracter   on j31_codigo = j48_caract
               inner join cargrup    on j32_grupo  = j31_grupo
               inner join iptuconstr on j39_matric = j48_matric
                                    and j39_idcons = j48_idcons
               inner join carfator   on j74_caract = j31_codigo
         where j48_matric = iMatricula
           and j48_idcons = rConstr.j39_idcons
           and j31_grupo  = 109;

        if not found then

            rbErro    := true;
            riCodErro := 102;
            rtErro    := 'ESTADO DE CONSERVACAO (109)';
            return;
        end if;
        perform fc_debug('<fc_iptu_calculavvc_osorio_2018> Estado de Conservacao: ' || nFatorEstConservacao,lRaise);

        -- nFatorIdadeConstrucao: Buscamos o fator da idade da edificacao
        rIdadeEdificacao := fc_iptu_getfatoridadeedificacao_osorio_2018(iMatricula, rConstr.j39_idcons, iAnousu, lRaise);
        if rIdadeEdificacao.rlErro then

            rbErro    := 't';
            riCodErro := rIdadeEdificacao.riCodErro;
            rtErro    := rIdadeEdificacao.rtErro;
            return;
        end if;

        nFatorIdadeConstrucao := rIdadeEdificacao.nFatorIdadeEdificacao;
        perform fc_debug('<fc_iptu_calculavvc_osorio_2018> Fator idade da edificacao: ' || nFatorIdadeConstrucao,lRaise);

        -- nValorVenal: Calculamos o valor venal
        nValorVenal := (nVm2c * rConstr.j39_area * nPadraoConstrucao * nEstrutura * nFatorEstConservacao * nFatorIdadeConstrucao);
        perform fc_debug('<fc_iptu_calculavvc_osorio_2018> Valor Venal: ' || nValorVenal,lRaise);

        nValorVenalTotal   := nValorVenalTotal + nValorVenal;
        iNumeroedificacoes := iNumeroedificacoes + 1;

        insert into tmpiptucale (anousu, matric, idcons, areaed, vm2, pontos, valor, edificacao)
             values (iAnousu, iMatricula, rConstr.j39_idcons, rConstr.j39_area, nVm2c, 0, nValorVenal, true);

        if lAtualiza then

            update tmpdadosiptu set predial = true;
            lAtualiza = false;
       end if;

    end loop;


    perform matric
       from tmpiptucale
    where edificacao is true;

    if found then
        lMatriculaPredial = true;
    else
        lMatriculaPredial = false;
    end if;

    if lMatriculaPredial is true then

        rnVvc       := nValorVenalTotal;
        rnTotarea   := nAreaconstr;
        riNumconstr := iNumeroedificacoes;
        rbErro      := 'f';

        perform fc_debug('<fc_iptu_calculavvc_osorio_2018> Total VVC: '             || rnVvc,lRaise);
        perform fc_debug('<fc_iptu_calculavvc_osorio_2018> Toral area construida: ' || rnTotarea,lRaise);
        perform fc_debug('<fc_iptu_calculavvc_osorio_2018> Total de construcoes: '  || riNumconstr,lRaise);
        update tmpdadosiptu set vvc = rnVvc;
    else

        delete from tmpiptucale;
        update tmpdadosiptu set predial = false;
    end if;

    perform fc_debug('' || lpad('',60,'-'), lRaise);
    perform fc_debug('', lRaise);

    return;

end;
$$  language 'plpgsql';
FUNCAO;

        $this->execute($sSql);
    }

    public function down()
    {
        $this->execute("drop function fc_iptu_calculavvc_osorio_2018(integer, integer, boolean);");
    }
}
