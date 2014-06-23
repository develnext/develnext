@echo off

set ROOT=%~dp0

java -Dfile.encoding=UTF8 -Xms20m -cp "%ROOT%/libs/*;" php.runtime.launcher.Launcher %*
