<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\Auth\UserPostRequest;
use App\Models\User;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;


class UserController extends BaseController
{
    /**
     * @OA\Post(
     *    tags={"인증"},
     *    operationId="UserController#create",
     *    path="/api/v1/users",
     *    summary="회원 가입",
     *    description="회원가입 API",
     *    @OA\RequestBody(
     *        @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(property="email", type="string", format="email"),
     *              @OA\Property(property="password", type="string", format="password", description="대/소문자/숫자/특수문자 1개 이상 포함, 10자리 이상")
     *          )
     *        )
     *    ),
     *    @OA\Response(
     *          response=200,
     *          description="회원가입 성공",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *              @OA\Property(property="access_token", type="string", description="token"),
     *              @OA\Property(property="token_type", type="string", description="token_type"),
     *          )
     *    ),
     *    @OA\Response(
     *        response=422,
     *        description="Untitiy Error",
     *        @OA\JsonContent(type="object", ref="#/components/schemas/Exception422")
     *    ),
     *    @OA\Response(
     *        response=401,
     *        description="Authorization Error",
     *        @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="message", type="string", description="에러 메시지")
     *        )
     *    ),
     * )
     */
    public function create(UserPostRequest $request): \Illuminate\Http\JsonResponse
    {
        $userCreateParam = array_merge($request->only('email'), [
           'password' => Hash::make($request->email.$request->password)
        ]);
        $user = User::create($userCreateParam);
        $user->tokens()->delete();
        $token = $user->createToken('auth_token');

        return response()->json([
            'user' => $user,
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer'
        ]);
    }
}
