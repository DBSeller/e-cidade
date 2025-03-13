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

namespace ECidade\Financeiro\Contabilidade\ContaCorrente\Services;

use ECidade\Financeiro\Contabilidade\ContaCorrente\Repository\ContaCorrente;
use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model\InformacaoComplementar;
use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model\Lancamento as LancamentoModel;
use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Repository\Lancamento as LancamentoRepository;

class Processamento
{

    /**
     * @var \Instituicao
     */
    private $instituicao;
    /**
     * @var \DBCompetencia
     */
    private $competencia;

    private $atributosContaCorrente = array();

    /**
     * Processamento constructor.
     * @param \Instituicao $instituicao
     * @param \DBCompetencia $competencia
     * @throws \DBException
     */
    public function __construct(\Instituicao $instituicao, \DBCompetencia $competencia)
    {
        $this->instituicao = $instituicao;
        $this->competencia = $competencia;
        if (!\DBRegistry::has('conta_corrente_atributos')) {
            $daoConplanoSistema = new \cl_conplanosistema();
            $sqlContaCorrentes = $daoConplanoSistema->sql_query_file(null, "c122_sequencial", null, " c122_tipo = 2");
            $rsContaCorrentes = db_query($sqlContaCorrentes);
            if (!$rsContaCorrentes) {
                throw new \DBException("Erro ao pesquisar dados dos conta correntes");
            }
            $this->atributosContaCorrente = \db_utils::makeCollectionFromRecord($rsContaCorrentes, function ($dados) {
                return $dados->c122_sequencial;
            });
            \DBRegistry::add('conta_corrente_atributos', $this->atributosContaCorrente);
        }
        $this->atributosContaCorrente = \DBRegistry::get('conta_corrente_atributos');
    }

    /**
     * @return \Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param \Instituicao $instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @return \DBCompetencia
     */
    public function getCompetencia()
    {
        return $this->competencia;
    }

    /**
     * @param \DBCompetencia $competencia
     */
    public function setCompetencia($competencia)
    {
        $this->competencia = $competencia;
    }

    /**
     * Mï¿½todo que retorna
     * @return array
     */
    public function getAtributosDoTipoContaCorrente()
    {

        // @todo - retirar os codigos fixos
        return $this->atributosContaCorrente;
    }

    /**
     * Defines quais atributos devemos processar
     * @param $atributos
     */
    public function seAtributosDoTipoContaCorrente($atributos)
    {
        $this->atributosContaCorrente = $atributos;
    }

    /**
     * Metodo que itera em um array de lancamentos e lanca os atributos
     * @param array $lancamento
     * @throws \ParameterException|\Exception
     */
    public function processar(array $lancamento, array $contas = null)
    {

        $listaHashes = array();
        $competencia = $this->competencia;
        $repository = LancamentoRepository::getInstance();
        $repository->setSistema(implode(", ", $this->getAtributosDoTipoContaCorrente()));
        $lancamentos = $repository->getQueryLancamentosPorCompetenciaPorContaCorrente(
            $competencia->getMes(),
            $competencia->getAno(),
            $this->instituicao->getCodigo(),
            $lancamento,
            $contas
        );
        $rsLancamentos = db_query($lancamentos);
        if (!$rsLancamentos) {
            $mensagem = "Não foi possível encontrar os atributos configurados para os lançamentos informados..";
            throw new \Exception($mensagem);
        }
        $totalLinhas = pg_num_rows($rsLancamentos);
        $lancamentos = array();

        for ($i = 0; $i < $totalLinhas; $i++) {
            $dadosLancamento = \db_utils::fieldsMemory($rsLancamentos, $i);

            if (!empty($contas) && !in_array($dadosLancamento->conta_reduzida, $contas)) {
                continue;
            }

            $lancamento = new LancamentoModel();

            $lancamento->setSistema($dadosLancamento->conta_corrente);
            $lancamento->setData(new \DBDate($dadosLancamento->data_lancamento));
            $lancamento->setValor($dadosLancamento->valor);
            $lancamento->setCodigoLancamento($dadosLancamento->codigo_lancamento);
            $lancamento->setNatureza($dadosLancamento->natureza);
            $lancamento->setTipoLancamento($dadosLancamento->tipo);

            $informacoesComplementares = self::montarInformacaoComplementar($dadosLancamento, $repository);
            foreach ($informacoesComplementares as $informacaoComplementar) {
                $lancamento->addInfoComplementares($informacaoComplementar);
            }
            $lancamentos[] = $lancamento;
        }
        foreach ($lancamentos as $lancamento) {
            $hashes = self::montarHashesDolancamento($lancamento);
            foreach ($hashes as $hash) {
                self::calcularValorMovimentacaoDoHash(
                    $hash,
                    $lancamento->getValor(),
                    $lancamento->getNatureza(),
                    $listaHashes,
                    $lancamento->getSistema()
                );
            }
        }

        self::salvarLancamentos($lancamentos, $repository);
        foreach ($listaHashes as $hash) {
            $saldoDohash = self::getSaldoDohash($hash, $this->instituicao->getCodigo());
            if (!$saldoDohash) {
                $this->calcularSaldoInicialDaConta($hash, $this->competencia->getAno(), $hash->sistema);
            }
            self::atualizaSaldoContaCorrente(
                $hash,
                $hash->valor,
                $this->competencia->getAno(),
                $this->competencia->getMes(),
                $hash->sistema,
                $this->getCompetencia()
            );
        }
    }

