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

namespace ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Builder;

use DBDate;
use DBString;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\Registro00;

class Registro00Builder
{

    const ESFERA_FEDERAL = 1;
    const ESFERA_ESTADUAL = 2;
    const ESFERA_MUNICIPAL = 3;

    /**
     * @var Registro00
     */
    private $registro;

    /**
     * @var array
     */
    private $dadosEscola;

    /**
     * @var array
     */
    private $telefones;

    /**
     * @var array
     */
    private $datasCalendarioEscolar;

    public function __construct()
    {
        $this->registro = new Registro00();
    }

    public function create()
    {
        $this->registro = new Registro00();
    }

    public function addDadosCenso($dados)
    {
        $this->dadosEscola = $dados;
    }

    public function addDadosTelefones($telefones)
    {
        $this->telefones = $telefones;
    }

    public function build()
    {
        $this->create();
        $this->addEscola();
        $this->addTelefones();
        $this->addDatasCalendarioEscolar();

        return $this->registro;
    }

    private function addTelefones()
    {
        foreach ($this->telefones as $index => $telefone) {
            $this->registro->setDdd($telefone->iDDD);
            if ($index == 0) {
                $this->registro->setTelefone($telefone->iNumero);
            } else {
                $this->registro->setOutroTelefone($telefone->iNumero);
            }
        }
    }

    private function addEscola()
    {
        $this->registro->setCodigo($this->dadosEscola['ed18_i_codigo']);
        $this->registro->setCodigoInep($this->dadosEscola['ed18_c_codigoinep']);
        $this->registro->setSituacaoFuncionamento($this->dadosEscola['ed18_i_funcionamento']);
        $this->registro->setNomeEscola(DBString::removerAcentuacao($this->dadosEscola['ed18_c_nome']));
        $this->registro->setCep($this->dadosEscola['ed18_c_cep']);
        $this->registro->setEndereco(DBString::removerAcentuacao($this->dadosEscola['j14_nome']));
        $this->registro->setNumero($this->dadosEscola['ed18_i_numero']);
        $this->registro->setComplemento(DBString::removerAcentuacao($this->dadosEscola['ed18_c_compl']));
        $this->registro->setBairro(DBString::removerAcentuacao($this->dadosEscola['j13_descr']));
        $this->registro->setUf($this->dadosEscola['ed18_i_censouf']);
        $this->registro->setMunicipio($this->dadosEscola['ed18_i_censomunic']);
        $this->registro->setDistrito($this->dadosEscola['ed262_i_coddistrito']);
        $this->registro->setEmail(mb_strtoupper($this->dadosEscola['ed18_c_email']));
        $this->registro->setCodigoOrgaoRegionalEnsino($this->dadosEscola['ed263_i_codigocenso']);
        $this->registro->setDependenciaAdministrativa($this->dadosEscola['ed18_c_mantenedora']);
        $this->registro->setZonaEscola($this->dadosEscola['ed18_c_local']);
        $this->registro->setCategoriaEscolaPrivada($this->dadosEscola['ed18_i_categprivada']);
        $this->registro->setLocalizacaoDiferenciada($this->dadosEscola['ed18_i_locdiferenciada']);
        $this->registro->setRegulamentacao($this->dadosEscola['ed18_i_credenciamento']);
        $this->registro->setCnpjEscolaPrivada($this->dadosEscola['ed18_i_cnpjmantprivada']);

        // Define esfera administrativa apenas quando Regulamentação for: 1 Credenciada ou 2 Em Tramitação
        if (in_array($this->registro->getRegulamentacao(), array(1,2))) {
            $esfera = $this->dadosEscola['ed18_i_esferaadministrativa'];
            switch ($esfera) {
                case self::ESFERA_FEDERAL:
                    $this->registro->setEsferaFederal(1);
                    break;
                case self::ESFERA_ESTADUAL:
                    $this->registro->setEsferaEstadual(1);
                    break;
                case self::ESFERA_MUNICIPAL:
                    $this->registro->setEsferaMunicipal(1);
                    break;
            }
        }
    }

    public function addDadosCalendarioEscolar(array $datasCalendarioEscolar)
    {
        $this->datasCalendarioEscolar = $datasCalendarioEscolar;
    }

    public function addDatasCalendarioEscolar()
    {
        $data = $this->datasCalendarioEscolar['data_inicio'];
        if (!empty($data)) {
            $data = DBDate::format($data);
        }
        $this->registro->setDataIncicioAnoLetivo($data);


        $data = $this->datasCalendarioEscolar['data_final'];
        if (!empty($data)) {
            $data = DBDate::format($data);
        }
        $this->registro->setDataFinalAnoLetivo($data);
    }
}
