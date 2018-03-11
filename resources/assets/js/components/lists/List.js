import React, { Component } from 'react';
import ReactDOM from 'react-dom';

const api = require('../../utils/api')

const AlertDismissable = require('../shared/AlertDismissable')
const Button = require('react-bootstrap/lib/Button')
const ButtonGroup = require('react-bootstrap/lib/ButtonGroup')
const Col = require('react-bootstrap/lib/Col')
const DropdownButton = require('react-bootstrap/lib/DropdownButton')
const Label = require('react-bootstrap/lib/Label')
const ListGroup = require('react-bootstrap/lib/ListGroup')
const ListGroupItem = require('react-bootstrap/lib/ListGroupItem')
const Modal = require('react-bootstrap/lib/Modal')
const MenuItem = require('react-bootstrap/lib/MenuItem')
const Row = require('react-bootstrap/lib/Row')
const Table = require('react-bootstrap/lib/Table')

const VoucherExpiryTypeText = require('../vouchers/VoucherExpiryTypeText')
const Progress = require('../shared/Progress')

class List extends Component {
  constructor(props) {
    super(props)
    this.state = {
      createdAt: null,
      description: null,
      errors: null,
      hasMailchimpList: null,
      isSyncing: false,
      members: null,
      name: null,
      selectedMemberIds: [],
      selectedVoucher: null,
      showVoucherModal: false,
      vouchers: []
    }

    this.handleSync = this.handleSync.bind(this)
    this.handleVoucherModalClose = this.handleVoucherModalClose.bind(this)
    this.handleVoucherModalConfirm = this.handleVoucherModalConfirm.bind(this)
    this.handleVoucherSelect = this.handleVoucherSelect.bind(this)
    this.loadList = this.loadList.bind(this)
    this.toggleSelect = this.toggleSelect.bind(this)
    this.toggleSelectAll = this.toggleSelectAll.bind(this)
  }

  componentWillMount() {
    this.loadList()

    api.getVouchers()
    .then(({ vouchers }) => {
      this.setState({
        vouchers
      })
    });
  }

  loadList() {
    api.getList(this.props.match.params.listId)
      .then(({ name, description, members, created_at: createdAt, mailchimp_list_id }) => {
        this.setState({
          description,
          members,
          name,
          createdAt,
          hasMailchimpList: mailchimp_list_id || null
        })
      })
  }

  toggleSelect(e) {
    const val = parseInt(e.target.value)
    this.state.selectedMemberIds.includes(val)
      ? this.setState({
        selectedMemberIds: _.without(this.state.selectedMemberIds, val)
      })
      : this.setState({
        selectedMemberIds: _.concat(this.state.selectedMemberIds, [val])
      })
  }

  handleSync() {
    this.setState({
      isSyncing: true
    }, () => {
      api.syncWithMailchimp(this.props.match.params.listId)
        .then(({ success, errors }) => {
          this.setState({
            errors,
            hasMailchimpList: true,
            isSyncing: false
          })
        })
    })
  }

  handleVoucherModalClose() {
    this.setState({
      selectedVoucher: null,
      showVoucherModal: false
    })
  }

  handleVoucherModalConfirm() {
    api.assignVoucher(this.props.match.params.listId, this.state.selectedMemberIds, this.state.selectedVoucher)
    .then(({ success, errors}) => {
      this.setState({
        selectedVoucher: null,
        showVoucherModal: false
      }, () => {
        this.loadList()
      })
    })
  }

  handleVoucherSelect(voucher) {
    this.setState({
      selectedVoucher: voucher,
      showVoucherModal: true
    })
  }

  toggleSelectAll(e) {
    if (!e.target.checked) {
      this.setState({
        selectedMemberIds: []
      })
    } else {
      this.setState({
        selectedMemberIds: _.map(this.state.members, 'id')
      })
    }
  }

  render() {
    return (
      <div className="container">
        <h1>{this.state.name}</h1>
        <AlertDismissable errors={this.state.errors} />

        <h2>Actions</h2>
        <Row>
          <Col md={2} xs={2}>
            <ButtonGroup>
              <Button
                onClick={this.handleSync}
                disabled={this.state.isSyncing}
              >{this.state.hasMailchimpList ? 'Update Mailchimp List' : 'Create Mailchimp List'}
              </Button>
            </ButtonGroup>
          </Col>
          <Col md={2} xs={2}>
            <Progress inProgress={this.state.isSyncing} size={20} />
          </Col>
        </Row>

        <h2>List Members</h2>
        {this.state.members &&
            <p>There are {this.state.members.length} people in this list &nbsp;
            {this.state.selectedMemberIds.length > 0 &&
                <strong>({this.state.selectedMemberIds.length} selected)</strong>
            }
            </p>
        }
        {this.state.selectedMemberIds.length > 0 &&
        <div>
          <h5>With selected:</h5>
          <DropdownButton
            title="Choose a voucher to assign"
            id={`dropdown-basic`}
            >
            {this.state.vouchers.map((v) => (
              <MenuItem
                key={v.id}
                eventKey={v.id}
                onClick={(e) => this.handleVoucherSelect(v)}
              >{v.title}</MenuItem>
            ))}
          </DropdownButton>
          {this.state.selectedVoucher &&
            <Modal show={this.state.showVoucherModal}>
              <Modal.Header>
                <Modal.Title>Assign {this.state.selectedVoucher.title} to {this.state.selectedMemberIds.length} member(s)</Modal.Title>
              </Modal.Header>
              <Modal.Body>
                <h4>Voucher:</h4>
                <p>{this.state.selectedVoucher.title}</p>
                <h4>Expiry type</h4>
                <VoucherExpiryTypeText voucher={this.state.selectedVoucher} />

                <h4>Selected members</h4>
                <ListGroup>
                {this.state.members.filter((m) => this.state.selectedMemberIds.includes(m.id))
                .map((m, i) => (
                  <ListGroupItem key={i}>{m.name} ({m.email})</ListGroupItem>
                ))}
                </ListGroup>
              </Modal.Body>
              <Modal.Footer>
                <Button onClick={this.handleVoucherModalClose}>Cancel</Button>
                <Button bsStyle='primary' onClick={this.handleVoucherModalConfirm}>Confirm</Button>
              </Modal.Footer>
            </Modal>
          }
        </div>
        }
        {Array.isArray(this.state.members) && (
          <Table>
            <thead>
              <tr>
                <th>
                  <input
                    onChange={this.toggleSelectAll}
                    type="checkbox"
                    checked={this.state.members.length > 0 && this.state.selectedMemberIds.length == this.state.members.length}
                  />
                </th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                {this.state.members[0].attributes.map((attr, i) => (
                  <th key={i}>{attr.attribute_name}</th>
                ))}
                <th>Voucher</th>
              </tr>
            </thead>
            <tbody>
              {this.state.members.map((member, i) => (
                <tr key={i}>
                  <td>
                    <input
                      type="checkbox"
                      onChange={this.toggleSelect}
                      value={parseInt(member.id)}
                      checked={this.state.selectedMemberIds.includes(parseInt(member.id))}
                    />
                  </td>
                  <td>{member.name}</td>
                  <td>{member.email}</td>
                  <td>{member.contactno}</td>
                  {member.attributes.map((attr, i) => (
                    <td key={i}>{attr.value}</td>
                  ))}
                  <td>
                  {member.voucher_assignment != null && (
                    <Label bsStyle='info'>{member.voucher_assignment.voucher.title}</Label>
                  )}
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

module.exports = List