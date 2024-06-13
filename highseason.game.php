<?php
 /**
  *------
  * BGA framework: Gregory Isabelli & Emmanuel Colin & BoardGameArena
  * highseason implementation : © <Mathieu Chatrain> <mathieu.chatrain@gmail.com>
  * 
  * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
  * See http://en.boardgamearena.com/#!doc/Studio for more information.
  * -----
  * 
  * highseason.game.php
  *
  * This is the main file for your game logic.
  *
  * In this PHP file, you are going to defines the rules of the game.
  *
  */


require_once( APP_GAMEMODULE_PATH.'module/table/table.game.php' );
include('modules/Pending.php');
include('modules/Board.php');
include('modules/Staff.php');
include('modules/Emperor.php');

class highseason extends Table
{
    public static $instance = null;
    
	function __construct( )
	{
        // Your global variables labels:
        //  Here, you can assign labels to global variables you are using for this game.
        //  You can use any number of global variables with IDs between 10 and 99.
        //  If your game has options (variants), you also have to associate here a label to
        //  the corresponding ID in gameoptions.inc.php.
        // Note: afterwards, you can get/set the global variables with getGameStateValue/setGameStateInitialValue/setGameStateValue
        parent::__construct();
        
        self::initGameStateLabels( array( 
            "round" => 10, 
            "preparing" => 11, 
            "preparingmax" => 12,
            "preparing3" => 13, 
            "preparingmax3" => 14,
            "checkmultiaction" => 15,
            "lvlaction6" => 16,
            "preparing61" => 17,
            "preparing63" => 18,
            "credit" => 19,
            "staffdouble" => 20,
            "turn" => 21,
            "variable1" => 22,
            "variable2" => 23,
            "variable3" => 24,

        ) ); 
        
        self::$instance = $this;

	}
	
    protected function getGameName( )
    {
		// Used for translations and stuff. Please do not modify.
        return "highseason";
    }	

    /*
        setupNewGame:
        
        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
    */
    protected function setupNewGame( $players, $options = array() )
    {    
        // Set the colors of the players with HTML color code
        // The default below is red/green/blue/orange/brown
        // The number of colors defined here must correspond to the maximum number of players allowed for the gams
        $gameinfos = self::getGameinfos();
        $default_colors = $gameinfos['player_colors'];
 
        // Create players
        // Note: if you added some extra field on "player" table in the database (dbmodel.sql), you can initialize it there.
        $sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar) VALUES ";
        $values = array();
        foreach( $players as $player_id => $player )
        {
            $color = array_shift( $default_colors );
            $values[] = "('".$player_id."','$color','".$player['player_canal']."','".addslashes( $player['player_name'] )."','".addslashes( $player['player_avatar'] )."')";
        }
        $sql .= implode( ',' , $values, );
        self::DbQuery( $sql );
        self::reattributeColorsBasedOnPreferences( $players, $gameinfos['player_colors'] );
        self::reloadPlayersBasicInfos();
        
            
/////////////////////////////////////////////////////////////////////////////////  
//       _____                        _____       _ _   _       _ _          _   _             
//      / ____|                      |_   _|     (_) | (_)     | (_)        | | (_)            
//     | |  __  __ _ _ __ ___   ___    | |  _ __  _| |_ _  __ _| |_ ______ _| |_ _  ___  _ __  
//     | | |_ |/ _` | '_ ` _ \ / _ \   | | | '_ \| | __| |/ _` | | |_  / _` | __| |/ _ \| '_ \ 
//     | |__| | (_| | | | | | |  __/  _| |_| | | | | |_| | (_| | | |/ / (_| | |_| | (_) | | | |
//      \_____|\__,_|_| |_| |_|\___| |_____|_| |_|_|\__|_|\__,_|_|_/___\__,_|\__|_|\___/|_| |_|
//                                                                                               
/////////////////////////////////////////////////////////////////////////////////    

        highseason::$instance->setGameStateValue('round', 1);
        

        $countplayer = count(self::getObjectListFromDB( "SELECT player_id FROM player", true ));
        $listeplayers = self::getObjectListFromDB( "SELECT player_id id FROM player", true );
        $nombreplayers = count(self::getObjectListFromDB( "SELECT player_id id FROM player", true ));

        $tableauhotel = [1,2,3,4,5,6,7,8];
        $tableaustaff = [1,2,3,4,5,6,7,8];
        $randmax = 8;

        for ($i=1; $i <=$countplayer; $i++)
        {
            $player = $listeplayers[$i-1];

            $randhotel = bga_rand(1,$randmax);
            $randstaff = bga_rand(1,$randmax);
            $h = $tableauhotel[$randhotel-1];
            $s = $tableaustaff[$randstaff-1];
            
            self::DbQuery( "UPDATE player set hotel = {$h} WHERE player_id = {$player}" );
            self::DbQuery( "UPDATE player set staff = {$s} WHERE player_id = {$player}" );

            $tableauhotel = array_diff($tableauhotel, array($h));
            $tableauhotel = array_values($tableauhotel);
            $tableaustaff = array_diff($tableaustaff, array($s));
            $tableaustaff = array_values($tableaustaff);
            $randmax = $randmax-1;

            
            //self::DbQuery( "UPDATE player set hotel = 3 WHERE player_id = {$player}" );  //// je force hotel et staff sur 1 pour les joueurs
            //self::DbQuery( "UPDATE player set staff = 1 WHERE player_id = {$player}" );  //// je force hotel et staff sur 1 pour les joueurs

        }

        foreach( $listeplayers as $id )
        {
            for ($p = 1; $p<=28; $p++)
            {
            self::DbQuery( "INSERT INTO hotel (player_id, porte) VALUES ({$id}, {$p})");
            }
            self::DbQuery( "UPDATE hotel set etat = 1 WHERE player_id = {$id} AND porte IN (3, 4, 5)" );
            self::DbQuery( "UPDATE player set moneygain = 7 WHERE player_id = {$id}" );

            $numhotel = intval(self::getUniqueValueFromDB("SELECT hotel FROM player WHERE player_id={$id}"));
            $couleur = $this->hotelboard['hotel'.$numhotel]['couleur']; 
            for ($c = 1; $c <=28; $c++)
            {
                self::DbQuery( "UPDATE hotel set couleur = {$couleur[$c-1]} WHERE player_id = {$id} AND porte = {$c}" );
                if(($c>=1)&&($c<=7))
                {
                    self::DbQuery( "UPDATE hotel set niveau = 1 WHERE player_id = {$id} AND porte = {$c}" );
                }
                if(($c>=8)&&($c<=14))
                {
                    self::DbQuery( "UPDATE hotel set niveau = 2 WHERE player_id = {$id} AND porte = {$c}" );
                }
                if(($c>=15)&&($c<=21))
                {
                    self::DbQuery( "UPDATE hotel set niveau = 3 WHERE player_id = {$id} AND porte = {$c}" );
                }
                if(($c>=22)&&($c<=28))
                {
                    self::DbQuery( "UPDATE hotel set niveau = 4 WHERE player_id = {$id} AND porte = {$c}" );
                }
            }

            $numstaff = intval(self::getUniqueValueFromDB("SELECT staff FROM player WHERE player_id={$id}"));
            $type = $this->staffboard['staff'.$numstaff]['type'];
            $prix = $this->staffboard['staff'.$numstaff]['prix'];

            for ($s = 1; $s <=6; $s++)
            {
                self::DbQuery( "INSERT INTO staff (player_id, pos, type, prix) VALUES ({$id}, {$s},{$type[$s-1]}, {$prix[$s-1]})");
                
            }
            
        }

        self::DbQuery( "UPDATE player set first = 1 WHERE player_no = 1" );

        
        
        if ($nombreplayers == 2 )
        {
            self::DbQuery( "UPDATE player set moneygain = 8 WHERE player_no = 2" );
            self::DbQuery( "UPDATE player set player_score = 2 WHERE player_no = 1" );
            self::DbQuery( "UPDATE player set player_score = 2 WHERE player_no = 2" );
        }
        if ($nombreplayers == 3 )
        {
            self::DbQuery( "UPDATE player set moneygain = 8 WHERE player_no = 2" );
            self::DbQuery( "UPDATE player set moneygain = 9 WHERE player_no = 3" );
            self::DbQuery( "UPDATE player set player_score = 2 WHERE player_no = 1" );
            self::DbQuery( "UPDATE player set player_score = 2 WHERE player_no = 2" );
            self::DbQuery( "UPDATE player set player_score = 3 WHERE player_no = 3" );
            
        }
        if ($nombreplayers == 4 )
        {
            self::DbQuery( "UPDATE player set moneygain = 8 WHERE player_no = 2" );
            self::DbQuery( "UPDATE player set moneygain = 9 WHERE player_no = 3" );
            self::DbQuery( "UPDATE player set moneygain = 10 WHERE player_no = 4" );
            self::DbQuery( "UPDATE player set player_score = 2 WHERE player_no = 1" );
            self::DbQuery( "UPDATE player set player_score = 3 WHERE player_no = 2" );
            self::DbQuery( "UPDATE player set player_score = 3 WHERE player_no = 3" );
            self::DbQuery( "UPDATE player set player_score = 3 WHERE player_no = 4" );
        }

        /************ Init Pending *****/

                
        foreach( $players as $player_id => $player )
        {
            $this->addPendingFirst($player_id, "NormalTurn");
        }

        $first = intval(self::getUniqueValueFromDB("SELECT player_id FROM player WHERE player_no=1"));
        $this->addPending($first, "Dice");


        highseason::$instance->notifyAllPlayers('message',clienttranslate( 'Round 1' ), array(
                                                
            )
            );

        /************ End of the game initialization *****/
    }

   
