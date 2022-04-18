import {React, useState, useEffect, useId} from "react";
import {Nav, Navbar} from 'react-bootstrap';
import {BrowserRouter, Route, Switch, useLocation, Link} from 'react-router-dom';
import axios from 'axios';
import Button from "react-bootstrap/Button";



function Profile(){

    const location = useLocation();
    console.log(location);
    const uid = location.state.uid
    console.log('uid: ', uid);
    const [userInfo, setUserInfo] = useState([]);
    let [posts,setPosts] = useState([]);


    useEffect( () =>{
        
        axios.get("http://localhost/db-project/BlurbBackend/index.php/getUserInfo/" + uid)
                .then(res=>{
                    const data = res.data
                    console.log(data)
                    setUserInfo(data)
        })
        axios.get("http://localhost/db-project/BlurbBackend/index.php/getUserPosts/" + uid)
                    .then(post=>{
                        console.log('here');
                        const userPosts= post.data
                        console.log(post.data);
                        //console.log(data)
                        setPosts(userPosts)
                        console.log(posts);
            }) 
        
    },[])


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
                Username: {userInfo.username} on {userInfo.tier} Tier
            </div>
            <div>
                Total User Likes: {userInfo.totalUserLikes}
            </div>
            <div>
                My Posts:
                <div>
                    {posts.length ? posts.map((p) => (
                        <Post key={p.uid + p.pid} post={p} uid={uid}/>
                    )): <p>No Posts</p>}
                </div>
            </div>

        </div>
    );
}


export default Profile;


function Post({post,uid}){
    //need: user-id, post text, post date, comments(add after posts are working)
    

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
                    </div>

                    <div>
                        <span>
                        <Button variant="primary">Comments </Button>
                        </span>
                        
                    </div>
                    
                    </div>
            </div>
        
    );
}