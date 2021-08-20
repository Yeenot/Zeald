<?php
use Illuminate\Support;  // https://laravel.com/docs/5.8/collections - provides the collect methods & collections class
use LSS\Array2Xml;
// require_once('classes/Exporter.php');
// require_once('Controllers/Controllerr.php');
// include('include/utils.php');
require_once('Services/GetPlayerStats.php');
require_once('Services/GetPlayers.php');
require_once('Core/Display.php');

class ExportController {

    public function index($type, $format)
    {
        $data = [];
        if ($type === 'playerstats') {
            $data = GetPlayerStats::execute(args(['player', 'playerId', 'team', 'position', 'country']));
        } else if ($type === 'players') {
            $data = GetPlayers::execute(args(['player', 'playerId', 'team', 'position', 'country']));
        }
        
        if (!$data) {
            exit("Error: No data found!");
        }

        return Display::format($data, $format);
    }
}
