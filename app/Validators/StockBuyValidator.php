<?php
namespace App\Validators;

use Illuminate\Http\Request;

class StockBuyValidator
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function validateStockLogoForm()
    {
        if ($this->request->input('find')){
            $this->request->validate([
               'logo' => ['required','alpha']
            ]);
        }


    }
    public function validateStockBuyForm()
    {
        if ($this->request->input('buy')){
            $this->request->validate([
                'amount' => ['required','integer'],
            ]);
        }
    }
}
