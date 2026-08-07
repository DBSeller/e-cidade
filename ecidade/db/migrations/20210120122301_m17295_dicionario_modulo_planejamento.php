<?php

use Classes\PostgresMigration;

class M17295DicionarioModuloPlanejamento extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL

insert into db_sysmodulo values (85,'planejamento','O planejamento governamental é uma atividade permanente da administração pública, além de se constituir em função essencial de Estado. O processo de planejamento compreende a escolha de políticas públicas capazes de combater os problemas enfrentados pela sociedade em um ambiente no qual os recursos (financeiros, organizacionais, informacionais e tecnológicos) são limitados.','2021-01-19','t');

/**
 * CRIA as ESTRUTURAS
 */
insert into db_sysarquivo values
   (1010701, 'status', 'Status do planejamento', 'pl1', '2021-02-18', 'Status do planejamento', 0, 'f', 'f', 'f', 'f' ),
   (1010702, 'planejamento', 'Armazena os planos de governo', 'pl2', '2021-02-18', 'Planejamento', 0, 'f', 'f', 'f', 'f' ),
   (1010703, 'comissao', 'Comissão do Plano de Governo', 'pl3', '2021-02-18', 'Comissão', 0, 'f', 'f', 'f', 'f' ),
   (1010704, 'arearesultado', 'Area de Resultado do Plano de Governo', 'pl4', '2021-02-18', 'Area de Resultado', 0, 'f', 'f', 'f', 'f' ),
   (1010705, 'objetivoestrategico', 'Objetivo estrategico do plano de governo', 'pl5', '2021-02-18', 'Objetivo Estrategico', 0, 'f', 'f', 'f', 'f' ),
   (1010706, 'objetivoestrategicoprograma', 'Objetivo estrategico da area de resultado', 'pl6', '2021-02-18', 'Objetivo estrategico', 0, 'f', 'f', 'f', 'f' ),
   (1010707, 'fatorcorrecaodespesa', 'Fator de Corereção da Despesa do plano', 'pl7', '2021-02-18', 'Fator de Corereção da Despesa', 0, 'f', 'f', 'f', 'f' ),
   (1010708, 'estimativareceita', 'Estimativa de Receita do Plano de Governo', 'pl8', '2021-02-18', 'Estimativa de Receita', 0, 'f', 'f', 'f', 'f' ),
   (1010709, 'programaestregico', 'Programa Estratégico de um plano de Governo', 'pl9', '2021-02-18', 'Programa Estratégico', 0, 'f', 'f', 'f', 'f' ),
   (1010710, 'valores', 'Valores referente ao Planejamento e seu plano de Governo', 'pl10', '2021-02-18', 'Valores', 0, 'f', 'f', 'f', 'f' ),
   (1010711, 'objetivosprogramaestregico', 'Objetivos Programa Estratégico do Plano de Governo', 'pl11', '2021-02-18', 'Objetivos Programa Estratégico', 0, 'f', 'f', 'f', 'f' ),
   (1010712, 'iniciativaprojativ', 'Iniciativa Projeto Atividade', 'pl12', '2021-02-18', 'Iniciativa Projeto Atividade', 0, 'f', 'f', 'f', 'f' ),
   (1010713, 'origeminiciativa', 'Origem da Iniciativa', 'pl13', '2021-02-18', 'Origem da Iniciativa', 0, 'f', 'f', 'f', 'f' ),
   (1010714, 'periodoacao', 'Periodo de uma ação', 'pl14', '2021-02-18', 'Periodo de uma ação', 0, 'f', 'f', 'f', 'f' ),
   (1010715, 'iniciativaobjetivosprogramaestregico', 'iniciativa dos objetivos programas de trabalhos', 'pl16', '2021-02-18', 'iniciativa dos objetivos programas de trabalhos', 0, 'f', 'f', 'f', 'f' ),
   (1010716, 'regionalizacao', 'Regionalização do Plano', 'pl17', '2021-02-18', 'regionalizacao', 0, 'f', 'f', 'f', 'f' ),
   (1010717, 'abrangencia', 'abrangencia', 'pl18', '2021-02-18', 'abrangencia', 0, 'f', 'f', 'f', 'f' ),
   (1010718, 'abrangenciainiciativaprojativ', 'abrangencia iniciativa projeto atividade', 'pl19', '2021-02-18', 'abrangencia iniciativa projeto atividade', 0, 'f', 'f', 'f', 'f' ),
   (1010719, 'detalhamentoiniciativa', 'detalhamento iniciativa ', 'pl20', '2021-02-18', 'detalhamento iniciativa', 0, 'f', 'f', 'f', 'f' ),
   (1010720, 'metasobjetivoprogramaestregico', 'Metas objetivos do Programa Estratégico do plano de governo', 'pl21', '2021-02-18', 'Metas objetivos do Programa Estratégico', 0, 'f', 'f', 'f', 'f' ),
   (1010721, 'indicadoresprogramaestregico', 'indicadores Programa Estratégico', 'pl22', '2021-02-18', 'indicadores Programa Estratégico', 0, 'f', 'f', 'f', 'f' ),
   (1010722, 'fatorcorrecaoreceita', 'indicadores Programa Estratégico', 'pl24', '2021-02-18', 'fator de correção da receita', 0, 'f', 'f', 'f', 'f' ),
   (1010723, 'iniciativaprojativppasubtitulolocalizador', 'iniciativa projeto atividade da regionalizacao do plano', 'pl25', '2021-02-18', 'iniciativa projeto atividade da regionalizacao', 0, 'f', 'f', 'f', 'f' );

/**
 * VINCULA ESTRUTURAS COM O MODULO
 */
insert into db_sysarqmod values
 (85,1010701),
 (85,1010702),
 (85,1010703),
 (85,1010704),
 (85,1010705),
 (85,1010706),
 (85,1010707),
 (85,1010708),
 (85,1010709),
 (85,1010710),
 (85,1010711),
 (85,1010712),
 (85,1010713),
 (85,1010714),
 (85,1010715),
 (85,1010716),
 (85,1010717),
 (85,1010718),
 (85,1010719),
 (85,1010720),
 (85,1010721),
 (85,1010722),
 (85,1010723);


/**
 * CRIACAO DOS CAMPOS
 */
