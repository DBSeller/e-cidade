<?php

use Classes\PostgresMigration;

class M17955AlteraInstSuperiorCenso2021 extends PostgresMigration
{

    public function up()
    {
        $this->inclusaoUp();
        $this->alteracaoNomeUp();
        $this->alteracaoSituacaoUp();
        $this->alteracaoCodInstituicaoUp();
        $this->exclusaoUp();

    }

    public function down()
    {
        $this->inclusaoDown();
        $this->alteracaoNomeDown();
        $this->alteracaoSituacaoDown();
    }

    /**
     * UP
     */

    private function inclusaoUp()
    {
        $sql = "insert into censoinstsuperior values (12410, 'INSTITUTO SUPERIOR DO MINISTERIO PUBLICO', 4, 4, 3304557, 'ATIVA');
       insert into censoinstsuperior values (15667, 'FACULDADE UNIRB - JUAZEIRO', 4, 4, 2918407, 'ATIVA');
       insert into censoinstsuperior values (16437, 'FACULDADE DE CIÊNCIAS DA SAÚDE', 4, 4, 2800308, 'ATIVA');
       insert into censoinstsuperior values (17395, 'INSTITUTO EDUCACIONAL DAS AMÉRICAS', 4, 4, 4314902, 'ATIVA');
       insert into censoinstsuperior values (17765, 'FACULDADE SANTA MARIA', 4, 4, 2108603, 'ATIVA');
       insert into censoinstsuperior values (17892, 'FACULDADE DE ESTUDOS BÍBLICOS INTERDISCIPLINARES', 4, 4, 5300108, 'ATIVA');
       insert into censoinstsuperior values (18066, 'FACULDADE DE CIÊNCIAS E TECNOLOGIA PASCHOAL DANTAS', 4, 4, 3550308, 'ATIVA');
       insert into censoinstsuperior values (18734, 'FACULDADE SOLIDÁRIA DE MARTINÓPOLIS', 4, 4, 3529203, 'ATIVA');
       insert into censoinstsuperior values (18880, 'SISTEMA EDUCACIONAL CORPORATIVO DA PETROBRAS', 4, 4, 3304557, 'ATIVA');
       insert into censoinstsuperior values (19207, 'FACULDADE IDDE', 4, 4, 3106200, 'ATIVA');
       insert into censoinstsuperior values (19299, 'FACULDADE PITAGORAS DE PETROLINA', 4, 4, 2611101, 'ATIVA');
       insert into censoinstsuperior values (19737, 'FACULDADE RENOVAÇÃO DE GUARAPUAVA', 4, 4, 4109401, 'ATIVA');
       insert into censoinstsuperior values (19878, 'FACULDADE JK  GOIÁS - VALPARAÍSO', 4, 4, 5221858, 'ATIVA');
       insert into censoinstsuperior values (19879, 'FACULDADE INTEGRADA DE PESQUISA E EDUCAÇÃO EM SAÚDE DE SP', 4, 4, 3550308, 'ATIVA');
       insert into censoinstsuperior values (20074, 'INSTITUTO BRASILEIRO DE COACHING', 4, 4, 5208707, 'ATIVA');
       insert into censoinstsuperior values (20099, 'FACULDADE DE DIREITO, CIÊNCIAS E TECNOLOGIA SANTA MARIA MADA', 4, 4, 2704302, 'ATIVA');
       insert into censoinstsuperior values (20291, 'FACULDADE DO VALE DO CAJU', 4, 4, 2309607, 'ATIVA');
       insert into censoinstsuperior values (20321, 'FACULDADE IMPACTO', 4, 4, 2704302, 'ATIVA');
       insert into censoinstsuperior values (20438, 'FACULDADE INTERCULTURAL DA AMAZONIA', 4, 4, 1501402, 'ATIVA');
       insert into censoinstsuperior values (20534, 'CENTRO DE ENSINO SUPERIOR RIOGRANDENSE GUAPORE', 4, 4, 4309407, 'ATIVA');
       insert into censoinstsuperior values (20663, 'FACULDADE INNOVATE DE ANÁPOLIS', 4, 4, 5201108, 'ATIVA');
       insert into censoinstsuperior values (20709, 'FACULDADE FECOMERCIO RORAIMA', 4, 4, 1400100, 'ATIVA');
       insert into censoinstsuperior values (21636, 'FACULDADE TERRA BRASILIS', 4, 4, 5100359, 'ATIVA');
       insert into censoinstsuperior values (21681, 'FACULDADE UNIVEST DE EDUCAÇÃO', 4, 4, 5103403, 'ATIVA');
       insert into censoinstsuperior values (21816, 'FACULDADE UNINTA', 4, 4, 2312908, 'ATIVA');
       insert into censoinstsuperior values (21850, 'INSTITUTO INTERNACIONAL DE ESTUDOS EM SAUDE', 4, 4, 3543402, 'ATIVA');
       insert into censoinstsuperior values (21857, 'FACULDADE TECNOLÓGICA DE LIMOEIRO DO NORTE: LADISLAU PEREIRA', 4, 4, 2307601, 'ATIVA');
       insert into censoinstsuperior values (21861, 'FACULDADE UNIVERSO BRASÍLIA', 4, 4, 5300108, 'ATIVA');
       insert into censoinstsuperior values (21885, 'FACULDADE IBAM', 4, 4, 3304557, 'ATIVA');
       insert into censoinstsuperior values (21891, 'FACULDADE DO SERTÃO DO ARARIPE', 4, 4, 2615607, 'ATIVA');
       insert into censoinstsuperior values (21893, 'FACULDADE MAURÍCIO DE NASSAU DE SANTO ANDRÉ', 4, 4, 3547809, 'ATIVA');
       insert into censoinstsuperior values (21894, 'FACULDADE MAURÍCIO DE NASSAU DE SOROCABA', 4, 4, 3552205, 'ATIVA');
       insert into censoinstsuperior values (21898, 'FACULDADE MAURÍCIO DE NASSAU DE BLUMENAU', 4, 4, 4202404, 'ATIVA');
       insert into censoinstsuperior values (21899, 'FACULDADE MAURÍCIO DE NASSAU DE PELOTAS', 4, 4, 4314407, 'ATIVA');
       insert into censoinstsuperior values (21926, 'FACULDADE METROPOLITANA DE JOINVILLE', 4, 4, 4209102, 'ATIVA');
       insert into censoinstsuperior values (21928, 'FACULDADE GRAU S ENSINO SUPERIOR', 4, 4, 2611606, 'ATIVA');
       insert into censoinstsuperior values (21929, 'FACULDADE DOCTUM DA ZONA NORTE DE JUIZ DE FORA', 4, 4, 3136702, 'ATIVA');
       insert into censoinstsuperior values (21935, 'FUNDAÇÃO ESCOLA LINCE KEMPIM', 4, 4, 1101500, 'ATIVA');
       insert into censoinstsuperior values (21939, 'FACULDADE METROPOLITANA DE NOVO HAMBURGO', 4, 4, 4313409, 'ATIVA');
       insert into censoinstsuperior values (21978, 'FACULDADE BAIANA DE TECNOLOGIA E CIÊNCIAS', 4, 4, 2927408, 'ATIVA');
       insert into censoinstsuperior values (21999, 'FACULDADE PAN-AMERICANA DE ADMINISTRAÇÃO E DIREITO DE CAMPO', 4, 4, 5002704, 'ATIVA');
       insert into censoinstsuperior values (22017, 'FACULDADE NOSSA SENHORA APARECIDA', 4, 4, 5212501, 'ATIVA');
       insert into censoinstsuperior values (22018, 'FACULDADE DE TECNOLOGIA DE SINOP', 4, 4, 5107909, 'ATIVA');
       insert into censoinstsuperior values (22028, 'FACULDADE CTA', 4, 4, 3550308, 'ATIVA');
       insert into censoinstsuperior values (22090, 'FACULDADE DE CIÊNCIAS DA SAÚDE DO GRUPO HOSPITALAR CONCEIÇÃO', 4, 4, 4314902, 'ATIVA');
       insert into censoinstsuperior values (22097, 'FACULDADE POLITÉCNICA DE CAMPO GRANDE', 4, 4, 5002704, 'ATIVA');
       insert into censoinstsuperior values (22125, 'FACULDADE DE CIÊNCIAS JURÍDICAS DE BOM JESUS DA LAPA', 4, 4, 2903904, 'ATIVA');
       insert into censoinstsuperior values (22139, 'FACULDADE SANTO ANTONIO DE ITABUNA', 4, 4, 2914802, 'ATIVA');
       insert into censoinstsuperior values (22142, 'FACULDADE DE CIÊNCIAS JURÍDICAS DE JABOATÃO DOS GUARARAPES', 4, 4, 2607901, 'ATIVA');
       insert into censoinstsuperior values (22146, 'FACULDADE DE CIÊNCIAS JURÍDICAS DE JAÚ', 4, 4, 3525300, 'ATIVA');
       insert into censoinstsuperior values (22147, 'FACULDADE DE CIÊNCIAS JURÍDICAS DE BELO JARDIM', 4, 4, 2601706, 'ATIVA');
       insert into censoinstsuperior values (22154, 'FACULDADE DE CIÊNCIAS JURÍDICAS DE JACOBINA', 4, 4, 2917508, 'ATIVA');
       insert into censoinstsuperior values (22171, 'FACULDADE REGIONAL DE MINAS GERAIS', 4, 4, 3106606, 'ATIVA');
       insert into censoinstsuperior values (22173, 'FACULDADE - UNINORTE  BARCARENA', 4, 4, 1501303, 'ATIVA');
       insert into censoinstsuperior values (22174, 'FACULDADE - UNINORTE  ALTAMIRA', 4, 4, 1500602, 'ATIVA');
       insert into censoinstsuperior values (22181, 'FACULDADE PITÁGORAS DE RIO VERDE', 4, 4, 5218805, 'ATIVA');
       insert into censoinstsuperior values (22185, 'FACULDADE DE CIÊNCIAS JURÍDICAS DE CRUZ DAS ALMAS', 4, 4, 2909802, 'ATIVA');
       insert into censoinstsuperior values (22193, 'FACULDADE SERRA DOURADA', 4, 4, 1500602, 'ATIVA');
       insert into censoinstsuperior values (22195, 'INSTITUTO SERRA DOURADA', 4, 4, 1500602, 'ATIVA');
       insert into censoinstsuperior values (22196, 'FACULDADE DE DIREITO SERRA DOURADA', 4, 4, 1500602, 'ATIVA');
       insert into censoinstsuperior values (22212, 'FACULDADE UNAMA DE PARAUAPEBAS', 4, 4, 1505536, 'ATIVA');
       insert into censoinstsuperior values (22213, 'FACULDADE UNAMA DE CASTANHAL', 4, 4, 1502400, 'ATIVA');
       insert into censoinstsuperior values (22214, 'FACULDADE UNIVERITAS UNIVERSUS VERITAS DE SÃO GONÇALO', 4, 4, 3304904, 'ATIVA');
       insert into censoinstsuperior values (22216, 'FACULDADE UNIVERITAS UNIVERSUS VERITAS DE OSASCO', 4, 4, 3534401, 'ATIVA');
       insert into censoinstsuperior values (22218, 'FACULDADE UNIVERITAS UNIVERSUS VERITAS DE MONTES CLAROS', 4, 4, 3143302, 'ATIVA');
       insert into censoinstsuperior values (22222, 'FACULDADE UNIVERITAS UNIVERSUS VERITAS DE PIRACICABA', 4, 4, 3538709, 'ATIVA');
       insert into censoinstsuperior values (22224, 'FACULDADE UNIVERITAS UNIVERSUS VERITAS SÃO JOSÉ DO RIO PRETO', 4, 4, 3549805, 'ATIVA');
       insert into censoinstsuperior values (22232, 'FACULDADE DE CIÊNCIAS JURÍDICAS DE ALTAMIRA', 4, 4, 1500602, 'ATIVA');
       insert into censoinstsuperior values (22237, 'FACULDADE DE CIÊNCIAS JURÍDICAS DE ASSIS', 4, 4, 3504008, 'ATIVA');
       insert into censoinstsuperior values (22246, 'FACULDADE DO AMAZONAS DE ENSINO, PESQUISA E INOVAÇÃO', 4, 4, 1302603, 'ATIVA');
       insert into censoinstsuperior values (22252, 'FACULDADE GERALDO VELOSO', 4, 4, 1505437, 'ATIVA');
       insert into censoinstsuperior values (22262, 'FACULDADE DE PALMEIRAS DE GOIÁS - FACMAIS', 4, 4, 5215702, 'ATIVA');
       insert into censoinstsuperior values (22264, 'FACULDADE DO VALE DO JAGUARIBE MOSSORÓ', 4, 4, 2408003, 'ATIVA');
       insert into censoinstsuperior values (22310, 'FACULDADE SANTO ANTÔNIO DE FEIRA DE SANTANA', 4, 4, 2910800, 'ATIVA');
       insert into censoinstsuperior values (22314, 'FACULDADE NOVE DE JULHO GUARULHOS', 4, 4, 3518800, 'ATIVA');
       insert into censoinstsuperior values (22329, 'FACULDADE UNINTA FORTALEZA', 4, 4, 2304400, 'ATIVA');
       insert into censoinstsuperior values (22405, 'SVT FACULDADE DE ENSINO SUPERIOR', 4, 4, 2111300, 'ATIVA');
       insert into censoinstsuperior values (22424, 'FACULDADE ESAMC GOIÂNIA', 4, 4, 5208707, 'ATIVA');
       insert into censoinstsuperior values (22433, 'FACULDADE DEXTER', 4, 4, 2207702, 'ATIVA');
       insert into censoinstsuperior values (22441, 'FACULDADE ENSIN.E', 4, 4, 3136702, 'ATIVA');
       insert into censoinstsuperior values (22443, 'FACULDADE ALFREDO NASSER DE CASA NOVA', 4, 4, 2907202, 'ATIVA');
       insert into censoinstsuperior values (22449, 'INSTITUTO SUPERIOR DE CIÊNCIAS DA SAÚDE CARLOS CHAGAS', 4, 4, 3304557, 'ATIVA');
       insert into censoinstsuperior values (22452, 'FACULDADE ATITUDE DE EDUCAÇÃO CONTINUADA', 4, 4, 3515509, 'ATIVA');
       insert into censoinstsuperior values (22453, 'FACULDADE DOURADO STIELER', 4, 4, 4317509, 'ATIVA');
       insert into censoinstsuperior values (22455, 'FGW - FACULDADE DE GESTÃO WOLI', 4, 4, 3104007, 'ATIVA');
       insert into censoinstsuperior values (22456, 'FACULDADE REALIZA', 4, 4, 5201405, 'ATIVA');
       insert into censoinstsuperior values (22462, 'FACULDADE UNIRB - CIDADE DE FORTALEZA', 4, 4, 2304400, 'ATIVA');
       insert into censoinstsuperior values (22470, 'FACULDADE UNIVERSALIS', 4, 4, 2800308, 'ATIVA');
       insert into censoinstsuperior values (22472, 'FACULDADE ARQUIDIOCESANA DE PIRAPORA', 4, 4, 3151206, 'ATIVA');
       insert into censoinstsuperior values (22521, 'FACULDADE IRARÁ', 4, 4, 2914505, 'ATIVA');
       insert into censoinstsuperior values (22527, 'FACULDADE CLEBER LEITE', 4, 4, 3547809, 'ATIVA');
       insert into censoinstsuperior values (22566, 'FACULDADE METROPOLITANA DE ITACOATIARA', 4, 4, 1301902, 'ATIVA');
       insert into censoinstsuperior values (22578, 'FACULDADE CENSUPEG', 4, 4, 4209102, 'ATIVA');
       insert into censoinstsuperior values (22592, 'FACULDADES INTEGRADAS DA AMÉRICA DO SUL', 4, 4, 5204508, 'ATIVA');
       insert into censoinstsuperior values (22599, 'FACULDADE CECAPE', 4, 4, 2307304, 'ATIVA');
       insert into censoinstsuperior values (22605, 'FACULDADE ALVORADA DE SAÚDE', 4, 4, 3550308, 'ATIVA');
       insert into censoinstsuperior values (22606, 'FACULDADE DAMÁSIO', 4, 4, 3550308, 'ATIVA');
       insert into censoinstsuperior values (22625, 'FORS - FACULDADE DE EDUCAÇÃO E TECNOLOGIA', 4, 4, 3557105, 'ATIVA');
       insert into censoinstsuperior values (22628, 'CLARETIANO - FACULDADE DE BOA VISTA', 4, 4, 1400100, 'ATIVA');
       insert into censoinstsuperior values (22629, 'CENTRO DE ESTUDOS EM DIREITO E NEGÓCIOS', 4, 4, 3106200, 'ATIVA');
       insert into censoinstsuperior values (22634, 'FACULDADE FASIPE DE RONDONÓPOLIS', 4, 4, 5107602, 'ATIVA');
       insert into censoinstsuperior values (22636, 'FACULDADE SÃO FRANCISCO XAVIER', 4, 4, 3131307, 'ATIVA');
       insert into censoinstsuperior values (22640, 'FACULDADE METROPOLITANA DE COARI', 4, 4, 1301209, 'ATIVA');
       insert into censoinstsuperior values (22641, 'FACULDADE METROPOLITANA DE PARINTINS', 4, 4, 1303403, 'ATIVA');
       insert into censoinstsuperior values (22643, 'FACULDADE METROPOLITANA DE TEFÉ', 4, 4, 1304203, 'ATIVA');
       insert into censoinstsuperior values (22644, 'FACULDADE DO CEFI', 4, 4, 4314902, 'ATIVA');
       insert into censoinstsuperior values (22651, 'FACULDADE PAULISTANA UNIDAS', 4, 4, 3550308, 'ATIVA');
       insert into censoinstsuperior values (22659, 'FACULDADE SEB DE RIBEIRÃO PRETO', 4, 4, 3543402, 'ATIVA');
       insert into censoinstsuperior values (22684, 'FACULDADE DE CIÊNCIAS SOCIAIS E TECNOLOGIA', 4, 4, 5102678, 'ATIVA');
       insert into censoinstsuperior values (22702, 'FACULDADE PITÁGORAS UNOPAR DE CHAPECÓ', 4, 4, 4204202, 'ATIVA');
       insert into censoinstsuperior values (22707, 'FACULDADE PITÁGORAS UNOPAR DE ITAJUBÁ', 4, 4, 3132404, 'ATIVA');
       insert into censoinstsuperior values (22710, 'FACULDADE PITÁGORAS UNOPAR DE QUIXERAMOBIM', 4, 4, 2311405, 'ATIVA');
       insert into censoinstsuperior values (22712, 'FACULDADE PITÁGORAS UNOPAR DE MURIAÉ', 4, 4, 3143906, 'ATIVA');
       insert into censoinstsuperior values (22713, 'FACULDADE DO OESTE POTIGUAR', 4, 4, 2412500, 'ATIVA');
       insert into censoinstsuperior values (22715, 'FACULDADE PITÁGORAS UNOPAR DE CANINDÉ', 4, 4, 2302800, 'ATIVA');
       insert into censoinstsuperior values (22736, 'FACULDADE SOCIESC DE ITAJAÍ', 4, 4, 4208203, 'ATIVA');
       insert into censoinstsuperior values (22738, 'FACULDADE PITÁGORAS UNOPAR DE CALDAS NOVAS', 4, 4, 5204508, 'ATIVA');
       insert into censoinstsuperior values (22739, 'INSTITUTO SOCIESC DE ITAJAÍ', 4, 4, 4208203, 'ATIVA');
       insert into censoinstsuperior values (22741, 'FACULDADE MASTER DO PARÁ - FAMAP XINGUARA', 4, 4, 1508407, 'ATIVA');
       insert into censoinstsuperior values (22742, 'FACULDADE MASTER DO PARÁ - FAMAP TUCUMÃ', 4, 4, 1508084, 'ATIVA');
       insert into censoinstsuperior values (22746, 'FACULDADE FLEMING DE OSASCO', 4, 4, 3534401, 'ATIVA');
       insert into censoinstsuperior values (22753, 'FACULDADE RIO PARNAÍBA', 4, 4, 2112209, 'ATIVA');
       insert into censoinstsuperior values (22758, 'FACULDADE UNA DE ITABIRA', 4, 4, 3131703, 'ATIVA');
       insert into censoinstsuperior values (22760, 'FACULDADE FLEMING CERQUILHO', 4, 4, 3511508, 'ATIVA');
       insert into censoinstsuperior values (22762, 'FACULDADE DE TECNOLOGIA E CIÊNCIAS - FTC CARUARU', 4, 4, 2604106, 'ATIVA');
       insert into censoinstsuperior values (22763, 'FACULDADE SANTA CASA', 4, 4, 2927408, 'ATIVA');
       insert into censoinstsuperior values (22764, 'ESCOLA SUPERIOR DE EDUCAÇÃO', 4, 4, 3106200, 'ATIVA');
       insert into censoinstsuperior values (22775, 'FACULDADE GARÇA BRANCA PANTANAL', 4, 4, 5103403, 'ATIVA');
       insert into censoinstsuperior values (22777, 'FACULDADE VALE DOS CARAJÁS', 4, 4, 1505536, 'ATIVA');
       insert into censoinstsuperior values (22787, 'FACULDADE DE TECNOLOGIA E CIÊNCIAS - FTC CAMAÇARI', 4, 4, 2905701, 'ATIVA');
       insert into censoinstsuperior values (22811, 'COMPLEXO DE ENSINO SUPERIOR DE PALMAS', 4, 4, 1721000, 'ATIVA');
       insert into censoinstsuperior values (22814, 'FACULDADE AUDEN EDUCACIONAL', 4, 4, 3550308, 'ATIVA');
       insert into censoinstsuperior values (22862, 'FRANKLINCOVEY BUSINESS SCHOOL', 4, 4, 3550308, 'ATIVA');
       insert into censoinstsuperior values (22872, 'FACULDADE ESDRAS DANTAS', 4, 4, 5300108, 'ATIVA');
       insert into censoinstsuperior values (22911, 'FACULDADE  DO SERTÃO CENTRAL  EAD', 4, 4, 2308351, 'ATIVA');
       insert into censoinstsuperior values (22917, 'FACULDADE DE CIÊNCIAS MÉDICAS DE MARICÁ', 4, 4, 3302700, 'ATIVA');
       insert into censoinstsuperior values (22946, 'FACULDADE DE CIÊNCIAS JURÍDICAS DE SANTA MARIA', 4, 4, 4316907, 'ATIVA');
       insert into censoinstsuperior values (22950, 'FACULDADE DO ESTADO DO RIO DE JANEIRO', 4, 4, 3301009, 'ATIVA');
       insert into censoinstsuperior values (22975, 'UNICORP FACULDADES', 4, 4, 2507507, 'ATIVA');
       insert into censoinstsuperior values (22992, 'FACULDADE SENAI DE CONSTRUÇÃO', 4, 4, 5002704, 'ATIVA');
       insert into censoinstsuperior values (22996, 'FACULDADE DE TECNOLOGIA E CIÊNCIAS - FTC PARNAMIRIM', 4, 4, 2403251, 'ATIVA');
       insert into censoinstsuperior values (22999, 'FACULDADE DE TECNOLOGIA E CIÊNCIAS - FTC ITAPIPOCA', 4, 4, 2306405, 'ATIVA');
       insert into censoinstsuperior values (23000, 'FACULDADE DE TECNOLOGIA E CIÊNCIAS - FTC CAUCAIA', 4, 4, 2303709, 'ATIVA');
       insert into censoinstsuperior values (23002, 'FACULDADE DE TECNOLOGIA E CIÊNCIAS - FTC N. SRA. DO SOCORRO', 4, 4, 2804805, 'ATIVA');
       insert into censoinstsuperior values (23012, 'FACULDADE LIBER DE PORANGATU', 4, 4, 5218003, 'ATIVA');
       insert into censoinstsuperior values (23022, 'FACULDADE CETRUS', 4, 4, 3550308, 'ATIVA');
       insert into censoinstsuperior values (23025, 'FACULDADE DE ENSINO SUPERIOR BRASILEIRA - FACULDADE FEBRAS', 4, 4, 3205200, 'ATIVA');
       insert into censoinstsuperior values (23066, 'FACULDADE METROPOLITANA DE DIAS D ÀVILA', 4, 4, 2910057, 'ATIVA');
       insert into censoinstsuperior values (23089, 'FACULDADE DE NOVA MUTUM', 4, 4, 5106224, 'ATIVA');
       insert into censoinstsuperior values (23090, 'INSTITUTO DE ENSINO SUPERIOR CAPIXABA', 4, 4, 3205002, 'ATIVA');
       insert into censoinstsuperior values (23095, 'INSTITUTO SOCIESC DE JARAGUÁ DO SUL', 4, 4, 4208906, 'ATIVA');
       insert into censoinstsuperior values (23096, 'FACULDADE SANTO ANTÔNIO - SJC', 4, 4, 3549904, 'ATIVA');
       insert into censoinstsuperior values (23097, 'FACULDADE IBMEC DE BRASÍLIA', 4, 4, 5300108, 'ATIVA');
       insert into censoinstsuperior values (23099, 'FACULDADE DE ENGENHARIA UNOPAR DE PALMAS', 4, 4, 1721000, 'ATIVA');
       insert into censoinstsuperior values (23100, 'FACULDADE DE ENGENHARIA PITÁGORAS DE JUAZEIRO DO NORTE', 4, 4, 2307304, 'ATIVA');
       insert into censoinstsuperior values (23101, 'FACULDADE DE ENGENHARIA PITÁGORAS DE SANTARÉM', 4, 4, 1506807, 'ATIVA');
       insert into censoinstsuperior values (23102, 'FACULDADE DE ENGENHARIA PITÁGORAS DE SOBRAL', 4, 4, 2312908, 'ATIVA');
       insert into censoinstsuperior values (23107, 'FACULDADE DOM ADELIO TOMASIN', 4, 4, 2311306, 'ATIVA');
       insert into censoinstsuperior values (23110, 'FACULDADE SANTA TERESA D ÁVILA', 4, 4, 2211001, 'ATIVA');
       insert into censoinstsuperior values (23130, 'INSTITUTO DE DESENVOLVIMENTO E APRENDIZAGEM - IDEA SÃO LUIZ', 4, 4, 2111300, 'ATIVA');
       insert into censoinstsuperior values (23139, 'BRAIN BUSINESS SCHOOL', 4, 4, 3550308, 'ATIVA');
       insert into censoinstsuperior values (23147, 'FACULDADE DO COMÉRCIO DE SÃO PAULO', 4, 4, 3550308, 'ATIVA');
       insert into censoinstsuperior values (23151, 'FACULDADE EDUCAR DA IBIAPABA', 4, 4, 2305803, 'ATIVA');
       insert into censoinstsuperior values (23155, 'FACULDADE DE TECNOLOGIA E CIÊNCIAS - FTC PORTO SEGURO', 4, 4, 2925303, 'ATIVA');
       insert into censoinstsuperior values (23159, 'FACULDADE BARÃO DE JEQUIRIÇA', 4, 4, 2932903, 'ATIVA');
       insert into censoinstsuperior values (23162, 'CENTRO DE ENSINO SUPERIOR DE CONTAGEM', 4, 4, 3118601, 'ATIVA');
       insert into censoinstsuperior values (23164, 'CENTRO DE ENSINO SUPERIOR DE DIVINÓPOLIS', 4, 4, 3122306, 'ATIVA');
       insert into censoinstsuperior values (23168, 'CENTRO DE ESTUDOS SUPERIORES DE JATAÍ', 4, 4, 5211909, 'ATIVA');
       insert into censoinstsuperior values (23169, 'ESCOLA SUPERIOR DE ITABIRA', 4, 4, 3131703, 'ATIVA');
       insert into censoinstsuperior values (23172, 'FACULDADE DE EDUCAÇÃO SUPERIOR DE POUSO ALEGRE', 4, 4, 3152501, 'ATIVA');
       insert into censoinstsuperior values (23174, 'CENTRO DE ENSINO SUPERIOR DE NOVA SERRANA', 4, 4, 3145208, 'ATIVA');
       insert into censoinstsuperior values (23175, 'ESCOLA SUPERIOR DE POUSO ALEGRE', 4, 4, 3152501, 'ATIVA');
       insert into censoinstsuperior values (23176, 'FACULDADE DE EDUCAÇÃO SUPERIOR DE SETE LAGOAS', 4, 4, 3167202, 'ATIVA');
       insert into censoinstsuperior values (23177, 'FACULDADE DE EDUCAÇÃO SUPERIOR DE CATALÃO', 4, 4, 5205109, 'ATIVA');
       insert into censoinstsuperior values (23178, 'CENTRO DE ENSINO SUPERIOR DE SETE LAGOAS', 4, 4, 3167202, 'ATIVA');
       insert into censoinstsuperior values (23179, 'CENTRO DE ENSINO SUPERIOR DE CATALÃO', 4, 4, 5205109, 'ATIVA');
       insert into censoinstsuperior values (23180, 'ESCOLA SUPERIOR DE CATALÃO', 4, 4, 5205109, 'ATIVA');
       insert into censoinstsuperior values (23191, 'FACULDADE CENTRAL DO RECIFE CENTRO', 4, 4, 2611606, 'ATIVA');
       insert into censoinstsuperior values (23194, 'FACULDADE PSICOLOG', 4, 4, 3543402, 'ATIVA');
       insert into censoinstsuperior values (23201, 'CENTRO DE ENSINO SUPERIOR SOCIESC DE ITAJAÍ', 4, 4, 4208203, 'ATIVA');
       insert into censoinstsuperior values (23202, 'FACULDADE SOCIESC DE EDUCAÇÃO DE SÃO BENTO DO SUL', 4, 4, 4215802, 'ATIVA');
       insert into censoinstsuperior values (23203, 'CENTRO DE ENSINO SUPERIOR SOCIESC DE SÃO BENTO DO SUL', 4, 4, 4215802, 'ATIVA');
       insert into censoinstsuperior values (23215, 'FACULDADE UNIÃO CULTURAL DO ESTADO DE SÃO PAULO', 4, 4, 3502804, 'ATIVA');
       insert into censoinstsuperior values (23218, 'FACULDADE PARAÍSO FORTALEZA', 4, 4, 2304400, 'ATIVA');
       insert into censoinstsuperior values (23228, 'FACULDADE SÃO JUDAS DE GUARULHOS', 4, 4, 3518800, 'ATIVA');
       insert into censoinstsuperior values (23229, 'FACULDADE ALFREDO NASSER DE PONTALINA', 4, 4, 5217708, 'ATIVA');
       insert into censoinstsuperior values (23241, 'INSTITUTO UNIVERSITÁRIO SÃO JUDAS DE SÃO BERNARDO DO CAMPO', 4, 4, 3548708, 'ATIVA');
       insert into censoinstsuperior values (23261, 'FACULDADE SÃO JUDAS DE SÃO BERNARDO DO CAMPO', 4, 4, 3548708, 'ATIVA');
       insert into censoinstsuperior values (23264, 'FACULDADE UNA DE CONSELHEIRO LAFAIETE', 4, 4, 3118304, 'ATIVA');
       insert into censoinstsuperior values (23275, 'FACULDADE DE AGRONOMIA UNA DE CONSELHEIRO LAFAIETE', 4, 4, 3118304, 'ATIVA');
       insert into censoinstsuperior values (23335, 'FACULDADE EMBU DAS ARTES', 4, 4, 3515004, 'ATIVA');
       insert into censoinstsuperior values (23342, 'FACULDADE AVANTIS DE FLORIANÓPOLIS', 4, 4, 4205407, 'ATIVA');
       insert into censoinstsuperior values (23358, 'CENTRO DE ENSINO SUPERIOR SOCIESC DE JARAGUÁ DO SUL', 4, 4, 4208906, 'ATIVA');
       insert into censoinstsuperior values (23382, 'FACULDADE UNINORTE TAILÂNDIA', 4, 4, 1507953, 'ATIVA');
       insert into censoinstsuperior values (23383, 'FACULDADE NACIONAL', 4, 4, 2611606, 'ATIVA');
       insert into censoinstsuperior values (23389, 'FACULDADE UNIBRAS DO MARANHÃO', 4, 4, 2109908, 'ATIVA');
       insert into censoinstsuperior values (23400, 'FACULDADE FASIPE DE SORRISO', 4, 4, 5107925, 'ATIVA');
       insert into censoinstsuperior values (23409, 'FACULDADE CENTRO SÃO PAULO', 4, 4, 3550308, 'ATIVA');
       insert into censoinstsuperior values (23454, 'FACULDADE IMEPAC DE ITUMBIARA', 4, 4, 5211503, 'ATIVA');
       insert into censoinstsuperior values (23820, 'FACULDADE DESCOMPLICA', 4, 4, 3304557, 'ATIVA');
       insert into censoinstsuperior values (23867, 'FACULDADE DE TECNOLOGIA DE SUMARÉ', 2, 2, 3552403, 'ATIVA');
       insert into censoinstsuperior values (23868, 'LINK SCHOOL OF BUSINESS', 4, 4, 3550308, 'ATIVA');
       insert into censoinstsuperior values (24024, 'CENTRO DE ENSINO SUPERIOR DE LORENA', 4, 4, 3527207, 'ATIVA');
       insert into censoinstsuperior values (24190, 'FACULDADE BARROS MELO RECIFE', 4, 4, 2611606, 'ATIVA');
       insert into censoinstsuperior values (24255, 'FACULDADE DE NOVA MUTUM', 4, 4, 5106224, 'ATIVA');
       insert into censoinstsuperior values (24268, 'FACULDADE UNINORTE MARABÁ', 4, 4, 1504208, 'ATIVA');
       insert into censoinstsuperior values (24282, 'FACULDADE BOA ESPERANÇA', 4, 4, 3107109, 'ATIVA');
       insert into censoinstsuperior values (24290, 'FACULDADE ANDREOTTI DE MARINGÁ', 4, 4, 4115200, 'ATIVA');
       insert into censoinstsuperior values (24404, 'FACULDADE ATENAS CENTRO DE MINAS', 4, 4, 3167202, 'ATIVA');
       insert into censoinstsuperior values (24410, 'FACULDADE CENTRO SUL', 4, 4, 2305506, 'ATIVA');
       insert into censoinstsuperior values (24443, 'FACULDADE AGES DE MEDICINA DE IRECÊ', 4, 4, 2914604, 'ATIVA');
       insert into censoinstsuperior values (24488, 'FACULDADE UNICESUMAR DE CORUMBÁ', 4, 4, 5003207, 'ATIVA');
       insert into censoinstsuperior values (24509, 'FACULDADE ESTÁCIO DE CANINDÉ', 4, 4, 2302800, 'ATIVA');
       insert into censoinstsuperior values (24547, 'ITPAC CRUZEIRO DO SUL', 4, 4, 1200203, 'ATIVA');
       insert into censoinstsuperior values (24550, 'FACULDADE ITPAC SANTA INES', 4, 4, 2109908, 'ATIVA');
       insert into censoinstsuperior values (24672, 'FACULDADE DE TECNOLOGIA DE MATÃO', 2, 2, 3529302, 'ATIVA');
       insert into censoinstsuperior values (25274, 'UNIVERSIDADE FEDERAL DE CATALÃO', 1, 1, 5205109, 'ATIVA');
       insert into censoinstsuperior values (25275, 'UNIVERSIDADE FEDERAL DO AGRESTE DE PERNAMBUCO', 1, 1, 2606002, 'ATIVA');
       insert into censoinstsuperior values (25277, 'UNIVERSIDADE FEDERAL DO DELTA DO PARNAIBA', 1, 1, 2207702, 'ATIVA');
       insert into censoinstsuperior values (25282, 'UNIVERSIDADE FEDERAL DE JATAÍ', 1, 1, 5211909, 'ATIVA');
       insert into censoinstsuperior values (21950, 'FACULDADE TUCURUI', 4, 4, 1508100, 'ATIVA');
       insert into censoinstsuperior values (22158, 'FACULDADE UNOPAR DE CIENCIAS JURIDICAS DE PETROLINA', 4, 4, 2611101, 'ATIVA');
       insert into censoinstsuperior values (22236, 'FACULDADE UNOPAR DE CIENCIAS JURIDICAS DE SETE LAGOAS', 4, 4, 3167202, 'ATIVA');
       insert into censoinstsuperior values (22325, 'FACULDADE CHRISTUS', 4, 4, 2304285, 'ATIVA');
       insert into censoinstsuperior values (22326, 'FACULDADE BRASILIA', 4, 4, 5300108, 'ATIVA');
       insert into censoinstsuperior values (25352, 'UNIVERSIDADE FEDERAL DE RONDONÓPOLIS', 1, 1, 5107602, 'ATIVA');";

        $this->execute($sql);
    }

