<?php

namespace App\Domain\Configuracao\Menu\Services;

use Illuminate\Support\Facades\DB;

class DuplicaPermissoesSaudeService
{
    /**
     * @param integer $anoOrigem
     * @param integer $anoDestino
     * @throws \Exception
     */
    public function execute($anoOrigem, $anoDestino)
    {
        // Ambulatorial, Agendamento, TFD, Laboratório e Farmácia, respectivamente.
        $menus = [1000004, 6952, 8322, 8167, 6877];

        $uuid = '0b861fd3-15fd-4fba-b454-2e3e9a4c98fe'; // ESF
        $esf = DB::table("db_pluginmodulos")->where('db152_uid', $uuid)->first();
        if ($esf) {
            $menus[] = $esf->db152_db_modulo;
        }

        $menus = implode(',', $menus);
        $permissoes = DB::table('db_permissao')
            ->whereRaw("id_modulo in ({$menus})")
            ->where('anousu', $anoOrigem)
            ->first();
        if ($permissoes == null) {
            throw new \Exception('Não existem permissões para o ano origem informado.');
        }

        DB::statement("delete from db_permissao where id_modulo in ({$menus}) AND anousu = {$anoDestino}");

        DB::statement(<<<SQL
            INSERT INTO db_permissao
                SELECT id_usuario, id_item, permissaoativa, {$anoDestino}, id_instit, id_modulo
                FROM db_permissao
                WHERE id_modulo in ({$menus}) AND anousu = {$anoOrigem}
SQL
        );
    }
}
