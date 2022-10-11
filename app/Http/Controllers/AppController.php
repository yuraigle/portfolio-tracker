<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class AppController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function home(Request $request): View
    {
        $errors = $request->session()->get("form_errors", []);
        if ($errors) {
            $request->session()->forget("form_errors");
        }

        $lastUpd = DB::selectOne("SELECT date_add(min(updated_at), INTERVAL 6 HOUR) AS A FROM `assets` WHERE is_active = 1")->A;
        $usdRub = DB::selectOne("SELECT price AS A FROM `assets` WHERE ticker = 'USD' AND currency = 'RUB'")->A;
        $portfolios = DB::select("SELECT ID, `NAME` FROM `portfolios` ORDER BY ID");

        $mine = $sumT = $sumP = $sumA = [];

        $assets = DB::select("
SELECT a.ID, a.TICKER, a.PRICE, a.CURRENCY, t.ID AS TYPE_ID, t.`NAME` AS `TYPE`,
  ROUND(SUM(p.amount * a.price / (CASE WHEN a.currency = 'RUB' THEN u.price ELSE 1 END)), 2) AS TTL_USD
FROM `assets` a
  LEFT JOIN `asset_types` t ON t.id = a.`type`
  LEFT JOIN `assets` u ON u.ticker = 'USD' AND u.currency = 'RUB'
  LEFT JOIN `portfolio_assets` p ON a.id = p.asset_id
GROUP BY a.ID, a.TICKER, a.PRICE, a.CURRENCY, t.ID, t.`NAME`
ORDER BY t.id, TTL_USD DESC
        ");

        $res1 = DB::select("
SELECT p.ASSET_ID, a.TYPE, t.`NAME` AS `TYPE_NAME`, p.PORTFOLIO_ID, p.AMOUNT,
  ROUND(p.amount * a.price / (CASE WHEN a.currency = 'RUB' THEN u.price ELSE 1 END), 2) AS TTL_USD
FROM `portfolio_assets` p
  LEFT JOIN `assets` a ON a.id = p.asset_id
  LEFT JOIN `assets` u ON u.ticker = 'USD' AND u.currency = 'RUB'
  LEFT JOIN `asset_types` t ON t.id = a.`type`
ORDER BY a.TYPE, a.ID
");

        foreach ($res1 as $m) {
            $mine[$m->ASSET_ID][$m->PORTFOLIO_ID] = $m->AMOUNT;
            $sumT[$m->TYPE_NAME] = round(($sumT[$m->TYPE_NAME] ?? 0) + $m->TTL_USD, 2);
            $sumA[$m->ASSET_ID] = round(($sumA[$m->ASSET_ID] ?? 0) + $m->TTL_USD, 2);
            $sumP[$m->PORTFOLIO_ID] = round(($sumP[$m->PORTFOLIO_ID] ?? 0) + $m->TTL_USD, 2);
        }

        return view("home", compact("portfolios", "assets", "mine", "sumA", "sumP", "sumT",
            "usdRub", "lastUpd", "errors"));
    }

    function edit(Request $request): RedirectResponse
    {
        $p = $request->post('portfolio_id');
        $a = $request->post('asset_id');
        $c = $request->post('asset_amount');

        $errors = [];
        if (!$p) {
            $errors[] = "Портфель: обязетельное поле";
        }
        if (!$a) {
            $errors[] = "Тикер: обязательное поле";
        }
        if (is_null($c)) {
            $errors[] = "Количество: обязательное поле";
        } elseif (!preg_match('|^\d{0,14}(\.\d{0,6})?$|', $c)) {
            $errors[] = "Количество: ожидалось число";
        }

        if ($errors) {
            $request->session()->put("form_errors", $errors);
            return redirect("/");
        }

        $exists = DB::selectOne("select * from `portfolio_assets` where `portfolio_id`=? and `asset_id`=?", [$p, $a]);
        if ($exists) {
            DB::update("update `portfolio_assets` set `amount`=? where `portfolio_id`=? and `asset_id`=?",
                [$c, $p, $a]);
        } else {
            DB::insert("insert into `portfolio_assets` (`amount`, `portfolio_id`, `asset_id`) values (?,?,?)",
                [$c, $p, $a]);
        }

        return redirect("/");
    }
}
