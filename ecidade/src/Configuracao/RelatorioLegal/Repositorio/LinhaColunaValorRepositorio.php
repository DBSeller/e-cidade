<?php

namespace ECidade\Configuracao\RelatorioLegal\Repositorio;

use cl_orcparamseqorcparamseqcolunavalor;
use ECidade\Configuracao\RelatorioLegal\Modelo\Linha;
use ECidade\Configuracao\RelatorioLegal\Modelo\LinhaColuna;
use ECidade\Configuracao\RelatorioLegal\Modelo\LinhaColunaValor;
use Exception;

class LinhaColunaValorRepositorio extends Repositorio
{
    /**
     * @param LinhaColuna $linhaColuna
     * @param string $operator
     * @return LinhaColunaValorRepositorio
     */
    public function scopeLinhaColuna(LinhaColuna $linhaColuna, $operator = '=')
    {
        $this->scopes['linhaColuna'] = "o117_orcparamseqorcparamseqcoluna {$operator} {$linhaColuna->getSequencial()}";
        return $this;
    }

    /**
     * @param LinhaColunaValor|null $linhaColunaValor
     * @throws Exception
     */
    public function delete(LinhaColunaValor $linhaColunaValor = null)
    {
        $id = $linhaColunaValor instanceof LinhaColunaValor ? $linhaColunaValor->getSequencial() : null;

        $dao = new cl_orcparamseqorcparamseqcolunavalor();
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception(
                "Não foi possível excluir o valor do vínculo da coluna com a linha.\nContate o suporte."
            );
        }
    }

    /**
     * @param array $columns
     * @return LinhaColunaValor[]
     * @throws Exception
     */
    public function get($columns = array('*'))
    {
        $dao = new cl_orcparamseqorcparamseqcolunavalor();
        $sql = $dao->sql_query(null, implode(', ', $columns), '', implode(' AND ', $this->scopes));
        $resultado = db_query($sql);

        if (!$resultado) {
            throw new Exception("Não foi possível buscar as edições manuais da linha.\nContate o suporte.");
        }

        $registros = array();

        if (pg_num_rows($resultado) === 0) {
            return $registros;
        }

        while ($configuracao = pg_fetch_array($resultado)) {
            $registros[] = LinhaColunaValor::fromState($configuracao);
        }

        return $registros;
    }

    public function scopeExercicio($exercicio)
    {
        $this->scopes['exercicio'] = "o117_anousu = {$exercicio}";
        return $this;
    }

    /**
     * @param Linha $linha
     * @return $this
     */
    public function scopeLinha(Linha $linha)
    {
        $this->scopes['relatorio'] = "o116_codparamrel = {$linha->getRelatorio()->getSequencial()}";
        $this->scopes['linha'] = "o116_codseq = {$linha->getLinha()}";

        return $this;
    }

    public function scopeInstituicoes(array $instituicoes)
    {
        $this->scopes['instituicoes'] = sprintf(" o117_instit in (%s)", implode(', ', $instituicoes));
        return $this;
    }


    public function scopePeriodo($idPeriodo)
    {
        $this->scopes['periodo'] = "o116_periodo = {$idPeriodo}";
        return $this;
    }
}
