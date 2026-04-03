<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Http\Requests\Seller\StoreSellerRequest;
use App\Http\Requests\Seller\UpdateSellerRequest;
use App\Http\Resources\SellerForAdminResource;
use App\Http\Resources\SellerForUserResource;
use App\Models\Seller;
use App\Services\SellerService;
use Symfony\Component\HttpFoundation\Request;

class SellerController extends Controller
{
    public function __construct(
        private readonly SellerService $sellerService,
    ) {
        $this->authorizeResource(Seller::class, 'seller');
    }

    public function index(Request $request)
    {
        if ($request->user() && $request->user()->isAdmin()) {
            return SellerForAdminResource::collection(Seller::paginate(20));
        }

        return SellerForUserResource::collection(
            Seller::where('access_status', StatusEnum::ACCESS->value)->paginate(20)
        );
    }

    public function show(Request $request, Seller $seller)
    {
        $isAdmin = $request->user() && $request->user()->isAdmin();
        if (! $isAdmin && $seller->access_status !== StatusEnum::ACCESS->value) {
            abort(404);
        }

        return $isAdmin
            ? new SellerForAdminResource($seller)
            : new SellerForUserResource($seller);
    }

    public function showSellerInfo(Seller $seller)
    {
        $this->authorize('showSellerInfo', [Seller::class, $seller]);

        return new SellerForAdminResource($seller);
    }

    public function store(StoreSellerRequest $request)
    {
        $seller = $this->sellerService->create($request->user(), $request->validated());

        return new SellerForUserResource($seller);
    }

    public function update(UpdateSellerRequest $request, Seller $seller)
    {
        $seller->update($request->validated());

        return new SellerForUserResource($seller);
    }

    public function destroy(Seller $seller)
    {
        $seller->delete();

        return response()->noContent();
    }
}
