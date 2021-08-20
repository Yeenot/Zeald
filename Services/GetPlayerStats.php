<?php
use Illuminate\Support;
require_once('Core/QueryBuilder.php');

// retrieves & formats data from the database for export
class GetPlayerStats
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
        $playerStats = QueryBuilder::from('player_totals')
            ->join('roster', 'id', 'player_totals.player_id')
            ->select(['roster.name', 'player_totals.*'])
            ->search(self::$searchDetails, $searchable)
            ->get();

        $playerStats = $playerStats->map(function ($playerStat) {
            unset($playerStat['player_id']);
            $playerStat['total_points'] = ($playerStat['3pt'] * 3) + ($playerStat['2pt'] * 2) + $playerStat['free_throws'];
            $playerStat['field_goals_pct'] = $playerStat['field_goals_attempted'] ? (round($playerStat['field_goals'] / $playerStat['field_goals_attempted'], 2) * 100) . '%' : 0;
            $playerStat['3pt_pct'] = $playerStat['3pt_attempted'] ? (round($playerStat['3pt'] / $playerStat['3pt_attempted'], 2) * 100) . '%' : 0;
            $playerStat['2pt_pct'] = $playerStat['2pt_attempted'] ? (round($playerStat['2pt'] / $playerStat['2pt_attempted'], 2) * 100) . '%' : 0;
            $playerStat['free_throws_pct'] = $playerStat['free_throws_attempted'] ? (round($playerStat['free_throws'] / $playerStat['free_throws_attempted'], 2) * 100) . '%' : 0;
            $playerStat['total_rebounds'] = $playerStat['offensive_rebounds'] + $playerStat['defensive_rebounds'];

            return $playerStat;
        });

        return $playerStats;
    }
}