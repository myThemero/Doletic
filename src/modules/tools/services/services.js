// ----------------------- MAILLIST INTERFACE SERVICES CLASS ----------------------------------

var MaillistServicesInterface = new function() {

  this.meta = {
    // --- (object)
    OBJECT: 'maillist',
    // --- (actions)
    ACTION: {
	    GET_MAILLIST_BY_ID:'byid';
		GET_ALL_MAILLIST:'all';
		SUBSCRIBE:'subscribe';
		UNSUBSCRIBE:'unsubscribe';
		INSERT:'insert';
		UPDATE:'update';
		DELETE:'delete';
    }
  };

  this.getAll = function(successHandler) {
   return DoleticServicesInterface.callService(this.meta.OBJECT, this.meta.ACTION.GET_ALL_MAILLIST, {}, successHandler); 
  }
  this.getById = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_MAILLIST_BY_ID, 
            { id: id }, 
            successHandler); 
  }
  this.subscribe = function(maillistId, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.SUBSCRIBE, 
            { maillistId: maillistId }, 
            successHandler); 
  }
  this.unsubscribe = function(maillistId, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.UNSUBSCRIBE, 
            { maillistId: maillistId }, 
            successHandler); 
  }
  this.insert = function(name, canSubscribe, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.INSERT, 
            {
              name:name,
              canSubscribe:canSubscribe
            }, 
            successHandler); 
  }
  this.update = function(id, name, canSubscribe, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.UPDATE, 
            {
              id:id,
              name:name,
              canSubscribe:canSubscribe
            }, 
            successHandler);
  }
  this.delete = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.DELETE, 
            { id: id }, 
            successHandler); 
  } 
}

// ----------------------- DCOTEMPLATE INTERFACE SERVICES CLASS ----------------------------------

var DocTemplateServicesInterface = new function() {

  this.meta = {
    // --- (object)
    OBJECT: 'document_template',
    // --- (actions)
    ACTION: {
	    GET_MAILLIST_BY_ID:'byid';
		GET_ALL_MAILLIST:'all';
		SUBSCRIBE:'subscribe';
		UNSUBSCRIBE:'unsubscribe';
		INSERT:'insert';
		UPDATE:'update';
		DELETE:'delete';
    }
  };

  this.getAll = function(successHandler) {
   return DoleticServicesInterface.callService(this.meta.OBJECT, this.meta.ACTION.GET_ALL_MAILLIST, {}, successHandler); 
  }
  this.getById = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_MAILLIST_BY_ID, 
            { id: id }, 
            successHandler); 
  }
  this.subscribe = function(maillistId, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.SUBSCRIBE, 
            { maillistId: maillistId }, 
            successHandler); 
  }
  this.unsubscribe = function(maillistId, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.UNSUBSCRIBE, 
            { maillistId: maillistId }, 
            successHandler); 
  }
  this.insert = function(name, canSubscribe, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.INSERT, 
            {
              name:name,
              canSubscribe:canSubscribe
            }, 
            successHandler); 
  }
  this.update = function(id, name, canSubscribe, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.UPDATE, 
            {
              id:id,
              name:name,
              canSubscribe:canSubscribe
            }, 
            successHandler);
  }
  this.delete = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.DELETE, 
            { id: id }, 
            successHandler); 
  } 
}