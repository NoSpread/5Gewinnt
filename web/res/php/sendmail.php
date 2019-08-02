<?php
sendEmail();
function sendEmail() {




        // Die Nachricht
$nachricht = "Zeile 1\r\nZeile 2\r\nZeile 3";

// Falls eine Zeile der Nachricht mehr als 70 Zeichen enthälten könnte,
// sollte wordwrap() benutzt werden
$nachricht = wordwrap($nachricht, 70, "\r\n");

// Verschicken
mail('simon-nomis123@web.de', 'test02', $nachricht);











    
    /*$recipient  = 'simon-nomis123@web.de';

    // Betreff
    $subject = 'Complete your registration at 5Gewinnt';

    // Nachricht
    $message = '
    <html>
    <head>
    <title>TEST</title>
    </head>
    <body>
    <p>Complete your registration</p>
    <table>
        <tr>
        <th>Person</th><th>Tag</th><th>Monat</th><th>Jahr</th>
        </tr>
        <tr>
        <td>Max</td><td>3.</td><td>August</td><td>1970</td>
        </tr>
        <tr>
        <td>Moritz</td><td>17.</td><td>August</td><td>1973</td>
        </tr>
    </table>
    </body>
    </html>
    ';

    // für HTML-E-Mails muss der 'Content-type'-Header gesetzt werden
    $header[] = 'MIME-Version: 1.0';
    $header[] = 'Content-type: text/html; charset=iso-8859-1';

    // zusätzliche Header
    //$header[] = 'To: Simone <simone@example.com>, Andreas <andreas@example.com>';
    //$header[] = 'From: Geburtstags-Erinnerungen <geburtstag@example.com>';
    //$header[] = 'Cc: geburtstagsarchiv@example.com';
    //$header[] = 'Bcc: geburtstagscheck@example.com';

    // verschicke die E-Mail
    mail($recipient, $subject, $message, implode("\r\n", $header));*/
}
?>