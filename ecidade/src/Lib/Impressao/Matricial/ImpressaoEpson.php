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


namespace ECidade\Lib\Impressao\Matricial;

use cl_cfautent;
use cl_db_plugin;
use db_utils;
use Exception;
use impressao;

abstract class ImpressaoEpson
{
    /**
     * @var string
     */
    private $ipAutenticadora;

    /**
     * @var int
     */
    private $portaAutenticadora;

    /**
     * @var bool
     */
    private $utilizarAutenticadoraNova = true;

    const QUEBRA_LINHA = "QUEBRA_LINHA";
    const TAB = "TAB";
    const QUEBRA_PAGINA = "QUEBRA_PAGINA";
    const HABILITAR_MODO_CONDENSADO = "HABILITAR_MODO_CONDENSADO";
    const DESABILITAR_MODO_CONDENSADO = "DESABILITAR_MODO_CONDENSADO";
    const HABILITAR_NEGRITO = "HABILITAR_NEGRITO";
    const DESABILITAR_NEGRITO = "DESABILITAR_NEGRITO";
    const INICIALIZAR = "INICIALIZAR";
    const SEPARADOR = "SEPARADOR";
    const FONT_12_CPI = "FONT_12_CPI";
    const FONT_15_CPI = "FONT_15_CPI";

    /**
     * @var Integer
     * Tamanho da linha (número de caracteres/colunas);
     */
    protected $tamanhoLinha = 145;

    /**
     * @var Integer
     * Tamanho da pagina (número de linhas que uma página tem);
     */
    protected $tamanhoPagina = 75;

    /**
     * @var Integer
     * Contador de número de linhas para gerenciamento de quebra de página
     */
    protected $linhas = 0;

    /**
     * @var Text
     * Variável que guarda o conteudo do que será impresso
     */
    protected $conteudo = '';

    /**
     * @var Array
     * Variável que os códigos utilizados na impressao
     */
    protected $arrCodigos;

    public function __construct()
    {

        $this->arrCodigos = array(
            self::QUEBRA_LINHA => "\n",
            self::TAB => "\r",
            self::QUEBRA_PAGINA => chr(12),
            self::HABILITAR_MODO_CONDENSADO => chr(15),
            self::DESABILITAR_MODO_CONDENSADO => chr(18),
            self::HABILITAR_NEGRITO => Chr(27) . Chr(69),
            self::DESABILITAR_NEGRITO => Chr(27) . Chr(70),
            self::INICIALIZAR => chr(27).chr(64),
            self::SEPARADOR => str_repeat("-", 110),
            self::FONT_12_CPI => chr(27) . chr(77),
            self::FONT_15_CPI => chr(27) . chr(103)
        );
    }

    /**
     * @return ImpressaoMatricial
     * Getter para o tamanho da pagina (número de linhas que uma página tem);
     */
    public function setTamanhoPagina($tamanhoPagina)
    {
        $this->tamanhoPagina = $tamanhoPagina;
        return $this;
    }

    /**
     * @return Integer
     * Getter para o tamanho da pagina (número de linhas que uma página tem);
     */
    public function getTamanhoPagina()
    {
        return $this->tamanhoPagina;
    }

    /**
     * @return ImpressaoMatricial
     * Setter para o tamanho da linha (número de caracteres/colunas);
     */
    public function setTamanhoLinha($tamanhoLinha)
    {
        $this->tamanhoLinha = $tamanhoLinha;
        return $this;
    }

    /**
     * @return Integer
     * Getter para o tamanho da linha (número de caracteres/colunas);
     */
    public function getTamanhoLinha()
    {
        return $this->tamanhoLinha;
    }

    /**
     * @return Integer
     * Seta o número atual de linhas;
     */
    public function getLinhas()
    {
        return $this->linhas;
    }

    /**
     * @return Integer
     * Seta o número atual de linhas;
     */
    public function setLinhas($linhas)
    {
        $this->linhas = $linhas;
        return $this;
    }

    /**
     * @return Integer
     * Adiciona linhas ao número atual de linhas;
     */
    public function adicionaLinhas($linhas)
    {
        $this->linhas += $linhas;
        return $this;
    }

    /**
     * @return ImpressaoEpson
     * Adiciona texto ao conteudo do documento;
     */
    public function adicionaConteudo($texto)
    {
        $this->conteudo .= $texto;
        return $this;
    }

