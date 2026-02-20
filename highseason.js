/**
 *------
 * BGA framework: Gregory Isabelli & Emmanuel Colin & BoardGameArena
 * highseason implementation : © <Mathieu Chatrain> <mathieu.chatrain@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * highseason.js
 *
 * highseason user interface script
 * 
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

define([
    "dojo","dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter"
],
function (dojo, declare) {
    return declare("bgagame.highseason", ebg.core.gamegui, {
        constructor: function(){
            console.log('highseason constructor');
              
            // Here, you can init the global variables of your user interface
            // Example:
            // this.myGlobalValue = 0;

        },

        
        updatePlayerOrdering() {
            
            this.inherited(arguments);
            dojo.place(this.format_block('jstpl_playerboardcompteur', {}), 'player_boards', 'first');

            

                $('compteurturn').innerHTML = this.gamedatas.turn;
                $('compteurround').innerHTML = this.gamedatas.round;
        }, 
        
        /*
            setup:
            
            This method must set up the game user interface according to current game situation specified
            in parameters.
            
            The method is called each time the game interface is displayed to a player, ie:
            _ when the game starts
            _ when a player refreshes the game page (F5)
            
            "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
        */
        
            setup: function( gamedatas )
            {
                console.log( "Starting game setup" );
    
    /////////////////////////////////////////////////////////////////////////////////           
    //    _____                      _____        _            
    //   / ____|                    |  __ \      | |           
    //  | |  __  __ _ _ __ ___   ___| |  | | __ _| |_ __ _ ___ 
    //  | | |_ |/ _` | '_ ` _ \ / _ \ |  | |/ _` | __/ _` / __|
    //  | |__| | (_| | | | | | |  __/ |__| | (_| | || (_| \__ \
    //   \_____|\__,_|_| |_| |_|\___|_____/ \__,_|\__\__,_|___/
    //                                                        
    /////////////////////////////////////////////////////////////////////////////////  
    
                this.players = gamedatas.players;
                
                                
                
    
                for( var i in gamedatas.board)
                {
                    var variable = gamedatas.board[i];
                    
                    if( variable.id !== null )
                    {
                        dojo.query("#hotel_"+variable.id).addClass("hotel"+variable.hotel);
                        dojo.query("#staff_"+variable.id).addClass("staff"+variable.staff);

                    }
                }

                for( var player_id in gamedatas.players )
                {
                    var player = gamedatas.players[player_id].id;
                    if(player != this.getCurrentPlayerId())
                    {
                        dojo.query("#playerview_"+player).addClass("masque");
                    }

                                                
                    
                }

                if(this.isSpectator)
                {
                    var player = gamedatas.listplayers[0];
                    dojo.query("#playerview_"+player).removeClass("masque");
                }

                for( var player_id in gamedatas.players )   
                {
                                      
                    var eyes = document.getElementById('eye_'+player_id);
                    if (eyes !== null)
                    {
                        dojo.destroy('eye_'+player_id)
                    }
                    
                    var player_board_div = $('player_board_'+player_id);
                    var elementScore = player_board_div.querySelector(".player_score");
                    dojo.place(this.format_block('jstpl_eye', {id: player_id }), elementScore);

                    
                    
                    
                    
                }

                for( var p in gamedatas.poignee )   
                {
                    var poignee = gamedatas.poignee[p];
                    if (poignee.etat != 0)  
                    {
                        this.addEtatPoignee(poignee.id, poignee.porte, poignee.etat);
                    }             
                                    
                }

                /*$('dice_1').innerHTML = gamedatas.dice1;
                $('dice_2').innerHTML = gamedatas.dice2; 
                $('dice_3').innerHTML = gamedatas.dice3;
                $('dice_4').innerHTML = gamedatas.dice4; 
                $('dice_5').innerHTML = gamedatas.dice5; 
                $('dice_6').innerHTML = gamedatas.dice6; */

                this.addDice(gamedatas.dice1, gamedatas.dice2, gamedatas.dice3, gamedatas.dice4, gamedatas.dice5, gamedatas.dice6);

               
               

                for( var player_id in gamedatas.players ) 
                {  
                    for (let rd = 1; rd <= gamedatas.round; rd++) 
                    {
                        this.addRound(player_id, rd);
                    }
                }

                for( var m in gamedatas.money )

                {
                    var money = gamedatas.money[m];
                    this.addMoney(money.id, money.mgain, money.muse);

                }

                for( var n in gamedatas.credituse )

                {
                    var credit = gamedatas.credituse[n];
                    if(credit.credituse >0)
                    {
                        for (var c =1 ; c<=credit.credituse; c++)
                        {
                        
                        this.addCredit(credit.id, c);
                        }
                    }

                }


                dojo.place( this.format_block('jstpl_first', {} ), 'player_board_'+gamedatas.first);


                
                for( var id in gamedatas.bonusetage )
                {
                
                var tableau = [gamedatas.bonusetage[id][0].l1, gamedatas.bonusetage[id][0].l2, gamedatas.bonusetage[id][0].l3, gamedatas.bonusetage[id][0].l4, gamedatas.bonusetage[id][0].l5, gamedatas.bonusetage[id][0].l6, gamedatas.bonusetage[id][0].l7, gamedatas.bonusetage[id][0].l8 ,gamedatas.bonusetage[id][0].c1, gamedatas.bonusetage[id][0].c2, gamedatas.bonusetage[id][0].c3, gamedatas.bonusetage[id][0].c4, gamedatas.bonusetage[id][0].c5, gamedatas.bonusetage[id][0].c6, gamedatas.bonusetage[id][0].c7]
                this.addBonusEtage(id, tableau);
                
                }

                for( var id in gamedatas.score )
                {
                
                    
                    if (gamedatas.score[id][0].score_1 != 0)
                    {
                        $('score_1_'+id).innerHTML = gamedatas.score[id][0].score_1;
                    }
                    if (gamedatas.score[id][0].score_1 == 0)
                    {
                        $('score_1_'+id).innerHTML = '';
                    }
                    if (gamedatas.score[id][0].score_2 != 0)
                    {
                        $('score_2_'+id).innerHTML = gamedatas.score[id][0].score_2;
                    }
                    if (gamedatas.score[id][0].score_2 == 0)
                    {
                        $('score_2_'+id).innerHTML = '';
                    }
                    if (gamedatas.score[id][0].score_3 != 0)
                    {
                        $('score_3_'+id).innerHTML = gamedatas.score[id][0].score_3;
                    }
                    if (gamedatas.score[id][0].score_3 == 0)
                    {
                        $('score_3_'+id).innerHTML = '';
                    }
                    if (gamedatas.score[id][0].score_4 != 0)
                    {
                        $('score_4_'+id).innerHTML = gamedatas.score[id][0].score_4;
                    }
                    if (gamedatas.score[id][0].score_4 == 0)
                    {
                        $('score_4_'+id).innerHTML = '';
                    }
                    if (gamedatas.score[id][0].score_5 != 0)
                    {
                        $('score_5_'+id).innerHTML = gamedatas.score[id][0].score_5;
                    }
                    if (gamedatas.score[id][0].score_5 == 0)
                    {
                        $('score_5_'+id).innerHTML = '';
                    }
                    if (gamedatas.score[id][0].score_6 != 0)
                    {
                        $('score_6_'+id).innerHTML = gamedatas.score[id][0].score_6;
                    }
                    if (gamedatas.score[id][0].score_6 == 0)
                    {
                        $('score_6_'+id).innerHTML = '';
                    }
                    if (gamedatas.score[id][0].score_7 != 0)
                    {
                        $('score_7_'+id).innerHTML = gamedatas.score[id][0].score_7;
                    }
                    if (gamedatas.score[id][0].score_7 == 0)
                    {
                        $('score_7_'+id).innerHTML = '';
                    }
                    
                    var score_8 = gamedatas.score[id][0].score_81 - gamedatas.score[id][0].score_82;
                    var div = Math.floor(score_8/3);
                    if (div != 0)
                    {
                        $('score_8_'+id).innerHTML = div;
                    }
                    if (div == 0)
                    {
                        $('score_8_'+id).innerHTML = '';
                    }
                    if (gamedatas.score[id][0].score_9 != 0)
                    {
                        $('score_9_'+id).innerHTML = gamedatas.score[id][0].score_9;
                    }
                    if (gamedatas.score[id][0].score_9 == 0)
                    {
                        $('score_9_'+id).innerHTML = '';
                    }

                   
                    if (gamedatas.score[id][0].score_10 == 1)
                    {
                    var scorecredit = -2;
                    }
                    if (gamedatas.score[id][0].score_10 == 2)
                    {
                    var scorecredit = -5;
                    }
                    if (gamedatas.score[id][0].score_10 == 3)
                    {
                    var scorecredit = -9;
                    }
                    if (gamedatas.score[id][0].score_10 != 0)
                    {
                        $('score_10_'+id).innerHTML = scorecredit;
                    }
                    if (gamedatas.score[id][0].score_10 == 0)
                    {
                        $('score_10_'+id).innerHTML = '';
                    }                    

                    
                }

                for( var id in gamedatas.emperor)
                {
                    this.addEmperorTrack(id, gamedatas.emperor[id][0].emperor1, gamedatas.emperor[id][0].emperor2, gamedatas.emperor[id][0].emperor3);
                }
                for( var id in gamedatas.bonusemperor)
                {
                    this.addBonusEmperor(id, gamedatas.bonusemperor[id][0].bonusemperor1, gamedatas.bonusemperor[id][0].bonusemperor2, gamedatas.bonusemperor[id][0].bonusemperor3);
                }

                for( var id in gamedatas.malusemperor)
                {
                    this.addMalusEmperor(id, gamedatas.malusemperor[id][0].malusemperor1, gamedatas.malusemperor[id][0].malusemperor2, gamedatas.malusemperor[id][0].malusemperor3);
                }

                for( var id in gamedatas.staff)
                {
                   
                    
                    for ( var s=0; s<=5; s++)
                    {
                        this.addStaff (id, gamedatas.staff[id][s].pos, gamedatas.staff[id][s].etat);
                    }
                    
                }


                var name1 = _("Preparing Rooms");
                var description1 = _("Prepare one or more rooms in your hotel, up to the strength of this action. The prepared rooms must be orthoganally adjacent to a room with a circled doorknob. Pay the cost for preparing a room, as indicated by the staircase on that floor.");
                var html1 = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_actiontool',{name: name1, description: description1})+'</div></div>';
                this.addTooltipHtml( 'action_1', html1,1000);

                var name2 = _("Occupying a Room");
                var description2 = _("Occupy exactly one prepared room in your hotel. Pay the cost for occupying a room, as indicated by the staircase on that floor, reduced by the strength of this action, to a minimum of 0 krones. Immediately after, you get the one-time room bonus if there is one depicted on the door.");
                var html2 = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_actiontool',{name: name2, description: description2})+'</div></div>';
                this.addTooltipHtml( 'action_2', html2,1000);

                var name3 = _("Preparing Rooms or Occupying a Room");
                var description3 = _("Choose exactly one of the Prepare Rooms and Occupy a Room actions, applying the strength of this action.");
                var html3 = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_actiontool',{name: name3, description: description3})+'</div></div>';
                this.addTooltipHtml( 'action_3', html3,1000);

                var name4 = _("Emperor or Krones");
                var description4 = _("Either advance as many spaces on the active Emperor's track as the strength of this action, or gain as many krones as the strength of this action.");
                var html4 = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_actiontool',{name: name4, description: description4})+'</div></div>';
                this.addTooltipHtml( 'action_4', html4,1000);

                var name5 = _("Hiring Staff");
                var description5 = _("Hire exactly one person from your staff board. Pay the indicated cost, reduced by the strength of this action, to a minimum of 0 krones.");
                var html5 = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_actiontool',{name: name5, description: description5})+'</div></div>';
                this.addTooltipHtml( 'action_5', html5,1000);

                var name6 = _("Imitating an Action");
                var description6 = _("Pay 1 krone and choose one of the other five actions. Perform the chosen action by applying the force of the Imitating action. The number of dice on the space of the chosen action does not matter (even if there are none).");
                var html6 = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_actiontool',{name: name6, description: description6})+'</div></div>';
                this.addTooltipHtml( 'action_6', html6,1000);


                for( var id in gamedatas.staff)
                {
                   
                    
                    for ( var s=0; s<=5; s++)
                    {
                        var staff = 'stafftool_'+gamedatas.staff[id][s].pos+'_'+id;
                        
                        if(gamedatas.staff[id][s].type == 1)
                        {
                            var name = _("Permanent:");
                            var description = _("Each time you take the indicated die you gain 1 krone and 1 on the Emperor track.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 2)
                        {
                            var name = _("Permanent:");
                            var description = _("Taking the indicated die earns you 1 krone (instead of paying 1).");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 3)
                        {
                            var name = _("Immediately and only once:");
                            var description = _("You can prepare 2 red rooms for free.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 4)
                        {
                            var name = _("Immediately and only once:");
                            var description = _("You can occupy a yellow room prepared for free.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 5)
                        {
                            var name = _("At the end of the game:");
                            var description = _("2 points for each occupied blue room.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 6)
                        {
                            var name = _("At the end of the game:");
                            var description = _("2 points for each occupied room (of any color) on the 3rd and 4th floor.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 7)
                        {
                            var name = _("Permanent:");
                            var description = _("Each time you take the indicated die you gain 2 krones.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 8)
                        {
                            var name = _("Permanent:");
                            var description = _("Preparing a red room is always free.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 9)
                        {
                            var name = _("Immediately and only once:");
                            var description = _("Gain 3 on the Emperor track.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 10)
                        {
                            var name = _("Immediately and only once:");
                            var description = _("You can prepare a room and occupy a room for free (in the order of your choice).");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 11)
                        {
                            var name = _("At the end of the game:");
                            var description = _("2 points for each occupied yellow room.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 12)
                        {
                            var name = _("At the end of the game:");
                            var description = _("4 points for each fully occupied row on the left and 3 points for each fully occupied row on the right of the stairway.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 13)
                        {
                            var name = _("Permanent:");
                            var description = _("Each time you take the indicated die, you gain 1 to the strength for the action and 1 on the Emperor track.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 14)
                        {
                            var name = _("Immediately and only once:");
                            var description = _("You can prepare 2 yellow rooms for free.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 15)
                        {
                            var name = _("Immediately and only once:");
                            var description = _("You can occupy a blue room prepared for free.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 16)
                        {
                            var name = _("At the end of the game:");
                            var description = _("5 points for each fully occupied column.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 17)
                        {
                            var name = _("At the end of the game:");
                            var description = _("2 points for each hired staff member.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 18)
                        {
                            var name = _("Permanent:");
                            var description = _("Each time you take the indicated die, you gain 2 to the strength for the action.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 19)
                        {
                            var name = _("Permanent:");
                            var description = _("Preparing a blue room is always free.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 20)
                        {
                            var name = _("Immediately and only once:");
                            var description = _("You can prepare a room for free. Gain 2 on the Emperor track.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 21)
                        {
                            var name = _("Immediately and only once:");
                            var description = _("You can occupy a red room prepared for free.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 22)
                        {
                            var name = _("At the end of the game:");
                            var description = _("3 points for each set of rooms of all three colors (need not be adjacent and a room cannot belong to more than one set).");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 23)
                        {
                            var name = _("At the end of the game:");
                            var description = _("2 points for each fully occupied group of rooms.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 24)
                        {
                            var name = _("Permanent:");
                            var description = _("Preparing a yellow room is always free.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 25)
                        {
                            var name = _("Immediately and only once:");
                            var description = _("You can prepare 2 blue rooms for free.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 26)
                        {
                            var name = _("At the end of the game:");
                            var description = _("2 points for each occupied red room.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }

                        if(gamedatas.staff[id][s].type == 27)
                        {
                            var name = _("Permanent:");
                            var description = _("Each time you take the indicated die you gain 1 krone and 1 on the Emperor track.");
                            var html = '<div class="anatooltip"><div class="anataction">'+this.format_block('jstpl_stafftool',{name: name, description: description})+'</div></div>';
                            this.addTooltipHtml( staff, html,1000);

                        }
                    }
                    
                }


                
                for( var player in gamedatas.players )
                {
                    var playerid = gamedatas.players[player].id;
                    
                    var descriptionscore1 = _("Score hired staff.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptionscore1})+'</div></div>';
                    this.addTooltipHtml( 'scoretool_1_'+playerid, html,1000);
                    var descriptionscore2 = _("Score points for fully occupied groups of blue rooms.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptionscore2})+'</div></div>';
                    this.addTooltipHtml( 'scoretool_2_'+playerid, html,1000);
                    var descriptionscore3 = _("4 points for each occupied room on the 4th floor.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptionscore3})+'</div></div>';
                    this.addTooltipHtml( 'scoretool_3_'+playerid, html,1000);  
                    var descriptionscore4 = _("3 points for each occupied room on the 3rd floor.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptionscore4})+'</div></div>';
                    this.addTooltipHtml( 'scoretool_4_'+playerid, html,1000);  
                    var descriptionscore5 = _("2 points for each occupied room on the 2nd floor.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptionscore5})+'</div></div>';
                    this.addTooltipHtml( 'scoretool_5_'+playerid, html,1000);  
                    var descriptionscore6 = _("1 point for each occupied room on the 1st floor.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptionscore6})+'</div></div>';
                    this.addTooltipHtml( 'scoretool_6_'+playerid, html,1000);  
                    var descriptionscore7 = _("Total points for the Emperor track.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptionscore7})+'</div></div>';
                    this.addTooltipHtml( 'scoretool_7_'+playerid, html,1000);  
                    var descriptionscore8 = _("Score the money track (1 point for every 3 krones).");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptionscore8})+'</div></div>';
                    this.addTooltipHtml( 'scoretool_8_'+playerid, html,1000);  
                    var descriptionscore9 = _("Total the points from row and column bonuses that you got during the game.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptionscore9})+'</div></div>';
                    this.addTooltipHtml( 'scoretool_9_'+playerid, html,1000);  
                    var descriptionscore10 = _("Total negative points of loans made.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptionscore10})+'</div></div>';
                    this.addTooltipHtml( 'scoretool_10_'+playerid, html,1000); 
                    
                    var descriptiongroup1 = _("Bonus for groups of occupied blue rooms.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptiongroup1})+'</div></div>';
                    this.addTooltipHtml( 'grouptool_1_'+playerid, html,1000);
                    var descriptiongroup2 = _("Bonus for groups of occupied red rooms.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptiongroup2})+'</div></div>';
                    this.addTooltipHtml( 'grouptool_2_'+playerid, html,1000);
                    var descriptiongroup3 = _("Bonus for groups of occupied yellow rooms.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptiongroup3})+'</div></div>';
                    this.addTooltipHtml( 'grouptool_3_'+playerid, html,1000);


                    var descriptionemperormalus1 = _("-2 points if you have not reached the 4th square (inclusive) at the end of the 3rd round.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptionemperormalus1})+'</div></div>';
                    this.addTooltipHtml( 'emperortrackmalus_1_'+playerid, html,1000);
                    var descriptionemperormalus2 = _("-3 points if you have not reached the 4th square (inclusive) at the end of the 5th round.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptionemperormalus2})+'</div></div>';
                    this.addTooltipHtml( 'emperortrackmalus_2_'+playerid, html,1000);
                    var descriptionemperormalus3 = _("-4 points if you have not reached the 5th square (inclusive) at the end of the 7th round.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptionemperormalus3})+'</div></div>';
                    this.addTooltipHtml( 'emperortrackmalus_3_'+playerid, html,1000);

                    var descriptionemperorbonus1 = _("2 points for the first player to complete the 1st track.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptionemperorbonus1})+'</div></div>';
                    this.addTooltipHtml( 'emperortrackbonus_1_'+playerid, html,1000);
                    var descriptionemperorbonus2 = _("3 points for the first player to complete the 2nd track.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptionemperorbonus2})+'</div></div>';
                    this.addTooltipHtml( 'emperortrackbonus_2_'+playerid, html,1000);
                    var descriptionemperorbonus3 = _("4 points for the first player to complete the 3rd track.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptionemperorbonus3})+'</div></div>';
                    this.addTooltipHtml( 'emperortrackbonus_3_'+playerid, html,1000);

                    var descriptioncredit1 = _("-2 points for the 1st loan.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptioncredit1})+'</div></div>';
                    this.addTooltipHtml( 'credit_1_'+playerid, html,1000);
                    var descriptioncredit2 = _("-3 points for the 2nd loan.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptioncredit2})+'</div></div>';
                    this.addTooltipHtml( 'credit_2_'+playerid, html,1000);
                    var descriptioncredit3 = _("-4 points for the 3rd loan.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptioncredit3})+'</div></div>';
                    this.addTooltipHtml( 'credit_3_'+playerid, html,1000);

                    var descriptionetage1 = _("The first player to occupy the rows/columns to the left of the staircase gains the corresponding points.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptionetage1})+'</div></div>';
                    this.addTooltipHtml( 'etagetool_1_'+playerid, html,1000);
                    var descriptionetage2 = _("The first player to occupy the rows/columns to the right of the staircase gains the corresponding points.");
                    var html = '<div class="anatooltip"><div class="anataction2">'+this.format_block('jstpl_boardtool',{description: descriptionetage2})+'</div></div>';
                    this.addTooltipHtml( 'etagetool_2_'+playerid, html,1000);

                    var nameaide1 = _("Room and Emperor Track Bonuses");
                    var nameaide2 = _("Prepare a room of the indicated color—or any color if none is given:");
                    var nameaide3 = _("Occupy a prepared room of the indicated color—or any color if none is given:");
                    var nameaide4 = _("Hire a staff:");
                    var nameaide5 = _("Money and Emperor track:");
                    var descriptionaide1 = _("Pay the normal cost (see stairway).");
                    var descriptionaide2 = _("The cost is reduced by the indicated amount.");
                    var descriptionaide3 = _("The action is free of any cost.");
                    var descriptionaide4 = _("The cost is reduced by the indicated amount.");
                    var descriptionaide5 = _("The action is free of any cost.");
                    var descriptionaide6 = _("The cost is reduced by the indicated amount.");
                    var descriptionaide7 = _("The action is free of any cost.");
                    var descriptionaide8 = _("Gain indicated number of krones.");
                    var descriptionaide9 = _("Advance on the active Emperor track by the indicated number.");
                    var html = '<div class="anatooltip"><div class="anataide">'+this.format_block('jstpl_aidetool',{name1: nameaide1, name2: nameaide2, name3: nameaide3, name4: nameaide4, name5: nameaide5, description1: descriptionaide1, description2: descriptionaide2, description3: descriptionaide3, description4: descriptionaide4, description5: descriptionaide5, description6: descriptionaide6, description7: descriptionaide7, description8: descriptionaide8, description9: descriptionaide9})+'</div></div>';
                    this.addTooltipHtml( 'aide_'+playerid, html,1000);
                    
                }
                
                

               
    
                
                // TODO: Set up your game interface here, according to "gamedatas"
                
     
                // Setup game notifications to handle (see "setupNotifications" method below)
                this.setupNotifications();


                dojo.query(".left").connect('onclick', this, 'onPrev' );
                dojo.query(".right").connect('onclick', this, 'onNext' );
                dojo.query(".eye").connect('onclick', this, 'onEye' );
                dojo.query(".poigneecontent").connect('onclick', this, 'onSelect' );
                dojo.query(".action").connect('onclick', this, 'onSelect' );
                dojo.query(".credit").connect('onclick', this, 'onSelect' );
                dojo.query(".staff").connect('onclick', this, 'onSelect' )


    
                console.log( "Ending game setup" );
            },
           
           
    /////////////////////////////////////////////////////////////////////////////////   
    //         _____ _        _            
    //        / ____| |      | |           
    //       | (___ | |_ __ _| |_ ___  ___ 
    //        \___ \| __/ _` | __/ _ \/ __|
    //        ____) | || (_| | ||  __/\__ \
    //       |_____/ \__\__,_|\__\___||___/
    //                                    
    /////////////////////////////////////////////////////////////////////////////////                                        
      
            
            // onEnteringState: this method is called each time we are entering into a new game state.
            //                  You can use this method to perform some user interface changes at this moment.
            //
            onEnteringState: function( stateName, args )
            {
                console.log( 'Entering state: '+stateName );
    
                dojo.query(".selectable").removeClass("selectable");
                dojo.query(".selected").removeClass("selected");
                dojo.query(".noanimationred").removeClass("noanimationred");
                dojo.query(".noanimationblue").removeClass("noanimationblue");

                
                
                
                switch( stateName )
                {
                
                case 'playerTurn':


                

                

                if ((this.isCurrentPlayerActive())&&(this.getActivePlayerId() == this.getCurrentPlayerId()))
                {
                    var elements = document.querySelectorAll('[id^="playerview"]:not(.masque)');
                    var idsSansMasque = [];
                    elements.forEach(function(element) {
                        // Obtenez l'ID de chaque élément
                        var id = element.id;
                        
                        // Ajoutez l'ID à la liste
                        idsSansMasque.push(id);
                    });

                    dojo.query("#"+idsSansMasque[0]).addClass("masque");
                    dojo.query("#playerview_"+this.getActivePlayerId()).removeClass("masque");

                }





                    this.args = args.args;
                    for( var sid in this.args.selectable)
                        {
                            if(this.isCurrentPlayerActive())
                            {
                            dojo.query("#"+this.args.selectable[sid]).addClass("selectable");
                            }
                        }

                    for( var sid in this.args.selected)
                    {
                        if(this.isCurrentPlayerActive())
                        {
                        dojo.query("#"+this.args.selected[sid]).addClass("selected");
                        }
                    }
    
                    //this.gamedatas.gamestate.descriptionmyturn = _(this.args.titleyou);
                    //this.gamedatas.gamestate.description = _(this.args.title);
                    //this.updatePageTitle();
                        
                    if( this.isCurrentPlayerActive() )
                    {
                        if(args.args.titleyou != null)
                    {
                        $('pagemaintitletext').innerHTML = 	this.format_string_recursive(_(args.args.titleyou).replace('${you}', this.divYou()).replace('#nb#',args.args.nb).replace('#nb2#',args.args.nb2).replace('#icon#',args.args.icon), args.args);   
                    }
                    } 
                    
                    else{
                        if(args.args.title != null)
                        {
                            $('pagemaintitletext').innerHTML = this.format_string_recursive(_(args.args.title).replace('${actplayer}', this.divActPlayer()).replace('#nb#',args.args.nb), args.args);  
                        }
                    }


                    if (this.bga.userPreferences.get(100) == 2)
                {
                    var red1 = document.querySelectorAll('.poigneecontent.selectable');
                    var red2 = document.querySelectorAll('.action.selectable');
                    var red3 = document.querySelectorAll('.staff.selectable');

                    red1.forEach(function(element) {
                        // Ajout d'une classe spéciale pour annuler l'animation
                        element.classList.add('noanimationred');
                    });

                    red2.forEach(function(element) {
                        // Ajout d'une classe spéciale pour annuler l'animation
                        element.classList.add('noanimationred');
                    });

                    red3.forEach(function(element) {
                        // Ajout d'une classe spéciale pour annuler l'animation
                        element.classList.add('noanimationred');
                    });
                    
                    

                    var blue1 = document.querySelectorAll('.credit.selectable');
                    blue1.forEach(function(element) {
                        // Ajout d'une classe spéciale pour annuler l'animation
                        element.classList.add('noanimationblue');
                    });

                }


                
                    break;
        
               
               
                case 'dummmy':
                    break;
                }
            },
    
            // onLeavingState: this method is called each time we are leaving a game state.
            //                 You can use this method to perform some user interface changes at this moment.
            //
            onLeavingState: function( stateName )
            {
                console.log( 'Leaving state: '+stateName );
                
                switch( stateName )
                {
                
                /* Example:
                
                case 'myGameState':
                
                    // Hide the HTML block we are displaying only during this game state
                    dojo.style( 'my_html_block_id', 'display', 'none' );
                    
                    break;
               */
               
               
                case 'dummmy':
                    break;
                }               
            }, 
    
            // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
            //                        action status bar (ie: the HTML links in the status bar).
            //        
            
            onUpdateActionButtons: function( stateName, args )
            {
                console.log( 'onUpdateActionButtons: '+stateName );
                          
                if( this.isCurrentPlayerActive() )
                {            
                    switch( stateName )
                    {
    
                        case "playerTurn":
                            for( var nb in args.buttons )
                             { 
                                     
                                     if(args.buttons[nb] == "cancel")
                                     {
                                        this.addActionButton( 'cancel', _("Cancel") ,'onOpButton', null, null, 'red' );
                                     }
                                     if(args.buttons[nb] == "pass")
                                     {
                                        this.addActionButton( 'pass', _("Pass") ,'onOpButton', null, null, 'red' );
                                     }
                                     if(args.buttons[nb] == "undo")
                                    {
                                    this.addActionButton( 'undo', _("Undo") ,'onOpUndo', null, null, 'red' );
                                    }
                                     if(args.buttons[nb] == "confirm")
                                     {
                                        this.addActionButton( 'confirm', _("Confirm") ,'onOpButton', null, null, 'blue' );
                                     }
                                     /*if(args.buttons[nb] == "relancedes")
                                     {
                                        this.addActionButton( 'relancedes', _("Relance Dés") ,'onOpButton', null, null, 'gray' );
                                     }*/

                                     if(args.buttons[nb].startsWith("dice"))
                                    {
                                        this.addActionButton(args.buttons[nb], `<div class="${args.buttons[nb]} bouton"></div>`, 'onOpButton', null, null, 'none');
                                    }

                                    if(args.buttons[nb].startsWith("iconemperorboard"))
                                    {
                                        var tableau1 = args.buttons[nb].split("_");
                                        
                                        this.addActionButton(args.buttons[nb], `<div class="${tableau1[0]} ${tableau1[1]} bouton"></div>`, 'onOpButton', null, null, 'none');
                                    }

                                    if(args.buttons[nb].startsWith("iconboard"))
                                    {
                                        var tableau2 = args.buttons[nb].split("_");
                                        
                                        this.addActionButton(args.buttons[nb], `<div class="${tableau2[0]} ${tableau2[1]} bouton"></div>`, 'onOpButton', null, null, 'none');
                                    }

                                    if(args.buttons[nb].startsWith("staff"))
                                    {
                                        this.addActionButton(args.buttons[nb], `<div class="${args.buttons[nb]} bouton"></div>`, 'onOpButton', null, null, 'none');
                                    }





                            }
                            
                            
                            break;
    
    
    
                    }
                }
            },   
    
         
    /////////////////////////////////////////////////////////////////////////////////         
    //   _    _ _   _ _ _ _                          _   _               _     
    //  | |  | | | (_) (_) |                        | | | |             | |    
    //  | |  | | |_ _| |_| |_ _   _   _ __ ___   ___| |_| |__   ___   __| |___ 
    //  | |  | | __| | | | __| | | | | '_ ` _ \ / _ \ __| '_ \ / _ \ / _` / __|
    //  | |__| | |_| | | | |_| |_| | | | | | | |  __/ |_| | | | (_) | (_| \__ \
    //   \____/ \__|_|_|_|\__|\__, | |_| |_| |_|\___|\__|_| |_|\___/ \__,_|___/
    //                         __/ |                                           
    //                        |___/                                            
    /////////////////////////////////////////////////////////////////////////////////  

    divYou : function() {
            
        var color = this.players[this.player_id].color;
        var color_bg = "";
        var you = "<span style=\"font-weight:bold;color:#" + color + ";" + color_bg + "\">" + _("You") + "</span>";
        return you;
    },

    divActPlayer : function() {        	
        var color = this.players[this.getActivePlayerId()].color;
        var name = this.players[this.getActivePlayerId()].name;
        var color_bg = "";
        var you = "<span style=\"font-weight:bold;color:#" + color + ";" + color_bg + "\">" + name + "</span>";
        return you;
    },

    format_string_recursive : function(log, args) {
        try {
            if (log && args && !args.processed) {
                args.processed = true;

                
            }
        } catch (e) {
            console.error(log,args,"Exception thrown", e.stack);
        }
        return this.inherited(arguments);
    },
     
    attachToNewParentNoDestroy: function (mobile_in, new_parent_in, relation, place_position) 
        {
    
            const mobile = $(mobile_in);
            const new_parent = $(new_parent_in);

            var src = dojo.position(mobile);
            if (place_position)
                mobile.style.position = place_position;
            dojo.place(mobile, new_parent, relation);
            mobile.offsetTop;//force re-flow
            var tgt = dojo.position(mobile);
            var box = dojo.marginBox(mobile);
            var cbox = dojo.contentBox(mobile);
            var left = box.l + src.x - tgt.x;
            var top = box.t + src.y - tgt.y;

            mobile.style.position = "absolute";
            mobile.style.left = left + "px";
            mobile.style.top = top + "px";
            box.l += box.w - cbox.w;
            box.t += box.h - cbox.h;
            mobile.offsetTop;//force re-flow
            return box;
        },

    addEtatPoignee: function (id, porte, etat)
        {
            if ((porte != 3)&&(porte != 4)&&(porte != 5))
            {
            dojo.place( this.format_block( 'jstpl_etatpoignee', {
                id: id,
                porte: porte,
                etat: etat,
                                       
            } ) , 'poignee_'+porte+'_'+id );
            }
            if ((porte == 3)||(porte == 4)||(porte == 5))
            {
                if(etat == 3)
                {
                    dojo.place( this.format_block( 'jstpl_etatpoignee', {
                        id: id,
                        porte: porte,
                        etat: 3,
                                               
                    } ) , 'poignee_'+porte+'_'+id );
                }

            }

        },

        addRound: function (id, round)
        {
            
            dojo.place( this.format_block( 'jstpl_round', {
                id: id,
                rd: round,
                                                      
            } ) , 'round_'+round+'_'+id );
                        

        },

        addMoney: function (id, gain, use)
        {
            
            if(( use >=1)&&(use<=7))
            {
                for (var m =1; m<=use; m++)
                {
                dojo.place( this.format_block( 'jstpl_money', {
                    id: id,
                    pos: m,
                    etat: 3,
                                                          
                } ) , 'money_'+m+'_'+id );
                }
            }

            if ((gain >= 8)&&(use<=7))
            {
                for (var g =8; g<=gain; g++)
                {
                dojo.place( this.format_block( 'jstpl_money', {
                    id: id,
                    pos: g,
                    etat: 1,
                                                          
                } ) , 'money_'+g+'_'+id );
                }
            }

            if ((gain >= 8)&&(use>=8))
            {
                var diff = use -7;
                for (var m =1; m<=7; m++)
                {
                dojo.place( this.format_block( 'jstpl_money', {
                    id: id,
                    pos: m,
                    etat: 3,
                                                          
                } ) , 'money_'+m+'_'+id );
                }
                for (var c =8; c <=(7+diff); c++)
                {
                dojo.place( this.format_block( 'jstpl_money', {
                    id: id,
                    pos: c,
                    etat: 2,
                                                          
                } ) , 'money_'+c+'_'+id );
                } 

                for (var d =8+diff; d<=gain; d++)
                {
                dojo.place( this.format_block( 'jstpl_money', {
                    id: id,
                    pos: d,
                    etat: 1,
                                                          
                } ) , 'money_'+d+'_'+id );
                }

            }

        },

        addGain: function (id, nbre, before)
        {
            for (var g=before+1 ; g<=before+nbre; g++)
            {
            dojo.place( this.format_block( 'jstpl_money', {
                id: id,
                pos: g,
                etat: 1,
                                                      
            } ) , 'money_'+g+'_'+id );

            }
                        

        },

        addSpend: function (id, nbre, before)
        {
            for (var g=before+1 ; g<=before+nbre; g++)
            {
            if((g>=1)&&(g<=7))
            {
            dojo.place( this.format_block( 'jstpl_money', {
                id: id,
                pos: g,
                etat: 3,
                                                      
            } ) , 'money_'+g+'_'+id );
            }

            if(g>=8)
            {
            dojo.destroy('etatmoney_'+id+'_'+g);
            dojo.place( this.format_block( 'jstpl_money', {
                id: id,
                pos: g,
                etat: 2,
                                                      
            } ) , 'money_'+g+'_'+id );
            }

            }
                        

        },

        addReduceSpend: function (id, before, reduce)
        {
                        
            for (var i=before-reduce+1; i<= before; i++)
            {
                dojo.destroy('etatmoney_'+id+'_'+i);
                if (i>=8)
                {
                    dojo.place( this.format_block( 'jstpl_money', {
                        id: id,
                        pos: i,
                        etat: 1,
                    } ) , 'money_'+i+'_'+id );

                }
            }
            
                        

        },


        addCredit: function (id, use)
        {
                        
            dojo.place( this.format_block( 'jstpl_credit', {
                id: id,
                pos: use,
                
                                                      
            } ) , 'credit_'+use+'_'+id );
            
                        

        },

        addBonusEtage: function (id, tableau)
        {
                        
            for( var i=1; i<=15; i++)
            {
                dojo.place( this.format_block( 'jstpl_etage', {
                    id: id,
                    pos: i,
                    etat: tableau[i-1],
                    
                                                          
                } ) , 'etage_'+i+'_'+id );
            }
            
                        

        },

        addGainBonusEtage: function (id, etage)
        {
            for (player_id in this.players)
            {
                if (id == player_id)
                {
                    dojo.place( this.format_block( 'jstpl_etage', {
                        id: player_id,
                        pos: etage,
                        etat: 1,
                        
                                                              
                    } ) , 'etage_'+etage+'_'+player_id );
                }

                else
                {
                    dojo.place( this.format_block( 'jstpl_etage', {
                        id: player_id,
                        pos: etage,
                        etat: 2,
                        
                                                              
                    } ) , 'etage_'+etage+'_'+player_id );
                }
            }
         

        },

        addEmperorTrack: function (id, emperor1, emperor2, emperor3)
        {
            if (emperor1 !=0) 
            {
                for(var i = 1; i<= emperor1; i++)
                {
                    dojo.place( this.format_block( 'jstpl_emperor', {
                        id: id,
                        pos: i,
                        etat: 1,
                        
                                                              
                    } ) , 'emperortrack_'+i+'_'+id );
                }
            }
            
            if (emperor2 !=0) 
            {
                for(var j = 1; j<= emperor2; j++)
                {
                    dojo.place( this.format_block( 'jstpl_emperor', {
                        id: id,
                        pos: j+5,
                        etat: 1,
                        
                                                              
                    } ) , 'emperortrack_'+(j+5)+'_'+id );
                }
            }

            if ((emperor3 !=0)&&(emperor3<=7))
            {
                for(var k = 1; k<= emperor3; k++)
                {
                    dojo.place( this.format_block( 'jstpl_emperor', {
                        id: id,
                        pos: k+11,
                        etat: 1,
                        
                                                              
                    } ) , 'emperortrack_'+(k+11)+'_'+id );
                }
            }

            if ((emperor3 !=0)&&(emperor3>7))
            {
                for(var k = 1; k<= 7; k++)
                {
                    dojo.place( this.format_block( 'jstpl_emperor', {
                        id: id,
                        pos: k+11,
                        etat: 1,
                        
                                                              
                    } ) , 'emperortrack_'+(k+11)+'_'+id );
                }

                for(var l = 8; l<=emperor3; l++)
                {
                    dojo.place( this.format_block( 'jstpl_emperor', {
                        id: id,
                        pos: l+11,
                        etat: 2,
                        
                                                              
                    } ) , 'emperortrack_'+(l+11)+'_'+id );
                }

            }
            
            
                        

        },

        addGainEmperor: function (id, pos, piste)
        {
                        
            if(piste == 1)
            {
                dojo.place( this.format_block( 'jstpl_emperor', {
                    id: id,
                    pos: pos,
                    etat: 1,
                    
                                                          
                } ) , 'emperortrack_'+pos+'_'+id );

            }

            if(piste == 2)
            {
                dojo.place( this.format_block( 'jstpl_emperor', {
                    id: id,
                    pos: pos+5,
                    etat: 1,
                    
                                                          
                } ) , 'emperortrack_'+(pos+5)+'_'+id );
                
            }

            if(piste == 3)
            {
                if(pos <= 7)
                {
                dojo.place( this.format_block( 'jstpl_emperor', {
                    id: id,
                    pos: pos+11,
                    etat: 1,
                    
                                                          
                } ) , 'emperortrack_'+(pos+11)+'_'+id );

                }

                if(pos > 7)
                {
                dojo.place( this.format_block( 'jstpl_emperor', {
                    id: id,
                    pos: pos+11,
                    etat: 2,
                    
                                                          
                } ) , 'emperortrack_'+(pos+11)+'_'+id );

                }
            }
            
                        

        },

        addBonusEmperor: function (id, bonus1, bonus2, bonus3)
        {
            if(bonus1==1)
            {
                dojo.place( this.format_block( 'jstpl_emperorbonus', {
                    id: id,
                    pos: 1,
                    etat: 1,
                    
                                                          
                } ) , 'emperortrackbonus_'+1+'_'+id );

            }
            if(bonus1==2)
            {
                dojo.place( this.format_block( 'jstpl_emperorbonus', {
                    id: id,
                    pos: 1,
                    etat: 2,
                    
                                                          
                } ) , 'emperortrackbonus_'+1+'_'+id );

            }
            if(bonus2==1)
            {
                dojo.place( this.format_block( 'jstpl_emperorbonus', {
                    id: id,
                    pos: 2,
                    etat: 1,
                    
                                                          
                } ) , 'emperortrackbonus_'+2+'_'+id );

            }
            if(bonus2==2)
            {
                dojo.place( this.format_block( 'jstpl_emperorbonus', {
                    id: id,
                    pos: 2,
                    etat: 2,
                    
                                                          
                } ) , 'emperortrackbonus_'+2+'_'+id );

            }
            if(bonus3==1)
            {
                dojo.place( this.format_block( 'jstpl_emperorbonus', {
                    id: id,
                    pos: 3,
                    etat: 1,
                    
                                                          
                } ) , 'emperortrackbonus_'+3+'_'+id );

            }
            if(bonus3==2)
            {
                dojo.place( this.format_block( 'jstpl_emperorbonus', {
                    id: id,
                    pos: 3,
                    etat: 2,
                    
                                                          
                } ) , 'emperortrackbonus_'+3+'_'+id );

            }


            


        },

        addGainBonusEmperor: function (id, bonus, etat)
        {
            if(etat ==1)
            {
                dojo.place( this.format_block( 'jstpl_emperorbonus', {
                    id: id,
                    pos: bonus,
                    etat: 1,
                    
                                                          
                } ) , 'emperortrackbonus_'+bonus+'_'+id );

            }

            if(etat ==2)
            {
                dojo.place( this.format_block( 'jstpl_emperorbonus', {
                    id: id,
                    pos: bonus,
                    etat: 2,
                    
                                                          
                } ) , 'emperortrackbonus_'+bonus+'_'+id );
                
            }

        },

        addMalusEmperor: function (id, malus1, malus2, malus3)
        {
            if(malus1==1)
            {
                dojo.place( this.format_block( 'jstpl_emperormalus', {
                    id: id,
                    pos: 1,
                    etat: 1,
                    
                                                          
                } ) , 'emperortrackmalus_'+1+'_'+id );

            }

            if(malus2==1)
            {
                dojo.place( this.format_block( 'jstpl_emperormalus', {
                    id: id,
                    pos: 2,
                    etat: 1,
                    
                                                          
                } ) , 'emperortrackmalus_'+2+'_'+id );

            }

            if(malus3==1)
            {
                dojo.place( this.format_block( 'jstpl_emperormalus', {
                    id: id,
                    pos: 3,
                    etat: 1,
                    
                                                          
                } ) , 'emperortrackmalus_'+3+'_'+id );

            }
            

            


        },

        addGainMalusEmperor: function (id, track)
        {
            
                dojo.place( this.format_block( 'jstpl_emperormalus', {
                    id: id,
                    pos: track,
                    etat: 1,
                    
                                                          
                } ) , 'emperortrackmalus_'+track+'_'+id );

            

        },

        addStaff: function (id, pos, etat)
        {
            if (etat ==1)
            {
            dojo.place( this.format_block( 'jstpl_staff', {
                id: id,
                pos: pos,
                
                
                                                      
            } ) , 'staff_'+pos+'_'+id );
           
            }
        },

        addGainStaff: function (id, pos)
        {
            
            dojo.place( this.format_block( 'jstpl_staff', {
                id: id,
                pos: pos,
                
                
                                                      
            } ) , 'staff_'+pos+'_'+id );
           
            
        },

        addDice: function (dice1, dice2, dice3, dice4, dice5, dice6,)
        {
            if (dice1 >=1)
            {
                for (var pos=1; pos <= dice1; pos++)
                {
                dojo.place( this.format_block( 'jstpl_dicedispo', {
                    type: 1,
                    pos: pos,
                    
                    
                                                          
                } ) , 'dicecontent_1_'+pos);
                }
            }

            if (dice2 >=1)
            {
                for (var pos=1; pos <= dice2; pos++)
                {
                dojo.place( this.format_block( 'jstpl_dicedispo', {
                    type: 2,
                    pos: pos,
                    
                    
                                                          
                } ) , 'dicecontent_2_'+pos);
                }
            }

            if (dice3 >=1)
            {
                for (var pos=1; pos <= dice3; pos++)
                {
                dojo.place( this.format_block( 'jstpl_dicedispo', {
                    type: 3,
                    pos: pos,
                    
                    
                                                          
                } ) , 'dicecontent_3_'+pos);
                }
            }

            if (dice4 >=1)
            {
                for (var pos=1; pos <= dice4; pos++)
                {
                dojo.place( this.format_block( 'jstpl_dicedispo', {
                    type: 4,
                    pos: pos,
                    
                    
                                                          
                } ) , 'dicecontent_4_'+pos);
                }
            }

            if (dice5 >=1)
            {
                for (var pos=1; pos <= dice5; pos++)
                {
                dojo.place( this.format_block( 'jstpl_dicedispo', {
                    type: 5,
                    pos: pos,
                    
                    
                                                          
                } ) , 'dicecontent_5_'+pos);
                }
            }

            if (dice6 >=1)
            {
                for (var pos=1; pos <= dice6; pos++)
                {
                dojo.place( this.format_block( 'jstpl_dicedispo', {
                    type: 6,
                    pos: pos,
                    
                    
                                                          
                } ) , 'dicecontent_6_'+pos);
                }
            }

        },

        addDice2: function (dice, pos)
        {
            
                dojo.place( this.format_block( 'jstpl_dicedispo', {
                    type: dice,
                    pos: pos,
                    
                    
                                                          
                } ) , 'dicecontent_'+dice+'_'+pos);
                
            

            

        },

        
    
    
    
    
    /////////////////////////////////////////////////////////////////////////////////  
    //         _____  _                       _                  _   _             
    //        |  __ \| |                     ( )                | | (_)            
    //        | |__) | | __ _ _   _  ___ _ __|/ ___    __ _  ___| |_ _  ___  _ __  
    //        |  ___/| |/ _` | | | |/ _ \ '__| / __|  / _` |/ __| __| |/ _ \| '_ \ 
    //        | |    | | (_| | |_| |  __/ |    \__ \ | (_| | (__| |_| | (_) | | | |
    //        |_|    |_|\__,_|\__, |\___|_|    |___/  \__,_|\___|\__|_|\___/|_| |_|
    //                         __/ |                                               
    //                        |___/                                                
    /////////////////////////////////////////////////////////////////////////////////  
    
            
            onSelect: function(evt)
            {        	 
                // Preventing default browser reaction
                 dojo.stopEvent( evt );
    
                
                 
                if( !this.isCurrentPlayerActive() || !(evt.currentTarget.classList.contains('selectable')) )
                {   
                    return; 
                }
                
                if(this.isCurrentPlayerActive() && evt.currentTarget.classList.contains('selectable') && this.checkAction( "actSelect" ))
                {
                    
                    this.ajaxcall( "/highseason/highseason/actSelect.html", { 
                        lock: true,
                        arg1: evt.currentTarget.id
                        
                     }, 
                     this, function( result ) {}, function( is_error) {} );
                }
    
                
    
    
            },
    
            onOpButton: function(evt)
            {
                dojo.stopEvent( evt );
                this.ajaxcall( "/highseason/highseason/actButton.html", { 
                    lock: true,
                    arg1: evt.currentTarget.id
                                
                    }, 
                    this, function( result ) {}, function( is_error) {} );
    
            },

            onOpUndo: function(evt)
            {
                dojo.stopEvent( evt );
                this.ajaxcall( "/highseason/highseason/actUndo.html", { 
                    lock: true,
                    
                                
                    }, 
                    this, function( result ) {}, function( is_error) {} );

            },

            onNext: function(evt)
            {        	 
                // Preventing default browser reaction
                dojo.stopEvent( evt );

                var elements = document.querySelectorAll('[id^="playerview"]:not(.masque)');
                var idsSansMasque = [];
                elements.forEach(function(element) {
                    // Obtenez l'ID de chaque élément
                    var id = element.id;
                    
                    // Ajoutez l'ID à la liste
                    idsSansMasque.push(id);
                });

                var elementall = document.querySelectorAll('[id^="playerview"]');
                var idsAll = [];
                // Parcourez les éléments et affichez leur ID
                elementall.forEach(function(element) {
                    var id = element.id;
                    idsAll.push(id);
                });

                
                var variable = idsSansMasque[0];
                var index = idsAll.indexOf(variable);
                               
                if(index != (this.gamedatas.countplayers[0]-1))
                {
                    dojo.query("#"+variable).addClass("masque");
                    dojo.query("#"+idsAll[index+1]).removeClass("masque");

                }

                if(index == (this.gamedatas.countplayers[0]-1))
                {
                    dojo.query("#"+variable).addClass("masque");
                    dojo.query("#"+idsAll[0]).removeClass("masque");

                }
    

                
                

            },

            onPrev: function(evt)
            {        	 
                // Preventing default browser reaction
                dojo.stopEvent( evt );

                var elements = document.querySelectorAll('[id^="playerview"]:not(.masque)');
                var idsSansMasque = [];
                elements.forEach(function(element) {
                    // Obtenez l'ID de chaque élément
                    var id = element.id;
                    
                    // Ajoutez l'ID à la liste
                    idsSansMasque.push(id);
                });

                var elementall = document.querySelectorAll('[id^="playerview"]');
                var idsAll = [];
                // Parcourez les éléments et affichez leur ID
                elementall.forEach(function(element) {
                    var id = element.id;
                    idsAll.push(id);
                });

                
                var variable = idsSansMasque[0];
                var index = idsAll.indexOf(variable);
                

               
                if(index != 0)
                {
                    dojo.query("#"+variable).addClass("masque");
                    dojo.query("#"+idsAll[index-1]).removeClass("masque");

                }

                if(index == 0)
                {
                    dojo.query("#"+variable).addClass("masque");
                    dojo.query("#"+idsAll[this.gamedatas.countplayers[0]-1]).removeClass("masque");

                }
    

            },

            onEye: function(evt)
            {        	 
                // Preventing default browser reaction
                dojo.stopEvent( evt );

                var selection = evt.currentTarget.id;
                var nombre = selection.match(/\d+/);

                var elements = document.querySelectorAll('[id^="playerview"]:not(.masque)');
                var idsSansMasque = [];
                elements.forEach(function(element) {
                        // Obtenez l'ID de chaque élément
                        var id = element.id;
                        
                        // Ajoutez l'ID à la liste
                        idsSansMasque.push(id);
                });

                    dojo.query("#"+idsSansMasque[0]).addClass("masque");
                    dojo.query("#playerview_"+nombre[0]).removeClass("masque");

                
                

            },
            
            
    
    ///////////////////////////////////////////////////////////////////////////////// 
    //       _   _       _   _  __ _           _   _                 
    //      | \ | |     | | (_)/ _(_)         | | (_)                
    //      |  \| | ___ | |_ _| |_ _  ___ __ _| |_ _  ___  _ __  ___ 
    //      | . ` |/ _ \| __| |  _| |/ __/ _` | __| |/ _ \| '_ \/ __|
    //      | |\  | (_) | |_| | | | | (_| (_| | |_| | (_) | | | \__ \
    //      |_| \_|\___/ \__|_|_| |_|\___\__,_|\__|_|\___/|_| |_|___/
    //                                                                 
    /////////////////////////////////////////////////////////////////////////////////  
    
    
            setupNotifications: function()
            {
                console.log( 'notifications subscriptions setup' );
                
                // TODO: here, associate your game notifications with local methods
                
                // Example 1: standard notification handling
                // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
                
                // Example 2: standard notification handling + tell the user interface to wait
                //            during 3 seconds after calling the method in order to let the players
                //            see what is happening in the game.
                // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
                // this.notifqueue.setSynchronous( 'cardPlayed', 3000 );
                // 

                dojo.subscribe( 'preparing', this, "notif_preparing" );
                dojo.subscribe( 'occupying', this, "notif_occupying" );
                dojo.subscribe( 'dice', this, "notif_dice" );
                dojo.subscribe( 'gain', this, "notif_gain" );
                dojo.subscribe( 'spend', this, "notif_spend" );
                dojo.subscribe( 'reducespend', this, "notif_reducespend" );
                dojo.subscribe( 'reducedice', this, "notif_reducedice" );
                dojo.subscribe( 'credit', this, "notif_credit" );
                dojo.subscribe( 'gainbonusetage', this, "notif_gainbonusetage" );
                dojo.subscribe( 'majscore', this, "notif_majscore" );
                dojo.subscribe( 'gainemperor', this, "notif_gainemperor" );
                dojo.subscribe( 'gainbonusemperor', this, "notif_gainbonusemperor" );
                dojo.subscribe( 'gainmalusemperor', this, "notif_gainmalusemperor" );
                dojo.subscribe( 'gainstaff', this, "notif_gainstaff" );
                dojo.subscribe( 'turn', this, "notif_turn" );
                dojo.subscribe( 'round', this, "notif_round" );
                dojo.subscribe( 'changementfirst', this, "notif_changementfirst" );
                dojo.subscribe( 'destroydice', this, "notif_destroydice" );

                
                
            },  

            notif_changementfirst: function( notif )
            {
                dojo.destroy('first');
                dojo.place( this.format_block('jstpl_first', {} ), 'player_board_'+notif.args.first);
                
            },
            
            notif_preparing: function( notif )
            {
                this.addEtatPoignee (notif.args.id, notif.args.porte, 1);
            },

            notif_occupying: function( notif )
            {
                if ((notif.args.porte >=3)&&(notif.args.porte<=5))
                {
                this.addEtatPoignee (notif.args.id, notif.args.porte, 3);
                }
                else
                {
                    dojo.destroy('etatpoignee_'+notif.args.id+'_'+notif.args.porte);
                    this.addEtatPoignee (notif.args.id, notif.args.porte, 2);

                }
            },

            notif_dice: function( notif )
            {
                /*$('dice_1').innerHTML = notif.args.dice1;
                $('dice_2').innerHTML = notif.args.dice2; 
                $('dice_3').innerHTML = notif.args.dice3;
                $('dice_4').innerHTML = notif.args.dice4; 
                $('dice_5').innerHTML = notif.args.dice5; 
                $('dice_6').innerHTML = notif.args.dice6;*/

                if(notif.args.dice1>=1)
                {
                    for (var i=1; i<=notif.args.dice1; i++)
                    {
                    this.addDice2 (1, i);
                    this.placeOnObject( "dicedispo_1_"+i, "overall_player_board_"+notif.args.playerid);
                    this.slideToObject( "dicedispo_1_"+i, "dicecontent_1_"+i ).play();
                    }
                }
                if(notif.args.dice2>=1)
                {
                    for (var i=1; i<=notif.args.dice2; i++)
                    {
                    this.addDice2 (2, i);
                    this.placeOnObject( "dicedispo_2_"+i, "overall_player_board_"+notif.args.playerid);
                    this.slideToObject( "dicedispo_2_"+i, "dicecontent_2_"+i ).play();
                    }
                }
                if(notif.args.dice3>=1)
                {
                    for (var i=1; i<=notif.args.dice3; i++)
                    {
                    this.addDice2 (3, i);
                    this.placeOnObject( "dicedispo_3_"+i, "overall_player_board_"+notif.args.playerid);
                    this.slideToObject( "dicedispo_3_"+i, "dicecontent_3_"+i ).play();
                    }
                }
                if(notif.args.dice4>=1)
                {
                    for (var i=1; i<=notif.args.dice4; i++)
                    {
                    this.addDice2 (4, i);
                    this.placeOnObject( "dicedispo_4_"+i, "overall_player_board_"+notif.args.playerid);
                    this.slideToObject( "dicedispo_4_"+i, "dicecontent_4_"+i ).play();
                    }
                }
                if(notif.args.dice5>=1)
                {
                    for (var i=1; i<=notif.args.dice5; i++)
                    {
                    this.addDice2 (5, i);
                    this.placeOnObject( "dicedispo_5_"+i, "overall_player_board_"+notif.args.playerid);
                    this.slideToObject( "dicedispo_5_"+i, "dicecontent_5_"+i ).play();
                    }
                }
                if(notif.args.dice6>=1)
                {
                    for (var i=1; i<=notif.args.dice6; i++)
                    {
                    this.addDice2 (6, i);
                    this.placeOnObject( "dicedispo_6_"+i, "overall_player_board_"+notif.args.playerid);
                    this.slideToObject( "dicedispo_6_"+i, "dicecontent_6_"+i ).play();
                    }
                }
                
            },

            notif_destroydice: function( notif )
            {
                if (notif.args.dice1 >=1)
                {
                    for (var i=1; i<=notif.args.dice1; i++)
                    {
                        this.fadeOutAndDestroy( "dicedispo_1_"+i );
                    }
                }
                if (notif.args.dice2 >=1)
                {
                    for (var i=1; i<=notif.args.dice2; i++)
                    {
                        this.fadeOutAndDestroy( "dicedispo_2_"+i );
                    }
                }
                if (notif.args.dice3 >=1)
                {
                    for (var i=1; i<=notif.args.dice3; i++)
                    {
                        this.fadeOutAndDestroy( "dicedispo_3_"+i );
                    }
                }
                if (notif.args.dice4 >=1)
                {
                    for (var i=1; i<=notif.args.dice4; i++)
                    {
                        this.fadeOutAndDestroy( "dicedispo_4_"+i );
                    }
                }
                if (notif.args.dice5 >=1)
                {
                    for (var i=1; i<=notif.args.dice5; i++)
                    {
                        this.fadeOutAndDestroy( "dicedispo_5_"+i );
                    }
                }
                if (notif.args.dice6 >=1)
                {
                    for (var i=1; i<=notif.args.dice6; i++)
                    {
                        this.fadeOutAndDestroy( "dicedispo_6_"+i );
                    }
                }
                
            },

            notif_turn: function( notif )
            {

                $('compteurturn').innerHTML = notif.args.turn;
                
            },

            notif_round: function( notif )
            {
                
                $('compteurround').innerHTML = notif.args.round;

                for(var player in notif.args.players)
                {
                    this.addRound (notif.args.players[player], notif.args.round);
                }
                
                
            },

            notif_gain: function( notif )
            {
                this.addGain (notif.args.id, notif.args.nbre, notif.args.moneygainbefore);
                
            },

            notif_spend: function( notif )
            {
                
                this.addSpend (notif.args.id, notif.args.nbre, notif.args.moneyusebefore);
            },

            notif_reducespend: function( notif )
            {
                
                this.addReduceSpend (notif.args.id, notif.args.before, notif.args.reduce);
            },

            notif_reducedice: function( notif )
            {
                //$('dice_'+notif.args.valeur).innerHTML = notif.args.newcount;
                
                this.slideToObjectAndDestroy( "dicedispo_"+notif.args.valeur+"_"+notif.args.position, "overall_player_board_"+notif.args.player, 1000, 0 );
                
            },

            notif_credit: function( notif )
            {
                this.addCredit (notif.args.id, notif.args.credit);
                
                
            },

            notif_gainbonusetage: function( notif )
            {
                this.addGainBonusEtage (notif.args.id, notif.args.etage);
                
                
            },

            notif_majscore: function( notif )
            {
                if (notif.args.score1 != 0)
                {
                    $('score_1_'+notif.args.id).innerHTML = notif.args.score1;
                }
                if (notif.args.score1 == 0)
                {
                    $('score_1_'+notif.args.id).innerHTML = '';
                }
                if (notif.args.score2 != 0)
                {
                    $('score_2_'+notif.args.id).innerHTML = notif.args.score2;
                }
                if (notif.args.score2 == 0)
                {
                    $('score_2_'+notif.args.id).innerHTML = '';
                }
                if (notif.args.score3 != 0)
                {
                    $('score_3_'+notif.args.id).innerHTML = notif.args.score3;
                }
                if (notif.args.score3 == 0)
                {
                    $('score_3_'+notif.args.id).innerHTML = '';
                }
                if (notif.args.score4 != 0)
                {
                    $('score_4_'+notif.args.id).innerHTML = notif.args.score4;
                }
                if (notif.args.score4 == 0)
                {
                    $('score_4_'+notif.args.id).innerHTML = '';
                }
                if (notif.args.score5 != 0)
                {
                    $('score_5_'+notif.args.id).innerHTML = notif.args.score5;
                }
                if (notif.args.score5 == 0)
                {
                    $('score_5_'+notif.args.id).innerHTML = '';
                }
                if (notif.args.score6 != 0)
                {
                    $('score_6_'+notif.args.id).innerHTML = notif.args.score6;
                }
                if (notif.args.score6 == 0)
                {
                    $('score_6_'+notif.args.id).innerHTML = '';
                }
                if (notif.args.score7 != 0)
                {
                    $('score_7_'+notif.args.id).innerHTML = notif.args.score7;
                }
                if (notif.args.score7 == 0)
                {
                    $('score_7_'+notif.args.id).innerHTML = '';
                }
                if (notif.args.score8 != 0)
                {
                    $('score_8_'+notif.args.id).innerHTML = notif.args.score8;
                }
                if (notif.args.score8 <= 0)
                {
                    $('score_8_'+notif.args.id).innerHTML = '';
                }
                if (notif.args.score9 != 0)
                {
                    $('score_9_'+notif.args.id).innerHTML = notif.args.score9;
                }
                if (notif.args.score9 == 0)
                {
                    $('score_9_'+notif.args.id).innerHTML = '';
                }
                if (notif.args.score10 != 0)
                {
                    $('score_10_'+notif.args.id).innerHTML = notif.args.score10;
                }
                if (notif.args.score10 == 0)
                {
                    $('score_10_'+notif.args.id).innerHTML = '';
                }

                this.scoreCtrl[ notif.args.id ].toValue( notif.args.scoretotal );
                               
                
            },



            notif_gainemperor: function( notif )
            {
                if (notif.args.before1 != notif.args.after1)
                {
                    var before1 = parseInt(notif.args.before1);
                    var after1 = parseInt(notif.args.after1);
                    for (var i = (before1+1); i<= after1; i++ )
                    {
                            
                    this.addGainEmperor (notif.args.id, i, 1);
                    }
                }

                if (notif.args.before2 != notif.args.after2)
                {
                    var before2 = parseInt(notif.args.before2);
                    var after2 = parseInt(notif.args.after2);
                    for (var j = (before2+1); j<=after2; j++ )
                    {
                    this.addGainEmperor (notif.args.id, j, 2);
                    }
                    
                }

                if (notif.args.before3 != notif.args.after3)
                {
                    var before3 = parseInt(notif.args.before3);
                    var after3 = parseInt(notif.args.after3);
                    for (var k = (before3+1); k<=after3; k++ )
                    {
                    this.addGainEmperor (notif.args.id, k, 3);
                    }
                    
                }
                
                
            },

            notif_gainbonusemperor: function( notif )
            {
                this.addGainBonusEmperor (notif.args.id, notif.args.bonus, notif.args.etat);
                
                
            },

            notif_gainmalusemperor: function( notif )
            {
                this.addGainMalusEmperor (notif.args.id, notif.args.track);
                
                
            },

            notif_gainstaff: function( notif )
            {
                this.addGainStaff (notif.args.id, notif.args.pos);
                
                
            },
            






            
       });             
    });
    