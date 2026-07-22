<?php

use Classes\PostgresMigration;

class M16314FracionamentoMaquine extends PostgresMigration
{
    public function up()
    {
        $this->upCriatemptable();
        $this->upFracionalote();
        $this->upGetareaconstrloteidbql();
        $this->upTaxaLixoMaquine();
    }

    public function down()
    {
        $this->downCriatemptable();
        $this->downFracionalote();
        $this->downGetareaconstrloteidbql();
        $this->downTaxaLixoMaquine();
    }

    public function upCriatemptable()
    {
        $this->execute(<<<SQL
            create or replace function fc_iptu_criatemptable(boolean) returns boolean as
            $$
            declare

                 lRaise alias for $1;

                 rbErro boolean default false;
                 nome   name;

            begin

               /**
                * FUNCAO PARA CRIAR AS TABELAS TEMPORARIAS
                */
              perform fc_debug('', lRaise);
              perform fc_debug(' <iptu_criatemptable> INICIANDO CRIACAO DE ESTRUTURAS TEMPORARIAS...', lRaise);

              begin

                /*
                 * NAO REMOVER CAMPOS DESSAS TABELAS, ESSA ALTERACAO PODE CAUSAR PROBLEMAS EM TODOS OS CALCULOS
                 * QUANDO USAR AS TABELAS TEMPORARIAS NAO USE SELECT * INTO VAI1, VAR2,VAR3 FROM XXX.
                 * USE: SELECT CAMPO1,CAMPO2,CAMPO3 INTO  VAR1, VAR2,VAR3 FROM XXXX.
                 */

                /**
                 * Tabela que guarda as receitas e valores das mesmas, para gerar o financeiro(arrecad)
                 */
                create temporary table tmprecval( "receita" integer,"valor" numeric,"hist" integer,"taxa" boolean,"aliq" numeric );
                perform fc_debug(' <iptu_criatemptable> TABELA TMPRECVAL CRIADA', lRaise);

                /**
                 * Tabela que guarda os dados referente ao comportamento do calculo durante o processamento das sub-funcoes
                 */
                create temporary table tmpdadosiptu( "aliq"      numeric,
                                                     "vvc"       numeric,
                                                     "vvt"       numeric,
                                                     "viptu"     numeric,
                                                     "fracao"    numeric,
                                                     "areat"     numeric,
                                                     "predial"   boolean,
                                                     "codvenc"   integer,
                                                     "tipoisen"  integer,
                                                     "vm2t"      numeric,
                                                     "testada"   numeric,
                                                     "matric"    integer,
                                                     "isentaxas" boolean );
                insert into tmpdadosiptu values (0,0,0,0,0,0,false,0,0,0,0,0);
                perform fc_debug(' <iptu_criatemptable> TABELA TMPDADOSIPTU CRIADA', lRaise);

                /**
                 * Tabela que guarda os dados das contrucoes calculadas, alimentada pela fc_iptu_calculavvc
                 */
                create temporary table tmpiptucale( "anousu"         integer,
                                                    "matric"         integer,
                                                    "idcons"         integer,
                                                    "areaed"         numeric,
                                                    "vm2"            numeric,
                                                    "pontos"         integer,
                                                    "valor"          numeric,
                                                    "edificacao"     boolean,
                                                    "caracteristica" integer,
                                                    "aliquota"       numeric );
                perform fc_debug(' <iptu_criatemptable> TABELA TMPIPTUCALE CRIADA', lRaise);

                /**
                 * Tabela que guarda os valores para calcular as taxas
                 */
                create temporary table tmpdadostaxa( "anousu"  integer,
                                                     "matric"  integer,
                                                     "zona"    integer,
                                                     "idbql"   integer,
                                                     "nparc"   integer,
                                                     "valiptu" numeric,
                                                     "valref"  numeric,
                                                     "vvt"     numeric,
                                                     "totareaconst" numeric );
                insert into tmpdadostaxa values (0,0,0,0,0,0,0,0,0);
                perform fc_debug(' <iptu_criatemptable> TABELA TMPDADOSTAXA CRIADA', lRaise);

                /**
                 * Tabela com os parametros para o comportamento da fase do calculo que gera o financeiro
                 */
                create temporary table tmpfinanceiro("anousu" integer,"matric" integer,"idbql" integer,"valiptu" numeric,"valref" numeric,"vvt" numeric);
                insert into tmpfinanceiro values (0,0,0,0,0,0);
                perform fc_debug(' <iptu_criatemptable> TABELA TMPFINANCEIRO CRIADA', lRaise);

                /**
                 * Tabela que guarda as receitas e percentual de isencao das taxas
                 */
                create temporary table tmptaxapercisen("rectaxaisen" integer,"percisen" numeric, "histcalcisen" integer,"valsemisen" numeric);
                perform fc_debug(' <iptu_criatemptable> TABELA TMPTAXAPERCISEN CRIADA', lRaise);

                /**
                 * Tabela que guarda os valores para "outras" taxas (taxa bombeiro, limpeza)
                 */
                create temporary table tmpoutrosvalores("valor" numeric,"descricao" varchar);
                perform fc_debug(' <iptu_criatemptable> TABELA TMPTAXAPERCISEN CRIADA', lRaise);

                /**
                 * Tabela que guarda os valores de vencimentos
                 */
                create temporary table tmp_cadvenc as
                  select q92_codigo,
                         q92_tipo,
                         q92_hist,
                         q92_vlrminimo,
                         q82_parc,
                         q82_venc,
                         q82_perc,
                         q82_hist
                    from cadvencdesc
                         inner join cadvenc on q92_codigo = q82_codigo
                   limit 0;
                perform fc_debug(' <iptu_criatemptable> TABELA TMP_CADVENC CRIADA', lRaise);

                /**
                 * Tabela para guardar o numpre gerado na diversos (iptu_complementar)
                 */
                create temporary table tmpipturecalculonump (
                    matricula integer,
                    anousu    integer,
                    numpre    integer
                );
                perform fc_debug(' <iptu_criatemptable> TABELA TMPIPTURECALCULONUMP CRIADA', lRaise);

                /**
                 * Tabela para guardar o numpre gerado na diversos (iptu_complementar)
                 */
                create temporary table tmpipturecalculocreditonump (
                    matricula integer,
                    anousu    integer,
                    numpre    integer
                );
                perform fc_debug(' <iptu_criatemptable> TABELA TMPIPTURECALCULOCREDITONUMP CRIADA', lRaise);

              exception
                   when duplicate_table then
                        truncate tmprecval;
                        truncate tmpdadosiptu;
                        truncate tmpiptucale;
                        truncate tmpdadostaxa;
                        truncate tmpfinanceiro;
                        truncate tmptaxapercisen;
                        truncate tmpoutrosvalores;
                        truncate tmp_cadvenc;
                        truncate tmpipturecalculonump;
                        truncate tmpipturecalculocreditonump;
                        insert into tmpdadosiptu  values (0,0,0,0,0,0,false,0,0,0,0,0,false);
                        insert into tmpdadostaxa  values (0,0,0,0,0,0,0,0,0);
                        insert into tmpfinanceiro values (0,0,0,0,0,0);
              end;

              perform fc_debug(' <iptu_criatemptable> FIM CRIACAO DE ESTRUTURAS TEMPORARIAS', lRaise);
              perform fc_debug('', lRaise);

              return rbErro;

            end;
            $$  language 'plpgsql';
SQL
        );
    }

