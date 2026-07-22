
update cgs_und_ext set z01_b_faleceu = false where z01_b_faleceu is true and z01_d_falecimento is null;
update cgs_und_ext set z01_b_faleceu = true  where z01_b_faleceu is false and z01_d_falecimento is not null;