    private function alteracaoCodInstituicaoUp() {
      $sql = "update formacao set ed27_i_censoinstsuperior = 19257 where ed27_i_censoinstsuperior = 302;
        update formacao set ed27_i_censoinstsuperior = 721 where ed27_i_censoinstsuperior = 722;
        update formacao set ed27_i_censoinstsuperior = 721 where ed27_i_censoinstsuperior = 723;
        update formacao set ed27_i_censoinstsuperior = 1498 where ed27_i_censoinstsuperior = 839;
        update formacao set ed27_i_censoinstsuperior = 1498 where ed27_i_censoinstsuperior = 840;
        update formacao set ed27_i_censoinstsuperior = 1818 where ed27_i_censoinstsuperior = 891;
        update formacao set ed27_i_censoinstsuperior = 2148 where ed27_i_censoinstsuperior = 1066;
        update formacao set ed27_i_censoinstsuperior = 3588 where ed27_i_censoinstsuperior = 1124;
        update formacao set ed27_i_censoinstsuperior = 721 where ed27_i_censoinstsuperior = 1212;
        update formacao set ed27_i_censoinstsuperior = 2132 where ed27_i_censoinstsuperior = 1226;
        update formacao set ed27_i_censoinstsuperior = 707 where ed27_i_censoinstsuperior = 1437;
        update formacao set ed27_i_censoinstsuperior = 1587 where ed27_i_censoinstsuperior = 1442;
        update formacao set ed27_i_censoinstsuperior = 1818 where ed27_i_censoinstsuperior = 1668;
        update formacao set ed27_i_censoinstsuperior = 707 where ed27_i_censoinstsuperior = 1692;
        update formacao set ed27_i_censoinstsuperior = 2566 where ed27_i_censoinstsuperior = 1706;
        update formacao set ed27_i_censoinstsuperior = 2566 where ed27_i_censoinstsuperior = 1707;
        update formacao set ed27_i_censoinstsuperior = 2973 where ed27_i_censoinstsuperior = 1731;
        update formacao set ed27_i_censoinstsuperior = 1587 where ed27_i_censoinstsuperior = 1767;
        update formacao set ed27_i_censoinstsuperior = 3588 where ed27_i_censoinstsuperior = 1858;
        update formacao set ed27_i_censoinstsuperior = 2132 where ed27_i_censoinstsuperior = 2146;
        update formacao set ed27_i_censoinstsuperior = 2149 where ed27_i_censoinstsuperior = 2168;
        update formacao set ed27_i_censoinstsuperior = 1462 where ed27_i_censoinstsuperior = 2243;
        update formacao set ed27_i_censoinstsuperior = 1498 where ed27_i_censoinstsuperior = 2245;
        update formacao set ed27_i_censoinstsuperior = 2973 where ed27_i_censoinstsuperior = 2791;
        update formacao set ed27_i_censoinstsuperior = 781 where ed27_i_censoinstsuperior = 2794;
        update formacao set ed27_i_censoinstsuperior = 3186 where ed27_i_censoinstsuperior = 2891;
        update formacao set ed27_i_censoinstsuperior = 2973 where ed27_i_censoinstsuperior = 2974;
        update formacao set ed27_i_censoinstsuperior = 1996 where ed27_i_censoinstsuperior = 3776;
        update formacao set ed27_i_censoinstsuperior = 2908 where ed27_i_censoinstsuperior = 3784;
        update formacao set ed27_i_censoinstsuperior = 2241 where ed27_i_censoinstsuperior = 3788;
        update formacao set ed27_i_censoinstsuperior = 2082 where ed27_i_censoinstsuperior = 4631;
        update formacao set ed27_i_censoinstsuperior = 13684 where ed27_i_censoinstsuperior = 5066;
        update formacao set ed27_i_censoinstsuperior = 4655 where ed27_i_censoinstsuperior = 5216;
        update formacao set ed27_i_censoinstsuperior = 448 where ed27_i_censoinstsuperior = 5317;
        update formacao set ed27_i_censoinstsuperior = 1657 where ed27_i_censoinstsuperior = 12847;
        update formacao set ed27_i_censoinstsuperior = 1805 where ed27_i_censoinstsuperior = 14002;
        update formacao set ed27_i_censoinstsuperior = 17632 where ed27_i_censoinstsuperior = 18290;
        update formacao set ed27_i_censoinstsuperior = 383 where ed27_i_censoinstsuperior = 18642;
        update formacao set ed27_i_censoinstsuperior = 18147 where ed27_i_censoinstsuperior = 18714;
        update formacao set ed27_i_censoinstsuperior = 17632 where ed27_i_censoinstsuperior = 18716;
        update formacao set ed27_i_censoinstsuperior = 18979 where ed27_i_censoinstsuperior = 19049;
        update formacao set ed27_i_censoinstsuperior = 2537 where ed27_i_censoinstsuperior = 19050;
        update formacao set ed27_i_censoinstsuperior = 2537 where ed27_i_censoinstsuperior = 19208;
        update formacao set ed27_i_censoinstsuperior = 13982 where ed27_i_censoinstsuperior = 19332;
        update formacao set ed27_i_censoinstsuperior = 4135 where ed27_i_censoinstsuperior = 19342;
        update formacao set ed27_i_censoinstsuperior = 17632 where ed27_i_censoinstsuperior = 19375;
        update formacao set ed27_i_censoinstsuperior = 17632 where ed27_i_censoinstsuperior = 19405;
        update formacao set ed27_i_censoinstsuperior = 18147 where ed27_i_censoinstsuperior = 19733;
        update formacao set ed27_i_censoinstsuperior = 17632 where ed27_i_censoinstsuperior = 19735;
        update formacao set ed27_i_censoinstsuperior = 2885 where ed27_i_censoinstsuperior = 20612;
        update formacao set ed27_i_censoinstsuperior = 1055 where ed27_i_censoinstsuperior = 21421;
        update formacao set ed27_i_censoinstsuperior = 17632 where ed27_i_censoinstsuperior = 21614;
        update formacao set ed27_i_censoinstsuperior = 1988 where ed27_i_censoinstsuperior = 21676;
        update formacao set ed27_i_censoinstsuperior = 21931 where ed27_i_censoinstsuperior = 21932;
        update formacao set ed27_i_censoinstsuperior = 19323 where ed27_i_censoinstsuperior = 22126;
        update formacao set ed27_i_censoinstsuperior = 19260 where ed27_i_censoinstsuperior = 22127;
        update formacao set ed27_i_censoinstsuperior = 19781 where ed27_i_censoinstsuperior = 22129;
        update formacao set ed27_i_censoinstsuperior = 19786 where ed27_i_censoinstsuperior = 22134;
        update formacao set ed27_i_censoinstsuperior = 20587 where ed27_i_censoinstsuperior = 22135;
        update formacao set ed27_i_censoinstsuperior = 21687 where ed27_i_censoinstsuperior = 22136;
        update formacao set ed27_i_censoinstsuperior = 20588 where ed27_i_censoinstsuperior = 22140;
        update formacao set ed27_i_censoinstsuperior = 21238 where ed27_i_censoinstsuperior = 22143;
        update formacao set ed27_i_censoinstsuperior = 21693 where ed27_i_censoinstsuperior = 22149;
        update formacao set ed27_i_censoinstsuperior = 19785 where ed27_i_censoinstsuperior = 22150;
        update formacao set ed27_i_censoinstsuperior = 21552 where ed27_i_censoinstsuperior = 22151;
        update formacao set ed27_i_censoinstsuperior = 21553 where ed27_i_censoinstsuperior = 22152;
        update formacao set ed27_i_censoinstsuperior = 19780 where ed27_i_censoinstsuperior = 22153;
        update formacao set ed27_i_censoinstsuperior = 21886 where ed27_i_censoinstsuperior = 22157;
        update formacao set ed27_i_censoinstsuperior = 21834 where ed27_i_censoinstsuperior = 22169;
        update formacao set ed27_i_censoinstsuperior = 21833 where ed27_i_censoinstsuperior = 22170;
        update formacao set ed27_i_censoinstsuperior = 19298 where ed27_i_censoinstsuperior = 22225;
        update formacao set ed27_i_censoinstsuperior = 19783 where ed27_i_censoinstsuperior = 22226;
        update formacao set ed27_i_censoinstsuperior = 21280 where ed27_i_censoinstsuperior = 22227;
        update formacao set ed27_i_censoinstsuperior = 21900 where ed27_i_censoinstsuperior = 22228;
        update formacao set ed27_i_censoinstsuperior = 21554 where ed27_i_censoinstsuperior = 22229;
        update formacao set ed27_i_censoinstsuperior = 21903 where ed27_i_censoinstsuperior = 22235;";

      $this->execute($sql);
    }

