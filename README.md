# Запуск проекта
## Необходимые иструменты
- docker
- docker-compose
- git
## ОС
В связи с плохой скоростью работы Docker Desktop проект разрабатывался на Linux, запуск на Windows будет сильно замедлять работу проекта.

## Built
### Копируем проект
- git clone https://github.com/TenshiNoAku/IctisInfo.git

### Инициализацируем .env
- cd IctisInfo/docker
- echo  "COMPOSE_PROJECT_NAME=ictisinfoapo

#users ids could retrieved by the command `id $USER`
PUID=1000
PGID=1000

#nginx
NGINX_HOST_HTTP_PORT=888

POSTGRES_PORT=5000
POSTGRES_DB=ictisDB
POSTGRES_USER=ictis
POSTGRES_PASSWORD=ictis" > .env

### Запускаем проект
- cd ..
- make dc_up
- make app_bash
### Собираем проект в bash
- composer_install
### Пересоздаем и заполняем БД
- php bin/console doctrine:schema:drop --full-database --force
- php bin/console doctrine:migrations:migrate
- php bin/console database:load
- 
### Теперь проект развернут на localhost:888

### Endpoints
  #### Audience(Аудитории)
  - GET /api/v1/audiences - Возвращает все аудитории
  - POST /api/v1/audiences - Добавляет аудиторию, поле (name : string)
  - PUT, PATCH /api/v1/audiences/{id} - Изменяет Аудиторию с определенным id
  - GET /api/v1/audiences/{id} - Возвращает Аудиторию с определенным id
  - DELETE /api/v1/audiences/{id} - Удаляет Аудиторию с определенным id
  - 
#### ClassTime(Время проведения занятий)
  -  GET /api/v1/class_types - Возвращает все Времена
 -  POST /api/v1/class_types  - Добавляет Время, поле (ClassTime : string)
  - PUT, PATCH /api/v1/class_types /{id} - Изменяет Время с определенным id
  - GET /api/v1/class_types/{id} - Возвращает Время с определенным id
  - DELETE /api/v1/class_types/{id} - Удаляет Время с определенным id
  - 
#### ClassType(Название пары)
  - GET /api/v1/class_times - Возвращает все Названия
 -  POST /api/v1/class_times - Добавляет Название, поле (name : string)
  - PUT, PATCH /api/v1/class_times/{id} - Изменяет Название с определенным id
  - GET /api/v1/class_times/{id} - Возвращает Название с определенным id
  - DELETE /api/v1/class_times/{id} - Удаляет Название с определенным id

#### Schedule(Занятие)
  - GET /api/v1/schedules - Возвращает все Занятия
  - POST /api/v1/schedules - Добавляет Название, поле (name : string)
  - PUT, PATCH /api/v1/schedules/{id} - Изменяет Занятие с определенным id
  - GET /api/v1/schedules/{id} - Возвращает Занятие с определенным id
  - DELETE /api/v1/scheduless/{id} - Удаляет Занятие с определенным id
  - GET /api/v1/schedules/{date}/{class_time_id} - Возвращает занятия проводимые в опреденную дату и время дата формата DD-MM-YYYY
  - Пример запроса: localhost:888/api/v1/schedules/20-04-2024/{2} - запрос вернет все занятия проводимые 20 апреля 2024 года с 09:45 до 11:25

### Схема БД
![image](https://github.com/TenshiNoAku/IctisInfo/assets/75081246/8d006b94-be29-4f45-8e3c-fa2fd3928a59)

