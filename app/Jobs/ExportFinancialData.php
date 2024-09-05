<?php

namespace App\Jobs;

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

class ExportFinancialData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $storeId;
    protected $userEmail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($storeId, $userEmail)
    {
        $this->storeId = $storeId;
        $this->userEmail = $userEmail;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Fetch journals for the given store
        $journals = Journal::where('store_id', $this->storeId)->get();

        // Create a CSV file
        $filename = 'financial_data_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $handle = fopen('php://temp', 'r+');
        
        // Add CSV headers
        fputcsv($handle, ['Date', 'Revenue', 'Food Costs', 'Labor Costs', 'Profit']);

        // Add data rows
        foreach ($journals as $journal) {
            fputcsv($handle, [
                $journal->date,
                "$".number_format($journal->revenue, 2),
                "$".number_format($journal->food_cost, 2),
                "$".number_format($journal->labor_cost, 2),
                "$".number_format($journal->profit, 2),
            ]);
        }

        // Rewind and save the file
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);
        Storage::put('exports/' . $filename, $content);
        
         // Generate a secure URL for the file
            $url = URL::temporarySignedRoute(
                'download.file',
                Carbon::now()->addMinutes(30), // URL expiration time
                ['filename' => $filename]
            );

        // Send email with download link
        Mail::to($this->userEmail)->send(new \App\Mail\FinancialDataExport($url));
    }
}
