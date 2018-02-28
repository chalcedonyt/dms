const axios = require('axios')
const QueryString = require('query-string')
const endpoint = `${__BASE_API_URL}`

module.exports = {
  //google sheet stuff
  getSpreadsheets: (query = null) => {
    const paramString = query ? QueryString.stringify(query) : '';
    const encodedURI = window.encodeURI(`${endpoint}/spreadsheets?${paramString}`);
    return axios.get(encodedURI)
      .then(function (response) {
        return response.data;
      });
  },

  getSheets: (spreadsheetId) => {
    const encodedURI = window.encodeURI(`${endpoint}/spreadsheets/${spreadsheetId}/sheets`);
    return axios.get(encodedURI)
    .then(({data}) => data)
  },

  getSheetData: (spreadsheetId, sheetId) => {
    const encodedURI = window.encodeURI(`${endpoint}/spreadsheets/${spreadsheetId}/${sheetId}`);
    return axios.get(encodedURI)
    .then(({data}) => data)
  },

  importData: (params) => {
    const encodedURI = window.encodeURI(`${endpoint}/member_lists`);
    return axios.post(encodedURI, params, {
      headers: {
        'Content-Type': 'application/json'
      }
    }).then(({data}) => data)
  },

  //lists
  getList: (listId) => {
    const encodedURI = window.encodeURI(`${endpoint}/member_list/${listId}`);
    return axios.get(encodedURI).then(({data}) => data)
  },

  getLists: (params = null) => {
    const encodedURI = window.encodeURI(`${endpoint}/member_lists`);
    return axios.get(encodedURI).then(({data}) => data)
  },

  syncWithMailchimp: (listId) => {
    const encodedURI = window.encodeURI(`${endpoint}/member_list/${listId}/mailchimp-sync`);
    return axios.post(encodedURI).then(({data}) => data)
  },

  //vouchers
  createVoucher: (params) => {
    const encodedURI = window.encodeURI(`${endpoint}/vouchers`);
    return axios.post(encodedURI, params, {
      headers: {
        'Content-Type': 'application/json'
      }
    }).then(({data}) => data)
  },

  getVouchers: (params = null) => {
    const encodedURI = window.encodeURI(`${endpoint}/vouchers`);
    return axios.get(encodedURI).then(({data}) => data)
  },

  assignVoucher: (listId, memberIds, voucher) => {
    const encodedURI = window.encodeURI(`${endpoint}/member_list/${listId}/assign-voucher`);
    return axios.post(encodedURI, {
      member_ids: memberIds,
      voucher_id: voucher.id
    }, {
      headers: {
        'Content-Type': 'application/json'
      }
    })
  },

  prevalidateVoucher: (uuid) => {
    const encodedURI = window.encodeURI(`${endpoint}/voucher-validate/${uuid}`);
    return axios.get(encodedURI).then(({data}) => data)
  },

  validateVoucher: (uuid) => {
    const encodedURI = window.encodeURI(`${endpoint}/voucher-validate/${uuid}`);
    return axios.post(encodedURI).then(({data}) => data)
  },

  createAdmin: (params) => {
    const encodedURI = window.encodeURI(`${endpoint}/user`);
    return axios.post(encodedURI, params, {
      headers: {
        'Content-Type': 'application/json'
      }
    }).then(({data}) => data)
  },

  getAdmins: () => {
    const encodedURI = window.encodeURI(`${endpoint}/users`);
    return axios.get(encodedURI).then(({data}) => data)
  },

  disableAdmin: (id) => {
    const encodedURI = window.encodeURI(`${endpoint}/user/${id}`);
    return axios.delete(encodedURI).then(({data}) => data)
  },

  updateAdmin: (id, params) => {
    const encodedURI = window.encodeURI(`${endpoint}/user/${id}`);
    return axios.put(encodedURI, params, {
      headers: {
        'Content-Type': 'application/json'
      }
    }).then(({data}) => data)
  },

  getMembers: () => {
    const encodedURI = window.encodeURI(`${endpoint}/members`);
    return axios.get(encodedURI).then(({data}) => data)
  }
}