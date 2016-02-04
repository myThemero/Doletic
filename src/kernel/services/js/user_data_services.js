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

  this.getUserLastPos = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_USER_LAST_POS, 
            { id: id }, 
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