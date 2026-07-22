<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20147TipoTreinamentoEsocial extends Migration
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

    public function upDicionario()
    {
        $sSql = <<<SQL
            insert into configuracoes.db_sysarquivo values (1010865, 'tipotreinamentoesocial', 'Tipo de Treinamento/Capacitações da tabela 28 do eSocial', 'h84', '2022-03-02', 'tipotreinamentoesocial', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1010865);
            insert into configuracoes.db_syscampo values(1013755,'h84_codigo','int4','Código da tabela 28 do esocial.','0', 'h84_codigo',4,'f','f','f',1,'text','');
            insert into configuracoes.db_syscampo values(1013756,'h84_descricao','varchar(255)','descricao do tipo de treinamento/capacitação da tabela 28 do esocial.','', 'h84_descricao',255,'f','t','f',0,'text','');
            delete from configuracoes.db_sysarqcamp where codarq = 1010865;
            insert into configuracoes.db_sysarqcamp values(1010865,1013755,1,0);
            insert into configuracoes.db_sysarqcamp values(1010865,1013756,2,0);
            update configuracoes.db_syscampo set nomecam = 'h84_descricao', conteudo = 'varchar(255)', descricao = 'descricao do tipo de treinamento/capacitação da tabela 28 do esocial.', valorinicial = '', rotulo = 'h84_descricao', nulo = 't', tamanho = 255, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = '' where codcam = 1013756;
            delete from configuracoes.db_syscampodep where codcam = 1013756;
            delete from configuracoes.db_syscampodef where codcam = 1013756;
            delete from configuracoes.db_sysarqcamp where codarq = 1010865;
            insert into configuracoes.db_sysarqcamp values(1010865,1013755,1,0);
            insert into configuracoes.db_sysarqcamp values(1010865,1013756,2,0);
            delete from configuracoes.db_sysprikey where codarq = 1010865;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010865,1013755,1,1013755);
            update configuracoes.db_syscampo set nomecam = 'h84_codigo', conteudo = 'int4', descricao = 'Código da tabela 28 do esocial.', valorinicial = '0', rotulo = 'h84_codigo', nulo = 'f', tamanho = 4, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'h84_codigo' where codcam = 1013755;
            delete from configuracoes.db_syscampodep where codcam = 1013755;
            delete from configuracoes.db_syscampodef where codcam = 1013755;
            update configuracoes.db_syscampo set nomecam = 'h84_descricao', conteudo = 'varchar(255)', descricao = 'descricao do tipo de treinamento/capacitação da tabela 28 do esocial.', valorinicial = '', rotulo = 'h84_descricao', nulo = 't', tamanho = 255, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'h84_descricao' where codcam = 1013756;
            delete from configuracoes.db_syscampodep where codcam = 1013756;
            delete from configuracoes.db_syscampodef where codcam = 1013756;
            update configuracoes.db_syscampo set nomecam = 'h84_codigo', conteudo = 'int4', descricao = 'Código do treinamento/capacitacao da tabela 28 do esocial.', valorinicial = '0', rotulo = 'código do treinamento', nulo = 'f', tamanho = 4, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'código do treinamento' where codcam = 1013755;
            delete from configuracoes.db_syscampodep where codcam = 1013755;
            delete from configuracoes.db_syscampodef where codcam = 1013755;
            update configuracoes.db_syscampo set nomecam = 'h84_descricao', conteudo = 'varchar(255)', descricao = 'descricao do tipo de treinamento/capacitação da tabela 28 do esocial.', valorinicial = '', rotulo = 'descricao do treinamento', nulo = 't', tamanho = 255, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'descricao do treinamento' where codcam = 1013756;
            delete from configuracoes.db_syscampodep where codcam = 1013756;
            delete from configuracoes.db_syscampodef where codcam = 1013756;

            -- adicionando campo na tabela tabcurritipo que vai referenciar a tabela tipotreinamentoesocial
            insert into db_syscampo values(1013768,'h02_tipotreinamento','int4','código do treinamento, capacitação, exercício simulado ou outra anotação referente a tabela 28 do esocial.','0', 'tipo de treinamento do esocial',4,'t','f','f',1,'text','tipo de treinamento do esocial');
            insert into db_sysarqcamp values(594,1013768,4,0);
            insert into db_sysforkey values(594,1013768,1,1010865,0);

