# Wichtige Befehle während der Implementierung
## für die db:
touch database/database.sqlite
New-Item -Path database/database.sqlite -ItemType File
git checkout -b feature/backend



## für die Installation von Laravel Breeze mit React:
composer require laravel/breeze --dev
php artisan breeze:install react
npm install --legacy-peer-deps (sonst npm install --force)
npm run dev

## allgemein
php artisan
php artisan make:migration
php artisan migrate
php artisan migrate:fresh --seed
php artisan migrate:fresh
php artisan migrate:refresh
php artisan migrate:refresh --seed
php artisan make:model Ticket -m (oder händisch)
php artisan make:controller TicketController --resource
php artisan make:request StoreTicketRequest
php artisan make:factory TicketFactory
php artisan make:seeder TicketSeeder (nicht verwendet, statt vorgefertigtem DatabaseSeeder)
php artisan db:seed
laravel new project
composer run dev
herd unlink
herd link support
php artisan about
php artisan optimize:clear
php artisan tinker
