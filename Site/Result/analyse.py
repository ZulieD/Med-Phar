import sys
import pandas as pd


print('ok')
'''
medicament = sys.argv[1]
nom_maladie = sys.argv[2]
sexe_patient = sys.argv[3]
date_naissance_patient = sys.argv[4]
poids_patient = sys.argv[5]
taille_patient = sys.argv[6]

df_medicament=pd.read_csv("../../Analyse/df_medicament.csv")
df=pd.read_csv("../../../df_patient.csv")

filtered_df_medicament = df_medicament[df_medicament['DRUGNAME'] == medicament]

utilisation = filtered_df_medicament['INDICATION_NAME_FR']
df_merged = pd.merge(df, filtered_df_medicament, on='id_medicament')

## Pour repartition sexe 

sexe_counts = df_merged['GENDER_FR_encoded'].value_counts()

# Créer le graphique à secteurs
labels = ['Homme', 'Femme']  # Assumant que 1.0 = Homme et 2.0 = Femme
sizes = [sexe_counts[1.0], sexe_counts[2.0]]
colors = ['lightblue', 'lightpink']
explode = (0.1, 0)  # Séparer le premier secteur légèrement

plt.pie(sizes, explode=explode, labels=labels, colors=colors, autopct='%1.1f%%',
        shadow=True, startangle=140)
plt.axis('equal')  # Assure que le camembert est dessiné en cercle

plt.title('Répartition par sexe des utilisations de ', medicament)
lien1='repartition_sexe.png'
plt.savefig(lien1)

## Pour deces 

sexe_counts = df_merged['DEATH'].value_counts()

# Créer le graphique à secteurs
labels = ['Mort', 'Vivant']  # Assumant que 1.0 = Homme et 2.0 = Femme
sizes = [sexe_counts[1.0], sexe_counts[2.0]]
colors = ['red', 'lightgreen']
explode = (0.1, 0)  # Séparer le premier secteur légèrement

plt.pie(sizes, explode=explode, labels=labels, colors=colors, autopct='%1.1f%%',
        shadow=True, startangle=140)
plt.axis('equal')  # Assure que le camembert est dessiné en cercle

plt.title('Répartition des deces de', medicament)
plt.savefig('repartition_mort.png')
plt.show()

print(utilisation, lien1)


'''