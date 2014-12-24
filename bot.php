<?php
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  Include Statements
-----------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	// SmartIRC
	include_once('SmartIRC/SmartIRC.php');
	include_once('SmartIRC/defines.php');
	include_once('SmartIRC/irccommands.php');
	include_once('SmartIRC/messagehandler.php');
/*
  Files Needed For Functions
*/
	// Google
	include_once('Function_Files/Google/googleurlapi.php');

	// DCS Meetings
	include_once('Function_Files/dcsmeetings/attendancelist.txt');
	include_once('Function_Files/dcsmeetings/location.txt');
	include_once('Function_Files/dcsmeetings/topic.txt');
	include_once('Function_Files/dcsmeetings/meetingDate.txt');

	// ESPN Scores
	include_once('Function_Files/Scores/mlbScores.php');
	include_once('Function_Files/Scores/nbaScores.php');
	include_once('Function_Files/Scores/ncaaScores.php');
	include_once('Function_Files/Scores/nflScores.php');
	include_once('Function_Files/Scores/nhlScores.php');

	//Password file
	include_once('Function_Files/passwords.txt');
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  Basic Bot Functions
-----------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
class mybot {

	// quit function
	function quit(&$irc, &$data) {
		if ($data->nick == "ScooterAmerica") {
			$irc->disconnect();
		}

		// quit protection
		else {
			return;
		}
	}

	// leave a channel
	function leaveChannel($irc, $data) {
		if ($data->nick == "ScooterAmerica") {
			$irc->part($data->messageex[1]);
		}
	}

	// make the bot say something in the channel
	function privateMessage($irc, $data) {
		$newmsg = trim(substr($data->message, 5));

		// text sent to channel
		$irc->message(SMARTIRC_TYPE_CHANNEL, '#dcs', $newmsg);
	}

	// auto rejoin
	function kickResponse($irc, $data) {
		// when kicked
		$irc->join(array('#jeff','#dcs'));
		return;
	}

	// join greeting
	function joinGreeting($irc, $data) {
		// don't greet self
		if ($data->nick == $irc->_nick) {
			return;
		}

		if ($data->nick == "neilforobot") {
			$irc->message(SMARTIRC_TYPE_ACTION, $data->channel, 'glares at neilforobot');
		}
	}

	// invite the bot to join a channel (replaces joinChannel)
	function joinChannelInvited($irc, $data) {
		$irc->join($data->messageex[0]);
	}

	// identify with NickServ
	function identify($irc, $data) {
		$password = file_get_contents('Function_Files/passwords.txt');

		list($user, $pw) = explode("=", $password);
		$irc->message(SMARTIRC_TYPE_QUERY, 'NickServ', 'identify '.$pw);
	}
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  API Libraries
-----------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	function api($irc, $data) {
		$api = trim(substr($data->message, 5));
		$apiLibraries = array(
				"java"=>" http://docs.oracle.com/javase/1.5.0/docs/api/",
				"php"=>" http://php.net/manual/en/book.spl.php",
				"haskell"=>" http://www.haskell.org/hoogle/",
				"python"=>" http://docs.python.org/library/",
				"perl"=>" http://perldoc.perl.org/index-language.html"
				);

		// switch statement to determine which api library to return to the user
		switch($api) {
			case "java":
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$apiLibraries["java"]);
				break;

			case "php":
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$apiLibraries["php"]);
				break;

