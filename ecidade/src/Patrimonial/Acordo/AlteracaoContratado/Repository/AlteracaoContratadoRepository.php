<?php

namespace ECidade\Patrimonial\Acordo\AlteracaoContratado\Repository;

use BusinessException;
use cl_acordoalteracaocontratado;
use db_utils;
use DBException;
use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Patrimonial\Acordo\AlteracaoContratado\Model\AlteracaoContratado;
use LicitanteLicitaCon;

class AlteracaoContratadoRepository extends Repository
{

    /**
     * @param $codigo
     * @return AlteracaoContratado|null
     */
    public function findByCodigo($codigo)
    {
        return $this->scopeCodigo($codigo)->first();
    }


    /**
     * @return AlteracaoContratado|null
     * @throws \Exception
     */
    public function first()
    {
        $alteracaoContratado = $this->get();
        if (empty($alteracaoContratado)) {
            return null;
        }
        return $alteracaoContratado[0];
    }

    /**
     * @return AlteracaoContratado|null
     * @throws \Exception
     */
    public function findLastByCodigoAcordo($codigo)
    {
        return $this->scopeAcordo($codigo)->last();
    }

    /**
     * @return false|mixed|null
     * @throws \Exception
     */
    public function last()
    {
        $alteracaoContratado = $this->get();
        if (empty($alteracaoContratado)) {
            return null;
        }
        return end($alteracaoContratado);
    }

    public function get()
    {
        $dao = new cl_acordoalteracaocontratado();
        $sql = $dao->sql_query_file('', '*', '', implode(' and ', $this->scopes));
        $rs = $dao->sql_record($sql);
        if (!$rs) {
            throw new \Exception('Erro ao buscar alterações do contratado');
        }
        $alteracaoContratado = [];
        while ($state = pg_fetch_array($rs)) {
            $alteracaoContratado[] = AlteracaoContratado::fromState($state);
        }

        return $alteracaoContratado;
    }

    /**
     * @param $posicao
     * @return array
     */
    public static function getHistoricoContratado($posicao)
    {
        $dados = [];

        $daoAlteracaoContratado = new cl_acordoalteracaocontratado;
        $sqlAnterior = $daoAlteracaoContratado->sql_query_contratado($posicao, 'ac60_anterior');
        $rsAnterior = $daoAlteracaoContratado->sql_record($sqlAnterior);
        if ($daoAlteracaoContratado->numrows > 0) {
            $resultAnterior = \db_utils::fieldsMemory($rsAnterior, 0);
            $dados['tipoDocumentoAnterior'] = LicitanteLicitaCon::getTipoDocumentoPorCGM($resultAnterior->z01_numcgm);
            if ($dados['tipoDocumentoAnterior'] === 'E') {
                $dados['numeroDocumentoAnterior'] = $resultAnterior->z09_documento;
            } else {
                $dados['numeroDocumentoAnterior'] = $resultAnterior->z01_cgccpf;
            }
        }

        $sqlNovo = $daoAlteracaoContratado->sql_query_contratado($posicao, 'ac60_novo');
        $rsNovo = $daoAlteracaoContratado->sql_record($sqlNovo);
        if ($daoAlteracaoContratado->numrows > 0) {
            $resultNovo = \db_utils::fieldsMemory($rsNovo, 0);
            $dados['tipoDocumentoNovo'] = LicitanteLicitaCon::getTipoDocumentoPorCGM($resultNovo->z01_numcgm);
            if ($dados['tipoDocumentoNovo'] === "E") {
                $dados['numeroDocumentoNovo'] = $resultNovo->z09_documento;
            } else {
                $dados['numeroDocumentoNovo'] = $resultNovo->z01_cgccpf;
            }
        }

        return $dados;
    }

    public function salvar(AlteracaoContratado $contratado)
    {
        if (!db_utils::inTransaction()) {
            throw new DBException("Transação com o banco de dados não encontrada");
        }

        $daoAlteracaoContratado = new cl_acordoalteracaocontratado;
        $daoAlteracaoContratado->ac60_acordo = $contratado->getCodigoAcordo();
        $daoAlteracaoContratado->ac60_posicao = $contratado->getPosicaoAcordo();
        $daoAlteracaoContratado->ac60_anterior = $contratado->getContratadoAnterior();
        $daoAlteracaoContratado->ac60_novo = $contratado->getContratadoNovo();
        $codigoAlteracaoContratado = $contratado->getCodigoAlteracaoContratado();

        if (!empty($codigoAlteracaoContratado)) {
            $daoAlteracaoContratado->ac60_sequencial = $contratado->getCodigoAlteracaoContratado();
            $daoAlteracaoContratado->alterar($contratado->getCodigoAlteracaoContratado());
        } else {
            $daoAlteracaoContratado->incluir(null);
            $contratado->setCodigoAlteracaoContratado($daoAlteracaoContratado->ac60_sequencial);
        }
    }

    public function excluir(AlteracaoContratado $contratado)
    {
        $daoAlteracaoContratado = new cl_acordoalteracaocontratado();
        $daoAlteracaoContratado->ac60_sequencial =  $contratado->getCodigoAlteracaoContratado();
        $daoAlteracaoContratado->excluir($daoAlteracaoContratado->ac60_sequencial);

        if ($daoAlteracaoContratado->erro_status == 0) {
            throw new BusinessException($daoAlteracaoContratado->erro_msg);
        }
    }

    public function scopeCodigo($codigo)
    {
        $this->scopes['codigo'] = "ac60_sequencial = {$codigo}";
        return $this;
    }

    public function scopeAcordo($codigoAcordo)
    {
        $this->scopes['acordo'] = "ac60_acordo = {$codigoAcordo}";
        return $this;
    }

    public function scopeAnterior($codigoAnterior)
    {
        $this->scopes['anterior'] = "ac60_anterior = {$codigoAnterior}";
        return $this;
    }
}
