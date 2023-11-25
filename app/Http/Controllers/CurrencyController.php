<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyRequest;
use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CurrencyController extends Controller
{
    public function getCurrencyRates(CurrencyRequest $request)
    {
        $valuteID = $request->input('valuteID');
        $fromDate = $request->input('from');
        $toDate = $request->input('to');
        $perPage = $request->query('per_page', 10);
        $rates = Currency::where('valuteID', $valuteID)
            ->whereBetween('date', [$fromDate, $toDate])
            ->paginate($perPage);

        return response()->json($rates);
    }
}
