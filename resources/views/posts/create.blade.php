<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded-lg shadow">

    <h1 class="text-2xl font-bold mb-5">Create New Post</h1>

    <form method="POST" action="{{ route('posts.store') }}">
        @csrf

        <input type="text"
               name="title"
               placeholder="Title"
               class="w-full border p-2 mb-3 rounded">

        <textarea name="content"
                  placeholder="Content"
                  class="w-full border p-2 mb-3 rounded"></textarea>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Save
        </button>
    </form>

    <a href="{{ route('posts.index') }}" class="text-blue-500 mt-3 inline-block">
        Back to Posts
    </a>

</div>

</body>
</html>