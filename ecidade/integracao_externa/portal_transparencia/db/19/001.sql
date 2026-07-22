create sequence comissao_membros_id_seq;

CREATE TABLE transparencia.comissao_membros
(
    id                 integer DEFAULT nextval('comissao_membros_id_seq'::regclass) NOT NULL,
    acordo_id          integer                                                      NOT NULL,
    nome_membro        varchar(50)                                                  NOT NULL,
    tipo_membro_codigo integer                                                      NOT NULL,
    tipo_membro_descr  varchar(30)                                                  NOT NULL,

    CONSTRAINT comissao_membros_id_pk PRIMARY KEY (id),

    CONSTRAINT comissao_membros_id_fk
        FOREIGN KEY (acordo_id)
            REFERENCES acordos (id)

);

CREATE INDEX comissao_membros_nome_in on comissao_membros (nome_membro);

CREATE INDEX comissao_membros_tipomembdescr_in on comissao_membros (tipo_membro_descr);

CREATE INDEX comissao_membros_tipomembcod_in on comissao_membros (tipo_membro_codigo);
