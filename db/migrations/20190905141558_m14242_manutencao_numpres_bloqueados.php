<?php

use Classes\PostgresMigration;

class M14242ManutencaoNumpresBloqueados extends PostgresMigration
{
    public function up()
    {
        $sql = "
            drop function if exists fc_executa_baixa_banco(integer, date);
            create or replace function fc_executa_baixa_banco( cod_ret integer,
                                                            datausu date,
                                                            OUT processado boolean,
                                                            OUT codigo integer,
                                                            OUT descricao text)
            returns setof record as
            $$
            declare

            iInstitSessao                  integer;
            iAnoSessao                     integer;
            iRows                          integer;
            iRegistroAProcessar            integer;
            iOrigensDuplicadas             integer default 0;
            iCodigoRetornoBaixa            integer default 0;
            -- variavel de controle do numpre , se tiver ativado o pgto parcial, e essa variavel for dif. de 0
            -- os numpres a partir dele serao tratados como pgto parcial, abaixo, sem pgto parcial
            iNumprePagamentoParcial        integer default 0;
            iQuantidadeRegistros           integer default 0;

            sRetornoBaixa                  varchar;

            lAtivaPgtoParcial              boolean default false;
            lUltimoProcessamento           boolean default false;
            lRaise                         boolean default false;
            sDebug                         text;
            r_idret                        record;

            -- Utilizado para armazenar as informações de retorno da função que executa a baixa
            rRetornoBaixa                  record;
            sMensagemErro                  text;

            begin

            -- Busca Dados Sessão
            iInstitSessao := cast(fc_getsession('DB_instit') as integer);
            if iInstitSessao is null then
                raise exception 'Variavel de sessão [DB_instit] não encontrada.';
            end if;

            iAnoSessao    := cast(fc_getsession('DB_anousu') as integer);
            if iAnoSessao is null then
                raise exception 'Variavel de sessão [DB_anousu] não encontrada.';
            end if;

            --
            -- Validamos os dados da sessão
            --
            if cast( fc_getsession('DB_id_usuario') as integer) is null then
                raise exception 'Variavel de sessão [DB_id_usuario] não encontrada.';
            end if;

            if cast( fc_getsession('DB_datausu') as date) is null then
                raise exception 'Variavel de sessão [DB_datausu] não encontrada.';
            end if;

            if cast( fc_getsession('DB_use_pcasp') as boolean) is null then
                raise exception 'Variavel de sessão [DB_use_pcasp] não encontrada.';
            end if;

            lRaise        := ( case when fc_getsession('DB_debugon') is null then false else true end );

            if lRaise is true then
                perform fc_debug('<PreProcessamento> - ###############################################',lRaise,true,false);
                perform fc_debug('<PreProcessamento> -  INICIO BAIXA DE BANCO    <PREPROCESSAMENTO>   ',lRaise,false,false);
                perform fc_debug('<PreProcessamento> - ###############################################',lRaise,false,false);

                raise info '';
                raise info ' Inicio do Processamento da Baixa de Banco...';
                raise info '';

            end if;

            select k03_pgtoparcial
                into lAtivaPgtoParcial
                from numpref
            where k03_instit = iInstitSessao
                and k03_anousu = iAnoSessao;

            /**
             * Caso NAO exista pagamento parcial ativo, apenas executa a baixa de banco.
             */
            if lAtivaPgtoParcial is false then

                if lRaise is true then
                perform fc_debug('<PreProcessamento> '                                     ,lRaise,false,false);
                perform fc_debug('<PreProcessamento> Pagamento Parcial não está ativado...',lRaise,false,false);
                perform fc_debug('<PreProcessamento> '                                     ,lRaise,false,false);
                end if;
                /**
                 * cria estrutura temporaria
                 */
                perform fc_baixa_banco_estrutura_temporaria( cod_ret );

                /**
                 * Inserimos os IDRETS referente aos recibos originados de um empenho de prestação de contas
                 * que tenham o seu VALOR PAGO diferente do VALOR ORIGINAL do recibo
                 */
                insert into tmpnaoprocessar
                    select distinct disbanco.idret
                    from empprestarecibo
                            inner join recibo   on recibo.k00_numpre   = empprestarecibo.e170_numpre
                                            and recibo.k00_numpar   = empprestarecibo.e170_numpar
                            inner join disbanco on disbanco.k00_numpre = recibo.k00_numpre
                                            and disbanco.k00_numpar = recibo.k00_numpar
                    where disbanco.vlrpago <> recibo.k00_valor
                        and disbanco.codret = cod_ret;

                begin

                select baixa.codigo_retorno, baixa.descricao
                    from fc_baixabanco( cod_ret, datausu ) as baixa
                    into rRetornoBaixa;

                processado := (rRetornoBaixa.codigo_retorno = 1)::boolean;
                codigo     := rRetornoBaixa.codigo_retorno;
                descricao  := rRetornoBaixa.descricao;

                exception
                when raise_exception then

                    get stacked diagnostics sMensagemErro := MESSAGE_TEXT;

                    processado := false;
                    codigo     := null;
                    descricao  := sMensagemErro;
                end;

                return next;
                return;

            end if;

            /**
             * Quando pagamento parcial ativo
             */
            if lRaise is true then
                perform fc_debug('<PreProcessamento> '                                 ,lRaise,false,false);
                perform fc_debug('<PreProcessamento> Pagamento Parcial está ativado...',lRaise,false,false);
                perform fc_debug('<PreProcessamento> '                                 ,lRaise,false,false);
            end if;

            /**
             * buscamos o valor base setado na numpref campo k03_numprepgtoparcial
             */
            select k03_numprepgtoparcial
                into iNumprePagamentoParcial
                from numpref
            where k03_anousu = iAnoSessao
                and k03_instit = iInstitSessao;

            /**
             * Executa as Baixas de Banco.
             */
            while lUltimoProcessamento is false loop

                if lRaise is true then

                perform fc_debug('<PreProcessamento> - ###############################################',lRaise,false,false);
                perform fc_debug('<PreProcessamento> - lUltimoProcessamento: '||lUltimoProcessamento   ,lRaise,false,false);
                perform fc_debug('<PreProcessamento> - ###############################################',lRaise,false,false);
                perform fc_debug('<PreProcessamento> - ###############################################',lRaise,false,false);
                perform fc_debug('<PreProcessamento> -                  INICIO WHILE                  ',lRaise,false,false);
                perform fc_debug('<PreProcessamento> - ###############################################',lRaise,false,false);

                raise info '';
                raise info ' Dentro do WHILE...';
                raise info ' UltimoProcessamento: %',lUltimoProcessamento;
                raise info '';

                end if;

                /**
                 * 0 - Default
                 * 1 - Processado Com sucesso
                 */
                if lRaise is true then
                perform fc_debug('<PreProcessamento> - ###############################################',lRaise,false,false);
                perform fc_debug('<PreProcessamento> - iCodigoRetornoBaixa: '||iCodigoRetornoBaixa||', iQuantidadeRegistros: '||iQuantidadeRegistros,lRaise,false,false);
                end if;

                if iCodigoRetornoBaixa not in (0, 1) then

                lUltimoProcessamento := true;
                continue;
                end if;

                /**
                 * cria estrutura temporaria
                 */
                perform fc_baixa_banco_estrutura_temporaria( cod_ret );

                /**
                 * Pagamentos em Carnê
                 */
                insert into tmp_registrosaprocessar
                select distinct
                    disbanco.k00_numpre,
                    disbanco.k00_numpar,
                    disbanco.idret
                from disbanco
                    left join numprebloqpag  on numprebloqpag.ar22_numpre = disbanco.k00_numpre
                                            and numprebloqpag.ar22_numpar = disbanco.k00_numpar
                where disbanco.codret            = cod_ret
                and disbanco.classi            is false
                and disbanco.instit            = iInstitSessao
                and numprebloqpag.ar22_numpre  is null
                and disbanco.k00_numpre        > 0
                and disbanco.k00_numpar        > 0
                and not exists ( select 1
                                    from tmpnaoprocessar
                                    where idret = disbanco.idret )
                group by disbanco.k00_numpre,
                        disbanco.k00_numpar,
                        disbanco.idret;

                /***
                 * Pagamentos em Recibos da CGF
                 */
                insert into tmp_registrosaprocessar
                select distinct
                    recibopaga.k00_numpre,
                    recibopaga.k00_numpar,
                    disbanco.idret
                from disbanco
                    inner join recibopaga on disbanco.k00_numpre = recibopaga.k00_numnov
                where disbanco.codret = cod_ret
                and case when iNumprePagamentoParcial = 0
                        then true
                        else disbanco.k00_numpre > iNumprePagamentoParcial
                    end
                and disbanco.classi is false
                and not exists ( select 1
                                    from tmpnaoprocessar
                                    where idret = disbanco.idret )
                and disbanco.instit = iInstitSessao;


                /***
                 * Pagamentos em Recibos Avulsos
                 */
                insert into tmp_registrosaprocessar
                select distinct
                    recibo.k00_numpre,
                    recibo.k00_numpar,
                    disbanco.idret
                from disbanco
                    inner join recibo on disbanco.k00_numpre = recibo.k00_numpre
                where disbanco.codret = cod_ret
                and case when iNumprePagamentoParcial = 0
                        then true
                        else disbanco.k00_numpre > iNumprePagamentoParcial
                    end
                and not exists ( select 1
                                    from tmpnaoprocessar
                                    where idret = disbanco.idret )
                and disbanco.classi is false
                and disbanco.instit = iInstitSessao;

                /*
                * Verificamos a quantidade de vezes que a baixa de banco deverá ser processada
                *
                * A lógica a princípio eh ver quantos numpres possuem mais de um idret
                * O maior numero de idret para o mesmo numpre e parcela será a quantidade de vezes que a baixa de banco
                * será processada
                *
                * Por exemplo:
                * Numpre Parcela IdRets
                * 1      1       1,2,3
                * 2      1       4,5
                * 3      1       6
                *
                * A quantidade de vezes que a baixa de banco deverá ser processada é 3 pois é a maior quantidade de idrets
                * para o mesmo numpre e parcela.
                *
                */
                select coalesce( max(qtd), 1)
                into iQuantidadeRegistros
                from ( select array_upper(idrets.array,1)::integer as qtd
                        from (select array_accum(distinct idret) as array
                                from tmp_registrosaprocessar
                                group by numpre, numpar) as idrets
                        group by qtd) as loops;

                if lRaise is true then
                perform fc_debug('<PreProcessamento> - Quantidade de vezes que a baixa de banco será processada: ' || iQuantidadeRegistros, lRaise);
                end if;

                /*
                * Inserimos os idrets a não serem processados neste loop, estes idrets serão processados no proximo loop
                *
                * A lógica é identificar o idret que possui o numpre e parcela de outro idret, o primeiro idret que será
                * processado é o menor, após serão processados os demais idret de acordo com o processamento da baixa de banco
                */
                insert into tmpnaoprocessar
                select distinct idret
                    from tmp_registrosaprocessar
                        inner join ( select numpre,
                                            numpar,
                                            min(idret)
                                        from tmp_registrosaprocessar
                                    group by numpre, numpar
                                    having count(distinct idret) > 1) as duplos on duplos.numpre = tmp_registrosaprocessar.numpre
                                                                                and duplos.numpar = tmp_registrosaprocessar.numpar
                                                                                and idret        <> min
                where not exists ( select 1
                                        from tmpnaoprocessar
                                    where idret = min );

                select array_accum(idret)
                into sDebug
                from tmpnaoprocessar;

                perform fc_debug('<PreProcessamento> - IdRet\s inseridos na tmpNAOprocessar: ' || sDebug, lRaise );

                if iQuantidadeRegistros = 1  then
                lUltimoProcessamento := true;
                end if;

                /**
                 * Inserimos os IDRETS referente aos recibos originados de um empenho de prestação de contas
                 * que tenham o seu VALOR PAGO diferente do VALOR ORIGINAL do recibo
                 */
                insert into tmpnaoprocessar
                    select distinct disbanco.idret
                    from empprestarecibo
                            inner join recibo   on recibo.k00_numpre   = empprestarecibo.e170_numpre
                                            and recibo.k00_numpar   = empprestarecibo.e170_numpar
                            inner join disbanco on disbanco.k00_numpre = recibo.k00_numpre
                                            and disbanco.k00_numpar = recibo.k00_numpar
                    where disbanco.vlrpago <> recibo.k00_valor
                        and disbanco.codret = cod_ret;

                insert into tmpnaoprocessar
                    select distinct idret
                    from disbanco
                            inner join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre
                            inner join numprebloqpag on recibopaga.k00_numpre = ar22_numpre
                                                    and recibopaga.k00_numpar = ar22_numpar
                    where disbanco.codret = cod_ret;


                if lRaise is true then

                raise info '----------------------------------------------------------';
                raise info '  cod_ret: % datausu: % ',cod_ret,datausu;
                raise info '               EXECUTANDO BAIXA DE BANCO                  ';
                raise info '----------------------------------------------------------';

                perform fc_debug('<PreProcessamento> - ##########################################################',lRaise,false,false);
                perform fc_debug('<PreProcessamento> - #               EXECUTANDO BAIXA DE BANCO                #',lRaise,false,false);
                perform fc_debug('<PreProcessamento> - ########################  Debug 1 ########################',lRaise,false,false);
                perform fc_debug('<PreProcessamento> - # cod_ret: '||cod_ret||', datausu: '||datausu||'   #######',lRaise,false,false);
                perform fc_debug('<PreProcessamento> - ##########################################################',lRaise,false,false);

                end if;

                -- Executa a baixa de banco
                begin

                select baixa.codigo_retorno, baixa.descricao
                    from fc_baixabanco( cod_ret, datausu ) as baixa
                    into rRetornoBaixa;

                processado := (rRetornoBaixa.codigo_retorno = 1)::boolean;
                codigo     := rRetornoBaixa.codigo_retorno;
                descricao  := rRetornoBaixa.descricao;

                exception
                when raise_exception then

                    get stacked diagnostics sMensagemErro := MESSAGE_TEXT;

                    processado := false;
                    codigo     := null;
                    descricao  := sMensagemErro;
                end;

                return next;

                iCodigoRetornoBaixa := codigo;
                sRetornoBaixa       := descricao;

                if lRaise is true then

                raise info '';
                raise info 'Resposta Baixa de Banco:     %',sRetornoBaixa;
                raise info '----------------------------------------------------------';

                perform fc_debug('<PreProcessamento> - #############################'||lpad('', length(sRetornoBaixa), '#') ,lRaise,false,false);
                perform fc_debug('<PreProcessamento> - Resposta Baixa de Banco:     '||sRetornoBaixa||'.'                   ,lRaise,false,false);
                perform fc_debug('<PreProcessamento> - #############################'||lpad('', length(sRetornoBaixa), '#') ,lRaise,false,false);

                end if;
            end loop;--Fim do While

            if lRaise is true then

                raise info '';
                raise info ' Fim do Processamento ';
                raise info '';
                raise info ' O log do processamento esta na variavel db_debug gravado na sessao.';
                raise info ' Para ver o log execute: select fc_getsession(''db_debug'');';
                perform fc_debug('<PreProcessamento> - ################  FIM DO PREPROCESSAMENTO ################################' ,lRaise,false,true);

            end if;

            end;

            $$ language 'plpgsql';
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            drop function if exists fc_executa_baixa_banco(integer, date);
            create or replace function fc_executa_baixa_banco( cod_ret integer,
                                                            datausu date,
                                                            OUT processado boolean,
                                                            OUT codigo integer,
                                                            OUT descricao text)
            returns setof record as
            $$
            declare

            iInstitSessao                  integer;
            iAnoSessao                     integer;
            iRows                          integer;
            iRegistroAProcessar            integer;
            iOrigensDuplicadas             integer default 0;
            iCodigoRetornoBaixa            integer default 0;
            -- variavel de controle do numpre , se tiver ativado o pgto parcial, e essa variavel for dif. de 0
            -- os numpres a partir dele serao tratados como pgto parcial, abaixo, sem pgto parcial
            iNumprePagamentoParcial        integer default 0;
            iQuantidadeRegistros           integer default 0;

            sRetornoBaixa                  varchar;

            lAtivaPgtoParcial              boolean default false;
            lUltimoProcessamento           boolean default false;
            lRaise                         boolean default false;
            sDebug                         text;
            r_idret                        record;

            -- Utilizado para armazenar as informações de retorno da função que executa a baixa
            rRetornoBaixa                  record;
            sMensagemErro                  text;

            begin

            -- Busca Dados Sessão
            iInstitSessao := cast(fc_getsession('DB_instit') as integer);
            if iInstitSessao is null then
                raise exception 'Variavel de sessão [DB_instit] não encontrada.';
            end if;

            iAnoSessao    := cast(fc_getsession('DB_anousu') as integer);
            if iAnoSessao is null then
                raise exception 'Variavel de sessão [DB_anousu] não encontrada.';
            end if;

            --
            -- Validamos os dados da sessão
            --
            if cast( fc_getsession('DB_id_usuario') as integer) is null then
                raise exception 'Variavel de sessão [DB_id_usuario] não encontrada.';
            end if;

            if cast( fc_getsession('DB_datausu') as date) is null then
                raise exception 'Variavel de sessão [DB_datausu] não encontrada.';
            end if;

            if cast( fc_getsession('DB_use_pcasp') as boolean) is null then
                raise exception 'Variavel de sessão [DB_use_pcasp] não encontrada.';
            end if;

            lRaise        := ( case when fc_getsession('DB_debugon') is null then false else true end );

            if lRaise is true then
                perform fc_debug('<PreProcessamento> - ###############################################',lRaise,true,false);
                perform fc_debug('<PreProcessamento> -  INICIO BAIXA DE BANCO    <PREPROCESSAMENTO>   ',lRaise,false,false);
                perform fc_debug('<PreProcessamento> - ###############################################',lRaise,false,false);

                raise info '';
                raise info ' Inicio do Processamento da Baixa de Banco...';
                raise info '';

            end if;

            select k03_pgtoparcial
                into lAtivaPgtoParcial
                from numpref
            where k03_instit = iInstitSessao
                and k03_anousu = iAnoSessao;

            /**
             * Caso NAO exista pagamento parcial ativo, apenas executa a baixa de banco.
             */
            if lAtivaPgtoParcial is false then

                if lRaise is true then
                perform fc_debug('<PreProcessamento> '                                     ,lRaise,false,false);
                perform fc_debug('<PreProcessamento> Pagamento Parcial não está ativado...',lRaise,false,false);
                perform fc_debug('<PreProcessamento> '                                     ,lRaise,false,false);
                end if;
                /**
                 * cria estrutura temporaria
                 */
                perform fc_baixa_banco_estrutura_temporaria( cod_ret );

                /**
                 * Inserimos os IDRETS referente aos recibos originados de um empenho de prestação de contas
                 * que tenham o seu VALOR PAGO diferente do VALOR ORIGINAL do recibo
                 */
                insert into tmpnaoprocessar
                    select distinct disbanco.idret
                    from empprestarecibo
                            inner join recibo   on recibo.k00_numpre   = empprestarecibo.e170_numpre
                                            and recibo.k00_numpar   = empprestarecibo.e170_numpar
                            inner join disbanco on disbanco.k00_numpre = recibo.k00_numpre
                                            and disbanco.k00_numpar = recibo.k00_numpar
                    where disbanco.vlrpago <> recibo.k00_valor
                        and disbanco.codret = cod_ret;

                begin

                select baixa.codigo_retorno, baixa.descricao
                    from fc_baixabanco( cod_ret, datausu ) as baixa
                    into rRetornoBaixa;

                processado := (rRetornoBaixa.codigo_retorno = 1)::boolean;
                codigo     := rRetornoBaixa.codigo_retorno;
                descricao  := rRetornoBaixa.descricao;

                exception
                when raise_exception then

                    get stacked diagnostics sMensagemErro := MESSAGE_TEXT;

                    processado := false;
                    codigo     := null;
                    descricao  := sMensagemErro;
                end;

                return next;
                return;

            end if;

            /**
             * Quando pagamento parcial ativo
             */
            if lRaise is true then
                perform fc_debug('<PreProcessamento> '                                 ,lRaise,false,false);
                perform fc_debug('<PreProcessamento> Pagamento Parcial está ativado...',lRaise,false,false);
                perform fc_debug('<PreProcessamento> '                                 ,lRaise,false,false);
            end if;

            /**
             * buscamos o valor base setado na numpref campo k03_numprepgtoparcial
             */
            select k03_numprepgtoparcial
                into iNumprePagamentoParcial
                from numpref
            where k03_anousu = iAnoSessao
                and k03_instit = iInstitSessao;

            /**
             * Executa as Baixas de Banco.
             */
            while lUltimoProcessamento is false loop

                if lRaise is true then

                perform fc_debug('<PreProcessamento> - ###############################################',lRaise,false,false);
                perform fc_debug('<PreProcessamento> - lUltimoProcessamento: '||lUltimoProcessamento   ,lRaise,false,false);
                perform fc_debug('<PreProcessamento> - ###############################################',lRaise,false,false);
                perform fc_debug('<PreProcessamento> - ###############################################',lRaise,false,false);
                perform fc_debug('<PreProcessamento> -                  INICIO WHILE                  ',lRaise,false,false);
                perform fc_debug('<PreProcessamento> - ###############################################',lRaise,false,false);

                raise info '';
                raise info ' Dentro do WHILE...';
                raise info ' UltimoProcessamento: %',lUltimoProcessamento;
                raise info '';

                end if;

                /**
                 * 0 - Default
                 * 1 - Processado Com sucesso
                 */
                if lRaise is true then
                perform fc_debug('<PreProcessamento> - ###############################################',lRaise,false,false);
                perform fc_debug('<PreProcessamento> - iCodigoRetornoBaixa: '||iCodigoRetornoBaixa||', iQuantidadeRegistros: '||iQuantidadeRegistros,lRaise,false,false);
                end if;

                if iCodigoRetornoBaixa not in (0, 1) then

                lUltimoProcessamento := true;
                continue;
                end if;

                /**
                 * cria estrutura temporaria
                 */
                perform fc_baixa_banco_estrutura_temporaria( cod_ret );

                /**
                 * Pagamentos em Carnê
                 */
                insert into tmp_registrosaprocessar
                select distinct
                    disbanco.k00_numpre,
                    disbanco.k00_numpar,
                    disbanco.idret
                from disbanco
                    left join numprebloqpag  on numprebloqpag.ar22_numpre = disbanco.k00_numpre
                                            and numprebloqpag.ar22_numpar = disbanco.k00_numpar
                where disbanco.codret            = cod_ret
                and disbanco.classi            is false
                and disbanco.instit            = iInstitSessao
                and numprebloqpag.ar22_numpre  is null
                and disbanco.k00_numpre        > 0
                and disbanco.k00_numpar        > 0
                and not exists ( select 1
                                    from tmpnaoprocessar
                                    where idret = disbanco.idret )
                group by disbanco.k00_numpre,
                        disbanco.k00_numpar,
                        disbanco.idret;

                /***
                 * Pagamentos em Recibos da CGF
                 */
                insert into tmp_registrosaprocessar
                select distinct
                    recibopaga.k00_numpre,
                    recibopaga.k00_numpar,
                    disbanco.idret
                from disbanco
                    inner join recibopaga on disbanco.k00_numpre = recibopaga.k00_numnov
                where disbanco.codret = cod_ret
                and case when iNumprePagamentoParcial = 0
                        then true
                        else disbanco.k00_numpre > iNumprePagamentoParcial
                    end
                and disbanco.classi is false
                and not exists ( select 1
                                    from tmpnaoprocessar
                                    where idret = disbanco.idret )
                and disbanco.instit = iInstitSessao;


                /***
                 * Pagamentos em Recibos Avulsos
                 */
                insert into tmp_registrosaprocessar
                select distinct
                    recibo.k00_numpre,
                    recibo.k00_numpar,
                    disbanco.idret
                from disbanco
                    inner join recibo on disbanco.k00_numpre = recibo.k00_numpre
                where disbanco.codret = cod_ret
                and case when iNumprePagamentoParcial = 0
                        then true
                        else disbanco.k00_numpre > iNumprePagamentoParcial
                    end
                and not exists ( select 1
                                    from tmpnaoprocessar
                                    where idret = disbanco.idret )
                and disbanco.classi is false
                and disbanco.instit = iInstitSessao;

                /*
                * Verificamos a quantidade de vezes que a baixa de banco deverá ser processada
                *
                * A lógica a princípio eh ver quantos numpres possuem mais de um idret
                * O maior numero de idret para o mesmo numpre e parcela será a quantidade de vezes que a baixa de banco
                * será processada
                *
                * Por exemplo:
                * Numpre Parcela IdRets
                * 1      1       1,2,3
                * 2      1       4,5
                * 3      1       6
                *
                * A quantidade de vezes que a baixa de banco deverá ser processada é 3 pois é a maior quantidade de idrets
                * para o mesmo numpre e parcela.
                *
                */
                select coalesce( max(qtd), 1)
                into iQuantidadeRegistros
                from ( select array_upper(idrets.array,1)::integer as qtd
                        from (select array_accum(distinct idret) as array
                                from tmp_registrosaprocessar
                                group by numpre, numpar) as idrets
                        group by qtd) as loops;

                if lRaise is true then
                perform fc_debug('<PreProcessamento> - Quantidade de vezes que a baixa de banco será processada: ' || iQuantidadeRegistros, lRaise);
                end if;

                /*
                * Inserimos os idrets a não serem processados neste loop, estes idrets serão processados no proximo loop
                *
                * A lógica é identificar o idret que possui o numpre e parcela de outro idret, o primeiro idret que será
                * processado é o menor, após serão processados os demais idret de acordo com o processamento da baixa de banco
                */
                insert into tmpnaoprocessar
                select distinct idret
                    from tmp_registrosaprocessar
                        inner join ( select numpre,
                                            numpar,
                                            min(idret)
                                        from tmp_registrosaprocessar
                                    group by numpre, numpar
                                    having count(distinct idret) > 1) as duplos on duplos.numpre = tmp_registrosaprocessar.numpre
                                                                                and duplos.numpar = tmp_registrosaprocessar.numpar
                                                                                and idret        <> min
                where not exists ( select 1
                                        from tmpnaoprocessar
                                    where idret = min );

                select array_accum(idret)
                into sDebug
                from tmpnaoprocessar;

                perform fc_debug('<PreProcessamento> - IdRet\'s inseridos na tmpNAOprocessar: ' || sDebug, lRaise );

                if iQuantidadeRegistros = 1  then
                lUltimoProcessamento := true;
                end if;

                /**
                 * Inserimos os IDRETS referente aos recibos originados de um empenho de prestação de contas
                 * que tenham o seu VALOR PAGO diferente do VALOR ORIGINAL do recibo
                 */
                insert into tmpnaoprocessar
                    select distinct disbanco.idret
                    from empprestarecibo
                            inner join recibo   on recibo.k00_numpre   = empprestarecibo.e170_numpre
                                            and recibo.k00_numpar   = empprestarecibo.e170_numpar
                            inner join disbanco on disbanco.k00_numpre = recibo.k00_numpre
                                            and disbanco.k00_numpar = recibo.k00_numpar
                    where disbanco.vlrpago <> recibo.k00_valor
                        and disbanco.codret = cod_ret;


                if lRaise is true then

                raise info '----------------------------------------------------------';
                raise info '  cod_ret: % datausu: % ',cod_ret,datausu;
                raise info '               EXECUTANDO BAIXA DE BANCO                  ';
                raise info '----------------------------------------------------------';

                perform fc_debug('<PreProcessamento> - ##########################################################',lRaise,false,false);
                perform fc_debug('<PreProcessamento> - #               EXECUTANDO BAIXA DE BANCO                #',lRaise,false,false);
                perform fc_debug('<PreProcessamento> - ########################  Debug 1 ########################',lRaise,false,false);
                perform fc_debug('<PreProcessamento> - # cod_ret: '||cod_ret||', datausu: '||datausu||'   #######',lRaise,false,false);
                perform fc_debug('<PreProcessamento> - ##########################################################',lRaise,false,false);

                end if;

                -- Executa a baixa de banco
                begin

                select baixa.codigo_retorno, baixa.descricao
                    from fc_baixabanco( cod_ret, datausu ) as baixa
                    into rRetornoBaixa;

                processado := (rRetornoBaixa.codigo_retorno = 1)::boolean;
                codigo     := rRetornoBaixa.codigo_retorno;
                descricao  := rRetornoBaixa.descricao;

                exception
                when raise_exception then

                    get stacked diagnostics sMensagemErro := MESSAGE_TEXT;

                    processado := false;
                    codigo     := null;
                    descricao  := sMensagemErro;
                end;

                return next;

                iCodigoRetornoBaixa := codigo;
                sRetornoBaixa       := descricao;

                if lRaise is true then

                raise info '';
                raise info 'Resposta Baixa de Banco:     %',sRetornoBaixa;
                raise info '----------------------------------------------------------';

                perform fc_debug('<PreProcessamento> - #############################'||lpad('', length(sRetornoBaixa), '#') ,lRaise,false,false);
                perform fc_debug('<PreProcessamento> - Resposta Baixa de Banco:     '||sRetornoBaixa||'.'                   ,lRaise,false,false);
                perform fc_debug('<PreProcessamento> - #############################'||lpad('', length(sRetornoBaixa), '#') ,lRaise,false,false);

                end if;
            end loop;--Fim do While

            if lRaise is true then

                raise info '';
                raise info ' Fim do Processamento ';
                raise info '';
                raise info ' O log do processamento esta na variavel db_debug gravado na sessao.';
                raise info ' Para ver o log execute: select fc_getsession(''db_debug'');';
                perform fc_debug('<PreProcessamento> - ################  FIM DO PREPROCESSAMENTO ################################' ,lRaise,false,true);

            end if;

            end;

            $$ language 'plpgsql';
        ";

        $this->execute($sql);
    }
}
