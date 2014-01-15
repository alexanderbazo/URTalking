subject('Informationswissenschaft').
subject('Medieninformatik').
course('Android', 'Alexander Bazo').

major(X,Y) :-
	subject(X),
	concat(X,' kann als Hauptfach studiert werden.', Y),
	write(Y).

lecturer(X,Y) :-
	course(X,Z),
	concat(Z,' hält diesen Kurs.',Y),
	write(Y).
