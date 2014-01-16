init:-
	ensure_loaded('./vvz_ss2013.pl').

:-
	init.

subject('Informationswissenschaft').
subject('Medieninformatik').
course('android', 'Alexander Bazo').
course('window8', 'Jürgen Reischer').

major(SUBJECT,RESULT) :-
	subject(SUBJECT),
	concat(SUBJECT,' kann als Hauptfach studiert werden.', RESULT),
	write(RESULT).

lecturer(COURSENAME,RESULT) :-
	downcase_atom(COURSENAME,SEARCHTERM),
	course(SEARCHTERM,LECTURER),
	concat(LECTURER, ' hält den ', TMP),
	concat(TMP,COURSENAME,TMP2),
	concat(TMP2,' Kurs.', RESULT),
	write(RESULT).

findAllCurrentCourses(SUBJECT,BAG) :-
	findall(BAG, currentCourses(SUBJECT), _).

currentCourses(SUBJECT) :-
	vorlesungaktuell(ID,NR,TITLE),
	sub_string(ID,0,3,_,SUBJECT_ID),
	\+(SUBJECT == SUBJECT_ID),
	concat('<span class="course" nr="', NR, TMP),
	concat(TMP, '">', TMP2),
	concat(TMP2, TITLE, TMP3),
	concat(TMP3, '</span>, ', RESULT),
	write(RESULT).
