<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthServices
{
    protected int $maxAttempts  = 5;  
    protected int $decaySeconds = 60; 
    
    protected function throttleKey(string $email): string
    {
        return 'login:'.Str::lower($email);
    }
    
    protected function lockKey(string $email): string
    {
        return 'login-lock:'.Str::lower($email);
    }

    protected function isLocked(string $email): bool
    {
        return Cache::has($this->lockKey($email));
    }

    protected function startLock(string $email): void
    {
        $until = now()->addSeconds($this->decaySeconds);
        Cache::put($this->lockKey($email), $until->getTimestamp(), $until);
    }

    protected function secondsUntilUnlock(string $email): int
    {
        $ts = Cache::get($this->lockKey($email));
        return $ts ? max(0, $ts - now()->getTimestamp()) : 0;
    }

    protected function hitThrottle(string $key): void
    {
        RateLimiter::hit($key, $this->decaySeconds);
    }

    public function authenticate(array $data): User
    {
        $email    = strtolower(trim($data['email']));
        $password = (string) ($data['password']);
        $remember = (bool) ($data['remember'] ?? false);

        $key = $this->throttleKey($email);
        
        if ($this->isLocked($email)) {
            $seconds = $this->secondsUntilUnlock($email);
            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds."
            ]);
        }
        
        if (Auth::guard('web')->attempt(['email' => $email, 'password' => $password], $remember)) {
            request()->session()->regenerate();
            RateLimiter::clear($key);
            Cache::forget($this->lockKey($email)); 
            return Auth::guard('web')->user();
        }
        
        $this->hitThrottle($key);

        $remaining = RateLimiter::retriesLeft($key, $this->maxAttempts);
        
        if ($remaining <= 0) {
            $this->startLock($email);
            RateLimiter::clear($key);
            $seconds = $this->secondsUntilUnlock($email);

            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Your account is temporarily locked. Try again in {$seconds} seconds."
            ]);
        }
        
        $used       = $this->maxAttempts - $remaining;
        $attemptTxt = $remaining === 1 ? 'attempt' : 'attempts';

        throw ValidationException::withMessages([
            'email' => "Incorrect email or password. Attempt {$used} of {$this->maxAttempts}. You have {$remaining} {$attemptTxt} left before a {$this->decaySeconds}-second lockout."
        ]);
    }

    public function logout(): void
    {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
