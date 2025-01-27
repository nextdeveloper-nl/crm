<?php

namespace NextDeveloper\CRM\Actions\Quotes;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Services\CurrenciesService;
use NextDeveloper\CRM\Database\Models\QuoteItems;
use NextDeveloper\CRM\Database\Models\Quotes;
use NextDeveloper\CRM\Database\Models\Users;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\Marketplace\Database\Models\ProductCatalogs;
use NextDeveloper\Marketplace\Database\Models\ProductCatalogsPerspective;
use NextDeveloper\Marketplace\Database\Models\ProductsPerspective;

/**
 * This action, assigns an account manager to the given user
 */
class RecalculateQuote extends AbstractAction
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * This action takes a user object and assigns an Account Manager
     *
     * @param Users $users
     */
    public function __construct(Quotes $quote, $params = null, $previousAction = null)
    {
        $this->model = $quote;

        parent::__construct($params, $previousAction);
    }

    public function handle()
    {
        $this->setProgress(0, 'Starting to recalculate quote');

        $items = QuoteItems::where('crm_quote_id', $this->model->id)->get();

        $detailedAmount = $this->model->detailed_amount;

        $itemCount = count($items);
        $step = floor(90 / $itemCount);
        $i = 1;

        foreach ($items as $item) {
            $progress = 10 + ($step * $i);
            $this->setProgress($progress, 'Calculating the item number: ' . $i);

            $productCatalog = ProductCatalogsPerspective::withoutGlobalScope(AuthorizationScope::class)
                ->where('id', $item->marketplace_product_catalog_id)
                ->first();

            $product = ProductsPerspective::withoutGlobalScope(AuthorizationScope::class)
                ->where('id', $productCatalog->marketplace_product_id)
                ->first();

            $currency = CurrenciesService::getCurrecyByCode($product->currency_code);

            if(!$productCatalog) {
                $item->delete();
            }

            Log::info(__METHOD__ . '| Calculating item id: ' . $item->id);

            Log::info(__METHOD__ . '| Price of product is: ' . $productCatalog->price . $currency->code);

            //  Updating item
            Log::info(__METHOD__ . '| Product catalog price: ' . $productCatalog->price);

            //  Updating without triggering events
            $item->updateQuietly([
                'unit_price'    =>  $productCatalog->price
            ]);

            $item = $item->fresh();

            $discountMultiplier = 1 - ($item->discount / 100);

            Log::info(__METHOD__ . '| Discount multiplier is: ' . $discountMultiplier);
            Log::info(__METHOD__ . '| Unit price is: ' . $item->unit_price);

            //  Calculation
            $price = [
                'avg_discount'  =>  $discountMultiplier,
                'original_price'    =>  $item->unit_price,
                'price' =>  $item->unit_price * $discountMultiplier * $item->quantity,
                'currency_code' =>  $currency->code
            ];

            //  Updating without triggering events
            $item->updateQuietly([
                'total_price'   =>  $price['price'],
            ]);

            Log::info(__METHOD__ . '| Price is: ' . $price['price']);

            //  If this is null, then we add and continue
            if(!$detailedAmount) {
                $detailedAmount = [ $currency->code => $price ];
                continue;
            }

            //  Adding the price to the list here
            if(array_key_exists($currency->code, $detailedAmount)) {
                $detailedAmount[$currency->code]['original_price'] += $price['original_price'];
                $detailedAmount[$currency->code]['price'] += $price['price'];
                $detailedAmount[$currency->code]['avg_discount'] = 1 - ($detailedAmount[$currency->code]['original_price'] / $detailedAmount[$currency->code]['price']);

                Log::info(__METHOD__ . '| Currency: ' . $currency->code . ' | ' . $detailedAmount[$currency->code]['price']);
            } else {
                $detailedAmount[$currency->code] = $price;
            }

            $i++;
        }

        $this->model->update([
            'detailed_amount' => $detailedAmount,
            'approval_level'    =>  'draft'
        ]);

        if(count($detailedAmount) > 1) {
            $this->model->update([
                'total_amount'  =>  -1,
                'common_currency_id'    =>  CurrenciesService::getDefaultCurrency()->id
            ]);
        } else {
            $this->model->update([
                'total_amount'  =>  $detailedAmount[CurrenciesService::getDefaultCurrency()->code]['price'],
                'common_currency_id'    =>  CurrenciesService::getDefaultCurrency()->id
            ]);
        }

        $this->setFinished('Quote recalculation finished');
    }
}