    private function alteracaoSituacaoUp()
    {
        $sql = "update censoinstsuperior set ed257_c_situacao = 'INATIVA' where ed257_i_codigo in (255,281,321,455,615,624,650,651,660,
        662,681,761,767,906,917,924,973,977,
        983,986,990,1013,1016,1097,1099,1108,
        1138,1183,1213,1246,1283,1285,1293,1333,
        1335,1358,1366,1371,1399,1415,1448,1453,
        1467,1495,1503,1516,1544,1567,1617,1628,
        1634,1667,1674,1678,1689,1724,1741,1750,
        1765,1769,1771,1784,1824,1831,1865,1886,
        1890,1899,1938,1943,1950,1951,2014,2032,
        2034,2102,2104,2188,2197,2247,2280,2303,
        2304,2308,2364,2366,2393,2431,2451,2499,
        2528,2529,2572,2578,2594,2619,2639,2651,
        2660,2750,2772,2784,2795,2804,2840,2846,
        2854,2861,2895,3001,3007,3021,3027,3154,
        3156,3173,3209,3224,3225,3227,3228,3238,
        3290,3311,3380,3392,3394,3411,3506,3538,
        3643,3647,3663,3691,3693,3775,3790,3794,
        3803,3807,3882,3951,3963,3975,3989,3993,
        4005,4036,4064,4136,4148,4204,4209,4210,
        4295,4371,4394,4395,4421,4435,4454,4582,
        4590,4598,4693,4721,4746,4771,4772,4794,
        4859,4869,4998,5079,5186,5388,5663,10059,
        10950,11376,12052,12249,12346,12416,12421,
        12803,13452,13498,13696,13735,13818,13856,
        13878,14090,14126,14128,14149,14150,14153,
        14160,14166,14169,14171,14204,14209,14222,
        14258,14782,14783,14785,15518,17109,17382,
        17587,17636,17701,17744,17828,17850,18107,
        18115,18117,18132,18135,18145,18160,18164,
        18210,18257,18266,18370,18678,19357,19358,
        19913,19966,20003,20219,21266,21287,21288);";

