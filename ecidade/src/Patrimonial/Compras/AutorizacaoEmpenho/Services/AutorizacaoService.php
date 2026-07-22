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

namespace ECidade\Patrimonial\Compras\AutorizacaoEmpenho\Services;

use App\Domain\Patrimonial\Licitacoes\Models\LicitacaoTramita;
use cl_cflicita;
use cl_liclicita;
use cl_pctipocompra;
use DateTime;
use db_utils;
use Dotacao;
use ECidade\Patrimonial\Compras\AutorizacaoEmpenho\Model\Autorizacao;
use ECidade\Patrimonial\Compras\AutorizacaoEmpenho\Repository\AutorizacaoRepository;
use ECidade\Patrimonial\Compras\HistoricoEmpenho\Model\Historico;
use ECidade\Patrimonial\Compras\TipoPrestacaoEmpenho\Model\TipoPrestacao;
use ECidade\Patrimonial\Compras\ItemEmpenho\Model\Item;
use Exception;
use fornecedor;
use Instituicao;
use DBDepartamento;
use JSON;
use InstituicaoRepository;

/**
 * Class ServidorOperadoraSaudeDependenteService
 * @package ECidade\RecursosHumanos\Pessoal\Service
 */
class AutorizacaoService
{
    private $repositorio;

    /**
     * AutorizacaoService constructor.
     * @param AutorizacaoRepository $repositorio
     */
    public function __construct(AutorizacaoRepository $repositorio)
    {
        $this->repositorio = $repositorio;
    }

    /**
     * @param $codigoTipoCompra
     * @param $daoTipoLicitacao
     * @return array|bool
     * @throws Exception
     */
    public function buscaLicitacoesPorTipoCompra($codigoTipoCompra, $daoTipoLicitacao)
    {
        return $this->repositorio->buscarLicitacoesPorTipoCompra($codigoTipoCompra, $daoTipoLicitacao);
    }

    /**
     * @param $codigo
     * @return bool|Autorizacao
     * @throws Exception
     */
    public function buscaAutorizacao($codigo)
    {
        return $this->repositorio->find($codigo);
    }

    /**
     * @param fornecedor $fornecedor
     * @param boolean $isInsersao
     * @return array
     */
    public function validaDadosAutorizacao(fornecedor $fornecedor, $isInsersao = false)
    {
        $iAnoUsu = db_getsession("DB_anousu");

        if ($isInsersao) {
            /* [Extensão] Programação Financeira */
        }

        $fornecedor->verificaBloqueioAutorizacaoEmpenho(null);
        $statusBloqueio = $fornecedor->getStatusBloqueio();

        $mensagem = 'Fornecedor com débito.';

        return array(
            'statusFornecedor' => $statusBloqueio,
            'mensagem' => $statusBloqueio == 1 ? '' : $mensagem
        );
    }

    /**
     * @throws Exception
     */
    public function validaDataSessao()
    {
        // Valida ano da sessao com a data da sessao... pois devem ser iguais
        $anoUsu = db_getsession("DB_anousu");
        $anoData = date("Y", db_getsession("DB_datausu"));

        if ((int)$anoUsu <> (int)$anoData) {
            $dataUsu = date("d/m/Y", db_getsession("DB_datausu"));
            throw new Exception("ERRO: Ano da Sessão ($anoUsu) diferente do Ano da Data Atual ($dataUsu)!");
        }
    }

    /**
     * @param $codigoAutorizacao
     * @throws Exception
     */
    public function validaUsuario($codigoAutorizacao)
    {
        $retorno = $this->repositorio->buscaUsuarioPorAutorizacao($codigoAutorizacao);
        $usuario = $retorno['e54_login'];
        $departamento = $retorno['e54_depto'];

        if ($usuario != db_getsession("DB_id_usuario")) {
            $result = $this->repositorio->buscaDepartamentoPorUsuarioEDepartamento($usuario, $departamento);
            if (!$result) {
                throw new Exception('Usuário sem permissão de alterar!');
            }
        }
    }


