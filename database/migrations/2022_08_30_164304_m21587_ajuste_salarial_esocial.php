 <?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21587AjusteSalarialEsocial extends Migration
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
        -- TABELA recursoshumanos.rhreajustesalarialesocial
        insert into configuracoes.db_sysarquivo values (1010984, 'rhreajustesalarialesocial', 'Alteração de dados contratuais para o eSocial.', 'eso39', '2022-08-30', 'Reajuste salarial eSocial', 0, 'f', 'f', 'f', 'f' );
        insert into configuracoes.db_sysarqmod values (29,1010984);
        insert into configuracoes.db_syscampo values(1014464,'eso39_sequencial','int8','Código sequencial único da tabela.','0', 'Código Sequencial',10,'f','f','f',1,'text','Código Sequencial');
        insert into configuracoes.db_syscampo values(1014465,'eso39_matricula','int8','Matrícula atribuída ao trabalhador.','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');
        insert into configuracoes.db_syscampo values(1014466,'eso39_dataefeito','date','Data dos efeitos remuneratórios da alteração contratual.','null', 'Data Efetividade',10,'f','f','f',1,'text','Data Efetividade');
        insert into configuracoes.db_syscampo values(1014467,'eso39_tipo','char(1)','Tipo do instrumento ou situação ensejadora da remuneração relativa a períodos de apuração anteriores.','', 'Tipo do instrumento ',1,'f','t','f',0,'text','Tipo do instrumento ');
        insert into configuracoes.db_syscampo values(1014468,'eso39_descricao','varchar(150)','Descrição da alteração ou do instrumento que a gerou.','', 'Descrição',150,'f','t','f',0,'text','Descrição');
        insert into configuracoes.db_syscampo values(1014505,'eso39_alteracao','date','Data de alteração de dados contratuais.','null', 'Data Alteração:',10,'t','f','f',1,'text','Data Alteração:');
        insert into configuracoes.db_sysarqcamp values(1010984,1014464,1,0);
        insert into configuracoes.db_sysarqcamp values(1010984,1014465,2,0);
        insert into configuracoes.db_sysarqcamp values(1010984,1014466,3,0);
        insert into configuracoes.db_sysarqcamp values(1010984,1014467,4,0);
        insert into configuracoes.db_sysarqcamp values(1010984,1014468,5,0);
        insert into configuracoes.db_sysarqcamp values(1010984,1014505,6,0);
        insert into configuracoes.db_syssequencia values(1001088, 'rhreajustesalarialesocial_eso39_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update configuracoes.db_sysarqcamp set codsequencia = 1001088 where codarq = 1010984 and codcam = 1014464;
        insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010984,1014464,1,1014464);
        insert into configuracoes.db_sysforkey values(1010984,1014465,1,1153,0);
        update configuracoes.db_syscampo set nomecam = 'eso39_descricao', conteudo = 'varchar(150)', descricao = 'Descrição da alteração ou do instrumento que a gerou.', valorinicial = '', rotulo = 'Descrição', nulo = 't', tamanho = 150, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Descrição' where codcam = 1014468;
        delete from configuracoes.db_syscampodep where codcam = 1014468;
        delete from configuracoes.db_syscampodef where codcam = 1014468;

        -- CAMPO DA TABELA pessoal.cfpess
        insert into configuracoes.db_syscampo values(1014471,'r11_rubricadifsalario','varchar(4)','Quando houver no cálculo salário da rubrica será efetuado os preenchimento de data , tipo de instrumento e data de assinatura de acordo, e os respectivos dados de informações dos pagamentos referente a período anteriores no eSocial.','', 'Rubrica Dif. Reajuste Salarial',4,'t','t','f',0,'text','Rubrica Dif. Reajuste Salarial');
        insert into configuracoes.db_sysarqcamp values(536,1014471,108,0);

SQL;
        DB::connection()->getPdo()->exec($sql);
}


private function downDicionario()
{
    $sql = <<<SQL
    -- REFERENTE A TABELA recursoshumanos.rhreajustesalarialesocial
    delete from configuracoes.db_sysforkey where codarq = 1010984;
    delete from configuracoes.db_sysprikey where codarq = 1010984;
    delete from configuracoes.db_syssequencia where codsequencia = 1001088;
    delete from configuracoes.db_sysarqcamp where codarq = 1010984;
    delete from configuracoes.db_syscampo where codcam in (1014464,1014465,1014466,1014467,1014468,1014505);
    delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1010984; 
    delete from configuracoes.db_sysarquivo where codarq = 1010984;

    -- REFERENTE A TABELA pessoal.cfpess
    delete from configuracoes.db_sysarqcamp where codarq = 536 and codcam = 1014471 and seqarq = 108;
    delete from configuracoes.db_syscampo where codcam = 1014471;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstrutura()
    {
        $sql = <<<SQL
        -- Criando  sequences
        CREATE SEQUENCE recursoshumanos.rhreajustesalarialesocial_eso39_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;

        -- TABELAS E ESTRUTURA
        -- Módulo: recursoshumanos
        CREATE TABLE recursoshumanos.rhreajustesalarialesocial(
        eso39_sequencial	int8 NOT NULL default nextval('rhreajustesalarialesocial_eso39_sequencial_seq'),
        eso39_matricula		int8 NOT NULL default 0,
        eso39_dataefeito	date NOT NULL default null,
        eso39_tipo		    char(1) NOT NULL  default '',
        eso39_descricao		varchar(150)  default '',
        eso39_alteracao  	date NOT NULL default CURRENT_DATE,
        CONSTRAINT rhreajustesalarialesocial_sequ_pk PRIMARY KEY (eso39_sequencial));

        ALTER TABLE recursoshumanos.rhreajustesalarialesocial
        ADD CONSTRAINT rhreajustesalarialesocial_matricula_fk FOREIGN KEY (eso39_matricula)
        REFERENCES pessoal.rhpessoal;

        --TABELA pessoal.cfpess
        ALTER TABLE pessoal.cfpess ADD r11_rubricadifsalario varchar(4) NULL;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstrutura()
    {
        $sql = <<<SQL
        --DROP TABLE:
        DROP TABLE IF EXISTS recursoshumanos.rhreajustesalarialesocial;
        --Criando drop sequences
        DROP SEQUENCE IF EXISTS recursoshumanos.rhreajustesalarialesocial_eso39_sequencial_seq;
        --TABELA pessoal.cfpess
        ALTER TABLE pessoal.cfpess DROP r11_rubricadifsalario;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
