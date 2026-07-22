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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));


$json              = new services_json();
$parametros        = JSON::create()->parse(str_replace('\\', "", $_POST["json"]));
$retorno           = new stdClass();
$retorno->iStatus  = 1;
$retorno->mensagem = '';

define("MENSAGENS", "arquivo-de-mensagens.json");
define("TIPO_COMBOBOX", "6");

try {

    db_inicio_transacao();

    switch ($parametros->execucao) {

        case "incluir":

            $codigoTipoAssentamento = vincularAfastamentoEsocial($parametros);
            atualizarTipoAssentamento($codigoTipoAssentamento);

            $daoGrupoMotivoAfastamentoEsocial = new cl_grupomotivoafastamentoesocial();
            $sqlConsultaCadAttDinamico = $daoGrupoMotivoAfastamentoEsocial->sql_query_file(
                null,
                "eso10_db_cadattdinamico",
                null,
                "eso10_sequencial = {$parametros->afastamento}"
            );

            $rsConsultaCadAttDinamico = db_query($sqlConsultaCadAttDinamico);
            if (!$rsConsultaCadAttDinamico) {
                throw new DBException("Erro ao buscar o código do grupo de campos dinâmicos do eSocial.");
            }

            if (pg_num_rows($rsConsultaCadAttDinamico) == 0) {
                throw new  BusinessException("Não há configuração de campos dinâmicos para afastamento selecionado.");
            }

            $codigoGrupoAttDinamico = db_utils::fieldsMemory($rsConsultaCadAttDinamico, 0)->eso10_db_cadattdinamico;

            $daoTipoAsseCadAttDinamico = new cl_tipoassedb_cadattdinamico();
            $sqlVinculoTipoAssentamento = $daoTipoAsseCadAttDinamico->sql_query_file(
                null,
                $codigoTipoAssentamento,
                "h79_db_cadattdinamico",
                null,
                null
            );
            $rsVinculoTipoAssentamento = db_query($sqlVinculoTipoAssentamento);
            if(!$rsVinculoTipoAssentamento) {
                throw new DBException("Erro ao buscar tipo de assentamento vinculado a grupo de atributos dinâmicos.");
            }

            if (pg_num_rows($rsVinculoTipoAssentamento) > 0) {

                $codigoGrupoAttDinamicoVinculado = db_utils::fieldsMemory($rsVinculoTipoAssentamento, 0)->h79_db_cadattdinamico;

                $daoMapeamentoEsocial = new cl_mapeamentoatributosesocial();
                $daoAtributosEsocial  = new cl_db_cadattdinamicoatributos();

                $sqlAtributosEsocial = $daoAtributosEsocial->sql_query_file(
                    null,
                    "*",
                    null,
                    "db109_db_cadattdinamico = {$codigoGrupoAttDinamico}"
                );
                $rsAttributosEsocial = db_query($sqlAtributosEsocial);

                if (!$rsAttributosEsocial || pg_num_rows($rsAttributosEsocial) == 0) {
                    throw new DBException("Erro ao buscar os atributos do eSocial.");
                }

                $linhasAtributosEsocial = pg_num_rows($rsAttributosEsocial);
                for ($linha = 0; $linha < $linhasAtributosEsocial; $linha++) {

                    $dadosAfastamento = db_utils::fieldsMemory($rsAttributosEsocial, $linha);

                    if (!empty($dadosAfastamento)) {
                        $sqlValidacao = "
                            select 
                                   * 
                            from 
                                mapeamentoatributosesocial 
                                inner join db_cadattdinamicoatributos on 
                                    db109_sequencial = db39_camponovo 
                            where 
                                  db39_campoorigem = {$dadosAfastamento->db109_sequencial}
                                    and db109_db_cadattdinamico = {$codigoGrupoAttDinamicoVinculado}";
                        $rsValidacao = db_query($sqlValidacao);
                        if (!$rsValidacao) {
                            throw new DBException("Erro ao verificar grupos de atributos dinamicos.");
                        }
                        if (pg_num_rows($rsValidacao) == 0) {
                            $codigoNovoAtributo = adicionarNovoAtributo($dadosAfastamento, $codigoGrupoAttDinamicoVinculado);
                            adicionarOpcoes($dadosAfastamento, $codigoNovoAtributo);

                            $daoMapeamentoEsocial->db39_camponovo = $codigoNovoAtributo;
                            $daoMapeamentoEsocial->db39_campoorigem = $dadosAfastamento->db109_sequencial;
                            $daoMapeamentoEsocial->incluir(null);

                            if ($daoMapeamentoEsocial->erro_status == "0") {
                                throw new DBException("Erro ao incluir vínculo do novo atributo dinâmico.");
                            }
                        }
                    }
                }

            } else {
                $codigoTipoAsse = $codigoTipoAssentamento;
                $daoTipoAsseCadAttDinamico->incluir($codigoGrupoAttDinamico, $codigoTipoAsse);

                if($daoTipoAsseCadAttDinamico->erro_status == "0") {
                    throw new DBException("Erro ao vincular tipo de assentamento com afastamento do eSocial.");
                }
            }

            break;

        case "consultar":

            $daoAfastamentosesocial = new cl_afastamentosesocial();
            $sql = $daoAfastamentosesocial->sql_query_grupo_assentamento_codigos();
            $rsAfastamento = db_query($sql);

            if (empty($rsAfastamento)) {
                throw new DBException("Erro ao consultar dados de afastamentos.");
            }
            $afastamento = db_utils::makeCollectionFromRecord($rsAfastamento, function ($registro){
                $retorno = new stdClass();
                $retorno->codigo = $registro->eso08_sequencial;
                $retorno->codigoAssentamento = $registro->h12_assent;
                $retorno->tipoAssentamento = sprintf('%s - %s', $registro->h12_assent, $registro->h12_descr);
                $retorno->afastamento = $registro->eso10_descricao;
                return $retorno;
            });

            $retorno->dados = $afastamento;
            break;

        case "excluir":

            $daoMapeamentoatributosesocial = new cl_mapeamentoatributosesocial();
            $daoCadAttDinamicoAtributo = new cl_db_cadattdinamicoatributos();
            $daoAfastamentosesocial = new cl_afastamentosesocial();
            $daoOpcoesAtributos =  new cl_db_cadattdinamicoatributosopcoes();

            $sqlMapeamentoAtributos = $daoAfastamentosesocial->sql_query_mapeamento_atributos("db110_valor, db39_camponovo", "eso08_sequencial = {$parametros->codigo}");
            $rsMapeamentoAtributos = db_query($sqlMapeamentoAtributos);

            if (!$rsMapeamentoAtributos) {
                throw new DBException("Erro ao buscar os atributos dinâmicos.");
            }

            $totalAtributos = pg_num_rows($rsMapeamentoAtributos);

            for ($i = 0; $i < $totalAtributos; $i++) {
                $dadosMapeamentoAtributos = db_utils::fieldsMemory($rsMapeamentoAtributos, $i);

                if (!empty($dadosMapeamentoAtributos->db110_valor)) {
                    throw new BusinessException("Não é possível excluir vínculo, pois há assentamentos lançados com este tipo vinculado.");
                }

                $daoOpcoesAtributos->excluir(null, "db18_cadattdinamicoatributos = {$dadosMapeamentoAtributos->db39_camponovo}");
                if($daoOpcoesAtributos->erro_status == 0) {
                    throw new DBException("Erro ao excluir opções de atributos dinâmico.");
                }

                $daoMapeamentoatributosesocial->excluir(null, null, "db39_camponovo = {$dadosMapeamentoAtributos->db39_camponovo}");
                if ($daoMapeamentoatributosesocial->erro_status == "0") {
                    throw new DBException("Erro ao excluír vínculo do atributo dinâmico.");
                }

                $daoCadAttDinamicoAtributo->excluir(null,"db109_sequencial = {$dadosMapeamentoAtributos->db39_camponovo}");
                if ($daoCadAttDinamicoAtributo->erro_status == "0") {
                    throw new DBException("Erro ao excluír atributo dinâmico.");
                }
            }

            $sqlValoresAtributos = $daoAfastamentosesocial->sql_query_atributos_valores("db110_valor", "eso08_sequencial = {$parametros->codigo}");
            $rsValoresAtributos = db_query($sqlValoresAtributos);

            if (!$rsValoresAtributos) {
                throw new DBException("Erro ao buscar os valores dos atributos dinâmicos.");
            }

            if (pg_num_rows($rsValoresAtributos) > 0) {
                throw new BusinessException("Não é possível excluir vínculo, pois há assentamentos lançados com este tipo vinculado.");
            }

            $sqlAfastamentoEsocial = $daoAfastamentosesocial->sql_query(null, '*', null, "eso08_sequencial = {$parametros->codigo}");
            $rsAfastamentoEsocial = db_query($sqlAfastamentoEsocial);

            if (!$rsAfastamentoEsocial || pg_num_rows($rsAfastamentoEsocial) == 0) {
                throw new DBException("Erro ao buscar os dados do afastamento para o eSocial.");
            }

            $dadosAssentamento = db_utils::fieldsMemory($rsAfastamentoEsocial, 0);
            $daoTipoAsseCadAttDinamico = new cl_tipoassedb_cadattdinamico();
            $daoTipoAsseCadAttDinamico->excluir($dadosAssentamento->eso10_db_cadattdinamico, $dadosAssentamento->eso08_tipoasse);

            if ($daoTipoAsseCadAttDinamico->erro_status == "0") {
                throw new DBException("Erro ao excluir vínculo do tipo de assentamento com afastamento do eSocial.");
            }

            $resultadoExcluir = $daoAfastamentosesocial->excluir($parametros->codigo);

            if (empty($resultadoExcluir)) {
                throw new DBException("Erro ao excluir o vínculo de afastamento.");
            }

            $retorno->mensagem = "Exclusão efetuada com sucesso!";
            break;

        case "validarTipoAssentamento":

            $daoTipoasse = new cl_tipoasse();
            $sqlTipoasse = $daoTipoasse->sql_query_file(
              null,
              "h12_tipo",
              null,
              "h12_assent = '{$parametros->tipoAssentamento}'"
            );

            $rsTipoAsse = db_query($sqlTipoasse);

            if (empty($rsTipoAsse)) {
                throw new DBException("Erro ao consultar o tipo de assentamento.");
            }

            $tipoAssentamento = db_utils::fieldsMemory($rsTipoAsse, 0);

            $retorno->afastamento = false;
            $retorno->mensagem = "Ao efetuar o vínculo com o afastamento do eSocial, este assentamento terá seu tipo alterado para afastamento.";

            if ($tipoAssentamento->h12_tipo == TipoAssentamento::AFASTAMENTO) {
                $retorno->afastamento = true;
            }

            break;
    }

    db_fim_transacao(false);

} catch (Exception $erro){

    db_fim_transacao(true);
    $retorno->iStatus = 2;
    $retorno->mensagem = $erro->getMessage();
}

