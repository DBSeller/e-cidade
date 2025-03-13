<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
 *                      www.dbseller.com.br
 *                   e-cidade@dbseller.com.br
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

namespace ECidade\Tributario\Integracao\JuntaComercial\Envio;

/**
 * Class Inscricao
 * @package ECidade\Tributario\Integracao\JuntaComercial\Envio
 */
class Inscricao
{
    const SUCESSO = 3;
    const DUPLICADO = 5;
    const XML_INVALIDO = 6;
    const SERVICO_INVALIDO = 9;
    const FUNCAO = 110;
    const ARQUIVO_CONFIGURACAO = 'config/Regin/webservice.ini';

    private $soapClient;

    /**
     * Inscricao constructor.
     * @throws \ParameterException
     */
    public function __construct()
    {

        if (!file_exists(self::ARQUIVO_CONFIGURACAO)) {
            throw new \ParameterException('Arquivo de configuração do webservice não encontrado.');
        }
        $configuracoes = parse_ini_file(self::ARQUIVO_CONFIGURACAO);

        $opcoes = array(
          'location'      => $configuracoes["webservice_url"],
          'uri'           => $configuracoes["webservice_url"]
        );

        $this->soapClient = new \SoapClient(
          "{$configuracoes["webservice_url"]}?wsdl",
          $opcoes
        );
    }

    /**
     * Realiza o envio dos dados para webservice do regin
     * @param $parametrosTarefa
     * @throws \DBException
     */
    public function enviar($parametrosTarefa)
    {
        $parametrosSoap = array(
          "servico" => $parametrosTarefa['servico'],
          "funcao" => self::FUNCAO,
          "protocolo" => $parametrosTarefa['protocolo'],
          "xml" => file_get_contents($parametrosTarefa['arquivo']),
          "par8" => date("Ymd"),
          "par9" => $parametrosTarefa['cnpjInstituicao'],
          "par10" => $parametrosTarefa['cnpjInstituicao']
        );

        $retorno = $this->soapClient->__soapCall("recebeRUC", $parametrosSoap);

        $daoJuntaComercial = new \cl_juntacomercialprotocolo();
        $sqlIntegra        = $daoJuntaComercial->integrar($parametrosTarefa['protocolo'], $retorno);
        $retornoIntegra    = db_query($sqlIntegra);

        if (!$retornoIntegra) {
            throw new \DBException("Erro ao alterar registro do protocolo.");
        }

        unlink($parametrosTarefa['arquivo']);
    }

    /**
     * Adiciona um job para o envio dos dados de alvaras da inscrição municipal para o regin
     * @param $protocolo
     * @param $servico
     * @param $caminhoArquivo
     * @param $cnpjInstituicao
     */
    public static function criarTarefa($protocolo, $servico, $caminhoArquivo, $cnpjInstituicao)
    {
        $job = new \Job();
        $job->setNome('JuntaComercial'.$protocolo);
        $job->setCodigoUsuario(1);
        $time = new \DateTime();
        $job->setMomentoCricao($time->modify('+ 1 minute')->getTimestamp());
        $job->setDescricao('Junta Comercial');
        $job->setNomeClasse('JuntaComercialTask');
        $job->setTipoPeriodicidade(\Agenda::PERIODICIDADE_UNICA);
        $job->adicionarParametro("protocolo", $protocolo);
        $job->adicionarParametro("arquivo", $caminhoArquivo);
        $job->adicionarParametro("servico", $servico);
        $job->adicionarParametro("cnpjInstituicao", $cnpjInstituicao);
        $job->setCaminhoPrograma('model/issqn/JuntaComercialTask.model.php');
        $job->salvar();
    }
}