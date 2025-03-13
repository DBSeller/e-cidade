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

namespace ECidade\Financeiro\Contabilidade\ContaCorrente\Relatorio\ContaContabil;

/**
 * Class ContasPorContaCorrente
 * @package ECidade\Financeiro\Contabilidade\ContaCorrente\Relatorio\ContaContabil
 */
class ContasContaCorrente
{
    /**
     * Codigo do Conta Corrente
     * @var integer
     */
    private $codigoContaCorrente;

    /**
     * Estrutural
     * @var string
     */
    private $estrutural;

    /**
     * @var integer
     */
    private $ano;

    /**
     * Atributos do conta corrente vinculado
     * @var array
     */
    private $atributosUnicos = array();

    /**
     * @var \PDFDocument
     */
    private $pdf;


    public function __construct()
    {

    }

    /**
     * @param $where
     * @throws \BusinessException
     * @throws \DBException
     */
    private function carregarAtributosUnicos($where)
    {
        $this->atributosUnicos = array();
        $campos = "distinct conplanoinfocomplementar.c121_sigla as sigla, conplanoinfocomplementar.c121_descricao as descricao";
        $daoSistema  = new \cl_conplanosistema();
        $buscarAtributos = $daoSistema->sql_query_vinculo_contas($campos, implode(' and ', $where), "order by 1");
        $buscarAtributos = db_query($buscarAtributos);
        if (!$buscarAtributos) {
            throw new \DBException("Ocorreu um erro ao verificar os atributos existentes para a conta contábil.");
        }

        $totalRegistros = pg_num_rows($buscarAtributos);
        if ($totalRegistros === 0){
            throw new \BusinessException("Não foram encontrados atributos para o filtro selecionado.");
        }

        for ($rowAtributos = 0; $rowAtributos < $totalRegistros; $rowAtributos++) {

            $stdAtributo = \db_utils::fieldsMemory($buscarAtributos, $rowAtributos);
            if (array_key_exists($stdAtributo->sigla, $this->atributosUnicos)) {
                continue;
            }
            $this->atributosUnicos[$stdAtributo->sigla] = $stdAtributo->descricao;
        }
    }

    /**
     * @throws \BusinessException
     * @throws \DBException
     */
    private function getDados()
    {

        $where = array(
            'conplanosistema.c122_tipo = 2',
            "conplano.c60_anousu = {$this->ano}"
        );

        if (!empty($this->codigoContaCorrente)) {
            $where[] = "conplanosistema.c122_sequencial = {$this->codigoContaCorrente}";
        }

        if (!empty($this->estrutural)) {
            $where[] = "conplano.c60_estrut ilike '{$this->estrutural}%'";
        }

        $this->carregarAtributosUnicos($where);

        $orderBy = " group by c60_codcon, c60_estrut, c60_descr, c122_sequencial, c122_descricao order by conplanosistema.c122_sequencial, conplano.c60_estrut  ";
        $campos = implode(',', array(
            'conplano.c60_codcon as codigo_conta_contabil',
            'conplano.c60_estrut as estrutura',
            'conplano.c60_descr as descricao_conta_contabil',
            'conplanosistema.c122_sequencial as codigo_conta_corrente',
            'conplanosistema.c122_descricao as descricao_conta_corrente',
            'array_to_string(array_accum(conplanoinfocomplementar.c121_sigla), \' | \') as sigla_atributo',
        ));

        $daoSistema  = new \cl_conplanosistema();
        $buscaContas = $daoSistema->sql_query_vinculo_contas($campos, $where, $orderBy);
        $buscaContas = db_query($buscaContas);
        if (!$buscaContas) {
            throw new \DBException("Ocorreu um erro ao processar os filtros do relatório.");
        }

        $totalRegistros = pg_num_rows($buscaContas);
        if ($totalRegistros === 0){
            throw new \BusinessException("Não foram encontrados registros para o filtro selecionado.");
        }
        return \db_utils::getCollectionByRecord($buscaContas);
    }

    /**
     * @param integer $codigo
     */
    public function setCodigoContaCorrente($codigo)
    {
        $this->codigoContaCorrente = $codigo;
    }

    /**
     * @param string $estrutural
     */
    public function setEstrutural($estrutural)
    {
        $this->estrutural = $estrutural;
    }

