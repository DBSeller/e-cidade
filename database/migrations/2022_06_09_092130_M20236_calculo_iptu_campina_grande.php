<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20236CalculoIptuCampinaGrande extends Migration
{   
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $this->upDicionario();
       $this->upEstrutura();
    }

    public function down()
    {
       $this->downDicionario();
       $this->downEstrutura();
    }
    
    public function upDicionario()
    {
      DB::connection()->getPdo()->exec(<<<SQL
      insert into db_sysfuncoes( codfuncao ,nomefuncao ,nomearquivo ,obsfuncao ,corpofuncao ,triggerfuncao ) 
      values (213 ,'fc_calculoiptu_campinagrande_2022' ,'calculoiptu_campinagrande_2022.sql' ,
              'Cálculo IPTU de Campina Grande para o ano de 2022' ,
              'CREATE OR REPLACE FUNCTION fc_calculoiptu_campinagrande_2022(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer) 
               RETURNS varchar(100) AS $$ declare iMatricula alias for $1; iAnousu alias for $2; lGerafinanc alias for $3; lAtualizaParcela alias for $4; 
               lNovonumpre alias for $5; lCalculogeral alias for $6; lDemonstrativo alias for $7; iParcelaini alias for $8; iParcelafim alias for $9; 
               iIdbql integer default 0; iNumcgm integer default 0; iCodcli integer default 0; iCodisen integer default 0; iTipois integer default 0; 
               iParcelas integer default 0; iNumconstr integer default 0; iCodErro integer default 0; dDatabaixa date; nAreal numeric default 0; nAreac numeric default 0;
                nTotarea numeric default 0; nFracao numeric default 0; nFracaolote numeric default 0; nAliquota numeric default 0; nIsenaliq numeric default 0; 
                nArealo numeric default 0; nVvc numeric(15,2) default 0; nVvt numeric(15,2) default 0; nVv numeric(15,2) default 0; nViptu numeric(15,2) default 0; 
                tRetorno text default \'\'; tDemo text default \'\'; tErro text default \'\'; lPredial boolean; lFinanceiro boolean; 
                lIsencaoAutomatica boolean default false; lDadosIptu boolean; lErro boolean; lIsentaxas boolean; lTempagamento boolean; 
                lEmpagamento boolean; lTaxasCalculadas boolean; lRaise boolean default false; -- true para habilitar raise na funcao principal lSubRaise boolean default false; 
                -- true para habilitar raise nas sub-funcoes iCodigoCadastroIsencao integer default 0; rCfiptu record; rConstr record; begin lRaise := ( case when fc_getsession(\'DB_debugon\') is null then false else true end ); 
                lSubRaise := lRaise; perform fc_debug(\'INICIANDO CALCULO\',lRaise,true,false); -- raise notice \'Inicio %\', current_time; perform fc_debug(\'\',lRaise,true,false);
                 /** * Guarda os parametros do calculo */ select * from into rCfiptu cfiptu where j18_anousu = iAnousu; /* O cálculo deverá lançar automaticamente uma isenção conforme 
                 o tipo de isenção padrão configurado para os imóveis que atenderem os seguintes critérios: - a soma total da área construida das unidades do lote tem que sem igual ou 
                 inferior a 60 m2 j34_area <= 60 - a taxa de ocupação do lote deve ser igual ou inferior a 70% conforme cálculo: área total construida no lote dividido pela área do 
                 lote - a ocupação de todas as unidades do lote devem ser residencial (caracteristica 472 grupo 47) j34_totcon/j34_area - o tipo de construção de todas as unidades do lote 
                 devem ser casa (caracteristicas 461,462,463,464,468,469,4610,4611,4612,4725 do grupo 46). 461,462,463,464,468,469,4610,4611,4612,4725 */ 
                 -- raise notice \'Inicio isencao automatica %\', current_time; select j34_area, coalesce(j34_totcon, 0) into nArealo, nTotarea from iptubase 
                 inner join lote on j34_idbql = j01_idbql where j01_matric = iMatricula; if not found then select fc_iptu_geterro( 3, \'ERRO - Sem dados para a matricula \'||iMatricula ) 
                 into tRetorno; return tRetorno; end if; lIsencaoAutomatica := false; if nTotarea <= 60 then -- raise notice \'passou na primeira regra\'; lIsencaoAutomatica := true; 
                 if (nTotarea/nArealo) > 0.7 then -- raise notice \'passou na segunda regra\'; for rConstr in select j31_grupo, j31_codigo from iptuconstr 
                 inner join carconstr on j39_matric = j48_matric and j39_idcons = j48_idcons inner join caracter on j31_codigo = j48_caract 
                 where j39_matric = iMatricula and j39_dtdemo is null and j31_grupo in (47, 46) loop -- raise notice \'grupo % caracteristica %\', rConstr.j31_grupo, rConstr.j31_codigo; 
                 if (rConstr.j31_grupo = 47 and rConstr.j31_codigo <> 472) or (rConstr.j31_grupo = 46 and rConstr.j31_codigo not in(461,462,463,464,468,469,4610,4611,4612,4725)) then -- raise notice \'nao passou na regra de caracteristicas\'; lIsencaoAutomatica := false; exit; end if; end loop; -- if lIsencaoAutomatica is true -- then -- raise notice \'passou na regra de caracteristicas\'; -- end if; else -- raise notice \'nao passou na segunda regra\'; lIsencaoAutomatica := false; end if; end if; if lIsencaoAutomatica is true and not exists(select 1 from iptuisen inner join isenexe on j46_codigo = j47_codigo where j46_matric = iMatricula and j46_tipo = rCfiptu.j18_tipoisen and j47_anousu = iAnousu) then INSERT INTO iptuisen(j46_codigo, j46_matric, j46_tipo, j46_dtini, j46_dtfim, j46_perc, j46_dtinc, j46_idusu, j46_hist, j46_arealo) VALUES (nextval(\'iptuisen_j46_codigo_seq\'), iMatricula, rCfiptu.j18_tipoisen, (\'01-01-\'||iAnousu)::date, (\'31-12-\'||iAnousu)::date, 100, current_date, 1, \'ISENCAO AUTOMATICA\', 0); INSERT INTO isenexe(j47_codigo,j47_anousu ) VALUES (currval(\'iptuisen_j46_codigo_seq\'), iAnousu); else select j46_codigo into iCodigoCadastroIsencao from iptuisen inner join isenexe on j46_codigo = j47_codigo left join isenproc on j61_codigo = j46_codigo left join isentaxa on j56_codigo = j46_codigo where j46_matric = iMatricula and j46_tipo = rCfiptu.j18_tipoisen and j47_anousu = iAnousu and j61_codigo is null and j56_codigo is null; if found then delete from isenexe where j47_codigo = iCodigoCadastroIsencao; delete from iptuisen where j46_codigo = iCodigoCadastroIsencao; end if; end if; -- raise notice \'Fim isencao automatica %\', current_time; -- raise notice \'Inicio pre calculo %\', current_time; /** * Executa PRE CALCULO */ select r_iIdbql, r_nAreal, r_nFracao, r_iNumcgm, r_dDatabaixa, r_nFracaolote, r_tDemo, r_lTempagamento, r_lEmpagamento, r_iCodisen, r_iTipois, r_nIsenaliq, r_lIsentaxas, r_nArealote, r_iCodCli, r_tRetorno into iIdbql, nAreal, nFracao, iNumcgm, dDatabaixa, nFracaolote, tDemo, lTempagamento, lEmpagamento, iCodisen, iTipois, nIsenaliq, lIsentaxas, nArealo, iCodCli, tRetorno from fc_iptu_precalculo( iMatricula, iAnousu, lCalculogeral, lAtualizaParcela, lDemonstrativo, lRaise ); perform fc_debug(\' RETORNO DA PRE CALCULO: \', lRaise); perform fc_debug(\' iIdbql -> \' || iIdbql, lRaise); perform fc_debug(\' nAreal -> \' || nAreal, lRaise); perform fc_debug(\' nFracao -> \' || nFracao, lRaise); perform fc_debug(\' iNumcgm -> \' || iNumcgm, lRaise); perform fc_debug(\' dDatabaixa -> \' || dDatabaixa, lRaise); perform fc_debug(\' nFracaolote -> \' || nFracaolote, lRaise); perform fc_debug(\' tDemo -> \' || tDemo, lRaise); perform fc_debug(\' lTempagamento -> \' || lTempagamento, lRaise); perform fc_debug(\' lEmpagamento -> \' || lEmpagamento, lRaise); perform fc_debug(\' iCodisen -> \' || iCodisen, lRaise); perform fc_debug(\' iTipois -> \' || iTipois, lRaise); perform fc_debug(\' nIsenaliq -> \' || nIsenaliq, lRaise); perform fc_debug(\' lIsentaxas -> \' || lIsentaxas, lRaise); perform fc_debug(\' nArealote -> \' || nArealo, lRaise); perform fc_debug(\' iCodCli -> \' || iCodCli, lRaise); perform fc_debug(\' tRetorno -> \' || tRetorno, lRaise); perform fc_debug(\'\',lRaise,true,false); -- raise notice \'Fim pre calculo %\', current_time; /** * Variavel de retorno contem a msg * de erro retornada do pre calculo */ if trim(tRetorno) <> \'\' then return tRetorno; end if; update tmpdadosiptu set matric = iMatricula; update tmpdadostaxa set anousu = iAnousu, matric = iMatricula, idbql = iIdbql, valref = rCfiptu.j18_vlrref; /** * Calcula valor do terreno */ perform fc_debug(\'PARAMETROS fc_iptu_calculavvt_campinagrande_2022 IDBQL: \'||iIdbql||\' - iMatricula: \'||iMatricula||\' - Anousu: \'||iAnousu||\' - FRACAO DO LOTE: \'||nFracaolote||\' - DEMO: \'||lDemonstrativo||\' - DEBUG: \'||lRaise, lRaise); -- raise notice \'Inicio VVT %\', current_time; select rnvvt, rnarea, rtdemo, rtmsgerro, rberro, riCodErro, rtErro into nVvt, nAreac, tDemo, tRetorno, lErro, iCodErro, tErro from fc_iptu_calculavvt_campinagrande_2022( iIdbql, iMatricula, iAnousu, nFracaolote, lDemonstrativo, lRaise); perform fc_debug(\'RETORNO fc_iptu_calculavvt_campinagrande_2022 -> VVT: \'||nVvt||\' - AREA CONSTRUIDA: \'||nAreac||\' - RETORNO: \'||tRetorno||\' - ERRO: \'||lErro, lRaise); perform fc_debug(\'\', lRaise); -- raise notice \'Fim VVT %\', current_time; if lErro is true then select fc_iptu_geterro( iCodErro, tErro ) into tRetorno; return tRetorno; end if; /** * Calcula valor da construcao */ perform fc_debug(\'PARAMETROS fc_iptu_calculavvc_campinagrande_2022 MATRICULA: \'||iMatricula||\' - ANOUSU: \'||iAnousu||\' - DEMO: \'||lDemonstrativo||\' - DEBUG: \'||lRaise, lRaise); -- raise notice \'Inicio VVC %\', current_time; select rnvvc, rntotarea, rinumconstr, rtdemo, rtmsgerro, rberro, riCodErro, rtErro into nVvc, nTotarea, iNumconstr, tDemo, tRetorno, lErro, iCodErro, tErro from fc_iptu_calculavvc_campinagrande_2022( iMatricula, iAnousu, lDemonstrativo, lRaise ); perform fc_debug(\'RETORNO fc_iptu_calculavvc_campinagrande_2022 -> VVC: \'||nVvc||\' - AREA TOTAL: \'||nTotarea||\' - NUMERO DE CONSTRUCOES: \'||iNumconstr||\' - RETORNO: \'||tRetorno||\' - ERRO: \'||lErro, lRaise); perform fc_debug(\'\', lRaise); -- raise notice \'Fim VVC %\', current_time; if lErro is true then select fc_iptu_geterro(iCodErro, tErro) into tRetorno; return tRetorno; end if; select predial into lPredial from tmpdadosiptu; /* BUSCA A ALIQUOTA */ -- so executar se nao for isento perform fc_debug(\'BUSCA A ALIQUOTA DO IPTU \', lRaise); -- raise notice \'Inicio aliquota %\', current_time; select coderro, descrerro, aliquota into iCodErro, tErro, nAliquota from fc_iptu_getaliquota_campinagrande_2022(iMatricula, iIdbql, iAnousu, lPredial, lSubRaise); -- raise notice \'Fim Aliquota %\', current_time; if nAliquota = 0 then select fc_iptu_geterro( iCodErro, tErro ) into tRetorno; return tRetorno; end if; perform fc_debug(\'RETORNO DA BUSCA A ALIQUOTA DO IPTU \', lRaise); perform fc_debug(\' \', lRaise); /*--------- CALCULA O VALOR VENAL -----------*/ perform fc_debug(\'valor venal construcao (nVvc) - \'||nVvc||\' valor venal terreno (nVvt) - \'||nVvt, lRaise); nVv := nVvc + nVvt; perform fc_debug(\'valor venal total - \'||nVv, lRaise); nViptu := nVv * ( nAliquota / 100 ); perform fc_debug(\'valor iptu \'||nViptu||\' - aliquota \'||nAliquota||\'%\', lRaise); perform fc_debug(\' \', lRaise); perform fc_debug(\'Inserindo as receitas de IPTU na tabela tmprecval \', lRaise); perform fc_debug(\' \', lRaise); if lPredial then insert into tmprecval values (rCfiptu.j18_rpredi, nViptu, 1, false); else insert into tmprecval values (rCfiptu.j18_rterri, nViptu, 1, false); end if; perform fc_debug(\'Inserindo valor e codigo de vencimento do IPTU na tabela tmpdadosiptu\', lRaise); perform fc_debug(\' \', lRaise); update tmpdadosiptu set viptu = nViptu, codvenc = rCfiptu.j18_vencim; /*-------------------------------------------*/ select count(*) into iParcelas from cadvencdesc inner join cadvenc on q92_codigo = q82_codigo where q92_codigo = rCfiptu.j18_vencim ; if not found or iParcelas = 0 then select fc_iptu_geterro(14,\'\') into tRetorno; return tRetorno; end if; update tmpdadostaxa set valiptu = nViptu, vvt = nVvt, nparc = iParcelas, totareaconst = nTotarea; /* CALCULA AS TAXAS */ perform fc_debug(\'PARAMETROS fc_iptu_calculataxas ANOUSU \'||iAnousu||\' -- CODCLI \'||iCodcli||\' -- Debug: \'||lSubRaise, lRaise); perform fc_debug(\' \', lRaise); -- raise notice \'Inicio Taxa %\', current_time; select fc_iptu_calculataxas(iMatricula, iAnousu, iCodcli, lSubRaise) into lTaxasCalculadas; -- raise notice \'Fim taxa %\', current_time; perform fc_debug(\'RETORNO fc_iptu_calculataxas --->>> TAXAS CALCULADAS - \'||lTaxasCalculadas, lRaise); -- raise notice \'Inicio demonstrativo %\', current_time; /* MONTA O DEMONSTRATIVO */ select fc_iptu_demonstrativo(iMatricula,iAnousu,iIdbql,lSubRaise ) into tDemo; -- raise notice \'Fim demonstrativo %\', current_time; /* GERA FINANCEIRO */ if lDemonstrativo is false then -- Se nao for demonstrativo gera o financeiro, caso contrario retorna o demonstrativo -- raise notice \'Inicio dados IPTU %\', current_time; select fc_iptu_geradadosiptu(iMatricula,iIdbql,iAnousu,nIsenaliq,lDemonstrativo,lSubRaise) into lDadosIptu; -- raise notice \'Fim dados IPTU %\', current_time; if lGerafinanc then -- raise notice \'Inicio Financeiro %\', current_time; select fc_iptu_gerafinanceiro(iMatricula,iAnousu,iParcelaini,iParcelafim,lCalculogeral,lTempagamento,lNovonumpre,lDemonstrativo,lSubRaise) into lFinanceiro; -- raise notice \'Fim financeiro %\', current_time; end if; else return tDemo; end if; if lDemonstrativo is false then update iptucalc set j23_manual = tDemo where j23_matric = iMatricula and j23_anousu = iAnousu; end if; select fc_iptu_geterro(1, \'\') into tRetorno; -- raise notice \'Fim Calculo %\', current_time; return tRetorno; end; $$ LANGUAGE \'plpgsql\';' ,'0' );

        insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) 
        values ( 1166 ,213 ,1 ,'iMatricula' ,'int4' ,0 ,0 ,'0' ,'MATRICULA' );
        insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) 
        values ( 1167 ,213 ,2 ,'iAnousu' ,'int4' ,0 ,0 ,'0' ,'ANO DE CALCULO' );
        insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) 
        values ( 1168 ,213 ,3 ,'lGerafinanceiro' ,'bool' ,0 ,0 ,'0' ,'VARIÁVEL DE CONTROLE PARA GERAR FINANCEIRO' );
        insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) 
        values ( 1169 ,213 ,4 ,'lAtualizaParcela' ,'bool' ,0 ,0 ,'0' ,'VARIÁVEL DE CONTROLE PARA ATUALIZAR PARCELAS' );
        insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) 
        values ( 1170 ,213 ,5 ,'lNovonumpre' ,'bool' ,0 ,0 ,'0' ,'VARIÁVEL DE CONTROLE PARA GERAR UM NOVO NUMPRE' );
        insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) 
        values ( 1171 ,213 ,6 ,'lCalculogeral' ,'bool' ,0 ,0 ,'0' ,'VARIÁVEL DE CONTROLE PARA CÁLCULO GERAL OU PARCIAL' );
        insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) 
        values ( 1172 ,213 ,7 ,'lDemonstrativo' ,'bool' ,0 ,0 ,'0' ,'VARIÁVEL DE CONTROLE PARA GERAR DEMONSTRATIVO' );
        insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) 
        values ( 1173 ,213 ,8 ,'iParcelaini' ,'int4' ,0 ,0 ,'0' ,'PARCELA INICIAL' );
        insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) 
        values ( 1174 ,213 ,9 ,'iParcelafim' ,'int4' ,0 ,0 ,'0' ,'PARCELA FINAL' );

        insert into db_sysfuncoes( codfuncao ,nomefuncao ,nomearquivo ,obsfuncao ,corpofuncao ,triggerfuncao ) 
        values ( 214 ,'fc_iptu_taxalixo_campinagrande_2022' ,'iptu_taxalixo_campinagrande_2022.sql' ,
                'Cálculo Taxa de Coleta de Lixo Campina Grande para o ano de 2022' ,
                'create or replace function fc_iptu_taxalixo_campinagrande_2022(integer, numeric, integer, numeric, numeric, boolean) returns boolean as $$ declare iReceita alias for $1; iAliquota alias for $2; iHistCalc alias for $3; iPercIsen alias for $4; nValpar alias for $5; lRaise alias for $6; nValTaxa numeric(15,2) default 0; iAnousu integer default 0; iMatricula integer default 0; tSql text default \'\'; tRetorno text default \'\'; begin perform fc_debug(\' < iptu_taxalixo > Calculando taxa de lixo\', lRaise); perform fc_debug(\' \', lRaise); perform fc_debug(\' < iptu_taxalixo > receita: \' || iReceita, lRaise); perform fc_debug(\' < iptu_taxalixo > aliq: \' || iAliquota, lRaise); perform fc_debug(\' < iptu_taxalixo > historico: \' || iHistCalc, lRaise); select anousu, matric into iAnousu, iMatricula from tmpdadostaxa; if not found or iAnousu = 0 then raise notice \'Ano do calculo nao encontrado\'; return false; end if; select j71_valor into nValTaxa from iptubase inner join lote on j01_idbql = j34_idbql inner join iptuconstr on j39_matric = j01_matric inner join carconstr on j48_matric = j39_matric and j48_idcons = j39_idcons inner join caracter on j31_codigo = j48_caract and j31_grupo = 47 inner join carvalor on j71_anousu = iAnousu and j71_caract = j48_caract and j39_area between j71_ini and j71_fim and j71_quantini = case when j34_bairro in (10, 40, 50) then 1 else 0 end where j39_matric = iMatricula and j39_dtdemo is null and j39_idprinc is true; if not found then nValTaxa := nValpar; end if; insert into tmptaxapercisen values (iReceita, iPercIsen, 0, nValTaxa); if iPercIsen > 0 then nValTaxa := nValTaxa * (100 - iPercIsen) / 100; end if; perform fc_debug(\' <iptu_taxalixo> Percentual Isencao: \' || iPercIsen, lRaise); perform fc_debug(\' <iptu_taxalixo> Valor final da taxa: \' || nValTaxa, lRaise); tSql := \'insert into tmprecval values (\'||iReceita||\',\'||nValTaxa||\',\'||iHistCalc||\',true)\'; execute tSql; return true; end; $$ language \'plpgsql\';','0' );
        insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) 
        values ( 1175 ,214 ,1 ,'iReceita' ,'int4' ,0 ,0 ,'0' ,'CÓDIGO DA RECEITA' );
        insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) 
        values ( 1176 ,214 ,2 ,'iAliquota' ,'int4' ,0 ,0 ,'0' ,'ALÍQUOTA PARA CÁLCULO' );
        insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) 
        values ( 1177 ,214 ,3 ,'iHistCalc' ,'int4' ,0 ,0 ,'0' ,'HISTÓRICO DE CÁLCULO' );
        insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) 
        values ( 1178 ,214 ,4 ,'iPercIsen' ,'numeric' ,0 ,0 ,'0' ,'PERCENTUAL DE ISENÇÃO' );
        insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) 
        values ( 1179 ,214 ,5 ,'nValpar' ,'numeric' ,0 ,0 ,'109.84' ,'VALOR BASE DA TAXA' );
        insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) 
        values ( 1180 ,214 ,6 ,'lRaise' ,'bool' ,0 ,0 ,'FALSE' ,'VARIÁVEL PARA DEBUG' );

        insert into db_sysfuncoes values(215, 'fc_iptu_calculavvc_campinagrande_2022', 'Função de cálculo da construção de Campina Grande', 'create or replace function fc_iptu_calculavvc_campinagrande_2022(integer,integer,boolean,boolean) returns tp_iptu_calculavvc as $$ declare iMatricula alias for $1; iAnousu alias for $2; lMostrademo alias for $3; lRaise alias for $4; nAreatc numeric default 0; nVm2c numeric default 0; nVvcP numeric default 0; nVvc numeric default 0; iNumerocontr integer default 0; iCaractGrupo integer default 0; sDescrGrupo varchar; lAtualiza boolean default true; rConstr record; rtp_iptu_calculavvc tp_iptu_calculavvc%ROWTYPE; begin perform fc_debug(\'INICIANDO CALCULO VVC ...\', lRaise); rtp_iptu_calculavvc.rnVvc := 0; rtp_iptu_calculavvc.rnTotarea := 0; rtp_iptu_calculavvc.riNumconstr := 0; rtp_iptu_calculavvc.rtDemo := \'\'; rtp_iptu_calculavvc.rtMsgerro := \'Retorno ok\' ; rtp_iptu_calculavvc.rbErro := \'f\'; rtp_iptu_calculavvc.riCodErro := 0; rtp_iptu_calculavvc.rtErro := \'\'; iNumerocontr := 0; for rConstr in select j39_matric, j39_idcons, j39_idprinc, j39_area, j39_areap from iptuconstr where j39_matric = iMatricula and j39_dtdemo is null loop select j71_valor, j31_codigo, j31_descr into nVm2c, iCaractGrupo, sDescrGrupo from carconstr inner join caracter on j31_codigo = j48_caract and j31_grupo = 46 inner join carvalor on j71_anousu = iAnousu and j71_caract = j48_caract and rConstr.j39_area between j71_ini and j71_fim where j48_matric = rConstr.j39_matric and j48_idcons = rConstr.j39_idcons; if not found then perform fc_debug(\'< calculo vvc > Matricula \'||rConstr.j39_matric||\' Edificacao \'||rConstr.j39_idcons||\' nao encontrado .\', lRaise); rtp_iptu_calculavvc.rtErro := \' PARA O GRUPO 46 OU SEM VALOR PARA O ANO DE \'||iAnousu; rtp_iptu_calculavvc.rtMsgErro := \'SEM CARACTERISITICA PARA O GRUPO 46 OU SEM VALOR PARA O ANO DE \'||iAnousu; rtp_iptu_calculavvc.riCodErro := 24; rtp_iptu_calculavvc.rbErro := \'t\'; return rtp_iptu_calculavvc; end if; nVvcp := nVm2c * rConstr.j39_area; nAreatc := nAreatc + rConstr.j39_area; nVvc := nVvc + nVvcp; perform fc_debug(\'< calculo vvc > Valor venal total parcial - nVvc - \'||nVvc||\' nVm2c - \'||nVm2c|| \'area - \'||rConstr.j39_area, lRaise); iNumerocontr := iNumerocontr + 1; insert into tmpiptucale (anousu, matric, idcons, areaed, vm2, pontos, valor) values (iAnousu, iMatricula, rConstr.j39_idcons, rConstr.j39_area, nVm2c, 0, nVvcp); if lAtualiza then update tmpdadosiptu set predial = true; lAtualiza := false; end if; end loop; nVvc := round(nVvc, 2); perform fc_debug(\'< calculo vvc > Valor venal total final - nVvc - \'||nVvc, lRaise); rtp_iptu_calculavvc.rnVvc := nVvc::numeric; rtp_iptu_calculavvc.rnTotarea := nAreatc::numeric; rtp_iptu_calculavvc.riNumconstr := iNumerocontr; rtp_iptu_calculavvc.rtDemo := \'\'; rtp_iptu_calculavvc.rbErro := \'f\'; update tmpdadosiptu set vvc = rtp_iptu_calculavvc.rnVvc; return rtp_iptu_calculavvc; end; $$ language \'plpgsql\';', '');
        insert into db_sysfuncoes values(216, 'fc_iptu_calculavvt_campinagrande_2022', 'unção de cálculo do terreno de Campina Grande', 'create or replace function fc_iptu_calculavvt_campinagrande_2022(integer,integer,integer,numeric,boolean,boolean) returns tp_iptu_calculavvt as $$ declare iIdbql alias for $1; iMatricula alias for $2; iAnousu alias for $3; nFracao alias for $4; lMostrademo alias for $5; lRaise alias for $6; lPredial boolean default false; --iRegra integer; nVm2t numeric default 0; nVm2tCalc numeric default 0; nAreaLoteCorrigi numeric default 0; nAreaTerreno numeric default 0; nValor numeric default 0; nTestada numeric default 0; nFatorReducao numeric default 0; nFatorSituacao numeric default 0; nFatorTopografia numeric default 0; nFatorPedologia numeric default 0; rtp_iptu_calculavvt tp_iptu_calculavvt%ROWTYPE; begin rtp_iptu_calculavvt.rnAreaTotalC := 0; rtp_iptu_calculavvt.rnArea := 0; rtp_iptu_calculavvt.rnTestada := 0; rtp_iptu_calculavvt.riCoderro := 0; rtp_iptu_calculavvt.rtDemo := \'\'; rtp_iptu_calculavvt.rtMsgerro := \'\'; rtp_iptu_calculavvt.rtErro := \'\'; rtp_iptu_calculavvt.rbErro := \'f\'; perform fc_debug(\'< calculo vvt > INICIANDO CALCULO DO VALOR VENAL TERRITORIAL...\', lRaise); select case when j39_matric is not null then \'t\'::boolean else \'f\'::boolean end into lPredial from iptubase left join iptuconstr on j39_matric = j01_matric and j39_dtdemo is null where j01_matric = iMatricula; if not found then rtp_iptu_calculavvt.rbErro := \'t\'; rtp_iptu_calculavvt.riCoderro := 9; rtp_iptu_calculavvt.rtErro := \' DADOS DA MATRICULA NAO ENCONTRADO\'; return rtp_iptu_calculavvt; end if; /* verifica a area do lote */ select case when j34_area = 0 then j34_areal else j34_area end into nAreaTerreno from lote where j34_idbql = iIdbql; if nAreaTerreno is null or nAreaTerreno = 0 then rtp_iptu_calculavvt.rbErro := \'t\'; rtp_iptu_calculavvt.riCoderro := 3; rtp_iptu_calculavvt.rtErro := \'AREA DO LOTE NAO ENCONTRADA OU ZERADA\'; return rtp_iptu_calculavvt; end if; nAreaLoteCorrigi := ( nAreaTerreno * ( nFracao / 100::numeric ) ); perform fc_debug(\'< calculo vvt > Area do terreno: \'||nAreaTerreno, lRaise); /* busca valor do m2 do terreno */ select coalesce(j36_testad,0), j81_valorterreno into nTestada, nVm2t from testada inner join testpri on j49_idbql = j36_idbql and j49_face = j36_face and j49_codigo = j36_codigo inner join facevalor on j81_face = j36_face and j81_anousu = iAnousu where j36_idbql = iIdbql; if not found then rtp_iptu_calculavvt.rbErro := \'t\'; rtp_iptu_calculavvt.riCoderro := 25; rtp_iptu_calculavvt.rtMsgErro := \'VERIFIQUE O VALOR DO M2 DO TERRENO PARA A FACE\'; return rtp_iptu_calculavvt; end if; perform fc_debug(\'< calculo vvt > Valor do m2 do terreno: \'||nVm2t, lRaise); /* Imóveis não edificados até 250 m² Imóveis não edificados acima de 250 m² Imóveis edificados (apartamento) (caracter 131 e 140) Imóveis edificados (demais tipos de construções) */ case when lPredial is false and nAreaLoteCorrigi <= 250 then nFatorReducao := 0.3; when lPredial is false and nAreaLoteCorrigi > 250 then nFatorReducao := 0.4; when lPredial is true and exists(select 1 from iptuconstr inner join carconstr on j48_matric = j39_matric and j48_idcons = j39_idcons and j39_dtdemo is null and j39_idprinc is true where j39_matric = iMatricula and j48_caract in (4613, 4614)) then nFatorReducao := 1; else nFatorReducao := 0.35; end case; if not found or nFatorReducao > 1 then rtp_iptu_calculavvt.rbErro := \'t\'; rtp_iptu_calculavvt.riCoderro := 104; rtp_iptu_calculavvt.rtErro := \'\'; return rtp_iptu_calculavvt; end if; nVm2tCalc := nVm2t * nFatorReducao; /* busca fator pedologia */ select j74_fator into nFatorPedologia from lote inner join carlote on j35_idbql = j34_idbql inner join caracter on j31_codigo = j35_caract inner join carfator on j74_anousu = iAnousu and j74_caract = j35_caract where j34_idbql = iIdbql and j31_grupo = 9; if nFatorPedologia = 0 or nFatorPedologia is null then rtp_iptu_calculavvt.rbErro := \'t\'; rtp_iptu_calculavvt.riCoderro := 24; rtp_iptu_calculavvt.rtErro := \' PARA GRUPO 9 OU SEM VALOR PARA O ANO DE \'||iAnousu; rtp_iptu_calculavvt.rtMsgErro := \'SEM CARACTERISTICA PARA GRUPO 9 OU SEM VALOR PARA O ANO DE \'||iAnousu; return rtp_iptu_calculavvt; end if; /* busca fator Situacao */ select j74_fator into nFatorSituacao from lote inner join carlote on j35_idbql = j34_idbql inner join caracter on j31_codigo = j35_caract inner join carfator on j74_anousu = iAnousu and j74_caract = j35_caract where j34_idbql = iIdbql and j31_grupo = 13; if nFatorSituacao = 0 or nFatorSituacao is null then rtp_iptu_calculavvt.rbErro := \'t\'; rtp_iptu_calculavvt.riCoderro := 24; rtp_iptu_calculavvt.rtErro := \' PARA GRUPO 13 OU SEM VALOR PARA O ANO DE \'||iAnousu; rtp_iptu_calculavvt.rtMsgErro := \'SEM CARACTERISTICA PARA GRUPO 13 OU SEM VALOR PARA O ANO DE \'||iAnousu; return rtp_iptu_calculavvt; end if; /* busca fator topografia */ select j74_fator into nFatorTopografia from lote inner join carlote on j35_idbql = j34_idbql inner join caracter on j31_codigo = j35_caract inner join carfator on j74_anousu = iAnousu and j74_caract = j35_caract where j34_idbql = iIdbql and j31_grupo = 16; if nFatorTopografia = 0 or nFatorTopografia is null then rtp_iptu_calculavvt.rbErro := \'t\'; rtp_iptu_calculavvt.riCoderro := 24; rtp_iptu_calculavvt.rtErro := \' PARA GRUPO 16 OU SEM VALOR PARA O ANO DE \'||iAnousu; rtp_iptu_calculavvt.rtMsgErro := \'SEM CARACTERISTICA PARA GRUPO 16 OU SEM VALOR PARA O ANO DE \'||iAnousu; return rtp_iptu_calculavvt; end if; nValor := round( nAreaLoteCorrigi * (nVm2t*nFatorReducao)::numeric * nFatorPedologia * nFatorSituacao * nFatorTopografia, 2); if nValor <= 0 or nValor is null then rtp_iptu_calculavvt.rbErro := \'t\'; rtp_iptu_calculavvt.riCoderro := 113; rtp_iptu_calculavvt.rtErro := \'\'; return rtp_iptu_calculavvt; end if; /* formula de calculo do terreno */ perform fc_debug(\'< calculo vvt > Formula: VVT = (nAreaLoteCorrigi * nVm2t * nFatorPedologia * nFatorSituacao * nFatorTopografia)\', lRaise); -- raise notice \'< calculo vvt > Formula: VVT = (% * % * % * % * %)\', nAreaLoteCorrigi, nVm2tCalc, nFatorPedologia, nFatorSituacao, nFatorTopografia; perform fc_debug(\'\', lRaise); perform fc_debug(\'< calculo vvt > Formula: \'||nValor||\' = (\'||nAreaLoteCorrigi||\' * (\'||nVm2t||\' * \'||nFatorReducao||\') * \'||nFatorPedologia||\' * \'||nFatorSituacao||\' * \'||nFatorTopografia||\')\', lRaise, lRaise, lRaise); rtp_iptu_calculavvt.rnArea := nAreaTerreno; rtp_iptu_calculavvt.rnVvt := nValor; rtp_iptu_calculavvt.rnAreaTotalC := nAreaLoteCorrigi; rtp_iptu_calculavvt.rnTestada := nTestada; rtp_iptu_calculavvt.rtDemo := \'\'; rtp_iptu_calculavvt.rtMsgerro := \'\'; rtp_iptu_calculavvt.rbErro := \'f\'; update tmpdadosiptu set vvt = rtp_iptu_calculavvt.rnVvt, vm2t= nVm2t, areat=nAreaLoteCorrigi; return rtp_iptu_calculavvt; end; $$ language \'plpgsql\';', '');
        insert into db_sysfuncoes values(217, 'fc_iptu_getaliquota_campinagrande_2022', 'Função para calculo da aliquota', 'create or replace function fc_iptu_getaliquota_campinagrande_2022(integer, integer, integer, boolean, boolean) returns tp_aliquota_iptu as $$ declare iMatricula alias for $1; iIdbql alias for $2; iAnousu alias for $3; bPredial alias for $4; bRaise alias for $5; nAliquota numeric default 0; rtp_aliquota_iptu tp_aliquota_iptu%ROWTYPE; begin perform fc_debug(\'DEFININDO QUAL ALIQUOTA APLICAR ...\', bRaise); rtp_aliquota_iptu.coderro = 0; rtp_aliquota_iptu.descrerro = \'\'; rtp_aliquota_iptu.aliquota = 0; select coalesce(j73_aliq, 0) into nAliquota from iptuconstr inner join carconstr on j39_matric = j48_matric and j39_idcons = j48_idcons inner join caracter on j31_codigo = j48_caract inner join caraliq on j73_caract = j48_caract and j73_anousu = iAnousu where j39_matric = iMatricula and j31_grupo = 47 and j39_idprinc is true and j39_dtdemo is null; if not found or nAliquota = 0 or nAliquota is null then nAliquota := 1.5; end if; perform fc_debug(\'< getaliquota > aliquota final: \'||nAliquota, bRaise); execute \'update tmpdadosiptu set aliq = \'||nAliquota; rtp_aliquota_iptu.coderro = 0; rtp_aliquota_iptu.descrerro = \'\'; rtp_aliquota_iptu.aliquota = nAliquota; return rtp_aliquota_iptu; end; $$ language \'plpgsql\';', '0');