    /**
     * @param $lancamento
     * @param $repository
     * @return array
     */
    public static function montarInformacaoComplementar($lancamento, $repository)
    {

        $listaAtributos = explode(",", $lancamento->infos_complementares);

        $atributosDoLancamento = array();
        $dadosDosAtributos = $repository->getAtributos();

        foreach ($listaAtributos as $codigoAtributo) {
            $atributo = $dadosDosAtributos[$codigoAtributo];
            $informacaoComplementar = new InformacaoComplementar();
            $informacaoComplementar->setSigla($atributo->sigla);
            $informacaoComplementar->setDescricao($atributo->descricao);
            $informacaoComplementar->setConta($lancamento->conta);
            $informacaoComplementar->setAnousu($lancamento->anousu);
            $informacaoComplementar->setContaEstrutura($lancamento->estrutura);
            $informacaoComplementar->setCodigoLancamento($lancamento->codigo_lancamento);
            $informacaoComplementar->setContaReduzida($lancamento->conta_reduzida);
            $informacaoComplementar->setCodigoInstituicao($lancamento->instituicao);
            $informacaoComplementar->setCodigoInformacaoComplementar($codigoAtributo);
            $informacaoComplementar->setCodigoSistema($lancamento->conta_corrente);
            $valorAtributo = $lancamento->{$atributo->nome_propriedade};
            if (trim($valorAtributo) == '') {
                $valorAtributo = $atributo->valor_padrao;
            }
            $informacaoComplementar->setValor($valorAtributo);
            $atributosDoLancamento[] = $informacaoComplementar;
        }
        return $atributosDoLancamento;
    }


