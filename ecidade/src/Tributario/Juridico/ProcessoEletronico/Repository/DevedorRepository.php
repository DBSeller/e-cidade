<?php

namespace ECidade\Tributario\Juridico\ProcessoEletronico\Repository;

use ECidade\Tributario\Juridico\ProcessoEletronico\Domain\Devedor;
use ECidade\Tributario\Juridico\ProcessoEletronico\Enum\TipoListaEnum;
use ECidade\Tributario\Juridico\ProcessoEletronico\Factory\DevedorFactory;

/**
 * Classe Responsavel por buscar dados no banco
 *
 * Class DevedorRepository
 * @package ECidade\Tributario\Juridico\ProcessoEletronico\Repository
 */
class DevedorRepository
{


    /**
     * @param $cgm
     * @return Devedor
     * @throws \BusinessException
     */
    public function getDevedor($cgm)
    {
        $sSqlEnvolvidos = " select distinct cgm.* from cgm where z01_numcgm = {$cgm}";
        $rsEnvolvidos = db_query($sSqlEnvolvidos);

        if (!$rsEnvolvidos) {
            throw new \BusinessException("Não foi possível buscar o devedor da parte envolvida");
        }

        $oDadosEnvol = \db_utils::fieldsMemory($rsEnvolvidos, 0);

        $iCodigoLogradouro = $this->getCodigoLogradouroDevedor($cgm);


        $oDevedor = new Devedor();

        $oDevedor->setNome($oDadosEnvol->z01_nome);
        $oDevedor->setEndereco($oDadosEnvol->z01_ender);
        $oDevedor->setNumero($oDadosEnvol->z01_numero);
        $oDevedor->setBairro($oDadosEnvol->z01_bairro);
        $oDevedor->setMunicipio($oDadosEnvol->z01_munic);
        $oDevedor->setComplemento($oDadosEnvol->z01_compl);
        $oDevedor->setUf($oDadosEnvol->z01_uf);
        $oDevedor->setCep($oDadosEnvol->z01_cep);
        $oDevedor->setGenero($oDadosEnvol->z01_sexo);
        $oDevedor->setPai($oDadosEnvol->z01_pai);
        $oDevedor->setMae($oDadosEnvol->z01_mae);
        $oDevedor->setNaturalidade($oDadosEnvol->z01_naturalidade);
        $oDevedor->setDataNascimento($oDadosEnvol->z01_nasc);
        $oDevedor->setCgccpf($oDadosEnvol->z01_cgccpf);
        $oDevedor->setCodigoLogradouro($iCodigoLogradouro);
        $oDevedor->setTipoDevedor(TipoListaEnum::CGM);

        return $oDevedor;
    }

    /**
     * Busca o devedor pela inscricao
     *
     * @param $inscricao
     * @throws \BusinessException
     */
    public function getDevedorInscricao($inscricao)
    {
        $sSqlEnvolvidos = "select issbase.q02_inscr                            as inscricao, 
                q02_numcgm                                                     as cgm,
                ruastipo.j88_sigla                                             as tipo_logradouro,
                ruas.j14_nome                                                  as nome_logradouro,
                issruas.q02_numero                                             as numero_logradouro,
                issruas.q02_compl                                              as complemento_logradouro,
                bairro.j13_descr                                               as bairro_logradouro,
                (select munic from db_config where prefeitura = true)          as cidade_logradouro,
                (select uf    from db_config where prefeitura = true)          as uf_logradouro,
                (select * from fc_cep_dne(issbase.q02_inscr, 3))               as cep_logradouro
             from issbase 
                 left  join issruas   on issruas.q02_inscr    = issbase.q02_inscr
                 left  join ruas      on ruas.j14_codigo      = issruas.j14_codigo 
                 left  join ruastipo  on ruastipo.j88_codigo  = ruas.j14_tipo
                 left  join issbairro on issbairro.q13_inscr  = issbase.q02_inscr
                 left  join bairro    on issbairro.q13_bairro = bairro.j13_codi 
             where issbase.q02_inscr = {$inscricao};";

        $rsEnvolvidos = db_query($sSqlEnvolvidos);

        if (!$rsEnvolvidos) {
            throw new \BusinessException("Não foi possível buscar o devedor da parte envolvida");
        }

        $oDadosEnvol = \db_utils::fieldsMemory($rsEnvolvidos, 0);

        $iCodigoLogradouro = $this->getCodigoLogradouroDevedor($oDadosEnvol->cgm);

        $oDevedor = new Devedor();

        $oDevedor->setNome($oDadosEnvol->z01_nome);
        $oDevedor->setEndereco($oDadosEnvol->nome_logradouro);
        $oDevedor->setNumero($oDadosEnvol->numero_logradouro);
        $oDevedor->setBairro($oDadosEnvol->bairro_logradouro);
        $oDevedor->setMunicipio($oDadosEnvol->cidade_logradouro);
        $oDevedor->setComplemento($oDadosEnvol->complemento_logradouro);
        $oDevedor->setUf($oDadosEnvol->uf_logradouro);
        $oDevedor->setCep($oDadosEnvol->cep_logradouro);
        $oDevedor->setGenero($oDadosEnvol->z01_sexo);
        $oDevedor->setPai($oDadosEnvol->z01_pai);
        $oDevedor->setMae($oDadosEnvol->z01_mae);
        $oDevedor->setNaturalidade($oDadosEnvol->z01_naturalidade);
        $oDevedor->setDataNascimento($oDadosEnvol->z01_nasc);
        $oDevedor->setCgccpf($oDadosEnvol->z01_cgccpf);
        $oDevedor->setCodigoLogradouro($iCodigoLogradouro);
        $oDevedor->setTipoLogradouro($oDadosEnvol->tipo_logradouro);
        $oDevedor->setTipoDevedor(TipoListaEnum::INSCRICAO);

        return $oDevedor;
    }


