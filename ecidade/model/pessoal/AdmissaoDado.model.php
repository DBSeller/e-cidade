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

class AdmissaoDado
{
    // Hipótese legal para contratação de trabalhador temporário.
    const NAO_INFORMADO = '';
    const NECESSIDADE_PERMISSAO_TRANSITORIA_PERMANENTE = 1;
    const DEMANDA_COMPLEMENTAR_SERVICO = 2;

    /**
     * @var array
     */
    const DESCRICOES_HIPOTESES = [
        self::NAO_INFORMADO => 'Não informado',
        self::NECESSIDADE_PERMISSAO_TRANSITORIA_PERMANENTE => self::NECESSIDADE_PERMISSAO_TRANSITORIA_PERMANENTE . " - Necessidade de substituição transitória de pessoal permanente",
        self::DEMANDA_COMPLEMENTAR_SERVICO => self::DEMANDA_COMPLEMENTAR_SERVICO . " - Demanda complementar de serviços"
    ];

    /**
     * @var integer
     */
    private $mesDataBase;

    /**
     * @var integer
     */
    private $hipoteseLegalTrabTemp;

    /**
     * @var date
     */
    private $dataNomeacao;


    public function __construct($matricula = null)
    {
        if ($matricula) {
            $instituicao = db_getsession("DB_instit");
            $daoAdmissaoDados = new cl_rhadmissaodado;
            $campos =["h25_sequencial",
                    "h25_nrdispositivo",
                    "h25_nomeacao",
                    "h25_irfonte", 
                    "h25_referenciair", 
                    "h25_portariaaposentadoria",
                    "h25_dataaposentadoria",
                    "h25_contaraposentadoria",
                    "h25_processoaposentadoria",
                    "h25_nrprocessoaposentadoria",
                    "h25_anoprocessoaposentadoria",
                    "h25_portariaexoneracao",
                    "h25_dataexoneracao",
                    "h25_contarexoneracao",
                    "h25_processoexoneracao",
                    "h25_nrprocessoexoneracao",
                    "h25_anoprocessoexoneracao",
                    "h25_portariareintegracao",
                    "h25_datareintegracao",
                    "h25_processoreintegracao",
                    "h25_nrprocessoreintegracao",
                    "h25_anoprocessoreintegracao",
                    "h25_regist",
                    "h25_instit",
                    "h25_publicacaoexoneracao",
                    "h25_hipleg",
                    "h25_dtbase"
            ];
            $campos = implode(',',$campos);
            $sqlAdmissaoDados = $daoAdmissaoDados->sql_query("",$campos,"","h25_regist = {$matricula} and h25_instit = {$instituicao} ");
            $resultadoAdmissaoDados = \db_query($sqlAdmissaoDados);

            if (!$resultadoAdmissaoDados) {
                throw new \DBException("Houve um erro ao buscar dados admissional da matrícula {$matricula}.");
            }

            if (pg_num_rows($resultadoAdmissaoDados) > 0) {
                $admissaoDado = \db_utils::fieldsMemory($resultadoAdmissaoDados, 0);    
                $this->setMesDataBase($admissaoDado->h25_dtbase);
                $this->setHipoteseLegalTrabTemp($admissaoDado->h25_hipleg);
                $this->setDataNomeacao($admissaoDado->h25_nomeacao);
            }


        }
    }


     public static function getDescricoesHipotese()
    {
        return AdmissaoDado::DESCRICOES_HIPOTESES;
    }

    /**
     * Get the value of mesDataBase
     *
     * @return  integer
     */ 
    public function getMesDataBase()
    {
        return $this->mesDataBase;
    }

    /**
     * Set the value of mesDataBase
     *
     * @param  integer  $mesDataBase
     *
     * @return  self
     */ 

     public function setMesDataBase($mesDataBase)
    {
        $this->mesDataBase = $mesDataBase;
    }

    /**
     * Get the value of mesDataBase
     *
     * @return  integer
     */ 
    public function getHipoteseLegalTrabTemp()
    {
        return $this->hipoteseLegalTrabTemp;
    }

    /**
     * Set the value of mesDataBase
     *
     * @param  integer  $mesDataBase
     *
     * @return  self
     */ 

     public function setHipoteseLegalTrabTemp($hipoteseLegalTrabTemp)
    {
        $this->hipoteseLegalTrabTemp = $hipoteseLegalTrabTemp;
    }


    /**
     * Get the value of dataNomeacao
     *
     * @return  date
     */ 
    public function getDataNomeacao()
    {
        return $this->dataNomeacao;
    }

    /**
     * Set the value of dataNomeacao
     *
     * @param  date  $dataNomeacao
     *
     */ 
    public function setDataNomeacao($dataNomeacao)
    {
        $this->dataNomeacao = $dataNomeacao;

    }
}
