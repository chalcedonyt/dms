import React, { Component } from 'react';
import ReactDOM from 'react-dom';
const api = require('../../utils/api')

const Button = require('react-bootstrap/lib/Button')

class ValidateVoucher extends Component {
  constructor(props) {
    super(props)
    this.state = {
      voucherAssignment: null,
      validated: false
    }

    this.handleValidation = this.handleValidation.bind(this)
  }

  componentDidMount() {
    api.prevalidateVoucher(this.props.match.params.uuid)
    .then((voucherAssignment) => {
      this.setState({
        voucherAssignment
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
            {this.state.voucherAssignment.created_at}
            <h5>Name</h5>
            <p>{this.state.voucherAssignment.member.name}</p>
            <h5>Expires: </h5>
            <p>{this.state.voucherAssignment.expires_at}</p>
            {!this.state.validated && (
              <Button bsStyle='primary' onClick={this.handleValidation}>Validate</Button>
            )}
          </div>
        )}
      </div>
    )
  }
}

module.exports = ValidateVoucher