import pandas as pd
from sklearn.metrics.pairwise import euclidean_distances
from sklearn.preprocessing import StandardScaler
import numpy as np
from difflib import get_close_matches
import pandas as pd
import re
from fuzzywuzzy import fuzz

# Charger les fichiers CSV
df_patients = pd.read_csv('final.csv')
df_reports = pd.read_csv('final.csv')
df_drugs = pd.read_csv('df_medicament.csv')
df_effects = pd.read_csv('df_effet_secondaire.csv')



# Exemple d'informations du patient (taille, âge, sexe)
patient_height = 160  # Exemple: 170 cm
patient_age = 20      # Exemple: 75 ans
patient_gender = 2    # Exemple: 1 (homme)
indication_keyword = "Dépression"  # Exemple: migraine

# Fonction pour trouver les indications similaires
def find_close_matches(keyword, all_indications, threshold=80):
    matches = [indication for indication in all_indications if fuzz.partial_ratio(keyword.lower(), indication.lower()) >= threshold]
    return list(set(matches))

# Étape 1: Trouver les indications les plus proches du mot-clé donné
all_indications = df_drugs['INDICATION_NAME_FR'].dropna().unique()
closest_matches = find_close_matches(indication_keyword, all_indications)

# Filtrer les médicaments pour les indications similaires trouvées
filtered_drugs = df_drugs[df_drugs['INDICATION_NAME_FR'].isin(closest_matches)]
filtered_medicament_ids = filtered_drugs['id_medicament'].unique()

# Vérifier les indications et médicaments filtrés
print(f"Indications les plus proches trouvées pour '{indication_keyword}': {closest_matches}")
print(f"Médicaments filtrés (id_medicament): {filtered_medicament_ids}")

# Étape 2: Filtrer les rapports de patients ayant les médicaments correspondants
filtered_reports = df_reports[df_reports['id_medicament'].isin(filtered_medicament_ids)]

# Vérifier les rapports de patients filtrés
print(f"Nombre de rapports de patients après filtrage: {filtered_reports.shape[0]}")
print(filtered_reports.head())

# Étape 3: Sélectionner les colonnes pertinentes et supprimer les lignes avec des NaN
features = ['HEIGHT', 'AGE_Y', 'GENDER_FR_encoded']
df_reports_cleaned = filtered_reports.dropna(subset=features).copy()

# Vérifier les données après suppression des NaN
print(f"Nombre de lignes après suppression des NaN: {df_reports_cleaned.shape[0]}")

# Convertir AGE_Y en float
df_reports_cleaned['AGE_Y'] = df_reports_cleaned['AGE_Y'].astype(float)

# Filtrer les patients similaires
def is_similar(row):
    return (abs(row['HEIGHT'] - patient_height) <= 10 and
            abs(row['AGE_Y'] - patient_age) <= 10 and
            row['GENDER_FR_encoded'] == patient_gender)

similar_patients = df_reports_cleaned[df_reports_cleaned.apply(is_similar, axis=1)]

# Vérifier les patients similaires trouvés
print(f"Nombre de patients similaires trouvés : {len(similar_patients)}")
print(similar_patients.head())

# Étape 4: Regrouper les rapports par REPORT_ID et collecter les id_effet_secondaire distincts
grouped_patients = similar_patients.groupby('REPORT_ID').agg({
    'id_medicament': 'first',
    'id_effet_secondaire': lambda x: list(x.unique())
}).reset_index()

# Afficher les résultats regroupés
print(grouped_patients.head())

# Enregistrer les résultats dans un fichier CSV
grouped_patients.to_csv('similar_patients_medications_and_side_effects.csv', index=False)



df_grouped = pd.read_csv('similar_patients_medications_and_side_effects.csv')

# Créer une liste pour stocker les nouvelles lignes du DataFrame
rows = []

# Parcourir les lignes du DataFrame groupé
for _, row in df_grouped.iterrows():
    report_id = row['REPORT_ID']
    id_medicament = row['id_medicament']
    id_effet_secondaires = eval(row['id_effet_secondaire'])  # Assure que c'est une liste de valeurs
    
    # Obtenir le nom du médicament et l'indication associée
    drug_info = df_drugs[df_drugs['id_medicament'] == id_medicament].iloc[0]
    drug_name = drug_info['DRUGNAME']
    indication_name = drug_info['INDICATION_NAME_FR']
    
    # Parcourir les effets secondaires
    for id_effet in id_effet_secondaires:
        effect_info = df_effects[df_effects['id_effet_secondaire'] == id_effet].iloc[0]
        effect_name = effect_info['PT_NAME_FR']
        soc_name = effect_info['SOC_NAME_FR']
        rows.append([report_id, id_medicament, drug_name, indication_name, id_effet, effect_name, soc_name])

# Créer le nouveau DataFrame
df_final = pd.DataFrame(rows, columns=['REPORT_ID', 'id_medicament', 'drug_name', 'indication_name', 'id_effet_secondaire', 'effect_name', 'soc_name'])

# Enregistrer les résultats dans un fichier CSV
df_final.to_csv('detailed_patient_effects.csv', index=False)