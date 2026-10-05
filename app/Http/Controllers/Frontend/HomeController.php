<?php

namespace App\Http\Controllers\Frontend;

use App\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        $data = array(
            'title' => '',
            'menu' => 'home'
        );

        return view('front.home')->with($data);
    }


    public function about()
    {
        $data = array(
            'title' => 'About Us',
            'menu' => 'about'
        );

        return view('front.about')->with($data);
    }

    public function track(Request $request)
    {
        $order = $order_details = [];
        if($request->order_id){
            $order = \App\Order::with('cargo_service')->where('order_no', $request->order_id)->first();
            if($order){
                $_order_details = \App\OrderStatusChange::where('order_id', $order->id)->get();

                foreach ($_order_details as $value) {
                    $order_details[$value->status] = $value;
                }
            }
        }
        $data = array(
            'title' => 'Track Order',
            'menu' => 'track',
            'order' => $order,
            'order_details' => $order_details
        );

        return view('front.track')->with($data);
    }

    public function contact()
    {
        $data = array(
            'title' => 'Contact Us',
            'menu' => 'contact'
        );

        return view('front.contact')->with($data);
    }

    public function save_contact(Request $request)
    {
        $validator = Validator::make($request->toArray(), array(
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'phone' => ['required', 'string'],
            'subject' => ['required', 'string'],
            'msg' => ['required', 'string', 'min:10'],
        ));

        if (!$validator->passes()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->subject = $request->subject;
        $contact->msg = $request->msg;

        $contact->save();
        return response()->json([
            'success' => "Thanks For contacting us we will contact you shortly!",
            'reload' => true
        ]);
    }

    public function services()
    {
        $data = array(
            'title' => 'Services',
            'menu' => 'services'
        );

        return view('front.services')->with($data);
    }

    public function terms_conditions()
    {
        $data = array(
            'title' => 'Term and conditions',
            'menu' => 'terms'
        );

        return view('front.terms')->with($data);
    }

    public function privacy_policy()
    {
        $data = array(
            'title' => 'Privacy Policy',
            'menu' => 'privacy'
        );

        return view('front.privacy')->with($data);
    }
}