/////////////////////////////////////////////////////////////////////////////////  
//               _            _ _ _____        _            
//              | |     /\   | | |  __ \      | |           
//     __ _  ___| |_   /  \  | | | |  | | __ _| |_ __ _ ___ 
//    / _` |/ _ \ __| / /\ \ | | | |  | |/ _` | __/ _` / __|
//   | (_| |  __/ |_ / ____ \| | | |__| | (_| | || (_| \__ \
//    \__, |\___|\__/_/    \_\_|_|_____/ \__,_|\__\__,_|___/
//     __/ |                                                
//    |___/                                                 
/////////////////////////////////////////////////////////////////////////////////  


    protected function getAllDatas()
    {
        $result = array();
    
        $current_player_id = self::getCurrentPlayerId();    // !! We must only return informations visible by this player !!
    
        // Get information about players
        // Note: you can retrieve some extra field you added for "player" table in "dbmodel.sql" if you need it.
        $sql = "SELECT player_id id, player_score score FROM player ";
        $result['players'] = self::getCollectionFromDb( $sql );
  
        // TODO: Gather all information about current game situation (visible by player $current_player_id).

        $result['board'] = self::getObjectListFromDB( "SELECT player_id id, hotel hotel, staff staff FROM player" );

        $result['listplayers'] = self::getObjectListFromDB( "SELECT player_id id FROM player", true );
        $result['countplayers'][] = count(self::getObjectListFromDB( "SELECT player_id id FROM player", true ));

        $result['poignee'] = self::getObjectListFromDB( "SELECT player_id id, porte porte, etat etat FROM hotel" );

        $result['dice1'] = count(self::getObjectListFromDB( "SELECT id FROM dice WHERE valeur = 1", true ));
        $result['dice2'] = count(self::getObjectListFromDB( "SELECT id FROM dice WHERE valeur = 2", true ));
        $result['dice3'] = count(self::getObjectListFromDB( "SELECT id FROM dice WHERE valeur = 3", true ));
        $result['dice4'] = count(self::getObjectListFromDB( "SELECT id FROM dice WHERE valeur = 4", true ));
        $result['dice5'] = count(self::getObjectListFromDB( "SELECT id FROM dice WHERE valeur = 5", true ));
        $result['dice6'] = count(self::getObjectListFromDB( "SELECT id FROM dice WHERE valeur = 6", true ));

        $result['round'][] = highseason::$instance->getGameStateValue('round');
        $result['turn'][] = highseason::$instance->getGameStateValue('turn');

        

        

        $result['money'] = self::getObjectListFromDB( "SELECT player_id id, moneygain mgain, moneyuse muse FROM player" );

        $result['credituse'] = self::getObjectListFromDB( "SELECT player_id id, credituse credituse FROM player" );

        $result['first'][] = self::getUniqueValueFromDB("SELECT player_id FROM player WHERE first=1");

        $listplayers = self::getObjectListFromDB("SELECT player_id id FROM player", true);

        foreach($listplayers as $player)
        {
            $result['bonusetage'][$player] = self::getObjectListFromDB( "SELECT l1 l1, l2 l2, l3 l3, l4 l4, l5 l5, l6 l6, l7 l7, l8 l8, c1 c1, c2 c2, c3 c3, c4 c4, c5 c5, c6 c6, c7 c7 FROM player WHERE player_id = {$player}" );
            $result['score'][$player] = self::getObjectListFromDB( "SELECT vpstaff score_1, vpgroupe score_2, vpligne4 score_3, vpligne3 score_4, vpligne2 score_5, vpligne1 score_6, vpemperor score_7, moneygain score_81, moneyuse score_82, vpetage score_9, credituse score_10 FROM player WHERE player_id = {$player}" );
            $result['emperor'][$player] = self::getObjectListFromDB( "SELECT emperor1 emperor1, emperor2 emperor2, emperor3 emperor3 FROM player WHERE player_id = {$player}" );
            $result['bonusemperor'][$player] = self::getObjectListFromDB( "SELECT bonusemperor1 bonusemperor1, bonusemperor2 bonusemperor2, bonusemperor3 bonusemperor3 FROM player WHERE player_id = {$player}" );
            $result['malusemperor'][$player] = self::getObjectListFromDB( "SELECT malussemperor1 malusemperor1, malussemperor2 malusemperor2, malussemperor3 malusemperor3 FROM player WHERE player_id = {$player}" );
            $result['staff'][$player] = self::getObjectListFromDB( "SELECT pos pos, etat etat, type type FROM staff WHERE player_id = {$player}" );
        }
        


        return $result;
    }

   
/////////////////////////////////////////////////////////////////////////////////  
//     _____                      _____                                   _             
//    / ____|                    |  __ \                                 (_)            
//   | |  __  __ _ _ __ ___   ___| |__) | __ ___   __ _ _ __ ___  ___ ___ _  ___  _ __  
//   | | |_ |/ _` | '_ ` _ \ / _ \  ___/ '__/ _ \ / _` | '__/ _ \/ __/ __| |/ _ \| '_ \ 
//   | |__| | (_| | | | | | |  __/ |   | | | (_) | (_| | | |  __/\__ \__ \ | (_) | | | |
//    \_____|\__,_|_| |_| |_|\___|_|   |_|  \___/ \__, |_|  \___||___/___/_|\___/|_| |_|
//                                                 __/ |                                
//                                                |___/                                 
/////////////////////////////////////////////////////////////////////////////////    

    function getGameProgression()
    {
        $r = highseason::$instance->getGameStateValue('round');
        $progression = floor(($r * 100)/7);
        return $progression;
    }


/////////////////////////////////////////////////////////////////////////////////  
//     _    _ _   _ _ _ _            __                  _   _                 
//    | |  | | | (_) (_) |          / _|                | | (_)                
//    | |  | | |_ _| |_| |_ _   _  | |_ _   _ _ __   ___| |_ _  ___  _ __  ___ 
//    | |  | | __| | | | __| | | | |  _| | | | '_ \ / __| __| |/ _ \| '_ \/ __|
//    | |__| | |_| | | | |_| |_| | | | | |_| | | | | (__| |_| | (_) | | | \__ \
//     \____/ \__|_|_|_|\__|\__, | |_|  \__,_|_| |_|\___|\__|_|\___/|_| |_|___/
//                           __/ |                                             
//                          |___/                                              
/////////////////////////////////////////////////////////////////////////////////  

function addPending($player_id, $function, $arg = NULL, $arg2 = NULL, $arg3 = NULL, $arg4 = NULL) {
    $sql = "INSERT INTO pending (player_id, function, arg, arg2, arg3, arg4) VALUES (".$player_id.", '".$function."', '".$arg."', '".$arg2."', '".$arg3."', '".$arg4."')";
    self::DbQuery( $sql );
}

function addPendingTarget($player_id, $function, $target, $arg = NULL, $arg2 = NULL, $arg3 = NULL, $arg4 = NULL) {
    $sql = "INSERT INTO pending (player_id, function, target, arg, arg2, arg3, arg4) VALUES (".$player_id.", '".$function."', '".$target."', '".$arg."', '".$arg2."', '".$arg3."', '".$arg4."')";
    self::DbQuery( $sql );
}

function addPendingFirst($player_id, $function, $arg = NULL, $arg2 = NULL, $arg3 = NULL, $arg4 = NULL) {
    $minid = self::getUniqueValueFromDB( "select min(id) from pending")-1;
    $sql = "INSERT INTO pending (id, player_id, function, arg, arg2) VALUES (".$minid.",".$player_id.", '".$function."', '".$arg."', '".$arg2."')";
    self::DbQuery( $sql );
}

function getPlayerRelativePositions()  // permet de mettre dans view.php les joueurs dans l'ordre de la base de données et de positionner le current player en haut avec les autres joueurs dans l'ordre du tour
    {
        $result = array();
        
        $players = self::loadPlayersBasicInfos();
        $nextPlayer = self::createNextPlayerTable(array_keys($players)); //met joueurs dans l'ordre du tour au niveau de l'affichage à droite
        
        $current_player = self::getCurrentPlayerId();
        
        if(!isset($nextPlayer[$current_player])) {
            // Spectator mode: prend la vue du premier joueur de la liste
            $player_id = $nextPlayer[0];
        }
        else {
            // Normal mode: current player est premier de la liste puis les autres dans l ordre de la base de données player
            $player_id = $current_player;
        }
        $result[] = $player_id;
        
        for($i=1; $i<count($players); $i++) {
            $player_id = $nextPlayer[$player_id];
            $result[] = $player_id;
        }
        return $result;
    }

    function getLogsRessource( $type ) 
    {
		if($type == 1)
        {return "<div class='icone_k'></div>";}
        if($type == 2)
        {return "<div class='icone_e'></div>";}
        
        
    }

    function getLogsDice( $type ) 
    {
		if($type == 1)
        {return "<div class='icone_dice1'></div>";}
        if($type == 2)
        {return "<div class='icone_dice2'></div>";}
        if($type == 3)
        {return "<div class='icone_dice3'></div>";}
        if($type == 4)
        {return "<div class='icone_dice4'></div>";}
        if($type == 5)
        {return "<div class='icone_dice5'></div>";}
        if($type == 6)
        {return "<div class='icone_dice6'></div>";}
        
        
        
    }

