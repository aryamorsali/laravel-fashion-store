<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\SearchRequest;
use App\Models\Market\WarehouseTransaction;
use Illuminate\Http\Request;

class WarehouseTransactionController extends Controller
{
   public function index(SearchRequest $request)
   {
      $validated = $request->validated();

      $search = $validated['search'] ?? null;

      $query = WarehouseTransaction::query()->with([
         'warehouse',
         'productVariant.product',
         'productVariant.color',
         'productVariant.size',
      ]);
      if ($request->filled('search')) {

         $query->where(function ($q) use ($search) {
            $q->whereHas('productVariant.product', function ($p) use ($search) {
               $p->where('name', 'LIKE', '%' . $search . '%');
            })
               ->orWhereHas('warehouse', function ($w) use ($search) {
                  $w->where('name', 'LIKE', '%' . $search . '%');
               });
         });
      }

      $transactions = $query->orderBy('created_at', 'desc')->paginate(15)->appends(request()->query());

      return view('admin.market.warehouse.warehouse-transaction.index', compact('transactions'));
   }
}
