#!/usr/bin/python

import sys
import getopt
import aiml
import subprocess
import contextlib
import cStringIO
import os.path
import marshal


aiml_request = "";
aiml_file = "";
aiml_session = "";

@contextlib.contextmanager
def nostdout():
	save_stdout = sys.stdout
	sys.stdout = cStringIO.StringIO()
	yield
 	sys.stdout = save_stdout

def getRequest():
	global aiml_request, aiml_file, aiml_session;
	myopts, args = getopt.getopt(sys.argv[1:],"q:a:s:");
	for o, a in myopts:
		if o == '-q':
			aiml_request = a;
		elif o == '-a':
			aiml_file = a;
		elif o == '-s':
			aiml_session = a;
		else:
			print("Usage: %s -q query -a aiml-file -s session-id" % sys.argv[0]);

def setupAiml():
	global aiml_kernel, aiml_file;
	aiml_kernel = aiml.Kernel();
	with nostdout():
		aiml_kernel.learn(aiml_file);
	restoreSession();

def processRequest(str):
	print(aiml_kernel.respond(str, aiml_session));
	saveSession();

def saveSession():
	session = aiml_kernel.getSessionData(aiml_session);
	sessionFile = file("../sessions/"+aiml_session+".ses", "wb");
	marshal.dump(session, sessionFile);
	sessionFile.close();

def restoreSession():
	try:
		sessionFile = file("../sessions/"+aiml_session+".ses", "rb");
	except IOError:
		return;
	session = marshal.load(sessionFile);
	sessionFile.close();
	for pred,value in session.items():
		aiml_kernel.setPredicate(pred, value, aiml_session);


getRequest();
setupAiml();
processRequest(aiml_request);