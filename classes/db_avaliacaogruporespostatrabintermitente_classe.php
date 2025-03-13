<?php

/**
 * Class cl_avaliacaogruporespostatrabintermitente
 */
class cl_avaliacaogruporespostatrabintermitente extends DAOBasica
{
    /**
     * cl_avaliacaogruporespostatrabintermitente constructor.
     */
    public function __construct()
    {
        parent::__construct('esocial.avaliacaogruporespostatrabintermitente');
    }

    /**
     * Busca as respostas do preenchimento
     * @param array $campos
     * @param array $where
     * @param string $outrosComandos
     * @return string
     */
    public function buscarRespostasPreenchimento(
        array $campos = array('*'),
        array $where = array(),
        $outrosComandos = null
    ) {
        $sql = " SELECT " . implode(', ', $campos);
        $sql .= "  FROM avaliacaogruporespostatrabintermitente";
        $sql .= "  INNER JOIN avaliacaogruporesposta ON db107_sequencial = eso19_avaliacaogruporesposta ";
        $sql .= "  INNER JOIN avaliacaogrupoperguntaresposta ON db108_avaliacaogruporesposta = db107_sequencial";
        $sql .= "  INNER JOIN avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta ";
        $sql .= "  INNER JOIN avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao ";
        $sql .= "  INNER JOIN avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta ";
        $sql .= "  INNER JOIN avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial ";
        $sql .= "  INNER JOIN avaliacao ON db102_avaliacao = db101_sequencial ";
        $sql .= "  INNER JOIN cgm ON z01_numcgm = eso19_cgm";

        if (!empty($where)) {
            $sql .= " where " . implode(' and ', $where);
        }

        if (!empty($outrosComandos)) {
            $sql .= " {$outrosComandos} ";
        }

        return $sql;
    }

    public function buscarRespostasPorPergunta($pergunta, $preenchimento, $campos = "*", $ordem = null)
    {
        $sql = "select {$campos}";
        $sql .= "  from avaliacaogruporespostatrabintermitente";
        $sql .= "      inner join avaliacaogruporesposta on db107_sequencial = eso19_avaliacaogruporesposta";
        $sql .= "      inner join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial";
        $sql .= "      inner join avaliacaoresposta on db106_sequencial = db108_avaliacaoresposta";
        $sql .= "      inner join avaliacaoperguntaopcao on db104_sequencial = db106_avaliacaoperguntaopcao";
        $sql .= "      inner join avaliacaopergunta on db103_sequencial = db104_avaliacaopergunta";
        $sql .= " where db103_sequencial = {$pergunta}";
        $sql .= "   and db107_sequencial = {$preenchimento}";
        if (!empty($ordem)) {
            $sql .= " order by {$ordem}";
        }
        return $sql;
    }

    public function sqlPreenchimentos($empregador, array $campos = array('*'), array $where = array())
    {
        $campos = implode(', ', $campos);
        $where = implode(' AND ', $where);

        if ($where) {
            $where = "WHERE {$where}";
        }

        $sql = "
            WITH respostas AS (SELECT {$campos}
                               FROM avaliacaogruporespostatrabintermitente
                                      JOIN avaliacaogrupoperguntaresposta
                                        ON avaliacaogrupoperguntaresposta.db108_avaliacaogruporesposta =
                                           avaliacaogruporespostatrabintermitente.eso19_avaliacaogruporesposta
                                      JOIN avaliacaoresposta ON avaliacaoresposta.db106_sequencial =
                                                                avaliacaogrupoperguntaresposta.db108_avaliacaoresposta
                                      JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_sequencial = avaliacaoresposta.db106_avaliacaoperguntaopcao
                                      JOIN rhpessoal
                                        ON rhpessoal.rh01_regist = avaliacaogruporespostatrabintermitente.eso19_matricula
                                      JOIN cgm ON cgm.z01_numcgm = rhpessoal.rh01_numcgm
                               WHERE avaliacaoperguntaopcao.db104_avaliacaopergunta = 3001614
                                 AND eso19_cgm = {$empregador})
            SELECT *
            FROM respostas
            {$where}
        ";

        return $sql;
    }
}
