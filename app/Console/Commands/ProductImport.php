<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Client\Response;

class ProductImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:import {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products from external API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $productId = $this->option('id');

        if (!$productId) {
            $this->error('Product ID is required');
            return 1;
        }

        $importedProduct =  Http::get('https://fakestoreapi.com/products/'.$productId)->json();

        try {
            Product::create(
                [
                    'name' => $importedProduct['title'],
                    'price' => $importedProduct['price'],
                    'description' => $importedProduct['description'],
                    'category' => $importedProduct['category'],
                    'image_url' => $importedProduct['image'],
                ]
            );



            $this->info('Product imported successfully');
            $this->info('Product ID: ' .$importedProduct['title']);
            
        } catch (\Throwable $th) {
            //throw $th;
            $this->error('Error importing product');
            $this->error($th->getMessage());
        }
    }
}
