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

namespace ECidade\Financeiro\Contabilidade\PlanoDeContas\Orcamento\Atualizacao\Ementario;

use ECidade\Financeiro\Contabilidade\PlanoDeContas\Atualizacao\Importacao;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP\Importacao\Modelo;
use PhpOffice\PhpWord\Exception\Exception;

/**
 * Class Receita
 * @package ECidade\Financeiro\Contabilidade\PlanoDeContas\Orcamento\Atualizacao\Ementario
 */
class Receita
{

    /**
     * @type integer
     */
    const MODELO_TCE_RS = 1000;

    /**
     * @type integer
     */
    const MODELO_STN = 1001;

    /**
     * @type integer
     */
    const ANO_IMPLANTACAO = 2018;

    /**
     * @type integer
     */
    const ANO_ANTERIOR_IMPLANTACAO = 2017;

    /**
     * @var integer
     */
    private $modelo;

    /**
     * @var \File
     */
    private $arquivo;

    /**
     * @var \DBDate
     */
    private $dataImportacao;

    /**
     * @var integer
     */
    private static $iAnoImplantacao = self::ANO_IMPLANTACAO;

    public function __construct()
    {
        self::$iAnoImplantacao = self::ANO_IMPLANTACAO;
    }
    
    /**
     * @param $iAnoImplantacao integer
     */
    public function setAnoImplantacao($iAnoImplantacao)
    {
        self::$iAnoImplantacao = $iAnoImplantacao;
    }

    /**
     * @param $modelo
     */
    public function setModelo($modelo)
    {
        $this->modelo = $modelo;
    }

    /**
     * @param \DBDate $dataImportacao
     */
    public function setDataImportacao(\DBDate $dataImportacao)
    {
        $this->dataImportacao = $dataImportacao;
    }

    /**
     * @param \File $arquivo
     */
    public function setArquivo(\File $arquivo)
    {
        $this->arquivo = $arquivo;
    }

    /**
     * Verifica se existe uma importação de receita realizada.
     * @return integer|bool
     * @throws \DBException
     */
    public function possuiImportacaoRealizada()
    {
        $daoImportacao = new \cl_importacaoplanoconta();
        $buscaImportacao = $daoImportacao->sql_query(null, "importacaoplanoconta.*", null, "c94_exercicio = ". self::$iAnoImplantacao);
        $resBuscaImportacao = db_query($buscaImportacao);
        if (!$resBuscaImportacao) {
            throw new \DBException("Ocorreu um erro ao consultar a ultima importação realizada.");
        }
        return pg_num_rows($resBuscaImportacao) > 0 ? \db_utils::fieldsMemory($resBuscaImportacao, 0)->c96_sequencial : false;
    }

    /**
     * @return bool|Importacao
     * @throws \DBException
     */
    public function getImportacao()
    {
        if (empty($this->modelo)) {

            $importacaoRealizada = $this->possuiImportacaoRealizada();
            if (!empty($importacaoRealizada)) {
                return Importacao::get($importacaoRealizada);
            }
        }
        return false;
    }

