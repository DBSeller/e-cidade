<?php

use Classes\PostgresMigration;

class M12227MigracaoRecursosLoa extends PostgresMigration
{

    /**
     * @var array
     */
    private $estruturaisCadastrados = array();

    /**
     * @var integer
     */
    private $sequencial = 10000;

    public function up()
    {

        $rsInstituicao = $this->query("select db21_codcli from configuracoes.db_config where db21_codcli = 7107");

        $this->migracaoDespesa();
        $this->migracaoReceita();

    }

    private function migracaoDespesa()
    {
        $buscaFontes = <<<SQL_BUSCA
            select c333_identificadoruso, c333_tipodetalhamento, c333_grupofonterecursos, c333_especificacaofonte
              from previsaodespesa
             group by c333_identificadoruso,c333_tipodetalhamento,c333_grupofonterecursos,c333_especificacaofonte 
SQL_BUSCA;

        $buscaFontes = $this->fetchAll($buscaFontes);

        foreach ($buscaFontes as $dados) {

            $identificadorUso = $dados['c333_identificadoruso'];
            $tipo             = $dados['c333_tipodetalhamento'];
            $grupo            = $dados['c333_grupofonterecursos'];
            $especificacao    = $dados['c333_especificacaofonte'];

            $estruturalDescricao = "{$identificadorUso}.{$tipo}.{$grupo}.{$especificacao}";
            $estrutural = str_pad("{$grupo}{$especificacao}", 4, '0');

            if ( in_array($estruturalDescricao, $this->estruturaisCadastrados) ) {
                continue;
            }

            $this->estruturaisCadastrados[] = $estruturalDescricao;
            $descricao = substr("{$estruturalDescricao} - {$this->getDescricaoFinal($especificacao)}", 0, 60);

            $row = $this->fetchRow("select * from db_estruturavalor where db121_estrutural = '{$estrutural}' and db121_db_estrutura = 6;");
            if (!empty($row['db121_sequencial'])) {
                $sequencialEstruturaValor = $row['db121_sequencial'];
            } else {

                $this->execute("insert into db_estruturavalor values (nextval('db_estruturavalor_db121_sequencial_seq'), 6, '{$estrutural}', '{$descricao}', NULL, 0, null);");
                $sequencialEstruturaValor = "currval('db_estruturavalor_db121_sequencial_seq')";
            }
            $stringInsert = "
                insert into orctiporec(o15_codigo, o15_descr, o15_codtri, o15_db_estruturavalor, o15_loaidentificadoruso, o15_loatipo, o15_loagrupo, o15_loaespecificacao)
                     values ({$this->sequencial}, '{$descricao}', '{$estrutural}', {$sequencialEstruturaValor}, {$identificadorUso}, {$tipo}, {$grupo}, '{$especificacao}' ); 
            ";

            $this->execute($stringInsert);
            $this->sequencial++;
        }
    }

