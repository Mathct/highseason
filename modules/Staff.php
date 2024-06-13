<?php 


class Staff extends APP_GameClass
{
    
        
    public function __construct()
    {
       


        $player_id = highseason::$instance->getActivePlayerId();
        
        $p = self::getObjectFromDB("SELECT * FROM player WHERE player_id = {$player_id}");        
        $this->player_no = $p['player_no'];
        $this->player_id = $p['player_id'];
        $this->player_name = $p['player_name'];
        $this->player_score = $p['player_score'];
        $this->player_color = $p['player_color'];
        $this->player_hotel = $p['hotel'];
        $this->player_staff = $p['staff'];
        $this->player_moneygain = intval($p['moneygain']);
        $this->player_moneyuse = intval($p['moneyuse']);
        $this->player_money = $this->player_moneygain- $this->player_moneyuse;
        $this->player_staff1 = intval(self::getUniqueValueFromDB("SELECT type FROM staff WHERE player_id={$this->player_id} AND pos = 1 AND etat = 1"));
        $this->player_staff2 = intval(self::getUniqueValueFromDB("SELECT type FROM staff WHERE player_id={$this->player_id} AND pos = 2 AND etat = 1"));



    }
    
    public function arginit($parg1, $parg2)
    {
        
    }
    
    public function init($parg1, $parg2, $varg1, $varg2)
    {
        
    }

    public function argStaff1($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        
        return $ret;
        
    }
    
    public function Staff1($parg1, $parg2, $varg1, $varg2)
    {

        self::DbQuery( "UPDATE staff set etat = 1 WHERE player_id = {$this->player_id} AND type = 1" );
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        
        
    }

    public function argStaff2($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        

        return $ret;
        
    }
    
    public function Staff2($parg1, $parg2, $varg1, $varg2)
    {

        self::DbQuery( "UPDATE staff set etat = 1 WHERE player_id = {$this->player_id} AND type = 2" );
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
       
        
    }

    public function argStaff3($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        
        return $ret;
        
    }
    
    public function Staff3($parg1, $parg2, $varg1, $varg2)
    {
        highseason::$instance->setGameStateValue('staffdouble', 1);
        self::DbQuery( "INSERT INTO multiaction (type, action, arg) VALUES ('staff', 'Staffaction', 3)");
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        
        
    }

    public function argStaffaction3($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose an action');
        

        $icon = 3;
        $ret['icon'] = '<div class="staff_' . $icon . '"></div>';

        
        //// pas besoin de faire un credit ici (le cout est à 0)

        $porteadajacente = highseason::$instance->getPorteAdjacente(); 
        foreach ($porteadajacente as $porte)
        {
            $niveau = self::getUniqueValueFromDB("SELECT niveau FROM hotel WHERE porte = {$porte} AND player_id={$this->player_id}");
            $couleur = self::getUniqueValueFromDB("SELECT couleur FROM hotel WHERE porte = {$porte} AND player_id={$this->player_id}");
            
            if ($couleur==2)
            {
            $ret["selectable"][]= 'poignee_'.$porte.'_'.$this->player_id;
            }
        }

        if($ret["selectable"] != NULL)
        {
        
            $a = highseason::$instance->getGameStateValue('staffdouble');
            if ($a == 1)
            {
                $ret['titleyou'] = clienttranslate('${you} must perform the staff bonus action #icon# (1/2) or');
            }

            if ($a == 2)
            {
                $ret['titleyou'] = clienttranslate('${you} must perform the staff bonus action #icon# (2/2) or');
            }

            $ret['buttons'][]='pass';
            $ret['buttons'][]='undo';
        }

        else
        {
            $ret['titleyou'] = clienttranslate('${you} can\'t do the staff action');
            $ret['buttons'][]='pass';
            $ret['buttons'][]='undo';
        }

        return $ret;
        
    }
    
    public function Staffaction3($parg1, $parg2, $varg1, $varg2)
    {

        if($varg1 == "pass")
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif($varg1 == NULL)
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        else
        {

            $explode = explode("_", $varg1);
            $porte = intval($explode[1]);

            self::DbQuery( "UPDATE hotel set etat = 1 WHERE player_id = {$this->player_id} AND porte = {$porte}" );

            highseason::$instance->notifyAllPlayers('preparing',clienttranslate( '${player_name} prepares the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte, 
                
                )
                );

            $a = highseason::$instance->getGameStateValue('staffdouble');

            if ($a == 2)
            {
                highseason::$instance->addPending($this->player_id, "CheckMultiAction");
            }

            if ($a == 1)
            {
                highseason::$instance->setGameStateValue('staffdouble', 2);
                self::DbQuery( "INSERT INTO multiaction (type, action, arg) VALUES ('staff', 'Staffaction', 3)");
                highseason::$instance->addPending($this->player_id, "CheckMultiAction");
            }
    
            
                    
            
        }


    }
        

        

