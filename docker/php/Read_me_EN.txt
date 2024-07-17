
#######################################################

There are two ways to set up the Proethos container:

First -----------------------------------------------
by running the commands from the file:
"Configuração manual container Proethos.txt"


---------------------------------------------------------


Second -----------------------------------------------

by building the Dockerfile,
which is in this directory, for it to work,
it is necessary that all files in this directory
are also on your machine.

In the terminal, navigate to the docker folder and run the following command
to create the container image:

 docker build -t imagem_proethos .

After this, run the command:

 docker run --name ubuntuProethos -itd -p 8090:80 imagem_proethos

 docker exec -it ubuntuProethos bash

Then inside the container, run the following commands:

 perl -pi -e 's/
/
/g' ./configurar.sh

./configurar.sh

After that, just access the container through the browser: localhost:8090

------------------------------------------

 Language: English - en_US
 19/08/2023 - Gabriel Souza

#######################################################