/**
 * Atualiza o tipo de assentamento informado para ser um afastamento
 * @param $codigoTipoAssentamento
 * @throws DBException
 */
function atualizarTipoAssentamento($codigoTipoAssentamento) {
    $tipo = TipoAssentamento::AFASTAMENTO;

    $sqlAlterarTipoAssentamento  = " update tipoasse                                     ";
    $sqlAlterarTipoAssentamento .= "    set h12_tipo = '{$tipo}'                         ";
    $sqlAlterarTipoAssentamento .= "  where h12_codigo = {$codigoTipoAssentamento} ";

    $rsAlterarTipoAssentamento  = db_query($sqlAlterarTipoAssentamento);

    if (!$rsAlterarTipoAssentamento ) {
        throw new DBException("Erro ao alterar o tipo do assentamento");
    }
}

/**
 * Víncula o tipo de assentamento com o grupo do eSocial
 * @param $parametros
 * @return int
 * @throws DBException
 */
function vincularAfastamentoEsocial($parametros) {

    $daoTipoasse = new cl_tipoasse();
    $sqlTipoasse = $daoTipoasse->sql_query_file(
        null,
        "*",
        null,
        "h12_assent = '{$parametros->tipoAssentamento}'"
    );

    $rsTipoAsse = $daoTipoasse->sql_record($sqlTipoasse);

    if (!$rsTipoAsse) {
        throw new DBException("Erro ao buscar código do tipo de assentamento.");
    }

    $tipoAssentamento = db_utils::fieldsMemory($rsTipoAsse, 0);

    $daoAfastamentosesocial = new cl_afastamentosesocial();
    $daoAfastamentosesocial->eso08_tipoasse = $tipoAssentamento->h12_codigo;
    $daoAfastamentosesocial->eso08_grupomotivoafastamentoesocial = $parametros->afastamento;
    $resultadoIncluir = $daoAfastamentosesocial->incluir();

    if (!$resultadoIncluir) {
        throw new DBException($daoAfastamentosesocial->erro_sql);
    }

    return $tipoAssentamento->h12_codigo;
}

