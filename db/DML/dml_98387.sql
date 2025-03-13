select fc_executa_ddl('
CREATE TABLE ambulatorial.cgs_und_ext
(
  z01_i_id serial NOT NULL,
  z01_i_cgsund integer NOT NULL,

  z01_b_faleceu boolean,
  z01_d_falecimento date,
  z01_b_descnomemae boolean,
  z01_i_naturalidade integer DEFAULT 0,
  z01_i_paisorigem integer,

  z01_v_municnasc character varying(40),
  z01_v_ufnasc character varying(2),
  z01_codigoibgenasc character varying(50),
  z01_i_codocupacao integer DEFAULT 0,
  z01_i_escolaridade integer,
  z01_i_cgm integer,
  z01_i_cge integer,
  z01_i_cidadao integer,
  z01_b_inativo boolean NOT NULL DEFAULT false,

  CONSTRAINT z01_i_id_pkey PRIMARY KEY (z01_i_id)
)
WITH (
  OIDS=TRUE
);
');
-- ALTER TABLE ambulatorial.cgs_und_ext ADD CONSTRAINT cgs_und_ext_i_pais_fk FOREIGN KEY (z01_i_paisorigem) REFERENCES plugins.paises (cod) ON DELETE NO ACTION;
select fc_executa_ddl('ALTER TABLE ambulatorial.cgs_und_ext ADD CONSTRAINT cgs_und_ext_i_cgsund_fk FOREIGN KEY (z01_i_cgsund) REFERENCES ambulatorial.cgs_und (z01_i_cgsund) MATCH SIMPLE ON UPDATE NO ACTION ON DELETE CASCADE;');
