<?php

use Classes\PostgresMigration;

class M12850AdicionaColunasTiposCertidao extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->upDicionarioDados();
        $this->upEstruturaTipo();
        $this->upTipoTemplateCertidao();
    }

    public function down()
    {
        $this->downTipoTemplateCertidao();
        $this->downDicionarioDados();
        $this->downEstruturaTipo();
    }

    public function upDicionarioDados()
    {
        $this->execute(<<<SQL
            insert into db_syscampo values(1010681,'k03_diasvalidadecertidaoweb','int4','Número de dias de vencimento das certidões regulares emitidas por meio eletrônico','0', 'Certidões Regulares',5,'t','f','f',1,'text','Dias venc. das Certidões Regulares Web');
            insert into db_syscampo values(1010682,'k03_diasvalidadecertidaopositiva','int4','Número de dias de vencimento das certidões positivas','0', 'Certidões Positivas',5,'t','f','f',1,'text','Dias venc. das Certidões Positivas');
            insert into db_syscampo values(1010683,'k03_diasvalidadecertidaopositivaweb','int4','Número de dias de vencimento das certidões positivas emitidas por meio eletrônico','0', 'Certidões Positivas',5,'t','f','f',1,'text','Dias venc. das Certidões Positivas Web');
            insert into db_syscampo values(1010684,'k03_diasvalidadecertidaonegativa','int4','Número de dias de vencimento das certidões negativas','0', 'Certidões Negativas',5,'t','f','f',1,'text','Dias venc. das Certidões Negativas');
            insert into db_syscampo values(1010685,'k03_diasvalidadecertidaonegativaweb','int4','Número de dias de vencimento das certidões negativas emitidas por meio eletrônico','0', 'Certidões Negativas',5,'t','f','f',1,'text','Dias venc. das Certidões Negativas Web');
            
            insert into db_syscampo values(1010686,'k03_templatecertidao','int4','Template de documento de Certidão Regular','0', 'Template de documento de Certidão Regular',5,'t','f','f',1,'text','Template de Certidão Regular');
            insert into db_syscampo values(1010687,'k03_templatecertidaoweb','int4','Template de documento de Certidão Regular emitido via DBPref','0', 'Template de documento de Certidão Regular DBPref',5,'t','f','f',1,'text','Template de Certidão Regular DBPref');
            insert into db_syscampo values(1010688,'k03_templatecertidaopositiva','int4','Template de documento de Certidão Positiva','0', 'Template de documento de Certidão Positiva',5,'t','f','f',1,'text','Template de Certidão Positiva');
            insert into db_syscampo values(1010689,'k03_templatecertidaopositivaweb','int4','Template de documento de Certidão Positiva emitido via DBPref','0', 'Template de documento de Certidão Positiva DBPref',5,'t','f','f',1,'text','Template de Certidão Positiva DBPref');
            insert into db_syscampo values(1010690,'k03_templatecertidaonegativa','int4','Template de documento de Certidão Negativa','0', 'Template de documento de Certidão Negativa',5,'t','f','f',1,'text','Template de Certidão Negativa');
            insert into db_syscampo values(1010691,'k03_templatecertidaonegativaweb','int4','Template de documento de Certidão Negativa emitido via DBPref','0', 'Template de documento de Certidão Negativa DBPref',5,'t','f','f',1,'text','Template de Certidão Negativa DBPref');
            
            insert into db_sysarqcamp values(318,1010681,39,0);
            insert into db_sysarqcamp values(318,1010684,40,0);
            insert into db_sysarqcamp values(318,1010685,41,0);
            insert into db_sysarqcamp values(318,1010682,42,0);
            insert into db_sysarqcamp values(318,1010683,43,0);
            
            insert into db_sysarqcamp values(318,1010686,44,0);
            insert into db_sysarqcamp values(318,1010687,45,0);
            insert into db_sysarqcamp values(318,1010688,46,0);
            insert into db_sysarqcamp values(318,1010689,47,0);
            insert into db_sysarqcamp values(318,1010690,48,0);
            insert into db_sysarqcamp values(318,1010691,49,0);            
SQL
        );
    }

    public function downDicionarioDados()
    {
        $this->execute(<<<SQL
            delete from db_sysarqcamp where codarq = 318 and codcam in (1010681, 1010682, 1010683, 1010684, 1010685, 1010686, 1010687, 1010688, 1010689, 1010690, 1010691);
            delete from db_syscampo where codcam in (1010681, 1010682, 1010683, 1010684, 1010685, 1010686, 1010687, 1010688, 1010689, 1010690, 1010691);
SQL
        );
    }

    public function upEstruturaTipo()
    {
        $this->execute(<<<SQL
            ALTER TABLE numpref
                ADD COLUMN k03_diasvalidadecertidaoweb integer default 30, 
                ADD COLUMN k03_diasvalidadecertidaopositiva integer default 60,
                ADD COLUMN k03_diasvalidadecertidaopositivaweb integer default 30,
                ADD COLUMN k03_diasvalidadecertidaonegativa integer default 60,
                ADD COLUMN k03_diasvalidadecertidaonegativaweb integer default 30,
                
                ADD COLUMN k03_templatecertidao integer,
                ADD COLUMN k03_templatecertidaoweb integer,
                ADD COLUMN k03_templatecertidaopositiva integer,
                ADD COLUMN k03_templatecertidaopositivaweb integer,
                ADD COLUMN k03_templatecertidaonegativa integer,
                ADD COLUMN k03_templatecertidaonegativaweb integer;
                
        UPDATE db_syscampo SET rotulo = 'Certidões Regulares' WHERE nomecam = 'k03_diasvalidadecertidao' AND codcam = 20229;
SQL
        );
    }

    public function downEstruturaTipo()
    {
        $this->execute(<<<SQL
            ALTER TABLE numpref 
                DROP COLUMN k03_diasvalidadecertidaoweb,
                DROP COLUMN k03_diasvalidadecertidaopositiva,
                DROP COLUMN k03_diasvalidadecertidaopositivaweb,
                DROP COLUMN k03_diasvalidadecertidaonegativa,
                DROP COLUMN k03_diasvalidadecertidaonegativaweb,
                
                DROP COLUMN k03_templatecertidao,
                DROP COLUMN k03_templatecertidaoweb,
                DROP COLUMN k03_templatecertidaopositiva,
                DROP COLUMN k03_templatecertidaopositivaweb,
                DROP COLUMN k03_templatecertidaonegativa,
                DROP COLUMN k03_templatecertidaonegativaweb;
SQL
        );
    }

    public function upTipoTemplateCertidao()
    {
        $this->execute(<<<SQL
            INSERT INTO db_documentotemplatetipo VALUES (57, 'Certidão de Regularidade Fiscal');
SQL
        );
    }

    public function downTipoTemplateCertidao()
    {
        $this->execute(<<<SQL
            DELETE FROM db_documentotemplate WHERE db82_templatetipo = 57;
            DELETE FROM db_documentotemplatetipo WHERE db80_sequencial = 57;
SQL
        );
    }
}