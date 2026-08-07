CREATE SEQUENCE transparencia.detalhe_diaria_id_seq;

CREATE TABLE transparencia.detalhe_diarias (
  id                      INTEGER NOT NULL DEFAULT nextval('detalhe_diaria_id_seq'),
  empenho_movimentacao    INTEGER NOT NULL,
  servidor_matricula      INTEGER NOT NULL,
  cgm                     INTEGER NOT NULL,
  inicio                  DATE NOT NULL,
  fim                     DATE NOT NULL,
  quantidade              INTEGER NOT NULL,
  itemprestacao           INTEGER NOT NULL,
  valor                   numeric NOT NULL,
  tipodiaria              TEXT DEFAULT '',
  destino                 TEXT DEFAULT '',
  motivo                  TEXT DEFAULT '',
  CONSTRAINT detalhe_diaria_id_pk PRIMARY KEY (id)
);

COMMENT ON TABLE  detalhe_diarias IS 'Tabela de detalhe das diarias do empenho';
COMMENT ON COLUMN detalhe_diarias.id IS 'ID do detalhe da diaria';
COMMENT ON COLUMN detalhe_diarias.empenho_movimentacao IS 'Codigo da movimentacao';
COMMENT ON COLUMN detalhe_diarias.cgm IS 'Codigo do cgm';
COMMENT ON COLUMN detalhe_diarias.servidor_matricula IS 'Matricula do servidor';
COMMENT ON COLUMN detalhe_diarias.inicio IS 'Data de inicio da diaria';
COMMENT ON COLUMN detalhe_diarias.fim IS 'Data de termino da diaria';
COMMENT ON COLUMN detalhe_diarias.quantidade IS 'Quantidade de diarias dentro do empenho para aquele servidor';
COMMENT ON COLUMN detalhe_diarias.tipodiaria IS 'Tipo de diaria, se e dentro ou fora do estado ou internacional';
COMMENT ON COLUMN detalhe_diarias.destino  IS 'Local onde aconteceu a diaria';
COMMENT ON COLUMN detalhe_diarias.motivo  IS 'Motivo de porque precisou da diaria';
COMMENT ON COLUMN detalhe_diarias.valor  IS 'valor do item da movimentacao da diaria';
COMMENT ON COLUMN detalhe_diarias.itemprestacao  IS 'Codigo dentro da prestacao do ecidade usado para nao duplicar informacoes';
