<?php

use Classes\PostgresMigration;

class M10795AjusteCamposMedicamentos extends PostgresMigration
{
    function up() {
        $sSql = "
            update db_syscampo set conteudo = 'varchar(255)', tamanho = 255 where codcam = 21289;
            update db_syscampo set conteudo = 'varchar(255)', tamanho = 255 where codcam = 21290;
            update db_syscampo set conteudo = 'varchar(255)', tamanho = 255 where codcam = 21291;
            update db_syscampo set conteudo = 'varchar(255)', tamanho = 255 where codcam = 21292;
            
            alter table medicamentos alter column fa58_concentracao type varchar(255);
            alter table medicamentos alter column fa58_formafarmaceutica type varchar(255);
            alter table medicamentos alter column fa58_volume type varchar(255);
            alter table medicamentos alter column fa58_unidadefornecimento type varchar(255);            
        ";
        $this->execute($sSql);
    }

    function down(){
        $sSql = "
            update db_syscampo set conteudo = 'varchar(40)', tamanho = 40 where codcam = 21289;
            update db_syscampo set conteudo = 'varchar(40)', tamanho = 40 where codcam = 21290;
            update db_syscampo set conteudo = 'varchar(40)', tamanho = 40 where codcam = 21291;
            update db_syscampo set conteudo = 'varchar(40)', tamanho = 40 where codcam = 21292;
            
            alter table medicamentos alter column fa58_concentracao type varchar(40);
            alter table medicamentos alter column fa58_formafarmaceutica type varchar(40);
            alter table medicamentos alter column fa58_volume type varchar(40);
            alter table medicamentos alter column fa58_unidadefornecimento type varchar(40);
        ";
        $this->execute($sSql);
    }
}
