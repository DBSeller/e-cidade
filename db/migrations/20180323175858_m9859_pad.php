<?php

use Classes\PostgresMigration;

/**
 * Class M9859Pad
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class M9859Pad extends PostgresMigration
{
    public function up()
    {
        $sql = "DROP TABLE IF EXISTS w_tipoempresapad;
                CREATE TABLE w_tipoempresapad AS
                SELECT
                     z01_numcgm,
                     z01_nome,
                     CASE
                       WHEN length(z01_cgccpf) = 11
                         THEN 31::INTEGER
                       ELSE 36::INTEGER
                     END AS tipoempresa
                FROM cgm
                 LEFT JOIN cgmtipoempresa ON z03_numcgm = z01_numcgm
                WHERE z03_numcgm IS NULL
                 AND z01_cgccpf != '';
                                
                INSERT INTO cgmtipoempresa
                SELECT nextval('cgmtipoempresa_z03_sequencial_seq'),
                      z01_numcgm,
                      tipoempresa
                 FROM w_tipoempresapad;";

        $this->execute($sql);

        $this->execute('DELETE FROM conplanocontacorrente WHERE c18_anousu >= 2017 AND c18_codcon = (SELECT c60_codcon FROM conplano WHERE c60_estrut LIKE \'8211101%\' LIMIT 1)');
    }

    public function down()
    {
    }
}
