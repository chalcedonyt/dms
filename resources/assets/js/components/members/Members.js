import React, { Component } from 'react';
import ReactDOM from 'react-dom';

const api = require('../../utils/api')
const Button = require('react-bootstrap/lib/Button')
const Label = require('react-bootstrap/lib/Label')
const ListGroup = require('react-bootstrap/lib/ListGroup')
const ListGroupItem = require('react-bootstrap/lib/ListGroupItem')
const OverlayTrigger = require('react-bootstrap/lib/OverlayTrigger')
const Popover = require('react-bootstrap/lib/Popover')
const Table = require('react-bootstrap/lib/Table')

class Members extends Component {
  constructor(props) {
    super(props)
    this.state = {
      members: null
    }
  }

  componentWillMount() {
    api.getMembers()
      .then(({ members }) => {
        this.setState({
          members
        })
      })
  }

  render() {
    return (
      <div className="container">
        {Array.isArray(this.state.members) &&
          <div>
            <Table>
              <thead>
                <tr>
                  <th></th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Contact</th>
                  <th>Lists</th>
                  <th>Added on</th>
                </tr>
              </thead>
              <tbody>
                {this.state.members.map((member, i) => (
                  <tr key={member.id}>
                    <td>{i+1}</td>
                    <td>
                      {member.name}
                    </td>
                    <td>
                      {member.email}
                    </td>
                    <td>
                      {member.contactno}
                    </td>
                    <td>
                      <OverlayTrigger
                        trigger="click"
                        overlay={(
                        <Popover
                          id={member.name + ' tooltip'}
                          placement='left'
                          style={{maxWidth: '400px'}}
                        >
                          <ListGroup>
                            {member.memberLists.map((list) => (
                              <ListGroupItem key={list.id}>
                              <Button
                                bsStyle='link'
                                href={`/list/${list.id}`}>{list.name}</Button>
                              </ListGroupItem>
                            ))}
                          </ListGroup>
                        </Popover>
                      )}>
                      <a href="javascript:;">
                        {member.memberLists.length > 1
                        ? member.memberLists.length + ' list(s)'
                        : member.memberLists.length + ' list'
                        }
                      </a>
                      </OverlayTrigger>
                    </td>
                    <td>
                      {member.created_at}
                    </td>
                    <td>
                    </td>
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

module.exports = Members