function getPorteAdjacente()
{
    $ret = [];
    $player_id = $this->getActivePlayerId();
    $porte = self::getObjectListFromDB( "SELECT porte FROM hotel WHERE etat != 0 AND player_id = {$player_id}", true );
    foreach ($porte as $numero)
    {
        if($numero == 1)
        {
            $pos1 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero+1)));
            $pos2 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero+7)));
            if($pos1 == 0)
            {
                $ret[] = $numero+1;
            }
            if($pos2 == 0)
            {
                $ret[] = $numero+7;
                
            }

        }
        if(($numero >= 2)&&($numero <= 6))
        {
            $pos1 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero-1)));
            $pos2 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero+7)));
            $pos3 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero+1)));

            if($pos1 == 0)
            {
                $ret[] = $numero-1;
            }
            if($pos2 == 0)
            {
                $ret[] = $numero+7;
                
            }
            if($pos3 == 0)
            {
                $ret[] = $numero+1;
                
            }

            
        }
        if($numero == 7)
        {
            $pos1 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero-1)));
            $pos2 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero+7)));
            if($pos1 == 0)
            {
                $ret[] = $numero-1;
            }
            if($pos2 == 0)
            {
                $ret[] = $numero+7;
                
            }
        }

        if(($numero == 8)||($numero == 15))
        {
            $pos1 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero+1)));
            $pos2 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero+7)));
            $pos3 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero-7)));
            if($pos1 == 0)
            {
                $ret[] = $numero+1;
            }
            if($pos2 == 0)
            {
                $ret[] = $numero+7;
                
            }
            if($pos3 == 0)
            {
                $ret[] = $numero-7;
                
            }
        }

        if(($numero == 14)||($numero == 21))
        {
            $pos1 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero-1)));
            $pos2 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero+7)));
            $pos3 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero-7)));
            if($pos1 == 0)
            {
                $ret[] = $numero-1;
            }
            if($pos2 == 0)
            {
                $ret[] = $numero+7;
                
            }
            if($pos3 == 0)
            {
                $ret[] = $numero-7;
                
            }
        }

        if((($numero >= 9)&&($numero <= 13))||(($numero >= 16)&&($numero <= 20)))
        {
            $pos1 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero-1)));
            $pos2 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero+7)));
            $pos3 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero-7)));
            $pos4 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero+1)));
            if($pos1 == 0)
            {
                $ret[] = $numero-1;
            }
            if($pos2 == 0)
            {
                $ret[] = $numero+7;
                
            }
            if($pos3 == 0)
            {
                $ret[] = $numero-7;
                
            }
            if($pos4 == 0)
            {
                $ret[] = $numero+1;
                
            }
        }
        if($numero == 22)
        {
            $pos1 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero+1)));
            $pos2 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero-7)));
            if($pos1 == 0)
            {
                $ret[] = $numero+1;
            }
            if($pos2 == 0)
            {
                $ret[] = $numero-7;
                
            }

        }
        if($numero == 28)
        {
            $pos1 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero-1)));
            $pos2 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero-7)));
            if($pos1 == 0)
            {
                $ret[] = $numero-1;
            }
            if($pos2 == 0)
            {
                $ret[] = $numero-7;
                
            }

        }
        if(($numero >= 23)&&($numero <= 27))
        {
            $pos1 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero-1)));
            $pos2 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero-7)));
            $pos3 = intval(self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id = {$player_id} AND porte =".($numero+1)));

            if($pos1 == 0)
            {
                $ret[] = $numero-1;
            }
            if($pos2 == 0)
            {
                $ret[] = $numero-7;
                
            }
            if($pos3 == 0)
            {
                $ret[] = $numero+1;
                
            }

            
        }
        
    }

    return $ret;

}

function GainMoney($x)
{
    $player_id = $this->getActivePlayerId();
    $player_name = self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player_id}");
    $moneygainbefore = intval(self::getUniqueValueFromDB("SELECT moneygain FROM player WHERE player_id={$player_id}"));
    $moneyusebefore = intval(self::getUniqueValueFromDB("SELECT moneyuse FROM player WHERE player_id={$player_id}"));
    if($moneygainbefore + $x <=32)
    {
            self::DbQuery( "UPDATE player set moneygain = moneygain + {$x} WHERE player_id = {$player_id}" );
            
            highseason::$instance->notifyAllPlayers('gain','', array(
                'nbre' =>  $x,
                'id' =>  $player_id,
                'moneygainbefore' => $moneygainbefore,
                
                )
                );
    }

    if(($moneygainbefore + $x >32)&&($moneygainbefore<32)&&($moneyusebefore>=1))
    {
        $acompleter = 32 - $moneygainbefore;
        $surplus = $moneygainbefore + $x -32;

        self::DbQuery( "UPDATE player set moneygain = 32 WHERE player_id = {$player_id}" );
            
            highseason::$instance->notifyAllPlayers('gain','', array(
                'nbre' =>  $acompleter,
                'id' =>  $player_id,
                'moneygainbefore' => $moneygainbefore,
                
                )
                );
        if( $surplus <= $moneyusebefore)
        {
            self::DbQuery( "UPDATE player set moneyuse = moneyuse - {$surplus} WHERE player_id = {$player_id}" );
            highseason::$instance->notifyAllPlayers('reducespend','', array(
                'before' =>  $moneyusebefore,
                'id' =>  $player_id,
                'reduce' => $surplus,
                
                )
                );
        }

        if( $surplus > $moneyusebefore)
        {
            self::DbQuery( "UPDATE player set moneyuse = 0 WHERE player_id = {$player_id}" );
            highseason::$instance->notifyAllPlayers('reducespend','', array(
                'before' =>  $moneyusebefore,
                'id' =>  $player_id,
                'reduce' => $moneyusebefore,
                
                )
                );
        }
    }

    if (($moneygainbefore == 32 )&&($moneyusebefore>=1))
    {
        if( $x <= $moneyusebefore)
        {
            self::DbQuery( "UPDATE player set moneyuse = moneyuse - {$x} WHERE player_id = {$player_id}" );
            highseason::$instance->notifyAllPlayers('reducespend','', array(
                'before' =>  $moneyusebefore,
                'id' =>  $player_id,
                'reduce' => $x,
                
                )
                );
        }

        if( $x > $moneyusebefore)
        {
            self::DbQuery( "UPDATE player set moneyuse = 0 WHERE player_id = {$player_id}" );
            highseason::$instance->notifyAllPlayers('reducespend','', array(
                'before' =>  $moneyusebefore,
                'id' =>  $player_id,
                'reduce' => $moneyusebefore,
                
                )
                );
        }

    }

    if (($moneygainbefore == 32 )&&($moneyusebefore==0))
    {
        return;
    }

    /*highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains ${x} Krone(s)' ), array(
        'player_name' => $player_name,
        'x' => $x,
        
        )
        );*/

    highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains ${x} ${k}' ), array(
        'player_name' => $player_name,
        'x' => $x,
        'k' => highseason::$instance->getLogsRessource(1),
        
        )
        );

    highseason::$instance->Score();
}

function SpendMoney($x)
{
    $player_id = $this->getActivePlayerId();
    $moneyusebefore = intval(self::getUniqueValueFromDB("SELECT moneyuse FROM player WHERE player_id={$player_id}"));
            self::DbQuery( "UPDATE player set moneyuse = moneyuse +{$x} WHERE player_id = {$player_id}" );

            highseason::$instance->notifyAllPlayers('spend','', array(
                'nbre' => $x,
                'id' =>  $player_id,
                'moneyusebefore' => $moneyusebefore,
                
                )
                );
    highseason::$instance->Score();
}

function ReduceDice($d)
{
    $player_id = $this->getActivePlayerId();
    self::DbQuery("DELETE FROM dice WHERE valeur = {$d} AND id = (SELECT * FROM (SELECT MAX(id) FROM dice WHERE valeur = {$d}) AS subquery)");
    $countdice = count(self::getObjectListFromDB( "SELECT id FROM dice WHERE valeur = {$d}", true ));
    highseason::$instance->notifyAllPlayers('reducedice','', array(
        'valeur' => $d,
        'newcount' =>  $countdice,
        'position' =>  $countdice+1,
        'player' => $player_id,
        
        
        )
        );

    highseason::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1000] );
    
}

