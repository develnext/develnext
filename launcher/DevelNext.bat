@echo off

set ROOT=%~dp0

"%ROOT%/tools/jre/bin/java.exe" -Dfile.encoding=UTF8 -Xms50m -cp "%ROOT%/libs/*;" php.runtime.launcher.Launcher %*
