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

class R2020 extends Sugestao
{

    private $cgm;
    private $paramentros;

    public function __construct($cgm, $paramentros)
    {
        $this->cgm = $cgm;
        $this->paramentros = $paramentros;
    }

    public function parse()
    {
        if ($this->possuiPreenchimento()) {
            return null;
        }

        $cnpj = $this->cgm->getCnpj();
        $cnpj = db_formatar($cnpj, 'cnpj');

        return array(
            'tpInscEstabPrest' => array(
                'option' => 'tpInscEstabPrest_1'
            ),
            'nrInscEstabPrest' => $cnpj
        );
    }

    /**
     * Deve validar se o "sujeito" que preenche o formulário já preencheu o formulário.
     * Se sim retornar true
     * @return boolean
     */
    protected function possuiPreenchimento()
    {
        if (!empty($this->paramentros->preenchimento)) {
            return true;
        }
        return false;
    }

    /**
     * Essa função tem o objetivo de fazer um depara com os dados do e-cidade para com os dados do eSocial
     * Os campos que precisam desse depara devem ser informados no array $deParaESocial
     *
     * @param $nomeCampo
     * @param $valor
     * @return $valor O valor retornado deve ser o correspondente/equivalente no eSocial
     */
    protected function buscarValorCorrespondenteESocial($nomeCampo, $valor)
    {
        // TODO: Implement buscarValorCorrespondenteEsocial() method.
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
     * @return null|resource
     * @throws Exception
     */
    protected function buscarDados()
    {
    }
}