function BonusEtage($porte)
 {
    $player_id = $this->getActivePlayerId();
    $player_name = self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player_id}");
    
        
    /// LIGNES

    if(($porte == 1)||($porte == 2)||($porte == 3)||($porte == 4))
    {
        $count = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (1, 2, 3, 4)", true ));
        $ligne = intval(self::getUniqueValueFromDB("SELECT l1 FROM player WHERE player_id={$player_id}"));
        if(($count == 4)&&($ligne==0))
        {
            self::DbQuery( "UPDATE player set l1 = 1 WHERE player_id = {$player_id}" );
            self::DbQuery( "UPDATE player set l1 = 2 WHERE player_id != {$player_id}" );
            self::DbQuery( "UPDATE player set vpetage = vpetage + 2 WHERE player_id = {$player_id}" );

            highseason::$instance->notifyAllPlayers('gainbonusetage',clienttranslate( '${player_name} is the first to complete this row and wins the bonus'), array(
                'player_name' => $player_name, 
                'id' => $player_id,
                'etage' =>  1,
                
                
                )
                );

        

        }

        if(($count == 4)&&($ligne!=0))
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} completes the row but is not first (no bonus)' ), array(
                'player_name' => $player_name, 
                
                )
                );
        }
    }

    if(($porte == 8)||($porte == 9)||($porte == 10)||($porte == 11))
    {
        $count = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (8, 9, 10, 11)", true ));
        $ligne = intval(self::getUniqueValueFromDB("SELECT l2 FROM player WHERE player_id={$player_id}"));
        if(($count == 4)&&($ligne==0))
        {
            self::DbQuery( "UPDATE player set l2 = 1 WHERE player_id = {$player_id}" );
            self::DbQuery( "UPDATE player set l2 = 2 WHERE player_id != {$player_id}" );
            self::DbQuery( "UPDATE player set vpetage = vpetage + 4 WHERE player_id = {$player_id}" );

            highseason::$instance->notifyAllPlayers('gainbonusetage',clienttranslate( '${player_name} is the first to complete this row and wins the bonus' ), array(
                'player_name' => $player_name, 
                'id' => $player_id,
                'etage' =>  2,
                
                
                )
                );

                
        }
        if(($count == 4)&&($ligne!=0))
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} completes the row but is not first (no bonus)' ), array(
                'player_name' => $player_name, 
                
                )
                );
        }
    }

    if(($porte == 15)||($porte == 16)||($porte == 17)||($porte == 18))
    {
        $count = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (15, 16, 17, 18)", true ));
        $ligne = intval(self::getUniqueValueFromDB("SELECT l3 FROM player WHERE player_id={$player_id}"));
        if(($count == 4)&&($ligne==0))
        {
            self::DbQuery( "UPDATE player set l3 = 1 WHERE player_id = {$player_id}" );
            self::DbQuery( "UPDATE player set l3 = 2 WHERE player_id != {$player_id}" );
            self::DbQuery( "UPDATE player set vpetage = vpetage + 7 WHERE player_id = {$player_id}" );

            highseason::$instance->notifyAllPlayers('gainbonusetage',clienttranslate( '${player_name} is the first to complete this row and wins the bonus' ), array(
                'player_name' => $player_name, 
                'id' => $player_id,
                'etage' =>  3,
                
                
                )
                );

        }
        if(($count == 4)&&($ligne!=0))
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} completes the row but is not first (no bonus)' ), array(
                'player_name' => $player_name, 
                
                )
                );
        }
    }

    if(($porte == 22)||($porte == 23)||($porte == 24)||($porte == 25))
    {
        $count = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (22, 23, 24, 25)", true ));
        $ligne = intval(self::getUniqueValueFromDB("SELECT l4 FROM player WHERE player_id={$player_id}"));
        

        if(($count == 4)&&($ligne==0))
        {
            self::DbQuery( "UPDATE player set l4 = 1 WHERE player_id = {$player_id}" );
            self::DbQuery( "UPDATE player set l4 = 2 WHERE player_id != {$player_id}" );
            self::DbQuery( "UPDATE player set vpetage = vpetage + 10 WHERE player_id = {$player_id}" );
            highseason::$instance->notifyAllPlayers('gainbonusetage',clienttranslate( '${player_name} is the first to complete this row and wins the bonus' ), array(
                'player_name' => $player_name, 
                'id' => $player_id,
                'etage' =>  4,
                
                
                )
                );

               
        }
        if(($count == 4)&&($ligne!=0))
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} completes the row but is not first (no bonus)' ), array(
                'player_name' => $player_name, 
                
                )
                );
        }
    }

    if(($porte == 5)||($porte == 6)||($porte == 7))
    {
        $count = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (5, 6, 7)", true ));
        $ligne = intval(self::getUniqueValueFromDB("SELECT l5 FROM player WHERE player_id={$player_id}"));
        if(($count == 3)&&($ligne==0))
        {
            self::DbQuery( "UPDATE player set l5 = 1 WHERE player_id = {$player_id}" );
            self::DbQuery( "UPDATE player set l5 = 2 WHERE player_id != {$player_id}" );
            self::DbQuery( "UPDATE player set vpetage = vpetage + 1 WHERE player_id = {$player_id}" );

            highseason::$instance->notifyAllPlayers('gainbonusetage',clienttranslate( '${player_name} is the first to complete this row and wins the bonus' ), array(
                'player_name' => $player_name, 
                'id' => $player_id,
                'etage' =>  5,
                
                
                )
                );

                
        }
        if(($count == 3)&&($ligne!=0))
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} completes the row but is not first (no bonus)' ), array(
                'player_name' => $player_name, 
                
                )
                );
        }
    }

    if(($porte == 12)||($porte == 13)||($porte == 14))
    {
        $count = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (12, 13, 14)", true ));
        $ligne = intval(self::getUniqueValueFromDB("SELECT l6 FROM player WHERE player_id={$player_id}"));
        if(($count == 3)&&($ligne==0))
        {
            self::DbQuery( "UPDATE player set l6 = 1 WHERE player_id = {$player_id}" );
            self::DbQuery( "UPDATE player set l6 = 2 WHERE player_id != {$player_id}" );
            self::DbQuery( "UPDATE player set vpetage = vpetage + 2 WHERE player_id = {$player_id}" );

            highseason::$instance->notifyAllPlayers('gainbonusetage',clienttranslate( '${player_name} is the first to complete this row and wins the bonus' ), array(
                'player_name' => $player_name, 
                'id' => $player_id,
                'etage' =>  6,
                
                
                )
                );

                
        }
        if(($count == 3)&&($ligne!=0))
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} completes the row but is not first (no bonus)' ), array(
                'player_name' => $player_name, 
                
                )
                );
        }
    }

    if(($porte == 19)||($porte == 20)||($porte == 21))
    {
        $count = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (19, 20, 21)", true ));
        $ligne = intval(self::getUniqueValueFromDB("SELECT l7 FROM player WHERE player_id={$player_id}"));
        if(($count == 3)&&($ligne==0))
        {
            self::DbQuery( "UPDATE player set l7 = 1 WHERE player_id = {$player_id}" );
            self::DbQuery( "UPDATE player set l7 = 2 WHERE player_id != {$player_id}" );
            self::DbQuery( "UPDATE player set vpetage = vpetage + 4 WHERE player_id = {$player_id}" );

            highseason::$instance->notifyAllPlayers('gainbonusetage',clienttranslate( '${player_name} is the first to complete this row and wins the bonus' ), array(
                'player_name' => $player_name, 
                'id' => $player_id,
                'etage' =>  7,
                
                
                )
                );

                
        }
        if(($count == 3)&&($ligne!=0))
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} completes the row but is not first (no bonus)' ), array(
                'player_name' => $player_name, 
                
                )
                );
        }
    }

    if(($porte == 26)||($porte == 27)||($porte == 28))
    {
        $count = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (26, 27, 28)", true ));
        $ligne = intval(self::getUniqueValueFromDB("SELECT l8 FROM player WHERE player_id={$player_id}"));
        if(($count == 3)&&($ligne==0))
        {
            self::DbQuery( "UPDATE player set l8 = 1 WHERE player_id = {$player_id}" );
            self::DbQuery( "UPDATE player set l8 = 2 WHERE player_id != {$player_id}" );
            self::DbQuery( "UPDATE player set vpetage = vpetage + 7 WHERE player_id = {$player_id}" );

            highseason::$instance->notifyAllPlayers('gainbonusetage',clienttranslate( '${player_name} is the first to complete this row and wins the bonus' ), array(
                'player_name' => $player_name, 
                'id' => $player_id,
                'etage' =>  8,
                
                
                )
                );

              
        }
        if(($count == 3)&&($ligne!=0))
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} completes the row but is not first (no bonus)' ), array(
                'player_name' => $player_name, 
                
                )
                );
        }
    }

    /// COLONNES

    if(($porte == 1)||($porte == 8)||($porte == 15)||($porte == 22))
    {
        $count = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (1, 8, 15, 22)", true ));
        $colonne = intval(self::getUniqueValueFromDB("SELECT c1 FROM player WHERE player_id={$player_id}"));
        if(($count == 4)&&($colonne==0))
        {
            self::DbQuery( "UPDATE player set c1 = 1 WHERE player_id = {$player_id}" );
            self::DbQuery( "UPDATE player set c1 = 2 WHERE player_id != {$player_id}" );
            self::DbQuery( "UPDATE player set vpetage = vpetage + 8 WHERE player_id = {$player_id}" );

            highseason::$instance->notifyAllPlayers('gainbonusetage',clienttranslate( '${player_name} is the first to complete this column and wins the bonus' ), array(
                'player_name' => $player_name, 
                'id' => $player_id,
                'etage' =>  9,
                
                
                )
                );

                
        }
        if(($count == 4)&&($colonne!=0))
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} completes the column but is not first (no bonus)' ), array(
                'player_name' => $player_name, 
                
                )
                );
        }
    }

    if(($porte == 2)||($porte == 9)||($porte == 16)||($porte == 23))
    {
        $count = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (2, 9, 16, 23)", true ));
        $colonne = intval(self::getUniqueValueFromDB("SELECT c2 FROM player WHERE player_id={$player_id}"));
        if(($count == 4)&&($colonne==0))
        {
            self::DbQuery( "UPDATE player set c2 = 1 WHERE player_id = {$player_id}" );
            self::DbQuery( "UPDATE player set c2 = 2 WHERE player_id != {$player_id}" );
            self::DbQuery( "UPDATE player set vpetage = vpetage + 7 WHERE player_id = {$player_id}" );

            highseason::$instance->notifyAllPlayers('gainbonusetage',clienttranslate( '${player_name} is the first to complete this column and wins the bonus' ), array(
                'player_name' => $player_name, 
                'id' => $player_id,
                'etage' =>  10,
                
                
                )
                );

               
        }
        if(($count == 4)&&($colonne!=0))
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} completes the column but is not first (no bonus)' ), array(
                'player_name' => $player_name, 
                
                )
                );
        }
    }

    if(($porte == 3)||($porte == 10)||($porte == 17)||($porte == 24))
    {
        $count = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (3, 10, 17, 24)", true ));
        $colonne = intval(self::getUniqueValueFromDB("SELECT c3 FROM player WHERE player_id={$player_id}"));
        

        if(($count == 4)&&($colonne==0))
        {
            self::DbQuery( "UPDATE player set c3 = 1 WHERE player_id = {$player_id}" );
            self::DbQuery( "UPDATE player set c3 = 2 WHERE player_id != {$player_id}" );
            self::DbQuery( "UPDATE player set vpetage = vpetage + 6 WHERE player_id = {$player_id}" );
            highseason::$instance->notifyAllPlayers('gainbonusetage',clienttranslate( '${player_name} is the first to complete this column and wins the bonus' ), array(
                'player_name' => $player_name, 
                'id' => $player_id,
                'etage' =>  11,
                
                
                )
                );

               
        }
        if(($count == 4)&&($colonne!=0))
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} completes the column but is not first (no bonus)' ), array(
                'player_name' => $player_name, 
                
                )
                );
        }
    }

    if(($porte == 4)||($porte == 11)||($porte == 18)||($porte == 25))
    {
        $count = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (4, 11, 18, 25)", true ));
        $colonne = intval(self::getUniqueValueFromDB("SELECT c4 FROM player WHERE player_id={$player_id}"));
        if(($count == 4)&&($colonne==0))
        {
            self::DbQuery( "UPDATE player set c4 = 1 WHERE player_id = {$player_id}" );
            self::DbQuery( "UPDATE player set c4 = 2 WHERE player_id != {$player_id}" );
            self::DbQuery( "UPDATE player set vpetage = vpetage + 5 WHERE player_id = {$player_id}" );

            highseason::$instance->notifyAllPlayers('gainbonusetage',clienttranslate( '${player_name} is the first to complete this column and wins the bonus' ), array(
                'player_name' => $player_name, 
                'id' => $player_id,
                'etage' =>  12,
                
                
                )
                );

                
        }
        if(($count == 4)&&($colonne!=0))
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} completes the column but is not first (no bonus)' ), array(
                'player_name' => $player_name, 
                
                )
                );
        }
    }

    
    if(($porte == 5)||($porte == 12)||($porte == 19)||($porte == 26))
    {
        $count = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (5, 12, 19, 26)", true ));
        $colonne = intval(self::getUniqueValueFromDB("SELECT c5 FROM player WHERE player_id={$player_id}"));
        if(($count == 4)&&($colonne==0))
        {
            self::DbQuery( "UPDATE player set c5 = 1 WHERE player_id = {$player_id}" );
            self::DbQuery( "UPDATE player set c5 = 2 WHERE player_id != {$player_id}" );
            self::DbQuery( "UPDATE player set vpetage = vpetage + 6 WHERE player_id = {$player_id}" );

            highseason::$instance->notifyAllPlayers('gainbonusetage',clienttranslate( '${player_name} is the first to complete this column and wins the bonus' ), array(
                'player_name' => $player_name, 
                'id' => $player_id,
                'etage' =>  13,
                
                
                )
                );

                
        }
    }

    if(($porte == 6)||($porte == 13)||($porte == 20)||($porte == 27))
    {
        $count = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (6, 13, 20, 27)", true ));
        $colonne = intval(self::getUniqueValueFromDB("SELECT c6 FROM player WHERE player_id={$player_id}"));
        if(($count == 4)&&($colonne==0))
        {
            self::DbQuery( "UPDATE player set c6 = 1 WHERE player_id = {$player_id}" );
            self::DbQuery( "UPDATE player set c6 = 2 WHERE player_id != {$player_id}" );
            self::DbQuery( "UPDATE player set vpetage = vpetage + 7 WHERE player_id = {$player_id}" );

            highseason::$instance->notifyAllPlayers('gainbonusetage',clienttranslate( '${player_name} is the first to complete this column and wins the bonus' ), array(
                'player_name' => $player_name, 
                'id' => $player_id,
                'etage' =>  14,
                
                
                )
                );

               
        }
        if(($count == 4)&&($colonne!=0))
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} completes the column but is not first (no bonus)' ), array(
                'player_name' => $player_name, 
                
                )
                );
        }
    }

    if(($porte == 7)||($porte == 14)||($porte == 21)||($porte == 28))
    {
        $count = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (7, 14, 21, 28)", true ));
        $colonne = intval(self::getUniqueValueFromDB("SELECT c7 FROM player WHERE player_id={$player_id}"));
        if(($count == 4)&&($colonne==0))
        {
            self::DbQuery( "UPDATE player set c7 = 1 WHERE player_id = {$player_id}" );
            self::DbQuery( "UPDATE player set c7 = 2 WHERE player_id != {$player_id}" );
            self::DbQuery( "UPDATE player set vpetage = vpetage + 8 WHERE player_id = {$player_id}" );

            highseason::$instance->notifyAllPlayers('gainbonusetage',clienttranslate( '${player_name} is the first to complete this column and wins the bonus' ), array(
                'player_name' => $player_name, 
                'id' => $player_id,
                'etage' =>  15,
                
                
                )
                );

               
        }
        if(($count == 4)&&($colonne!=0))
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} completes the column but is not first (no bonus)' ), array(
                'player_name' => $player_name, 
                
                )
                );
        }
    }

    highseason::$instance->Score();

 }

 function BonusGroupe($porte)
 {
    $player_id = $this->getActivePlayerId();
    $player_name = self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player_id}");
    $hotel = self::getUniqueValueFromDB("SELECT hotel FROM player WHERE player_id={$player_id}");

    
    $groupes = $this->hotelboard['hotel'.$hotel]['groupe'];
    foreach ($groupes as $groupe)
    {
        if (in_array($porte, $groupe)) 
        {
            $compteur = 0;
            $count = count($groupe);
            foreach ($groupe as $test)
            {
                $etat = self::getUniqueValueFromDB("SELECT etat FROM hotel WHERE player_id={$player_id} AND porte = {$test}");
                if (($etat == 2)||($etat == 3))
                {
                    $compteur = $compteur +1;

                }
            }

            if($compteur == $count)
            {
                $color = self::getUniqueValueFromDB("SELECT couleur FROM hotel WHERE player_id={$player_id} AND porte = {$porte}");
                if($color ==1)
                {
                    $info = 0;
                    if ($count == 2)
                    {
                        self::DbQuery( "UPDATE player set vpgroupe = vpgroupe + 3 WHERE player_id = {$player_id}" );
                        $info = 3;
                    }

                    if ($count == 3)
                    {
                        self::DbQuery( "UPDATE player set vpgroupe = vpgroupe + 6 WHERE player_id = {$player_id}" );
                        $info = 6;
                    }

                    if ($count == 4)
                    {
                        self::DbQuery( "UPDATE player set vpgroupe = vpgroupe + 10 WHERE player_id = {$player_id}" );
                        $info = 10;
                    }
                    
                    highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} validates a blue group of value ${nbre} (+${info} VP)' ), array(
                        'player_name' => $player_name, 
                        'nbre' => $count,
                        'info' => $info,
                                                
                        )
                        );
                   
                        self::DbQuery( "UPDATE player set compteurgroupe = compteurgroupe + 1 WHERE player_id = {$player_id}" );
                   
                }

                if($color ==2)
                {
                    $info = 0;

                    if ($count == 2)
                    {
                        highseason::$instance->GainMoney(1);
                        $info = 1;
                    }

                    if ($count == 3)
                    {
                        highseason::$instance->GainMoney(3);
                        $info = 3;
                    }

                    if ($count == 4)
                    {
                        highseason::$instance->GainMoney(5);
                        $info = 5;
                    }

                    highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} validates a red group of value ${nbre} (+${info} Krones)' ), array(
                        'player_name' => $player_name, 
                        'nbre' => $count,
                        'info' => $info,
                                                
                        )
                        );

                        self::DbQuery( "UPDATE player set compteurgroupe = compteurgroupe + 1 WHERE player_id = {$player_id}" );
                }

                if($color ==3)
                {
                    $info = 0;
                    if ($count == 2)
                    {
                        highseason::$instance->GainEmperor(1);
                        $info = 1;
                    }

                    if ($count == 3)
                    {
                        highseason::$instance->GainEmperor(3);
                        $info = 3;
                    }

                    if ($count == 4)
                    {
                        highseason::$instance->GainEmperor(5);
                        $info = 5;
                    }

                    highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} validates a yellow group of value ${nbre} (+${info} on Emperor\'s track)' ), array(
                        'player_name' => $player_name, 
                        'nbre' => $count,
                        'info' => $info,
                                                
                        )
                        );
                    
                        self::DbQuery( "UPDATE player set compteurgroupe = compteurgroupe + 1 WHERE player_id = {$player_id}" );
                }
                
            }
           
        }
    }

    highseason::$instance->Score();

 }

 function GainEmperor($g)
 {
    $round = highseason::$instance->getGameStateValue('round');
    $player_id = $this->getActivePlayerId();
    $player_name = self::getUniqueValueFromDB("SELECT player_name FROM player WHERE player_id={$player_id}");
    $track1 = self::getUniqueValueFromDB("SELECT emperor1 FROM player WHERE player_id={$player_id}");
    $track2 = self::getUniqueValueFromDB("SELECT emperor2 FROM player WHERE player_id={$player_id}");
    $track3 = self::getUniqueValueFromDB("SELECT emperor3 FROM player WHERE player_id={$player_id}");
    $point = 0;
    $listeplayers = self::getObjectListFromDB( "SELECT player_id FROM player", true );

    /*highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} advances by ${point} on the Emperor\'s track'), array(
        'player_name' => $player_name, 
        'point' => $g,
        
        )
        );*/

    highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains ${point} ${e}' ), array(
        'player_name' => $player_name,
        'point' => $g,
        'e' => highseason::$instance->getLogsRessource(2),
        
        )
        );



    if(($round >=1)&&($round <=3))
    {
        if($track1+$g<=5)
        {
            self::DbQuery( "UPDATE player set emperor1 = emperor1 + {$g} WHERE player_id = {$player_id}" );
        }

        if($track1+$g>=5)
        {
            $bonus1 = self::getUniqueValueFromDB("SELECT bonusemperor1 FROM player WHERE player_id={$player_id}");
            if ($bonus1 == 0)
            {
                self::DbQuery( "UPDATE player set bonusemperor1 = 1 WHERE player_id = {$player_id}" );
                self::DbQuery( "UPDATE player set bonusemperor1 = 2 WHERE player_id != {$player_id}" );
                self::DbQuery( "UPDATE player set vpemperor = vpemperor + 2 WHERE player_id = {$player_id}" );

                foreach ($listeplayers as $id)
                {
                    $etat = self::getUniqueValueFromDB("SELECT bonusemperor1 FROM player WHERE player_id={$id}");
                    highseason::$instance->notifyAllPlayers('gainbonusemperor','', array(
                        'id' => $id,
                        'bonus' => 1,
                        'etat' => $etat,
                        
                        
                        )
                        );
                }

                highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains 2 vp (Emperor track bonus)' ), array(
                    'player_name' => $player_name, 
                    
                    )
                    );
            }

        }
        if($track1+$g>5)
        {
            $diff = 5 - $track1;
            $surplus = $g - $diff;
            self::DbQuery( "UPDATE player set emperor1 = 5 WHERE player_id = {$player_id}" );

            if($track2+$surplus<=6)
            {
                self::DbQuery( "UPDATE player set emperor2 = emperor2 + {$surplus} WHERE player_id = {$player_id}" );
            }

            if($track2+$surplus>=6)
            {
                $bonus2 = self::getUniqueValueFromDB("SELECT bonusemperor2 FROM player WHERE player_id={$player_id}");
                if ($bonus2 == 0)
                {
                    self::DbQuery( "UPDATE player set bonusemperor2 = 1 WHERE player_id = {$player_id}" );
                    self::DbQuery( "UPDATE player set bonusemperor2 = 2 WHERE player_id != {$player_id}" );
                    self::DbQuery( "UPDATE player set vpemperor = vpemperor + 3 WHERE player_id = {$player_id}" );

                foreach ($listeplayers as $id)
                {
                    $etat = self::getUniqueValueFromDB("SELECT bonusemperor2 FROM player WHERE player_id={$id}");
                    highseason::$instance->notifyAllPlayers('gainbonusemperor','', array(
                        'id' => $id,
                        'bonus' => 2,
                        'etat' => $etat,
                        
                        
                        )
                        );
                }

                highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains 3 vp (Emperor track bonus)' ), array(
                    'player_name' => $player_name, 
                    
                    )
                    );
                }
    
            }

            if($track2+$surplus>6)
            {
                $diff2 = 6 - $track2;
                $surplus2 = $surplus - $diff2;
                self::DbQuery( "UPDATE player set emperor2 = 6 WHERE player_id = {$player_id}" );

                if($track3+$surplus2>=7)
                {
                    $bonus3 = self::getUniqueValueFromDB("SELECT bonusemperor3 FROM player WHERE player_id={$player_id}");
                    if ($bonus3 == 0)
                    {
                        self::DbQuery( "UPDATE player set bonusemperor3 = 1 WHERE player_id = {$player_id}" );
                        self::DbQuery( "UPDATE player set bonusemperor3 = 2 WHERE player_id != {$player_id}" );
                        self::DbQuery( "UPDATE player set vpemperor = vpemperor + 4 WHERE player_id = {$player_id}" );

                    foreach ($listeplayers as $id)
                    {
                        $etat = self::getUniqueValueFromDB("SELECT bonusemperor3 FROM player WHERE player_id={$id}");
                        highseason::$instance->notifyAllPlayers('gainbonusemperor','', array(
                            'id' => $id,
                            'bonus' => 3,
                            'etat' => $etat,
                            
                            
                            )
                            );
                    }

                    highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains 4 vp (Emperor track bonus)' ), array(
                    'player_name' => $player_name, 
                    
                        )
                        );

                    }
        
                }
                
                if(($track3+$surplus2 > 7)&&($track3 < 17))
                {
                    if ($track3<7)
                    {
                        $point = $surplus2 - (7- $track3);
                    }

                    if (($track3>=7)&&($track3+$surplus2 <= 17))
                    {
                        $point = $surplus2;
                    }

                    if (($track3>=7)&&($track3+$surplus2 > 17))
                    {
                        $point = 17 - $track3;
                    }

                    self::DbQuery( "UPDATE player set vpemperor = vpemperor + {$point} WHERE player_id = {$player_id}" );
                }


                
                if($track3+$surplus2<=17)
                {
                    self::DbQuery( "UPDATE player set emperor3 = emperor3 + {$surplus2} WHERE player_id = {$player_id}" );
                }

                if($track3+$surplus2>17)
                {
                    self::DbQuery( "UPDATE player set emperor3 = 17 WHERE player_id = {$player_id}" );

                }
            }

        }
    }

    if(($round >=4)&&($round <=5))
    {
        if($track2+$g<=6)
        {
            self::DbQuery( "UPDATE player set emperor2 = emperor2 + {$g} WHERE player_id = {$player_id}" );
        }
        if($track2+$g>=6)
        {
            $bonus2 = self::getUniqueValueFromDB("SELECT bonusemperor2 FROM player WHERE player_id={$player_id}");
            if ($bonus2 == 0)
            {
                self::DbQuery( "UPDATE player set bonusemperor2 = 1 WHERE player_id = {$player_id}" );
                self::DbQuery( "UPDATE player set bonusemperor2 = 2 WHERE player_id != {$player_id}" );
                self::DbQuery( "UPDATE player set vpemperor = vpemperor + 3 WHERE player_id = {$player_id}" );

                foreach ($listeplayers as $id)
                {
                    $etat = self::getUniqueValueFromDB("SELECT bonusemperor2 FROM player WHERE player_id={$id}");
                    highseason::$instance->notifyAllPlayers('gainbonusemperor','', array(
                        'id' => $id,
                        'bonus' => 2,
                        'etat' => $etat,
                        
                        
                        )
                        );
                }

                highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains 3 vp (Emperor track bonus)' ), array(
                    'player_name' => $player_name, 
                    
                    )
                    );
            }

        }
        if($track2+$g>6)
        {
            $diff = 6 - $track2;
            $surplus = $g - $diff;
            self::DbQuery( "UPDATE player set emperor2 = 6 WHERE player_id = {$player_id}" );
            
            if($track3+$surplus<=17)
            {
                self::DbQuery( "UPDATE player set emperor3 = emperor3 + {$surplus} WHERE player_id = {$player_id}" );
            }

            if($track3+$surplus>17)
            {
                self::DbQuery( "UPDATE player set emperor3 = 17 WHERE player_id = {$player_id}" );
                
            }

            if($track3+$surplus>=7)
            {
                $bonus3 = self::getUniqueValueFromDB("SELECT bonusemperor3 FROM player WHERE player_id={$player_id}");
                if ($bonus3 == 0)
                {
                    self::DbQuery( "UPDATE player set bonusemperor3 = 1 WHERE player_id = {$player_id}" );
                    self::DbQuery( "UPDATE player set bonusemperor3 = 2 WHERE player_id != {$player_id}" );
                    self::DbQuery( "UPDATE player set vpemperor = vpemperor + 4 WHERE player_id = {$player_id}" );

                foreach ($listeplayers as $id)
                {
                    $etat = self::getUniqueValueFromDB("SELECT bonusemperor3 FROM player WHERE player_id={$id}");
                    highseason::$instance->notifyAllPlayers('gainbonusemperor','', array(
                        'id' => $id,
                        'bonus' => 3,
                        'etat' => $etat,
                        
                        
                        )
                        );
                }

                highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains 4 vp (Emperor track bonus)' ), array(
                    'player_name' => $player_name, 
                    
                    )
                    );
                }

            }

            if(($track3+$surplus > 7)&&($track3 < 17))
                {
                    if ($track3<7)
                    {
                        $point = $surplus - (7- $track3);
                    }

                    if (($track3>=7)&&($track3+$surplus <= 17))
                    {
                        $point = $surplus;
                    }

                    if (($track3>=7)&&($track3+$surplus > 17))
                    {
                        $point = 17 - $track3;
                    }

                    self::DbQuery( "UPDATE player set vpemperor = vpemperor + {$point} WHERE player_id = {$player_id}" );
                }

        }
        
    }

    if(($round >=6)&&($round <=7))
    {
        if($track3+$g<=17)
            {
                self::DbQuery( "UPDATE player set emperor3 = emperor3 + {$g} WHERE player_id = {$player_id}" );
            }

            if($track3+$g>17)
            {
                self::DbQuery( "UPDATE player set emperor3 = 17 WHERE player_id = {$player_id}" );
                
            }
        if($track3+$g>=7)
        {
            $bonus3 = self::getUniqueValueFromDB("SELECT bonusemperor3 FROM player WHERE player_id={$player_id}");
            if ($bonus3 == 0)
            {
                self::DbQuery( "UPDATE player set bonusemperor3 = 1 WHERE player_id = {$player_id}" );
                self::DbQuery( "UPDATE player set bonusemperor3 = 2 WHERE player_id != {$player_id}" );
                self::DbQuery( "UPDATE player set vpemperor = vpemperor + 4 WHERE player_id = {$player_id}" );

                foreach ($listeplayers as $id)
                {
                    $etat = self::getUniqueValueFromDB("SELECT bonusemperor3 FROM player WHERE player_id={$id}");
                    highseason::$instance->notifyAllPlayers('gainbonusemperor','', array(
                        'id' => $id,
                        'bonus' => 3,
                        'etat' => $etat,
                        
                        
                        )
                        );
                }

                highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} gains 4 vp (Emperor track bonus)' ), array(
                    'player_name' => $player_name, 
                    
                    )
                    );
                
            }

        }

        if(($track3+$g > 7)&&($track3 < 17))
                {
                    if ($track3<7)
                    {
                        $point = $g - (7- $track3);
                    }

                    if (($track3>=7)&&($track3+$g <= 17))
                    {
                        $point = $g;
                    }

                    if (($track3>=7)&&($track3+$g > 17))
                    {
                        $point = 17 - $track3;
                    }

                    self::DbQuery( "UPDATE player set vpemperor = vpemperor + {$point} WHERE player_id = {$player_id}" );
                }
        
    }

    $track1after = self::getUniqueValueFromDB("SELECT emperor1 FROM player WHERE player_id={$player_id}");
    $track2after = self::getUniqueValueFromDB("SELECT emperor2 FROM player WHERE player_id={$player_id}");
    $track3after = self::getUniqueValueFromDB("SELECT emperor3 FROM player WHERE player_id={$player_id}");

    highseason::$instance->notifyAllPlayers('gainemperor','', array(
         
        'id' => $player_id,
        'before1' => $track1,
        'after1' => $track1after,
        'before2' => $track2,
        'after2' => $track2after,
        'before3' => $track3,
        'after3' => $track3after,
        
        
        
        )
        );

    if (($track1<=2)&&($track1after>=3))
    {
        $countemperor = count(self::getObjectListFromDB( "SELECT id FROM multiaction WHERE type = 'emperor'", true ));
        self::DbQuery( "INSERT INTO multiaction (type, action, arg, ordre) VALUES ('emperor', 'Bonus', 1, {$countemperor})");
    }

    if (($track1<=4)&&($track1after>=5))
    {
        $countemperor = count(self::getObjectListFromDB( "SELECT id FROM multiaction WHERE type = 'emperor'", true ));
        self::DbQuery( "INSERT INTO multiaction (type, action, arg, ordre) VALUES ('emperor', 'Bonus', 2, {$countemperor})");
    }

    if (($track2<=1)&&($track2after>=2))
    {
        $countemperor = count(self::getObjectListFromDB( "SELECT id FROM multiaction WHERE type = 'emperor'", true ));
        self::DbQuery( "INSERT INTO multiaction (type, action, arg, ordre) VALUES ('emperor', 'Bonus', 3, {$countemperor})");
    }

    if (($track2<=5)&&($track2after>=6))
    {
        $countemperor = count(self::getObjectListFromDB( "SELECT id FROM multiaction WHERE type = 'emperor'", true ));
        self::DbQuery( "INSERT INTO multiaction (type, action, arg, ordre) VALUES ('emperor', 'Bonus', 4, {$countemperor})");
    }

    if (($track3<=1)&&($track3after>=2))
    {
        $countemperor = count(self::getObjectListFromDB( "SELECT id FROM multiaction WHERE type = 'emperor'", true ));
        self::DbQuery( "INSERT INTO multiaction (type, action, arg, ordre) VALUES ('emperor', 'Bonus', 5, {$countemperor})");
    }

    if (($track3<=3)&&($track3after>=4))
    {
        $countemperor = count(self::getObjectListFromDB( "SELECT id FROM multiaction WHERE type = 'emperor'", true ));
        self::DbQuery( "INSERT INTO multiaction (type, action, arg, ordre) VALUES ('emperor', 'Bonus', 6, {$countemperor})");
    }

    if (($track3<=6)&&($track3after>=7))
    {
        $countemperor = count(self::getObjectListFromDB( "SELECT id FROM multiaction WHERE type = 'emperor'", true ));
        self::DbQuery( "INSERT INTO multiaction (type, action, arg, ordre) VALUES ('emperor', 'Bonus', 7, {$countemperor})");
    }



    highseason::$instance->Score();

 }

 function Score()
 {
    $players = self::getObjectListFromDB( "SELECT player_id FROM player", true );

    // calcul score staff total//

    foreach ($players as $player_id)
    {
        
    self::DbQuery( "UPDATE player set vpstafftotal = 0 WHERE player_id = {$player_id}" );

    $perm1 = intval(self::getUniqueValueFromDB("SELECT permstaff1 FROM player WHERE player_id={$player_id}"));
    $perm2 = intval(self::getUniqueValueFromDB("SELECT permstaff2 FROM player WHERE player_id={$player_id}"));

    //5
    if (($perm1==5)||($perm2==5))
    {
    $nbre = count(self::getObjectListFromDB( "SELECT id FROM hotel WHERE player_id = {$player_id} AND (etat =2 OR etat =3) AND couleur = 1", true )) *2;
    self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + {$nbre} WHERE player_id = {$player_id}" );
    }

    //6

    if (($perm1==6)||($perm2==6))
    {
    $nbre = count(self::getObjectListFromDB( "SELECT id FROM hotel WHERE player_id = {$player_id} AND (niveau = 3 OR niveau = 4) AND etat = 2", true )) *2;
    self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + {$nbre} WHERE player_id = {$player_id}" );
    }


    //11

    if (($perm1==11)||($perm2==11))
    {
    $nbre = count(self::getObjectListFromDB( "SELECT id FROM hotel WHERE player_id = {$player_id} AND (etat =2 OR etat =3) AND couleur = 3", true )) *2;
    self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + {$nbre} WHERE player_id = {$player_id}" );
    }

    //12

    if (($perm1==12)||($perm2==12))
    {
        $countl1 = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (1, 2, 3, 4)", true ));
        //$l1 =intval(self::getUniqueValueFromDB("SELECT l1 FROM player WHERE player_id={$player_id}"));
        if ($countl1 == 4)
        {
            self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + 4 WHERE player_id = {$player_id}" );
        }
        $countl2 = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (8, 9, 10, 11)", true ));
        //$l2 =intval(self::getUniqueValueFromDB("SELECT l2 FROM player WHERE player_id={$player_id}"));
        if ($countl2 == 4)
        {
            self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + 4 WHERE player_id = {$player_id}" );
        }
        $countl3 = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (15, 16, 17, 18)", true ));
        //$l3 =intval(self::getUniqueValueFromDB("SELECT l3 FROM player WHERE player_id={$player_id}"));
        if ($countl3 == 4)
        {
            self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + 4 WHERE player_id = {$player_id}" );
        }
        $countl4 = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (22, 23, 24, 25)", true ));
        //$l4 =intval(self::getUniqueValueFromDB("SELECT l4 FROM player WHERE player_id={$player_id}"));
        if ($countl4 == 4)
        {
            self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + 4 WHERE player_id = {$player_id}" );
        }
        $countl5 = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (5, 6, 7)", true ));
        //$l5 =intval(self::getUniqueValueFromDB("SELECT l5 FROM player WHERE player_id={$player_id}"));
        if ($countl5 == 3)
        {
            self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + 3 WHERE player_id = {$player_id}" );
        }
        $countl6 = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (12, 13, 14)", true ));
        //$l6 =intval(self::getUniqueValueFromDB("SELECT l6 FROM player WHERE player_id={$player_id}"));
        if ($countl6 == 3)
        {
            self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + 3 WHERE player_id = {$player_id}" );
        }
        $countl7 = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (19, 20, 21)", true ));
        //$l7 =intval(self::getUniqueValueFromDB("SELECT l7 FROM player WHERE player_id={$player_id}"));
        if ($countl7 == 3)
        {
            self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + 3 WHERE player_id = {$player_id}" );
        }
        $countl8 = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (26, 27, 28)", true ));
        //$l8 =intval(self::getUniqueValueFromDB("SELECT l8 FROM player WHERE player_id={$player_id}"));
        if ($countl8 == 3)
        {
            self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + 3 WHERE player_id = {$player_id}" );
        }

    }

    //16 

    if (($perm1==16)||($perm2==16))
    {
        $countc1 = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (1, 8, 15, 22)", true ));
        //$c1 =intval(self::getUniqueValueFromDB("SELECT c1 FROM player WHERE player_id={$player_id}"));
        if ($countc1 == 4)
        {
            self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + 5 WHERE player_id = {$player_id}" );
        }
        $countc2 = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (2, 9, 16, 23)", true ));
        //$c2 =intval(self::getUniqueValueFromDB("SELECT c2 FROM player WHERE player_id={$player_id}"));
        if ($countc2 == 4)
        {
            self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + 5 WHERE player_id = {$player_id}" );
        }
        $countc3 = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (3, 10, 17, 24)", true ));
        //$c3 =intval(self::getUniqueValueFromDB("SELECT c3 FROM player WHERE player_id={$player_id}"));
        if ($countc3 == 4)
        {
            self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + 5 WHERE player_id = {$player_id}" );
        }
        $countc4 = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (4, 11, 18, 25)", true ));
        //$c4 =intval(self::getUniqueValueFromDB("SELECT c4 FROM player WHERE player_id={$player_id}"));
        if ($countc4 == 4)
        {
            self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + 5 WHERE player_id = {$player_id}" );
        }
        $countc5 = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (5, 12, 19, 26)", true ));
        //$c5 =intval(self::getUniqueValueFromDB("SELECT c5 FROM player WHERE player_id={$player_id}"));
        if ($countc5 == 4)
        {
            self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + 5 WHERE player_id = {$player_id}" );
        }
        $countc6 = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (6, 13, 20, 27)", true ));
        //$c6 =intval(self::getUniqueValueFromDB("SELECT c6 FROM player WHERE player_id={$player_id}"));
        if ($countc6 == 4)
        {
            self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + 5 WHERE player_id = {$player_id}" );
        }
        $countc7 = count(self::getObjectListFromDB( "SELECT etat FROM hotel WHERE player_id = {$player_id} AND etat IN (2,3) AND porte IN (7, 14, 21, 28)", true ));
        //$c7 =intval(self::getUniqueValueFromDB("SELECT c7 FROM player WHERE player_id={$player_id}"));
        if ($countc7 == 4)
        {
            self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + 5 WHERE player_id = {$player_id}" );
        }
        
        
    }

    //17
    if (($perm1==17)||($perm2==17))
    {
    $nbre = count(self::getObjectListFromDB( "SELECT id FROM staff WHERE player_id = {$player_id} AND etat = 1", true )) *2;
    self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + {$nbre} WHERE player_id = {$player_id}" );
    }

    //22

    if (($perm1==22)||($perm2==22))
    {
        $nbre1 = count(self::getObjectListFromDB( "SELECT id FROM hotel WHERE player_id = {$player_id} AND (etat =2 OR etat =3) AND couleur = 1", true ));
        $nbre2 = count(self::getObjectListFromDB( "SELECT id FROM hotel WHERE player_id = {$player_id} AND (etat =2 OR etat =3) AND couleur = 2", true ));
        $nbre3 = count(self::getObjectListFromDB( "SELECT id FROM hotel WHERE player_id = {$player_id} AND (etat =2 OR etat =3) AND couleur = 3", true ));
        $tableau= [$nbre1, $nbre2, $nbre3];
        $minNumber = min($tableau);
        $nbre = $minNumber *3;
        self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + {$nbre} WHERE player_id = {$player_id}" );
    }


    //23 
    if (($perm1==23)||($perm2==23))
    {
        $compteur = intval(self::getUniqueValueFromDB("SELECT compteurgroupe FROM player WHERE player_id={$player_id}"));
        $nbre = $compteur*2;
        self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + {$nbre} WHERE player_id = {$player_id}" );
    }


    //26

    if (($perm1==26)||($perm2==26))
    {
    $nbre = count(self::getObjectListFromDB( "SELECT id FROM hotel WHERE player_id = {$player_id} AND (etat =2 OR etat =3) AND couleur = 2", true )) *2;
    self::DbQuery( "UPDATE player set vpstafftotal = vpstafftotal + {$nbre} WHERE player_id = {$player_id}" );
    }

    
    }


    ////////////////////////////////

    foreach ($players as $player_id)
    {
        $score1 = self::getUniqueValueFromDB("SELECT vpstafftotal FROM player WHERE player_id={$player_id}");
        $score2 = self::getUniqueValueFromDB("SELECT vpgroupe FROM player WHERE player_id={$player_id}");
        $score3 = self::getUniqueValueFromDB("SELECT vpligne4 FROM player WHERE player_id={$player_id}");
        $score4 = self::getUniqueValueFromDB("SELECT vpligne3 FROM player WHERE player_id={$player_id}");
        $score5 = self::getUniqueValueFromDB("SELECT vpligne2 FROM player WHERE player_id={$player_id}");
        $score6 = self::getUniqueValueFromDB("SELECT vpligne1 FROM player WHERE player_id={$player_id}");
        $score7 = self::getUniqueValueFromDB("SELECT vpemperor FROM player WHERE player_id={$player_id}");
        $score9 = self::getUniqueValueFromDB("SELECT vpetage FROM player WHERE player_id={$player_id}");

        $moneygain = intval(self::getUniqueValueFromDB("SELECT moneygain FROM player WHERE player_id={$player_id}"));
        $moneyuse = intval(self::getUniqueValueFromDB("SELECT moneyuse FROM player WHERE player_id={$player_id}"));
        $score8 = floor(($moneygain - $moneyuse) / 3);
        
       $credit = self::getUniqueValueFromDB("SELECT credituse FROM player WHERE player_id={$player_id}");
       $score10 =0;
       if ($credit==1)
       {
            $score10 = -2;
       }
       if ($credit==2)
       {
            $score10 = -5;
       }
       if ($credit==3)
       {
            $score10 = -9;
       }

       $score = $score1 + $score2 + $score3 + $score4 + $score5 + $score6 + $score7 + $score8 + $score9 + $score10;

       
       self::DbQuery( "UPDATE player set player_score = {$score} WHERE player_id = {$player_id}" );
       $scorejoueur = self::getUniqueValueFromDB("SELECT player_score FROM player WHERE player_id={$player_id}");

       highseason::$instance->notifyAllPlayers('majscore','', array(
        'id' => $player_id,
        'score1' => $score1,
        'score2' => $score2,
        'score3' => $score3,
        'score4' => $score4,
        'score5' => $score5,
        'score6' => $score6,
        'score7' => $score7,
        'score8' => $score8,
        'score9' => $score9,
        'score10' => $score10,
        'scoretotal' => $scorejoueur,
                                
        )
        );


    }
 }


