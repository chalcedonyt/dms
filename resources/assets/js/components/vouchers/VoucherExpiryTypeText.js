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
        : voucher.expiry_type == 'Days after issuing'
        ? 'Expires ' + voucher.expires_days + ' day(s) after issuing'
        : 'No expiry'}
      </p>
    )
  }
}

module.exports = VoucherExpiryTypeText