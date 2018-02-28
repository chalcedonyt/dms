import React, { Component } from 'react';
import ReactDOM from 'react-dom';

const api = require('../../utils/api')
const Button = require('react-bootstrap/lib/Button')
const ControlLabel = require('react-bootstrap/lib/ControlLabel')
const FormControl = require('react-bootstrap/lib/FormControl')
const FormGroup = require('react-bootstrap/lib/FormGroup')

class CreateAdmin extends Component {
  constructor(props) {
    super(props)
    this.state = {
      email: '',
      role_id: 1
    }
    this.handleEmailChange = this.handleEmailChange.bind(this)
    this.handleRoleChange = this.handleRoleChange.bind(this)
    this.handleCreate = this.handleCreate.bind(this)
  }

  getValidationState() {
    if (this.state.email.indexOf('gmail.com') !== 1)
      return 'warning'
    return null
  }

  handleEmailChange(e) {
    this.setState({
      email: e.target.value
    })
  }

  handleRoleChange(e) {
    this.setState({
      role_id: e.target.value
    })
  }

  handleCreate() {
    const params = {
      email: this.state.email,
      role_id: this.state.role_id
    }
    api.createAdmin(params)
    .then(() => {
      location.href='/admins'
    })
  }

  render() {
    return (
      <div className="container">
        <form>
          <FormGroup validationState={this.getValidationState()}>
            <h2>Enter the Gmail address</h2>
            <p>
              Fields like name and avatar URL will be filled in after Google sign-in
            </p>
            <FormControl
              type="email"
              value={this.state.email}
              placeholder="someone@gmail.com"
              onChange={this.handleEmailChange}
            />
          </FormGroup>
          <FormGroup>
            <h2>Role</h2>
            <FormControl
              onChange={this.handleRoleChange}
              componentClass="select"
              placeholder="Select role"
              id="select-role"
            >
              <option value={1}>Normal (Can redeem voucher)</option>
              <option value={2}>Admin (Can access this and add other users)</option>
            </FormControl>
          </FormGroup>
          <Button bsStyle='primary' onClick={this.handleCreate}>Create User</Button>
        </form>
      </div>
    )
  }
}

module.exports = CreateAdmin