///////////////////////////////////////////////////////////////////////////////// 
//     _____  _                                    _   _                 
//    |  __ \| |                                  | | (_)                
//    | |__) | | __ _ _   _  ___ _ __    __ _  ___| |_ _  ___  _ __  ___ 
//    |  ___/| |/ _` | | | |/ _ \ '__|  / _` |/ __| __| |/ _ \| '_ \/ __|
//    | |    | | (_| | |_| |  __/ |    | (_| | (__| |_| | (_) | | | \__ \
//    |_|    |_|\__,_|\__, |\___|_|     \__,_|\___|\__|_|\___/|_| |_|___/
//                     __/ |                                             
//                    |___/                                              
/////////////////////////////////////////////////////////////////////////////////    


function actSelect($arg1)
{
   
    self::checkAction( 'actSelect' );        
      
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    $this->callPending($pending, true, $arg1);
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->giveExtraTime(self::getActivePlayerId());
    $this->gamestate->nextState( 'next');
    
}

function actButton($arg1)
{
   
    self::checkAction( 'actSelect' );        
      
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    $this->callPending($pending, true, $arg1);
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->giveExtraTime(self::getActivePlayerId());
    $this->gamestate->nextState( 'next');
    
}

function actUndo()
{
   
    self::checkAction( 'actSelect' );   
    
         
    $this->undoRestorePoint();
    
     
    highseason::$instance->Score();
    
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    self::DbQuery("delete from pending where id=".$pending['id']);
    $this->addPending ($pending['player_id'], "TakeAction");
    
    $this->gamestate->nextState( 'next');
    
}


