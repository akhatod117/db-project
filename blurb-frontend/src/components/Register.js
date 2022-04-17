import React, { useState } from "react";
import Form from "react-bootstrap/Form";
import Button from "react-bootstrap/Button";
import axios from "axios";
import "./Register.css";

//source code from https://serverless-stack.com/chapters/create-a-login-page.html




export default function Register() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [loggedIn, setLoggedIn] = useState("");


  function validateForm() {
    return email.length > 0 && password.length > 0;
  }
    //axios POST Request
  async function handleSubmit(event) {
    event.preventDefault();
    const data = {
        email: email,
        password: password
    }
    try {
        await axios.post("http://localhost/db-project/BlurbBackend/index.php/createUser", data).then(res => {

            setLoggedIn("It worked");

        })
    } catch (error) {
        setLoggedIn("did not work :(");   
    }
  }

  return (
    <div className="Register">
      <Form onSubmit={handleSubmit}>
        <Form.Group size="lg" controlId="email">
          <Form.Label>Email</Form.Label>
          <Form.Control
            autoFocus
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
          />
        </Form.Group>
        <Form.Group size="lg" controlId="password">
          <Form.Label>Password</Form.Label>
          <Form.Control
            type="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
          />
        </Form.Group>
        <div>
            <Button block size="med" type="submit" disabled={!validateForm()} >
            Register!
            </Button>
        </div>
      </Form>
      <div>{loggedIn}</div>
    </div>
  );
}