---------------------------------------------------------------------------------------------
-------------------------------- INCIO FINANCEIRO -------------------------------------------
---------------------------------------------------------------------------------------------

-- Manutenção de veículos

alter table veicmanut drop column if exists ve62_situacao,
                      drop column if exists ve62_numero,
                      drop column if exists ve62_anousu,
                      drop column if exists ve62_veicmotoristas;

alter table veicmanutitem drop column if exists ve63_valortotalcomdesconto,
                          drop column if exists ve63_unidade,
                          drop column if exists ve63_tipoitem,
                          drop column if exists ve63_proximatroca,
                          drop column if exists ve63_datanota,
                          drop column if exists ve63_numeronota;

-- Autorização para circulação de veículos

drop index if exists autorizacaocirculacaoveiculo_veiculo_in;
drop index if exists autorizacaocirculacaoveiculo_motorista_in;
drop index if exists autorizacaocirculacaoveiculo_instituicao_in;
drop index if exists autorizacaocirculacaoveiculo_departamento_in;
drop table if exists autorizacaocirculacaoveiculo;
drop sequence if exists autorizacaocirculacaoveiculo_ve13_sequencial_seq;

drop index if exists levantamentopatrimonialbens_levantamentopatrimonial_in;
drop index if exists levantamentopatrimonial_departamento_in;
drop table if exists levantamentopatrimonialbens;
drop table if exists levantamentopatrimonial;
drop sequence if exists levantamentopatrimonialbens_p14_sequencial_seq;
drop sequence if exists levantamentopatrimonial_p13_sequencial_seq;

drop index if exists pagordemdescontoempanulado_pagordemdesconto_in;
drop index if exists pagordemdescontoempanulado_empanulado_in;
drop index if exists pagordemdescontoempanulado_sequencial_in;
drop table if exists pagordemdescontoempanulado;
drop sequence if exists pagordemdescontoempanulado_e06_sequencial_seq;
---------------------------------------------------------------------------------------------
-------------------------------- FIM FINANCEIRO---------------------------------------------
---------------------------------------------------------------------------------------------

---------------------------------------------------------------------------------------------
------------------------------ INÍCIO EDUCAÇÃO/SAÚDE ----------------------------------------
---------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS pontoparadaescolaproc;
DROP SEQUENCE IF EXISTS pontoparadaescolaproc_tre13_sequencial_seq;

DROP TABLE IF EXISTS dadoscompetenciadispensacao CASCADE;
DROP TABLE IF EXISTS dadoscompetenciaentrada CASCADE;
DROP TABLE IF EXISTS dadoscompetenciasaida CASCADE;
DROP TABLE IF EXISTS integracaohorusenvio CASCADE;
DROP TABLE IF EXISTS integracaohorusenviodadoscompetencia CASCADE;
DROP TABLE IF EXISTS situacaohorus CASCADE;
DROP SEQUENCE IF EXISTS dadoscompetenciadispensacao_fa61_sequencial_seq;
DROP SEQUENCE IF EXISTS dadoscompetenciaentrada_fa62_sequencial_seq;
DROP SEQUENCE IF EXISTS dadoscompetenciasaida_fa63_sequencial_seq;
DROP SEQUENCE IF EXISTS integracaohorusenvio_fa64_sequencial_seq;
DROP SEQUENCE IF EXISTS integracaohorusenviodadoscompetencia_fa65_sequencial_seq;
DROP SEQUENCE IF EXISTS situacaohorus_fa60_sequencial_seq;

alter table integracaohorus drop column fa59_situacaohorus;
alter table integracaohorus drop column fa59_db_depart;
alter table integracaohorus add column fa59_data date not null;
alter table integracaohorus add column fa59_protocolo text;

---------------------------------------------------------------------------------------------
-------------------------------- FIM EDUCAÇÃO/SAÚDE -----------------------------------------
---------------------------------------------------------------------------------------------
