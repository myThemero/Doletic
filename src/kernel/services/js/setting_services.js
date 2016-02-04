// ----------------------- SETTINGS INTERFACE SERVICES CLASS ----------------------------------

var SettingServicesInterface = new function() {

  this.meta = {
    // --- (object)
    OBJECT: 'setting',
    // --- (actions)
    ACTION: {
      GET_SETTING_BY_ID:'byid',
      GET_SETTING_BY_KEY:'bykey',
      GET_ALL_SETTINGS:'all',
      INSERT: 'insert',
      UPDATE: 'update',
      DELETE: 'delete'
    }
  };

  this.getAll = function(successHandler) {
   return DoleticServicesInterface.callService(this.meta.OBJECT,this.meta.ACTION.GET_ALL_SETTINGS,{},successHandler); 
  }

  this.getById = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_SETTING_BY_ID, 
            { id: id }, 
            successHandler); 
  }

  this.getByKey = function(key, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_SETTING_BY_KEY, 
            { key: key }, 
            successHandler); 
  }

  this.insert = function(key, value, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, 
            this.meta.ACTION.INSERT, 
            {
              key: key,
              value: value
            }, 
            successHandler); 
  }

  this.update = function(key, value, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, 
            this.meta.ACTION.UPDATE, 
            {
              key: key,
              value: value
            }, 
            successHandler); 
  }

  this.delete = function(key, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, 
            this.meta.ACTION.DELETE, 
            { key: key }, 
            successHandler); 
  }  

}