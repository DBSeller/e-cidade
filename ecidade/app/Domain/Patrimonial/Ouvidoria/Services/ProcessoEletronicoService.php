<?php

namespace App\Domain\Patrimonial\Ouvidoria\Services;

use Illuminate\Support\Facades\DB;
use App\Domain\Patrimonial\Ouvidoria\HelperServices\MenuHelper;

class ProcessoEletronicoService
{

    public function getMensagens($numero_processo)
    {
        $result = DB::select("
                        SELECT DISTINCT
                        p61_codandam AS codigo_andamento,
                        p78_despacho AS mensagem,
                        p78_sequencial AS codigo_mensagem,
                        p78_data AS DATA,
                        p78_hora AS hora,
                        p01_documento AS id_estorage,
                        p01_descricao AS descricao,
                        p01_nomedocumento AS nomedocumento,
                        p78_tipodespacho AS tipo_despacho,
                        CASE  WHEN p78_tipodespacho IN (1002, 1000) THEN
                            (
                            SELECT
                                p78_sequencial
                            FROM
                                procandamint
                            WHERE
                                p78_codandam = p61_codandam
                                AND p78_tipodespacho IN (1001, 1,1003)
                            )
                        ELSE
                          NULL
                        END AS referencia_codigo,
                         CASE  WHEN p78_tipodespacho IN (1002, 1000) THEN
                            (
                            SELECT
                                p78_despacho
                            FROM
                                procandamint
                            WHERE
                                p78_codandam = p61_codandam
                                AND p78_tipodespacho IN (1001, 1,1003)
                            )
                        ELSE
                          NULL
                        END AS referencia_mensagem,
                    TO_CHAR(p113_data_registro,'dd/mm/yyyy HH24:MI:SS') AS data_visualizacao,
                    db_usuarios.login AS usuario_visualizou
                    FROM
                        procandam
                    INNER JOIN processosvinculados ON
                        p92_processofilho = p61_codproc
                    INNER JOIN procandamint ON
                        p78_codandam = p61_codandam
                    LEFT JOIN protprocessodocumento ON
                        p01_procandamint = p78_sequencial
                        AND p01_estorage IS TRUE
                    LEFT JOIN protocolo.historicovisualizacaoprocandam ON
                    p113_procandamint_id = p78_sequencial
                    LEFT JOIN db_usuarios ON id_usuario = p113_usuario_id
                    WHERE
                        p92_processopai = {$numero_processo}
                    ORDER BY
                        DATA ASC,
                        hora ASC,
                        p78_sequencial ASC
                    ");

        return $this->coverterMensagensParaObjecto($result);
    }

    public function saveVisualizacao($p78_sequencial)
    {
        try {
            $usuario_id = db_getsession('DB_id_usuario');
            $instituicao_id = db_getsession('DB_instit');
            $departamento_id = db_getsession('DB_coddepto');

            $sql = "INSERT INTO protocolo.historicovisualizacaoprocandam (
                    p113_usuario_id
                    , p113_instituicao_id
                    , p113_departamento_id
                    , p113_procandamint_id
                    , p113_data_registro
                    )VALUES(
                  {$usuario_id}
                , {$instituicao_id}
                , {$departamento_id}
                , {$p78_sequencial}
                , NOW()
            );
           ";
            $rs = pg_query($sql);
            if ($rs == false) {
                throw new \Exception("Error ao salvar visualiza��o! " . pg_last_error());
            }

            $sql = "SELECT
                    TO_CHAR(p113_data_registro,'dd/mm/yyyy HH24:MI:SS') AS data_visualizacao,
                    db_usuarios.login AS usuario_visualizou
                    FROM protocolo.historicovisualizacaoprocandam
                    LEFT JOIN db_usuarios ON id_usuario = p113_usuario_id
                    WHERE
                        p113_procandamint_id = {$p78_sequencial}
                   ";

            $rs = pg_query($sql);
            $data = collect(pg_fetch_object($rs));
            return [
                "success" => true,
                "message" => utf8_encode(urldecode("Visualiza��o salva com sucessso!")),
                "data" => $data
            ];
        } catch (\Exception $ex) {
            return ["success" => false, "message" => utf8_encode(urldecode($ex->getMessage()))];
        }
    }

