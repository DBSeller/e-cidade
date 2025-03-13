<?php

namespace ECidade\Patrimonial\Compras\ItemEmpenho\Model;

class Item
{

    /**
     * @var int
     */
    private $codigoItem;

    /**
     * @var String
     */
    private $descricaoItem;

    /**
     * @var String
     */
    private $complemento;

    /**
     * @var int
     */
    private $codigoSubgrupo;

    /**
     * @var boolean
     */
    private $ativo;

    /**
     * @var boolean
     */
    private $conversao;

    /**
     * @var int
     */
    private $idUsuario;

    /**
     * @var boolean
     */
    private $liberacaoAutomatica;

    /**
     * @var boolean
     */
    private $servico;

    /**
     * @var boolean
     */
    private $veiculo;

    /**
     * @var boolean
     */
    private $validadeMinima;

    /**
     * @var boolean
     */
    private $obrigatorio;

    /**
     * @var boolean
     */
    private $fraciona;

    /**
     * @var boolean
     */
    private $liberaResumo;

    public function __construct($codigoItem = null)
    {
        if ($codigoItem) {
            $dao = new \cl_pcmater();
            $sql = $dao->sql_query_file($codigoItem);

            $rs = $dao->sql_record($sql);

            if ($rs) {
                $this->codigoItem = (int) pg_fetch_result($rs, 0, 'pc01_codmater');
                $this->descricaoItem = pg_fetch_result($rs, 0, 'pc01_descrmater');
                $this->complemento = pg_fetch_result($rs, 0, 'pc01_complmater');
                $this->codigoSubgrupo = (int) pg_fetch_result($rs, 0, 'pc01_codsubgrupo');
                $this->ativo = (boolean) pg_fetch_result($rs, 0, 'pc01_ativo');
                $this->conversao = (boolean) pg_fetch_result($rs, 0, 'pc01_conversao');
                $this->idUsuario = (int) pg_fetch_result($rs, 0, 'pc01_id_usuario');
                $this->liberacaoAutomatica = (boolean) pg_fetch_result($rs, 0, 'pc01_libaut');
                $this->servico = (boolean) pg_fetch_result($rs, 0, 'pc01_servico');
                $this->veiculo = (boolean) pg_fetch_result($rs, 0, 'pc01_veiculo');
                $this->validadeMinima = (boolean) pg_fetch_result($rs, 0, 'pc01_validademinima');
                $this->obrigatorio = (boolean) pg_fetch_result($rs, 0, 'pc01_obrigatorio');
                $this->fraciona = (boolean) pg_fetch_result($rs, 0, 'pc01_fraciona');
                $this->liberaResumo = (boolean) pg_fetch_result($rs, 0, 'pc01_liberaresumo');
            }
        }
    }

    /**
     * @return int
     */
    public function getCodigoItem()
    {
        return $this->codigoItem;
    }

    /**
     * @param int $codigoItem
     */
    public function setCodigoItem($codigoItem)
    {
        $this->codigoItem = $codigoItem;
    }

    /**
     * @return String
     */
    public function getDescricaoItem()
    {
        return $this->descricaoItem;
    }

    /**
     * @param String $descricaoItem
     */
    public function setDescricaoItem($descricaoItem)
    {
        $this->descricaoItem = $descricaoItem;
    }

