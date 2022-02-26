<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
/**
 * @OA\Info(title="Kmong-App API", version="0.1")
 * @OA\Server(url="http://localhost.com")
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Token을 이용한 인증 방식입니다.",
 *     name="Bearer Tokan Authentication",
 *     securityScheme="BearerToken",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="Authorization: Bearer <token>"
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
