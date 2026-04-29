<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Trash Posts</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: linear-gradient(135deg, #fdf2f8, #fef9c3);
        }
    </style>
</head>

<body class="min-h-screen">

    <div class="max-w-6xl mx-auto py-10 px-6">



        <!-- HEADER -->
        <div class="text-center mb-10">

            <h1 class="text-4xl font-extrabold text-gray-800">
                🗑️ Trash Posts
            </h1>

            <p class="text-gray-600 mt-2">
                Manage deleted posts — restore or remove permanently
            </p>

            <div class="mt-5">
                <a href="{{ route('posts.index') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl shadow">
                    ⬅ Back to Posts
                </a>
            </div>

            <!-- SUCCESS MESSAGE -->
            @if(session('success'))
                <div class="bg-green-500 text-white px-4 py-3 rounded-xl mb-6 text-center shadow-md">
                    {{ session('success') }}
                </div>
            @endif

        </div>

        <!-- TRASH LIST -->
        <div class="grid md:grid-cols-2 gap-6">

            @forelse($posts as $post)

                <div class="bg-white rounded-2xl shadow-lg p-6 border hover:shadow-2xl transition">

                    <!-- TITLE -->
                    <h2 class="text-xl font-bold text-gray-800">
                        {{ $post->getTitle() }}
                    </h2>

                    <!-- CONTENT -->
                    <p class="text-gray-600 mt-3 leading-relaxed">
                        {{ $post->getContent() }}
                    </p>

                    <!-- ACTIONS -->
                    <div class="mt-6 flex justify-between items-center">

                        <!-- RESTORE -->
                        <a href="{{ route('posts.restore', $post->getId()) }}"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow">
                            ♻ Restore
                        </a>

                        <!-- DELETE FOREVER -->
                        <form method="POST" action="{{ route('posts.forceDelete', $post->getId()) }}">
                            @csrf
                            @method('DELETE')

                            <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow">
                                ❌ Delete Forever
                            </button>
                        </form>

                    </div>

                </div>

            @empty

                <div class="col-span-2 text-center bg-white p-10 rounded-2xl shadow">
                    <h3 class="text-xl font-semibold text-gray-700">
                        🎉 Trash is empty
                    </h3>
                    <p class="text-gray-500 mt-2">No deleted posts found</p>
                </div>

            @endforelse

        </div>

    </div>

</body>

</html>