    /**
     * @return String
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * @param String $complemento
     */
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
    }

    /**
     * @return int
     */
    public function getCodigoSubgrupo()
    {
        return $this->codigoSubgrupo;
    }

    /**
     * @param int $codigoSubgrupo
     */
    public function setCodigoSubgrupo($codigoSubgrupo)
    {
        $this->codigoSubgrupo = $codigoSubgrupo;
    }

    /**
     * @return bool
     */
    public function isAtivo()
    {
        return $this->ativo;
    }

    /**
     * @param bool $ativo
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
    }

    /**
     * @return bool
     */
    public function isConversao()
    {
        return $this->conversao;
    }

    /**
     * @param bool $conversao
     */
    public function setConversao($conversao)
    {
        $this->conversao = $conversao;
    }

    /**
     * @return int
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * @param int $idUsuario
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    /**
     * @return bool
     */
    public function isLiberacaoAutomatica()
    {
        return $this->liberacaoAutomatica;
    }

    /**
     * @param bool $liberacaoAutomatica
     */
    public function setLiberacaoAutomatica($liberacaoAutomatica)
    {
        $this->liberacaoAutomatica = $liberacaoAutomatica;
    }

    /**
     * @return bool
     */
    public function isServico()
    {
        return $this->servico;
    }

    /**
     * @param bool $servico
     */
    public function setServico($servico)
    {
        $this->servico = $servico;
    }

    /**
     * @return bool
     */
    public function isVeiculo()
    {
        return $this->veiculo;
    }

    /**
     * @param bool $veiculo
     */
    public function setVeiculo($veiculo)
    {
        $this->veiculo = $veiculo;
    }

    /**
     * @return bool
     */
    public function isValidadeMinima()
    {
        return $this->validadeMinima;
    }

    /**
     * @param bool $validadeaMinima
     */
    public function setValidadeMinima($validadeMinima)
    {
        $this->validadeMinima = $validadeMinima;
    }

    /**
     * @return bool
     */
    public function isObrigatorio()
    {
        return $this->obrigatorio;
    }

    /**
     * @param bool $obrigatorio
     */
    public function setObrigatorio($obrigatorio)
    {
        $this->obrigatorio = $obrigatorio;
    }

    /**
     * @return bool
     */
    public function isFraciona()
    {
        return $this->fraciona;
    }

    /**
     * @param bool $fraciona
     */
    public function setFraciona($fraciona)
    {
        $this->fraciona = $fraciona;
    }

    /**
     * @return bool
     */
    public function isLiberaResumo()
    {
        return $this->liberaResumo;
    }

    /**
     * @param bool $liberaResumo
     */
    public function setLiberaResumo($liberaResumo)
    {
        $this->liberaResumo = $liberaResumo;
    }

    public function toArray()
    {
        return array(
            'pc01_codmater' => $this->getCodigoItem(),
            'pc01_descrmater' => $this->getDescricaoItem(),
            'pc01_complmater' => $this->getComplemento(),
            'pc01_codsubgrupo' => $this->getCodigoSubgrupo(),
            'pc01_ativo' => $this->isAtivo(),
            'pc01_conversao' => $this->isConversao(),
            'pc01_id_usuario' => $this->getIdUsuario(),
            'pc01_libaut' => $this->isLiberacaoAutomatica(),
            'pc01_servico' => $this->isServico(),
            'pc01_veiculo' => $this->isVeiculo(),
            'pc01_validademinima' => $this->isValidadeMinima(),
            'pc01_obrigatorio' => $this->isObrigatorio(),
            'pc01_fraciona' => $this->isFraciona(),
            'pc01_liberaresumo' => $this->isLiberaResumo()
        );
    }

    public static function fromState(array $state)
    {
        $item = new self();

        if (array_key_exists(('pc01_codmater'), $state)) {
            $item->setCodigoItem((int) $state['pc01_codmater']);
        }

        if (array_key_exists(('pc01_descrmater'), $state)) {
            $item->setDescricaoItem($state['pc01_descrmater']);
        }

        if (array_key_exists(('pc01_complmater'), $state)) {
            $item->setComplemento($state['pc01_complmater']);
        }

        if (array_key_exists(('pc01_codsubgrupo'), $state)) {
            $item->setCodigoSubgrupo((int) $state['pc01_codsubgrupo']);
        }

        if (array_key_exists(('pc01_ativo'), $state)) {
            $item->setAtivo((boolean) $state['pc01_ativo']);
        }

        if (array_key_exists(('pc01_conversao'), $state)) {
            $item->setConversao((boolean) $state['pc01_conversao']);
        }

        if (array_key_exists(('pc01_id_usuario'), $state)) {
            $item->setIdUsuario((int) $state['pc01_id_usuario']);
        }

        if (array_key_exists(('pc01_libaut'), $state)) {
            $item->setLiberacaoAutomatica((boolean) $state['pc01_libaut']);
        }

        if (array_key_exists(('pc01_servico'), $state)) {
            $item->setServico((boolean) $state['pc01_servico']);
        }

        if (array_key_exists(('pc01_veiculo'), $state)) {
            $item->setVeiculo((int) $state['pc01_veiculo']);
        }

        if (array_key_exists(('pc01_validademinima'), $state)) {
            $item->setValidadeMinima((boolean) $state['pc01_validademinima']);
        }

        if (array_key_exists(('pc01_obrigatorio'), $state)) {
            $item->setObrigatorio((boolean) $state['pc01_obrigatorio']);
        }

        if (array_key_exists(('pc01_fraciona'), $state)) {
            $item->setFraciona((boolean) $state['pc01_fraciona']);
        }

        if (array_key_exists(('pc01_liberaresumo'), $state)) {
            $item->setLiberaResumo((boolean) $state['pc01_liberaresumo']);
        }

        return $item;
    }
}
