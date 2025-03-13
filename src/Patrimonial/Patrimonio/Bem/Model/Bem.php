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

namespace ECidade\Patrimonial\Patrimonio\Bem\Model;

use DBDepartamento;
use Instituicao;
use DateTime;
use ECidade\Patrimonial\Patrimonio\Bem\Model\BemPlaca;
use ECidade\Patrimonial\Patrimonio\Bem\Repository\BemPlacaRepository;

class Bem
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $idClassificacaoBem;

    /**
     * @var integer
     */
    protected $numeroCgm;

    /**
     * @var double
     */
    protected $valorAquisicao;

    /**
     * @var DateTime
     */
    protected $dataAquisicao;

    /**
     * @var String
     */
    protected $identificacao;

    /**
     * @var String
     */
    protected $descricao;

    /**
     * @var String
     */
    protected $obsevacao;

    /**
     * @var DBDepartamento
     */
    protected $departamento;

    /**
     * @var Instituicao
     */
    protected $instituicao;

    /**
     * @var integer
     */
    protected $idMarca;

    /**
     * @var integer
     */
    protected $idModelo;

    /**
     * @var integer
     */
    protected $medida;

    /**
     * @var array BemPlaca
     */
    protected $bemPlacas;

    public function __construct($id = null)
    {
        if (!empty($id)) {
            $daoBens =  new \cl_bens();

            $sql = $daoBens->sql_query_file($id);
            $postgresResource = $daoBens->sql_record($sql);
            
            if (pg_num_rows($postgresResource) > 0) {
                $bem = pg_fetch_assoc($postgresResource);

                $this->setId($bem['t52_bem']);
                $this->setIdClassificacao($bem['t52_codcla']);
                $this->setNumeroCgm($bem['t52_numcgm']);
                $this->setValorAquisicao((double) $bem['t52_valaqu']);
                $this->setDataAquisicao(new DateTime($bem['t52_dtaqu']));
                $this->setIdentificacao($bem['t52_ident']);
                $this->setDescricao($bem['t52_descr']);
                $this->setObservacao($bem['t52_obs']);
                $this->setDepartamento(new DBDepartamento((int) $bem['t52_depart']));
                $this->setInstituicao(new Instituicao((int) $bem['t52_instit']));
                $this->setIdMarca((int) $bem['t52_bensmarca']);
                $this->setIdModelo((int) $bem['t52_bensmodelo']);
                $this->setIdMedida((int) $bem['t52_bensmedida']);
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getIdClassificacao()
    {
        return $this->idClassificacaoBem;
    }

    public function setIdClassificacao($idClassificacao)
    {
        $this->idClassificacaoBem = $idClassificacao;
    }

    public function getNumeroCgm()
    {
        return $this->idClassificacaoBem;
    }

    public function setNumeroCgm($numeroCgm)
    {
        $this->numeroCgm = $numeroCgm;
    }

    public function getValorAquisicao()
    {
        return $this->valorAquisicao;
    }

    public function setValorAquisicao($valorAquisicao)
    {
        $this->valorAquisicao = $valorAquisicao;
    }

    public function getDataAquisicao()
    {
        return $this->dataAquisicao;
    }

    public function setDataAquisicao($dataAquisicao)
    {
        $this->dataAquisicao = $dataAquisicao;
    }

    public function getIdentificacao()
    {
        return $this->identificacao;
    }

    public function setIdentificacao($identificacao)
    {
        $this->identificacao = $identificacao;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getObservacao()
    {
        return $this->observacao;
    }

    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }

    public function getDepartamento()
    {
        return $this->departamento;
    }

    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;
    }

    public function getInstituicao()
    {
        return $this->instituicao;
    }

    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    public function getIdMarca()
    {
        return $this->idMarca;
    }

    public function setIdMarca($idMarca)
    {
        $this->idMarca = $idMarca;
    }

    public function getIdModelo()
    {
        return $this->idModelo;
    }

    public function setIdModelo($idModelo)
    {
        $this->idModelo = $idModelo;
    }

    public function getIdMedida()
    {
        return $this->idMedida;
    }

    public function setIdMedida($idMedida)
    {
        $this->idMedida = $idMedida;
    }

    public function getbemPlacas()
    {
        return $this->bemPlacas;
    }

    public function setbemPlacas($bemPlacas)
    {
        $this->bemPlacas = $bemPlacas;
    }

    /**
     * @return self
     */
    public function withBemPlacas()
    {
        if (empty($this->bemPlacas)) {
            $repository = new BemPlacaRepository(new \cl_bensplaca());
            $bemPlacas = $repository->buscaBemPlacasPorIdBem($this->id, array('bensplaca.*'));
            $this->setBemPlacas($bemPlacas);
        }

        return $this;
    }

    public static function fromState(array $state)
    {
        $bem = new self();
        
        if (array_key_exists('t52_bem', $state)) {
            $bem->setId((int) $state['t52_bem']);
        }

        if (array_key_exists('t52_codcla', $state)) {
            $bem->setIdClassificacao((int) $state['t52_codcla']);
        }

        if (array_key_exists('t52_numcgm', $state)) {
            $bem->setIdClassificacao($state['t52_numcgm']);
        }

        if (array_key_exists('t52_valaqu', $state)) {
            $bem->setValorAquisicao((double) $state['t52_valaqu']);
        }

        if (array_key_exists('t52_dtaqu', $state)) {
            $bem->setDataAquisicao(new DateTime($state['t52_dtaqu']));
        }

        if (array_key_exists('t52_ident', $state)) {
            $bem->setIdentificacao($state['t52_ident']);
        }

        if (array_key_exists('t52_descr', $state)) {
            $bem->setDescricao($state['t52_descr']);
        }

        if (array_key_exists('t52_obs', $state)) {
            $bem->setDescricao($state['t52_obs']);
        }

        if (array_key_exists('t52_depart', $state)) {
            $bem->setDepartamento(new DBDepartamento((int) $state['t52_depart']));
        }

        if (array_key_exists('t52_instit', $state)) {
            $bem->setInstituicao(new Instituicao((int) $state['t52_instit']));
        }

        if (array_key_exists('t52_bensmarca', $state)) {
            $bem->setIdMarca((int) $state['t52_bensmarca']);
        }

        if (array_key_exists('t52_bensmodelo', $state)) {
            $bem->setIdModelo((int) $state['t52_bensmodelo']);
        }

        if (array_key_exists('t52_bensmedida', $state)) {
            $bem->setIdMedida((int) $state['t52_bensmedida']);
        }
        
        return $bem;
    }

    public function toArray()
    {
        $placasAux = $this->getBemPlacas();
        $placas = array();
        
        foreach ($placasAux as $placa) {
            $placas[] = $placa->toArray();
        }

        return [
            't52_bem' => $this->getId(),
            't52_codcla' => $this->getIdClassificacao(),
            't52_numcgm' => $this->getNumeroCgm(),
            't52_valaqu' => $this->getValorAquisicao(),
            't52_dtaqui' => !is_null($this->getDataAquisicao()) ? $this->getDataAquisicao()->format('Y-m-d') : null,
            't52_ident' => $this->getIdentificacao(),
            't52_descr' => $this->getDescricao(),
            't52_obs' => $this->getObservacao(),
            't52_depart' => $this->getDepartamento() ? $this->getDepartamento()->getCodigo() : 0,
            't52_instit' => $this->getInstituicao() ? $this->getInstituicao()->getCodigo() : 0,
            't52_bensmarca' => $this->getIdMarca(),
            't52_bensmodelo' => $this->getIdModelo(),
            't52_bensmedida' => $this->getIdMedida(),
            'placas' => $placas
        ];
    }
}
