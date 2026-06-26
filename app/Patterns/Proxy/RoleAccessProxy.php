<?php

namespace App\Patterns\Proxy;

use App\Models\User;

class RoleAccessProxy
{
    /**
     * @var User
     */
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get available access features based on user role.
     *
     * @return array
     */
    public function getAvailableAccess(): array
    {
        return match ($this->user->role) {
            'committee' => ['Manage Competitions', 'Review Registrations', 'Broadcast Email'],
            'participant' => ['Browse Competitions', 'View Registrations', 'Manage Teams'],
            'judge' => ['Evaluate Submissions', 'Give Scores'],
            default => [],
        };
    }

    /**
     * Forward property access to the underlying user model.
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->user->$name;
    }

    /**
     * Forward method calls to the underlying user model.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return $this->user->$name(...$arguments);
    }
}
