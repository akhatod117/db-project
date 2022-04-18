import logo from './logo.svg';
import CreateBlurb  from './components/CreateBlurb.js';
import Feed from './components/Feed.js';
import Profile from './components/Profile.js';
import Login from './components/Login.js';
import Register from './components/Register.js';
import {Nav, Navbar} from 'react-bootstrap';
import {BrowserRouter, Route, Routes, Redirect} from 'react-router-dom';
import React from 'react';
import 'bootstrap/dist/css/bootstrap.css';
import CommentFeed from './components/CommentFeed.js';


export default function App() {
  return (
      <div>
        <Routes>
          <Route path='/login' element={<Login />} />
          <Route path='/register' element={<Register />} />
          <Route path='/feed' element={<Feed /> } />
          <Route path='/create' element={<CreateBlurb /> } />
          <Route path='/profile' element={<Profile /> } />
          <Route path='/comments' element={<CommentFeed /> } />
          <Route path='*' element={<Login />} />
        </Routes>
      </div>
  );
}

