# Newsletter im Mailversand

Base und Administration verwenden jeweils den Branch `release/mailversand` und dieselbe Anwendungsdatenbank. Beim ersten Release zuerst die Base ausrollen und dort `php artisan migrate --force` ausführen. Danach die Administration ausrollen. Das Redesign wird separat veröffentlicht.

Die Base erzeugt die Mailbilder, verwaltet die persönlichen Links und verarbeitet fällige Ausgaben. Ihr bestehender minütlicher Laravel-Scheduler startet `newsletter:dispatch-due` und den Datenbank-Worker für `kundenmail`. Die Administration braucht dafür keinen zweiten Worker.

`APP_URL` auf der Base muss auf ihre öffentliche Adresse zeigen. Die Administration nutzt ihre bestehende Verbindung zur Base. Bei einer getrennten Testinstallation lassen sich die Basisadresse mit `NEWSLETTER_BASE_URL` im Admin und der gemeinsame API-Schlüssel mit `NEWSLETTER_API_KEY` in beiden Anwendungen festlegen. Für lokale Versandproben eignet sich `MAIL_MAILER=array` in der Base.

Beitragsbilder und die daraus erzeugten Mailbilder werden über den öffentlichen Storage-Link der Base ausgeliefert. `storage/app/public/newsletter/bilder` gehört in das persistente Datenverzeichnis und die Sicherung. Bereits verschickte Ausgaben verlinken auf diese Dateien.
