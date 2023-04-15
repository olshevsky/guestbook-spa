# Installation

0. Clone repo to your directory.

1. In project dir run:
    - ./vendor/bin/sail up
   
2. Run:
    - docker ps
    - Copy sail-8.2/app CONTAINER ID

3. Login into app container: 
    - docker exec -it {CONTAINER ID} /bin/sh

4. Install dependencies, build assets and run migrations:
    - npm install
    - npm run build
    - composer install
    - php artisan migrate
    
6. App url:
   http://localhost:80
