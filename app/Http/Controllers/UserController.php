<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    //
    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Gets all users",
     *     description="Returns all users from the system that the user has access to",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="All Users fetched successfully"),
     *         )
     *     )
     * )
     */
    public function index()
    {
        // return response()->json(['message' => 'User index']);
        return response()->json(User::paginate(), Response::HTTP_OK);

    }

    public function show($id)
    {
        return response()->json(User::find($id), Response::HTTP_OK);
    }

    public function store(UserCreateRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make('password')
        ]);
        return response()->json($user, Response::HTTP_CREATED);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        $user->update($request->only(['first_name', 'last_name', 'email']) +
            ($request->filled('password') ? ['password' => Hash::make($request->password)] : [ 'password' => $user->password ] )
        );
        return response()->json($user, Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        $user->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function profile()
    {
        $user = auth()->user();
        return response()->json($user, Response::HTTP_OK);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed|different:current_password',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully'], Response::HTTP_OK);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        $user = auth()->user();
        $user->update($request->only(['first_name', 'last_name', 'email']));

        return response()->json($user, Response::HTTP_OK);
    }   

}
