import nltk
from nltk.corpus import stopwords
import re
import numpy as np
from pprint import pprint
import logging
import texthero as hero
from texthero import preprocessing
import pandas as pd
from IPython.display import Audio, display
def allDone():
    display(Audio(url='https://sound.peal.io/ps/audios/000/000/537/original/woo_vu_luvub_dub_dub.wav', autoplay=True))
import warnings
warnings.filterwarnings("ignore")
import csv
csv.field_size_limit()
131072
csv.field_size_limit(256 << 10)
131072
csv.field_size_limit()
262144
logging.basicConfig(
    format='%(asctime)s : %(levelname)s : %(message)s', level=logging.ERROR)
warnings.filterwarnings("ignore", category=DeprecationWarning)
from gensim.models.coherencemodel import CoherenceModel
from gensim.models.ldamodel import LdaModel
from gensim.test.utils import common_corpus, common_dictionary
from gensim.models import LdaModel
from pprint import pprint
from gensim.models import CoherenceModel, LdaModel, LsiModel, HdpModel
import numpy as np
import gensim
import matplotlib.pyplot as plt
import operator
import re
import os
from IPython.core.display import display, HTML
import warnings
import pyLDAvis.gensim_models
from sklearn.manifold import TSNE
from sklearn.decomposition import LatentDirichletAllocation
import pyLDAvis
import pandas as pd
from gensim.corpora import Dictionary
from gensim.models import TfidfModel
import gensim.downloader as api
#%% Load file
df = pd.read_csv('C:/Users/user/2. GIT_PROJECTS/Desafios/Projeto 5 - NLP Secretarias/OUTPUT BOX/seguranca_urbana.csv', sep=r'Γ')
df = df.dropna()
train_text = df['clean_lem']

#%% Create tokens
df.dropna()
tokenized_sentences = [p.lower().split() for p in train_text]

# tokenized_sentences
id2word = gensim.corpora.Dictionary(tokenized_sentences)
texts = tokenized_sentences
corpus = [id2word.doc2bow(text) for text in texts]
# print(texts[:1])
# train_text[300]
# id2word[16]

# %% Tf-iDf
tfidf = TfidfModel(corpus, id2word)

low_value = 0.1
low_value_words = []
for bow in corpus:
    low_value_words += [id for id, value in tfidf[bow] if value < low_value]

id2word.filter_tokens(bad_ids=low_value_words)

new_corpus = [id2word.doc2bow(text) for text in texts]

#%% Create Model
lda_model = gensim.models.ldamodel.LdaModel(corpus=new_corpus,
                                            id2word=id2word,
                                            num_topics=30,
                                            random_state=100,
                                            update_every=1,
                                            chunksize=100,
                                            passes=10,
                                            alpha='auto',
                                            per_word_topics=True)
train_text[7]
# In[62]:
print(lda_model.print_topics()[2])
doc_lda = lda_model[new_corpus]
lda_model[new_corpus]

lda_model.print_topics()

#%%
import numpy as np
import tqdm
grid = {}
grid['Validation_Set'] = {}
# Topics range
min_topics = 2
max_topics = 30
step_size = 1
topics_range = range(min_topics, max_topics, step_size)
# Alpha parameter
alpha = list(np.arange(0.01, 1, 0.3))
alpha.append('symmetric')
alpha.append('asymmetric')
# Beta parameter
beta = list(np.arange(0.01, 1, 0.3))
beta.append('symmetric')
# Validation sets
num_of_docs = len(new_corpus)
corpus_sets = [# gensim.utils.ClippedCorpus(corpus, num_of_docs*0.25),
               # gensim.utils.ClippedCorpus(corpus, num_of_docs*0.5),
               gensim.utils.ClippedCorpus(new_corpus, num_of_docs*0.75),
               new_corpus]
corpus_title = ['75% Corpus', '100% Corpus']
model_results = {'Validation_Set': [],
                 'Topics': [],
                 'Alpha': [],
                 'Beta': [],
                 'Coherence': []
                }
# Can take a long time to run
if 1 == 1:
    pbar = tqdm.tqdm(total=540)

    # iterate through validation corpuses
    for i in range(len(corpus_sets)):
        # iterate through number of topics
        for k in topics_range:
            # iterate through alpha values
            for a in alpha:
                # iterare through beta values
                for b in beta:
                    # get the coherence score for the given parameters
                    cv = compute_coherence_values(corpus=corpus_sets[i], dictionary=id2word,
                                                  k=k, a=a, b=b)
                    # Save the model results
                    model_results['Validation_Set'].append(corpus_title[i])
                    model_results['Topics'].append(k)
                    model_results['Alpha'].append(a)
                    model_results['Beta'].append(b)
                    model_results['Coherence'].append(cv)

                    pbar.update(1)
    pd.DataFrame(model_results).to_csv('lda_tuning_results.csv', index=False)
    pbar.close()













## %%
#warnings.filterwarnings('ignore')  # Let's not pay heed to them right now
#
##from gensim.models.wrappers import LdaMallet
#
#
#def ret_top_model():
#    """
#    Since LDAmodel is a probabilistic model, it comes up different topics each time we run it. To control the
#    quality of the topic model we produce, we can see what the interpretability of the best topic is and keep
#    evaluating the topic model until this threshold is crossed.
#
#    Returns:
#    -------
#    lm: Final evaluated topic model
#    top_topics: ranked topics in decreasing order. List of tuples
#    """
#    top_topics = [(0, 0)]
#    while top_topics[0][1] < 0.97:
#        lm = LdaModel(corpus=new_corpus, id2word=id2word)
#        coherence_values = {}
#        for n, topic in lm.show_topics(num_topics=-1, formatted=False):
#            topic = [word for word, _ in topic]
#            cm = CoherenceModel(
#                topics=[topic], texts=train_text, dictionary=id2word, window_size=10)
#            coherence_values[n] = cm.get_coherence()
#        top_topics = sorted(coherence_values.items(),
#                            key=operator.itemgetter(1), reverse=True)
#    return lm, top_topics
#
#lm, top_topics = ret_top_model()
#print(top_topics[:5])
#pprint([lm.show_topic(topicid) for topicid, c_v in top_topics[:10]])
#lda_lsi_topics = [[word for word, prob in lm.show_topic(
#    topicid)] for topicid, c_v in top_topics]
#
#allDone()
# %%


#top_topics
#
#
#top_topics = lda_model.top_topics(new_corpus)
# , num_words=20)
#
# Average topic coherence is the sum of topic coherences of all topics, divided by the number of topics.
#avg_topic_coherence = sum([t[1] for t in top_topics]) / num_topics
#print('Average topic coherence: %.4f.' % avg_topic_coherence)
#
#pprint(top_topics)
# %%
#
#>> >
#model = LdaModel(common_corpus, 5, common_dictionary)
#>> >
#cm = CoherenceModel(model=model, corpus=common_corpus, coherence='u_mass')
#coherence = cm.get_coherence()
#
#>> >
#m1 = LdaModel(common_corpus, 3, common_dictionary)
#m2 = LdaModel(common_corpus, 5, common_dictionary)
#>> >
#cm = CoherenceModel.for_models(
#    [m1, m2], common_dictionary, corpus=common_corpus, coherence='u_mass')
#
#top_topics = model.top_topics(corpus)  # , num_words=20)
#
## Average topic coherence is the sum of topic coherences of all topics, divided by the number of topics.
#avg_topic_coherence = sum([t[1] for t in top_topics]) / num_topics
#print('Average topic coherence: %.4f.' % avg_topic_coherence)
#
#pprint(top_topics)  # get coherence value
#
