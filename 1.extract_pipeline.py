import os
import glob
from tika import parser
from pandas import DataFrame
import pandas as pd
import re
from IPython.display import Audio, display
def allDone():
    display(Audio(url='https://sound.peal.io/ps/audios/000/000/537/original/woo_vu_luvub_dub_dub.wav', autoplay=True))
# %% Big Table
# Define Function to automate each DataFrame extraction
def get_data(y):
    ext = "*.txt"
    PATH = f'C:\\3\\DOUTORADO\\NATUREZAS\\1\\3. NOTÍCIAS INSTITUCIONAIS\\prefeitura\\{y}\\'

    # Find all the files with that extension
    files = []
    for dirpath, dirnames, filenames in os.walk(PATH):
        files += glob.glob(os.path.join(dirpath, ext))
    print(files)

    # Create a Pandas Dataframe to hold the filenames and the text
    df = DataFrame(columns=("filename", "text"))
    print(df)
    # Process each file in turn, parsing with Tika and storing in the dataframe
    for idx, filename in enumerate(files):
        data = parser.from_file(filename)
        text = data['content']
        df.loc[idx] = [filename, text]
    #Get data and strip text from date
    df['date'] = df['text'].apply(lambda x : re.search('\d{1,2}\/\d\d\/\d\d\d\d', x).group(0) if re.search('\d{1,2}\/\d\d\/\d\d\d\d', x) is not None else 's/data')
    #Drop duplicates
    df = df.drop_duplicates(subset=["filename", "text"])

    # For debugging, print what we found
    print(df)
    df.to_csv(f'C:/Users/user/2. GIT_PROJECTS/Desafios/Projeto 5 - NLP Secretarias/OUTPUT BOX/{y}.csv', sep=r'Γ', index=False)
    # Separate Mandates
    search_values = ['2013','2014','2015','2016']
    df_haddad = df[df.date.str.contains('|'.join(search_values ))]
    search_values = ['2017','2018','2019']
    df_doria_covas = df[df.date.str.contains('|'.join(search_values ))]
    df_haddad.to_csv(f'C:/Users/user/2. GIT_PROJECTS/Desafios/Projeto 5 - NLP Secretarias/OUTPUT BOX/haddad/{y}_haddad.csv', sep=r'Γ', index=False)
    df_doria_covas.to_csv(f'C:/Users/user/2. GIT_PROJECTS/Desafios/Projeto 5 - NLP Secretarias/OUTPUT BOX/doria/{y}_doria_covas.csv', sep=r'Γ', index=False)
    print(df_haddad)
    return allDone()


# %% Test and see (DON'T PARALLELIZE IT OR TIKA WILL FREEZE)
get_data('assistencia_social')
get_data('capitalsp')
get_data('controladoria_geral')
get_data('cultura')
get_data('desenvolvimento')
get_data('direitos_humanos')
get_data('direitos_humanos-poprua')
get_data('esportes')
get_data('fazenda')
get_data('gestao')
get_data('governo')
get_data('habitacao')
get_data('justica')
get_data('meio_ambiente')
get_data('obras')
get_data('pessoa_com_deficiencia')
get_data('procuradoria_geral')
get_data('relacoes_internacionais')
get_data('relacoes_sociais')
get_data('saude')
get_data('seguranca_urbana-defesa_civil')
get_data('seguranca_urbana-guarda_civil')
get_data('subprefeituras')
get_data('transportes')
get_data('urbanismo')
get_data('seguranca_urbana')

# %% Big Table
# What file extension to find, and where to look from
ext = "*.txt"
PATH = "C:\\3\\DOUTORADO\\NATUREZAS\\1\\3. NOTÍCIAS INSTITUCIONAIS\\prefeitura\\direitos_humanos-poprua\\"

# Find all the files with that extension
files = []
for dirpath, dirnames, filenames in os.walk(PATH):
    files += glob.glob(os.path.join(dirpath, ext))
print(files)

# Create a Pandas Dataframe to hold the filenames and the text
df = DataFrame(columns=("filename", "text"))
print(df)
# Process each file in turn, parsing with Tika and storing in the dataframe
for idx, filename in enumerate(files):
    data = parser.from_file(filename)
    text = data['content']
    df.loc[idx] = [filename, text]
#Get data and strip text from date
df['date'] = df['text'].apply(lambda x : re.search('\d{1,2}\/\d\d\/\d\d\d\d', x).group(0) if re.search('\d{1,2}\/\d\d\/\d\d\d\d', x) is not None else 's/data')
#Drop duplicates
df = df.drop_duplicates(subset=["filename", "text"])
# Separate Mandates
search_values = ['2013','2014','2015','2016']
df_haddad = df[df.date.str.contains('|'.join(search_values ))]
search_values = ['2017','2018','2019']
df_doria_covas = df[df.date.str.contains('|'.join(search_values ))]
# For debugging, print what we found
print(df_haddad)
df.to_csv(f'C:/Users/user/2. GIT_PROJECTS/Desafios/Projeto 5 - NLP Secretarias/OUTPUT BOX/big_table.csv', sep=r'Γ', index=False)
df_haddad.to_csv(f'C:/Users/user/2. GIT_PROJECTS/Desafios/Projeto 5 - NLP Secretarias/OUTPUT BOX/direitos_humanos-poprua_haddad.csv', sep=r'Γ', index=False)
df_doria_covas.to_csv(f'C:/Users/user/2. GIT_PROJECTS/Desafios/Projeto 5 - NLP Secretarias/OUTPUT BOX/big_table_doria_covas.csv', sep=r'Γ', index=False)
allDone()


# Enable the table_schema option in pandas,
# data-explorer makes this snippet available with the `dx` prefix:
#pd.options.display.html.table_schema = True
#pd.options.display.max_rows = None
#a = pd.read_csv(
#    'C:/Users/user/2. GIT_PROJECTS/Desafios/Projeto 5 - NLP Secretarias/urbanismo.csv', sep=r'Γ', engine='python')
#a