    public function downCriatemptable()
    {
        $this->execute(<<<SQL
            create or replace function fc_iptu_criatemptable(boolean) returns boolean as
            $$
            declare

                 lRaise alias for $1;

                 rbErro boolean default false;
                 nome   name;

            begin

               /**
                * FUNCAO PARA CRIAR AS TABELAS TEMPORARIAS
                */
              perform fc_debug('', lRaise);
              perform fc_debug(' <iptu_criatemptable> INICIANDO CRIACAO DE ESTRUTURAS TEMPORARIAS...', lRaise);

              begin

                /*
                 * NAO REMOVER CAMPOS DESSAS TABELAS, ESSA ALTERACAO PODE CAUSAR PROBLEMAS EM TODOS OS CALCULOS
                 * QUANDO USAR AS TABELAS TEMPORARIAS NAO USE SELECT * INTO VAI1, VAR2,VAR3 FROM XXX.
                 * USE: SELECT CAMPO1,CAMPO2,CAMPO3 INTO  VAR1, VAR2,VAR3 FROM XXXX.
                 */

                /**
                 * Tabela que guarda as receitas e valores das mesmas, para gerar o financeiro(arrecad)
                 */
                create temporary table tmprecval( "receita" integer,"valor" numeric,"hist" integer,"taxa" boolean );
                perform fc_debug(' <iptu_criatemptable> TABELA TMPRECVAL CRIADA', lRaise);

                /**
                 * Tabela que guarda os dados referente ao comportamento do calculo durante o processamento das sub-funcoes
                 */
                create temporary table tmpdadosiptu( "aliq"      numeric,
                                                     "vvc"       numeric,
                                                     "vvt"       numeric,
                                                     "viptu"     numeric,
                                                     "fracao"    numeric,
                                                     "areat"     numeric,
                                                     "predial"   boolean,
                                                     "codvenc"   integer,
                                                     "tipoisen"  integer,
                                                     "vm2t"      numeric,
                                                     "testada"   numeric,
                                                     "matric"    integer,
                                                     "isentaxas" boolean );
                insert into tmpdadosiptu values (0,0,0,0,0,0,false,0,0,0,0,0);
                perform fc_debug(' <iptu_criatemptable> TABELA TMPDADOSIPTU CRIADA', lRaise);

                /**
                 * Tabela que guarda os dados das contrucoes calculadas, alimentada pela fc_iptu_calculavvc
                 */
                create temporary table tmpiptucale( "anousu"         integer,
                                                    "matric"         integer,
                                                    "idcons"         integer,
                                                    "areaed"         numeric,
                                                    "vm2"            numeric,
                                                    "pontos"         integer,
                                                    "valor"          numeric,
                                                    "edificacao"     boolean,
                                                    "caracteristica" integer,
                                                    "aliquota"       numeric );
                perform fc_debug(' <iptu_criatemptable> TABELA TMPIPTUCALE CRIADA', lRaise);

                /**
                 * Tabela que guarda os valores para calcular as taxas
                 */
                create temporary table tmpdadostaxa( "anousu"  integer,
                                                     "matric"  integer,
                                                     "zona"    integer,
                                                     "idbql"   integer,
                                                     "nparc"   integer,
                                                     "valiptu" numeric,
                                                     "valref"  numeric,
                                                     "vvt"     numeric,
                                                     "totareaconst" numeric );
                insert into tmpdadostaxa values (0,0,0,0,0,0,0,0,0);
                perform fc_debug(' <iptu_criatemptable> TABELA TMPDADOSTAXA CRIADA', lRaise);

                /**
                 * Tabela com os parametros para o comportamento da fase do calculo que gera o financeiro
                 */
                create temporary table tmpfinanceiro("anousu" integer,"matric" integer,"idbql" integer,"valiptu" numeric,"valref" numeric,"vvt" numeric);
                insert into tmpfinanceiro values (0,0,0,0,0,0);
                perform fc_debug(' <iptu_criatemptable> TABELA TMPFINANCEIRO CRIADA', lRaise);

                /**
                 * Tabela que guarda as receitas e percentual de isencao das taxas
                 */
                create temporary table tmptaxapercisen("rectaxaisen" integer,"percisen" numeric, "histcalcisen" integer,"valsemisen" numeric);
                perform fc_debug(' <iptu_criatemptable> TABELA TMPTAXAPERCISEN CRIADA', lRaise);

                /**
                 * Tabela que guarda os valores para "outras" taxas (taxa bombeiro, limpeza)
                 */
                create temporary table tmpoutrosvalores("valor" numeric,"descricao" varchar);
                perform fc_debug(' <iptu_criatemptable> TABELA TMPTAXAPERCISEN CRIADA', lRaise);

                /**
                 * Tabela que guarda os valores de vencimentos
                 */
                create temporary table tmp_cadvenc as
                  select q92_codigo,
                         q92_tipo,
                         q92_hist,
                         q92_vlrminimo,
                         q82_parc,
                         q82_venc,
                         q82_perc,
                         q82_hist
                    from cadvencdesc
                         inner join cadvenc on q92_codigo = q82_codigo
                   limit 0;
                perform fc_debug(' <iptu_criatemptable> TABELA TMP_CADVENC CRIADA', lRaise);

                /**
                 * Tabela para guardar o numpre gerado na diversos (iptu_complementar)
                 */
                create temporary table tmpipturecalculonump (
                    matricula integer,
                    anousu    integer,
                    numpre    integer
                );
                perform fc_debug(' <iptu_criatemptable> TABELA TMPIPTURECALCULONUMP CRIADA', lRaise);

                /**
                 * Tabela para guardar o numpre gerado na diversos (iptu_complementar)
                 */
                create temporary table tmpipturecalculocreditonump (
                    matricula integer,
                    anousu    integer,
                    numpre    integer
                );
                perform fc_debug(' <iptu_criatemptable> TABELA TMPIPTURECALCULOCREDITONUMP CRIADA', lRaise);

              exception
                   when duplicate_table then
                        truncate tmprecval;
                        truncate tmpdadosiptu;
                        truncate tmpiptucale;
                        truncate tmpdadostaxa;
                        truncate tmpfinanceiro;
                        truncate tmptaxapercisen;
                        truncate tmpoutrosvalores;
                        truncate tmp_cadvenc;
                        truncate tmpipturecalculonump;
                        truncate tmpipturecalculocreditonump;
                        insert into tmpdadosiptu  values (0,0,0,0,0,0,false,0,0,0,0,0,false);
                        insert into tmpdadostaxa  values (0,0,0,0,0,0,0,0,0);
                        insert into tmpfinanceiro values (0,0,0,0,0,0);
              end;

              perform fc_debug(' <iptu_criatemptable> FIM CRIACAO DE ESTRUTURAS TEMPORARIAS', lRaise);
              perform fc_debug('', lRaise);

              return rbErro;

            end;
            $$  language 'plpgsql';
SQL
        );
    }

