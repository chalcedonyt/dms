import React, { Component } from 'react';
import ReactDOM from 'react-dom';

const _ = require('lodash')
const api = require('../../utils/api')
const Button = require('react-bootstrap/lib/Button')
const Col = require('react-bootstrap/lib/Col')
const Label = require('react-bootstrap/lib/Label')
const ListGroup = require('react-bootstrap/lib/ListGroup')
const ListGroupItem = require('react-bootstrap/lib/ListGroupItem')
const Panel = require('react-bootstrap/lib/Panel')
const Row = require('react-bootstrap/lib/Row')
const Table = require('react-bootstrap/lib/Table')

class ImportList extends Component {
  constructor(props) {
    super(props)
    this.state = {
      headers: null,
      specialColumns: null,
      items: null,
      listTitle: ''
    }
    this.handleHeaderChange = this.handleHeaderChange.bind(this)
    this.handleItemChange = this.handleItemChange.bind(this)
    this.doImport = this.doImport.bind(this)
  }

  componentWillMount() {
    api.getSheetData(this.props.match.params.spreadsheetId, this.props.match.params.sheetId)
      .then(({ headers, special_columns: specialColumns, items, suggested_title: listTitle }) => {
        const parsedHeaders = headers.map((header, i) => {
          header.isChecked = true
          header.offset = i
          return header
        })

        const parsedItems = items.map((item, i) => {
          //assign offsets to every column
          return {
            attributes: item.map((col, i) => {
              return {
                offset: i,
                value: col,
              }
            }),
            isChecked: true
          }
        })

        console.log("Parsed items are %O, headers are %O", parsedItems, parsedHeaders)
        this.setState({
          headers: parsedHeaders,
          specialColumns,
          items: parsedItems,
          listTitle
        })
      })
  }

  handleHeaderChange(e) {
    var headers = this.state.headers;
    headers[e.target.value].isChecked = !headers[e.target.value].isChecked;
    this.setState({
      headers
    })
  }

  handleItemChange(e) {
    var items = this.state.items;
    items[e.target.value].isChecked = !items[e.target.value].isChecked;
    this.setState({
      items
    })
  }

  doImport() {
    const allAttrs = this.state.headers
    .map((header, i) => {
      header.offset = i
      return header
    })
    .filter((header) => header.isChecked)

    const attrs = allAttrs.filter((attr) => attr.special_type == null)
    const specialAttrs = allAttrs.filter((attr) => attr.special_type != null)
    .map((attr) => {
      attr.special_type = attr.special_type.toLowerCase()
      return attr
    })
    console.log("Special attrs are %O", specialAttrs)
    const itemsToCreate = this.state.items
    .filter((item) => item.isChecked)
    .map((item, i) => {
      const specialItemAttrs = item.attributes.filter((attr) => {
        return _.map(specialAttrs, 'offset').includes(attr.offset)
      })

      return {
        attributes: item.attributes.filter((attr) => {
          return _.map(attrs, 'offset').includes(attr.offset)
        }),
        special: {
          email: _.find(specialItemAttrs, {offset: _.find(specialAttrs, {special_type: 'email'}).offset}).value,
          contact: _.find(specialItemAttrs, {offset: _.find(specialAttrs, {special_type: 'contact'}).offset}).value,
          name: _.find(specialItemAttrs, {offset: _.find(specialAttrs, {special_type: 'name'}).offset}).value,
        }
      }
    })
    const params = {
      list_title: this.state.listTitle,
      attributes: attrs,
      members: itemsToCreate
    }
    api.importData(params)
    .then(({id}) => {
      window.location = '/list/' + id
    })
  }

  render() {
    return (
      <div className="container">
        {Array.isArray(this.state.headers) &&
          <div>
            <div>
              <div className='pull-left'>
                <p>Import into a new list called &nbsp;
                  <input size="60" type="text" defaultValue={this.state.listTitle} />
                </p>
              </div>
              <div className='pull-left'>
                <Button bsStyle='primary' onClick={this.doImport}>Import</Button>
              </div>
            </div>
            <Table>
              <thead>
                <tr>
                  <th></th>
                  {this.state.headers.map((header) => (
                    <th key={header.name} style={header.isChecked ? {} : { backgroundColor: 'lightgrey' }}>
                      {header.special_type
                        ? <div>
                          <Label bsStyle='info'>{header.special_type}</Label>
                        </div>
                        : <div>
                          <input
                            type="checkbox"
                            defaultChecked={header.isChecked}
                            onChange={this.handleHeaderChange}
                            value={header.offset}
                          />
                        </div>
                      }
                    </th>
                  ))}
                </tr>
                <tr>
                  <th></th>
                  {this.state.headers.map((header) => (
                    <th key={header.name} style={header.isChecked ? {} : { backgroundColor: 'lightgrey' }}>
                      {header.name}
                    </th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {this.state.items.map((item, i) => (
                  <tr key={i} style={item.isChecked ? {} : { backgroundColor: 'lightgrey' }}>
                    <td>
                      <input
                        type="checkbox"
                        value={i}
                        defaultChecked={item.isChecked}
                        onChange={this.handleItemChange}
                      />
                    </td>
                    {this.state.headers.map((header) => (
                      <td
                        key={header.offset}
                        style={header.isChecked ? {} : { backgroundColor: 'lightgrey' }}
                      >
                        {_.find(item.attributes, {offset: header.offset})
                        ? _.find(item.attributes, {offset: header.offset}).value
                        : null}
                      </td>
                    ))}
                  </tr>
                ))}
              </tbody>
            </Table>
          </div>
        }
      </div>
    )
  }
}

module.exports = ImportList