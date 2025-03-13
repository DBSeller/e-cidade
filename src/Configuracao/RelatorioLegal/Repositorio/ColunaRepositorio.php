<?php

namespace ECidade\Configuracao\RelatorioLegal\Repositorio;

use cl_orcparamseqcoluna;
use ECidade\Configuracao\RelatorioLegal\Modelo\Coluna;
use ECidade\Configuracao\RelatorioLegal\Modelo\Relatorio;
use ECidade\Configuracao\RelatorioLegal\Registry\ColunaRegistry;
use Exception;

/**
 * Class ColunaRepositorio
 * @package ECidade\Configuracao\RelatorioLegal\Repositorio
 */
class ColunaRepositorio extends Repositorio
{
    /**
     * @var bool
     */
    protected $useJoin = false;

    /**
     * @param Relatorio $relatorio
     * @param string $operador
     * @return ColunaRepositorio
     */
    public function scopeRelatorio(Relatorio $relatorio, $operador = '=')
    {
        $this->scopes['o115_relatorio'] = "o115_relatorio {$operador} {$relatorio->getSequencial()}";
        return $this;
    }

    /**
     * @param $sequence
     * @param array $columns
     * @return Coluna|null
     * @throws Exception
     */
    public static function find($sequence, array $columns = array('*'))
    {
        $dao = new cl_orcparamseqcoluna();
        $sql = $dao->sql_query($sequence, implode(', ', $columns));
        $resultado = db_query($sql);

        if (!$resultado) {
            throw new Exception("Não foi possível buscar a coluna.\nContate o suporte.");
        }

        if (pg_num_rows($resultado) === 0) {
            return null;
        }

        $resultado = pg_fetch_array($resultado);

        return Coluna::fromState($resultado);
    }

    /**
     * @param Coluna $coluna
     * @return Coluna
     * @throws Exception
     */
    public static function save(Coluna $coluna)
    {
        $dao = new cl_orcparamseqcoluna();
        $dao->o115_sequencial = $coluna->getSequencial();
        $dao->o115_anousu = $coluna->getAno();
        $dao->o115_descricao = $coluna->getDescricao();
        $dao->o115_tipo = $coluna->getTipo();
        $dao->o115_valoresdefault = $coluna->getDefault();
        $dao->o115_nomecoluna = $coluna->getNome();
        $dao->o115_formula = $coluna->getFormula();
        $dao->o115_origem = $coluna->getOrigem();

        if ($coluna->getRelatorio() instanceof Relatorio) {
            $dao->o115_relatorio = $coluna->getRelatorio()->getSequencial();
        }

        $coluna->getSequencial()
            ? $dao->alterar($coluna->getSequencial())
            : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar as informações da coluna.\nContate o suporte.");
        }

        $coluna->setSequencial($dao->o115_sequencial);

        return $coluna;
    }

    /**
     * @param $ids
     * @return int
     * @throws Exception
     */
    public static function destroy($ids)
    {
        $count = 0;
        $ids = is_array($ids) ? $ids : func_get_args();

        $self = new self();

        foreach ($ids as $id) {
            $self->delete(ColunaRegistry::get($id));
            $count++;
        }

        return $count;
    }

    /**
     * @param Coluna $coluna
     * @throws Exception
     */
    public function delete(Coluna $coluna = null)
    {
        $id = $coluna instanceof Coluna ? $coluna->getSequencial() : null;

        $dao = new cl_orcparamseqcoluna();
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir a coluna.\nContate o suporte.");
        }
    }

    /**
     * @param Relatorio $relatorio
     * @return array
     * @throws Exception
     */
    public static function colunasPorRelatorio(Relatorio $relatorio)
    {
        $campos = "distinct orcparamseqcoluna.*";
        $where = "o116_codparamrel = {$relatorio->getSequencial()}";
        $dao = new cl_orcparamseqcoluna();
        $sql = $dao->sql_query_vinculo_relatorio(null, $campos, null, $where);
        $resultado = db_query($sql);

        if (!$resultado) {
            throw new Exception("Não foi possível buscar a coluna.\nContate o suporte.");
        }

        $colunas = array();

        if (pg_num_rows($resultado) === 0) {
            return $colunas;
        }

        $colunaEstruturalRepositorio = new ColunaEstruturalRepositorio();
        while ($retorno = pg_fetch_array($resultado)) {
            $coluna = Coluna::fromState($retorno);
            $coluna->setColunaEstruturais($colunaEstruturalRepositorio->scopeColuna($coluna)->get());

            $colunas[] = $coluna;
        }

        return $colunas;
    }

    /**
     * @param Coluna $coluna
     * @return Coluna
     * @throws Exception
     */
    public static function import(Coluna $coluna)
    {
        $dao = new cl_orcparamseqcoluna();
        $dao->o115_sequencial = $coluna->getSequencial();
        $dao->o115_anousu = $coluna->getAno();
        $dao->o115_descricao = $coluna->getDescricao();
        $dao->o115_tipo = $coluna->getTipo();
        $dao->o115_valoresdefault = $coluna->getDefault();
        $dao->o115_nomecoluna = $coluna->getNome();
        $dao->o115_formula = $coluna->getFormula();
        $dao->o115_origem = $coluna->getOrigem();

        if ($coluna->getRelatorio() != null) {
            $dao->o115_relatorio = $coluna->getRelatorio()->getSequencial();
        }

        ColunaRegistry::get($coluna->getSequencial())
            ? $dao->alterar($coluna->getSequencial())
            : $dao->incluir($coluna->getSequencial());

        if ($dao->erro_status == '0') {
            throw new Exception("Não foi possível importar as informações da coluna.");
        }

        return $coluna;
    }

    /**
     * @param bool $useJoin
     * @return ColunaRepositorio
     */
    public function setUseJoin($useJoin)
    {
        $this->useJoin = (bool)$useJoin;
        return $this;
    }

    /**
     * @param Relatorio $relatorio
     * @param string $operador
     * @return ColunaRepositorio
     */
    public function scopeRelatorioLinhaColuna(Relatorio $relatorio, $operador = '=')
    {
        $this->scopes['o116_codparamrel'] = "o116_codparamrel {$operador} {$relatorio->getSequencial()}";
        return $this;
    }

    /**
     * @param array $columns
     * @return Coluna[]
     * @throws Exception
     */
    public function get($columns = array('*'))
    {
        $dao = new cl_orcparamseqcoluna();

        if ($this->useJoin) {
            $dao->addJoin(
                'orcparamseqorcparamseqcoluna',
                'o116_orcparamseqcoluna',
                '=',
                'o115_sequencial'
            );
        }

        $sql = $dao->sql($columns, $this->scopes);
        $resultado = db_query($sql);

        if (!$resultado) {
            throw new Exception("Não foi possível buscar a configuração da coluna.\nContate o suporte.");
        }

        $colunas = array();

        if (pg_num_rows($resultado) === 0) {
            return $colunas;
        }

        while ($coluna = pg_fetch_array($resultado)) {
            $colunas[] = Coluna::fromState($coluna);
        }

        return $colunas;
    }
}
