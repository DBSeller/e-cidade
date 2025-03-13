<?php
/**
 * Created by PhpStorm.
 * User: robson
 * Date: 2020-02-05
 * Time: 17:04
 */

namespace ECidade\Financeiro\Contabilidade\Relatorio\TCE\RO\IN22;

abstract class Base
{

    const CODIGO_RELATORIO = 0;

    /**
     * Codigo do periodo contabil
     * @var integer
     */
    protected $codigoPeriodo;

    /**
     * Ano de emissao do relatorio
     * @var integer
     */
    protected $ano;

    /**
     * Usuário emissor
     * @var integer
     */
    protected $usuario;

    /**
     * Array com  a lista de instituicoes de emissao
     * @var array
     */
    protected $instituicoes = [];

    /**
     * @var \relatorioContabil
     */
    protected $relatorioLegal;

    /**
     * @var \DBDate
     */
    protected $dataEmissao;

    /**
     * @return \relatorioContabil
     */
    public function getRelatorioLegal()
    {
        $this->relatorioLegal = new \relatorioContabil(
            static::CODIGO_RELATORIO,
            false
        );
        return $this->relatorioLegal;
    }

    /**
     * @param $codigoPeriodo
     * @return mixed
     */
    public function setPeriodo($codigoPeriodo)
    {
        $this->codigoPeriodo = $codigoPeriodo;
    }

    /**
     * @param $ano
     * @return mixed
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    /**
     * @param array $instituicoes
     * @return mixed
     */
    public function setInstituicoes(array $instituicoes)
    {
        $this->instituicoes = $instituicoes;
    }

    /**
     * @return int
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param int $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * retorna as regras para emissao dos relatorios da IN22
     * @param $relatorio
     * @param $ano
     *
     * @return mixed
     */
    public static function getRegrasEmissaoRelatorio($relatorio)
    {

        $dePara = array(
            1 => ["codigo" => 206, "filtro" => 'legal'],
            2 => ["codigo" => 207, "filtro" => 'despesa'],
            3 => ["codigo" => 208, "filtro" => 'despesa'],
            4 => ["codigo" => 209, "filtro" => 'despesa'],
            5 => ["codigo" => 210, "filtro" => 'despesa'],
            6 => ["codigo" => 211, "filtro" => 'despesa'],
            7 => ["codigo" => 212, "filtro" => 'legal'],
            8 => ["codigo" => 213, "filtro" => 'despesa'],
            9 => ["codigo" => 214, "filtro" => 'despesa'],
            10 => ["codigo" => 215, "filtro" => 'despesa'],
            '10a' => ["codigo" => Anexo10A::CODIGO_RELATORIO, "filtro" => 'despesa'],
            11 => ["codigo" => Anexo11::CODIGO_RELATORIO, "filtro" => 'despesa'],
            '11a' => ["codigo" => Anexo11A::CODIGO_RELATORIO, "filtro" => 'legal'],
            '11b' => ["codigo" => Anexo11B::CODIGO_RELATORIO, "filtro" => 'despesa'],
            '11c' => ["codigo" => Anexo11C::CODIGO_RELATORIO, "filtro" => 'legal'],
            12 => ["codigo" => 227, "filtro" => 'legal'],
            13 => ["codigo" => 228, "filtro" => 'despesa'],
            '13a' => ["codigo" => Anexo13A::CODIGO_RELATORIO, "filtro" => 'despesa'],
            14 => ["codigo" => 229, "filtro" => 'despesa'],
            15 => ["codigo" => 230, "filtro" => 'despesa'],
            16 => ["codigo" => 231, "filtro" => 'despesa'],
        );
        return $dePara[$relatorio];
    }

    /**
     * Retorna o where com os filtros configurados
     * @return string
     */
    public function getFiltrosConfigurados()
    {
        $filtros = $this->getRelatorioLegal()->getParametrosUsuario($this->getUsuario());
        return $this->getWhereFiltro($filtros);
    }