///////////////////////////////////////////////////////////////////////////////// 
//     _____                             _        _                                                    _       
//    / ____|                           | |      | |                                                  | |      
//    | |  __  __ _ _ __ ___   ___   ___| |_ __ _| |_ ___    __ _ _ __ __ _ _   _ _ __ ___   ___ _ __ | |_ ___ 
//    | | |_ |/ _` | '_ ` _ \ / _ \ / __| __/ _` | __/ _ \  / _` | '__/ _` | | | | '_ ` _ \ / _ \ '_ \| __/ __|
//    | |__| | (_| | | | | | |  __/ \__ \ || (_| | ||  __/ | (_| | | | (_| | |_| | | | | | |  __/ | | | |_\__ \
//     \_____|\__,_|_| |_| |_|\___| |___/\__\__,_|\__\___|  \__,_|_|  \__, |\__,_|_| |_| |_|\___|_| |_|\__|___/
//                                                                    __/ |                                   
//                                                                   |___/                                    
///////////////////////////////////////////////////////////////////////////////// 

function argPlayerTurn()
{
    $pending =  self::getObjectFromDB( "SELECT* FROM pending order by id desc limit 1");
    $arg = $this->callPending($pending, false);
    
    return $arg;
}

///////////////////////////////////////////////////////////////////////////////// 
//      _____                            _        _                    _   _                 
//     / ____|                          | |      | |                  | | (_)                
//    | |  __  __ _ _ __ ___   ___   ___| |_ __ _| |_ ___    __ _  ___| |_ _  ___  _ __  ___ 
//    | | |_ |/ _` | '_ ` _ \ / _ \ / __| __/ _` | __/ _ \  / _` |/ __| __| |/ _ \| '_ \/ __|
//    | |__| | (_| | | | | | |  __/ \__ \ || (_| | ||  __/ | (_| | (__| |_| | (_) | | | \__ \
//     \_____|\__,_|_| |_| |_|\___| |___/\__\__,_|\__\___|  \__,_|\___|\__|_|\___/|_| |_|___/
//                                                                                       
/////////////////////////////////////////////////////////////////////////////////                                                                                       

 
function callPending($pending, $execute, $arg1 = null, $arg2 = null)
{
   
    if(class_exists($pending['function'])){
        $obj = new $pending['function']();
        $obj->player_id = $this->getActivePlayerId();
        if($pending['player_id'] != null)
        {
            $obj->player_id = $pending['player_id'];
        }
        $obj->player = new Pending($obj->player_id);
        
        $method = "";
        if($pending['target'] != null)
        {
            $method = $pending['target'];
        }
        if(!$execute)
        {
            $name = "arg".$method;
        }
        else
        {
            $name = $method;
        }
        $ret = $obj->$name($pending['arg'], $pending['arg2'], $arg1, $arg2);
    }
    else
    {
        $obj = $this;
        if($pending['player_id'] != null)
        {
            $obj = new Pending($pending['player_id']);
        }
        
        $fname ="";
        if(!$execute)
        {
            $fname .= "arg";
        }
        $fname .= $pending['function'];
        
        $ret = null;
        if(method_exists($obj, $fname))
        {
            $ret = $obj->$fname($pending['arg'], $pending['arg2'], $arg1, $arg2);
        }
    }
    return $ret;
}


