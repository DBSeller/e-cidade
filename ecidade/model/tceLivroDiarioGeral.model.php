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


require_once(modification('model/tceEstruturaBasica.php'));

class tceLivroDiarioGeral extends tceEstruturaBasica
{
    const  NOME_ARQUIVO = 'TCE_4111.TXT';
    const  CODIGO_ARQUIVO = 33;
    /**
     * @var string
     */
    private $dataInicio;
    /**
     * @var string
     */
    private $dataFim;
    /**
     * @var string
     */
    private $codigosInstituicoes;
    /**
     * @var stdClass
     */
    private $cabecalho;
    /**
     * @var array
     */
    private $numerosLotesGerados;

    private $codigoCampo = null;

    /**
     * Método construtor
     * @param string $dataInicio
     * @param string $dataFim
     * @param string $codigosInstituicoes
     * @param stdClass $cabecalho
     */
    public function __construct($dataInicio, $dataFim, $codigosInstituicoes, $cabecalho)
    {

        $daoLayoutCampos = new \cl_db_layoutcampos();

        if (db_getsession("DB_anousu") < 2020) {
            $where = [
                "db51_layouttxt = " . self::CODIGO_ARQUIVO,
                "db52_nome = 'complemento_recurso'"
            ];
            $sqlDadosCampo = $daoLayoutCampos->sql_query(
                null,
                "db_layoutcampos.db52_codigo, db52_layoutlinha",
                null,
                implode(" and ", $where)
            );
            $rsCodigoLayout = db_query($sqlDadosCampo);
            $totalLinhas = pg_num_rows($rsCodigoLayout);
            if ($totalLinhas == 0) {
                throw new \Exception("Não foi encontrado campo 'complemento do recurso no layout.");
            }
            $this->codigoCampo = db_utils::fieldsMemory($rsCodigoLayout, 0)->db52_codigo;
            $daoLayoutCampos->db52_imprimir = 'false';
            $daoLayoutCampos->db52_codigo = $this->codigoCampo;
            $daoLayoutCampos->alterar($this->codigoCampo);

        }
        try {
            parent::__construct(self::CODIGO_ARQUIVO, self::NOME_ARQUIVO);
        } catch (Exception $e) {
            throw $e;
        }

        $this->dataInicio = $dataInicio;
        $this->dataFim = $dataFim;
        $this->codigosInstituicoes = $codigosInstituicoes;
        $this->cabecalho = $cabecalho;
        $this->numerosLotesGerados = array();
    }

    /**
     * Retorna o nome do arquivo
     * @return string
     */
    public function getNomeArquivo()
    {
        return self::NOME_ARQUIVO;
    }

    /**
     * Busca e escreve os lançamentos no arquivo
     */
    public function geraArquivo()
    {
        $this->oTxtLayout->setByLineOfDBUtils($this->cabecalho, 1);
        $sWhere = "where lan.c69_data between '{$this->dataInicio}' and '{$this->dataFim}' ";
        $sSqlDados = $this->sqlDiarioGeral($sWhere);
        $rsLancamentos = db_query($sSqlDados);
        $iNumRows = pg_num_rows($rsLancamentos);
        $iTotalRegistros = 0;

        /**
         * removemos a coluna quando o ano for menor de 2020.
         */
        for ($i = 0; $i < $iNumRows; $i++) {

            $oLancamento = db_utils::fieldsMemory($rsLancamentos, $i);

            $oLancamento->codigorecursovinculado = str_pad(
                $oLancamento->codigorecursovinculado,
                4,
                0,
                STR_PAD_LEFT
            );

            if (db_getsession("DB_anousu") >= 2020) {
                $oLancamento->complemento_recurso = str_pad($oLancamento->complemento_recurso, 4, 0, STR_PAD_LEFT);
            }
            $this->validaLote($oLancamento->numerolote);
            $this->oTxtLayout->setByLineOfDBUtils($oLancamento, 3);
            $iTotalRegistros++;
        }
        /**
         * Voltamos a coluna para o linha
         *
         */
        if (db_getsession("DB_anousu") < 2020 && !empty($this->codigoCampo)) {
            $daoLayoutCampos = new \cl_db_layoutcampos();
            $daoLayoutCampos->db52_imprimir = 'true';
            $daoLayoutCampos->db52_codigo = $this->codigoCampo;
            $daoLayoutCampos->alterar($this->codigoCampo);
        }
        $this->oTxtLayout->setByLineOfDBUtils($this->rodapePadrao($iTotalRegistros), 5);
        unset($rsLancamentos);
    }

