<?php

use Classes\PostgresMigration;

class M16534DesabilitarMenusMelhoriasPortoVelho extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL

            UPDATE db_itensmenu SET libcliente = 'false' WHERE id_item IN (
                -- M15279
                228244,
                228245,
                228250,
                228262,
                228263,
                -- M16123

                -- M16124
                    -- alvara_eventos_menus
                228266,
                228267,
                228268,
                    -- modificacoes_alvara_eventos
                228274,
                    -- menus_inscricao_carros
                228275,
                228276,
                228277,
                228278,
                228279,
                228280,
                228281,
                228282,
                228283,
                228284,
                228290,
                228291,

                -- M16127
                228285,
                228286,
                228287,
                228288,
                228289,
                228303,

                -- M16072
                228271,
                228272,
                228273,

                -- M16122
                228300,
                228301,
                228302
            );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL

            UPDATE db_itensmenu SET libcliente = 'true' WHERE id_item IN (
                -- M15279
                228244,
                228245,
                228250,
                228262,
                228263,
                -- M16123

                -- M16124
                    -- alvara_eventos_menus
                228266,
                228267,
                228268,
                    -- modificacoes_alvara_eventos
                228274,
                    -- menus_inscricao_carros
                228275,
                228276,
                228277,
                228278,
                228279,
                228280,
                228281,
                228282,
                228283,
                228284,
                228290,
                228291,

                -- M16127
                228285,
                228286,
                228287,
                228288,
                228289,
                228303,

                -- M16072
                228271,
                228272,
                228273,

                -- M16122
                228300,
                228301,
                228302
            );
SQL
        );
    }
}
