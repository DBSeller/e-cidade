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
 *  junto com este programa; se nao, escreva para a Free Softwareb
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\RecursosHumanos\ESocial\Repository;

use db_utils;
use Exception;
use ParameterException;

use \ECidade\Configuracao\Formulario\Repository\Formulario;
use ECidade\Configuracao\Formulario\Resposta\Repository\Resposta;

/**
 * Class FormularioRepository
 * @package ECidade\RecursosHumanos\ESocial\Repository
 */
class FormularioRepository
{
    const FILTRO_INSTITUICAO = 'instituicao';

    public static function getRespostasFormularioById($codigoFormulario, $mapeador, $instituicao = null, $filtro = null)
    {
        $formulario = Formulario::getById($codigoFormulario);

        $where = '';
        $identificador = $mapeador->getIdentificador(true);

        if (!empty($filtro)) {
            $inWhere = "'" . implode("','", $filtro) . "'";
            $where = " and db103_identificadorcampo = '{$identificador}' and db106_resposta in({$inWhere})";
        } else {
            $where = " and db103_identificadorcampo = '{$identificador}' ";
        }

        $perguntasMapeadas = $mapeador->getPerguntas();
        $dados = new \stdClass();
        $dados->fields = array();
        $dados->respostas =array();

        $iColunas = 1;
        $listaColunas = array();

        foreach ($formulario->getPerguntas() as $pergunta) {
            $listaColunas[] = $pergunta->getIdentificador();
            $campo = new \stdClass();

            $campo->identificador = strtolower($pergunta->getIdentificadorCampo());
            $campo->descricao = self::removeHtmlContent($pergunta->getDescricao());
            $campo->tipo = $pergunta->getTipo();
            $dados->fields[] = $campo;
            $iColunas++;
        }
        if (!empty($perguntasMapeadas)) {
            $novasColunas = [];
            foreach ($perguntasMapeadas as $pergunta) {
                $novasColunas[] = $pergunta;
            }
            $listaColunas = $novasColunas;
        }
        $oDaoAvaliacaoResposta = new \cl_avaliacaogrupoperguntaresposta;
        $aWhere   = array("db101_sequencial = {$formulario->getCodigo()}");

        $group  = " group by db106_resposta ";

        $campos = "distinct max(db107_sequencial) as db107_sequencial, "
            . "max(db107_datalancamento) as db107_datalancamento, db106_resposta";

        $sSqlRespostas  = $oDaoAvaliacaoResposta->sql_query_avaliacao(
            null,
            $campos,
            null,
            implode(" and ", $aWhere) . $where
        );

        $sSqlRespostas .= "{$group} order by db106_resposta desc ";

        if (!empty($instituicao)) {
            $sSqlRespostas = "select * from ($sSqlRespostas) as x";
            $sSqlRespostas .= " where exists(select 1
                from
                    avaliacaogruporesposta as a
                    join avaliacaogrupoperguntaresposta as b on a.db107_sequencial = b.db108_avaliacaogruporesposta
                    join avaliacaoresposta as c on c.db106_sequencial = b.db108_avaliacaoresposta
                    join avaliacaoperguntaopcao as d on d.db104_sequencial = c.db106_avaliacaoperguntaopcao
                    join avaliacaopergunta as e on e.db103_sequencial = d.db104_avaliacaopergunta
                where a.db107_sequencial = x.db107_sequencial
                    and e.db103_identificadorcampo = '" . self::FILTRO_INSTITUICAO . "'
                    and c.db106_resposta = '{$instituicao}')";
        };
        //$sSqlRespostas .= " limit 1";

