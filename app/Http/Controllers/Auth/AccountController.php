<?php

namespace App\Http\Controllers\Auth;

use App\DataTables\CmsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Models\User;
use App\Services\AccountServices;
use Illuminate\Http\JsonResponse;
use Throwable;

class AccountController extends Controller
{
    public function index(CmsDataTable $dataTable)
    {
        $page_title = 'Accounts';
        $resource   = 'account';
        $columns    = ['id', 'name', 'Action'];
        $data       = User::getAllUsers();

        return $dataTable->render('participant.index', compact(
            'page_title',
            'columns',
            'data',
            'resource',
            'dataTable'
        ));
    }

    public function store(AccountRequest $request, AccountServices $service)
    {
        try {
            $user = $service->create($request->validated());
            
            if ($request->expectsJson()) {
                return new JsonResponse([
                    'message' => 'Account created successfully.',
                    'data'    => [
                        'id'         => $user->id,
                        'full_name'  => trim($user->first_name.' '.($user->middle_name ?? '').' '.$user->last_name),
                        'email'      => $user->email,
                        'contact'    => $user->contact_number,
                        'roles'      => $user->getRoleNames(),
                    ],
                ], 201);
            }
            
            return redirect()
                ->back()
                ->with('success', 'Account created successfully.');
        } catch (Throwable $e) {
            report($e);

            if ($request->expectsJson()) {
                return new JsonResponse([
                    'message' => 'Failed to create account.',
                    'failed'   => $e->getMessage(),
                ], 422);
            }

            return redirect()
                ->back()
                ->with('failed', 'Failed to create account. '.$e->getMessage())
                ->withInput();
        }
    }
    
    public function update(AccountRequest $request, User $account, AccountServices $service)
    {
        try {
            $user = $service->update($account, $request->validated());

            if ($request->expectsJson()) {
                return new JsonResponse([
                    'message' => 'Account updated successfully.',
                    'data'    => [
                        'id'        => $user->id,
                        'full_name' => trim($user->first_name.' '.($user->middle_name ?? '').' '.$user->last_name),
                        'roles'     => $user->getRoleNames(),
                    ],
                ], 200);
            }

            return back()->with('success', 'Account updated successfully.');
        } catch (Throwable $e) {
            report($e);

            if ($request->expectsJson()) {
                return new JsonResponse([
                    'message' => 'Failed to update account.',
                    'error'   => $e->getMessage(),
                ], 422);
            }

            return back()->withInput()->with('error', 'Failed to update account. '.$e->getMessage());
        }
    }

    public function destroy(User $account, AccountServices $service)
    {
        try {
            $label = trim($account->first_name.' '.($account->middle_name ?? '').' '.$account->last_name);

            $service->delete($account);

            if (request()->expectsJson()) {
                return new JsonResponse([
                    'message' => 'Account deleted successfully.',
                    'label'   => $label,
                ], 200);
            }

            return back()->with('success', "Deleted account: {$label}");
        } catch (Throwable $e) {
            report($e);

            if (request()->expectsJson()) {
                return new JsonResponse([
                    'message' => 'Failed to delete account.',
                    'error'   => $e->getMessage(),
                ], 422);
            }

            return back()->with('error', 'Failed to delete account. '.$e->getMessage());
        }
    }
}
