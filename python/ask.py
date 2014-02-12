#!/usr/bin/python

import sys
import getopt
import aiml
import contextlib
import cStringIO
import marshal

from aimlizer import *


aiml_request = ""
aiml_file = ""
aiml_session = ""
dict_folder = ""
replacement_dict = {}
aimlizer = ""

@contextlib.contextmanager
def nostdout():
	save_stdout = sys.stdout
	sys.stdout = cStringIO.StringIO()
	yield
 	sys.stdout = save_stdout

def getRequest():
	global aiml_request, aiml_file, aiml_session, dict_folder
	myopts, args = getopt.getopt(sys.argv[1:],"q:a:s:d:")
	for o, a in myopts:
		if o == '-q':
			aiml_request = a
		elif o == '-a':
			aiml_file = a
		elif o == '-s':
			aiml_session = a
		elif o == '-d':
			dict_folder = a
		else:
			print("Usage: %s -q QUERY -a AIML-FILE -s SESSION-ID -d DICTONARY-FOLDER" % sys.argv[0])

def setupAiml():
	global aiml_kernel, aiml_file
	aiml_kernel = aiml.Kernel()
	with nostdout():
		aiml_kernel.learn(aiml_file)
	restoreSession()

def setupAimlizer():
	global aimlizer
	aimlizer = Aimlizer()
	aimlizer.addModule(NormalizerModule())
	#aimlizer.addModule(SpellCheckerModule('gutenberg_de_collection.list'))
	aimlizer.addModule(SpellCheckerModule('top10k_de.list'))
	aimlizer.addModule(ReplacerModule(dict_folder))

def processRequest(str):
	str = aimlizer.aimlize(str)
	print(aiml_kernel.respond(str, aiml_session))
	saveSession()

def saveSession():
	session = aiml_kernel.getSessionData(aiml_session)
	sessionFile = file("../sessions/"+aiml_session+".ses", "wb")
	marshal.dump(session, sessionFile)
	sessionFile.close()

def restoreSession():
	try:
		sessionFile = file("../sessions/"+aiml_session+".ses", "rb")
	except IOError:
		return
	session = marshal.load(sessionFile)
	sessionFile.close()
	for pred,value in session.items():
		aiml_kernel.setPredicate(pred, value, aiml_session)


getRequest()
setupAiml()
setupAimlizer()
processRequest(aiml_request)