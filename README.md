## Magic DTO
[![Total Downloads](https://poser.pugx.org/agelxnash/laravel-magic-dto/d/total.png)](https://packagist.org/packages/agelxnash/laravel-magic-dto) [![codecov](https://codecov.io/gh/AgelxNash/magicDto/graph/badge.svg?token=U92VBOENZJ)](https://codecov.io/gh/AgelxNash/magicDto)


```php
class ExampleDto extends MagicDto
{
    public function __construct(
        public int $propInt,
        public float $propFloat,
        public CarbonImmutable $date,
        public ?ExampleDto $nested = null,
    ) {
    }
}
ExampleDto::from([
    'propInt' => 666,
    'propFloat' => '100.5',
    'date' => '2023-12-11',
    'nested' => [
        'date' => '2024-02-23',
        'propInt' => 777,
        'propFloat' => '200.1',
    ],
])
// or
ExampleDto::from([
    'prop_int' => 666,
    'prop_float' => '100.5',
    'date' => '2023-12-11',
    'nested' => [
        'date' => '2024-02-23',
        'prop_int' => 777,
        'prop_float' => '200.1',
    ],
])
```

#### Inject eloquent models
`composer require "illuminate/database"` required when you need to use **AgelxNash\MagicDto\Attributes\InjectModel**

```php
Class User extends Illuminate\Database\Eloquent\Model {}

class ExampleDto extends MagicDto
{
    public function __construct(
        #[InjectModel(User::class, 'id')]
        public User $user1,
        #[InjectModel(User::class, 'email')]
        public User $user2,
    ) {
    }
}

ExampleDto::from(['user1' => 777]);
ExampleDto::from(['user2' => 'agel-nash@example.com']);
```


### Author
---------
<table>
  <tr>
    <td valign="center" align="center"><img src="http://www.gravatar.com/avatar/bf12d44182c98288015f65c9861903aa?s=250"></td>
	<td valign="top">
		<h4>Borisov Evgeniy
		<br />
		Agel_Nash</h4>
		<br />
	    Laravel, MODX, Security Audit
		<br />
		<br />
		<br />
		<br />
        <small>
            <a href="https://agel-nash.ru">https://agel-nash.ru</a>
		    <br />
		    <strong>Telegram</strong>: <a href="https://t.me/Agel_Nash">@agel_nash</a>
		    <br />
		    <strong>Email</strong>: laravel@agel-nash.ru
		</small>
	</td>
	<td valign="top">
		<h4>Donation<br /><br /></h4>
		<br />
		<strong>ЮMoney</strong>: <a href="https://yoomoney.ru/to/41001299480137">41001299480137</a><br />
	</td>
  </tr>
</table>
