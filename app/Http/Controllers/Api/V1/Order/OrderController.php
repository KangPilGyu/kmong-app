<?php

namespace App\Http\Controllers\Api\V1\Order;

use App\Http\Requests\Order\OrderPostRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class OrderController extends BaseController
{
    /**
     *  @OA\Post(
     *    tags={"주문"},
     *    operationId="OrderController#create",
     *    path="/api/v1/orders",
     *    summary="주문 생성",
     *    description="주문 생성 API",
     *    security={ {"BearerToken": {}} },
     *    @OA\RequestBody(
     *        @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(property="product_id", type="number", description="상품 ID"),
     *          )
     *        )
     *    ),
     *    @OA\Response(
     *          response=200,
     *          description="주문 생성 성공 (with User, Product)",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="order", type="object", ref="#/components/schemas/Order"),
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
    public function create(OrderPostRequest $request): \Illuminate\Http\JsonResponse
    {
        $order = $request->user()->orders()->create($request->only(['product_id']));
        return response()->json([
            'order' => $order
        ]);
    }
    /**
     *  @OA\Get(
     *    tags={"주문"},
     *    operationId="OrderController#list",
     *    path="/api/v1/orders",
     *    summary="주문 조회",
     *    description="주문 조회 API",
     *    security={ {"BearerToken": {}} },
     *    @OA\RequestBody(
     *        @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(property="limit", type="number", description="pagnation limit"),
     *              @OA\Property(property="page", type="number", description="pagnation page"),
     *          )
     *        )
     *    ),
     *    @OA\Response(
     *          response=200,
     *          description="주문 조회 성공 (with User, Product)",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(type="object", ref="#/components/schemas/Order")),
     *              @OA\Property(property="current_page", type="number"),
     *              @OA\Property(property="per_page", type="number"),
     *              @OA\Property(property="total", type="number"),
     *              @OA\Property(property="last_page", type="number"),
     *              @OA\Property(property="path", type="string", format="url"),
     *              @OA\Property(property="first_page_url", type="string", format="url"),
     *              @OA\Property(property="last_page_url", type="string", format="url"),
     *              @OA\Property(property="prev_page_url", type="string", format="url"),
     *              @OA\Property(property="next_page_url", type="string", format="url"),
     *              @OA\Property(property="from", type="number"),
     *              @OA\Property(property="to", type="number"),
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
    public function list(Request $request)
    {
        $limit = $request->has('limit') ?: 20;
        $paginatedOrders = Order::where('user_id', $request->user()->id)
            ->with(['user', 'product'])
            ->paginate($limit);
        return response()->json($paginatedOrders);
    }
}
