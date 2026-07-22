<?php
namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Repository;

use ECidade\Tributario\Library\DataBase;
use ECidade\Tributario\Library\DataBaseRepository;
use ECidade\Tributario\Caixa\Entity\Debito;

use \cl_arquivoautoatendimentotipocadtipo;
use \db_utils;

class TipoDebito extends DataBaseRepository
{
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function findByDebito(Debito $debito)
    {

        $daoArquivoautoatendimentotipocadtipo = new cl_arquivoautoatendimentotipocadtipo;
        $sql = $daoArquivoautoatendimentotipocadtipo->sql_query_arretipo($debito->getTipo());

        $rs = $this->database->execute($sql);

        if (!$rs) {
            throw new DBException("Ocorreu um erro ao consultar a base de dados.\n". pg_last_error());
        }

        if (pg_num_rows($rs) > 0) {
            return db_utils::fieldsMemory($rs, 0);
        }

        return null;
    }
}
