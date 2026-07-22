<?php

use Classes\PostgresMigration;

class M11400PlIptuNit20142 extends PostgresMigration
{
    public function up()
    {
        $sql = "
            CREATE OR REPLACE FUNCTION fc_calculoiptu_nit_2014(INTEGER, INTEGER, BOOLEAN, BOOLEAN, BOOLEAN, BOOLEAN, BOOLEAN,
                                                               INTEGER, INTEGER, INTEGER)
              RETURNS CHARACTER VARYING
            LANGUAGE plpgsql
            AS $$
            DECLARE
            
              iMatricula ALIAS FOR $1;
              iAnousu ALIAS FOR $2;
              bGerafinanc ALIAS FOR $3;
              bAtualizap ALIAS FOR $4;
              bNovonumpre ALIAS FOR $5;
              bCalculogeral ALIAS FOR $6;
              bDemo ALIAS FOR $7;
              iParcelaini ALIAS FOR $8;
              iParcelafim ALIAS FOR $9;
              iDiasVcto ALIAS FOR $10;
            
              iIdbql     INTEGER DEFAULT 0;
              iNumcgm    INTEGER DEFAULT 0;
              iCodcli    INTEGER DEFAULT 0;
              iCodisen   INTEGER DEFAULT 0;
              iTipois    INTEGER DEFAULT 0;
              iParcelas  INTEGER DEFAULT 0;
              iNumconstr INTEGER DEFAULT 0;
              iZona      INTEGER DEFAULT 0;
            
              dDatabaixa DATE;
            
              nTestada   NUMERIC;
              nAreaLote   NUMERIC;
            
              nAreal      NUMERIC DEFAULT 0;
              nAreac      NUMERIC DEFAULT 0;
              nTotarea    NUMERIC DEFAULT 0;
              nFracao     NUMERIC DEFAULT 0;
              nFracaolote NUMERIC DEFAULT 0;
              nAliquota   NUMERIC DEFAULT 0;
              nIsenaliq   NUMERIC DEFAULT 0;
              nArealo     NUMERIC DEFAULT 0;
              nVvc        NUMERIC(15, 2) DEFAULT 0;
              nVvt                NUMERIC(15, 2) DEFAULT 0;
              nVv                 NUMERIC(15, 2) DEFAULT 0;
              nViptu              NUMERIC(15, 2) DEFAULT 0;
            
              iCaracteristica     INTEGER;
              cSetor              VARCHAR(4);
            
              iFatorCorrecao      INTEGER;
              nValorFatorCorrecao NUMERIC DEFAULT 1;
            
              tRetorno            TEXT DEFAULT '';
              tDemo               TEXT DEFAULT '';
            
              bFinanceiro         BOOLEAN;
              bDadosIptu       BOOLEAN;
              bErro            BOOLEAN;
              iCodErro         INTEGER;
              tErro            TEXT;
              bIsentaxas       BOOLEAN;
              bTempagamento    BOOLEAN;
              bEmpagamento     BOOLEAN;
              bTaxasCalculadas BOOLEAN;
              bRaise           BOOLEAN DEFAULT TRUE; -- true para abilitar raise na funcao principal
              bSubRaise        BOOLEAN DEFAULT TRUE; -- true para abilitar raise nas sub-funcoes
            
              rCfiptu           RECORD;
              nValorBaseCalculo NUMERIC DEFAULT 0;
            
              cRefAnt           VARCHAR(20) DEFAULT NULL;
              bLic              BOOLEAN;
            
            BEGIN
            
              bRaise := (CASE WHEN fc_getsession('DB_debugon') IS NULL
                THEN FALSE
                         ELSE TRUE END); --false;   -- true para abilitar raise notice na funcao principal
              bSubRaise := (CASE WHEN fc_getsession('DB_debugon') IS NULL
                THEN FALSE
                            ELSE TRUE END); --false;   -- true para abilitar raise notice nas sub-funcoes
            
              PERFORM fc_debug('', bRaise, TRUE, FALSE);
            
              /* VERIFICA SE OS PARAMETROS PASSADOS ESTAO CORRETOS */
              SELECT riidbql, rnareal, rnfracao, rinumcgm, rdbaixa, rberro, rtretorno
                  INTO iIdbql, nAreal, nFracao, iNumcgm, dDatabaixa, bErro, tRetorno
              FROM fc_iptu_verificaparametros(iMatricula, iAnousu, iParcelaini, iParcelafim);
            
              PERFORM fc_debug(' <parametros> IDBQL - ' || coalesce(iIdbql, 0));
              PERFORM fc_debug(' <parametros> AREAL - ' || coalesce(nAreal, 0));
              PERFORM fc_debug(' <parametros> FRACAO - ' || coalesce(nFracao, 0));
              PERFORM fc_debug(' <parametros> CGM - ' || coalesce(iNumcgm, 0));
              PERFORM fc_debug(' <parametros> DATABAIXA - ' || dDatabaixa);
              PERFORM fc_debug(' <parametros> ERRO - ' || bErro);
              PERFORM fc_debug(' <parametros> RETORNO - ' || tRetorno);
            
              /* VERIFICA SE O CALCULO PODE SER REALIZADO */
              SELECT rbErro, riCodErro
                  INTO bErro,
                    iCodErro
              FROM fc_iptu_verificacalculo(iMatricula, iAnousu, iParcelaini, iParcelafim);
            
              IF bErro IS TRUE AND bDemo IS FALSE
              THEN
                SELECT fc_iptu_geterro(iCodErro, '') INTO tRetorno;
                RETURN tRetorno;
              END IF;
            
              /* VERIFICA SE MATRICULA ESTA BAIXADA */
              IF dDataBaixa IS NOT NULL AND to_char(dDataBaixa, 'Y') :: INTEGER <= iAnousu
              THEN
                /* criar funcao para exclusao de calculo */
                DELETE
                FROM arrecad USING iptunump
                WHERE k00_numpre = iptunump.j20_numpre
                  AND iptunump.j20_anousu = iAnousu
                  AND iptunump.j20_matric = iMatricula;
            
                DELETE FROM iptunump WHERE j20_anousu = iAnousu
                                       AND j20_matric = iMatricula;
            
                SELECT fc_iptu_geterro(2, '') INTO tRetorno;
                RETURN tRetorno;
              END IF;
            
              /* CRIA AS TABELAS TEMPORARIAS */
              SELECT * INTO bErro FROM fc_iptu_criatemptable(bSubRaise);
            
              /* GUARDA OS PARAMETROS DO CALCULO */
              SELECT *
                  INTO rCfiptu
              FROM cfiptu
                     LEFT JOIN infla ON i02_codigo = j18_infla
                                          AND extract(YEAR FROM i02_data) = iAnousu
              WHERE j18_anousu = iAnousu
              LIMIT 1;
            
              IF rCfiptu.i02_valor IS NULL
              THEN
                --    raise notice 'usando cfíptu.vlrref...';
                nValorBaseCalculo = rCfiptu.j18_vlrref :: NUMERIC;
              ELSE
                --    raise notice 'usando valor do inflator...';
                nValorBaseCalculo = rCfiptu.i02_valor :: NUMERIC;
              END IF;
            
              PERFORM fc_debug('VALOR BASE DE CALCULO: ' || coalesce(nValorBaseCalculo, 0));
              PERFORM fc_debug('MATRICULA: ' || coalesce(iMatricula, 0));
              PERFORM fc_debug('IDBQL: ' || coalesce(iIdbql, 0));
              PERFORM fc_debug('ANOUSU: ' || coalesce(iAnousu, 0));
              PERFORM fc_debug('DEMO: ' || bDemo);
            
              /* FRACIONA LOTE */
              PERFORM fc_debug('CHAMANDO fc_iptu_fracionalote...');
              IF bRaise
              THEN
              --  raise notice 'auiii thiago';
              END IF;
              SELECT rnfracao, rtdemo, rtmsgerro, rberro
                  INTO nFracaolote, tDemo, tRetorno, bErro
              FROM fc_iptu_fracionalote(iMatricula, iAnousu, 'idbql', bDemo, bSubRaise);
            
              UPDATE tmpdadosiptu
              SET fracao = nFracaolote,
                  matric = iMatricula;
            
              PERFORM fc_debug('RETORNO fc_iptu_fracionalote:');
              PERFORM fc_debug(' <retorno> FRACAO DO LOTE : ' || coalesce(nFracaolote, 0));
              PERFORM fc_debug(' <retorno> DEMONS : ' || tDemo);
              PERFORM fc_debug(' <retorno> MSRETORNO : ' || tRetorno);
              PERFORM fc_debug(' <retorno> ERRO : ' || bErro);
            
              /* VERIFICA PAGAMENTOS */
              PERFORM fc_debug('CHAMANDO fc_iptu_verificapag...');
            
              SELECT rbtempagamento, rbempagamento, rtmsgretorno, rberro
                  INTO bTempagamento, bEmpagamento, tRetorno, bErro
              FROM fc_iptu_verificapag(iMatricula, iAnousu, bCalculogeral, bAtualizap, FALSE, bDemo, bSubRaise);
            
              PERFORM fc_debug('RETORNO fc_iptu_verificapag:');
              PERFORM fc_debug(' <retorno> TEMPAGAMENTO: ' || bTempagamento);
              PERFORM fc_debug(' <retorno> EMPAGAMENTO: ' || bEmpagamento);
              PERFORM fc_debug(' <retorno> RETORNO: ' || tRetorno);
              PERFORM fc_debug(' <retorno> ERRO: ' || bErro);
            
              /* CALCULA VALOR DO TERRENO */
              PERFORM fc_debug('CHAMANDO fc_iptu_calculavvt_nit_2014...');
            
              --  raise notice 'aaaaaaaaaaaaaaaaaaa';
              PERFORM j10_vlrter
              FROM iptucalcpadrao
              WHERE j10_matric = iMatricula
                AND j10_anousu = iAnousu;
            
              IF NOT found
              THEN
            
                --raise notice 'passei territorial';
                SELECT rnvvt, rnarea, rberro, riCodErro, rtErro
                    INTO nVvt,
                      nAreac,
                      bErro,
                      iCodErro,
                      tErro
                FROM fc_iptu_calculavvt_nit_2014(iAnousu, iIdbql, nFracaolote, nValorBaseCalculo, bSubRaise);
              END IF; -- Thiago
              PERFORM fc_debug('RETORNO fc_iptu_calculavvt_nit_2014:');
              PERFORM fc_debug(' <retorno> VVT: ' || coalesce(nVvt, 0));
              PERFORM fc_debug(' <retorno> AREA CONTRUIDA: ' || coalesce(nAreac, 0));
              PERFORM fc_debug(' <retorno> ERRO: ' || bErro);
            
              IF bErro IS TRUE
              THEN
                SELECT fc_iptu_geterro(iCodErro, tErro) INTO tRetorno;
                RETURN tRetorno;
              END IF;
            
              --  raise notice 'bbbbbbbbbbbbb';
            
              /* VERIFICA ISENCOES */
              PERFORM fc_debug('PARAMETROS fc_iptu_verificaisencoes...');
            
              SELECT ricodisen, ritipois, rnisenaliq, rbisentaxas, rnarealo
                  INTO iCodisen, iTipois, nIsenaliq, bIsentaxas, nArealo
              FROM fc_iptu_verificaisencoes(iMatricula, iAnousu, bDemo, bSubRaise);
            
              IF iTipois IS NOT NULL
              THEN
                UPDATE tmpdadosiptu SET tipoisen = iTipois;
              END IF;
            
              PERFORM fc_debug('RETORNO fc_iptu_verificaisencoes:');
              PERFORM fc_debug(' <retorno> CODISEN: ' || coalesce(iCodisen, 0));
              PERFORM fc_debug(' <retorno> TIPOISEN: ' || coalesce(iTipois, 0));
              PERFORM fc_debug(' <retorno> ALIQ INSEN: ' || coalesce(nIsenaliq, 0));
              PERFORM fc_debug(' <retorno> INSENTAXAS: ' || bIsentaxas);
              PERFORM fc_debug(' <retorno> AREALO: ' || coalesce(nArealo, 0));
            
              /* CALCULA VALOR DA CONSTRUCAO */
              PERFORM fc_debug('CHAMANDO fc_iptu_calculavvc_nit_2014...');
            
              PERFORM j10_vlrter
              FROM iptucalcpadrao
              WHERE j10_matric = iMatricula
                AND j10_anousu = iAnousu;
            
              IF NOT found
              THEN
                --raise notice 'passei predial';
                SELECT rnvvc, rntotarea, rinumconstr, rberro, riCodErro, rtErro
                    INTO nVvc,
                      nTotarea,
                      iNumconstr,
                      bErro,
                      iCodErro,
                      tErro
                FROM fc_iptu_calculavvc_nit_2014(iMatricula, iAnousu, nValorBaseCalculo, bSubRaise);
              END IF; -- thiago
              PERFORM fc_debug('RETORNO fc_iptu_calculavvc_nit_2014:');
              PERFORM fc_debug(' <retorno> VVC: ' || coalesce(nVvc, 0));
              PERFORM fc_debug(' <retorno> AREA TOTAL: ' || coalesce(nTotarea, 0));
              PERFORM fc_debug(' <retorno> NUMERO DE CONSTRUCOES: ' || coalesce(iNumconstr, 0));
              PERFORM fc_debug(' <retorno> ERRO: ' || bErro);
            
              IF bErro IS TRUE
              THEN
                SELECT fc_iptu_geterro(iCodErro, tErro) INTO tRetorno;
                RETURN tRetorno;
              END IF;
            
              IF nVvc IS NULL OR nVvc = 0 AND iNumconstr <> 0
              THEN
                SELECT fc_iptu_geterro(103, '') INTO tRetorno;
                RETURN tRetorno;
              END IF;
            
              PERFORM predial FROM tmpdadosiptu WHERE predial IS TRUE;
            
              IF found
              THEN
            
                SELECT j35_caract
                    INTO iFatorCorrecao
                FROM carlote
                       INNER JOIN caracter ON j31_codigo = j35_caract
                WHERE j35_idbql = iIdbql
                  AND j31_grupo = 32;
            
                IF iFatorCorrecao = 3201
                THEN
                  nValorFatorCorrecao = 0.60;
                ELSIF iFatorCorrecao = 3202
                  THEN
                    nValorFatorCorrecao = 0.70;
                ELSIF iFatorCorrecao = 3203
                  THEN
                    nValorFatorCorrecao = 0.80;
                ELSIF iFatorCorrecao = 3204
                  THEN
                    nValorFatorCorrecao = 0.90;
                ELSIF iFatorCorrecao = 3205
                  THEN
                    nValorFatorCorrecao = 1.00;
                END IF;
            
              END IF;
            
              SELECT j34_setor
                  INTO cSetor FROM lote WHERE j34_idbql = iIdbql;
            
              SELECT j31_codigo
                  INTO iCaracteristica
              FROM carlote
                     INNER JOIN caracter ON j31_codigo = j35_caract
              WHERE j31_grupo = 11
                AND j35_idbql = iIdbql;
            
              IF iCaracteristica = 1106 AND cSetor = '0106'
              THEN
            
                nVvc = 10000;
              --    nTaxaTlc = 0;
              --    nTaxaIlum = 0;
            
              ELSE
            
                nVvc = nVvc * nValorFatorCorrecao;
                nVvt = nVvt * nValorFatorCorrecao;
            
                IF bRaise
                THEN
                --   raise notice 'nValorFatorCorrecao: % - vt: % - vc: %', nValorFatorCorrecao, nVvt, nVvc;
                END IF;
            
                UPDATE tmpdadosiptu
                SET vvt = nVvt,
                    vvc = nVvc;
                UPDATE tmpiptucale SET valor = nVvc;
            
              END IF;
            
              /* CALCULA O VALOR VENAL */
              nVv := (nVvc + nVvt);
              -- raise notice 'nValorFatorCorrecao: % - vt: % - vc: %', nValorFatorCorrecao, nVvt, nVvc;
              IF bRaise
              THEN
                RAISE NOTICE 'fator de correcao: % - vvc: % - vvt: % - vv: %', nValorFatorCorrecao, nVvc, nVvt, nVv;
              END IF;
            
              SELECT j40_refant INTO cRefAnt FROM iptuant WHERE j40_matric = iMatricula; --and substr(j40_refant,1,6) = '101071';
            
              PERFORM j10_vlrter
              FROM iptucalcpadrao
              WHERE j10_matric = iMatricula
                AND j10_anousu = iAnousu;
              --  if found and cRefAnt is not null then
              IF found
              THEN
                --    raise notice 'achou iptucalcpadrao';
            
                SELECT j10_vlrter * nValorBaseCalculo,
                       coalesce((SELECT sum(j11_vlrcons) FROM iptucalcpadraoconstr WHERE j11_iptucalcpadrao = j10_sequencial), 0) *
                       nValorBaseCalculo,
                       j10_aliq
                    INTO nVvt, nVvc, nAliquota
                FROM iptucalcpadrao
                WHERE j10_matric = iMatricula
                  AND j10_anousu = iAnousu;
            
                IF bRaise
                THEN
                  RAISE NOTICE 'Aliquota: % ', nAliquota;
                  RAISE NOTICE 'Anousu: % ', iAnousu;
                END IF;
            
                SELECT j34_area
                    INTO nArealote FROM lote WHERE j34_idbql = iIdbql;
            
                SELECT j36_testad
                    INTO nTestada
                FROM testada
                       INNER JOIN testpri ON j49_idbql = j36_idbql AND j49_face = j36_face AND j49_codigo = j36_codigo
                WHERE j49_idbql = iIdbql;
            
                --    raise notice 'area lote: %', nAreaLote;
            
                UPDATE tmpdadosiptu SET vvt = nVvt,
                    vvc     = nVvc,
                    areat   = nAreaLote,
                    testada = nTestada;
                UPDATE tmpiptucale SET valor = nVvc;
            
                nVv := (nVvc + nVvt);
            
              --   raise notice 'nValorBaseCalculo: % - valor venal: %', nValorBaseCalculo, nVv, nAliquota;
            
              ELSE
            
                /* BUSCA A ALIQUOTA  */
                PERFORM fc_debug('CHAMANDO fc_iptu_get_aliquota_nit_2014...');
            
                SELECT rnAliquota, rlErro, riCodErro, rtErro
                    INTO nAliquota,
                      bErro,
                      iCodErro,
                      tErro
                FROM fc_iptu_get_aliquota_nit_2014(iMatricula, iIdbql, nVv, iAnousu, bSubRaise);
            
              -- raise notice 'NAO achou iptucalcpadrao';
              END IF;
            
              PERFORM fc_debug('VALOR VENAL: ' || nVv);
            
              IF bRaise
              THEN
                RAISE NOTICE 'vv [valor venal]: %', nVv;
              END IF;
            
            
              PERFORM fc_debug('RETORNO fc_iptu_get_aliquota_nit_2014:');
              PERFORM fc_debug(' <retorno> ALIQUOTA: ' || coalesce(nAliquota, 0));
              PERFORM fc_debug(' <retorno> ERRO: ' || bErro);
            
              IF bErro IS TRUE
              THEN
                SELECT fc_iptu_geterro(iCodErro, tErro) INTO tRetorno;
                RETURN tRetorno;
              END IF;
            
              IF nAliquota IS NULL OR nAliquota = 0
              THEN
                SELECT fc_iptu_geterro(13, '') INTO tRetorno;
                RETURN tRetorno;
              END IF;
            
              --  raise notice 'aliquota: %', nAliquota;
            
              nViptu := nVv * (nAliquota / 100);
              PERFORM fc_debug('VALOR DO IPTU: ' || nViptu);
            
              SELECT count(*)
                  INTO iParcelas
              FROM cadvencdesc
                     INNER JOIN cadvenc ON q92_codigo = q82_codigo
              WHERE q92_codigo = rCfiptu.j18_vencim;
            
              IF NOT found OR iParcelas = 0
              THEN
                SELECT fc_iptu_geterro(14, '') INTO tRetorno;
                RETURN tRetorno;
              END IF;
            
              PERFORM predial FROM tmpdadosiptu WHERE predial IS TRUE;
            
              IF found
              THEN
                INSERT INTO tmprecval VALUES (rCfiptu.j18_rpredi, nViptu, 1, FALSE);
              ELSE
                INSERT INTO tmprecval VALUES (rCfiptu.j18_rterri, nViptu, 1, FALSE);
              END IF;
            
              IF bRaise
              THEN
                RAISE NOTICE 'nViptu 1: %', nViptu;
              END IF;
            
              UPDATE tmpdadosiptu SET viptu = nViptu,
                  codvenc = rCfiptu.j18_vencim,
                  aliq    = nAliquota;
              UPDATE tmpdadostaxa
              SET anousu  = iAnousu,
                  matric  = iMatricula,
                  idbql   = iIdbql,
                  valiptu = nViptu,
                  valref  = nValorBaseCalculo,
                  vvt     = nVvt,
                  nparc   = iParcelas;
            
              /* CALCULA AS TAXAS */
              SELECT db21_codcli
                  INTO iCodcli FROM db_config WHERE prefeitura IS TRUE;
            
              PERFORM fc_debug('CODCLI: ' || iCodcli);
              PERFORM fc_debug('CHAMANDO fc_iptu_calculataxas...');
            
              SELECT fc_iptu_calculataxas(iMatricula, iAnousu, iCodcli, bSubRaise)
                  INTO bTaxasCalculadas;
            
              PERFORM fc_debug('RETORNO fc_iptu_calculataxas:');
            
              IF bRaise
              THEN
                RAISE NOTICE '%', (SELECT fc_debug(' <retorno> TAXASCALCULADAS: ' || bTaxasCalculadas, bRaise, FALSE, TRUE));
              END IF;
            
              IF bRaise
              THEN
                RAISE NOTICE 'aliquota %', nAliquota;
              END IF;
            
              /* MONTA O DEMONSTRATIVO */
              SELECT fc_iptu_demonstrativo_niteroi(iMatricula, iAnousu, iIdbql, bSubRaise)
                  INTO tDemo;
            
              /* GERA FINANCEIRO */
              IF bDemo IS FALSE
              THEN -- se nao for demonstrativo gera o financeiro, caso contrario retorna o demonstrativo
                SELECT fc_iptu_geradadosiptu(iMatricula, iIdbql, iAnousu, nIsenaliq, bDemo, bSubRaise)
                    INTO bDadosIptu;
                IF bGerafinanc
                THEN
                  SELECT fc_iptu_gerafinanceiro(iMatricula, iAnousu, iParcelaini, iParcelafim, bCalculogeral, bTempagamento,
                                                bNovonumpre, bDemo, bSubRaise, iDiasVcto)
                      INTO bFinanceiro;
                END IF;
              ELSE
                RETURN tDemo;
              END IF;
            
              /* VERIFICA E APLICA O DESCONTO DA LIC */
              SELECT fc_iptu_calcula_lic(iMatricula, iAnousu) INTO bLic;
            
              IF bDemo IS FALSE
              THEN
                UPDATE iptucalc SET j23_manual = tDemo WHERE j23_matric = iMatricula
                                                         AND j23_anousu = iAnousu;
                UPDATE iptucalc SET j23_aliq = nAliquota WHERE j23_matric = iMatricula
                                                           AND j23_anousu = iAnousu;
              END IF;
            
              SELECT fc_iptu_geterro(1, '') INTO tRetorno;
              RETURN tRetorno;
            
            END;
            $$;
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql =
<<<SQL
    create or replace function fc_calculoiptu_nit_2014 (integer, integer, boolean, boolean, boolean, boolean, boolean, integer, integer, integer)
    RETURNS CHARACTER VARYING
    LANGUAGE plpgsql
    AS $$
    DECLARE                                                                                                                                                     
                                                                                                                                                                  
       iMatricula       alias   for $1;                                                                                                                           
       iAnousu          alias   for $2;                                                                                                                           
       bGerafinanc      alias   for $3;                                                                                                                           
       bAtualizap       alias   for $4;                                                                                                                           
       bNovonumpre      alias   for $5;                                                                                                                           
       bCalculogeral    alias   for $6;                                                                                                                           
       bDemo            alias   for $7;                                                                                                                           
       iParcelaini      alias   for $8;                                                                                                                           
       iParcelafim      alias   for $9;                                                                                                                           
       iDiasVcto        alias   for $10;                                                                                                                          
                                                                                                                                                                  
       iIdbql           integer default 0;                                                                                                                        
       iNumcgm          integer default 0;                                                                                                                        
       iCodcli          integer default 0;                                                                                                                        
       iCodisen         integer default 0;                                                                                                                        
       iTipois          integer default 0;                                                                                                                        
       iParcelas        integer default 0;                                                                                                                        
       iNumconstr       integer default 0;                                                                                                                        
       iZona            integer default 0;                                                                                                                        
                                                                                                                                                                  
       dDatabaixa       date;                                                                                                                                     
                                                                                                                                                                  
       nTestada         numeric;                                                                                                                                  
       nAreaLote        numeric;                                                                                                                                  
                                                                                                                                                                  
       nAreal           numeric default 0;                                                                                                                        
       nAreac           numeric default 0;                                                                                                                        
       nTotarea         numeric default 0;                                                                                                                        
       nFracao          numeric default 0;                                                                                                                        
       nFracaolote      numeric default 0;                                                                                                                        
       nAliquota        numeric default 0;                                                                                                                        
       nIsenaliq        numeric default 0;                                                                                                                        
       nArealo          numeric default 0;                                                                                                                        
       nVvc             numeric(15,2) default 0;                                                                                                                  
       nVvt             numeric(15,2) default 0;                                                                                                                  
       nVv              numeric(15,2) default 0;                                                                                                                  
       nViptu           numeric(15,2) default 0;                                                                                                                  
                                                                                                                                                                  
       iCaracteristica  integer;                                                                                                                                  
       cSetor           varchar(4);                                                                                                                               
                                                                                                                                                                  
       iFatorCorrecao   integer;                                                                                                                                  
       nValorFatorCorrecao numeric default 1;                                                                                                                     
                                                                                                                                                                  
       tRetorno         text default '';                                                                                                                          
       tDemo            text default '';                                                                                                                          
                                                                                                                                                                  
       bFinanceiro      boolean;                                                                                                                                  
       bDadosIptu       boolean;                                                                                                                                  
       bErro            boolean;                                                                                                                                  
       iCodErro         integer;                                                                                                                                  
       tErro            text;                                                                                                                                     
       bIsentaxas       boolean;                                                                                                                                  
       bTempagamento    boolean;                                                                                                                                  
       bEmpagamento     boolean;                                                                                                                                  
       bTaxasCalculadas boolean;                                                                                                                                  
       bRaise           boolean default true; -- true para abilitar raise na funcao principal                                                                     
       bSubRaise        boolean default true; -- true para abilitar raise nas sub-funcoes                                                                         
                                                                                                                                                                  
       rCfiptu          record;                                                                                                                                   
       nValorBaseCalculo numeric default 0;                                                                                                                       
                                                                                                                                                                  
       cRefAnt          varchar(20) default null;
       bLic             boolean;                                                                                                                  
                                                                                                                                                                  
     begin                                                                                                                                                        
                                                                                                                                                                  
       bRaise    := ( case when fc_getsession('DB_debugon') is null then false else true end ); --false;   -- true para abilitar raise notice na funcao principal 
       bSubRaise := ( case when fc_getsession('DB_debugon') is null then false else true end ); --false;   -- true para abilitar raise notice nas sub-funcoes     
                                                                                                                                                                  
       perform fc_debug('', bRaise, true, false);                                                                                                                 
                                                                                                                                                                  
       /* VERIFICA SE OS PARAMETROS PASSADOS ESTAO CORRETOS */                                                                                                    
       select riidbql, rnareal, rnfracao, rinumcgm, rdbaixa, rberro, rtretorno                                                                                    
         into iIdbql,  nAreal,  nFracao,  iNumcgm,  dDatabaixa, bErro, tRetorno                                                                                   
         from fc_iptu_verificaparametros(iMatricula, iAnousu, iParcelaini, iParcelafim);                                                                          
                                                                                                                                                                  
       perform fc_debug( ' <parametros> IDBQL - ' || coalesce(iIdbql, 0));                                                                                       
       perform fc_debug( ' <parametros> AREAL - ' || coalesce(nAreal, 0));                                                                                       
       perform fc_debug( ' <parametros> FRACAO - ' || coalesce(nFracao, 0));                                                                                     
       perform fc_debug( ' <parametros> CGM - ' || coalesce(iNumcgm, 0));                                                                                        
       perform fc_debug( ' <parametros> DATABAIXA - ' || dDatabaixa);                                                                                            
       perform fc_debug( ' <parametros> ERRO - ' || bErro);                                                                                                       
       perform fc_debug( ' <parametros> RETORNO - ' || tRetorno);                                                                                                 
                                                                                                                                                                  
       /* VERIFICA SE O CALCULO PODE SER REALIZADO */                                                                                                             
       select rbErro,                                                                                                                                             
              riCodErro                                                                                                                                           
         into bErro,                                                                                                                                              
              iCodErro                                                                                                                                            
         from fc_iptu_verificacalculo(iMatricula, iAnousu, iParcelaini, iParcelafim);                                                                             
                                                                                                                                                                  
       if bErro is true and bDemo is false then                                                                                                                   
         select fc_iptu_geterro(iCodErro,'') into tRetorno;                                                                                                       
         return tRetorno;                                                                                                                                         
       end if;                                                                                                                                                    
                                                                                                                                                                  
       /* VERIFICA SE MATRICULA ESTA BAIXADA */                                                                                                                   
       if dDataBaixa is not null and to_char(dDataBaixa,'Y')::integer <= iAnousu then                                                                             
          /* criar funcao para exclusao de calculo */                                                                                                             
          delete from arrecad using iptunump                                                                                                                      
           where k00_numpre = iptunump.j20_numpre                                                                                                                 
             and iptunump.j20_anousu = iAnousu                                                                                                                    
             and iptunump.j20_matric = iMatricula;                                                                                                                
                                                                                                                                                                  
          delete from iptunump                                                                                                                                    
           where j20_anousu = iAnousu                                                                                                                             
             and j20_matric = iMatricula;                                                                                                                         
                                                                                                                                                                  
          select fc_iptu_geterro(2,'') into tRetorno;                                                                                                             
          return tRetorno;                                                                                                                                        
       end if;                                                                                                                                                    
                                                                                                                                                                  
       /* CRIA AS TABELAS TEMPORARIAS */                                                                                                                          
       select * into bErro from fc_iptu_criatemptable(bSubRaise);                                                                                                 
                                                                                                                                                                  
       /* GUARDA OS PARAMETROS DO CALCULO */                                                                                                                      
       select *                                                                                                                                                   
         into rCfiptu                                                                                                                                             
         from cfiptu                                                                                                                                              
              left join infla on i02_codigo = j18_infla                                                                                                           
                             and extract( year from i02_data) = iAnousu                                                                                           
        where j18_anousu = iAnousu                                                                                                                                
        limit 1;                                                                                                                                                  
                                                                                                                                                                  
       if rCfiptu.i02_valor is null then                                                                                                                          
     --    raise notice 'usando cfíptu.vlrref...';                                                                                                                
         nValorBaseCalculo = rCfiptu.j18_vlrref::numeric;                                                                                                         
       else                                                                                                                                                       
     --    raise notice 'usando valor do inflator...';                                                                                                            
         nValorBaseCalculo = rCfiptu.i02_valor::numeric;                                                                                                          
       end if;                                                                                                                                                    
                                                                                                                                                                  
       perform fc_debug('VALOR BASE DE CALCULO: ' || coalesce( nValorBaseCalculo, 0));                                                                            
       perform fc_debug('MATRICULA: ' || coalesce( iMatricula, 0));                                                                                               
       perform fc_debug('IDBQL: ' || coalesce( iIdbql, 0));                                                                                                       
       perform fc_debug('ANOUSU: ' || coalesce( iAnousu, 0));                                                                                                     
       perform fc_debug('DEMO: ' || bDemo);                                                                                                                       
                                                                                                                                                                  
       /* FRACIONA LOTE */                                                                                                                                        
       perform fc_debug('CHAMANDO fc_iptu_fracionalote...');                                                                                                      
       if bRaise then                                                                                                                                             
       --  raise notice 'auiii thiago';                                                                                                                           
       end if;                                                                                                                                                    
       select rnfracao, rtdemo, rtmsgerro, rberro                                                                                                                 
         into nFracaolote, tDemo, tRetorno, bErro                                                                                                                 
         from fc_iptu_fracionalote(iMatricula, iAnousu, 'idbql', bDemo, bSubRaise);                                                                               
                                                                                                                                                                  
       update tmpdadosiptu set fracao = nFracaolote, matric = iMatricula;                                                                                         
                                                                                                                                                                  
       perform fc_debug('RETORNO fc_iptu_fracionalote:');                                                                                                         
       perform fc_debug(' <retorno> FRACAO DO LOTE : ' || coalesce(nFracaolote, 0));                                                                              
       perform fc_debug(' <retorno> DEMONS : ' || tDemo);                                                                                                         
       perform fc_debug(' <retorno> MSRETORNO : ' || tRetorno);                                                                                                   
       perform fc_debug(' <retorno> ERRO : ' || bErro );                                                                                                          
                                                                                                                                                                  
       /* VERIFICA PAGAMENTOS */                                                                                                                                  
       perform fc_debug('CHAMANDO fc_iptu_verificapag...');                                                                                                       
                                                                                                                                                                  
       select rbtempagamento, rbempagamento, rtmsgretorno, rberro                                                                                                 
         into bTempagamento, bEmpagamento, tRetorno, bErro                                                                                                        
         from fc_iptu_verificapag(iMatricula, iAnousu, bCalculogeral, bAtualizap, false, bDemo, bSubRaise);                                                       
                                                                                                                                                                  
       perform fc_debug('RETORNO fc_iptu_verificapag:');                                                                                                          
       perform fc_debug(' <retorno> TEMPAGAMENTO: ' || bTempagamento);                                                                                            
       perform fc_debug(' <retorno> EMPAGAMENTO: ' || bEmpagamento);                                                                                              
       perform fc_debug(' <retorno> RETORNO: ' || tRetorno);                                                                                                      
       perform fc_debug(' <retorno> ERRO: ' || bErro);                                                                                                            
                                                                                                                                                                  
       /* CALCULA VALOR DO TERRENO */                                                                                                                             
       perform fc_debug( 'CHAMANDO fc_iptu_calculavvt_nit_2014...' );                                                                                             
                                                                                                                                                                  
     --  raise notice 'aaaaaaaaaaaaaaaaaaa';                                                                                                                      
       perform j10_vlrter from iptucalcpadrao where j10_matric = iMatricula and j10_anousu = iAnousu;                                                             
                                                                                                                                                                  
          if not found  then                                                                                                                                      
                                                                                                                                                                  
     --raise notice 'passei territorial';                                                                                                                         
             select rnvvt,                                                                                                                                        
                     rnarea,                                                                                                                                      
                     rberro,                                                                                                                                      
                     riCodErro,                                                                                                                                   
                     rtErro                                                                                                                                       
              into nVvt,                                                                                                                                          
                   nAreac,                                                                                                                                        
                   bErro,                                                                                                                                         
                   iCodErro,                                                                                                                                      
                   tErro                                                                                                                                          
              from fc_iptu_calculavvt_nit_2014(iAnousu, iIdbql, nFracaolote, nValorBaseCalculo, bSubRaise);                                                       
          End If; -- Thiago                                                                                                                                       
       perform fc_debug('RETORNO fc_iptu_calculavvt_nit_2014:');                                                                                                  
       perform fc_debug(' <retorno> VVT: ' || coalesce(nVvt, 0));                                                                                                 
       perform fc_debug(' <retorno> AREA CONTRUIDA: ' || coalesce(nAreac, 0));                                                                                    
       perform fc_debug(' <retorno> ERRO: ' || bErro);                                                                                                            
                                                                                                                                                                  
       if bErro is true then                                                                                                                                      
         select fc_iptu_geterro(iCodErro, tErro) into tRetorno;                                                                                                   
         return tRetorno;                                                                                                                                         
       end if;                                                                                                                                                    
                                                                                                                                                                  
     --  raise notice 'bbbbbbbbbbbbb';                                                                                                                            
                                                                                                                                                                  
       /* VERIFICA ISENCOES */                                                                                                                                    
       perform fc_debug('PARAMETROS fc_iptu_verificaisencoes...');                                                                                                
                                                                                                                                                                  
       select ricodisen, ritipois, rnisenaliq, rbisentaxas, rnarealo                                                                                              
         into iCodisen, iTipois, nIsenaliq, bIsentaxas, nArealo                                                                                                   
         from fc_iptu_verificaisencoes(iMatricula, iAnousu, bDemo, bSubRaise);                                                                                    
                                                                                                                                                                  
       if iTipois is not null then                                                                                                                                
         update tmpdadosiptu set tipoisen = iTipois;                                                                                                              
       end if;                                                                                                                                                    
                                                                                                                                                                  
       perform fc_debug('RETORNO fc_iptu_verificaisencoes:');                                                                                                     
       perform fc_debug(' <retorno> CODISEN: ' || coalesce(iCodisen, 0));                                                                                         
       perform fc_debug(' <retorno> TIPOISEN: ' || coalesce(iTipois, 0));                                                                                         
       perform fc_debug(' <retorno> ALIQ INSEN: ' || coalesce(nIsenaliq, 0));                                                                                     
       perform fc_debug(' <retorno> INSENTAXAS: ' || bIsentaxas);                                                                                                 
       perform fc_debug(' <retorno> AREALO: ' || coalesce(nArealo, 0));                                                                                           
                                                                                                                                                                  
       /* CALCULA VALOR DA CONSTRUCAO */                                                                                                                          
       perform fc_debug('CHAMANDO fc_iptu_calculavvc_nit_2014...');                                                                                               
                                                                                                                                                                  
        perform j10_vlrter from iptucalcpadrao where j10_matric = iMatricula and j10_anousu = iAnousu;                                                            
                                                                                                                                                                  
        if not found then                                                                                                                                         
     --raise notice 'passei predial';                                                                                                                             
           select rnvvc,                                                                                                                                          
                  rntotarea,                                                                                                                                      
                  rinumconstr,                                                                                                                                    
                  rberro,                                                                                                                                         
                  riCodErro,                                                                                                                                      
                  rtErro                                                                                                                                          
           into nVvc,                                                                                                                                             
                nTotarea,                                                                                                                                         
                iNumconstr,                                                                                                                                       
                bErro,                                                                                                                                            
                iCodErro,                                                                                                                                         
                tErro                                                                                                                                             
           from fc_iptu_calculavvc_nit_2014(iMatricula, iAnousu, nValorBaseCalculo, bSubRaise);                                                                   
        End If; -- thiago                                                                                                                                         
       perform fc_debug('RETORNO fc_iptu_calculavvc_nit_2014:');                                                                                                  
       perform fc_debug(' <retorno> VVC: ' || coalesce(nVvc, 0));                                                                                                 
       perform fc_debug(' <retorno> AREA TOTAL: ' || coalesce(nTotarea, 0));                                                                                      
       perform fc_debug(' <retorno> NUMERO DE CONSTRUCOES: ' || coalesce(iNumconstr, 0));                                                                         
       perform fc_debug(' <retorno> ERRO: ' || bErro);                                                                                                            
                                                                                                                                                                  
       if bErro is true then                                                                                                                                      
         select fc_iptu_geterro(iCodErro, tErro) into tRetorno;                                                                                                   
         return tRetorno;                                                                                                                                         
       end if;                                                                                                                                                    
                                                                                                                                                                  
       if nVvc is null or nVvc = 0 and iNumconstr <> 0 then                                                                                                       
         select fc_iptu_geterro(103, '') into tRetorno;                                                                                                           
         return tRetorno;                                                                                                                                         
       end if;                                                                                                                                                    
                                                                                                                                                                  
       perform predial from tmpdadosiptu where predial is true;                                                                                                   
                                                                                                                                                                  
       if found then                                                                                                                                              
                                                                                                                                                                  
         select j35_caract                                                                                                                                        
         into iFatorCorrecao                                                                                                                                      
         from carlote                                                                                                                                             
         inner join caracter on j31_codigo = j35_caract                                                                                                           
         where j35_idbql = iIdbql and j31_grupo = 32;                                                                                                             
                                                                                                                                                                  
         if iFatorCorrecao = 3201 then                                                                                                                            
           nValorFatorCorrecao = 0.60;                                                                                                                            
         elsif iFatorCorrecao = 3202 then                                                                                                                         
           nValorFatorCorrecao = 0.70;                                                                                                                            
         elsif iFatorCorrecao = 3203 then                                                                                                                         
           nValorFatorCorrecao = 0.80;                                                                                                                            
         elsif iFatorCorrecao = 3204 then                                                                                                                         
           nValorFatorCorrecao = 0.90;                                                                                                                            
         elsif iFatorCorrecao = 3205 then                                                                                                                         
           nValorFatorCorrecao = 1.00;                                                                                                                            
         end if;                                                                                                                                                  
                                                                                                                                                                  
       end if;                                                                                                                                                    
                                                                                                                                                                  
       select j34_setor                                                                                                                                           
       into cSetor                                                                                                                                                
       from lote                                                                                                                                                  
       where j34_idbql = iIdbql;                                                                                                                                  
                                                                                                                                                                  
       select j31_codigo                                                                                                                                          
              into iCaracteristica                                                                                                                                
              from carlote                                                                                                                                        
                   inner join caracter on j31_codigo = j35_caract                                                                                                 
              where j31_grupo  = 11                                                                                                                               
              and j35_idbql  = iIdbql;                                                                                                                            
                                                                                                                                                                  
       if iCaracteristica = 1106 and cSetor = '0106' then                                                                                                         
                                                                                                                                                                  
         nVvc = 10000;                                                                                                                                            
     --    nTaxaTlc = 0;                                                                                                                                          
     --    nTaxaIlum = 0;                                                                                                                                         
                                                                                                                                                                  
       else                                                                                                                                                       
                                                                                                                                                                  
         nVvc = nVvc * nValorFatorCorrecao;                                                                                                                       
         nVvt = nVvt * nValorFatorCorrecao;                                                                                                                       
                                                                                                                                                                  
         if bRaise then                                                                                                                                           
        --   raise notice 'nValorFatorCorrecao: % - vt: % - vc: %', nValorFatorCorrecao, nVvt, nVvc;                                                              
         end if;                                                                                                                                                  
                                                                                                                                                                  
         update tmpdadosiptu set vvt = nVvt, vvc = nVvc;                                                                                                          
         update tmpiptucale set valor = nVvc;                                                                                                                     
                                                                                                                                                                  
       end if;                                                                                                                                                    
                                                                                                                                                                  
       /* CALCULA O VALOR VENAL */                                                                                                                                
       nVv := ( nVvc + nVvt );                                                                                                                                    
     -- raise notice 'nValorFatorCorrecao: % - vt: % - vc: %', nValorFatorCorrecao, nVvt, nVvc;                                                                   
       if bRaise then                                                                                                                                             
         raise notice 'fator de correcao: % - vvc: % - vvt: % - vv: %', nValorFatorCorrecao, nVvc, nVvt, nVv;                                                     
       end if;                                                                                                                                                    
                                                                                                                                                                  
       select j40_refant into cRefAnt from iptuant where j40_matric = iMatricula; --and substr(j40_refant,1,6) = '101071';                                        
                                                                                                                                                                  
       perform j10_vlrter from iptucalcpadrao where j10_matric = iMatricula and j10_anousu = iAnousu;                                                             
       if found and cRefAnt is not null then                                                                                                                      
     --    raise notice 'achou iptucalcpadrao';                                                                                                                   
                                                                                                                                                                  
         select      j10_vlrter * nValorBaseCalculo,                                                                                                              
                     coalesce ( ( select sum(j11_vlrcons) from iptucalcpadraoconstr where j11_iptucalcpadrao = j10_sequencial ),0) * nValorBaseCalculo,           
                     j10_aliq                                                                                                                                     
         into nVvt, nVvc, nAliquota                                                                                                                               
         from iptucalcpadrao                                                                                                                                      
         where j10_matric = iMatricula and j10_anousu = iAnousu;                                                                                                  
                                                                                                                                                                  
         if bRaise then                                                                                                                                           
           raise notice 'Aliquota: % ', nAliquota;                                                                                                                
           raise notice 'Anousu: % ', iAnousu;                                                                                                                    
         end if;                                                                                                                                                  
                                                                                                                                                                  
         select j34_area                                                                                                                                          
         into nArealote                                                                                                                                           
         from lote                                                                                                                                                
         where j34_idbql = iIdbql;                                                                                                                                
                                                                                                                                                                  
         select j36_testad                                                                                                                                        
         into nTestada                                                                                                                                            
         from testada                                                                                                                                             
         inner join testpri on j49_idbql = j36_idbql and j49_face = j36_face and j49_codigo = j36_codigo                                                          
         where j49_idbql = iIdbql;                                                                                                                                
                                                                                                                                                                  
     --    raise notice 'area lote: %', nAreaLote;                                                                                                                
                                                                                                                                                                  
         update tmpdadosiptu set vvt = nVvt, vvc = nVvc, areat = nAreaLote, testada = nTestada;                                                                   
         update tmpiptucale set valor = nVvc;                                                                                                                     
                                                                                                                                                                  
         nVv := ( nVvc + nVvt );                                                                                                                                  
                                                                                                                                                                  
      --   raise notice 'nValorBaseCalculo: % - valor venal: %', nValorBaseCalculo, nVv, nAliquota;                                                               
                                                                                                                                                                  
       else                                                                                                                                                       
                                                                                                                                                                  
       /* BUSCA A ALIQUOTA  */                                                                                                                                    
       perform fc_debug('CHAMANDO fc_iptu_get_aliquota_nit_2014...');                                                                                             
                                                                                                                                                                  
         select rnAliquota,                                                                                                                                       
              rlErro,                                                                                                                                             
              riCodErro,                                                                                                                                          
              rtErro                                                                                                                                              
         into nAliquota,                                                                                                                                          
              bErro,                                                                                                                                              
              iCodErro,                                                                                                                                           
              tErro                                                                                                                                               
         from fc_iptu_get_aliquota_nit_2014(iMatricula, iIdbql, nVv, iAnousu, bSubRaise);                                                                         
                                                                                                                                                                  
        -- raise notice 'NAO achou iptucalcpadrao';                                                                                                               
       end if;                                                                                                                                                    
                                                                                                                                                                  
       perform fc_debug('VALOR VENAL: ' || nVv);                                                                                                                  
                                                                                                                                                                  
       if bRaise then                                                                                                                                             
         raise notice 'vv [valor venal]: %', nVv;                                                                                                                 
       end if;                                                                                                                                                    
                                                                                                                                                                  
                                                                                                                                                                  
       perform fc_debug('RETORNO fc_iptu_get_aliquota_nit_2014:');                                                                                                
       perform fc_debug(' <retorno> ALIQUOTA: ' || coalesce(nAliquota, 0));                                                                                       
       perform fc_debug(' <retorno> ERRO: ' || bErro);                                                                                                            
                                                                                                                                                                  
       if bErro is true then                                                                                                                                      
         select fc_iptu_geterro(iCodErro, tErro) into tRetorno;                                                                                                   
         return tRetorno;                                                                                                                                         
       end if;                                                                                                                                                    
                                                                                                                                                                  
       if nAliquota is null or nAliquota = 0 then                                                                                                                 
         select fc_iptu_geterro(13, '') into tRetorno;                                                                                                            
         return tRetorno;                                                                                                                                         
       end if;                                                                                                                                                    
                                                                                                                                                                  
     --  raise notice 'aliquota: %', nAliquota;                                                                                                                   
                                                                                                                                                                  
       nViptu := nVv * ( nAliquota / 100 );                                                                                                                       
       perform fc_debug('VALOR DO IPTU: ' || nViptu);                                                                                                             
                                                                                                                                                                  
       select count(*)                                                                                                                                            
         into iParcelas                                                                                                                                           
         from cadvencdesc                                                                                                                                         
              inner join cadvenc on q92_codigo = q82_codigo                                                                                                       
        where q92_codigo = rCfiptu.j18_vencim;                                                                                                                    
                                                                                                                                                                  
       if not found or iParcelas = 0 then                                                                                                                         
         select fc_iptu_geterro(14, '') into tRetorno;                                                                                                            
         return tRetorno;                                                                                                                                         
       end if;                                                                                                                                                    
                                                                                                                                                                  
       perform predial from tmpdadosiptu where predial is true;                                                                                                   
                                                                                                                                                                  
       if found then                                                                                                                                              
         insert into tmprecval values (rCfiptu.j18_rpredi, nViptu, 1, false);                                                                                     
       else                                                                                                                                                       
         insert into tmprecval values (rCfiptu.j18_rterri, nViptu, 1, false);                                                                                     
       end if;                                                                                                                                                    
                                                                                                                                                                  
       if bRaise then                                                                                                                                             
         raise notice 'nViptu 1: %', nViptu;                                                                                                                      
       end if;                                                                                                                                                    
                                                                                                                                                                  
       update tmpdadosiptu set viptu = nViptu, codvenc = rCfiptu.j18_vencim, aliq = nAliquota;                                                                    
       update tmpdadostaxa set anousu = iAnousu, matric = iMatricula, idbql = iIdbql, valiptu = nViptu, valref = nValorBaseCalculo, vvt = nVvt, nparc = iParcelas;
                                                                                                                                                                  
       /* CALCULA AS TAXAS */                                                                                                                                     
       select db21_codcli                                                                                                                                         
         into iCodcli                                                                                                                                             
         from db_config                                                                                                                                           
        where prefeitura is true;                                                                                                                                 
                                                                                                                                                                  
       perform fc_debug('CODCLI: ' || iCodcli);                                                                                                                   
       perform fc_debug('CHAMANDO fc_iptu_calculataxas...');                                                                                                      
                                                                                                                                                                  
       select fc_iptu_calculataxas(iMatricula, iAnousu, iCodcli, bSubRaise)                                                                                      
        into bTaxasCalculadas;                                                                                                                                   
                                                                                                                                                                  
       perform fc_debug('RETORNO fc_iptu_calculataxas:');                                                                                                         
                                                                                                                                                                  
       if bRaise then                                                                                                                                             
         raise notice '%', (select fc_debug(' <retorno> TAXASCALCULADAS: ' || bTaxasCalculadas, bRaise, false, true));                                            
       end if;                                                                                                                                                    
                                                                                                                                                                                                                                                                                                                               
       if bRaise then                                                                                                                                             
         raise notice 'aliquota %',nAliquota;                                                                                                                     
       end if;                                                                                                                                                    
                                                                                                                                                                  
       /* MONTA O DEMONSTRATIVO */                                                                                                                                
       select fc_iptu_demonstrativo_niteroi(iMatricula, iAnousu, iIdbql, bSubRaise )                                                                                      
          into tDemo;                                                                                                                                             
                                                                                                                                                                  
       /* GERA FINANCEIRO */                                                                                                                                      
       if bDemo is false then -- se nao for demonstrativo gera o financeiro, caso contrario retorna o demonstrativo                                               
         select fc_iptu_geradadosiptu(iMatricula,iIdbql,iAnousu,nIsenaliq,bDemo,bSubRaise)                                                                        
           into bDadosIptu;                                                                                                                                       
           if bGerafinanc then                                                                                                                                    
             select fc_iptu_gerafinanceiro(iMatricula,iAnousu,iParcelaini,iParcelafim,bCalculogeral,bTempagamento,bNovonumpre,bDemo,bSubRaise,iDiasVcto)          
               into bFinanceiro;                                                                                                                                  
           end if;                                                                                                                                                
       else                                                                                                                                                       
          return tDemo;                                                                                                                                           
       end if;                                                                                                                                                    
                                                                                                                                                                  
       if bDemo is false then                                                                                                                                     
          update iptucalc set j23_manual = tDemo where j23_matric = iMatricula and j23_anousu = iAnousu;                                                          
          update iptucalc set j23_aliq = nAliquota where j23_matric = iMatricula and j23_anousu = iAnousu;                                                        
       end if;                                                                                                                                                    
                                                                                                                                                                  
       select fc_iptu_geterro(1, '') into tRetorno;                                                                                                               
       return tRetorno;                                                                                                                                           
                                                                                                                                                                  
     end;                                                                                                                                                         
    $$;

SQL;
        $this->execute($sql);
    }
}
