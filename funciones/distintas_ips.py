import matplotlib.pyplot as plt
import os
from funciones.utilidades_archivo import get_filename_without_extension

def distintas_ips(ruta):
    archivo = open(ruta, "r")
    lineas = archivo.readlines()
    archivo.close()
    
    diccionario = {}
    
    for linea in lineas:
        ip = linea.split(' ')[0]
        if ip not in diccionario:
            diccionario[ip] = 1
        else:
            diccionario[ip] += 1
    
    ips_ordenadas = sorted(diccionario.items(), key=lambda x: x[1], reverse=True)[:20]
    ips = [item[0] for item in ips_ordenadas]
    numero = [item[1] for item in ips_ordenadas]
    
    plt.figure(figsize=(10, 5))
    plt.bar(ips, numero)
    plt.xlabel('IP')
    plt.ylabel('Número de accesos')
    plt.title('Número de accesos por IP')
    plt.xticks(rotation=45)
    plt.tight_layout()
    nombre_archivo = get_filename_without_extension(ruta)
    os.makedirs("graficas", exist_ok=True)
    virtual_host = nombre_archivo.split('-')[1]
    os.makedirs("graficas/" + virtual_host, exist_ok=True)
    plt.savefig(f"graficas/{virtual_host}/{nombre_archivo}_distintas_ips.png")
    plt.close()