    public function argStaff4($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        
        return $ret;
        
    }
    
    public function Staff4($parg1, $parg2, $varg1, $varg2)
    {
        
        self::DbQuery( "INSERT INTO multiaction (type, action, arg) VALUES ('staff', 'Staffaction', 4)");
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        
        
    }

    public function argStaffaction4($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose an action');
        

        $icon = 4;
        $ret['icon'] = '<div class="staff_' . $icon . '"></div>';

        
        //// pas besoin de faire un credit ici (le cout est à 0)

        $porteprepare = self::getObjectListFromDB( "SELECT porte FROM hotel WHERE etat = 1 AND player_id = {$this->player_id}", true );
        
        foreach ($porteprepare as $porte)
        {
            
            $couleur = self::getUniqueValueFromDB("SELECT couleur FROM hotel WHERE porte = {$porte} AND player_id={$this->player_id}");
            
            if ($couleur == 3)
            {
            $ret["selectable"][]= 'poignee_'.$porte.'_'.$this->player_id;
            }
        }


        if($ret["selectable"] != NULL)
        {
        
            
            $ret['titleyou'] = clienttranslate('${you} must perform the staff bonus action #icon# or');
           

            $ret['buttons'][]='pass';
            $ret['buttons'][]='undo';
        }

        else
        {
            $ret['titleyou'] = clienttranslate('${you} can\'t do the staff action');
            $ret['buttons'][]='pass';
            $ret['buttons'][]='undo';
        }

        return $ret;
        
    }
    
    public function Staffaction4($parg1, $parg2, $varg1, $varg2)
    {

        if($varg1 == "pass")
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif($varg1 == NULL)
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        else
        {
            $explode = explode("_", $varg1);
            $porte = intval($explode[1]);

            if (($porte >=3)&&($porte<=5))
             {
                self::DbQuery( "UPDATE hotel set etat = 3 WHERE player_id = {$this->player_id} AND porte = {$porte}" );
                
             }
            else
            {
                self::DbQuery( "UPDATE hotel set etat = 2 WHERE player_id = {$this->player_id} AND porte = {$porte}" );
                

            }

            highseason::$instance->notifyAllPlayers('occupying',clienttranslate( '${player_name} occupies the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte,
                
                )
                );

                        
            if (($porte >=1)&&($porte<=7))
            {
                self::DbQuery( "UPDATE player set vpligne1 = vpligne1 +1  WHERE player_id = {$this->player_id}" );
            }
            if (($porte >=8)&&($porte<=14))
            {
                self::DbQuery( "UPDATE player set vpligne2 = vpligne2 +2  WHERE player_id = {$this->player_id}" );
            }
            if (($porte >=15)&&($porte<=21))
            {
                self::DbQuery( "UPDATE player set vpligne3 = vpligne3 +3  WHERE player_id = {$this->player_id}" );
            }
            if (($porte >=22)&&($porte<=28))
            {
                self::DbQuery( "UPDATE player set vpligne4 = vpligne4 +4  WHERE player_id = {$this->player_id}" );
            }
            
            highseason::$instance->BonusEtage($porte);
            highseason::$instance->BonusGroupe($porte);
            highseason::$instance->Score();

            if($porte >= 8)

            {
                self::DbQuery( "INSERT INTO multiaction (type, action, arg) VALUES ('hotel', 'Porte', {$porte})");
            }
       
        
                       
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");      
            
        }


    }

    public function argStaff5($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        

        return $ret;
        
    }
    
    public function Staff5($parg1, $parg2, $varg1, $varg2)
    {

        self::DbQuery( "UPDATE staff set etat = 1 WHERE player_id = {$this->player_id} AND type = 5" );
        $pos = self::getUniqueValueFromDB("SELECT pos FROM staff WHERE player_id={$this->player_id} AND type = 5");
        if($pos==5)
        {
            self::DbQuery( "UPDATE player set permstaff1 = 5 WHERE player_id = {$this->player_id}" );
        }
        if($pos==6)
        {
            self::DbQuery( "UPDATE player set permstaff2 = 5 WHERE player_id = {$this->player_id}" );
        }
        highseason::$instance->Score();
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        
        
    }

    public function argStaff6($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        

        return $ret;
        
    }
    
