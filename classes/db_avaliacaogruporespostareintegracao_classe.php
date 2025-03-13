<?php

/**
 * Class cl_avaliacaogruporespostareintegracao
 */
class cl_avaliacaogruporespostareintegracao extends DAOBasica
{
    /**
     * cl_avaliacaogruporespostareintegracao constructor.
     */
    public function __construct()
    {
        parent::__construct('esocial.avaliacaogruporespostareintegracao');
    }

    public function buscarRespostasPreenchimento(
        array $campos = array('*'),
        array $where = array(),
        $outrosComandos = null
    ) {
        $sql = " SELECT " . implode(', ', $campos);
        $sql .= "  FROM avaliacaogruporespostareintegracao";
        $sql .= "  INNER JOIN avaliacaogruporesposta ON db107_sequencial = eso21_avaliacaogruporesposta ";
        $sql .= "  INNER JOIN avaliacaogrupoperguntaresposta ON db108_avaliacaogruporesposta = db107_sequencial";
        $sql .= "  INNER JOIN avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta ";
        $sql .= "  INNER JOIN avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao ";
        $sql .= "  INNER JOIN avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta ";
        $sql .= "  INNER JOIN avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial ";
        $sql .= "  INNER JOIN avaliacao ON db102_avaliacao = db101_sequencial ";
        $sql .= "  INNER JOIN cgm ON z01_numcgm = eso21_cgm";

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        if (!empty($outrosComandos)) {
            $sql .= " {$outrosComandos} ";
        }

        return $sql;
    }

    public function buscarRespostasPorPergunta($pergunta, $preenchimento, $campos = "*", $ordem = null)
    {
        $sql = "select {$campos}";
        $sql .= "  from avaliacaogruporespostareintegracao";
        $sql .= "      inner join avaliacaogruporesposta on db107_sequencial = eso21_avaliacaogruporesposta";
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

    public function sqlDadosServidorPreenchimento(
        array $campos = array('*'),
        array $where = array(),
        $outrosComandos = null
    ) {
        $sql  = " SELECT " . implode(', ', $campos);
        $sql .= "  FROM avaliacaogruporespostareintegracao";
        $sql .= "    INNER JOIN rhpessoal ON rhpessoal.rh01_regist = avaliacaogruporespostareintegracao.eso21_matricula ";
        $sql .= "    INNER JOIN cgm ON cgm.z01_numcgm = rhpessoal.rh01_numcgm ";
        if (!empty($where)) {
            $sql .= " where " . implode(' and ', $where);
        }

        if (!empty($outrosComandos)) {
            $sql .= " {$outrosComandos} ";
        }
        
        return $sql;
    }
}
