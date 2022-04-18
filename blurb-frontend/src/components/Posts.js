import React from "react";
import axios from 'axios';
import {useState, useEffect} from 'react';
import './Post.css';
import Button from "react-bootstrap/Button";
import Form from "react-bootstrap/Form";
import {Nav, Navbar} from 'react-bootstrap';
import {BrowserRouter, Route, Switch, useLocation, Link} from 'react-router-dom';




export default function Post({post, changedLikes, setLikes,uid}){
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
                        <Button variant="primary" onClick={incrementLikes}>Comments </Button>
                        </span>
                        
                    </div>
                    
                    </div>
            </div>
        
    );
}