    public function Staff6($parg1, $parg2, $varg1, $varg2)
    {

        self::DbQuery( "UPDATE staff set etat = 1 WHERE player_id = {$this->player_id} AND type = 6" );
        $pos = self::getUniqueValueFromDB("SELECT pos FROM staff WHERE player_id={$this->player_id} AND type = 6");
        if($pos==5)
        {
            self::DbQuery( "UPDATE player set permstaff1 = 6 WHERE player_id = {$this->player_id}" );
        }
        if($pos==6)
        {
            self::DbQuery( "UPDATE player set permstaff2 = 6 WHERE player_id = {$this->player_id}" );
        }
        highseason::$instance->Score();
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
    }

    public function argStaff7($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        

        return $ret;
        
    }
    
    public function Staff7($parg1, $parg2, $varg1, $varg2)
    {

        self::DbQuery( "UPDATE staff set etat = 1 WHERE player_id = {$this->player_id} AND type = 7" );
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        
        
    }

    public function argStaff8($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        

        return $ret;
        
    }
    
    public function Staff8($parg1, $parg2, $varg1, $varg2)
    {

        self::DbQuery( "UPDATE staff set etat = 1 WHERE player_id = {$this->player_id} AND type = 8" );
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
    }

    public function argStaff9($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        

        return $ret;
        
    }
    
    public function Staff9($parg1, $parg2, $varg1, $varg2)
    {

        highseason::$instance->GainEmperor(3);
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        
        
    }

    public function argStaff10($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        

        return $ret;
        
    }
    
    public function Staff10($parg1, $parg2, $varg1, $varg2)
    {

        self::DbQuery( "INSERT INTO multiaction (type, action, arg) VALUES ('staff', 'Staffaction', 101)");
        self::DbQuery( "INSERT INTO multiaction (type, action, arg) VALUES ('staff', 'Staffaction', 102)");
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        
    }

    public function argStaffaction101($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose an action');
        

        $icon = 101;
        $ret['icon'] = '<div class="staff_' . $icon . '"></div>';

        
        //// pas besoin de faire un credit ici (le cout est à 0)

        $porteadajacente = highseason::$instance->getPorteAdjacente(); 
        foreach ($porteadajacente as $porte)
        {
            
            $ret["selectable"][]= 'poignee_'.$porte.'_'.$this->player_id;
            
        }

        if($ret["selectable"] != NULL)
        {
        
            $ret['titleyou'] = clienttranslate('${you} must perform the staff bonus action #icon# or');
           

            $ret['buttons'][]='pass';
            $ret['buttons'][]='undo';
        }

        else
        {
            $ret['titleyou'] = clienttranslate('${you} can\'t do the staff action');
            $ret['buttons'][]='pass';
            $ret['buttons'][]='undo';
        }

        return $ret;
        
    }
    
    public function Staffaction101($parg1, $parg2, $varg1, $varg2)
    {

        if($varg1 == "pass")
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif($varg1 == NULL)
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        else
        {

            $explode = explode("_", $varg1);
            $porte = intval($explode[1]);

            self::DbQuery( "UPDATE hotel set etat = 1 WHERE player_id = {$this->player_id} AND porte = {$porte}" );

            highseason::$instance->notifyAllPlayers('preparing',clienttranslate( '${player_name} prepares the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte, 
                
                )
                );

            
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
            
            
                    
            
        }


    }

    public function argStaffaction102($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose an action');
        

        $icon = 102;
        $ret['icon'] = '<div class="staff_' . $icon . '"></div>';

        
        //// pas besoin de faire un credit ici (le cout est à 0)

        $porteprepare = self::getObjectListFromDB( "SELECT porte FROM hotel WHERE etat = 1 AND player_id = {$this->player_id}", true );
        
        foreach ($porteprepare as $porte)
        {
            
            $ret["selectable"][]= 'poignee_'.$porte.'_'.$this->player_id;
          
        }


        if($ret["selectable"] != NULL)
        {
        
            
            $ret['titleyou'] = clienttranslate('${you} must perform the staff bonus action #icon# or');
           

            $ret['buttons'][]='pass';
            $ret['buttons'][]='undo';
        }

        else
        {
            $ret['titleyou'] = clienttranslate('${you} can\'t do the staff action');
            $ret['buttons'][]='pass';
            $ret['buttons'][]='undo';
        }

        return $ret;
        
    }
    
