<?php

namespace App\Http\Controllers\Api\V1\Product;

use App\Http\Requests\Product\ProductPostRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ProductController extends BaseController
{
    /**
     *  @OA\Post(
     *    tags={"상품"},
     *    operationId="ProductController#create",
     *    path="/api/v1/products",
     *    summary="상품 생성",
     *    description="상품 생성 API",
     *    security={ {"BearerToken": {}} },
     *    @OA\RequestBody(
     *        @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(property="product_name", type="string", description="상품명"),
     *              @OA\Property(property="product_price", type="number", description="상품가격"),
     *          )
     *        )
     *    ),
     *    @OA\Response(
     *          response=200,
     *          description="상품 생성 성공",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="product", type="object", ref="#/components/schemas/Product"),
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
    public function create(ProductPostRequest $request): \Illuminate\Http\JsonResponse
    {
        $productParam = array_merge($request->only(['product_name', 'product_price']), [
            'creatable_id' => $request->user()->id,
            'creatable_type' => User::class
        ]);
        $product = Product::create($productParam);
        return response()->json([
            'product' => $product
        ]);
    }

    /**
     *  @OA\Get(
     *    tags={"상품"},
     *    operationId="ProductControllers#list",
     *    path="/api/v1/products",
     *    summary="상품 조회",
     *    description="상품 조회 API",
     *    security={ {"BearerToken": {}} },
     *    @OA\RequestBody(
     *        @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(property="product_query", type="string", description="이름 검색어"),
     *              @OA\Property(property="limit", type="number", description="pagnation limit"),
     *              @OA\Property(property="page", type="number", description="pagnation page"),
     *          )
     *        )
     *    ),
     *    @OA\Response(
     *          response=200,
     *          description="상품 조회 성공 (with User)",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(type="object", ref="#/components/schemas/Product")),
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
    public function list(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'product_query' => 'string',
            'limit' => 'nullable|integer|max:99'
        ]);
        $limit = $request->has('limit') ?: 20;
        $paginatedProducts = Product::when($request->has('product_query'), function ($query) use ($request) {
            $query->where('product_name', 'like', "%{$request->product_query}%");
        })->with(['user'])
            ->paginate($limit);
        return response()->json($paginatedProducts);
    }
}