    public function getMenu($instituicoes, $formas_reclamacao, $cpfCnpj = null)
    {
        $filtrarTiposDeProcessoPersona = "null";

        if (!empty($cpfCnpj)) {
            $filtrarTiposDeProcessoPersona = "
                SELECT
                   protocolo.personacgm.p121_persona
                FROM
                   protocolo.cgm
                INNER JOIN protocolo.personacgm ON
                protocolo.personacgm.p121_cgm = protocolo.cgm.z01_numcgm
                WHERE
                    protocolo.cgm.z01_cgccpf = '{$cpfCnpj}'
                GROUP BY p121_persona
            ";
        }

        $sql = "
                SELECT
                    codigo AS instituicao_codigo ,
                    nomeinst AS instituicao_nome ,
                    o40_orgao AS orgao_codigo ,
                    trim(o40_descr) AS orgao_nome ,
                    '' AS categoria_descricao ,
                    '/storage/2020/4/28/1588080821.png' AS categoria_logo_url ,
                    db_depart.coddepto AS tipoprocesso_depto_id ,
                    db_depart.descrdepto AS tipoprocesso_depto_descricao ,
                    tipoproc.p51_codigo AS tipoprocesso_codigo ,
                    tipoproc.p51_descr AS tipoprocesso_descricao ,
                    tipoproc.p51_linksaibamais AS linksaibamais ,
                    tipoproc.p51_itemmenu AS item_menu ,
                    tipoproc.p51_dtlimite AS p51_dtlimite ,
                    tipoprocformareclamacao.p43_formareclamacao AS tipoprocesso_formareclamacao ,
                    tipoprocessoformulario.p108_rota AS rota,
                    tipoproc.p51_identificado AS identificado
                FROM
                    tipoproc
                INNER JOIN tipoprocformareclamacao ON
                    p43_tipoproc = p51_codigo
                INNER JOIN tipoprocdepto ON
                    p41_tipoproc = p51_codigo
                INNER JOIN db_depart ON
                    coddepto = p41_coddepto
                INNER JOIN db_departorg ON
                    db01_coddepto = coddepto
                INNER JOIN orcunidade ON
                            o41_orgao = db01_orgao
                            AND o41_unidade = db01_unidade
                            AND o41_anousu = db01_anousu
                            AND o41_instit = p51_instit
                INNER JOIN orcorgao ON
                    o40_orgao = db01_orgao
                    AND o40_anousu = db01_anousu
                INNER JOIN db_config ON
                    codigo = p51_instit
                LEFT JOIN tipoprocessoformulario ON
                    p108_tipoproc = p51_codigo
                LEFT JOIN ouvidoria.tipoprocpersona ON
                    ouvidoria.tipoprocpersona.ov34_tipoproc
                     = p51_codigo
                WHERE
                    tipoprocformareclamacao.p43_formareclamacao IN ({$formas_reclamacao})
                    AND tipoproc.p51_instit IN ({$instituicoes})
                    AND o40_anousu::TEXT = TO_CHAR(NOW(), 'yyyy')
                AND (p51_dtlimite IS NULL
                    OR p51_dtlimite >= now())
                AND CASE WHEN p51_identificado  = TRUE THEN
                  ouvidoria.tipoprocpersona.ov34_persona  IN (
                       {$filtrarTiposDeProcessoPersona}
                ) OR ouvidoria.tipoprocpersona.ov34_sequencial  IS NULL  ELSE TRUE END

                GROUP BY
                instituicao_codigo,
                instituicao_nome,
                orgao_codigo,
                orgao_nome,
                tipoprocesso_codigo,
                categoria_logo_url,
                tipoprocesso_depto_id,
                tipoprocesso_depto_descricao,
                tipoprocesso_formareclamacao,
                linksaibamais,
                item_menu,
                rota,
                p51_dtlimite,
                p51_identificado
                ORDER BY
                categoria_descricao ,
                tipoprocesso_descricao;
        ";

        $result = DB::select($sql);

        if ($formas_reclamacao == 9) {
            return MenuHelper::coverterMenuPrimeiroAcesso($result);
        }
        return MenuHelper::converterMenusParaObjeto($result);
    }

