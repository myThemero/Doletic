<?php

require_once "interfaces/functions.php";
require_once "interfaces/AbstractOVHWrapper.php";
require_once "../wrappers/ovh-mail/types.php";

/**
 *	Interface principale du wrapper de couplage avec OVH concernant les mails
 */
class OVHMailWrapper extends AbstractOVHWrapper {

	// -- consts
	// --- wrapper metadata related consts
	const NAME = "ovh-mail";
	const VERSION = "1.0dev";
	const AUTHORS = array(
		"Paul Dautry"
		);
	// --- wrapper functions related consts
	// ---- GET --------------------------------
	const FUNC_LIST_DOMAINS 				= "_func_available_services";			// List available services
	const FUNC_DOMAIN_PROPERTIES 			= "_func_domain_properties";				// Get this object properties
	const FUNC_LIST_ACCOUNTS 				= "_func_list_accounts";					// Get accounts
	const FUNC_ACCOUNT_PROPERTIES 			= "_func_account_properties";			// Get this object properties
	const FUNC_LIST_FILTERS 				= "_func_list_filters";					// Get filters
	const FUNC_FILTER_PROPERTIES 			= "_func_filter_properties";				// Get this object properties
	const FUNC_LIST_RULES 					= "_func_list_rules";					// Get rules
	const FUNC_RULE_PROPERTIES 				= "_func_rule_properties";				// Get this object properties
	const FUNC_ACCOUNT_USAGE 				= "_func_account_usage";					// usage of account
	const FUNC_LIST_ACL 					= "_func_list_acl";						// Get ACL on your domain
	const FUNC_ACL_PROPERTIES 				= "_func_acl_properties";				// Get this object properties
	const FUNC_LIST_MXFILTERS 				= "_func_list_mxfilters";				// Domain MX filter
	const FUNC_LIST_MXRECORDS 				= "_func_list_mxrecords";				// Domain MX records
	const FUNC_LIST_MAILLISTS 				= "_func_list_maillists";				// Get mailing lists
	const FUNC_MAILLIST_PROPERTIES 			= "_func_maillist_properties";			// Get this object properties
	const FUNC_LIST_MODERATORS 				= "_func_list_moderators";				// List of moderators
	const FUNC_MODERATOR_PROPERTIES			= "_func_moderator_properties";			// Get this object properties
	const FUNC_LIST_SUBSCRIBERS 			= "_func_list_subscribers";				// List of subscribers
	const FUNC_SUBSCRIBER_PROPERTIES 		= "_func_subscriber_properties";			// Get this object properties
	const FUNC_LIST_QUOTAS 					= "_func_list_quotas";					// List all quotas for this domain
	const FUNC_LIST_REDIRECTIONS 			= "_func_list_redirections";				// Get redirections
	const FUNC_REDIRECTION_PROPERTIES 		= "_func_redirection_properties";		// Get this object properties
	const FUNC_LIST_RESPONDERS 				= "_func_list_responders";				// Get responders
	const FUNC_RESPONDER_PROPERTIES 		= "_func_responder_properties";			// Get this object properties
	const FUNC_SERVICES_INFO 				= "_func_services_info";					// Get this object properties
	const FUNC_SUMMARY 						= "_func_summary";						// Summary for this domain
	const FUNC_LIST_ACCOUNT_TASKS			= "_func_list_account_tasks";			// Get account tasks
	const FUNC_ACCOUNT_TASK_PROPERTIES		= "_func_account_task_properties";		// Get this object properties
	const FUNC_LIST_FILTER_TASKS 			= "_func_list_filter_tasks";				// Get filter tasks
	const FUNC_FILTER_TASK_PROPERTIES 		= "_func_filter_task_properties";		// Get this object properties
	const FUNC_LIST_MAILLIST_TASKS 			= "_func_list_maillist_tasks";			// Get Mailing List tasks
	const FUNC_MAILLIST_TASK_PROPERTIES 	= "_func_maillist_task_properties";		// Get this object properties
	const FUNC_LIST_REDIRECTION_TASKS 		= "_func_list_redirection_tasks";		// Get redirection tasks
	const FUNC_REDIRECTION_TASK_PROPERTIES 	= "_func_redirection_task_properties";	// Get this object properties
	const FUNC_LIST_RESPONDER_TASKS 		= "_func_list_responder_tasks";			// Get responder tasks
	const FUNC_RESPONDER_TASK_PROPERTIES 	= "_func_responder_task_properties";		// Get this object properties
	// ---- PUT --------------------------------
	const FUNC_UPDATE_ACCOUNT 				= "_func_update_account";				// Alter this object properties
	const FUNC_UPDATE_MAILLIST 				= "_func_update_maillist";				// Alter this object properties
	const FUNC_UPDATE_RESPONDER 			= "_func_update_responder";				// Alter this object properties
	const FUNC_UPDATE_SERVICES_INFO 		= "_func_update_services_info";			// Alter this object properties
	// ---- POST ------------------------------
	const FUNC_CREATE_MAILBOX 				= "_func_create_mailbox"; 					// Create new mailbox in server
	const FUNC_CHANGE_MAILBOX_PWD 			= "_func_change_mailbox_pwd"; 				// Change mailbox password (length in [9;30], trimmed, no accent)
	const FUNC_CREATE_FILTER 				= "_func_create_filter"; 					// Create new filter for account
	const FUNC_CHANGE_FILTER_ACTIVITY 		= "_func_change_filter_activity"; 			// Change filter activity
	const FUNC_CHANGE_FILTER_PRIORITY 		= "_func_change_filter_priority"; 			// Change filter priority
	const FUNC_CREATE_RULE 					= "_func_create_rule"; 						// Create a new rule for filter
	const FUNC_UPDATE_ACCOUNT_USAGE 		= "_func_update_account_usage"; 			// Update usage of account
	const FUNC_CREATE_ACL 					= "_func_create_acl"; 						// Create a new ACL
	const FUNC_CHANGE_CONTACT 				= "_func_change_contact"; 					// Launch a contact change procedure
	const FUNC_CHANGE_MXFILTER 				= "_func_change_mxfilter"; 					// Change MX filter, so change MX DNS records
	const FUNC_CREATE_MAILLIST 				= "_func_create_maillist"; 					// Create a new mailing list
	const FUNC_CHANGE_MAILLIST_OPTS 		= "_func_change_maillist_opts"; 			// Change mailing list options
	const FUNC_ADD_MODERATOR 				= "_func_add_moderator"; 					// Add moderator to mailing list
	const FUNC_SEND_MAILLIST_BY_MAIL 		= "_func_send_maillist_by_mail"; 			// Send moderators list and subscribers list of this mailing list by email
	const FUNC_ADD_SUBSCRIBER 				= "_func_add_subscriber"; 					// Add subscriber to mailing list
	const FUNC_CREATE_DOMAIN_DELEG 			= "_func_create_domain_deleg"; 				// Create delegation of domain with same nic than V3
	const FUNC_CREATE_REDIRECTION 			= "_func_create_redirection"; 				// Create new redirection in server
	const FUNC_CHANGE_REDIRECTION 			= "_func_change_redirection"; 				// Change redirection
	const FUNC_CREATE_RESPONDER 			= "_func_create_responder"; 				// Create new responder in server
	// ---- DELETE ----------------------------
	const FUNC_REMOVE_MAILBOX 				= "_func_remove_mailbox"; 		// Delete an existing mailbox in server
	const FUNC_REMOVE_FILTER 				= "_func_remove_filter"; 		// Delete an existing filter
	const FUNC_REMOVE_RULE 					= "_func_remove_rule"; 			// Delete an existing rule
	const FUNC_REMOVE_ACL 					= "_func_remove_acl"; 			// Delete ACL
	const FUNC_REMOVE_MAILLIST 				= "_func_remove_maillist"; 		// Delete existing Mailing list
	const FUNC_REMOVE_MODERATOR 			= "_func_remove_moderator"; 	// Delete existing moderator
	const FUNC_REMOVE_SUBSCRIBER 			= "_func_remove_subscriber"; 	// Delete existing subscriber
	const FUNC_REMOVE_REDIRECTION 			= "_func_remove_redirection"; 	// Delete an existing redirection in server
	const FUNC_REMOVE_RESPONDER 			= "_func_remove_responder"; 	// Delete an existing responder in server
	// --- commands substitution arguments ----
	const ARG_DOMAIN 		= "{domain}";
	const ARG_NAME  		= "{name}";
	const ARG_ID 			= "{id}";
	const ARG_EMAIL			= "{email}";
	const ARG_ACCOUNT       = "{account}";
	const ARG_ACCOUNT_ID    = "{accountId}";
	const ARG_ACCOUNT_NAME 	= "{accountName}"; 
	// --- const params -----------------------
	const P_DESCRIPTION     	 	= "description";
	const P_PWD					 	= "password";
	const P_SIZE 				 	= "size"; //(in bytes/en octets)
	const P_ACTIVITY			 	= "activity";
	const P_PRIORITY             	= "priority";
	const P_TO 						= "to";
	const P_CREATE_FILTER_CONFIG 	= "create_filter_config";
	const P_CREATE_RULE_CONFIG 		= "create_rule_config";
	const P_CONTACT_CONFIG		 	= "contact_config";
	const P_MXFILTER_CONFIG			= "mxfilter_config";
	const P_CREATE_MAILLIST_CONFIG  = "maillist_config";
	const P_MAILLIST_OPTIONS		= "maillist_options";
	const P_REDIRECTION_CONFIG		= "redirection_config";
	const P_RESPONDER_CONFIG		= "responder_config";
	const P_UPDATE_ACCOUNT			= "update_account_config";
	const P_UPDATE_MAILLIST			= "update_maillist_config";
	const P_UPDATE_RESPONDER		= "update_responder_config";
	const P_UPDATE_SERVICE_INFO		= "update_service_info_config";
	// --- commands dictionaries sorted by GET, PUT, POST, DELETE
	const GET_COMMANDS = array(
		OVHMailWrapper::FUNC_LIST_DOMAINS					=> "/email/domain", 
		OVHMailWrapper::FUNC_DOMAIN_PROPERTIES				=> "/email/domain/{domain}",
		OVHMailWrapper::FUNC_LIST_ACCOUNTS					=> "/email/domain/{domain}/account",
		OVHMailWrapper::FUNC_ACCOUNT_PROPERTIES				=> "/email/domain/{domain}/account/{accountName}",
		OVHMailWrapper::FUNC_LIST_FILTERS					=> "/email/domain/{domain}/account/{accountName}/filter",
		OVHMailWrapper::FUNC_FILTER_PROPERTIES				=> "/email/domain/{domain}/account/{accountName}/filter/{name}",
		OVHMailWrapper::FUNC_LIST_RULES						=> "/email/domain/{domain}/account/{accountName}/filter/{name}/rule",
		OVHMailWrapper::FUNC_RULE_PROPERTIES				=> "/email/domain/{domain}/account/{accountName}/filter/{name}/rule/{id}",
		OVHMailWrapper::FUNC_ACCOUNT_USAGE					=> "/email/domain/{domain}/account/{accountName}/usage",
		OVHMailWrapper::FUNC_LIST_ACL						=> "/email/domain/{domain}/acl",
		OVHMailWrapper::FUNC_ACL_PROPERTIES					=> "/email/domain/{domain}/acl/{accountId}",
		OVHMailWrapper::FUNC_LIST_MXFILTERS					=> "/email/domain/{domain}/dnsMXFilter",
		OVHMailWrapper::FUNC_LIST_MXRECORDS					=> "/email/domain/{domain}/dnsMXRecords",
		OVHMailWrapper::FUNC_LIST_MAILLISTS					=> "/email/domain/{domain}/mailingList",
		OVHMailWrapper::FUNC_MAILLIST_PROPERTIES			=> "/email/domain/{domain}/mailingList/{name}",
		OVHMailWrapper::FUNC_LIST_MODERATORS				=> "/email/domain/{domain}/mailingList/{name}/moderator",
		OVHMailWrapper::FUNC_MODERATOR_PROPERTIES			=> "/email/domain/{domain}/mailingList/{name}/moderator/{email}",
		OVHMailWrapper::FUNC_LIST_SUBSCRIBERS				=> "/email/domain/{domain}/mailingList/{name}/subscriber",
		OVHMailWrapper::FUNC_SUBSCRIBER_PROPERTIES			=> "/email/domain/{domain}/mailingList/{name}/subscriber/{email}",
		OVHMailWrapper::FUNC_LIST_QUOTAS					=> "/email/domain/{domain}/quota",
		OVHMailWrapper::FUNC_LIST_REDIRECTIONS				=> "/email/domain/{domain}/redirection",
		OVHMailWrapper::FUNC_REDIRECTION_PROPERTIES			=> "/email/domain/{domain}/redirection/{id}",
		OVHMailWrapper::FUNC_LIST_RESPONDERS				=> "/email/domain/{domain}/responder",
		OVHMailWrapper::FUNC_RESPONDER_PROPERTIES			=> "/email/domain/{domain}/responder/{account}",
		OVHMailWrapper::FUNC_SERVICES_INFO					=> "/email/domain/{domain}/serviceInfos",
		OVHMailWrapper::FUNC_SUMMARY						=> "/email/domain/{domain}/summary",
		OVHMailWrapper::FUNC_LIST_ACCOUNT_TASKS				=> "/email/domain/{domain}/task/account",
		OVHMailWrapper::FUNC_ACCOUNT_TASK_PROPERTIES		=> "/email/domain/{domain}/task/account/{id}",
		OVHMailWrapper::FUNC_LIST_FILTER_TASKS				=> "/email/domain/{domain}/task/filter",
		OVHMailWrapper::FUNC_FILTER_TASK_PROPERTIES			=> "/email/domain/{domain}/task/filter/{id}",
		OVHMailWrapper::FUNC_LIST_MAILLIST_TASKS			=> "/email/domain/{domain}/task/mailinglist",
		OVHMailWrapper::FUNC_MAILLIST_TASK_PROPERTIES		=> "/email/domain/{domain}/task/mailinglist/{id}",
		OVHMailWrapper::FUNC_LIST_REDIRECTION_TASKS			=> "/email/domain/{domain}/task/redirection",
		OVHMailWrapper::FUNC_REDIRECTION_TASK_PROPERTIES	=> "/email/domain/{domain}/task/redirection/{id}",
		OVHMailWrapper::FUNC_LIST_RESPONDER_TASKS			=> "/email/domain/{domain}/task/responder",
		OVHMailWrapper::FUNC_RESPONDER_TASK_PROPERTIES		=> "/email/domain/{domain}/task/responder/{id}"
		);
	const PUT_COMMANDS = array(
		OVHMailWrapper::FUNC_UPDATE_ACCOUNT			=> "/email/domain/{domain}/account/{accountName}",
		OVHMailWrapper::FUNC_UPDATE_MAILLIST		=> "/email/domain/{domain}/mailingList/{name}",
		OVHMailWrapper::FUNC_UPDATE_RESPONDER		=> "/email/domain/{domain}/responder/{account}",
		OVHMailWrapper::FUNC_UPDATE_SERVICES_INFO	=> "/email/domain/{domain}/serviceInfos"
		);
	const POST_COMMANDS = array(
		OVHMailWrapper::FUNC_CREATE_MAILBOX			=> "/email/domain/{domain}/account",					 
		// params(domain, accountName, description, password, size(in bytes))
		OVHMailWrapper::FUNC_CHANGE_MAILBOX_PWD		=> "/email/domain/{domain}/account/{accountName}/changePassword",
		// params(domain, accountName, password)
		OVHMailWrapper::FUNC_CREATE_FILTER			=> "/email/domain/{domain}/account/{accountName}/filter",
		// params(domain, accountName, action, actionParam, active, header, name, operand, priority, value)
		OVHMailWrapper::FUNC_CHANGE_FILTER_ACTIVITY	=> "/email/domain/{domain}/account/{accountName}/filter/{name}/changeActivity",
		// params(domain, name, accountName, activity)
		OVHMailWrapper::FUNC_CHANGE_FILTER_PRIORITY	=> "/email/domain/{domain}/account/{accountName}/filter/{name}/changePriority",
		// params(domain, name, accountName, priority)
		OVHMailWrapper::FUNC_CREATE_RULE			=> "/email/domain/{domain}/account/{accountName}/filter/{name}/rule",
		// params(domain, name, accountName, header, operand, value)
		OVHMailWrapper::FUNC_UPDATE_ACCOUNT_USAGE	=> "/email/domain/{domain}/account/{accountName}/updateUsage",
		// params(domain, accountName)
		OVHMailWrapper::FUNC_CREATE_ACL				=> "/email/domain/{domain}/acl",						
		// params(domain, accountId) 
		OVHMailWrapper::FUNC_CHANGE_CONTACT			=> "/email/domain/{domain}/changeContact",
		// params(domain, contactAdmin, contactBilling, contactTech)
		OVHMailWrapper::FUNC_CHANGE_MXFILTER		=> "/email/domain/{domain}/changeDnsMXFilter",
		// params(domain, customTarget, mxFilter, subDomain)
		OVHMailWrapper::FUNC_CREATE_MAILLIST		=> "/email/domain/{domain}/mailingList",
		// params(domain, language, name, options[moderatorMessage(bool),subscribeByModerator(bool),usersPostOnly(bool)],ownerEmail,replyTo)
		OVHMailWrapper::FUNC_CHANGE_MAILLIST_OPTS	=> "/email/domain/{domain}/mailingList/{name}/changeOptions",
		// params(domain, name, options[moderatorMessage(bool),subscribeByModerator(bool),usersPostOnly(bool)])
		OVHMailWrapper::FUNC_ADD_MODERATOR			=> "/email/domain/{domain}/mailingList/{name}/moderator",
		// params(domain, name, email)
		OVHMailWrapper::FUNC_SEND_MAILLIST_BY_MAIL	=> "/email/domain/{domain}/mailingList/{name}/sendListByEmail",
		// params(domain, name, email)
		OVHMailWrapper::FUNC_ADD_SUBSCRIBER			=> "/email/domain/{domain}/mailingList/{name}/subscriber",
		// params(domain, name, email)
		OVHMailWrapper::FUNC_CREATE_DOMAIN_DELEG	=> "/email/domain/{domain}/migrateDelegationV3toV6",
		// params(domain)
		OVHMailWrapper::FUNC_CREATE_REDIRECTION		=> "/email/domain/{domain}/redirection",
		// params(domain, from, localCopy(bool), to)
		OVHMailWrapper::FUNC_CHANGE_REDIRECTION		=> "/email/domain/{domain}/redirection/{id}/changeRedirection",
		// params(domain, id, to) 
		OVHMailWrapper::FUNC_CREATE_RESPONDER		=> "/email/domain/{domain}/responder"
		// params(domain, account, content, copy, copyTo, from, to)
		);
	const DELETE_COMMANDS = array(
		OVHMailWrapper::FUNC_REMOVE_MAILBOX		=> "/email/domain/{domain}/account/{accountName}",
		OVHMailWrapper::FUNC_REMOVE_FILTER		=> "/email/domain/{domain}/account/{accountName}/filter/{name}",
		OVHMailWrapper::FUNC_REMOVE_RULE		=> "/email/domain/{domain}/account/{accountName}/filter/{name}/rule/{id}",
		OVHMailWrapper::FUNC_REMOVE_ACL			=> "/email/domain/{domain}/acl/{accountId}",
		OVHMailWrapper::FUNC_REMOVE_MAILLIST	=> "/email/domain/{domain}/mailingList/{name}",
		OVHMailWrapper::FUNC_REMOVE_MODERATOR	=> "/email/domain/{domain}/mailingList/{name}/moderator/{email}",
		OVHMailWrapper::FUNC_REMOVE_SUBSCRIBER	=> "/email/domain/{domain}/mailingList/{name}/subscriber/{email}",
		OVHMailWrapper::FUNC_REMOVE_REDIRECTION	=> "/email/domain/{domain}/redirection/{id}",
		OVHMailWrapper::FUNC_REMOVE_RESPONDER	=> "/email/domain/{domain}/responder/{account}"
		);

