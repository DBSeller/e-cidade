<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M20842AtualizacaoTabelaAuxiliaresIes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into escola.censoinstsuperior (ed257_i_codigo, ed257_c_nome, ed257_i_tipo, ed257_i_dependencia, ed257_i_censomunic, ed257_c_situacao) 
values (25444,'SISTEMAS DE ENSINO EM CIENCIAS E TECNOLOGIAS',1,4,2507507, 'ATIVA'),
(24463,'FACULDADE VITORIA EM CRISTO',1,4,3304557, 'ATIVA'),
(24046,'FACULDADE DA ASSOCIACAO MEDICA PAULISTA',1,4,3550308, 'ATIVA'),
(24530,'FACULDADE DA POLICIA MILITAR DE SANTA CATARINA',1,2,4205407, 'ATIVA'),
(21998,'FACULDADE PANAMERICANA DE ADMINISTRACAO E DIREITO',1,4,4106902, 'ATIVA'),
(23242,'ESCOLA SUPERIOR SAO JUDAS DE SAO BERNARDO DO CAMPO',1,4,3548708, 'ATIVA'),
(23181,'FACULDADE INTERCONTINENTAL',1,4,3557006, 'ATIVA'),
(22985,'FACULDADE TRILOGICA NOSSA SENHORA DE TODOS OS POVOS',1,4,3550308, 'ATIVA'),
(23890,'FACULDADES INTEGRADAS DE SAUDE E EDUCACAO DO BRASIL',1,4,5103403, 'ATIVA'),
(23224,'FACULDADE PRESIDENTE DUTRA',1,4,2109106, 'ATIVA'),
(23244,'INSTITUTO UNIVERSITARIO UNA DE CONSELHEIRO LAFAIETE',1,4,3118304, 'ATIVA'),
(23776,'FACULDADE DE ENSINO SUPERIOR DO LAGO',1,4,2112209, 'ATIVA'),
(24457,'FACULDADE PARAISO ARARIPINA',1,4,2601102, 'ATIVA'),
(23875,'FACULDADE INSTITUTO DE ENSINO SUPERIOR',1,4,5208707, 'ATIVA'),
(18039,'FACULDADE CAPACITAR',1,4,4301602, 'ATIVA'),
(23866,'FACULDADE RESULTADOS',1,4,5002704, 'ATIVA'),
(24471,'FACULDADE ATENAS VALENCA',1,4,2932903, 'ATIVA'),
(23381,'FACULDADE DE GOVERNANCA ENGENHARIA E EDUCACAO DE SAO PAULO',1,4,3504503, 'ATIVA'),
(22866,'FACULDADE ASSOCIADA BRASIL  EAD',1,4,3550308, 'ATIVA'),
(24300,'FACULDADE UNIDA DE SAO PAULO  EAD',1,4,3550308, 'ATIVA'),
(24470,'FACULDADE DE MEDICINA DO SERTAO',1,4,2601201, 'ATIVA'),
(24025,'CENTRO DE ENSINO SUPERIOR DE SERRA DOURADA',1,4,3527207, 'ATIVA'),
(24026,'INSTITUTO DE SERRA DOURADA',1,4,3527207, 'ATIVA'),
(24055,'FACULDADE FASIPE DE PRIMAVERA',1,4,5107040, 'ATIVA'),
(22748,'FACULDADES FAMEP',1,4,2210508, 'ATIVA'),
(23109,'FACULDADE DE CIENCIAS E TECNOLOGIA DE CHAPECO',1,4,4204202, 'ATIVA'),
(20584,'FACULDADE SANTANA',1,4,2210607, 'ATIVA'),
(23723,'FACULDADE VOLPE MIELE',1,4,3543402, 'ATIVA'),
(22769,'GAIA',1,4,3552205, 'ATIVA'),
(24287,'FACULDADE BIOPARK',1,4,4127700, 'ATIVA'),
(23252,'ESCOLA SUPERIOR UNA DE ITUMBIARA',1,4,5211503, 'ATIVA'),
(24525,'CENTRO INTERNACIONAL DE ENSINO EM CIENCIAS E SUAS APLICACOES',1,4,3509502, 'ATIVA'),
(23038,'FACULDADE DE EDUCACAO E TECNOLOGIA DO ESPIRITO SANTO',1,4,3205002, 'ATIVA'),
(24275,'FACULDADE UNIFICADA DO ESTADO DE SAO PAULO',1,4,3529401, 'ATIVA'),
(22178,'FACULDADE PITAGORAS ANHANGUERA DE TRES LAGOAS',1,4,5008305, 'ATIVA'),
(23973,'CENTRO DE ENSINO SUPERIOR DE ALTAMIRA',1,4,1500602, 'ATIVA'),
(22966,'FACULDADE INSTITUTO BRASILEIRO DE ENSINO',1,4,3106200, 'ATIVA'),
(24066,'FACULDADE CRISTA DA CIDADE',1,4,3549904, 'ATIVA'),
(17783,'FACULDADE DE MEDIACAO IVIA CORNELI',1,4,3144805, 'ATIVA'),
(23026,'ESCOLA DE SARGENTOS DE LOGISTICA',1,1,3304557, 'ATIVA'),
(18751,'FACULDADE MALTA',1,4,2211001, 'ATIVA'),
(25157,'FACULDADE LUIZ MARIO MOUTINHO',1,4,2611606, 'ATIVA'),
(24459,'FACULDADE TIRADENTES DE GOIANA',1,4,2606200, 'ATIVA'),
(23455,'FACULDADE INTEGRADA INSTITUTO SOUZA',1,4,3131307, 'ATIVA'),
(24205,'FACULDADE SOBERANA DE ARAPIRACA',1,4,2700300, 'ATIVA'),
(24700,'FACULDADE INSTITUTO RIO DE JANEIRO',1,4,3304557, 'ATIVA'),
(24687,'FACULDADE DE MEDICINA ESTACIO DE CASTANHAL',1,4,1502400, 'ATIVA'),
(23946,'FACULDADE DE TECNOLOGIA E CIENCIAS  FTC JUAZEIRO DO NORTE',1,4,2307304, 'ATIVA'),
(23331,'ALFA  FACULDADE DE TEOFILO OTONI',1,4,3168606, 'ATIVA'),
(23707,'FACULDADE DE TECNOLOGIA E EDUCACAO SUPERIOR E PROFISSIONALIZ',1,4,5208004, 'ATIVA'),
(23893,'FACULDADE ANHANGUERA DE ITAPETININGA',1,4,3522307, 'ATIVA'),
(23322,'FACULDADE MENNA BARRETO',1,4,4101804, 'ATIVA'),
(24196,'FACULDADE CLEBER LEITE  EAD',1,4,3547809, 'ATIVA'),
(23023,'FACULDADE TECNOLOGICA ANTHROPOS',1,4,2313401, 'ATIVA'),
(23280,'FACULDADE DE EDUCACAO SUPERIOR IESLA',1,4,3106200, 'ATIVA'),
(24922,'FACULDADE SAO VICENTE DE IRATI',1,4,4110706, 'ATIVA'),
(25613,'ACADEMIA DE POLICIA MILITAR DE MINAS GERAIS',1,2,3106200, 'ATIVA'),
(24980,'FACULDADE DE TECNOLOGIA SENAC PONTA GROSSA',1,4,4119905, 'ATIVA'),
(24399,'FACULDADE ESCOLA SOBRAL DE OLIVEIRA',1,4,2304954, 'ATIVA'),
(24464,'FACULDADE DE CIENCIAS DA SAUDE PITAGORAS DE CODO',1,4,2103307, 'ATIVA'),
(22703,'FACULDADE PITAGORAS DE CONCORDIA',1,4,4204301, 'ATIVA'),
(24446,'FACULDADE DE MEDICINA DE ACAILANDIA',1,4,2100055, 'ATIVA'),
(24245,'FACULDADE QUALITTAS  EAD',1,4,3550308, 'ATIVA'),
(23394,'FACULDADE DE ENGENHARIA E ADMINISTRACAO PAULISTA',1,4,3504503, 'ATIVA'),
(22425,'FACULDADE ESAMC FRANCA',1,4,3516200, 'ATIVA'),
(23924,'FACULDADE ANHANGUERA DE SAO JOAO DE MERITI',1,4,3305109, 'ATIVA'),
(25377,'FACULDADE INPRO',1,4,5208707, 'ATIVA'),
(22771,'FACULDADE CRISTO REI',1,4,4106407, 'ATIVA'),
(21220,'FACULDADE SILVA NETO',1,4,5300108, 'ATIVA'),
(23118,'IBPTECH FACULDADE DE CIENCIAS FORENSES E TECNOLOGIA',1,4,3550308, 'ATIVA'),
(23908,'FACULDADE SAO FRANCISCO DO CEARA  CRATO',1,4,2304202, 'ATIVA'),
(22759,'INSTITUTO UNA DE ITABIRA',1,4,3131703, 'ATIVA'),
(23912,'FACULDADE IBCMED SAO PAULO',1,4,3550308, 'ATIVA'),
(24392,'FACULDADE DINAMICA',1,4,5219704, 'ATIVA'),
(23842,'FACULDADE SENAC CEARA',1,4,2304400, 'ATIVA'),
(23163,'FACULDADE DE EDUCACAO SUPERIOR DE DIVINOPOLIS',1,4,3122306, 'ATIVA'),
(24884,'FACULDADE MOCA DE SAO PAULO',1,4,3550308, 'ATIVA'),
(24214,'FACULDADE DOS GENIOS',1,4,3131307, 'ATIVA'),
(23273,'FACULDADE UNA DE ITUMBIARA',1,4,5211503, 'ATIVA'),
(24403,'FACULDADE ATENAS SUL DE MINAS',1,4,3147907, 'ATIVA'),
(24168,'FACULDADE UNIDA DE SAO PAULO',1,4,3550308, 'ATIVA'),
(23117,'FACULDADE CAPISTRANO DE ABREU',1,4,2304400, 'ATIVA'),
(23028,'ESCOLA DE SARGENTOS DAS ARMAS',1,1,3169307, 'ATIVA'),
(22177,'FACULDADE UNIAO PAULISTANA',1,4,3550308, 'ATIVA'),
(22716,'FACULDADE PITAGORAS DE PENAPOLIS',1,4,3537305, 'ATIVA'),
(23798,'FACULDADE DE CIENCIAS E EDUCACAO EM SAUDE',1,4,2611606, 'ATIVA'),
(23877,'FACULDADE ENDEAVOUR',1,4,5300108, 'ATIVA'),
(23843,'FACULDADE ITEC',1,4,2510808, 'ATIVA'),
(24391,'FACULDADE DE EDUCACAO DA IBIAPABA',1,4,2305803, 'ATIVA'),
(22650,'FACULDADE IMPACTOS  FACI',1,4,5103403, 'ATIVA'),
(22660,'FACULDADE UNYPUBLICA',1,4,4106902, 'ATIVA'),
(23236,'ESCOLA SUPERIOR SAO JUDAS DE GUARULHOS',1,4,3518800, 'ATIVA'),
(23333,'FACULDADE DAMASIO EDUCACIONAL',1,4,3550308, 'ATIVA'),
(23894,'FACULDADE PITAGORAS DE BELEM',1,4,1501402, 'ATIVA'),
(23146,'FACULDADE DOMINIUS',1,4,2911709, 'ATIVA'),
(24400,'FACULDADE DOCTUM DE SETE LAGOAS',1,4,3167202, 'ATIVA'),
(23332,'FACULDADE ALFA DE TEOFILO OTONI',1,4,3168606, 'ATIVA'),
(24405,'INSTITUTO DE DESENVOLVIMENTO EDUCACIONAL DA AMAZONIA',1,4,2105302, 'ATIVA'),
(23245,'ESCOLA SUPERIOR UNA DE CONSELHEIRO LAFAIETE',1,4,3118304, 'ATIVA'),
(24211,'INSTITUTO AMAZONICO DE ENSINO SUPERIOR',1,4,1302603, 'ATIVA'),
(24074,'FACULDADE DE GESTAO EDUCACAO E DE SAUDE',1,4,5103403, 'ATIVA'),
(23733,'FACULDADE RIO GUARIBAS',1,4,2208007, 'ATIVA');

