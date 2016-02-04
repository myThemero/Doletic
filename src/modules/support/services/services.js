// ----------------------- TICKET INTERFACE SERVICES CLASS ----------------------------------

var TicketServicesInterface = new function() {

  this.meta = {
    // --- (object)
    OBJECT: 'ticket',
    // --- (actions)
    ACTION: {
      GET_TICKET_BY_ID:'byidt',
      GET_STATUS_BY_ID:'byids',
      GET_CATEGO_BY_ID:'byidc',
      GET_ALL_TICKETS:'allt',
      GET_ALL_STATUSES:'alls',
      GET_ALL_CATEGOS:'allc',
      INSERT:'insert',
      UPDATE:'update',
      DELETE:'delete',
      ARCHIVE:'archive'
    }
  };

  this.getAll = function(successHandler) {
   return DoleticServicesInterface.callService(this.meta.OBJECT, this.meta.ACTION.GET_ALL_TICKETS, {}, successHandler); 
  }
  this.getAllStatuses = function(successHandler) {
   return DoleticServicesInterface.callService(this.meta.OBJECT, this.meta.ACTION.GET_ALL_STATUSES, {}, successHandler); 
  }
  this.getAllCategories = function(successHandler) {
   return DoleticServicesInterface.callService(this.meta.OBJECT, this.meta.ACTION.GET_ALL_CATEGOS, {}, successHandler); 
  }

  this.getById = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_TICKET_BY_ID, 
            { id: id }, 
            successHandler); 
  }
  this.getStatusById = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_STATUS_BY_ID, 
            { id: id }, 
            successHandler); 
  }
  this.getCategoryById = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_CATEGO_BY_ID, 
            { id: id }, 
            successHandler); 
  }

  this.insert = function(receiverId, subject, categoryId, data, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.INSERT, 
            {
              receiverId:receiverId,
              subject:subject,
              categoryId:categoryId,
              data:data
            }, 
            successHandler); 
  }
  this.update = function(id, receiverId, subject, categoryId, data, statusId, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.UPDATE, 
            {
              id:id,
              receiverId:receiverId,
              subject:subject,
              categoryId:categoryId,
              data:data,
              statusId:statusId
            }, 
            successHandler);
  }
  this.delete = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.DELETE, 
            { id: id }, 
            successHandler); 
  }
  this.archive = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.DELETE, 
            { id: id }, 
            successHandler); 
  } 

}