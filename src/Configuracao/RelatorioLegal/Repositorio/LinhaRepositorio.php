<?php

namespace ECidade\Configuracao\RelatorioLegal\Repositorio;

use cl_orcparamseq;
use ECidade\Configuracao\RelatorioLegal\Modelo\ColunaEstrutural;
use ECidade\Configuracao\RelatorioLegal\Modelo\Linha;
use ECidade\Configuracao\RelatorioLegal\Modelo\LinhaColuna;
use ECidade\Configuracao\RelatorioLegal\Modelo\ConfiguracaoPadrao;
use ECidade\Configuracao\RelatorioLegal\Modelo\LinhaInformacaoComplementar;
use ECidade\Configuracao\RelatorioLegal\Modelo\Relatorio;
use ECidade\Configuracao\RelatorioLegal\Registry\LinhaRegistry;
use Exception;

/**
 * Class LinhaRepositorio
 * @package ECidade\Configuracao\RelatorioLegal\Repositorio
 */
class LinhaRepositorio extends Repositorio
{
    /**
     * @var array
     */
    protected $order = array();
    /**
     * @var bool
     */
    protected $useLeftJoin = true;

    /**
     * @param Relatorio $relatorio
     * @param int $id
     * @param array $columns
     * @return bool|Linha
     * @throws Exception
     */
    public static function find(Relatorio $relatorio, $id, $columns = array('*'))
    {
        $dao = new cl_orcparamseq();
        $sql = $dao->sql_query($relatorio->getSequencial(), $id, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a linha.\nContate o suporte.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $result = pg_fetch_array($rs);

        return Linha::fromState($result);
    }

    /**
     * @param Linha $linha
     * @return Linha
     * @throws Exception
     */
    public static function save(Linha $linha)
    {
        $dao = self::setDadosDao($linha);

        $sql = $dao->sql_query($linha->getRelatorio()->getSequencial(), $linha->getLinha());
        $rs = db_query($sql);

        pg_num_rows($rs) > 0
            ? $dao->alterar($linha->getRelatorio()->getSequencial(), $linha->getLinha())
            : $dao->incluir($linha->getRelatorio()->getSequencial(), $linha->getLinha());

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar as informações da linha.\nContate o suporte.");
        }
        return $linha;
    }

    /**
     * @param Linha $linha
     * @return cl_orcparamseq
     */
    public static function setDadosDao(Linha $linha)
    {
        $grupo = $linha->getGrupo();
        $grupoExclusao = $linha->getGrupoExclusao();
        $dao = new cl_orcparamseq();
        $dao->o69_codparamrel = $linha->getRelatorio()->getSequencial();
        $dao->o69_codseq = $linha->getLinha();
        $dao->o69_descr = $linha->getDescricao();
        $dao->o69_grupo = empty($grupo) ? '0' : $grupo;
        $dao->o69_grupoexclusao = empty($grupoExclusao) ? '0' : $grupoExclusao;
        $dao->o69_nivel = $linha->getNivel();
        $dao->o69_libnivel = $linha->isLiberaNivel() ? 't' : 'f';
        $dao->o69_librec = $linha->isLiberaRecurso() ? 't' : 'f';
        $dao->o69_libsubfunc = $linha->isLiberaSubFuncao() ? 't' : 'f';
        $dao->o69_libfunc = $linha->isLiberaFuncao() ? 't' : 'f';
        $dao->o69_verificaano = $linha->isVerificaAno() ? 't' : 'f';
        $dao->o69_labelrel = $linha->getLabel();
        $dao->o69_manual = $linha->isManual() ? 't' : 'f';
        $dao->o69_totalizador = $linha->isTotalizadora() ? 't' : 'f';
        $dao->o69_ordem = $linha->getOrdem();
        $dao->o69_nivellinha = $linha->getNivelLinha();
        $dao->o69_observacao = $linha->getObservacao();
        $dao->o69_desdobrarlinha = $linha->isDesdobra() ? 't' : 'f';
        $dao->o69_origem = $linha->getOrigem();

        return $dao;
    }

    /**
     * @param $ordem
     * @param string $operator
     * @return $this
     */
    public function scopeOrdem($ordem, $operator = '=')
    {
        $this->scopes['o69_ordem'] = "o69_ordem {$operator} {$ordem}";
        return $this;
    }

    public function scopeRelatorio(Relatorio $relatorio)
    {
        $this->scopes["o69_codparamrel"] = "o69_codparamrel = {$relatorio->getSequencial()}";
        return $this;
    }

    /**
     * @param $periodo
     * @param string $operator
     * @return $this
     */
    public function scopePeriodo($periodo, $operator = '=')
    {
        $this->scopes["o116_periodo"] = "o116_periodo {$operator} {$periodo}";
        return $this;
    }

    /**
     * @param $origem
     * @param string $operator
     * @return $this
     */
    public function scopeOrigem($origem, $operator = '=')
    {
        $this->scopes["o69_origem"] = "o69_origem {$operator} {$origem}";
        return $this;
    }

    /**
     * @param $linha
     * @param string $operator
     * @return $this
     */
    public function scopeLinha($linha, $operator = '=')
    {
        $this->scopes['o69_codseq'] = "o69_codseq {$operator} {$linha}";
        return $this;
    }