insert into db_syscampo values
(1012460,'pl1_codigo','int4','Código sequencial','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012461,'pl1_descricao','varchar(255)','Descrição','', 'Descrição',255,'f','t','f',0,'text','Descrição'),
(1012462,'pl2_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012463,'pl2_tipo','varchar(3)','Tipo','', 'Tipo',3,'f','t','f',0,'text','Tipo'),
(1012464,'pl2_codigo_pai','int4','Planejamento Pai','0', 'Planejamento Pai',10,'f','f','f',1,'text','Planejamento Pai'),
(1012465,'pl2_ano_inicial','int4','Ano Inicial','0', 'Ano Inicial',4,'f','f','f',1,'text','Ano Inicial'),
(1012466,'pl2_ano_final','int4','Ano Final','0', 'Ano Final',4,'f','f','f',1,'text','Ano Final'),
(1012467,'pl2_ativo','bool','Ativo','t', 'Ativo',1,'f','f','f',5,'text','Ativo'),
(1012468,'pl2_status','int4','Status','0', 'Status',10,'f','f','f',1,'text','Status'),
(1012469,'pl2_titulo','varchar(255)','Titulo','', 'Titulo',255,'f','t','f',0,'text','Titulo'),
(1012470,'pl2_base_calculo','int4','Base de Cálculo','0', 'Base de Cálculo',10,'f','f','f',1,'text','Base de Cálculo'),
(1012471,'pl2_base_despesa','int4','Base da Despesa','0', 'Base da Despesa',10,'f','f','f',1,'text','Base da Despesa'),
(1012472,'pl2_area_resultado','bool','Área de Resultado','f', 'Área de Resultado',1,'f','f','f',5,'text','Área de Resultado'),
(1012473,'pl2_ementa','text','Ementa','', 'Ementa',1,'t','t','f',0,'text','Ementa'),
(1012474,'pl2_missao','text','Missão','', 'Missão',1,'t','t','f',0,'text','Missão'),
(1012475,'pl2_visao','text','Visão','', 'Visão',1,'t','t','f',0,'text','Visão'),
(1012476,'pl2_valores','text','Valores','', 'Valores',1,'t','t','f',0,'text','Valores'),
(1012477,'pl2_created_at','varchar(30)','Criado em','', 'Criado em',30,'f','t','f',0,'text','Criado em'),
(1012478,'pl2_updated_at','varchar(30)','Alterado em','', 'Alterado em',30,'f','t','f',0,'text','Alterado em'),
(1012479,'pl3_codigo','int4','Código Sequencial','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012480,'pl3_cgm','int4','CGM','0', 'CGM',10,'f','f','f',1,'text','CGM'),
(1012481,'pl3_planejamento','int4','Planejamento','0', 'Planejamento',10,'f','f','f',1,'text','Planejamento'),
(1012482,'pl3_created_at','varchar(30)','Criado em','', 'Criado em',30,'f','t','f',0,'text','Criado em'),
(1012483,'pl3_updated_at','varchar(30)','Alterado em','', 'Alterado em',30,'f','t','f',0,'text','Alterado em'),
(1012484,'pl4_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012485,'pl4_planejamento','int4','Planejamento','0', 'Planejamento',10,'f','f','f',1,'text','Planejamento'),
(1012486,'pl4_titulo','varchar(255)','Titulo','', 'Titulo',255,'f','t','f',0,'text','Titulo'),
(1012487,'pl4_contextualizacao','text','Contextualização','', 'Contextualização',1,'t','t','f',0,'text','Contextualização'),
(1012488,'pl4_created_at','varchar(30)','Criado em','', 'Criado em',30,'f','t','f',0,'text','Criado em'),
(1012489,'pl4_updated_at','varchar(30)','Alterado em','', 'Alterado em',30,'f','t','f',0,'text','Alterado em'),
(1012490,'pl5_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012491,'pl5_arearesultado','int4','Area de Resultado','0', 'Area de Resultado',10,'f','f','f',1,'text','Area de Resultado'),
(1012492,'pl5_titulo','varchar(255)','Titulo','', 'Titulo',255,'f','t','f',0,'text','Titulo'),
(1012493,'pl5_contextualizacao','text','Contextualização','', 'Contextualização',1,'t','t','f',0,'text','Contextualização'),
(1012494,'pl5_fonte','text','Fonte','', 'Fonte',1,'t','t','f',0,'text','Fonte'),
(1012495,'pl5_created_at','varchar(30)','Criado em','', 'Criado em',30,'f','t','f',0,'text','Criado em'),
(1012496,'pl5_updated_at','varchar(30)','Alterado em','', 'Alterado em',30,'f','t','f',0,'text','Alterado em'),
(1012497,'pl6_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012498,'pl6_objetivoestrategico','int4','Objetivo Estratégico','0', 'Objetivo Estratégico',10,'f','f','f',1,'text','Objetivo Estratégico'),
(1012499,'pl6_programaestregico','int4','Programa Estratégico','0', 'Programa Estratégico',10,'f','f','f',1,'text','Programa Estratégico'),
(1012500,'pl6_created_at','varchar(30)','Criado em','', 'Criado em',30,'f','t','f',0,'text','Criado em'),
(1012501,'pl6_updated_at','varchar(30)','Alterado em','', 'Alterado em',30,'f','t','f',0,'text','Alterado em'),
(1012502,'pl7_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012503,'pl7_planejamento','int4','Planejamento','0', 'Planejamento',10,'f','f','f',1,'text','Planejamento'),
(1012504,'pl7_orcelemento','int4','Elemento','0', 'Elemento',10,'f','f','f',1,'text','Elemento'),
(1012505,'pl7_anoorcamento','int4','Ano do Orçamento','0', 'Ano do Orçamento',4,'f','f','f',1,'text','Ano do Orçamento'),
(1012506,'pl7_exercicio','int4','Exercicio','0', 'Exercicio',4,'f','f','f',1,'text','Exercicio'),
(1012507,'pl7_percentual','float4','Percentual','0', 'Percentual',10,'f','f','f',4,'text','Percentual'),
(1012508,'pl8_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012509,'pl8_planejamento','int4','Planejamento','0', 'Planejamento',10,'f','f','f',1,'text','Planejamento'),
(1012510,'pl8_ano','int4','Ano','0', 'Ano',4,'f','f','f',1,'text','Ano'),
(1012511,'pl8_orcfontes','int4','Fonte Orçamento','0', 'Fonte Orçamento',10,'f','f','f',1,'text','Fonte Orçamento'),
(1012512,'pl8_recurso','int4','Recurso','0', 'Recurso',10,'f','f','f',1,'text','Recurso'),
(1012513,'pl8_instituicao','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição'),
(1012514,'pl8_concarpeculiar','varchar(10)','Característica Peculiar','', 'Característica Peculiar',10,'f','t','f',0,'text','Característica Peculiar'),
(1012515,'pl8_orcorgao','int4','Orgão','0', 'Orgão',10,'f','f','f',1,'text','Orgão'),
(1012516,'pl8_orcunidade','int4','Unidade','0', 'Unidade',10,'f','f','f',1,'text','Unidade'),
(1012517,'pl8_esferaorcamentaria','int4','Esfera Orçamentária','0', 'Esfera Orçamentária',10,'f','f','f',1,'text','Esfera Orçamentária'),
(1012518,'pl8_inclusaomanual','bool','Inclusão Manual','f', 'Inclusão Manual',1,'f','f','f',5,'text','Inclusão Manual'),
(1012519,'pl9_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012520,'pl9_planejamento','int4','Planejamento','0', 'Planejamento',10,'f','f','f',1,'text','Planejamento'),
(1012521,'pl9_orcprograma','int4','Programa','0', 'Programa',10,'f','f','f',1,'text','Programa'),
(1012522,'pl9_anoorcamento','int4','Ano do Orçamento','0', 'Ano do Orçamento',10,'f','f','f',1,'text','Ano do Orçamento'),
(1012523,'pl10_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012524,'pl10_origem','varchar(255)','Origem','', 'Origem',255,'f','t','f',0,'text','Origem'),
(1012525,'pl10_chave','int4','Chave','0', 'Chave',10,'f','f','f',1,'text','Chave'),
(1012526,'pl10_ano','int4','Ano','0', 'Ano',4,'f','f','f',1,'text','Ano'),
(1012527,'pl10_valor','float4','Valor','0', 'Valor',10,'f','f','f',4,'text','Valor'),
(1012528,'pl10_editadomanual','bool','Edição Manual','f', 'Edição Manual',1,'f','f','f',5,'text','Edição Manual'),
(1012529,'pl11_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012530,'pl11_programaestregico','int4','Programa Estratégico','0', 'Programa Estratégico',10,'f','f','f',1,'text','Programa Estratégico'),
(1012531,'pl11_numero','int4','Número','0', 'Número',10,'f','f','f',1,'text','Número'),
(1012532,'pl11_descricao','text','Descrição','', 'Descrição',1,'f','t','f',0,'text','Descrição'),
(1012533,'pl12_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012534,'pl12_orcprojativ','int4','Atividade','0', 'Atividade',10,'f','f','f',1,'text','Atividade'),
(1012535,'pl12_anoorcamento','int4','Ano do Orçamento','0', 'Ano do Orçamento',4,'f','f','f',1,'text','Ano do Orçamento'),
(1012536,'pl12_programaestregico','int4','Programa Estratégico','0', 'Programa Estratégico',10,'f','f','f',1,'text','Programa Estratégico'),
(1012537,'pl12_origeminiciativa','int4','Origem da Iniciativa','0', 'Origem da Iniciativa',10,'f','f','f',1,'text','Origem da Iniciativa'),
(1012538,'pl12_periodoacao','int4','Periodo de uma ação','0', 'Periodo de uma ação',10,'t','f','f',1,'text','Periodo de uma ação'),
(1012539,'pl13_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012540,'pl13_descricao','varchar(255)','Descrição','', 'Descrição',255,'f','t','f',0,'text','Descrição'),
(1012541,'pl14_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012542,'pl14_descricao','varchar(255)','Descrição','', 'Descrição',255,'f','t','f',0,'text','Descrição'),
(1012543,'pl16_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012544,'pl16_iniciativaprojativ','int4','Iniciativa Projeto Atividade','0', 'Iniciativa Projeto Atividade',10,'f','f','f',1,'text','Iniciativa Projeto Atividade'),
(1012545,'pl16_objetivosprogramaestregico','int4','Objetivos Programa Estratégico','0', 'Objetivos Programa Estratégico',10,'f','f','f',1,'text','Objetivos Programa Estratégico'),
(1012546,'pl17_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012547,'pl17_descricao','varchar(255)','Descrição','', 'Descrição',255,'f','t','f',0,'text','Descrição'),
(1012548,'pl18_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012549,'pl18_descricao','varchar(255)','Descrição','', 'Descrição',255,'f','t','f',0,'text','Descrição'),
(1012550,'pl19_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012551,'pl19_iniciativaprojativ','int4','Iniciativa Projeto Atividade','0', 'Iniciativa Projeto Atividade',10,'f','f','f',1,'text','Iniciativa Projeto Atividade'),
(1012552,'pl19_abrangencia','int4','Abrangência','0', 'Abrangência',10,'f','f','f',1,'text','Abrangência'),
(1012553,'pl20_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012554,'pl20_anoorcamento','int4','Ano do Orçamento','0', 'Ano do Orçamento',4,'f','f','f',1,'text','Ano do Orçamento'),
(1012555,'pl20_iniciativaprojativ','int4','Iniciativa Projeto Atividade','0', 'Iniciativa Projeto Atividade',10,'f','f','f',1,'text','Iniciativa Projeto Atividade'),
(1012556,'pl20_instituicao','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição'),
(1012557,'pl20_orcorgao','int4','Orgão','0', 'Orgão',10,'f','f','f',1,'text','Orgão'),
(1012558,'pl20_orcunidade','int4','Unidade','0', 'Unidade',10,'f','f','f',1,'text','Unidade'),
(1012559,'pl20_orcfuncao','int4','Função','0', 'Função',10,'f','f','f',1,'text','Função'),
(1012560,'pl20_orcsubfuncao','int4','Subfunção','0', 'Subfunção',10,'f','f','f',1,'text','Subfunção'),
(1012561,'pl20_orcelemento','int4','Elemento','0', 'Elemento',10,'f','f','f',1,'text','Elemento'),
(1012562,'pl20_recurso','int4','Recurso','0', 'Recurso',10,'f','f','f',1,'text','Recurso'),
(1012563,'pl20_localizadorgastos','int4','Localizador de Gastos','0', 'Localizador de Gastos',10,'f','f','f',1,'text','Localizador de Gastos'),
(1012564,'pl20_concarpeculiar','varchar(3)','Característica Peculiar','', 'Característica Peculiar',3,'f','t','f',0,'text','Característica Peculiar'),
(1012565,'pl20_esferaorcamentaria','int4','Esfera Orçamentária','0', 'Esfera Orçamentária',10,'f','f','f',1,'text','Esfera Orçamentária'),
(1012566,'pl21_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012567,'pl21_objetivosprogramaestregico','int4','Objetivos Programa Estratégico','0', 'Objetivos Programa Estratégico',10,'f','f','f',1,'text','Objetivos Programa Estratégico'),
(1012568,'pl21_texto','text','Texto','', 'Texto',1,'f','t','f',0,'text','Texto'),
(1012569,'pl22_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012570,'pl22_programaestregico','int4','Programa Estratégico','0', 'Programa Estratégico',10,'f','f','f',1,'text','Programa Estratégico'),
(1012571,'pl22_orcindica','int4','Indicador','0', 'Indicador',10,'f','f','f',1,'text','Indicador'),
(1012572,'pl22_ano','int4','Ano','0', 'Ano',4,'f','f','f',1,'text','Ano'),
(1012573,'pl22_indice','float4','Índice','0', 'Índice',10,'f','f','f',4,'text','Índice'),
(1012574,'pl24_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012575,'pl24_planejamento','int4','Planejamento','0', 'Planejamento',10,'f','f','f',1,'text','Planejamento'),
(1012576,'pl24_orcfontes','int4','Fonte Orçamento','0', 'Fonte Orçamento',10,'f','f','f',1,'text','Fonte Orçamento'),
(1012577,'pl24_anoorcamento','int4','Ano do Orçamento','0', 'Ano do Orçamento',4,'f','f','f',1,'text','Ano do Orçamento'),
(1012578,'pl24_exercicio','int4','Exercicio','0', 'Exercicio',4,'f','f','f',1,'text','Exercicio'),
(1012579,'pl24_percentual','float4','Percentual','0', 'Percentual',10,'f','f','f',4,'text','Percentual'),
(1012580,'pl25_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
(1012581,'pl25_iniciativaprojativ','int4','Iniciativa Projeto Atividade','0', 'Iniciativa Projeto Atividade',10,'f','f','f',1,'text','Iniciativa Projeto Atividade'),
(1012582,'pl25_ppasubtitulolocalizadorgasto','int4','Regionalização','0', 'Regionalização',10,'f','f','f',1,'text','Regionalização'),
(1012583,'created_at','varchar(30)','Criado em','', 'Criado em',30,'f','t','f',0,'text','Criado em'),
(1012584,'updated_at','varchar(10)','Alterado em','', 'Alterado em',10,'f','t','f',0,'text','Alterado em');

/**
 * VINCULA as ESTRUTURAS COM OS CAMPOS
 */


insert into db_sysarqcamp values(1010701,1012460,1,0);
insert into db_sysarqcamp values(1010701,1012461,2,0);

insert into db_sysarqcamp values(1010702,1012462,1,0);
insert into db_sysarqcamp values(1010702,1012463,2,0);
insert into db_sysarqcamp values(1010702,1012464,3,0);
insert into db_sysarqcamp values(1010702,1012465,4,0);
insert into db_sysarqcamp values(1010702,1012466,5,0);
insert into db_sysarqcamp values(1010702,1012467,6,0);
insert into db_sysarqcamp values(1010702,1012468,7,0);
insert into db_sysarqcamp values(1010702,1012469,8,0);
insert into db_sysarqcamp values(1010702,1012470,9,0);
insert into db_sysarqcamp values(1010702,1012471,10,0);
insert into db_sysarqcamp values(1010702,1012472,11,0);
insert into db_sysarqcamp values(1010702,1012473,12,0);
insert into db_sysarqcamp values(1010702,1012474,13,0);
insert into db_sysarqcamp values(1010702,1012475,14,0);
insert into db_sysarqcamp values(1010702,1012476,15,0);
insert into db_sysarqcamp values(1010702,1012477,16,0);
insert into db_sysarqcamp values(1010702,1012478,17,0);

insert into db_sysarqcamp values(1010703,1012479,1,0);
insert into db_sysarqcamp values(1010703,1012480,2,0);
insert into db_sysarqcamp values(1010703,1012481,3,0);
insert into db_sysarqcamp values(1010703,1012482,4,0);
insert into db_sysarqcamp values(1010703,1012483,5,0);

insert into db_sysarqcamp values(1010704,1012484,1,0);
insert into db_sysarqcamp values(1010704,1012485,2,0);
insert into db_sysarqcamp values(1010704,1012486,3,0);
insert into db_sysarqcamp values(1010704,1012487,4,0);
insert into db_sysarqcamp values(1010704,1012488,5,0);
insert into db_sysarqcamp values(1010704,1012489,6,0);

insert into db_sysarqcamp values(1010705,1012490,1,0);
insert into db_sysarqcamp values(1010705,1012491,2,0);
insert into db_sysarqcamp values(1010705,1012492,3,0);
insert into db_sysarqcamp values(1010705,1012493,4,0);
insert into db_sysarqcamp values(1010705,1012494,5,0);
insert into db_sysarqcamp values(1010705,1012495,6,0);
insert into db_sysarqcamp values(1010705,1012496,7,0);

insert into db_sysarqcamp values(1010706,1012497,1,0);
insert into db_sysarqcamp values(1010706,1012498,2,0);
insert into db_sysarqcamp values(1010706,1012499,3,0);
insert into db_sysarqcamp values(1010706,1012500,4,0);
insert into db_sysarqcamp values(1010706,1012501,5,0);

insert into db_sysarqcamp values(1010707,1012502,1,0);
insert into db_sysarqcamp values(1010707,1012503,2,0);
insert into db_sysarqcamp values(1010707,1012504,3,0);
insert into db_sysarqcamp values(1010707,1012505,4,0);
insert into db_sysarqcamp values(1010707,1012506,5,0);
insert into db_sysarqcamp values(1010707,1012507,6,0);
insert into db_sysarqcamp values(1010707,1012583,7,0);
insert into db_sysarqcamp values(1010707,1012584,8,0);

insert into db_sysarqcamp values(1010708,1012508,1,0);
insert into db_sysarqcamp values(1010708,1012509,2,0);
insert into db_sysarqcamp values(1010708,1012510,3,0);
insert into db_sysarqcamp values(1010708,1012511,4,0);
insert into db_sysarqcamp values(1010708,1012512,5,0);
insert into db_sysarqcamp values(1010708,1012513,6,0);
insert into db_sysarqcamp values(1010708,1012514,7,0);
insert into db_sysarqcamp values(1010708,1012515,8,0);
insert into db_sysarqcamp values(1010708,1012516,9,0);
insert into db_sysarqcamp values(1010708,1012517,10,0);
insert into db_sysarqcamp values(1010708,1012518,11,0);
insert into db_sysarqcamp values(1010708,1012583,12,0);
insert into db_sysarqcamp values(1010708,1012584,13,0);

insert into db_sysarqcamp values(1010709,1012519,1,0);
insert into db_sysarqcamp values(1010709,1012520,2,0);
insert into db_sysarqcamp values(1010709,1012521,3,0);
insert into db_sysarqcamp values(1010709,1012522,4,0);
insert into db_sysarqcamp values(1010709,1012583,5,0);
insert into db_sysarqcamp values(1010709,1012584,6,0);

insert into db_sysarqcamp values(1010710,1012523,1,0);
insert into db_sysarqcamp values(1010710,1012524,2,0);
insert into db_sysarqcamp values(1010710,1012525,3,0);
insert into db_sysarqcamp values(1010710,1012526,4,0);
insert into db_sysarqcamp values(1010710,1012527,5,0);
insert into db_sysarqcamp values(1010710,1012528,6,0);
insert into db_sysarqcamp values(1010710,1012583,7,0);
insert into db_sysarqcamp values(1010710,1012584,8,0);

insert into db_sysarqcamp values(1010711,1012529,1,0);
insert into db_sysarqcamp values(1010711,1012530,2,0);
insert into db_sysarqcamp values(1010711,1012531,3,0);
insert into db_sysarqcamp values(1010711,1012532,4,0);
insert into db_sysarqcamp values(1010711,1012583,5,0);
insert into db_sysarqcamp values(1010711,1012584,6,0);

insert into db_sysarqcamp values(1010712,1012533,1,0);
insert into db_sysarqcamp values(1010712,1012534,2,0);
insert into db_sysarqcamp values(1010712,1012535,3,0);
insert into db_sysarqcamp values(1010712,1012536,4,0);
insert into db_sysarqcamp values(1010712,1012537,5,0);
insert into db_sysarqcamp values(1010712,1012538,6,0);
insert into db_sysarqcamp values(1010712,1012583,7,0);
insert into db_sysarqcamp values(1010712,1012584,8,0);

insert into db_sysarqcamp values(1010713,1012539,1,0);
insert into db_sysarqcamp values(1010713,1012540,2,0);
insert into db_sysarqcamp values(1010713,1012583,3,0);
insert into db_sysarqcamp values(1010713,1012584,4,0);

insert into db_sysarqcamp values(1010714,1012541,1,0);
insert into db_sysarqcamp values(1010714,1012542,2,0);
insert into db_sysarqcamp values(1010714,1012583,3,0);
insert into db_sysarqcamp values(1010714,1012584,4,0);

insert into db_sysarqcamp values(1010715,1012543,1,0);
insert into db_sysarqcamp values(1010715,1012544,2,0);
insert into db_sysarqcamp values(1010715,1012545,3,0);
insert into db_sysarqcamp values(1010715,1012583,4,0);
insert into db_sysarqcamp values(1010715,1012584,5,0);

insert into db_sysarqcamp values(1010716,1012546,1,0);
insert into db_sysarqcamp values(1010716,1012547,2,0);
insert into db_sysarqcamp values(1010716,1012583,3,0);
insert into db_sysarqcamp values(1010716,1012584,4,0);

insert into db_sysarqcamp values(1010717,1012548,1,0);
insert into db_sysarqcamp values(1010717,1012549,2,0);
insert into db_sysarqcamp values(1010717,1012583,3,0);
insert into db_sysarqcamp values(1010717,1012584,4,0);

insert into db_sysarqcamp values(1010718,1012550,1,0);
insert into db_sysarqcamp values(1010718,1012551,2,0);
insert into db_sysarqcamp values(1010718,1012552,3,0);
insert into db_sysarqcamp values(1010718,1012583,4,0);
insert into db_sysarqcamp values(1010718,1012584,5,0);

insert into db_sysarqcamp values(1010719,1012553,1,0);
insert into db_sysarqcamp values(1010719,1012554,2,0);
insert into db_sysarqcamp values(1010719,1012555,3,0);
insert into db_sysarqcamp values(1010719,1012556,4,0);
insert into db_sysarqcamp values(1010719,1012557,5,0);
insert into db_sysarqcamp values(1010719,1012558,6,0);
insert into db_sysarqcamp values(1010719,1012559,7,0);
insert into db_sysarqcamp values(1010719,1012560,8,0);
insert into db_sysarqcamp values(1010719,1012561,9,0);
insert into db_sysarqcamp values(1010719,1012562,10,0);
insert into db_sysarqcamp values(1010719,1012563,11,0);
insert into db_sysarqcamp values(1010719,1012564,12,0);
insert into db_sysarqcamp values(1010719,1012565,13,0);
insert into db_sysarqcamp values(1010719,1012583,14,0);
insert into db_sysarqcamp values(1010719,1012584,15,0);

insert into db_sysarqcamp values(1010720,1012566,1,0);
insert into db_sysarqcamp values(1010720,1012567,2,0);
insert into db_sysarqcamp values(1010720,1012568,3,0);
insert into db_sysarqcamp values(1010720,1012583,4,0);
insert into db_sysarqcamp values(1010720,1012584,5,0);

insert into db_sysarqcamp values(1010721,1012569,1,0);
insert into db_sysarqcamp values(1010721,1012570,2,0);
insert into db_sysarqcamp values(1010721,1012571,3,0);
insert into db_sysarqcamp values(1010721,1012572,4,0);
insert into db_sysarqcamp values(1010721,1012573,5,0);
insert into db_sysarqcamp values(1010721,1012583,6,0);
insert into db_sysarqcamp values(1010721,1012584,7,0);


insert into db_sysarqcamp values(1010722,1012574,1,0);
insert into db_sysarqcamp values(1010722,1012575,2,0);
insert into db_sysarqcamp values(1010722,1012576,3,0);
insert into db_sysarqcamp values(1010722,1012577,4,0);
insert into db_sysarqcamp values(1010722,1012578,5,0);
insert into db_sysarqcamp values(1010722,1012579,6,0);
insert into db_sysarqcamp values(1010722,1012583,7,0);
insert into db_sysarqcamp values(1010722,1012584,8,0);

insert into db_sysarqcamp values(1010723,1012580,1,0);
insert into db_sysarqcamp values(1010723,1012581,2,0);
insert into db_sysarqcamp values(1010723,1012582,3,0);
insert into db_sysarqcamp values(1010723,1012583,4,0);
insert into db_sysarqcamp values(1010723,1012584,5,0);


/**
  CRIA AS PK
 */
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010701,1012460,1,1012460);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010702,1012462,1,1012462);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010703,1012479,1,1012479);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010704,1012484,1,1012484);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010705,1012490,1,1012490);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010706,1012497,1,1012497);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010707,1012502,1,1012502);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010709,1012519,1,1012519);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010708,1012508,1,1012508);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010710,1012523,1,1012523);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010711,1012529,1,1012529);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010720,1012566,1,1012566);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010714,1012541,1,1012541);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010713,1012539,1,1012539);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010717,1012548,1,1012548);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010712,1012533,1,1012533);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010722,1012574,1,1012574);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010721,1012569,1,1012569);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010715,1012543,1,1012543);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010716,1012546,1,1012546);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010718,1012550,1,1012550);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010723,1012580,1,1012580);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010719,1012553,1,1012553);

