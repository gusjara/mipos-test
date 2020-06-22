<?php

namespace App\Http\Controllers\API;

use App\Balance;
use App\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BalanceController extends Controller
{

    public function cashierBalance()
    {
        //
        $balance = Balance::latest()->first();
        $date = ($balance)?Carbon::parse($balance->date_open)->format('Y-m-d'):Carbon::parse(date('Y-m-d'))->format('Y-m-d');
        $time = ($balance)?Carbon::parse($balance->date_open)->format('H:i:s'):Carbon::parse(date('H:i'))->format('H:i:s');
        return response()->json([
            'status' => 'Success',
            'results' =>[
                'date_open' => Carbon::parse($date)->format('Y/m/d'),
                'hour_open' => Carbon::parse($time)->format('H:i'),
                "value_previous_close" => ($balance && !is_null($balance->value_previous_close))? $balance->value_previous_close : "0",
                "value_open" => ($balance)?$balance->value_open:'0',
                "observation" => ($balance && !is_null($balance->observation))?$balance->observation:''
            ],
        ], 200);

    }

    public function cashierBalanceOpen(Request $request)
    {
        //
        // dd($request->date_open);
        $date = Carbon::parse($request->date_open)->format('Y-m-d');
        $time = Carbon::parse($request->hour_open)->format('H:i');
        $balance = Balance::create([
            "date_open" => $date.' '.$time,
            "value_open" => $request->value_open,
            "value_previous_close" => $request->value_previous_close,
            "observation" => $request->observation
        ]);

        return response()->json([
            'msg' => 'Información guardada con éxito',
            'results' =>[
                'date_open' => Carbon::parse($balance->date_open)->format('Y/m/d'),
                'hour_open' => Carbon::parse($balance->date_open)->format('H:i'),
                "value_previous_close" => ($balance && !is_null($balance->value_previous_close))?$balance->value_previous_close:'0',
                "value_open" => $balance->value_open,
            ],
        ], 201);

    }


    public function cashierBalanceClose(Request $request)
    {
        //
        // dd($request->all());
        $date = Carbon::parse($request->date_close)->format('Y-m-d');
        $time = Carbon::parse($request->hour_close)->format('H:i:s');
        $balance = Balance::latest()->first();
        
        $balance->date_close = $date.' '.$time;
        $balance->value_card = $request->value_card;
        $balance->value_cash = $request->value_cash;
        $balance->value_open = $request->value_open;
        $balance->value_close = $request->value_close;
        $balance->value_previous_close = $request->value_close;
        // $balance->value_sales = $request->value_sales;
        $balance->save();


        if ($request->expenses) {
            foreach ($request->expenses as $expense) {
                // dd($expense->name);
                $saexpense = Expense::create([
                    "balance_id" => $balance->id,
                    "name" => $expense['name'],
                    "value" => $expense['value']
                ]);
            }
        }

        return response()->json([
            'msg' => 'Información guardada con éxito',
            'results' =>[
            ],
        ], 200);
        
    }

    public function hasCashierBalance()
    {
        //
        $balance = Balance::latest()->first();
        
        return response()->json([
            'status' => 'Success',
            'results' =>[
                "value" => ($balance && !is_null($balance->value_open))?$balance->value_open:'0',
                "close" => ($balance && !is_null($balance->value_close))?$balance->value_close:'0',
                "card" => ($balance && !is_null($balance->value_card))?$balance->value_card:'0',
                "cash" => ($balance && !is_null($balance->value_cash))?$balance->value_cash:'0',
            ],
        ], 200);

    }


}