/**
 * Clona o atributo dinâmico do eSocial
 * @param $dadosAfastamento
 * @param $codigoGrupoAttDinamicoVinculado
 * @return int|null
 * @throws DBException
 */
function adicionarNovoAtributo($dadosAfastamento, $codigoGrupoAttDinamicoVinculado) {

    $daoAtributosEsocial  = new cl_db_cadattdinamicoatributos();
    $daoAtributosEsocial->db109_sequencial = null;
    $daoAtributosEsocial->db109_db_cadattdinamico = $codigoGrupoAttDinamicoVinculado;
    $daoAtributosEsocial->db109_codcam = $dadosAfastamento->db109_codcam;
    $daoAtributosEsocial->db109_descricao = $dadosAfastamento->db109_descricao;
    $daoAtributosEsocial->db109_valordefault = $dadosAfastamento->db109_valordefault;
    $daoAtributosEsocial->db109_tipo = $dadosAfastamento->db109_tipo;
    $daoAtributosEsocial->db109_nome = $dadosAfastamento->db109_nome;
    $daoAtributosEsocial->db109_obrigatorio = $dadosAfastamento->db109_obrigatorio;
    $daoAtributosEsocial->db109_ativo = $dadosAfastamento->db109_ativo;
    $daoAtributosEsocial->db109_fixo = $dadosAfastamento->db109_fixo;
    $daoAtributosEsocial->db109_db_formulas = $dadosAfastamento->db109_db_formulas;
    $daoAtributosEsocial->incluir(null);

    if ($daoAtributosEsocial->erro_status == "0") {
        throw new DBException("Erro ao incluir novo atributo dinâmico.");
    }

    return $daoAtributosEsocial->db109_sequencial;
}