SQL
);
    }

    public function upEstrutura()
    {
      DB::connection()->getPdo()->exec(<<<SQL

CREATE OR REPLACE FUNCTION fc_calculoiptu_campinagrande_2022(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer) RETURNS varchar(100) AS
$$

declare

   iMatricula                          alias   for $1;
   iAnousu                             alias   for $2;
   lGerafinanc                         alias   for $3;
   lAtualizaParcela                    alias   for $4;
   lNovonumpre                         alias   for $5;
   lCalculogeral                       alias   for $6;
   lDemonstrativo                      alias   for $7;
   iParcelaini                         alias   for $8;
   iParcelafim                         alias   for $9;

   iIdbql                              integer default 0;
   iNumcgm                             integer default 0;
   iCodcli                             integer default 0;
   iCodisen                            integer default 0;
   iTipois                             integer default 0;
   iParcelas                           integer default 0;
   iNumconstr                          integer default 0;
   iCodErro                            integer default 0;

   dDatabaixa                          date;

   nAreal                              numeric default 0;
   nAreac                              numeric default 0;
   nTotarea                            numeric default 0;
   nFracao                             numeric default 0;
   nFracaolote                         numeric default 0;
   nAliquota                           numeric default 0;
   nIsenaliq                           numeric default 0;
   nArealo                             numeric default 0;
   nVvc                                numeric(15,2) default 0;
   nVvt                                numeric(15,2) default 0;
   nVv                                 numeric(15,2) default 0;
   nViptu                              numeric(15,2) default 0;

   tRetorno                            text default '';
   tDemo                               text default '';
   tErro                               text default '';

   lPredial                            boolean;
   lFinanceiro                         boolean;
   lIsencaoAutomatica                  boolean default false;
   lDadosIptu                          boolean;
   lErro                               boolean;
   lIsentaxas                          boolean;
   lTempagamento                       boolean;
   lEmpagamento                        boolean;
   lTaxasCalculadas                    boolean;
   lRaise                              boolean default false; -- true para habilitar raise na funcao principal
   lSubRaise                           boolean default false; -- true para habilitar raise nas sub-funcoes

   iCodigoCadastroIsencao              integer default 0; 

   rCfiptu                             record;
   rConstr                             record;

begin

  lRaise := ( case when fc_getsession('DB_debugon') is null then false else true end );
  lSubRaise := lRaise;

  perform fc_debug('INICIANDO CALCULO',lRaise,true,false);
  -- raise notice 'Inicio %', current_time;
  perform fc_debug('',lRaise,true,false);
  
  /**
   * Guarda os parametros do calculo
   */
  
  select * from into rCfiptu cfiptu where j18_anousu = iAnousu;
  /*
   O cálculo deverá lançar automaticamente uma isenção conforme o tipo de isenção padrão
  configurado para os imóveis que atenderem os seguintes critérios:
  - a soma total da área construida das unidades do lote tem que sem igual ou inferior a 60
  m2

  j34_area <= 60 
  - a taxa de ocupação do lote deve ser igual ou inferior a 70% conforme cálculo:
  área total construida no lote dividido pela área do lote
  - a ocupação de todas as unidades do lote devem ser residencial (caracteristica 472 grupo
  47)

  j34_totcon/j34_area

  - o tipo de construção de todas as unidades do lote devem ser casa (caracteristicas
  461,462,463,464,468,469,4610,4611,4612,4725 do grupo 46). 

  461,462,463,464,468,469,4610,4611,4612,4725
*/
  -- raise notice 'Inicio isencao automatica %', current_time;
  select j34_area, coalesce(j34_totcon, 0)
    into nArealo, nTotarea
    from iptubase
   inner join lote 
      on j34_idbql = j01_idbql
   where j01_matric = iMatricula;    
  
  if not found then
    
    select fc_iptu_geterro( 3, 'ERRO - Sem dados para a matricula '||iMatricula ) into tRetorno;
    return tRetorno;  
  end if;
  
  lIsencaoAutomatica := false;
  if nTotarea <= 60
  then
    
    -- raise notice 'passou na primeira regra';
    lIsencaoAutomatica := true;
    if (nTotarea/nArealo) > 0.7
    then

      -- raise notice 'passou na segunda regra'; 
      for rConstr in 
      select j31_grupo,
             j31_codigo
        from iptuconstr
       inner join carconstr
          on j39_matric = j48_matric 
         and j39_idcons = j48_idcons
       inner join caracter
          on j31_codigo = j48_caract  
       where j39_matric = iMatricula
         and j39_dtdemo is null
         and j31_grupo in (47, 46)
     loop
      
      -- raise notice 'grupo % caracteristica %', rConstr.j31_grupo, rConstr.j31_codigo;
      if (rConstr.j31_grupo = 47 and 
          rConstr.j31_codigo <> 472) 
        or (rConstr.j31_grupo = 46 and 
            rConstr.j31_codigo not in(461,462,463,464,468,469,4610,4611,4612,4725))
      then

        -- raise notice 'nao passou na regra de caracteristicas'; 
        lIsencaoAutomatica := false;
        exit;
      end if; 
     end loop;
     
    --  if lIsencaoAutomatica is true
    --  then
    --    raise notice 'passou na regra de caracteristicas'; 
    --  end if;  
    else 

      -- raise notice 'nao passou na segunda regra';
      lIsencaoAutomatica := false;
    end if;
  end if; 

  if lIsencaoAutomatica is true 
  and not exists(select 1 
                   from iptuisen
                  inner join isenexe
                     on j46_codigo = j47_codigo
                  where j46_matric = iMatricula
                    and j46_tipo   = rCfiptu.j18_tipoisen
                    and j47_anousu = iAnousu)
  then 
  
    INSERT INTO iptuisen(j46_codigo, j46_matric, j46_tipo, j46_dtini, j46_dtfim, j46_perc, j46_dtinc, j46_idusu, j46_hist, j46_arealo)
         VALUES (nextval('iptuisen_j46_codigo_seq'), iMatricula, rCfiptu.j18_tipoisen, ('01-01-'||iAnousu)::date, ('31-12-'||iAnousu)::date, 100, current_date, 1, 'ISENCAO AUTOMATICA', 0);
    
    INSERT INTO isenexe(j47_codigo,j47_anousu )
         VALUES (currval('iptuisen_j46_codigo_seq'), iAnousu);
  else 
    
    select j46_codigo 
      into iCodigoCadastroIsencao 
      from iptuisen
     inner join isenexe
        on j46_codigo = j47_codigo
      left join isenproc
        on j61_codigo = j46_codigo 
      left join isentaxa
        on j56_codigo = j46_codigo  
     where j46_matric = iMatricula
       and j46_tipo   = rCfiptu.j18_tipoisen
       and j47_anousu = iAnousu
       and j61_codigo is null
       and j56_codigo is null;

      if found then   
        
        delete
          from isenexe 
         where j47_codigo = iCodigoCadastroIsencao;
        
        delete
          from iptuisen 
         where j46_codigo = iCodigoCadastroIsencao;

      end if;

  end if;

  -- raise notice 'Fim isencao automatica %', current_time;

  -- raise notice 'Inicio pre calculo %', current_time;
  /**
   * Executa PRE CALCULO
   */
  select r_iIdbql, r_nAreal, r_nFracao, r_iNumcgm, r_dDatabaixa, r_nFracaolote,
         r_tDemo, r_lTempagamento, r_lEmpagamento, r_iCodisen, r_iTipois, r_nIsenaliq,
         r_lIsentaxas, r_nArealote, r_iCodCli, r_tRetorno

    into iIdbql, nAreal, nFracao, iNumcgm, dDatabaixa, nFracaolote, tDemo, lTempagamento,
         lEmpagamento, iCodisen, iTipois, nIsenaliq, lIsentaxas, nArealo, iCodCli, tRetorno

  from fc_iptu_precalculo( iMatricula, iAnousu, lCalculogeral, lAtualizaParcela, lDemonstrativo, lRaise );

  perform fc_debug(' RETORNO DA PRE CALCULO: ',            lRaise);
  perform fc_debug('  iIdbql        -> ' || iIdbql,        lRaise);
  perform fc_debug('  nAreal        -> ' || nAreal,        lRaise);
  perform fc_debug('  nFracao       -> ' || nFracao,       lRaise);
  perform fc_debug('  iNumcgm       -> ' || iNumcgm,       lRaise);
  perform fc_debug('  dDatabaixa    -> ' || dDatabaixa,    lRaise);
  perform fc_debug('  nFracaolote   -> ' || nFracaolote,   lRaise);
  perform fc_debug('  tDemo         -> ' || tDemo,         lRaise);
  perform fc_debug('  lTempagamento -> ' || lTempagamento, lRaise);
  perform fc_debug('  lEmpagamento  -> ' || lEmpagamento,  lRaise);
  perform fc_debug('  iCodisen      -> ' || iCodisen,      lRaise);
  perform fc_debug('  iTipois       -> ' || iTipois,       lRaise);
  perform fc_debug('  nIsenaliq     -> ' || nIsenaliq,     lRaise);
  perform fc_debug('  lIsentaxas    -> ' || lIsentaxas,    lRaise);
  perform fc_debug('  nArealote     -> ' || nArealo,       lRaise);
  perform fc_debug('  iCodCli       -> ' || iCodCli,       lRaise);
  perform fc_debug('  tRetorno      -> ' || tRetorno,      lRaise);
  perform fc_debug('',lRaise,true,false);
  -- raise notice 'Fim pre calculo %', current_time;
  /**
   * Variavel de retorno contem a msg
   * de erro retornada do pre calculo
   */
  
  if trim(tRetorno) <> '' then
    return tRetorno;
  end if;

  update tmpdadosiptu set matric = iMatricula;

  update tmpdadostaxa 
     set anousu = iAnousu, 
         matric = iMatricula, 
         idbql  = iIdbql, 
         valref = rCfiptu.j18_vlrref;

  /**
   * Calcula valor do terreno
   */
  
  perform fc_debug('PARAMETROS fc_iptu_calculavvt_campinagrande_2022 IDBQL: '||iIdbql||' - iMatricula: '||iMatricula||' - Anousu: '||iAnousu||' - FRACAO DO LOTE: '||nFracaolote||' - DEMO: '||lDemonstrativo||' - DEBUG: '||lRaise, lRaise);
  -- raise notice 'Inicio VVT %', current_time;
  select rnvvt, rnarea, rtdemo, rtmsgerro, rberro, riCodErro, rtErro
    into nVvt, nAreac, tDemo, tRetorno, lErro, iCodErro, tErro
  from fc_iptu_calculavvt_campinagrande_2022( iIdbql, iMatricula, iAnousu, nFracaolote, lDemonstrativo, lRaise);
     
  perform fc_debug('RETORNO fc_iptu_calculavvt_campinagrande_2022 -> VVT: '||nVvt||' - AREA CONSTRUIDA: '||nAreac||' - RETORNO: '||tRetorno||' - ERRO: '||lErro, lRaise);
  perform fc_debug('', lRaise);
  -- raise notice 'Fim VVT %', current_time;
  if lErro is true then
    select fc_iptu_geterro( iCodErro, tErro ) into tRetorno;
    return tRetorno;
  end if;
  
  /**
   * Calcula valor da construcao
   */
  
  perform fc_debug('PARAMETROS fc_iptu_calculavvc_campinagrande_2022 MATRICULA: '||iMatricula||' - ANOUSU: '||iAnousu||' - DEMO: '||lDemonstrativo||' - DEBUG: '||lRaise, lRaise);
  -- raise notice 'Inicio VVC %', current_time;
  select rnvvc, rntotarea, rinumconstr, rtdemo, rtmsgerro, rberro, riCodErro, rtErro
    into nVvc, nTotarea, iNumconstr, tDemo, tRetorno, lErro, iCodErro, tErro
  from fc_iptu_calculavvc_campinagrande_2022( iMatricula, iAnousu, lDemonstrativo, lRaise );
  
  perform fc_debug('RETORNO fc_iptu_calculavvc_campinagrande_2022 -> VVC: '||nVvc||' - AREA TOTAL: '||nTotarea||' - NUMERO DE CONSTRUCOES: '||iNumconstr||' - RETORNO: '||tRetorno||' - ERRO: '||lErro, lRaise);
  perform fc_debug('', lRaise);
  -- raise notice 'Fim VVC %', current_time;
  if lErro is true then
    select fc_iptu_geterro(iCodErro, tErro) into tRetorno;
    return tRetorno;
  end if;

  select predial into lPredial from tmpdadosiptu;
  
  /* BUSCA A ALIQUOTA  */
  
  -- so executar se nao for isento
  perform fc_debug('BUSCA A ALIQUOTA DO IPTU ', lRaise);
  -- raise notice 'Inicio aliquota %', current_time;
  select coderro, descrerro, aliquota
    into iCodErro, tErro, nAliquota
  from fc_iptu_getaliquota_campinagrande_2022(iMatricula, iIdbql, iAnousu, lPredial, lSubRaise);
  -- raise notice 'Fim Aliquota %', current_time;
  if nAliquota = 0 then
    select fc_iptu_geterro( iCodErro, tErro ) into tRetorno;
    return tRetorno;
  end if;

  
  perform fc_debug('RETORNO DA BUSCA A ALIQUOTA DO IPTU ', lRaise);
  perform fc_debug(' ', lRaise);


  /*--------- CALCULA O VALOR VENAL -----------*/
  
  perform fc_debug('valor venal construcao (nVvc) - '||nVvc||' valor venal terreno (nVvt) - '||nVvt, lRaise);
  
  nVv    := nVvc + nVvt;
  
  perform fc_debug('valor venal total - '||nVv, lRaise);
  
  nViptu := nVv * ( nAliquota / 100 );
  
  perform fc_debug('valor iptu '||nViptu||' - aliquota '||nAliquota||'%', lRaise);
  perform fc_debug(' ', lRaise);
  perform fc_debug('Inserindo as receitas de IPTU na tabela tmprecval ', lRaise);
  perform fc_debug(' ', lRaise);

  if lPredial then
     insert into tmprecval values (rCfiptu.j18_rpredi, nViptu, 1, false);
  else
     insert into tmprecval values (rCfiptu.j18_rterri, nViptu, 1, false);
  end if;

  perform fc_debug('Inserindo valor e codigo de vencimento do IPTU na tabela tmpdadosiptu', lRaise);
  perform fc_debug(' ', lRaise);
   
  update tmpdadosiptu 
     set viptu   = nViptu, 
         codvenc = rCfiptu.j18_vencim;
 
  /*-------------------------------------------*/
  
  select count(*)
    into iParcelas
  from cadvencdesc 
 inner join cadvenc on q92_codigo = q82_codigo
 where q92_codigo = rCfiptu.j18_vencim ;
  
  if not found or iParcelas = 0 then
    select fc_iptu_geterro(14,'') into tRetorno;
    return tRetorno;
  end if;

  update tmpdadostaxa set valiptu = nViptu, vvt = nVvt, nparc = iParcelas, totareaconst = nTotarea;

  /* CALCULA AS TAXAS */


  perform fc_debug('PARAMETROS fc_iptu_calculataxas  ANOUSU '||iAnousu||' -- CODCLI '||iCodcli||' -- Debug: '||lSubRaise, lRaise);
  perform fc_debug(' ', lRaise);
  -- raise notice 'Inicio Taxa %', current_time;
  select fc_iptu_calculataxas(iMatricula, iAnousu, iCodcli, lSubRaise)
    into lTaxasCalculadas;
  -- raise notice 'Fim taxa %', current_time;    
  perform fc_debug('RETORNO fc_iptu_calculataxas --->>> TAXAS CALCULADAS - '||lTaxasCalculadas, lRaise);

  -- raise notice 'Inicio demonstrativo %', current_time; 
  /* MONTA O DEMONSTRATIVO */
  select fc_iptu_demonstrativo(iMatricula,iAnousu,iIdbql,lSubRaise )
    into tDemo;
  -- raise notice 'Fim demonstrativo %', current_time; 
  /* GERA FINANCEIRO */
  if lDemonstrativo is false then -- Se nao for demonstrativo gera o financeiro, caso contrario retorna o demonstrativo
    -- raise notice 'Inicio dados IPTU %', current_time; 
    select fc_iptu_geradadosiptu(iMatricula,iIdbql,iAnousu,nIsenaliq,lDemonstrativo,lSubRaise)
      into lDadosIptu;
    -- raise notice 'Fim dados IPTU %', current_time; 
      if lGerafinanc then

        -- raise notice 'Inicio Financeiro %', current_time; 
        select fc_iptu_gerafinanceiro(iMatricula,iAnousu,iParcelaini,iParcelafim,lCalculogeral,lTempagamento,lNovonumpre,lDemonstrativo,lSubRaise)
          into lFinanceiro;
        -- raise notice 'Fim financeiro %', current_time;   
      end if;
  else
    return tDemo;
  end if;

  if lDemonstrativo is false then

    update iptucalc
       set j23_manual = tDemo
     where j23_matric = iMatricula
       and j23_anousu = iAnousu;
  end if;

  select fc_iptu_geterro(1, '') into tRetorno;
  -- raise notice 'Fim Calculo %', current_time; 
  return tRetorno;
  
end;

$$ LANGUAGE 'plpgsql';

create or replace function fc_iptu_calculavvc_campinagrande_2022(integer,integer,boolean,boolean) returns tp_iptu_calculavvc as
$$

 declare

     iMatricula          alias for $1;
     iAnousu             alias for $2;
     lMostrademo         alias for $3;
     lRaise              alias for $4;

     nAreatc             numeric default 0;
     nVm2c               numeric default 0;
     nVvcP               numeric default 0;
     nVvc                numeric default 0;

     iNumerocontr        integer default 0;
     iCaractGrupo        integer default 0;
     sDescrGrupo         varchar;     
     lAtualiza           boolean default true;

     rConstr             record;
     rtp_iptu_calculavvc tp_iptu_calculavvc%ROWTYPE;

 begin

     perform fc_debug('INICIANDO CALCULO VVC ...', lRaise);

     rtp_iptu_calculavvc.rnVvc       := 0;
     rtp_iptu_calculavvc.rnTotarea   := 0;
     rtp_iptu_calculavvc.riNumconstr := 0;
     rtp_iptu_calculavvc.rtDemo      := '';
     rtp_iptu_calculavvc.rtMsgerro   := 'Retorno ok' ;
     rtp_iptu_calculavvc.rbErro      := 'f';
     rtp_iptu_calculavvc.riCodErro   := 0;
     rtp_iptu_calculavvc.rtErro      := '';

     iNumerocontr := 0;

     for rConstr in select j39_matric, j39_idcons, j39_idprinc, j39_area, j39_areap
                      from iptuconstr
                     where j39_matric = iMatricula
                       and j39_dtdemo is null
     loop

       select j71_valor, j31_codigo, j31_descr 
         into nVm2c, iCaractGrupo, sDescrGrupo
         from carconstr 
        inner join caracter 
           on j31_codigo = j48_caract
          and j31_grupo  = 46
        inner join carvalor 
          on j71_anousu = iAnousu
         and j71_caract = j48_caract
         and rConstr.j39_area between j71_ini and j71_fim
       where j48_matric = rConstr.j39_matric
         and j48_idcons = rConstr.j39_idcons;

       if not found then

          perform fc_debug('< calculo vvc > Matricula '||rConstr.j39_matric||' Edificacao '||rConstr.j39_idcons||' nao encontrado .', lRaise);
          rtp_iptu_calculavvc.rtErro      := ' PARA O GRUPO 46 OU SEM VALOR PARA O ANO DE '||iAnousu;
          rtp_iptu_calculavvc.rtMsgErro   := 'SEM CARACTERISITICA PARA O GRUPO 46 OU SEM VALOR PARA O ANO DE '||iAnousu;
          rtp_iptu_calculavvc.riCodErro   := 24;
          rtp_iptu_calculavvc.rbErro      := 't';
          return rtp_iptu_calculavvc;
       end if;

       nVvcp   := nVm2c * rConstr.j39_area;
       nAreatc := nAreatc + rConstr.j39_area;
       nVvc    := nVvc + nVvcp;

       perform fc_debug('< calculo vvc > Valor venal total parcial - nVvc - '||nVvc||' nVm2c - '||nVm2c|| 'area - '||rConstr.j39_area, lRaise);

       iNumerocontr := iNumerocontr + 1;

       insert into tmpiptucale (anousu, matric, idcons, areaed, vm2, pontos, valor)
                     values (iAnousu, iMatricula, rConstr.j39_idcons, rConstr.j39_area, nVm2c, 0, nVvcp);
       if lAtualiza then
          update tmpdadosiptu set predial = true;
         lAtualiza := false;
       end if;

     end loop;

     nVvc := round(nVvc, 2);
     perform fc_debug('< calculo vvc > Valor venal total final - nVvc - '||nVvc, lRaise);

     rtp_iptu_calculavvc.rnVvc       := nVvc::numeric;
     rtp_iptu_calculavvc.rnTotarea   := nAreatc::numeric;
     rtp_iptu_calculavvc.riNumconstr := iNumerocontr;
     rtp_iptu_calculavvc.rtDemo      := '';
     rtp_iptu_calculavvc.rbErro      := 'f';

     update tmpdadosiptu set vvc = rtp_iptu_calculavvc.rnVvc;

     return rtp_iptu_calculavvc;

 end;

$$  language 'plpgsql';

create or replace function fc_iptu_calculavvt_campinagrande_2022(integer,integer,integer,numeric,boolean,boolean) returns tp_iptu_calculavvt as
$$

declare

  iIdbql                   alias for $1;
  iMatricula               alias for $2;
  iAnousu                  alias for $3;
  nFracao                  alias for $4;
  lMostrademo              alias for $5;
  lRaise                   alias for $6;
  
  lPredial                 boolean default false;
  --iRegra                   integer;
  nVm2t                    numeric default 0;
  nVm2tCalc                numeric default 0;
  nAreaLoteCorrigi         numeric default 0;
  nAreaTerreno             numeric default 0;
  nValor                   numeric default 0;
  nTestada                 numeric default 0;
  nFatorReducao            numeric default 0;
  nFatorSituacao           numeric default 0;
  nFatorTopografia         numeric default 0;
  nFatorPedologia          numeric default 0;
  
  rtp_iptu_calculavvt      tp_iptu_calculavvt%ROWTYPE;
  
begin
 
  rtp_iptu_calculavvt.rnAreaTotalC := 0;
  rtp_iptu_calculavvt.rnArea       := 0;
  rtp_iptu_calculavvt.rnTestada    := 0;
  rtp_iptu_calculavvt.riCoderro    := 0;
  rtp_iptu_calculavvt.rtDemo       := '';
  rtp_iptu_calculavvt.rtMsgerro    := '';
  rtp_iptu_calculavvt.rtErro       := '';
  rtp_iptu_calculavvt.rbErro       := 'f';

  perform fc_debug('< calculo vvt > INICIANDO CALCULO DO VALOR VENAL TERRITORIAL...', lRaise);

  select case 
         when j39_matric is not null
         then 't'::boolean
         else 
           'f'::boolean
         end 
    into lPredial
    from iptubase 
    left join iptuconstr
      on j39_matric = j01_matric
     and j39_dtdemo is null 
   where j01_matric = iMatricula;

  if not found
  then
    
    rtp_iptu_calculavvt.rbErro    := 't';
    rtp_iptu_calculavvt.riCoderro := 9;
    rtp_iptu_calculavvt.rtErro    := ' DADOS DA MATRICULA NAO ENCONTRADO';
   
    return rtp_iptu_calculavvt;
    
  end if;  

  /* verifica a area do lote */
  select case when j34_area = 0
              then j34_areal
              else j34_area
         end
    into nAreaTerreno
    from lote
   where j34_idbql = iIdbql;

  if nAreaTerreno is null or nAreaTerreno = 0 then
      
      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 3;
      rtp_iptu_calculavvt.rtErro    := 'AREA DO LOTE NAO ENCONTRADA OU ZERADA';
   
      return rtp_iptu_calculavvt;
  end if;
    
  nAreaLoteCorrigi := ( nAreaTerreno * ( nFracao / 100::numeric ) );

  perform fc_debug('< calculo vvt > Area do terreno: '||nAreaTerreno, lRaise);

  /* busca valor do m2 do terreno */
    
  select coalesce(j36_testad,0), j81_valorterreno
    into nTestada, nVm2t
    from testada 
   inner join testpri   
      on j49_idbql  = j36_idbql
     and j49_face   = j36_face
     and j49_codigo = j36_codigo
   inner join facevalor 
      on j81_face   = j36_face
     and j81_anousu = iAnousu
    where j36_idbql = iIdbql;

    if not found then

      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 25;
      rtp_iptu_calculavvt.rtMsgErro := 'VERIFIQUE O VALOR DO M2 DO TERRENO PARA A FACE';

      return rtp_iptu_calculavvt;
    end if;
    

    perform fc_debug('< calculo vvt > Valor do m2 do terreno: '||nVm2t, lRaise);

    /*
      Imóveis não edificados até 250 m²
      Imóveis não edificados acima de 250 m²
      Imóveis edificados (apartamento) (caracter 131 e 140)
      Imóveis edificados (demais tipos de construções)
    */

    case 
      when lPredial is false 
       and nAreaLoteCorrigi <= 250
      then

        nFatorReducao := 0.3;
      when lPredial is false 
       and nAreaLoteCorrigi > 250
      then

        nFatorReducao := 0.4;
      when lPredial is true 
       and exists(select 1
                    from iptuconstr
                   inner join carconstr 
                     on j48_matric = j39_matric
                    and j48_idcons = j39_idcons
                    and j39_dtdemo is null
                    and j39_idprinc is true
                  where j39_matric = iMatricula
                    and j48_caract in (4613, 4614))
      then 
        
        nFatorReducao := 1;
      else
        
        nFatorReducao := 0.35;
    end case;
    
    if not found or nFatorReducao > 1
    then

      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 104;
      rtp_iptu_calculavvt.rtErro    := '';
     
      return rtp_iptu_calculavvt;
    end if;   
    
    nVm2tCalc     := nVm2t * nFatorReducao;
    
    /* busca fator pedologia */
    select j74_fator
      into nFatorPedologia
    from lote inner join carlote   on j35_idbql  = j34_idbql
              inner join caracter  on j31_codigo = j35_caract
              inner join carfator  on j74_anousu = iAnousu
                                  and j74_caract = j35_caract
    where j34_idbql = iIdbql
      and j31_grupo = 9;

    if nFatorPedologia = 0 or nFatorPedologia is null then
  
       rtp_iptu_calculavvt.rbErro    := 't';
       rtp_iptu_calculavvt.riCoderro := 24;
       rtp_iptu_calculavvt.rtErro    := ' PARA GRUPO 9 OU SEM VALOR PARA O ANO DE '||iAnousu;  
       rtp_iptu_calculavvt.rtMsgErro := 'SEM CARACTERISTICA PARA GRUPO 9 OU SEM VALOR PARA O ANO DE '||iAnousu;
       return rtp_iptu_calculavvt;
    end if;

    /* busca fator Situacao */
    select j74_fator
      into nFatorSituacao
      from lote 
     inner join carlote   
        on j35_idbql  = j34_idbql
     inner join caracter  
        on j31_codigo = j35_caract
     inner join carfator  
        on j74_anousu = iAnousu
       and j74_caract = j35_caract
     where j34_idbql = iIdbql
       and j31_grupo = 13;

    if nFatorSituacao = 0 or nFatorSituacao is null 
    then

      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 24;
      rtp_iptu_calculavvt.rtErro    := ' PARA GRUPO 13 OU SEM VALOR PARA O ANO DE '||iAnousu;
      rtp_iptu_calculavvt.rtMsgErro := 'SEM CARACTERISTICA PARA GRUPO 13 OU SEM VALOR PARA O ANO DE '||iAnousu;
      return rtp_iptu_calculavvt;
    end if;

  /* busca fator topografia */

  select j74_fator
    into nFatorTopografia
    from lote 
   inner join carlote   
      on j35_idbql  = j34_idbql
   inner join caracter  
      on j31_codigo = j35_caract
   inner join carfator  
      on j74_anousu = iAnousu
     and j74_caract = j35_caract
   where j34_idbql = iIdbql
     and j31_grupo = 16;

   if nFatorTopografia = 0 or nFatorTopografia is null then
     
      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 24;
      rtp_iptu_calculavvt.rtErro    := ' PARA GRUPO 16 OU SEM VALOR PARA O ANO DE '||iAnousu;
      rtp_iptu_calculavvt.rtMsgErro := 'SEM CARACTERISTICA PARA GRUPO 16 OU SEM VALOR PARA O ANO DE '||iAnousu;
   
      return rtp_iptu_calculavvt;
   end if;

   
	 nValor := round( nAreaLoteCorrigi * (nVm2t*nFatorReducao)::numeric * nFatorPedologia * nFatorSituacao * nFatorTopografia, 2);
      
   if nValor <= 0 or nValor is null then
   
      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 113;
      rtp_iptu_calculavvt.rtErro    := '';
   
      return rtp_iptu_calculavvt;
   end if;

    /* formula de calculo do terreno */
   perform fc_debug('< calculo vvt > Formula: VVT = (nAreaLoteCorrigi * nVm2t * nFatorPedologia * nFatorSituacao * nFatorTopografia)', lRaise);
  --  raise notice '< calculo vvt > Formula: VVT = (% * % * % * % * %)', nAreaLoteCorrigi, nVm2tCalc, nFatorPedologia, nFatorSituacao, nFatorTopografia;
   perform fc_debug('', lRaise);
   perform fc_debug('< calculo vvt > Formula: '||nValor||' = ('||nAreaLoteCorrigi||' * ('||nVm2t||' * '||nFatorReducao||') * '||nFatorPedologia||' * '||nFatorSituacao||' * '||nFatorTopografia||')', lRaise, lRaise, lRaise);
    
   rtp_iptu_calculavvt.rnArea       := nAreaTerreno;
   rtp_iptu_calculavvt.rnVvt        := nValor;
   rtp_iptu_calculavvt.rnAreaTotalC := nAreaLoteCorrigi;
   rtp_iptu_calculavvt.rnTestada    := nTestada;
   rtp_iptu_calculavvt.rtDemo       := '';
   rtp_iptu_calculavvt.rtMsgerro    := '';
   rtp_iptu_calculavvt.rbErro       := 'f';

   update tmpdadosiptu 
      set vvt = rtp_iptu_calculavvt.rnVvt, 
          vm2t= nVm2t, 
          areat=nAreaLoteCorrigi;

   return rtp_iptu_calculavvt;

end;
$$  language 'plpgsql';

CREATE TYPE tp_aliquota_iptu AS (
  coderro   int,
  descrerro text,
  aliquota  numeric
);

create or replace function fc_iptu_getaliquota_campinagrande_2022(integer, integer, integer, boolean, boolean) returns tp_aliquota_iptu as
$$

declare

    iMatricula   alias for $1;
    iIdbql       alias for $2;
    iAnousu      alias for $3;
    bPredial     alias for $4;
    bRaise       alias for $5;
    nAliquota    numeric default 0;
    rtp_aliquota_iptu      tp_aliquota_iptu%ROWTYPE;
    
begin

  perform fc_debug('DEFININDO QUAL ALIQUOTA APLICAR ...', bRaise);

  rtp_aliquota_iptu.coderro   = 0;
  rtp_aliquota_iptu.descrerro = '';
  rtp_aliquota_iptu.aliquota  = 0;

  select coalesce(j73_aliq, 0)
    into nAliquota
    from iptuconstr
   inner join carconstr
      on j39_matric = j48_matric
     and j39_idcons = j48_idcons
   inner join caracter
      on j31_codigo = j48_caract 
   inner join caraliq  
      on j73_caract = j48_caract
     and j73_anousu = iAnousu
   where j39_matric = iMatricula
     and j31_grupo  = 47
     and j39_idprinc is true
     and j39_dtdemo is null;

   if not found or nAliquota = 0 or nAliquota is null then

      nAliquota := 1.5;
   end if;
 
   perform fc_debug('< getaliquota > aliquota final: '||nAliquota, bRaise);
 
   execute 'update tmpdadosiptu set aliq = '||nAliquota;
 
   rtp_aliquota_iptu.coderro   = 0;
   rtp_aliquota_iptu.descrerro = '';
   rtp_aliquota_iptu.aliquota  = nAliquota;
 
   return rtp_aliquota_iptu;

end;
$$  language 'plpgsql';

create or replace function fc_iptu_taxalixo_campinagrande_2022(integer, numeric, integer, numeric, numeric, boolean) returns boolean as
$$

declare

   iReceita          alias for $1;
   iAliquota         alias for $2;
   iHistCalc         alias for $3;
   iPercIsen         alias for $4;
   nValpar           alias for $5;
   lRaise            alias for $6;

   nValTaxa          numeric(15,2) default 0;
   
   iAnousu           integer       default 0;
   iMatricula        integer       default 0;

   tSql              text          default '';
   tRetorno          text          default '';

begin

   perform fc_debug(' < iptu_taxalixo > Calculando taxa de lixo',  lRaise);
   perform fc_debug(' ',                                           lRaise);
   perform fc_debug(' < iptu_taxalixo > receita: '   || iReceita,  lRaise);
   perform fc_debug(' < iptu_taxalixo > aliq: '      || iAliquota, lRaise);
   perform fc_debug(' < iptu_taxalixo > historico: ' || iHistCalc, lRaise);
   
   select anousu, matric
     into iAnousu, iMatricula
     from tmpdadostaxa;
    
   if not found or iAnousu = 0 then

     raise notice 'Ano do calculo nao encontrado';
     return false;
   end if;

   select j71_valor 
     into nValTaxa 
     from iptubase
    inner join lote
       on j01_idbql  = j34_idbql  
    inner join iptuconstr
       on j39_matric = j01_matric
    inner join carconstr  
       on j48_matric = j39_matric
      and j48_idcons = j39_idcons
    inner join caracter   
       on j31_codigo = j48_caract
      and j31_grupo  = 47
    inner join carvalor 
       on j71_anousu = iAnousu
      and j71_caract = j48_caract
      and j39_area between j71_ini and j71_fim
      and j71_quantini = case 
                         when j34_bairro in (10, 40, 50) 
                         then 1
                         else 0 end    
    where j39_matric  = iMatricula
      and j39_dtdemo  is null
      and j39_idprinc is true;
   
   if not found 
   then
     
      nValTaxa := nValpar;
   end if;

   insert into tmptaxapercisen values (iReceita, iPercIsen, 0, nValTaxa);
   
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

    public function downDicionario()
    {   
      DB::connection()->getPdo()->exec(<<<SQL

      delete
        from db_sysfuncoesparam
        where db42_funcao in (213, 214);
    
      delete 
        from db_sysfuncoes
       where codfuncao in (213, 214, 215, 216, 217);  
SQL
);
    }

    public function downEstrutura()
    {   
      DB::connection()->getPdo()->exec(<<<SQL

      delete
        from db_sysfuncoesparam
        where db42_funcao in (213, 214);
    
      delete 
        from db_sysfuncoes
       where codfuncao in (213, 214, 215, 216, 217);  

      drop type if exists tp_aliquota_iptu cascade;
      drop function if exists fc_iptu_taxalixo_campinagrande_2022(integer, numeric, integer, numeric, numeric, boolean);
      drop function if exists fc_calculoiptu_campinagrande_2022(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer);
      drop function if exists fc_iptu_calculavvc_campinagrande_2022(integer,integer,boolean,boolean);
      drop function if exists fc_iptu_calculavvt_campinagrande_2022(integer,integer,integer,numeric,boolean,boolean);
      drop function if exists fc_iptu_getaliquota_campinagrande_2022(integer, integer, integer, boolean, boolean);
SQL
);
    }
   
}
