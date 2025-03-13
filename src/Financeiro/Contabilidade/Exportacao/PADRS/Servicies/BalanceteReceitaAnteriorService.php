<?php


namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Servicies;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Builders\v2020\BalanceteReceitaAnteriorBuilder2020;
use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Builders\v2022\BalanceteReceitaAnteriorBuilder2022;
use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts\LayoutPad;
use Exception;
use Instituicao;
use ParametroPCASP;

/**
 * Class BalanceteReceitaAnteriorService
 * @package ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Servicies
 */
class BalanceteReceitaAnteriorService extends PadService
{
    protected $fileName = 'BREC_ANT.TXT';

    /**
     * @var integer
     */
    private $ano;
    private $dataInicio;
    private $dataFinal;

    /**
     * BalanceteReceitaAnteriorService constructor.
     * @param Instituicao[] $instituicoes
     * @param integer $ano para calculo
     */
    public function __construct(array $instituicoes, $ano)
    {
        $this->instituicoes = $instituicoes;
        $this->ano = $ano;
        $this->dataInicio = "{$ano}-01-01";
        $this->dataFinal = "{$ano}-12-31";
    }

    /**
     * @return LayoutPad[]
     * @throws Exception
     */
    protected function getDados()
    {
        $where = "o70_instit in ({$this->getListaInstituicoes()})";

        $sql = ReceitaSaldoComplemento(
            11,
            1,
            3,
            true,
            $where,
            $this->ano,
            $this->dataInicio,
            $this->dataFinal,
            true
        );

        $sql = "
            select y.*, fc_nivel_plano2005(rpad(o57_fonte,15, '0' ) ) as nivel  from (
                select case
                         when fc_conplano_grupo({$this->ano}, substr(o57_fonte, 1, 1) || '%', 9000 ) is false
                            then rpad(substr(o57_fonte, 2, 14), 14, '0')
                         else rpad(substr(o57_fonte, 1, 15), 15,'0')
                       end as o57_fonte,
                       o57_descr,
                       round(saldo_inicial, 2) as saldo_inicial,
                       round(saldo_arrecadado_acumulado, 2) as saldo_arrecadado_acumulado,
                       x.recurso,
                       x.o70_codrec,
                       coalesce(o70_instit,0) as o70_instit,
                       x.complemento,
                       x.o70_concarpeculiar,
                       coalesce(codtrib, '0') as orgao_unidade

                  from ({$sql}) as x
                left join orcreceita on orcreceita.o70_codrec = x.o70_codrec
                      and orcreceita.o70_anousu = {$this->ano}
                left join db_config on db_config.codigo = orcreceita.o70_instit
                order by o57_fonte
                ) as y
            where o57_fonte <> '000000000000000'
              and o57_fonte <> '00000000000000'
        ";


        if (ParametroPCASP::utilizaPCASPNoAno($this->ano)) {
            $sql = analiseQueryPlanoOrcamento($sql, $this->ano);
        }

        $rs = db_query($sql);
        //db_criatabela($rs);die();
        if (!$rs || pg_num_rows($rs) === 0) {
            //removido o Exception pois em casos de intit que não tem cadastro de receita, o arquivo deve se
            // gerado em branco
            //throw new Exception("Erro ao buscar receitas.");
        }

        while ($state = pg_fetch_array($rs)) {
            $builder = $this->getBuilder();
            yield $builder->addDados($state)->build();
        }
    }

    /**
     * @return BalanceteReceitaAnteriorBuilder2022
     * @throws Exception
     */
    protected function getBuilder()
    {
        $ano = $this->ano + 1;
        switch ($ano) {
            case 2020:
            case 2021:
                return new BalanceteReceitaAnteriorBuilder2020();
            default:
                throw new Exception("Layout {$this->fileName} não foi implementado para o ano {$ano}.");
        }
    }
}