    /**
     * Busca  devedor pelo banco matricula
     *
     * @param $matricula
     * @return Devedor
     * @throws \BusinessException
     */
    public function getDevedorMatricula($matricula)
    {

        $sSqlEnvolvidos = "select 
               j01_matric   as matricula, 
               j01_numcgm   as cgm,
               j88_sigla    as tipo_logradouro,
               j14_nome     as nome_logradouro,
               (select j39_numero 
                   from iptuconstr 
                   where j39_idprinc = true and 
                         j39_matric  = iptubase.j01_matric)          as numero_logradouro,
                (select j39_compl 
                   from iptuconstr 
                   where j39_idprinc = true and 
                         j39_matric  = iptubase.j01_matric)          as complemento_logradouro,
               j13_descr                                             as bairro_logradouro,
               (select munic from db_config where prefeitura = true) as cidade_logradouro,
               (select uf    from db_config where prefeitura = true) as uf_logradouro,
               (select * from fc_cep_dne(j01_matric, 2))             as cep_logradouro,
               cgm.*
           from iptubase 
              inner join cgm      on j01_numcgm = z01_numcgm 
              inner join lote     on j01_idbql  = j34_idbql 
              inner join bairro   on j34_bairro = j13_codi 
              inner join testada  on j34_idbql  = j36_idbql 
              inner join testpri  on j34_idbql  = j49_idbql and 
                                     j36_face   = j49_face 
              inner join ruas     on j49_codigo = j14_codigo 
              inner join ruastipo on j14_tipo   = j88_codigo  
           where j01_matric = {$matricula};";

        $rsEnvolvidos = db_query($sSqlEnvolvidos);


        if (!$rsEnvolvidos) {
            throw new \BusinessException("Não foi possível buscar o devedor da parte envolvida");
        }


        $oDadosEnvol = \db_utils::fieldsMemory($rsEnvolvidos, 0);

        $iCodigoLogradouro = $this->getCodigoLogradouroDevedor($oDadosEnvol->cgm);

        $oDevedor = new Devedor();

        $oDevedor->setNome($oDadosEnvol->z01_nome);
        $oDevedor->setEndereco($oDadosEnvol->nome_logradouro);
        $oDevedor->setNumero($oDadosEnvol->numero_logradouro);
        $oDevedor->setBairro($oDadosEnvol->bairro_logradouro);
        $oDevedor->setMunicipio($oDadosEnvol->cidade_logradouro);
        $oDevedor->setComplemento($oDadosEnvol->complemento_logradouro);
        $oDevedor->setUf($oDadosEnvol->uf_logradouro);
        $oDevedor->setCep($oDadosEnvol->cep_logradouro);
        $oDevedor->setGenero($oDadosEnvol->z01_sexo);
        $oDevedor->setPai($oDadosEnvol->z01_pai);
        $oDevedor->setMae($oDadosEnvol->z01_mae);
        $oDevedor->setNaturalidade($oDadosEnvol->z01_naturalidade);
        $oDevedor->setDataNascimento($oDadosEnvol->z01_nasc);
        $oDevedor->setCgccpf($oDadosEnvol->z01_cgccpf);
        $oDevedor->setTipoDevedor(TipoListaEnum::MATRICULA);
        $oDevedor->setCodigoLogradouro($iCodigoLogradouro);
        $oDevedor->setTipoLogradouro($oDadosEnvol->tipo_logradouro);

        return $oDevedor;
    }


    /**
     * Busca o devedor do auto de infracao
     *
     * @param $auto
     * @return Devedor|null
     * @throws \BusinessException
     */
    public function getDevedorAuto($auto)
    {

        $sSqlOrigemAuto = "select 
               case when y54_numcgm <> '' then 'C'
               case when y53_matric <> '' then 'M'
               case when y52_inscr  <> '' then 'I'
               
               ELSE 'C' as origem
            from  auto 
               inner join autocgm   on   y50_codauto =  y54_codauto
               left join automatric  on  y50_codauto  =  y53_codauto
               left join autoinscr on  y50_codauto  =  y53_codauto
               where y50_codauto = {$auto}";

        $rsAuto = db_query($sSqlOrigemAuto);

        $oDadosAuto = \db_utils::fieldsMemory($rsAuto, 0);

        if (!$oDadosAuto) {
            throw new \BusinessException("Não foi possível origem do auto de infracao do devedor");
        }

        return DevedorFactory::create($oDadosAuto->origem);
    }

    /**
     * Busca o código do logradouro a ser utilizado
     *
     * @param $cgm
     * @return string
     */
    public function getCodigoLogradouroDevedor($cgm)
    {
        $oDaoCadEnderLocal = \db_utils::getDao("cadenderlocal");
        $sSql = $oDaoCadEnderLocal->sql_query_cgmendereco(null, "db85_ruastipo", null, "z07_numcgm = {$cgm}");

        $rsCadEnder = $oDaoCadEnderLocal->sql_record($sSql);
        $iCodigoLogradouro = "";

        if ($oDaoCadEnderLocal->numrows > 0) {
            $iCodigoLogradouro = \db_utils::fieldsMemory($rsCadEnder, 0)->db85_ruastipo;
        }

        return  $iCodigoLogradouro;
    }
}