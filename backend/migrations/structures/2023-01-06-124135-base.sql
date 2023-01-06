create table platforms
(
    id   int auto_increment
        primary key,
    name varchar(255) not null
);

create index platforms_name_index
    on platforms (name);

create table studios
(
    id   int auto_increment
        primary key,
    name varchar(255) not null
);

create table products
(
    id             int auto_increment
        primary key,
    name           varchar(255)   not null,
    current_price  decimal(14, 3) not null,
    original_price decimal(14, 3) not null,
    studio_id      int            not null,
    constraint products_studios_id_fk
        foreign key (studio_id) references studios (id)
);

create index products_name_index
    on products (name);

create table products_editions
(
    id         int auto_increment
        primary key,
    name       varchar(255) not null,
    product_id int          not null,
    constraint products_editions_products_id_fk
        foreign key (product_id) references products (id)
);

create index products_editions_name_index
    on products_editions (name);

create table products_platforms
(
    id          int auto_increment
        primary key,
    product_id  int not null,
    platform_id int not null,
    constraint products_platforms_pk2
        unique (product_id, platform_id),
    constraint products_platforms_platforms_id_fk
        foreign key (platform_id) references platforms (id),
    constraint products_platforms_products_id_fk
        foreign key (product_id) references products (id)
);

create table products_translations
(
    id                int auto_increment
        primary key,
    short_description varchar(255) not null,
    long_description  text         not null,
    product_id        int          not null,
    locale            varchar(10)  not null,
    constraint products_translations_pk2
        unique (product_id, locale),
    constraint products_translations_products_id_fk
        foreign key (product_id) references products (id)
);

create index studio_name_index
    on studios (name);

