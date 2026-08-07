<?php

namespace ECidade\Educacao\Escola\Censo\SituacaoAluno\Dados;

use ECidade\Educacao\Escola\Censo\Censo;
use Escola;
use Exception;
use stdClass;

/**
 * Class BuscaDadosEscola2016
 * @package ECidade\Educacao\Escola\Censo\SituacaoAluno\Dados
 */
class BuscaDadosEscola2016 implements BuscarDados
{
    /**
     * @var stdClass
     */
    private $oDados = null;

    /**
     * BuscaDadosEscola2016 constructor.
     * @param Censo $censo
     * @param Escola $escola
     * @throws Exception
     */
    public function __construct(Censo $censo, Escola $escola)
    {
        $sCampos = "distinct escolagestorcenso.ed325_email as email_gestor ";
        $sCampos .= ",escola.ed18_c_codigoinep as codigo_escola_inep ";
        $sCampos .= ",case                                  ";
        $sCampos .= "   when  cgmrh.z01_cgccpf is not null  ";
        $sCampos .= "     then cgmrh.z01_cgccpf             ";
        $sCampos .= "   else cgmcgm.z01_cgccpf              ";
        $sCampos .= " end as cpf_gestor            ";
        $sCampos .= ",case                                  ";
        $sCampos .= "   when cgmrh.z01_nome is not null     ";
        $sCampos .= "     then cgmrh.z01_nomecomple         ";
        $sCampos .= "   else cgmcgm.z01_nomecomple          ";
        $sCampos .= " end as nome_gestor         ";
        $sCampos .= ",case when trim(atividaderh.ed01_c_descr) = 'DIRETOR' then 1 else 2 end as cargo_gestor";
        $sCampos .= ",'' as separador_final ";
        $sWhere = "escola.ed18_i_codigo = {$escola->getCodigo()} ";

        $oDaoEscolaGestorCenso = new \cl_escolagestorcenso();
        $sSqlEscolaGestorCenso = $oDaoEscolaGestorCenso->sql_query_dados_gestor(
            null,
            $sCampos,
            null,
            $sWhere
        );
        $rsEscolaGestorCenso = db_query($sSqlEscolaGestorCenso);

        if (!$rsEscolaGestorCenso) {
            throw new \DBException("Erro ao buscar os dados da Escola.");
        }

        if (pg_num_rows($rsEscolaGestorCenso) == 0) {
            throw new Exception(
                "Dados do gestor da escola não cadastrados. Acesse: Cadastros -> Dados da Escola -> aba Gestor."
            );
        }

        $this->oDados = \db_utils::fieldsMemory($rsEscolaGestorCenso, 0);
    }

    /**
     * @return DadosEscola2016
     */
    public function getDados()
    {
        $oValidacaoEscola = new DadosEscola2016();
        $oValidacaoEscola->popular($this->oDados);

        return $oValidacaoEscola;
    }
}
