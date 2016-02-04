// ----------------------- COMMENT INTERFACE SERVICES CLASS ----------------------------------

var CommentServicesInterface = new function() {

  this.meta = {
    // --- (object)
    OBJECT: 'comment',
    // --- (actions)
    ACTION: {
      GET_COMMENT_BY_ID:'byid',
      GET_ALL_COMMENTS:'all',
      INSERT: 'insert',
      UPDATE: 'update',
      DELETE: 'delete'
    }
  };

  this.getAll = function(successHandler) {
   return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_ALL_COMMENTS, 
            {}, 
            successHandler); 
  }

  this.getById = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_COMMENT_BY_ID, 
            { id: id }, 
            successHandler); 
  }

  this.insert = function(userId, data, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.INSERT, 
            {
              userId: userId,
              data: data
            },
            successHandler); 
  }

  this.update = function(id, data, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.UPDATE, 
            {
              id: id,
              data: data
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