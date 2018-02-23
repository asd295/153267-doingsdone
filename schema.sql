CREATE DATABASE doingsdone;
DEFAULT CHARACTER SET utf8;
DEFAULT COLLATE utf8_general_ci;
USE doingsdone;

CREATE TABLE users (
    id int NOT NULL AUTO_INCREMENT,
    signup_date DATETIME(6) NOT NULL,
    email varchar(255) NOT NULL UNIQUE,
    name varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    contacts varchar(255),
  PRIMARY KEY (id)
);


CREATE TABLE projects (
    id int auto_increment primary key,
    name varchar(255) not null
);

-- fact_date - дата записи задачи по факту в бд
-- realized - дата выполнения задачи 
-- end_date- сроки выполнения задачи до

CREATE TABLE tasks (
    id int auto_increment primary key,
    project_id int null,
    user_id int not null,
    FOREIGN KEY (project_id) REFERENCES projects (id) on delete cascade,
    FOREIGN KEY (user_id) REFERENCES users (id) on delete cascade,
    fact_date timestamp default now(),
    realized timestamp null,
    name varchar(255) not null,
    filename varchar(255) null,
    end_date timestamp null,
    image_url varchar(255) null
);