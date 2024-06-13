{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: Gregory Isabelli & Emmanuel Colin & BoardGameArena
-- highseason implementation : © <Mathieu Chatrain> <mathieu.chatrain@gmail.com>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------

    highseason_highseason.tpl
    
    This is the HTML template of your game.
    
    Everything you are writing in this file will be displayed in the HTML page of your game user interface,
    in the "main game zone" of the screen.
    
    You can use in this template:
    _ variables, with the format {MY_VARIABLE_ELEMENT}.
    _ HTML block, with the BEGIN/END format
    
    See your "view" PHP file to check how to set variables and control blocks
    
    Please REMOVE this comment before publishing your game on BGA
-->

<div id="global">

<div id="boardaction">
    <div id="action_1" class="action" style="left: 0px; top: 37px;"></div>
    <div id="action_2" class="action" style="left: 0px; top: 158px;"></div>
    <div id="action_3" class="action" style="left: 0px; top: 279px;"></div>
    <div id="action_4" class="action" style="left: 0px; top: 400px;"></div>
    <div id="action_5" class="action" style="left: 0px; top: 520px;"></div>
    <div id="action_6" class="action" style="left: 0px; top: 641px;"></div>

<!-- 
    <div id="dice_1" class="dice" style="left: 3px; top: 3px;"></div>
    <div id="dice_2" class="dice" style="left: 3px; top: 124px;"></div>
    <div id="dice_3" class="dice" style="left: 3px; top: 245px;"></div>
    <div id="dice_4" class="dice" style="left: 3px; top: 366px;"></div>
    <div id="dice_5" class="dice" style="left: 3px; top: 487px;"></div>
    <div id="dice_6" class="dice" style="left: 3px; top: 608px;"></div>
-->

<!-- 
    <div id="diceicon_1" style="left: -12px; top: -11px;"></div>
    <div id="diceicon_2" style="left: -12px; top: 110px;"></div>
    <div id="diceicon_3" style="left: -12px; top: 231px;"></div>
    <div id="diceicon_4" style="left: -12px; top: 352px;"></div>
    <div id="diceicon_5" style="left: -12px; top: 473px;"></div>
    <div id="diceicon_6" style="left: -12px; top: 594px;"></div>
-->

    <div id="dicecontent_1_1" class="dicecontent" style="left: -26px; top: -2px;"></div>
    <div id="dicecontent_1_2" class="dicecontent" style="left: -26px; top: 22px;"></div>
    <div id="dicecontent_1_3" class="dicecontent" style="left: -26px; top: 46px;"></div>
    <div id="dicecontent_1_4" class="dicecontent" style="left: -26px; top: 70px;"></div>
    <div id="dicecontent_1_5" class="dicecontent" style="left: -26px; top: 94px;"></div>
    <div id="dicecontent_1_6" class="dicecontent" style="left: -49px; top: -2px;"></div>
    <div id="dicecontent_1_7" class="dicecontent" style="left: -49px; top: 22px;"></div>
    <div id="dicecontent_1_8" class="dicecontent" style="left: -49px; top: 46px;"></div>
    <div id="dicecontent_1_9" class="dicecontent" style="left: -49px; top: 70px;"></div>
    <div id="dicecontent_1_10" class="dicecontent" style="left: -49px; top: 94px;"></div>
    <div id="dicecontent_1_11" class="dicecontent" style="left: -73px; top: -2px;"></div>
    <div id="dicecontent_1_12" class="dicecontent" style="left: -73px; top: 22px;"></div>
    <div id="dicecontent_1_13" class="dicecontent" style="left: -73px; top: 46px;"></div>
    <div id="dicecontent_1_14" class="dicecontent" style="left: -73px; top: 70px;"></div>
    
    <div id="dicecontent_2_1" class="dicecontent" style="left: -26px; top: 119px;"></div>
    <div id="dicecontent_2_2" class="dicecontent" style="left: -26px; top: 143px;"></div>
    <div id="dicecontent_2_3" class="dicecontent" style="left: -26px; top: 167px;"></div>
    <div id="dicecontent_2_4" class="dicecontent" style="left: -26px; top: 191px;"></div>
    <div id="dicecontent_2_5" class="dicecontent" style="left: -26px; top: 215px;"></div>
    <div id="dicecontent_2_6" class="dicecontent" style="left: -49px; top: 119px;"></div>
    <div id="dicecontent_2_7" class="dicecontent" style="left: -49px; top: 143px;"></div>
    <div id="dicecontent_2_8" class="dicecontent" style="left: -49px; top: 167px;"></div>
    <div id="dicecontent_2_9" class="dicecontent" style="left: -49px; top: 191px;"></div>
    <div id="dicecontent_2_10" class="dicecontent" style="left: -49px; top: 215px;"></div>
    <div id="dicecontent_2_11" class="dicecontent" style="left: -73px; top: 119px;"></div>
    <div id="dicecontent_2_12" class="dicecontent" style="left: -73px; top: 143px;"></div>
    <div id="dicecontent_2_13" class="dicecontent" style="left: -73px; top: 167px;"></div>
    <div id="dicecontent_2_14" class="dicecontent" style="left: -73px; top: 191px;"></div>
    

    <div id="dicecontent_3_1" class="dicecontent" style="left: -26px; top: 240px;"></div>
    <div id="dicecontent_3_2" class="dicecontent" style="left: -26px; top: 264px;"></div>
    <div id="dicecontent_3_3" class="dicecontent" style="left: -26px; top: 288px;"></div>
    <div id="dicecontent_3_4" class="dicecontent" style="left: -26px; top: 312px;"></div>
    <div id="dicecontent_3_5" class="dicecontent" style="left: -26px; top: 336px;"></div>
    <div id="dicecontent_3_6" class="dicecontent" style="left: -49px; top: 240px;"></div>
    <div id="dicecontent_3_7" class="dicecontent" style="left: -49px; top: 264px;"></div>
    <div id="dicecontent_3_8" class="dicecontent" style="left: -49px; top: 288px;"></div>
    <div id="dicecontent_3_9" class="dicecontent" style="left: -49px; top: 312px;"></div>
    <div id="dicecontent_3_10" class="dicecontent" style="left: -49px; top: 336px;"></div>
    <div id="dicecontent_3_11" class="dicecontent" style="left: -73px; top: 240px;"></div>
    <div id="dicecontent_3_12" class="dicecontent" style="left: -73px; top: 264px;"></div>
    <div id="dicecontent_3_13" class="dicecontent" style="left: -73px; top: 288px;"></div>
    <div id="dicecontent_3_14" class="dicecontent" style="left: -73px; top: 312px;"></div>
    

    <div id="dicecontent_4_1" class="dicecontent" style="left: -26px; top: 361px;"></div>
    <div id="dicecontent_4_2" class="dicecontent" style="left: -26px; top: 385px;"></div>
    <div id="dicecontent_4_3" class="dicecontent" style="left: -26px; top: 409px;"></div>
    <div id="dicecontent_4_4" class="dicecontent" style="left: -26px; top: 433px;"></div>
    <div id="dicecontent_4_5" class="dicecontent" style="left: -26px; top: 457px;"></div>
    <div id="dicecontent_4_6" class="dicecontent" style="left: -49px; top: 361px;"></div>
    <div id="dicecontent_4_7" class="dicecontent" style="left: -49px; top: 385px;"></div>
    <div id="dicecontent_4_8" class="dicecontent" style="left: -49px; top: 409px;"></div>
    <div id="dicecontent_4_9" class="dicecontent" style="left: -49px; top: 433px;"></div>
    <div id="dicecontent_4_10" class="dicecontent" style="left: -49px; top: 457px;"></div>
    <div id="dicecontent_4_11" class="dicecontent" style="left: -73px; top: 361px;"></div>
    <div id="dicecontent_4_12" class="dicecontent" style="left: -73px; top: 385px;"></div>
    <div id="dicecontent_4_13" class="dicecontent" style="left: -73px; top: 409px;"></div>
    <div id="dicecontent_4_14" class="dicecontent" style="left: -73px; top: 433px;"></div>
    

    <div id="dicecontent_5_1" class="dicecontent" style="left: -26px; top: 482px;"></div>
    <div id="dicecontent_5_2" class="dicecontent" style="left: -26px; top: 506px;"></div>
    <div id="dicecontent_5_3" class="dicecontent" style="left: -26px; top: 530px;"></div>
    <div id="dicecontent_5_4" class="dicecontent" style="left: -26px; top: 554px;"></div>
    <div id="dicecontent_5_5" class="dicecontent" style="left: -26px; top: 578px;"></div>
    <div id="dicecontent_5_6" class="dicecontent" style="left: -49px; top: 482px;"></div>
    <div id="dicecontent_5_7" class="dicecontent" style="left: -49px; top: 506px;"></div>
    <div id="dicecontent_5_8" class="dicecontent" style="left: -49px; top: 530px;"></div>
    <div id="dicecontent_5_9" class="dicecontent" style="left: -49px; top: 554px;"></div>
    <div id="dicecontent_5_10" class="dicecontent" style="left: -49px; top: 578px;"></div>
    <div id="dicecontent_5_11" class="dicecontent" style="left: -73px; top: 482px;"></div>
    <div id="dicecontent_5_12" class="dicecontent" style="left: -73px; top: 506px;"></div>
    <div id="dicecontent_5_13" class="dicecontent" style="left: -73px; top: 530px;"></div>
    <div id="dicecontent_5_14" class="dicecontent" style="left: -73px; top: 554px;"></div>
    

    <div id="dicecontent_6_1" class="dicecontent" style="left: -26px; top: 603px;"></div>
    <div id="dicecontent_6_2" class="dicecontent" style="left: -26px; top: 627px;"></div>
    <div id="dicecontent_6_3" class="dicecontent" style="left: -26px; top: 651px;"></div>
    <div id="dicecontent_6_4" class="dicecontent" style="left: -26px; top: 675px;"></div>
    <div id="dicecontent_6_5" class="dicecontent" style="left: -26px; top: 699px;"></div>
    <div id="dicecontent_6_6" class="dicecontent" style="left: -49px; top: 603px;"></div>
    <div id="dicecontent_6_7" class="dicecontent" style="left: -49px; top: 627px;"></div>
    <div id="dicecontent_6_8" class="dicecontent" style="left: -49px; top: 651px;"></div>
    <div id="dicecontent_6_9" class="dicecontent" style="left: -49px; top: 675px;"></div>
    <div id="dicecontent_6_10" class="dicecontent" style="left: -49px; top: 699px;"></div>
    <div id="dicecontent_6_11" class="dicecontent" style="left: -73px; top: 603px;"></div>
    <div id="dicecontent_6_12" class="dicecontent" style="left: -73px; top: 627px;"></div>
    <div id="dicecontent_6_13" class="dicecontent" style="left: -73px; top: 651px;"></div>
    <div id="dicecontent_6_14" class="dicecontent" style="left: -73px; top: 675px;"></div>
    

    

</div>

    



<!-- BEGIN player -->
    <div id="playerview_{PLAYER_ID}" class="titlename" style="color:#{COLOR}; outline: 1px solid #{COLOR};"><div class="left"><<<&nbsp;&nbsp;&nbsp;</div>{PLAYER_NAME}<div class="right">&nbsp;&nbsp;&nbsp;>>></div>
        <div id="playerborder_{PLAYER_ID}" class="playerborder" style="border: 1px solid #{COLOR};">
            <div id="hotel_{PLAYER_ID}">
                <div id="poignee_1_{PLAYER_ID}" class="poigneecontent" style="left: 262px; top: 379px;"></div>
                <div id="poignee_2_{PLAYER_ID}" class="poigneecontent" style="left: 343px; top: 379px;"></div>
                <div id="poignee_3_{PLAYER_ID}" class="poigneecontent" style="left: 425px; top: 379px;"></div>
                <div id="poignee_4_{PLAYER_ID}" class="poigneecontent" style="left: 506px; top: 379px;"></div>
                <div id="poignee_5_{PLAYER_ID}" class="poigneecontent" style="left: 716px; top: 379px;"></div>
                <div id="poignee_6_{PLAYER_ID}" class="poigneecontent" style="left: 797px; top: 379px;"></div>
                <div id="poignee_7_{PLAYER_ID}" class="poigneecontent" style="left: 878px; top: 379px;"></div>
                <div id="poignee_8_{PLAYER_ID}" class="poigneecontent" style="left: 262px; top: 295px;"></div>
                <div id="poignee_9_{PLAYER_ID}" class="poigneecontent" style="left: 343px; top: 295px;"></div>
                <div id="poignee_10_{PLAYER_ID}" class="poigneecontent" style="left: 425px; top: 295px;"></div>
                <div id="poignee_11_{PLAYER_ID}" class="poigneecontent" style="left: 506px; top: 295px;"></div>
                <div id="poignee_12_{PLAYER_ID}" class="poigneecontent" style="left: 716px; top: 295px;"></div>
                <div id="poignee_13_{PLAYER_ID}" class="poigneecontent" style="left: 797px; top: 295px;"></div>
                <div id="poignee_14_{PLAYER_ID}" class="poigneecontent" style="left: 878px; top: 295px;"></div>
                <div id="poignee_15_{PLAYER_ID}" class="poigneecontent" style="left: 262px; top: 211px;"></div>
                <div id="poignee_16_{PLAYER_ID}" class="poigneecontent" style="left: 343px; top: 211px;"></div>
                <div id="poignee_17_{PLAYER_ID}" class="poigneecontent" style="left: 425px; top: 211px;"></div>
                <div id="poignee_18_{PLAYER_ID}" class="poigneecontent" style="left: 506px; top: 211px;"></div>
                <div id="poignee_19_{PLAYER_ID}" class="poigneecontent" style="left: 716px; top: 211px;"></div>
                <div id="poignee_20_{PLAYER_ID}" class="poigneecontent" style="left: 797px; top: 211px;"></div>
                <div id="poignee_21_{PLAYER_ID}" class="poigneecontent" style="left: 878px; top: 211px;"></div>
                <div id="poignee_22_{PLAYER_ID}" class="poigneecontent" style="left: 262px; top: 127px;"></div>
                <div id="poignee_23_{PLAYER_ID}" class="poigneecontent" style="left: 343px; top: 127px;"></div>
                <div id="poignee_24_{PLAYER_ID}" class="poigneecontent" style="left: 425px; top: 127px;"></div>
                <div id="poignee_25_{PLAYER_ID}" class="poigneecontent" style="left: 506px; top: 127px;"></div>
                <div id="poignee_26_{PLAYER_ID}" class="poigneecontent" style="left: 716px; top: 127px;"></div>
                <div id="poignee_27_{PLAYER_ID}" class="poigneecontent" style="left: 797px; top: 127px;"></div>
                <div id="poignee_28_{PLAYER_ID}" class="poigneecontent" style="left: 878px; top: 127px;"></div>

                <div id="round_1_{PLAYER_ID}" class="round" style="left: 77px; top: 408px;"></div>
                <div id="round_2_{PLAYER_ID}" class="round" style="left: 105px; top: 418px;"></div>
                <div id="round_3_{PLAYER_ID}" class="round" style="left: 123px; top: 440px;"></div>
                <div id="round_4_{PLAYER_ID}" class="round" style="left: 129px; top: 474px;"></div>
                <div id="round_5_{PLAYER_ID}" class="round" style="left: 118px; top: 502px;"></div>
                <div id="round_6_{PLAYER_ID}" class="round" style="left: 90px; top: 522px;"></div>
                <div id="round_7_{PLAYER_ID}" class="round" style="left: 61px; top: 528px;"></div>

                <div id="money_1_{PLAYER_ID}" class="money" style="left: 738px; top: 446px;"></div>
                <div id="money_2_{PLAYER_ID}" class="money" style="left: 768px; top: 446px;"></div>
                <div id="money_3_{PLAYER_ID}" class="money" style="left: 798px; top: 446px;"></div>
                <div id="money_4_{PLAYER_ID}" class="money" style="left: 828px; top: 446px;"></div>
                <div id="money_5_{PLAYER_ID}" class="money" style="left: 858px; top: 446px;"></div>
                <div id="money_6_{PLAYER_ID}" class="money" style="left: 888px; top: 446px;"></div>
                <div id="money_7_{PLAYER_ID}" class="money" style="left: 918px; top: 446px;"></div>
                <div id="money_8_{PLAYER_ID}" class="money" style="left: 948px; top: 446px;"></div>
                <div id="money_9_{PLAYER_ID}" class="money" style="left: 738px; top: 478px;"></div>
                <div id="money_10_{PLAYER_ID}" class="money" style="left: 768px; top: 478px;"></div>
                <div id="money_11_{PLAYER_ID}" class="money" style="left: 798px; top: 478px;"></div>
                <div id="money_12_{PLAYER_ID}" class="money" style="left: 828px; top: 478px;"></div>
                <div id="money_13_{PLAYER_ID}" class="money" style="left: 858px; top: 478px;"></div>
                <div id="money_14_{PLAYER_ID}" class="money" style="left: 888px; top: 478px;"></div>
                <div id="money_15_{PLAYER_ID}" class="money" style="left: 918px; top: 478px;"></div>
                <div id="money_16_{PLAYER_ID}" class="money" style="left: 948px; top: 478px;"></div>
                <div id="money_17_{PLAYER_ID}" class="money" style="left: 738px; top: 511px;"></div>
                <div id="money_18_{PLAYER_ID}" class="money" style="left: 768px; top: 511px;"></div>
                <div id="money_19_{PLAYER_ID}" class="money" style="left: 798px; top: 511px;"></div>
                <div id="money_20_{PLAYER_ID}" class="money" style="left: 828px; top: 511px;"></div>
                <div id="money_21_{PLAYER_ID}" class="money" style="left: 858px; top: 511px;"></div>
                <div id="money_22_{PLAYER_ID}" class="money" style="left: 888px; top: 511px;"></div>
                <div id="money_23_{PLAYER_ID}" class="money" style="left: 918px; top: 511px;"></div>
                <div id="money_24_{PLAYER_ID}" class="money" style="left: 948px; top: 511px;"></div>
                <div id="money_25_{PLAYER_ID}" class="money" style="left: 738px; top: 543px;"></div>
                <div id="money_26_{PLAYER_ID}" class="money" style="left: 768px; top: 543px;"></div>
                <div id="money_27_{PLAYER_ID}" class="money" style="left: 798px; top: 543px;"></div>
                <div id="money_28_{PLAYER_ID}" class="money" style="left: 828px; top: 543px;"></div>
                <div id="money_29_{PLAYER_ID}" class="money" style="left: 858px; top: 543px;"></div>
                <div id="money_30_{PLAYER_ID}" class="money" style="left: 888px; top: 543px;"></div>
                <div id="money_31_{PLAYER_ID}" class="money" style="left: 918px; top: 543px;"></div>
                <div id="money_32_{PLAYER_ID}" class="money" style="left: 948px; top: 543px;"></div>

                <div id="credit_1_{PLAYER_ID}" class="credit" style="left: 582px; top: 458px;"></div>
                <div id="credit_2_{PLAYER_ID}" class="credit" style="left: 626px; top: 458px;"></div>
                <div id="credit_3_{PLAYER_ID}" class="credit" style="left: 669px; top: 458px;"></div>

                <div id="etage_1_{PLAYER_ID}" class="etage" style="left: 216px; top: 378px;"></div>
                <div id="etage_2_{PLAYER_ID}" class="etage" style="left: 216px; top: 294px;"></div>
                <div id="etage_3_{PLAYER_ID}" class="etage" style="left: 216px; top: 210px;"></div>
                <div id="etage_4_{PLAYER_ID}" class="etage" style="left: 216px; top: 126px;"></div>
                <div id="etage_5_{PLAYER_ID}" class="etage" style="left: 958px; top: 378px;"></div>
                <div id="etage_6_{PLAYER_ID}" class="etage" style="left: 958px; top: 294px;"></div>
                <div id="etage_7_{PLAYER_ID}" class="etage" style="left: 958px; top: 210px;"></div>
                <div id="etage_8_{PLAYER_ID}" class="etage" style="left: 958px; top: 126px;"></div>
                <div id="etage_9_{PLAYER_ID}" class="etage" style="left: 281px; top: 40px;"></div>
                <div id="etage_10_{PLAYER_ID}" class="etage" style="left: 362px; top: 40px;"></div>
                <div id="etage_11_{PLAYER_ID}" class="etage" style="left: 444px; top: 40px;"></div>
                <div id="etage_12_{PLAYER_ID}" class="etage" style="left: 525px; top: 40px;"></div>
                <div id="etage_13_{PLAYER_ID}" class="etage" style="left: 736px; top: 40px;"></div>
                <div id="etage_14_{PLAYER_ID}" class="etage" style="left: 817px; top: 40px;"></div>
                <div id="etage_15_{PLAYER_ID}" class="etage" style="left: 899px; top: 40px;"></div>

                <div id="score_1_{PLAYER_ID}" class="score" style="left: 74px; top: 42px;"></div>
                <div id="score_2_{PLAYER_ID}" class="score" style="left: 74px; top: 75px;"></div>
                <div id="score_3_{PLAYER_ID}" class="score" style="left: 74px; top: 107px;"></div>
                <div id="score_4_{PLAYER_ID}" class="score" style="left: 74px; top: 139px;"></div>
                <div id="score_5_{PLAYER_ID}" class="score" style="left: 74px; top: 172px;"></div>
                <div id="score_6_{PLAYER_ID}" class="score" style="left: 74px; top: 204px;"></div>
                <div id="score_7_{PLAYER_ID}" class="score" style="left: 74px; top: 236px;"></div>
                <div id="score_8_{PLAYER_ID}" class="score" style="left: 74px; top: 268px;"></div>
                <div id="score_9_{PLAYER_ID}" class="score" style="left: 74px; top: 303px;"></div>
                <div id="score_10_{PLAYER_ID}" class="score" style="left: 74px; top: 342px;"></div>

                <div id="emperortrack_1_{PLAYER_ID}" class="emperor" style="left: 216px; top: 446px;"></div>
                <div id="emperortrack_2_{PLAYER_ID}" class="emperor" style="left: 240px; top: 446px;"></div>
                <div id="emperortrack_3_{PLAYER_ID}" class="emperor" style="left: 264px; top: 446px;"></div>
                <div id="emperortrack_4_{PLAYER_ID}" class="emperor" style="left: 326px; top: 446px;"></div>
                <div id="emperortrack_5_{PLAYER_ID}" class="emperor" style="left: 350px; top: 446px;"></div>
                <div id="emperortrack_6_{PLAYER_ID}" class="emperor" style="left: 194px; top: 503px;"></div>
                <div id="emperortrack_7_{PLAYER_ID}" class="emperor" style="left: 218px; top: 503px;"></div>
                <div id="emperortrack_8_{PLAYER_ID}" class="emperor" style="left: 280px; top: 503px;"></div>
                <div id="emperortrack_9_{PLAYER_ID}" class="emperor" style="left: 304px; top: 503px;"></div>
                <div id="emperortrack_10_{PLAYER_ID}" class="emperor" style="left: 327px; top: 503px;"></div>
                <div id="emperortrack_11_{PLAYER_ID}" class="emperor" style="left: 350px; top: 503px;"></div>
                <div id="emperortrack_12_{PLAYER_ID}" class="emperor" style="left: 131px; top: 560px;"></div>
                <div id="emperortrack_13_{PLAYER_ID}" class="emperor" style="left: 154px; top: 560px;"></div>
                <div id="emperortrack_14_{PLAYER_ID}" class="emperor" style="left: 217px; top: 560px;"></div>
                <div id="emperortrack_15_{PLAYER_ID}" class="emperor" style="left: 241px; top: 560px;"></div>
                <div id="emperortrack_16_{PLAYER_ID}" class="emperor" style="left: 304px; top: 560px;"></div>
                <div id="emperortrack_17_{PLAYER_ID}" class="emperor" style="left: 327px; top: 560px;"></div>
                <div id="emperortrack_18_{PLAYER_ID}" class="emperor" style="left: 351px; top: 560px;"></div>
                <div id="emperortrack_19_{PLAYER_ID}" class="emperor" style="left: 506px; top: 443px;"></div>
                <div id="emperortrack_20_{PLAYER_ID}" class="emperor" style="left: 528px; top: 443px;"></div>
                <div id="emperortrack_21_{PLAYER_ID}" class="emperor" style="left: 506px; top: 472px;"></div>
                <div id="emperortrack_22_{PLAYER_ID}" class="emperor" style="left: 528px; top: 472px;"></div>
                <div id="emperortrack_23_{PLAYER_ID}" class="emperor" style="left: 506px; top: 502px;"></div>
                <div id="emperortrack_24_{PLAYER_ID}" class="emperor" style="left: 528px; top: 502px;"></div>
                <div id="emperortrack_25_{PLAYER_ID}" class="emperor" style="left: 506px; top: 531px;"></div>
                <div id="emperortrack_26_{PLAYER_ID}" class="emperor" style="left: 528px; top: 531px;"></div>
                <div id="emperortrack_27_{PLAYER_ID}" class="emperor" style="left: 506px; top: 561px;"></div>
                <div id="emperortrack_28_{PLAYER_ID}" class="emperor" style="left: 528px; top: 561px;"></div>

                <div id="emperortrackbonus_1_{PLAYER_ID}" class="emperorbonusmalus" style="left: 462px; top: 446px;"></div>
                <div id="emperortrackmalus_1_{PLAYER_ID}" class="emperorbonusmalus" style="left: 190px; top: 445px;"></div>
                <div id="emperortrackbonus_2_{PLAYER_ID}" class="emperorbonusmalus" style="left: 462px; top: 502px;"></div>
                <div id="emperortrackmalus_2_{PLAYER_ID}" class="emperorbonusmalus" style="left: 168px; top: 502px;"></div>
                <div id="emperortrackbonus_3_{PLAYER_ID}" class="emperorbonusmalus" style="left: 462px; top: 560px;"></div>
                <div id="emperortrackmalus_3_{PLAYER_ID}" class="emperorbonusmalus" style="left: 105px; top: 559px;"></div>


                <div id="scoretool_1_{PLAYER_ID}" class="scoretool" style="left: 20px; top: 39px;"></div>
                <div id="scoretool_2_{PLAYER_ID}" class="scoretool" style="left: 20px; top: 71px;"></div>
                <div id="scoretool_3_{PLAYER_ID}" class="scoretool" style="left: 20px; top: 104px;"></div>
                <div id="scoretool_4_{PLAYER_ID}" class="scoretool" style="left: 20px; top: 136px;"></div>
                <div id="scoretool_5_{PLAYER_ID}" class="scoretool" style="left: 20px; top: 169px;"></div>
                <div id="scoretool_6_{PLAYER_ID}" class="scoretool" style="left: 20px; top: 201px;"></div>
                <div id="scoretool_7_{PLAYER_ID}" class="scoretool" style="left: 20px; top: 234px;"></div>
                <div id="scoretool_8_{PLAYER_ID}" class="scoretool" style="left: 20px; top: 266px;"></div>
                <div id="scoretool_9_{PLAYER_ID}" class="scoretool" style="left: 20px; top: 301px;"></div>
                <div id="scoretool_10_{PLAYER_ID}" class="scoretool" style="left: 20px; top: 339px;"></div>

                <div id="grouptool_1_{PLAYER_ID}" class="grouptool" style="left: 129px; top: 73px;"></div>
                <div id="grouptool_2_{PLAYER_ID}" class="grouptool" style="left: 129px; top: 184px;"></div>
                <div id="grouptool_3_{PLAYER_ID}" class="grouptool" style="left: 129px; top: 298px;"></div>

                <div id="etagetool_1_{PLAYER_ID}" class="etagetool" style="left: 210px; top: 30px;"></div>
                <div id="etagetool_2_{PLAYER_ID}" class="etagetool" style="left: 937px; top: 30px;"></div>

                <div id="aide_{PLAYER_ID}" class="aide" style="left: 974px; top: 1px; color: #{COLOR}; border: 1.5px solid #{COLOR};">?</div>
      
            </div>

            <div id="staff_{PLAYER_ID}">
            
                <div id="staff_1_{PLAYER_ID}" class="staff" style="left: 120px; top: 22px;"></div>
                <div id="staff_2_{PLAYER_ID}" class="staff" style="left: 286px; top: 22px;"></div>
                <div id="staff_3_{PLAYER_ID}" class="staff" style="left: 453px; top: 22px;"></div>
                <div id="staff_4_{PLAYER_ID}" class="staff" style="left: 620px; top: 22px;"></div>
                <div id="staff_5_{PLAYER_ID}" class="staff" style="left: 787px; top: 22px;"></div>
                <div id="staff_6_{PLAYER_ID}" class="staff" style="left: 954px; top: 22px;"></div>

                <div id="stafftool_1_{PLAYER_ID}" class="stafftool" style="left: 0px; top: 0px;"></div>
                <div id="stafftool_2_{PLAYER_ID}" class="stafftool" style="left: 166px; top: 0px;"></div>
                <div id="stafftool_3_{PLAYER_ID}" class="stafftool" style="left: 332px; top: 0px;"></div>
                <div id="stafftool_4_{PLAYER_ID}" class="stafftool" style="left: 498px; top: 0px;"></div>
                <div id="stafftool_5_{PLAYER_ID}" class="stafftool" style="left: 665px; top: 0px;"></div>
                <div id="stafftool_6_{PLAYER_ID}" class="stafftool" style="left: 834px; top: 0px;"></div>
            
            </div>
        </div>
    </div>
<!-- END player -->

</div>




<script type="text/javascript">


var jstpl_eye='<div id="eye_${id}" class="eye" style="display: inline-block;"></div>';
var jstpl_etatpoignee='<div id="etatpoignee_${id}_${porte}" class="poignee_${etat}"</div>';
var jstpl_round='<div id="compteurround_${rd}_${id}" class="compteurrounds"</div>';
var jstpl_money='<div id="etatmoney_${id}_${pos}" class="money_${etat}"</div>';
var jstpl_credit='<div id="credituse_${id}_${pos}" class="credituse"</div>';
var jstpl_etage='<div id="etage_${id}_${pos}" class="etage_${etat}"</div>';
var jstpl_emperor='<div id="emperor_${id}_${pos}" class="emperor_${etat}"</div>';
var jstpl_emperorbonus='<div id="emperorbonus_${id}_${pos}" class="emperorbonusmalus_${etat}"</div>';
var jstpl_emperormalus='<div id="emperormalus_${id}_${pos}" class="emperorbonusmalus_${etat}"</div>';
var jstpl_staff='<div id="staffcheck_${id}_${pos}" class="staffcheck"</div>';

var jstpl_first='<div id="first"></div>';
var jstpl_diceanime='<div id="diceanime_${id}" class="diceanime_${type}"</div>';

var jstpl_playerboardcompteur='<div id="player_board_compteur" style="display: flex; align-items: center; flex-direction: column; justify-content: center; z-index: 100; position: relative;"><div id="titre"></div><div id="compteur" style="text-align: center; display: flex; margin-top: 3px;"><div id="titre1" style="display: inline-block; margin-right: 4px;">{ROUND}</div><div id="compteurround" style="display: inline-block; margin-right: 4px;"></div><div id="separation" style="display: inline-block; margin-right: 3px;">-</div><div id="titre2" style="display: inline-block; margin-right: 4px;">{TURN}</div><div id="compteurturn" style="display: inline-block; margin-right: 4px;"></div></div></div>';
var jstpl_actiontool='<div style="text-align: center; font-weight: bold;">${name}</div><div style="text-align: center;">${description}</div>';

var jstpl_stafftool='<div style="text-align: center; font-weight: bold;">${name}</div><div style="text-align: center;">${description}</div>';

var jstpl_boardtool='<div style="text-align: center;">${description}</div>';

var jstpl_aidetool='<div style="text-align: center; font-weight: bold; margin-bottom: 10px;">${name1}</div><div style="text-align: center; margin-bottom: 5px;">${name2}</div><div class="iconeaide1"></div><div style="text-align: center;">&nbsp;&nbsp;${description1}</div><div style="clear: both;"></div><div class="iconeaide2"></div><div style="text-align: center;">&nbsp;&nbsp;${description2}</div><div style="clear: both;"></div><div class="iconeaide3"></div><div style="text-align: center;">&nbsp;&nbsp;${description3}</div><div style="clear: both;"></div><div style="text-align: center; margin-bottom: 5px; margin-top: 5px;">${name3}</div><div class="iconeaide4"></div><div style="text-align: center;">&nbsp;&nbsp;${description4}</div><div style="clear: both;"></div><div class="iconeaide5"></div><div style="text-align: center;">&nbsp;&nbsp;${description5}</div><div style="clear: both;"></div><div style="text-align: center; margin-bottom: 5px; margin-top: 5px;">${name4}</div><div class="iconeaide6"></div><div style="text-align: center;">&nbsp;&nbsp;${description6}</div><div style="clear: both;"></div><div class="iconeaide7"></div><div style="text-align: center;">&nbsp;&nbsp;${description7}</div><div style="clear: both;"></div><div style="text-align: center; margin-bottom: 5px; margin-top: 5px;">${name5}</div><div class="iconeaide8"></div><div style="text-align: center;">&nbsp;&nbsp;${description8}</div><div style="clear: both;"></div><div class="iconeaide9"></div><div style="text-align: center;">&nbsp;&nbsp;${description9}</div><div style="clear: both;"></div>';

var jstpl_dicedispo='<div id="dicedispo_${type}_${pos}" class="dicedispo_${type}"</div>';

</script>  

{OVERALL_GAME_FOOTER}
