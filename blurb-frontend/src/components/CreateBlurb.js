import React from "react";
import InputGroup from 'react-bootstrap/InputGroup';
import Form from 'react-bootstrap/Form'
import {Button} from 'react-bootstrap'

function CreateBlurb(){

    return(
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
    );
}


export default CreateBlurb;