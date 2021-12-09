<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Create an account
     *
     * @param Request $request
     * @version 1.0.0
     */
    public function create(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
        ]);

        $account = Account::create([
            'name' => $fields['name'],
        ]);

        $response = [
            'account' => $account,
        ];

        return response($response, 201);
    }

    /**
     * Get an account
     *
     * @param int $id
     * @version 1.0.0
     */
    public function show($id) {
        return Account::find($id);
    }

    /**
     * Get an accounts users
     *
     * @param int $id
     * @version 1.0.0
     */
    public function users($id) {
        $account = Account::find($id);

        $response = [
            'users' => false,
        ];

        if ( $account ) {
            $response['users'] = $account->users;
        }

        return response($response, 201);
    }

    /**
     * Assign a user to an account
     *
     * @param int $account
     * @param int $user
     * @version 1.0.0
     */
    public function assign_user_to_account($account_id, $user_id) {
        $account = Account::find($account_id);

        $user = User::find($user_id);

        $response = [
            'message' => 'User is already added to account',
        ];

        if ( ! $account->users->contains($user) ) {
            $account->users()->attach($user);
            $response['message'] = 'User has been added to account';
        }

        return response($response, 201);
    }
}