    public function Staffaction102($parg1, $parg2, $varg1, $varg2)
    {

        if($varg1 == "pass")
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif($varg1 == NULL)
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        else
        {
            $explode = explode("_", $varg1);
            $porte = intval($explode[1]);

            if (($porte >=3)&&($porte<=5))
             {
                self::DbQuery( "UPDATE hotel set etat = 3 WHERE player_id = {$this->player_id} AND porte = {$porte}" );
                
             }
            else
            {
                self::DbQuery( "UPDATE hotel set etat = 2 WHERE player_id = {$this->player_id} AND porte = {$porte}" );
                

            }

            highseason::$instance->notifyAllPlayers('occupying',clienttranslate( '${player_name} occupies the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte,
                
                )
                );

                        
            if (($porte >=1)&&($porte<=7))
            {
                self::DbQuery( "UPDATE player set vpligne1 = vpligne1 +1  WHERE player_id = {$this->player_id}" );
            }
            if (($porte >=8)&&($porte<=14))
            {
                self::DbQuery( "UPDATE player set vpligne2 = vpligne2 +2  WHERE player_id = {$this->player_id}" );
            }
            if (($porte >=15)&&($porte<=21))
            {
                self::DbQuery( "UPDATE player set vpligne3 = vpligne3 +3  WHERE player_id = {$this->player_id}" );
            }
            if (($porte >=22)&&($porte<=28))
            {
                self::DbQuery( "UPDATE player set vpligne4 = vpligne4 +4  WHERE player_id = {$this->player_id}" );
            }
            
            highseason::$instance->BonusEtage($porte);
            highseason::$instance->BonusGroupe($porte);
            highseason::$instance->Score();

            if($porte >= 8)

            {
                self::DbQuery( "INSERT INTO multiaction (type, action, arg) VALUES ('hotel', 'Porte', {$porte})");
            }
       
        
                       
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");      
            
        }


    }


    public function argStaff11($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        

        return $ret;
        
    }
    
    public function Staff11($parg1, $parg2, $varg1, $varg2)
    {

        self::DbQuery( "UPDATE staff set etat = 1 WHERE player_id = {$this->player_id} AND type = 11" );
        $pos = self::getUniqueValueFromDB("SELECT pos FROM staff WHERE player_id={$this->player_id} AND type = 11");
        if($pos==5)
        {
            self::DbQuery( "UPDATE player set permstaff1 = 11 WHERE player_id = {$this->player_id}" );
        }
        if($pos==6)
        {
            self::DbQuery( "UPDATE player set permstaff2 = 11 WHERE player_id = {$this->player_id}" );
        }
        highseason::$instance->Score();
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        
    }

    public function argStaff12($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        

        return $ret;
        
    }
    
    public function Staff12($parg1, $parg2, $varg1, $varg2)
    {

        self::DbQuery( "UPDATE staff set etat = 1 WHERE player_id = {$this->player_id} AND type = 12" );
        $pos = self::getUniqueValueFromDB("SELECT pos FROM staff WHERE player_id={$this->player_id} AND type = 12");
        if($pos==5)
        {
            self::DbQuery( "UPDATE player set permstaff1 = 12 WHERE player_id = {$this->player_id}" );
        }
        if($pos==6)
        {
            self::DbQuery( "UPDATE player set permstaff2 = 12 WHERE player_id = {$this->player_id}" );
        }
        highseason::$instance->Score();
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
    }

    public function argStaff13($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

       

        return $ret;
        
    }
    
    public function Staff13($parg1, $parg2, $varg1, $varg2)
    {

        self::DbQuery( "UPDATE staff set etat = 1 WHERE player_id = {$this->player_id} AND type = 13" );
        
        $a = highseason::$instance->getGameStateValue('preparingmax3');
        $a = $a +1;
        highseason::$instance->setGameStateValue('preparingmax3', $a);
        
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        
    }


    public function argStaff14($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        
        return $ret;
        
    }
    
    public function Staff14($parg1, $parg2, $varg1, $varg2)
    {
        highseason::$instance->setGameStateValue('staffdouble', 1);
        self::DbQuery( "INSERT INTO multiaction (type, action, arg) VALUES ('staff', 'Staffaction', 14)");
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        
        
    }

