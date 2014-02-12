#!/usr/bin/python

import sys
import getopt
import aiml
import subprocess
import contextlib
import cStringIO
import os.path
import marshal
import string
import re


aiml_request = "";
aiml_file = "";
aiml_session = "";
dict_folder = "";
replacement_dict = {};

@contextlib.contextmanager
def nostdout():
	save_stdout = sys.stdout
	sys.stdout = cStringIO.StringIO()
	yield
 	sys.stdout = save_stdout

def getRequest():
	global aiml_request, aiml_file, aiml_session, dict_folder;
	myopts, args = getopt.getopt(sys.argv[1:],"q:a:s:d:");
	for o, a in myopts:
		if o == '-q':
			aiml_request = a;
		elif o == '-a':
			aiml_file = a;
		elif o == '-s':
			aiml_session = a;
		elif o == '-d':
			dict_folder = a;
		else:
			print("Usage: %s -q QUERY -a AIML-FILE -s SESSION-ID -d DICTONARY-FOLDER" % sys.argv[0]);

def setupAiml():
	global aiml_kernel, aiml_file;
	aiml_kernel = aiml.Kernel();
	with nostdout():
		aiml_kernel.learn(aiml_file);
	restoreSession();

def loadDictionaries():
	global replacement_dict;
	from os import walk;
	files = [];
	for(dirpath, dirnames, filenames) in walk(dict_folder):
		files.extend(filenames);
		break;
	for(filename) in files:
		with open(dict_folder+"/"+filename) as file:
			for(line) in file.readlines():
				parts = line.split(":");
				key = parts[0];
				values = parts[1].split(",");
				for(value) in values:
					replacement_dict[value.rstrip('\n').upper()] = key.upper();

def processRequest(str):
	str = str.translate(string.maketrans("",""), string.punctuation);
	str = re.sub(' +',' ',str);
	query = "";
	for(word) in str.split(" "):
		try:
			replacement = replacement_dict[word.upper()];
			query += replacement+" ";
		except KeyError:
			query += word+" ";

	query = query[:-1];
	print(aiml_kernel.respond(query, aiml_session));
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
loadDictionaries();
processRequest(aiml_request);