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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');
require_once(modification("classes/db_parcvalor_classe.php"));
use ECidade\Tributario\Arrecadacao\Repository\ParcValor as ParcValorRepository;

$parametros = JSON::requestParameters();

$retorno = new stdClass();
$retorno->status = 1;
$retorno->mensagem = '';

try{

    db_inicio_transacao();

    switch ($parametros->exec){

        case "salvar":

            if(empty($parametros->dados)){
                throw new BusinessException("Campo de dados do parcelamento vazio.");
            } else if (empty($parametros->numpre)){
                throw new BusinessException("Numpre de débitos não informado!");
            }

            ParcValorRepository::deletaDebitos($parametros->numpre);

            foreach ($parametros->dados as $dado) {
                
                if(empty($dado->data) && empty($dado->valor)){
                    throw new BusinessException("Valor e data de parcela vazios!");
                }

                $cl_parcvalor = new cl_parcvalor;
                $cl_parcvalor->k189_numpre = $parametros->numpre;
                $cl_parcvalor->k189_numpar = $dado->numpar;
                $cl_parcvalor->k189_valor = $dado->valor;
                $cl_parcvalor->k189_data = $dado->data;
                $cl_parcvalor->incluir();

            }
            
            $retorno->mensagem = "Dados de parcelamento salvos com sucesso.";
            break;

        default:
            throw new Exception("Opção inválida!");
            
    }

    db_fim_transacao(false);

} catch (Exception $erro){

    db_fim_transacao(true);
    $retorno->status = 2;
    $retorno->mensagem = $erro->getMessage();
}

$retorno->erro = $retorno->status == 2;
echo JSON::create()->stringify($retorno);