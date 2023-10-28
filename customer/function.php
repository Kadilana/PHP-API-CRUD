<?php

require "../inc/dbcon.php";

// error422
function error422($message)
{
    $data = [
        'status' => 422,
        'message' => $message,
    ];
    header("HTTP/1.0.405 Unprocessable Entity");
    echo json_encode($data);

    exit();
}

// insert customer data
function storeCustomer($customerInput)
{
    global $con;

    $name = mysqli_real_escape_string($con, $customerInput['name']);
    $email = mysqli_real_escape_string($con, $customerInput['email']);
    $phone = mysqli_real_escape_string($con, $customerInput['phone']);

    if(empty(trim($name)))
    {
        return error422('Enter your name');
    }
    elseif(empty(trim($email)))
    {
        return error422('Enter your email');
    }
    elseif(empty(trim($phone)))
    {
        return error422('Enter your phone');
    }
    else{
        $query = "INSERT INTO customers (name, email, phone) VALUES ('$name', '$email', '$phone')";
        $result = mysqli_query($con, $query);

        if($result)
        {
            $data = [
                'status' => 201,
                'message' => 'Customer created successfully',
            ];
            header("HTTP/1.0 201 Customer created successfully");
            return json_encode($data);
        }
        else
        {
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }
    }
}



// retrieves the customers data from the table
function getCustomerList() 
{

    global $con;

    $query = "SELECT * FROM customers";
    $query_run = mysqli_query($con, $query);

    if($query_run) 
    {
        if(mysqli_num_rows($query_run) > 0)
        {
            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'Customer List Fetched Successfully',
                'data' => $res
            ];
            header("HTTP/1.0 200 Success");
            return json_encode($data);

        }
        else
        {
            $data = [
                'status' => 404,
                'message' => 'No Cusotmer Found',
            ];
            header("HTTP/1.0 404 No Customer Found");
            return json_encode($data);
        }
    }
    else
    {
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }

}

//retrieve single record
function getCustomer($customerParams)
{
    global $con;

    if($customerParams['id'] == null)
    {
        return error422('Enter your customer id');
    }

    $customerId = mysqli_real_escape_string($con, $customerParams['id']);

    $query = "SELECT * FROM customers WHERE id='$customerId' LIMIT 1";
    $result = mysqli_query($con, $query);

    if($result)
    {
        if(mysqli_num_rows($result) == 1)
        {
            $res = mysqli_fetch_assoc($result);

            $data = [
                'status' => 200,
                'message' => 'Customer Fetched Successfully',
                'data' => $res
            ];
            header("HTTP/1.0 200 Ok");
            return json_encode($data);
        }
        else
        {
            $data = [
                'status' => 404,
                'message' => 'No customer found',
            ];
            header("HTTP/1.0 404 Not found");
            return json_encode($data);
        }
    }
    else
    {
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }
}

// update record
function updateCustomer($customerInput, $customerParams)
{
    global $con;

    if(!isset($customerParams['id']))
    {
        return error422('Customer id not found in URL');
    }
    elseif($customerParams['id'] == null)
    {
        return error422('Enter customer id');
    }

    $customerId = mysqli_real_escape_string($con, $customerParams['id']);

    $name = mysqli_real_escape_string($con, $customerInput['name']);
    $email = mysqli_real_escape_string($con, $customerInput['email']);
    $phone = mysqli_real_escape_string($con, $customerInput['phone']);

    if(empty(trim($name)))
    {
        return error422('Enter your name');
    }
    elseif(empty(trim($email)))
    {
        return error422('Enter your email');
    }
    elseif(empty(trim($phone)))
    {
        return error422('Enter your phone');
    }
    else{
        $query = "UPDATE customers SET name='$name', email='$email', phone='$phone' WHERE id='$customerId' LIMIT 1";
        $result = mysqli_query($con, $query);

        if($result)
        {
            $data = [
                'status' => 200,
                'message' => 'Customer updated successfully',
            ];
            header("HTTP/1.0 200 Customer updated successfully");
            return json_encode($data);
        }
        else
        {
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }
    }
}


// delete record
function deleteCustomer($customerParams)
{
    global $con;

    if(!isset($customerParams['id']))
    {
        return error422('Customer id not found in URL');
    }
    elseif($customerParams['id'] == null)
    {
        return error422('Enter customer id');
    }

    $customerId = mysqli_real_escape_string($con, $customerParams['id']);

    $query = "DELETE FROM customers WHERE id='$customerId' LIMIT 1";
    $result = mysqli_query($con, $query);

    if($result)
    {
        $data = [
            'status' => 200,
            'message' => 'Customer Deleted Successfully',
        ];
        header("HTTP/1.0 200 Ok");
        return json_encode($data);
    }
    else
    {
        $data = [
            'status' => 404,
            'message' => 'Customer not found',
        ];
        header("HTTP/1.0 404 Not found");
        return json_encode($data);
    }

}