
create or replace function migracao_documentos(
  pis                      varchar,
  data_entrada             date,
  cpf                      varchar,

  numero_identidade        varchar,
  data_emissao_identidade  date,
  orgao_emissor_identidade varchar,
  uf_identidade            varchar,

  numero_ctps              varchar,
  serie_ctps               varchar,
  data_emissao_ctps        date,
  uf_ctps                  varchar,

  certidao_tipo            varchar,
  certido_livro            varchar,
  certidao_termo           varchar,
  certidao_cartorio        varchar,
  certidao_folha           varchar,
  certidao_data            date,

  numero_cnh               varchar,
  categoria_cnh            varchar,
  data_emissao_cnh         date,
  data_habilitacao         date,
  data_vencimento          date,

  banco                    varchar,
  agencia                  varchar,
  conta                    varchar,

  cgs                      integer) returns boolean as

$$
DECLARE
  GERAIS                                   integer default 3000000;
  IDENTIDADE                               integer default 3000001;
  CTPS                                     integer default 3000002;
  CERTIDAO                                 integer default 3000003;
  CNH                                      integer default 3000004;
  DADOS_BANCARIOS                          integer default 3000005;
  sequencial_documento_GERAIS              integer;
  sequencial_documento_IDENTIDADE          integer;
  sequencial_documento_CTPS                integer;
  sequencial_documento_CERTIDAO            integer;
  sequencial_documento_CNH                 integer;
  sequencial_documento_DADOS_BANCARIOS     integer;
  matriz_geral                             text[][];
BEGIN

  raise info '-- MIGRANDO CGS --- ;D %', cgs;
  sequencial_documento_GERAIS          := nextval('documento_db58_sequencial_seq');
  sequencial_documento_IDENTIDADE      := nextval('documento_db58_sequencial_seq');
  sequencial_documento_CTPS            := nextval('documento_db58_sequencial_seq');
  sequencial_documento_CERTIDAO        := nextval('documento_db58_sequencial_seq');
  sequencial_documento_CNH             := nextval('documento_db58_sequencial_seq');
  sequencial_documento_DADOS_BANCARIOS := nextval('documento_db58_sequencial_seq');

  matriz_geral := array[
    array[GERAIS::varchar,          sequencial_documento_GERAIS::varchar,          'PIS/PASEP',           pis::varchar],
    array[GERAIS::varchar,          sequencial_documento_GERAIS::varchar,          'CPF',                 cpf::varchar],
    array[GERAIS::varchar,          sequencial_documento_GERAIS::varchar,          'DATA ENTRADA',        data_entrada::varchar],
    array[IDENTIDADE::varchar,      sequencial_documento_IDENTIDADE::varchar,      'NÚMERO',              numero_identidade::varchar],
    array[IDENTIDADE::varchar,      sequencial_documento_IDENTIDADE::varchar,      'DATA DE EMISSÃO',     data_emissao_identidade::varchar],
    array[IDENTIDADE::varchar,      sequencial_documento_IDENTIDADE::varchar,      'ÓRGÃO EMISSOR',       orgao_emissor_identidade::varchar],
    array[IDENTIDADE::varchar,      sequencial_documento_IDENTIDADE::varchar,      'UF',                  uf_identidade::varchar],
    array[CTPS::varchar,            sequencial_documento_CTPS::varchar,            'NÚMERO',              numero_ctps::varchar],
    array[CTPS::varchar,            sequencial_documento_CTPS::varchar,            'SÉRIE',               serie_ctps::varchar],
    array[CTPS::varchar,            sequencial_documento_CTPS::varchar,            'DATA DE EMISSÃO',     data_emissao_ctps::varchar],
    array[CTPS::varchar,            sequencial_documento_CTPS::varchar,            'UF',                  uf_ctps::varchar],
    array[CERTIDAO::varchar,        sequencial_documento_CERTIDAO::varchar,        'TIPO DE CERTIDÃO',    certidao_tipo::varchar],
    array[CERTIDAO::varchar,        sequencial_documento_CERTIDAO::varchar,        'LIVRO',               certido_livro::varchar],
    array[CERTIDAO::varchar,        sequencial_documento_CERTIDAO::varchar,        'TERMO',               certidao_termo::varchar],
    array[CERTIDAO::varchar,        sequencial_documento_CERTIDAO::varchar,        'CARTÓRIO',            certidao_cartorio::varchar],
    array[CERTIDAO::varchar,        sequencial_documento_CERTIDAO::varchar,        'FOLHA',               certidao_folha::varchar],
    array[CERTIDAO::varchar,        sequencial_documento_CERTIDAO::varchar,        'DATA DA EMISSÃO',     certidao_data::varchar],
    array[CNH::varchar,             sequencial_documento_CNH::varchar,             'NÚMERO',              numero_cnh::varchar],
    array[CNH::varchar,             sequencial_documento_CNH::varchar,             'CATEGORIA',           categoria_cnh::varchar],
    array[CNH::varchar,             sequencial_documento_CNH::varchar,             'DATA DE EMISSÃO',     data_emissao_cnh::varchar],
    array[CNH::varchar,             sequencial_documento_CNH::varchar,             'DATA HABILITAÇÃO',    data_habilitacao::varchar],
    array[CNH::varchar,             sequencial_documento_CNH::varchar,             'DATA DE VENCIMENTO',  data_vencimento::varchar],
    array[DADOS_BANCARIOS::varchar, sequencial_documento_DADOS_BANCARIOS::varchar, 'BANCO',               banco::varchar],
    array[DADOS_BANCARIOS::varchar, sequencial_documento_DADOS_BANCARIOS::varchar, 'AGÊNCIA',             agencia::varchar],
    array[DADOS_BANCARIOS::varchar, sequencial_documento_DADOS_BANCARIOS::varchar, 'CONTA',               conta::varchar]
  ];

  insert into documento(db58_sequencial)
      values (sequencial_documento_GERAIS),
             (sequencial_documento_IDENTIDADE),
             (sequencial_documento_CTPS),
             (sequencial_documento_CERTIDAO),
             (sequencial_documento_CNH),
             (sequencial_documento_DADOS_BANCARIOS);

  insert into cgs_unddocumento
       values (nextval('cgs_unddocumento_sd108_sequencial_seq'), cgs, sequencial_documento_GERAIS),
              (nextval('cgs_unddocumento_sd108_sequencial_seq'), cgs, sequencial_documento_IDENTIDADE),
              (nextval('cgs_unddocumento_sd108_sequencial_seq'), cgs, sequencial_documento_CTPS),
              (nextval('cgs_unddocumento_sd108_sequencial_seq'), cgs, sequencial_documento_CERTIDAO),
              (nextval('cgs_unddocumento_sd108_sequencial_seq'), cgs, sequencial_documento_CNH),
              (nextval('cgs_unddocumento_sd108_sequencial_seq'), cgs, sequencial_documento_DADOS_BANCARIOS);

  for indice_for in 1..25 loop

    insert into caddocumentoatributovalor(db43_sequencial, db43_documento, db43_caddocumentoatributo, db43_valor)
         select nextval('caddocumentoatributovalor_db43_sequencial_seq') as sequencial_valor,
                matriz_geral[indice_for][2]::integer,
                db45_sequencial,
                matriz_geral[indice_for][4]
          from caddocumentoatributo
         where caddocumentoatributo.db45_descricao    = matriz_geral[indice_for][3]  -- campo
           and caddocumentoatributo.db45_caddocumento = matriz_geral[indice_for][1]::integer; -- caddocumento

  end loop;
  return true;
