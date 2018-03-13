import React, { Component } from 'react';
import ReactDOM from 'react-dom';
const api = require('../../utils/api')

const Panel = require('react-bootstrap/lib/Panel')
const Button = require('react-bootstrap/lib/Button')

class ValidateVoucher extends Component {
  constructor(props) {
    super(props)
    this.state = {
      voucherAssignment: null,
      validated: false,
      validityError: null
    }

    this.handleValidation = this.handleValidation.bind(this)
  }

  componentDidMount() {
    api.prevalidateVoucher(this.props.match.params.uuid)
    .then(({voucher_assignment: voucherAssignment, validity_error: validityError}) => {
      this.setState({
        voucherAssignment,
        validityError
      })
    })
  }

  handleValidation() {
    api.validateVoucher(this.props.match.params.uuid)
    .then(({succcess, errors}) => {
      this.setState({
        validated: true
      })
    })
  }

  render() {
    return (
      <div>
        {this.state.voucherAssignment &&  (
          <div>
            {this.state.validated
            ? <h1>Voucher validated!</h1>
            : <h1>Validating voucher</h1>
          }
            <h5>Type</h5>
            {this.state.voucherAssignment.voucher.title}
            <h5>Issued on</h5>
            {this.state.voucherAssignment.created_at} - {this.state.voucherAssignment.memberList.name}
            <h5>Name</h5>
            <p>{this.state.voucherAssignment.member.name}</p>
            <h5>Expires: </h5>
            <p>{this.state.voucherAssignment.expires_at}</p>
            {!this.state.validated && !this.state.validityError && (
              <Button bsStyle='primary' onClick={this.handleValidation}>Validate</Button>
            )}
            {this.state.validityError && (
              <Panel bsStyle='warning'>
                <Panel.Heading>
                  Could not redeem voucher
                </Panel.Heading>
                <Panel.Body>
                  <p>Reason: {this.state.validityError}</p>
                </Panel.Body>
              </Panel>
            )}
          </div>
        )}
      </div>
    )
  }
}

module.exports = ValidateVoucher