    /**
     * @throws \BusinessException
     * @throws \DBException
     * @throws \Exception
     */
    public function processarArquivo()
    {
        $this->removerTodosVinculos();

        $importacao = new Importacao($this->modelo);
        $importacao->setDataImportacao($this->dataImportacao);
        $importacao->salvar();

        $this->cadastrarEmentarioReceita();

        $contasDoModelo = $this->getModelo();
        $estruturaisJaVinculados = array();
        $arquivo = file($this->arquivo->getFilePath());
        foreach ($arquivo as $indice => $linha) {

            $linha = preg_replace("/\n/", '', $linha);
            $grupoEstrutural = (int)substr($linha, 0, 1);
            if (!in_array($grupoEstrutural, array(1,4))) {
                continue;
            }

            list($estruturalNovo, $estruturalAntigo) = explode('|', $linha);

            if (empty($estruturalNovo) || empty($estruturalAntigo)) {
                continue;
            }

            if (!array_key_exists($estruturalNovo, $contasDoModelo)) {
                throw new \BusinessException("Estrutural [{$estruturalNovo}] encontrado no arquivo não está presente no ementário da receita.");
            }

            $daoOrcamento = new \cl_conplanoorcamento();
            $estruturalSemMascara = str_replace('.', '', $estruturalAntigo);
            $where = " conplanoorcamento.c60_estrut = '{$estruturalSemMascara}' limit 1 ";
            $buscaEstrutural = $daoOrcamento->sql_query_file(null, null, 'c60_codcon', null, $where);
            $resBuscaEstrutural = db_query($buscaEstrutural);
            if (!$resBuscaEstrutural) {
                throw new \DBException("Ocorreu um erro ao consultar o estrutural {$estruturalAntigo}.");
            }

            if (pg_num_rows($resBuscaEstrutural) === 0) {
                continue;
            }

            $codigoConta = \db_utils::fieldsMemory($resBuscaEstrutural, 0)->c60_codcon;

            $codigoContaDetalhe = $contasDoModelo[$estruturalNovo];
            self::salvarVinculo($codigoContaDetalhe, $codigoConta);
            $estruturaisJaVinculados[] = str_replace('.', '', $estruturalNovo);
        }

        foreach ($contasDoModelo as $estrutural => $codigoContaDetalhe) {

            $estrutural = str_replace('.', '', $estrutural);
            if (in_array($estrutural, $estruturaisJaVinculados)) {
                continue;
            }

            $contaOrcamento = \ContaOrcamentoRepository::getContaPorEstrutural($estrutural, self::$iAnoImplantacao);
            if (empty($contaOrcamento)) {
                continue;
            }
            self::salvarVinculo($codigoContaDetalhe, $contaOrcamento->getCodigo());
        }

    }

    public function cadastrarEmentarioReceita()
    {
        $arquivo = file($this->arquivo->getFilePath());
        foreach ($arquivo as $indiceArquivo => $linha) {

            $linha = preg_replace("/\n/", '', $linha);
            $dadosLinha = explode("|", $linha);

            if (count($dadosLinha) !== 5) {
                $indiceArquivo++;
                throw new \ParameterException("A linha {$indiceArquivo} do arquivo importado possui elementos faltando. Verifique.");
            }

            $estruturalNovo = $dadosLinha[0];
            $descricao      = $dadosLinha[2];
            $funcao         = $dadosLinha[3];
            $analitica      = $dadosLinha[4];
            if (!preg_match("/[0-9]/", $dadosLinha[0])) {
                continue;
            }

            if (strlen($estruturalNovo) <> 25) {

                $indiceArquivo++;
                throw new \BusinessException("O esturural {$estruturalNovo} da linha {$indiceArquivo} deve conter exatamente 25 caracteres.");
            }

            $daoDetalhe = new \cl_planocontadetalhe();
            $daoDetalhe->c95_sequencial = null;
            $daoDetalhe->c95_modeloplanoconta   = $this->modelo;
            $daoDetalhe->c95_estrutural         = $estruturalNovo;
            $daoDetalhe->c95_titulo             = mb_strtoupper($descricao);
            $daoDetalhe->c95_funcao             = mb_strtoupper($funcao);
            $daoDetalhe->c95_naturezasaldo      = 2;
            $daoDetalhe->c95_analitica          = trim(strtolower($analitica)) === 'sim';
            $daoDetalhe->c95_sistema            = '0';
            $daoDetalhe->c95_indicadorsuperavit = 'N';
            $daoDetalhe->c95_excluir            = false;
            $daoDetalhe->incluir(null);
            if ($daoDetalhe->erro_status === '0') {
                throw new \DBException("Ocorreu um erro ao salvar os dados detalhados da conta. {$daoDetalhe->erro_msg}");
            }
        }
    }

    /**
     * Cria vínculo entre o Plano Orçamentário e o Ementário da Receita.
     *
     * @param integer $codigoPlanoContaDetalhe
     * @param integer $codigoContaOrcamento
     *
     * @throws \Exception
     */
    public static function salvarVinculo($codigoPlanoContaDetalhe, $codigoContaOrcamento)
    {
        self::removerVinculo($codigoPlanoContaDetalhe, $codigoContaOrcamento);

        $daoVinculo = new \cl_planocontadetalheconplanoorcamento();
        $daoVinculo->c97_sequencial = null;
        $daoVinculo->c97_planocontadetalhe = $codigoPlanoContaDetalhe;
        $daoVinculo->c97_conplanoorcamento = $codigoContaOrcamento;
        $daoVinculo->incluir(null);
        if ($daoVinculo->erro_status === '0') {
            throw new \Exception("Não foi possível víncular os dados.");
        }

        self::atualizarOrcamento($codigoPlanoContaDetalhe, $codigoContaOrcamento);
    }

