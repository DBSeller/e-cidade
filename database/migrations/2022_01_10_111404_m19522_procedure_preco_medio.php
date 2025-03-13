<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class M19522ProcedurePrecoMedio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<sql
            insert into db_sysarquivo values (1010851, 'matestoqueitemanulacao', 'Guarda o vinculo de um matestoqueitem que está em uma anulação da ordem de compra mas ja tem saldo atendido com um matestoqueitem que está sendo usado o saldo para anular a entrada.', 'm104', '2022-01-13', 'matestoqueitem anulacao', 2, 'f', 't', 't', 't' );
            insert into db_sysarqmod values (13,1010851);
            insert into db_sysarqarq values (1020,1010851);
            insert into db_syscampo values(1013606,'m104_matestoqueitemanular','int8','código matestoqueitem que está sendo anulado entrada da ordem de compras','0', 'Estoque Item Anulação',11,'f','f','f',1,'text','Estoque Item Anulação');
            insert into db_syscampo values(1013607,'m104_matestoqueitemusasaldo','int8','código matestoqueitem que está sendo usado o saldo deste para anular outra entrada da ordem de compra','0', 'Estoque Item Com Saldo',11,'f','f','f',1,'text','Estoque Item Com Saldo');
            insert into db_syscampo values(1013608,'m104_codigo','int8','código sequencial da tabela','0', 'Código',11,'f','f','f',1,'text','Código');
            insert into db_sysarqcamp values(1010851,1013608,1,0);
            insert into db_sysarqcamp values(1010851,1013606,2,0);
            insert into db_sysarqcamp values(1010851,1013607,3,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010851,1013608,1,1013608);
            insert into db_sysforkey values(1010851,1013606,1,1020,0);
            insert into db_sysforkey values(1010851,1013607,1,1020,0);
            insert into db_sysindices values(1008707,'matestoqueitemanulacao_m104_codigo_in',1010851,'1');
            insert into db_syssequencia values(1001028, 'matestoqueitemanulacao_m104_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1001028 where codarq = 1010851 and codcam = 1013608;
sql
        );


        Schema::create('material.matestoqueitemanulacao', function (Blueprint $table) {
            $table->bigInteger('m104_codigo', true);
            $table->bigInteger('m104_matestoqueitemanular');
            $table->foreign('m104_matestoqueitemanular')->references('m71_codlanc')->on('material.matestoqueitem');
            $table->bigInteger('m104_matestoqueitemusasaldo');
            $table->foreign('m104_matestoqueitemusasaldo')->references('m71_codlanc')->on('material.matestoqueitem');
        });

        DB::connection()->getPdo()->exec(<<<sql
drop function fc_calculaprecomedio;
create function fc_calculaprecomedio(integer, integer, float8, integer)
    returns numeric as
$$

declare
    iCodigoMatestoqueiniMei        alias for $1;
    iCodigoMatestoqueini           alias for $2;
    nQuantidadeMovimento           alias for $3;
    iCodigoInimeiOrigem            alias for $4;

    nPrecoMedio                    numeric default 0;
    iMaterial                      integer;
    iInstituicao                   integer;
    iAlmoxarifado                  integer;
    nValorEstoque                  numeric;
    nQuantidadeEstoque             numeric default 0;
    nValorEstoqueDiferenca         numeric default 0;
    nQuantidadeEstoqueDiferenca    numeric default 0;
    iTipoMovimento                 integer;
    iCodigoEstoque                 integer;
    iCodigoMovimento               integer;
    iCodigoEntradaItem             integer;
    nValorUnitario                 numeric default 0;
    dtMovimento                    date;
    tHora                          timestamp;
    tHoraMovimento                 time;
    lTemPrecoMedio                 boolean default false;
    lServico                       boolean;
    iDepto                         integer;
    iVinculoMatestoqueitem         integer;
    saldoEstoque                   numeric;
    quantidadeEstoque              numeric;
    valorEntradaItem               numeric;
    quantidadeEntradaItem          numeric;
begin
    iInstituicao = fc_getsession('DB_instit');
    if iInstituicao is null then
        raise exception 'Instituicao não informada.';
    end if;

    /**
     * Consultamos o codigo do material,
     * atraves da tabela matestoqueitem, com o campo new.m82_matestoqueitem.
     */
    select m70_codmatmater,
           (case when  m71_quant > 0 then
                     coalesce(m71_valor/m71_quant, 0)
                 else 0 end),
           m71_servico,
           m70_coddepto,
           m71_codlanc,
           m70_codigo
    into iMaterial,
        nValorUnitario,
        lServico,
        iAlmoxarifado,
        iCodigoEntradaItem,
        iCodigoEstoque
    from matestoqueitem
             inner join matestoque       on m70_codigo  = m71_codmatestoque
             inner join matestoqueinimei on m71_codlanc = m82_matestoqueitem
    where m82_codigo  = iCodigoMatestoqueiniMei;

   /**
    * Consultamos o tipo da movimentacao
    */
   select m80_codtipo,
          m81_tipo,
          to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS'),
          m80_data,
          m80_hora,
          m80_coddepto,
          instit
     into iCodigoMovimento,
          iTipoMovimento,
          tHora,
          dtMovimento,
          tHoraMovimento,
          iDepto,
          iInstituicao
     from matestoqueini
          inner join matestoquetipo on m81_codtipo = m80_codtipo
          inner join DB_DEPART on m80_coddepto     = coddepto
    where m80_codigo = iCodigoMatestoqueini;

    /**
     * Soma a quantidade em estoque do item na instituicao
     *
     */
    select coalesce(sum(CASE when m81_tipo = 1 then m82_quant when m81_tipo = 2 then m82_quant*-1 end), 0),
           round(coalesce(sum(CASE when m81_tipo = 1 then m82_quant*m89_valorunitario
                                   when m81_tipo = 2 then m82_quant*m89_valorunitario*-1 end), 0) , 2)
    into nQuantidadeEstoque,
        nValorEstoque
    from matestoque
             inner join db_depart          on m70_coddepto       = coddepto
             inner join matestoqueitem     on m70_codigo         = m71_codmatestoque
             inner join matestoqueinimei   on m82_matestoqueitem = m71_codlanc
             inner join matestoqueinimeipm on m82_codigo         = m89_matestoqueinimei
             inner join matestoqueini      on m82_matestoqueini  = m80_codigo
             inner join matestoquetipo     on m81_codtipo        = m80_codtipo
    where instit           = iInstituicao
      and m70_codmatmater  = iMaterial
      and to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS') <= tHora
      and m82_codigo <> iCodigoMatestoqueiniMei
      and m70_coddepto = iAlmoxarifado
      and m81_tipo not in(4,5)
      and m71_servico is false;

    /**
      * verificamos se o item possui no mesmo movimento entradas para o mesmo item de estoque
      */
    SELECT coalesce(sum(CASE when m81_tipo = 1 then round(m82_quant, 2)
                             when m81_tipo = 2 then round(m82_quant,2)*-1 end), 0) as saldodif,
           round(coalesce(sum(CASE when m81_tipo = 1 then round(round(round(m82_quant, 2)*m89_valorunitario, 5), 2)
                                   when m81_tipo = 2 then round(round(m82_quant, 2)*round( case when m81_codtipo in (4, 19) then m89_valorunitario else m89_precomedio end, 5), 2)*-1 end), 0), 2)
    into nQuantidadeEstoqueDiferenca,
        nValorEstoqueDiferenca
    from matestoqueinimei
             inner join matestoqueitem     on m71_codlanc          = m82_matestoqueitem
             inner join matestoque         on m71_codmatestoque    = m70_codigo
             inner join matestoqueinimeipm on m89_matestoqueinimei = m82_codigo
             inner join matestoqueini      on m82_matestoqueini    = m80_codigo
             inner join matestoquetipo     on m80_codtipo          = m81_codtipo
    where m70_codmatmater   = iMaterial
      and m82_matestoqueini = iCodigoMatestoqueini
      and m82_codigo        > iCodigoMatestoqueiniMei
      and m70_coddepto = iAlmoxarifado
      and m81_tipo not in(4,5)
      and m71_servico is false;
    nQuantidadeEstoque := nQuantidadeEstoque - nQuantidadeEstoqueDiferenca;
    nValorEstoque      := nValorEstoque      - nValorEstoqueDiferenca;
    /**
     * Verificamos o ultimo preco medio da data do material para o item.
     */

    select m85_precomedio
    into nPrecoMedio
    from matmaterprecomedio
    where m85_matmater = iMaterial
      and m85_instit   = iInstituicao
      and m85_coddepto = iAlmoxarifado
      and to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') <= tHora
    order by to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') desc limit 1;

    if ( not found or nPrecoMedio = 0 ) and iCodigoMovimento in (8) then
        select m85_precomedio
        into nPrecoMedio
        from matmaterprecomedio
        where m85_matmater = iMaterial
          and m85_instit   = iInstituicao
          and m85_precomedio > 0
          and m85_coddepto = ( select m80_coddepto
                               from matestoqueini
                                        inner join matestoqueinil  inil  on inil.m86_matestoqueini   = matestoqueini.m80_codigo
                                        inner join matestoqueinill inill on inill.m87_matestoqueinil = inil.m86_codigo
                               where inill.m87_matestoqueini = iCodigoMatestoqueini limit 1)
          and to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') <= tHora
        order by to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') desc limit 1;

        update matmaterprecomedio
        set m85_precomedio = nPrecoMedio
        where m85_matmater = iMaterial
          and m85_instit   = iInstituicao
          and m85_coddepto = iAlmoxarifado
          and to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') <= tHora;
    end if;

    if nQuantidadeEstoque = 0 then
        nValorEstoque := 0;
    end if;
    if  found then
        lTemPrecoMedio = true;
    end if;
    nPrecoMedio := coalesce(nPrecoMedio, 0);
    /**
     * Verificamos as entradas no estoque (refletem no calculo do preço medio)
     * algumas entradas, que na verdade são cancelamentos de saidas, devem entrar no estoque
     * pelo preco médio atual, não alterando o preço do calculo médio.
     */

    if iCodigoMovimento in(8, 1, 3, 4, 18, 19, 12, 14, 15, 25) then
        /**
         * como o sistema já inclui as informações do estoque na hora de verificarmos o preço médio,
         * devemos deduzir a quantidade da entrada, (nQuantidade - m82_quant). a regra do calculo do preço médio é:
         * pegamos a quantidade anterior em estoque, e multiplicamos pelo ultimo preço médio.
         * - Somamos a nova entrada (quantidade e valor da entrada,) e dividimos o valor encontrado pela quantidade
         * encontrada. o resultado dessa divisão, encontramos o preço médio.
         */
        --nValorEstoque      = round(nQuantidadeEstoque * nPrecoMedio, 2);

        -- verificamos se é uma anulação da entrada da ordem de compra ou manual - devemos recalcular o preco medio
        if iCodigoMovimento in (4, 19) then
            select m104_matestoqueitemanular
            into iVinculoMatestoqueitem
            from matestoqueitemanulacao
            where m104_matestoqueitemusasaldo = iCodigoEntradaItem;

            if found then
                select (case when  m71_quant > 0 then
                                 coalesce(m71_valor/m71_quant, 0)
                             else 0 end)
                into nValorUnitario
                from matestoqueitem where m71_codlanc = iVinculoMatestoqueitem;
            end if;

            nQuantidadeEstoque = nQuantidadeEstoque - nQuantidadeMovimento;
            nValorEstoque      = nValorEstoque - (nQuantidadeMovimento*nValorUnitario);
        elsif iCodigoMovimento in (18) then
            select m89_valorunitario
            into nValorUnitario
            from matestoqueinimei
                join matestoqueinimeipm ON matestoqueinimeipm.m89_matestoqueinimei = matestoqueinimei.m82_codigo
            where matestoqueinimei.m82_codigo = iCodigoInimeiOrigem;

            if not found then
                raise exception 'Erro ao buscar valor do atendimento de requisição.';
            end if;

            nQuantidadeEstoque = nQuantidadeEstoque  + nQuantidadeMovimento;
            nValorEstoque      = round(nValorEstoque + (nQuantidadeMovimento*nValorUnitario), 5);
        else
            nQuantidadeEstoque = nQuantidadeEstoque  + nQuantidadeMovimento;
            nValorEstoque      = round(nValorEstoque + (nQuantidadeMovimento*nValorUnitario), 5);
        end if;

        nPrecoMedio = 0;
        if nQuantidadeEstoque > 0 then
            nPrecoMedio    = nValorEstoque / nQuantidadeEstoque;
        end if;
        /**
         * Excluimos o preço medio para o movimento/hora
         */
        delete from matmaterprecomedio
        where m85_matmater = imaterial
          and m85_instit   = iInstituicao
          and m85_coddepto = iAlmoxarifado
          and to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') >= tHora;

        insert into matmaterprecomedio
        (m85_sequencial,
         m85_matmater,
         m85_instit,
         m85_precomedio,
         m85_data,
         m85_hora,
         m85_coddepto
        )
        values (nextval('matmaterprecomedio_m85_sequencial_seq'),
                iMaterial,
                iInstituicao,
                nPrecoMedio,
                dtMovimento,
                tHoraMovimento,
                iAlmoxarifado
               );
    elsif iTipoMovimento = 2 and iCodigoMovimento not in(8, 9, 998) then
        nValorUnitario = nPrecoMedio;
    elsif iCodigoMovimento in(7, 6, 9) then
        nValorUnitario = nPrecoMedio;
    elsif iCodigoMovimento in (21) then
        /**
         * caso  a transferencia seja confirmada,
         * temos que fazer a entrada no estoque ao mesmo valor da saida, pois a movimentacao no estoque
         * nao existe a movimentacao de valores.
         * o codigo da transferencia está na tabela mastoqueinil/matestoqueinill
         */
        select m89_precomedio
        into nPrecoMedio
        from matestoqueinill
                 inner join matestoqueinil     on m87_matestoqueinil = m86_codigo
                 inner join matestoqueinimei   on m86_matestoqueini  = m82_matestoqueini
                 inner join matestoqueinimeipm on m82_codigo         = m89_matestoqueinimei
                 inner join matestoqueitem     on m82_matestoqueitem = m71_codlanc
                 inner join matestoque         on m70_codigo         = m71_codmatestoque
        where m70_codmatmater   = iMaterial
          and m87_matestoqueini = iCodigoMatestoqueini
          and m71_servico is false;

        nValorUnitario = nPrecoMedio;
    elsif iCodigoMovimento in (998, 999) then
        select coalesce(sum(CASE when m81_tipo = 1 then m82_quant when m81_tipo = 2 then m82_quant*-1 end), 0),
               round(coalesce(sum(CASE when m81_tipo = 1 then m82_quant*m89_valorunitario
                                       when m81_tipo = 2 then m82_quant*m89_valorunitario*-1 end), 0) , 2)
        into nQuantidadeEstoque,
            nValorEstoque
        from matestoque
                 inner join db_depart          on m70_coddepto       = coddepto
                 inner join matestoqueitem     on m70_codigo         = m71_codmatestoque
                 inner join matestoqueinimei   on m82_matestoqueitem = m71_codlanc
                 inner join matestoqueinimeipm on m82_codigo         = m89_matestoqueinimei
                 inner join matestoqueini      on m82_matestoqueini  = m80_codigo
                 inner join matestoquetipo     on m81_codtipo        = m80_codtipo
        where instit           = iInstituicao
          and m70_codmatmater  = iMaterial
          and to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS') <= tHora
          and m82_codigo <> iCodigoMatestoqueiniMei
          and m70_coddepto = iAlmoxarifado
          and m81_tipo not in(4,5)
          and m81_codtipo not in(4, 19)
          and m71_servico is false;

        select sum(case
                       when m81_tipo = 1 then m82_quant * m89_valorunitario
                       when m81_tipo = 2 then (m82_quant * m89_valorunitario) * -1 end),
               sum(case when m81_tipo = 1 then m82_quant when m81_tipo = 2 then m82_quant * -1 end)
        into saldoEstoque,
            quantidadeEstoque
        from matestoqueinimei
                 inner join matestoqueitem on matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem
                 inner join matestoqueini on matestoqueini.m80_codigo = matestoqueinimei.m82_matestoqueini
                 inner join matestoqueinimeipm on m89_matestoqueinimei = m82_codigo
                 inner join matestoque on matestoque.m70_codigo = matestoqueitem.m71_codmatestoque
                 inner join matestoquetipo on matestoquetipo.m81_codtipo = matestoqueini.m80_codtipo
        where m71_codmatestoque = iCodigoEstoque
            and m80_codigo < iCodigoMatestoqueini;

        select m71_valor, m71_quant into valorEntradaItem, quantidadeEntradaItem from matestoqueitem where m71_codlanc = iCodigoEntradaItem;
        nValorUnitario = abs(valorEntradaItem - saldoEstoque);

        if iCodigoMovimento = 998 then
            nQuantidadeEstoque = quantidadeEstoque - nQuantidadeMovimento;

            if (nQuantidadeEstoque - quantidadeEntradaItem) <> 0 then
                nValorUnitario = 0.01;
            end if;
            nValorEstoque = round(nValorEstoque - (nQuantidadeMovimento*nValorUnitario), 2);
        elsif iCodigoMovimento = 999 then
            nValorUnitario = nValorUnitario + 0.01;
            nQuantidadeEstoque = quantidadeEstoque + nQuantidadeMovimento;
            if (nQuantidadeEstoque - quantidadeEntradaItem) <> 0 then
                nValorUnitario = nValorUnitario + ((quantidadeEstoque - quantidadeEntradaItem) * nPrecoMedio);
            end if;
            nValorEstoque      = round(nValorEstoque + (nQuantidadeMovimento*nValorUnitario), 2);
        end if;

        nPrecoMedio = nValorEstoque / nQuantidadeEstoque;
    end if;

    delete from matestoqueinimeipm where m89_matestoqueinimei = iCodigoMatestoqueiniMei;
    insert into matestoqueinimeipm
    (m89_sequencial,
     m89_matestoqueinimei,
     m89_precomedio,
     m89_valorunitario,
     m89_valorfinanceiro
    )
    values (nextval('matestoqueinimeipm_m89_sequencial_seq'),
            iCodigoMatestoqueiniMei,
            nPrecoMedio,
            nValorUnitario,
            round((nQuantidadeMovimento * nValorUnitario)::numeric , 2)
           );
    return nPrecoMedio;
end;
$$
language 'plpgsql';
sql
        );

        DB::connection()->getPdo()->exec(<<<sql
create or replace function fc_matestoqueinimei_inc()
  returns trigger as
$$

declare

  nPrecoMedio                    numeric default 0;
  iMaterial                      integer;
  iInstituicao                   integer;
  nValorEstoque                  numeric;
  nQuantidadeEstoque             numeric default 0;
  iTipoMovimento                 integer;
  iCodigoEstoque                 integer;
  iCodigoMovimento               integer;
  nValorUnitario                 numeric default 0;
  dtMovimento                    date;
  tHora                          timestamp;
  tHoraMovimento                 time;
  lTemPrecoMedio                 boolean default false;
  nQuantidadeMovimento           numeric default 0;
  rValoresAnterior               record;
  rValoresPosteriores            record;
  lServico                       boolean default false;
  iDepto                         integer;
  nQuantidadeSaidasPosteriores   numeric default 0;
  nQuantidadeEntradasPosteriores numeric default 0;
  nSaidasNoPeriodo               numeric default 0;
  nSaldoNoPeriodo                numeric default 0;
  nSaldoAposPeriodo              numeric default 0;
  sMensagemEstoque               varchar;
  lEntradaAposPeriodo            boolean default false;
begin

   iInstituicao = fc_getsession('DB_instit');
   if iInstituicao is null then
     raise exception 'Instituicao nao informada.';
   end if;


   /**
    * Consultamos o tipo da movimentacao
    */
   select m80_codtipo,
          m81_tipo,
          to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS'),
          m80_data,
          m80_hora,
          m80_coddepto,
          instit
     into iCodigoMovimento,
          iTipoMovimento,
          tHora,
          dtMovimento,
          tHoraMovimento,
          iDepto,
          iInstituicao
     from matestoqueini
          inner join matestoquetipo on m81_codtipo = m80_codtipo
          inner join DB_DEPART on m80_coddepto     = coddepto
          inner join matestoqueinimei on m80_codigo = m82_matestoqueini
          inner join matestoqueitem   on m82_matestoqueitem = m71_codlanc
          inner join matestoque       on m71_codmatestoque  = m70_codigo
    where m80_codigo = NEW.m82_matestoqueini;

    select m70_codmatmater,
           m71_servico
    into
        iMaterial,
        lServico
    from matestoqueitem
        join matestoque on m70_codigo = m71_codmatestoque
    where m71_codlanc = NEW.m82_matestoqueitem;

    /**
     * verifica se existe saldo no estoque para realizar a operacao (saldo cronologico.)
     * pois usuario pode retornar a data, termos uma saida do material antes de termos a entrada.....
     */
   raise notice 'codigo: % Hora: %', new.m82_codigo, tHora;
   if iTipoMovimento = 2 and lServico = false then

     raise notice 'tipo Movimento:% Operacao:%, Servico:%',iTipoMovimento, TG_OP,lServico;

      /**
       * percorremos todo a movimentação do estoque posterior a data do movimento
       */
      for rValoresAnterior in select m82_quant,
                          m81_tipo,
                          to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS') as dtmov
                     from matestoque
                          inner join db_depart        on m70_coddepto       = coddepto
                          inner join matestoqueitem   on m70_codigo         = m71_codmatestoque
                          inner join matestoqueinimei on m82_matestoqueitem = m71_codlanc
                          inner join matestoqueini    on m82_matestoqueini  = m80_codigo
                          inner join matestoquetipo   on m81_codtipo        = m80_codtipo
                    where instit           = iInstituicao
                     and m70_codmatmater  = iMaterial
                     and m70_coddepto     = iDepto
                     and m82_codigo       <> new.m82_codigo
                     and m81_tipo    in(1, 2)
                     and to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS') < tHora
                    order by to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS')

     loop

       raise notice 'Tipo: %, Quantidade:%',rValoresAnterior.m81_tipo,  rValoresAnterior.m82_quant;
       /**
        * Abatemos o valor do estoque do saldo no periodo, caso nao tenha nenhuma entrada, pois
        */
       if rValoresAnterior.m81_tipo = 1 then
         nQuantidadeEstoque = nQuantidadeEstoque + rValoresAnterior.m82_quant;
       elsif rValoresAnterior.m81_tipo = 2 then
         nQuantidadeEstoque = nQuantidadeEstoque - rValoresAnterior.m82_quant;
       end if;
     end loop;
     raise notice 'Antes No periodo: %, saidas no periodo:%', nQuantidadeEstoque, nSaidasNoPeriodo;
     nSaldoNoPeriodo    = nQuantidadeEstoque -  new.m82_quant;
     raise notice 'No periodo: %, apos Periodo:%', nSaldoNoPeriodo, nSaldoAposPeriodo;
     if (nSaldoNoPeriodo < 0 or nSaldoAposPeriodo  < 0) and lServico is false then


        raise exception 'Saldo total não disponível nesta data (%). Saldo disponivel: %. Servico: %',
                         to_char(dtMovimento, 'DD/MM/YYYY'),
                         nQuantidadeEstoque - nSaidasNoPeriodo, lServico;

     end if;
  end if;


    perform fc_calculaprecomedio(NEW.m82_codigo::integer,
                                 NEW.m82_matestoqueini::integer,
                                 NEW.m82_quant,
                                 NEW.m82_matestoqueinimeiorigem::integer);


    for rValoresPosteriores in select matestoqueinimei.*
	                             from matestoqueitem
	                                  inner join matestoqueinimei on m82_matestoqueitem = m71_codlanc
	                                  inner join matestoqueini    on m80_codigo         = m82_matestoqueini
	                                  inner join matestoque       on m71_codmatestoque  = m70_codigo
	                                  inner join db_depart        on m70_coddepto       = coddepto
	                            where m70_codmatmater  = iMaterial
	                             and instit            = iInstituicao
	                             and to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS') > tHora
	                           order by  to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS'), m82_codigo
	loop

            perform fc_calculaprecomedio(rValoresPosteriores.m82_codigo::integer,
                                         rValoresPosteriores.m82_matestoqueini::integer,
                                         rValoresPosteriores.m82_quant,
                                         rValoresPosteriores.m82_matestoqueinimeiorigem::integer);
end loop;

    /**
     * verifica se existe saldo no estoque para realizar a operacao (saldo cronologico.)
     * pois usuário pode retornar a data, termos uma saida do material antes de termos a entrada.....
     */

    if iTipoMovimento = 2 and TG_OP = 'INSERT' and lServico = false then

      /**
       * percorremos todo a movimentação do estoque posterior a data do movimento
       */
      for rValoresPosteriores in select m82_quant,
                          m81_tipo,
                          to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS') as dtmov
                     from matestoque
                          inner join db_depart        on m70_coddepto       = coddepto
                          inner join matestoqueitem   on m70_codigo         = m71_codmatestoque
                          inner join matestoqueinimei on m82_matestoqueitem = m71_codlanc
                          inner join matestoqueini    on m82_matestoqueini  = m80_codigo
                          inner join matestoquetipo   on m81_codtipo        = m80_codtipo
                    where instit           = iInstituicao
                     and m70_codmatmater  = iMaterial
                     and m70_coddepto     = iDepto
                     and m81_tipo    in(1, 2)
                     and to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS') > tHora
                    order by to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS')
                                  limit 1
     loop

       if rValoresPosteriores.m81_tipo = 1 then
         lEntradaAposPeriodo := true;
       end if;
       /**
        * Abatemos o valor do estoque do saldo no periodo, caso nao tenha nenhuma entrada, pois
        */
       if rValoresPosteriores.m81_tipo = 2 and lEntradaAposPeriodo = false then
         nSaidasNoPeriodo = nSaidasNoPeriodo + rValoresPosteriores.m82_quant;
       elsif rValoresPosteriores.m81_tipo = 2 and lEntradaAposPeriodo = true then
         nSaldoAposPeriodo = nSaldoAposPeriodo - rValoresPosteriores.m82_quant;
       else
         nSaldoAposPeriodo = nSaldoAposPeriodo + rValoresPosteriores.m82_quant;
       end if;
     end loop;

     nSaldoNoPeriodo    = nQuantidadeEstoque - nSaidasNoPeriodo - NEW.m82_quant;
     if (nSaldoNoPeriodo < 0 or nSaldoAposPeriodo  < 0) and lServico is false  then


        raise exception 'Saldo total não disponível nesta data (%). Saldo disponível: %. Servico: %',
                         to_char(dtMovimento, 'DD/MM/YYYY'),
                         nQuantidadeEstoque - nSaidasNoPeriodo, lServico;

     end if;
  end if;
  return new;
end;
$$
language 'plpgsql';
drop TRIGGER if exists "tg_matestoqueinimei_inc" on matestoqueinimei;
CREATE TRIGGER "tg_matestoqueinimei_inc" after  update or insert ON "matestoqueinimei" FOR EACH ROW EXECUTE PROCEDURE "fc_matestoqueinimei_inc" ();
sql
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<sql
drop function fc_calculaprecomedio;
create function fc_calculaprecomedio(integer, integer, float8, boolean)
  returns numeric as
$$

declare

  iCodigoMatestoqueiniMei        alias for $1;
  iCodigoMatestoqueini           alias for $2;
  nQuantidadeMovimento           alias for $3;
  lRecursivo                     alias for $4;

  nPrecoMedio                    numeric default 0;
  iMaterial                      integer;
  iInstituicao                   integer;
  iAlmoxarifado                  integer;
  nValorEstoque                  numeric;
  nQuantidadeEstoque             numeric default 0;
  nValorEstoqueDiferenca         numeric default 0;
  nQuantidadeEstoqueDiferenca    numeric default 0;
  iTipoMovimento                 integer;
  iCodigoEstoque                 integer;
  iCodigoMovimento               integer;
  iCodigoEntradaItem             integer;
  nValorUnitario                 numeric default 0;
  dtMovimento                    date;
  tHora                          timestamp;
  tHoraMovimento                 time;
  lTemPrecoMedio                 boolean default false;
  rValoresPosteriores            record;
  lServico                       boolean;
  iDepto                         integer;
  nQuantidadeSaidasPosteriores   numeric default 0;
  nQuantidadeEntradasPosteriores numeric default 0;
  nSaidasNoPeriodo               numeric default 0;
  nSaldoNoPeriodo                numeric default 0;
  nSaldoAposPeriodo              numeric default 0;
  sMensagemEstoque               varchar;
  lEntradaAposPeriodo            boolean default false;
  sSqlPrecoMedio                 varchar;
begin

   iInstituicao = fc_getsession('DB_instit');
   if iInstituicao is null then
     raise exception 'Instituicao não informada.';
   end if;

   /**
    * Consultamos o codigo do material,
    * atraves da tabela matestoqueitem, com o campo new.m82_matestoqueitem.
    */
    select m70_codmatmater,
           (case when  m71_quant > 0 then
           coalesce(m71_valor/m71_quant, 0)
           else 0 end),
           m71_servico,
           m70_coddepto,
           m71_codlanc
      into iMaterial,
           nValorUnitario,
           lServico,
           iAlmoxarifado,
           iCodigoEntradaItem
      from matestoqueitem
           inner join matestoque       on m70_codigo  = m71_codmatestoque
           inner join matestoqueinimei on m71_codlanc = m82_matestoqueitem
      where m82_codigo  = iCodigoMatestoqueiniMei;

   /**
    * Consultamos o tipo da movimentacao
    */
   select m80_codtipo,
          m81_tipo,
          to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS'),
          m80_data,
          m80_hora,
          m80_coddepto,
          instit
     into iCodigoMovimento,
          iTipoMovimento,
          tHora,
          dtMovimento,
          tHoraMovimento,
          iDepto,
          iInstituicao
     from matestoqueini
          inner join matestoquetipo on m81_codtipo = m80_codtipo
          inner join DB_DEPART on m80_coddepto     = coddepto
    where m80_codigo = iCodigoMatestoqueini;

   /**
    * Soma a quantidade em estoque do item na instituicao
    *
    */
   select coalesce(sum(CASE when m81_tipo = 1 then round(m82_quant, 2) when m81_tipo = 2 then round(m82_quant,2)*-1 end), 0),
          round(coalesce(sum(CASE when m81_tipo = 1 then round(round(m82_quant, 2)*m89_valorunitario, 5)
                            when m81_tipo = 2 then round(m82_quant, 2)*round(case when m81_codtipo in (4, 19) then m89_valorunitario else m89_precomedio end, 5)*-1 end), 0) , 2)
     into nQuantidadeEstoque,
          nValorEstoque
     from matestoque
          inner join db_depart          on m70_coddepto       = coddepto
          inner join matestoqueitem     on m70_codigo         = m71_codmatestoque
          inner join matestoqueinimei   on m82_matestoqueitem = m71_codlanc
          inner join matestoqueinimeipm on m82_codigo         = m89_matestoqueinimei
          inner join matestoqueini      on m82_matestoqueini  = m80_codigo
          inner join matestoquetipo     on m81_codtipo        = m80_codtipo
    where instit           = iInstituicao
      and m70_codmatmater  = iMaterial
      and to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS') <= tHora
      and m82_codigo <> iCodigoMatestoqueiniMei
      and m70_coddepto = iAlmoxarifado
      and m81_tipo not in(4,5)
      and m71_servico is false;

   /**
     * verificamos se o item possui no mesmo movimento entradas para o mesmo item de estoque
     */
    SELECT coalesce(sum(CASE when m81_tipo = 1 then round(m82_quant, 2)
                             when m81_tipo = 2 then round(m82_quant,2)*-1 end), 0) as saldodif,
           round(coalesce(sum(CASE when m81_tipo = 1 then round(round(round(m82_quant, 2)*m89_valorunitario, 5), 2)
                            when m81_tipo = 2 then round(round(m82_quant, 2)*round( case when m81_codtipo in (4, 19) then m89_valorunitario else m89_precomedio end, 5), 2)*-1 end), 0), 2)
      into nQuantidadeEstoqueDiferenca,
          nValorEstoqueDiferenca
      from matestoqueinimei
           inner join matestoqueitem     on m71_codlanc          = m82_matestoqueitem
           inner join matestoque         on m71_codmatestoque    = m70_codigo
           inner join matestoqueinimeipm on m89_matestoqueinimei = m82_codigo
           inner join matestoqueini      on m82_matestoqueini    = m80_codigo
           inner join matestoquetipo     on m80_codtipo          = m81_codtipo
     where m70_codmatmater   = iMaterial
       and m82_matestoqueini = iCodigoMatestoqueini
       and m82_codigo        > iCodigoMatestoqueiniMei
       and m70_coddepto = iAlmoxarifado
       and m81_tipo not in(4,5)
       and m71_servico is false;
       nQuantidadeEstoque := nQuantidadeEstoque - nQuantidadeEstoqueDiferenca;
       nValorEstoque      := nValorEstoque      - nValorEstoqueDiferenca;

   /**
    * Verificamos o ultimo preco medio da data do material para o item.
    */

   select round(m85_precomedio, 5)
     into nPrecoMedio
     from matmaterprecomedio
    where m85_matmater = iMaterial
      and m85_instit   = iInstituicao
      and m85_coddepto = iAlmoxarifado
      and to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') <= tHora
    order by to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') desc limit 1;

    if ( not found or nPrecoMedio = 0 ) and iCodigoMovimento in (8) then

   select round(m85_precomedio, 5)
     into nPrecoMedio
     from matmaterprecomedio
    where m85_matmater = iMaterial
      and m85_instit   = iInstituicao
      and m85_precomedio > 0
      and m85_coddepto = ( select m80_coddepto
                             from matestoqueini
                                  inner join matestoqueinil  inil  on inil.m86_matestoqueini   = matestoqueini.m80_codigo
                                  inner join matestoqueinill inill on inill.m87_matestoqueinil = inil.m86_codigo
                            where inill.m87_matestoqueini = iCodigoMatestoqueini limit 1)
      and to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') <= tHora
    order by to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') desc limit 1;

      update matmaterprecomedio
         set m85_precomedio = nPrecoMedio
       where m85_matmater = iMaterial
         and m85_instit   = iInstituicao
         and m85_coddepto = iAlmoxarifado
         and to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') <= tHora;

    end if;

    if nQuantidadeEstoque = 0 then
       nValorEstoque := 0;
    end if;
    if  found then
     lTemPrecoMedio = true;
   end if;
   nPrecoMedio := coalesce(nPrecoMedio, 0);
  /**
   * Verificamos as entradas no estoque (refletem no calculo do preço medio)
   * algumas entradas, que na verdade são cancelamentos de saidas, devem entrar no estoque
   * pelo preco médio atual, não alterando o preço do calculo médio.
   */
  if iCodigoMovimento in(8, 1, 3, 12, 14, 15, 25) then

    /**
     * como o sistema já inclui as informações do estoque na hora de verificarmos o preço médio,
     * devemos deduzir a quantidade da entrada, (nQuantidade - m82_quant). a regra do calculo do preço médio é:
     * pegamos a quantidade anterior em estoque, e multiplicamos pelo ultimo preço médio.
     * - Somamos a nova entrada (quantidade e valor da entrada,) e dividimos o valor encontrado pela quantidade
     * encontrada. o resultado dessa divisão, encontramos o preço médio.
     */
    --nValorEstoque      = round(nQuantidadeEstoque * nPrecoMedio, 2);
    nQuantidadeEstoque = nQuantidadeEstoque  + nQuantidadeMovimento;
    nValorEstoque      = round(nValorEstoque + (nQuantidadeMovimento*nValorUnitario), 2);
    nPrecoMedio        = 0;
    if nQuantidadeEstoque > 0 then
      nPrecoMedio    = round( nValorEstoque / nQuantidadeEstoque, 5);
    end if;
  /**
   * Excluimos o preço medio para o movimento/hora
   */
    delete from matmaterprecomedio
     where m85_matmater = imaterial
       and m85_instit   = iInstituicao
       and m85_coddepto = iAlmoxarifado
       and to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') >= tHora;

    insert into matmaterprecomedio
                  (m85_sequencial,
                   m85_matmater,
                   m85_instit,
                   m85_precomedio,
                   m85_data,
                   m85_hora,
                   m85_coddepto
                  )
           values (nextval('matmaterprecomedio_m85_sequencial_seq'),
                   iMaterial,
                   iInstituicao,
                   round(nPrecoMedio, 5),
                   dtMovimento,
                   tHoraMovimento,
                   iAlmoxarifado
                  );

  elsif iCodigoMovimento in (4, 19) then

    nValorUnitario := nPrecoMedio;

  elsif iTipoMovimento = 2 and iCodigoMovimento not in(8, 9) then

    nValorUnitario = round(nPrecoMedio, 5);

  elsif iCodigoMovimento in(7, 6, 18, 9) then

    nValorUnitario = round(nPrecoMedio, 5);

  elsif iCodigoMovimento in (21) then

    /**
     * caso  a transferencia seja confirmada,
     * temos que fazer a entrada no estoque ao mesmo valor da saida, pois a movimentacao no estoque
     * nao existe a movimentacao de valores.
     * o codigo da transferencia está na tabela mastoqueinil/matestoqueinill
     */
     select round(m89_precomedio, 5)
       into nPrecoMedio
       from matestoqueinill
            inner join matestoqueinil     on m87_matestoqueinil = m86_codigo
            inner join matestoqueinimei   on m86_matestoqueini  = m82_matestoqueini
            inner join matestoqueinimeipm on m82_codigo         = m89_matestoqueinimei
            inner join matestoqueitem     on m82_matestoqueitem = m71_codlanc
            inner join matestoque         on m70_codigo         = m71_codmatestoque
      where m70_codmatmater   = iMaterial
        and m87_matestoqueini = iCodigoMatestoqueini
        and m71_servico is false;

     nValorUnitario = round(nPrecoMedio, 5);
  end if;

  delete from matestoqueinimeipm where m89_matestoqueinimei = iCodigoMatestoqueiniMei;
  insert into matestoqueinimeipm
              (m89_sequencial,
               m89_matestoqueinimei,
               m89_precomedio,
               m89_valorunitario,
               m89_valorfinanceiro
               )
       values (nextval('matestoqueinimeipm_m89_sequencial_seq'),
               iCodigoMatestoqueiniMei,
               round(nPrecoMedio, 5),
               round(nValorUnitario, 5),
               round(nQuantidadeMovimento * round(nValorUnitario, 5), 2)
              );
  return round(nPrecoMedio, 5);
end;
$$
language 'plpgsql';
sql
    );

        DB::connection()->getPdo()->exec(<<<sql
create or replace function fc_matestoqueinimei_inc()
  returns trigger as
$$

declare

  nPrecoMedio                    numeric default 0;
  iMaterial                      integer;
  iInstituicao                   integer;
  nValorEstoque                  numeric;
  nQuantidadeEstoque             numeric default 0;
  iTipoMovimento                 integer;
  iCodigoEstoque                 integer;
  iCodigoMovimento               integer;
  nValorUnitario                 numeric default 0;
  dtMovimento                    date;
  tHora                          timestamp;
  tHoraMovimento                 time;
  lTemPrecoMedio                 boolean default false;
  nQuantidadeMovimento           numeric default 0;
  rValoresAnterior               record;
  rValoresPosteriores            record;
  lServico                       boolean default false;
  iDepto                         integer;
  nQuantidadeSaidasPosteriores   numeric default 0;
  nQuantidadeEntradasPosteriores numeric default 0;
  nSaidasNoPeriodo               numeric default 0;
  nSaldoNoPeriodo                numeric default 0;
  nSaldoAposPeriodo              numeric default 0;
  sMensagemEstoque               varchar;
  lEntradaAposPeriodo            boolean default false;
begin

   iInstituicao = fc_getsession('DB_instit');
   if iInstituicao is null then
     raise exception 'Instituicao nao informada.';
   end if;


   /**
    * Consultamos o tipo da movimentacao
    */
   select m80_codtipo,
          m81_tipo,
          to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS'),
          m80_data,
          m80_hora,
          m80_coddepto,
          instit,
          m70_codmatmater
          m71_servico
     into iCodigoMovimento,
          iTipoMovimento,
          tHora,
          dtMovimento,
          tHoraMovimento,
          iDepto,
          iInstituicao,
          iMaterial,
          lServico
     from matestoqueini
          inner join matestoquetipo on m81_codtipo = m80_codtipo
          inner join DB_DEPART on m80_coddepto     = coddepto
          inner join matestoqueinimei on m80_codigo = m82_matestoqueini
          inner join matestoqueitem   on m82_matestoqueitem = m71_codlanc
          inner join matestoque       on m71_codmatestoque  = m70_codigo
    where m80_codigo = NEW.m82_matestoqueini;

    /**
     * verifica se existe saldo no estoque para realizar a operacao (saldo cronologico.)
     * pois usuario pode retornar a data, termos uma saida do material antes de termos a entrada.....
     */
   raise notice 'codigo: % Hora: %', new.m82_codigo, tHora;
   if iTipoMovimento = 2 and lServico = false then

     raise notice 'tipo Movimento:% Operacao:%, Servico:%',iTipoMovimento, TG_OP,lServico;

      /**
       * percorremos todo a movimentação do estoque posterior a data do movimento
       */
      for rValoresAnterior in select m82_quant,
                          m81_tipo,
                          to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS') as dtmov
                     from matestoque
                          inner join db_depart        on m70_coddepto       = coddepto
                          inner join matestoqueitem   on m70_codigo         = m71_codmatestoque
                          inner join matestoqueinimei on m82_matestoqueitem = m71_codlanc
                          inner join matestoqueini    on m82_matestoqueini  = m80_codigo
                          inner join matestoquetipo   on m81_codtipo        = m80_codtipo
                    where instit           = iInstituicao
                     and m70_codmatmater  = iMaterial
                     and m70_coddepto     = iDepto
                     and m82_codigo       <> new.m82_codigo
                     and m81_tipo    in(1, 2)
                     and to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS') < tHora
                    order by to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS')

     loop

       raise notice 'Tipo: %, Quantidade:%',rValoresAnterior.m81_tipo,  rValoresAnterior.m82_quant;
       /**
        * Abatemos o valor do estoque do saldo no periodo, caso nao tenha nenhuma entrada, pois
        */
       if rValoresAnterior.m81_tipo = 1 then
         nQuantidadeEstoque = nQuantidadeEstoque + rValoresAnterior.m82_quant;
       elsif rValoresAnterior.m81_tipo = 2 then
         nQuantidadeEstoque = nQuantidadeEstoque - rValoresAnterior.m82_quant;
       end if;
     end loop;
     raise notice 'Antes No periodo: %, saidas no periodo:%', nQuantidadeEstoque, nSaidasNoPeriodo;
     nSaldoNoPeriodo    = nQuantidadeEstoque -  new.m82_quant;
     raise notice 'No periodo: %, apos Periodo:%', nSaldoNoPeriodo, nSaldoAposPeriodo;
     if (nSaldoNoPeriodo < 0 or nSaldoAposPeriodo  < 0) and lServico is false then


        raise exception 'Saldo total não disponível nesta data (%). Saldo disponivel: %. Servico: %',
                         to_char(dtMovimento, 'DD/MM/YYYY'),
                         nQuantidadeEstoque - nSaidasNoPeriodo, lServico;

     end if;
  end if;


  perform fc_calculaprecomedio(NEW.m82_codigo::integer,
                               NEW.m82_matestoqueini::integer,
                               NEW.m82_quant, false);


    for rValoresPosteriores in select matestoqueinimei.*
	                             from matestoqueitem
	                                  inner join matestoqueinimei on m82_matestoqueitem = m71_codlanc
	                                  inner join matestoqueini    on m80_codigo         = m82_matestoqueini
	                                  inner join matestoque       on m71_codmatestoque  = m70_codigo
	                                  inner join db_depart        on m70_coddepto       = coddepto
	                            where m70_codmatmater  = iMaterial
	                             and instit            = iInstituicao
	                             and to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS') > tHora
	                           order by  to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS')
	loop

	  perform fc_calculaprecomedio(rValoresPosteriores.m82_codigo::integer,
                                   rValoresPosteriores.m82_matestoqueini::integer,
                                   rValoresPosteriores.m82_quant, false);
    end loop;

    /**
     * verifica se existe saldo no estoque para realizar a operacao (saldo cronologico.)
     * pois usuário pode retornar a data, termos uma saida do material antes de termos a entrada.....
     */

    if iTipoMovimento = 2 and TG_OP = 'INSERT' and lServico = false then

      /**
       * percorremos todo a movimentação do estoque posterior a data do movimento
       */
      for rValoresPosteriores in select m82_quant,
                          m81_tipo,
                          to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS') as dtmov
                     from matestoque
                          inner join db_depart        on m70_coddepto       = coddepto
                          inner join matestoqueitem   on m70_codigo         = m71_codmatestoque
                          inner join matestoqueinimei on m82_matestoqueitem = m71_codlanc
                          inner join matestoqueini    on m82_matestoqueini  = m80_codigo
                          inner join matestoquetipo   on m81_codtipo        = m80_codtipo
                    where instit           = iInstituicao
                     and m70_codmatmater  = iMaterial
                     and m70_coddepto     = iDepto
                     and m81_tipo    in(1, 2)
                     and to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS') > tHora
                    order by to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS')
                                  limit 1
     loop

       if rValoresPosteriores.m81_tipo = 1 then
         lEntradaAposPeriodo := true;
       end if;
       /**
        * Abatemos o valor do estoque do saldo no periodo, caso nao tenha nenhuma entrada, pois
        */
       if rValoresPosteriores.m81_tipo = 2 and lEntradaAposPeriodo = false then
         nSaidasNoPeriodo = nSaidasNoPeriodo + rValoresPosteriores.m82_quant;
       elsif rValoresPosteriores.m81_tipo = 2 and lEntradaAposPeriodo = true then
         nSaldoAposPeriodo = nSaldoAposPeriodo - rValoresPosteriores.m82_quant;
       else
         nSaldoAposPeriodo = nSaldoAposPeriodo + rValoresPosteriores.m82_quant;
       end if;
     end loop;

     nSaldoNoPeriodo    = nQuantidadeEstoque - nSaidasNoPeriodo - NEW.m82_quant;
     if (nSaldoNoPeriodo < 0 or nSaldoAposPeriodo  < 0) and lServico is false  then


        raise exception 'Saldo total não disponível nesta data (%). Saldo disponível: %. Servico: %',
                         to_char(dtMovimento, 'DD/MM/YYYY'),
                         nQuantidadeEstoque - nSaidasNoPeriodo, lServico;

     end if;
  end if;
  return new;
end;
$$
language 'plpgsql';
drop TRIGGER if exists "tg_matestoqueinimei_inc" on matestoqueinimei;
CREATE TRIGGER "tg_matestoqueinimei_inc" after  update or insert ON "matestoqueinimei" FOR EACH ROW EXECUTE PROCEDURE "fc_matestoqueinimei_inc" ();
sql
        );
        Schema::drop('material.matestoqueitemanulacao');

        DB::connection()->getPdo()->exec(<<<sql
            delete from db_sysindices where codind = 1008707;
            delete from db_sysforkey where codarq = 1010851;
            delete from db_sysprikey where codarq = 1010851;
            delete from db_sysarqcamp where codarq = 1010851;
            delete from db_syscampo where codcam in (1013606, 1013607, 1013608);
            delete from db_sysarqarq where codarq = 1010851;
            delete from db_sysarqmod where codarq = 1010851;
            delete from db_sysarqcamp where codarq = 1010851;
            delete from db_sysarquivo where codarq = 1010851;
            delete from db_syssequencia where codsequencia = 1001028;
sql
        );
    }
}
