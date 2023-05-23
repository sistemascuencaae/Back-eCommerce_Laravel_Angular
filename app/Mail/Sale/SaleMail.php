<?php

namespace App\Mail\Sale;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SaleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sale;

    public function __construct($sale)
    {
        $this->sale = $sale;
    }

    public function build()
    {
        return $this->subject("ECOMMERCE DETALLADO DE COMPRA")->view('sale.sale_email');
    }
}