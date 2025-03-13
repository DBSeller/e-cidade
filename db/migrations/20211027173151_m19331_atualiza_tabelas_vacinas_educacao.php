<?php

use Classes\PostgresMigration;

class M19331AtualizaTabelasVacinasEducacao extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            INSERT INTO escola.doses (ed180_codigo, ed180_descricao) VALUES (8, 'Dose Adicional');
            INSERT INTO escola.vacinas_doses (ed179_codigo, ed179_vacina, ed179_dose) VALUES (25, 6, 6);
            INSERT INTO escola.vacinas_doses (ed179_codigo, ed179_vacina, ed179_dose) VALUES (26, 6, 8);
            INSERT INTO escola.vacinas_doses (ed179_codigo, ed179_vacina, ed179_dose) VALUES (27, 8, 6);
            INSERT INTO escola.vacinas_doses (ed179_codigo, ed179_vacina, ed179_dose) VALUES (28, 8, 8);
            INSERT INTO escola.vacinas_doses (ed179_codigo, ed179_vacina, ed179_dose) VALUES (29, 9, 6);
            INSERT INTO escola.vacinas_doses (ed179_codigo, ed179_vacina, ed179_dose) VALUES (30, 9, 8);
            SELECT setval('doses_ed180_codigo_seq', 8);
            SELECT setval('vacinas_doses_ed179_codigo_seq', 30);
SQL
        );
    }
    public function down()
    {
        $this->execute(<<<SQL
            delete from escola.vacinas_doses where ed179_codigo > 24 and ed179_codigo < 31;
            delete from escola.doses where ed180_codigo = 8;
SQL
    );
    }
}
