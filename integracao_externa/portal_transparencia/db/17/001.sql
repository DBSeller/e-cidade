alter table servidor_movimentacoes add column local_trabalho varchar(40);
alter table servidor_movimentacoes add column situacao_funcional varchar(6);
alter table servidor_movimentacoes add column causa_rescisao varchar(40);

CREATE TABLE transparencia.cargos(
id                     int4     NOT NULL default 0,
instituicao_id         int4     NOT NULL default 0,
descricao              varchar(30)  NOT NULL,
descricaocompleta      varchar(255)  NOT NULL,
vagas                  int4     NOT NULL default 0,
instrucao              varchar(40),
CONSTRAINT cargos_sequ_pk PRIMARY KEY (id, instituicao_id));

COMMENT ON TABLE  cargos                   IS 'Cadatro de cargos';
COMMENT ON COLUMN cargos.id                IS 'ID do cargo no e-cidade';
COMMENT ON COLUMN cargos.instituicao_id    IS 'ID da instituição';
COMMENT ON COLUMN cargos.descricao         IS 'Descrição do cargo';
COMMENT ON COLUMN cargos.descricaocompleta IS 'Descrição completa do cargo';
COMMENT ON COLUMN cargos.instrucao         IS 'Descrição da instrução';
COMMENT ON COLUMN cargos.vagas             IS 'Número de vagas';

ALTER TABLE cargos ADD CONSTRAINT cargos_instituicao_id_fk
FOREIGN KEY (instituicao_id)
REFERENCES instituicoes (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

alter table servidor_movimentacoes 
  add column id_cargo int4 NOT NULL default 0;