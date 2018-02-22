import React, { Component } from 'react';
import ReactDOM from 'react-dom';
const api = require('../../utils/api')
const Table = require('react-bootstrap/lib/Table')
const VoucherExpiryTypeText = require('./VoucherExpiryTypeText')

class Vouchers extends Component {
  constructor(props) {
    super(props)
    this.state = {
      vouchers: null
    }
  }

  componentDidMount() {
    api.getVouchers()
    .then(({vouchers}) => {
      this.setState({
        vouchers
      })
    })
  }

  render() {
    return (
      <div>
        {this.state.vouchers && (
          <Table>
            <thead>
              <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Usage Limit</th>
                <th>Expiry Type</th>
              </tr>
            </thead>
            <tbody>
              {this.state.vouchers.map((voucher) => (
                <tr key={voucher.id}>
                  <td>{voucher.title}</td>
                  <td>{voucher.description}</td>
                  <td>{voucher.usage_limit}</td>
                  <td>
                    <VoucherExpiryTypeText voucher={voucher} />
                  </td>
                </tr>
              ))}
            </tbody>
          </Table>
        )}
      </div>
    )
  }
}

module.exports = Vouchers