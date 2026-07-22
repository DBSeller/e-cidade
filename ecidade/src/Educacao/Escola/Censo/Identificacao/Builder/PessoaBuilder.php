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

namespace ECidade\Educacao\Escola\Censo\Identificacao\Builder;

use DBDate;
use DBString;
use ECidade\Educacao\Escola\Censo\Helpers\Pessoa as PessoaHelper;
use ECidade\Educacao\Escola\Censo\Identificacao\Model\Pessoa;
use ECidade\Educacao\Escola\Model\Aluno;
use ECidade\Educacao\Escola\Model\CensoMunicipio;
use ECidade\Educacao\Escola\Model\ProfissionalEscola;

/**
 * Class PessoaBuilder
 * @package ECidade\Educacao\Escola\Censo\Identificacao\Builder
 */
class PessoaBuilder
{
    /**
     * @var Pessoa
     */
    private $pessoa;
    /**
     * @var ProfissionalEscola
     */
    private $profissionalEscola;
    /**
     * @var Aluno
     */
    private $alunoEscola;
    /**
     * @var CensoMunicipio
     */
    private $municipioInstituicao;

    /**
     * @param ProfissionalEscola $profissionalEscola
     */
    public function setDadosProfissional(ProfissionalEscola $profissionalEscola)
    {
        $this->profissionalEscola = $profissionalEscola;
    }

    /**
     * @param Aluno $aluno
     */
    public function setDadosAluno(Aluno $aluno)
    {
        $this->alunoEscola = $aluno;
    }

    /**
     * @return Pessoa
     */
    public function build()
    {
        $this->create();
        if (!empty($this->profissionalEscola)) {
            $this->buildProfissional();
        }

        if (!empty($this->alunoEscola)) {
            $this->buildAluno();
        }
        return $this->pessoa;
    }

    private function create()
    {
        $this->pessoa = new Pessoa();
    }

    /**
     * Seta os registro do profissional na classe Pessoa
     */
    private function buildProfissional()
    {
        $cgm = $this->profissionalEscola->getCgm();
        $this->pessoa->setCodigoPessoa(PessoaHelper::buildCodigoProfissional($cgm->getCpf()));
        $this->pessoa->setCpf($cgm->getCpf());
        //this->pessoa->setNis(trim($this->profissionalEscola->getNis()));
        $this->pessoa->setNacionalidade($this->profissionalEscola->getNacionalidade());

        $nome = $cgm->getNomeCompleto();
        if (empty($nome)) {
            $nome = $cgm->getNome();
        }
        $nome = DBString::removerAcentuacao($nome);
        $this->pessoa->setNome(mb_strtoupper($nome));
        $dataNascimento = $cgm->getDataNascimento();
        if (!empty($dataNascimento)) {
            $this->pessoa->setDataNascimento(DBDate::format($dataNascimento));
        }

        $this->buildFiliacao($cgm->getNomeMae(), $cgm->getNomePai());

        $codigoMunicipioNascimento = $this->profissionalEscola->getCensoMunicipioNascimento();
        if ($this->profissionalEscola->getNacionalidade() === 3) {
            $codigoMunicipioNascimento = $this->municipioInstituicao->getCodigo();
        }
        $this->pessoa->setCodigoMunicipioNascimento($codigoMunicipioNascimento);
    }

    /**
     * Seta os registros do Aluno na classe Pessoa
     */
    private function buildAluno()
    {
        $this->pessoa->setCodigoPessoa(PessoaHelper::buildCodigoAluno($this->alunoEscola->getCodigo()));
        $this->pessoa->setNacionalidade($this->alunoEscola->getNacionalidade());
        $this->pessoa->setCpf(trim($this->alunoEscola->getCpf()));
        $this->pessoa->setCertidaoNascimento($this->alunoEscola->getMatriculaCeridao());
        //$this->pessoa->setNis(trim($this->alunoEscola->getNis()));
        $this->pessoa->setNome(mb_strtoupper(DBString::removerAcentuacao($this->alunoEscola->getNome())));
        $this->pessoa->setDataNascimento($this->alunoEscola->getDataNascimento()->getDate(DBDate::DATA_PTBR));
        $this->buildFiliacao($this->alunoEscola->getMae(), $this->alunoEscola->getPai());

        $codigoMunicipioNascimento = $this->alunoEscola->getCensoMunicipioNascimento();
        if ($this->alunoEscola->getNacionalidade() === 3) {
            $codigoMunicipioNascimento = $this->municipioInstituicao->getCodigo();
        }
        $this->pessoa->setCodigoMunicipioNascimento($codigoMunicipioNascimento);
        $this->pessoa->setInep(trim($this->alunoEscola->getCodigoInep()));
    }

    /**
     * @param string $nomeMae
     * @param string $nomePai
     */
    private function buildFiliacao($nomeMae, $nomePai)
    {
        $nomeMae = mb_strtoupper(DBString::removerAcentuacao($nomeMae));
        $nomePai = mb_strtoupper(DBString::removerAcentuacao($nomePai));
        if (empty($nomeMae) && !empty($nomePai)) {
            $this->pessoa->setFiliacao1($nomePai);
        }
        if (!empty($nomeMae) && empty($nomePai)) {
            $this->pessoa->setFiliacao1($nomeMae);
        }
        if (!empty($nomeMae) && !empty($nomePai)) {
            $this->pessoa->setFiliacao1($nomeMae);
            $this->pessoa->setFiliacao2($nomePai);
        }
    }

    /**
     * @param array $linha
     * @return Pessoa
     */
    public function buildFromLine(array $linha)
    {
        $this->create();
        $this->pessoa->setCodigoPessoa($linha[0]);
        $this->pessoa->setCpf($linha[1]);
        $this->pessoa->setCertidaoNascimento($linha[2]);
        //$this->pessoa->setNis($linha[3]);
        $this->pessoa->setNome($linha[3]);
        $this->pessoa->setDataNascimento($linha[4]);
        $this->pessoa->setFiliacao1($linha[5]);
        $this->pessoa->setFiliacao2($linha[6]);
        $this->pessoa->setCodigoMunicipioNascimento($linha[7]);
        $this->pessoa->setInep($linha[8]);
        return $this->pessoa;
    }

    /**
     * @param CensoMunicipio $municipioInstituicao
     */
    public function setMunicipio(CensoMunicipio $municipioInstituicao)
    {
        $this->municipioInstituicao = $municipioInstituicao;
    }
}
