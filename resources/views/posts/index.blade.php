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

        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800">📝 Doctrine Posts</h1>
            <p class="text-gray-500 mt-2">Laravel 12 + Doctrine ORM CRUD</p>
            <div class="mt-4 flex justify-center gap-4">
                <a href="{{ route('posts.create') }}" class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700">
                    + Create Post
                </a>
                <a href="{{ route('posts.trash') }}" class="bg-gray-800 text-white px-5 py-2 rounded-lg hover:bg-gray-900">
                    Trash 🗑️
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-500 text-white p-3 rounded-lg mb-5 text-center">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500 text-white p-3 rounded-lg mb-5 text-center">
                {{ session('error') }}
            </div>
        @endif

        <form method="GET" class="mb-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..."
                class="w-full p-3 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </form>

        <div class="grid md:grid-cols-2 gap-6">
            @forelse($posts as $post)
                <div class="bg-white shadow-lg rounded-2xl p-6 border flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">
                            {{ $post->getTitle() }}
                        </h3>
                        <p class="text-gray-600 mt-3">
                            {{ $post->getContent() }}
                        </p>
                    </div>
                    <div class="mt-5 flex gap-2">
                        <a href="{{ route('posts.edit', $post->getId()) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 text-sm">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('posts.destroy', $post->getId()) }}">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 text-sm">
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

        @if(isset($totalPages) && $totalPages > 1)
        <div class="mt-8 flex justify-center">
            <nav class="inline-flex rounded-md shadow-sm">
                <a href="{{ request()->fullUrlWithQuery(['page' => max(1, $page - 1)]) }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border rounded-l-md hover:bg-gray-50 {{ $page <= 1 ? 'pointer-events-none opacity-50' : '' }}">
                    Previous
                </a>
                @for($i = 1; $i <= $totalPages; $i++)
                    <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" 
                       class="px-4 py-2 text-sm font-medium border-t border-b border-r {{ $page == $i ? 'bg-blue-600 text-white border-blue-600' : 'text-gray-700 bg-white hover:bg-gray-50' }}">
                        {{ $i }}
                    </a>
                @endfor
                <a href="{{ request()->fullUrlWithQuery(['page' => min($totalPages, $page + 1)]) }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-l-0 rounded-r-md hover:bg-gray-50 {{ $page >= $totalPages ? 'pointer-events-none opacity-50' : '' }}">
                    Next
                </a>
            </nav>
        </div>
        @endif

    </div>

</body>
</html>