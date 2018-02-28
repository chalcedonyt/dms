import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';

const api = require('../../utils/api')
const Button = require('react-bootstrap/lib/Button')
const ButtonGroup = require('react-bootstrap/lib/ButtonGroup')
const Modal = require('react-bootstrap/lib/Modal')
const Table = require('react-bootstrap/lib/Table')

class EditAdminDialog extends Component {
  constructor(props) {
    super(props)
    this.state = {
      show: false
    }
    this.handleHide = this.handleHide.bind(this)
    this.handleDisable = this.handleDisable.bind(this)
    this.handleRoleChange = this.handleRoleChange.bind(this)
  }

  componentWillReceiveProps(nextProps) {
    this.setState({
      show: nextProps.show
    })
  }

  handleHide() {
    this.setState({
      show: false
    })
  }

  handleDisable() {
    if (confirm('Really disable this user?')) {
      api.disableAdmin(this.props.admin.id)
        .then(() => {
          window.location.reload()
        })
    }
  }

  handleRoleChange(role) {
    const params = {
      role_id: role == 'admin' ? 2 : 1
    }
    api.updateAdmin(this.props.admin.id, params)
      .then(() => {
        window.location.reload()
      })
  }

  render() {
    return (
      <Modal
        show={this.state.show}
        aria-labelledby="contained-modal-title"
      >
        <Modal.Header>
          <Modal.Title id="contained-modal-title">
            Edit user entry for {this.props.admin && this.props.admin.name}
          </Modal.Title>
        </Modal.Header>
        {this.props.admin &&
          <Modal.Body>
            <h5>Name</h5>
            <p>{this.props.admin.name}</p>
            <h5>Email</h5>
            <p>{this.props.admin.email}</p>
            <h5>Actions</h5>
            <ButtonGroup>
              <Button bsStyle='danger' onClick={this.handleDisable}>Disable user</Button>
              {
                this.props.admin.role_name == 'Admin'
                  ? <Button bsStyle='info' onClick={(e) => this.handleRoleChange('normal')}>Change user to non-admin</Button>
                  : <Button bsStyle='info' onClick={(e) => this.handleRoleChange('admin')}>Change user to admin</Button>
              }
            </ButtonGroup>
          </Modal.Body>
        }
        <Modal.Footer>
          <Button onClick={this.handleHide}>Close</Button>
        </Modal.Footer>
      </Modal>
    )
  }
}

class Admins extends Component {
  constructor(props) {
    super(props)
    this.state = {
      admins: null,
      selectedAdmin: null,
      showModal: false
    }
    this.handleModalShow = this.handleModalShow.bind(this)
  }

  componentWillMount() {
    api.getAdmins()
      .then(({ users: admins }) => {
        this.setState({
          admins
        })
      })
  }

  handleModalShow(admin) {
    this.setState({
      selectedAdmin: admin,
      showModal: true
    })
  }

  render() {
    return (
      <div className="container">
        {Array.isArray(this.state.admins) &&
          <div>
            <Table>
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                {this.state.admins.map((admin) => (
                  <tr key={admin.id}>
                    <td>
                      {admin.name}
                    </td>
                    <td>
                      {admin.email}
                    </td>
                    <td>
                      {admin.role_name}
                    </td>
                    <td>
                      <Button bsStyle='default' onClick={(e) => this.handleModalShow(admin)}>Edit</Button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </Table>
          </div>
        }
        <EditAdminDialog admin={this.state.selectedAdmin} show={this.state.showModal} />
      </div>
    )
  }
}

module.exports = Admins