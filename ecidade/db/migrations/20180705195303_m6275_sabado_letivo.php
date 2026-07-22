<?php

use Classes\PostgresMigration;

class M6275SabadoLetivo extends PostgresMigration
{
    public function up()
    {
        $this->upMenu();
        $this->upDescricaoTipoVinculo();
    }

    public function down()
    {
        $this->downMenu();
        $this->downDescricaoTipoVinculo();
    }

    /**
     * Up Menu
     */
    private function upMenu()
    {
        $this->execute(<<<SQL
          insert into db_itensmenu (id_item, descricao, help, funcao, itemativo ,manutencao ,desctec ,libcliente) values (10542 , 'Agenda de Sábado Letivo' , 'Agenda de Sábado Letivo' , 'edu1_recuperacaodiasletivos001.php', '1' , '1' , 'Recuperação de dias letivos de uma turma.' , 'true' );
          insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1100873 ,10542 ,7 ,1100747 );
SQL
        );
    }

    /**
     * Down Menu
     */
    private function downMenu()
    {
        $this->execute(<<<SQL
          delete from db_itensmenu where id_item = 10542;
          delete from db_menu where id_item_filho = 10542;
SQL
        );
    }

    /**
     * Up descrição campo ed58_tipovinculo
     */
    private function upDescricaoTipoVinculo()
    {
        $this->execute(<<<SQL
          update db_syscampo 
          set descricao = 'Vinculo do professor com a disciplina:\n1 - Vincula o professor e discisplina com a Turma com base no turno inteiro \n2 - Vincula o professor e discisplina com a Turma com a grade de horário\n3 - Cadastro de recuperação de dias letivos.'
          where codcam = 19767 and nomecam = 'ed58_tipovinculo';
SQL
        );
    }

    /**
     * Down descrição campo ed58_tipovinculo
     */
    private function downDescricaoTipoVinculo()
    {
        $this->execute(<<<SQL
          update db_syscampo 
          set descricao = 'Como o professor foi vínculado a disciplina\n1 Vincular professor discisplina\n2 Criar grade de horário'
          where codcam = 19767 and nomecam = 'ed58_tipovinculo';
SQL
        );
    }
}
