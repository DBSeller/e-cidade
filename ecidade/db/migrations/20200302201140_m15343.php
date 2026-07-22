<?php

use Classes\PostgresMigration;

class M15343 extends PostgresMigration
{
    public function up()
    {
        $this->dicionarioUp();
        $this->estruturaUp();
        $this->migracao();
        $this->adicionaAreaConhecimento();
    }

    public function down()
    {
        $this->estruturaDown();
        $this->dicionarioDown();
        $this->dropAreaConhecimento();
    }

    private function dicionarioUp()
    {
        $sql = "
        insert into db_syscampo
        values(1011077,'ed34_areaconhecimento','int4','Área de Conhecimento da disciplina na base','0', 'Área de Conhecimento',10,'t','f','f',1,'text','Área de Conhecimento'),
              (1011078,'ed59_areaconhecimento','int4','Área de Conhecimento da regência','0', 'Área de Conhecimento',10,'t','f','f',1,'text','Área de Conhecimento');

        insert into db_sysarqcamp
        values	(1010061,1011077,13,0),
                (1010084,1011078,16,0);

        insert into db_sysforkey
        values	(1010061,1011077,1,3258,0),
                (1010084,1011078,1,1010084,0);

        insert into db_sysindices
        values	(1008537,'basemps_areaconhecimento_in',1010061,'0'),
                (1008538,'regencia_areaconhecimento_in',1010084,'0');

        insert into db_syscadind
        values	(1008537,1011077,1),
                (1008538,1011078,1);
        ";
        $this->execute($sql);
    }
    private function dicionarioDown()
    {
        $sql = "
        delete from db_syscadind where codind in (1008537, 1008538);
        delete from db_sysindices where codind in (1008537, 1008538);
        delete from db_sysforkey where codarq in (1010061, 1010084);
        delete from db_sysarqcamp where codarq in (1010061, 1010084);
        delete from db_syscampo where codcam in (1011077, 1011078);
        ";

        $this->execute($sql);
    }

    private function estruturaUp()
    {
        $this->execute("
            alter table escola.basemps add column ed34_areaconhecimento integer;

            alter table escola.basemps
            add constraint basemps_areaconhecimento_fk foreign key (ed34_areaconhecimento)
            references areaconhecimento;

            alter table escola.regencia add column ed59_areaconhecimento integer;

            alter table escola.regencia
            add constraint regencia_areaconhecimento_fk foreign key (ed59_areaconhecimento)
            references areaconhecimento;

            create index basemps_areaconhecimento_in on escola.basemps(ed34_areaconhecimento);
            create index regencia_areaconhecimento_in on escola.regencia(ed59_areaconhecimento);
        ");
    }

    private function migracao()
    {
        $this->execute("
            update escola.basemps set ed34_areaconhecimento = ed232_areaconhecimento
              from disciplina
              join caddisciplina ON caddisciplina.ed232_i_codigo = disciplina.ed12_i_caddisciplina
            where disciplina.ed12_i_codigo = basemps.ed34_i_disciplina;
        ");

        $this->execute("
            update escola.regencia set ed59_areaconhecimento = ed232_areaconhecimento
              from disciplina
              join caddisciplina ON caddisciplina.ed232_i_codigo = disciplina.ed12_i_caddisciplina
            where disciplina.ed12_i_codigo = regencia.ed59_i_disciplina;
        ");
    }

    private function estruturaDown()
    {
        $this->execute("
            alter table escola.basemps drop column ed34_areaconhecimento;
            alter table escola.regencia drop column ed59_areaconhecimento;
        ");
    }

    private function adicionaAreaConhecimento()
    {
        $this->execute("insert into escola.areaconhecimento values (1005, 'Currículo Globalizado', 1);");
    }

    private function dropAreaConhecimento()
    {
        $this->execute("delete from escola.areaconhecimento where ed293_sequencial = 1005");
    }
}
