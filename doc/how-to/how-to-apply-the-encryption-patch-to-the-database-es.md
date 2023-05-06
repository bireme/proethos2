[English](how-to-apply-the-encryption-patch-to-the-database.md)

---

Cómo aplicar el patch de encriptación a la base de datos (obligatorias si ProEthos2 >= 1.6.0)
=============================================================================================

En caso de actualizar una instalación anterior, es necesario aplicar el patch de encriptación a la base de datos ejecutando el comando `proethos2:sanitize-sensitive-data`.

Uso:

```
$ php app/console proethos2:sanitize-sensitive-data [-r, --rollback]
```

Ejemplo:

```
$ php app/console proethos2:sanitize-sensitive-data
```

Para restaurar la base de datos a un estado previamente definido, simplemente ejecute el comando con la opción `-r` or `--rollback`:

```
$ php app/console proethos2:sanitize-sensitive-data --rollback

or

$ php app/console proethos2:sanitize-sensitive-data -r
```

__NOTES:__
- Es altamente recomendable realizar una copia de seguridad de la base de datos antes de ejecutar estos comandos.
- Antes de ejecutar estos comandos, también es necesario generar y configurar las claves de cifrado (haga clic [aquí](how-to-install-proethos2-in-ubuntu-es.md#claves-de-cifrado-obligatorias-si-proethos2--160) para saber cómo aplicar las claves de cifrado)