<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\ChangeStatusRequest;
use App\Http\Requests\Seller\StoreSellerRequest;
use App\Http\Requests\Seller\UpdateSellerRequest;
use App\Http\Resources\SellerResource;
use App\Models\Seller;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class SellerController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Seller::class, 'seller');
    }

    public function index()
    {
        $sellers = Seller::query()->paginate(20);

        return SellerResource::collection($sellers);
    }

    public function show(Seller $seller)
    {
        return new SellerResource($seller);
    }

    public function store(StoreSellerRequest $request, User $user)
    {
        $data = $request->validated();
        $seller = Seller::create([
            'user_id' => $request->user()->id,
            'TIN' => $data['TIN'],
        ]);

        return new SellerResource($seller)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateSellerRequest $request, Seller $seller)
    {
        $data = $request->validated();
        $seller->update($data);

        return new SellerResource($seller);
    }

    public function destroy(Seller $seller)
    {
        $seller->delete();

        return response()->noContent();
    }

    public function changeStatus(ChangeStatusRequest $request, Seller $seller)
    {
        $this->authorize('changeStatus', $seller);
        $seller->update($request->validated());

        return new SellerResource($seller);
    }
}
