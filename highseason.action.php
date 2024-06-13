<?php
/**
 *------
 * BGA framework: Gregory Isabelli & Emmanuel Colin & BoardGameArena
 * highseason implementation : © <Mathieu Chatrain> <mathieu.chatrain@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 * 
 * highseason.action.php
 *
 * highseason main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *       
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/highseason/highseason/myAction.html", ...)
 *
 */
  
  
  class action_highseason extends APP_GameAction
  { 
    // Constructor: please do not modify
   	public function __default()
  	{
  	    if( self::isArg( 'notifwindow') )
  	    {
            $this->view = "common_notifwindow";
  	        $this->viewArgs['table'] = self::getArg( "table", AT_posint, true );
  	    }
  	    else
  	    {
            $this->view = "highseason_highseason";
            self::trace( "Complete reinitialization of board game" );
      }
  	} 
  	
  	public function actSelect()
  	{
  	    self::setAjaxMode();
  	    
  	    $arg1 = self::getArg( "arg1", AT_alphanum );
  	    
  	    
  	    $this->game->actSelect( $arg1);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actButton()
  	{
		self::setAjaxMode();
		$arg1 = self::getArg( "arg1", AT_alphanum );
  	    
  	    $this->game->actButton($arg1);
  	    
  	    self::ajaxResponse( );
  	}

	  public function actUndo()
  	{
  	    self::setAjaxMode();
  	    
  	    $this->game->actUndo();
  	    
  	    self::ajaxResponse( );
  	}

  }
  

