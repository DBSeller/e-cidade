<?php

namespace ECidade\Configuracao\RelatorioLegal\Repositorio;

use cl_orcparamseqinfocomplementarlancamento;
use ECidade\Configuracao\RelatorioLegal\Modelo\InformacaoComplementarLancamento;
use ECidade\Configuracao\RelatorioLegal\Modelo\Relatorio;
use ECidade\Configuracao\RelatorioLegal\Modelo\Linha;
use ECidade\Configuracao\RelatorioLegal\Registry\InformacaoComplementarLancamentoRegistry;
use Exception;

/**
 * Class InformacaoComplementarLancamentoRepositorio
 * @package ECidade\Configuracao\RelatorioLegal\Repositorio
 */
class InformacaoComplementarLancamentoRepositorio extends Repositorio
{
    /**
     * @var bool
     */
    protected $useJoin = false;

    /**
     * @param InformacaoComplementarLancamento $informacaoComplementarLancamento
     * @return cl_orcparamseqinfocomplementarlancamento
     */
    private static function setDadosDao(InformacaoComplementarLancamento $informacaoComplementarLancamento)
    {
        $dao = new cl_orcparamseqinfocomplementarlancamento();
        $dao->o102_sequencial = $informacaoComplementarLancamento->getSequencial();
        $dao->o102_exclusao = $informacaoComplementarLancamento->isExclusao() ? 't' : 'f';
        return $dao;
    }

    /**
     * @param int $key
     * @param array $columns
     * @return bool|InformacaoComplementarLancamento
     * @throws Exception
     */
    public static function find($key, $columns = array('*'))
    {
        $dao = new cl_orcparamseqinfocomplementarlancamento();
        $sql = $dao->sql_query($key, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar as informações complementares da linha.\nContate o suporte.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return InformacaoComplementarLancamento::fromState($resultado);
    }

    /**
     * @param InformacaoComplementarLancamento $informacaoComplementarLancamento
     * @param bool $force
     * @return InformacaoComplementarLancamento
     * @throws Exception
     */
    public static function save(InformacaoComplementarLancamento $informacaoComplementarLancamento, $force = false)
    {
        $dao = static::setDadosDao($informacaoComplementarLancamento);

        if ($informacaoComplementarLancamento->getSequencial() && $force) {
            $dao->incluir($informacaoComplementarLancamento->getSequencial());
        } else {
            $informacaoComplementarLancamento->getSequencial()
                ? $dao->alterar($informacaoComplementarLancamento->getSequencial())
                : $dao->incluir(null);
        }

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar as informações complementares.\nContate o suporte.");
        }

        $informacaoComplementarLancamento->setSequencial($dao->o102_sequencial);

        return $informacaoComplementarLancamento;
    }

    /**
     * @param InformacaoComplementarLancamento $informacaoComplementarLancamento
     * @throws Exception
     */
    public function delete(InformacaoComplementarLancamento $informacaoComplementarLancamento)
    {
        $id = $informacaoComplementarLancamento instanceof InformacaoComplementarLancamento
            ? $informacaoComplementarLancamento->getSequencial()
            : null;

        $dao = new cl_orcparamseqinfocomplementarlancamento();
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir a informação complementar.\nContate o suporte.");
        }
    }

    /**
     * @param array $columns
     * @return InformacaoComplementarLancamento[]
     * @throws Exception
     */
    public function get($columns = array('*'))
    {
        $dao = new cl_orcparamseqinfocomplementarlancamento();

        if ($this->useJoin) {
            $dao->addJoin(
                'orcparamseqinfocomplementar',
                'o157_infocomplementarlancamento',
                '=',
                'o102_sequencial'
            );
        }

        $sql = $dao->sql($columns, $this->scopes);
        $resultado = db_query($sql);

        if (!$resultado) {
            throw new Exception("Não foi possível buscar a configuração das linhas.\nContate o suporte.");
        }

        $configuracoes = array();

        if (pg_num_rows($resultado) === 0) {
            return $configuracoes;
        }

        while ($configuracao = pg_fetch_array($resultado)) {
            $configuracoes[] = InformacaoComplementarLancamento::fromState($configuracao);
        }

        return $configuracoes;
    }

    /**
     * @param bool $useJoin
     * @return InformacaoComplementarLancamentoRepositorio
     */
    public function setUseJoin($useJoin)
    {
        $this->useJoin = (bool)$useJoin;
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
     * @return int
     * @throws Exception
     */
    public static function nextval()
    {
        $sql = "
            SELECT max(o102_sequencial) + 1 AS sequencial
            FROM orcparamseqinfocomplementarlancamento;
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
            SELECT setval('orcparamseqinfocomplementarlancamento_o102_sequencial_seq', {$value}," . ($currval ? 'true' : 'false') . ");
        ";
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível definir o próximo sequencial.");
        }
    }

    /**
     * @param InformacaoComplementarLancamento $informacaoComplementarLancamento
     * @return InformacaoComplementarLancamento
     * @throws Exception
     */
    public static function import(InformacaoComplementarLancamento $informacaoComplementarLancamento)
    {
        $dao = static::setDadosDao($informacaoComplementarLancamento);

        InformacaoComplementarLancamentoRegistry::get($informacaoComplementarLancamento->getSequencial())
            ? $dao->alterar($informacaoComplementarLancamento->getSequencial())
            : $dao->incluir($informacaoComplementarLancamento->getSequencial());

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível importar os vinculos entre linha e coluna.");
        }

        return $informacaoComplementarLancamento;
    }
}
