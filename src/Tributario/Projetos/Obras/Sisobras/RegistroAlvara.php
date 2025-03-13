<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Projetos\Obras\Sisobras;

class RegistroAlvara
{

  /**
   * @var string
   */
    private $Id;

  /**
   * @var float
   */
    private $numeroAlvara;

  /**
   * @var integer
   */
    private $numeroProtocoloAnterior;

  /**
   * @var string
   */
    private $nomeObra;

  /**
   * @var string
   */
    private $dataAlvara;

  /**
   * @var string
   */
    private $dataInicioObra;

  /**
   * @var string
   */
    private $dataFinalObra;

  /**
   * @var string
   */
    private $tipoAlvara;

  /**
   * @var string
   */
    private $proprietario_do_imovel;

  /**
   * @var string
   */
    private $dono_da_obra_cnpj;

  /**
   * @var string
   */
    private $dono_da_obra_cpf;

  /**
   * @var string
   */
    private $incorporador_construcao_civil_cnpj;

  /**
   * @var string
   */
    private $incorporador_construcao_civil_cpf;

  /**
   * @var string
   */
    private $empresa_construtora_cnpj;

  /**
   * @var string
   */
    private $cnpjConsorcio;

  /**
   * @var string
   */
    private $cnpjEmpresaLiderConsorcio;

  /**
   * @var string
   */
    private $cpfResponsavelPrincipal;
  
  /**
   * @var string
   */
    private $cnpjResponsavelPrincipal;
  
  /**
   * @var array
   */
    private $construcao_nome_coletivo_cnpj = [];
  
  /**
   * @var array
   */
    private $construcao_nome_coletivo_cpf = [];

  /**
   * @var string
   */
    private $cep;

  /**
   * @var string
   */
    private $tipoLogradouro;
  
  /**
   * @var string
   */
    private $logradouro;
  
  /**
   * @var string
   */
    private $numero;
  
  /**
   * @var string
   */
    private $complemento;
  
  /**
   * @var string
   */
    private $bairro;

  /**
   * @var string
   */
    private $unidadeMedida;
  
  /**
   * @var string
   */
    private $valorUnidadeMedida;
  
  /**
   * @var string
   */
    private $proprietarioObraCpf;

  /**
   * @var string
   */
    private $proprietarioObraCnpj;

  /**
   * @var string
   */
    private $situacao;

  /**
   * @var string
   */
    private $classe;

  /**
   * @var string
   */
    private $numeroProcesso;

  /**
   * @var string
   */
    private $engenheiroNomeTecnico;

  /**
   * @var string
   */
    private $engenheiroCreaTecnico;
    
  /**
   * @var string
   */
    private $engenheiroArtTecnico;

  /**
   * @var string
   */
    private $arquitetoNomeTecnico;

  /**
   * @var string
   */
    private $arquitetoCauTecnico;

  /**
   * @var string
   */
    private $arquitetoRrtTecnico;

/**
   * @var string
   */
    private $engenheiroNomeProjeto;

  /**
   * @var string
   */
    private $engenheiroCreaProjeto;
    
  /**
   * @var string
   */
    private $engenheiroArtProjeto;

  /**
   * @var string
   */
    private $arquitetoNomeProjeto;

  /**
   * @var string
   */
    private $arquitetoCauProjeto;

  /**
   * @var string
   */
    private $arquitetoRrtProjeto;


  /**
   * @var string
   */
    private $especificacao;

  /**
   * @var string
   */
    private $observacao;

  /**
   * @return string
   */
    public function getId()
    {
        return $this->Id;
    }

  /**
   * @param string $Id
   */
    public function setId($Id)
    {
        $this->Id = $Id;
    }

  /**
   * @return integer
   */
    public function getNumeroAlvara()
    {
        return $this->numeroAlvara;
    }

  /**
   * @param integer $numeroAlvara
   */
    public function setNumeroAlvara($numeroAlvara)
    {
        $this->numeroAlvara = $numeroAlvara;
    }

  /**
   * @return integer
   */
    public function getNumeroProtocoloAnterior()
    {
        return $this->numeroProtocoloAnterior;
    }

  /**
   * @param integer $numeroProtocoloAnterior
   */
    public function setNumeroProtocoloAnterior($numeroProtocoloAnterior)
    {
        $this->numeroProtocoloAnterior = $numeroProtocoloAnterior;
    }

  /**
   * @return string
   */
    public function getNomeObra()
    {
        return $this->nomeObra;
    }

  /**
   * @param string $nomeObra
   */
    public function setNomeObra($nomeObra)
    {
        $this->nomeObra = $nomeObra;
    }

  /**
   * @return string
   */
    public function getDataAlvara()
    {
        return $this->dataAlvara;
    }

