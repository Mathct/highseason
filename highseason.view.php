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
 * highseason.view.php
 *
 * This is your "view" file.
 *
 * The method "build_page" below is called each time the game interface is displayed to a player, ie:
 * _ when the game starts
 * _ when a player refreshes the game page (F5)
 *
 * "build_page" method allows you to dynamically modify the HTML generated for the game interface. In
 * particular, you can set here the values of variables elements defined in highseason_highseason.tpl (elements
 * like {MY_VARIABLE_ELEMENT}), and insert HTML block elements (also defined in your HTML template file)
 *
 * Note: if the HTML of your game interface is always the same, you don't have to place anything here.
 *
 */
  
require_once( APP_BASE_PATH."view/common/game.view.php" );
  
class view_highseason_highseason extends game_view
{
    protected function getGameName()
    {
        // Used for translations and stuff. Please do not modify.
        return "highseason";
    }
    
  	function build_page( $viewArgs )
  	{		
  	    // Get players & players number
          global $g_user;
          $current_player_id = $g_user->get_id(); // id current player
          $spectator = $this->game->isSpectator();  // true ou false
          $players = $this->game->loadPlayersBasicInfos();
          
          $players_nbr = count( $players );
          $template = self::getGameName() . "_" . self::getGameName();
    
          $player_ordre = $this->game->getPlayerRelativePositions();

          

        /*********** Place your code below:  ************/

        $this->page->begin_block($template, "player");

        $this->page->insert_block("player", array (
        "PLAYER_ID" => $player_ordre[0],
        "PLAYER_NAME" => $players [$player_ordre[0]] ['player_name'],
        "COLOR" => $players [$player_ordre[0]]['player_color'],
        
        
        ));


        for( $i=1; $i<$players_nbr; $i++) 
      {
          
          $this->page->insert_block("player", array (
          "PLAYER_ID" => $player_ordre[$i],
          "PLAYER_NAME" => $players [$player_ordre[$i]] ['player_name'],
          "COLOR" => $players [$player_ordre[$i]]['player_color'],
          
          
          ));
           
      }

      $this->tpl['ROUND'] = self::_("Round");
      $this->tpl['TURN'] = self::_("Turn");

        
  	}
}
