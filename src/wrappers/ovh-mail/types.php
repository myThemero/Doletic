<?php

/**
 *    Crée un dictionnaire contenant la configuration d'un filtre lors de sa création
 */
function make_create_filter_config($action, $actionParam, $active, $header, $name, $operand, $priority, $value)
{
    return array(
        "action" => $action,                    // enum qstring, valeur dans ['accept','delete','account','redirect']
        "actionParam" => $actionParam,            // string, optionnel, action à réaliser
        "active" => $active,                    // bool, filtre actif ou non
        "header" => $header,                    // string, chaine à filtrer
        "name" => $name,                    // string, nom du filtre
        "operand" => $operand,                // enum string, valeur dans ['checkspf','contains','noContains']
        "priority" => $priority,                // long
        "value" => $value                    // string, paramètre de règle pour le filtre
    );
}

/**
 *    Crée un dictionnaire contenant la configuration d'une règle lors de sa création
 */
function make_create_rule_config($header, $operand, $value)
{
    return array(
        "header" => $header,                    // string, chaine à filtrer
        "operand" => $operand,                // enum string, valeur dans ['checkspf','contains','noContains']
        "value" => $value                    // string, paramètre de règle pour le filtre
    );
}

/**
 *    Crée un dictionnaire contenant la configuration des infos de contact
 */
function make_change_contact_config($contactAdmin, $contactBilling, $contactTech)
{
    return array(
        "contactAdmin" => $contactAdmin,        // string, email de contact de l'admin
        "contactBilling" => $contactBilling,        // string, email de contact pour les facturations
        "contactTech" => $contactTech            // string, email de contact pour la technique
    );
}

/**
 *    Crée un dictionnaire contenant la configuration d'un filtre DNS MX
 */
function make_change_mxfilter_config($customTarget, $mxFilter, $subDomain)
{
    return array(
        "customTarget" => $customTarget,    // string, serveur cible
        "mxFilter" => $mxFilter,        // enum string, valeur dans ['CUSTOM','FULL_FILTERING','NO_FILTERING','REDIRECT','SIMPLE_FILTERING']
        "subDomain" => $subDomain        // string, sous domaine concerné
    );
}

/**
 *    Crée un dictionnaire contenant la configuration des options d'une mailing list
 */
function make_maillist_options_config($moderatorMessage, $subscribeByModerator, $userPostOnly)
{
    return array(
        "moderatorMessage" => $moderatorMessage,        // bool, verif message par le moderateur
        "subscribeByModerator" => $subscribeByModerator,    // bool, verif inscription par le moderateur
        "userPostOnly" => $userPostOnly            // bool, seuls les utilisateurs peuvent utiliser
    );
}

/**
 *    Crée un dictionnaire contenant la configuration d'une mailing list
 */
function make_create_maillist_config($language, $name, $moderatorMessage, $subscribeByModerator, $userPostOnly, $ownerEmail, $replyTo)
{
    return array(
        "language" => $language,                            // enum string, valeur dans ['de','en','es','fr','it','nl','pl','pt']
        "name" => $name,                                // string, nom de la mailing list
        "options" => make_maillist_options_config($moderatorMessage, $subscribeByModerator, $userPostOnly),
        "ownerEmail" => $ownerEmail,                            // string, email du propriétaire de la mailing list
        "replyTo" => $replyTo                                // string, email de réponse aux mails envoyés grâce à la mailing list
    );
}

/**
 *    Crée un dictionnaire contenant la configuration d'une redirection
 */
function make_create_redirection_config($from, $localCopy, $to)
{
    return array(
        "from" => $from,        // string, nom de la redirection
        "localCopy" => $localCopy,    // bool, garder une copie locale ?
        "to" => $to            // string, compte cible
    );
}

/**
 *    Crée un dictionnaire contenant la configuration d'un répondeur
 */
function make_create_responder_config($content, $copy, $copyTo, $from, $to)
{
    return array(
        "content" => $content,    // string, contenu du message de reponse automatique
        "copy" => $copy,        // bool, envoyer une copie à l'adresse copyTo ?
        "copyTo" => $copyTo,    // string, adresse de reception de la copie
        "from" => $from,        // datetime, date de debut
        "to" => $to            // datetime, date de fin
    );
}

/**
 *    Crée un dictionnaire pour la mise à jour des propriétés d'un compte
 */
function make_update_account($description, $size)
{
    return array(
        "Account" => array(
            "description" => $description,
            "size" => $size
        )
    );
}

/**
 *    Crée un dictionnaire pour la mise à jour des propriétés d'une mailing list
 */
function make_update_maillist($language, $ownerEmail, $replyTo)
{
    return array(
        "MailingList" => array(
            "language" => $language,
            "ownerEmail" => $ownerEmail,
            "replyTo" => $replyTo
        )
    );
}

/**
 *    Crée un dictionnaire pour la mise à jour des propriétés d'un répondeur
 */
function make_update_responder($content, $from, $to)
{
    return array(
        "Responder" => array(
            "content" => $content,
            "from" => $from,
            "to" => $to
        )
    );
}

/**
 *    Crée un dictionnaire pour la mise à jour des propriétés du service de mails
 */
function make_update_service_info($automatic, $deleteAtExpiration, $forced, $period)
{
    return array(
        "Service" => array(
            "renew" => array(
                "automatic" => $automatic,
                "deleteAtExpiration" => $deleteAtExpiration,
                "forced" => $forced,
                "period" => $period
            )
        )
    );
}