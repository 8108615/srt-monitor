Set WshShell = CreateObject("WScript.Shell")
Do
    ' Ejecuta el comando de artisan de forma totalmente oculta (el 0 indica ventana invisible)
    WshShell.Run "php artisan streams:check-status", 0, True
    ' Espera 10 segundos antes de volver a verificar el estado de los streams
    Wscript.Sleep 10000
Loop
