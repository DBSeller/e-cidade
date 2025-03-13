<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20712CriandoTabelaCampanhaPublicitaria extends Migration
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();

    }

    private function upDicionario()
    {
        $sql = <<<SQL
            insert into configuracoes.db_sysarquivo values (1010918, 'pccampanhapublicitaria', 'Dados relacionados a campanha publicitaria', 'pc94', '2022-05-12', 'Dados relacionados a campanha publicitaria', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (12,1010918);
            insert into configuracoes.db_sysarquivo values (1010919, 'pctipocampanhapublicitaria', 'tipos de campanhas publicitarias', 'pc95', '2022-05-12', 'tipos de campanhas publicitarias', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (12,1010919);
            insert into configuracoes.db_syscampo values(1014096,'pc95_codigo','int8','Comissao sobre serviços de produção','0', 'codigo do tipo de campanha publicitaria',2,'f','f','f',1,'text','codigo do tipo de campanha publicitaria');
            insert into configuracoes.db_syscampo values(1014098,'pc95_descricao','varchar(255)','descricao do tipo de campanha publicitaria','', 'descricao do tipo de campanha',255,'f','f','f',0,'text','descricao do tipo de campanha');
            insert into configuracoes.db_syscampo values(1014090,'pc94_codigo','int8','Sequencial das campanhas publicitarias','0', 'sequencial das campanhas publicatarias',8,'f','f','f',1,'text','sequencial das campanhas publicatarias');
            insert into configuracoes.db_syscampo values(1014092,'pc94_comissaoproducao','float8','Comissão da agência sobre serviços de produção','0', 'Comissao sobre serviços de produção',8,'t','f','f',4,'text','Comissao sobre serviços de produção');
            insert into configuracoes.db_syscampo values(1014093,'pc94_comissaoveiculacao','float8','Comissão da agência sobre serviços de veiculação','0', 'Comissão sobre serviços de veiculação',8,'t','f','f',4,'text','Comissão sobre serviços de veiculação');
            insert into configuracoes.db_syscampo values(1014094,'pc94_datainicio','date','Data de início da campanha','null', 'Data de início da campanha',10,'t','f','f',1,'text','Data de início da campanha');
            insert into configuracoes.db_syscampo values(1014095,'pc94_datafim','date','Data de fim da campanha','null', 'Data de fim da campanha',10,'t','f','f',1,'text','Data de fim da campanha');
            insert into configuracoes.db_syscampo values(1014100,'pc94_pctipocampanhapublicitaria','int8','codigo do tipo de campanha publicitaria','0', 'codigo do tipo de campanha publicitaria',2,'f','f','f',1,'text','codigo do tipo de campanha publicitaria');
            insert into configuracoes.db_syscampo values(1014101,'pc94_pcmater','int8','Sequencial da pcmater','0', 'Sequencial da pcmater',8,'f','f','f',1,'text','Sequencial da pcmater');
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010919,1014096,1,1014096);
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010918,1014090,1,1014090);
            insert into configuracoes.db_sysforkey values(1010918,1014100,1,1010919,0);
            insert into configuracoes.db_sysforkey values(1010918,1014101,1,855,0);
            insert into configuracoes.db_syssequencia values(1001062, 'pccampanhapublicitaria_pc94_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
            insert into configuracoes.db_syscampo values(1014132,'pc94_cgm','int8','Cgm da agência contratada','0', 'Cgm da agência contratada',8,'t','f','f',1,'text','Cgm da agência contratada');
            insert into configuracoes.db_syscampo values(1014133,'pc94_valorcampanha','float8','Valor total da campanhaa','0', 'Valor total da campanhaa',8,'f','f','f',4,'text','Valor total da campanhaa');
            insert into configuracoes.db_sysforkey values(1010918,1014132,1,42,0);

            update configuracoes.db_sysarqcamp set codsequencia = 1001062 where codarq = 1010918 and codcam = 1014090;

            insert into configuracoes.db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228665 ,'Incluir/Alterar campanha publicitária' ,'Incluir/Alterar campanha publicitária' ,'pat_campanha_publicitaria.php' ,'1' ,'1' ,'Menu para incluir ou alterar campanha publicitária vinculada ao item' ,'false');
            insert into configuracoes.db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3566 ,228665 ,4 ,28 );

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstrutura()
    {
        $sql = <<<SQL
            CREATE TABLE compras.pctipocampanhapublicitaria(
                pc95_codigo int4 PRIMARY KEY,
                pc95_descricao varchar(255)
            );

            INSERT INTO compras.pctipocampanhapublicitaria VALUES(0,'Campanhas Institucionais Gerais');
            INSERT INTO compras.pctipocampanhapublicitaria VALUES(1,'Festas Municipais');
            INSERT INTO compras.pctipocampanhapublicitaria VALUES(2,'Festas Regionais');
            INSERT INTO compras.pctipocampanhapublicitaria VALUES(3,'Festas Nacionais');
            INSERT INTO compras.pctipocampanhapublicitaria VALUES(4,'Campanhas Educativas da Saúde');
            INSERT INTO compras.pctipocampanhapublicitaria VALUES(5,'Campanhas Educativas de Sociais');
            INSERT INTO compras.pctipocampanhapublicitaria VALUES(6,'Campanhas Educativas Outras');
            INSERT INTO compras.pctipocampanhapublicitaria VALUES(7,'Outras Campanhas');

            CREATE TABLE compras.pccampanhapublicitaria(
                pc94_codigo int4 PRIMARY KEY,
                pc94_cgm int4,
                pc94_valorcampanha float,
                pc94_datainicio date,
                pc94_datafim date,
                pc94_comissaoproducao float,
                pc94_comissaoveiculacao float,
                pc94_pctipocampanhapublicitaria int4,
                pc94_pcmater int4,
                CONSTRAINT fk_pctipocampanhapublicitaria
                    FOREIGN KEY (pc94_pctipocampanhapublicitaria)
                        REFERENCES compras.pctipocampanhapublicitaria(pc95_codigo),
                CONSTRAINT fk_pcmater
                    FOREIGN KEY (pc94_pcmater)
                        REFERENCES compras.pcmater(pc01_codmater),
                CONSTRAINT fk_cgm
                    FOREIGN KEY (pc94_cgm)
                        REFERENCES protocolo.cgm(z01_numcgm)
            );
            CREATE SEQUENCE compras.pccampanhapublicitaria_pc94_codigo_seq start 1;

SQL;
        DB::connection()->getPdo()->exec($sql);

    }

    private function downDicionario()
    {
        $sql = <<<SQL


                delete from configuracoes.db_syssequencia where codsequencia = 1001062;
                delete from configuracoes.db_sysforkey where codcam = 1014132 and referen = 42;
                delete from configuracoes.db_sysforkey where codcam = 1014101 and referen = 855;
                delete from configuracoes.db_sysforkey where codcam = 1014100 and referen = 1010919;
                delete from configuracoes.db_sysprikey where codcam = 1014090;
                delete from configuracoes.db_sysprikey where codcam = 1014096;
                delete from configuracoes.db_syscampo where codcam = 1014132;
                delete from configuracoes.db_syscampo where codcam = 1014133;
                delete from configuracoes.db_syscampo where codcam = 1014101;
                delete from configuracoes.db_syscampo where codcam = 1014100;
                delete from configuracoes.db_syscampo where codcam = 1014095;
                delete from configuracoes.db_syscampo where codcam = 1014094;
                delete from configuracoes.db_syscampo where codcam = 1014093;
                delete from configuracoes.db_syscampo where codcam = 1014092;
                delete from configuracoes.db_syscampo where codcam = 1014090;
                delete from configuracoes.db_syscampo where codcam = 1014098;
                delete from configuracoes.db_syscampo where codcam = 1014096;

                delete from configuracoes.db_sysarqmod where codarq = 1010919;
                delete from configuracoes.db_sysarquivo where codarq = 1010919;
                delete from configuracoes.db_sysarqmod where codarq = 1010918;
                delete from configuracoes.db_sysarquivo where codarq = 1010918;


                delete from configuracoes.db_menu where id_item_filho = 228665 AND modulo = 28;
                delete from configuracoes.db_itensmenu where id_item = 228665;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstrutura()
    {
        $sql = <<<SQL
            DROP SEQUENCE compras.pccampanhapublicitaria_pc94_codigo_seq;
            DROP TABLE compras.pccampanhapublicitaria;
            DROP TABLE compras.pctipocampanhapublicitaria;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
