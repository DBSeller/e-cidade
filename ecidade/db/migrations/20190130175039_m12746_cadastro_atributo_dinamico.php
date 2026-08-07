<?php

use Classes\PostgresMigration;

class M12746CadastroAtributoDinamico extends PostgresMigration
{
    public function up()
    {
        $sql = "
            INSERT INTO db_cadattdinamicoatributos
            VALUES (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), 4,
                    NULL, 'Licitação Compartilhada', 'N', 1, 'licitacao_compartilhada');
            
            INSERT INTO db_cadattdinamicoatributosopcoes
            VALUES (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),
                    currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 'N', 'Não'),
                   (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),
                    currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 'S', 'Sim');
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            DELETE
            FROM db_cadattdinamicoatributosvalor
            WHERE db110_db_cadattdinamicoatributos IN
                  (SELECT db109_sequencial FROM db_cadattdinamicoatributos WHERE db109_nome = 'licitacao_compartilhada');
            
            DELETE
            FROM db_cadattdinamicoatributosopcoes
            WHERE db18_cadattdinamicoatributos IN
                  (SELECT db109_sequencial FROM db_cadattdinamicoatributos WHERE db109_nome = 'licitacao_compartilhada');
            
            DELETE
            FROM db_cadattdinamicoatributos
            WHERE db109_nome = 'licitacao_compartilhada';                    
        ";

        $this->execute($sql);
    }
}
