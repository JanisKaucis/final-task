<?php
 namespace App\Validators;

 use Illuminate\Http\Request;

 class SecondLoginValidator
 {
     private $request;

     public function __construct(Request $request)
     {
         $this->request = $request;
     }

     public function validateLoginForm()
     {
         $this->request->validate([
             'token' => ['required'],
         ]);
     }
 }
