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

// ----------------------- USER DATA INTERFACE SERVICES CLASS ----------------------------------

var UserDataServicesInterface = new function() {

  this.meta = {
    // --- (object)
    OBJECT: 'udata',
    // --- (actions)
    ACTION: {
      GET_USER_DATA_BY_ID:"byidud",
      GET_GENDER_BY_ID:"byidg",
      GET_COUNTRY_BY_ID:"byidc",
      GET_INSA_DEPT_BY_ID:"byiddept",
      GET_POSITION_BY_ID:"byidpos",
      GET_USER_LAST_POS:"lastpos",
      GET_ALL_USER_DATA:"allud",
      GET_ALL_GENDERS:"allg",
      GET_ALL_COUNTRIES:"allc",
      GET_ALL_INSA_DEPTS:"alldept",
      GET_ALL_POSITIONS:"allpos",
      INSERT:"insert",
      UPDATE:"update",
      UPDATE_POSTION:"updatepos",
      DELETE:"delete"
    }
  };

  this.getAll = function(successHandler) {
   return DoleticServicesInterface.callService(this.meta.OBJECT,this.meta.ACTION.GET_ALL_USER_DATA,{},successHandler); 
  }

  this.getAllGenders = function(successHandler) {
   return DoleticServicesInterface.callService(this.meta.OBJECT,this.meta.ACTION.GET_ALL_GENDERS,{},successHandler);
  }

  this.getAllCountries = function(successHandler) {
   return DoleticServicesInterface.callService(this.meta.OBJECT,this.meta.ACTION.GET_ALL_COUNTRIES,{},successHandler); 
  }

  this.getAllINSADepts = function(successHandler) {
   return DoleticServicesInterface.callService(this.meta.OBJECT,this.meta.ACTION.GET_ALL_INSA_DEPTS,{},successHandler); 
  } 

  this.getAllPositions = function(successHandler) {
   return DoleticServicesInterface.callService(this.meta.OBJECT,this.meta.ACTION.GET_ALL_POSITIONS,{},successHandler); 
  }  

  this.getById = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_USER_DATA_BY_ID, 
            { id: id }, 
            successHandler); 
  }

  this.getGenderById = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_GENDER_BY_ID, 
            { id: id }, 
            successHandler); 
  }

  this.getCountryById = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_COUNTRY_BY_ID, 
            { id: id },  
            successHandler); 
  }

  this.getINSADeptById = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_INSA_DEPT_BY_ID, 
            { id: id }, 
            successHandler); 
  }

  this.getINSADeptById = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_INSA_DEPT_BY_ID, 
            { id: id }, 
            successHandler); 
  }  

  this.getUserLastPos = function(userId, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_USER_LAST_POS, 
            { userId: userId }, 
            successHandler);
  }

  this.insert = function(userId, genderId, firstname, lastname, birthdate, tel, email, address, countryId, schoolYear, insaDeptId, positionId, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, 
            this.meta.ACTION.INSERT, 
            {
              userId:userId,
              genderId:genderId,
              firstname:firstname,
              lastname:lastname,
              birthdate:birthdate,
              tel:tel,
              email:email,
              address:address,
              countryId:countryId,
              schoolYear:schoolYear,
              insaDeptId:insaDeptId,
              positionId:positionId
            }, 
            successHandler); 
  }

  this.update = function(id, userId, genderId, firstname, lastname, birthdate, tel, email, address, countryId, schoolYear, insaDeptId, positionId, password, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, 
            this.meta.ACTION.UPDATE, 
            {
              id: id,
              userId:userId,
              genderId:genderId,
              firstname:firstname,
              lastname:lastname,
              birthdate:birthdate,
              tel:tel,
              email:email,
              address:address,
              countryId:countryId,
              schoolYear:schoolYear,
              insaDeptId:insaDeptId,
              positionId:positionId
            }, 
            successHandler); 
  }

  this.updateUserPosition = function(userId, positionId, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.UPDATE_POSTION, 
            { 
              userId: userId,
              positionId: positionId
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