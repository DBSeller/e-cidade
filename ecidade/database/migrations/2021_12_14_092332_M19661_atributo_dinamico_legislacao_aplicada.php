<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19661AtributoDinamicoLegislacaoAplicada extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      // Cadastro Atributos dinтmicos   
      DB::statement("INSERT INTO configuracoes.db_cadattdinamicoatributos(db109_sequencial,
                                                                          db109_db_cadattdinamico,
                                                                          db109_codcam,
                                                                          db109_descricao,
                                                                          db109_valordefault,
                                                                          db109_tipo,
                                                                          db109_nome
                                                                          )
                     VALUES (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), 
                            (select db118_sequencial 
                               from  db_cadattdinamico 
                              where db118_descricao ilike 'Atributos da licita%' 
                              limit 1),
                             null,
                             'Legislaчуo Aplicada', 
                             null, 
                             1, 
                             'legislacao_aplicada');");
      
      DB::statement("INSERT INTO configuracoes.db_cadattdinamicoatributosopcoes
                     VALUES (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),
                             currval('configuracoes.db_cadattdinamicoatributos_db109_sequencial_seq'), '1', 'Decreto nК 10.024, de 20 de setembro de 2019'),
                            (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),
                             currval('configuracoes.db_cadattdinamicoatributos_db109_sequencial_seq'), '2', 'Decreto nК 5.450, de 31 de maio de 2005'),
                             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),
                             currval('configuracoes.db_cadattdinamicoatributos_db109_sequencial_seq'), '3', 'Lei nК 14.133, de 1К de abril de 2021'),
                             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),
                             currval('configuracoes.db_cadattdinamicoatributos_db109_sequencial_seq'), '4', 'Lei nК 8.666, de 21 de junho de 1993'),
                             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),
                             currval('configuracoes.db_cadattdinamicoatributos_db109_sequencial_seq'), '5', 'Lei nК 12.462, de 4 de agosto de 2011'),
                             (nextval('configuracoes.db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),
                             currval('configuracoes.db_cadattdinamicoatributos_db109_sequencial_seq'), '6', 'Decreto nК 3.555, de 8 de agosto de 2000 - Pregуo Presencial');");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        return;
    }
}
