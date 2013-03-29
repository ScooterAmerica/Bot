IRC BOT
	This is my IRC Bot. I developed this bot as my first real personal project as i got into coding and tbh im very happy with
it.  It has some few useful functions that you can find listed below. Anything i missed can be found in the bot.php file along
with comments that go into more detail as to how the functions themselves work.


DCS MEETINGS
	!topic <text> - sets the topic of the next DCS meeting.
	!location <text> - sets the location of the next DCS meeting.
	!confirm
	   -yes: marks your status as attending the next dcs meeting.
	   -no: marks your status as not attending the next dcs meeting.
	   -attendance: returns names of those attending and not attending.
	   -cleared: Clears the attendance list.
	!dcsmeeting – shows the time left until the next dcs meeting.
	!setmeeting <date> <time> - set the date and time of the next DCS meeting within the IRC channel.

NOTES
	!notes - allows you to take notes. The bot opens a file for you and records what you want. This will display your current notes.
	!notes <text> - adds a new note to your list.
	!notes <clear> - deletes all of your notes.
	!delnote <#> - deletes the note from your file. # is the line number you want removed.

API SEARCH
	!api <language> - returns the url for the language entered. Currently only searches Java, PHP, Haskell, Python, Perl.
	search <language><term> - will return the url for the api of the language (one of the above) for the term listed.

SCORES
	!scores <league> - will grab the current scores for the games that day for the league specified. The leagues set up
	right now are NHL, NFL, NBA, NCAA Mens Basketball, MLB. Working on Soccer in the US and Euro Leagues as well as possibly Golf and 
	NCAA Mens and Womens Volleyball.

Other Miscellaneous Functions
	!insult <nickname> - insults the nickname given.
	!compliment <nickname> - compliments the nickname given.
	!g <term(s)> - returns link to Google search results
	!lucky <term(s)> - Google click "I'm Feeling Lucky" button
	(PM) !say <message> - makes AakashBot say the message in ‘#dcs’
	(PM) !hash <text> - returns md5 hash of text sent
