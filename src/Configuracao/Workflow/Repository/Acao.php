<?php

namespace ECidade\Configuracao\Workflow\Repository;

use ECidade\Lib\Database\DataBaseRepository;
use \DBException;
use \cl_transicaoacao;
use \db_utils;

class Acao extends DataBaseRepository
{

    /**
    * @param $atividadeDestino
    * @return \ECidade\Configuracao\Workflow\Collection\Acoes
    * @throws \BusinessException
    */
    public function getAcoes($filtro)
    {

        $atividadeOrigem  = $filtro->getAtividadeOrigem();
        $atividadeDestino = $filtro->getAtividadeDestino();

        if (!empty($atividadeOrigem) && !empty($atividadeDestino)) {
            $where = array(
                 "db174_origem  = {$atividadeOrigem->getCodigo()}"
                ,"db174_destino = {$atividadeDestino->getCodigo()}"
            );
            
            $dao             = new cl_transicaoacao();
            $sqlTransicao    = $dao->sql_query(null, " * ", "db176_sequencial", implode(' AND ', $where));
            $rsTransicao     = $this->dataBase->execute($sqlTransicao);
            
            if (!$rsTransicao) {
                $msgErro  = 'Erro ao buscar transição de atividade do workflow de: ';
                $msgErro .= $atividadeOrigem;
                $msgErro  = ' com destino em: ';
                $msgErro .= $atividadeDestino;
                $msgErro .= "\n". pg_last_error();
                
                throw new DBException($msgErro);
            }

            $totalAcoes = pg_num_rows($rsTransicao);
                
            return db_utils::makeCollectionFromRecord($rsTransicao, function ($retorno) {
                return$retorno;
            });
        }

        return array();
    }
}