END
$$ language plpgsql;
/************************************************************************************************************************/

/**
 * Início da migração
 */
delete from caddocumentoatributovalor
 using caddocumentoatributo
 where db45_sequencial   = db43_caddocumentoatributo
   and db45_caddocumento in (3000000, 3000001, 3000002, 3000003, 3000004, 3000005);

/**
 * Migrando
 */
select migracao_documentos(pis, data_entrada, cpf, numero_identidade, data_emissao_identidade, orgao_emissor_identidade, uf_identidade, numero_ctps, serie_ctps, data_emissao_ctps, uf_ctps, certidao_tipo, certido_livro, certidao_termo, certidao_cartorio, certidao_folha, certidao_data, numero_cnh, categoria_cnh, data_emissao_cnh, data_habilitacao, data_vencimento, banco, agencia, conta, cgs)
  from (
select z01_c_pis                  as pis,
       z01_d_datapais             as data_entrada,
       z01_v_cgccpf               as cpf,
       z01_v_ident                as numero_identidade,
       z01_d_dtemissao            as data_emissao_identidade,
       z01_orgaoemissoridentidade as orgao_emissor_identidade,
       z01_c_ufident              as uf_identidade,
       z01_c_numctps              as numero_ctps,
       z01_c_seriectps            as serie_ctps,
       z01_d_dtemissaoctps        as data_emissao_ctps,
       z01_c_ufctps               as uf_ctps,
       z01_c_certidaotipo         as certidao_tipo,
       z01_c_certidaolivro        as certido_livro,
       z01_c_certidaotermo        as certidao_termo,
       z01_c_certidaocart         as certidao_cartorio,
       z01_c_certidaofolha        as certidao_folha,
       z01_c_certidaodata         as certidao_data,
       z01_v_cnh                  as numero_cnh,
       z01_v_categoria            as categoria_cnh,
       z01_d_dtemissaocnh         as data_emissao_cnh,
       z01_d_dthabilitacao        as data_habilitacao,
       z01_d_dtvencimento         as data_vencimento,
       z01_c_banco                as banco,
       z01_c_agencia              as agencia,
       z01_c_conta                as conta,
       z01_i_cgsund               as cgs
  from cgs_und
 order by z01_i_cgsund
 ) as migracao;

drop function migracao_documentos(varchar, date, varchar, varchar, date, varchar, varchar, varchar, varchar, date, varchar, varchar, varchar, varchar, varchar, varchar, date, varchar, varchar, date, date, date, varchar, varchar, varchar, integer);
