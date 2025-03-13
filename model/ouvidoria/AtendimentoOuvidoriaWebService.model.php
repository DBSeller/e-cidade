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

require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");
require_once(modification("libs/JSON.php"));


class AtendimentoOuvidoriaWebService
{

    /**
     * Json com os dados para salvar atendimento
     * @var integer
     */
    private $dadosJson;

    /**
     * Construtor da classe
     */
    public function __construct()
    {
    }

    /**
     * Seta os dados em json
     * @param string $json
     */
    public function setDadosJson($json)
    {
        $this->dadosJson = $json;
    }


    public function getDadosJson()
    {
        return $this->dadosJson;
    }


    /**
     * Cria um novo atendimento de ouvidoria
     * @return Object
     * @throws BusinessException
     */

    public function criarNovoAtendimento()
    {


        try {

            db_inicio_transacao();

            $oRetorno = new stdClass();
            $oRetorno->codigo_atendimento = "";
            $oRetorno->sucesso = true;
            $oRetorno->status = "";

            $json = ($this->getDadosJson());
            $oDadosJson = JSON::create()->parse($json);

            if (empty($json)) {
                $msg = "Dados de inclusão não encontrados. Tente mais tarde, ";
                $msg .= "se o problema persistir, contate o administrador do sistema ";
                throw new Exception($msg);
            }

            $dadosRequerente = $oDadosJson->requerente;

            $this->validarRequerente($dadosRequerente);

            $municipio = ($dadosRequerente->municipio);
            if ($dadosRequerente->municipio instanceof \stdClass) {
                $municipio = ($dadosRequerente->municipio->descricao);
            }
            $bairro = $dadosRequerente->municipio;
            if ($dadosRequerente->bairro instanceof \stdClass) {
                $bairro = ($dadosRequerente->bairro->descricao);
            }
            $estado = $dadosRequerente->estado;
            if ($dadosRequerente->estado instanceof \stdClass) {
                $estado = ( $dadosRequerente->estado->codigo);
            }
            $oDaoCidadao = new cl_cidadao();
            $oDaoCidadao->ov02_seq = 1;
            $oDaoCidadao->ov02_nome = $dadosRequerente->nome;
            $oDaoCidadao->ov02_ident = $dadosRequerente->identidade;
            $oDaoCidadao->ov02_cnpjcpf = !empty($dadosRequerente->cpf) ? $dadosRequerente->cpf : $dadosRequerente->cnpj;
            $oDaoCidadao->ov02_endereco = $dadosRequerente->logradouro;
            $oDaoCidadao->ov02_numero = $dadosRequerente->numero;
            $oDaoCidadao->ov02_compl = $dadosRequerente->complemento;
            $oDaoCidadao->ov02_bairro = $bairro;
            $oDaoCidadao->ov02_munic = $municipio;
            $oDaoCidadao->ov02_uf = $estado;
            $oDaoCidadao->ov02_cep = $dadosRequerente->cep;
            $oDaoCidadao->ov02_situacaocidadao = 1;
            $oDaoCidadao->ov02_ativo = 't';
            $oDaoCidadao->ov02_data = date('Y-m-d', db_getsession('DB_datausu'));
            $oDaoCidadao->ov02_datanascimento = $dadosRequerente->nascimento;
            $oDaoCidadao->ov02_sexo = $dadosRequerente->sexo;
            $oDaoCidadao->incluir(null, 1);
            if ($oDaoCidadao->erro_status == "0") {
                $msg = "Ocorreu algo inexperado ao salvar dados do requerente, tente mais tarde, se o problema persistir, contate o administrador do sistema \n";
                $msg .= "Erro técnico: {$oDaoCidadao->erro_msg} \n";
                throw new Exception($msg);
            }

            $this->validarDadosAtendimento($oDadosJson);

            $oDaoAtendimento = new cl_ouvidoriaatendimento();
            $oDaoAtendimento->ov01_situacaoouvidoriaatendimento = 1;
            $oDaoAtendimento->ov01_tipoprocesso = $oDadosJson->tipo_processo;
            $oDaoAtendimento->ov01_formareclamacao = $oDadosJson->forma_reclamacao;
            $oDaoAtendimento->ov01_tipoidentificacao = 2;
            $oDaoAtendimento->ov01_usuario = db_getsession('DB_id_usuario');
            $oDaoAtendimento->ov01_depart = $this->getDepartamentoPadraoDoTipo($oDadosJson->tipo_processo);
            $oDaoAtendimento->ov01_instit = db_getsession('DB_instit');
            $oDaoAtendimento->ov01_numero = $this->getNUmeroAtendimento();
            $oDaoAtendimento->ov01_anousu = db_getsession('DB_anousu');
            $oDaoAtendimento->ov01_dataatend = date('Y-m-d', db_getsession('DB_datausu'));
            $oDaoAtendimento->ov01_horaatend = db_hora();
            $oDaoAtendimento->ov01_requerente = $oDaoCidadao->ov02_nome;
            $oDaoAtendimento->ov01_solicitacao = 'Solicitação aberta via processo eletrônico.';
            $oDaoAtendimento->ov01_executado = "";
            $oDaoAtendimento->incluir(null);
            if ($oDaoAtendimento->erro_status == "0") {
                $msg = "Ocorreu algo inexperado ao salvar dados do atendimento, tente mais tarde, se o problema persistir, contate o administrador do sistema \n";
                $msg .= "Erro técnico: {$oDaoAtendimento->erro_msg} \n";
                throw new Exception($msg);
            }

            $oDaoAtendimentoOuvidoriaProcessoEletronico = new cl_ouvidoriaatendimentoprocessoeletronico();
            $json = json_encode($json);
            $oDaoAtendimentoOuvidoriaProcessoEletronico->ov33_ouvidoriaatendimento = $oDaoAtendimento->ov01_sequencial;
            $oDaoAtendimentoOuvidoriaProcessoEletronico->ov33_informacoesprocesso = pg_escape_string(utf8_decode(json_decode($json)));
            $oDaoAtendimentoOuvidoriaProcessoEletronico->incluir();
            if ($oDaoAtendimentoOuvidoriaProcessoEletronico->erro_status == "0") {
                $msg = "Ocorreu algo inexperado ao salvar dados do atendimento, tente mais tarde, se o problema persistir, contate o administrador do sistema \n";
                $msg .= "Erro técnico: {$oDaoAtendimentoOuvidoriaProcessoEletronico->erro_msg} \n";
                throw new Exception($msg);
            }

            $oDaoOuvidoriaCidadao = new cl_ouvidoriaatendimentocidadao;
            $oDaoOuvidoriaCidadao->ov10_cidadao = $oDaoCidadao->ov02_sequencial;
            $oDaoOuvidoriaCidadao->ov10_seq = 1;
            $oDaoOuvidoriaCidadao->ov10_ouvidoriaatendimento = $oDaoAtendimento->ov01_sequencial;
            $oDaoOuvidoriaCidadao->incluir(null);
            if ($oDaoOuvidoriaCidadao->erro_status == "0") {
                $msg = "Ocorreu algo inexperado ao salvar dados do atendimento, tente mais tarde, se o problema persistir, contate o administrador do sistema \n";
                $msg .= "Erro técnico: {$oDaoOuvidoriaCidadao->erro_msg} \n";
                throw new Exception($msg);
            }
            db_fim_transacao(false);
            $oRetorno->codigo_atendimento = $oDaoAtendimento->ov01_sequencial;
            $oRetorno->numero_atendimento = $oDaoAtendimento->ov01_numero;
            $oRetorno->ano_atendimento = $oDaoAtendimento->ov01_anousu;
            $oRetorno->status = "Atendimento registrado com sucesso. O número do seu atendimento é {$oDaoAtendimento->ov01_sequencial} \n";
            $oRetorno->status = utf8_encode(urldecode($oRetorno->status));
            return $oRetorno;

        } catch (Exception $oException) {
            db_fim_transacao(true);
            $oRetorno->sucesso = false;
            $oRetorno->status = utf8_encode(urldecode($oException->getMessage()));
            return $oRetorno;
        }
    }

