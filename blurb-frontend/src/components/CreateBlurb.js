import React from "react";
import InputGroup from 'react-bootstrap/InputGroup';
import Form from 'react-bootstrap/Form'
import {Button} from 'react-bootstrap'
import {Nav, Navbar} from 'react-bootstrap';
import {BrowserRouter, Route, Switch, useLocation, Link} from 'react-router-dom';

function CreateBlurb(){

    const location = useLocation()
    console.log('location: ', location);
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
            <div>
                <InputGroup className="mb-3">
                    <Form.Control
                    placeholder="Create a Blurb..."
                    aria-label="Create Blurb"
                    aria-describedby="basic-addon2"
                    />
                    <Button variant="primary" id="button-addon2">
                    Button
                    </Button>
                </InputGroup>
            </div>
        </div>
    );
}


export default CreateBlurb;