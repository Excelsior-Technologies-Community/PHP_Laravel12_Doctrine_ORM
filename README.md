# PHP_Laravel12_Doctrine_ORM

## Project Introduction

PHP_Laravel12_Doctrine_ORM is a Laravel 12 project demonstrating how to integrate and use Doctrine ORM instead of Laravel’s default Eloquent ORM. This project provides a modern approach to database management in Laravel using Doctrine entities, repositories, and migrations, along with basic operations for managing posts.

It showcases a full workflow including entity creation, repository setup, migrations, and basic operations like creating, listing, and deleting posts, all structured in a maintainable and scalable way.

------------------------------------------------------------------------

## Project Overview

This project demonstrates:

- Installing Laravel 12 and Laravel Doctrine ORM

- Configuring Doctrine with Laravel

- Creating Entities and Repositories

- Generating and running Doctrine migrations

- Implementing basic post management (create, view, delete)

- Using TailwindCSS for a clean, modern UI

- Structuring the project for maintainability and scalability

The project provides a hands-on example of replacing Eloquent with Doctrine while keeping the familiar Laravel workflow for controllers, routes, and views.

------------------------------------------------------------------------

## Step 1: Create Laravel 12 Project

Open terminal and run:

``` bash
composer create-project laravel/laravel PHP_Laravel12_Doctrine_ORM "12.*"
cd PHP_Laravel12_Doctrine_ORM
```

------------------------------------------------------------------------

## Step 2: Install Laravel Doctrine ORM

``` bash
composer require laravel-doctrine/orm
```

Publish configuration:

``` bash
php artisan vendor:publish --tag="config"
```

This creates:

```bash
config/doctrine.php
```

------------------------------------------------------------------------

## Step 3: Configure Database (.env)

``` env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=doctrine_db
DB_USERNAME=root
DB_PASSWORD=
```

------------------------------------------------------------------------

## Step 4: Create Entity (Post)

Create folder:

app/Entities/Post.php

``` php
<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use App\Repositories\PostRepository; //  ADD THIS

#[ORM\Entity(repositoryClass: PostRepository::class)] // FIXED
#[ORM\Table(name: "posts")]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private string $title;

    #[ORM\Column(type: "text")]
    private string $content;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
```

------------------------------------------------------------------------

## Step 5: Create Repository

app/Repositories/PostRepository.php

``` php
<?php

namespace App\Repositories;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    // Custom query methods can be added here
}
```

------------------------------------------------------------------------


## Step 6: Install Doctrine Migrations Package

Run this:

```bash
composer require laravel-doctrine/migrations
```

Publish Config:

```bash
php artisan vendor:publish --provider="LaravelDoctrine\Migrations\MigrationsServiceProvider"
```

This will create:

```bash
config/migrations.php
```

After installation, clear config:

```bash
php artisan config:clear
php artisan cache:clear
```

------------------------------------------------------------------------

## Step 7: update config/migrations.php

File: config/migrations.php

```php
<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Entity Manager Migrations Configuration
    |--------------------------------------------------------------------------
    |
    | Each entity manager can have a custom migration configuration. Provide
    | the name of the entity manager as the key, then duplicate the settings.
    | This will allow generating custom migrations per EM instance and not have
    | collisions when executing them.
    |
    */
    'default' => [
        'table_storage' => [
            /*
            |--------------------------------------------------------------------------
            | Migration Repository Table
            |--------------------------------------------------------------------------
            |
            | This table keeps track of all the migrations that have already run for
            | your application. Using this information, we can determine which of
            | the migrations on disk haven't actually been run in the database.
            |
            */
            'table_name' => 'doctrine_migration_versions',

            'version_column_length' => 191,

            /*
            |--------------------------------------------------------------------------
            | Schema filter
            |--------------------------------------------------------------------------
            |
            | Tables which are filtered by Regular Expression. You optionally
            | exclude or limit to certain tables. The default will
            | filter all tables.
            |
            */
            'schema_filter'    => '/^(?!password_resets|failed_jobs).*$/'
        ],

        'migrations_paths' => [
            'Database\\Migrations' => database_path('migrations')
        ],

        /*
        |--------------------------------------------------------------------------
        | Migration Organize Directory
        |--------------------------------------------------------------------------
        |
        | Organize migrations file by directory.
        | Possible values: "year", "year_and_month" and "none"
        |
        | none:
        |    directory/
        | "year":
        |    directory/2020/
        | "year_and_month":
        |    directory/2020/01/
        |
        */
        'organize_migrations' => 'none',
    ],
];
```

Clear cache again:

