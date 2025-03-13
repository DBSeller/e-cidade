<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Tributario\Juridico\Inicial\Repository;

use Exception;

/**
 * Class AbstractQuery
 * @package ECidade\Tributario\Juridico\Inicial\Repository
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 * @author Stephano Ramos <stephano.ramos@dbseller.com.br>
 */
abstract class AbstractQuery
{
    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    private $where = array();

    /**
     * @var array
     */
    private $orWhere = array();

    /**
     * @var array
     */
    private $join = array();

    /**
     * @var string
     */
    private $from = '';

    /**
     * @var string
     */
    private $select = '*';

    /**
     * @var integer
     */
    private $limit;

    /**
     * @param $field
     * @param $operator
     * @param $value
     * @return $this
     */
    final public function where($field, $operator, $value)
    {
        $this->where[] = "{$field} {$operator} {$value}";
        return $this;
    }

    /**
     * @param $field
     * @param $operator
     * @param $value
     * @return $this
     */
    final public function orWhere($field, $operator, $value)
    {
        $this->orWhere[] = "{$field} {$operator} {$value}";
        return $this;
    }

    /**
     * @param $field
     * @param array $values
     * @return $this
     */
    final public function whereIn($field, array $values)
    {
        $values = implode(', ', $values);

        $this->where[] = "{$field} IN ({$values})";

        return $this;
    }

    /**
     * @param $table
     * @param $self
     * @param $operator
     * @param $foreign
     * @return $this
     */
    final public function join($table, $self, $operator, $foreign)
    {
        if (empty($this->join)) {
            $this->from = "FROM {$table}";
            $this->where($self, $operator, $foreign);
        }
        return $this;
    }

    final public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param $fields
     * @return $this
     */
    final public function select($fields)
    {
        $this->select = implode(',', $fields);

        return $this;
    }

    /**
     * @param array $fields
     * @return bool
     * @throws Exception
     */
    final public function update(array $fields)
    {
        $sets = array();

        foreach ($fields as $field => $value) {
            $sets[] = "{$field} = {$value}";
            $this->log($field, $value, 'A');
        }

        $sets = implode(', ', $sets);

        $query = "UPDATE {$this->table} SET {$sets} {$this->from} {$this->buildWhere()}";

        $result = db_query($query);

        if (!$result) {
            throw new Exception("Não foi possível atualizar a tabela {$this->table}.");
        }

        return true;
    }

    /**
     * @param array $fields
     * @return bool
     * @throws Exception
     */
    final public function insert(array $fields)
    {
        $columns = implode(',', array_keys($fields));
        /*
         * @todo Verificar tipo da variável (string, inteiro, nulo)
         */
        $values = implode(',', array_values($fields));

        $query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$values})";

        $result = db_query($query);

        if (!$result) {
            throw new Exception("Não foi possível inserir na tabela {$this->table}.");
        }

        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    final public function delete()
    {
        $query = "DELETE FROM {$this->table} {$this->buildWhere()}";

        $result = db_query($query);

        if (!$result) {
            throw new Exception("Não foi possível apagar o registro da tabela {$this->table}");
        }

        return true;
    }

    /**
     * @return array
     * @throws Exception
     */
    final public function get()
    {
        $result = db_query($this->buildSelect());

        if (!$result) {
            throw new Exception("Não foi possível consultar os dados da tabela {$this->table}");
        }

        return pg_fetch_all($result);
    }

    /**
     * @return object
     * @throws Exception
     */
    final public function first()
    {
        $query = $this->buildSelect() . ' LIMIT 1';

        $result = db_query($query);

        if (!$result) {
            throw new Exception("Não foi possível consultar os dados da tabela {$this->table}");
        }

        return pg_fetch_object($result, 0);
    }

    /**
     * @return string
     */
    private function buildSelect()
    {
        return "SELECT {$this->select} FROM {$this->table} {$this->buildWhere()} {$this->buildLimit()}";
    }

    /**
     * @return string
     */
    private function buildWhere()
    {
        if (empty($this->where) && empty($this->orWhere)) {
            return '';
        }

        $where = implode(' AND ', $this->where);
        $orWhere = implode(' OR ', $this->orWhere);

        return " WHERE {$where}" . ($orWhere ? ' OR ' . $orWhere : '');
    }

    /**
     * @return string
     */
    private function buildLimit()
    {
        if (empty($this->limit)) {
            return '';
        }

        return ' LIMIT ' . $this->limit;
    }

    private function log($field, $value, $type)
    {
        $affectedTableQuery = "
            SELECT db_syscampo.codcam as coluna, db_sysarquivo.codarq as tabela
            FROM db_syscampo
              INNER JOIN db_sysarqcamp ON db_sysarqcamp.codcam = db_syscampo.codcam
              INNER JOIN db_sysarquivo ON db_sysarquivo.codarq = db_sysarqcamp.codarq
            WHERE db_syscampo.nomecam = '{$field}' AND db_sysarquivo.nomearq = '{$this->table}'
        ";

        $affectedTable = db_query($affectedTableQuery);

        if (!$affectedTable) {
            throw new Exception('Não foi possível buscar os dados alterados.');
        }

        $affectedTable = pg_fetch_object($affectedTable, 0);

        $access = db_getsession('DB_acessado');

        $acountNextval = db_query("SELECT nextval('db_acount_id_acount_seq') AS acount");

        if (!$acountNextval) {
            throw new Exception('Não foi possível salvar os dados alterados.');
        }

        $acountNextval = pg_fetch_object($acountNextval, 0);

        $acountAcesso = db_query("INSERT INTO db_acountacesso VALUES ({$acountNextval->acount}, {$access})");

        if (!$acountAcesso) {
            throw new Exception('Não foi possível salvar os dados alterados.');
        }

        $acountKey = db_query("INSERT INTO db_acountkey VALUES ({$acountNextval->acount}, {$affectedTable->coluna}, {$value}, '{$type}')");

        if (!$acountKey) {
            throw new Exception('Não foi possível salvar os dados alterados.');
        }

        $affectedRowsQuery = "SELECT * FROM {$this->table}{$this->buildWhere()}";

        $affectedRows = db_query($affectedRowsQuery);

        if (!$affectedRows) {
            throw new Exception('Não foi possível buscar os dados alterados.');
        }

        $affectedRows = pg_fetch_all($affectedRows);
        $affectedRows = array_filter($affectedRows, function ($affectedRow) use ($field) {
            return array_key_exists($field, $affectedRow);
        });

        $year = db_getsession('DB_datausu');
        $user = db_getsession('DB_id_usuario');

        foreach ($affectedRows as $affectedRow) {
            $field = AddSlashes($affectedRow[$field]);
            $acount = db_query("INSERT INTO db_acount VALUES ({$acountNextval->acount}, {$affectedTable->tabela}, {$affectedTable->coluna}, '{$field}', '{$value}', {$year}, {$user})");

            if (!$acount) {
                throw new Exception('Não foi possível salvar os dados alterados.');
            }
        }
    }
}
