<?php

use Classes\PostgresMigration;

class M15278CobrancaRegistradaBancoBrasil extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            INSERT INTO db_syscampo VALUES(1011115,'ar28_codban','char(10)','Campo com o código do banco do webservice.','', 'Código do Banco',10,'f','t','f',0,'text','Código do Banco');
            INSERT INTO db_syscampo VALUES(1011143,'ar28_clientid','varchar(1000)','Token com o ID do cliente para autenticação com Banco do Brasil.','', 'ClientID',1000,'t','f','f',0,'text','ClientID');
            INSERT INTO db_syscampo VALUES(1011144,'ar28_clientsecret','varchar(1000)','Token para autenticação com o Banco do Brasil.','', 'Client Secret',1000,'t','f','f',0,'text','Client Secret');
            INSERT INTO db_syscampo VALUES(1011159,'ar28_chavej','varchar(10)','Código para acessar a transações do cliente.','0', 'Chave J',10,'t','f','f',1,'text','Chave J');

            INSERT INTO db_sysarqcamp VALUES(1010208,1011115,3,0);
            INSERT INTO db_sysarqcamp VALUES(1010208,1011143,4,0);
            INSERT INTO db_sysarqcamp VALUES(1010208,1011144,5,0);
            INSERT INTO db_sysarqcamp VALUES(1010208,1011159,6,0);

            INSERT INTO db_sysforkey VALUES(1010208,1011115,1,1185,0);

            ALTER TABLE arrecadacao.parametroscobrancaregistrada ADD COLUMN ar28_clientid TEXT;

            ALTER TABLE arrecadacao.parametroscobrancaregistrada ADD COLUMN ar28_clientsecret TEXT;

            ALTER TABLE arrecadacao.parametroscobrancaregistrada ADD COLUMN ar28_codban character varying (10);

            ALTER TABLE arrecadacao.parametroscobrancaregistrada ADD CONSTRAINT db_bancos_fk FOREIGN KEY (ar28_codban) REFERENCES db_bancos(db90_codban);

            ALTER TABLE arrecadacao.parametroscobrancaregistrada ADD COLUMN ar28_chavej text;

            UPDATE db_syscampo
               SET nomecam = 'ar28_usuario',
                   conteudo = 'varchar(30)',
                   descricao = 'Usuário do Webservice da Caixa',
                   valorinicial = '',
                   rotulo = 'Usuário',
                   nulo = 't',
                   tamanho = 30,
                   maiusculo = 't',
                   autocompl = 'f',
                   aceitatipo = 0,
                   tipoobj = 'text',
                   rotulorel = 'Usuário'
             WHERE codcam = 1009338;
SQL
    );
    }

    public function down()
    {
        $this->execute(<<<SQL
            delete from db_sysarqcamp where codcam in (1011115, 1011143, 1011144, 1011159);
            delete from db_sysforkey where codarq = 1010208;
            DELETE FROM db_syscampo WHERE codcam IN (1011115, 1011143, 1011144, 1011159);

            ALTER TABLE arrecadacao.parametroscobrancaregistrada
             DROP COLUMN ar28_clientid;

            ALTER TABLE arrecadacao.parametroscobrancaregistrada
             DROP COLUMN ar28_clientsecret;

            ALTER TABLE arrecadacao.parametroscobrancaregistrada
             DROP COLUMN ar28_codban;

            ALTER TABLE arrecadacao.parametroscobrancaregistrada
             DROP COLUMN ar28_chavej;

            UPDATE db_syscampo
               SET nomecam = 'ar28_usuario',
                   conteudo = 'varchar(30)',
                   descricao = 'Usuário do Webservice da Caixa',
                   valorinicial = '',
                   rotulo = 'Usuário',
                   nulo = 'f',
                   tamanho = 30,
                   maiusculo = 't',
                   autocompl = 'f',
                   aceitatipo = 0,
                   tipoobj = 'text',
                   rotulorel = 'Usuário'
             WHERE codcam = 1009338;
SQL
    );
    }
}
