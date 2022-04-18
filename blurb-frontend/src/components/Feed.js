import React from "react";
import axios from 'axios';
import {useState, useEffect} from 'react';
import './Post.css';
import Button from "react-bootstrap/Button";
import Form from "react-bootstrap/Form";
import {Nav, Navbar} from 'react-bootstrap';
import {BrowserRouter, Route, Switch, useLocation, Link, useNavigate} from 'react-router-dom';






export default function Feed(){

    let [posts,setPosts] = useState([]);
    let [changedLikes, setLikes] = useState(false);
    const [totalUsers,setTotalUsers] = useState(0);
    const [thread, setThread] = useState("");
    const[filterByThread, setFilterByThread] = useState(false);
    const[relatedTopics, setRelatedTopics] = useState([]);
    const location = useLocation();
    console.log("location: ", location);
    const uid = location.state.uid;
    console.log('uid: ', uid);
    const navigate = useNavigate();



    async function handleSubmit(event) {
        event.preventDefault();
        if(thread == ""){
            setLikes(!changedLikes);
        }else{

        
            const data = {
                thread: thread
            }
            try {
                await axios.get("http://localhost/db-project/BlurbBackend/index.php/filterByThread/"+thread).then(res => {
        
                    const data = res.data
                    setPosts(data)
                    setFilterByThread(true)
                    if(Object.keys(data).length === 0 ){
                        setLikes(!changedLikes);                    
                    }
                }).then(res=>{
                    axios.get("http://localhost/db-project/BlurbBackend/index.php/getRelatedTopics/"+thread)
                    .then(res=>{
                        const data = res.data
                        console.log("HERERERERE")
                        console.log(data)
                        setRelatedTopics(data)
                })
                })
            } catch (error) {
                setLikes(!changedLikes);
            }
        }
      }



    useEffect( () =>{
        axios.get("http://localhost/db-project/BlurbBackend/index.php/post")
                .then(res=>{
                    const data = res.data
                    console.log(data)
                    setPosts(data)
        }).then(res => {
            axios.get("http://localhost/db-project/BlurbBackend/index.php/getUsers")
                .then(total=>{
                    console.log('before total')
                    console.log(total.data.param1)
                    setTotalUsers(total.data.param1);
                })
            }
        )
        
    },[changedLikes])
    
    async function handleSort(){
        axios.get("http://localhost/db-project/BlurbBackend/index.php/postByLikes")
                .then(res=>{
                    const data = res.data
                    console.log(data)
                    setPosts(data)
        })
    }




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
            <Button variant='primary' onClick={handleSort}>Sort by Likes</Button>

            <Form onSubmit={handleSubmit}>
                <Form.Group size="lg" controlId="filter">
                <Form.Label>Filter by Thread</Form.Label>
                <Form.Control
                    autoFocus
                    type="filter"
                    value={thread}
                    onChange={(e) => setThread(e.target.value)}
                />
                </Form.Group>
            </Form>

            <div>
                Related Topics:
                <div>
                {relatedTopics.map((t) => (
                    <span>{t.relatedTopics}, </span>
                ))}
                </div>
            </div>
            
            
            <div>
                {posts.map((p) => (
                    <Post key={p.uid + p.pid} post={p} changedLikes = {changedLikes} setLikes = {setLikes} uid={uid} navigate = {navigate}/>
                ))}
            </div>
            <div>
                Total Users: {totalUsers}
            </div>
        </div>
    </div>
    );
}

function Post({post, changedLikes, setLikes,uid, navigate}){
    //need: user-id, post text, post date, comments(add after posts are working)
    


    async function incrementLikes(){
        const data = {
            uid: post.uid,
            pid: post.pid,
            likeUid: uid,
        }

        
        axios.post("http://localhost/db-project/BlurbBackend/index.php/incrementLikes", data)
                .then(res=>{
                setLikes(!changedLikes);
        })
    }

    async function handleComment(event) {
        event.preventDefault();
        navigate('/comment', { state: {post: post}})
      }

    return (
        
            <div className="post">
                <div className = "postWrapper">
                    <div className = "postTop">
                        <div className = 'postDescription'>
                            {post.description}
                        </div>

                        <div>
                            Post {post.pid}
                        </div>
                        
                    </div>

                    
                    <div className = 'userName'>
                        by {post.username} on {post.date}
                    </div>
                        
                    <div>
                        Liked by {post.numberOfLikes} others
                        <span>
                        <Button variant="primary" onClick={incrementLikes}>Like</Button>
                        </span>
                    </div>

                    <div>
                        <span>
                        <Button variant="primary" onClick={handleComment}>Comments </Button>
                        </span>
                        
                    </div>
                    
                    </div>
            </div>
        
    );
}

function Comment({comment}){
    //need: user-id, post text, post date, comments(add after posts are working)

    return (
        
            <div className="post">
                <div className = "postWrapper">
                    <div className = "postTop">
                        <div className = 'postDescription'>
                            {comment.commentText}
                        </div>
                        
                    </div>

                    
                    <div className = 'userName'>
                        by {comment.commenterUID} on {comment.date}
                    </div>
                    
                    </div>
            </div>
        
    );
}