        $this->execute($sql);
    }

    private function alteracaoNomeUp()
    {
        $sql = "update censoinstsuperior set ed257_c_nome = 'FACULDADES INTEGRADAS DO ESTADO DE SAO PAULO' where ed257_i_codigo = 131;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNIBRASILIA DE CIENCIAS ECONOMICAS DE MINAS GERAIS'  where ed257_i_codigo = 139;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO ANHANGUERA PITAGORAS AMPLI' where ed257_i_codigo = 242;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO ACADEMIA' where ed257_i_codigo = 337;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE MUSICA DO ESPIRITO SANTO MAURICIO DE OLIVEIRA' where ed257_i_codigo = 530;
        update censoinstsuperior set ed257_c_nome = 'FUNDACAO FACULDADE DE FILOSOFIA, CIENCIAS E LETRAS DE MANDAGUARI' where ed257_i_codigo = 535;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE MARIA THEREZA' where ed257_i_codigo = 640;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE ASSIS' where ed257_i_codigo = 721;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO DE GOIANIA' where ed257_i_codigo = 763;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE IBRA DOS VALES' where ed257_i_codigo = 778;
        update censoinstsuperior set ed257_c_nome = 'FIAP  CENTRO UNIVERSITARIO' where ed257_i_codigo = 852;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO ARTHUR SA EARP NETO' where ed257_i_codigo = 1080;
        update censoinstsuperior set ed257_c_nome = 'UNIVERSIDADE CESUMAR' where ed257_i_codigo = 1196;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO NOSSA SENHORA APARECIDA' where ed257_i_codigo = 1237;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE BRASILEIRA MULTIVIX VITORIA' where ed257_i_codigo = 1244;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE MULTIVIX SERRA' where ed257_i_codigo = 1326;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE MULTIVIX NOVA VENECIA' where ed257_i_codigo = 1359;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO FAESA' where ed257_i_codigo = 1379;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE NOVO HORIZONTE DE IPOJUCA' where ed257_i_codigo = 1383;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE ESTACIO DE PIMENTA BUENO' where ed257_i_codigo = 1403;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO SALESIANO' where ed257_i_codigo = 1494;
        update censoinstsuperior set ed257_c_nome = 'CENTRO DE ENSINO SUPERIOR CESUL' where ed257_i_codigo = 1523;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO ALFREDO NASSER' where ed257_i_codigo = 1573;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE MONTE PASCOAL' where ed257_i_codigo = 1636;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO ARAGUAIA' where ed257_i_codigo = 1663;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNIBRAS DE GOIAS' where ed257_i_codigo = 1703;
        update censoinstsuperior set ed257_c_nome = 'STRONG BUSINESS SCHOOL' where ed257_i_codigo = 1723;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO FACID WYDEN' where ed257_i_codigo = 1734;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNIBRASILIA SUL' where ed257_i_codigo = 1739;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DA ASSOCIACAO BRASILIENSE DE EDUCACAO' where ed257_i_codigo = 1864;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO SANTA CRUZ DE CURITIBA' where ed257_i_codigo = 1872;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE EUROPEIA DE TECNOLOGIA E CIENCIAS HUMANAS  EUROTECH' where ed257_i_codigo = 1894;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO UNIFACIMED' where ed257_i_codigo = 1917;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO LA SALLE' where ed257_i_codigo = 1936;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNIRB  ALAGOAS' where ed257_i_codigo = 1956;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO MARIO PONTES JUCA' where ed257_i_codigo = 1965;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE MULTIVIX DE CACHOEIRO' where ed257_i_codigo = 1970;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE FATIFAJAR JAGUARIAIVA' where ed257_i_codigo = 2035;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS, EDUCACAO E TEOLOGIA DO NORTE DO BRASIL' where ed257_i_codigo = 2133;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNIS SAO LOURENCO' where ed257_i_codigo = 2229;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNIAO DE GOYAZES FORMOSA' where ed257_i_codigo = 2266;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO BRASILIA DO ESTADO DE GOIAS' where ed257_i_codigo = 2336;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO FIBRA' where ed257_i_codigo = 2426;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE MULTIVIX CARIACICA' where ed257_i_codigo = 2537;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO DE JUAZEIRO DO NORTE' where ed257_i_codigo = 2593;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO ESTACIO META DE RIO BRANCO ESTACIO UNIMETA' where ed257_i_codigo = 2613;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE APRIMORAR DE SAO JOSE DOS CAMPOS' where ed257_i_codigo = 2625;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE ESTACIO SAO PAULO DE RONDONIA' where ed257_i_codigo = 2754;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE EDUCACAO DO PIAUI' where ed257_i_codigo = 2827;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO SATC' where ed257_i_codigo = 2896;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE ESTACIO DO PANTANAL' where ed257_i_codigo = 2961;
        update censoinstsuperior set ed257_c_nome = 'FACULDADES INTEGRADAS DE PRIMAVERA DO LESTE' where ed257_i_codigo = 2973;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNIBRAS DO MATO GROSSO' where ed257_i_codigo = 3204;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO DE VICOSA' where ed257_i_codigo = 3205;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO IDEAU' where ed257_i_codigo = 3339;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNIBRAS DA BAHIA' where ed257_i_codigo = 3365;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO PARAISO' where ed257_i_codigo = 3388;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO CESUCA' where ed257_i_codigo = 3443;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNICA DE MONTES CLAROS' where ed257_i_codigo = 3657;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE GRANDE SAO PAULO' where ed257_i_codigo = 3746;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO GUAIRACA' where ed257_i_codigo = 3797;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE IBRA DE BRASILIA' where ed257_i_codigo = 3854;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE JK CCI' where ed257_i_codigo = 3980;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO UNICURITIBA' where ed257_i_codigo = 4045;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA E CIENCIAS DO DISTRITO FEDERAL' where ed257_i_codigo = 4095;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA FTEC DE PORTO ALEGRE' where ed257_i_codigo = 4096;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA FTEC DE BENTO GONCALVES' where ed257_i_codigo = 4097;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO MAURICIO DE NASSAU PAULISTA' where ed257_i_codigo = 4118;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNINORTE' where ed257_i_codigo = 4135;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNIBRASILIA DE MINAS GERAIS' where ed257_i_codigo = 4166;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE CMB' where ed257_i_codigo = 4261;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE ESTACIO UNIJIPA DE JIPARANA' where ed257_i_codigo = 4411;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO AESO   BARROS MELO' where ed257_i_codigo = 4420;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE ESPG' where ed257_i_codigo = 4584;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNIBRAS DO NORTE GOIANO' where ed257_i_codigo = 4586;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO UNIFATECIE' where ed257_i_codigo = 4751;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO UNA DE CONTAGEM' where ed257_i_codigo = 4766;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE IBRA DE TAUBATE' where ed257_i_codigo = 4873;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE CONECTADA FACONNECT' where ed257_i_codigo = 4889;
        update censoinstsuperior set ed257_c_nome = 'STRONG BUSINESS SCHOOL BS' where ed257_i_codigo = 4943;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE IBRA DE TECNOLOGIA' where ed257_i_codigo = 4983;
        update censoinstsuperior set ed257_c_nome = 'FACULDADES FAMEP' where ed257_i_codigo = 5008;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNINA' where ed257_i_codigo = 5025;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE IMPACTA' where ed257_i_codigo = 5387;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO FUNORTE' where ed257_i_codigo = 5592;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE METODISTA CONEXIONAL' where ed257_i_codigo = 10251;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE MULTIVIX SAO MATEUS' where ed257_i_codigo = 10685;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE CRISTA DA AMAZONIA' where ed257_i_codigo = 11593;
        update censoinstsuperior set ed257_c_nome = 'ISTITUTO EUROPEO DI DESIGN' where ed257_i_codigo = 11807;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNIBRASILIA' where ed257_i_codigo = 11895;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE CESURG MARAU' where ed257_i_codigo = 15351;
        update censoinstsuperior set ed257_c_nome = 'INSTITUTO TOCANTINENSE PRESIDENTE ANTONIO CARLOS' where ed257_i_codigo = 16728;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE MELIES' where ed257_i_codigo = 16934;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE ESEA' where ed257_i_codigo = 17291;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE ABERTA DO TOCANTINS' where ed257_i_codigo = 17322;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE EMPREENDEDORISMO E CIENCIAS HUMANAS' where ed257_i_codigo = 18036;
        update censoinstsuperior set ed257_c_nome = 'FACULDADES INTEGRADAS VITAL BRAZIL' where ed257_i_codigo = 18319;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNIJUAZEIRO' where ed257_i_codigo = 18652;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE SANTO ANTONIO' where ed257_i_codigo = 18667;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE ADMINISTRACAO, COMERCIO E EMPREENDEDORISMO' where ed257_i_codigo = 18679;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DYNAMUS DE CAMPINAS' where ed257_i_codigo = 18696;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE INOVA MAIS DE SAO PAULO' where ed257_i_codigo = 18711;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE MULTIVIX VILA VELHA' where ed257_i_codigo = 18979;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE GOYAZES DO DISTRITO FEDERAL' where ed257_i_codigo = 19216;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE BRASILEIRA DO RECONCAVO' where ed257_i_codigo = 19284;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE CEAM' where ed257_i_codigo = 19369;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNIRB JOAO PESSOA' where ed257_i_codigo = 19956;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNIRB PARNAMIRIM' where ed257_i_codigo = 19957;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS ODONTOLOGICAS' where ed257_i_codigo = 19963;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE PECEGE' where ed257_i_codigo = 21638;";

        $this->execute($sql);

    }

    private function exclusaoUp()
    {
        $sql = "delete from censoinstsuperior where ed257_i_codigo in (302,722,723,839,840,891,1066,1124,1212,1226,
        1437,1442,1668,1692,1706,1707,1731,1767,1858
        ,2146,2168,2243,2245,2791,2794,2891,2974,3776,3784
        ,3788,4631,5066,5216,5317,12847,14002,18290,18642,18714,18716,19049
        ,19050,19208,19332,19342,19375,19405,19733,19735,20612,21421,21614
        ,21676,21932,22126,22127,22129,22134,22135,22136,22140,22143
        ,22149,22150,22151,22152,22153,22157,22169
        ,22170,22225,22226,22227,22228,22229,22235);";

        $this->execute($sql);

    }

    /**
     * DOWN
     */

    private function inclusaoDown()
    {
        $sql = "delete from censoinstsuperior where ed257_i_codigo in (
                                                       12410,15667,16437,17395,17765,17892,18066,18734,18880,19207,
                                                       19299,19737,19878,19879,20074,20099,20291,20321,20438,20534,
                                                       20663,20709,21636,21681,21816,21850,21857,21861,21885,21891,
                                                       21893,21894,21898,21899,21926,21928,21929,21935,21939,21978,
                                                       21999,22017,22018,22028,22090,22097,22125,22139,22142,22146,
                                                       22147,22154,22171,22173,22174,22181,22185,22193,22195,22196,
                                                       22212,22213,22214,22216,22218,22222,22224,22232,22237,22246,
                                                       22252,22262,22264,22310,22314,22329,22405,22424,22433,22441,
                                                       22443,22449,22452,22453,22455,22456,22462,22470,22472,22521,
                                                       22527,22566,22578,22592,22599,22605,22606,22625,22628,22629,
                                                       22634,22636,22640,22641,22643,22644,22651,22659,22684,22702,
                                                       22707,22710,22712,22713,22715,22736,22738,22739,22741,22742,
                                                       22746,22753,22758,22760,22762,22763,22764,22775,22777,22787,
                                                       22811,22814,22862,22872,22911,22917,22946,22950,22975,22992,
                                                       22996,22999,23000,23002,23012,23022,23025,23066,23089,23090,
                                                       23095,23096,23097,23099,23100,23101,23102,23107,23110,23130,
                                                       23139,23147,23151,23155,23159,23162,23164,23168,23169,23172,
                                                       23174,23175,23176,23177,23178,23179,23180,23191,23194,23201,
                                                       23202,23203,23215,23218,23228,23229,23241,23261,23264,23275,
                                                       23335,23342,23358,23382,23383,23389,23400,23409,23454,23820,
                                                       23867,23868,24024,24190,24255,24268,24282,24290,24404,24410,
                                                       24443,24488,24509,24547,24550,24672,25274,25275,25277,25282,
                                                       25352,21819,21950,22158,22168,22236,22325,22326,20591);";

        $this->execute($sql);
    }

    private function alteracaoSituacaoDown()
    {
       $sql =  "update censoinstsuperior set ed257_c_situacao = 'ATIVA' where ed257_i_codigo in (255,281,321,455,615,624,650,651,660,
        662,681,761,767,906,917,924,973,977,
        983,986,990,1013,1016,1097,1099,1108,
        1138,1183,1213,1246,1283,1285,1293,1333,
        1335,1358,1366,1371,1399,1415,1448,1453,
        1467,1495,1503,1516,1544,1567,1617,1628,
        1634,1667,1674,1678,1689,1724,1741,1750,
        1765,1769,1771,1784,1824,1831,1865,1886,
        1890,1899,1938,1943,1950,1951,2014,2032,
        2034,2102,2104,2188,2197,2247,2280,2303,
        2304,2308,2364,2366,2393,2431,2451,2499,
        2528,2529,2572,2578,2594,2619,2639,2651,
        2660,2750,2772,2784,2795,2804,2840,2846,
        2854,2861,2895,3001,3007,3021,3027,3154,
        3156,3173,3209,3224,3225,3227,3228,3238,
        3290,3311,3380,3392,3394,3411,3506,3538,
        3643,3647,3663,3691,3693,3775,3790,3794,
        3803,3807,3882,3951,3963,3975,3989,3993,
        4005,4036,4064,4136,4148,4204,4209,4210,
        4295,4371,4394,4395,4421,4435,4454,4582,
        4590,4598,4693,4721,4746,4771,4772,4794,
        4859,4869,4998,5079,5186,5388,5663,10059,
        10950,11376,12052,12249,12346,12416,12421,
        12803,13452,13498,13696,13735,13818,13856,
        13878,14090,14126,14128,14149,14150,14153,
        14160,14166,14169,14171,14204,14209,14222,
        14258,14782,14783,14785,15518,17109,17382,
        17587,17636,17701,17744,17828,17850,18107,
        18115,18117,18132,18135,18145,18160,18164,
        18210,18257,18266,18370,18678,19357,19358,
        19913,19966,20003,20219,21266,21287,21288);";

       $this->execute($sql);
    }

    private function alteracaoNomeDown()
    {
        $sql = "update censoinstsuperior set ed257_c_nome = 'FACULDADE DE EDUCACAO FISICA DE BARRA BONITA' where ed257_i_codigo =  131  ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS ECONOMICAS DO TRIANGULO MINEIRO' where ed257_i_codigo = 139  ;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO ANHANGUERA DE SANTO ANDRE' where ed257_i_codigo = 242  ;
        update censoinstsuperior set ed257_c_nome = 'CENTRO DE ENSINO SUPERIOR DE JUIZ DE FORA' where ed257_i_codigo = 337  ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE MUSICA DO ESPIRITO SANTO' where ed257_i_codigo = 530  ;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO DE MANDAGUARI  UNIMAN' where ed257_i_codigo = 535  ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADES INTEGRADAS MARIA THEREZA' where ed257_i_codigo = 640  ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE EDUCACAO DE ASSIS' where ed257_i_codigo = 721  ;
        update censoinstsuperior set ed257_c_nome = 'INSTITUTO UNIFICADO DE ENSINO SUPERIOR OBJETIVO' where ed257_i_codigo = 763  ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA DE JACAREI' where ed257_i_codigo = 778  ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE INFORMATICA E ADMINISTRACAO PAULISTA' where ed257_i_codigo = 852  ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE ARTHUR SA EARP NETO' where ed257_i_codigo = 1080 ;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO DE MARINGA  UNICESUMAR' where ed257_i_codigo = 1196 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE NOSSA SENHORA APARECIDA' where ed257_i_codigo = 1237 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE BRASILEIRA' where ed257_i_codigo = 1244 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE CAPIXABA DA SERRA' where ed257_i_codigo = 1326 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE CAPIXABA DE NOVA VENECIA' where ed257_i_codigo = 1359 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADES INTEGRADAS SAO PEDRO' where ed257_i_codigo = 1379 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE JOSE LACERDA FILHO DE CIENCIAS APLICADAS' where ed257_i_codigo = 1383 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE PIMENTA BUENO' where ed257_i_codigo = 1403 ;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO CATOLICO DE VITORIA' where ed257_i_codigo = 1494 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE DIREITO FRANCISCO BELTRAO' where ed257_i_codigo = 1523 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE ALFREDO NASSER' where ed257_i_codigo = 1573 ;
        update censoinstsuperior set ed257_c_nome = 'CASTELLI ESCOLA SUPERIOR DE HOTELARIA' where ed257_i_codigo = 1636 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE ARAGUAIA' where ed257_i_codigo = 1663 ;
        update censoinstsuperior set ed257_c_nome = 'INSTITUTO DE ENSINO SUPERIOR DE RIO VERDE' where ed257_i_codigo = 1703 ;
        update censoinstsuperior set ed257_c_nome = 'ESCOLA SUPERIOR DE ADMINISTRACAO E GESTAO STRONG' where ed257_i_codigo = 1723 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE INTEGRAL DIFERENCIAL WYDEN' where ed257_i_codigo = 1734 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE FORTIUM SAO SEBASTIAO' where ed257_i_codigo = 1739 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE ADMINISTRACAO DA ASSOCIACAO BRASILIENSE DE EDUCACAO' where ed257_i_codigo = 1864 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADES INTEGRADAS SANTA CRUZ DE CURITIBA' where ed257_i_codigo = 1872 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE EUROPEIA DE ADMINISTRACAO E MARKETING' where ed257_i_codigo = 1894 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS BIOMEDICAS DE CACOAL' where ed257_i_codigo = 1917 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE LA SALLE' where ed257_i_codigo = 1936 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE REGIONAL BRASILEIRA  MACEIO' where ed257_i_codigo = 1956 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA DE ALAGOAS' where ed257_i_codigo = 1965 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DO ESPIRITO SANTO' where ed257_i_codigo = 1970 ;
        update censoinstsuperior set ed257_c_nome = 'UNIAO LATINOAMERICANA DE TECNOLOGIA' where ed257_i_codigo = 2035 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS  EDUCACAO E TEOLOGIA DO NORTE DO BRASIL' where ed257_i_codigo = 2133 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE VICTOR HUGO' where ed257_i_codigo = 2229 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE CAMBURY DE FORMOSA' where ed257_i_codigo = 2266 ;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO MONTES BELOS' where ed257_i_codigo = 2336 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE INTEGRADA BRASIL AMAZONIA  FIBRA' where ed257_i_codigo = 2426 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE SAO GERALDO' where ed257_i_codigo = 2537 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE JUAZEIRO DO NORTE' where ed257_i_codigo = 2593 ;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO META' where ed257_i_codigo = 2613 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA IBTA  SAO JOSE DOS CAMPOS' where ed257_i_codigo = 2625 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE SAO PAULO' where ed257_i_codigo = 2754 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE EVANGELICA DO PIAUI' where ed257_i_codigo = 2827 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE SATC' where ed257_i_codigo = 2896 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DO PANTANAL MATOGROSSENSE' where ed257_i_codigo = 2961 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS HUMANAS E BIOLOGICAS E DA SAUDE' where ed257_i_codigo = 2973 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE QUATRO MARCOS' where ed257_i_codigo = 3204 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS E TECNOLOGIA DE VICOSA' where ed257_i_codigo = 3205 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE IDEAU DE GETULIO VARGAS' where ed257_i_codigo = 3339 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE SAO FRANCISCO DE JUAZEIRO' where ed257_i_codigo = 3365 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE PARAISO DO CEARA' where ed257_i_codigo = 3388 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE INEDI' where ed257_i_codigo = 3443 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE COMPUTACAO DE MONTES CLAROS' where ed257_i_codigo = 3657 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE PAULISTA DE PESQUISA E ENSINO SUPERIOR' where ed257_i_codigo = 3746 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE GUAIRACA' where ed257_i_codigo = 3797 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE IDEAL DE BRASILIA' where ed257_i_codigo = 3854 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE JK BRASILIA SAMAMBAIA' where ed257_i_codigo = 3980 ;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO SOCIESC DE CURITIBA' where ed257_i_codigo = 4045 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA EQUIPE DARWIN' where ed257_i_codigo = 4095 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA FTEC' where ed257_i_codigo = 4096 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA FTEC' where ed257_i_codigo = 4097 ;
        update censoinstsuperior set ed257_c_nome = 'CENTRO UNIVERSITARIO JOAQUIM NABUCO DE PAULISTA' where ed257_i_codigo = 4118 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNINASSAU MANAUS' where ed257_i_codigo = 4135 ;
        update censoinstsuperior set ed257_c_nome = 'CENTRO DE ENSINO SUPERIOR DE UBERABA' where ed257_i_codigo = 4166 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA DE PORTO ALEGRE' where ed257_i_codigo = 4261 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE PANAMERICANA DE JIPARANA' where ed257_i_codigo = 4411 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADES INTEGRADAS BARROS MELO' where ed257_i_codigo = 4420 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE FACESE' where ed257_i_codigo = 4584 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DO NORTE GOIANO' where ed257_i_codigo = 4586 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA E CIENCIAS DO NORTE DO PARANA' where ed257_i_codigo = 4751 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNA DE CONTAGEM' where ed257_i_codigo = 4766 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA DE TAUBATE' where ed257_i_codigo = 4873 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE CONCHAS' where ed257_i_codigo = 4889 ;
        update censoinstsuperior set ed257_c_nome = 'ESCOLA SUPERIOR DE ADMINISTRACAO E GESTAO STRONG DA BAIXADA SANTISTA' where ed257_i_codigo = 4943 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE SAO JOSE DOS CAMPOS' where ed257_i_codigo = 4983 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DO MEDIO PARNAIBA' where ed257_i_codigo = 5008 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE SAO BRAZ' where ed257_i_codigo = 5025 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE IMPACTA DE TECNOLOGIA' where ed257_i_codigo = 5387 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADES INTEGRADAS DO NORTE DE MINAS  FUNORTE' where ed257_i_codigo = 5592 ;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE ORTODOXA' where ed257_i_codigo = 10251;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE NORTE CAPIXABA DE SAO MATEUS' where ed257_i_codigo = 10685;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE  APOENA' where ed257_i_codigo = 11593;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA DO ISTITUTO EUROPEO DI DESIGN' where ed257_i_codigo = 11807;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE FORTIUM DE SANTA MARIA' where ed257_i_codigo = 11895;
        update censoinstsuperior set ed257_c_nome = 'CENTRO DE ENSINO SUPERIOR RIOGRANDENSE MARAU CESURG' where ed257_i_codigo = 15351;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE CIENCIAS HUMANAS  ECONOMICAS E DA SAUDE' where ed257_i_codigo = 16728;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE MELIES DE TECNOLOGIA' where ed257_i_codigo = 16934;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE ANTONIO PROPICIO AGUIAR FRANCO' where ed257_i_codigo = 17291;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE INTEGRADA DE ARAGUATINS' where ed257_i_codigo = 17322;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE TELOS' where ed257_i_codigo = 18036;
        update censoinstsuperior set ed257_c_nome = 'FAINIC  FACULDADES INTEGRADAS NIC' where ed257_i_codigo = 18319;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE UNINASSAU JUAZEIRO DO NORTE' where ed257_i_codigo = 18652;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE SAO LUCAS DE CACAPAVA' where ed257_i_codigo = 18667;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE SIDROLANDIA' where ed257_i_codigo = 18679;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE FEBRACIS' where ed257_i_codigo = 18696;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE TECNOLOGIA E CIENCIAS' where ed257_i_codigo = 18711;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE CAPIXABA DE VILA VELHA' where ed257_i_codigo = 18979;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE METROPOLITANA RECANTO DAS EMAS' where ed257_i_codigo = 19216;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE BATISTA BRASILEIRA DO RECONCAVO' where ed257_i_codigo = 19284;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE MARTINHO LUTERO' where ed257_i_codigo = 19369;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE GESTAO E NEGOCIOS DE JOAO PESSOA' where ed257_i_codigo = 19956;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE GESTAO E NEGOCIOS DE PARNAMIRIM' where ed257_i_codigo = 19957;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DE ODONTOLOGIA DO NORTE DE MINAS' where ed257_i_codigo = 19963;
        update censoinstsuperior set ed257_c_nome = 'FACULDADE DO INSTITUTO PECEGE' where ed257_i_codigo = 21638;";

        $this->execute($sql);
    }

}
