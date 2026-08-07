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

namespace ECidade\Saude\Laboratorio\Integracao\Luckmann\Service;

use AtributoValorReferenciaNumerico;
use ECidade\Saude\Laboratorio\Integracao\Luckmann\Service\Parametros as ParametrosService;
use ECidade\Saude\Laboratorio\Integracao\Luckmann\Enum\Parametros as ParametrosEnum;
use ECidade\Saude\Laboratorio\Integracao\Luckmann\Builder\ImportacaoInconsistencia;
use Exception;
use RequisicaoExame;
use ResultadoExameAtributo;
use RequisicaoLaboratorial;

/**
 * Class Resultado
 * @package ECidade\Saude\Laboratorio\Integracao\Luckmann\Service
 */
class Resultado
{
    const RESULTADO_NAO_SALVO = 1;

    /**
     * Caminho do XML a ser lido
     * @var string
     */
    private $xml;

    /**
     * @var RequisicaoLaboratorial
     */
    private $requisicaoLaboratorial;

    /**
     * @var array
     */
    private $situacoesValidas = array(
      RequisicaoExame::COLETADO,
      RequisicaoExame::LANCADO
    );

    /**
     * @var ImportacaoInconsistencia
     */
    private $importacaoInconsistencia;

    /**
     * @var boolean
     */
    private $arquivoComInconsistencias = false;

    /**
     * Resultado constructor.
     * @param $xml
     * @param ImportacaoInconsistencia $importacaoInconsistencia
     * @throws Exception
     */
    public function __construct($xml, ImportacaoInconsistencia $importacaoInconsistencia)
    {
        if (empty($xml)) {
            throw new Exception('XML não informado.');
        }

        $parametrosService = new ParametrosService(ParametrosEnum::JSON_CONFIGURACOES);
        $pastaResultados = $parametrosService->getParametros()->getPastaResultados();

        if (!$this->xml = simplexml_load_file($pastaResultados . '/' . $xml)) {
            throw new Exception('XML inválido.');
        }

        $this->importacaoInconsistencia = $importacaoInconsistencia;
    }

    /**
     * @throws \BusinessException
     * @throws Exception
     */
    public function salvar()
    {
        $this->requisicaoLaboratorial = new RequisicaoLaboratorial($this->getCodigoRequisicao());

        if ($this->requisicaoLaboratorial->getCodigo() === null) {
            throw new Exception("Importação não concluída. Requisição {$this->getCodigoRequisicao()} não encontrada.");
        }

        $this->percorreExamesXml();
    }

    /**
     * Código da requisição = CodigoLis
     * A requisição é referente aos 10 últimos dígitos do CodigoLis
     * @return int
     */
    public function getCodigoRequisicao()
    {
        return (int)$this->xml->Paciente->CodigoLis;
    }

    /**
     * @throws \BusinessException
     * @throws \Exception
     */
    private function percorreExamesXml()
    {
        $exames = $this->xml->Paciente->Resultado->children();
        $totalExames = count($exames);

        for ($contador = 0; $contador < $totalExames; $contador++) {
            $exameAtual = $exames[$contador];
            $siglaExame = (string)$exameAtual->CodExame;

            $requisicaoExame = $this->getRequisicaoExameBySigla($siglaExame);

            if (!empty($requisicaoExame)) {
                $atributosValores = $this->resultadosAtributosXml($exameAtual);

                $this->atualizarResultados($requisicaoExame, $atributosValores);
            }
        }
    }

    /**
     * @param $siglaExame
     * @return RequisicaoExame|RequisicaoExame[]
     * @throws Exception
     */
    private function getRequisicaoExameBySigla($siglaExame)
    {
        $requisicoesExame = $this->requisicaoLaboratorial->getRequisicoesDeExames();
        $requisicaoExame = null;

        foreach ($requisicoesExame as $requisicao) {
            if ($requisicao->getExame()->getSigla() === $siglaExame) {
                $requisicaoExame = $requisicao;
            }
        }

        if (empty($requisicaoExame)) {
            throw new Exception("Nenhum exame encontrado para o resultado (Exame {$siglaExame})");
        }

        if (!$this->validaSituacaoExame($requisicaoExame)) {
            return null;
        }

        return $requisicaoExame;
    }

