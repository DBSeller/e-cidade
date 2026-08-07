<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AcertoBaseIncidenciaFgtsRubricas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upIncidenciaFGTS();
    }

    private function upIncidenciaFGTS()
    {
        $this->upAcertoBaseAvaliacaoPerguntaIncidenciaFGTS();
        $this->upUpdateAvaliacaoPerguntaIncidenciaFGTS();
    }

     /**
     * Run the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downIncidenciaFGTS();
    }

    private function downIncidenciaFGTS()
    {
        $this->downAcertoBaseAvaliacaoPerguntaIncidenciaFGTS();
        $this->downUpdateAvaliacaoPerguntaIncidenciaFGTS();
    }

    private function upAcertoBaseAvaliacaoPerguntaIncidenciaFGTS()
    {
        $sql = <<<SQL
        INSERT INTO avaliacaoperguntaopcao
        VALUES (3003852,
        3000949,
        '92 - Incidência suspensa em decorrência de decisão judicial - FGTS 13º salário',
        'f',
        '92-incidencia-suspensa-em-decorrencia-de-decisao-j',
        0,
        '92',
        'codIncFGTS_92');

        INSERT INTO avaliacaoperguntaopcao
        VALUES (3003853,
        3000949,
        '93 - Incidência suspensa em decorrência de decisão judicial - FGTS aviso prévio indenizado',
        'f',
        '93-incidencia-suspensa-em-decorrencia-de-decisao-j',
        0,
        '93',
        'codIncFGTS_93');

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upUpdateAvaliacaoPerguntaIncidenciaFGTS()
    {
        $sql = <<<SQL
        UPDATE avaliacaoperguntaopcao SET db104_descricao = '91 - Incidência suspensa em decorrência de decisão judicial - FGTS mensal' WHERE db104_sequencial = 3003847;
    
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downAcertoBaseAvaliacaoPerguntaIncidenciaFGTS()
    {
        $sql = <<<SQL
        DELETE FROM habitacao.avaliacaoperguntaopcao where db104_sequencial in (3003852,3003853);

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downUpdateAvaliacaoPerguntaIncidenciaFGTS()
    {
        $sql = <<<SQL
        UPDATE avaliacaoperguntaopcao SET db104_descricao = '91 - Incidência suspensa em decorrência de decisão judicial' WHERE db104_sequencial = 3003847;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
