IssueTracker
============

A Symfony3 project

created on September 1, 2018, 8:50 pm.

# Quick Setup Guide for Developers #

1. Copy `app/config/parameters.yml.dist` to `app/config/parameters.yml` and add in your own database/mailer configuration.

2. To correctly set the permissions on the `cache` and `logs` directories, run the following commands from your server (OSX based systems)

        $ APACHEUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data' | grep -v root | head -1 | cut -d\  -f1`
        $ sudo chmod +a "$APACHEUSER allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
        $ sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs

   The `www-data` user should be replaced with whatever user your apache / nginx service is running as

3. Install Composer

   As Symfony3 uses [Composer][1] to manage its dependencies, IssueTracker manages external libraries the same way.

   If you don't have Composer yet, just run the following command from your project directory

        curl -s http://getcomposer.org/installer | php
        sudo mv composer.phar /usr/local/bin/composer
        composer self-update

   You then need to install vendor libraries using...

        composer install
        #or
        sudo composer update

   It should install all required vendor bundles.

4. Run the following commands from the project directory in your terminal...

        php bin/console doctrine:database:create
        php bin/console doctrine:schema:create

   This should create your database schema from the entities in the bundles located in the `src` folder. If there are any problems ensure that your database privileges and credentials are okay.

5. Import data fixtures by running the following command in your project directory...

        php bin/console doctrine:fixtures:load

6. Install NodeJS in the system

        sudo apt-get update
        sudo apt-get install nodejs

7. También puede instalar npm, que es el gestor de paquetes Node.js

        sudo apt-get install npm
        sudo npm install bower

7. Update your parameters.yml paths for `node_bin` to point to your node binary

8. Install node modules...

        cd app/Resources && npm install && cd ../../

9. Install JS dependencies with bower...

        bin/bower install

10. Assets install command 

        php bin/console assetic:dump
        php bin/console assets:install web --symlink
          
11. Create database
        
        $ php bin/console doctrine:database:create
        
12. Create database schema
        
        $ php bin/console doctrine:schema:create
        
13. Create and activate a user for login

        $ php bin/console fos:user:create
        
      (Follow the console for creating user)

14. Activation

        $ php bin/console fos:user:activate <user_name>

15. If you want to login as admin

        $ php bin/console fos:user:promote <user_name> ROLE_ADMIN
        
16. If you want run the standalone server and test...

        # $ php app/console server:start (symfony 2.x)
        $ php bin/console server:run     (symfony 3.x)  
        
        #test in:
        http://127.0.0.1:8000
 
17. Execute the security:check script from the command line
        
        php bin/console security:check
        
18. Troubleshooting, If something goes wrong, errors & exceptions are logged at the application level:
    
    
        tail -f var/logs/prod.log
        tail -f var/logs/dev.log        
        
[1]:  http://getcomposer.org/        
