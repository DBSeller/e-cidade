<?php

namespace ECidade\Configuracao\Instituicao\Repository;

use Exception;
use cl_db_config;
use DBDate;
use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Configuracao\Instituicao\Model\Instituicao;

class InstituicaoRepository extends Repository
{
    /**
     * @param integer $id
     * @return Instituicao
     */
    public function find($id)
    {
        $dao = new cl_db_config();
        $rs = db_query($dao->sql_query_file($id));
        if (!$rs) {
            throw new Exception("Erro ao buscar instituição.");
        }

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        return Instituicao::fromState(pg_fetch_array($rs));
    }

    /**
     * Salva dados na db_config
     * @param Instituicao $instituicao
     * @throws Exception
     * @return Instituicao
     */
    public function salvar(Instituicao $instituicao)
    {
        $dao = new cl_db_config();
        $dao->codigo = $instituicao->getCodigo();
        $dao->nomeinst = $instituicao->getNome();
        $dao->ender = $instituicao->getEndereco();
        $dao->munic = $instituicao->getMunicipio();
        $dao->uf = $instituicao->getUf();
        $dao->telef = $instituicao->getTelefone();
        $dao->email = $instituicao->getEmail();
        $dao->tx_banc = $instituicao->getTaxaBancaria();
        $dao->numbanco = $instituicao->getNumeroBanco();
        $dao->url = $instituicao->getSite();
        $dao->logo = $instituicao->getLogo();
        $dao->figura = $instituicao->getFigura();
        $dao->dtcont = '';
        if ($instituicao->getDataContabilidade() instanceof DBDate) {
            $dao->dtcont = $instituicao->getDataContabilidade()->getDate('Y-m-d');
        }
        $dao->diario = $instituicao->getDiario();
        $dao->pref = $instituicao->getPrefeito();
        $dao->vicepref = $instituicao->getVicePrefeito();
        $dao->fax = $instituicao->getFax();
        $dao->cgc = $instituicao->getCgc();
        $dao->cep = $instituicao->getCep();
        $dao->tpropri = $instituicao->isDebitoProprietarios();
        $dao->tsocios = $instituicao->isDebitoSocios();
        $dao->prefeitura = $instituicao->isPrefeitura();
        $dao->bairro = $instituicao->getBairro();
        $dao->numcgc = $instituicao->getNumeroCgm();
        $dao->codtrib = $instituicao->getCodigoOrgaoUnidade();
        $dao->tribinst = $instituicao->getInstituicaoSiapcPad();
        $dao->segmento = $instituicao->getSegmento();
        $dao->formvencfebraban = $instituicao->getFormaVencimentoFebraban();
        $dao->numero = $instituicao->getNumeroEndereco();
        $dao->nomedebconta = $instituicao->getNomeContaDebito();
        $dao->db21_tipoinstit = $instituicao->getTipoInstituicao();
        $dao->db21_ativo = $instituicao->getAtivo();
        $dao->db21_regracgmiss = $instituicao->getRegraCgmIssbase();
        $dao->db21_regracgmiptu = $instituicao->getRegraCgmIptu();
        $dao->db21_codcli = $instituicao->getCodigoCliente();
        $dao->nomeinstabrev = $instituicao->getNomeAbreviado();
        $dao->db21_usasisagua = $instituicao->usaSisAgua();
        $dao->db21_codigomunicipoestado = $instituicao->getCodigoMunicipioEstado();
        $dao->db21_datalimite = '';
        if ($instituicao->getDataLimite() instanceof DBDate) {
            $dao->db21_datalimite = $instituicao->getDataLimite()->getDate('Y-m-d');
        }
        $dao->db21_criacao = '';
        if ($instituicao->getDataCriacao() instanceof DBDate) {
            $dao->db21_criacao = $instituicao->getDataCriacao()->getDate('Y-m-d');
        }
        $dao->db21_compl = $instituicao->getComplemento();
        $dao->db21_imgmarcadagua = $instituicao->getImgMarcaDagua();
        $dao->db21_esfera = $instituicao->getEsfera();
        $dao->db21_tipopoder = $instituicao->getTipoPoder();
        $dao->db21_codtj = $instituicao->getCodigoMunicipioTj();
        $dao->db21_codsiconfi = $instituicao->getCodigoSiconfi();
        $dao->db21_unidade_gestora_rpps = $instituicao->isUnidadeGestoraRpps();
        $dao->db21_esfera_op = $instituicao->getEsferaOp();
        $dao->db21_valor_teto_remuneratorio = $instituicao->getTetoRemuneratorio();
        $dao->db21_ente_federativo_resp = $instituicao->isEnteFederativoResp();
        $dao->db21_cnpj_efr = $instituicao->getCnpjEnteFederativoResp();
        $dao->db21_efr_previdencia_compl = $instituicao->efrInstituiPrevidenciaComplementar();
        $dao->db21_possui_rpps = $instituicao->possuiRpps();
        $dao->db21_departamento = $instituicao->getCodigoDepartamentoPrincipal();
        $dao->db21_descr_depart_abrev = $instituicao->getDescricaoDepartamentoAbreviado();
        
        if (empty($dao->codigo)) {
            $dao->incluir(null);
        } else {
            $dao->alterar($dao->codigo);
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar Instituição. " . pg_last_error());
        }

        $instituicao->setCodigo($dao->codigo);
        return $instituicao;
    }
}
