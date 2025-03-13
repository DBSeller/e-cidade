<?php

use Classes\PostgresMigration;

class M11299RelatorioPrevisaoReceita extends PostgresMigration
{
    public function up()
    {
        $sql = "
            INSERT INTO db_itensmenu (id_item, descricao, help, funcao, manutencao, desctec)
            VALUES (10546, 'Conferência da Previsão da Receita LOA 2019', 'Conferência da Previsão da Receita LOA 2019',
                    'orc1_relatorio_previsao_receita.php', '1', 'Conferência da Previsão da Receita LOA 2019');
            
            INSERT INTO db_menu (id_item, id_item_filho, menusequencia, modulo) VALUES (30, 10546, 473, 116);
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            DELETE FROM db_menu WHERE id_item_filho = 10546 AND modulo = 116;
            DELETE FROM db_itensmenu WHERE id_item = 10546;
        ";

        $this->execute($sql);
    }
}