    /**
     * Atualiza dos valores dos contas correntes
     * @param $hash
     * @param $valor
     * @param $ano
     * @param $mes
     * @param $sistema
     * @param $competencia
     * @throws \ParameterException|\Exception
     */
    public static function atualizaSaldoContaCorrente($hash, $valor, $ano, $mes, $sistema, $competencia)
    {

        $saldo = self::getSaldoDohash($hash, db_getsession('DB_instit'));
        if (!$saldo) {
            $saldo = new \stdClass();
            $saldo->c125_sequencial = null;
            $saldo->c125_anousu = $ano;
            $saldo->c125_mesusu = $mes;
            $saldo->c125_hashcontaatributos = $hash->hash;
            $saldo->c125_valor = 0;
            $saldo->c125_natureza = "C";
            $saldo->c125_tipo = 3;
            $saldo->c125_conplanosistema = $sistema;
            $saldo->c125_instit = db_getsession('DB_instit');
        } else {
            $competenciaSaldo = null;
            if ($saldo->c125_mesusu != 0) {
                $competenciaSaldo = new \DBCompetencia($saldo->c125_anousu, $saldo->c125_mesusu);
            }

            if ((empty($competenciaSaldo)) ||
                (!empty($competenciaSaldo) && $competenciaSaldo->comparar(
                    $competencia,
                    \DBCompetencia::COMPARACAO_MENOR
                )
                )
            ) {
                $saldo->c125_sequencial = null;
                $saldo->c125_mesusu = $mes;
                $saldo->c125_anousu = $ano;
            }
        }


        $valorSaldo = $saldo->c125_natureza === 'C' ? $saldo->c125_valor * -1 : $saldo->c125_valor;
        $valorSaldo += $valor;

        $natureza = $valorSaldo <= 0 ? "C" : "D";

        $saldo->c125_valor = abs($valorSaldo);
        $saldo->c125_natureza = $natureza;

        if (empty($saldo->c125_sequencial)) {
            $insert = "insert into conplanoatributosaldo values (nextval('conplanoatributosaldo_c125_sequencial_seq'),";
            $insert .= " {$saldo->c125_anousu},";
            $insert .= " {$saldo->c125_mesusu},";
            $insert .= " '{$saldo->c125_hashcontaatributos}',";
            $insert .= " {$saldo->c125_valor},";
            $insert .= " '{$natureza}',";
            $insert .= " {$saldo->c125_tipo},";
            $insert .= " {$saldo->c125_conplanosistema},";
            $insert .= " {$saldo->c125_instit});";
            $insert = db_query($insert);
            if (!$insert) {
                $mensagem = "Erro ao incluir novo registro em conplanoatributosaldo." . pg_last_error();
                throw new \DBException($mensagem);
            }
        } else {
            $update = "update conplanoatributosaldo ";
            $update .= "   set c125_valor = {$saldo->c125_valor}, ";
            $update .= "       c125_natureza = '{$natureza}' ";
            $update .= " where c125_sequencial = {$saldo->c125_sequencial}";
            db_query($update);
        }
    }

    /**
     * Monta o hash do atributo
     * @param LancamentoModel $dadosDoLancamento
     *
     * @return array
     */
    public static function montarHashesDolancamento(LancamentoModel $dadosDoLancamento)
    {
        $hashes = array();
        foreach ($dadosDoLancamento->getInfoComplementares() as $informacaoComplementar) {
            $contaComSistema = $informacaoComplementar->getContaEstrutura() . "#";
            $contaComSistema .= $informacaoComplementar->getCodigoSistema();

            if (empty($hashes[$contaComSistema])) {
                $hash = new \stdClass();
                $hash->estrutural = $informacaoComplementar->getContaEstrutura();
                $hash->reduzido = $informacaoComplementar->getContaReduzida();
                $hash->atributos = array();
                $hashes[$contaComSistema] = $hash;
            }
            $hash = $hashes[$contaComSistema];
            $hash->atributos[] = $informacaoComplementar->getValor() . "#" . $informacaoComplementar->getSigla();
        }
        return $hashes;
    }

    /**
     *   Metodo para montar o hash dos atributos do conta corrente
     * @param $hash
     * @param $valor
     * @param $natureza
     * @param array $listaHashes
     */
    public static function calcularValorMovimentacaoDoHash(
        $hash,
        $valor,
        $natureza,
        &$listaHashes = array(),
        $sistema = null
    ) {

        $valorHash = "$hash->estrutural|" . implode("|", $hash->atributos);
        $valorFinanceiro = $natureza === "C" ? $valor * -1 : $valor;
        if (empty($listaHashes[$valorHash])) {
            $objetoHash = new \stdClass();
            $objetoHash->valor = 0;
            $objetoHash->natureza = $natureza;
            $objetoHash->hash = $valorHash;
            $objetoHash->sistema = $sistema;
            $objetoHash->reduzido = $hash->reduzido;

            $listaHashes[$valorHash] = $objetoHash;
        }
        $objetoHash = $listaHashes[$valorHash];
        $objetoHash->valor += $valorFinanceiro;
        $listaHashes[$valorHash] = $objetoHash;
    }

