<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19704AjustaCamposObrigatorios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
            -- Dicionario de dados
            update db_syscampo set nomecam = 'rh252_residencia', conteudo = 'int4', descricao = 'Tipo de Residência', valorinicial = '0', rotulo = 'Tipo de Residência', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Tipo de Residência' where codcam = 1013511;
            update db_syscampo set nomecam = 'rh252_condicao', conteudo = 'int4', descricao = 'Tipo de Condição', valorinicial = '0', rotulo = 'Tipo de Condição', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Tipo de Condição' where codcam = 1013512;
            update db_syscampo set nomecam = 'rh164_assecuratoria', conteudo = 'varchar(1)', descricao = 'Contém cláusula assecuratória do direito recíproco de rescisão antes da data de seu término.', valorinicial = 'N', rotulo = 'Cláusula assecuratória', nulo = 't', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Cláusula assecuratória' where codcam = 1013514;

            -- Estrutura
            ALTER TABLE pessoal.rhimigrante ALTER COLUMN rh252_residencia DROP NOT NULL;
            ALTER TABLE pessoal.rhimigrante ALTER COLUMN rh252_condicao DROP NOT NULL;
            ALTER TABLE pessoal.rhcontratoemergencialrenovacao ALTER COLUMN rh164_assecuratoria DROP NOT NULL;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL
            -- Dicionario de dados
            update db_syscampo set nomecam = 'rh252_residencia', conteudo = 'int4', descricao = 'Tipo de Residência', valorinicial = '0', rotulo = 'Tipo de Residência', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Tipo de Residência' where codcam = 1013511;
            update db_syscampo set nomecam = 'rh252_condicao', conteudo = 'int4', descricao = 'Tipo de Condição', valorinicial = '0', rotulo = 'Tipo de Condição', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Tipo de Condição' where codcam = 1013512;
            update db_syscampo set nomecam = 'rh164_assecuratoria', conteudo = 'varchar(1)', descricao = 'Contém cláusula assecuratória do direito recíproco de rescisão antes da data de seu término.', valorinicial = 'N', rotulo = 'Cláusula assecuratória', nulo = 'f', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Cláusula assecuratória' where codcam = 1013514;

            -- Estrutura
            ALTER TABLE pessoal.rhimigrante ALTER COLUMN rh252_residencia SET NOT NULL;
            ALTER TABLE pessoal.rhimigrante ALTER COLUMN rh252_condicao SET NOT NULL;
            ALTER TABLE pessoal.rhcontratoemergencialrenovacao ALTER COLUMN rh164_assecuratoria SET NOT NULL;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