function stPending() {
   
   $pending =  self::getObjectFromDB( "SELECT * FROM pending order by id desc limit 1");
   if($pending == null)
   {
        //$this->endGame();
        $this->gamestate->nextState( 'end' ); 
   }
   else
   {
       $args = $this->callPending($pending, false);
              
       if($args == null || (count($args['selectable']) == 0 && count($args['buttons']) == 0))
       {
           //no args required, execute
           $this->callPending($pending, true);
           self::DbQuery("delete from pending where id=".$pending['id']);
           $this->gamestate->nextState( 'same' );  
       }
       /*else if(count($args['selectable']) + count($args['buttons']) == 1)
       {
           //AUTO PLAY IF ONLY ONE CHOICE
           foreach($args['selectable'] as $arg1 => $argnul)
           {
               $this->callPending($pending, true, $arg1);
           }
           foreach($args['buttons'] as $arg1 => $argnul)
           {
               $this->callPending($pending, true, $arg1);
           }
           self::DbQuery("delete from pending where id=".$pending['id']);
           $this->gamestate->nextState( 'same' );  
       }*/
       else
       {
          
           $this->gamestate->changeActivePlayer( $pending['player_id']);
           
           //player input required
           $this->gamestate->nextState( 'player' ); 
       }            
   }
   
}


