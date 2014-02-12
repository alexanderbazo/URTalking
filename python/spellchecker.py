# -*- coding: utf-8 -*-

import string
import re
import collections

#Toy-Spell-Checker (probabilistic retrieval) from http://norvig.com/spell-correct.html (Peter Norvig)

class SpellChecker:

	NWORDS = {}
	alphabet = ""

	def __init__(self, word_list):
		global NWORDS, alphabet
		self.NWORDS = self.train(self.words(file(word_list).read()))
		self.alphabet = 'abcdefghijklmnopqrstuvwxyzäöüß'

	def words(self, text):
		return re.findall('[a-z]+', text.lower())

	def train(self, features):
		model = collections.defaultdict(lambda: 1)
		for f in features:
			model[f] += 1
		return model

	def edits1(self, word):
		s = [(word[:i], word[i:]) for i in range(len(word) + 1)]
		deletes    = [a + b[1:] for a, b in s if b]
		transposes = [a + b[1] + b[0] + b[2:] for a, b in s if len(b)>1]
		replaces   = [a + c + b[1:] for a, b in s for c in self.alphabet if b]
		inserts    = [a + c + b     for a, b in s for c in self.alphabet]
		return set(deletes + transposes + replaces + inserts)

	def known_edits2(self, word):
		return set(e2 for e1 in self.edits1(word) for e2 in self.edits1(e1) if e2 in self.NWORDS)

	def known(self, words):
		return set(w for w in words if w in self.NWORDS)

	def correct(self, word):
		candidates = self.known([word]) or self.known(self.edits1(word)) or self.known_edits2(word) or [word]
		return max(candidates, key=self.NWORDS.get)