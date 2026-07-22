<?php


namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Servicies;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Builders\v2020\DecretoBuilder2020;
use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Builders\v2021\DecretoBuilder2021;
use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Builders\v2022\DecretoBuilder2022;
use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\v2020\Layout\LayoutPad;
use Exception;
use Instituicao;

class DecretoService extends PadService
{
    protected $fileName = 'DECRETO.TXT';

    /**
     * @var int
     */
    protected $ano;
    /**
     * @var string
     */
    protected $dataInicial;
    /**
     * @var string
     */
    protected $dataFinal;

    /**
     * BalanceteReceitaAnteriorService constructor.
     * @param Instituicao[] $instituicoes
     * @param integer $ano para calculo
     * @param string $dataInicial
     * @param string $dataFinal
     */
    public function __construct(array $instituicoes, $ano, $dataInicial, $dataFinal)
    {
        $this->instituicoes = $instituicoes;
        $this->ano = $ano;
        $this->dataInicial = $dataInicial;
        $this->dataFinal = $dataFinal;
    }

    protected function getDados()
    {
        $where = [
            "o58_anousu = {$this->ano}",
            "o58_instit in ({$this->getListaInstituicoes()})",
            "o39_anousu = {$this->ano}",
            "o49_data BETWEEN '{$this->dataInicial}' AND '{$this->dataFinal}'",
        ];

        $where = implode(' and ', $where);

        $sql = "
        SELECT o45_numlei AS numero_lei,
              o45_dataini AS data_lei,
              o39_numero AS numero_decreto,
              o39_data AS data_decreto,
              o46_codsup AS codigo_suplementacao,
              o46_tiposup AS tipo_suplementacao,
              round(sum(CASE WHEN o47_valor > 0 THEN o47_valor ELSE 0 END),2) AS valor_credito,
              round(sum(CASE WHEN o47_valor < 0 THEN abs(o47_valor) ELSE 0 END),2) AS valor_reducao,
              round(sum(case when o46_tiposup in (1014, 1015, 1016) then o47_valor else 0 end), 2)
                  as valor_alteracao_orcamentaria,
              round(sum(case when o46_tiposup in (1012, 1013) then o47_valor else 0 end), 2) as valor_saldo_reaberto,
              case
                when o46_tiposup in (1012, 1013) then o46_data
                else null
              end data_reabertura_credito_adicional
         FROM orcprojeto
         JOIN orclei ON o45_codlei = o39_codlei
         JOIN orcsuplem AS suplem ON o46_codlei=orcprojeto.o39_codproj
         JOIN orcsuplemval ON o47_codsup = o46_codsup
         JOIN orcdotacao  ON o58_coddot=o47_coddot
         JOIN orcsuplemlan ON orcsuplemlan.o49_codsup= o46_codsup
         LEFT JOIN orcsuplemretif ON o48_retificado = orcprojeto.o39_codproj
        WHERE {$where}
        GROUP BY o45_numlei, o45_dataini, o39_numero, o39_data, o46_codsup, o46_tiposup
        ORDER BY o45_numlei, o45_dataini, o39_numero, o39_data
        ";

        $sql = "
        SELECT x.*,
               CASE
                   WHEN
                          (SELECT count(distinct(o58_instit))
                             FROM orcsuplemval s
                           INNER JOIN orcdotacao ON o58_coddot=s.o47_coddot
                           AND o58_anousu=s.o47_anousu
                           WHERE s.o47_codsup = x.codigo_suplementacao
                           GROUP BY o47_codsup) = 1 THEN FALSE
                   ELSE TRUE
               END AS entre_entidades
          from ({$sql}) AS x
        ";

        $rs = db_query($sql);

        while ($state = pg_fetch_array($rs)) {
            $builder = $this->getBuilder();
            yield $builder->addDados($state)->build();
        }
    }

    protected function getBuilder()
    {
        switch ($this->ano) {
            case 2020:
                return new DecretoBuilder2020();
            case 2021:
                return new DecretoBuilder2021();
            default:
                throw new Exception("Layout {$this->fileName} não foi implementado para o ano {$this->ano}.");
        }
    }
}