    /**
     * @param $ano
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    /**
     * @throws \BusinessException
     * @throws \DBException
     */
    public function emitir()
    {
        $this->pdf = new \PDFDocument(\PDFDocument::PRINT_PORTRAIT);
        $this->pdf->setFontSize(7);
        $this->pdf->setFillColor(220);
        $this->pdf->addHeaderDescription('Relação dos Contas Correntes por Conta Contábil');
        $this->pdf->addHeaderDescription('');
        if (!empty($this->codigoContaCorrente)) {

            $daoContaCorrente = new \cl_conplanosistema();
            $buscaDescricao = $daoContaCorrente->sql_query_file($this->codigoContaCorrente);
            $buscaDescricao = db_query($buscaDescricao);
            if (!$buscaDescricao || pg_num_rows($buscaDescricao) == 0) {
                throw new \DBException("Não foi encontrado conta corrente para o código {$this->codigoContaCorrente}.");
            }
            $descricao = \db_utils::fieldsMemory($buscaDescricao, 0)->c122_descricao;
            $this->pdf->addHeaderDescription("Conta Corrente: {$this->codigoContaCorrente} - {$descricao}");
        }

        if (!empty($this->estrutural)) {
            $this->pdf->addHeaderDescription("Estrutural: ".str_pad($this->estrutural, 15, '0', STR_PAD_RIGHT));
        }

        $this->pdf->open();
        $this->pdf->addPage(\PDFDocument::PRINT_PORTRAIT);

        $dados = $this->getDados();
        $ultimoContaCorrente = null;
        $this->imprimirLegendaDeAtributos();
        foreach ($dados as $stdConta) {

            if ($this->pdf->getAvailHeight() < 30) {

                $this->pdf->addPage(\PDFDocument::PRINT_PORTRAIT);
                $this->imprimirLegendaDeAtributos();
            }

            if ($ultimoContaCorrente != $stdConta->codigo_conta_corrente) {
                $this->imprimeCabecalhoContaCorrente($stdConta);
            }
            $this->imprimeLinha($stdConta);
            $ultimoContaCorrente = $stdConta->codigo_conta_corrente;
        }

        $this->pdf->showPDF('ContaContabilPorContaCorrente'.date('YmdHis'));
    }

    /**
     * Imprime linha referente as contas contabeis
     * @param $stdConta
     */
    private function imprimeLinha($stdConta)
    {
        $this->pdf->cell(25, 4, $stdConta->estrutura, 0, 0, 'C');
        $this->pdf->cell($this->pdf->getAvailWidth(), 4, $stdConta->descricao_conta_contabil, 0, 1, 'L');
    }

    /**
     * Imprime o cabeçalho do conta corrente
     * @param $stdConta
     */
    private function imprimeCabecalhoContaCorrente($stdConta)
    {
        $this->pdf->ln(5);
        $this->pdf->setBold(true);
        $this->pdf->cell(22, 4, 'Conta Corrente:', 0, 0, 'L', 1);
        $this->pdf->setBold(false);
        $this->pdf->cell(60, 4, $stdConta->descricao_conta_corrente, 0, 0, 'L', 1);
        $this->pdf->setBold(true);
        $this->pdf->cell(15, 4, 'Atributos:', 0, 0, 'L', 1);
        $this->pdf->setBold(false);
        $this->pdf->cell($this->pdf->getAvailWidth(), 4, $stdConta->sigla_atributo, 0, 1, 'L', 1);
    }

    /**
     * Imprime a legenda com os atributos unicos
     */
    private function imprimirLegendaDeAtributos()
    {
        $this->pdf->setBold(true);
        $this->pdf->cell($this->pdf->getAvailWidth(), 4, 'Legenda', 0, 1, 'C', 1);
        $this->pdf->setBold(false);
        $tamanhoColuna = ($this->pdf->getAvailWidth() / 3);
        $impresso = 1;
        foreach ($this->atributosUnicos as $sigla => $descricao) {

            if ($impresso == 3) {
                $impresso = 0;
                $quebraLinha = 1;
            } else {
                $quebraLinha = 0;
            }
            $this->pdf->cell($tamanhoColuna, 4, "{$sigla} - {$descricao}", 0, $quebraLinha);
            $impresso++;
        }
        $this->pdf->ln(5);
    }


}

