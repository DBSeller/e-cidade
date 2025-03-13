<?php

use Classes\PostgresMigration;

class M12227MigracaoReceitaNiteroi extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {


        $this->execute("select fc_putsession('__disable_audit__', 'on')");
        $sSqlPl = <<<SQL
        drop function if exists fc_avaliacao_previsao_receita(iconta integer, ianousu integer);
create function fc_avaliacao_previsao_receita(iconta integer, ianousu integer,
  OUT conta integer,
  OUT esfera_orcamentaria varchar,
  OUT unidade_orcamentaria varchar,
  OUT identificacao_uso varchar,
  OUT previsao_tipo_detalhamento varchar,
  OUT grupo varchar,
  OUT especificacao varchar,
  OUT anousu integer,
  OUT recurso integer,
  OUT real2017 numeric,
  OUT provavel2018 numeric,
  OUT previsao2019 numeric
)
  returns record
language plpgsql
as $$
declare

resposta      record;
  grupo         varchar;
  codigoPreenchimento integer;
  nValor        numeric default 0;
  especificacao varchar;
  nivel_estrut  char(1);

begin

  real2017     := 0;
  provavel2018 := 0;
  previsao2019 := 0;
  conta  := iConta;
  anousu := iAnousu;

  select max(c06_avaliacaogruporesposta)
    into codigoPreenchimento
    from avaliacaogruporespostaconta
   where  c06_conta = iConta
    and c06_ano   = iAnousu;

  if not found then
    return;
  end if;

  select substr(c60_estrut, 1, 1) as estrut
    into nivel_estrut
    from conplanoorcamento
   where c60_codcon = iConta
    and c60_anousu = iAnousu;

  for resposta in
      select db107_sequencial,
         db103_identificadorcampo,
         db102_identificadorcampo,
         case when db103_avaliacaotiporesposta in (1, 3) then db104_valorresposta else db106_resposta end as db106_resposta
  from avaliacaopergunta
         inner join avaliacaogrupopergunta on db102_sequencial = db103_avaliacaogrupopergunta
         inner join avaliacaoperguntaopcao on db103_sequencial = db104_avaliacaopergunta
         inner join avaliacaoresposta      on db104_sequencial = db106_avaliacaoperguntaopcao
         inner join avaliacaogrupoperguntaresposta on db106_sequencial = db108_avaliacaoresposta
         inner join avaliacaogruporesposta on db107_sequencial = db108_avaliacaogruporesposta
         inner join avaliacaogruporespostaconta on c06_avaliacaogruporesposta = db107_sequencial
  where c06_conta = iConta
    and c06_ano   = iAnousu
    and c06_avaliacaogruporesposta = codigoPreenchimento
  loop


    if resposta.db103_identificadorcampo IN ('previsaoReal2017','previsaoProvavel2018','previsaoPrevisao2019') then

      nValor := cast(coalesce(case when resposta.db106_resposta = '' then null else resposta.db106_resposta end, '0') as numeric);
      if nivel_estrut = '9' and nValor > 0 then
        nValor := nValor * -1;
      end if;

    end if ;

    if resposta.db103_identificadorcampo = 'previsaoReal2017' then
       real2017 := nValor;
    end if;

    if resposta.db103_identificadorcampo = 'previsaoProvavel2018' then
      provavel2018 := nValor;
    end if;
    if resposta.db103_identificadorcampo = 'previsaoPrevisao2019' then
      previsao2019 := nValor;
    end if;

    if resposta.db103_identificadorcampo = 'grupo_fonte_recurso' then

      grupo := resposta.db106_resposta;

    end if;

    if resposta.db103_identificadorcampo = 'especificacao_fonte' then
      especificacao := resposta.db106_resposta;
    end if;

    if resposta.db103_identificadorcampo = 'esferaOrcamentaria' then
      esfera_orcamentaria := resposta.db106_resposta;
    end if;

    if resposta.db103_identificadorcampo = 'unidadeOrcamentaria' then
      unidade_orcamentaria := resposta.db106_resposta;
    end if;

    if resposta.db103_identificadorcampo = 'previsaoTipoDetalhamento' then
      previsao_tipo_detalhamento := resposta.db106_resposta;
    end if;

    if resposta.db103_identificadorcampo = 'id_uso' then
      identificacao_uso := resposta.db106_resposta;
    end if;

