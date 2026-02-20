<?php
class Pending extends \APP_DbObject
{
    public function __construct($player_id)
    {
        $this->player_id = $player_id;
        $p = self::getObjectFromDB("SELECT * FROM `player` WHERE `player_id` = {$player_id}");        
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
        $this->player_staff1 = intval(self::getUniqueValueFromDB("SELECT `type` FROM `staff` WHERE `player_id`={$this->player_id} AND `pos` = 1 AND `etat` = 1"));
        $this->player_staff2 = intval(self::getUniqueValueFromDB("SELECT `type` FROM `staff` WHERE `player_id`={$this->player_id} AND `pos` = 2 AND `etat` = 1"));



         /*
            AIDE:
            
            highseason::$instance->GainMoney(1); 
            
            highseason::$instance->SpendMoney(1);

            highseason::$instance->ReduceDice(6);

            highseason::$instance->GainEmperor(1);

            highseason::$instance->BonusEtage($porte);
            highseason::$instance->BonusGroupe($porte);
            highseason::$instance->Score();
            
        */


    }

    function argDice($parg1, $parg2)
    {
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer}');
        $ret['titleyou'] = clienttranslate('${you}');
        
                
        return $ret;
    }

    function Dice($parg1, $parg2, $varg1, $varg2)
    {
            highseason::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1000] );

            $dice1 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 1", true ));
            $dice2 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 2", true ));
            $dice3 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 3", true ));
            $dice4 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 4", true ));
            $dice5 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 5", true ));
            $dice6 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 6", true ));

            highseason::$instance->notifyAllPlayers('destroydice','', array(
                'dice1' =>  $dice1,
                'dice2' =>  $dice2,
                'dice3' =>  $dice3,
                'dice4' =>  $dice4,
                'dice5' =>  $dice5,
                'dice6' =>  $dice6,
                              
                
                )
                );

            highseason::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1000] );


        
            $nbreplayer = count(self::getObjectListFromDB( "SELECT `player_id` FROM `player`", true ));
            self::DbQuery( "DELETE FROM `dice`");
            $des = 0;
            if($nbreplayer==2)
            {
                $des = 10;
            }
            if($nbreplayer==3)
            {
                $des = 12;
            }
            if($nbreplayer==4)
            {
                $des = 14;
            }
            for ($d =1; $d<=$des ;$d++)
            {
            $randdice = bga_rand(1,6);
            self::DbQuery( "INSERT INTO `dice` (`valeur`) VALUES ($randdice)");
            }

            $dice1 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 1", true ));
            $dice2 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 2", true ));
            $dice3 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 3", true ));
            $dice4 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 4", true ));
            $dice5 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 5", true ));
            $dice6 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 6", true ));

            highseason::$instance->notifyAllPlayers('dice',clienttranslate( '${player_name} becomes the first player and rolls the dice for the round' ), array(
                'player_name' => $this->player_name,
                'dice1' =>  $dice1,
                'dice2' =>  $dice2,
                'dice3' =>  $dice3,
                'dice4' =>  $dice4,
                'dice5' =>  $dice5,
                'dice6' =>  $dice6,
                'playerid' => $this->player_id,
                
                
                )
                );

            highseason::$instance->notifyAllPlayers('message',clienttranslate('${dice1}x${d1} ${dice2}x${d2} ${dice3}x${d3} ${dice4}x${d4} ${dice5}x${d5} ${dice6}x${d6}'), array(
                'dice1' =>  $dice1,
                'dice2' =>  $dice2,
                'dice3' =>  $dice3,
                'dice4' =>  $dice4,
                'dice5' =>  $dice5,
                'dice6' =>  $dice6,
                'd1' => highseason::$instance->getLogsDice(1),
                'd2' => highseason::$instance->getLogsDice(2),
                'd3' => highseason::$instance->getLogsDice(3),
                'd4' => highseason::$instance->getLogsDice(4),
                'd5' => highseason::$instance->getLogsDice(5),
                'd6' => highseason::$instance->getLogsDice(6),
                
                
                
                )
                );

            highseason::$instance->notifyAllPlayers( 'simplePause', '', [ 'time' => 1000] );

                
       
    }

    function argNormalTurn($parg1, $parg2)
    {
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer}');
        $ret['titleyou'] = clienttranslate('${you}');
        
        
        return $ret;
    }

    function NormalTurn($parg1, $parg2, $varg1, $varg2)
    {
        


        highseason::$instance->setGameStateValue('credit', 0);

        $dice1 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 1", true ));
        highseason::$instance->setGameStateValue('preparing', 1);
        highseason::$instance->setGameStateValue('preparingmax', $dice1);
        $dice3 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 3", true ));
        highseason::$instance->setGameStateValue('preparing3', 1);
        highseason::$instance->setGameStateValue('preparingmax3', $dice3);

        if(($this->player_staff1==13)||($this->player_staff2 ==13))
        {
            $a = highseason::$instance->getGameStateValue('preparingmax3');
            $a = $a +1;
            highseason::$instance->setGameStateValue('preparingmax3', $a);
                
        }

        highseason::$instance->setGameStateValue('preparing61', 1);
        highseason::$instance->setGameStateValue('preparing63', 1);
        highseason::$instance->setGameStateValue('lvlaction6', 0);

        $findepartie = 0;
        $changementround = 0;

        

        $turn = intval(self::getUniqueValueFromDB("SELECT `turn` FROM `player` WHERE `player_id`={$this->player_id}"));

        if ($turn == 2)
        {
            
            $r = highseason::$instance->getGameStateValue('round');

            if($r == 7)
            {
                $findepartie = 1;
                $listeplayers = self::getObjectListFromDB( "SELECT `player_id` `id` FROM `player`", true );
                /////////////// TESTER LES MALUS EMPEROR   7 /////////////
                foreach ($listeplayers as $player)
                {
                    $niveau = intval(self::getUniqueValueFromDB("SELECT `emperor3` FROM `player` WHERE `player_id` = {$player}"));
                    $name = self::getUniqueValueFromDB("SELECT `player_name` FROM `player` WHERE `player_id` = {$player}");

                    if($niveau <=4)
                    {
                        self::DbQuery( "UPDATE `player` set `vpemperor` = `vpemperor` -4 WHERE `player_id` = {$player}" );
                        self::DbQuery( "UPDATE `player` set `malussemperor3` = 1 WHERE `player_id` = {$player}" );

                        highseason::$instance->notifyAllPlayers('gainmalusemperor',clienttranslate( '${player_name} takes the penalty of 4 on the Emperor\'s track' ), array(
                            'player_name' => $name,
                            'id'=> $player,
                            'track' => 3,
                                        
                            )
                            );
                        
                    }
                }

                highseason::$instance->Score();

                
                self::DbQuery( "DELETE FROM `pending`"); ////// DECLENCHER FIN DE PARTIE /////
            }

            if($r < 7)
            {
             
            $countplayer = count(self::getObjectListFromDB( "SELECT `player_id` FROM `player`", true ));
            $listeplayers = self::getObjectListFromDB( "SELECT `player_id` `id` FROM `player`", true );

            self::DbQuery( "UPDATE `player` SET `turn` = 0 ");   
            highseason::$instance->setGameStateValue('turn', 0);
            $r = $r+1;
            highseason::$instance->setGameStateValue('round', $r);
            $changementround = 1;

            /////////////// TESTER LES MALUS EMPEROR  AU PASSAGE 4 et 6 /////////////

            if ($r == 4)
            {
                foreach ($listeplayers as $player)
                {
                    $niveau = intval(self::getUniqueValueFromDB("SELECT `emperor1` FROM `player` WHERE `player_id` = {$player}"));
                    $name = self::getUniqueValueFromDB("SELECT `player_name` FROM `player` WHERE `player_id` = {$player}");

                    if($niveau <=3)
                    {
                        self::DbQuery( "UPDATE `player` set `vpemperor` = `vpemperor` -2 WHERE `player_id` = {$player}" );
                        self::DbQuery( "UPDATE `player` set `malussemperor1` = 1 WHERE `player_id` = {$player}" );

                        highseason::$instance->notifyAllPlayers('gainmalusemperor',clienttranslate( '${player_name} takes the penalty of 2 on the Emperor\'s track' ), array(
                            'player_name' => $name,
                            'id'=> $player,
                            'track' => 1,
                                        
                            )
                            );
                        
                    }
                }

                highseason::$instance->Score();
            }

            if ($r == 6)
            {
                foreach ($listeplayers as $player)
                {
                    $niveau = intval(self::getUniqueValueFromDB("SELECT `emperor2` FROM `player` WHERE `player_id` = {$player}"));
                    $name = self::getUniqueValueFromDB("SELECT `player_name` FROM `player` WHERE `player_id` = {$player}");

                    if($niveau <=3)
                    {
                        self::DbQuery( "UPDATE `player` set `vpemperor` = `vpemperor` -3 WHERE `player_id` = {$player}" );
                        self::DbQuery( "UPDATE `player` set `malussemperor2` = 1 WHERE `player_id` = {$player}" );

                        highseason::$instance->notifyAllPlayers('gainmalusemperor',clienttranslate( '${player_name} takes the penalty of 3 on the Emperor\'s track' ), array(
                            'player_name' => $name,
                            'id'=> $player,
                            'track' => 2,
                                        
                            )
                            );
                        
                    }
                }

                highseason::$instance->Score();
            }


            
            highseason::$instance->notifyAllPlayers('round',clienttranslate( 'Round ${r}' ), array(
                'r' => $r,
                'round'=> $r,
                'players'=> $listeplayers, 

                )
                );

            if($r == 7)
            {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( 'Last Round' ), array(
                
                )
                );
            }

            
            self::DbQuery( "DELETE FROM `pending`");
            self::DbQuery( "UPDATE `player` SET `first` = 0 ");  
            if ($countplayer == 2)
            {
                $playerid1 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 1"));
                $playerid2 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 2"));

                if ($this->player_no == 1)
                {
                    
                    self::DbQuery( "UPDATE `player` SET `first` = 1 WHERE `player_no` = 2 ");
                    highseason::$instance->addPendingFirst($playerid2, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid1, "NormalTurn");
                    highseason::$instance->addPending($playerid2, "Dice");
    
                }

                if ($this->player_no == 2)
                {
                    self::DbQuery( "UPDATE `player` SET `first` = 1 WHERE `player_no` = 1 "); 
                    highseason::$instance->addPendingFirst($playerid1, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid2, "NormalTurn");
                    highseason::$instance->addPending($playerid1, "Dice");
                }

            }

            if ($countplayer == 3)
            {
                $playerid1 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 1"));
                $playerid2 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 2"));
                $playerid3 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 3"));
                
                if ($this->player_no == 1)
                {
                    
                    self::DbQuery( "UPDATE `player` SET `first` = 1 WHERE `player_no` = 2 ");
                    highseason::$instance->addPendingFirst($playerid2, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid3, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid1, "NormalTurn");
                    highseason::$instance->addPending($playerid2, "Dice");
    
                }

                if ($this->player_no == 2)
                {
                    self::DbQuery( "UPDATE `player` SET `first` = 1 WHERE `player_no` = 3 "); 
                    highseason::$instance->addPendingFirst($playerid3, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid1, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid2, "NormalTurn");
                    highseason::$instance->addPending($playerid3, "Dice");
                }

                if ($this->player_no == 3)
                {
                    self::DbQuery( "UPDATE `player` SET `first` = 1 WHERE `player_no` = 1 "); 
                    highseason::$instance->addPendingFirst($playerid1, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid2, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid3, "NormalTurn");
                    highseason::$instance->addPending($playerid1, "Dice");
                }
            }

            if ($countplayer == 4)
            {
                $playerid1 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 1"));
                $playerid2 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 2"));
                $playerid3 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 3"));
                $playerid4 = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `player_no` = 4"));
                
                if ($this->player_no == 1)
                {
                    
                    self::DbQuery( "UPDATE `player` SET `first` = 1 WHERE `player_no` = 2 ");
                    highseason::$instance->addPendingFirst($playerid2, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid3, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid4, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid1, "NormalTurn");
                    highseason::$instance->addPending($playerid2, "Dice");
    
                }

                if ($this->player_no == 2)
                {
                    self::DbQuery( "UPDATE `player` SET `first` = 1 WHERE `player_no` = 3 "); 
                    highseason::$instance->addPendingFirst($playerid3, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid4, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid1, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid2, "NormalTurn");
                    highseason::$instance->addPending($playerid3, "Dice");
                }

                if ($this->player_no == 3)
                {
                    self::DbQuery( "UPDATE `player` SET `first` = 1 WHERE `player_no` = 4 "); 
                    highseason::$instance->addPendingFirst($playerid4, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid1, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid2, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid3, "NormalTurn");
                    highseason::$instance->addPending($playerid4, "Dice");
                }

                if ($this->player_no == 4)
                {
                    self::DbQuery( "UPDATE `player` SET `first` = 1 WHERE `player_no` = 1 "); 
                    highseason::$instance->addPendingFirst($playerid1, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid2, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid3, "NormalTurn");
                    highseason::$instance->addPendingFirst($playerid4, "NormalTurn");
                    highseason::$instance->addPending($playerid1, "Dice");
                }
                
            }

            
            $firstplayer = intval(self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE `first` = 1"));
            highseason::$instance->notifyAllPlayers('changementfirst','', array(
                'first' => $firstplayer,

                )
                );  
                
               
            
            }

            
        }
        
        
        if(($findepartie == 0)&&($changementround == 0))
        {
            $turn = intval(self::getUniqueValueFromDB("SELECT `turn` FROM `player` WHERE `player_id`={$this->player_id}"));

            

            if ($turn <=1)
            {
                self::DbQuery( "UPDATE `player` SET `turn` = `turn` +1 WHERE `player_id` = {$this->player_id}");
                $first = intval(self::getUniqueValueFromDB("SELECT `first` FROM `player` WHERE `player_id`={$this->player_id}"));
                
                if($first == 1)
                {
                    if($turn == 0)
                    {
                    highseason::$instance->setGameStateValue('turn', 1);
                    highseason::$instance->notifyAllPlayers('turn',clienttranslate( 'Turn 1' ), array(
                        'turn' => 1,                       
                        )
                        );
                    }
                    if($turn == 1)
                    {
                    highseason::$instance->setGameStateValue('turn', 2);
                    highseason::$instance->notifyAllPlayers('turn',clienttranslate( 'Turn 2' ), array(
                        'turn' => 2,                    
                        )
                        );
                    }
                    

                }
            }

            
            
            highseason::$instance->addPending($this->player_id, "Save");

        }
   
        
    }

    function argSave($parg1, $parg2)
    {
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose an action');
        
        
        
        return $ret;
    }

    function Save($parg1, $parg2, $varg1, $varg2)
    {
        highseason::$instance->undoSavepoint();
        highseason::$instance->addPending($this->player_id, "TakeAction");
        
    }

    function argTakeAction($parg1, $parg2)
    {
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose an action');
        
        $nombrestaff = count(self::getObjectListFromDB( "SELECT `id` FROM `staff` WHERE `etat` = 1 AND `player_id` = {$this->player_id}", true ));
        $nbreporteprepare = count(self::getObjectListFromDB( "SELECT `porte` FROM `hotel` WHERE `etat` = 1 AND `player_id` = {$this->player_id}", true ));
        for ($d=1; $d<=6; $d++)
        {
            $dice = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = {$d}", true ));
            if($dice !=0)
            {
                if($d ==2)
                {
                    if ($nbreporteprepare>=1)
                    {
                        $ret["selectable"][] = 'action_'.$d;
                    }
                }

                elseif($d ==5)
                {
                    
                    if ($nombrestaff <6)
                    {
                        $ret["selectable"][] = 'action_'.$d;
                    }
                    
                }

                elseif($d ==6)
                {
                    if(($this->player_staff1==2)||($this->player_staff2 ==2))
                    {
                        $ret["selectable"][] = 'action_'.$d;
                    }

                    else
                    {
                        if ($this->player_money >= 1)
                        {
                            $ret["selectable"][] = 'action_'.$d;
                        }
                    }
                    
                }
                else
                {
                    $ret["selectable"][] = 'action_'.$d;
                }
                
            }
        }

        $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
        if($credit<3)
        {
            $newcredit = $credit +1;
            $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
        }
               
        
        if (highseason::$instance->getGameStateValue('credit') == 0)
        {
            if($credit<3)
            {
            $ret['titleyou'] = clienttranslate('${you} must choose an action (or Pass: Discard a die and Gain 1 krone) (You can take out a loan)');
            }
            if($credit>=3)
            {
            $ret['titleyou'] = clienttranslate('${you} must choose an action (or Pass: Discard a die and Gain 1 krone)');
            }
            $ret['buttons'][]='pass';
        }

        if(highseason::$instance->getGameStateValue('credit') > 0)
        {
            if($credit<3)
            {
            $ret['titleyou'] = clienttranslate('${you} must choose an action (You can take out a loan)');
            }
            if($credit>=3)
            {
            $ret['titleyou'] = clienttranslate('${you} must choose an action');
            }
            $ret['buttons'][]='undo';
        }

        

        //$ret['buttons'][]='relancedes';  /////////////A ENLEVER!!!!!!!!!!!!!!!!!!!
        return $ret;
    }

    function TakeAction($parg1, $parg2, $varg1, $varg2)
    {
        self::DbQuery( "DELETE FROM `multiaction`");
        
        

        if($varg1 == "pass")
        {
            
            highseason::$instance->addPending($this->player_id, "Discard");
        }

        elseif($varg1 == "action_1")
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} chooses ${d}' ), array(
                'player_name' => $this->player_name, 
                'd' => highseason::$instance->getLogsDice(1),
                
                )
                );

            if(($this->player_staff1==7)||($this->player_staff2 ==7))
            {
                highseason::$instance->GainMoney(2); 
                
                  
            }
            self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('dice', 'Preparing', 1)");
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif($varg1 == "action_2")
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} chooses ${d}' ), array(
                'player_name' => $this->player_name, 
                'd' => highseason::$instance->getLogsDice(2),
                
                )
                );

            if(($this->player_staff1==1)||($this->player_staff2 ==1))
            {
                highseason::$instance->GainMoney(1); 
                highseason::$instance->GainEmperor(1);
                  
            }
            
            self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('dice', 'Occupying', 2)");
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif($varg1 == "action_3")
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} chooses ${d}' ), array(
                'player_name' => $this->player_name, 
                'd' => highseason::$instance->getLogsDice(3),
                
                )
                );
            highseason::$instance->addPending($this->player_id, "TakeAction3");
        }

        elseif($varg1 == "action_4")
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} chooses ${d}' ), array(
                'player_name' => $this->player_name, 
                'd' => highseason::$instance->getLogsDice(4),
                
                )
                );
            highseason::$instance->addPending($this->player_id, "TakeAction4");
        }

        elseif($varg1 == "action_5")
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} chooses ${d}' ), array(
                'player_name' => $this->player_name, 
                'd' => highseason::$instance->getLogsDice(5),
                
                )
                );
            self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('dice', 'ActionStaff', 5)");
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif($varg1 == "action_6")
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} chooses ${d}' ), array(
                'player_name' => $this->player_name, 
                'd' => highseason::$instance->getLogsDice(6),
                
                )
                );

            if(($this->player_staff1==2)||($this->player_staff2 ==2))
            {
                highseason::$instance->GainMoney(1); 
                                  
            }
            else
            {
            highseason::$instance->SpendMoney(1);
            }
            self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('dice', 'Imitate', 6)");
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        elseif (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPending($this->player_id, "TakeAction");
            highseason::$instance->addPending($this->player_id, "Credit");

        }

        

        
  
        
    }

    function argDiscard($parg1, $parg2)
    {
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose an action');
        $ret['titleyou'] = clienttranslate('${you} must choose the die to discard');
        
        for ($d=1; $d<=6; $d++)
        {
            $dice = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = {$d}", true ));
            if($dice !=0)
            {
                $ret["selectable"][] = 'action_'.$d;
            }
        }
        
        $ret['buttons'][]='cancel';
        return $ret;
    }

    function Discard($parg1, $parg2, $varg1, $varg2)
    {

        if($varg1 == "cancel")
        {
            highseason::$instance->addPending($this->player_id, "TakeAction");
        }

        else
        {
                       
            $explode = explode("_", $varg1);
            $dice = intval($explode[1]);

            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} passes and discards ${d}' ), array(
                'player_name' => $this->player_name,
                'd' => highseason::$instance->getLogsDice($dice),
                
                )
                );

            highseason::$instance->GainMoney(1);
            highseason::$instance->ReduceDice($dice);
            highseason::$instance->addPendingFirst($this->player_id, "NormalTurn");


        }


        
  
        
    }

    function argTakeAction3($parg1, $parg2)
    {
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose an action');
        

        $ret["selected"][] = 'action_3';
        $nbreporteprepare = count(self::getObjectListFromDB( "SELECT `porte` FROM `hotel` WHERE `etat` = 1 AND `player_id` = {$this->player_id}", true ));

        $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
        if($credit<3)
        {
            $newcredit = $credit +1;
            $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
        }

        if ($credit<3)
        {
            $ret['titleyou'] = clienttranslate('${you} must choose an action (You can take out a loan)');
        }
        if ($credit >=3)
        {
            $ret['titleyou'] = clienttranslate('${you} must choose an action');
        }

        $ret['buttons'][] = 'dice_31';
        if($nbreporteprepare >=1)
        {
        $ret['buttons'][] = 'dice_32';
        }
        $ret['buttons'][]='undo';
        return $ret;
    }

    function TakeAction3($parg1, $parg2, $varg1, $varg2)
    {
        

        if (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPending($this->player_id, "TakeAction3");
            highseason::$instance->addPending($this->player_id, "Credit");

        }

        if($varg1 == "dice_31")
        {
            if(($this->player_staff1==13)||($this->player_staff2 ==13))
            {
                highseason::$instance->GainEmperor(1);
                  
            }
            self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('dice', 'Preparing3', 31)");
            
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");

        }

        if($varg1 == "dice_32")
        {
            if(($this->player_staff1==13)||($this->player_staff2 ==13))
            {
                highseason::$instance->GainEmperor(1);
                  
            }
            self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('dice', 'Occupying3', 32)");
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        
        
        
    }

    function argTakeAction4($parg1, $parg2)
    {
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose an action');
        

        $ret["selected"][] = 'action_4';

        $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
        if($credit<3)
        {
            $newcredit = $credit +1;
            $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
        }

        if ($credit<3)
        {
            $ret['titleyou'] = clienttranslate('${you} must choose an action (You can take out a loan)');
        }
        if ($credit >=3)
        {
            $ret['titleyou'] = clienttranslate('${you} must choose an action');           
        }

        
        $ret['buttons'][] = 'dice_41';
        $ret['buttons'][] = 'dice_42';
        $ret['buttons'][]='undo';
        return $ret;
    }

    function TakeAction4($parg1, $parg2, $varg1, $varg2)
    {
        
        if (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPending($this->player_id, "TakeAction4");
            highseason::$instance->addPending($this->player_id, "Credit");

        }

        if($varg1 == "dice_41")
        {
            if(($this->player_staff1==27)||($this->player_staff2 ==27))
            {
                highseason::$instance->GainEmperor(1);
                highseason::$instance->GainMoney(1); 
            }
            self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('dice', 'Emperor4', 41)");
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");

        }

        if($varg1 == "dice_42")
        {
            if(($this->player_staff1==27)||($this->player_staff2 ==27))
            {
                highseason::$instance->GainEmperor(1);
                highseason::$instance->GainMoney(1); 
            }
            self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('dice', 'Krones4', 42)");
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");

           
        }
        
        
    }


    function argCheckMultiAction($parg1, $parg2)
    {
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose an action');
        

        $actions = self::getObjectListFromDB( "SELECT `type` `type`, `arg` `arg` FROM `multiaction`" );
        $count = count($actions);

        if($count >= 2)
        {
            $action0 = self::getObjectListFromDB( "SELECT `type` `type`, `arg` `arg` FROM `multiaction` WHERE `ordre` = 0" );
            $count0 = count($action0);

            if ($count0 >=2)
            {
            foreach ($action0 as $action)
            {
                
                
                if ($action['type'] == 'hotel')
                {
                
                    $ret['buttons'][] = 'iconboard'.$this->player_hotel.'_iconboardpos'.$action['arg'];
                }
                
                if ($action['type'] == 'staff')
                {
                
                    $ret['buttons'][] = $action['type'].'_'.$action['arg'];
                }

                if ($action['type'] == 'dice')
                {
                
                    $ret['buttons'][] = $action['type'].'_'.$action['arg'];
                }

                
                   

            }

            foreach ($action0 as $action)
            {
                
                

                if ($action['type'] == 'emperor')
                {
                
                    $ret['buttons'][] = 'iconemperorboard'.$this->player_hotel.'_iconemperorpos'.$action['arg'];
                }

                   

            }

            $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
            if($credit<3)
            {
                $newcredit = $credit +1;
                $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
            }

            if ($credit<3)
            {
                $ret['titleyou'] = clienttranslate('${you} can choose the order of your possible actions (You can take out a loan)');
            }
            if ($credit >=3)
            {
                $ret['titleyou'] = clienttranslate('${you} can choose the order of your possible actions');       
            }

            $ret['buttons'][]='undo';
            }

            if ($count0 == 1)
            {
                highseason::$instance->setGameStateValue('checkmultiaction', 2);
            }


        }

        if($count == 0)
        {

        highseason::$instance->setGameStateValue('checkmultiaction', 0);
        }

        if($count == 1)
        {

        highseason::$instance->setGameStateValue('checkmultiaction', 1);
        }
        
        

        
        return $ret;
    }

    function CheckMultiAction($parg1, $parg2, $varg1, $varg2)
    {

       if(($varg1 == NULL)&&(highseason::$instance->getGameStateValue('checkmultiaction') ==1))
       {
        
            $fonction = self::getUniqueValueFromDB("SELECT `action` FROM `multiaction` WHERE `ordre` = 0");
            $type = self::getUniqueValueFromDB("SELECT `type` FROM `multiaction` WHERE `ordre` = 0");
            $arg = self::getUniqueValueFromDB("SELECT `arg` FROM `multiaction` WHERE `ordre` = 0");
            self::DbQuery( "DELETE FROM `multiaction`");
            if ($type == 'dice')
            {
                highseason::$instance->addPending($this->player_id, $fonction);
            }
            if ($type == 'hotel')
            {
                highseason::$instance->addPendingTarget($this->player_id, "Board".$this->player_hotel, 'Porte'.$arg, $arg);
            }
            if ($type == 'emperor')
            {
                highseason::$instance->addPendingTarget($this->player_id, "Emperorboard".$this->player_hotel, 'Bonus'.$arg, $arg);
            }

            if ($type == 'staff')
            {
                highseason::$instance->addPendingTarget($this->player_id, "Staff", 'Staffaction'.$arg, $arg);
            }

       }

       elseif(($varg1 == NULL)&&(highseason::$instance->getGameStateValue('checkmultiaction') ==0))
       {
        highseason::$instance->addPendingFirst($this->player_id, "NormalTurn");
       }

       

        elseif (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
            highseason::$instance->addPending($this->player_id, "Credit");

        }

        elseif(($varg1 == NULL)&&(highseason::$instance->getGameStateValue('checkmultiaction') ==2))   ////seulement plusieurs actions emperor ////
        {
            $fonction = self::getUniqueValueFromDB("SELECT `action` FROM `multiaction` WHERE `ordre` = 0");
            $type = self::getUniqueValueFromDB("SELECT `type` FROM `multiaction` WHERE `ordre` = 0");
            $arg = self::getUniqueValueFromDB("SELECT `arg` FROM `multiaction` WHERE `ordre` = 0");
            highseason::$instance->addPendingTarget($this->player_id, "Emperorboard".$this->player_hotel, 'Bonus'.$arg, $arg);
            self::DbQuery( "DELETE FROM `multiaction` WHERE `ordre` = 0");
            self::DbQuery( "UPDATE `multiaction` SET `ordre` = `ordre` - 1");

        }

        else        ////1 action hotel/staff/dice et au moins 1 action emperor ////
        {
          
            if (strpos($varg1, "dice") === 0)
            {
                $fonction = self::getUniqueValueFromDB("SELECT `action` FROM `multiaction` WHERE `ordre` = 0 AND `type` = 'dice'");
                $arg = self::getUniqueValueFromDB("SELECT `arg` FROM `multiaction` WHERE `ordre` = 0 AND `type` = 'dice'");
                self::DbQuery( "DELETE FROM `multiaction` WHERE `type` = 'dice'");
                highseason::$instance->addPending($this->player_id, $fonction);
            }

            if (strpos($varg1, "iconboard") === 0)
            {
                $explode2 = explode("_", $varg1);
                $arg2 = $explode2[1];
                preg_match_all('!\d+!', $arg2, $matches);
                $numbers = $matches[0];
                $nbre= intval($numbers[0]);
                
                $fonction = self::getUniqueValueFromDB("SELECT `action` FROM `multiaction` WHERE `ordre` = 0 AND `type` = 'hotel' AND `arg` = {$nbre}");
                //$arg = self::getUniqueValueFromDB("SELECT `arg` FROM `multiaction` WHERE `ordre` = 0 AND `type` = 'hotel'");
                $arg=$nbre;
                self::DbQuery( "DELETE FROM `multiaction` WHERE `type` = 'hotel' AND `arg` = {$nbre}");
                highseason::$instance->addPendingTarget($this->player_id, "Board".$this->player_hotel, 'Porte'.$arg, $arg);
            }

            if (strpos($varg1, "iconemperorboard") === 0)
            {
                $fonction = self::getUniqueValueFromDB("SELECT `action` FROM `multiaction` WHERE `ordre` = 0 AND `type` = 'emperor'");
                $arg = self::getUniqueValueFromDB("SELECT `arg` FROM `multiaction` WHERE `ordre` = 0 AND `type` = 'emperor'");
                highseason::$instance->addPendingTarget($this->player_id, "Emperorboard".$this->player_hotel, 'Bonus'.$arg, $arg);
                self::DbQuery( "DELETE FROM `multiaction` WHERE `ordre` = 0 AND `type` = 'emperor'");
                self::DbQuery( "UPDATE `multiaction` SET `ordre` = `ordre` - 1 WHERE `type` = 'emperor'");

                
            }

            if (strpos($varg1, "staff") === 0)
            {
                $explode = explode("_", $varg1);
                $arg1 = intval($explode[1]);
                $fonction = self::getUniqueValueFromDB("SELECT `action` FROM `multiaction` WHERE `type` = 'staff' AND `arg` = {$arg1}");
                $arg = self::getUniqueValueFromDB("SELECT `arg` FROM `multiaction` WHERE `type` = 'staff' AND `arg` = {$arg1}");
                self::DbQuery( "DELETE FROM `multiaction` WHERE `type` = 'staff' AND `arg` = {$arg1}");
                highseason::$instance->addPendingTarget($this->player_id, "Staff", 'Staffaction'.$arg, $arg);
                

                
            }

           

        }
        
    }




    function argCredit($parg1, $parg2)
    {
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} makes a loan request');
        $ret['titleyou'] = clienttranslate('${you} must confirm your loan request');

        
        $ret['buttons'][]='confirm';
        $ret['buttons'][]='cancel';
        
        
        return $ret;
    }

    function Credit($parg1, $parg2, $varg1, $varg2)
    {
        
        if($varg1 == "confirm")
        {
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} takes out a loan' ), array(
                'player_name' => $this->player_name,
                
                
                )
                );

            highseason::$instance->GainMoney(2);
            $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
            $creditset = $credit +1;
            self::DbQuery( "UPDATE `player` set `credituse` = {$creditset} WHERE `player_id` = {$this->player_id}" );
            highseason::$instance->notifyAllPlayers('credit','', array(
                'credit' =>  $creditset,
                'id' =>  $this->player_id,
                
                )
                );
            highseason::$instance->Score();

            $c = highseason::$instance->getGameStateValue('credit');
            $c = $c+1;
            highseason::$instance->setGameStateValue('credit', $c);

        }
   
        
    }
    
    function argPreparing($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to prepare a room');
        
        

        $ret['nb'] = highseason::$instance->getGameStateValue('preparing');
        $ret['nb2'] = highseason::$instance->getGameStateValue('preparingmax');

       
        $ret["selected"][] = 'action_1';
        
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

        if ($credit<3)
        {
            $ret['titleyou'] = clienttranslate('${you} can prepare a room (#nb#/#nb2#) (You can take out a loan)');
        }
        if ($credit >=3)
        {
            $ret['titleyou'] = clienttranslate('${you} can prepare a room (#nb#/#nb2#)');        
        }

        if(highseason::$instance->getGameStateValue('preparing')==1)
        {
        
        $ret['buttons'][]='undo';
        }
        if(highseason::$instance->getGameStateValue('preparing')>=2)
        {
        
        $ret['buttons'][]='undo';
        $ret['buttons'][]='pass';
        
        }


        
        return $ret;
    }

    function Preparing($parg1, $parg2, $varg1, $varg2)
    {
        
        
       
        if($varg1 == "pass")
        {
            highseason::$instance->ReduceDice(1);

            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} discards ${d}' ), array(
                'player_name' => $this->player_name, 
		        'd' => highseason::$instance->getLogsDice(1),
                
                )
                );
            
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");

            
            
        }

        

        elseif (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPending($this->player_id, "Preparing");
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

            

                        
            if(highseason::$instance->getGameStateValue('preparing')!=highseason::$instance->getGameStateValue('preparingmax'))
            {
            $a = highseason::$instance->getGameStateValue('preparing');
            $a = $a+1;
            highseason::$instance->setGameStateValue('preparing', $a);
            highseason::$instance->addPending($this->player_id, "Preparing");
            }
            else
            {
                highseason::$instance->ReduceDice(1);

                highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} discards ${d}' ), array(
                    'player_name' => $this->player_name, 
                    'd' => highseason::$instance->getLogsDice(1),
                    
                    )
                    );
                
                highseason::$instance->addPending($this->player_id, "CheckMultiAction");
            }
        }
    }

    function argOccupying($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to occupy a room');
        

        $ret['nb'] = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 2", true ));

        $ret["selected"][] = 'action_2';
        
        $porteprepare = self::getObjectListFromDB( "SELECT `porte` FROM `hotel` WHERE `etat` = 1 AND `player_id` = {$this->player_id}", true );
        foreach ($porteprepare as $porte)
        {
            $niveau = intval(self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}"));
            $dice2 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 2", true ));
            $cout = $niveau - $dice2;
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

        if ($credit<3)
        {
            $ret['titleyou'] = clienttranslate('${you} can occupy a room with a reduction of #nb# Krone(s) (You can take out a loan)');
        }
        if ($credit >=3)
        {
            $ret['titleyou'] = clienttranslate('${you} can occupy a room with a reduction of #nb# Krone(s)');       
        }
        

        $ret['buttons'][]='undo';
        


        
        return $ret;
    }

    function Occupying($parg1, $parg2, $varg1, $varg2)
    {
        
        
        
        if (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPending($this->player_id, "Occupying");
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

            highseason::$instance->notifyAllPlayers('occupying',clienttranslate( '${player_name} discards ${d} and occupies the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte,
                'd' => highseason::$instance->getLogsDice(2),
                
                )
                );

            $niveau = intval(self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}"));
            $dice2 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 2", true ));

            $spend = $niveau - $dice2;
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
            highseason::$instance->ReduceDice(2);
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


    function argPreparing3($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to prepare a room');
        
        $ret["selected"][] = 'action_3';

        


        $ret['nb'] = highseason::$instance->getGameStateValue('preparing3');
        $ret['nb2'] = highseason::$instance->getGameStateValue('preparingmax3');

       
        
        
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

        if ($credit<3)
        {
            $ret['titleyou'] = clienttranslate('${you} can prepare a room (#nb#/#nb2#) (You can take out a loan)');
        }
        if ($credit >=3)
        {
            $ret['titleyou'] = clienttranslate('${you} can prepare a room (#nb#/#nb2#)');         
        }

        if(highseason::$instance->getGameStateValue('preparing3')==1)
        {
        $ret['buttons'][]='undo';
        }
        if(highseason::$instance->getGameStateValue('preparing3')>=2)
        {
        
        $ret['buttons'][]='undo';
        $ret['buttons'][]='pass';
        }


        
        return $ret;
    }

    function Preparing3($parg1, $parg2, $varg1, $varg2)
    {
        
        
       
        if($varg1 == "pass")
        {
            highseason::$instance->ReduceDice(3);
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} discards ${d}' ), array(
                'player_name' => $this->player_name, 
		        'd' => highseason::$instance->getLogsDice(3),
                
                )
                );
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        

        elseif (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPending($this->player_id, "Preparing3");
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

                        
            if(highseason::$instance->getGameStateValue('preparing3')!=highseason::$instance->getGameStateValue('preparingmax3'))
            {
            $a = highseason::$instance->getGameStateValue('preparing3');
            $a = $a+1;
            highseason::$instance->setGameStateValue('preparing3', $a);
            highseason::$instance->addPending($this->player_id, "Preparing3");
            }
            else
            {
                highseason::$instance->ReduceDice(3);
                highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} discards ${d}' ), array(
                    'player_name' => $this->player_name, 
                    'd' => highseason::$instance->getLogsDice(3),
                    
                    )
                    );
                highseason::$instance->addPending($this->player_id, "CheckMultiAction");
            }
        }
    }

    function argOccupying3($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to occupy a room');
        
        if(($this->player_staff1==13)||($this->player_staff2 ==13))
        {
            $ret['nb'] = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 3", true )) +1;
        }
        else
        {
            $ret['nb'] = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 3", true ));
        }

        $ret["selected"][] = 'action_3';
        
        $porteprepare = self::getObjectListFromDB( "SELECT `porte` FROM `hotel` WHERE `etat` = 1 AND `player_id` = {$this->player_id}", true );
        foreach ($porteprepare as $porte)
        {
            $niveau = intval(self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}"));
            $dice3 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 3", true ));
            if(($this->player_staff1==13)||($this->player_staff2 ==13))
            {
                $cout = $niveau - $dice3 - 1;
            }
            else
            {
                $cout = $niveau - $dice3;
            }
            
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

        if ($credit<3)
        {
            $ret['titleyou'] = clienttranslate('${you} can occupy a room with a reduction of #nb# Krone(s) (You can take out a loan)');
        }
        if ($credit >=3)
        {
            $ret['titleyou'] = clienttranslate('${you} can occupy a room with a reduction of #nb# Krone(s)');         
        }
        

        $ret['buttons'][]='undo';
        


        
        return $ret;
    }

    function Occupying3($parg1, $parg2, $varg1, $varg2)
    {
        
        
        
        
        if (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPending($this->player_id, "Occupying3");
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

            highseason::$instance->notifyAllPlayers('occupying',clienttranslate( '${player_name} discards ${d} and occupies the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte,
                'd' => highseason::$instance->getLogsDice(3),
                
                )
                );

            $niveau = intval(self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}"));
            $dice3 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 3", true ));
            
            if(($this->player_staff1==13)||($this->player_staff2 ==13))
            {
                $spend = $niveau - $dice3 - 1;
            }
            else
            {
                $spend = $niveau - $dice3;
            }
            
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
            highseason::$instance->ReduceDice(3);
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


    function argEmperor4($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer}');
        $ret['titleyou'] = clienttranslate('${you}');

                
        return $ret;
    }

    function Emperor4($parg1, $parg2, $varg1, $varg2)
    {
        
        $dice = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 4", true ));

        highseason::$instance->ReduceDice(4);
        highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} discards ${d}' ), array(
            'player_name' => $this->player_name, 
            'd' => highseason::$instance->getLogsDice(4),
            
            )
            );
        highseason::$instance->GainEmperor($dice);
        highseason::$instance->Score();
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
    }

    function argKrones4($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer}');
        $ret['titleyou'] = clienttranslate('${you}');

                
        return $ret;
    }

    function Krones4($parg1, $parg2, $varg1, $varg2)
    {
        
        $dice = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 4", true ));
        highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} discards ${d}' ), array(
            'player_name' => $this->player_name, 
            'd' => highseason::$instance->getLogsDice(4),
            
            )
            );
        highseason::$instance->GainMoney($dice);
        highseason::$instance->ReduceDice(4);
        
        highseason::$instance->Score();
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
    }

    function argActionStaff($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        

        $ret["selected"][] = 'action_5';

        if(($this->player_staff1==18)||($this->player_staff2 ==18))
        {
            $ret['nb'] = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 5", true )) +2;
        }
        else
        {
            $ret['nb'] = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 5", true ));
        }
        

        $staffs = self::getObjectListFromDB( "SELECT `pos` `pos`, `prix` `prix` FROM `staff` WHERE `player_id` = {$this->player_id} AND `etat` = 0");

        if(($this->player_staff1==18)||($this->player_staff2 ==18))
        {
            $reduction = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 5", true )) +2;
        }
        else
        {
            $reduction = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 5", true ));
        }
        

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

        if ($credit<3)
        {
            $ret['titleyou'] = clienttranslate('${you} must can hire staff with a reduction of #nb# Krone(s) (You can take out a loan)');
        }
        if ($credit >=3)
        {
            $ret['titleyou'] = clienttranslate('${you} must can hire staff with a reduction of #nb# Krone(s)');       
        }

        $ret['buttons'][]='undo';

        return $ret;
    }

    function ActionStaff($parg1, $parg2, $varg1, $varg2)
    {
        
        
        if (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPending($this->player_id, "ActionStaff");
            highseason::$instance->addPending($this->player_id, "Credit");

        }

        else
        {
            $explode = explode("_", $varg1);
            $pos = intval($explode[1]);

            self::DbQuery( "UPDATE `staff` set `etat` = 1 WHERE `player_id` = {$this->player_id} AND `pos` = {$pos}" );

            highseason::$instance->notifyAllPlayers('gainstaff',clienttranslate( '${player_name} discards ${d} and hires staff' ), array(
                
                'pos' =>  $pos,
                'player_name' => $this->player_name, 
                'id' => $this->player_id,
                'd' => highseason::$instance->getLogsDice(5),
                                
                )
                );

            $prixstaff = self::getUniqueValueFromDB("SELECT `prix` FROM `staff` WHERE `player_id`={$this->player_id} AND `pos` = {$pos}");
            if(($this->player_staff1==18)||($this->player_staff2 ==18))
            {
                $reduction = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 5", true ))+2;
            }
            else
            {
                $reduction = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 5", true ));
            }
            
            $money = $prixstaff - $reduction;
            if ($money>=1)
            {
                highseason::$instance->SpendMoney($money);
            }
            highseason::$instance->ReduceDice(5);
            highseason::$instance->Score();

            $typestaff = self::getUniqueValueFromDB("SELECT `type` FROM `staff` WHERE `player_id`={$this->player_id} AND `pos` = {$pos}");
            highseason::$instance->addPendingTarget($this->player_id, "Staff", "Staff".$typestaff);

            


            
        }
       
    }

    function argImitate($parg1, $parg2)
    {
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} makes a loan request');
        

        $ret["selected"][] = 'action_6';

        $ret['nb'] = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 6", true ));

        $dice6 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 6", true ));
        highseason::$instance->setGameStateValue('lvlaction6', $dice6);

        $nombrestaff = count(self::getObjectListFromDB( "SELECT `id` FROM `staff` WHERE `etat` = 1 AND `player_id` = {$this->player_id}", true ));
        $nbreporteprepare = count(self::getObjectListFromDB( "SELECT `porte` FROM `hotel` WHERE `etat` = 1 AND `player_id` = {$this->player_id}", true ));
        for ($d=1; $d<=5; $d++)
        {
            
                if($d ==2)
                {
                    if ($nbreporteprepare>=1)
                    {
                        $ret["selectable"][] = 'action_'.$d;
                    }
                }

                elseif($d ==5)
                {
                    
                    if ($nombrestaff <6)
                    {
                        $ret["selectable"][] = 'action_'.$d;
                    }
                    
                }

               
                else
                {
                    $ret["selectable"][] = 'action_'.$d;
                }
                
            
        }

        $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
        if($credit<3)
        {
            $newcredit = $credit +1;
            $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
        }

        if ($credit<3)
        {
            $ret['titleyou'] = clienttranslate('${you} can imitate an other action with a strength of #nb# (You can take out a loan)');
        }
        if ($credit >=3)
        {
            $ret['titleyou'] = clienttranslate('${you} can imitate an other action with a strength of #nb#');         
        }

        
        $ret['buttons'][]='undo';
        
        
        return $ret;
    }

    function Imitate($parg1, $parg2, $varg1, $varg2)
    {
        
        

        if (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPending($this->player_id, "Imitate");
            highseason::$instance->addPending($this->player_id, "Credit");

        }

        if($varg1 == "action_1")
        {
            
            self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('dice', 'Preparing6', 61)");
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        if($varg1 == "action_2")
        {
            
            self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('dice', 'Occupying6', 62)");
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        if($varg1 == "action_3")
        {
            highseason::$instance->addPending($this->player_id, "TakeAction3_6");
        }

        if($varg1 == "action_4")
        {
            highseason::$instance->addPending($this->player_id, "TakeAction4_6");
        }

        if($varg1 == "action_5")
        {
            self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('dice', 'ActionStaff6', 65)");
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        
     
        
    }

    function argPreparing6($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to prepare a room');
        
        

        $ret['nb'] = highseason::$instance->getGameStateValue('preparing61');
        $ret['nb2'] = highseason::$instance->getGameStateValue('lvlaction6');

        $ret["selected"][] = 'action_1';
        $ret["selected"][] = 'action_6';
        
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

        if ($credit<3)
        {
            $ret['titleyou'] = clienttranslate('${you} can prepare a room (#nb#/#nb2#) (You can take out a loan)');
        }
        if ($credit >=3)
        {
            $ret['titleyou'] = clienttranslate('${you} can prepare a room (#nb#/#nb2#)');
        }

        if(highseason::$instance->getGameStateValue('preparing61')==1)
        {
        
        $ret['buttons'][]='undo';
        }
        if(highseason::$instance->getGameStateValue('preparing61')>=2)
        {
        
        $ret['buttons'][]='undo';
        $ret['buttons'][]='pass';
        }


        
        return $ret;
    }

    function Preparing6($parg1, $parg2, $varg1, $varg2)
    {
        
        
       
        if($varg1 == "pass")
        {
            highseason::$instance->ReduceDice(6);
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} discards ${d}' ), array(
                'player_name' => $this->player_name, 
		        'd' => highseason::$instance->getLogsDice(6),
                
                )
                );
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

       

        elseif (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPending($this->player_id, "Preparing6");
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

                        
            if(highseason::$instance->getGameStateValue('preparing61')!=highseason::$instance->getGameStateValue('lvlaction6'))
            {
            $a = highseason::$instance->getGameStateValue('preparing61');
            $a = $a+1;
            highseason::$instance->setGameStateValue('preparing61', $a);
            highseason::$instance->addPending($this->player_id, "Preparing6");
            }
            else
            {
                highseason::$instance->ReduceDice(6);
                highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} discards ${d}' ), array(
                    'player_name' => $this->player_name, 
                    'd' => highseason::$instance->getLogsDice(6),
                    
                    )
                    );
                highseason::$instance->addPending($this->player_id, "CheckMultiAction");
            }
        }
    }

    function argOccupying6($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to occupy a room');
        

        $ret['nb'] = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 6", true ));

        $ret["selected"][] = 'action_2';
        $ret["selected"][] = 'action_6';
        
        $porteprepare = self::getObjectListFromDB( "SELECT `porte` FROM `hotel` WHERE `etat` = 1 AND `player_id` = {$this->player_id}", true );
        foreach ($porteprepare as $porte)
        {
            $niveau = intval(self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}"));
            $dice6 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 6", true ));
            $cout = $niveau - $dice6;
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

        if ($credit<3)
        {
            $ret['titleyou'] = clienttranslate('${you} can occupy a room with a reduction of #nb# Krone(s) (You can take out a loan)');
        }
        if ($credit >=3)
        {
            $ret['titleyou'] = clienttranslate('${you} can occupy a room with a reduction of #nb# Krone(s)');         
        }
        

        $ret['buttons'][]='undo';
        


        
        return $ret;
    }

    function Occupying6($parg1, $parg2, $varg1, $varg2)
    {
        
        
        
        if (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPending($this->player_id, "Occupying6");
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

            highseason::$instance->notifyAllPlayers('occupying',clienttranslate( '${player_name} discards ${d} and occupies the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte,
                'd' => highseason::$instance->getLogsDice(6),
                
                )
                );

            $niveau = intval(self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}"));
            $dice6 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 6", true ));

            $spend = $niveau - $dice6;
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
            highseason::$instance->ReduceDice(6);
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
    


    function argTakeAction3_6($parg1, $parg2)
    {
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose an action');
        

        $ret["selected"][] = 'action_3';
        $ret["selected"][] = 'action_6';

        $nbreporteprepare = count(self::getObjectListFromDB( "SELECT `porte` FROM `hotel` WHERE `etat` = 1 AND `player_id` = {$this->player_id}", true ));

        $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
        if($credit<3)
        {
            $newcredit = $credit +1;
            $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
        }

        if ($credit<3)
        {
            $ret['titleyou'] = clienttranslate('${you} must choose an action (You can take out a loan)');
        }
        if ($credit >=3)
        {
            $ret['titleyou'] = clienttranslate('${you} must choose an action');        
        }

        $ret['buttons'][] = 'dice_631';
        if($nbreporteprepare >=1)
        {
        $ret['buttons'][] = 'dice_632';
        }
        $ret['buttons'][]='undo';
        return $ret;
    }

    function TakeAction3_6($parg1, $parg2, $varg1, $varg2)
    {
        
        if (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPending($this->player_id, "TakeAction3_6");
            highseason::$instance->addPending($this->player_id, "Credit");

        }

        if($varg1 == "dice_631")
        {
            self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('dice', 'Preparing63', 631)");
            
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");

        }

        if($varg1 == "dice_632")
        {
            self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('dice', 'Occupying63', 632)");
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        
        
        
    }

    function argTakeAction4_6($parg1, $parg2)
    {
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} must choose an action');
        

        $ret["selected"][] = 'action_4';
        $ret["selected"][] = 'action_6';

        $credit = self::getUniqueValueFromDB("SELECT `credituse` FROM `player` WHERE `player_id`={$this->player_id}");
        if($credit<3)
        {
            $newcredit = $credit +1;
            $ret["selectable"][] = 'credit_'.$newcredit.'_'.$this->player_id;
        }

        if ($credit<3)
        {
            $ret['titleyou'] = clienttranslate('${you} must choose an action (You can take out a loan)');
        }
        if ($credit >=3)
        {
            $ret['titleyou'] = clienttranslate('${you} must choose an action');      
        }
        
        $ret['buttons'][] = 'dice_641';
        $ret['buttons'][] = 'dice_642';
        $ret['buttons'][]='undo';
        return $ret;
    }

    function TakeAction4_6($parg1, $parg2, $varg1, $varg2)
    {
        
        if (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPending($this->player_id, "TakeAction4_6");
            highseason::$instance->addPending($this->player_id, "Credit");

        }

        if($varg1 == "dice_641")
        {
            self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('dice', 'Emperor6', 641)");
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");

        }

        if($varg1 == "dice_642")
        {
            self::DbQuery( "INSERT INTO `multiaction` (`type`, `action`, `arg`) VALUES ('dice', 'Krones6', 642)");
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");

           
        }
        
        
    }


    function argPreparing63($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret["selected"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to prepare a room');
        
        $ret["selected"][] = 'action_3';
        $ret["selected"][] = 'action_6';

        $ret['nb'] = highseason::$instance->getGameStateValue('preparing63');
        $ret['nb2'] = highseason::$instance->getGameStateValue('lvlaction6');

       
        
        
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

        if ($credit<3)
        {
            $ret['titleyou'] = clienttranslate('${you} can prepare a room (#nb#/#nb2#) (You can take out a loan)');
        }
        if ($credit >=3)
        {
            $ret['titleyou'] = clienttranslate('${you} can prepare a room (#nb#/#nb2#)');         
        }

        if(highseason::$instance->getGameStateValue('preparing63')==1)
        {
        
        $ret['buttons'][]='undo';
        }
        if(highseason::$instance->getGameStateValue('preparing63')>=2)
        {
        
        $ret['buttons'][]='undo';
        $ret['buttons'][]='pass';
        }


        
        return $ret;
    }

    function Preparing63($parg1, $parg2, $varg1, $varg2)
    {
        
        
       
        if($varg1 == "pass")
        {
            highseason::$instance->ReduceDice(6);
            highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} discards ${d}' ), array(
                'player_name' => $this->player_name, 
		        'd' => highseason::$instance->getLogsDice(6),
                
                )
                );
            highseason::$instance->addPending($this->player_id, "CheckMultiAction");
        }

        

        elseif (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPending($this->player_id, "Preparing63");
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
    

                        
            if(highseason::$instance->getGameStateValue('preparing63')!=highseason::$instance->getGameStateValue('lvlaction6'))
            {
            $a = highseason::$instance->getGameStateValue('preparing63');
            $a = $a+1;
            highseason::$instance->setGameStateValue('preparing63', $a);
            highseason::$instance->addPending($this->player_id, "Preparing63");
            }
            else
            {
                highseason::$instance->ReduceDice(6);
                highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} discards ${d}' ), array(
                    'player_name' => $this->player_name, 
                    'd' => highseason::$instance->getLogsDice(6),
                    
                    )
                    );
                highseason::$instance->addPending($this->player_id, "CheckMultiAction");
            }
        }
    }

    function argOccupying63($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to occupy a room');
        

        $ret['nb'] = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 6", true ));

        $ret["selected"][] = 'action_3';
        $ret["selected"][] = 'action_6';
        
        $porteprepare = self::getObjectListFromDB( "SELECT `porte` FROM `hotel` WHERE `etat` = 1 AND `player_id` = {$this->player_id}", true );
        foreach ($porteprepare as $porte)
        {
            $niveau = intval(self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}"));
            $dice6 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 6", true ));
            $cout = $niveau - $dice6;
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

        if ($credit<3)
        {
            $ret['titleyou'] = clienttranslate('${you} can occupy a room with a reduction of #nb# Krone(s) (You can take out a loan)');
        }
        if ($credit >=3)
        {
            $ret['titleyou'] = clienttranslate('${you} can occupy a room with a reduction of #nb# Krone(s)');        
        }

        $ret['buttons'][]='undo';
        


        
        return $ret;
    }

    function Occupying63($parg1, $parg2, $varg1, $varg2)
    {
        
        
        
        
        if (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPending($this->player_id, "Occupying63");
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

            highseason::$instance->notifyAllPlayers('occupying',clienttranslate( '${player_name} discards ${d} and occupies the room ${nb}' ), array(
                'porte' =>  $porte,
                'id' =>  $this->player_id,
                'player_name' => $this->player_name, 
                'nb' => $porte,
                'd' => highseason::$instance->getLogsDice(6),
                
                )
                );

            $niveau = intval(self::getUniqueValueFromDB("SELECT `niveau` FROM `hotel` WHERE `porte` = {$porte} AND `player_id`={$this->player_id}"));
            $dice6 = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 6", true ));

            $spend = $niveau - $dice6;
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
            highseason::$instance->ReduceDice(6);
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


    function argEmperor6($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer}');
        $ret['titleyou'] = clienttranslate('${you}');

                
        return $ret;
    }

    function Emperor6($parg1, $parg2, $varg1, $varg2)
    {
        
        $dice = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 6", true ));

        highseason::$instance->ReduceDice(6);
        highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} discards ${d}' ), array(
            'player_name' => $this->player_name, 
            'd' => highseason::$instance->getLogsDice(6),
            
            )
            );
        highseason::$instance->GainEmperor($dice);
        highseason::$instance->Score();
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
    }

    function argKrones6($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer}');
        $ret['titleyou'] = clienttranslate('${you}');

                
        return $ret;
    }

    function Krones6($parg1, $parg2, $varg1, $varg2)
    {
        
        $dice = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 6", true ));
        highseason::$instance->notifyAllPlayers('message',clienttranslate( '${player_name} discards ${d}' ), array(
            'player_name' => $this->player_name, 
            'd' => highseason::$instance->getLogsDice(6),
            
            )
            );
        highseason::$instance->GainMoney($dice);
        highseason::$instance->ReduceDice(6);
        
        highseason::$instance->Score();
        highseason::$instance->addPending($this->player_id, "CheckMultiAction");
    }

    function argActionStaff6($parg1, $parg2)
    {
        $ret = array();
        $ret["selectable"] = array();
        $ret['buttons'] = array();
        $ret['title'] = clienttranslate('${actplayer} chooses to hire staff');
        

        $ret["selected"][] = 'action_5';
        $ret["selected"][] = 'action_6';

        $ret['nb'] = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 6", true ));

        $staffs = self::getObjectListFromDB( "SELECT `pos` `pos`, `prix` `prix` FROM `staff` WHERE `player_id` = {$this->player_id} AND `etat` = 0");
        $reduction = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 6", true ));

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

        if ($credit<3)
        {
            $ret['titleyou'] = clienttranslate('${you} must can hire staff with a reduction of #nb# Krone(s) (You can take out a loan)');
        }
        if ($credit >=3)
        {
            $ret['titleyou'] = clienttranslate('${you} must can hire staff with a reduction of #nb# Krone(s)');        
        }

        $ret['buttons'][]='undo';

        return $ret;
    }

    function ActionStaff6($parg1, $parg2, $varg1, $varg2)
    {
        
        
        if (strpos($varg1, "credit") === 0)
        {
            highseason::$instance->addPending($this->player_id, "ActionStaff6");
            highseason::$instance->addPending($this->player_id, "Credit");

        }

        else
        {
            $explode = explode("_", $varg1);
            $pos = intval($explode[1]);

            self::DbQuery( "UPDATE `staff` set `etat` = 1 WHERE `player_id` = {$this->player_id} AND `pos` = {$pos}" );

            highseason::$instance->notifyAllPlayers('gainstaff',clienttranslate( '${player_name} discards ${d} and hires staff' ), array(
                
                'pos' =>  $pos,
                'player_name' => $this->player_name, 
                'id' => $this->player_id,
                'd' => highseason::$instance->getLogsDice(6),
                                
                )
                );

            $prixstaff = self::getUniqueValueFromDB("SELECT `prix` FROM `staff` WHERE `player_id`={$this->player_id} AND `pos` = {$pos}");
            $reduction = count(self::getObjectListFromDB( "SELECT `id` FROM `dice` WHERE `valeur` = 6", true ));
            $money = $prixstaff - $reduction;
            if ($money>=1)
            {
                highseason::$instance->SpendMoney($money);
            }
            highseason::$instance->ReduceDice(6);
            highseason::$instance->Score();

            $typestaff = self::getUniqueValueFromDB("SELECT `type` FROM `staff` WHERE `player_id`={$this->player_id} AND `pos` = {$pos}");
            highseason::$instance->addPendingTarget($this->player_id, "Staff", "Staff".$typestaff);

            


            
        }
       
    }













}