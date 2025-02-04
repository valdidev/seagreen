import matplotlib.pyplot as plt
import os
from funciones.utilidades_archivo import get_filename_without_extension

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
    nombre_archivo = get_filename_without_extension(ruta)
    os.makedirs("graficas", exist_ok=True)
    virtual_host = nombre_archivo.split('-')[0]
    os.makedirs("graficas/" + virtual_host, exist_ok=True)
    plt.savefig(f"graficas/{virtual_host}/{nombre_archivo}_registros_por_hora.png")
    plt.close()