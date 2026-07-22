<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2009 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

/**
 * Classe repository para classes Cgm
 *
 * @author
 * @package
 */
class CgmRepository
{
    /**
     * Instancia da classe
     *
     * @var CgmRepository
     */
    private static $oInstance;
    /**
     * Collection de Cgm
     *
     * @var array
     */
    private $aItens = array();

    private function __construct() {}

    /**
     * Retorna uma instancia do Cgm pelo Codigo
     *
     * @param integer $iCodigo Codigo do Cgm
     * @return CgmFisico|CgmJuridico
     * @throws Exception
     */
    public static function getByCodigo($iCodigo)
    {
        if (!array_key_exists($iCodigo, CgmRepository::getInstance()->aItens)) {
            CgmRepository::getInstance()->aItens[$iCodigo] = CgmFactory::getInstanceByCgm($iCodigo);
        }

        return CgmRepository::getInstance()->aItens[$iCodigo];
    }

    /**
     * Busca um cgm através da tabela rhpessoal
     *
     * @param mixed $matricula
     * @return CgmFisico|CgmJuridico
     */
    public static function getByMatricula($matricula)
    {
        $dao = new cl_rhpessoal();
        $sql = $dao->sql_query_file($matricula, 'rh01_numcgm', null, null);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o rhpessoal de matrícula {$matricula}.");
        }

        if (pg_num_rows($rs) <= 0) {
            return null;
        }

        $cgm = pg_fetch_array($rs);

        return static::getByCodigo($cgm['rh01_numcgm']);
    }

    /**
     * Retorna a instancia da classe
     *
     * @return CgmRepository
     */
    protected static function getInstance()
    {
        if (self::$oInstance == null) {
            self::$oInstance = new CgmRepository();
        }

        return self::$oInstance;
    }

    /**
     * Adiciona uma instancia de Cgm ao repositorio
     *
     * @param CgmBase $oCgm Instancia de Cgm
     * @return boolean
     */
    public static function adicionarCgm(CgmBase $oCgm)
    {
        if (!array_key_exists($oCgm->getCodigo(), CgmRepository::getInstance()->aItens)) {
            CgmRepository::getInstance()->aItens[$oCgm->getCodigo()] = $oCgm;
        }

        return true;
    }

    /**
     * Remove a instancia passada como parametro do repository
     *
     * @param CgmBase $oCgm
     * @return boolean
     */
    public static function remover(CgmBase $oCgm)
    {
        if (array_key_exists($oCgm->getCodigo(), CgmRepository::getInstance()->aItens)) {
            unset(CgmRepository::getInstance()->aItens[$oCgm->getCodigo()]);
        }

        return true;
    }

    /**
     * Retorna o total de itens existentes no repositorio;
     *
     * @return integer;
     */
    public static function getTotalCgm()
    {
        return count(CgmRepository::getInstance()->aItens);
    }

    /**
     * Método para buscar todos os CGM's que possuem matrícula na instituição
     *
     * @param DBCompetencia $dbCompetencia
     * @return \stdClass[]: Retorna os códigos de CGM
     * @throws DBException
     */
    public static function buscarTodosCGMCompetencia(DBCompetencia $dbCompetencia)
    {
        $instituicao = InstituicaoRepository::getInstituicaoSessao()->getCodigo();
        $where = array(
          "rh02_anousu = {$dbCompetencia->getAno()}",
          "rh02_mesusu = {$dbCompetencia->getMes()}",
          "rh02_instit = {$instituicao}"
        );

        $daoRhPessoalMov = new cl_rhpessoalmov();
        $sqlRhPessoalMov = $daoRhPessoalMov->sql_query_matricula_cgm(
          null,
          $instituicao,
          'distinct rh01_numcgm as cgm',
          'rh01_numcgm',
          implode(' AND ', $where)
        );

        $rsRhPessoalMov = db_query($sqlRhPessoalMov);

        if (!$rsRhPessoalMov) {
            throw new DBException("Erro ao buscar os CGM's com matrcula ativa na instituio.");
        }

        if (pg_num_rows($rsRhPessoalMov) == 0) {
            throw new DBException("Nenhum CGM, com matrcula ativa na instituio, encontrado.");
        }

        return db_utils::makeCollectionFromRecord($rsRhPessoalMov, function ($retorno) {
            return $retorno->cgm;
        });
    }

    /**
     * @param int $codigoCgm
     * @return string
     * @throws BusinessException
     * @throws DBException
     * @throws ParameterException
     */
    public static function buscarCNPJEmpregador($codigoCgm)
    {
        if (empty($codigoCgm)) {
            throw new ParameterException('CGM no informado.');
        }

        $daoCgm = new cl_cgm();
        $sqlCgm = $daoCgm->sql_query_file($codigoCgm, 'z01_cgccpf as cnpj');
        $rsCgm = db_query($sqlCgm);

        if (!$rsCgm) {
            throw new DBException('Erro ao buscar o CNPJ do CGM.');
        }

        if (pg_num_rows($rsCgm) == 0) {
            throw new DBException("CGM {$codigoCgm} no encontrado.");
        }

        $cnpj = db_utils::fieldsMemory($rsCgm, 0)->cnpj;

        if (empty($cnpj)) {
            throw new BusinessException("CNPJ no informado para o CGM {$codigoCgm}.");
        }

        return $cnpj;
    }

    private function __clone() {}

    /**
     * Busca o nome do cgm através do codigo cgm
     *
     * @param mixed $numeroCgm
     * @return string $nome
     */
    public static function getNomeByCodigo($numeroCgm)
    {
        $dao = new cl_cgm();
        $sql = $dao->sql_query_file($numeroCgm, 'z01_nome');
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o nome do cgm {$numeroCgm}.");
        }

        if (pg_num_rows($rs) <= 0) {
            return null;
        }

        $cgm = pg_fetch_array($rs);

        return $cgm['z01_nome'];
    }

    /**
     * Busca o nome do cgm através do cpf do cgm
     *
     * @param mixed $numeroCgm
     * @return string $nome
     */
    public static function getNomeByCpf($cpfCgm)
    {
        $dao = new cl_cgm();
        $sql = $dao->sql_matricula(null, 'z01_nome', null, "z01_cgccpf ='{$cpfCgm}' limit 1");

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o nome do cgm {$cpfCgm}.");
        }

        if (pg_num_rows($rs) <= 0) {
            return null;
        }

        $cgm = pg_fetch_array($rs);

        return $cgm['z01_nome'];
    }

    /**
     * Busca o nome do cgm atraves do CNPJ
     *
     * @param string $sCnpj
     * @return string
     */
    public static function getNomeByCNPJ($sCnpj)
    {
        $dao = new cl_cgm();
        $sql = $dao->sql_query_file(null, 'z01_nome', null, "z01_cgccpf = '{$sCnpj}' limit 1");

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o nome do cnpj {$sCnpj}.");
        }

        if (pg_num_rows($rs) <= 0) {
            return null;
        }

        $cgm = pg_fetch_array($rs);

        return $cgm['z01_nome'];
    }
}
