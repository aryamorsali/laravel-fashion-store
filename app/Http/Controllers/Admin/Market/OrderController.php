<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Models\Market\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::orderBy('id', 'desc')->paginate(20);

        return view('admin.market.order.index', compact('orders'));
    }


    public function show(Order $order)
    {
        if ($order->order_status === 'not_checked') {
            $order->order_status = 'awaiting_confirmation';
            $order->save();
        }
        return view('admin.market.order.show', compact('order'));
    }

    public function detail(Order $order)
    {
        $order = Order::with([
            'orderItems.productVariant.product.attributeValues.productAttribute',
            'orderItems.amazingSale',
            'orderItems.productVariant.color',
            'orderItems.productVariant.size',
        ])->whereKey($order->getKey())->firstOrFail();
            
        return view('admin.market.order.detail', compact('order'));
    }

    public function newOrder()
    {
        $orders = Order::where('order_status', 'not_checked')->paginate(20);
        return view('admin.market.order.index', compact('orders'));
    }

    public function sending()
    {
        $orders = Order::where('delivery_status', 'sending')->paginate(20);
        return view('admin.market.order.index', compact('orders'));
    }

    public function unpaid()
    {
        $orders = Order::where('payment_status', 'unpaid')->paginate(20);
        return view('admin.market.order.index', compact('orders'));
    }
    public function canceled()
    {
        $orders = Order::where('order_status', 'canceled')->paginate(20);
        return view('admin.market.order.index', compact('orders'));
    }
    public function returned()
    {
        $orders = Order::where('order_status', 'returned')->paginate(20);
        return view('admin.market.order.index', compact('orders'));
    }

    public function changeSendStatus(Order $order)
    {
        switch ($order->delivery_status) {
            case 'sending':
                $order->delivery_status = 'shipped';
                break;
            case 'shipped':
                $order->delivery_status = 'delivered';
                break;
            case 'delivered':
                $order->delivery_status = 'canceled';
                break;
            default:
                $order->delivery_status = 'sending';
                break;
        }
        $order->save();
        return back()->with(
            'alert-section-success',
            'Delivery status successfully updated.'
        );
    }
    public function changeOrderStatus(Order $order)
    {
        switch ($order->order_status) {
            case 'not_checked':
                $order->order_status = 'awaiting_confirmation';
                break;
            case 'awaiting_confirmation':
                $order->order_status = 'confirmed';
                break;
            case 'confirmed':
                $order->order_status = 'not_confirmed';
                break;
            case 'not_confirmed':
                $order->order_status = 'canceled';
                break;
            case 'canceled':
                $order->order_status = 'returned';
                break;
            default:
                $order->order_status = 'not_checked';
                break;
        }
        $order->save();
        return back()->with(
            'alert-section-success',
            'Order status successfully updated.'
        );
    }
    public function cancelOrder(Order $order)
    {
        $order->order_status = 'canceled';

        $order->save();
        return back()->with(
            'alert-section-success',
            'Order status successfully updated.'
        );;
    }
}
