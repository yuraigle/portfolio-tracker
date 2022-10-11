<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use JsonPath\InvalidJsonException;
use JsonPath\JsonObject;

class AssetsUpdateCommand extends Command
{
    protected $signature = 'assets:update';
    protected $description = 'Update assets prices';

    public function handle()
    {
        $assets = DB::select("select * from `assets` where `is_active` = 1");
        foreach ($assets as $asset) {
            $price = null;
            $src = file_get_contents($asset->upd_url);

            if ($asset->upd_type === 'json') {
                try {
                    $json = new JsonObject($src);
                    $price = floatval($json->get($asset->upd_xpath)[0]);
                } catch (InvalidJsonException $e) {
                    print_r($asset->ticker . " : " . $e->getMessage() . PHP_EOL);
                }
            } elseif (preg_match('|^https://www\.tinkoff\.ru/invest/|', $asset->upd_url)) {
                if (preg_match('| data-qa-file="SecurityPriceDetails">(.+?) data-qa-file="NavigateButton"|', $src, $m1)) {
                    if (preg_match('|Money-module_[^"]+">(\d+)<span data-qa-file="Money">([^<]*)<|', $m1[1], $m2)) {
                        $price = floatval(str_replace(',', '.', $m2[1] . $m2[2]));
                    }
                }
            } elseif (preg_match('|https://www\.investing\.com/equities/|', $asset->upd_url)) {
                if (preg_match('| data-test="instrument-price-last">(.+?)</span>|', $src, $m)) {
                    $price = floatval(str_replace(',', '', $m[1]));
                }
            }

            if ($price) {
                DB::update("update `assets` set `price`=?, `updated_at` = NOW() where `id`=?", [$price, $asset->id]);
                print_r($asset->ticker . " : " . $price . PHP_EOL);
            }

            sleep(1);
        }

        print_r("ok" . PHP_EOL);
    }
}
