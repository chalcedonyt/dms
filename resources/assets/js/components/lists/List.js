import React, { Component } from 'react';
import ReactDOM from 'react-dom';

const api = require('../../utils/api')
const Button = require('react-bootstrap/lib/Button')
const ButtonGroup = require('react-bootstrap/lib/ButtonGroup')
const Col = require('react-bootstrap/lib/Col')
const Row = require('react-bootstrap/lib/Row')
const Table = require('react-bootstrap/lib/Table')
const AlertDismissable = require('../shared/AlertDismissable')

class List extends Component {
  constructor(props) {
    super(props)
    this.state = {
      createdAt: null,
      description: null,
      errors: null,
      hasMailchimpList: null,
      members: null,
      name: null,
      selectedMembers: []
    }

    this.handleSync = this.handleSync.bind(this)
    this.toggleSelect = this.toggleSelect.bind(this)
    this.toggleSelectAll = this.toggleSelectAll.bind(this)
  }

  componentWillMount() {
    api.getList(this.props.match.params.listId)
      .then(({ name, description, members, created_at: createdAt, mailchimp_list_id }) => {
        this.setState({
          description,
          members,
          name,
          createdAt,
          hasMailchimpList: mailchimp_list_id || null
        })
      })
  }

  toggleSelect(e) {
    const val = parseInt(e.target.value)
    this.state.selectedMembers.includes(val)
      ? this.setState({
        selectedMembers: _.without(this.state.selectedMembers, val)
      })
      : this.setState({
        selectedMembers: _.concat(this.state.selectedMembers, [val])
      })
  }

  handleSync() {
    api.syncWithMailchimp(this.props.match.params.listId)
      .then(({ success, errors }) => {
        this.setState({
          errors,
          hasMailchimpList: true,
        })
      })
  }

  toggleSelectAll(e) {
    if (!e.target.checked) {
      this.setState({
        selectedMembers: []
      })
    } else {
      this.setState({
        selectedMembers: _.map(this.state.members, 'id')
      })
    }
  }

  render() {
    return (
      <div className="container">
        <h1>{this.state.name}</h1>
        <AlertDismissable errors={this.state.errors} />

        <h2>Actions</h2>
        <ButtonGroup>
          <Button onClick={this.handleSync}>{this.state.hasMailchimpList ? 'Update Mailchimp List' : 'Create Mailchimp List'}</Button>
          <Button>Create a Voucher</Button>
        </ButtonGroup>

        <h2>List Members</h2>
        {this.state.members &&
            <p>There are {this.state.members.length} people in this list &nbsp;
            {this.state.selectedMembers.length > 0 &&
                <strong>({this.state.selectedMembers.length} selected)</strong>
            }
            </p>
        }
        {Array.isArray(this.state.members) && (
          <Table>
            <thead>
              <tr>
                <th>
                  <input
                    onChange={this.toggleSelectAll}
                    type="checkbox"
                    checked={this.state.members.length > 0 && this.state.selectedMembers.length == this.state.members.length}
                  />
                </th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                {this.state.members[0].attributes.map((attr, i) => (
                  <th key={i}>{attr.attribute_name}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {this.state.members.map((member, i) => (
                <tr key={i}>
                  <td>
                    <input
                      type="checkbox"
                      onChange={this.toggleSelect}
                      value={parseInt(member.id)}
                      checked={this.state.selectedMembers.includes(parseInt(member.id))}
                    />
                  </td>
                  <td>{member.name}</td>
                  <td>{member.email}</td>
                  <td>{member.contactno}</td>
                  {member.attributes.map((attr, i) => (
                    <td key={i}>{attr.value}</td>
                  ))}
                </tr>
              ))}
            </tbody>
          </Table>
        )}
      </div>
    )
  }
}

module.exports = List