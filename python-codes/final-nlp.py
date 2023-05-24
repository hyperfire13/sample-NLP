#! C:/Users/WINDOWS/AppData/Local/Programs/Python/Python37/python.exe
#!/usr/bin/env python
# coding: utf-8

import spacy
from sklearn import svm
import joblib as joblib
import sys
import os
import json

this_dir = os.path.dirname(os.path.abspath(__file__))
nlp = spacy.load("en_core_web_md")

given_rationale = sys.argv[1]
given_rationale = given_rationale.replace("\\t", " ")
given_rationale = given_rationale.replace("\\n", "")

test_x = [given_rationale]
test_docs = [nlp(text) for text in test_x]
text_x_word_vectors = [x.vector for x in test_docs]


real_model = joblib.load(this_dir + '/raquel-training.joblib')

result = real_model.predict(text_x_word_vectors)

print(result[0])