update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO VALE DO RIO VERDE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3169307 where ed257_i_codigo = 27;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE FILOSOFIA CIENCIAS E LETRAS DE PENAPOLIS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3537305 where ed257_i_codigo = 68;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE FILOSOFIA CIENCIAS E LETRAS DE MACAE', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3302403 where ed257_i_codigo = 84;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADES INTEGRADAS DO ESTADO DE SAO PAULO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3505302 where ed257_i_codigo = 131;
update escola.censoinstsuperior set ed257_c_nome = 'CLARETIANO  CENTRO UNIVERSITARIO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3505906 where ed257_i_codigo = 135;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNIBRASILIA DE CIENCIAS ECONOMICAS DE MINAS GERAIS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3170107 where ed257_i_codigo = 139;
update escola.censoinstsuperior set ed257_c_nome = 'ESCOLA DE ADMINISTRACAO DE EMPRESAS DE SAO PAULO  FGV EAESP', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 151;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE NOVA ROMA CARUARU', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2604106 where ed257_i_codigo = 159;
update escola.censoinstsuperior set ed257_c_nome = 'FAI  CENTRO DE ENSINO SUPERIOR EM GESTAO TECNOLOGIA E EDUCACAO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3159605 where ed257_i_codigo = 166;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO FMABC', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3547809 where ed257_i_codigo = 224;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS ADMINISTRATIVAS E CONTABEIS COSTA BRAGA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 282;
update escola.censoinstsuperior set ed257_c_nome = 'UNIVERSIDADE PITAGORAS UNOPAR ANHANGUERA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4113700 where ed257_i_codigo = 298;
update escola.censoinstsuperior set ed257_c_nome = 'UNIVERSIDADE EVANGELICA DE GOIAS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5201108 where ed257_i_codigo = 384;
update escola.censoinstsuperior set ed257_c_nome = 'ESCOLA DE ENGENHARIA DE AGRIMENSURA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2927408 where ed257_i_codigo = 399;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO ARMANDO ALVARES PENTEADO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 461;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE FILOSOFIA CIENCIAS E LETRAS DE ITAPETININGA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3522307 where ed257_i_codigo = 468;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS ECONOMICAS ADMINISTRATIVAS E DA COMPUTACAO DOM BOSCO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3304201 where ed257_i_codigo = 473;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO ANHANGUERA PITAGORAS UNOPAR DE NITEROI', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3303302 where ed257_i_codigo = 515;
update escola.censoinstsuperior set ed257_c_nome = 'FUNDACAO FACULDADE DE FILOSOFIA CIENCIAS E LETRAS DE MANDAGUARI', ed257_i_tipo = 1, ed257_i_dependencia = 3, ed257_i_censomunic = 4114203 where ed257_i_codigo = 535;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE FILOSOFIA CIENCIAS E LETRAS DE BOA ESPERANCA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3107109 where ed257_i_codigo = 554;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DA BAHIA', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 2927408 where ed257_i_codigo = 599;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DO MARANHAO', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 2111300 where ed257_i_codigo = 600;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DO RIO GRANDE DO SUL', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 4302105 where ed257_i_codigo = 601;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE ENGENHARIA SAO PAULO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 637;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE PETROLINA', ed257_i_tipo = 1, ed257_i_dependencia = 3, ed257_i_censomunic = 2611101 where ed257_i_codigo = 692;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE FILOSOFIA CIENCIAS E LETRAS DO ALTO SAO FRANCISCO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3138807 where ed257_i_codigo = 727;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO TERESA D''AVILA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3527207 where ed257_i_codigo = 738;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE INTERACAO AMERICANA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3548708 where ed257_i_codigo = 803;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS HUMANAS DO VALE DO RIO GRANDE', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3533908 where ed257_i_codigo = 831;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PARAIBANA DE PROCESSAMENTO DE DADOS', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2507507 where ed257_i_codigo = 848;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO UNISAN', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 898;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNIRIO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1100205 where ed257_i_codigo = 900;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO ANHANGUERA PITAGORAS UNOPAR DE CAMPO GRANDE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5002704 where ed257_i_codigo = 926;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TURISMO  SANTOS DUMONT', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3160702 where ed257_i_codigo = 966;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO SUPERIOR E CENTRO EDUCACIONAL LUTERANO  BOM JESUS - IELUSC', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4209102 where ed257_i_codigo = 1014;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO ESTACIO DA BAHIA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2927408 where ed257_i_codigo = 1058;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS ECONOMICAS E ADMINISTRATIVAS DE VILA VELHA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3205200 where ed257_i_codigo = 1065;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE FILOSOFIA CIENCIAS E LETRAS DE CAJAZEIRAS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2503704 where ed257_i_codigo = 1076;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DO RIO GRANDE DO NORTE', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 2408102 where ed257_i_codigo = 1082;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA FLUMINENSE', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 3301009 where ed257_i_codigo = 1120;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO NOSSA SENHORA DO PATROCINIO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3523909 where ed257_i_codigo = 1149;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO CAMBURY', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5208707 where ed257_i_codigo = 1160;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA  DA PARAIBA', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 2507507 where ed257_i_codigo = 1166;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO FAMEC', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2905701 where ed257_i_codigo = 1170;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS SOCIAIS E APLICADAS DO PARANA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4106902 where ed257_i_codigo = 1198;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO FAEL', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4113205 where ed257_i_codigo = 1205;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ANGLO LATINO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 1215;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO MULTIVIX VITORIA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3205309 where ed257_i_codigo = 1244;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE ADMINISTRACAO CIENCIAS, EDUCACAO E LETRAS', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4106902 where ed257_i_codigo = 1257;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE SANT¿ANNA DE SALTO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3545209 where ed257_i_codigo = 1272;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO TAQUARITINGUENSE DE ENSINO SUPERIOR DR. ARISTIDES DE CARVALHO SCHLOBACH', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3553708 where ed257_i_codigo = 1300;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA GOIANO', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 5208707 where ed257_i_codigo = 1303;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS JURIDICAS GERENCIAIS E EDUCACAO DE SINOP', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5107909 where ed257_i_codigo = 1305;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO MATER DEI', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4118501 where ed257_i_codigo = 1337;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO DO VALE DO JAGUARIBE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2301109 where ed257_i_codigo = 1350;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO GOVERNADOR OZANAM COELHO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3169901 where ed257_i_codigo = 1362;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO TOLEDO WYDEN', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3502804 where ed257_i_codigo = 1418;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE EVOLUIR', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2304400 where ed257_i_codigo = 1425;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADES DE CAMPINAS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3509502 where ed257_i_codigo = 1438;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ESTACIO DE COTIA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3513009 where ed257_i_codigo = 1457;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE LUSOFONA DO RIO DE JANEIRO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3304904 where ed257_i_codigo = 1488;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNIGUACU', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4125704 where ed257_i_codigo = 1500;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO VALE DO CRICARE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3204906 where ed257_i_codigo = 1514;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA SULRIO-GRANDENSE', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 4314407 where ed257_i_codigo = 1578;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO DO NORTE DE MINAS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3143302 where ed257_i_codigo = 1600;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADES FATIFAJAR  FATIFAJAR ARAPOTI', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4101606 where ed257_i_codigo = 1611;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE J. SIMOES ENSINO SUPERIOR', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3202405 where ed257_i_codigo = 1650;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE KENNEDY', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3136207 where ed257_i_codigo = 1665;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE CATOLICA SALESIANA DE MACAE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3302403 where ed257_i_codigo = 1682;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO DO RECIFE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2611606 where ed257_i_codigo = 1708;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO UNIAO DAS AMERICAS DESCOMPLICA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4108304 where ed257_i_codigo = 1716;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO NOBRE DE FEIRA DE SANTANA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2910800 where ed257_i_codigo = 1718;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DO CEARA', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 2304400 where ed257_i_codigo = 1807;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DO ESPIRITO SANTO', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 3205309 where ed257_i_codigo = 1808;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DE PERNAMBUCO', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 2611606 where ed257_i_codigo = 1809;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DE SAO PAULO', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 3550308 where ed257_i_codigo = 1810;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DE GOIAS', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 5208707 where ed257_i_codigo = 1811;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA  DO AMAZONAS', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 1302603 where ed257_i_codigo = 1812;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DO PARA', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 1501402 where ed257_i_codigo = 1813;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DO PIAUI', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 2211001 where ed257_i_codigo = 1820;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO SULAMERICANA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5208707 where ed257_i_codigo = 1822;
update escola.censoinstsuperior set ed257_c_nome = 'FEFISA  FACULDADES INTEGRADAS DE SANTO ANDRE', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3547809 where ed257_i_codigo = 1845;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE INTERVALE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3139607 where ed257_i_codigo = 1863;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE MERCURIO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3304557 where ed257_i_codigo = 1873;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE FILOSOFIA CIENCIAS E LETRAS DE IBITINGA', ed257_i_tipo = 1, ed257_i_dependencia = 3, ed257_i_censomunic = 3519600 where ed257_i_codigo = 1875;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO DE ENSINO CIENCIA E TECNOLOGIA DO PARANA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4106902 where ed257_i_codigo = 1900;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO UNIVINTE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4203956 where ed257_i_codigo = 1918;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE  ARNALDO JANSSEN', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3106200 where ed257_i_codigo = 1923;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA IBRATEC', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2611606 where ed257_i_codigo = 1944;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO SENAI BLUMENAU', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4202404 where ed257_i_codigo = 1958;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO TECNOLOGICO E DAS CIENCIAS SOCIAIS APLICADAS E DA SAUDE DO CENTRO EDUC. N. SRª AUXILIADORA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3301009 where ed257_i_codigo = 1961;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DOS IMIGRANTES  FAI', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4305108 where ed257_i_codigo = 1969;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO SUPERIOR DE EDUCACAO ORIGENES LESSA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3526803 where ed257_i_codigo = 1973;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO SUPERIOR DE EDUCACAO AVANTIS', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4202008 where ed257_i_codigo = 1989;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO DE ENSINO SUPERIOR DE PIEDADE', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2607901 where ed257_i_codigo = 1992;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE JK  UNIDADE II - GAMA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 2021;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO DE CIENCIAS E EMPREENDEDORISMO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2928703 where ed257_i_codigo = 2067;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS EDUCACAO E TEOLOGIA DO NORTE DO BRASIL', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1400100 where ed257_i_codigo = 2133;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE MULTIEDUCATIVA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 2142;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE LUTERANA RUI BARBOSA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4114609 where ed257_i_codigo = 2312;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE IBERO AMERICANA DE SAO PAULO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3538808 where ed257_i_codigo = 2332;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO BRASILIA DO ESTADO DE GOIAS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5220108 where ed257_i_codigo = 2336;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ITEANA DE IBITINGA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3519600 where ed257_i_codigo = 2344;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA E CIENCIAS DE SALVADOR', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2927408 where ed257_i_codigo = 2402;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ALVES FARIA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 2463;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ANGLICANA DE ERECHIM', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4307005 where ed257_i_codigo = 2488;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO DE EDUCACAO E INOVACAO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1400100 where ed257_i_codigo = 2536;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TEOLOGIA FILOSOFIA E CIENCIAS HUMANAS GAMALIEL', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1508100 where ed257_i_codigo = 2548;
update escola.censoinstsuperior set ed257_c_nome = 'FIAMFAAM - CENTRO UNIVERSITARIO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 2556;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS ADMINISTRATIVAS', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 5103403 where ed257_i_codigo = 2561;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE REGIONAL DE FILOSOFIA CIENCIAS E LETRAS DE CANDEIAS', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2906501 where ed257_i_codigo = 2572;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO SUPERIOR DE EDUCACAO DON DOMENICO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3518701 where ed257_i_codigo = 2596;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO ESTACIO META DE RIO BRANCO ESTACIO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1200401 where ed257_i_codigo = 2613;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE RECIFE', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2611606 where ed257_i_codigo = 2656;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO SUPERIOR DE EDUCACAO SAO JUDAS TADEU', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2203909 where ed257_i_codigo = 2677;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE BILAC DE SAO JOSE DOS CAMPOS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3549904 where ed257_i_codigo = 2726;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO FACUNICAMPS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5208707 where ed257_i_codigo = 2770;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ANHANGUERA DE MACAPA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1600303 where ed257_i_codigo = 2773;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNIVERITAS UNIVERSUS VERITAS DE BELO HORIZONTE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3106200 where ed257_i_codigo = 2885;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DE ALAGOAS', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 2704302 where ed257_i_codigo = 3160;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DO SERTAO PERNAMBUCANO', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 2611101 where ed257_i_codigo = 3161;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA  DE SANTA CATARINA', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 4205407 where ed257_i_codigo = 3162;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DO RIO DE JANEIRO', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 3304557 where ed257_i_codigo = 3163;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DE MATO GROSSO', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 5103403 where ed257_i_codigo = 3164;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA  DO TRIANGULO MINEIRO', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 3170107 where ed257_i_codigo = 3165;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE EQUIPE', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4320008 where ed257_i_codigo = 3171;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DE SERGIPE', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 2800308 where ed257_i_codigo = 3183;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DE RORAIMA', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 1400100 where ed257_i_codigo = 3184;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DO NORTE DE MINAS GERAIS', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 3143302 where ed257_i_codigo = 3188;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DE MINAS GERAIS', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 3106200 where ed257_i_codigo = 3189;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DO SUDESTE DE MINAS GERAIS', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 3136702 where ed257_i_codigo = 3279;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ENGENHEIRO SALVADOR ARENA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3548708 where ed257_i_codigo = 3308;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE BOAS NOVAS DE CIENCIAS TEOLOGICAS SOCIAIS E BIOTECNOLOGICAS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1302603 where ed257_i_codigo = 3397;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UBERLANDENSE DE NUCLEOS INTEGRADOS DE ENSINO SERVICO SOCIAL E APRENDIZAGEM', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3170206 where ed257_i_codigo = 3430;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADES INTEGRADAS DE CIENCIAS HUMANAS SAUDE E EDUCACAO DE GUARULHOS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3518800 where ed257_i_codigo = 3432;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNISUL DE BALNEARIO CAMBORIU', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4202008 where ed257_i_codigo = 3437;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DA FRONTEIRA  FAF', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4102604 where ed257_i_codigo = 3502;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE EVANGELICA DE TECNOLOGIA CIENCIAS E BIOTECNOLOGIA DA CGADB', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3304557 where ed257_i_codigo = 3525;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE NANUQUE', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3144300 where ed257_i_codigo = 3530;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADES INTEGRADAS DA UNIAO DE ENSINO SUPERIOR CERTO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 3611;
update escola.censoinstsuperior set ed257_c_nome = 'ESCOLA DE CIENCIAS SOCIAIS FGV CPDOC', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3304557 where ed257_i_codigo = 3614;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE MONTES CLAROS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3143302 where ed257_i_codigo = 3657;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA TUPY DE SAO BENTO DO SUL', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4215802 where ed257_i_codigo = 3691;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE IBRA DA GRANDE SAO PAULO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 3746;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE BRASILIA DE SAO PAULO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 3749;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ALFAUNIPAC DE ALMENARA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3101706 where ed257_i_codigo = 3756;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNISUL DE FLORIANOPOLIS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4205407 where ed257_i_codigo = 3758;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO UVB.BR', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 3775;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO MAURICIO DE NASSAU DE JOAO PESSOA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2507507 where ed257_i_codigo = 3817;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE SALVADOR', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2927408 where ed257_i_codigo = 3826;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO CENTRO DE ENSINO TECNOLOGICO  SOBRAL', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2312908 where ed257_i_codigo = 3830;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO MAURICIO DE NASSAU DE NATAL', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2408102 where ed257_i_codigo = 3853;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE IBRA DE BRASILIA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 3854;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO MAUA DE BRASILIA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 3867;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE MAUA DE GOIAS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5200258 where ed257_i_codigo = 3877;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO SUPERIOR DE EDUCACAO DE PARAISOPOLIS', ed257_i_tipo = 2, ed257_i_dependencia = 3, ed257_i_censomunic = 3147303 where ed257_i_codigo = 3970;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE CCI', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 3980;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO GOYAZES', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5221403 where ed257_i_codigo = 3987;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE SAO BERNARDO DE TECNOLOGIA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3548708 where ed257_i_codigo = 3990;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE JK  PLANO PILOTO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 3992;
update escola.censoinstsuperior set ed257_c_nome = 'FATECE  FACULDADE DE TECNOLOGIA CIENCIAS E EDUCACAO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3539301 where ed257_i_codigo = 4007;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE ITAPECERICA DA SERRA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3522208 where ed257_i_codigo = 4028;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE SANTA BARBARA D''OESTE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3545803 where ed257_i_codigo = 4029;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE TECNOLOGIA EDUVALE  AVARE', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3504503 where ed257_i_codigo = 4043;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA PEDRO ROGERIO GARCIA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4204301 where ed257_i_codigo = 4092;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO DE TECNOLOGIA DE CURITIBA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4106902 where ed257_i_codigo = 4093;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA FARROUPILHA', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 4316907 where ed257_i_codigo = 4098;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE JK BRASILIA  RECANTO DAS EMAS II', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 4199;
update escola.censoinstsuperior set ed257_c_nome = 'ESCOLA SUPERIOR DE ADMINISTRACAO MARKETING E COMUNICACAO DO MORUMBI', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 4210;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS GERENCIAIS DE BICAS', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3106903 where ed257_i_codigo = 4220;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO SUPERIOR DE EDUCACAO DE BICAS', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3106903 where ed257_i_codigo = 4221;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO SUPERIOR DE EDUCACAO DE MATIAS BARBOSA', ed257_i_tipo = 2, ed257_i_dependencia = 3, ed257_i_censomunic = 3140803 where ed257_i_codigo = 4222;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS GERENCIAIS ALVES FORTES (JUIZ DE FORA)', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3136702 where ed257_i_codigo = 4250;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE INHUMAS  FAC-MAIS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5210000 where ed257_i_codigo = 4259;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DO SUL DE MINAS GERAIS', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 3152501 where ed257_i_codigo = 4358;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE JK  UNIDADE I - GAMA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 4416;
update escola.censoinstsuperior set ed257_c_nome = 'ESCOLA SUPERIOR PAULISTA DE ADMINISTRACAO  ESPA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3518800 where ed257_i_codigo = 4442;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE MERIDIONAL DE IJUI', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4310207 where ed257_i_codigo = 4443;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE CIDADE DE GUANHAES  FACIG', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3128006 where ed257_i_codigo = 4446;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE FACESE', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4106902 where ed257_i_codigo = 4584;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CORNELIO PROCOPIO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4106407 where ed257_i_codigo = 4605;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA CONTEC  UNIDADE DE CARAPINA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3205002 where ed257_i_codigo = 4606;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO FAEMA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1100023 where ed257_i_codigo = 4613;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA E INOVACAO SENAC DF', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 4732;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PRESBITERIANA AUGUSTO GALVAO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2906006 where ed257_i_codigo = 4739;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DO TOCANTINS', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 1721000 where ed257_i_codigo = 4786;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO CENTRO DE ENSINO TECNOLOGICO  CARIRI', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2307601 where ed257_i_codigo = 4788;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO CENTRO DE ENSINO TECNOLOGICO  LIMOEIRO DO NORTE', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2307601 where ed257_i_codigo = 4789;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ANGLO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3538709 where ed257_i_codigo = 4917;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE INTEGRADA DAS CATARATAS EJOVEM', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4108304 where ed257_i_codigo = 4922;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE IBRA DE TECNOLOGIA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3549904 where ed257_i_codigo = 4983;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE AGORA  ADMINISTRACAO EDUCACAO E CULTURA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4106902 where ed257_i_codigo = 5000;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE EDUCACAO TECNOLOGICA DO ESTADO DO RIO DE JANEIRO FERNANDO MOTA', ed257_i_tipo = 1, ed257_i_dependencia = 2, ed257_i_censomunic = 3304557 where ed257_i_codigo = 5016;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA CATARINENSE', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 4202404 where ed257_i_codigo = 5036;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO INTERNACIONAL SIGNORELLI', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3304557 where ed257_i_codigo = 5105;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE FORTIUM', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 5277;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE MEDICINA DE GARANHUNS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2606002 where ed257_i_codigo = 5580;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE OBOE  FACO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2304400 where ed257_i_codigo = 10016;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DA FRATERNIDADE DE VALENCA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2932903 where ed257_i_codigo = 10058;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE SANTA RITA DO PASSA QUATRO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3547502 where ed257_i_codigo = 10418;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNIAO ARARUAMA DE ENSINO S/S LTDA.', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3300209 where ed257_i_codigo = 10836;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE VILLALOBOS DO CONE-LESTE PAULISTA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3549904 where ed257_i_codigo = 11376;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE SUDOESTE PAULISTA  TATUI - FSP', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3554003 where ed257_i_codigo = 11752;
update escola.censoinstsuperior set ed257_c_nome = 'FACUMINAS FACULDADE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3143302 where ed257_i_codigo = 12189;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA FAESA  VILA VELHA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3205200 where ed257_i_codigo = 12229;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO BRASILEIRO DE ENSINO DESENVOLVIMENTO E PESQUISA DE BRASILIA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 12247;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ACESITA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3168705 where ed257_i_codigo = 12718;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE FINACI', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 12723;
update escola.censoinstsuperior set ed257_c_nome = 'FETAC  FACULDADE DE EDUCACAO TECNOLOGIA E ADMINISTRACAO DE CAARAPO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5002407 where ed257_i_codigo = 12748;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADES UNIDAS DE PESQUISA CIENCIAS E SAUDE LTDA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2918001 where ed257_i_codigo = 12749;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNESCUNAMA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1100205 where ed257_i_codigo = 12758;
update escola.censoinstsuperior set ed257_c_nome = 'IPOG  INSTITUTO DE POS-GRADUACAO & GRADUACAO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5208707 where ed257_i_codigo = 12916;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE ENSINO SUPERIOR', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3170206 where ed257_i_codigo = 13034;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA AEROTD', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4205407 where ed257_i_codigo = 13073;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE CARAGUA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3510500 where ed257_i_codigo = 13538;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS EDUCACAO, SAUDE, PESQUISA E GESTAO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3304805 where ed257_i_codigo = 13631;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA SENAI TELEMACO BORBA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4127106 where ed257_i_codigo = 13674;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA CAPACITACAO E GESTAO INTEGRAL', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4106902 where ed257_i_codigo = 13735;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA DE AMPERE', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4101002 where ed257_i_codigo = 13764;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA DE NOVO CABRAIS', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4313391 where ed257_i_codigo = 14158;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PRESIDENTE ANTONIO CARLOS DE RIBEIRAO DAS NEVES', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3154606 where ed257_i_codigo = 14173;
update escola.censoinstsuperior set ed257_c_nome = 'FATEC  FACULDADE DE TEOLOGIA E CIENCIAS DE VOTUPORANGA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3557105 where ed257_i_codigo = 14194;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PRESIDENTE ANTONIO CARLOS DE CONGONHAS', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3118007 where ed257_i_codigo = 14249;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA INSAEOS', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4104808 where ed257_i_codigo = 14326;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DE BRASILIA', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 5300108 where ed257_i_codigo = 14408;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA BAIANO', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 2927408 where ed257_i_codigo = 14509;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNIFTB', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2807402 where ed257_i_codigo = 14622;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DO PARANA', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 4106902 where ed257_i_codigo = 14724;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE FELUMA  SAUDE TECNOLOGIA E CIENCIA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3137601 where ed257_i_codigo = 14738;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS DA SAUDE DE BARRETOS DR. PAULO PRATA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3505500 where ed257_i_codigo = 14892;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA EDUCACAO SUPERIOR E PROFISSIONAL', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2211001 where ed257_i_codigo = 15272;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE BRASILEIRA DE ENSINO PESQUISA E EXTENSAO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2507507 where ed257_i_codigo = 15280;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DA INDUSTRIA CURITIBA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4106902 where ed257_i_codigo = 15445;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DO ACRE', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 1200401 where ed257_i_codigo = 15507;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DE MATO GROSSO DO SUL', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 5002704 where ed257_i_codigo = 15520;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO FEDERAL DE EDUCACAO CIENCIA E TECNOLOGIA DO AMAPA', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 1600303 where ed257_i_codigo = 15522;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNIRB  JUAZEIRO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2918407 where ed257_i_codigo = 15667;
update escola.censoinstsuperior set ed257_c_nome = 'FATEC CRUZEIRO  PROF. WALDOMIRO MAY', ed257_i_tipo = 1, ed257_i_dependencia = 2, ed257_i_censomunic = 3513405 where ed257_i_codigo = 15680;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA DE ITAPETININGA  PROF.ANTONIO BELIZANDRO BARBOSA REZENDE', ed257_i_tipo = 1, ed257_i_dependencia = 2, ed257_i_censomunic = 3522307 where ed257_i_codigo = 15693;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA DR. THOMAZ NOVELINO', ed257_i_tipo = 1, ed257_i_dependencia = 2, ed257_i_censomunic = 3516200 where ed257_i_codigo = 15708;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA DE TATUI  PROF. WILSON ROBERTO RIBEIRO DE CAMARGO', ed257_i_tipo = 1, ed257_i_dependencia = 2, ed257_i_censomunic = 3554003 where ed257_i_codigo = 15803;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PITAGORAS UNOPAR DE JOAO PESSOA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2507507 where ed257_i_codigo = 15839;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE FABAD', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3538006 where ed257_i_codigo = 15922;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS DA SAUDE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2800308 where ed257_i_codigo = 16437;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ALFAUNIPAC DE CAPELINHA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3112307 where ed257_i_codigo = 16556;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DO VALE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1506138 where ed257_i_codigo = 16816;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNILAGOS', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4114401 where ed257_i_codigo = 17165;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO DE EDUCACAO SUPERIOR E INOVACAO PERSONA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 17269;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE FAPAF', ed257_i_tipo = 1, ed257_i_dependencia = 3, ed257_i_censomunic = 1717503 where ed257_i_codigo = 17291;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE 05 DE JULHO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2312908 where ed257_i_codigo = 17394;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO EDUCACIONAL DAS AMERICAS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4314902 where ed257_i_codigo = 17395;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS HUMANASEXATAS E DA SAUDE DO PIAUI', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2207702 where ed257_i_codigo = 17565;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PROF. WLADEMIR DOS SANTOS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3552205 where ed257_i_codigo = 17598;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE JK  GOIAS - PADRE BERNARDO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5215603 where ed257_i_codigo = 17651;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO BRASILEIRO DE ENSINO DESENVOLVIMENTO E PESQUISA DE SAO PAULO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 17672;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE FADAM', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2304400 where ed257_i_codigo = 17688;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE RETAMA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 1721000 where ed257_i_codigo = 17704;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADES FAMEP UNIDADE EUCLIDES DA CUNHA  BA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2910701 where ed257_i_codigo = 17758;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE ESTUDOS BIBLICOS INTERDISCIPLINARES', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 17892;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE NECTAR', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2611606 where ed257_i_codigo = 17894;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE EDUCACAO  E TECNOLOGIA DE SAO CARLOS', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3548906 where ed257_i_codigo = 17899;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO DE ENSINO SUPERIOR RIOGRANDENSE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4320107 where ed257_i_codigo = 18034;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA IPENO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4205407 where ed257_i_codigo = 18038;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO TECNOLOGICO POSITIVO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4106902 where ed257_i_codigo = 18064;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS E TECNOLOGIA PASCHOAL DANTAS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 18066;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE DIREITO TECH DE SAO PAULO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 18072;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE CESUMAR DE MARINGA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4115200 where ed257_i_codigo = 18149;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE INTEGRADA DE ARAPONGAS', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4101507 where ed257_i_codigo = 18152;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ESTACIO DE IMPERATRIZ  ESTACIO IMPERATRIZ', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2105302 where ed257_i_codigo = 18260;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE CRIATIVO DE CIENCIAS APLICADAS', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2604106 where ed257_i_codigo = 18276;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNIFATEC', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1302504 where ed257_i_codigo = 18304;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE GAMALIEL', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 5214606 where ed257_i_codigo = 18338;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE IDEAL DE ALTO HORIZONTE', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 5200555 where ed257_i_codigo = 18450;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNIFAHE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5005681 where ed257_i_codigo = 18463;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ALFAUNIPAC DE ARACUAI', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3103405 where ed257_i_codigo = 18520;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PITAGORAS DE ARAPIRACA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2700300 where ed257_i_codigo = 18626;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PITAGORAS DE JOAO PESSOA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2507507 where ed257_i_codigo = 18627;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE IMES', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3127701 where ed257_i_codigo = 18637;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE ADMINISTRACAO COMERCIO E EMPREENDEDORISMO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5007901 where ed257_i_codigo = 18679;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ALFAUNIPAC DE GUANHAES', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3128006 where ed257_i_codigo = 18692;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DYNAMUS DE CAMPINAS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3509502 where ed257_i_codigo = 18696;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE AGES DE JEREMOABO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2918100 where ed257_i_codigo = 18700;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE MARTINOPOLIS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3529203 where ed257_i_codigo = 18734;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE SUPREMO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3106200 where ed257_i_codigo = 19207;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE FASIPE DF', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 19219;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ITEQ ESCOLAS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 19252;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PITAGORAS DE BOM JESUS DA LAPA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2903904 where ed257_i_codigo = 19255;
update escola.censoinstsuperior set ed257_c_nome = 'ESCOLA DE POLITICAS PUBLICAS E GOVERNO DA FUNDACAO GETULIO VARGAS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 19320;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNINASSAU BRASILIA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 19334;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE INTEGRADA CESUMAR DE CURITIBA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4106902 where ed257_i_codigo = 19404;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE METROPOLITANA DE PETROLINA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2611101 where ed257_i_codigo = 19465;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DO VALE DO SAO FRANCISCO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2611101 where ed257_i_codigo = 19674;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE REPUBLICANA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 19727;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE RENOVACAO DE ARAPONGAS', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4101507 where ed257_i_codigo = 19736;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE RENOVACAO DE GUARAPUAVA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4109401 where ed257_i_codigo = 19737;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PITAGORAS UNOPAR DE GUANAMBI', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2911709 where ed257_i_codigo = 19780;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE FADAM DE MARACANAU', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2304400 where ed257_i_codigo = 19793;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE AUTONOMA DO BRASIL  CABO DE SANTO AGOSTINHO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2602902 where ed257_i_codigo = 19846;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DOM BOSCO PARANA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4113700 where ed257_i_codigo = 19853;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE BRAVIUM', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5221858 where ed257_i_codigo = 19878;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE INTEGRADA DE PESQUISA E EDUCACAO EM SAUDE DE SP', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 19879;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ICTQ/PGE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5201108 where ed257_i_codigo = 19909;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE MASTER', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2800308 where ed257_i_codigo = 19914;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE REDENTOR METROPOLITANA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3304144 where ed257_i_codigo = 20090;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE DIREITO CIENCIAS E TECNOLOGIA SANTA MARIA MADA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2704302 where ed257_i_codigo = 20099;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PADRE CICERO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2307304 where ed257_i_codigo = 20200;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADES JOAO PAULO II  RIO GRANDE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4315602 where ed257_i_codigo = 20563;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNIRB  CAMACARI', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2905701 where ed257_i_codigo = 20622;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE INNOVATE DE ANAPOLIS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5201108 where ed257_i_codigo = 20663;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE FAEL DE PORTO ALEGRE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4314902 where ed257_i_codigo = 21364;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE FAEL DE CURITIBA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4106902 where ed257_i_codigo = 21366;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE TEATICA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4108304 where ed257_i_codigo = 21412;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNA DE NOVA SERRANA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3145208 where ed257_i_codigo = 21415;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO SUPERIOR DA CONVENCAO NAC. DAS ASSEMBLEIAS DE DEUS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 21446;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNIVEST DE EDUCACAO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5103403 where ed257_i_codigo = 21681;
update escola.censoinstsuperior set ed257_c_nome = 'FASUL EDUCACIONAL EAD', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3163706 where ed257_i_codigo = 21757;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO DE PESQUISAS ENSINO E GESTAO EM SAUDE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4314902 where ed257_i_codigo = 21814;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE SEBRAE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 21826;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE TECNOLOGICA DE LIMOEIRO DO NORTE: LADISLAU PEREIRA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2307601 where ed257_i_codigo = 21857;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNIVERSO BRASILIA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 21861;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DO SERTAO DO ARARIPE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2615607 where ed257_i_codigo = 21891;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE MAURICIO DE NASSAU DE SANTO ANDRE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3547809 where ed257_i_codigo = 21893;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE MAURICIO DE NASSAU DE SOROCABA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3552205 where ed257_i_codigo = 21894;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE MAURICIO DE NASSAU DE BLUMENAU', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4202404 where ed257_i_codigo = 21898;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE MAURICIO DE NASSAU DE PELOTAS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4314407 where ed257_i_codigo = 21899;
update escola.censoinstsuperior set ed257_c_nome = 'FUNDACAO ESCOLA LINCE KEMPIM', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1101500 where ed257_i_codigo = 21935;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE TUCURUI', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1508100 where ed257_i_codigo = 21950;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE POSITIVO JOINVILLE', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 4209102 where ed257_i_codigo = 21951;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO UNIAO DAS FACULDADES AMERICANAS', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 5208004 where ed257_i_codigo = 21953;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE EDUFOR DE SALVADOR', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2927408 where ed257_i_codigo = 21978;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PRIME', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5002704 where ed257_i_codigo = 21999;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS DA SAUDE DO GRUPO HOSPITALAR CONCEICAO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4314902 where ed257_i_codigo = 22090;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE POLITECNICA DE CAMPO GRANDE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5002704 where ed257_i_codigo = 22097;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS JURIDICAS ANHANGUERA DE ARAPIRACA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2700300 where ed257_i_codigo = 22123;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PITAGORAS UNOPAR DE BRUMADO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2904605 where ed257_i_codigo = 22124;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS JURIDICAS DE BOM JESUS DA LAPA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2903904 where ed257_i_codigo = 22125;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS JURIDICAS DE JABOATAO DOS GUARARAPES', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2607901 where ed257_i_codigo = 22142;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS JURIDICAS DE JAU', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3525300 where ed257_i_codigo = 22146;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS JURIDICAS DE BELO JARDIM', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2601706 where ed257_i_codigo = 22147;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS JURIDICAS DE JACOBINA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2917508 where ed257_i_codigo = 22154;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE  UNINORTE  BARCARENA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1501303 where ed257_i_codigo = 22173;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE  UNINORTE  ALTAMIRA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1500602 where ed257_i_codigo = 22174;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PITAGORAS DE RIO VERDE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5218805 where ed257_i_codigo = 22181;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS JURIDICAS DE CRUZ DAS ALMAS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2909802 where ed257_i_codigo = 22185;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO DE EDUCACAO SUPERIOR SANTO AGOSTINHO', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 2914802 where ed257_i_codigo = 22199;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNIVERITAS UNIVERSUS VERITAS DE SAO GONCALO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3304904 where ed257_i_codigo = 22214;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNIVERITAS UNIVERSUS VERITAS SAO JOSE DO RIO PRETO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3549805 where ed257_i_codigo = 22224;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS JURIDICAS DE ALTAMIRA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1500602 where ed257_i_codigo = 22232;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS JURIDICAS DE ASSIS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3504008 where ed257_i_codigo = 22237;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DO AMAZONAS DE ENSINO PESQUISA E INOVACAO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1302603 where ed257_i_codigo = 22246;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNIBRAS DO PARA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1505437 where ed257_i_codigo = 22252;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE PALMEIRAS DE GOIAS  FACMAIS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5215702 where ed257_i_codigo = 22262;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DO VALE DO JAGUARIBE MOSSORO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2408003 where ed257_i_codigo = 22264;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE SANTO ANTONIO DE FEIRA DE SANTANA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2910800 where ed257_i_codigo = 22310;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO SANTO AGOSTINHO DE ENSINO SUPERIOR  ISA', ed257_i_tipo = 2, ed257_i_dependencia = 4, ed257_i_censomunic = 3106200 where ed257_i_codigo = 22321;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNIRB  MACEIO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2704302 where ed257_i_codigo = 22420;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ESAMC GOIANIA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5208707 where ed257_i_codigo = 22424;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADES FAMEP UNIDADE PARNAIBA  PI', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2207702 where ed257_i_codigo = 22433;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO SUPERIOR DE CIENCIAS DA SAUDE CARLOS CHAGAS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3304557 where ed257_i_codigo = 22449;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ATITUDE DE EDUCACAO CONTINUADA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3515509 where ed257_i_codigo = 22452;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNINTESE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4317509 where ed257_i_codigo = 22453;
update escola.censoinstsuperior set ed257_c_nome = 'FGW  FACULDADE DE GESTAO WOLI', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3104007 where ed257_i_codigo = 22455;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNIRB  CIDADE DE FORTALEZA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2304400 where ed257_i_codigo = 22462;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADES FAMEP UNIDADE IRARA  BA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2914505 where ed257_i_codigo = 22521;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADES INTEGRADAS DA AMERICA DO SUL', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5204508 where ed257_i_codigo = 22592;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ANCLIVEPA DE GESTAO E HUMANOLOGIA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 22603;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ALVORADA DE SAUDE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 22605;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DAMASIO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 22606;
update escola.censoinstsuperior set ed257_c_nome = 'FORS  FACULDADE DE EDUCACAO E TECNOLOGIA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3557105 where ed257_i_codigo = 22625;
update escola.censoinstsuperior set ed257_c_nome = 'CLARETIANO  FACULDADE DE BOA VISTA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1400100 where ed257_i_codigo = 22628;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO DE ESTUDOS EM DIREITO E NEGOCIOS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3106200 where ed257_i_codigo = 22629;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE FASIPE DE RONDONOPOLIS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5107602 where ed257_i_codigo = 22634;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE SAO FRANCISCO XAVIER', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3131307 where ed257_i_codigo = 22636;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE METROPOLITANA DE TEFE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1304203 where ed257_i_codigo = 22643;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO DE TECNOLOGIA E LIDERANCA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 22651;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE SEB DE RIBEIRAO PRETO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3543402 where ed257_i_codigo = 22659;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNICENTRAL', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5102678 where ed257_i_codigo = 22684;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PITAGORAS UNOPAR DE CHAPECO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4204202 where ed257_i_codigo = 22702;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PITAGORAS UNOPAR DE ITAJUBA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3132404 where ed257_i_codigo = 22707;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PITAGORAS UNOPAR DE QUIXERAMOBIM', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2311405 where ed257_i_codigo = 22710;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PITAGORAS UNOPAR DE MURIAE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3143906 where ed257_i_codigo = 22712;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PITAGORAS UNOPAR DE CANINDE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2302800 where ed257_i_codigo = 22715;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNISUL DE ITAJAI', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4208203 where ed257_i_codigo = 22736;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PITAGORAS UNOPAR DE CALDAS NOVAS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5204508 where ed257_i_codigo = 22738;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO SOCIESC DE ITAJAI', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4208203 where ed257_i_codigo = 22739;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE MASTER DO PARA  FAMAP XINGUARA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1508407 where ed257_i_codigo = 22741;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE MASTER DO PARA  FAMAP TUCUMA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1508084 where ed257_i_codigo = 22742;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE RIO PARNAIBA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2112209 where ed257_i_codigo = 22753;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA E CIENCIAS  FTC CARUARU', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2604106 where ed257_i_codigo = 22762;
update escola.censoinstsuperior set ed257_c_nome = 'ESCOLA SUPERIOR DE EDUCACAO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3106200 where ed257_i_codigo = 22764;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE GARCA BRANCA PANTANAL', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5103403 where ed257_i_codigo = 22775;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE VALE DOS CARAJAS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1505536 where ed257_i_codigo = 22777;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA E CIENCIAS  FTC CAMACARI', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2905701 where ed257_i_codigo = 22787;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNIABA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 22872;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE  DO SERTAO CENTRAL  EAD', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2308351 where ed257_i_codigo = 22911;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS MEDICAS DE MARICA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3302700 where ed257_i_codigo = 22917;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS JURIDICAS DE SANTA MARIA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4316907 where ed257_i_codigo = 22946;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE SENAI DE CONSTRUCAO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5002704 where ed257_i_codigo = 22992;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA E CIENCIAS  FTC PARNAMIRIM', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2403251 where ed257_i_codigo = 22996;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA E CIENCIAS  FTC ITAPIPOCA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2306405 where ed257_i_codigo = 22999;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA E CIENCIAS  FTC CAUCAIA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2303709 where ed257_i_codigo = 23000;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA E CIENCIAS  FTC N. SRA. DO SOCORRO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2804805 where ed257_i_codigo = 23002;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE ENSINO SUPERIOR BRASILEIRA  FACULDADE FEBRAS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3205200 where ed257_i_codigo = 23025;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE METROPOLITANA DE DIAS D''AVILA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2910057 where ed257_i_codigo = 23066;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO SOCIESC DE JARAGUA DO SUL', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4208906 where ed257_i_codigo = 23095;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE SANTO ANTONIO  SJC', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3549904 where ed257_i_codigo = 23096;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE IBMEC DE BRASILIA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5300108 where ed257_i_codigo = 23097;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PITAGORAS UNOPAR DE JUAZEIRO DO NORTE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2307304 where ed257_i_codigo = 23100;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE ENGENHARIA PITAGORAS DE SANTAREM', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1506807 where ed257_i_codigo = 23101;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE ENGENHARIA PITAGORAS DE SOBRAL', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2312908 where ed257_i_codigo = 23102;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE SANTA TERESA D''AVILA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2211001 where ed257_i_codigo = 23110;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO DE DESENVOLVIMENTO E APRENDIZAGEM  IDEA SAO LUIZ', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2111300 where ed257_i_codigo = 23130;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DO COMERCIO DE SAO PAULO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 23147;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA E CIENCIAS  FTC PORTO SEGURO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2925303 where ed257_i_codigo = 23155;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE BARAO DE JEQUIRICA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2932903 where ed257_i_codigo = 23159;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO DE ENSINO SUPERIOR DE DIVINOPOLIS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3122306 where ed257_i_codigo = 23164;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO DE ESTUDOS SUPERIORES DE JATAI', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5211909 where ed257_i_codigo = 23168;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE EDUCACAO SUPERIOR DE POUSO ALEGRE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3152501 where ed257_i_codigo = 23172;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE EDUCACAO SUPERIOR DE SETE LAGOAS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3167202 where ed257_i_codigo = 23176;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE EDUCACAO SUPERIOR DE CATALAO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5205109 where ed257_i_codigo = 23177;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO DE ENSINO SUPERIOR DE CATALAO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5205109 where ed257_i_codigo = 23179;
update escola.censoinstsuperior set ed257_c_nome = 'ESCOLA SUPERIOR DE CATALAO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5205109 where ed257_i_codigo = 23180;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO DE EDUCACAO SUPERIOR UNISUL DE ITAJAI', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4208203 where ed257_i_codigo = 23201;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE SOCIESC DE EDUCACAO DE SAO BENTO DO SUL', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4215802 where ed257_i_codigo = 23202;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO DE ENSINO SUPERIOR SOCIESC DE SAO BENTO DO SUL', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4215802 where ed257_i_codigo = 23203;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE GENNARI & PEARTREE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3502804 where ed257_i_codigo = 23215;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE PARAISO FORTALEZA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2304400 where ed257_i_codigo = 23218;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE SAO JUDAS DE GUARULHOS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3518800 where ed257_i_codigo = 23228;
update escola.censoinstsuperior set ed257_c_nome = 'INSTITUTO UNIVERSITARIO SAO JUDAS DE SAO BERNARDO DO CAMPO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3548708 where ed257_i_codigo = 23241;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE SAO JUDAS DE SAO BERNARDO DO CAMPO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3548708 where ed257_i_codigo = 23261;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE AVANTIS DE FLORIANOPOLIS', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4205407 where ed257_i_codigo = 23342;
update escola.censoinstsuperior set ed257_c_nome = 'CENTRO DE ENSINO SUPERIOR SOCIESC DE JARAGUA DO SUL', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4208906 where ed257_i_codigo = 23358;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNINORTE TAILANDIA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1507953 where ed257_i_codigo = 23382;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNIBRAS DO MARANHAO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2109908 where ed257_i_codigo = 23389;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE CENTRO SAO PAULO', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3550308 where ed257_i_codigo = 23409;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA DE SUMARE', ed257_i_tipo = 1, ed257_i_dependencia = 2, ed257_i_censomunic = 3552403 where ed257_i_codigo = 23867;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNINORTE MARABA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 1504208 where ed257_i_codigo = 24268;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE BOA ESPERANCA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 3107109 where ed257_i_codigo = 24282;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ANDREOTTI DE MARINGA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 4115200 where ed257_i_codigo = 24290;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE AGES DE MEDICINA DE IRECE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2914604 where ed257_i_codigo = 24443;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE UNICESUMAR DE CORUMBA', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 5003207 where ed257_i_codigo = 24488;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE ESTACIO DE CANINDE', ed257_i_tipo = 1, ed257_i_dependencia = 4, ed257_i_censomunic = 2302800 where ed257_i_codigo = 24509;
update escola.censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA DE MATAO', ed257_i_tipo = 1, ed257_i_dependencia = 2, ed257_i_censomunic = 3529302 where ed257_i_codigo = 24672;
update escola.censoinstsuperior set ed257_c_nome = 'UNIVERSIDADE FEDERAL DE CATALAO', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 5205109 where ed257_i_codigo = 25274;
update escola.censoinstsuperior set ed257_c_nome = 'UNIVERSIDADE FEDERAL DE JATAI', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 5211909 where ed257_i_codigo = 25282;
update escola.censoinstsuperior set ed257_c_nome = 'UNIVERSIDADE FEDERAL DE RONDONOPOLIS', ed257_i_tipo = 1, ed257_i_dependencia = 1, ed257_i_censomunic = 5107602 where ed257_i_codigo = 25352;

SQL
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from escola.censoinstsuperior where ed257_i_codigo in (25444, 24463, 24046, 24530, 21998, 23242, 23181, 22985, 23890, 23224, 23244, 23776, 24457, 23875, 18039, 23866, 24471, 23381, 22866, 24300, 24470, 24025, 24026, 24055, 22748, 23109, 20584, 23723, 22769, 24287, 23252, 24525, 23038, 24275, 22178, 23973, 22966, 24066, 17783, 23026, 18751, 25157, 24459, 23455, 24205, 24700, 24687, 23946, 23331, 23707, 23893, 23322, 24196, 23023, 23280, 24922, 25613, 24980, 24399, 24464, 22703, 24446, 24245, 23394, 22425, 23924, 25377, 22771, 21220, 23118, 23908, 22759, 23912, 24392, 23842, 23163, 24884, 24214, 23273, 24403, 24168, 23117, 23028, 22177, 22716, 23798, 23877, 23843, 24391, 22650, 22660, 23236, 23333, 23894, 23146, 24400, 23332, 24405, 23245, 24211, 24074, 23733);

SQL
        );
    }
}
