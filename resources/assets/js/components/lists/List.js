import React, { Component } from 'react';
import ReactDOM from 'react-dom';

const api = require('../../utils/api')
const Button = require('react-bootstrap/lib/Button')
const Table = require('react-bootstrap/lib/Table')

class List extends Component {
  constructor(props) {
    super(props)
    this.state = {
      description: null,
      createdAt: null,
      members: null,
      name: null,
      hasMailchimpList: null,
    }

    this.handleSync = this.handleSync.bind(this)
  }

  componentWillMount() {
    api.getList(this.props.match.params.listId)
      .then(({name, description, members, created_at: createdAt, mailchimp_list_id}) => {
        this.setState({
          description,
          members,
          name,
          createdAt,
          hasMailchimpList: mailchimp_list_id || null
        })
      })
  }

  handleSync() {
    api.syncWithMailchimp(this.props.match.params.listId)
    .then((data) => {
      this.setState({
        hasMailchimpList: true
      })
      console.log(data)
    })
  }

  render() {
    return (
      <div className="container">
        <h1>{this.state.name}</h1>
        <Button onClick={this.handleSync}>{this.state.hasMailchimpList ? 'Update Mailchimp List' : 'Create Mailchimp List'}</Button>
        {Array.isArray(this.state.members) && (
          <Table>
            <thead>
              <tr>
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