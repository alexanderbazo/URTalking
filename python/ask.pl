#!/usr/bin/python

import sys
import getopt
import aiml
import subprocess
import contextlib
import cStringIO


aiml_request = "";
aiml_file = "";

@contextlib.contextmanager
def nostdout():
	save_stdout = sys.stdout
	sys.stdout = cStringIO.StringIO()
	yield
 	sys.stdout = save_stdout

def getRequest():
	global aiml_request, aiml_file;
	myopts, args = getopt.getopt(sys.argv[1:],"q:a:");
	for o, a in myopts:
		if o == '-q':
			aiml_request = a;
		elif o == '-a':
			aiml_file = a;
		else:
			print("Usage: %s -q query -a aiml-file" % sys.argv[0]);

def setupAiml():
	global aiml_kernel, aiml_file;
	aiml_kernel = aiml.Kernel();
	with nostdout():
		aiml_kernel.learn(aiml_file);

def processRequest(str):
	print(aiml_kernel.respond(str));

getRequest();
setupAiml();
processRequest(aiml_request);