    /**
     * @param $parametros
     * @param Instituicao $instituicao
     * @return Autorizacao
     * @throws Exception
     */
    public function salvar($parametros, Instituicao $instituicao)
    {
        $this->validaDataSessao();
        $isInsercao = false;
        $dataUsuario = new DateTime(date('Y-m-d', db_getsession('DB_datausu')));

        if (!empty($parametros->e54_autori)) {
            $this->validaUsuario($parametros->e54_autori);
        } else {
            $isInsercao = true;
        }

        //valida fornecedor
        if (empty($parametros->e54_numcgm)) {
            throw new Exception("O fornecedor deve ser informado.");
        }

        $fornecedor = new fornecedor($parametros->e54_numcgm);

        $retornoValidacaoFornecedor = $this->validaDadosAutorizacao($fornecedor, $isInsercao);

        if ($retornoValidacaoFornecedor['statusFornecedor'] == 3) {
            throw new Exception($retornoValidacaoFornecedor['mensagem']);
        }

        //instancia e seta atributos
        $autorizacao = new Autorizacao();
        $autorizacaoImportada = "";

        if (!empty($parametros->e54_autori)) {
            $autorizacao->setCodigoAutorizacao($parametros->e54_autori);
        }


        $numeroLicitacao = '';
        if (!empty($parametros->numeroLicitacao) && !empty($parametros->anoLicitacao)) {
            $numeroLicitacao = "$parametros->numeroLicitacao/$parametros->anoLicitacao";
        }

        $autorizacao->setFornecedor($fornecedor);
        $autorizacao->setCodigoTipoCompra($parametros->e54_codcom);
        $autorizacao->setTipoLicitacao($parametros->e54_tipol);
        $autorizacao->setCodigoTipoEmpenho($parametros->e54_codtipo);
        $autorizacao->setNumeroLicitacao($numeroLicitacao);
        $autorizacao->setDestino($parametros->e54_destin);
        $autorizacao->setCodigoCaracteristicaPeculiar($parametros->e54_concarpeculiar);
        $autorizacao->setResumo(pg_escape_string($parametros->e54_resumo));
        $autorizacao->setInstituicao($instituicao);
        $autorizacao->setDepartamento(new DBDepartamento(db_getsession("DB_coddepto")));
        $autorizacao->setLogin(db_getsession("DB_id_usuario"));
        $autorizacao->setAnousu(db_getsession("DB_anousu"));
        $autorizacao->setEmiss($dataUsuario);
        $autorizacao->setInstituicaoLicitacao($parametros->instituicaoLicitacao);
        if (!empty($parametros->e54_valor)) {
            $autorizacao->setValor($parametros->e54_valor);
        }

        if (!is_null($parametros->e57_codhist)) {
            $autorizacao->setHistorico(new Historico($parametros->e57_codhist));
        }

        if (!empty($parametros->e44_tipo)) {
            $autorizacao->setTipoPrestacao(new TipoPrestacao($parametros->e44_tipo));
        }

        $numeroProcesso = $parametros->e150_numeroprocesso;

        if (!empty($parametros->o58_coddot)) {
            $autorizacao->setDotacao(new Dotacao($parametros->o58_coddot, $parametros->o58_anousu));
        }

        if (!empty($parametros->e55_itens)) {
            $itens = JSON::create()->parse($parametros->e55_itens);

            if ($itens) {
                $arrayItens = array();
                foreach ($itens as $item) {
                    $arrayItens[] = Item::fromState((array)$item);
                }
                $autorizacao->setItens($arrayItens);
            }
        }

        if (!empty($parametros->e54_autori_importada)) {
            $autorizacaoImportada = $parametros->e54_autori_importada;
        }

        try {
            $autorizacao = $this->repositorio->salvar($autorizacao, $numeroProcesso, $autorizacaoImportada);
        } catch (Exception $e) {
            throw new Exception('Não foi possível incluir a Autorização.');
        }

        return $autorizacao;
    }
}
