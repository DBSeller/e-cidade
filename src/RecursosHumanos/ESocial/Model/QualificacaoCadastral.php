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

namespace ECidade\RecursosHumanos\ESocial\Model;

/**
 * Class QualificacaoCadastral
 * @package ECidade\RecursosHumanos\ESocial\Model
 */
class QualificacaoCadastral
{
    /**
     * @var string
     */
    private $cpf;

    /**
     * @var string
     */
    private $nis;

    /**
     * @var string
     */
    private $nome;

    /**
     * @var \DBDate
     */
    private $dataNascimento;

    /**
     * @var integer
     */
    private $matricula;

    /**
     * @var string
     */
    private $descricaoLotacao;

    /**
     * @var array
     */
    private $inconsistencias = array();

    /**
     * @return string
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * @param string $cpf
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    /**
     * @return string
     */
    public function getNIS()
    {
        return $this->nis;
    }

    /**
     * @param string $nis
     */
    public function setNIS($nis)
    {
        $this->nis = $nis;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return \DBDate
     */
    public function getDataNascimento()
    {
        return $this->dataNascimento;
    }

    /**
     * @param \DBDate $dataNascimento
     */
    public function setDataNascimento($dataNascimento)
    {
        $this->dataNascimento = $dataNascimento;
    }

    /**
     * Retorna a matrícula do servidor
     * @return integer
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * Define a matricula do servidor
     * @param integer $matricula 
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    public function getDescricaoLotacao()
    {
        return $this->descricaoLotacao;
    }

    /**
     * Define a descrição da lotação
     * @param string $descricaoLotacao 
     */
    public function setDescricaoLotacao($descricaoLotacao)
    {
        $this->descricaoLotacao = $descricaoLotacao;
    }

    public function getInconsistencias()
    {
        return $this->inconsistencias;
    }

    /**
     * Adiciona uma inconsistência
     * @param integer $inconsistencia 
     */
    public function addInconsistencias($inconsistencia) 
    {
        $this->inconsistencias[] = $inconsistencia;
    }

    /**
     * Adiciona uma coleção de inconsistências
     * @param array $inconsistencias 
     */
    public function setInconsistencias($inconsistencias) 
    {
        $this->inconsistencias = $inconsistencias;
        $this->mapearInconsistencias();
    }

    /**
     * Mapea as inconsistencia do array de acordo com o código.
     */
    private function mapearInconsistencias()
    {
        foreach ($this->inconsistencias as $index => $descricao) {
            $this->inconsistencias[$index] = $this->mapIconsistencia($index, $descricao);
        }
    }

    /**
     * Retorna a descrição da inconsistência de acordo com o seu código
     * @param integer $codigo 
     * @return string
     */
    private function mapIconsistencia($codigo, $descricao = null)
    {
        $erroOrientacaoNIS  = "Verifique os dados digitados.";
        $erroOrientacaoNIS .= " Se estiverem corretos, antes de realizar a atualização cadastral do PIS ou PASEP, é necessário verificar o vínculo empregatício atual:";
        $erroOrientacaoNIS .= "\n  1- se vinculado à empresa privada, a atualização cadastral deve ser solicitada na CAIXA.";
        $erroOrientacaoNIS .= "\n  2- se vinculado a órgão público, a atualização cadastral deve ser solicitada no Banco do Brasil.";
        $erroOrientacaoNIS .= "\nObs: a atualização cadastral poderá ser realizada pelo interessado ou pela empresa/órgão público¹.";

        $descricoesInconsistencia = array(
            -1 => $erroOrientacaoNIS,
            5 => "NIS inválido.",
            6 => "CPF inválido.",
            7 => "NOME inválido.",
            8 => "Data de Nascimento inválida.",
            9 => "NIS inconsistente.",
            10 => "Data de Nascimento informada diverge da existente no CNIS.",
            11 => "NIS com óbito no CNIS.",
            12 => "CPF informado diverge do existente no CNIS.",
            13 => "CPF não preenchido no CNIS.",
            14 => "CPF informado não consta no Cadastro CPF.",
            15 => "CPF informado NULO no Cadastro CPF.",
            16 => "CPF informado CANCELADO no Cadastro CPF.",
            17 => "CPF informado SUSPENSO no Cadastro CPF.",
            18 => "Data de Nascimento informada diverge da existente no Cadastro CPF.",
            19 => "NOME informado diverge do existente no Cadastro CPF. - {$descricao}",
            20 => "Procurar Conveniadas da RFB¹.",
            21 => " Atualizar NIS no INSS².",
            1005 => "CPF inconsistente.",
            1006 => "NIS inconsistente.",
            1007 => "NOME inconsistente.",
            1008 => "Data de Nascimento inconsistente.",
            1009 => "Separador inválido.",
            1010 => "Formatação inválida."
        );

        if (!array_key_exists($codigo, $descricoesInconsistencia)) {
            throw new \Exception("Código da inconsistência informada não encontrado.");
        }

        return $descricoesInconsistencia[$codigo];
    }
}
