<?php

require_once "../wrappers/utils/ovh-php/src/Exceptions/ApiException.php";
require_once "../wrappers/utils/ovh-php/src/Exceptions/InvalidParameterException.php";
require_once "../wrappers/utils/ovh-php/src/Exceptions/NotLoggedException.php";
require_once "../wrappers/utils/ovh-php/src/Api.php";
use \Ovh\Api;

class OVHAPIConnector
{
    // -- consts

    // -- attributes
    private $api;

    // -- functions

    public function __construct($wrapperConfig, $httpClient = null)
    {
        // retrieve api settings
        $endpoint = $wrapperConfig->GetEndpoint();
        $application_key = $wrapperConfig->GetApplicationKey();
        $application_secret = $wrapperConfig->GetApplicationSecret();
        $consumer_key = $wrapperConfig->GetConsumerKey();
        var_dump($application_key);
        var_dump($application_secret);
        var_dump($endpoint);
        var_dump($consumer_key);
        // check settings
        if (isset($application_key) && strlen($application_key) > 0 &&
            isset($application_secret) && strlen($application_secret) > 0 &&
            isset($endpoint) && strlen($endpoint) > 0 &&
            isset($consumer_key) && strlen($consumer_key) > 0
        ) {
            // create api object
            $this->api = new Api(
                $application_key,
                $application_secret,
                $endpoint,
                $consumer_key,
                $httpClient
            );
        } else {
            $this->api = null;
        }
    }

    public function GetAPI()
    {
        return $this->api;
    }

# PROTECTED & PRIVATE ###########################################

// nothing here for now...

}