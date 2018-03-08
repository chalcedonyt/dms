import React, { Component } from 'react';
import ReactDOM from 'react-dom';

const api = require('../../utils/api')
const Table = require('react-bootstrap/lib/Table')

class VoucherRedemptions extends Component {
  constructor(props) {
    super(props)
    this.state = {
      voucher: null,
      redemptions: []
    }
  }

  componentDidMount() {
    api.getVoucherRedemptions(this.props.match.params.voucherId)
    .then(({voucher, redemptions}) => {
      this.setState({
        voucher,
        redemptions
      })
    });
  }

  render() {
    return (
      <div>
        {this.state.voucher
        && <h3>Redemptions for {this.state.voucher.title}</h3>}
        <Table>
          <thead>
            <tr>
              <th>
                Validated on
              </th>
              <th>
                Member
              </th>
              <th>
                Validated by
              </th>
            </tr>
          </thead>
          <tbody>
            {this.state.redemptions.map((redemption, i) => (
              <tr key={i}>
                <td>{redemption.created_at}</td>
                <td>{redemption.member.name}</td>
                <td>{redemption.validator.name}</td>
              </tr>
            ))}
          </tbody>
        </Table>
      </div>
    )
  }
}

module.exports = VoucherRedemptions