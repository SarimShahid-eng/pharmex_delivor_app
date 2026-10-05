<?php

namespace App\Policies;

use App\Admin;
use App\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function view(Admin $user, Order $order)
    {
        if($user->is_admin) return true;

        if($user->is_agent && $order->agent_id == $user->id) return true;
        
        return ($user->branch_id !== $order->branch_id);
    }
    
    public function create(Admin $user)
    {
        if ($user->is_agent) {
            return $this->deny(env("ERROR_403"));
        }
        return true;
    }
    
    public function assignAgent(Admin $user)
    {
        if ($user->is_booker || $user->is_agent) {
            return $this->deny(env("ERROR_403"));
        }
        return true;
    }

    public function edit(Admin $user, Order $order)
    {
        if(!$user->is_admin){
            return $this->deny(env("ERROR_403"));
        }
        return true;
    }
    
    public function delete(Admin $user, Order $order)
    {
        return $user->is_admin;
    }

    public function seeAmount(Admin $user, Order $order)
    {
        if ($user->is_agent) {
            return $this->deny(env("ERROR_403"));
        }
        return true;
    }
}
