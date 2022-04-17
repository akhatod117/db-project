import React from "react";
import axios from 'axios';
import {useState, useEffect} from 'react';
import './Post.css';
import Button from "react-bootstrap/Button";
import Form from "react-bootstrap/Form";






export default function Feed(){

    let [posts,setPosts] = useState([]);
    let [changedLikes, setLikes] = useState(false);
    const [totalUsers,setTotalUsers] = useState(0);
    const [thread, setThread] = useState("");
    const[filterByThread, setFilterByThread] = useState(false);
    const[relatedTopics, setRelatedTopics] = useState([]);



    async function handleSubmit(event) {
        event.preventDefault();
        const data = {
            thread: thread
        }
        try {
            await axios.get("http://localhost/db-project/BlurbBackend/index.php/filterByThread/"+thread).then(res => {
    
                const data = res.data
                console.log(data)
                setPosts(data)
                setFilterByThread(true)
            })
        } catch (error) {
            
        }
      }

      useEffect( () =>{
        if(setFilterByThread){
            axios.get("http://localhost/db-project/BlurbBackend/index.php/getRelatedTopics/"+thread)
                .then(res=>{
                    const data = res.data
                    console.log(data)
                    setRelatedTopics(data)
            })
        }
        
        
    },[setFilterByThread])

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


        //<div></div>
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
                {posts.map((p) => (
                    <Post key={p.uid + p.pid} post={p} changedLikes = {changedLikes} setLikes = {setLikes}/>
                ))}
            </div>
            <div>
                Total Users: {totalUsers}
            </div>
        </div>
    );
}

function Post({post, changedLikes, setLikes}){
    //need: user-id, post text, post date, comments(add after posts are working)
    console.log(post);
    


    async function incrementLikes(){
        console.log('here');
        const data = {
            uid: post.uid,
            pid: post.pid,
            numberOfLikes: post.numberOfLikes
        }
        
        axios.post("http://localhost/db-project/BlurbBackend/index.php/incrementLikes", data)
                .then(res=>{
                setLikes(!changedLikes);
        })
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
                </div>

                <div>
                    <Button variant="primary" onClick={incrementLikes}>Like</Button>
                </div>
                
                </div>
        </div>

    );
}

