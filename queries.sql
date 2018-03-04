
-- добавление пользователей в таблицу user
INSERT INTO users SET email = 'ignat.v@gmail.com', name='Игнат', password ='$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka', signup_date='2018-02-20' ;
INSERT INTO users SET email = 'kitty_93@li.ru', name ='Леночка', password ='$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa', signup_date='2018-02-22';
INSERT INTO users SET email = 'warrior07@mail.ru', name = 'Руслан', password = '2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW', signup_date='2018-02-23';

-- добавление список проектов в таблицу projects

INSERT INTO projects SET name = "Все", user_id = 1;
INSERT INTO projects SET name = "Входящие", user_id = 1;
INSERT INTO projects SET name = "Учеба", user_id = 1;
INSERT INTO projects SET name = "Домашние дела", user_id = 1;
INSERT INTO projects SET name = "Авто", user_id = 1;

-- добавление задач 

INSERT INTO tasks SET 
  name = 'Собеседование в IT компании',
  end_date = '2018-06-01',
  user_id = 1,
  project_id = 4;
 
INSERT INTO tasks SET
  name = 'Выполнить тестовое задание',
  end_date = '2018-05-25',
  user_id = 1,
  project_id = 4;
INSERT INTO tasks SET
  name = 'Сделать задание первого раздела',
  end_date = '2018-04-21',
  user_id = 1,
  project_id = 3;
INSERT INTO tasks SET
  name = 'Встреча с другом',
  end_date = '2018-04-22',
  user_id = 1,
  project_id = 2;
INSERT INTO tasks SET
  name = 'Купить корм для кота',
  end_date = '2018-02-08',
  user_id = 1,
  project_id = 5;
INSERT INTO tasks SET
  name = 'Заказать пиццу',
  end_date = '2018-02-09',
  user_id = 1,
  project_id = 5;



-- получить список из всех проектов для одного пользователя
select * from projects as p join tasks as t on p.id = t.project_id where t.user_id = 1;

-- получить список из всех задач для одного проекта

select * from tasks where project_id=3;

-- пометить задачу как выполненную

update tasks set realized = now() where id = 1;

-- получить все задачи для завтрашнего дня

select * from tasks where date(end_date) = curdate() + interval 1 day;

-- обновить название задачи по её идентификатору

update tasks set name = 'Собеседование в банке' where id = 1;

