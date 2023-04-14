<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Seller;
use App\Models\Identification;
use App\Models\Product;
use App\Models\Bidding;
use App\Models\Cart;
use App\Models\Card;
use App\Models\Wallet;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManagerStatic as Image;

class BuyerController extends Controller
{
    public function bid($pid)
    {
        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }

        $product = Product::where('pid', $pid)->first();



        return view('buyer.bid', compact('data', 'product'));
    }

    public function entryPayment(Request $request, $pid)
    {

        $request->validate([
            'bid_amount' => 'required',

        ]);

        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }

        $product = Product::where('pid', $pid)->first();

        if ($product->bought_by == $data->id) {
            return back()->with('fail', 'You are already winning.');
        }

        if ($product->status == 'carted') {
            return back()->with('fail', 'Item is already carted.');
        }

        if (
            ($request->bid_amount >= $product->start_price && $product->bought_by == 0)
            || ($request->bid_amount > ($product->current_price + 99) && $product->bought_by != 0)
        ) {

            $bidding = Bidding::where([
                ['pid', '=', $pid],
                ['uid', '=', $data->id],
            ])->first();

            if ($bidding) {
                $product->current_price = $request->bid_amount;
                $product->bought_by = $data->id;
                $res = $product->update();

                $res2 = Bidding::updateOrInsert(
                    ['pid' => $pid, 'uid' => $data->id],
                    ['amount' => $request->bid_amount]
                );

                if ($res && $res2) {
                    return redirect('homepage')->with('success', 'Bid Successful');
                }
            }

            $bidder = Bidding::where('pid', '=', $pid)->get();

            if ($bidder->count() < 3) {
                $bid_amount = $request->bid_amount;
                return view('buyer.entryPayment', compact('data', 'product', 'bid_amount'));
            } else {
                return back()->with('fail', 'No Slot Left.');
            }




        } else if ($request->bid_amount <= ($product->current_price + 99)) {
            return back()->with('fail', 'Must Bid atleast 100 TK more.');
        }


        return back()->with('fail', 'Invalid Bid Amount.');


    }

    public function setBid(Request $request, $pid)
    {
        $request->validate([
            'name' => 'required',
            'number' => 'required|digits:16',
            'date' => 'required',
            'cvv' => 'required|digits:3'

        ]);

        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }


        $identify = array();
        $identify = Card::where('card_number', '=', $request->number)
            ->where('name', '=', $request->name)
            ->where('expiry', '=', $request->date)
            ->where('cvv', '=', $request->cvv)
            ->first();

        if ($identify) {

            $product = Product::where('pid', $pid)->first();
            $product->current_price = $request->amount;
            $product->bought_by = $data->id;
            $product->status = 'bidding';
            $res = $product->update();

            $c_wallet = Wallet::where('id', '0')->first();
            $c_wallet->balance += 1000;
            $c_wallet->update();

            $res2 = Bidding::updateOrInsert(
                ['pid' => $pid, 'uid' => $data->id],
                ['amount' => $request->amount, 'card_number' => $request->number]
            );

            $data->user_type = '2';
            $data->update();

            if ($res && $res2) {
                $identify->balance -= 1000;
                $identify->update();
                return redirect('homepage')->with('success', 'Bid Successful');
            }

        }

        return back()->with('fail', 'Invalid Card Info.');

    }

    // Seller Control
    public function sellProduct($pid)
    {
        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }

        $product = Product::where('pid', $pid)->first();

        if ($product->status == 'bidding') {

            $product->status = 'carted';
            $res = $product->update();

            $cart = new Cart();
            $cart->pid = $pid;
            $cart->uid = $product->bought_by;
            $res2 = $cart->save();

            if ($res && $res2) {
                return back()->with('success', 'Product Carted.');
            }

            return back()->with('success', 'Something went wrong.');
        } else if ($product->status == 'carted') {
            return back()->with('fail', 'Product Already carted.');
        } else if ($product->status == 'sold') {
            return back()->with('fail', 'Product Already sold.');
        }

        return back()->with('fail', "The product hasn't been bid on.");

    }

    public function cart()
    {
        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }

        $cartItems = Cart::join('products', 'carts.pid', '=', 'products.pid')
            ->where('carts.uid', $data->id)
            ->where('products.bought_by', $data->id)
            ->get();

        if ($cartItems) {
            return view('buyer.cart', compact('data', 'cartItems'));
        }

        return back()->with('fail', 'Cart is empty.');


    }

    public function removeProduct($pid)
    {
        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }


        $bid = Bidding::where([
            ['pid', '=', $pid],
            ['uid', '=', $data->id],
        ])->delete();


        // echo "<pre>";
        // print_r($bid->toArray());
        // die;

        $cart = Cart::where([
            ['pid', '=', $pid],
            ['uid', '=', $data->id],
        ])->first();


        $res2 = $cart->delete();


        if ($bid && $res2) {

            $bidder = Bidding::where('pid', $pid)
                ->orderBy('amount', 'desc')
                ->first();

            if ($bidder) {

                $product = Product::where('pid', $pid)->first();
                $product->bought_by = $bidder->uid;
                $product->current_price = $bidder->amount;
                $res = $product->update();

                $cart = new Cart();
                $cart->pid = $pid;
                $cart->uid = $bidder->uid;
                $res2 = $cart->save();

                if ($res && $res2) {
                    return back()->with('success', 'Product removed successfully.');
                }
                return back()->with('fail', 'Could not update cart.');

            } else {

                $product = Product::where('pid', $pid)->first();
                $product->bought_by = '0';
                $product->current_price = '0';
                $product->status = 'offline';
                $res = $product->update();

                if ($res) {
                    return back()->with('success', 'Product removed successfully.');
                }
                return back()->with('fail', 'Could not update cart.');

            }
        }

        return back()->with('fail', 'Something went wrong.');


    }

    public function paymentGateway($pid)
    {

        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }


        $product = Product::where('pid', $pid)->first();

        return view('buyer.paymentGateway', compact('data', 'product'));
        // echo '<pre>';
        // print_r($pid);
    }

    public function buyProduct(Request $request, $pid)
    {
        $request->validate([
            'name' => 'required',
            'number' => 'required|digits:16',
            'date' => 'required',
            'cvv' => 'required|digits:3'

        ]);

        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }


        $identify = array();
        $identify = Card::where('card_number', '=', $request->number)
            ->where('name', '=', $request->name)
            ->where('expiry', '=', $request->date)
            ->where('cvv', '=', $request->cvv)
            ->first();

        if ($identify) {

            $product = Product::where('pid', $pid)->first();

            $wallet = Wallet::where('id', $product->sold_by)->first();
            $wallet->balance += $product->current_price * 0.95;
            $wallet->update();

            $c_wallet = Wallet::where('id', '0')->first();
            $c_wallet->balance -= 1000;
            $c_wallet->balance += $product->current_price * 0.05;
            $c_wallet->update();

            $identify->balance -= $product->current_price - 1000;
            $identify->update();

            $product->status = 'sold';
            $res = $product->update();

            $cart = Cart::where('pid', $pid)->delete();

            $bid = Bidding::where([
                ['pid', '=', $pid],
                ['uid', '=', $data->id],
            ])->delete();

            $bids = Bidding::where('pid', $pid)->get();

            foreach ($bids as $bid) {
                $card = Card::where('card_number', '=', $bid->card_number)->first();
                $card->balance += 1000;
                $card->update();
            }

            $c_wallet = Wallet::where('id', '0')->first();
            $c_wallet->balance -= 1000 * $bids->count();
            $c_wallet->update();

            $bidding = Bidding::where('pid', $pid)->delete();

            if ($res) {

                return redirect('homepage')->with('success', 'Product Bought');
            }

        }

        return back()->with('fail', 'Invalid Card Info.');

    }

    public function purchaseHistory(Request $request)
    {

        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }

        $search = $request['search'] ?? "";
        if ($search != "") {
            $products = Product::where('bought_by', $data->id)
                ->where('product_name', 'LIKE', "%$search%")
                ->where('status', 'sold')
                ->orderBy('product_name')
                ->get();


        } else {
            $products = Product::where('bought_by', $data->id)
                ->orderBy('product_name')
                ->get();
        }



        return view('buyer.purchaseHistory', compact('data', 'products', 'search'));
    }

    public function viewProduct($pid)
    {

        $data = array();
        if (Session::has('loginID')) {
            $data = User::where('id', '=', Session::get('loginID'))->first();

        }

        $product = Product::where('pid', $pid)->first();

        return view('buyer.viewProduct', compact('data', 'product'));

    }

//
}