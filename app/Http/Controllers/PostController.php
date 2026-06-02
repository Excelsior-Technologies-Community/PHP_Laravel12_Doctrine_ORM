<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Post;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PostController extends Controller
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $qb = $this->em->createQueryBuilder();
        $qb->select('p')
            ->from(Post::class, 'p');

        if ($request->query('trash') === 'true') {
            $qb->where('p.deletedAt IS NOT NULL');
        } else {
            $qb->where('p.deletedAt IS NULL');
        }

        $qb->orderBy('p.id', 'DESC');

        if ($request->search) {
            $qb->andWhere(
                $qb->expr()->orX(
                    'p.title LIKE :search',
                    'p.content LIKE :search'
                )
            )
            ->setParameter('search', '%' . $request->search . '%');
        }

        $qb->setFirstResult($offset)
           ->setMaxResults($limit);

        $query = $qb->getQuery();
        $paginator = new Paginator($query);
        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / $limit);

        $posts = [];
        foreach ($paginator as $post) {
            $posts[] = $post;
        }

        return view('posts.index', compact('posts', 'page', 'totalPages', 'totalItems'));
    }

    public function create()
    {
        return view('posts.create');
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

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post created successfully 🎉');
    }

    public function edit($id)
    {
        $post = $this->em->find(Post::class, $id);

        if (!$post) {
            return redirect()->route('posts.index')->with('error', 'Post not found');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);

        $post = $this->em->find(Post::class, $id);

        if (!$post) {
            return redirect()->route('posts.index')->with('error', 'Post not found');
        }

        $post->setTitle($request->title);
        $post->setContent($request->content);
        
        $this->em->flush();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post updated successfully 👍');
    }

    public function destroy($id)
    {
        $post = $this->em->find(Post::class, $id);

        if ($post) {
            $post->setDeletedAt(new \DateTime());
            $this->em->flush();
        }

        return back()->with('success', 'Moved to trash 🗑️');
    }

    public function trash(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $qb = $this->em->createQueryBuilder();
        $qb->select('p')
            ->from(Post::class, 'p')
            ->where('p.deletedAt IS NOT NULL')
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

        $qb->setFirstResult($offset)
           ->setMaxResults($limit);

        $query = $qb->getQuery();
        $paginator = new Paginator($query);
        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / $limit);

        $posts = [];
        foreach ($paginator as $post) {
            $posts[] = $post;
        }

        return view('posts.trash', compact('posts', 'page', 'totalPages', 'totalItems'));
    }

    public function restore($id)
    {
        $post = $this->em->find(Post::class, $id);

        if ($post) {
            $post->setDeletedAt(null);
            $this->em->flush();
        }

        return back()->with('success', 'Post restored ♻️');
    }

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