    /**
     * Metodo para salvar lancamentos do contacorrente
     * @param $lancamentos
     * @param LancamentoRepository $repository
     * @throws \Exception
     */
    public static function salvarLancamentos($lancamentos, LancamentoRepository $repository)
    {

        foreach ($lancamentos as $lancamento) {
            $repository->persistAtributoLancamentos($lancamento);
        }
    }

    /**
     * Reprocessa os lancamnentos das contas informadas
     * @param $contaCorrente
     * @param array|null $contas
     * @throws \BusinessException
     * @throws \DBException
     * @throws \NotFoundException
     * @throws \ParameterException
     */
    public function reprocessar($contaCorrente, array $contas = null)
    {


        $this->limparDadosContaCorrente($contaCorrente, $contas);
        $lancamentos = $this->getLancamentosParaProcessamento($contaCorrente, $contas);
        if (count($lancamentos) == 0) {
            $mensagem = "Para esse conta corrente e nesta competência já foram reprocessados todos dados.";
            $mensagem .= "Informe outro conta corrente e/ou outra competência.";
            throw new \NotFoundException($mensagem);
        }
        $this->seAtributosDoTipoContaCorrente(array($contaCorrente));
        $this->processar($lancamentos, $contas);
    }

    /**
     * Remove os dados da conta corrente
     * @param $contaCorrente
     * @param $contas
     * @throws \BusinessException
     * @throws \DBException
     */
    protected function limparDadosContaCorrente($contaCorrente, $contas = null)
    {

        $dadosLancamento = $this->getLancamentosParaExclusao($contaCorrente, $contas);
        $estruturais = $dadosLancamento->estruturais;
        if (count($estruturais) > 0) {
            $this->removerSaldoFinalNacompetenciaDaContaCorrente($contaCorrente, $estruturais);
        }
        if (count($dadosLancamento->lancamentos) > 0) {
            $this->removerLancamentosDaContaCorrente($contaCorrente, $dadosLancamento->lancamentos);
        }
    }

    /**
     * Remove todos os saldos da conta
     * @param $contaCorrente
     * @param $estruturais
     * @throws \DBException
     */
    protected function removerSaldoFinalNacompetenciaDaContaCorrente($contaCorrente, $estruturais)
    {
        $daoConplanoatributoSaldo = new \cl_conplanoatributosaldo();
        $where = array(
            "substr(c125_hashcontaatributos, 1, 15) in ('" . implode("', '", $estruturais) . "')",
            "c125_anousu = {$this->competencia->getAno()}",
            "c125_mesusu = {$this->competencia->getMes()}",
            "c125_conplanosistema = {$contaCorrente}",
            "c125_instit          = {$this->instituicao->getCodigo()}",
        );

        $daoConplanoatributoSaldo->excluir(null, implode(" and ", $where));
        if ($daoConplanoatributoSaldo->erro_status == 0) {
            throw new \DBException("Erro ao remover os dados da conta corrente");
        }
    }

    /**
     * remove todos os lancamentos e seus atributos
     *
     * @throws \Exception
     */
    protected function removerLancamentosDaContaCorrente($contaCorrente, array $lancamentos)
    {

        $ano = $this->competencia->getAno();
        $mes = $this->competencia->getMes();
        $where = " c123_conplanoatributolancamentos in(" . implode(", ", $lancamentos) . ")";
        if (!empty($contaCorrente)) {
            $where .= " and c123_conplanosistema = {$contaCorrente}";
        }

        $sqlInfo = " delete from infocomplementarvalor where {$where}";
        $rsInfo = db_query($sqlInfo);
        if (!$rsInfo) {
            $mensagem = "Erro ao excluir as informações complementares dos lançamentos da competência: {$mes}/{$ano}.";
            throw new \Exception($mensagem);
        }

        $sqlAtributos = "delete from conplanoatributolancamentos ";
        $sqlAtributos .= " where extract(month from c124_data) = {$mes} ";
        $sqlAtributos .= "   and extract(year from c124_data) = {$ano}";
        $sqlAtributos .= "   and  not exists (select 1 from infocomplementarvalor ";
        $sqlAtributos .= "                      where c123_conplanoatributolancamentos = c124_sequencial)";
        $sqlAtributos .= " and c124_conplanosistema = {$contaCorrente}";

        $rsAtributos = db_query($sqlAtributos);
        if (!$rsAtributos) {
            throw new \Exception("Erro ao excluir os atributos dos lançamentos da competência: {$mes}/{$ano}.");
        }
    }

