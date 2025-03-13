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

use Exception;
use RequisicaoLaboratorial;

/**
 * Class Pedido
 * @package ECidade\Saude\Laboratorio\Integracao\Luckmann\Service
 */
class Pedido
{
    /**
     * @var RequisicaoLaboratorial
     */
    private $requisicaoLaboratorial;

    /**
     * @var array
     */
    private $dados = array(
      'CodigoLis'      => '',
      'Nome'           => '',
      'Sexo'           => '',
      'CodPac'         => '',
      'Nascimento'     => '',
      'Idade'          => '',
      'Medico'         => '',
      'Data'           => '',
      'Hora'           => '',
      'Unidade'        => '',
      'OrigemMaterial' => '',
      'DadosClinicos'  => '',
      'Exames'         => array(),
    );

    /**
     * @var array
     */
    private $itensRequisicao;

    /**
     * Pedido constructor.
     * @param RequisicaoLaboratorial $requisicaoLaboratorial
     */
    public function __construct(RequisicaoLaboratorial $requisicaoLaboratorial)
    {
        $this->requisicaoLaboratorial = $requisicaoLaboratorial;
    }

    /**
     * @param array $itensRequisicao
     * @return array
     * @throws \DBException
     * @throws Exception
     */
    public function getDados(array $itensRequisicao)
    {
        if (empty($itensRequisicao)) {
            throw new Exception('Nenhum exame informado.');
        }

        $this->itensRequisicao = $itensRequisicao;

        $this->getDadosRequisicao();
        $this->getExames();

        return $this->dados;
    }

    /**
     * @throws \DBException
     * @throws \Exception
     */
    private function getDadosRequisicao()
    {
        $cgs = $this->requisicaoLaboratorial->getCgs();
        $requisicao = $this->requisicaoLaboratorial;

        $dadosClinicos = array();

        if ($requisicao->getMedicamento() !== '') {
            $dadosClinicos[] = $requisicao->getMedicamento();
        }

        if ($requisicao->getDiagnostico() !== '') {
            $dadosClinicos[] = $requisicao->getDiagnostico();
        }

        if ($requisicao->getObservacao() !== '') {
            $dadosClinicos[] = $requisicao->getObservacao();
        }

        $this->dados['CodigoLis'] = $requisicao->getCodigo();
        $this->dados['Nome'] = $cgs->getNome();
        $this->dados['Sexo'] = $cgs->getSexo();
        $this->dados['CodPac'] = $cgs->getCodigo();
        $this->dados['Nascimento'] = $cgs->getDataNascimento()->getDate(\DBDate::DATA_PTBR);
        $this->dados['Idade'] = $cgs->getIdade() . 'A';
        $this->dados['Medico'] = $requisicao->getMedico();
        $this->dados['Data'] = $requisicao->getData()->getDate(\DBDate::DATA_PTBR);
        $this->dados['Hora'] = $requisicao->getHora();
        $this->dados['Unidade'] = $requisicao->getDepartamento()->getNomeDepartamento();
        $this->dados['OrigemMaterial'] = $requisicao->getLaboratorio()->getDescricao();
        $this->dados['DadosClinicos'] = implode(' / ', $dadosClinicos);
    }

    /**
     * @throws \Exception
     */
    private function getExames()
    {
        $requisicoesExame = $this->requisicaoLaboratorial->getRequisicoesDeExames();
        $amostrasMaterial = array();

        foreach ($requisicoesExame as $requisicaoExame) {
            if (!in_array($requisicaoExame->getCodigo(), $this->itensRequisicao)) {
                continue;
            }

            $setor = $requisicaoExame->getLaboratorioSetor()->getCodigo();
            $materiasColeta = $requisicaoExame->getExame()->getMaterialColeta();

            if (count($materiasColeta) === 0) {
                $mensagem = "Ação não permitida. Exame sem material de coleta cadastrado";
                $mensagem .= " ({$requisicaoExame->getExame()->getNome()}). Acesse:";
                $mensagem .= " SAÚDE > Laboratório > Cadastros > Exame > Alteração > aba Material de coleta";

                throw new \Exception($mensagem);
            }

            foreach ($materiasColeta as $material) {
                $chaveCodigoColetaSetor = $material->codigo_material_coleta . "#" . $setor;

                if (!array_key_exists($chaveCodigoColetaSetor, $amostrasMaterial)) {
                    $amostrasMaterial[$chaveCodigoColetaSetor] = $requisicaoExame->getNumeroCodigoBarras(
                        $material->codigo_material_coleta,
                        $setor
                    );
                }

                $numeroAmostra = $amostrasMaterial[$chaveCodigoColetaSetor];

                $exame = array(
                  'Codigo'   => $requisicaoExame->getExame()->getSigla(),
                  'Amostra'  => $numeroAmostra,
                  'Material' => $material->material_coleta
                );

                $this->dados['Exames'][] = $exame;
            }
        }
        if (empty($this->dados['Exames'])) {
            throw new Exception('Nenhum exame informado.');
        }
    }
}