    /**
     * @return $this
     */
    public function scopeConfiguracao()
    {
        $sql = "
            NOT EXISTS(SELECT TRUE
            FROM orcparamseqinfocomplementar
            WHERE o157_linha = o69_codseq AND
                  o157_relatorio = o69_codparamrel)
        ";

        $this->scopes['configuracao'] = "({$sql} OR o157_padrao = (NOT EXISTS(SELECT TRUE
                                                                              FROM orcparamseqinfocomplementar
                                                                              WHERE o157_linha = o69_codseq AND
                                                                                    o157_relatorio = o69_codparamrel AND
                                                                                    o157_padrao IS FALSE)))";
        return $this;
    }

    /**
     * @return Linha|null
     * @throws Exception
     */
    public function first()
    {
        $registros = $this->get();

        return count($registros) > 0
            ? array_shift($registros)
            : null;
    }

    /**
     * @return Linha[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_orcparamseq();

        if ($this->useLeftJoin) {
            $dao->addLeftJoin('orcparamseqorcparamseqcoluna', 'o116_codseq', '=', 'o69_codseq')
                ->addLeftJoin('orcparamseqorcparamseqcoluna', 'o116_codparamrel', '=', 'o69_codparamrel')
                ->addLeftJoin('orcparamseqinfocomplementar', 'o157_linha', '=', 'o69_codseq')
                ->addLeftJoin('orcparamseqinfocomplementar', 'o157_relatorio', '=', 'o69_codparamrel')
                ->addLeftJoin('conplanoinfocomplementar', 'o157_conplanoinfocomplementar', '=', 'c121_sequencial')
                ->addLeftJoin('orcparamseqcolunaestruturais', 'o158_orcparamseqcoluna', '=', 'o116_orcparamseqcoluna')
                ->addLeftJoin('orcparamseqfiltropadrao', 'o132_orcparamrel', '=', 'o69_codparamrel')
                ->addLeftJoin('orcparamseqfiltropadrao', 'o132_orcparamseq', '=', 'o69_codseq');
        }

        $sql = $dao->sql(array('*'), $this->scopes, $this->order);

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar as linhas.\nContate o suporte.");
        }

        $linhas = array();
        $linhaColunas = array();
        if (pg_num_rows($rs) === 0) {
            return $linhas;
        }

        while ($retorno = pg_fetch_array($rs)) {
            if (array_key_exists($retorno['o69_ordem'], $linhas)) {
                $linha = $linhas[$retorno['o69_ordem']];
            } else {
                $linha = Linha::fromState($retorno);
            }

            if (isset($retorno['o116_sequencial'])) {
                if (array_key_exists($retorno['o116_sequencial'], $linhaColunas)) {
                    $linhaColuna = $linhaColunas[$retorno['o116_sequencial']];
                } else {
                    $linhaColuna = LinhaColuna::fromState($retorno);
                    $linhaColunas[$linhaColuna->getSequencial()] = $linhaColuna;
                }

                if (isset($retorno['o158_sequencial'])) {
                    $linhaColuna->getColuna()->addColunaEstrutural(ColunaEstrutural::fromState($retorno));
                }

                $linha->addLinhaColuna($linhaColuna);
            }

            if (isset($retorno['o157_sequencial'])) {
                $linha->addInformacaoComplementar(LinhaInformacaoComplementar::fromState($retorno));
            }

            if (isset($retorno['o132_sequencial'])) {
                $linha->addFiltroPadrao(ConfiguracaoPadrao::fromState($retorno));
            }

            $linhas[$linha->getOrdem()] = $linha;
        }

        return $linhas;
    }

    /**
     * @param Linha $linha
     * @return Linha
     * @throws Exception
     */
    public function import(Linha $linha)
    {
        $dao = self::setDadosDao($linha);
        LinhaRegistry::get($linha->getRelatorio(), $linha->getLinha())
            ? $dao->alterar($linha->getRelatorio()->getSequencial(), $linha->getLinha())
            : $dao->incluir($linha->getRelatorio()->getSequencial(), $linha->getLinha());

        if ($dao->erro_status == '0') {
            throw new Exception("Não foi possível importar as informações da linha.");
        }

        return $linha;
    }

    /**
     * @param Linha|null $linha
     * @throws Exception
     */
    public function delete(Linha $linha = null)
    {
        $codigoRelatorio = null;
        $codigoLinha = null;

        if ($linha instanceof Linha) {
            $codigoRelatorio = $linha->getRelatorio()->getSequencial();
            $codigoLinha = $linha->getLinha();
        }

        $dao = new cl_orcparamseq();
        $dao->excluir($codigoRelatorio, $codigoLinha, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir a linha.\nContate o suporte.\n{$dao->erro_banco}");
        }
    }

    /**
     * @param $order
     * @return LinhaRepositorio
     */
    public function addOrder($order)
    {
        $this->order[$order] = $order;
        return $this;
    }

    /**
     * @return bool
     */
    public function useLeftJoin()
    {
        return $this->useLeftJoin;
    }

    /**
     * @param bool $useLeftJoin
     * @return LinhaRepositorio
     */
    public function setUseLeftJoin($useLeftJoin)
    {
        $this->useLeftJoin = (bool)$useLeftJoin;
        return $this;
    }
}
