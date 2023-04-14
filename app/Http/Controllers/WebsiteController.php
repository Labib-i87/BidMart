<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Seller;
use App\Models\Identification;
use App\Models\Product;
use App\Models\Bidding;
use App\Models\Wallet;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManagerStatic as Image;

class WebsiteController extends Controller
{
    public function back()
    {
        return redirect(url()->previous());
    }
    public function homepage(Request $request)
    {
        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();
        }

        if ($data->user_type == '0' || $data->user_type == '2') {

            $search = $request['search'] ?? "";
            $filter = $request['status'] ?? "";

            if ($search != "") {
                $products = Product::where(function ($query) use ($search) {
                    $query->where('product_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('product_name', 'LIKE', '%' . str_replace(' ', '%', $search) . '%');
                })
                    ->whereNotIn('status', ['sold', 'offline', 'carted'])
                    ->orderBy('product_name')
                    ->get();


            } else if ($filter != "all" && $filter != "") {
                $products = Product::whereNotIn('status', ['sold', 'offline', 'carted'])
                    ->where('status', $filter)
                    ->orderBy('product_name')
                    ->get();


            } else {
                $products = Product::whereNotIn('status', ['sold', 'offline', 'carted'])
                    ->orderBy('product_name')
                    ->get();
            }


            return view('homepage', compact('data', 'products', 'search'));

        } else if ($data->user_type == '1') {

            $search = $request['search'] ?? "";
            $filter = $request['status'] ?? "";

            if ($search != "") {
                $products = Product::where('sold_by', $data->id)
                    ->where('product_name', 'LIKE', "%$search%")
                    ->whereNotIn('status', ['sold', 'offline', 'carted'])
                    ->orderBy('product_name')
                    ->get();

            } else if ($filter != "all" && $filter != "") {
                $products = Product::where('sold_by', $data->id)
                    ->whereNotIn('status', ['sold', 'offline', 'carted'])
                    ->where('status', $filter)
                    ->orderBy('product_name')
                    ->get();

            } else {
                $products = Product::where('sold_by', $data->id)
                    ->whereNotIn('status', ['sold', 'offline', 'carted'])
                    ->orderBy('product_name')
                    ->get();
            }



            return view('homepage', compact('data', 'products', 'search'));

        }

    }

    public function dashboard()
    {
        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }

        $seller = array();
        $seller = Seller::where('sid', '=', Session::get('loginID'))->first();



        return view('dashboard', compact('data', 'seller'));
    }

    public function verification()
    {
        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }



        return view('verification', compact('data'));
    }

    public function verifyUser(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'nid' => 'required|digits:10',
            'date' => 'required',
            'number' => 'required'
        ]);

        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }

        $seller = new Seller();
        $seller->sid = $data->id;
        $seller->full_name = $request->name;
        $seller->email = $data->email;
        $seller->dob = $request->date;
        $seller->nid = $request->nid;
        $seller->phone_no = $request->number;

        $identify = array();
        $identify = Identification::where('nid', '=', $seller->nid)
            ->where('name', '=', $seller->full_name)
            ->where('dob', '=', $seller->dob)
            ->where('phone_no', '=', $seller->phone_no)
            ->first();


        if ($identify) {

            if ($identify->flag == '1') {
                return back()->with('fail', 'Invalid Information Given.');
            }

            $res = $seller->save();

            $identify->flag = '1';
            $identify->update();

            $user = array();
            $user = User::where('id', '=', Session::get('loginID'))->first();
            $user->user_type = '1';
            $user->update();

            $wallet = new Wallet();
            $wallet->id = $data->id;
            $wallet->save();


            if ($res) {
                return redirect('dashboard')->with('success', 'Your verification is successful.');
            } else {
                return redirect('dashboard')->with('fail', 'Something went wrong.');
            }
        } else {
            return back()->with('fail', 'Invalid Information Given.');
        }




    }

    public function productEntry()
    {
        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }
        return view('productEntry', compact('data'));
    }

    public function productUpload(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'starting_price' => 'required',
            'buyout_price' => 'required',
            'image' => 'required'
        ]);

        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }

        if ($request->starting_price >= $request->buyout_price) {
            return back()->with('fail', 'Buyout price must be greater than starting price.');
        }

        $filename = time() . "-" . $data->username . "." . $request->file('image')->getClientOriginalExtension();
        $image = $request->file('image');
        $image_resize = Image::make($image->getRealPath());
        $image_resize->fit(300);
        $image_resize->save(public_path('uploads/' . $filename));

        $product = new Product();
        $product->product_name = $request->name;
        $product->description = $request->description;
        $product->image_path = $filename;
        $product->start_price = $request->starting_price;
        $product->buyout_price = $request->buyout_price;
        $product->sold_by = $data->id;

        $res = $product->save();

        if ($res) {
            return redirect('dashboard')->with('success', 'Your Product is successfully uploaded.');
        }


    }


    public function productHistory(Request $request)
    {
        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }

        $search = $request['search'] ?? "";
        if ($search != "") {
            $products = Product::where('sold_by', $data->id)
                ->where('product_name', 'LIKE', "%$search%")
                ->orderBy('product_name')
                ->get();


        } else {
            $products = Product::where('sold_by', $data->id)
                ->orderBy('product_name')
                ->get();
        }



        return view('productHistory', compact('data', 'products', 'search'));
    }

    public function payment()
    {
        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }
        return view('paymentGateway', compact('data'));
    }

    public function editProduct($pid)
    {
        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }


        $product = Product::where('pid', $pid)->first();

        if ($product->status == 'offline' || $product->status == 'online') {
            return view('editProduct', compact('data', 'product'));
        } else {
            return back()->with('fail', 'This product already has an active bidding process. Updates are not allowed.');
        }


    }

    public function updateProduct(Request $request, $pid)
    {

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'starting_price' => 'required',
            'buyout_price' => 'required',

        ]);

        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }

        if ($request->starting_price >= $request->buyout_price) {
            return back()->with('fail', 'Buyout price must be greater than starting price.');
        }


        $product = Product::where('pid', $pid)->first();
        $product->product_name = $request->name;
        $product->description = $request->description;
        $product->status = $request->option;
        $product->start_price = $request->starting_price;
        $product->buyout_price = $request->buyout_price;
        $product->sold_by = $data->id;

        if ($request->hasfile('image')) {
            $destination = 'uploads/' . $product->image_path;
            if (File::exists($destination)) {
                File::delete($destination);
            }

            $filename = time() . "-" . $data->username . "." . $request->file('image')->getClientOriginalExtension();
            $image = $request->file('image');
            $image_resize = Image::make($image->getRealPath());
            $image_resize->fit(300);
            $image_resize->save(public_path('uploads/' . $filename));
            $product->image_path = $filename;


        }

        $res = $product->update();

        if ($res) {
            // return redirect('homepage')->with('success', 'Your Product is successfully updated.');
            return redirect(url('view-product/' . $product->pid))->with('success', 'Your Product is successfully updated.');

        }

    }

    public function deleteProduct($pid)
    {
        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }



        $product = Product::where('pid', $pid)->first();

        if ($product->status == 'offline' || $product->status == 'online') {

            $destination = 'uploads/' . $product->image_path;
            if (File::exists($destination)) {
                File::delete($destination);
            }

            $res = $product->delete();

            if ($res) {
                return redirect('product-history')->with('success', 'Your Product is successfully deleted.');
            }
        } else {
            return back()->with('fail', 'This product already has an active bidding process. Deletion is not allowed.');
        }
    }

}