    /**
     * retorna todos os movimentos que devem ser removidos
     * @param $contaCorrente
     * @param $contas
     * @return \stdClass
     * @throws \BusinessException
     */
    private function getLancamentosParaExclusao($contaCorrente, $contas)
    {
        $daoInfoComplamentarValor = new \cl_infocomplementarvalor();
        $where = array("c123_conplanosistema = {$contaCorrente}");
        $where[] = "extract(year from c124_data) = {$this->getCompetencia()->getAno()}";
        $where[] = "extract(month from c124_data) = {$this->getCompetencia()->getMes()}";
        $where[] = "c53_tipo <> 3000";
        $where[] = "c61_instit = " . $this->instituicao->getCodigo();
        if (!empty($contas)) {
            $where[] = "c123_reduzido in(" . implode(", ", $contas) . ")";
        }
        $campos = "distinct c124_sequencial as codigo_lancamento, c124_lancamento as lancamento_contabil, ";
        $campos .= "c60_estrut as estrutural ";
        $sql = $daoInfoComplamentarValor->sql_query_lancamento($campos, implode(" and ", $where));
        $rs = db_query($sql);
        if (!$rs) {
            throw new \BusinessException("Erro ao pesquisar lançamentos para remover.");
        }
        $lancamentos = new \stdClass;
        $lancamentos->lancamentos = array();
        $lancamentos->estruturais = array();
        \db_utils::makeCollectionFromRecord($rs, function ($dados) use (&$lancamentos) {

            $lancamentos->lancamentos[] = $dados->codigo_lancamento;
            if (!in_array($dados->estrutural, $lancamentos->estruturais)) {
                $lancamentos->estruturais[] = $dados->estrutural;
            }
        });
        return $lancamentos;
    }

    /**
     * @param $contaCorrente
     * @param array $contas
     *
     * @return array
     */
    private function getLancamentosParaProcessamento($contaCorrente, array $contas = null)
    {

        /**
         * @todo mover query para dao
         */
        $whereCredito = array("atrc.c120_conplanosistema = ({$contaCorrente})");
        $whereDebito = array("atrd.c120_conplanosistema = ({$contaCorrente})");
        if (!empty($contas)) {
            $listaContas = implode(",", $contas);
            $whereCredito[] = "c69_credito in ({$listaContas})";
            $whereDebito[] = "c69_debito in ({$listaContas})";
        }
        $sqlLancamentos = "select distinct c69_codlan ";
        $sqlLancamentos .= "from conlancamval ";
        $sqlLancamentos .= "       inner join conplanoreduz credito  on c69_credito = credito.c61_reduz ";
        $sqlLancamentos .= "                                        and credito.c61_anousu = c69_anousu ";
        $sqlLancamentos .= "       left join conplanoatributos atrc on atrc.c120_conplano = credito.c61_codcon ";
        $sqlLancamentos .= "                                        and atrc.c120_anousu = credito.c61_anousu ";
        $sqlLancamentos .= "       inner join conplanoreduz debito on c69_debito = debito.c61_reduz ";
        $sqlLancamentos .= "                                      and debito.c61_anousu = c69_anousu";
        $sqlLancamentos .= "       left join conplanoatributos atrd";
        $sqlLancamentos .= "         on atrd.c120_conplano = debito.c61_codcon ";
        $sqlLancamentos .= "               and atrd.c120_anousu = debito.c61_anousu";
        $sqlLancamentos .= "       inner join conlancamdoc   on c71_codlan = c69_codlan  ";
        $sqlLancamentos .= "       inner join conlancaminstit on c02_codlan = c69_codlan  ";
        $sqlLancamentos .= "       inner join conhistdoc   on c71_coddoc = c53_coddoc  ";
        $sqlLancamentos .= "where ( (" . implode(" and ", $whereCredito) . ") ";
        $sqlLancamentos .= "   or (" . implode(" and ", $whereDebito) . ")) ";
        $sqlLancamentos .= "  and  c69_anousu = {$this->getCompetencia()->getAno()} ";
        $sqlLancamentos .= "  and  c53_tipo <> 3000";
        $sqlLancamentos .= "  and c02_instit = " . $this->instituicao->getCodigo();
        $sqlLancamentos .= "  and  extract(month from c69_data) = {$this->getCompetencia()->getMes()} ";

        $rsLancamentos = db_query($sqlLancamentos);
        $lancamentos = \db_utils::makeCollectionFromRecord($rsLancamentos, function ($dados) {
            return $dados->c69_codlan;
        });
        return $lancamentos;
    }

