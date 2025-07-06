<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class UpdateSteamAppList extends Command
{
    protected $signature = 'steam:update-app-list';
    protected $description = 'Atualiza a lista completa de apps da Steam e salva no cache';
     public function handle()
    {
        ini_set('memory_limit', '512M');

        $this->info('Baixando lista de apps da Steam...');
        
        $response = Http::timeout(60)->get('https://api.steampowered.com/ISteamApps/GetAppList/v2/');
        
        if (!$response->ok()) {
            $this->error('Erro ao buscar lista da Steam.');
            return 1;
        }
        
        $data = $response->json();
        
        if (!isset($data['applist']['apps'])) {
            $this->error('Formato inesperado da resposta da Steam.');
            return 1;
        }
        
        $appList = $data['applist']['apps'];
        
        // Salvar JSON no storage/app/steam_app_list.json
        Storage::put('steam_app_list.json', json_encode($appList));
        
        $this->info('Lista salva em storage/app/steam_app_list.json com ' . count($appList) . ' apps.');
        
        return 0;
    }
}
