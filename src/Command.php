<?php

namespace ScaryLayer\NovaPoshta;

use Illuminate\Console\Command as ConsoleCommand;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Command extends ConsoleCommand
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
    protected $description = 'Loads cities and warehouses lists';

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
        $response = Http::post('https://api.novaposhta.ua/v2.0/json/', [
            "modelName" => "Address",
            "calledMethod" => "getCities",
        ]);

        Storage::put(
            'nova-poshta/cities.json',
            collect($response->json()['data'])->toJson()
        );

        $this->info('Cities was loaded successfully');

        $response = Http::post('https://api.novaposhta.ua/v2.0/json/', [
            "modelName" => "Address",
            "calledMethod" => "getWarehouses",
        ]);

        $list = collect($response->json()['data'])->groupBy('CityRef');
        foreach ($list as $cityRef => $group) {
            Storage::put("nova-poshta/warehouses/$cityRef.json", $group->toJson());
        }

        $this->info('Warehouses was loaded successfully');
    }
}