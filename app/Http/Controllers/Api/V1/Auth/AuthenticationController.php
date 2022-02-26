<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\Auth\LoginPostRequest;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends BaseController
{
    /**
     * @OA\Post(
     *    tags={"인증"},
     *    operationId="AuthenticationController#login",
     *    path="/api/v1/auth/token",
     *    summary="로그인",
     *    description="로그인 API",
     *    @OA\RequestBody(
     *        @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(property="email", type="string", format="email"),
     *              @OA\Property(property="password", type="string", format="password")
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
    public function login(LoginPostRequest $request): \Illuminate\Http\JsonResponse
    {
        $authParam = [
            'email' => $request->email,
            'password' => $request->email.$request->password
        ];
        $user = User::where('email', $request->email)->first();
        if (!Auth::attempt($authParam) || !$user) {
            throw new HttpResponseException(response()->json([
                'message' => 'Can`t access this account',
            ], 401));
        }

        $user->tokens()->delete();
        $token = $user->createToken('auth_token');
        return response()->json([
            'user' => $user,
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * @OA\Post(
     *    tags={"인증"},
     *    operationId="AuthenticationController#logout",
     *    path="/api/v1/auth/logout",
     *    summary="로그아웃",
     *    description="로그아웃 API",
     *    security={ {"BearerToken": {}} },
     *    @OA\Response(
     *          response=200,
     *          description="로그아웃 성공",
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
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->tokens()->delete();
        return response()->json([]);
    }
}
