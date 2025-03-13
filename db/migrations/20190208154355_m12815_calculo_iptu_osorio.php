<?php

use Classes\PostgresMigration;

class M12815CalculoIptuOsorio extends PostgresMigration
{
    public function up()
    {
        $this->dicionarioUp();
        $this->calculavvcUp();
        $this->getValorMetroQuadradoUp();
        $this->taxaLimpezaUp();
    }

    public function down()
    {
        $this->dicionarioDown();
        $this->calculavvcDown();
        $this->getValorMetroQuadradoDown();
        $this->taxaLimpezaDown();
    }

    private function dicionarioUp()
    {
        $sql  = <<<SQL

insert into db_sysfuncoes( codfuncao ,nomefuncao ,nomearquivo ,obsfuncao ,corpofuncao ,triggerfuncao ) values ( 181 ,'fc_iptu_taxalimpeza_osorio_2019' ,'fc_iptu_taxalimpeza_osorio_2019' ,'Procedimento para calculo de taxa de limpeza de Osório.' ,'.' ,'0' );
insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1010 ,181 ,1 ,'iReceita' ,'int4' ,0 ,0 ,'0' ,'RECEITA' );
insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1011 ,181 ,1 ,'iAliquota' ,'numeric' ,0 ,0 ,'0' ,'ALIQUOTA' );
insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1012 ,181 ,1 ,'iHistCalc' ,'int4' ,0 ,0 ,'0' ,'HISTORICO DE CALCULO' );
insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1013 ,181 ,1 ,'iPercIsen' ,'numeric' ,0 ,0 ,'0' ,'PERCENTUAL DE ISENCAO' );
insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1014 ,181 ,5 ,'nValpar' ,'numeric' ,0 ,0 ,'0' ,'VALOR POR PARAMETRO' );
insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1015 ,181 ,6 ,'bRaise' ,'bool' ,0 ,0 ,'FALSE' ,'DEBUG' );

