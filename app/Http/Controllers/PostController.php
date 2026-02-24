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