/////////////////////////////////////////////////////////////////////////////////
//    ______               _     _      
//   |___  /              | |   (_)     
//      / / ___  _ __ ___ | |__  _  ___ 
//     / / / _ \| '_ ` _ \| '_ \| |/ _ \
//    / /_| (_) | | | | | | |_) | |  __/
//   /_____\___/|_| |_| |_|_.__/|_|\___|
//                                   
/////////////////////////////////////////////////////////////////////////////////                                   


    function zombieTurn( $state, $active_player )
    {
    	$statename = $state['name'];
    	
        if ($state['type'] === "activeplayer") {
            switch ($statename) {
                default:
                    $player_id = $this->getActivePlayerId();
    	            self::DbQuery("delete from pending where player_id = {$player_id}");
                    $this->gamestate->nextState( "zombiePass" );
                	break;
            }

            return;
        }

        if ($state['type'] === "multipleactiveplayer") {
            // Make sure player is in a non blocking status for role turn
            $this->gamestate->setPlayerNonMultiactive( $active_player, '' );
            
            return;
        }

        throw new feException( "Zombie mode not supported at this game state: ".$statename );
    }
   
///////////////////////////////////////////////////////////////////////////////// 
//     _____  ____                                    _      
//    |  __ \|  _ \                                  | |     
//    | |  | | |_) |  _   _ _ __   __ _ _ __ __ _  __| | ___ 
//    | |  | |  _ <  | | | | '_ \ / _` | '__/ _` |/ _` |/ _ \
//    | |__| | |_) | | |_| | |_) | (_| | | | (_| | (_| |  __/
//    |_____/|____/   \__,_| .__/ \__, |_|  \__,_|\__,_|\___|
//                         | |     __/ |                     
//                         |_|    |___/                      
/////////////////////////////////////////////////////////////////////////////////    

    
    function upgradeTableDb( $from_version )
    {
        // $from_version is the current version of this game database, in numerical form.
        // For example, if the game was running with a release of your game named "140430-1345",
        // $from_version is equal to 1404301345
        
        // Example:
//        if( $from_version <= 1404301345 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "ALTER TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        if( $from_version <= 1405061421 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "CREATE TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        // Please add your future database scheme changes here
//
//


    }    
}
