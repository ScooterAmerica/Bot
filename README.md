IRC BOT
========
This is my IRC Bot. I developed this bot as my first real personal project as i got into coding and tbh im very happy with
it.  It has some few useful functions that you can find listed below. Anything i missed can be found in the bot.php file along
with comments that go into more detail as to how the functions themselves work.

----------------

### BOT FUNCTIONS

#### DCS MEETINGS
	!topic <text> - sets the topic of the next DCS meeting.
	!location <text> - sets the location of the next DCS meeting.
	!confirm
	   -yes: marks your status as attending the next dcs meeting.
	   -no: marks your status as not attending the next dcs meeting.
	   -attendance: returns names of those attending and not attending.
	   -cleared: Clears the attendance list.
	!dcsmeeting – shows the time left until the next dcs meeting.
	!setmeeting <date> <time> - set the date and time of the next DCS meeting within the IRC channel.

----------------

#### NOTES
	!notes - allows you to take notes. The bot opens a file for you and records what you want. This will display your current notes.
	!notes <text> - adds a new note to your list.
	!notes <clear> - deletes all of your notes.
	!delnote <#> - deletes the note from your file. # is the line number you want removed.

----------------

#### API SEARCH
	!api <language> - returns the url for the language entered. Currently only searches Java, PHP, Haskell, Python, Perl.
	search <language><term> - will return the url for the api of the language (one of the above) for the term listed.

----------------

#### SCORES
	!scores <league> - will grab the current scores for the games that day for the league specified. The leagues set up
	right now are NHL, NFL, NBA, NCAA Mens Basketball, MLB. Working on Soccer in the US and Euro Leagues as well as possibly Golf and
	NCAA Mens and Womens Volleyball.

----------------

#### Other Miscellaneous Functions
	* !insult <nickname> - insults the nickname given.
	* !compliment <nickname> - compliments the nickname given.
	* !g <term(s)> - returns link to Google search results
	* !lucky <term(s)> - Google click "I'm Feeling Lucky" button
	* (PM) !say <message> - makes the bot say a message in ‘#dcs’
	* (PM) !hash <text> - returns md5 hash of text sent
	* (PM) !invite <nickname> <channel> - invite a user to a specific channel. Also sends a message to that user.

----------------

#### INVITE AND IDENTIFY (BOT ITSELF)
	* !identify will now have the Bot identify with NickServ so he can get his cloak from services (he likes to play ninja). Also took the smart route for once and didnt put his password to identify right in the code.  Instead he's got a nifty password file for that sort of thing (okay, its not really that innovative or "nifty", but i still thought it was worth a mention.
	* !invite takes care of the part where im lazy and got tired of having to PM the bot to join a new channel.  Now just /invite <bot> like you would any other person and the bot will automatically join the channel.
