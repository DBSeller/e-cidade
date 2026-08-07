<?php
/*
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

namespace ECidade\Educacao\Escola\Repository;

use cl_formacao;
use ECidade\Educacao\Escola\Model\ProfissionalFormacao;
use ECidade\Educacao\Escola\Registry\CensoDisciplinaRegistry;
use Exception;

class ProfissionalFormacaoRepository extends Repository
{

    /**
     * @return ProfissionalFormacao[]|array
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_formacao();
        $campos = "
            formacao.*,
            (select array_to_string(array_accum(ed145_censodisciplina), ',')
               from formacaocensodisciplina
              where ed145_formacao = ed27_i_codigo
            ) as formacao_complementar
        ";
        $sql = $dao->sql_query_file(null, "$campos", null, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar formação do profissional.");
        }
        $formacao = array();
        if (pg_num_rows($rs) === 0) {
            return $formacao;
        }

        while ($state = pg_fetch_array($rs)) {
            $formacaoProfissional = ProfissionalFormacao::fromState($state);

            if (!empty($state['formacao_complementar'])) {
                $codigoDisciplinas = explode(',', $state['formacao_complementar']);
                foreach ($codigoDisciplinas as $codigoDisciplina) {
                    $formacaoProfissional->addFormacaoComplementar(CensoDisciplinaRegistry::get($codigoDisciplina));
                }
            }
            $formacao[] = $formacaoProfissional;
        }

        return $formacao;
    }

    public function getPosGraduacoes($cgm)
    {
        $posGraduacoes = [];
        $sqlPos = "select * from rhformacaosuperior where ed183_cgm = {$cgm} order by ed183_anoconclusao desc limit 6";
        $rs = db_query($sqlPos);
        while ($pos = pg_fetch_assoc($rs)) {
            $posGraduacoes[] = $pos;
        }
        return $posGraduacoes;
    }

    /**
     * @param $codigo
     * @param string $operador
     * @return $this
     */
    public function scopeCodigoRecHumano($codigo, $operador = '=')
    {
        $this->scopes['rechumano'] = "ed27_i_rechumano {$operador} {$codigo}";
        return $this;
    }

    /**
     * @param $situacao
     * @param $operador
     * @return $this
     */
    public function scopeSituacao($situacao, $operador = '=')
    {
        $this->scopes['situacao'] = "ed27_c_situacao {$operador} '{$situacao}'";
        return $this;
    }
}
