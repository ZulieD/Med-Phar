import sys
import json

nom_maladie= sys.argv[1]
sexe_patient = sys.argv[2]
date_naissance_patient = sys.argv[3]
poids_patient = sys.argv[4]
taille_patient = sys.argv[5]

# Mettre le model afin de determiner x medicament 
result = [1, 27, 84099]
output = json.dumps(result)

# Afficher la sortie JSON
print(output)
