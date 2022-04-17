import logo from './logo.svg';
import CreateBlurb  from './components/CreateBlurb.js';
import Feed from './components/Feed.js';
import Profile from './components/Profile.js';
import Login from './components/Login.js';
import Register from './components/Register.js';
import {Nav, Navbar} from 'react-bootstrap';
import 'bootstrap/dist/css/bootstrap.css';

import {Routes, Route, Link} from 'react-router-dom';

export default function App() {
  return (
    <div>
      <Navbar bg='dark' variant='dark'>
        <Navbar.Brand>Blurb</Navbar.Brand>
        <Nav className='m-auto'>
          <Nav.Link href='/feed'>Feed</Nav.Link>
          <Nav.Link href='/create'>Create a Blurb</Nav.Link>
          <Nav.Link href='/profile'>Profile</Nav.Link>
          <Nav.Link href='/login'>Login</Nav.Link>
          <Nav.Link href='/register'>Register</Nav.Link>
          
        </Nav>
      </Navbar>
      <Routes>
        <Route path='/feed' element={<Feed /> } />
        <Route path='/create' element={<CreateBlurb /> } />
        <Route path='/profile' element={<Profile /> } />
        <Route path='/login' element={<Login />} />
        <Route path='/register' element={<Register />} />
      </Routes>
    </div>
  );
}

