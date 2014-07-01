DevelNext - IDE for PHP
=======================

![DevelNext](src/main/resources/images/splash.png)


### How to build and start?

1. Get the sources from the JPHP repository to a directory, e.g: `path/to/jphp`.
2. Create a symlink in the directory of the DevelNext sources for the `jphp`.
3. (only for Windows) Download the archive https://bitbucket.org/dim-s/develnext/downloads/tools.7z and unpack it to the root of the sources.

and ...

Install Gradle and use the command `start` to start IDE:

    gradle start


Or you can create a portable version of DevelNext as following:

    gradle dist

The result of this command will be located at the `build/dist-{VERSION}` directory, there you will
find a few executable files: `DevelNext` (for Linux, MacOS), `DevelNext.bat` (for debugging under Windows) and
`DevelNext.exe` (for Windows). Use one of them to start IDE.

---

### Как собрать и запустить?

1. Возьмите все исходники из JPHP репозитария и разметите их в какой-нибудь папке, например `path/to/jphp`.
2. Создайте символическую ссылку для этой директории внутри исходников DevelNext под названием `jphp`.
3. (только для Windows) Скачайте архив https://bitbucket.org/dim-s/develnext/downloads/tools.7z и распакуйте его в корень исходников.

Установите систему Gradle и используйте его команду `start`, чтобы запустить среду:

    gradle start

Или вы можете создать портабельную версию DevelNext следующим образом:

    gradle dist

Результат выполнения этой команды будет находится в папке `build/dist-{VERSION}`, там будет находится
несколько исполняемых файлов: `DevelNext` (для Linux и MacOS), `DevelNext.bat` (для отладки под Windows) и
`DevelNext.exe` (для Windows). Используйте одну из них для запуска IDE.
