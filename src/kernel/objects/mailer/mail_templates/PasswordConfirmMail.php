<?php

require_once "objects/mailer/MailTemplate.php";

class PasswordConfirmMail extends MailTemplate
{

    // -- consts
    const NAME = 'password_confirm_mail';
    // -- attributes

    // -- functions

    public function __construct()
    {
        parent::__construct(
        //__________________________________________________________________________________________________________________________________________
        // -- subject ------------------------------------------------------------------------------------------------------------------------------
            "[Doletic] - Confirmer la réinitialisation du mot de passe.",
            //__________________________________________________________________________________________________________________________________________
            // -- plain text body ----------------------------------------------------------------------------------------------------------------------
            "Bonjour -{PRENOM}-, \n" .
            "\n" .
            "Tu as demandé une réinitialisation de ton mot de passe. Si ce n'est pas le cas, ignore ce mail et ton mot de passe ne sera pas changé.\n" .
            "\n" .
            "Pour valider la réinitialisation, merci de cliquer sur ce lien : -{URL}-.\n" .
            "\n" .
            "À bientôt sur Doletic !\n",
            //__________________________________________________________________________________________________________________________________________
            // -- plain text body ----------------------------------------------------------------------------------------------------------------------
            "Bonjour <b>-{PRENOM}-</b>,<br>" .
            "<br>" .
            "Tu as demandé une réinitialisation de ton mot de passe. Si ce n'est pas le cas, ignore ce mail et ton mot de passe ne sera pas changé.<br>" .
            "<br>" .
            "Pour valider la réinitialisation, merci de cliquer sur ce lien : <a href=\"-{URL}-\">-{URL}-</a>.<br>" .
            "<br>" .
            "À bientôt sur Doletic !<br>",
            //__________________________________________________________________________________________________________________________________________
            // attachments -----------------------------------------------------------------------------------------------------------------------------
            array() // no attachment here
        );
    }
}