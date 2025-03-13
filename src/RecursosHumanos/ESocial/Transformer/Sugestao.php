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

namespace ECidade\RecursosHumanos\ESocial\Transformer;

use Exception;

abstract class Sugestao
{

    /**
     * mapeia as propriedades que devemos realizar um "de para" com os valores aceito no eSocial
     * @example
     *   No ecidade o campo Raça e cor o valor para INDIGENA é 1 e no eSocial é 5.
     * @var array
     */
    protected $deParaESocial = array();

    /**
     * Os campos considerados 'simples', são os que possuem uma única opção de resposta.
     * Um input text
     *
     * @example Como funciona o array
     * O index representa a propriedade retornada no e-cidade.
     * O valor, representa o identificador_campo no formulário do e-cidade
     *
     * @var array
     */
    protected $deParaCamposSimples = array();

    /**
     * Os campos 'complexos', são os que possuem MAIS de uma opção de resposta. Exemplo os rádio button
     *
     * @example da estrutura do array
     * 'nome da propriedade retornada na query' => array(
     *     'nome do identificador de campo da pergunta' => array(
     *         'valor da resposta' => 'nome do identificador de campo da opção'
     *      )
     * )
     * @var array
     */
    protected $deParaCamposComplexos = array();

    protected $dados = array();


    public function parse()
    {
        if ($this->possuiPreenchimento()) {
            return null;
        }

        $rs = $this->buscarDados();

        if (!empty($rs)) {
            while ($row = pg_fetch_assoc($rs)) {
                foreach ($row as $indice => $valor) {
                    $this->adicionarValor($indice, $valor);
                }
            }
        }
        $this->posProcessamento();

        return $this->dados;
    }

    protected function adicionarValor($indice, $valor)
    {
        // Busca o mapeador dos dados para o campo informado
        if (in_array($indice, $this->deParaESocial)) {
            $valor = $this->buscarValorCorrespondenteESocial($indice, $valor);
        }

        if (array_key_exists($indice, $this->deParaCamposSimples)) {
            $identificadorCampo = $this->deParaCamposSimples[$indice];
            if (!empty($valor) || $valor === "0") {
                $this->dados[$identificadorCampo] = $valor;
            }
        }

        if (array_key_exists($indice, $this->deParaCamposComplexos)) {
            $campo = $this->deParaCamposComplexos[$indice];
            $identificadorPergunta = key($campo);
            if (!empty($campo[$identificadorPergunta][$valor])) {
                $identificadorResposta = $campo[$identificadorPergunta][$valor];
                $this->dados[$identificadorPergunta] = array();
                $this->dados[$identificadorPergunta]["option"] = $identificadorResposta;
            }
        }
    }

    /**
     * Essa função tem o objetivo de fazer um depara com os dados do e-cidade para com os dados do eSocial
     * Os campos que precisam desse depara devem ser informados no array $deParaESocial
     *
     * @param mixed $nomeCampo
     * @param mixed $valor
     * @return mixed $valor O valor retornado deve ser o correspondente/equivalente no eSocial
     */
    abstract protected function buscarValorCorrespondenteESocial($nomeCampo, $valor);


    /**
     * Realiza algum tratamento nos dados após o parse
     */
    abstract protected function posProcessamento();

    /**
     * Deve retornar um resource com os dados
     * @return null|resource
     * @throws Exception
     */
    abstract protected function buscarDados();

    /**
     * Deve validar se o "sujeito" que preenche o formulário já preencheu o formulário.
     * Se sim retornar true
     * @return boolean
     */
    abstract protected function possuiPreenchimento();
}
