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

namespace ECidade\Tributario\Juridico\Inicial\Repository;

use ECidade\Tributario\Divida\Certidao\Repository\Certidao as CertidaoRepository;
use ECidade\Tributario\Juridico\Inicial\Inicial as Entity;
use ECidade\Tributario\Juridico\Inicial\Repository\InicialNome as InicialNomeRepository;
use ECidade\Tributario\Juridico\Repository\HonorariosParcelamento as HonorariosParcelamentoRepository;
use ECidade\Tributario\Divida\Termo\Termo;
use ECidade\Tributario\Divida\Repository\Divida as DividaRepository;
use ECidade\Tributario\Divida\Repository\DiversosRepository;
use BusinessException;

/**
 * Class Inicial
 *
 * @method static Inicial getInstance()
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class Inicial extends \BaseClassRepository
{
    /** @var bool */
    private $returnFullItem;

    /** @var bool */
    private $persistPropagation;

    protected static $oInstance;

    /**
     * @var array
     */
    private $scopes;

    /**
     * @var array
     */
    private $joins = array();

    /**
     * Retorna uma inicial filtrando por codigo.
     *
     * @param integer $code
     *
     * @return Entity|null
     *
     * @throws \Exception
     */
    public function getByCode($code)
    {
        $dao = new \cl_inicial;
        $sql = $dao->sql_query($code);

        $result = \db_query($sql);

        if (!$result) {
            throw new \Exception('Não foi possível consultar a inicial.');
        }

        if (!pg_num_rows($result)) {
            return null;
        }

        return $this->make(pg_fetch_object($result, 0));
    }

    /**
     * Retorna inicial filtrando por certidão.
     *
     * @param integer $certidao
     *
     * @return Entity|null
     *
     * @throws \Exception
     */
    public function getByCertidao($certidao)
    {
        $dao = new \cl_inicial;
        $sql = $dao->sql_query_certidao($certidao);

        $result = \db_query($sql);

        if (!$result) {
            throw new \Exception('Não foi possível consultar a inicial filtrando por certidão.');
        }

        if (!pg_num_rows($result)) {
            return null;
        }

        return $this->make(pg_fetch_object($result, 0));
    }

    /**
     * @param integer $codigoProcesso
     *
     * @return Entity[]|null
     *
     * @throws \Exception
     */
    public function getByProcessoForo($codigoProcesso)
    {
        $dao = new \cl_inicial;
        $sql = $dao->sql_query_by_processo($codigoProcesso);

        $result = \db_query($sql);

        if (!$result) {
            throw new \Exception('Não foi possível consultar a inicial filtrando por processo.');
        }

        if (!pg_num_rows($result)) {
            return null;
        }

        $data = array();
        foreach (pg_fetch_all($result) as $item) {
            $data[] = $this->make((object) $item);
        }

        return $data;
    }

    /**
     * @param integer $codigoProcesso
     *
     * @return array|null
     *
     * @throws \Exception
     */
    public function getIniciaisAtivasPorProcesso($codigoProcesso)
    {
        $dao = new \cl_inicial;
        $sql = $dao->sql_query_by_processo($codigoProcesso, false);

        $result = \db_query($sql);

        if (!$result) {
            throw new \Exception('Não foi possível consultar a inicial filtrando por processo.');
        }

        if (!pg_num_rows($result)) {
            return null;
        }

        $data = array();
        foreach (pg_fetch_all($result) as $item) {
            $data[] = $this->make((object) $item);
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function isReciboEmitidoDebito($iInicial)
    {
        $sSql  = " select 1                                                                           ";
        $sSql .= "   from inicial                                                                     ";
        $sSql .= "        inner join inicialnumpre on inicialnumpre.v59_inicial = inicial.v50_inicial ";
        $sSql .= "        inner join recibopaga on recibopaga.k00_numpre = inicialnumpre.v59_numpre   ";
        $sSql .= "  where inicial.v50_inicial = {$iInicial}                                           ";
        $sSql .= "  limit 1                                                                           ";

        $rsResult = db_query($sSql);

        if (!$rsResult) {
            throw new DBException("Ocorreu um erro ao buscar dados da inicial {$iInicial}");
        }

        $lReciboEmitido = false;

        if (pg_num_rows($rsResult) > 0) {
            $lReciboEmitido = true;
        }

        return $lReciboEmitido;
    }

    /**
     * @return bool
     */
    public function isDebitoPago($iInicial)
    {
        $sSql  = " select 1                                                                           ";
        $sSql .= "   from inicial                                                                     ";
        $sSql .= "        inner join inicialnumpre on inicialnumpre.v59_inicial = inicial.v50_inicial ";
        $sSql .= "        inner join arrepaga on arrepaga.k00_numpre = inicialnumpre.v59_numpre       ";
        $sSql .= "  where inicial.v50_inicial = {$iInicial}                                           ";
        $sSql .= "  limit 1                                                                           ";

        $rsResult = db_query($sSql);

        if (!$rsResult) {
            throw new DBException("Ocorreu um erro ao buscar dados da inicial {$iInicial}");
        }

        $lDebitoPago = false;

        if (pg_num_rows($rsResult) > 0) {
            $lDebitoPago = true;
        }

        return $lDebitoPago;
    }

    /**
     * Persiste uma inicial no banco de dados.
     *
     * @param Entity $inicial
     *
     * @return Entity
     *
     * @throws \Exception
     */
    public function persist(Entity $inicial)
    {
        $dao = new \cl_inicial;

        $dao->v50_advog = $inicial->getAdvogado();
        $dao->v50_data = $inicial->getData()->format('Y-m-d H:i:s');
        $dao->v50_id_login = $inicial->getLogin();
        $dao->v50_codlocal = $inicial->getCodigoForo();
        $dao->v50_codmov = $inicial->getCodigoMovimento();
        $dao->v50_instit = $inicial->getInstituicao();
        $dao->v50_situacao = $inicial->getSituacao();

        $codigo = $inicial->getCodigo();

        if (!empty($codigo)) {
            $dao->v50_inicial = $codigo;
            $dao->alterar($codigo);
        } else {
            $dao->incluir(null);
            $inicial->setCodigo($dao->v50_inicial);
        }

        if ($dao->erro_status == 0) {
            throw new \Exception($dao->erro_msg);
        }

        if ($this->isPersistPropagation() && $inicial->getCertidoes()) {
            $certidaoRepository = CertidaoRepository::getInstance();
            $certidaoRepository->setPersistPropagation(true);

            $dao = new \cl_inicialcert();
            foreach ($inicial->getCertidoes() as $certidao) {
                $codigoCertidao = $certidao->getCodigo();

                $certidaoRepository->persist($certidao);

                if (empty($codigoCertidao)) {
                    $dao->incluir($inicial->getCodigo(), $certidao->getCodigo());
                }
            }
        }

        if ($this->isPersistPropagation() && $inicial->getInicialNomes()) {
            $inicialNomeRepository = InicialNomeRepository::getInstance();

            foreach ($inicial->getInicialNomes() as $inicialNome) {
                $inicialNomeRepository->persist($inicialNome, $inicial->getCodigo());
            }
        }

        return $inicial;
    }

    /**
     * @param \stdClass $inicial
     *
     * @return Entity
     */
    protected function make($inicial)
    {
        $entity = new Entity;
        $entity
            ->setCodigo($inicial->v50_inicial)
            ->setAdvogado($inicial->v50_advog)
            ->setData(new \DateTime($inicial->v50_data))
            ->setLogin($inicial->v50_id_login)
            ->setCodigoForo($inicial->v50_codlocal)
            ->setCodigoMovimento($inicial->v50_codmov)
            ->setInstituicao($inicial->v50_instit)
            ->setSituacao($inicial->v50_situacao);

        if ($this->isReturnFullItem()) {
            $certidaoRepository = CertidaoRepository::getInstance()
                ->setReturnFullItem(true);

            $entity->setCertidoes($certidaoRepository->getByInicial($inicial->v50_inicial));

            $inicialNomeRepository = InicialNomeRepository::getInstance();

            $iniciaisNome = $inicialNomeRepository->getByInitial($inicial->v50_inicial);

            if (!empty($iniciaisNome)) {
                $entity->setInicialNomes($iniciaisNome);
            }

            $honorariosParcelamentoRepository = HonorariosParcelamentoRepository::getInstance();
            $parcelas = $honorariosParcelamentoRepository->getByInicial($entity);
            $entity->setParcelasHonorarios($parcelas);
        }

        return $entity;
    }

    /**
     * @return Entity[]
     */
    public function get()
    {
        $dao = new \cl_inicial();

        $sql = $dao->query(
            '*',
            implode(' AND ', $this->scopes),
            implode(' ', $this->joins)
        );
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os iniciais.");
        }

        $iniciais = array();

        while ($inicial = pg_fetch_array($rs)) {
            $iniciais[] = Entity::fromState($inicial);
        }

        $this->scopes = array();
        $this->joins = array();

        return $iniciais;
    }

    public function first()
    {
        $all = $this->get();

        if (empty($all)) {
            return null;
        }

        return $all[0];
    }

    /**
     * Adiciona um scope na busca
     *
     * @param  string $id       Identificador desta regra
     * @param  string $campo    Lado esquerdo da
     *                          operação
     * @param  string $operacao Operador
     * @param  string $valor    Lado direito da
     *                          operação
     * @return Inicial
     */
    private function scope($id, $campo, $operacao, $valor)
    {
        $this->scopes[$id] = "{$campo} {$operacao} {$valor}";
        return $this;
    }

    /**
     * Filtra por 'v50_inicial'
     *
     * @param  mixed $valor
     * @param  mixed $operacao
     * @return Inicial
     */
    public function scopeInicial($valor, $operacao = '=')
    {
        return $this->scope('inicial', 'v50_inicial', $operacao, $valor);
    }

    /**
     * @param $valor
     * @param string $operacao
     * @return Inicial
     */
    public function scopeNumpre($valor, $operacao = '=')
    {
        $this->innerJoin('inicialnumpre', 'v59_inicial', 'v50_inicial');

        return $this->scope('numpre', 'v59_numpre', $operacao, $valor);
    }

    public function withCodigoForo()
    {
        $this->leftJoin('processoforoinicial', 'v50_inicial', 'v71_inicial')
            ->leftJoin('processoforo', 'v71_processoforo', 'v70_sequencial');

        return $this;
    }

    private function innerJoin($tabela, $chaveEsquerda, $chaveDireita)
    {
        $this->joins[$tabela] = "inner join {$tabela} on {$chaveEsquerda} = {$chaveDireita}";

        return $this;
    }

    /**
     * @param  array $numpres
     * @param  Termo $parcelamento
     * @return bool
     */
    public function atualizarObservacaoOrigemPorNumpreAoAnular(array $numpres, Termo $parcelamento)
    {
        $dividaRepository = DividaRepository::getInstance();
        $diversosRepository = DiversosRepository::getInstance();

        // No final das contas, uma dívida inicial é ou um diversos ou uma dívida, então atualize ambos
        return $dividaRepository->atualizarObservacaoOrigemPorNumpreAoAnular($numpres, $parcelamento) &&
               $diversosRepository->atualizarObservacaoOrigemPorNumpreAoAnular($numpres, $parcelamento);
    }

    /**
     * @return bool
     */
    public function isReturnFullItem()
    {
        return $this->returnFullItem;
    }

    /**
     * @param bool $returnFullItem
     * @return Inicial
     */
    public function setReturnFullItem($returnFullItem)
    {
        $this->returnFullItem = $returnFullItem;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPersistPropagation()
    {
        return $this->persistPropagation;
    }

    /**
     * @param bool $persistPropagation
     * @return Inicial
     */
    public function setPersistPropagation($persistPropagation)
    {
        $this->persistPropagation = $persistPropagation;
        return $this;
    }

    private function leftJoin($tabela, $chaveEsquerda, $chaveDireita)
    {
        $this->joins[$tabela] = "left join {$tabela} on {$chaveEsquerda} = {$chaveDireita}";

        return $this;
    }
}
