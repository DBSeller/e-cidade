<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once(modification("classes/db_dataenvioefd_classe.php"));

$parametros = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$retorno = new stdClass();
$retorno->mensagem = '';
$retorno->erro = false;

$instituicaoSessao = InstituicaoRepository::getInstituicaoSessao();
$instituicao = $instituicaoSessao->getSequencial();

try {

    db_inicio_transacao();

    switch ($parametros->execucao) {

        case 'salvar':

            if(empty($instituicao)){
                throw new BusinessException("Instituição é de preenchimento obrigatório.");
            }

            foreach ($parametros->eventos as $key => $value) {

                if(empty($value->data)) {
                    throw new BusinessException("O campo Data de Início é de preenchimento obrigatório.");
                }

                $dao = new cl_dataenvioefd();
                $where = array(
                    "efd06_instituicao = {$instituicao}",
                    "efd06_arquivo = '{$key}'",
                );

                $sql = $dao->sql_query_file(null, "*", 'efd06_sequencial DESC', implode(' AND ', $where));
                $rs = db_query($sql);

                if (!$rs) {
                    throw new Exception("Não foi possível buscar os preenchimentos anteriores.");
                }

                if (pg_num_rows($rs) === 0) {
                                                                    
                    $dao->efd06_dataenvio = $value->data;
                    $dao->efd06_arquivo = $key;
                    $dao->efd06_instituicao = $instituicao;
                    $dao->incluir();

                    if($dao->erro_status == 0){
                        throw new DBException("Ocorreu um erro ao salvar a data de envio.");
                    }

                } else {
                    $obj = pg_fetch_object($rs);               

                    $dao->efd06_sequencial = $obj->efd06_sequencial;
                    $dao->efd06_dataenvio = $value->data;
                    $dao->efd06_arquivo = $key;
                    $dao->efd06_instituicao = $instituicao;
                    $dao->alterar();

                    if($dao->erro_status == 0){
                        throw new DBException("Ocorreu um erro ao salvar a data de envio.");
                    }                

                }
            }                                        

            $retorno->mensagem = "Configurações salvas com sucesso.";
            break;

        case 'buscar':

            if(empty($instituicao)){
                throw new BusinessException("Campo instituição obrigatório!");
            }

            $dao = new cl_dataenvioefd();
            $sql = $dao->query(array(), "efd06_sequencial, efd06_dataenvio, efd06_arquivo, efd06_instituicao", "efd06_instituicao = {$instituicao}", null, 'efd06_sequencial asc', 1);
            $rs = db_query($sql);
            $eventos = array();

            while ($evento = pg_fetch_array($rs)) {
                $aux = array();
                // $aux['efd06_sequencial']  = $envioArquivo['efd06_sequencial'];
                $aux['efd06_dataenvio']   = $evento['efd06_dataenvio'];
                $aux['efd06_arquivo']     = $evento['efd06_arquivo'];
                // $aux['efd06_instituicao'] = $envioArquivo['efd06_instituicao'];
                $eventos[] = $aux;
            }

            $retorno->eventos = $eventos;

            break;
    }

    db_fim_transacao(false);

} catch (Exception $e) {

    db_fim_transacao(true);

    $retorno->mensagem = $e->getMessage();
    $retorno->erro = true;
}

echo JSON::create()->stringify($retorno);