    /**
     * Remove o vínculo entre Plano Orçamentário e o Ementário da Receita.
     *
     * @param integer|null $codigoPlanoContaDetalhe
     * @param integer|null $codigoContaOrcamento
     * @throws \Exception
     */
    public static function removerVinculo($codigoPlanoContaDetalhe = null, $codigoContaOrcamento = null)
    {
        if (empty($codigoPlanoContaDetalhe) && empty($codigoContaOrcamento)) {
            throw new \Exception('Deve ser informado o código da Plano Conta Detalhe ou o Código do Plano Orçamentário');
        }

        $where = array();

        if (!empty($codigoPlanoContaDetalhe)) {
            $where[] = 'c97_planocontadetalhe = ' . $codigoPlanoContaDetalhe;
        }

        if (!empty($codigoContaOrcamento)) {
            $where[] = 'c97_conplanoorcamento = ' . $codigoContaOrcamento;
        }

        $daoVinculo = new \cl_planocontadetalheconplanoorcamento();
        $sqlBuscaVinculo = $daoVinculo->sql_query_file(null, "*", null, "c97_planocontadetalhe = {$codigoPlanoContaDetalhe}");
        $resBuscaVinculo = db_query($sqlBuscaVinculo);
        if (!$resBuscaVinculo) {
            throw new \DBException("Ocorreu um erro ao consultar o vínculo existente.");
        }
        if (pg_num_rows($resBuscaVinculo) > 0) {

            $codigoConta = \db_utils::fieldsMemory($resBuscaVinculo, 0)->c97_conplanoorcamento;
            $daoOrcamento = new \cl_conplanoorcamento();
            $whereContaOrcamento = " c60_codcon = {$codigoConta} and c60_anousu <= ". self::ANO_ANTERIOR_IMPLANTACAO;
            $sqlBuscaOrcamentoAnterior = $daoOrcamento->sql_query_file(null, null, "c60_codcon", null, $whereContaOrcamento);
            $resBuscaOrcamentoAnterior = db_query($sqlBuscaOrcamentoAnterior);
            if (!$resBuscaOrcamentoAnterior) {
                throw new \DBException("Ocorreu um erro ao consultar a conta {$codigoConta} para o ano de ". self::ANO_ANTERIOR_IMPLANTACAO .".");
            }
            if (pg_num_rows($resBuscaOrcamentoAnterior) === 0) {

                $daoFonteReceita = new \cl_orcfontes();
                $daoFonteReceita->excluir(null, null, "o57_codfon = {$codigoConta}");
                if ($daoFonteReceita->erro_status === '0') {
                    throw new \DBException("Ocorreu um erro ao excluir a fonte de receita da conta {$codigoConta}.");
                }
                $daoOrcamento->excluir(null, null, "c60_codcon = {$codigoConta}");

                if ($daoOrcamento->erro_status === '0') {
                    throw new \DBException("Ocorreu um erro ao excluir a conta de receita {$codigoConta}.");
                }
            }
            self::retornarInformacoesAnteriores($codigoConta);
        }
        self::retornarInformacoesAnteriores($codigoContaOrcamento);

        $sSql = 'DELETE FROM planocontadetalheconplanoorcamento WHERE ' . implode(' OR ', $where);
        $result = db_query($sSql);
        if (!$result) {
            throw new \Exception('Ocorreu um erro ao remover os vínculos.');
        }
    }