    /**
     * Calcula o saldo inicial da conta e retorna o seu saldo.
     * O saldo inicial da conta é calculado atraves  do saldo da tabela conplanoexe.
     * seus atributos estão com o valor padrao dos atributos
     * @param $hash
     * @param $ano
     * @param $sistema
     * @return bool
     * @throws \Exception
     */
    private function calcularSaldoInicialDaConta($hash, $ano, $sistema)
    {
        $daoConplano = new \cl_conplanoexe();

        $partesHash = explode("|", $hash->hash);
        $estrutural = $partesHash[0];
        $campos = "(case when c62_vlrdeb > 0 then c62_vlrdeb else c62_vlrcre end ) as valor, ";
        $campos .= "(case when c62_vlrdeb > 0 then 'D' else 'C' end ) as natureza, ";
        $campos .= " c60_estrut as estrutural, c60_codcon as conta";
        $where = "c62_reduz = {$hash->reduzido} and c62_anousu  = {$ano} and (c62_vlrdeb > 0 or c62_vlrcre > 0)";
        $where .= " and not exists(select 1 from conplanoatributosaldo ";
        $where .= "              where c125_mesusu = 0 and substr(c125_hashcontaatributos, 1, 15) = '{$estrutural}')";
        $sqlSaldoInicial = $daoConplano->sql_query(null, null, $campos, null, $where);
        $rsSaldoInicial = db_query($sqlSaldoInicial);
        $totalLinhas = pg_num_rows($rsSaldoInicial);
        if ($totalLinhas == 0) {
            return false;
        }
        $saldoConta = \db_utils::fieldsMemory($rsSaldoInicial, 0);

        $lancamento = new LancamentoModel();
        $lancamento->setData(new \DBDate("{$ano}-01-01"));
        $lancamento->setNatureza($saldoConta->natureza);
        $lancamento->setSistema($sistema);
        $lancamento->setTipoLancamento(1);
        $lancamento->setValor($saldoConta->valor);

        $contaCorrente = ContaCorrente::getByCodigo($sistema);
        $atributos = $contaCorrente->getAtributos();
        foreach ($atributos as $atributo) {
            $informacaoComplementar = new InformacaoComplementar();
            $informacaoComplementar->setSigla($atributo->getSigla());
            $informacaoComplementar->setDescricao($atributo->getNome());
            $informacaoComplementar->setConta($saldoConta->conta);
            $informacaoComplementar->setAnousu($ano);
            $informacaoComplementar->setContaEstrutura($saldoConta->estrutural);
            $informacaoComplementar->setCodigoLancamento('null');
            $informacaoComplementar->setContaReduzida($hash->reduzido);
            $informacaoComplementar->setCodigoInstituicao($this->getInstituicao()->getCodigo());
            $informacaoComplementar->setCodigoInformacaoComplementar($atributo->getCodigo());
            $informacaoComplementar->setCodigoSistema($sistema);
            $informacaoComplementar->setValor("SI");
            $lancamento->addInfoComplementares($informacaoComplementar);
        }

        $repository = LancamentoRepository::getInstance();
        self::salvarLancamentos(array($lancamento), $repository);
        $listaHashes = array();
        $hashes = self::montarHashesDolancamento($lancamento);
        foreach ($hashes as $hash) {
            self::calcularValorMovimentacaoDoHash(
                $hash,
                $lancamento->getValor(),
                $lancamento->getNatureza(),
                $listaHashes,
                $lancamento->getSistema()
            );
        }
        foreach ($listaHashes as $codigoHash => $hash) {
            $insert = "insert into conplanoatributosaldo values (nextval('conplanoatributosaldo_c125_sequencial_seq'),";
            $insert .= " {$ano},";
            $insert .= " 0,";
            $insert .= " '{$hash->hash}',";
            $insert .= " {$saldoConta->valor},";
            $insert .= " '{$saldoConta->natureza}',";
            $insert .= " 3,";
            $insert .= " {$sistema},";
            $insert .= " {$this->instituicao->getCodigo()}, 3);";
            db_query($insert);
        }
    }

