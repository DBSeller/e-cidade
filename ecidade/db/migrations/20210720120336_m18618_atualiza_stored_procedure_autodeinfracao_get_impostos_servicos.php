<?php

use Classes\PostgresMigration;

class M18618AtualizaStoredProcedureAutodeinfracaoGetImpostosServicos extends PostgresMigration
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
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->execute(<<<SQL

drop function if exists fc_autodeinfracao_getImpostosServicos(integer, integer);
drop type if exists tp_impostosServicos_autodeinfracao;
create type tp_impostosServicos_autodeinfracao as ( codigo_levantamento         integer,
                                                    datainicio_levantamento     date,
                                                    datafim_levantamento        date,
                                                    valorhistorico_levantamento float8,
                                                    valorcorrigido_levantamento float8,
                                                    valormulta_levantamento     float8,
                                                    valorjuros_levantamento     float8,
                                                    valortotal_levantamento     float8
                                                  );

create or replace function fc_autodeinfracao_getImpostosServicos(integer, integer)  returns tp_impostosServicos_autodeinfracao as
$$
declare

  iCodigoAutoInfracao                 alias for $1;
  iCodigoLevantamento                 alias for $2;

  iReceitaParaCalculo                 integer;
  iReceitaLevantamento                integer;
  iReceitaLevantamentoEspontaneo      integer;
  fValorCorrigido                     float8 default 0;
  fValorHistoricoLevantamento         float8 default 0;
  fValorCorrigidoLevantamento         float8 default 0;
  fValorJuros                         float8 default 0;
  fValorJurosLevantamento             float8 default 0;
  fValorMulta                         float8 default 0;
  fValorMultaLevantamento             float8 default 0;
  fValorTotalLevantamento             float8 default 0;
  dDataOperacao                       date;
  dDataVencParam                      date;
  dDataVencAuto                       date;
  iTipoDataOperacao                   integer;
  iTipoDataVencimento                 integer;

  iInstitSessao                       integer;
  iAnoSessao                          integer;
  dDataHoje                           date;

  lRaise                              boolean default false;

  r_levantamento                      record;
  r_levantamentoValores               record;

  rtp_impostosServicos_autodeinfracao tp_impostosServicos_autodeinfracao%ROWTYPE;

begin

  /**
   * Busca Dados Sessão
   */
  iInstitSessao := cast(fc_getsession('DB_instit') as integer);
  iAnoSessao    := cast(fc_getsession('DB_anousu') as integer);
  lRaise        := ( case when fc_getsession('DB_debugon') is null then false else true end );

  perform fc_debug(''||lpad(' ',100));
  perform fc_debug('Retorna Valores do Imposto'                      );
  perform fc_debug(' iCodigoAutoInfracao -> ' || iCodigoAutoInfracao );
  perform fc_debug(' iCodigoLevantamento -> ' || iCodigoLevantamento );

  select CURRENT_date into dDataHoje;

  select y32_receit, y32_receitexp, coalesce(y32_tipodataoperacao, 1) as y32_tipodataoperacao, coalesce(y32_tipodatavencimento, 1) as y32_tipodatavencimento
    into iReceitaLevantamento, iReceitaLevantamentoEspontaneo, iTipoDataOperacao, iTipoDataVencimento
    from parfiscal
   where y32_instit = iInstitSessao;

  /**
   * Varre os levantamentos vinculados ao auto
   */
  for r_levantamento in

    select *
      from autolevanta
           inner join levanta on y60_codlev = y117_levanta
     where y117_auto    = iCodigoAutoInfracao
  loop

    if r_levantamento.y60_codlev <> iCodigoLevantamento then
      continue;
    end if;

    /**
     * Vincula os dados padrões do levantamento
     */
    perform fc_debug('');
    perform fc_debug('Lendo levantamentos vinculados ao auto:' );
    perform fc_debug(' y60_codlev -> ' || r_levantamento.y60_codlev );
    perform fc_debug(' y60_dtini  -> ' || r_levantamento.y60_dtini  );
    perform fc_debug(' y60_dtfim  -> ' || r_levantamento.y60_dtfim  );
    perform fc_debug('');

    rtp_impostosServicos_autodeinfracao.codigo_levantamento     := coalesce( r_levantamento.y60_codlev, null );
    rtp_impostosServicos_autodeinfracao.datainicio_levantamento := coalesce( r_levantamento.y60_dtini,  null );
    rtp_impostosServicos_autodeinfracao.datafim_levantamento    := coalesce( r_levantamento.y60_dtfim,  null );

      /**
       * Busca valores vinculados por levantamento
       */
      for r_levantamentoValores in
        select *
          from levvalor
               left join levantanotas on y79_sequencia = y63_sequencia
         where y63_codlev = r_levantamento.y60_codlev
      loop

        iReceitaParaCalculo = iReceitaLevantamento;
        if r_levantamento.y60_espontaneo is true then
          iReceitaParaCalculo = iReceitaLevantamentoEspontaneo;
        end if;

        /**
         * Seta data de operação que será utilizada na juros e na multa
         */
        select (extract(year from r_levantamentoValores.y63_dtvenc)||'-'||extract(month from r_levantamentoValores.y63_dtvenc)||'-01')::date
          into dDataOperacao;

        /**
         * Seta data de vencimento conforme o parametro parfiscal.y32_tipodatavencimento
         * 1 = pelo data de vencimento do levantamento, 2 - pela data de vencimento do auto de infracao
         */

        dDataVencParam = r_levantamentoValores.y63_dtvenc;

        if iTipoDataVencimento = 2 then 
           select y50_dtvenc 
             into dDataVencAuto
             from autolevanta inner join auto on y117_auto = y50_codauto 
            where y117_levanta = r_levantamentoValores.y63_codlev
              and y50_dtvenc is not null
            order by y117_sequencial desc limit 1;

           if FOUND then
              dDataVencParam = dDataVencAuto;
           end if;
        end if;
       
        perform fc_debug('Executando fc_corre utilizando parametros:'                               );
        perform fc_debug('iReceitaParaCalculo              -> ' || iReceitaParaCalculo              );
        perform fc_debug('r_levantamentoValores.y63_dtvenc -> ' || r_levantamentoValores.y63_dtvenc );
        perform fc_debug('r_levantamentoValores.y63_saldo  -> ' || r_levantamentoValores.y63_saldo  );
        perform fc_debug('dDataHoje                        -> ' || dDataHoje                        );
        perform fc_debug('iAnoSessao                       -> ' || iAnoSessao                       );
        perform fc_debug('r_levantamentoValores.y63_dtvenc -> ' || dDataVencParam                   );

        /**
         * Busca Valor Corrigido
         */
        select coalesce (round( fc_corre( iReceitaParaCalculo,
                                          r_levantamentoValores.y63_dtvenc,
                                          r_levantamentoValores.y63_saldo,
                                          dDataHoje,
                                          iAnoSessao,
                                          dDataVencParam ), 2 ), 0 ) into fValorCorrigido;

        perform fc_debug('Valor Corrigido POR levvalor: ' || coalesce( fValorCorrigido, 0 ) );
        perform fc_debug('');

        fValorCorrigidoLevantamento = fValorCorrigidoLevantamento + fValorCorrigido;

        /**
         * Busca Percentual de Juros
         */
        perform fc_debug('Executando FC_JUROS utilizando parametros:'                               );
        perform fc_debug('iReceitaParaCalculo              -> ' || iReceitaParaCalculo              );
        perform fc_debug('r_levantamentoValores.y63_dtvenc -> ' || r_levantamentoValores.y63_dtvenc );
        perform fc_debug('dataoper                         -> ' || dDataOperacao                    );
        perform fc_debug('iAnoSessao                       -> ' || iAnoSessao                       );

        select coalesce (round( fc_juros( iReceitaParaCalculo,
                                          dDataVencParam,
                                          dDataHoje,
                                          dDataOperacao,
                                          'f',
                                          iAnoSessao ), 2 ), 0 ) into fValorJuros;

        perform fc_debug('Percentual dos Juros POR levvalor: ' || coalesce( fValorJuros, 0 ) );
        perform fc_debug('Valor dos Juros POR levvalor: '      || coalesce( (fValorCorrigido * fValorJuros) , 0 ) );
        perform fc_debug('');

        fValorJurosLevantamento = fValorJurosLevantamento + ( fValorCorrigido * fValorJuros );

        /**
         * Busca Multa
         */
        perform fc_debug('Executando FC_MULTA utilizando parametros:'                               );
        perform fc_debug('iReceitaParaCalculo              -> ' || iReceitaParaCalculo              );
        perform fc_debug('r_levantamentoValores.y63_dtvenc -> ' || r_levantamentoValores.y63_dtvenc );
        perform fc_debug('dataoper                         -> ' || dDataOperacao                    );
        perform fc_debug('iAnoSessao                       -> ' || iAnoSessao                       );

        select coalesce (round( fc_multa( iReceitaParaCalculo,
                                          dDataVencParam,
                                          dDataHoje,
                                          dDataOperacao,
                                          iAnoSessao ), 2 ), 0 ) into fValorMulta;

        perform fc_debug('Percentual da Multa POR levvalor: ' || coalesce( fValorMulta, 0 ) );
        perform fc_debug('Valor da Multa POR levvalor: '      || coalesce( (fValorCorrigido * fValorMulta) , 0 ) );
        perform fc_debug('');

        fValorMultaLevantamento = fValorMultaLevantamento + ( fValorCorrigido * fValorMulta );

        /**
         * Valor Historico
         */
        fValorHistoricoLevantamento = fValorHistoricoLevantamento + r_levantamentoValores.y63_saldo;

        /**
         * Valor Total do lançamento
         */
        fValorTotalLevantamento = fValorCorrigidoLevantamento + fValorJurosLevantamento + fValorMultaLevantamento;

      end loop; --loop por levvalor

      rtp_impostosServicos_autodeinfracao.valorhistorico_levantamento = round( fValorHistoricoLevantamento, 2 );
      rtp_impostosServicos_autodeinfracao.valorcorrigido_levantamento = round( fValorCorrigidoLevantamento, 2 );
      rtp_impostosServicos_autodeinfracao.valormulta_levantamento     = round( fValorMultaLevantamento,     2 );
      rtp_impostosServicos_autodeinfracao.valorjuros_levantamento     = round( fValorJurosLevantamento,     2 );
      rtp_impostosServicos_autodeinfracao.valortotal_levantamento     = round( fValorTotalLevantamento,     2 );

  end loop; --loop por levantamento vinculado ao auto

  return rtp_impostosServicos_autodeinfracao;

end;
$$ language 'plpgsql';

SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL


drop function if exists fc_autodeinfracao_getImpostosServicos(integer, integer);
drop type if exists tp_impostosServicos_autodeinfracao;

create type tp_impostosServicos_autodeinfracao as ( codigo_levantamento         integer,
                                                    datainicio_levantamento     date,
                                                    datafim_levantamento        date,
                                                    valorhistorico_levantamento float8,
                                                    valorcorrigido_levantamento float8,
                                                    valormulta_levantamento     float8,
                                                    valorjuros_levantamento     float8,
                                                    valortotal_levantamento     float8
                                                  );

create or replace function fc_autodeinfracao_getImpostosServicos(integer, integer)  returns tp_impostosServicos_autodeinfracao as
$$
declare

  iCodigoAutoInfracao                 alias for $1;
  iCodigoLevantamento                 alias for $2;

  iReceitaParaCalculo                 integer;
  iReceitaLevantamento                integer;
  iReceitaLevantamentoEspontaneo      integer;
  fValorCorrigido                     float8 default 0;
  fValorHistoricoLevantamento         float8 default 0;
  fValorCorrigidoLevantamento         float8 default 0;
  fValorJuros                         float8 default 0;
  fValorJurosLevantamento             float8 default 0;
  fValorMulta                         float8 default 0;
  fValorMultaLevantamento             float8 default 0;
  fValorTotalLevantamento             float8 default 0;
  dDataOperacao                       date;

  iInstitSessao                       integer;
  iAnoSessao                          integer;
  dDataHoje                           date;

  lRaise                              boolean default false;

  r_levantamento                      record;
  r_levantamentoValores               record;

  rtp_impostosServicos_autodeinfracao tp_impostosServicos_autodeinfracao%ROWTYPE;

begin

  /**
   * Busca Dados Sessão
   */
  iInstitSessao := cast(fc_getsession('DB_instit') as integer);
  iAnoSessao    := cast(fc_getsession('DB_anousu') as integer);
  lRaise        := ( case when fc_getsession('DB_debugon') is null then false else true end );

  perform fc_debug(''||lpad(' ',100));
  perform fc_debug('Retorna Valores do Imposto'                      );
  perform fc_debug(' iCodigoAutoInfracao -> ' || iCodigoAutoInfracao );
  perform fc_debug(' iCodigoLevantamento -> ' || iCodigoLevantamento );

  select CURRENT_date into dDataHoje;

  select y32_receit, y32_receitexp
    into iReceitaLevantamento, iReceitaLevantamentoEspontaneo
    from parfiscal
   where y32_instit = iInstitSessao;

  /**
   * Varre os levantamentos vinculados ao auto
   */
  for r_levantamento in

    select *
      from autolevanta
           inner join levanta on y60_codlev = y117_levanta
     where y117_auto    = iCodigoAutoInfracao
  loop

    if r_levantamento.y60_codlev <> iCodigoLevantamento then
      continue;
    end if;

    /**
     * Vincula os dados padrões do levantamento
     */
    perform fc_debug('');
    perform fc_debug('Lendo levantamentos vinculados ao auto:' );
    perform fc_debug(' y60_codlev -> ' || r_levantamento.y60_codlev );
    perform fc_debug(' y60_dtini  -> ' || r_levantamento.y60_dtini  );
    perform fc_debug(' y60_dtfim  -> ' || r_levantamento.y60_dtfim  );
    perform fc_debug('');

    rtp_impostosServicos_autodeinfracao.codigo_levantamento     := coalesce( r_levantamento.y60_codlev, null );
    rtp_impostosServicos_autodeinfracao.datainicio_levantamento := coalesce( r_levantamento.y60_dtini,  null );
    rtp_impostosServicos_autodeinfracao.datafim_levantamento    := coalesce( r_levantamento.y60_dtfim,  null );

      /**
       * Busca valores vinculados por levantamento
       */
      for r_levantamentoValores in
        select *
          from levvalor
               left join levantanotas on y79_sequencia = y63_sequencia
         where y63_codlev = r_levantamento.y60_codlev
      loop

        iReceitaParaCalculo = iReceitaLevantamento;
        if r_levantamento.y60_espontaneo is true then
          iReceitaParaCalculo = iReceitaLevantamentoEspontaneo;
        end if;

        /**
         * Seta data de operação que será utilizada na juros e na multa
         */
        select (extract(year from r_levantamentoValores.y63_dtvenc)||'-'||extract(month from r_levantamentoValores.y63_dtvenc)||'-01')::date
          into dDataOperacao;

        perform fc_debug('Executando fc_corre utilizando parametros:'                               );
        perform fc_debug('iReceitaParaCalculo              -> ' || iReceitaParaCalculo              );
        perform fc_debug('r_levantamentoValores.y63_dtvenc -> ' || r_levantamentoValores.y63_dtvenc );
        perform fc_debug('r_levantamentoValores.y63_saldo  -> ' || r_levantamentoValores.y63_saldo  );
        perform fc_debug('dDataHoje                        -> ' || dDataHoje                        );
        perform fc_debug('iAnoSessao                       -> ' || iAnoSessao                       );
        perform fc_debug('r_levantamentoValores.y63_dtvenc -> ' || r_levantamentoValores.y63_dtvenc );

        /**
         * Busca Valor Corrigido
         */
        select coalesce (round( fc_corre( iReceitaParaCalculo,
                                          r_levantamentoValores.y63_dtvenc,
                                          r_levantamentoValores.y63_saldo,
                                          dDataHoje,
                                          iAnoSessao,
                                          r_levantamentoValores.y63_dtvenc ), 2 ), 0 ) into fValorCorrigido;

        perform fc_debug('Valor Corrigido POR levvalor: ' || coalesce( fValorCorrigido, 0 ) );
        perform fc_debug('');

        fValorCorrigidoLevantamento = fValorCorrigidoLevantamento + fValorCorrigido;

        /**
         * Busca Percentual de Juros
         */
        perform fc_debug('Executando FC_JUROS utilizando parametros:'                               );
        perform fc_debug('iReceitaParaCalculo              -> ' || iReceitaParaCalculo              );
        perform fc_debug('r_levantamentoValores.y63_dtvenc -> ' || r_levantamentoValores.y63_dtvenc );
        perform fc_debug('dataoper                         -> ' || dDataOperacao                    );
        perform fc_debug('iAnoSessao                       -> ' || iAnoSessao                       );

        select coalesce (round( fc_juros( iReceitaParaCalculo,
                                          r_levantamentoValores.y63_dtvenc,
                                          dDataHoje,
                                          dDataOperacao,
                                          'f',
                                          iAnoSessao ), 2 ), 0 ) into fValorJuros;

        perform fc_debug('Percentual dos Juros POR levvalor: ' || coalesce( fValorJuros, 0 ) );
        perform fc_debug('Valor dos Juros POR levvalor: '      || coalesce( (fValorCorrigido * fValorJuros) , 0 ) );
        perform fc_debug('');

        fValorJurosLevantamento = fValorJurosLevantamento + ( fValorCorrigido * fValorJuros );

        /**
         * Busca Multa
         */
        perform fc_debug('Executando FC_MULTA utilizando parametros:'                               );
        perform fc_debug('iReceitaParaCalculo              -> ' || iReceitaParaCalculo              );
        perform fc_debug('r_levantamentoValores.y63_dtvenc -> ' || r_levantamentoValores.y63_dtvenc );
        perform fc_debug('dataoper                         -> ' || dDataOperacao                    );
        perform fc_debug('iAnoSessao                       -> ' || iAnoSessao                       );

        select coalesce (round( fc_multa( iReceitaParaCalculo,
                                          r_levantamentoValores.y63_dtvenc,
                                          dDataHoje,
                                          dDataOperacao,
                                          iAnoSessao ), 2 ), 0 ) into fValorMulta;

        perform fc_debug('Percentual da Multa POR levvalor: ' || coalesce( fValorMulta, 0 ) );
        perform fc_debug('Valor da Multa POR levvalor: '      || coalesce( (fValorCorrigido * fValorMulta) , 0 ) );
        perform fc_debug('');

        fValorMultaLevantamento = fValorMultaLevantamento + ( fValorCorrigido * fValorMulta );

        /**
         * Valor Historico
         */
        fValorHistoricoLevantamento = fValorHistoricoLevantamento + r_levantamentoValores.y63_saldo;

        /**
         * Valor Total do lançamento
         */
        fValorTotalLevantamento = fValorCorrigidoLevantamento + fValorJurosLevantamento + fValorMultaLevantamento;

      end loop; --loop por levvalor

      rtp_impostosServicos_autodeinfracao.valorhistorico_levantamento = round( fValorHistoricoLevantamento, 2 );
      rtp_impostosServicos_autodeinfracao.valorcorrigido_levantamento = round( fValorCorrigidoLevantamento, 2 );
      rtp_impostosServicos_autodeinfracao.valormulta_levantamento     = round( fValorMultaLevantamento,     2 );
      rtp_impostosServicos_autodeinfracao.valorjuros_levantamento     = round( fValorJurosLevantamento,     2 );
      rtp_impostosServicos_autodeinfracao.valortotal_levantamento     = round( fValorTotalLevantamento,     2 );

  end loop; --loop por levantamento vinculado ao auto

  return rtp_impostosServicos_autodeinfracao;

end;
$$ language 'plpgsql';

SQL
        );
    }
}

