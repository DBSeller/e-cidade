<?php

namespace ECidade\Configuracao\Opcao;

use ECidade\Configuracao\Opcao\Model\Opcao as OpcaoModel;

class Opcao
{


    /**
     * @var Opcao
     */
    private static $instance;

    /**
     * Lista de opcoes disponiveis
     * @var array
     */
    private $opcoes = [];

    private function __construct()
    {
    }

    /**
     * @return $this|Opcao
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }


    /**
     * Salva os dados das opcoes
     * @param $nome
     * @param $valor
     * @param null $ano
     * @param null $instituicao
     * @throws \Exception
     */
    public static function salvar($nome, $valor, $ano = null, $instituicao = null)
    {
        $hash = "{$nome}#{$ano}#{$instituicao}";
        $item = self::get($nome, $ano, $instituicao);

        if (empty($item)) {
            $item = new OpcaoModel();
            $item->setAno($ano);
            $item->setNome($nome);
            $item->setValor($valor);
            $item->setInstituicao($instituicao);
            self::getInstance()->opcoes[$hash] = $item;
        } else {
            $sql = "delete from db_opcoes where db153_sequencial = {$item->getId()}";
            $rsDelete = db_query($sql);
            if (!$rsDelete) {
                throw new \Exception("Erro ao remover Item {$nome}");
            }
            unset(self::getInstance()->opcoes[$hash]);
        }

        $item->setValor($valor);
        $campos = [
            'db153_sequencial',
            'db153_nome',
            'db153_valor',
        ];
        $nome = pg_escape_string($nome);
        $valor = pg_escape_string($valor);
        $valores = [
            "nextval('db_opcoes_db153_sequencial_seq')",
            "'{$nome}'",
            "'{$valor}'",
        ];
        if (!empty($ano)) {
            $campos[] = "db153_ano";
            $valores[] = pg_escape_string($ano);
        }
        if (!empty($instituicao)) {
            $campos[] = "db153_instit";
            $valores[] = $instituicao;
        }
        $insert = "insert into db_opcoes (" . implode(", ", $campos) . ") ";
        $insert .= " values (" . implode(", ", $valores) . ") returning db153_sequencial";

        $rsInsert = db_query($insert);
        if (!$rsInsert) {
            throw new \Exception("Erro ao incluir opcao {$nome}");
        }
        $codigoOpcao  = \db_utils::fieldsMemory($rsInsert, 0)->db153_sequencial;
        $item->setid($codigoOpcao);
        self::getInstance()->opcoes[$hash] = $item;
    }

    /**
     * Retorna uma opcao
     * @param $nome
     * @param null $ano
     * @param null $instituicao
     * @return OpcaoModel
     * @throws \Exception
     */
    public static function get($nome, $ano = null, $instituicao = null)
    {
        $hash = "{$nome}#{$ano}#{$instituicao}";
        $item = null;
        if (!empty(self::getInstance()->opcoes[$hash])) {
            $item = self::getInstance()->opcoes[$hash];
        }
        if (empty($item)) {
            $where = [
                "db153_nome='{$nome}'",
            ];
            if (!empty($ano)) {
                $where[] = "db153_ano = {$ano}";
            }
            if (!empty($instituicao)) {
                $where[] = "db153_instit = " . $instituicao;
            }
            $sql = "select  * from db_opcoes where " . implode(" and ", $where);
            $rsDados = db_query($sql);
            if (!$rsDados) {
                throw  new \Exception("Erro ao pesquisar dados da opcao '{$nome}'");
            }
            if (pg_num_rows($rsDados) > 0) {
                $dados = \db_utils::fieldsMemory($rsDados, 0);
                $item = new OpcaoModel();
                $item->setAno($dados->db153_ano);
                $item->setID($dados->db153_sequencial);
                $item->setNome($dados->db153_nome);
                $item->setValor($dados->db153_valor);
                $item->setInstituicao($dados->db153_instit);
                self::getInstance()->opcoes[$hash] = $item;
            }
        }
        return $item;
    }
}
