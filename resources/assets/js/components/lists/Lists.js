import React, { Component } from 'react';
import ReactDOM from 'react-dom';

const api = require('../../utils/api')
const Button = require('react-bootstrap/lib/Button')
const Table = require('react-bootstrap/lib/Table')

class Lists extends Component {
  constructor(props) {
    super(props)
    this.state = {
      memberLists: null
    }
  }

  componentWillMount() {
    api.getLists()
      .then(({ member_lists: memberLists }) => {
        this.setState({
          memberLists
        })
      })
  }

  render() {
    return (
      <div className="container">
        {Array.isArray(this.state.memberLists) &&
          <div>
            <Table>
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Created on</th>
                  <th>View</th>
                </tr>
              </thead>
              <tbody>
                {this.state.memberLists.map((list) => (
                  <tr key={list.id}>
                    <td>
                      {list.name}
                    </td>
                    <td>
                      {list.description}
                    </td>
                    <td>
                      {list.created_at}
                    </td>
                    <td>
                      <Button bsStyle='info' href={'/list/' + list.id}>View</Button>
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

module.exports = Lists