/**
 * CRIA FK
 */


insert into db_sysforkey values(1010703,1012480,1,42,0);
insert into db_sysforkey values(1010703,1012481,1,1010702,0);
insert into db_sysforkey values(1010702,1012464,1,1010702,0);
insert into db_sysforkey values(1010702,1012468,1,1010701,0);
insert into db_sysforkey values(1010704,1012485,1,1010702,0);
insert into db_sysforkey values(1010705,1012491,1,1010704,0);
insert into db_sysforkey values(1010707,1012503,1,1010702,0);
insert into db_sysforkey values(1010707,1012504,1,753,0);
insert into db_sysforkey values(1010707,1012505,2,753,0);
insert into db_sysforkey values(1010709,1012520,1,1010702,0);
insert into db_sysforkey values(1010706,1012498,1,1010705,0);
insert into db_sysforkey values(1010706,1012499,1,1010709,0);
insert into db_sysforkey values(1010709,1012522,1,752,0);
insert into db_sysforkey values(1010709,1012521,2,752,0);
insert into db_sysforkey values(1010708,1012509,1,1010702,0);
insert into db_sysforkey values(1010708,1012513,1,83,0);
insert into db_sysforkey values(1010708,1012514,1,1862,0);
insert into db_sysforkey values(1010708,1012511,1,755,0);
insert into db_sysforkey values(1010708,1012512,1,749,0);
insert into db_sysforkey values(1010708,1012510,2,755,0);
insert into db_sysforkey values(1010708,1012510,1,757,0);
insert into db_sysforkey values(1010708,1012515,2,757,0);
insert into db_sysforkey values(1010708,1012516,3,757,0);
insert into db_sysforkey values(1010711,1012530,1,1010709,0);
insert into db_sysforkey values(1010720,1012567,1,1010711,0);
insert into db_sysforkey values(1010710,1012525,1,1010711,0);
insert into db_sysforkey values(1010710,1012525,1,1010712,0);
insert into db_sysforkey values(1010712,1012536,1,1010709,0);
insert into db_sysforkey values(1010712,1012537,1,1010713,0);
insert into db_sysforkey values(1010712,1012538,1,1010714,0);
insert into db_sysforkey values(1010722,1012575,1,1010702,0);
insert into db_sysforkey values(1010719,1012555,1,1010712,0);
insert into db_sysforkey values(1010719,1012554,1,756,0);
insert into db_sysforkey values(1010719,1012557,2,756,0);
insert into db_sysforkey values(1010719,1012554,1,757,0);
insert into db_sysforkey values(1010719,1012557,2,757,0);
insert into db_sysforkey values(1010719,1012558,3,757,0);
insert into db_sysforkey values(1010719,1012561,1,753,0);
insert into db_sysforkey values(1010719,1012554,2,753,0);
insert into db_sysforkey values(1010719,1012562,1,749,0);
insert into db_sysforkey values(1010719,1012564,1,1862,0);
insert into db_sysforkey values(1010719,1012559,1,750,0);
insert into db_sysforkey values(1010719,1012556,1,83,0);
insert into db_sysforkey values(1010719,1012563,1,2395,0);
insert into db_sysforkey values(1010719,1012560,1,751,0);
insert into db_sysforkey values(1010722,1012576,1,755,0);
insert into db_sysforkey values(1010722,1012577,2,755,0);
insert into db_sysforkey values(1010721,1012571,1,1125,0);
insert into db_sysforkey values(1010721,1012570,1,1010709,0);
insert into db_sysforkey values(1010715,1012544,1,1010712,0);
insert into db_sysforkey values(1010715,1012545,1,1010720,0);
insert into db_sysforkey values(1010718,1012551,1,1010712,0);
insert into db_sysforkey values(1010718,1012552,1,1010717,0);
insert into db_sysforkey values(1010712,1012535,1,754,0);
insert into db_sysforkey values(1010712,1012534,2,754,0);
insert into db_sysforkey values(1010710,1012525,1,1010719,0);
insert into db_sysforkey values(1010723,1012581,1,1010712,0);
insert into db_sysforkey values(1010723,1012582,1,2395,0);


