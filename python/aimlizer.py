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

class SpellCheckerModule(AimlizerModule):
	sp = None

	def __init__(self, word_list):
		global sp
		self.sp = SpellChecker(word_list)

	def process(self, str):
		out = ""
		for(word) in str.split(" "):
			correction = self.sp.correct(word)
			out += correction+" ";
		out = out[:-1]
		return out


class ReplacerModule(AimlizerModule):
	replacement_dict = {}
	
	def __init__(self, dictionary_folder):
		self.loadDictionaries(dictionary_folder)
		
	def loadDictionaries(self, dictionary_folder):
		global replacement_dict
		from os import walk
		files = []
		for(dirpath, dirnames, filenames) in walk(dictionary_folder):
			files.extend(filenames)
			break
		for(filename) in files:
			with open(dictionary_folder+"/"+filename) as file:
				for(line) in file.readlines():
					parts = line.split(":")
					key = parts[0]
					values = parts[1].split(",")
					for(value) in values:
						self.replacement_dict[value.rstrip('\n').upper()] = key.upper()
		
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
				try:
					replacement = self.replacement_dict[phrase.upper()]
				except KeyError:
					replacement = ""
				if(replacement != ""):
					out = out.replace(phrase, replacement);
				
		return out