```bash
php artisan config:clear
php artisan cache:clear
```
------------------------------------------------------------------------

## Step 8: Run Sync and Create Migration using Doctrine

Run Sync:

```bash
php artisan doctrine:migrations:sync-metadata-storage
```

Generate and run migrations:

``` bash
php artisan doctrine:migrations:diff
php artisan doctrine:migrations:migrate
```

------------------------------------------------------------------------

## Step 9: Create Controller

``` bash
php artisan make:controller PostController
```

app/Http/Controllers/PostController.php

``` php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Post;
use Doctrine\ORM\EntityManagerInterface;

class PostController extends Controller
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function index()
    {
        $posts = $this->em
            ->getRepository(Post::class)
            ->findAll();

        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);

        $post = new Post();
        $post->setTitle($request->title);
        $post->setContent($request->content);

        $this->em->persist($post);
        $this->em->flush();

        return redirect()->route('posts.index');
    }

    public function destroy($id)
    {
        $post = $this->em->find(Post::class, $id);

        if ($post) {
            $this->em->remove($post);
            $this->em->flush();
        }

        return redirect()->route('posts.index');
    }
}
```

------------------------------------------------------------------------

## Step 10: Routes

routes/web.php

``` php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

Route::get('/', function () {
    return view('welcome');
});
```

------------------------------------------------------------------------

## Step 11: Create View

resources/views/posts/index.blade.php

``` html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctrine Posts</title>

    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa, #e4ecf7);
        }
    </style>
</head>
<body class="min-h-screen font-sans">

<div class="max-w-5xl mx-auto py-12 px-6">

    <!-- Header -->
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">
            📝 Doctrine Posts
        </h1>
        <p class="text-gray-500">
            Laravel 12 + Doctrine ORM – Basic Post Management
        </p>
    </div>

    <!-- Create Post Card -->
    <div class="bg-white/80 backdrop-blur-lg shadow-xl rounded-2xl p-8 mb-12 border border-gray-100">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Create New Post</h2>

        <form method="POST" action="{{ route('posts.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">Title</label>
                <input 
                    type="text" 
                    name="title"
                    required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                    placeholder="Enter post title..."
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">Content</label>
                <textarea 
                    name="content"
                    rows="4"
                    required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                    placeholder="Write something amazing..."
                ></textarea>
            </div>

            <button 
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl shadow-md transition duration-300"
            >
                Save Post
            </button>
        </form>
    </div>

    <!-- Posts List -->
    <div>
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">
            All Posts
        </h2>

        @if(count($posts) === 0)
            <div class="bg-white shadow-md rounded-xl p-6 text-center text-gray-500">
                No posts yet. Create your first one above 👆
            </div>
        @endif

        <div class="grid md:grid-cols-2 gap-6">
            @foreach($posts as $post)
                <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100 hover:shadow-2xl transition duration-300">
                    
                    <h3 class="text-xl font-bold text-gray-800 mb-3">
                        {{ $post->getTitle() }}
                    </h3>

                    <p class="text-gray-600 mb-6 leading-relaxed">
                        {{ $post->getContent() }}
                    </p>

                    <form method="POST" action="{{ route('posts.destroy', $post->getId()) }}">
                        @csrf
                        @method('DELETE')

                        <button 
                            type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition"
                        >
                            Delete
                        </button>
                    </form>

                </div>
            @endforeach
        </div>
    </div>

</div>

</body>
</html>
```

------------------------------------------------------------------------

## Output

<img width="1828" height="1090" alt="Screenshot 2026-02-24 174000" src="https://github.com/user-attachments/assets/8b21b5cd-7bb1-4f37-8b7a-6d0f8bd39909" />

<img width="1900" height="1030" alt="Screenshot 2026-02-24 174014" src="https://github.com/user-attachments/assets/fbd0e43d-90cf-4bae-8e52-b6449a92c849" />


------------------------------------------------------------------------

## Project Structure

```
PHP_Laravel12_Doctrine_ORM/
├── app/
│   ├── Entities/
│   │   └── Post.php
│   ├── Repositories/
│   │   └── PostRepository.php
│   └── Http/
│       └── Controllers/
│           └── PostController.php
├── config/
│   ├── doctrine.php
│   └── migrations.php
├── database/
│   └── migrations/          // Doctrine migration files (VersionXXXX.php)
├── routes/
│   └── web.php
├── resources/
│   └── views/
│       └── posts/
│           └── index.blade.php
└── .env
```

------------------------------------------------------------------------

Your PHP_Laravel12_Doctrine_ORM Project is now ready!
<<<<<<< HEAD


=======
>>>>>>> development