    public function upFracionalote()
    {
        $this->execute(<<<SQL
            drop function if exists fc_iptu_fracionalote(integer,integer,boolean,boolean);
            drop function if exists fc_iptu_fracionalote(integer,integer,boolean,boolean,boolean);

            drop   type if exists     tp_iptu_fracionalote;
            create type tp_iptu_fracionalote as ( rnFracao  numeric,
            rtDemo    text,
            rtMsgerro text,
            rbErro    boolean);

            create or replace function fc_iptu_fracionalote(integer,integer,boolean,boolean) returns tp_iptu_fracionalote as
            $$
            declare

              iMatricula            alias for $1;
              iAnousu               alias for $2;
              bMostrademo           alias for $3;
              lRaise                alias for $4;

              rtp_iptu_fracionalote tp_iptu_fracionalote%ROWTYPE;

              begin

                rtp_iptu_fracionalote.rnFracao  := 0;
                rtp_iptu_fracionalote.rtDemo    := '';
                rtp_iptu_fracionalote.rtMsgerro := '';
                rtp_iptu_fracionalote.rbErro    := 'f';

                select *
                  into rtp_iptu_fracionalote
                  from fc_iptu_fracionalote(iMatricula, iAnousu, bMostrademo, lRaise, true);

                return rtp_iptu_fracionalote;

              end;
            $$  language 'plpgsql';


            create or replace function fc_iptu_fracionalote(integer,integer,boolean,boolean,boolean) returns tp_iptu_fracionalote as
            $$
            declare

              iMatricula 	           alias for $1;
              iAnousu    	           alias for $2;
              bMostrademo            alias for $3; --Não utilizada no escopo
              lRaise                 alias for $4;
              lAtualizaFracaoForcada alias for $5;

              cSetor	               char(4);
              cQuadra	               char(4);
              cLote		               char(4);
              iIptufrac	             integer;
              fTotalAreaConstruida   numeric;
              iTotalMatriculas	     integer;
              tManual 	             text     default '';
              iIdbql 	               integer  default 0;
              rnFracao 	             numeric  default 0;
              nAreacalc	             numeric  default 0;
              nJ01_fracao            numeric  default 0;
              lFracionaIdbql         boolean;

              rFracao	               record;

              rtp_iptu_fracionalote  tp_iptu_fracionalote%ROWTYPE;

              begin

                perform fc_debug('', lRaise);
                perform fc_debug(' <fracionalote> INICIANDO FRACIONAMENTO DO LOTE...', lRaise);

                lFracionaIdbql := ( case when fc_getsession('DB_fraciona_lote_idbql') is null then false else true end );

                rtp_iptu_fracionalote.rnFracao  := 0;
                rtp_iptu_fracionalote.rtDemo    := '';
                rtp_iptu_fracionalote.rtMsgerro := '';
                rtp_iptu_fracionalote.rbErro    := 'f';

                select j01_idbql
                  into iIdbql
                  from iptubase
                 where j01_matric = iMatricula;

                select j34_setor, j34_quadra, j34_lote
                  into cSetor, cQuadra, cLote
                  from lote
                 where j34_idbql = iIdbql;

                /**
                 * Conta quantas Matriculas tem para o lote da Matricula a ser calculada
                 */
                if lFracionaIdbql then
                   perform fc_debug(' <fracionalote> Fracionamento por Idbql: '||iIdbql, lRaise);

                   select count(j01_idbql)
                     into iTotalMatriculas
                     from iptubase
                          inner join lote on j01_idbql = j34_idbql
                    where j01_baixa is null
                      and j34_idbql  = iIdbql;
                else
                   perform fc_debug(' <fracionalote> fracionamento por Setor: '||cSetor||' - Quadra: '||cQuadra||' Lote: '||cLote, lRaise);

                   select count(j01_idbql)
                     into iTotalMatriculas
                     from iptubase
                          inner join lote on j01_idbql = j34_idbql
                    where j01_baixa is null
                      and j34_setor  = cSetor
                      and j34_quadra = cQuadra
                      and j34_lote   = cLote;
                end if;

                perform fc_debug(' <fracionalote> iMatricula          : ' || iMatricula, lRaise);
                perform fc_debug(' <fracionalote> fracao              : ' || rnFracao, lRaise);
                perform fc_debug(' <fracionalote> total de iMatriculas: ' || iTotalMatriculas, lRaise);

                if iTotalMatriculas = 1 then

                    if rnFracao is null or rnFracao = 0 then
                      rnFracao = 100::numeric;
                    else

                      perform fc_debug(' <fracionalote> Calculando area construida da iMatricula... ' || iMatricula, lRaise);

                      /**
                       * Retorna a area total construida da MATRICULA
                       */
                       select into nAreacalc fc_iptu_getareaconstrmat( iMatricula );

                       perform fc_debug(' <fracionalote> Fracao de novo: ' || rnFracao, lRaise);
                       perform fc_debug(' <fracionalote> fracaocalc: ' || nAreacalc, lRaise);

                       if nAreacalc is null or nAreacalc = 0 then
                         rnFracao = 100;
                       else

                         rnFracao = ( (nAreacalc / rnFracao ) * 100 );
                         perform fc_debug(' <fracionalote> nAreacalc: '||nAreacalc||' - rnFracao: ' || rnFracao, lRaise);
                       end if;

                    end if;

                else

                  /**
                   * Retorna a area total construida do LOTE
                   */
                  if lFracionaIdbql then
                     select into fTotalAreaConstruida fc_iptu_getareaconstrloteidbql(iIdbql);
                     perform fc_debug(' <fracionalote> Busca area total construida por idbql: '||fTotalAreaConstruida, lRaise);
                  else
                     select into fTotalAreaConstruida fc_iptu_getareaconstrlote(cSetor,cQuadra,cLote);
                     perform fc_debug(' <fracionalote> Busca area total construida por Setor/Quadra/Lote: '||fTotalAreaConstruida, lRaise);
                  end if;

                  perform fc_debug(' <fracionalote> Total construido no lote: ' || fTotalAreaConstruida, lRaise);

                  tManual := tManual || 'total construido no lote: ' || fTotalAreaConstruida || ' - ';

                  if fTotalAreaConstruida = 0 then

                    select j01_fracao
                      into nJ01_fracao
                      from iptubase
                     where j01_matric = iMatricula;

                    if nJ01_fracao = 0 or nJ01_fracao is null then

                      if lAtualizaFracaoForcada then
                        update iptubase set j01_fracao = 0 where j01_idbql = iIdbql;
                      end if;

                      rnFracao = 100::numeric;
                    else
                      rnFracao = nJ01_fracao;
                    end if;

                  else

                    perform fc_debug(' <fracionalote> Fraciona rFracao ', lRaise);

                    for rFracao in

                        select j01_matric, sum(j39_area)
                          from iptubase
                               inner join iptuconstr on j39_matric = j01_matric
                         where j01_baixa  is null
                           and j39_dtdemo is null
                           and j01_matric = iMatricula
                      group by j01_matric loop

                      perform fc_debug(' <fracionalote> processando fracao iMatricula: '||coalesce(rFracao.j01_matric,0)||' - construido desta: ' || coalesce(rFracao.sum,0), lRaise );

                      select j25_matric
                        into iIptufrac
                        from iptufrac
                       where j25_matric = rFracao.j01_matric
                         and j25_anousu = iAnousu;

                      perform fc_debug(' <fracionalote>    iptufrac: ' || coalesce( iIptufrac, 0 ), lRaise);

                      if iIptufrac is null or iIptufrac = 0 then

                        perform fc_debug(' <fracionalote>    insert no iptufrac', lRaise);
                        insert into iptufrac values (iAnousu, rFracao.j01_matric, iIdbql, rFracao.sum / fTotalAreaConstruida * 100);
                      else

                        perform fc_debug(' <fracionalote>    update no iptufrac', lRaise);
                        update iptufrac
                           set j25_fracao = rFracao.sum / fTotalAreaConstruida * 100,
                               j25_idbql  = iIdbql
                         where j25_matric = rFracao.j01_matric
                           and j25_anousu = iAnousu;
                      end if;

                    end loop;

                    select j25_fracao
                      into rnFracao
                      from iptufrac
                     where j25_matric = iMatricula
                       and j25_anousu = iAnousu;

                    if rnFracao is null or rnFracao = 0 then
                      rnFracao = 100::numeric;
                    end if;

                  end if;

                end if;

                select j01_fracao
                  into nJ01_fracao
                  from iptubase
                where j01_matric = iMatricula;

                if nJ01_fracao is not null and nJ01_fracao > 0 then
                  rnFracao = nJ01_fracao;
                end if;

                rtp_iptu_fracionalote.rnFracao := rnFracao;
                rtp_iptu_fracionalote.rtDemo   := tManual;

                perform fc_debug(' <fracionalote> texto demonstrativo :' || tManual, lRaise);
                perform fc_debug(' <fracionalote> FIM FRACIONAMENTO DO LOTE', lRaise);
                perform fc_debug(' ', lRaise);

                return rtp_iptu_fracionalote;

              end;
              $$  language 'plpgsql';
SQL
        );
    }

