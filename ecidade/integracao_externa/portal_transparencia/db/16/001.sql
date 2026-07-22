create table transferencias_financeiras 
( 
    id                       integer,
    exercicio                integer,
    tipo_transferencia       integer,
    descricao_transferencia  varchar(255),
    slip                     integer, 
    data_emissao_slip        date,
    complemento_historico    varchar(255),
    descr_hist               varchar(255),
    recurso_debito           integer,
    recurso_credito          integer,
    estrutural_debito        varchar(15),
    descr_debito             varchar(255),
    estrutural_credito       varchar(15),  
    descr_credito            varchar(255),
    instituicao_origem       integer,
    nome_instituicao_origem  varchar(255),
    instituicao_destino      integer,
    nome_instituicao_destino varchar(255),
    data_lancamento          date,
    tipo_evento              integer,
    evento_contabil          varchar(255),
    valor_lancamento         numeric
);

