#%%
import sqlalchemy as db
import pandas as pd
import tqdm as tqdm
import warnings
from IPython.display import Audio, display
def allDone():
    display(Audio(url='https://sound.peal.io/ps/audios/000/000/537/original/woo_vu_luvub_dub_dub.wav', autoplay=True))
import csv
csv.field_size_limit()
131072
csv.field_size_limit(256 << 10)
131072
csv.field_size_limit()
262144
#SQL Server [rename it as you need]
db_server = 'postgresql'
user = 'postgres'
password = 'admin'
ip = 'localhost'
db_name = 'NLP_secretaria_municipio'
# create the engine
engine = db.create_engine(f'{db_server}://{user}:{password}@{ip}/{db_name}')
import os
import sqlalchemy as db
tables = os.listdir('C:/Users/user/2. GIT_PROJECTS/Desafios/Projeto 5 - NLP Secretarias/tables/')
print(tables)
conn = engine.connect()
for i in tables:
    try:
        df_final = pd.read_csv('C:/Users/user/2. GIT_PROJECTS/Desafios/Projeto 5 - NLP Secretarias/tables/' + i, sep = r'Γ')
        df_final.to_sql(i.replace('.csv', ''), con = conn, index = False, if_exists = 'append')
        allDone()
    except:
        pass

conn.close()
