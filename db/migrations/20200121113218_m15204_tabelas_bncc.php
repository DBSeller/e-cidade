<?php

use Classes\PostgresMigration;

class M15204TabelasBncc extends PostgresMigration
{
    public function up()
    {
        $this->disciplinasBNCC();
        $this->etapasBNCC();
    }

    public function down()
    {
        $this->execute('truncate escola.bnccdisciplinas cascade;');
        $this->execute('truncate escola.bnccetapas cascade;');
    }

    private function disciplinasBNCC()
    {
        $this->execute("
            insert into escola.bnccdisciplinas
            values  (1, 'Traços, sons, cores e formas', 'TS', null, 'EI'),
                (2, 'O eu, o outro e o nós','EO', null, 'EI'),
                (3, 'Corpo, gestos e movimentos', 'CG', null, 'EI'),
                (4, 'Escuta, fala, pensamento e imaginação', 'EF', null, 'EI'),
                (5, 'Espaços, tempos, quantidades, relações e transformações', 'ET', null, 'EI'),
                (6, 'Língua Portuguesa', 'LP', 'Linguagens', 'EF'),
                (7, 'Arte', 'AR', 'Linguagens', 'EF'),
                (8, 'Educação Física', 'EF', 'Linguagens', 'EF'),
                (9, 'Língua Inglesa', 'LI', 'Linguagens', 'EF'),
                (10, 'Matemática', 'MA', 'Matemática', 'EF'),
                (11, 'Ciências', 'CI', 'Ciências da Natureza', 'EF'),
                (12, 'Geografia', 'GE', 'Ciências Humanas', 'EF'),
                (13, 'História', 'HI', 'Ciências Humanas', 'EF'),
                (14, 'Ensino Religioso', 'ER', 'Ensino Religioso', 'EF');
        ");

        $this->execute("select setval('bnccdisciplinas_ed149_sequencial_seq', 14) ");
    }

    public function etapasBNCC()
    {
        $this->execute("
            insert into escola.bnccetapas
            values (1, '1º', 'EF'),
               (2, '2º', 'EF'),
               (3, '3º', 'EF'),
               (4, '4º', 'EF'),
               (5, '5º', 'EF'),
               (6, '6º', 'EF'),
               (7, '7º', 'EF'),
               (8, '8º', 'EF'),
               (9, '9º', 'EF');
        ");

        $this->execute("select setval('bnccetapas_ed152_sequencial_seq', 9) ");
    }


}
