archivo = open("darkslateblue-access.log", "r")

lineas = archivo.readlines()

for linea in lineas:
    inicio = linea.find('[')
    final = linea.find(']')
    tiempo = linea[inicio+1:final]
    partes = tiempo.split(':')
    hora = partes[1]
    print(hora)

