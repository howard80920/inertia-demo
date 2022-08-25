<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Presenters\UserPresenter;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function user(): ?User
    {
        return Auth::user();
    }

    public function edit()
    {
        return Inertia::render('User/Edit', [
            'user' => UserPresenter::make($this->user())->get(),
        ]);
    }

    public function update(UpdateUserRequest $request)
    {
        $this->user()->update($request->validated());

        return back()->with('success', '帳號更新成功');
    }

    public function destroy()
    {
        $user = $this->user();
        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', '帳號刪除成功');
    }
}