    public function argStaffaction14($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose an action');
        

        $icon = 14;
        $ret['icon'] = '<div class="staff_' . $icon . '"></div>';

        
        //// pas besoin de faire un credit ici (le cout est à 0)

        $porteadajacente = highseason::$instance->getPorteAdjacente(); 
        foreach ($porteadajacente as $porte)
        {
            $niveau = self::getUniqueValueFromDB("SELECT niveau FROM hotel WHERE porte = {$porte} AND player_id={$this->player_id}");
            $couleur = self::getUniqueValueFromDB("SELECT couleur FROM hotel WHERE porte = {$porte} AND player_id={$this->player_id}");
            
            if ($couleur==3)
            {
            $ret["selectable"][]= 'poignee_'.$porte.'_'.$this->player_id;
            }
        }

        if($ret["selectable"] != NULL)
        {
        
            $a = highseason::$instance->getGameStateValue('staffdouble');
            if ($a == 1)
            {
                $ret['titleyou'] = clienttranslate('${you} must perform the staff bonus action #icon# (1/2) or');
            }

            if ($a == 2)
            {
                $ret['titleyou'] = clienttranslate('${you} must perform the staff bonus action #icon# (2/2) or');
            }

            $ret['buttons'][]='pass';
            $ret['buttons'][]='undo';
        }

        else
        {
            $ret['titleyou'] = clienttranslate('${you} can\'t do the staff action');
            $ret['buttons'][]='pass';
            $ret['buttons'][]='undo';
        }

        return $ret;
        
    }
    
    public function Staffaction14($parg1, $parg2, $varg1, $varg2)
    {

        if($varg1 == "pass")
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif($varg1 == NULL)
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        else
        {

            $explode = explode("_", $varg1);
            $porte = intval($explode[1]);

            self::DbQuery( "UPDATE hotel set etat = 1 WHERE player_id = {$this->player_id} AND porte = {$porte}" );

            highseason::$instance->notifyAllPlayers('preparing',clienttranslate( '${player_name} prepares the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte, 
                
                )
                );

            $a = highseason::$instance->getGameStateValue('staffdouble');

            if ($a == 2)
            {
                highseason::$instance->addPending($this->player_id, "CheckMultiAction");
            }

            if ($a == 1)
            {
                highseason::$instance->setGameStateValue('staffdouble', 2);
                self::DbQuery( "INSERT INTO multiaction (type, action, arg) VALUES ('staff', 'Staffaction', 14)");
                highseason::$instance->addPending($this->player_id, "CheckMultiAction");
            }
    
            
                    
            
        }


    }

    public function argStaff15($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        
        return $ret;
        
    }
    
    public function Staff15($parg1, $parg2, $varg1, $varg2)
    {
        
        self::DbQuery( "INSERT INTO multiaction (type, action, arg) VALUES ('staff', 'Staffaction', 15)");
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        
        
    }

    public function argStaffaction15($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose an action');
        

        $icon = 15;
        $ret['icon'] = '<div class="staff_' . $icon . '"></div>';

        
        //// pas besoin de faire un credit ici (le cout est à 0)

        $porteprepare = self::getObjectListFromDB( "SELECT porte FROM hotel WHERE etat = 1 AND player_id = {$this->player_id}", true );
        
        foreach ($porteprepare as $porte)
        {
            
            $couleur = self::getUniqueValueFromDB("SELECT couleur FROM hotel WHERE porte = {$porte} AND player_id={$this->player_id}");
            
            if ($couleur == 1)
            {
            $ret["selectable"][]= 'poignee_'.$porte.'_'.$this->player_id;
            }
        }


        if($ret["selectable"] != NULL)
        {
        
            
            $ret['titleyou'] = clienttranslate('${you} must perform the staff bonus action #icon# or');
           

            $ret['buttons'][]='pass';
            $ret['buttons'][]='undo';
        }

        else
        {
            $ret['titleyou'] = clienttranslate('${you} can\'t do the staff action');
            $ret['buttons'][]='pass';
            $ret['buttons'][]='undo';
        }

        return $ret;
        
    }
    
    public function Staffaction15($parg1, $parg2, $varg1, $varg2)
    {

        if($varg1 == "pass")
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif($varg1 == NULL)
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        else
        {
            $explode = explode("_", $varg1);
            $porte = intval($explode[1]);

            if (($porte >=3)&&($porte<=5))
             {
                self::DbQuery( "UPDATE hotel set etat = 3 WHERE player_id = {$this->player_id} AND porte = {$porte}" );
                
             }
            else
            {
                self::DbQuery( "UPDATE hotel set etat = 2 WHERE player_id = {$this->player_id} AND porte = {$porte}" );
                

            }

            highseason::$instance->notifyAllPlayers('occupying',clienttranslate( '${player_name} occupies the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte,
                
                )
                );

                        
            if (($porte >=1)&&($porte<=7))
            {
                self::DbQuery( "UPDATE player set vpligne1 = vpligne1 +1  WHERE player_id = {$this->player_id}" );
            }
            if (($porte >=8)&&($porte<=14))
            {
                self::DbQuery( "UPDATE player set vpligne2 = vpligne2 +2  WHERE player_id = {$this->player_id}" );
            }
            if (($porte >=15)&&($porte<=21))
            {
                self::DbQuery( "UPDATE player set vpligne3 = vpligne3 +3  WHERE player_id = {$this->player_id}" );
            }
            if (($porte >=22)&&($porte<=28))
            {
                self::DbQuery( "UPDATE player set vpligne4 = vpligne4 +4  WHERE player_id = {$this->player_id}" );
            }
            
            highseason::$instance->BonusEtage($porte);
            highseason::$instance->BonusGroupe($porte);
            highseason::$instance->Score();

            if($porte >= 8)

            {
                self::DbQuery( "INSERT INTO multiaction (type, action, arg) VALUES ('hotel', 'Porte', {$porte})");
            }
       
        
                       
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");      
            
        }


    }


