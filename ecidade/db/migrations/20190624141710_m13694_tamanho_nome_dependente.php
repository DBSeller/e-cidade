<?php

use Classes\PostgresMigration;

/**
 * Class M13694TamanhoNomeDependente
 */
class M13694TamanhoNomeDependente extends PostgresMigration
{
    /**
     *
     */
    public function up()
    {
        $sql = "
            ALTER TABLE pessoal.rhdepend
                ALTER COLUMN rh31_nome TYPE VARCHAR(70) USING rh31_nome::VARCHAR(70);
                
            UPDATE db_syscampo
            SET conteudo = 'varchar(70)',
                tamanho  = 70
            WHERE codcam = 7151;
        ";

        $this->execute($sql);
    }

    /**
     *
     */
    public function down()
    {
        $sql = "
            UPDATE db_syscampo
            SET conteudo = 'varchar(40)',
                tamanho  = 40
            WHERE codcam = 7151;        
        
            ALTER TABLE pessoal.rhdepend
                ALTER COLUMN rh31_nome TYPE VARCHAR(40) USING rh31_nome::VARCHAR(40);        
        ";

        $this->execute($sql);
    }
}
