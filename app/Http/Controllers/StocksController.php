<?php

namespace App\Http\Controllers;

use App\Services\StocksService;
use Illuminate\Http\Request;

class StocksController extends Controller
{
    private StocksService $stocksService;

    public function __construct(StocksService $stocksService)
    {
        $this->stocksService = $stocksService;
    }

    public function stocksShow() {
        $this->stocksService->stocksHandleShow();
        $context = $this->stocksService->getContext();
        return view('stocks', $context);
    }
    public function stocksStore() {
        $this->stocksService->sellStocks();
        return redirect()->route('stocks');
    }
}
