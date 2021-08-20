<?php
use Illuminate\Support;
require_once('Core/QueryBuilder.php');

// retrieves & formats data from the database for export
class GetPlayers
{
    private static $searchDetails = [
        'playerId' => 'roster.id',
        'player' => 'roster.name',
        'team' => 'roster.team_code',
        'position' => 'roster.pos',
        'country' => 'roster.nationality'
    ];

    public static function execute($searchable = [])
    {
        $players = QueryBuilder::from('roster')
            ->select(['roster.*'])
            ->search(self::$searchDetails, $searchable)
            ->get();

        $players = $players->map(function ($player) {
            unset($playerStat['id']);

            return $player;
        });

        return $players;
    }
}