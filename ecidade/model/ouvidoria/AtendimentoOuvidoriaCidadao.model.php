<?php

require_once(modification('model/ouvidoria/Cidadao.model.php'));
/**
 * Class AtendimentoOuvidoriaCidadao
 */
class AtendimentoOuvidoriaCidadao
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $seq;
    /**
     * @var integer
     */
    private $cidadaoId;
    /**
     * @var integer
     */
    private $ouvidoriaAtendimentoId;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


    /**
     * @return int
     */
    public function getSeq()
    {
        return $this->seq;
    }

    /**
     * @param int $seq
     * @return $this
     */
    public function setSeq($seq)
    {
        $this->seq = $seq;
        return $this;
    }

    /**
     * @return int
     */
    public function getCidadaoId()
    {
        return $this->cidadaoId;
    }

    /**
     * @param int $cidadaoId
     * @return $this
     */
    public function setCidadaoId($cidadaoId)
    {
        $this->cidadaoId = $cidadaoId;
        return $this;
    }

    /**
     * @return int
     */
    public function getOuvidoriaAtendimentoId()
    {
        return $this->ouvidoriaAtendimentoId;
    }

    /**
     * @param int $ouvidoriaAtendimentoId
     * @return $this
     */
    public function setOuvidoriaAtendimentoId($ouvidoriaAtendimentoId)
    {
        $this->ouvidoriaAtendimentoId = $ouvidoriaAtendimentoId;
        return $this;
    }


    /**
     * @param $id
     * @return AtendimentoOuvidoriaCidadao|false
     */
    public static function find($id)
    {
        if (empty($id)) {
            return false;
        }
        $ouvidoriaAtendimentoCidadao = new cl_ouvidoriaatendimentocidadao();
        $sql = $ouvidoriaAtendimentoCidadao->sql_query_file($id);
        $rs = $ouvidoriaAtendimentoCidadao->sql_record($sql);

        if ($ouvidoriaAtendimentoCidadao->numrows < 1) {
            return false;
        }

        $objAtendimento = pg_fetch_object($rs);
        if (empty($objAtendimento)) {
            return false;
        }

        return self::fromDao($objAtendimento);
    }


    /**
     * @param $idAtendimento
     * @return AtendimentoOuvidoriaCidadao|false
     */
    public static function findByAtendimento($idAtendimento)
    {
        if (empty($idAtendimento)) {
            return false;
        }

        $ouvidoriaAtendimento = new cl_ouvidoriaatendimentocidadao();
        $sql = $ouvidoriaAtendimento->sql_query_file(null, "*", null, "ov10_ouvidoriaatendimento={$idAtendimento}");
        $rs = $ouvidoriaAtendimento->sql_record($sql);

        if ($ouvidoriaAtendimento->numrows < 1) {
            return false;
        }
        $objOuvidoriaAtendimento = pg_fetch_object($rs);
        return self::fromDao($objOuvidoriaAtendimento);
    }


    public static function fromDao($resultDao)
    {
        $atendimentoCidadao = new self();
        return $atendimentoCidadao
            ->setId($resultDao->ov10_sequencial)
            ->setOuvidoriaAtendimentoId($resultDao->ov10_ouvidoriaatendimento)
            ->setCidadaoId($resultDao->ov10_cidadao)
            ->setSeq($resultDao->ov10_seq);
    }

    /**
     * @source  model/ouvidoria/Cidadao.model.php
     * @return Cidadao|false|void
     */
    public function getCidadao()
    {
        return Cidadao::find($this->getCidadaoId());
    }


}