-- CRIA OS INDICES


insert into db_sysindices values(1008638,'planejamento_pl2_codigo_pai_in',1010702,'0');
insert into db_sysindices values(1008639,'planejamento_pl2_status_in',1010702,'0');
insert into db_sysindices values(1008640,'comissao_pl3_cgm_in',1010703,'0');
insert into db_sysindices values(1008641,'comissao_pl3_planejamento_in',1010703,'0');
insert into db_sysindices values(1008642,'arearesultado_pl4_planejamento_in',1010704,'0');
insert into db_sysindices values(1008643,'objetivoestrategico_pl5_arearesultado_in',1010705,'0');
insert into db_sysindices values(1008644,'fatorcorrecaodespesa_pl7_planejamento_in',1010707,'0');
insert into db_sysindices values(1008645,'fatorcorrecaodespesa_pl7_orcelemento_in',1010707,'0');
insert into db_sysindices values(1008646,'programaestregico_pl9_planejamento_in',1010709,'0');
insert into db_sysindices values(1008647,'objetivoestrategicoprograma_pl6_objetivoestrategico_in',1010706,'0');
insert into db_sysindices values(1008648,'objetivoestrategicoprograma_pl6_programaestregico_in',1010706,'0');
insert into db_sysindices values(1008649,'programaestregico_pl9_orcprograma_in',1010709,'0');
insert into db_sysindices values(1008650,'estimativareceita_pl8_planejamento_in',1010708,'0');
insert into db_sysindices values(1008651,'estimativareceita_pl8_recurso_in',1010708,'0');
insert into db_sysindices values(1008652,'estimativareceita_pl8_instituicao_in',1010708,'0');
insert into db_sysindices values(1008653,'estimativareceita_pl8_concarpeculiar_in',1010708,'0');
insert into db_sysindices values(1008654,'estimativareceita_pl8_orcfontes_in',1010708,'0');
insert into db_sysindices values(1008655,'estimativareceita_pl8_ano_in',1010708,'0');
insert into db_sysindices values(1008656,'valores_pl10_origem_in',1010710,'0');
insert into db_sysindices values(1008657,'objetivosprogramaestregico_pl11_programaestregico_in',1010711,'0');
insert into db_sysindices values(1008658,'valores_pl10_chave_in',1010710,'0');
insert into db_sysindices values(1008659,'metasobjetivoprogramaestregico_pl21_objetivosprogramaestregico_in',1010720,'0');
insert into db_sysindices values(1008660,'iniciativaprojativ_pl12_programaestregico_in',1010712,'0');
insert into db_sysindices values(1008661,'iniciativaprojativ_pl12_origeminiciativa_in',1010712,'0');
insert into db_sysindices values(1008662,'iniciativaprojativ_pl12_periodoacao_in',1010712,'0');
insert into db_sysindices values(1008663,'fatorcorrecaoreceita_pl24_planejamento_in',1010722,'0');
insert into db_sysindices values(1008664,'fatorcorrecaoreceita_pl24_orcfontes_in',1010722,'0');
insert into db_sysindices values(1008665,'indicadoresprogramaestregico_pl22_programaestregico_in',1010721,'0');
insert into db_sysindices values(1008666,'indicadoresprogramaestregico_pl22_orcindica_in',1010721,'0');
insert into db_sysindices values(1008667,'iniciativaobjetivosprogramaestregico_pl16_iniciativaprojativ_in',1010715,'0');
insert into db_sysindices values(1008668,'iniciativaobjetivosprogramaestregico_pl16_objetivosprogramaestregico_in',1010715,'0');
insert into db_sysindices values(1008669,'abrangenciainiciativaprojativ_pl19_iniciativaprojativ_in',1010718,'0');
insert into db_sysindices values(1008670,'abrangenciainiciativaprojativ_pl19_abrangencia_in',1010718,'0');
insert into db_sysindices values(1008671,'iniciativaprojativ_pl12_orcprojativ_in',1010712,'0');
insert into db_sysindices values(1008674,'detalhamentoiniciativa_pl20_iniciativaprojativ_in',1010719,'0');
insert into db_sysindices values(1008675,'detalhamentoiniciativa_pl20_orcorgao_in',1010719,'0');
insert into db_sysindices values(1008676,'detalhamentoiniciativa_pl20_orcunidade_in',1010719,'0');
insert into db_sysindices values(1008677,'detalhamentoiniciativa_pl20_orcelemento_in',1010719,'0');
insert into db_sysindices values(1008678,'detalhamentoiniciativa_pl20_recurso_in',1010719,'0');
insert into db_sysindices values(1008679,'detalhamentoiniciativa_pl20_concarpeculiar_in',1010719,'0');
insert into db_sysindices values(1008680,'detalhamentoiniciativa_pl20_orcfuncao_in',1010719,'0');
insert into db_sysindices values(1008681,'detalhamentoiniciativa_pl20_instituicao_in',1010719,'0');
insert into db_sysindices values(1008682,'detalhamentoiniciativa_pl20_localizadorgastos_in',1010719,'0');
insert into db_sysindices values(1008683,'detalhamentoiniciativa_pl20_orcsubfuncao_in',1010719,'0');
insert into db_sysindices values(1008672,'iniciativaprojativppasubtitulolocalizador_pl25_iniciativaprojativ_in',1010723,'0');
insert into db_sysindices values(1008673,'iniciativaprojativppasubtitulolocalizador_pl25_ppasubtitulolocalizadorgasto_in',1010723,'0');