    /**
     * Monta a query para buscar os lançamentos
     * @param string $sWhere
     */
    private function sqlDiarioGeral($sWhere)
    {
        $iAnoUsu = db_getsession('DB_anousu');
        /**
         * Sub-select que descobre a origem da arrecadação da receita: planilha de receita ou arrecadação
         */
        $sSqlDadosTesouraria = " select lpad(case when ";
        $sSqlDadosTesouraria .= "             k82_seqpla::varchar is null ";
        $sSqlDadosTesouraria .= "             then k12_numpre::varchar ";
        $sSqlDadosTesouraria .= "        end, 10, '0')";
        $sSqlDadosTesouraria .= "   from conlancambol";
        $sSqlDadosTesouraria .= "        inner join conlancamdoc  on c71_codlan = c77_codlan";
        $sSqlDadosTesouraria .= "        inner join corrente      on corrente.k12_id = c77_id";
        $sSqlDadosTesouraria .= "                                and corrente.k12_autent = c77_autent";
        $sSqlDadosTesouraria .= "                                and c77_databol = corrente.k12_data";
        $sSqlDadosTesouraria .= "        left  join corlanc       on corrente.k12_id = corlanc.k12_id";
        $sSqlDadosTesouraria .= "                                and corrente.k12_autent = corlanc.k12_autent";
        $sSqlDadosTesouraria .= "                                and corrente.k12_data= corlanc.k12_data";
        $sSqlDadosTesouraria .= "        left  join cornump       on cornump.k12_id = corrente.k12_id";
        $sSqlDadosTesouraria .= "                                and cornump.k12_autent = corrente.k12_autent";
        $sSqlDadosTesouraria .= "                                and cornump.k12_data = corrente.k12_data";
        $sSqlDadosTesouraria .= "        left  join corplacaixa   on corplacaixa.k82_id = corrente.k12_id";
        $sSqlDadosTesouraria .= "                                and corplacaixa.k82_data = corrente.k12_data";
        $sSqlDadosTesouraria .= "                                and corplacaixa.k82_autent = corrente.k12_autent";
        $sSqlDadosTesouraria .= "  where c77_codlan = c69_codlan limit 1  ";

        $sSqlLancamentos = "select c69_sequen   as sequencial_lancamento, ";
        $sSqlLancamentos .= "       c60_estrut   as codigocontabalanceteverificasg, ";
        $sSqlLancamentos .= "       codtrib      as codigoorgunidorcavinccodsg, ";
        $sSqlLancamentos .= "       c78_chave    as numerolote, ";
        $sSqlLancamentos .= "       c61_codigo as codigorecursovinculado, ";
        $sSqlLancamentos .= "       complemento as complemento_recurso, ";
        $sSqlLancamentos .= "       c60_identificadorfinanceiro as indicadorsuperavitfinanceiro, ";
        $sSqlLancamentos .= "       (SELECT c65_sigla FROM consistemaconta WHERE c65_sequencial = c60_consistemaconta) AS naturezainformacao, ";
        $sSqlLancamentos .= "       case  ";
        $sSqlLancamentos .= "         when c53_tipo is null   ";
        $sSqlLancamentos .= "           then null ";
        $sSqlLancamentos .= "         when c53_tipo in(10, 11) ";
        $sSqlLancamentos .= "           then (select empempenho.e60_anousu || lpad(empempenho.e60_instit, 2, '0') || '0' || lpad(e60_codemp, 6, '0')::varchar ";
        $sSqlLancamentos .= "                   from conlancamemp ";
        $sSqlLancamentos .= "                        inner join empempenho on empempenho.e60_numemp = conlancamemp.c75_numemp";
        $sSqlLancamentos .= "                  where c75_codlan = c69_codlan limit 1)";
        $sSqlLancamentos .= "         when c53_tipo in(20, 21) ";
        $sSqlLancamentos .= "           then (select lpad(c66_codnota::varchar, 10,'0')::varchar ";
        $sSqlLancamentos .= "                   from conlancamnota ";
        $sSqlLancamentos .= "                  where c66_codlan = c69_codlan limit 1) ";
        $sSqlLancamentos .= "         when c53_tipo in(30, 31) ";
        $sSqlLancamentos .= "           then (select lpad(c80_codord::varchar, 10,'0')::varchar ";
        $sSqlLancamentos .= "                   from conlancamord ";
        $sSqlLancamentos .= "                  where c80_codlan = c69_codlan limit 1) ";
        $sSqlLancamentos .= "           when c53_tipo in (40,41,50,51,60,61,70,71) ";
        $sSqlLancamentos .= "           then (select lpad(o46_codlei::varchar, 10, '0')::varchar ";
        $sSqlLancamentos .= "                   from conlancamsup ";
        $sSqlLancamentos .= "                        inner join orcsuplem on orcsuplem.o46_codsup = conlancamsup.c79_codsup limit 1)";
        $sSqlLancamentos .= "         when c53_tipo in(100, 101)";
        $sSqlLancamentos .= "           then ({$sSqlDadosTesouraria}) ";
        $sSqlLancamentos .= "        end          as numerodocumento, ";
        $sSqlLancamentos .= "       c69_codlan   as numerolancamento, ";
        $sSqlLancamentos .= "       c69_data     as datalancamento, ";
        $sSqlLancamentos .= "       c69_valor    as valor, ";
        $sSqlLancamentos .= "       tipo         as tipolancamento, ";
        $sSqlLancamentos .= "       ''           as numeroarquivamento, ";
        $sSqlLancamentos .= "       ''           as reservadofuturo, ";
        $sSqlLancamentos .= "       substr(replace(replace(c72_complem,'\\n',''), '\\r', ''), 1, 150) as historico, ";
        $sSqlLancamentos .= "       (case when c53_tipo in(10, 11) then 1";
        $sSqlLancamentos .= "             when c53_tipo in (20,21) then 3 ";
        $sSqlLancamentos .= "             when c53_tipo in (30,11) then 2 ";
        $sSqlLancamentos .= "             when c53_tipo is null  then 0 ";
        $sSqlLancamentos .= "             else 9 end ) as tipodocumento ";
        $sSqlLancamentos .= "  from (select c69_sequen, ";
        $sSqlLancamentos .= "               c69_codlan, ";
        $sSqlLancamentos .= "               c69_data, ";
        $sSqlLancamentos .= "               c69_valor, ";
        $sSqlLancamentos .= "               c69_credito as reduz, ";
        $sSqlLancamentos .= "               'C' as tipo, ";
        $sSqlLancamentos .= "               codtrib, ";
        $sSqlLancamentos .= "               substr(coalesce(c78_chave,'NAOINFORMADO'),1,12) as c78_chave, ";
        $sSqlLancamentos .= "               conhistdoc.c53_coddoc, ";
        $sSqlLancamentos .= "               substr(coalesce(c72_complem, o39_descr),1,150) as c72_complem, ";
        $sSqlLancamentos .= "               pcr.c60_estrut, ";
        $sSqlLancamentos .= "               pcr.c60_identificadorfinanceiro, ";
        $sSqlLancamentos .= "               pcr.c60_consistemaconta, ";
        $sSqlLancamentos .= "               cre.c61_codigo, ";
        $sSqlLancamentos .= "               case
                                               when o200_sequencial is not null and o200_tribunal is true
                                                   then o200_sequencial
                                               else 0
                                            end as complemento, ";
        $sSqlLancamentos .= "               c53_tipo ";
        $sSqlLancamentos .= "          from conlancamval lan ";
        $sSqlLancamentos .= "               inner join conplanoreduz cre  on cre.c61_anousu            = c69_anousu ";
        $sSqlLancamentos .= "                                            and cre.c61_anousu            = {$iAnoUsu} ";
        $sSqlLancamentos .= "                                            and cre.c61_instit            in ({$this->codigosInstituicoes}) ";
        $sSqlLancamentos .= "                                            and cre.c61_reduz             = c69_credito ";
        $sSqlLancamentos .= "               inner join conplano pcr       on pcr.c60_anousu            = cre.c61_anousu ";
        $sSqlLancamentos .= "                                            and pcr.c60_codcon            = cre.c61_codcon ";
        $sSqlLancamentos .= "               inner join db_config          on db_config.codigo          = cre.c61_instit ";
        $sSqlLancamentos .= "                left join conlancamcomplementorecurso  on o201_codlan   = lan.c69_codlan ";
        $sSqlLancamentos .= "                left join complementofonterecurso on o200_sequencial = o201_complemento ";
        $sSqlLancamentos .= "                left join conlancamdig       on conlancamdig.c78_codlan   = lan.c69_codlan ";
        $sSqlLancamentos .= "                left join conlancamdoc       on conlancamdoc.c71_codlan   = lan.c69_codlan ";
        $sSqlLancamentos .= "                left join conhistdoc         on conhistdoc.c53_coddoc     = conlancamdoc.c71_coddoc ";
        $sSqlLancamentos .= "                left join conlancamcompl     on conlancamcompl.c72_codlan = lan.c69_codlan ";
        $sSqlLancamentos .= "                left join conlancamsup       on conlancamsup.c79_codlan   = lan.c69_codlan ";
        $sSqlLancamentos .= "                left join orcsuplem          on orcsuplem.o46_codsup      = conlancamsup.c79_codsup  ";
        $sSqlLancamentos .= "                left join orcprojeto          on orcprojeto.o39_codproj   = orcsuplem.o46_codlei  ";
        $sSqlLancamentos .= "     {$sWhere} ";
        $sSqlLancamentos .= "        union all ";
        $sSqlLancamentos .= "        select c69_sequen, ";
        $sSqlLancamentos .= "               c69_codlan, ";
        $sSqlLancamentos .= "               c69_data, ";
        $sSqlLancamentos .= "               c69_valor, ";
        $sSqlLancamentos .= "               c69_debito as reduz, ";
        $sSqlLancamentos .= "               'D' as tipo, ";
        $sSqlLancamentos .= "               codtrib, ";
        $sSqlLancamentos .= "               substr(coalesce(c78_chave,'NAOINFORMADO'),1,12) as c78_chave, ";
        $sSqlLancamentos .= "               conhistdoc.c53_coddoc, ";
        $sSqlLancamentos .= "               substr(coalesce(c72_complem, o39_descr),1,150) as c72_complem, ";
        $sSqlLancamentos .= "               pdb.c60_estrut, ";
        $sSqlLancamentos .= "               pdb.c60_identificadorfinanceiro, ";
        $sSqlLancamentos .= "               pdb.c60_consistemaconta, ";
        $sSqlLancamentos .= "               deb.c61_codigo, ";
        $sSqlLancamentos .= "               case
                                               when o200_sequencial is not null and o200_tribunal is true
                                                   then o200_sequencial
                                               else 0
                                            end as complemento, ";
        $sSqlLancamentos .= "               c53_tipo ";
        $sSqlLancamentos .= "          from conlancamval lan ";
        $sSqlLancamentos .= "               inner join conplanoreduz deb  on deb.c61_anousu            = c69_anousu ";
        $sSqlLancamentos .= "                                            and deb.c61_anousu            = {$iAnoUsu} ";
        $sSqlLancamentos .= "                                            and deb.c61_instit            in ({$this->codigosInstituicoes}) ";
        $sSqlLancamentos .= "                                            and deb.c61_reduz             = c69_debito ";
        $sSqlLancamentos .= "               inner join conplano pdb       on pdb.c60_anousu            = deb.c61_anousu ";
        $sSqlLancamentos .= "                                            and pdb.c60_codcon            = deb.c61_codcon ";
        $sSqlLancamentos .= "               inner join db_config          on db_config.codigo          = deb.c61_instit ";
        $sSqlLancamentos .= "                left join conlancamdig       on conlancamdig.c78_codlan   = lan.c69_codlan ";
        $sSqlLancamentos .= "                left join conlancamcomplementorecurso  on o201_codlan   = lan.c69_codlan ";
        $sSqlLancamentos .= "                left join complementofonterecurso on o200_sequencial = o201_complemento  ";
        $sSqlLancamentos .= "                left join conlancamdoc       on conlancamdoc.c71_codlan   = lan.c69_codlan ";
        $sSqlLancamentos .= "                left join conhistdoc         on conhistdoc.c53_coddoc     = conlancamdoc.c71_coddoc ";
        $sSqlLancamentos .= "                left join conlancamcompl     on conlancamcompl.c72_codlan = lan.c69_codlan ";
        $sSqlLancamentos .= "                left join conlancamsup       on conlancamsup.c79_codlan   = lan.c69_codlan ";
        $sSqlLancamentos .= "                left join orcsuplem          on orcsuplem.o46_codsup      = conlancamsup.c79_codsup  ";
        $sSqlLancamentos .= "                left join orcprojeto          on orcprojeto.o39_codproj   = orcsuplem.o46_codlei  ";
        $sSqlLancamentos .= "     {$sWhere} ";
        $sSqlLancamentos .= "     ) as x ";
        $sSqlLancamentos .= " order by c69_sequen ";

        return $sSqlLancamentos;
    }

    /**
     * Valida se o número do lote é um inteiro, caso não seja, é gerado um novo valor para o mesmo
     * @param mixed &$numeroLote
     */
    private function validaLote(&$numeroLote)
    {
        if (!preg_match('/^\d+$/', $numeroLote)) {
            if (!array_key_exists($numeroLote, $this->numerosLotesGerados)) {
                $this->numerosLotesGerados[$numeroLote] = $this->gerarNumeroLote();
            }
            $numeroLote = $this->numerosLotesGerados[$numeroLote];
        }
    }

    /**
     * Gera um número inteiro contendo 12 dígitos
     * @return int
     */
    private function gerarNumeroLote()
    {
        $novoNumero = mt_rand(100000000000, 999999999999);
        if (in_array($novoNumero, $this->numerosLotesGerados)) {
            $this->gerarNumeroLote();
        }
        return $novoNumero;
    }
}
