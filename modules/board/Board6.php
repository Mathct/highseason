<?php 

class Board6 extends Board
{
    public function arginit($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer}');
        $ret['titleyou'] = clienttranslate('${you}');

              
        return $ret;
     }
    
    public function init($parg1, $parg2, $varg1, $varg2)
    {
        

    }

    public function argPorte8($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');

        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';
      
        //$ret['buttons'][]="pass";
        
        return $ret;
     }
    
    public function Porte8($parg1, $parg2, $varg1, $varg2)
    {
        highseason::$instance->GainEmperor(1);
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
    }

    public function argPorte9($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');

        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';
      
        $staffs = self::getObjectListFromDB( "SELECT `pos` `pos`, `prix` `prix` FROM `staff` WHERE `player_id` = {$this->player_id} AND `etat` = 0");
        $reduction = 1;

        foreach ($staffs as $staff)
        {
            if ($staff['prix'] <= $this->player_money + $reduction)
            {
                $ret["selectable"][] = 'staff_'.$staff['pos'].'_'.$this->player_id;
            }
        }

        $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
        if($credit<3)
        {
            $newcredit = $credit +1;
            $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
        }
        
        $ret['buttons'][]="pass";
        $ret['buttons'][]="undo";
        
        return $ret;
     }
    
    public function Porte9($parg1, $parg2, $varg1, $varg2)
    {
        
        if ($varg1 == 'pass')
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPendingTarget($this->player_id, "Board".$this->player_hotel, 'Porte9', 9);
            highseason::$instance->addPending($this->player_id, "Credit");

        }

        else
        {

            $explode = explode("_", $varg1);
            $pos = intval($explode[1]);

            self::DbQuery( "UPDATE `staff` set `etat` = 1 WHERE `player_id` = {$this->player_id} AND `pos` = {$pos}" );

            highseason::$instance->notifyAllPlayers('gainstaff',clienttranslate( '${player_name} hires staff' ), array(
                
                'pos' =>  $pos,
                'player_name' => $this->player_name, 
                'id' => $this->player_id,
                                
                )
                );

            $prixstaff = self::getUniqueValueFromDB("SELECT `prix` FROM `staff` WHERE `player_id`={$this->player_id} AND `pos` = {$pos}");
            $reduction = 1;
            $money = $prixstaff - $reduction;
            if ($money>=1)
            {
                highseason::$instance->SpendMoney($money);
            }
            
            highseason::$instance->Score();

            $typestaff = self::getUniqueValueFromDB("SELECT `type` FROM `staff` WHERE `player_id`={$this->player_id} AND `pos` = {$pos}");
            highseason::$instance->addPendingTarget($this->player_id, "Staff", "Staff".$typestaff);

        }
    }

    public function argPorte10($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');

        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';
      
        $porteprepare = self::getObjectListFromDB( "SELECT `porte` FROM `hotel` WHERE `etat` = 1 AND `player_id` = {$this->player_id}", true );
        
        foreach ($porteprepare as $porte)
        {
            $niveau = intval(self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}"));
            $couleur = self::getUniqueValueFromDB("SELECT `couleur` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
            $cout = $niveau - 1;
            if (($cout <= $this->player_money )&&($couleur == 2))
            {
            $ret["selectable"][]= 'poignee_'.$porte.'_'.$this->player_id;
            }
        }

        $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
        if($credit<3)
        {
            $newcredit = $credit +1;
            $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
        }


        
        $ret['buttons'][]="pass";
        $ret['buttons'][]="undo";
        
        
        return $ret;
     }
    
    public function Porte10($parg1, $parg2, $varg1, $varg2)
    {
        if ($varg1 == 'pass')
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPendingTarget($this->player_id, "Board".$this->player_hotel, 'Porte10', 10);
            highseason::$instance->addPending($this->player_id, "Credit");

        }
        
        else
        {

            $explode = explode("_", $varg1);
            $porte = intval($explode[1]);

            if (($porte >=3)&&($porte<=5))
             {
                self::DbQuery( "UPDATE `hotel` set `etat` = 3 WHERE `player_id` = {$this->player_id} AND `porte` = {$porte}" );
                
             }
            else
            {
                self::DbQuery( "UPDATE `hotel` set `etat` = 2 WHERE `player_id` = {$this->player_id} AND `porte` = {$porte}" );
                

            }

            highseason::$instance->notifyAllPlayers('occupying',clienttranslate( '${player_name} occupies the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte,
                
                )
                );

            $niveau = intval(self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}"));
            
            $spend = $niveau - 1;
            if ($spend > 0)
            {
                highseason::$instance->SpendMoney($spend);
            }
            
            if (($porte >=1)&&($porte<=7))
            {
                self::DbQuery( "UPDATE `player` set `vpligne1` = `vpligne1` +1  WHERE `player_id` = {$this->player_id}" );
            }
            if (($porte >=8)&&($porte<=14))
            {
                self::DbQuery( "UPDATE `player` set `vpligne2` = `vpligne2` +2  WHERE `player_id` = {$this->player_id}" );
            }
            if (($porte >=15)&&($porte<=21))
            {
                self::DbQuery( "UPDATE `player` set `vpligne3` = `vpligne3` +3  WHERE `player_id` = {$this->player_id}" );
            }
            if (($porte >=22)&&($porte<=28))
            {
                self::DbQuery( "UPDATE `player` set `vpligne4` = `vpligne4` +4  WHERE `player_id` = {$this->player_id}" );
            }
            
            highseason::$instance->BonusEtage($porte);
            highseason::$instance->BonusGroupe($porte);
            highseason::$instance->Score();

            if($porte >= 8)

            {
                self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('hotel', 'Porte', {$porte})");
            }
       
        
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");

        }
    }

    public function argPorte11($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');
        
        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';


        $porteadajacente = highseason::$instance->getPorteAdjacente(); 
        foreach ($porteadajacente as $porte)
        {
            $niveau = self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
            $couleur = self::getUniqueValueFromDB("SELECT `couleur` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");

            if((($this->player_staff1 == 24)||($this->player_staff2 == 24))&&($couleur == 3))
            {
                $prix = 0;
            }
            else
            {
            $prix = $niveau -1;
            }

            if (($prix <= $this->player_money )&&($couleur==3))
            {
            $ret["selectable"][]= 'poignee_'.$porte.'_'.$this->player_id;
            }
        }

        $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
        if($credit<3)
        {
            $newcredit = $credit +1;
            $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
        }

        
        $ret['buttons'][]="pass";
        $ret['buttons'][]="undo";
        
        
        return $ret;
     }
    
    public function Porte11($parg1, $parg2, $varg1, $varg2)
    {
        if ($varg1 == 'pass')
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPendingTarget($this->player_id, "Board".$this->player_hotel, 'Porte11', 11);
            highseason::$instance->addPending($this->player_id, "Credit");

        }

        else
        {

        
        $explode = explode("_", $varg1);
            $porte = intval($explode[1]);

            self::DbQuery( "UPDATE `hotel` set `etat` = 1 WHERE `player_id` = {$this->player_id} AND `porte` = {$porte}" );

            highseason::$instance->notifyAllPlayers('preparing',clienttranslate( '${player_name} prepares the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte, 
                
                )
                );

            $niveau = self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
            $couleur = self::getUniqueValueFromDB("SELECT `couleur` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
            
            if(($this->player_staff1 == 24)||($this->player_staff2 == 24))
                {
                    
                }
            else
            {

            if ($niveau == 2)
            {
                highseason::$instance->SpendMoney(1);
                highseason::$instance->Score();
            }
            if ($niveau == 3)
            {
                highseason::$instance->SpendMoney(2);
                highseason::$instance->Score();
            }
            if ($niveau == 4)
            {
                highseason::$instance->SpendMoney(3);
                highseason::$instance->Score();
            }
            }
        
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");

        }
    }

    public function argPorte12($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');

        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';
      
        //$ret['buttons'][]="pass";
        
        return $ret;
     }
    
    public function Porte12($parg1, $parg2, $varg1, $varg2)
    {
        highseason::$instance->GainMoney(1); 
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
    }

    public function argPorte13($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');

        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';
      
        $porteprepare = self::getObjectListFromDB( "SELECT `porte` FROM `hotel` WHERE `etat` = 1 AND `player_id` = {$this->player_id}", true );
        
        foreach ($porteprepare as $porte)
        {
            $niveau = intval(self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}"));
            $couleur = self::getUniqueValueFromDB("SELECT `couleur` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
            $cout = $niveau - 1;
            if (($cout <= $this->player_money )&&($couleur == 3))
            {
            $ret["selectable"][]= 'poignee_'.$porte.'_'.$this->player_id;
            }
        }

        $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
        if($credit<3)
        {
            $newcredit = $credit +1;
            $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
        }


        
        $ret['buttons'][]="pass";
        $ret['buttons'][]="undo";
        
        
        return $ret;
     }
    
    public function Porte13($parg1, $parg2, $varg1, $varg2)
    {
        if ($varg1 == 'pass')
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPendingTarget($this->player_id, "Board".$this->player_hotel, 'Porte13', 13);
            highseason::$instance->addPending($this->player_id, "Credit");

        }
        
        else
        {

            $explode = explode("_", $varg1);
            $porte = intval($explode[1]);

            if (($porte >=3)&&($porte<=5))
             {
                self::DbQuery( "UPDATE `hotel` set `etat` = 3 WHERE `player_id` = {$this->player_id} AND `porte` = {$porte}" );
                
             }
            else
            {
                self::DbQuery( "UPDATE `hotel` set `etat` = 2 WHERE `player_id` = {$this->player_id} AND `porte` = {$porte}" );
                

            }

            highseason::$instance->notifyAllPlayers('occupying',clienttranslate( '${player_name} occupies the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte,
                
                )
                );

            $niveau = intval(self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}"));
            
            $spend = $niveau - 1;
            if ($spend > 0)
            {
                highseason::$instance->SpendMoney($spend);
            }
            
            if (($porte >=1)&&($porte<=7))
            {
                self::DbQuery( "UPDATE `player` set `vpligne1` = `vpligne1` +1  WHERE `player_id` = {$this->player_id}" );
            }
            if (($porte >=8)&&($porte<=14))
            {
                self::DbQuery( "UPDATE `player` set `vpligne2` = `vpligne2` +2  WHERE `player_id` = {$this->player_id}" );
            }
            if (($porte >=15)&&($porte<=21))
            {
                self::DbQuery( "UPDATE `player` set `vpligne3` = `vpligne3` +3  WHERE `player_id` = {$this->player_id}" );
            }
            if (($porte >=22)&&($porte<=28))
            {
                self::DbQuery( "UPDATE `player` set `vpligne4` = `vpligne4` +4  WHERE `player_id` = {$this->player_id}" );
            }
            
            highseason::$instance->BonusEtage($porte);
            highseason::$instance->BonusGroupe($porte);
            highseason::$instance->Score();

            if($porte >= 8)

            {
                self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('hotel', 'Porte', {$porte})");
            }
       
        
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");

        }
    }

    public function argPorte14($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');

        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';
      
        $porteadajacente = highseason::$instance->getPorteAdjacente(); 
        foreach ($porteadajacente as $porte)
        {
            $niveau = self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
            $couleur = self::getUniqueValueFromDB("SELECT `couleur` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
            if((($this->player_staff1 == 19)||($this->player_staff2 == 19))&&($couleur == 1))
            {
                $prix = 0;
            }
            else
            {
            $prix = $niveau -1;
            }

            if (($prix <= $this->player_money )&&($couleur==1))
            {
            $ret["selectable"][]= 'poignee_'.$porte.'_'.$this->player_id;
            }
        }

        $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
        if($credit<3)
        {
            $newcredit = $credit +1;
            $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
        }

        
        $ret['buttons'][]="pass";
        $ret['buttons'][]="undo";
        
        return $ret;
     }
    
    public function Porte14($parg1, $parg2, $varg1, $varg2)
    {
        
        if ($varg1 == 'pass')
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPendingTarget($this->player_id, "Board".$this->player_hotel, 'Porte14', 14);
            highseason::$instance->addPending($this->player_id, "Credit");

        }

        else
        {

        
        $explode = explode("_", $varg1);
            $porte = intval($explode[1]);

            self::DbQuery( "UPDATE `hotel` set `etat` = 1 WHERE `player_id` = {$this->player_id} AND `porte` = {$porte}" );

            highseason::$instance->notifyAllPlayers('preparing',clienttranslate( '${player_name} prepares the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte, 
                
                )
                );

            $niveau = self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
            $couleur = self::getUniqueValueFromDB("SELECT `couleur` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
            
            if(($this->player_staff1 == 19)||($this->player_staff2 == 19))
                {
                    
                }
            else
            {

            if ($niveau == 2)
            {
                highseason::$instance->SpendMoney(1);
                highseason::$instance->Score();
            }
            if ($niveau == 3)
            {
                highseason::$instance->SpendMoney(2);
                highseason::$instance->Score();
            }
            if ($niveau == 4)
            {
                highseason::$instance->SpendMoney(3);
                highseason::$instance->Score();
            }
        }
        
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");

        }
    }

    public function argPorte15($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');

        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';
      
        $porteadajacente = highseason::$instance->getPorteAdjacente(); 
        foreach ($porteadajacente as $porte)
        {
            $niveau = self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
            $couleur = self::getUniqueValueFromDB("SELECT `couleur` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
            if((($this->player_staff1 == 8)||($this->player_staff2 == 8))&&($couleur == 2))
            {
                $prix = 0;
            }
            elseif((($this->player_staff1 == 19)||($this->player_staff2 == 19))&&($couleur == 1))
            {
                $prix = 0;
            }
            elseif((($this->player_staff1 == 24)||($this->player_staff2 == 24))&&($couleur == 3))
            {
                $prix = 0;
            }
            else
            {
                $prix = $niveau -1;
            }
            
            if ($prix <= $this->player_money )
            {
            $ret["selectable"][]= 'poignee_'.$porte.'_'.$this->player_id;
            }
        }

        $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
        if($credit<3)
        {
            $newcredit = $credit +1;
            $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
        }

        
        $ret['buttons'][]="pass";
        $ret['buttons'][]="undo";
        
        return $ret;
     }
    
    public function Porte15($parg1, $parg2, $varg1, $varg2)
    {
        
        if ($varg1 == 'pass')
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPendingTarget($this->player_id, "Board".$this->player_hotel, 'Porte15', 15);
            highseason::$instance->addPending($this->player_id, "Credit");

        }

        else
        {

        
        $explode = explode("_", $varg1);
            $porte = intval($explode[1]);

            self::DbQuery( "UPDATE `hotel` set `etat` = 1 WHERE `player_id` = {$this->player_id} AND `porte` = {$porte}" );

            highseason::$instance->notifyAllPlayers('preparing',clienttranslate( '${player_name} prepares the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte, 
                
                )
                );

                $niveau = self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
                $couleur = self::getUniqueValueFromDB("SELECT `couleur` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
    
                if((($this->player_staff1 == 8)||($this->player_staff2 == 8))&&($couleur == 2))
                {
                    
                }
                elseif((($this->player_staff1 == 19)||($this->player_staff2 == 19))&&($couleur == 1))
                {
                    
                }
                elseif((($this->player_staff1 == 24)||($this->player_staff2 == 24))&&($couleur == 3))
                {
                    
                }
                else
                {
                    if ($niveau == 2)
                    {
                        highseason::$instance->SpendMoney(1);
                        highseason::$instance->Score();
                    }
                    if ($niveau == 3)
                    {
                        highseason::$instance->SpendMoney(2);
                        highseason::$instance->Score();
                    }
                    if ($niveau == 4)
                    {
                        highseason::$instance->SpendMoney(3);
                        highseason::$instance->Score();
                    }
                }
        
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");

        }
    }

    public function argPorte16($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');
        
        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';


        $porteadajacente = highseason::$instance->getPorteAdjacente(); 
        foreach ($porteadajacente as $porte)
        {
            $niveau = self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
            $couleur = self::getUniqueValueFromDB("SELECT `couleur` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");

            if((($this->player_staff1 == 24)||($this->player_staff2 == 24))&&($couleur == 3))
            {
                $prix = 0;
            }
            else
            {
            $prix = $niveau -2;
            }

            if (($prix <= $this->player_money )&&($couleur==3))
            {
            $ret["selectable"][]= 'poignee_'.$porte.'_'.$this->player_id;
            }
        }

        $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
        if($credit<3)
        {
            $newcredit = $credit +1;
            $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
        }

        
        $ret['buttons'][]="pass";
        $ret['buttons'][]="undo";
        
        
        return $ret;
     }
    
    public function Porte16($parg1, $parg2, $varg1, $varg2)
    {
        if ($varg1 == 'pass')
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPendingTarget($this->player_id, "Board".$this->player_hotel, 'Porte16', 16);
            highseason::$instance->addPending($this->player_id, "Credit");

        }

        else
        {

        
        $explode = explode("_", $varg1);
            $porte = intval($explode[1]);

            self::DbQuery( "UPDATE `hotel` set `etat` = 1 WHERE `player_id` = {$this->player_id} AND `porte` = {$porte}" );

            highseason::$instance->notifyAllPlayers('preparing',clienttranslate( '${player_name} prepares the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte, 
                
                )
                );

            $niveau = self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
            $couleur = self::getUniqueValueFromDB("SELECT `couleur` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
            
            if(($this->player_staff1 == 24)||($this->player_staff2 == 24))
                {
                    
                }
            else
            {

            
            if ($niveau == 3)
            {
                highseason::$instance->SpendMoney(1);
                highseason::$instance->Score();
            }
            if ($niveau == 4)
            {
                highseason::$instance->SpendMoney(2);
                highseason::$instance->Score();
            }
            }
        
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");

        }
    }

    public function argPorte17($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');

        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';
      
        //$ret['buttons'][]="pass";
        
        return $ret;
     }
    
    public function Porte17($parg1, $parg2, $varg1, $varg2)
    {
        highseason::$instance->GainEmperor(1);
        highseason::$instance->GainMoney(1); 
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
    }

    public function argPorte18($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');

        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';
      
        $porteprepare = self::getObjectListFromDB( "SELECT `porte` FROM `hotel` WHERE `etat` = 1 AND `player_id` = {$this->player_id}", true );
        
        foreach ($porteprepare as $porte)
        {
            $niveau = intval(self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}"));
            $couleur = self::getUniqueValueFromDB("SELECT `couleur` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
            $cout = $niveau - 2;
            if (($cout <= $this->player_money )&&($couleur == 1))
            {
            $ret["selectable"][]= 'poignee_'.$porte.'_'.$this->player_id;
            }
        }

        $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
        if($credit<3)
        {
            $newcredit = $credit +1;
            $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
        }


        
        $ret['buttons'][]="pass";
        $ret['buttons'][]="undo";
        
        
        return $ret;
     }
    
    public function Porte18($parg1, $parg2, $varg1, $varg2)
    {
        if ($varg1 == 'pass')
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPendingTarget($this->player_id, "Board".$this->player_hotel, 'Porte18', 18);
            highseason::$instance->addPending($this->player_id, "Credit");

        }
        
        else
        {

            $explode = explode("_", $varg1);
            $porte = intval($explode[1]);

            if (($porte >=3)&&($porte<=5))
             {
                self::DbQuery( "UPDATE `hotel` set `etat` = 3 WHERE `player_id` = {$this->player_id} AND `porte` = {$porte}" );
                
             }
            else
            {
                self::DbQuery( "UPDATE `hotel` set `etat` = 2 WHERE `player_id` = {$this->player_id} AND `porte` = {$porte}" );
                

            }

            highseason::$instance->notifyAllPlayers('occupying',clienttranslate( '${player_name} occupies the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte,
                
                )
                );

            $niveau = intval(self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}"));
            
            $spend = $niveau - 2;
            if ($spend > 0)
            {
                highseason::$instance->SpendMoney($spend);
            }
            
            if (($porte >=1)&&($porte<=7))
            {
                self::DbQuery( "UPDATE `player` set `vpligne1` = `vpligne1` +1  WHERE `player_id` = {$this->player_id}" );
            }
            if (($porte >=8)&&($porte<=14))
            {
                self::DbQuery( "UPDATE `player` set `vpligne2` = `vpligne2` +2  WHERE `player_id` = {$this->player_id}" );
            }
            if (($porte >=15)&&($porte<=21))
            {
                self::DbQuery( "UPDATE `player` set `vpligne3` = `vpligne3` +3  WHERE `player_id` = {$this->player_id}" );
            }
            if (($porte >=22)&&($porte<=28))
            {
                self::DbQuery( "UPDATE `player` set `vpligne4` = `vpligne4` +4  WHERE `player_id` = {$this->player_id}" );
            }
            
            highseason::$instance->BonusEtage($porte);
            highseason::$instance->BonusGroupe($porte);
            highseason::$instance->Score();

            if($porte >= 8)

            {
                self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('hotel', 'Porte', {$porte})");
            }
       
        
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");

        }
    }

    public function argPorte19($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');

        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';
      
        $staffs = self::getObjectListFromDB( "SELECT `pos` `pos`, `prix` `prix` FROM `staff` WHERE `player_id` = {$this->player_id} AND `etat` = 0");
        $reduction = 2;

        foreach ($staffs as $staff)
        {
            if ($staff['prix'] <= $this->player_money + $reduction)
            {
                $ret["selectable"][] = 'staff_'.$staff['pos'].'_'.$this->player_id;
            }
        }

        $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
        if($credit<3)
        {
            $newcredit = $credit +1;
            $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
        }
        
        $ret['buttons'][]="pass";
        $ret['buttons'][]="undo";
        
        return $ret;
     }
    
    public function Porte19($parg1, $parg2, $varg1, $varg2)
    {
        
        if ($varg1 == 'pass')
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPendingTarget($this->player_id, "Board".$this->player_hotel, 'Porte19', 19);
            highseason::$instance->addPending($this->player_id, "Credit");

        }

        else
        {

            $explode = explode("_", $varg1);
            $pos = intval($explode[1]);

            self::DbQuery( "UPDATE `staff` set `etat` = 1 WHERE `player_id` = {$this->player_id} AND `pos` = {$pos}" );

            highseason::$instance->notifyAllPlayers('gainstaff',clienttranslate( '${player_name} hires staff' ), array(
                
                'pos' =>  $pos,
                'player_name' => $this->player_name, 
                'id' => $this->player_id,
                                
                )
                );

            $prixstaff = self::getUniqueValueFromDB("SELECT `prix` FROM `staff` WHERE `player_id`={$this->player_id} AND `pos` = {$pos}");
            $reduction = 2;
            $money = $prixstaff - $reduction;
            if ($money>=1)
            {
                highseason::$instance->SpendMoney($money);
            }
            
            highseason::$instance->Score();

            $typestaff = self::getUniqueValueFromDB("SELECT `type` FROM `staff` WHERE `player_id`={$this->player_id} AND `pos` = {$pos}");
            highseason::$instance->addPendingTarget($this->player_id, "Staff", "Staff".$typestaff);

        }
    }

    public function argPorte20($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');

        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';
      
        //$ret['buttons'][]="pass";
        
        return $ret;
     }
    
    public function Porte20($parg1, $parg2, $varg1, $varg2)
    {
        
        highseason::$instance->GainMoney(2); 
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
    }

    public function argPorte21($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');

        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';
      
        $porteprepare = self::getObjectListFromDB( "SELECT `porte` FROM `hotel` WHERE `etat` = 1 AND `player_id` = {$this->player_id}", true );
        
        foreach ($porteprepare as $porte)
        {
            $niveau = intval(self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}"));
            $couleur = self::getUniqueValueFromDB("SELECT `couleur` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
            $cout = $niveau - 1;
            if ($cout <= $this->player_money )
            {
            $ret["selectable"][]= 'poignee_'.$porte.'_'.$this->player_id;
            }
        }

        $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
        if($credit<3)
        {
            $newcredit = $credit +1;
            $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
        }


        
        $ret['buttons'][]="pass";
        $ret['buttons'][]="undo";
        
        
        return $ret;
     }
    
    public function Porte21($parg1, $parg2, $varg1, $varg2)
    {
        if ($varg1 == 'pass')
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPendingTarget($this->player_id, "Board".$this->player_hotel, 'Porte21', 21);
            highseason::$instance->addPending($this->player_id, "Credit");

        }
        
        else
        {

            $explode = explode("_", $varg1);
            $porte = intval($explode[1]);

            if (($porte >=3)&&($porte<=5))
             {
                self::DbQuery( "UPDATE `hotel` set `etat` = 3 WHERE `player_id` = {$this->player_id} AND `porte` = {$porte}" );
                
             }
            else
            {
                self::DbQuery( "UPDATE `hotel` set `etat` = 2 WHERE `player_id` = {$this->player_id} AND `porte` = {$porte}" );
                

            }

            highseason::$instance->notifyAllPlayers('occupying',clienttranslate( '${player_name} occupies the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte,
                
                )
                );

            $niveau = intval(self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}"));
            
            $spend = $niveau - 1;
            if ($spend > 0)
            {
                highseason::$instance->SpendMoney($spend);
            }
            
            if (($porte >=1)&&($porte<=7))
            {
                self::DbQuery( "UPDATE `player` set `vpligne1` = `vpligne1` +1  WHERE `player_id` = {$this->player_id}" );
            }
            if (($porte >=8)&&($porte<=14))
            {
                self::DbQuery( "UPDATE `player` set `vpligne2` = `vpligne2` +2  WHERE `player_id` = {$this->player_id}" );
            }
            if (($porte >=15)&&($porte<=21))
            {
                self::DbQuery( "UPDATE `player` set `vpligne3` = `vpligne3` +3  WHERE `player_id` = {$this->player_id}" );
            }
            if (($porte >=22)&&($porte<=28))
            {
                self::DbQuery( "UPDATE `player` set `vpligne4` = `vpligne4` +4  WHERE `player_id` = {$this->player_id}" );
            }
            
            highseason::$instance->BonusEtage($porte);
            highseason::$instance->BonusGroupe($porte);
            highseason::$instance->Score();

            if($porte >= 8)

            {
                self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('hotel', 'Porte', {$porte})");
            }
       
        
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");

        }
    }

    public function argPorte22($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');

        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';
      
        $staffs = self::getObjectListFromDB( "SELECT `pos` `pos`, `prix` `prix` FROM `staff` WHERE `player_id` = {$this->player_id} AND `etat` = 0");
        //$reduction = 2;

        foreach ($staffs as $staff)
        {
            //if ($staff['prix'] <= $this->player_money + $reduction)
            //{
                $ret["selectable"][] = 'staff_'.$staff['pos'].'_'.$this->player_id;
            //}
        }

        $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
        if($credit<3)
        {
            $newcredit = $credit +1;
            $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
        }
        
        $ret['buttons'][]="pass";
        $ret['buttons'][]="undo";
        
        return $ret;
     }
    
    public function Porte22($parg1, $parg2, $varg1, $varg2)
    {
        
        if ($varg1 == 'pass')
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPendingTarget($this->player_id, "Board".$this->player_hotel, 'Porte22', 22);
            highseason::$instance->addPending($this->player_id, "Credit");

        }

        else
        {

            $explode = explode("_", $varg1);
            $pos = intval($explode[1]);

            self::DbQuery( "UPDATE `staff` set `etat` = 1 WHERE `player_id` = {$this->player_id} AND `pos` = {$pos}" );

            highseason::$instance->notifyAllPlayers('gainstaff',clienttranslate( '${player_name} hires staff' ), array(
                
                'pos' =>  $pos,
                'player_name' => $this->player_name, 
                'id' => $this->player_id,
                                
                )
                );

            /*$prixstaff = self::getUniqueValueFromDB("SELECT `prix` FROM `staff` WHERE `player_id`={$this->player_id} AND `pos` = {$pos}");
            $reduction = 2;
            $money = $prixstaff - $reduction;
            if ($money>=1)
            {
                highseason::$instance->SpendMoney($money);
            }*/
            
            highseason::$instance->Score();

            $typestaff = self::getUniqueValueFromDB("SELECT `type` FROM `staff` WHERE `player_id`={$this->player_id} AND `pos` = {$pos}");
            highseason::$instance->addPendingTarget($this->player_id, "Staff", "Staff".$typestaff);

        }
    }

    public function argPorte23($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');

        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';
      
        $porteprepare = self::getObjectListFromDB( "SELECT `porte` FROM `hotel` WHERE `etat` = 1 AND `player_id` = {$this->player_id}", true );
        
        foreach ($porteprepare as $porte)
        {
            $niveau = intval(self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}"));
            $couleur = self::getUniqueValueFromDB("SELECT `couleur` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
            $cout = $niveau - 2;
            if ($cout <= $this->player_money )
            {
            $ret["selectable"][]= 'poignee_'.$porte.'_'.$this->player_id;
            }
        }

        $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
        if($credit<3)
        {
            $newcredit = $credit +1;
            $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
        }


        
        $ret['buttons'][]="pass";
        $ret['buttons'][]="undo";
        
        
        return $ret;
     }
    
    public function Porte23($parg1, $parg2, $varg1, $varg2)
    {
        if ($varg1 == 'pass')
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPendingTarget($this->player_id, "Board".$this->player_hotel, 'Porte23', 23);
            highseason::$instance->addPending($this->player_id, "Credit");

        }
        
        else
        {

            $explode = explode("_", $varg1);
            $porte = intval($explode[1]);

            if (($porte >=3)&&($porte<=5))
             {
                self::DbQuery( "UPDATE `hotel` set `etat` = 3 WHERE `player_id` = {$this->player_id} AND `porte` = {$porte}" );
                
             }
            else
            {
                self::DbQuery( "UPDATE `hotel` set `etat` = 2 WHERE `player_id` = {$this->player_id} AND `porte` = {$porte}" );
                

            }

            highseason::$instance->notifyAllPlayers('occupying',clienttranslate( '${player_name} occupies the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte,
                
                )
                );

            $niveau = intval(self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}"));
            
            $spend = $niveau - 2;
            if ($spend > 0)
            {
                highseason::$instance->SpendMoney($spend);
            }
            
            if (($porte >=1)&&($porte<=7))
            {
                self::DbQuery( "UPDATE `player` set `vpligne1` = `vpligne1` +1  WHERE `player_id` = {$this->player_id}" );
            }
            if (($porte >=8)&&($porte<=14))
            {
                self::DbQuery( "UPDATE `player` set `vpligne2` = `vpligne2` +2  WHERE `player_id` = {$this->player_id}" );
            }
            if (($porte >=15)&&($porte<=21))
            {
                self::DbQuery( "UPDATE `player` set `vpligne3` = `vpligne3` +3  WHERE `player_id` = {$this->player_id}" );
            }
            if (($porte >=22)&&($porte<=28))
            {
                self::DbQuery( "UPDATE `player` set `vpligne4` = `vpligne4` +4  WHERE `player_id` = {$this->player_id}" );
            }
            
            highseason::$instance->BonusEtage($porte);
            highseason::$instance->BonusGroupe($porte);
            highseason::$instance->Score();

            if($porte >= 8)

            {
                self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('hotel', 'Porte', {$porte})");
            }
       
        
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");

        }
    }

    public function argPorte24($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');

        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';
      
        //$ret['buttons'][]="pass";
        
        return $ret;
     }
    
    public function Porte24($parg1, $parg2, $varg1, $varg2)
    {
        highseason::$instance->GainEmperor(2);
        highseason::$instance->GainMoney(1); 
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
    }

    public function argPorte25($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');

        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';
      
        //$ret['buttons'][]="pass";
        
        return $ret;
     }
    
    public function Porte25($parg1, $parg2, $varg1, $varg2)
    {
        highseason::$instance->GainEmperor(1);
        highseason::$instance->GainMoney(2); 
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
    }

    public function argPorte26($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');

        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';
      
        //$ret['buttons'][]="pass";
        
        return $ret;
     }
    
    public function Porte26($parg1, $parg2, $varg1, $varg2)
    {
        
        highseason::$instance->GainMoney(3); 
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
    }

    public function argPorte27($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');

        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';
      
        //$ret['buttons'][]="pass";
        
        return $ret;
     }
    
    public function Porte27($parg1, $parg2, $varg1, $varg2)
    {
        highseason::$instance->GainEmperor(2);
        highseason::$instance->GainMoney(1); 
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
    }

    public function argPorte28($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} can trigger a room bonus');
        $ret['titleyou'] = clienttranslate('${you} must perform the bonus action of the room #icon# or');

        
        $ret['icon'] = '&nbsp;<div class="iconboard'.$this->player_hotel.' iconboardpos'.$parg1.'"></div> &nbsp;';
      
        $porteadajacente = highseason::$instance->getPorteAdjacente(); 
        foreach ($porteadajacente as $porte)
        {
            $niveau = self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
            $couleur = self::getUniqueValueFromDB("SELECT `couleur` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
            if((($this->player_staff1 == 8)||($this->player_staff2 == 8))&&($couleur == 2))
            {
                $prix = 0;
            }
            elseif((($this->player_staff1 == 19)||($this->player_staff2 == 19))&&($couleur == 1))
            {
                $prix = 0;
            }
            elseif((($this->player_staff1 == 24)||($this->player_staff2 == 24))&&($couleur == 3))
            {
                $prix = 0;
            }
            else
            {
                $prix = $niveau -2;
            }
            
            if ($prix <= $this->player_money )
            {
            $ret["selectable"][]= 'poignee_'.$porte.'_'.$this->player_id;
            }
        }

        $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
        if($credit<3)
        {
            $newcredit = $credit +1;
            $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
        }

        
        $ret['buttons'][]="pass";
        $ret['buttons'][]="undo";
        
        return $ret;
     }
    
    public function Porte28($parg1, $parg2, $varg1, $varg2)
    {
        
        if ($varg1 == 'pass')
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPendingTarget($this->player_id, "Board".$this->player_hotel, 'Porte28', 28);
            highseason::$instance->addPending($this->player_id, "Credit");

        }

        else
        {

        
        $explode = explode("_", $varg1);
            $porte = intval($explode[1]);

            self::DbQuery( "UPDATE `hotel` set `etat` = 1 WHERE `player_id` = {$this->player_id} AND `porte` = {$porte}" );

            highseason::$instance->notifyAllPlayers('preparing',clienttranslate( '${player_name} prepares the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte, 
                
                )
                );

                $niveau = self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
                $couleur = self::getUniqueValueFromDB("SELECT `couleur` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}");
    
                if((($this->player_staff1 == 8)||($this->player_staff2 == 8))&&($couleur == 2))
                {
                    
                }
                elseif((($this->player_staff1 == 19)||($this->player_staff2 == 19))&&($couleur == 1))
                {
                    
                }
                elseif((($this->player_staff1 == 24)||($this->player_staff2 == 24))&&($couleur == 3))
                {
                    
                }
                else
                {
                    
                    if ($niveau == 3)
                    {
                        highseason::$instance->SpendMoney(1);
                        highseason::$instance->Score();
                    }
                    if ($niveau == 4)
                    {
                        highseason::$instance->SpendMoney(2);
                        highseason::$instance->Score();
                    }
                }
        
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");

        }
    }

    
}