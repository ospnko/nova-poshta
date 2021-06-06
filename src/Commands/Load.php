<?php

namespace ScaryLayer\NovaPoshta\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Load extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nova-poshta:load';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads and store cities and warehouses lists';

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
        $this->getCities();
        $this->info('Cities was loaded successfully');

        $this->getWarehouses();
        $this->info('Warehouses was loaded successfully');
    }

    /**
     * Get cities from Nova Poshta and put it to json file into storage folder
     */
    protected function getCities(): void
    {
        $response = Http::post('https://api.novaposhta.ua/v2.0/json/', [
            "modelName" => "Address",
            "calledMethod" => "getCities",
        ]);

        Storage::put(
            'nova-poshta/cities.json',
            collect($response->json()['data'])->toJson()
        );
    }

    /**
     * Get warehouses from Nova Poshta and put warehouses of each city into
     * separate json files into storage folder
     */
    protected function getWarehouses(): void
    {
        $response = Http::post('https://api.novaposhta.ua/v2.0/json/', [
            "modelName" => "Address",
            "calledMethod" => "getWarehouses",
        ]);

        $list = collect($response->json()['data'])->groupBy('CityRef');
        foreach ($list as $cityRef => $group) {
            $path = "nova-poshta/warehouses/$cityRef.json";
            Storage::put($path, $group->toJson());
        }
    }
}