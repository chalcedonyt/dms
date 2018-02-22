import React, { Component } from 'react';
import ReactDOM from 'react-dom';
const Alert = require('react-bootstrap/lib/Alert')
const Button = require('react-bootstrap/lib/Button')

class AlertDismissable extends Component {
  constructor(props, context) {
    super(props, context);

    this.handleDismiss = this.handleDismiss.bind(this);
    this.state = {
      show: false
    };
  }

  componentWillReceiveProps(props) {
    if (props.errors) {
      this.setState({
        show: true
      })
    }
  }

  handleDismiss() {
    this.setState({ show: false });
  }

  render() {
    if (this.state.show) {
      return (
        <Alert bsStyle="danger" onDismiss={this.handleDismiss}>
          <h4>Error</h4>
          <ul>
            {this.props.errors.map((error, i) => (
              <li key={i}>{error}</li>
            ))}
          </ul>
          <p>
            <Button onClick={this.handleDismiss}>OK</Button>
          </p>
        </Alert>
      );
    } else {
      return (<div></div>)
    }
  }
}

module.exports = AlertDismissable