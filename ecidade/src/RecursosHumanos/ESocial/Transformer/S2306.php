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

namespace ECidade\RecursosHumanos\ESocial\Transformer;

use Exception;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

/**
 * Class S2306
 * @package ECidade\RecursosHumanos\ESocial\Transformer
 */
class S2306 extends S2300
{
    /**
     * @var \Servidor
     */
    protected $servidor;

    /**
     * @var \CgmBase
     */
    protected $empregador;

    /**
     * S2306 constructor.
     * @param \Servidor $servidor
     * @param \CgmBase $empregador
     */
    public function __construct(\Servidor $servidor, \CgmBase $empregador)
    {
        $this->servidor   = $servidor;
        $this->empregador = $empregador;
        $this->deParaCamposSimples["codcateg"] = "codCateg";
    }


    /**
     * Essa função tem o objetivo de fazer um depara com os dados do e-cidade para com os dados do eSocial
     * Os campos que precisam desse depara devem ser informados no array $deParaESocial
     * @param $nomeCampo
     * @param $valor
     * @return mixed $valor O valor retornado deve ser o correspondente/equivalente no eSocial
     */
    protected function buscarValorCorrespondenteESocial($nomeCampo, $valor)
    {
        return $valor;
    }

    /**
     * Realiza algum tratamento nos dados após o parse
     */
    protected function posProcessamento()
    {
        // TODO: Implement posProcessamento() method.
    }

    /**
     * Deve retornar um resource com os dados
     * @return null|array
     * @throws Exception
     */
    protected function buscarDados()
    {
        $dadosAlteracao = $this->getDadosPorArquivo(Tipo::S2306);
        if (empty($dadosAlteracao)) {
            $dadosAlteracao = $this->getDadosPorArquivo(Tipo::S2300);
        }
        return $dadosAlteracao;
    }

    /**
     * @param $codigoArquivo
     * @return mixed|null
     * @throws \DBException
     */
    private function getDadosPorArquivo($codigoArquivo)
    {
        $where = implode(' and ', array(
            "rh213_evento = '{$codigoArquivo}'",
            "rh213_empregador = {$this->empregador->getCodigo()}",
            "rh213_responsavelpreenchimento = '{$this->servidor->getMatricula()}'",
        ));
        $daoEsocialEnvio = new \cl_esocialenvio();
        $buscaPreenchimento = $daoEsocialEnvio->sql_query_file(null, "rh213_dados", '1 desc limit 1', $where);
        $buscaPreenchimento = db_query($buscaPreenchimento);
        if (!$buscaPreenchimento) {
            throw new \DBException("Não foi possível buscar os dados de envio do arquivo S-{$codigoArquivo}.");
        }

        $retornoJsonToArray = null;
        if (pg_num_rows($buscaPreenchimento) > 0) {
            $retornoJsonToArray = json_decode(\db_utils::fieldsMemory($buscaPreenchimento, 0)->rh213_dados, true);
        }
        return $retornoJsonToArray;
    }

    /**
     * @return array|null
     * @throws \DBException
     */
    public function parse()
    {
        if ($this->possuiPreenchimento()) {
            return null;
        }

        $jsonToArray = $this->buscarDados();
        $this->transformToObject($jsonToArray);
        return $this->dados;
    }

    /**
     * Transforma recursivamente em um objeto conforme esperado
     * @param $dadosJson
     */
    private function transformToObject($dadosJson)
    {
        if (!empty($dadosJson)) {
            foreach ($dadosJson as $indice => $valor) {
                if (is_array($valor)) {
                    $this->transformToObject($valor);
                }
                $this->adicionarValor(strtolower($indice), $valor);
            }
        }
    }

    /**
     * Verifica se há preenchimento par ao formulário
     * @return bool
     * @throws \DBException
     */
    protected function possuiPreenchimento()
    {
        $where = array(
            "avaliacaogruporespostatsvealteracao.eso23_rhpessoal = {$this->servidor->getMatricula()}",
            "rhlota.r70_numcgm = {$this->empregador->getCodigo()}"
        );
        $daotsve = new \cl_avaliacaogruporespostatsvealteracao();
        $buscaPreenchimento = $daotsve->sql_query_busca_avaliacao(array('eso23_avaliacaogruporesposta'), $where);
        $buscaPreenchimento = db_query($buscaPreenchimento);
        if (!$buscaPreenchimento) {
            throw new \DBException("Ocorreu um erro ao consultar os dados do preenchimento.");
        }
        return pg_num_rows($buscaPreenchimento) > 0;
    }
}