    /**
     * @param $oDadosFiltro
     * @return string
     */
    protected function getWhereFiltro($oDadosFiltro)
    {
        $sWhereDespesa = "";
        /**
         * Verifica se Tem filtro por Orgão
         */
        if (count($oDadosFiltro->orgao->valor) > 0) {
            if ($oDadosFiltro->orgao->operador == 'notin') {
                $oDadosFiltro->orgao->operador = "not in";
            }
            $sWhereDespesa .= " and o58_orgao {$oDadosFiltro->orgao->operador} ("
                . implode(",", $oDadosFiltro->orgao->valor) . ")";
        }

        /**
         * Verifica se Tem filtro por Unidade
         */
        if (count($oDadosFiltro->unidade->valor) > 0) {
            if ($oDadosFiltro->unidade->operador == 'notin') {
                $oDadosFiltro->unidade->operador = "not in";
            }
            $sWhereDespesa .= " and o58_unidade {$oDadosFiltro->unidade->operador} ("
                . implode(",", $oDadosFiltro->unidade->valor) . ")";
        }

        /**
         * Verifica se Tem filtro por Função
         */
        if (count($oDadosFiltro->funcao->valor) > 0) {
            if ($oDadosFiltro->funcao->operador == 'notin') {
                $oDadosFiltro->funcao->operador = "not in";
            }
            $sWhereDespesa .= " and o58_funcao {$oDadosFiltro->funcao->operador} ("
                . implode(",", $oDadosFiltro->funcao->valor) . ")";
        }

        /**
         * Verifica se Tem filtro por SubFunção
         */
        if (count($oDadosFiltro->subfuncao->valor) > 0) {
            if ($oDadosFiltro->subfuncao->operador == 'notin') {
                $oDadosFiltro->subfuncao->operador = "not in";
            }
            $sWhereDespesa .= " and o58_subfuncao {$oDadosFiltro->subfuncao->operador} ("
                . implode(",", $oDadosFiltro->subfuncao->valor) . ")";
        }

        /**
         * Verifica se Tem filtro por Programa
         */
        if (count($oDadosFiltro->programa->valor) > 0) {
            if ($oDadosFiltro->programa->operador == 'notin') {
                $oDadosFiltro->programa->operador = "not in";
            }
            $sWhereDespesa .= " and o58_programa {$oDadosFiltro->programa->operador} ("
                . implode(",", $oDadosFiltro->programa->valor) . ")";
        }

        /**
         * Verifica se Tem filtro por Projeto
         */
        if (count($oDadosFiltro->projativ->valor) > 0) {
            if ($oDadosFiltro->projativ->operador == 'notin') {
                $oDadosFiltro->projativ->operador = "not in";
            }
            $sWhereDespesa .= " and o58_projativ {$oDadosFiltro->projativ->operador} ("
                . implode(",", $oDadosFiltro->projativ->valor) . ")";
        }

        /**
         * Verifica se Tem filtro por Elemento
         */
        if (count($oDadosFiltro->elemento->valor) > 0) {
            if ($oDadosFiltro->elemento->operador == 'notin') {
                $oDadosFiltro->elemento->operador = "not in";
            }
            $sWhereDespesa .= " and o56_elemento::bigint {$oDadosFiltro->elemento->operador} ("
                . implode(",", $oDadosFiltro->elemento->valor) . ")";
        }

        /**
         * Verifica se Tem filtro por recurso
         */
        if (count($oDadosFiltro->recurso->valor) > 0) {
            if ($oDadosFiltro->recurso->operador == 'notin') {
                $oDadosFiltro->recurso->operador = "not in";
            }
            $sWhereDespesa .= " and o58_codigo {$oDadosFiltro->recurso->operador} ("
                . implode(",", $oDadosFiltro->recurso->valor) . ")";
        }

        return $sWhereDespesa;
    }

    /**
     * @return \DBDate
     */
    public function getDataEmissao()
    {
        return $this->dataEmissao;
    }

    /**
     * @param \DBDate $dataEmissao
     */
    public function setDataEmissao(\DBDate $dataEmissao)
    {
        $this->dataEmissao = $dataEmissao;
    }

    /**
     * @param $valor
     * @return string
     */
    public function formataValor($valor)
    {
        return number_format($valor, 2, ',', '.');
    }
}
