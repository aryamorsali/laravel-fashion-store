<?php

namespace App\Console\Commands;

use App\Models\Market\AmazingSale;
use App\Models\Market\CommonDiscount;
use App\Models\Market\Coupon;
use Illuminate\Console\Command;

class ExpireDiscounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discounts:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'mark expired discounts as expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $couponDiscounts = Coupon::where('status', 1)
            ->where('end_date', '<=', now())
            ->update([
                'status' => 2,  //expired
                'updated_at' => now(),
            ]);

        $commonDiscounts = CommonDiscount::where('status', 1)
            ->where('end_date', '<=', now())
            ->update([
                'status' => 2,  //expired
                'updated_at' => now(),
            ]);

        $amazingSaleDiscounts = AmazingSale::where('is_active', 1)
            ->where('end_date', '<=', now())
            ->update([
                'is_active' => 0,  // inActive
                'updated_at' => now(),
            ]);
    }
}
