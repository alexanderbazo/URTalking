import string
import re
import codecs
import sys

from spellchecker import *

class Aimlizer:
	modules = []

	def addModule(self, module):
		self.modules.append(module)

	def aimlize(self, string):
		string  = string.encode('utf-8')
		for(module) in self.modules:
			string = module.process(string)
		return string

class AimlizerModule:

	def process(self, string):
		return string

class NormalizerModule(AimlizerModule):

	def process(self, str):
		exclude = set(string.punctuation)
		str = ''.join(ch for ch in str if ch not in exclude)
		
		str = re.sub('/',' ',str)
		str = re.sub('-',' ',str)
		str = re.sub(' +',' ',str)

		#print("Normalizer: " + str)
		return str.upper()

class StopwordReductionModule(AimlizerModule):
	list = []
	
	def __init__(self, stopwordlist):
		global list
		file = codecs.open(stopwordlist, 'r', 'utf-8')
		words = file.read().splitlines()
		self.list = [word.strip().upper() for word in words]
		file.close()

	def process(self, str):
		out = ""
		for(word) in str.split(" "):
			if(word.upper() not in self.list):
				out += word+" "
		out = out[:-1]
		#print("Stopwords: " + out)
		return out

class SpellCheckerModule(AimlizerModule):
	sp = None

	def __init__(self, word_list):
		global sp
		self.sp = SpellChecker(word_list)

	def process(self, str):
		out = ""
		for(word) in str.split(" "):
			correction = self.sp.correct(word)
			out += correction+" "
		out = out[:-1]
		print("Spellchecker: " + out)
		return out


class ReplacerModule(AimlizerModule):
	dict = []
	file = ""
	
	def __init__(self, dictionary_file):
		self.loadDictionaries(dictionary_file)
		
	def loadDictionaries(self, dictionary_file):
		global dict,file
		self.file = dictionary_file
		
		with codecs.open(dictionary_file, 'r', 'utf-8') as file:
			for line in file.readlines():
				line = line.encode('utf-8').strip()
				line.replace(': ', ':')
				if line[0:2] == "//":
					continue
				if line == "":
						continue
				parts = line.split(":")
				key = parts[0]
				try:
					tmp = parts[1]
					values = re.sub("\(([^)]+)\)", lambda x:x.group(0).replace(',',';'), tmp)
					values = values.split(",")
					
				except IndexError:
					continue
				for value in values:
					if  not "(" in value:
						tmp = {'word': value.strip().upper(), 'replacement': key.strip().upper(), 'context': '', 'distance': ''}
						self.dict.append(tmp)
					else:
						str = value[0:value.find("(")].strip()
						tmp = value[value.find("(")+1:value.find(")")]
						for context in tmp.split(";"):
							neighbour = context[0:context.find("[")]
							count = context[context.find("[")+1:context.find("]")]
							tmp = {'word': str.strip().upper(), 'replacement': key.strip().upper(), 'context': neighbour.strip().upper(), 'distance': count}
							self.dict.append(tmp)
				


	def process(self, str):
		tmp = self.replaceTokens(str)
		return tmp

	def replaceTokens(self, str):
		out = str
		words = str.split(" ")
		word_count = len(words)
		replacement = ""
		for i in range(0, word_count):
			for j in reversed(range(0, word_count+1)):
				phrase = " ".join(words[i:j]).upper()
				if phrase == "":
					continue
				#print(phrase)
				replacement = self.replacePhrase(phrase, str)
				if(replacement != ''):
					out = out.replace(" "+phrase+" ", " "+replacement+" ");
				
		#print("Replacer (" + self.file + "):" + out)
		return out


	def replacePhrase(self, phrase, str):
		list = str.split(" ")
		for word in self.dict:
			test = word['word']
			if phrase == test:
				distance = word['distance']
				if distance == '':
					return word['replacement']
				else:
					context = word['context']
					try:
						delta = abs(list.index(phrase) - list.index(context))
						if delta <= distance:
							return word['replacement']
					except ValueError:
						continue

		return ''

class FinalizerModule(AimlizerModule):
	
	def process(self, str):
		ulist = []
		[ulist.append(x) for x in str.split() if x not in ulist]
		return ' '.join(ulist)







