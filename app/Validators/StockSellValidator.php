<?php
namespace App\Validators;

use Illuminate\Http\Request;

class StockSellValidator
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function validateStockForm()
    {
        if ($this->request->input('sell')){
            $this->request->validate([
                'symbol' => ['required','alpha'],
                'amount' => ['required','integer']
            ]);
        }


    }
}
