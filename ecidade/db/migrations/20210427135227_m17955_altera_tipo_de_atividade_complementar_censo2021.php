<?php

use Classes\PostgresMigration;

class M17955AlteraTipoDeAtividadeComplementarCenso2021 extends PostgresMigration
{

    public function up()
    {
        $this->inclusaoUp();
        $this->alteracaoUp();
        $this->exclusaoUp();
    }

    public function down()
    {
        $this->exclusaoDown();
        $this->alteracaoDown();
        $this->inclusaoDown();
    }

    private function inclusaoUp()
    {
        $sql = "insert into censoativcompl values (41001,41,upper('Direitos da criança e do adolescente'), False);
        insert into censoativcompl values (41014,41,upper('Respeito e valorização do idoso'), False);
        insert into censoativcompl values (41015,41,upper('Educação para o trânsito'), False);
        insert into censoativcompl values (13302,133,upper('Educação alimentar e nutricional'), False);
        insert into censoativcompl values (13303,133,upper('Reciclagem'), False);
        insert into censoativcompl values (13304,133,upper('Reflorestamento - Plantio de árvores'), False);
        insert into censoativcompl values (13305,133,upper('Consumo consciente de água'), False);
        insert into censoativcompl values (18101,181,upper('Desenvolvimento de competências socioemocionais'), False);
        insert into censoativcompl values (18102,181,upper('Atividades de autoconhecimento, identificação e gestão de sentimento'), False);
        insert into censoativcompl values (18103,181,upper('Atividades de empatia e gestão de conflitos'), False);";

        $this->execute($sql);
    }

    private function exclusaoUp()
    {
        $sql = "delete from censoativcompl where ed133_i_codigo in (71001,51004,13105);";

        $this->execute($sql);
    }

    private function inclusaoDown()
    {
       $sql = "delete from censoativcompl where ed133_i_codigo in (41001,41014,41015,13302,13303,13304,13305,18101,18102,18103);";
       $this->execute($sql);
    }

    private function alteracaoUp()
    {
        $sql = "
        update turmaacativ set ed267_i_censoativcompl = 13302 where ed267_i_censoativcompl = 71001;
        update turmaacativ set ed267_i_censoativcompl = 13303 where ed267_i_censoativcompl = 51004;
        update turmaacativ set ed267_i_censoativcompl = 13305 where ed267_i_censoativcompl = 13105;
        ";

        $this->execute($sql);
    }

    private function alteracaoDown()
    {
        $sql = "
        update turmaacativ set ed267_i_censoativcompl = 13105 where ed267_i_censoativcompl = 13305;
        update turmaacativ set ed267_i_censoativcompl = 51004 where ed267_i_censoativcompl = 13303;
        update turmaacativ set ed267_i_censoativcompl = 71001 where ed267_i_censoativcompl = 13302;
        ";

        $this->execute($sql);
    }

    private function exclusaoDown()
    {
        $sql = "insert into censoativcompl values (51004,51,'RECICLAGEM',False);
        insert into censoativcompl values (71001,71,'PROMOCAO DA ALIMENTACAO SAUDAVEL',False);
        insert into censoativcompl values (13105,131,'USO EFICIENTE DE AGUA E ENERGIA',False);";

        $this->execute($sql);
    }

}
