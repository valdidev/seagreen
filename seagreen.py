archivo = open("darkslateblue-access.log", "r")

diccionario = {}

lineas = archivo.readlines()
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
print(horas)
print(numero)

