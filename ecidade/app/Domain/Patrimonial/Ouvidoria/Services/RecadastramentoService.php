<?php


namespace App\Domain\Patrimonial\Ouvidoria\Services;

use Illuminate\Support\Facades\DB;

class RecadastramentoService
{

    public function getDadosServidorCpf($cpf)
    {

        $termo_1 = 'Declaro para fins de direito, sob as penas da lei, que as informações abaixo prestadas e ';
        $termo_1 .= 'documentos que apresento para a finalidade de recadastramento, preenchidos e anexados, ';
        $termo_1 .= 'são verdadeiros e autênticos (fieis à verade e condizentes com a realidade dos fatos ';
        $termo_1 .= 'à época) e da minha inteira responsabilidade, ficando ciente que a falsidade das ';
        $termo_1 .= 'informações implicará em sanções civis, administrativas e criminais previstas na ';
        $termo_1 .= 'legislação aplicável.';

        $termo_2 = 'Coleta de Dados. Reconheço que a EMPREGADORA, para fins de persecução das suas ';
        $termo_2 .= 'atividades ou cumprimento de obrigações legais e em consonância com a Lei Geral ';
        $termo_2 .= 'de Proteção de Dados, irá coletar dados de natureza pessoal por meio de seus equipamentos, ';
        $termo_2 .= 'sistemas, colaboradores e outros meios lícitos para obtenção dessas informações. Tais dados ';
        $termo_2 .= 'pessoais podem ser compartilhados com outros órgãos da administração municipal ou fornecidos ';
        $termo_2 .= 'a autoridades mediante ordem judicial, como também para o devido cumprimento legal das ';
        $termo_2 .= 'obrigações trabalhistas e previdenciárias.';

        $sql = "
            WITH matricula as (
            select
            rh01_regist
            from
            rhpessoal
            inner join cgm ON rh01_numcgm = z01_numcgm
            where
            z01_cgccpf = '{$cpf}'
            ),
            conjuge AS (
            SELECT
            rh31_nome,
            dp01_cpf,
            rh31_dtnasc,
            rh31_regist
            FROM
            rhdepend
            LEFT JOIN rhdependeplug ON dp01_rhdepend = rh31_codigo
            join matricula on rh31_regist = rh01_regist
            WHERE
            rh31_gparen = 'C'
            ),
            pdc AS (
            SELECT
            rh02_regist,
            CASE
            WHEN rh02_tipodeficiencia = 1 THEN 1
            ELSE 2
            END fisica,
            CASE
            WHEN rh02_tipodeficiencia = 2 THEN 1
            ELSE 2
            END auditiva,
            CASE
            WHEN rh02_tipodeficiencia = 3 THEN 1
            ELSE 2
            END visual,
            CASE
            WHEN rh02_tipodeficiencia = 4 THEN 1
            ELSE 2
            END intelectual,
            CASE
            WHEN rh02_tipodeficiencia = 6 THEN 1
            ELSE 2
            END reabilitado
            FROM
            pessoal.rhpessoalmov
            join matricula on rh01_regist = rh02_regist
            WHERE
            rh02_anousu = fc_anofolha(rh02_instit) :: INTEGER
            AND rh02_mesusu = fc_mesfolha(rh02_instit) :: INTEGER
            ),
            dados_pessoais AS (
            SELECT
            '{$termo_1}' as termo_1,
            '{$termo_2}' as termo_2,
            rhpessoal.rh01_regist,
            z01_nome AS nome_funcionario,
            z01_sexo AS sexo_funcionario,
            TO_CHAR(z01_nasc, 'DD/MM/YYYY') AS data_nascimento_funcionario,
            z01_mae AS nome_mae,
            z01_pai AS nome_pai,
            (
            CASE
            WHEN rh01_raca = 1 THEN 1
            WHEN rh01_raca = 2 THEN 2
            WHEN rh01_raca = 4 THEN 3
            WHEN rh01_raca = 6 THEN 6
            WHEN rh01_raca = 8 THEN 5
            END
            ) AS raca,
            (
            CASE
            WHEN rh01_estciv = 1 THEN 1
            WHEN rh01_estciv = 2 THEN 2
            WHEN rh01_estciv = 3 THEN 5
            WHEN rh01_estciv = 4 THEN 4
            WHEN rh01_estciv = 5 THEN 3
            WHEN rh01_estciv = 6 THEN 9
            WHEN rh01_estciv = 8 THEN 9
            END
            ) AS estado_civil,
            rh01_instru AS escolaridade,
            z01_nacion AS nacionalidade,
            z01_ident AS numero_rg,
            z01_identorgao AS nome_orgao,
            NULL AS data_emissao,
            rh16_titele AS titulo_eleitor,
            rh16_secaoe AS secao_titulo,
            rh16_zonael AS zona_eleitoral,
            rh16_reserv AS reservista,
            rh16_pis AS pis,
            rh16_carth_n AS cnh,
            r16_carth_cat AS categoria_cnh,
            TO_CHAR(rh16_carth_val, 'DD/MM/YYYY') AS validade_cnh,
            conjuge.rh31_nome AS nome_conjuge,
            conjuge.dp01_cpf AS cpf_conjuge,
            TO_CHAR(conjuge.rh31_dtnasc, 'DD/MM/YYYY') AS nasc_conjuge,
            z01_cep AS cep,
            z01_uf AS uf,
            z01_munic AS municipio,
            z01_bairro AS bairro,
            z01_ender AS endereco,
            z01_numero AS numero,
            z01_compl AS complemento,
            CASE
            WHEN z01_naturalidade = '' THEN NULL
            WHEN z01_naturalidade = '0' THEN NULL
            ELSE z01_naturalidade
            END AS naturalidade,
            pdc.fisica AS pcd_fisica,
            pdc.visual AS pcd_visual,
            pdc.auditiva AS pcd_auditiva,
            pdc.intelectual AS pcd_intelectual,
            pdc.reabilitado AS pcd_reabilitado,
            z01_telcel AS celular,
            z01_email as email,
            z01_telef AS telefone
            FROM
            cgm
            INNER JOIN rhpessoal ON rh01_numcgm = z01_numcgm
            LEFT JOIN rhpesdoc ON rh16_regist = rh01_regist
            LEFT JOIN pdc ON rh02_regist = rh01_regist
            LEFT JOIN conjuge ON rh31_regist = rh01_regist
            inner join matricula on matricula.rh01_regist = rhpessoal.rh01_regist
            WHERE
            z01_cgccpf = '{$cpf}'
            )
            SELECT
            *
            FROM
            dados_pessoais;
        ";
        return DB::select($sql);
    }

