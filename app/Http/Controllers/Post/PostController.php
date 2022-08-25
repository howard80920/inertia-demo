<?php

namespace App\Http\Controllers\Post;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Presenters\PostPresenter;
use Inertia\Inertia;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function user(): ?User
    {
        return Auth::user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->user()
            ->posts()
            ->where('published', true)
            ->latest()
            ->paginate();

        return Inertia::render('Post/List', [
            'type' => 'published',
            'typeText' => '文章',
            'posts' => PostPresenter::collection($posts)->get(),
        ]);
    }

    public function drafts()
    {
        $posts = $this->user()
            ->posts()
            ->where('published', false)
            ->latest()
            ->paginate();

        return Inertia::render('Post/List', [
            'type' => 'drafts',
            'typeText' => '草稿',
            'posts' => PostPresenter::collection($posts)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Post/Form', [
            'post' => PostPresenter::make(Post::make())->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $post = $this->user()
            ->posts()
            ->create($request->validated());

        return redirect("/posts/{$post->id}")->with('success', '文章新增成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return Inertia::render('Post/Form', [
            'post' => PostPresenter::make($post)->with(function (Post $post) {
                return [
                    'content' => $post->content,
                ];
            })->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $post->update($request->validated());

        return redirect("/posts/{$post->id}")->with('success', '文章更新成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect('/posts')->with('success', '文章刪除成功');
    }

    public function like(Post $post)
    {
        if (!$post->published) {
            throw ValidationException::withMessages([
                'like' => '未發布文章不可以點喜歡',
            ]);
        }

        if ($post->isLikedBy($this->user())) {
            $this->user()->unlike($post);
        } else {
            $this->user()->like($post);
        }

        return back();
    }
}
