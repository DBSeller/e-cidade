
insert into censoetapa
    (select ed266_i_codigo, ed266_c_descr, ed266_c_regular, ed266_c_especial, ed266_c_eja, 2016
       from censoetapa
      where ed266_ano = 2015
        and not exists (select 1 from censoetapa where ed266_ano = 2016)
    );

insert into censoetapamediacaodidaticopedagogica
    (select nextval('censoetapamediacaodidaticopedagogica_ed131_codigo_seq'), ed131_mediacaodidaticopedagogica, ed131_censoetapa, 2016, ed131_regular, ed131_especial, ed131_eja, ed131_profissional
       from censoetapamediacaodidaticopedagogica
      where ed131_ano = 2015
        and not exists (select 1 from censoetapamediacaodidaticopedagogica where ed131_ano = 2016 )
    );

insert into censoregradisc
    (select nextval('censoregradisc_ed272_i_codigo_seq'), ed272_i_censoetapa, ed272_i_censodisciplina, 2016
       from censoregradisc
      where ed272_ano = 2015
        and not exists (select 1 from censoregradisc where ed272_ano = 2016 )
    );

update censoinstsuperior set ed257_c_nome = 'UNIVERSIDADE DO CONTESTADO'                            , ed257_i_censomunic = '4210100', ed257_i_dependencia = 3, ed257_i_tipo = 1, ed257_c_situacao = 'ATIVA' where ed257_i_codigo = 441;
update censoinstsuperior set ed257_c_nome = 'UNIVERSIDADE DO SUL DE SANTA CATARINA'                 , ed257_i_censomunic = '4218707', ed257_i_dependencia = 3, ed257_i_tipo = 1, ed257_c_situacao = 'ATIVA' where ed257_i_codigo = 494;
update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO DE MANDAGUARI UNIMAN'             , ed257_i_censomunic = '4114203', ed257_i_dependencia = 3, ed257_i_tipo = 1, ed257_c_situacao = 'ATIVA' where ed257_i_codigo = 535;
update censoinstsuperior set ed257_c_nome = 'FACULDADE DE FORMACAO DE PROFESSORES DE SERRA TALHADA' , ed257_i_censomunic = '2613909', ed257_i_dependencia = 3, ed257_i_tipo = 1, ed257_c_situacao = 'ATIVA' where ed257_i_codigo = 657;
update censoinstsuperior set ed257_c_nome = 'UNIVERSIDADE DE TAUBATE'                               , ed257_i_censomunic = '3554102', ed257_i_dependencia = 3, ed257_i_tipo = 1, ed257_c_situacao = 'ATIVA' where ed257_i_codigo = 665;
update censoinstsuperior set ed257_c_nome = 'UNIVERSIDADE DO PLANALTO CATARINENSE'                  , ed257_i_censomunic = '4209300', ed257_i_dependencia = 3, ed257_i_tipo = 1, ed257_c_situacao = 'ATIVA' where ed257_i_codigo = 1189;
update censoinstsuperior set ed257_c_nome = 'FACULDADES ADAMANTINENSES INTEGRADAS'                  , ed257_i_censomunic = '3500105', ed257_i_dependencia = 3, ed257_i_tipo = 1, ed257_c_situacao = 'ATIVA' where ed257_i_codigo = 1292;
update censoinstsuperior set ed257_c_nome = 'FACULDADES INTEGRADAS DE SANTA FE DO SUL'              , ed257_i_censomunic = '3546603', ed257_i_dependencia = 3, ed257_i_tipo = 1, ed257_c_situacao = 'ATIVA' where ed257_i_codigo = 1356;
update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO FUNDACAO SANTO ANDRE'             , ed257_i_censomunic = '3547809', ed257_i_dependencia = 3, ed257_i_tipo = 1, ed257_c_situacao = 'ATIVA' where ed257_i_codigo = 2183;
update censoinstsuperior set ed257_c_nome = 'UNIVERSIDADE COMUNITARIA DA REGIAO DE CHAPECO'         , ed257_i_censomunic = '4204202', ed257_i_dependencia = 3, ed257_i_tipo = 1, ed257_c_situacao = 'ATIVA' where ed257_i_codigo = 3151;
update censoinstsuperior set ed257_c_nome = 'UNIVERSIDADE DE RIO VERDE'                             , ed257_i_censomunic = '5218805', ed257_i_dependencia = 3, ed257_i_tipo = 1, ed257_c_situacao = 'ATIVA' where ed257_i_codigo = 3974;
update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO MUNICIPAL DE SAO JOSE'            , ed257_i_censomunic = '4216602', ed257_i_dependencia = 3, ed257_i_tipo = 1, ed257_c_situacao = 'ATIVA' where ed257_i_codigo = 4756;
update censoinstsuperior set ed257_c_nome = 'UNIVERSIDADE ALTO VALE DO RIO DO PEIXE'                , ed257_i_censomunic = '4203006', ed257_i_dependencia = 3, ed257_i_tipo = 1, ed257_c_situacao = 'ATIVA' where ed257_i_codigo = 15032;
update censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS DA SAUDE DE SERRA TALHADA'       , ed257_i_censomunic = '2613909', ed257_i_dependencia = 3, ed257_i_tipo = 1, ed257_c_situacao = 'ATIVA' where ed257_i_codigo = 17775;

