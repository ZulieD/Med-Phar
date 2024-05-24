import pandas as pd 
from sklearn.model_selection import train_test_split

df=pd.read_csv('df_patient.csv')

y=df['id_effet_secondaire']
X = df.drop('id_effet_secondaire', axis=1)

X_train,X_test,y_train,y_test=train_test_split(X,y, test_size=0.4, random_state=42)

## faire model regression 