    private function migracaoReceita()
    {

        $recursosReceita = $this->fetchAll(
            <<<SQL_MIGRACAO_RECEITA
select c06_sequencial, array_to_string(array_accum(resposta), '.') as estrutural
from (
       SELECT DISTINCT
         avaliacaogruporespostaconta.c06_sequencial,
         avaliacaopergunta.db103_sequencial,
         avaliacaopergunta.db103_descricao AS pergunta,
         db103_identificadorcampo,
         CASE
           WHEN avaliacaoresposta.db106_resposta <> ''
             THEN avaliacaoresposta.db106_resposta
           ELSE avaliacaoperguntaopcao.db104_valorresposta
           END                             AS resposta
       FROM avaliacaogruporespostaconta
              JOIN avaliacaogruporesposta
                   ON avaliacaogruporesposta.db107_sequencial = avaliacaogruporespostaconta.c06_avaliacaogruporesposta
              JOIN avaliacaogrupoperguntaresposta ON avaliacaogrupoperguntaresposta.db108_avaliacaogruporesposta =
                                                     avaliacaogruporesposta.db107_sequencial
              JOIN avaliacaoresposta
                   ON avaliacaoresposta.db106_sequencial = avaliacaogrupoperguntaresposta.db108_avaliacaoresposta
              JOIN avaliacaoperguntaopcao
                   ON avaliacaoperguntaopcao.db104_sequencial = avaliacaoresposta.db106_avaliacaoperguntaopcao
              JOIN avaliacaopergunta
                   ON avaliacaopergunta.db103_sequencial = avaliacaoperguntaopcao.db104_avaliacaopergunta
              JOIN conplanoorcamento ON conplanoorcamento.c60_codcon = avaliacaogruporespostaconta.c06_conta
           AND conplanoorcamento.c60_anousu = avaliacaogruporespostaconta.c06_ano
       WHERE conplanoorcamento.c60_anousu = 2019
         and avaliacaopergunta.db103_identificadorcampo in
             ('id_uso', 'previsaoTipoDetalhamento', 'grupo_fonte_recurso', 'especificacao_fonte')
         and length(db106_resposta) <= 2
       ORDER BY avaliacaogruporespostaconta.c06_sequencial, avaliacaopergunta.db103_sequencial
    ) as x group by x.c06_sequencial;
SQL_MIGRACAO_RECEITA
        );

        foreach ($recursosReceita as $dadosRecurso) {

            $estrutural = $dadosRecurso['estrutural'];

            $explodeEstrutural = explode('.', $estrutural);
            if ( count($explodeEstrutural) !== 4 ) {
                continue;
            }

            $identificadorUso = $explodeEstrutural[0];
            $tipo             = $explodeEstrutural[1];
            $grupo            = $explodeEstrutural[2];
            $especificacao    = $explodeEstrutural[3];

            if ( in_array($estrutural, $this->estruturaisCadastrados) ) {
                continue;
            }
            $this->estruturaisCadastrados[] = $estrutural;

            $codigoTribunal = "0{$grupo}{$especificacao}";
            $descricao = substr("{$estrutural} - {$this->getDescricaoFinal($especificacao)}", 0, 60);
            $row = $this->fetchRow("select * from db_estruturavalor where db121_estrutural = '{$codigoTribunal}' and db121_db_estrutura = 6;");
            if (!empty($row['db121_sequencial'])) {
                $sequencialEstruturaValor = $row['db121_sequencial'];
            } else {
                $sequencialEstruturaValor = "currval('db_estruturavalor_db121_sequencial_seq')";
                $this->execute("insert into db_estruturavalor values (nextval('db_estruturavalor_db121_sequencial_seq'), 6, '{$codigoTribunal}', '{$descricao}', NULL, 0, null);");
            }

            $stringInsert = "
                insert into orctiporec(o15_codigo, o15_descr, o15_codtri, o15_db_estruturavalor, o15_loaidentificadoruso, o15_loatipo, o15_loagrupo, o15_loaespecificacao)
                     values ({$this->sequencial}, '{$descricao}', '{$codigoTribunal}', {$sequencialEstruturaValor}, {$identificadorUso}, {$tipo}, {$grupo}, '{$especificacao}' ); 
            ";
            $this->execute($stringInsert);
            $this->sequencial++;

        }

    }

    private function getDescricaoFinal($valor) {

        $especificacao = array(
            '00' => 'Ordinários Não Provenientes de Impostos',
            '01' => 'Operações de Crédito',
            '02' => 'Recursos de Convênios',
            '03' => 'Recursos Próprios Não Financeiros',
            '05' => 'Contribuição do Salário-Educação',
            '06' => 'Recursos Destinados à Alimentação Escolar',
            '07' => 'Recursos do Sistema Único de Saúde',
            '08' => 'Recursos do Fundo Nacional de Assistência Social',
            '10' => 'Recursos Vinculados ao Fundo de Mobilidade',
            '12' => 'Outorga Onerosa do Direito de Construir',
            '13' => 'Ordinários Provenientes de Impostos',
            '14' => 'Transferências Constitucionais Provenientes de Impostos',
            '15' => 'Recursos do Fundeb',
            '17' => 'Outras Transferências da União',
            '18' => 'Recursos Vinculados à Previdência Municipal',
            '36' => 'Recursos de Multas de Trânsito',
            '37' => 'Contribuição sobre a Iluminação Pública',
            '38' => 'Compensação Financeira pela Exploração e Produção de Petróleo',
            '53' => 'Taxas e Multas pelo Exercício do Poder de Polícia',
            '80' => 'Remuneração das Disponibilidades do Tesouro',
            '82' => 'Recursos Próprios Financeiros',
            '83' => 'Recursos de Alienação de Bens e Direitos do Patrimônio Público',
            '90' => 'Recursos do Tesouro - a Definir',
            '99' => 'Recursos Extraorçamentários',
        );
        return !empty($especificacao[$valor]) ? $especificacao[$valor] :  '';
    }


    public function down()
    {

        $this->execute("delete from orctiporec where o15_codigo >= 10000;");

    }

}
