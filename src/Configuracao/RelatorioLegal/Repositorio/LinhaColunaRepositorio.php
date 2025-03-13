<?php

namespace ECidade\Configuracao\RelatorioLegal\Repositorio;

use cl_orcparamseqorcparamseqcoluna;
use ECidade\Configuracao\RelatorioLegal\Modelo\Coluna;
use ECidade\Configuracao\RelatorioLegal\Modelo\Linha;
use ECidade\Configuracao\RelatorioLegal\Modelo\LinhaColuna;
use ECidade\Configuracao\RelatorioLegal\Modelo\Relatorio;
use ECidade\Configuracao\RelatorioLegal\Registry\LinhaRegistry;
use Exception;

/**
 * Class LinhaColunaRepositorio
 * @package ECidade\Configuracao\RelatorioLegal\Repositorio
 */
class LinhaColunaRepositorio extends Repositorio
{
    /**
     * @param LinhaColuna $linhaColuna
     * @return LinhaColuna
     * @throws Exception
     */
    public static function save(LinhaColuna $linhaColuna)
    {
        $dao = self::setDadosDao($linhaColuna);

        $linhaColuna->getSequencial()
            ? $dao->alterar($linhaColuna->getSequencial())
            : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            $msg = "N?o foi poss?vel salvar as informa??es de v?nculo entre linha e coluna.\nContate o suporte.";
            throw new Exception($msg);
        }
        return $linhaColuna;
    }

    /**
     * @param LinhaColuna $linhaColuna
     * @return cl_orcparamseqorcparamseqcoluna
     */
    private static function setDadosDao(LinhaColuna $linhaColuna)
    {
        $dao = new cl_orcparamseqorcparamseqcoluna();
        $dao->o116_sequencial  = $linhaColuna->getSequencial();
        $dao->o116_codparamrel = $linhaColuna->getRelatorio()->getSequencial();
        $dao->o116_codseq      = $linhaColuna->getLinha()->getLinha();
        $dao->o116_orcparamseqcoluna = $linhaColuna->getColuna()->getSequencial();
        $dao->o116_ordem   = $linhaColuna->getOrdem();
        $dao->o116_periodo = $linhaColuna->getPeriodo();
        $dao->o116_formula = addslashes($linhaColuna->getFormula());

        return $dao;
    }

    /**
     * @param Relatorio $relatorio
     * @param array $codigoLinhas
     * @throws Exception
     */
    public static function atualizaFormulaLinhas(Relatorio $relatorio, array $codigoLinhas)
    {
        $linhaRepositorio = new LinhaRepositorio();
        foreach ($codigoLinhas as $index => $codigoLinha) {
            $ordemNova = $index + 1;
            $linhas = $linhaRepositorio->scopeOrdem($ordemNova)->scopeRelatorio($relatorio)->get();
            if (count($linhas) > 1) {
                throw new Exception("Existe mais de uma linha com a mesma ordem no relat?rio.\nContate o suporte.");
            }

            if (count($linhas) === 0) {
                throw new Exception("Linha com ordem inconsistente.\nContate o suporte.");
            }

            $linha = array_pop($linhas);
            $linhaComparativa = LinhaRegistry::get($relatorio, $codigoLinha);
            $ordemAnterior = $linhaComparativa->getOrdem();

            if ($linhaComparativa->getOrdem() !== $linha->getOrdem()) {
                self::atualizaFormulaLinha($relatorio, $ordemAnterior, $ordemNova);
            }
        }

        self::normalizaFormulas($relatorio);
        self::atualizaOrdemLinhas($relatorio, $codigoLinhas);
    }

    /**
     * @param Relatorio $relatorio
     * @param $ordemAnterior
     * @param $ordemNova
     * @return bool
     * @throws Exception
     */
    private static function atualizaFormulaLinha(Relatorio $relatorio, $ordemAnterior, $ordemNova)
    {
        $linhaColunaRepositorio = new LinhaColunaRepositorio();
        $linhaColunas = $linhaColunaRepositorio->scopeRelatorio($relatorio)
            ->scopeFormula("%L[{$ordemAnterior}]%", 'ILIKE')
            ->get(array(
                'o116_codseq',
                'o116_codparamrel',
                'o116_orcparamseqcoluna',
                'o116_ordem',
                'o116_formula'
            ));

        if (count($linhaColunas) === 0) {
            return false;
        }

        $linhaAnterior = "L[{$ordemAnterior}]";
        $auxNova = "L[AUX_{$ordemNova}]";

        foreach ($linhaColunas as $linhaColuna) {
            $formula = str_replace($linhaAnterior, $auxNova, $linhaColuna->getFormula());

            $where = array(
                "o116_codseq = {$linhaColuna->getLinha()->getLinha()}",
                "o116_codparamrel = {$relatorio->getSequencial()}",
                "o116_orcparamseqcoluna = {$linhaColuna->getColuna()->getSequencial()}"
            );

            $dao = static::setDadosDao($linhaColuna);
            $dao->o116_formula = $formula;
            $dao->alterar_where(null, implode(' AND ', $where));
        }

        return true;
    }

    /**
     * @param Relatorio $relatorio
     * @return bool
     * @throws Exception
     */
    private static function normalizaFormulas(Relatorio $relatorio)
    {
        $sql = "update orcparamseqorcparamseqcoluna
                set o116_formula = replace(o116_formula, '[AUX_', '[')
                where o116_codparamrel = {$relatorio->getSequencial()}
                and o116_formula ilike '%L[AUX_%]%';";

        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("N?o foi poss?vel normalizar as f?rmulas.\nContate o suporte.");
        }
        return true;
    }

    /**
     * @param Relatorio $relatorio
     * @param array $codigoLinhas
     * @throws Exception
     */
    private static function atualizaOrdemLinhas(Relatorio $relatorio, array $codigoLinhas)
    {
        foreach ($codigoLinhas as $index => $codigoLinha) {
            $linha = LinhaRegistry::get($relatorio, $codigoLinha);
            $dao = LinhaRepositorio::setDadosDao($linha);
            $dao->o69_codseq = $codigoLinha;
            $dao->o69_ordem = $index + 1;
            $dao->alterar($relatorio->getSequencial(), $codigoLinha);
        }
//        for ($i = 0; $i < count($codigoLinhas); $i++) {
//            $linha = LinhaRegistry::get($relatorio, $codigoLinhas[$i]);
//
//            $dao = LinhaRepositorio::setDadosDao($linha);
//            $dao->o69_codseq = $codigoLinhas[$i];
//            $dao->o69_ordem = $i + 1;
//            $dao->alterar($relatorio->getSequencial(), $codigoLinhas[$i]);
//        }
    }

    /**
     * @param $value
     * @param string $operator
     * @return $this
     */
    public function scopeSequencial($value, $operator = '=')
    {
        $this->scopes['o116_sequencial'] = "o116_sequencial {$operator} {$value}";
        return $this;
    }

    /**
     * @param Linha $value
     * @param string $operator
     * @return $this
     */
    public function scopeLinha(Linha $value, $operator = '=')
    {
        $this->scopes['o116_codseq'] = "o116_codseq {$operator} {$value->getLinha()}";
        return $this;
    }

    /**
     * @param Relatorio $value
     * @param string $operator
     * @return $this
     */
    public function scopeRelatorio(Relatorio $value, $operator = '=')
    {
        $this->scopes['o116_codparamrel'] = "o116_codparamrel {$operator} {$value->getSequencial()}";
        return $this;
    }

    /**
     * @param Coluna $value
     * @param string $operator
     * @return $this
     */
    public function scopeColuna(Coluna $value, $operator = '=')
    {
        $this->scopes['o116_orcparamseqcoluna'] = "o116_orcparamseqcoluna {$operator} {$value->getSequencial()}";
        return $this;
    }

    /**
     * @param $value
     * @param string $operator
     * @return $this
     */
    public function scopeOrdem($value, $operator = '=')
    {
        $this->scopes['o116_ordem'] = "o116_ordem {$operator} {$value}";
        return $this;
    }

    /**
     * @param $value
     * @param string $operator
     * @return $this
     */
    public function scopePeriodo($value, $operator = '=')
    {
        $this->scopes['o116_periodo'] = "o116_periodo {$operator} {$value}";
        return $this;
    }

    /**
     * @param $value
     * @param string $operator
     * @return $this
     */
    public function scopeFormula($value, $operator = '=')
    {
        $this->scopes['o116_formula'] = "o116_formula {$operator} '{$value}'";
        return $this;
    }

    /**
     * @return LinhaColuna|mixed|null
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
     * @param array $columns
     * @return LinhaColuna[]
     * @throws Exception
     */
    public function get($columns = array('*'))
    {
        $dao = new cl_orcparamseqorcparamseqcoluna();
        $sql = $dao->sql_query(null, implode(', ', $columns), 'o116_ordem', implode(' AND ', $this->scopes));
        $resultado = db_query($sql);

        if (!$resultado) {
            throw new Exception("N?o foi poss?vel buscar a configura??o das linhas.\nContate o suporte.");
        }

        $registros = array();

        if (pg_num_rows($resultado) === 0) {
            return $registros;
        }

        while ($configuracao = pg_fetch_array($resultado)) {
            $registros[] = LinhaColuna::fromState($configuracao);
        }

        return $registros;
    }

    /**
     * @param LinhaColuna|null $linhaColuna
     * @throws Exception
     */
    public function delete(LinhaColuna $linhaColuna = null)
    {
        $id = $linhaColuna instanceof LinhaColuna ? $linhaColuna->getSequencial() : null;

        $daoValor = new \cl_orcparamseqorcparamseqcolunavalor();
        $daoValor->excluir(null, "o117_orcparamseqorcparamseqcoluna = {$id}");
        if ($daoValor->erro_status === "0") {
            throw new Exception("N?o foi poss?vel excluir os valores manuais lan?ados para a linha.");
        }

        $dao = new cl_orcparamseqorcparamseqcoluna();
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            $msg  = "N?o foi poss?vel excluir o v?nculo da coluna com a linha. Poss?vel erro:\n";
            $msg .= "Existe valor configurado para a coluna que est? sendo exclu?da.\n\n";
            $msg .= "Se o problema persistir, contate o suporte.   -> ".pg_last_error();
            throw new Exception($msg);
        }
    }

    /**
     * @param LinhaColuna $linhaColuna
     * @return LinhaColuna
     * @throws Exception
     */
    public function import(LinhaColuna $linhaColuna)
    {
        $dao = self::setDadosDao($linhaColuna);
        self::find($linhaColuna->getSequencial())
            ? $dao->alterar($linhaColuna->getSequencial())
            : $dao->incluir($linhaColuna->getSequencial());

        if ($dao->erro_status == 0) {
            throw new Exception("N?o foi poss?vel importar os vinculos entre linha e coluna.");
        }

        return $linhaColuna;
    }

    /**
     * @param int $sequence
     * @param array $columns
     * @return null|LinhaColuna
     * @throws Exception
     */
    public static function find($sequence, array $columns = array('*'))
    {
        $dao = new cl_orcparamseqorcparamseqcoluna();
        $sql = $dao->sql_query($sequence, implode(', ', $columns));
        $resultado = db_query($sql);

        if (!$resultado) {
            throw new Exception("N?o foi poss?vel buscar o v?nculo da linha com a coluna.\nContate o suporte.");
        }

        if (pg_num_rows($resultado) === 0) {
            return null;
        }

        $resultado = pg_fetch_array($resultado);

        return LinhaColuna::fromState($resultado);
    }
}