  /**
   * @param string $dataAlvara
   */
    public function setDataAlvara($dataAlvara)
    {
        $this->dataAlvara = $dataAlvara;
    }

  /**
   * @return string
   */
    public function getDataInicioObra()
    {
        return $this->dataInicioObra;
    }

  /**
   * @param string $dataInicioObra
   */
    public function setDataInicioObra($dataInicioObra)
    {
        $this->dataInicioObra = $dataInicioObra;
    }

  /**
   * @return string
   */
    public function getDataFinalObra()
    {
        return $this->dataFinalObra;
    }

  /**
   * @param string $dataFinalObra
   */
    public function setDataFinalObra($dataFinalObra)
    {
        $this->dataFinalObra = $dataFinalObra;
    }

  /**
   * @return string
   */
    public function getTipoAlvara()
    {
        return $this->tipoAlvara;
    }

  /**
   * @param string $tipoAlvara
   */
    public function setTipoAlvara($tipoAlvara)
    {
        $this->tipoAlvara = $tipoAlvara;
    }

  /**
   * @return string
   */
    public function getProprietarioDoImovel()
    {
        return $this->proprietario_do_imovel;
    }

  /**
   * @param string $proprietario_do_imovel
   */
    public function setProprietarioDoImovel($proprietario_do_imovel)
    {
        $this->proprietario_do_imovel = $proprietario_do_imovel;
    }

  /**
   * @return string
   */
    public function getDonoDaObraCnpj()
    {
        return $this->dono_da_obra_cnpj;
    }

  /**
   * @param string $dono_da_obra_cnpj
   */
    public function setDonoDaObraCnpj($dono_da_obra_cnpj)
    {
        $this->dono_da_obra_cnpj = $dono_da_obra_cnpj;
    }

  /**
   * @return string
   */
    public function getDonoDaObraCpf()
    {
        return $this->dono_da_obra_cpf;
    }

  /**
   * @param string $dono_da_obra_cpf
   */
    public function setDonoDaObraCpf($dono_da_obra_cpf)
    {
        $this->dono_da_obra_cpf = $dono_da_obra_cpf;
    }

  /**
   * @return string
   */
    public function getIncorporadorConstrucaoCivilCnpj()
    {
        return $this->incorporador_construcao_civil_cnpj;
    }

  /**
   * @param string $incorporador_construcao_civil_cnpj
   */
    public function setIncorporadorConstrucaoCivilCnpj($incorporador_construcao_civil_cnpj)
    {
        $this->incorporador_construcao_civil_cnpj = $incorporador_construcao_civil_cnpj;
    }

  /**
   * @return string
   */
    public function getIncorporadorConstrucaoCivilCpf()
    {
        return $this->incorporador_construcao_civil_cpf;
    }

  /**
   * @param string $incorporador_construcao_civil_cpf
   */
    public function setIncorporadorConstrucaoCivilCpf($incorporador_construcao_civil_cpf)
    {
        $this->incorporador_construcao_civil_cpf = $incorporador_construcao_civil_cpf;
    }

  /**
   * @return string
   */
    public function getEmpresaConstrutoraCnpj()
    {
        return $this->empresa_construtora_cnpj;
    }

  /**
   * @param string $empresa_construtora_cnpj
   */
    public function setEmpresaConstrutoraCnpj($empresa_construtora_cnpj)
    {
        $this->empresa_construtora_cnpj = $empresa_construtora_cnpj;
    }

  /**
   * @return string
   */
    public function getCnpjConsorcio()
    {
        return $this->cnpjConsorcio;
    }

  /**
   * @param string $cnpjConsorcio
   */
    public function setCnpjConsorcio($cnpjConsorcio)
    {
        $this->cnpjConsorcio = $cnpjConsorcio;
    }

  /**
   * @return string
   */
    public function getCnpjEmpresaLiderConsorcio()
    {
        return $this->cnpjEmpresaLiderConsorcio;
    }

  /**
   * @param string $cnpjEmpresaLiderConsorcio
   */
    public function setCnpjEmpresaLiderConsorcio($cnpjEmpresaLiderConsorcio)
    {
        $this->cnpjEmpresaLiderConsorcio = $cnpjEmpresaLiderConsorcio;
    }

  /**
   * @return string
   */
    public function getCpfResponsavelPrincipal()
    {
        return $this->cpfResponsavelPrincipal;
    }

  /**
   * @param string $cpfResponsavelPrincipal
   */
    public function setCpfResponsavelPrincipal($cpfResponsavelPrincipal)
    {
        $this->cpfResponsavelPrincipal = $cpfResponsavelPrincipal;
    }

