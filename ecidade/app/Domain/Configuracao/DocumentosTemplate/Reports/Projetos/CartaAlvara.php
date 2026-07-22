<?php
namespace App\Domain\Configuracao\DocumentosTemplate\Reports\Projetos;

use App\Domain\Configuracao\DocumentosTemplate\ProcessaDocumentoTemplate;
use \db_utils;
use \cl_obrasalvara;

class CartaAlvara extends ProcessaDocumentoTemplate
{
  
    private $codigoObra;
    const GRUPOTEMPLATE = 14;
  
    public function __construct($codigoObra)
    {
        parent::__construct(self::GRUPOTEMPLATE);
        $this->codigoObra        = $codigoObra;
        $this->setNomeDocumento('CartaAlvara');
    }
    
    /**
     * @return array
     */
    public function configuraDadosVariaveis()
    {
        $obrasAlvara = new cl_obrasalvara();
        $campos = "sequencial_alvara,
                   ano_sequencial_alvara,
                   validade_alvara,
                   data_inicio_alvara,
                   data_final_alvara,
                   cgm,
                   nome_proprietario,
                   nome_outros_proprietarios,
                   cpf_cnpj_proprietario,
                   matricula_imovel,
                   data_expedicao_extenso, 
                   logradouro,
                   numero,
                   bairro,
                   complemento,
                   sql,
                   pql,
                   setor_pql,
                   quadra_pql,
                   lote_pql,
                   cod_obra,
                   engenheiro,
                   seq_alvara,
                   crea,
                   area_total,
                   area_total_atual,
                   unidade,
                   pavimentos,
                   protocolo,
                   data_protocolo,
                   data_aprovacao,
                   endereco_obra,
                   numero_endereco_obra,
                   bairro_endereco_obra,
                   complemento_endereco_obra,
                   nome_servidor,
                   cargo_servidor,
                   matricula_servidor,
                   carac_ocupacao,
                   carac_tipo_construcao,
                   carac_tipo_lancamento,
                   observacoes,
                   data_expedicao,
                   cgm_resp_projeto,
                   cpf_resp_projeto,
                   nome_resp_projeto,
                   crea_resp_projeto,
                   prof_resp_projeto,
                   cgm_resp_acomp_obra,
                   cpf_resp_acomp_obra,
                   nome_resp_acomp_obra,
                   crea_resp_acomp_obra,
                   prof_resp_acomp_obra,
                   nome_obra,
                   art_rrt_responsavel_projeto,
                   art_rrt_responsavel_tecnico,
                   cgm_responsavel_execucao,
                   nome_responsavel_execucao,
                   cpf_responsavel_execucao";
      
        $sqlVariaveisObras = $obrasAlvara->sql_query_cartaAlvaraDadosTemplate($campos, $this->codigoObra);
        $rsObrasVariaveis  = $obrasAlvara->sql_record($sqlVariaveisObras);
        if (!$rsObrasVariaveis || $obrasAlvara->numrows == 0) {
            throw new \Exception("Não foi possível buscar os dados");
        }

        $aDadosObras               = (array) db_utils::fieldsMemory($rsObrasVariaveis, 0);
        $aDadosObras['db_anousu']  = db_getsession("DB_anousu");
        $aDadosObras['db_datausu'] = date('d/m/Y');
        $aDadosObras['db_horausu'] = date('H:i');
        
        return $aDadosObras;
    }
}
