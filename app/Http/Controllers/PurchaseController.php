<?php

namespace App\Http\Controllers;

use App\Services\PurchaseService;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function createPurchase(Request $request)
    {
        $data = $request->validate([
             'extra_attempts_id' => 'string|exists:extra_attempts,id'
        ]);

        $purchase = $this->purchaseService->createPurchase($data);

        return response()->json(['message' => 'Purchase successful', 'purchase' => $purchase], 201);
    }
}
