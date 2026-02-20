<?php 

for($i = 1; $i<=8;$i++)
{
    include("emperor/Emperorboard{$i}.php");    
}

class Emperor extends \APP_DbObject
{
    
        
    public function __construct()
    {
       


        $player_id = highseason::$instance->getActivePlayerId();
        
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



    }
    
    public function arginit($parg1, $parg2)
    {
        
    }
    
    public function init($parg1, $parg2, $varg1, $varg2)
    {
        
    }
    
    
}