<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MessagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Conversation $c): bool
    {
        return $user->isAdmin() || $c->user_id == $user->id || $c->seller_id == $user->seller->id;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Conversation $c, Message $message): bool
    {
        return $user->isAdmin() || $c->user_id == $user->id || $c->seller_id == $user->seller->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Conversation $c): bool
    {
        return $user->isAdmin() || $c->user_id == $user?->id || $c->seller_id == $user?->seller?->id;
    }
}