    public function getDependentesServidorCpf($cpf)
    {
        $sql = "
            SELECT
                DISTINCT
                rh31_nome AS nome_dependentes,
                case when dp01_sexo = 'M' then 1 else 2 end AS sexo_dependente,
                dp01_cpf AS cpf_dependente,
                TO_CHAR(rh31_dtnasc, 'DD/MM/YYYY') AS data_nasc_dependente,
                CASE
                    rh31_gparen
                    WHEN 'C' THEN 1
                    WHEN 'F' THEN 3
                    WHEN 'P' THEN 9
                    WHEN 'M' THEN 9
                    WHEN 'A' THEN 9
                    ELSE 99
                END
            AS tipo_de_dependentes
            ,  CASE WHEN rh31_depend='C' THEN 1
                    WHEN rh31_depend='S' THEN 1
               ELSE 2
               END AS dependente_salario_familia,
                CASE
                    WHEN rh31_fins_previdenciarios = true THEN
                1
                    ELSE
                2
                    END AS dependente_fins_previdenciarios,
                CASE
                    rh31_irf
                 WHEN '0' THEN 2 ELSE 1 END
                 AS dependente_irrf
            ,
            CASE
                    rh31_irf
                 WHEN '8' THEN 1 ELSE 2 END
                 AS dependente_incapacidade
            FROM
                cgm
            JOIN rhpessoal ON
                rh01_numcgm = z01_numcgm
            INNER JOIN rhdepend ON
                rh31_regist = rh01_regist
            INNER JOIN rhdependeplug ON
                dp01_rhdepend = rh31_codigo
            WHERE
                z01_cgccpf = '{$cpf}';
         ";
        return DB::select($sql);
    }

    public function verificaServidorPossuiPermissaoRecadastramento($cpf, $tipoProcesso)
    {

        $instituicaoPorSecretaria = "";
        $filtroSecretaria = "";
        if (!empty($instituicaoPorSecretaria)) {
            $filtroSecretaria = "
            when rh02_instit IN ({$instituicaoPorSecretaria}) then
             exists (
                    select
                        1
                    from
                        tipoprocdepto
                        join db_departorg on p41_coddepto = db01_coddepto
                    where
                        db01_anousu = rh02_anousu
                        and db01_orgao = rh26_orgao
                        and db01_unidade = rh26_unidade
                        and p41_tipoproc = {$tipoProcesso}
            )";
        }

        $sql = "
        with dados_servidor as (
                    select
                        distinct rh01_regist,
                        z01_nome,
                        z01_cgccpf,
                        rh01_instit
                    from
                        cgm
                        inner join rhpessoal on rh01_numcgm = z01_numcgm
                    where
                        z01_cgccpf = '{$cpf}'
        ),
        parametros as (
                select
                    codigo as r11_instit,
                    fc_anofolha(codigo) as r11_anousu,
                    fc_mesfolha(codigo) as r11_mesusu
                from
                    db_config
                where
                    codigo in (
                        select
                            rh01_instit
                        from
                            dados_servidor
                    )

        ), movimento_servidor as (
                select
                    *
                from
                    dados_servidor
                    join parametros on r11_instit = rh01_instit
                    join rhpessoalmov on rh02_regist = rh01_regist
                    and rh02_anousu = r11_anousu
                    and rh02_mesusu = r11_mesusu
                    and rh02_instit = rh01_instit
                    left join rhlota on r70_codigo = rh02_lota
                    and r70_instit = rh02_instit
                    left join rhlotaexe on rh26_codigo = r70_codigo
                    and rh26_anousu = rh02_anousu
                    left join rhpesrescisao on rh05_seqpes = rh02_seqpes
                    where
                       rh05_seqpes is null
        )
         select
                    true as pertence
                from
                    movimento_servidor
                where
                    case
                        when rh02_instit = 6 then
                            9726 = {$tipoProcesso}
                        when rh02_instit in (7, 8) then
                            9727 = {$tipoProcesso}
                        {$filtroSecretaria}
                        else
                            exists (
                                select
                                    1
                                from
                                    tipoproc
                                where
                                    p51_codigo = {$tipoProcesso}
                                    and p51_instit = rh02_instit
                            )
                    end
        ";

        return DB::select($sql);
    }
}
