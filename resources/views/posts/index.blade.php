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