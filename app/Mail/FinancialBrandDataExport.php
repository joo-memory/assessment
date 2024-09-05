<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;

class FinancialBrandDataExport extends Mailable
{
    use Queueable, SerializesModels;
    
    protected $url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    
    public function __construct($url)
    {
        $this->url = $url;
    }

    public function build()
    {
       
        return $this->view('emails.financial_data_export')
                    ->with(['downloadLink' => $this->url,'title'=>"Overall Brand Profit"]);
    }
}
