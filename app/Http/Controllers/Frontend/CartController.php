<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartAddRequest;
use App\Models\EventTicket;
use App\Services\CartService;
use Illuminate\Http\Request;
use RuntimeException;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService)
    {
    }

    public function index(Request $request)
    {
        return view('frontend.pages.tickets.cart', [
            'items' => $this->cartService->items($request->session()->getId(), auth()->id()),
            'total' => $this->cartService->total($request->session()->getId(), auth()->id()),
        ]);
    }

    public function add(CartAddRequest $request)
    {
        $ticket = EventTicket::with('event')->findOrFail($request->integer('event_ticket_id'));

        try {
            $this->cartService->addGeneralAdmission(
                $ticket,
                (int) $request->input('quantity', 1),
                $request->session()->getId(),
                auth()->id()
            );
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage())->withInput();
        }

        return redirect()->route('frontend.cart')->with('success', 'Ticket added to cart. Checkout will be added in Step 10.');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'cart_item_id' => ['required', 'integer', 'exists:cart_items,id'],
        ]);

        $this->cartService->removeItem($request->integer('cart_item_id'), $request->session()->getId(), auth()->id());

        return redirect()->route('frontend.cart')->with('success', 'Cart item removed.');
    }

    public function clear(Request $request)
    {
        $this->cartService->clear($request->session()->getId(), auth()->id());

        return redirect()->route('frontend.cart')->with('success', 'Cart cleared.');
    }

    public function checkoutNotice(Request $request)
    {
        return redirect()->route('frontend.cart')->with('info', 'Checkout and order creation will be implemented in Step 10.');
    }
}
