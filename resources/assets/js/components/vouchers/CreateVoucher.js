import React, { Component } from 'react';
import ReactDOM from 'react-dom';

const api = require('../../utils/api')
const Button = require('react-bootstrap/lib/Button')
const ButtonGroup = require('react-bootstrap/lib/ButtonGroup')
const Col = require('react-bootstrap/lib/Col')
const ControlLabel = require('react-bootstrap/lib/ControlLabel')
const FormGroup = require('react-bootstrap/lib/FormGroup')
const FormControl = require('react-bootstrap/lib/FormControl')
const Modal = require('react-bootstrap/lib/Modal')
const Row = require('react-bootstrap/lib/Row')

import DatePicker from 'react-datepicker';
import moment from 'moment';
import 'react-datepicker/dist/react-datepicker.css';


class CreateVoucher extends Component {
  constructor(props) {
    super(props)
    this.state = {
      title: '',
      description: '',
      expiresAt: moment().add(3, 'months'),
      expiresWeeks: 0,
      expiryOption: null, //weeks, fixed
      showDialog: false,
      usageLimit: 0,
    }
    this.createVoucher = this.createVoucher.bind(this)
    this.handleChange = this.handleChange.bind(this)
    this.handleDatepickerChange = this.handleDatepickerChange.bind(this)
    this.handleDialogClose = this.handleDialogClose.bind(this)
    this.setExpiryOption = this.setExpiryOption.bind(this)
    this.showConfirmation = this.showConfirmation.bind(this)
  }

  createVoucher() {
    var params = {
      title: this.state.title || 'Untitled Voucher',
      description: this.state.description || '',
      usage_limit: this.state.usageLimit
    }
    switch (this.state.expiryOption) {
      case 'weeks':
        params.expires_weeks = this.state.expiresWeeks
        params.expires_at = null
        break
      case 'fixed':
        params.expires_weeks = 0
        params.expires_at = this.state.expiresAt.format()
        break
      default:
        params.expires_weeks = 0
        params.expires_at = null
        break
    }
    api.createVoucher(params)
    .then(() => {
      window.location = '/vouchers'
    })
  }

  getValidationState() {
    return this.state.title.length ? null : 'warning'
  }

  handleChange(e, field) {
    this.setState({
      [field]: e.target.value
    })
  }

  handleDatepickerChange(date) {
    this.setState({
      expiresAt: date
    })
  }

  handleDialogClose() {
    this.setState({
      showDialog: false
    })
  }

  setExpiryOption(expiryOption) {
    this.setState({
      expiryOption
    })
  }

  showConfirmation() {
    this.setState({
      showDialog: true
    })
  }

  render() {
    return (
      <form>
        <h3>Details</h3>
        <FormGroup validationState={this.getValidationState()}>
          <ControlLabel>Voucher Title</ControlLabel>
          <FormControl
            type="text"
            value={this.state.title}
            placeholder="Enter the voucher title"
            onChange={(e) => this.handleChange(e, 'title')}
          />
        </FormGroup>
        <ControlLabel>Voucher Description</ControlLabel>
        <FormControl
          componentClass="textarea"
          value={this.state.description}
          placeholder="Enter a description"
          onChange={(e) => this.handleChange(e, 'description')}
        />

        <ControlLabel>Usage Limit</ControlLabel>
        <FormControl
          componentClass="select"
          placeholder="How many times can this voucher be used?"
          onChange={(e) => this.handleChange(e, 'usageLimit')}
        >
          <option value="0">No limit</option>
          {[1, 2, 3, 4, 5, 6, 7, 8, 9, 10].map((i) => (
            <option key={i} value={i}>{i} time(s)</option>
          ))}
        </FormControl>

        <h3>Expiry</h3>
        <Row>
          <Col xs={5} md={5}>
            <p>Choose one option below</p>
            <ButtonGroup>
              <Button
                bsStyle={!this.state.expiryOption ? 'info' : 'default'}
                onClick={(e) => this.setExpiryOption(null)}
              >
                No expiry
                </Button>
              <Button
                bsStyle={this.state.expiryOption == 'weeks' ? 'info' : 'default'}
                onClick={(e) => this.setExpiryOption('weeks')}
              >
                Expires X weeks after issuing
                </Button>
              <Button
                bsStyle={this.state.expiryOption == 'fixed' ? 'info' : 'default'}
                onClick={(e) => this.setExpiryOption('fixed')}
              >
                Expires on fixed date
                </Button>
            </ButtonGroup>
          </Col>
          <Col md={2} xs={2}>
            <div>
              {this.state.expiryOption == 'weeks' &&
                <div>
                  <ControlLabel>Expires in weeks (optional)</ControlLabel>
                  <FormControl
                    type="number"
                    placeholder="How many weeks will the voucher be valid for? (0 for no limit)"
                    onChange={(e) => this.handleChange(e, 'expiresWeeks')}
                    defaultValue={this.state.expiresWeeks}>
                  </FormControl>
                </div>
              }
              {this.state.expiryOption == 'fixed' &&
                <div>
                  <ControlLabel>Expiry date (optional)</ControlLabel>
                  <DatePicker
                    selected={this.state.expiresAt}
                    onChange={this.handleDatepickerChange}
                  />
                </div>
              }
            </div>
          </Col>
        </Row>
        <br />
        <Button
          bsStyle='primary'
          bsSize='large'
          onClick={this.showConfirmation}
        >Create voucher</Button>
        <Modal show={this.state.showDialog}>
          <Modal.Header>
            <Modal.Title>Confirm voucher creation</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <h4>Title</h4>
            <p>{this.state.title}</p>
            <h4>Usage</h4>
            <p>This voucher can be used {this.state.usageLimit == 0 ? 'unlimited' : this.state.usageLimit} time(s)</p>
            {this.state.expiryOption == 'weeks' && this.state.expiresWeeks > 0 &&
              <p>
                This voucher will expire {this.state.expiresWeeks} week(s) after being issued
            </p>
            }
            {this.state.expiryOption == 'fixed' && this.state.expiresAt != null &&
              <p>
                This voucher will expire on {this.state.expiresAt.format('MMM Do YYYY')}
              </p>
            }
          </Modal.Body>
          <Modal.Footer>
            <Button onClick={this.handleDialogClose}>Close</Button>
            <Button bsStyle='primary' onClick={this.createVoucher}>Create</Button>
          </Modal.Footer>
        </Modal>
      </form>
    )
  }
}

module.exports = CreateVoucher