    public function argStaff16($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

       

        return $ret;
        
    }
    
    public function Staff16($parg1, $parg2, $varg1, $varg2)
    {

        self::DbQuery( "UPDATE staff set etat = 1 WHERE player_id = {$this->player_id} AND type = 16" );
        $pos = self::getUniqueValueFromDB("SELECT pos FROM staff WHERE player_id={$this->player_id} AND type = 16");
        if($pos==5)
        {
            self::DbQuery( "UPDATE player set permstaff1 = 16 WHERE player_id = {$this->player_id}" );
        }
        if($pos==6)
        {
            self::DbQuery( "UPDATE player set permstaff2 =16 WHERE player_id = {$this->player_id}" );
        }
        highseason::$instance->Score();
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        
    }


    public function argStaff17($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        

        return $ret;
        
    }
    
    public function Staff17($parg1, $parg2, $varg1, $varg2)
    {

        self::DbQuery( "UPDATE staff set etat = 1 WHERE player_id = {$this->player_id} AND type = 17" );
        $pos = self::getUniqueValueFromDB("SELECT pos FROM staff WHERE player_id={$this->player_id} AND type = 17");
        if($pos==5)
        {
            self::DbQuery( "UPDATE player set permstaff1 = 17 WHERE player_id = {$this->player_id}" );
        }
        if($pos==6)
        {
            self::DbQuery( "UPDATE player set permstaff2 =17 WHERE player_id = {$this->player_id}" );
        }
        highseason::$instance->Score();
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        
    }



    public function argStaff18($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

       

        return $ret;
        
    }
    
    public function Staff18($parg1, $parg2, $varg1, $varg2)
    {

        self::DbQuery( "UPDATE staff set etat = 1 WHERE player_id = {$this->player_id} AND type = 18" );
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        
        
    }

    public function argStaff19($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        

        return $ret;
        
    }
    
    public function Staff19($parg1, $parg2, $varg1, $varg2)
    {

        self::DbQuery( "UPDATE staff set etat = 1 WHERE player_id = {$this->player_id} AND type = 19" );
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
    }

    
    public function argStaff20($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        
        return $ret;
        
    }
    
    public function Staff20($parg1, $parg2, $varg1, $varg2)
    {
        highseason::$instance->GainEmperor(2);
        self::DbQuery( "INSERT INTO multiaction (type, action, arg) VALUES ('staff', 'Staffaction', 20)");
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        
        
    }

    public function argStaffaction20($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose an action');
        

        $icon = 20;
        $ret['icon'] = '<div class="staff_' . $icon . '"></div>';

        
        //// pas besoin de faire un credit ici (le cout est à 0)

        $porteadajacente = highseason::$instance->getPorteAdjacente(); 
        foreach ($porteadajacente as $porte)
        {
            
            $ret["selectable"][]= 'poignee_'.$porte.'_'.$this->player_id;
            
        }

        if($ret["selectable"] != NULL)
        {
        
            $ret['titleyou'] = clienttranslate('${you} must perform the staff bonus action #icon# or');
           

            $ret['buttons'][]='pass';
            $ret['buttons'][]='undo';
        }

        else
        {
            $ret['titleyou'] = clienttranslate('${you} can\'t do the staff action');
            $ret['buttons'][]='pass';
            $ret['buttons'][]='undo';
        }

        return $ret;
        
    }
    
    public function Staffaction20($parg1, $parg2, $varg1, $varg2)
    {

        if($varg1 == "pass")
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif($varg1 == NULL)
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        else
        {

            $explode = explode("_", $varg1);
            $porte = intval($explode[1]);

            self::DbQuery( "UPDATE hotel set etat = 1 WHERE player_id = {$this->player_id} AND porte = {$porte}" );

            highseason::$instance->notifyAllPlayers('preparing',clienttranslate( '${player_name} prepares the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte, 
                
                )
                );

            
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
            
            
                    
            
        }


    }

    public function argStaff21($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        
        return $ret;
        
    }
    
