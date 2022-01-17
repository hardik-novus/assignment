<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function store(CreateUserRequest $request)
    {
        $validated = $request->validated();

        //
        $ip = $request->ip();
        $endpoint = $request->fullUrl();
        $userAgent = $request->header('User-Agent');

        // Modified value
        $isVaccinated = Str::upper($validated['is_vaccinated']);
        $vaccineName = Str::upper($validated['vaccine_name']);

        // Create a new user
        $userObj = new User();
        $userObj->first_name = $validated['first_name'];
        $userObj->last_name = $validated['last_name'];
        $userObj->email = $validated['email'];
        $userObj->phone_number = $validated['phone_number'];
        $userObj->address = $validated['address'];
        $userObj->date_of_birth = $validated['date_of_birth'];
        $userObj->is_vaccinated = $isVaccinated;
        $userObj->vaccine_name = ($isVaccinated === 'YES') ? $vaccineName : null;
        $userObj->save();

        // Send response
        return (new UserResource($userObj))->response()->setStatusCode(201);
    }
}
