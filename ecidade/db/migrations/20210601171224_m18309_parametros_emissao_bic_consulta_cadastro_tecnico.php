<?php

use Classes\PostgresMigration;

class M18309ParametrosEmissaoBicConsultaCadastroTecnico extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL

insert into db_syscampo values(1013275,'j18_permitectmcgf','bool','Permite efetuar a consulta do Cadastro Técnico Municipal a partir do link da Matrícula na Consulta Geral Financeira.','\'t\'', 'Permite consultar CTM com Matric na CGF',1,'f','f','f',5,'text','Permite consultar CTM com Matric na CGF');
insert into db_syscampo values(1013276,'j18_bicmarcasigilo','bool','Parâmetro para deixar marcado ou não a opção de emissão de dados sigilosos na emissão da BIC.','\'t\'', 'BIC Marca Emissão Dados Sigilosos',1,'f','f','f',5,'text','BIC Marca Emissão Dados Sigilosos');

update db_syscampo set nomecam = 'j18_permitectmcgf', conteudo = 'bool', descricao = 'Permite efetuar a consulta do Cadastro Técnico Municipal a partir do link da Matrícula na Consulta Geral Financeira.', valorinicial = 'true', rotulo = 'Permite consultar CTM com Matric na CGF', nulo = 'f', tamanho = 1, maiusculo = 'f', autocompl = 'f', aceitatipo = 5, tipoobj = 'text', rotulorel = 'Permite consultar CTM com Matric na CGF' where codcam = 1013275;

update db_syscampo set nomecam = 'j18_bicmarcasigilo', conteudo = 'bool', descricao = 'Parâmetro para deixar marcado ou não a opção de emissão de dados sigilosos na emissão da BIC.', valorinicial = 'true', rotulo = 'BIC Marca Emissão Dados Sigilosos', nulo = 'f', tamanho = 1, maiusculo = 'f', autocompl = 'f', aceitatipo = 5, tipoobj = 'text', rotulorel = 'BIC Marca Emissão Dados Sigilosos' where codcam = 1013276;

insert into db_sysarqcamp values(153,1013276,36,0);
insert into db_sysarqcamp values(153,1013275,37,0);

ALTER TABLE cfiptu ADD COLUMN j18_permitectmcgf boolean NOT NULL DEFAULT 't';
ALTER TABLE cfiptu ADD COLUMN j18_bicmarcasigilo  boolean NOT NULL DEFAULT 't';


SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL

delete from db_syscampodep where codcam = 1013275;
delete from db_syscampodep where codcam = 1013276;
delete from db_sysarqcamp  where codcam = 1013275 and codarq = 153;
delete from db_sysarqcamp  where codcam = 1013276 and codarq = 153;
delete from db_syscampo    where codcam = 1013275;
delete from db_syscampo    where codcam = 1013276;

ALTER TABLE cfiptu DROP COLUMN j18_permitectmcgf;
ALTER TABLE cfiptu DROP COLUMN j18_bicmarcasigilo;

SQL
        );
    }
	
}