SQL;
            DB::connection()->getPdo()->exec($sSql);
    }

    public function downDicionario(){
        $sSql = <<<SQL
            delete from configuracoes.db_sysforkey where codcam = 1013768;
            delete from configuracoes.db_sysarqcamp where codcam = 1013768;
            delete from configuracoes.db_syscampo where codcam = 1013768;


            delete from configuracoes.db_sysarqcamp where codcam = 1013755;
            delete from configuracoes.db_syscampo where codcam = 1013755;
            delete from configuracoes.db_sysarqcamp where codcam = 1013756;
            delete from configuracoes.db_syscampo where codcam = 1013756;
            delete from configuracoes.db_sysprikey where codarq = 1010865;
            delete from configuracoes.db_sysarqmod where codarq = 1010865;
            delete from configuracoes.db_sysarquivo where codarq = 1010865;
SQL;
            DB::connection()->getPdo()->exec($sSql);
    }

    public function upEstrutura()
    {
     $sSql = <<<SQL
            create table recursoshumanos.tipotreinamentoesocial(
                h84_codigo int4 PRIMARY KEY,
                h84_descricao varchar(255)
            );
            insert into recursoshumanos.tipotreinamentoesocial values(3701,'Treinamento antes do primeiro embarque');
            insert into recursoshumanos.tipotreinamentoesocial values(3702,'Treinamento antes do primeiro embarque - Reciclagem');
            insert into recursoshumanos.tipotreinamentoesocial values(3703,'Treinamento eventual');
            insert into recursoshumanos.tipotreinamentoesocial values(3704,'Treinamento básico');
            insert into recursoshumanos.tipotreinamentoesocial values(3705,'Treinamento básico - Reciclagem');
            insert into recursoshumanos.tipotreinamentoesocial values(3706,'Treinamento avançado');
            insert into recursoshumanos.tipotreinamentoesocial values(3707,'Treinamento avançado - Reciclagem');
            insert into recursoshumanos.tipotreinamentoesocial values(3708,'Treinamento específico - Empregado integrante da brigada de incêndio');
            insert into recursoshumanos.tipotreinamentoesocial values(3709,'Treinamento específico - Riscos radiológicos da plataforma');
            insert into recursoshumanos.tipotreinamentoesocial values(3710,'Treinamento específico - Empregado integrante de equipe de resposta a emergências');
            insert into recursoshumanos.tipotreinamentoesocial values(3711,'Treinamento - Sinaleiro (reciclagem)');
            insert into recursoshumanos.tipotreinamentoesocial values(3712,'Treinamento - Operador de guindaste (reciclagem)');
            insert into recursoshumanos.tipotreinamentoesocial values(3713,'Treinamento - Curso Básico para Manipulador de Alimentos');
            insert into recursoshumanos.tipotreinamentoesocial values(3714,'Treinamento - Curso complementar para serviços em instalações elétricas em alta tensão');
            insert into recursoshumanos.tipotreinamentoesocial values(3715,'Treinamento - Curso básico de segurança em operações de movimentação de cargas e transporte de pessoas');
            insert into recursoshumanos.tipotreinamentoesocial values(3716,'Treinamento - Curso complementar para operadores de guindastes');
            insert into recursoshumanos.tipotreinamentoesocial values(3717,'Treinamento - Curso para indivíduos ocupacionalmente expostos à radiação ionizante');
            insert into recursoshumanos.tipotreinamentoesocial values(3718,'Treinamento - Procedimento operacional - Acendimento da chama piloto');
            insert into recursoshumanos.tipotreinamentoesocial values(1006,'Autorização para trabalhar em instalações elétricas');
            insert into recursoshumanos.tipotreinamentoesocial values(1207,'Operação e realização de intervenções em máquinas');
            insert into recursoshumanos.tipotreinamentoesocial values(3179,'Operador do equipamento de guindar');
            
            alter table recursoshumanos.tabcurritipo add column h02_tipotreinamento int4;
            alter table recursoshumanos.tabcurritipo add constraint FK_tipotreinamento foreign key (h02_tipotreinamento) references recursoshumanos.tipotreinamentoesocial (h84_codigo)
SQL;
            DB::connection()->getPdo()->exec($sSql);
    }

    public function downEstrutura()
    {
        $sSql = <<<SQL
            alter table recursoshumanos.tabcurritipo drop constraint  FK_tipotreinamento;
            alter table recursoshumanos.tabcurritipo drop column h02_tipotreinamento;
            DROP TABLE recursoshumanos.tipotreinamentoesocial;
            
SQL;
    DB::connection()->getPdo()->exec($sSql);
    }
}
