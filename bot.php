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


                //query
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


        //api searches

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

                 global $attendance;

                         date_default_timezone_set('EST');
                         $start_time = date('20:00');

                         $date = "04.20.2012";
                         $day = "Friday";
                         $time = "8:00 pm";

                         $target = null; // null target until next date is set
                         //$target = mktime(20, 0, 0, 4, 27, 2012);
                         $seconds_away = $target-time();

                         $days = (int)($seconds_away/60/60/24);
                         $seconds_away-=$days;

                         $hours = (int)($seconds_away/60/60);
                         $seconds_away-=$hours;

                         $mins = (int)($seconds_away/60);
                         $seconds_away-=$mins;

                         if ($target != null)
                         {
                                  if ($days > 0)
                                  {
                                          $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The next dcs meeting is on '.$day.', '.$date    .' at '.$time.'. Which is in '.$days.' day(s)');

                                  }
                                  elseif ($hours > 0 && $days == 0)
                                  {
                                          $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The next dcs meeting is today at '.$time.' i    n '.$hours.' hour(s)');
                                  }

                                  elseif ($hours == 0 && $days == 0 && $mins > 0)
                                  {
                                          $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "The next dcs meeting is today, in ".$mins."     minute(s)! \o/. Confirming yes or no at this point is pretty useless don't you think?");
                                  }

                                  else
                                   {
                                          $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." The meeting has started or is a    lready over. Either way, if you're reading this, You probably missed it");

                                  }

                                  if (array_key_exists($data->nick, $attendance))
                                  {
                                          print_r($attendance);
                                          return;
                                  }

                               else
                                  {
                                          $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' Please let us know if you plan     to attend by "!confirm <yes>/<no>"');
                                  }

                          }
                          else

                                  $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The next DCS meeting has not yet been scheduled');
                 }

         //topic function to set topic for meetings
         function meeting_Topic($irc, $data)
         {
                 //keeps the array with current values
                  static $topic = array();

                  //takes the message and uses substr to cut out the trigger words and keep the end needed for the new topic
                   $changeTopic = $data->message;
                   $topicChange = array(0 => substr($changeTopic, 11));
                   $newTopic = array_replace($topic, $topicChange); //replaces the first array with the array from above

                  //makes the bot spit out the current topic set
                   if ($data->message == "!topic")
                   {
                          $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "The topic for the next DCS meeting is ".$topic[0]);
                          print_r($topic);//prints the $topic array for debugging purposes
                  }

                   else //code to run to change the topic
                  {
                          //kept as a 1 value array. removes the current topic and pushes the new on onto the array
                          array_pop($topic);
                          array_push($topic, $newTopic[0]);

                          $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Topic Changed. The new Topic for the next DCS meeting is ".$newTopic[0]);
                       print_r($topic);
                  }
         }


           //function to keep a list of those who will be and wont be attending meetings
             function meeting_List($irc, $data)
             {
                  /*
                          todo
                          - save arrays if bot quits
                  */

                  global $attendance;

                          //confirming yes
                           if($data->message == "!confirm yes")
                           {

                                  //checks to see if name is already on the list of those attending
                                   if ($attendance[$data->nick] == 1)
                                   {
                                         $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." I've already confirmed you as at    tending");
                                          print_r($attendance);
                                          return;
                                   }


                                  //first time responder saying "yes"
                                   else
                              {
                                           $attendance[$data->nick] = 1;
                                           $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." You have been confirmed as att    ending");
                                           print_r($attendance);
                                  }

                         }


                          //confirming not attending
                          if ($data->message == "!confirm no")
                           {
                                   //checks to see if the person is already on the list of not attending
                                   if ($attendance[$data->nick] == 0)
                                   {

                                          $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." You don't need to tell us twice     that you're not coming");
                                          print_r($attendance);
                                          return;
                                   }


                                   //first time responder saying "no"
                                  else
                                  {
                                          $attendance[$data->nick] = 0;
                                          $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." You have been confirmed as not     attending");
                                          print_r($attendance);
                                  }
                          }


                               //lists those confirmed as attending or not attending
                                   if ($data->message == "!confirm attendance")
                                   {
                                           $yes = "";
                                           $no = "";

                                          if (empty($attendance))
                                          {
                                                  $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "No one has responded whether or not     they are going");
                                          }

                                          elseif (in_array(1, $attendance) && !in_array(0, $attendance))
                                          {
                                                  foreach (array_keys($attendance) as $key)
                                                  {
                                                          $yes .= $key." ";
                                                  }

                                                  $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Attending the next DCS meeting: ".$y    es." No one has responded no at this time.");
                                                  print_r($attendance);
                                          }

                                          elseif (in_array(0, $attendance) && !in_array(1, $attendance))
                                          {
                                                  foreach (array_keys($attendance) as $key)
                                                  {
                                                          $no .= $key." ";
		                                          }

                                                  $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Bailing on the next DCS meeting: ".$    no." No one has responded yes at this time.");
                                                  print_r($attendance);
                                          }

                                          else
                                          {
                                                   foreach (array_keys($attendance) as $key)
                                                   {
                                                          if ($attendance[$key] == 1)
                                                          {
                                                                  $yes .= $key." ";
                                                          }

                                                          if ($attendance[$key] == 0)
                                                          {
                                                                  $no .= $key." ";
                                                          }

                                           $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Attending the next DCS meeting: ".$yes);
                                           $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Bailing on the next DCS meeting: ".$no);

                                                  }
                                         }

                                           print_r($attendance);
                                   }


                           //clears the lists
                       if ($data->message == "!confirm cleared")
                          {
                                 if ($data->nick == "ScooterAmerica")
                                 {
                                         foreach ($attendance as $i => $value)
                                         {
                                                 unset($attendance[$i]);
                                         }

                                         $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "The lists are now blank");

                                         print_r($attendance);
                                 }

                                 if ($data->nick != "ScooterAmerica")
                                 {
                                         return;
                                 }
                             }


                 //will only return if $data->message doesnt match one of the above parameters (this is to avoid calling this function accid    entally)
                           else
                                  return;
                   }
	
	               function hash($irc, $data)
                {
                        $str = $data->message;
                        $hashed = substr($str, 6);

                        $irc->message(SMARTIRC_TYPE_QUERY, $data->nick, md5($hashed));
                }

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
