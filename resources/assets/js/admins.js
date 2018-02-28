
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
const CreateAdmin = require('./components/admins/CreateAdmin')
const Admins = require('./components/admins/Admins')
import {Route, BrowserRouter, Switch} from 'react-router-dom'

ReactDOM.render(
    <BrowserRouter>
      <div>
        <Switch>
          <Route exact path='/admins' component={Admins} />
          <Route exact path='/admin/create' component={CreateAdmin} />
        </Switch>
      </div>
    </BrowserRouter>
, document.getElementById('admin'));