-- VINCULA OS INDICES
insert into db_syscadind values(1008638,1012464,1);
insert into db_syscadind values(1008639,1012468,1);
insert into db_syscadind values(1008640,1012480,1);
insert into db_syscadind values(1008641,1012481,1);
insert into db_syscadind values(1008642,1012485,1);
insert into db_syscadind values(1008643,1012491,1);
insert into db_syscadind values(1008644,1012503,1);
insert into db_syscadind values(1008645,1012504,1);
insert into db_syscadind values(1008646,1012520,1);
insert into db_syscadind values(1008647,1012498,1);
insert into db_syscadind values(1008648,1012499,1);
insert into db_syscadind values(1008649,1012521,1);
insert into db_syscadind values(1008650,1012509,1);
insert into db_syscadind values(1008651,1012512,1);
insert into db_syscadind values(1008652,1012513,1);
insert into db_syscadind values(1008653,1012514,1);
insert into db_syscadind values(1008654,1012511,1);
insert into db_syscadind values(1008655,1012510,1);
insert into db_syscadind values(1008655,1012515,2);
insert into db_syscadind values(1008655,1012516,3);
insert into db_syscadind values(1008656,1012524,1);
insert into db_syscadind values(1008657,1012530,1);
insert into db_syscadind values(1008658,1012525,1);
insert into db_syscadind values(1008659,1012567,1);
insert into db_syscadind values(1008660,1012536,1);
insert into db_syscadind values(1008661,1012537,1);
insert into db_syscadind values(1008662,1012538,1);
insert into db_syscadind values(1008663,1012575,1);
insert into db_syscadind values(1008664,1012576,1);
insert into db_syscadind values(1008664,1012577,2);
insert into db_syscadind values(1008665,1012570,1);
insert into db_syscadind values(1008666,1012571,1);
insert into db_syscadind values(1008667,1012544,1);
insert into db_syscadind values(1008668,1012545,1);
insert into db_syscadind values(1008669,1012551,1);
insert into db_syscadind values(1008670,1012552,1);
insert into db_syscadind values(1008671,1012534,1);
insert into db_syscadind values(1008671,1012535,2);
insert into db_syscadind values(1008674,1012555,1);
insert into db_syscadind values(1008675,1012554,1);
insert into db_syscadind values(1008675,1012557,2);
insert into db_syscadind values(1008676,1012557,1);
insert into db_syscadind values(1008676,1012558,2);
insert into db_syscadind values(1008676,1012554,3);
insert into db_syscadind values(1008677,1012561,1);
insert into db_syscadind values(1008677,1012554,2);
insert into db_syscadind values(1008678,1012562,1);
insert into db_syscadind values(1008679,1012564,1);
insert into db_syscadind values(1008680,1012559,1);
insert into db_syscadind values(1008681,1012556,1);
insert into db_syscadind values(1008682,1012563,1);
insert into db_syscadind values(1008683,1012560,1);
insert into db_syscadind values(1008672,1012581,1);
insert into db_syscadind values(1008673,1012582,1);


