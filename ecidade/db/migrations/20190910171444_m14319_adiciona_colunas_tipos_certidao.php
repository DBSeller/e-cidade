<?php

use Classes\PostgresMigration;

class M14319AdicionaColunasTiposCertidao extends PostgresMigration
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
        $this->upRemoveColunasObsoletas();
        $this->upDicionarioDados();
        $this->upEstruturaDiasCertidao();
        $this->upEstruturaTemplateCertidao();
        $this->upTemplatePadrao();
    }

    public function down()
    {
        $this->downRemoveColunasObsoletas();
        $this->downDicionarioDados();
        $this->downEstruturaDiasCertidao();
        $this->downEstruturaTemplateCertidao();
        $this->downTemplatePadrao();
    }

    public function upRemoveColunasObsoletas()
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
    public function downRemoveColunasObsoletas()
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
SQL
        );
    }

    public function upDicionarioDados()
    {
        $this->execute(<<<SQL
insert into db_syscampo values(1010703,'k03_diascertidregular_cgm','int4','Número de dias de vencimento das certidões positivas com efeito de negativa informadas através de CGM.','0', 'Dias Venc. Certidão Regular por CGM',5,'t','f','f',1,'text','Dias Vencimento Certid. Regular por CGM');
insert into db_syscampo values(1010704,'k03_diascertidregular_matric','int4','Número de dias de vencimento das certidões positivas com efeito de negativa informadas através de Matrícula.','0', 'Dias Venc. Certidão Regular por Matricula',5,'t','f','f',1,'text','Dias Venc. Certid. Regular por Matricula');
insert into db_syscampo values(1010705,'k03_diascertidregular_inscr','int4','Número de dias de vencimento das certidões positivas com efeito de negativa informadas através de inscrição.','0', 'Dias Venc. Certidão Regular por Inscrição',5,'t','f','f',1,'text','Dias Venc. Certid. Regular por Inscrição');
insert into db_syscampo values(1010706,'k03_diascertidpositiva_cgm','int4','Número de dias de vencimento das certidões positivas informadas através de CGM.','0', 'Dias Venc. Certidão Positiva por CGM',5,'t','f','f',1,'text','Dias Vencimento Certid. Positiva por CGM');
insert into db_syscampo values(1010707,'k03_diascertidpositiva_matric','int4','Número de dias de vencimento das certidões positivas informadas através de Matricula.','0', 'Dias Venc. Certidão Positiva por Matricula',5,'t','f','f',1,'text','Dias Venc. Certid. Positiva por Matricul');
insert into db_syscampo values(1010708,'k03_diascertidpositiva_inscr','int4','Número de dias de vencimento das certidões positivas informadas através de Inscrição.','0', 'Dias Venc. Certidão Positiva por Inscrição',5,'t','f','f',1,'text','Dias Venc. Certid. Positiva por Inscriçã');
insert into db_syscampo values(1010709,'k03_diascertidnegativa_cgm','int4','Número de dias de vencimento das certidões negativas informadas através de CGM.','0', 'Dias Venc. Certidão Negativa por CGM',5,'t','f','f',1,'text','Dias Venc. Certid. Negativa por CGM');
insert into db_syscampo values(1010710,'k03_diascertidnegativa_matric','int4','Número de dias de vencimento das certidões negativas informadas através de Matricula.','0', 'Dias Venc. Certidão Negativa por Matricula',5,'t','f','f',1,'text','Dias Venc. Certid. Negativa por Matricul');
insert into db_syscampo values(1010711,'k03_diascertidnegativa_inscr','int4','Número de dias de vencimento das certidões negativas informadas através de Inscrição.','0', 'Dias Venc. Certidão Negativa por Inscrição',5,'t','f','f',1,'text','Dias Venc. Certid. Negativa por Inscriçã');
insert into db_syscampo values(1010712,'k03_templatecertidao_cgm','int4','Template das certidões positivas com efeito de negativa informadas através de CGM.','0', 'Template Certidão Regular por CGM',5,'t','f','f',1,'text','Template Certidão Regular por CGM');
insert into db_syscampo values(1010713,'k03_templatecertidao_matric','int4','Template das certidões positivas com efeito de negativa informadas através de Matricula.','0', 'Template Certidão Regular por Matricula',5,'t','f','f',1,'text','Template Certidão Regular por Matricula');
insert into db_syscampo values(1010714,'k03_templatecertidao_inscr','int4','Template das certidões positivas com efeito de negativa informadas através de Inscrição.','0', 'Template Certidão Regular por Inscrição',5,'t','f','f',1,'text','Template Certidão Regular por Inscrição');
insert into db_syscampo values(1010715,'k03_templatecertidaopositiva_cgm','int4','Template das certidões positivas informadas através de CGM.','0', 'Template Certidão Positiva por CGM',5,'t','f','f',1,'text','Template Certidão Positiva por CGM');
insert into db_syscampo values(1010716,'k03_templatecertidaopositiva_matric','int4','Template das certidões positivas informadas através de Matricula.','0', 'Template Certidão Positiva por Matricula',5,'t','f','f',1,'text','Template Certidão Positiva por Matricula');
insert into db_syscampo values(1010717,'k03_templatecertidaopositiva_inscr','int4','Template das certidões positivas informadas através de Inscrição.','0', 'Template Certidão Positiva por Inscrição',5,'t','f','f',1,'text','Template Certidão Positiva por Inscrição');
insert into db_syscampo values(1010718,'k03_templatecertidaonegativa_cgm','int4','Template das certidões negativas informadas através de CGM.','0', 'Template Certidão Negativa por CGM',5,'t','f','f',1,'text','Template Certidão Negativa por CGM');
insert into db_syscampo values(1010719,'k03_templatecertidaonegativa_matric','int4','Template das certidões negativas informadas através de Matricula.','0', 'Template Certidão Negativa por Matricula',5,'t','f','f',1,'text','Template Certidão Negativa por Matricula');
insert into db_syscampo values(1010720,'k03_templatecertidaonegativa_inscr','int4','Template das certidões negativas informadas através de Inscrição.','0', 'Template Certidão Negativa por Inscrição',5,'t','f','f',1,'text','Template Certidão Negativa por Inscrição');
insert into db_syscampo values(1010721,'k03_diascertidregularweb_cgm','int4','Número de dias de vencimento das certidões positivas com efeito de negativa informadas através de CGM e emitidas via DBPref.','0', 'Dias Venc. Certidão Regular por CGM',5,'t','f','f',1,'text','Dias Vencimento Certid. Regular por CGM');
insert into db_syscampo values(1010722,'k03_diascertidregularweb_matric','int4','Número de dias de vencimento das certidões positivas com efeito de negativa informadas através de Matrícula e emitidas via DBPref.','0', 'Dias Venc. Certidão Regular por Matricula',5,'t','f','f',1,'text','Dias Venc. Certid. Regular por Matricula');
insert into db_syscampo values(1010723,'k03_diascertidregularweb_inscr','int4','Número de dias de vencimento das certidões positivas com efeito de negativa informadas através de Inscrição e emitidas via DBPref.','0', 'Dias Venc. Certidão Regular por Inscrição',5,'t','f','f',1,'text','Dias Venc. Certid. Regular por Inscrição');
insert into db_syscampo values(1010724,'k03_diascertidpositivaweb_cgm','int4','Número de dias de vencimento das certidões positivas informadas através de CGM e emitidas via DBPref.','0', 'Dias Venc. Certidão Positiva por CGM',5,'t','f','f',1,'text','Dias Vencimento Certid. Positiva por CGM');
insert into db_syscampo values(1010725,'k03_diascertidpositivaweb_matric','int4','Número de dias de vencimento das certidões positivas informadas através de Matricula e emitidas via DBPref.','0', 'Dias Venc. Certidão Positiva por Matricula',5,'t','f','f',1,'text','Dias Venc. Certid. Positiva por Matricul');
insert into db_syscampo values(1010726,'k03_diascertidpositivaweb_inscr','int4','Número de dias de vencimento das certidões positivas informadas através de Inscrição e emitidas via DBPref.','0', 'Dias Venc. Certidão Positiva por Inscrição',5,'t','f','f',1,'text','Dias Venc. Certid. Positiva por Inscriçã');
insert into db_syscampo values(1010727,'k03_diascertidnegativaweb_cgm','int4','Número de dias de vencimento das certidões negativas informadas através de CGM e emitidas via DBPref.','0', 'Dias Venc. Certidão Negativa por CGM',5,'t','f','f',1,'text','Dias Venc. Certid. Negativa por CGM');
insert into db_syscampo values(1010728,'k03_diascertidnegativaweb_matric','int4','Número de dias de vencimento das certidões negativas informadas através de Matricula e emitidas via DBPref.','0', 'Dias Venc. Certidão Negativa por Matricula',5,'t','f','f',1,'text','Dias Venc. Certid. Negativa por Matricul');
insert into db_syscampo values(1010729,'k03_diascertidnegativaweb_inscr','int4','Número de dias de vencimento das certidões negativas informadas através de Inscrição e emitidas via DBPref.','0', 'Dias Venc. Certidão Negativa por Inscrição',5,'t','f','f',1,'text','Dias Venc. Certid. Negativa por Inscriçã');
insert into db_syscampo values(1010730,'k03_templatecertidaoweb_cgm','int4','Template das certidões positivas com efeito de negativa informadas através de CGM e emitidas via DBPref.','0', 'Template Certidão Regular por CGM',5,'t','f','f',1,'text','Template Certidão Regular por CGM');
insert into db_syscampo values(1010731,'k03_templatecertidaoweb_matric','int4','Template das certidões positivas com efeito de negativa informadas através de Matricula e emitidas via DBPref.','0', 'Template Certidão Regular por Matricula',5,'t','f','f',1,'text','Template Certidão Regular por Matricula');
insert into db_syscampo values(1010732,'k03_templatecertidaoweb_inscr','int4','Template das certidões positivas com efeito de negativa informadas através de Inscrição e emitidas via DBPref.','0', 'Template Certidão Regular por Inscrição',5,'t','f','f',1,'text','Template Certidão Regular por Inscrição');
insert into db_syscampo values(1010733,'k03_templatecertidaopositivaweb_cgm','int4','Template das certidões positivas informadas através de CGM e emitidas via DBPref.','0', 'Template Certidão Positiva por CGM',5,'t','f','f',1,'text','Template Certidão Positiva por CGM');
insert into db_syscampo values(1010734,'k03_templatecertidaopositivaweb_matric','int4','Template das certidões positivas informadas através de CGM e emitidas via DBPref.','0', 'Template Certidão Positiva por Matricula',5,'t','f','f',1,'text','Template Certidão Positiva por Matricula');
insert into db_syscampo values(1010736,'k03_templatecertidaopositivaweb_inscr','int4','Template das certidões positivas informadas através de Inscrição e emitidas via DBPref.','0', 'Template Certidão Positiva por Inscrição',5,'t','f','f',1,'text','Template Certidão Positiva por Inscrição');
insert into db_syscampo values(1010737,'k03_templatecertidaonegativaweb_cgm','int4','Template das certidões negativas informadas através de CGM e emitidas via DBPref.','0', 'Template Certidão Negativa por CGM',5,'t','f','f',1,'text','Template Certidão Negativa por CGM');
insert into db_syscampo values(1010738,'k03_templatecertidaonegativaweb_matric','int4','Template das certidões negativas informadas através de Matricula e emitidas via DBPref.','0', 'Template Certidão Negativa por Matricula',5,'t','f','f',1,'text','Template Certidão Negativa por Matricula');
insert into db_syscampo values(1010739,'k03_templatecertidaonegativaweb_inscr','int4','Template das certidões negativas informadas através de Inscrição e emitidas via DBPref.','0', 'Template Certidão Negativa por Inscrição',5,'t','f','f',1,'text','Template Certidão Negativa por Inscrição');
delete from db_sysarqcamp where codarq = 318;
insert into db_sysarqcamp values(318,1904,1,0);
insert into db_sysarqcamp values(318,10716,2,0);
insert into db_sysarqcamp values(318,1905,3,17);
insert into db_sysarqcamp values(318,1906,4,0);
insert into db_sysarqcamp values(318,1907,5,0);
insert into db_sysarqcamp values(318,1908,6,0);
insert into db_sysarqcamp values(318,1909,7,0);
insert into db_sysarqcamp values(318,1910,8,0);
insert into db_sysarqcamp values(318,1911,9,0);
insert into db_sysarqcamp values(318,1912,10,0);
insert into db_sysarqcamp values(318,1913,11,0);
insert into db_sysarqcamp values(318,1914,12,0);
insert into db_sysarqcamp values(318,1915,13,0);
insert into db_sysarqcamp values(318,7918,14,0);
insert into db_sysarqcamp values(318,7925,15,0);
insert into db_sysarqcamp values(318,7943,16,0);
insert into db_sysarqcamp values(318,8737,17,0);
insert into db_sysarqcamp values(318,8797,18,0);
insert into db_sysarqcamp values(318,8799,19,0);
insert into db_sysarqcamp values(318,9419,20,0);
insert into db_sysarqcamp values(318,11859,21,0);
insert into db_sysarqcamp values(318,14400,22,0);
insert into db_sysarqcamp values(318,14484,23,0);
insert into db_sysarqcamp values(318,14587,24,0);
insert into db_sysarqcamp values(318,15036,25,0);
insert into db_sysarqcamp values(318,17195,26,0);
insert into db_sysarqcamp values(318,17196,27,0);
insert into db_sysarqcamp values(318,17943,28,0);
insert into db_sysarqcamp values(318,18059,29,0);
insert into db_sysarqcamp values(318,18150,30,0);
insert into db_sysarqcamp values(318,18429,31,0);
insert into db_sysarqcamp values(318,18468,32,0);
insert into db_sysarqcamp values(318,18874,33,0);
insert into db_sysarqcamp values(318,19223,34,0);
insert into db_sysarqcamp values(318,19647,35,0);
insert into db_sysarqcamp values(318,20614,36,0);
insert into db_sysarqcamp values(318,20230,37,0);
insert into db_sysarqcamp values(318,1010703,38,0);
insert into db_sysarqcamp values(318,1010704,39,0);
insert into db_sysarqcamp values(318,1010705,40,0);
insert into db_sysarqcamp values(318,1010706,41,0);
insert into db_sysarqcamp values(318,1010707,42,0);
insert into db_sysarqcamp values(318,1010708,43,0);
insert into db_sysarqcamp values(318,1010709,44,0);
insert into db_sysarqcamp values(318,1010710,45,0);
insert into db_sysarqcamp values(318,1010711,46,0);
insert into db_sysarqcamp values(318,1010712,47,0);
insert into db_sysarqcamp values(318,1010713,48,0);
insert into db_sysarqcamp values(318,1010714,49,0);
insert into db_sysarqcamp values(318,1010715,50,0);
insert into db_sysarqcamp values(318,1010716,51,0);
insert into db_sysarqcamp values(318,1010717,52,0);
insert into db_sysarqcamp values(318,1010718,53,0);
insert into db_sysarqcamp values(318,1010719,54,0);
insert into db_sysarqcamp values(318,1010720,55,0);
insert into db_sysarqcamp values(318,1010721,56,0);
insert into db_sysarqcamp values(318,1010722,57,0);
insert into db_sysarqcamp values(318,1010723,58,0);
insert into db_sysarqcamp values(318,1010724,59,0);
insert into db_sysarqcamp values(318,1010725,60,0);
insert into db_sysarqcamp values(318,1010726,61,0);
insert into db_sysarqcamp values(318,1010727,62,0);
insert into db_sysarqcamp values(318,1010728,63,0);
insert into db_sysarqcamp values(318,1010729,64,0);
insert into db_sysarqcamp values(318,1010730,65,0);
delete from db_sysarqcamp where codarq = 318;
insert into db_sysarqcamp values(318,1904,1,0);
insert into db_sysarqcamp values(318,10716,2,0);
insert into db_sysarqcamp values(318,1905,3,17);
insert into db_sysarqcamp values(318,1906,4,0);
insert into db_sysarqcamp values(318,1907,5,0);
insert into db_sysarqcamp values(318,1908,6,0);
insert into db_sysarqcamp values(318,1909,7,0);
insert into db_sysarqcamp values(318,1910,8,0);
insert into db_sysarqcamp values(318,1911,9,0);
insert into db_sysarqcamp values(318,1912,10,0);
insert into db_sysarqcamp values(318,1913,11,0);
insert into db_sysarqcamp values(318,1914,12,0);
insert into db_sysarqcamp values(318,1915,13,0);
insert into db_sysarqcamp values(318,7918,14,0);
insert into db_sysarqcamp values(318,7925,15,0);
insert into db_sysarqcamp values(318,7943,16,0);
insert into db_sysarqcamp values(318,8737,17,0);
insert into db_sysarqcamp values(318,8797,18,0);
insert into db_sysarqcamp values(318,8799,19,0);
insert into db_sysarqcamp values(318,9419,20,0);
insert into db_sysarqcamp values(318,11859,21,0);
insert into db_sysarqcamp values(318,14400,22,0);
insert into db_sysarqcamp values(318,14484,23,0);
insert into db_sysarqcamp values(318,14587,24,0);
insert into db_sysarqcamp values(318,15036,25,0);
insert into db_sysarqcamp values(318,17195,26,0);
insert into db_sysarqcamp values(318,17196,27,0);
insert into db_sysarqcamp values(318,17943,28,0);
insert into db_sysarqcamp values(318,18059,29,0);
insert into db_sysarqcamp values(318,18150,30,0);
insert into db_sysarqcamp values(318,18429,31,0);
insert into db_sysarqcamp values(318,18468,32,0);
insert into db_sysarqcamp values(318,18874,33,0);
insert into db_sysarqcamp values(318,19223,34,0);
insert into db_sysarqcamp values(318,19647,35,0);
insert into db_sysarqcamp values(318,20614,36,0);
insert into db_sysarqcamp values(318,20230,37,0);
insert into db_sysarqcamp values(318,1010703,38,0);
insert into db_sysarqcamp values(318,1010704,39,0);
insert into db_sysarqcamp values(318,1010705,40,0);
insert into db_sysarqcamp values(318,1010706,41,0);
insert into db_sysarqcamp values(318,1010707,42,0);
insert into db_sysarqcamp values(318,1010708,43,0);
insert into db_sysarqcamp values(318,1010709,44,0);
insert into db_sysarqcamp values(318,1010710,45,0);
insert into db_sysarqcamp values(318,1010711,46,0);
insert into db_sysarqcamp values(318,1010712,47,0);
insert into db_sysarqcamp values(318,1010713,48,0);
insert into db_sysarqcamp values(318,1010714,49,0);
insert into db_sysarqcamp values(318,1010715,50,0);
insert into db_sysarqcamp values(318,1010716,51,0);
insert into db_sysarqcamp values(318,1010717,52,0);
insert into db_sysarqcamp values(318,1010718,53,0);
insert into db_sysarqcamp values(318,1010719,54,0);
insert into db_sysarqcamp values(318,1010720,55,0);
insert into db_sysarqcamp values(318,1010721,56,0);
insert into db_sysarqcamp values(318,1010722,57,0);
insert into db_sysarqcamp values(318,1010723,58,0);
insert into db_sysarqcamp values(318,1010724,59,0);
insert into db_sysarqcamp values(318,1010725,60,0);
insert into db_sysarqcamp values(318,1010726,61,0);
insert into db_sysarqcamp values(318,1010727,62,0);
insert into db_sysarqcamp values(318,1010728,63,0);
insert into db_sysarqcamp values(318,1010729,64,0);
insert into db_sysarqcamp values(318,1010730,65,0);
insert into db_sysarqcamp values(318,1010731,66,0);
insert into db_sysarqcamp values(318,1010732,67,0);
insert into db_sysarqcamp values(318,1010733,68,0);
insert into db_sysarqcamp values(318,1010734,69,0);
insert into db_sysarqcamp values(318,1010736,70,0);
insert into db_sysarqcamp values(318,1010737,71,0);
insert into db_sysarqcamp values(318,1010738,72,0);
insert into db_sysarqcamp values(318,1010739,73,0);
SQL
        );
    }
    public function downDicionarioDados()
    {
        $this->execute(<<<SQL
            delete from db_sysarqcamp where codarq = 318 and codcam in (1010703,1010704,1010705,1010706,1010707,1010708,1010709,1010710,1010711,1010712,1010713,1010714,1010715,1010716,1010717,1010718,1010719,1010720,1010721,1010722,1010723,1010724,1010725,1010726,1010727,1010728,1010729,1010730,1010731,1010732,1010733,1010734,1010736,1010737,1010738,1010739);
            delete from db_syscampo where codcam in (1010703,1010704,1010705,1010706,1010707,1010708,1010709,1010710,1010711,1010712,1010713,1010714,1010715,1010716,1010717,1010718,1010719,1010720,1010721,1010722,1010723,1010724,1010725,1010726,1010727,1010728,1010729,1010730,1010731,1010732,1010733,1010734,1010736,1010737,1010738,1010739);
SQL
        );
    }

    public function upEstruturaDiasCertidao()
    {
        $this->execute(<<<SQL
            ALTER TABLE numpref
                ADD COLUMN k03_diascertidregular_cgm integer DEFAULT 60,
                ADD COLUMN k03_diascertidregular_matric integer DEFAULT 60,
                ADD COLUMN k03_diascertidregular_inscr integer DEFAULT 60,
                
                ADD COLUMN k03_diascertidpositiva_cgm integer DEFAULT 60,
                ADD COLUMN k03_diascertidpositiva_matric integer DEFAULT 60,
                ADD COLUMN k03_diascertidpositiva_inscr integer DEFAULT 60,
                
                ADD COLUMN k03_diascertidnegativa_cgm integer DEFAULT 60,
                ADD COLUMN k03_diascertidnegativa_matric integer DEFAULT 60,
                ADD COLUMN k03_diascertidnegativa_inscr integer DEFAULT 60,
                
                ADD COLUMN k03_diascertidregularweb_cgm integer DEFAULT 30,
                ADD COLUMN k03_diascertidregularweb_matric integer DEFAULT 30,
                ADD COLUMN k03_diascertidregularweb_inscr integer DEFAULT 30,
                
                ADD COLUMN k03_diascertidpositivaweb_cgm integer DEFAULT 30,
                ADD COLUMN k03_diascertidpositivaweb_matric integer DEFAULT 30,
                ADD COLUMN k03_diascertidpositivaweb_inscr integer DEFAULT 30,
                
                ADD COLUMN k03_diascertidnegativaweb_cgm integer DEFAULT 30,
                ADD COLUMN k03_diascertidnegativaweb_matric integer DEFAULT 30,
                ADD COLUMN k03_diascertidnegativaweb_inscr integer DEFAULT 30;
SQL
        );
    }
    public function downEstruturaDiasCertidao()
    {
        $this->execute(<<<SQL
            ALTER TABLE numpref
                DROP COLUMN k03_diascertidregular_cgm,
                DROP COLUMN k03_diascertidregular_matric,
                DROP COLUMN k03_diascertidregular_inscr,
                
                DROP COLUMN k03_diascertidpositiva_cgm,
                DROP COLUMN k03_diascertidpositiva_matric,
                DROP COLUMN k03_diascertidpositiva_inscr,
                
                DROP COLUMN k03_diascertidnegativa_cgm,
                DROP COLUMN k03_diascertidnegativa_matric,
                DROP COLUMN k03_diascertidnegativa_inscr,
                
                DROP COLUMN k03_diascertidregularweb_cgm,
                DROP COLUMN k03_diascertidregularweb_matric,
                DROP COLUMN k03_diascertidregularweb_inscr,
                
                DROP COLUMN k03_diascertidpositivaweb_cgm,
                DROP COLUMN k03_diascertidpositivaweb_matric,
                DROP COLUMN k03_diascertidpositivaweb_inscr,
                
                DROP COLUMN k03_diascertidnegativaweb_cgm,
                DROP COLUMN k03_diascertidnegativaweb_matric,
                DROP COLUMN k03_diascertidnegativaweb_inscr;
SQL
        );
    }

    public function upEstruturaTemplateCertidao()
    {
        $this->execute(<<<SQL
            ALTER TABLE numpref
                ADD COLUMN k03_templatecertidao_cgm integer,
                ADD COLUMN k03_templatecertidao_matric integer,
                ADD COLUMN k03_templatecertidao_inscr integer,
                
                ADD COLUMN k03_templatecertidaopositiva_cgm integer,
                ADD COLUMN k03_templatecertidaopositiva_matric integer,
                ADD COLUMN k03_templatecertidaopositiva_inscr integer,
                
                ADD COLUMN k03_templatecertidaonegativa_cgm integer,
                ADD COLUMN k03_templatecertidaonegativa_matric integer,
                ADD COLUMN k03_templatecertidaonegativa_inscr integer,
                
                ADD COLUMN k03_templatecertidaoweb_cgm integer,
                ADD COLUMN k03_templatecertidaoweb_matric integer,
                ADD COLUMN k03_templatecertidaoweb_inscr integer,
                
                ADD COLUMN k03_templatecertidaopositivaweb_cgm integer,
                ADD COLUMN k03_templatecertidaopositivaweb_matric integer,
                ADD COLUMN k03_templatecertidaopositivaweb_inscr integer,
                
                ADD COLUMN k03_templatecertidaonegativaweb_cgm integer,
                ADD COLUMN k03_templatecertidaonegativaweb_matric integer,
                ADD COLUMN k03_templatecertidaonegativaweb_inscr integer;
SQL
        );
    }
    public function downEstruturaTemplateCertidao()
    {
        $this->execute(<<<SQL
            ALTER TABLE numpref
                DROP COLUMN k03_templatecertidao_cgm,
                DROP COLUMN k03_templatecertidao_matric,
                DROP COLUMN k03_templatecertidao_inscr,
                
                DROP COLUMN k03_templatecertidaopositiva_cgm,
                DROP COLUMN k03_templatecertidaopositiva_matric,
                DROP COLUMN k03_templatecertidaopositiva_inscr,
                
                DROP COLUMN k03_templatecertidaonegativa_cgm,
                DROP COLUMN k03_templatecertidaonegativa_matric,
                DROP COLUMN k03_templatecertidaonegativa_inscr,
                
                DROP COLUMN k03_templatecertidaoweb_cgm,
                DROP COLUMN k03_templatecertidaoweb_matric,
                DROP COLUMN k03_templatecertidaoweb_inscr,
                
                DROP COLUMN k03_templatecertidaopositivaweb_cgm,
                DROP COLUMN k03_templatecertidaopositivaweb_matric,
                DROP COLUMN k03_templatecertidaopositivaweb_inscr,
                
                DROP COLUMN k03_templatecertidaonegativaweb_cgm,
                DROP COLUMN k03_templatecertidaonegativaweb_matric,
                DROP COLUMN k03_templatecertidaonegativaweb_inscr;
SQL
        );
    }

    public function upTemplatePadrao()
    {
        $this->execute(<<<SQL
            SELECT setval('db_documentotemplatepadrao_db81_sequencial_seq', (select max(db81_sequencial) + 1 from db_documentotemplatepadrao), FALSE);
             
            INSERT INTO db_documentotemplatepadrao 
            VALUES 
                ( 
                    nextval('db_documentotemplatepadrao_db81_sequencial_seq'), 
                    57, 
                    'Certidão Regularidade Padrão', 
                    'documentos/templates/modelo_variaveis_certidao.sxw'
                );
SQL
        );
    }

    public function downTemplatePadrao()
    {
        $this->execute(<<<SQL
            DELETE FROM db_documentotemplatepadrao WHERE db81_templatetipo = 57;
SQL
        );
    }
}