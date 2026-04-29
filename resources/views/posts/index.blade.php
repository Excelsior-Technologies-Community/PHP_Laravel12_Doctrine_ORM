<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Doctrine Posts</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa, #e4ecf7);
        }
    </style>
</head>

<body class="min-h-screen">

    <div class="max-w-6xl mx-auto py-10 px-6">



        <!-- HEADER -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800">📝 Doctrine Posts</h1>
            <p class="text-gray-500 mt-2">Laravel 12 + Doctrine ORM CRUD</p>

            <div class="mt-4 flex justify-center gap-4">

                <a href="{{ route('posts.create') }}"
                    class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700">
                    + Create Post
                </a>

                <a href="{{ route('posts.trash') }}"
                    class="bg-gray-800 text-white px-5 py-2 rounded-lg hover:bg-gray-900">
                    Trash 🗑️
                </a>

            </div>
        </div>

        <!-- SUCCESS MESSAGE -->
        @if(session('success'))
            <div class="bg-green-500 text-white p-3 rounded-lg mb-5 text-center">
                {{ session('success') }}
            </div>
        @endif

        <!-- SEARCH -->
        <form method="GET" class="mb-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..."
                class="w-full p-3 border rounded-lg shadow-sm">
        </form>

        <!-- POSTS -->
        <div class="grid md:grid-cols-2 gap-6">

            @forelse($posts as $post)
                <div class="bg-white shadow-lg rounded-2xl p-6 border">

                    <h3 class="text-xl font-bold text-gray-800">
                        {{ $post->getTitle() }}
                    </h3>

                    <p class="text-gray-600 mt-3">
                        {{ $post->getContent() }}
                    </p>

                    <div class="mt-5">

                        <form method="POST" action="{{ route('posts.destroy', $post->getId()) }}">
                            @csrf
                            @method('DELETE')

                            <button class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                                Move to Trash
                            </button>
                        </form>

                    </div>

                </div>
            @empty
                <div class="col-span-2 text-center text-gray-500 bg-white p-6 rounded-lg">
                    No posts found 😢
                </div>
            @endforelse

        </div>

    </div>

</body>

</html>