<?php


namespace ECidade\RecursosHumanos\Pessoal\Service;

use BusinessException;
use cl_rhrubricas;
use ECidade\RecursosHumanos\Pessoal\Model\ControleAfastamento;
use ECidade\RecursosHumanos\Pessoal\Repository\ControleAfastamentoRepository;
use Exception;
use Instituicao;
use RubricaRepository;

class ControleAfastamentoService
{

    private $repositorio;

    /**
     * ControleAfastamentoService constructor.
     * @param ControleAfastamentoRepository $repositorio
     */
    public function __construct(ControleAfastamentoRepository $repositorio)
    {
        $this->repositorio = $repositorio;
    }

    /**
     * @param Instituicao $instituicao
     * @param $codigoTabela
     * @param $anoFolha
     * @param $mesFolha
     * @return array
     * @throws Exception
     */
    public function buscarFaixas(Instituicao $instituicao, $codigoTabela, $anoFolha, $mesFolha)
    {
        if (empty($codigoTabela)) {
            throw new Exception('É necessário informar o código da tabela de previdência.');
        }

        $sql = "
                SELECT 
                       r33_codigo as sequencial,
                       r33_anousu as ano,
                       r33_mesusu as mes,
                       r33_codtab as codigoTabela,
                       r33_inic as \"valorInicial\",
                       r33_fim as \"valorFinal\",
                       r33_perc as percentual,
                       r33_deduzi as deduzir
                FROM inssirf
                WHERE r33_instit = {$instituicao->getCodigo()} 
                      AND r33_anousu = {$anoFolha}
                      and r33_mesusu = {$mesFolha}
                      AND r33_codtab = '{$codigoTabela}'
                ORDER BY r33_inic;
            ";

        $rs = db_query($sql);

        $faixas = array();

        while ($faixa = pg_fetch_object($rs)) {
            $faixas[] = $faixa;
        }
        return $faixas;
    }

    /**
     * @param cl_rhrubricas $daoRubricas
     * @param Instituicao $instituicao
     * @param int $codigoTabelaPrevidencia
     * @param int $codigoTipoAfastamento
     * @param int $ano
     * @param int $mes
     * @return ControleAfastamento[]
     * @throws BusinessException
     */
    public function buscaRubricasProporcionalizaveis(
        $daoRubricas,
        Instituicao $instituicao,
        $codigoTabelaPrevidencia,
        $codigoTipoAfastamento,
        $ano,
        $mes
    ) {
        $sql = $daoRubricas->sql_query_controleafastamento(
            $instituicao,
            $codigoTabelaPrevidencia,
            $codigoTipoAfastamento,
            $ano,
            $mes
        );
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar as rubricas que são proporcionalizaveis.');
        }

        $rubricas = array();

        while ($codigoRubrica = pg_fetch_object($rs)) {
            $rubricas[] = RubricaRepository::getInstanciaByCodigo($codigoRubrica->codigo, $instituicao->getCodigo());
        }

        return $rubricas;
    }

    /**
     * @param Instituicao $instituicao
     * @param int $tipoAfastamento
     * @param int $codigoTabelaPrevidencia
     * @param int $ano
     * @param int $mes
     * @return ControleAfastamento[]
     * @throws Exception
     */
    public function filtraRubricasPorAfastamentos(
        Instituicao $instituicao,
        $tipoAfastamento,
        $codigoTabelaPrevidencia,
        $ano,
        $mes
    ) {
        return $this->repositorio
            ->scopeTipoAfastamento($tipoAfastamento)
            ->scopeTabelaPrevidencia($codigoTabelaPrevidencia)
            ->scopeInstituicao($instituicao)
            ->scopeAno($ano)
            ->scopeMes($mes)
            ->get();
    }

    /**
     * @param int $afastamento
     * @param array $rubricas
     * @param int $codigoTabela
     * @param Instituicao $instituicao
     * @param int $ano
     * @param int $mes
     * @return bool
     * @throws Exception
     */
    public function vinculaRubricasAfastamento(
        $afastamento,
        array $rubricas,
        $codigoTabela,
        Instituicao $instituicao,
        $ano,
        $mes
    ) {

        $this->repositorio->removeVinculoAfastamento($afastamento, $codigoTabela, $instituicao, $ano, $mes);

        foreach ($rubricas as $rubrica) {
            $instanciaRubrica = RubricaRepository::getInstanciaByCodigo($rubrica->rubrica);

            $controleAfastamento = new ControleAfastamento();
            $controleAfastamento->setAfastamento($afastamento);
            $controleAfastamento->setRubrica($instanciaRubrica);
            $controleAfastamento->setTabelaPrevidencia($codigoTabela);
            $controleAfastamento->setInstituicao($instituicao);
            $controleAfastamento->setAno($ano);
            $controleAfastamento->setMes($mes);

            $this->repositorio->save($controleAfastamento);
        }

        return true;
    }

    /**
     * @param Instituicao $instituicao
     * @param int $ano
     * @param int $mes
     * @return array
     * @throws Exception
     */
    public function atualizaCompetenciaControleAfastamentos(Instituicao $instituicao, $ano, $mes)
    {
        $controleAfastamentos = $this->repositorio
            ->scopeInstituicao($instituicao)
            ->scopeAno($ano)
            ->scopeMes($mes)
            ->get();

        if ($mes == 12) {
            $mes = 1;
            $ano += 1;
        } else {
            $mes += 1;
        }

        $controleAfastamentosAtualizados = array_map(
            function (ControleAfastamento $controleAfastamento) use ($ano, $mes) {
                $controleAfastamento->setSequencial(null);
                $controleAfastamento->setAno($ano);
                $controleAfastamento->setMes($mes);
                return $controleAfastamento;
            },
            $controleAfastamentos
        );

        return $this->repositorio->saveAll($controleAfastamentosAtualizados);
    }

    /**
     * @param Instituicao $instituicao
     * @param int $ano
     * @param int $mes
     * @throws Exception
     */
    public function excluirControleAfastamentoPorCompetencia(Instituicao $instituicao, $ano, $mes)
    {
        $this->repositorio->excluirControleAfastamentoPorCompetencia($instituicao, $ano, $mes);
    }

    /**
     * @param Instituicao $instituicao
     * @param int $codigoTabela
     * @param int $ano
     * @param int $mes
     * @return ControleAfastamento[]
     * @throws Exception
     */
    public function buscaControleAfastamentosCalculo(Instituicao $instituicao, $codigoTabela, $ano, $mes)
    {
        return $this->repositorio
            ->scopeInstituicao($instituicao)
            ->scopeTabelaPrevidencia($codigoTabela)
            ->scopeAno($ano)
            ->scopeMes($mes)
            ->get();
    }
}