    public function downFracionalote()
    {
        $this->execute(<<<SQL
            create or replace function fc_iptu_fracionalote(integer,integer,boolean,boolean,boolean) returns tp_iptu_fracionalote as
            $$
            declare

              iMatricula 	           alias for $1;
              iAnousu    	           alias for $2;
              bMostrademo            alias for $3; --N?o utilizada no escopo
              lRaise                 alias for $4;
              lAtualizaFracaoForcada alias for $5;

              cSetor	               char(4);
              cQuadra	               char(4);
              cLote		               char(4);
              iIptufrac	             integer;
              fTotalAreaConstruida   numeric;
              iTotalMatriculas	     integer;
              tManual 	             text     default '';
              iIdbql 	               integer  default 0;
              rnFracao 	             numeric  default 0;
              nAreacalc	             numeric  default 0;
              nJ01_fracao            numeric  default 0;

              rFracao	               record;

              rtp_iptu_fracionalote  tp_iptu_fracionalote%ROWTYPE;

              begin

                perform fc_debug('', lRaise);
                perform fc_debug(' <fracionalote> INICIANDO FRACIONAMENTO DO LOTE...', lRaise);

                rtp_iptu_fracionalote.rnFracao  := 0;
                rtp_iptu_fracionalote.rtDemo    := '';
                rtp_iptu_fracionalote.rtMsgerro := '';
                rtp_iptu_fracionalote.rbErro    := 'f';

                select j01_idbql
                  into iIdbql
                  from iptubase
                 where j01_matric = iMatricula;

                select j34_setor, j34_quadra, j34_lote
                  into cSetor, cQuadra, cLote
                  from lote
                 where j34_idbql = iIdbql;

                perform fc_debug(' <fracionalote> Setor: '||cSetor||' - Quadra: '||cQuadra||' Lote: '||cLote, lRaise);

                /**
                 * Conta quantas Matriculas tem para o lote da Matricula a ser calculada
                 */
                select count(j01_idbql)
                  into iTotalMatriculas
                  from iptubase
                       inner join lote on j01_idbql = j34_idbql
                 where j01_baixa is null
                   and j34_setor  = cSetor
                   and j34_quadra = cQuadra
                   and j34_lote   = cLote;

                perform fc_debug(' <fracionalote> iMatricula          : ' || iMatricula, lRaise);
                perform fc_debug(' <fracionalote> fracao              : ' || rnFracao, lRaise);
                perform fc_debug(' <fracionalote> total de iMatriculas: ' || iTotalMatriculas, lRaise);

                if iTotalMatriculas = 1 then

                    if rnFracao is null or rnFracao = 0 then
                      rnFracao = 100::numeric;
                    else

                      perform fc_debug(' <fracionalote> Calculando area construida da iMatricula... ' || iMatricula, lRaise);

                      /**
                       * Retorna a area total contruida da MATRICULA
                       */
                       select into nAreacalc fc_iptu_getareaconstrmat( iMatricula );

                       perform fc_debug(' <fracionalote> Fracao de novo: ' || rnFracao, lRaise);
                       perform fc_debug(' <fracionalote> fracaocalc: ' || nAreacalc, lRaise);

                       if nAreacalc is null or nAreacalc = 0 then
                         rnFracao = 100;
                       else

                         rnFracao = ( (nAreacalc / rnFracao ) * 100 );
                         perform fc_debug(' <fracionalote> nAreacalc: '||nAreacalc||' - rnFracao: ' || rnFracao, lRaise);
                       end if;

                    end if;

                else

                  /**
                   * Retorna a area total contruida do LOTE
                   */
                  select into fTotalAreaConstruida fc_iptu_getareaconstrlote(cSetor,cQuadra,cLote);

                  perform fc_debug(' <fracionalote> Total construido no lote: ' || fTotalAreaConstruida, lRaise);

                  tManual := tManual || 'total construido no lote: ' || fTotalAreaConstruida || ' - ';

                  if fTotalAreaConstruida = 0 then

                    select j01_fracao
                      into nJ01_fracao
                      from iptubase
                     where j01_matric = iMatricula;

                    if nJ01_fracao = 0 or nJ01_fracao is null then

                      if lAtualizaFracaoForcada then
                        update iptubase set j01_fracao = 0 where j01_idbql = iIdbql;
                      end if;

                      rnFracao = 100::numeric;
                    else
                      rnFracao = nJ01_fracao;
                    end if;

                  else

                    perform fc_debug(' <fracionalote> Fraciona rFracao ', lRaise);

                    for rFracao in

                        select j01_matric, sum(j39_area)
                          from iptubase
                               inner join iptuconstr on j39_matric = j01_matric
                         where j01_baixa  is null
                           and j39_dtdemo is null
                           and j01_matric = iMatricula
                      group by j01_matric loop

                      perform fc_debug(' <fracionalote> processando fracao iMatricula: '||coalesce(rFracao.j01_matric,0)||' - contruido desta: ' || coalesce(rFracao.sum,0), lRaise );

                      select j25_matric
                        into iIptufrac
                        from iptufrac
                       where j25_matric = rFracao.j01_matric
                         and j25_anousu = iAnousu;

                      perform fc_debug(' <fracionalote>    iptufrac: ' || coalesce( iIptufrac, 0 ), lRaise);

                      if iIptufrac is null or iIptufrac = 0 then

                        perform fc_debug(' <fracionalote>    insert no iptufrac', lRaise);
                        insert into iptufrac values (iAnousu, rFracao.j01_matric, iIdbql, rFracao.sum / fTotalAreaConstruida * 100);
                      else

                        perform fc_debug(' <fracionalote>    update no iptufrac', lRaise);
                        update iptufrac
                           set j25_fracao = rFracao.sum / fTotalAreaConstruida * 100,
                               j25_idbql  = iIdbql
                         where j25_matric = rFracao.j01_matric
                           and j25_anousu = iAnousu;
                      end if;

                    end loop;

                    select j25_fracao
                      into rnFracao
                      from iptufrac
                     where j25_matric = iMatricula
                       and j25_anousu = iAnousu;

                    if rnFracao is null or rnFracao = 0 then
                      rnFracao = 100::numeric;
                    end if;

                  end if;

                end if;

                select j01_fracao
                  into nJ01_fracao
                  from iptubase
                where j01_matric = iMatricula;

                if nJ01_fracao is not null and nJ01_fracao > 0 then
                  rnFracao = nJ01_fracao;
                end if;

                rtp_iptu_fracionalote.rnFracao := rnFracao;
                rtp_iptu_fracionalote.rtDemo   := tManual;

                perform fc_debug(' <fracionalote> texto demonstrativo :' || tManual, lRaise);
                perform fc_debug(' <fracionalote> FIM FRACIONAMENTO DO LOTE', lRaise);
                perform fc_debug(' ', lRaise);

                return rtp_iptu_fracionalote;

              end;
              $$  language 'plpgsql';
SQL
        );
    }

