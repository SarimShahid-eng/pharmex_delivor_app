<?php

namespace App\Policies;

use App\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    public function view(Admin $user, Admin $admin)
    {
        if($user->is_admin) return true;

        return $user->branch_id === $admin->branch_id ;
    }

    public function create(Admin $user)
    {
        return $user->is_admin || $user->is_manager;
    }

    public function update(Admin $user, Admin $admin)
    {
        if ($user->is_admin) return true;

        return $user->branch_id === $admin->branch_id;
    }

    public function delete(Admin $user, Admin $admin)
    {
        if ($user->is_admin) return true;

        return $user->branch_id === $admin->branch_id && !$admin->is_admin;
    }
}
