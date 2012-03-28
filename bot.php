<?php
    include_once('SmartIRC.php');
    include_once('SmartIRC/defines.php');
    include_once('SmartIRC/irccommands.php');
    include_once('SmartIRC/messagehandler.php');

  class mybot
{
        //quit function
        function quit(&$irc, &$data)
        {
            if ($data->nick == "ScooterAmerica")
            {
                $irc->disconnect();
            }

           //quit protection

                     if ($data->nick != "ScooterAmerica")
                {

                         $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.': No, i dont think i will.');

                }
        }


                //tells the bot what to say via PM
                   function query(&$irc, &$data)
                {
                        $query = $data->message;
                        $newmsg = substr($query, 5);


                        if ($data->nick == "ScooterAmerica")
                        {
                         // text sent to channel
                                $irc->message(SMARTIRC_TYPE_CHANNEL, '#dcs', $newmsg);
                        }

                        if ($data->nick != "ScooterAmerica")
                        {
                                $irc->message(SMARTIRC_TYPE_CHANNEL, '#dcs', $data->nick.' is trying to get in through my back door!');
                        }

                }


                //when he leaves
                function partResponse($irc, $data)
                {
                        if ($data->nick == "elitegunslinger")

                                $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, '\o/');
                }


                //auto rejoin
                function kickResponse(&$irc, &$data)
                 { //when kicked
                                 $irc->join(array('#jeff','#dcs'));
                                return;

                 }

                                //join greeting
                 function onjoin_greeting(&$irc, &$data)
                 {  // don't greet self
                                 if ($data->nick == $irc->_nick)
                                          return;

                                if ($data->channel == '#dcs')
                                {
                                        if ($data->nick == "neilforobot")
                                        {
                                                 $irc->message(SMARTIRC_TYPE_ACTION, $data->channel, 'glares at neilforobot');
                                        }
                                }

                        elseif ($data->channel == '#comp-arch' || $data->channel == '#jeff')

                                   $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.': SHALL WE START?!?! ');
                }

                //api libraries

                function api($irc, $data)
                {
                        $irc1 = array("java"=>" http://docs.oracle.com/javase/1.5.0/docs/api/", "php"=>" http://php.net/manual/en/book.spl.php", "haskell"=>" http://www.haskell.org/hoogle/", "python"=>" http://docs.python.org/library/", "perl"=>" http://perldoc.perl.org/index-language.html", "no"=>" Language not found. Please try again.");


                        if ($data->message == "!api java")
                        {
                                $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$irc1["java"]);
                        }

                        elseif ($data->message == "!api php")
                        {
                                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$irc1["php"]);
                        }

                        elseif ($data->message == "!api haskell")
                        {
                                $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$irc1["haskell"]);
                        }

                        elseif ($data->message == "!api python")
                        {
                                $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$irc1["python"]);
                        }

                        elseif ($data->message == "!api perl")
                        {
                                $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$irc1["perl"]);
                        }

                        else
                                $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$irc1["no"]);
                }


        //search specific api libraries

                function php_search($irc, $data)
                {
                        $search = $data->message;
                        $term = substr($search, 12);
                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://www.php.net/manual-lookup.php?pattern='.$term.'&lang=en&scope=quickref');
                }

                function perl_search($irc, $data)
                {
                        $search2 = $data->message;
                        $term2 = substr($search2, 13);

                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://perldoc.perl.org/search.html?q='.$term2);
                }

                function cpan_search($irc, $data)
                {
                        $search3 = $data->message;
                        $term3 = substr($search3, 13);

                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://search.cpan.org/search?query='.$term3.'&mode=all');
                }

                function python_search($irc, $data)
                {
                        $search4 = $data->message;
                        $term4 = substr($search4, 15);

                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://docs.python.org/search.html?q='.$term4.'&check_keywords=yes&area=default');
                }

                function haskell_search($irc, $data)
                {
                        $search5 = $data->message;
                        $term5 = substr($search5, 16);

                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://www.haskell.org/hoogle/?hoogle='.$term5);
                }
				
			
			function espnScores ($irc, $data)
			{	
				$content = get_content ("http://sports.espn.go.com/ncb/bottomline/scores");

				$content_array=explode("&", $content);
				$scorearray = array();
				$i=0;
				
				foreach($content_array as $content) 
				{
					if (strpos($content, "_left")) 
					{
						$equalpos = strpos($content, "=");
						$end = strlen($content);
						$title = substr($content, ($equalpos+1), $end);
						$title = str_replace("^", "", $title);
						$title = str_replace("%20", " ", $title);
						$scorearray[$i]["title"] = $title;
					}
					
					if (strpos($content, "_url")) 
					{
						$equalpos = strpos($content, "=");
						$end = strlen($content);
						$url = substr($content, ($equalpos+1), $end);
						$url = str_replace("^", "", $url);
						$url = str_replace("%20", " ", $url);
						$scorearray[$i]["url"] = $url;
						$i++;
					}
				}
					
				foreach($scorearray as $score) 
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "<item>\n");
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "<title>".$score["title"]."</title>\n");
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "<link>".$score["url"]."</link>\n");
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "</item>\n");
				}

			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "</channel>\n");
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel,"</rss>\n");
		}
				
                //espn scores
                function scoresTemp($irc, $data)
                {
                        $score = array("ncaa" => " http://scores.espn.go.com/ncb/scoreboard", "nba" => " http://espn.go.com/nba/scoreboard", "nfl" => " http://scores.espn.go.com/nfl/scoreboard", "nhl" => " http://scores.espn.go.com/nhl/scoreboard", "mlb" => " http://espn.go.com/mlb/scoreboard");

                                if ($data->message == "!scores ncaa")
                                {
                                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$score["ncaa"]);
                                }

                                elseif($data->message == "!scores nba")
                                {
                                         $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$score["nba"]);
                                }

                                elseif($data->message == "!scores nfl")
                                {
                                         $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$score["nfl"]);
                                }

                                elseif($data->message == "!scores nhl")
                                {
                                         $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$score["nhl"]);
                                }

                                elseif($data->message == "!scores mlb")
                                {
                                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$score["mlb"]);
                                }

                                else
                                         $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' not found');
                }



           //triggers (talk)
                function right($irc, $data)
                {
                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel,'RIIIIIIIIIIIGHT!');
                }

                function neilforoshan($irc, $data)
                {
                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'That man is evil');
                }

                function doit($irc, $data)
                {
                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'Do it for you, not for me');
                }

                function twss($irc, $data)
                {
                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'She said that! Yes?');
                }

                function myPing($irc, $data)
                {
                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.': ping!');
                }

                //countdown to next meeting
               function countdown($irc, $data)
               {
                      date_default_timezone_set('EST');
                      $start_time = date('18:30');

                     	 $date = "03.23.2012";
                         $day = "Friday";
                         $time = "6:30 pm";

                         $target = null;
                         $seconds_away = $target-time();

                         $days = (int)($seconds_away/60/60/24);
                         $seconds_away-=$days;

                         $hours = (int)($seconds_away/60/60);
                         $seconds_away-=$hours;

                         $mins = (int)($seconds_away/60);
                        $seconds_away-=$mins;

						//if target date is set function will output time remaining based on parameters
                         if ($target != null)
                         { 
							     while ($days >= 0 && $hours >= 0 && $minutes >= 0)
                                 {
                                         if ($days > 0)
                                        {
                                                 $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The next dcs meeting is on '.$day.', '.$date.' at '.$time.'. Which is in '.$days.' days ');
										}

                                         elseif ($hours > 0 && $days == 0)
                                         {
                                                 $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The next dcs meeting is today at '.$time.' in '.$hours.' hours');
										 }

                                         else
										{
                                                 $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The next dcs meeting is today, in '.$mins.' minutes! \o/');
								 		}
								 }
							 

								 while ($days < 0 && $hours < 0 && $minutes < 0)
								 {
								 	$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'You missed it');
								 }
							
						 }
						 
						 else
						 
                                 $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The next DCS meeting has not yet been scheduled');
						 
			   }

				//md5 hash a string
                function hash($irc, $data)
                {
                        $str = $data->message;
                        $hashed = substr($str, 6);

                        $irc->message(SMARTIRC_TYPE_QUERY, $data->nick, md5($hashed));
                }

				//compliment a user
                function nice($irc, $data)
                {
                        $comp = array(" <--This guy. AWESOME", " You're the best", " Everyone is jealous of you", " You're amazing", " <--Next President");
                        $rand_comp = shuffle($comp);

                        $msg = $data->message;
                        $name = substr($msg, 12);


                        if ($data->message == "!compliment !roulette" || $data->message == "!compliment !roulette " || $data->message == "!compliment !dino !roulette" ||		  							 $data->message == "!compliment !dino !roulette ")
                        {
                                $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' Stop trying to break things');
                        }

                        else

                                $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.$comp[$rand_comp]);

                }

				//insult a user
                function mean($irc, $data)
                {
                        $ins = array(" You suck", " It would be better if you left", " You will never amount to anything", " Im just going to pretend like you arent 		  										here", " No one likes you");
                        $rand_ins = shuffle($ins);

                        $msg = $data->message;
                        $name= substr($msg, 8);

                        if ($data->message == "!insult !roulette" || $data->message == "!insult !roulette " || $data->message == "!insult !dino !roulette" || $data->					  												message == "!insult !dino !roulette ")
                        {
                                $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' Stop trying to break things');
                        }

                        else

                                $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.$ins[$rand_ins]);

                }


                function drinking_game($irc, $data)
                {
                        $nicks = array("ScooterAmerica", "stan_theman", "bro_keefe", "compywiz", "NellyFatFingers", "jico", "prg318", "ericoc", "OpEx");
                        $drinker = shuffle($nicks);

                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $nicks[$drinker]);
                }



                //triggers(actions)
                 function ping($irc, $data)
                {
                        $irc->message(SMARTIRC_TYPE_ACTION, $data->channel,'twitches his mustache and smiles');
                }

                 function thumbs($irc, $data)
                {
                        $irc->message(SMARTIRC_TYPE_ACTION, $data->channel,'double thumbs up');
                }

                function burn($irc, $data)
                {
                        $msge = $data->message;
                        $short = substr($msge, 6);

                        if ($data->message == "!burn" || $data->message == "!burn ")
                        {
                                $irc->message( $data->type, $data->nick, 'Pick somebody to burn. !burn <nick>' );
                        }

                        elseif ($data->message == "!burn AakashBot" || $data->message == "!burn AakashBot ")
                        {
                                $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' I do not feel, so I feel no burn');
                        }

                        else
                                $irc->message(SMARTIRC_TYPE_ACTION, $data->channel, 'gives the burn ointment to '.$short);
                }

				//tells the bot to kick a user
                function kickNick($irc, $data)
                 {
                                 if(isset($data->messageex[1],$data->messageex[2]))
                                 {
                                         $nickname = $data->messageex[1];
                                         $reason = "Peace Dude";
                                         $channel = $data->channel;
                                         $irc->kick($channel, $nickname, $reason);
                                 }

                                 else

                                         $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' Use !kick <nick>');

                }

				//ops the user (bot must be opped for this to work)
                function opMe(&$irc, &$data)
                {
                        if ($data->nick == "ScooterAmerica" || $data->nick == "compywiz" || $data->nick == "bro_keefe" || $data->nick == "stan_theman" || $data->nick == "jico" || $data->nick == "NellyFatFingers" || $data->nick == "prg318" || $data->nick == "ericoc")
                        {
                                $nickname = $data->nick;
                                $channel = $data->channel;
                                $irc->op($channel, $nickname);
                        }

                        else

                                $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." Sorry. You're not on the list");
                }


				//decision maker based on "drawing straws"
                function straws($irc, $data)
                {
                        $nicks = array(" ScooterAmerica", " stan_theman", " bro_keefe", " compywiz", " NellyFatFingers", " jico", " prg318", " ericoc");
                        $rand_nick = shuffle($nicks);

                        $irc->message(SMARTIRC_TYPE_ACTION, $data->channel, 'hands out the straws.' .$nicks[$rand_nick]. ' got the short straw.');
                }



}


    $bot = &new mybot();
    $irc = &new Net_SmartIRC();
    $irc->setDebug(SMARTIRC_DEBUG_ALL);
    $irc->setUseSockets(TRUE);


        //api libraries
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '!api', $bot, 'api');
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '!search.php', $bot, 'php_search');
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '!search.perl', $bot, 'perl_search');
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '!search.cpan', $bot, 'cpan_search');
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '!search.python', $bot, 'python_search');
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '!search.haskell', $bot, 'haskell_search');


        //espn scores