    public function upGetareaconstrloteidbql()
    {
        $this->execute(<<<SQL
                create or replace function fc_iptu_getareaconstrloteidbql(integer) returns float8 as 
                $$

                select coalesce(sum(j39_area),0)
                  from iptubase
                       inner join iptuconstr on j39_matric = j01_matric
                       inner join lote       on j34_idbql  = j01_idbql
                where j01_baixa  is null
                  and j34_idbql   = $1
                  and j39_dtdemo is null;

                $$ 
                language 'sql';
SQL
        );
    }

    public function downGetareaconstrloteidbql()
    {
        $this->execute(<<<SQL
            DROP FUNCTION fc_iptu_getareaconstrloteidbql;
SQL
        );
    }

    public function upTaxaLixoMaquine()
    {
        $this->execute(<<<SQL
            create or replace function fc_iptu_taxalixo_maq_2019(integer, numeric, integer, numeric, boolean) returns boolean as
            $$

            declare

               iReceita        alias for $1;
               iAliquota       alias for $2;
               iHistCalc       alias for $3;
               iPercIsen       alias for $4;
               lRaise          alias for $5;

               nValTaxa         numeric(15,2) default 0;
               nValTaxaBase     numeric(15,2) default 0;

               iIdbql           integer       default 0;
               iAnousu          integer       default 0;
               iMatric          integer       default 0;
               iMultiplicador   integer       default 0;
               iCaracterCalculo integer       default 0;

               dDataConstr      date;
               dDataBase        date;
               dDataFim         date;

               bPredial         boolean       default false;

               tSql             text          default '';
               tRetorno         text          default '';

            begin

               perform fc_debug(' <iptu_taxalixo> Calculando taxa de lixo', lRaise);
               perform fc_debug(' ',                                           lRaise);
               perform fc_debug(' <iptu_taxalixo> receita: '   || iReceita,    lRaise);
               perform fc_debug(' <iptu_taxalixo> aliq: '      || iAliquota,   lRaise);
               perform fc_debug(' <iptu_taxalixo> historico: ' || iHistCalc,   lRaise);

               -- busca informacoes cadastrais para o calculo

               select idbql, anousu, matric,
                      case when totareaconst > 0 then true
                           else false
                      end
                into iIdbql, iAnousu, iMatric, bPredial
               from tmpdadostaxa limit 1;

               select j35_caract
                 into iCaracterCalculo
               from iptubase inner join lote     on j34_idbql  = j01_idbql
                             inner join carlote  on j35_idbql  = j34_idbql
                             inner join caracter on j31_codigo = j35_caract
                                                and j31_grupo  = 1
               where j01_matric = iMatric;

               if bPredial = false or iCaracterCalculo = 3 then
                  perform fc_debug('Verifica se existe caracteristica do grupo 50 para calcular a taxa de lixo para terrenos', lRaise);

                  select j74_fator
                    into nValTaxaBase
                  from carlote inner join caracter on j31_codigo = j35_caract
                               inner join carfator on j74_anousu = iAnousu
                                                  and j74_caract = j35_caract
                  where j35_idbql = iIdbql
                    and j31_grupo = 50;
               else
                  perform fc_debug('Verifica se existe caracteristica do grupo 60 para calcular a taxa de lixo para predios', lRaise);

                  select j74_fator
                    into nValTaxaBase
                  from carconstr inner join caracter on j31_codigo = j48_caract
                                 inner join carfator on j74_anousu = iAnousu
                                                    and j74_caract = j48_caract
                  where j48_matric = iMatric
                    and j31_grupo = 60 limit 1;
               end if;

               if nValTaxaBase = 0 or nValTaxaBase is null then
                  if bPredial = false or iCaracterCalculo = 3 then
					select fc_iptu_geterro(106,'do grupo 50. Valor zerado ou não informado. Tabela carfator.')
                    into tRetorno;
                  else
					select fc_iptu_geterro(106,'do grupo 60. Valor zerado ou não informado. Tabela carfator.')
                    into tRetorno;
                  end if;

                  return false;
               end if;

			   perform fc_debug('Verifica a data da construção para calcular proporcional a taxa, se necessário.', lRaise);

               dDataBase := (iAnousu||'-01-01')::date;
               dDataFim  := (iAnousu||'-12-31')::date;

               select coalesce(j39_dtlan,dDataBase)
                  into dDataConstr
               from iptuconstr
               where j39_matric = iMatric
                 and j39_dtdemo is null
               order by j39_dtlan;

               if dDataConstr > dDataBase and iCaracterCalculo <> 3 then

                  select count(*)
                    into iMultiplicador
                  from generate_series(dDataConstr, dDataFim, INTERVAL '1 month');

                  nValTaxa := ((nValTaxaBase / 12) * iMultiplicador);

               else

                  nValTaxa := nValTaxaBase;

               end if;

               insert into tmptaxapercisen values (iReceita,iPercIsen,0,nValTaxa);

               if iPercIsen > 0 then
                 nValTaxa := nValTaxa * (100 - iPercIsen) / 100;
               end if;

               perform fc_debug(' <iptu_taxalixo> Percentual Isencao: ' || iPercIsen, lRaise);
               perform fc_debug(' <iptu_taxalixo> Valor final da taxa: ' || nValTaxa, lRaise);

               tSql := 'insert into tmprecval values ('||iReceita||','||nValTaxa||','||iHistCalc||',true)';

               execute tSql;

               return true;

            end;

            $$ language 'plpgsql';
SQL
        );
    }