    /**
     * Atualiza as informações do vinculo que a conta possuia anteriormente para as informações originais de 2017
     * @param $codigoConta
     * @throws \DBException
     */
    public static function retornarInformacoesAnteriores($codigoConta)
    {
        $sqlRetornaInformacoes = "
                update conplanoorcamento 
                   set c60_estrut = co.c60_estrut,  
                       c60_descr  = co.c60_descr,
                       c60_finali = co.c60_finali,  
                       c60_funcao = co.c60_funcao  
                  from conplanoorcamento co 
                 where conplanoorcamento.c60_codcon = co.c60_codcon 
                   and co.c60_anousu = ".self::ANO_ANTERIOR_IMPLANTACAO."
                   and conplanoorcamento.c60_anousu >=". self::$iAnoImplantacao ."
                   and co.c60_codcon = {$codigoConta};
        ";
        $updateOrcamento = db_query($sqlRetornaInformacoes);
        if (!$updateOrcamento) {
            throw new \DBException("Ocorreu um erro ao retornar os dados do orçamento.");
        }

        $updateOrcamentoReceita = db_query("
                update orcfontes 
                   set o57_fonte = co.c60_estrut,  
                       o57_descr  = co.c60_descr,
                       o57_finali = co.c60_finali                       
                  from conplanoorcamento co 
                 where orcfontes.o57_codfon = co.c60_codcon 
                   and co.c60_anousu = ".self::ANO_ANTERIOR_IMPLANTACAO."
                   and orcfontes.o57_anousu >= ". self::$iAnoImplantacao ."
                   and co.c60_codcon = {$codigoConta};
            ");
        if (!$updateOrcamentoReceita) {
            throw new \DBException("Ocorreu um erro ao retornar os dados do orçamento de receita.");
        }

        $updateOrcamentoTesouraria = db_query("
            update taborc
               set k02_estorc = tbo.k02_estorc
              from taborc tbo, orcreceita
             where taborc.k02_codrec = tbo.k02_codrec
               and taborc.k02_codigo = tbo.k02_codigo
               and tbo.k02_anousu = ".self::ANO_ANTERIOR_IMPLANTACAO."
               and taborc.k02_anousu >= ". self::$iAnoImplantacao ."
               and orcreceita.o70_codfon = {$codigoConta};
        ");
        if (!$updateOrcamentoTesouraria) {
            throw new \DBException("Ocorreu um erro ao alterar os dados da receita na tesouraria.");
        }

    }

    /**
     * @return array
     */
    protected function getModelo()
    {
        $retorno = array();
        $buscaEmentario = pg_query("select * from planocontadetalhe where c95_modeloplanoconta = {$this->modelo}");

        $totalRegistros = pg_num_rows($buscaEmentario);
        for ($row = 0; $row < $totalRegistros; $row++) {

            $stdEmentario = \db_utils::fieldsMemory($buscaEmentario, $row);
            $retorno[$stdEmentario->c95_estrutural] = $stdEmentario->c95_sequencial;
        }
        return $retorno;
    }

    /**
     * Retorna os registros da orcamentoreceita para a conplano e remove os vinculos criados na importação anterior
     * @throws \DBException
     */
    private function removerTodosVinculos()
    {
        $updateDesfazerImportacao = "
            update conplanoorcamento 
               set c60_estrut = c98_estrutural, 
                   c60_descr = c98_descricao, 
                   c60_finali = c98_finalidade, 
                   c60_funcao = c98_funcao 
              from orcamentoreceita 
             where c98_codcon = c60_codcon 
               and c98_anousu = c60_anousu
               and c60_anousu >= ". self::$iAnoImplantacao .";
       ";

        $resUpdateOrcamento = db_query($updateDesfazerImportacao);

        if (!$resUpdateOrcamento) {
            throw new \DBException("Ocorreu um erro ao retornar os registros para o orçamento.");
        }

        $updateDesfazerImportacaoReceita = "
            update orcfontes
               set  o57_fonte  = c98_estrutural
                   ,o57_descr  = c98_descricao
                   ,o57_finali = c98_finalidade
              from orcamentoreceita 
             where c98_codcon = o57_codfon 
               and o57_anousu = c98_anousu
               and o57_anousu >= ". self::$iAnoImplantacao .";
        ";

        $resImportacaoReceita = db_query($updateDesfazerImportacaoReceita);

        if (!$resImportacaoReceita) {
            throw new \DBException("Ocorreu um erro ao retornar os registros do orçamento de receita.");
        }

        $updateOrcamentoTesouraria = "
            update taborc
               set k02_estorc = tbo.k02_estorc
              from taborc tbo
             where tbo.k02_codrec = taborc.k02_codrec
               and tbo.k02_codigo = taborc.k02_codigo
               and tbo.k02_anousu = ".self::ANO_ANTERIOR_IMPLANTACAO." 
               and taborc.k02_anousu >= ". self::$iAnoImplantacao .";
        ";

        $resOrcamentoTesouraria = db_query($updateOrcamentoTesouraria);
        if (!$resOrcamentoTesouraria) {
            throw new \DBException("Ocorreu um erro ao retornar os registros do orçamento de receita da tesouraria.");
        }

        $resDeleteVinculos = db_query('delete from planocontadetalheconplanoorcamento;');
        if (!$resDeleteVinculos) {
            throw new \DBException("Ocorreu um erro ao remover os vinculos criados na importação anterior.");
        }

        $daoImportacao = new \cl_importacaoplanoconta();
        $buscaImportacao = $daoImportacao->sql_query(null, "importacaoplanoconta.*", null, "c94_exercicio = ". self::$iAnoImplantacao);
        $resBuscaImportacao = db_query($buscaImportacao);
        if (!$resBuscaImportacao) {
            throw new \DBException("Ocorreu um erro ao consultar a importação já realizada.");
        }
        if (pg_num_rows($resBuscaImportacao) > 0) {

            $stdDadosImportacao = \db_utils::fieldsMemory($resBuscaImportacao, 0);
            $daoImportacao->excluir($stdDadosImportacao->c96_sequencial);
            $deletarDetalhes = db_query('delete from planocontadetalhe where c95_modeloplanoconta = '.$stdDadosImportacao->c96_modeloplanoconta);
            if (!$deletarDetalhes) {
                throw new \DBException("Ocorreu um erro ao excluir os detalhamentos de contas.");
            }
        }


    }

    /**
     * @param $codigoPlanoContaDetalhe
     * @param $codigoContaOrcamento
     * @throws \BusinessException
     * @throws \DBException
     */
    public static function atualizarOrcamento($codigoPlanoContaDetalhe, $codigoContaOrcamento)
    {

        $where = implode(' and ', array(
                "c60_anousu >= ". self::$iAnoImplantacao,
                "substr(c60_estrut ,1,1)::integer in (4,9)",
                "c60_codcon = {$codigoContaOrcamento}",
            )) . " order by c60_anousu";
        $daoOrcamento  = new \cl_conplanoorcamento();
        $sqlBuscaConta = $daoOrcamento->sql_query_orcamento_receita("*", $where);
        $resBuscaConta = db_query($sqlBuscaConta);
        if (!$resBuscaConta) {
            throw new \DBException("Ocorreu um erro ao consultar a conta {$codigoContaOrcamento}.");
        }

        $totalRegistro = pg_num_rows($resBuscaConta);
        for ($rowConta = 0; $rowConta < $totalRegistro; $rowConta++) {

            $stdOrcamento = \db_utils::fieldsMemory($resBuscaConta, $rowConta);

            $daoOrcamentoReceita = new \cl_orcamentoreceita();
            $sqlBuscaContaOrcamento = $daoOrcamentoReceita->sql_query_file(null, "*", null, "c98_codcon = {$codigoContaOrcamento} and c98_anousu = {$stdOrcamento->c60_anousu}");
            $resBuscaContaOrcamento = db_query($sqlBuscaContaOrcamento);
            if (pg_num_rows($resBuscaContaOrcamento) === 0) {

                $daoOrcamentoReceita->c98_sequencial              = null;
                $daoOrcamentoReceita->c98_codcon                  = $codigoContaOrcamento;
                $daoOrcamentoReceita->c98_anousu                  = $stdOrcamento->c60_anousu;
                $daoOrcamentoReceita->c98_estrutural              = $stdOrcamento->c60_estrut;
                $daoOrcamentoReceita->c98_descricao               = strtoupper($stdOrcamento->c60_descr);
                $daoOrcamentoReceita->c98_finalidade              = !empty($stdOrcamento->c60_finali) ? $stdOrcamento->c60_finali : $stdOrcamento->c60_descr;
                $daoOrcamentoReceita->c98_codsis                  = $stdOrcamento->c60_codsis;
                $daoOrcamentoReceita->c98_codcla                  = $stdOrcamento->c60_codcla;
                $daoOrcamentoReceita->c98_consistemaconta         = $stdOrcamento->c60_consistemaconta;
                $daoOrcamentoReceita->c98_identificadorfinanceiro = $stdOrcamento->c60_identificadorfinanceiro;
                $daoOrcamentoReceita->c98_naturezasaldo           = $stdOrcamento->c60_naturezasaldo;
                $daoOrcamentoReceita->c98_funcao                  = !empty($stdOrcamento->c60_funcao) ? $stdOrcamento->c60_funcao : $stdOrcamento->c60_descr;
                $daoOrcamentoReceita->c98_codrec                  = $stdOrcamento->o70_codrec;
                $daoOrcamentoReceita->c98_recurso                 = $stdOrcamento->o70_codigo;
                $daoOrcamentoReceita->c98_valor                   = $stdOrcamento->o70_valor;
                $daoOrcamentoReceita->c98_receitalancada          = $stdOrcamento->o70_reclan;
                $daoOrcamentoReceita->c98_instit                  = $stdOrcamento->o70_instit;
                $daoOrcamentoReceita->c98_concarpeculiar          = $stdOrcamento->o70_concarpeculiar;
                $daoOrcamentoReceita->c98_datacriacao             = $stdOrcamento->o70_datacriacao;
                $daoOrcamentoReceita->incluir(null);
                if ($daoOrcamentoReceita->erro_status === '0') {
                    throw new \DBException("Ocorreu um erro ao salvar os dados do plano orcamentário ". $daoOrcamentoReceita->erro_msg);
                }
            }

            $daoDetalhe = new \cl_planocontadetalhe();
            $sqlDetalhe = $daoDetalhe->sql_query_file($codigoPlanoContaDetalhe);
            $resDetalhe = db_query($sqlDetalhe);
            if (!$resDetalhe || pg_num_rows($resDetalhe) === 0) {
                throw new \DBException("Ocorreu um erro ao consultar o detalhamento da conta a ser vinculada.");
            }

            $stdDetalhe = \db_utils::fieldsMemory($resDetalhe, 0);
            $stdDetalhe->c95_estrutural = str_replace('.', '', $stdDetalhe->c95_estrutural);

            $contaOrcamento = \ContaOrcamentoRepository::getContaPorEstrutural($stdDetalhe->c95_estrutural, $stdOrcamento->c60_anousu);
            if (!empty($contaOrcamento) && ($stdDetalhe->c95_estrutural !== $stdOrcamento->c60_estrut) ) {

                $mensagem  = "O estrutural {$stdDetalhe->c95_estrutural} ja encontra-se em uso para o ano de {$stdOrcamento->c60_anousu}. ";
                $mensagem .= "É necessário vincular esta conta do plano orçamentário em outra conta do ementário da receita.";
                throw new \BusinessException($mensagem);
            }

            $daoOrcamento->c60_codcon = $stdOrcamento->c60_codcon;
            $daoOrcamento->c60_anousu = $stdOrcamento->c60_anousu;
            $daoOrcamento->c60_estrut = $stdDetalhe->c95_estrutural;
            $daoOrcamento->c60_descr  = strtoupper(substr($stdDetalhe->c95_titulo, 0, 50));
            $daoOrcamento->c60_finali = strtoupper($stdDetalhe->c95_titulo);
            $daoOrcamento->c60_funcao = strtoupper($stdDetalhe->c95_funcao);
            $daoOrcamento->alterar($daoOrcamento->c60_codcon, $daoOrcamento->c60_anousu);
            if ($daoOrcamento->erro_status === '0') {
                throw new \BusinessException("Ocorreu um erro ao alterar os dados da conta com estrutura: {$stdOrcamento->c60_estrut}.");
            }

            $daoFonteReceita = new \cl_orcfontes();
            $daoFonteReceita->o57_codfon = $stdOrcamento->c60_codcon;
            $daoFonteReceita->o57_anousu = $stdOrcamento->c60_anousu;
            $daoFonteReceita->o57_fonte  = $daoOrcamento->c60_estrut;
            $daoFonteReceita->o57_descr  = $daoOrcamento->c60_descr;
            $daoFonteReceita->o57_finali = $daoOrcamento->c60_finali;
            $daoFonteReceita->alterar($daoFonteReceita->o57_codfon, $daoFonteReceita->o57_anousu);
            if ($daoFonteReceita->erro_status === '0') {
                throw new \BusinessException("Ocorreu um erro ao alterar os dados da fonte de receita para o estrutural: {$stdOrcamento->c60_estrut}.");
            }

            $whereTesouraria = implode(' and ', array(
                "conplanoorcamento.c60_codcon = {$stdOrcamento->c60_codcon}",
                "taborc.k02_anousu = ". self::$iAnoImplantacao
            ));

            $daoTesouraria = new \cl_taborc();
            $resTesouraria = db_query($daoTesouraria->sql_query_receita("taborc.*", $whereTesouraria));
            if (pg_num_rows($resTesouraria) > 0) {

                $stdReceita = \db_utils::fieldsMemory($resTesouraria, 0);
                $daoTesouraria->k02_codigo = $stdReceita->k02_codigo;
                $daoTesouraria->k02_anousu = $stdReceita->k02_anousu;
                $daoTesouraria->k02_codrec = $stdReceita->k02_codrec;
                $daoTesouraria->k02_estorc = $daoOrcamento->c60_estrut;
                $daoTesouraria->alterar($daoTesouraria->k02_anousu, $daoTesouraria->k02_codigo);
                if ($daoTesouraria->erro_status === '0') {
                    throw new \DBException("Ocorreu um erro ao alterar os registros da receita da tesouraria.");
                }
            }
        }
    }

    /**
     * @param $codigoEmentario
     * @throws \DBException
     * @throws \Exception
     * @throws \ParameterException
     */
    public static function importarContaParaOrcamento($codigoEmentario)
    {

        if (empty($codigoEmentario)) {
            throw new \ParameterException("Informe uma conta do ementário da receita.");
        }

        $daoEmentario = new \cl_planocontadetalhe();
        $sqlContaEmentario = $daoEmentario->sql_query_file($codigoEmentario);
        $resContaEmentario = db_query($sqlContaEmentario);
        if (!$resContaEmentario) {
            throw new \DBException("Ocorreu um erro ao consultar a conta do ementário de receita.");
        }
        $stdEmentario = \db_utils::fieldsMemory($resContaEmentario, 0);

        $daoOrcamento = new \cl_conplanoorcamento();
        $buscaUltimoaAno = $daoOrcamento->sql_query_file(null, null, 'max(c60_anousu) as c60_anousu');
        $resBuscaUltimoAno = db_query($buscaUltimoaAno);
        if (!$resBuscaUltimoAno) {
            throw new \DBException("Não foi possível verificar o último ano disponível.");
        }

        $ultimoAno = \db_utils::fieldsMemory($resBuscaUltimoAno, 0)->c60_anousu;
        $codigoContaOrcamento = null;
        for ($ano = self::$iAnoImplantacao; $ano <= $ultimoAno; $ano++) {

            $estrutural = str_replace('.', '', $stdEmentario->c95_estrutural);
            $daoOrcamento->c60_estrut                  = $estrutural;
            $daoOrcamento->c60_descr                   = mb_strtoupper(substr($stdEmentario->c95_titulo, 0, 50));
            $daoOrcamento->c60_finali                  = mb_strtoupper($stdEmentario->c95_titulo);
            $daoOrcamento->c60_codsis                  = '0';
            $daoOrcamento->c60_codcla                  = '4';
            $daoOrcamento->c60_consistemaconta         = '0';
            $daoOrcamento->c60_identificadorfinanceiro = 'N';
            $daoOrcamento->c60_naturezasaldo           = "2";
            $daoOrcamento->c60_funcao                  = mb_strtoupper($stdEmentario->c95_funcao);
            $daoOrcamento->c60_anousu                  = $ano;
            $daoOrcamento->c60_codcon                  = $codigoContaOrcamento;
            $daoOrcamento->incluir($daoOrcamento->c60_codcon, $daoOrcamento->c60_anousu);
            $codigoContaOrcamento = $daoOrcamento->c60_codcon;
            if ($daoOrcamento->erro_status === "0") {
                throw new \DBException("Ocorreu um erro para cadastrar a conta com estrutural: {$stdEmentario->c95_estrutural}.");
            }

            $daoFontesReceita = new \cl_orcfontes();
            $daoFontesReceita->o57_codfon = $codigoContaOrcamento;
            $daoFontesReceita->o57_anousu = $daoOrcamento->c60_anousu;
            $daoFontesReceita->o57_fonte  = $estrutural;
            $daoFontesReceita->o57_descr  = mb_strtoupper(substr($stdEmentario->c95_titulo, 0, 50));
            $daoFontesReceita->o57_finali = mb_strtoupper($stdEmentario->c95_titulo);
            $daoFontesReceita->incluir($daoFontesReceita->o57_codfon, $daoFontesReceita->o57_anousu);
            if ($daoFontesReceita->erro_status === "0") {
                throw new \DBException("Ocorreu um erro ao incluir o estrutural {$stdEmentario->c95_estrutural} como fonte de receita.");
            }
        }
        self::salvarVinculo($codigoEmentario, $codigoContaOrcamento);
    }

}
