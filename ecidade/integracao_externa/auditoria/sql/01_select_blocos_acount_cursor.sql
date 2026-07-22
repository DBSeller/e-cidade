/* retornar os campos chave e seus valores em array dos accounts selecionados*/
with acountkey as (
	select	id_acount,
			actipo,
			array_agg(trim(nomecam) order by b.codcam) as pkey_nome_campo,
			array_agg(campotext     order by b.codcam) as pkey_valor
	from	db_acountkey{$sufixo} a
			join db_syscampo b on b.codcam = a.id_codcam
	where	a.id_acount between {$acount_ini} and {$acount_fim}
	group	by id_acount, actipo
),

/* retornar db_logsacessa associado aos acounts selecionados */
logsacessa as (
	select	a.id_acount,
			b.codsequen,
			b.id_modulo,
			b.id_item,
			(b.data || ' ' || b.hora)::timestamp as datahora,
			c.codigo as instit
	from	db_acountacesso{$sufixo} a
			join db_logsacessa b on b.codsequen = a.codsequen
								and b.data between '{$data_ini}'::date and '{$data_fim}'::date
								and b.instit = any('{$instit}'::integer[])
			left join db_config c on c.codigo = b.instit
	where	a.id_acount between {$acount_ini} and {$acount_fim}
),

/* agregar as mudanças dos acounts selecionados consultando dicionário de dados */
acount as (
	select	x.id_acount,
			b.codarq,
			b.codmod,
			coalesce(la.id_modulo, dp.id_modulo) as id_modulo,
			coalesce(la.id_item, dp.id_item) as id_item,
			c.nomemod,
			a.nomearq,
			x.actipo,
			coalesce(min(la.datahora), to_timestamp(min(y.datahr))) as datahr,
			to_timestamp(min(y.datahr)) as datahr_sessao,
			extract(year  from coalesce(min(la.datahora), to_timestamp(min(y.datahr))))::integer as anousu,
			extract(month from coalesce(min(la.datahora), to_timestamp(min(y.datahr))))::integer as mesusu,
			z.login,
			y.id_usuario,
			pkey_nome_campo,
			pkey_valor,
			array_agg((trim(w.nomecam)) order by y.codcam) as mudancas_nome_campo,
			array_agg( nullif((case x.actipo when 'E' then y.contatu else y.contant end), '') order by y.codcam) as mudancas_valor_antigo,
			array_agg( nullif((case x.actipo when 'E' then y.contant else y.contatu end), '') order by y.codcam) as mudancas_valor_novo,
			la.codsequen, 
			coalesce(la.instit, (coalesce(	(select	min(i.id_instit)
											 from	db_userinst i
											 where	i.id_usuario = y.id_usuario),
											(select	codigo
											 from	db_config
											 where	prefeitura is true
											 limit	1) ))) as instit
	from	db_acount{$sufixo} y
			join acountkey x     on y.id_acount   =  x.id_acount
								and trim(contant) <> trim(contatu)
			join db_syscampo   w on w.codcam     = y.codcam
			join db_sysarquivo a on a.codarq     = y.codarq
								and trim(a.nomearq) !~ '^db_(log|acount|auditoria)'
			join db_sysarqmod  b on b.codarq     = y.codarq
			join db_sysmodulo  c on c.codmod     = b.codmod
			join db_usuarios   z on z.id_usuario = y.id_usuario

			left join logsacessa la on la.id_acount = y.id_acount

			left join db_auditoria_migracao_depara_codarq_codmod_id_modulo dp  	 on dp.codarq = b.codarq
																				and dp.codmod = b.codmod
	group	by 1, 2, 3, 4, 5, 6, 7, 8, 13, 14, 15, 16, 20, 21
)
/* retornar dados transformados de db_acount para db_auditoria */
select	id_acount,
		codarq,
		codmod,
		id_modulo,
		id_item,
		trim(nomemod) as esquema,
		trim(nomearq) as tabela,
		case actipo
			when 'A' then 'U'
			when 'E' then 'D'
			else 'I'
		end                    as operacao,
		'db_auditoria_'||
			trim(to_char(anousu, '0000')) ||
			trim(to_char(mesusu, '00')) as particao,
		fc_xid_current()       as transacao,
		datahr_sessao as datahora_sessao,
		datahr as datahora_servidor,
		anousu::text || mesusu::text  as anomes,
		login,
		id_usuario,
		pkey_nome_campo,
		pkey_valor,
		mudancas_nome_campo,
		mudancas_valor_antigo,
		mudancas_valor_novo,
		codsequen,
		instit,
		to_timestamp(format('%s-%s-%s %s:%s:%s', anousu, mesusu, 1, 0, 0, 0), 'YYYY-MM-DD HH24:MI:SS') AS datahora_ini,
		to_timestamp(format('%s-%s-%s %s:%s:%s', anousu, mesusu, 1, 0, 0, 0), 'YYYY-MM-DD HH24:MI:SS') + interval '1 month' AS datahora_fim
from	acount;
