<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckCustomerActive
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Decode the stringified JSON payload in 'data'
        $rawData = $request->input('data');

        if (is_string($rawData)) {
            $orders = json_decode($rawData, true);
        } else {
            $orders = (array) $rawData;
        }

        // 2. Loop through all orders to collect unique customer_ids
        $customerIds = [];

        if (is_array($orders)) {
            foreach ($orders as $order) {
                if (isset($order['customer_id']) && !empty($order['customer_id'])) {
                    $customerIds[] = $order['customer_id'];
                }
            }
        }

        $customerIds = array_unique($customerIds);

        // 3. Perform batched check if customer_ids exist
        if (!empty($customerIds)) {
            // Join customers with users using u_id to check status in one query
            $deactivatedUsers = DB::table('customers')
                ->join('users', 'customers.u_id', '=', 'users.id')
                ->whereIn('customers.id', $customerIds)
                ->where('users.is_active', 0)
                ->select('customers.id as customer_id', 'users.user_name')
                ->get();

            // If any deactivated user was found across the orders, block the request
            if ($deactivatedUsers->isNotEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'msg'    => 'Account is deactivated! Please contact Administrator.',
                ], 403);
            }
        }

        return $next($request);
    }
}