    /**
     * retorna o ultimo saldo
     * @param $hash
     * @return \_db_fields|bool|\stdClass
     * @throws \Exception
     */
    public static function getSaldoDohash($hash, $instituicao)
    {

        $sSql = <<<SQL
            select *
              from conplanoatributosaldo 
             where c125_hashcontaatributos = '{$hash->hash}' 
             and  c125_instit = '{$instituicao}' 
             order by  c125_anousu desc, 
                       c125_mesusu desc limit 1 for update;
SQL;

        $rsUltimoSaldo = db_query($sSql);
        if (!$rsUltimoSaldo) {
            throw new \Exception("Houve um problema ao buscar o saldo do conta corrente.");
        }

        $totalLinhas = pg_num_rows($rsUltimoSaldo);
        if ($totalLinhas == 0) {
            return false;
        }
        return \db_utils::fieldsMemory($rsUltimoSaldo, 0);
    }


    /**
     * @param integer $reduzido
     * @param float $valor
     * @param integer $codigoLancamento
     * @param \DBDate $data
     * @return bool
     * @throws \Exception
     * @throws \DBException
     * @throws \ParameterException
     */
    public static function atualizarSaldoPorContaLancamento($reduzido, $valor, $codigoLancamento, \DBDate $data)
    {


        /**
         * Busca os atributos envolvidos no lancamento contabil
         */
        $daoAtributoLancamento = new \cl_conplanoatributolancamentos();
        $buscaAtributos = $daoAtributoLancamento->sql_query_file(
            null,
            "*",
            null,
            "c124_lancamento = {$codigoLancamento}"
        );
        $buscaAtributos = db_query($buscaAtributos);
        if (!$buscaAtributos) {
            $mensagem = "Não foi possível consultar os valores dos atributos de conta corrente do lançamento.";
            throw new \Exception($mensagem);
        }

        if (pg_num_rows($buscaAtributos) == 0) {
            return false;
        }

        /**
         * busca os valores dos atributos executados no lancamento para criar o hash por conta (debito/credito)
         */
        $buscaValorAtributos = "
        select c124_lancamento as lancamento,
               c124_natureza as natureza,
               c124_valor as valor_montario,
               c124_data as data,
               c124_conplanosistema as codigo_conta_corrente,
               c123_reduzido as reduzido,
               c123_valor as valor_atributo,
               c121_sigla as sigla,
               c121_sequencial as codigo_atributo
          from contabilidade.conplanoatributolancamentos
               join contabilidade.infocomplementarvalor on c123_conplanoatributolancamentos = c124_sequencial
               join contabilidade.conplanoinfocomplementar on c121_sequencial = c123_infocomplementar
               join contabilidade.conplanosistema on c122_sequencial = c124_conplanosistema
               join contabilidade.conplanosistemaatributos on c129_conplanoinfocomplementar = c121_sequencial
                                            and c129_conplanosistema = c122_sequencial
         where c124_lancamento = {$codigoLancamento}
           and c123_reduzido = {$reduzido}
         order by c129_ordem
            ";
        $buscaValorAtributos = db_query($buscaValorAtributos);
        if (!$buscaValorAtributos) {
            $mensagem = "Ocorreu um erro ao consultar os atributos e seus valores para atualização de saldo.";
            throw new \DBException($mensagem);
        }

        $totalRegistros = pg_num_rows($buscaValorAtributos);
        if ($totalRegistros == 0) {
            return false;
        }

        /**
         * percorre os registros adicionando em um array
         */
        $atributos = array();
        $codigoContaCorrente = null;
        for ($rowAtributo = 0; $rowAtributo < $totalRegistros; $rowAtributo++) {
            $stdDados = \db_utils::fieldsMemory($buscaValorAtributos, $rowAtributo);
            $codigoContaCorrente = $stdDados->codigo_conta_corrente;
            $atributos[] = "{$stdDados->valor_atributo}#{$stdDados->sigla}";
        }
        $contaPlano = \ContaPlanoPCASPRepository::getContaPorReduzido($stdDados->reduzido, $data->getAno());
        /**
         * cria o hash de acordo com a classe de processamento.
         */
        $hashEncontrado = $contaPlano->getEstrutural() . "|" . implode('|', $atributos);

        $hash = (object)array('hash' => $hashEncontrado);
        $competencia = new \DBCompetencia($data->getAno(), $data->getMes());
        self::atualizaSaldoContaCorrente(
            $hash,
            $valor,
            $data->getAno(),
            $data->getMes(),
            $codigoContaCorrente,
            $competencia
        );
    }


