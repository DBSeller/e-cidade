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

namespace ECidade\Tributario\Divida;

use ECidade\Tributario\Divida\Repository\ProcedenciaDividaRepository;

/**
 * Entidade que modela uma dívida do banco de dados.
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class Divida
{
    /**
     * @var integer coddiv
     */
    private $codigoDivida;

    /**
     * @var integer numcgm
     */
    private $cgm;

    /**
     * @var \DateTime dtinsc
     */
    private $dataIncricao;

    /**
     * @var integer exerc
     */
    private $exercicio;

    /**
     * @var integer numpre
     */
    private $numpre;

    /**
     * @var integer numpar
     */
    private $numpar;

    /**
     * @var integer numtot
     */
    private $numtot;

    /**
     * @var float vlrhis
     */
    private $valorHistorico;

    /**
     * @var Procedencia proced
     */
    private $procedencia;

    /**
     * @var integer livro
     */
    private $livro;

    /**
     * @var integer folha
     */
    private $folha;

    /**
     * @var \DateTime dtvenc
     */
    private $dataVencimento;

    /**
     * @var \DateTime dtoper
     */
    private $dataOperacao;

    /**
     * @var float valor
     */
    private $valor;

    /**
     * @var string obs
     */
    private $observacao;

    /**
     * @var integer numdig
     */
    private $numdig;

    /**
     * @var integer instit
     */
    private $instituicao;

    /**
     * @var \DateTime dtinclusao
     */
    private $dataInclusao;

    /**
     * @var string processo
     */
    private $processo;

    /**
     * @var \DateTime dtprocesso
     */
    private $dataProcesso;

    /**
     * @var string titular
     */
    private $titular;

    /**
     * Cria uma divida a partir de um array com seus dados
     *
     * @param  array $state
     * @return Divida
     */
    public static function fromState($state)
    {
        $divida = new self();
        
        if (array_key_exists('v01_coddiv', $state)) {
            $divida->setCodigoDivida($state['v01_coddiv']);
        }
        if (array_key_exists('v01_numcgm', $state)) {
            $divida->setCgm($state['v01_numcgm']);
        }
        if (array_key_exists('v01_dtinsc', $state) && !empty($state['v01_dtinsc'])) {
            $divida->setDataInclusao(new \DateTime($state['v01_dtinsc']));
        }
        if (array_key_exists('v01_exerc', $state)) {
            $divida->setExercicio($state['v01_exerc']);
        }
        if (array_key_exists('v01_numpre', $state)) {
            $divida->setNumpre($state['v01_numpre']);
        }
        if (array_key_exists('v01_numpar', $state)) {
            $divida->setNumpar($state['v01_numpar']);
        }
        if (array_key_exists('v01_numtot', $state)) {
            $divida->setNumtot($state['v01_numtot']);
        }
        if (array_key_exists('v01_vlrhis', $state)) {
            $divida->setValorHistorico($state['v01_vlrhis']);
        }
        if (array_key_exists('v01_proced', $state)) {
            $procedenciaRepository = ProcedenciaDividaRepository::getInstance();
            $procedencia = $procedenciaRepository->find($state['v01_proced']);
            $divida->setProcedencia($procedencia);
        }
        if (array_key_exists('v01_livro', $state)) {
            $divida->setLivro($state['v01_livro']);
        }
        if (array_key_exists('v01_folha', $state)) {
            $divida->setFolha($state['v01_folha']);
        }
        if (array_key_exists('v01_dtvenc', $state) && !empty($state['v01_dtvenc'])) {
            $divida->setDataVencimento(new \DateTime($state['v01_dtvenc']));
        }
        if (array_key_exists('v01_dtoper', $state) && !empty($state['v01_dtoper'])) {
            $divida->setDataOperacao(new \DateTime($state['v01_dtoper']));
        }
        if (array_key_exists('v01_valor', $state)) {
            $divida->setValor($state['v01_valor']);
        }
        if (array_key_exists('v01_obs', $state)) {
            $divida->setObservacao($state['v01_obs']);
        }
        if (array_key_exists('v01_numdig', $state)) {
            $divida->setNumdig($state['v01_numdig']);
        }
        if (array_key_exists('v01_instit', $state)) {
            $divida->setInstituicao($state['v01_instit']);
        }
        if (array_key_exists('v01_dtinclusao', $state) && !empty($state['v01_dtinclusao'])) {
            $divida->setDataInclusao(new \DateTime($state['v01_dtinclusao']));
        }
        if (array_key_exists('v01_processo', $state)) {
            $divida->setProcesso($state['v01_processo']);
        }
        if (array_key_exists('v01_dtprocesso', $state) && !empty($state['v01_dtprocesso'])) {
            $divida->setDataProcesso(new \DateTime($state['v01_dtprocesso']));
        }
        if (array_key_exists('v01_titular', $state)) {
            $divida->setTitular($state['v01_titular']);
        }

        return $divida;
    }

    /**
     * @return int
     */
    public function getCodigoDivida()
    {
        return $this->codigoDivida;
    }

    /**
     * @param  int $codigoDivida
     * @return Divida
     */
    public function setCodigoDivida($codigoDivida)
    {
        $this->codigoDivida = $codigoDivida;
        return $this;
    }

    /**
     * @return int
     */
    public function getCgm()
    {
        return $this->cgm;
    }

    /**
     * @param  int $cgm
     * @return Divida
     */
    public function setCgm($cgm)
    {
        $this->cgm = $cgm;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDataIncricao()
    {
        return $this->dataIncricao;
    }

    /**
     * @param  \DateTime $dataIncricao
     * @return Divida
     */
    public function setDataIncricao($dataIncricao)
    {
        $this->dataIncricao = $dataIncricao;
        return $this;
    }

    /**
     * @return int
     */
    public function getExercicio()
    {
        return $this->exercicio;
    }

    /**
     * @param  int $exercicio
     * @return Divida
     */
    public function setExercicio($exercicio)
    {
        $this->exercicio = $exercicio;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumpre()
    {
        return $this->numpre;
    }

    /**
     * @param  int $numpre
     * @return Divida
     */
    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumpar()
    {
        return $this->numpar;
    }

    /**
     * @param  int $numpar
     * @return Divida
     */
    public function setNumpar($numpar)
    {
        $this->numpar = $numpar;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumtot()
    {
        return $this->numtot;
    }

    /**
     * @param  int $numtot
     * @return Divida
     */
    public function setNumtot($numtot)
    {
        $this->numtot = $numtot;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorHistorico()
    {
        return $this->valorHistorico;
    }

    /**
     * @param  float $valorHistorico
     * @return Divida
     */
    public function setValorHistorico($valorHistorico)
    {
        $this->valorHistorico = $valorHistorico;
        return $this;
    }

    /**
     * @return Procedencia
     */
    public function getProcedencia()
    {
        return $this->procedencia;
    }

    /**
     * @param  Procedencia $procedencia
     * @return Divida
     */
    public function setProcedencia(Procedencia $procedencia)
    {
        $this->procedencia = $procedencia;
        return $this;
    }

    /**
     * @return int
     */
    public function getLivro()
    {
        return $this->livro;
    }

    /**
     * @param  int $livro
     * @return Divida
     */
    public function setLivro($livro)
    {
        $this->livro = $livro;
        return $this;
    }

    /**
     * @return int
     */
    public function getFolha()
    {
        return $this->folha;
    }

    /**
     * @param  int $folha
     * @return Divida
     */
    public function setFolha($folha)
    {
        $this->folha = $folha;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDataVencimento()
    {
        return $this->dataVencimento;
    }

    /**
     * @param  \DateTime $dataVencimento
     * @return Divida
     */
    public function setDataVencimento($dataVencimento)
    {
        $this->dataVencimento = $dataVencimento;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDataOperacao()
    {
        return $this->dataOperacao;
    }

    /**
     * @param  \DateTime $dataOperacao
     * @return Divida
     */
    public function setDataOperacao($dataOperacao)
    {
        $this->dataOperacao = $dataOperacao;
        return $this;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param  float $valor
     * @return Divida
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @param  string $observacao
     * @return Divida
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumdig()
    {
        return $this->numdig;
    }

    /**
     * @param  int $numdig
     * @return Divida
     */
    public function setNumdig($numdig)
    {
        $this->numdig = $numdig;
        return $this;
    }

    /**
     * @return int
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param  int $instituicao
     * @return Divida
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDataInclusao()
    {
        return $this->dataInclusao;
    }

    /**
     * @param  \DateTime $dataInclusao
     * @return Divida
     */
    public function setDataInclusao($dataInclusao)
    {
        $this->dataInclusao = $dataInclusao;
        return $this;
    }

    /**
     * @return string
     */
    public function getProcesso()
    {
        return $this->processo;
    }

    /**
     * @param  string $processo
     * @return Divida
     */
    public function setProcesso($processo)
    {
        $this->processo = $processo;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDataProcesso()
    {
        return $this->dataProcesso;
    }

    /**
     * @param  \DateTime $dataProcesso
     * @return Divida
     */
    public function setDataProcesso($dataProcesso)
    {
        $this->dataProcesso = $dataProcesso;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitular()
    {
        return $this->titular;
    }

    /**
     * @param  string $titular
     * @return Divida
     */
    public function setTitular($titular)
    {
        $this->titular = $titular;
        return $this;
    }
}
