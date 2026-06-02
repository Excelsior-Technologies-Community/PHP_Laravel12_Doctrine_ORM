<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa, #e4ecf7);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">

    <div class="max-w-2xl w-full bg-white p-8 rounded-2xl shadow-xl border">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Create New Post</h1>

        <form method="POST" action="{{ route('posts.store') }}" class="space-y-4">
            @csrf
            <div>
                <input type="text" name="title" placeholder="Title" required
                    class="w-full border p-3 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <textarea name="content" placeholder="Content" rows="5" required
                    class="w-full border p-3 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 font-medium shadow-md">
                    Save
                </button>
                <a href="{{ route('posts.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-300 font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</body>
</html>