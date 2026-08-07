<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 20/06/18
 * Time: 17:54
 */

namespace ECidade\Tributario\Juridico\ProcessoEletronico\Repository;

use ECidade\Tributario\Juridico\ProcessoEletronico\Configuracao as ConfiguracaoModel;


/**
 * Repository para persistencia dos dados e configuração
 * Class Configuracao
 * @package ECidade\Tributario\Juridico\ProcessoEletronico\Repository
 */
class Configuracao extends \BaseClassRepository
{


    /**
     * @var Configuracao
     */
    protected static $oInstance;

    /**
     * @return Configuracao
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * Retorna a configuração da instituição
     * @param $instituicao
     * @return bool|ConfiguracaoModel
     * @throws \DBException
     */
    public static function getPorInstituicao($instituicao)
    {

        if (empty(self::getInstance()->aColecao[$instituicao])) {

            $daoConfiguracao = new \cl_integracaoprocessoeletronicoconfiguracao;
            $sqlConfiguracao = $daoConfiguracao->sql_query_file(null, "*", null, "v41_instituicao = {$instituicao}");
            $rsConfiguracao  = db_query($sqlConfiguracao);
            if (!$rsConfiguracao) {
                throw new \DBException("Erro ao pesquisar configurações da integração do TJ para a instituição {$instituicao}\n".pg_last_error());
            }
            if (pg_num_rows($rsConfiguracao) == 0) {
                return false;
            }
            return self::getInstance()->make(\db_utils::fieldsMemory($rsConfiguracao, 0));
        }
        return self::getInstance()->aColecao[$instituicao];
    }

    /**
     * @param $iCodigo
     * @return ConfiguracaoModel
     */
    public function make($dados)
    {

        $configuracao = new ConfiguracaoModel();
        $configuracao->setCodigo($dados->v41_sequencial);
        $configuracao->setLocalidade($dados->v41_codigolocalidade);
        $configuracao->setUsuario($dados->v41_usuario);
        $configuracao->setSenha($dados->v41_senha);
        $configuracao->setUrlAmbiente($dados->v41_urlambiente);
        $this->aColecao[$dados->v41_instituicao] = $configuracao;
        return $configuracao;
    }

    /**
     * Persiste os dados da configuração
     * @param ConfiguracaoModel $configuracao
     * @param \Instituicao $instituicao
     * @throws \DBException
     */
    public static function persist(ConfiguracaoModel $configuracao, \Instituicao $instituicao)
    {

        $daoConfiguracao = new \cl_integracaoprocessoeletronicoconfiguracao;
        $daoConfiguracao->v41_instituicao = $instituicao->getCodigo();
        $daoConfiguracao->v41_codigolocalidade = $configuracao->getLocalidade();
        $daoConfiguracao->v41_usuario = $configuracao->getUsuario();
        $daoConfiguracao->v41_senha = $configuracao->getSenha();
        $daoConfiguracao->v41_sequencial = $configuracao->getCodigo();
        $daoConfiguracao->v41_urlambiente = $configuracao->getUrlAmbiente();
        if (empty($daoConfiguracao->v41_sequencial)) {

            $daoConfiguracao->incluir(null);
            $configuracao->setCodigo($daoConfiguracao->v41_sequencial);
        } else {
            $daoConfiguracao->alterar($daoConfiguracao->v41_sequencial);
        }
        if ($daoConfiguracao->erro_status == 0) {
            throw new \DBException("Erro ao salvar dados da configuracao com o TJ.\n{$daoConfiguracao->erro_msg}");
        }
        self::getInstance()->aColecao[$instituicao->getCodigo()] = $configuracao;
    }

}