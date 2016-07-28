// ----------------------- TICKET INTERFACE SERVICES CLASS ----------------------------------

var TicketServicesInterface = new function () {

    this.meta = {
        // --- (object)
        OBJECT: 'ticket',
        // --- (actions)
        ACTION: {
            GET_TICKET_BY_ID: 'byidt',
            GET_TICKET_BY_STATUS: 'bystt',
            GET_STATUS_BY_ID: 'byids',
            GET_CATEGO_BY_ID: 'byidc',
            GET_ALL_TICKETS: 'allt',
            GET_USER_TICKETS: 'allut',
            GET_ALL_STATUSES: 'alls',
            GET_ALL_CATEGOS: 'allc',
            INSERT: 'insert',
            UPDATE: 'update',
            NEXT_STATUS: 'nexts',
            DELETE: 'delete',
            ARCHIVE: 'archive'
        }
    };

    this.getAll = function (successHandler) {
        return DoleticServicesInterface.callService(this.meta.OBJECT, this.meta.ACTION.GET_ALL_TICKETS, {}, successHandler);
    };
    this.getUserTickets = function (successHandler) {
        return DoleticServicesInterface.callService(this.meta.OBJECT, this.meta.ACTION.GET_USER_TICKETS, {}, successHandler);
    };
    this.getTicketsByStatus = function (statusId, successHandler) {
        return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_TICKET_BY_STATUS,
            {statusId: statusId},
            successHandler);
    };
    this.getAllStatuses = function (successHandler) {
        return DoleticServicesInterface.callService(this.meta.OBJECT, this.meta.ACTION.GET_ALL_STATUSES, {}, successHandler);
    };
    this.getAllCategories = function (successHandler) {
        return DoleticServicesInterface.callService(this.meta.OBJECT, this.meta.ACTION.GET_ALL_CATEGOS, {}, successHandler);
    };
    this.getById = function (id, successHandler) {
        return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_TICKET_BY_ID,
            {id: id},
            successHandler);
    };
    this.getStatusById = function (id, successHandler) {
        return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_STATUS_BY_ID,
            {id: id},
            successHandler);
    };
    this.getCategoryById = function (id, successHandler) {
        return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.GET_CATEGO_BY_ID,
            {id: id},
            successHandler);
    };
    this.insert = function (receiverId, subject, categoryId, data, successHandler) {
        return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.INSERT,
            {
                receiverId: receiverId,
                subject: subject,
                categoryId: categoryId,
                data: data
            },
            successHandler);
    };
    this.update = function (id, receiverId, subject, categoryId, data, statusId, successHandler) {
        return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.UPDATE,
            {
                id: id,
                receiverId: receiverId,
                subject: subject,
                categoryId: categoryId,
                data: data,
                statusId: statusId
            },
            successHandler);
    };
    this.nextTicketStatus = function (id, successHandler) {
        return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.NEXT_STATUS,
            {
                id: id
            },
            successHandler);
    };
    this.delete = function (id, successHandler) {
        return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.DELETE,
            {id: id},
            successHandler);
    };
    this.archive = function (id, successHandler) {
        return DoleticServicesInterface.callService(
            this.meta.OBJECT, this.meta.ACTION.DELETE,
            {id: id},
            successHandler);
    }

};