    private function validarRequerente($requerente)
    {
        if (empty($requerente->nome)) {
            throw new Exception("Nome do requerente não informado");
        }


        if (empty($requerente->cpf) && empty($requerente->cnpj)) {
            throw new Exception("CPF/CNPJ do requerente não informado");
        }
    }

    private function validarDadosAtendimento($atendimento)
    {

        if (empty($atendimento->tipo_processo)) {
            throw new Exception("Tipo de processo do atendimento não informado");
        }

        if (empty($atendimento->forma_reclamacao)) {
            throw new Exception("Tipo de processo do atendimento não informado");
        }

    }

    private function getNUmeroAtendimento()
    {
        $oOuvidoriaParam = db_utils::getDao("ouvidoriaparametro");
        $sSqlParametro = $oOuvidoriaParam->sql_query_file(db_getsession("DB_instit"), db_getsession("DB_anousu"),
            "ov06_tiponumprocesso");
        $rsParametro = $oOuvidoriaParam->sql_record($sSqlParametro);

        $iTipoControleNumeracao = 1;
        if ($rsParametro && $oOuvidoriaParam->numrows == 1) {
            $iTipoControleNumeracao = 2;
        }
        // Consulta Numero do Atendimento
        if ($iTipoControleNumeracao == 1) { // Sequencial infinito

            $sSqlNumeroAtendimento = "  select max(ov01_numero) + 1 as seq from ouvidoriaatendimento";
            $rsNumeroAtendimento = db_query($sSqlNumeroAtendimento);
            if ($rsNumeroAtendimento) {
                $oNumeroAtendimento = db_utils::fieldsMemory($rsNumeroAtendimento, 0);
                $ov01_numero = $oNumeroAtendimento->seq;
            }
        } else if ($iTipoControleNumeracao == 2) {

            $sSqlAnoAtendimento = "  select 1 from ouvidoriaatendimento where ov01_anousu = " . db_getsession("DB_anousu");
            $rsAnoAtendimento = db_query($sSqlAnoAtendimento);

            if ($rsAnoAtendimento && pg_num_rows($rsAnoAtendimento) > 0) { //Sequencial por ano

                $sSqlProximoNumero = "select max(ov01_numero) + 1 as seq from ouvidoriaatendimento where ov01_anousu = ";
                $sSqlProximoNumero .= db_getsession("DB_anousu");
                $rsProximoNumero = db_query($sSqlProximoNumero);
                $oNumeroAtendimento = db_utils::fieldsMemory($rsProximoNumero, 0);
                $ov01_numero = $oNumeroAtendimento->seq;
            } else {
                $ov01_numero = 1;
            }
        }
        return $ov01_numero;
    }


    /**
     * retorna o andamento padrao do tipo do processo
     * @param $tipoProcesso
     * @return mixed|string|null
     */
    private function getDepartamentoPadraoDoTipo($tipoProcesso)
    {

        $daoAndPadrao = new cl_andpadrao();
        $sqlAndamentoPadrao = $daoAndPadrao->sql_query_file($tipoProcesso, 1, '*');
        $rsAndamentoPadrao = db_query($sqlAndamentoPadrao);
        if (pg_num_rows($rsAndamentoPadrao) > 0) {
            return db_utils::fieldsMemory($rsAndamentoPadrao, 0)->p53_coddepto;
        }
        return db_getsession('DB_coddepto');
    }

}
