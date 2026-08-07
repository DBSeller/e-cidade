<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22116AlteracaoTabelaFontesiconfi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upNomeMenu();
        $this->upEstrutura();
        $this->upMigracao();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downEstrutura();
        $this->downMigracao();
    }

    private function upEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_syscampo values(1014134,'finalidade','varchar(255)','Finalidade detalhamento do recurso. ','', 'Finalidade',255,'f','t','f',0,'text','Finalidade');
insert into db_sysarqcamp values (1010850,1014134,4,0);
alter table orcamento.fontesiconfi add column finalidade text;
SQL
        );
    }

    private function downEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
alter table orcamento.fontesiconfi drop column finalidade;
delete from db_sysarqcamp where codarq = 1010850 and codcam = 1014134;
delete from db_syscampo where codcam = 1014134;
SQL
        );
    }

    private function downMigracao()
    {
        DB::connection()->getPdo()->exec(<<<SQL
truncate orcamento.fontesiconfi;

insert into orcamento.fontesiconfi
values ('1500','Recursos não vinculados de Impostos', 2),
       ('1501','Outros Recursos não Vinculados', 2),
       ('1540','Transferências do FUNDEB - Impostos e Transferências de Impostos', 3),
       ('1541','Transferências do FUNDEB - Complementação da União - VAAF', 3),
       ('1542','Transferências do FUNDEB - Complementação da União - VAAT', 3),
       ('1543','Transferências do FUNDEB - Complementação da União - VAAR', 3),
       ('1544','Recursos de Precatórios do FUNDEF', 3),
       ('1550','Transferência do Salário-Educação', 3),
       ('1551','Transferências de Recursos do FNDE Referentes ao Programa Dinheiro Direto na Escola (PDDE)', 3),
       ('1552','Transferências de Recursos do FNDE Referentes ao Programa Nacional de Alimentação Escolar (PNAE)', 3),
       ('1553','Transferências de Recursos do FNDE Referentes ao Programa Nacional de Apoio ao Transporte Escolar (PNATE)', 3),
       ('1569','Outras Transferências de Recursos do FNDE', 3),
       ('9570','Transferências do Governo Federal referentes a Convênios e outros Repasses vinculados à Educação', 3),
       ('9571','Transferências do Estado referentes a Convênios e outros Repasses vinculados à Educação', 3),
       ('9572','Transferências de Municípios referentes a Convênios e outros Repasses vinculados à Educação', 3),
       ('1573','Royalties do Petróleo e Gás Natural Vinculados à Educação', 3),
       ('9574','Operações de Crédito Vinculadas à Educação', 3),
       ('9575','Outras Transferências de Convênios e Instrumentos Congêneres vinculados à Educação', 3),
       ('1576','Transferências de Recursos dos Estados para programas de educação', 3),
       ('1599','Outros Recursos Vinculados à Educação', 3),
       ('1600','Transferências Fundo a Fundo de Recursos do SUS provenientes do Governo Federal - Bloco de Manutenção das Ações e Serviços Públicos de Saúde',4),
       ('1601','Transferências Fundo a Fundo de Recursos do SUS provenientes do Governo Federal - Bloco de Estruturação da Rede de Serviços Públicos de Saúde',4),
       ('1602','Transferências Fundo a Fundo de Recursos do SUS provenientes do Governo Federal - Bloco de Manutenção das Ações e Serviços Públicos de Saúde - Recursos destinados ao enfrentamento da COVID-19 no bojo da ação 21C0.',4),
       ('1603','Transferências Fundo a Fundo de Recursos do SUS provenientes do Governo Federal - Bloco de Estruturação da Rede de Serviços Públicos de Saúde - Recursos destinados ao enfrentamento da COVID-19 no bojo da ação 21C0.',4),
       ('1621','Transferências Fundo a Fundo de Recursos do SUS provenientes do Governo Estadual',4),
       ('1622','Transferências Fundo a Fundo de Recursos do SUS provenientes dos Governos Municipais',4),
       ('9631','Transferências do Governo Federal referentes a Convênios e outros Repasses vinculados à Saúde',4),
       ('9632','Transferências do Estado referentes a Convênios e outros Repasses vinculados à Saúde',4),
       ('9633','Transferências de Municípios referentes a Convênios e outros Repasses vinculados à Saúde',4),
       ('9634','Operações de Crédito vinculadas à Saúde',4),
       ('1635','Royalties do Petróleo e Gás Natural vinculados à Saúde',4),
       ('9636','Outras Transferências de Convênios e Instrumentos Congêneres vinculados à Saúde',4),
       ('1659','Outros Recursos Vinculados à Saúde',4),
       ('1660','Transferência de Recursos do Fundo Nacional de Assistência Social - FNAS', 5),
       ('1661','Transferência de Recursos dos Fundos Estaduais de Assistência Social', 5),
       ('9665','Transferências de Convênios e outros Repasses vinculados à Assistência Social', 5),
       ('1669','Outros Recursos Vinculados à Assistência Social', 5),
       ('9700','Outras Transferências de Convênios ou Repasses da União', 6),
       ('9701','Outras Transferências de Convênios ou Repasses dos Estados', 6),
       ('9702','Outras Transferências de Convênios ou Repasses dos Municípios', 6),
       ('9703','Outras Transferências de Convênios ou Contratos de Repasse de outras Entidades', 6),
       ('1704','Transferência da União Referente a Royalties do Petróleo e Gás Natural', 6),
       ('1705','Transferência dos Estados Referente a Royalties do Petróleo e Gás Natural', 6),
       ('1706','Transferência Especial da União', 6),
       ('1707','Transferências da União - inciso I do art. 5º da Lei Complementar 173/2020', 6),
       ('1708','Transferência da União Referente à Compensação Financeira de Recursos Minerais', 6),
       ('1709','Transferência da União referente à Compensação Financeira de Recursos Hídricos', 6),
       ('1710','Transferência Especial dos Estados', 6),
       ('1749','Outras vinculações de transferências', 6),
       ('1750','Recursos da Contribuição de Intervenção no Domínio Econômico - CIDE', 7),
       ('1751','Recursos da Contribuição para o Custeio do Serviço de Iluminação Pública - COSIP', 7),
       ('1752','Recursos Vinculados ao Trânsito', 7),
       ('1753','Recursos provenientes de taxas e contribuições', 7),
       ('9754','Recursos de Operações de Crédito', 7),
       ('1755','Recursos de Alienação de Bens/Ativos - Administração Direta', 7),
       ('1756','Recursos de Alienação de Bens/Ativos - Administração Indireta', 7),
       ('1757','Recursos de depósitos judiciais - Lides das quais o ente faz parte', 7),
       ('1758','Recursos de depósitos judiciais - Lides das quais o ente não faz parte', 7),
       ('1759','Recursos vinculados a fundos', 7),
       ('1760','Recursos de Emolumentos e Taxas judiciais', 7),
       ('1761','Recursos vinculados ao Fundo de Combate e Erradicação da Pobreza', 7),
       ('1799','Outras vinculações legais', 7),
       ('1800','Recursos vinculados ao RPPS - Fundo em Capitalização (Plano Previdenciário)', 8),
       ('1801','Recursos vinculados ao RPPS - Fundo em Repartição (Plano Financeiro)', 8),
       ('1802','Recursos vinculados ao RPPS - Taxa de Administração', 8),
       ('1803','Recursos vinculados ao Sistema de Proteção Social dos Militares (SPSM)', 8),
       ('1860','Recursos extraorçamentários vinculados a precatórios', 9),
       ('1861','Recursos extraorçamentários vinculados a depósitos judiciais', 9),
       ('1862','Depósitos de terceiros', 9),
       ('1869','Outros recursos extraorçamentários', 9),
       ('1880','Recursos próprios dos consórcios', 10),
       ('1898','Recursos não classificados - a classificar', 10),
       ('1899','Outros Recursos Vinculados', 10);
SQL
        );
    }

    private function upMigracao()
    {
        DB::connection()->getPdo()->exec(<<<SQL
truncate orcamento.fontesiconfi;

insert into orcamento.fontesiconfi (classificacaofr_id, codigo_siconfi, descricao, finalidade)
values (2,'500','Recursos não Vinculados de Impostos','Recursos de impostos e transferências de impostos de livre aplicação. Em atendimento ao disposto no inciso X do art. 4º da Lei Complementar nº 141, de 13 de janeiro de 2012, para identificação do percentual mínimo aplicado em ASPS, essa fonte de recursos deverá ser associada ao marcador que identifica as despesas que podem ser consideradas para esse limite. A mesma lógica será utilizada para a identificação do percentual mínimo de aplicação em MDE.'),
(2,'501','Outros Recursos não Vinculados','Outros recursos não vinculados que não se enquadram na especificação acima.'),
(3,'540','Transferências do FUNDEB - Impostos e Transferências de Impostos','Controle dos recursos recebidos do FUNDEB referente à repartição dentro de cada Estado, com base nos incisos I, II e III do art. 212-A da Constituição Federal. Na fase da despesa, quando for o caso, será necessário associar esta fonte ao marcador do percentual de aplicação no pagamento da remuneração dos profissionais da educação básica em efetivo exercício para identificar o cumprimento do percentual mínimo de 70% estabelecido no inciso XI do art. 212-A da CF.'),
(3,'541','Transferências do FUNDEB - Complementação da União - VAAF','Controle dos recursos de complementação da União ao FUNDEB - VAAF, com base na alínea a do inciso V do art. 212-A da Constituição Federal. Na fase da despesa, quando for o caso, será necessário associar esta fonte ao marcador do percentual de aplicação no pagamento da remuneração dos profissionais da educação básica em efetivo exercício para identificar o cumprimento do percentual mínimo de 70% estabelecido no inciso XI do art. 212-A da CF.'),
(3,'542','Transferências do FUNDEB - Complementação da União - VAAT','Controle dos recursos de complementação da União ao FUNDEB - VAAT, com base na alínea b do inciso V do art. 212-A da Constituição Federal. Na fase da despesa, quando for o caso, será necessário associar esta fonte ao marcador do percentual de aplicação no pagamento da remuneração dos profissionais da educação básica em efetivo exercício para identificar o cumprimento do percentual mínimo de 70% estabelecido no inciso XI do art. 212-A da CF.'),
(3,'543','Transferências do FUNDEB - Complementação da União - VAAR','Controle dos recursos de complementação da União ao FUNDEB - VAAR, com base na alínea c, inciso V do art. 212-A da Constituição Federal.'),
(3,'544','Recursos de Precatórios do FUNDEF','Controle dos recursos decorrentes do recebimento de precatórios derivados de ações judiciais associadas à complementação devida pela União ao Fundo de Manutenção e Desenvolvimento do Ensino Fundamental e de Valorização do Magistério dos demais entes federados (Precatórios Fundef).'),
(3,'550','Transferência do Salário-Educação','Controle dos recursos originários de transferências recebidas do Fundo Nacional do Desenvolvimento da Educação - FNDE, relativos aos repasses referentes ao salário-educação.'),
(3,'551','Transferências de Recursos do FNDE referentes ao Programa Dinheiro Direto na Escola (PDDE)','Controle dos recursos originários de transferências do Fundo Nacional do Desenvolvimento da Educação - FNDE, destinados ao Programa Dinheiro Direto na Escola (PDDE).'),
(3,'552','Transferências de Recursos do FNDE referentes ao Programa Nacional de Alimentação Escolar (PNAE)','Controle dos recursos originários de transferências do Fundo Nacional do Desenvolvimento da Educação - FNDE, destinados ao Programa Nacional de Alimentação Escolar (PNAE).'),
(3,'553','Transferências de Recursos do FNDE Referentes ao Programa Nacional de Apoio ao Transporte Escolar (PNATE)','Controle dos recursos originários de transferências do Fundo Nacional do Desenvolvimento da Educação - FNDE, destinados ao Programa Nacional de Apoio ao Transporte Escolar (PNATE).'),
(3,'569','Outras Transferências de Recursos do FNDE','Controle dos demais recursos originários de transferências do Fundo Nacional do Desenvolvimento da Educação - FNDE.'),
(3,'570','Transferências do Governo Federal referentes a Convênios e Instrumentos Congêneres vinculados à Educação','Controle dos recursos originários de transferências em decorrência da celebração de convênios e instrumentos congêneres com a União, cuja destinação encontra-se vinculada a programas da educação.'),
(3,'571','Transferências do Estado referentes a Convênios e Instrumentos Congêneres vinculados à Educação','Controle dos recursos originários de transferências em decorrência da celebração de convênios e instrumentos congêneres com os Estados, cuja destinação encontra-se vinculada a programas da educação.'),
(3,'572','Transferências de Municípios referentes a Convênios e Instrumentos Congêneres vinculados à Educação','Controle dos recursos originários de transferências em decorrência da celebração de convênios e instrumentos congêneres com outros municípios, cuja destinação encontra-se vinculada a programas da educação.'),
(3,'573','Royalties do Petróleo e Gás Natural Vinculados à Educação','Controle dos recursos vinculados à Educação, originários de transferências recebidas pelos entes, relativos a Royalties e Participação Especial - Art. 2º da Lei nº 12.858/2013.'),
(3,'574','Operações de Crédito Vinculadas à Educação','Controle dos recursos originários de operações de crédito, cuja destinação encontra-se vinculada a programas da educação.'),
(3,'575','Outras Transferências de Convênios e Instrumentos Congêneres vinculados à Educação','Controle dos recursos originários de transferências de entidades privadas, estrangeiras ou multigovernamentais em virtude de assinatura de convênios e instrumentos congêneres, cuja destinação encontra-se vinculada a programas de educação.'),
(3,'576','Transferências de Recursos dos Estados para programas de educação','Controle dos recursos transferidos pelos Estados para programas de educação, que não decorram de celebração de convênios, contratos de repasse e termos de parceria.'),
(3,'599','Outros Recursos Vinculados à Educação','Controle dos demais recursos vinculados à Educação, não enquadrados nas especificações anteriores.'),
(4,'600','Transferências Fundo a Fundo de Recursos do SUS provenientes do Governo Federal - Bloco de Manutenção das Ações e Serviços Públicos de Saúde','Controle dos recursos originários de transferências do Fundo Nacional de Saúde, referentes ao Sistema Único de Saúde (SUS) e relacionados ao Bloco de Manutenção das Ações e Serviços Públicos de Saúde.'),
(4,'601','Transferências Fundo a Fundo de Recursos do SUS provenientes do Governo Federal - Bloco de Estruturação da Rede de Serviços Públicos de Saúde','Controle dos recursos originários de transferências do Fundo Nacional de Saúde, referentes ao Sistema Único de Saúde (SUS) e relacionados ao Bloco de Estruturação na Rede de Serviços Públicos de Saúde.'),
(4,'602','Transferências Fundo a Fundo de Recursos do SUS provenientes do Governo Federal - Bloco de Manutenção das Ações e Serviços Públicos de Saúde - Recursos destinados ao enfrentamento da COVID-19 no bojo da ação 21C0.','Controle dos recursos originários de transferências do Fundo Nacional de Saúde, referentes ao Sistema Único de Saúde (SUS), relacionados ao Bloco de Manutenção das Ações e Serviços Públicos de Saúde, e destinados ao enfrentamento da COVID-19 no bojo da ação 21C0 do orçamento da União.'),
(4,'603','Transferências Fundo a Fundo de Recursos do SUS provenientes do Governo Federal - Bloco de Estruturação da Rede de Serviços Públicos de Saúde - Recursos destinados ao enfrentamento da COVID-19 no bojo da ação 21C0.','Controle dos recursos originários de transferências do Fundo Nacional de Saúde, referentes ao Sistema Único de Saúde (SUS), relacionados ao Bloco de Estruturação na Rede de Serviços Públicos de Saúde e destinados ao enfrentamento da COVID-19 no bojo da ação 21C0 do orçamento da União.'),
(4,'604','Transferências provenientes do Governo Federal destinadas ao vencimento dos agentes comunitários de saúde e dos agentes de combate às endemias','Controle dos recursos originários do Governo Federal, referentes ao Sistema Único de Saúde (SUS), relacionados ao vencimento dos agentes comunitários de saúde e dos agentes de combate às endemias, nos termos do art. 198, §7ª da Constituição Federal.'),
(4,'621','Transferências Fundo a Fundo de Recursos do SUS provenientes do Governo Estadual','Controle dos recursos originários de transferências do Fundo Estadual de Saúde, referentes ao Sistema Único de Saúde (SUS).'),
(4,'622','Transferências Fundo a Fundo de Recursos do SUS provenientes dos Governos Municipais','Controle dos recursos originários de transferências dos Fundos de Saúde de outros municípios, referentes ao Sistema Único de Saúde (SUS).'),
(4,'631','Transferências do Governo Federal referentes a Convênios e Instrumentos Congêneres vinculados à Saúde','Controle dos recursos originários de transferências em decorrência da celebração de convênios e instrumentos congêneres com a União, cuja destinação encontra-se vinculada a programas da saúde.'),
(4,'632','Transferências do Estado referentes a Convênios e Instrumentos Congêneres vinculados à Saúde','Controle dos recursos originários de transferências em decorrência da celebração de convênios e instrumentos congêneres com os Estados, cuja destinação encontra-se vinculada a programas da saúde.'),
(4,'633','Transferências de Municípios referentes a Convênios Instrumentos Congêneres vinculados à Saúde','Controle dos recursos originários de transferências em decorrência da celebração de convênios e instrumentos congêneres com outros Municípios, cuja destinação encontra-se vinculada a programas da saúde.'),
(4,'634','Operações de Crédito vinculadas à Saúde','Controle dos recursos originários de operações de crédito, cuja destinação encontra-se vinculada a programas da saúde.'),
(4,'635','Royalties do Petróleo e Gás Natural vinculados à Saúde','Controle dos recursos vinculados à Saúde, originários de transferências recebidas pelos entes, relativos a Royalties e Participação Especial - Art. 2º da Lei nº 12.858/2013.'),
(4,'636','Outras Transferências de Convênios e Instrumentos Congêneres vinculados à Saúde','Controle dos recursos originários de transferências de entidades privadas, estrangeiras ou multigovernamentais em virtude de assinatura de convênios e instrumentos congêneres, cuja destinação encontra-se vinculada a programas de saúde.'),
(4,'659','Outros Recursos Vinculados à Saúde','Controle dos demais recursos vinculados à Saúde, não enquadrados nas especificações anteriores.'),
(5,'660','Transferência de Recursos do Fundo Nacional de Assistência Social - FNAS','Controle os recursos originários de transferências do Fundo Nacional de Assistência Social - Lei Federal nº 8.742, 07/12/1993.'),
(5,'661','Transferência de Recursos dos Fundos Estaduais de Assistência Social','Controle dos recursos originários de transferências dos fundos estaduais de assistência social.'),
(5,'662','Transferências de Recursos dos Fundos Municipais de Assistência Social','Controle os recursos originários de transferência dos fundos municipais de assistência social.'),
(5,'665','Transferências de Convênios e Instrumentos Congêneres vinculados à Assistência Social','Controle dos recursos originários de transferências em decorrência da celebração de convênios e instrumentos congêneres cuja destinação encontra-se vinculada a programas da assistência social.'),
(5,'669','Outros Recursos Vinculados à Assistência Social','Controle dos demais recursos vinculados à Assistência Social, não enquadrados nas especificações anteriores.'),
(6,'700','Outras Transferências de Convênios ou Instrumentos Congêneres da União','Controle dos recursos originários de transferências federais em decorrência da celebração de convênios e instrumentos congêneres cuja destinação encontra-se vinculada aos seus objetos. Não serão controlados por esta fonte os recursos de convênios vinculados a programas da educação, da saúde e da assistência social.'),
(6,'701','Outras Transferências de Convênios ou Instrumentos Congêneres dos Estados','Controle dos recursos originários de transferências estaduais em decorrência da celebração de convênios e instrumentos congêneres, cuja destinação encontra-se vinculada aos seus objetos. Não serão controlados por esta fonte os recursos de convênios ou contratos de repasse vinculados a programas da educação, da saúde e da assistência social.'),
(6,'702','Outras Transferências de Convênios ou Instrumentos Congêneres dos Municípios','Controle dos recursos originários de transferências de municípios em decorrência da celebração de convênios e instrumentos congêneres, cuja destinação encontra-se vinculada aos seus objetos. Não serão controlados por esta fonte os recursos de convênios ou contratos de repasse vinculados a programas da educação, da saúde e da assistência social.'),
(6,'703','Outras Transferências de Convênios ou Instrumentos Congêneres de outras Entidades','Controle dos recursos originários de transferências de entidades privadas, estrangeiras ou multigovernamentais em virtude de assinatura de convênios e instrumentos congêneres, cuja destinação encontra-se vinculada aos seus objetos. Não serão controlados por esta fonte os recursos de convênios ou contratos de repasse vinculados a programas da educação, da saúde e da assistência social.'),
(6,'704','Transferências da União Referentes a Compensações Financeiras pela Exploração de Recursos Naturais','Controle dos recursos transferidos pela União, originários da arrecadação de royalties do petróleo, do gás natural, da cota-parte do bônus de assinatura de contrato de partilha de produção, exceto os recursos provenientes da Lei nº 12.858/2013, destinados às áreas da saúde ou da educação.'),
(6,'705','Transferências dos Estados Referentes a Compensações Financeiras pela Exploração de Recursos Naturais','Controle dos recursos transferidos pelos Estados, originários da arrecadação de royalties do petróleo, do gás natural, da cota-parte do bônus de assinatura de contrato de partilha de produção.'),
(6,'706','Transferência Especial da União','Controle dos recursos transferidos pela União provenientes de emendas individuais impositivas ao orçamento da União, por meio de transferências especiais, nos termos do art. 166-A da Constituição Federal.'),
(6,'707','Transferências da União - inciso I do art. 5º da Lei Complementar 173/2020','Controle dos recursos provenientes de transferência da União com base no disposto no inciso I do art. 5º da Lei Complementar 173, de 27 de maio de 2020.'),
(6,'708','Transferência da União Referente à Compensação Financeira de Recursos Minerais','Controle dos recursos transferidos pela União, referentes à compensação financeira pela exploração de recursos minerais em atendimento às destinações e vedações previstas na legislação.'),
(6,'709','Transferência da União referente à Compensação Financeira de Recursos Hídricos','Controle dos recursos transferidos pela União, referentes à compensação financeira de recursos hídricos em atendimento às destinações e vedações previstas na legislação.'),
(6,'710','Transferência Especial dos Estados','Controle dos recursos transferidos pelos Estados provenientes de emendas individuais impositivas ao orçamento desses entes, por meio de transferências especiais, nos termos das constituições estaduais que reproduziram o disposto no art. 166-A da Constituição Federal.'),
(6,'711','Demais Transferências Obrigatórias não Decorrentes de Repartições de Receitas.','Controla os recursos originários de transferências obrigatórias da União que não decorram de repartição de receitas, como as transferências a título de auxílio ou apoio financeiro, e para os quais não tenha sido criada fonte ou destinação de receitas específica.'),
(6,'712','Transferências Fundo a Fundo de Recursos do Fundo Penitenciário - FUNPEN','Controla as transferências obrigatórias de recursos do Fundo Penitenciário Nacional - FUNPEN.'),
(6,'713','Transferências Fundo a Fundo de Recursos do Fundo de Segurança Pública - FSP','Controla as transferências obrigatórias de recursos do Fundo de Segurança Pública - FSP'),
(6,'714','Transferências Fundo a Fundo de Recursos do Fundo de Amparo ao Trabalhador - FAT','Controla as transferências obrigatórias de recursos do Fundo de Amparo ao Trabalhador - FAT'),
(6,'715','Transferências Destinadas ao Setor Cultural - LC nº 195/2022 - Art. 5º - Audiovisual','Controla a parcela dos recursos provenientes das transferências efetuadas pela União destinadas ao setor cultural, especificamente ao setor audiovisual, como ação emergencial adotada em decorrência dos efeitos econômicos e sociais da pandemia da covid-19, em cumprimento ao Art. 5º da Lei Complementar nº 195, de 8 de julho de 2022.'),
(6,'716','Transferências Destinadas ao Setor cultural - LC nº 195/2022 - Art. 8º - Demais Setores da Cultura','Controla a parcela dos recursos provenientes das transferências efetuadas pela União destinadas ao setor cultural, como ação emergencial adotada em decorrência dos efeitos econômicos e sociais da pandemia da covid-19, em cumprimento ao Art. 8º da Lei Complementar nº 195, de 8 de julho de 2022'),
(6,'717','Assistência Financeira Transporte Coletivo - Art. 5º, Inciso IV, EC nº 123/2022','Controla os recursos provenientes das transferências da União a título de assistência financeira a serem utilizados no custeio da garantia prevista no §2º do art. 230 da CF, de gratuidade dos transportes coletivos urbanos aos maiores de 65 anos, conforme prevê o inciso IV, art. 5º, da Emenda Constitucional nº 123/2022.'),
(6,'718','Auxílio Financeiro - Outorga Crédito Tributário ICMS - Art. 5º, Inciso V, EC nº 123/2022','Controla os recursos provenientes das transferências da União a título de auxílio financeiro para os Estados e o Distrito Federal que outorgarem créditos tributários do Imposto sobre Operações relativas à Circulação de Mercadorias e sobre Prestações de Serviços de Transporte Interestadual e Intermunicipal e de Comunicação (ICMS) aos produtores ou distribuidores de etanol hidratado em seu território, em montante equivalente ao valor recebido, conforme prevê o Inciso V, art. 5º, da Emenda Constitucional nº 123/2022.'),
(6,'719','Transferências da Política Nacional Aldir Blanc de Fomento à Cultura - Lei nº 14.399/2022','Controla os recursos provenientes de transferências efetuadas pela União em decorrência da Política Nacional Aldir Blanc de Fomento à Cultura previstas no art. 6º da Lei nº 14.399, de 8 de julho de 2022.'),
(6,'749','Outras vinculações de transferências','Controle dos recursos de outras transferências vinculadas, não enquadrados nas especificações anteriores.'),
(7,'750','Recursos da Contribuição de Intervenção no Domínio Econômico - CIDE','Controle dos recursos recebidos pelos Estados, Distrito Federal e Municípios, decorrentes da distribuição da arrecadação da União com a CIDE - Combustíveis, com base no disposto na Lei nº 10.336/2001.'),
(7,'751','Recursos da Contribuição para o Custeio do Serviço de Iluminação Pública - COSIP','Controle dos recursos da COSIP, nos termos do artigo 149-A da Constituição Federal da República.'),
(7,'752','Recursos Vinculados ao Trânsito','Controle dos recursos com a cobrança das multas de trânsito nos termos do art. 320 da Lei nº 9.503/1997 - Código de Trânsito Brasileiro.'),
(7,'753','Recursos Provenientes de Taxas, Contribuições e Preços Públicos','Controle dos recursos de taxas, contribuições e preços públicos vinculados conforme legislações específicas.'),
(7,'754','Recursos de Operações de Crédito','Controle dos recursos originários de operações de crédito, exceto as operações cuja aplicação esteja destinada a programas de educação e saúde.'),
(7,'755','Recursos de Alienação de Bens/Ativos - Administração Direta','Controle dos recursos decorrentes da alienação de bens da Administração Direta, nos termos do art. 44 da Lei Complementar nº 101/2000.'),
(7,'756','Recursos de Alienação de Bens/Ativos - Administração Indireta','Controle dos recursos decorrentes da alienação de bens da Administração Indireta, nos termos do art. 44 da Lei Complementar nº 101/2000.'),
(7,'757','Recursos de Depósitos Judiciais - Lides das quais o Ente faz parte','Controle dos recursos de depósitos judiciais apropriados pelo ente de lides das quais o ente faz parte, com base na Lei Complementar nº 151/2015, no art. 101 do ADCT da Constituição Federal.'),
(7,'758','Recursos de Depósitos Judiciais - Lides das quais o Ente não faz parte','Controle dos recursos de depósitos judiciais apropriados pelo ente de lides das quais o ente não faz parte, com base no art. 101 do ADCT da Constituição Federal.'),
(7,'759','Recursos Vinculados a Fundos','Controle dos recursos vinculados a fundos, com exceção dos fundos relacionados à saúde, à educação, à assistência social e aos regimes de previdência.'),
(7,'760','Recursos de Emolumentos, Taxas e Custas','Controle dos recursos de emolumentos, taxas e outros recursos arrecadados, judiciais ou extrajudiciais, observado o disposto em legislações específicas.'),
(7,'761','Recursos Vinculados ao Fundo de Combate e Erradicação da Pobreza','Controle dos recursos vinculados ao Fundo de Combate e Erradicação da Pobreza, na forma prevista nos art. 82 do ADCT e da Lei Complementar nº 111, de 6 de julho de 2001.'),
(7,'799','Outras Vinculações Legais','Controle de outros recursos vinculados por lei, não enquadrados nas especificações anteriores.'),
(8,'800','Recursos Vinculados ao RPPS - Fundo em Capitalização (Plano Previdenciário)','Controle dos recursos vinculados ao fundo em capitalização do RPPS. Esse plano existe tanto nos entes que segregaram quanto nos que não segregaram a massa dos segurados, observando-se o disposto na Portaria MF nº 464/2018. Na fase das despesas, será necessário associar esta fonte ao marcador que identifica a qual Poder ou Órgão se refere a despesa quando ela é executada no PO RPPS.'),
(8,'801','Recursos Vinculados ao RPPS - Fundo em Repartição (Plano Financeiro)','Controle dos recursos vinculados ao fundo em repartição do RPPS. Esse plano deve existir somente nos entes que segregaram a massa dos segurados, observando-se o disposto na Portaria MF nº 464/2018. Na fase da despesa, será necessário associar esta fonte ao marcador que identifica a qual Poder ou Órgão se refere a despesa quando ela é executada no PO RPPS.'),
(8,'802','Recursos Vinculados ao RPPS - Taxa de Administração','Controle dos recursos destinados ao custeio das despesas necessárias à organização e ao funcionamento da unidade gestora do RPPS, observando-se o disposto na Portaria MPS nº 402/2008 e na Portaria MF nº 464/2018, ambas alteradas pela Portaria ME nº 19.451/2020.'),
(8,'803','Recursos Vinculados ao Sistema de Proteção Social dos Militares (SPSM)','Controle dos recursos vinculados ao Sistema de Proteção Social dos Militares (SPSM), com base na Lei nº 6.880/1980 (Estatuto dos Militares), alterada pela Lei nº 13.954/2019.'),
(9,'860','Recursos Extraorçamentários Vinculados a Precatórios','Controle dos recursos financeiros junto aos tribunais de justiça vinculados ao pagamento de precatórios.'),
(9,'861','Recursos Extraorçamentários Vinculados a Depósitos Judiciais','Controle dos recursos financeiros junto aos tribunais de justiça vinculados aos depósitos judiciais.'),
(9,'862','Recursos de Depósitos de Terceiros','Controle dos recursos financeiros decorrentes de depósitos de terceiros.'),
(9,'869','Outros Recursos Extraorçamentários','Controle dos demais recursos financeiros extraorçamentários, como, por exemplo, retenções e consignações.'),
(10,'880','Recursos Próprios dos Consórcios','Controle dos recursos próprios dos Consórcios Públicos (utilizada pelos consórcios públicos)'),
(10,'898','Recursos a Classificar','Classificação temporária enquanto não se identifica a correta vinculação.'),
(10,'899','Outros Recursos Vinculados','Controle dos recursos cuja aplicação seja vinculada e não tenha sido enquadrado em outras especificações.');
SQL
        );
    }

    private function upNomeMenu()
    {
        DB::connection()->getPdo()->exec(<<<SQL
update db_itensmenu
   set descricao = 'De Para Recursos ',
       help = 'De Para Recursos'
 where id_item = 228618;
SQL
        );
    }
    private function downNomeMenu()
    {
        DB::connection()->getPdo()->exec(<<<SQL
update db_itensmenu
   set descricao = 'De Para Recursos 2021 - 2022',
       help = 'De Para Recursos 2021 - 2022'
 where id_item = 228618;
SQL
        );
    }
}
