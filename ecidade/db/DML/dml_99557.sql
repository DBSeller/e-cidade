drop view cadastro_pessoal_2008 ;
create view cadastro_pessoal_2008 as
SELECT rhpessoalmov.rh02_funcao as rh02_funcao,
       rhpessoalmov.rh02_instit,
       rhpessoalmov.rh02_anousu,
       rhpessoalmov.rh02_mesusu,
       rhpessoalmov.rh02_regist,
       rhpessoalmov.rh02_portadormolestia,
       rhpessoalmov.rh02_deficientefisico,
       rhpessoal.rh01_regist,
       rhpessoalmov.rh02_hrssem,
       rhpessoalmov.rh02_hrsmen,
       padroes.*,
       cgm.z01_nome,
       rhpessoal.rh01_admiss,
       rhpesrescisao.rh05_recis,
       rhpessoal.rh01_sexo,
       rhpessoal.rh01_nasc,
       rhpessoalmov.rh02_tbprev,
       rhlota.r70_estrut,
       rhlota.r70_descr,
       rhpessoalmov.rh02_funcao as rh01_funcao,
       btrim(rhfuncao.rh37_descr::text) AS rh37_descr,
       rhinstrucao.rh21_descr,
       rhestcivil.rh08_descr,
       rhipe.rh14_matipe,
       rhpesdoc.rh16_titele,
       rhpesdoc.rh16_zonael,
       rhpesdoc.rh16_secaoe,
       rhpesdoc.rh16_reserv,
       rhpesdoc.rh16_catres,
       rhpesdoc.rh16_ctps_n,
       rhpesdoc.rh16_ctps_s,
       rhpesdoc.rh16_ctps_d,
       rhpesdoc.rh16_ctps_uf,
       rhpesdoc.rh16_pis,
       cgm.z01_cgccpf,
       cgm.z01_ident,
       cgm.z01_telef,
       cgm.z01_ender,
       cgm.z01_compl,
       cgm.z01_numero,
       cgm.z01_munic,
       cgm.z01_bairro,
       cgm.z01_cep,
       cgm.z01_numcgm,
       rhpesdoc.rh16_carth_n,
       rhpesdoc.r16_carth_cat,
       rhpesdoc.rh16_carth_val,
       rhpespadrao.rh03_padrao,
       rhregime.rh30_descr,
       rhregime.rh30_regime,
       rhregime.rh30_vinculo,
       rhpesbanco.rh44_codban,
       rhpesbanco.rh44_agencia,
       rhpesbanco.rh44_dvagencia,
       rhpesbanco.rh44_conta,
       rhpesbanco.rh44_dvconta,
       rhlocaltrab.rh55_estrut,
       rhlocaltrab.rh55_descr,
       rhpessoal.rh01_trienio,
       rhpessoal.rh01_progres,
       rhraca.rh18_descr,
       f.rh37_descr as h07_cant,
       h07_regist,
       h07_tipadm,
       h07_dato  ,
       h07_dhist ,
       h07_ddem  ,
       h07_icon  ,
       h07_ires  ,
       h07_class ,
       h07_refe  ,
       h07_area  ,
       h07_nrato ,
       h07_nrfich,
       h07_impofi,
       h07_dpubl ,
       h07_fundam,
       h07_defet ,
       h07_tempor,
       h07_termin,
       h07_justif,
       h04_descr,
       h05_descr,
       h06_refer ,
       h06_eaber  ,
       h06_daber  ,
       h06_ehomo  ,
       h06_dhomo  ,
       h06_concur ,
       h06_dvalid ,
       h06_dprorr ,
       h06_dpubl  ,
       h06_nrproc ,
       ( select case when rh02_tbprev = 0
                     then 'SEM PREVIDENCIA'
                     else r33_nome
                end as r33_nome
         from inssirf
         where inssirf.r33_codtab = rhpessoalmov.rh02_tbprev+2
           and inssirf.r33_anousu = rhpessoalmov.rh02_anousu
           and rhpessoalmov.rh02_mesusu = inssirf.r33_mesusu
           and rhpessoalmov.rh02_instit = inssirf.r33_instit limit 1 ) as r33_nome
       
       
  FROM rhpessoal
       INNER JOIN cgm                 ON rhpessoal.rh01_numcgm         = cgm.z01_numcgm
       INNER JOIN rhpessoalmov        ON rhpessoalmov.rh02_anousu      = fc_anofolha(rhpessoalmov.rh02_instit)
                                     AND rhpessoalmov.rh02_mesusu      = fc_mesfolha(rhpessoalmov.rh02_instit)
                                     AND rhpessoalmov.rh02_regist      = rhpessoal.rh01_regist
       LEFT  JOIN rhpesrescisao       ON rhpesrescisao.rh05_seqpes     = rhpessoalmov.rh02_seqpes
       INNER JOIN rhlota              ON rhlota.r70_codigo             = rhpessoalmov.rh02_lota
                                     AND rhlota.r70_instit             = rhpessoalmov.rh02_instit
       INNER JOIN rhfuncao            ON rhpessoal.rh01_funcao         = rhfuncao.rh37_funcao
                                     AND rhfuncao.rh37_instit          = rhpessoalmov.rh02_instit
       INNER JOIN rhinstrucao         ON rhpessoal.rh01_instru         = rhinstrucao.rh21_instru
       INNER JOIN rhestcivil          ON rhpessoal.rh01_estciv         = rhestcivil.rh08_estciv
       LEFT  JOIN rhiperegist         ON rhiperegist.rh62_regist       = rhpessoal.rh01_regist
       LEFT  JOIN rhipe               ON rhipe.rh14_sequencia          = rhiperegist.rh62_sequencia
                                     AND rhipe.rh14_instit             = rhpessoalmov.rh02_instit
       LEFT  JOIN rhpeslocaltrab      ON rhpeslocaltrab.rh56_seqpes    = rhpessoalmov.rh02_seqpes
                                     AND rhpeslocaltrab.rh56_princ     = true
       LEFT  JOIN rhlocaltrab         ON rhpeslocaltrab.rh56_localtrab = rhlocaltrab.rh55_codigo
                                     AND rhlocaltrab.rh55_instit       = rhpessoalmov.rh02_instit
       LEFT  JOIN rhpesdoc            ON rhpesdoc.rh16_regist          = rhpessoal.rh01_regist
       LEFT  JOIN rhpespadrao         ON rhpessoalmov.rh02_seqpes      = rhpespadrao.rh03_seqpes
       LEFT  JOIN padroes             ON r02_anousu                    = rh03_anousu
                                     AND r02_mesusu                    = rh03_mesusu
                                     AND r02_regime                    = rh03_regime
                                     AND r02_codigo                    = rh03_padrao
                                     AND r02_instit                    = rh02_instit
       INNER JOIN rhregime            ON rhregime.rh30_codreg          = rhpessoalmov.rh02_codreg
                                     AND rhregime.rh30_instit          = rhpessoalmov.rh02_instit
       LEFT  JOIN rhpesbanco          ON rhpesbanco.rh44_seqpes        = rhpessoalmov.rh02_seqpes
       LEFT  JOIN admissao            ON rhpessoal.rh01_regist         = admissao.h07_regist
       LEFT  JOIN rhfuncao f          ON f.rh37_funcao                 = admissao.h07_cant::integer
                                     AND f.rh37_instit                 = rhpessoalmov.rh02_instit
       LEFT  JOIN flegal              ON flegal.h04_codigo             = admissao.h07_fundam
       LEFT  JOIN concur              ON concur.h06_refer              = admissao.h07_refe
       LEFT  JOIN areas               ON areas.h05_codigo              = admissao.h07_area
       LEFT  JOIN rhraca              ON rhpessoal.rh01_raca           = rhraca.rh18_raca
 ORDER BY cgm.z01_nome;

