// ----------------------- USER INTERFACE SERVICES CLASS ----------------------------------

var UserServicesInterface = new function() {

  this.meta = {
    // --- (object)
    OBJECT: 'user',
    // --- (actions)
    ACTION: {
      GET_USER_BY_ID:'byid',
      GET_ALL_USERS: 'all',
      INSERT: 'insert',
      UPDATE: 'update',
      DELETE: 'delete'
    }
  };

  this.getAll = function(successHandler) {
   return DoleticServicesInterface.callService(
            this.meta.OBJECT, 
            this.meta.ACTION.GET_ALL_USERS, 
            {}, 
            successHandler); 
  }

  this.getById = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, 
            this.meta.ACTION.GET_USER_BY_ID, 
            {
              id: id
            }, 
            successHandler); 
  }

  this.insert = function(username, password, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, 
            this.meta.ACTION.INSERT, 
            {
              username: username,
              hash: phpjsLight.sha1(password)
            }, 
            successHandler); 
  }

  this.update = function(id, password, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, 
            this.meta.ACTION.UPDATE, 
            {
              id: id,
              hash: phpjsLight.sha1(password)
            }, 
            successHandler); 
  }

  this.delete = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, 
            this.meta.ACTION.DELETE, 
            {
              id: id
            }, 
            successHandler); 
  }  

}