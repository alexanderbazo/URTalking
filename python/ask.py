#!/usr/bin/python
# -*- coding: iso-8859-1 -*-


import sys
import getopt
import aiml
import contextlib
import cStringIO
import marshal
import itertools
import string
import codecs



from os import listdir
from os.path import isfile, join
from aimlizer import *


aiml_request = ""
aiml_file = ""
aiml_session = ""
dict_folder = ""
spellchecker_file = ""
replacement_dict = {}
aimlizer = ""
stopwords = ""
testfile = ""
max_permutations = ""
testerrors = 0

@contextlib.contextmanager
def nostdout():
	save_stdout = sys.stdout
	sys.stdout = cStringIO.StringIO()
	yield
 	sys.stdout = save_stdout

def getRequest():
	global aiml_request, aiml_file, aiml_session, dict_folder, spellchecker_file,stopwords,testfile,max_permutations
	myopts, args = getopt.getopt(sys.argv[1:],"q:a:s:d:c:l:t:p:")
	for o, a in myopts:
		if o == '-q':
			aiml_request = a
		elif o == '-a':
			aiml_file = a
		elif o == '-s':
			aiml_session = a
		elif o == '-d':
			dict_folder = a
		elif o == '-c':
			spellchecker_file = a
		elif o == '-l':
			stopwords = a
		elif o == '-t':
			testfile = a
		elif o == '-p':
			max_permutations = a
		else:
			print("Usage: %s -q QUERY -a AIML-FILE -s SESSION-ID -d DICTONARY-FOLDER -c WORD-LIST FOR SPELLCHECKER" % sys.argv[0])

def setupAiml():
	global aiml_kernel, aiml_file
	aiml_kernel = aiml.Kernel()
	aiml_kernel.verbose(False)
	with nostdout():
		aiml_kernel.learn(aiml_file)
	restoreSession()

def setupAimlizer():
	global aimlizer
	aimlizer = Aimlizer()


	#aimlizer.addModule(SpellCheckerModule(spellchecker_file))
	aimlizer.addModule(NormalizerModule())

	dictonaries = [f for f in listdir(dict_folder) if isfile(join(dict_folder,f))]
	for file in dictonaries:
		if file.endswith(".dict"):
			aimlizer.addModule(ReplacerModule(dict_folder+"/"+file))


	aimlizer.addModule(StopwordReductionModule(stopwords))
	aimlizer.addModule(FinalizerModule())

def processRequest(str):
	str = unicode(str, "utf-8")
	str = aimlizer.aimlize(str)

	aimlizer_steps = aimlizer.getSteps()


	last_query = restoreQuery()
	permutations = list(itertools.permutations(str.split(), len(str.split())))
	valid_queries = []
	for permutation in permutations:
		query = ' '.join(permutation)
		try:
			tmp_1 = aiml_kernel.respond(last_query, aiml_session)
			tmp = aiml_kernel.respond(query, aiml_session)
			if tmp != '':
				valid_queries.append(query)
		except Error:
			continue

	tmp = ''

	if len(valid_queries) > 0:
		tmp_1 = aiml_kernel.respond(last_query, aiml_session)
		tmp = aiml_kernel.respond(valid_queries[0], aiml_session)
	else:
		tmp = 'WARNING: No match found for input: '+str
	print(tmp+" <span class='debug'>(aimlized query: "+str+" [")
	print(', '.join(aimlizer_steps)+"])</span>")
	saveSession()
	if len(valid_queries) > 0:
		saveQuery(valid_queries[0], aiml_session)

def test():
	if testfile == "":
		return
	else:
		file = codecs.open(testfile, 'r', 'utf-8')
		questions = file.read().split('\n')

		for question in questions:
			print(question.strip().upper())
			testRequest(question)

		file.close()

def testRequest(str):
	global testerrors
	input = str
	str = aimlizer.aimlize(str)

	last_query = restoreQuery()
	permutations = list(itertools.permutations(str.split(), len(str.split())))

	valid_queries = []

	if len(str.split()) > int(max_permutations):
		permutations = [str]

	ok = -1;

	for permutation in permutations:
		query = ' '.join(permutation)
		try:
			tmp_1 = aiml_kernel.respond(last_query, aiml_session)
			tmp = aiml_kernel.respond(query, aiml_session)
			#print(tmp)
			if tmp != '':
				ok = 1
				print("\""+input.encode('utf-8')+"\",\""+str+"\",\"OK\"")
		except (UnicodeDecodeError, UnicodeEncodeError) as e:
			#print("aiml-error at: " + input)
			#print(e)
			continue

	if ok == -1:
		try:
			print("\""+input.encode('utf-8')+"\",\""+str+"\",\"FAILED\"")
		except (UnicodeDecodeError, UnicodeEncodeError) as e:
			#print("print-error at: " + input)
			#print(e)
			pass

	saveSession()
	if len(valid_queries) > 0:
		saveQuery(valid_queries[0], aiml_session)



def saveQuery(query, aiml_session):
	sessionFile = file("../sessions/last_query_"+aiml_session+".ses", "wb")
	marshal.dump(query, sessionFile)
	sessionFile.close()

def restoreQuery():
	try:
		lines = open("../sessions/last_query_"+aiml_session+".ses").readlines()
	except IOError:
		return "HALLO"
	if len(lines) > 0:
		tmp = str(lines[0])
		tmp = filter(string.printable.__contains__, tmp)
		tmp = tmp.replace("s\r", "")
		return tmp
	else:
		return "HALLO"

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
#test()
processRequest(aiml_request)
