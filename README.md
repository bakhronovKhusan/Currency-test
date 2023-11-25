## How running
composer update
php artisan migrate
npm install
npm run dev

##Откройте Crontab на вашем сервере:
crontab -e
добавьте это
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