	// --functions

	public function __construct() {
		// creation du parent avec les informations adéquates
		parent::__construct(
			OVHMailWrapper::NAME,
			OVHMailWrapper::VERSION,
			OVHMailWrapper::AUTHORS);
	}

	public function Execute($function = "", $params = array()) {
		$r = null;
		if(false) {} 
		else if(!strcmp($function, OVHMailWrapper::FUNC_LIST_DOMAINS 				)) { $r = $this->_func_available_services 			($params); }
		else if(!strcmp($function, OVHMailWrapper::FUNC_DOMAIN_PROPERTIES 			)) { $r = $this->_func_domain_properties 			($params); }				
		else if(!strcmp($function, OVHMailWrapper::FUNC_LIST_ACCOUNTS 				)) { $r = $this->_func_list_accounts 				($params); }				
		else if(!strcmp($function, OVHMailWrapper::FUNC_ACCOUNT_PROPERTIES 			)) { $r = $this->_func_account_properties 			($params); }
		else if(!strcmp($function, OVHMailWrapper::FUNC_LIST_FILTERS 				)) { $r = $this->_func_list_filters 				($params); }		
		else if(!strcmp($function, OVHMailWrapper::FUNC_FILTER_PROPERTIES 			)) { $r = $this->_func_filter_properties 			($params); }				
		else if(!strcmp($function, OVHMailWrapper::FUNC_LIST_RULES 					)) { $r = $this->_func_list_rules 					($params); }				
		else if(!strcmp($function, OVHMailWrapper::FUNC_RULE_PROPERTIES 			)) { $r = $this->_func_rule_properties 				($params); }			
		else if(!strcmp($function, OVHMailWrapper::FUNC_ACCOUNT_USAGE 				)) { $r = $this->_func_account_usage 				($params); }				
		else if(!strcmp($function, OVHMailWrapper::FUNC_LIST_ACL 					)) { $r = $this->_func_list_acl 					($params); }
		else if(!strcmp($function, OVHMailWrapper::FUNC_ACL_PROPERTIES 				)) { $r = $this->_func_acl_properties 				($params); }				
		else if(!strcmp($function, OVHMailWrapper::FUNC_LIST_MXFILTERS 				)) { $r = $this->_func_list_mxfilters 				($params); }				
		else if(!strcmp($function, OVHMailWrapper::FUNC_LIST_MXRECORDS 				)) { $r = $this->_func_list_mxrecords 				($params); }				
		else if(!strcmp($function, OVHMailWrapper::FUNC_LIST_MAILLISTS 				)) { $r = $this->_func_list_maillists 				($params); }				
		else if(!strcmp($function, OVHMailWrapper::FUNC_MAILLIST_PROPERTIES 		)) { $r = $this->_func_maillist_properties 			($params); }		
		else if(!strcmp($function, OVHMailWrapper::FUNC_LIST_MODERATORS 			)) { $r = $this->_func_list_moderators 				($params); }				
		else if(!strcmp($function, OVHMailWrapper::FUNC_MODERATOR_PROPERTIES		)) { $r = $this->_func_moderator_properties 		($params); }			
		else if(!strcmp($function, OVHMailWrapper::FUNC_LIST_SUBSCRIBERS 			)) { $r = $this->_func_list_subscribers 			($params); }				
		else if(!strcmp($function, OVHMailWrapper::FUNC_SUBSCRIBER_PROPERTIES 		)) { $r = $this->_func_subscriber_properties 		($params); }			
		else if(!strcmp($function, OVHMailWrapper::FUNC_LIST_QUOTAS 				)) { $r = $this->_func_list_quotas 					($params); }
		else if(!strcmp($function, OVHMailWrapper::FUNC_LIST_REDIRECTIONS 			)) { $r = $this->_func_list_redirections 	 		($params); }				
		else if(!strcmp($function, OVHMailWrapper::FUNC_REDIRECTION_PROPERTIES 		)) { $r = $this->_func_redirection_properties 		($params); }		
		else if(!strcmp($function, OVHMailWrapper::FUNC_LIST_RESPONDERS 			)) { $r = $this->_func_list_responders 				($params); }				
		else if(!strcmp($function, OVHMailWrapper::FUNC_RESPONDER_PROPERTIES 		)) { $r = $this->_func_responder_properties  		($params); }			
		else if(!strcmp($function, OVHMailWrapper::FUNC_SERVICES_INFO 				)) { $r = $this->_func_services_info 				($params); }
		else if(!strcmp($function, OVHMailWrapper::FUNC_SUMMARY 					)) { $r = $this->_func_summary 						($params); }
		else if(!strcmp($function, OVHMailWrapper::FUNC_LIST_ACCOUNT_TASKS			)) { $r = $this->_func_list_account_tasks 	 		($params); }			
		else if(!strcmp($function, OVHMailWrapper::FUNC_ACCOUNT_TASK_PROPERTIES		)) { $r = $this->_func_account_task_properties 		($params); }		
		else if(!strcmp($function, OVHMailWrapper::FUNC_LIST_FILTER_TASKS 			)) { $r = $this->_func_list_filter_tasks 			($params); }				
		else if(!strcmp($function, OVHMailWrapper::FUNC_FILTER_TASK_PROPERTIES 		)) { $r = $this->_func_filter_task_properties 		($params); }		
		else if(!strcmp($function, OVHMailWrapper::FUNC_LIST_MAILLIST_TASKS 		)) { $r = $this->_func_list_maillist_tasks 			($params); }			
		else if(!strcmp($function, OVHMailWrapper::FUNC_MAILLIST_TASK_PROPERTIES 	)) { $r = $this->_func_maillist_task_properties 	($params); }		
		else if(!strcmp($function, OVHMailWrapper::FUNC_LIST_REDIRECTION_TASKS 		)) { $r = $this->_func_list_redirection_tasks 		($params); }		
		else if(!strcmp($function, OVHMailWrapper::FUNC_REDIRECTION_TASK_PROPERTIES )) { $r = $this->_func_redirection_task_properties 	($params); }
		else if(!strcmp($function, OVHMailWrapper::FUNC_LIST_RESPONDER_TASKS 		)) { $r = $this->_func_list_responder_tasks 		($params); }			
		else if(!strcmp($function, OVHMailWrapper::FUNC_RESPONDER_TASK_PROPERTIES 	)) { $r = $this->_func_responder_task_properties 	($params); }		
		else if(!strcmp($function, OVHMailWrapper::FUNC_UPDATE_ACCOUNT 				)) { $r = $this->_func_update_account 				($params); }			
		else if(!strcmp($function, OVHMailWrapper::FUNC_UPDATE_MAILLIST 			)) { $r = $this->_func_update_maillist				($params); }			
		else if(!strcmp($function, OVHMailWrapper::FUNC_UPDATE_RESPONDER 			)) { $r = $this->_func_update_responder				($params); }			
		else if(!strcmp($function, OVHMailWrapper::FUNC_UPDATE_SERVICES_INFO 		)) { $r = $this->_func_update_services_info 		($params); }		
		else if(!strcmp($function, OVHMailWrapper::FUNC_CREATE_MAILBOX 				)) { $r = $this->_func_create_mailbox 				($params); } 			
		else if(!strcmp($function, OVHMailWrapper::FUNC_CHANGE_MAILBOX_PWD 			)) { $r = $this->_func_change_mailbox_pwd 			($params); } 		
		else if(!strcmp($function, OVHMailWrapper::FUNC_CREATE_FILTER 				)) { $r = $this->_func_create_filter 				($params); } 			
		else if(!strcmp($function, OVHMailWrapper::FUNC_CHANGE_FILTER_ACTIVITY 		)) { $r = $this->_func_change_filter_activity 		($params); }
		else if(!strcmp($function, OVHMailWrapper::FUNC_CHANGE_FILTER_PRIORITY 		)) { $r = $this->_func_change_filter_priority 		($params); }
		else if(!strcmp($function, OVHMailWrapper::FUNC_CREATE_RULE 				)) { $r = $this->_func_create_rule 					($params); } 			
		else if(!strcmp($function, OVHMailWrapper::FUNC_UPDATE_ACCOUNT_USAGE 		)) { $r = $this->_func_update_account_usage 		($params); } 
		else if(!strcmp($function, OVHMailWrapper::FUNC_CREATE_ACL 					)) { $r = $this->_func_create_acl 					($params); } 			
		else if(!strcmp($function, OVHMailWrapper::FUNC_CHANGE_CONTACT 				)) { $r = $this->_func_change_contact 				($params); } 		
		else if(!strcmp($function, OVHMailWrapper::FUNC_CHANGE_MXFILTER 			)) { $r = $this->_func_change_mxfilter 				($params); } 		
		else if(!strcmp($function, OVHMailWrapper::FUNC_CREATE_MAILLIST 			)) { $r = $this->_func_create_maillist 				($params); } 		
		else if(!strcmp($function, OVHMailWrapper::FUNC_CHANGE_MAILLIST_OPTS 		)) { $r = $this->_func_change_maillist_opts 		($params); } 
		else if(!strcmp($function, OVHMailWrapper::FUNC_ADD_MODERATOR 				)) { $r = $this->_func_add_moderator 				($params); } 		
		else if(!strcmp($function, OVHMailWrapper::FUNC_SEND_MAILLIST_BY_MAIL 		)) { $r = $this->_func_send_maillist_by_mail 		($params); }
		else if(!strcmp($function, OVHMailWrapper::FUNC_ADD_SUBSCRIBER 				)) { $r = $this->_func_add_subscriber 				($params); } 		
		else if(!strcmp($function, OVHMailWrapper::FUNC_CREATE_DOMAIN_DELEG 		)) { $r = $this->_func_create_domain_deleg 			($params); } 	
		else if(!strcmp($function, OVHMailWrapper::FUNC_CREATE_REDIRECTION 			)) { $r = $this->_func_create_redirection 			($params); } 	
		else if(!strcmp($function, OVHMailWrapper::FUNC_CHANGE_REDIRECTION 			)) { $r = $this->_func_change_redirection 			($params); } 	
		else if(!strcmp($function, OVHMailWrapper::FUNC_CREATE_RESPONDER 			)) { $r = $this->_func_create_responder 			($params); } 	
		else if(!strcmp($function, OVHMailWrapper::FUNC_REMOVE_MAILBOX 				)) { $r = $this->_func_remove_mailbox 				($params); } 		
		else if(!strcmp($function, OVHMailWrapper::FUNC_REMOVE_FILTER 				)) { $r = $this->_func_remove_filter 				($params); } 		
		else if(!strcmp($function, OVHMailWrapper::FUNC_REMOVE_RULE 				)) { $r = $this->_func_remove_rule 					($params); } 			
		else if(!strcmp($function, OVHMailWrapper::FUNC_REMOVE_ACL 					)) { $r = $this->_func_remove_acl 					($params); } 			
		else if(!strcmp($function, OVHMailWrapper::FUNC_REMOVE_MAILLIST 			)) { $r = $this->_func_remove_maillist 				($params); } 		
		else if(!strcmp($function, OVHMailWrapper::FUNC_REMOVE_MODERATOR 			)) { $r = $this->_func_remove_moderator 			($params); } 	
		else if(!strcmp($function, OVHMailWrapper::FUNC_REMOVE_SUBSCRIBER 			)) { $r = $this->_func_remove_subscriber 			($params); } 	
		else if(!strcmp($function, OVHMailWrapper::FUNC_REMOVE_REDIRECTION 			)) { $r = $this->_func_remove_redirection 			($params); } 	
		else if(!strcmp($function, OVHMailWrapper::FUNC_REMOVE_RESPONDER 			)) { $r = $this->_func_remove_responder 			($params); }
		else { $r = "Unknown function called."; }
		return $r;
	}

# PROTECTED & PRIVATE ################################################################################

