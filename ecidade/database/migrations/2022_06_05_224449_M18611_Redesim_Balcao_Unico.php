<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M18611RedesimBalcaoUnico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upEstrutura();
        $this->upDicionario();
        $this->upCadenderpais();
        $this->upCadendermunicipio();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downEstrutura();
        $this->downDicionario();
        $this->downCadenderpais();
        $this->downCadendermunicipio();
    }

    private function upEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            CREATE TABLE issqn.qualificacaosocio (
                q180_sequencial SERIAL NOT NULL,
                q180_codigo INTEGER NOT NULL,
                q180_descricao TEXT NOT NULL,
                CONSTRAINT "qualificacaosocio_pk" PRIMARY KEY ("q180_sequencial")
            );

            INSERT INTO issqn.qualificacaosocio VALUES
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 5  ,'ADMINISTRADOR'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 8 ,'CONSELHEIRO DE ADMINISTRAÇÃO'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 10 ,'DIRETOR'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 16 ,'PRESIDENTE'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 17 ,'PROCURADOR'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 18 ,'SECRETÁRIO'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 20 ,'SOCIEDADE CONSORCIADA'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 21 ,'SOCIEDADE FILIADA'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 22 ,'SÓCIO'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 23 ,'SÓCIO CAPITALISTA'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 24 ,'SÓCIO COMANDITADO'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 25 ,'SÓCIO COMANDITÁRIO'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 26 ,'SÓCIO DE INDÚSTRIA'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 28 ,'SÓCIO-GERENTE'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 29 ,'SÓCIO INCAPAZ OU RELAT.INCAPAZ (EXCETO MENOR)'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 30 ,'SÓCIO MENOR (ASSISTIDO/REPRESENTADO)'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 31 ,'SÓCIO OSTENSIVO'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 33 ,'TESOUREIRO'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 37 ,'SÓCIO PESSOA JURÍDICA DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 38 ,'SÓCIO PESSOA FÍSICA RESIDENTE NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 47 ,'SÓCIO PESSOA FÍSICA RESIDENTE NO BRASIL'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 48 ,'SÓCIO PESSOA JURÍDICA DOMICILIADO NO BRASIL'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 49 ,'SÓCIO-ADMINISTRADOR'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 52 ,'SÓCIO COM CAPITAL'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 53 ,'SÓCIO SEM CAPITAL'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 54 ,'FUNDADOR'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 55 ,'SÓCIO COMANDITADO RESIDENTE NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 56 ,'SÓCIO COMANDITÁRIO PESSOA FÍSICA RESIDENTE NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 57 ,'SÓCIO COMANDITÁRIO PESSOA JURÍDICA DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 58 ,'SÓCIO COMANDITÁRIO INCAPAZ'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 59 ,'PRODUTOR RURAL'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 63 ,'COTAS EM TESOURARIA'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 64 ,'ADMINISTRADOR JUDICIAL'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 65 ,'TITULAR PESSOA FÍSICA RESIDENTE OU DOMICILIADO NO BRASIL'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 66 ,'TITULAR PESSOA FÍSICA RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 67 ,'TITULAR PESSOA FÍSICA INCAPAZ OU RELATIVAMENTE INCAPAZ (EXCETO MENOR)'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 68 ,'TITULAR PESSOA FÍSICA MENOR (ASSISTIDO/REPRESENTADO)'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 70 ,'ADMINISTRADOR RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 71 ,'CONSELHEIRO DE ADMINISTRAÇÃO RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 72 ,'DIRETOR RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 73 ,'PRESIDENTE RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 74 ,'SÓCIO-ADMINISTRADOR RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 75 ,'FUNDADOR RESIDENTE OU DOMICILIADO NO EXTERIOR'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 76 ,'PROTETOR'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 77 ,'VICE-PRESIDENTE'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 78 ,'TITULAR PESSOA JURÍDICA DOMICILIADA NO BRASIL'),
            (NEXTVAL('qualificacaosocio_q180_sequencial_seq'), 79 ,'TITULAR PESSOA JURÍDICA DOMICILIADA NO EXTERIOR');

            ALTER TABLE issqn.socios ADD COLUMN q95_qualificacaosocio INTEGER;
            ALTER TABLE issqn.socios ADD CONSTRAINT "socios_qualificacaosocio_fk" FOREIGN KEY ("q95_qualificacaosocio") REFERENCES issqn.qualificacaosocio ("q180_sequencial");

            CREATE TABLE issqn.dadosredesim (
                q181_sequencial SERIAL NOT NULL,
                q181_inscricao INTEGER NOT NULL,
                q181_processo INTEGER,
                q181_dadosbalcaounico JSON NOT NULL,
                CONSTRAINT "dadosredesim_pk" PRIMARY KEY ("q181_sequencial"),
                CONSTRAINT "dadosredesim_issbase_fk" FOREIGN KEY ("q181_inscricao") REFERENCES issqn.issbase ("q02_inscr"),
                CONSTRAINT "dadosredesim_protprocesso_fk" FOREIGN KEY ("q181_processo") REFERENCES protocolo.protprocesso ("p58_codproc")
            );

            INSERT INTO formareclamacao
                (
                    p42_sequencial,
                    p42_descricao
                )
            VALUES
                (
                    10,
                    'REDESIM'
                );


            INSERT INTO tipoproc
                (
                    p51_codigo,
                    p51_descr,
                    p51_dtlimite,
                    p51_instit,
                    p51_tipoprocgrupo,
                    p51_identificado,
                    p51_prottipodocumentoprocesso,
                    p51_linksaibamais,
                    p51_itemmenu,
                    p51_mensagem
                )
            VALUES
               (
                    1000,
                    'INCLUSÃO DE INSCRIÇÃO',
                    null,
                    1,
                    1,
                    't',
                    1,
                    '',
                    '',
                    ''
               );


            INSERT INTO tipoprocformareclamacao
                (
                    p43_sequencial,
                    p43_formareclamacao,
                    p43_tipoproc
                )
            VALUES
                (
                    NEXTVAL('tipoprocformareclamacao_p43_sequencial_seq'),
                    10,
                    1000
                );

            CREATE TABLE issqn.inscricaoredesim (
                q179_sequencial SERIAL NOT NULL,
                q179_inscricao INTEGER NOT NULL,
                q179_processo INTEGER NOT NULL,
                q179_identificadorredesim TEXT NOT NULL,
                CONSTRAINT "inscricaoredesim_pk" PRIMARY KEY ("q179_sequencial"),
                CONSTRAINT "inscricaoredesim_issbase_fk" FOREIGN KEY ("q179_inscricao") REFERENCES issqn.issbase ("q02_inscr"),
                CONSTRAINT "inscricaoredesim_protprocesso_fk" FOREIGN KEY ("q179_processo") REFERENCES protocolo.protprocesso ("p58_codproc")
            );

            INSERT INTO configuracoes.tipoempresa
                    (
                        db98_sequencial,
                        db98_descricao
                    )
                 VALUES
                    (1015,	'ÓRGÃO PÚBLICO DO PODER EXECUTIVO FEDERAL'),
                    (1023,	'ÓRGÃO PÚBLICO DO PODER EXECUTIVO ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1031,	'ÓRGÃO PÚBLICO DO PODER EXECUTIVO MUNICIPAL'),
                    (1040,	'ÓRGÃO PÚBLICO DO PODER LEGISLATIVO FEDERAL'),
                    (1058,	'ÓRGÃO PÚBLICO DO PODER LEGISLATIVO ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1066,	'ÓRGÃO PÚBLICO DO PODER LEGISLATIVO MUNICIPAL'),
                    (1074,	'ÓRGÃO PÚBLICO DO PODER JUDICIÁRIO FEDERAL'),
                    (1082,	'ÓRGÃO PÚBLICO DO PODER JUDICIÁRIO ESTADUAL'),
                    (1104,	'AUTARQUIA FEDERAL'),
                    (1112,	'AUTARQUIA ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1120,	'AUTARQUIA MUNICIPAL'),
                    (1139,	'FUNDAÇÃOPÚBLICA DE DIREITO PÚBLICOFEDERAL'),
                    (1147,	'FUNDAÇÃOPÚBLICA DE DIREITO PÚBLICOESTADUAL OU DO DISTRITO FEDERAL'),
                    (1155,	'FUNDAÇÃOPÚBLICA DE DIREITO PÚBLICOMUNICIPAL'),
                    (1163,	'ÓRGÃO PÚBLICO AUTÔNOMO FEDERAL'),
                    (1171,	'ÓRGÃO PÚBLICO AUTÔNOMO ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1180,	'ÓRGÃO PÚBLICO AUTÔNOMO MUNICIPAL'),
                    (1198,	'COMISSÃO POLINACIONAL'),
                    (1210,	'CONSÓRCIO PÚBLICO DE DIREITO PÚBLICO(ASSOCIAÇÃO PÚBLICA)'),
                    (1228,	'CONSÓRCIO PÚBLICO DE DIREITO PRIVADO'),
                    (1236,	'ESTADO OU DISTRITO FEDERAL'),
                    (1244,	'MUNICÍPIO'),
                    (1252,	'CONSÓRCIO PÚBLICO DE DIREITO PRIVADO FEDERAL'),
                    (1260,	'CONSÓRCIO PÚBLICO DE DIREITO PRIVADO ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1279,	'CONSÓRCIO PÚBLICO DE DIREITO PRIVADO MUNICIPAL'),
                    (1287,	'FUNDO PÚBLICO DA ADMINISTRAÇÃO INDIRETA FEDERAL'),
                    (1295,	'FUNDO PÚBLICO DA ADMINISTRAÇÃO INDIRETA ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1309,	'FUNDO PÚBLICO DA ADMINISTRAÇÃO INDIRETA MUNICIPAL'),
                    (1317,	'FUNDO PÚBLICO DA ADMINISTRAÇÃO DIRETA FEDERAL'),
                    (1325,	'FUNDO PÚBLICO DA ADMINISTRAÇÃO DIRETA ESTADUAL OU DO DISTRITO FEDERAL'),
                    (1333,	'FUNDO PÚBLICO DA ADMINISTRAÇÃO DIRETA MUNICIPAL'),
                    (1341,	'UNIÃO'),
                    (2011,	'EMPRESA PÚBLICA'),
                    (2038,	'SOCIEDADE DE ECONOMIA MISTA'),
                    (2046,	'SOCIEDADE ANÔNIMA ABERTA'),
                    (2054,	'SOCIEDADE ANÔNIMA FECHADA'),
                    (2062,	'SOCIEDADE EMPRESÁRIA LIMITADA'),
                    (2070,	'SOCIEDADE EMPRESÁRIA EM NOME COLETIVO'),
                    (2089,	'SOCIEDADE EMPRESÁRIA EM COMANDITA SIMPLES'),
                    (2097,	'SOCIEDADE EMPRESÁRIA EM COMANDITA POR AÇÕES'),
                    (2127,	'SOCIEDADE EM CONTA DE PARTICIPAÇÃO'),
                    (2135,	'EMPRESÁRIO (INDIVIDUAL)'),
                    (2143,	'COOPERATIVA'),
                    (2151,	'CONSÓRCIO DE SOCIEDADES'),
                    (2160,	'GRUPO DE SOCIEDADES'),
                    (2178,	'ESTABELECIMENTO, NO BRASIL, DE SOCIEDADE ESTRANGEIRA'),
                    (2194,	'ESTABELECIMENTO, NO BRASIL, DE EMPRESA BINACIONAL ARGENTINO-BRASILEIRA'),
                    (2216,	'EMPRESA DOMICILIADA NO EXTERIOR'),
                    (2224,	'CLUBE/FUNDO DE INVESTIMENTO'),
                    (2232,	'SOCIEDADE SIMPLES PURA'),
                    (2240,	'SOCIEDADE SIMPLES LIMITADA'),
                    (2259,	'SOCIEDADE SIMPLES EM NOME COLETIVO'),
                    (2267,	'SOCIEDADE SIMPLES EM COMANDITA SIMPLES'),
                    (2275,	'EMPRESA BINACIONAL'),
                    (2283,	'CONSÓRCIO DE EMPREGADORES'),
                    (2291,	'CONSÓRCIO SIMPLES'),
                    (2305,	'EMPRESA INDIVIDUAL DE RESPONSABILIDADE LIMITADA (DE NATUREZA EMPRESÁRIA)'),
                    (2313,	'EMPRESA INDIVIDUAL DE RESPONSABILIDADE LIMITADA (DE NATUREZA SIMPLES)'),
                    (2321,	'SOCIEDADE UNIPESSOAL DE ADVOCACIA'),
                    (2330,	'COOPERATIVAS DE CONSUMO'),
                    (2348,	'EMPRESA SIMPLES DE INOVAÇÃO - INOVA SIMPLES'),
                    (3034,	'SERVIÇO NOTARIAL E REGISTRAL (CARTÓRIO)'),
                    (3069,	'FUNDAÇÃO PRIVADA'),
                    (3077,	'SERVIÇO SOCIAL AUTÔNOMO'),
                    (3085,	'CONDOMÍNIO EDILÍCIO'),
                    (3107,	'COMISSÃO DE CONCILIAÇÃO PRÉVIA'),
                    (3115,	'ENTIDADE DE MEDIAÇÃO E ARBITRAGEM'),
                    (3131,	'ENTIDADE SINDICAL'),
                    (3204,	'ESTABELECIMENTO, NO BRASIL, DE FUNDAÇÃO OU ASSOCIAÇÃO ESTRANGEIRAS'),
                    (3212,	'FUNDAÇÃO OU ASSOCIAÇÃO DOMICILIADA NO EXTERIOR'),
                    (3220,	'ORGANIZAÇÃO RELIGIOSA'),
                    (3239,	'COMUNIDADE INDÍGENA'),
                    (3247,	'FUNDO PRIVADO'),
                    (3255,	'ÓRGÃO DE DIREÇÃO NACIONAL DE PARTIDO POLÍTICO'),
                    (3263,	'ÓRGÃO DE DIREÇÃO REGIONAL DE PARTIDO POLÍTICO'),
                    (3271,	'ÓRGÃO DE DIREÇÃO LOCAL DE PARTIDO POLÍTICO'),
                    (3280,	'COMITÊ FINANCEIRO DE PARTIDO POLÍTICO'),
                    (3298,	'FRENTE PLEBISCITÁRIA OU REFERENDÁRIA'),
                    (3301,	'ORGANIZAÇÃO SOCIAL (OS)'),
                    (3999,	'ASSOCIAÇÃO PRIVADA'),
                    (4014,	'EMPRESA INDIVIDUAL IMOBILIÁRIA'),
                    (4022,	'SEGURADO ESPECIAL'),
                    (4090,	'CANDIDATO A CARGO POLÍTICO ELETIVO'),
                    (4111,	'LEILOEIRO'),
                    (4120,	'PRODUTOR RURAL (PESSOA FÍSICA)'),
                    (5010,	'ORGANIZAÇÃO INTERNACIONAL'),
                    (5029,	'REPRESENTAÇÃO DIPLOMÁTICA ESTRANGEIRA'),
                    (5037,	'OUTRAS INSTITUIÇÕES EXTRATERRITORIAIS');

SQL
        );
    }

    private function downEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            DELETE FROM tipoprocformareclamacao WHERE p43_formareclamacao = 10;
            DELETE FROM tipoproc WHERE p51_codigo = 1000;
            DELETE FROM formareclamacao WHERE p42_sequencial = 10;

            ALTER TABLE issqn.socios DROP CONSTRAINT "socios_qualificacaosocio_fk";
            ALTER TABLE issqn.socios DROP COLUMN q95_qualificacaosocio;

            DROP TABLE issqn.inscricaoredesim;
            DROP TABLE issqn.qualificacaosocio;
            DROP TABLE issqn.dadosredesim;

            DELETE FROM configuracoes.tipoempresa
                  WHERE db98_sequencial IN (1015, 1023, 1031, 1040, 1058, 1066, 1074, 1082, 1104, 1112, 1120, 1139, 1147, 1155, 1163, 1171, 1180, 1198, 1210, 1228, 1236, 1244, 1252, 1260, 1279, 1287, 1295, 1309, 1317, 1325, 1333, 1341, 2011, 2038, 2046, 2054, 2062, 2070, 2089, 2097, 2127, 2135, 2143, 2151, 2160, 2178, 2194, 2216, 2224, 2232, 2240, 2259, 2267, 2275, 2283, 2291, 2305, 2313, 2321, 2330, 2348, 3034, 3069, 3077, 3085, 3107, 3115, 3131, 3204, 3212, 3220, 3239, 3247, 3255, 3263, 3271, 3280, 3298, 3301, 3999, 4014, 4022, 4090, 4111, 4120, 5010, 5029, 5037);
SQL
        );
    }

    private function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            INSERT INTO db_sysarquivo VALUES (1010900, 'inscricaoredesim', 'Tabela que salva quais inscrições foram geradas a partir da REDESIM', 'q179', '2022-04-19', 'Inscrição REDESIM', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (3,1010900);

            INSERT INTO db_syscampo VALUES(1013996,'q179_sequencial','int8','Sequencial da tabela inscricaoredesim','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            INSERT INTO db_syscampo VALUES(1013997,'q179_inscricao','int8','Campo que salva o número da inscrição.','0', 'Inscrição',11,'f','f','f',1,'text','Inscrição');
            INSERT INTO db_syscampo VALUES(1013998,'q179_processo','int8','Código do processo que originou a inscrição.','0', 'Processo',11,'f','f','f',1,'text','Processo');
            INSERT INTO db_syscampo VALUES(1014214,'q179_identificadorredesim','text','Código identificador da REDESIM','', 'Identificador da REDESIM',255,'f','f','f',0,'text','Identificador da REDESIM');

            INSERT INTO db_syssequencia VALUES(1001046, 'inscricaoredesim_q179_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            INSERT INTO db_sysarqcamp VALUES(1010900,1013996,1,1001046);
            INSERT INTO db_sysarqcamp VALUES(1010900,1013997,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010900,1013998,3,0);
            INSERT INTO db_sysarqcamp VALUES(1010900,1014214,4,0);

            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010900,1013996,1,1013996);

            INSERT INTO db_sysforkey VALUES(1010900,1013997,1,41,0);
            INSERT INTO db_sysforkey VALUES(1010900,1013998,1,403,0);

            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228649 ,'REDESIM' ,'Relatórios REDESIM' ,'' ,'1' ,'1' ,'Relatórios REDESIM' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 30 ,228649 ,843 ,40 );

            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228648 ,'Inscrições geradas a partir da REDESIM' ,'Inscrições geradas a partir da REDESIM' ,'iss179_inscricaoredesim.php' ,'1' ,'1' ,'Rotina para geração de relatório com as inscrições geradas a partir da REDESIM' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228649 ,228648 ,1 ,40 );

            INSERT INTO db_syscampo VALUES(1014036,'db70_codigoreceita','int8','Código da receita referente ao país.','0', 'Código da Receita',11,'f','f','f',1,'text','Código da Receita');
            INSERT INTO db_sysarqcamp VALUES(2779,1014036,4,0);

            insert into db_sysarquivo values (1010940, 'qualificacaosocio', 'Esta tabela guarda as qualificações que um sócio da empresa tem.', 'q180', '2022-06-12', 'Qualificações para o Sócio', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (3,1010940);
            insert into db_sysarqarq values(0,1010940);

            insert into db_syscampo values(1014194,'q180_sequencial','int8','Sequencial da tabela qualificacaosocio','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1014195,'q180_codigo','int8','Código da qualificação na receita federal.','0', 'Código da Receita',11,'f','f','f',1,'text','Código da Receita');
            insert into db_syscampo values(1014196,'q180_descricao','text','Descrição da qualificação','', 'Descrição da qualificação',255,'f','t','f',0,'text','Descrição da qualificação');

            insert into db_syssequencia values(1001067, 'qualificacaosocio_q180_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            insert into db_sysarqcamp values(1010940,1014194,1,1001067);
            insert into db_sysarqcamp values(1010940,1014195,2,0);
            insert into db_sysarqcamp values(1010940,1014196,3,0);

            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010940,1014194,1,1014194);

            insert into db_syscampo values(1014200,'q95_qualificacaosocio','int8','Qualificação do Sócio','0', 'Qualificação do Sócio',11,'t','f','f',1,'text','Qualificação do Sócio');
            insert into db_sysarqcamp values(66,1014200,5,0);
            insert into db_sysforkey values(66,1014200,1,1010940,0);

            insert into db_sysarquivo values (1010942, 'dadosredesim', 'Esta tabela salva os JSONs e XMLs enviados pela REDESIM para conferências dos dados', 'q181', '2022-06-12', 'Dados enviados pela REDESIM', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (3,1010942);

            insert into db_syscampo values(1014201,'q181_sequencial','int8','Sequencial da tabela dadosredesim','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1014202,'q181_inscricao','int8','Inscrição','0', 'Inscrição',11,'f','f','f',1,'text','Inscrição');
            insert into db_syscampo values(1014203,'q181_processo','int8','Processo','0', 'Processo',11,'t','f','f',1,'text','Processo');
            insert into db_syscampo values(1014204,'q181_dadosbalcaounico','text','Campo que salva os dados que foram recebidos via requisição da modalidade Balcão Único da REDESIM','', 'Dados Balcão Único',1000,'f','f','f',0,'text','Dados Balcão Único');

            insert into db_syssequencia values(1001069, 'dadosredesim_q181_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            insert into db_sysarqcamp values(1010942,1014201,1,1001069);
            insert into db_sysarqcamp values(1010942,1014202,2,0);
            insert into db_sysarqcamp values(1010942,1014203,3,0);
            insert into db_sysarqcamp values(1010942,1014204,4,0);

            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010942,1014201,1,1014201);

            insert into db_sysforkey values(1010942,1014202,1,41,0);
            insert into db_sysforkey values(1010942,1014203,1,403,0);
SQL
        );
    }

    private function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            DELETE FROM db_sysforkey WHERE codarq IN (
                /* inscricaoredesim */
                1010900,
                /* qualificacaosocio */
                1010940,
                /* dadosredesim */
                1010942
            );

            DELETE FROM db_sysforkey WHERE codcam IN (
                /* socios */
                1014200
            );

            DELETE FROM db_sysprikey WHERE codarq IN (
                /* inscricaoredesim */
                1010900,
                /* qualificacaosocio */
                1010940,
                /* dadosredesim */
                1010942
            );

            DELETE FROM db_sysarqcamp WHERE codarq IN (
                /* inscricaoredesim */
                1010900,
                /* qualificacaosocio */
                1010940,
                /* dadosredesim */
                1010942
            );

            DELETE FROM db_sysarqcamp WHERE codcam IN (
                /* inscricaoredesim */
                1013996,
                1013997,
                1013998,
                1014214,
                /* cadenderpais */
                1014036,
                /* qualificacaosocio */
                1014194,
                1014195,
                1014196,
                /* socios */
                1014200,
                /* dadosredesim */
                1014201,
                1014202,
                1014203,
                1014204
            );

            DELETE FROM db_syssequencia WHERE codsequencia IN (
                /* inscricaoredesim */
                1001046,
                /* qualificacaosocio */
                1001067,
                /* dadosredesim */
                1001069
            );

            DELETE FROM db_syscampo WHERE codcam IN (
                /* inscricaoredesim */
                1013996,
                1013997,
                1013998,
                1014214,
                /* cadenderpais */
                1014036,
                /* qualificacaosocio */
                1014194,
                1014195,
                1014196,
                /* socios */
                1014200,
                /* dadosredesim */
                1014201,
                1014202,
                1014203,
                1014204
            );

            DELETE FROM db_sysarqarq WHERE codarq IN (
                /* qualificacaosocio */
                1010940,
                /* dadosredesim */
                1010942
            );

            DELETE FROM db_sysarqmod WHERE codarq IN (
                /* inscricaoredesim */
                1010900,
                /* qualificacaosocio */
                1010940,
                /* dadosredesim */
                1010942
            );

            DELETE FROM db_sysarquivo WHERE codarq IN (
                /* inscricaoredesim */
                1010900,
                /* qualificacaosocio */
                1010940,
                /* dadosredesim */
                1010942
            );

            DELETE FROM db_menu WHERE modulo = 40 AND id_item_filho IN (
                228648,
                228649
            );

            DELETE FROM db_itensmenu WHERE id_item IN (
                228648,
                228649
            );
SQL
        );
    }
    
    private function upCadenderpais()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            ALTER TABLE cadenderpais ADD COLUMN db70_codigoreceita INTEGER;

            UPDATE cadenderpais SET db70_codigoreceita = 105 WHERE db70_sequencial = 1;
            UPDATE cadenderpais SET db70_codigoreceita = 13 WHERE db70_sequencial = 1001;
            UPDATE cadenderpais SET db70_codigoreceita = 17 WHERE db70_sequencial = 1002;
            UPDATE cadenderpais SET db70_codigoreceita = 23 WHERE db70_sequencial = 1003;
            UPDATE cadenderpais SET db70_codigoreceita = 31 WHERE db70_sequencial = 1004;
            UPDATE cadenderpais SET db70_codigoreceita = 37 WHERE db70_sequencial = 1005;
            UPDATE cadenderpais SET db70_codigoreceita = 40 WHERE db70_sequencial = 1006;
            UPDATE cadenderpais SET db70_codigoreceita = 41 WHERE db70_sequencial = 1007;
            UPDATE cadenderpais SET db70_codigoreceita = 43 WHERE db70_sequencial = 1008;
            UPDATE cadenderpais SET db70_codigoreceita = 47 WHERE db70_sequencial = 1009;
            UPDATE cadenderpais SET db70_codigoreceita = 53 WHERE db70_sequencial = 1010;
            UPDATE cadenderpais SET db70_codigoreceita = 59 WHERE db70_sequencial = 1011;
            UPDATE cadenderpais SET db70_codigoreceita = 63 WHERE db70_sequencial = 1012;
            UPDATE cadenderpais SET db70_codigoreceita = 64 WHERE db70_sequencial = 1013;
            UPDATE cadenderpais SET db70_codigoreceita = 65 WHERE db70_sequencial = 1014;
            UPDATE cadenderpais SET db70_codigoreceita = 69 WHERE db70_sequencial = 1015;
            UPDATE cadenderpais SET db70_codigoreceita = 72 WHERE db70_sequencial = 1016;
            UPDATE cadenderpais SET db70_codigoreceita = 73 WHERE db70_sequencial = 1017;
            UPDATE cadenderpais SET db70_codigoreceita = 77 WHERE db70_sequencial = 1018;
            UPDATE cadenderpais SET db70_codigoreceita = 80 WHERE db70_sequencial = 1019;
            UPDATE cadenderpais SET db70_codigoreceita = 81 WHERE db70_sequencial = 1020;
            UPDATE cadenderpais SET db70_codigoreceita = 83 WHERE db70_sequencial = 1021;
            UPDATE cadenderpais SET db70_codigoreceita = 85 WHERE db70_sequencial = 1022;
            UPDATE cadenderpais SET db70_codigoreceita = 87 WHERE db70_sequencial = 1023;
            UPDATE cadenderpais SET db70_codigoreceita = 88 WHERE db70_sequencial = 1024;
            UPDATE cadenderpais SET db70_codigoreceita = 90 WHERE db70_sequencial = 1025;
            UPDATE cadenderpais SET db70_codigoreceita = 93 WHERE db70_sequencial = 1026;
            UPDATE cadenderpais SET db70_codigoreceita = 97 WHERE db70_sequencial = 1027;
            UPDATE cadenderpais SET db70_codigoreceita = 98 WHERE db70_sequencial = 1028;
            UPDATE cadenderpais SET db70_codigoreceita = 101 WHERE db70_sequencial = 1029;
            UPDATE cadenderpais SET db70_codigoreceita = 108 WHERE db70_sequencial = 1030;
            UPDATE cadenderpais SET db70_codigoreceita = 111 WHERE db70_sequencial = 1031;
            UPDATE cadenderpais SET db70_codigoreceita = 115 WHERE db70_sequencial = 1032;
            UPDATE cadenderpais SET db70_codigoreceita = 119 WHERE db70_sequencial = 1033;
            UPDATE cadenderpais SET db70_codigoreceita = 127 WHERE db70_sequencial = 1034;
            UPDATE cadenderpais SET db70_codigoreceita = 137 WHERE db70_sequencial = 1035;
            UPDATE cadenderpais SET db70_codigoreceita = 141 WHERE db70_sequencial = 1036;
            UPDATE cadenderpais SET db70_codigoreceita = 145 WHERE db70_sequencial = 1037;
            UPDATE cadenderpais SET db70_codigoreceita = 149 WHERE db70_sequencial = 1038;
            UPDATE cadenderpais SET db70_codigoreceita = 151 WHERE db70_sequencial = 1041;
            UPDATE cadenderpais SET db70_codigoreceita = 153 WHERE db70_sequencial = 1042;
            UPDATE cadenderpais SET db70_codigoreceita = 154 WHERE db70_sequencial = 1043;
            UPDATE cadenderpais SET db70_codigoreceita = 158 WHERE db70_sequencial = 1044;
            UPDATE cadenderpais SET db70_codigoreceita = 160 WHERE db70_sequencial = 1045;
            UPDATE cadenderpais SET db70_codigoreceita = 161 WHERE db70_sequencial = 1046;
            UPDATE cadenderpais SET db70_codigoreceita = 163 WHERE db70_sequencial = 1047;
            UPDATE cadenderpais SET db70_codigoreceita = 165 WHERE db70_sequencial = 1048;
            UPDATE cadenderpais SET db70_codigoreceita = 169 WHERE db70_sequencial = 1049;
            UPDATE cadenderpais SET db70_codigoreceita = 173 WHERE db70_sequencial = 1050;
            UPDATE cadenderpais SET db70_codigoreceita = 177 WHERE db70_sequencial = 1051;
            UPDATE cadenderpais SET db70_codigoreceita = 183 WHERE db70_sequencial = 1052;
            UPDATE cadenderpais SET db70_codigoreceita = 187 WHERE db70_sequencial = 1053;
            UPDATE cadenderpais SET db70_codigoreceita = 190 WHERE db70_sequencial = 1054;
            UPDATE cadenderpais SET db70_codigoreceita = 193 WHERE db70_sequencial = 1055;
            UPDATE cadenderpais SET db70_codigoreceita = 195 WHERE db70_sequencial = 1056;
            UPDATE cadenderpais SET db70_codigoreceita = 196 WHERE db70_sequencial = 1057;
            UPDATE cadenderpais SET db70_codigoreceita = 198 WHERE db70_sequencial = 1058;
            UPDATE cadenderpais SET db70_codigoreceita = 199 WHERE db70_sequencial = 1059;
            UPDATE cadenderpais SET db70_codigoreceita = 229 WHERE db70_sequencial = 1060;
            UPDATE cadenderpais SET db70_codigoreceita = 232 WHERE db70_sequencial = 1061;
            UPDATE cadenderpais SET db70_codigoreceita = 235 WHERE db70_sequencial = 1062;
            UPDATE cadenderpais SET db70_codigoreceita = 239 WHERE db70_sequencial = 1063;
            UPDATE cadenderpais SET db70_codigoreceita = 240 WHERE db70_sequencial = 1064;
            UPDATE cadenderpais SET db70_codigoreceita = 243 WHERE db70_sequencial = 1065;
            UPDATE cadenderpais SET db70_codigoreceita = 244 WHERE db70_sequencial = 1066;
            UPDATE cadenderpais SET db70_codigoreceita = 245 WHERE db70_sequencial = 1067;
            UPDATE cadenderpais SET db70_codigoreceita = 246 WHERE db70_sequencial = 1068;
            UPDATE cadenderpais SET db70_codigoreceita = 247 WHERE db70_sequencial = 1069;
            UPDATE cadenderpais SET db70_codigoreceita = 249 WHERE db70_sequencial = 1070;
            UPDATE cadenderpais SET db70_codigoreceita = 251 WHERE db70_sequencial = 1071;
            UPDATE cadenderpais SET db70_codigoreceita = 253 WHERE db70_sequencial = 1072;
            UPDATE cadenderpais SET db70_codigoreceita = 255 WHERE db70_sequencial = 1073;
            UPDATE cadenderpais SET db70_codigoreceita = 259 WHERE db70_sequencial = 1074;
            UPDATE cadenderpais SET db70_codigoreceita = 267 WHERE db70_sequencial = 1075;
            UPDATE cadenderpais SET db70_codigoreceita = 271 WHERE db70_sequencial = 1076;
            UPDATE cadenderpais SET db70_codigoreceita = 275 WHERE db70_sequencial = 1077;
            UPDATE cadenderpais SET db70_codigoreceita = 281 WHERE db70_sequencial = 1078;
            UPDATE cadenderpais SET db70_codigoreceita = 285 WHERE db70_sequencial = 1079;
            UPDATE cadenderpais SET db70_codigoreceita = 289 WHERE db70_sequencial = 1080;
            UPDATE cadenderpais SET db70_codigoreceita = 291 WHERE db70_sequencial = 1081;
            UPDATE cadenderpais SET db70_codigoreceita = 293 WHERE db70_sequencial = 1082;
            UPDATE cadenderpais SET db70_codigoreceita = 297 WHERE db70_sequencial = 1083;
            UPDATE cadenderpais SET db70_codigoreceita = 301 WHERE db70_sequencial = 1084;
            UPDATE cadenderpais SET db70_codigoreceita = 305 WHERE db70_sequencial = 1085;
            UPDATE cadenderpais SET db70_codigoreceita = 309 WHERE db70_sequencial = 1086;
            UPDATE cadenderpais SET db70_codigoreceita = 313 WHERE db70_sequencial = 1087;
            UPDATE cadenderpais SET db70_codigoreceita = 319 WHERE db70_sequencial = 1088;
            UPDATE cadenderpais SET db70_codigoreceita = 325 WHERE db70_sequencial = 1089;
            UPDATE cadenderpais SET db70_codigoreceita = 329 WHERE db70_sequencial = 1090;
            UPDATE cadenderpais SET db70_codigoreceita = 331 WHERE db70_sequencial = 1091;
            UPDATE cadenderpais SET db70_codigoreceita = 334 WHERE db70_sequencial = 1092;
            UPDATE cadenderpais SET db70_codigoreceita = 337 WHERE db70_sequencial = 1093;
            UPDATE cadenderpais SET db70_codigoreceita = 341 WHERE db70_sequencial = 1094;
            UPDATE cadenderpais SET db70_codigoreceita = 345 WHERE db70_sequencial = 1095;
            UPDATE cadenderpais SET db70_codigoreceita = 351 WHERE db70_sequencial = 1096;
            UPDATE cadenderpais SET db70_codigoreceita = 355 WHERE db70_sequencial = 1097;
            UPDATE cadenderpais SET db70_codigoreceita = 357 WHERE db70_sequencial = 1098;
            UPDATE cadenderpais SET db70_codigoreceita = 359 WHERE db70_sequencial = 1099;
            UPDATE cadenderpais SET db70_codigoreceita = 361 WHERE db70_sequencial = 1100;
            UPDATE cadenderpais SET db70_codigoreceita = 365 WHERE db70_sequencial = 1101;
            UPDATE cadenderpais SET db70_codigoreceita = 369 WHERE db70_sequencial = 1102;
            UPDATE cadenderpais SET db70_codigoreceita = 372 WHERE db70_sequencial = 1103;
            UPDATE cadenderpais SET db70_codigoreceita = 375 WHERE db70_sequencial = 1104;
            UPDATE cadenderpais SET db70_codigoreceita = 379 WHERE db70_sequencial = 1105;
            UPDATE cadenderpais SET db70_codigoreceita = 383 WHERE db70_sequencial = 1106;
            UPDATE cadenderpais SET db70_codigoreceita = 386 WHERE db70_sequencial = 1107;
            UPDATE cadenderpais SET db70_codigoreceita = 391 WHERE db70_sequencial = 1108;
            UPDATE cadenderpais SET db70_codigoreceita = 396 WHERE db70_sequencial = 1109;
            UPDATE cadenderpais SET db70_codigoreceita = 399 WHERE db70_sequencial = 1110;
            UPDATE cadenderpais SET db70_codigoreceita = 403 WHERE db70_sequencial = 1111;
            UPDATE cadenderpais SET db70_codigoreceita = 411 WHERE db70_sequencial = 1112;
            UPDATE cadenderpais SET db70_codigoreceita = 420 WHERE db70_sequencial = 1113;
            UPDATE cadenderpais SET db70_codigoreceita = 423 WHERE db70_sequencial = 1114;
            UPDATE cadenderpais SET db70_codigoreceita = 426 WHERE db70_sequencial = 1115;
            UPDATE cadenderpais SET db70_codigoreceita = 427 WHERE db70_sequencial = 1116;
            UPDATE cadenderpais SET db70_codigoreceita = 431 WHERE db70_sequencial = 1117;
            UPDATE cadenderpais SET db70_codigoreceita = 434 WHERE db70_sequencial = 1118;
            UPDATE cadenderpais SET db70_codigoreceita = 438 WHERE db70_sequencial = 1119;
            UPDATE cadenderpais SET db70_codigoreceita = 440 WHERE db70_sequencial = 1120;
            UPDATE cadenderpais SET db70_codigoreceita = 442 WHERE db70_sequencial = 1121;
            UPDATE cadenderpais SET db70_codigoreceita = 445 WHERE db70_sequencial = 1122;
            UPDATE cadenderpais SET db70_codigoreceita = 447 WHERE db70_sequencial = 1123;
            UPDATE cadenderpais SET db70_codigoreceita = 449 WHERE db70_sequencial = 1124;
            UPDATE cadenderpais SET db70_codigoreceita = 450 WHERE db70_sequencial = 1125;
            UPDATE cadenderpais SET db70_codigoreceita = 452 WHERE db70_sequencial = 1126;
            UPDATE cadenderpais SET db70_codigoreceita = 455 WHERE db70_sequencial = 1127;
            UPDATE cadenderpais SET db70_codigoreceita = 458 WHERE db70_sequencial = 1128;
            UPDATE cadenderpais SET db70_codigoreceita = 461 WHERE db70_sequencial = 1129;
            UPDATE cadenderpais SET db70_codigoreceita = 464 WHERE db70_sequencial = 1130;
            UPDATE cadenderpais SET db70_codigoreceita = 467 WHERE db70_sequencial = 1131;
            UPDATE cadenderpais SET db70_codigoreceita = 472 WHERE db70_sequencial = 1132;
            UPDATE cadenderpais SET db70_codigoreceita = 474 WHERE db70_sequencial = 1133;
            UPDATE cadenderpais SET db70_codigoreceita = 476 WHERE db70_sequencial = 1134;
            UPDATE cadenderpais SET db70_codigoreceita = 477 WHERE db70_sequencial = 1135;
            UPDATE cadenderpais SET db70_codigoreceita = 485 WHERE db70_sequencial = 1136;
            UPDATE cadenderpais SET db70_codigoreceita = 488 WHERE db70_sequencial = 1137;
            UPDATE cadenderpais SET db70_codigoreceita = 490 WHERE db70_sequencial = 1139;
            UPDATE cadenderpais SET db70_codigoreceita = 493 WHERE db70_sequencial = 1140;
            UPDATE cadenderpais SET db70_codigoreceita = 494 WHERE db70_sequencial = 1141;
            UPDATE cadenderpais SET db70_codigoreceita = 495 WHERE db70_sequencial = 1142;
            UPDATE cadenderpais SET db70_codigoreceita = 497 WHERE db70_sequencial = 1143;
            UPDATE cadenderpais SET db70_codigoreceita = 498 WHERE db70_sequencial = 1144;
            UPDATE cadenderpais SET db70_codigoreceita = 499 WHERE db70_sequencial = 1145;
            UPDATE cadenderpais SET db70_codigoreceita = 501 WHERE db70_sequencial = 1146;
            UPDATE cadenderpais SET db70_codigoreceita = 505 WHERE db70_sequencial = 1147;
            UPDATE cadenderpais SET db70_codigoreceita = 507 WHERE db70_sequencial = 1148;
            UPDATE cadenderpais SET db70_codigoreceita = 508 WHERE db70_sequencial = 1149;
            UPDATE cadenderpais SET db70_codigoreceita = 511 WHERE db70_sequencial = 1150;
            UPDATE cadenderpais SET db70_codigoreceita = 517 WHERE db70_sequencial = 1151;
            UPDATE cadenderpais SET db70_codigoreceita = 521 WHERE db70_sequencial = 1152;
            UPDATE cadenderpais SET db70_codigoreceita = 525 WHERE db70_sequencial = 1153;
            UPDATE cadenderpais SET db70_codigoreceita = 528 WHERE db70_sequencial = 1154;
            UPDATE cadenderpais SET db70_codigoreceita = 531 WHERE db70_sequencial = 1155;
            UPDATE cadenderpais SET db70_codigoreceita = 535 WHERE db70_sequencial = 1156;
            UPDATE cadenderpais SET db70_codigoreceita = 538 WHERE db70_sequencial = 1157;
            UPDATE cadenderpais SET db70_codigoreceita = 542 WHERE db70_sequencial = 1158;
            UPDATE cadenderpais SET db70_codigoreceita = 545 WHERE db70_sequencial = 1159;
            UPDATE cadenderpais SET db70_codigoreceita = 548 WHERE db70_sequencial = 1160;
            UPDATE cadenderpais SET db70_codigoreceita = 551 WHERE db70_sequencial = 1161;
            UPDATE cadenderpais SET db70_codigoreceita = 556 WHERE db70_sequencial = 1162;
            UPDATE cadenderpais SET db70_codigoreceita = 566 WHERE db70_sequencial = 1163;
            UPDATE cadenderpais SET db70_codigoreceita = 573 WHERE db70_sequencial = 1164;
            UPDATE cadenderpais SET db70_codigoreceita = 575 WHERE db70_sequencial = 1165;
            UPDATE cadenderpais SET db70_codigoreceita = 576 WHERE db70_sequencial = 1166;
            UPDATE cadenderpais SET db70_codigoreceita = 580 WHERE db70_sequencial = 1167;
            UPDATE cadenderpais SET db70_codigoreceita = 586 WHERE db70_sequencial = 1168;
            UPDATE cadenderpais SET db70_codigoreceita = 589 WHERE db70_sequencial = 1169;
            UPDATE cadenderpais SET db70_codigoreceita = 593 WHERE db70_sequencial = 1170;
            UPDATE cadenderpais SET db70_codigoreceita = 599 WHERE db70_sequencial = 1171;
            UPDATE cadenderpais SET db70_codigoreceita = 603 WHERE db70_sequencial = 1172;
            UPDATE cadenderpais SET db70_codigoreceita = 607 WHERE db70_sequencial = 1173;
            UPDATE cadenderpais SET db70_codigoreceita = 611 WHERE db70_sequencial = 1174;
            UPDATE cadenderpais SET db70_codigoreceita = 623 WHERE db70_sequencial = 1175;
            UPDATE cadenderpais SET db70_codigoreceita = 625 WHERE db70_sequencial = 1176;
            UPDATE cadenderpais SET db70_codigoreceita = 628 WHERE db70_sequencial = 1177;
            UPDATE cadenderpais SET db70_codigoreceita = 640 WHERE db70_sequencial = 1178;
            UPDATE cadenderpais SET db70_codigoreceita = 647 WHERE db70_sequencial = 1179;
            UPDATE cadenderpais SET db70_codigoreceita = 660 WHERE db70_sequencial = 1180;
            UPDATE cadenderpais SET db70_codigoreceita = 665 WHERE db70_sequencial = 1181;
            UPDATE cadenderpais SET db70_codigoreceita = 670 WHERE db70_sequencial = 1182;
            UPDATE cadenderpais SET db70_codigoreceita = 675 WHERE db70_sequencial = 1183;
            UPDATE cadenderpais SET db70_codigoreceita = 676 WHERE db70_sequencial = 1184;
            UPDATE cadenderpais SET db70_codigoreceita = 677 WHERE db70_sequencial = 1185;
            UPDATE cadenderpais SET db70_codigoreceita = 685 WHERE db70_sequencial = 1186;
            UPDATE cadenderpais SET db70_codigoreceita = 687 WHERE db70_sequencial = 1187;
            UPDATE cadenderpais SET db70_codigoreceita = 690 WHERE db70_sequencial = 1188;
            UPDATE cadenderpais SET db70_codigoreceita = 691 WHERE db70_sequencial = 1189;
            UPDATE cadenderpais SET db70_codigoreceita = 695 WHERE db70_sequencial = 1190;
            UPDATE cadenderpais SET db70_codigoreceita = 697 WHERE db70_sequencial = 1191;
            UPDATE cadenderpais SET db70_codigoreceita = 700 WHERE db70_sequencial = 1192;
            UPDATE cadenderpais SET db70_codigoreceita = 705 WHERE db70_sequencial = 1193;
            UPDATE cadenderpais SET db70_codigoreceita = 710 WHERE db70_sequencial = 1194;
            UPDATE cadenderpais SET db70_codigoreceita = 715 WHERE db70_sequencial = 1195;
            UPDATE cadenderpais SET db70_codigoreceita = 720 WHERE db70_sequencial = 1196;
            UPDATE cadenderpais SET db70_codigoreceita = 728 WHERE db70_sequencial = 1197;
            UPDATE cadenderpais SET db70_codigoreceita = 731 WHERE db70_sequencial = 1198;
            UPDATE cadenderpais SET db70_codigoreceita = 735 WHERE db70_sequencial = 1199;
            UPDATE cadenderpais SET db70_codigoreceita = 737 WHERE db70_sequencial = 1200;
            UPDATE cadenderpais SET db70_codigoreceita = 741 WHERE db70_sequencial = 1201;
            UPDATE cadenderpais SET db70_codigoreceita = 744 WHERE db70_sequencial = 1202;
            UPDATE cadenderpais SET db70_codigoreceita = 748 WHERE db70_sequencial = 1203;
            UPDATE cadenderpais SET db70_codigoreceita = 750 WHERE db70_sequencial = 1204;
            UPDATE cadenderpais SET db70_codigoreceita = 754 WHERE db70_sequencial = 1205;
            UPDATE cadenderpais SET db70_codigoreceita = 756 WHERE db70_sequencial = 1206;
            UPDATE cadenderpais SET db70_codigoreceita = 759 WHERE db70_sequencial = 1207;
            UPDATE cadenderpais SET db70_codigoreceita = 764 WHERE db70_sequencial = 1208;
            UPDATE cadenderpais SET db70_codigoreceita = 767 WHERE db70_sequencial = 1209;
            UPDATE cadenderpais SET db70_codigoreceita = 770 WHERE db70_sequencial = 1210;
            UPDATE cadenderpais SET db70_codigoreceita = 772 WHERE db70_sequencial = 1211;
            UPDATE cadenderpais SET db70_codigoreceita = 776 WHERE db70_sequencial = 1212;
            UPDATE cadenderpais SET db70_codigoreceita = 780 WHERE db70_sequencial = 1213;
            UPDATE cadenderpais SET db70_codigoreceita = 782 WHERE db70_sequencial = 1214;
            UPDATE cadenderpais SET db70_codigoreceita = 783 WHERE db70_sequencial = 1215;
            UPDATE cadenderpais SET db70_codigoreceita = 788 WHERE db70_sequencial = 1216;
            UPDATE cadenderpais SET db70_codigoreceita = 791 WHERE db70_sequencial = 1217;
            UPDATE cadenderpais SET db70_codigoreceita = 795 WHERE db70_sequencial = 1218;
            UPDATE cadenderpais SET db70_codigoreceita = 800 WHERE db70_sequencial = 1219;
            UPDATE cadenderpais SET db70_codigoreceita = 805 WHERE db70_sequencial = 1220;
            UPDATE cadenderpais SET db70_codigoreceita = 810 WHERE db70_sequencial = 1221;
            UPDATE cadenderpais SET db70_codigoreceita = 815 WHERE db70_sequencial = 1222;
            UPDATE cadenderpais SET db70_codigoreceita = 820 WHERE db70_sequencial = 1223;
            UPDATE cadenderpais SET db70_codigoreceita = 823 WHERE db70_sequencial = 1224;
            UPDATE cadenderpais SET db70_codigoreceita = 824 WHERE db70_sequencial = 1225;
            UPDATE cadenderpais SET db70_codigoreceita = 827 WHERE db70_sequencial = 1226;
            UPDATE cadenderpais SET db70_codigoreceita = 828 WHERE db70_sequencial = 1227;
            UPDATE cadenderpais SET db70_codigoreceita = 831 WHERE db70_sequencial = 1228;
            UPDATE cadenderpais SET db70_codigoreceita = 833 WHERE db70_sequencial = 1229;
            UPDATE cadenderpais SET db70_codigoreceita = 845 WHERE db70_sequencial = 1230;
            UPDATE cadenderpais SET db70_codigoreceita = 847 WHERE db70_sequencial = 1231;
            UPDATE cadenderpais SET db70_codigoreceita = 848 WHERE db70_sequencial = 1232;
            UPDATE cadenderpais SET db70_codigoreceita = 850 WHERE db70_sequencial = 1233;
            UPDATE cadenderpais SET db70_codigoreceita = 858 WHERE db70_sequencial = 1234;
            UPDATE cadenderpais SET db70_codigoreceita = 863 WHERE db70_sequencial = 1235;
            UPDATE cadenderpais SET db70_codigoreceita = 866 WHERE db70_sequencial = 1236;
            UPDATE cadenderpais SET db70_codigoreceita = 870 WHERE db70_sequencial = 1237;
            UPDATE cadenderpais SET db70_codigoreceita = 873 WHERE db70_sequencial = 1238;
            UPDATE cadenderpais SET db70_codigoreceita = 888 WHERE db70_sequencial = 1239;
            UPDATE cadenderpais SET db70_codigoreceita = 890 WHERE db70_sequencial = 1240;

            INSERT INTO cadenderpais
                (
                    db70_sequencial,
                    db70_descricao,
                    db70_codigoreceita
                )
            VALUES
                (150, 'ILHAS DO CANAL (JERSEY E GUERNSEY)', 150),
                (563, 'PACÍFICO, ILHAS DO (ADMINIST.DOS EUA)', 563),
                (895, 'ZONA DO CANAL DO PANAMA', 895),
                (990, 'PROVISAO DE NAVIOS E AERONAVES', 990),
                (994, 'A DESIGNAR', 994),
                (995, 'BANCOS CENTRAIS', 995),
                (997, 'ORGANIZACOES INTERNACIONAIS', 997),
                (998, 'DELEGAÇÃO ESPECIAL DA PALESTINA', 998),
                (999, 'NÃO INFORMADO', 999);
SQL
        );
    }
   
    private function downCadenderpais()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            ALTER TABLE cadenderpais DROP COLUMN db70_codigoreceita;
            DELETE FROM cadenderpais WHERE db70_sequencial IN (150, 563, 895, 990, 994, 995, 997, 998, 999);
SQL
        );
    }

    private function upCadendermunicipio()
    {
        $iCodigoSistemaExterno = 20;

        DB::table('db_sistemaexterno')->insert(
            [
                'db124_sequencial' => $iCodigoSistemaExterno,
                'db124_descricao' => 'RECEITA FEDERAL'
            ]
        );

        DB::connection()->getPdo()->exec(<<<SQL

create temp table dados_inserir as 
with dados_municipios as (
select * from json_populate_recordset(null::record, '[{
   "siglaestado":"AC",
   "nomemunicipio":"ACRELANDIA",
   "codigoexterno":643
},
{
   "siglaestado":"AC",
   "nomemunicipio":"ASSIS BRASIL",
   "codigoexterno":157
},
{
   "siglaestado":"AC",
   "nomemunicipio":"BRASILEIA",
   "codigoexterno":105
},
{
   "siglaestado":"AC",
   "nomemunicipio":"BUJARI",
   "codigoexterno":645
},
{
   "siglaestado":"AC",
   "nomemunicipio":"CAPIXABA",
   "codigoexterno":647
},
{
   "siglaestado":"AC",
   "nomemunicipio":"CRUZEIRO DO SUL",
   "codigoexterno":107
},
{
   "siglaestado":"AC",
   "nomemunicipio":"EPITACIOLANDIA",
   "codigoexterno":651
},
{
   "siglaestado":"AC",
   "nomemunicipio":"FEIJO",
   "codigoexterno":113
},
{
   "siglaestado":"AC",
   "nomemunicipio":"JORDAO",
   "codigoexterno":653
},
{
   "siglaestado":"AC",
   "nomemunicipio":"MANCIO LIMA",
   "codigoexterno":109
},
{
   "siglaestado":"AC",
   "nomemunicipio":"MANOEL URBANO",
   "codigoexterno":155
},
{
   "siglaestado":"AC",
   "nomemunicipio":"MARECHAL THAUMATURGO",
   "codigoexterno":655
},
{
   "siglaestado":"AC",
   "nomemunicipio":"PLACIDO DE CASTRO",
   "codigoexterno":151
},
{
   "siglaestado":"AC",
   "nomemunicipio":"PORTO ACRE",
   "codigoexterno":649
},
{
   "siglaestado":"AC",
   "nomemunicipio":"PORTO WALTER",
   "codigoexterno":657
},
{
   "siglaestado":"AC",
   "nomemunicipio":"RIO BRANCO",
   "codigoexterno":139
},
{
   "siglaestado":"AC",
   "nomemunicipio":"RODRIGUES ALVES",
   "codigoexterno":659
},
{
   "siglaestado":"AC",
   "nomemunicipio":"SANTA ROSA",
   "codigoexterno":661
},
{
   "siglaestado":"AC",
   "nomemunicipio":"SENA MADUREIRA",
   "codigoexterno":145
},
{
   "siglaestado":"AC",
   "nomemunicipio":"SENADOR GUIOMARD",
   "codigoexterno":153
},
{
   "siglaestado":"AC",
   "nomemunicipio":"TARAUACA",
   "codigoexterno":147
},
{
   "siglaestado":"AC",
   "nomemunicipio":"XAPURI",
   "codigoexterno":149
},
{
   "siglaestado":"AL",
   "nomemunicipio":"AGUA BRANCA",
   "codigoexterno":2701
},
{
   "siglaestado":"AL",
   "nomemunicipio":"ANADIA",
   "codigoexterno":2703
},
{
   "siglaestado":"AL",
   "nomemunicipio":"ARAPIRACA",
   "codigoexterno":2705
},
{
   "siglaestado":"AL",
   "nomemunicipio":"ATALAIA",
   "codigoexterno":2707
},
{
   "siglaestado":"AL",
   "nomemunicipio":"BARRA DE SANTO ANTONIO",
   "codigoexterno":2709
},
{
   "siglaestado":"AL",
   "nomemunicipio":"BARRA DE SAO MIGUEL",
   "codigoexterno":2711
},
{
   "siglaestado":"AL",
   "nomemunicipio":"BATALHA",
   "codigoexterno":2713
},
{
   "siglaestado":"AL",
   "nomemunicipio":"BELEM",
   "codigoexterno":2715
},
{
   "siglaestado":"AL",
   "nomemunicipio":"BELO MONTE",
   "codigoexterno":2717
},
{
   "siglaestado":"AL",
   "nomemunicipio":"BOCA DA MATA",
   "codigoexterno":2719
},
{
   "siglaestado":"AL",
   "nomemunicipio":"BRANQUINHA",
   "codigoexterno":2721
},
{
   "siglaestado":"AL",
   "nomemunicipio":"CACIMBINHAS",
   "codigoexterno":2723
},
{
   "siglaestado":"AL",
   "nomemunicipio":"CAJUEIRO",
   "codigoexterno":2725
},
{
   "siglaestado":"AL",
   "nomemunicipio":"CAMPESTRE",
   "codigoexterno":560
},
{
   "siglaestado":"AL",
   "nomemunicipio":"CAMPO ALEGRE",
   "codigoexterno":2727
},
{
   "siglaestado":"AL",
   "nomemunicipio":"CAMPO GRANDE",
   "codigoexterno":2729
},
{
   "siglaestado":"AL",
   "nomemunicipio":"CANAPI",
   "codigoexterno":2731
},
{
   "siglaestado":"AL",
   "nomemunicipio":"CAPELA",
   "codigoexterno":2733
},
{
   "siglaestado":"AL",
   "nomemunicipio":"CARNEIROS",
   "codigoexterno":2735
},
{
   "siglaestado":"AL",
   "nomemunicipio":"CHA PRETA",
   "codigoexterno":2737
},
{
   "siglaestado":"AL",
   "nomemunicipio":"COITE DO NOIA",
   "codigoexterno":2739
},
{
   "siglaestado":"AL",
   "nomemunicipio":"COLONIA LEOPOLDINA",
   "codigoexterno":2741
},
{
   "siglaestado":"AL",
   "nomemunicipio":"COQUEIRO SECO",
   "codigoexterno":2743
},
{
   "siglaestado":"AL",
   "nomemunicipio":"CORURIPE",
   "codigoexterno":2745
},
{
   "siglaestado":"AL",
   "nomemunicipio":"CRAIBAS",
   "codigoexterno":2889
},
{
   "siglaestado":"AL",
   "nomemunicipio":"DELMIRO GOUVEIA",
   "codigoexterno":2747
},
{
   "siglaestado":"AL",
   "nomemunicipio":"DOIS RIACHOS",
   "codigoexterno":2749
},
{
   "siglaestado":"AL",
   "nomemunicipio":"ESTRELA DE ALAGOAS",
   "codigoexterno":2643
},
{
   "siglaestado":"AL",
   "nomemunicipio":"FEIRA GRANDE",
   "codigoexterno":2751
},
{
   "siglaestado":"AL",
   "nomemunicipio":"FELIZ DESERTO",
   "codigoexterno":2753
},
{
   "siglaestado":"AL",
   "nomemunicipio":"FLEXEIRAS",
   "codigoexterno":2755
},
{
   "siglaestado":"AL",
   "nomemunicipio":"GIRAU DO PONCIANO",
   "codigoexterno":2757
},
{
   "siglaestado":"AL",
   "nomemunicipio":"IBATEGUARA",
   "codigoexterno":2759
},
{
   "siglaestado":"AL",
   "nomemunicipio":"IGACI",
   "codigoexterno":2761
},
{
   "siglaestado":"AL",
   "nomemunicipio":"IGREJA NOVA",
   "codigoexterno":2763
},
{
   "siglaestado":"AL",
   "nomemunicipio":"INHAPI",
   "codigoexterno":2765
},
{
   "siglaestado":"AL",
   "nomemunicipio":"JACARE DOS HOMENS",
   "codigoexterno":2767
},
{
   "siglaestado":"AL",
   "nomemunicipio":"JACUIPE",
   "codigoexterno":2769
},
{
   "siglaestado":"AL",
   "nomemunicipio":"JAPARATINGA",
   "codigoexterno":2771
},
{
   "siglaestado":"AL",
   "nomemunicipio":"JARAMATAIA",
   "codigoexterno":2773
},
{
   "siglaestado":"AL",
   "nomemunicipio":"JEQUIA DA PRAIA",
   "codigoexterno":562
},
{
   "siglaestado":"AL",
   "nomemunicipio":"JOAQUIM GOMES",
   "codigoexterno":2775
},
{
   "siglaestado":"AL",
   "nomemunicipio":"JUNDIA",
   "codigoexterno":2777
},
{
   "siglaestado":"AL",
   "nomemunicipio":"JUNQUEIRO",
   "codigoexterno":2779
},
{
   "siglaestado":"AL",
   "nomemunicipio":"LAGOA DA CANOA",
   "codigoexterno":2781
},
{
   "siglaestado":"AL",
   "nomemunicipio":"LIMOEIRO DE ANADIA",
   "codigoexterno":2783
},
{
   "siglaestado":"AL",
   "nomemunicipio":"MACEIO",
   "codigoexterno":2785
},
{
   "siglaestado":"AL",
   "nomemunicipio":"MAJOR ISIDORO",
   "codigoexterno":2787
},
{
   "siglaestado":"AL",
   "nomemunicipio":"MAR VERMELHO",
   "codigoexterno":2797
},
{
   "siglaestado":"AL",
   "nomemunicipio":"MARAGOGI",
   "codigoexterno":2789
},
{
   "siglaestado":"AL",
   "nomemunicipio":"MARAVILHA",
   "codigoexterno":2791
},
{
   "siglaestado":"AL",
   "nomemunicipio":"MARECHAL DEODORO",
   "codigoexterno":2793
},
{
   "siglaestado":"AL",
   "nomemunicipio":"MARIBONDO",
   "codigoexterno":2795
},
{
   "siglaestado":"AL",
   "nomemunicipio":"MATA GRANDE",
   "codigoexterno":2799
},
{
   "siglaestado":"AL",
   "nomemunicipio":"MATRIZ DE CAMARAGIBE",
   "codigoexterno":2801
},
{
   "siglaestado":"AL",
   "nomemunicipio":"MESSIAS",
   "codigoexterno":2803
},
{
   "siglaestado":"AL",
   "nomemunicipio":"MINADOR DO NEGRAO",
   "codigoexterno":2805
},
{
   "siglaestado":"AL",
   "nomemunicipio":"MONTEIROPOLIS",
   "codigoexterno":2807
},
{
   "siglaestado":"AL",
   "nomemunicipio":"MURICI",
   "codigoexterno":2809
},
{
   "siglaestado":"AL",
   "nomemunicipio":"NOVO LINO",
   "codigoexterno":2811
},
{
   "siglaestado":"AL",
   "nomemunicipio":"OLHO D\'AGUA DAS FLORES",
   "codigoexterno":2813
},
{
   "siglaestado":"AL",
   "nomemunicipio":"OLHO D\'AGUA DO CASADO",
   "codigoexterno":2815
},
{
   "siglaestado":"AL",
   "nomemunicipio":"OLHO D\'AGUA GRANDE",
   "codigoexterno":2817
},
{
   "siglaestado":"AL",
   "nomemunicipio":"OLIVENCA",
   "codigoexterno":2819
},
{
   "siglaestado":"AL",
   "nomemunicipio":"OURO BRANCO",
   "codigoexterno":2821
},
{
   "siglaestado":"AL",
   "nomemunicipio":"PALESTINA",
   "codigoexterno":2823
},
{
   "siglaestado":"AL",
   "nomemunicipio":"PALMEIRA DOS INDIOS",
   "codigoexterno":2825
},
{
   "siglaestado":"AL",
   "nomemunicipio":"PAO DE ACUCAR",
   "codigoexterno":2827
},
{
   "siglaestado":"AL",
   "nomemunicipio":"PARICONHA",
   "codigoexterno":2645
},
{
   "siglaestado":"AL",
   "nomemunicipio":"PARIPUEIRA",
   "codigoexterno":2641
},
{
   "siglaestado":"AL",
   "nomemunicipio":"PASSO DE CAMARAGIBE",
   "codigoexterno":2829
},
{
   "siglaestado":"AL",
   "nomemunicipio":"PAULO JACINTO",
   "codigoexterno":2831
},
{
   "siglaestado":"AL",
   "nomemunicipio":"PENEDO",
   "codigoexterno":2833
},
{
   "siglaestado":"AL",
   "nomemunicipio":"PIACABUCU",
   "codigoexterno":2835
},
{
   "siglaestado":"AL",
   "nomemunicipio":"PILAR",
   "codigoexterno":2837
},
{
   "siglaestado":"AL",
   "nomemunicipio":"PINDOBA",
   "codigoexterno":2839
},
{
   "siglaestado":"AL",
   "nomemunicipio":"PIRANHAS",
   "codigoexterno":2841
},
{
   "siglaestado":"AL",
   "nomemunicipio":"POCO DAS TRINCHEIRAS",
   "codigoexterno":2843
},
{
   "siglaestado":"AL",
   "nomemunicipio":"PORTO CALVO",
   "codigoexterno":2845
},
{
   "siglaestado":"AL",
   "nomemunicipio":"PORTO DE PEDRAS",
   "codigoexterno":2847
},
{
   "siglaestado":"AL",
   "nomemunicipio":"PORTO REAL DO COLEGIO",
   "codigoexterno":2849
},
{
   "siglaestado":"AL",
   "nomemunicipio":"QUEBRANGULO",
   "codigoexterno":2851
},
{
   "siglaestado":"AL",
   "nomemunicipio":"RIO LARGO",
   "codigoexterno":2853
},
{
   "siglaestado":"AL",
   "nomemunicipio":"ROTEIRO",
   "codigoexterno":2855
},
{
   "siglaestado":"AL",
   "nomemunicipio":"SANTA LUZIA DO NORTE",
   "codigoexterno":2857
},
{
   "siglaestado":"AL",
   "nomemunicipio":"SANTANA DO IPANEMA",
   "codigoexterno":2859
},
{
   "siglaestado":"AL",
   "nomemunicipio":"SANTANA DO MUNDAU",
   "codigoexterno":2861
},
{
   "siglaestado":"AL",
   "nomemunicipio":"SAO BRAS",
   "codigoexterno":2863
},
{
   "siglaestado":"AL",
   "nomemunicipio":"SAO JOSE DA LAJE",
   "codigoexterno":2865
},
{
   "siglaestado":"AL",
   "nomemunicipio":"SAO JOSE DA TAPERA",
   "codigoexterno":2867
},
{
   "siglaestado":"AL",
   "nomemunicipio":"SAO LUIS DO QUITUNDE",
   "codigoexterno":2869
},
{
   "siglaestado":"AL",
   "nomemunicipio":"SAO MIGUEL DOS CAMPOS",
   "codigoexterno":2871
},
{
   "siglaestado":"AL",
   "nomemunicipio":"SAO MIGUEL DOS MILAGRES",
   "codigoexterno":2873
},
{
   "siglaestado":"AL",
   "nomemunicipio":"SAO SEBASTIAO",
   "codigoexterno":2875
},
{
   "siglaestado":"AL",
   "nomemunicipio":"SATUBA",
   "codigoexterno":2877
},
{
   "siglaestado":"AL",
   "nomemunicipio":"SENADOR RUI PALMEIRA",
   "codigoexterno":2891
},
{
   "siglaestado":"AL",
   "nomemunicipio":"TANQUE D\'ARCA",
   "codigoexterno":2879
},
{
   "siglaestado":"AL",
   "nomemunicipio":"TAQUARANA",
   "codigoexterno":2881
},
{
   "siglaestado":"AL",
   "nomemunicipio":"TEOTONIO VILELA",
   "codigoexterno":971
},
{
   "siglaestado":"AL",
   "nomemunicipio":"TRAIPU",
   "codigoexterno":2883
},
{
   "siglaestado":"AL",
   "nomemunicipio":"UNIAO DOS PALMARES",
   "codigoexterno":2885
},
{
   "siglaestado":"AL",
   "nomemunicipio":"VICOSA",
   "codigoexterno":2887
},
{
   "siglaestado":"AM",
   "nomemunicipio":"ALVARAES",
   "codigoexterno":289
},
{
   "siglaestado":"AM",
   "nomemunicipio":"AMATURA",
   "codigoexterno":291
},
{
   "siglaestado":"AM",
   "nomemunicipio":"ANAMA",
   "codigoexterno":293
},
{
   "siglaestado":"AM",
   "nomemunicipio":"ANORI",
   "codigoexterno":203
},
{
   "siglaestado":"AM",
   "nomemunicipio":"APUI",
   "codigoexterno":969
},
{
   "siglaestado":"AM",
   "nomemunicipio":"ATALAIA DO NORTE",
   "codigoexterno":205
},
{
   "siglaestado":"AM",
   "nomemunicipio":"AUTAZES",
   "codigoexterno":207
},
{
   "siglaestado":"AM",
   "nomemunicipio":"BARCELOS",
   "codigoexterno":209
},
{
   "siglaestado":"AM",
   "nomemunicipio":"BARREIRINHA",
   "codigoexterno":211
},
{
   "siglaestado":"AM",
   "nomemunicipio":"BENJAMIN CONSTANT",
   "codigoexterno":213
},
{
   "siglaestado":"AM",
   "nomemunicipio":"BERURI",
   "codigoexterno":295
},
{
   "siglaestado":"AM",
   "nomemunicipio":"BOA VISTA DO RAMOS",
   "codigoexterno":297
},
{
   "siglaestado":"AM",
   "nomemunicipio":"BOCA DO ACRE",
   "codigoexterno":215
},
{
   "siglaestado":"AM",
   "nomemunicipio":"BORBA",
   "codigoexterno":217
},
{
   "siglaestado":"AM",
   "nomemunicipio":"CAAPIRANGA",
   "codigoexterno":299
},
{
   "siglaestado":"AM",
   "nomemunicipio":"CANUTAMA",
   "codigoexterno":219
},
{
   "siglaestado":"AM",
   "nomemunicipio":"CARAUARI",
   "codigoexterno":221
},
{
   "siglaestado":"AM",
   "nomemunicipio":"CAREIRO",
   "codigoexterno":223
},
{
   "siglaestado":"AM",
   "nomemunicipio":"CAREIRO DA VARZEA",
   "codigoexterno":965
},
{
   "siglaestado":"AM",
   "nomemunicipio":"COARI",
   "codigoexterno":225
},
{
   "siglaestado":"AM",
   "nomemunicipio":"CODAJAS",
   "codigoexterno":227
},
{
   "siglaestado":"AM",
   "nomemunicipio":"EIRUNEPE",
   "codigoexterno":229
},
{
   "siglaestado":"AM",
   "nomemunicipio":"ENVIRA",
   "codigoexterno":231
},
{
   "siglaestado":"AM",
   "nomemunicipio":"FONTE BOA",
   "codigoexterno":233
},
{
   "siglaestado":"AM",
   "nomemunicipio":"GUAJARA",
   "codigoexterno":967
},
{
   "siglaestado":"AM",
   "nomemunicipio":"HUMAITA",
   "codigoexterno":235
},
{
   "siglaestado":"AM",
   "nomemunicipio":"IPIXUNA",
   "codigoexterno":239
},
{
   "siglaestado":"AM",
   "nomemunicipio":"IRANDUBA",
   "codigoexterno":9835
},
{
   "siglaestado":"AM",
   "nomemunicipio":"ITACOATIARA",
   "codigoexterno":241
},
{
   "siglaestado":"AM",
   "nomemunicipio":"ITAMARATI",
   "codigoexterno":9837
},
{
   "siglaestado":"AM",
   "nomemunicipio":"ITAPIRANGA",
   "codigoexterno":243
},
{
   "siglaestado":"AM",
   "nomemunicipio":"JAPURA",
   "codigoexterno":245
},
{
   "siglaestado":"AM",
   "nomemunicipio":"JURUA",
   "codigoexterno":247
},
{
   "siglaestado":"AM",
   "nomemunicipio":"JUTAI",
   "codigoexterno":249
},
{
   "siglaestado":"AM",
   "nomemunicipio":"LABREA",
   "codigoexterno":251
},
{
   "siglaestado":"AM",
   "nomemunicipio":"MANACAPURU",
   "codigoexterno":253
},
{
   "siglaestado":"AM",
   "nomemunicipio":"MANAQUIRI",
   "codigoexterno":9839
},
{
   "siglaestado":"AM",
   "nomemunicipio":"MANAUS",
   "codigoexterno":255
},
{
   "siglaestado":"AM",
   "nomemunicipio":"MANICORE",
   "codigoexterno":257
},
{
   "siglaestado":"AM",
   "nomemunicipio":"MARAA",
   "codigoexterno":259
},
{
   "siglaestado":"AM",
   "nomemunicipio":"MAUES",
   "codigoexterno":261
},
{
   "siglaestado":"AM",
   "nomemunicipio":"NHAMUNDA",
   "codigoexterno":263
},
{
   "siglaestado":"AM",
   "nomemunicipio":"NOVA OLINDA DO NORTE",
   "codigoexterno":265
},
{
   "siglaestado":"AM",
   "nomemunicipio":"NOVO AIRAO",
   "codigoexterno":201
},
{
   "siglaestado":"AM",
   "nomemunicipio":"NOVO ARIPUANA",
   "codigoexterno":267
},
{
   "siglaestado":"AM",
   "nomemunicipio":"PARINTINS",
   "codigoexterno":269
},
{
   "siglaestado":"AM",
   "nomemunicipio":"PAUINI",
   "codigoexterno":271
},
{
   "siglaestado":"AM",
   "nomemunicipio":"PRESIDENTE FIGUEIREDO",
   "codigoexterno":9841
},
{
   "siglaestado":"AM",
   "nomemunicipio":"RIO PRETO DA EVA",
   "codigoexterno":9843
},
{
   "siglaestado":"AM",
   "nomemunicipio":"SANTA ISABEL DO RIO NEGRO",
   "codigoexterno":237
},
{
   "siglaestado":"AM",
   "nomemunicipio":"SANTO ANTONIO DO ICA",
   "codigoexterno":273
},
{
   "siglaestado":"AM",
   "nomemunicipio":"SAO GABRIEL DA CACHOEIRA",
   "codigoexterno":283
},
{
   "siglaestado":"AM",
   "nomemunicipio":"SAO PAULO DE OLIVENCA",
   "codigoexterno":275
},
{
   "siglaestado":"AM",
   "nomemunicipio":"SAO SEBASTIAO DO UATUMA",
   "codigoexterno":9845
},
{
   "siglaestado":"AM",
   "nomemunicipio":"SILVES",
   "codigoexterno":277
},
{
   "siglaestado":"AM",
   "nomemunicipio":"TABATINGA",
   "codigoexterno":9847
},
{
   "siglaestado":"AM",
   "nomemunicipio":"TAPAUA",
   "codigoexterno":279
},
{
   "siglaestado":"AM",
   "nomemunicipio":"TEFE",
   "codigoexterno":281
},
{
   "siglaestado":"AM",
   "nomemunicipio":"TONANTINS",
   "codigoexterno":9851
},
{
   "siglaestado":"AM",
   "nomemunicipio":"UARINI",
   "codigoexterno":9849
},
{
   "siglaestado":"AM",
   "nomemunicipio":"URUCARA",
   "codigoexterno":285
},
{
   "siglaestado":"AM",
   "nomemunicipio":"URUCURITUBA",
   "codigoexterno":287
},
{
   "siglaestado":"AP",
   "nomemunicipio":"AMAPA",
   "codigoexterno":601
},
{
   "siglaestado":"AP",
   "nomemunicipio":"CALCOENE",
   "codigoexterno":603
},
{
   "siglaestado":"AP",
   "nomemunicipio":"CUTIAS",
   "codigoexterno":667
},
{
   "siglaestado":"AP",
   "nomemunicipio":"FERREIRA GOMES",
   "codigoexterno":611
},
{
   "siglaestado":"AP",
   "nomemunicipio":"ITAUBAL",
   "codigoexterno":669
},
{
   "siglaestado":"AP",
   "nomemunicipio":"LARANJAL DO JARI",
   "codigoexterno":613
},
{
   "siglaestado":"AP",
   "nomemunicipio":"MACAPA",
   "codigoexterno":605
},
{
   "siglaestado":"AP",
   "nomemunicipio":"MAZAGAO",
   "codigoexterno":607
},
{
   "siglaestado":"AP",
   "nomemunicipio":"OIAPOQUE",
   "codigoexterno":609
},
{
   "siglaestado":"AP",
   "nomemunicipio":"PEDRA BRANCA DO AMAPARI",
   "codigoexterno":663
},
{
   "siglaestado":"AP",
   "nomemunicipio":"PORTO GRANDE",
   "codigoexterno":671
},
{
   "siglaestado":"AP",
   "nomemunicipio":"PRACUUBA",
   "codigoexterno":673
},
{
   "siglaestado":"AP",
   "nomemunicipio":"SANTANA",
   "codigoexterno":615
},
{
   "siglaestado":"AP",
   "nomemunicipio":"SERRA DO NAVIO",
   "codigoexterno":665
},
{
   "siglaestado":"AP",
   "nomemunicipio":"TARTARUGALZINHO",
   "codigoexterno":617
},
{
   "siglaestado":"AP",
   "nomemunicipio":"VITORIA DO JARI",
   "codigoexterno":70
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ABAIRA",
   "codigoexterno":3301
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ABARE",
   "codigoexterno":3303
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ACAJUTIBA",
   "codigoexterno":3305
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ADUSTINA",
   "codigoexterno":3253
},
{
   "siglaestado":"BA",
   "nomemunicipio":"AGUA FRIA",
   "codigoexterno":3307
},
{
   "siglaestado":"BA",
   "nomemunicipio":"AIQUARA",
   "codigoexterno":3311
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ALAGOINHAS",
   "codigoexterno":3313
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ALCOBACA",
   "codigoexterno":3315
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ALMADINA",
   "codigoexterno":3317
},
{
   "siglaestado":"BA",
   "nomemunicipio":"AMARGOSA",
   "codigoexterno":3319
},
{
   "siglaestado":"BA",
   "nomemunicipio":"AMELIA RODRIGUES",
   "codigoexterno":3321
},
{
   "siglaestado":"BA",
   "nomemunicipio":"AMERICA DOURADA",
   "codigoexterno":3071
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ANAGE",
   "codigoexterno":3323
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ANDARAI",
   "codigoexterno":3325
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ANDORINHA",
   "codigoexterno":3255
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ANGICAL",
   "codigoexterno":3327
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ANGUERA",
   "codigoexterno":3329
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ANTAS",
   "codigoexterno":3331
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ANTONIO CARDOSO",
   "codigoexterno":3333
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ANTONIO GONCALVES",
   "codigoexterno":3335
},
{
   "siglaestado":"BA",
   "nomemunicipio":"APORA",
   "codigoexterno":3337
},
{
   "siglaestado":"BA",
   "nomemunicipio":"APUAREMA",
   "codigoexterno":3257
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ARACAS",
   "codigoexterno":3259
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ARACATU",
   "codigoexterno":3339
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ARACI",
   "codigoexterno":3341
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ARAMARI",
   "codigoexterno":3343
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ARATACA",
   "codigoexterno":3073
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ARATUIPE",
   "codigoexterno":3345
},
{
   "siglaestado":"BA",
   "nomemunicipio":"AURELINO LEAL",
   "codigoexterno":3347
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BAIANOPOLIS",
   "codigoexterno":3349
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BAIXA GRANDE",
   "codigoexterno":3351
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BANZAE",
   "codigoexterno":3261
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BARRA",
   "codigoexterno":3353
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BARRA DA ESTIVA",
   "codigoexterno":3355
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BARRA DO CHOCA",
   "codigoexterno":3357
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BARRA DO MENDES",
   "codigoexterno":3359
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BARRA DO ROCHA",
   "codigoexterno":3361
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BARREIRAS",
   "codigoexterno":3363
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BARRO ALTO",
   "codigoexterno":3075
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BARRO PRETO",
   "codigoexterno":3365
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BARROCAS",
   "codigoexterno":1110
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BELMONTE",
   "codigoexterno":3367
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BELO CAMPO",
   "codigoexterno":3369
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BIRITINGA",
   "codigoexterno":3371
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BOA NOVA",
   "codigoexterno":3373
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BOA VISTA DO TUPIM",
   "codigoexterno":3375
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BOM JESUS DA LAPA",
   "codigoexterno":3377
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BOM JESUS DA SERRA",
   "codigoexterno":3263
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BONINAL",
   "codigoexterno":3379
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BONITO",
   "codigoexterno":3265
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BOQUIRA",
   "codigoexterno":3381
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BOTUPORA",
   "codigoexterno":3383
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BREJOES",
   "codigoexterno":3385
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BREJOLANDIA",
   "codigoexterno":3387
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BROTAS DE MACAUBAS",
   "codigoexterno":3389
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BRUMADO",
   "codigoexterno":3391
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BUERAREMA",
   "codigoexterno":3393
},
{
   "siglaestado":"BA",
   "nomemunicipio":"BURITIRAMA",
   "codigoexterno":3079
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CAATIBA",
   "codigoexterno":3395
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CABACEIRAS DO PARAGUACU",
   "codigoexterno":3267
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CACHOEIRA",
   "codigoexterno":3397
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CACULE",
   "codigoexterno":3399
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CAEM",
   "codigoexterno":3401
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CAETANOS",
   "codigoexterno":3269
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CAETITE",
   "codigoexterno":3403
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CAFARNAUM",
   "codigoexterno":3405
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CAIRU",
   "codigoexterno":3407
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CALDEIRAO GRANDE",
   "codigoexterno":3409
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CAMACAN",
   "codigoexterno":3411
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CAMACARI",
   "codigoexterno":3413
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CAMAMU",
   "codigoexterno":3415
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CAMPO ALEGRE DE LOURDES",
   "codigoexterno":3417
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CAMPO FORMOSO",
   "codigoexterno":3419
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CANAPOLIS",
   "codigoexterno":3421
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CANARANA",
   "codigoexterno":3423
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CANAVIEIRAS",
   "codigoexterno":3425
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CANDEAL",
   "codigoexterno":3427
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CANDEIAS",
   "codigoexterno":3429
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CANDIBA",
   "codigoexterno":3431
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CANDIDO SALES",
   "codigoexterno":3433
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CANSANCAO",
   "codigoexterno":3435
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CANUDOS",
   "codigoexterno":3085
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CAPELA DO ALTO ALEGRE",
   "codigoexterno":3081
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CAPIM GROSSO",
   "codigoexterno":3083
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CARAIBAS",
   "codigoexterno":3271
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CARAVELAS",
   "codigoexterno":3437
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CARDEAL DA SILVA",
   "codigoexterno":3439
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CARINHANHA",
   "codigoexterno":3441
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CASA NOVA",
   "codigoexterno":3443
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CASTRO ALVES",
   "codigoexterno":3445
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CATOLANDIA",
   "codigoexterno":3447
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CATU",
   "codigoexterno":3449
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CATURAMA",
   "codigoexterno":3273
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CENTRAL",
   "codigoexterno":3451
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CHORROCHO",
   "codigoexterno":3453
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CICERO DANTAS",
   "codigoexterno":3455
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CIPO",
   "codigoexterno":3457
},
{
   "siglaestado":"BA",
   "nomemunicipio":"COARACI",
   "codigoexterno":3459
},
{
   "siglaestado":"BA",
   "nomemunicipio":"COCOS",
   "codigoexterno":3461
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CONCEICAO DA FEIRA",
   "codigoexterno":3463
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CONCEICAO DO ALMEIDA",
   "codigoexterno":3465
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CONCEICAO DO COITE",
   "codigoexterno":3467
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CONCEICAO DO JACUIPE",
   "codigoexterno":3469
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CONDE",
   "codigoexterno":3471
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CONDEUBA",
   "codigoexterno":3473
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CONTENDAS DO SINCORA",
   "codigoexterno":3475
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CORACAO DE MARIA",
   "codigoexterno":3477
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CORDEIROS",
   "codigoexterno":3479
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CORIBE",
   "codigoexterno":3481
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CORONEL JOAO SA",
   "codigoexterno":3483
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CORRENTINA",
   "codigoexterno":3485
},
{
   "siglaestado":"BA",
   "nomemunicipio":"COTEGIPE",
   "codigoexterno":3487
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CRAVOLANDIA",
   "codigoexterno":3489
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CRISOPOLIS",
   "codigoexterno":3491
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CRISTOPOLIS",
   "codigoexterno":3493
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CRUZ DAS ALMAS",
   "codigoexterno":3495
},
{
   "siglaestado":"BA",
   "nomemunicipio":"CURACA",
   "codigoexterno":3497
},
{
   "siglaestado":"BA",
   "nomemunicipio":"DARIO MEIRA",
   "codigoexterno":3499
},
{
   "siglaestado":"BA",
   "nomemunicipio":"DIAS D\'AVILA",
   "codigoexterno":3087
},
{
   "siglaestado":"BA",
   "nomemunicipio":"DOM BASILIO",
   "codigoexterno":3501
},
{
   "siglaestado":"BA",
   "nomemunicipio":"DOM MACEDO COSTA",
   "codigoexterno":3503
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ELISIO MEDRADO",
   "codigoexterno":3505
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ENCRUZILHADA",
   "codigoexterno":3507
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ENTRE RIOS",
   "codigoexterno":3509
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ERICO CARDOSO",
   "codigoexterno":3309
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ESPLANADA",
   "codigoexterno":3511
},
{
   "siglaestado":"BA",
   "nomemunicipio":"EUCLIDES DA CUNHA",
   "codigoexterno":3513
},
{
   "siglaestado":"BA",
   "nomemunicipio":"EUNAPOLIS",
   "codigoexterno":3117
},
{
   "siglaestado":"BA",
   "nomemunicipio":"FATIMA",
   "codigoexterno":3089
},
{
   "siglaestado":"BA",
   "nomemunicipio":"FEIRA DA MATA",
   "codigoexterno":3275
},
{
   "siglaestado":"BA",
   "nomemunicipio":"FEIRA DE SANTANA",
   "codigoexterno":3515
},
{
   "siglaestado":"BA",
   "nomemunicipio":"FILADELFIA",
   "codigoexterno":3091
},
{
   "siglaestado":"BA",
   "nomemunicipio":"FIRMINO ALVES",
   "codigoexterno":3517
},
{
   "siglaestado":"BA",
   "nomemunicipio":"FLORESTA AZUL",
   "codigoexterno":3519
},
{
   "siglaestado":"BA",
   "nomemunicipio":"FORMOSA DO RIO PRETO",
   "codigoexterno":3521
},
{
   "siglaestado":"BA",
   "nomemunicipio":"GANDU",
   "codigoexterno":3523
},
{
   "siglaestado":"BA",
   "nomemunicipio":"GAVIAO",
   "codigoexterno":3093
},
{
   "siglaestado":"BA",
   "nomemunicipio":"GENTIO DO OURO",
   "codigoexterno":3525
},
{
   "siglaestado":"BA",
   "nomemunicipio":"GLORIA",
   "codigoexterno":3527
},
{
   "siglaestado":"BA",
   "nomemunicipio":"GONGOGI",
   "codigoexterno":3529
},
{
   "siglaestado":"BA",
   "nomemunicipio":"GOVERNADOR MANGABEIRA",
   "codigoexterno":3531
},
{
   "siglaestado":"BA",
   "nomemunicipio":"GUAJERU",
   "codigoexterno":3095
},
{
   "siglaestado":"BA",
   "nomemunicipio":"GUANAMBI",
   "codigoexterno":3533
},
{
   "siglaestado":"BA",
   "nomemunicipio":"GUARATINGA",
   "codigoexterno":3535
},
{
   "siglaestado":"BA",
   "nomemunicipio":"HELIOPOLIS",
   "codigoexterno":3097
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IACU",
   "codigoexterno":3537
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IBIASSUCE",
   "codigoexterno":3539
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IBICARAI",
   "codigoexterno":3541
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IBICOARA",
   "codigoexterno":3543
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IBICUI",
   "codigoexterno":3545
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IBIPEBA",
   "codigoexterno":3547
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IBIPITANGA",
   "codigoexterno":3551
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IBIQUERA",
   "codigoexterno":3553
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IBIRAPITANGA",
   "codigoexterno":3555
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IBIRAPUA",
   "codigoexterno":3557
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IBIRATAIA",
   "codigoexterno":3559
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IBITIARA",
   "codigoexterno":3561
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IBITITA",
   "codigoexterno":3563
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IBOTIRAMA",
   "codigoexterno":3565
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ICHU",
   "codigoexterno":3567
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IGAPORA",
   "codigoexterno":3569
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IGRAPIUNA",
   "codigoexterno":3277
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IGUAI",
   "codigoexterno":3571
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ILHEUS",
   "codigoexterno":3573
},
{
   "siglaestado":"BA",
   "nomemunicipio":"INHAMBUPE",
   "codigoexterno":3575
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IPECAETA",
   "codigoexterno":3577
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IPIAU",
   "codigoexterno":3579
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IPIRA",
   "codigoexterno":3581
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IPUPIARA",
   "codigoexterno":3583
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IRAJUBA",
   "codigoexterno":3585
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IRAMAIA",
   "codigoexterno":3587
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IRAQUARA",
   "codigoexterno":3589
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IRARA",
   "codigoexterno":3591
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IRECE",
   "codigoexterno":3593
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITABELA",
   "codigoexterno":3279
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITABERABA",
   "codigoexterno":3595
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITABUNA",
   "codigoexterno":3597
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITACARE",
   "codigoexterno":3599
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITAETE",
   "codigoexterno":3601
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITAGI",
   "codigoexterno":3603
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITAGIBA",
   "codigoexterno":3605
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITAGIMIRIM",
   "codigoexterno":3607
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITAGUACU DA BAHIA",
   "codigoexterno":3281
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITAJU DO COLONIA",
   "codigoexterno":3609
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITAJUIPE",
   "codigoexterno":3611
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITAMARAJU",
   "codigoexterno":3613
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITAMARI",
   "codigoexterno":3615
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITAMBE",
   "codigoexterno":3617
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITANAGRA",
   "codigoexterno":3619
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITANHEM",
   "codigoexterno":3621
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITAPARICA",
   "codigoexterno":3623
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITAPE",
   "codigoexterno":3625
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITAPEBI",
   "codigoexterno":3627
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITAPETINGA",
   "codigoexterno":3629
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITAPICURU",
   "codigoexterno":3631
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITAPITANGA",
   "codigoexterno":3633
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITAQUARA",
   "codigoexterno":3635
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITARANTIM",
   "codigoexterno":3637
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITATIM",
   "codigoexterno":3283
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITIRUCU",
   "codigoexterno":3639
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITIUBA",
   "codigoexterno":3641
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITORORO",
   "codigoexterno":3643
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITUACU",
   "codigoexterno":3645
},
{
   "siglaestado":"BA",
   "nomemunicipio":"ITUBERA",
   "codigoexterno":3647
},
{
   "siglaestado":"BA",
   "nomemunicipio":"IUIU",
   "codigoexterno":3285
},
{
   "siglaestado":"BA",
   "nomemunicipio":"JABORANDI",
   "codigoexterno":9859
},
{
   "siglaestado":"BA",
   "nomemunicipio":"JACARACI",
   "codigoexterno":3649
},
{
   "siglaestado":"BA",
   "nomemunicipio":"JACOBINA",
   "codigoexterno":3651
},
{
   "siglaestado":"BA",
   "nomemunicipio":"JAGUAQUARA",
   "codigoexterno":3653
},
{
   "siglaestado":"BA",
   "nomemunicipio":"JAGUARARI",
   "codigoexterno":3655
},
{
   "siglaestado":"BA",
   "nomemunicipio":"JAGUARIPE",
   "codigoexterno":3657
},
{
   "siglaestado":"BA",
   "nomemunicipio":"JANDAIRA",
   "codigoexterno":3659
},
{
   "siglaestado":"BA",
   "nomemunicipio":"JEQUIE",
   "codigoexterno":3661
},
{
   "siglaestado":"BA",
   "nomemunicipio":"JEREMOABO",
   "codigoexterno":3663
},
{
   "siglaestado":"BA",
   "nomemunicipio":"JIQUIRICA",
   "codigoexterno":3665
},
{
   "siglaestado":"BA",
   "nomemunicipio":"JITAUNA",
   "codigoexterno":3667
},
{
   "siglaestado":"BA",
   "nomemunicipio":"JOAO DOURADO",
   "codigoexterno":3099
},
{
   "siglaestado":"BA",
   "nomemunicipio":"JUAZEIRO",
   "codigoexterno":3669
},
{
   "siglaestado":"BA",
   "nomemunicipio":"JUCURUCU",
   "codigoexterno":3287
},
{
   "siglaestado":"BA",
   "nomemunicipio":"JUSSARA",
   "codigoexterno":3671
},
{
   "siglaestado":"BA",
   "nomemunicipio":"JUSSARI",
   "codigoexterno":3069
},
{
   "siglaestado":"BA",
   "nomemunicipio":"JUSSIAPE",
   "codigoexterno":3673
},
{
   "siglaestado":"BA",
   "nomemunicipio":"LAFAIETE COUTINHO",
   "codigoexterno":3675
},
{
   "siglaestado":"BA",
   "nomemunicipio":"LAGEDO DO TABOCAL",
   "codigoexterno":3291
},
{
   "siglaestado":"BA",
   "nomemunicipio":"LAGOA REAL",
   "codigoexterno":3289
},
{
   "siglaestado":"BA",
   "nomemunicipio":"LAJE",
   "codigoexterno":3677
},
{
   "siglaestado":"BA",
   "nomemunicipio":"LAJEDAO",
   "codigoexterno":3679
},
{
   "siglaestado":"BA",
   "nomemunicipio":"LAJEDINHO",
   "codigoexterno":3681
},
{
   "siglaestado":"BA",
   "nomemunicipio":"LAMARAO",
   "codigoexterno":3683
},
{
   "siglaestado":"BA",
   "nomemunicipio":"LAPAO",
   "codigoexterno":3973
},
{
   "siglaestado":"BA",
   "nomemunicipio":"LAURO DE FREITAS",
   "codigoexterno":3685
},
{
   "siglaestado":"BA",
   "nomemunicipio":"LENCOIS",
   "codigoexterno":3687
},
{
   "siglaestado":"BA",
   "nomemunicipio":"LICINIO DE ALMEIDA",
   "codigoexterno":3689
},
{
   "siglaestado":"BA",
   "nomemunicipio":"LIVRAMENTO DE NOSSA SENHORA",
   "codigoexterno":3691
},
{
   "siglaestado":"BA",
   "nomemunicipio":"LUIS EDUARDO MAGALH??ES",
   "codigoexterno":1112
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MACAJUBA",
   "codigoexterno":3693
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MACARANI",
   "codigoexterno":3695
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MACAUBAS",
   "codigoexterno":3697
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MACURURE",
   "codigoexterno":3699
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MADRE DE DEUS",
   "codigoexterno":3293
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MAETINGA",
   "codigoexterno":3975
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MAIQUINIQUE",
   "codigoexterno":3701
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MAIRI",
   "codigoexterno":3703
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MALHADA",
   "codigoexterno":3705
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MALHADA DE PEDRAS",
   "codigoexterno":3707
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MANOEL VITORINO",
   "codigoexterno":3709
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MANSIDAO",
   "codigoexterno":3977
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MARACAS",
   "codigoexterno":3711
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MARAGOGIPE",
   "codigoexterno":3713
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MARAU",
   "codigoexterno":3715
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MARCIONILIO SOUZA",
   "codigoexterno":3717
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MASCOTE",
   "codigoexterno":3719
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MATA DE SAO JOAO",
   "codigoexterno":3721
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MATINA",
   "codigoexterno":3295
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MEDEIROS NETO",
   "codigoexterno":3723
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MIGUEL CALMON",
   "codigoexterno":3725
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MILAGRES",
   "codigoexterno":3727
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MIRANGABA",
   "codigoexterno":3729
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MIRANTE",
   "codigoexterno":3297
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MONTE SANTO",
   "codigoexterno":3731
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MORPARA",
   "codigoexterno":3733
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MORRO DO CHAPEU",
   "codigoexterno":3735
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MORTUGABA",
   "codigoexterno":3737
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MUCUGE",
   "codigoexterno":3739
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MUCURI",
   "codigoexterno":3741
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MULUNGU DO MORRO",
   "codigoexterno":3299
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MUNDO NOVO",
   "codigoexterno":3743
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MUNIZ FERREIRA",
   "codigoexterno":3745
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MUQUEM DO SAO FRANCISCO",
   "codigoexterno":3005
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MURITIBA",
   "codigoexterno":3747
},
{
   "siglaestado":"BA",
   "nomemunicipio":"MUTUIPE",
   "codigoexterno":3749
},
{
   "siglaestado":"BA",
   "nomemunicipio":"NAZARE",
   "codigoexterno":3751
},
{
   "siglaestado":"BA",
   "nomemunicipio":"NILO PECANHA",
   "codigoexterno":3753
},
{
   "siglaestado":"BA",
   "nomemunicipio":"NORDESTINA",
   "codigoexterno":3979
},
{
   "siglaestado":"BA",
   "nomemunicipio":"NOVA CANAA",
   "codigoexterno":3755
},
{
   "siglaestado":"BA",
   "nomemunicipio":"NOVA FATIMA",
   "codigoexterno":3007
},
{
   "siglaestado":"BA",
   "nomemunicipio":"NOVA IBIA",
   "codigoexterno":3009
},
{
   "siglaestado":"BA",
   "nomemunicipio":"NOVA ITARANA",
   "codigoexterno":3757
},
{
   "siglaestado":"BA",
   "nomemunicipio":"NOVA REDENCAO",
   "codigoexterno":3011
},
{
   "siglaestado":"BA",
   "nomemunicipio":"NOVA SOURE",
   "codigoexterno":3759
},
{
   "siglaestado":"BA",
   "nomemunicipio":"NOVA VICOSA",
   "codigoexterno":3761
},
{
   "siglaestado":"BA",
   "nomemunicipio":"NOVO HORIZONTE",
   "codigoexterno":3013
},
{
   "siglaestado":"BA",
   "nomemunicipio":"NOVO TRIUNFO",
   "codigoexterno":3015
},
{
   "siglaestado":"BA",
   "nomemunicipio":"OLINDINA",
   "codigoexterno":3763
},
{
   "siglaestado":"BA",
   "nomemunicipio":"OLIVEIRA DOS BREJINHOS",
   "codigoexterno":3765
},
{
   "siglaestado":"BA",
   "nomemunicipio":"OURICANGAS",
   "codigoexterno":3767
},
{
   "siglaestado":"BA",
   "nomemunicipio":"OUROLANDIA",
   "codigoexterno":3017
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PALMAS DE MONTE ALTO",
   "codigoexterno":3769
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PALMEIRAS",
   "codigoexterno":3771
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PARAMIRIM",
   "codigoexterno":3773
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PARATINGA",
   "codigoexterno":3775
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PARIPIRANGA",
   "codigoexterno":3777
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PAU BRASIL",
   "codigoexterno":3779
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PAULO AFONSO",
   "codigoexterno":3781
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PE DE SERRA",
   "codigoexterno":3981
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PEDRAO",
   "codigoexterno":3783
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PEDRO ALEXANDRE",
   "codigoexterno":3785
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PIATA",
   "codigoexterno":3787
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PILAO ARCADO",
   "codigoexterno":3789
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PINDAI",
   "codigoexterno":3791
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PINDOBACU",
   "codigoexterno":3793
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PINTADAS",
   "codigoexterno":3983
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PIRAI DO NORTE",
   "codigoexterno":3019
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PIRIPA",
   "codigoexterno":3795
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PIRITIBA",
   "codigoexterno":3797
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PLANALTINO",
   "codigoexterno":3799
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PLANALTO",
   "codigoexterno":3801
},
{
   "siglaestado":"BA",
   "nomemunicipio":"POCOES",
   "codigoexterno":3803
},
{
   "siglaestado":"BA",
   "nomemunicipio":"POJUCA",
   "codigoexterno":3805
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PONTO NOVO",
   "codigoexterno":3021
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PORTO SEGURO",
   "codigoexterno":3807
},
{
   "siglaestado":"BA",
   "nomemunicipio":"POTIRAGU??",
   "codigoexterno":3809
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PRADO",
   "codigoexterno":3811
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PRESIDENTE DUTRA",
   "codigoexterno":3813
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PRESIDENTE JANIO QUADROS",
   "codigoexterno":3815
},
{
   "siglaestado":"BA",
   "nomemunicipio":"PRESIDENTE TANCREDO NEVES",
   "codigoexterno":3023
},
{
   "siglaestado":"BA",
   "nomemunicipio":"QUEIMADAS",
   "codigoexterno":3817
},
{
   "siglaestado":"BA",
   "nomemunicipio":"QUIJINGUE",
   "codigoexterno":3819
},
{
   "siglaestado":"BA",
   "nomemunicipio":"QUIXABEIRA",
   "codigoexterno":3025
},
{
   "siglaestado":"BA",
   "nomemunicipio":"RAFAEL JAMBEIRO",
   "codigoexterno":3985
},
{
   "siglaestado":"BA",
   "nomemunicipio":"REMANSO",
   "codigoexterno":3821
},
{
   "siglaestado":"BA",
   "nomemunicipio":"RETIROLANDIA",
   "codigoexterno":3823
},
{
   "siglaestado":"BA",
   "nomemunicipio":"RIACHAO DAS NEVES",
   "codigoexterno":3825
},
{
   "siglaestado":"BA",
   "nomemunicipio":"RIACHAO DO JACUIPE",
   "codigoexterno":3827
},
{
   "siglaestado":"BA",
   "nomemunicipio":"RIACHO DE SANTANA",
   "codigoexterno":3829
},
{
   "siglaestado":"BA",
   "nomemunicipio":"RIBEIRA DO AMPARO",
   "codigoexterno":3831
},
{
   "siglaestado":"BA",
   "nomemunicipio":"RIBEIRA DO POMBAL",
   "codigoexterno":3833
},
{
   "siglaestado":"BA",
   "nomemunicipio":"RIBEIRAO DO LARGO",
   "codigoexterno":3027
},
{
   "siglaestado":"BA",
   "nomemunicipio":"RIO DE CONTAS",
   "codigoexterno":3835
},
{
   "siglaestado":"BA",
   "nomemunicipio":"RIO DO ANTONIO",
   "codigoexterno":3837
},
{
   "siglaestado":"BA",
   "nomemunicipio":"RIO DO PIRES",
   "codigoexterno":3839
},
{
   "siglaestado":"BA",
   "nomemunicipio":"RIO REAL",
   "codigoexterno":3841
},
{
   "siglaestado":"BA",
   "nomemunicipio":"RODELAS",
   "codigoexterno":3843
},
{
   "siglaestado":"BA",
   "nomemunicipio":"RUY BARBOSA",
   "codigoexterno":3845
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SALINAS DA MARGARIDA",
   "codigoexterno":3847
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SALVADOR",
   "codigoexterno":3849
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SANTA BARBARA",
   "codigoexterno":3851
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SANTA BRIGIDA",
   "codigoexterno":3853
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SANTA CRUZ CABRALIA",
   "codigoexterno":3855
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SANTA CRUZ DA VITORIA",
   "codigoexterno":3857
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SANTA INES",
   "codigoexterno":3859
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SANTA LUZIA",
   "codigoexterno":3987
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SANTA MARIA DA VITORIA",
   "codigoexterno":3863
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SANTA RITA DE CASSIA",
   "codigoexterno":3549
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SANTA TERESINHA",
   "codigoexterno":3869
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SANTALUZ",
   "codigoexterno":3861
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SANTANA",
   "codigoexterno":3865
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SANTANOPOLIS",
   "codigoexterno":3867
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SANTO AMARO",
   "codigoexterno":3871
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SANTO ANTONIO DE JESUS",
   "codigoexterno":3873
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SANTO ESTEVAO",
   "codigoexterno":3875
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SAO DESIDERIO",
   "codigoexterno":3877
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SAO DOMINGOS",
   "codigoexterno":3029
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SAO FELIPE",
   "codigoexterno":3881
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SAO FELIX",
   "codigoexterno":3879
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SAO FELIX DO CORIBE",
   "codigoexterno":3031
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SAO FRANCISCO DO CONDE",
   "codigoexterno":3883
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SAO GABRIEL",
   "codigoexterno":3989
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SAO GONCALO DOS CAMPOS",
   "codigoexterno":3885
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SAO JOSE DA VITORIA",
   "codigoexterno":3035
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SAO JOSE DO JACUIPE",
   "codigoexterno":3033
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SAO MIGUEL DAS MATAS",
   "codigoexterno":3887
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SAO SEBASTIAO DO PASSE",
   "codigoexterno":3889
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SAPEACU",
   "codigoexterno":3891
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SATIRO DIAS",
   "codigoexterno":3893
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SAUBARA",
   "codigoexterno":3037
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SAUDE",
   "codigoexterno":3895
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SEABRA",
   "codigoexterno":3897
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SEBASTIAO LARANJEIRAS",
   "codigoexterno":3899
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SENHOR DO BONFIM",
   "codigoexterno":3901
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SENTO SE",
   "codigoexterno":3903
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SERRA DO RAMALHO",
   "codigoexterno":3039
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SERRA DOURADA",
   "codigoexterno":3905
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SERRA PRETA",
   "codigoexterno":3907
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SERRINHA",
   "codigoexterno":3909
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SERROLANDIA",
   "codigoexterno":3911
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SIMOES FILHO",
   "codigoexterno":3913
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SITIO DO MATO",
   "codigoexterno":3041
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SITIO DO QUINTO",
   "codigoexterno":3043
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SOBRADINHO",
   "codigoexterno":3045
},
{
   "siglaestado":"BA",
   "nomemunicipio":"SOUTO SOARES",
   "codigoexterno":3915
},
{
   "siglaestado":"BA",
   "nomemunicipio":"TABOCAS DO BREJO VELHO",
   "codigoexterno":3917
},
{
   "siglaestado":"BA",
   "nomemunicipio":"TANHACU",
   "codigoexterno":3919
},
{
   "siglaestado":"BA",
   "nomemunicipio":"TANQUE NOVO",
   "codigoexterno":3991
},
{
   "siglaestado":"BA",
   "nomemunicipio":"TANQUINHO",
   "codigoexterno":3921
},
{
   "siglaestado":"BA",
   "nomemunicipio":"TAPEROA",
   "codigoexterno":3923
},
{
   "siglaestado":"BA",
   "nomemunicipio":"TAPIRAMUTA",
   "codigoexterno":3925
},
{
   "siglaestado":"BA",
   "nomemunicipio":"TEIXEIRA DE FREITAS",
   "codigoexterno":3993
},
{
   "siglaestado":"BA",
   "nomemunicipio":"TEODORO SAMPAIO",
   "codigoexterno":3927
},
{
   "siglaestado":"BA",
   "nomemunicipio":"TEOFILANDIA",
   "codigoexterno":3929
},
{
   "siglaestado":"BA",
   "nomemunicipio":"TEOLANDIA",
   "codigoexterno":3931
},
{
   "siglaestado":"BA",
   "nomemunicipio":"TERRA NOVA",
   "codigoexterno":3933
},
{
   "siglaestado":"BA",
   "nomemunicipio":"TREMEDAL",
   "codigoexterno":3935
},
{
   "siglaestado":"BA",
   "nomemunicipio":"TUCANO",
   "codigoexterno":3937
},
{
   "siglaestado":"BA",
   "nomemunicipio":"UAUA",
   "codigoexterno":3939
},
{
   "siglaestado":"BA",
   "nomemunicipio":"UBAIRA",
   "codigoexterno":3941
},
{
   "siglaestado":"BA",
   "nomemunicipio":"UBAITABA",
   "codigoexterno":3943
},
{
   "siglaestado":"BA",
   "nomemunicipio":"UBATA",
   "codigoexterno":3945
},
{
   "siglaestado":"BA",
   "nomemunicipio":"UIBAI",
   "codigoexterno":3947
},
{
   "siglaestado":"BA",
   "nomemunicipio":"UMBURANAS",
   "codigoexterno":3047
},
{
   "siglaestado":"BA",
   "nomemunicipio":"UNA",
   "codigoexterno":3949
},
{
   "siglaestado":"BA",
   "nomemunicipio":"URANDI",
   "codigoexterno":3951
},
{
   "siglaestado":"BA",
   "nomemunicipio":"URUCUCA",
   "codigoexterno":3953
},
{
   "siglaestado":"BA",
   "nomemunicipio":"UTINGA",
   "codigoexterno":3955
},
{
   "siglaestado":"BA",
   "nomemunicipio":"VALENCA",
   "codigoexterno":3957
},
{
   "siglaestado":"BA",
   "nomemunicipio":"VALENTE",
   "codigoexterno":3959
},
{
   "siglaestado":"BA",
   "nomemunicipio":"VARZEA DA ROCA",
   "codigoexterno":3997
},
{
   "siglaestado":"BA",
   "nomemunicipio":"VARZEA DO POCO",
   "codigoexterno":3961
},
{
   "siglaestado":"BA",
   "nomemunicipio":"VARZEA NOVA",
   "codigoexterno":3995
},
{
   "siglaestado":"BA",
   "nomemunicipio":"VARZEDO",
   "codigoexterno":3049
},
{
   "siglaestado":"BA",
   "nomemunicipio":"VERA CRUZ",
   "codigoexterno":3963
},
{
   "siglaestado":"BA",
   "nomemunicipio":"VEREDA",
   "codigoexterno":3051
},
{
   "siglaestado":"BA",
   "nomemunicipio":"VITORIA DA CONQUISTA",
   "codigoexterno":3965
},
{
   "siglaestado":"BA",
   "nomemunicipio":"WAGNER",
   "codigoexterno":3967
},
{
   "siglaestado":"BA",
   "nomemunicipio":"WANDERLEY",
   "codigoexterno":3999
},
{
   "siglaestado":"BA",
   "nomemunicipio":"WENCESLAU GUIMARAES",
   "codigoexterno":3969
},
{
   "siglaestado":"BA",
   "nomemunicipio":"XIQUE-XIQUE",
   "codigoexterno":3971
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ABAIARA",
   "codigoexterno":1301
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ACARAPE",
   "codigoexterno":1231
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ACARAU",
   "codigoexterno":1303
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ACOPIARA",
   "codigoexterno":1305
},
{
   "siglaestado":"CE",
   "nomemunicipio":"AIUABA",
   "codigoexterno":1307
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ALCANTARAS",
   "codigoexterno":1309
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ALTANEIRA",
   "codigoexterno":1311
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ALTO SANTO",
   "codigoexterno":1313
},
{
   "siglaestado":"CE",
   "nomemunicipio":"AMONTADA",
   "codigoexterno":1587
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ANTONINA DO NORTE",
   "codigoexterno":1315
},
{
   "siglaestado":"CE",
   "nomemunicipio":"APUIARES",
   "codigoexterno":1317
},
{
   "siglaestado":"CE",
   "nomemunicipio":"AQUIRAZ",
   "codigoexterno":1319
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ARACATI",
   "codigoexterno":1321
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ARACOIABA",
   "codigoexterno":1323
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ARARENDA",
   "codigoexterno":989
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ARARIPE",
   "codigoexterno":1325
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ARATUBA",
   "codigoexterno":1327
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ARNEIROZ",
   "codigoexterno":1329
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ASSARE",
   "codigoexterno":1331
},
{
   "siglaestado":"CE",
   "nomemunicipio":"AURORA",
   "codigoexterno":1333
},
{
   "siglaestado":"CE",
   "nomemunicipio":"BAIXIO",
   "codigoexterno":1335
},
{
   "siglaestado":"CE",
   "nomemunicipio":"BANABUIU",
   "codigoexterno":1233
},
{
   "siglaestado":"CE",
   "nomemunicipio":"BARBALHA",
   "codigoexterno":1337
},
{
   "siglaestado":"CE",
   "nomemunicipio":"BARREIRA",
   "codigoexterno":1235
},
{
   "siglaestado":"CE",
   "nomemunicipio":"BARRO",
   "codigoexterno":1339
},
{
   "siglaestado":"CE",
   "nomemunicipio":"BARROQUINHA",
   "codigoexterno":1237
},
{
   "siglaestado":"CE",
   "nomemunicipio":"BATURITE",
   "codigoexterno":1341
},
{
   "siglaestado":"CE",
   "nomemunicipio":"BEBERIBE",
   "codigoexterno":1343
},
{
   "siglaestado":"CE",
   "nomemunicipio":"BELA CRUZ",
   "codigoexterno":1345
},
{
   "siglaestado":"CE",
   "nomemunicipio":"BOA VIAGEM",
   "codigoexterno":1347
},
{
   "siglaestado":"CE",
   "nomemunicipio":"BREJO SANTO",
   "codigoexterno":1349
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CAMOCIM",
   "codigoexterno":1351
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CAMPOS SALES",
   "codigoexterno":1353
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CANINDE",
   "codigoexterno":1355
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CAPISTRANO",
   "codigoexterno":1357
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CARIDADE",
   "codigoexterno":1359
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CARIRE",
   "codigoexterno":1361
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CARIRIACU",
   "codigoexterno":1363
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CARIUS",
   "codigoexterno":1365
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CARNAUBAL",
   "codigoexterno":1367
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CASCAVEL",
   "codigoexterno":1369
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CATARINA",
   "codigoexterno":1371
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CATUNDA",
   "codigoexterno":983
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CAUCAIA",
   "codigoexterno":1373
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CEDRO",
   "codigoexterno":1375
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CHAVAL",
   "codigoexterno":1377
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CHORO",
   "codigoexterno":993
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CHOROZINHO",
   "codigoexterno":1239
},
{
   "siglaestado":"CE",
   "nomemunicipio":"COREAU",
   "codigoexterno":1381
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CRATEUS",
   "codigoexterno":1383
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CRATO",
   "codigoexterno":1385
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CROATA",
   "codigoexterno":1241
},
{
   "siglaestado":"CE",
   "nomemunicipio":"CRUZ",
   "codigoexterno":1589
},
{
   "siglaestado":"CE",
   "nomemunicipio":"DEPUTADO IRAPUAN PINHEIRO",
   "codigoexterno":1243
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ERERE",
   "codigoexterno":1245
},
{
   "siglaestado":"CE",
   "nomemunicipio":"EUSEBIO",
   "codigoexterno":1247
},
{
   "siglaestado":"CE",
   "nomemunicipio":"FARIAS BRITO",
   "codigoexterno":1387
},
{
   "siglaestado":"CE",
   "nomemunicipio":"FORQUILHA",
   "codigoexterno":1591
},
{
   "siglaestado":"CE",
   "nomemunicipio":"FORTALEZA",
   "codigoexterno":1389
},
{
   "siglaestado":"CE",
   "nomemunicipio":"FORTIM",
   "codigoexterno":987
},
{
   "siglaestado":"CE",
   "nomemunicipio":"FRECHEIRINHA",
   "codigoexterno":1391
},
{
   "siglaestado":"CE",
   "nomemunicipio":"GENERAL SAMPAIO",
   "codigoexterno":1393
},
{
   "siglaestado":"CE",
   "nomemunicipio":"GRACA",
   "codigoexterno":1249
},
{
   "siglaestado":"CE",
   "nomemunicipio":"GRANJA",
   "codigoexterno":1395
},
{
   "siglaestado":"CE",
   "nomemunicipio":"GRANJEIRO",
   "codigoexterno":1397
},
{
   "siglaestado":"CE",
   "nomemunicipio":"GROAIRAS",
   "codigoexterno":1399
},
{
   "siglaestado":"CE",
   "nomemunicipio":"GUAIUBA",
   "codigoexterno":1251
},
{
   "siglaestado":"CE",
   "nomemunicipio":"GUARACIABA DO NORTE",
   "codigoexterno":1401
},
{
   "siglaestado":"CE",
   "nomemunicipio":"GUARAMIRANGA",
   "codigoexterno":1403
},
{
   "siglaestado":"CE",
   "nomemunicipio":"HIDROLANDIA",
   "codigoexterno":1405
},
{
   "siglaestado":"CE",
   "nomemunicipio":"HORIZONTE",
   "codigoexterno":1253
},
{
   "siglaestado":"CE",
   "nomemunicipio":"IBARETAMA",
   "codigoexterno":1255
},
{
   "siglaestado":"CE",
   "nomemunicipio":"IBIAPINA",
   "codigoexterno":1407
},
{
   "siglaestado":"CE",
   "nomemunicipio":"IBICUITINGA",
   "codigoexterno":1257
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ICAPUI",
   "codigoexterno":1593
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ICO",
   "codigoexterno":1409
},
{
   "siglaestado":"CE",
   "nomemunicipio":"IGUATU",
   "codigoexterno":1411
},
{
   "siglaestado":"CE",
   "nomemunicipio":"INDEPENDENCIA",
   "codigoexterno":1413
},
{
   "siglaestado":"CE",
   "nomemunicipio":"IPAPORANGA",
   "codigoexterno":1259
},
{
   "siglaestado":"CE",
   "nomemunicipio":"IPAUMIRIM",
   "codigoexterno":1415
},
{
   "siglaestado":"CE",
   "nomemunicipio":"IPU",
   "codigoexterno":1417
},
{
   "siglaestado":"CE",
   "nomemunicipio":"IPUEIRAS",
   "codigoexterno":1419
},
{
   "siglaestado":"CE",
   "nomemunicipio":"IRACEMA",
   "codigoexterno":1421
},
{
   "siglaestado":"CE",
   "nomemunicipio":"IRAUCUBA",
   "codigoexterno":1423
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ITAICABA",
   "codigoexterno":1425
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ITAITINGA",
   "codigoexterno":991
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ITAPAJE",
   "codigoexterno":1427
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ITAPIPOCA",
   "codigoexterno":1429
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ITAPIUNA",
   "codigoexterno":1431
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ITAREMA",
   "codigoexterno":1595
},
{
   "siglaestado":"CE",
   "nomemunicipio":"ITATIRA",
   "codigoexterno":1433
},
{
   "siglaestado":"CE",
   "nomemunicipio":"JAGUARETAMA",
   "codigoexterno":1435
},
{
   "siglaestado":"CE",
   "nomemunicipio":"JAGUARIBARA",
   "codigoexterno":1437
},
{
   "siglaestado":"CE",
   "nomemunicipio":"JAGUARIBE",
   "codigoexterno":1439
},
{
   "siglaestado":"CE",
   "nomemunicipio":"JAGUARUANA",
   "codigoexterno":1441
},
{
   "siglaestado":"CE",
   "nomemunicipio":"JARDIM",
   "codigoexterno":1443
},
{
   "siglaestado":"CE",
   "nomemunicipio":"JATI",
   "codigoexterno":1445
},
{
   "siglaestado":"CE",
   "nomemunicipio":"JIJOCA DE JERICOACOARA",
   "codigoexterno":985
},
{
   "siglaestado":"CE",
   "nomemunicipio":"JUAZEIRO DO NORTE",
   "codigoexterno":1447
},
{
   "siglaestado":"CE",
   "nomemunicipio":"JUCAS",
   "codigoexterno":1449
},
{
   "siglaestado":"CE",
   "nomemunicipio":"LAVRAS DA MANGABEIRA",
   "codigoexterno":1451
},
{
   "siglaestado":"CE",
   "nomemunicipio":"LIMOEIRO DO NORTE",
   "codigoexterno":1453
},
{
   "siglaestado":"CE",
   "nomemunicipio":"MADALENA",
   "codigoexterno":1261
},
{
   "siglaestado":"CE",
   "nomemunicipio":"MARACANAU",
   "codigoexterno":1585
},
{
   "siglaestado":"CE",
   "nomemunicipio":"MARANGUAPE",
   "codigoexterno":1455
},
{
   "siglaestado":"CE",
   "nomemunicipio":"MARCO",
   "codigoexterno":1457
},
{
   "siglaestado":"CE",
   "nomemunicipio":"MARTINOPOLE",
   "codigoexterno":1459
},
{
   "siglaestado":"CE",
   "nomemunicipio":"MASSAPE",
   "codigoexterno":1461
},
{
   "siglaestado":"CE",
   "nomemunicipio":"MAURITI",
   "codigoexterno":1463
},
{
   "siglaestado":"CE",
   "nomemunicipio":"MERUOCA",
   "codigoexterno":1465
},
{
   "siglaestado":"CE",
   "nomemunicipio":"MILAGRES",
   "codigoexterno":1467
},
{
   "siglaestado":"CE",
   "nomemunicipio":"MILHA",
   "codigoexterno":1597
},
{
   "siglaestado":"CE",
   "nomemunicipio":"MIRAIMA",
   "codigoexterno":1263
},
{
   "siglaestado":"CE",
   "nomemunicipio":"MISSAO VELHA",
   "codigoexterno":1469
},
{
   "siglaestado":"CE",
   "nomemunicipio":"MOMBACA",
   "codigoexterno":1471
},
{
   "siglaestado":"CE",
   "nomemunicipio":"MONSENHOR TABOSA",
   "codigoexterno":1473
},
{
   "siglaestado":"CE",
   "nomemunicipio":"MORADA NOVA",
   "codigoexterno":1475
},
{
   "siglaestado":"CE",
   "nomemunicipio":"MORAUJO",
   "codigoexterno":1477
},
{
   "siglaestado":"CE",
   "nomemunicipio":"MORRINHOS",
   "codigoexterno":1479
},
{
   "siglaestado":"CE",
   "nomemunicipio":"MUCAMBO",
   "codigoexterno":1481
},
{
   "siglaestado":"CE",
   "nomemunicipio":"MULUNGU",
   "codigoexterno":1483
},
{
   "siglaestado":"CE",
   "nomemunicipio":"NOVA OLINDA",
   "codigoexterno":1485
},
{
   "siglaestado":"CE",
   "nomemunicipio":"NOVA RUSSAS",
   "codigoexterno":1487
},
{
   "siglaestado":"CE",
   "nomemunicipio":"NOVO ORIENTE",
   "codigoexterno":1489
},
{
   "siglaestado":"CE",
   "nomemunicipio":"OCARA",
   "codigoexterno":1265
},
{
   "siglaestado":"CE",
   "nomemunicipio":"OROS",
   "codigoexterno":1491
},
{
   "siglaestado":"CE",
   "nomemunicipio":"PACAJUS",
   "codigoexterno":1493
},
{
   "siglaestado":"CE",
   "nomemunicipio":"PACATUBA",
   "codigoexterno":1495
},
{
   "siglaestado":"CE",
   "nomemunicipio":"PACOTI",
   "codigoexterno":1497
},
{
   "siglaestado":"CE",
   "nomemunicipio":"PACUJA",
   "codigoexterno":1499
},
{
   "siglaestado":"CE",
   "nomemunicipio":"PALHANO",
   "codigoexterno":1501
},
{
   "siglaestado":"CE",
   "nomemunicipio":"PALMACIA",
   "codigoexterno":1503
},
{
   "siglaestado":"CE",
   "nomemunicipio":"PARACURU",
   "codigoexterno":1505
},
{
   "siglaestado":"CE",
   "nomemunicipio":"PARAIPABA",
   "codigoexterno":1599
},
{
   "siglaestado":"CE",
   "nomemunicipio":"PARAMBU",
   "codigoexterno":1507
},
{
   "siglaestado":"CE",
   "nomemunicipio":"PARAMOTI",
   "codigoexterno":1509
},
{
   "siglaestado":"CE",
   "nomemunicipio":"PEDRA BRANCA",
   "codigoexterno":1511
},
{
   "siglaestado":"CE",
   "nomemunicipio":"PENAFORTE",
   "codigoexterno":1513
},
{
   "siglaestado":"CE",
   "nomemunicipio":"PENTECOSTE",
   "codigoexterno":1515
},
{
   "siglaestado":"CE",
   "nomemunicipio":"PEREIRO",
   "codigoexterno":1517
},
{
   "siglaestado":"CE",
   "nomemunicipio":"PINDORETAMA",
   "codigoexterno":1267
},
{
   "siglaestado":"CE",
   "nomemunicipio":"PIQUET CARNEIRO",
   "codigoexterno":1519
},
{
   "siglaestado":"CE",
   "nomemunicipio":"PIRES FERREIRA",
   "codigoexterno":1269
},
{
   "siglaestado":"CE",
   "nomemunicipio":"PORANGA",
   "codigoexterno":1521
},
{
   "siglaestado":"CE",
   "nomemunicipio":"PORTEIRAS",
   "codigoexterno":1523
},
{
   "siglaestado":"CE",
   "nomemunicipio":"POTENGI",
   "codigoexterno":1525
},
{
   "siglaestado":"CE",
   "nomemunicipio":"POTIRETAMA",
   "codigoexterno":1271
},
{
   "siglaestado":"CE",
   "nomemunicipio":"QUITERIANOPOLIS",
   "codigoexterno":9917
},
{
   "siglaestado":"CE",
   "nomemunicipio":"QUIXADA",
   "codigoexterno":1527
},
{
   "siglaestado":"CE",
   "nomemunicipio":"QUIXELO",
   "codigoexterno":9853
},
{
   "siglaestado":"CE",
   "nomemunicipio":"QUIXERAMOBIM",
   "codigoexterno":1529
},
{
   "siglaestado":"CE",
   "nomemunicipio":"QUIXERE",
   "codigoexterno":1531
},
{
   "siglaestado":"CE",
   "nomemunicipio":"REDENCAO",
   "codigoexterno":1533
},
{
   "siglaestado":"CE",
   "nomemunicipio":"RERIUTABA",
   "codigoexterno":1535
},
{
   "siglaestado":"CE",
   "nomemunicipio":"RUSSAS",
   "codigoexterno":1537
},
{
   "siglaestado":"CE",
   "nomemunicipio":"SABOEIRO",
   "codigoexterno":1539
},
{
   "siglaestado":"CE",
   "nomemunicipio":"SALITRE",
   "codigoexterno":1273
},
{
   "siglaestado":"CE",
   "nomemunicipio":"SANTA QUITERIA",
   "codigoexterno":1545
},
{
   "siglaestado":"CE",
   "nomemunicipio":"SANTANA DO ACARAU",
   "codigoexterno":1541
},
{
   "siglaestado":"CE",
   "nomemunicipio":"SANTANA DO CARIRI",
   "codigoexterno":1543
},
{
   "siglaestado":"CE",
   "nomemunicipio":"SAO BENEDITO",
   "codigoexterno":1547
},
{
   "siglaestado":"CE",
   "nomemunicipio":"SAO GONCALO DO AMARANTE",
   "codigoexterno":1549
},
{
   "siglaestado":"CE",
   "nomemunicipio":"SAO JOAO DO JAGUARIBE",
   "codigoexterno":1551
},
{
   "siglaestado":"CE",
   "nomemunicipio":"SAO LUIS DO CURU",
   "codigoexterno":1553
},
{
   "siglaestado":"CE",
   "nomemunicipio":"SENADOR POMPEU",
   "codigoexterno":1555
},
{
   "siglaestado":"CE",
   "nomemunicipio":"SENADOR SA",
   "codigoexterno":1557
},
{
   "siglaestado":"CE",
   "nomemunicipio":"SOBRAL",
   "codigoexterno":1559
},
{
   "siglaestado":"CE",
   "nomemunicipio":"SOLONOPOLE",
   "codigoexterno":1561
},
{
   "siglaestado":"CE",
   "nomemunicipio":"TABULEIRO DO NORTE",
   "codigoexterno":1563
},
{
   "siglaestado":"CE",
   "nomemunicipio":"TAMBORIL",
   "codigoexterno":1565
},
{
   "siglaestado":"CE",
   "nomemunicipio":"TARRAFAS",
   "codigoexterno":1275
},
{
   "siglaestado":"CE",
   "nomemunicipio":"TAUA",
   "codigoexterno":1567
},
{
   "siglaestado":"CE",
   "nomemunicipio":"TEJUCUOCA",
   "codigoexterno":1277
},
{
   "siglaestado":"CE",
   "nomemunicipio":"TIANGUA",
   "codigoexterno":1569
},
{
   "siglaestado":"CE",
   "nomemunicipio":"TRAIRI",
   "codigoexterno":1571
},
{
   "siglaestado":"CE",
   "nomemunicipio":"TURURU",
   "codigoexterno":1279
},
{
   "siglaestado":"CE",
   "nomemunicipio":"UBAJARA",
   "codigoexterno":1573
},
{
   "siglaestado":"CE",
   "nomemunicipio":"UMARI",
   "codigoexterno":1575
},
{
   "siglaestado":"CE",
   "nomemunicipio":"UMIRIM",
   "codigoexterno":9855
},
{
   "siglaestado":"CE",
   "nomemunicipio":"URUBURETAMA",
   "codigoexterno":1577
},
{
   "siglaestado":"CE",
   "nomemunicipio":"URUOCA",
   "codigoexterno":1579
},
{
   "siglaestado":"CE",
   "nomemunicipio":"VARJOTA",
   "codigoexterno":9857
},
{
   "siglaestado":"CE",
   "nomemunicipio":"VARZEA ALEGRE",
   "codigoexterno":1581
},
{
   "siglaestado":"CE",
   "nomemunicipio":"VICOSA DO CEARA",
   "codigoexterno":1583
},
{
   "siglaestado":"DF",
   "nomemunicipio":"BRASILIA",
   "codigoexterno":9701
},
{
   "siglaestado":"ES",
   "nomemunicipio":"AFONSO CLAUDIO",
   "codigoexterno":5601
},
{
   "siglaestado":"ES",
   "nomemunicipio":"AGUA DOCE DO NORTE",
   "codigoexterno":5717
},
{
   "siglaestado":"ES",
   "nomemunicipio":"AGUIA BRANCA",
   "codigoexterno":5733
},
{
   "siglaestado":"ES",
   "nomemunicipio":"ALEGRE",
   "codigoexterno":5603
},
{
   "siglaestado":"ES",
   "nomemunicipio":"ALFREDO CHAVES",
   "codigoexterno":5605
},
{
   "siglaestado":"ES",
   "nomemunicipio":"ALTO RIO NOVO",
   "codigoexterno":5719
},
{
   "siglaestado":"ES",
   "nomemunicipio":"ANCHIETA",
   "codigoexterno":5607
},
{
   "siglaestado":"ES",
   "nomemunicipio":"APIACA",
   "codigoexterno":5609
},
{
   "siglaestado":"ES",
   "nomemunicipio":"ARACRUZ",
   "codigoexterno":5611
},
{
   "siglaestado":"ES",
   "nomemunicipio":"ATILIO VIVACQUA",
   "codigoexterno":5613
},
{
   "siglaestado":"ES",
   "nomemunicipio":"BAIXO GUANDU",
   "codigoexterno":5615
},
{
   "siglaestado":"ES",
   "nomemunicipio":"BARRA DE SAO FRANCISCO",
   "codigoexterno":5617
},
{
   "siglaestado":"ES",
   "nomemunicipio":"BOA ESPERANCA",
   "codigoexterno":5619
},
{
   "siglaestado":"ES",
   "nomemunicipio":"BOM JESUS DO NORTE",
   "codigoexterno":5621
},
{
   "siglaestado":"ES",
   "nomemunicipio":"BREJETUBA",
   "codigoexterno":758
},
{
   "siglaestado":"ES",
   "nomemunicipio":"CACHOEIRO DE ITAPEMIRIM",
   "codigoexterno":5623
},
{
   "siglaestado":"ES",
   "nomemunicipio":"CARIACICA",
   "codigoexterno":5625
},
{
   "siglaestado":"ES",
   "nomemunicipio":"CASTELO",
   "codigoexterno":5627
},
{
   "siglaestado":"ES",
   "nomemunicipio":"COLATINA",
   "codigoexterno":5629
},
{
   "siglaestado":"ES",
   "nomemunicipio":"CONCEICAO DA BARRA",
   "codigoexterno":5631
},
{
   "siglaestado":"ES",
   "nomemunicipio":"CONCEICAO DO CASTELO",
   "codigoexterno":5633
},
{
   "siglaestado":"ES",
   "nomemunicipio":"DIVINO DE SAO LOURENCO",
   "codigoexterno":5635
},
{
   "siglaestado":"ES",
   "nomemunicipio":"DOMINGOS MARTINS",
   "codigoexterno":5637
},
{
   "siglaestado":"ES",
   "nomemunicipio":"DORES DO RIO PRETO",
   "codigoexterno":5639
},
{
   "siglaestado":"ES",
   "nomemunicipio":"ECOPORANGA",
   "codigoexterno":5641
},
{
   "siglaestado":"ES",
   "nomemunicipio":"FUNDAO",
   "codigoexterno":5643
},
{
   "siglaestado":"ES",
   "nomemunicipio":"GOVERNADOR LINDENBERG",
   "codigoexterno":1114
},
{
   "siglaestado":"ES",
   "nomemunicipio":"GUACUI",
   "codigoexterno":5645
},
{
   "siglaestado":"ES",
   "nomemunicipio":"GUARAPARI",
   "codigoexterno":5647
},
{
   "siglaestado":"ES",
   "nomemunicipio":"IBATIBA",
   "codigoexterno":5709
},
{
   "siglaestado":"ES",
   "nomemunicipio":"IBIRACU",
   "codigoexterno":5649
},
{
   "siglaestado":"ES",
   "nomemunicipio":"IBITIRAMA",
   "codigoexterno":6011
},
{
   "siglaestado":"ES",
   "nomemunicipio":"ICONHA",
   "codigoexterno":5651
},
{
   "siglaestado":"ES",
   "nomemunicipio":"IRUPI",
   "codigoexterno":2931
},
{
   "siglaestado":"ES",
   "nomemunicipio":"ITAGUACU",
   "codigoexterno":5653
},
{
   "siglaestado":"ES",
   "nomemunicipio":"ITAPEMIRIM",
   "codigoexterno":5655
},
{
   "siglaestado":"ES",
   "nomemunicipio":"ITARANA",
   "codigoexterno":5657
},
{
   "siglaestado":"ES",
   "nomemunicipio":"IUNA",
   "codigoexterno":5659
},
{
   "siglaestado":"ES",
   "nomemunicipio":"JAGUARE",
   "codigoexterno":5713
},
{
   "siglaestado":"ES",
   "nomemunicipio":"JERONIMO MONTEIRO",
   "codigoexterno":5661
},
{
   "siglaestado":"ES",
   "nomemunicipio":"JOAO NEIVA",
   "codigoexterno":5721
},
{
   "siglaestado":"ES",
   "nomemunicipio":"LARANJA DA TERRA",
   "codigoexterno":5723
},
{
   "siglaestado":"ES",
   "nomemunicipio":"LINHARES",
   "codigoexterno":5663
},
{
   "siglaestado":"ES",
   "nomemunicipio":"MANTENOPOLIS",
   "codigoexterno":5665
},
{
   "siglaestado":"ES",
   "nomemunicipio":"MARATAIZES",
   "codigoexterno":760
},
{
   "siglaestado":"ES",
   "nomemunicipio":"MARECHAL FLORIANO",
   "codigoexterno":2929
},
{
   "siglaestado":"ES",
   "nomemunicipio":"MARILANDIA",
   "codigoexterno":5707
},
{
   "siglaestado":"ES",
   "nomemunicipio":"MIMOSO DO SUL",
   "codigoexterno":5667
},
{
   "siglaestado":"ES",
   "nomemunicipio":"MONTANHA",
   "codigoexterno":5669
},
{
   "siglaestado":"ES",
   "nomemunicipio":"MUCURICI",
   "codigoexterno":5671
},
{
   "siglaestado":"ES",
   "nomemunicipio":"MUNIZ FREIRE",
   "codigoexterno":5673
},
{
   "siglaestado":"ES",
   "nomemunicipio":"MUQUI",
   "codigoexterno":5675
},
{
   "siglaestado":"ES",
   "nomemunicipio":"NOVA VENECIA",
   "codigoexterno":5677
},
{
   "siglaestado":"ES",
   "nomemunicipio":"PANCAS",
   "codigoexterno":5679
},
{
   "siglaestado":"ES",
   "nomemunicipio":"PEDRO CANARIO",
   "codigoexterno":5715
},
{
   "siglaestado":"ES",
   "nomemunicipio":"PINHEIROS",
   "codigoexterno":5681
},
{
   "siglaestado":"ES",
   "nomemunicipio":"PIUMA",
   "codigoexterno":5683
},
{
   "siglaestado":"ES",
   "nomemunicipio":"PONTO BELO",
   "codigoexterno":762
},
{
   "siglaestado":"ES",
   "nomemunicipio":"PRESIDENTE KENNEDY",
   "codigoexterno":5685
},
{
   "siglaestado":"ES",
   "nomemunicipio":"RIO BANANAL",
   "codigoexterno":5711
},
{
   "siglaestado":"ES",
   "nomemunicipio":"RIO NOVO DO SUL",
   "codigoexterno":5687
},
{
   "siglaestado":"ES",
   "nomemunicipio":"SANTA LEOPOLDINA",
   "codigoexterno":5689
},
{
   "siglaestado":"ES",
   "nomemunicipio":"SANTA MARIA DE JETIBA",
   "codigoexterno":5725
},
{
   "siglaestado":"ES",
   "nomemunicipio":"SANTA TERESA",
   "codigoexterno":5691
},
{
   "siglaestado":"ES",
   "nomemunicipio":"SAO DOMINGOS DO NORTE",
   "codigoexterno":2933
},
{
   "siglaestado":"ES",
   "nomemunicipio":"SAO GABRIEL DA PALHA",
   "codigoexterno":5693
},
{
   "siglaestado":"ES",
   "nomemunicipio":"SAO JOSE DO CALCADO",
   "codigoexterno":5695
},
{
   "siglaestado":"ES",
   "nomemunicipio":"SAO MATEUS",
   "codigoexterno":5697
},
{
   "siglaestado":"ES",
   "nomemunicipio":"SAO ROQUE DO CANAA",
   "codigoexterno":764
},
{
   "siglaestado":"ES",
   "nomemunicipio":"SERRA",
   "codigoexterno":5699
},
{
   "siglaestado":"ES",
   "nomemunicipio":"SOORETAMA",
   "codigoexterno":766
},
{
   "siglaestado":"ES",
   "nomemunicipio":"VARGEM ALTA",
   "codigoexterno":5727
},
{
   "siglaestado":"ES",
   "nomemunicipio":"VENDA NOVA DO IMIGRANTE",
   "codigoexterno":5729
},
{
   "siglaestado":"ES",
   "nomemunicipio":"VIANA",
   "codigoexterno":5701
},
{
   "siglaestado":"ES",
   "nomemunicipio":"VILA PAVAO",
   "codigoexterno":2935
},
{
   "siglaestado":"ES",
   "nomemunicipio":"VILA VALERIO",
   "codigoexterno":768
},
{
   "siglaestado":"ES",
   "nomemunicipio":"VILA VELHA",
   "codigoexterno":5703
},
{
   "siglaestado":"ES",
   "nomemunicipio":"VITORIA",
   "codigoexterno":5705
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ABADIA DE GOIAS",
   "codigoexterno":1050
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ABADIANIA",
   "codigoexterno":9201
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ACREUNA",
   "codigoexterno":9645
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ADELANDIA",
   "codigoexterno":9769
},
{
   "siglaestado":"GO",
   "nomemunicipio":"AGUA FRIA DE GOIAS",
   "codigoexterno":9771
},
{
   "siglaestado":"GO",
   "nomemunicipio":"AGUA LIMPA",
   "codigoexterno":9203
},
{
   "siglaestado":"GO",
   "nomemunicipio":"AGUAS LINDAS DE GOIAS",
   "codigoexterno":1052
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ALEXANIA",
   "codigoexterno":9205
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ALOANDIA",
   "codigoexterno":9209
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ALTO HORIZONTE",
   "codigoexterno":85
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ALTO PARAISO DE GOIAS",
   "codigoexterno":9211
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ALVORADA DO NORTE",
   "codigoexterno":9215
},
{
   "siglaestado":"GO",
   "nomemunicipio":"AMARALINA",
   "codigoexterno":1054
},
{
   "siglaestado":"GO",
   "nomemunicipio":"AMERICANO DO BRASIL",
   "codigoexterno":9661
},
{
   "siglaestado":"GO",
   "nomemunicipio":"AMORINOPOLIS",
   "codigoexterno":9217
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ANAPOLIS",
   "codigoexterno":9221
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ANHANGUERA",
   "codigoexterno":9223
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ANICUNS",
   "codigoexterno":9225
},
{
   "siglaestado":"GO",
   "nomemunicipio":"APARECIDA DE GOIANIA",
   "codigoexterno":9227
},
{
   "siglaestado":"GO",
   "nomemunicipio":"APARECIDA DO RIO DOCE",
   "codigoexterno":71
},
{
   "siglaestado":"GO",
   "nomemunicipio":"APORE",
   "codigoexterno":9229
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ARACU",
   "codigoexterno":9231
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ARAGARCAS",
   "codigoexterno":9233
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ARAGOIANIA",
   "codigoexterno":9235
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ARAGUAPAZ",
   "codigoexterno":9669
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ARENOPOLIS",
   "codigoexterno":9671
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ARUANA",
   "codigoexterno":9249
},
{
   "siglaestado":"GO",
   "nomemunicipio":"AURILANDIA",
   "codigoexterno":9251
},
{
   "siglaestado":"GO",
   "nomemunicipio":"AVELINOPOLIS",
   "codigoexterno":9255
},
{
   "siglaestado":"GO",
   "nomemunicipio":"BALIZA",
   "codigoexterno":9261
},
{
   "siglaestado":"GO",
   "nomemunicipio":"BARRO ALTO",
   "codigoexterno":9263
},
{
   "siglaestado":"GO",
   "nomemunicipio":"BELA VISTA DE GOIAS",
   "codigoexterno":9265
},
{
   "siglaestado":"GO",
   "nomemunicipio":"BOM JARDIM DE GOIAS",
   "codigoexterno":9267
},
{
   "siglaestado":"GO",
   "nomemunicipio":"BOM JESUS DE GOIAS",
   "codigoexterno":9269
},
{
   "siglaestado":"GO",
   "nomemunicipio":"BONFINOPOLIS",
   "codigoexterno":9775
},
{
   "siglaestado":"GO",
   "nomemunicipio":"BONOPOLIS",
   "codigoexterno":1056
},
{
   "siglaestado":"GO",
   "nomemunicipio":"BRAZABRANTES",
   "codigoexterno":9271
},
{
   "siglaestado":"GO",
   "nomemunicipio":"BRITANIA",
   "codigoexterno":9275
},
{
   "siglaestado":"GO",
   "nomemunicipio":"BURITI ALEGRE",
   "codigoexterno":9277
},
{
   "siglaestado":"GO",
   "nomemunicipio":"BURITI DE GOIAS",
   "codigoexterno":63
},
{
   "siglaestado":"GO",
   "nomemunicipio":"BURITINOPOLIS",
   "codigoexterno":61
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CABECEIRAS",
   "codigoexterno":9279
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CACHOEIRA ALTA",
   "codigoexterno":9281
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CACHOEIRA DE GOIAS",
   "codigoexterno":9283
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CACHOEIRA DOURADA",
   "codigoexterno":9673
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CACU",
   "codigoexterno":9285
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CAIAPONIA",
   "codigoexterno":9287
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CALDAS NOVAS",
   "codigoexterno":9289
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CALDAZINHA",
   "codigoexterno":31
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CAMPESTRE DE GOIAS",
   "codigoexterno":9291
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CAMPINACU",
   "codigoexterno":9687
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CAMPINORTE",
   "codigoexterno":9293
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CAMPO ALEGRE DE GOIAS",
   "codigoexterno":9295
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CAMPO LIMPO DE GOI??S",
   "codigoexterno":1070
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CAMPOS BELOS",
   "codigoexterno":9297
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CAMPOS VERDES",
   "codigoexterno":9781
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CARMO DO RIO VERDE",
   "codigoexterno":9299
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CASTELANDIA",
   "codigoexterno":81
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CATALAO",
   "codigoexterno":9301
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CATURAI",
   "codigoexterno":9303
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CAVALCANTE",
   "codigoexterno":9305
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CERES",
   "codigoexterno":9307
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CEZARINA",
   "codigoexterno":9785
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CHAPADAO DO CEU",
   "codigoexterno":73
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CIDADE OCIDENTAL",
   "codigoexterno":77
},
{
   "siglaestado":"GO",
   "nomemunicipio":"COCALZINHO DE GOIAS",
   "codigoexterno":55
},
{
   "siglaestado":"GO",
   "nomemunicipio":"COLINAS DO SUL",
   "codigoexterno":9791
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CORREGO DO OURO",
   "codigoexterno":9315
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CORUMBA DE GOIAS",
   "codigoexterno":9317
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CORUMBAIBA",
   "codigoexterno":9319
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CRISTALINA",
   "codigoexterno":9325
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CRISTIANOPOLIS",
   "codigoexterno":9327
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CRIXAS",
   "codigoexterno":9329
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CROMINIA",
   "codigoexterno":9331
},
{
   "siglaestado":"GO",
   "nomemunicipio":"CUMARI",
   "codigoexterno":9333
},
{
   "siglaestado":"GO",
   "nomemunicipio":"DAMIANOPOLIS",
   "codigoexterno":9335
},
{
   "siglaestado":"GO",
   "nomemunicipio":"DAMOLANDIA",
   "codigoexterno":9337
},
{
   "siglaestado":"GO",
   "nomemunicipio":"DAVINOPOLIS",
   "codigoexterno":9339
},
{
   "siglaestado":"GO",
   "nomemunicipio":"DIORAMA",
   "codigoexterno":9343
},
{
   "siglaestado":"GO",
   "nomemunicipio":"DIVINOPOLIS DE GOIAS",
   "codigoexterno":9309
},
{
   "siglaestado":"GO",
   "nomemunicipio":"DOVERLANDIA",
   "codigoexterno":9675
},
{
   "siglaestado":"GO",
   "nomemunicipio":"EDEALINA",
   "codigoexterno":9795
},
{
   "siglaestado":"GO",
   "nomemunicipio":"EDEIA",
   "codigoexterno":9349
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ESTRELA DO NORTE",
   "codigoexterno":9351
},
{
   "siglaestado":"GO",
   "nomemunicipio":"FAINA",
   "codigoexterno":9797
},
{
   "siglaestado":"GO",
   "nomemunicipio":"FAZENDA NOVA",
   "codigoexterno":9353
},
{
   "siglaestado":"GO",
   "nomemunicipio":"FIRMINOPOLIS",
   "codigoexterno":9357
},
{
   "siglaestado":"GO",
   "nomemunicipio":"FLORES DE GOIAS",
   "codigoexterno":9359
},
{
   "siglaestado":"GO",
   "nomemunicipio":"FORMOSA",
   "codigoexterno":9361
},
{
   "siglaestado":"GO",
   "nomemunicipio":"FORMOSO",
   "codigoexterno":9363
},
{
   "siglaestado":"GO",
   "nomemunicipio":"GAMALEIRA DE GOI??S",
   "codigoexterno":1072
},
{
   "siglaestado":"GO",
   "nomemunicipio":"GOIANAPOLIS",
   "codigoexterno":9367
},
{
   "siglaestado":"GO",
   "nomemunicipio":"GOIANDIRA",
   "codigoexterno":9369
},
{
   "siglaestado":"GO",
   "nomemunicipio":"GOIANESIA",
   "codigoexterno":9371
},
{
   "siglaestado":"GO",
   "nomemunicipio":"GOIANIA",
   "codigoexterno":9373
},
{
   "siglaestado":"GO",
   "nomemunicipio":"GOIANIRA",
   "codigoexterno":9375
},
{
   "siglaestado":"GO",
   "nomemunicipio":"GOIAS",
   "codigoexterno":9377
},
{
   "siglaestado":"GO",
   "nomemunicipio":"GOIATUBA",
   "codigoexterno":9379
},
{
   "siglaestado":"GO",
   "nomemunicipio":"GOUVELANDIA",
   "codigoexterno":9799
},
{
   "siglaestado":"GO",
   "nomemunicipio":"GUAPO",
   "codigoexterno":9381
},
{
   "siglaestado":"GO",
   "nomemunicipio":"GUARAITA",
   "codigoexterno":65
},
{
   "siglaestado":"GO",
   "nomemunicipio":"GUARANI DE GOIAS",
   "codigoexterno":9383
},
{
   "siglaestado":"GO",
   "nomemunicipio":"GUARINOS",
   "codigoexterno":9993
},
{
   "siglaestado":"GO",
   "nomemunicipio":"HEITORAI",
   "codigoexterno":9387
},
{
   "siglaestado":"GO",
   "nomemunicipio":"HIDROLANDIA",
   "codigoexterno":9389
},
{
   "siglaestado":"GO",
   "nomemunicipio":"HIDROLINA",
   "codigoexterno":9391
},
{
   "siglaestado":"GO",
   "nomemunicipio":"IACIARA",
   "codigoexterno":9393
},
{
   "siglaestado":"GO",
   "nomemunicipio":"INACIOLANDIA",
   "codigoexterno":69
},
{
   "siglaestado":"GO",
   "nomemunicipio":"INDIARA",
   "codigoexterno":9681
},
{
   "siglaestado":"GO",
   "nomemunicipio":"INHUMAS",
   "codigoexterno":9395
},
{
   "siglaestado":"GO",
   "nomemunicipio":"IPAMERI",
   "codigoexterno":9397
},
{
   "siglaestado":"GO",
   "nomemunicipio":"IPIRANGA DE GOI??S",
   "codigoexterno":1074
},
{
   "siglaestado":"GO",
   "nomemunicipio":"IPORA",
   "codigoexterno":9399
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ISRAELANDIA",
   "codigoexterno":9401
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ITABERAI",
   "codigoexterno":9403
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ITAGUARI",
   "codigoexterno":9919
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ITAGUARU",
   "codigoexterno":9407
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ITAJA",
   "codigoexterno":9411
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ITAPACI",
   "codigoexterno":9413
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ITAPIRAPUA",
   "codigoexterno":9415
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ITAPURANGA",
   "codigoexterno":9419
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ITARUMA",
   "codigoexterno":9421
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ITAUCU",
   "codigoexterno":9423
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ITUMBIARA",
   "codigoexterno":9425
},
{
   "siglaestado":"GO",
   "nomemunicipio":"IVOLANDIA",
   "codigoexterno":9427
},
{
   "siglaestado":"GO",
   "nomemunicipio":"JANDAIA",
   "codigoexterno":9429
},
{
   "siglaestado":"GO",
   "nomemunicipio":"JARAGUA",
   "codigoexterno":9431
},
{
   "siglaestado":"GO",
   "nomemunicipio":"JATAI",
   "codigoexterno":9433
},
{
   "siglaestado":"GO",
   "nomemunicipio":"JAUPACI",
   "codigoexterno":9435
},
{
   "siglaestado":"GO",
   "nomemunicipio":"JESUPOLIS",
   "codigoexterno":49
},
{
   "siglaestado":"GO",
   "nomemunicipio":"JOVIANIA",
   "codigoexterno":9437
},
{
   "siglaestado":"GO",
   "nomemunicipio":"JUSSARA",
   "codigoexterno":9439
},
{
   "siglaestado":"GO",
   "nomemunicipio":"LAGOA SANTA",
   "codigoexterno":1076
},
{
   "siglaestado":"GO",
   "nomemunicipio":"LEOPOLDO DE BULHOES",
   "codigoexterno":9443
},
{
   "siglaestado":"GO",
   "nomemunicipio":"LUZIANIA",
   "codigoexterno":9445
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MAIRIPOTABA",
   "codigoexterno":9447
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MAMBAI",
   "codigoexterno":9449
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MARA ROSA",
   "codigoexterno":9451
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MARZAGAO",
   "codigoexterno":9453
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MATRINCHA",
   "codigoexterno":9927
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MAURILANDIA",
   "codigoexterno":9457
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MIMOSO DE GOIAS",
   "codigoexterno":9931
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MINACU",
   "codigoexterno":9647
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MINEIROS",
   "codigoexterno":9459
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MOIPORA",
   "codigoexterno":9465
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MONTE ALEGRE DE GOIAS",
   "codigoexterno":9467
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MONTES CLAROS DE GOIAS",
   "codigoexterno":9471
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MONTIVIDIU",
   "codigoexterno":9933
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MONTIVIDIU DO NORTE",
   "codigoexterno":79
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MORRINHOS",
   "codigoexterno":9473
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MORRO AGUDO DE GOIAS",
   "codigoexterno":9935
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MOSSAMEDES",
   "codigoexterno":9475
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MOZARLANDIA",
   "codigoexterno":9477
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MUNDO NOVO",
   "codigoexterno":9651
},
{
   "siglaestado":"GO",
   "nomemunicipio":"MUTUNOPOLIS",
   "codigoexterno":9479
},
{
   "siglaestado":"GO",
   "nomemunicipio":"NAZARIO",
   "codigoexterno":9485
},
{
   "siglaestado":"GO",
   "nomemunicipio":"NEROPOLIS",
   "codigoexterno":9487
},
{
   "siglaestado":"GO",
   "nomemunicipio":"NIQUELANDIA",
   "codigoexterno":9489
},
{
   "siglaestado":"GO",
   "nomemunicipio":"NOVA AMERICA",
   "codigoexterno":9491
},
{
   "siglaestado":"GO",
   "nomemunicipio":"NOVA AURORA",
   "codigoexterno":9493
},
{
   "siglaestado":"GO",
   "nomemunicipio":"NOVA CRIXAS",
   "codigoexterno":9653
},
{
   "siglaestado":"GO",
   "nomemunicipio":"NOVA GLORIA",
   "codigoexterno":9655
},
{
   "siglaestado":"GO",
   "nomemunicipio":"NOVA IGUACU DE GOIAS",
   "codigoexterno":87
},
{
   "siglaestado":"GO",
   "nomemunicipio":"NOVA ROMA",
   "codigoexterno":9495
},
{
   "siglaestado":"GO",
   "nomemunicipio":"NOVA VENEZA",
   "codigoexterno":9497
},
{
   "siglaestado":"GO",
   "nomemunicipio":"NOVO BRASIL",
   "codigoexterno":9501
},
{
   "siglaestado":"GO",
   "nomemunicipio":"NOVO GAMA",
   "codigoexterno":1058
},
{
   "siglaestado":"GO",
   "nomemunicipio":"NOVO PLANALTO",
   "codigoexterno":9735
},
{
   "siglaestado":"GO",
   "nomemunicipio":"ORIZONA",
   "codigoexterno":9503
},
{
   "siglaestado":"GO",
   "nomemunicipio":"OURO VERDE DE GOIAS",
   "codigoexterno":9505
},
{
   "siglaestado":"GO",
   "nomemunicipio":"OUVIDOR",
   "codigoexterno":9507
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PADRE BERNARDO",
   "codigoexterno":9509
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PALESTINA DE GOIAS",
   "codigoexterno":9737
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PALMEIRAS DE GOIAS",
   "codigoexterno":9511
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PALMELO",
   "codigoexterno":9513
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PALMINOPOLIS",
   "codigoexterno":9515
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PANAMA",
   "codigoexterno":9517
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PARANAIGUARA",
   "codigoexterno":9455
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PARAUNA",
   "codigoexterno":9523
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PEROLANDIA",
   "codigoexterno":75
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PETROLINA DE GOIAS",
   "codigoexterno":9531
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PILAR DE GOIAS",
   "codigoexterno":9535
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PIRACANJUBA",
   "codigoexterno":9539
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PIRANHAS",
   "codigoexterno":9541
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PIRENOPOLIS",
   "codigoexterno":9543
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PIRES DO RIO",
   "codigoexterno":9545
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PLANALTINA",
   "codigoexterno":9595
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PONTALINA",
   "codigoexterno":9549
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PORANGATU",
   "codigoexterno":9555
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PORTEIRAO",
   "codigoexterno":1060
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PORTELANDIA",
   "codigoexterno":9557
},
{
   "siglaestado":"GO",
   "nomemunicipio":"POSSE",
   "codigoexterno":9561
},
{
   "siglaestado":"GO",
   "nomemunicipio":"PROFESSOR JAMIL",
   "codigoexterno":51
},
{
   "siglaestado":"GO",
   "nomemunicipio":"QUIRINOPOLIS",
   "codigoexterno":9563
},
{
   "siglaestado":"GO",
   "nomemunicipio":"RIALMA",
   "codigoexterno":9565
},
{
   "siglaestado":"GO",
   "nomemunicipio":"RIANAPOLIS",
   "codigoexterno":9567
},
{
   "siglaestado":"GO",
   "nomemunicipio":"RIO QUENTE",
   "codigoexterno":9995
},
{
   "siglaestado":"GO",
   "nomemunicipio":"RIO VERDE",
   "codigoexterno":9571
},
{
   "siglaestado":"GO",
   "nomemunicipio":"RUBIATABA",
   "codigoexterno":9573
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SANCLERLANDIA",
   "codigoexterno":9575
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SANTA BARBARA DE GOIAS",
   "codigoexterno":9577
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SANTA CRUZ DE GOIAS",
   "codigoexterno":9579
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SANTA FE DE GOIAS",
   "codigoexterno":9743
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SANTA HELENA DE GOIAS",
   "codigoexterno":9581
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SANTA ISABEL",
   "codigoexterno":9689
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SANTA RITA DO ARAGUAIA",
   "codigoexterno":9583
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SANTA RITA DO NOVO DESTINO",
   "codigoexterno":1062
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SANTA ROSA DE GOIAS",
   "codigoexterno":9585
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SANTA TEREZA DE GOIAS",
   "codigoexterno":9587
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SANTA TEREZINHA DE GOIAS",
   "codigoexterno":9589
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SANTO ANTONIO DA BARRA",
   "codigoexterno":83
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SANTO ANTONIO DE GOIAS",
   "codigoexterno":53
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SANTO ANTONIO DO DESCOBERTO",
   "codigoexterno":9677
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SAO DOMINGOS",
   "codigoexterno":9591
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SAO FRANCISCO DE GOIAS",
   "codigoexterno":9593
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SAO JOAO DA PARAUNA",
   "codigoexterno":9747
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SAO JOAO D\'ALIANCA",
   "codigoexterno":9597
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SAO LUIS DE MONTES BELOS",
   "codigoexterno":9599
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SAO LUIZ DO NORTE",
   "codigoexterno":9749
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SAO MIGUEL DO ARAGUAIA",
   "codigoexterno":9601
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SAO MIGUEL DO PASSA QUATRO",
   "codigoexterno":9751
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SAO PATRICIO",
   "codigoexterno":1064
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SAO SIMAO",
   "codigoexterno":9605
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SENADOR CANEDO",
   "codigoexterno":9753
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SERRANOPOLIS",
   "codigoexterno":9607
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SILVANIA",
   "codigoexterno":9609
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SIMOLANDIA",
   "codigoexterno":9755
},
{
   "siglaestado":"GO",
   "nomemunicipio":"SITIO D\'ABADIA",
   "codigoexterno":9611
},
{
   "siglaestado":"GO",
   "nomemunicipio":"TAQUARAL DE GOIAS",
   "codigoexterno":9617
},
{
   "siglaestado":"GO",
   "nomemunicipio":"TERESINA DE GOIAS",
   "codigoexterno":9759
},
{
   "siglaestado":"GO",
   "nomemunicipio":"TEREZOPOLIS DE GOIAS",
   "codigoexterno":57
},
{
   "siglaestado":"GO",
   "nomemunicipio":"TRES RANCHOS",
   "codigoexterno":9623
},
{
   "siglaestado":"GO",
   "nomemunicipio":"TRINDADE",
   "codigoexterno":9625
},
{
   "siglaestado":"GO",
   "nomemunicipio":"TROMBAS",
   "codigoexterno":9761
},
{
   "siglaestado":"GO",
   "nomemunicipio":"TURVANIA",
   "codigoexterno":9631
},
{
   "siglaestado":"GO",
   "nomemunicipio":"TURVELANDIA",
   "codigoexterno":9765
},
{
   "siglaestado":"GO",
   "nomemunicipio":"UIRAPURU",
   "codigoexterno":59
},
{
   "siglaestado":"GO",
   "nomemunicipio":"URUACU",
   "codigoexterno":9633
},
{
   "siglaestado":"GO",
   "nomemunicipio":"URUANA",
   "codigoexterno":9635
},
{
   "siglaestado":"GO",
   "nomemunicipio":"URUTAI",
   "codigoexterno":9637
},
{
   "siglaestado":"GO",
   "nomemunicipio":"VALPARAISO DE GOIAS",
   "codigoexterno":1066
},
{
   "siglaestado":"GO",
   "nomemunicipio":"VARJAO",
   "codigoexterno":9639
},
{
   "siglaestado":"GO",
   "nomemunicipio":"VIANOPOLIS",
   "codigoexterno":9641
},
{
   "siglaestado":"GO",
   "nomemunicipio":"VICENTINOPOLIS",
   "codigoexterno":9657
},
{
   "siglaestado":"GO",
   "nomemunicipio":"VILA BOA",
   "codigoexterno":67
},
{
   "siglaestado":"GO",
   "nomemunicipio":"VILA PROPICIO",
   "codigoexterno":1068
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ACAILANDIA",
   "codigoexterno":961
},
{
   "siglaestado":"MA",
   "nomemunicipio":"AFONSO CUNHA",
   "codigoexterno":701
},
{
   "siglaestado":"MA",
   "nomemunicipio":"AGUA DOCE DO MARANHAO",
   "codigoexterno":104
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ALCANTARA",
   "codigoexterno":703
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ALDEIAS ALTAS",
   "codigoexterno":705
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ALTAMIRA DO MARANHAO",
   "codigoexterno":707
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ALTO ALEGRE DO MARANHAO",
   "codigoexterno":106
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ALTO ALEGRE DO PINDARE",
   "codigoexterno":108
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ALTO PARNAIBA",
   "codigoexterno":709
},
{
   "siglaestado":"MA",
   "nomemunicipio":"AMAPA DO MARANHAO",
   "codigoexterno":110
},
{
   "siglaestado":"MA",
   "nomemunicipio":"AMARANTE DO MARANHAO",
   "codigoexterno":711
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ANAJATUBA",
   "codigoexterno":713
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ANAPURUS",
   "codigoexterno":715
},
{
   "siglaestado":"MA",
   "nomemunicipio":"APICUM-ACU",
   "codigoexterno":112
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ARAGUANA",
   "codigoexterno":114
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ARAIOSES",
   "codigoexterno":717
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ARAME",
   "codigoexterno":1281
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ARARI",
   "codigoexterno":719
},
{
   "siglaestado":"MA",
   "nomemunicipio":"AXIXA",
   "codigoexterno":721
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BACABAL",
   "codigoexterno":723
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BACABEIRA",
   "codigoexterno":116
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BACURI",
   "codigoexterno":725
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BACURITUBA",
   "codigoexterno":118
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BALSAS",
   "codigoexterno":727
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BARAO DE GRAJAU",
   "codigoexterno":729
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BARRA DO CORDA",
   "codigoexterno":731
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BARREIRINHAS",
   "codigoexterno":733
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BELA VISTA DO MARANHAO",
   "codigoexterno":122
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BELAGUA",
   "codigoexterno":120
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BENEDITO LEITE",
   "codigoexterno":735
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BEQUIMAO",
   "codigoexterno":737
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BERNARDO DO MEARIM",
   "codigoexterno":124
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BOA VISTA DO GURUPI",
   "codigoexterno":126
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BOM JARDIM",
   "codigoexterno":955
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BOM JESUS DAS SELVAS",
   "codigoexterno":128
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BOM LUGAR",
   "codigoexterno":130
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BREJO",
   "codigoexterno":739
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BREJO DE AREIA",
   "codigoexterno":132
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BURITI",
   "codigoexterno":741
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BURITI BRAVO",
   "codigoexterno":743
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BURITICUPU",
   "codigoexterno":134
},
{
   "siglaestado":"MA",
   "nomemunicipio":"BURITIRANA",
   "codigoexterno":136
},
{
   "siglaestado":"MA",
   "nomemunicipio":"CACHOEIRA GRANDE",
   "codigoexterno":138
},
{
   "siglaestado":"MA",
   "nomemunicipio":"CAJAPIO",
   "codigoexterno":745
},
{
   "siglaestado":"MA",
   "nomemunicipio":"CAJARI",
   "codigoexterno":747
},
{
   "siglaestado":"MA",
   "nomemunicipio":"CAMPESTRE DO MARANHAO",
   "codigoexterno":140
},
{
   "siglaestado":"MA",
   "nomemunicipio":"CANDIDO MENDES",
   "codigoexterno":749
},
{
   "siglaestado":"MA",
   "nomemunicipio":"CANTANHEDE",
   "codigoexterno":751
},
{
   "siglaestado":"MA",
   "nomemunicipio":"CAPINZAL DO NORTE",
   "codigoexterno":142
},
{
   "siglaestado":"MA",
   "nomemunicipio":"CAROLINA",
   "codigoexterno":753
},
{
   "siglaestado":"MA",
   "nomemunicipio":"CARUTAPERA",
   "codigoexterno":755
},
{
   "siglaestado":"MA",
   "nomemunicipio":"CAXIAS",
   "codigoexterno":757
},
{
   "siglaestado":"MA",
   "nomemunicipio":"CEDRAL",
   "codigoexterno":759
},
{
   "siglaestado":"MA",
   "nomemunicipio":"CENTRAL DO MARANHAO",
   "codigoexterno":144
},
{
   "siglaestado":"MA",
   "nomemunicipio":"CENTRO DO GUILHERME",
   "codigoexterno":146
},
{
   "siglaestado":"MA",
   "nomemunicipio":"CENTRO NOVO DO MARANHAO",
   "codigoexterno":148
},
{
   "siglaestado":"MA",
   "nomemunicipio":"CHAPADINHA",
   "codigoexterno":761
},
{
   "siglaestado":"MA",
   "nomemunicipio":"CIDELANDIA",
   "codigoexterno":150
},
{
   "siglaestado":"MA",
   "nomemunicipio":"CODO",
   "codigoexterno":763
},
{
   "siglaestado":"MA",
   "nomemunicipio":"COELHO NETO",
   "codigoexterno":765
},
{
   "siglaestado":"MA",
   "nomemunicipio":"COLINAS",
   "codigoexterno":767
},
{
   "siglaestado":"MA",
   "nomemunicipio":"CONCEICAO DO LAGO-ACU",
   "codigoexterno":152
},
{
   "siglaestado":"MA",
   "nomemunicipio":"COROATA",
   "codigoexterno":769
},
{
   "siglaestado":"MA",
   "nomemunicipio":"CURURUPU",
   "codigoexterno":771
},
{
   "siglaestado":"MA",
   "nomemunicipio":"DAVINOPOLIS",
   "codigoexterno":154
},
{
   "siglaestado":"MA",
   "nomemunicipio":"DOM PEDRO",
   "codigoexterno":773
},
{
   "siglaestado":"MA",
   "nomemunicipio":"DUQUE BACELAR",
   "codigoexterno":775
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ESPERANTINOPOLIS",
   "codigoexterno":777
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ESTREITO",
   "codigoexterno":963
},
{
   "siglaestado":"MA",
   "nomemunicipio":"FEIRA NOVA DO MARANHAO",
   "codigoexterno":156
},
{
   "siglaestado":"MA",
   "nomemunicipio":"FERNANDO FALCAO",
   "codigoexterno":158
},
{
   "siglaestado":"MA",
   "nomemunicipio":"FORMOSA DA SERRA NEGRA",
   "codigoexterno":160
},
{
   "siglaestado":"MA",
   "nomemunicipio":"FORTALEZA DOS NOGUEIRAS",
   "codigoexterno":779
},
{
   "siglaestado":"MA",
   "nomemunicipio":"FORTUNA",
   "codigoexterno":781
},
{
   "siglaestado":"MA",
   "nomemunicipio":"GODOFREDO VIANA",
   "codigoexterno":783
},
{
   "siglaestado":"MA",
   "nomemunicipio":"GONCALVES DIAS",
   "codigoexterno":785
},
{
   "siglaestado":"MA",
   "nomemunicipio":"GOVERNADOR ARCHER",
   "codigoexterno":787
},
{
   "siglaestado":"MA",
   "nomemunicipio":"GOVERNADOR EDISON LOBAO",
   "codigoexterno":162
},
{
   "siglaestado":"MA",
   "nomemunicipio":"GOVERNADOR EUGENIO BARROS",
   "codigoexterno":789
},
{
   "siglaestado":"MA",
   "nomemunicipio":"GOVERNADOR LUIZ ROCHA",
   "codigoexterno":164
},
{
   "siglaestado":"MA",
   "nomemunicipio":"GOVERNADOR NEWTON BELLO",
   "codigoexterno":166
},
{
   "siglaestado":"MA",
   "nomemunicipio":"GOVERNADOR NUNES FREIRE",
   "codigoexterno":168
},
{
   "siglaestado":"MA",
   "nomemunicipio":"GRACA ARANHA",
   "codigoexterno":791
},
{
   "siglaestado":"MA",
   "nomemunicipio":"GRAJAU",
   "codigoexterno":793
},
{
   "siglaestado":"MA",
   "nomemunicipio":"GUIMARAES",
   "codigoexterno":795
},
{
   "siglaestado":"MA",
   "nomemunicipio":"HUMBERTO DE CAMPOS",
   "codigoexterno":797
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ICATU",
   "codigoexterno":799
},
{
   "siglaestado":"MA",
   "nomemunicipio":"IGARAPE DO MEIO",
   "codigoexterno":170
},
{
   "siglaestado":"MA",
   "nomemunicipio":"IGARAPE GRANDE",
   "codigoexterno":801
},
{
   "siglaestado":"MA",
   "nomemunicipio":"IMPERATRIZ",
   "codigoexterno":803
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ITAIPAVA DO GRAJAU",
   "codigoexterno":172
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ITAPECURU MIRIM",
   "codigoexterno":807
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ITINGA DO MARANHAO",
   "codigoexterno":174
},
{
   "siglaestado":"MA",
   "nomemunicipio":"JATOBA",
   "codigoexterno":176
},
{
   "siglaestado":"MA",
   "nomemunicipio":"JENIPAPO DOS VIEIRAS",
   "codigoexterno":178
},
{
   "siglaestado":"MA",
   "nomemunicipio":"JOAO LISBOA",
   "codigoexterno":809
},
{
   "siglaestado":"MA",
   "nomemunicipio":"JOSELANDIA",
   "codigoexterno":811
},
{
   "siglaestado":"MA",
   "nomemunicipio":"JUNCO DO MARANHAO",
   "codigoexterno":180
},
{
   "siglaestado":"MA",
   "nomemunicipio":"LAGO DA PEDRA",
   "codigoexterno":813
},
{
   "siglaestado":"MA",
   "nomemunicipio":"LAGO DO JUNCO",
   "codigoexterno":815
},
{
   "siglaestado":"MA",
   "nomemunicipio":"LAGO DOS RODRIGUES",
   "codigoexterno":184
},
{
   "siglaestado":"MA",
   "nomemunicipio":"LAGO VERDE",
   "codigoexterno":817
},
{
   "siglaestado":"MA",
   "nomemunicipio":"LAGOA DO MATO",
   "codigoexterno":182
},
{
   "siglaestado":"MA",
   "nomemunicipio":"LAGOA GRANDE DO MARANHAO",
   "codigoexterno":186
},
{
   "siglaestado":"MA",
   "nomemunicipio":"LAJEADO NOVO",
   "codigoexterno":188
},
{
   "siglaestado":"MA",
   "nomemunicipio":"LIMA CAMPOS",
   "codigoexterno":819
},
{
   "siglaestado":"MA",
   "nomemunicipio":"LORETO",
   "codigoexterno":821
},
{
   "siglaestado":"MA",
   "nomemunicipio":"LUIS DOMINGUES",
   "codigoexterno":823
},
{
   "siglaestado":"MA",
   "nomemunicipio":"MAGALHAES DE ALMEIDA",
   "codigoexterno":825
},
{
   "siglaestado":"MA",
   "nomemunicipio":"MARACACUME",
   "codigoexterno":190
},
{
   "siglaestado":"MA",
   "nomemunicipio":"MARAJA DO SENA",
   "codigoexterno":192
},
{
   "siglaestado":"MA",
   "nomemunicipio":"MARANHAOZINHO",
   "codigoexterno":194
},
{
   "siglaestado":"MA",
   "nomemunicipio":"MATA ROMA",
   "codigoexterno":827
},
{
   "siglaestado":"MA",
   "nomemunicipio":"MATINHA",
   "codigoexterno":829
},
{
   "siglaestado":"MA",
   "nomemunicipio":"MATOES",
   "codigoexterno":831
},
{
   "siglaestado":"MA",
   "nomemunicipio":"MATOES DO NORTE",
   "codigoexterno":196
},
{
   "siglaestado":"MA",
   "nomemunicipio":"MILAGRES DO MARANHAO",
   "codigoexterno":198
},
{
   "siglaestado":"MA",
   "nomemunicipio":"MIRADOR",
   "codigoexterno":833
},
{
   "siglaestado":"MA",
   "nomemunicipio":"MIRANDA DO NORTE",
   "codigoexterno":1283
},
{
   "siglaestado":"MA",
   "nomemunicipio":"MIRINZAL",
   "codigoexterno":835
},
{
   "siglaestado":"MA",
   "nomemunicipio":"MONCAO",
   "codigoexterno":837
},
{
   "siglaestado":"MA",
   "nomemunicipio":"MONTES ALTOS",
   "codigoexterno":839
},
{
   "siglaestado":"MA",
   "nomemunicipio":"MORROS",
   "codigoexterno":841
},
{
   "siglaestado":"MA",
   "nomemunicipio":"NINA RODRIGUES",
   "codigoexterno":843
},
{
   "siglaestado":"MA",
   "nomemunicipio":"NOVA COLINAS",
   "codigoexterno":200
},
{
   "siglaestado":"MA",
   "nomemunicipio":"NOVA IORQUE",
   "codigoexterno":845
},
{
   "siglaestado":"MA",
   "nomemunicipio":"NOVA OLINDA DO MARANHAO",
   "codigoexterno":202
},
{
   "siglaestado":"MA",
   "nomemunicipio":"OLHO D\'AGUA DAS CUNHAS",
   "codigoexterno":847
},
{
   "siglaestado":"MA",
   "nomemunicipio":"OLINDA NOVA DO MARANHAO",
   "codigoexterno":204
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PACO DO LUMIAR",
   "codigoexterno":849
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PALMEIRANDIA",
   "codigoexterno":851
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PARAIBANO",
   "codigoexterno":853
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PARNARAMA",
   "codigoexterno":855
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PASSAGEM FRANCA",
   "codigoexterno":857
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PASTOS BONS",
   "codigoexterno":859
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PAULINO NEVES",
   "codigoexterno":206
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PAULO RAMOS",
   "codigoexterno":959
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PEDREIRAS",
   "codigoexterno":861
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PEDRO DO ROSARIO",
   "codigoexterno":208
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PENALVA",
   "codigoexterno":863
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PERI MIRIM",
   "codigoexterno":865
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PERITORO",
   "codigoexterno":210
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PINDARE MIRIM",
   "codigoexterno":867
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PINHEIRO",
   "codigoexterno":869
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PIO XII",
   "codigoexterno":871
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PIRAPEMAS",
   "codigoexterno":873
},
{
   "siglaestado":"MA",
   "nomemunicipio":"POCAO DE PEDRAS",
   "codigoexterno":875
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PORTO FRANCO",
   "codigoexterno":877
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PORTO RICO DO MARANHAO",
   "codigoexterno":212
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PRESIDENTE DUTRA",
   "codigoexterno":879
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PRESIDENTE JUSCELINO",
   "codigoexterno":881
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PRESIDENTE MEDICI",
   "codigoexterno":214
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PRESIDENTE SARNEY",
   "codigoexterno":216
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PRESIDENTE VARGAS",
   "codigoexterno":883
},
{
   "siglaestado":"MA",
   "nomemunicipio":"PRIMEIRA CRUZ",
   "codigoexterno":885
},
{
   "siglaestado":"MA",
   "nomemunicipio":"RAPOSA",
   "codigoexterno":218
},
{
   "siglaestado":"MA",
   "nomemunicipio":"RIACHAO",
   "codigoexterno":887
},
{
   "siglaestado":"MA",
   "nomemunicipio":"RIBAMAR FIQUENE",
   "codigoexterno":220
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ROSARIO",
   "codigoexterno":891
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAMBAIBA",
   "codigoexterno":893
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SANTA FILOMENA DO MARANHAO",
   "codigoexterno":222
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SANTA HELENA",
   "codigoexterno":895
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SANTA INES",
   "codigoexterno":957
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SANTA LUZIA",
   "codigoexterno":897
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SANTA LUZIA DO PARUA",
   "codigoexterno":1285
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SANTA QUITERIA DO MARANHAO",
   "codigoexterno":899
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SANTA RITA",
   "codigoexterno":901
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SANTANA DO MARANHAO",
   "codigoexterno":224
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SANTO AMARO DO MARANHAO",
   "codigoexterno":226
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SANTO ANTONIO DOS LOPES",
   "codigoexterno":903
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO BENEDITO DO RIO PRETO",
   "codigoexterno":905
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO BENTO",
   "codigoexterno":907
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO BERNARDO",
   "codigoexterno":909
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO DOMINGOS DO AZEITAO",
   "codigoexterno":228
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO DOMINGOS DO MARANHAO",
   "codigoexterno":911
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO FELIX DE BALSAS",
   "codigoexterno":913
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO FRANCISCO DO BREJAO",
   "codigoexterno":230
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO FRANCISCO DO MARANHAO",
   "codigoexterno":915
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO JOAO BATISTA",
   "codigoexterno":917
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO JOAO DO CARU",
   "codigoexterno":232
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO JOAO DO PARAISO",
   "codigoexterno":234
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO JOAO DO SOTER",
   "codigoexterno":236
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO JOAO DOS PATOS",
   "codigoexterno":919
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO JOSE DE RIBAMAR",
   "codigoexterno":889
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO JOSE DOS BASILIOS",
   "codigoexterno":238
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO LUIS",
   "codigoexterno":921
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO LUIS GONZAGA DO MARANHAO",
   "codigoexterno":805
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO MATEUS DO MARANHAO",
   "codigoexterno":923
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO PEDRO DA AGUA BRANCA",
   "codigoexterno":240
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO PEDRO DOS CRENTES",
   "codigoexterno":242
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO RAIMUNDO DAS MANGABEIRAS",
   "codigoexterno":925
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO RAIMUNDO DO DOCA BEZERRA",
   "codigoexterno":244
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO ROBERTO",
   "codigoexterno":246
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SAO VICENTE FERRER",
   "codigoexterno":927
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SATUBINHA",
   "codigoexterno":248
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SENADOR ALEXANDRE COSTA",
   "codigoexterno":250
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SENADOR LA ROCQUE",
   "codigoexterno":252
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SERRANO DO MARANHAO",
   "codigoexterno":254
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SITIO NOVO",
   "codigoexterno":929
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SUCUPIRA DO NORTE",
   "codigoexterno":931
},
{
   "siglaestado":"MA",
   "nomemunicipio":"SUCUPIRA DO RIACHAO",
   "codigoexterno":256
},
{
   "siglaestado":"MA",
   "nomemunicipio":"TASSO FRAGOSO",
   "codigoexterno":933
},
{
   "siglaestado":"MA",
   "nomemunicipio":"TIMBIRAS",
   "codigoexterno":935
},
{
   "siglaestado":"MA",
   "nomemunicipio":"TIMON",
   "codigoexterno":937
},
{
   "siglaestado":"MA",
   "nomemunicipio":"TRIZIDELA DO VALE",
   "codigoexterno":258
},
{
   "siglaestado":"MA",
   "nomemunicipio":"TUFILANDIA",
   "codigoexterno":260
},
{
   "siglaestado":"MA",
   "nomemunicipio":"TUNTUM",
   "codigoexterno":939
},
{
   "siglaestado":"MA",
   "nomemunicipio":"TURIACU",
   "codigoexterno":941
},
{
   "siglaestado":"MA",
   "nomemunicipio":"TURILANDIA",
   "codigoexterno":262
},
{
   "siglaestado":"MA",
   "nomemunicipio":"TUTOIA",
   "codigoexterno":943
},
{
   "siglaestado":"MA",
   "nomemunicipio":"URBANO SANTOS",
   "codigoexterno":945
},
{
   "siglaestado":"MA",
   "nomemunicipio":"VARGEM GRANDE",
   "codigoexterno":947
},
{
   "siglaestado":"MA",
   "nomemunicipio":"VIANA",
   "codigoexterno":949
},
{
   "siglaestado":"MA",
   "nomemunicipio":"VILA NOVA DOS MARTIRIOS",
   "codigoexterno":264
},
{
   "siglaestado":"MA",
   "nomemunicipio":"VITORIA DO MEARIM",
   "codigoexterno":951
},
{
   "siglaestado":"MA",
   "nomemunicipio":"VITORINO FREIRE",
   "codigoexterno":953
},
{
   "siglaestado":"MA",
   "nomemunicipio":"ZE DOCA",
   "codigoexterno":1287
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ABADIA DOS DOURADOS",
   "codigoexterno":4001
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ABAETE",
   "codigoexterno":4003
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ABRE CAMPO",
   "codigoexterno":4005
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ACAIACA",
   "codigoexterno":4007
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ACUCENA",
   "codigoexterno":4009
},
{
   "siglaestado":"MG",
   "nomemunicipio":"AGUA BOA",
   "codigoexterno":4011
},
{
   "siglaestado":"MG",
   "nomemunicipio":"AGUA COMPRIDA",
   "codigoexterno":4013
},
{
   "siglaestado":"MG",
   "nomemunicipio":"AGUANIL",
   "codigoexterno":4015
},
{
   "siglaestado":"MG",
   "nomemunicipio":"AGUAS FORMOSAS",
   "codigoexterno":4017
},
{
   "siglaestado":"MG",
   "nomemunicipio":"AGUAS VERMELHAS",
   "codigoexterno":4019
},
{
   "siglaestado":"MG",
   "nomemunicipio":"AIMORES",
   "codigoexterno":4021
},
{
   "siglaestado":"MG",
   "nomemunicipio":"AIURUOCA",
   "codigoexterno":4023
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ALAGOA",
   "codigoexterno":4025
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ALBERTINA",
   "codigoexterno":4027
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ALEM PARAIBA",
   "codigoexterno":4029
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ALFENAS",
   "codigoexterno":4031
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ALFREDO VASCONCELOS",
   "codigoexterno":2681
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ALMENARA",
   "codigoexterno":4033
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ALPERCATA",
   "codigoexterno":4035
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ALPINOPOLIS",
   "codigoexterno":4037
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ALTEROSA",
   "codigoexterno":4039
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ALTO CAPARAO",
   "codigoexterno":564
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ALTO JEQUITIBA",
   "codigoexterno":5069
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ALTO RIO DOCE",
   "codigoexterno":4041
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ALVARENGA",
   "codigoexterno":4043
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ALVINOPOLIS",
   "codigoexterno":4045
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ALVORADA DE MINAS",
   "codigoexterno":4047
},
{
   "siglaestado":"MG",
   "nomemunicipio":"AMPARO DA SERRA",
   "codigoexterno":4049
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ANDRADAS",
   "codigoexterno":4051
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ANDRELANDIA",
   "codigoexterno":4055
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ANGELANDIA",
   "codigoexterno":566
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ANTONIO CARLOS",
   "codigoexterno":4057
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ANTONIO DIAS",
   "codigoexterno":4059
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ANTONIO PRADO DE MINAS",
   "codigoexterno":4061
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ARACAI",
   "codigoexterno":4063
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ARACITABA",
   "codigoexterno":4065
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ARACUAI",
   "codigoexterno":4067
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ARAGUARI",
   "codigoexterno":4069
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ARANTINA",
   "codigoexterno":4071
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ARAPONGA",
   "codigoexterno":4073
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ARAPORA",
   "codigoexterno":2903
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ARAPUA",
   "codigoexterno":4075
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ARAUJOS",
   "codigoexterno":4077
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ARAXA",
   "codigoexterno":4079
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ARCEBURGO",
   "codigoexterno":4081
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ARCOS",
   "codigoexterno":4083
},
{
   "siglaestado":"MG",
   "nomemunicipio":"AREADO",
   "codigoexterno":4085
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ARGIRITA",
   "codigoexterno":4087
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ARICANDUVA",
   "codigoexterno":568
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ARINOS",
   "codigoexterno":4089
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ASTOLFO DUTRA",
   "codigoexterno":4091
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ATALEIA",
   "codigoexterno":4093
},
{
   "siglaestado":"MG",
   "nomemunicipio":"AUGUSTO DE LIMA",
   "codigoexterno":4095
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BAEPENDI",
   "codigoexterno":4097
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BALDIM",
   "codigoexterno":4099
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BAMBUI",
   "codigoexterno":4101
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BANDEIRA",
   "codigoexterno":4103
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BANDEIRA DO SUL",
   "codigoexterno":4105
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BARAO DE COCAIS",
   "codigoexterno":4107
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BARAO DE MONTE ALTO",
   "codigoexterno":4109
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BARBACENA",
   "codigoexterno":4111
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BARRA LONGA",
   "codigoexterno":4113
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BARROSO",
   "codigoexterno":4117
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BELA VISTA DE MINAS",
   "codigoexterno":4119
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BELMIRO BRAGA",
   "codigoexterno":4121
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BELO HORIZONTE",
   "codigoexterno":4123
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BELO ORIENTE",
   "codigoexterno":4125
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BELO VALE",
   "codigoexterno":4127
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BERILO",
   "codigoexterno":4129
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BERIZAL",
   "codigoexterno":570
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BERTOPOLIS",
   "codigoexterno":4131
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BETIM",
   "codigoexterno":4133
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BIAS FORTES",
   "codigoexterno":4135
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BICAS",
   "codigoexterno":4137
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BIQUINHAS",
   "codigoexterno":4139
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BOA ESPERANCA",
   "codigoexterno":4141
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BOCAINA DE MINAS",
   "codigoexterno":4143
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BOCAIUVA",
   "codigoexterno":4145
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BOM DESPACHO",
   "codigoexterno":4147
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BOM JARDIM DE MINAS",
   "codigoexterno":4149
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BOM JESUS DA PENHA",
   "codigoexterno":4151
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BOM JESUS DO AMPARO",
   "codigoexterno":4153
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BOM JESUS DO GALHO",
   "codigoexterno":4155
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BOM REPOUSO",
   "codigoexterno":4157
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BOM SUCESSO",
   "codigoexterno":4159
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BONFIM",
   "codigoexterno":4161
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BONFINOPOLIS DE MINAS",
   "codigoexterno":4163
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BONITO DE MINAS",
   "codigoexterno":572
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BORDA DA MATA",
   "codigoexterno":4165
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BOTELHOS",
   "codigoexterno":4167
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BOTUMIRIM",
   "codigoexterno":4169
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BRAS PIRES",
   "codigoexterno":4173
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BRASILANDIA DE MINAS",
   "codigoexterno":574
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BRASILIA DE MINAS",
   "codigoexterno":4171
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BRASOPOLIS",
   "codigoexterno":4177
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BRAUNAS",
   "codigoexterno":4175
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BRUMADINHO",
   "codigoexterno":4179
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BUENO BRANDAO",
   "codigoexterno":4181
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BUENOPOLIS",
   "codigoexterno":4183
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BUGRE",
   "codigoexterno":576
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BURITIS",
   "codigoexterno":4185
},
{
   "siglaestado":"MG",
   "nomemunicipio":"BURITIZEIRO",
   "codigoexterno":4187
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CABECEIRA GRANDE",
   "codigoexterno":578
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CABO VERDE",
   "codigoexterno":4189
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CACHOEIRA DA PRATA",
   "codigoexterno":4191
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CACHOEIRA DE MINAS",
   "codigoexterno":4193
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CACHOEIRA DE PAJEU",
   "codigoexterno":4053
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CACHOEIRA DOURADA",
   "codigoexterno":4195
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAETANOPOLIS",
   "codigoexterno":4197
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAETE",
   "codigoexterno":4199
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAIANA",
   "codigoexterno":4201
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAJURI",
   "codigoexterno":4203
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CALDAS",
   "codigoexterno":4205
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAMACHO",
   "codigoexterno":4207
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAMANDUCAIA",
   "codigoexterno":4209
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAMBUI",
   "codigoexterno":4211
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAMBUQUIRA",
   "codigoexterno":4213
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAMPANARIO",
   "codigoexterno":4215
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAMPANHA",
   "codigoexterno":4217
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAMPESTRE",
   "codigoexterno":4219
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAMPINA VERDE",
   "codigoexterno":4221
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAMPO AZUL",
   "codigoexterno":580
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAMPO BELO",
   "codigoexterno":4223
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAMPO DO MEIO",
   "codigoexterno":4225
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAMPO FLORIDO",
   "codigoexterno":4227
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAMPOS ALTOS",
   "codigoexterno":4229
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAMPOS GERAIS",
   "codigoexterno":4231
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CANA VERDE",
   "codigoexterno":4237
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CANAA",
   "codigoexterno":4233
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CANAPOLIS",
   "codigoexterno":4235
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CANDEIAS",
   "codigoexterno":4239
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CANTAGALO",
   "codigoexterno":582
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAPARAO",
   "codigoexterno":4241
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAPELA NOVA",
   "codigoexterno":4243
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAPELINHA",
   "codigoexterno":4245
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAPETINGA",
   "codigoexterno":4247
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAPIM BRANCO",
   "codigoexterno":4249
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAPINOPOLIS",
   "codigoexterno":4251
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAPITAO ANDRADE",
   "codigoexterno":2651
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAPITAO ENEAS",
   "codigoexterno":4253
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAPITOLIO",
   "codigoexterno":4255
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAPUTIRA",
   "codigoexterno":4257
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CARAI",
   "codigoexterno":4259
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CARANAIBA",
   "codigoexterno":4261
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CARANDAI",
   "codigoexterno":4263
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CARANGOLA",
   "codigoexterno":4265
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CARATINGA",
   "codigoexterno":4267
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CARBONITA",
   "codigoexterno":4269
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAREACU",
   "codigoexterno":4271
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CARLOS CHAGAS",
   "codigoexterno":4273
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CARMESIA",
   "codigoexterno":4275
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CARMO DA CACHOEIRA",
   "codigoexterno":4277
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CARMO DA MATA",
   "codigoexterno":4279
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CARMO DE MINAS",
   "codigoexterno":4281
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CARMO DO CAJURU",
   "codigoexterno":4283
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CARMO DO PARANAIBA",
   "codigoexterno":4285
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CARMO DO RIO CLARO",
   "codigoexterno":4287
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CARMOPOLIS DE MINAS",
   "codigoexterno":4289
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CARNEIRINHO",
   "codigoexterno":2685
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CARRANCAS",
   "codigoexterno":4291
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CARVALHOPOLIS",
   "codigoexterno":4293
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CARVALHOS",
   "codigoexterno":4295
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CASA GRANDE",
   "codigoexterno":4297
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CASCALHO RICO",
   "codigoexterno":4299
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CASSIA",
   "codigoexterno":4301
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CATAGUASES",
   "codigoexterno":4305
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CATAS ALTAS",
   "codigoexterno":584
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CATAS ALTAS DA NORUEGA",
   "codigoexterno":4307
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CATUJI",
   "codigoexterno":2653
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CATUTI",
   "codigoexterno":586
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CAXAMBU",
   "codigoexterno":4309
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CEDRO DO ABAETE",
   "codigoexterno":4311
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CENTRAL DE MINAS",
   "codigoexterno":4313
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CENTRALINA",
   "codigoexterno":4315
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CHACARA",
   "codigoexterno":4317
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CHALE",
   "codigoexterno":4319
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CHAPADA DO NORTE",
   "codigoexterno":4321
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CHAPADA GAUCHA",
   "codigoexterno":588
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CHIADOR",
   "codigoexterno":4323
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CIPOTANEA",
   "codigoexterno":4325
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CLARAVAL",
   "codigoexterno":4327
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CLARO DOS POCOES",
   "codigoexterno":4329
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CLAUDIO",
   "codigoexterno":4331
},
{
   "siglaestado":"MG",
   "nomemunicipio":"COIMBRA",
   "codigoexterno":4333
},
{
   "siglaestado":"MG",
   "nomemunicipio":"COLUNA",
   "codigoexterno":4335
},
{
   "siglaestado":"MG",
   "nomemunicipio":"COMENDADOR GOMES",
   "codigoexterno":4337
},
{
   "siglaestado":"MG",
   "nomemunicipio":"COMERCINHO",
   "codigoexterno":4339
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CONCEICAO DA APARECIDA",
   "codigoexterno":4341
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CONCEICAO DA BARRA DE MINAS",
   "codigoexterno":4303
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CONCEICAO DAS ALAGOAS",
   "codigoexterno":4345
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CONCEICAO DAS PEDRAS",
   "codigoexterno":4343
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CONCEICAO DE IPANEMA",
   "codigoexterno":4347
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CONCEICAO DO MATO DENTRO",
   "codigoexterno":4349
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CONCEICAO DO PARA",
   "codigoexterno":4351
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CONCEICAO DO RIO VERDE",
   "codigoexterno":4353
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CONCEICAO DOS OUROS",
   "codigoexterno":4355
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CONEGO MARINHO",
   "codigoexterno":590
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CONFINS",
   "codigoexterno":592
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CONGONHAL",
   "codigoexterno":4357
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CONGONHAS",
   "codigoexterno":4359
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CONGONHAS DO NORTE",
   "codigoexterno":4361
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CONQUISTA",
   "codigoexterno":4363
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CONSELHEIRO LAFAIETE",
   "codigoexterno":4365
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CONSELHEIRO PENA",
   "codigoexterno":4367
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CONSOLACAO",
   "codigoexterno":4369
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CONTAGEM",
   "codigoexterno":4371
},
{
   "siglaestado":"MG",
   "nomemunicipio":"COQUEIRAL",
   "codigoexterno":4373
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CORACAO DE JESUS",
   "codigoexterno":4375
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CORDISBURGO",
   "codigoexterno":4377
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CORDISLANDIA",
   "codigoexterno":4379
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CORINTO",
   "codigoexterno":4381
},
{
   "siglaestado":"MG",
   "nomemunicipio":"COROACI",
   "codigoexterno":4383
},
{
   "siglaestado":"MG",
   "nomemunicipio":"COROMANDEL",
   "codigoexterno":4385
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CORONEL FABRICIANO",
   "codigoexterno":4387
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CORONEL MURTA",
   "codigoexterno":4389
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CORONEL PACHECO",
   "codigoexterno":4391
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CORONEL XAVIER CHAVES",
   "codigoexterno":4393
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CORREGO DANTA",
   "codigoexterno":4395
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CORREGO DO BOM JESUS",
   "codigoexterno":4397
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CORREGO FUNDO",
   "codigoexterno":594
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CORREGO NOVO",
   "codigoexterno":4399
},
{
   "siglaestado":"MG",
   "nomemunicipio":"COUTO DE MAGALHAES DE MINAS",
   "codigoexterno":4401
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CRISOLITA",
   "codigoexterno":596
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CRISTAIS",
   "codigoexterno":4403
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CRISTALIA",
   "codigoexterno":4405
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CRISTIANO OTONI",
   "codigoexterno":4407
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CRISTINA",
   "codigoexterno":4409
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CRUCILANDIA",
   "codigoexterno":4411
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CRUZEIRO DA FORTALEZA",
   "codigoexterno":4413
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CRUZILIA",
   "codigoexterno":4415
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CUPARAQUE",
   "codigoexterno":598
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CURRAL DE DENTRO",
   "codigoexterno":600
},
{
   "siglaestado":"MG",
   "nomemunicipio":"CURVELO",
   "codigoexterno":4417
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DATAS",
   "codigoexterno":4419
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DELFIM MOREIRA",
   "codigoexterno":4421
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DELFINOPOLIS",
   "codigoexterno":4423
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DELTA",
   "codigoexterno":602
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DESCOBERTO",
   "codigoexterno":4425
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DESTERRO DE ENTRE RIOS",
   "codigoexterno":4427
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DESTERRO DO MELO",
   "codigoexterno":4429
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DIAMANTINA",
   "codigoexterno":4431
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DIOGO DE VASCONCELOS",
   "codigoexterno":4433
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DIONISIO",
   "codigoexterno":4435
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DIVINESIA",
   "codigoexterno":4437
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DIVINO",
   "codigoexterno":4439
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DIVINO DAS LARANJEIRAS",
   "codigoexterno":4441
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DIVINOLANDIA DE MINAS",
   "codigoexterno":4443
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DIVINOPOLIS",
   "codigoexterno":4445
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DIVISA ALEGRE",
   "codigoexterno":604
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DIVISA NOVA",
   "codigoexterno":4447
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DIVISOPOLIS",
   "codigoexterno":2657
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DOM BOSCO",
   "codigoexterno":606
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DOM CAVATI",
   "codigoexterno":4449
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DOM JOAQUIM",
   "codigoexterno":4451
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DOM SILVERIO",
   "codigoexterno":4453
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DOM VICOSO",
   "codigoexterno":4455
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DONA EUZEBIA",
   "codigoexterno":4457
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DORES DE CAMPOS",
   "codigoexterno":4459
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DORES DE GUANHAES",
   "codigoexterno":4461
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DORES DO INDAIA",
   "codigoexterno":4463
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DORES DO TURVO",
   "codigoexterno":4465
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DORESOPOLIS",
   "codigoexterno":4467
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DOURADOQUARA",
   "codigoexterno":4469
},
{
   "siglaestado":"MG",
   "nomemunicipio":"DURANDE",
   "codigoexterno":2675
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ELOI MENDES",
   "codigoexterno":4471
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ENGENHEIRO CALDAS",
   "codigoexterno":4473
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ENGENHEIRO NAVARRO",
   "codigoexterno":4475
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ENTRE FOLHAS",
   "codigoexterno":2663
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ENTRE RIOS DE MINAS",
   "codigoexterno":4477
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ERVALIA",
   "codigoexterno":4479
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ESMERALDAS",
   "codigoexterno":4481
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ESPERA FELIZ",
   "codigoexterno":4483
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ESPINOSA",
   "codigoexterno":4485
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ESPIRITO SANTO DO DOURADO",
   "codigoexterno":4487
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ESTIVA",
   "codigoexterno":4489
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ESTRELA DALVA",
   "codigoexterno":4491
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ESTRELA DO INDAIA",
   "codigoexterno":4493
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ESTRELA DO SUL",
   "codigoexterno":4495
},
{
   "siglaestado":"MG",
   "nomemunicipio":"EUGENOPOLIS",
   "codigoexterno":4497
},
{
   "siglaestado":"MG",
   "nomemunicipio":"EWBANK DA CAMARA",
   "codigoexterno":4499
},
{
   "siglaestado":"MG",
   "nomemunicipio":"EXTREMA",
   "codigoexterno":4501
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FAMA",
   "codigoexterno":4503
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FARIA LEMOS",
   "codigoexterno":4505
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FELICIO DOS SANTOS",
   "codigoexterno":4507
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FELISBURGO",
   "codigoexterno":4511
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FELIXLANDIA",
   "codigoexterno":4513
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FERNANDES TOURINHO",
   "codigoexterno":4515
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FERROS",
   "codigoexterno":4517
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FERVEDOURO",
   "codigoexterno":2683
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FLORESTAL",
   "codigoexterno":4519
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FORMIGA",
   "codigoexterno":4521
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FORMOSO",
   "codigoexterno":4523
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FORTALEZA DE MINAS",
   "codigoexterno":4525
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FORTUNA DE MINAS",
   "codigoexterno":4527
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FRANCISCO BADARO",
   "codigoexterno":4529
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FRANCISCO DUMONT",
   "codigoexterno":4531
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FRANCISCO SA",
   "codigoexterno":4533
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FRANCISCOPOLIS",
   "codigoexterno":608
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FREI GASPAR",
   "codigoexterno":4535
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FREI INOCENCIO",
   "codigoexterno":4537
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FREI LAGONEGRO",
   "codigoexterno":610
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FRONTEIRA",
   "codigoexterno":4539
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FRONTEIRA DOS VALES",
   "codigoexterno":4935
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FRUTA DE LEITE",
   "codigoexterno":612
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FRUTAL",
   "codigoexterno":4541
},
{
   "siglaestado":"MG",
   "nomemunicipio":"FUNILANDIA",
   "codigoexterno":4543
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GALILEIA",
   "codigoexterno":4545
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GAMELEIRAS",
   "codigoexterno":614
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GLAUCILANDIA",
   "codigoexterno":616
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GOIABEIRA",
   "codigoexterno":618
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GOIANA",
   "codigoexterno":620
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GONCALVES",
   "codigoexterno":4547
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GONZAGA",
   "codigoexterno":4549
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GOUVEA",
   "codigoexterno":4551
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GOVERNADOR VALADARES",
   "codigoexterno":4553
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GRAO MOGOL",
   "codigoexterno":4555
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GRUPIARA",
   "codigoexterno":4557
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GUANHAES",
   "codigoexterno":4559
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GUAPE",
   "codigoexterno":4561
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GUARACIABA",
   "codigoexterno":4563
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GUARACIAMA",
   "codigoexterno":622
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GUARANESIA",
   "codigoexterno":4565
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GUARANI",
   "codigoexterno":4567
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GUARARA",
   "codigoexterno":4569
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GUARDA-MOR",
   "codigoexterno":4571
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GUAXUPE",
   "codigoexterno":4573
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GUIDOVAL",
   "codigoexterno":4575
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GUIMARANIA",
   "codigoexterno":4577
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GUIRICEMA",
   "codigoexterno":4579
},
{
   "siglaestado":"MG",
   "nomemunicipio":"GURINHATA",
   "codigoexterno":4581
},
{
   "siglaestado":"MG",
   "nomemunicipio":"HELIODORA",
   "codigoexterno":4583
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IAPU",
   "codigoexterno":4585
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IBERTIOGA",
   "codigoexterno":4587
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IBIA",
   "codigoexterno":4589
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IBIAI",
   "codigoexterno":4591
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IBIRACATU",
   "codigoexterno":624
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IBIRACI",
   "codigoexterno":4593
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IBIRITE",
   "codigoexterno":4595
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IBITIURA DE MINAS",
   "codigoexterno":4597
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IBITURUNA",
   "codigoexterno":4599
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ICARAI DE MINAS",
   "codigoexterno":2693
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IGARAPE",
   "codigoexterno":4601
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IGARATINGA",
   "codigoexterno":4603
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IGUATAMA",
   "codigoexterno":4605
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IJACI",
   "codigoexterno":4607
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ILICINEA",
   "codigoexterno":4609
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IMBE DE MINAS",
   "codigoexterno":626
},
{
   "siglaestado":"MG",
   "nomemunicipio":"INCONFIDENTES",
   "codigoexterno":4611
},
{
   "siglaestado":"MG",
   "nomemunicipio":"INDAIABIRA",
   "codigoexterno":628
},
{
   "siglaestado":"MG",
   "nomemunicipio":"INDIANOPOLIS",
   "codigoexterno":4613
},
{
   "siglaestado":"MG",
   "nomemunicipio":"INGAI",
   "codigoexterno":4615
},
{
   "siglaestado":"MG",
   "nomemunicipio":"INHAPIM",
   "codigoexterno":4617
},
{
   "siglaestado":"MG",
   "nomemunicipio":"INHAUMA",
   "codigoexterno":4619
},
{
   "siglaestado":"MG",
   "nomemunicipio":"INIMUTABA",
   "codigoexterno":4621
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IPABA",
   "codigoexterno":2665
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IPANEMA",
   "codigoexterno":4623
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IPATINGA",
   "codigoexterno":4625
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IPIACU",
   "codigoexterno":4627
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IPUIUNA",
   "codigoexterno":4629
},
{
   "siglaestado":"MG",
   "nomemunicipio":"IRAI DE MINAS",
   "codigoexterno":4631
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITABIRA",
   "codigoexterno":4633
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITABIRINHA",
   "codigoexterno":4635
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITABIRITO",
   "codigoexterno":4637
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITACAMBIRA",
   "codigoexterno":4639
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITACARAMBI",
   "codigoexterno":4641
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITAGUARA",
   "codigoexterno":4643
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITAIPE",
   "codigoexterno":4645
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITAJUBA",
   "codigoexterno":4647
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITAMARANDIBA",
   "codigoexterno":4649
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITAMARATI DE MINAS",
   "codigoexterno":4651
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITAMBACURI",
   "codigoexterno":4653
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITAMBE DO MATO DENTRO",
   "codigoexterno":4655
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITAMOGI",
   "codigoexterno":4657
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITAMONTE",
   "codigoexterno":4659
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITANHANDU",
   "codigoexterno":4661
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITANHOMI",
   "codigoexterno":4663
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITAOBIM",
   "codigoexterno":4665
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITAPAGIPE",
   "codigoexterno":4667
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITAPECERICA",
   "codigoexterno":4669
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITAPEVA",
   "codigoexterno":4671
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITATIAIUCU",
   "codigoexterno":4673
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITAU DE MINAS",
   "codigoexterno":5731
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITAUNA",
   "codigoexterno":4675
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITAVERAVA",
   "codigoexterno":4677
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITINGA",
   "codigoexterno":4679
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITUETA",
   "codigoexterno":4681
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITUIUTABA",
   "codigoexterno":4683
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITUMIRIM",
   "codigoexterno":4685
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITURAMA",
   "codigoexterno":4687
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ITUTINGA",
   "codigoexterno":4689
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JABOTICATUBAS",
   "codigoexterno":4691
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JACINTO",
   "codigoexterno":4693
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JACUI",
   "codigoexterno":4695
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JACUTINGA",
   "codigoexterno":4697
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JAGUARACU",
   "codigoexterno":4699
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JAIBA",
   "codigoexterno":2893
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JAMPRUCA",
   "codigoexterno":2655
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JANAUBA",
   "codigoexterno":4701
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JANUARIA",
   "codigoexterno":4703
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JAPARAIBA",
   "codigoexterno":4705
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JAPONVAR",
   "codigoexterno":630
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JECEABA",
   "codigoexterno":4707
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JENIPAPO DE MINAS",
   "codigoexterno":632
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JEQUERI",
   "codigoexterno":4709
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JEQUITAI",
   "codigoexterno":4711
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JEQUITIBA",
   "codigoexterno":4713
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JEQUITINHONHA",
   "codigoexterno":4715
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JESUANIA",
   "codigoexterno":4717
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JOAIMA",
   "codigoexterno":4719
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JOANESIA",
   "codigoexterno":4721
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JOAO MONLEVADE",
   "codigoexterno":4723
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JOAO PINHEIRO",
   "codigoexterno":4725
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JOAQUIM FELICIO",
   "codigoexterno":4727
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JORDANIA",
   "codigoexterno":4729
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JOSE GONCALVES DE MINAS",
   "codigoexterno":634
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JOSE RAYDAN",
   "codigoexterno":636
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JOSENOPOLIS",
   "codigoexterno":638
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JUATUBA",
   "codigoexterno":2691
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JUIZ DE FORA",
   "codigoexterno":4733
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JURAMENTO",
   "codigoexterno":4735
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JURUAIA",
   "codigoexterno":4737
},
{
   "siglaestado":"MG",
   "nomemunicipio":"JUVENILIA",
   "codigoexterno":640
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LADAINHA",
   "codigoexterno":4739
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LAGAMAR",
   "codigoexterno":4741
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LAGOA DA PRATA",
   "codigoexterno":4743
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LAGOA DOS PATOS",
   "codigoexterno":4745
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LAGOA DOURADA",
   "codigoexterno":4747
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LAGOA FORMOSA",
   "codigoexterno":4749
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LAGOA GRANDE",
   "codigoexterno":2905
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LAGOA SANTA",
   "codigoexterno":4751
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LAJINHA",
   "codigoexterno":4753
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LAMBARI",
   "codigoexterno":4755
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LAMIM",
   "codigoexterno":4757
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LARANJAL",
   "codigoexterno":4759
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LASSANCE",
   "codigoexterno":4761
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LAVRAS",
   "codigoexterno":4763
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LEANDRO FERREIRA",
   "codigoexterno":4765
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LEME DO PRADO",
   "codigoexterno":642
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LEOPOLDINA",
   "codigoexterno":4767
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LIBERDADE",
   "codigoexterno":4769
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LIMA DUARTE",
   "codigoexterno":4771
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LIMEIRA DO OESTE",
   "codigoexterno":2687
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LONTRA",
   "codigoexterno":2695
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LUISBURGO",
   "codigoexterno":644
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LUISLANDIA",
   "codigoexterno":646
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LUMINARIAS",
   "codigoexterno":4773
},
{
   "siglaestado":"MG",
   "nomemunicipio":"LUZ",
   "codigoexterno":4775
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MACHACALIS",
   "codigoexterno":4777
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MACHADO",
   "codigoexterno":4779
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MADRE DE DEUS DE MINAS",
   "codigoexterno":4781
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MALACACHETA",
   "codigoexterno":4783
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MAMONAS",
   "codigoexterno":2895
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MANGA",
   "codigoexterno":4785
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MANHUACU",
   "codigoexterno":4787
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MANHUMIRIM",
   "codigoexterno":4789
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MANTENA",
   "codigoexterno":4791
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MAR DE ESPANHA",
   "codigoexterno":4795
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MARAVILHAS",
   "codigoexterno":4793
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MARIA DA FE",
   "codigoexterno":4797
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MARIANA",
   "codigoexterno":4799
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MARILAC",
   "codigoexterno":4801
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MARIO CAMPOS",
   "codigoexterno":648
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MARIPA DE MINAS",
   "codigoexterno":4803
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MARLIERIA",
   "codigoexterno":4805
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MARMELOPOLIS",
   "codigoexterno":4807
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MARTINHO CAMPOS",
   "codigoexterno":4809
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MARTINS SOARES",
   "codigoexterno":650
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MATA VERDE",
   "codigoexterno":2659
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MATERLANDIA",
   "codigoexterno":4811
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MATEUS LEME",
   "codigoexterno":4813
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MATHIAS LOBATO",
   "codigoexterno":5431
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MATIAS BARBOSA",
   "codigoexterno":4815
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MATIAS CARDOSO",
   "codigoexterno":2897
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MATIPO",
   "codigoexterno":4817
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MATO VERDE",
   "codigoexterno":4819
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MATOZINHOS",
   "codigoexterno":4821
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MATUTINA",
   "codigoexterno":4823
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MEDEIROS",
   "codigoexterno":4825
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MEDINA",
   "codigoexterno":4827
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MENDES PIMENTEL",
   "codigoexterno":4829
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MERCES",
   "codigoexterno":4831
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MESQUITA",
   "codigoexterno":4833
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MINAS NOVAS",
   "codigoexterno":4835
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MINDURI",
   "codigoexterno":4837
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MIRABELA",
   "codigoexterno":4839
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MIRADOURO",
   "codigoexterno":4841
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MIRAI",
   "codigoexterno":4843
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MIRAVANIA",
   "codigoexterno":652
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MOEDA",
   "codigoexterno":4845
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MOEMA",
   "codigoexterno":4847
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MONJOLOS",
   "codigoexterno":4849
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MONSENHOR PAULO",
   "codigoexterno":4851
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MONTALVANIA",
   "codigoexterno":4853
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MONTE ALEGRE DE MINAS",
   "codigoexterno":4855
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MONTE AZUL",
   "codigoexterno":4857
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MONTE BELO",
   "codigoexterno":4859
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MONTE CARMELO",
   "codigoexterno":4861
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MONTE FORMOSO",
   "codigoexterno":654
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MONTE SANTO DE MINAS",
   "codigoexterno":4863
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MONTE SIAO",
   "codigoexterno":4867
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MONTES CLAROS",
   "codigoexterno":4865
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MONTEZUMA",
   "codigoexterno":2697
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MORADA NOVA DE MINAS",
   "codigoexterno":4869
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MORRO DA GARCA",
   "codigoexterno":4871
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MORRO DO PILAR",
   "codigoexterno":4873
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MUNHOZ",
   "codigoexterno":4875
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MURIAE",
   "codigoexterno":4877
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MUTUM",
   "codigoexterno":4879
},
{
   "siglaestado":"MG",
   "nomemunicipio":"MUZAMBINHO",
   "codigoexterno":4881
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NACIP RAYDAN",
   "codigoexterno":4883
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NANUQUE",
   "codigoexterno":4885
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NAQUE",
   "codigoexterno":656
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NATALANDIA",
   "codigoexterno":658
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NATERCIA",
   "codigoexterno":4887
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NAZARENO",
   "codigoexterno":4889
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NEPOMUCENO",
   "codigoexterno":4891
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NINHEIRA",
   "codigoexterno":660
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NOVA BELEM",
   "codigoexterno":662
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NOVA ERA",
   "codigoexterno":4893
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NOVA LIMA",
   "codigoexterno":4895
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NOVA MODICA",
   "codigoexterno":4897
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NOVA PONTE",
   "codigoexterno":4899
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NOVA PORTEIRINHA",
   "codigoexterno":664
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NOVA RESENDE",
   "codigoexterno":4901
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NOVA SERRANA",
   "codigoexterno":4903
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NOVA UNIAO",
   "codigoexterno":4731
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NOVO CRUZEIRO",
   "codigoexterno":4905
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NOVO ORIENTE DE MINAS",
   "codigoexterno":666
},
{
   "siglaestado":"MG",
   "nomemunicipio":"NOVORIZONTE",
   "codigoexterno":668
},
{
   "siglaestado":"MG",
   "nomemunicipio":"OLARIA",
   "codigoexterno":4907
},
{
   "siglaestado":"MG",
   "nomemunicipio":"OLHOS-D\'AGUA",
   "codigoexterno":670
},
{
   "siglaestado":"MG",
   "nomemunicipio":"OLIMPIO NORONHA",
   "codigoexterno":4909
},
{
   "siglaestado":"MG",
   "nomemunicipio":"OLIVEIRA",
   "codigoexterno":4911
},
{
   "siglaestado":"MG",
   "nomemunicipio":"OLIVEIRA FORTES",
   "codigoexterno":4913
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ONCA DE PITANGUI",
   "codigoexterno":4915
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ORATORIOS",
   "codigoexterno":672
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ORIZANIA",
   "codigoexterno":674
},
{
   "siglaestado":"MG",
   "nomemunicipio":"OURO BRANCO",
   "codigoexterno":4917
},
{
   "siglaestado":"MG",
   "nomemunicipio":"OURO FINO",
   "codigoexterno":4919
},
{
   "siglaestado":"MG",
   "nomemunicipio":"OURO PRETO",
   "codigoexterno":4921
},
{
   "siglaestado":"MG",
   "nomemunicipio":"OURO VERDE DE MINAS",
   "codigoexterno":4923
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PADRE CARVALHO",
   "codigoexterno":676
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PADRE PARAISO",
   "codigoexterno":4925
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PAI PEDRO",
   "codigoexterno":678
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PAINEIRAS",
   "codigoexterno":4927
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PAINS",
   "codigoexterno":4929
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PAIVA",
   "codigoexterno":4931
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PALMA",
   "codigoexterno":4933
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PALMOPOLIS",
   "codigoexterno":2661
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PAPAGAIOS",
   "codigoexterno":4937
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PARA DE MINAS",
   "codigoexterno":4941
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PARACATU",
   "codigoexterno":4939
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PARAGUACU",
   "codigoexterno":4943
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PARAISOPOLIS",
   "codigoexterno":4945
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PARAOPEBA",
   "codigoexterno":4947
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PASSA QUATRO",
   "codigoexterno":4951
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PASSA TEMPO",
   "codigoexterno":4953
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PASSA VINTE",
   "codigoexterno":4955
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PASSABEM",
   "codigoexterno":4949
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PASSOS",
   "codigoexterno":4957
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PATIS",
   "codigoexterno":680
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PATOS DE MINAS",
   "codigoexterno":4959
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PATROCINIO",
   "codigoexterno":4961
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PATROCINIO DO MURIAE",
   "codigoexterno":4963
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PAULA CANDIDO",
   "codigoexterno":4965
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PAULISTAS",
   "codigoexterno":4967
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PAVAO",
   "codigoexterno":4969
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PECANHA",
   "codigoexterno":4971
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PEDRA AZUL",
   "codigoexterno":4973
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PEDRA BONITA",
   "codigoexterno":682
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PEDRA DO ANTA",
   "codigoexterno":4975
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PEDRA DO INDAIA",
   "codigoexterno":4977
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PEDRA DOURADA",
   "codigoexterno":4979
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PEDRALVA",
   "codigoexterno":4981
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PEDRAS DE MARIA DA CRUZ",
   "codigoexterno":2899
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PEDRINOPOLIS",
   "codigoexterno":4983
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PEDRO LEOPOLDO",
   "codigoexterno":4985
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PEDRO TEIXEIRA",
   "codigoexterno":4987
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PEQUERI",
   "codigoexterno":4989
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PEQUI",
   "codigoexterno":4991
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PERDIGAO",
   "codigoexterno":4993
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PERDIZES",
   "codigoexterno":4995
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PERDOES",
   "codigoexterno":4997
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PERIQUITO",
   "codigoexterno":684
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PESCADOR",
   "codigoexterno":4999
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PIAU",
   "codigoexterno":5001
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PIEDADE DE CARATINGA",
   "codigoexterno":686
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PIEDADE DE PONTE NOVA",
   "codigoexterno":5003
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PIEDADE DO RIO GRANDE",
   "codigoexterno":5005
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PIEDADE DOS GERAIS",
   "codigoexterno":5007
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PIMENTA",
   "codigoexterno":5009
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PINGO D\'AGUA",
   "codigoexterno":688
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PINTOPOLIS",
   "codigoexterno":690
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PIRACEMA",
   "codigoexterno":5011
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PIRAJUBA",
   "codigoexterno":5013
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PIRANGA",
   "codigoexterno":5015
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PIRANGUCU",
   "codigoexterno":5017
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PIRANGUINHO",
   "codigoexterno":5019
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PIRAPETINGA",
   "codigoexterno":5021
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PIRAPORA",
   "codigoexterno":5023
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PIRAUBA",
   "codigoexterno":5025
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PITANGUI",
   "codigoexterno":5027
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PIUMHI",
   "codigoexterno":5029
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PLANURA",
   "codigoexterno":5031
},
{
   "siglaestado":"MG",
   "nomemunicipio":"POCO FUNDO",
   "codigoexterno":5033
},
{
   "siglaestado":"MG",
   "nomemunicipio":"POCOS DE CALDAS",
   "codigoexterno":5035
},
{
   "siglaestado":"MG",
   "nomemunicipio":"POCRANE",
   "codigoexterno":5037
},
{
   "siglaestado":"MG",
   "nomemunicipio":"POMPEU",
   "codigoexterno":5039
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PONTE NOVA",
   "codigoexterno":5041
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PONTO CHIQUE",
   "codigoexterno":692
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PONTO DOS VOLANTES",
   "codigoexterno":694
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PORTEIRINHA",
   "codigoexterno":5043
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PORTO FIRME",
   "codigoexterno":5045
},
{
   "siglaestado":"MG",
   "nomemunicipio":"POTE",
   "codigoexterno":5047
},
{
   "siglaestado":"MG",
   "nomemunicipio":"POUSO ALEGRE",
   "codigoexterno":5049
},
{
   "siglaestado":"MG",
   "nomemunicipio":"POUSO ALTO",
   "codigoexterno":5051
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PRADOS",
   "codigoexterno":5053
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PRATA",
   "codigoexterno":5055
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PRATAPOLIS",
   "codigoexterno":5057
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PRATINHA",
   "codigoexterno":5059
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PRESIDENTE BERNARDES",
   "codigoexterno":5061
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PRESIDENTE JUSCELINO",
   "codigoexterno":5063
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PRESIDENTE KUBITSCHEK",
   "codigoexterno":5065
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PRESIDENTE OLEGARIO",
   "codigoexterno":5067
},
{
   "siglaestado":"MG",
   "nomemunicipio":"PRUDENTE DE MORAIS",
   "codigoexterno":5071
},
{
   "siglaestado":"MG",
   "nomemunicipio":"QUARTEL GERAL",
   "codigoexterno":5073
},
{
   "siglaestado":"MG",
   "nomemunicipio":"QUELUZITA",
   "codigoexterno":5075
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RAPOSOS",
   "codigoexterno":5077
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RAUL SOARES",
   "codigoexterno":5079
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RECREIO",
   "codigoexterno":5081
},
{
   "siglaestado":"MG",
   "nomemunicipio":"REDUTO",
   "codigoexterno":696
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RESENDE COSTA",
   "codigoexterno":5083
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RESPLENDOR",
   "codigoexterno":5085
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RESSAQUINHA",
   "codigoexterno":5087
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RIACHINHO",
   "codigoexterno":2901
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RIACHO DOS MACHADOS",
   "codigoexterno":5089
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RIBEIRAO DAS NEVES",
   "codigoexterno":5091
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RIBEIRAO VERMELHO",
   "codigoexterno":5093
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RIO ACIMA",
   "codigoexterno":5095
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RIO CASCA",
   "codigoexterno":5097
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RIO DO PRADO",
   "codigoexterno":5101
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RIO DOCE",
   "codigoexterno":5099
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RIO ESPERA",
   "codigoexterno":5103
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RIO MANSO",
   "codigoexterno":5105
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RIO NOVO",
   "codigoexterno":5107
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RIO PARANAIBA",
   "codigoexterno":5109
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RIO PARDO DE MINAS",
   "codigoexterno":5111
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RIO PIRACICABA",
   "codigoexterno":5113
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RIO POMBA",
   "codigoexterno":5115
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RIO PRETO",
   "codigoexterno":5117
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RIO VERMELHO",
   "codigoexterno":5119
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RITAPOLIS",
   "codigoexterno":5121
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ROCHEDO DE MINAS",
   "codigoexterno":5123
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RODEIRO",
   "codigoexterno":5125
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ROMARIA",
   "codigoexterno":5127
},
{
   "siglaestado":"MG",
   "nomemunicipio":"ROSARIO DA LIMEIRA",
   "codigoexterno":698
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RUBELITA",
   "codigoexterno":5129
},
{
   "siglaestado":"MG",
   "nomemunicipio":"RUBIM",
   "codigoexterno":5131
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SABARA",
   "codigoexterno":5133
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SABINOPOLIS",
   "codigoexterno":5135
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SACRAMENTO",
   "codigoexterno":5137
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SALINAS",
   "codigoexterno":5139
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SALTO DA DIVISA",
   "codigoexterno":5141
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA BARBARA",
   "codigoexterno":5143
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA BARBARA DO LESTE",
   "codigoexterno":2667
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA BARBARA DO MONTE VERDE",
   "codigoexterno":700
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA BARBARA DO TUGURIO",
   "codigoexterno":5145
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA CRUZ DE MINAS",
   "codigoexterno":702
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA CRUZ DE SALINAS",
   "codigoexterno":704
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA CRUZ DO ESCALVADO",
   "codigoexterno":5147
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA EFIGENIA DE MINAS",
   "codigoexterno":5149
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA FE DE MINAS",
   "codigoexterno":5151
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA HELENA DE MINAS",
   "codigoexterno":706
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA JULIANA",
   "codigoexterno":5153
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA LUZIA",
   "codigoexterno":5155
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA MARGARIDA",
   "codigoexterno":5157
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA MARIA DE ITABIRA",
   "codigoexterno":5159
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA MARIA DO SALTO",
   "codigoexterno":5161
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA MARIA DO SUACUI",
   "codigoexterno":5163
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA RITA DE CALDAS",
   "codigoexterno":5183
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA RITA DE JACUTINGA",
   "codigoexterno":5185
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA RITA DE MINAS",
   "codigoexterno":2669
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA RITA DO IBITIPOCA",
   "codigoexterno":5187
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA RITA DO ITUETO",
   "codigoexterno":5189
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA RITA DO SAPUCAI",
   "codigoexterno":5191
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA ROSA DA SERRA",
   "codigoexterno":5193
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTA VITORIA",
   "codigoexterno":5195
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTANA DA VARGEM",
   "codigoexterno":5165
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTANA DE CATAGUASES",
   "codigoexterno":5167
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTANA DE PIRAPAMA",
   "codigoexterno":5169
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTANA DO DESERTO",
   "codigoexterno":5171
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTANA DO GARAMBEU",
   "codigoexterno":5173
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTANA DO JACARE",
   "codigoexterno":5175
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTANA DO MANHUACU",
   "codigoexterno":5177
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTANA DO PARAISO",
   "codigoexterno":2673
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTANA DO RIACHO",
   "codigoexterno":5179
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTANA DOS MONTES",
   "codigoexterno":5181
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTO ANTONIO DO AMPARO",
   "codigoexterno":5197
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTO ANTONIO DO AVENTUREIRO",
   "codigoexterno":5199
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTO ANTONIO DO GRAMA",
   "codigoexterno":5201
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTO ANTONIO DO ITAMBE",
   "codigoexterno":5203
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTO ANTONIO DO JACINTO",
   "codigoexterno":5205
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTO ANTONIO DO MONTE",
   "codigoexterno":5207
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTO ANTONIO DO RETIRO",
   "codigoexterno":708
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTO ANTONIO DO RIO ABAIXO",
   "codigoexterno":5209
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTO HIPOLITO",
   "codigoexterno":5211
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SANTOS DUMONT",
   "codigoexterno":5213
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO BENTO ABADE",
   "codigoexterno":5215
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO BRAS DO SUACUI",
   "codigoexterno":5217
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO DOMINGOS DAS DORES",
   "codigoexterno":710
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO DOMINGOS DO PRATA",
   "codigoexterno":5219
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO FELIX DE MINAS",
   "codigoexterno":712
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO FRANCISCO",
   "codigoexterno":5221
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO FRANCISCO DE PAULA",
   "codigoexterno":5223
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO FRANCISCO DE SALES",
   "codigoexterno":5225
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO FRANCISCO DO GLORIA",
   "codigoexterno":5227
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO GERALDO",
   "codigoexterno":5229
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO GERALDO DA PIEDADE",
   "codigoexterno":5231
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO GERALDO DO BAIXIO",
   "codigoexterno":714
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO GONCALO DO ABAETE",
   "codigoexterno":5233
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO GONCALO DO PARA",
   "codigoexterno":5235
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO GONCALO DO RIO ABAIXO",
   "codigoexterno":5237
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO GONCALO DO RIO PRETO",
   "codigoexterno":4509
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO GONCALO DO SAPUCAI",
   "codigoexterno":5239
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO GOTARDO",
   "codigoexterno":5241
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOAO BATISTA DO GLORIA",
   "codigoexterno":5243
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOAO DA LAGOA",
   "codigoexterno":716
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOAO DA MATA",
   "codigoexterno":5245
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOAO DA PONTE",
   "codigoexterno":5247
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOAO DAS MISSOES",
   "codigoexterno":718
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOAO DEL REI",
   "codigoexterno":5249
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOAO DO MANHUACU",
   "codigoexterno":2677
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOAO DO MANTENINHA",
   "codigoexterno":2679
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOAO DO ORIENTE",
   "codigoexterno":5251
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOAO DO PACUI",
   "codigoexterno":720
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOAO DO PARAISO",
   "codigoexterno":5253
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOAO EVANGELISTA",
   "codigoexterno":5255
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOAO NEPOMUCENO",
   "codigoexterno":5257
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOAQUIM DE BICAS",
   "codigoexterno":722
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOSE DA BARRA",
   "codigoexterno":724
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOSE DA LAPA",
   "codigoexterno":2649
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOSE DA SAFIRA",
   "codigoexterno":5259
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOSE DA VARGINHA",
   "codigoexterno":5261
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOSE DO ALEGRE",
   "codigoexterno":5263
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOSE DO DIVINO",
   "codigoexterno":5265
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOSE DO GOIABAL",
   "codigoexterno":5267
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOSE DO JACURI",
   "codigoexterno":5269
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO JOSE DO MANTIMENTO",
   "codigoexterno":5271
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO LOURENCO",
   "codigoexterno":5273
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO MIGUEL DO ANTA",
   "codigoexterno":5275
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO PEDRO DA UNIAO",
   "codigoexterno":5277
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO PEDRO DO SUACUI",
   "codigoexterno":5281
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO PEDRO DOS FERROS",
   "codigoexterno":5279
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO ROMAO",
   "codigoexterno":5283
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO ROQUE DE MINAS",
   "codigoexterno":5285
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO SEBASTIAO DA BELA VISTA",
   "codigoexterno":5287
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO SEBASTIAO DA VARGEM ALEGRE",
   "codigoexterno":726
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO SEBASTIAO DO ANTA",
   "codigoexterno":728
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO SEBASTIAO DO MARANHAO",
   "codigoexterno":5289
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO SEBASTIAO DO OESTE",
   "codigoexterno":5291
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO SEBASTIAO DO PARAISO",
   "codigoexterno":5293
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO SEBASTIAO DO RIO PRETO",
   "codigoexterno":5295
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO SEBASTIAO DO RIO VERDE",
   "codigoexterno":5297
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO TIAGO",
   "codigoexterno":5299
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO TOMAS DE AQUINO",
   "codigoexterno":5301
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO TOME DAS LETRAS",
   "codigoexterno":5303
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAO VICENTE DE MINAS",
   "codigoexterno":5305
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SAPUCAI-MIRIM",
   "codigoexterno":5307
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SARDOA",
   "codigoexterno":5309
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SARZEDO",
   "codigoexterno":730
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SEM-PEIXE",
   "codigoexterno":734
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SENADOR AMARAL",
   "codigoexterno":2689
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SENADOR CORTES",
   "codigoexterno":5311
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SENADOR FIRMINO",
   "codigoexterno":5313
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SENADOR JOSE BENTO",
   "codigoexterno":5315
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SENADOR MODESTINO GONCALVES",
   "codigoexterno":5317
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SENHORA DE OLIVEIRA",
   "codigoexterno":5319
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SENHORA DO PORTO",
   "codigoexterno":5321
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SENHORA DOS REMEDIOS",
   "codigoexterno":5323
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SERICITA",
   "codigoexterno":5325
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SERITINGA",
   "codigoexterno":5327
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SERRA AZUL DE MINAS",
   "codigoexterno":5329
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SERRA DA SAUDADE",
   "codigoexterno":5331
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SERRA DO SALITRE",
   "codigoexterno":5335
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SERRA DOS AIMORES",
   "codigoexterno":5333
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SERRANIA",
   "codigoexterno":5337
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SERRANOPOLIS DE MINAS",
   "codigoexterno":736
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SERRANOS",
   "codigoexterno":5339
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SERRO",
   "codigoexterno":5341
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SETE LAGOAS",
   "codigoexterno":5343
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SETUBINHA",
   "codigoexterno":732
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SILVEIRANIA",
   "codigoexterno":5345
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SILVIANOPOLIS",
   "codigoexterno":5347
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SIMAO PEREIRA",
   "codigoexterno":5349
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SIMONESIA",
   "codigoexterno":5351
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SOBRALIA",
   "codigoexterno":5353
},
{
   "siglaestado":"MG",
   "nomemunicipio":"SOLEDADE DE MINAS",
   "codigoexterno":5355
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TABULEIRO",
   "codigoexterno":5357
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TAIOBEIRAS",
   "codigoexterno":5359
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TAPARUBA",
   "codigoexterno":738
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TAPIRA",
   "codigoexterno":5361
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TAPIRAI",
   "codigoexterno":5363
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TAQUARACU DE MINAS",
   "codigoexterno":5365
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TARUMIRIM",
   "codigoexterno":5367
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TEIXEIRAS",
   "codigoexterno":5369
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TEOFILO OTONI",
   "codigoexterno":5371
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TIMOTEO",
   "codigoexterno":5373
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TIRADENTES",
   "codigoexterno":5375
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TIROS",
   "codigoexterno":5377
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TOCANTINS",
   "codigoexterno":5379
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TOCOS DO MOJI",
   "codigoexterno":740
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TOLEDO",
   "codigoexterno":5381
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TOMBOS",
   "codigoexterno":5383
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TRES CORACOES",
   "codigoexterno":5385
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TRES MARIAS",
   "codigoexterno":4115
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TRES PONTAS",
   "codigoexterno":5387
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TUMIRITINGA",
   "codigoexterno":5389
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TUPACIGUARA",
   "codigoexterno":5391
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TURMALINA",
   "codigoexterno":5393
},
{
   "siglaestado":"MG",
   "nomemunicipio":"TURVOLANDIA",
   "codigoexterno":5395
},
{
   "siglaestado":"MG",
   "nomemunicipio":"UBA",
   "codigoexterno":5397
},
{
   "siglaestado":"MG",
   "nomemunicipio":"UBAI",
   "codigoexterno":5399
},
{
   "siglaestado":"MG",
   "nomemunicipio":"UBAPORANGA",
   "codigoexterno":2671
},
{
   "siglaestado":"MG",
   "nomemunicipio":"UBERABA",
   "codigoexterno":5401
},
{
   "siglaestado":"MG",
   "nomemunicipio":"UBERLANDIA",
   "codigoexterno":5403
},
{
   "siglaestado":"MG",
   "nomemunicipio":"UMBURATIBA",
   "codigoexterno":5405
},
{
   "siglaestado":"MG",
   "nomemunicipio":"UNAI",
   "codigoexterno":5407
},
{
   "siglaestado":"MG",
   "nomemunicipio":"UNIAO DE MINAS",
   "codigoexterno":742
},
{
   "siglaestado":"MG",
   "nomemunicipio":"URUANA DE MINAS",
   "codigoexterno":744
},
{
   "siglaestado":"MG",
   "nomemunicipio":"URUCANIA",
   "codigoexterno":5409
},
{
   "siglaestado":"MG",
   "nomemunicipio":"URUCUIA",
   "codigoexterno":2699
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VARGEM ALEGRE",
   "codigoexterno":746
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VARGEM BONITA",
   "codigoexterno":5411
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VARGEM GRANDE DO RIO PARDO",
   "codigoexterno":748
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VARGINHA",
   "codigoexterno":5413
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VARJAO DE MINAS",
   "codigoexterno":750
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VARZEA DA PALMA",
   "codigoexterno":5415
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VARZELANDIA",
   "codigoexterno":5417
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VAZANTE",
   "codigoexterno":5419
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VERDELANDIA",
   "codigoexterno":752
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VEREDINHA",
   "codigoexterno":754
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VERISSIMO",
   "codigoexterno":5423
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VERMELHO NOVO",
   "codigoexterno":756
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VESPASIANO",
   "codigoexterno":5425
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VICOSA",
   "codigoexterno":5427
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VIEIRAS",
   "codigoexterno":5429
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VIRGEM DA LAPA",
   "codigoexterno":5433
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VIRGINIA",
   "codigoexterno":5435
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VIRGINOPOLIS",
   "codigoexterno":5437
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VIRGOLANDIA",
   "codigoexterno":5439
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VISCONDE DO RIO BRANCO",
   "codigoexterno":5441
},
{
   "siglaestado":"MG",
   "nomemunicipio":"VOLTA GRANDE",
   "codigoexterno":5443
},
{
   "siglaestado":"MG",
   "nomemunicipio":"WENCESLAU BRAZ",
   "codigoexterno":5421
},
{
   "siglaestado":"MS",
   "nomemunicipio":"AGUA CLARA",
   "codigoexterno":9003
},
{
   "siglaestado":"MS",
   "nomemunicipio":"ALCINOPOLIS",
   "codigoexterno":141
},
{
   "siglaestado":"MS",
   "nomemunicipio":"AMAMBAI",
   "codigoexterno":9011
},
{
   "siglaestado":"MS",
   "nomemunicipio":"ANASTACIO",
   "codigoexterno":9013
},
{
   "siglaestado":"MS",
   "nomemunicipio":"ANAURILANDIA",
   "codigoexterno":9015
},
{
   "siglaestado":"MS",
   "nomemunicipio":"ANGELICA",
   "codigoexterno":9169
},
{
   "siglaestado":"MS",
   "nomemunicipio":"ANTONIO JOAO",
   "codigoexterno":9017
},
{
   "siglaestado":"MS",
   "nomemunicipio":"APARECIDA DO TABOADO",
   "codigoexterno":9019
},
{
   "siglaestado":"MS",
   "nomemunicipio":"AQUIDAUANA",
   "codigoexterno":9021
},
{
   "siglaestado":"MS",
   "nomemunicipio":"ARAL MOREIRA",
   "codigoexterno":9171
},
{
   "siglaestado":"MS",
   "nomemunicipio":"BANDEIRANTES",
   "codigoexterno":9029
},
{
   "siglaestado":"MS",
   "nomemunicipio":"BATAGUASSU",
   "codigoexterno":9037
},
{
   "siglaestado":"MS",
   "nomemunicipio":"BATAYPORA",
   "codigoexterno":9039
},
{
   "siglaestado":"MS",
   "nomemunicipio":"BELA VISTA",
   "codigoexterno":9041
},
{
   "siglaestado":"MS",
   "nomemunicipio":"BODOQUENA",
   "codigoexterno":9801
},
{
   "siglaestado":"MS",
   "nomemunicipio":"BONITO",
   "codigoexterno":9043
},
{
   "siglaestado":"MS",
   "nomemunicipio":"BRASILANDIA",
   "codigoexterno":9045
},
{
   "siglaestado":"MS",
   "nomemunicipio":"CAARAPO",
   "codigoexterno":9055
},
{
   "siglaestado":"MS",
   "nomemunicipio":"CAMAPUA",
   "codigoexterno":9049
},
{
   "siglaestado":"MS",
   "nomemunicipio":"CAMPO GRANDE",
   "codigoexterno":9051
},
{
   "siglaestado":"MS",
   "nomemunicipio":"CARACOL",
   "codigoexterno":9053
},
{
   "siglaestado":"MS",
   "nomemunicipio":"CASSILANDIA",
   "codigoexterno":9057
},
{
   "siglaestado":"MS",
   "nomemunicipio":"CHAPADAO DO SUL",
   "codigoexterno":9787
},
{
   "siglaestado":"MS",
   "nomemunicipio":"CORGUINHO",
   "codigoexterno":9061
},
{
   "siglaestado":"MS",
   "nomemunicipio":"CORONEL SAPUCAIA",
   "codigoexterno":9997
},
{
   "siglaestado":"MS",
   "nomemunicipio":"CORUMBA",
   "codigoexterno":9063
},
{
   "siglaestado":"MS",
   "nomemunicipio":"COSTA RICA",
   "codigoexterno":9803
},
{
   "siglaestado":"MS",
   "nomemunicipio":"COXIM",
   "codigoexterno":9065
},
{
   "siglaestado":"MS",
   "nomemunicipio":"DEODAPOLIS",
   "codigoexterno":9175
},
{
   "siglaestado":"MS",
   "nomemunicipio":"DOIS IRMAOS DO BURITI",
   "codigoexterno":9793
},
{
   "siglaestado":"MS",
   "nomemunicipio":"DOURADINA",
   "codigoexterno":9805
},
{
   "siglaestado":"MS",
   "nomemunicipio":"DOURADOS",
   "codigoexterno":9073
},
{
   "siglaestado":"MS",
   "nomemunicipio":"ELDORADO",
   "codigoexterno":9173
},
{
   "siglaestado":"MS",
   "nomemunicipio":"FATIMA DO SUL",
   "codigoexterno":9075
},
{
   "siglaestado":"MS",
   "nomemunicipio":"FIGUEIRAO",
   "codigoexterno":1178
},
{
   "siglaestado":"MS",
   "nomemunicipio":"GLORIA DE DOURADOS",
   "codigoexterno":9079
},
{
   "siglaestado":"MS",
   "nomemunicipio":"GUIA LOPES DA LAGUNA",
   "codigoexterno":9081
},
{
   "siglaestado":"MS",
   "nomemunicipio":"IGUATEMI",
   "codigoexterno":9085
},
{
   "siglaestado":"MS",
   "nomemunicipio":"INOCENCIA",
   "codigoexterno":9087
},
{
   "siglaestado":"MS",
   "nomemunicipio":"ITAPORA",
   "codigoexterno":9089
},
{
   "siglaestado":"MS",
   "nomemunicipio":"ITAQUIRAI",
   "codigoexterno":9807
},
{
   "siglaestado":"MS",
   "nomemunicipio":"IVINHEMA",
   "codigoexterno":9093
},
{
   "siglaestado":"MS",
   "nomemunicipio":"JAPORA",
   "codigoexterno":161
},
{
   "siglaestado":"MS",
   "nomemunicipio":"JARAGUARI",
   "codigoexterno":9097
},
{
   "siglaestado":"MS",
   "nomemunicipio":"JARDIM",
   "codigoexterno":9099
},
{
   "siglaestado":"MS",
   "nomemunicipio":"JATEI",
   "codigoexterno":9101
},
{
   "siglaestado":"MS",
   "nomemunicipio":"JUTI",
   "codigoexterno":9923
},
{
   "siglaestado":"MS",
   "nomemunicipio":"LADARIO",
   "codigoexterno":9103
},
{
   "siglaestado":"MS",
   "nomemunicipio":"LAGUNA CARAPA",
   "codigoexterno":163
},
{
   "siglaestado":"MS",
   "nomemunicipio":"MARACAJU",
   "codigoexterno":9107
},
{
   "siglaestado":"MS",
   "nomemunicipio":"MIRANDA",
   "codigoexterno":9111
},
{
   "siglaestado":"MS",
   "nomemunicipio":"MUNDO NOVO",
   "codigoexterno":9179
},
{
   "siglaestado":"MS",
   "nomemunicipio":"NAVIRAI",
   "codigoexterno":9113
},
{
   "siglaestado":"MS",
   "nomemunicipio":"NIOAQUE",
   "codigoexterno":9115
},
{
   "siglaestado":"MS",
   "nomemunicipio":"NOVA ALVORADA DO SUL",
   "codigoexterno":143
},
{
   "siglaestado":"MS",
   "nomemunicipio":"NOVA ANDRADINA",
   "codigoexterno":9123
},
{
   "siglaestado":"MS",
   "nomemunicipio":"NOVO HORIZONTE DO SUL",
   "codigoexterno":159
},
{
   "siglaestado":"MS",
   "nomemunicipio":"PARAISO DAS AGUAS",
   "codigoexterno":1196
},
{
   "siglaestado":"MS",
   "nomemunicipio":"PARANAIBA",
   "codigoexterno":9125
},
{
   "siglaestado":"MS",
   "nomemunicipio":"PARANHOS",
   "codigoexterno":9739
},
{
   "siglaestado":"MS",
   "nomemunicipio":"PEDRO GOMES",
   "codigoexterno":9127
},
{
   "siglaestado":"MS",
   "nomemunicipio":"PONTA PORA",
   "codigoexterno":9131
},
{
   "siglaestado":"MS",
   "nomemunicipio":"PORTO MURTINHO",
   "codigoexterno":9137
},
{
   "siglaestado":"MS",
   "nomemunicipio":"RIBAS DO RIO PARDO",
   "codigoexterno":9141
},
{
   "siglaestado":"MS",
   "nomemunicipio":"RIO BRILHANTE",
   "codigoexterno":9143
},
{
   "siglaestado":"MS",
   "nomemunicipio":"RIO NEGRO",
   "codigoexterno":9145
},
{
   "siglaestado":"MS",
   "nomemunicipio":"RIO VERDE DE MATO GROSSO",
   "codigoexterno":9147
},
{
   "siglaestado":"MS",
   "nomemunicipio":"ROCHEDO",
   "codigoexterno":9149
},
{
   "siglaestado":"MS",
   "nomemunicipio":"SANTA RITA DO PARDO",
   "codigoexterno":9745
},
{
   "siglaestado":"MS",
   "nomemunicipio":"SAO GABRIEL DO OESTE",
   "codigoexterno":9809
},
{
   "siglaestado":"MS",
   "nomemunicipio":"SELVIRIA",
   "codigoexterno":9811
},
{
   "siglaestado":"MS",
   "nomemunicipio":"SETE QUEDAS",
   "codigoexterno":9813
},
{
   "siglaestado":"MS",
   "nomemunicipio":"SIDROLANDIA",
   "codigoexterno":9157
},
{
   "siglaestado":"MS",
   "nomemunicipio":"SONORA",
   "codigoexterno":9757
},
{
   "siglaestado":"MS",
   "nomemunicipio":"TACURU",
   "codigoexterno":9815
},
{
   "siglaestado":"MS",
   "nomemunicipio":"TAQUARUSSU",
   "codigoexterno":9817
},
{
   "siglaestado":"MS",
   "nomemunicipio":"TERENOS",
   "codigoexterno":9159
},
{
   "siglaestado":"MS",
   "nomemunicipio":"TRES LAGOAS",
   "codigoexterno":9165
},
{
   "siglaestado":"MS",
   "nomemunicipio":"VICENTINA",
   "codigoexterno":9187
},
{
   "siglaestado":"MT",
   "nomemunicipio":"ACORIZAL",
   "codigoexterno":9001
},
{
   "siglaestado":"MT",
   "nomemunicipio":"AGUA BOA",
   "codigoexterno":9191
},
{
   "siglaestado":"MT",
   "nomemunicipio":"ALTA FLORESTA",
   "codigoexterno":8987
},
{
   "siglaestado":"MT",
   "nomemunicipio":"ALTO ARAGUAIA",
   "codigoexterno":9005
},
{
   "siglaestado":"MT",
   "nomemunicipio":"ALTO BOA VISTA",
   "codigoexterno":127
},
{
   "siglaestado":"MT",
   "nomemunicipio":"ALTO GARCAS",
   "codigoexterno":9007
},
{
   "siglaestado":"MT",
   "nomemunicipio":"ALTO PARAGUAI",
   "codigoexterno":9009
},
{
   "siglaestado":"MT",
   "nomemunicipio":"ALTO TAQUARI",
   "codigoexterno":9911
},
{
   "siglaestado":"MT",
   "nomemunicipio":"APIACAS",
   "codigoexterno":9773
},
{
   "siglaestado":"MT",
   "nomemunicipio":"ARAGUAIANA",
   "codigoexterno":9869
},
{
   "siglaestado":"MT",
   "nomemunicipio":"ARAGUAINHA",
   "codigoexterno":9023
},
{
   "siglaestado":"MT",
   "nomemunicipio":"ARAPUTANGA",
   "codigoexterno":8989
},
{
   "siglaestado":"MT",
   "nomemunicipio":"ARENAPOLIS",
   "codigoexterno":9025
},
{
   "siglaestado":"MT",
   "nomemunicipio":"ARIPUANA",
   "codigoexterno":9027
},
{
   "siglaestado":"MT",
   "nomemunicipio":"BARAO DE MELGACO",
   "codigoexterno":9031
},
{
   "siglaestado":"MT",
   "nomemunicipio":"BARRA DO BUGRES",
   "codigoexterno":9033
},
{
   "siglaestado":"MT",
   "nomemunicipio":"BARRA DO GARCAS",
   "codigoexterno":9035
},
{
   "siglaestado":"MT",
   "nomemunicipio":"BOA ESPERANCA DO NORTE",
   "codigoexterno":1182
},
{
   "siglaestado":"MT",
   "nomemunicipio":"BOM JESUS DO ARAGUAIA",
   "codigoexterno":1078
},
{
   "siglaestado":"MT",
   "nomemunicipio":"BRASNORTE",
   "codigoexterno":9873
},
{
   "siglaestado":"MT",
   "nomemunicipio":"CACERES",
   "codigoexterno":9047
},
{
   "siglaestado":"MT",
   "nomemunicipio":"CAMPINAPOLIS",
   "codigoexterno":9863
},
{
   "siglaestado":"MT",
   "nomemunicipio":"CAMPO NOVO DO PARECIS",
   "codigoexterno":9777
},
{
   "siglaestado":"MT",
   "nomemunicipio":"CAMPO VERDE",
   "codigoexterno":9779
},
{
   "siglaestado":"MT",
   "nomemunicipio":"CAMPOS DE JULIO",
   "codigoexterno":1032
},
{
   "siglaestado":"MT",
   "nomemunicipio":"CANABRAVA DO NORTE",
   "codigoexterno":129
},
{
   "siglaestado":"MT",
   "nomemunicipio":"CANARANA",
   "codigoexterno":9193
},
{
   "siglaestado":"MT",
   "nomemunicipio":"CARLINDA",
   "codigoexterno":1034
},
{
   "siglaestado":"MT",
   "nomemunicipio":"CASTANHEIRA",
   "codigoexterno":9783
},
{
   "siglaestado":"MT",
   "nomemunicipio":"CHAPADA DOS GUIMARAES",
   "codigoexterno":9059
},
{
   "siglaestado":"MT",
   "nomemunicipio":"CLAUDIA",
   "codigoexterno":9789
},
{
   "siglaestado":"MT",
   "nomemunicipio":"COCALINHO",
   "codigoexterno":9865
},
{
   "siglaestado":"MT",
   "nomemunicipio":"COLIDER",
   "codigoexterno":8979
},
{
   "siglaestado":"MT",
   "nomemunicipio":"COLNIZA",
   "codigoexterno":1080
},
{
   "siglaestado":"MT",
   "nomemunicipio":"COMODORO",
   "codigoexterno":9883
},
{
   "siglaestado":"MT",
   "nomemunicipio":"CONFRESA",
   "codigoexterno":131
},
{
   "siglaestado":"MT",
   "nomemunicipio":"CONQUISTA D\'OESTE",
   "codigoexterno":1082
},
{
   "siglaestado":"MT",
   "nomemunicipio":"COTRIGUACU",
   "codigoexterno":89
},
{
   "siglaestado":"MT",
   "nomemunicipio":"CUIABA",
   "codigoexterno":9067
},
{
   "siglaestado":"MT",
   "nomemunicipio":"CURVEL?NDIA",
   "codigoexterno":1084
},
{
   "siglaestado":"MT",
   "nomemunicipio":"DENISE",
   "codigoexterno":9833
},
{
   "siglaestado":"MT",
   "nomemunicipio":"DIAMANTINO",
   "codigoexterno":9069
},
{
   "siglaestado":"MT",
   "nomemunicipio":"DOM AQUINO",
   "codigoexterno":9071
},
{
   "siglaestado":"MT",
   "nomemunicipio":"FELIZ NATAL",
   "codigoexterno":1036
},
{
   "siglaestado":"MT",
   "nomemunicipio":"FIGUEIROPOLIS D\'OESTE",
   "codigoexterno":9881
},
{
   "siglaestado":"MT",
   "nomemunicipio":"GAUCHA DO NORTE",
   "codigoexterno":1038
},
{
   "siglaestado":"MT",
   "nomemunicipio":"GENERAL CARNEIRO",
   "codigoexterno":9077
},
{
   "siglaestado":"MT",
   "nomemunicipio":"GLORIA D\'OESTE",
   "codigoexterno":135
},
{
   "siglaestado":"MT",
   "nomemunicipio":"GUARANTA DO NORTE",
   "codigoexterno":9887
},
{
   "siglaestado":"MT",
   "nomemunicipio":"GUIRATINGA",
   "codigoexterno":9083
},
{
   "siglaestado":"MT",
   "nomemunicipio":"INDIAVAI",
   "codigoexterno":9877
},
{
   "siglaestado":"MT",
   "nomemunicipio":"IPIRANGA DO NORTE",
   "codigoexterno":1184
},
{
   "siglaestado":"MT",
   "nomemunicipio":"ITANHANGA",
   "codigoexterno":1186
},
{
   "siglaestado":"MT",
   "nomemunicipio":"ITAUBA",
   "codigoexterno":9901
},
{
   "siglaestado":"MT",
   "nomemunicipio":"ITIQUIRA",
   "codigoexterno":9091
},
{
   "siglaestado":"MT",
   "nomemunicipio":"JACIARA",
   "codigoexterno":9095
},
{
   "siglaestado":"MT",
   "nomemunicipio":"JANGADA",
   "codigoexterno":9861
},
{
   "siglaestado":"MT",
   "nomemunicipio":"JAURU",
   "codigoexterno":8991
},
{
   "siglaestado":"MT",
   "nomemunicipio":"JUARA",
   "codigoexterno":9819
},
{
   "siglaestado":"MT",
   "nomemunicipio":"JUINA",
   "codigoexterno":9831
},
{
   "siglaestado":"MT",
   "nomemunicipio":"JURUENA",
   "codigoexterno":9921
},
{
   "siglaestado":"MT",
   "nomemunicipio":"JUSCIMEIRA",
   "codigoexterno":9189
},
{
   "siglaestado":"MT",
   "nomemunicipio":"LAMBARI D\'OESTE",
   "codigoexterno":137
},
{
   "siglaestado":"MT",
   "nomemunicipio":"LUCAS DO RIO VERDE",
   "codigoexterno":9925
},
{
   "siglaestado":"MT",
   "nomemunicipio":"LUCIARA",
   "codigoexterno":9105
},
{
   "siglaestado":"MT",
   "nomemunicipio":"MARCELANDIA",
   "codigoexterno":9899
},
{
   "siglaestado":"MT",
   "nomemunicipio":"MATUPA",
   "codigoexterno":9929
},
{
   "siglaestado":"MT",
   "nomemunicipio":"MIRASSOL D\'OESTE",
   "codigoexterno":9177
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOBRES",
   "codigoexterno":9117
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NORTELANDIA",
   "codigoexterno":9119
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOSSA SENHORA DO LIVRAMENTO",
   "codigoexterno":9121
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOVA BANDEIRANTES",
   "codigoexterno":117
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOVA BRASILANDIA",
   "codigoexterno":8981
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOVA CANAA DO NORTE",
   "codigoexterno":9889
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOVA GUARITA",
   "codigoexterno":121
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOVA LACERDA",
   "codigoexterno":1040
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOVA MARILANDIA",
   "codigoexterno":103
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOVA MARINGA",
   "codigoexterno":111
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOVA MONTE VERDE",
   "codigoexterno":119
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOVA MUTUM",
   "codigoexterno":9937
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOVA NAZAR?",
   "codigoexterno":1086
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOVA OLIMPIA",
   "codigoexterno":9893
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOVA SANTA HELENA",
   "codigoexterno":1088
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOVA UBIRATA",
   "codigoexterno":1042
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOVA XAVANTINA",
   "codigoexterno":9195
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOVO HORIZONTE DO NORTE",
   "codigoexterno":9903
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOVO MUNDO",
   "codigoexterno":1044
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOVO SANTO ANT?NIO",
   "codigoexterno":1090
},
{
   "siglaestado":"MT",
   "nomemunicipio":"NOVO SAO JOAQUIM",
   "codigoexterno":9867
},
{
   "siglaestado":"MT",
   "nomemunicipio":"PARANAITA",
   "codigoexterno":9885
},
{
   "siglaestado":"MT",
   "nomemunicipio":"PARANATINGA",
   "codigoexterno":8983
},
{
   "siglaestado":"MT",
   "nomemunicipio":"PEDRA PRETA",
   "codigoexterno":9181
},
{
   "siglaestado":"MT",
   "nomemunicipio":"PEIXOTO DE AZEVEDO",
   "codigoexterno":9891
},
{
   "siglaestado":"MT",
   "nomemunicipio":"PLANALTO DA SERRA",
   "codigoexterno":91
},
{
   "siglaestado":"MT",
   "nomemunicipio":"POCONE",
   "codigoexterno":9129
},
{
   "siglaestado":"MT",
   "nomemunicipio":"PONTAL DO ARAGUAIA",
   "codigoexterno":95
},
{
   "siglaestado":"MT",
   "nomemunicipio":"PONTE BRANCA",
   "codigoexterno":9133
},
{
   "siglaestado":"MT",
   "nomemunicipio":"PONTES E LACERDA",
   "codigoexterno":8999
},
{
   "siglaestado":"MT",
   "nomemunicipio":"PORTO ALEGRE DO NORTE",
   "codigoexterno":9895
},
{
   "siglaestado":"MT",
   "nomemunicipio":"PORTO DOS GAUCHOS",
   "codigoexterno":9135
},
{
   "siglaestado":"MT",
   "nomemunicipio":"PORTO ESPERIDIAO",
   "codigoexterno":9875
},
{
   "siglaestado":"MT",
   "nomemunicipio":"PORTO ESTRELA",
   "codigoexterno":101
},
{
   "siglaestado":"MT",
   "nomemunicipio":"POXOREO",
   "codigoexterno":9139
},
{
   "siglaestado":"MT",
   "nomemunicipio":"PRIMAVERA DO LESTE",
   "codigoexterno":9871
},
{
   "siglaestado":"MT",
   "nomemunicipio":"QUERENCIA",
   "codigoexterno":97
},
{
   "siglaestado":"MT",
   "nomemunicipio":"RESERVA DO CABACAL",
   "codigoexterno":9879
},
{
   "siglaestado":"MT",
   "nomemunicipio":"RIBEIRAO CASCALHEIRA",
   "codigoexterno":9741
},
{
   "siglaestado":"MT",
   "nomemunicipio":"RIBEIRAOZINHO",
   "codigoexterno":99
},
{
   "siglaestado":"MT",
   "nomemunicipio":"RIO BRANCO",
   "codigoexterno":8995
},
{
   "siglaestado":"MT",
   "nomemunicipio":"RONDOL?NDIA",
   "codigoexterno":1092
},
{
   "siglaestado":"MT",
   "nomemunicipio":"RONDONOPOLIS",
   "codigoexterno":9151
},
{
   "siglaestado":"MT",
   "nomemunicipio":"ROSARIO OESTE",
   "codigoexterno":9153
},
{
   "siglaestado":"MT",
   "nomemunicipio":"SALTO DO CEU",
   "codigoexterno":8997
},
{
   "siglaestado":"MT",
   "nomemunicipio":"SANTA CARMEN",
   "codigoexterno":123
},
{
   "siglaestado":"MT",
   "nomemunicipio":"SANTA CRUZ DO XINGU",
   "codigoexterno":1094
},
{
   "siglaestado":"MT",
   "nomemunicipio":"SANTA RITA DO TRIVELATO",
   "codigoexterno":1096
},
{
   "siglaestado":"MT",
   "nomemunicipio":"SANTA TEREZINHA",
   "codigoexterno":9197
},
{
   "siglaestado":"MT",
   "nomemunicipio":"SANTO AFONSO",
   "codigoexterno":115
},
{
   "siglaestado":"MT",
   "nomemunicipio":"SANTO ANTONIO DO LESTE",
   "codigoexterno":1098
},
{
   "siglaestado":"MT",
   "nomemunicipio":"SANTO ANTONIO DO LEVERGER",
   "codigoexterno":9155
},
{
   "siglaestado":"MT",
   "nomemunicipio":"SAO FELIX DO ARAGUAIA",
   "codigoexterno":9183
},
{
   "siglaestado":"MT",
   "nomemunicipio":"SAO JOSE DO POVO",
   "codigoexterno":6087
},
{
   "siglaestado":"MT",
   "nomemunicipio":"SAO JOSE DO RIO CLARO",
   "codigoexterno":9199
},
{
   "siglaestado":"MT",
   "nomemunicipio":"SAO JOSE DO XINGU",
   "codigoexterno":133
},
{
   "siglaestado":"MT",
   "nomemunicipio":"SAO JOSE DOS QUATRO MARCOS",
   "codigoexterno":8993
},
{
   "siglaestado":"MT",
   "nomemunicipio":"SAO PEDRO DA CIPA",
   "codigoexterno":93
},
{
   "siglaestado":"MT",
   "nomemunicipio":"SAPEZAL",
   "codigoexterno":1046
},
{
   "siglaestado":"MT",
   "nomemunicipio":"SERRA NOVA DOURADA",
   "codigoexterno":1100
},
{
   "siglaestado":"MT",
   "nomemunicipio":"SINOP",
   "codigoexterno":8985
},
{
   "siglaestado":"MT",
   "nomemunicipio":"SORRISO",
   "codigoexterno":9907
},
{
   "siglaestado":"MT",
   "nomemunicipio":"TABAPORA",
   "codigoexterno":125
},
{
   "siglaestado":"MT",
   "nomemunicipio":"TANGARA DA SERRA",
   "codigoexterno":9185
},
{
   "siglaestado":"MT",
   "nomemunicipio":"TAPURAH",
   "codigoexterno":9763
},
{
   "siglaestado":"MT",
   "nomemunicipio":"TERRA NOVA DO NORTE",
   "codigoexterno":9909
},
{
   "siglaestado":"MT",
   "nomemunicipio":"TESOURO",
   "codigoexterno":9161
},
{
   "siglaestado":"MT",
   "nomemunicipio":"TORIXOREU",
   "codigoexterno":9163
},
{
   "siglaestado":"MT",
   "nomemunicipio":"UNIAO DO SUL",
   "codigoexterno":1048
},
{
   "siglaestado":"MT",
   "nomemunicipio":"VALE DE S?O DOMINGOS",
   "codigoexterno":1102
},
{
   "siglaestado":"MT",
   "nomemunicipio":"VARZEA GRANDE",
   "codigoexterno":9167
},
{
   "siglaestado":"MT",
   "nomemunicipio":"VERA",
   "codigoexterno":9905
},
{
   "siglaestado":"MT",
   "nomemunicipio":"VILA BELA DA SANTISSIMA TRINDADE",
   "codigoexterno":9109
},
{
   "siglaestado":"MT",
   "nomemunicipio":"VILA RICA",
   "codigoexterno":9897
},
{
   "siglaestado":"PA",
   "nomemunicipio":"ABAETETUBA",
   "codigoexterno":401
},
{
   "siglaestado":"PA",
   "nomemunicipio":"ABEL FIGUEIREDO",
   "codigoexterno":375
},
{
   "siglaestado":"PA",
   "nomemunicipio":"ACARA",
   "codigoexterno":403
},
{
   "siglaestado":"PA",
   "nomemunicipio":"AFUA",
   "codigoexterno":405
},
{
   "siglaestado":"PA",
   "nomemunicipio":"AGUA AZUL DO NORTE",
   "codigoexterno":383
},
{
   "siglaestado":"PA",
   "nomemunicipio":"ALENQUER",
   "codigoexterno":407
},
{
   "siglaestado":"PA",
   "nomemunicipio":"ALMEIRIM",
   "codigoexterno":409
},
{
   "siglaestado":"PA",
   "nomemunicipio":"ALTAMIRA",
   "codigoexterno":411
},
{
   "siglaestado":"PA",
   "nomemunicipio":"ANAJAS",
   "codigoexterno":413
},
{
   "siglaestado":"PA",
   "nomemunicipio":"ANANINDEUA",
   "codigoexterno":415
},
{
   "siglaestado":"PA",
   "nomemunicipio":"ANAPU",
   "codigoexterno":40
},
{
   "siglaestado":"PA",
   "nomemunicipio":"AUGUSTO CORREA",
   "codigoexterno":417
},
{
   "siglaestado":"PA",
   "nomemunicipio":"AURORA DO PARA",
   "codigoexterno":389
},
{
   "siglaestado":"PA",
   "nomemunicipio":"AVEIRO",
   "codigoexterno":419
},
{
   "siglaestado":"PA",
   "nomemunicipio":"BAGRE",
   "codigoexterno":421
},
{
   "siglaestado":"PA",
   "nomemunicipio":"BAIAO",
   "codigoexterno":423
},
{
   "siglaestado":"PA",
   "nomemunicipio":"BANNACH",
   "codigoexterno":42
},
{
   "siglaestado":"PA",
   "nomemunicipio":"BARCARENA",
   "codigoexterno":425
},
{
   "siglaestado":"PA",
   "nomemunicipio":"BELEM",
   "codigoexterno":427
},
{
   "siglaestado":"PA",
   "nomemunicipio":"BELTERRA",
   "codigoexterno":44
},
{
   "siglaestado":"PA",
   "nomemunicipio":"BENEVIDES",
   "codigoexterno":429
},
{
   "siglaestado":"PA",
   "nomemunicipio":"BOM JESUS DO TOCANTINS",
   "codigoexterno":575
},
{
   "siglaestado":"PA",
   "nomemunicipio":"BONITO",
   "codigoexterno":431
},
{
   "siglaestado":"PA",
   "nomemunicipio":"BRAGANCA",
   "codigoexterno":433
},
{
   "siglaestado":"PA",
   "nomemunicipio":"BRASIL NOVO",
   "codigoexterno":639
},
{
   "siglaestado":"PA",
   "nomemunicipio":"BREJO GRANDE DO ARAGUAIA",
   "codigoexterno":577
},
{
   "siglaestado":"PA",
   "nomemunicipio":"BREU BRANCO",
   "codigoexterno":625
},
{
   "siglaestado":"PA",
   "nomemunicipio":"BREVES",
   "codigoexterno":435
},
{
   "siglaestado":"PA",
   "nomemunicipio":"BUJARU",
   "codigoexterno":437
},
{
   "siglaestado":"PA",
   "nomemunicipio":"CACHOEIRA DO ARARI",
   "codigoexterno":439
},
{
   "siglaestado":"PA",
   "nomemunicipio":"CACHOEIRA DO PIRIA",
   "codigoexterno":46
},
{
   "siglaestado":"PA",
   "nomemunicipio":"CAMETA",
   "codigoexterno":441
},
{
   "siglaestado":"PA",
   "nomemunicipio":"CANAA DOS CARAJAS",
   "codigoexterno":48
},
{
   "siglaestado":"PA",
   "nomemunicipio":"CAPANEMA",
   "codigoexterno":443
},
{
   "siglaestado":"PA",
   "nomemunicipio":"CAPITAO POCO",
   "codigoexterno":445
},
{
   "siglaestado":"PA",
   "nomemunicipio":"CASTANHAL",
   "codigoexterno":447
},
{
   "siglaestado":"PA",
   "nomemunicipio":"CHAVES",
   "codigoexterno":449
},
{
   "siglaestado":"PA",
   "nomemunicipio":"COLARES",
   "codigoexterno":451
},
{
   "siglaestado":"PA",
   "nomemunicipio":"CONCEICAO DO ARAGUAIA",
   "codigoexterno":453
},
{
   "siglaestado":"PA",
   "nomemunicipio":"CONCORDIA DO PARA",
   "codigoexterno":579
},
{
   "siglaestado":"PA",
   "nomemunicipio":"CUMARU DO NORTE",
   "codigoexterno":385
},
{
   "siglaestado":"PA",
   "nomemunicipio":"CURIONOPOLIS",
   "codigoexterno":581
},
{
   "siglaestado":"PA",
   "nomemunicipio":"CURRALINHO",
   "codigoexterno":455
},
{
   "siglaestado":"PA",
   "nomemunicipio":"CURUA",
   "codigoexterno":50
},
{
   "siglaestado":"PA",
   "nomemunicipio":"CURUCA",
   "codigoexterno":457
},
{
   "siglaestado":"PA",
   "nomemunicipio":"DOM ELISEU",
   "codigoexterno":583
},
{
   "siglaestado":"PA",
   "nomemunicipio":"ELDORADO DOS CARAJAS",
   "codigoexterno":377
},
{
   "siglaestado":"PA",
   "nomemunicipio":"FARO",
   "codigoexterno":459
},
{
   "siglaestado":"PA",
   "nomemunicipio":"FLORESTA DO ARAGUAIA",
   "codigoexterno":52
},
{
   "siglaestado":"PA",
   "nomemunicipio":"GARRAFAO DO NORTE",
   "codigoexterno":585
},
{
   "siglaestado":"PA",
   "nomemunicipio":"GOIANESIA DO PARA",
   "codigoexterno":627
},
{
   "siglaestado":"PA",
   "nomemunicipio":"GURUPA",
   "codigoexterno":461
},
{
   "siglaestado":"PA",
   "nomemunicipio":"IGARAPE-ACU",
   "codigoexterno":463
},
{
   "siglaestado":"PA",
   "nomemunicipio":"IGARAPE-MIRI",
   "codigoexterno":465
},
{
   "siglaestado":"PA",
   "nomemunicipio":"INHANGAPI",
   "codigoexterno":467
},
{
   "siglaestado":"PA",
   "nomemunicipio":"IPIXUNA DO PARA",
   "codigoexterno":621
},
{
   "siglaestado":"PA",
   "nomemunicipio":"IRITUIA",
   "codigoexterno":469
},
{
   "siglaestado":"PA",
   "nomemunicipio":"ITAITUBA",
   "codigoexterno":471
},
{
   "siglaestado":"PA",
   "nomemunicipio":"ITUPIRANGA",
   "codigoexterno":473
},
{
   "siglaestado":"PA",
   "nomemunicipio":"JACAREACANGA",
   "codigoexterno":631
},
{
   "siglaestado":"PA",
   "nomemunicipio":"JACUNDA",
   "codigoexterno":475
},
{
   "siglaestado":"PA",
   "nomemunicipio":"JURUTI",
   "codigoexterno":477
},
{
   "siglaestado":"PA",
   "nomemunicipio":"LIMOEIRO DO AJURU",
   "codigoexterno":479
},
{
   "siglaestado":"PA",
   "nomemunicipio":"MAE DO RIO",
   "codigoexterno":587
},
{
   "siglaestado":"PA",
   "nomemunicipio":"MAGALHAES BARATA",
   "codigoexterno":481
},
{
   "siglaestado":"PA",
   "nomemunicipio":"MARABA",
   "codigoexterno":483
},
{
   "siglaestado":"PA",
   "nomemunicipio":"MARACANA",
   "codigoexterno":485
},
{
   "siglaestado":"PA",
   "nomemunicipio":"MARAPANIM",
   "codigoexterno":487
},
{
   "siglaestado":"PA",
   "nomemunicipio":"MARITUBA",
   "codigoexterno":54
},
{
   "siglaestado":"PA",
   "nomemunicipio":"MEDICILANDIA",
   "codigoexterno":589
},
{
   "siglaestado":"PA",
   "nomemunicipio":"MELGACO",
   "codigoexterno":489
},
{
   "siglaestado":"PA",
   "nomemunicipio":"MOCAJUBA",
   "codigoexterno":491
},
{
   "siglaestado":"PA",
   "nomemunicipio":"MOJU",
   "codigoexterno":493
},
{
   "siglaestado":"PA",
   "nomemunicipio":"MOJUI DOS CAMPOS",
   "codigoexterno":1190
},
{
   "siglaestado":"PA",
   "nomemunicipio":"MONTE ALEGRE",
   "codigoexterno":495
},
{
   "siglaestado":"PA",
   "nomemunicipio":"MUANA",
   "codigoexterno":497
},
{
   "siglaestado":"PA",
   "nomemunicipio":"NOVA ESPERANCA DO PIRIA",
   "codigoexterno":391
},
{
   "siglaestado":"PA",
   "nomemunicipio":"NOVA IPIXUNA",
   "codigoexterno":56
},
{
   "siglaestado":"PA",
   "nomemunicipio":"NOVA TIMBOTEUA",
   "codigoexterno":499
},
{
   "siglaestado":"PA",
   "nomemunicipio":"NOVO PROGRESSO",
   "codigoexterno":633
},
{
   "siglaestado":"PA",
   "nomemunicipio":"NOVO REPARTIMENTO",
   "codigoexterno":629
},
{
   "siglaestado":"PA",
   "nomemunicipio":"OBIDOS",
   "codigoexterno":501
},
{
   "siglaestado":"PA",
   "nomemunicipio":"OEIRAS DO PARA",
   "codigoexterno":503
},
{
   "siglaestado":"PA",
   "nomemunicipio":"ORIXIMINA",
   "codigoexterno":505
},
{
   "siglaestado":"PA",
   "nomemunicipio":"OUREM",
   "codigoexterno":507
},
{
   "siglaestado":"PA",
   "nomemunicipio":"OURILANDIA DO NORTE",
   "codigoexterno":591
},
{
   "siglaestado":"PA",
   "nomemunicipio":"PACAJA",
   "codigoexterno":593
},
{
   "siglaestado":"PA",
   "nomemunicipio":"PALESTINA DO PARA",
   "codigoexterno":379
},
{
   "siglaestado":"PA",
   "nomemunicipio":"PARAGOMINAS",
   "codigoexterno":509
},
{
   "siglaestado":"PA",
   "nomemunicipio":"PARAUAPEBAS",
   "codigoexterno":595
},
{
   "siglaestado":"PA",
   "nomemunicipio":"PAU D\'ARCO",
   "codigoexterno":387
},
{
   "siglaestado":"PA",
   "nomemunicipio":"PEIXE-BOI",
   "codigoexterno":511
},
{
   "siglaestado":"PA",
   "nomemunicipio":"PICARRA",
   "codigoexterno":58
},
{
   "siglaestado":"PA",
   "nomemunicipio":"PLACAS",
   "codigoexterno":60
},
{
   "siglaestado":"PA",
   "nomemunicipio":"PONTA DE PEDRAS",
   "codigoexterno":513
},
{
   "siglaestado":"PA",
   "nomemunicipio":"PORTEL",
   "codigoexterno":515
},
{
   "siglaestado":"PA",
   "nomemunicipio":"PORTO DE MOZ",
   "codigoexterno":517
},
{
   "siglaestado":"PA",
   "nomemunicipio":"PRAINHA",
   "codigoexterno":519
},
{
   "siglaestado":"PA",
   "nomemunicipio":"PRIMAVERA",
   "codigoexterno":521
},
{
   "siglaestado":"PA",
   "nomemunicipio":"QUATIPURU",
   "codigoexterno":62
},
{
   "siglaestado":"PA",
   "nomemunicipio":"REDENCAO",
   "codigoexterno":567
},
{
   "siglaestado":"PA",
   "nomemunicipio":"RIO MARIA",
   "codigoexterno":569
},
{
   "siglaestado":"PA",
   "nomemunicipio":"RONDON DO PARA",
   "codigoexterno":573
},
{
   "siglaestado":"PA",
   "nomemunicipio":"RUROPOLIS",
   "codigoexterno":597
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SALINOPOLIS",
   "codigoexterno":523
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SALVATERRA",
   "codigoexterno":525
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SANTA BARBARA DO PARA",
   "codigoexterno":369
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SANTA CRUZ DO ARARI",
   "codigoexterno":527
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SANTA ISABEL DO PARA",
   "codigoexterno":529
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SANTA LUZIA DO PARA",
   "codigoexterno":371
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SANTA MARIA DAS BARREIRAS",
   "codigoexterno":599
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SANTA MARIA DO PARA",
   "codigoexterno":531
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SANTANA DO ARAGUAIA",
   "codigoexterno":533
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SANTAREM",
   "codigoexterno":535
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SANTAREM NOVO",
   "codigoexterno":537
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SANTO ANTONIO DO TAUA",
   "codigoexterno":539
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SAO CAETANO DE ODIVELAS",
   "codigoexterno":541
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SAO DOMINGOS DO ARAGUAIA",
   "codigoexterno":381
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SAO DOMINGOS DO CAPIM",
   "codigoexterno":543
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SAO FELIX DO XINGU",
   "codigoexterno":545
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SAO FRANCISCO DO PARA",
   "codigoexterno":547
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SAO GERALDO DO ARAGUAIA",
   "codigoexterno":619
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SAO JOAO DA PONTA",
   "codigoexterno":64
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SAO JOAO DE PIRABAS",
   "codigoexterno":393
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SAO JOAO DO ARAGUAIA",
   "codigoexterno":549
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SAO MIGUEL DO GUAMA",
   "codigoexterno":551
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SAO SEBASTIAO DA BOA VISTA",
   "codigoexterno":553
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SAPUCAIA",
   "codigoexterno":66
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SENADOR JOSE PORFIRIO",
   "codigoexterno":555
},
{
   "siglaestado":"PA",
   "nomemunicipio":"SOURE",
   "codigoexterno":557
},
{
   "siglaestado":"PA",
   "nomemunicipio":"TAILANDIA",
   "codigoexterno":395
},
{
   "siglaestado":"PA",
   "nomemunicipio":"TERRA ALTA",
   "codigoexterno":373
},
{
   "siglaestado":"PA",
   "nomemunicipio":"TERRA SANTA",
   "codigoexterno":637
},
{
   "siglaestado":"PA",
   "nomemunicipio":"TOME-ACU",
   "codigoexterno":559
},
{
   "siglaestado":"PA",
   "nomemunicipio":"TRACUATEUA",
   "codigoexterno":68
},
{
   "siglaestado":"PA",
   "nomemunicipio":"TRAIRAO",
   "codigoexterno":635
},
{
   "siglaestado":"PA",
   "nomemunicipio":"TUCUMA",
   "codigoexterno":397
},
{
   "siglaestado":"PA",
   "nomemunicipio":"TUCURUI",
   "codigoexterno":561
},
{
   "siglaestado":"PA",
   "nomemunicipio":"ULIANOPOLIS",
   "codigoexterno":623
},
{
   "siglaestado":"PA",
   "nomemunicipio":"URUARA",
   "codigoexterno":399
},
{
   "siglaestado":"PA",
   "nomemunicipio":"VIGIA",
   "codigoexterno":563
},
{
   "siglaestado":"PA",
   "nomemunicipio":"VISEU",
   "codigoexterno":565
},
{
   "siglaestado":"PA",
   "nomemunicipio":"VITORIA DO XINGU",
   "codigoexterno":641
},
{
   "siglaestado":"PA",
   "nomemunicipio":"XINGUARA",
   "codigoexterno":571
},
{
   "siglaestado":"PB",
   "nomemunicipio":"AGUA BRANCA",
   "codigoexterno":1901
},
{
   "siglaestado":"PB",
   "nomemunicipio":"AGUIAR",
   "codigoexterno":1903
},
{
   "siglaestado":"PB",
   "nomemunicipio":"ALAGOA GRANDE",
   "codigoexterno":1905
},
{
   "siglaestado":"PB",
   "nomemunicipio":"ALAGOA NOVA",
   "codigoexterno":1907
},
{
   "siglaestado":"PB",
   "nomemunicipio":"ALAGOINHA",
   "codigoexterno":1909
},
{
   "siglaestado":"PB",
   "nomemunicipio":"ALCANTIL",
   "codigoexterno":440
},
{
   "siglaestado":"PB",
   "nomemunicipio":"ALGODAO DE JANDAIRA",
   "codigoexterno":442
},
{
   "siglaestado":"PB",
   "nomemunicipio":"ALHANDRA",
   "codigoexterno":1911
},
{
   "siglaestado":"PB",
   "nomemunicipio":"AMPARO",
   "codigoexterno":444
},
{
   "siglaestado":"PB",
   "nomemunicipio":"APARECIDA",
   "codigoexterno":446
},
{
   "siglaestado":"PB",
   "nomemunicipio":"ARACAGI",
   "codigoexterno":1915
},
{
   "siglaestado":"PB",
   "nomemunicipio":"ARARA",
   "codigoexterno":1917
},
{
   "siglaestado":"PB",
   "nomemunicipio":"ARARUNA",
   "codigoexterno":1919
},
{
   "siglaestado":"PB",
   "nomemunicipio":"AREIA",
   "codigoexterno":1921
},
{
   "siglaestado":"PB",
   "nomemunicipio":"AREIA DE BARAUNAS",
   "codigoexterno":448
},
{
   "siglaestado":"PB",
   "nomemunicipio":"AREIAL",
   "codigoexterno":1923
},
{
   "siglaestado":"PB",
   "nomemunicipio":"AROEIRAS",
   "codigoexterno":1925
},
{
   "siglaestado":"PB",
   "nomemunicipio":"ASSUNCAO",
   "codigoexterno":450
},
{
   "siglaestado":"PB",
   "nomemunicipio":"BAIA DA TRAICAO",
   "codigoexterno":1929
},
{
   "siglaestado":"PB",
   "nomemunicipio":"BANANEIRAS",
   "codigoexterno":1931
},
{
   "siglaestado":"PB",
   "nomemunicipio":"BARAUNA",
   "codigoexterno":452
},
{
   "siglaestado":"PB",
   "nomemunicipio":"BARRA DE SANTA ROSA",
   "codigoexterno":1933
},
{
   "siglaestado":"PB",
   "nomemunicipio":"BARRA DE SANTANA",
   "codigoexterno":454
},
{
   "siglaestado":"PB",
   "nomemunicipio":"BARRA DE SAO MIGUEL",
   "codigoexterno":1935
},
{
   "siglaestado":"PB",
   "nomemunicipio":"BAYEUX",
   "codigoexterno":1937
},
{
   "siglaestado":"PB",
   "nomemunicipio":"BELEM",
   "codigoexterno":1939
},
{
   "siglaestado":"PB",
   "nomemunicipio":"BELEM DO BREJO DO CRUZ",
   "codigoexterno":1941
},
{
   "siglaestado":"PB",
   "nomemunicipio":"BERNARDINO BATISTA",
   "codigoexterno":456
},
{
   "siglaestado":"PB",
   "nomemunicipio":"BOA VENTURA",
   "codigoexterno":1943
},
{
   "siglaestado":"PB",
   "nomemunicipio":"BOA VISTA",
   "codigoexterno":458
},
{
   "siglaestado":"PB",
   "nomemunicipio":"BOM JESUS",
   "codigoexterno":1945
},
{
   "siglaestado":"PB",
   "nomemunicipio":"BOM SUCESSO",
   "codigoexterno":1947
},
{
   "siglaestado":"PB",
   "nomemunicipio":"BONITO DE SANTA FE",
   "codigoexterno":1949
},
{
   "siglaestado":"PB",
   "nomemunicipio":"BOQUEIRAO",
   "codigoexterno":1951
},
{
   "siglaestado":"PB",
   "nomemunicipio":"BORBOREMA",
   "codigoexterno":1955
},
{
   "siglaestado":"PB",
   "nomemunicipio":"BREJO DO CRUZ",
   "codigoexterno":1957
},
{
   "siglaestado":"PB",
   "nomemunicipio":"BREJO DOS SANTOS",
   "codigoexterno":1959
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CAAPORA",
   "codigoexterno":1961
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CABACEIRAS",
   "codigoexterno":1963
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CABEDELO",
   "codigoexterno":1965
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CACHOEIRA DOS INDIOS",
   "codigoexterno":1967
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CACIMBA DE AREIA",
   "codigoexterno":1969
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CACIMBA DE DENTRO",
   "codigoexterno":1971
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CACIMBAS",
   "codigoexterno":460
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CAICARA",
   "codigoexterno":1973
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CAJAZEIRAS",
   "codigoexterno":1975
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CAJAZEIRINHAS",
   "codigoexterno":462
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CALDAS BRANDAO",
   "codigoexterno":1977
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CAMALAU",
   "codigoexterno":1979
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CAMPINA GRANDE",
   "codigoexterno":1981
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CAPIM",
   "codigoexterno":464
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CARAUBAS",
   "codigoexterno":466
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CARRAPATEIRA",
   "codigoexterno":1983
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CASSERENGUE",
   "codigoexterno":468
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CATINGUEIRA",
   "codigoexterno":1985
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CATOLE DO ROCHA",
   "codigoexterno":1987
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CATURITE",
   "codigoexterno":470
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CONCEICAO",
   "codigoexterno":1989
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CONDADO",
   "codigoexterno":1991
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CONDE",
   "codigoexterno":1993
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CONGO",
   "codigoexterno":1995
},
{
   "siglaestado":"PB",
   "nomemunicipio":"COREMAS",
   "codigoexterno":1997
},
{
   "siglaestado":"PB",
   "nomemunicipio":"COXIXOLA",
   "codigoexterno":472
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CRUZ DO ESPIRITO SANTO",
   "codigoexterno":1999
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CUBATI",
   "codigoexterno":2001
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CUITE",
   "codigoexterno":2003
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CUITE DE MAMANGUAPE",
   "codigoexterno":474
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CUITEGI",
   "codigoexterno":2005
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CURRAL DE CIMA",
   "codigoexterno":476
},
{
   "siglaestado":"PB",
   "nomemunicipio":"CURRAL VELHO",
   "codigoexterno":2007
},
{
   "siglaestado":"PB",
   "nomemunicipio":"DAMIAO",
   "codigoexterno":478
},
{
   "siglaestado":"PB",
   "nomemunicipio":"DESTERRO",
   "codigoexterno":2009
},
{
   "siglaestado":"PB",
   "nomemunicipio":"DIAMANTE",
   "codigoexterno":2013
},
{
   "siglaestado":"PB",
   "nomemunicipio":"DONA INES",
   "codigoexterno":2015
},
{
   "siglaestado":"PB",
   "nomemunicipio":"DUAS ESTRADAS",
   "codigoexterno":2017
},
{
   "siglaestado":"PB",
   "nomemunicipio":"EMAS",
   "codigoexterno":2019
},
{
   "siglaestado":"PB",
   "nomemunicipio":"ESPERANCA",
   "codigoexterno":2021
},
{
   "siglaestado":"PB",
   "nomemunicipio":"FAGUNDES",
   "codigoexterno":2023
},
{
   "siglaestado":"PB",
   "nomemunicipio":"FREI MARTINHO",
   "codigoexterno":2025
},
{
   "siglaestado":"PB",
   "nomemunicipio":"GADO BRAVO",
   "codigoexterno":480
},
{
   "siglaestado":"PB",
   "nomemunicipio":"GUARABIRA",
   "codigoexterno":2027
},
{
   "siglaestado":"PB",
   "nomemunicipio":"GURINHEM",
   "codigoexterno":2029
},
{
   "siglaestado":"PB",
   "nomemunicipio":"GURJAO",
   "codigoexterno":2031
},
{
   "siglaestado":"PB",
   "nomemunicipio":"IBIARA",
   "codigoexterno":2033
},
{
   "siglaestado":"PB",
   "nomemunicipio":"IGARACY",
   "codigoexterno":1953
},
{
   "siglaestado":"PB",
   "nomemunicipio":"IMACULADA",
   "codigoexterno":2035
},
{
   "siglaestado":"PB",
   "nomemunicipio":"INGA",
   "codigoexterno":2037
},
{
   "siglaestado":"PB",
   "nomemunicipio":"ITABAIANA",
   "codigoexterno":2039
},
{
   "siglaestado":"PB",
   "nomemunicipio":"ITAPORANGA",
   "codigoexterno":2041
},
{
   "siglaestado":"PB",
   "nomemunicipio":"ITAPOROROCA",
   "codigoexterno":2043
},
{
   "siglaestado":"PB",
   "nomemunicipio":"ITATUBA",
   "codigoexterno":2045
},
{
   "siglaestado":"PB",
   "nomemunicipio":"JACARAU",
   "codigoexterno":2047
},
{
   "siglaestado":"PB",
   "nomemunicipio":"JERICO",
   "codigoexterno":2049
},
{
   "siglaestado":"PB",
   "nomemunicipio":"JOAO PESSOA",
   "codigoexterno":2051
},
{
   "siglaestado":"PB",
   "nomemunicipio":"JUAREZ TAVORA",
   "codigoexterno":2053
},
{
   "siglaestado":"PB",
   "nomemunicipio":"JUAZEIRINHO",
   "codigoexterno":2055
},
{
   "siglaestado":"PB",
   "nomemunicipio":"JUNCO DO SERIDO",
   "codigoexterno":2057
},
{
   "siglaestado":"PB",
   "nomemunicipio":"JURIPIRANGA",
   "codigoexterno":2059
},
{
   "siglaestado":"PB",
   "nomemunicipio":"JURU",
   "codigoexterno":2061
},
{
   "siglaestado":"PB",
   "nomemunicipio":"LAGOA",
   "codigoexterno":2063
},
{
   "siglaestado":"PB",
   "nomemunicipio":"LAGOA DE DENTRO",
   "codigoexterno":2065
},
{
   "siglaestado":"PB",
   "nomemunicipio":"LAGOA SECA",
   "codigoexterno":2067
},
{
   "siglaestado":"PB",
   "nomemunicipio":"LASTRO",
   "codigoexterno":2069
},
{
   "siglaestado":"PB",
   "nomemunicipio":"LIVRAMENTO",
   "codigoexterno":2071
},
{
   "siglaestado":"PB",
   "nomemunicipio":"LOGRADOURO",
   "codigoexterno":482
},
{
   "siglaestado":"PB",
   "nomemunicipio":"LUCENA",
   "codigoexterno":2073
},
{
   "siglaestado":"PB",
   "nomemunicipio":"MAE D\'AGUA",
   "codigoexterno":2075
},
{
   "siglaestado":"PB",
   "nomemunicipio":"MALTA",
   "codigoexterno":2077
},
{
   "siglaestado":"PB",
   "nomemunicipio":"MAMANGUAPE",
   "codigoexterno":2079
},
{
   "siglaestado":"PB",
   "nomemunicipio":"MANAIRA",
   "codigoexterno":2081
},
{
   "siglaestado":"PB",
   "nomemunicipio":"MARCACAO",
   "codigoexterno":484
},
{
   "siglaestado":"PB",
   "nomemunicipio":"MARI",
   "codigoexterno":2083
},
{
   "siglaestado":"PB",
   "nomemunicipio":"MARIZOPOLIS",
   "codigoexterno":486
},
{
   "siglaestado":"PB",
   "nomemunicipio":"MASSARANDUBA",
   "codigoexterno":2085
},
{
   "siglaestado":"PB",
   "nomemunicipio":"MATARACA",
   "codigoexterno":2087
},
{
   "siglaestado":"PB",
   "nomemunicipio":"MATINHAS",
   "codigoexterno":488
},
{
   "siglaestado":"PB",
   "nomemunicipio":"MATO GROSSO",
   "codigoexterno":490
},
{
   "siglaestado":"PB",
   "nomemunicipio":"MATUREIA",
   "codigoexterno":492
},
{
   "siglaestado":"PB",
   "nomemunicipio":"MOGEIRO",
   "codigoexterno":2089
},
{
   "siglaestado":"PB",
   "nomemunicipio":"MONTADAS",
   "codigoexterno":2091
},
{
   "siglaestado":"PB",
   "nomemunicipio":"MONTE HOREBE",
   "codigoexterno":2093
},
{
   "siglaestado":"PB",
   "nomemunicipio":"MONTEIRO",
   "codigoexterno":2095
},
{
   "siglaestado":"PB",
   "nomemunicipio":"MULUNGU",
   "codigoexterno":2097
},
{
   "siglaestado":"PB",
   "nomemunicipio":"NATUBA",
   "codigoexterno":2099
},
{
   "siglaestado":"PB",
   "nomemunicipio":"NAZAREZINHO",
   "codigoexterno":2101
},
{
   "siglaestado":"PB",
   "nomemunicipio":"NOVA FLORESTA",
   "codigoexterno":2103
},
{
   "siglaestado":"PB",
   "nomemunicipio":"NOVA OLINDA",
   "codigoexterno":2105
},
{
   "siglaestado":"PB",
   "nomemunicipio":"NOVA PALMEIRA",
   "codigoexterno":2107
},
{
   "siglaestado":"PB",
   "nomemunicipio":"OLHO D\'AGUA",
   "codigoexterno":2109
},
{
   "siglaestado":"PB",
   "nomemunicipio":"OLIVEDOS",
   "codigoexterno":2111
},
{
   "siglaestado":"PB",
   "nomemunicipio":"OURO VELHO",
   "codigoexterno":2113
},
{
   "siglaestado":"PB",
   "nomemunicipio":"PARARI",
   "codigoexterno":494
},
{
   "siglaestado":"PB",
   "nomemunicipio":"PASSAGEM",
   "codigoexterno":2115
},
{
   "siglaestado":"PB",
   "nomemunicipio":"PATOS",
   "codigoexterno":2117
},
{
   "siglaestado":"PB",
   "nomemunicipio":"PAULISTA",
   "codigoexterno":2119
},
{
   "siglaestado":"PB",
   "nomemunicipio":"PEDRA BRANCA",
   "codigoexterno":2121
},
{
   "siglaestado":"PB",
   "nomemunicipio":"PEDRA LAVRADA",
   "codigoexterno":2123
},
{
   "siglaestado":"PB",
   "nomemunicipio":"PEDRAS DE FOGO",
   "codigoexterno":2125
},
{
   "siglaestado":"PB",
   "nomemunicipio":"PEDRO REGIS",
   "codigoexterno":500
},
{
   "siglaestado":"PB",
   "nomemunicipio":"PIANCO",
   "codigoexterno":2127
},
{
   "siglaestado":"PB",
   "nomemunicipio":"PICUI",
   "codigoexterno":2129
},
{
   "siglaestado":"PB",
   "nomemunicipio":"PILAR",
   "codigoexterno":2131
},
{
   "siglaestado":"PB",
   "nomemunicipio":"PILOES",
   "codigoexterno":2133
},
{
   "siglaestado":"PB",
   "nomemunicipio":"PILOEZINHOS",
   "codigoexterno":2135
},
{
   "siglaestado":"PB",
   "nomemunicipio":"PIRPIRITUBA",
   "codigoexterno":2137
},
{
   "siglaestado":"PB",
   "nomemunicipio":"PITIMBU",
   "codigoexterno":2139
},
{
   "siglaestado":"PB",
   "nomemunicipio":"POCINHOS",
   "codigoexterno":2141
},
{
   "siglaestado":"PB",
   "nomemunicipio":"POCO DANTAS",
   "codigoexterno":496
},
{
   "siglaestado":"PB",
   "nomemunicipio":"POCO DE JOSE DE MOURA",
   "codigoexterno":498
},
{
   "siglaestado":"PB",
   "nomemunicipio":"POMBAL",
   "codigoexterno":2143
},
{
   "siglaestado":"PB",
   "nomemunicipio":"PRATA",
   "codigoexterno":2145
},
{
   "siglaestado":"PB",
   "nomemunicipio":"PRINCESA ISABEL",
   "codigoexterno":2147
},
{
   "siglaestado":"PB",
   "nomemunicipio":"PUXINANA",
   "codigoexterno":2149
},
{
   "siglaestado":"PB",
   "nomemunicipio":"QUEIMADAS",
   "codigoexterno":2151
},
{
   "siglaestado":"PB",
   "nomemunicipio":"QUIXABA",
   "codigoexterno":2153
},
{
   "siglaestado":"PB",
   "nomemunicipio":"REMIGIO",
   "codigoexterno":2155
},
{
   "siglaestado":"PB",
   "nomemunicipio":"RIACHAO",
   "codigoexterno":502
},
{
   "siglaestado":"PB",
   "nomemunicipio":"RIACHAO DO BACAMARTE",
   "codigoexterno":504
},
{
   "siglaestado":"PB",
   "nomemunicipio":"RIACHAO DO POCO",
   "codigoexterno":506
},
{
   "siglaestado":"PB",
   "nomemunicipio":"RIACHO DE SANTO ANTONIO",
   "codigoexterno":508
},
{
   "siglaestado":"PB",
   "nomemunicipio":"RIACHO DOS CAVALOS",
   "codigoexterno":2157
},
{
   "siglaestado":"PB",
   "nomemunicipio":"RIO TINTO",
   "codigoexterno":2159
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SALGADINHO",
   "codigoexterno":2161
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SALGADO DE SAO FELIX",
   "codigoexterno":2163
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SANTA CECILIA",
   "codigoexterno":510
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SANTA CRUZ",
   "codigoexterno":2165
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SANTA HELENA",
   "codigoexterno":2167
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SANTA INES",
   "codigoexterno":512
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SANTA LUZIA",
   "codigoexterno":2169
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SANTA RITA",
   "codigoexterno":2175
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SANTA TERESINHA",
   "codigoexterno":2177
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SANTANA DE MANGUEIRA",
   "codigoexterno":2171
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SANTANA DOS GARROTES",
   "codigoexterno":2173
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SANTAREM",
   "codigoexterno":514
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SANTO ANDRE",
   "codigoexterno":516
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO BENTINHO",
   "codigoexterno":518
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO BENTO",
   "codigoexterno":2179
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO DOMINGOS DE POMBAL",
   "codigoexterno":522
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO DOMINGOS DO CARIRI",
   "codigoexterno":520
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO FRANCISCO",
   "codigoexterno":524
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO JOAO DO CARIRI",
   "codigoexterno":2181
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO JOAO DO RIO DO PEIXE",
   "codigoexterno":1913
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO JOAO DO TIGRE",
   "codigoexterno":2183
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO JOSE DA LAGOA TAPADA",
   "codigoexterno":2185
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO JOSE DE CAIANA",
   "codigoexterno":2187
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO JOSE DE ESPINHARAS",
   "codigoexterno":2189
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO JOSE DE PIRANHAS",
   "codigoexterno":2191
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO JOSE DE PRINCESA",
   "codigoexterno":528
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO JOSE DO BONFIM",
   "codigoexterno":2193
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO JOSE DO BREJO DO CRUZ",
   "codigoexterno":530
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO JOSE DO SABUGI",
   "codigoexterno":2195
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO JOSE DOS CORDEIROS",
   "codigoexterno":2197
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO JOSE DOS RAMOS",
   "codigoexterno":526
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO MAMEDE",
   "codigoexterno":2199
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO MIGUEL DE TAIPU",
   "codigoexterno":2201
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO SEBASTIAO DE LAGOA DE ROCA",
   "codigoexterno":2203
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO SEBASTIAO DO UMBUZEIRO",
   "codigoexterno":2205
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAO VICENTE DO SERIDO",
   "codigoexterno":2209
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SAPE",
   "codigoexterno":2207
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SERRA BRANCA",
   "codigoexterno":2211
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SERRA DA RAIZ",
   "codigoexterno":2213
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SERRA GRANDE",
   "codigoexterno":2215
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SERRA REDONDA",
   "codigoexterno":2217
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SERRARIA",
   "codigoexterno":2219
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SERTAOZINHO",
   "codigoexterno":532
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SOBRADO",
   "codigoexterno":534
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SOLANEA",
   "codigoexterno":2221
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SOLEDADE",
   "codigoexterno":2223
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SOSSEGO",
   "codigoexterno":536
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SOUSA",
   "codigoexterno":2225
},
{
   "siglaestado":"PB",
   "nomemunicipio":"SUME",
   "codigoexterno":2227
},
{
   "siglaestado":"PB",
   "nomemunicipio":"TACIMA",
   "codigoexterno":2229
},
{
   "siglaestado":"PB",
   "nomemunicipio":"TAPEROA",
   "codigoexterno":2231
},
{
   "siglaestado":"PB",
   "nomemunicipio":"TAVARES",
   "codigoexterno":2233
},
{
   "siglaestado":"PB",
   "nomemunicipio":"TEIXEIRA",
   "codigoexterno":2235
},
{
   "siglaestado":"PB",
   "nomemunicipio":"TENORIO",
   "codigoexterno":538
},
{
   "siglaestado":"PB",
   "nomemunicipio":"TRIUNFO",
   "codigoexterno":2237
},
{
   "siglaestado":"PB",
   "nomemunicipio":"UIRAUNA",
   "codigoexterno":2239
},
{
   "siglaestado":"PB",
   "nomemunicipio":"UMBUZEIRO",
   "codigoexterno":2241
},
{
   "siglaestado":"PB",
   "nomemunicipio":"VARZEA",
   "codigoexterno":2243
},
{
   "siglaestado":"PB",
   "nomemunicipio":"VIEIROPOLIS",
   "codigoexterno":540
},
{
   "siglaestado":"PB",
   "nomemunicipio":"VISTA SERRANA",
   "codigoexterno":2011
},
{
   "siglaestado":"PB",
   "nomemunicipio":"ZABELE",
   "codigoexterno":542
},
{
   "siglaestado":"PE",
   "nomemunicipio":"ABREU E LIMA",
   "codigoexterno":2631
},
{
   "siglaestado":"PE",
   "nomemunicipio":"AFOGADOS DA INGAZEIRA",
   "codigoexterno":2301
},
{
   "siglaestado":"PE",
   "nomemunicipio":"AFRANIO",
   "codigoexterno":2303
},
{
   "siglaestado":"PE",
   "nomemunicipio":"AGRESTINA",
   "codigoexterno":2305
},
{
   "siglaestado":"PE",
   "nomemunicipio":"AGUA PRETA",
   "codigoexterno":2307
},
{
   "siglaestado":"PE",
   "nomemunicipio":"AGUAS BELAS",
   "codigoexterno":2309
},
{
   "siglaestado":"PE",
   "nomemunicipio":"ALAGOINHA",
   "codigoexterno":2311
},
{
   "siglaestado":"PE",
   "nomemunicipio":"ALIANCA",
   "codigoexterno":2313
},
{
   "siglaestado":"PE",
   "nomemunicipio":"ALTINHO",
   "codigoexterno":2315
},
{
   "siglaestado":"PE",
   "nomemunicipio":"AMARAJI",
   "codigoexterno":2317
},
{
   "siglaestado":"PE",
   "nomemunicipio":"ANGELIM",
   "codigoexterno":2319
},
{
   "siglaestado":"PE",
   "nomemunicipio":"ARACOIABA",
   "codigoexterno":544
},
{
   "siglaestado":"PE",
   "nomemunicipio":"ARARIPINA",
   "codigoexterno":2321
},
{
   "siglaestado":"PE",
   "nomemunicipio":"ARCOVERDE",
   "codigoexterno":2323
},
{
   "siglaestado":"PE",
   "nomemunicipio":"BARRA DE GUABIRABA",
   "codigoexterno":2325
},
{
   "siglaestado":"PE",
   "nomemunicipio":"BARREIROS",
   "codigoexterno":2327
},
{
   "siglaestado":"PE",
   "nomemunicipio":"BELEM DE MARIA",
   "codigoexterno":2329
},
{
   "siglaestado":"PE",
   "nomemunicipio":"BELEM DE SAO FRANCISCO",
   "codigoexterno":2331
},
{
   "siglaestado":"PE",
   "nomemunicipio":"BELO JARDIM",
   "codigoexterno":2333
},
{
   "siglaestado":"PE",
   "nomemunicipio":"BETANIA",
   "codigoexterno":2335
},
{
   "siglaestado":"PE",
   "nomemunicipio":"BEZERROS",
   "codigoexterno":2337
},
{
   "siglaestado":"PE",
   "nomemunicipio":"BODOCO",
   "codigoexterno":2339
},
{
   "siglaestado":"PE",
   "nomemunicipio":"BOM CONSELHO",
   "codigoexterno":2341
},
{
   "siglaestado":"PE",
   "nomemunicipio":"BOM JARDIM",
   "codigoexterno":2343
},
{
   "siglaestado":"PE",
   "nomemunicipio":"BONITO",
   "codigoexterno":2345
},
{
   "siglaestado":"PE",
   "nomemunicipio":"BREJAO",
   "codigoexterno":2347
},
{
   "siglaestado":"PE",
   "nomemunicipio":"BREJINHO",
   "codigoexterno":2349
},
{
   "siglaestado":"PE",
   "nomemunicipio":"BREJO DA MADRE DE DEUS",
   "codigoexterno":2351
},
{
   "siglaestado":"PE",
   "nomemunicipio":"BUENOS AIRES",
   "codigoexterno":2353
},
{
   "siglaestado":"PE",
   "nomemunicipio":"BUIQUE",
   "codigoexterno":2355
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CABO DE SANTO AGOSTINHO",
   "codigoexterno":2357
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CABROBO",
   "codigoexterno":2359
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CACHOEIRINHA",
   "codigoexterno":2361
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CAETES",
   "codigoexterno":2363
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CALCADO",
   "codigoexterno":2365
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CALUMBI",
   "codigoexterno":2367
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CAMARAGIBE",
   "codigoexterno":2629
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CAMOCIM DE SAO FELIX",
   "codigoexterno":2369
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CAMUTANGA",
   "codigoexterno":2371
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CANHOTINHO",
   "codigoexterno":2373
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CAPOEIRAS",
   "codigoexterno":2375
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CARNAIBA",
   "codigoexterno":2377
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CARNAUBEIRA DA PENHA",
   "codigoexterno":2635
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CARPINA",
   "codigoexterno":2379
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CARUARU",
   "codigoexterno":2381
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CASINHAS",
   "codigoexterno":546
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CATENDE",
   "codigoexterno":2383
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CEDRO",
   "codigoexterno":2385
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CHA DE ALEGRIA",
   "codigoexterno":2387
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CHA GRANDE",
   "codigoexterno":2389
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CONDADO",
   "codigoexterno":2391
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CORRENTES",
   "codigoexterno":2393
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CORTES",
   "codigoexterno":2395
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CUMARU",
   "codigoexterno":2397
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CUPIRA",
   "codigoexterno":2399
},
{
   "siglaestado":"PE",
   "nomemunicipio":"CUSTODIA",
   "codigoexterno":2401
},
{
   "siglaestado":"PE",
   "nomemunicipio":"DORMENTES",
   "codigoexterno":2299
},
{
   "siglaestado":"PE",
   "nomemunicipio":"ESCADA",
   "codigoexterno":2403
},
{
   "siglaestado":"PE",
   "nomemunicipio":"EXU",
   "codigoexterno":2405
},
{
   "siglaestado":"PE",
   "nomemunicipio":"FEIRA NOVA",
   "codigoexterno":2407
},
{
   "siglaestado":"PE",
   "nomemunicipio":"FERNANDO DE NORONHA",
   "codigoexterno":3001
},
{
   "siglaestado":"PE",
   "nomemunicipio":"FERREIROS",
   "codigoexterno":2409
},
{
   "siglaestado":"PE",
   "nomemunicipio":"FLORES",
   "codigoexterno":2411
},
{
   "siglaestado":"PE",
   "nomemunicipio":"FLORESTA",
   "codigoexterno":2413
},
{
   "siglaestado":"PE",
   "nomemunicipio":"FREI MIGUELINHO",
   "codigoexterno":2415
},
{
   "siglaestado":"PE",
   "nomemunicipio":"GAMELEIRA",
   "codigoexterno":2417
},
{
   "siglaestado":"PE",
   "nomemunicipio":"GARANHUNS",
   "codigoexterno":2419
},
{
   "siglaestado":"PE",
   "nomemunicipio":"GLORIA DO GOITA",
   "codigoexterno":2421
},
{
   "siglaestado":"PE",
   "nomemunicipio":"GOIANA",
   "codigoexterno":2423
},
{
   "siglaestado":"PE",
   "nomemunicipio":"GRANITO",
   "codigoexterno":2425
},
{
   "siglaestado":"PE",
   "nomemunicipio":"GRAVATA",
   "codigoexterno":2427
},
{
   "siglaestado":"PE",
   "nomemunicipio":"IATI",
   "codigoexterno":2429
},
{
   "siglaestado":"PE",
   "nomemunicipio":"IBIMIRIM",
   "codigoexterno":2431
},
{
   "siglaestado":"PE",
   "nomemunicipio":"IBIRAJUBA",
   "codigoexterno":2433
},
{
   "siglaestado":"PE",
   "nomemunicipio":"IGARASSU",
   "codigoexterno":2435
},
{
   "siglaestado":"PE",
   "nomemunicipio":"IGUARACI",
   "codigoexterno":2437
},
{
   "siglaestado":"PE",
   "nomemunicipio":"ILHA DE ITAMARACA",
   "codigoexterno":2451
},
{
   "siglaestado":"PE",
   "nomemunicipio":"INAJA",
   "codigoexterno":2439
},
{
   "siglaestado":"PE",
   "nomemunicipio":"INGAZEIRA",
   "codigoexterno":2441
},
{
   "siglaestado":"PE",
   "nomemunicipio":"IPOJUCA",
   "codigoexterno":2443
},
{
   "siglaestado":"PE",
   "nomemunicipio":"IPUBI",
   "codigoexterno":2445
},
{
   "siglaestado":"PE",
   "nomemunicipio":"ITACURUBA",
   "codigoexterno":2447
},
{
   "siglaestado":"PE",
   "nomemunicipio":"ITAIBA",
   "codigoexterno":2449
},
{
   "siglaestado":"PE",
   "nomemunicipio":"ITAMBE",
   "codigoexterno":2597
},
{
   "siglaestado":"PE",
   "nomemunicipio":"ITAPETIM",
   "codigoexterno":2453
},
{
   "siglaestado":"PE",
   "nomemunicipio":"ITAPISSUMA",
   "codigoexterno":2633
},
{
   "siglaestado":"PE",
   "nomemunicipio":"ITAQUITINGA",
   "codigoexterno":2455
},
{
   "siglaestado":"PE",
   "nomemunicipio":"JABOATAO DOS GUARARAPES",
   "codigoexterno":2457
},
{
   "siglaestado":"PE",
   "nomemunicipio":"JAQUEIRA",
   "codigoexterno":548
},
{
   "siglaestado":"PE",
   "nomemunicipio":"JATAUBA",
   "codigoexterno":2459
},
{
   "siglaestado":"PE",
   "nomemunicipio":"JATOBA",
   "codigoexterno":550
},
{
   "siglaestado":"PE",
   "nomemunicipio":"JOAO ALFREDO",
   "codigoexterno":2461
},
{
   "siglaestado":"PE",
   "nomemunicipio":"JOAQUIM NABUCO",
   "codigoexterno":2463
},
{
   "siglaestado":"PE",
   "nomemunicipio":"JUCATI",
   "codigoexterno":2295
},
{
   "siglaestado":"PE",
   "nomemunicipio":"JUPI",
   "codigoexterno":2465
},
{
   "siglaestado":"PE",
   "nomemunicipio":"JUREMA",
   "codigoexterno":2467
},
{
   "siglaestado":"PE",
   "nomemunicipio":"LAGOA DO CARRO",
   "codigoexterno":2289
},
{
   "siglaestado":"PE",
   "nomemunicipio":"LAGOA DO ITAENGA",
   "codigoexterno":2469
},
{
   "siglaestado":"PE",
   "nomemunicipio":"LAGOA DO OURO",
   "codigoexterno":2471
},
{
   "siglaestado":"PE",
   "nomemunicipio":"LAGOA DOS GATOS",
   "codigoexterno":2473
},
{
   "siglaestado":"PE",
   "nomemunicipio":"LAGOA GRANDE",
   "codigoexterno":552
},
{
   "siglaestado":"PE",
   "nomemunicipio":"LAJEDO",
   "codigoexterno":2475
},
{
   "siglaestado":"PE",
   "nomemunicipio":"LIMOEIRO",
   "codigoexterno":2477
},
{
   "siglaestado":"PE",
   "nomemunicipio":"MACAPARANA",
   "codigoexterno":2479
},
{
   "siglaestado":"PE",
   "nomemunicipio":"MACHADOS",
   "codigoexterno":2481
},
{
   "siglaestado":"PE",
   "nomemunicipio":"MANARI",
   "codigoexterno":554
},
{
   "siglaestado":"PE",
   "nomemunicipio":"MARAIAL",
   "codigoexterno":2483
},
{
   "siglaestado":"PE",
   "nomemunicipio":"MIRANDIBA",
   "codigoexterno":2485
},
{
   "siglaestado":"PE",
   "nomemunicipio":"MOREILANDIA",
   "codigoexterno":2585
},
{
   "siglaestado":"PE",
   "nomemunicipio":"MORENO",
   "codigoexterno":2487
},
{
   "siglaestado":"PE",
   "nomemunicipio":"NAZARE DA MATA",
   "codigoexterno":2489
},
{
   "siglaestado":"PE",
   "nomemunicipio":"OLINDA",
   "codigoexterno":2491
},
{
   "siglaestado":"PE",
   "nomemunicipio":"OROBO",
   "codigoexterno":2493
},
{
   "siglaestado":"PE",
   "nomemunicipio":"OROCO",
   "codigoexterno":2495
},
{
   "siglaestado":"PE",
   "nomemunicipio":"OURICURI",
   "codigoexterno":2497
},
{
   "siglaestado":"PE",
   "nomemunicipio":"PALMARES",
   "codigoexterno":2499
},
{
   "siglaestado":"PE",
   "nomemunicipio":"PALMEIRINA",
   "codigoexterno":2501
},
{
   "siglaestado":"PE",
   "nomemunicipio":"PANELAS",
   "codigoexterno":2503
},
{
   "siglaestado":"PE",
   "nomemunicipio":"PARANATAMA",
   "codigoexterno":2505
},
{
   "siglaestado":"PE",
   "nomemunicipio":"PARNAMIRIM",
   "codigoexterno":2507
},
{
   "siglaestado":"PE",
   "nomemunicipio":"PASSIRA",
   "codigoexterno":2509
},
{
   "siglaestado":"PE",
   "nomemunicipio":"PAUDALHO",
   "codigoexterno":2511
},
{
   "siglaestado":"PE",
   "nomemunicipio":"PAULISTA",
   "codigoexterno":2513
},
{
   "siglaestado":"PE",
   "nomemunicipio":"PEDRA",
   "codigoexterno":2515
},
{
   "siglaestado":"PE",
   "nomemunicipio":"PESQUEIRA",
   "codigoexterno":2517
},
{
   "siglaestado":"PE",
   "nomemunicipio":"PETROLANDIA",
   "codigoexterno":2519
},
{
   "siglaestado":"PE",
   "nomemunicipio":"PETROLINA",
   "codigoexterno":2521
},
{
   "siglaestado":"PE",
   "nomemunicipio":"POCAO",
   "codigoexterno":2523
},
{
   "siglaestado":"PE",
   "nomemunicipio":"POMBOS",
   "codigoexterno":2525
},
{
   "siglaestado":"PE",
   "nomemunicipio":"PRIMAVERA",
   "codigoexterno":2527
},
{
   "siglaestado":"PE",
   "nomemunicipio":"QUIPAPA",
   "codigoexterno":2529
},
{
   "siglaestado":"PE",
   "nomemunicipio":"QUIXABA",
   "codigoexterno":2637
},
{
   "siglaestado":"PE",
   "nomemunicipio":"RECIFE",
   "codigoexterno":2531
},
{
   "siglaestado":"PE",
   "nomemunicipio":"RIACHO DAS ALMAS",
   "codigoexterno":2533
},
{
   "siglaestado":"PE",
   "nomemunicipio":"RIBEIRAO",
   "codigoexterno":2535
},
{
   "siglaestado":"PE",
   "nomemunicipio":"RIO FORMOSO",
   "codigoexterno":2537
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SAIRE",
   "codigoexterno":2539
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SALGADINHO",
   "codigoexterno":2541
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SALGUEIRO",
   "codigoexterno":2543
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SALOA",
   "codigoexterno":2545
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SANHARO",
   "codigoexterno":2547
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SANTA CRUZ",
   "codigoexterno":2297
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SANTA CRUZ DA BAIXA VERDE",
   "codigoexterno":2639
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SANTA CRUZ DO CAPIBARIBE",
   "codigoexterno":2549
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SANTA FILOMENA",
   "codigoexterno":556
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SANTA MARIA DA BOA VISTA",
   "codigoexterno":2551
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SANTA MARIA DO CAMBUCA",
   "codigoexterno":2553
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SANTA TEREZINHA",
   "codigoexterno":2555
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SAO BENEDITO DO SUL",
   "codigoexterno":2557
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SAO BENTO DO UNA",
   "codigoexterno":2559
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SAO CAITANO",
   "codigoexterno":2561
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SAO JOAO",
   "codigoexterno":2563
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SAO JOAQUIM DO MONTE",
   "codigoexterno":2565
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SAO JOSE DA COROA GRANDE",
   "codigoexterno":2567
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SAO JOSE DO BELMONTE",
   "codigoexterno":2569
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SAO JOSE DO EGITO",
   "codigoexterno":2571
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SAO LOURENCO DA MATA",
   "codigoexterno":2573
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SAO VICENTE FERRER",
   "codigoexterno":2575
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SERRA TALHADA",
   "codigoexterno":2577
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SERRITA",
   "codigoexterno":2579
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SERTANIA",
   "codigoexterno":2581
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SIRINHAEM",
   "codigoexterno":2583
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SOLIDAO",
   "codigoexterno":2587
},
{
   "siglaestado":"PE",
   "nomemunicipio":"SURUBIM",
   "codigoexterno":2589
},
{
   "siglaestado":"PE",
   "nomemunicipio":"TABIRA",
   "codigoexterno":2591
},
{
   "siglaestado":"PE",
   "nomemunicipio":"TACAIMBO",
   "codigoexterno":2593
},
{
   "siglaestado":"PE",
   "nomemunicipio":"TACARATU",
   "codigoexterno":2595
},
{
   "siglaestado":"PE",
   "nomemunicipio":"TAMANDARE",
   "codigoexterno":558
},
{
   "siglaestado":"PE",
   "nomemunicipio":"TAQUARITINGA DO NORTE",
   "codigoexterno":2599
},
{
   "siglaestado":"PE",
   "nomemunicipio":"TEREZINHA",
   "codigoexterno":2601
},
{
   "siglaestado":"PE",
   "nomemunicipio":"TERRA NOVA",
   "codigoexterno":2603
},
{
   "siglaestado":"PE",
   "nomemunicipio":"TIMBAUBA",
   "codigoexterno":2605
},
{
   "siglaestado":"PE",
   "nomemunicipio":"TORITAMA",
   "codigoexterno":2607
},
{
   "siglaestado":"PE",
   "nomemunicipio":"TRACUNHAEM",
   "codigoexterno":2609
},
{
   "siglaestado":"PE",
   "nomemunicipio":"TRINDADE",
   "codigoexterno":2611
},
{
   "siglaestado":"PE",
   "nomemunicipio":"TRIUNFO",
   "codigoexterno":2613
},
{
   "siglaestado":"PE",
   "nomemunicipio":"TUPANATINGA",
   "codigoexterno":2615
},
{
   "siglaestado":"PE",
   "nomemunicipio":"TUPARETAMA",
   "codigoexterno":2617
},
{
   "siglaestado":"PE",
   "nomemunicipio":"VENTUROSA",
   "codigoexterno":2619
},
{
   "siglaestado":"PE",
   "nomemunicipio":"VERDEJANTE",
   "codigoexterno":2621
},
{
   "siglaestado":"PE",
   "nomemunicipio":"VERTENTE DO LERIO",
   "codigoexterno":2291
},
{
   "siglaestado":"PE",
   "nomemunicipio":"VERTENTES",
   "codigoexterno":2623
},
{
   "siglaestado":"PE",
   "nomemunicipio":"VICENCIA",
   "codigoexterno":2625
},
{
   "siglaestado":"PE",
   "nomemunicipio":"VITORIA DE SANTO ANTAO",
   "codigoexterno":2627
},
{
   "siglaestado":"PE",
   "nomemunicipio":"XEXEU",
   "codigoexterno":2293
},
{
   "siglaestado":"PI",
   "nomemunicipio":"ACAUA",
   "codigoexterno":266
},
{
   "siglaestado":"PI",
   "nomemunicipio":"AGRICOLANDIA",
   "codigoexterno":1001
},
{
   "siglaestado":"PI",
   "nomemunicipio":"AGUA BRANCA",
   "codigoexterno":1003
},
{
   "siglaestado":"PI",
   "nomemunicipio":"ALAGOINHA DO PIAUI",
   "codigoexterno":9767
},
{
   "siglaestado":"PI",
   "nomemunicipio":"ALEGRETE DO PIAUI",
   "codigoexterno":2269
},
{
   "siglaestado":"PI",
   "nomemunicipio":"ALTO LONGA",
   "codigoexterno":1005
},
{
   "siglaestado":"PI",
   "nomemunicipio":"ALTOS",
   "codigoexterno":1007
},
{
   "siglaestado":"PI",
   "nomemunicipio":"ALVORADA DO GURGUEIA",
   "codigoexterno":268
},
{
   "siglaestado":"PI",
   "nomemunicipio":"AMARANTE",
   "codigoexterno":1009
},
{
   "siglaestado":"PI",
   "nomemunicipio":"ANGICAL DO PIAUI",
   "codigoexterno":1011
},
{
   "siglaestado":"PI",
   "nomemunicipio":"ANISIO DE ABREU",
   "codigoexterno":1013
},
{
   "siglaestado":"PI",
   "nomemunicipio":"ANTONIO ALMEIDA",
   "codigoexterno":1015
},
{
   "siglaestado":"PI",
   "nomemunicipio":"AROAZES",
   "codigoexterno":1017
},
{
   "siglaestado":"PI",
   "nomemunicipio":"AROEIRAS DO ITAIM",
   "codigoexterno":1188
},
{
   "siglaestado":"PI",
   "nomemunicipio":"ARRAIAL",
   "codigoexterno":1019
},
{
   "siglaestado":"PI",
   "nomemunicipio":"ASSUNCAO DO PIAUI",
   "codigoexterno":270
},
{
   "siglaestado":"PI",
   "nomemunicipio":"AVELINO LOPES",
   "codigoexterno":1021
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BAIXA GRANDE DO RIBEIRO",
   "codigoexterno":2245
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BARRA D\'ALCANTARA",
   "codigoexterno":272
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BARRAS",
   "codigoexterno":1023
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BARREIRAS DO PIAUI",
   "codigoexterno":1025
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BARRO DURO",
   "codigoexterno":1027
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BATALHA",
   "codigoexterno":1029
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BELA VISTA DO PIAUI",
   "codigoexterno":274
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BELEM DO PIAUI",
   "codigoexterno":276
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BENEDITINOS",
   "codigoexterno":1031
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BERTOLINIA",
   "codigoexterno":1033
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BETANIA DO PIAUI",
   "codigoexterno":278
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BOA HORA",
   "codigoexterno":280
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BOCAINA",
   "codigoexterno":1035
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BOM JESUS",
   "codigoexterno":1037
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BOM PRINCIPIO DO PIAUI",
   "codigoexterno":2287
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BONFIM DO PIAUI",
   "codigoexterno":2251
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BOQUEIRAO DO PIAUI",
   "codigoexterno":282
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BRASILEIRA",
   "codigoexterno":2283
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BREJO DO PIAUI",
   "codigoexterno":284
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BURITI DOS LOPES",
   "codigoexterno":1039
},
{
   "siglaestado":"PI",
   "nomemunicipio":"BURITI DOS MONTES",
   "codigoexterno":1297
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CABECEIRAS DO PIAUI",
   "codigoexterno":1299
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CAJAZEIRAS DO PIAUI",
   "codigoexterno":286
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CAJUEIRO DA PRAIA",
   "codigoexterno":288
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CALDEIRAO GRANDE DO PIAUI",
   "codigoexterno":2271
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CAMPINAS DO PIAUI",
   "codigoexterno":1041
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CAMPO ALEGRE DO FIDALGO",
   "codigoexterno":290
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CAMPO GRANDE DO PIAUI",
   "codigoexterno":292
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CAMPO LARGO DO PIAUI",
   "codigoexterno":294
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CAMPO MAIOR",
   "codigoexterno":1043
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CANAVIEIRA",
   "codigoexterno":2247
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CANTO DO BURITI",
   "codigoexterno":1045
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CAPITAO DE CAMPOS",
   "codigoexterno":1047
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CAPITAO GERVASIO OLIVEIRA",
   "codigoexterno":296
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CARACOL",
   "codigoexterno":1049
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CARAUBAS DO PIAUI",
   "codigoexterno":298
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CARIDADE DO PIAUI",
   "codigoexterno":300
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CASTELO DO PIAUI",
   "codigoexterno":1051
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CAXINGO",
   "codigoexterno":302
},
{
   "siglaestado":"PI",
   "nomemunicipio":"COCAL",
   "codigoexterno":1053
},
{
   "siglaestado":"PI",
   "nomemunicipio":"COCAL DE TELHA",
   "codigoexterno":304
},
{
   "siglaestado":"PI",
   "nomemunicipio":"COCAL DOS ALVES",
   "codigoexterno":306
},
{
   "siglaestado":"PI",
   "nomemunicipio":"COIVARAS",
   "codigoexterno":995
},
{
   "siglaestado":"PI",
   "nomemunicipio":"COLONIA DO GURGUEIA",
   "codigoexterno":2249
},
{
   "siglaestado":"PI",
   "nomemunicipio":"COLONIA DO PIAUI",
   "codigoexterno":2253
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CONCEICAO DO CANINDE",
   "codigoexterno":1055
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CORONEL JOSE DIAS",
   "codigoexterno":2255
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CORRENTE",
   "codigoexterno":1057
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CRISTALANDIA DO PIAUI",
   "codigoexterno":1059
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CRISTINO CASTRO",
   "codigoexterno":1061
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CURIMATA",
   "codigoexterno":1063
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CURRAIS",
   "codigoexterno":308
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CURRAL NOVO DO PIAUI",
   "codigoexterno":312
},
{
   "siglaestado":"PI",
   "nomemunicipio":"CURRALINHOS",
   "codigoexterno":310
},
{
   "siglaestado":"PI",
   "nomemunicipio":"DEMERVAL LOBAO",
   "codigoexterno":1065
},
{
   "siglaestado":"PI",
   "nomemunicipio":"DIRCEU ARCOVERDE",
   "codigoexterno":1229
},
{
   "siglaestado":"PI",
   "nomemunicipio":"DOM EXPEDITO LOPES",
   "codigoexterno":1067
},
{
   "siglaestado":"PI",
   "nomemunicipio":"DOM INOCENCIO",
   "codigoexterno":1289
},
{
   "siglaestado":"PI",
   "nomemunicipio":"DOMINGOS MOURAO",
   "codigoexterno":1141
},
{
   "siglaestado":"PI",
   "nomemunicipio":"ELESBAO VELOSO",
   "codigoexterno":1069
},
{
   "siglaestado":"PI",
   "nomemunicipio":"ELISEU MARTINS",
   "codigoexterno":1071
},
{
   "siglaestado":"PI",
   "nomemunicipio":"ESPERANTINA",
   "codigoexterno":1073
},
{
   "siglaestado":"PI",
   "nomemunicipio":"FARTURA DO PIAUI",
   "codigoexterno":2257
},
{
   "siglaestado":"PI",
   "nomemunicipio":"FLORES DO PIAUI",
   "codigoexterno":1075
},
{
   "siglaestado":"PI",
   "nomemunicipio":"FLORESTA DO PIAUI",
   "codigoexterno":314
},
{
   "siglaestado":"PI",
   "nomemunicipio":"FLORIANO",
   "codigoexterno":1077
},
{
   "siglaestado":"PI",
   "nomemunicipio":"FRANCINOPOLIS",
   "codigoexterno":1079
},
{
   "siglaestado":"PI",
   "nomemunicipio":"FRANCISCO AYRES",
   "codigoexterno":1081
},
{
   "siglaestado":"PI",
   "nomemunicipio":"FRANCISCO MACEDO",
   "codigoexterno":316
},
{
   "siglaestado":"PI",
   "nomemunicipio":"FRANCISCO SANTOS",
   "codigoexterno":1083
},
{
   "siglaestado":"PI",
   "nomemunicipio":"FRONTEIRAS",
   "codigoexterno":1085
},
{
   "siglaestado":"PI",
   "nomemunicipio":"GEMINIANO",
   "codigoexterno":318
},
{
   "siglaestado":"PI",
   "nomemunicipio":"GILBUES",
   "codigoexterno":1087
},
{
   "siglaestado":"PI",
   "nomemunicipio":"GUADALUPE",
   "codigoexterno":1089
},
{
   "siglaestado":"PI",
   "nomemunicipio":"GUARIBAS",
   "codigoexterno":320
},
{
   "siglaestado":"PI",
   "nomemunicipio":"HUGO NAPOLEAO",
   "codigoexterno":1091
},
{
   "siglaestado":"PI",
   "nomemunicipio":"ILHA GRANDE",
   "codigoexterno":322
},
{
   "siglaestado":"PI",
   "nomemunicipio":"INHUMA",
   "codigoexterno":1093
},
{
   "siglaestado":"PI",
   "nomemunicipio":"IPIRANGA DO PIAUI",
   "codigoexterno":1095
},
{
   "siglaestado":"PI",
   "nomemunicipio":"ISAIAS COELHO",
   "codigoexterno":1097
},
{
   "siglaestado":"PI",
   "nomemunicipio":"ITAINOPOLIS",
   "codigoexterno":1099
},
{
   "siglaestado":"PI",
   "nomemunicipio":"ITAUEIRA",
   "codigoexterno":1101
},
{
   "siglaestado":"PI",
   "nomemunicipio":"JACOBINA DO PIAUI",
   "codigoexterno":2273
},
{
   "siglaestado":"PI",
   "nomemunicipio":"JAICOS",
   "codigoexterno":1103
},
{
   "siglaestado":"PI",
   "nomemunicipio":"JARDIM DO MULATO",
   "codigoexterno":997
},
{
   "siglaestado":"PI",
   "nomemunicipio":"JATOBA DO PIAUI",
   "codigoexterno":324
},
{
   "siglaestado":"PI",
   "nomemunicipio":"JERUMENHA",
   "codigoexterno":1105
},
{
   "siglaestado":"PI",
   "nomemunicipio":"JOAO COSTA",
   "codigoexterno":326
},
{
   "siglaestado":"PI",
   "nomemunicipio":"JOAQUIM PIRES",
   "codigoexterno":1107
},
{
   "siglaestado":"PI",
   "nomemunicipio":"JOCA MARQUES",
   "codigoexterno":328
},
{
   "siglaestado":"PI",
   "nomemunicipio":"JOSE DE FREITAS",
   "codigoexterno":1109
},
{
   "siglaestado":"PI",
   "nomemunicipio":"JUAZEIRO DO PIAUI",
   "codigoexterno":330
},
{
   "siglaestado":"PI",
   "nomemunicipio":"JULIO BORGES",
   "codigoexterno":332
},
{
   "siglaestado":"PI",
   "nomemunicipio":"JUREMA",
   "codigoexterno":334
},
{
   "siglaestado":"PI",
   "nomemunicipio":"LAGOA ALEGRE",
   "codigoexterno":999
},
{
   "siglaestado":"PI",
   "nomemunicipio":"LAGOA DE SAO FRANCISCO",
   "codigoexterno":338
},
{
   "siglaestado":"PI",
   "nomemunicipio":"LAGOA DO BARRO DO PIAUI",
   "codigoexterno":2259
},
{
   "siglaestado":"PI",
   "nomemunicipio":"LAGOA DO PIAUI",
   "codigoexterno":340
},
{
   "siglaestado":"PI",
   "nomemunicipio":"LAGOA DO SITIO",
   "codigoexterno":342
},
{
   "siglaestado":"PI",
   "nomemunicipio":"LAGOINHA DO PIAUI",
   "codigoexterno":336
},
{
   "siglaestado":"PI",
   "nomemunicipio":"LANDRI SALES",
   "codigoexterno":1111
},
{
   "siglaestado":"PI",
   "nomemunicipio":"LUIS CORREIA",
   "codigoexterno":1113
},
{
   "siglaestado":"PI",
   "nomemunicipio":"LUZILANDIA",
   "codigoexterno":1115
},
{
   "siglaestado":"PI",
   "nomemunicipio":"MADEIRO",
   "codigoexterno":344
},
{
   "siglaestado":"PI",
   "nomemunicipio":"MANOEL EMIDIO",
   "codigoexterno":1117
},
{
   "siglaestado":"PI",
   "nomemunicipio":"MARCOLANDIA",
   "codigoexterno":2275
},
{
   "siglaestado":"PI",
   "nomemunicipio":"MARCOS PARENTE",
   "codigoexterno":1119
},
{
   "siglaestado":"PI",
   "nomemunicipio":"MASSAPE DO PIAUI",
   "codigoexterno":346
},
{
   "siglaestado":"PI",
   "nomemunicipio":"MATIAS OLIMPIO",
   "codigoexterno":1121
},
{
   "siglaestado":"PI",
   "nomemunicipio":"MIGUEL ALVES",
   "codigoexterno":1123
},
{
   "siglaestado":"PI",
   "nomemunicipio":"MIGUEL LEAO",
   "codigoexterno":1125
},
{
   "siglaestado":"PI",
   "nomemunicipio":"MILTON BRANDAO",
   "codigoexterno":348
},
{
   "siglaestado":"PI",
   "nomemunicipio":"MONSENHOR GIL",
   "codigoexterno":1127
},
{
   "siglaestado":"PI",
   "nomemunicipio":"MONSENHOR HIPOLITO",
   "codigoexterno":1129
},
{
   "siglaestado":"PI",
   "nomemunicipio":"MONTE ALEGRE DO PIAUI",
   "codigoexterno":1131
},
{
   "siglaestado":"PI",
   "nomemunicipio":"MORRO CABECA NO TEMPO",
   "codigoexterno":350
},
{
   "siglaestado":"PI",
   "nomemunicipio":"MORRO DO CHAPEU DO PIAUI",
   "codigoexterno":352
},
{
   "siglaestado":"PI",
   "nomemunicipio":"MURICI DOS PORTELAS",
   "codigoexterno":354
},
{
   "siglaestado":"PI",
   "nomemunicipio":"NAZARE DO PIAUI",
   "codigoexterno":1133
},
{
   "siglaestado":"PI",
   "nomemunicipio":"NAZARIA",
   "codigoexterno":1180
},
{
   "siglaestado":"PI",
   "nomemunicipio":"NOSSA SENHORA DE NAZARE",
   "codigoexterno":356
},
{
   "siglaestado":"PI",
   "nomemunicipio":"NOSSA SENHORA DOS REMEDIOS",
   "codigoexterno":1135
},
{
   "siglaestado":"PI",
   "nomemunicipio":"NOVA SANTA RITA",
   "codigoexterno":370
},
{
   "siglaestado":"PI",
   "nomemunicipio":"NOVO ORIENTE DO PIAUI",
   "codigoexterno":1137
},
{
   "siglaestado":"PI",
   "nomemunicipio":"NOVO SANTO ANTONIO",
   "codigoexterno":358
},
{
   "siglaestado":"PI",
   "nomemunicipio":"OEIRAS",
   "codigoexterno":1139
},
{
   "siglaestado":"PI",
   "nomemunicipio":"OLHO D\'AGUA DO PIAUI",
   "codigoexterno":360
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PADRE MARCOS",
   "codigoexterno":1143
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PAES LANDIM",
   "codigoexterno":1145
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PAJEU DO PIAUI",
   "codigoexterno":362
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PALMEIRA DO PIAUI",
   "codigoexterno":1147
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PALMEIRAIS",
   "codigoexterno":1149
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PAQUETA",
   "codigoexterno":364
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PARNAGUA",
   "codigoexterno":1151
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PARNAIBA",
   "codigoexterno":1153
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PASSAGEM FRANCA DO PIAUI",
   "codigoexterno":1293
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PATOS DO PIAUI",
   "codigoexterno":2277
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PAU D´ARCO DO PIAU??",
   "codigoexterno":1104
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PAULISTANA",
   "codigoexterno":1155
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PAVUSSU",
   "codigoexterno":366
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PEDRO II",
   "codigoexterno":1157
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PEDRO LAURENTINO",
   "codigoexterno":368
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PICOS",
   "codigoexterno":1159
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PIMENTEIRAS",
   "codigoexterno":1161
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PIO IX",
   "codigoexterno":1163
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PIRACURUCA",
   "codigoexterno":1165
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PIRIPIRI",
   "codigoexterno":1167
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PORTO",
   "codigoexterno":1169
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PORTO ALEGRE DO PIAUI",
   "codigoexterno":372
},
{
   "siglaestado":"PI",
   "nomemunicipio":"PRATA DO PIAUI",
   "codigoexterno":1171
},
{
   "siglaestado":"PI",
   "nomemunicipio":"QUEIMADA NOVA",
   "codigoexterno":2279
},
{
   "siglaestado":"PI",
   "nomemunicipio":"REDENCAO DO GURGUEIA",
   "codigoexterno":1173
},
{
   "siglaestado":"PI",
   "nomemunicipio":"REGENERACAO",
   "codigoexterno":1175
},
{
   "siglaestado":"PI",
   "nomemunicipio":"RIACHO FRIO",
   "codigoexterno":374
},
{
   "siglaestado":"PI",
   "nomemunicipio":"RIBEIRA DO PIAUI",
   "codigoexterno":376
},
{
   "siglaestado":"PI",
   "nomemunicipio":"RIBEIRO GONCALVES",
   "codigoexterno":1177
},
{
   "siglaestado":"PI",
   "nomemunicipio":"RIO GRANDE DO PIAUI",
   "codigoexterno":1179
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SANTA CRUZ DO PIAUI",
   "codigoexterno":1181
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SANTA CRUZ DOS MILAGRES",
   "codigoexterno":1295
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SANTA FILOMENA",
   "codigoexterno":1183
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SANTA LUZ",
   "codigoexterno":1185
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SANTA ROSA DO PIAUI",
   "codigoexterno":2261
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SANTANA DO PIAUI",
   "codigoexterno":2281
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SANTO ANTONIO DE LISBOA",
   "codigoexterno":1187
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SANTO ANTONIO DOS MILAGRES",
   "codigoexterno":378
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SANTO INACIO DO PIAUI",
   "codigoexterno":1189
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO BRAZ DO PIAUI",
   "codigoexterno":2263
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO FELIX DO PIAUI",
   "codigoexterno":1191
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO FRANCISCO DE ASSIS DO PIAUI",
   "codigoexterno":380
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO FRANCISCO DO PIAUI",
   "codigoexterno":1193
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO GONCALO DO GURGUEIA",
   "codigoexterno":382
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO GONCALO DO PIAUI",
   "codigoexterno":1195
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO JOAO DA CANABRAVA",
   "codigoexterno":1291
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO JOAO DA FRONTEIRA",
   "codigoexterno":384
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO JOAO DA SERRA",
   "codigoexterno":1197
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO JOAO DA VARJOTA",
   "codigoexterno":386
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO JOAO DO ARRAIAL",
   "codigoexterno":388
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO JOAO DO PIAUI",
   "codigoexterno":1199
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO JOSE DO DIVINO",
   "codigoexterno":2285
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO JOSE DO PEIXE",
   "codigoexterno":1201
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO JOSE DO PIAUI",
   "codigoexterno":1203
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO JULIAO",
   "codigoexterno":1205
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO LOURENCO DO PIAUI",
   "codigoexterno":2265
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO LUIS DO PIAUI",
   "codigoexterno":390
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO MIGUEL DA BAIXA GRANDE",
   "codigoexterno":392
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO MIGUEL DO FIDALGO",
   "codigoexterno":394
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO MIGUEL DO TAPUIO",
   "codigoexterno":1207
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO PEDRO DO PIAUI",
   "codigoexterno":1209
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SAO RAIMUNDO NONATO",
   "codigoexterno":1211
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SEBASTIAO BARROS",
   "codigoexterno":396
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SEBASTIAO LEAL",
   "codigoexterno":398
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SIGEFREDO PACHECO",
   "codigoexterno":1379
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SIMOES",
   "codigoexterno":1213
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SIMPLICIO MENDES",
   "codigoexterno":1215
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SOCORRO DO PIAUI",
   "codigoexterno":1217
},
{
   "siglaestado":"PI",
   "nomemunicipio":"SUSSUAPARA",
   "codigoexterno":400
},
{
   "siglaestado":"PI",
   "nomemunicipio":"TAMBORIL DO PIAUI",
   "codigoexterno":402
},
{
   "siglaestado":"PI",
   "nomemunicipio":"TANQUE DO PIAUI",
   "codigoexterno":404
},
{
   "siglaestado":"PI",
   "nomemunicipio":"TERESINA",
   "codigoexterno":1219
},
{
   "siglaestado":"PI",
   "nomemunicipio":"UNIAO",
   "codigoexterno":1221
},
{
   "siglaestado":"PI",
   "nomemunicipio":"URUCUI",
   "codigoexterno":1223
},
{
   "siglaestado":"PI",
   "nomemunicipio":"VALENCA DO PIAUI",
   "codigoexterno":1225
},
{
   "siglaestado":"PI",
   "nomemunicipio":"VARZEA BRANCA",
   "codigoexterno":2267
},
{
   "siglaestado":"PI",
   "nomemunicipio":"VARZEA GRANDE",
   "codigoexterno":1227
},
{
   "siglaestado":"PI",
   "nomemunicipio":"VERA MENDES",
   "codigoexterno":406
},
{
   "siglaestado":"PI",
   "nomemunicipio":"VILA NOVA DO PIAUI",
   "codigoexterno":408
},
{
   "siglaestado":"PI",
   "nomemunicipio":"WALL FERRAZ",
   "codigoexterno":410
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ABATIA",
   "codigoexterno":7401
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ADRIANOPOLIS",
   "codigoexterno":7403
},
{
   "siglaestado":"PR",
   "nomemunicipio":"AGUDOS DO SUL",
   "codigoexterno":7405
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ALMIRANTE TAMANDARE",
   "codigoexterno":7407
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ALTAMIRA DO PARANA",
   "codigoexterno":8455
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ALTO PARAISO",
   "codigoexterno":5523
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ALTO PARANA",
   "codigoexterno":7409
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ALTO PIQUIRI",
   "codigoexterno":7411
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ALTONIA",
   "codigoexterno":7951
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ALVORADA DO SUL",
   "codigoexterno":7413
},
{
   "siglaestado":"PR",
   "nomemunicipio":"AMAPORA",
   "codigoexterno":7415
},
{
   "siglaestado":"PR",
   "nomemunicipio":"AMPERE",
   "codigoexterno":7417
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ANAHY",
   "codigoexterno":5463
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ANDIRA",
   "codigoexterno":7419
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ANGULO",
   "codigoexterno":5509
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ANTONINA",
   "codigoexterno":7421
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ANTONIO OLINTO",
   "codigoexterno":7423
},
{
   "siglaestado":"PR",
   "nomemunicipio":"APUCARANA",
   "codigoexterno":7425
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ARAPONGAS",
   "codigoexterno":7427
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ARAPOTI",
   "codigoexterno":7429
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ARAPUA",
   "codigoexterno":830
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ARARUNA",
   "codigoexterno":7431
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ARAUCARIA",
   "codigoexterno":7435
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ARIRANHA DO IVAI",
   "codigoexterno":832
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ASSAI",
   "codigoexterno":7437
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ASSIS CHATEAUBRIAND",
   "codigoexterno":7953
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ASTORGA",
   "codigoexterno":7439
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ATALAIA",
   "codigoexterno":7441
},
{
   "siglaestado":"PR",
   "nomemunicipio":"BALSA NOVA",
   "codigoexterno":7443
},
{
   "siglaestado":"PR",
   "nomemunicipio":"BANDEIRANTES",
   "codigoexterno":7445
},
{
   "siglaestado":"PR",
   "nomemunicipio":"BARBOSA FERRAZ",
   "codigoexterno":7447
},
{
   "siglaestado":"PR",
   "nomemunicipio":"BARRA DO JACARE",
   "codigoexterno":7451
},
{
   "siglaestado":"PR",
   "nomemunicipio":"BARRACAO",
   "codigoexterno":7449
},
{
   "siglaestado":"PR",
   "nomemunicipio":"BELA VISTA DO CAROBA",
   "codigoexterno":834
},
{
   "siglaestado":"PR",
   "nomemunicipio":"BELA VISTA DO PARAISO",
   "codigoexterno":7453
},
{
   "siglaestado":"PR",
   "nomemunicipio":"BITURUNA",
   "codigoexterno":7455
},
{
   "siglaestado":"PR",
   "nomemunicipio":"BOA ESPERANCA",
   "codigoexterno":7457
},
{
   "siglaestado":"PR",
   "nomemunicipio":"BOA ESPERANCA DO IGUACU",
   "codigoexterno":5471
},
{
   "siglaestado":"PR",
   "nomemunicipio":"BOA VENTURA DE SAO ROQUE",
   "codigoexterno":836
},
{
   "siglaestado":"PR",
   "nomemunicipio":"BOA VISTA DA APARECIDA",
   "codigoexterno":7981
},
{
   "siglaestado":"PR",
   "nomemunicipio":"BOCAIUVA DO SUL",
   "codigoexterno":7459
},
{
   "siglaestado":"PR",
   "nomemunicipio":"BOM JESUS DO SUL",
   "codigoexterno":838
},
{
   "siglaestado":"PR",
   "nomemunicipio":"BOM SUCESSO",
   "codigoexterno":7461
},
{
   "siglaestado":"PR",
   "nomemunicipio":"BOM SUCESSO DO SUL",
   "codigoexterno":9979
},
{
   "siglaestado":"PR",
   "nomemunicipio":"BORRAZOPOLIS",
   "codigoexterno":7463
},
{
   "siglaestado":"PR",
   "nomemunicipio":"BRAGANEY",
   "codigoexterno":7983
},
{
   "siglaestado":"PR",
   "nomemunicipio":"BRASILANDIA DO SUL",
   "codigoexterno":5521
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CAFEARA",
   "codigoexterno":7465
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CAFELANDIA",
   "codigoexterno":7985
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CAFEZAL DO SUL",
   "codigoexterno":5491
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CALIFORNIA",
   "codigoexterno":7467
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CAMBARA",
   "codigoexterno":7469
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CAMBE",
   "codigoexterno":7471
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CAMBIRA",
   "codigoexterno":7473
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CAMPINA DA LAGOA",
   "codigoexterno":7475
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CAMPINA DO SIMAO",
   "codigoexterno":840
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CAMPINA GRANDE DO SUL",
   "codigoexterno":7477
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CAMPO BONITO",
   "codigoexterno":8475
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CAMPO DO TENENTE",
   "codigoexterno":7479
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CAMPO LARGO",
   "codigoexterno":7481
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CAMPO MAGRO",
   "codigoexterno":842
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CAMPO MOURAO",
   "codigoexterno":7483
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CANDIDO DE ABREU",
   "codigoexterno":7485
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CANDOI",
   "codigoexterno":5499
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CANTAGALO",
   "codigoexterno":8451
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CAPANEMA",
   "codigoexterno":7487
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CAPITAO LEONIDAS MARQUES",
   "codigoexterno":7489
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CARAMBEI",
   "codigoexterno":844
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CARLOPOLIS",
   "codigoexterno":7491
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CASCAVEL",
   "codigoexterno":7493
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CASTRO",
   "codigoexterno":7495
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CATANDUVAS",
   "codigoexterno":7497
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CENTENARIO DO SUL",
   "codigoexterno":7499
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CERRO AZUL",
   "codigoexterno":7501
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CEU AZUL",
   "codigoexterno":7957
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CHOPINZINHO",
   "codigoexterno":7503
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CIANORTE",
   "codigoexterno":7505
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CIDADE GAUCHA",
   "codigoexterno":7507
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CLEVELANDIA",
   "codigoexterno":7509
},
{
   "siglaestado":"PR",
   "nomemunicipio":"COLOMBO",
   "codigoexterno":7513
},
{
   "siglaestado":"PR",
   "nomemunicipio":"COLORADO",
   "codigoexterno":7515
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CONGONHINHAS",
   "codigoexterno":7517
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CONSELHEIRO MAIRINCK",
   "codigoexterno":7519
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CONTENDA",
   "codigoexterno":7521
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CORBELIA",
   "codigoexterno":7523
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CORNELIO PROCOPIO",
   "codigoexterno":7525
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CORONEL DOMINGOS SOARES",
   "codigoexterno":846
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CORONEL VIVIDA",
   "codigoexterno":7527
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CORUMBATAI DO SUL",
   "codigoexterno":8479
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CRUZ MACHADO",
   "codigoexterno":7533
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CRUZEIRO DO IGUACU",
   "codigoexterno":5473
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CRUZEIRO DO OESTE",
   "codigoexterno":7529
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CRUZEIRO DO SUL",
   "codigoexterno":7531
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CRUZMALTINA",
   "codigoexterno":848
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CURITIBA",
   "codigoexterno":7535
},
{
   "siglaestado":"PR",
   "nomemunicipio":"CURIUVA",
   "codigoexterno":7537
},
{
   "siglaestado":"PR",
   "nomemunicipio":"DIAMANTE DO NORTE",
   "codigoexterno":7539
},
{
   "siglaestado":"PR",
   "nomemunicipio":"DIAMANTE DO SUL",
   "codigoexterno":5465
},
{
   "siglaestado":"PR",
   "nomemunicipio":"DIAMANTE D\'OESTE",
   "codigoexterno":9915
},
{
   "siglaestado":"PR",
   "nomemunicipio":"DOIS VIZINHOS",
   "codigoexterno":7541
},
{
   "siglaestado":"PR",
   "nomemunicipio":"DOURADINA",
   "codigoexterno":8465
},
{
   "siglaestado":"PR",
   "nomemunicipio":"DOUTOR CAMARGO",
   "codigoexterno":7543
},
{
   "siglaestado":"PR",
   "nomemunicipio":"DOUTOR ULYSSES",
   "codigoexterno":5449
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ENEAS MARQUES",
   "codigoexterno":7545
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ENGENHEIRO BELTRAO",
   "codigoexterno":7547
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ENTRE RIOS DO OESTE",
   "codigoexterno":5529
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ESPERANCA NOVA",
   "codigoexterno":850
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ESPIGAO ALTO DO IGUACU",
   "codigoexterno":852
},
{
   "siglaestado":"PR",
   "nomemunicipio":"FAROL",
   "codigoexterno":5511
},
{
   "siglaestado":"PR",
   "nomemunicipio":"FAXINAL",
   "codigoexterno":7549
},
{
   "siglaestado":"PR",
   "nomemunicipio":"FAZENDA RIO GRANDE",
   "codigoexterno":9983
},
{
   "siglaestado":"PR",
   "nomemunicipio":"FENIX",
   "codigoexterno":7551
},
{
   "siglaestado":"PR",
   "nomemunicipio":"FERNANDES PINHEIRO",
   "codigoexterno":854
},
{
   "siglaestado":"PR",
   "nomemunicipio":"FIGUEIRA",
   "codigoexterno":8457
},
{
   "siglaestado":"PR",
   "nomemunicipio":"FLOR DA SERRA DO SUL",
   "codigoexterno":5475
},
{
   "siglaestado":"PR",
   "nomemunicipio":"FLORAI",
   "codigoexterno":7553
},
{
   "siglaestado":"PR",
   "nomemunicipio":"FLORESTA",
   "codigoexterno":7555
},
{
   "siglaestado":"PR",
   "nomemunicipio":"FLORESTOPOLIS",
   "codigoexterno":7557
},
{
   "siglaestado":"PR",
   "nomemunicipio":"FLORIDA",
   "codigoexterno":7559
},
{
   "siglaestado":"PR",
   "nomemunicipio":"FORMOSA DO OESTE",
   "codigoexterno":7561
},
{
   "siglaestado":"PR",
   "nomemunicipio":"FOZ DO IGUACU",
   "codigoexterno":7563
},
{
   "siglaestado":"PR",
   "nomemunicipio":"FOZ DO JORDAO",
   "codigoexterno":856
},
{
   "siglaestado":"PR",
   "nomemunicipio":"FRANCISCO ALVES",
   "codigoexterno":7977
},
{
   "siglaestado":"PR",
   "nomemunicipio":"FRANCISCO BELTRAO",
   "codigoexterno":7565
},
{
   "siglaestado":"PR",
   "nomemunicipio":"GENERAL CARNEIRO",
   "codigoexterno":7567
},
{
   "siglaestado":"PR",
   "nomemunicipio":"GODOY MOREIRA",
   "codigoexterno":9947
},
{
   "siglaestado":"PR",
   "nomemunicipio":"GOIOERE",
   "codigoexterno":7569
},
{
   "siglaestado":"PR",
   "nomemunicipio":"GOIOXIM",
   "codigoexterno":858
},
{
   "siglaestado":"PR",
   "nomemunicipio":"GRANDES RIOS",
   "codigoexterno":7959
},
{
   "siglaestado":"PR",
   "nomemunicipio":"GUAIRA",
   "codigoexterno":7571
},
{
   "siglaestado":"PR",
   "nomemunicipio":"GUAIRACA",
   "codigoexterno":7573
},
{
   "siglaestado":"PR",
   "nomemunicipio":"GUAMIRANGA",
   "codigoexterno":860
},
{
   "siglaestado":"PR",
   "nomemunicipio":"GUAPIRAMA",
   "codigoexterno":7575
},
{
   "siglaestado":"PR",
   "nomemunicipio":"GUAPOREMA",
   "codigoexterno":7577
},
{
   "siglaestado":"PR",
   "nomemunicipio":"GUARACI",
   "codigoexterno":7579
},
{
   "siglaestado":"PR",
   "nomemunicipio":"GUARANIACU",
   "codigoexterno":7581
},
{
   "siglaestado":"PR",
   "nomemunicipio":"GUARAPUAVA",
   "codigoexterno":7583
},
{
   "siglaestado":"PR",
   "nomemunicipio":"GUARAQUECABA",
   "codigoexterno":7585
},
{
   "siglaestado":"PR",
   "nomemunicipio":"GUARATUBA",
   "codigoexterno":7587
},
{
   "siglaestado":"PR",
   "nomemunicipio":"HONORIO SERPA",
   "codigoexterno":9981
},
{
   "siglaestado":"PR",
   "nomemunicipio":"IBAITI",
   "codigoexterno":7589
},
{
   "siglaestado":"PR",
   "nomemunicipio":"IBEMA",
   "codigoexterno":9949
},
{
   "siglaestado":"PR",
   "nomemunicipio":"IBIPORA",
   "codigoexterno":7591
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ICARAIMA",
   "codigoexterno":7593
},
{
   "siglaestado":"PR",
   "nomemunicipio":"IGUARACU",
   "codigoexterno":7595
},
{
   "siglaestado":"PR",
   "nomemunicipio":"IGUATU",
   "codigoexterno":5467
},
{
   "siglaestado":"PR",
   "nomemunicipio":"IMBAU",
   "codigoexterno":862
},
{
   "siglaestado":"PR",
   "nomemunicipio":"IMBITUVA",
   "codigoexterno":7597
},
{
   "siglaestado":"PR",
   "nomemunicipio":"INACIO MARTINS",
   "codigoexterno":7599
},
{
   "siglaestado":"PR",
   "nomemunicipio":"INAJA",
   "codigoexterno":7601
},
{
   "siglaestado":"PR",
   "nomemunicipio":"INDIANOPOLIS",
   "codigoexterno":7961
},
{
   "siglaestado":"PR",
   "nomemunicipio":"IPIRANGA",
   "codigoexterno":7603
},
{
   "siglaestado":"PR",
   "nomemunicipio":"IPORA",
   "codigoexterno":7605
},
{
   "siglaestado":"PR",
   "nomemunicipio":"IRACEMA DO OESTE",
   "codigoexterno":5485
},
{
   "siglaestado":"PR",
   "nomemunicipio":"IRATI",
   "codigoexterno":7607
},
{
   "siglaestado":"PR",
   "nomemunicipio":"IRETAMA",
   "codigoexterno":7609
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ITAGUAJE",
   "codigoexterno":7611
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ITAIPULANDIA",
   "codigoexterno":5525
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ITAMBARACA",
   "codigoexterno":7613
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ITAMBE",
   "codigoexterno":7615
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ITAPEJARA D\'OESTE",
   "codigoexterno":7617
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ITAPERUCU",
   "codigoexterno":5451
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ITAUNA DO SUL",
   "codigoexterno":7619
},
{
   "siglaestado":"PR",
   "nomemunicipio":"IVAI",
   "codigoexterno":7621
},
{
   "siglaestado":"PR",
   "nomemunicipio":"IVAIPORA",
   "codigoexterno":7623
},
{
   "siglaestado":"PR",
   "nomemunicipio":"IVATE",
   "codigoexterno":9955
},
{
   "siglaestado":"PR",
   "nomemunicipio":"IVATUBA",
   "codigoexterno":7625
},
{
   "siglaestado":"PR",
   "nomemunicipio":"JABOTI",
   "codigoexterno":7627
},
{
   "siglaestado":"PR",
   "nomemunicipio":"JACAREZINHO",
   "codigoexterno":7629
},
{
   "siglaestado":"PR",
   "nomemunicipio":"JAGUAPITA",
   "codigoexterno":7631
},
{
   "siglaestado":"PR",
   "nomemunicipio":"JAGUARIAIVA",
   "codigoexterno":7633
},
{
   "siglaestado":"PR",
   "nomemunicipio":"JANDAIA DO SUL",
   "codigoexterno":7635
},
{
   "siglaestado":"PR",
   "nomemunicipio":"JANIOPOLIS",
   "codigoexterno":7637
},
{
   "siglaestado":"PR",
   "nomemunicipio":"JAPIRA",
   "codigoexterno":7639
},
{
   "siglaestado":"PR",
   "nomemunicipio":"JAPURA",
   "codigoexterno":7641
},
{
   "siglaestado":"PR",
   "nomemunicipio":"JARDIM ALEGRE",
   "codigoexterno":7643
},
{
   "siglaestado":"PR",
   "nomemunicipio":"JARDIM OLINDA",
   "codigoexterno":7645
},
{
   "siglaestado":"PR",
   "nomemunicipio":"JATAIZINHO",
   "codigoexterno":7647
},
{
   "siglaestado":"PR",
   "nomemunicipio":"JESUITAS",
   "codigoexterno":7997
},
{
   "siglaestado":"PR",
   "nomemunicipio":"JOAQUIM TAVORA",
   "codigoexterno":7649
},
{
   "siglaestado":"PR",
   "nomemunicipio":"JUNDIAI DO SUL",
   "codigoexterno":7651
},
{
   "siglaestado":"PR",
   "nomemunicipio":"JURANDA",
   "codigoexterno":8463
},
{
   "siglaestado":"PR",
   "nomemunicipio":"JUSSARA",
   "codigoexterno":7653
},
{
   "siglaestado":"PR",
   "nomemunicipio":"KALORE",
   "codigoexterno":7655
},
{
   "siglaestado":"PR",
   "nomemunicipio":"LAPA",
   "codigoexterno":7657
},
{
   "siglaestado":"PR",
   "nomemunicipio":"LARANJAL",
   "codigoexterno":5501
},
{
   "siglaestado":"PR",
   "nomemunicipio":"LARANJEIRAS DO SUL",
   "codigoexterno":7659
},
{
   "siglaestado":"PR",
   "nomemunicipio":"LEOPOLIS",
   "codigoexterno":7661
},
{
   "siglaestado":"PR",
   "nomemunicipio":"LIDIANOPOLIS",
   "codigoexterno":5507
},
{
   "siglaestado":"PR",
   "nomemunicipio":"LINDOESTE",
   "codigoexterno":9959
},
{
   "siglaestado":"PR",
   "nomemunicipio":"LOANDA",
   "codigoexterno":7663
},
{
   "siglaestado":"PR",
   "nomemunicipio":"LOBATO",
   "codigoexterno":7665
},
{
   "siglaestado":"PR",
   "nomemunicipio":"LONDRINA",
   "codigoexterno":7667
},
{
   "siglaestado":"PR",
   "nomemunicipio":"LUIZIANA",
   "codigoexterno":8481
},
{
   "siglaestado":"PR",
   "nomemunicipio":"LUNARDELLI",
   "codigoexterno":8459
},
{
   "siglaestado":"PR",
   "nomemunicipio":"LUPIONOPOLIS",
   "codigoexterno":7669
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MALLET",
   "codigoexterno":7671
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MAMBORE",
   "codigoexterno":7673
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MANDAGUACU",
   "codigoexterno":7675
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MANDAGUARI",
   "codigoexterno":7677
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MANDIRITUBA",
   "codigoexterno":7679
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MANFRINOPOLIS",
   "codigoexterno":864
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MANGUEIRINHA",
   "codigoexterno":7511
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MANOEL RIBAS",
   "codigoexterno":7681
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MARECHAL CANDIDO RONDON",
   "codigoexterno":7683
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MARIA HELENA",
   "codigoexterno":7685
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MARIALVA",
   "codigoexterno":7687
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MARILANDIA DO SUL",
   "codigoexterno":7433
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MARILENA",
   "codigoexterno":7975
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MARILUZ",
   "codigoexterno":7689
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MARINGA",
   "codigoexterno":7691
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MARIOPOLIS",
   "codigoexterno":7693
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MARIPA",
   "codigoexterno":5487
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MARMELEIRO",
   "codigoexterno":7695
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MARQUINHO",
   "codigoexterno":866
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MARUMBI",
   "codigoexterno":7697
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MATELANDIA",
   "codigoexterno":7699
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MATINHOS",
   "codigoexterno":7963
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MATO RICO",
   "codigoexterno":5503
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MAUA DA SERRA",
   "codigoexterno":5459
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MEDIANEIRA",
   "codigoexterno":7701
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MERCEDES",
   "codigoexterno":5531
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MIRADOR",
   "codigoexterno":7703
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MIRASELVA",
   "codigoexterno":7705
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MISSAL",
   "codigoexterno":8469
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MOREIRA SALES",
   "codigoexterno":7707
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MORRETES",
   "codigoexterno":7709
},
{
   "siglaestado":"PR",
   "nomemunicipio":"MUNHOZ DE MELLO",
   "codigoexterno":7711
},
{
   "siglaestado":"PR",
   "nomemunicipio":"NOSSA SENHORA DAS GRACAS",
   "codigoexterno":7713
},
{
   "siglaestado":"PR",
   "nomemunicipio":"NOVA ALIANCA DO IVAI",
   "codigoexterno":7715
},
{
   "siglaestado":"PR",
   "nomemunicipio":"NOVA AMERICA DA COLINA",
   "codigoexterno":7717
},
{
   "siglaestado":"PR",
   "nomemunicipio":"NOVA AURORA",
   "codigoexterno":7965
},
{
   "siglaestado":"PR",
   "nomemunicipio":"NOVA CANTU",
   "codigoexterno":7719
},
{
   "siglaestado":"PR",
   "nomemunicipio":"NOVA ESPERANCA",
   "codigoexterno":7721
},
{
   "siglaestado":"PR",
   "nomemunicipio":"NOVA ESPERANCA DO SUDOESTE",
   "codigoexterno":5477
},
{
   "siglaestado":"PR",
   "nomemunicipio":"NOVA FATIMA",
   "codigoexterno":7723
},
{
   "siglaestado":"PR",
   "nomemunicipio":"NOVA LARANJEIRAS",
   "codigoexterno":5479
},
{
   "siglaestado":"PR",
   "nomemunicipio":"NOVA LONDRINA",
   "codigoexterno":7725
},
{
   "siglaestado":"PR",
   "nomemunicipio":"NOVA OLIMPIA",
   "codigoexterno":7967
},
{
   "siglaestado":"PR",
   "nomemunicipio":"NOVA PRATA DO IGUACU",
   "codigoexterno":7995
},
{
   "siglaestado":"PR",
   "nomemunicipio":"NOVA SANTA BARBARA",
   "codigoexterno":5457
},
{
   "siglaestado":"PR",
   "nomemunicipio":"NOVA SANTA ROSA",
   "codigoexterno":7979
},
{
   "siglaestado":"PR",
   "nomemunicipio":"NOVA TEBAS",
   "codigoexterno":9913
},
{
   "siglaestado":"PR",
   "nomemunicipio":"NOVO ITACOLOMI",
   "codigoexterno":5517
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ORTIGUEIRA",
   "codigoexterno":7727
},
{
   "siglaestado":"PR",
   "nomemunicipio":"OURIZONA",
   "codigoexterno":7729
},
{
   "siglaestado":"PR",
   "nomemunicipio":"OURO VERDE DO OESTE",
   "codigoexterno":9965
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PAICANDU",
   "codigoexterno":7731
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PALMAS",
   "codigoexterno":7733
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PALMEIRA",
   "codigoexterno":7735
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PALMITAL",
   "codigoexterno":7737
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PALOTINA",
   "codigoexterno":7739
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PARAISO DO NORTE",
   "codigoexterno":7741
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PARANACITY",
   "codigoexterno":7743
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PARANAGUA",
   "codigoexterno":7745
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PARANAPOEMA",
   "codigoexterno":7747
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PARANAVAI",
   "codigoexterno":7749
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PATO BRAGADO",
   "codigoexterno":5533
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PATO BRANCO",
   "codigoexterno":7751
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PAULA FREITAS",
   "codigoexterno":7753
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PAULO FRONTIN",
   "codigoexterno":7755
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PEABIRU",
   "codigoexterno":7757
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PEROBAL",
   "codigoexterno":868
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PEROLA",
   "codigoexterno":7969
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PEROLA D\'OESTE",
   "codigoexterno":7759
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PIEN",
   "codigoexterno":7761
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PINHAIS",
   "codigoexterno":5453
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PINHAL DO SAO BENTO",
   "codigoexterno":5495
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PINHALAO",
   "codigoexterno":7763
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PINHAO",
   "codigoexterno":7765
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PIRAI DO SUL",
   "codigoexterno":7767
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PIRAQUARA",
   "codigoexterno":7769
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PITANGA",
   "codigoexterno":7771
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PITANGUEIRAS",
   "codigoexterno":5461
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PLANALTINA DO PARANA",
   "codigoexterno":7773
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PLANALTO",
   "codigoexterno":7775
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PONTA GROSSA",
   "codigoexterno":7777
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PONTAL DO PARANA",
   "codigoexterno":870
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PORECATU",
   "codigoexterno":7779
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PORTO AMAZONAS",
   "codigoexterno":7781
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PORTO BARREIRO",
   "codigoexterno":872
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PORTO RICO",
   "codigoexterno":7783
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PORTO VITORIA",
   "codigoexterno":7785
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PRADO FERREIRA",
   "codigoexterno":874
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PRANCHITA",
   "codigoexterno":7991
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PRESIDENTE CASTELO BRANCO",
   "codigoexterno":7787
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PRIMEIRO DE MAIO",
   "codigoexterno":7789
},
{
   "siglaestado":"PR",
   "nomemunicipio":"PRUDENTOPOLIS",
   "codigoexterno":7791
},
{
   "siglaestado":"PR",
   "nomemunicipio":"QUARTO CENTENARIO",
   "codigoexterno":876
},
{
   "siglaestado":"PR",
   "nomemunicipio":"QUATIGUA",
   "codigoexterno":7793
},
{
   "siglaestado":"PR",
   "nomemunicipio":"QUATRO BARRAS",
   "codigoexterno":7795
},
{
   "siglaestado":"PR",
   "nomemunicipio":"QUATRO PONTES",
   "codigoexterno":5535
},
{
   "siglaestado":"PR",
   "nomemunicipio":"QUEDAS DO IGUACU",
   "codigoexterno":7955
},
{
   "siglaestado":"PR",
   "nomemunicipio":"QUERENCIA DO NORTE",
   "codigoexterno":7797
},
{
   "siglaestado":"PR",
   "nomemunicipio":"QUINTA DO SOL",
   "codigoexterno":7799
},
{
   "siglaestado":"PR",
   "nomemunicipio":"QUITANDINHA",
   "codigoexterno":7801
},
{
   "siglaestado":"PR",
   "nomemunicipio":"RAMILANDIA",
   "codigoexterno":5527
},
{
   "siglaestado":"PR",
   "nomemunicipio":"RANCHO ALEGRE",
   "codigoexterno":7803
},
{
   "siglaestado":"PR",
   "nomemunicipio":"RANCHO ALEGRE D\'OESTE",
   "codigoexterno":5513
},
{
   "siglaestado":"PR",
   "nomemunicipio":"REALEZA",
   "codigoexterno":7805
},
{
   "siglaestado":"PR",
   "nomemunicipio":"REBOUCAS",
   "codigoexterno":7807
},
{
   "siglaestado":"PR",
   "nomemunicipio":"RENASCENCA",
   "codigoexterno":7809
},
{
   "siglaestado":"PR",
   "nomemunicipio":"RESERVA",
   "codigoexterno":7811
},
{
   "siglaestado":"PR",
   "nomemunicipio":"RESERVA DO IGUACU",
   "codigoexterno":878
},
{
   "siglaestado":"PR",
   "nomemunicipio":"RIBEIRAO CLARO",
   "codigoexterno":7813
},
{
   "siglaestado":"PR",
   "nomemunicipio":"RIBEIRAO DO PINHAL",
   "codigoexterno":7815
},
{
   "siglaestado":"PR",
   "nomemunicipio":"RIO AZUL",
   "codigoexterno":7817
},
{
   "siglaestado":"PR",
   "nomemunicipio":"RIO BOM",
   "codigoexterno":7819
},
{
   "siglaestado":"PR",
   "nomemunicipio":"RIO BONITO DO IGUACU",
   "codigoexterno":5481
},
{
   "siglaestado":"PR",
   "nomemunicipio":"RIO BRANCO DO IVAI",
   "codigoexterno":880
},
{
   "siglaestado":"PR",
   "nomemunicipio":"RIO BRANCO DO SUL",
   "codigoexterno":7821
},
{
   "siglaestado":"PR",
   "nomemunicipio":"RIO NEGRO",
   "codigoexterno":7823
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ROLANDIA",
   "codigoexterno":7825
},
{
   "siglaestado":"PR",
   "nomemunicipio":"RONCADOR",
   "codigoexterno":7827
},
{
   "siglaestado":"PR",
   "nomemunicipio":"RONDON",
   "codigoexterno":7829
},
{
   "siglaestado":"PR",
   "nomemunicipio":"ROSARIO DO IVAI",
   "codigoexterno":8473
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SABAUDIA",
   "codigoexterno":7831
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SALGADO FILHO",
   "codigoexterno":7833
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SALTO DO ITARARE",
   "codigoexterno":7835
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SALTO DO LONTRA",
   "codigoexterno":7837
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTA AMELIA",
   "codigoexterno":7839
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTA CECILIA DO PAVAO",
   "codigoexterno":7841
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTA CRUZ DO MONTE CASTELO",
   "codigoexterno":7843
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTA FE",
   "codigoexterno":7845
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTA HELENA",
   "codigoexterno":7971
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTA INES",
   "codigoexterno":7847
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTA ISABEL DO IVAI",
   "codigoexterno":7849
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTA IZABEL DO OESTE",
   "codigoexterno":7851
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTA LUCIA",
   "codigoexterno":5469
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTA MARIA DO OESTE",
   "codigoexterno":5505
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTA MARIANA",
   "codigoexterno":7853
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTA MONICA",
   "codigoexterno":5519
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTA TEREZA DO OESTE",
   "codigoexterno":9969
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTA TEREZINHA DE ITAIPU",
   "codigoexterno":8467
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTANA DO ITARARE",
   "codigoexterno":7855
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTO ANTONIO DA PLATINA",
   "codigoexterno":7859
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTO ANTONIO DO CAIUA",
   "codigoexterno":7861
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTO ANTONIO DO PARAISO",
   "codigoexterno":7863
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTO ANTONIO DO SUDOESTE",
   "codigoexterno":7857
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SANTO INACIO",
   "codigoexterno":7865
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO CARLOS DO IVAI",
   "codigoexterno":7867
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO JERONIMO DA SERRA",
   "codigoexterno":7869
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO JOAO",
   "codigoexterno":7871
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO JOAO DO CAIUA",
   "codigoexterno":7873
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO JOAO DO IVAI",
   "codigoexterno":7875
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO JOAO DO TRIUNFO",
   "codigoexterno":7877
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO JORGE DO IVAI",
   "codigoexterno":7879
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO JORGE DO PATROCINIO",
   "codigoexterno":7999
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO JORGE D\'OESTE",
   "codigoexterno":7881
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO JOSE DA BOA VISTA",
   "codigoexterno":7883
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO JOSE DAS PALMEIRAS",
   "codigoexterno":8471
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO JOSE DOS PINHAIS",
   "codigoexterno":7885
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO MANOEL DO PARANA",
   "codigoexterno":5515
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO MATEUS DO SUL",
   "codigoexterno":7887
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO MIGUEL DO IGUACU",
   "codigoexterno":7889
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO PEDRO DO IGUACU",
   "codigoexterno":5489
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO PEDRO DO IVAI",
   "codigoexterno":7891
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO PEDRO DO PARANA",
   "codigoexterno":7893
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO SEBASTIAO DA AMOREIRA",
   "codigoexterno":7895
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAO TOME",
   "codigoexterno":7897
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAPOPEMA",
   "codigoexterno":7899
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SARANDI",
   "codigoexterno":8461
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SAUDADE DO IGUACU",
   "codigoexterno":5493
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SENGES",
   "codigoexterno":7901
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SERRANOPOLIS DO IGUACU",
   "codigoexterno":882
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SERTANEJA",
   "codigoexterno":7903
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SERTANOPOLIS",
   "codigoexterno":7905
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SIQUEIRA CAMPOS",
   "codigoexterno":7907
},
{
   "siglaestado":"PR",
   "nomemunicipio":"SULINA",
   "codigoexterno":8477
},
{
   "siglaestado":"PR",
   "nomemunicipio":"TAMARANA",
   "codigoexterno":884
},
{
   "siglaestado":"PR",
   "nomemunicipio":"TAMBOARA",
   "codigoexterno":7909
},
{
   "siglaestado":"PR",
   "nomemunicipio":"TAPEJARA",
   "codigoexterno":7911
},
{
   "siglaestado":"PR",
   "nomemunicipio":"TAPIRA",
   "codigoexterno":7973
},
{
   "siglaestado":"PR",
   "nomemunicipio":"TEIXEIRA SOARES",
   "codigoexterno":7913
},
{
   "siglaestado":"PR",
   "nomemunicipio":"TELEMACO BORBA",
   "codigoexterno":7915
},
{
   "siglaestado":"PR",
   "nomemunicipio":"TERRA BOA",
   "codigoexterno":7917
},
{
   "siglaestado":"PR",
   "nomemunicipio":"TERRA RICA",
   "codigoexterno":7919
},
{
   "siglaestado":"PR",
   "nomemunicipio":"TERRA ROXA",
   "codigoexterno":7921
},
{
   "siglaestado":"PR",
   "nomemunicipio":"TIBAGI",
   "codigoexterno":7923
},
{
   "siglaestado":"PR",
   "nomemunicipio":"TIJUCAS DO SUL",
   "codigoexterno":7925
},
{
   "siglaestado":"PR",
   "nomemunicipio":"TOLEDO",
   "codigoexterno":7927
},
{
   "siglaestado":"PR",
   "nomemunicipio":"TOMAZINA",
   "codigoexterno":7929
},
{
   "siglaestado":"PR",
   "nomemunicipio":"TRES BARRAS DO PARANA",
   "codigoexterno":7987
},
{
   "siglaestado":"PR",
   "nomemunicipio":"TUNAS DO PARANA",
   "codigoexterno":5455
},
{
   "siglaestado":"PR",
   "nomemunicipio":"TUNEIRAS DO OESTE",
   "codigoexterno":7931
},
{
   "siglaestado":"PR",
   "nomemunicipio":"TUPASSI",
   "codigoexterno":7993
},
{
   "siglaestado":"PR",
   "nomemunicipio":"TURVO",
   "codigoexterno":8453
},
{
   "siglaestado":"PR",
   "nomemunicipio":"UBIRATA",
   "codigoexterno":7933
},
{
   "siglaestado":"PR",
   "nomemunicipio":"UMUARAMA",
   "codigoexterno":7935
},
{
   "siglaestado":"PR",
   "nomemunicipio":"UNIAO DA VITORIA",
   "codigoexterno":7937
},
{
   "siglaestado":"PR",
   "nomemunicipio":"UNIFLOR",
   "codigoexterno":7939
},
{
   "siglaestado":"PR",
   "nomemunicipio":"URAI",
   "codigoexterno":7941
},
{
   "siglaestado":"PR",
   "nomemunicipio":"VENTANIA",
   "codigoexterno":5497
},
{
   "siglaestado":"PR",
   "nomemunicipio":"VERA CRUZ DO OESTE",
   "codigoexterno":7989
},
{
   "siglaestado":"PR",
   "nomemunicipio":"VERE",
   "codigoexterno":7945
},
{
   "siglaestado":"PR",
   "nomemunicipio":"VIRMOND",
   "codigoexterno":5483
},
{
   "siglaestado":"PR",
   "nomemunicipio":"VITORINO",
   "codigoexterno":7947
},
{
   "siglaestado":"PR",
   "nomemunicipio":"WENCESLAU BRAZ",
   "codigoexterno":7943
},
{
   "siglaestado":"PR",
   "nomemunicipio":"XAMBRE",
   "codigoexterno":7949
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"ANGRA DOS REIS",
   "codigoexterno":5801
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"APERIBE",
   "codigoexterno":2919
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"ARARUAMA",
   "codigoexterno":5803
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"AREAL",
   "codigoexterno":2925
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"ARMACAO DE BUZIOS",
   "codigoexterno":770
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"ARRAIAL DO CABO",
   "codigoexterno":5927
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"BARRA DO PIRAI",
   "codigoexterno":5805
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"BARRA MANSA",
   "codigoexterno":5807
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"BELFORD ROXO",
   "codigoexterno":2909
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"BOM JARDIM",
   "codigoexterno":5809
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"BOM JESUS DO ITABAPOANA",
   "codigoexterno":5811
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"CABO FRIO",
   "codigoexterno":5813
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"CACHOEIRAS DE MACACU",
   "codigoexterno":5815
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"CAMBUCI",
   "codigoexterno":5817
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"CAMPOS DOS GOYTACAZES",
   "codigoexterno":5819
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"CANTAGALO",
   "codigoexterno":5821
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"CARAPEBUS",
   "codigoexterno":772
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"CARDOSO MOREIRA",
   "codigoexterno":2915
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"CARMO",
   "codigoexterno":5823
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"CASIMIRO DE ABREU",
   "codigoexterno":5825
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"COMENDADOR LEVY GASPARIAN",
   "codigoexterno":2927
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"CONCEICAO DE MACABU",
   "codigoexterno":5827
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"CORDEIRO",
   "codigoexterno":5829
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"DUAS BARRAS",
   "codigoexterno":5831
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"DUQUE DE CAXIAS",
   "codigoexterno":5833
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"ENGENHEIRO PAULO DE FRONTIN",
   "codigoexterno":5835
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"GUAPIMIRIM",
   "codigoexterno":2907
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"IGUABA GRANDE",
   "codigoexterno":774
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"ITABORAI",
   "codigoexterno":5837
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"ITAGUAI",
   "codigoexterno":5839
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"ITALVA",
   "codigoexterno":5929
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"ITAOCARA",
   "codigoexterno":5841
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"ITAPERUNA",
   "codigoexterno":5843
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"ITATIAIA",
   "codigoexterno":6003
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"JAPERI",
   "codigoexterno":2913
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"LAJE DO MURIAE",
   "codigoexterno":5845
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"MACAE",
   "codigoexterno":5847
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"MACUCO",
   "codigoexterno":776
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"MAGE",
   "codigoexterno":5849
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"MANGARATIBA",
   "codigoexterno":5851
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"MARICA",
   "codigoexterno":5853
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"MENDES",
   "codigoexterno":5855
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"MESQUITA",
   "codigoexterno":1116
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"MIGUEL PEREIRA",
   "codigoexterno":5857
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"MIRACEMA",
   "codigoexterno":5859
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"NATIVIDADE",
   "codigoexterno":5861
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"NILOPOLIS",
   "codigoexterno":5863
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"NITEROI",
   "codigoexterno":5865
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"NOVA FRIBURGO",
   "codigoexterno":5867
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"NOVA IGUACU",
   "codigoexterno":5869
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"PARACAMBI",
   "codigoexterno":5871
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"PARAIBA DO SUL",
   "codigoexterno":5873
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"PARATI",
   "codigoexterno":5875
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"PATY DO ALFERES",
   "codigoexterno":6005
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"PETROPOLIS",
   "codigoexterno":5877
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"PINHEIRAL",
   "codigoexterno":778
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"PIRAI",
   "codigoexterno":5879
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"PORCIUNCULA",
   "codigoexterno":5881
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"PORTO REAL",
   "codigoexterno":780
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"QUATIS",
   "codigoexterno":2923
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"QUEIMADOS",
   "codigoexterno":2911
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"QUISSAMA",
   "codigoexterno":6007
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"RESENDE",
   "codigoexterno":5883
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"RIO BONITO",
   "codigoexterno":5885
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"RIO CLARO",
   "codigoexterno":5887
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"RIO DAS FLORES",
   "codigoexterno":5889
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"RIO DAS OSTRAS",
   "codigoexterno":2921
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"RIO DE JANEIRO",
   "codigoexterno":6001
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"SANTA MARIA MADALENA",
   "codigoexterno":5891
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"SANTO ANTONIO DE PADUA",
   "codigoexterno":5893
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"SAO FIDELIS",
   "codigoexterno":5895
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"SAO FRANCISCO DE ITABAPOANA",
   "codigoexterno":782
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"SAO GONCALO",
   "codigoexterno":5897
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"SAO JOAO DA BARRA",
   "codigoexterno":5899
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"SAO JOAO DE MERITI",
   "codigoexterno":5901
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"SAO JOSE DE UBA",
   "codigoexterno":784
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"SAO JOSE DO VALE DO RIO PRETO",
   "codigoexterno":6009
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"SAO PEDRO DA ALDEIA",
   "codigoexterno":5903
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"SAO SEBASTIAO DO ALTO",
   "codigoexterno":5905
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"SAPUCAIA",
   "codigoexterno":5907
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"SAQUAREMA",
   "codigoexterno":5909
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"SEROPEDICA",
   "codigoexterno":786
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"SILVA JARDIM",
   "codigoexterno":5911
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"SUMIDOURO",
   "codigoexterno":5913
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"TANGUA",
   "codigoexterno":788
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"TERESOPOLIS",
   "codigoexterno":5915
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"TRAJANO DE MORAIS",
   "codigoexterno":5917
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"TRES RIOS",
   "codigoexterno":5919
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"VALENCA",
   "codigoexterno":5921
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"VARRE-SAI",
   "codigoexterno":2917
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"VASSOURAS",
   "codigoexterno":5923
},
{
   "siglaestado":"RJ",
   "nomemunicipio":"VOLTA REDONDA",
   "codigoexterno":5925
},
{
   "siglaestado":"RN",
   "nomemunicipio":"ACARI",
   "codigoexterno":1601
},
{
   "siglaestado":"RN",
   "nomemunicipio":"AFONSO BEZERRA",
   "codigoexterno":1605
},
{
   "siglaestado":"RN",
   "nomemunicipio":"AGUA NOVA",
   "codigoexterno":1607
},
{
   "siglaestado":"RN",
   "nomemunicipio":"ALEXANDRIA",
   "codigoexterno":1609
},
{
   "siglaestado":"RN",
   "nomemunicipio":"ALMINO AFONSO",
   "codigoexterno":1611
},
{
   "siglaestado":"RN",
   "nomemunicipio":"ALTO DO RODRIGUES",
   "codigoexterno":1613
},
{
   "siglaestado":"RN",
   "nomemunicipio":"ANGICOS",
   "codigoexterno":1615
},
{
   "siglaestado":"RN",
   "nomemunicipio":"ANTONIO MARTINS",
   "codigoexterno":1617
},
{
   "siglaestado":"RN",
   "nomemunicipio":"APODI",
   "codigoexterno":1619
},
{
   "siglaestado":"RN",
   "nomemunicipio":"AREIA BRANCA",
   "codigoexterno":1621
},
{
   "siglaestado":"RN",
   "nomemunicipio":"ARES",
   "codigoexterno":1623
},
{
   "siglaestado":"RN",
   "nomemunicipio":"ASSU",
   "codigoexterno":1603
},
{
   "siglaestado":"RN",
   "nomemunicipio":"BAIA FORMOSA",
   "codigoexterno":1627
},
{
   "siglaestado":"RN",
   "nomemunicipio":"BARAUNA",
   "codigoexterno":3003
},
{
   "siglaestado":"RN",
   "nomemunicipio":"BARCELONA",
   "codigoexterno":1629
},
{
   "siglaestado":"RN",
   "nomemunicipio":"BENTO FERNANDES",
   "codigoexterno":1631
},
{
   "siglaestado":"RN",
   "nomemunicipio":"BOA SAUDE",
   "codigoexterno":1703
},
{
   "siglaestado":"RN",
   "nomemunicipio":"BODO",
   "codigoexterno":412
},
{
   "siglaestado":"RN",
   "nomemunicipio":"BOM JESUS",
   "codigoexterno":1633
},
{
   "siglaestado":"RN",
   "nomemunicipio":"BREJINHO",
   "codigoexterno":1635
},
{
   "siglaestado":"RN",
   "nomemunicipio":"CAICARA DO NORTE",
   "codigoexterno":414
},
{
   "siglaestado":"RN",
   "nomemunicipio":"CAICARA DO RIO DO VENTO",
   "codigoexterno":1637
},
{
   "siglaestado":"RN",
   "nomemunicipio":"CAICO",
   "codigoexterno":1639
},
{
   "siglaestado":"RN",
   "nomemunicipio":"CAMPO GRANDE",
   "codigoexterno":1625
},
{
   "siglaestado":"RN",
   "nomemunicipio":"CAMPO REDONDO",
   "codigoexterno":1641
},
{
   "siglaestado":"RN",
   "nomemunicipio":"CANGUARETAMA",
   "codigoexterno":1643
},
{
   "siglaestado":"RN",
   "nomemunicipio":"CARAUBAS",
   "codigoexterno":1645
},
{
   "siglaestado":"RN",
   "nomemunicipio":"CARNAUBA DOS DANTAS",
   "codigoexterno":1647
},
{
   "siglaestado":"RN",
   "nomemunicipio":"CARNAUBAIS",
   "codigoexterno":1649
},
{
   "siglaestado":"RN",
   "nomemunicipio":"CEARA-MIRIM",
   "codigoexterno":1651
},
{
   "siglaestado":"RN",
   "nomemunicipio":"CERRO-CORA",
   "codigoexterno":1653
},
{
   "siglaestado":"RN",
   "nomemunicipio":"CORONEL EZEQUIEL",
   "codigoexterno":1655
},
{
   "siglaestado":"RN",
   "nomemunicipio":"CORONEL JOAO PESSOA",
   "codigoexterno":1657
},
{
   "siglaestado":"RN",
   "nomemunicipio":"CRUZETA",
   "codigoexterno":1659
},
{
   "siglaestado":"RN",
   "nomemunicipio":"CURRAIS NOVOS",
   "codigoexterno":1661
},
{
   "siglaestado":"RN",
   "nomemunicipio":"DOUTOR SEVERIANO",
   "codigoexterno":1663
},
{
   "siglaestado":"RN",
   "nomemunicipio":"ENCANTO",
   "codigoexterno":1665
},
{
   "siglaestado":"RN",
   "nomemunicipio":"EQUADOR",
   "codigoexterno":1667
},
{
   "siglaestado":"RN",
   "nomemunicipio":"ESPIRITO SANTO",
   "codigoexterno":1669
},
{
   "siglaestado":"RN",
   "nomemunicipio":"EXTREMOZ",
   "codigoexterno":1671
},
{
   "siglaestado":"RN",
   "nomemunicipio":"FELIPE GUERRA",
   "codigoexterno":1673
},
{
   "siglaestado":"RN",
   "nomemunicipio":"FERNANDO PEDROZA",
   "codigoexterno":416
},
{
   "siglaestado":"RN",
   "nomemunicipio":"FLORANIA",
   "codigoexterno":1675
},
{
   "siglaestado":"RN",
   "nomemunicipio":"FRANCISCO DANTAS",
   "codigoexterno":1677
},
{
   "siglaestado":"RN",
   "nomemunicipio":"FRUTUOSO GOMES",
   "codigoexterno":1751
},
{
   "siglaestado":"RN",
   "nomemunicipio":"GALINHOS",
   "codigoexterno":1679
},
{
   "siglaestado":"RN",
   "nomemunicipio":"GOIANINHA",
   "codigoexterno":1681
},
{
   "siglaestado":"RN",
   "nomemunicipio":"GOVERNADOR DIX-SEPT ROSADO",
   "codigoexterno":1683
},
{
   "siglaestado":"RN",
   "nomemunicipio":"GROSSOS",
   "codigoexterno":1685
},
{
   "siglaestado":"RN",
   "nomemunicipio":"GUAMARE",
   "codigoexterno":1687
},
{
   "siglaestado":"RN",
   "nomemunicipio":"IELMO MARINHO",
   "codigoexterno":1689
},
{
   "siglaestado":"RN",
   "nomemunicipio":"IPANGUACU",
   "codigoexterno":1691
},
{
   "siglaestado":"RN",
   "nomemunicipio":"IPUEIRA",
   "codigoexterno":1693
},
{
   "siglaestado":"RN",
   "nomemunicipio":"ITAJA",
   "codigoexterno":418
},
{
   "siglaestado":"RN",
   "nomemunicipio":"ITAU",
   "codigoexterno":1695
},
{
   "siglaestado":"RN",
   "nomemunicipio":"JACANA",
   "codigoexterno":1697
},
{
   "siglaestado":"RN",
   "nomemunicipio":"JANDAIRA",
   "codigoexterno":1699
},
{
   "siglaestado":"RN",
   "nomemunicipio":"JANDUIS",
   "codigoexterno":1701
},
{
   "siglaestado":"RN",
   "nomemunicipio":"JAPI",
   "codigoexterno":1705
},
{
   "siglaestado":"RN",
   "nomemunicipio":"JARDIM DE ANGICOS",
   "codigoexterno":1707
},
{
   "siglaestado":"RN",
   "nomemunicipio":"JARDIM DE PIRANHAS",
   "codigoexterno":1709
},
{
   "siglaestado":"RN",
   "nomemunicipio":"JARDIM DO SERIDO",
   "codigoexterno":1711
},
{
   "siglaestado":"RN",
   "nomemunicipio":"JOAO CAMARA",
   "codigoexterno":1713
},
{
   "siglaestado":"RN",
   "nomemunicipio":"JOAO DIAS",
   "codigoexterno":1715
},
{
   "siglaestado":"RN",
   "nomemunicipio":"JOSE DA PENHA",
   "codigoexterno":1717
},
{
   "siglaestado":"RN",
   "nomemunicipio":"JUCURUTU",
   "codigoexterno":1719
},
{
   "siglaestado":"RN",
   "nomemunicipio":"JUNDI??",
   "codigoexterno":1108
},
{
   "siglaestado":"RN",
   "nomemunicipio":"LAGOA DANTA",
   "codigoexterno":1723
},
{
   "siglaestado":"RN",
   "nomemunicipio":"LAGOA DE PEDRAS",
   "codigoexterno":1725
},
{
   "siglaestado":"RN",
   "nomemunicipio":"LAGOA DE VELHOS",
   "codigoexterno":1727
},
{
   "siglaestado":"RN",
   "nomemunicipio":"LAGOA NOVA",
   "codigoexterno":1729
},
{
   "siglaestado":"RN",
   "nomemunicipio":"LAGOA SALGADA",
   "codigoexterno":1731
},
{
   "siglaestado":"RN",
   "nomemunicipio":"LAJES",
   "codigoexterno":1733
},
{
   "siglaestado":"RN",
   "nomemunicipio":"LAJES PINTADAS",
   "codigoexterno":1735
},
{
   "siglaestado":"RN",
   "nomemunicipio":"LUCRECIA",
   "codigoexterno":1737
},
{
   "siglaestado":"RN",
   "nomemunicipio":"LUIS GOMES",
   "codigoexterno":1739
},
{
   "siglaestado":"RN",
   "nomemunicipio":"MACAIBA",
   "codigoexterno":1741
},
{
   "siglaestado":"RN",
   "nomemunicipio":"MACAU",
   "codigoexterno":1743
},
{
   "siglaestado":"RN",
   "nomemunicipio":"MAJOR SALES",
   "codigoexterno":420
},
{
   "siglaestado":"RN",
   "nomemunicipio":"MARCELINO VIEIRA",
   "codigoexterno":1745
},
{
   "siglaestado":"RN",
   "nomemunicipio":"MARTINS",
   "codigoexterno":1747
},
{
   "siglaestado":"RN",
   "nomemunicipio":"MAXARANGUAPE",
   "codigoexterno":1749
},
{
   "siglaestado":"RN",
   "nomemunicipio":"MESSIAS TARGINO",
   "codigoexterno":1721
},
{
   "siglaestado":"RN",
   "nomemunicipio":"MONTANHAS",
   "codigoexterno":1753
},
{
   "siglaestado":"RN",
   "nomemunicipio":"MONTE ALEGRE",
   "codigoexterno":1755
},
{
   "siglaestado":"RN",
   "nomemunicipio":"MONTE DAS GAMELEIRAS",
   "codigoexterno":1757
},
{
   "siglaestado":"RN",
   "nomemunicipio":"MOSSORO",
   "codigoexterno":1759
},
{
   "siglaestado":"RN",
   "nomemunicipio":"NATAL",
   "codigoexterno":1761
},
{
   "siglaestado":"RN",
   "nomemunicipio":"NISIA FLORESTA",
   "codigoexterno":1763
},
{
   "siglaestado":"RN",
   "nomemunicipio":"NOVA CRUZ",
   "codigoexterno":1765
},
{
   "siglaestado":"RN",
   "nomemunicipio":"OLHO D\'AGUA DO BORGES",
   "codigoexterno":1767
},
{
   "siglaestado":"RN",
   "nomemunicipio":"OURO BRANCO",
   "codigoexterno":1769
},
{
   "siglaestado":"RN",
   "nomemunicipio":"PARANA",
   "codigoexterno":1771
},
{
   "siglaestado":"RN",
   "nomemunicipio":"PARAU",
   "codigoexterno":1773
},
{
   "siglaestado":"RN",
   "nomemunicipio":"PARAZINHO",
   "codigoexterno":1775
},
{
   "siglaestado":"RN",
   "nomemunicipio":"PARELHAS",
   "codigoexterno":1777
},
{
   "siglaestado":"RN",
   "nomemunicipio":"PARNAMIRIM",
   "codigoexterno":1779
},
{
   "siglaestado":"RN",
   "nomemunicipio":"PASSA E FICA",
   "codigoexterno":1781
},
{
   "siglaestado":"RN",
   "nomemunicipio":"PASSAGEM",
   "codigoexterno":1783
},
{
   "siglaestado":"RN",
   "nomemunicipio":"PATU",
   "codigoexterno":1785
},
{
   "siglaestado":"RN",
   "nomemunicipio":"PAU DOS FERROS",
   "codigoexterno":1787
},
{
   "siglaestado":"RN",
   "nomemunicipio":"PEDRA GRANDE",
   "codigoexterno":1789
},
{
   "siglaestado":"RN",
   "nomemunicipio":"PEDRA PRETA",
   "codigoexterno":1791
},
{
   "siglaestado":"RN",
   "nomemunicipio":"PEDRO AVELINO",
   "codigoexterno":1793
},
{
   "siglaestado":"RN",
   "nomemunicipio":"PEDRO VELHO",
   "codigoexterno":1795
},
{
   "siglaestado":"RN",
   "nomemunicipio":"PENDENCIAS",
   "codigoexterno":1797
},
{
   "siglaestado":"RN",
   "nomemunicipio":"PILOES",
   "codigoexterno":1799
},
{
   "siglaestado":"RN",
   "nomemunicipio":"POCO BRANCO",
   "codigoexterno":1801
},
{
   "siglaestado":"RN",
   "nomemunicipio":"PORTALEGRE",
   "codigoexterno":1803
},
{
   "siglaestado":"RN",
   "nomemunicipio":"PORTO DO MANGUE",
   "codigoexterno":426
},
{
   "siglaestado":"RN",
   "nomemunicipio":"PUREZA",
   "codigoexterno":1807
},
{
   "siglaestado":"RN",
   "nomemunicipio":"RAFAEL FERNANDES",
   "codigoexterno":1809
},
{
   "siglaestado":"RN",
   "nomemunicipio":"RAFAEL GODEIRO",
   "codigoexterno":1893
},
{
   "siglaestado":"RN",
   "nomemunicipio":"RIACHO DA CRUZ",
   "codigoexterno":1811
},
{
   "siglaestado":"RN",
   "nomemunicipio":"RIACHO DE SANTANA",
   "codigoexterno":1813
},
{
   "siglaestado":"RN",
   "nomemunicipio":"RIACHUELO",
   "codigoexterno":1815
},
{
   "siglaestado":"RN",
   "nomemunicipio":"RIO DO FOGO",
   "codigoexterno":422
},
{
   "siglaestado":"RN",
   "nomemunicipio":"RODOLFO FERNANDES",
   "codigoexterno":1817
},
{
   "siglaestado":"RN",
   "nomemunicipio":"RUY BARBOSA",
   "codigoexterno":1819
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SANTA CRUZ",
   "codigoexterno":1823
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SANTA MARIA",
   "codigoexterno":424
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SANTANA DO MATOS",
   "codigoexterno":1827
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SANTANA DO SERIDO",
   "codigoexterno":1825
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SANTO ANTONIO",
   "codigoexterno":1829
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SAO BENTO DO NORTE",
   "codigoexterno":1831
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SAO BENTO DO TRAIRI",
   "codigoexterno":1833
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SAO FERNANDO",
   "codigoexterno":1835
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SAO FRANCISCO DO OESTE",
   "codigoexterno":1821
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SAO GONCALO DO AMARANTE",
   "codigoexterno":1837
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SAO JOAO DO SABUGI",
   "codigoexterno":1839
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SAO JOSE DE MIPIBU",
   "codigoexterno":1841
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SAO JOSE DO CAMPESTRE",
   "codigoexterno":1843
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SAO JOSE DO SERIDO",
   "codigoexterno":1845
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SAO MIGUEL",
   "codigoexterno":1847
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SAO MIGUEL DO GOSTOSO",
   "codigoexterno":430
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SAO PAULO DO POTENGI",
   "codigoexterno":1849
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SAO PEDRO",
   "codigoexterno":1851
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SAO RAFAEL",
   "codigoexterno":1853
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SAO TOME",
   "codigoexterno":1855
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SAO VICENTE",
   "codigoexterno":1857
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SENADOR ELOI DE SOUZA",
   "codigoexterno":1859
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SENADOR GEORGINO AVELINO",
   "codigoexterno":1861
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SERRA CAIADA",
   "codigoexterno":1805
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SERRA DE SAO BENTO",
   "codigoexterno":1863
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SERRA DO MEL",
   "codigoexterno":1927
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SERRA NEGRA DO NORTE",
   "codigoexterno":1865
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SERRINHA",
   "codigoexterno":1867
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SERRINHA DOS PINTOS",
   "codigoexterno":432
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SEVERIANO MELO",
   "codigoexterno":1869
},
{
   "siglaestado":"RN",
   "nomemunicipio":"SITIO NOVO",
   "codigoexterno":1871
},
{
   "siglaestado":"RN",
   "nomemunicipio":"TABOLEIRO GRANDE",
   "codigoexterno":1873
},
{
   "siglaestado":"RN",
   "nomemunicipio":"TAIPU",
   "codigoexterno":1875
},
{
   "siglaestado":"RN",
   "nomemunicipio":"TANGARA",
   "codigoexterno":1877
},
{
   "siglaestado":"RN",
   "nomemunicipio":"TENENTE ANANIAS",
   "codigoexterno":1879
},
{
   "siglaestado":"RN",
   "nomemunicipio":"TENENTE LAURENTINO CRUZ",
   "codigoexterno":434
},
{
   "siglaestado":"RN",
   "nomemunicipio":"TIBAU",
   "codigoexterno":428
},
{
   "siglaestado":"RN",
   "nomemunicipio":"TIBAU DO SUL",
   "codigoexterno":1881
},
{
   "siglaestado":"RN",
   "nomemunicipio":"TIMBAUBA DOS BATISTAS",
   "codigoexterno":1883
},
{
   "siglaestado":"RN",
   "nomemunicipio":"TOUROS",
   "codigoexterno":1885
},
{
   "siglaestado":"RN",
   "nomemunicipio":"TRIUNFO POTIGUAR",
   "codigoexterno":436
},
{
   "siglaestado":"RN",
   "nomemunicipio":"UMARIZAL",
   "codigoexterno":1887
},
{
   "siglaestado":"RN",
   "nomemunicipio":"UPANEMA",
   "codigoexterno":1889
},
{
   "siglaestado":"RN",
   "nomemunicipio":"VARZEA",
   "codigoexterno":1891
},
{
   "siglaestado":"RN",
   "nomemunicipio":"VENHA-VER",
   "codigoexterno":438
},
{
   "siglaestado":"RN",
   "nomemunicipio":"VERA CRUZ",
   "codigoexterno":1895
},
{
   "siglaestado":"RN",
   "nomemunicipio":"VICOSA",
   "codigoexterno":1897
},
{
   "siglaestado":"RN",
   "nomemunicipio":"VILA FLOR",
   "codigoexterno":1899
},
{
   "siglaestado":"RO",
   "nomemunicipio":"ALTA FLORESTA D\'OESTE",
   "codigoexterno":33
},
{
   "siglaestado":"RO",
   "nomemunicipio":"ALTO ALEGRE DOS PARECIS",
   "codigoexterno":2
},
{
   "siglaestado":"RO",
   "nomemunicipio":"ALTO PARAISO",
   "codigoexterno":675
},
{
   "siglaestado":"RO",
   "nomemunicipio":"ALVORADA D\'OESTE",
   "codigoexterno":35
},
{
   "siglaestado":"RO",
   "nomemunicipio":"ARIQUEMES",
   "codigoexterno":7
},
{
   "siglaestado":"RO",
   "nomemunicipio":"BURITIS",
   "codigoexterno":4
},
{
   "siglaestado":"RO",
   "nomemunicipio":"CABIXI",
   "codigoexterno":37
},
{
   "siglaestado":"RO",
   "nomemunicipio":"CACAULANDIA",
   "codigoexterno":677
},
{
   "siglaestado":"RO",
   "nomemunicipio":"CACOAL",
   "codigoexterno":9
},
{
   "siglaestado":"RO",
   "nomemunicipio":"CAMPO NOVO DE RONDONIA",
   "codigoexterno":679
},
{
   "siglaestado":"RO",
   "nomemunicipio":"CANDEIAS DO JAMARI",
   "codigoexterno":681
},
{
   "siglaestado":"RO",
   "nomemunicipio":"CASTANHEIRAS",
   "codigoexterno":691
},
{
   "siglaestado":"RO",
   "nomemunicipio":"CEREJEIRAS",
   "codigoexterno":27
},
{
   "siglaestado":"RO",
   "nomemunicipio":"CHUPINGUAIA",
   "codigoexterno":6
},
{
   "siglaestado":"RO",
   "nomemunicipio":"COLORADO DO OESTE",
   "codigoexterno":23
},
{
   "siglaestado":"RO",
   "nomemunicipio":"CORUMBIARA",
   "codigoexterno":981
},
{
   "siglaestado":"RO",
   "nomemunicipio":"COSTA MARQUES",
   "codigoexterno":21
},
{
   "siglaestado":"RO",
   "nomemunicipio":"CUJUBIM",
   "codigoexterno":8
},
{
   "siglaestado":"RO",
   "nomemunicipio":"ESPIGAO D\'OESTE",
   "codigoexterno":25
},
{
   "siglaestado":"RO",
   "nomemunicipio":"GOVERNADOR JORGE TEIXEIRA",
   "codigoexterno":693
},
{
   "siglaestado":"RO",
   "nomemunicipio":"GUAJARA-MIRIM",
   "codigoexterno":1
},
{
   "siglaestado":"RO",
   "nomemunicipio":"ITAPUA DO OESTE",
   "codigoexterno":683
},
{
   "siglaestado":"RO",
   "nomemunicipio":"JARU",
   "codigoexterno":15
},
{
   "siglaestado":"RO",
   "nomemunicipio":"JI-PARANA",
   "codigoexterno":5
},
{
   "siglaestado":"RO",
   "nomemunicipio":"MACHADINHO D\'OESTE",
   "codigoexterno":39
},
{
   "siglaestado":"RO",
   "nomemunicipio":"MINISTRO ANDREAZZA",
   "codigoexterno":695
},
{
   "siglaestado":"RO",
   "nomemunicipio":"MIRANTE DA SERRA",
   "codigoexterno":697
},
{
   "siglaestado":"RO",
   "nomemunicipio":"MONTE NEGRO",
   "codigoexterno":685
},
{
   "siglaestado":"RO",
   "nomemunicipio":"NOVA BRASILANDIA D\'OESTE",
   "codigoexterno":41
},
{
   "siglaestado":"RO",
   "nomemunicipio":"NOVA DO MAMORE",
   "codigoexterno":47
},
{
   "siglaestado":"RO",
   "nomemunicipio":"NOVA UNIAO",
   "codigoexterno":10
},
{
   "siglaestado":"RO",
   "nomemunicipio":"NOVO HORIZONTE DO OESTE",
   "codigoexterno":689
},
{
   "siglaestado":"RO",
   "nomemunicipio":"OURO PRETO DO OESTE",
   "codigoexterno":17
},
{
   "siglaestado":"RO",
   "nomemunicipio":"PARECIS",
   "codigoexterno":12
},
{
   "siglaestado":"RO",
   "nomemunicipio":"PIMENTA BUENO",
   "codigoexterno":11
},
{
   "siglaestado":"RO",
   "nomemunicipio":"PIMENTEIRAS DO OESTE",
   "codigoexterno":14
},
{
   "siglaestado":"RO",
   "nomemunicipio":"PORTO VELHO",
   "codigoexterno":3
},
{
   "siglaestado":"RO",
   "nomemunicipio":"PRESIDENTE MEDICI",
   "codigoexterno":19
},
{
   "siglaestado":"RO",
   "nomemunicipio":"PRIMAVERA DE RONDONIA",
   "codigoexterno":16
},
{
   "siglaestado":"RO",
   "nomemunicipio":"RIO CRESPO",
   "codigoexterno":687
},
{
   "siglaestado":"RO",
   "nomemunicipio":"ROLIM DE MOURA",
   "codigoexterno":29
},
{
   "siglaestado":"RO",
   "nomemunicipio":"SANTA LUZIA D\'OESTE",
   "codigoexterno":43
},
{
   "siglaestado":"RO",
   "nomemunicipio":"SAO FELIPE D\'OESTE",
   "codigoexterno":18
},
{
   "siglaestado":"RO",
   "nomemunicipio":"SAO FRANCISCO DO GUAPORE",
   "codigoexterno":20
},
{
   "siglaestado":"RO",
   "nomemunicipio":"SAO MIGUEL DO GUAPORE",
   "codigoexterno":45
},
{
   "siglaestado":"RO",
   "nomemunicipio":"SERINGUEIRAS",
   "codigoexterno":699
},
{
   "siglaestado":"RO",
   "nomemunicipio":"TEIXEIROPOLIS",
   "codigoexterno":22
},
{
   "siglaestado":"RO",
   "nomemunicipio":"THEOBROMA",
   "codigoexterno":975
},
{
   "siglaestado":"RO",
   "nomemunicipio":"URUPA",
   "codigoexterno":977
},
{
   "siglaestado":"RO",
   "nomemunicipio":"VALE DO ANARI",
   "codigoexterno":24
},
{
   "siglaestado":"RO",
   "nomemunicipio":"VALE DO PARAISO",
   "codigoexterno":979
},
{
   "siglaestado":"RO",
   "nomemunicipio":"VILHENA",
   "codigoexterno":13
},
{
   "siglaestado":"RR",
   "nomemunicipio":"ALTO ALEGRE",
   "codigoexterno":305
},
{
   "siglaestado":"RR",
   "nomemunicipio":"AMAJARI",
   "codigoexterno":26
},
{
   "siglaestado":"RR",
   "nomemunicipio":"BOA VISTA",
   "codigoexterno":301
},
{
   "siglaestado":"RR",
   "nomemunicipio":"BONFIM",
   "codigoexterno":307
},
{
   "siglaestado":"RR",
   "nomemunicipio":"CANTA",
   "codigoexterno":28
},
{
   "siglaestado":"RR",
   "nomemunicipio":"CARACARAI",
   "codigoexterno":303
},
{
   "siglaestado":"RR",
   "nomemunicipio":"CAROEBE",
   "codigoexterno":30
},
{
   "siglaestado":"RR",
   "nomemunicipio":"IRACEMA",
   "codigoexterno":32
},
{
   "siglaestado":"RR",
   "nomemunicipio":"MUCAJAI",
   "codigoexterno":309
},
{
   "siglaestado":"RR",
   "nomemunicipio":"NORMANDIA",
   "codigoexterno":311
},
{
   "siglaestado":"RR",
   "nomemunicipio":"PACARAIMA",
   "codigoexterno":34
},
{
   "siglaestado":"RR",
   "nomemunicipio":"RORAINOPOLIS",
   "codigoexterno":36
},
{
   "siglaestado":"RR",
   "nomemunicipio":"SAO JOAO DA BALIZA",
   "codigoexterno":313
},
{
   "siglaestado":"RR",
   "nomemunicipio":"SAO LUIZ",
   "codigoexterno":315
},
{
   "siglaestado":"RR",
   "nomemunicipio":"UIRAMUTA",
   "codigoexterno":38
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ACEGU??",
   "codigoexterno":1118
},
{
   "siglaestado":"RS",
   "nomemunicipio":"AGUA SANTA",
   "codigoexterno":8499
},
{
   "siglaestado":"RS",
   "nomemunicipio":"AGUDO",
   "codigoexterno":8501
},
{
   "siglaestado":"RS",
   "nomemunicipio":"AJURICABA",
   "codigoexterno":8503
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ALECRIM",
   "codigoexterno":8505
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ALEGRETE",
   "codigoexterno":8507
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ALEGRIA",
   "codigoexterno":8497
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ALMIRANTE TAMANDAR?? DO SUL",
   "codigoexterno":1120
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ALPESTRE",
   "codigoexterno":8509
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ALTO ALEGRE",
   "codigoexterno":8495
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ALTO FELIZ",
   "codigoexterno":6045
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ALVORADA",
   "codigoexterno":8511
},
{
   "siglaestado":"RS",
   "nomemunicipio":"AMARAL FERRADOR",
   "codigoexterno":8493
},
{
   "siglaestado":"RS",
   "nomemunicipio":"AMETISTA DO SUL",
   "codigoexterno":5969
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ANDRE DA ROCHA",
   "codigoexterno":8491
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ANTA GORDA",
   "codigoexterno":8513
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ANTONIO PRADO",
   "codigoexterno":8515
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ARAMBARE",
   "codigoexterno":5779
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ARARICA",
   "codigoexterno":952
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ARATIBA",
   "codigoexterno":8517
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ARROIO DO MEIO",
   "codigoexterno":8519
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ARROIO DO PADRE",
   "codigoexterno":1122
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ARROIO DO SAL",
   "codigoexterno":8489
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ARROIO DO TIGRE",
   "codigoexterno":8523
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ARROIO DOS RATOS",
   "codigoexterno":8521
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ARROIO GRANDE",
   "codigoexterno":8525
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ARVOREZINHA",
   "codigoexterno":8527
},
{
   "siglaestado":"RS",
   "nomemunicipio":"AUGUSTO PESTANA",
   "codigoexterno":8529
},
{
   "siglaestado":"RS",
   "nomemunicipio":"AUREA",
   "codigoexterno":8487
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BAGE",
   "codigoexterno":8531
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BALNEARIO PINHAL",
   "codigoexterno":954
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BARAO",
   "codigoexterno":8485
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BARAO DE COTEGIPE",
   "codigoexterno":8533
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BARAO DO TRIUNFO",
   "codigoexterno":5771
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BARRA DO GUARITA",
   "codigoexterno":6069
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BARRA DO QUARAI",
   "codigoexterno":956
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BARRA DO RIBEIRO",
   "codigoexterno":8537
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BARRA DO RIO AZUL",
   "codigoexterno":5959
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BARRA FUNDA",
   "codigoexterno":5943
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BARRACAO",
   "codigoexterno":8535
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BARROS CASSAL",
   "codigoexterno":8539
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BENJAMIN CONSTANT DO SUL",
   "codigoexterno":958
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BENTO GONCALVES",
   "codigoexterno":8541
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BOA VISTA DAS MISSOES",
   "codigoexterno":5981
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BOA VISTA DO BURICA",
   "codigoexterno":8543
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BOA VISTA DO CADEADO",
   "codigoexterno":1124
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BOA VISTA DO INCRA",
   "codigoexterno":1126
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BOA VISTA DO SUL",
   "codigoexterno":960
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BOM JESUS",
   "codigoexterno":8545
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BOM PRINCIPIO",
   "codigoexterno":9823
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BOM PROGRESSO",
   "codigoexterno":6071
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BOM RETIRO DO SUL",
   "codigoexterno":8547
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BOQUEIRAO DO LEAO",
   "codigoexterno":8483
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BOSSOROCA",
   "codigoexterno":8549
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BOZANO",
   "codigoexterno":1128
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BRAGA",
   "codigoexterno":8551
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BROCHIER",
   "codigoexterno":8449
},
{
   "siglaestado":"RS",
   "nomemunicipio":"BUTIA",
   "codigoexterno":8553
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CACAPAVA DO SUL",
   "codigoexterno":8555
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CACEQUI",
   "codigoexterno":8557
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CACHOEIRA DO SUL",
   "codigoexterno":8559
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CACHOEIRINHA",
   "codigoexterno":8561
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CACIQUE DOBLE",
   "codigoexterno":8563
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CAIBATE",
   "codigoexterno":8565
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CAICARA",
   "codigoexterno":8567
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CAMAQUA",
   "codigoexterno":8569
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CAMARGO",
   "codigoexterno":8447
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CAMBARA DO SUL",
   "codigoexterno":8571
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CAMPESTRE DA SERRA",
   "codigoexterno":6013
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CAMPINA DAS MISSOES",
   "codigoexterno":8573
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CAMPINAS DO SUL",
   "codigoexterno":8575
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CAMPO BOM",
   "codigoexterno":8577
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CAMPO NOVO",
   "codigoexterno":8579
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CAMPOS BORGES",
   "codigoexterno":8445
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CANDELARIA",
   "codigoexterno":8581
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CANDIDO GODOI",
   "codigoexterno":8583
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CANDIOTA",
   "codigoexterno":6083
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CANELA",
   "codigoexterno":8585
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CANGUCU",
   "codigoexterno":8587
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CANOAS",
   "codigoexterno":8589
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CANUDOS DO VALE",
   "codigoexterno":1130
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CAP??O BONITO DO SUL",
   "codigoexterno":1132
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CAP??O DO CIP?",
   "codigoexterno":1134
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CAPAO DA CANOA",
   "codigoexterno":8915
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CAPAO DO LEAO",
   "codigoexterno":8973
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CAPELA DE SANTANA",
   "codigoexterno":8443
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CAPITAO",
   "codigoexterno":6025
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CAPIVARI DO SUL",
   "codigoexterno":962
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CARAA",
   "codigoexterno":964
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CARAZINHO",
   "codigoexterno":8591
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CARLOS BARBOSA",
   "codigoexterno":8593
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CARLOS GOMES",
   "codigoexterno":5961
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CASCA",
   "codigoexterno":8595
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CASEIROS",
   "codigoexterno":8441
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CATUIPE",
   "codigoexterno":8597
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CAXIAS DO SUL",
   "codigoexterno":8599
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CENTENARIO",
   "codigoexterno":5963
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CERRITO",
   "codigoexterno":966
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CERRO BRANCO",
   "codigoexterno":8439
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CERRO GRANDE",
   "codigoexterno":8437
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CERRO GRANDE DO SUL",
   "codigoexterno":8435
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CERRO LARGO",
   "codigoexterno":8601
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CHAPADA",
   "codigoexterno":8603
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CHARQUEADAS",
   "codigoexterno":8693
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CHARRUA",
   "codigoexterno":5965
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CHIAPETTA",
   "codigoexterno":8605
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CHUI",
   "codigoexterno":968
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CHUVISCA",
   "codigoexterno":970
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CIDREIRA",
   "codigoexterno":8433
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CIRIACO",
   "codigoexterno":8607
},
{
   "siglaestado":"RS",
   "nomemunicipio":"COLINAS",
   "codigoexterno":6029
},
{
   "siglaestado":"RS",
   "nomemunicipio":"COLORADO",
   "codigoexterno":8609
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CONDOR",
   "codigoexterno":8611
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CONSTANTINA",
   "codigoexterno":8613
},
{
   "siglaestado":"RS",
   "nomemunicipio":"COQUEIRO BAIXO",
   "codigoexterno":1136
},
{
   "siglaestado":"RS",
   "nomemunicipio":"COQUEIROS DO SUL",
   "codigoexterno":5945
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CORONEL BARROS",
   "codigoexterno":6055
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CORONEL BICACO",
   "codigoexterno":8615
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CORONEL PILAR",
   "codigoexterno":1138
},
{
   "siglaestado":"RS",
   "nomemunicipio":"COTIPORA",
   "codigoexterno":8977
},
{
   "siglaestado":"RS",
   "nomemunicipio":"COXILHA",
   "codigoexterno":5797
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CRISSIUMAL",
   "codigoexterno":8617
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CRISTAL",
   "codigoexterno":8431
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CRISTAL DO SUL",
   "codigoexterno":972
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CRUZ ALTA",
   "codigoexterno":8619
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CRUZALTENSE",
   "codigoexterno":1140
},
{
   "siglaestado":"RS",
   "nomemunicipio":"CRUZEIRO DO SUL",
   "codigoexterno":8621
},
{
   "siglaestado":"RS",
   "nomemunicipio":"DAVID CANABARRO",
   "codigoexterno":8623
},
{
   "siglaestado":"RS",
   "nomemunicipio":"DERRUBADAS",
   "codigoexterno":6073
},
{
   "siglaestado":"RS",
   "nomemunicipio":"DEZESSEIS DE NOVEMBRO",
   "codigoexterno":8429
},
{
   "siglaestado":"RS",
   "nomemunicipio":"DILERMANDO DE AGUIAR",
   "codigoexterno":974
},
{
   "siglaestado":"RS",
   "nomemunicipio":"DOIS IRMAOS",
   "codigoexterno":8625
},
{
   "siglaestado":"RS",
   "nomemunicipio":"DOIS IRMAOS DAS MISSOES",
   "codigoexterno":5971
},
{
   "siglaestado":"RS",
   "nomemunicipio":"DOIS LAJEADOS",
   "codigoexterno":8427
},
{
   "siglaestado":"RS",
   "nomemunicipio":"DOM FELICIANO",
   "codigoexterno":8627
},
{
   "siglaestado":"RS",
   "nomemunicipio":"DOM PEDRITO",
   "codigoexterno":8629
},
{
   "siglaestado":"RS",
   "nomemunicipio":"DOM PEDRO DE ALCANTARA",
   "codigoexterno":976
},
{
   "siglaestado":"RS",
   "nomemunicipio":"DONA FRANCISCA",
   "codigoexterno":8631
},
{
   "siglaestado":"RS",
   "nomemunicipio":"DOUTOR MAURICIO CARDOSO",
   "codigoexterno":8425
},
{
   "siglaestado":"RS",
   "nomemunicipio":"DOUTOR RICARDO",
   "codigoexterno":978
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ELDORADO DO SUL",
   "codigoexterno":8423
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ENCANTADO",
   "codigoexterno":8633
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ENCRUZILHADA DO SUL",
   "codigoexterno":8635
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ENGENHO VELHO",
   "codigoexterno":5947
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ENTRE IJUIS",
   "codigoexterno":8419
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ENTRE RIOS DO SUL",
   "codigoexterno":8421
},
{
   "siglaestado":"RS",
   "nomemunicipio":"EREBANGO",
   "codigoexterno":8417
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ERECHIM",
   "codigoexterno":8637
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ERNESTINA",
   "codigoexterno":8415
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ERVAL GRANDE",
   "codigoexterno":8641
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ERVAL SECO",
   "codigoexterno":8643
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ESMERALDA",
   "codigoexterno":8645
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ESPERANCA DO SUL",
   "codigoexterno":980
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ESPUMOSO",
   "codigoexterno":8647
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ESTACAO",
   "codigoexterno":7301
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ESTANCIA VELHA",
   "codigoexterno":8649
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ESTEIO",
   "codigoexterno":8651
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ESTRELA",
   "codigoexterno":8653
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ESTRELA VELHA",
   "codigoexterno":982
},
{
   "siglaestado":"RS",
   "nomemunicipio":"EUGENIO DE CASTRO",
   "codigoexterno":8413
},
{
   "siglaestado":"RS",
   "nomemunicipio":"FAGUNDES VARELA",
   "codigoexterno":8411
},
{
   "siglaestado":"RS",
   "nomemunicipio":"FARROUPILHA",
   "codigoexterno":8655
},
{
   "siglaestado":"RS",
   "nomemunicipio":"FAXINAL DO SOTURNO",
   "codigoexterno":8657
},
{
   "siglaestado":"RS",
   "nomemunicipio":"FAXINALZINHO",
   "codigoexterno":8409
},
{
   "siglaestado":"RS",
   "nomemunicipio":"FAZENDA VILANOVA",
   "codigoexterno":984
},
{
   "siglaestado":"RS",
   "nomemunicipio":"FELIZ",
   "codigoexterno":8659
},
{
   "siglaestado":"RS",
   "nomemunicipio":"FLORES DA CUNHA",
   "codigoexterno":8661
},
{
   "siglaestado":"RS",
   "nomemunicipio":"FLORIANO PEIXOTO",
   "codigoexterno":986
},
{
   "siglaestado":"RS",
   "nomemunicipio":"FONTOURA XAVIER",
   "codigoexterno":8663
},
{
   "siglaestado":"RS",
   "nomemunicipio":"FORMIGUEIRO",
   "codigoexterno":8665
},
{
   "siglaestado":"RS",
   "nomemunicipio":"FORQUETINHA",
   "codigoexterno":1142
},
{
   "siglaestado":"RS",
   "nomemunicipio":"FORTALEZA DOS VALOS",
   "codigoexterno":9827
},
{
   "siglaestado":"RS",
   "nomemunicipio":"FREDERICO WESTPHALEN",
   "codigoexterno":8667
},
{
   "siglaestado":"RS",
   "nomemunicipio":"GARIBALDI",
   "codigoexterno":8669
},
{
   "siglaestado":"RS",
   "nomemunicipio":"GARRUCHOS",
   "codigoexterno":6081
},
{
   "siglaestado":"RS",
   "nomemunicipio":"GAURAMA",
   "codigoexterno":8671
},
{
   "siglaestado":"RS",
   "nomemunicipio":"GENERAL CAMARA",
   "codigoexterno":8673
},
{
   "siglaestado":"RS",
   "nomemunicipio":"GENTIL",
   "codigoexterno":5799
},
{
   "siglaestado":"RS",
   "nomemunicipio":"GETULIO VARGAS",
   "codigoexterno":8677
},
{
   "siglaestado":"RS",
   "nomemunicipio":"GIRUA",
   "codigoexterno":8679
},
{
   "siglaestado":"RS",
   "nomemunicipio":"GLORINHA",
   "codigoexterno":8407
},
{
   "siglaestado":"RS",
   "nomemunicipio":"GRAMADO",
   "codigoexterno":8681
},
{
   "siglaestado":"RS",
   "nomemunicipio":"GRAMADO DOS LOUREIROS",
   "codigoexterno":5949
},
{
   "siglaestado":"RS",
   "nomemunicipio":"GRAMADO XAVIER",
   "codigoexterno":5763
},
{
   "siglaestado":"RS",
   "nomemunicipio":"GRAVATAI",
   "codigoexterno":8683
},
{
   "siglaestado":"RS",
   "nomemunicipio":"GUABIJU",
   "codigoexterno":8405
},
{
   "siglaestado":"RS",
   "nomemunicipio":"GUAIBA",
   "codigoexterno":8685
},
{
   "siglaestado":"RS",
   "nomemunicipio":"GUAPORE",
   "codigoexterno":8687
},
{
   "siglaestado":"RS",
   "nomemunicipio":"GUARANI DAS MISSOES",
   "codigoexterno":8689
},
{
   "siglaestado":"RS",
   "nomemunicipio":"HARMONIA",
   "codigoexterno":8403
},
{
   "siglaestado":"RS",
   "nomemunicipio":"HERVAL",
   "codigoexterno":8639
},
{
   "siglaestado":"RS",
   "nomemunicipio":"HERVEIRAS",
   "codigoexterno":988
},
{
   "siglaestado":"RS",
   "nomemunicipio":"HORIZONTINA",
   "codigoexterno":8691
},
{
   "siglaestado":"RS",
   "nomemunicipio":"HULHA NEGRA",
   "codigoexterno":6085
},
{
   "siglaestado":"RS",
   "nomemunicipio":"HUMAITA",
   "codigoexterno":8695
},
{
   "siglaestado":"RS",
   "nomemunicipio":"IBARAMA",
   "codigoexterno":8401
},
{
   "siglaestado":"RS",
   "nomemunicipio":"IBIACA",
   "codigoexterno":8697
},
{
   "siglaestado":"RS",
   "nomemunicipio":"IBIRAIARAS",
   "codigoexterno":8699
},
{
   "siglaestado":"RS",
   "nomemunicipio":"IBIRAPUITA",
   "codigoexterno":7299
},
{
   "siglaestado":"RS",
   "nomemunicipio":"IBIRUBA",
   "codigoexterno":8701
},
{
   "siglaestado":"RS",
   "nomemunicipio":"IGREJINHA",
   "codigoexterno":8703
},
{
   "siglaestado":"RS",
   "nomemunicipio":"IJUI",
   "codigoexterno":8705
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ILOPOLIS",
   "codigoexterno":8707
},
{
   "siglaestado":"RS",
   "nomemunicipio":"IMBE",
   "codigoexterno":7297
},
{
   "siglaestado":"RS",
   "nomemunicipio":"IMIGRANTE",
   "codigoexterno":7295
},
{
   "siglaestado":"RS",
   "nomemunicipio":"INDEPENDENCIA",
   "codigoexterno":8709
},
{
   "siglaestado":"RS",
   "nomemunicipio":"INHACORA",
   "codigoexterno":6051
},
{
   "siglaestado":"RS",
   "nomemunicipio":"IPE",
   "codigoexterno":8399
},
{
   "siglaestado":"RS",
   "nomemunicipio":"IPIRANGA DO SUL",
   "codigoexterno":7399
},
{
   "siglaestado":"RS",
   "nomemunicipio":"IRAI",
   "codigoexterno":8711
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ITAARA",
   "codigoexterno":990
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ITACURUBI",
   "codigoexterno":7397
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ITAPUCA",
   "codigoexterno":6027
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ITAQUI",
   "codigoexterno":8713
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ITATI",
   "codigoexterno":1144
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ITATIBA DO SUL",
   "codigoexterno":8715
},
{
   "siglaestado":"RS",
   "nomemunicipio":"IVORA",
   "codigoexterno":7395
},
{
   "siglaestado":"RS",
   "nomemunicipio":"IVOTI",
   "codigoexterno":8717
},
{
   "siglaestado":"RS",
   "nomemunicipio":"JABOTICABA",
   "codigoexterno":7393
},
{
   "siglaestado":"RS",
   "nomemunicipio":"JACUIZINHO",
   "codigoexterno":1146
},
{
   "siglaestado":"RS",
   "nomemunicipio":"JACUTINGA",
   "codigoexterno":8719
},
{
   "siglaestado":"RS",
   "nomemunicipio":"JAGUARAO",
   "codigoexterno":8721
},
{
   "siglaestado":"RS",
   "nomemunicipio":"JAGUARI",
   "codigoexterno":8723
},
{
   "siglaestado":"RS",
   "nomemunicipio":"JAQUIRANA",
   "codigoexterno":7391
},
{
   "siglaestado":"RS",
   "nomemunicipio":"JARI",
   "codigoexterno":992
},
{
   "siglaestado":"RS",
   "nomemunicipio":"JOIA",
   "codigoexterno":9829
},
{
   "siglaestado":"RS",
   "nomemunicipio":"JULIO DE CASTILHOS",
   "codigoexterno":8725
},
{
   "siglaestado":"RS",
   "nomemunicipio":"LAGOA BONITA DO SUL",
   "codigoexterno":1148
},
{
   "siglaestado":"RS",
   "nomemunicipio":"LAGOA DOS TRES CANTOS",
   "codigoexterno":5951
},
{
   "siglaestado":"RS",
   "nomemunicipio":"LAGOA VERMELHA",
   "codigoexterno":8727
},
{
   "siglaestado":"RS",
   "nomemunicipio":"LAGOAO",
   "codigoexterno":7389
},
{
   "siglaestado":"RS",
   "nomemunicipio":"LAJEADO",
   "codigoexterno":8729
},
{
   "siglaestado":"RS",
   "nomemunicipio":"LAJEADO DO BUGRE",
   "codigoexterno":5983
},
{
   "siglaestado":"RS",
   "nomemunicipio":"LAVRAS DO SUL",
   "codigoexterno":8731
},
{
   "siglaestado":"RS",
   "nomemunicipio":"LIBERATO SALZANO",
   "codigoexterno":8733
},
{
   "siglaestado":"RS",
   "nomemunicipio":"LINDOLFO COLLOR",
   "codigoexterno":6017
},
{
   "siglaestado":"RS",
   "nomemunicipio":"LINHA NOVA",
   "codigoexterno":6047
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MACAMBARA",
   "codigoexterno":994
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MACHADINHO",
   "codigoexterno":8735
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MAMPITUBA",
   "codigoexterno":996
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MANOEL VIANA",
   "codigoexterno":6079
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MAQUINE",
   "codigoexterno":5783
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MARATA",
   "codigoexterno":6039
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MARAU",
   "codigoexterno":8737
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MARCELINO RAMOS",
   "codigoexterno":8739
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MARIANA PIMENTEL",
   "codigoexterno":5759
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MARIANO MORO",
   "codigoexterno":8741
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MARQUES DE SOUZA",
   "codigoexterno":998
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MATA",
   "codigoexterno":8743
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MATO CASTELHANO",
   "codigoexterno":5931
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MATO LEITAO",
   "codigoexterno":6031
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MATO QUEIMADO",
   "codigoexterno":1150
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MAXIMILIANO DE ALMEIDA",
   "codigoexterno":8745
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MINAS DO LEAO",
   "codigoexterno":5773
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MIRAGUAI",
   "codigoexterno":8747
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MONTAURI",
   "codigoexterno":7387
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MONTE ALEGRE DOS CAMPOS",
   "codigoexterno":1000
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MONTE BELO DO SUL",
   "codigoexterno":5993
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MONTENEGRO",
   "codigoexterno":8749
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MORMACO",
   "codigoexterno":5933
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MORRINHOS DO SUL",
   "codigoexterno":5775
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MORRO REDONDO",
   "codigoexterno":7385
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MORRO REUTER",
   "codigoexterno":6019
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MOSTARDAS",
   "codigoexterno":8751
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MUCUM",
   "codigoexterno":8753
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MUITOS CAPOES",
   "codigoexterno":1002
},
{
   "siglaestado":"RS",
   "nomemunicipio":"MULITERNO",
   "codigoexterno":5935
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NAO-ME-TOQUE",
   "codigoexterno":8755
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NICOLAU VERGUEIRO",
   "codigoexterno":5937
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NONOAI",
   "codigoexterno":8757
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVA ALVORADA",
   "codigoexterno":7383
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVA ARACA",
   "codigoexterno":8759
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVA BASSANO",
   "codigoexterno":8761
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVA BOA VISTA",
   "codigoexterno":5953
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVA BRESCIA",
   "codigoexterno":8763
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVA CANDELARIA",
   "codigoexterno":1004
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVA ESPERANCA DO SUL",
   "codigoexterno":7381
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVA HARTZ",
   "codigoexterno":7379
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVA PADUA",
   "codigoexterno":5991
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVA PALMA",
   "codigoexterno":8765
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVA PETROPOLIS",
   "codigoexterno":8767
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVA PRATA",
   "codigoexterno":8769
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVA RAMADA",
   "codigoexterno":1006
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVA ROMA DO SUL",
   "codigoexterno":7377
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVA SANTA RITA",
   "codigoexterno":5757
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVO BARREIRO",
   "codigoexterno":5985
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVO CABRAIS",
   "codigoexterno":1008
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVO HAMBURGO",
   "codigoexterno":8771
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVO MACHADO",
   "codigoexterno":6057
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVO TIRADENTES",
   "codigoexterno":5973
},
{
   "siglaestado":"RS",
   "nomemunicipio":"NOVO XINGU",
   "codigoexterno":1152
},
{
   "siglaestado":"RS",
   "nomemunicipio":"OSORIO",
   "codigoexterno":8773
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PAIM FILHO",
   "codigoexterno":8775
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PALMARES DO SUL",
   "codigoexterno":8967
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PALMEIRA DAS MISSOES",
   "codigoexterno":8777
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PALMITINHO",
   "codigoexterno":8779
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PANAMBI",
   "codigoexterno":8781
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PANTANO GRANDE",
   "codigoexterno":7375
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PARAI",
   "codigoexterno":8783
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PARAISO DO SUL",
   "codigoexterno":7373
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PARECI NOVO",
   "codigoexterno":6041
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PAROBE",
   "codigoexterno":9825
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PASSA SETE",
   "codigoexterno":1010
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PASSO DO SOBRADO",
   "codigoexterno":5765
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PASSO FUNDO",
   "codigoexterno":8785
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PAULO BENTO",
   "codigoexterno":1154
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PAVERAMA",
   "codigoexterno":7371
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PEDRAS ALTAS",
   "codigoexterno":1156
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PEDRO OSORIO",
   "codigoexterno":8787
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PEJUCARA",
   "codigoexterno":8789
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PELOTAS",
   "codigoexterno":8791
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PICADA CAFE",
   "codigoexterno":6021
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PINHAL",
   "codigoexterno":7369
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PINHAL DA SERRA",
   "codigoexterno":1158
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PINHAL GRANDE",
   "codigoexterno":5787
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PINHEIRINHO DO VALE",
   "codigoexterno":5975
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PINHEIRO MACHADO",
   "codigoexterno":8793
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PINTO BANDEIRA",
   "codigoexterno":1160
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PIRAPO",
   "codigoexterno":7367
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PIRATINI",
   "codigoexterno":8795
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PLANALTO",
   "codigoexterno":8797
},
{
   "siglaestado":"RS",
   "nomemunicipio":"POCO DAS ANTAS",
   "codigoexterno":7365
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PONTAO",
   "codigoexterno":5939
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PONTE PRETA",
   "codigoexterno":5967
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PORTAO",
   "codigoexterno":8799
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PORTO ALEGRE",
   "codigoexterno":8801
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PORTO LUCENA",
   "codigoexterno":8803
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PORTO MAUA",
   "codigoexterno":6065
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PORTO VERA CRUZ",
   "codigoexterno":6067
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PORTO XAVIER",
   "codigoexterno":8805
},
{
   "siglaestado":"RS",
   "nomemunicipio":"POUSO NOVO",
   "codigoexterno":7363
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PRESIDENTE LUCENA",
   "codigoexterno":6023
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PROGRESSO",
   "codigoexterno":7361
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PROTASIO ALVES",
   "codigoexterno":7359
},
{
   "siglaestado":"RS",
   "nomemunicipio":"PUTINGA",
   "codigoexterno":8807
},
{
   "siglaestado":"RS",
   "nomemunicipio":"QUARAI",
   "codigoexterno":8809
},
{
   "siglaestado":"RS",
   "nomemunicipio":"QUATRO IRM??OS",
   "codigoexterno":1162
},
{
   "siglaestado":"RS",
   "nomemunicipio":"QUEVEDOS",
   "codigoexterno":5789
},
{
   "siglaestado":"RS",
   "nomemunicipio":"QUINZE DE NOVEMBRO",
   "codigoexterno":7357
},
{
   "siglaestado":"RS",
   "nomemunicipio":"REDENTORA",
   "codigoexterno":8811
},
{
   "siglaestado":"RS",
   "nomemunicipio":"RELVADO",
   "codigoexterno":7355
},
{
   "siglaestado":"RS",
   "nomemunicipio":"RESTINGA SECA",
   "codigoexterno":8813
},
{
   "siglaestado":"RS",
   "nomemunicipio":"RIO DOS INDIOS",
   "codigoexterno":5955
},
{
   "siglaestado":"RS",
   "nomemunicipio":"RIO GRANDE",
   "codigoexterno":8815
},
{
   "siglaestado":"RS",
   "nomemunicipio":"RIO PARDO",
   "codigoexterno":8817
},
{
   "siglaestado":"RS",
   "nomemunicipio":"RIOZINHO",
   "codigoexterno":7353
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ROCA SALES",
   "codigoexterno":8819
},
{
   "siglaestado":"RS",
   "nomemunicipio":"RODEIO BONITO",
   "codigoexterno":8821
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ROLADOR",
   "codigoexterno":1164
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ROLANTE",
   "codigoexterno":8823
},
{
   "siglaestado":"RS",
   "nomemunicipio":"RONDA ALTA",
   "codigoexterno":8825
},
{
   "siglaestado":"RS",
   "nomemunicipio":"RONDINHA",
   "codigoexterno":8827
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ROQUE GONZALES",
   "codigoexterno":8829
},
{
   "siglaestado":"RS",
   "nomemunicipio":"ROSARIO DO SUL",
   "codigoexterno":8831
},
{
   "siglaestado":"RS",
   "nomemunicipio":"S??O JOS?? DO SUL",
   "codigoexterno":1170
},
{
   "siglaestado":"RS",
   "nomemunicipio":"S??O PEDRO DAS MISS?-ES",
   "codigoexterno":1172
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAGRADA FAMILIA",
   "codigoexterno":5987
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SALDANHA MARINHO",
   "codigoexterno":7339
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SALTO DO JACUI",
   "codigoexterno":8975
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SALVADOR DAS MISSOES",
   "codigoexterno":6061
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SALVADOR DO SUL",
   "codigoexterno":8833
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANANDUVA",
   "codigoexterno":8835
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTA BARBARA DO SUL",
   "codigoexterno":8837
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTA CEC??LIA DO SUL",
   "codigoexterno":1166
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTA CLARA DO SUL",
   "codigoexterno":6033
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTA CRUZ DO SUL",
   "codigoexterno":8839
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTA MARGARIDA DO SUL",
   "codigoexterno":1168
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTA MARIA",
   "codigoexterno":8841
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTA MARIA DO HERVAL",
   "codigoexterno":7337
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTA ROSA",
   "codigoexterno":8847
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTA TEREZA",
   "codigoexterno":5995
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTA VITORIA DO PALMAR",
   "codigoexterno":8849
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTANA DA BOA VISTA",
   "codigoexterno":8843
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTANA DO LIVRAMENTO",
   "codigoexterno":8845
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTIAGO",
   "codigoexterno":8851
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTO ANGELO",
   "codigoexterno":8853
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTO ANTONIO DA PATRULHA",
   "codigoexterno":8855
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTO ANTONIO DAS MISSOES",
   "codigoexterno":8857
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTO ANTONIO DO PALMA",
   "codigoexterno":5941
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTO ANTONIO DO PLANALTO",
   "codigoexterno":5957
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTO AUGUSTO",
   "codigoexterno":8859
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTO CRISTO",
   "codigoexterno":8861
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SANTO EXPEDITO DO SUL",
   "codigoexterno":5977
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO BORJA",
   "codigoexterno":8863
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO DOMINGOS DO SUL",
   "codigoexterno":7351
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO FRANCISCO DE ASSIS",
   "codigoexterno":8865
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO FRANCISCO DE PAULA",
   "codigoexterno":8867
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO GABRIEL",
   "codigoexterno":8869
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO JERONIMO",
   "codigoexterno":8871
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO JOAO DA URTIGA",
   "codigoexterno":7349
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO JOAO DO POLESINE",
   "codigoexterno":5791
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO JORGE",
   "codigoexterno":7347
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO JOSE DAS MISSOES",
   "codigoexterno":5989
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO JOSE DO HERVAL",
   "codigoexterno":7345
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO JOSE DO HORTENCIO",
   "codigoexterno":7343
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO JOSE DO INHACORA",
   "codigoexterno":6059
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO JOSE DO NORTE",
   "codigoexterno":8873
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO JOSE DO OURO",
   "codigoexterno":8875
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO JOSE DOS AUSENTES",
   "codigoexterno":6015
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO LEOPOLDO",
   "codigoexterno":8877
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO LOURENCO DO SUL",
   "codigoexterno":8879
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO LUIZ GONZAGA",
   "codigoexterno":8881
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO MARCOS",
   "codigoexterno":8883
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO MARTINHO",
   "codigoexterno":8885
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO MARTINHO DA SERRA",
   "codigoexterno":5793
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO MIGUEL DAS MISSOES",
   "codigoexterno":7341
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO NICOLAU",
   "codigoexterno":8887
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO PAULO DAS MISSOES",
   "codigoexterno":8889
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO PEDRO DA SERRA",
   "codigoexterno":6043
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO PEDRO DO BUTIA",
   "codigoexterno":6063
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO PEDRO DO SUL",
   "codigoexterno":8891
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO SEBASTIAO DO CAI",
   "codigoexterno":8893
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO SEPE",
   "codigoexterno":8895
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO VALENTIM",
   "codigoexterno":8897
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO VALENTIM DO SUL",
   "codigoexterno":5997
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO VALERIO DO SUL",
   "codigoexterno":6075
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO VENDELINO",
   "codigoexterno":7293
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAO VICENTE DO SUL",
   "codigoexterno":8675
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAPIRANGA",
   "codigoexterno":8899
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SAPUCAIA DO SUL",
   "codigoexterno":8901
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SARANDI",
   "codigoexterno":8903
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SEBERI",
   "codigoexterno":8905
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SEDE NOVA",
   "codigoexterno":7335
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SEGREDO",
   "codigoexterno":7317
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SELBACH",
   "codigoexterno":8907
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SENADOR SALGADO FILHO",
   "codigoexterno":1012
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SENTINELA DO SUL",
   "codigoexterno":5781
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SERAFINA CORREA",
   "codigoexterno":8909
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SERIO",
   "codigoexterno":6035
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SERTAO",
   "codigoexterno":8911
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SERTAO SANTANA",
   "codigoexterno":5761
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SETE DE SETEMBRO",
   "codigoexterno":1014
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SEVERIANO DE ALMEIDA",
   "codigoexterno":8913
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SILVEIRA MARTINS",
   "codigoexterno":7315
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SINIMBU",
   "codigoexterno":5767
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SOBRADINHO",
   "codigoexterno":8917
},
{
   "siglaestado":"RS",
   "nomemunicipio":"SOLEDADE",
   "codigoexterno":8919
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TABAI",
   "codigoexterno":1016
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TAPEJARA",
   "codigoexterno":8921
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TAPERA",
   "codigoexterno":8923
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TAPES",
   "codigoexterno":8925
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TAQUARA",
   "codigoexterno":8927
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TAQUARI",
   "codigoexterno":8929
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TAQUARUCU DO SUL",
   "codigoexterno":7313
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TAVARES",
   "codigoexterno":8971
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TENENTE PORTELA",
   "codigoexterno":8931
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TERRA DE AREIA",
   "codigoexterno":7333
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TEUTONIA",
   "codigoexterno":9821
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TIO HUGO",
   "codigoexterno":1174
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TIRADENTES DO SUL",
   "codigoexterno":6077
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TOROPI",
   "codigoexterno":1018
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TORRES",
   "codigoexterno":8933
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TRAMANDAI",
   "codigoexterno":8935
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TRAVESSEIRO",
   "codigoexterno":6037
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TRES ARROIOS",
   "codigoexterno":7331
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TRES CACHOEIRAS",
   "codigoexterno":7329
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TRES COROAS",
   "codigoexterno":8937
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TRES DE MAIO",
   "codigoexterno":8939
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TRES FORQUILHAS",
   "codigoexterno":5777
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TRES PALMEIRAS",
   "codigoexterno":7327
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TRES PASSOS",
   "codigoexterno":8941
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TRINDADE DO SUL",
   "codigoexterno":7325
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TRIUNFO",
   "codigoexterno":8943
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TUCUNDUVA",
   "codigoexterno":8945
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TUNAS",
   "codigoexterno":7323
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TUPANCI DO SUL",
   "codigoexterno":5979
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TUPANCIRETA",
   "codigoexterno":8947
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TUPANDI",
   "codigoexterno":7321
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TUPARENDI",
   "codigoexterno":8949
},
{
   "siglaestado":"RS",
   "nomemunicipio":"TURUCU",
   "codigoexterno":1020
},
{
   "siglaestado":"RS",
   "nomemunicipio":"UBIRETAMA",
   "codigoexterno":1022
},
{
   "siglaestado":"RS",
   "nomemunicipio":"UNIAO DA SERRA",
   "codigoexterno":5999
},
{
   "siglaestado":"RS",
   "nomemunicipio":"UNISTALDA",
   "codigoexterno":1024
},
{
   "siglaestado":"RS",
   "nomemunicipio":"URUGUAIANA",
   "codigoexterno":8951
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VACARIA",
   "codigoexterno":8953
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VALE DO SOL",
   "codigoexterno":5769
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VALE REAL",
   "codigoexterno":6049
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VALE VERDE",
   "codigoexterno":1026
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VANINI",
   "codigoexterno":7319
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VENANCIO AIRES",
   "codigoexterno":8955
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VERA CRUZ",
   "codigoexterno":8957
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VERANOPOLIS",
   "codigoexterno":8959
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VESPASIANO CORREA",
   "codigoexterno":1028
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VIADUTOS",
   "codigoexterno":8961
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VIAMAO",
   "codigoexterno":8963
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VICENTE DUTRA",
   "codigoexterno":8965
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VICTOR GRAEFF",
   "codigoexterno":8969
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VILA FLORES",
   "codigoexterno":7311
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VILA LANGARO",
   "codigoexterno":1030
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VILA MARIA",
   "codigoexterno":7309
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VILA NOVA DO SUL",
   "codigoexterno":5795
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VISTA ALEGRE",
   "codigoexterno":7307
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VISTA ALEGRE DO PRATA",
   "codigoexterno":7305
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VISTA GAUCHA",
   "codigoexterno":7303
},
{
   "siglaestado":"RS",
   "nomemunicipio":"VITORIA DAS MISSOES",
   "codigoexterno":6053
},
{
   "siglaestado":"RS",
   "nomemunicipio":"WESTF??LIA",
   "codigoexterno":1176
},
{
   "siglaestado":"RS",
   "nomemunicipio":"XANGRI-LA",
   "codigoexterno":5785
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ABDON BATISTA",
   "codigoexterno":9939
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ABELARDO LUZ",
   "codigoexterno":8001
},
{
   "siglaestado":"SC",
   "nomemunicipio":"AGROLANDIA",
   "codigoexterno":8003
},
{
   "siglaestado":"SC",
   "nomemunicipio":"AGRONOMICA",
   "codigoexterno":8005
},
{
   "siglaestado":"SC",
   "nomemunicipio":"AGUA DOCE",
   "codigoexterno":8007
},
{
   "siglaestado":"SC",
   "nomemunicipio":"AGUAS DE CHAPECO",
   "codigoexterno":8009
},
{
   "siglaestado":"SC",
   "nomemunicipio":"AGUAS FRIAS",
   "codigoexterno":5577
},
{
   "siglaestado":"SC",
   "nomemunicipio":"AGUAS MORNAS",
   "codigoexterno":8011
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ALFREDO WAGNER",
   "codigoexterno":8013
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ALTO BELA VISTA",
   "codigoexterno":886
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ANCHIETA",
   "codigoexterno":8015
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ANGELINA",
   "codigoexterno":8017
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ANITA GARIBALDI",
   "codigoexterno":8019
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ANITAPOLIS",
   "codigoexterno":8021
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ANTONIO CARLOS",
   "codigoexterno":8023
},
{
   "siglaestado":"SC",
   "nomemunicipio":"APIUNA",
   "codigoexterno":9941
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ARABUTA",
   "codigoexterno":5597
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ARAQUARI",
   "codigoexterno":8025
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ARARANGUA",
   "codigoexterno":8027
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ARMAZEM",
   "codigoexterno":8029
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ARROIO TRINTA",
   "codigoexterno":8031
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ARVOREDO",
   "codigoexterno":5599
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ASCURRA",
   "codigoexterno":8033
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ATALANTA",
   "codigoexterno":8035
},
{
   "siglaestado":"SC",
   "nomemunicipio":"AURORA",
   "codigoexterno":8037
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BALNEARIO ARROIO DO SILVA",
   "codigoexterno":888
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BALNEARIO BARRA DO SUL",
   "codigoexterno":5549
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BALNEARIO CAMBORIU",
   "codigoexterno":8039
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BALNEARIO DE PICARRAS",
   "codigoexterno":8251
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BALNEARIO GAIVOTA",
   "codigoexterno":890
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BALNEARIO RINCAO",
   "codigoexterno":1192
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BANDEIRANTE",
   "codigoexterno":892
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BARRA BONITA",
   "codigoexterno":894
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BARRA VELHA",
   "codigoexterno":8041
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BELA VISTA DO TOLDO",
   "codigoexterno":896
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BELMONTE",
   "codigoexterno":5745
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BENEDITO NOVO",
   "codigoexterno":8043
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BIGUACU",
   "codigoexterno":8045
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BLUMENAU",
   "codigoexterno":8047
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BOCAINA DO SUL",
   "codigoexterno":898
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BOM JARDIM DA SERRA",
   "codigoexterno":8389
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BOM JESUS",
   "codigoexterno":900
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BOM JESUS DO OESTE",
   "codigoexterno":902
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BOM RETIRO",
   "codigoexterno":8049
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BOMBINHAS",
   "codigoexterno":5537
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BOTUVERA",
   "codigoexterno":8051
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BRACO DO NORTE",
   "codigoexterno":8053
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BRACO DO TROMBUDO",
   "codigoexterno":5557
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BRUNOPOLIS",
   "codigoexterno":904
},
{
   "siglaestado":"SC",
   "nomemunicipio":"BRUSQUE",
   "codigoexterno":8055
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CACADOR",
   "codigoexterno":8057
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CAIBI",
   "codigoexterno":8059
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CALMON",
   "codigoexterno":5553
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CAMBORIU",
   "codigoexterno":8061
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CAMPO ALEGRE",
   "codigoexterno":8063
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CAMPO BELO DO SUL",
   "codigoexterno":8065
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CAMPO ERE",
   "codigoexterno":8067
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CAMPOS NOVOS",
   "codigoexterno":8069
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CANELINHA",
   "codigoexterno":8071
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CANOINHAS",
   "codigoexterno":8073
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CAPAO ALTO",
   "codigoexterno":906
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CAPINZAL",
   "codigoexterno":8075
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CAPIVARI DE BAIXO",
   "codigoexterno":5545
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CATANDUVAS",
   "codigoexterno":8077
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CAXAMBU DO SUL",
   "codigoexterno":8079
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CELSO RAMOS",
   "codigoexterno":9943
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CERRO NEGRO",
   "codigoexterno":5567
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CHAPADAO DO LAGEADO",
   "codigoexterno":908
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CHAPECO",
   "codigoexterno":8081
},
{
   "siglaestado":"SC",
   "nomemunicipio":"COCAL DO SUL",
   "codigoexterno":5543
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CONCORDIA",
   "codigoexterno":8083
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CORDILHEIRA ALTA",
   "codigoexterno":5579
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CORONEL FREITAS",
   "codigoexterno":8085
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CORONEL MARTINS",
   "codigoexterno":5735
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CORREIA PINTO",
   "codigoexterno":8395
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CORUPA",
   "codigoexterno":8087
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CRICIUMA",
   "codigoexterno":8089
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CUNHA PORA",
   "codigoexterno":8091
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CUNHATAI",
   "codigoexterno":910
},
{
   "siglaestado":"SC",
   "nomemunicipio":"CURITIBANOS",
   "codigoexterno":8093
},
{
   "siglaestado":"SC",
   "nomemunicipio":"DESCANSO",
   "codigoexterno":8095
},
{
   "siglaestado":"SC",
   "nomemunicipio":"DIONISIO CERQUEIRA",
   "codigoexterno":8097
},
{
   "siglaestado":"SC",
   "nomemunicipio":"DONA EMMA",
   "codigoexterno":8099
},
{
   "siglaestado":"SC",
   "nomemunicipio":"DOUTOR PEDRINHO",
   "codigoexterno":9945
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ENTRE RIOS",
   "codigoexterno":912
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ERMO",
   "codigoexterno":914
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ERVAL VELHO",
   "codigoexterno":8101
},
{
   "siglaestado":"SC",
   "nomemunicipio":"FAXINAL DOS GUEDES",
   "codigoexterno":8103
},
{
   "siglaestado":"SC",
   "nomemunicipio":"FLOR DO SERTAO",
   "codigoexterno":916
},
{
   "siglaestado":"SC",
   "nomemunicipio":"FLORIANOPOLIS",
   "codigoexterno":8105
},
{
   "siglaestado":"SC",
   "nomemunicipio":"FORMOSA DO SUL",
   "codigoexterno":5581
},
{
   "siglaestado":"SC",
   "nomemunicipio":"FORQUILHINHA",
   "codigoexterno":973
},
{
   "siglaestado":"SC",
   "nomemunicipio":"FRAIBURGO",
   "codigoexterno":8107
},
{
   "siglaestado":"SC",
   "nomemunicipio":"FREI ROGERIO",
   "codigoexterno":918
},
{
   "siglaestado":"SC",
   "nomemunicipio":"GALVAO",
   "codigoexterno":8109
},
{
   "siglaestado":"SC",
   "nomemunicipio":"GAROPABA",
   "codigoexterno":8113
},
{
   "siglaestado":"SC",
   "nomemunicipio":"GARUVA",
   "codigoexterno":8115
},
{
   "siglaestado":"SC",
   "nomemunicipio":"GASPAR",
   "codigoexterno":8117
},
{
   "siglaestado":"SC",
   "nomemunicipio":"GOVERNADOR CELSO RAMOS",
   "codigoexterno":8111
},
{
   "siglaestado":"SC",
   "nomemunicipio":"GRAO PARA",
   "codigoexterno":8119
},
{
   "siglaestado":"SC",
   "nomemunicipio":"GRAVATAL",
   "codigoexterno":8121
},
{
   "siglaestado":"SC",
   "nomemunicipio":"GUABIRUBA",
   "codigoexterno":8123
},
{
   "siglaestado":"SC",
   "nomemunicipio":"GUARACIABA",
   "codigoexterno":8125
},
{
   "siglaestado":"SC",
   "nomemunicipio":"GUARAMIRIM",
   "codigoexterno":8127
},
{
   "siglaestado":"SC",
   "nomemunicipio":"GUARUJA DO SUL",
   "codigoexterno":8129
},
{
   "siglaestado":"SC",
   "nomemunicipio":"GUATAMBU",
   "codigoexterno":5583
},
{
   "siglaestado":"SC",
   "nomemunicipio":"HERVAL D\'OESTE",
   "codigoexterno":8131
},
{
   "siglaestado":"SC",
   "nomemunicipio":"IBIAM",
   "codigoexterno":920
},
{
   "siglaestado":"SC",
   "nomemunicipio":"IBICARE",
   "codigoexterno":8133
},
{
   "siglaestado":"SC",
   "nomemunicipio":"IBIRAMA",
   "codigoexterno":8135
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ICARA",
   "codigoexterno":8137
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ILHOTA",
   "codigoexterno":8139
},
{
   "siglaestado":"SC",
   "nomemunicipio":"IMARUI",
   "codigoexterno":8141
},
{
   "siglaestado":"SC",
   "nomemunicipio":"IMBITUBA",
   "codigoexterno":8143
},
{
   "siglaestado":"SC",
   "nomemunicipio":"IMBUIA",
   "codigoexterno":8145
},
{
   "siglaestado":"SC",
   "nomemunicipio":"INDAIAL",
   "codigoexterno":8147
},
{
   "siglaestado":"SC",
   "nomemunicipio":"IOMERE",
   "codigoexterno":922
},
{
   "siglaestado":"SC",
   "nomemunicipio":"IPIRA",
   "codigoexterno":8149
},
{
   "siglaestado":"SC",
   "nomemunicipio":"IPORA DO OESTE",
   "codigoexterno":9951
},
{
   "siglaestado":"SC",
   "nomemunicipio":"IPUACU",
   "codigoexterno":5737
},
{
   "siglaestado":"SC",
   "nomemunicipio":"IPUMIRIM",
   "codigoexterno":8151
},
{
   "siglaestado":"SC",
   "nomemunicipio":"IRACEMINHA",
   "codigoexterno":9953
},
{
   "siglaestado":"SC",
   "nomemunicipio":"IRANI",
   "codigoexterno":8153
},
{
   "siglaestado":"SC",
   "nomemunicipio":"IRATI",
   "codigoexterno":5585
},
{
   "siglaestado":"SC",
   "nomemunicipio":"IRINEOPOLIS",
   "codigoexterno":8155
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ITA",
   "codigoexterno":8157
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ITAIOPOLIS",
   "codigoexterno":8159
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ITAJAI",
   "codigoexterno":8161
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ITAPEMA",
   "codigoexterno":8163
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ITAPIRANGA",
   "codigoexterno":8165
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ITAPOA",
   "codigoexterno":9985
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ITUPORANGA",
   "codigoexterno":8167
},
{
   "siglaestado":"SC",
   "nomemunicipio":"JABORA",
   "codigoexterno":8169
},
{
   "siglaestado":"SC",
   "nomemunicipio":"JACINTO MACHADO",
   "codigoexterno":8171
},
{
   "siglaestado":"SC",
   "nomemunicipio":"JAGUARUNA",
   "codigoexterno":8173
},
{
   "siglaestado":"SC",
   "nomemunicipio":"JARAGUA DO SUL",
   "codigoexterno":8175
},
{
   "siglaestado":"SC",
   "nomemunicipio":"JARDINOPOLIS",
   "codigoexterno":5587
},
{
   "siglaestado":"SC",
   "nomemunicipio":"JOACABA",
   "codigoexterno":8177
},
{
   "siglaestado":"SC",
   "nomemunicipio":"JOINVILLE",
   "codigoexterno":8179
},
{
   "siglaestado":"SC",
   "nomemunicipio":"JOSE BOITEUX",
   "codigoexterno":9957
},
{
   "siglaestado":"SC",
   "nomemunicipio":"JUPIA",
   "codigoexterno":924
},
{
   "siglaestado":"SC",
   "nomemunicipio":"LACERDOPOLIS",
   "codigoexterno":8181
},
{
   "siglaestado":"SC",
   "nomemunicipio":"LAGEADO GRANDE",
   "codigoexterno":5739
},
{
   "siglaestado":"SC",
   "nomemunicipio":"LAGES",
   "codigoexterno":8183
},
{
   "siglaestado":"SC",
   "nomemunicipio":"LAGUNA",
   "codigoexterno":8185
},
{
   "siglaestado":"SC",
   "nomemunicipio":"LAURENTINO",
   "codigoexterno":8187
},
{
   "siglaestado":"SC",
   "nomemunicipio":"LAURO MULLER",
   "codigoexterno":8189
},
{
   "siglaestado":"SC",
   "nomemunicipio":"LEBON REGIS",
   "codigoexterno":8191
},
{
   "siglaestado":"SC",
   "nomemunicipio":"LEOBERTO LEAL",
   "codigoexterno":8193
},
{
   "siglaestado":"SC",
   "nomemunicipio":"LINDOIA DO SUL",
   "codigoexterno":9961
},
{
   "siglaestado":"SC",
   "nomemunicipio":"LONTRAS",
   "codigoexterno":8195
},
{
   "siglaestado":"SC",
   "nomemunicipio":"LUIZ ALVES",
   "codigoexterno":8197
},
{
   "siglaestado":"SC",
   "nomemunicipio":"LUZERNA",
   "codigoexterno":926
},
{
   "siglaestado":"SC",
   "nomemunicipio":"MACIEIRA",
   "codigoexterno":5575
},
{
   "siglaestado":"SC",
   "nomemunicipio":"MAFRA",
   "codigoexterno":8199
},
{
   "siglaestado":"SC",
   "nomemunicipio":"MAJOR GERCINO",
   "codigoexterno":8201
},
{
   "siglaestado":"SC",
   "nomemunicipio":"MAJOR VIEIRA",
   "codigoexterno":8203
},
{
   "siglaestado":"SC",
   "nomemunicipio":"MARACAJA",
   "codigoexterno":8391
},
{
   "siglaestado":"SC",
   "nomemunicipio":"MARAVILHA",
   "codigoexterno":8205
},
{
   "siglaestado":"SC",
   "nomemunicipio":"MAREMA",
   "codigoexterno":9963
},
{
   "siglaestado":"SC",
   "nomemunicipio":"MASSARANDUBA",
   "codigoexterno":8207
},
{
   "siglaestado":"SC",
   "nomemunicipio":"MATOS COSTA",
   "codigoexterno":8209
},
{
   "siglaestado":"SC",
   "nomemunicipio":"MELEIRO",
   "codigoexterno":8211
},
{
   "siglaestado":"SC",
   "nomemunicipio":"MIRIM DOCE",
   "codigoexterno":5559
},
{
   "siglaestado":"SC",
   "nomemunicipio":"MODELO",
   "codigoexterno":8213
},
{
   "siglaestado":"SC",
   "nomemunicipio":"MONDAI",
   "codigoexterno":8215
},
{
   "siglaestado":"SC",
   "nomemunicipio":"MONTE CARLO",
   "codigoexterno":5561
},
{
   "siglaestado":"SC",
   "nomemunicipio":"MONTE CASTELO",
   "codigoexterno":8217
},
{
   "siglaestado":"SC",
   "nomemunicipio":"MORRO DA FUMACA",
   "codigoexterno":8219
},
{
   "siglaestado":"SC",
   "nomemunicipio":"MORRO GRANDE",
   "codigoexterno":5539
},
{
   "siglaestado":"SC",
   "nomemunicipio":"NAVEGANTES",
   "codigoexterno":8221
},
{
   "siglaestado":"SC",
   "nomemunicipio":"NOVA ERECHIM",
   "codigoexterno":8223
},
{
   "siglaestado":"SC",
   "nomemunicipio":"NOVA ITABERABA",
   "codigoexterno":5589
},
{
   "siglaestado":"SC",
   "nomemunicipio":"NOVA TRENTO",
   "codigoexterno":8225
},
{
   "siglaestado":"SC",
   "nomemunicipio":"NOVA VENEZA",
   "codigoexterno":8227
},
{
   "siglaestado":"SC",
   "nomemunicipio":"NOVO HORIZONTE",
   "codigoexterno":5591
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ORLEANS",
   "codigoexterno":8229
},
{
   "siglaestado":"SC",
   "nomemunicipio":"OTACILIO COSTA",
   "codigoexterno":8397
},
{
   "siglaestado":"SC",
   "nomemunicipio":"OURO",
   "codigoexterno":8231
},
{
   "siglaestado":"SC",
   "nomemunicipio":"OURO VERDE",
   "codigoexterno":5741
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PAIAL",
   "codigoexterno":928
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PAINEL",
   "codigoexterno":930
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PALHOCA",
   "codigoexterno":8233
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PALMA SOLA",
   "codigoexterno":8235
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PALMEIRA",
   "codigoexterno":932
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PALMITOS",
   "codigoexterno":8237
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PAPANDUVA",
   "codigoexterno":8239
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PARAISO",
   "codigoexterno":5747
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PASSO DE TORRES",
   "codigoexterno":5541
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PASSOS MAIA",
   "codigoexterno":5743
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PAULO LOPES",
   "codigoexterno":8241
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PEDRAS GRANDES",
   "codigoexterno":8243
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PENHA",
   "codigoexterno":8245
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PERITIBA",
   "codigoexterno":8247
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PESCARIA BRAVA",
   "codigoexterno":1194
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PETROLANDIA",
   "codigoexterno":8249
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PINHALZINHO",
   "codigoexterno":8253
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PINHEIRO PRETO",
   "codigoexterno":8255
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PIRATUBA",
   "codigoexterno":8257
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PLANALTO ALEGRE",
   "codigoexterno":5593
},
{
   "siglaestado":"SC",
   "nomemunicipio":"POMERODE",
   "codigoexterno":8259
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PONTE ALTA",
   "codigoexterno":8261
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PONTE ALTA DO NORTE",
   "codigoexterno":5569
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PONTE SERRADA",
   "codigoexterno":8263
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PORTO BELO",
   "codigoexterno":8265
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PORTO UNIAO",
   "codigoexterno":8267
},
{
   "siglaestado":"SC",
   "nomemunicipio":"POUSO REDONDO",
   "codigoexterno":8269
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PRAIA GRANDE",
   "codigoexterno":8271
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PRESIDENTE CASTELO BRANCO",
   "codigoexterno":8273
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PRESIDENTE GETULIO",
   "codigoexterno":8275
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PRESIDENTE NEREU",
   "codigoexterno":8277
},
{
   "siglaestado":"SC",
   "nomemunicipio":"PRINCESA",
   "codigoexterno":934
},
{
   "siglaestado":"SC",
   "nomemunicipio":"QUILOMBO",
   "codigoexterno":8279
},
{
   "siglaestado":"SC",
   "nomemunicipio":"RANCHO QUEIMADO",
   "codigoexterno":8281
},
{
   "siglaestado":"SC",
   "nomemunicipio":"RIO DAS ANTAS",
   "codigoexterno":8283
},
{
   "siglaestado":"SC",
   "nomemunicipio":"RIO DO CAMPO",
   "codigoexterno":8285
},
{
   "siglaestado":"SC",
   "nomemunicipio":"RIO DO OESTE",
   "codigoexterno":8287
},
{
   "siglaestado":"SC",
   "nomemunicipio":"RIO DO SUL",
   "codigoexterno":8291
},
{
   "siglaestado":"SC",
   "nomemunicipio":"RIO DOS CEDROS",
   "codigoexterno":8289
},
{
   "siglaestado":"SC",
   "nomemunicipio":"RIO FORTUNA",
   "codigoexterno":8293
},
{
   "siglaestado":"SC",
   "nomemunicipio":"RIO NEGRINHO",
   "codigoexterno":8295
},
{
   "siglaestado":"SC",
   "nomemunicipio":"RIO RUFINO",
   "codigoexterno":5571
},
{
   "siglaestado":"SC",
   "nomemunicipio":"RIQUEZA",
   "codigoexterno":5749
},
{
   "siglaestado":"SC",
   "nomemunicipio":"RODEIO",
   "codigoexterno":8297
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ROMELANDIA",
   "codigoexterno":8299
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SALETE",
   "codigoexterno":8301
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SALTINHO",
   "codigoexterno":936
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SALTO VELOSO",
   "codigoexterno":8303
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SANGAO",
   "codigoexterno":5547
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SANTA CECILIA",
   "codigoexterno":8305
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SANTA HELENA",
   "codigoexterno":5751
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SANTA ROSA DE LIMA",
   "codigoexterno":8307
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SANTA ROSA DO SUL",
   "codigoexterno":9967
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SANTA TEREZINHA",
   "codigoexterno":5555
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SANTA TEREZINHA DO PROGRESSO",
   "codigoexterno":938
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SANTIAGO DO SUL",
   "codigoexterno":940
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SANTO AMARO DA IMPERATRIZ",
   "codigoexterno":8309
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO BENTO DO SUL",
   "codigoexterno":8311
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO BERNARDINO",
   "codigoexterno":942
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO BONIFACIO",
   "codigoexterno":8313
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO CARLOS",
   "codigoexterno":8315
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO CRISTOVAO DO SUL",
   "codigoexterno":5573
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO DOMINGOS",
   "codigoexterno":8317
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO FRANCISCO DO SUL",
   "codigoexterno":8319
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO JOAO BATISTA",
   "codigoexterno":8321
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO JOAO DO ITAPERIU",
   "codigoexterno":5551
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO JOAO DO OESTE",
   "codigoexterno":5753
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO JOAO DO SUL",
   "codigoexterno":8323
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO JOAQUIM",
   "codigoexterno":8325
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO JOSE",
   "codigoexterno":8327
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO JOSE DO CEDRO",
   "codigoexterno":8329
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO JOSE DO CERRITO",
   "codigoexterno":8331
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO LOURENCO D\'OESTE",
   "codigoexterno":8333
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO LUDGERO",
   "codigoexterno":8335
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO MARTINHO",
   "codigoexterno":8337
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO MIGUEL DA BOA VISTA",
   "codigoexterno":5755
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO MIGUEL D\'OESTE",
   "codigoexterno":8339
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAO PEDRO DE ALCANTARA",
   "codigoexterno":944
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SAUDADES",
   "codigoexterno":8341
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SCHROEDER",
   "codigoexterno":8343
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SEARA",
   "codigoexterno":8345
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SERRA ALTA",
   "codigoexterno":9989
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SIDEROPOLIS",
   "codigoexterno":8347
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SOMBRIO",
   "codigoexterno":8349
},
{
   "siglaestado":"SC",
   "nomemunicipio":"SUL BRASIL",
   "codigoexterno":5595
},
{
   "siglaestado":"SC",
   "nomemunicipio":"TAIO",
   "codigoexterno":8351
},
{
   "siglaestado":"SC",
   "nomemunicipio":"TANGARA",
   "codigoexterno":8353
},
{
   "siglaestado":"SC",
   "nomemunicipio":"TIGRINHOS",
   "codigoexterno":946
},
{
   "siglaestado":"SC",
   "nomemunicipio":"TIJUCAS",
   "codigoexterno":8355
},
{
   "siglaestado":"SC",
   "nomemunicipio":"TIMBE DO SUL",
   "codigoexterno":8393
},
{
   "siglaestado":"SC",
   "nomemunicipio":"TIMBO",
   "codigoexterno":8357
},
{
   "siglaestado":"SC",
   "nomemunicipio":"TIMBO GRANDE",
   "codigoexterno":9971
},
{
   "siglaestado":"SC",
   "nomemunicipio":"TRES BARRAS",
   "codigoexterno":8359
},
{
   "siglaestado":"SC",
   "nomemunicipio":"TREVISO",
   "codigoexterno":948
},
{
   "siglaestado":"SC",
   "nomemunicipio":"TREZE DE MAIO",
   "codigoexterno":8361
},
{
   "siglaestado":"SC",
   "nomemunicipio":"TREZE TILIAS",
   "codigoexterno":8363
},
{
   "siglaestado":"SC",
   "nomemunicipio":"TROMBUDO CENTRAL",
   "codigoexterno":8365
},
{
   "siglaestado":"SC",
   "nomemunicipio":"TUBARAO",
   "codigoexterno":8367
},
{
   "siglaestado":"SC",
   "nomemunicipio":"TUNAPOLIS",
   "codigoexterno":9991
},
{
   "siglaestado":"SC",
   "nomemunicipio":"TURVO",
   "codigoexterno":8369
},
{
   "siglaestado":"SC",
   "nomemunicipio":"UNIAO DO OESTE",
   "codigoexterno":9973
},
{
   "siglaestado":"SC",
   "nomemunicipio":"URUBICI",
   "codigoexterno":8371
},
{
   "siglaestado":"SC",
   "nomemunicipio":"URUPEMA",
   "codigoexterno":9975
},
{
   "siglaestado":"SC",
   "nomemunicipio":"URUSSANGA",
   "codigoexterno":8373
},
{
   "siglaestado":"SC",
   "nomemunicipio":"VARGEAO",
   "codigoexterno":8375
},
{
   "siglaestado":"SC",
   "nomemunicipio":"VARGEM",
   "codigoexterno":5563
},
{
   "siglaestado":"SC",
   "nomemunicipio":"VARGEM BONITA",
   "codigoexterno":5565
},
{
   "siglaestado":"SC",
   "nomemunicipio":"VIDAL RAMOS",
   "codigoexterno":8377
},
{
   "siglaestado":"SC",
   "nomemunicipio":"VIDEIRA",
   "codigoexterno":8379
},
{
   "siglaestado":"SC",
   "nomemunicipio":"VITOR MEIRELES",
   "codigoexterno":9977
},
{
   "siglaestado":"SC",
   "nomemunicipio":"WITMARSUM",
   "codigoexterno":8381
},
{
   "siglaestado":"SC",
   "nomemunicipio":"XANXERE",
   "codigoexterno":8383
},
{
   "siglaestado":"SC",
   "nomemunicipio":"XAVANTINA",
   "codigoexterno":8385
},
{
   "siglaestado":"SC",
   "nomemunicipio":"XAXIM",
   "codigoexterno":8387
},
{
   "siglaestado":"SC",
   "nomemunicipio":"ZORTEA",
   "codigoexterno":950
},
{
   "siglaestado":"SE",
   "nomemunicipio":"AMPARO DE SAO FRANCISCO",
   "codigoexterno":3101
},
{
   "siglaestado":"SE",
   "nomemunicipio":"AQUIDABA",
   "codigoexterno":3103
},
{
   "siglaestado":"SE",
   "nomemunicipio":"ARACAJU",
   "codigoexterno":3105
},
{
   "siglaestado":"SE",
   "nomemunicipio":"ARAUA",
   "codigoexterno":3107
},
{
   "siglaestado":"SE",
   "nomemunicipio":"AREIA BRANCA",
   "codigoexterno":3109
},
{
   "siglaestado":"SE",
   "nomemunicipio":"BARRA DOS COQUEIROS",
   "codigoexterno":3111
},
{
   "siglaestado":"SE",
   "nomemunicipio":"BOQUIM",
   "codigoexterno":3115
},
{
   "siglaestado":"SE",
   "nomemunicipio":"BREJO GRANDE",
   "codigoexterno":3113
},
{
   "siglaestado":"SE",
   "nomemunicipio":"CAMPO DO BRITO",
   "codigoexterno":3119
},
{
   "siglaestado":"SE",
   "nomemunicipio":"CANHOBA",
   "codigoexterno":3121
},
{
   "siglaestado":"SE",
   "nomemunicipio":"CANINDE DE SAO FRANCISCO",
   "codigoexterno":3123
},
{
   "siglaestado":"SE",
   "nomemunicipio":"CAPELA",
   "codigoexterno":3125
},
{
   "siglaestado":"SE",
   "nomemunicipio":"CARIRA",
   "codigoexterno":3127
},
{
   "siglaestado":"SE",
   "nomemunicipio":"CARMOPOLIS",
   "codigoexterno":3129
},
{
   "siglaestado":"SE",
   "nomemunicipio":"CEDRO DE SAO JOAO",
   "codigoexterno":3131
},
{
   "siglaestado":"SE",
   "nomemunicipio":"CRISTINAPOLIS",
   "codigoexterno":3133
},
{
   "siglaestado":"SE",
   "nomemunicipio":"CUMBE",
   "codigoexterno":3137
},
{
   "siglaestado":"SE",
   "nomemunicipio":"DIVINA PASTORA",
   "codigoexterno":3139
},
{
   "siglaestado":"SE",
   "nomemunicipio":"ESTANCIA",
   "codigoexterno":3141
},
{
   "siglaestado":"SE",
   "nomemunicipio":"FEIRA NOVA",
   "codigoexterno":3143
},
{
   "siglaestado":"SE",
   "nomemunicipio":"FREI PAULO",
   "codigoexterno":3145
},
{
   "siglaestado":"SE",
   "nomemunicipio":"GARARU",
   "codigoexterno":3149
},
{
   "siglaestado":"SE",
   "nomemunicipio":"GENERAL MAYNARD",
   "codigoexterno":3147
},
{
   "siglaestado":"SE",
   "nomemunicipio":"GRACCHO CARDOSO",
   "codigoexterno":3151
},
{
   "siglaestado":"SE",
   "nomemunicipio":"ILHA DAS FLORES",
   "codigoexterno":3153
},
{
   "siglaestado":"SE",
   "nomemunicipio":"INDIAROBA",
   "codigoexterno":3155
},
{
   "siglaestado":"SE",
   "nomemunicipio":"ITABAIANA",
   "codigoexterno":3157
},
{
   "siglaestado":"SE",
   "nomemunicipio":"ITABAIANINHA",
   "codigoexterno":3159
},
{
   "siglaestado":"SE",
   "nomemunicipio":"ITABI",
   "codigoexterno":3161
},
{
   "siglaestado":"SE",
   "nomemunicipio":"ITAPORANGA D\'AJUDA",
   "codigoexterno":3163
},
{
   "siglaestado":"SE",
   "nomemunicipio":"JAPARATUBA",
   "codigoexterno":3165
},
{
   "siglaestado":"SE",
   "nomemunicipio":"JAPOATA",
   "codigoexterno":3167
},
{
   "siglaestado":"SE",
   "nomemunicipio":"LAGARTO",
   "codigoexterno":3169
},
{
   "siglaestado":"SE",
   "nomemunicipio":"LARANJEIRAS",
   "codigoexterno":3171
},
{
   "siglaestado":"SE",
   "nomemunicipio":"MACAMBIRA",
   "codigoexterno":3173
},
{
   "siglaestado":"SE",
   "nomemunicipio":"MALHADA DOS BOIS",
   "codigoexterno":3175
},
{
   "siglaestado":"SE",
   "nomemunicipio":"MALHADOR",
   "codigoexterno":3177
},
{
   "siglaestado":"SE",
   "nomemunicipio":"MARUIM",
   "codigoexterno":3179
},
{
   "siglaestado":"SE",
   "nomemunicipio":"MOITA BONITA",
   "codigoexterno":3181
},
{
   "siglaestado":"SE",
   "nomemunicipio":"MONTE ALEGRE DE SERGIPE",
   "codigoexterno":3183
},
{
   "siglaestado":"SE",
   "nomemunicipio":"MURIBECA",
   "codigoexterno":3185
},
{
   "siglaestado":"SE",
   "nomemunicipio":"NEOPOLIS",
   "codigoexterno":3187
},
{
   "siglaestado":"SE",
   "nomemunicipio":"NOSSA SENHORA APARECIDA",
   "codigoexterno":3135
},
{
   "siglaestado":"SE",
   "nomemunicipio":"NOSSA SENHORA DA GLORIA",
   "codigoexterno":3189
},
{
   "siglaestado":"SE",
   "nomemunicipio":"NOSSA SENHORA DAS DORES",
   "codigoexterno":3191
},
{
   "siglaestado":"SE",
   "nomemunicipio":"NOSSA SENHORA DE LOURDES",
   "codigoexterno":3193
},
{
   "siglaestado":"SE",
   "nomemunicipio":"NOSSA SENHORA DO SOCORRO",
   "codigoexterno":3195
},
{
   "siglaestado":"SE",
   "nomemunicipio":"PACATUBA",
   "codigoexterno":3197
},
{
   "siglaestado":"SE",
   "nomemunicipio":"PEDRA MOLE",
   "codigoexterno":3199
},
{
   "siglaestado":"SE",
   "nomemunicipio":"PEDRINHAS",
   "codigoexterno":3201
},
{
   "siglaestado":"SE",
   "nomemunicipio":"PINHAO",
   "codigoexterno":3203
},
{
   "siglaestado":"SE",
   "nomemunicipio":"PIRAMBU",
   "codigoexterno":3205
},
{
   "siglaestado":"SE",
   "nomemunicipio":"POCO REDONDO",
   "codigoexterno":3207
},
{
   "siglaestado":"SE",
   "nomemunicipio":"POCO VERDE",
   "codigoexterno":3209
},
{
   "siglaestado":"SE",
   "nomemunicipio":"PORTO DA FOLHA",
   "codigoexterno":3211
},
{
   "siglaestado":"SE",
   "nomemunicipio":"PROPRIA",
   "codigoexterno":3213
},
{
   "siglaestado":"SE",
   "nomemunicipio":"RIACHAO DO DANTAS",
   "codigoexterno":3215
},
{
   "siglaestado":"SE",
   "nomemunicipio":"RIACHUELO",
   "codigoexterno":3217
},
{
   "siglaestado":"SE",
   "nomemunicipio":"RIBEIROPOLIS",
   "codigoexterno":3219
},
{
   "siglaestado":"SE",
   "nomemunicipio":"ROSARIO DO CATETE",
   "codigoexterno":3221
},
{
   "siglaestado":"SE",
   "nomemunicipio":"SALGADO",
   "codigoexterno":3223
},
{
   "siglaestado":"SE",
   "nomemunicipio":"SANTA LUZIA DO ITANHY",
   "codigoexterno":3225
},
{
   "siglaestado":"SE",
   "nomemunicipio":"SANTA ROSA DE LIMA",
   "codigoexterno":3229
},
{
   "siglaestado":"SE",
   "nomemunicipio":"SANTANA DO SAO FRANCISCO",
   "codigoexterno":2647
},
{
   "siglaestado":"SE",
   "nomemunicipio":"SANTO AMARO DAS BROTAS",
   "codigoexterno":3231
},
{
   "siglaestado":"SE",
   "nomemunicipio":"SAO CRISTOVAO",
   "codigoexterno":3233
},
{
   "siglaestado":"SE",
   "nomemunicipio":"SAO DOMINGOS",
   "codigoexterno":3235
},
{
   "siglaestado":"SE",
   "nomemunicipio":"SAO FRANCISCO",
   "codigoexterno":3237
},
{
   "siglaestado":"SE",
   "nomemunicipio":"SAO MIGUEL DO ALEIXO",
   "codigoexterno":3239
},
{
   "siglaestado":"SE",
   "nomemunicipio":"SIMAO DIAS",
   "codigoexterno":3241
},
{
   "siglaestado":"SE",
   "nomemunicipio":"SIRIRI",
   "codigoexterno":3243
},
{
   "siglaestado":"SE",
   "nomemunicipio":"TELHA",
   "codigoexterno":3245
},
{
   "siglaestado":"SE",
   "nomemunicipio":"TOBIAS BARRETO",
   "codigoexterno":3247
},
{
   "siglaestado":"SE",
   "nomemunicipio":"TOMAR DO GERU",
   "codigoexterno":3249
},
{
   "siglaestado":"SE",
   "nomemunicipio":"UMBAUBA",
   "codigoexterno":3251
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ADAMANTINA",
   "codigoexterno":6101
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ADOLFO",
   "codigoexterno":6103
},
{
   "siglaestado":"SP",
   "nomemunicipio":"AGUAI",
   "codigoexterno":6105
},
{
   "siglaestado":"SP",
   "nomemunicipio":"AGUAS DA PRATA",
   "codigoexterno":6107
},
{
   "siglaestado":"SP",
   "nomemunicipio":"AGUAS DE LINDOIA",
   "codigoexterno":6109
},
{
   "siglaestado":"SP",
   "nomemunicipio":"AGUAS DE SANTA BARBARA",
   "codigoexterno":7019
},
{
   "siglaestado":"SP",
   "nomemunicipio":"AGUAS DE SAO PEDRO",
   "codigoexterno":6111
},
{
   "siglaestado":"SP",
   "nomemunicipio":"AGUDOS",
   "codigoexterno":6113
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ALAMBARI",
   "codigoexterno":2995
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ALFREDO MARCONDES",
   "codigoexterno":6115
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ALTAIR",
   "codigoexterno":6117
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ALTINOPOLIS",
   "codigoexterno":6119
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ALTO ALEGRE",
   "codigoexterno":6121
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ALUMINIO",
   "codigoexterno":3065
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ALVARES FLORENCE",
   "codigoexterno":6123
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ALVARES MACHADO",
   "codigoexterno":6125
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ALVARO DE CARVALHO",
   "codigoexterno":6127
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ALVINLANDIA",
   "codigoexterno":6129
},
{
   "siglaestado":"SP",
   "nomemunicipio":"AMERICANA",
   "codigoexterno":6131
},
{
   "siglaestado":"SP",
   "nomemunicipio":"AMERICO BRASILIENSE",
   "codigoexterno":6133
},
{
   "siglaestado":"SP",
   "nomemunicipio":"AMERICO DE CAMPOS",
   "codigoexterno":6135
},
{
   "siglaestado":"SP",
   "nomemunicipio":"AMPARO",
   "codigoexterno":6137
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ANALANDIA",
   "codigoexterno":6139
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ANDRADINA",
   "codigoexterno":6141
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ANGATUBA",
   "codigoexterno":6143
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ANHEMBI",
   "codigoexterno":6145
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ANHUMAS",
   "codigoexterno":6147
},
{
   "siglaestado":"SP",
   "nomemunicipio":"APARECIDA",
   "codigoexterno":6149
},
{
   "siglaestado":"SP",
   "nomemunicipio":"APARECIDA D\'OESTE",
   "codigoexterno":6151
},
{
   "siglaestado":"SP",
   "nomemunicipio":"APIAI",
   "codigoexterno":6153
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ARACARIGUAMA",
   "codigoexterno":3067
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ARACATUBA",
   "codigoexterno":6155
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ARACOIABA DA SERRA",
   "codigoexterno":6157
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ARAMINA",
   "codigoexterno":6159
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ARANDU",
   "codigoexterno":6161
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ARAPEI",
   "codigoexterno":2991
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ARARAQUARA",
   "codigoexterno":6163
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ARARAS",
   "codigoexterno":6165
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ARCO-IRIS",
   "codigoexterno":790
},
{
   "siglaestado":"SP",
   "nomemunicipio":"AREALVA",
   "codigoexterno":6167
},
{
   "siglaestado":"SP",
   "nomemunicipio":"AREIAS",
   "codigoexterno":6169
},
{
   "siglaestado":"SP",
   "nomemunicipio":"AREIOPOLIS",
   "codigoexterno":6171
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ARIRANHA",
   "codigoexterno":6173
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ARTUR NOGUEIRA",
   "codigoexterno":6175
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ARUJA",
   "codigoexterno":6177
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ASPASIA",
   "codigoexterno":2981
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ASSIS",
   "codigoexterno":6179
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ATIBAIA",
   "codigoexterno":6181
},
{
   "siglaestado":"SP",
   "nomemunicipio":"AURIFLAMA",
   "codigoexterno":6183
},
{
   "siglaestado":"SP",
   "nomemunicipio":"AVAI",
   "codigoexterno":6185
},
{
   "siglaestado":"SP",
   "nomemunicipio":"AVANHANDAVA",
   "codigoexterno":6187
},
{
   "siglaestado":"SP",
   "nomemunicipio":"AVARE",
   "codigoexterno":6189
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BADY BASSITT",
   "codigoexterno":6191
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BALBINOS",
   "codigoexterno":6193
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BALSAMO",
   "codigoexterno":6195
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BANANAL",
   "codigoexterno":6197
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BARAO DE ANTONINA",
   "codigoexterno":6201
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BARBOSA",
   "codigoexterno":6199
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BARIRI",
   "codigoexterno":6203
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BARRA BONITA",
   "codigoexterno":6205
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BARRA DO CHAPEU",
   "codigoexterno":2997
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BARRA DO TURVO",
   "codigoexterno":6207
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BARRETOS",
   "codigoexterno":6209
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BARRINHA",
   "codigoexterno":6211
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BARUERI",
   "codigoexterno":6213
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BASTOS",
   "codigoexterno":6215
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BATATAIS",
   "codigoexterno":6217
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BAURU",
   "codigoexterno":6219
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BEBEDOURO",
   "codigoexterno":6221
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BENTO DE ABREU",
   "codigoexterno":6223
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BERNARDINO DE CAMPOS",
   "codigoexterno":6225
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BERTIOGA",
   "codigoexterno":2965
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BILAC",
   "codigoexterno":6227
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BIRIGUI",
   "codigoexterno":6229
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BIRITIBA-MIRIM",
   "codigoexterno":6231
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BOA ESPERANCA DO SUL",
   "codigoexterno":6233
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BOCAINA",
   "codigoexterno":6235
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BOFETE",
   "codigoexterno":6237
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BOITUVA",
   "codigoexterno":6239
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BOM JESUS DOS PERDOES",
   "codigoexterno":6241
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BOM SUCESSO DE ITARARE",
   "codigoexterno":3059
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BORA",
   "codigoexterno":6243
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BORACEIA",
   "codigoexterno":6245
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BORBOREMA",
   "codigoexterno":6247
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BOREBI",
   "codigoexterno":7247
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BOTUCATU",
   "codigoexterno":6249
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BRAGANCA PAULISTA",
   "codigoexterno":6251
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BRAUNA",
   "codigoexterno":6255
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BREJO ALEGRE",
   "codigoexterno":792
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BRODOSQUI",
   "codigoexterno":6257
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BROTAS",
   "codigoexterno":6259
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BURI",
   "codigoexterno":6261
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BURITAMA",
   "codigoexterno":6263
},
{
   "siglaestado":"SP",
   "nomemunicipio":"BURITIZAL",
   "codigoexterno":6265
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CABRALIA PAULISTA",
   "codigoexterno":6267
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CABREUVA",
   "codigoexterno":6269
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CACAPAVA",
   "codigoexterno":6271
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CACHOEIRA PAULISTA",
   "codigoexterno":6273
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CACONDE",
   "codigoexterno":6275
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CAFELANDIA",
   "codigoexterno":6277
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CAIABU",
   "codigoexterno":6279
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CAIEIRAS",
   "codigoexterno":6281
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CAIUA",
   "codigoexterno":6283
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CAJAMAR",
   "codigoexterno":6285
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CAJATI",
   "codigoexterno":2967
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CAJOBI",
   "codigoexterno":6287
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CAJURU",
   "codigoexterno":6289
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CAMPINA DO MONTE ALEGRE",
   "codigoexterno":2999
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CAMPINAS",
   "codigoexterno":6291
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CAMPO LIMPO PAULISTA",
   "codigoexterno":6293
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CAMPOS DO JORDAO",
   "codigoexterno":6295
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CAMPOS NOVOS PAULISTA",
   "codigoexterno":6297
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CANANEIA",
   "codigoexterno":6299
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CANAS",
   "codigoexterno":794
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CANDIDO MOTA",
   "codigoexterno":6301
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CANDIDO RODRIGUES",
   "codigoexterno":6303
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CANITAR",
   "codigoexterno":2947
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CAPAO BONITO",
   "codigoexterno":6305
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CAPELA DO ALTO",
   "codigoexterno":6307
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CAPIVARI",
   "codigoexterno":6309
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CARAGUATATUBA",
   "codigoexterno":6311
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CARAPICUIBA",
   "codigoexterno":6313
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CARDOSO",
   "codigoexterno":6315
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CASA BRANCA",
   "codigoexterno":6317
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CASSIA DOS COQUEIROS",
   "codigoexterno":6319
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CASTILHO",
   "codigoexterno":6321
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CATANDUVA",
   "codigoexterno":6323
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CATIGUA",
   "codigoexterno":6325
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CEDRAL",
   "codigoexterno":6327
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CERQUEIRA CESAR",
   "codigoexterno":6329
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CERQUILHO",
   "codigoexterno":6331
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CESARIO LANGE",
   "codigoexterno":6333
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CHARQUEADA",
   "codigoexterno":6335
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CHAVANTES",
   "codigoexterno":6337
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CLEMENTINA",
   "codigoexterno":6339
},
{
   "siglaestado":"SP",
   "nomemunicipio":"COLINA",
   "codigoexterno":6341
},
{
   "siglaestado":"SP",
   "nomemunicipio":"COLOMBIA",
   "codigoexterno":6343
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CONCHAL",
   "codigoexterno":6345
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CONCHAS",
   "codigoexterno":6347
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CORDEIROPOLIS",
   "codigoexterno":6349
},
{
   "siglaestado":"SP",
   "nomemunicipio":"COROADOS",
   "codigoexterno":6351
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CORONEL MACEDO",
   "codigoexterno":6353
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CORUMBATAI",
   "codigoexterno":6355
},
{
   "siglaestado":"SP",
   "nomemunicipio":"COSMOPOLIS",
   "codigoexterno":6357
},
{
   "siglaestado":"SP",
   "nomemunicipio":"COSMORAMA",
   "codigoexterno":6359
},
{
   "siglaestado":"SP",
   "nomemunicipio":"COTIA",
   "codigoexterno":6361
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CRAVINHOS",
   "codigoexterno":6363
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CRISTAIS PAULISTA",
   "codigoexterno":6365
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CRUZALIA",
   "codigoexterno":6367
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CRUZEIRO",
   "codigoexterno":6369
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CUBATAO",
   "codigoexterno":6371
},
{
   "siglaestado":"SP",
   "nomemunicipio":"CUNHA",
   "codigoexterno":6373
},
{
   "siglaestado":"SP",
   "nomemunicipio":"DESCALVADO",
   "codigoexterno":6375
},
{
   "siglaestado":"SP",
   "nomemunicipio":"DIADEMA",
   "codigoexterno":6377
},
{
   "siglaestado":"SP",
   "nomemunicipio":"DIRCE REIS",
   "codigoexterno":7249
},
{
   "siglaestado":"SP",
   "nomemunicipio":"DIVINOLANDIA",
   "codigoexterno":6379
},
{
   "siglaestado":"SP",
   "nomemunicipio":"DOBRADA",
   "codigoexterno":6381
},
{
   "siglaestado":"SP",
   "nomemunicipio":"DOIS CORREGOS",
   "codigoexterno":6383
},
{
   "siglaestado":"SP",
   "nomemunicipio":"DOLCINOPOLIS",
   "codigoexterno":6385
},
{
   "siglaestado":"SP",
   "nomemunicipio":"DOURADO",
   "codigoexterno":6387
},
{
   "siglaestado":"SP",
   "nomemunicipio":"DRACENA",
   "codigoexterno":6389
},
{
   "siglaestado":"SP",
   "nomemunicipio":"DUARTINA",
   "codigoexterno":6391
},
{
   "siglaestado":"SP",
   "nomemunicipio":"DUMONT",
   "codigoexterno":6393
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ECHAPORA",
   "codigoexterno":6395
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ELDORADO",
   "codigoexterno":6397
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ELIAS FAUSTO",
   "codigoexterno":6399
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ELISIARIO",
   "codigoexterno":2975
},
{
   "siglaestado":"SP",
   "nomemunicipio":"EMBAUBA",
   "codigoexterno":7251
},
{
   "siglaestado":"SP",
   "nomemunicipio":"EMBU",
   "codigoexterno":6401
},
{
   "siglaestado":"SP",
   "nomemunicipio":"EMBU-GUACU",
   "codigoexterno":6403
},
{
   "siglaestado":"SP",
   "nomemunicipio":"EMILIANOPOLIS",
   "codigoexterno":2961
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ENGENHEIRO COELHO",
   "codigoexterno":2949
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ESPIRITO SANTO DO PINHAL",
   "codigoexterno":6865
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ESPIRITO SANTO DO TURVO",
   "codigoexterno":7253
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ESTIVA GERBI",
   "codigoexterno":2959
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ESTRELA DO NORTE",
   "codigoexterno":6407
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ESTRELA D\'OESTE",
   "codigoexterno":6405
},
{
   "siglaestado":"SP",
   "nomemunicipio":"EUCLIDES DA CUNHA PAULISTA",
   "codigoexterno":7255
},
{
   "siglaestado":"SP",
   "nomemunicipio":"FARTURA",
   "codigoexterno":6409
},
{
   "siglaestado":"SP",
   "nomemunicipio":"FERNANDO PRESTES",
   "codigoexterno":6413
},
{
   "siglaestado":"SP",
   "nomemunicipio":"FERNANDOPOLIS",
   "codigoexterno":6411
},
{
   "siglaestado":"SP",
   "nomemunicipio":"FERNAO",
   "codigoexterno":796
},
{
   "siglaestado":"SP",
   "nomemunicipio":"FERRAZ DE VASCONCELOS",
   "codigoexterno":6415
},
{
   "siglaestado":"SP",
   "nomemunicipio":"FLORA RICA",
   "codigoexterno":6417
},
{
   "siglaestado":"SP",
   "nomemunicipio":"FLOREAL",
   "codigoexterno":6419
},
{
   "siglaestado":"SP",
   "nomemunicipio":"FLORIDA PAULISTA",
   "codigoexterno":6421
},
{
   "siglaestado":"SP",
   "nomemunicipio":"FLORINEA",
   "codigoexterno":6423
},
{
   "siglaestado":"SP",
   "nomemunicipio":"FRANCA",
   "codigoexterno":6425
},
{
   "siglaestado":"SP",
   "nomemunicipio":"FRANCISCO MORATO",
   "codigoexterno":6427
},
{
   "siglaestado":"SP",
   "nomemunicipio":"FRANCO DA ROCHA",
   "codigoexterno":6429
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GABRIEL MONTEIRO",
   "codigoexterno":6431
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GALIA",
   "codigoexterno":6433
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GARCA",
   "codigoexterno":6435
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GASTAO VIDIGAL",
   "codigoexterno":6437
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GAVIAO PEIXOTO",
   "codigoexterno":798
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GENERAL SALGADO",
   "codigoexterno":6439
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GETULINA",
   "codigoexterno":6441
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GLICERIO",
   "codigoexterno":6443
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GUAICARA",
   "codigoexterno":6445
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GUAIMBE",
   "codigoexterno":6447
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GUAIRA",
   "codigoexterno":6449
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GUAPIACU",
   "codigoexterno":6451
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GUAPIARA",
   "codigoexterno":6453
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GUARA",
   "codigoexterno":6455
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GUARACAI",
   "codigoexterno":6457
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GUARACI",
   "codigoexterno":6459
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GUARANI D\'OESTE",
   "codigoexterno":6461
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GUARANTA",
   "codigoexterno":6463
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GUARARAPES",
   "codigoexterno":6465
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GUARAREMA",
   "codigoexterno":6467
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GUARATINGUETA",
   "codigoexterno":6469
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GUAREI",
   "codigoexterno":6471
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GUARIBA",
   "codigoexterno":6473
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GUARUJA",
   "codigoexterno":6475
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GUARULHOS",
   "codigoexterno":6477
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GUATAPARA",
   "codigoexterno":7257
},
{
   "siglaestado":"SP",
   "nomemunicipio":"GUZOLANDIA",
   "codigoexterno":6479
},
{
   "siglaestado":"SP",
   "nomemunicipio":"HERCULANDIA",
   "codigoexterno":6481
},
{
   "siglaestado":"SP",
   "nomemunicipio":"HOLAMBRA",
   "codigoexterno":2953
},
{
   "siglaestado":"SP",
   "nomemunicipio":"HORTOLANDIA",
   "codigoexterno":2951
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IACANGA",
   "codigoexterno":6483
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IACRI",
   "codigoexterno":6485
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IARAS",
   "codigoexterno":7259
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IBATE",
   "codigoexterno":6487
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IBIRA",
   "codigoexterno":6489
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IBIRAREMA",
   "codigoexterno":6491
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IBITINGA",
   "codigoexterno":6493
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IBIUNA",
   "codigoexterno":6495
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ICEM",
   "codigoexterno":6497
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IEPE",
   "codigoexterno":6499
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IGARACU DO TIETE",
   "codigoexterno":6501
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IGARAPAVA",
   "codigoexterno":6503
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IGARATA",
   "codigoexterno":6505
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IGUAPE",
   "codigoexterno":6507
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ILHA COMPRIDA",
   "codigoexterno":2969
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ILHA SOLTEIRA",
   "codigoexterno":2943
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ILHABELA",
   "codigoexterno":6509
},
{
   "siglaestado":"SP",
   "nomemunicipio":"INDAIATUBA",
   "codigoexterno":6511
},
{
   "siglaestado":"SP",
   "nomemunicipio":"INDIANA",
   "codigoexterno":6513
},
{
   "siglaestado":"SP",
   "nomemunicipio":"INDIAPORA",
   "codigoexterno":6515
},
{
   "siglaestado":"SP",
   "nomemunicipio":"INUBIA PAULISTA",
   "codigoexterno":6517
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IPAUSSU",
   "codigoexterno":6519
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IPERO",
   "codigoexterno":6521
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IPEUNA",
   "codigoexterno":6523
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IPIGUA",
   "codigoexterno":800
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IPORANGA",
   "codigoexterno":6525
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IPUA",
   "codigoexterno":6527
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IRACEMAPOLIS",
   "codigoexterno":6529
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IRAPUA",
   "codigoexterno":6531
},
{
   "siglaestado":"SP",
   "nomemunicipio":"IRAPURU",
   "codigoexterno":6533
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITABERA",
   "codigoexterno":6535
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITAI",
   "codigoexterno":6537
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITAJOBI",
   "codigoexterno":6539
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITAJU",
   "codigoexterno":6541
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITANHAEM",
   "codigoexterno":6543
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITAOCA",
   "codigoexterno":3053
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITAPECERICA DA SERRA",
   "codigoexterno":6545
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITAPETININGA",
   "codigoexterno":6547
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITAPEVA",
   "codigoexterno":6549
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITAPEVI",
   "codigoexterno":6551
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITAPIRA",
   "codigoexterno":6553
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITAPIRAPUA PAULISTA",
   "codigoexterno":3055
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITAPOLIS",
   "codigoexterno":6555
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITAPORANGA",
   "codigoexterno":6557
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITAPUI",
   "codigoexterno":6559
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITAPURA",
   "codigoexterno":6561
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITAQUAQUECETUBA",
   "codigoexterno":6563
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITARARE",
   "codigoexterno":6565
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITARIRI",
   "codigoexterno":6567
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITATIBA",
   "codigoexterno":6569
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITATINGA",
   "codigoexterno":6571
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITIRAPINA",
   "codigoexterno":6573
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITIRAPUA",
   "codigoexterno":6575
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITOBI",
   "codigoexterno":6577
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITU",
   "codigoexterno":6579
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITUPEVA",
   "codigoexterno":6581
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ITUVERAVA",
   "codigoexterno":6583
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JABORANDI",
   "codigoexterno":6585
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JABOTICABAL",
   "codigoexterno":6587
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JACAREI",
   "codigoexterno":6589
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JACI",
   "codigoexterno":6591
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JACUPIRANGA",
   "codigoexterno":6593
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JAGUARIUNA",
   "codigoexterno":6595
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JALES",
   "codigoexterno":6597
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JAMBEIRO",
   "codigoexterno":6599
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JANDIRA",
   "codigoexterno":6601
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JARDINOPOLIS",
   "codigoexterno":6603
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JARINU",
   "codigoexterno":6605
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JAU",
   "codigoexterno":6607
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JERIQUARA",
   "codigoexterno":6609
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JOANOPOLIS",
   "codigoexterno":6611
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JOAO RAMALHO",
   "codigoexterno":6613
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JOSE BONIFACIO",
   "codigoexterno":6615
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JULIO MESQUITA",
   "codigoexterno":6617
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JUMIRIM",
   "codigoexterno":802
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JUNDIAI",
   "codigoexterno":6619
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JUNQUEIROPOLIS",
   "codigoexterno":6621
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JUQUIA",
   "codigoexterno":6623
},
{
   "siglaestado":"SP",
   "nomemunicipio":"JUQUITIBA",
   "codigoexterno":6625
},
{
   "siglaestado":"SP",
   "nomemunicipio":"LAGOINHA",
   "codigoexterno":6627
},
{
   "siglaestado":"SP",
   "nomemunicipio":"LARANJAL PAULISTA",
   "codigoexterno":6629
},
{
   "siglaestado":"SP",
   "nomemunicipio":"LAVINIA",
   "codigoexterno":6631
},
{
   "siglaestado":"SP",
   "nomemunicipio":"LAVRINHAS",
   "codigoexterno":6633
},
{
   "siglaestado":"SP",
   "nomemunicipio":"LEME",
   "codigoexterno":6635
},
{
   "siglaestado":"SP",
   "nomemunicipio":"LENCOIS PAULISTA",
   "codigoexterno":6637
},
{
   "siglaestado":"SP",
   "nomemunicipio":"LIMEIRA",
   "codigoexterno":6639
},
{
   "siglaestado":"SP",
   "nomemunicipio":"LINDOIA",
   "codigoexterno":6641
},
{
   "siglaestado":"SP",
   "nomemunicipio":"LINS",
   "codigoexterno":6643
},
{
   "siglaestado":"SP",
   "nomemunicipio":"LORENA",
   "codigoexterno":6645
},
{
   "siglaestado":"SP",
   "nomemunicipio":"LOURDES",
   "codigoexterno":2937
},
{
   "siglaestado":"SP",
   "nomemunicipio":"LOUVEIRA",
   "codigoexterno":6647
},
{
   "siglaestado":"SP",
   "nomemunicipio":"LUCELIA",
   "codigoexterno":6649
},
{
   "siglaestado":"SP",
   "nomemunicipio":"LUCIANOPOLIS",
   "codigoexterno":6651
},
{
   "siglaestado":"SP",
   "nomemunicipio":"LUIS ANTONIO",
   "codigoexterno":6653
},
{
   "siglaestado":"SP",
   "nomemunicipio":"LUIZIANIA",
   "codigoexterno":6655
},
{
   "siglaestado":"SP",
   "nomemunicipio":"LUPERCIO",
   "codigoexterno":6657
},
{
   "siglaestado":"SP",
   "nomemunicipio":"LUTECIA",
   "codigoexterno":6659
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MACATUBA",
   "codigoexterno":6661
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MACAUBAL",
   "codigoexterno":6663
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MACEDONIA",
   "codigoexterno":6665
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MAGDA",
   "codigoexterno":6667
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MAIRINQUE",
   "codigoexterno":6669
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MAIRIPORA",
   "codigoexterno":6671
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MANDURI",
   "codigoexterno":6673
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MARABA PAULISTA",
   "codigoexterno":6675
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MARACAI",
   "codigoexterno":6677
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MARAPOAMA",
   "codigoexterno":2977
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MARIAPOLIS",
   "codigoexterno":6679
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MARILIA",
   "codigoexterno":6681
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MARINOPOLIS",
   "codigoexterno":6683
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MARTINOPOLIS",
   "codigoexterno":6685
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MATAO",
   "codigoexterno":6687
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MAUA",
   "codigoexterno":6689
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MENDONCA",
   "codigoexterno":6691
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MERIDIANO",
   "codigoexterno":6693
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MESOPOLIS",
   "codigoexterno":2983
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MIGUELOPOLIS",
   "codigoexterno":6695
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MINEIROS DO TIETE",
   "codigoexterno":6697
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MIRA ESTRELA",
   "codigoexterno":6701
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MIRACATU",
   "codigoexterno":6699
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MIRANDOPOLIS",
   "codigoexterno":6703
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MIRANTE DO PARANAPANEMA",
   "codigoexterno":6705
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MIRASSOL",
   "codigoexterno":6707
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MIRASSOLANDIA",
   "codigoexterno":6709
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MOCOCA",
   "codigoexterno":6711
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MOGI DAS CRUZES",
   "codigoexterno":6713
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MOGI-GUACU",
   "codigoexterno":6715
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MOGI-MIRIM",
   "codigoexterno":6717
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MOMBUCA",
   "codigoexterno":6719
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MONCOES",
   "codigoexterno":6721
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MONGAGUA",
   "codigoexterno":6723
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MONTE ALEGRE DO SUL",
   "codigoexterno":6725
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MONTE ALTO",
   "codigoexterno":6727
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MONTE APRAZIVEL",
   "codigoexterno":6729
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MONTE AZUL PAULISTA",
   "codigoexterno":6731
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MONTE CASTELO",
   "codigoexterno":6733
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MONTE MOR",
   "codigoexterno":6737
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MONTEIRO LOBATO",
   "codigoexterno":6735
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MORRO AGUDO",
   "codigoexterno":6739
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MORUNGABA",
   "codigoexterno":6741
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MOTUCA",
   "codigoexterno":7263
},
{
   "siglaestado":"SP",
   "nomemunicipio":"MURUTINGA DO SUL",
   "codigoexterno":6743
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NANTES",
   "codigoexterno":804
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NARANDIBA",
   "codigoexterno":6745
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NATIVIDADE DA SERRA",
   "codigoexterno":6747
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NAZARE PAULISTA",
   "codigoexterno":6749
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NEVES PAULISTA",
   "codigoexterno":6751
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NHANDEARA",
   "codigoexterno":6753
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NIPOA",
   "codigoexterno":6755
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NOVA ALIANCA",
   "codigoexterno":6757
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NOVA CAMPINA",
   "codigoexterno":3061
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NOVA CANAA PAULISTA",
   "codigoexterno":2985
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NOVA CASTILHO",
   "codigoexterno":806
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NOVA EUROPA",
   "codigoexterno":6759
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NOVA GRANADA",
   "codigoexterno":6761
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NOVA GUATAPORANGA",
   "codigoexterno":6763
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NOVA INDEPENDENCIA",
   "codigoexterno":6765
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NOVA LUZITANIA",
   "codigoexterno":6767
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NOVA ODESSA",
   "codigoexterno":6769
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NOVAIS",
   "codigoexterno":2979
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NOVO HORIZONTE",
   "codigoexterno":6771
},
{
   "siglaestado":"SP",
   "nomemunicipio":"NUPORANGA",
   "codigoexterno":6773
},
{
   "siglaestado":"SP",
   "nomemunicipio":"OCAUCU",
   "codigoexterno":6775
},
{
   "siglaestado":"SP",
   "nomemunicipio":"OLEO",
   "codigoexterno":6777
},
{
   "siglaestado":"SP",
   "nomemunicipio":"OLIMPIA",
   "codigoexterno":6779
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ONDA VERDE",
   "codigoexterno":6781
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ORIENTE",
   "codigoexterno":6783
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ORINDIUVA",
   "codigoexterno":6785
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ORLANDIA",
   "codigoexterno":6787
},
{
   "siglaestado":"SP",
   "nomemunicipio":"OSASCO",
   "codigoexterno":6789
},
{
   "siglaestado":"SP",
   "nomemunicipio":"OSCAR BRESSANE",
   "codigoexterno":6791
},
{
   "siglaestado":"SP",
   "nomemunicipio":"OSVALDO CRUZ",
   "codigoexterno":6793
},
{
   "siglaestado":"SP",
   "nomemunicipio":"OURINHOS",
   "codigoexterno":6795
},
{
   "siglaestado":"SP",
   "nomemunicipio":"OURO VERDE",
   "codigoexterno":6797
},
{
   "siglaestado":"SP",
   "nomemunicipio":"OUROESTE",
   "codigoexterno":808
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PACAEMBU",
   "codigoexterno":6799
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PALESTINA",
   "codigoexterno":6801
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PALMARES PAULISTA",
   "codigoexterno":6803
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PALMEIRA D\'OESTE",
   "codigoexterno":6805
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PALMITAL",
   "codigoexterno":6807
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PANORAMA",
   "codigoexterno":6809
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PARAGUACU PAULISTA",
   "codigoexterno":6811
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PARAIBUNA",
   "codigoexterno":6813
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PARAISO",
   "codigoexterno":6815
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PARANAPANEMA",
   "codigoexterno":6817
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PARANAPUA",
   "codigoexterno":6819
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PARAPUA",
   "codigoexterno":6821
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PARDINHO",
   "codigoexterno":6823
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PARIQUERA-ACU",
   "codigoexterno":6825
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PARISI",
   "codigoexterno":2989
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PATROCINIO PAULISTA",
   "codigoexterno":6827
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PAULICEIA",
   "codigoexterno":6829
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PAULINIA",
   "codigoexterno":6831
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PAULISTANIA",
   "codigoexterno":810
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PAULO DE FARIA",
   "codigoexterno":6833
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PEDERNEIRAS",
   "codigoexterno":6835
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PEDRA BELA",
   "codigoexterno":6837
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PEDRANOPOLIS",
   "codigoexterno":6839
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PEDREGULHO",
   "codigoexterno":6841
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PEDREIRA",
   "codigoexterno":6843
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PEDRINHAS PAULISTA",
   "codigoexterno":2963
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PEDRO DE TOLEDO",
   "codigoexterno":6845
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PENAPOLIS",
   "codigoexterno":6847
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PEREIRA BARRETO",
   "codigoexterno":6849
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PEREIRAS",
   "codigoexterno":6851
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PERUIBE",
   "codigoexterno":6853
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PIACATU",
   "codigoexterno":6855
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PIEDADE",
   "codigoexterno":6857
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PILAR DO SUL",
   "codigoexterno":6859
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PINDAMONHANGABA",
   "codigoexterno":6861
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PINDORAMA",
   "codigoexterno":6863
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PINHALZINHO",
   "codigoexterno":6867
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PIQUEROBI",
   "codigoexterno":6869
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PIQUETE",
   "codigoexterno":6871
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PIRACAIA",
   "codigoexterno":6873
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PIRACICABA",
   "codigoexterno":6875
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PIRAJU",
   "codigoexterno":6877
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PIRAJUI",
   "codigoexterno":6879
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PIRANGI",
   "codigoexterno":6881
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PIRAPORA DO BOM JESUS",
   "codigoexterno":6883
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PIRAPOZINHO",
   "codigoexterno":6885
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PIRASSUNUNGA",
   "codigoexterno":6887
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PIRATININGA",
   "codigoexterno":6889
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PITANGUEIRAS",
   "codigoexterno":6891
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PLANALTO",
   "codigoexterno":6893
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PLATINA",
   "codigoexterno":6895
},
{
   "siglaestado":"SP",
   "nomemunicipio":"POA",
   "codigoexterno":6897
},
{
   "siglaestado":"SP",
   "nomemunicipio":"POLONI",
   "codigoexterno":6899
},
{
   "siglaestado":"SP",
   "nomemunicipio":"POMPEIA",
   "codigoexterno":6901
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PONGAI",
   "codigoexterno":6903
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PONTAL",
   "codigoexterno":6905
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PONTALINDA",
   "codigoexterno":2987
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PONTES GESTAL",
   "codigoexterno":6907
},
{
   "siglaestado":"SP",
   "nomemunicipio":"POPULINA",
   "codigoexterno":6909
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PORANGABA",
   "codigoexterno":6911
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PORTO FELIZ",
   "codigoexterno":6913
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PORTO FERREIRA",
   "codigoexterno":6915
},
{
   "siglaestado":"SP",
   "nomemunicipio":"POTIM",
   "codigoexterno":2993
},
{
   "siglaestado":"SP",
   "nomemunicipio":"POTIRENDABA",
   "codigoexterno":6917
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PRACINHA",
   "codigoexterno":812
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PRADOPOLIS",
   "codigoexterno":6919
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PRAIA GRANDE",
   "codigoexterno":6921
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PRATANIA",
   "codigoexterno":814
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PRESIDENTE ALVES",
   "codigoexterno":6923
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PRESIDENTE BERNARDES",
   "codigoexterno":6925
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PRESIDENTE EPITACIO",
   "codigoexterno":6927
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PRESIDENTE PRUDENTE",
   "codigoexterno":6929
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PRESIDENTE VENCESLAU",
   "codigoexterno":6931
},
{
   "siglaestado":"SP",
   "nomemunicipio":"PROMISSAO",
   "codigoexterno":6933
},
{
   "siglaestado":"SP",
   "nomemunicipio":"QUADRA",
   "codigoexterno":816
},
{
   "siglaestado":"SP",
   "nomemunicipio":"QUATA",
   "codigoexterno":6935
},
{
   "siglaestado":"SP",
   "nomemunicipio":"QUEIROZ",
   "codigoexterno":6937
},
{
   "siglaestado":"SP",
   "nomemunicipio":"QUELUZ",
   "codigoexterno":6939
},
{
   "siglaestado":"SP",
   "nomemunicipio":"QUINTANA",
   "codigoexterno":6941
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RAFARD",
   "codigoexterno":6943
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RANCHARIA",
   "codigoexterno":6945
},
{
   "siglaestado":"SP",
   "nomemunicipio":"REDENCAO DA SERRA",
   "codigoexterno":6947
},
{
   "siglaestado":"SP",
   "nomemunicipio":"REGENTE FEIJO",
   "codigoexterno":6949
},
{
   "siglaestado":"SP",
   "nomemunicipio":"REGINOPOLIS",
   "codigoexterno":6951
},
{
   "siglaestado":"SP",
   "nomemunicipio":"REGISTRO",
   "codigoexterno":6953
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RESTINGA",
   "codigoexterno":6955
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RIBEIRA",
   "codigoexterno":6957
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RIBEIRAO BONITO",
   "codigoexterno":6959
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RIBEIRAO BRANCO",
   "codigoexterno":6961
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RIBEIRAO CORRENTE",
   "codigoexterno":6963
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RIBEIRAO DO SUL",
   "codigoexterno":6965
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RIBEIRAO DOS INDIOS",
   "codigoexterno":818
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RIBEIRAO GRANDE",
   "codigoexterno":3057
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RIBEIRAO PIRES",
   "codigoexterno":6967
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RIBEIRAO PRETO",
   "codigoexterno":6969
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RIFAINA",
   "codigoexterno":6973
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RINCAO",
   "codigoexterno":6975
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RINOPOLIS",
   "codigoexterno":6977
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RIO CLARO",
   "codigoexterno":6979
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RIO DAS PEDRAS",
   "codigoexterno":6981
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RIO GRANDE DA SERRA",
   "codigoexterno":6983
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RIOLANDIA",
   "codigoexterno":6985
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RIVERSUL",
   "codigoexterno":6971
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ROSANA",
   "codigoexterno":7265
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ROSEIRA",
   "codigoexterno":6987
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RUBIACEA",
   "codigoexterno":6989
},
{
   "siglaestado":"SP",
   "nomemunicipio":"RUBINEIA",
   "codigoexterno":6991
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SABINO",
   "codigoexterno":6993
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAGRES",
   "codigoexterno":6995
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SALES",
   "codigoexterno":6997
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SALES OLIVEIRA",
   "codigoexterno":6999
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SALESOPOLIS",
   "codigoexterno":7001
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SALMOURAO",
   "codigoexterno":7003
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SALTINHO",
   "codigoexterno":5445
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SALTO",
   "codigoexterno":7005
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SALTO DE PIRAPORA",
   "codigoexterno":7007
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SALTO GRANDE",
   "codigoexterno":7009
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANDOVALINA",
   "codigoexterno":7011
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA ADELIA",
   "codigoexterno":7013
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA ALBERTINA",
   "codigoexterno":7015
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA BARBARA D\'OESTE",
   "codigoexterno":7017
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA BRANCA",
   "codigoexterno":7021
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA CLARA D\'OESTE",
   "codigoexterno":7023
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA CRUZ DA CONCEICAO",
   "codigoexterno":7025
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA CRUZ DA ESPERANCA",
   "codigoexterno":820
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA CRUZ DAS PALMEIRAS",
   "codigoexterno":7027
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA CRUZ DO RIO PARDO",
   "codigoexterno":7029
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA ERNESTINA",
   "codigoexterno":7031
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA FE DO SUL",
   "codigoexterno":7033
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA GERTRUDES",
   "codigoexterno":7035
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA ISABEL",
   "codigoexterno":7037
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA LUCIA",
   "codigoexterno":7039
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA MARIA DA SERRA",
   "codigoexterno":7041
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA MERCEDES",
   "codigoexterno":7043
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA RITA DO PASSA QUATRO",
   "codigoexterno":7051
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA RITA D\'OESTE",
   "codigoexterno":7049
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA ROSA DE VITERBO",
   "codigoexterno":7053
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTA SALETE",
   "codigoexterno":822
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTANA DA PONTE PENSA",
   "codigoexterno":7045
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTANA DE PARNAIBA",
   "codigoexterno":7047
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTO ANASTACIO",
   "codigoexterno":7055
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTO ANDRE",
   "codigoexterno":7057
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTO ANTONIO DA ALEGRIA",
   "codigoexterno":7059
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTO ANTONIO DE POSSE",
   "codigoexterno":7061
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTO ANTONIO DO ARACANGUA",
   "codigoexterno":2939
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTO ANTONIO DO JARDIM",
   "codigoexterno":7063
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTO ANTONIO DO PINHAL",
   "codigoexterno":7065
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTO EXPEDITO",
   "codigoexterno":7067
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTOPOLIS DO AGUAPEI",
   "codigoexterno":7069
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SANTOS",
   "codigoexterno":7071
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO BENTO DO SAPUCAI",
   "codigoexterno":7073
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO BERNARDO DO CAMPO",
   "codigoexterno":7075
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO CAETANO DO SUL",
   "codigoexterno":7077
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO CARLOS",
   "codigoexterno":7079
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO FRANCISCO",
   "codigoexterno":7081
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO JOAO DA BOA VISTA",
   "codigoexterno":7083
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO JOAO DAS DUAS PONTES",
   "codigoexterno":7085
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO JOAO DE IRACEMA",
   "codigoexterno":2941
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO JOAO DO PAU D\'ALHO",
   "codigoexterno":7087
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO JOAQUIM DA BARRA",
   "codigoexterno":7089
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO JOSE DA BELA VISTA",
   "codigoexterno":7091
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO JOSE DO BARREIRO",
   "codigoexterno":7093
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO JOSE DO RIO PARDO",
   "codigoexterno":7095
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO JOSE DO RIO PRETO",
   "codigoexterno":7097
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO JOSE DOS CAMPOS",
   "codigoexterno":7099
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO LOURENCO DA SERRA",
   "codigoexterno":5447
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO LUIZ DO PARAITINGA",
   "codigoexterno":7101
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO MANUEL",
   "codigoexterno":7103
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO MIGUEL ARCANJO",
   "codigoexterno":7105
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO PAULO",
   "codigoexterno":7107
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO PEDRO",
   "codigoexterno":7109
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO PEDRO DO TURVO",
   "codigoexterno":7111
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO ROQUE",
   "codigoexterno":7113
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO SEBASTIAO",
   "codigoexterno":7115
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO SEBASTIAO DA GRAMA",
   "codigoexterno":7117
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO SIMAO",
   "codigoexterno":7119
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SAO VICENTE",
   "codigoexterno":7121
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SARAPUI",
   "codigoexterno":7123
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SARUTAIA",
   "codigoexterno":7125
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SEBASTIANOPOLIS DO SUL",
   "codigoexterno":7127
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SERRA AZUL",
   "codigoexterno":7129
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SERRA NEGRA",
   "codigoexterno":7133
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SERRANA",
   "codigoexterno":7131
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SERTAOZINHO",
   "codigoexterno":7135
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SETE BARRAS",
   "codigoexterno":7137
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SEVERINIA",
   "codigoexterno":7139
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SILVEIRAS",
   "codigoexterno":7141
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SOCORRO",
   "codigoexterno":7143
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SOROCABA",
   "codigoexterno":7145
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SUD MENNUCCI",
   "codigoexterno":7147
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SUMARE",
   "codigoexterno":7149
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SUZANAPOLIS",
   "codigoexterno":2945
},
{
   "siglaestado":"SP",
   "nomemunicipio":"SUZANO",
   "codigoexterno":7151
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TABAPUA",
   "codigoexterno":7153
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TABATINGA",
   "codigoexterno":7155
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TABOAO DA SERRA",
   "codigoexterno":7157
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TACIBA",
   "codigoexterno":7159
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TAGUAI",
   "codigoexterno":7161
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TAIACU",
   "codigoexterno":7163
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TAIUVA",
   "codigoexterno":7165
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TAMBAU",
   "codigoexterno":7167
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TANABI",
   "codigoexterno":7169
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TAPIRAI",
   "codigoexterno":7171
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TAPIRATIBA",
   "codigoexterno":7173
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TAQUARAL",
   "codigoexterno":824
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TAQUARITINGA",
   "codigoexterno":7175
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TAQUARITUBA",
   "codigoexterno":7177
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TAQUARIVAI",
   "codigoexterno":3063
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TARABAI",
   "codigoexterno":7179
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TARUMA",
   "codigoexterno":7267
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TATUI",
   "codigoexterno":7181
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TAUBATE",
   "codigoexterno":7183
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TEJUPA",
   "codigoexterno":7185
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TEODORO SAMPAIO",
   "codigoexterno":7187
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TERRA ROXA",
   "codigoexterno":7189
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TIETE",
   "codigoexterno":7191
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TIMBURI",
   "codigoexterno":7193
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TORRE DE PEDRA",
   "codigoexterno":3227
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TORRINHA",
   "codigoexterno":7195
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TRABIJU",
   "codigoexterno":826
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TREMEMBE",
   "codigoexterno":7197
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TRES FRONTEIRAS",
   "codigoexterno":7199
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TUIUTI",
   "codigoexterno":2955
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TUPA",
   "codigoexterno":7201
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TUPI PAULISTA",
   "codigoexterno":7203
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TURIUBA",
   "codigoexterno":7205
},
{
   "siglaestado":"SP",
   "nomemunicipio":"TURMALINA",
   "codigoexterno":7207
},
{
   "siglaestado":"SP",
   "nomemunicipio":"UBARANA",
   "codigoexterno":2971
},
{
   "siglaestado":"SP",
   "nomemunicipio":"UBATUBA",
   "codigoexterno":7209
},
{
   "siglaestado":"SP",
   "nomemunicipio":"UBIRAJARA",
   "codigoexterno":7211
},
{
   "siglaestado":"SP",
   "nomemunicipio":"UCHOA",
   "codigoexterno":7213
},
{
   "siglaestado":"SP",
   "nomemunicipio":"UNIAO PAULISTA",
   "codigoexterno":7215
},
{
   "siglaestado":"SP",
   "nomemunicipio":"URANIA",
   "codigoexterno":7217
},
{
   "siglaestado":"SP",
   "nomemunicipio":"URU",
   "codigoexterno":7219
},
{
   "siglaestado":"SP",
   "nomemunicipio":"URUPES",
   "codigoexterno":7221
},
{
   "siglaestado":"SP",
   "nomemunicipio":"VALENTIM GENTIL",
   "codigoexterno":7223
},
{
   "siglaestado":"SP",
   "nomemunicipio":"VALINHOS",
   "codigoexterno":7225
},
{
   "siglaestado":"SP",
   "nomemunicipio":"VALPARAISO",
   "codigoexterno":7227
},
{
   "siglaestado":"SP",
   "nomemunicipio":"VARGEM",
   "codigoexterno":2957
},
{
   "siglaestado":"SP",
   "nomemunicipio":"VARGEM GRANDE DO SUL",
   "codigoexterno":7231
},
{
   "siglaestado":"SP",
   "nomemunicipio":"VARGEM GRANDE PAULISTA",
   "codigoexterno":7273
},
{
   "siglaestado":"SP",
   "nomemunicipio":"VARZEA PAULISTA",
   "codigoexterno":7233
},
{
   "siglaestado":"SP",
   "nomemunicipio":"VERA CRUZ",
   "codigoexterno":7235
},
{
   "siglaestado":"SP",
   "nomemunicipio":"VINHEDO",
   "codigoexterno":7237
},
{
   "siglaestado":"SP",
   "nomemunicipio":"VIRADOURO",
   "codigoexterno":7239
},
{
   "siglaestado":"SP",
   "nomemunicipio":"VISTA ALEGRE DO ALTO",
   "codigoexterno":7241
},
{
   "siglaestado":"SP",
   "nomemunicipio":"VITORIA BRASIL",
   "codigoexterno":828
},
{
   "siglaestado":"SP",
   "nomemunicipio":"VOTORANTIM",
   "codigoexterno":7243
},
{
   "siglaestado":"SP",
   "nomemunicipio":"VOTUPORANGA",
   "codigoexterno":7245
},
{
   "siglaestado":"SP",
   "nomemunicipio":"ZACARIAS",
   "codigoexterno":2973
},
{
   "siglaestado":"TO",
   "nomemunicipio":"ABREULANDIA",
   "codigoexterno":337
},
{
   "siglaestado":"TO",
   "nomemunicipio":"AGUIARNOPOLIS",
   "codigoexterno":72
},
{
   "siglaestado":"TO",
   "nomemunicipio":"ALIANCA DO TOCANTINS",
   "codigoexterno":9441
},
{
   "siglaestado":"TO",
   "nomemunicipio":"ALMAS",
   "codigoexterno":9207
},
{
   "siglaestado":"TO",
   "nomemunicipio":"ALVORADA",
   "codigoexterno":9213
},
{
   "siglaestado":"TO",
   "nomemunicipio":"ANANAS",
   "codigoexterno":9219
},
{
   "siglaestado":"TO",
   "nomemunicipio":"ANGICO",
   "codigoexterno":165
},
{
   "siglaestado":"TO",
   "nomemunicipio":"APARECIDA DO RIO NEGRO",
   "codigoexterno":9713
},
{
   "siglaestado":"TO",
   "nomemunicipio":"ARAGOMINAS",
   "codigoexterno":167
},
{
   "siglaestado":"TO",
   "nomemunicipio":"ARAGUACEMA",
   "codigoexterno":9237
},
{
   "siglaestado":"TO",
   "nomemunicipio":"ARAGUACU",
   "codigoexterno":9239
},
{
   "siglaestado":"TO",
   "nomemunicipio":"ARAGUAINA",
   "codigoexterno":9241
},
{
   "siglaestado":"TO",
   "nomemunicipio":"ARAGUANA",
   "codigoexterno":169
},
{
   "siglaestado":"TO",
   "nomemunicipio":"ARAGUATINS",
   "codigoexterno":9243
},
{
   "siglaestado":"TO",
   "nomemunicipio":"ARAPOEMA",
   "codigoexterno":9245
},
{
   "siglaestado":"TO",
   "nomemunicipio":"ARRAIAS",
   "codigoexterno":9247
},
{
   "siglaestado":"TO",
   "nomemunicipio":"AUGUSTINOPOLIS",
   "codigoexterno":9685
},
{
   "siglaestado":"TO",
   "nomemunicipio":"AURORA DO TOCANTINS",
   "codigoexterno":9253
},
{
   "siglaestado":"TO",
   "nomemunicipio":"AXIXA DO TOCANTINS",
   "codigoexterno":9257
},
{
   "siglaestado":"TO",
   "nomemunicipio":"BABACULANDIA",
   "codigoexterno":9259
},
{
   "siglaestado":"TO",
   "nomemunicipio":"BANDEIRANTES DO TOCANTINS",
   "codigoexterno":74
},
{
   "siglaestado":"TO",
   "nomemunicipio":"BARRA DO OURO",
   "codigoexterno":76
},
{
   "siglaestado":"TO",
   "nomemunicipio":"BARROLANDIA",
   "codigoexterno":9693
},
{
   "siglaestado":"TO",
   "nomemunicipio":"BERNARDO SAYAO",
   "codigoexterno":9695
},
{
   "siglaestado":"TO",
   "nomemunicipio":"BOM JESUS DO TOCANTINS",
   "codigoexterno":341
},
{
   "siglaestado":"TO",
   "nomemunicipio":"BRASILANDIA DO TOCANTINS",
   "codigoexterno":339
},
{
   "siglaestado":"TO",
   "nomemunicipio":"BREJINHO DE NAZARE",
   "codigoexterno":9273
},
{
   "siglaestado":"TO",
   "nomemunicipio":"BURITI DO TOCANTINS",
   "codigoexterno":9715
},
{
   "siglaestado":"TO",
   "nomemunicipio":"CACHOEIRINHA",
   "codigoexterno":171
},
{
   "siglaestado":"TO",
   "nomemunicipio":"CAMPOS LINDOS",
   "codigoexterno":173
},
{
   "siglaestado":"TO",
   "nomemunicipio":"CARIRI DO TOCANTINS",
   "codigoexterno":327
},
{
   "siglaestado":"TO",
   "nomemunicipio":"CARMOLANDIA",
   "codigoexterno":175
},
{
   "siglaestado":"TO",
   "nomemunicipio":"CARRASCO BONITO",
   "codigoexterno":177
},
{
   "siglaestado":"TO",
   "nomemunicipio":"CASEARA",
   "codigoexterno":9717
},
{
   "siglaestado":"TO",
   "nomemunicipio":"CENTENARIO",
   "codigoexterno":343
},
{
   "siglaestado":"TO",
   "nomemunicipio":"CHAPADA DA NATIVIDADE",
   "codigoexterno":80
},
{
   "siglaestado":"TO",
   "nomemunicipio":"CHAPADA DE AREIA",
   "codigoexterno":78
},
{
   "siglaestado":"TO",
   "nomemunicipio":"COLINAS DO TOCANTINS",
   "codigoexterno":9311
},
{
   "siglaestado":"TO",
   "nomemunicipio":"COLMEIA",
   "codigoexterno":9529
},
{
   "siglaestado":"TO",
   "nomemunicipio":"COMBINADO",
   "codigoexterno":9697
},
{
   "siglaestado":"TO",
   "nomemunicipio":"CONCEICAO DO TOCANTINS",
   "codigoexterno":9313
},
{
   "siglaestado":"TO",
   "nomemunicipio":"COUTO DE MAGALHAES",
   "codigoexterno":9321
},
{
   "siglaestado":"TO",
   "nomemunicipio":"CRISTALANDIA",
   "codigoexterno":9323
},
{
   "siglaestado":"TO",
   "nomemunicipio":"CRIXAS DO TOCANTINS",
   "codigoexterno":82
},
{
   "siglaestado":"TO",
   "nomemunicipio":"DARCINOPOLIS",
   "codigoexterno":179
},
{
   "siglaestado":"TO",
   "nomemunicipio":"DIANOPOLIS",
   "codigoexterno":9341
},
{
   "siglaestado":"TO",
   "nomemunicipio":"DIVINOPOLIS DO TOCANTINS",
   "codigoexterno":9719
},
{
   "siglaestado":"TO",
   "nomemunicipio":"DOIS IRMAOS DO TOCANTINS",
   "codigoexterno":9345
},
{
   "siglaestado":"TO",
   "nomemunicipio":"DUERE",
   "codigoexterno":9347
},
{
   "siglaestado":"TO",
   "nomemunicipio":"ESPERANTINA",
   "codigoexterno":181
},
{
   "siglaestado":"TO",
   "nomemunicipio":"FATIMA",
   "codigoexterno":9683
},
{
   "siglaestado":"TO",
   "nomemunicipio":"FIGUEIROPOLIS",
   "codigoexterno":9667
},
{
   "siglaestado":"TO",
   "nomemunicipio":"FILADELFIA",
   "codigoexterno":9355
},
{
   "siglaestado":"TO",
   "nomemunicipio":"FORMOSO DO ARAGUAIA",
   "codigoexterno":9365
},
{
   "siglaestado":"TO",
   "nomemunicipio":"FORTALEZA DO TABOCAO",
   "codigoexterno":345
},
{
   "siglaestado":"TO",
   "nomemunicipio":"GOIANORTE",
   "codigoexterno":9699
},
{
   "siglaestado":"TO",
   "nomemunicipio":"GOIATINS",
   "codigoexterno":9533
},
{
   "siglaestado":"TO",
   "nomemunicipio":"GUARAI",
   "codigoexterno":9627
},
{
   "siglaestado":"TO",
   "nomemunicipio":"GURUPI",
   "codigoexterno":9385
},
{
   "siglaestado":"TO",
   "nomemunicipio":"IPUEIRAS",
   "codigoexterno":84
},
{
   "siglaestado":"TO",
   "nomemunicipio":"ITACAJA",
   "codigoexterno":9405
},
{
   "siglaestado":"TO",
   "nomemunicipio":"ITAGUATINS",
   "codigoexterno":9409
},
{
   "siglaestado":"TO",
   "nomemunicipio":"ITAPIRATINS",
   "codigoexterno":347
},
{
   "siglaestado":"TO",
   "nomemunicipio":"ITAPORA DO TOCANTINS",
   "codigoexterno":9417
},
{
   "siglaestado":"TO",
   "nomemunicipio":"JAU DO TOCANTINS",
   "codigoexterno":329
},
{
   "siglaestado":"TO",
   "nomemunicipio":"JUARINA",
   "codigoexterno":349
},
{
   "siglaestado":"TO",
   "nomemunicipio":"LAGOA DA CONFUSAO",
   "codigoexterno":367
},
{
   "siglaestado":"TO",
   "nomemunicipio":"LAGOA DO TOCANTINS",
   "codigoexterno":353
},
{
   "siglaestado":"TO",
   "nomemunicipio":"LAJEADO",
   "codigoexterno":351
},
{
   "siglaestado":"TO",
   "nomemunicipio":"LAVANDEIRA",
   "codigoexterno":86
},
{
   "siglaestado":"TO",
   "nomemunicipio":"LIZARDA",
   "codigoexterno":9569
},
{
   "siglaestado":"TO",
   "nomemunicipio":"LUZINOPOLIS",
   "codigoexterno":88
},
{
   "siglaestado":"TO",
   "nomemunicipio":"MARIANOPOLIS DO TOCANTINS",
   "codigoexterno":9711
},
{
   "siglaestado":"TO",
   "nomemunicipio":"MATEIROS",
   "codigoexterno":317
},
{
   "siglaestado":"TO",
   "nomemunicipio":"MAURILANDIA DO TOCANTINS",
   "codigoexterno":183
},
{
   "siglaestado":"TO",
   "nomemunicipio":"MIRACEMA DO TOCANTINS",
   "codigoexterno":9461
},
{
   "siglaestado":"TO",
   "nomemunicipio":"MIRANORTE",
   "codigoexterno":9463
},
{
   "siglaestado":"TO",
   "nomemunicipio":"MONTE DO CARMO",
   "codigoexterno":9469
},
{
   "siglaestado":"TO",
   "nomemunicipio":"MONTE SANTO DO TOCANTINS",
   "codigoexterno":90
},
{
   "siglaestado":"TO",
   "nomemunicipio":"MURICILANDIA",
   "codigoexterno":187
},
{
   "siglaestado":"TO",
   "nomemunicipio":"NATIVIDADE",
   "codigoexterno":9481
},
{
   "siglaestado":"TO",
   "nomemunicipio":"NAZARE",
   "codigoexterno":9483
},
{
   "siglaestado":"TO",
   "nomemunicipio":"NOVA OLINDA",
   "codigoexterno":9663
},
{
   "siglaestado":"TO",
   "nomemunicipio":"NOVA ROSALANDIA",
   "codigoexterno":9721
},
{
   "siglaestado":"TO",
   "nomemunicipio":"NOVO ACORDO",
   "codigoexterno":9499
},
{
   "siglaestado":"TO",
   "nomemunicipio":"NOVO ALEGRE",
   "codigoexterno":9703
},
{
   "siglaestado":"TO",
   "nomemunicipio":"NOVO JARDIM",
   "codigoexterno":321
},
{
   "siglaestado":"TO",
   "nomemunicipio":"OLIVEIRA DE FATIMA",
   "codigoexterno":92
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PALMAS",
   "codigoexterno":9733
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PALMEIRANTE",
   "codigoexterno":189
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PALMEIRAS DO TOCANTINS",
   "codigoexterno":185
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PALMEIROPOLIS",
   "codigoexterno":9649
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PARAISO DO TOCANTINS",
   "codigoexterno":9519
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PARANA",
   "codigoexterno":9521
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PAU D\'ARCO",
   "codigoexterno":191
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PEDRO AFONSO",
   "codigoexterno":9525
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PEIXE",
   "codigoexterno":9527
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PEQUIZEIRO",
   "codigoexterno":9705
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PINDORAMA DO TOCANTINS",
   "codigoexterno":9537
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PIRAQUE",
   "codigoexterno":355
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PIUM",
   "codigoexterno":9547
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PONTE ALTA DO BOM JESUS",
   "codigoexterno":9551
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PONTE ALTA DO TOCANTINS",
   "codigoexterno":9553
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PORTO ALEGRE DO TOCANTINS",
   "codigoexterno":9723
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PORTO NACIONAL",
   "codigoexterno":9559
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PRAIA NORTE",
   "codigoexterno":9725
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PRESIDENTE KENNEDY",
   "codigoexterno":9629
},
{
   "siglaestado":"TO",
   "nomemunicipio":"PUGMIL",
   "codigoexterno":94
},
{
   "siglaestado":"TO",
   "nomemunicipio":"RECURSOLANDIA",
   "codigoexterno":357
},
{
   "siglaestado":"TO",
   "nomemunicipio":"RIACHINHO",
   "codigoexterno":193
},
{
   "siglaestado":"TO",
   "nomemunicipio":"RIO DA CONCEICAO",
   "codigoexterno":323
},
{
   "siglaestado":"TO",
   "nomemunicipio":"RIO DOS BOIS",
   "codigoexterno":359
},
{
   "siglaestado":"TO",
   "nomemunicipio":"RIO SONO",
   "codigoexterno":9679
},
{
   "siglaestado":"TO",
   "nomemunicipio":"SAMPAIO",
   "codigoexterno":9727
},
{
   "siglaestado":"TO",
   "nomemunicipio":"SANDOLANDIA",
   "codigoexterno":331
},
{
   "siglaestado":"TO",
   "nomemunicipio":"SANTA FE DO ARAGUAIA",
   "codigoexterno":195
},
{
   "siglaestado":"TO",
   "nomemunicipio":"SANTA MARIA DO TOCANTINS",
   "codigoexterno":361
},
{
   "siglaestado":"TO",
   "nomemunicipio":"SANTA RITA DO TOCANTINS",
   "codigoexterno":96
},
{
   "siglaestado":"TO",
   "nomemunicipio":"SANTA ROSA DO TOCANTINS",
   "codigoexterno":9729
},
{
   "siglaestado":"TO",
   "nomemunicipio":"SANTA TEREZA DO TOCANTINS",
   "codigoexterno":9731
},
{
   "siglaestado":"TO",
   "nomemunicipio":"SANTA TEREZINHA DO TOCANTINS",
   "codigoexterno":98
},
{
   "siglaestado":"TO",
   "nomemunicipio":"SAO BENTO DO TOCANTINS",
   "codigoexterno":197
},
{
   "siglaestado":"TO",
   "nomemunicipio":"SAO FELIX DO TOCANTINS",
   "codigoexterno":363
},
{
   "siglaestado":"TO",
   "nomemunicipio":"SAO MIGUEL DO TOCANTINS",
   "codigoexterno":199
},
{
   "siglaestado":"TO",
   "nomemunicipio":"SAO SALVADOR DO TOCANTINS",
   "codigoexterno":333
},
{
   "siglaestado":"TO",
   "nomemunicipio":"SAO SEBASTIAO DO TOCANTINS",
   "codigoexterno":9603
},
{
   "siglaestado":"TO",
   "nomemunicipio":"SAO VALERIO DA NATIVIDADE",
   "codigoexterno":9691
},
{
   "siglaestado":"TO",
   "nomemunicipio":"SILVANOPOLIS",
   "codigoexterno":9659
},
{
   "siglaestado":"TO",
   "nomemunicipio":"SITIO NOVO DO TOCANTINS",
   "codigoexterno":9613
},
{
   "siglaestado":"TO",
   "nomemunicipio":"SUCUPIRA",
   "codigoexterno":335
},
{
   "siglaestado":"TO",
   "nomemunicipio":"TAGUATINGA",
   "codigoexterno":9615
},
{
   "siglaestado":"TO",
   "nomemunicipio":"TAIPAS DO TOCANTINS",
   "codigoexterno":325
},
{
   "siglaestado":"TO",
   "nomemunicipio":"TALISMA",
   "codigoexterno":100
},
{
   "siglaestado":"TO",
   "nomemunicipio":"TOCANTINIA",
   "codigoexterno":9619
},
{
   "siglaestado":"TO",
   "nomemunicipio":"TOCANTINOPOLIS",
   "codigoexterno":9621
},
{
   "siglaestado":"TO",
   "nomemunicipio":"TUPIRAMA",
   "codigoexterno":102
},
{
   "siglaestado":"TO",
   "nomemunicipio":"TUPIRATINS",
   "codigoexterno":365
},
{
   "siglaestado":"TO",
   "nomemunicipio":"WANDERLANDIA",
   "codigoexterno":9665
},
{
   "siglaestado":"TO",
   "nomemunicipio":"XAMBIOA",
   "codigoexterno":9643
}
]') 
AS (siglaestado  varchar, nomemunicipio varchar, codigoexterno integer))
select NEXTVAL('cadendermunicipio_db72_sequencial_seq') as codmunic, 
       db71_sequencial,
       dados_municipios.*
  from dados_municipios
  inner join configuracoes.cadenderestado
    on siglaestado = db71_sigla;

INSERT INTO configuracoes.cadendermunicipio (
   db72_sequencial,
   db72_cadenderestado,
   db72_descricao
)    
select codmunic,
       db71_sequencial,
       nomemunicipio
  from dados_inserir;
  
INSERT INTO configuracoes.cadendermunicipiosistema (
  db125_sequencial,
  db125_cadendermunicipio,
  db125_db_sistemaexterno,
  db125_codigosistema
)    
select NEXTVAL('cadendermunicipiosistema_db125_sequencial_seq'), 
       codmunic,
       20,
       codigoexterno
from dados_inserir;

SQL
        );

    }

    public function downCadendermunicipio()
    {
        $iCodigoSistemaExterno = 20;

        $aCadendermunicipiosistema = DB::table("cadendermunicipiosistema")
                                       ->where("db125_db_sistemaexterno", $iCodigoSistemaExterno)
                                       ->get(["db125_cadendermunicipio"])->toArray();

        $aCadendermunicipiosistemaIds = array_map(function ($oCadendermunicipiosistema) {
            return $oCadendermunicipiosistema->db125_cadendermunicipio;
        }, $aCadendermunicipiosistema);

        DB::table("cadendermunicipiosistema")->where("db125_db_sistemaexterno", $iCodigoSistemaExterno)->delete();
        DB::table("db_sistemaexterno")->where("db124_sequencial", $iCodigoSistemaExterno)->delete();
        DB::table("cadendermunicipio")->whereIn("db72_sequencial", $aCadendermunicipiosistemaIds)->delete();
    }
    
}
