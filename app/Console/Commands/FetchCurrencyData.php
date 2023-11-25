<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
class FetchCurrencyData extends Command
{
    protected $signature = 'currency:fetch';
    protected $description = 'Fetch currency data from Central Bank of Russia';

    public function handle()
    {
        $client = new Client();
        $response = $client->request('GET', 'http://www.cbr.ru/scripts/XML_daily.asp');
        $endDate = Carbon::today();
        $startDate = $endDate->copy()->subDays(30)->format('d/m/Y');
        $endDate = $endDate->format('d/m/Y');
        $xml_daily = simplexml_load_string($response->getBody());
        foreach ($xml_daily->Valute as $daily) {
            $response = $client->request('GET', "https://www.cbr.ru/scripts/XML_dynamic.asp?date_req1={$startDate}&date_req2={$endDate}&VAL_NM_RQ={$daily['ID']}");
            $xml_dynamic = simplexml_load_string($response->getBody());
            foreach ($xml_dynamic->Record as $record) {
                $date = Carbon::createFromFormat('d.m.Y', $record['Date'])->format('Y-m-d');
                Currency::updateOrCreate(['date' => $date, 'valuteID' => (string)$daily['ID']],
                    [
                        'valuteID' => (string)$daily['ID'],
                        'numCode'  => (string)$daily->NumCode,
                        'charCode' => (string)$daily->CharCode,
                        'name'     => (string)$daily->Name,
                        'value'    => (float)str_replace(',', '.', $record->Value),
                        'date'     => $date
                    ]);
            }
        }
        $this->info('Currency data fetched successfully!');
    }
}
