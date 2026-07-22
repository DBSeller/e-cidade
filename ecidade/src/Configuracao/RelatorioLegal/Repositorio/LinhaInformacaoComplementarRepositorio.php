<?php

namespace ECidade\Configuracao\RelatorioLegal\Repositorio;

use cl_orcparamseqinfocomplementar;
use ECidade\Configuracao\RelatorioLegal\Modelo\InformacaoComplementarLancamento;
use ECidade\Configuracao\RelatorioLegal\Modelo\Linha;
use ECidade\Configuracao\RelatorioLegal\Modelo\LinhaInformacaoComplementar;
use ECidade\Configuracao\RelatorioLegal\Modelo\Relatorio;
use ECidade\Configuracao\RelatorioLegal\Registry\LinhaInformacaoComplementarRegistry;
use Exception;

/**
 * Class LinhaInformacaoComplementarRepositorio
 * @package ECidade\Configuracao\RelatorioLegal\Repositorio
 */
class LinhaInformacaoComplementarRepositorio extends Repositorio
{
    /**
     * @param int $key
     * @param array $columns
     * @return bool|LinhaInformacaoComplementar
     * @throws Exception
     */
    public static function find($key, $columns = array('*'))
    {
        $dao = new cl_orcparamseqinfocomplementar();
        $sql = $dao->sql_query($key, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar as informações complementares da linha.\nContate o suporte.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return LinhaInformacaoComplementar::fromState($resultado);
    }

    /**
     * @param LinhaInformacaoComplementar $linhaInformacaoComplementar
     * @param bool $force
     * @return LinhaInformacaoComplementar
     * @throws Exception
     */
    public static function save(LinhaInformacaoComplementar $linhaInformacaoComplementar, $force = false)
    {
        $dao = self::setDadosDao($linhaInformacaoComplementar);

        if ($linhaInformacaoComplementar->getSequencial() && $force) {
            $dao->incluir($linhaInformacaoComplementar->getSequencial());
        } else {
            $linhaInformacaoComplementar->getSequencial()
                ? $dao->alterar($linhaInformacaoComplementar->getSequencial())
                : $dao->incluir(null);
        }

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar as informações.\nContate o suporte.");
        }

        $linhaInformacaoComplementar->setSequencial($dao->o157_sequencial);

        return $linhaInformacaoComplementar;
    }

    /**
     * @param LinhaInformacaoComplementar $informacaoComplementar
     * @return cl_orcparamseqinfocomplementar
     */
    private static function setDadosDao(LinhaInformacaoComplementar $informacaoComplementar)
    {
        $dao = new cl_orcparamseqinfocomplementar();
        $dao->o157_sequencial = $informacaoComplementar->getSequencial();
        $dao->o157_valor = $informacaoComplementar->getValor();
        $dao->o157_conplanoinfocomplementar = $informacaoComplementar->getInformacaoComplementar();
        $dao->o157_relatorio = $informacaoComplementar->getRelatorio()->getSequencial();
        $dao->o157_linha = $informacaoComplementar->getLinha()->getLinha();
        $dao->o157_padrao = $informacaoComplementar->isPadrao();
        $dao->o157_infocomplementarlancamento = $informacaoComplementar->getInformacaoComplementarLancamento()
            ->getSequencial();

        return $dao;
    }

    /**
     * @param int $value
     * @param string $operator
     * @return $this
     */
    public function scopeSequencial($value, $operator = '=')
    {
        $this->scopes['o157_sequencial'] = "o157_sequencial {$operator} {$value}";
        return $this;
    }

    /**
     * @param string $value
     * @param string $operator
     * @return $this
     */
    public function scopeValor($value, $operator = '=')
    {
        $this->scopes['o157_valor'] = "o157_valor {$operator} '{$value}'";
        return $this;
    }

    /**
     * @param int $value
     * @param string $operator
     * @return $this
     */
    public function scopeInformacaoComplementar($value, $operator = '=')
    {
        $this->scopes['o157_conplanoinfocomplementar'] = "o157_conplanoinfocomplementar {$operator} {$value}";
        return $this;
    }

    /**
     * @param Relatorio $value
     * @param string $operator
     * @return $this
     */
    public function scopeRelatorio(Relatorio $value, $operator = '=')
    {
        $this->scopes['o157_relatorio'] = "o157_relatorio {$operator} {$value->getSequencial()}";
        return $this;
    }

