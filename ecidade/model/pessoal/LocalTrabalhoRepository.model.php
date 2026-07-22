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

class LocalTrabalhoRepository extends BaseClassRepository
{
    /**
     * @var LocalTrabalhoRepository
     */
    protected static $oInstance;

    private $locaisTrabalhoServidor = array();

    public function getLocalTrabalhoPorServidor( \Servidor $servidor, $ano = null, $mes = null) {

        if (empty($ano)) {
            $ano = \DBPessoal::getAnoFolha();
        }

        if (empty($mes)) {
            $mes = \DBPessoal::getMesFolha();
        }

        $instituicaoServidor = $servidor->getCodigoInstituicao();
        $matricula = $servidor->getMatricula();

        if (!empty($this->locaisTrabalhoServidor[$matricula][$instituicaoServidor][$ano][$mes])) {
            return $this->locaisTrabalhoServidor[$matricula][$instituicaoServidor][$ano][$mes];
        }

        $daoRhLocalTrab = new cl_rhlocaltrab();
        $where  = " rh55_instit = {$instituicaoServidor}";
        $where .= " and rh02_regist = {$matricula}";
        $where .= " and rh02_anousu = {$ano}";
        $where .= " and rh02_mesusu = {$mes}";

        $sql = $daoRhLocalTrab->sql_query_servidor("rhlocaltrab.*, rh56_princ , rh56_datainicio, rh56_datafim", $where);
        $rs = \db_query($sql);

        if (!$rs) {
            throw new \DBException('Erro ao buscar o local de trabalho do servidor: ' . $servidor->getCgm()->getNome());
        }

        $linhas = pg_num_rows($rs);
        $this->locaisTrabalhoServidor[$matricula][$instituicaoServidor][$ano][$mes] = array();

        for($i = 0; $i < $linhas; $i++) {
            $dados = db_utils::fieldsMemory($rs, $i);
            $localTrabalho = new LocalTrabalho();
            $instituicao = InstituicaoRepository::getInstituicaoByCodigo($dados->rh55_instit);
            $localTrabalho->setCodigo($dados->rh55_codigo);
            $localTrabalho->setPrincipal($dados->rh56_princ);
            $localTrabalho->setInstituicao($instituicao);
            $localTrabalho->setEstrutural($dados->rh55_estrut);
            $localTrabalho->setDescricao($dados->rh55_descr);
            $localTrabalho->setObservacao($dados->rh55_observacaoregistrosambientais);
            $localTrabalho->setTipoInscricao($dados->rh55_tipoinscricao);
            $localTrabalho->setNumeroInscricao($dados->rh55_numeroinscricao);
            $localTrabalho->setLotacaoTributaria($dados->rh55_lotacaotributaria);

            if (!empty($dados->rh56_datainicio)) {
                $localTrabalho->setDataInicio(new DBDate($dados->rh56_datainicio));
            }
            if (!empty($dados->rh56_datafim)) {
                $localTrabalho->setDataFim(new DBDate($dados->rh56_datafim));
            }

            if ($localTrabalho->isPrincipal()){
                $this->locaisTrabalhoServidor[$matricula][$instituicaoServidor][$ano][$mes]['principal'] = $localTrabalho;
            } else {
                $this->locaisTrabalhoServidor[$matricula][$instituicaoServidor][$ano][$mes][] = $localTrabalho;
            }
        }
        return $this->locaisTrabalhoServidor[$matricula][$instituicaoServidor][$ano][$mes];
    }
}
