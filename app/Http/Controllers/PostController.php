<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Post;
use Doctrine\ORM\EntityManagerInterface;

class PostController extends Controller
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    // =========================
    // INDEX
    // =========================
    public function index(Request $request)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('p')
            ->from(Post::class, 'p')
            ->where('p.deletedAt IS NULL')
            ->orderBy('p.id', 'DESC');

        if ($request->search) {

            $qb->andWhere(
                $qb->expr()->orX(
                    'p.title LIKE :search',
                    'p.content LIKE :search'
                )
            )
                ->setParameter('search', '%' . $request->search . '%');
        }

        $posts = $qb->getQuery()->getResult();

        return view('posts.index', compact('posts'));
    }

    // =========================
    // CREATE
    // =========================
    public function create()
    {
        return view('posts.create');
    }

    // =========================
    // STORE + SUCCESS MSG
    // =========================
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

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post created successfully 🎉');
    }

    // =========================
    // SOFT DELETE + MSG
    // =========================
    public function destroy($id)
    {
        $post = $this->em->find(Post::class, $id);

        if ($post) {
            $post->setDeletedAt(new \DateTime());
            $this->em->flush();
        }

        return back()->with('success', 'Moved to trash 🗑️');
    }

    // =========================
    // TRASH
    // =========================
    public function trash()
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('p')
            ->from(Post::class, 'p')
            ->where('p.deletedAt IS NOT NULL');

        $posts = $qb->getQuery()->getResult();

        return view('posts.trash', compact('posts'));
    }

    // =========================
    // RESTORE + MSG
    // =========================
    public function restore($id)
    {
        $post = $this->em->find(Post::class, $id);

        if ($post) {
            $post->setDeletedAt(null);
            $this->em->flush();
        }

        return back()->with('success', 'Post restored ♻️');
    }

    // =========================
    // FORCE DELETE + MSG
    // =========================
    public function forceDelete($id)
    {
        $post = $this->em->find(Post::class, $id);

        if ($post) {
            $this->em->remove($post);
            $this->em->flush();
        }

        return back()->with('success', 'Deleted permanently ❌');
    }
}