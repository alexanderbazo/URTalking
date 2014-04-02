import string
import re

from spellchecker import *

class Aimlizer:
	modules = []

	def addModule(self, module):
		self.modules.append(module)

	def aimlize(self, string):
		for(module) in self.modules:
			string = module.process(string)
		return string

class AimlizerModule:

	def process(self, string):
		return string

class NormalizerModule(AimlizerModule):

	def process(self, str):
		str = str.translate(string.maketrans("",""), string.punctuation)
		str = re.sub(' +',' ',str)
		return str

class StopwordReductionModule(AimlizerModule):
	list = []
	
	def __init__(self, stopwordlist):
		global list
		list = open(stopwordlist,'r').read().splitlines()
		list = [word.upper() for word in list]

	def process(self, str):
		out = ""
		for(word) in str.split(" "):
			if(word.upper() not in list):
				out += word+" "
		out = out[:-1]
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
		return out


class ReplacerModule(AimlizerModule):
	replacement_dict = {}
	context_dict = {}
	
	def __init__(self, dictionary_file):
		self.loadDictionaries(dictionary_file)
		
	def loadDictionaries(self, dictionary_file):
		global replacement_dict
		with open(dictionary_file) as file:
			for line in file.readlines():
				if line[0:2] == "//":
					continue
				parts = line.split(":")
				key = parts[0]
				try:
					values = parts[1].split(",")
				except IndexError:
					print(values)
				for value in values:
					if(self.extractNeighborContext(value, key) == 0):
						self.replacement_dict[value.rstrip('\n').upper()] = key.upper()

	def extractNeighborContext(self, str, key):
		global context_dict
		if  not "(" in str:
			return 0

		tmp = str[str.find("(")+1:str.find(")")]
		str = str[0:str.find("(")]
		self.context_dict[str.rstrip('\n').upper()] = key.upper()+","+tmp
		return 1


	def process(self, str):
		return self.replaceTokens(str)

	def replaceTokens(self, str):
		out = str
		words = str.split(" ")
		word_count = len(words)
		replacement = ""
		
		for i in range(0, word_count):
			for j in reversed(range(0, word_count+1)):
				phrase = " ".join(words[i:j])
				replacement = self.replaceTokenInContext(phrase, str)
				if(replacement != ""):
					out = out.replace(phrase, replacement);
				
		return out

	def replaceTokenInContext(self, phrase, str):
		list = str.split(" ")
		tmp = ""
		try:
			tmp = self.context_dict[phrase.upper()]
			replacement = tmp[0:tmp.find(",")]
			tokens = tmp[tmp.find(",")+1:len(tmp)]
			for token in tokens.split(";"):
				word = token[0:token.find("[")]
				count = token[token.find("[")+1:token.find("]")]
				if word in list:
					distance = abs(list.index(phrase) - list.index(word))
					if distance <= count:
						return replacement
				try:
					tmp = self.replacement_dict[phrase.upper()]
				except KeyError:
					tmp = ""
		except KeyError:
			try:
				tmp = self.replacement_dict[phrase.upper()]
			except KeyError:
				tmp = ""
		return tmp

class FinalizerModule(AimlizerModule):
	
	def process(self, str):
		ulist = []
		[ulist.append(x) for x in str.split() if x not in ulist]
		return ' '.join(ulist)