    /**
     * @return Text
     * Retorna o conteudo
     */
    public function getConteudo()
    {
        return $this->conteudo;
    }

    /**
     * Função getCabecalho
     * Retorna o cabecalho configurado para o txt.
     */
    abstract public function getCabecalho();

    public function imprimirCabecalho()
    {
        $this->adicionaConteudo($this->arrCodigos[self::FONT_15_CPI]);
        $this->adicionaConteudo($this->getCabecalho() . $this->arrCodigos[self::QUEBRA_LINHA]);
        $this->adicionaConteudo($this->arrCodigos[self::FONT_12_CPI]);
    }

    /**
     * Função quebraPagina
     * Quebra a página do txt
     */
    public function quebrarPagina($bCabecalho = true)
    {
        $this->adicionaConteudo($this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA])
             ->adicionaConteudo($this->arrCodigos[self::FONT_15_CPI])
             ->adicionaConteudo(" {$this->arrCodigos[self::SEPARADOR]} ")
             ->adicionaConteudo($this->arrCodigos[self::FONT_12_CPI])
             ->adicionaConteudo($this->arrCodigos[self::TAB] . $this->arrCodigos[self::QUEBRA_LINHA])
             ->adicionaConteudo($this->arrCodigos[self::QUEBRA_PAGINA])
            ->setLinhas(1);

        if ($bCabecalho) {
            $this->imprimirCabecalho();
        }

        return $this;
    }
    /**
     * Função validaQuebraPagina
     * Valida se deve quebrar a pagina do txt
     */
    public function validaQuebraPagina($texto)
    {
        $this->linhas += ceil(strlen($texto) / $this->tamanhoLinha);

        if ($this->linhas > $this->tamanhoPagina) {
            $this->quebrarPagina();
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    protected function buscarAutenticadoraConfigurada()
    {
        $ipComputador = db_getsession("DB_ip");
        $daoAutenticadora = new cl_cfautent();
        $camposAutenticadoras = "k11_ipimpcheque, k11_portaimpcheque";
        $whereAutenticadora = "k11_ipterm = '{$ipComputador}'";
        $sqlAutenticadora = $daoAutenticadora->sql_query_file(null, $camposAutenticadoras, null, $whereAutenticadora);
        $rsAutenticadora = db_query($sqlAutenticadora);

        if (!$rsAutenticadora) {
            throw new Exception("Erro ao buscar autenticadora cadastrada para o computador ($ipComputador).");
        }

        if (pg_num_rows($rsAutenticadora) == 0) {
            $mensagem = "Não foi encontrada nenhuma autenticadora cadastrada para o computador ($ipComputador).\n\n";
            $mensagem .= "Acesse: DB:FINANCEIRO > Tesouraria > Cadastros > Autenticadoras > Inclusão de Autenticadora.";
            throw new Exception($mensagem);
        }

        $dados = db_utils::fieldsMemory($rsAutenticadora, 0);
        $this->ipAutenticadora = $dados->k11_ipimpcheque;
        $this->portaAutenticadora = $dados->k11_portaimpcheque;
    }

    /**
     * @throws Exception
     */
    protected function imprimir()
    {
        $daoPlugin = new cl_db_plugin();
        $where = "db145_nome = 'autenticadora'";
        $where .= " AND db145_situacao is true";
        $sqlPlugins = $daoPlugin->sql_query_file(null, '1', null, $where);
        $rsPlugins = db_query($sqlPlugins);

        if (!$rsPlugins) {
            throw new Exception("Não foi possível verificar o plugin da Autenticadora.");
        }

        if (pg_num_rows($rsPlugins) > 0) {
            // Autenticadora Nova
            $impressora = new impressao();
            $impressora->setIp($this->ipAutenticadora);
            $impressora->setPorta($this->portaAutenticadora);
            $impressora->imprimir($this->conteudo);
        } else {
            // Autenticadora Antiga
            $this->utilizarAutenticadoraNova = false;

            $rImpressora = @fsockopen($this->ipAutenticadora, $this->portaAutenticadora);
            if (!$rImpressora) {
                throw new Exception("Não foi possivel conectar com impressora!");
            }
            fputs($rImpressora, $this->conteudo);
            fclose($rImpressora);
        }
    }

    /**
     * @return bool
     */
    public function isUtilizarAutenticadoraNova()
    {
        return $this->utilizarAutenticadoraNova;
    }
}