/**
 * Clona as opções do atributo dinâmico do eSocial
 * @param $dadosAfastamento
 * @param $codigoAtributoEsocial
 * @throws DBException
 */
function adicionarOpcoes($dadosAfastamento, $codigoAtributoEsocial) {

    $daoOpcoesAtributos   = new cl_db_cadattdinamicoatributosopcoes();

    if($dadosAfastamento->db109_tipo == TIPO_COMBOBOX) {

        $sqlOpcoesAtributo = $daoOpcoesAtributos->sql_query_file(null, "*", null, "db18_cadattdinamicoatributos = {$dadosAfastamento->db109_sequencial}");
        $rsOpcoesAtributo = db_query($sqlOpcoesAtributo);

        if(!$rsOpcoesAtributo) {
            throw new DBException("Erro ao buscar opções de atributos.");
        }

        $linhasOpcoesAtributo = pg_num_rows($rsOpcoesAtributo);

        for($i = 0; $i < $linhasOpcoesAtributo; $i++) {

            $dadosOpcoesAtributos = db_utils::fieldsMemory($rsOpcoesAtributo, $i);
            $daoOpcoesAtributos->db18_sequencial = null;
            $daoOpcoesAtributos->db18_cadattdinamicoatributos = $codigoAtributoEsocial;
            $daoOpcoesAtributos->db18_opcao = $dadosOpcoesAtributos->db18_opcao;
            $daoOpcoesAtributos->db18_valor = $dadosOpcoesAtributos->db18_valor;
            $daoOpcoesAtributos->incluir(null);

            if($daoOpcoesAtributos->erro_status == 0) {
                throw new DBException("Erro ao incluir opções do atributo dinâmico.");
            }
        }
    }
}

$retorno->erro = $retorno->iStatus == 2;
echo JSON::create()->stringify($retorno);