  /**
   * @return string
   */
    public function getCnpjResponsavelPrincipal()
    {
        return $this->cnpjResponsavelPrincipal;
    }

  /**
   * @param string $cnpjResponsavelPrincipal
   */
    public function setCnpjResponsavelPrincipal($cnpjResponsavelPrincipal)
    {
        $this->cnpjResponsavelPrincipal = $cnpjResponsavelPrincipal;
    }

  /**
   * @return array
   */
    public function getConstrucaoNomeColetivoCnpj()
    {
        return $this->construcao_nome_coletivo_cnpj;
    }

  /**
   * @param string $construcao_nome_coletivo_cnpj
   */
    public function addConstrucaoNomeColetivoCnpj($construcao_nome_coletivo_cnpj)
    {
        $this->construcao_nome_coletivo_cnpj[] = $construcao_nome_coletivo_cnpj;
    }

  /**
   * @return array
   */
    public function getConstrucaoNomeColetivoCpf()
    {
        return $this->construcao_nome_coletivo_cpf;
    }

  /**
   * @param string $construcao_nome_coletivo_cpf
   */
    public function addConstrucaoNomeColetivoCpf($construcao_nome_coletivo_cpf)
    {
        $this->construcao_nome_coletivo_cpf[] = $construcao_nome_coletivo_cpf;
    }

  /**
   * @return string
   */
    public function getCep()
    {
        return $this->cep;
    }

  /**
   * @param string $cep
   */
    public function setCep($cep)
    {
        $this->cep = $cep;
    }

  /**
   * @return string
   */
    public function getTipoLogradouro()
    {
        return $this->tipoLogradouro;
    }

  /**
   * @param string $tipoLogradouro
   */
    public function setTipoLogradouro($tipoLogradouro)
    {
        $this->tipoLogradouro = $tipoLogradouro;
    }

  /**
   * @return string
   */
    public function getLogradouro()
    {
        return $this->logradouro;
    }

  /**
   * @param string $logradouro
   */
    public function setLogradouro($logradouro)
    {
        $this->logradouro = $logradouro;
    }

  /**
   * @return string
   */
    public function getNumero()
    {
        return $this->numero;
    }

  /**
   * @param string $numero
   */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

  /**
   * @return string
   */
    public function getComplemento()
    {
        return $this->complemento;
    }

