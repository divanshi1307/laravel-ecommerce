<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Order;
use App\Models\OrderUpdate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function main()
    {
		if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
		}else{
            return redirect()->route('admin.login');
		}
	}
	
    public function login(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        if ($request->isMethod('post')) {
            $admin = Admin::where('username', $request->username)->first();
            if (!$admin || $admin->password !== md5($request->password)) {
                return back()->withErrors(['error' => 'Invalid username or password']);
            }
            Auth::guard('admin')->login($admin);

            $admin->last_login = now();
            $admin->save();   
            return redirect()->route('admin.dashboard')->with('status','Login successfully');	
        }
        return view('admin.login');
    }

    public function changepassword(Request $request)
    {
        $admin = Auth::guard('admin')->user(); 

        if ($request->isMethod('post')) {
            $request->validate([
                'opass' => 'required',
                'npass' => 'required',
                'cpass' => 'required'
            ], [
                'opass.required' => 'The old password cannot be left blank!',
                'npass.required' => 'The new password cannot be left blank!',
                'cpass.required' => 'The confirm password cannot be left blank!'
            ]);

            if ($admin->password !== md5($request->opass)) {
                return back()->withErrors(['error' => 'Old password incorrect!']);
            }

            if ($request->npass !== $request->cpass) {
                return back()->withErrors(['error' => "Passwords didn't match!"]);
            }

            $admin->password = md5($request->npass);
            $admin->save();

            return redirect()
                ->route('admin.changepassword')
                ->with('status', 'Password updated successfully.');
        }
        return view('admin.changepassword');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('status','Logged out successfully');
    }

	public function dashboard()
    {
        $brands = Brand::count();
        $products = Product::count();
        $categories = Category::count();
		return view('admin.dashboard', compact('brands', 'products', 'categories'));
	}

    public function settings(Request $request)
    {
		$admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login');
        }
		
		if ($request->isMethod('post')) {			
			foreach($request->except('_token') as $key=>$val){
				$row = Setting::where('key',$key)->first();
				if(count((Array)$row)>0){
					$s_row = Setting::find($row->id);
					$s_row->code = 'config';
					$s_row->value = $val;
					$s_row->save();
				}else{
					$s_row = new Setting;
					$s_row->code = 'config';
					$s_row->key = $key;
					$s_row->value = $val=="" ? " " : $val;
					$s_row->save();
				}				
			}
            return redirect()->route('admin.settings')->with('status','Data updated');
		}
		
		$data =array(); 
		
		$settings = array();
		foreach(Setting::where('code','config')->get()->toArray() as $k=>$v){
			$settings[$v['key']] = $v['value'];
		}
		
		$data['data'] = (object)$settings;
		return view('admin.settings',$data);
	}

    // Orders 
	public function orders(Request $request)
    {
        $query = Order::query();
        // Filters
        if ($request->order_id) {
            $query->where('order_id', $request->order_id);
        }

        if ($request->from) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->to) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        $results = $query->orderBy('id', 'DESC')->paginate(10);
        return view('orders.index', compact('results'));
    }

    public function vieworder($id)
    {
        $order = Order::with('history', 'items.product')->find($id);
        if (!$order) {
            return redirect('/admin/orders')->with('status', 'Order not found!');
        }
        $order->save();
        return view('orders.vieworder', [
            'order' => $order
        ]);
    }

    public function updateOrderStatus()
    {
        $order = Order::find(request()->id);

        if (!$order) {
            return response()->json(['msg' => 0]);
        }

        $order->status = request()->status;
        $order->save();

        $user = (object) [
            'first_name' => $order->first_name,
            'last_name'  => $order->last_name,
            'email'      => $order->email,
        ];

        if (empty($user->email)) {
            return response()->json([
                'msg' => 1,
                'warning' => 'Order updated, but customer email not available.'
            ]);
        }

        $content = "
            <p>Dear {$user->first_name},</p>
            <p>Your Order ID {$order->order_id} status has been updated.</p>
            <p><strong>Status:</strong> " . Order::$order_status[$order->status] . "</p>
        ";

        // Send mail
        Mail::send('mail.common', [
            'heading' => 'Order Update',
            'content' => $content
        ], function ($mail) use ($order, $user) {
            $mail->to($user->email)
                ->subject('Order Update: ' . $order->order_id);
        });

        return response()->json(['msg' => 1]);
    }

    public function orderupdate()
    {
        request()->validate([
            'content' => 'required'
        ]);

        $order_update = new OrderUpdate();
        $order_update->order_id = request()->order_id;
        $order_update->content = request()->content;
        $order_update->date_created = now();
        $order_update->save();

        $order = $order_update->order ?? null;
        $billing = $order->billing ?? null;
        $user = $billing ? json_decode($billing) : null;

        if (!$user || empty($user->email)) {
            return back()->with('status', 'Update saved, but email not sent because billing info missing.');
        }

        Mail::send('mail.common', [
            'heading' => 'Order Update',
            'content' => request()->content
        ], function ($mail) use ($order, $user) {
            $mail->to($user->email)
                ->subject('Order update for ORDER ID: ' . $order->order_id);
        });

        return back()->with('status', 'Update sent to customer successfully');
    }


}
