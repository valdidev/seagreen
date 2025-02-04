import os
from funciones.registros_por_hora import registros_por_hora

    
# carpeta = "C:/xampp/htdocs/seagreen"
carpeta = "/var/log/apache2"

for root, dirs, files in os.walk(carpeta):
    for file in files:
        if file.endswith(".log"):
            registros_por_hora(os.path.join(root, file))
