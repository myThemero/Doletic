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

  this.getAll = function(successHandler) {
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

// ----------------------- ADM_MEMBERSHIP INTERFACE SERVICES CLASS ----------------------------------

var AdmMembershipServicesInterface = new function() {

  this.meta = {
    // --- (object)
    OBJECT: 'adm_membership',
    // --- (actions)
    ACTION: {
      GET_ADM_MEMBERSHIP_BY_ID:'byidam',
      GET_ALL_ADM_MEMBERSHIP:'allam',
      GET_USER_ADM_MEMBERSHIP:'alluam',
      GET_ALL_AGS:'allag',
      INSERT:'insert',
      UPDATE:'update',
      DELETE:'delete'
    }
  };

  this.getAll = function(successHandler) {
   return DoleticServicesInterface.callService(this.meta.OBJECT, this.meta.ACTION.GET_ALL_ADM_MEMBERSHIPS, {}, successHandler); 
  }
  this.getUserAdmMemberships = function(successHandler) {
   return DoleticServicesInterface.callService(this.meta.OBJECT, this.meta.ACTION.GET_USER_ADM_MEMBERSHIPS, {}, successHandler); 
  }
  this.getById = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_ADM_MEMBERSHIP_BY_ID, 
            { id: id }, 
            successHandler); 
  }
  this.insert = function(userId, startDate, endDate, fee, form, certif, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.INSERT, 
            {
              userId:userId,
              startDate:startDate,
              endDate:endDate,
              fee:fee,
              form:form,
              certif:certif
            }, 
            successHandler); 
  }
  this.update = function(id, userId, startDate, endDate, fee, form, certif, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.UPDATE, 
            {
              id:id,
              userId:userId,
              startDate:startDate,
              endDate:endDate,
              fee:fee,
              form:form,
              certif:certif
            }, 
            successHandler);
  }
  this.delete = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.DELETE, 
            { id: id }, 
            successHandler); 
  } 
  this.getAllAgs = function(successHandler) {
    return DoleticServicesInterface.callService(this.meta.OBJECT, this.meta.ACTION.GET_ALL_AGS, {}, successHandler); 
  }

}

// ----------------------- INT_MEMBERSHIP INTERFACE SERVICES CLASS ----------------------------------

var IntMembershipServicesInterface = new function() {

  this.meta = {
    // --- (object)
    OBJECT: 'int_membership',
    // --- (actions)
    ACTION: {
      GET_INT_MEMBERSHIP_BY_ID:'byidim',
      GET_ALL_INT_MEMBERSHIP:'allim',
      GET_USER_INT_MEMBERSHIP:'alluim',
      INSERT:'insert',
      UPDATE:'update',
      DELETE:'delete'
    }
  };

  this.getAll = function(successHandler) {
   return DoleticServicesInterface.callService(this.meta.OBJECT, this.meta.ACTION.GET_ALL_INT_MEMBERSHIPS, {}, successHandler); 
  }
  this.getUserIntMemberships = function(successHandler) {
   return DoleticServicesInterface.callService(this.meta.OBJECT, this.meta.ACTION.GET_USER_INT_MEMBERSHIPS, {}, successHandler); 
  }
  this.getById = function(id, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_INT_MEMBERSHIP_BY_ID, 
            { id: id }, 
            successHandler); 
  }
  this.insert = function(userId, startDate, fee, form, certif, rib, identity, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.INSERT, 
            {
              userId:userId,
              startDate:startDate,
              fee:fee,
              form:form,
              certif:certif,
              rib:rib,
              identity:identity
            }, 
            successHandler); 
  }
  this.update = function(id, userId, startDate, fee, form, certif, rib, identity, successHandler) {
    return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.UPDATE, 
            {
              id:id,
              userId:userId,
              startDate:startDate,
              fee:fee,
              form:form,
              certif:certif,
              rib:rib,
              identity:identity
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