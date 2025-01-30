import matplotlib.pyplot as plt

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
    plt.savefig("accesos_por_hora.png")

registros_por_hora("darkslateblue-access.log")