import React, { Component } from 'react';
import ReactDOM from 'react-dom';

const api = require('../utils/api')
const Button = require('react-bootstrap/lib/Button')

class CreateList extends Component {
  constructor(props) {
    super(props)
    this.state = {
      googleSheets: null
    }
    this.loadSheets = this.loadSheets.bind(this)
  }

  loadSheets() {

  }

  render() {
    return (
      <div>
        <Button onClick={this.loadSheets}>Import from Google sheets</Button> or <Button>Manual entry</Button>
      </div>
    )
  }
}

module.exports = CreateList