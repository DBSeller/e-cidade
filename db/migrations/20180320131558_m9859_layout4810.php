<?php

use Classes\PostgresMigration;

class M9859Layout4810 extends PostgresMigration
{
    public function up()
    {
        $sSql = "
            insert into db_layouttxt( db50_codigo ,db50_layouttxtgrupo ,db50_descr ,db50_quantlinhas ,db50_obs) values (302, 3, 'BVER_ENC', 0, '');
            INSERT INTO db_layouttxt VALUES (301, 'TCE_4810 - FOLHA DE PAGAMENTO', 0, '', 2);

            INSERT INTO db_layoutlinha VALUES (1014, 301, 'HEADER DO ARQUIVO', 1, 130, 0, 0, '', NULL, false);
            INSERT INTO db_layoutlinha VALUES (1015, 301, 'REGISTRO', 3, 141, 0, 0, '', NULL, false);
            INSERT INTO db_layoutlinha VALUES (1016, 301, 'TRAILLER DO ARQUIVO', 5, 22, 0, 0, '', NULL, false);
            
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1014, 'cnpjsetorgoverno', 'CNPJ SETOR DE GOVERNO (ÓRGÃO/ENTIDADE)', 2, 1, '', 14, false, true, 'e', 'Informar  o  CNPJ  do  Setor  de  Governo, (órgão/entidade)  junto  ao  Ministério  da Fazenda', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1014, 'datainicialinformacao', 'DATA INICIAL DA INFORMAÇÃO', 4, 15, '', 8, false, true, 'e', 'Informar  a  data  inicial  do  período  (1º  de janeiro  do  exercício  referente  a  entrega dos dados), no formato ddmmaaaa', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1014, 'datafinalinformacao', 'DATA FINAL DA INFORMAÇÃO', 4, 23, '', 8, false, true, 'e', 'Informar  a  data  final  do  período  a  que  se referem os dados, no formato ddmmaaaa', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1014, 'datageracaoarquivo', 'DATA DA GERAÇÃO DO ARQUIVO', 4, 31, '', 8, false, true, 'e', 'Informar a data da geração do arquivo noformato ddmmaaaa', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1014, 'nomesetorgoverno', 'NOME SETOR DE GOVERNO (ÓRGÃO/ENTIDADE)', 13, 39, '', 80, false, true, 'd', 'Nome  do  órgão  ou  entidade  responsável pelos dados e informações', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1014, 'codigoremessa', 'CÓDIGO DA REMESSA', 2, 119, '', 12, false, true, 'e', 'Código  da  Remessa.  Deverá  ser  gerado pelo  próprio  Órgão/Entidade,  e  será utilizado  como  identificador  exclusivo  da remessa', 0);
            
            
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'codigotipofolha', 'CÓDIGO DO TIPO DA FOLHA', 2, 1 , '', 1, false, true, 'e', 'Preencher com:
            1 = Folha Normal
            2 = 13º Salário
            3 = Férias
            4 = Rescisão
            5 = Complementar
            6 = Afastamentos
            9 = Outros (Especificar no campo Observações ou no arquivo LEIAME.TXT)', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'codigoregistrofuncionario', 'CÓDIGO DO REGISTRO DO FUNCIONÁRIO', 2, 2, '', 12, false, true, 'e', 'Codificação própria', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'datacompetenciafolha', 'DATA DE COMPETÊNCIA DA FOLHA', 4, 14, '', 8, false, true, 'e', 'Formato ddmmaaaa', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'datapagamentofolha', 'DATA DE PAGAMENTO DA FOLHA', 4, 22, '', 8, false, true, 'e', 'Formato ddmmaaaa', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'codigovantagemdescontototalizador', 'CÓDIGO VANTAGEM /DESCONTO/ TOTALIZADOR', 2, 30, '', 3, false, true, 'e', 'Codificação própria', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'valorvantagemdescontototalizador', 'VALOR VANTAGEM/ DESCONTO/ TOTALIZADOR', 3, 33, '', 17, false, true, 'e', 'Formato valor', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'identificacaooperacao', 'IDENTIFICAÇÃO DA OPERAÇÃO', 13, 50, '', 1, false, true, 'd', 'Preencher com:V = Vantagem;D = Desconto;T = Totalizador;O = Outros (Especificar no campo Observações)', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'indicadorincidenciairrf', 'INDICADOR DE INCIDÊNCIA DO IRRF', 13, 51, '', 1, false, true, 'd', 'Indicador de incidência ou dedutibilidade da Vantagem ou Desconto para efeitos do IRRF. Preencher com:
            S = SimN = Não', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'codigobancodepositofolhapagentidad', 'CÓDIGO BANCO DEPÓSITO FOLHA PAG.ENTIDAD.', 2, 52, '', 5, false, true, 'e', 'Padrão Febraban ', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'codigoagencdepositofolhapagentidad', 'CÓDIGO AGÊNC DEPÓSITO FOLHA PAG.ENTIDAD.', 2, 57, '', 5, false, true, 'e', 'Padrão Febraban ', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'codcontacorrbancodepfolhapagent', 'CÓD. CONTA-CORR.BANCO DEP.FOLHA.PAG.ENT', 2, 62, '', 20, false, true, 'e', 'Cadastro  próprio  do  Banco com dígito verificador', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'codigobancofuncionario', 'CÓDIGO DO BANCO DO FUNCIONÁRIO', 2, 82, '', 5, false, true, 'e', 'Padrão Febraban', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'codigoagenciabancofuncionario', 'CÓDIGO DA AGÊNCIA DO BANCO FUNCIONÁRIO', 2, 87, '', 5, false, true, 'e', 'Padrão Febraban', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'codigocontacorrentebancofuncionario', 'CÓDIGO CONTA-CORRENTE BANCO FUNCIONARIO', 2, 92, '', 20, false, true, 'e', 'Cadastro  próprio  do  Banco com digito verificador', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'observacoes', 'OBSERVAÇÕES', 13, 112, '', 30, false, true, 'd', 'Se  for  necessário  mais espaço  para  Observações, utilizar o arquivo LEIAME.TXT', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'rubrica', 'RUBRICA', 2, 142, '', 5, false, true, 'e', '', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'indicadorincidenciasaude', 'INDICADOR DE INCIDÊNCIA DA SAÚDE', 13, 147, '', 1, false, true, 'd', '', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'indicadorincidenciarpps', 'INDICADOR DE INCIDÊNCIA DO RPPS', 13, 148, '', 1, false, true, 'd', '', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'indicadorincidenciainss', 'INDICADOR DE INCIDÊNCIA DO INSS', 13, 149, '', 1, false, true, 'd', '', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'percentualdescontoirpf', 'PERCENTUAL DE DESCONTO DO IRPF', 2, 150, '', 4, false, true, 'e', '', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'percentualdescontoprevidencia', 'PERCENTUAL DE DESCONTO DA PREVIDÊNCIA', 2, 154, '', 4, false, true, 'e', '', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'percentualdescontosaude', 'PERCENTUAL DE DESCONTO DA SAÚDE', 2, 158, '', 4, false, true, 'e', '', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'indicadordesconto', 'IDENTIFICADOR DE DESCONTO', 2, 162, '', 1, false, true, 'e', '', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1015, 'indicadordescontosaude', 'IDENTIFICADOR DE DESCONTO DA SAÚDE', 2, 163, '', 1, false, true, 'e', '', 0);
            
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1016, 'totalregistros', 'TOTAL REGISTROS', 2, 12, '', 10, false, true, 'e', 'Totalizador de Registros, onde contem a quantidade de registros gerada no arquivo', 0);
            INSERT INTO db_layoutcampos VALUES (nextval('db_layoutcampos_db52_codigo_seq'), 1016, 'descricao', 'DESCRICAO', 13, 1, '', 11, false, true, 'd', 'Valor padrão “FINALIZADOR”', 0);
        ";

        $this->execute($sSql);
    }

    public function down()
    {
        $this->execute("DELETE FROM db_layoutcampos WHERE db52_layoutlinha in (1014, 1015, 1016)");
        $this->execute("DELETE FROM db_layoutlinha WHERE db51_codigo in (1014, 1015, 1016)");
        $this->execute("DELETE FROM db_layouttxt WHERE db50_codigo in (301, 302)");
    }
}
