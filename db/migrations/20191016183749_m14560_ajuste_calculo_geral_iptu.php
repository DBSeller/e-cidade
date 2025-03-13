<?php

use Classes\PostgresMigration;

class M14560AjusteCalculoGeralIptu extends PostgresMigration
{
    public function up()
    {
        $this->upPlIptuCriatempTable();
        $this->upPlDebug();
    }

    public function down()
    {
        $this->downPlIptuCriatempTable();
        $this->downPlDebug();
    }

    public function upPlIptuCriatempTable()
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
                create temporary table tmp_cadvenc(
                    q92_codigo integer,
                    q92_tipo integer,
                    q92_hist integer,
                    q92_vlrminimo float8,
                    q82_parc integer,
                    q82_venc date,
                    q82_perc float8,
                    q82_hist integer
                );
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

    public function downPlIptuCriatempTable()
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

    public function upPlDebug()
    {
        $this->execute(<<<SQL
            create or replace function fc_debug(text,boolean,boolean,boolean) returns text as
            $$
            declare

                tMensagem      alias for $1;
                lPutMsg        alias for $2;
                lInicioDebug   alias for $3;
                lFinalDebug    alias for $4;

                    vFormatacao    varchar;

                tMsg           text ;

            begin
            return '';

            if lInicioDebug is true and lPutMsg is true then

                perform fc_startsession();
                perform fc_putsession( 'DB_debug','' );

            end if;


            if lPutMsg then

                vFormatacao := '\n| MSG - '||lpad( extract(day from now()),2,'0' )||'/'||lpad( extract(month from now()),2,'0' )||'/'||extract(year from now() );
                vFormatacao := vFormatacao||' - '||lpad( extract(hour from now()),2,'0' )||':'||lpad( extract(minutes from now()),2,'0' )||':'||lpad( round(extract(seconds from now())),2,'0' )||' | - ' ;

                tMsg :=  coalesce(fc_getsession('DB_debug'),'')||vFormatacao||coalesce(tMensagem,'VALOR NULO');

                perform fc_putsession( 'DB_debug',tMsg );

            end if;

            if lFinalDebug is true and lPutMsg is true then

                return fc_getsession( 'DB_debug' );

            else

                return '';

            end if;

            end;
            $$ language 'plpgsql';

            /**
            * wrapper para Funcao fc_debug
            *
            * @param tMensagem         text     Mensagem
            * @param lPutMsg           boolean  Se coloca ou nao a mensagem na sessao
            * @param lInicioDebug      boolean  Se é inicio da sessao de debug(para iniciar as sessoes e limpar as variaveis de debug)
            * @param lFinalDebug       boolean  Se é finalo da sessao de debug(para mostrar a saida do debug)
            *
            * @return void             void     do debito a ser pesquisado

            * @author Robson Inacio
            * @since  20/02/2008
            *
            * \$id$
            */

            create or replace function fc_debug(text,boolean) returns text as
            $$
            declare

                tMensagem      alias for $1;
                lPutMsg        alias for $2;

                tMsg           text ;

            begin
            return '';
            return fc_debug(tMensagem,lPutMsg,false,false);


            end;
            $$ language 'plpgsql';


            /**
            * wrapper para Funcao fc_debug
            *
            * @param tMensagem         text     Mensagem
            *
            * @return void             void

            * @author Robson Inacio
            * @since  20/02/2008
            *
            * \$id$
            */

            create or replace function fc_debug(text) returns text as
            $$
            declare

                tMensagem      alias for $1;

                lRaise         boolean default false;

            begin
            return '';
            lRaise := ( case when fc_getsession('DB_debugon') is null then false else true end );

            return fc_debug(tMensagem,lRaise,false,false);

            end;
            $$ language 'plpgsql';
SQL
        );
    }

    public function downPlDebug()
    {
        $this->execute(<<<SQL
            create or replace function fc_debug(text,boolean,boolean,boolean) returns text as
            $$
            declare

                tMensagem      alias for $1;
                lPutMsg        alias for $2;
                lInicioDebug   alias for $3;
                lFinalDebug    alias for $4;

                    vFormatacao    varchar;

                tMsg           text ;

            begin

            if lInicioDebug is true and lPutMsg is true then

                perform fc_startsession();
                perform fc_putsession( 'DB_debug','' );

            end if;


            if lPutMsg then

                vFormatacao := '\n| MSG - '||lpad( extract(day from now()),2,'0' )||'/'||lpad( extract(month from now()),2,'0' )||'/'||extract(year from now() );
                vFormatacao := vFormatacao||' - '||lpad( extract(hour from now()),2,'0' )||':'||lpad( extract(minutes from now()),2,'0' )||':'||lpad( round(extract(seconds from now())),2,'0' )||' | - ' ;

                tMsg :=  coalesce(fc_getsession('DB_debug'),'')||vFormatacao||coalesce(tMensagem,'VALOR NULO');

                perform fc_putsession( 'DB_debug',tMsg );

            end if;

            if lFinalDebug is true and lPutMsg is true then

                return fc_getsession( 'DB_debug' );

            else

                return '';

            end if;

            end;
            $$ language 'plpgsql';

            /**
            * wrapper para Funcao fc_debug
            *
            * @param tMensagem         text     Mensagem
            * @param lPutMsg           boolean  Se coloca ou nao a mensagem na sessao
            * @param lInicioDebug      boolean  Se é inicio da sessao de debug(para iniciar as sessoes e limpar as variaveis de debug)
            * @param lFinalDebug       boolean  Se é finalo da sessao de debug(para mostrar a saida do debug)
            *
            * @return void             void     do debito a ser pesquisado

            * @author Robson Inacio
            * @since  20/02/2008
            *
            * \$id$
            */

            create or replace function fc_debug(text,boolean) returns text as
            $$
            declare

                tMensagem      alias for $1;
                lPutMsg        alias for $2;

                tMsg           text ;

            begin

            return fc_debug(tMensagem,lPutMsg,false,false);


            end;
            $$ language 'plpgsql';


            /**
            * wrapper para Funcao fc_debug
            *
            * @param tMensagem         text     Mensagem
            *
            * @return void             void

            * @author Robson Inacio
            * @since  20/02/2008
            *
            * \$id$
            */

            create or replace function fc_debug(text) returns text as
            $$
            declare

                tMensagem      alias for $1;

                lRaise         boolean default false;

            begin

            lRaise := ( case when fc_getsession('DB_debugon') is null then false else true end );

            return fc_debug(tMensagem,lRaise,false,false);

            end;
            $$ language 'plpgsql';
SQL
        );
    }
}
