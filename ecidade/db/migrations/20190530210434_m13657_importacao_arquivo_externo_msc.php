<?php

use Classes\PostgresMigration;

class M13657ImportacaoArquivoExternoMsc extends PostgresMigration
{

    public function up()
    {
        $sqlMenu = <<<SQL
insert into db_itensmenu values( 228126, 'Importação de arquivo externo para MSC', 'Importação de arquivo externo para MSC', 'con4_mscimportacaoarquivoexterno001.php', '1', '1', 'Importação de arquivo externo para MSC', '1'	);
insert into db_itensfilho (id_item, codfilho) values(228126,1);
insert into db_menu values(4197,228126,15,209);
SQL;
        $this->execute($sqlMenu);
    }

    public function down()
    {
        $sqlMenu = <<<SQL
delete from db_itensfilho where id_item = 228126;
delete from db_menu       where id_item = 228126;
delete from db_itensmenu  where id_item = 228126;
SQL;
        $this->execute($sqlMenu);
    }

}
