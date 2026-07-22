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

namespace ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial;

use cl_rhprocessotributoirrf;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\TributoIRRF;
use Exception;
use DBDate;

class TributoIRRFRepository
{
    /**
     * @var array
     */
    private $scopes = [];


    /**
     * @param int $sequencial
     * @param string $operator
     * @return $this
     */
    public function scopeSequencial($sequencial, $operator = '=')
    {
        $this->scopes['sequencial'] = "rh299_sequencial {$operator} {$sequencial}";
        return $this;
    }

    /**
     * @param $sequencialprocessoservidor
     * @return $this
     */
    public function scopeSequencialServidor($sequencialServidor, $operator = '=')
    {
        $this->scopes['servidor'] = "
            rh299_sequencialprocessoservidor {$operator} {$sequencialServidor}
        ";

        return $this;
    }

    /**
     * @param $codigoReceita
     * @param $operator
     * @return $this
     */
    public function scopeCodigoReceita($codigoReceita, $operator = '=')
    {
        $this->scopes['codigoReceita'] = "
            rh299_tpcr {$operator} {$codigoReceita}
        ";

        return $this;
    }

    /**
     * @param $ano
     * @param $mes
     * @return $this
     */
    public function scopePeriodoContemplado($ano = '', $mes = '')
    {
        $this->scopes['contemplado'] = "
            rh299_pagamento = '{$ano}-{$mes}'
        ";

        return $this;
    }
    /**
     * @return $this
     */
    public function resetScopes()
    {
        $this->scopes = [];

        return $this;
    }
    
    /**
     * @param array|int $ids
     * @return int
     * @throws Exception
     */
    public static function destroy($ids)
    {
        $count = 0;
        $ids = is_array($ids) ? $ids : func_get_args();

        $self = new self();

        foreach ($ids as $id) {
            $self->delete(self::find($id));
            $count++;
        }

        return $count;
    }

    /**
     * @param TributoIRRF|null $tributoIRRF
     * @throws Exception
     */
    public function delete(TributoIRRF $tributoIRRF = null)
    {
        $id = $tributoIRRF instanceof TributoIRRF ? $tributoIRRF->getSequencial() : null;

        $dao = new cl_rhprocessotributoirrf;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o tributo base mensal de IRRF do servidor.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|TributoIRRF
     * @throws Exception
     */
    public static function find($id, $columns = array('*'), $order = null, $where = null)
    {
        $dao = new cl_rhprocessotributoirrf;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);
 
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o tributo base mensal de IRRF do servidor.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return TributoIRRF::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return TributoIRRF[]
     * @throws Exception
     */
    public function all($columns = ['*'])
    {
        $dao = new cl_rhprocessotributoirrf;
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $tributoIRRF = [];

        if (pg_num_rows($rs) === 0) {
            return $tributoIRRF;
        }

        while ($tributoIRRFItem = pg_fetch_array($rs)) {
            $unicidade[] = TributoIRRF::fromState($tributoIRRFItem);
        }
        
        return $tributoIRRF;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return ProcessoJudicial[]
     * @throws Exception
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhprocessotributoirrf;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $tributoIRRF = [];

        if (pg_num_rows($rs) === 0) {
            return $tributoIRRF;
        }

        while ($tributoIRRFItem = pg_fetch_array($rs)) {
            $tributoIRRF[] = TributoIRRF::fromState($tributoIRRFItem);
        }
        
        return $tributoIRRF;
    }


    /**
     * @return TributoIRRF[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_rhprocessotributoirrf;
        $campos =  [
            'rh299_sequencial',
            'rh299_sequencialprocessoservidor',
            'rh299_tpcr',
            'rh299_vcr',
            'rh299_pagamento',
            'rh299_vrrendtrib',
            'rh299_vrrendtrib13',
            'rh299_vrrendmolegrave',
            'rh299_vrrendisen65',
            'rh299_vrjurosmora',
            'rh299_vrrendisenntrib',
            'rh299_descisenntrib',
            'rh299_vrprevoficial',
            'rh299_descrra',
            'rh299_qtdmesesrra',
            'rh299_vlrdespcustas',
            'rh299_vlrdespadvogados'
        ];
        $sql = $dao->sql_query(null, implode(' , ', $campos), null, implode(' AND ', $this->scopes));

        $rs = db_query($sql);


        $tributoIRRF = [];

        if (pg_num_rows($rs) === 0) {
            return $tributoIRRF;
        }

        while ($tributoIRRFProcesso = pg_fetch_array($rs)) {
            $tributoIRRF[] = TributoIRRF::fromState($tributoIRRFProcesso);
        }

        return $tributoIRRF;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_rhprocessotributoIRRF;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o(s) tributo(s) base do servidor(es).");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param TributoIRRF $TributoIRRF
     * @return TributoIRRF
     * @throws Exception
     */
    public function save(TributoIRRF $tributoIRRF)
    {
        $dao = new cl_rhprocessoTributoIRRF;
        $dao->rh299_sequencial                  = $tributoIRRF->getSequencial();
        $dao->rh299_sequencialprocessoservidor  = $tributoIRRF->getSequencialProcessoServidor();
        $dao->rh299_tpcr                        = $tributoIRRF->getCodigoReceita();
        $dao->rh299_vcr                         = $tributoIRRF->getValorIRRF();
        $dao->rh299_pagamento                   = $tributoIRRF->getPeriodoPagamento();
        $dao->rh299_vrrendtrib                  = $tributoIRRF->getValorRendimentoTributavel();
        $dao->rh299_vrrendtrib13                = $tributoIRRF->getValorRendimentoTributavel13();
        $dao->rh299_vrrendmolegrave             = $tributoIRRF->getValorRendimentoMolestia();
        $dao->rh299_vrrendisen65                = $tributoIRRF->getValorIsenta65();
        $dao->rh299_vrjurosmora                 = $tributoIRRF->getValorJurosMora();
        $dao->rh299_vrrendisenntrib             = $tributoIRRF->getValorRendimentoIsento();
        $dao->rh299_descisenntrib               = $tributoIRRF->getDescricaoIsento();
        $dao->rh299_vrprevoficial               = $tributoIRRF->getValorPrevidenciaOficial();
        $dao->rh299_descrra                     = $tributoIRRF->getDescricaoRendimentoAcumula();
        $dao->rh299_qtdmesesrra                 = $tributoIRRF->getQuantidadeMesAcumula();
        $dao->rh299_vlrdespcustas               = $tributoIRRF->getValorDespesaCusta();
        $dao->rh299_vlrdespadvogados            = $tributoIRRF->getValorDespesaAdvogados();

        $dao->rh299_sequencial ? $dao->alterar($tributoIRRF->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar registro relacionado a tributo base do servidor."
                . $dao->erro_msg);
        }

        $tributoIRRF->setSequencial($dao->rh299_sequencial);

        return $tributoIRRF;
    }
}
