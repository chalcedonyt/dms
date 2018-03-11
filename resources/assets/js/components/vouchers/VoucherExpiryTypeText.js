import React, { Component } from 'react';
import ReactDOM from 'react-dom';

class VoucherExpiryTypeText extends Component {
  constructor(props) {
    super(props)
  }

  render() {
    const voucher = this.props.voucher
    return (
      <p>
        {voucher.expiry_type == 'Fixed'
        ? 'Expires on ' + voucher.expires_at
        : voucher.expiry_type == 'Weeks after issuing'
        ? 'Expires ' + voucher.expires_weeks + ' week(s) after issuing'
        : 'No expiry'}
      </p>
    )
  }
}

module.exports = VoucherExpiryTypeText