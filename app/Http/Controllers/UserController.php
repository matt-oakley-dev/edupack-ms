<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create an account
     *
     * @param Request $request
     * @version 1.0.0
     */
    public function accounts(Request $request) {
        $accounts = auth()->user()->accounts;

        $response = [
            'accounts' => $accounts,
        ];

        return response($response, 201);
    }
}
