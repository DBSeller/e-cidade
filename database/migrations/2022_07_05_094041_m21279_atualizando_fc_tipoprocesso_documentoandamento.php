<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M21279AtualizandoFcTipoprocessoDocumentoandamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
create or replace function fc_tipoprocesso_documentoandamento(integer, integer)
   returns integer
   language plpgsql
  as
$$
declare
codigoTipoDocumento    alias for $1;
    codigoOrigem           alias for $2;
    codigoTipoProcesso     integer default null;
    numemp                 integer default null;
begin
    select p51_codigo
        into codigoTipoProcesso
    from tipoproc
    where p51_prottipodocumentoprocesso = codigoTipoDocumento
    order by p51_codigo asc limit 1;

    return codigoTipoProcesso;
end
$$
SQL
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<SQL
create or replace function fc_tipoprocesso_documentoandamento(integer, integer)
   returns integer
   language plpgsql
  as
$$
declare
codigoTipoDocumento    alias for $1;
    codigoOrigem           alias for $2;
    codigoTipoProcesso     integer default null;
    numemp                 integer default null;
begin
    select p51_codigo
        into codigoTipoProcesso
    from tipoproc
    where p51_prottipodocumentoprocesso = codigoTipoDocumento
    order by p51_codigo asc limit 1;

    return codigoTipoProcesso;
end
$$
SQL
        );
    }
}