    public function downTaxaLixoMaquine()
    {
        $this->execute(<<<SQL
            create or replace function fc_iptu_taxalixo_maq_2019(integer, numeric, integer, numeric, boolean) returns boolean as
            $$

            declare

               iReceita        alias for $1;
               iAliquota       alias for $2;
               iHistCalc       alias for $3;
               iPercIsen       alias for $4;
               lRaise          alias for $5;

               nValTaxa         numeric(15,2) default 0;
               nValTaxaBase     numeric(15,2) default 0;

               iIdbql           integer       default 0;
               iAnousu          integer       default 0;
               iMatric          integer       default 0;
               iMultiplicador   integer       default 0;
               iCaracterCalculo integer       default 0;

               dDataConstr      date;
               dDataBase        date;

               bPredial         boolean       default false;

               tSql             text          default '';
               tRetorno         text          default '';

            begin

               perform fc_debug(' <iptu_taxalixo> Calculando taxa de lixo', lRaise);
               perform fc_debug(' ',                                           lRaise);
               perform fc_debug(' <iptu_taxalixo> receita: '   || iReceita,    lRaise);
               perform fc_debug(' <iptu_taxalixo> aliq: '      || iAliquota,   lRaise);
               perform fc_debug(' <iptu_taxalixo> historico: ' || iHistCalc,   lRaise);

               -- busca informacoes cadastrais para o calculo

               select idbql, anousu, matric,
                      case when totareaconst > 0 then true
                           else false
                      end
                into iIdbql, iAnousu, iMatric, bPredial
               from tmpdadostaxa limit 1;

               select j35_caract
                 into iCaracterCalculo
               from iptubase inner join lote     on j34_idbql  = j01_idbql
                             inner join carlote  on j35_idbql  = j34_idbql
                             inner join caracter on j31_codigo = j35_caract
                                                and j31_grupo  = 1
               where j01_matric = iMatric;

               if bPredial = false or iCaracterCalculo = 3 then
                  perform fc_debug('Verifica se existe caracteristica do grupo 50 para calcular a taxa de lixo para terrenos', lRaise);

                  select j74_fator
                    into nValTaxaBase
                  from carlote inner join caracter on j31_codigo = j35_caract
                               inner join carfator on j74_anousu = iAnousu
                                                  and j74_caract = j35_caract
                  where j35_idbql = iIdbql
                    and j31_grupo = 50;
               else
                  perform fc_debug('Verifica se existe caracteristica do grupo 60 para calcular a taxa de lixo para predios', lRaise);

                  select j74_fator
                    into nValTaxaBase
                  from carconstr inner join caracter on j31_codigo = j48_caract
                                 inner join carfator on j74_anousu = iAnousu
                                                    and j74_caract = j48_caract
                  where j48_matric = iMatric
                    and j31_grupo = 60 limit 1;
               end if;

               if nValTaxaBase = 0 or nValTaxaBase is null then
                  if bPredial = false or iCaracterCalculo = 3 then
                    select fc_iptu_geterro(106,'do grupo 50. Valor zerado ou n?o informado. Tabela carfator.')
                    into tRetorno;
                  else
                    select fc_iptu_geterro(106,'do grupo 60. Valor zerado ou n?o informado. Tabela carfator.')
                    into tRetorno;
                  end if;

                  return false;
               end if;

               perform fc_debug('Verifica a data da constru??o para calcular proporcional a taxa, se necess?rio.', lRaise);

               dDataBase := (iAnousu||'-01-01')::date;

               select coalesce(j39_dtlan,dDataBase)
                  into dDataConstr
               from iptuconstr
               where j39_matric = iMatric
                 and j39_dtdemo is null
               order by j39_dtlan;

               if dDataConstr > dDataBase then

                  select count(*) - 1
                    into iMultiplicador
                  from generate_series(dDataBase,dDataConstr, INTERVAL '1 month');

                  nValTaxa := ((nValTaxaBase / 12) * iMultiplicador);

               else

                  nValTaxa := nValTaxaBase;

               end if;

               insert into tmptaxapercisen values (iReceita,iPercIsen,0,nValTaxa);

               if iPercIsen > 0 then
                 nValTaxa := nValTaxa * (100 - iPercIsen) / 100;
               end if;

               perform fc_debug(' <iptu_taxalixo> Percentual Isencao: ' || iPercIsen, lRaise);
               perform fc_debug(' <iptu_taxalixo> Valor final da taxa: ' || nValTaxa, lRaise);

               tSql := 'insert into tmprecval values ('||iReceita||','||nValTaxa||','||iHistCalc||',true)';

               execute tSql;

               return true;

            end;

            $$ language 'plpgsql';
SQL
        );
    }
}
