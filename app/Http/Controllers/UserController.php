<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = auth()->user()->role === Role::Developer
            ? User::all()
            : User::getWithoutDeveloper();

        return view('app.user.index', compact('users'));
    }

    public function create(): View
    {
        return view('app.user.create');
    }

    public function store(UserCreateRequest $request): RedirectResponse
    {
        User::create($request->validatedData());

        return redirect()->route('app.user.index')->with('success', 'ユーザーを作成しました。');
    }

    public function edit(User $user): View
    {
        return view('app.user.edit', compact('user'));
    }

    public function update(User $user, UserUpdateRequest $request): RedirectResponse
    {
        $user->update($request->validatedData());

        return redirect()->back()->with('success', 'ユーザー情報を更新しました。');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = User::findOrFail($request->delete_id);

        // 自分は削除できない
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', '自分自身を削除することはできません。');
        }

        // ログインユーザーが開発者でない場合、開発者ユーザーを削除できない
        if ($user->is_developer && ! auth()->user()->is_developer) {
            return redirect()->back()->with('error', '開発者ユーザーは削除できません。');
        }

        $user->delete();

        return redirect()->back()->with('success', 'ユーザーを削除しました。');
    }
}
