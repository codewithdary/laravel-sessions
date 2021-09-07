## Sessions in Laravel

The following documentation is based on my [Laravel Sessions for Beginners](https://www.youtube.com/watch?v=UjA-16diixc) tutorial we’re going to cover the basics of sessions in Laravel. <br> <br>
•	Author: [Code With Dary](https://github.com/codewithdary) <br>
•	Twitter: [@codewithdary](https://twitter.com/codewithdary) <br>
•	Instagram: [@codewithdary](https://www.instagram.com/codewithdary/) <br>

## Usage <br>
Setup your coding environment <br>
```
git clone git@github.com:codewithdary/laravel8-tailwindcss2.git
cd laravel8-tailwindcss2
composer install
cp .env.example .env 
php artisan key:generate
php artisan cache:clear && php artisan config:clear 
php artisan serve 
```

## Database Setup <br>
We will be performing database tests which (obviously) needs to interact with the database. Make sure that your database credentials are up and running.
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_sessions
DB_USERNAME=root
DB_PASSWORD=
```

Next up, we need to create the database which will be grabbed from the ```DB_DATABASE``` environment variable.
```
mysql;
create database laravelblog;
exit;
```

Finally, make sure that you migrate your migrations.
```
php artisan migrate
```

## Session Configuration

When a user interacts with your web application, data needs to be stored temporary. In most cases, it will be done in a session.

A session allows you to store states between page requests. An example might be a product page. When you store a value from the product page inside a session, you can access that session in the shopping cart.

All your session settings and drivers are stored in the ```/config/session.php``` file. The first thing you’ll notice right here is that you have the option to change up your ```SESSION_DRIVER```. This might seem odd, but you can actually choose between the following drivers: <br>
•	File (Default) <br> 
•	Cookie <br>
•	Database <br>
•	Memcached <br>
•	Redit <br>
•	Amazon Web Service <br>
•	DynamoDB <br>
•	Basic array <br>

Another interesting setting is the ```encrypt```. You can choose whether you want to encrypt your session data or not. BY default, it’s always turned off. If you’re working with sentive data inside your sessions, you can set the value equal to ```true```.

## Creating our first session

I’ve created a new controller called ```PagesController``` and I’ve setup two methods and routes called ```index``` and ```about```. 

```ruby
public function index()
{
    return view('index');
}

public function about()
{
    return view('about');
}
```

You can define a session through the Session Facade or the global session helper. I prefer to use the Session Facade so that’s what we’re going to use in this tutorial.

The two most common methods are ```get()``` and ```put()```. The ```put()``` method allows you to save data, and it accepts two parameters. The first one will be the key, and the second parameter will be the value. The second parameter can also be an array with multiple values.
```ruby
Session::put('name', 'John');
```

Whenever the ```index()``` method will be called, a new session with a key of name and a value of John will be created. 

In order to retrieve data, we got to use the ```get()``` method. If we navigate to the ```index.blade.php``` file, we could use the Session façade in the same exact way to get a value. Be aware that you always need to provide a key of the session. You can attach a fallback value as well, which can be a string or a closure.
```ruby
{{ Session::get('name') }}

{{ Session::get('name', 'Default name!') }}
```

A session should be accessible throughout the entire application. If we navigate to the ```/about``` page and print out the ```get()``` method with a key of name, you’ll see that our name will be printed out again.

## Available methods on session instance

There are a couple important methods available that you can perform on your session Facade. We’ve already performed the two most basic ones, which is the get method to get data, and put to add values into our session.

### Session::push($key, $value)
The next one will be the push method, which allows you to add a value to an array.
```ruby
Session::put('name', ['John']);
Session:push('name', 'Michael');
```

### Session::has($key)
The next method is a method I’ve used quite a lot in my tutorials, which is the ```has()``` method. The has method checks whether there’s a value set at the provided key.
```ruby
if(Session::has('name')) {
    echo 'Name does exist';
}
```

### Session::exists($key)
```exists()``` will check whether there’s a value set at the provided key.
```ruby
if(Session::exists('name')) {
    echo 'Name does exist';
}
```

### Session::all()
The ```all()``` method returns an array of everything that’s in the session, including those values set by the framework.
```ruby
Session::all();
```

### Session::forget($key) & Session::flush()
```forget()``` removes a previously set session value and the ```flush()``` method removes every session value, even those set by the framework.
```ruby
Session::forget('name');
Session::flush();
```

### Session::regenerate()
The last available method is the ```regenerate()``` method, which will regenerate the ```_token``` value from your session.
```ruby
Session::regenerate();
```

## How to store sessions in database
Whenever you want to combine session data with meta data, I recommend you to store sessions in the database because you can track whenever and how often a user has logged in. But you should also keep in mind that the numbers of record can grow tremendously inside your database, which can make your site slow as well.

Let’s pull in a frontend scaffolding to save a user_id inside a session.
```
composer require laravel/ui
php artisan ui vue –auth
npm install && npm run dev
```

We don’t need to generate the sessions migration ourselves because Artisan allows us to generate one.
```
php artisan session:table
```

This will create a new migration inside the ```/database/migrations``` folder with the name of ```{datetime}}_create_sessions_table.php```.
```ruby 
Schema::create('sessions', function (Blueprint $table) {
    $table->string('id')->primary();
    $table->foreignId('user_id')->nullable()->index();
    $table->string('ip_address', 45)->nullable();
    $table->text('user_agent')->nullable();
    $table->text('payload');
    $table->integer('last_activity')->index();
});
```

```user_id``` is the user_id that is logged in. It’s optional, so you don’t need to be logged in to set the array.
```ip_address``` will be the ip address that you use when setting your session. On your localhost, this will most likely be ```127.0.0.1```.
```user_agent``` will be the software that present content for end users.
```payload``` is the portion of transmitted data.
```last_activity``` will be a datetime of when a user had his last activity.

You can only add your session into the database if you change up your ```SESSION_DRIVER```. This can be done inside the ```/config/session.php``` file, but the best way is to define an env variable.
```
SESSION_DRIVER=database
```

## Example

Change up the RegisterController so set a session when a user creates a new account

```ruby
protected function create(array $data)
{
    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
    ]);

    Session::push('user', [
        'id' => $data['id'],
        'name' => $data['name'],
        'email' => $data['email']
    ]);

    return $user;
}
```

If you then print out all values inside the PagesController:
```ruby
dd(Session::all());
```

Refresh the /endpoint, where you find an ```name``` array with information of a user, and if you perform
```
SELECT * FROM sessions;
```

Inside MySQL, you’ll see a new session!

# Credits due where credits due…
Thanks to [Laravel](https://laravel.com/) for giving me the opportunity to make this tutorial on [Sessions](https://laravel.com/docs/8.x/session).
