#### Dependencies
- PHP 7.4
- PHP Composer
- [Slim PHP Framework](https://www.slimframework.com/)

#### How to setup
1. If you don't have PHP 7.4 installed. You can install it using `brew install php@7.4`.
2. If you have a different version of PHP you can switch using `brew-php-switcher`. Install this by running `brew install brew-php-switcher`, then run `brew-php-switcher 7.4`
3. To install composer run `brew install composer`
4. switch to server directory `cd server/php`
5. run `composer install`
6. add the appropriate publisher test keys to [line 65 of step_1_save_card.html](https://github.com/cabbage-cart/3DS-test/blob/c5405f23801e4881ea418151e3b7670583d9444f/client/step_1_save_card.html#L65)

```js
// init collect card details
const stripe = Stripe('');
```
7. add appropriate publisher key to [line 25 of step_3_complete.html](https://github.com/cabbage-cart/3DS-test/blob/c5405f23801e4881ea418151e3b7670583d9444f/client/step_3_complete.html#L23)

```js
const stripe = Stripe(''); // set publisher key
```
8. don't forget to create `.env` in the directory `server/php` file and add `STRIPE_SECRET_KEY=` variable

#### How to run

1. switch to server directory `cd server/php`
2. run `composer start`
3. visit http://localhost:4242 in browser to add card
4. copy customer id and payment method id and visit http://localhost:4242/admin to test charging later


#### Test cards
1. Always requires 3DS: 4000 0027 6000 3184
2. Requires 3DS once: 4000 0025 0000 3155

