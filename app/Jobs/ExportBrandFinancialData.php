<?php

namespace App\Jobs;

use App\Models\Brand;
use App\Models\Journal;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ExportBrandFinancialData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $brandId;
    protected $userEmail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($brandId, $userEmail)
    {
        $this->brandId = $brandId;
        $this->userEmail = $userEmail;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $brand = Brand::findOrFail($this->brandId);
        $stores = $brand->stores;
        $journals = Journal::whereIn('store_id', $stores->pluck('id'))->get();
        $totalRevenue = $journals->sum('revenue');
        $totalFoodCost = $journals->sum('food_cost');
        $totalLaborCost = $journals->sum('labor_cost');
        $totalProfit = $journals->sum('profit');

        // Create a CSV file
        $filename = 'financial_data_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $handle = fopen('php://temp', 'r+');
        
        // Add CSV headers
        fputcsv($handle, ['Total Revenue', 'Total Food Costs', 'Total Labor Costs', 'Total Profit']);

        // Add data rows
        // foreach ($journals as $journal) {
            fputcsv($handle, [
                "$".number_format( $totalRevenue, 2),
                "$".number_format( $totalFoodCost, 2),
                "$".number_format( $totalLaborCost, 2),
                "$".number_format( $totalProfit, 2),
            ]);
        // }

        // Rewind and save the file
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        // // Store the file
        Storage::put('exports/' . $filename, $content);
        
         // Generate a secure URL for the file
            $url = URL::temporarySignedRoute(
                'download.file',
                Carbon::now()->addMinutes(30), // URL expiration time
                ['filename' => $filename]
            );

        // Send email with download link
        Mail::to($this->userEmail)->send(new \App\Mail\FinancialBrandDataExport($url));
    }
}
