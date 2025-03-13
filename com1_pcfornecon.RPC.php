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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta" . ".php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_pcfornecon_classe.php"));

$parametros = JSON::requestParameters();
$clpcforneconpad = new cl_pcforneconpad;
$clpcfornecon = new cl_pcfornecon;
$clempagemovconta = new cl_empagemovconta;
$retorno = (object)array('erro' => false, 'mensagem' => '');
$sqlerro = false;

try {

    db_inicio_transacao();

    switch ($parametros->acao) {
        case 'getLancamentos':
            $sql = $clpcfornecon->sql_query_lefpadrao(null,"pc63_contabanco,pc63_cnpjcpf,pc63_numcgm,pc63_banco,pc63_agencia,pc63_agencia_dig,pc63_conta, pc63_conta_dig, pc63_dataconf,case when pc64_contabanco is not null then 'Padrão' else '' end as pc64_contabanco, pc63_tipopix, pc63_chavepix","pc63_contabanco"," pc63_numcgm = $parametros->num_cgm ");
            $rs = db_query($sql);
            
            while ($lancamento = pg_fetch_array($rs)) {
                $lanca = [
                    'codigo' => $lancamento['pc63_contabanco'],
                    'cnpj_cpf' => $lancamento['pc63_cnpjcpf'],
                    'num_cgm' => $lancamento['pc63_numcgm'],
                    'banco' => $lancamento['pc63_banco'],
                    'agencia' => $lancamento['pc63_agencia']." - ".$lancamento['pc63_agencia_dig'],
                    'agencia_solo' => $lancamento['pc63_agencia'],
                    'agencia_dig' => $lancamento['pc63_agencia_dig'],
                    'conta' => $lancamento['pc63_conta']." - ".$lancamento['pc63_conta_dig'],
                    'conta_solo' => $lancamento['pc63_conta'],
                    'conta_dig' => $lancamento['pc63_conta_dig'],
                    'conferido' => is_null($lancamento['pc63_dataconf']) ? '' : $lancamento['pc63_dataconf'],
                    'conta_banco' => $lancamento['pc64_contabanco'],
                    'tipo_pix' => is_null($lancamento['pc63_tipopix']) ? '' : $lancamento['pc63_tipopix'],
                    'chave_pix' => is_null($lancamento['pc63_chavepix']) ? '' : $lancamento['pc63_chavepix']
                ];
                
                $retorno->lancamentos[] = $lanca;
            }
            break;
        case 'excluirLancamento':
            $result_movconta = db_query($clempagemovconta->sql_query_file(null,"*","","e98_contabanco = $pc63_contabanco limit 1 "));
            if(pg_num_rows($result_movconta) > 0){
                throw new Exception("Conta incluída em pagamento de agenda. Exclusão abortada");                
            }

            $clpcforneconpad->excluir($pc63_contabanco);

            if($clpcforneconpad->erro_status == 0){
                throw new Exception($clpcforneconpad->erro_msg);
            }

            $clpcfornecon->excluir($pc63_contabanco);

            if($clpcfornecon->erro_status == 0){
                throw new Exception($clpcfornecon->erro_msg);
            }
            
            break;
    }
            
} catch (Exception $erro) {
    $retorno->mensagem = $erro->getMessage();
    $retorno->erro = true;
}

db_fim_transacao();

echo JSON::create()->stringify($retorno);
