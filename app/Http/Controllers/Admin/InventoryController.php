<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\StockLog;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        // Load inventory rows + product names
        $inventory = Inventory::with('product')->get();

        return view('admin.inventory.index', compact('inventory'));
    }

    public function edit(Inventory $inventory)
    {
        return view('admin.inventory.edit', compact('inventory'));
    }
    
    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        $oldQuantity = $inventory->quantity;

        $inventory->update([
            'quantity' => $request->quantity,
        ]);

        StockLog::create([
            'product_id'   => $inventory->product_id,
            'old_quantity' => $oldQuantity,
            'new_quantity' => $request->quantity,
            'action'       => 'Stock updated manually',
        ]);

        return redirect()->route('inventory.index')
                        ->with('success', 'Inventory updated.');
    }



    public function logs()
    {
        $logs = StockLog::with('product')->latest()->get();
        return view('admin.users.logs', compact('logs'));
    }
}
