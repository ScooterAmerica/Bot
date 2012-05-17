<?php
    include_once('SmartIRC.php');
    include_once('SmartIRC/defines.php');
    include_once('SmartIRC/irccommands.php');
    include_once('SmartIRC/messagehandler.php');

  class mybot
{
	static $attendance = array();
	static $topic = "";
	static $location = "";

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

		//leave a channel
		function peace($irc, $data)
		{
			if ($data->nick == "ScooterAmerica")
			{
				if(isset($data->messageex[1])) 
				{    
                			$channel = $data->messageex[1];
                			$irc->part($channel);
				}
			}
		}

		//join a channel
		function joinChannel($irc, $data)
		{
			if ($data->nick == "ScooterAmerica")
			{
				if(isset($data->messageex[1]))
				{    
                			$channel = $data->messageex[1];
                			$irc->join($channel);
				}
			}
		}


		//make the bot say something in the channel
		function query(&$irc, &$data)
    		{
			$msg = $data->message;
			$newmsg = substr($msg, 5);
 
       			 // text sent to channel
       				$irc->message(SMARTIRC_TYPE_CHANNEL, '#dcs', $newmsg);	
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
        	   	{	
				return;
			}

		 	if ($data->channel == '#dcs')
			{
				if ($data->nick == "neilforobot")
				{
					 $irc->message(SMARTIRC_TYPE_ACTION, $data->channel, 'glares at neilforobot');
				}
			}

			elseif ($data->channel == '#jeff')
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.': SHALL WE START?!?! ');
			}
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


	//specific api searches
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

		//search for a term on urbandictionary.com
		function urban_dictionary($irc, $data)
		{
			if(isset($data->messageex[1]))
			{
				$term = $data->messageex[1];

				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "http://www.urbandictionary.com/define.php?term=".$term);
			}

			else
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "No term given");
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

		function googleIt($irc, $data)
                {
			$search = $data->message;
			$google = substr($search, 8);
			$googles = str_word_count($google, 1);

			$words = "";
			if(str_word_count($google, 0) > 1)
			{
				foreach ($googles as $word)
				{
					$words .= $word."+";
				}

				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Your result: https://www.google.com/#q=".$words);
			}

			else
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Your result: https://www.google.com/#q=".$google);
			}
		}

