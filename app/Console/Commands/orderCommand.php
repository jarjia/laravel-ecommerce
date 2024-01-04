<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Console\Command;

class orderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vue-ecommerce:order-filter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command goes over all orders and deletes the necessary ones';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::all();

        foreach($orders as $order) {
            if($order['arrives_at'] < now()) {
                $order->delete();
            }
        }
    }
}
