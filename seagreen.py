import matplotlib.pyplot as plt
import os
from urllib.parse import urlparse

def get_filename_without_extension(file_url, extension):
    parsed_url = urlparse(file_url)
    file_path = parsed_url.path
    base_name = os.path.basename(file_path)
    file_name, _ = os.path.splitext(base_name)
    return file_name + extension

def registros_por_hora(ruta):
    archivo = open(ruta, "r")
    diccionario = {}
    lineas = archivo.readlines()
    archivo.close()
    for linea in lineas:
        inicio = linea.find('[')
        final = linea.find(']')
        tiempo = linea[inicio+1:final]
        partes = tiempo.split(':')
        hora = partes[1]
        if hora not in diccionario:
            diccionario[hora] = 1
        else:
            diccionario[hora] += 1

    horas = sorted(diccionario.keys())
    numero = [diccionario[hora] for hora in horas]

    # gráfica
    plt.figure(figsize=(10, 5))
    plt.bar(horas, numero)
    plt.xlabel('Hora')
    plt.ylabel('Número de accesos')
    plt.title('Número de accesos por hora')
    plt.xticks(rotation=45)
    plt.tight_layout()
    
    nombre_archivo = get_filename_without_extension(ruta, '.png')
    os.makedirs("graficas", exist_ok=True)
    plt.savefig("graficas/" + nombre_archivo)
    
carpeta = "C:/xampp/htdocs/seagreen"

for root, dirs, files in os.walk(carpeta):
    for file in files:
        if file.endswith(".log"):
            registros_por_hora(file)
