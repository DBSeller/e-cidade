create sequence licitacoes_contratos_id_seq;

CREATE TABLE licitacoes_contratos (
    id integer DEFAULT nextval('licitacoes_contratos_id_seq'::regclass) NOT NULL,
    licitacao_id integer NOT NULL,
    acordo_id integer NOT NULL
);

ALTER TABLE ONLY licitacoes_contratos
    ADD CONSTRAINT licitacoes_contratos_id_pk PRIMARY KEY (id);

CREATE INDEX licitacoes_contratos_licitacao_id_in 
    ON licitacoes_contratos USING btree (licitacao_id);

CREATE INDEX licitacoes_contratos_acordo_id_in 
    ON licitacoes_contratos USING btree (acordo_id);

ALTER TABLE ONLY licitacoes_contratos
    ADD CONSTRAINT licitacoes_contratos_licitacao_id_fk FOREIGN KEY (licitacao_id) REFERENCES licitacoes(id);

ALTER TABLE ONLY licitacoes_contratos
    ADD CONSTRAINT licitacoes_contratos_acordo_id_fk FOREIGN KEY (acordo_id) REFERENCES acordos(id);