const axios = require('axios')
const QueryString = require('query-string')
const endpoint = `${__BASE_API_URL}`

module.exports = {
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
  }
}