    private function coverterMensagensParaObjecto($mensagesResult)
    {
        $mensagens = collect($mensagesResult)->groupBy(function ($mensagem) {
            return $mensagem->codigo_mensagem;
        })->toArray();

        return collect($mensagens)->flatMap(function ($mensagem, $index) {
            $anexos = array_map(function ($item) {
                return (object)[
                    'id_estorage' => !empty($item->id_estorage) ? $item->id_estorage : $item->nomedocumento,
                    'descricao' => !empty($item->descricao) ? utf8_encode(urldecode($item->descricao)) : null,
                    'content' => null,
                    'type' => null
                ];
            }, $mensagem);

            $mensagem = current($mensagem);

            if (empty($mensagem->id_estorage) and empty($mensagem->nomedocumento)) {
                $anexos = [];
            }
            return [
                $index => (object)[
                    'codigo_andamento' => $mensagem->codigo_andamento,
                    'codigo' => $mensagem->codigo_mensagem,
                    'data' => (new \DateTime($mensagem->data))->format('d/m/Y'),
                    'hora' => urldecode($mensagem->hora),
                    'mensagem' => utf8_encode(html_entity_decode($mensagem->mensagem, ENT_QUOTES, 'ISO-8859-1')),
                    'anexos' => $anexos,
                    'tipo_despacho' => $mensagem->tipo_despacho,
                    'referencia_codigo' => $mensagem->referencia_codigo,
                    'referencia_mensagem' => utf8_encode(urldecode($mensagem->referencia_mensagem)),
                    'data_visualizacao' => utf8_encode(urldecode($mensagem->data_visualizacao)),
                    'usuario_visualizou' => utf8_encode(urldecode($mensagem->usuario_visualizou)),
                ]
            ];
        })->values();
    }

    public function getAtendimentos($ids = '')
    {
        $sql = "SELECT
        ouvidoriaatendimento.ov01_dataatend AS data_atendimento,
        ouvidoriaatendimento.ov01_horaatend AS hora_atendimento,
        ouvidoriaatendimento.ov01_numero AS numero_atendimento,
        ouvidoriaatendimento.ov01_anousu AS ano_atendimento,
        cidadao.ov02_nome AS nome_cidadao,
        cidadao.ov02_cnpjcpf AS cnpjcpf,
        ouvidoriaatendimento.ov01_sequencial AS codigo_atendimento,
        ouvidoriaatendimento.ov01_requerente AS nome_requerente,
        protprocesso.p58_numero AS numero_processo,
        protprocesso.p58_ano AS ano_processo,
        protprocesso.p58_codproc AS codigo_processo,
        ouvidoriaatendimento.ov01_tipoprocesso AS tipo_processo,
        ouvidoriaatendimento.ov01_depart AS departamento,
        tipoproc.p51_descr AS nome_tipo_processo,
        (
            SELECT
               p69_arquivado
            FROM
                protocolo.arqproc
            LEFT JOIN protocolo.procarquiv ON
            p68_codarquiv = p67_codarquiv
            LEFT JOIN protocolo.arqandam ON
            p69_codarquiv = p67_codarquiv
            WHERE p67_codproc  = p58_codproc
            GROUP BY
            p67_codproc,
            p69_arquivado
            ORDER BY MAX(p67_codarquiv) DESC
                    LIMIT 1
        )  AS arquivado,
         p58_obs ilike 'Recadastramento acesso arquivado pelo sistema' as recadastramento_aprovado
        FROM
         ouvidoriaatendimento
        INNER JOIN protocolo.tipoproc
                ON protocolo.tipoproc.p51_codigo  =  ouvidoria.ouvidoriaatendimento.ov01_tipoprocesso
        LEFT JOIN ouvidoriaatendimentocidadao
               ON ouvidoriaatendimentocidadao.ov10_ouvidoriaatendimento  = ouvidoriaatendimento.ov01_sequencial
        LEFT JOIN cidadao
               ON cidadao.ov02_sequencial = ouvidoriaatendimentocidadao.ov10_cidadao
              AND ouvidoriaatendimentocidadao.ov10_seq = cidadao.ov02_seq
        LEFT JOIN processoouvidoria
               ON processoouvidoria.ov09_ouvidoriaatendimento = ouvidoriaatendimento.ov01_sequencial
        LEFT JOIN protprocesso ON processoouvidoria.ov09_protprocesso = protprocesso.p58_codproc
        LEFT JOIN ouvidoriaatendimentoprocessoeletronico
        ON ouvidoriaatendimentoprocessoeletronico.ov33_ouvidoriaatendimento = ouvidoriaatendimento.ov01_sequencial
        WHERE
             ov01_sequencial IN ({$ids})
        ORDER BY ouvidoriaatendimento.ov01_dataatend DESC,ouvidoriaatendimento.ov01_horaatend DESC;";

        $rs = pg_query($sql);
        return collect(pg_fetch_all($rs));
    }

