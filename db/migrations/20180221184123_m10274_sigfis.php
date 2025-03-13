<?php

use Classes\PostgresMigration;

class M10274Sigfis extends PostgresMigration
{
    public function up() {

        $this->updateCamposEspRec();
        $this->updateCamposPrevRec();
        $this->updateCamposRecLanc();
        $this->updateCamposAPrevRec();
    }

    public function down() {

        $this->execute(
<<<SQL_DOWN_ESPREC
DELETE FROM db_layoutcampos WHERE db52_layoutlinha = 403;
INSERT INTO db_layoutcampos VALUES (6426, 403, 'cd_ItemReceitaGestor', 'ITEM DE RECEITA', 14, 5, '', 8, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6427, 403, 'de_ItemReceita', 'Descrição', 14, 13, '', 50, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6428, 403, 'cd_ItemReceita', 'Item de receita TCE', 14, 63, '', 8, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6429, 403, 'dt_ano', 'ANO', 14, 71, '', 4, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6425, 403, 'cd_Unidade', 'UNIDADE GESTORA', 14, 1, '', 4, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6430, 403, 'Cd_receblanc', 'RECEBE LANÇAMENTO', 14, 75, '', 1, false, true, 'd', '', 0);
SQL_DOWN_ESPREC
        );

        $this->execute(
            <<<SQL_DOWN_PREVREC
delete from db_layoutcampos where db52_layoutlinha = 406;
insert into db_layoutcampos values (6442, 406, 'dt_Ano', 'ANO', 14, 1, '', 4, false, true, 'd', '', 0);
insert into db_layoutcampos values (6443, 406, 'Cd_Unidade', 'UNIDADE GESTORA', 14, 5, '', 4, false, true, 'd', '', 0);
insert into db_layoutcampos values (6444, 406, 'Cd_ItemReceita', 'ITEM RECEITA', 14, 9, '', 8, false, true, 'd', '', 0);
insert into db_layoutcampos values (6445, 406, 'vl_Receita', 'VALOR', 14, 17, '', 16, false, true, 'd', '', 0);
SQL_DOWN_PREVREC
        );

        $this->execute(
            <<<SQL_DOWN_RECLANC
delete from db_layoutcampos where db52_layoutlinha = 409;
INSERT INTO db_layoutcampos VALUES (6486, 409, 'dt_AnoMes', 'Competência', 14, 14, '', 6, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6487, 409, 'vl_Lancamento', 'Valor lançamento', 14, 20, '', 16, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6483, 409, 'cd_unidade', 'UNIDADE GESTORA', 14, 1, '', 4, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6484, 409, 'tp_AtualizacaoReceitaLancada', 'Tipo de lançamento', 14, 5, '', 1, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6485, 409, 'cd_ItemReceita', 'Item da receita', 14, 6, '', 8, false, true, 'd', '', 0);
SQL_DOWN_RECLANC
            );
        $this->execute(
            <<<SQL_DOWN_APREVREC
delete from db_layoutcampos where db52_layoutlinha = 410;
INSERT INTO db_layoutcampos VALUES (6488, 410, 'dt_Ano', 'ANO DA ATUALIZAÇÃO', 14, 1, '', 4, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6489, 410, 'cd_Unidade', 'UNIDADE GESTORA', 14, 5, '', 4, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6490, 410, 'cd_ItemReceita', 'Item da Receita', 14, 9, '', 8, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6491, 410, 'tp_Atual_Receita', 'Tipo de atualização', 14, 17, '', 1, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6492, 410, 'vl_Receita', 'Valor da receita', 14, 18, '', 16, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6493, 410, 'dt_AnoMes', 'Competência', 14, 34, '', 6, false, true, 'd', '', 0);
SQL_DOWN_APREVREC
            );

    }

    private function updateCamposEspRec() {
        $this->execute(
<<<SQL_UP_ESPREC
DELETE FROM db_layoutcampos WHERE db52_layoutlinha = 403;
INSERT INTO db_layoutcampos VALUES (6426, 403, 'cd_ItemReceitaGestor', 'ITEM DE RECEITA', 14, 5, '', 13, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6425, 403, 'cd_Unidade', 'UNIDADE GESTORA', 14, 1, '', 4, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6430, 403, 'Cd_receblanc', 'RECEBE LANÇAMENTO', 14, 85, '', 1, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6427, 403, 'de_ItemReceita', 'Descrição', 14, 18, '', 50, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6428, 403, 'cd_ItemReceita', 'Item de receita TCE', 14, 68, '', 13, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6429, 403, 'dt_ano', 'ANO', 14, 81, '', 4, false, true, 'd', '', 0);
SQL_UP_ESPREC

        );
    }

    private function updateCamposPrevRec() {

        $this->execute(
<<<SQL_UP_PREVREC
delete from db_layoutcampos where db52_layoutlinha = 406;
insert into db_layoutcampos values (6442, 406, 'dt_Ano', 'ANO', 14, 1, '', 4, false, true, 'd', '', 0);
insert into db_layoutcampos values (6443, 406, 'Cd_Unidade', 'UNIDADE GESTORA', 14, 5, '', 4, false, true, 'd', '', 0);
insert into db_layoutcampos values (6444, 406, 'Cd_ItemReceita', 'ITEM RECEITA', 14, 9, '', 13, false, true, 'd', '', 0);
insert into db_layoutcampos values (6445, 406, 'vl_Receita', 'VALOR', 14, 22, '', 16, false, true, 'd', '', 0);
SQL_UP_PREVREC

        );
    }

    private function updateCamposRecLanc() {
        $this->execute(
<<<SQL_UP_RECLANC
delete from db_layoutcampos where db52_layoutlinha = 409;
INSERT INTO db_layoutcampos VALUES (6483, 409, 'cd_unidade', 'UNIDADE GESTORA', 14, 1, '', 4, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6484, 409, 'tp_AtualizacaoReceitaLancada', 'Tipo de lançamento', 14, 5, '', 1, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6485, 409, 'cd_ItemReceita', 'Item da receita', 14, 6, '', 13, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6486, 409, 'dt_AnoMes', 'Competência', 14, 19, '', 6, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6487, 409, 'vl_Lancamento', 'Valor lançamento', 14, 25, '', 16, false, true, 'd', '', 0);
SQL_UP_RECLANC
);
    }

    private function updateCamposAPrevRec(){
        $this->execute(
<<<SQL_UP_APREVREC
delete from db_layoutcampos where db52_layoutlinha = 410;
INSERT INTO db_layoutcampos VALUES (6488, 410, 'dt_Ano', 'ANO DA ATUALIZAÇÃO', 14, 1, '', 4, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6489, 410, 'cd_Unidade', 'UNIDADE GESTORA', 14, 5, '', 4, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6490, 410, 'cd_ItemReceita', 'Item da Receita', 14, 9, '', 13, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6491, 410, 'tp_Atual_Receita', 'Tipo de atualização', 14, 22, '', 1, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6492, 410, 'vl_Receita', 'Valor da receita', 14, 23, '', 16, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (6493, 410, 'dt_AnoMes', 'Competência', 14, 39, '', 6, false, true, 'd', '', 0);
SQL_UP_APREVREC
);
    }
}
