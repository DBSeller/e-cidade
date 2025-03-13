<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009 DBSeller Servicos de Informatica
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

require_once modification("interfaces/iPadArquivoTxtBase.interface.php");
require_once modification("model/contabilidade/arquivos/sigfis/SigfisArquivoBase.model.php");

/**
 *
 * Classe Responsável pela geração dos dados necessários para o arquivo Movimento Contabil
 * @author Andrio Costa
 * @package contabilidade
 * @subpackage sigfis
 *
 */
class SigfisArquivoMovimentoContabil extends SigfisArquivoBase implements iPadArquivoTXTBase
{
    protected $iCodigoLayout = 205;
    protected $sNomeArquivo = 'MovConta';
    protected $aMovimentoContabil = array();

    /**
     * Busca os dados para gerar o Arquivo do Movimento Contabil
     * @throws Exception
     */
    public function gerarDados()
    {
        /**
         * Busca os dados da db_config
         */
        $iInstituicaoSessao = db_getsession('DB_instit');
        $iAnoSessao = db_getsession('DB_anousu');
        $this->setCodigoLayout(205);

        if ($iAnoSessao < 2013) {
            $this->setCodigoLayout(124);
        }

        $clConLanCam = db_utils::getDao('conlancam');
        $sSqlConLanCam = $this->sql_movimentoContabilSigfis_new($this->iAnoUso, $iInstituicaoSessao, $this->dtDataInicial, $this->dtDataFinal);
        $rsConLanCam = $clConLanCam->sql_record($sSqlConLanCam);

        $this->addLog("=====Arquivo" . $this->getNomeArquivo() . " Erros:\n");
        if ($clConLanCam->numrows > 0) {
            if (empty($this->sCodigoTribunal)) {
                throw new Exception("O código do tribunal deve ser informado para geração do arquivo");
            }

            for ($i = 0; $i < $clConLanCam->numrows; $i++) {
                $oDadosQuery = db_utils::fieldsMemory($rsConLanCam, $i);

                /**
                 * Verifica se a Conta retornada possui vinculo com a conta do Sigfis
                 */
                if (true) {
                    $sIndice = $oDadosQuery->estrutural . $oDadosQuery->competencia . $oDadosQuery->tipo_movimento . $oDadosQuery->reduzido;

                    /**
                     * Se a conta existir, temos que agrupar os valores de crédito e débito
                     * Para isso foi criado um indice único para cada conta concatenando:
                     * Conta do TCE + Ano e mes (competencia) + tipo do movimento + Reduzido
                     */

                    $sContaCorrente  = "select (case when $iAnoSessao < 2016 then c56_sequencial else c56_contabancaria end) as seq_conta_corrente ";
                    $sContaCorrente .= "  from contabilidade.conplano ";
                    $sContaCorrente .= "       left join conplanocontabancaria  on conplano.c60_codcon = conplanocontabancaria.c56_codcon ";
                    $sContaCorrente .= "                                       and conplano.c60_anousu = conplanocontabancaria.c56_anousu ";
                    $sContaCorrente .= "       left join configuracoes.contabancaria on db83_sequencial = c56_contabancaria ";
                    $sContaCorrente .= " where c60_anousu = $this->iAnoUso ";
                    $sContaCorrente .= "   and c60_codcon = $oDadosQuery->conta ";
                    $sContaCorrente .= "   and c56_reduz = $oDadosQuery->reduzido";

                    $rsContaCorrente = db_query($sContaCorrente);
                    $oDadosContaCorrente = db_utils::fieldsMemory($rsContaCorrente, 0);

                    if ($oDadosQuery->tipo_movimento == "") {
                        $oDadosQuery->tipo_movimento = 3;
                    }

                    if ($oDadosQuery->competencia == "") {
                        $oDadosQuery->competencia = substr($this->dtDataInicial, 0, 4) . substr($this->dtDataInicial, 5, 2);
                    }

                    $oConta = new stdClass();
                    $oConta->conta = $oDadosQuery->estrutural;
                    $oConta->competencia = $oDadosQuery->competencia;
                    $oConta->tipo_movimento = $oDadosQuery->tipo_movimento;
                    $oConta->valor_credito = $oDadosQuery->valor_credito;
                    $oConta->valor_debito = $oDadosQuery->valor_debito;
                    $oConta->seq_conta_corrente = $oDadosContaCorrente->seq_conta_corrente;

                    if ($oDadosQuery->valor_credito > 0 or $oDadosQuery->valor_debito > 0) {
                        $aMovimentoContabil[$sIndice] = $oConta;
                    }

                    if (substr($oDadosQuery->competencia, -2) == "01") {
                        if ($oDadosQuery->c62_vlrcre > 0 or $oDadosQuery->c62_vlrdeb > 0) {
                            // saldo abertura
                            $oConta = new stdClass();
                            $oDadosQuery->tipo_movimento = 1;

                            $oConta->conta = $oDadosQuery->estrutural;
                            $oConta->competencia = $oDadosQuery->competencia;
                            $oConta->tipo_movimento = $oDadosQuery->tipo_movimento;
                            $oConta->valor_credito = $oDadosQuery->c62_vlrcre;
                            $oConta->valor_debito = $oDadosQuery->c62_vlrdeb;
                            $oConta->seq_conta_corrente = $oDadosContaCorrente->seq_conta_corrente;

                            $sIndice = $oDadosQuery->estrutural . $oDadosQuery->competencia . $oDadosQuery->tipo_movimento;

                            $aMovimentoContabil[$sIndice] = $oConta;
                        }
                    }
                } else {
                    $sErroLog = "Estrutural {$oDadosQuery->estrutural} - Conta{$oDadosQuery->conta} -> ";
                    $sErroLog .= "sem Vinculo com plano do SIGFIS - Conta *NÃO* Adicionada ao Arquivo.\n";
                    $this->addLog($sErroLog);
                }
            }
        } else {
            throw new Exception("Nenhum registro retornado para o ano {$this->iAnoUso}");
        }

        $this->addLog("===== Fim do Arquivo: " . $this->getNomeArquivo() . "\n");

        foreach ($aMovimentoContabil as $oMovimento) {
            $oDados = new stdClass();
            $oDados->dt_AnoCriacao = $this->iAnoUso;
            $oDados->cd_Unidade = str_pad($this->sCodigoTribunal, 4, ' ', STR_PAD_LEFT);
            $oDados->cd_ContaContabil = str_pad(substr($oMovimento->conta, 0, 34), 34, ' ', STR_PAD_RIGHT);
            $oDados->tp_MovContabil = $oMovimento->tipo_movimento;
            $oDados->dt_AnoMes = $oMovimento->competencia;
            $oDados->vl_Debito = str_pad(number_format($oMovimento->valor_debito, 2, '', ''), 16, '0', STR_PAD_LEFT);
            $oDados->vl_Credito = str_pad(number_format($oMovimento->valor_credito, 2, '', ''), 16, '0', STR_PAD_LEFT);
            $oDados->Cd_Conta_Corrente = str_pad($oMovimento->seq_conta_corrente, 30, ' ', STR_PAD_RIGHT);

            if ($iAnoSessao < 2013) {
                $oDados->codigolinha = 411;
            } else {
                $oDados->Cd_ContaCorrente = str_pad(str_repeat(' ', 30), 30, ' ', STR_PAD_LEFT);
                $oDados->codigolinha = 670;
            }

            $this->aDados[] = $oDados;
        }
    }