    /**
     * @throws Exception
     */
    private function validaSituacaoExame(RequisicaoExame $requisicaoExame)
    {
        if (!in_array($requisicaoExame->getSituacao(), $this->situacoesValidas)) {
            return false;
        }

        return true;
    }

    /**
     * @param $exameAtual
     * @return array
     */
    private function resultadosAtributosXml($exameAtual)
    {
        $totalResultados = count($exameAtual->Secao);
        $atributosValores = array();

        for ($contador = 0; $contador < $totalResultados; $contador++) {
            $secaoAtual = $exameAtual->Secao[$contador];
            $valor = str_replace(',', '.', str_replace('.', '', (string)$secaoAtual->Valor));

            $atributosValores[(string)$secaoAtual->Codigo] = $valor;
        }

        return $atributosValores;
    }

    /**
     * @param RequisicaoExame $requisicaoExame
     * @param $atributosValores
     * @throws \BusinessException
     */
    private function atualizarResultados(RequisicaoExame $requisicaoExame, $atributosValores)
    {
        $atributos = $requisicaoExame->getExame()->getAtributos();
        $resultado = $requisicaoExame->getResultado();
        $resultadosAtributos = $resultado->getResultadoDosAtributos();
        $resultadosAtributosAtualizar = array();

        foreach ($resultadosAtributos as $resultadoAtributo) {
            if (array_key_exists($resultadoAtributo->getAtributo()->getSigla(), $atributosValores)) {
                $resultadosAtributosAtualizar[$resultadoAtributo->getAtributo()->getSigla()] = $resultadoAtributo;
            }
        }
        $this->setArquivoComInconsistencias(false);
        foreach ($atributos as $atributoExame) {
            $atributoValorReferencia = $atributoExame->getValoresDeReferenciaParaExame($requisicaoExame);
            $siglaExame = $atributoExame->getSigla();
            $valor = isset($atributosValores[$siglaExame]) ? $atributosValores[$siglaExame] : '';
            
            if ($atributoExame->getTipo() == 2) {
                if (($valor == "" && $atributoExame->preenchimentoObrigatorio()) ||
                str_replace('.', '', $valor) == "" ||
                str_replace(',', '', $valor) == "" ||
                str_replace('"', '', $valor) == "") {
                    $this->importacaoInconsistencia->addInconsistencia(
                        $this->requisicaoLaboratorial,
                        $requisicaoExame,
                        $atributoExame
                    );
                    $this->setArquivoComInconsistencias(true);

                    continue;
                }
            }

            $resultadoExameAtributo = new ResultadoExameAtributo();
            $resultadoExameAtributo->setAtributo($atributoExame);

            if (array_key_exists($siglaExame, $resultadosAtributosAtualizar)) {
                $resultadoExameAtributo = $resultadosAtributosAtualizar[$siglaExame];
            }

            $resultadoExameAtributo->setValorAbsoluto($valor);

            if ($atributoValorReferencia instanceof \AtributoValorReferenciaNumerico
              && $atributoValorReferencia->getTipoCalculo() == 2) {
                $resultadoExameAtributo->setValorPercentual($valor);
                $resultadoExameAtributo->setValorAbsoluto('');
            }

            if ($atributoValorReferencia !== null && $atributoValorReferencia->getCodigo() !== null) {
                $resultadoExameAtributo->setFaixaUtilizada(
                    new AtributoValorReferenciaNumerico($atributoValorReferencia->getCodigo())
                );
            }

            $resultado->adicionarResultadoParaAtributo($resultadoExameAtributo, true);
        }

        $resultado->salvar();

        $requisicaoExame->setSituacao(RequisicaoExame::LANCADO);
        $requisicaoExame->salvar();
    }

    /**
     * @return array
     */
    public function getInconsistencias()
    {
        return $this->importacaoInconsistencia->getInconsistencias();
    }

    /**
     * @return array
     */
    public function getArquivoComInconsistencias()
    {
        return $this->importacaoInconsistenciaArquivo;
    }

    public function setArquivoComInconsistencias($inconsistenciasArquivo)
    {
        $this->importacaoInconsistenciaArquivo = $inconsistenciasArquivo;
    }
}