        $rsRespostas = db_query($sSqlRespostas);
        if (!$rsRespostas) {
            throw new \BusinessException("Erro ao pesquisar as respostas do formulário {$formulario->getNome()}.");
        }
        $dados->respostas = \db_utils::makeCollectionFromRecord(
            $rsRespostas,
            function ($dados) use ($formulario, $listaColunas) {
                $oResposta = Resposta::make($dados, $formulario);
                $oRespostaRetorno = new \stdClass();
                $respostas = [];
                foreach ($oResposta->getRespostas() as $valorResposta) {
                    $identificadorCampo = strtolower($valorResposta->getPergunta()->getIdentificadorCampo());
                    foreach ($listaColunas as $ordem => $coluna) {
                        if ($valorResposta->getPergunta()->getIdentificadorCampo() == '') {
                            continue;
                        }
                        if ($coluna == $identificadorCampo) {
                            $valor = $valorResposta->getValor();
                            $pergunta = $valorResposta->getPergunta();
                            if (in_array($pergunta->getTipoResposta(), array(1, 3))) {
                                $valor = $valorResposta->getOpcao()->getDescricao();
                            }

                            /**
                             * formatar campos:
                             */
                            if ($pergunta->getTipo() == 5) {
                                $valor = db_formatar(substr($valor, 0, 10), 'd');
                            }
                            $respostas[$ordem] = [strtolower($pergunta->getIdentificadorCampo()) => $valor];
                        }
                    }
                }
                for ($i = 0; $i < sizeof($listaColunas); $i++) {
                    if (empty($respostas[$i])) {
                        $respostas[$i] = [$listaColunas[$i] => "-"];
                    }
                    foreach ($respostas[$i] as $key => $value) {
                        $oRespostaRetorno->{$key} = $value;
                    }
                }
                return $oRespostaRetorno;
            }
        );
        return $dados;
    }

    public static function getTodasRespostasFormularioById($codigoFormulario, $instituicao = null, $filtro = null)
    {
        $formulario = Formulario::getById($codigoFormulario);

        $where = '';

        $dados = new \stdClass();
        $dados->respostas =array();

        $oDaoAvaliacaoResposta = new \cl_avaliacaogrupoperguntaresposta;
        $aWhere   = array("db101_sequencial = {$formulario->getCodigo()}");

        $sSqlRespostas  = $oDaoAvaliacaoResposta->sql_query_avaliacao(
            null,
            "distinct db107_sequencial, db107_usuario, db107_datalancamento, db107_hora",
            null,
            implode(" and ", $aWhere) . $where
        );

        $sSqlRespostas .= " order by db107_sequencial";

        if (!empty($instituicao)) {
            $sSqlRespostas = "select * from ($sSqlRespostas) as x";
            $sSqlRespostas .= " where exists(select 1
                from
                    avaliacaogruporesposta as a
                    join avaliacaogrupoperguntaresposta as b on a.db107_sequencial = b.db108_avaliacaogruporesposta
                    join avaliacaoresposta as c on c.db106_sequencial = b.db108_avaliacaoresposta
                    join avaliacaoperguntaopcao as d on d.db104_sequencial = c.db106_avaliacaoperguntaopcao
                    join avaliacaopergunta as e on e.db103_sequencial = d.db104_avaliacaopergunta
                where a.db107_sequencial = x.db107_sequencial
                    and e.db103_identificadorcampo = '" . self::FILTRO_INSTITUICAO . "'
                    and c.db106_resposta = '{$instituicao}')";
        };
        $rsRespostas = db_query($sSqlRespostas);
        if (!$rsRespostas) {
            throw new \BusinessException("Erro ao pesquisar as respostas do formulário {$formulario->getNome()}.");
        }
        $dados->respostas = \db_utils::makeCollectionFromRecord(
            $rsRespostas,
            function ($dados) use ($formulario) {
                $oResposta = Resposta::make($dados, $formulario);
                return $oResposta->getRespostas();
            }
        );
        return $dados;
    }

    public static function getCodigoPreenchimentoRubrica($codigoFormulario, $instituicao, $rubrica)
    {
        $where = " and db103_identificadorcampo = 'codRubr' and db106_resposta = '{$rubrica}'";

        $oDaoAvaliacaoResposta = new \cl_avaliacaogrupoperguntaresposta;
        $aWhere   = array("db101_sequencial = {$codigoFormulario}");

        $sSqlRespostas  = $oDaoAvaliacaoResposta->sql_query_avaliacao(
            null,
            "distinct db107_sequencial, db107_usuario, db107_datalancamento, db107_hora",
            null,
            implode(" and ", $aWhere) . $where
        );

        $sSqlRespostas .= " order by db107_sequencial";

        $sSqlRespostas = "select * from ($sSqlRespostas) as x";
        $sSqlRespostas .= " where exists(select 1
            from
                avaliacaogruporesposta as a
                join avaliacaogrupoperguntaresposta as b on a.db107_sequencial = b.db108_avaliacaogruporesposta
                join avaliacaoresposta as c on c.db106_sequencial = b.db108_avaliacaoresposta
                join avaliacaoperguntaopcao as d on d.db104_sequencial = c.db106_avaliacaoperguntaopcao
                join avaliacaopergunta as e on e.db103_sequencial = d.db104_avaliacaopergunta
            where a.db107_sequencial = x.db107_sequencial
                and e.db103_identificadorcampo = '" . self::FILTRO_INSTITUICAO . "'
                and c.db106_resposta = '{$instituicao}')";

        $sSqlRespostas .= " order by db107_datalancamento desc limit 1";
        $rsRespostas = db_query($sSqlRespostas);
        if (!$rsRespostas) {
            $mensagem = "Erro ao pesquisar as respostas do formulário {$codigoFormulario}, rubrica {$rubrica}.";
            throw new \BusinessException($mensagem);
        }

        $respostas = \db_utils::fieldsMemory($rsRespostas, 0);

        return $respostas->db107_sequencial;
    }

    public static function removeHtmlContent($text, $tags = '', $invert = false)
    {
        preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
        $tags = array_unique($tags[1]);

        if (is_array($tags) && count($tags) > 0) {
            if (!$invert) {
                return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
            } else {
                return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text);
            }
        } elseif (!$invert) {
            return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
        }
        return $text;
    }
}