    /**
     * @param Linha $value
     * @param string $operator
     * @return $this
     */
    public function scopeLinha(Linha $value, $operator = '=')
    {
        $this->scopes['o157_linha'] = "o157_linha {$operator} {$value->getLinha()}";
        return $this;
    }

    /**
     * @param string|array $value
     * @param string $operator
     * @return $this
     */
    public function scopeSigla($value, $operator = '=')
    {
        if (is_array($value)) {
            $value = implode('\', \'', $value);
            $value = $value ? "('{$value}')" : '';
        } else {
            $value = "'{$value}'";
        }

        if ($value) {
            $this->scopes['c121_sigla'] = "c121_sigla {$operator} {$value}";
        }

        return $this;
    }

    /**
     * @param bool $value
     * @param string $operator
     * @return LinhaInformacaoComplementarRepositorio
     */
    public function scopePadrao($value, $operator = 'IS')
    {
        $value = $value ? 'TRUE' : 'FALSE';
        $this->scopes['o157_padrao'] = "o157_padrao {$operator} {$value}";
        return $this;
    }

    /**
     * @param InformacaoComplementarLancamento $linhaInformacaoComplementarContaCorrente
     * @param string $operador
     * @return $this
     */
    public function scopeInformacaoComplementarLancamento(
        InformacaoComplementarLancamento $linhaInformacaoComplementarContaCorrente,
        $operador = '='
    ) {
        $this->scopes['o157_infocomplementarlancamento'] = "o157_infocomplementarlancamento {$operador} {$linhaInformacaoComplementarContaCorrente->getSequencial()}";
        return $this;
    }

    /**
     * @return LinhaInformacaoComplementar
     * @throws Exception
     */
    public function firstOrNew()
    {
        $configuracoes = $this->get();

        return count($configuracoes) > 0
            ? array_shift($configuracoes)
            : new LinhaInformacaoComplementar();
    }

    /**
     * @return LinhaInformacaoComplementar[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_orcparamseqinfocomplementar();
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $resultado = db_query($sql);

        if (!$resultado) {
            throw new Exception("Não foi possível buscar a configuração das linhas.\nContate o suporte.");
        }

        $configuracoes = array();

        if (pg_num_rows($resultado) === 0) {
            return $configuracoes;
        }

        while ($configuracao = pg_fetch_array($resultado)) {
            $configuracoes[] = LinhaInformacaoComplementar::fromState($configuracao);
        }

        return $configuracoes;
    }

    /**
     * @param LinhaInformacaoComplementar|null $linhaColunaValorInformacaoComplementar
     * @throws Exception
     */
    public function delete(LinhaInformacaoComplementar $linhaColunaValorInformacaoComplementar = null)
    {
        $id = $linhaColunaValorInformacaoComplementar instanceof LinhaInformacaoComplementar
            ? $linhaColunaValorInformacaoComplementar->getSequencial()
            : null;

        $dao = new cl_orcparamseqinfocomplementar();
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir a informação complementar.\nContate o suporte.");
        }
    }

    /**
     * @param LinhaInformacaoComplementar $informacaoComplementar
     * @return LinhaInformacaoComplementar
     * @throws Exception
     */
    public function import(LinhaInformacaoComplementar $informacaoComplementar)
    {
        $dao = self::setDadosDao($informacaoComplementar);
        LinhaInformacaoComplementarRegistry::get($informacaoComplementar->getSequencial())
            ? $dao->alterar($informacaoComplementar->getSequencial())
            : $dao->incluir($informacaoComplementar->getSequencial());

        if ($dao->erro_status == '0') {
            throw new Exception('Não foi possível importar uma informação complementar.');
        }

        return $informacaoComplementar;
    }

    /**
     * @return int
     * @throws Exception
     */
    public static function nextval()
    {
        $sql = "
            SELECT max(o157_sequencial) + 1 AS sequencial
            FROM orcparamseqinfocomplementar;
        ";
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o próximo sequencial.");
        }

        return pg_fetch_object($rs)->sequencial;
    }

    /**
     * @param int $value
     * @param bool $currval
     * @throws Exception
     */
    public static function setval($value, $currval = true)
    {
        $sql = "
            SELECT setval('orcparamseqinfocomplementar_o157_sequencial_seq', {$value}," . ($currval ? 'true' : 'false') . ");
        ";
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível definir o próximo sequencial.");
        }
    }
}
