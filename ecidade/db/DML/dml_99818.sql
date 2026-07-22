--Dicionario de dados com ajustes para tabelas do eSocial
select fc_executa_ddl('update db_sysarquivo set nomearq = ''avaliacaogruporespostarhpessoal'', descricao = ''Tabela que vincula uma resposta de uma pergunta do e-Social para o servidor.'', sigla = ''eso02'', dataincl = ''2016-05-18'', rotulo = ''Vincula uma resposta de avaliação a um servidor'', tipotabela = 0, naolibclass = ''f'', naolibfunc = ''f'', naolibprog = ''f'', naolibform = ''f'' where codarq = 3924;');
select fc_executa_ddl('update db_syscampo set nomecam = ''eso02_rhpessoal'', conteudo = ''int4'', descricao = ''Vínculo com o cadastro de servidores'', valorinicial = ''0'', rotulo = ''Matrícula'', nulo = ''f'', tamanho = 19, maiusculo = ''f'', autocompl = ''f'', aceitatipo = 1, tipoobj = ''text'', rotulorel = ''Matrícula'' where codcam = 21794;');
select fc_executa_ddl('delete from db_syscampodep where codcam = 21794;');
select fc_executa_ddl('delete from db_syscampodef where codcam = 21794;');
select fc_executa_ddl('delete from db_sysforkey where codarq = 3924 and codcam = 21794;');
select fc_executa_ddl('insert into db_sysforkey values(3924,21794,1,1153,0);');
select fc_executa_ddl('delete from db_syssequencia where codsequencia = 1000559;');
select fc_executa_ddl('insert into db_syssequencia values(1000573, ''avaliacaogruporespostarhpessoal_eso02_sequencial_seq'', 1, 1, 9223372036854775807, 1, 1);');
select fc_executa_ddl('insert into db_sysindices values(4352,''avaliacaogruporespostarhpessoal_un_in'',3924,''1'');');
select fc_executa_ddl('insert into db_syscadind values(4352,21793,1);');
select fc_executa_ddl('insert into db_syscadind values(4352,21794,2);');
select fc_executa_ddl('insert into db_syscadind values(4351,21792,1);');
select fc_executa_ddl('update db_sysarqcamp set codsequencia = 1000573 where codarq = 3924 and codcam = 21792;');

select fc_executa_ddl('update db_syscampo set nomecam = ''eso02_avaliacaogruporesposta'', conteudo = ''int4'', descricao = ''V�nculo com a resposta'', valorinicial = ''0'', rotulo = ''Resposta'', nulo = ''f'', tamanho = 19, maiusculo = ''f'', autocompl = ''f'', aceitatipo = 1, tipoobj = ''text'', rotulorel = ''Resposta'' where codcam = 21793;');
select fc_executa_ddl('delete from db_syscampodep where codcam = 21793;');
select fc_executa_ddl('delete from db_syscampodef where codcam = 21793;');
select fc_executa_ddl('delete from db_sysforkey where codarq = 3924 and referen = 2986;');
select fc_executa_ddl('insert into db_sysforkey values(3924,21793,1,2987,0);');

---------------------------------------------------------------------------------------------------------------------------
--------------------------------------------------- INICIO FOLHA ----------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------
select fc_executa_ddl('CREATE SEQUENCE avaliacaogruporespostarhpessoal_eso02_sequencial_seq
                         INCREMENT 1
                         MINVALUE 1
                         MAXVALUE 9223372036854775807
                         START 1
                         CACHE 1;');

select fc_executa_ddl('CREATE TABLE avaliacaogruporespostarhpessoal(
                         eso02_sequencial int4 NOT NULL default nextval(''avaliacaogruporespostarhpessoal_eso02_sequencial_seq''),
                         eso02_avaliacaogruporesposta int4 NOT NULL,
                         eso02_rhpessoal int4 NOT NULL);');

select fc_executa_ddl('CREATE UNIQUE INDEX avaliacaogruporespostarhpessoal_un_in on avaliacaogruporespostarhpessoal(eso02_avaliacaogruporesposta, eso02_rhpessoal);');

select fc_executa_ddl('ALTER TABLE avaliacaogruporespostarhpessoal
												 ADD CONSTRAINT eso02_sequencial_pk PRIMARY KEY (eso02_sequencial);');
select fc_executa_ddl('ALTER TABLE avaliacaogruporespostarhpessoal
												 ADD CONSTRAINT eso02_avaliacaogruporesposta_fk FOREIGN KEY (eso02_avaliacaogruporesposta) REFERENCES avaliacaogruporesposta;');
select fc_executa_ddl('ALTER TABLE avaliacaogruporespostarhpessoal
												 ADD CONSTRAINT eso02_rhpessoal_fk FOREIGN KEY (eso02_rhpessoal) REFERENCES rhpessoal;');

--Guarda dados ja prenchidos do eSocial
select fc_executa_ddl('CREATE TEMP TABLE w_avaliacaogruporespostacgm AS (    SELECT avaliacaogruporespostacgm.*, rh01_regist as matricula 
	                                                                             FROM avaliacaogruporespostacgm
                                                                         INNER JOIN rhpessoal ON rh01_numcgm = eso02_cgm);');

select fc_executa_ddl('DROP TABLE IF EXISTS avaliacaogruporespostacgm');
select fc_executa_ddl('DROP SEQUENCE IF EXISTS avaliacaogruporespostacgm_eso02_sequencial_seq');

--Retorna os valores j� respondidos do eSocial
select fc_executa_ddl('INSERT INTO avaliacaogruporespostarhpessoal (SELECT eso02_sequencial, eso02_avaliacaogruporesposta, matricula FROM w_avaliacaogruporespostacgm);');

--Ajuste o valor da sequence
select fc_executa_ddl('select setval(''avaliacaogruporespostarhpessoal_eso02_sequencial_seq'', (select max(eso02_sequencial) from w_avaliacaogruporespostacgm));');