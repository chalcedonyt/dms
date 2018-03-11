import React, { Component } from 'react';
import ReactDOM from 'react-dom';

const _ = require('lodash')
const api = require('../../utils/api')
const Button = require('react-bootstrap/lib/Button')
const Col = require('react-bootstrap/lib/Col')
const ListGroup = require('react-bootstrap/lib/ListGroup')
const ListGroupItem = require('react-bootstrap/lib/ListGroupItem')
const Panel = require('react-bootstrap/lib/Panel')
const Row = require('react-bootstrap/lib/Row')

const Progress = require('../shared/Progress')

class CreateList extends Component {
  constructor(props) {
    super(props)
    this.state = {
      isLoadingSpreadsheets: false,
      isLoadingSheets: false,
      spreadsheets: null,
      selectedSpreadsheetId: null,
      sheets: null,
      selectedSheetId: null
    }
    this.loadSheets = this.loadSheets.bind(this)
    this.handleSpreadsheetSelect = this.handleSpreadsheetSelect.bind(this)
    this.handleSheetSelect = this.handleSheetSelect.bind(this)
  }

  handleSpreadsheetSelect(selectedSpreadsheetId) {
    this.setState({
      isLoadingSheets: true,
      selectedSpreadsheetId
    }, () => {
      api.getSheets(selectedSpreadsheetId)
        .then(({ sheets }) => {
          this.setState({
            isLoadingSheets: false,
            sheets
          })
        })
    })

  }

  handleSheetSelect(selectedSheetId) {
    this.setState({
      selectedSheetId
    })
  }

  loadSheets() {
    this.setState({
      isLoadingSpreadsheets: true
    }, () => {
      api.getSpreadsheets()
        .then(({ spreadsheets }) => {
          this.setState({
            spreadsheets,
            isLoadingSpreadsheets: false
          })
        })
    })
  }

  render() {
    const selectedSpreadsheetName = this.state.selectedSpreadsheetId
    ? _.find(this.state.spreadsheets, {id: this.state.selectedSpreadsheetId}).name
    : null

    const selectedSheetName = this.state.selectedSheetId
    ? _.find(this.state.sheets, {id: this.state.selectedSheetId}).name
    : null

    return (
      <div>
        <div>
          <Button bsStyle='primary' onClick={this.loadSheets}>
            Import from Google sheets
          </Button>
        </div>
        <div>
          <Row>
            <Col md={5}>
              {!Array.isArray(this.state.spreadsheets) && this.state.isLoadingSpreadsheets && (
                <Progress margin={100} size={40} inProgress={this.state.isLoadingSpreadsheets}></Progress>
              )}
              {Array.isArray(this.state.spreadsheets) && (
                <Panel bsStyle='info'>
                  <Panel.Heading>
                    <Panel.Title>Select a spreadsheet</Panel.Title>
                  </Panel.Heading>
                  <Panel.Body>
                    <ListGroup>
                      {this.state.spreadsheets.map((spreadsheet) => (
                        <ListGroupItem
                          style={{ width: '100%', textAlign: 'left' }}
                          key={spreadsheet.id}
                          bsStyle={spreadsheet.id == this.state.selectedSpreadsheetId ? 'info': null}
                          onClick={(e) => this.handleSpreadsheetSelect(spreadsheet.id)}>
                          {spreadsheet.name}
                        </ListGroupItem>
                      ))}
                    </ListGroup>
                  </Panel.Body>
                </Panel>
              )}
            </Col>
            <Col md={3}>
            {!Array.isArray(this.state.sheets) && this.state.isLoadingSheets && (
                <Progress margin={100} size={40} inProgress={this.state.isLoadingSheets}></Progress>
              )}
            {Array.isArray(this.state.sheets) && (
              <Panel bsStyle='info'>
                <Panel.Heading>
                    <Panel.Title>Select a sheet</Panel.Title>
                  </Panel.Heading>
                <Panel.Body>
                  <ListGroup>
                    {this.state.sheets.map((sheet, i) => (
                      <ListGroupItem
                        style={{ width: '100%', textAlign: 'left' }}
                        key={i}
                        bsStyle={sheet.id == this.state.selectedSheetId ? 'info': null}
                        onClick={(e) => this.handleSheetSelect(sheet.id)}>
                        {sheet.name}
                      </ListGroupItem>
                    ))}
                  </ListGroup>
                </Panel.Body>
              </Panel>
            )}
            </Col>
            {//a sheet can have id 0
              this.state.selectedSheetId != null && this.state.selectedSpreadsheetId != null && (
            <Col md={2}>
              <Panel bsStyle='info'>
                <Panel.Heading>
                  <Panel.Title>Confirm</Panel.Title>
                </Panel.Heading>
                <Panel.Body>
                  <p>Import entries {selectedSheetName} from {selectedSpreadsheetName}</p>
                  <Button
                    bsStyle='primary'
                    href={'/list/import/' + this.state.selectedSpreadsheetId + '/' + this.state.selectedSheetId}>Import
                  </Button>
                </Panel.Body>
              </Panel>
            </Col>
            )}
          </Row>
        </div>
      </div>
    )
  }
}

module.exports = CreateList