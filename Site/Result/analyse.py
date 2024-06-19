import sys
import pandas as pd

medicament = sys.argv[1]
nom_maladie = sys.argv[2]
sexe_patient = sys.argv[3]
date_naissance_patient = sys.argv[4]
poids_patient = sys.argv[5]
taille_patient = sys.argv[6]

# Affichage des arguments pour vérifier qu'ils sont bien passés
print(f"Medicament: {medicament}")

#df_medicament=pd.read_csv("../../Analyse/df_medicament.csv")
#df=pd.read_csv("../../../df_patient.csv")

#filtered_df_medicament = df_medicament[df_medicament['DRUGNAME'] == medicament]

