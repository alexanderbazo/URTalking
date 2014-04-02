init:-
	ensure_loaded('./kurse_vorlesungsverzeichnis.pl'),
	ensure_loaded('./vvz_ss2013.pl'),
	ensure_loaded('./module_inf-mi-mewi.pl'),
	ensure_loaded('./module_kurse.pl').

:-
	init.

subject('Informationswissenschaft').
subject('Medieninformatik').
course('android', 'Alexander Bazo').
course('windows8', 'Jürgen Reischer').

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

findAllModules(SUBJECT,BAG) :-
	findall(BAG, subjectModules(SUBJECT), _).

findAllModulesForCourse(COURSENR,BAG) :-
	findall(BAG, modulesForCouse(COURSENR), _).

currentCourses(SUBJECT) :-
	vorlesungaktuell(ID,NR,TITLE),
	sub_string(ID,0,3,_,SUBJECTID),
	name(SUBJECT, S1),
	name(SUBJECTID, S2),
	S1 == S2,
	concat('<span class="course" nr="', NR, TMP),
	concat(TMP, '">', TMP2),
	concat(TMP2, TITLE, TMP3),
	concat(TMP3, '</span>, ', RESULT),
	write(RESULT).

subjectModules(SUBJECT) :-
	modul(ID,TITLE),
	sub_string(ID,0,3,_,SUBJECTID),
	compareStrings(SUBJECT,SUBJECTID),
	concat('<span class="module" id="', ID, TMP),
	concat(TMP, '">', TMP2),
	concat(TMP2, TITLE, TMP3),
	concat(TMP3, '</span>, ', RESULT),
	write(RESULT).


coursetitle(COURSENR) :-
	vorlesungaktuell(_, COURSENR, COURSETITLE),
	write(COURSETITLE).

modulesForCouse(COURSENR) :-
	vorlesungaktuell(ID, COURSENR, _),
	kurse_vorlesungsverzeichnis(ID,_,MODUL,LP),
	concat(' ', MODUL, TMP),
	concat(TMP, '(', TMP2),
	concat(TMP2, LP, TMP3),
	concat(TMP3, '), ', RESULT),
	write(RESULT).


compareStrings(S1, S2):-
	name(S1, X),
	name(S2, Y),
	X == Y.