select fc_executa_ddl('
insert into censoinstsuperior
values (12899, \'FACULDADE METROPOLITANA DO VALE DO AÇO\'                       , 4, 2, \'3131307\', \'INATIVA\'),
       (10251, \'FACULDADE ORTODOXA\'                                           , 4, 2, \'5104104\', \'ATIVA\'),
       (13728, \'FACULDADE DOS CARAJÁS\'                                        , 4, 2, \'1504208\', \'ATIVA\'),
       (13764, \'FACULDADE DE TECNOLOGIA DE AMPÉRE\'                            , 4, 2, \'4101002\', \'INATIVA\'),
       (14158, \'FACULDADE DE TECNOLOGIA DE NOVO CABRAIS\'                      , 4, 2, \'4313391\', \'ATIVA\'),
       (14718, \'FACULDADE PARANÁ\'                                             , 4, 2, \'4103701\', \'INATIVA\'),
       (15500, \'FACULDADE LUSOCAPIXABA\'                                       , 4, 2, \'3201308\', \'INATIVA\'),
       (15562, \'FACULDADE BATISTA DO CARIRI\'                                  , 4, 2, \'2304202\', \'ATIVA\'),
       (16602, \'FACULDADE DE EDUCAÇÃO ELIÂ\'                                   , 4, 2, \'1507953\', \'INATIVA\'),
       (16782, \'FACULDADE MÁRIO QUINTANA\'                                     , 4, 2, \'4314902\', \'ATIVA\'),
       (16849, \'FACULDADE MODAL\'                                              , 4, 2, \'3106200\', \'INATIVA\'),
       (16918, \'FACULDADE CATÓLICA DE FEIRA DE SANTANA\'                       , 4, 2, \'2910800\', \'INATIVA\'),
       (16948, \'FACULDADE 28 DE AGOSTO DE ENSINO E PESQUISA\'                  , 4, 2, \'3550308\', \'INATIVA\'),
       (17025, \'FACULDADE DE EDUCAÇÃO SUPERIOR DE PARAGOMINAS\'                , 4, 2, \'1505502\', \'INATIVA\'),
       (17091, \'FACULDADE DE NEGÓCIOS DO RECIFE\'                              , 4, 2, \'2611606\', \'INATIVA\'),
       (17115, \'FACULDADE DA UNIÃO DE ENSINO E PESQUISA INTEGRADA\'            , 4, 2, \'2507507\', \'INATIVA\'),
       (17118, \'FACULDADE DO NORTE DE MATO GROSSO\'                            , 4, 2, \'5104104\', \'ATIVA\'),
       (17289, \'FACULDADE DE TEOLOGIA DE CARATINGA URIEL DE ALMEIDA LEITÃO\'   , 4, 2, \'3113404\', \'INATIVA\'),
       (17348, \'FACULDADE DE TECNOLOGIA DOS INCONFIDENTES\'                    , 4, 2, \'3131901\', \'ATIVA\'),
       (17355, \'FACULDADE DE EDUCAÇÃO EM CIÊNCIAS DA SAÚDE\'                   , 4, 2, \'3550308\', \'ATIVA\'),
       (17382, \'FACULDADE IETEC\'                                              , 4, 2, \'3106200\', \'INATIVA\'),
       (17394, \'FACULDADE ALENCARINA DE SOBRAL\'                               , 4, 2, \'2312908\', \'INATIVA\'),
       (17400, \'FACULDADE MENINO DEUS\'                                        , 4, 2, \'4314902\', \'INATIVA\'),
       (17403, \'FACULDADE ARI DE SÁ\'                                          , 4, 2, \'2304400\', \'INATIVA\'),
       (17420, \'FACULDADE CESUMAR DE PONTA GROSSA\'                            , 4, 2, \'4119905\', \'INATIVA\'),
       (17460, \'FACULDADE PROFISSIONAL\'                                       , 4, 2, \'4106902\', \'ATIVA\'),
       (17558, \'FACULDADE SANTO ANDRÉ\'                                        , 4, 2, \'1100304\', \'INATIVA\'),
       (17563, \'FACULDADE COESP\'                                              , 4, 2, \'2507507\', \'INATIVA\'),
       (17565, \'FACULDADE DE CIÊNCIAS HUMANAS,EXATAS E DA SAÚDE DO PIAUÍ\'     , 4, 2, \'2207702\', \'INATIVA\'),
       (17590, \'FACULDADE ISAE BRASIL\'                                        , 4, 2, \'4106902\', \'INATIVA\'),
       (17593, \'FACULDADE DE BOTUCATU\'                                        , 4, 2, \'3507506\', \'INATIVA\'),
       (17598, \'FACULDADE PROF. WLADEMIR DOS SANTOS\'                          , 4, 2, \'3552205\', \'INATIVA\'),
       (17608, \'FACULDADE DE EDUCAÇÃO PAULISTANA\'                             , 4, 2, \'3550308\', \'INATIVA\'),
       (17622, \'FACULDADE TALLES DE MILETO - SEDE DRAGÃO DO MAR\'              , 4, 2, \'2304400\', \'INATIVA\'),
       (17628, \'FACULDADE DO MACIÇO DO BATURITÉ\'                              , 4, 2, \'2302107\', \'ATIVA\'),
       (17662, \'FACULDADE GALILEU\'                                            , 4, 2, \'3507506\', \'ATIVA\'),
       (17670, \'FACULDADE DE QUIXERAMOBIM\'                                    , 4, 2, \'2311405\', \'INATIVA\'),
       (17672, \'INSTITUTO DE DIREITO PÚBLICO DE SÃO PAULO\'                    , 4, 2, \'3550308\', \'INATIVA\'),
       (17674, \'FACULDADE DE EDUCAÇÃO DE SÃO MATEUS\'                          , 4, 2, \'2111508\', \'INATIVA\'),
       (17701, \'FAP-FACULDADE DE PINHEIROS\'                                   , 4, 2, \'3204104\', \'INATIVA\'),
       (17731, \'FACULDADE SESI-SP DE EDUCAÇÃO\'                                , 4, 2, \'3550308\', \'INATIVA\'),
       (17749, \'FACULDADE AMÉRICA\'                                            , 4, 2, \'3201209\', \'INATIVA\'),
       (17763, \'FACULDADE SENAI DE JOÃO PESSOA\'                               , 4, 2, \'2507507\', \'INATIVA\'),
       (17816, \'FACULDADE MAURÍCIO DE NASSAU DE FEIRA DE SANTANA\'             , 4, 2, \'2910800\', \'INATIVA\'),
       (17828, \'FACULDADE DO CENTRO LESTE - CARIACICA\'                        , 4, 2, \'3201308\', \'INATIVA\'),
       (17831, \'FACULDADE DE TECNOLOGIA E NEGÓCIOS DE CATALÃO\'                , 4, 2, \'5205109\', \'INATIVA\'),
       (17850, \'FACULDADE TECNOLÓGICA SANTANNA\'                               , 4, 2, \'3556701\', \'ATIVA\'),
       (17854, \'FACULDADE CAPITAL FEDERAL\'                                    , 4, 2, \'3552809\', \'ATIVA\'),
       (18010, \'FACULDADE ESTÁCIO DE CUIABÁ\'                                  , 4, 2, \'5103403\', \'INATIVA\'),
       (18019, \'FACULDADE DO EDUCADOR\'                                        , 4, 2, \'3550308\', \'INATIVA\'),
       (18023, \'FACULDADE MAURÍCIO DE NASSAU DE PETROLINA\'                    , 4, 2, \'2611101\', \'INATIVA\'),
       (18067, \'CISNE - FACULDADE TECNOLÓGICA DE QUIXADÁ\'                     , 4, 2, \'2311306\', \'INATIVA\'),
       (18075, \'FACULDADE MAURÍCIO DE NASSAU DE JABOATÃO DOS GUARARAPES\'      , 4, 2, \'2607901\', \'INATIVA\'),
       (18114, \'FACULDADE FASIPE MATO GROSSO\'                                 , 4, 2, \'5103403\', \'INATIVA\'),
       (18133, \'FACULDADE UNIDA DE CAMPINAS GOIÂNIA - FACUNICAMPS GOIÂNIA\'    , 4, 2, \'5208707\', \'INATIVA\'),
       (18165, \'FUNDAÇÃO UNIVERSIDADE VIRTUAL DO ESTADO DE SÃO PAULO\'         , 2, 1, \'3550308\', \'INATIVA\'),
       (18257, \'FACULDADE SÄO JOSÉ\'                                           , 4, 2, \'4217204\', \'INATIVA\'),
       (18288, \'FACULDADE LATINO-AMERICANA\'                                   , 4, 2, \'3503901\', \'ATIVA\'),
       (19500, \'FACULDADE DE TECNOLOGIA DE SÃO CARLOS\'                        , 2, 1, \'3548906\', \'ATIVA\'),
       (19501, \'FACULDADE DE TECNOLOGIA SEBRAE\'                               , 2, 1, \'3550308\', \'ATIVA\'),
       (19512, \'INSTITUTO MASTER DE ENSINO PRESIDENTE ANTÔNIO CARLOS\'         , 4, 2, \'3103504\', \'ATIVA\'),
       (19578, \'FACULDADE DE TECNOLOGIA DE COTIA\'                             , 2, 1, \'3513009\', \'ATIVA\'),
       (19588, \'FACULDADE DE EDUCAÇÃO TECNOLÓGICA DO ESTADO DO RIO DE JANEIRO\', 2, 1, \'3301702\', \'ATIVA\'),
       (19739, \'FACULDADE DE TECNOLOGIA DE CAMPINAS\'                          , 2, 1, \'3509502\', \'ATIVA\'),
       (19862, \'FACULDADE DE TECNOLOGIA DE BEBEDOURO\'                         , 2, 1, \'3506102\', \'ATIVA\'),
       (20478, \'FACULDADE DE TECNOLOGIA DE SANTANA DE PARNAÍBA\'               , 2, 1, \'3547304\', \'ATIVA\'),
       (21095, \'ACADEMIA MILITAR DAS AGULHAS NEGRAS\'                          , 3, 1, \'3304201\', \'ATIVA\'),
       (21206, \'ESCOLA DE EDUCAÇÃO FÍSICA DO EXÉRCITO\'                        , 3, 1, \'3304557\', \'ATIVA\');
');