SQL
        );

        $this->upDicionarioTabelaOds();
        $this->upDicionarioTabelaOrgaoprogramaestregico();
        $this->upDicionarioTabelaConfiguracaoplanejamento();
        $this->upDicionarioTabelaIniciativaprojativmetas();
        $this->upDicionarioArearesultadoprograma();
    }

    public function down()
    {

        $this->downDicionarioArearesultadoprograma();
        $this->downDicionarioTabelaIniciativaprojativmetas();
        $this->downDicionarioTabelaOds();
        $this->downDicionarioTabelaOrgaoprogramaestregico();
        $this->downDicionarioTabelaConfiguracaoplanejamento();

        $sCampos = "
  1012460, 1012461, 1012462, 1012463, 1012464, 1012465, 1012466, 1012467, 1012468, 1012469, 1012470, 1012471, 1012472,
  1012473, 1012474, 1012475, 1012476, 1012477, 1012478, 1012479, 1012480, 1012481, 1012482, 1012483,  1012484, 1012485,
  1012486, 1012487, 1012488, 1012489, 1012490, 1012491, 1012492, 1012493, 1012494, 1012495, 1012496, 1012497, 1012498,
  1012499, 1012500, 1012501, 1012502, 1012503, 1012504, 1012505, 1012506, 1012507, 1012508, 1012509, 1012510, 1012511,
  1012512, 1012513, 1012514, 1012515, 1012516, 1012517, 1012518, 1012519, 1012520, 1012521, 1012522, 1012523, 1012524,
  1012525, 1012526, 1012527, 1012528, 1012529, 1012530, 1012531, 1012532, 1012533, 1012534, 1012535, 1012536, 1012537,
  1012538, 1012539, 1012540, 1012541, 1012542, 1012543, 1012544, 1012545, 1012546, 1012547, 1012548, 1012549, 1012550,
  1012551, 1012552, 1012553, 1012554, 1012555, 1012556, 1012557, 1012558, 1012559, 1012560, 1012561, 1012562, 1012563,
  1012564, 1012565, 1012566, 1012567, 1012568, 1012569, 1012570, 1012571, 1012572, 1012573, 1012574, 1012575, 1012576,
  1012577, 1012578, 1012579, 1012580, 1012581, 1012582, 1012583, 1012584
        ";

        $sEstruturas = "
        1010701, 1010702, 1010703, 1010704, 1010705, 1010706, 1010707, 1010708, 1010709, 1010710, 1010711,
        1010712, 1010713, 1010714, 1010715, 1010716, 1010717, 1010718, 1010719, 1010720, 1010721, 1010722, 1010723
        ";


        $this->execute(<<<SQL

        --REMOVE VINCULO DOS INDICES
        delete from db_syscadind  where codcam in ( $sCampos );
        --REMOVE PK
        delete from db_sysprikey  where codarq in ( $sEstruturas);
        -- REMOVE FK
        delete from db_sysforkey  where codarq in ( $sEstruturas);
        --REMOVE INDICES
        delete from db_sysindices where codarq in ( $sEstruturas);
        -- REMOVE VUNCULO DO CAMPO COM ESTRUTURA
        delete from db_sysarqcamp where codarq in ( $sEstruturas);
        -- REMOVE OS CAMPOS
        delete from db_syscampo where codcam in ($sCampos);

         -- REMOVE AS ESTRUTURAS
        delete from db_sysarqmod where codarq in ($sEstruturas) ;
        delete from db_sysarquivo where codarq in ($sEstruturas) ;


        -- REMOVE MODULO
        delete from db_sysmodulo where codmod = 85;


SQL
        );
    }


    private function upDicionarioTabelaOds()
    {
        $this->execute(<<<SQL
insert into db_sysarquivo values (1010725, 'ods', 'Objetivos de Desenvolvimento Sustentável (ODS) ', 'pl26', '2021-02-24', 'ODS', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (85,1010725);
insert into db_syscampo
values (1012590,'pl26_codigo','varchar(5)','Código da ODS','', 'Código',5,'f','t','f',0,'text','Código'),
       (1012591,'pl26_descricao','varchar(60)','Descrição da ODS','', 'Descrição',60,'t','f','f',0,'text','Descrição');

insert into db_sysarqcamp
values (1010725,1011345,1,0),
       (1010725,1012590,2,0),
       (1010725,1012591,3,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010725,1011345,1,1012591);
insert into db_sysindices values(1008684,'ods_codigo_in',1010725,'0');
insert into db_syscadind values(1008684,1012590,1);
insert into db_sysindices values(1008685,'ods_descricao_in',1010725,'0');
insert into db_syscadind values(1008685,1012591,1);
SQL
        );
    }

    private function downDicionarioTabelaOds()
    {
$this->execute(<<<SQL
delete from db_sysarqcamp where codarq = 1010725;
delete from db_sysprikey where codarq = 1010725;
delete from db_syscadind where codind in (1008684, 1008685);
delete from db_sysindices where codind in (1008684, 1008685);
delete from db_sysarqmod where codarq = 1010725;
delete from db_syscampo where codcam in (1012590, 1012591);
delete from db_sysarquivo where codarq = 1010725;
SQL
        );
    }

    private function upDicionarioTabelaOrgaoprogramaestregico()
    {
        $this->execute(<<<SQL
insert into db_sysarquivo values (1010726, 'orgaoprogramaestregico', 'Orgão do programa estratégico', 'pl27', '2021-02-25', 'Orgão do programa estratégico', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (85,1010726);

insert into db_syscampo
values (1012592,'pl27_orcorgao','int4','Órgão do orçamento','0', 'Órgão',10,'f','f','f',1,'text','Órgão'),
       (1012593,'pl27_anoorcamento','int4','Ano do orçamento','0', 'Ano do orçamento',10,'f','f','f',1,'text','Ano do orçamento'),
       (1012594,'pl27_programaestrategico','int4','Programa estratégico','0', 'Programa estratégico',10,'f','f','f',1,'text','Programa estratégico');

insert into db_sysarqcamp
values (1010726,1011345,1,0),
       (1010726,1012594,2,0),
       (1010726,1012592,3,0),
       (1010726,1012593,4,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010726,1011345,1,1011345);
insert into db_sysforkey
values (1010726,1012594,1,1010709,0),
       (1010726,1012593,1,756,0),
       (1010726,1012592,2,756,0);
SQL
        );
    }


    private function downDicionarioTabelaOrgaoprogramaestregico()
    {
$this->execute(<<<SQL

delete from db_sysprikey where codarq = 1010726;
delete from db_sysforkey where codarq = 1010726;
delete from db_sysarqcamp where codarq = 1010726;
delete from db_sysarqmod where codarq = 1010726;
delete from db_syscampo where codcam in (1012592, 1012593, 1012594);
delete from db_sysarquivo where codarq = 1010726;
SQL
        );
    }

    private function upDicionarioTabelaConfiguracaoplanejamento()
    {
        $this->execute(<<<SQL
insert into db_sysarquivo values (1010727, 'configuracaoplanejamento', 'Parametrização do Planejamento', 'pl28', '2021-02-25', 'Configuração', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (85,1010727);
insert into db_syscampo values(1012595,'pl28_composicao','int8','Composição 1 - Sem Área de Resultado 2 - Com Área de Resultado 3 - Com Área de Resultado e Objetivo Estratégico','0', 'Composição',10,'f','f','f',1,'text','Composição');
insert into db_sysarqcamp values(1010727,1012595,1,0);

SQL
        );
}

    private function downDicionarioTabelaConfiguracaoplanejamento()
    {
        $this->execute(<<<SQL
delete from db_sysarqcamp where codarq = 1010727;
delete from db_sysarqmod where codarq = 1010727;
delete from db_syscampo where codcam in (1012595);
delete from db_sysarquivo where codarq = 1010727;
SQL
        );
    }

    public function upDicionarioTabelaIniciativaprojativmetas()
    {
      $this->execute(<<<SQL
insert into db_sysarquivo values (1010749, 'metasiniciativaprojativ', 'armazena as metas das iniciativas', 'pl28', '2021-03-02', 'iniciativaprojativmetas', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (85,1010749);
insert into db_syscampo
values (1012799,'iniciativaprojativ_id','int4','FK','0', 'Iniciativa',10,'f','f','f',1,'text','Iniciativa'),
       (1012800,'meta_financeira','float8','Valor da Meta Financeira','0', 'Valor da Meta Financeira',10,'f','f','f',4,'text','Valor da Meta Financeira'),
       (1012801,'unidade','varchar(255)','Unidade','', 'Unidade',255,'t','f','f',0,'text','Unidade'),
       (1012802,'meta_fisica','float8','Valor da Meta Física','0', 'Valor da Meta Física',10,'t','f','f',4,'text','Valor da Meta Física');

insert into db_sysarqcamp
values (1010749, 1011345, 1, 0),
       (1010749, 1012799, 2, 0),
       (1010749, 15983, 3, 0),
       (1010749, 1012802, 4, 0),
       (1010749, 1012801, 5, 0),
       (1010749, 1012800, 6, 0);

insert into db_sysforkey values(1010749,1012799,1,1010712,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010749,1011345,1,1011345);
SQL
        );
    }

    public function downDicionarioTabelaIniciativaprojativmetas()
    {
      $this->execute(<<<SQL
delete from db_sysforkey where codarq = 1010749;
delete from db_sysprikey where codarq = 1010749;
delete from db_sysarqcamp where codarq = 1010749;
delete from db_syscampo where codcam in (1012799, 1012800, 1012801, 1012802);
delete from db_sysarqmod where  codarq = 1010749;
delete from db_sysarquivo where  codarq = 1010749;
SQL
        );
    }

    public function upDicionarioArearesultadoprograma()
    {
      $this->execute(<<<SQL
insert into db_sysarquivo values (1010750, 'arearesultadoprograma', 'Vínculo da área de resultado com o programa estratégico', 'pl28', '2021-03-03', 'arearesultadoprograma', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (85,1010750);
insert into db_syscampo
values (1012803,'arearesultado_id','int4','Vínculo com Área de Resultado','0', 'Área de Resultado',10,'f','f','f',1,'text','Área de Resultado'),
       (1012804,'programaestrategico_id','int4','Vínculo Programa estratégico','0', 'Programa estratégico',10,'f','f','f',1,'text','Programa estratégico');

insert into db_sysarqcamp
values (1010750, 1011345, 1, 0),
       (1010750, 1012803, 2, 0),
       (1010750, 1012804, 3, 0),
       (1010750, 1012583, 4, 0),
       (1010750, 1012584, 5, 0);

insert into db_sysprikey (codarq,codcam,sequen,camiden)
values (1010750, 1011345, 1, 1011345);

insert into db_sysforkey
values (1010750, 1012803, 1, 1010704, 0),
       (1010750, 1012804, 1, 1010709, 0);
SQL
        );
    }

    public function downDicionarioArearesultadoprograma()
    {
      $this->execute(<<<SQL
delete from db_sysprikey where codarq = 1010750;
delete from db_sysforkey where codarq = 1010750;
delete from db_sysarqcamp where codarq = 1010750;
delete from db_syscampo where codcam in (1012803, 1012804);
delete from db_sysarqmod where codarq = 1010750;
delete from db_sysarquivo where codarq = 1010750;
SQL
        );
    }

}
