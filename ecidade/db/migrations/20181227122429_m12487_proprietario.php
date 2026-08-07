<?php

use Classes\PostgresMigration;

class M12487Proprietario extends PostgresMigration
{
    public function up()
    {
        $sql = <<<ODONODABAGAÇA
create or replace view proprietario as
SELECT x.z01_numcgm,
   x.j01_matric,
   x.z01_cgccpf,
   case when x.totpropri > 0 then case when x.totpropri = 1 then RTRIM(X.PROPRIETARIO) when x.totpropri = 2 then RTRIM(X.PROPRIETARIO) || ' E OUTRO' else RTRIM(X.PROPRIETARIO) || ' E OUTROS' end else X.PROPRIETARIO end,
   trim(substr(case when x.totpromi > 0 then case when x.totpromi = 1 then RTRIM(X.z01_nome) when x.totpromi = 2 then RTRIM(X.z01_nome) || ' E OUTRO' else RTRIM(X.z01_nome) || ' E OUTROS' end else X.Z01_NOME end,1,40))::varchar as z01_nome,
   trim(case when x.totpromi > 0 then case when x.totpromi = 1 then RTRIM(X.z01_nomecompleto) when x.totpromi = 2 then RTRIM(X.z01_nomecompleto) || ' E OUTRO' else RTRIM(X.z01_nomecompleto) || ' E OUTROS' end else X.z01_nomecompleto end)::varchar as z01_nomecompleto,
   case when length(trim(x.z01_ender)) = 0 and (length(trim(x.z01_cxpostal)) > 0 and to_number(x.z01_cxpostal,'999999') > 0 ) then 'CAIXA POSTAL: '||x.z01_cxpostal else x.z01_ender::varchar(80) end as z01_ender,
   x.z01_munic,
   x.z01_bairro,
   x.z01_cep,
   x.z01_uf,
   x.z01_numero,
   x.z01_compl,
   x.codpri,
   x.nomepri::varchar(40),
   x.tipopri::varchar(40),
   x.j39_numero,
   x.j39_compl,
   x.j34_setor,
   x.j34_quadra,
   x.j34_lote,
   x.j34_zona,
   x.j34_bairro,
   x.j40_refant,
   x.j01_idbql,
   x.j14_codigo,
   x.j14_nome,
   x.j14_tipo,
   x.j13_codi,
   x.j13_descr,
   x.j01_baixa,
   x.j34_area,
   x.j34_areal,
   x.j44_numcgm,
   x.j41_numcgm,
   x.j43_matric,
   case when x.j43_munic  is null then x.z01_munic    else x.j43_munic  end as j43_munic,
   case when x.j43_ender  is null then x.z01_ender    else x.j43_ender  end as j43_ender,
   case when x.j43_cep    is null then x.z01_cep      else x.j43_cep    end as j43_cep,
   case when x.j43_uf     is null then x.z01_uf       else x.j43_uf     end as j43_uf,
   x.j43_dest,
   case when x.j43_numimo is null then x.z01_numero   else x.j43_numimo end as j43_numimo,
   case when x.j43_cxpost is null then x.z01_cxpostal else to_char(x.j43_cxpost,'99999999999999999999') end as j43_cxpost,
   case when x.j43_comple is null then x.z01_compl    else x.j43_comple end as j43_comple,
   x.j01_tipoimp::varchar(20),
   x.j01_codave,
   x.j37_zona,
   x.z01_cgmpri,
   x.j39_pavim,
   x.j05_codigoproprio, x.j06_setorloc, x.j06_quadraloc, x.j06_lote, x.j05_descr,
   x.pql_localizacao,
   cgmpropri.z01_cgccpf as z01_cgccpfpropri,
   cgmpropri.z01_nomecomple as z01_nomecomplepri,
   cgmpropri.z01_ender as z01_enderpri,
   cgmpropri.z01_munic as z01_municpri,
   cgmpropri.z01_bairro as z01_bairropri,
   cgmpropri.z01_cep as z01_ceppri,
   cgmpropri.z01_uf as z01_ufpri,
   cgmpropri.z01_numero as z01_numeropri,
   cgmpropri.z01_compl as z01_complpri
   FROM ( SELECT  J01_MATRIC,
          CGM.Z01_NUMCGM,
          CASE
            WHEN PROMITE.Z01_NUMCGM IS NULL
              THEN CGM.Z01_CGCCPF
            ELSE CGMPROMITE.Z01_CGCCPF
          END AS Z01_CGCCPF,

          CASE
            WHEN PROMITE.Z01_NOME IS NULL
              THEN CGM.Z01_NOME
            ELSE ( (select coalesce(trim(j18_textoprom)||' ','')
                      from cfiptu
                     order by j18_anousu
                      desc limit 1) || SUBSTR(PROMITE.Z01_NOME,1,29) )
          END AS Z01_NOME,

         CASE WHEN PROMITE.Z01_NOME IS NULL THEN CGM.Z01_NOME
         ELSE ( (select coalesce(trim(j18_textoprom)||' ','') from cfiptu order by j18_anousu desc limit 1) ||
               PROMITE.Z01_NOME ) END AS Z01_NOMECOMPLETO,

          CASE
            WHEN PROMITE.Z01_NUMCGM IS NULL
              THEN CGM.Z01_numcgm
          ELSE PROMITE.Z01_NUMCGM
          END AS Z01_CGMPRI,

          CGM.Z01_NOME AS PROPRIETARIO,
          case
            when j43_ender is null
              then
                case
                  when j41_numcgm is null
                    then CGM.Z01_ENDER
                  else promite.Z01_ENDER
                end
            else j43_ender
          end as z01_ender,

          case
            when j43_ender is null
              then
                case
                  when j41_numcgm is null
                    then  CGM.Z01_MUNIC
                  else promite.Z01_munic
                end
            else j43_munic
          end as z01_munic,

          case
            when j43_ender is null
              then
                case
                  when j41_numcgm is null
                    then  CGM.Z01_BAIRRO
                  else promite.z01_bairro
                end
            else j43_bairro
          end as z01_bairro,

     case
       when j43_ender is null
         then
           case
             when j41_numcgm is null
               then CGM.Z01_CEP
             else promite.z01_cep
           end
       else j43_cep
     end as z01_cep,

     case
       when j43_ender is null
         then case
                when j41_numcgm is null
                  then CGM.Z01_UF
                else promite.z01_uf
              end
       else j43_uf
     end as z01_uf,

     case
       when j43_ender is null
         then
           case
             when j41_numcgm is null
               then CGM.Z01_NUMERO
             else promite.z01_numero
           end
       else j43_numimo
     end as z01_NUMERO,

     case
       when j43_ender is null
         then
           case
             when j41_numcgm is null
               then  CGM.Z01_COMPL
             else promite.z01_COMPL end
       else j43_comple
     end as z01_COMPL,

     case
       when j43_cxpost is null
         then case
                when j41_numcgm is null
                  then cgm.z01_cxpostal
                else promite.z01_cxpostal
              end
       else j43_cxpost::varchar(20)
     end as z01_cxpostal,

     case
       when rr.J14_CODIGO is null
         then r.j14_codigo
       else rr.j14_codigo
     end as codpri,

     case
       when rr.J14_NOME   is null
         then r.j14_nome
       else rr.j14_nome
     end as nomepri,

     case
       when rr.J14_TIPO   is null
         then rt.j88_sigla
       else rrt.j88_sigla
     end as tipopri,

     case
       when length(trim(cast(j15_numero as varchar))) > 0 and j39_matric is null
         then j15_numero
       else j39_numero
     end as j39_numero,

     case
       when length(trim(j15_compl)) > 0 and j39_matric is null
         then j15_compl
       else j39_compl
     end as j39_compl,
       J34_SETOR,
       J34_QUADRA,
       J34_LOTE,
       J34_ZONA,
       J34_BAIRRO,
       J40_REFANT,
       J01_IDBQL,
       r.j14_codigo,
       r.j14_nome,
       rt.j88_sigla::text as j14_tipo,
       J13_CODI,
       J13_DESCR ,
       J01_BAIXA,
       J34_AREA,
       j34_areal,
       j44_NUMCGM,
       J41_NUMCGM,
       IPTUENDER.*,
       CASE
         WHEN IPTUCONSTR.J39_IDCONS IS NULL
           THEN 'Territorial'
         ELSE 'Predial'
       END as J01_TIPOIMP,

         j01_codave,
         j37_zona,
         j39_pavim,
         (select count(j42_matric) from propri where j42_matric = j01_matric) as totpropri,
         (select count(j41_matric) from promitente where j41_matric = j01_matric) as totpromi,
         j05_codigoproprio, j06_setorloc, j06_quadraloc, j06_lote, j05_descr,
         j05_codigoproprio||'-'||j05_descr||'/'||j06_quadraloc||'/'||j06_lote as pql_localizacao
         FROM IPTUBASE
        INNER JOIN      CGM           ON Z01_NUMCGM = J01_NUMCGM
        LEFT OUTER JOIN IPTUCONSTR    ON J39_MATRIC             = J01_MATRIC
                                     AND J39_IDPRINC            IS TRUE
                                     AND J39_DTDEMO             IS NULL
        LEFT OUTER JOIN RUAS rr       ON rr.J14_CODIGO = J39_CODIGO
        LEFT OUTER JOIN RUASTIPO rrt  ON rrt.j88_codigo = rr.j14_tipo
        LEFT OUTER JOIN IPTUANT       ON J40_MATRIC = J01_MATRIC
        INNER JOIN      LOTE          ON J01_IDBQL = J34_IDBQL
        left outer JOIN TESTPRI       ON J49_IDBQL = J34_IDBQL
        left outer JOIN TESTADANUMERO ON J15_IDBQL = J49_IDBQL
                                     and J15_face  = J49_face
        left outer JOIN FACE          ON J37_FACE = J49_FACE
        LEFT OUTER JOIN RUAS R        ON R.J14_CODIGO = J49_CODIGO
        LEFT OUTER JOIN RUASTIPO rt   ON rt.j88_codigo = r.j14_tipo
        LEFT OUTER JOIN IMOBIL        ON J01_MATRIC = J44_MATRIC
        LEFT OUTER JOIN PROMITENTE    ON j01_MATRIC             = J41_MATRIC
                                     AND PROMITENTE.J41_TIPOPRO IS TRUE
        LEFT OUTER JOIN CGM CGMPROMITE  ON J41_NUMCGM = CGMPROMITE.Z01_NUMCGM
        LEFT OUTER JOIN CGM PROMITE   ON PROMITE.Z01_NUMCGM = J41_NUMCGM
        LEFT OUTER JOIN BAIRRO        ON J13_CODI = J34_BAIRRO
        LEFT OUTER JOIN IPTUENDER     ON J01_MATRIC = J43_MATRIC
        LEFT join loteloc             on loteloc.j06_idbql   = iptubase.j01_idbql
        LEFT join setorloc            on setorloc.j05_codigo = loteloc.j06_setorloc
        left join setor               on setor.j30_codi      = lote.j34_setor
  ) AS X
  LEFT JOIN CGM CGMPROPRI ON X.Z01_NUMCGM = CGMPROPRI.Z01_NUMCGM

;
ODONODABAGAÇA;

        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<ODONODABAGAÇA
create or replace view proprietario as
SELECT x.z01_numcgm,
   x.j01_matric,
   x.z01_cgccpf,
   case when x.totpropri > 0 then case when x.totpropri = 2 then RTRIM(X.PROPRIETARIO) || ' E OUTRO' else RTRIM(X.PROPRIETARIO) || ' E OUTROS' end else X.PROPRIETARIO end,
   trim(substr(case when x.totpromi > 0 then case when x.totpropri = 2 then RTRIM(X.z01_nome) || ' E OUTRO' else RTRIM(X.z01_nome) || ' E OUTROS' end else X.Z01_NOME end,1,40))::varchar as z01_nome,
   trim(case when x.totpromi > 0 then case when x.totpropri = 2 then RTRIM(X.z01_nomecompleto) || ' E OUTRO' else RTRIM(X.z01_nomecompleto) || ' E OUTROS' end else X.z01_nomecompleto end)::varchar as z01_nomecompleto,
   case when length(trim(x.z01_ender)) = 0 and (length(trim(x.z01_cxpostal)) > 0 and to_number(x.z01_cxpostal,'999999') > 0 ) then 'CAIXA POSTAL: '||x.z01_cxpostal else x.z01_ender::varchar(80) end as z01_ender,
   x.z01_munic,
   x.z01_bairro,
   x.z01_cep,
   x.z01_uf,
   x.z01_numero,
   x.z01_compl,
   x.codpri,
   x.nomepri::varchar(40),
   x.tipopri::varchar(40),
   x.j39_numero,
   x.j39_compl,
   x.j34_setor,
   x.j34_quadra,
   x.j34_lote,
   x.j34_zona,
   x.j34_bairro,
   x.j40_refant,
   x.j01_idbql,
   x.j14_codigo,
   x.j14_nome,
   x.j14_tipo,
   x.j13_codi,
   x.j13_descr,
   x.j01_baixa,
   x.j34_area,
   x.j34_areal,
   x.j44_numcgm,
   x.j41_numcgm,
   x.j43_matric,
   case when x.j43_munic  is null then x.z01_munic    else x.j43_munic  end as j43_munic,
   case when x.j43_ender  is null then x.z01_ender    else x.j43_ender  end as j43_ender,
   case when x.j43_cep    is null then x.z01_cep      else x.j43_cep    end as j43_cep,
   case when x.j43_uf     is null then x.z01_uf       else x.j43_uf     end as j43_uf,
   x.j43_dest,
   case when x.j43_numimo is null then x.z01_numero   else x.j43_numimo end as j43_numimo,
   case when x.j43_cxpost is null then x.z01_cxpostal else to_char(x.j43_cxpost,'99999999999999999999') end as j43_cxpost,
   case when x.j43_comple is null then x.z01_compl    else x.j43_comple end as j43_comple,
   x.j01_tipoimp::varchar(20),
   x.j01_codave,
   x.j37_zona,
   x.z01_cgmpri,
   x.j39_pavim, 
   x.j05_codigoproprio, x.j06_setorloc, x.j06_quadraloc, x.j06_lote, x.j05_descr,
   x.pql_localizacao,
   cgmpropri.z01_cgccpf as z01_cgccpfpropri,
   cgmpropri.z01_nomecomple as z01_nomecomplepri,
   cgmpropri.z01_ender as z01_enderpri,
   cgmpropri.z01_munic as z01_municpri,
   cgmpropri.z01_bairro as z01_bairropri,
   cgmpropri.z01_cep as z01_ceppri,
   cgmpropri.z01_uf as z01_ufpri,
   cgmpropri.z01_numero as z01_numeropri,
   cgmpropri.z01_compl as z01_complpri
   FROM ( SELECT  J01_MATRIC, 
          CGM.Z01_NUMCGM, 
          CASE  
            WHEN PROMITE.Z01_NUMCGM IS NULL 
              THEN CGM.Z01_CGCCPF 
            ELSE CGMPROMITE.Z01_CGCCPF 
          END AS Z01_CGCCPF,
          
          CASE 
            WHEN PROMITE.Z01_NOME IS NULL 
              THEN CGM.Z01_NOME 
            ELSE ( (select coalesce(trim(j18_textoprom)||' ','') 
                      from cfiptu 
                     order by j18_anousu 
                      desc limit 1) || SUBSTR(PROMITE.Z01_NOME,1,29) ) 
          END AS Z01_NOME,

         CASE WHEN PROMITE.Z01_NOME IS NULL THEN CGM.Z01_NOME 
         ELSE ( (select coalesce(trim(j18_textoprom)||' ','') from cfiptu order by j18_anousu desc limit 1) ||
               PROMITE.Z01_NOME ) END AS Z01_NOMECOMPLETO,

          CASE 
            WHEN PROMITE.Z01_NUMCGM IS NULL 
              THEN CGM.Z01_numcgm 
          ELSE PROMITE.Z01_NUMCGM 
          END AS Z01_CGMPRI,
          
          CGM.Z01_NOME AS PROPRIETARIO,
          case 
            when j43_ender is null 
              then 
                case 
                  when j41_numcgm is null 
                    then CGM.Z01_ENDER 
                  else promite.Z01_ENDER 
                end 
            else j43_ender 
          end as z01_ender,
          
          case 
            when j43_ender is null 
              then 
                case 
                  when j41_numcgm is null 
                    then  CGM.Z01_MUNIC 
                  else promite.Z01_munic 
                end 
            else j43_munic 
          end as z01_munic,
          
          case 
            when j43_ender is null 
              then 
                case 
                  when j41_numcgm is null 
                    then  CGM.Z01_BAIRRO 
                  else promite.z01_bairro 
                end 
            else j43_bairro 
          end as z01_bairro,
          
     case 
       when j43_ender is null 
         then 
           case 
             when j41_numcgm is null 
               then CGM.Z01_CEP 
             else promite.z01_cep 
           end 
       else j43_cep 
     end as z01_cep,
     
     case 
       when j43_ender is null 
         then case 
                when j41_numcgm is null 
                  then CGM.Z01_UF
                else promite.z01_uf 
              end 
       else j43_uf 
     end as z01_uf,
     
     case 
       when j43_ender is null 
         then 
           case 
             when j41_numcgm is null 
               then CGM.Z01_NUMERO
             else promite.z01_numero 
           end 
       else j43_numimo 
     end as z01_NUMERO,
     
     case 
       when j43_ender is null 
         then 
           case 
             when j41_numcgm is null 
               then  CGM.Z01_COMPL
             else promite.z01_COMPL end 
       else j43_comple 
     end as z01_COMPL,
     
     case 
       when j43_cxpost is null 
         then case 
                when j41_numcgm is null 
                  then cgm.z01_cxpostal
                else promite.z01_cxpostal 
              end
       else j43_cxpost::varchar(20) 
     end as z01_cxpostal,
     
     case 
       when rr.J14_CODIGO is null 
         then r.j14_codigo 
       else rr.j14_codigo 
     end as codpri,
     
     case 
       when rr.J14_NOME   is null 
         then r.j14_nome   
       else rr.j14_nome   
     end as nomepri,
     
     case 
       when rr.J14_TIPO   is null 
         then rt.j88_sigla
       else rrt.j88_sigla
     end as tipopri,
     
     case 
       when length(trim(cast(j15_numero as varchar))) > 0 and j39_matric is null 
         then j15_numero   
       else j39_numero    
     end as j39_numero,
     
     case 
       when length(trim(j15_compl)) > 0 and j39_matric is null 
         then j15_compl    
       else j39_compl     
     end as j39_compl,
       J34_SETOR,
       J34_QUADRA,
       J34_LOTE,
       J34_ZONA,
       J34_BAIRRO,
       J40_REFANT, 
       J01_IDBQL, 
       r.j14_codigo,
       r.j14_nome, 
       rt.j88_sigla::text as j14_tipo,
       J13_CODI,
       J13_DESCR ,
       J01_BAIXA,
       J34_AREA,
       j34_areal,
       j44_NUMCGM,
       J41_NUMCGM,
       IPTUENDER.*,
       CASE 
         WHEN IPTUCONSTR.J39_IDCONS IS NULL 
           THEN 'Territorial' 
         ELSE 'Predial' 
       END as J01_TIPOIMP,

         j01_codave,
         j37_zona,
         j39_pavim, 
         (select count(j42_matric) from propri where j42_matric = j01_matric) as totpropri,
         (select count(j41_matric) from promitente where j41_matric = j01_matric) as totpromi,
         j05_codigoproprio, j06_setorloc, j06_quadraloc, j06_lote, j05_descr,
         j05_codigoproprio||'-'||j05_descr||'/'||j06_quadraloc||'/'||j06_lote as pql_localizacao
         FROM IPTUBASE
        INNER JOIN      CGM           ON Z01_NUMCGM = J01_NUMCGM
        LEFT OUTER JOIN IPTUCONSTR    ON J39_MATRIC             = J01_MATRIC 
                                     AND J39_IDPRINC            IS TRUE 
                                     AND J39_DTDEMO             IS NULL 
        LEFT OUTER JOIN RUAS rr       ON rr.J14_CODIGO = J39_CODIGO 
        LEFT OUTER JOIN RUASTIPO rrt  ON rrt.j88_codigo = rr.j14_tipo
        LEFT OUTER JOIN IPTUANT       ON J40_MATRIC = J01_MATRIC 
        INNER JOIN      LOTE          ON J01_IDBQL = J34_IDBQL 
        left outer JOIN TESTPRI       ON J49_IDBQL = J34_IDBQL 
        left outer JOIN TESTADANUMERO ON J15_IDBQL = J49_IDBQL
                                     and J15_face  = J49_face 
        left outer JOIN FACE          ON J37_FACE = J49_FACE 
        LEFT OUTER JOIN RUAS R        ON R.J14_CODIGO = J49_CODIGO 
        LEFT OUTER JOIN RUASTIPO rt   ON rt.j88_codigo = r.j14_tipo
        LEFT OUTER JOIN IMOBIL        ON J01_MATRIC = J44_MATRIC 
        LEFT OUTER JOIN PROMITENTE    ON j01_MATRIC             = J41_MATRIC 
                                     AND PROMITENTE.J41_TIPOPRO IS TRUE
        LEFT OUTER JOIN CGM CGMPROMITE  ON J41_NUMCGM = CGMPROMITE.Z01_NUMCGM
        LEFT OUTER JOIN CGM PROMITE   ON PROMITE.Z01_NUMCGM = J41_NUMCGM
        LEFT OUTER JOIN BAIRRO        ON J13_CODI = J34_BAIRRO
        LEFT OUTER JOIN IPTUENDER     ON J01_MATRIC = J43_MATRIC
        LEFT join loteloc             on loteloc.j06_idbql   = iptubase.j01_idbql
        LEFT join setorloc            on setorloc.j05_codigo = loteloc.j06_setorloc
        left join setor               on setor.j30_codi      = lote.j34_setor
  ) AS X
  LEFT JOIN CGM CGMPROPRI ON X.Z01_NUMCGM = CGMPROPRI.Z01_NUMCGM
        
;
ODONODABAGAÇA;

        $this->execute($sql);
    }
}
