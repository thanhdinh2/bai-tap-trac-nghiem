create table cauhoi(
maso    int(11) primary key auto_increment,
lop smallint(2) not null default '10',
chuong smallint(2) not null default 1,
bai smallint(2) not null default 0,
dokho smallint(1) not null default 1,
kieu smallint(1) not null default 1,
noidung   varchar(250),
ngaylam date not null
);

create table thn_bai(
maso    int(11) primary key auto_increment,
kichhoat smallint(2) not null default 0,
socau smallint(2) not null default 1,
sogiay smallint(2) not null default 0,
khoi smallint(1) not null default 10,
tenbai   varchar(250),
ngaylam date not null
);