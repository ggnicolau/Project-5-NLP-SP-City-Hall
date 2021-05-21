import gensim
import pyLDAvis.sklearn
from nltk.tokenize import RegexpTokenizer
from stop_words import get_stop_words
from gensim import corpora, models
import gensim
import os
from os import path
from time import sleep
import matplotlib.pyplot as plt
import random
from wordcloud import WordCloud, STOPWORDS
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

#%%Def function
def vis(x):
    #Load file
    df = pd.read_csv('C:/Users/user/2. GIT_PROJECTS/Desafios/Projeto 5 - NLP Secretarias/tables_clean/doria/'+str(x)+'.csv', sep=r'Γ')
    df = df.dropna()
    train_text = df['clean_title']
    # Create tokens
    df.dropna()
    tokenized_sentences = [p.lower().split() for p in train_text]
    # tokenized_sentences
    id2word = gensim.corpora.Dictionary(tokenized_sentences)
    texts = tokenized_sentences
    corpus = [id2word.doc2bow(text) for text in texts]
    # print(texts[:1])
    # train_text[300]
    # id2word[16]

    # Tf-iDf
    #tfidf = TfidfModel(corpus, id2word)
    #
    #low_value = 0.9
    #low_value_words = []
    #for bow in corpus:
    #    low_value_words += [id for id, value in tfidf[bow] if value < low_value]
    #
    #id2word.filter_tokens(bad_ids=low_value_words)
    #
    #new_corpus = [id2word.doc2bow(text) for text in texts]
    #
    #new_corpus
    #
    #
    # Create Model
    lda_model = gensim.models.ldamodel.LdaModel(corpus=corpus,
                                                id2word=id2word,
                                                num_topics=30,
                                                random_state=100,
                                                update_every=1,
                                                chunksize=100,
                                                passes=10,
                                                alpha='auto',
                                                per_word_topics=True)
    print('show txt trained 7', train_text[7])
    lda_model.save('lda.model'+str(x))

    # Create Model_suggestion_by_gensim
    #num_topics = 30
    #chunksize = 2000
    #passes = 20
    #iterations = 400
    #eval_every = None
    #
    #lda_model = gensim.models.ldamodel.LdaModel(corpus=corpus,
    #                                            id2word=id2word,
    #                                            chunksize=chunksize,
    #                                            alpha='auto',
    #                                            eta='auto',
    #                                            iterations=iterations,
    #                                            num_topics=num_topics,
    #                                            passes=passes,
    #                                            eval_every=eval_every)
    #
    #train_text[7]
    #
    print('show topics', lda_model.show_topics())
    print('print topic 2', lda_model.print_topics()[2])
    doc_lda = lda_model[corpus]
    my_model = lda_model[corpus]

    print('print topics', lda_model.print_topics())

    # LDA Graphs
        # WordClouds
    for t in range(lda_model.num_topics):
        plt.figure(figsize=(20,10))
        #print(dict(lda_model.show_topic(t, 30))
        plt.imshow(WordCloud(width=1600, height=800).fit_words(dict(lda_model.show_topic(t, 30)))) #, facecolor='k')  if want to strip from borders
        plt.axis("off")
        plt.title("Topic #" + str(t))
        plt.tight_layout(pad=0)
        plt.savefig(str(x)+str(t)+'.png', dpi=300)
        plt.show()
        plt.clf()

        # pyLDAvis
    display(HTML("<style>.container { max-width:100% !important; }</style>"))
    display(HTML("<style>.output_result { max-width:100% !important; }</style>"))
    display(HTML("<style>.output_area { max-width:100% !important; }</style>"))
    display(HTML("<style>.input_area { max-width:100% !important; }</style>"))

    #matplotlib inline
    visu = pyLDAvis.gensim_models.prepare(
        topic_model=lda_model, corpus=corpus, dictionary=id2word, mds='mmds')
    pyLDAvis.enable_notebook(local=True)
    pyLDAvis.save_html(visu, str(x)+'.html')
    return allDone()
#%%create your graphs!
vis('assistencia_social_doria_covas')
vis('capitalsp_doria_covas')
vis('controladoria_geral_doria_covas')
vis('cultura_doria_covas')
vis('desenvolvimento_doria_covas')
vis('direitos_humanos_doria_covas')
vis('direitos_humanos-poprua_doria_covas')
vis('esportes_doria_covas')
vis('fazenda_doria_covas')
vis('gestao_doria_covas')
vis('governo_doria_covas')
vis('habitacao_doria_covas')
vis('justica_doria_covas')
vis('meio_ambiente_doria_covas')
vis('obras_doria_covas')
vis('pessoa_com_deficiencia_doria_covas')
vis('procuradoria_geral_doria_covas')
vis('relacoes_internacionais_doria_covas')
vis('relacoes_sociais_doria_covas')
vis('saude_doria_covas')
#vis('seguranca_urbana-defesa_civil_doria_covas')
vis('seguranca_urbana-guarda_civil_doria_covas')
vis('subprefeituras_doria_covas')
vis('transportes_doria_covas')
vis('urbanismo_doria_covas')
vis('seguranca_urbana_doria_covas')
#%% Big table
vis('big_table_doria_covas')
