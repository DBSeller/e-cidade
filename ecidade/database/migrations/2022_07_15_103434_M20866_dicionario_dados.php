<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class M20866DicionarioDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
    }

    protected function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_sysarquivo values (1010956, 'assentconf', 'Configuração do Assentamento', 'rh500', '2022-07-11', 'Configuração do Assentamento', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (29,1010956);
insert into db_sysarquivo values (1010958, 'assentperc', 'Ordem do Assentamento, valor e unidade valor', 'rh501', '2022-07-11', 'Ordem do Assentamento', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (29,1010958);
insert into db_sysarquivo values (1010959, 'assentform', 'Parametros de Concessao e nao concessao do Assentamento', 'rh502', '2022-07-11', 'Parametros de Concessao do Assentamento', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (69,1010959);
insert into db_sysarquivo values (1010960, 'assentconcedeconf', 'Parâmetros do calculo da concessão', 'rh503', '2022-07-11', 'Parametros do calculo da concessao', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (29,1010960);
insert into db_sysarquivo values (1010961, 'concessaocalculo', 'datas das concessões ', 'rh504', '2022-07-11', 'datas das concessoes ', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (29,1010961);
insert into db_sysarquivo values (1010963, 'concessaocalculolog', 'Assentamentos usado para o calculo', 'rh507', '2022-07-11', 'Assentamentos usado para o calculo', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (29,1010963);
insert into db_sysarquivo values (1010964, 'concessaocalculonovadata', 'datas das concessões caso a data seja alterada', 'rh506', '2022-07-11', 'datas das concessoes alteradas', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (29,1010964);
insert into db_sysarquivo values (1010965, 'concessaocalculonovadatalog', 'Assentamentos usado para o calculo caso a data seja alterada', 'rh508', '2022-07-11', 'Assentamentos usado para o calculo nova data', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (29,1010965);
insert into db_sysarquivo values (1010966, 'concessaoassent', 'Concessões e assentamentos da portaria gerada', 'rh505', '2022-07-11', 'Concessoes com portaria', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (29,1010966);
insert into db_syscampo values(1014265,'rh500_sequencial','int8','sequencial da tabela assentconf','0', 'Sequencial',10,'f','f','t',1,'text','Sequencial ');
insert into db_syscampo values(1014266,'rh500_assentamento','int8','h12_codigo da tabela tipoasse ','0', 'Tipo do Assentamento',10,'f','f','f',1,'text','Tipo do Assentamento');
insert into db_syscampo values(1014267,'rh500_datalimite','date','data limite de cálculo ','null', 'Data Limite',10,'t','f','f',1,'text','Data Limite');
insert into db_syscampo values(1014268,'rh500_condede','int8','h12_codigo da tabela tipoasse','0', 'Tipo de Assentamento',10,'f','f','f',1,'text','Tipo de Assentamento');
insert into db_syscampo values(1014270,'rh500_naocencede','int8','h12_codigo da tabela tipoasse','0', 'Tipo de Assentamento',10,'f','f','f',1,'text','Tipo de Assentamento');
insert into db_syscampo values(1014271,'rh501_sequencial','int8','Sequencial da tabela assentperc','0', 'Sequencial',10,'f','f','t',1,'text','Sequencial');
insert into db_syscampo values(1014272,'rh501_seqasentconf','int8',' rh500_sequencial da tabela assentconf','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1014273,'rh501_ordem','int8','Ordem na execução do calculo','0', 'Ordem',2,'f','f','f',1,'text','Ordem');
insert into db_syscampo values(1014274,'rh501_perc','int8','percentual na concessão','0', 'percentual',3,'f','f','f',1,'text','percentual');
insert into db_syscampo values(1014275,'rh501_valor','int8',' quantidade de unidades','0', 'Valor',3,'f','f','f',1,'text','Valor');
insert into db_syscampo values(1014276,'rh501_unidade','varchar(10)','( dias, meses, anos )','', 'Unidade',10,'f','f','f',0,'text','Unidade');
insert into db_syscampo values(1014277,'rh502_sequencial','int8','sequencial da tabela assentform','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1014278,'rh502_seqassentconf','int8','rh500_sequencial da tabela assentconf ','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1014279,'rh502_codigo','int8','h12_codigo da tabela tipoasse ','0', 'Assentamento',10,'f','f','f',1,'text','Assentamento');
insert into db_syscampo values(1014280,'rh502_condicao','varchar(15)','(Início,Antes do Início,Meio e Final ) ','', 'Condição',15,'f','f','f',0,'text','Condição');
insert into db_syscampo values(1014281,'rh502_resultado','varchar(158)','soma dias (protela), diminui dias ( antecipa ) ou acumula dias (ação) ','', 'Ação',158,'f','f','f',0,'text','Ação');
insert into db_syscampo values(1014282,'rh502_operador','varchar(7)','Formula +dias ou -dias','', 'Fórmula',7,'f','f','f',0,'text','Fórmula');
insert into db_syscampo values(1014283,'rh502_multiplicador','varchar(10)','multiplicador de dias por dias do assentamento','', 'Multiplicador',10,'f','f','f',0,'text','Multiplicador');
insert into db_syscampo values(1014284,'rh503_sequencial','int8','sequencial da tabela assentconcedeconf','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1014285,'rh503_seqassentconf','int8','Sequencial (rh500_sequencial) da tabela assentconf','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1014286,'rh503_codigo','int8','h12_codigo da tabela tipoasse','0', 'Tipo de Assentamento',10,'f','f','f',1,'text','Tipo de Assentamento');
insert into db_syscampo values(1014287,'rh503_acao','int8','1 - concede, 2 - não concede','0', 'Ação',2,'f','f','f',1,'text','Ação');
insert into db_syscampo values(1014288,'rh503_tipo','int8','1 - acumula, 2 não acumula , 3 - Protela','0', 'Tipo',2,'f','f','f',1,'text','Tipo');
insert into db_syscampo values(1014289,'rh503_formula','varchar(7)','( +dias, +meses )','', 'Formula',7,'f','f','f',0,'text','Formula');
insert into db_syscampo values(1014290,'rh503_condicao','varchar(10)','campo para colocar fórmula para validar a quantidade de dias','', 'Condição',10,'f','f','f',0,'text','Condição');
insert into db_syscampo values(1014291,'rh504_sequencial','int8',' sequencial da tabela concessaocalculo','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1014292,'rh504_regist','int8','rh01_regist da tabela rhpessoa','0', 'Matricula',10,'f','f','f',1,'text','Matricula');
insert into db_syscampo values(1014293,'rh504_seqassentconf','int8','rh500_sequencial (sequencial) da tabela assentconf ','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1014294,'rh504_seqassentperc','int8','rh501_sequencial da assentperc','0', 'Sequencial Percentual',10,'f','f','f',1,'text','Sequencial Percentual');
insert into db_syscampo values(1014295,'rh504_dtproc','date','data do processamento','null', 'Data do Processamento',10,'f','f','f',3,'text','Data do Processamento');
insert into db_syscampo values(1014296,'rh504_data','date','data da concessão','null', 'Data da concessão',10,'f','f','f',0,'text','Data da concessão');
insert into db_syscampo values(1014297,'rh507_sequencial','int4',' sequencial da tabela concessaocalculolog','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1014298,'rh507_concessaocalculo','int8','sequencial da tabela concessaocalculo','0', 'Concessão Calculo',10,'f','f','f',1,'text','Concessão Calculo');
insert into db_syscampo values(1014299,'rh507_codigo','int8','h16_codigo tabela assenta','0', 'Assentamento',10,'f','f','f',1,'text','Assentamento');
insert into db_syscampo values(1014300,'rh506 sequencial','int8','sequencial da tabela concessaocalculonovadata','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1014301,'rh506_concessaocalculo','int8','sequencial tabela concessaocalculo','0', 'Concessao Calculo',10,'f','f','f',1,'text','Concessao Calculo');
insert into db_syscampo values(1014302,'rh506_datanova','date','Data da nova concessão ','null', 'Nova Data',10,'t','f','f',0,'text','Nova Data');
insert into db_syscampo values(1014303,'rh508_sequencial','int8','sequencial da tabela concessaocalculonovadatalog','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1014305,'rh508_concessaocalculo','int8','sequencial da tabela concessaocalculo ','0', 'Concessão Calculo',10,'f','f','f',1,'text','Concessão Calculo');
insert into db_syscampo values(1014306,'rh508_codigo','int8','sequencial da tabela assenta','0', 'Assentamento',10,'f','f','f',1,'text','Assentamento');
insert into db_syscampo values(1014307,'rh505_sequencial','int8','sequencial da tabela','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1014308,'rh505_concessaocalculo','int8','sequncial da tabela concessaocalculo','0', 'Concessão Calculo',10,'f','f','f',1,'text','Concessão Calculo');
insert into db_syscampo values(1014309,'rh505_codigo','int8','código da tabela assenta','0', 'Assentamento',10,'f','f','f',1,'text','Assentamento');
insert into db_syscampo values(1014310,'rh505_anousu','int8','ano e concessão para folha','0', 'Ano',4,'f','f','f',1,'text','Ano');
insert into db_syscampo values(1014311,'rh505_mesusu','int8','mês e concessão para folha','0', 'Mês',2,'f','f','f',1,'text','Mês');
insert into db_syscampo values(1014312,'rh505_data','date',' Data do processamento','null', 'Data do Processamento',10,'f','f','f',0,'text','Data do Processamento');
delete from db_sysarqcamp where codarq = 1010956;
insert into db_sysarqcamp values(1010956,1014270,1,0);
insert into db_sysarqcamp values(1010956,1014268,2,0);
insert into db_sysarqcamp values(1010956,1014267,3,0);
insert into db_sysarqcamp values(1010956,1014266,4,0);
insert into db_sysarqcamp values(1010956,1014265,5,0);
delete from db_sysprikey where codarq = 1010956;
delete from db_sysprikey where codarq = 1010956;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010956,1014265,1,1014270);
delete from db_sysforkey where codarq = 1010956 and referen = 0;
insert into db_sysforkey values(1010956,1014266,1,1010956,0);
insert into db_sysforkey values(1010956,1014270,2,1010956,0);
insert into db_sysforkey values(1010956,1014268,3,1010956,0);
delete from db_sysforkey where codarq = 1010956 and referen = 0;
insert into db_sysforkey values(1010956,1014265,1,596,0);
insert into db_sysforkey values(1010956,1014268,2,596,0);
insert into db_sysforkey values(1010956,1014270,3,596,0);
delete from db_sysforkey where codarq = 1010956 and referen = 1010956;
delete from db_sysarqcamp where codarq = 1010958;
insert into db_sysarqcamp values(1010958,1014276,1,0);
insert into db_sysarqcamp values(1010958,1014275,2,0);
insert into db_sysarqcamp values(1010958,1014274,3,0);
insert into db_sysarqcamp values(1010958,1014273,4,0);
insert into db_sysarqcamp values(1010958,1014272,5,0);
insert into db_sysarqcamp values(1010958,1014271,6,0);
delete from db_sysprikey where codarq = 1010958;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010958,1014271,1,1014276);
delete from db_sysforkey where codarq = 1010958 and referen = 0;
insert into db_sysforkey values(1010958,1014272,1,1010956,0);
update db_sysarquivo set nomearq = 'assentform', descricao = 'Parametros de Concessao e nao concessao do Assentamento', sigla = 'rh502', dataincl = '2022-07-11', rotulo = 'Parametros de Concessao do Assentamento', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 1010959;
UPDATE db_sysarqmod SET codmod = 29 WHERE codarq = 1010959;
delete from db_sysarqarq where codarq = 1010959;
insert into db_sysarqarq values(0,1010959);
delete from db_sysarqcamp where codarq = 1010959;
insert into db_sysarqcamp values(1010959,1014277,1,0);
insert into db_sysarqcamp values(1010959,1014278,2,0);
insert into db_sysarqcamp values(1010959,1014279,3,0);
insert into db_sysarqcamp values(1010959,1014280,4,0);
insert into db_sysarqcamp values(1010959,1014281,5,0);
insert into db_sysarqcamp values(1010959,1014282,6,0);
insert into db_sysarqcamp values(1010959,1014283,7,0);
delete from db_sysprikey where codarq = 1010959;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010959,1014277,1,1014277);
delete from db_sysforkey where codarq = 1010959 and referen = 0;
insert into db_sysforkey values(1010959,1014278,1,1010956,0);
delete from db_sysforkey where codarq = 1010959 and referen = 0;
insert into db_sysforkey values(1010959,1014279,1,596,0);
delete from db_sysarqcamp where codarq = 1010960;
insert into db_sysarqcamp values(1010960,1014284,1,0);
insert into db_sysarqcamp values(1010960,1014285,2,0);
insert into db_sysarqcamp values(1010960,1014286,3,0);
insert into db_sysarqcamp values(1010960,1014287,4,0);
insert into db_sysarqcamp values(1010960,1014288,5,0);
insert into db_sysarqcamp values(1010960,1014289,6,0);
insert into db_sysarqcamp values(1010960,1014290,7,0);
delete from db_sysprikey where codarq = 1010960;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010960,1014284,1,1014284);
delete from db_sysforkey where codarq = 1010960 and referen = 0;
insert into db_sysforkey values(1010960,1014285,1,1010956,0);
delete from db_sysforkey where codarq = 1010960 and referen = 0;
insert into db_sysforkey values(1010960,1014286,1,596,0);
delete from db_sysarqcamp where codarq = 1010961;
insert into db_sysarqcamp values(1010961,1014291,1,0);
insert into db_sysarqcamp values(1010961,1014292,2,0);
insert into db_sysarqcamp values(1010961,1014293,3,0);
insert into db_sysarqcamp values(1010961,1014294,4,0);
insert into db_sysarqcamp values(1010961,1014295,5,0);
insert into db_sysarqcamp values(1010961,1014296,6,0);
delete from db_sysprikey where codarq = 1010961;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010961,1014291,1,1014291);
delete from db_sysforkey where codarq = 1010961 and referen = 0;
insert into db_sysforkey values(1010961,1014292,1,1153,0);
delete from db_sysforkey where codarq = 1010961 and referen = 0;
insert into db_sysforkey values(1010961,1014293,1,1010956,0);
delete from db_sysforkey where codarq = 1010961 and referen = 0;
insert into db_sysforkey values(1010961,1014294,1,1010958,0);
delete from db_sysarqcamp where codarq = 1010963;
insert into db_sysarqcamp values(1010963,1014297,1,0);
insert into db_sysarqcamp values(1010963,1014298,2,0);
insert into db_sysarqcamp values(1010963,1014299,3,0);
delete from db_sysprikey where codarq = 1010963;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010963,1014297,1,1014297);
delete from db_sysforkey where codarq = 1010963 and referen = 0;
insert into db_sysforkey values(1010963,1014298,1,1010961,0);
delete from db_sysforkey where codarq = 1010963 and referen = 0;
insert into db_sysforkey values(1010963,1014299,1,528,0);
delete from db_sysarqcamp where codarq = 1010964;
insert into db_sysarqcamp values(1010964,1014300,1,0);
insert into db_sysarqcamp values(1010964,1014301,2,0);
insert into db_sysarqcamp values(1010964,1014302,3,0);
delete from db_sysprikey where codarq = 1010964;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010964,1014300,1,1014300);
delete from db_sysforkey where codarq = 1010964 and referen = 0;
insert into db_sysforkey values(1010964,1014301,1,1010961,0);
delete from db_sysarqcamp where codarq = 1010965;
insert into db_sysarqcamp values(1010965,1014303,1,0);
insert into db_sysarqcamp values(1010965,1014305,2,0);
insert into db_sysarqcamp values(1010965,1014306,3,0);
delete from db_sysprikey where codarq = 1010965;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010965,1014303,1,1014303);
delete from db_sysarqcamp where codarq = 1010965;
insert into db_sysarqcamp values(1010965,1014303,1,0);
insert into db_sysarqcamp values(1010965,1014305,2,0);
insert into db_sysarqcamp values(1010965,1014306,3,0);
delete from db_sysforkey where codarq = 1010965 and referen = 0;
insert into db_sysforkey values(1010965,1014305,1,1010961,0);
delete from db_sysarqcamp where codarq = 1010966;
insert into db_sysarqcamp values(1010966,1014307,1,0);
insert into db_sysarqcamp values(1010966,1014308,2,0);
insert into db_sysarqcamp values(1010966,1014309,3,0);
insert into db_sysarqcamp values(1010966,1014310,4,0);
insert into db_sysarqcamp values(1010966,1014311,5,0);
insert into db_sysarqcamp values(1010966,1014312,6,0);
delete from db_sysprikey where codarq = 1010966;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010966,1014307,1,1014307);
delete from db_sysforkey where codarq = 1010966 and referen = 0;
insert into db_sysforkey values(1010966,1014308,1,1010961,0);
delete from db_sysforkey where codarq = 1010966 and referen = 0;
insert into db_sysforkey values(1010966,1014309,1,524,0);
delete from db_sysforkey where codarq = 1010966 and referen = 524;
insert into db_sysforkey values(1010966,1014309,1,528,0);
insert into db_syscampo values(1014317,'rh500_selecao','int8',' r44_selec da tabela pessoal.selecao','0', 'Seleção',10,'t','f','f',1,'text','Seleção');
delete from db_sysarqcamp where codarq = 1010956;
insert into db_sysarqcamp values(1010956,1014270,1,0);
insert into db_sysarqcamp values(1010956,1014268,2,0);
insert into db_sysarqcamp values(1010956,1014267,3,0);
insert into db_sysarqcamp values(1010956,1014266,4,0);
insert into db_sysarqcamp values(1010956,1014265,5,0);
insert into db_sysarqcamp values(1010956,1014317,6,0);
delete from db_sysforkey where codarq = 1010956 and referen = 0;
insert into db_sysforkey values(1010956,1014317,1,591,0);
SQL
        );
    }

    protected function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_sysprikey where codarq = 1010956;
delete from db_sysprikey where codarq = 1010958;
delete from db_sysprikey where codarq = 1010959;
delete from db_sysprikey where codarq = 1010960;
delete from db_sysprikey where codarq = 1010961;
delete from db_sysprikey where codarq = 1010963;
delete from db_sysprikey where codarq = 1010964;
delete from db_sysprikey where codarq = 1010965;
delete from db_sysprikey where codarq = 1010966;

delete from db_sysforkey where codarq = 1010956;
delete from db_sysforkey where codarq = 1010958;
delete from db_sysforkey where codarq = 1010959;
delete from db_sysforkey where codarq = 1010960;
delete from db_sysforkey where codarq = 1010961;
delete from db_sysforkey where codarq = 1010963;
delete from db_sysforkey where codarq = 1010964;
delete from db_sysforkey where codarq = 1010965;
delete from db_sysforkey where codarq = 1010966;
delete from db_sysarqarq where codarq = 1010959;

delete from db_sysarqcamp where codarq = 1010956;
delete from db_sysarqcamp where codarq = 1010958;
delete from db_sysarqcamp where codarq = 1010959;
delete from db_sysarqcamp where codarq = 1010960;
delete from db_sysarqcamp where codarq = 1010961;
delete from db_sysarqcamp where codarq = 1010963;
delete from db_sysarqcamp where codarq = 1010964;
delete from db_sysarqcamp where codarq = 1010965;
delete from db_sysarqcamp where codarq = 1010965;
delete from db_sysarqcamp where codarq = 1010966;
delete from db_sysarqcamp where codarq = 1010956;

delete from db_syscampo where codcam = 1014265;
delete from db_syscampo where codcam = 1014266;
delete from db_syscampo where codcam = 1014267;
delete from db_syscampo where codcam = 1014268;
delete from db_syscampo where codcam = 1014270;
delete from db_syscampo where codcam = 1014271;
delete from db_syscampo where codcam = 1014272;
delete from db_syscampo where codcam = 1014273;
delete from db_syscampo where codcam = 1014274;
delete from db_syscampo where codcam = 1014275;
delete from db_syscampo where codcam = 1014276;
delete from db_syscampo where codcam = 1014277;
delete from db_syscampo where codcam = 1014278;
delete from db_syscampo where codcam = 1014279;
delete from db_syscampo where codcam = 1014280;
delete from db_syscampo where codcam = 1014281;
delete from db_syscampo where codcam = 1014282;
delete from db_syscampo where codcam = 1014283;
delete from db_syscampo where codcam = 1014284;
delete from db_syscampo where codcam = 1014285;
delete from db_syscampo where codcam = 1014286;
delete from db_syscampo where codcam = 1014287;
delete from db_syscampo where codcam = 1014288;
delete from db_syscampo where codcam = 1014289;
delete from db_syscampo where codcam = 1014290;
delete from db_syscampo where codcam = 1014291;
delete from db_syscampo where codcam = 1014292;
delete from db_syscampo where codcam = 1014293;
delete from db_syscampo where codcam = 1014294;
delete from db_syscampo where codcam = 1014295;
delete from db_syscampo where codcam = 1014296;
delete from db_syscampo where codcam = 1014297;
delete from db_syscampo where codcam = 1014298;
delete from db_syscampo where codcam = 1014299;
delete from db_syscampo where codcam = 1014300;
delete from db_syscampo where codcam = 1014301;
delete from db_syscampo where codcam = 1014302;
delete from db_syscampo where codcam = 1014303;
delete from db_syscampo where codcam = 1014305;
delete from db_syscampo where codcam = 1014306;
delete from db_syscampo where codcam = 1014307;
delete from db_syscampo where codcam = 1014308;
delete from db_syscampo where codcam = 1014309;
delete from db_syscampo where codcam = 1014310;
delete from db_syscampo where codcam = 1014311;
delete from db_syscampo where codcam = 1014312;
delete from db_syscampo where codcam = 1014317;

delete from db_sysarqmod where codarq = 1010961;
delete from db_sysarqmod where codarq = 1010960;
delete from db_sysarqmod where codarq = 1010963;
delete from db_sysarqmod where codarq = 1010959;
delete from db_sysarqmod where codarq = 1010964;
delete from db_sysarqmod where codarq = 1010958;
delete from db_sysarqmod where codarq = 1010965;
delete from db_sysarqmod where codarq = 1010956;
delete from db_sysarqmod where codarq = 1010966;

delete from db_sysarquivo where codarq = 1010956;
delete from db_sysarquivo where codarq = 1010966;
delete from db_sysarquivo where codarq = 1010958;
delete from db_sysarquivo where codarq = 1010959;
delete from db_sysarquivo where codarq = 1010965;
delete from db_sysarquivo where codarq = 1010960;
delete from db_sysarquivo where codarq = 1010964;
delete from db_sysarquivo where codarq = 1010963;
delete from db_sysarquivo where codarq = 1010961;

SQL
        );
    }
}