SQL;
        $this->execute($sql);
    }

    private function dicionarioDown()
    {
        $sql  = <<<SQL

delete from db_sysfuncoesparam where db42_sysfuncoesparam in (1010, 1011, 1012, 1013, 1014, 1015);
delete from db_sysfuncoes where codfuncao = 181;

SQL;
        $this->execute($sql);
    }

    private function calculavvcUp()
    {
        $sql  = <<<SQL
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
    nVm2c                 numeric(15,2) default 0;
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

        select j71_valor
          into nVm2c
          from carconstr
               inner join caracter   on j31_codigo = j48_caract
               inner join cargrup    on j32_grupo  = j31_grupo
               inner join iptuconstr on j39_matric = j48_matric
                                    and j39_idcons = j48_idcons
               inner join carvalor   on j71_caract = j31_codigo and j71_anousu = iAnousu
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

        nAreaconstr := nAreaconstr + rConstr.j39_area;
        perform fc_debug('<fc_iptu_calculavvc_osorio_2018> Área de Construção: ' || rConstr.j39_area,lRaise);

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

        rIdadeEdificacao := fc_iptu_getfatoridadeedificacao_osorio_2018(iMatricula, rConstr.j39_idcons, iAnousu, lRaise);
        if rIdadeEdificacao.rlErro then

            rbErro    := 't';
            riCodErro := rIdadeEdificacao.riCodErro;
            rtErro    := rIdadeEdificacao.rtErro;
            return;
        end if;

        nFatorIdadeConstrucao := rIdadeEdificacao.nFatorIdadeEdificacao;
        perform fc_debug('<fc_iptu_calculavvc_osorio_2018> Fator idade da edificacao: ' || nFatorIdadeConstrucao,lRaise);

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
SQL;
        $this->execute($sql);
    }

    private function getValorMetroQuadradoUp()
    {
        $sql  = <<<SQL

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
                            and j81_anousu = 2017
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

SQL;
        $this->execute($sql);
    }

    private function taxaLimpezaUp()
    {
        $sql  = <<<SQL

create or replace function fc_iptu_taxalimpeza_osorio_2019(integer,numeric,integer,numeric,numeric,boolean) returns boolean as
$$
declare

    iReceita                 alias for $1;
    iAliquota                alias for $2;
    iHistoricoCalculoIsencao alias for $3;
    nPercIsen                alias for $4;
    lRaise                   alias for $6;

    nValorTaxa      numeric(15,2) default 0;
    nValorDesconto  numeric(15,2) default 0;
    nInflatorURM    numeric(15,4) default 0;
    nAreaEdificada  numeric(15,2) default 0;
    iIdbql          integer       default 0;
    iNparc          integer       default 0;
    tSql            text          default '';
    iURM            numeric       default 0;

  begin

    select coalesce( sum(areaed) ,0)
      into nAreaEdificada
      from ( select areaed, coalesce( ( select carconstr.j48_caract
                                          from carconstr
                                               inner join caracter on carconstr.j48_caract = caracter.j31_codigo
                                         where carconstr.j48_matric = tmpiptucale.matric
                                           and carconstr.j48_idcons = tmpiptucale.idcons
                                      ), 0 ) as j48_caract
              from tmpiptucale
           ) as x;

    if not found then
      return false;
    end if;

    if nAreaEdificada = 0 then
      nPercIsen := 100;
    end if;

    case
      when nAreaEdificada <= 400 then
        iURM := nAreaEdificada * 0.3;
      when nAreaEdificada > 400 and nAreaEdificada <= 1000  then
        iURM := 120;
      when nAreaEdificada > 1000 then
        iURM := 300;
      else
        iURM := 0;
    end case;

    select i02_valor::numeric
      into nInflatorURM
      from infla
     where i02_codigo = 'URM'
       and extract ( year from i02_data) = (select anousu
                                              from tmpiptucale
                                             limit 1)
     limit 1;

    perform fc_debug(' <iptu_taxalimpeza_osorio> Inflator encontrado: ' || coalesce(nInflatorURM, 0), lRaise);

    if nInflatorURM is null or nInflatorURM = 0 then
      return false;
    end if;
    perform fc_debug(' <iptu_taxalimpeza_osorio> Percentual de Isencao: ' || nPercIsen, lRaise);

    nValorTaxa := nInflatorURM * iURM;

    perform fc_debug(' <iptu_taxalimpeza_osorio> URM: ' || iURM || ' INFLATOR: ' || nInflatorURM, lRaise);
    perform fc_debug(' <iptu_taxalimpeza_osorio> Limpeza: ' || nValorTaxa, lRaise);
    perform fc_debug(' <iptu_taxalimpeza_osorio> Inserindo tmptaxapercisen  - iReceita '||coalesce(iReceita,0)||' nPercIsen - '||coalesce(nPercIsen,0)||' nValorTaxa - ' || coalesce(nValorTaxa,0), lRaise);

    insert into tmptaxapercisen values (iReceita, nPercIsen, 0, nValorTaxa);

    if nPercIsen > 0 then

      nValorDesconto := nValorTaxa * ( nPercIsen / 100 );
      nValorTaxa     := nValorTaxa * ( 100 - nPercIsen ) / 100;
    end if;

    perform fc_debug(' <iptu_taxalimpeza_osorio> LIMPEZA COM ISENÇÃO: ' || coalesce(nValorTaxa,0) || ' DESCONTO: '|| nValorDesconto, lRaise);

    tSql := 'insert into tmprecval values ('||iReceita||','||nValorTaxa||','||iHistoricoCalculoIsencao||',true)';

    execute tSql;

    return true;

  end;
$$ language 'plpgsql';

SQL;
        $this->execute($sql);
    }

    private function calculavvcDown()
    {
        $sql  = <<<SQL

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
    nVm2c                 numeric(15,2) default 0;
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

        nAreaconstr := nAreaconstr + rConstr.j39_area;

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

SQL;
        $this->execute($sql);
    }

    private function getValorMetroQuadradoDown()
    {
        $sql  = <<<SQL
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
        rtTextoErro  := 'VALOR DO EXERCICIO ANTERIOR NAO ENCONTRADO.';
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
        rtTextoErro := 'VALOR DO EXERCICIO ATUAL NAO ENCONTRADO.';
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


SQL;
        $this->execute($sql);
    }

    private function taxaLimpezaDown()
    {
        $sql  = <<<SQL

drop function fc_iptu_taxalimpeza_osorio_2019(integer,numeric,integer,numeric,numeric,boolean);

SQL;
        $this->execute($sql);
    }
}
