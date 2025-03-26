<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

class SendProductToCashRegisterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $products;
    public $apiSettings;
    public $tries = 1;  
    public $timeout = 0;  
    public $retryUntil = null;

    public function __construct(array $products)
    {
        $this->products = $products;
        $this->apiSettings = Setting::where('type', 'api')
            ->where('key', 'like', 'cash_register%')
            ->pluck('value', 'key')
            ->mapWithKeys(fn ($value, $key) => [$key => $value])
            ->toArray();
    }

    public function handle()
    {
        $apiToken = $this->apiSettings['cash_register_api_key'];
        $apiUrl = $this->apiSettings['cash_register_api_url'];
        $client = new \GuzzleHttp\Client();

        $productsBody = '';
        foreach ($this->products as $product) {
            $label = $product->labels->isNotEmpty() ? $product->labels[0] : null;
            $image = $product->getAllImageUrls('m')[0] ?? "";
            $productsBody .= "{'number':'".$product->id."','title':'".$product->name."','taxassignment':'10000','sale':[{'pricelist':'Endkundenpreis','price':".$product->price."}],'displayName':'".$product->name."','EAN':'".$label->barcode."','description':'".$product->description."','createdAt':'".now()->toIso8601String()."','stock':{'reorder':{},'total':1},'images':[{'0':'".$image."'}]},";        
        }

        


        try {
            
            $response = $client->request('POST', $apiUrl . 'articles', [
                'body' => '{"number":"2324","title":"Testprodukt","ean":"000000000006","sale":[{"pricelist":"Endkundenpreis","price":"22.50"}]}',
                'headers' => [
                  'Authorization' => 'Bearer ' . $apiToken,
                  'accept' => 'application/json',
                  'content-type' => 'application/json',
                ],
              ]);

            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody(), true);
                \Log::info('API-Daten erfolgreich gesendet:', $responseData);
            } else {
                \Log::error('Fehler beim senden der API-Daten:', ['status' => $response->getStatusCode()]);
            }
        } catch (\Exception $e) {
            \Log::error('API-Anfrage fehlgeschlagen:', [
                'message' => $e->getMessage(),
                'request' => $e->getRequest(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ]);
        }
    }
}


