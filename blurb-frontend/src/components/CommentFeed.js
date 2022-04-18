import React from "react";
import axios from 'axios';
import {useState, useEffect} from 'react';
import './Post.css';
import Button from "react-bootstrap/Button";
import Form from "react-bootstrap/Form";
import {Nav, Navbar} from 'react-bootstrap';
import {BrowserRouter, Route, Switch, useLocation, Link, useNavigate} from 'react-router-dom';






export default function CommentFeed(){

    const [comment, setComment] = useState([]);
    const location = useLocation();
    console.log("location: ", location);
    const post = location.state.post;



    useEffect( () =>{
        const data = {
            uid: post.uid,
            pid: post.pid,
        }
        
        axios.post("http://localhost/db-project/BlurbBackend/index.php/getComments", data)
                .then(res=>{
                const data = res.data;
                setComment(data);
        })
        
    },[])
    


    return(

        <div>
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
                            <Button variant="primary" >Like</Button>
                            </span>
                        </div>

                        <div>
                            <span>
                            <Button variant="primary" >Comments </Button>
                            </span>
                            
                        </div>
                        
                        </div>
                </div>
                <div>
                    {comment.map((c) => (
                        <Comment key={c.uid + c.pid} comment={c}/>
                    ))}
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