    public function Staff21($parg1, $parg2, $varg1, $varg2)
    {
        
        self::DbQuery( "INSERT INTO multiaction (type, action, arg) VALUES ('staff', 'Staffaction', 21)");
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        
        
    }

    public function argStaffaction21($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose an action');
        

        $icon = 21;
        $ret['icon'] = '<div class="staff_' . $icon . '"></div>';

        
        //// pas besoin de faire un credit ici (le cout est à 0)

        $porteprepare = self::getObjectListFromDB( "SELECT porte FROM hotel WHERE etat = 1 AND player_id = {$this->player_id}", true );
        
        foreach ($porteprepare as $porte)
        {
            
            $couleur = self::getUniqueValueFromDB("SELECT couleur FROM hotel WHERE porte = {$porte} AND player_id={$this->player_id}");
            
            if ($couleur == 2)
            {
            $ret["selectable"][]= 'poignee_'.$porte.'_'.$this->player_id;
            }
        }


        if($ret["selectable"] != NULL)
        {
        
            
            $ret['titleyou'] = clienttranslate('${you} must perform the staff bonus action #icon# or');
           

            $ret['buttons'][]='pass';
            $ret['buttons'][]='undo';
        }

        else
        {
            $ret['titleyou'] = clienttranslate('${you} can\'t do the staff action');
            $ret['buttons'][]='pass';
            $ret['buttons'][]='undo';
        }

        return $ret;
        
    }
    
    public function Staffaction21($parg1, $parg2, $varg1, $varg2)
    {

        if($varg1 == "pass")
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif($varg1 == NULL)
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        else
        {
            $explode = explode("_", $varg1);
            $porte = intval($explode[1]);

            if (($porte >=3)&&($porte<=5))
             {
                self::DbQuery( "UPDATE hotel set etat = 3 WHERE player_id = {$this->player_id} AND porte = {$porte}" );
                
             }
            else
            {
                self::DbQuery( "UPDATE hotel set etat = 2 WHERE player_id = {$this->player_id} AND porte = {$porte}" );
                

            }

            highseason::$instance->notifyAllPlayers('occupying',clienttranslate( '${player_name} occupies the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte,
                
                )
                );

                        
            if (($porte >=1)&&($porte<=7))
            {
                self::DbQuery( "UPDATE player set vpligne1 = vpligne1 +1  WHERE player_id = {$this->player_id}" );
            }
            if (($porte >=8)&&($porte<=14))
            {
                self::DbQuery( "UPDATE player set vpligne2 = vpligne2 +2  WHERE player_id = {$this->player_id}" );
            }
            if (($porte >=15)&&($porte<=21))
            {
                self::DbQuery( "UPDATE player set vpligne3 = vpligne3 +3  WHERE player_id = {$this->player_id}" );
            }
            if (($porte >=22)&&($porte<=28))
            {
                self::DbQuery( "UPDATE player set vpligne4 = vpligne4 +4  WHERE player_id = {$this->player_id}" );
            }
            
            highseason::$instance->BonusEtage($porte);
            highseason::$instance->BonusGroupe($porte);
            highseason::$instance->Score();

            if($porte >= 8)

            {
                self::DbQuery( "INSERT INTO multiaction (type, action, arg) VALUES ('hotel', 'Porte', {$porte})");
            }
       
        
                       
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");      
            
        }


    }

    public function argStaff22($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        

        return $ret;
        
    }
    
    public function Staff22($parg1, $parg2, $varg1, $varg2)
    {

        self::DbQuery( "UPDATE staff set etat = 1 WHERE player_id = {$this->player_id} AND type = 22" );
        $pos = self::getUniqueValueFromDB("SELECT pos FROM staff WHERE player_id={$this->player_id} AND type = 22");
        if($pos==5)
        {
            self::DbQuery( "UPDATE player set permstaff1 = 22 WHERE player_id = {$this->player_id}" );
        }
        if($pos==6)
        {
            self::DbQuery( "UPDATE player set permstaff2 =22 WHERE player_id = {$this->player_id}" );
        }
        highseason::$instance->Score();
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        
    }

    public function argStaff23($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        

        return $ret;
        
    }
    
    public function Staff23($parg1, $parg2, $varg1, $varg2)
    {

        self::DbQuery( "UPDATE staff set etat = 1 WHERE player_id = {$this->player_id} AND type = 23" );
        $pos = self::getUniqueValueFromDB("SELECT pos FROM staff WHERE player_id={$this->player_id} AND type = 23");
        if($pos==5)
        {
            self::DbQuery( "UPDATE player set permstaff1 = 23 WHERE player_id = {$this->player_id}" );
        }
        if($pos==6)
        {
            self::DbQuery( "UPDATE player set permstaff2 =23 WHERE player_id = {$this->player_id}" );
        }
        highseason::$instance->Score();
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        
    }