//      $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '!rss', $bot, 'scores');

        //espn scores(temp)
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '!scores', $bot, 'scoresTemp');


        //greet & leave responses
        $irc->registerActionhandler(SMARTIRC_TYPE_PART, '.*', $bot, 'partResponse');
        $irc->registerActionhandler(SMARTIRC_TYPE_KICK, '.*', $bot, 'kickResponse');
        $irc->registerActionhandler(SMARTIRC_TYPE_JOIN, '.*', $bot, 'onjoin_greeting');

        //bot talk
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, 'right', $bot, 'right');
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '4chan', $bot, 'neilforoshan');
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, 'doit', $bot, 'doit');
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, 'twss', $bot, 'twss');
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '!me', $bot, 'myPing');
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '!score', $bot, 'score');
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '!kick', $bot, 'kickNick');
        $irc->registerActionhandler(SMARTIRC_TYPE_QUERY, '!say', $bot, 'query');
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '!dcsmeeting', $bot, 'countdown');
        $irc->registerActionhandler(SMARTIRC_TYPE_QUERY, '!hash', $bot, 'hash');
		$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '!compliment', $bot, 'nice');
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '!insult', $bot, 'mean');
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '!drink', $bot, 'drinking_game');

        //bot actions
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!quit', $bot, 'quit');
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, 'AakashBot', $bot, 'ping');
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, 'yes', $bot, 'thumbs');
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '!burn', $bot, 'burn');
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '!op', $bot, 'opMe');
        $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '!drawstraws', $bot, 'straws');

        //freenode connect/login
        $irc->connect('chat.freenode.net', 6667);
        $irc->login('AakashBot', 'Net_SmartIRC Client '.SMARTIRC_VERSION.'(aakashBot.php)',  'jeff');


        //channel join
        $irc->join(array('#jeff', '#dcs'));
//      $irc->join(array('#jeff'));
//      $irc->join(array('#jeff', '#dcs', '#comp-arch'));
//      $irc->join(array('#jeff', #dcs', '#comp-arch', '#deadcodersociety'));


        $irc->listen();
        $irc->disconnect();

?>
