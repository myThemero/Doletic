<?php

require_once "objects/mailer/MailTemplate.php";

class ChangePasswordMail extends MailTemplate
{

    // -- consts
    const NAME = 'change_password_mails';
    // -- attributes

    // -- functions

    public function __construct()
    {
        parent::__construct(
        //__________________________________________________________________________________________________________________________________________
        // -- subject ------------------------------------------------------------------------------------------------------------------------------
            "[Doletic] - Confirmer le changement de mot de passe.",
            //__________________________________________________________________________________________________________________________________________
            // -- plain text body ----------------------------------------------------------------------------------------------------------------------
            "Bonjour -{PRENOM}-, \n" .
            "\n" .
            "Ton mot de passe a été modifié avec succès. Si tu n'es pas l'auteur de la demande, contacte immédiatement le responsable DSI de la J.E. !\n" .
            "\n" .
            "À bientôt sur Doletic !\n",
            //__________________________________________________________________________________________________________________________________________
            // -- plain text body ----------------------------------------------------------------------------------------------------------------------
            "Bonjour <b>-{PRENOM}-</b>,<br>" .
            "<br>" .
            "Ton mot de passe a été modifié avec succès. Si tu n'es pas l'auteur de la demande, contacte immédiatement le responsable DSI de la J.E. !<br>" .
            "<br>" .
            "À bientôt sur Doletic !<br>",
            //__________________________________________________________________________________________________________________________________________
            // attachments -----------------------------------------------------------------------------------------------------------------------------
            array() // no attachment here
        );
    }
}