			case "haskell":
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$apiLibraries["haskell"]);
				break;

			case "python":
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$apiLibraries["python"]);
				break;

			case "perl":
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$apiLibraries["perl"]);
				break;
		}
	}

	// search within a specific api library
	function php_search($irc, $data) {
		// cuts off the trigger word and counts the remaining words to determine if the foreach loop is needed
		$term = trim(substr($data->message, 12));
		$terms = str_word_count($term, 1, '_()');
		$params = "";

		if (str_word_count($term, 1) > 1) {
			// adds a "+" in between multiple words to create a valid hyperlink
			foreach ($terms as $values) {
				$params .= $values."+";
			}
			$fparams = substr($params, 0, -1);

			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://www.php.net/manual-lookup.php?pattern='.$fparams.'&lang=en&scope=quickref');
		}

		else {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://www.php.net/manual-lookup.php?pattern='.$term.'&lang=en&scope=quickref');
		}
	}

	function perl_search($irc, $data) {
		$term2 = trim(substr($data->message, 13));
		$terms2 = str_word_count($term2, 1, '_()');
		$params2 = "";

		if (str_word_count($term2, 1) > 1) {
			foreach ($terms2 as $values2) {
				$params2 .= $values2."+";
			}
			$fparams2 = substr($params2, 0, -1);

			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://perldoc.perl.org/search.html?q='.$fparams2);
		}

		else {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://perldoc.perl.org/search.html?q='.$term2);
		}
	}

	function cpan_search($irc, $data) {
		$term3 = trim(substr($data->message, 13));
		$terms3 = str_word_count($term3, 1, '_()');
		$params3 = "";

		if (str_word_count($term3, 1) > 1) {
			foreach($terms3 as $values3) {
				$params3 .= $values3."+";
			}
			$fparams3 = substr($params3, 0, -1);

			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://search.cpan.org/search?query='.$fparams3.'&mode=all');
		}

		else {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://search.cpan.org/search?query='.$term3.'&mode=all');
		}
	}

	function python_search($irc, $data) {
		$term4 = trim(substr($data->message, 15));
		$terms4 = str_word_count($term4, 1, '_()');
		$params4 = "";

		if (str_word_count($term4, 1) > 1) {
			foreach($terms4 as $values4) {
				$params4 .= $values4."+";
			}
			$fparams4 = substr($params4, 0, -1);

			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://docs.python.org/search.html?q='.$fparams4.'&check_keywords=yes&area=default');
		}

		else {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://docs.python.org/search.html?q='.$term4.'&check_keywords=yes&area=default');
		}
	}

	function haskell_search($irc, $data) {
		$term5 = trim(substr($data->message, 16));
		$terms5 = str_word_count($term5, 1, '_()');
		$params5 = "";

		if(str_word_count($term5, 1) > 1) {
			foreach($terms5 as $values5) {
				$params5 .= $values5."+";
			}
			$fparams5 = substr($params5, 0, -1);

			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://www.haskell.org/hoogle/?hoogle='.$fparams5);
		}

		else {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://www.haskell.org/hoogle/?hoogle='.$term5);
		}
	}
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  ESPN Scores
/*---------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	function espnScores($irc, $data) {
		/* This will grab the RSS off of ESPN for their Bottom line of scores. Returns the teams, score, and period/inning/etc.*/
		$league = $data->messageex[1];
		switch($league) {
			case "ncaa":
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, `php Function_Files/Scores/ncaaScores.php`);
				break;

			case "nba":
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, `php Function_Files/Scores/nbaScores.php`);
				break;

			case "nfl":
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, `php Function_Files/Scores/nflScores.php`);
				break;

			case "nhl":
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, `php Function_Files/Scores/nhlScores.php`);
				break;

			/* There are too many MLB scores for one IRC message so
			the bot posts the ESPN MLB scoreboard link so users can
			see scores not shown */
			case "mlb":
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, `php Function_Files/Scores/mlbScores.php`);
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "FULL SCOREBOARD: http://scores.espn.go.com/mlb/scoreboard");
				break;
		}
	}
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  Triggers(talk)
-----------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
        function doit($irc, $data) {
	        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'Do it for you, not for me');
	}

	function twss($irc, $data) {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'She said that! Yes?');
	}

	function hilightMe($irc, $data) {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.': ping!');
	}

	// creates a md5 hash of a string (via PM)
	function hash($irc, $data) {
		$hashed = $data->messageex[1];
		$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, md5($hashed));
	}

	// tells the bot to compliment a user(grabs a random response from the array)
	function beNice($irc, $data) {
		$compliments = array(
				" <--This guy. AWESOME",
				" You're the best",
				" Everyone is jealous of you",
				" You're amazing",
				" <--Next President"
				);

		$rand_comp = shuffle($comp);
		$name = trim(substr($data->message, 12));

		if ($data->message == "!compliment !roulette" || $data->message == "!compliment !dino !roulette") {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' Stop trying to break things');
		}

		else {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.$compliments[$rand_comp]);
		}
	}

	// tells the bot to insult a user
	function beMean($irc, $data) {
		$insults = array(
			" You remind me of Andrew",
			" You suck",
			" It would be better if you left",
			" You will never amount to anything",
			" Im just going to pretend like you arent here",
			" No one likes you"
			);

		$rand_ins = shuffle($ins);
		$name = trim(substr($data->message, 8));

		if ($data->message == "!insult !roulette" || $data->message == "!insult !dino !roulette") {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' Stop trying to break things');
		}

		else {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.$insults[$rand_ins]);
		}
	}

	// the function name says it all doesnt it?
	function drinking_game($irc, $data) {
		$nicks = array(
			"ScooterAmerica",
			"stan_theman",
			"bro_keefe",
			"compywiz",
			"NellyFatFingers",
			"jico",
			"prg318",
			"ericoc",
			"OpEx"
			);
		$drinker = shuffle($nicks);

		$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $nicks[$drinker]);
	}
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  Google
-----------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	// provides the URL to a list of Google Search Responses
	function googleIt($irc, $data) {
		$google = trim(substr($data->message, 3));

		if ($data->message == "!g") {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "There's nothing to Google");
		}

		else {
			$googles = str_word_count($google, 1);
			$words = "";

			if (str_word_count($google, 0) > 1) {
				foreach ($googles as $word) {
					$words .= $word."+";
				}
				$fwords = substr($words, 0, -1);

				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Your results: https://www.google.com/#q=".$fwords);
			}

			else {
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Your results: https://www.google.com/#q=".$google);

			}
		}
	}

	// Equivalent to the "I'm feeling lucky" button on the Google Search page. In the future the bot will return a link to the first result on the page.
	function googleLucky($irc, $data) {
		$google = trim(substr($data->message, 7));

		if ($data->message == "!lucky" || $data->message == "!lucky ") {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "You're not lucky");
		}

		else {
			$googles = str_word_count($google, 1);
			$words = "";

			if(str_word_count($google, 0) > 1) {
 				foreach ($googles as $word) {
					$words .= $word."+";
				}
				$fwords = substr($words, 0, -1);

				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Your result: http://www.google.com/search?btnI=I%27m+Feeling+Lucky&ie=UTF-8&oe=UTF-8&q=".$fwords);
			}

			else {
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Your result: http://www.google.com/search?btnI=I%27m+Feeling+Lucky&ie=UTF-8&oe=UTF-8&q=".$google);
			}
		}
	}
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  Notes
-----------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	/*Keep a note/notes of something..like a reminder function except saved in a file forever.
	Notes are saved in a file named by the users hostname. This way if the user changes their
	nick the bot will still be able to find the notes file associated with them.*/

	function makeANote($irc, $data) {
		$revokePrivs = array(
				"NotBot",
				"AakashBot",
				"VinceTron",
				"neilforobot"
				);

		if (in_array($data->nick, $revokePrivs)) {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." Your note privelages have been revoked");
		}

		else {
			$note = substr($data->message, 6);
			$person = $data->host;

			if ($data->message == "!notes") {

				// checks to see if the file exists, if yes, open it and read out the notes line by line.
				if (file_exists("Function_Files/Notes/".$person.".txt")) {
					$currentNotes = fopen("Function_Files/Notes/".$person.".txt", "r");
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." Your Notes:");

					while(!feof($currentNotes)) {
                               	        	$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, fgets($currentNotes));
                                       	}

                                       	fclose($currentNotes);
				}

				else {
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "You have no notes yet");
				}
			}

			// clear all the notes and delete the File
			elseif ($data->message == "!notes clear") {
				unlink("Function_Files/Notes/".$person.".txt");
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Your notes are gone. I hope you've got a good memory");
			}

			// if the file doesnt exist or they wish to add a note, bot looks for the file, creates if necessary, writes the note and closes the file.
			else {
				$makeNote = fopen("Function_Files/Notes/".$person.".txt", "a+");
				fwrite($makeNote, $note."\n");
				fclose($makeNote);

				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Note Taken");
			}
		}
	}

	// This function will allow you to delete a specific note in the file.
	function deleteANote($irc, $data) {
		// creates and empty variable, finds the users notes file and opens it
		$writeNotes = "";
		$person = $data->host;
		$notes = file("Function_Files/Notes/".$person.".txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$i = $data->messageex[1];

		// looks for the line number given and removes it.
		if (is_numeric((int)$i) && $i > 0) {
			$j = $i-1;
			unset($notes[$j]);

			$newNotes = fopen("Function_Files/Notes/".$person.".txt", "w+");
			foreach ($notes as $value) {
				$writeNotes = $value."\r\n";
				fwrite($newNotes, $writeNotes);
			}
			fclose($newNotes);

			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Note Deleted");
		}

		else {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Not Valid Entry");
		}
	}
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  User's Manual
-----------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	// bot help manual (provides specific help for each of the bots functions) equivalent to the "man" pages in Linux
	function help($irc, $data) {
		$help = $data->messageex[1];

		if ($data->message == "!help") {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' Please use "!help <botname>" for a list of features.');
		}

		else {
			switch($help) {
				case "api":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: !api <language>. !search <language><term(s)>");
					break;

				case "meetings":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: !dcsmeeting, !location <location(s)>, !topic <topic(s)>, !setmeeting <date(yyyy-mm-dd)> <time(00:00)>.");
					break;

				case "confirm":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: !confirm <yes>/<no>/<attendance>/<cleared>");
					break;

				case "burn":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: !burn <nick> || !superburn <nick>");
					break;

				case "google":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: !g <term(s)> || !lucky <term(s)>");
					break;

				case "drawstraws":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: !drawstraws");
					break;

				case "say":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: (PM) !say <message>");
					break;

				case "hash":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: (PM) !hash <text>");
					break;

				case "insult":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: !insult <nick>");
					break;

				case "compliment":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: !compliment <nick>");
					break;

				case "AakashBot":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Help Options: all | api | burn | meetings | compliment | confirm | drawstraws | google | hash | insult | invite | notes | say | scores");
					break;

				case "notes":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: !notes <your note>| !notes clear | !notes (view your notes) | !delnote <line #>");
					break;

				case "scores":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: !scores <league> (nfl, mlb, etc.)");
					break;

				case "invite":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: (PM) !invite <nickname> <channel>");
					break;

				case "all":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "http://forum.deadcodersociety.org/index.php/topic,217.0.html");
					break;
			}
		}
	}
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  DCS Meeting Functions
-----------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
static $topic = "";
static $location = "";

	function setNextMeetingDate ($irc, $data) {
		if ($data->message == "!setmeeting over") {
			$meetingOver = fopen("Function_Files/dcsmeetings/meetingDate.txt", "w");
			fclose($meetingOver);

			$topicOver = fopen("Function_Files/dcsmeetings/topic.txt", "w");
			fclose($topicOver);

			$locationOver = fopen("Function_Files/dcsmeetings/location.txt", "w");
			fclose($locationOver);

			$clearAttendance = fopen("Function_Files/dcsmeetings/attendancelist.txt", "w");
			fclose($clearAttendance);

			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Meeting Schedule Cleared");
		}

		else {
			if (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $data->messageex[1]) &&
			preg_match('/^(0?\d|1\d|2[0-3]):[0-5]\d$/', $data->messageex[2])) {
				$meetingDate = strtotime(date('Y-m-d G:i', strtotime(substr($data->message, 12))));
				$date = date('D Y-m-d', strtotime($data->messageex[1]));
				$meetingTime = $data->messageex[2];

				if ($meetingDate > time()) {
					$dcsMeeting = fopen("Function_Files/dcsmeetings/meetingDate.txt", "w");
					fwrite($dcsMeeting, $date."\r\n");
					fwrite($dcsMeeting, $meetingTime."\r\n");
					fwrite($dcsMeeting, $meetingDate."\r\n");
					fwrite($dcsMeeting, "Meeting set by: ".$data->nick);
					fclose($dcsMeeting);

					$this->countDownToNextMeeting($irc, $data);
				}

				else {
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "That Date has already passed");
				}
			}

			else {
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Invalid Format. Please use 'yyyy-mm-dd' and '00:00 (24 hour clock)' for date and time formats respectively.");
			}
		}
	}

	// countdown to next meeting
	function countDownToNextMeeting ($irc, $data) {
		$meetingInfo = file("Function_Files/dcsmeetings/meetingDate.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		$date = $meetingInfo[0];
		$meetingTime = $meetingInfo[1];
		$meetingDate = $meetingInfo[2];
		$meetingSetBy = $meetingInfo[3];


		if (empty($meetingInfo)) {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "There is no DCS meeting currently scheduled");
		}

		elseif ($meetingDate < time()) {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "The meeting has started or is already over");
		}

		else {
			$timeUntilMeeting = abs($meetingDate - time());

			$daysLeft = (int)($timeUntilMeeting/86400);
			$hoursLeft = (int)(($timeUntilMeeting-($daysLeft*86400))/3600);
			$minsLeft = (int)(($timeUntilMeeting-($daysLeft*86400)-($hoursLeft*3600))/60);

			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "dcs: Next Meeting scheduled for ".$date." at ".$meetingTime.". Which is in ".$daysLeft." day(s), ".$hoursLeft." hour(s), and ".$minsLeft." minute(s).");
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $meetingSetBy);

			$this->checkTopicAndLocation($irc, $data);
		}
	}

	function checkTopicAndLocation($irc, $data) {
		global $location;
		global $topic;

		/* these next few functions check the topic and location
		set for the meeting and displays them.*/

		// Topic
		$top = fopen("Function_Files/dcsmeetings/topic.txt", "r");
		$currentTop = fgets($top);

		if (empty($currentTop)) {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Topic undecided");
		}

		else {
			$topic = $currentTop;
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Topic: ".$topic);
		}
		fclose($top);

		//Location
		$place = fopen("Function_Files/dcsmeetings/location.txt", "r");
		$currentLoc = fgets($place);

		if (empty($currentLoc)) {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Location not set");
		}

		else {
			$location = $currentLoc;
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Location: ".$location);
		}
		fclose($place);
	}

	// topic function to set topic for meetings
	function meeting_Topic($irc, $data) {
		global $topic;
		$newTopic = trim(substr($data->message, 7));

		// makes the bot spit out the current topic set
		if ($data->message == "!topic") {
			$top = fopen("Function_Files/dcsmeetings/topic.txt", "r");
			$currentTop = fgets($top);

			if (empty($currentTop)) {
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Topic undecided");
			}

			else {
				$topic = $currentTop;
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "DCS meeting topic: ".$topic);
			}
                 }

		// clears the topic in the File
		elseif ($data->message == "!topic clear") {
			$top = fopen("Function_Files/dcsmeetings/topic.txt", "w");
			$topic = "";

			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Cleared");

			fwrite($top, $topic);
		}

		// code to run to change the topic (only ever one entry in the file)
		else {
			$top = fopen("Function_Files/dcsmeetings/topic.txt", "w");

			$topic = $newTopic;
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "New Meeting Topic: ".$topic);

			fwrite($top, $topic);
		}
		fclose($top);
	}

	// set the location of each DCS meeting
	function meeting_Location($irc, $data) {
		// stores the meeting location
		global $location;

		$newLoc = trim(substr($data->message, 10));
		$changeLoc = str_word_count($newLoc, 1, "0123456789");

		// used to ask for meeting location
		if ($data->message == "!location") {
			$place = fopen("Function_Files/dcsmeetings/location.txt", "r");
			$currentLoc = fgets($place);

			// checks if the file is empty
			if (empty($currentLoc)) {
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Location not set");
			}

			else {
				$location = $currentLoc;
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "DCS meeting location: ".$location);
			}
		}

		// clears the file location
		elseif ($data->message == "!location clear") {
			$place = fopen("Function_Files/dcsmeetings/location.txt", "w");
			$location = "";
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Cleared");

			fwrite($place, $location);
		}

		/*changes location and writes back to file. Bot creates a google maps link using the address its given. It then
		feeds the link into a script that hits Google's URL shortener api and records that as the location*/
		else {
			$place = fopen("Function_Files/dcsmeetings/location.txt", "w");
			$loc = "";

			if (str_word_count($newLoc, 0, "0123456789") >= 1) {
				foreach ($changeLoc as $locs) {
					$loc .= $locs."+";
				}
			}

			$shortLoc = substr($loc, 0, -1);
			$mapLoc = "https://maps.google.com/maps?q=".$shortLoc;

			// Create new API instance
			$google = new GoogleURLAPI();

			// Shorten URL
			$shortLocation = $google->shorten($mapLoc);
			$location = $shortLocation;

			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "New Meeting Location: ".$location);

			fwrite($place, $location);
		}
		fclose($place);
	}

	// function to keep a list of those who will be and wont be attending meetings
	function meeting_List($irc, $data) {
		$meetingInfo = file("Function_Files/dcsmeetings/meetingDate.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$response = $data->messageex[1];

		if (empty($meetingInfo)) {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "The list is unavailable since there is no meeting scheduled");
	}

		else {
			switch($response) {
			/*The yes and no cases, the bot opens the file, first checks to see if there is a response from that user,
			if not, the bot will add the users response, if the user already responded and it matches their current response, the bot informs the
			user they have already responded, if the answer is different (i.e. yes to no or vice versa), the bot will erase the first response and record the new one.
			*/
				case "yes":
					$attending = file("Function_Files/dcsmeetings/attendancelist.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

					if (!in_array($data->nick." said yes", $attending) && !in_array($data->nick." said no", $attending)) {
						$attending = fopen("Function_Files/dcsmeetings/attendancelist.txt", "a+");
						$name = $data->nick." said yes";
						fwrite($attending, $name."\n");
						fclose($attending);

						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Yes Confirmed. See you there!");
					}

					elseif (in_array($data->nick." said no", $attending) && !in_array($data->nick." said yes", $attending)) {
						$name = array_search($data->nick." said no", $attending);
						unset($attending[$name]);
						$newName = $data->nick." said yes\n";

						$newResponse = fopen("Function_Files/dcsmeetings/attendancelist.txt", "w+");
						fwrite($newResponse, $newName);
						$response = "";
						foreach ($attending as $value)
						{
							$response = $value."\r\n";
							fwrite($newResponse, $response);
						}
						fclose($newResponse);

						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Glad to see you wised up. Yes Confirmed");
					}

					else {
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." You've already said yes.");
					}
				break;

				case "no":
					$attending = file("Function_Files/dcsmeetings/attendancelist.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

					if (!in_array($data->nick." said no", $attending) && !in_array($data->nick." said yes", $attending)) {
						$attending = fopen("Function_Files/dcsmeetings/attendancelist.txt", "a+");
						$name = $data->nick." said no";

						fwrite($attending, $name."\n");
						fclose($attending);

						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "No Confirmed. You won't be missed.");
					}

					elseif (in_array($data->nick." said yes", $attending) && !in_array($data->nick." said no", $attending)) {
						$name = array_search($data->nick." said yes", $attending);
						unset($attending[$name]);
						$newName = $data->nick." said no\n";

						$newResponse = fopen("Function_Files/dcsmeetings/attendancelist.txt", "w+");
						fwrite($newResponse, $newName);
						$response = "";
						foreach ($attending as $value) {
							$response = $value."\r\n";
							fwrite($newResponse, $response);
						}
						fclose($newResponse);

						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "You'll regret this. Change to no confirmed");
					}

					else {
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." You said no already. We get it.");
					}
				break;

				// reads out all the user reponses with who said yes and who said no
				case "attendance":
					$names = file_get_contents("Function_Files/dcsmeetings/attendancelist.txt");

					if (strlen($names) > 1) {
						$attending = fopen("Function_Files/dcsmeetings/attendancelist.txt", "r");
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Responses so Far:");

						while(!feof($attending)) {
							$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, fgets($attending));
						}
						fclose($attending);
					}

					else {
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "No one had responded yet");
					}
				break;

				// clears the contents of the file thereby clearing the list.
				case "cleared":
					file_put_contents("Function_Files/dcsmeetings/attendancelist.txt", NULL);
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Lists cleared");
				break;
			}
		}
	}
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  Triggers(actions)
-----------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	function burn($irc, $data) {
		$short = trim(substr($data->message, 6));

		if ($data->message == "!burn AakashBot") {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." You're not good enough to burn me");
		}

		elseif ($data->message == "!burn") {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." Burn who?");
		}

		else {
			$irc->message(SMARTIRC_TYPE_ACTION, $data->channel, 'gives the burn ointment to '.$short);
		}
	}

	function superBurn($irc, $data) {
		$burnee = trim(substr($data->message, 11));

		if ($data->message == "!superburn AakashBot" || $data->message == "!superburn ScooterAmerica") {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "HOW DARE YOU!!!");
			$irc->message(SMARTIRC_TYPE_ACTION, $data->channel, 'ignites '.$data->nick.' with a flamethrower!');
		}

		elseif ($data->message == "!superburn") {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." Who is that directed towards?");
		}

		else {
			$irc->message(SMARTIRC_TYPE_ACTION, $data->channel, 'sets '.$burnee.' on fire!');
		}
	}

	// gives operator status to a user (bot must have ops already to do this)
	function opMe($irc, $data) {
		$dcs = array(
			"ScooterAmerica",
			"bro_keefe",
			"jico",
			"nelly",
			"OpEx"
			);

		if (in_array($data->nick, $dcs)) {
			$irc->op($data->channel, $data->nick);
		}

		else {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Nope");
		}
	}

	// makes the decision for us so we dont have to
	function straws($irc, $data) {
		$nicks = array(
			" ScooterAmerica",
			" stan_theman",
			" bro_keefe",
			" compywiz",
			" NellyFatFingers",
			" jico",
			" prg318",
			" ericoc",
			" OpEx"
			);
		$rand_nick = shuffle($nicks);

		$irc->message(SMARTIRC_TYPE_ACTION, $data->channel, 'hands out the straws.' .$nicks[$rand_nick]. ' got the short straw.');
	}

	// invites a user to a specific channel
	function inviteUser($irc, $data) {
		$nick = $data->messageex[1];
		$channel = $data->messageex[2];
		$irc->invite($nick, $channel);

		$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, "Inviting ".$nick." to ".$channel);
		$irc->message(SMARTIRC_TYPE_QUERY, $nick, "Join ".$channel." and be cool");
	}
}
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  Bot Settings
/*---------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
    $bot = &new mybot();
    $irc = &new Net_SmartIRC();
    $irc->setDebug(SMARTIRC_DEBUG_ALL);
    $irc->setUseSockets(FALSE);
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  Bot Users Manual
/*---------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!help\b', $bot, 'help');
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  API Libraries
/*---------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!api ([_\w]+)', $bot, 'api');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!search.php ([_\w]+)', $bot, 'php_search');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!search.perl ([_\w]+)', $bot, 'perl_search');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!search.cpan ([_\w]+)', $bot, 'cpan_search');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!search.python ([_\w]+)', $bot, 'python_search');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!search.haskell ([_\w]+)', $bot, 'haskell_search');
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  ESPN Scores
/*---------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!scores ([_\w]+)', $bot, 'espnScores');
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  Basic Bot Functions
/*---------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	// greet & leave responses
	$irc->registerActionhandler(SMARTIRC_TYPE_KICK, '.*', $bot, 'kickResponse');
        $irc->registerActionhandler(SMARTIRC_TYPE_JOIN, '.*', $bot, 'joinGreeting');

	// leave and join
	$irc->registerActionhandler(SMARTIRC_TYPE_QUERY, '^!leave', $bot, 'leaveChannel');
	$irc->registerActionhandler(SMARTIRC_TYPE_INVITE, '.*', $bot, 'joinChannelInvited');

	//identify bot with NickServ
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!identify', $bot, 'identify');
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  DCS Meeting Functions
/*---------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!setmeeting', $bot, 'setNextMeetingDate');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!dcsmeeting', $bot, 'countDownToNextMeeting');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!confirm', $bot, 'meeting_List');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!topic\b', $bot, 'meeting_Topic');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!location\b', $bot, 'meeting_Location');
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  Bot Talk
/*---------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, 'doit', $bot, 'doit');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, 'twss', $bot, 'twss');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!me\b', $bot, 'hilightMe');
	$irc->registerActionhandler(SMARTIRC_TYPE_QUERY, '^!say', $bot, 'privateMessage');
	$irc->registerActionhandler(SMARTIRC_TYPE_QUERY, '^!hash', $bot, 'hash');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!compliment ([_\w]+)', $bot, 'beNice');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!insult ([_\w]+)', $bot, 'beMean');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!drink', $bot, 'drinking_game');
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  Google
/*---------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!g\b ([_\w]+)', $bot, 'googleIt');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!lucky ([_\w]+)', $bot, 'googleLucky');
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  Notes
/*---------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!notes', $bot, 'makeANote');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!delnote', $bot, 'deleteANote');
/*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

  Bot Actions
/*---------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!quit', $bot, 'quit');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!burn ([_\w]+)', $bot, 'burn');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!superburn ([_\w]+)', $bot, 'superBurn');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!op\b', $bot, 'opMe');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!drawstraws', $bot, 'straws');
	$irc->registerActionhandler(SMARTIRC_TYPE_QUERY, '^!invite', $bot, 'inviteUser');
/*---------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
$irc->listen();
$irc->disconnect();
?>
