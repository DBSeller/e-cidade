<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M19522AlteraRegrasDocumento extends Migration
{
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<sql
            delete from conhistdocregra where c92_conhistdoc = 403 and c92_anousu = 2022;
            insert into conhistdocregra (c92_sequencial, c92_conhistdoc, c92_descricao, c92_regra, c92_anousu)
                values (nextval('conhistdocregra_c92_sequencial_seq'), 403, 'ENTRADA MANUAL', 'select 1 from matestoqueinimei inner join matestoqueini on m82_matestoqueini = m80_codigo where m82_codigo = [codigomovimentacaoestoque] and m80_codtipo in (3, 999)', 2022);
sql
        );
    }

    public function down()
    {
        DB::connection()->getPdo()->exec(<<<sql
            delete from conhistdocregra where c92_conhistdoc = 403 and c92_anousu = 2022;
            insert into conhistdocregra (c92_sequencial, c92_conhistdoc, c92_descricao, c92_regra, c92_anousu)
                values (nextval('conhistdocregra_c92_sequencial_seq'), 403, 'ENTRADA MANUAL', 'select 1 from matestoqueinimei inner join matestoqueini on m82_matestoqueini = m80_codigo where m82_codigo = [codigomovimentacaoestoque] and m80_codtipo = 3', 2022);
sql
        );
    }
}
