import React from "react";
import {Nav, Navbar} from 'react-bootstrap';
import {BrowserRouter, Route, Switch, useLocation, Link} from 'react-router-dom';


function Profile(){

    const location = useLocation();
    const uid = location.state.uid
    console.log('uid: ', uid)

    return(
        <div>
            <Navbar bg='dark' variant='dark'>
                <Navbar.Brand>Blurb</Navbar.Brand>
                <Nav className='m-auto' activeKey={location.pathname}>
                <Nav.Link as={Link}  to={'/feed'} state = {{uid}} >Feed</Nav.Link>
                <Nav.Link as={Link} to={'/create'} state = {{uid}} >Create A Blurb</Nav.Link> 
                <Nav.Link as={Link}  to={'/profile'} state = {{uid}} >Profile</Nav.Link>
                
                </Nav>
            </Navbar>

            <h1>This is the profile component</h1>

        </div>
    );
}


export default Profile;