<?php

require_once "objects/mailer/MailTemplate.php";

class WelcomeMail extends MailTemplate {

	// -- consts
	const NAME = 'welcome_mail';
	// -- attributes

	// -- functions

	public function __construct() {
		parent::__construct(
			//__________________________________________________________________________________________________________________________________________
			// -- subject ------------------------------------------------------------------------------------------------------------------------------
			"[Doletic] - Bienvenue !",
			//__________________________________________________________________________________________________________________________________________
			// -- plain text body ----------------------------------------------------------------------------------------------------------------------
			"Bonjour -{PRENOM}-, \n".
			"\n".
			"Doletic te souhaite la bienvenue !\n".
			"\n".
			"Tu peux te connecter à tout moment sur l'ERP à la l'adresse -{URL}- avec les identifiants qui t'ont été fournis lors de ton inscription.\n".
			"Si tu as besoin d'aide n'hésites pas à contacter le responsable DSI de ta Junior-Entreprise.\n".
			"\n".
			"Tu peux aussi utiliser le module support de Doletic si celui-ci est installé.\n".
			"\n".
			"À bientôt sur Doletic !\n",
			//__________________________________________________________________________________________________________________________________________
			// -- plain text body ---------------------------------------------------------------------------------------------------------------------- 			
			"Bonjour <b>-{PRENOM}-</b>,<br>".
			"<br>".
			"Doletic te souhaite la bienvenue !<br>".
			"<br>".
			"Tu peux te connecter à tout moment sur l'ERP à la l'adresse <a href=\"-{URL}-\">-{URL}-</a> avec les identifiants qui t'ont été fournis lors de ton inscription.<br>".
			"Si tu as besoin d'aide n'hésite pas à contacter le responsable DSI de ta Junior-Entreprise.<br>".
			"<br>".
			"Tu peux aussi utiliser le <b>module support</b> de Doletic si celui-ci est installé.<br>".
			"<br>".
			"À bientôt sur Doletic !<br>",
			//__________________________________________________________________________________________________________________________________________
			// attachments -----------------------------------------------------------------------------------------------------------------------------
			array() // no attachment here
			);
	}
}