		// -----------------------------------------------------------------------------------------------------------------------------------
		//  GET RELATED FUNCTIONS
		// -----------------------------------------------------------------------------------------------------------------------------------
		private function _func_available_services 			($params) {
			return $this->_get(OVHMailWrapper::FUNC_LIST_DOMAINS, $params);
		}
		private function _func_domain_properties 			($params) {
			return $this->_get(OVHMailWrapper::FUNC_DOMAIN_PROPERTIES, $params, 
				array(OVHMailWrapper::ARG_DOMAIN));
		}				
		private function _func_list_accounts 				($params) {
			return $this->_get(OVHMailWrapper::FUNC_LIST_ACCOUNTS, $params, 
				array(OVHMailWrapper::ARG_DOMAIN));
		}				
		private function _func_account_properties 			($params) {
			return $this->_get(OVHMailWrapper::FUNC_ACCOUNT_PROPERTIES, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ACCOUNT_NAME));
		}
		private function _func_list_filters 				($params) {
			return $this->_get(OVHMailWrapper::FUNC_LIST_FILTERS, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ACCOUNT_NAME));
		}		
		private function _func_filter_properties 			($params) {
			return $this->_get(OVHMailWrapper::FUNC_FILTER_PROPERTIES, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ACCOUNT_NAME, OVHMailWrapper::ARG_NAME));
		}				
		private function _func_list_rules 					($params) {
			return $this->_get(OVHMailWrapper::FUNC_LIST_RULES, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ACCOUNT_NAME, OVHMailWrapper::ARG_NAME));
		}				
		private function _func_rule_properties 				($params) {
			return $this->_get(OVHMailWrapper::FUNC_RULE_PROPERTIES, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ACCOUNT_NAME, OVHMailWrapper::ARG_NAME, OVHMailWrapper::ARG_ID));
		}			
		private function _func_account_usage 				($params) {
			return $this->_get(OVHMailWrapper::FUNC_ACCOUNT_USAGE, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ACCOUNT_NAME));
		}				
		private function _func_list_acl 					($params) {
			return $this->_get(OVHMailWrapper::FUNC_LIST_ACL, $params, array(OVHMailWrapper::ARG_DOMAIN));
		}
		private function _func_acl_properties 				($params) {
			return $this->_get(OVHMailWrapper::FUNC_ACL_PROPERTIES, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ACCOUNT_ID));
		}				
		private function _func_list_mxfilters 				($params) {
			return $this->_get(OVHMailWrapper::FUNC_LIST_MXFILTERS, $params, array(OVHMailWrapper::ARG_DOMAIN));
		}				
		private function _func_list_mxrecords 				($params) {
			return $this->_get(OVHMailWrapper::FUNC_LIST_MXRECORDS, $params, array(OVHMailWrapper::ARG_DOMAIN));
		}				
		private function _func_list_maillists 				($params) {
			return $this->_get(OVHMailWrapper::FUNC_LIST_MAILLISTS, $params, array(OVHMailWrapper::ARG_DOMAIN));
		}				
		private function _func_maillist_properties 			($params) {
			return $this->_get(OVHMailWrapper::FUNC_MAILLIST_PROPERTIES, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_NAME));
		}		
		private function _func_list_moderators 				($params) {
			return $this->_get(OVHMailWrapper::FUNC_LIST_MODERATORS, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_NAME));
		}				
		private function _func_moderator_properties 		($params) {
			return $this->_get(OVHMailWrapper::FUNC_MODERATOR_PROPERTIES, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_NAME, OVHMailWrapper::ARG_EMAIL));
		}			
		private function _func_list_subscribers 			($params) {
			return $this->_get(OVHMailWrapper::FUNC_LIST_SUBSCRIBERS, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_NAME));
		}				
		private function _func_subscriber_properties 		($params) {
			return $this->_get(OVHMailWrapper::FUNC_SUBSCRIBER_PROPERTIES, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_NAME, OVHMailWrapper::ARG_EMAIL));
		}			
		private function _func_list_quotas 					($params) {
			return $this->_get(OVHMailWrapper::FUNC_LIST_QUOTAS, $params, array(OVHMailWrapper::ARG_DOMAIN));
		}
		private function _func_list_redirections 	 		($params) {
			return $this->_get(OVHMailWrapper::FUNC_LIST_REDIRECTIONS, $params, array(OVHMailWrapper::ARG_DOMAIN));
		}				
		private function _func_redirection_properties 		($params) {
			return $this->_get(OVHMailWrapper::FUNC_REDIRECTION_PROPERTIES, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ID));
		}		
		private function _func_list_responders 				($params) {
			return $this->_get(OVHMailWrapper::FUNC_LIST_RESPONDERS, $params, array(OVHMailWrapper::ARG_DOMAIN));
		}				
		private function _func_responder_properties  		($params) {	
			return $this->_get(OVHMailWrapper::FUNC_RESPONDER_PROPERTIES, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ACCOUNT));
		}			
		private function _func_services_info 				($params) {
			return $this->_get(OVHMailWrapper::FUNC_SERVICES_INFO, $params, array(OVHMailWrapper::ARG_DOMAIN));
		}
		private function _func_summary 						($params) {
			return $this->_get(OVHMailWrapper::FUNC_SUMMARY, $params, array(OVHMailWrapper::ARG_DOMAIN));
		}
		private function _func_list_account_tasks 	 		($params) {
			return $this->_get(OVHMailWrapper::FUNC_LIST_ACCOUNT_TASKS, $params, array(OVHMailWrapper::ARG_DOMAIN));
		}			
		private function _func_account_task_properties 		($params) {
			return $this->_get(OVHMailWrapper::FUNC_ACCOUNT_TASK_PROPERTIES, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ID));
		}		
		private function _func_list_filter_tasks 			($params) {
			return $this->_get(OVHMailWrapper::FUNC_LIST_FILTER_TASKS, $params, array(OVHMailWrapper::ARG_DOMAIN));
		}				
		private function _func_filter_task_properties 		($params) {
			return $this->_get(OVHMailWrapper::FUNC_FILTER_TASK_PROPERTIES, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ID));
		}		
		private function _func_list_maillist_tasks 			($params) {
			return $this->_get(OVHMailWrapper::FUNC_LIST_MAILLIST_TASKS, $params, array(OVHMailWrapper::ARG_DOMAIN));
		}			
		private function _func_maillist_task_properties 	($params) {
			return $this->_get(OVHMailWrapper::FUNC_MAILLIST_TASK_PROPERTIES, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ID));
		}		
		private function _func_list_redirection_tasks 		($params) {
			return $this->_get(OVHMailWrapper::FUNC_LIST_REDIRECTION_TASKS, $params, array(OVHMailWrapper::ARG_DOMAIN));
		}		
		private function _func_redirection_task_properties 	($params) {
			return $this->_get(OVHMailWrapper::FUNC_REDIRECTION_TASK_PROPERTIES, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ID));
		}
		private function _func_list_responder_tasks 		($params) {
			return $this->_get(OVHMailWrapper::FUNC_LIST_RESPONDER_TASKS, $params, array(OVHMailWrapper::ARG_DOMAIN));
		}			
		private function _func_responder_task_properties 	($params) {
			return $this->_get(OVHMailWrapper::FUNC_RESPONDER_TASK_PROPERTIES, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ID));
		}		
		// -----------------------------------------------------------------------------------------------------------------------------------
		//  PUT RELATED FUNCTIONS
		// -----------------------------------------------------------------------------------------------------------------------------------
		private function _func_update_account 				($params) {
			return $this->_put(OVHMailWrapper::FUNC_UPDATE_ACCOUNT, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ACCOUNT_NAME));
		}			
		private function _func_update_maillist				($params) {
			return $this->_put(OVHMailWrapper::FUNC_UPDATE_MAILLIST, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_NAME));
		}			
		private function _func_update_responder				($params) {
			return $this->_put(OVHMailWrapper::FUNC_UPDATE_RESPONDER, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ACCOUNT));
		}			
		private function _func_update_services_info 		($params) {
			return $this->_put(OVHMailWrapper::FUNC_UPDATE_SERVICES_INFO, $params, array(OVHMailWrapper::ARG_DOMAIN));
		}		
		// -----------------------------------------------------------------------------------------------------------------------------------
		//  POST RELATED FUNCTIONS
		// -----------------------------------------------------------------------------------------------------------------------------------
		private function _func_create_mailbox 				($params) {
			return $this->_post(OVHMailWrapper::FUNC_CREATE_MAILBOX, $params, array(OVHMailWrapper::ARG_DOMAIN));
		} 			
		private function _func_change_mailbox_pwd 			($params) {
			return $this->_post(OVHMailWrapper::FUNC_CHANGE_MAILBOX_PWD, $params, 
				array(OVHMailWrapper::ARG_DOMAIN , OVHMailWrapper::ARG_ACCOUNT_NAME));
		} 		
		private function _func_create_filter 				($params) {
			return $this->_post(OVHMailWrapper::FUNC_CREATE_FILTER, $params, 
				array(OVHMailWrapper::ARG_DOMAIN , OVHMailWrapper::ARG_ACCOUNT_NAME));
		} 			
		private function _func_change_filter_activity 		($params) {
			return $this->_post(OVHMailWrapper::FUNC_CHANGE_FILTER_ACTIVITY, $params, 
				array(OVHMailWrapper::ARG_DOMAIN , OVHMailWrapper::ARG_ACCOUNT_NAME, OVHMailWrapper::ARG_NAME));
		}
		private function _func_change_filter_priority 		($params) {
			return $this->_post(OVHMailWrapper::FUNC_CHANGE_FILTER_PRIORITY, $params, 
				array(OVHMailWrapper::ARG_DOMAIN , OVHMailWrapper::ARG_ACCOUNT_NAME, OVHMailWrapper::ARG_NAME));
		}
		private function _func_create_rule 					($params) {
			return $this->_post(OVHMailWrapper::FUNC_CREATE_RULE, $params, 
				array(OVHMailWrapper::ARG_DOMAIN , OVHMailWrapper::ARG_ACCOUNT_NAME, OVHMailWrapper::ARG_NAME));
		} 			
		private function _func_update_account_usage 		($params) {
			return $this->_post(OVHMailWrapper::FUNC_UPDATE_ACCOUNT_USAGE, $params, 
				array(OVHMailWrapper::ARG_DOMAIN , OVHMailWrapper::ARG_ACCOUNT_NAME));
		} 
		private function _func_create_acl 					($params) {
			return $this->_post(OVHMailWrapper::FUNC_CREATE_ACL, $params, array(OVHMailWrapper::ARG_DOMAIN));
		} 			
		private function _func_change_contact 				($params) {
			return $this->_post(OVHMailWrapper::FUNC_CHANGE_CONTACT, $params, array(OVHMailWrapper::ARG_DOMAIN));
		} 		
		private function _func_change_mxfilter 				($params) {
			return $this->_post(OVHMailWrapper::FUNC_CHANGE_MXFILTER, $params, array(OVHMailWrapper::ARG_DOMAIN));
		} 		
		private function _func_create_maillist 				($params) {
			return $this->_post(OVHMailWrapper::FUNC_CREATE_MAILLIST, $params, array(OVHMailWrapper::ARG_DOMAIN));
		} 		
		private function _func_change_maillist_opts 		($params) {
			return $this->_post(OVHMailWrapper::FUNC_CHANGE_MAILLIST_OPTS, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_NAME));
		} 
		private function _func_add_moderator 				($params) {
			return $this->_post(OVHMailWrapper::FUNC_ADD_MODERATOR, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_NAME));
		} 		
		private function _func_send_maillist_by_mail 		($params) {
			return $this->_post(OVHMailWrapper::FUNC_SEND_MAILLIST_BY_MAIL, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_NAME));
		}
		private function _func_add_subscriber 				($params) {
			return $this->_post(OVHMailWrapper::FUNC_ADD_SUBSCRIBER, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_NAME));
		} 		
		private function _func_create_domain_deleg 			($params) {
			return $this->_post(OVHMailWrapper::FUNC_CREATE_DOMAIN_DELEG, $params, array(OVHMailWrapper::ARG_DOMAIN));
		} 	
		private function _func_create_redirection 			($params) {
			return $this->_post(OVHMailWrapper::FUNC_CREATE_REDIRECTION, $params, array(OVHMailWrapper::ARG_DOMAIN));
		} 	
		private function _func_change_redirection 			($params) {
			return $this->_post(OVHMailWrapper::FUNC_CHANGE_REDIRECTION, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ID));
		} 	
		private function _func_create_responder 			($params) {
			return $this->_post(OVHMailWrapper::FUNC_CREATE_RESPONDER, $params, array(OVHMailWrapper::ARG_DOMAIN));
		} 	
		// -----------------------------------------------------------------------------------------------------------------------------------
		//  DELETE RELATED FUNCTIONS
		// -----------------------------------------------------------------------------------------------------------------------------------
		private function _func_remove_mailbox 				($params) {
			return $this->_delete(OVHMailWrapper::FUNC_REMOVE_MAILBOX, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ACCOUNT_NAME));
		} 		
		private function _func_remove_filter 				($params) {
			return $this->_delete(OVHMailWrapper::FUNC_REMOVE_MAILBOX, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ACCOUNT_NAME, OVHMailWrapper::ARG_NAME));
		} 		
		private function _func_remove_rule 					($params) {
			return $this->_delete(OVHMailWrapper::FUNC_REMOVE_MAILBOX, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ACCOUNT_NAME, OVHMailWrapper::ARG_NAME, OVHMailWrapper::ARG_ID));
		} 			
		private function _func_remove_acl 					($params) {
			return $this->_delete(OVHMailWrapper::FUNC_REMOVE_MAILBOX, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ACCOUNT_ID));
		} 			
		private function _func_remove_maillist 				($params) {
			return $this->_delete(OVHMailWrapper::FUNC_REMOVE_MAILBOX, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_NAME));
		} 		
		private function _func_remove_moderator 			($params) {
			return $this->_delete(OVHMailWrapper::FUNC_REMOVE_MAILBOX, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_NAME, OVHMailWrapper::ARG_EMAIL));
		} 	
		private function _func_remove_subscriber 			($params) {
			return $this->_delete(OVHMailWrapper::FUNC_REMOVE_MAILBOX, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_NAME, OVHMailWrapper::ARG_EMAIL));
		} 	
		private function _func_remove_redirection 			($params) {
			return $this->_delete(OVHMailWrapper::FUNC_REMOVE_MAILBOX, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ID));
		} 	
		private function _func_remove_responder 			($params) {
			return $this->_delete(OVHMailWrapper::FUNC_REMOVE_MAILBOX, $params, 
				array(OVHMailWrapper::ARG_DOMAIN, OVHMailWrapper::ARG_ACCOUNT));
		}

		/**
		 *	Injecte les paramètres attendus dans l'url présents dans le tableau 
		 *  et retourne le tableau sans ces paramètres.
		 */
		private function _bind(&$params, $url, $binding_keys) {
			// check if expected binded params exists, bind and extract from array
			$binded = $url;
			foreach ($binding_keys as $key) {
				if(array_key_exists($key, $params)) {
					// bind
					$binded = str_replace($key, $params[$key], $binded);
					// extract
					unset($params[$key]);
				} else {
					$binded = null;
					break;
				}
			}
			// check if params remain
			if(isset($binded) && str_contains('{', $binded)) {
				$binded = null; // return null binded
			}
			return $binded;
		}

		/**
		 *	Execute une requete HTTP GET sur l'API OVH en utilisant l'interface Api développée par OVH
		 */
		private function _get($function, $params = array(), $binding_keys = array()) {
			$url = OVHMailWrapper::GET_COMMANDS[$function];
			$binded = $this->_bind($params, $url, $binding_keys);
			if(!isset($binded)) {
				return null;
			}
			return parent::getAPI()->get($binded);
		}

		/**
		 *	Execute une requete HTTP PUT sur l'API OVH en utilisant l'interface Api développée par OVH
		 */
		private function _put($function, $params = array(), $binding_keys = array()) {
			$url = OVHMailWrapper::GET_COMMANDS[$function];
			$binded = $this->_bind($params, $url, $binding_keys);
			if(!isset($binded)) {
				return null;
			}
			return parent::getAPI()->put($binded, $params);
		}

		/**
		 *	Execute une requete HTTP POST sur l'API OVH en utilisant l'interface Api développée par OVH
		 */
		private function _post($function, $params = array(), $binding_keys = array()) {
			$url = OVHMailWrapper::GET_COMMANDS[$function];
			$binded = $this->_bind($params, $url, $binding_keys);
			if(!isset($binded)) {
				return null;
			}
			return parent::getAPI()->post($binded, $params);
		}

		/**
		 *	Execute une requete HTTP DELETE sur l'API OVH en utilisant l'interface Api développée par OVH
		 */
		private function _delete($function, $params = array(), $binding_keys = array()) {
			$url = OVHMailWrapper::GET_COMMANDS[$function];
			$binded = $this->_bind($params, $url, $binding_keys);
			if(!isset($binded)) {
				return null;
			}
			return parent::getAPI()->delete($binded);
		}
}

# ENREGISTREMENT DU WRAPPER AUPRES DU LOADER ###############################################################

WrapperLoader::RegisterWrapper( new OVHMailWrapper() );