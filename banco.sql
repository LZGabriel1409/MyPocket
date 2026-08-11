create database mypocket;

use mypocket;

create table transacoes (
    id int auto_increment primary key,
    descricao varchar(255) not null,
    valor decimal(10,2) not null,
    tipo enum('entrada', 'saida') not null,
    data date not null
);

select * from transacoes;