    public function getDetalheProcesso($codigoProcesso)
    {
        $sql = "
        SELECT
            codigo AS   codigo_andamento,
            data_despacho AS    data_andamento,
            hora_despacho AS      hora_andamento,
            concat(despacho,' ',motivo) AS  despacho,
            id_estorage AS id_estorage,
            descricao AS     descricao
        FROM
           (
                    SELECT
                    p61_codandam AS codigo,
                    p61_despacho AS despacho,
                    p61_dtandam AS data_despacho,
                    p61_hora AS hora_despacho,
                    NULL AS id_estorage,
                    NULL AS descricao,
                    (
                        SELECT
                           p67_historico
                        FROM
                           protocolo.arqproc
                        LEFT JOIN protocolo.procarquiv on
                        p68_codarquiv = p67_codarquiv
                        LEFT JOIN protocolo.arqandam on
                        p69_codarquiv = p67_codarquiv
                        WHERE
                            p67_codproc  = {$codigoProcesso}
                        GROUP BY
                            p67_codproc,
                            p67_historico
                        ORDER BY MAX(p67_codarquiv) DESC
                        LIMIT 1
                    )  as motivo,
                    'PADRAO' AS tipo
                FROM
                    procandam
                WHERE
                    p61_codproc = {$codigoProcesso}
                    AND p61_publico IS TRUE
                UNION
                SELECT
                    DISTINCT p78_sequencial AS codigo,
                    p78_despacho AS despacho,
                    p78_data AS data_despacho,
                    p78_hora AS hora_despacho,
                    p01_documento AS id_estorage,
                    p01_descricao AS descricao,
                    '' as motivo,
                    'INTERNO' AS tipo
                FROM
                    procandamint
                LEFT JOIN procandamintand ON
                    p86_codandam = p78_codandam
                LEFT JOIN proctransferintand ON
                    p87_codandam = p78_codandam
                LEFT JOIN protprocessodocumento ON
                    p01_procandamint = p78_sequencial
                    AND p01_estorage IS TRUE
                WHERE
                    p78_codandam IN (
                    SELECT
                        p61_codandam
                    FROM
                        procandam
                    WHERE
                        p61_codproc = {$codigoProcesso}
                    )
                    AND p78_publico IS TRUE
                    AND p86_codandam IS NULL
                    AND p87_codandam IS NULL
           ) AS processo
           ORDER BY data_despacho DESC,hora_despacho DESC,codigo DESC
        ";

        $result = DB::select($sql);
        return $this->converterDetalheProcessoParaEstruturaDeDados($result);
    }

    public function converterDetalheProcessoParaEstruturaDeDados($andamentos)
    {

        $andamentos = collect($andamentos)->groupBy(function ($andamento) {
            return $andamento->codigo_andamento;
        })->toArray();

        return collect($andamentos)->flatMap(function ($andamento, $index) {

            $anexos = array_map(function ($item) {

                return (object)[
                    'id_estorage'=> !empty($item->id_estorage) ? $item->id_estorage : null,
                    'descricao'  => !empty($item->descricao) ? utf8_encode(urldecode($item->descricao)) : null,
                    'content'    => null,
                    'type'       => null
                ];
            }, $andamento);

            $andamento = current($andamento);

            if (empty($andamento->id_estorage)) {
                $anexos = [];
            }

            return [
                $index => (object) [
                    'codigo'                  => $andamento->codigo_andamento,
                    'data'                    => (new \DateTime($andamento->data_andamento))->format('d/m/Y'),
                    'hora'                    => urldecode($andamento->hora_andamento),
                    'despacho'                => utf8_encode(urldecode($andamento->despacho)),
                    'anexos'                  => $anexos,
                    'flagMensagem'            => false
                ]
            ];
        })->values();
    }
}
