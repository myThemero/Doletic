// ----------------------- TEAM INTERFACE SERVICES CLASS ----------------------------------

var TeamServicesInterface = new function() {

  this.meta = {
    // --- (object)
    OBJECT: 'team',
    // --- (actions)
    ACTION: {
      GET_TEAM_BY_ID:'byidt',
      GET_TEAM_BY_DIV:'bydivt',
      GET_TEAM_MEMBERS:'memt',
      GET_ALL_TEAMS:'allt',
      GET_USER_TEAMS:'allut',
      GET_ALL_DIVISIONS:'alldiv',
      INSERT_MEMBER:'insmem',
      DELETE_MEMBER:'delmem',
      INSERT:'insert',
      UPDATE:'update',
      DELETE:'delete'
    }
  };

  this.getAllTeams = function(successHandler) {
   return DoleticServicesInterface.callService(this.meta.OBJECT, this.meta.ACTION.GET_ALL_TEAMS, {}, successHandler); 
  }
  this.getAllDivisions = function(successHandler) {
   return DoleticServicesInterface.callService(this.meta.OBJECT, this.meta.ACTION.GET_ALL_DIVISIONS, {}, successHandler); 
  }
  this.getUserTeams = function(successHandler) {
   return DoleticServicesInterface.callService(this.meta.OBJECT, this.meta.ACTION.GET_USER_TEAMS, {}, successHandler); 
  }
  this.getTeamMembers = function(statusId, successHandler) {
   return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_TEAM_MEMBERS, 
            { id:id }, 
            successHandler); 
  }
  this.getById = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_TEAM_BY_ID, 
            { id: id }, 
            successHandler); 
  }
  this.insertMember = function(id, memberId, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.INSERT_MEMBER, 
            { 
              id: id,
              memberId: memberId
            }, 
            successHandler); 
  }
  this.deleteMember = function(id, memberId, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.DELETE_MEMBER, 
            { 
              id: id,
              memberId: memberId
            }, 
            successHandler); 
  }
  this.insert = function(name, leaderId, division, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.INSERT, 
            {
              name:name,
              leaderId:leaderId,
              division:division
            }, 
            successHandler); 
  }
  this.update = function(id, name, leaderId, division, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.UPDATE, 
            {
              id:id,
              name:name,
              leaderId:leaderId,
              division:division
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