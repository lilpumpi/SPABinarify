SPA Binarify
Creado por Samuel Pampillón Roa (21115573D)

Este proyecto se ha realizado en un entorno Windows y se ha utilizado XAMPP para alojar la web, a continuacion, se detallan los pasos 
que se deben seguir para el correcto funcionamiento del proyecto, en este caso desde XAMPP.

1. Para poder ejecutar correctamente el proyecto usando XAMPP, es necesario cargar la carpeta en el directorio xampp/htdocs
2. Dentro del pryecto, en /sql/setup.sql se encuentra el codigo para crear e iniciar la base de datos, en este caso tanto MVCBinarify y SPABinarify usan la misma BD. Si queremos hacerlo desde XAMPP deberemos realizar los siguientes pasos:

	2.1. Navegamos, dentor de la terminal XAMPP, hasta la carpeta que contiene el archivo sql, en este caso "htdocs/SPABinarify/sql"
	2.2. Entrar en la terminal SQL usando los comandos mysql -u root -p, nos pedirá la contraseña que será vacia
	2.3. Una vez en la terminal SQL ejecutamos el comando source setup.sql, que ejecutará el codigo del archivo y creará la base de datos "binarify"
	
3. Una vez creada la base de datos debemos usarla con el comando "use binairfy;"

4. Una vez configurada la base de datos ya podemos ejecutar el codigo en un navegador
