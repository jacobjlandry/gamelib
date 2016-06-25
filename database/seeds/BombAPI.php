<?php

use Illuminate\Database\Seeder;

class BombAPI extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        $page = 1;
        $done = 0;
        while($platforms = \App\Giantbomb::list('platforms', $page)) {
            foreach($platforms->results as $platform) {
                $record = new \App\Platform();
                $record->bomb_id = $platform->id;
                $record->name = $platform->name;
                $record->image = $platform->image->super_url;
                $record->bomb_url = $platform->api_detail_url;
                $record->detail_url = $platform->site_detail_url;
                $record->save();
                $done++;
            }

            print round(($done/$platforms->number_of_total_results)*100) . "% (" . $done . " / " . $platforms->number_of_total_results . ")\r";

            $page++;
            if(!count($platforms->results)) {
                break;
            }
        }
        */

        $page = 139;
        $done = 24900;
        while($games = \App\GiantBomb::list('games', $page)) {
            foreach($games->results as $game) {
                $record = new \App\Game;
                $record->bomb_id = $game->id;
                $record->name = $game->name;
                $record->image = isset($game->image) ? $game->image->super_url : null;
                $record->bomb_url = $game->api_detail_url;
                $record->detail_url = $game->site_detail_url;
                $record->save();
                $done++;

                if(isset($game->platforms)) {
                    foreach ($game->platforms as $platform) {
                        $stored = \App\Platform::where('bomb_id', $platform->id)->first();
                        $map = new \App\GamePlatform(['game_id' => $record->id, 'platform_id' => $stored->id]);
                        $map->save();
                    }
                }
            }

            print round(($done/$games->number_of_total_results)*100) . "% (" . $done . " / " . $games->number_of_total_results . ")\r";

            $page++;
            if(!count($games->results)) {
                break;
            }
        }
    }
}
