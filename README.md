## I Use service repository design pattern

``Run docker-compose up -d --build ``

``1 - Composer install``

``2 - Symfony serve``

``3 - Import postman collection and postman environment``

``4 - Run php bin/console doctrine:migrations:migrate``

``5 - Run php bin/console doctrine:fixtures:load to seed dume data ``

``6 - in postman there are 2 folders (User, Post)``

``7 - In the User folder login request``

``8 - in Post Folder post CRUD operations``


## dispatching 
```run bin/console app:schedule-posts ``` to dispatch th post Schedule

this command will call `SchedulePostListener` that will get  Schedule posts and publish them

## how schedule a post

in request `posts/create` if u set `schedule_date` null then this post will publish Immediately

On the other side If you put time into it will publish in ``SchedulePostListener``