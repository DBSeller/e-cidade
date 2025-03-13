<?php

use Classes\PostgresMigration;

class HistoricoTipoProcesso extends PostgresMigration
{
    public function up()
    {
        $this->upDicionarioDeDados();
        $this->upHistoricoTipoProcesso();
        $this->upPopularHistoricoTipoProcesso();
    }

    public function down()
    {
        $this->downDicionarioDeDados();
        $this->downHistoricoTipoProcesso();
    }

    private function upDicionarioDeDados()
    {
        $this->execute(<<<SQL
            insert into db_sysarquivo values (1010628, 'historico_tipo_processo', 'Registra as alterações no tipo do processo manual,eletrônico e ouvidoria.', 'p112', '2020-11-03', 'Histórico do tipo de processo ', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (4,1010628);
            insert into db_syscampo values(1011875,'p112_usuario','int8','Identificação do usuário','0', 'Usuário',20,'f','f','f',1,'text','Usuário');
            insert into db_syscampo values(1011876,'p112_instituicao','int8','Instituição referente ao usuário que efetuou mudança no tipo do processo.','0', 'instituicao',20,'f','f','f',1,'text','instituicao');
            insert into db_syscampo values(1011877,'p112_departamento','int8','Departamento referente ao usuário que solicitou mudança no tipo de processo.','0', 'Departamento',20,'f','f','f',1,'text','Departamento');
            insert into db_syscampo values(1011878,'p112_data_registro','date','Data em qual foi efetuado a mudança no tipo de processo','null', 'Data de Registro',10,'f','f','f',1,'text','Data de Registro');
            insert into db_syscampo values(1011880,'p112_tipoprocesso','int4','Tipo de processo ','0', 'Tipo de processo',10,'f','f','f',1,'text','Tipo de processo');
            insert into db_syscampo values(1011881,'p112_codigoprocesso','int8','Codigo Processo','0', 'Codigo Processo ',20,'f','f','f',1,'text','Codigo Processo');
            insert into db_syscampo values(1011879,'p112_sequencial','int8','Sequencial','0', 'Sequencial',20,'f','f','t',1,'text','Sequencial');
            insert into db_sysarqcamp values(1010628,1011879,1,0);
            insert into db_sysarqcamp values(1010628,1011878,2,0);
            insert into db_sysarqcamp values(1010628,1011877,3,0);
            insert into db_sysarqcamp values(1010628,1011876,4,0);
            insert into db_sysarqcamp values(1010628,1011875,5,0);
            insert into db_sysarqcamp values(1010628,1011880,6,0);
            insert into db_sysarqcamp values(1010628,1011881,7,0);
SQL
        );

    }

    private function downDicionarioDeDados()
    {

        $this->execute(<<<SQL
         DELETE FROM db_sysarqcamp WHERE codarq = 1010628;
         DELETE FROM db_syscampo WHERE codcam  IN (1011875,1011876,1011877,1011878,1011879);
         DELETE FROM db_sysarqmod WHERE codarq = 1010628;
         DELETE FROM db_sysarquivo WHERE codarq = 1010628;
SQL
        );

    }

    private function upHistoricoTipoProcesso()
    {

        $this->execute(<<<SQL
        CREATE TABLE IF NOT EXISTS protocolo.historico_tipo_processo(
           p112_sequencial SERIAL PRIMARY KEY,
           p112_usuario int8,
           p112_instituicao int8,
           p112_departamento int8,
           p112_tipoprocesso int4,
           p112_codigoprocesso int8,
           p112_data_registro timestamp,
           FOREIGN KEY (p112_usuario) REFERENCES configuracoes.db_usuarios (id_usuario),
           FOREIGN KEY (p112_instituicao) REFERENCES configuracoes.db_config  (codigo),
           FOREIGN KEY (p112_tipoprocesso) REFERENCES  protocolo.tipoprocesso  (p109_sequencial),
           FOREIGN KEY (p112_codigoprocesso) REFERENCES protocolo.protprocesso  (p58_codproc)
        );
SQL
        );

    }

    private function downHistoricoTipoProcesso()
    {
        $this->execute("DROP TABLE IF EXISTS protocolo.historico_tipo_processo;");
    }

    private function upPopularHistoricoTipoProcesso()
    {
        $this->execute(<<<SQL
        INSERT INTO protocolo.historico_tipo_processo (p112_codigoprocesso, p112_usuario, p112_departamento, p112_tipoprocesso, p112_instituicao, p112_data_registro )
        SELECT DISTINCT
        p58_codproc,
        p58_id_usuario,
        p58_coddepto,
        p58_tipoprocesso,
        p58_instit,
        to_timestamp(CONCAT(p58_dtproc, ' ', p58_hora),'YYYY-MM-DD HH24:MI') AS data_registro
        FROM protocolo.protprocesso
SQL
        );
    }

}
