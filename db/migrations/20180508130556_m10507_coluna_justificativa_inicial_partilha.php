<?php

use Classes\PostgresMigration;

/**
 * Class M10507ColunaJustificativaInicialPartilha
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class M10507ColunaJustificativaInicialPartilha extends PostgresMigration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $sql = <<<SQL
            CREATE OR REPLACE FUNCTION f_adiciona_coluna()
              RETURNS VOID AS $$
            BEGIN
               IF EXISTS (SELECT 1 FROM pg_attribute
                          WHERE  attrelid = 'juridico.inicialpartilha'::REGCLASS
                          AND    attname = 'v35_justificativa'
                          AND    NOT attisdropped) THEN
               ELSE
                  ALTER TABLE inicialpartilha ADD COLUMN v35_justificativa TEXT;
                  INSERT INTO db_syscampo VALUES (
                      1009630, 
                      'v35_justificativa', 
                      'text', 
                      'Justificativa para isencao', 
                      NULL, 
                      'Justificativa', 
                      1, 
                      TRUE, 
                      TRUE, 
                      FALSE, 
                      0, 
                      'text', 
                      'Justificativa'
                  );
                  INSERT INTO db_sysarqcamp VALUES (1010234, 1009630, 8, 0);
               END IF;
            END;
            $$ LANGUAGE plpgsql;
            
            DO $$ BEGIN
                PERFORM f_adiciona_coluna();
            END $$;

            DROP FUNCTION IF EXISTS f_adiciona_coluna();
SQL;

        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->table('inicialpartilha', array(
            'schema' => 'juridico'
        ))
            ->removeColumn('v35_justificativa')
            ->save();

        $sql = "
            DELETE FROM db_sysarqcamp WHERE codarq = 1010234 and codcam = 1009630;
            DELETE FROM db_syscampo WHERE codcam = 1009630;
        ";

        $this->execute($sql);
    }
}