    public function sql_movimentoContabilSigfis_new($iAnoUsu, $iInstit, $dtDataInicial, $dtDataFinal)
    {
        $sSql  = "select competencia,           ";
        $sSql .= "       sum(CASE WHEN tipo = 'C' THEN case when tipo <> tipo_abertura and tipo_abertura <> '' and tipo_movimento = 1 then 0 else valor end ELSE 0 END) AS valor_credito, ";
        $sSql .= "       sum(CASE WHEN tipo = 'D' THEN case when tipo <> tipo_abertura and tipo_abertura <> '' and tipo_movimento = 1 then 0 else valor end ELSE 0 END) AS valor_debito, ";
        $sSql .= "       conta, ";
        $sSql .= "       reduzido, ";
        $sSql .= "       tipo_movimento, ";
        $sSql .= "       estrutural, ";
        $sSql .= "       tipo_abertura ";
        $sSql .= "  from (SELECT to_char(c70_data,'YYYYmm') as competencia, ";
        $sSql .= "               (case c53_tipo when 1000 then 2 ";
        $sSql .= "                              when 2000 then 1 ";
        $sSql .= "               else 3 end ) as tipo_movimento, ";
        $sSql .= "               planocredito.c60_codcon as conta, ";
        $sSql .= "               sum(c69_valor) as valor, ";
        $sSql .= "               'C' as tipo, ";
        $sSql .= "               planocredito.c60_estrut as estrutural, ";
        $sSql .= "               reduzcredito.c61_reduz as reduzido, ";
        $sSql .= "               reduzcredito.c61_anousu, ";
        $sSql .= "               (select CASE WHEN c62_vlrcre <> 0 and c62_vlrdeb = 0 THEN 'C' WHEN c62_vlrcre = 0 and c62_vlrdeb <> 0 THEN 'D' ELSE '' END as teste ";
        $sSql .= "                  from conplanoexe ";
        $sSql .= "                 where conplanoexe.c62_reduz = reduzcredito.c61_reduz ";
        $sSql .= "                   and conplanoexe.c62_anousu = reduzcredito.c61_anousu) AS tipo_abertura ";
        $sSql .= "          from conlancamval ";
        $sSql .= "               inner join conlancam                  on c69_codlan = c70_codlan ";
        $sSql .= "               inner join conlancamdoc               on c71_codlan = c70_codlan ";
        $sSql .= "               inner join conhistdoc                 on c71_coddoc = c53_coddoc ";
        $sSql .= "               inner join conplanoreduz reduzcredito on reduzcredito.c61_reduz  = c69_credito ";
        $sSql .= "                                                    and reduzcredito.c61_anousu = c69_anousu ";
        $sSql .= "                                                    and reduzcredito.c61_instit = {$iInstit} ";
        $sSql .= "               inner join conplano planocredito      on planocredito.c60_codcon = reduzcredito.c61_codcon ";
        $sSql .= "                                                    and planocredito.c60_anousu = reduzcredito.c61_anousu ";
        $sSql .= "         where c70_data between cast('{$dtDataInicial}' as date) and  cast('{$dtDataFinal}' as date) ";
        $sSql .= "           and c70_anousu = {$this->iAnoUso}  GROUP BY 3,1,2,5,6,7,8 ";
        $sSql .= "  union ";
        $sSql .= "        SELECT to_char(c70_data,'YYYYmm') as competencia, ";
        $sSql .= "               (case c53_tipo when 1000 then 2 ";
        $sSql .= "                              when 2000 then 1 ";
        $sSql .= "               else 3 end ) as tipo_movimento, ";
        $sSql .= "               planodebito.c60_codcon as conta, ";
        $sSql .= "               sum(c69_valor) as valor, ";
        $sSql .= "               'D' as tipo, ";
        $sSql .= "               planodebito.c60_estrut as estrutural, ";
        $sSql .= "               reduzdebito.c61_reduz as reduzido, ";
        $sSql .= "               reduzdebito.c61_anousu, ";
        $sSql .= "               (select CASE WHEN c62_vlrcre <> 0 and c62_vlrdeb = 0 THEN 'C' WHEN c62_vlrcre = 0 and c62_vlrdeb <> 0 THEN 'D' ELSE '' END as teste ";
        $sSql .= "                  from conplanoexe ";
        $sSql .= "                 where conplanoexe.c62_reduz = reduzdebito.c61_reduz ";
        $sSql .= "                   and conplanoexe.c62_anousu = reduzdebito.c61_anousu) AS tipo_abertura ";
        $sSql .= "          from conlancamval ";
        $sSql .= "               inner join conlancam                  on c69_codlan = c70_codlan ";
        $sSql .= "               inner join conlancamdoc               on c71_codlan = c70_codlan ";
        $sSql .= "               inner join conhistdoc                 on c71_coddoc = c53_coddoc ";
        $sSql .= "               inner join conplanoreduz reduzdebito  on reduzdebito.c61_reduz   = c69_debito ";
        $sSql .= "                                                    and reduzdebito.c61_anousu  = c69_anousu ";
        $sSql .= "                                                    and reduzdebito.c61_instit  = {$iInstit} ";
        $sSql .= "               inner join conplano planodebito       on planodebito.c60_codcon  = reduzdebito.c61_codcon ";
        $sSql .= "                                                    and planodebito.c60_anousu  = reduzdebito.c61_anousu ";
        $sSql .= "        where c70_data between cast('{$dtDataInicial}' as date) and  cast('{$dtDataFinal}' as date) ";
        $sSql .= "          and c70_anousu = {$this->iAnoUso} GROUP BY 1,2,3,5,6,7,8 ";
        $sSql .= "  UNION ";
        $sSql .= "        SELECT Cast(c62_anousu AS TEXT) || '01' AS competencia, ";
        $sSql .= "               1 AS tipo_movimento, ";
        $sSql .= "               c61_codcon AS conta, ";
        $sSql .= "               (CASE ";
        $sSql .= "                     WHEN c62_vlrcre <> 0 THEN c62_vlrcre ";
        $sSql .= "                     ELSE c62_vlrdeb ";
        $sSql .= "                 END) AS c69_valor, ";
        $sSql .= "               (CASE ";
        $sSql .= "                     WHEN c62_vlrcre <> 0 THEN 'C' ";
        $sSql .= "                     ELSE 'D' ";
        $sSql .= "                 END) AS tipo, ";
        $sSql .= "               c60_estrut AS estrutural, ";
        $sSql .= "               conplanoreduz.c61_reduz as reduzido, ";
        $sSql .= "               c61_anousu, ";
        $sSql .= "               (CASE WHEN c62_vlrcre <> 0 and c62_vlrdeb = 0 THEN 'C' WHEN c62_vlrcre = 0 and c62_vlrdeb <> 0 THEN 'D' ELSE '' END) AS tipo_abertura ";
        $sSql .= "          FROM contabilidade.conplanoexe, ";
        $sSql .= "               contabilidade.conplanoreduz, ";
        $sSql .= "               contabilidade.conplano ";
        $sSql .= "          WHERE c62_anousu = {$this->iAnoUso} ";
        $sSql .= "            AND c61_instit = {$iInstit} ";
        $sSql .= "            AND c62_anousu = c61_anousu ";
        $sSql .= "            AND c62_reduz = c61_reduz ";
        $sSql .= "            AND c61_anousu = c60_anousu ";
        $sSql .= "            AND c61_codcon = c60_codcon ";
        $sSql .= "            AND c62_vlrcre + c62_vlrdeb <> 0 ";
        $sSql .= "            AND To_char(Cast('{$dtDataFinal}' AS DATE),'mm') = '01' ) lanc ";
        $sSql .= "       group by conta, ";
        $sSql .= "                competencia, ";
        $sSql .= "                tipo_movimento, ";
        $sSql .= "                reduzido, ";
        $sSql .= "                estrutural, ";
        $sSql .= "                tipo_abertura ";
        $sSql .= "       order by estrutural";

        return $sSql;
    }
}
