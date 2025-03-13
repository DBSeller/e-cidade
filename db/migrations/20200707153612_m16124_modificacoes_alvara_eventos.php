<?php

use Classes\PostgresMigration;

class M16124ModificacoesAlvaraEventos extends PostgresMigration
{
    public function up()
    {
        $this->upInsertGrupoAlvara();
        $this->upMenuImpressaoAlvara();
        // $this->upRemoverRestricaoAlvaraEvento();
    }

    public function down()
    {
        $this->downInsertGrupoAlvara();
        $this->downMenuImpressaoAlvara();
    }

    protected function upInsertGrupoAlvara()
    {
        $this->execute(<<<SQL

            INSERT INTO issgrupotipoalvara(q97_sequencial, q97_descricao, q97_isstipogrupoalvara) values (7, 'EVENTO', 2);

SQL
        );
    }

    protected function downInsertGrupoAlvara()
    {
        $this->execute(<<<SQL

            delete from issgrupotipoalvara where q97_sequencial = 7 and  q97_descricao = 'EVENTO' and q97_isstipogrupoalvara = 2;

SQL
        );
    }

    protected function upMenuImpressaoAlvara()
    {
        $this->execute(<<<SQL

            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228274 ,'Imprimir Alvará' ,'Impressão de alvará de eventos' ,'iss3_impressaoalvaraevento001.php' ,'1' ,'1' ,'Rotina para geração de alvara de eventos' ,'true' );
            delete from db_menu where id_item_filho = 228274 AND modulo = 40;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228267 ,228274 ,3 ,40 );

SQL
            );
    }

    protected function downMenuImpressaoAlvara()
    {
        $this->execute(<<<SQL

            delete from db_menu where id_item_filho = 228274 AND modulo = 40;
            delete from db_itensmenu where id_item = 228274;

SQL
            );
    }
}
