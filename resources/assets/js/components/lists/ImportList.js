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
      items: null
    }
    this.handleHeaderChange = this.handleHeaderChange.bind(this)
    this.handleItemChange = this.handleItemChange.bind(this)
    this.doImport = this.doImport.bind(this)
  }

  componentWillMount() {
    api.getSheetData(this.props.match.params.spreadsheetId, this.props.match.params.sheetId)
      .then(({ headers, special_columns, items, suggested_title }) => {
        const parsedHeaders = headers.map((header) => {
          header.isChecked = true
          return header
        })

        const parsedItems = items.map((item) => {
          item.isChecked = true
          return item
        })
        this.setState({
          headers: parsedHeaders,
          specialColumns: special_columns,
          items: parsedItems,
          listTitle: suggested_title
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
    const attrsToCreate = this.state.headers
    .map((header, i) => {
      header.offset = i
      return header
    })
    .filter((header) => header.isChecked)

    const itemsToCreate = this.state.items
    .filter((item) => item.isChecked)
    .map((item) => {
      return item.filter((col, i) => {
        return _.find(attrsToCreate, {offset: i})
      })
    })

    console.log("Creating attrs %O and items %O", attrsToCreate, itemsToCreate)
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
                  {this.state.headers.map((header, i) => (
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
                            value={i}
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
                    {this.state.headers.map((header, j) => (
                      <td
                        key={j}
                        style={header.isChecked ? {} : { backgroundColor: 'lightgrey' }}
                      >
                        {item[j]}
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