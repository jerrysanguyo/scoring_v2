<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountServices
{
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'first_name'     => $data['first_name'],
                'middle_name'    => $data['middle_name'] ?? null,
                'last_name'      => $data['last_name'],
                'email'          => $data['email'],
                'contact_number' => $data['contact_number'],
                'password'       => Hash::make($data['password']),
            ]);
            
            $user->assignRole($data['role']);
            
            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->withProperties([
                    'role' => $data['role'],
                    'by'   => auth()->user()?->id,
                ])
                ->log('account_created');

            return $user;
        });
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $before   = $user->only(['first_name','middle_name','last_name','email','contact_number']);
            $oldRoles = $user->getRoleNames()->values()->all();
            $oldRole  = $oldRoles[0] ?? null;
            $newRole  = $data['role'] ?? $oldRole;

            $payload = [
                'first_name'     => $data['first_name'],
                'middle_name'    => $data['middle_name'] ?? null,
                'last_name'      => $data['last_name'],
                'email'          => $data['email'] ?? null,
                'contact_number' => $data['contact_number'] ?? null,
            ];

            if (!empty($data['password'])) {
                $payload['password'] = Hash::make($data['password']);
            }

            $user->update($payload);

            if ($newRole && $oldRole !== $newRole) {
                $user->roles()->detach();
                $user->assignRole($newRole);
            }

            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->withProperties([
                    'before'   => $before,
                    'after'    => $user->only(['first_name','middle_name','last_name','email','contact_number']),
                    'oldRoles' => $oldRoles,
                    'newRoles' => $user->getRoleNames()->values()->all(),
                ])
                ->log('account_updated');

            return $user->fresh();
        });
    }

    

    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            $label   = trim($user->first_name.' '.($user->middle_name ?? '').' '.$user->last_name);
            $roleSet = $user->getRoleNames()->values()->all();
            
            $user->roles()->detach();

            $user->delete();

            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->withProperties([
                    'label' => $label,
                    'roles' => $roleSet,
                ])
                ->log('account_deleted');
        });
    }
}