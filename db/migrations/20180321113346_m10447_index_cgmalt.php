<?php

use Classes\PostgresMigration;

/**
 * Class M10447IndexCgmalt
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class M10447IndexCgmalt extends PostgresMigration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute('COMMIT;');
        $this->execute('CREATE INDEX CONCURRENTLY cgmalt_numcgm_data_alt_in ON protocolo.cgmalt (z05_numcgm, z05_data_alt)');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('DROP INDEX cgmalt_numcgm_data_alt_in');
    }
}