    public function argStaff24($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        

        return $ret;
        
    }
    
    public function Staff24($parg1, $parg2, $varg1, $varg2)
    {

        self::DbQuery( "UPDATE staff set etat = 1 WHERE player_id = {$this->player_id} AND type = 24" );
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
    }

    public function argStaff25($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        
        return $ret;
        
    }
    
    public function Staff25($parg1, $parg2, $varg1, $varg2)
    {
        highseason::$instance->setGameStateValue('staffdouble', 1);
        self::DbQuery( "INSERT INTO multiaction (type, action, arg) VALUES ('staff', 'Staffaction', 25)");
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        
        
    }

    public function argStaffaction25($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose an action');
        

        $icon = 25;
        $ret['icon'] = '<div class="staff_' . $icon . '"></div>';

        
        //// pas besoin de faire un credit ici (le cout est à 0)

        $porteadajacente = highseason::$instance->getPorteAdjacente(); 
        foreach ($porteadajacente as $porte)
        {
            $niveau = self::getUniqueValueFromDB("SELECT niveau FROM hotel WHERE porte = {$porte} AND player_id={$this->player_id}");
            $couleur = self::getUniqueValueFromDB("SELECT couleur FROM hotel WHERE porte = {$porte} AND player_id={$this->player_id}");
            
            if ($couleur==1)
            {
            $ret["selectable"][]= 'poignee_'.$porte.'_'.$this->player_id;
            }
        }

        if($ret["selectable"] != NULL)
        {
        
            $a = highseason::$instance->getGameStateValue('staffdouble');
            if ($a == 1)
            {
                $ret['titleyou'] = clienttranslate('${you} must perform the staff bonus action #icon# (1/2) or');
            }

            if ($a == 2)
            {
                $ret['titleyou'] = clienttranslate('${you} must perform the staff bonus action #icon# (2/2) or');
            }

            $ret['buttons'][]='pass';
            $ret['buttons'][]='undo';
        }

        else
        {
            $ret['titleyou'] = clienttranslate('${you} can\'t do the staff action');
            $ret['buttons'][]='pass';
            $ret['buttons'][]='undo';
        }

        return $ret;
        
    }
    
    public function Staffaction25($parg1, $parg2, $varg1, $varg2)
    {

        if($varg1 == "pass")
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif($varg1 == NULL)
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        else
        {

            $explode = explode("_", $varg1);
            $porte = intval($explode[1]);

            self::DbQuery( "UPDATE hotel set etat = 1 WHERE player_id = {$this->player_id} AND porte = {$porte}" );

            highseason::$instance->notifyAllPlayers('preparing',clienttranslate( '${player_name} prepares the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte, 
                
                )
                );

            $a = highseason::$instance->getGameStateValue('staffdouble');

            if ($a == 2)
            {
                highseason::$instance->addPending($this->player_id, "CheckMultiAction");
            }

            if ($a == 1)
            {
                highseason::$instance->setGameStateValue('staffdouble', 2);
                self::DbQuery( "INSERT INTO multiaction (type, action, arg) VALUES ('staff', 'Staffaction', 25)");
                highseason::$instance->addPending($this->player_id, "CheckMultiAction");
            }
    
            
                    
            
        }


    }

    public function argStaff26($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

        

        return $ret;
        
    }
    
    public function Staff26($parg1, $parg2, $varg1, $varg2)
    {

        self::DbQuery( "UPDATE staff set etat = 1 WHERE player_id = {$this->player_id} AND type = 26" );
        $pos = self::getUniqueValueFromDB("SELECT pos FROM staff WHERE player_id={$this->player_id} AND type = 26");
        if($pos==5)
        {
            self::DbQuery( "UPDATE player set permstaff1 = 26 WHERE player_id = {$this->player_id}" );
        }
        if($pos==6)
        {
            self::DbQuery( "UPDATE player set permstaff2 =26 WHERE player_id = {$this->player_id}" );
        }
        highseason::$instance->Score();
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
    }

    public function argStaff27($parg1, $parg2)
    {

        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        $ret['titleyou'] = clienttranslate('${you}');

       

        return $ret;
        
    }
    
    public function Staff27($parg1, $parg2, $varg1, $varg2)
    {

        self::DbQuery( "UPDATE staff set etat = 1 WHERE player_id = {$this->player_id} AND type = 27" );
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
       
        
    }

   
    
    
}