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
namespace ECidade\RecursosHumanos\Pessoal\Model;

use ECidade\Configuracao\Instituicao\Model\Instituicao;

class EquipamentoProtecaoEpi
{
    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var integer
     */
    private $codigoEquipamentoProtecao;

    /**
     * @var string
     */
    private $documentoAvaliacao;

    /**
     * @var string
     */
    private $descricao;

    public function __construct($codigo = null)
    {
        if (!empty($codigo)) {
            $instituicao = db_getsession("DB_instit");
            // No sistema esta invertido o cargo/funcao
            $sql = "
                select
                    *
                from
                    pessoal.rhlocaltrabequipamentoprotecaoepi
                where
                    rh259_sequencial = {$codigo}";
            $rs = \db_query($sql);

            if (!$rs) {
                throw new \DBException("Houve um erro ao buscar o EPI código {$codigo}.");
            }

            if (pg_num_rows($rs) == 0) {
                throw new \BusinessException("EPI código {$codigo} não encontrado.");
            }

            $epi = \db_utils::fieldsMemory($rs, 0);
            $this->setCodigo($epi->rh259_sequencial);
            $this->setCodigoEquipamentoProtecao($epi->rh259_rhlocaltrabequipamentoprotecao);
            $this->setDocumentoAvaliacao($epi->rh259_documentoavaliacao);
            $this->setDescricao($epi->rh259_descricao);
        }
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return int
     */
    public function getCodigoEquipamentoProtecao()
    {
        return $this->codigoEquipamentoProtecao;
    }

    /**
     * @param int $codigoEquipamentoProtecao
     */
    public function setCodigoEquipamentoProtecao($codigoEquipamentoProtecao)
    {
        $this->codigoEquipamentoProtecao = $codigoEquipamentoProtecao;
    }

    /**
     * @return string
     */
    public function getDocumentoAvaliacao()
    {
        return $this->documentoAvaliacao;
    }

    /**
     * @param string $documentoAvaliacao
     */
    public function setDocumentoAvaliacao($documentoAvaliacao)
    {
        $this->documentoAvaliacao = $documentoAvaliacao;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public static function getEpisByEquipamento($codigoEquipamento)
    {
        $epis = [];
        if (!empty($codigoEquipamento)) {
            $instituicao = db_getsession("DB_instit");
            $sql = "
                select
                    rh259_sequencial
                from
                    pessoal.rhlocaltrabequipamentoprotecaoepi
                where
                    rh259_rhlocaltrabequipamentoprotecao = {$codigoEquipamento}";
            $rs = \db_query($sql);

            if (!$rs) {
                $msg = "Houve um erro ao buscar os EPIs do equipamento de proteção coletivo ";
                $msg .= "código {$codigoEquipamento}.";
                throw new \DBException($msg);
            }

            $totalEpis = pg_num_rows($rs);
            for ($i = 0; $i < $totalEpis; $i++) {
                $epi = \db_utils::fieldsMemory($rs, $i);
                $epis[] = new EquipamentoProtecaoEpi($epi->rh259_sequencial);
            }
        }
        return $epis;
    }
}
