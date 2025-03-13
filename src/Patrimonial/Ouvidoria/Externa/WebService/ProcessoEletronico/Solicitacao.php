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
namespace ECidade\Patrimonial\Ouvidoria\Externa\WebService\ProcessoEletronico;

use ECidade\Patrimonial\Ouvidoria\Externa\WebService\ProcessoEletronico\Model\ProcessoEletronico as ProcessoEletronico;
use ECidade\Patrimonial\Ouvidoria\Externa\WebService\ProcessoEletronico\Model\Requerente;
use ECidade\Patrimonial\Ouvidoria\Externa\WebService\ProcessoEletronico\Repository\ProcessoEletronico
    as ProcessoEletronicoRepository;
use ECidade\Patrimonial\Protocolo\TipoProcesso\Model\TipoProcesso;
use ECidade\Patrimonial\Protocolo\TipoProcesso\Repository\TipoProcesso as TipoProcessoRepository;

use \DBDate;
use \DBDepartamentoRepository;
use Illuminate\Support\Facades\Log;
use \InstituicaoRepository;
use \JSON;
use \UsuarioSistemaRepository;

class Solicitacao
{
    /**
     * @var string
     */
    private $metadados;

    /**
     * @var int
     */
    private $instituicao;

    /**
     * @var int
     */
    private $codigoDepartamento;

    /**
     * @var int
     */
    private $tipoProcesso;

    /**
     * @var String
     */
    private $requerenteNome;

    /**
     * @var int
     */
    private $requerenteCpf;

    private $codigoAtendimentoAnterior;

    private $clientAPPAtendimentoID;


    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getMetadados()
    {
        return $this->metadados;
    }

    /**
     * @param string $metadados
     */
    public function setMetadados($metadados)
    {
        $this->metadados = $metadados;
    }

    /**
     * @return int
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param int $instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @return int
     */
    public function getTipoProcesso()
    {
        return $this->tipoProcesso;
    }

    /**
     * @param int $tipoProcesso
     */
    public function setTipoProcesso($tipoProcesso)
    {
        $this->tipoProcesso = $tipoProcesso;
    }

    /**
     * @return int
     */
    public function getRequerenteNome()
    {
        return $this->requerenteNome;
    }

    /**
     * @param int $requerenteNome
     */
    public function setRequerenteNome($requerenteNome)
    {
        $this->requerenteNome = urldecode($requerenteNome);
    }

    /**
     * @return int
     */
    public function getRequerenteCpf()
    {
        return $this->requerenteCpf;
    }

    /**
     * @param int $requerenteCpf
     */
    public function setRequerenteCpf($requerenteCpf)
    {
        $this->requerenteCpf = $requerenteCpf;
    }

    /**
     * @return int
     */
    public function getCodigoDepartamento()
    {
        return $this->codigoDepartamento;
    }

    /**
     * @param int $codigoDepartamento
     */
    public function setCodigoDepartamento($codigoDepartamento)
    {
        $this->codigoDepartamento = $codigoDepartamento;
    }

    /**
     * @return int
     */
    public function getCodigoAtendimentoAnterior()
    {
        return $this->codigoAtendimentoAnterior;
    }

    /**
     * @param int $codigoAtendimentoAnterior
     */
    public function setCodigoAtendimentoAnterior($codigoAtendimentoAnterior)
    {
        $this->codigoAtendimentoAnterior = $codigoAtendimentoAnterior;
    }

    /**
     * @return mixed
     */
    public function getClientAPPAtendimentoID()
    {
        return $this->clientAPPAtendimentoID;
    }

    /**
     * @param mixed $clientAPPAtendimentoID
     */
    public function setClientAPPAtendimentoID($clientAPPAtendimentoID)
    {
        $this->clientAPPAtendimentoID = $clientAPPAtendimentoID;
    }





    /**
     * @throws \ParameterException
     * @throws \Exception
     */
    public function salvar()
    {

        $tipoProcessoRepository = TipoProcessoRepository::getInstancia()->getByCodigo($this->tipoProcesso);

        $solicitacaoJSON = is_array($this->metadados) ? json_encode($this->metadados) : $this->metadados;
        $tipoProcesso = new TipoProcesso();
        $tipoProcesso->setCodigo($this->tipoProcesso);
        $usuarioSistema = UsuarioSistemaRepository::getPorCodigo(
            ProcessoEletronico::CODIGO_USUARIO
        );
        $departamento = DBDepartamentoRepository::getDBDepartamentoByCodigo(
            $this->codigoDepartamento
        );
        $instituicao = InstituicaoRepository::getInstituicaoByCodigo(
            $tipoProcessoRepository->getCodigoInstituicao()
        );

        $processoEletronicoModel = new ProcessoEletronico();
        $processoEletronicoModel->setRequerente(new Requerente($this->requerenteNome, $this->requerenteCpf));
        $processoEletronicoModel->setSolicitacaoJSON($solicitacaoJSON);
        $processoEletronicoModel->setTipoProcesso($tipoProcesso);
        $processoEletronicoModel->setUsuario($usuarioSistema);
        $processoEletronicoModel->setDepartamento($departamento);
        $processoEletronicoModel->setInstituicao($instituicao);
        $processoEletronicoModel->setClientAPPAtendimentoID($this->getClientAPPAtendimentoID());
        if (!empty($this->codigoAtendimentoAnterior)) {
            $processoEletronicoModel->setCodigoAtendimentoAnterior($this->codigoAtendimentoAnterior);
        }

        $response = ProcessoEletronicoRepository::getInstancia()->salvar($processoEletronicoModel);
        if ($response->sucesso === true) {
            $response->status = utf8_encode(urldecode($tipoProcessoRepository->getMensagem()));
        }
        Log::debug("caiu em sucesso".json_encode($response));
        return $response;
    }
}
