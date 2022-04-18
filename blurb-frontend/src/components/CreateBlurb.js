import {React,useState, useEffect} from "react";
import InputGroup from 'react-bootstrap/InputGroup';
import Form from 'react-bootstrap/Form'
import {Button, useAccordionButton} from 'react-bootstrap'
import {Nav, Navbar} from 'react-bootstrap';
import {BrowserRouter, Route, Switch, useLocation, Link, useNavigate} from 'react-router-dom';
import axios from 'axios';


export default function CreateBlurb(){

    const location = useLocation()
    console.log('location: ', location);
    const uid = location.state.uid
    console.log('uid: ', uid)
    const [description, setDescription] = useState("");
    const [thread, setThread] = useState("");
    //const[userId, setUserId] = useState("");
    const navigate = useNavigate();


    /*useEffect( () =>{
        setUserId(location.state.uid);
        //console.log('location state: ', location.state.uid)
        console.log("this is the userid", userId)
        
        
    },[])*/


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
                <CreateBlurbForm key={uid} uid={uid} thread={thread} setThread={setThread} description={description} setDescription={setDescription}  navigate={navigate}/>
            </div>
            
        </div>
    );
}



function CreateBlurbForm({uid,thread,setThread, description, setDescription, navigate}){


    async function handleSubmit(event) {
       event.preventDefault();
       // console.log(userId);
        const date = new Date().toISOString().slice(0, 19).replace('T', ' ');
        //console.log("location", location);
        //const curDate = date.getFullYear() + '-' + date.getMonth() + '-' + date.getDay() + " " + date.getHours() + ":" +  date.getMinutes() + ":" + date.getSeconds();
        //console.log('curDate', curDate);
        //console.log('create blurb uid: ', userId)
        console.log('thread', thread);
        const data = {
            description: description,
            numberOfLikes: 0,
            uid: uid,
            date: date,
            thread: thread,

            
        }
        try {
            await axios.post("http://localhost/db-project/BlurbBackend/index.php/createBlurb", data).then(res => {
                console.log('happened');    
                navigate('/profile', { state: {uid: uid}});
            })
        } catch (error) {
            
        }
    }

    return (

        <div>
            <Form onSubmit={handleSubmit}>
                <Form.Group size="lg" controlId="Blurb">
                <Form.Label>Blurb</Form.Label>
                <Form.Control
                    autoFocus
                    type="text"
                    value={description}
                    onChange={(e) => setDescription(e.target.value)}
                />
                </Form.Group>
                <Form.Group size="lg" controlId="Thread">
                <Form.Label>Thread</Form.Label>
                <Form.Control
                    autoFocus
                    type="text"
                    value={thread}
                    onChange={(e) => setThread(e.target.value)}
                />
                </Form.Group>
                <div>
                    <Button block size="med" type="submit" >
                    Create Blurb
                    </Button>
                </div>
            </Form>
            
        </div>
    );
}