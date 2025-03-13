<?php

namespace App\Domain\Patrimonial\Patrimonio\Services;

use App\Domain\Patrimonial\Patrimonio\Models\BensTransfDestino;
use App\Domain\Patrimonial\Patrimonio\Relatorios\BensTransferenciaAbertoPDF;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Classe responsável pelas regras de negócios relacionadas a tabela benstransf
 * @package App\Domain\Saude\TFD\Services
 */
class BensTransferenciaAbertoService
{
    /**
     * Monta os dados para o relatório
     * @param Collection $bensTransfAberto
     * @return \App\Domain\Saude\TFD\Contracts\ViagensPorMotorista
     * @throws Exception
     */
    public function gerarRelatorioBensTrasferenciaAbertos(\stdClass $parametros)
    {
        $dados = [];
        $transferenciasAbertas = $this->buscaTransferenciasAbertas(
            $parametros->dataInicial,
            $parametros->dataFinal,
            $parametros->DB_instit
        );

        if (empty($transferenciasAbertas)) {
            throw new \Exception("Não foram encontrados registros para o filtro selecionado!");
        }

        $num = 0;
        foreach ($transferenciasAbertas as $transferenciaAberta) {
            $index = $transferenciaAberta->id_departamento_origem;

            if (!array_key_exists($index, $dados)) {
                $dados[$index] = (object)[
                    'id' => $transferenciaAberta->id_departamento_origem,
                    'descricao' => $transferenciaAberta->departamento_origem,
                    'id_departamento_origem' => $transferenciaAberta->id_departamento_origem,
                    'transferencias' => []
                ];
            }

            $dados[$index]->transferencias[] = (object)[
                'codigo_transferencia' => $transferenciaAberta->codigo_transferencia,
                'data_transferencia' => db_formatar($transferenciaAberta->data_transferencia, 'd'),
                'id_usuario' => $transferenciaAberta->id_usuario,
                'id_departamento_destino' => $transferenciaAberta->id_departamento_destino,
                'nome_usuario' => $transferenciaAberta->nome_usuario,
                'departamento_destino' => $transferenciaAberta->departamento_destino
            ];
        }
        return new BensTransferenciaAbertoPDF($dados);
    }

    private function buscaTransferenciasAbertas($dataInicio, $dataFinal, $instituicao)
    {
        return DB::select("
        SELECT t93_codtran                    AS codigo_transferencia,
           t93_data                           AS data_transferencia,
           t93_id_usuario                     AS id_usuario,
           t34_divisaoorigem                  AS id_divisao_origem,
           t34_departamentoorigem             AS id_departamento_origem,
           t94_depart                         AS id_departamento_destino,
           t94_divisao                        AS id_divisao_destino,
           nome                               AS nome_usuario,
           departorigem.descrdepto            AS departamento_origem,
           departamentodestino.descrdepto     AS departamento_destino,
           divisaodestino.t30_descr           AS divisao_destino,
           departdivorigem.t30_descr          AS divisao_origem
    FROM   benstransf
           INNER JOIN benstransfdes
                   ON benstransfdes.t94_codtran = benstransf.t93_codtran
           INNER JOIN configuracoes.db_depart AS departamentodestino
                   ON departamentodestino.coddepto = benstransfdes.t94_depart
           INNER JOIN benstransforigemdestino AS divorigem
                   ON t34_transferencia = t93_codtran
           LEFT JOIN db_usuarios
                  ON id_usuario = t93_id_usuario
           LEFT JOIN db_depart departorigem
                  ON departorigem.coddepto = t93_depart
           LEFT JOIN patrimonio.departdiv divisaodestino
                  ON divisaodestino.t30_depto = departamentodestino.coddepto
           LEFT JOIN departdiv departdivorigem
                  ON departdivorigem.t30_codigo = t34_divisaoorigem
    WHERE  NOT EXISTS (SELECT 1
                       FROM   benstransfconf
                       WHERE  benstransfconf.t96_codtran = benstransf.t93_codtran)
           AND t93_data BETWEEN '{$dataInicio}' AND '{$dataFinal}' and t93_instit = {$instituicao}
    GROUP  BY t93_codtran,
              t93_data,
              t93_id_usuario,
              t34_divisaoorigem,
              t34_departamentoorigem,
              t94_depart,
              t94_divisao,
              nome,
              departorigem.descrdepto,
              departamentodestino.descrdepto,
              departdivorigem.t30_descr,
              divisaodestino.t30_descr
    ORDER  BY t93_data, nome
       ");
    }
}
