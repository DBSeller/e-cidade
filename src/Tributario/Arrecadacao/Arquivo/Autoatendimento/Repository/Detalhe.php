<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\Repository;

use ECidade\Tributario\Library\DataBase;
use ECidade\Tributario\Library\DataBaseRepository;

use cl_arquivoautoatendimentoregistros;
use DBException;
use db_utils;
use stdClass;

class Detalhe extends DataBaseRepository {

    private $database;

    public function __construct(Database $database) {
        $this->database = $database;
    }

    public function findByNumnov($numnov)
    {
        $dao = new cl_arquivoautoatendimentoregistros();
        $sql = $dao->sql_query_file(null, "*", null, "k183_numnov = {$numnov}");
        $rs = $this->database->execute($sql);

        if(!$rs) {
            throw new DBException("Ocorreu um erro ao consultar a base de dados. \n". pg_last_error());
        }

        if(pg_num_rows($rs) > 1) {
            throw new BusinessException("Há mais de um registro para o numpre.");
        }

        if(pg_num_rows($rs) > 0) {
            return db_utils::fieldsMemory($rs, 0);
        }

        return null;
    }

    public function persist(stdClass $params)
    {
        $daoArquivoautoatendimentoregistros = new cl_arquivoautoatendimentoregistros();
        $daoArquivoautoatendimentoregistros->k183_codigo          = $params->k183_codigo;
        $daoArquivoautoatendimentoregistros->k183_autoatendimento = $params->k183_autoatendimento;
        $daoArquivoautoatendimentoregistros->k183_tipodebito      = $params->k183_tipodebito;
        $daoArquivoautoatendimentoregistros->k183_situacao        = $params->k183_situacao;
        $daoArquivoautoatendimentoregistros->k183_numnov          = $params->k183_numnov;

        $acao = 'incluir';

        if(!empty($params->k183_codigo)) {
            
            $acao = 'alterar';
        }

        if(!$daoArquivoautoatendimentoregistros->{$acao}($params->k183_codigo)) {
            throw new DBException($daoArquivoautoatendimentoregistros->erro_msg);
        }

        return $daoArquivoautoatendimentoregistros;
    }
}