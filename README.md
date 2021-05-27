This project is about small bank simulation where you can register your account, 
login, verify your login by email, I used mailtrap.io. When you are in, you have
an account with your chosen currency where you can add money to your account 
and send money to other registered users with google 2-factor authentication code.
You can go to your deposit account page and if you don't have one you can create one.
With this deposit account you can deposit money to it and buy stocks after. Everything is
linked with bank currency rate api so this app can always convert your money by current 
rates and linked with stock api to show the latest stocks with prices.





To run this project:<br>
Run composer install.<br>
Run cp .env.example .env or copy .env.example .env.<br>
Run php artisan key:generate.<br>
Create database with name according to .env file.<br>
Run php artisan migrate.<br>
Run php artisan serve.<br>
Run php artisan queue:work.<br>
