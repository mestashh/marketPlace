<?php

namespace App\Http\Controllers;

use App\Exceptions\Admin\AdminExistException;
use App\Exceptions\Conversation\ConversationAdminException;
use App\Http\Requests\Admin\JoinConversationRequest;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use App\Services\AdminService;
use Throwable;

class AdminController extends Controller
{
    public function __construct(private readonly AdminService $adminService)
    {
        $this->authorizeResource(Admin::class, 'admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::query()->paginate(20);

        return AdminResource::collection($admins);
    }

    /**
     * Store a newly created resource in storage.
     * @throws AdminExistException
     */
    public function store(StoreAdminRequest $request)
    {
        $admin = $this->adminService->store($request->validated());

        return new AdminResource($admin);
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        return new AdminResource($admin);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        $admin->update($request->validated());

        return new AdminResource($admin);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();

        return response()->noContent();
    }

    /**
     * @throws ConversationAdminException
     * @throws Throwable
     */
    public function joinConversation(JoinConversationRequest $request)
    {
        $this->authorize('joinConversation', Admin::class);
        $this->adminService->joinConversation($request->user(), $request->conversation_id);

        return response()->json(['message' => 'You have joined']);
    }
}
