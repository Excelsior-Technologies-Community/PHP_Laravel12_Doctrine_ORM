<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-10">

<div class="max-w-xl mx-auto bg-white p-6 shadow rounded">

    <h2 class="text-2xl mb-4">Edit Post</h2>

    <form method="POST" action="{{ route('posts.update', $post->getId()) }}">
        @csrf
        @method('PUT')

        <input class="w-full border p-2 mb-3"
               name="title"
               value="{{ $post->getTitle() }}">

        <textarea class="w-full border p-2 mb-3"
                  name="content">{{ $post->getContent() }}</textarea>

        <button class="bg-blue-500 text-white px-4 py-2">
            Update
        </button>
    </form>

</div>

</body>
</html>