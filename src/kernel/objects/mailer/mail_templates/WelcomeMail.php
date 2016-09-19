<?php

require_once "objects/mailer/MailTemplate.php";

class WelcomeMail extends MailTemplate
{

    // -- consts
    const NAME = 'welcome_mail';
    // -- attributes

    // -- functions

    public function __construct()
    {
        parent::__construct(
        //__________________________________________________________________________________________________________________________________________
        // -- subject ------------------------------------------------------------------------------------------------------------------------------
            "[Doletic] - Bienvenue !",
            //__________________________________________________________________________________________________________________________________________
            // -- plain text body ----------------------------------------------------------------------------------------------------------------------
            "Bonjour -{PRENOM}-, \n" .
            "\n" .
            "Doletic te souhaite la bienvenue !\n" .
            "\n" .
            "Tu peux te connecter à tout moment sur l'ERP à la l'adresse -{URL}- avec les identifiants suivants :\n" .
            "Nom d'utilisateur : -{LOGIN}-\n" .
            "Mot de passe : -{PASSWORD}-\n" .
            "Pense à changer ton mot de passe rapidement. Si tu l'oublies, tu peux le réinitialiser depuis la fenêtre de connexion de Doletic.\n" .
            "Si tu as besoin d'aide n'hésites pas à contacter le responsable DSI de ta Junior-Entreprise.\n" .
            "\n" .
            "Tu peux aussi utiliser le module support de Doletic si celui-ci est installé.\n" .
            "\n" .
            "À bientôt sur Doletic !\n",
            //__________________________________________________________________________________________________________________________________________
            // -- plain text body ----------------------------------------------------------------------------------------------------------------------
            "Bonjour <b>-{PRENOM}-</b>,<br>" .
            "<br>" .
            "Doletic te souhaite la bienvenue !<br>" .
            "<br>" .
            "Tu peux te connecter à tout moment sur l'ERP à la l'adresse <ahref=\"-{URL}-\">-{URL}-</a> avec les identifiants suivants :<br>" .
            "Nom d'utilisateur : -{LOGIN}- <br>" .
            "Mot de passe : -{PASSWORD}- <br>" .
            "Pense à changer ton mot de passe rapidement. Si tu l'oublies, tu peux le réinitialiser depuis la fenêtre de connexion de Doletic.<br>" .
            "Si tu as besoin d'aide n'hésite pas à contacter le responsable DSI de ta Junior-Entreprise.<br>" .
            "<br>" .
            "Tu peux aussi utiliser le <b>module support</b> de Doletic si celui-ci est installé.<br>" .
            "<br>" .
            "À bientôt sur Doletic !<br>",
            //__________________________________________________________________________________________________________________________________________
            // attachments -----------------------------------------------------------------------------------------------------------------------------
            array() // no attachment here
        );
    }
}