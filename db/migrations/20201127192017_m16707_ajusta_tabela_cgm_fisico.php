<?php

use Classes\PostgresMigration;

class M16707AjustaTabelaCgmFisico extends PostgresMigration
{
    public function change()
    {
        $this->execute(<<<SQL
            DELETE FROM cgmfisico WHERE z04_numcgm IN (SELECT cgm.z01_numcgm
                                                         FROM cgm
                                                        INNER JOIN cgmfisico
                                                           ON (cgm.z01_numcgm = cgmfisico.z04_numcgm)
                                                        WHERE LENGTH(TRIM(z01_cgccpf)) = 14);
SQL
        );
    }
}
