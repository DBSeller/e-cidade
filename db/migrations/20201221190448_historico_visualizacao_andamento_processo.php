<?php

use Classes\PostgresMigration;

class HistoricoVisualizacaoAndamentoProcesso extends PostgresMigration
{


    public function up(){

        $this->dicionarioDeDadosUP();
        $this->criarTabelaUP();

    }

    public function down(){
       $this->dicionarioDeDadosDOWN();
       $this->criarTabelaDOWN();
    }

    public function dicionarioDeDadosUP(){

        $sql = <<<SQL
            insert into db_sysarquivo values (1010640, 'historicovisualizacaoprocandam', 'Histórico de visualização de mensagens referente ao andamento do processo', 'p113', '2020-12-21', 'historicovisualizacaoprocandam', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (4,1010640);
            insert into db_syscampo values(1011932,' p113_sequencial','int8','Identificação da visualização','0', 'p113_sequencial',20,'f','f','f',1,'text','p113_sequencial');
            insert into db_syscampo values(1011933,'p113_usuario_id','int8','Identificação do usuário','0', 'p113_usuario_id',20,'f','f','f',1,'text','p113_usuario_id');
            insert into db_syscampo values(1011934,'p113_instituicao_id','int8','Identificação da instituição ','0', 'p113_instituicao_id',20,'f','f','f',1,'text','p113_instituicao_id');
            insert into db_syscampo values(1011935,'p113_departamento_id','int8','Identificação do departamento','0', 'p113_departamento_id',20,'f','f','f',1,'text','p113_departamento_id');
            insert into db_syscampo values(1011936,'p113_procandamint_id','int8','procandamint','0', 'p113_procandamint_id',20,'f','f','f',1,'text','p113_procandamint_id');
            insert into db_syscampo values(1011937,'p113_data_registro','date','Data de Registro','null', 'p113_data_registro',14,'f','f','f',1,'text','p113_data_registro');
            insert into db_sysarqcamp values(1010640,1011937,1,0);
            insert into db_sysarqcamp values(1010640,1011936,2,0);
            insert into db_sysarqcamp values(1010640,1011935,3,0);
            insert into db_sysarqcamp values(1010640,1011934,4,0);
            insert into db_sysarqcamp values(1010640,1011933,5,0);
SQL;

        $this->execute($sql);

    }

    public function dicionarioDeDadosDOWN(){

        $sql = <<<SQL
            DELETE FROM db_sysarqcamp WHERE  codcam IN (1011937,1011936,1011935,1011934,1011934,1011933);
            DELETE FROM db_sysarqcamp WHERE  codcam IN (1011932,1011933,1011934,1011935,1011936,1011937);
            DELETE FROM db_syscampo WHERE  codcam IN (1011932,1011933,1011934,1011935,1011936,1011937);
            DELETE FROM db_sysarquivo WHERE  codarq  = 1010640;
SQL;

        $this->execute($sql);

    }

    public function criarTabelaUP(){
        $sql = <<< SQL
            CREATE TABLE protocolo.historicovisualizacaoprocandam(
                p113_sequencial serial
                , p113_usuario_id bigint
                , p113_instituicao_id bigint
                , p113_departamento_id bigint
                , p113_procandamint_id INT
                , p113_data_registro timestamp
                , PRIMARY KEY(p113_sequencial)
                , CONSTRAINT fk_p113_usuario_id FOREIGN KEY(p113_usuario_id) REFERENCES db_usuarios(id_usuario)
                , CONSTRAINT fk_p113_instituicao_id FOREIGN KEY(p113_instituicao_id) REFERENCES db_config(codigo)
                , CONSTRAINT fk_p113_departamento_id FOREIGN KEY(p113_departamento_id) REFERENCES db_depart(coddepto)
            );

SQL;

        $this->execute($sql);

    }

    public function criarTabelaDOWN(){
        $this->execute("DROP TABLE  IF EXISTS  protocolo.historicovisualizacaoprocandam");
    }




}
