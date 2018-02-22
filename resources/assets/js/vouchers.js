
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

window.__BASE_API_URL = '/api';
const React = require('react')
const ReactDOM = require('react-dom')
const Vouchers = require('./components/vouchers/Vouchers')
const CreateVoucher = require('./components/vouchers/CreateVoucher')
import {Route, BrowserRouter, Switch} from 'react-router-dom'

ReactDOM.render(
    <BrowserRouter>
      <div>
        <Switch>
          <Route exact path='/vouchers' component={Vouchers} />
          <Route exact path='/voucher/create' component={CreateVoucher} />
        </Switch>
      </div>
    </BrowserRouter>
, document.getElementById('voucher'));