//DCS Functions

	        //countdown to next meeting
                function countdown($irc, $data)
                {
                	global $attendance;	
			global $location;
			global $topic;

                        date_default_timezone_set('America/New_York');
                        $start_time = date('18:30');

                        $date = "06.02.2012";
                        $day = "Saturday";
                        $time = "3:00 pm";

			//$target = null; // null target until next date is set
			$target = mktime(15, 0, 0, 6, 2, 2012, 1);
                        $seconds_away = $target-time();

                        $days = (int)($seconds_away/86400);
                        $hours = (int)(($seconds_away-($days*86400))/3600);
			$mins = (int)(($seconds_away-($days*86400)-($hours*3600))/60);

                        if ($target != null)
                        {
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The next dcs: meeting is on '.$day.', '.$date.' at '.$time.'. Which is '.$days.' day(s), '.$hours." hour(s), and ".$mins." minute(s) from now.");

				if (array_key_exists($data->nick, $attendance))
                       		{
                        		print_r($attendance);
                        	}

				else
                        	{
                        		$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' Please let us know if you plan to attend by "!confirm <yes>/<no>."');
                        	}
		
				if(empty($topic))
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Topic undecided");
				}
				else
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Topic: ".$topic);
				}

				if(empty($location))
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Location not set");
				}
				else
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Location: ".$location);
				}
			 }

                         else

                                 $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The next DCS meeting has not yet been scheduled');
 		}   
	
	//topic function to set topic for meetings
	function meeting_Topic($irc, $data)
	{            
		global $topic;

                 //takes the message and uses substr to cut out the trigger words and keep the end needed for the new topic
                  $changeTopic = $data->message;
                  $newTopic =  substr($changeTopic, 7);

                 //makes the bot spit out the current topic set
                 if ($data->message == "!topic" || $data->message == "!topic ")
                 {
			if(empty($topic))
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Topic undecided");
			}

			else
			{
                         	$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "DCS meeting topic: ".$topic);
			}
                 }

		elseif ($data->message == "!topic clear")
		{
			$topic = "";

			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Cleared");
			$irc->message(SMARTIRC_TYPE_QUERY, "TestBot", "!topic clear");
		}

                  else //code to run to change the topic
                 {
			$topic = $newTopic;
                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Topic Changed. New Meeting Topic: ".$topic);
                       	//bot PM's me topic so i have it saved in case it quits out
			$irc->message(SMARTIRC_TYPE_QUERY, "TestBot", $data->message);
                 }
	}


		//set the location of each DCS meeting
                function meeting_Location($irc, $data)
                {
			//stores the meeting location
                        global $location;

                        $changeLoc = $data->message;
                        $newLoc = substr($changeLoc, 10);

			//used to ask for meeting location
                        if($data->message == "!location" || $data->message == "!location ")
                        {
				//checks if the array is empty
                                 if(empty($location))
                                 {
                                         $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Location not set");
                                 }

                                 else
                                 {
                                         $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "DCS meeting location: ".$location);
                                 }

                         }

			//clears the array location
                         elseif ($data->message == "!location clear")
                         {
                                 $location = "";

                                 $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Cleared");
				 $irc->message(SMARTIRC_TYPE_QUERY, "TestBot", "!location clear");
                         }

			//changes location and pushes to new array
                         else
                         {
                                 $location = $newLoc;
                                 $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Location Changed. New Meeting Location: ".$location);
                                 
				//bot PM's me location to be saved in case it quits
				 $irc->message(SMARTIRC_TYPE_QUERY, "TestBot", $data->message);
                         }
                 }


          //function to keep a list of those who will be and wont be attending meetings
            function meeting_List($irc, $data)
	    {
                 /*
                         todo
                         - save array if bot quits
                 */

                 global $attendance;

                         //confirming yes
                          if($data->message == "!confirm yes")
                          {

                                 //checks to see if name is already on the list of those attending
                                  if ($attendance[$data->nick] == 1)
                                  {
                                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." I've already confirmed you as attending");
                                         print_r($attendance);
                                         return;
                                  }


                                 //first time responder saying "yes"
                                  else
                                 {
                                          $attendance[$data->nick] = 1;
                                          $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." You have been confirmed as attending");
                                          print_r($attendance);
                                 }
 
                        }


                         //confirming not attending
                         if ($data->message == "!confirm no")
                         {
                                  //checks to see if the person is already on the list of not attending
                         	if ($attendance[$data->nick] == 0 && in_array($data->nick, $attendance))
                                {

                                	$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." You don't need to tell us twice that you're not coming");
                                        print_r($attendance);
                                        return;
                                }


                                 //first time responder saying "no"
                                else
                                {
                                        $attendance[$data->nick] = 0;
                                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." You have been confirmed as not attending");
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
                                	$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "No one has responded yes or no at this time.");
                                }

                                elseif (in_array(1, $attendance) && !in_array(0, $attendance))
                                {
                                	foreach (array_keys($attendance) as $key)
                                        {
                                        	$yes .= $key." ";
                                        }

                                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Attending the next DCS meeting: ".$yes);
                                        print_r($attendance);
                                }

                                elseif (in_array(0, $attendance) && !in_array(1, $attendance))
                                {
                                	foreach (array_keys($attendance) as $key)
                                        {
                                        	$no .= $key." ";
                                        }

                                	$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Bailing on the next DCS meeting: ".$no);
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
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." You're not allowed to do that");
				}
                         }


                //will only return if $data->message doesnt match one of the above parameters (this is to avoid calling this function accidentally)
                          else
                                 return;
                  }
		

		//creates a md5 hash of a string
		function hash($irc, $data)
		{
			$str = $data->message;
			$hashed = substr($str, 6);

			$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, md5($hashed));
		}


		//compliments a user
		function nice($irc, $data)
		{			
			$comp = array(" <--This guy. AWESOME", " You're the best", " Everyone is jealous of you", " You're amazing", " <--Next President");
			$rand_comp = shuffle($comp);

			$msg = $data->message;
			$name = substr($msg, 12);

			if ($data->message == "!compliment !roulette" || $data->message == "!compliment !roulette " || $data->message == "!compliment !dino !roulette" || $data->message == "!compliment !dino !roulette ")
                       	{
                               	$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' Stop trying to break things');
                       	}

			else
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.$comp[$rand_comp]);	
		}

		//insults a user
		function mean($irc, $data)
		{

			$ins = array(" You suck", " It would be better if you left", " You will never amount to anything", " Im just going to pretend like you arent here", " No one likes you");
			$rand_ins = shuffle($ins);

			$msg = $data->message;
			$name= substr($msg, 8);

			if ($data->message == "!insult !roulette" || $data->message == "!insult !roulette " || $data->message == "!insult !dino !roulette" || $data->message == "!insult !dino !roulette ")
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
		function burn($irc, $data)
		{
			$msge = $data->message;
			$short = substr($msge, 6);

			if ($data->message == "!burn AakashBot" || $data->message == "!burn AakashBot ")
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." You're not good enough to burn me");
			}

			elseif ($data->message == "!burn" || $data->message == "!burn ")
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." Burn who?");
			}
			else
				$irc->message(SMARTIRC_TYPE_ACTION, $data->channel, 'gives the burn ointment to '.$short);
		}

		function superBurn($irc, $data)
		{
			$burn = $data->message;
			$burnee = substr($burn, 11);

			if ($data->message == "!superburn AakashBot" || $data->message == "!superburn AakashBot " || $data->message == "!superburn ScooterAmerica" || $data->message == " !superburn ScooterAmerica ")
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "HOW DARE YOU!!!");
				$irc->message(SMARTIRC_TYPE_ACTION, $data->channel, 'ignites '.$data->nick.' with a flamethrower!');
			}

			elseif ($data->message == "!superburn" || $data->message == "!superburn ")
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." Who is that directed towards?");
			}

			else
				$irc->message(SMARTIRC_TYPE_ACTION, $data->channel, 'sets '.$burnee.'on fire!');
		}

		//gives operator status to a user (bot must have ops already to do this)
		function opMe(&$irc, &$data)
	        {
			if ($data->nick == "ScooterAmerica" || $data->nick == "compywiz" || $data->nick == "bro_keefe" || $data->nick == "stan_theman" || $data->nick == "jico" || $data->nick == "NellyFatFingers" || $data->nick == "prg318" || $data->nick == "ericoc" || $data->nick == "OpEx")
			{
				$nickname = $data->nick;
               			$channel = $data->channel;
               			$irc->op($channel, $nickname);
			}	

			else

				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Sorry ".$data->nick.".  You're not on the list");
		}

		//makes the decision for us so we dont have to
		function straws($irc, $data)
		{
			$nicks = array(" ScooterAmerica", " stan_theman", " bro_keefe", " compywiz", " NellyFatFingers", " jico", " prg318", " ericoc", " OpEx");
			$rand_nick = shuffle($nicks);

			$irc->message(SMARTIRC_TYPE_ACTION, $data->channel, 'hands out the straws.' .$nicks[$rand_nick]. ' got the short straw.');
		}


}


    $bot = &new mybot();
    $irc = &new Net_SmartIRC();
    $irc->setDebug(SMARTIRC_DEBUG_ALL);
    $irc->setUseSockets(FALSE);
     

	//api libraries
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!api', $bot, 'api');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!search.php', $bot, 'php_search');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!search.perl', $bot, 'perl_search');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!search.cpan', $bot, 'cpan_search');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!search.python', $bot, 'python_search');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!search.haskell', $bot, 'haskell_search');


	//espn scores(temp)
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!scores', $bot, 'scoresTemp');


	//greet & leave responses
	$irc->registerActionhandler(SMARTIRC_TYPE_KICK, '.*', $bot, 'kickResponse');
        $irc->registerActionhandler(SMARTIRC_TYPE_JOIN, '.*', $bot, 'onjoin_greeting');

	//part and join
	$irc->registerActionhandler(SMARTIRC_TYPE_QUERY, '^!part', $bot, 'peace');
	$irc->registerActionhandler(SMARTIRC_TYPE_QUERY, '^!join', $bot, 'joinChannel');


	//dcs meeting functions
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!dcsmeeting', $bot, 'countdown');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!confirm', $bot, 'meeting_List');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!topic', $bot, 'meeting_Topic');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL,'^!location', $bot, 'meeting_Location');

	//bot talk
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^4chan', $bot, 'neilforoshan');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^doit', $bot, 'doit');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, 'twss', $bot, 'twss');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!me', $bot, 'myPing');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!score', $bot, 'score');
	$irc->registerActionhandler(SMARTIRC_TYPE_QUERY, '^!say', $bot, 'query');
	$irc->registerActionhandler(SMARTIRC_TYPE_QUERY, '^!hash', $bot, 'hash');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!compliment', $bot, 'nice');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!insult', $bot, 'mean');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!drink', $bot, 'drinking_game');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!urban', $bot, 'urban_dictionary');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!google', $bot, 'googleIt');

	//bot actions
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!quit', $bot, 'quit');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!burn', $bot, 'burn');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!superburn', $bot, 'superBurn');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!op', $bot, 'opMe');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!drawstraws', $bot, 'straws');

	//DCS connect/login
	$irc->connect('ssl://irc.deadcodersociety.org', '6697');
	$irc->login('AakashBot', 'Net_SmartIRC Client '.SMARTIRC_VERSION.'(aakashBot.php)', '0');

/*
	//freenode connect/login
	$irc->connect('chat.freenode.net', '6667');
	$irc->login('AakashBot', 'Net_SmartIRC Client '.SMARTIRC_VERSION.'(aakashBot.php)', '0');
*/

	//channel join
//	$irc->join(array('#jeff'));
	$irc->join(array('#jeff', '#dcs', '#finance'));


	$irc->listen();
    $irc->disconnect();
?>
