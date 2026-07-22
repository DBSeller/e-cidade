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

class R1000 extends Sugestao
{

    protected $deParaESocial = array(
        'tpInsc',
        'nrInsc',
        'iniValid',
        'fimValid',
        'classTrib',
        'indEscrituracao',
        'indDesoneracao',
        'indAcordoIsenMulta',
        'indSitPJ',
        'nmCtt',
        'cpfCtt',
        'foneFixo',
        'foneCel',
        'email',
        'ideEFR'
    );

    protected $deParaCamposSimples = array(
        'nrinsc' => 'nrInsc',
        'inivalid' => 'iniValid',
        'fimvalid' => 'fimValid',
        'classtrib' => 'classTrib',
        'nmctt' => 'nmCtt',
        'cpfctt' => 'cpfCtt',
        'fonefixo' => 'foneFixo',
        'fonecel' => 'foneCel',
        'email' => 'email',
        'ideefr' => 'ideEFR',
    );

    protected $deParaCamposComplexos = array(
        'tpinsc' => array(
            'tpInsc' => array(
                1 => 'tpInsc_1',
                2 => 'tpInsc_2',
            )
        ),
        'indescrituracao' => array(
            'indEscrituracao' => array(
                0 => 'indEscrituracao_0',
                1 => 'indEscrituracao_1',
            )
        ),
        'indacordoisenmulta' => array(
            'indAcordoIsenMulta' => array(
                0 => 'indAcordoIsenMulta_0',
                1 => 'indAcordoIsenMulta_1',
            )
        ),
        'inddesoneracao' => array(
            'indDesoneracao' => array(
                0 => 'indDesoneracao_0',
                1 => 'indDesoneracao_1',
            )
        ),
        'indsitpj' => array(
            'indSitPJ' => array(
                0 => 'indSitPJ_0',
                1 => 'indSitPJ_1',
                2 => 'indSitPJ_2',
                3 => 'indSitPJ_3',
                4 => 'indSitPJ_4'
            )
        ),
        'ideefr' => array(
            'ideEFR' => array(
                's' => 'ideEFR_s',
                'n' => 'ideEFR_n',
            )
        ),
    );

    /**
     * @var \CgmBase
     */
    private $cgmContribuinte;

    public function __construct($cgmContribuinte)
    {
        $this->cgmContribuinte = $cgmContribuinte;
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
        return $valor;
    }

    /**
     * Realiza algum tratamento nos dados após o parse
     */
    protected function posProcessamento()
    {
        // @todo verificar $this->dados
        $a = $this->dados;
    }

    /**
     * Deve retornar um resource com os dados
     * @return null|resource
     * @throws Exception
     */
    protected function buscarDados()
    {
        $dao = new \cl_avaliacaogruporespostacontribuinte();
        $sql = $dao->sqlDadosSugestao($this->cgmContribuinte->getCodigo());
        return db_query($sql);
    }

    /**
     * Deve validar se o "sujeito" que preenche o formulário já preencheu o formulário.
     * Se sim retornar true
     * @return boolean
     */
    protected function possuiPreenchimento()
    {
        $dao = new \cl_avaliacaogruporespostacontribuinte();
        $where = " eso27_cgm = {$this->cgmContribuinte->getCodigo()} ";
        $sql = $dao->sql_query_file(null, "*", null, $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception(
                "Não foi possível buscar os preenchimentos anteriores do formulário.\nContate o suporte."
            );
        }

        if (pg_numrows($rs) > 0) {
            return true;
        }

        return false;
    }
}