  /**
   * @param string $complemento
   */
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
    }

  /**
   * @return string
   */
    public function getBairro()
    {
        return $this->bairro;
    }

  /**
   * @param string $bairro
   */
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
    }

  /**
   * @return string
   */
    public function getUnidadeMedida()
    {
        return $this->unidadeMedida;
    }

  /**
   * @param string $unidadeMedida
   */
    public function setUnidadeMedida($unidadeMedida)
    {
        $this->unidadeMedida = $unidadeMedida;
    }

  /**
   * @return string
   */
    public function getValorUnidadeMedida()
    {
        return $this->valorUnidadeMedida;
    }

  /**
   * @param string $valorUnidadeMedida
   */
    public function setValorUnidadeMedida($valorUnidadeMedida)
    {
        $this->valorUnidadeMedida = $valorUnidadeMedida;
    }

  /**
   * @return string
   */
    public function getProprietarioObraCpf()
    {
        return $this->proprietarioObraCpf;
    }

  /**
   * @param string $proprietarioObraCpf
   */
    public function setProprietarioObraCpf($proprietarioObraCpf)
    {
        $this->proprietarioObraCpf = $proprietarioObraCpf;
    }

  /**
   * @return string
   */
    public function getProprietarioObraCnpj()
    {
        return $this->proprietarioObraCnpj;
    }

  /**
   * @param string $proprietarioObraCnpj
   */
    public function setProprietarioObraCnpj($proprietarioObraCnpj)
    {
        $this->proprietarioObraCnpj = $proprietarioObraCnpj;
    }

  /**
   * @return string
   */
    public function getSituacao()
    {
        return $this->situacao;
    }

  /**
   * @param string $situacao
   */
    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
    }

  /**
   * @return string
   */
    public function getClasse()
    {
        return $this->classe;
    }

  /**
   * @param string $classe
   */
    public function setClasse($classe)
    {
        $this->classe = $classe;
    }

  /**
   * @return string
   */
    public function getNumeroProcesso()
    {
        return $this->numeroProcesso;
    }

  /**
   * @param string $numeroProcesso
   */
    public function setNumeroProcesso($numeroProcesso)
    {
        $this->numeroProcesso = $numeroProcesso;
    }

  /**
   * @return string
   */
    public function getEngenheiroNomeTecnico()
    {
        return $this->engenheiroNomeTecnico;
    }

  /**
   * @param string $engenheiroNomeTecnico
   */
    public function setEngenheiroNomeTecnico($engenheiroNomeTecnico)
    {
        $this->engenheiroNomeTecnico = $engenheiroNomeTecnico;
    }

  /**
   * @return string
   */
    public function getEngenheiroCreaTecnico()
    {
        return $this->engenheiroCreaTecnico;
    }

  /**
   * @param string $engenheiroCreaTecnico
   */
    public function setEngenheiroCreaTecnico($engenheiroCreaTecnico)
    {
        $this->engenheiroCreaTecnico = $engenheiroCreaTecnico;
    }

  /**
   * @return string
   */
    public function getEngenheiroArtTecnico()
    {
        return $this->engenheiroArtTecnico;
    }

  /**
   * @param string $engenheiroArtTecnico
   */
    public function setEngenheiroArtTecnico($engenheiroArtTecnico)
    {
        $this->engenheiroArtTecnico = $engenheiroArtTecnico;
    }

  /**
   * @return string
   */
    public function getArquitetoNomeTecnico()
    {
        return $this->arquitetoNomeTecnico;
    }

  /**
   * @param string $arquitetoNomeTecnico
   */
    public function setArquitetoNomeTecnico($arquitetoNomeTecnico)
    {
        $this->arquitetoNomeTecnico = $arquitetoNomeTecnico;
    }

  /**
   * @return string
   */
    public function getArquitetoCauTecnico()
    {
        return $this->arquitetoCauTecnico;
    }

  /**
   * @param string $arquitetoCauTecnico
   */
    public function setArquitetoCauTecnico($arquitetoCauTecnico)
    {
        $this->arquitetoCauTecnico = $arquitetoCauTecnico;
    }

  /**
   * @return string
   */
    public function getArquitetoRrtTecnico()
    {
        return $this->arquitetoRrtTecnico;
    }

  /**
   * @param string $arquitetoRrtTecnico
   */
    public function setArquitetoRrtTecnico($arquitetoRrtTecnico)
    {
        $this->arquitetoRrtTecnico = $arquitetoRrtTecnico;
    }

    /**
     * @return string
     */
    public function getEngenheiroNomeProjeto()
    {
        return $this->engenheiroNomeProjeto;
    }

  /**
   * @param string $engenheiroNomeProjeto
   */
    public function setEngenheiroNomeProjeto($engenheiroNomeProjeto)
    {
        $this->engenheiroNomeProjeto = $engenheiroNomeProjeto;
    }

  /**
   * @return string
   */
    public function getEngenheiroCreaProjeto()
    {
        return $this->engenheiroCreaProjeto;
    }

  /**
   * @param string $engenheiroCreaProjeto
   */
    public function setEngenheiroCreaProjeto($engenheiroCreaProjeto)
    {
        $this->engenheiroCreaProjeto = $engenheiroCreaProjeto;
    }

  /**
   * @return string
   */
    public function getEngenheiroArtProjeto()
    {
        return $this->engenheiroArtProjeto;
    }

  /**
   * @param string $engenheiroArtProjeto
   */
    public function setEngenheiroArtProjeto($engenheiroArtProjeto)
    {
        $this->engenheiroArtProjeto = $engenheiroArtProjeto;
    }

  /**
   * @return string
   */
    public function getArquitetoNomeProjeto()
    {
        return $this->arquitetoNomeProjeto;
    }

  /**
   * @param string $arquitetoNomeProjeto
   */
    public function setArquitetoNomeProjeto($arquitetoNomeProjeto)
    {
        $this->arquitetoNomeProjeto = $arquitetoNomeProjeto;
    }

  /**
   * @return string
   */
    public function getArquitetoCauProjeto()
    {
        return $this->arquitetoCauProjeto;
    }

  /**
   * @param string $arquitetoCauProjeto
   */
    public function setArquitetoCauProjeto($arquitetoCauProjeto)
    {
        $this->arquitetoCauProjeto = $arquitetoCauProjeto;
    }

  /**
   * @return string
   */
    public function getArquitetoRrtProjeto()
    {
        return $this->arquitetoRrtProjeto;
    }

  /**
   * @param string $arquitetoRrtProjeto
   */
    public function setArquitetoRrtProjeto($arquitetoRrtProjeto)
    {
        $this->arquitetoRrtProjeto = $arquitetoRrtProjeto;
    }

  /**
   * @return string
   */
    public function getEspecificacao()
    {
        return $this->especificacao;
    }

  /**
   * @param string $especificacao
   */
    public function setEspecificacao($especificacao)
    {
        $this->especificacao = $especificacao;
    }

  /**
   * @return string
   */
    public function getObservacao()
    {
        return $this->observacao;
    }

  /**
   * @param string $observacao
   */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }
}
