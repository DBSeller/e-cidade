<?php


namespace ECidade\Configuracao\RelatorioLegal\Repositorio;

use cl_orcparamseqfiltroorcamento;
use ECidade\Configuracao\RelatorioLegal\Modelo\ConfiguracaoUsuario;
use ECidade\Configuracao\RelatorioLegal\Modelo\Linha;

class ConfiguracaoUsuarioRepositorio extends Repositorio
{
    public function get()
    {
        $dao = new cl_orcparamseqfiltroorcamento();
        $sql = $dao->sql_query_file(null, '*', null, implode(' and ', $this->scopes));

        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Não foi possível buscar a configuração customizada.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        return ConfiguracaoUsuario::fromState(pg_fetch_array($rs));
    }

    /**
     * @param Linha $linha
     * @return $this
     */
    public function scopeLinha(Linha $linha)
    {
        $this->scopes['relatorio'] = "o133_orcparamrel = {$linha->getRelatorio()->getSequencial()}";
        $this->scopes['linha'] = "o133_orcparamseq = {$linha->getLinha()}";

        return $this;
    }

    /**
     * @param integer $exercicio
     * @return $this
     */
    public function scopeExercicio($exercicio)
    {
        $this->scopes['exercicio'] = "o133_anousu = {$exercicio}";
        return $this;
    }
}