    /**
     * Reprocessa saldo inicial das contas
     * @param integer $reduzido
     * @param integer $contaCorrente
     * @throws \Exception
     */
    public function reprocessarSaldoInicial($reduzido, $contaCorrente)
    {

        $buscaLancamentos = "
          select distinct 
                 array_to_string(array_accum(distinct c124_sequencial), ',') as codigos
            from conplanoatributolancamentos
                 inner join infocomplementarvalor on c124_sequencial = c123_conplanoatributolancamentos
                 inner join conplanoreduz on c123_reduzido = c61_reduz
                                         and extract(year from c124_data)::int = c61_anousu
                 inner join conplano      on c61_codcon = c60_codcon
                                         and c61_anousu = c60_anousu
           where c124_lancamento is null
             and c124_tipo = '1'
             and c124_conplanosistema = {$contaCorrente}
             and c123_reduzido = {$reduzido}";
        $buscaLancamentos = db_query($buscaLancamentos);
        if (!$buscaLancamentos) {
            throw new \DBException("Ocorreu um erro ao consultar os dados do lançamento.");
        }

        $codigosLancamentoAtributo = \db_utils::fieldsMemory($buscaLancamentos, 0)->codigos;
        if (empty($codigosLancamentoAtributo)) {
            $mensagem = "Não foram encontrados lançamentos para o reduzido {$reduzido} no conta corrente selecionado.";
            throw new \BusinessException($mensagem);
        }

        $deleteComplementarValor = db_query("delete 
                                               from infocomplementarvalor 
                                            where c123_conplanoatributolancamentos in ({$codigosLancamentoAtributo})");
        if (!$deleteComplementarValor) {
            throw new \DBException("Erro ao excluir de infocomplementarvalor. " . pg_last_error());
        }

        $deleteAtributoLancamento = db_query("delete 
                         from conplanoatributolancamentos where c124_sequencial in ({$codigosLancamentoAtributo})");
        if (!$deleteAtributoLancamento) {
            throw new \DBException("Erro ao excluir de conplanoatributolancamentos. " . pg_last_error());
        }

        $contaPlanoPcasp = \ContaPlanoPCASPRepository::getContaPorReduzido(
            $reduzido,
            $this->getCompetencia()->getAno()
        );
        $where = implode(' and ', array(
            "c125_mesusu = 0",
            "c125_hashcontaatributos ilike '{$contaPlanoPcasp->getEstrutural()}|%' ",
            "c125_conplanosistema = {$contaCorrente}",
            "c125_instit = {$this->getInstituicao()->getCodigo()}"
        ));
        $deleteAtributoSaldo = db_query("delete from conplanoatributosaldo where {$where}");
        if (!$deleteAtributoSaldo) {
            throw new \DBException("Erro ao excluir de conplanoatributosaldo. " . pg_last_error());
        }

        $hashCriado = (object)array(
            'hash' => $contaPlanoPcasp->getEstrutural(),
            'reduzido' => $reduzido
        );

        $this->calcularSaldoInicialDaConta($hashCriado, $this->getCompetencia()->getAno(), $contaCorrente);
    }
}