  end loop;
  recurso := cast(grupo||especificacao as integer);
  return ;
end;
$$;

alter function fc_avaliacao_previsao_receita(integer, integer,   
  OUT integer,
  OUT varchar,
  OUT varchar,
  OUT varchar,
  OUT varchar,
  OUT varchar,
  OUT varchar,
  OUT integer,
  OUT integer,
  OUT numeric,
  OUT numeric,
  OUT numeric ) owner to postgres;

SQL;

        $this->execute($sSqlPl);


        $rsInstituicao = $this->query("select db21_codcli from configuracoes.db_config where db21_codcli = 7107");
        if ($rsInstituicao->rowCount() == 0) {;
            return;
        }

        $ppaversao = 17;

        $this->execute("delete  from taborc where k02_anousu = 2019;");
        $this->execute("delete  from ppaintegracaoreceita where o122_anousu = 2019");
        $this->execute("delete  from ppaintegracao where o123_ppaversao = {$ppaversao} and not exists(select 1 from ppaintegracaodespesa where o121_ppaintegracao = o123_sequencial)");
        $this->execute("delete  from orcreceita where o70_anousu = 2019");
        $this->execute("delete from ppaestimativareceita where o06_ppaversao = {$ppaversao}");
        $sqlReceita =
            <<<SQL

    select c60_codcon, 
           c60_estrut, 
           c60_descr,
           c60_anousu,  
           c61_instit, 
           previsao_receita.* 
      from conplanoorcamento c 
           inner join conplanoorcamentoanalitica ca on ca.c61_codcon = c.c60_codcon 
                                                   and c60_anousu = ca.c61_anousu 
           inner join orcfontes on o57_codfon = c61_codcon 
                               and o57_anousu = c61_anousu  
           inner join fc_avaliacao_previsao_receita(ca.c61_codcon,ca.c61_anousu) as previsao_receita on conta = ca.c61_codcon 
                                                                                                   and anousu = ca.c61_anousu 
     where c61_anousu = 2019 
     order by c60_estrut;
SQL;

        $rsReceitas = $this->query($sqlReceita);
        $receitas = $rsReceitas->fetchAll(PDO::FETCH_CLASS);
        $cp = '000';


        $this->execute('select fc_startsession();');
        /**
             * INClUIR DADOS DA RECEITA
             */
        foreach ($receitas as $i => $receita) {


            if ($receita->previsao2019 == 0) {
                continue;
            }

            $codigoRecurso = '100';

            if (   ! empty($receita->identificacao_uso) &&
                   ! empty($receita->previsao_tipo_detalhamento) &&
                   ! empty($receita->grupo) &&
                   ! empty($receita->especificacao) ) {

                $recurso = "select o15_codigo ";
                $recurso .= "  from orcamento.orctiporec";
                $recurso .= " where o15_loaidentificadoruso = {$receita->identificacao_uso}";
                $recurso .= "   and o15_loatipo             = {$receita->previsao_tipo_detalhamento}";
                $recurso .= "   and o15_loagrupo            = {$receita->grupo}";
                $recurso .= "   and o15_loaespecificacao    = '{$receita->especificacao}'";

                $rsRecurso = $this->query($recurso);
                if ($rsRecurso->rowCount() > 0) {
                    $codigoRecurso = $rsRecurso->fetchColumn();
                }
            }

            $orgao = substr($receita->unidade_orcamentaria, 0, 2 );
            $unidade = substr($receita->unidade_orcamentaria, 2, 2 );
            $insertOrcreceita = "insert into orcamento.orcreceita 
              (
               o70_anousu,             
               o70_codrec,             
               o70_codfon,            
               o70_codigo,             
               o70_valor,              
               o70_reclan,             
               o70_instit,             
               o70_concarpeculiar,     
               o70_datacriacao,        
               o70_orcorgao ,          
               o70_orcunidade,         
               o70_esferaorcamentaria 
              ) values (
                 2019, 
                 nextval('orcreceita_o70_codrec_seq'),
                {$receita->c60_codcon},
                {$codigoRecurso},
                {$receita->previsao2019},
                true, 
                {$receita->c61_instit},
                '000',
                null, 
                {$orgao}, 
                {$unidade},
                '{$receita->esfera_orcamentaria}' 
                )";

            $this->execute($insertOrcreceita);
        }

    }
}
