<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthServices;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthServices $authService)
    {
        $this->authService = $authService;
    }

    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(LoginRequest $request)
    {
        $user = $this->authService->authenticate($request->validated());

        $ip = $request->ip();
        $agent = $request->header('User-Agent');

        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties([
                'ip'     => $ip,
                'agent'  => $agent,
            ])
            ->log('User ' . $user->first_name . ' ' . $user->last_name . ' successfully logged in');

        return redirect()
            ->route(Auth::user()->getRoleNames()->first() . '.dashboard.index')
            ->with('success', 'You have successfully logged in!');
    }

    public function logout()
    {
        $user = Auth::user();
        $ip = request()->ip();
        $agent = request()->header('User-Agent');

        $this->authService->logout();

        if ($user) {
            activity()
                ->performedOn($user)
                ->causedBy($user)
                ->withProperties([
                    'ip'    => $ip,
                    'agent' => $agent,
                ])
                ->log('User ' . $user->first_name . ' ' . $user->last_name . ' successfully logged out');
        }

        return redirect()
            ->route('login')
            ->with('success', 'You have successfully logged out!');
    }
}
