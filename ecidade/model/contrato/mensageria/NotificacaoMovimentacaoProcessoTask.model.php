<?php

require_once modification('model/configuracao/Task.model.php');
require_once modification('interfaces/iTarefa.interface.php');
require_once modification('integracao_externa/mensageria/DBSeller/Mensageria/Library/Cliente.php');
require_once(modification("libs/db_conecta.php"));

use DBseller\Mensageria\Library\Cliente;
use ECidade\Patrimonial\Protocolo\Modelo\Processo;
use ECidade\Patrimonial\Protocolo\Repositorio\ProcessoRepositorio;

class NotificacaoMovimentacaoProcessoTask extends \Task implements \iTarefa
{

    public function iniciar()
    {
        parent::iniciar();

        try {

            global $HTTP_SERVER_VARS, $HTTP_POST_VARS, $HTTP_GET_VARS, $_SESSION, $conn;
            $HTTP_SERVER_VARS = $_SESSION;
            $HTTP_POST_VARS = $_POST;
            $HTTP_GET_VARS = $_GET;

            require_once modification("libs/db_conn.php");
            require_once modification("libs/db_stdlib.php");
            require_once modification("libs/db_utils.php");
            require_once modification("dbforms/db_funcoes.php");


            /**
             * Conecta no banco com variaveis definidas no 'libs/db_conn.php'
             */
            if (!($conn = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {
                throw new Exception('Erro ao conectar ao banco.');
            }

            db_putsession('DB_desativar_account', true);
            db_putsession('DB_datausu', date('Y-m-d'));
            db_putsession('DB_acessado', "1325613");
            db_putsession('DB_anousu', date("Y"));
            db_putsession('DB_id_usuario', '1');


            $rsMensageriaProcesso = pg_query($conn,"SELECT * FROM mensageriaprocesso LIMIT 1");


            if (!$rsMensageriaProcesso) {
                throw new DBException('Não foi possível buscar os dados na notificação padrão.'. pg_last_error());
            }

            $oNotificacao = \db_utils::fieldsMemory($rsMensageriaProcesso, 0);

            if ($oNotificacao->p101_notificardatavencimento == 'f') {
                return;
            }


            $aProcessosVencidos = ProcessoRepositorio::vencidos();

            if (empty($aProcessosVencidos)) {
                $this->log('Nenhum Processo Vencido encontrado.');
            }

            $oInstituicao = new Instituicao();

            $oPrefeitura = $oInstituicao->getDadosPrefeitura();
            $sSistema = 'e-cidade.' . strtolower($oPrefeitura->getMunicipio());

            $remetente = new \UsuarioSistema($oNotificacao->p101_usuarioremetente);


            foreach ($aProcessosVencidos as $oProcesso) {

                $oDataUltimaMovimentacao = $oProcesso->getData();
                $iNumeroProcesso = $oProcesso->getNumero();
                $iAno = $oProcesso->getAno();
                $sDataUltimaMovimentacao = $oDataUltimaMovimentacao->getDate(DBDate::DATA_PTBR);

                $diasUtilizar = $oNotificacao->p101_diasprazo;

                $diasAndPad = $oProcesso->getIPrazoDiasEnvio();

                if ($oNotificacao->p101_tipoprazo == "2" && !empty($diasAndPad)) {
                    $diasUtilizar = $diasAndPad;
                }


                $sDataLimiteParaMovimentacao = $oDataUltimaMovimentacao->adiantarPeriodo($diasUtilizar, 'd')
                                                                       ->getDate(DBDate::DATA_PTBR);

                $sConteudoLink = str_replace(
                    array('[numero]/[ano]'),
                    array('<a href="#" onclick="consultaProcesso(' . $oProcesso->getCodigo() . ',false,this)">[numero]/[ano]</a>'),
                    $oNotificacao->p101_mensagem
                );
                $sConteudo = str_replace(
                    array('[numero]', '[ano]', '[data_final]', '[data_inicial]'),
                    array($iNumeroProcesso, $iAno, $sDataLimiteParaMovimentacao, $sDataUltimaMovimentacao),
                    $sConteudoLink
                );

                $aDestinatarios  = ProcessoRepositorio::getUsersDepartmentByPermissionMenuReceive($oProcesso->getCodigoDepartamento());


                if (empty($aDestinatarios)) {
                    continue;
                }

                $aDestiEnv = array();

                foreach ($aDestinatarios as $aDestinatario) {

                    $aDestiEnv[] = array(
                         'sLogin'   => $aDestinatario['login'],
                         'sSistema' => $sSistema
                    );
                }

                $aMensagem = array(
                    'iTipo'     => Cliente::TIPO_NOTIFICACAO,
                    'sAssunto'  => $oNotificacao->p101_assunto,
                    'sConteudo' => $sConteudo,
                    'aDestinatarios' => $aDestiEnv
                );

                Cliente::enviar($remetente->getLogin() , $sSistema, $aMensagem);

            }
        } catch (Exception $oException) {
            $this->log('Erro: ' . $oException->getMessage());
            return;
        }

        parent::terminar();
    }



    public function cancelar()
    {
    }
}
